<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\Alamat;
use App\Models\Master\Diagnosa;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\DiagnosaPasien;
use App\Models\Transaksi\DiagnosaTindakanPasien;
use App\Models\Transaksi\EMR;
use App\Models\Transaksi\EmrDokumen;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\EMRPasienForm;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PasienPerjanjian;
use App\Models\Transaksi\ResumeMedis;
use App\Models\Transaksi\ResumeMedisDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\SuratKeterangan;
use App\Models\User;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\Uuid;
use Webpatser\Uuid\Uuid as UuidReservasi;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Mockery\Undefined;
use phpseclib3\Net\SFTP as NetSFTP;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use GuzzleHttp\Client;

class EMRCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function saveEMR(Request $r)
    {
        DB::beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $registrasi = $r->input('data')['registrasi'];
            $pasien = $r->input('data')['pasien'];
            if ($r['norec_emr'] == '') {
                do {
                    $noemr = $this->generateCodeBySeqTable(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                    $existingEMR = EMRPasien::where('noemr', $noemr)
                        ->where('kdprofile', $this->kdProfile)
                        ->first();
                } while ($existingEMR);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = $registrasi['norec_pd'];
                $EMR->noregistrasi = $registrasi['noregistrasi'];
            } else {
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    ->where('nocm',  $pasien['nocm'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $noemr = $EMR['noemr'];
                $norec =  $EMR['norec'];
            };
            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm =   $pasien['nocm'];
            $EMR->nocmfk = $pasien['nocmfk'];
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            $EMR->umur = $pasien['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = $pasien['nohp'];
            $EMR->alamat = $pasien['alamatlengkap'];
            $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $registrasi['tglregistrasi'];
            $EMR->norec_apd = $registrasi['norec_apd'];
            $EMR->namakelas = $registrasi['namakelas'];
            $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $this->getPegawai()->id;
            $EMR->tglemr = $now;
            $EMR->save();

            if ($r['jenis_emr'] == 'resume_medis') {
                PasienDaftar::where('noregistrasi', $registrasi['noregistrasi'])->update([
                    'isresumemedis' => true,
                ]);
            }
            // MONGO
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                // 'kelompokPegawai' => $r['userBy'],
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['registrasi_pasien'] = array(
                'tglregistrasi' => $r['tanggal_registrasi'],
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' =>  $this->getProfile()->namalengkap,
            );
            if ($r['userBy']) {
                $data['userBy'] = $r['userBy'];
            }
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;

            if ($r->input('id') == '') {

                $data['id'] = $this->Uuid4();
                $data['created_at'] =  $now;
                $data['updated_at'] =  null;
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                $data['updated_at'] =  $now;
                $update = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'))
                    ->update($data);
            }


            // if ($r->input('id') == '' || $r['userBy'] == 'dokter') {

            //     $data['id'] = $this->Uuid4();
            //     $data['created_at'] =  $now;
            //     $data['updated_at'] =  null;
            //     DB::connection('mongodb')
            //         ->table($r->input('collection'))
            //         ->insert($data);
            // } else {
            //     // "trim(emrpasienfk) ='$r[norec_emr]'"
            //     $data['updated_at'] =  $now;
            //     $update = DB::connection('mongodb')
            //         ->table($r->input('collection'))
            //         ->where('id', $r->input('id'))
            //         // ->where(['emrpasienfk' => ['$regex' => '^\s|\s$']])
            //         ->update($data);
            // }
            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $r->input('collection'))
                ->where('noregistrasifk',  $registrasi['norec_pd'])
                ->first();

            // return $EMR;

            if (empty($formExist)) {
                $resume = array(
                    'id' => $this->Uuid4(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($r['icon']) ? $r['icon'] : null,
                );

                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->insert($resume);
            } else {
                $resume = array(
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('id', $formExist['id'])
                    ->update($resume);
            }

            if ($r->input('collection') == 'VitalSign') {
                $pd = PasienDaftar::where('norec',  $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $pd->tinggibadan = isset($data['tinggiBadan']) ? (float) str_replace(',', '.', $data['tinggiBadan']) : null;
                $pd->beratbadan = isset($data['beratBadan']) ? (float) str_replace(',', '.', $data['beratBadan']) : null;

                $pd->suhu =  isset($data['suhu']) ? (float) str_replace(',', '.', $data['suhu']) : null;
                $pd->nadi =  isset($data['nadi']) ? (float) str_replace(',', '.', $data['nadi']) : null;
                $pd->pernafasan =  isset($data['pernapasan']) ? (float) str_replace(',', '.', $data['pernapasan']) : null;
                $pd->tekanandarah =  isset($data['tekananDarah']) ? (float) str_replace(',', '.', $data['tekananDarah']) : null;
                $pd->spo2 =  isset($data['SPO2']) ? (float) str_replace(',', '.', $data['SPO2']) : null;
                $pd->save();
            }
            if (
                $r->input('collection') == 'CatatanPerkembanganPasienTerintegrasiRawatJalan'
                || $r->input('collection') == 'CatatanPerkembanganPasienTerintegrasi'
            ) {
                PasienDaftar::where('norec',  $registrasi['norec_pd'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update([
                        'iscppt' => true
                    ]);
                AntrianPasienDiperiksa::where(
                    'norec',
                    $registrasi['norec_apd']
                )
                    ->update([
                        'status' => 'Selesai',
                    ]);
            }
            if ($r->input('collection') == 'LembarPerdosi') {
                if ($r->input('isHapus') == true) {
                    $this->LOGGING(
                        'Hapus Kolom Lembar Perdosi Pasien  ',
                        $pasien['namapasien'],
                        'emrpasien_t',
                        'Hapus Kolom Perdosi Registrasi ' .
                            $pasien['namapasien'] . ' NOCM ' . $pasien['nocm']
                    );
                }
                if ($r->input('isEdit') == true) {
                    $this->LOGGING(
                        'Edit Kolom Lembar Perdosi Pasien  ',
                        $pasien['namapasien'],
                        'emrpasien_t',
                        'Edit Kolom Perdosi Registrasi ' .
                            $pasien['namapasien'] . ' NOCM ' . $pasien['nocm'] . ' Tanggal ' . $r->input('changedColumns')[0]['tglregistrasi2']
                    );
                }
                $this->LOGGING(
                    'Simpan Lembar Perdosi Pasien  ',
                    $pasien['namapasien'],
                    'emrpasien_t',
                    'Simpan Perdosi Registrasi ' .
                        $pasien['namapasien'] . ' NOCM ' . $pasien['nocm']
                );
            }
            $transMessage = "Sukses";
            DB::commit();
            $ihs = null;
            if ($r->input('collection') == 'VitalSign') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $registrasi['noregistrasi'];
                $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Observation($objetoRequest, true);
            }

            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr"  =>  $EMR->norec,
                    "noemr"  =>  $EMR->noemr,
                    "id"  =>  $data['id'],
                    "Observation"  => $ihs,
                    "noregistrasi" => $registrasi['noregistrasi'],
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function kirimResumeMedis(Request $request)
    {
        try {
            // start generate parameter kebutuhan save dokumen
            $data = DB::connection('mongodb')
                ->table('resumeMedis')
                ->where('statusenabled', true)
                ->where('emrpasienfk', $request['norecemr'])
                ->first();
            if (empty($data)) {
                return null;
            }

            $dataPasien = DB::table('pasien_m as pas')
                ->select(
                    'pas.nohp'
                )
                ->where('pas.nocm', $data['pasien']['nocm'])
                ->first();
            $otp = rand(100000, 999999);
            $client = new Client();


            $date = date('Y-m-d'); // Replace with actual dates
            $namapasien = $data['pasien']['namapasien'];
            $nomr = $data['pasien']['nocm'];
            // $phone = "089502222265";
            $phone = $dataPasien->nohp;

            $Link = "https://rsdgunungjati.com/service/emr/cetak/resumeMedis?pdf=true&emrpasienfk={$request['norecemr']}&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzUxMDE2NDN9.y2rs5nFOX8J4PK63Uxv_Df1AFY7MoDR1e8WmdLxDmxtolZNg9XrylQgMl2W5Ng1MYhIxgXYwnjEqbD9If_8vyw.MQ==";
            $finalLink = str_replace(' ', '%20', $Link);
            //    $finalLink = str_replace(' ', '%20', "https://sim.rsdgunungjati.com/service/emr/cetak/resumeMedis?pdf=true&emrpasienfk={$request['norecemr']}&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzUxMDE2NDN9.y2rs5nFOX8J4PK63Uxv_Df1AFY7MoDR1e8WmdLxDmxtolZNg9XrylQgMl2W5Ng1MYhIxgXYwnjEqbD9If_8vyw.MQ==");



            $pesan = "Salam hormat,";
            $pesan .= " \n";
            $pesan .= "Berikut kami sampaikan hasil dari Resume Medis tanggal $date";
            $pesan .= " \n";
            $pesan .= "a/n pasien *{$namapasien} ({$nomr})*.";
            $pesan .= " \n";
            $pesan .= "dapat dilihat dengan cara klik link berikut ini :";
            $pesan .= " \n \n";
            $pesan .= "{$finalLink}";
            $pesan .= " \n \n \n";
            $pesan .= "Terima kasih,";
            $pesan .= " \n";
            $pesan .= "RSD Gunung Jati Kota Cirebon";
            $pesan .= " \n";


            $convertPhoneNumber = function ($phone) {
                if (strpos($phone, "0") === 0) {
                    return "62" . substr($phone, 1);
                }
                return $phone;
            };
            $convertedPhone = $convertPhoneNumber($phone);

            // print_r($convertedPhone);

            $dataWA = [
                'phone' => $convertedPhone,
                'isGroup' => false,
                'isNewsletter' => false,
                'message' => $pesan,
            ];

            $WAurl = $this->settingFix('APIWA_url');
            $WAauth = 'Bearer ' . $this->settingFix('APIWA_token');

            $response = $client->post($WAurl, [
                'headers' => [
                    'Authorization' => $WAauth,
                ],
                'json' => $dataWA,
            ]);

            $result = array(
                "status" => 201,
                "message" => "Kirim WA sukses",
                "data" => $dataWA,
            );
            
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data"  => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function saveDiagnosaXCPPTRanap(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r['details'][0]['diagnosaDokter'];
            $transMessage = "Simpan Diagnosa X Berhasil";
            DetailDiagnosaPasien::where('noregistrasi', $r['registrasi']['noregistrasi'])->delete();
            DiagnosaPasien::where('noregistrasifk', $r['registrasi']['norec_apd'])->delete();

            // Create DiagnosaPasien
            foreach ($data as $key => $diagnosa) {
                $model = new DiagnosaPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
                $model->noregistrasifk = $r['registrasi']['norec_apd'];
                $model->ketdiagnosis = $diagnosa['keterangan'] ?? "";
                $model->tglregistrasi = $r['registrasi']['tglregistrasi'];
                $model->tglpendaftaran = $r['registrasi']['tglregistrasi'];
                $model->iskasusbaru = null;
                $model->iskasuslama = null;
                $model->save();

                $model2 = new DetailDiagnosaPasien();
                $model2->norec = $model2->generateNewId();
                $model2->kdprofile = $this->kdProfile;
                $model2->statusenabled = true;
                $model2->keterangan = $diagnosa['keterangan'] ?? "";
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasifk = $r['registrasi']['norec_apd'];
                $model2->tglregistrasi = $r['registrasi']['tglregistrasi'];
                $model2->norec = $model2->generateNewId();
                $model2->objectdiagnosafk = isset($diagnosa['diagnosaa']['value']) ? $diagnosa['diagnosaa']['value'] : null;
                $model2->objectdiagnosapasienfk = $model->norec;
                $model2->objectjenisdiagnosafk = $diagnosa['jenisDiagnosa'] ? $diagnosa['jenisDiagnosa']['value'] : null;
                $model2->tglinputdiagnosa = $r['details'][0]['tgl'];
                $model2->keterangan = $diagnosa['keterangan'] ?? "";
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasi =  $r['registrasi']['noregistrasi'];
                $model2->iskasusbaru =  null;
                $model2->iskasuslama =  null;
                $model2->save();
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Diagnosa X Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveDiagnosaIXCPPTRanap(Request $r)
    {
        DB::beginTransaction();
        try {
            $data = $r['details'][0]['diagnosaDokter9'];
            $transMessage = "Simpan Diagnosa IX Berhasil";
            DetailDiagnosaTindakanPasien::where('noregistrasi', $r['registrasi']['noregistrasi'])->delete();
            DiagnosaTindakanPasien::where('objectpasienfk', $r['registrasi']['norec_apd'])->delete();

            // Create DiagnosaPasien
            foreach ($data as $key => $diagnosa) {
                $model = new DiagnosaTindakanPasien();
                $model->norec = $model->generateNewId();
                $model->kdprofile = $this->kdProfile;
                $model->statusenabled = true;
                $model->objectpasienfk = $r['registrasi']['norec_apd'];
                $model->tglpendaftaran = $r['registrasi']['tglregistrasi'];
                $model->save();

                $model2 = new DetailDiagnosaTindakanPasien();
                $model2->norec = $model2->generateNewId();
                $model2->kdprofile = $this->kdProfile;
                $model2->statusenabled = true;
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasifk = $r['registrasi']['norec_apd'];
                $model2->objectdiagnosatindakanfk = isset($diagnosa['diagnosaa']) ? $diagnosa['diagnosaa']['value'] : null;
                $model2->objectdiagnosatindakanpasienfk = $model->norec;
                $model2->tglinputdiagnosa = $r['details'][0]['tgl'];
                $model2->keterangantindakan = $diagnosa['keterangan'] ?? "";
                $model2->objectpegawaifk = $this->getPegawai()->id;
                $model2->noregistrasi =  $r['registrasi']['noregistrasi'];
                $model2->save();
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Diagnosa IX Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getEMR(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])

            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) &&  $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }
    public function getEMRPerdosi(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        // $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])

            ->where('pasien.nocmfk', $nocmfk)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if ($r['kelompokPegawai'] == 'perawat') {
            $res = $res->where('userBy', '=', $r['kelompokPegawai']);
        }
        if (isset($r['index_tabs']) &&  $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int) $r['index_tabs']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();
        if (count($res) > 0) {
            $res = $res->toArray();
            foreach ($res as $k => $rr) {
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }
    public function getEMRCPPT(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', '=', $r['norec_pd']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();

        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            // ->where('norec_pd', $r['norec_pd'])
            ->where('nocmfk', $nocmfk)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        // if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
        //     $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
        // }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
        }
        $cppt = $cppt->get();

        // Use map to transform the collection
        $cppt = $cppt->map(function ($item) {
            if (isset($item['diagnosaDokter'])) {
                $uniqueDiagnosa = [];

                // Deduplicate based on diagnosaa, jenisDiagnosa, and keterangan values
                foreach ($item['diagnosaDokter'] as $diagnosa) {
                    $diagnosaValue = $diagnosa['diagnosaa']['value'] ?? "";
                    $jeniDiagnosaValue = $diagnosa['jenisDiagnosa']['value'] ?? "";
                    $keteranganValue = $diagnosa['keterangan'] ?? "";

                    // Create a unique key
                    $uniqueString = $diagnosaValue . $jeniDiagnosaValue . $keteranganValue;

                    // Use the unique string to filter duplicates
                    $uniqueDiagnosa[$uniqueString] = $diagnosa;
                }

                // Replace the original diagnosaDokter with the deduplicated one
                $item['diagnosaDokter'] = array_values($uniqueDiagnosa);
            }
            if (isset($item['diagnosaDokter9'])) {
                $uniqueDiagnosa = [];

                // Deduplicate based on diagnosaa, jenisDiagnosa, and keterangan values
                foreach ($item['diagnosaDokter9'] as $diagnosa) {
                    $diagnosaValue = $diagnosa['diagnosaa']['value'] ?? "";
                    $keteranganValue = $diagnosa['keterangan'] ?? "";

                    // Create a unique key
                    $uniqueString = $diagnosaValue . $keteranganValue;

                    // Use the unique string to filter duplicates
                    $uniqueDiagnosa[$uniqueString] = $diagnosa;
                }

                // Replace the original diagnosaDokter with the deduplicated one
                $item['diagnosaDokter9'] = array_values($uniqueDiagnosa);
            }

            // Return the modified item
            return $item;
        });

        // Return the transformed collection

        if (count($res) > 0) {
            $res = $res->toArray();
            $cppt = $cppt->toArray();
            foreach ($res as $k => $rr) {
                $res[$k]['details'] = [];
                foreach ($cppt as $z => $x) {
                    unset($cppt[$z]['_id']);
                    if ($rr['emrpasienfk'] == $cppt[$z]['emrpasienfk']) {
                        $res[$k]['details'][] = $cppt[$z];
                    }
                }
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }
    public function getEMRCPPTRanap(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', '=', $r['norec_pd']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();

        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            // ->where('norec_pd', $r['norec_pd'])
            ->where('nocmfk', $nocmfk)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        // if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
        //     $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
        // }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
        }
        if (isset($r['tipe']) &&  $r['tipe'] != '' && $r['tipe'] !== 'all') {
            if ($r['tipe'] == 'login') {
                $cppt = $cppt->where('tenagaMedis.value', $this->getPegawai()->id);
            } else {
                $cppt = $cppt->where('flag', $r['tipe']);
            }
        }
        $cppt = $cppt->orderByDesc('tgl')->get();

        // Use map to transform the collection
        $cppt = $cppt->map(function ($item) {
            if (isset($item['diagnosaDokter'])) {
                $uniqueDiagnosa = [];

                // Deduplicate based on diagnosaa, jenisDiagnosa, and keterangan values
                foreach ($item['diagnosaDokter'] as $diagnosa) {
                    if ($diagnosa) {
                        $diagnosaValue = $diagnosa['diagnosaa']['value'] ?? "";
                        $jeniDiagnosaValue = $diagnosa['jenisDiagnosa']['value'] ?? "";
                        $keteranganValue = $diagnosa['keterangan'] ?? "";

                        // Create a unique key
                        $uniqueString = $diagnosaValue . $jeniDiagnosaValue . $keteranganValue;

                        // Use the unique string to filter duplicates
                        $uniqueDiagnosa[$uniqueString] = $diagnosa;
                    }
                }

                // Replace the original diagnosaDokter with the deduplicated one
                $item['diagnosaDokter'] = array_values($uniqueDiagnosa);
            }
            if (isset($item['diagnosaDokter9'])) {
                $uniqueDiagnosa = [];

                // Deduplicate based on diagnosaa, jenisDiagnosa, and keterangan values
                foreach ($item['diagnosaDokter9'] as $diagnosa) {
                    if ($diagnosa) {
                        $diagnosaValue = $diagnosa['diagnosaa']['value'] ?? "";
                        $keteranganValue = $diagnosa['keterangan'] ?? "";

                        // Create a unique key
                        $uniqueString = $diagnosaValue . $keteranganValue;

                        // Use the unique string to filter duplicates
                        $uniqueDiagnosa[$uniqueString] = $diagnosa;
                    }
                }

                // Replace the original diagnosaDokter with the deduplicated one
                $item['diagnosaDokter9'] = array_values($uniqueDiagnosa);
            }

            // Return the modified item
            return $item;
        });


        // Return the transformed collection

        if (count($res) > 0) {
            $res = $res->toArray();
            $cppt = $cppt->toArray();
            foreach ($res as $k => $rr) {
                $res[$k]['details'] = [];
                foreach ($cppt as $z => $x) {
                    unset($cppt[$z]['_id']);
                    // if ($rr['emrpasienfk'] == $cppt[$z]['emrpasienfk']) {
                    // }
                    $res[$k]['details'][] = $cppt[$z];
                }
                unset($res[$k]['_id']);
            }
        }

        return $this->respond($res);
    }

    public function hapusEMRCPPT(Request $r)
    {
        DB::beginTransaction();
        try {
            // Mengambil ID dari request
            $pasien = DB::table('pasien_m')->where('id', $r->input('nocmfk'))->first();
            $pasiendaftar = DB::table('pasiendaftar_t')->where('norec', $r->input('norec_pd'))->first();
            $id = $r->input('uuid'); // Pastikan field 'id' dikirim dari frontend

            // Menghapus data berdasarkan ID dari koleksi 'ResumeEMR'
            $deleted = DB::connection('mongodb')
                ->table('CPPTDetail')
                ->where('uuid', $id)
                ->delete();

            // Mengatur pesan sukses atau gagal
            if ($deleted) {
                $transMessage = "Data berhasil dihapus";
                $result = [
                    'result' => true,
                    'status' => 200
                ];
                $this->LOGGING('Hapus CPPT', $r->input('norec_pd'), 'CPPTDetail', "Penghapusan CPPT {$pasien->namapasien} ({$pasien->nocm}) - {$pasiendaftar->noregistrasi} tanggal {$r->input('tgl')} flag: {$r->input('flag')}");
            } else {
                $transMessage = "Data tidak ditemukan atau tidak dapat dihapus";
                $result = [
                    'result' => false,
                    'status' => 404
                ];
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $transMessage = "Terjadi kesalahan saat menghapus data";
            $result = [
                'result' => false,
                'status' => 500,
                'error' => $th->getMessage()
            ];
        }

        // Return menggunakan format yang diinginkan
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function verifEMRCPPT(Request $r)
    {
        DB::beginTransaction();
        try {
            // Mengambil ID dari request
            $pasien = DB::table('pasien_m')->where('id', $r->input('nocmfk'))->first();
            $pasiendaftar = DB::table('pasiendaftar_t')->where('norec', $r->input('norec_pd'))->first();
            $id = $r->input('uuid'); // Pastikan field 'id' dikirim dari frontend

            $tanggalVerif = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');

            // Menghapus data berdasarkan ID dari koleksi 'ResumeEMR'
            $updated = DB::connection('mongodb')
                ->table('CPPTDetail')
                ->where('uuid', $id)
                ->update([
                    'isverif' => true,
                    'catatanDPJP' => $r->input('catatanDPJP'),
                    'tglverifDPJP' => $tanggalVerif
                ]);

            // Mengatur pesan sukses atau gagal
            if ($updated) {
                $transMessage = "Data berhasil diverifikasi";
                $result = [
                    'result' => true,
                    'status' => 200
                ];
                $this->LOGGING('Verif CPPT', $r->input('norec_pd'), 'CPPTDetail', "Verifikasi CPPT {$pasien->namapasien} ({$pasien->nocm}) - {$pasiendaftar->noregistrasi} tanggal {$r->input('tgl')} flag: {$r->input('flag')}");
            } else {
                $transMessage = "Data tidak ditemukan atau tidak dapat diverifikasi";
                $result = [
                    'result' => false,
                    'status' => 404
                ];
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $transMessage = "Terjadi kesalahan saat verifikasi data";
            $result = [
                'result' => false,
                'status' => 500,
                'error' => $th->getMessage()
            ];
        }

        // Return menggunakan format yang diinginkan
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function verifHarianEMRCPPT(Request $r)
    {
        DB::beginTransaction();
        try {
            // Mengambil ID dari request
            $pasien = DB::table('pasien_m')->where('id', $r->input('nocmfk'))->first();
            $pasiendaftar = DB::table('pasiendaftar_t')->where('norec', $r->input('norec_pd'))->first();
            $norec_pd = $r->input('norec_pd');
            $tanggal = $r->input('tanggal'); // Format: "18-01-2025"

            // Ubah ke format UTC sebelum simpan ke database
            $tanggalAwal = Carbon::createFromFormat('d-m-Y H:i:s', "$tanggal 00:00:00", 'Asia/Jakarta')->setTimezone('Asia/Jakarta')->toDateTimeString();
            $tanggalAkhir = Carbon::createFromFormat('d-m-Y H:i:s', "$tanggal 23:59:59", 'Asia/Jakarta')->setTimezone('Asia/Jakarta')->toDateTimeString();

            $tanggalAwalMongo = Carbon::createFromFormat('d-m-Y H:i:s', "$tanggal 07:00:00")
                ->setTimezone('UTC') // Ubah ke UTC
                ->format('Y-m-d\TH:i:s.000\Z');
            $tanggalAkhirMongo = Carbon::createFromFormat('d-m-Y H:i:s', "$tanggal 30:59:59")
                ->setTimezone('UTC') // Ubah ke UTC
                ->format('Y-m-d\TH:i:s.000\Z');

            $tanggalVerif = now()->setTimezone('Asia/Jakarta')->format('Y-m-d H:i:s');


            // Update data di MongoDB berdasarkan `norec_pd` dan `tgl`
            $updated = DB::connection('mongodb')
                ->table('CPPTDetail')
                ->where('norec_pd', $norec_pd)
                ->whereNull('isverif')
                ->whereNotIn('flag', ['sbar', 'sbarapt'])
                ->where(function ($query) use ($tanggalAwal, $tanggalAkhir, $tanggalAwalMongo, $tanggalAkhirMongo) {
                    $query->whereBetween('tgl', [$tanggalAwal, $tanggalAkhir])
                        ->orWhereBetween('tgl', [$tanggalAwalMongo, $tanggalAkhirMongo]);
                })
                ->update([
                    'isverif' => true,
                    'tglverifDPJP' => $tanggalVerif
                ]);


            // Mengatur pesan sukses atau gagal
            if ($updated) {
                $transMessage = "Data berhasil diverifikasi";
                $result = [
                    'result' => true,
                    'status' => 200
                ];
                $this->LOGGING(
                    'Verif Harian CPPT',
                    $norec_pd,
                    'CPPTDetail',
                    "Verifikasi Harian CPPT {$pasien->namapasien} ({$pasien->nocm}) - {$pasiendaftar->noregistrasi} tanggal {$tanggalVerif}"
                );
            } else {
                $transMessage = "Data tidak ditemukan atau tidak dapat diverifikasi";
                $result = [
                    'result' => false,
                    'status' => 404
                ];
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();

            $transMessage = "Terjadi kesalahan saat verifikasi data";
            $result = [
                'result' => false,
                'status' => 500,
                'error' => $th->getMessage()
            ];
        }

        // Return menggunakan format yang diinginkan
        return $this->respond($result, $result['status'], $transMessage);
    }

    public function saveEMRCPPT(Request $r)
    {

        $isDokterAuthor = DB::table('pegawai_m as pg')
            ->join('jenispegawai_m as jp', 'pg.objectjenispegawaifk', 'jp.id')
            ->select('pg.namalengkap', 'jp.jenispegawai', 'pg.objectjenispegawaifk')
            ->where('pg.id', $this->getPegawai()->id)
            ->where('pg.statusenabled', true)
            ->where('pg.objectjenispegawaifk', 1)
            ->where('pg.kdprofile', $this->kdProfile)
            ->count();

        DB::beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $registrasi = $r->input('data')['registrasi'];
            $pasien = $r->input('data')['pasien'];
            if ($r['norec_emr'] == '') {
                $noemr = $this->generateCodeBySeqTable(new EMRPasien(), 'noemr', 15, 'MR' . date('ym') . '/', $this->kdProfile);
                $EMR = new EMRPasien();
                $norec = $EMR->generateNewId();
                $EMR->norec = $norec;
                $EMR->kdprofile = $this->kdProfile;
                $EMR->statusenabled = true;
                $EMR->noregistrasifk = $registrasi['norec_pd'];
                $EMR->noregistrasi = $registrasi['noregistrasi'];
                $EMRPASIENDETAIL = [];
            } else {
                $EMR = EMRPasien::where('norec', $r['norec_emr'])
                    ->where('nocm',  $pasien['nocm'])
                    ->where('kdprofile', $this->kdProfile)
                    ->first();
                $noemr = $EMR->noemr;
                $norec =  $EMR->norec;

                $EMRPASIENDETAIL =  DB::connection('mongodb')
                    ->table('CPPTDetail')
                    ->where('emrpasienfk', $norec)
                    ->get();
            }
            $EMR->noemr = $noemr;
            $EMR->emrfk = 0;
            $EMR->nocm =   $pasien['nocm'];
            $EMR->nocmfk = $pasien['nocmfk'];
            $EMR->namapasien = $pasien['namapasien'];
            $EMR->jeniskelamin = $pasien['jeniskelamin'];
            $EMR->umur = $pasien['umur'];
            $EMR->tgllahir = $pasien['tgllahir'];
            $EMR->notelepon = $pasien['nohp'];
            $EMR->alamat = $pasien['alamatlengkap'];
            $EMR->kelompokpasien = $registrasi['kelompokpasien'];
            $EMR->tglregistrasi = $registrasi['tglregistrasi'];
            $EMR->norec_apd = $registrasi['norec_apd'];
            $EMR->namakelas = $registrasi['namakelas'];
            $EMR->namaruangan = $registrasi['namaruangan'];
            $EMR->jenisemr = $r['jenis_emr'];
            $EMR->pegawaifk = $this->getPegawai()->id;
            $EMR->tglemr = $now;
            $EMR->save();
            // MONGO
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' =>  $this->getProfile()->namalengkap,
            );
            $data['statusenabled'] = true;
            $data['noemr'] = $EMR->noemr;
            $data['emrpasienfk'] = $norec;



            $formExist = DB::connection('mongodb')
                ->table('#ResumeEMR')
                ->where('emrpasienfk', $norec)
                ->where('table', $r->input('collection'))
                ->where('noregistrasifk',  $registrasi['norec_pd'])
                ->first();

            if (empty($formExist)) {
                $resume = array(
                    'id' => $this->Uuid4(),
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($r['icon']) ? $r['icon'] : null,
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->insert($resume);
            } else {
                $resume = array(
                    'kdprofile' => $this->kdProfile,
                    'statusenabled' => true,
                    'noregistrasifk' => $registrasi['norec_pd'],
                    'nocmfk' => $pasien['nocmfk'],
                    'emrpasienfk' => $norec,
                    'table' => $r->input('collection'),
                    'last_update' => $now,
                    'author' => $this->getPegawai()->namalengkap,
                    'url_form' => $r['url_form'],
                    'namaemr' => $r['name_form'],
                    'noemr' => $EMR->noemr,
                    'icon' => isset($formExist['icon']) ? $formExist['icon'] : null,
                );
                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('id', $formExist['id'])
                    ->update($resume);
            }

            $i = 0;

            $sama = 0;
            $j = 0;
            $h = 0;
            $d = 0;

            foreach ($data['details']  as $item) {
                $item['emrpasienfk'] = $norec;
                $item['kdprofile'] = $this->kdProfile;
                $item['statusenabled'] = true;
                $item['norec_pd'] = $registrasi['norec_pd'];
                $item['nocmfk'] =  $pasien['nocmfk'];

                $sama = 0;

                foreach ($EMRPASIENDETAIL as $emrupdate) {
                    $sama = 0;
                    if ($item['uuid'] == $emrupdate['uuid']) {
                        $sama =  2;
                        break;
                    }
                }
                if ($sama ==  2) {
                    $item['update_before'] = $item['updated_at'] ?? $now;
                    $item['updated_at'] =  $now;
                    $item['update_count'] = isset($item['update_count']) ? $item['update_count'] + 1 : 1;
                    DB::connection('mongodb')
                        ->table('CPPTDetail')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('emrpasienfk', $norec)
                        ->where('uuid', $item['uuid'])
                        ->update($item);

                    $j++;
                }

                if ($sama ==  0) {
                    $item['created_at'] =  $now;
                    $item['updated_at'] =  null;
                    $item['update_count'] = 0;
                    DB::connection('mongodb')
                        ->table('CPPTDetail')
                        ->insert($item);
                    $h++;
                }
                $i = $i + 1;
            }
            unset($data['details']);
            if ($r->input('id') == '') {

                $data['id'] = $this->Uuid4();
                $data['created_at'] =  $now;
                $data['updated_at'] =  null;
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                $data['updated_at'] =  $now;
                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'))
                    ->update($data);
            }
            $transMessage = "Sukses";
            if ($isDokterAuthor > 0) {
                AntrianPasienDiperiksa::where('noregistrasifk', $registrasi['norec_pd'])
                    ->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)
                    ->update(['status' => 'Selesai']);
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr"  =>  $EMR->norec,
                    "noemr"  =>  $EMR->noemr,
                    "id"  =>  $data['id'],
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getRiwayatEMR(Request $r)
    {
        $data = DB::table('emrpasien_t as emrp')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'emrp.pegawaifk')
            ->select('emrp.*', 'pg.namalengkap')
            ->where('emrp.statusenabled', true)
            ->where('emrp.kdprofile', $this->kdProfile)
            ->orderBy('emrp.tglemr', 'desc');

        if (isset($r['noemr']) && $r['noemr'] != '') {
            $data = $data->where('emrp.noemr', $r['noemr']);
        }
        if (isset($r['norec']) && $r['norec'] != '') {
            $data = $data->where('emrp.norec', $r['norec']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('emrp.nocm', $r['nocm']);
        }
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $data = $data->where('emrp.nocmfk', $r['nocmfk']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $data = $data->where('emrp.noregistrasifk', $r['norec_pd']);
        }
        if (isset($r['jenis_emr']) && $r['jenis_emr'] != '') {
            $data = $data->where('emrp.jenisemr', '=', $r['jenis_emr']);
        }
        $data = $data->get();

        return $this->respond($data);
    }
    public function getRiwayatEMR_DETAIL(Request $r)
    {
        $exp = explode(',', $r['collection']);
        $array_col = [];
        foreach ($exp as $col) {
            $array_col[$col] = DB::connection('mongodb')
                ->table($col)
                ->where('emrpasienfk', $r['norec'])
                ->where('profile.kdprofile', $this->kdProfile)
                ->first();
        }

        return $this->respond($array_col);
    }
    public function hapusEMR(Request $r)
    {
        DB::beginTransaction();
        try {
            EMRPasien::where('norec', $r['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update([
                    'statusenabled' => false
                ]);
            $exp = explode(',', $r['collection']);
            foreach ($exp as $col) {
                DB::connection('mongodb')
                    ->table($col)
                    ->where('emrpasienfk', $r['norec'])
                    ->where('profile.kdprofile', $this->kdProfile)
                    ->update([
                        'statusenabled' => false
                    ]);

                DB::connection('mongodb')
                    ->table('#ResumeEMR')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('emrpasienfk', $r['norec'])
                    ->where('table', '=', $col)
                    ->update([
                        'statusenabled' => false
                    ]);
            }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null, //$e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function menuEMR(Request $r)
    {
        $dataRaw = DB::table('emr_t as emr')
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.statusenabled', true)
            ->where('emr.namaemr', $r['namaemr'])
            ->select('emr.*')
            ->orderBy('emr.nourut');

        if (isset($r['departemen']) && $r['departemen'] != '' && $r['departemen'] != 'undefined') {
            $dataMapping = DB::table('mapruangantoemr_t as mapemr')
                ->select('mapemr.emrfk')
                ->where('mapemr.kdprofile', $this->kdProfile)
                ->where('mapemr.statusenabled', true)
                ->where('mapemr.objectdepartemenfk', $r['departemen']);
            if (isset($r['ruangan']) && $r['ruangan'] != '') {
                $dataMapping = $dataMapping->where('mapemr.objectruanganfk', $r['ruangan']);
            }
            $dataMapping = $dataMapping->get();

            if (count($dataMapping) > 0) {
                $collection = collect($dataMapping);
                $dataRaw = $dataRaw->whereRaw("emr.id in (" . $collection->implode('emrfk', ', ') . ")");
            }
        }

        $dataRaw = $dataRaw->get();
        if (count($dataRaw) == 0) {
            $dataRaw = DB::table('emr_t as emr')
                ->where('emr.kdprofile', $this->kdProfile)
                ->where('emr.statusenabled', true)
                ->where('emr.namaemr', $r['namaemr'])
                ->select('emr.*')
                ->orderBy('emr.nourut')
                ->get();
        }
        $dataraw3 = [];
        foreach ($dataRaw as $dataRaw2) {
            $dataraw3[] = array(
                'id' => $dataRaw2->id,
                // 'kdprofile' => $dataRaw2->kdprofile,
                // 'statusenabled' => $dataRaw2->statusenabled,
                // 'kodeexternal' => $dataRaw2->kodeexternal,
                // 'namaexternal' => $dataRaw2->namaexternal,
                // 'reportdisplay' => $dataRaw2->reportdisplay,
                // 'namaemr' => $dataRaw2->namaemr,
                'label' => $dataRaw2->caption,
                'headfk' => $dataRaw2->headfk,
                'nourut' => $dataRaw2->nourut,
                'url_form' => $dataRaw2->url_form,
                'collection' => $dataRaw2->collection
            );
        }
        $data = $dataraw3;

        function recursiveElements($data)
        {
            $elements = [];
            $tree = [];
            foreach ($data as &$element) {
                $id = $element['id'];
                $parent_id = $element['headfk'];

                $elements[$id] = &$element;
                if (isset($elements[$parent_id])) {
                    $elements[$parent_id]['child'][] = &$element;
                } else {
                    if ($parent_id <= 10) {
                        $tree[] = &$element;
                    }
                }
                //}
            }
            return $tree;
        }




        $data = recursiveElements($data);


        $result = array(
            'data' => $data,
            'total' => count($dataRaw),
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    public function getDataComboPegawai(Request $request)
    {

        $data = Pegawai::select('id', 'namalengkap as nama')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->search($request['name'])
            ->take(10)
            ->get();

        return $this->respond($data);
    }

    public function getDataComboDiagnosa(Request $request)
    {

        $data = Diagnosa::select('id', 'kddiagnosa', 'namadiagnosa as nama')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->search($request['name'])
            ->take(10)
            ->get();

        $dt = [];
        foreach ($data as $item) {
            $dt[] = array(
                'id' => $item->value,
                'nama' => $item->kddiagnosa . ' - ' . $item->nama,
            );
        }

        return $this->respond($dt);
    }

    public function getEMRTabs(Request $r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : (int)$r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('pasien.nocmfk', $nocmfk);
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', $r['norec_pd']);
        }
        if (isset($r['index_tabs']) &&  $r['index_tabs'] != '') {
            $res = $res->where('index_tabs', (int)$r['index_tabs']);
        }
        $res = $res->orderByDesc('index_tabs');
        $res = $res->first();
        $index = 1;
        if (!empty($res)) {
            $index = (int) $res['index_tabs'];
        }

        return $this->respond($index);
    }

    public function getDiagnosaPasienByNoregICD9(Request $request)
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
                'dtp.norec as norec_diagnosapasien',
                'ddt.norec as norec_detaildpasien',
                'dt.*',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                'ddt.tglinputdiagnosa'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile);
        //            ->join ('jenisdiagnosa_m as jd','jd.id','=','ddp.objectjenisdiagnosafk');
        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        $data = $data->get();

        return $this->respond($data);
    }

    public function getDiagnosaPasienICD10(Request $request)
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
                'ddp.tglinputdiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                'dp.norec as norec_diagnosapasien',
                'ddp.norec as norec_detaildpasien',
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dg.*',
                'dp.iskasusbaru',
                'dp.iskasuslama',
                DB::raw("CAST(ddp.tglinputdiagnosa AS DATE)"),
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->orderby('ddp.tglinputdiagnosa', 'desc');

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('ps.id', '=', $request['nocmfk']);
        };

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $request['norec_pd']);
        };

        $data = $data->get();

        return $this->respond($data);
    }

    public function getDropdownDiagnosaKeper(Request $request)
    {
        $data = EMR::select('caption', 'url_form', 'id')->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)->where('headfk', 93)
            ->where('caption', 'ILIKE', '%' . $request['search'] . '%')
            ->limit(10)
            ->get();

        return $this->respond($data);
    }

    public function dropdownEMR(Request $r, $table)
    {
        try {
            $search = $r['query'];
            $data = DB::table($table);
            if (isset($r['select']) && $r['select'] != '') {
                if ($table == 'diagnosa_m') {
                    $data = $data->select(DB::raw("id as value,kddiagnosa || ' - ' || namadiagnosa as label"));
                } else if ($table == 'diagnosatindakan_m') {
                    $data = $data->select(DB::raw("id as value,kddiagnosatindakan || ' - ' || namadiagnosatindakan as label"));
                } else if ($table == 'emr_t') {
                } else {
                    $exp = explode(',', $r['select']);
                    foreach ($exp as $items) {
                        if ($items == 'id') {
                            $items = $items . ' as value';
                        }
                        $select[] = $items;
                    }
                    if (isset($exp[1]) && $exp[1] != 'id') {
                        $select[] = $exp[1] . ' as label';
                        foreach ($select as $k => $sel) {
                            if ($sel == $exp[1]) {
                                array_splice($select, $k, 1);
                            }
                        }
                    }
                    $data = $data->select($select);
                }
            }
            if (isset($r['param_search']) && $r['param_search'] != '') {
                $exp = explode(',', $r['param_search']);
                foreach ($exp as $items) {
                    $where[] = [$items, 'ILIKE', '%' . $search . '%'];
                }
                $data = $data->where($where);
            }
            if (isset($r['settingdatafix']) && $r['settingdatafix'] != '') {
                $exp = explode(',', $r['settingdatafix']);
                $arrayDataFix = $this->settingFix($exp[1]);
                if ($table == 'ruangan_m') {
                    if (count($exp) > 2) {
                        $arrayDataFix = $arrayDataFix . ',' . $this->settingFix($exp[2]);
                    }
                }
                $valueSet = explode(',', $arrayDataFix);
                $data = $data->whereIn($exp[0], $valueSet);
            }
            if (isset($r['kondisi']) && $r['kondisi'] != '') {
                $exp = explode(',', $r['kondisi']);
                $data = $data->where($exp[0], $exp[1]);
            }
            $data = $data->where('statusenabled', true);

            if (isset($r['orderby']) && $r['orderby'] != '') {
                $data = $data->orderBy($r['orderby']);
            }
            $data = $data->limit($r['limit']);
            $data = $data->get();
        } catch (Exception $e) {
            $data = $e->getMessage() . ' ' . $e->getLine();
        }

        return $this->respond($data);
    }
    public function camelCaseToSpace($input)
    {
        $pattern = '/(?<!^)[A-Z]/'; // Match uppercase letters not at the beginning
        $replacement = ' $0'; // Insert a space before the uppercase letter
        $output = preg_replace($pattern, $replacement, $input);
        return trim($output);
    }
    public function getEMRDynamic(Request $r)
    {
        $data = null;
        if (!empty($r['collection'])) {
            $data = DB::connection('mongodb')
                ->table('#DynamicForm')
                ->where('collection', $r['collection'])
                ->where('kdprofile', $this->kdProfile)
                ->first();
        }
        $result = [];
        $dataraw3A = [];
        $dataraw3B = [];
        $dataraw3C = [];
        $dataraw3D = [];
        if (!empty($data)) {
            foreach ($data['data'] as $d) {
                if (isset($d['kolom'])) {
                    if ($d['kolom'] == 1) {
                        $dataraw3A[] = $d;
                    } elseif ($d['kolom'] == 2) {
                        $dataraw3B[] = $d;
                    } elseif ($d['kolom'] == 3) {
                        $dataraw3C[] = $d;
                    } else {
                        $dataraw3D[] = $d;
                    }
                } else {
                    $dataraw3D[] = $d;
                }
            }
        }
        $result = array(
            'kolom1' => $dataraw3A,
            'kolom2' => $dataraw3B,
            'kolom3' => $dataraw3C,
            'kolom4' => $dataraw3D,
            'title' => '-',
            'classgrid' => !empty($data) ? $data['classgrid'] : '',
            'namaemr' => !empty($data) ? $this->camelCaseToSpace($data['collection']) : '',
            'tandatangan' => !empty($data) ? $data['tandatangan'] : [],
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function getDataExist(Request $r)
    {
        $res = DB::connection('mongodb')
            ->table('VitalSign')
            ->select('tinggiBadan', 'IMT', 'SPO2', 'beratBadan', 'lingkarPerut', 'nadi', 'suhu', 'tekananDarah', 'pernapasan')
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('statusenabled', true)
            ->first();

        unset($res['_id']);
        return $this->respond($res);
    }

    public function getDokterDPJP(Request $r)
    {
        $dokter = DB::table('pasiendaftar_t as pd')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(
                'pd.nocmfk',
                'pg.namalengkap as dokter',
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->first();

        return $this->respond($dokter);
    }
    public function getTandaTangan($pegawaifk)
    {
        $res = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int) $pegawaifk)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();
        return $this->respond($res);
    }

    public function getAutoFill(Request $r)
    {
        $select = explode(',', $r->input('field'));
        $res = DB::connection('mongodb')
            ->table($r->input('collection'))
            ->select($select)
            ->where('registrasi.norec_pd', $r->input('norec_pd'))
            ->where('statusenabled', true)
            // ->orderByDesc('updated_at')
            ->orderByDesc('created_at')
            ->first();

        unset($res['_id']);
        return $this->respond($res);
    }
    public function getAutoFillCppt(Request $r)
    {
        $select = explode(',', $r->input('field'));
        $res = DB::connection('mongodb')
            ->table($r->input('collection'))
            ->select($select)
            ->where('nocmfk', $r->input('nocmfk'))
            ->where('norec_pd', $r->input('norec_pd'))
            ->where('statusenabled', true)
            ->orderByDesc('updated_at')
            ->orderByDesc('created_at');

        if (isset($r['flag']) && $r['flag'] !== null && $r['flag'] !== 'undefined') {
            $res->where('flag', $r['flag']);
        }
        $res = $res->first();

        unset($res['_id']);
        return $this->respond($res);
    }

    public function getAutoRegisterPasien(Request $r)
    {
        $nocmfk = $r->nocmfk;
        $data = DB::table('pasiendaftar_t as pd')
            ->select('pd.tglregistrasi', 'ps.namapasien', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as rm', 'rm.id', '=', 'apd.objectruanganfk')
            ->where('pd.nocmfk', $nocmfk)
            ->whereIn('rm.id', [171, 172, 173, 167, 168, 169, 170])
            ->orderBy('pd.tglregistrasi', 'DESC')
            ->take(15)
            ->get();

        return $this->respond($data);
    }

    public function menuNavigasi(Request $r)
    {
        $res = DB::table('emr_t as emr')
            ->where('emr.kdprofile', $this->kdProfile)
            ->where('emr.statusenabled', true)
            ->where('emr.namaemr', 'navigasi')
            ->where('emr.caption', '<>', 'EMR')
            ->select('emr.*')
            ->orderBy('emr.nourut')
            ->get();
        $response[] = array(
            'name' => 'EMR',
            'desc' => 'Elektronik Medical Record Pasien',
            'color' => 'red',
            'icon' => 'lnir lnir-heartrate-monitor',
            'form' => 'module-emr-elektronik-medical-record',
        );
        foreach ($res as $d) {
            $response[] = array(
                'name' => $d->caption,
                'desc' =>  $d->namaexternal ? $d->namaexternal : $d->caption,
                'color' => $d->kodeexternal ? $d->kodeexternal : 'info',
                'icon' => $d->icon ? $d->icon : '',
                // 'form' => 'module-emr-order-bedah',
                'url_form' => $d->url_form,
            );
        }

        return $this->respond($response);
    }
    public function getDataBundleHais(Request $request)
    {
        // $jenis = explode(',', $request->input('jenis'));
        $data = DB::table('haisbundle_m')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            // ->whereIn('jenisbundle',$jenis)
            ->select(DB::raw(
                "id AS idbundle,namaexternal || '. ' || kelompok AS kelompok,
            namabundle as keterangan,jenisbundle,nourut,kelompok AS namakelompok"
            ))
            ->get();


        return $this->respond($data);
    }
    public function saveBundleHis(Request $r)
    {
        DB::beginTransaction();
        try {
            $now = date('Y-m-d H:i:s');
            $registrasi = $r->input('data')['registrasi'];
            $pasien = $r->input('data')['pasien'];

            $resume = array(
                'norec' => $this->Uuid4(),
                'kdprofile' => $this->kdProfile,
                'statusenabled' => true,
                'noregistrasifk' => $registrasi['norec_pd'],
                'nocmfk' => $pasien['nocmfk'],
                'table' => $r->input('collection'),
                'last_update' => $now,
                'author' => $this->getPegawai()->namalengkap,
                'url_form' => $r['url_form'],
                'namaemr' => $r['name_form'],
                'icon' => isset($r['icon']) ? $r['icon'] : null,
            );

            // MONGO
            $data = $r->input('data');
            if (isset($data['nocm'])) {
                unset($data['nocm']);
            }

            $data['user_input'] = array(
                'id' => $this->getUserId(),
                'namauser' => $this->getUsername(),
                'pegawaifk' => $this->getPegawai()->id,
                'namalengkap' => $this->getPegawai()->namalengkap,
            );
            $data['profile'] = array(
                'kdprofile' => $this->kdProfile,
                'namaprofile' =>  $this->getProfile()->namalengkap,
            );
            $data['statusenabled'] = true;

            $data['emrpasienfk'] = $resume['norec'];
            if ($r['norec_emr'] == '') {
                $noemr = $this->SEQUENCE_NEXVAL($r->input('collection_head'), 'noemr', 15, 'BH' . date('ym') . '/', $this->kdProfile);
                $resume['noemr'] = $noemr;
                $data['noemr'] = $noemr;

                DB::connection('mongodb')
                    ->table($r->input('collection_head'))
                    ->insert($resume);

                $data['id'] = $this->Uuid4();
                $data['created_at'] =  $now;
                $data['updated_at'] =  null;

                DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->insert($data);
            } else {
                $noemr =  DB::connection('mongodb')
                    ->table($r->input('collection_head'))
                    ->where('norec', $r['norec_emr'])
                    ->first()['noemr'];
                $data['noemr'] = $noemr;
                // unset($resume['norec'] );
                DB::connection('mongodb')
                    ->table($r->input('collection_head'))
                    ->where('norec', $r['norec_emr'])
                    ->update($resume);


                $data['updated_at'] =  $now;
                $update = DB::connection('mongodb')
                    ->table($r->input('collection'))
                    ->where('id', $r->input('id'));
                $data['id']  =  $r->input('id');
                if ($r['norec_emr'] != '') {
                    $update =  $update->where('emrpasienfk', $r['norec_emr']);
                }
                $update =  $update->update($data);
            }



            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "norec_emr"  =>  $data['emrpasienfk'],
                    "noemr"  => $noemr,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hapusBundleHis(Request $r)
    {
        DB::beginTransaction();
        try {

            DB::connection('mongodb')
                ->table($r->input('collection'))
                ->where('emrpasienfk', $r['norec'])
                ->where('profile.kdprofile', $this->kdProfile)
                ->update([
                    'statusenabled' => false
                ]);

            DB::connection('mongodb')
                ->table($r->input('collection_head'))
                ->where('kdprofile', $this->kdProfile)
                ->where('emrpasienfk', $r['norec'])
                ->update([
                    'statusenabled' => false
                ]);
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null, //$e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataComboPartObat(Request $request)
    {
        $dataObat = DB::table('produk_m as pr')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->where('spd.qtyproduk', '>', 0)
            ->groupBy('pr.id', 'pr.namaproduk')
            ->orderBy('pr.namaproduk');

        if (isset($request['filter']) && $request['filter']) {
            $dataObat = $dataObat->where('pr.namaproduk', 'ilike', '%' . $request['filter'] . '%');
        }

        $dataObat = $dataObat->take(10);
        $dataObat = $dataObat->get();

        return $this->respond($dataObat);
    }

    public function getFaktorRisiko(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $ett = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 1 and kdprofile=$kdProfile
        "));

        $cvl = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 2 and kdprofile=$kdProfile
        "));

        $ivl = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 3 and kdprofile=$kdProfile
        "));

        $uc = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 4 and kdprofile=$kdProfile
        "));

        $tc = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 5 and kdprofile=$kdProfile
        "));

        $op = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 6 and kdprofile=$kdProfile
        "));

        $antibiotik = collect(DB::select("
            SELECT id AS idpemeriksaan,id,pemeriksaan,jenisfaktorresikofk FROM faktorresiko_m where jenisfaktorresikofk = 7 and kdprofile=$kdProfile
        "));


        $result = array(
            'ett' => $ett,
            'cvl' => $cvl,
            'ivl' => $ivl,
            'uc' => $uc,
            'tc' => $tc,
            'op' => $op,
            'antibiotik' => $antibiotik,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }
    public function getFukuda(Request $request)
    {
        try {
            $dataJsonSend = null;
            $methods = 'GET';
            $set =  $this->settingFix('linkECGFukuda');
            $url =  str_replace(' ', '%20', $set . 'api/data/patient/data?nomr=' . $request['nocm'] . '&timeAwal=' . $request['dari'] . '&timeAkhir=' . $request['sampai']);

            $header = ['Content-Type:application/json'];

            $response = Http::withHeaders($header)
                ->{$methods}($url, $dataJsonSend);
            return $this->respond(str_replace('},]', '}]', $response));
        } catch (\Exception $e) {
            $response = $e->getMessage() . ' ' . $e->getLine();

            return $this->respond($response);
        }
    }
    public function getComboBerkas(Request $request)
    {
        $result =  DB::table('berkaspasien_m as bp')
            ->select('bp.id', 'bp.nama')
            ->where('bp.kdprofile', $this->kdProfile)
            ->where('bp.statusenabled', true)
            ->get();

        return $this->respond($result);
    }

    public function saveBerkasPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $uploadBerkasPasien = $request->file('filePasien');
            $path = 'berkaspasien/' . $request['nocm'];
            $dataDokumen = DB::table('emrdokumen_t as emrdkt')
                ->where('emrdkt.norec', '=', $request['norec'])
                ->where('emrdkt.kdprofile', $kdProfile)
                ->where('emrdkt.statusenabled', true)
                ->first();

            if ($dataDokumen == null) {

                if (!empty($uploadBerkasPasien)) {
                    $extension = $uploadBerkasPasien->getClientOriginalExtension();
                    $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;

                    $emrDkmn = new EmrDokumen();
                    $norecDkmn = $emrDkmn->generateNewId();
                    $emrDkmn->norec = $norecDkmn;
                    $emrDkmn->kdprofile = $kdProfile;
                    $emrDkmn->statusenabled = true;
                    $emrDkmn->noregistrasi = $request['noregistrasi'];
                    $emrDkmn->nocm = $request['nocm'];
                    $emrDkmn->norec_apd = $request['norec_apd'];
                    $emrDkmn->tglemr = date('Y-m-d H:i:s');
                    $emrDkmn->file = $path . '/' . $filename;
                    $emrDkmn->namafile = $filename;
                    $emrDkmn->deskripsi = $request['keterangan'];
                    $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                    if (isset($request['nama'])) {
                        $emrDkmn->nama = $request['nama'];
                    }

                    $emrDkmn->save();
                    // $request->file('filePasien')->move($path, $filename);
                    Storage::disk('ftp')->put($path . '/' . $filename, File::get($uploadBerkasPasien->getRealPath()));
                    // $request->fPut(
                    //     $path . '/' . $filename,
                    //     File::get($request->file('filePasien')->getRealPath()),
                    //     'public'
                    // );
                }
            } else {
                $emrDkmn = EmrDokumen::where('norec', $request['norec'])->first();
                $emrDkmn->deskripsi = $request['keterangan'];
                $extension = explode('.', $emrDkmn->file)[1];

                if (!empty($emrDkmn)) {
                    if (File::exists($emrDkmn->file)) {
                        $filename = 'deleted_' . date('YmdHis') . '.' . $extension;
                        Storage::disk('ftp')->put($path . '/' . $filename, File::get($request->file('filePasien')->getRealPath()));
                        // $request->fPut(
                        //     $path . '/' . $filename,
                        //     File::get($request->file('filePasien')->getRealPath()),
                        //     'public'
                        // );
                        // File::delete($emrDkmn->file);
                    }


                    $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;

                    $emrDkmn->file = $path . '/' . $filename;
                    // $request->file('filePasien')->move($path, $filename);
                    $request->fPut(
                        $path . '/' . $filename,
                        File::get($request->file('filePasien')->getRealPath()),
                        'external'
                    );
                    // $request->fPut(
                    //     $path . '/' . $filename,
                    //     File::get($request->file('filePasien')->getRealPath()),
                    //     'public'
                    // );
                } else {

                    if (File::exists($emrDkmn->file)) {
                        $filename = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                        File::move($emrDkmn->file, $path . '/' . $filename);
                        $emrDkmn->file = $path . '/' . $filename;
                    }
                }

                $emrDkmn->namafile = $request['namafile'] . '_' . date('YmdHis') . '.' . $extension;
                $emrDkmn->objectberkaspasien = $request['objectberkaspasien'];
                if (isset($request['nama'])) {
                    $emrDkmn->nama = $request['nama'];
                }
                $emrDkmn->save();
            }
            PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update([
                'isberkas' => true,
            ]);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getBerkasPasien(Request $request)
    {
        $data = DB::table('emrdokumen_t as emrdk')
            ->select('emrdk.*')
            ->where('emrdk.statusenabled', true)
            ->where('emrdk.kdprofile', $this->kdProfile);


        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('emrdk.nocm', $request['nocm']);
        }
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
            $data = $data->where('emrdk.noregistrasi', $request['noregistrasi']);
        }

        $data = $data->get();
        $disk = 'ftp';
        $filecount = 0;
        if ($request['storage']) {
            if (count($data)) {
                $file = [];
                foreach ($data as $dokumen) {
                    $originalFile = storage_path('app/temp/' . basename($dokumen->file));
                    $convertedFile = storage_path('app/temp/converted_' . basename($dokumen->file));

                    Storage::disk('local')->put('temp/' . basename($dokumen->file), Storage::disk($disk)->get($dokumen->file));

                    $ghostscriptCommand = "gs -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 -sOutputFile="
                        . escapeshellarg($convertedFile) . " " . escapeshellarg($originalFile);
                    exec($ghostscriptCommand, $output, $returnVar);

                    $file[] = $convertedFile;

                    // $localPath = storage_path('app/temp/' . basename($dokumen->file));
                    // Storage::disk('local')->put('temp/' . basename($dokumen->file), Storage::disk($disk)->get($dokumen->file));
                    // $file[] = $localPath;
                }

                $pdf = PDFMerger::init();
                foreach ($file as $data) {
                    if (file_exists($data)) {
                        $pdf->addPDF($data, 'all');
                        $filecount++;
                    }
                }
                if ($filecount > 0) {
                    $pdf->merge();
                    foreach ($file as $data) {
                        unlink($data);
                    }
                    return $pdf;
                } else {
                    return null;
                }
                // return empty($pdf);
            } else {
                return null;
            }
        }

        if ($request['pdf']) {
            if (count($data)) {
                $filePaths = [];
                foreach ($data as $dokumen) {
                    $filePath = storage_path('app/temp/' . $dokumen->file);
                    $tempFile = storage_path('app/temp/temp_' . $dokumen->namafile);
                    $originalFile = storage_path('app/' . $dokumen->file);

                    if (!file_exists(dirname($tempFile))) {
                        mkdir(dirname($tempFile), 0755, true);
                    }

                    if (file_exists($originalFile)) {
                        copy($originalFile, $tempFile);
                    } else {
                        return response()->json([
                            'metaData' => [
                                'code' => 404,
                                'message' => "File {$originalFile} tidak ditemukan."
                            ],
                            'response' => null
                        ]);
                    }

                    $ghostscriptCommand = 'gs -dBATCH -dNOPAUSE -q -sDEVICE=pdfwrite -sOutputFile="' . $tempFile . '" "' . $tempFile . '"';
                    exec($ghostscriptCommand, $output, $returnVar);

                    if ($returnVar !== 0) {
                        return response()->json([
                            'metaData' => [
                                'code' => 500,
                                'message' => 'Proses konversi PDF menggunakan Ghostscript gagal.',
                            ],
                            'response' => implode("\n", $output)
                        ]);
                    }

                    rename($tempFile, $originalFile);
                    $filePaths[] = $filePath;
                }

                $pdf = PDFMerger::init();
                foreach ($filePaths as $filePath) {
                    $pdf->addPDF($filePath, 'all');
                }

                // Tampilkan hasil merge langsung ke browser
                $pdf->merge('browser', 'hasil_merge.pdf');
            } else {
                return null;
            }
        }

        $result = array(
            'data' => $data,
            'message' => '@epic',
        );
        return $this->respond($result);
    }
    public function hapusBerkasPasien(Request $request)
    {
        DB::beginTransaction();
        try {

            $emrDkmn = EmrDokumen::where('norec', $request['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->first();

            $path = Storage::disk('public')->getAdapter()->getPathPrefix() . $emrDkmn->file;

            if (File::exists($path)) {
                File::delete($path);
            }
            $emrDkmn->delete();

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getAutoFillICD10($noregistrasi)
    {
        $data = DB::table('detaildiagnosapasien_t as ddp')
            ->select(
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.objectjenisdiagnosafk'
            )
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.noregistrasi', '=', $noregistrasi)
            ->get();

        return $this->respond($data);
    }
    public function getAutoFillICD9($noregistrasi)
    {
        $data = DB::table('detaildiagnosatindakanpasien_t as ddt')
            ->select(
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
            )
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->where('ddt.kdprofile', $this->kdProfile)
            ->where('ddt.noregistrasi', '=', $noregistrasi)
            ->get();

        return $this->respond($data);
    }

    public function GetJamKosong($kode, $tgl, $kdProfile, $eksek)
    {
        $ruangan = DB::table('ruangan_m')
            ->where('kdsubspesialisbpjs', $kode)
            ->whereRaw("(iseksekutif = false or iseksekutif is null)")
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();

        /*
        slot lokal
        */
        $hariReserve = $this->hari_ini($tgl);

        $slotruangan = DB::table('ruangan_m as ru')
            ->join('slottingkiosk_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'ru.objectdepartemenfk',
                'slot.jambuka',
                'slot.jamtutup',
                'slot.quota',
                'slot.loket',
                DB::raw("(EXTRACT(EPOCH FROM slot.jamtutup) - EXTRACT(EPOCH FROM slot.jambuka))/3600 as totaljam")
            )
            ->where('ru.statusenabled', true)
            ->where('ru.id', $ruangan->id)
            ->where('ru.kdprofile', $kdProfile)
            ->where('slot.statusenabled', true)
            ->where('slot.tanggal', $tgl)
            // ->where('slot.hari', 'ILIKE', '%'. strtoupper($hariReserve) .'%')
            ->first();

        if (empty($slotruangan)) {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }
        /*
            slot hfis
        */
        // $this->bridgingBPJSCtrl->getSetting()

        $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
            ->select('apr.norec', 'apr.tanggalreservasi')
            ->whereRaw(" to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tgl'")
            ->where('apr.objectruanganfk', $ruangan->id)
            ->where('apr.noreservasi', '!=', '-')
            ->whereNotNull('apr.noreservasi')
            ->where('apr.statusenabled', true)
            ->where('apr.kdprofile', $kdProfile)
            ->whereRaw("(apr.isbatal != false or apr.isbatal is null)")
            ->orderBy('apr.tanggalreservasi')
            ->get();

        $begin = new Carbon($slotruangan->jambuka);
        $jamBuka = $begin->format('H:i');
        $end = new Carbon($slotruangan->jamtutup);
        $jamTutup = $end->format('H:i');
        $quota = (float)$slotruangan->quota;
        if ($quota == 0) {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }
        $waktuPerorang = round(((float)$slotruangan->totaljam / (float)$slotruangan->quota) * 60, 1);

        $i = 0;
        $slotterisi = 0;
        $jamakhir = '00:00';
        $reservasi = [];
        foreach ($dataReservasi as $items) {
            $jamUse =  new Carbon($items->tanggalreservasi);
            $slotterisi += 1;
            $reservasi[] = array(
                'jamreservasi' => $jamUse->format('H:i')
            );
            $jamakhir = $jamUse->format('H:i');
        }

        $intervals = [];
        $intervalsAwal  = [];
        $begin = new \DateTime($jamBuka);
        $end = new \DateTime($jamTutup);
        $interval = \DateInterval::createFromDateString(floor($waktuPerorang) . ' minutes');

        $period = new \DatePeriod($begin, $interval, $end);
        foreach ($period as $dt) {
            $intervals[] = array(
                'jam' =>  $dt->format("H:i")
            );
            $intervalsAwal[] = array(
                'jam' =>  $dt->format("H:i")
            );
        }
        if (count($intervals) == 0) {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }

        if (count($reservasi) > 0) {
            for ($j = count($reservasi) - 1; $j >= 0; $j--) {
                for ($k = count($intervals) - 1; $k >= 0; $k--) {
                    if ($intervals[$k]['jam'] == $reservasi[$j]['jamreservasi']) {
                        array_splice($intervals, $k, 1);
                    }
                }
            }
        }

        if (count($intervals) > 0) {

            $tglAwal = date('Y-m-d', strtotime($tgl)) . ' 00:00:00';
            $tglAkhir = date('Y-m-d', strtotime($tgl)) . ' 23:59:59';

            $noantri = DB::table('antrianpasienregistrasi_t as apr')
                ->where('jenis', $ruangan->noruangan)
                ->where('loketkiosk', $slotruangan->loket)
                ->whereBetween('tanggalreservasi', [$tglAwal, $tglAkhir])
                ->max('noantrian') + 1;

            $antrian = 1;
            for ($x = 0; $x <= count($intervalsAwal); $x++) {
                if ($intervals[0]['jam'] == $intervalsAwal[$x]['jam']) {
                    $antrian = $x + 1;
                    // dd($antrian);
                    break;
                }
            }

            return array("antrian" => $noantri, "jamkosong" => $intervals[0]['jam'], "kuota" => $quota, "loket" => $slotruangan->loket);
        } else {
            return array("antrian" => 0, "jamkosong" => "00:00");
        }
    }

    public function ambilAntrean(Request $request)
    {
        $kdProfile = $this->kdProfile;
        date_default_timezone_set(config('app.timezone')); // set timezone

        // validasi parameter
        if (empty($request->nomorkartu)) {
            $result = array("metadata" => array("message" => "Nomor Kartu Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->tanggalperiksa)) {
            $result = array("metadata" => array("message" => "Tanggal Periksa Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $request['tanggalperiksa'])) {
            $result = array("metadata" => array("message" => "Format Tanggal Tidak Sesuai, format yang benar adalah yyyy-mm-dd", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($request->tanggalperiksa >= date('Y-m-d', strtotime('+90 days', strtotime(date('Y-m-d'))))) {
            $result = array("metadata" => array("message" => "Tanggal periksa maksimal 90 hari dari hari ini", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($request->tanggalperiksa == date('Y-m-d')) {
            $result = array("metadata" => array("message" => "Tanggal periksa minimal besok", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if ($request->tanggalperiksa < date('Y-m-d')) {
            $result = array("metadata" => array("message" => "Tanggal Periksa Tidak Berlaku", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        // if(date('w',strtotime( $request->tanggalperiksa )) == 0 ){
        //     $result = array("metadata"=>array("message" => "Tidak ada jadwal Poli di hari Minggu", "code" => 201));
        //     return response()->json($result, $result['metadata']['code']);
        // }

        if (empty($request->nik)) {
            $result = array("metadata" => array("message" => "NIK Belum Diisi", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (!is_numeric($request->nik) || strlen($request->nik) < 16) {
            $result = array("metadata" => array("message" => "Format NIK Tidak Sesuai ", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->nohp)) {
            $result = array("metadata" => array("message" => "No hp tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request['kdsubspesialis'])) {
            $result = array("metadata" => array("message" => "Poli Tidak Ditemukan", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->jeniskunjungan)) {
            $result = array("metadata" => array("message" => "Jenis Kunjungan tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($request->nomorreferensi)) {
            $result = array("metadata" => array("message" => "Nomor Referensi tidak boleh kosong", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $ruangan = DB::table('ruangan_m')
            ->where('kdsubspesialisbpjs', $request['kdsubspesialis'])
            ->whereRaw("(iseksekutif = false or iseksekutif is null)")
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->first();


        if (empty($ruangan)) {
            $result = array("metadata" => array("message" => "Poli Tidak Ditemukan atau belum termapping di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        if (empty($ruangan->noruangan) || $ruangan->noruangan == null) {
            $result = array("metadata" => array("message" => "No ruangan Poli Belum diatur di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $dokter = DB::table('pegawai_m')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->where('kddokterbpjs', $request->kodedokter)
            ->first();

        if (empty($dokter)) {
            $result = array("metadata" => array("message" => "Dokter Tidak Ditemukan atau belum termapping di RS", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        $eksek = false;
        $antrian = $this->GetJamKosong($request['kdsubspesialis'], $request->tanggalperiksa, $kdProfile, $eksek);

        if ($antrian['antrian'] == 0) {
            DB::rollBack();
            $result = array("metadata" => array("message" => "Jadwal Poli sedang tutup atau kuota habis", "code" => 201));
            return response()->json($result, $result['metadata']['code']);
        }

        DB::beginTransaction();
        try {
            $pasienBaru = 0;
            $pasien = DB::table('pasien_m')
                ->where(function ($query) use ($request) {
                    $query->where("nobpjs", $request->nomorkartu)
                        ->orWhere('noidentitas', $request->nik);
                })
                ->where('statusenabled', true)
                ->where('kdprofile', $kdProfile)
                ->first();

            if (empty($pasien)) {
                DB::rollBack();
                $result = array(
                    "metadata" => array(
                        "code" => 202,
                        "message" => "Data pasien ini tidak ditemukan, silahkan Melakukan Registrasi Pasien Baru"
                    )
                );
                return response()->json($result, $result['metadata']['code']);
            } else {
                $pasienBaru = 1;
            }

            $tgl = $request->tanggalperiksa;

            $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
                ->select('apr.noantrian', 'apr.noreservasi', 'ru.namaexternal', 'apr.tanggalreservasi')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
                ->whereRaw("to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tgl'")
                ->where('apr.objectruanganfk', '=', $ruangan->id)
                ->where('apr.noreservasi', '!=', '-')
                ->where('apr.noidentitas', '=', $request->nik)
                ->where('apr.nobpjs', '=', $request->nomorkartu)
                ->whereNotNull('apr.noreservasi')
                ->whereRaw("(apr.isbatal = false or apr.isbatal is null)")
                ->where('apr.statusenabled', true)
                ->first();

            if (isset($dataReservasi) && !empty($dataReservasi)) {
                DB::rollBack();
                $result = array("metadata" => array("code" => 201, "message" => "Nomor Antrean Hanya Dapat Diambil 1 Kali Pada Tanggal Yang Sama"));
                return response()->json($result, $result['metadata']['code']);
            }

            $newptp = new AntrianPasienRegistrasi();
            $newptp->norec = $newptp->generateNewId();
            $newptp->kdprofile = $kdProfile;
            $newptp->statusenabled = true;
            $newptp->objectruanganfk = $ruangan->id;
            $newptp->objectjeniskelaminfk = !empty($pasien) ? $pasien->objectjeniskelaminfk : null;
            $newptp->noreservasi = substr(UuidReservasi::generate(), 0, 7);
            $newptp->tanggalreservasi = $request->tanggalperiksa . " " . $antrian['jamkosong'];
            $newptp->tgllahir = !empty($pasien) ? $pasien->tgllahir : null;
            $newptp->objectkelompokpasienfk = "2";
            $newptp->objectpendidikanfk = 0;
            $newptp->namapasien = !empty($pasien) ? $pasien->namapasien : null;
            $newptp->tglinput = date('Y-m-d H:i:s');
            $newptp->nobpjs = $request->nomorkartu;
            $newptp->jenis = $ruangan->noruangan;
            $newptp->norujukan = $request->nomorreferensi;
            $newptp->notelepon = !empty($pasien) ? $pasien->nohp : null;
            $newptp->objectpegawaifk = $dokter->id;
            $newptp->tipepasien = !empty($pasien) ? "LAMA" : "BARU";
            $newptp->ismobilejkn = true;
            $newptp->objectasalrujukanfk = $request->jeniskunjungan;
            $newptp->type = !empty($pasien) ? "LAMA" : "BARU";
            $newptp->objectagamafk = !empty($pasien) ? $pasien->objectagamafk : null;
            if (!empty($pasien)) {
                $alamat = Alamat::where('nocmfk', $pasien->id)->first();
                if (!empty($alamat)) {
                    $newptp->alamatlengkap = $alamat->alamatlengkap;
                    $newptp->objectdesakelurahanfk = $alamat->objectdesakelurahanfk;
                    $newptp->negara = $alamat->objectnegarafk;
                }
            }
            $newptp->objectgolongandarahfk = !empty($pasien) ? $pasien->objectgolongandarahfk : null;
            $newptp->kebangsaan = !empty($pasien) ? $pasien->objectkebangsaanfk : null;
            $newptp->namaayah = !empty($pasien) ? $pasien->namaayah : null;
            $newptp->namaibu = !empty($pasien) ? $pasien->namaibu : null;
            $newptp->namasuamiistri = !empty($pasien) ? $pasien->namasuamiistri : null;
            $newptp->noaditional = !empty($pasien) ? $pasien->noaditional : null;
            $newptp->noantrian = $antrian['antrian'];
            $newptp->noidentitas =  $request->nik;
            $newptp->nocmfk = !empty($pasien) ? $pasien->id : null;
            $newptp->paspor = !empty($pasien) ? $pasien->paspor : null;
            $newptp->objectpekerjaanfk = !empty($pasien) ? $pasien->objectpekerjaanfk : null;
            $newptp->objectpendidikanfk = !empty($pasien) && $pasien->objectpendidikanfk != null ? $pasien->objectpendidikanfk :  0;
            $newptp->objectstatusperkawinanfk = !empty($pasien) ? $pasien->objectstatusperkawinanfk : null;
            $newptp->tempatlahir = !empty($pasien) ? $pasien->tempatlahir : null;
            $newptp->statuspanggil  = 0;

            $newptp->loketkiosk  = $antrian['loket'];
            $newptp->save();
            $norecAntrianRegis = $newptp->norec;

            $tglAwal = date('Y-m-d', strtotime($request->tanggalperiksa)) . ' 00:00:00';
            $tglAkhir = date('Y-m-d', strtotime($request->tanggalperiksa)) . ' 23:59:59';
            $idNonKelas = (int) $this->settingFix('idNonKelas');
            $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
            $idrekananBPJS = (int) $this->settingFix('BPJS_kodeRekanan');
            $idtipelayananBPJS = (int) $this->settingFix('idJenisPelayananReguler');

            $asalrujukan = 20;
            switch ($request->jeniskunjungan) {
                case 1:
                    $asalrujukan = 20;
                    break;
                case 2:
                    $asalrujukan = 5;
                    break;
                case 3:
                    $asalrujukan = 5;
                    break;
                case 4:
                    $asalrujukan = 2;
                    break;
            }
            if (!empty($pasien)) {
                $noAntrianRegistrasi = $newptp->jenis . '-' . str_pad($newptp->noantrian, 3, "0", STR_PAD_LEFT);
                $pasiendaftar = array(
                    'norec' => '',
                    'nocmfk' => $pasien->id,
                    'tglregistrasi' => $newptp->tanggalreservasi,
                    'objectruanganlastfk' => $ruangan->id,
                    'asalrujukanfk' => $asalrujukan,
                    'keteranganasalrujukan' => null,
                    'objectkelompokpasienlastfk' => $idKelBPJS,
                    'jenispelayananfk' => $idtipelayananBPJS,
                    'objectpegawaifk' => $dokter->id,
                    'objectpegawairawatbersamafk' => null,
                    'objectkelasfk' => $idNonKelas,
                    'israwatinap' => false,
                    'catatan' => null,
                    'statuspasien' => !empty($pasien) ? "LAMA" : "BARU",
                    'objectrekananfk' => $idrekananBPJS,
                    'nocm' => !empty($pasien) ? $pasien->nocm : null,
                    'namapasien' => !empty($pasien) ? $pasien->namapasien : null,
                    'antrianpasienregistrasifk' => $norecAntrianRegis,
                    'nojkn' => $noAntrianRegistrasi, //null,
                    'isjkn' => true,
                    'noreservasi' => $newptp->noreservasi,
                );
                $antrianpasiendiperiksa = array(
                    'norec' => '',
                    'objectkamarfk' => null,
                    'nobed' => null,
                    'israwatgabung' => null,
                );

                $objetoRequest = new Request();
                $objetoRequest['pasiendaftar'] = $pasiendaftar;
                $objetoRequest['antrianpasiendiperiksa'] = $antrianpasiendiperiksa;
                $objetoRequest['user'] = $this->getNamaPegawai();
                $cek = app('App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl')->saveRegistrasi($objetoRequest);
                $cek = json_decode($cek->content(), true);

                $nomorAntrian = 0;
                $nomorAntrianANGKA = $nomorAntrian;

                $jmlJKN = collect(DB::select("select count(norec) as jml
                from pasiendaftar_t
                where objectruanganlastfk = '$ruangan->id'
                --and statusenabled = true
                and tglregistrasi between '$tglAwal' and '$tglAkhir'
                "))->first();

                if (isset($cek["metaData"])) {
                    if ($cek["metaData"]["code"] == 200) {
                        $nomorAntrianANGKA = $cek["response"]["dataAPD"]["noantrian"];
                        $newptp->noantrian = $nomorAntrianANGKA;
                        $newptp->save();
                        $huruf = "";

                        if ($ruangan->noruangan != null) {
                            $huruf = $ruangan->noruangan;
                        }
                        $nomorAntrian = $huruf . '-' . str_pad($cek["response"]["dataAPD"]["noantrian"], 3, "0", STR_PAD_LEFT);

                        $transMessage = "Ok";
                        $transStatus = true;
                    } else {

                        DB::rollBack();
                        $result = array(
                            "metadata" => array(
                                "message" => $cek['metaData']['message'],
                                "code" => 201,
                            )
                        );
                        return response()->json($result, $result['metadata']['code']);
                    }
                } else {
                    DB::rollBack();
                    $result = array(
                        "metadata" => array(
                            "message" => $cek['response'],
                            "code" => 201,
                        )
                    );
                    return response()->json($result, $result['metadata']['code']);
                }
            }

            $transStatus = true;
            $transMessage = "Ok";
        } catch (Exception $e) {
            $transMessage = "Gagal Reservasi";
            $transStatus = false;
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "response" => array(
                    "nomorantrean" => $nomorAntrian, //$noAntrianRegistrasi,
                    "angkaantrean" => $nomorAntrianANGKA, //$newptp->noantrian,
                    "kodebooking" => $newptp->noreservasi,
                    "norm" => $pasien->nocm,
                    "namapoli" => $ruangan->namaruangan,
                    "namadokter" => $dokter->namalengkap,
                    "estimasidilayani" => strtotime(date($newptp->tanggalreservasi)) * 1000,
                    "sisakuotajkn" => $antrian['kuota'] - (!empty($jmlJKN) ? $jmlJKN->jml : 0),
                    "kuotajkn" => $antrian['kuota'],
                    "sisakuotanonjkn" => $antrian['kuota'] - (!empty($jmlJKN) ? $jmlJKN->jml : 0),
                    "kuotanonjkn" => $antrian['kuota'],
                    "keterangan" => "Peserta harap 60 menit lebih awal guna pencatatan administrasi."
                ),
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 200,
                ),
            );
        } else {
            DB::rollBack();
            $result = array(
                "metadata" => array(
                    "message" => $transMessage,
                    "code" => 201,
                ),
            );
        }
        return response()->json($result, $result['metadata']['code']);
    }

    public function simpanPerjanjian(Request $request)
    {


        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $method = $request['method'];
            $norec = '';
            if ($method == 'save') {
                $kdJenisSurat =  $this->settingFix('SuratKeteranganKontrol');


                $jenisSurat = DB::table('jenissurat_m')
                    ->select('*')
                    ->where('id', $kdJenisSurat)
                    ->first();
                $kodeSurat = '';
                if (!empty($jenisSurat)) {
                    $kodeSurat = $jenisSurat->kodeexternal;
                }
                if ($request['norec'] == '') {
                    $RekamMedis = new PasienPerjanjian();
                    $RekamMedis->norec = $RekamMedis->generateNewId();
                    $RekamMedis->kdprofile = $kdProfile;
                    $RekamMedis->statusenabled = true;
                    $noJanji = $this->SEQUENCE(new PasienPerjanjian, 'noperjanjian', 12, 'P' . $this->getDateTime()->format('ymd'), $kdProfile);

                    // new surat kontrol
                    $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4);
                    $SKS = new SuratKeterangan();
                    $SKS->norec = $SKS->generateNewId();
                    $SKS->kdprofile = $kdProfile;
                    $SKS->nosurat = $genSurat['nosurat'];
                    $SKS->nosint = $genSurat['noint'];
                    $SKS->statusenabled = true;
                    $SKS->jenissuratfk = $kdJenisSurat;
                    $SKS->tglsurat = date('Y-m-d H:i:s');
                } else {
                    $RekamMedis = PasienPerjanjian::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
                    $noJanji = $RekamMedis->noperjanjian;

                    // edit surat kontrol
                    $SKS =  SuratKeterangan::where('norec', $RekamMedis->objectsuratfk)->where('kdprofile', $kdProfile)->first();
                }
                //surat
                $SKS->pasiendaftarfk = $request['norec_pd'];
                $SKS->dokterfk = $request['objectdokterfk'];
                $SKS->diagnosa = $request['diagnosa'];
                $SKS->indikasi = $request['indikasi'];
                $SKS->tglkontrol = $request['tglperjanjian'];
                $SKS->pegawaifk = $this->getPegawaiId();
                $SKS->save();

                $idPasien = Pasien::where(
                    'nocm',
                    $request['nocm']
                )->where('kdprofile', $kdProfile)->first();
                $RekamMedis->objectpasienfk = $idPasien->id;
                $RekamMedis->objectdokterfk = $request['objectdokterfk'];
                $RekamMedis->jumlahkujungan = $request['jumlahkujungan'];
                $RekamMedis->keterangan = $request['keterangan'];
                $RekamMedis->tglperjanjian = $request['tglperjanjian'];
                $RekamMedis->tglinput = date('Y-m-d H:i:s');
                $RekamMedis->objectruanganfk = $request['objectruanganfk'];
                $RekamMedis->noperjanjian = $noJanji;
                $RekamMedis->objectsuratfk = $SKS->norec;
                $RekamMedis->noregistrasifk = $request['norec_pd'];
                $RekamMedis->diagnosa = $request['diagnosa'];
                $RekamMedis->indikasikembali = $request['indikasi'];
                $RekamMedis->save();
                $norec = $RekamMedis->norec;

                $cekdatana = AntrianPasienRegistrasi::where('perjanjianfk', $norec)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();

                $eksek = false;
                // $antrian = $this->GetJamKosong($request['kdsubspesialis'], $request['tglperjanjian'], $kdProfile, $eksek);

                if (empty($cekdatana)) {
                    // return $this->ambilAntrean($request);
                    // $APR = new AntrianPasienRegistrasi();
                    // $APR->norec = $APR->generateNewId();
                    // $APR->kdprofile = $kdProfile;
                    // $APR->statusenabled = true;
                    // $APR->noreservasi = substr(Uuid::uuid4()->toString(), 0, 7);
                    // $APR->nocmfk = $idPasien->id;
                    // $APR->objectpegawaifk = $request['objectdokterfk'];
                    // if ($idPasien->objectpendidikanfk != null) {
                    //     $APR->objectpendidikanfk = $idPasien->objectpendidikanfk;
                    // } else {
                    //     $APR->objectpendidikanfk = 0;
                    // }

                    // $APR->objectjeniskelaminfk = $idPasien->objectjeniskelaminfk;
                    // $APR->objectruanganfk = $request['objectruanganfk'];
                    // $APR->tanggalreservasi = $request['tglperjanjian'];
                    // $APR->tipepasien = 'LAMA';
                    // $APR->type = '';
                    // $APR->keterangan == $request['keterangan'];
                    // $APR->perjanjianfk = $norec;
                    // $APR->save();
                }
            }
            if ($method == 'delete') {
                PasienPerjanjian::where('norec', $request['norec'])->update(
                    ['statusenabled' => false]
                );
            }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "perjanjian" => isset($RekamMedis) ? $RekamMedis : null
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusOrderPerjanjian(Request $request)
    {
        DB::beginTransaction();
        try {

            PasienPerjanjian::where('norec', $request['norec'])->update(['statusenabled' => false]);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPasienPerjanjian(Request $request)
    {
        $data = DB::table('pasienperjanjian_t as rm')
            ->select(
                'rm.norec',
                'rm.objectdokterfk',
                'pg.namalengkap',
                'rm.jumlahkujungan',
                'rm.keterangan',
                'rm.objectpasienfk',
                'rm.tglinput',
                'rm.tglperjanjian',
                'st.tglkontrol AS tglperjanjian',
                'rm.noperjanjian',
                'rm.objectruanganfk',
                'ru.namaruangan',
                'ru.kdinternal',
                'pg.kddokterbpjs',
                'ps.nocm',
                'pd.noregistrasi',
                'pd.noreservasi',
                'ps.namapasien',
                'st.diagnosa',
                'st.indikasi',
                'rm.objectsuratfk',
                'apr.noantrian',
                'apr.jenis',
            )
            ->leftJoin('pasien_m as ps', 'rm.objectpasienfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.objectdokterfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'rm.objectruanganfk')
            ->leftJoin('suratketerangan_t as st', 'st.norec', '=', 'rm.objectsuratfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'st.pasiendaftarfk')
            ->leftJoin('antrianpasienregistrasi_t as apr', function ($join) {
                $join->on('apr.nocmfk', '=', 'rm.objectpasienfk')
                    ->whereRaw("DATE(rm.tglperjanjian) = DATE(apr.tanggalreservasi)");
            })

            ->where('rm.kdprofile', $this->kdProfile)
            ->where('rm.statusenabled', true);


        if (isset($request['noregistrasifk']) && $request['noregistrasifk'] != '') {
            $data = $data->where('rm.noregistrasifk', $request['noregistrasifk']);
        }
        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('ps.nocm', $request['nocm']);
        }
        if (isset($request['nik']) && $request['nik'] != '') {
            $data = $data->where('ps.noidentitas', $request['nik']);
        }
        if (isset($request['belum']) && $request['belum'] != '') {
            $data = $data->where('rm.tglinput', '>=', date('Y-m-d'));
        }
        if (isset($request['tanggal']) && $request['tanggal'] != '') {
            $data = $data->where('rm.tglperjanjian', '>=', date('Y-m-d'));
        }

        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }

        $data->orderByDesc('rm.tglinput');
        $data = $data->get();
        $result = array(
            'data' => $data,
        );
        return $this->respond($result);
    }
    public function getCollectionNameByForm($url_form)
    {
        $data = DB::table('emr_t')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('url_form', $url_form)
            ->first();
        return $this->respond(!empty($data) ? $data->collection : null);
    }
    public function saveOrderKonsul(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec_so'] == "") {
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdProfile;
                $dataSO->statusenabled = true;
                $namaLog = 'Tambah Konsultasi';
                $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 11, 'K' . date('ym'), $kdProfile);
            } else {
                $namaLog = 'Ubah Konsultasi';
                $dataSO = StrukOrder::where('norec', $request['norec_so'])->where('kdprofile', $kdProfile)->first();
                $noOrder = $dataSO->noorder;
            }
            $dataSO->nocmfk = $request['nocmfk'];
            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $request['norec_pd'];
            $dataSO->objectpegawaiorderfk = $request['pegawaifk'];
            $dataSO->objectpetugasfk = $this->getPegawaiId();
            $dataSO->qtyjenisproduk = 0;
            $dataSO->qtyproduk = 0;
            $dataSO->objectruanganfk = $request['objectruanganasalfk'];
            $dataSO->objectruangantujuanfk = $request['objectruangantujuanfk'];
            $dataSO->keteranganorder = $request['keterangan'];
            $dataSO->objectkelompoktransaksifk = $this->settingFix('idKelompokTransaksiKonsul');
            $dataSO->tglorder = $request['tanggalKonsul'];
            $dataSO->rawatbersama = $request['rawatbersama'];
            $dataSO->konsultasi = $request['konsultasi'];
            $dataSO->lainlain = $request['lainlain'];
            $dataSO->objectkelasfk = $request['objectkelasfk'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();
            $this->LOGGING(
                'Konsultasi',
                $dataSO->norec,
                'strukorder_t',
                $namaLog . 'ke ruang ' . $request['ruangantujuan'] . ' pada Pasien ' .   $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "data" => $dataSO
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(), //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getOrderKonsul(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $arrRuangId = [];
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        // if (isset($request['idadmin']) && $request['idadmin'] != '') {
        // $dataruangan = DB::table('maploginusertoruangan_s as mlu')
        //     ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
        //     ->select('ru.id', 'ru.namaruangan')
        //     ->where('mlu.kdprofile', $idProfile)
        //     ->where('mlu.objectloginuserfk', $request['idadmin'])
        //     ->get();
        $dataruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        if (count($dataruangan) > 0) {
            foreach ($dataruangan as $item) {
                $arrRuangId[]  = $item->id;
            }
        }
        // }
        $data = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->select(
                DB::raw("CASE WHEN so.statusorder = 1 THEN 'Terverifikasi' ELSE 'Belum Verifikasi' END AS status_order"),
                'so.norec',
                'so.objectkelasfk as kelasfk',
                'so.noorder',
                'so.tglorder',
                'so.rawatbersama',
                'so.konsultasi',
                'so.objectpetugasfk',
                'so.lainlain',
                'ru.namaruangan as ruanganasal',
                'pg.namalengkap',
                'rutuju.namaruangan as ruangantujuan',
                'pet.namalengkap as pengonsul',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.objectkelompoktransaksifk',
                'ps.nocm',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd',
                'so.keteranganlainnya',
                'pd.objectkelasfk as kelasfk_pd'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->where('ps.nocm', $request['nocm'])
            ->where('pd.noregistrasi', $request['noregistrasi']);
        // ->whereBetween(DB::raw("CAST(so.tglorder as Date)"),$dateRange);

        // if (isset($request['tglAwal']) && $request['tglAwal'] != '') {
        //     if ($request['isnotverif'] == true) {
        //         $data = $data->where('so.tglorder', '>=', $request['tglAwal'] . ' 00:00')->orWhere('apd.norec' ,null);
        //     }else{
        //         $data = $data->where('so.tglorder', '>=', $request['tglAwal'] . ' 00:00')->orWhere('apd.norec' ,'!=' ,null);
        //     }
        // }

        if (isset($request['dokterfk']) && $request['dokterfk'] != '') {
            $data = $data->where('pg.id', $request['dokterfk']);
        }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->where('so.objectruangantujuanfk', $request['ruanganfk']);
        // }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('rutuju.id', explode(',', $request['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->where(function ($query) use ($arrRuangId) {
                $query->whereIn('rutuju.id', $arrRuangId)
                    ->orWhereIn('ru.id', $arrRuangId);
            });
        }
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', $request['noregistrasi']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        // if (isset($request['tglAkhir']) && $request['tglAkhir'] != '') {
        //     if ($request['isnotverif'] == true) {
        //         $data = $data->where('so.tglorder', '<=', $request['tglAkhir'] . ' 23:59')->orWhere('apd.norec' ,null);
        //     }else{
        //         $data = $data->where('so.tglorder', '<=', $request['tglAkhir'] . ' 23:59')->orWhere('apd.norec' ,'!=' ,null);
        //     }
        // }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->where('so.objectruangantujuanfk', $request['ruanganfk']);
        // }
        // if (isset($request['namapasien']) && $request['namapasien'] != "" && $request['namapasien'] != "undefined") {
        //     $data = $data->where('ps.namapasien', 'ilike', '%' . $request['namapasien'] . '%');
        // }
        // if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
        //     $data = $data->where('pg.id', $request['idpegawai']);
        // }
        // if (isset($request['nocm']) && $request['nocm'] != '') {
        //     $data = $data->where('ps.nocm', $request['nocm']);
        // }
        // if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
        //     $data = $data->where('pd.noregistrasi', $request['noregistrasi']);
        // }
        // if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
        //     $data = $data->wherenull('apd.norec');
        // }
        // if (isset($request['idadmin']) && $request['idadmin'] != '') {
        //     $data = $data->whereIn('rutuju.id', $arrRuangId);
        // }
        $total = $data->count();
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        $data = $data
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->orderBy('so.tglorder', 'desc')
            ->get();
        $result = array(
            'data' => $data,
            'total' => $total
        );
        return $this->respond($result);
    }

    public function getHasilRadiologi(Request $request)
    {
        $norec = $request['norec_pd'];
        $hasil = DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('hasilradiologi_t as hr', 'pp.norec', 'hr.pelayananpasienfk')
            ->select(DB::raw(
                "
                        '\n\nExpertise Radiologi Tgl : ' || TO_CHAR(hr.tanggalreport, 'DD/MM/YYYY HH24:MI:SS') ||'\n '|| hr.keterangan || '\n' AS hasilExpertise"
                // 'Expertise Radiologi Tgl : ' || TO_CHAR(hr.tanggalreport, 'DD/MM/YYYY HH24:MI:SS') || E'\n' || hr.keterangan AS hasilExpertise"
            ))
            ->where('pp.noregistrasi', $request['noregistrasi'])
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->get();

        return $this->respond($hasil);
    }

    public function getHasilRadiologiEmr(Request $request)
    {
        $norec = $request['norec_pd'];
        $hasil = DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('hasilradiologi_t as hr', 'pp.norec', 'hr.pelayananpasienfk')
            ->select(
                'hr.tanggalreport',
                DB::raw(
                    "
                        '\n\nExpertise Radiologi Tgl : ' || TO_CHAR(hr.tanggalreport, 'DD/MM/YYYY HH24:MI:SS') ||'\n '|| hr.keterangan || '\n' AS hasilExpertise"
                    // 'Expertise Radiologi Tgl : ' || TO_CHAR(hr.tanggalreport, 'DD/MM/YYYY HH24:MI:SS') || E'\n' || hr.keterangan AS hasilExpertise"
                )
            )
            ->where('pp.noregistrasi', $request['noregistrasi'])
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'));
        if (isset($request['tanggalreport']) && $request['tanggalreport'] != '') {
            $hasil = $hasil->whereIn('hr.tanggalreport', explode(',', $request['tanggalreport']));
        }
        $hasil = $hasil->get();

        return $this->respond($hasil);
    }

    public function hapusOrderKonsul(Request $request)
    {
        DB::beginTransaction();
        try {

            // return $request['norec'];
            StrukOrder::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            $this->LOGGING(
                'Konsultasi',
                $request['norec'],
                'strukorder_t',
                'Hapus konsultasi no ' . $request['noorder'] . ' ke ' . $request['ruangantujuan'] . ' pada Pasien ' .   $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function jawabOrderKonsul(Request $request)
    {
        DB::beginTransaction();
        try {

            StrukOrder::where('norec', $request['norec_so'])->where('kdprofile', $this->kdProfile)->update([
                'keteranganlainnya' =>  $request['jawaban']
            ]);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Jawab Konsul Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null, //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function uploadImageBarcode(Request $request)
    {
        return $request->file('image');
    }

    public function getResumeMedis(Request $request)
    {
        $data = DB::table('resumemedis_t as rm')
            ->Join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'rm.noregistrasifk')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.pegawaifk')
            ->leftJoin('diagnosa_m as dg1', 'dg1.id', '=', 'rm.kddiagnosismasuk')
            ->leftJoin('diagnosa_m as dg2', 'dg2.id', '=', 'rm.kddiagnosisawal')
            ->leftJoin('diagnosa_m as dg3', 'dg3.id', '=', 'rm.kddiagnosistambahan')
            ->select(
                'rm.norec',
                'rm.tglresume',
                'ru.namaruangan',
                'pg.namalengkap as namadokter',
                'rm.namadiagnosatambahan',
                'rm.ringkasanriwayatpenyakit',
                'rm.pemeriksaanfisik',
                'rm.pemeriksaanpenunjang',
                'rm.hasilkonsultasi',
                'rm.terapi',
                'rm.diagnosisawal',
                'rm.diagnosissekunder',
                DB::raw("dg1.kddiagnosa || ' - ' || dg1.namadiagnosa as namadiagnosa1"),
                'rm.tindakanprosedur',
                // 'rm.diagnosismasuk', 'rm.diagnosistambahan', 'rm.alasandirawat',
                'rm.kddiagnosisawal',
                'rm.diagnosismasuk',
                'rm.kddiagnosismasuk',
                'rm.diagnosistambahan',
                'rm.kddiagnosistambahan',
                'rm.alasandirawat',
                DB::raw("dg2.kddiagnosa || ' - ' || dg2.namadiagnosa as namadiagnosa2"),
                'dg1.kddiagnosa as kddiagnosa1',
                // 'dg2.namadiagnosa as namadiagnosa2',
                'dg1.namadiagnosa as namadiagnosa1',
                'dg2.kddiagnosa as kddiagnosa2',
                // 'dg2.namadiagnosa as namadiagnosa2',
                'rm.tglkontrolpoli',
                'rm.rumahsakittujuan',
                'rm.alergi',
                'rm.instruksianjuran',
                'rm.hasillab',
                'rm.kondisiwaktukeluar',
                'rm.pengobatandilanjutkan',
                'rm.koderesume',
                'rm.pegawaifk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'rm.noregistrasifk',
                'rm.pemeriksaanfisikreal',
                'rm.terapipulang',
                'ps.namapasien'
            )
            ->where('rm.kdprofile', $this->kdProfile)
            ->where('rm.statusenabled', true)
            // ->where('ps.nocm', $nocm)
            ->where('rm.keteranganlainnya', 'RawatInap');

        if (isset($request['nocm']) && $request['nocm'] != '') {
            $data = $data->where('ps.nocm', $request['nocm']);
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
        );

        return $this->respond($result);
    }

    public function saveResumeMedis(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == '') {
                $RekamMedis = new ResumeMedis();
                $RekamMedis->norec = $RekamMedis->generateNewId();
                $RekamMedis->kdprofile = $kdProfile;
                $RekamMedis->statusenabled = true;
                $kdResume = $this->SEQUENCE(new ResumeMedis, 'koderesume', 12, 'RI' . date('ym'), $kdProfile);
            } else {
                $RekamMedis = ResumeMedis::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
                $kdResume = $RekamMedis->koderesume;
                ResumeMedisDetail::where('resumefk', $request['norec'])->where('kdprofile', $kdProfile)->delete();
            }
            $RekamMedis->tglresume = date('Y-m-d H:i:s');
            $RekamMedis->diagnosismasuk = $request['diagnosismasuk'];

            if (isset($request['kddiagnosismasuk']) && $request['kddiagnosismasuk'] != "") {
                $RekamMedis->kddiagnosismasuk = $request['kddiagnosismasuk'];
            }
            $RekamMedis->diagnosisawal = $request['diagnosisawal'];
            if (isset($request['kddiagnosisawal']) && $request['kddiagnosisawal'] != "") {
                $RekamMedis->kddiagnosisawal = $request['kddiagnosisawal'];
            }

            $RekamMedis->namadiagnosatambahan = $request['namadiagnosatambahan'];
            $RekamMedis->tindakanprosedur = $request['tindakanprosedur'];
            $RekamMedis->alasandirawat = $request['alasandirawat'];
            $RekamMedis->ringkasanriwayatpenyakit = $request['ringkasanriwayatpenyakit'];
            $RekamMedis->pemeriksaanfisik = $request['pemeriksaanfisik'];
            $RekamMedis->pemeriksaanpenunjang = $request['pemeriksaanpenunjang'];
            $RekamMedis->terapi = $request['terapi'];

            // $RekamMedis->terapipulang = $request['terapipulang'];
            $RekamMedis->hasilkonsultasi = $request['hasilkonsultasi'];
            $RekamMedis->kondisiwaktukeluar = $request['kondisiwaktukeluar'];
            $RekamMedis->instruksianjuran = $request['instruksianjuran'];
            $RekamMedis->pengobatandilanjutkan = $request['pengobatandilanjutkan'];
            // $RekamMedis->pemeriksaanfisikreal = $request['pemeriksaanfisikreal'];
            $RekamMedis->tglkontrolpoli = $request['tglkontrolpoli'];
            $RekamMedis->rumahsakittujuan = $request['rumahsakittujuan'];
            $RekamMedis->pegawaifk = $this->getPegawaiId();
            $RekamMedis->noregistrasifk = $request['noregistrasifk'];
            $RekamMedis->keteranganlainnya = 'RawatInap';
            $RekamMedis->koderesume = $kdResume;
            if (isset($request['ascvdfk'])) {
                $RekamMedis->ascvdfk = $request['ascvdfk'];
            }
            // return $RekamMedis;
            $RekamMedis->save();
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function hapusResumeMedis(Request $request)
    {
        DB::beginTransaction();
        try {

            $dataRM = ResumeMedis::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->update(['statusenabled' => false]);

            $this->LOGGING(
                'Resume Medis',
                $request['norec'],
                'resumemedis_t',
                'Hapus Resume Medis ' . ' pada Pasien ' .   $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(), //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getTotalBilling(Request $request) {}
    public function listDiagnosaKeperawatan(Request $r)
    {

        $res['diagnosaKeperawatan'] = DB::table('diagnosakeperawatan_m')
            ->select('id', 'diagnosakep', 'kodediagnosakep')
            ->where('statusenabled', true);


        if (isset($r['query']) && $r['query'] != '' && $r['query'] != 'undefined') {
            $res['diagnosaKeperawatan'] = $res['diagnosaKeperawatan']->where('diagnosakep', 'ilike', '%' . $r['query'] . '%');
        }
        $res['diagnosaKeperawatan'] = $res['diagnosaKeperawatan']->orderby('diagnosakep');
        $res['diagnosaKeperawatan'] = $res['diagnosaKeperawatan']->get();
        return $this->respond($res);
    }

    public function listTujuanDiagnosaKeperawatan(Request $r)
    {

        $data = DB::table('mapdiagnosatotojuankeperawatan_t as mdt')
            ->leftJoin('diagnosakeperawatan_m as dgp', 'dgp.qdiagnosakep', '=', 'mdt.objectdiagnosakep')
            ->leftJoin('tujuanperawatan_m as tjp', 'tjp.qtujuankep', '=', 'mdt.objecttujuankep')
            ->select('tjp.qtujuankep as idtujuan', 'dgp.diagnosakep', 'tjp.tujuankep')
            ->where('dgp.qdiagnosakep', $r['qdiagnosakep'])
            ->where('dgp.kodeexternal', 'Edokep')
            ->where('tjp.kodeexternal', 'Edokep')
            ->where('dgp.statusenabled', true)
            ->where('mdt.statusenabled', true)
            ->where('tjp.statusenabled', true);

        $data = $data->get();

        $result = array(
            'data' => $data,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listIntervensiDiagnosaKeperawatan(Request $r)
    {

        $data= DB::table('mapdiagnosatointervensikeperawatan_t as mdi')
            ->leftJoin('diagnosakeperawatan_m as dgp', 'dgp.qdiagnosakep', '=', 'mdi.objectdiagnosakep')
            ->leftJoin('intervensiperawatan_m as itp', 'itp.kodeintervensi', '=', 'mdi.objectintervensikep')
            ->select('itp.kodeintervensi', 'dgp.diagnosakep', 'itp.intervensi')
            ->where('dgp.qdiagnosakep', $r['qdiagnosakep'])
            ->where('dgp.kodeexternal', 'Edokep')
            ->where('itp.kodeexternal', 'Edokep')
            ->where('dgp.statusenabled', true)
            ->where('mdi.statusenabled', true)
            ->where('itp.statusenabled', true);

        $data = $data->get();

        $result = array(
            'data' => $data,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listKriteriaDiagnosaKeperawatan(Request $r)
    {

        $data= DB::table('kriteriaperawatan_m as kp')
            ->leftJoin('tujuanperawatan_m as tjp', 'tjp.qtujuankep', '=', 'kp.objecttujuanfk')
            ->select('tjp.id as idtujuan', 'kp.kodekriteria', 'tjp.tujuankep', 'kp.kriteria')
            ->where('kp.objecttujuanfk', $r['objecttujuanfk'])
            ->where('kp.kodeexternal', 'Edokep')
            ->where('tjp.kodeexternal', 'Edokep')
            ->where('kp.statusenabled', true)
            ->where('tjp.statusenabled', true);
        $data = $data->get();

        $result = array(
            'data' => $data,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listAktifitasDiagnosaKeperawatan(Request $r)
    {

        $data= DB::table('mapintervensitoaktifitaskeperawatan_t as mia')
            ->leftJoin('intervensiperawatan_m as itp', 'itp.kodeintervensi', '=', 'mia.objectintervensikep')
            ->leftJoin('aktifitasperawatan_m as akp', 'akp.kodeaktifitas', '=', 'mia.objectaktifitaskep')
            ->select('akp.kodeaktifitas', 'itp.intervensi', 'akp.aktifitas')
            ->where('itp.kodeintervensi', $r['kodeintervensi'])
            ->where('itp.kodeexternal', 'Edokep')
            ->where('akp.kodeexternal', 'Edokep')
            ->where('itp.statusenabled', true)
            ->where('mia.statusenabled', true)
            ->where('akp.statusenabled', true);
            
        $data = $data->get();

        $result = array(
            'data' => $data,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listDiagnosaKeperawatanEdokep(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $pengkajian = DB::connection('mongodb')
            ->table('pengkajianAwalRawatJalanKeperawatan')
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('statusenabled', true)
            ->get();

        $respirasiTidakNormal = false;
        $sirkulasiTidakNormal = false;
        $nutrisiTidakNormal = false;
        $eliminasiTidakNormal = false;
        $neurosensoriTidakNormal = false;
        $aktivitasTidurTidakNormal = false;
        $nyerikeamananTidakNormal = false;
        $kebutuhanBelajarTidakNormal = false;
        $kebersihanDiriTidakNormal = false;
        $proteksiKeamananTidakNormal = false;
        foreach ($pengkajian as $item) {
            if (!empty($item['respirasi']) && $item['respirasi'] == 'respirasiTidakNormal') {
                $respirasiTidakNormal = true;
            }
            if (!empty($item['sirkulasi']) && $item['sirkulasi'] == 'sirkulasiTidakNormal') {
                $sirkulasiTidakNormal = true;
            }
            if (!empty($item['nutrisi']) && $item['nutrisi'] == 'nutrisiTidakNormal') {
                $nutrisiTidakNormal = true;
            }
            if (!empty($item['eliminasi']) && $item['eliminasi'] == 'eliminasiTidakNormal') {
                $eliminasiTidakNormal = true;
            }
            if (!empty($item['neurosensori']) && $item['neurosensori'] == 'neurosensoriTidakNormal') {
                $neurosensoriTidakNormal = true;
            }
            if (!empty($item['aktivitasTidur']) && $item['aktivitasTidur'] == 'aktivitasTidurTidakNormal') {
                $aktivitasTidurTidakNormal = true;
            }
            if (!empty($item['nyerikeamanan']) && $item['nyerikeamanan'] == 'nyerikeamananTidakNormal') {
                $nyerikeamananTidakNormal = true;
            }
            if (!empty($item['kebutuhanBelajar']) && $item['kebutuhanBelajar'] == 'kebutuhanBelajarTidakNormal') {
                $kebutuhanBelajarTidakNormal = true;
            }
            if (!empty($item['kebersihanDiri']) && $item['kebersihanDiri'] == 'kebersihanDiriTidakNormal') {
                $kebersihanDiriTidakNormal = true;
            }
            if (!empty($item['proteksiKeamanan']) && $item['proteksiKeamanan'] == 'proteksiKeamananTidakNormal') {
                $proteksiKeamananTidakNormal = true;
            }
        }

        $query = DB::table('detailjenispdiagnosakeperawatan_m')
            ->select('id', 'detailjenisdiagnosakeperawatan')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->whereNotIn('id', [11, 12, 13, 14, 15]);
        if (!$respirasiTidakNormal) {
            $query->where('id', '!=', 1);
        }
        if (!$sirkulasiTidakNormal) {
            $query->where('id', '!=', 2);
        }
        if (!$nutrisiTidakNormal) {
            $query->where('id', '!=', 3);
        }
        if (!$eliminasiTidakNormal) {
            $query->where('id', '!=', 4);
        }
        if (!$neurosensoriTidakNormal) {
            $query->where('id', '!=', 5);
        }
        if (!$aktivitasTidurTidakNormal) {
            $query->where('id', '!=', 6);
        }
        if (!$nyerikeamananTidakNormal) {
            $query->where('id', '!=', 7);
        }
        if (!$kebutuhanBelajarTidakNormal) {
            $query->where('id', '!=', 8);
        }
        if (!$kebersihanDiriTidakNormal) {
            $query->where('id', '!=', 9);
        }
        if (!$proteksiKeamananTidakNormal) {
            $query->where('id', '!=', 10);
        }
        $detail = $query->get();

        $data = DB::table('diagnosakeperawatan_m as mpr')
            ->leftJoin('detailjenispdiagnosakeperawatan_m as djkp', 'djkp.id', '=', 'mpr.detailjenisdiagnosakeperawatanfk')
            ->select('mpr.qdiagnosakep', 'mpr.id', 'mpr.diagnosakep', 'mpr.kodediagnosakep', 'mpr.detailjenisdiagnosakeperawatanfk', 'djkp.detailjenisdiagnosakeperawatan')
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.kodeexternal', 'Edokep')
            ->where('mpr.statusenabled', true)
            ->orderBy('mpr.diagnosakep', 'ASC')
            ->get();

        foreach ($detail as $key => $value) {
            $value->details = [];
        }
        $i = 0;
        $detail = $detail->toArray();
        foreach ($detail as $value) {
            foreach ($data as $value2) {
                if ($detail[$i]->id == $value2->detailjenisdiagnosakeperawatanfk) {
                    $detail[$i]->details[] = $value2;
                }
            }
            $i++;
        }

        for ($i = count($detail) - 1; $i >= 0; $i--) {
            if (count($detail[$i]->details) == 0) {
                array_splice($detail, $i, 1);
            }
        }
        $result = array(
            'list_tindakan' => $detail,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listTujuanKeperawatan(Request $r)
    {
        $res['tujuanPerawat'] = DB::table('tujuanperawatan_m')->where('statusenabled', true);
        if (isset($r['objectdiagnosakepfk']) && $r['objectdiagnosakepfk'] != '') {
            $res['tujuanPerawat'] = $res['tujuanPerawat']->where('objectdiagnosakepfk', $r['objectdiagnosakepfk']);
        }
        if (isset($r['query']) && $r['query'] != '') {
            $res['tujuanPerawat'] = $res['tujuanPerawat']->where('tujuankep', 'ilike', '%' . $r['query'] . '%');
        }
        $res['tujuanPerawat'] = $res['tujuanPerawat']
            ->orderby('id')
            ->get();
        return $this->respond($res);
    }
    public function listIntervensiKeperawatan(Request $r)
    {
        $res['intervensi'] = DB::table('intervensi_m as in')
            ->select('name', 'kodeexternal', 'id')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        // ->where('objecttujuankeperawatan',$r['keperawatanfk'])
        // ->when($r['keperawatanfk'], function ($query) use ($r) {
        //     $query->where('objecttujuankeperawatan', $r['keperawatanfk'])->orWhere('objecttujuankeperawatan', ['keperawatanfk']);
        // })

        // $res['intervensi'] = DB::table('intervensi_m');
        if (isset($r['keperawatanfk']) && $r['keperawatanfk'] != '') {
            $res['intervensi'] = $res['intervensi']->where('objecttujuankeperawatan', $r['keperawatanfk']);
        }
        $res['intervensi'] = $res['intervensi']->get();
        // $res['intervensi'] = $res['intervensi']
        //     ->orderby('id')
        //     ->get();
        return $this->respond($res);
    }
    public function implementasiKeperawatan(Request $r)
    {
        $res['implementasi'] = DB::table('implementasi_m');
        if (isset($r['objectintervensifk']) && $r['objectintervensifk'] != '') {
            $res['implementasi'] = $res['implementasi']->where('objectintervensifk', $r['objectintervensifk']);
        }
        $res['implementasi'] = $res['implementasi']
            ->orderby('id')
            ->get();
        return $this->respond($res);
    }
    public function getResep(Request $r)
    {
        $data = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('orderpelayanan_t as op', 'op.noorderfk', 'so.norec')
            ->join('produk_m as pr', 'op.objectprodukfk', 'pr.id')
            ->select('pr.namaproduk')
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $this->kdProfile)
            // ->where('so.keteranganorder', '=', 'Order Farmasi')
            ->where('so.noregistrasifk', $r->input('norec_pd'));

        $data = $data->get();

        return $this->respond($data);
    }
    public function getPetugasPe(Request $r)
    {
        $data = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->select('pg.namalengkap')
            ->where('so.noregistrasifk', $r->input('norec_pd'))
            ->groupBy('pg.id');

        $data = $data->get();

        return $this->respond($data);
    }
    public function getHasilLab(Request $r)
    {
        $res['hasil_VansLAB'] = collect(DB::select("select
        res.no_order AS visit_trans_id, res.kode_pemeriksaan AS tarif_id,
        res.nama_pemeriksaan AS examination_name, res.hasil AS result_value, res.unit,
        res.normal AS normal_value, res.metode, res.nama_alat AS treatment_name,
        res.no_urut AS urut, res.no_rm AS rm_number,  res.tgl_hasil AS visit_date, res.kode_sir as his_test_id,
        res.nama_pemeriksaan AS tarif_name, res.flag, res.user_validasi as analis
        from lab_hasil as res
        join pasiendaftar_t as pd on pd.noregistrasi=res.no_registrasi
        where pd.norec = '$norec'
        "))->toArray();


        $res['hasil_LAB_MANUAL']  = collect(DB::select(
            "select case when so.noorder is null then  pd.noregistrasi  else so.noorder end as visit_trans_id,
            res.produkfk AS tarif_id,
            pr.namaproduk AS examination_name, res.hasil AS result_value,  res.satuan AS unit,
            res.nilainormal AS normal_value,  res.metode AS metode,   res.group AS treatment_name,
            null AS urut,  ps.nocm AS rm_number,  res.tglhasil AS visit_date,    res.produkfk as his_test_id,
            pr.namaproduk AS tarif_name,  res.flag,pg.namalengkap as analis
            from hasillaboratorium_t as res
            join produk_m as pr on pr.id=  res.produkfk
            join antrianpasiendiperiksa_t as apd on  apd.norec= res.noregistrasifk
            join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
            left join strukorder_t as so on so.noregistrasifk=pd.norec
            join pasien_m as ps on ps.id=pd.nocmfk
            left join pegawai_m as pg on cast(pg.id as text)=res.pegawaifk
            where pd.norec= '$norec'"
        ))->toArray();

        return $this->respond($res);
    }

    public function pemantauanPemeriksaanPenunjang(Request $request)
    {
        $noregistrasi = $request['noregistrasi'] ?? '';
        $result = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
            ->join('produk_m as prd', 'prd.id', 'op.objectprodukfk')
            ->select(DB::raw('DISTINCT CAST(so.tglorder as DATE) as tgl'), 'prd.namaproduk', 'op.keteranganlainnya', 'so.tglorder', 'hr.tanggalreport', 'lh.tgl_hasil')
            ->where('so.noregistrasi', $noregistrasi)
            ->where('so.statusorder', '<>', 0)
            ->where(function ($query) {
                $query->where('so.objectkelompoktransaksifk', 93)
                    ->orWhere('so.objectkelompoktransaksifk', 94);
            })
            ->leftJoin('lab_hasil as lh', function ($join) {
                $join->on('so.noorder', '=', 'lh.no_order')
                    ->where('so.objectkelompoktransaksifk', 93);
            })
            ->leftJoin('hasilradiologi_t as hr', function ($join) {
                $join->on('so.noregistrasifk', '=', 'hr.noregistrasifk')
                    ->where('so.objectkelompoktransaksifk', 94);
            })
            ->get();
        return $this->respond($result);
    }
}
