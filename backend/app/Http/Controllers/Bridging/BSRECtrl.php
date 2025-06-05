<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Transaksi\EMRBSRE;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Barryvdh\DomPDF\PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;

class BSRECtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    /**
     * get data autentikasi untuk bridging BSRE.
     */
    public function getSetting()
    {
        $res = [
            'BSRE_Auth' => '',
            'BSRE_URL' => '',
            'BSRE_Username' => '',
            'BSRE_Password' => '',
            // 'BSRE_Passphrase' => '',
        ];

        $data = DB::table('settingdatafixed_m')->where('kdprofile', $this->kdProfile)
            ->select('namafield', 'nilaifield')
            ->where('statusenabled', true)
            ->whereIn('namafield', [
                'BSRE_Auth',
                'BSRE_URL',
                'BSRE_Username',
                'BSRE_Password',
                // 'BSRE_Passphrase',
            ])
            ->get();


        foreach ($data as $v) {
            foreach (array_keys($res) as $v2) {
                if ($v->namafield == $v2) {
                    $res[$v->namafield] = $v->nilaifield;
                }
            }
        }
        return $res;
    }

    /**
     * setting header.
     */
    public function getHeader()
    {
        $set = $this->getSetting();
        $username = $set['BSRE_Username'];
        $password = $set['BSRE_Password'];

        $header = array(
            "Authorization: Basic " . base64_encode($username.':'.$password),
            "Content-Type: application/json",
        );
        return $header;
    }

    /**
     *  Set cURL untuk nge hit bsre
     */
    private function sendBridging($headers, $dataJsonSend = null, $url, $method, $tipe = null)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataJsonSend,
            CURLOPT_HTTPHEADER => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $result = "Terjadi Kesalahan #:" . $err;
        } else {
            if ($tipe != null) {
                $result = json_decode($response);
            } else {
                $result = json_decode($response);
            }
        }
        return $result;
    }

    public function getDataList(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dataBSREDokumen = DB::table("pasiendaftar_t as pd")
            ->join('emr_bsre_t as ebsre', 'ebsre.noregistrasifk', '=', 'pd.norec')
            ->join('pegawai_m as pg', 'pg.id', '=', 'ebsre.objectpegawaifk')
            ->join('emrpasien_t as emr', 'emr.norec', '=', 'ebsre.emrpasienfk')
            ->select(
                'ebsre.*',
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pg.id as pegawai_id',
                'pg.namalengkap',
                'emr.noemr',
            )
            ->where('pd.kdprofile', $kdProfile)
            ->where('ebsre.statusenabled', true)
            ->where('pd.statusenabled', true);

        if (isset($request['norec_pd']) && $request['norec_pd'] !== '' && $request['norec_pd'] !== 'undefined') {
            $dataBSREDokumen = $dataBSREDokumen->where('pd.norec', $request['norec_pd']);
        }
        $dataBSREDokumen = $dataBSREDokumen->get();

        $result = array(
            'data' => $dataBSREDokumen,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    /**
     * Save Dokumen yang akan di TTE BSRE ke local storage.
     */
    public function saveDokumenPengajuanTTE(Request $request)
    {
        $kdProfile = $this->kdProfile;
        db::beginTransaction();
        try {
            $dataRegistrasi = PasienDaftar::where('norec', $request['norec_pd'])->where('statusenabled', true)->first();
            // save data dokumen
            $modelEMRBSRE = EMRBSRE::where('noregistrasifk', $request['norec_pd'])->where('emrpasienfk', $request['emrpasienfk'])->first();
            if (!$modelEMRBSRE) {
                $modelEMRBSRE = new EMRBSRE();
                $modelEMRBSRE->norec = $modelEMRBSRE->generateNewId();
                $modelEMRBSRE->kdprofile = $kdProfile;
                $modelEMRBSRE->statusenabled = true;
            }
            $modelEMRBSRE->noregistrasifk = $dataRegistrasi->norec;
            $modelEMRBSRE->objectpegawaifk = $request['objectpegawaifk'];
            $modelEMRBSRE->emrpasienfk = $request['emrpasienfk'];
            $modelEMRBSRE->tampilan = "VISIBLE";
            $modelEMRBSRE->status = "Belum SIGN PDF";
            $modelEMRBSRE->namadokumen = $request['collection'];
            $namafile = 'TTD_BSRE/'  . $modelEMRBSRE->namadokumen . '/' . $modelEMRBSRE->norec . '/file' . '.pdf';
            $modelEMRBSRE->file = $namafile;
            $modelEMRBSRE->save();

            // collect dokumen to local storage
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $dataRegistrasi->noregistrasi;
            $objetoRequest['user'] = $this->getNamaPegawai();
            $objetoRequest['storage'] = true;
            $objetoRequest['flagnostream'] = true;
            $route =  explode('@', $request['api']);
            $fixroute = "App\Http\Controllers\'" . str_replace('-', "\'", $route[0]);
            $fix =  str_replace("'", '', $fixroute);
            $funccollect = explode('-', $route[1]);
            $func = $funccollect[0];
            $collection = isset($funccollect[1]) ? $funccollect[1] : "";
            if (isset($funccollect[1])) {
                $pdf = app($fix)->$func($collection, $objetoRequest);
            } else {
                $pdf = app($fix)->$func($objetoRequest);
            }
            $content = $pdf->download()->getOriginalContent();
            Storage::disk('local')->put('TTD_BSRE/' . $modelEMRBSRE->namadokumen . '/' . $modelEMRBSRE->norec . '/file' . '.pdf', $content);

            DB::commit();
            $result = array(
                "status" => 201,
                "message" => "success",
                "data" => $modelEMRBSRE,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 500,
                "message" => $e->getMessage() . ' ' . $e->getLine(),
            );
            return $this->respond('', $result['status'], $result['message']);
        }
        return $this->respond($result);
    }

    /**
     * SIGN PDF.
     */
    public function saveSIGNPDF(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $set = $this->getSetting();
        $header = $this->getHeader();
        $method = "POST";
        $url = $set['BSRE_URL'] . "api/v2/sign/pdf";
        // return $url;

        DB::beginTransaction();
        try {
            $dataEMRBSRE = EMRBSRE::where('norec', $request['norec'])->where('statusenabled', true)->first();
            if (!$dataEMRBSRE) {
                $result = array(
                    "status" => 404,
                    "message" => "Dokumen Tidak Ditemukan",
                );
                return $this->respond('', $result['status'], $result['message']);
            }

            $fileContents = Storage::get($dataEMRBSRE->file);
            $fileBase64 = base64_encode($fileContents);
            // return $fileBase64;

            // Request Body
            $dataJsonSend = [
                'nik' =>  $request['nik'],
                'passphrase' => $request['passphrase'],
                'signatureProperties' => [
                    [
                        "tampilan" => "INVISIBLE",
                        "location" => "Cirebon",
                        "reason" => "Dokumen ini ditandatangani secara elektronik melalui aplikasi e-Office Balai Sertifikasi Elektronik",
                        "contactInfo" => "cc.bsre@bssn.go.id"
                        // 'tag' => '$',
                        // 'imageBase64' => $base64Image,
                        // "page" => 1,
                        // "originX" => 0.0,
                        // "originY" => 0.0,
                        // "width" => 100.0,
                        // "height" => 75.0,
                        // 'location' => 'RSD Gunung Jati',
                        // 'reason' => "(Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)).",
                    ]
                ],
                'file' => [
                    $fileBase64
                ]
            ];
            $json = json_encode($dataJsonSend, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            // return $json;

            $response = $this->sendBridging($header, $json, $url, $method);
            // return $response;

            if (isset($response->file)) {
                $fileContents = $response->file[0];
                $fileBase64 = base64_decode($fileContents);

                // save response data to database
                $filename_bsre = 'TTD_BSRE/'  . $dataEMRBSRE->namadokumen . '/' . $dataEMRBSRE->norec . '/file_bsre' . '.pdf';
                Storage::disk('local')->put('TTD_BSRE/' . $dataEMRBSRE->namadokumen . '/' . $dataEMRBSRE->norec . '/file_bsre' . '.pdf', $fileBase64);

                $dataEMRBSRE->status = "Belum Verif PDF";
                $dataEMRBSRE->tgltandatangan = date('Y-m-d H:i:s');
                $dataEMRBSRE->file_bsre = $filename_bsre;
                $dataEMRBSRE->nik = $request['nik'];
                $dataEMRBSRE->location = 'RSD Gunung Jati';
                $dataEMRBSRE->reason = "(Dokumen ini ditandatangani secara elektronik menggunakan Sertifikat Elektronik yang diterbitkan oleh Balai Sertifikasi Elektronik (BSrE), Badan Siber dan Sandi Negara (BSSN)).";
                $dataEMRBSRE->save();
                DB::commit();

                $result = array(
                    "status" => 200,
                    "data" => $dataEMRBSRE,
                    'success' => "TTE Success",
                );
                return $this->respond($result);
            } else {
                $this->LOGGING(
                    'TTE',
                    $dataEMRBSRE->norec ?? null,
                    'emr_bsre_t',
                    'Gagal Melakukan Tanda Tangan Elektronik ' . $response->error,
                );

                $result = array(
                    "status" => 201,
                    "message" => $response->error,
                );
                return $this->respond('', $result['status'], $result['message']);
            }
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 201,
                "message" => $e->getMessage() . ' ' . $e->getLine(),
            );
            return $this->respond('', $result['status'], $result['message']);
        }
    }

    /**
     * VERIFY PDF.
     */
    public function saveVerifyPDF(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $set = $this->getSetting();
        $header = $this->getHeader();
        $method = "POST";
        $url = $set['BSRE_URL'] . "api/v2/verify/pdf";
        // return $url;

        try {
            $dataEMRBSRE = EMRBSRE::where('norec', $request['norec'])->where('statusenabled', true)->first();
            if (!$dataEMRBSRE) {
                $result = array(
                    "status" => 404,
                    "message" => "Dokumen Tidak Ditemukan",
                    "data" => $dataEMRBSRE,
                );
                return $this->respond('', $result['status'], $result['message']);
            }

            $fileContents = Storage::get($dataEMRBSRE->file);
            $fileBase64 = base64_encode($fileContents);

            // Request Body
            $dataJsonSend = [
                'file' => $fileBase64
            ];
            $json = json_encode($dataJsonSend, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
            $response = $this->sendBridgingCurl($header, $json, $url, $method);

            if (!$response->error) {
                $result = array(
                    "status" => 200,
                    "data" => $response,
                );
                return $this->respond($result);
            } else {
                $this->LOGGING(
                    'TTE',
                    $dataEMRBSRE->norec ?? null,
                    'emr_bsre_t',
                    'Gagal Melakukan Tanda Tangan Elektronik : ' . $response->error,
                );

                $result = array(
                    "status" => 500,
                    "message" => $response->error,
                    "as" => "error mahkluk astral",
                );
                return $this->respond('', $result['status'], $result['message']);
            }
        } catch (Exception $e) {
            $$result = array(
                "status" => 500,
                "message" => $e->getMessage() . ' ' . $e->getLine(),
            );
            return $this->respond('', $result['status'], $result['message']);
        }
    }

    public function hapusDokumen(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataEMRBSRE =  EMRBSRE::where('norec', $request['norec'])->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
            $dataEMRBSRE->statusenabled = false;
            $dataEMRBSRE->save();

            $result = array(
                "status" => 200,
                "data" => '',
            );
            DB::commit();
            return $this->respond($result);
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 500,
                "message" => $e->getMessage() . ' ' . $e->getLine(),
            );
            return $this->respond('', $result['status'], $result['message']);
        }
    }

    public function BSRECPPTRanap(Request $request)
    {
        $norec = $request['emr'];
        $kdProfile = 1;
        $data = DB::select(DB::raw("
            SELECT
                epd.emrdfk,
                ep.noemr,
                ed.TYPE,
                pa.namapasien,
                pa.tgllahir,
                pa.nohp,
                pa.nocm,
                ep.jeniskelamin,
                ep.umur,
                al.alamatlengkap,
                ep.noregistrasifk as noregistrasi ,
                epd.value,ep.namaruangan,pg.namalengkap as namadokter
                FROM
                emrpasien_t AS ep
                INNER JOIN emrpasiend_t AS epd ON ep.noemr = epd.emrpasienfk
                INNER JOIN emrd_t AS ed ON epd.emrdfk = ed.ID
                INNER JOIN antrianpasiendiperiksa_t AS pd ON pd.norec = ep.norec_apd
                left JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                left JOIN pasien_m as pa on ep.nocm =  pa.nocm
                left JOIN alamat_m as al on pa.id = al.nocmfk
            "
        ));
        foreach ($data as $z){
            if ($z->type == "datetime"){
                $z->value= date('Y-m-d H:i:s',strtotime($z->value));
            }
        }
        $pageWidth = 500;
        //   $headers = $this->headers();
        $res['profile'] = Profile::where('id',$request['kdprofile'])->first();
        $res['d'] = $data;
        ini_set("memory_limit","256M");
        ini_set('max_execution_time', '300');
        ini_set("pcre.backtrack_limit", "5000000");
        $rss = array(
          'res' => $res,
          'pageWidth' => $pageWidth
        );
        $pageWidth = 950;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('report.cppt-ranap-dom', $rss);
        return $pdf->stream();
        return $pdf->download('invoice.pdf');
    }
}
