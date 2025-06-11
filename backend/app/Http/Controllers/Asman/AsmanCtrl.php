<?php

namespace App\Http\Controllers\Asman;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class AsmanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function listMitraAsmanGrid(Request $r)
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
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderpenyelia',
                'mtrd.statusorderasman',
                'mtrd.tglisilembarkerjapelaksana',
                'mtrd.pelaksanaisilembarkerjafk',
                'mtrd.tglverifasman',
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
            ->where('mtrd.statusorderpenyelia', 1)
            ->where('mtr.iskaji', true)
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true);

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("mtrd.tglverifpenyelia::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("mtrd.tglverifpenyelia::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('mt.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        if (isset($r['statusorder']) && $r['statusorder'] != '') {
            $data = $data->where('mtrd.statusorderasman', '=', $r['statusorder']);
        }
        // if (isset($r['statusorderpenyelia']) && $r['statusorderpenyelia'] != '') {
        //     $data = $data->where('mtrd.statusorderpenyelia', '=', $r['statusorderpenyelia']);
        // }

        $data = $data->orderBy('mtr.tglregistrasi');
        if (isset($r['limit'])) {
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    // public function listMitraAsmanGrid(Request $r)
    // {
    //     $tglAwal = $r['dari'];
    //     $tglAkhir = $r['sampai'];
    //     $data  = DB::table('mitra_m as mt')
    //         ->leftjoin('mitraregistrasi_t as mtr', 'mtr.nomitrafk', '=', 'mt.id')
    //         ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
    //         ->select(
    //             'mt.id',
    //             'mt.namaperusahaan',
    //             'mt.tgldaftar',
    //             'mt.email',
    //             'mt.nohp',
    //             'mt.foto',
    //             'mt.progress',
    //             'mtr.tglregistrasi',
    //             'mtr.petugas',
    //             'mtr.nopendaftaran',
    //             'mtr.lokasikalibrasi',
    //             'mtr.norec as iddetail',
    //             'mtr.statusorder',
    //         )
    //         ->where('mt.statusenabled', true)
    //         ->where('mtr.statusenabled', true)
    //         ->where('mtr.iskaji', true)
    //         ->whereBetween('mtr.tglregistrasi', [$tglAwal, $tglAkhir]);
    //     $filter = false;
    //     if (isset($r['search']) && $r['search'] != '') {
    //         $filter = true;
    //         $searchTerm = '%' . $r['search'] . '%';
    //         $data = $data->where(function ($query) use ($searchTerm) {
    //             $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
    //                 ->orWhere('mtr.nopendaftaran', 'ilike', $searchTerm);
    //         });
    //     }
    //     if (isset($r['statusorder']) && $r['statusorder'] != '') {
    //         $data = $data->where('mtr.statusorder', '=', $r['statusorder']);
    //     }
    //     $page = 1;
    //     if (isset($r['page']) && $r['page'] != '') {
    //         $page = $r['page'];
    //     }
    //     $data = $data->orderByDesc('mtr.tglregistrasi');
    //     $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

    //     foreach ($data as $d) {
    //         $d->progress = $d->progress == null ? 0 : (int)  $d->progress;
    //         if ((int)  $d->progress <= 50) {
    //             $d->class_proggress = 'danger';
    //         }
    //         if ((int)  $d->progress > 50 && (int)  $d->progress <= 80) {
    //             $d->class_proggress = 'warning';
    //         }
    //         if ((int)  $d->progress > 80) {
    //             $d->class_proggress = 'success';
    //         }
    //     }

    //     return $this->respond($data);
    // }

    public function getAsmanDetail(Request $r)
    {
        $jabatanIds = [17, 20, 3, 7, 8, 4, 19, 9, 5, 18, 13, 15, 12, 16, 14];

        $pegawaiJakarta = DB::table('pegawai_m as pg')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->select(
                'pg.id as pegawai_id',
                'pg.namalengkap',
                'pg.jabatan1fk',
                'jb.id as jabatan_id',
                'jb.namajabatanulab'
            )
            ->where('pg.statusenabled', true)
            ->whereIn('jb.id', $jabatanIds)
            ->where('pg.lokasikalibrasifk', 1)
            ->where('jb.statusenabled', true);

        if (isset($r['limit']) && $r['limit'] != '') {
            $pegawaiJakarta->limit($r['limit']);
        }

        $pegawaiJakarta->orderBy('jb.namajabatanulab');
        $pegawaiJakarta = $pegawaiJakarta->get();

        $pegawaiGresik = DB::table('pegawai_m as pg')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->select(
                'pg.id as pegawai_id',
                'pg.namalengkap',
                'pg.jabatan1fk',
                'jb.id as jabatan_id',
                'jb.namajabatanulab'
            )
            ->where('pg.statusenabled', true)
            ->whereIn('jb.id', $jabatanIds)
            ->where('pg.lokasikalibrasifk', 2)
            ->where('jb.statusenabled', true);

        if (isset($r['limit']) && $r['limit'] != '') {
            $pegawaiGresik->limit($r['limit']);
        }

        $pegawaiGresik->orderBy('jb.namajabatanulab');
        $pegawaiGresik = $pegawaiGresik->get();

        $res['pegawaiJakarta'] = $pegawaiJakarta;
        $res['pegawaiGresik'] = $pegawaiGresik;
        return $this->respond($res);
    }

    public function LayananVerif(Request $r)
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
                'mtrd.durasikalbrasi',
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
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_pd']);

        $data = $data->orderByDesc('prd.namaproduk')->get();


        $result['length'] = count($data);
        $result['detail'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
    }

    public function detailProduk(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtrd.asmanveriffk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftJoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderasman',
                'mtrd.statusorderpenyelia',
                'mtrd.statusorderpelaksana',
                'mtrd.tglverifasman',
                'mtrd.tglverifpenyelia',
                'mtrd.tglverifpelaksana',
                'mtrd.tglisilembarkerjapelaksana',
                'mtrd.pelaksanaisilembarkerjafk',
                'mtrd.tglisilembarkerjapenyelia',
                'mtrd.penyeliaisilembarkerjafk',
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
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_pd'])
            ->orderByDesc('prd.namaproduk')
            ->get();

        $timeline = [];

        foreach ($data as $item) {
            if (!is_null($item->tglverifasman)) {
                $timeline[] = [
                    'date' => $item->tglverifasman,
                    'type' => 'Diverifikasi Oleh Asman',
                    'nama' => $item->asamanverifikasi ?? '-',
                ];
            }
            if (!is_null($item->tglverifpenyelia)) {
                $timeline[] = [
                    'date' => $item->tglverifpenyelia,
                    'type' => 'Diverifikasi Oleh Penyelia Teknik',
                    'nama' => $item->penyeliateknik ?? '-',
                ];
            }
            if (!is_null($item->tglverifpelaksana)) {
                $timeline[] = [
                    'date' => $item->tglverifpelaksana,
                    'type' => 'Diverifikasi Oleh Pelaksana Teknik',
                    'nama' => $item->pelaksanateknik ?? '-',
                ];
            }
            if (!is_null($item->tglisilembarkerjapelaksana)) {
                $timeline[] = [
                    'date' => $item->tglisilembarkerjapelaksana,
                    'type' => 'Diisi Lembar Kerja Oleh Pelaksana Teknik',
                    'nama' => $item->pelaksanateknik ?? '-',
                ];
            }
            if (!is_null($item->tglisilembarkerjapenyelia)) {
                $timeline[] = [
                    'date' => $item->tglisilembarkerjapenyelia,
                    'type' => 'Diisi Lembar Kerja Oleh Penyelia Teknik',
                    'nama' => $item->penyeliateknik ?? '-',
                ];
            }
        }

        usort($timeline, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        $result = [
            'length' => count($data),
            'timeline' => $timeline,
            'as' => '@adit'
        ];

        return $this->respond($result);
    }

    public function saveVerifItem(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['veriItem'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'iskaji' => true,
                    'lokasikajifk' => $VI['lokasikalibrasi'],
                    'lingkupkalibrasifk' => $VI['lingkupkalibrasi'],
                    'penyeliateknikfk' => $VI['penyeliateknik'],
                    'pelaksanateknikfk' => $VI['pelaksana'],
                    'durasikalbrasi' => $VI['durasikalbrasi'],
                    'asmanveriffk' => $this->getPegawaiId(),
                    'tglverifasman' => now(),
                    'statusorderasman' => 1,
                    'statusorderpelaksana' => 0,
                    'statusorderpenyelia' => 0,
                ]);

            $transMessage = "Simpan Verif Item Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@adit',
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

    public function saveVerif(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['verif'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'statusorderasman' => 1,
                    'asmanveriffk' => $this->getPegawaiId(),
                    'tglverifasman' => now(),
                ]);

            $transMessage = "Simpan Verif Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@adit',
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

    public function savePenolakanAsman(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['mitraregis'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'statusorderasman' => 2,
                    'asmanveriffk' => $this->getPegawaiId(),
                    'alasanpenolakanasman' => $VI['alasanpenolakanasman'],
                    'tglverifasman' => now(),
                ]);

            $transMessage = "Simpan Penolakan Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@adit',
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
            // ->where('mt.id', $r['nocmfk'])
            ->where('mtr.norec', $r['norec_pd'])
            ->first();

        $result['mitra'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
}
