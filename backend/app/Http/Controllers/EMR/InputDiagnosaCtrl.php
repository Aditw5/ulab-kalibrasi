<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\DiagnosaTindakan;
use App\Models\Master\JenisDiagnosa;
use App\Models\Master\JenisPetugasPelaksana;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\DiagnosaPasien;
use App\Models\Transaksi\DiagnosaTindakanPasien;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class InputDiagnosaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasien(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'pd.noregistrasi',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email',
                'pd.objectkelompokpasienlastfk',
                'pd.objectruanganasalfk'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =  DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'pd.objectruanganasalfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pa.nosep',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last  = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function listTindakan(Request $r)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('mpr.objectruanganfk', $r['idruangan'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (
            isset($r['name']) &&
            $r['name'] != "" &&
            $r['name'] != "undefined"
        ) {
            $data = $data
                ->where('prd.namaproduk', 'ilike', '%' . $r['name'] . '%');
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listDropdownDiagnosa(Request $r)
    {
        $result['jenisdiagnosa'] =  JenisDiagnosa::mine()->get();
        return $this->respond($result);
    }
    public function listDianosaX(Request $r)
    {
        $result['diagnosa'] =
            Diagnosa::mine()
            ->search($r['name'])
            ->paging($r['limit'])
            ->orderBy('kddiagnosa')
            ->get();
        return $this->respond($result);
    }
    public function listDianosaIX(Request $r)
    {
        $result['diagnosatindakan'] =
            DiagnosaTindakan::mine()
            ->search($r['name'])
            ->paging($r['limit'])
            ->orderBy('kddiagnosatindakan')
            ->get();
        return $this->respond($result);
    }
    public function saveDiagnosaPasien(Request $r)
    {

        DB::beginTransaction();

        $diagP = $r['diagnosapasien'];

        $diagDP = $r['detaildiagnosapasien'];
        $pasien = $r['pasien'];

        $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();

        try {
            if (!$r['isEdit']) {
                if (!empty($diagP['norec'])) {
                    $model = DiagnosaPasien::where('norec', $diagP['norec'])->first();
                    if ($model) {
                        DetailDiagnosaPasien::where('objectdiagnosapasienfk', $diagP['norec'])->delete();
                    }
                } else {
                    $model = new DiagnosaPasien();
                    $model->norec = $model->generateNewId();
                    $model->kdprofile = $this->kdProfile;
                    $model->statusenabled = true;
                }
            } else {
                $model = DiagnosaPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaPasien::where('objectdiagnosapasienfk', $diagP['norec'])->delete();
                if ($diagP['norec'] != '') {
                    $res = DB::connection('mongodb')
                        ->table('CPPTDetail')
                        ->select('*')
                        ->where('norec_pd', $pasien['norec_pd'])
                        ->where('flag', 'dokter')
                        ->where('statusenabled', true)
                        ->orderByDesc('updated_at')
                        ->orderByDesc('created_at')
                        ->latest()
                        ->first();
                    $adaDiagnosa = 0;
                    if ($res) {
                        foreach ($res['diagnosaDokter'] as $index => $item) {
                            if (isset($item['norecDiagnosa'])) {
                                if ($item['norecDiagnosa'] == $diagP['norec']) {
                                    $indexDiagnosa = $index;
                                    $diagnosaCurrent = $item;
                                    $adaDiagnosa = 1;
                                    break;
                                }
                            } else {
                                break;
                            }
                        }
                    }

                    if ($adaDiagnosa == 1) {
                        $newDiagnosa = [
                            "no" => $diagnosaCurrent['no'],
                            "keterangan" => $diagP['ketdiagnosis'],
                            "norecDiagnosa" => $diagnosaCurrent['norecDiagnosa'],
                            "jenisDiagnosa" => [
                                "label" => $diagDP['labeljenisdiagnosafk'],
                                "value" => $diagDP['objectjenisdiagnosafk']
                            ],
                            "diagnosaa" => [
                                "label" => $diagDP['labeldiagnosafk'],
                                "value" => $diagDP['objectdiagnosafk']
                            ],
                        ];

                        $updateDiagMongo = DB::connection('mongodb')
                            ->table('CPPTDetail')
                            ->where('norec_pd', $pasien['norec_pd'])
                            ->where('flag', 'dokter')
                            ->where('statusenabled', true)
                            ->update([
                                '$set' => [
                                    "diagnosaDokter.$indexDiagnosa" => $newDiagnosa,
                                ],
                            ]);
                    }
                }
            }
            $model->noregistrasifk = $diagP['noregistrasifk'];
            $model->ketdiagnosis = $diagP['ketdiagnosis'];
            $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
            $model->iskasusbaru = $diagP['iskasusbaru'];
            $model->iskasuslama = $diagP['iskasuslama'];
            $model->save();

            $model2 = new DetailDiagnosaPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model2->norec = $model2->generateNewId();
            $model2->objectdiagnosafk = $diagDP['objectdiagnosafk'];
            $model2->objectdiagnosapasienfk = $model->norec;
            $model2->objectjenisdiagnosafk = $diagDP['objectjenisdiagnosafk'];
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
            $model2->iskasusbaru =  isset($diagDP['iskasusbaru']) ? $diagDP['iskasusbaru'] : null;
            $model2->iskasuslama =  isset($diagDP['iskasuslama']) ? $diagDP['iskasuslama'] : null;
            $model2->save();

            $this->LOGGING(
                'Tambah Diagnosa',
                $model->norec,
                'diagnosapasien_t',
                'Tambah Diagnosa ' . ' pada Pasien ' .
                    $r['pasien']['namapasien'] ?? null . ' (' . $r['pasien']['nocm'] ?? null . ') - ' . $r['pasien']['noregistrasi'] ?? null
            );


            DB::commit();
            $ihs = null;
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            // $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                'Condition' => $ihs,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function saveDiagnosaPasienSelesai(Request $r)
    {

        DB::beginTransaction();

        $diagP = $r['diagnosapasien'];

        $diagDP = $r['detaildiagnosapasien'];

        $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();

        try {
            if (empty($diagP['norec'] == '')) {
                $model = new DiagnosaPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } elseif (empty($diagP['norec'])) {
                $model = new DiagnosaPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaPasien::where('objectdiagnosapasienfk', $diagP['norec'])->delete();
            }
            $model->noregistrasifk = $diagP['noregistrasifk'];
            $model->ketdiagnosis = $diagP['ketdiagnosis'];
            $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
            $model->iskasusbaru = $diagP['iskasusbaru'];
            $model->iskasuslama = $diagP['iskasuslama'];
            $model->save();

            $model2 = new DetailDiagnosaPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
            $model2->norec = $model2->generateNewId();
            $model2->objectdiagnosafk = $diagDP['objectdiagnosafk'];
            $model2->objectdiagnosapasienfk = $model->norec;
            $model2->objectjenisdiagnosafk = $diagDP['objectjenisdiagnosafk'];
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangan = $diagP['ketdiagnosis'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
            $model2->iskasusbaru =  isset($diagDP['iskasusbaru']) ? $diagDP['iskasusbaru'] : null;
            $model2->iskasuslama =  isset($diagDP['iskasuslama']) ? $diagDP['iskasuslama'] : null;
            $model2->save();

            $this->LOGGING(
                'Tambah Diagnosa',
                $model->norec,
                'diagnosapasien_t',
                'Tambah Diagnosa ' . ' pada Pasien ' .
                    $r['pasien']['namapasien'] ?? null . ' (' . $r['pasien']['nocm'] ?? null . ') - ' . $r['pasien']['noregistrasi'] ?? null
            );

            AntrianPasienDiperiksa::where(
                'norec',
                $diagP['noregistrasifk']
            )
                ->update([
                    'status' => 'Selesai',
                ]);


            DB::commit();
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            // $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                // 'Condition' => $ihs,
                'noregistrasi' => $getRegistrasi['noregistrasi'],
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function saveMoreDiagnosaPasien(Request $r)
    {

        DB::beginTransaction();
        try {
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $r['noregistrasifk'])->first();
            $pasien = DB::table('pasiendaftar_t as pd')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.*')
                ->where('pd.noregistrasi', $getRegistrasi['noregistrasi'])
                ->where('pd.kdprofile', $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('ps.statusenabled', true)
                ->first();
            foreach ($r['diagnosis'] as $dataDiagnosa) {
                if (isset($dataDiagnosa['jenisDiagnosa'])) {
                    if ($dataDiagnosa['norecDiagnosa'] == '') {
                        $model = new DiagnosaPasien();
                        $model->norec = $model->generateNewId();
                        $model->kdprofile = $this->kdProfile;
                        $model->statusenabled = true;
                    } else {
                        $model = DiagnosaPasien::where('norec', $dataDiagnosa['norecDiagnosa'])->first();
                        if ($model) {
                            DetailDiagnosaPasien::where('objectdiagnosapasienfk', $dataDiagnosa['norecDiagnosa'])->delete();
                        } else {
                            $model = new DiagnosaPasien();
                            $model->norec = $model->generateNewId();
                            $model->kdprofile = $this->kdProfile;
                            $model->statusenabled = true;
                        }
                    }
                    $model->noregistrasifk = $r['noregistrasifk'];
                    $model->ketdiagnosis = isset($dataDiagnosa['ketDiagnosaDok']) ? $dataDiagnosa['ketDiagnosaDok'] : '';
                    $model->tglregistrasi = $getRegistrasi['tglregistrasi'];
                    $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
                    $model->iskasusbaru = null;
                    $model->iskasuslama = null;
                    $model->save();
                    $model2 = new DetailDiagnosaPasien();
                    $model2->norec = $model2->generateNewId();
                    $model2->kdprofile = $this->kdProfile;
                    $model2->statusenabled = true;
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasifk = $r['noregistrasifk'];
                    $model2->tglregistrasi = $getRegistrasi['tglregistrasi'];
                    $model2->norec = $model2->generateNewId();
                    $model2->objectdiagnosafk = isset($dataDiagnosa['diagnosaIcd10']) && is_array($dataDiagnosa['diagnosaIcd10']) ? $dataDiagnosa['diagnosaIcd10']['value'] : null;
                    $model2->objectdiagnosapasienfk = $model->norec;
                    $model2->objectjenisdiagnosafk = $dataDiagnosa['jenisDiagnosa']['value'];
                    $model2->tglinputdiagnosa = date('Y-m-d H:i:s');
                    $model2->keterangan =  isset($dataDiagnosa['ketDiagnosaDok']) ? $dataDiagnosa['ketDiagnosaDok'] : '';
                    $model2->objectpegawaifk = $this->getPegawai()->id;
                    $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
                    $model2->iskasusbaru = null;
                    $model2->iskasuslama = null;
                    $model2->save();

                    $this->LOGGING(
                        'Tambah Diagnosa',
                        $model->norec,
                        'diagnosapasien_t',
                        'Tambah Diagnosa ' . ' pada Pasien ' .
                            $pasien->namapasien . ' (' . $pasien->nocm . ') - ' . $getRegistrasi['noregistrasi'] ?? null
                    );
                }
            }

            DB::commit();
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $getRegistrasi['noregistrasi'];
            $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Condition($objetoRequest, true);
            $result = [
                'message' => 'Simpan ICD 10',
                'result' => $model,
                'Condition' => $ihs,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Gagal Simpan ICD 10',
                'result' => $e->getMessage() . $e->getLine(),
                'status' => 400
            ];
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveMoreDiagnosaTindakanPasien(Request $r)
    {
        DB::beginTransaction();
        try {
            // $diagP = $r['diagnosapasien'];
            // $diagDP = $r['detaildiagnosapasien'];
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $r['noregistrasifk'])->first();
            foreach ($r['diagnosaPerawat'] as $diagPer) {
                if ($diagPer['norecDiagnosa9'] == '') {
                    $model = new DiagnosaTindakanPasien();
                    $model->norec = $model->generateNewId();
                    $model->kdprofile = $this->kdProfile;
                    $model->statusenabled = true;
                } else {
                    $model = DiagnosaTindakanPasien::where('norec', $diagPer['norecDiagnosa9'])->first();
                    DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagPer['norecDiagnosa9'])->delete();
                }
                $model->objectpasienfk = $r['noregistrasifk'];
                $model->tglpendaftaran = $getRegistrasi['tglregistrasi'];
                $model->save();
                $model2 = new DetailDiagnosaTindakanPasien();
                $model2->norec = $model2->generateNewId();
                $model2->kdprofile = $this->kdProfile;
                $model2->statusenabled = true;
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasifk = $r['noregistrasifk'];
                $model2->objectdiagnosatindakanfk = isset($diagPer['diagnosaIcd9']) && is_array($diagPer['diagnosaIcd9']) ? $diagPer['diagnosaIcd9']['value'] : null;
                $model2->objectdiagnosatindakanpasienfk = $model->norec;
                $model2->tglinputdiagnosa = date('Y-m-d H:i:s');
                $model2->keterangantindakan = isset($diagPer['ketTindakanDokter']) ? $diagPer['ketTindakanDokter'] : '';
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
                $model2->save();
            }

            DB::commit();
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model2,
                'status' => 201
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Gagal Simpan ICD 9',
                'result' => $e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function saveDiagnosaTindakanPasien(Request $r)
    {
        DB::beginTransaction();
        try {
            $diagP = $r['diagnosapasien'];
            $diagDP = $r['detaildiagnosapasien'];
            $pasien = $r['pasien'];
            if (!$r['isEdit']) {
                if (!empty($diagP['norec'])) {
                    $model = DiagnosaTindakanPasien::where('norec', $diagP['norec'])->first();
                    if ($model) {
                        DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagP['norec'])->delete();
                    }
                } else {
                    $model = new DiagnosaTindakanPasien();
                    $model->norec = $model->generateNewId();
                    $model->kdprofile = $this->kdProfile;
                    $model->statusenabled = true;
                }
            } else {
                $model = DiagnosaTindakanPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagP['norec'])->delete();
                $res = DB::connection('mongodb')
                    ->table('CPPTDetail')
                    ->select('*')
                    ->where('norec_pd', $pasien['norec_pd'])
                    ->where('flag', 'dokter')
                    ->where('statusenabled', true)
                    ->orderByDesc('updated_at')
                    ->orderByDesc('created_at')
                    ->latest()
                    ->first();
                $adaDiagnosa = false;
                if ($res) {
                    foreach ($res['diagnosaDokter9'] as $index => $item) {
                        if (isset($item['norecDiagnosa9'])) {
                            if ($item['norecDiagnosa9'] == $diagP['norec']) {
                                $indexDiagnosa = $index;
                                $diagnosaCurrent = $item;
                                $adaDiagnosa = true;
                                break;
                            }
                        } else {
                            break;
                        }
                    }

                    if ($adaDiagnosa) {
                        $newDiagnosa = [
                            "no" => $diagnosaCurrent['no'],
                            "keterangan" => $diagP['keterangantindakan'],
                            "norecDiagnosa9" => $diagP['norec'],
                            "diagnosaa" => [
                                "label" => $diagDP['labeldiagnosatindakanfk'],
                                "value" => $diagDP['objectdiagnosatindakanfk'],
                            ]
                        ];

                        $updateDiagMongo = DB::connection('mongodb')
                            ->table('CPPTDetail')
                            ->where('norec_pd', $pasien['norec_pd'])
                            ->where('flag', 'dokter')
                            ->where('statusenabled', true)
                            ->update([
                                '$set' => [
                                    "diagnosaDokter9.$indexDiagnosa" => $newDiagnosa,
                                ],
                            ]);
                    }
                }
            }
            $model->objectpasienfk = $diagP['noregistrasifk'];
            $model->tglpendaftaran = $diagP['tglregistrasi'];
            $model->save();
            $model2 = new DetailDiagnosaTindakanPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->objectdiagnosatindakanfk = $diagDP['objectdiagnosatindakanfk'];
            $model2->objectdiagnosatindakanpasienfk = $model->norec;
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangantindakan = $diagP['keterangantindakan'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $diagDP['noregistrasi'];
            $model2->save();

            DB::commit();
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model,
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function saveDiagnosaTindakanPasienSelesai(Request $r)
    {
        DB::beginTransaction();
        try {
            $diagP = $r['diagnosapasien'];
            $diagDP = $r['detaildiagnosapasien'];
            $getRegistrasi = AntrianPasienDiperiksa::where('norec', $diagP['noregistrasifk'])->first();
            if (empty($diagP['norec'] == '')) {
                $model = new DiagnosaTindakanPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } elseif (empty($diagP['norec'])) {
                $model = new DiagnosaTindakanPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
            } else {
                $model = DiagnosaTindakanPasien::where('norec', $diagP['norec'])->first();
                DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $diagP['norec'])->delete();
            }
            $model->objectpasienfk = $diagP['noregistrasifk'];
            $model->tglpendaftaran = $diagP['tglregistrasi'];
            $model->save();
            $model2 = new DetailDiagnosaTindakanPasien();
            $model2->norec = $model2->generateNewId();
            $model2->kdprofile = $this->kdProfile;
            $model2->statusenabled = true;
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasifk = $diagP['noregistrasifk'];
            $model2->objectdiagnosatindakanfk = $diagDP['objectdiagnosatindakanfk'];
            $model2->objectdiagnosatindakanpasienfk = $model->norec;
            $model2->tglinputdiagnosa = $diagDP['tglinputdiagnosa'];
            $model2->keterangantindakan = $diagP['keterangantindakan'];
            $model2->noregistrasi =  $getRegistrasi['noregistrasi'];
            $model2->objectpegawaifk = $this->getPegawai()->id;
            $model2->noregistrasi =  $diagDP['noregistrasi'];
            $model2->save();

            AntrianPasienDiperiksa::where(
                'norec',
                $diagP['noregistrasifk']
            )
                ->update([
                    'status' => 'Selesai',
                ]);

            DB::commit();
            $result = [
                'message' => 'Simpan ICD 9',
                'result' => $model,
                'noregistrasi' => $diagDP['noregistrasi'],
                'status' => 200
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                'message' => 'Simpan Gagal',
                'result' => $e->getMessage(),
                'status' => 400
            ];
        }
        return $this->respond($result, $result['status'], $result['message']);
    }


    public function riwayatDiagnosaIX(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddt.objectdiagnosatindakanfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                DB::raw('MIN(dtp.norec) as norec_diagnosapasien'),
                DB::raw('MIN(ddt.norec) as norec_detaildpasien'),
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                DB::raw('MIN(ddt.tglinputdiagnosa) as tglinputdiagnosa')
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->leftjoin('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('dtp.statusenabled', true)
            ->where('ddt.statusenabled', true)
            ->groupBy(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec',
                'ddt.objectdiagnosatindakanfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap'
            );
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };
        $data = $data->get();

        $uniqueData = [];
        foreach ($data as $diagnosa) {
            $keteranganValue = $diagnosa->keterangantindakan ?? '';
            $diagnosaValue = $diagnosa->objectdiagnosatindakanfk ?? '';
            $uniqueString = $keteranganValue . $diagnosaValue;
            $uniqueData[$uniqueString] = $diagnosa;
        }

        $data = array_values($uniqueData);

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaKeperawatan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $nocmfk = strlen($request['nocmfk']) >= 32 ? $request['nocmfk'] : $request['nocmfk'];
    
        $data = DB::connection('mongodb')
            ->table('DiagnosaKeperawatan')
            ->where('registrasi.norec_pd', $request['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('statusenabled', true)
            ->get();

        $result = [];    
        foreach ($data as $item) {
            if (!isset($item['diagnosisKerja']) || !is_array($item['diagnosisKerja'])) {
                continue; 
            }
    
            foreach ($item['diagnosisKerja'] as $diagnosa) {
                if (isset($diagnosa['diagnosakeperawatan'])) {
                    $result[] = [
                        "id" => isset($diagnosa['diagnosakeperawatan']['id']) ? (string) $diagnosa['diagnosakeperawatan']['id'] : null,
                        "qdiagnosakep" => isset($diagnosa['diagnosakeperawatan']['qdiagnosakep']) ? (string) $diagnosa['diagnosakeperawatan']['qdiagnosakep'] : null,
                        "diagnosakep" => $diagnosa['diagnosakeperawatan']['diagnosakep'] ?? null,
                        "kodediagnosakep" => $diagnosa['diagnosakeperawatan']['kodediagnosakep'] ?? null,
                        "detailjenisdiagnosakeperawatan" => $diagnosa['diagnosakeperawatan']['detailjenisdiagnosakeperawatan'] ?? null,
                        "detailjenisdiagnosakeperawatanfk" => isset($diagnosa['diagnosakeperawatan']['detailjenisdiagnosakeperawatanfk']) 
                            ? (string) $diagnosa['diagnosakeperawatan']['detailjenisdiagnosakeperawatanfk'] 
                            : null,
                    ];
                }
            }
        }
    
        $response = [
            'data' => $result, 
            'message' => '@epic',
        ];
        return $this->respond($response);
    }    

    public function riwayatPerencanaanKeperawatan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $nocmfk = strlen($request['nocmfk']) >= 32 ? $request['nocmfk'] : $request['nocmfk'];

        $data = DB::connection('mongodb')
            ->table('PerencanaanKeperawatan')
            ->where('registrasi.norec_pd', $request['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('statusenabled', true)
            ->get();

        $result = [];

        foreach ($data as $item) {
            if (!isset($item['detailTindakan']) || !is_array($item['detailTindakan'])) {
                continue;
            }

            foreach ($item['detailTindakan'] as $tindakan) {
                $result[] = [
                    'no' => $tindakan['no'] ?? null,
                    'diagnosakep' => $tindakan['diagnosakep'] ?? null,
                    'detailjenisdiagnosakep' => $tindakan['detailjenisdiagnosakep'] ?? null,
                    'kodediagnosakep' => $tindakan['kodediagnosakep'] ?? null,
                    'id' => isset($tindakan['id']) ? (string) $tindakan['id'] : null,
                    'qdiagnosakep' => isset($tindakan['qdiagnosakep']) ? (string) $tindakan['qdiagnosakep'] : null,
                    'selectedTujuan' => $tindakan['selectedTujuan'] ?? [],
                    'selectedKriteria' => $tindakan['selectedKriteria'] ?? [],
                    'selectedIntervensi' => $tindakan['selectedIntervensi'] ?? [],
                    'selectedAktifitas' => $tindakan['selectedAktifitas'] ?? [],
                ];
            }
        }

        $response = [
            'data' => $result,
            'message' => '@epic',
        ];

        return $this->respond($response);
    }  

    public function riwayatPengkajianKeperawatan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $nocmfk = strlen($request['nocmfk']) >= 32 ? $request['nocmfk'] : $request['nocmfk'];

        $data = DB::connection('mongodb')
            ->table('pengkajianAwalRawatJalanKeperawatan')
            ->where('registrasi.norec_pd', $request['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('statusenabled', true)
            ->get();

        $response = [
            'data' => $data,
            'message' => '@epic',
        ];

        return $this->respond($response);
    }  

    public function riwayatDiagnosaX(Request $request)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec as norec_apd',
                'ddp.objectdiagnosafk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                DB::raw('MIN(ddp.tglinputdiagnosa) as tglinputdiagnosa'),
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                DB::raw('MIN(dp.norec) as norec_diagnosapasien'),
                'dg.id as id_diagnosa',
                DB::raw('MIN(dp.norec) as norec_detaildpasien'),
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->leftjoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('dp.statusenabled', true)
            ->where('ddp.statusenabled', true)
            ->groupBy(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'apd.norec',
                'ddp.objectdiagnosafk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                'dg.id',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->orderby('ddp.objectdiagnosafk', 'asc');
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        $data = $data->get();

        $uniqueData = [];
        foreach ($data as $diagnosa) {
            $diagnosaValue = $diagnosa->objectdiagnosafk ?? '';
            $jeniDiagnosaValue = $diagnosa->objectjenisdiagnosafk ?? '';
            $keteranganValue = $diagnosa->keterangan;

            $uniqueString = $diagnosaValue . $jeniDiagnosaValue . $keteranganValue;
            $uniqueData[$uniqueString] = $diagnosa;
        }

        $data = array_values($uniqueData);

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
    public function deleteDiagnosaPasienX(Request $r)
    {
        DB::beginTransaction();
        try {

            $model = DiagnosaPasien::where('norec', $r['norec'])->delete();
            $detail = DetailDiagnosaPasien::where('objectdiagnosapasienfk', $r['norec'])->first();
            DetailDiagnosaPasien::where('objectdiagnosapasienfk', $r['norec'])->delete();
            $deleteItem = DetailDiagnosaPasien::where('noregistrasi', $detail->noregistrasi);
            if ($detail->objectdiagnosafk) {
                $deleteItem = $deleteItem->where('objectdiagnosafk', $detail->objectdiagnosafk);
            } else {
                $deleteItem = $deleteItem->whereNull('objectdiagnosafk');
            }
            if ($detail->objectjenisdiagnosafk) {
                $deleteItem = $deleteItem->where('objectjenisdiagnosafk', $detail->objectjenisdiagnosafk);
            } else {
                $deleteItem = $deleteItem->whereNull('objectjenisdiagnosafk');
            }
            if ($detail->keterangan) {
                $deleteItem = $deleteItem->where('keterangan', $detail->keterangan);
            } else {
                $deleteItem = $deleteItem->where('keterangan', '');
            }
            $deleteItem = $deleteItem->delete();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $model,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteDiagnosaPasienIX(Request $r)
    {
        DB::beginTransaction();
        try {

            $model = DiagnosaTindakanPasien::where('norec', $r['norec'])->delete();
            $detail = DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $r['norec'])->first();
            DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanpasienfk', $r['norec'])->delete();
            // clean null all value
            if ($detail->objectdiagnosatindakanfk && $detail->keterangantindakan) {
                DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanfk', $detail->objectdiagnosatindakanfk)
                    ->where('keterangantindakan', $detail->keterangantindakan)
                    ->where('noregistrasi', $detail->noregistrasi)
                    ->delete();
            } else if ($detail->objectdiagnosatindakanfk && !$detail->keterangantindakan) {
                DetailDiagnosaTindakanPasien::where('objectdiagnosatindakanfk', $detail->objectdiagnosatindakanfk)
                    ->where(function ($query) {
                        $query->whereNull('keterangantindakan')
                            ->orWhere('keterangantindakan', '');
                    })
                    ->where('noregistrasi', $detail->noregistrasi)
                    ->delete();
            } else if (!$detail->objectdiagnosatindakanfk && $detail->keterangantindakan) {
                DetailDiagnosaTindakanPasien::whereNull('objectdiagnosatindakanfk')
                    ->where('keterangantindakan', $detail->keterangantindakan)
                    ->where('noregistrasi', $detail->noregistrasi)
                    ->delete();
            } else {
                DetailDiagnosaTindakanPasien::whereNull('objectdiagnosatindakanfk')
                    ->where(function ($query) {
                        $query->whereNull('keterangantindakan')
                            ->orWhere('keterangantindakan', '');
                    })
                    ->where('noregistrasi', $detail->noregistrasi)
                    ->delete();
            }


            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $model,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function riwayatDiagnosaXCppt(Request $request)
    {

        $data = DB::table('diagnosapasien_t as dp')
            ->leftJoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', 'dp.norec')
            ->leftJoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->leftJoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                DB::raw('MIN(dp.norec) as norec'),
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'jd.jenisdiagnosa',
                'jd.id as objectdiagnosafk',
                'dg.id',
                'ddp.objectdiagnosafk',
                'ddp.keterangan',
                DB::raw('true as isDiagnosaDB'),
                'ddp.objectjenisdiagnosafk'
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.noregistrasi', '=', $request['noregistrasi'])
            ->where(function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereNotNull('ddp.keterangan')
                        ->where('ddp.keterangan', '!=', '');
                })
                    ->orWhere(function ($subQuery) {
                        $subQuery->where(function ($nestedQuery) {
                            $nestedQuery->whereNull('ddp.keterangan')
                                ->orWhere('ddp.keterangan', '=', '');
                        })
                            ->where(function ($nestedQuery) {
                                $nestedQuery->whereNotNull('dg.kddiagnosa')
                                    ->orWhereNotNull('dg.namadiagnosa')
                                    ->orWhereNotNull('ddp.objectdiagnosafk')
                                    ->orWhereNotNull('dg.id');
                            });
                    });
            })
            ->groupBy(
                'ddp.keterangan',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'jd.jenisdiagnosa',
                'jd.id',
                'dg.id',
                'ddp.objectdiagnosafk',
                'ddp.objectjenisdiagnosafk',
                // 'ddp.created_at'
            )
            ->orderBy('ddp.objectjenisdiagnosafk')
            // ->orderBy('ddp.created_at')
            ->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function riwayatDiagnosaIXCppt(Request $request)
    {
        $data = DB::table('diagnosatindakanpasien_t as dtp')
            ->leftJoin('detaildiagnosatindakanpasien_t as ddtp', 'ddtp.objectdiagnosatindakanpasienfk', 'dtp.norec')
            ->leftJoin('diagnosatindakan_m as dt', 'dt.id', '=', 'ddtp.objectdiagnosatindakanfk')
            ->select(
                DB::raw('MIN(dtp.norec) as norec'),
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dt.id',
                'ddtp.keterangantindakan',
                'ddtp.objectdiagnosatindakanpasienfk',
                'ddtp.objectdiagnosatindakanfk'
            )
            ->where('ddtp.kdprofile', $this->kdProfile)
            ->where('ddtp.noregistrasi', '=', $request['noregistrasi'])
            ->where(function ($query) {
                $query->whereNotNull('ddtp.keterangantindakan')
                    ->where('ddtp.keterangantindakan', '!=', '') // Keterangan tidak boleh kosong
                    ->orWhere(function ($subQuery) {
                        $subQuery->where('dt.namadiagnosatindakan', '!=', 'Belum di Isi') // Nama diagnosa tindakan harus terisi atau tidak 'Belum di Isi'
                            ->whereNotNull('dt.namadiagnosatindakan'); // Pastikan namadiagnosatindakan tidak null
                    });
            })
            ->groupBy(
                'ddtp.keterangantindakan',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'dt.id',
                'ddtp.objectdiagnosatindakanpasienfk',
                'ddtp.objectdiagnosatindakanfk',
                'ddtp.created_at'
            )
            ->orderBy('ddtp.created_at')
            ->get();

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
}
