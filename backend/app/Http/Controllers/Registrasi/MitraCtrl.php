<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

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
            ->where('mtr.statusenabled', true)
            ->where('mt.id', $r['nocmfk'])
            ->where('mtr.norec', $r['norec_pd'])
            ->first();

        $result['mitra'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function pegawaiManager(Request $r)
    {
        $data = DB::table('pegawai_m as pg')
            ->select(
                'pg.id',
                'pg.namalengkap',
                'pg.lokasikalibrasifk',
            )
            ->where('pg.statusenabled', true);

        if (isset($r['jabatan']) && $r['jabatan'] != "" && $r['jabatan'] != "undefined") {
            $data = $data->where('pg.jabatan1fk', '=', $r['jabatan']);
        };

        if (isset($r['lokasi']) && $r['lokasi'] != "" && $r['lokasi'] != "undefined") {
            $data = $data->where('pg.lokasikalibrasifk', '=', $r['lokasi']);
        };
        $data = $data->first();


        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function pegawaiLokasi(Request $r)
    {
        $search = $r['query'];
        $data = DB::table('pegawai_m as pg')
            ->select(
                'pg.id',
                'pg.namalengkap',
                'pg.lokasikalibrasifk',
            )
            ->where('pg.statusenabled', true);

        if (isset($r['lokasi']) && $r['lokasi'] != "" && $r['lokasi'] != "undefined") {
            $data = $data->where('pg.lokasikalibrasifk', '=', $r['lokasi']);
        };
        if (isset($r['param_search']) && $r['param_search'] != '') {
            $exp = explode(',', $r['param_search']);
            foreach ($exp as $items) {
                $where[] = [$items, 'ILIKE', '%' . $search . '%'];
            }
            $data = $data->where($where);
        }
        $data = $data->get();


        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function LayananKajian(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftjoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftjoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftjoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftjoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.namafile',
                'mtrd.keterangan',
                'prd.namaproduk',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mrk.id as idmerk',
                'mrk.namamerk',
                'tp.id as idtipe',
                'tp.namatipe',
                'sn.id as idsn',
                'sn.namaserialnumber',
                'mt.namaperusahaan',
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtr.norec', $r['norec_pd']);

        // if (isset($r['tglregistrasi']) && $r['tglregistrasi'] != '' && $r['tglregistrasi'] != 'undefined') {
        //     $data = $data->whereDate('mtr.tglregistrasi', '=', Carbon::parse($r['tglregistrasi'])->toDateString());
        // } else {
        //     $data = $data->whereDate('mtr.tglregistrasi', '=', now()->toDateString());
        // }

        if (isset($r['norecdetail']) && $r['norecdetail'] != "" && $r['norecdetail'] != "undefined") {
            $data = $data->where('mtrd.norec', '=', $r['norecdetail']);
        };

        $data = $data->orderByDesc('prd.namaproduk')->get();


        $result['length'] = count($data);
        $result['detail'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function saveKajianUlang(Request $r)
    {
        DB::beginTransaction();
        try {
            $file = $r->file('fileMitra');
            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, $allowedExtensions)) {
                throw new \Exception("File harus berupa gambar (jpg, jpeg, atau png).");
            }
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('berkas-mitra'), $filename);

            DB::table('mitraregistrasidetail_t')
                ->where('norec', $r->norec)
                ->update([
                    'keterangan' => $r->keterangan,
                    // 'tanggalkaji' => $r->tanggalKajian,
                    'lokasikajifk' => $r->lokasikalibrasi,
                    'lingkupkalibrasifk' => $r->lingkupkalibrasi,
                    'penyeliateknikfk' => $r->penyeliateknik,
                    'pelaksanateknikfk' => $r->pelaksana,
                    'iskaji' => true,
                    'namafile' => $filename,
                    'updated_at' => now()
                ]);

            $transMessage = "Simpan Kajian Ulang Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@epic',
                    "namafile" => $filename,
                ],
            ];
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = [
                "status" => 400,
                "result"  => $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveBatalRegis(Request $request)
    {
        DB::beginTransaction();
        try {
            $r_NewMitra = $request['mitraregis'];
            $dataMitra = DB::table('mitraregistrasi_t')
                ->where('norec', $r_NewMitra['norecregis'])
                ->where('statusenabled', true)
                ->first();

            if (!$dataMitra) {
                throw new \Exception("Data registrasi tidak ditemukan atau sudah tidak aktif");
            }

            $norecMitra = $dataMitra->norec;
            DB::table('mitraregistrasidetail_t')
                ->where('noregistrasifk', $norecMitra)
                ->update(['statusenabled' => false]);

            DB::table('mitraregistrasi_t')
                ->where('norec', $norecMitra)
                ->update([
                    'statusenabled' => false,
                    'tanggalpembatalan' => $r_NewMitra['tanggalpembatalan'] ?? null,
                    'alasanpembatalan' => $r_NewMitra['alasanpembatalan'] ?? null 
                ]);

            $message = 'Berhasil Batal Registrasi';

            DB::commit();

            $result = array(
                "status" => 200,
                "message" => $message,
                "result" => array(
                    "as" => '@adit',
                )
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . ' | Line: ' . $e->getLine()
            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
