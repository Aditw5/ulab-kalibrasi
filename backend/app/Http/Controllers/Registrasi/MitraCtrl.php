<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Agama;

use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\JenisPegawai;
use App\Models\Master\Kebangsaan;
use App\Models\Master\Negara;
use App\Models\Master\Pasien;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Suku;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MitraCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listMitraGrid(Request $r)
    {
        $tglAwal = $r['dari'];
        $tglAkhir = $r['sampai'];
        $data  = DB::table('mitra_m as mt')
            ->leftjoin('mitraregistrasi_t as mtr', 'mtr.nomitrafk', '=', 'mt.id')
            ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->select(
                'mt.id',
                'mt.namaperusahaan',
                'mt.tgldaftar',
                'mt.email',
                'mt.nohp',
                'mt.foto',
                'mt.progress',
                'mtr.tglregistrasi',
                'mtr.petugas',
                'mtr.nopendaftaran',
                'mtr.lokasikalibrasi',
                'mtr.norec as iddetail',
            )
            ->where('mt.statusenabled', true)
            ->where('mtr.statusenabled', true)
            ->whereBetween('mtr.tglregistrasi', [$tglAwal, $tglAkhir]);
        $filter = false;
        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('mtr.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->orderByDesc('mtr.tglregistrasi');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);
       
        foreach ($data as $d) {
            $d->progress = $d->progress == null ? 0 : (int)  $d->progress;
            if ((int)  $d->progress <= 50) {
                $d->class_proggress = 'danger';
            }
            if ((int)  $d->progress > 50 && (int)  $d->progress <= 80) {
                $d->class_proggress = 'warning';
            }
            if ((int)  $d->progress > 80) {
                $d->class_proggress = 'success';
            }
        }
       
        return $this->respond($data);
    }

    public function HeaderMitra(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->leftJoin('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->select(
                'mt.id',
                'mt.namaperusahaan',
                'mt.tgldaftar',
                'mt.email',
                'mt.nohp',
                'mt.foto',
                'mt.progress',
                'mtr.tglregistrasi',
                'mtr.petugas',
                'mtr.nopendaftaran',
                'mtr.lokasikalibrasi',
                'mtr.norec as norec_pd',
            )
            ->where('mt.statusenabled', true)
            ->where('mt.id', $r['nocmfk'])
            ->where('mtr.norec', $r['norec_pd'])
            ->first();
       
        $result['mitra'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function LayananKajian(Request $r)
    {
            $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'prd.namaproduk',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtr.norec', $r['norec_pd']);

        if (isset($r['tglregistrasi']) && $r['tglregistrasi'] != '' && $r['tglregistrasi'] != 'undefined') {
            $data = $data->whereDate('mtr.tglregistrasi', '=', Carbon::parse($r['tglregistrasi'])->toDateString());
        } else {
            $data = $data->whereDate('mtr.tglregistrasi', '=', now()->toDateString());
        }

        $data = $data->orderByDesc('prd.namaproduk')->get();


        $result['length'] = count($data);
        $result['detail'] = $data; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function CountDaftar(Request $r)
    {
        $tglAwal = $r->dari . " 00:00:00";
        $tglAkhir = $r->sampai . " 23:59:59";

        $range = "";
        if ($tglAwal != '' && $tglAkhir != '' && $tglAwal != null && $tglAkhir != null) {
            $range = " and pd.tglregistrasi between '" . $tglAwal . "' and '" . $tglAkhir . "'";
        }
        $data = DB::select(DB::raw("SELECT COUNT
        ( kp.kelompokpasien ) AS jmlkp , kp.kelompokpasien, kp.id
    FROM
        pasien_m AS ps
        INNER JOIN alamat_m AS alm ON alm.nocmfk = ps.id
        LEFT JOIN pasiendaftar_t AS pd ON ps.id = pd.nocmfk
        AND pd.statusenabled = true
        AND pd.norec IS NOT NULL
        LEFT JOIN antrianpasienregistrasi_t AS apr ON apr.norec = pd.antrianpasienregistrasifk
        LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
        LEFT JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.id
        LEFT JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.id
        LEFT JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
    WHERE
        ps.statusenabled = true
        $range
        AND ps.kdprofile = 1
        AND pd.norec IS NOT NULL
        GROUP BY  kp.kelompokpasien, kp.id"));

        $data2 = DB::select(DB::raw("SELECT COUNT( ru.objectdepartemenfk ) AS jmldp , dp.namadepartemen, dp.id
        FROM pasien_m AS ps INNER JOIN alamat_m AS alm ON alm.nocmfk = ps.id LEFT JOIN pasiendaftar_t AS pd ON ps.id = pd.nocmfk
        AND pd.statusenabled = true
        AND pd.norec IS NOT NULL
        LEFT JOIN antrianpasienregistrasi_t AS apr ON apr.norec = pd.antrianpasienregistrasifk
        LEFT JOIN pemakaianasuransi_t AS pa ON pa.noregistrasifk = pd.norec
        LEFT JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.id
        LEFT JOIN departemen_m AS dp ON ru.objectdepartemenfk = dp.id
        LEFT JOIN pegawai_m AS pg ON pd.objectpegawaifk = pg.id
        LEFT JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
        WHERE
        ps.statusenabled = true
        $range
        AND ps.kdprofile = 1
        AND pd.norec IS NOT NULL
        GROUP BY  dp.namadepartemen, dp.id"));

        $res['kelompokpasien'] = $data;
        $res['departemen'] = $data2;

        return $this->respond($res);
    }

    public function saveBatalStatusKanker(Request $request)
    {
        $request->validate([
            'norec_pasien' => 'required', //memastikan requestnya ada
        ]);
        DB::beginTransaction();
        try {
            Pasien::where('id', $request['norec_pasien'])->where('kdprofile', $this->kdProfile)->update([
                'iskanker' => false
            ]);

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $transMessage
            );
        } else {
            $transMessage = "Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' - ' . $e->getFile()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveJadikanStatusKanker(Request $request)
    {
        $request->validate([
            'norec_pasien' => 'required', //memastikan requestnya ada
        ]);
        DB::beginTransaction();
        try {
            Pasien::where('id', $request['norec_pasien'])->where('kdprofile', $this->kdProfile)->update([
                'iskanker' => true
            ]);

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $transMessage
            );
        } else {
            $transMessage = "Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' - ' . $e->getFile()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataPasienKanker() {
        $data = DB::table('pasien_m as ps')
        ->select(
            'ps.namapasien',
            'ps.nocm'
        )
        ->where('ps.kdprofile', $this->kdProfile)
        ->where('ps.statusenabled', true)
        ->where('ps.iskanker', true)
        ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
}
