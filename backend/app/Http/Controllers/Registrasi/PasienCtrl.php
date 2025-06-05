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
use Illuminate\Support\Facades\DB;

class PasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listPasienGrid(Request $r)
    {
        $count = 0;
        $data  = DB::table('pasien_m as ps')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('pasiendaftar_t as pd', function ($join) use ($r) {
                $dari = date('Y-m-d 00:00:00');
                $sampai = date('Y-m-d 23:59:59');
                $join->on('ps.id', '=', 'pd.nocmfk');
                $join->where('pd.statusenabled', true);
                if (isset($r['pasien_aktif']) && $r['pasien_aktif'] != '' && $r['pasien_aktif'] == 'true') {
                    $join->whereNotNull('pd.norec');
                    if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
                        $join->whereBetween('pd.tglregistrasi', [$r['dari'],  $r['sampai']]);
                    }
                } else {
                    $join->whereRaw("(
                        pd.tglregistrasi between '$dari' and '$sampai'
                        or pd.tglpulang is null
                    )");
                }
                // $join->OrderByDesc('pd.tglregistrasi');
            })
            // ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
            //     $join->on('apd.noregistrasifk', '=', 'pd.norec');
            //     $join->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            //     // $join->whereNull('apd.objectruanganasalfk')  ;
            // })
            ->leftjoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('departemen_m as dp', 'ru.objectdepartemenfk', '=', 'dp.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('ruangan_m as rupes', 'pd.ruangannextschedule', '=', 'rupes.id')
            ->select(
                'ps.id',
                'ps.namapasien',
                'pd.iskronis as status_kronis',
                'ps.nocm',
                'pd.catatan',
                'ps.noidentitas',
                'ps.nohp',
                'ps.nobpjs',
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'ps.tempatlahir',
                'ps.tgllahir',
                'ps.tglmeninggal',
                'ps.namaibu',
                'ps.progress',
                'ru.namaruangan',
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.isselesai',
                'ps.foto',
                'pg.namalengkap as dokter',
                'pd.objectkelompokpasienlastfk',
                'ru.objectdepartemenfk',
                'kp.kelompokpasien',
                'ps.id as nocmfk',
                'pd.ruangannextschedule',
                'rupes.namaruangan as namaruanganpesanan',
                // 'apd.norec as norec_apd',
                'pa.nosep',
                'pd.bahasa',
                'pd.bantuanpelayanan',
                'pd.bantuanpenerjemah',
                'pd.dikunjungi',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'apr.noreservasi',
                'dp.namadepartemen',
                'pd.objectruanganlastfk'
            )
            ->where('ps.statusenabled', true)
            ->where('ps.kdprofile', $this->kdProfile);
        $filter = false;
        if (isset($r['_total']) && $r['_total'] != '') {
            $filter = true;
            $count = $data->count();
        }
        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('pa.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['id']) && $r['id'] != '') {
            $filter = true;
            $data = $data->where('ps.id', '=',  $r['id']);
        }
        if (isset($r['pasien_aktif']) && $r['pasien_aktif'] != '' && $r['pasien_aktif'] == 'true') {
            $filter = true;
            $data = $data->whereNotNull('pd.norec');
        } else {
            $data = $data->whereNull('pd.norec');
        }
        if (isset($r['pasien_kronis']) && $r['pasien_kronis'] != '' && $r['pasien_kronis'] == 'true') {
            $filter = true;
            $data = $data->whereNotNull('pd.iskronis');
        }
        if (isset($r['pasien_selesai']) && $r['pasien_selesai'] != '' && $r['pasien_selesai'] == 'true') {
            $filter = true;
            $data = $data->where('pd.isselesai', true);
        }
        if (isset($r['q']) && $r['q'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['q'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $filter = true;
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noregistrasi'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $filter = true;
            $data = $data->where('ps.noidentitas', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $filter = true;
            $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['alamat']) && $r['alamat'] != '') {
            $filter = true;
            $data = $data->where('alm.alamatlengkap', '=',  $r['alamat']);
        }
        if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != '') {
            $filter = true;
            $data = $data->whereIN('pd.objectkelompokpasienlastfk',  explode(',', $r['kelompokpasienfk']));
        }
        if (isset($r['instalasifk']) && $r['instalasifk'] != '') {
            $filter = true;
            $data = $data->whereIN('ru.objectdepartemenfk', explode(',', $r['instalasifk']));
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $filter = true;
            $data = $data->where('ru.id',  $r['ruanganfk']);
        }


        // if (isset($r['offset']) && $r['offset'] != '') {
        //     $data = $data->offset($r['offset']);
        // }
        // if (isset($r['limit']) && $r['limit'] != '') {
        //     $data = $data->limit($r['limit']);
        // }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }

        // $data = $data->orderByDesc('ps.nocm');
        $data = $data->orderByDesc('pd.tglregistrasi');
        // $data = $data->get();
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);
        // $data = $data->orderByDesc('ps.id');
        // $data = $data->get();
        // dd(DB::getQueryLog());
        foreach ($data as $d) {
            $d->tgllahir = date('Y-m-d', strtotime($d->tgllahir));
            $d->umur =  $this->getAge($d->tgllahir,   date('Y-m-d H:i:s'));
            $d->status = 'Hidup';
            $d->status_c = 'purple';
            if ($d->tglmeninggal != null) {
                $d->status = 'Meninggal';
                $d->status_c = 'danger';
            }
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
        // if(count($data)>0){
        //     $price = array();
        //     $data = $data->toArray();
        //     foreach ($data as $key => $row)
        //     {
        //         $price[$key] = $row->tglregistrasi;
        //     }
        //     array_multisort($price, SORT_DESC, $data);

        // }
        // $res['data'] = $data;
        // $res['total'] = $count;
        return $this->respond($data);
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
