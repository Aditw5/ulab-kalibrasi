<?php

namespace App\Http\Controllers\Pelaksana;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\File;
use App\Models\Transaksi\LembarKerja;
use App\Models\Transaksi\DaftarAlatStandar;
use App\Models\Transaksi\DaftarInstruksiKerja;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\Snappy\Facades\SnappyPdf;

class PelaksanaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getAlatPelaksana(Request $r)
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
            ->leftjoin('pegawai_m as pg3', 'pg3.id', '=', 'mtrd.pelaksanaisilembarkerjafk')
            ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftjoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderpelaksana',
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
                'pg3.id as pelaksanaisilembarkerjafk',
                'pg3.namalengkap as pelaksanaisilembarkerja',
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
            )
            ->where('pg2.id', $this->getPegawaiId())
            ->where('mtr.statusorder', 1)
            ->where('mtr.iskaji', true)
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.statusenabled', true);

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("mtr.tglverifasman::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("mtr.tglverifasman::date"), '<=', $r->sampai);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('prd.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('mt.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        if (isset($r['statusorderpelaksana']) && $r['statusorderpelaksana'] != '') {
            $data = $data->where('mtrd.statusorderpelaksana', '=', $r['statusorderpelaksana']);
        }
        $data = $data->orderBy('mtr.tglregistrasi');
        if (isset($r['limit'])) {
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function LayananVerifPelaksana(Request $r)
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
            ->where('pg2.id', $this->getPegawaiId())
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
                    'statusorderpelaksana' => 1,
                    'pelaksanaveriffk' => $this->getPegawaiId(),
                    'tglverifpelaksana' => now(),
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
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtr.asmanveriffk')
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
                'mtr.tglverifasman',
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

    public function getLembarKerja(Request $request)
    {
        $data = DB::table('lembarkerja_t as lk')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
            ->join('mitraregistrasi_t as mtr', 'mtr.norec', '=', 'mtrd.noregistrasifk')
            ->select('lk.*', 'mtr.norec as norecregis')
            ->where('mtrd.norec', $request->norecdetail)
            ->where('lk.statusenabled', true)
            ->get();

        return $this->respond($data);
    }

    public function downloadTemplateLembarKerja(Request $request)
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

    public function simpanUploadLembaKerja(Request $request)
    {
        DB::beginTransaction();
        // try {
        $DIK = $request['daftarinstruksikerja'];
        $DAS = $request['daftarperalatanstandar'];
        DB::table('mitraregistrasidetail_t')
            ->where('norec', $request['norec_detail'])
            ->update([
                'pelaksanaisilembarkerjafk' => $this->getPegawaiId(),
                'tglisilembarkerjapelaksana' => now(),
                'tglkalibrasilembarkerja' => $request['tglkalibrasi'],
                'tempatKalibrasilembarkerja' => $request['tempatKalibrasi'],
                'kondisiRuanganlembarkerja' => $request['kondisiRuangan'],
                'suhulembarkerja' => $request['suhu'],
                'kelembabanRelatiflembarkerja' => $request['kelembabanRelatif'],
            ]);

        $dataInstruksi = [];
        DaftarInstruksiKerja::where('detailregistrasifk', $request['norec_detail'])->delete();
        foreach ($DIK as $instruksi) {
            $model_Instruksi = new DaftarInstruksiKerja;
            $model_Instruksi->norec = $model_Instruksi->generateNewId();
            $model_Instruksi->statusenabled = true;
            $model_Instruksi->idalatinstruksikerja = $instruksi['instruksikerja'];
            $model_Instruksi->detailregistrasifk = $request['norec_detail'];
            $model_Instruksi->petugas = $this->getPegawaiId();
            $model_Instruksi->save();
            $dataInstruksi[] = $model_Instruksi;
        }

        $dataAlatStandar = [];
        DaftarAlatStandar::where('detailregistrasifk', $request['norec_detail'])->delete();
        foreach ($DAS as $standar) {
            $model_Standar = new DaftarAlatStandar;
            $model_Standar->norec = $model_Standar->generateNewId();
            $model_Standar->statusenabled = true;
            $model_Standar->alatstandarfk = $standar['peralatanstandar'];
            $model_Standar->merkstandarfk = $standar['merkalatstandar'];
            $model_Standar->tipestandarfk = $standar['tipealatstandar'];
            $model_Standar->snstandarfk = $standar['serialalatstandar'];
            $model_Standar->detailregistrasifk = $request['norec_detail'];
            $model_Standar->petugas = $this->getPegawaiId();
            $model_Standar->save();
            $dataAlatStandar[] = $model_Standar;
        }

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
        // } catch (Exception $e) {
        //     $transStatus = 'false';
        // }

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

    public function detailProdukLembarKeerja(Request $r)
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
                'mtrd.tglkalibrasilembarkerja',
                'mtrd.tempatKalibrasilembarkerja',
                'mtrd.kondisiRuanganlembarkerja',
                'mtrd.suhulembarkerja',
                'mtrd.kelembabanRelatiflembarkerja',
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

        $instruksiKerja = DB::table('daftarinstruksikerja_t as dik')
            ->leftJoin('instruksikerja_m as ik', 'ik.id', '=', 'dik.idalatinstruksikerja')
            ->select('dik.detailregistrasifk', 'ik.id as value', 'ik.namainstruksikerja as label')
            ->where('dik.detailregistrasifk', $r['norec_pd'])
            ->where('dik.statusenabled', true)
            ->where('ik.statusenabled', true)
            ->get();

        foreach ($data as $ds) {
            $ds->daftarinstruksikerja = [];
            foreach ($instruksiKerja as $sd) {
                if ($ds->norec_detail == $sd->detailregistrasifk) {
                    $ds->daftarinstruksikerja[] = $sd;
                }
            }
        }

        $alatstandar = DB::table('daftaralatstandar_t as das')
            ->leftJoin('peralatanstandar_m as pas', 'pas.id', '=', 'das.alatstandarfk')
            ->select('das.detailregistrasifk', 'pas.id as value', 'pas.namaalatstandar as label')
            ->where('das.detailregistrasifk', $r['norec_pd'])
            ->where('das.statusenabled', true)
            ->where('pas.statusenabled', true)
            ->get();

        foreach ($data as $ds) {
            $ds->daftaralatstandar = [];
            foreach ($alatstandar as $sd) {
                if ($ds->norec_detail == $sd->detailregistrasifk) {
                    $ds->daftaralatstandar[] = $sd;
                }
            }
        }

        $result = [
            'length' => count($data),
            'data' => $data,
            'as' => '@adit'
        ];

        return $this->respond($result);
    }

    public function cetakSPK(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $res['identitas'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtr.petugaskaji')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->select(
                'jb.id as idjabatan',
                'jb.namajabatanulab as namajabanpetugaskaji',
                'mtr.norec',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mt.namaperusahaan',
                'mt.alamatktr',
                'pg.id as petugaskajifk',
                'pg.namalengkap as namapetugaskaji',
                'lk.lokasi',
                'mtr.jabatanpenanggungjawab',
                'mtr.namapenanggungjawab'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtr.norec', $r['norec'])
            ->first();

        $res['alat'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtrd.asmanveriffk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftJoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg2.jabatan1fk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.keterangan',
                'mtrd.namafile',
                'mtrd.pelaksanateknikfk',
                'mtrd.noorderalat',
                'mtrd.durasikalbrasi',
                'mtrd.namamanager',
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
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'jb.namajabatanulab as jabatanpelaksana',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtr.norec', $r['norec'])
            ->where('mtrd.pelaksanateknikfk', $r['pelaksanateknikfk'])
            ->orderByDesc('prd.namaproduk')
            ->get();

        $res['totalDurasi'] = $res['alat']->sum('durasikalbrasi');
        $res['pdf']  = $r['pdf'];
        $res['ttdManager'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->namamanager));
        $res['ttdPenyelia'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->pelaksanateknik));


        $blade = 'report.asman.surat-perintah-kerja';

        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }

    public function cetakSertifikatLembarKerja(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $res['identitas'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtr.petugaskaji')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->select(
                'jb.id as idjabatan',
                'jb.namajabatanulab as namajabanpetugaskaji',
                'mtr.norec',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mt.namaperusahaan',
                'mt.alamatktr',
                'pg.id as petugaskajifk',
                'pg.namalengkap as namapetugaskaji',
                'lk.lokasi',
                'mtr.jabatanpenanggungjawab',
                'mtr.namapenanggungjawab'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtr.norec', $r['norec'])
            ->first();

        $res['lembarKerja'] = DB::table('lembarkerja_t as lk')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
            ->select(
                'lk.*',
                'mtrd.tglkalibrasilembarkerja',
                'mtrd.tempatKalibrasilembarkerja',
                'mtrd.kondisiRuanganlembarkerja',
                'mtrd.suhulembarkerja',
                'mtrd.kelembabanRelatiflembarkerja'
            )
            ->where('mtrd.norec', $r['norec_detail'])
            ->where('lk.statusenabled', true)
            ->get();

        $res['instruksikerja'] = DB::table('daftarinstruksikerja_t as dik')
            ->leftJoin('instruksikerja_m as ik', 'ik.id', '=', 'dik.idalatinstruksikerja')
            ->select('dik.detailregistrasifk', 'ik.id', 'ik.namainstruksikerja', 'ik.noisntruksikerja')
            ->where('dik.detailregistrasifk', $r['norec_detail'])
            ->where('dik.statusenabled', true)
            ->where('ik.statusenabled', true)
            ->get();

        $res['alastandar'] = DB::table('daftaralatstandar_t as das')
            ->leftJoin('peralatanstandar_m as pas', 'pas.id', '=', 'das.alatstandarfk')
            ->leftJoin('merkalat_m as mk', 'mk.id', '=', 'das.merkstandarfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'das.tipestandarfk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'das.snstandarfk')
            ->select('das.detailregistrasifk', 
                'pas.id as value', 
                'pas.namaalatstandar',
                'pas.duedate',
                'mk.namamerk',
                'tp.namatipe',
                'sn.namaserialnumber'
                )
            ->where('das.detailregistrasifk', $r['norec_detail'])
            ->where('das.statusenabled', true)
            ->where('pas.statusenabled', true)
            ->get();

        $res['alat'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtr.asmanveriffk')
            ->select(
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_detail'])
            ->first();

        $res['pdf']  = $r['pdf'];
        $res['ttdPelaksana'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->pelaksanateknik));
        $res['ttdAsman'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->asamanverifikasi));
        $res['ttdPenyelia'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->penyeliateknik));

        $blade = 'report.pelaksana.sertifikat-lembar-kerja';

        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );

            // Tambah penomoran halaman otomatis di footer PDF
            $dompdf = $pdf->getDomPDF();
            $canvas = $dompdf->get_canvas();
            $canvas->page_text(200, 780, "Halaman ke {PAGE_NUM} dari {PAGE_COUNT} halaman", null, 11, array(0, 0, 0));
            $canvas->page_text(230, 795, "Page {PAGE_NUM} of {PAGE_COUNT} pages", null, 9, array(0, 0, 0));



            return $pdf->stream();
        }

        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }


        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }

    public function getMerkStandar(Request $r)
    {
        $search = $r['query'];
        $data = DB::table('mapmitratomerk_m as mmk')
            ->leftJoin('merkalat_m as mk', 'mk.id', '=', 'mmk.merkfk')
            ->select(
                'mmk.id as idmapmerk',
                'mmk.mitrafk',
                'mk.namamerk',
                'mk.id',
            )
            ->where('mmk.statusenabled', true)
            ->where('mmk.mitrafk', 1)
            ->where('mk.statusenabled', true);

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

    public function getTipeStandar(Request $r)
    {
        $search = $r['query'];
        $data = DB::table('mapmitratotipe_m as mmp')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mmp.tipefk')
            ->select(
                'mmp.id as idmaptipe',
                'mmp.mitrafk',
                'tp.namatipe',
                'tp.id',
            )
            ->where('mmp.statusenabled', true)
            ->where('mmp.mitrafk', 1)
            ->where('tp.statusenabled', true);

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

    public function getSnStandar(Request $r)
    {
        $search = $r['query'];
        $data = DB::table('mapmitratosn_m as mms')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mms.snfk')
            ->select(
                'mms.id as idmapsn',
                'mms.mitrafk',
                'sn.namaserialnumber',
                'sn.id',
            )
            ->where('mms.statusenabled', true)
            ->where('mms.mitrafk', 1)
            ->where('sn.statusenabled', true);

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

    //     public function cetakSertifikatLembarKerja(Request $r)
    //     {
    //         $profile = $this->profile();
    //         $print = false;
    //         $pageWidth = 950;

    //         // Query identitas
    //         $res['identitas'] = $data = DB::table('mitraregistrasi_t as mtr')
    //             ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
    //             ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtr.petugaskaji')
    //             ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
    //             ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
    //             ->select(
    //                 'jb.id as idjabatan',
    //                 'jb.namajabatanulab as namajabanpetugaskaji',
    //                 'mtr.norec',
    //                 'mtr.tglregistrasi',
    //                 'mtr.nopendaftaran',
    //                 'mtr.catatan',
    //                 'mt.namaperusahaan',
    //                 'mt.alamatktr',
    //                 'pg.id as petugaskajifk',
    //                 'pg.namalengkap as namapetugaskaji',
    //                 'lk.lokasi',
    //                 'mtr.jabatanpenanggungjawab',
    //                 'mtr.namapenanggungjawab'
    //             )
    //             ->where('mtr.statusenabled', true)
    //             ->where('mtr.iskaji', true)
    //             ->where('mtr.norec', $r['norec'])
    //             ->first();

    //         // Query lembar kerja
    //         $res['lembarKerja'] = DB::table('lembarkerja_t as lk')
    //             ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
    //             ->select('lk.*')
    //             ->where('mtrd.norec', $r['norec_detail'])
    //             ->where('lk.statusenabled', true)
    //             ->get();

    //         $res['pdf'] = $r['pdf'] ?? false;
    //         $blade = 'report.pelaksana.sertifikat-lembar-kerja';

    //         // PDF EXPORT
    //         if ($res['pdf'] === 'true' || $res['pdf'] === true) {

    //             // Render main html
    //             $html = view($blade, [
    //                 'profile' => $profile,
    //                 'pageWidth' => $pageWidth,
    //                 'print' => $print,
    //                 'res' => $res,
    //             ])->render();

    //             // Render header html
    //             $header = view('report.pelaksana.sertifikat-header', [
    //                 'profile' => $profile,
    //                 'res' => $res,
    //             ])->render();

    //             // dd($header);

    //             // Render footer html
    //             $footer = view('report.pelaksana.sertifikat-footer', [
    //                 'profile' => $profile,
    //                 'res' => $res,
    //             ])->render();

    //             // Generate PDF with header & footer
    //            $pdf = SnappyPdf::loadHTML($html)
    //             ->setOption('header-html', $header)
    //             ->setOption('footer-html', $footer)
    //             ->setOption('margin-top', 200)
    //             ->setOption('margin-bottom', 40)
    //             ->setOption('header-spacing', 5)
    //             ->setOption('footer-spacing', 5)
    //             ->setPaper('a4', 'portrait');
    //  // jarak antara footer dan konten

    //             return $pdf->inline('sertifikat.pdf');
    //             // atau bisa juga pakai: return $pdf->download('sertifikat.pdf');
    //         }

    //         // JIKA MAU SIMPAN PDF DI STORAGE, MIRIP SAJA
    //         if (isset($r['storage'])) {
    //             $res['storage'] = true;

    //             $html = view($blade, [
    //                 'profile' => $profile,
    //                 'pageWidth' => $pageWidth,
    //                 'print' => $print,
    //                 'res' => $res,
    //             ])->render();

    //             $header = view('report.pelaksana.sertifikat-header', [
    //                 'profile' => $profile,
    //                 'res' => $res,
    //             ])->render();


    //             $footer = view('report.pelaksana.sertifikat-footer', [
    //                 'profile' => $profile,
    //                 'res' => $res,
    //             ])->render();

    //             $pdf = SnappyPdf::loadHTML($html)
    //                 ->setPaper('a4', 'portrait')
    //                 ->setOption('margin-top', 50)
    //                 ->setOption('margin-bottom', 35)
    //                 ->setOption('header-html', $header)
    //                 ->setOption('header-spacing', 5)
    //                 ->setOption('footer-html', $footer)
    //                 ->setOption('footer-spacing', 3);

    //             // contoh save ke storage, jika mau
    //             // Storage::put('public/pdf/sertifikat.pdf', $pdf->output());

    //             return $pdf;
    //         }

    //         // TAMPILKAN DI BROWSER SAJA (HTML, BUKAN PDF)
    //         return view(
    //             $blade,
    //             compact('profile', 'pageWidth', 'print', 'res')
    //         );
    //     }
}
