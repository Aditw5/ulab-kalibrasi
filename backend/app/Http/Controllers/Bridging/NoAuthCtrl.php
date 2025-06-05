<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\OrderBridgeDLIS;
use App\Models\Transaksi\OrderBridgeLIS;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RisOrder;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Master\HargaNettoProdukByKelas;
use App\Models\Transaksi\OrderLab;
use Exception;
use Illuminate\Support\Facades\Log;

class NoAuthCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    private function encryptData($data, $key, $cipher = "AES-128-CTR") {
        $options = 0;
        $encryption_iv = '1234567891011121';
        return openssl_encrypt($data, $cipher, $key, $options, $encryption_iv);
    }

    private function decryptData($encryptedData, $key, $cipher = "AES-128-CTR") {
        $options = 0;
        $decryption_iv = '1234567891011121';
        return openssl_decrypt($encryptedData, $cipher, $key, $options, $decryption_iv);
    }

    public function saveSendBack(Request $request)
    {
        DB::beginTransaction();
        try {
            Log::info("Sendback diterima dari PACS ".$request['Report']['order']['id']);
            Log::channel('radiologiLOG')->info("Sendback diterima dari PACS ".$request['Report']['order']['id']);
            $data = RisOrder::where('accession_num', $request['Report']['order']['id'])
                ->update(
                    [
                        'order_complete' => 1,
                        'description' => $request['Report']['report']['description'],
                        'report_date' => $request['Report']['report']['reportDate'],
                        'charge_doc_id' => $request['Report']['report']['doctorID'],
                        'charge_doc_name' => $request['Report']['report']['doctorName'],
                        'link' => $request['Report']['report']['link']
                    ]
                );

                Log::info("Update RIS Order sukses");
            $risoder = RisOrder::where('accession_num', $request['Report']['order']['id'])->first();
            if (!empty($risoder)) {
                $strukorder = DB::table('strukorder_t')
                    ->where('noorder', $risoder->order_no)
                    ->first();
                if (!empty($strukorder)) {
                    $pelpasien = DB::table('pelayananpasien_t')
                        ->where('strukorderfk', $strukorder->norec)
                        ->where('produkfk', $risoder->order_code)
                        ->first();
                    if (!empty($pelpasien)) {
                        $Expertise = array(
                            "norec" => "",
                            "norec_so" => $strukorder->norec,
                            "hasil" => array(
                                "keterangan" => $request['Report']['report']['description'],
                                "pelayananpasienfk" => $pelpasien->norec,
                                "noregistrasifk" => $pelpasien->noregistrasifk,
                                "pegawaifk" => $request['Report']['report']['doctorID'],
                            )
                        );
                        $buildReq = new Request();
                        $buildReq['norec'] = "";
                        $buildReq['norec_so'] = $strukorder->norec;
                        $buildReq['hasil'] = array(
                            "keterangan" => $request['Report']['report']['description'],
                            "pelayananpasienfk" => $pelpasien->norec,
                            "noregistrasifk" => $pelpasien->noregistrasifk,
                            "pegawaifk" => $request['Report']['report']['doctorID'],
                        );
                        $buildReq['user'] = "PACS RADIOLOGI";
                        $postBuild = app('App\Http\Controllers\Radiologi\RadiologiCtrl')->saveExpertise($buildReq);
                        $postBuild = json_decode($postBuild->content(), true);
                    }
                }
            }
            Log::info("Update Expertise Berhasil");

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $data,
            );
            Log::info("Response API dikirim");
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data" => null,
            );
            Log::warning('Galat '.$request['Report']['order']['id']." ".$e->getMessage() . " " . $e->getLine());
        }
        try {
            if($result['status'] == 200){
                $dataOrder = DB::table('strukorder_t as so')
                            ->join('ris_order as ro', 'ro.order_no', 'so.noorder')
                            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
                            ->join('pasien_m as pas', 'pas.id', 'so.nocmfk')
                            ->select(
                                'ro.patient_id',
                                'ro.patient_name',
                                'ro.accession_num',
                                'so.norec',
                                'ru.objectdepartemenfk',
                                'pas.nohp'
                            )
                            ->where('ro.accession_num', $request['Report']['order']['id'])
                            ->first();
                            $otp = rand(100000, 999999);

                $client = new Client();
                    $baseUrl = 'https://hasil.rsdgunungjati.com/portal/';
                    $queryParams = "?user_name=hisris&password_encrypted=true&password=KGJGBCHIEJJDPCNCJGKEINFHNMHBKAOO&accession_number={$dataOrder->accession_num}";
                    $fullUrl = rawurlencode($baseUrl . $queryParams);
                    $fullUrlExpertise = rawurlencode("http://rsdgunungjati.com/service/radiologi/cetak-ekspertise-nontoken?echo=true&norec={$dataOrder->norec}");


                    $encryptionKey = 'simrsMaju';

                    $date = date('Y-m-d'); // Replace with actual dates
                    $patientName = $dataOrder->patient_name; // Replace with actual patient name
                    $patientID = $dataOrder->patient_id; // Replace with actual patient name
                    $encryptedPatientID = $this->encryptData($patientID, $encryptionKey);
                    $encryptedPatientName = $this->encryptData($patientName, $encryptionKey);
                    $encryptedFullUrl = $this->encryptData(rawurlencode($fullUrl), $encryptionKey);
                    $encryptedFullUrlExpertise = $this->encryptData(rawurlencode($fullUrlExpertise), $encryptionKey);

                    $encryptedOtp = $this->encryptData($otp, $encryptionKey);
                    $finalLink = "https://sim.rsdgunungjati.com/exp?nocm={$encryptedPatientID}&nama_pasien={$encryptedPatientName}&linkPACS={$fullUrl}&link_expertise={$fullUrlExpertise}";


                    $pesan = "Salam hormat,
    Berikut kami sampaikan hasil dari pemeriksaan radiologi tanggal {$date}, a/n pasien {$patientName} ({$dataOrder->patient_id}) Silakan masukkan kode OTP berikut untuk mengakses hasil: *{$otp}*.

    dapat dilihat dengan cara klik link berikut ini :

    {$finalLink}
    *Jika link di atas tidak bisa diklik, silahkan simpan nomor ini terlebih dahulu dan  atau forward pesan ini ke nomor sendiri !*
    Terima kasih,
    RSD Gunung Jati Kota Cirebon";

                    $convertPhoneNumber = function ($phone) {
                        if (strpos($phone, "0") === 0) {
                            return "62" . substr($phone, 1);
                        }
                        return $phone;
                    };

                    $phone = $dataOrder->nohp;
                    $convertedPhone = $convertPhoneNumber($phone);

                    $dataWA = [
                        'phone' => $convertedPhone,
                        'isGroup' => false,
                        'isNewsletter' => false,
                        'message' => $pesan,
                    ];

                    $url = $this->settingFix('APIWA_url');
                    $token = 'Bearer ' . $this->settingFix('APIWA_token');

                    // if($dataOrder->objectdepartemenfk == '18'){
                        $response = $client->post($url, [
                            'headers' => [
                                'Authorization' => $token,
                            ],
                            'json' => $dataWA,
                        ]);
                    // }
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }
}
