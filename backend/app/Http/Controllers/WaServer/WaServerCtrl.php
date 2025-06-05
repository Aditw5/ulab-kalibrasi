<?php

namespace App\Http\Controllers\WaServer;

use App\Http\Controllers\Controller;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class WaServerCtrl extends Controller
{
    use Valet;
    // use JsonRespon;
    // use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $kdProfile;
    protected $is_encrypt;

    protected $current_user = null;
    protected $skip_authentication;

    protected $url;
    protected $auth;

    //result
    // protected $transStatus = true;
    // protected $transMessage = null;
    public function __construct($is_encrypt = false)
    {
        $this->is_encrypt = $is_encrypt;

        $this->kdProfile = session("kdProfile");
        if (empty($this->kdProfile)) {
            $this->kdProfile = Cache::get('kdProfile');
        }
        $this->url = $this->settingFix('APIWA_url');
        $this->auth = 'Bearer ' . $this->settingFix('APIWA_token');
    }

    private function encryptData($data, $key, $cipher = "AES-128-CTR")
    {
        $options = 0;
        $encryption_iv = '1234567891011121';
        return openssl_encrypt($data, $cipher, $key, $options, $encryption_iv);
    }

    private function decryptData($encryptedData, $key, $cipher = "AES-128-CTR")
    {
        $options = 0;
        $decryption_iv = '1234567891011121';
        return openssl_decrypt($encryptedData, $cipher, $key, $options, $decryption_iv);
    }

    public function kirimWARadiologi(Request $request)
    {
        try {
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
                ->where('ro.accession_num', $request['accession_num'])
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

            // if($dataOrder->objectdepartemenfk == '18'){
            $response = $client->post($this->url, [
                'headers' => [
                    'Authorization' => $this->auth,
                ],
                'json' => $dataWA,
            ]);
            // }
            $result = array(
                "status" => 200,
                "message" => "Berhasil Kirim",
                "result" => $dataWA
            );
            return $this->respond($result['result'], $result['status'], $result['message']);
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" => "Gagal Kirim",
                "result"  => $e->getMessage() . $e->getLine()
            );
            return $this->respond($result['result'], $result['status'], $result['message'] . ' - ' . json_encode($result['result']));
        }
        return $this->respond($result['result'], $result['status'], $result['message'] . ' - ' . json_encode($result['result']));
    }
}
