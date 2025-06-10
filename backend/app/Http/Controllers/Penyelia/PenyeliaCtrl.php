<?php

namespace App\Http\Controllers\Penyelia;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;
use App\Models\Transaksi\LembarKerja;

class PenyeliaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getAlatPenyelia(Request $r)
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
            ->where('pg.id', $this->getPegawaiId())
            ->where('mtr.statusorder', 1)
            ->where('mtr.iskaji', true)
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.statusenabled', true);

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("mtrd.tglverifasman::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("mtrd.tglverifasman::date"), '<=', $r->sampai);
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
        if (isset($r['statusorderpenyelia']) && $r['statusorderpenyelia'] != '') {
            $data = $data->where('mtrd.statusorderpenyelia', '=', $r['statusorderpenyelia']);
        }
        // if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
        //     if ($r['statuspanggil'] == 'true') {
        //         $data = $data->whereNotNull('pd.tglpulang');
        //     } elseif ($r['statuspanggil'] == 'rawat') {
        //         $data = $data->whereNull('pd.tglpulang')
        //                      ->whereNull('apd.tglkeluar');
        //     }
        // }
        $data = $data->orderBy('mtr.tglregistrasi');
        if (isset($r['limit'])) {
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function LayananVerifPenyelia(Request $r)
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
            ->where('pg.id', $this->getPegawaiId())
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtr.norec', $r['norec_pd']);

        if (isset($r['norecdetail']) && $r['norecdetail'] != "" && $r['norecdetail'] != "undefined") {
            $data = $data->where('mtrd.norec', '=', $r['norecdetail']);
        };

        $data = $data->orderByDesc('prd.namaproduk')->get();


        $result['length'] = count($data);
        $result['detail'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
    }

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

    public function saveVerif(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['verif'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'statusorderpenyelia' => 1,
                    'penyeliaveriffk' => $this->getPegawaiId(),
                    'tglverifpenyelia' => now(),
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
            ->leftJoin('pegawai_m as pg4', 'pg4.id', '=', 'mtrd.pelaksanaisilembarkerjafk')
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
                'pg4.id as pelaksanaisilembarkerjafk',
                'pg4.namalengkap as pelaksanaisilembarkerja',
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

    public function getLembarKerjaPenyelia(Request $request)
    {
        $data = DB::table('lembarkerja_t as lk')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
            ->select('lk.*')
            ->where('mtrd.norec', $request->norecdetail)
            ->where('lk.statusenabled', true)
            ->get();

        return $this->respond($data);
    }

    public function downloadTemplateLembarKerjaPenyelia(Request $request)
    {

        $pathbundle = 'import/lembarkerja/template_lembar_kerja.xlsx';
        $name = 'Template Lembar Kerja.xlsx';
        $path =  public_path($pathbundle);
        if (File::exists($path)) {
            $file = File::get($path);

            $type = File::mimeType($path);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type)
                ->header('Content-disposition', 'attachment; filename="' . $name . '"');
            return $response;
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
        }
    }

    public function simpanUploadLembaKerjaPenyelia(Request $request)
    {
        DB::beginTransaction();
        try {
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $request['norec_detail'])
                ->update([
                    'penyeliaisilembarkerjafk' => $this->getPegawaiId(),
                    'tglisilembarkerjapenyelia' => now(),
                ]);

            $result = [];
            LembarKerja::where('detailregistraifk', $request['norec_detail'])->delete();
            foreach ($request['data'] as $item) {
                if ($item['isGroupHeader'] === true) {
                    continue;
                }
                $data1 = new LembarKerja();
                $data1->norec = $data1->generateNewId();
                $data1->statusenabled = true;

                $data1->detailregistraifk = $request['norec_detail'];
                $data1->group = $item['group'];
                $data1->rentang = $item['rentang'];
                $data1->rentang_satuan = $item['rentang_satuan'];
                $data1->penunjukan_standar = $item['penunjukan_standar'];
                $data1->penunjukan_standar_satuan = $item['penunjukan_standar_satuan'];
                $data1->penunjukan_standar_2 = $item['penunjukan_standar_2'];
                $data1->penunjukan_standar_satuan_2 = $item['penunjukan_standar_satuan_2'];
                $data1->pembacaan_alat = $item['pembacaan_alat'];
                $data1->pembacaan_alat_satuan = $item['pembacaan_alat_satuan'];
                $data1->koreksi = $item['koreksi'];
                $data1->koreksi_satuan = $item['koreksi_satuan'];
                $data1->ketidakpastian = $item['ketidakpastian'];
                $data1->ketidakpastian_standar = $item['ketidakpastian_standar'];
                $data1->excelfilename = $request['fileName'];
                $data1->tglupload = now();
                $data1->pengisifk = $this->getPegawaiId();
                $data1->save();
                $result[] = $data1;
            }
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "Simpan Lembar Kerja";
        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "status" => 201,
                "message" => $transMessage . ' Berhasil',
                "result" => $result
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage . ' Gagal' . $e->getMessage(),
                "result" => $result
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function detailProdukLembarKerjaPenyelia(Request $r)
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
                'prd.namaproduk',
                'mtr.catatan',
                'mrk.id as idmerk',
                'mrk.namamerk',
                'tp.id as idtipe',
                'tp.namatipe',
                'sn.id as idsn',
                'sn.namaserialnumber',
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

        $result = [
            'length' => count($data),
            'data' => $data,
            'as' => '@adit'
        ];

        return $this->respond($result);
    }
}
