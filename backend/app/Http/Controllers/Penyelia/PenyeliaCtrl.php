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
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
                'mtrd.setujuilembarkerjapenyelia',
                'mtrd.tglsetujupenyelialembarkerja',
                'mtrd.penyeliasetujulembarkerjafk',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.tglsetujuasmanlembarkerja',
                'mtrd.asmansetujulembarkerjafk',
                'mtrd.statusorderasman',
                'mtrd.tglverifpelaksana',
                'mtrd.noorderalat',
            )
            ->where('pg.id', $this->getPegawaiId())
            ->where('mtr.statusorder', 1)
            ->where('mtr.iskaji', true)
            ->where('mtr.statusenabled', true)
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
                $query->where('mtrd.noorderalat', 'ilike', $searchTerm)
                    ->orWhere('prd.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('mt.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        if (isset($r['statusorderpenyelia']) && $r['statusorderpenyelia'] != '') {
            $data = $data->where('mtrd.statusorderpenyelia', '=', $r['statusorderpenyelia']);
        }
        $data = $data->orderBy('mtr.tglregistrasi');
        if (isset($r['limit'])) {
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        foreach ($data as $item) {
            $durasi = (int) $item->durasikalbrasi;
            $tglAwal = $item->tglverifpelaksana ? Carbon::parse($item->tglverifpelaksana)->startOfDay() : null;
            $tglAkhir = $item->tglsetujuasmanlembarkerja ? Carbon::parse($item->tglsetujuasmanlembarkerja)->startOfDay() : null;

            if ($tglAwal && $tglAkhir) {
                $selisih = $tglAkhir->diffInDays($tglAwal);

                if ($selisih == $durasi) {
                    $item->statusPengerjaan = 'Kalibrasi Tepat waktu';
                    $item->statusColor = 'warning';
                } elseif ($selisih > $durasi) {
                    $item->statusPengerjaan = 'Kalibrasi Terlambat ' . ($selisih - $durasi) . ' hari';
                    $item->statusColor = 'danger';
                } else {
                    $item->statusPengerjaan = 'Kalibrasi Lebih cepat ' . ($durasi - $selisih) . ' hari';
                    $item->statusColor = 'info';
                }
            } else {
                $item->statusPengerjaan = null;
                $item->statusColor = null;
            }
        }

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
            ->where('mtrd.norec', $r['norec_pd']);

        $data = $data->orderByDesc('prd.namaproduk')->get();


        $result['length'] = count($data);
        $result['detail'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
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

    public function setujuiSertifikat(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['verif'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'setujuilembarkerjapenyelia' => true,
                    'penyeliasetujulembarkerjafk' => $this->getPegawaiId(),
                    'tglsetujupenyelialembarkerja' => now(),
                    'statusorderpenyelia' => 2,
                    'statusorderasman' => 0,
                ]);


            $transMessage = "Simpan Setujui Sertifikat Sukses";
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
            ->leftJoin('pegawai_m as pg5', 'pg5.id', '=', 'mtrd.penyeliasetujulembarkerjafk')
            ->leftJoin('pegawai_m as pg6', 'pg6.id', '=', 'mtrd.asmansetujulembarkerjafk')
            ->leftJoin('pegawai_m as pg7', 'pg7.id', '=', 'mtrd.managersetujulembarkerjafk')
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
                'lp.lingkupkalibrasi',
                'mtrd.setujuilembarkerjapenyelia',
                'mtrd.tglsetujupenyelialembarkerja',
                'mtrd.penyeliasetujulembarkerjafk',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.tglsetujuasmanlembarkerja',
                'mtrd.asmansetujulembarkerjafk',
                'pg5.id as penyeliasetujuilembarkerjafk',
                'pg5.namalengkap as penyeliasetujuilembarkerja',
                'pg6.id as asmansetujuilembarkerjafk',
                'pg6.namalengkap as asmansetujuilembarkerja',
                'mtrd.setujuilembarkerjamanager',
                'mtrd.tglsetujumanagerlembarkerja',
                'mtrd.managersetujulembarkerjafk',
                'pg7.id as managersetujuilembarkerjafk',
                'pg7.namalengkap as managersetujuilembarkerja',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_pd'])
            ->orderByDesc('prd.namaproduk')
            ->get();

        $timeline = [];

        foreach ($data as $item) {
            if (!is_null($item->tglsetujumanagerlembarkerja)) {
                $timeline[] = [
                    'date' => $item->tglsetujumanagerlembarkerja,
                    'type' => 'Sertifikat Di Setujui Oleh Manager',
                    'nama' => $item->managersetujuilembarkerja ?? '-',
                ];
            }
            if (!is_null($item->tglsetujuasmanlembarkerja)) {
                $timeline[] = [
                    'date' => $item->tglsetujuasmanlembarkerja,
                    'type' => 'Sertifikat Di Setujui Oleh Asman',
                    'nama' => $item->asmansetujuilembarkerja ?? '-',
                ];
            }
            if (!is_null($item->tglsetujupenyelialembarkerja)) {
                $timeline[] = [
                    'date' => $item->tglsetujupenyelialembarkerja,
                    'type' => 'Sertifikat Di Setujui Oleh Penyelia',
                    'nama' => $item->penyeliasetujuilembarkerja ?? '-',
                ];
            }
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
                    'tglkalibrasilembarkerja' => $request['tglkalibrasi'],
                    'tempatKalibrasilembarkerja' => $request['tempatKalibrasi'],
                    'kondisiRuanganlembarkerja' => $request['kondisiRuangan'],
                    'suhulembarkerja' => $request['suhu'],
                    'kelembabanRelatiflembarkerja' => $request['kelembabanRelatif'],
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
            ->leftJoin('merkalat_m as mk', 'mk.id', '=', 'das.merkstandarfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'das.tipestandarfk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'das.snstandarfk')
            ->select(
                'das.detailregistrasifk',
                'pas.id as value',
                'pas.namaalatstandar as label',
                'mk.id as idmrek',
                'mk.namamerk',
                'tp.id as idtipe',
                'tp.namatipe',
                'sn.id as idsn',
                'sn.namaserialnumber'
            )
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
            ->leftJoin('jabatan_m as jb1', 'jb1.id', '=', 'pg.jabatan1fk')
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
                'mtrd.namaasman',
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
                'jb1.namajabatanulab as jabatanpenyelia',
                'jb.namajabatanulab as jabatanpelaksana',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtr.norec', $r['norec'])
            ->where('mtrd.penyeliateknikfk', $r['penyeliateknikfk'])
            ->orderByDesc('prd.namaproduk')
            ->get();

        $res['totalDurasi'] = $res['alat']->sum('durasikalbrasi');
        $res['pdf']  = $r['pdf'];
        $res['ttdManager'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->namamanager));
        $res['ttdAsman'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->namaasman));
        $res['ttdPenyelia'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->pelaksanateknik));
        $res['penyelia'] = true;


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
            ->select(
                'das.detailregistrasifk',
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
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->select(
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'mtrd.noorderalat',
                'mtrd.namamanager',
                'mtrd.setujuilembarkerjamanager',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.setujuilembarkerjapenyelia',
                'prd.namaproduk',
                'mrk.namamerk',
                'tp.namatipe',
                'sn.namaserialnumber',
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
        $res['ttdManager'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->namamanager));
        $res['halamanPertama'] = false;

        $blade = 'report.pelaksana.sertifikat-lembar-kerja';

        if ($res['pdf'] == 'true') {
            $pdfDummy = App::make('dompdf.wrapper');
            $pdfDummy->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'jumlahHalaman' => null,
                )
            );
            $dompdfDummy = $pdfDummy->getDomPDF();
            $dompdfDummy->render();
            $jumlahHalaman = $dompdfDummy->getCanvas()->get_page_count();

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'jumlahHalaman' => $jumlahHalaman,
                )
            );


            $dompdf = $pdf->getDomPDF();
            $canvas = $dompdf->get_canvas();
            $canvas->page_text(230, 780, "Halaman ke {PAGE_NUM} dari {PAGE_COUNT} halaman", null, 8, array(0, 0, 0));
            $font = $dompdf->getFontMetrics()->getFont('Helvetica', 'italic');
            $canvas->page_text(260, 788, "Page {PAGE_NUM} of {PAGE_COUNT} pages", $font, 7, array(0, 0, 0));

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

    public function downloadFileTerunggah(Request $request)
    {
        $norec = $request->get('norec');
        $data = DB::table('mitraregistrasidetail_t')
            ->select('namafileexcel')
            ->where('norec', $norec)
            ->first();
        if (!$data || !$data->namafileexcel) {
            return '
            <script language="javascript">
                alert("File tidak ditemukan di database.");
                window.close();
            </script>';
        }
        $filename = $data->namafileexcel;
        $pathbundle = 'berkas-mitra-excel/' . $filename;
        $path = public_path($pathbundle);

        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type)
                ->header('Content-disposition', 'attachment; filename="' . $filename . '"');

            return $response;
        } else {
            return '
            <script language="javascript">
                alert("File tidak ditemukan di direktori.");
                window.close();
            </script>';
        }
    }


    public function getExcelLength(Request $request)
    {
        $norec = $request['norec'];
        $data = DB::table('mitraregistrasidetail_t')
            ->select('namafileexcel')
            ->where('norec', $norec)
            ->first();

        $result['data'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
    }

    public function getPegawaiPelaksana(Request $r)
    {
        $jabatanIds = [
            11,
            12,
            13,
            14,
            15,
            16,
            18
        ];

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


        $res['pegawaiJakarta'] = $pegawaiJakarta;
        return $this->respond($res);
    }
}
