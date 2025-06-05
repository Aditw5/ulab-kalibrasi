<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\AsuransiPasien;
use App\Models\Master\Departemen;
use App\Models\Master\INACBG_Status;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PemakaianAsuransi;
use App\Models\Transaksi\UlasanKlaim;
use App\Traits\Valet;
use Exception;
use GuzzleHttp\Client;
use ZipArchive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class InaCbgCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function dropDownINACBG(Request $r)
    {
        $res['kelompokpasien'] =  KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->get()->toArray();
        $res['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $res['inacbg_status'] = INACBG_Status::mine()->get();


        $ru = Ruangan::mine()->get();
        foreach ($res['departemen']  as $k => $d) {
            $res['departemen'][$k]['ruangan'] = [];
            foreach ($ru  as $dd) {
                if ($dd->objectdepartemenfk == $d['id']) {
                    $res['departemen'][$k]['ruangan'][] = $dd;
                }
            }
        }
        return $this->respond($res);
    }

    public function saveVerifikasiKoder(Request $request)
    {
        // $request->validate([
        //     'norec' => 'required|string',  // Pastikan ID pasien ada di request
        //     'status' => 'required|boolean'  // Status checkbox
        // ]);
        DB::beginTransaction();
        try {
            // Cari record pasien berdasarkan ID
            // $pasiendaftar = DB::table('pasiendaftar_t')->where('id', $request->id)->first();

            // // Jika pasien ditemukan, update kolom isverifikasikoder
            // if ($pasiendaftar) {
            //     DB::table('pasiendaftar_t')
            //         ->where('id', $request->id)
            //         ->update(['isverifikasikoder' => $request->isverifikasikoder]);

            //     return response()->json(['success' => true, 'message' => 'Verifikasi koder berhasil disimpan']);
            // } else {
            //     return response()->json(['success' => false, 'message' => 'Pasien tidak ditemukan'], 404);
            // }

            PasienDaftar::where('norec', $request['norec_pd'])->where('kdprofile', $this->kdProfile)->update([
                'isverifikasikoder' => $request['status'] ? true : false
            ]);

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
            // return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $transMessage
            );
        } else {
            $transMessage = "Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' - ' . $e->getFile()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveStatusDPJPincbgs(Request $request)
    {
        // $request->validate([
        //     'norec' => 'required|string',  // Pastikan ID pasien ada di request
        //     'status' => 'required|boolean'  // Status checkbox
        // ]);
        DB::beginTransaction();
        try {
            // Cari record pasien berdasarkan ID
            // $pasiendaftar = DB::table('pasiendaftar_t')->where('id', $request->id)->first();

            // // Jika pasien ditemukan, update kolom isverifikasikoder
            // if ($pasiendaftar) {
            //     DB::table('pasiendaftar_t')
            //         ->where('id', $request->id)
            //         ->update(['isverifikasikoder' => $request->isverifikasikoder]);

            //     return response()->json(['success' => true, 'message' => 'Verifikasi koder berhasil disimpan']);
            // } else {
            //     return response()->json(['success' => false, 'message' => 'Pasien tidak ditemukan'], 404);
            // }

            PasienDaftar::where('norec', $request['norec_pd'])->where('kdprofile', $this->kdProfile)->update([
                'statusdpjpincbgs' => $request['status'] ? true : false
            ]);

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
            // return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat menyimpan data'], 500);
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $transMessage
            );
        } else {
            $transMessage = "Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result" => $e->getMessage() . ' - ' . $e->getFile()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveBridgingINACBG(Request $request, $local = false)
    {
        $kdProfile = $this->kdProfile;
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }


        $dataReq = $request['data'];
        $responseArr = [];

        foreach ($dataReq as $dataLoop) {
            $json_request = json_encode($dataLoop);
            $payload = $this->inacbg_encrypt($json_request, $key);
            $header = array("Content-Type: application/x-www-form-urlencoded");

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            $response = curl_exec($ch);
            $err = curl_error($ch);
            if ($err) {
                // return $this->setStatusCode(400)->respond($err, $err);
                return $this->respond([
                    'status' => 400,
                    'error' => $err,
                    'message' => 'Gagal menghubungi server InaCBG'
                ], 400);
            }
            $first  = strpos($response, "\n") + 1;
            $last   = strrpos($response, "\n") - 1;
            $response  = substr(
                $response,
                $first,
                strlen($response) - $first - $last
            );
            $response = $this->inacbg_decrypt($response, $key);
            $responseArr[] = array(
                'datarequest' => $dataLoop,
                'dataresponse' =>   $response
            );
        }
        $result = array(
            "status" => 200,
            "dataresponse" => $responseArr,
            "as" => '@epic',
        );
        if ($local) {
            return $result;
        }
        return $this->respond($result, $result['status'], "Bridging InaCBG");

        // return $this->setStatusCode($result['status'])->respond($result, "Bridging InaCBG");
    }
    // Encryption Function
    function inacbg_encrypt($data, $key)
    {
        /// make binary representasion of $key
        $key = hex2bin($key);
        /// check key length, must be 256 bit or 32 bytes
        if (mb_strlen($key, "8bit") !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        /// create initialization vector
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        $iv = openssl_random_pseudo_bytes($iv_size);
        // dengan catatan dibawah
        /// encrypt
        $encrypted = openssl_encrypt(
            $data,
            "aes-256-cbc",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        /// create signature, against padding oracle attacks
        $signature = mb_substr(hash_hmac(
            "sha256",
            $encrypted,
            $key,
            true
        ), 0, 10, "8bit");
        /// combine all, encode, and format
        $encoded = chunk_split(base64_encode($signature . $iv . $encrypted));
        return $encoded;
    }
    // Decryption Function
    function inacbg_decrypt($str, $strkey)
    {
        /// make binary representation of $key
        $key = hex2bin($strkey);
        /// check key length, must be 256 bit or 32 bytes
        if (mb_strlen($key, "8bit") !== 32) {
            throw new Exception("Needs a 256-bit key!");
        }
        /// calculate iv size
        $iv_size = openssl_cipher_iv_length("aes-256-cbc");
        /// breakdown parts
        $decoded = base64_decode($str);
        $signature = mb_substr($decoded, 0, 10, "8bit");
        $iv = mb_substr($decoded, 10, $iv_size, "8bit");
        $encrypted = mb_substr($decoded, $iv_size + 10, NULL, "8bit");
        /// check signature, against padding oracle attack
        $calc_signature = mb_substr(hash_hmac(
            "sha256",
            $encrypted,
            $key,
            true
        ), 0, 10, "8bit");
        if ($this->inacbg_compare($signature, $calc_signature)) {
            //            return "SIGNATURE_NOT_MATCH"; /// signature doesn't match
        }
        $decrypted = openssl_decrypt(
            $encrypted,
            "aes-256-cbc",
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );
        $dtdtd = json_decode($decrypted);
        return $dtdtd;
    }
    /// Compare Function
    function inacbg_compare($a, $b)
    {
        /// compare individually to prevent timing attacks
        /// compare length
        if (strlen($a) !== strlen($b)) return false;
        /// compare individual
        $result = 0;
        for ($i = 0; $i < strlen($a); $i++) {
            $result |= ord($a[$i]) ^ ord($b[$i]);
        }
        return $result == 0;
    }
    public function daftarPasienINACBG(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();
        $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile);
        if (isset($request['deptId']) && $request['deptId'] != "" && $request['deptId'] != "undefined") {
            $dataMaster = $dataMaster->where('objectdepartemenfk', '=', $request['deptId']);
        };
        $dataMaster = $dataMaster->orderBy('nourut');
        $dataMaster = $dataMaster->get();

        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'rk.namarekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk',
                'pd.inacbg_topup',
                'ps.sitb_id',
                'pd.kemkes_dc_status',
                'pd.inacbg_penggunaan_dializer',
                'pd.inacbg_kantong_darah',
                'pd.nosuratmeninggal',
                DB::raw("case when pd.jenispelayanan = 2 then true else false end as eksekutif"),

            )
            ->where('pd.statusenabled', true)
            ->where(function ($query) {
                $query->where('pd.ismobilejkn', true)
                      ->where('pd.ischeckin', true)
                      ->orWhereNull('pd.ismobilejkn');
            })
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['verifikasi_koder']) && $r['verifikasi_koder'] == "true") {
            $data = $data->where('pd.isverifikasikoder', true);
            if (isset($r['status_koder_inacbgs'])) {
                $data = $data->where('pd.statuskoderincbgs', $r['status_koder_inacbgs']);
            }
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        if (isset($r['isVerifikasiKoder']) && $r['isVerifikasiKoder'] == 'true') {
            $data = $data->where('pd.isverifikasikoder', true);
        }
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            if (isset($r['isTanggalPulang']) && $r['isTanggalPulang'] != "" && $r['isTanggalPulang'] != "undefined" && $r['isTanggalPulang'] == "true") {
                $data = $data->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
            } else {
                $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
            }
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            if (isset($r['isTanggalPulang']) && $r['isTanggalPulang'] != "" && $r['isTanggalPulang'] != "undefined" && $r['isTanggalPulang'] == "true") {
                $data = $data->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
            } else {
                $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
            }
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('dept.id', '=', $r['ins']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruang']);
        }
        if (isset($r['inacbg_status']) && $r['inacbg_status'] != "" && $r['inacbg_status'] != "undefined") {
            if ($r['inacbg_status'] == "belum_kirim") {
                $data = $data->whereNull('pd.inacbg_status');
            } else {
                $data = $data->where('pd.inacbg_status', '=', $r['inacbg_status']);
            }
        }
        if (isset($r['isNotSEP']) && $r['isNotSEP'] != "" && $r['isNotSEP'] != "undefined" && $r['isNotSEP'] == "true") {
            $data = $data->whereRaw("(pas.nosep is null or pas.nosep  ='')");
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] == "true") {
            $data = $data->whereNull('pd.inacbg_status');
        }
        $kelPasien  = '';
        if (isset($r['kelId']) && $r['kelId'] != "" && $r['kelId'] != "undefined") {
            $arrKel = explode(',', $r['kelId']);
            $kodeKel = [];
            foreach ($arrKel as $item) {
                $kodeKel[] = (int) $item;
            }
            $kelPasien = ' and kp.id in (' . $r['kelId'] . ')';
            $data = $data->whereIn('kp.id', $kodeKel);
        } else {
            // $data = $data->whereIn('kp.id',  explode(',',$   kelompokPasienINACBG));

        }
        if (isset($r['dokId']) && $r['dokId'] != "" && $r['dokId'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokId']);
        }
        if (isset($r['status_pasien']) && $r['status_pasien'] != "" && $r['status_pasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['status_pasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'like', '%' . $r['noreg'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'like', '%' . $r['nocm'] . '%');
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $data = $data->where('ps.namapasien', 'like', '%' . $r['nama'] . '%');
        }
        if (isset($r['nosep']) && $r['nosep'] != "" && $r['nosep'] != "undefined") {
            $data = $data->where('pas.nosep', '=', $r['nosep']);
        }
        if (isset($r['status']) && $r['status'] != "" && $r['status'] != "undefined") {
            $data = $data->where('pd.statusklaim', '=', $r['status']);
        }
        if (isset($r['pasienpulang']) && $r['pasienpulang'] != "" && $r['pasienpulang'] != "undefined" && $r['pasienpulang'] == 'true') {
            $data = $data->whereNotNull('pd.tglpulang');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('pas.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }

        $data = $data->orderBy('pd.noregistrasi');
        // $data = $data->get();
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        $dataDokKlaim = DB::table("monitoringdokklaim_t")
            // ->where("noregistrasifk", $value->norec)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataDokKlaim = $dataDokKlaim->where('noregistrasifk', '=', $r['norec_pd']);
        }
        $dataDokKlaim = $dataDokKlaim->get();
        $norec_APD = '';
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            try {
                $APD = AntrianPasienDiperiksa::where('noregistrasifk', $r['norec_pd'])
                    ->where('objectruanganfk', $data[0]->ruanganid)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();
            } catch (Exception $ee) {
            }
        }
        $i = 0;
        $dtdt = '';

        $dataDiagnosa = DB::table('detaildiagnosapasien_t as dp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'dp.objectdiagnosafk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosa', 'apd.objectasalrujukanfk', 'pd.norec', 'dp.objectjenisdiagnosafk', 'pd.isverifikasikoder')
            ->wherein('dp.objectjenisdiagnosafk', explode(',', $this->settingFix('jenisDiagnosaINACBG')))

            ->where('pd.kdprofile', $kdProfile)
            ->orderBy('dp.objectjenisdiagnosafk', 'asc');
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] != "" && $r['isNotDiagnosis'] != "undefined" && $r['isNotDiagnosis'] == "true") {
            $dataDiagnosa = $dataDiagnosa->whereNull('pd.inacbg_status');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataDiagnosa = $dataDiagnosa->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
                // ->orWhere('ps.nocm', 'ilike', $searchTerm)
                // ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                // ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $dataDiagnosa = $dataDiagnosa->get();
        foreach ($data as $item) {
            $dtdt = '';
            $asalRujukan = '';
            $covid19_status_cd = '';
            foreach ($dataDiagnosa as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' .  $item2->kddiagnosa;
                    $asalRujukan = $item2->objectasalrujukanfk;
                }
            }
            $data[$i]->icd10 = substr($dtdt, 1, strlen($dtdt) - 1);
            $data[$i]->codernik = $codernik;
            $data[$i]->objectasalrujukanfk = $asalRujukan;
            $data[$i]->kodetarif = $kodetarif;

            foreach ($dataMaster as $itemXX) {

                if ($item->deptid == $itemXX->objectdepartemenfk) {
                    $data[$i]->dokumen[] = array(
                        'name' => $itemXX->dokumen,
                        'kodeexternal' => $itemXX->kodeexternal,
                        'norec_pd' =>   $data[$i]->norec,
                        'noregistrasi' =>   $data[$i]->noregistrasi,
                        'tglregistrasi' =>   $data[$i]->tgl_masuk,
                        'documentklaimfk' => $itemXX->id,
                        'api' => $itemXX->api,
                        'doc' => null
                    );
                }
            }
            foreach ($dataDokKlaim as $datDok) {
                if ($datDok->noregistrasifk == $item->norec) {
                    foreach ($data[$i]->dokumen as $dd => $vv) {
                        if ($datDok->documentklaimfk == $vv['documentklaimfk']) {
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                        }
                    }
                }
            }

            $i = $i + 1;
        }

        $i = 0;
        $dtdt = '';
        $dataICD9 = DB::table('diagnosatindakanpasien_t as dpa')
            ->join('detaildiagnosatindakanpasien_t as dp', 'dpa.norec', '=', 'dp.objectdiagnosatindakanpasienfk')
            ->join('diagnosatindakan_m as dg', 'dg.id', '=', 'dp.objectdiagnosatindakanfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dpa.objectpasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('dg.kddiagnosatindakan', 'pd.norec')
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.norec',  $r['norec_pd']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dataICD9 = $dataICD9->where(function ($query) use ($searchTerm) {
                $query->where('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
                // ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                // ->orWhere('ps.nocm', 'ilike', $searchTerm)
                // ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                // ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        $dataICD9 = $dataICD9->get();
        foreach ($data as $item) {
            $data[$i]->jenis_rawat = 2;
            foreach ($kdDepartemenRawatInap as $kddept) {
                if ($kddept == $item->deptid) {
                    $data[$i]->jenis_rawat = 1;
                }
            }
            $dtdt = '';
            foreach ($dataICD9 as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' . $item2->kddiagnosatindakan;
                }
            }
            $data[$i]->icd9 = substr($dtdt, 1, strlen($dtdt) - 1);
            $i = $i + 1;
        }

        $dataLab = DB::table('order_lab as ol')
            ->select('pd.noregistrasi', 'lh.HISREGNO as no_order')
            ->join('strukorder_t as so', 'so.noorder', '=', 'ol.no_lab')
            ->leftJoin('RESULT_HEADER as lh', 'lh.HISREGNO', '=', 'ol.no_lab')
            ->leftJoin('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'ol.no_registrasi')
            ->distinct()
            ->where('so.kdprofile', $kdProfile)
            ->where('so.statusenabled', true);

        $tanggalKolom = (!empty($r['isTanggalPulang']) && $r['isTanggalPulang'] == 'true') ? 'pd.tglpulang' : 'pd.tglregistrasi';
        if (!empty($r['dari']) && $r['dari'] != 'undefined' && !empty($r['sampai']) && $r['sampai'] != 'undefined') {
            $dataLab->whereBetween($tanggalKolom, [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        } elseif (!empty($r['dari']) && $r['dari'] != 'undefined') {
            $dataLab->where($tanggalKolom, '>=', $r['dari'] . ' 00:00');
        } elseif (!empty($r['sampai']) && $r['sampai'] != 'undefined') {
            $dataLab->where($tanggalKolom, '<=', $r['sampai'] . ' 23:59');
        }
        $dataLab = $dataLab->get();
        $labNoReg = [];
        foreach ($dataLab as $lab) {
            $labNoReg[$lab->noregistrasi][] = $lab;
        }
        foreach ($data as $item) {
            $item->hasil_lab = $labNoReg[$item->noregistrasi] ?? [];
        }

        $dataRad = DB::table('hasilradiologi_t as hr')
            ->distinct()
            ->select('pd.noregistrasi', 'hr.tanggalreport')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hr.noregistrasifk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk');
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            if (isset($r['isTanggalPulang']) && $r['isTanggalPulang'] != "" && $r['isTanggalPulang'] != "undefined" && $r['isTanggalPulang'] == "true") {
                $dataRad = $dataRad->where('pd.tglpulang', '>=', $r['dari'] . ' 00:00');
            } else {
                $dataRad = $dataRad->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
            }
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            if (isset($r['isTanggalPulang']) && $r['isTanggalPulang'] != "" && $r['isTanggalPulang'] != "undefined" && $r['isTanggalPulang'] == "true") {
                $dataRad = $dataRad->where('pd.tglpulang', '<=', $r['sampai'] . ' 23:59');
            } else {
                $dataRad = $dataRad->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
            }
        }
        $dataRad = $dataRad->where('hr.kdprofile', $kdProfile);
        $dataRad = $dataRad->where('hr.statusenabled', true);
        $dataRad = $dataRad->get();
        foreach ($data as $item) {
            $result = [];
            foreach ($dataRad as $rad) {
                if ($rad->noregistrasi == $item->noregistrasi) {
                    array_push($result, $rad);
                }
            }
            $item->hasil_rad = $result;
        }

        $dariawal = '';
        $sampaiakhir = '';
        $noregs = '';
        $norms = '';
        $namas = '';
        $norec_pd = '';
        $ooooor = '';
        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $dariawal = " and pd.tglregistrasi >= '$r[dari] 00:00'";
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $sampaiakhir = " and pd.tglregistrasi <= '$r[sampai] 23:59'";
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $noregs = " and pd.noregistrasi='$r[noreg]'";
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $norms = " and ps.nocm='$r[nocm]'";
        }
        if (isset($r['nama']) && $r['nama'] != "" && $r['nama'] != "undefined") {
            $namas = " and ps.namapasien ilike '%" . $r['nama'] . "%'";
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = $r['search'];
            $ooooor =  " and ( pd.noregistrasi ilike '%$searchTerm%'
            or ps.namapasien ilike '%$searchTerm%'
            or ps.nocm ilike '%$searchTerm%'
            )";
        }

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $norec_pd = " and pd.norec='$r[norec_pd]'";
        }
        foreach ($data as $item) {
            $item->cekRadiologiNotVerif = DB::table('strukorder_t as so')
                ->where('so.noregistrasi', $item->noregistrasi)
                ->where('so.keteranganorder', 'Order Radiologi')
                ->where('so.statusorder', 0)
                ->where('so.statusenabled', true)
                ->exists() ? 1 : 0;

            $item->cekLaboratoriumNotVerif = DB::table('strukorder_t as so')
                ->where('so.noregistrasi', $item->noregistrasi)
                ->where('so.keteranganorder', 'Order Laboratorium')
                ->where('so.statusorder', 0)
                ->where('so.statusenabled', true)
                ->exists() ? 1 : 0;
        }

        $dataTarif16Non = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal,pp.produkfk,pr.namaproduk
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                LEFT JOIN kelompokprodukbpjs_m as kpb on pr.objectkelompokprodukbpjsfk  =kpb.id
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                left join strukpelayanan_t as sp on sp.norec = pp.strukfk
                left join strukbuktipenerimaan_t as sbm on sbm.norec = sp.nosbmlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                --and sbm.nosbm is null
                and pr.objectkelompokprodukbpjsfk is null
                and pp.statusenabled = true
                $dariawal
                $sampaiakhir
                --and kp.id in ($kelompokPasienINACBG)
                $noregs
                $namas
                $norms
                $kelPasien
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal ,pp.produkfk,pr.namaproduk
                order by pd.norec")
        );


        $dataTarif16 = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                from pasiendaftar_t as pd
                inner join pasien_m as ps on ps.id = pd.nocmfk
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                INNER JOIN produk_m as pr on pr.id=pp.produkfk
                INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                left join strukpelayanan_t as sp on sp.norec = pp.strukfk
                left join strukbuktipenerimaan_t as sbm on sbm.norec = sp.nosbmlastfk
                where pd.statusenabled=true
                and pd.kdprofile=$kdProfile
                and pp.statusenabled = true
                --and sbm.nosbm is null
                --and kp.id in ($kelompokPasienINACBG)
                $dariawal
                $sampaiakhir
                $noregs
                $namas
                $norms
                $kelPasien
                $norec_pd
                $ooooor
                group by pd.norec,kpb.namaexternal
                order by pd.norec")
        );
        $i = 0;
        $datatatat = array(
            'prosedur_non_bedah' => 0,
            'prosedur_bedah' => 0,
            'konsultasi' => 0,
            'tenaga_ahli' => 0,
            'keperawatan' => 0,
            'penunjang' => 0,
            'radiologi' => 0,
            'laboratorium' => 0,
            'pelayanan_darah' => 0,
            'rehabilitasi' => 0,
            'kamar' => 0,
            'rawat_intensif' => 0,
            'obat' => 0,
            'obat_kronis' => 0,
            'obat_kemoterapi' => 0,
            'alkes' => 0,
            'bmhp' => 0,
            'sewa_alat' => 0,
        );
        $keys = array_keys($datatatat);
        foreach ($data as $item) {
            if (!empty($APD)) {
                if ($APD->noregistrasifk == $data[$i]->norec) {
                    $data[$i]->norec_apd = $APD->norec;
                }
            }
            $data[$i]->payor_id  = 3;
            $data[$i]->belum_mapping = [];
            $data[$i]->totalmappingtarif = 0;
            $data[$i]->totalbilling = 0;
            $data[$i]->umur_tahun = $this->getAgeYear($data[$i]->tgl_lahir, date('Y-m-d'));
            foreach ($dataTarif16 as $itm) {
                if ($itm->norec == $data[$i]->norec) {
                    foreach ($keys as $k) {
                        if ($itm->namaexternal == $k) {
                            $datatatat[$k] = (float)$itm->ttl;
                            $data[$i]->totalmappingtarif = $data[$i]->totalmappingtarif + (float)$itm->ttl;;
                            break;
                        }
                    }
                }
            }
            foreach ($dataTarif16Non as $itms) {
                if ($itms->norec == $data[$i]->norec) {
                    $data[$i]->totalbilling = $data[$i]->totalbilling + (float)$itms->ttl;;
                    $data[$i]->belum_mapping[] = $itms;
                }
            }

            $data[$i]->tarif_rs = $datatatat;
            $data[$i]->totalbilling = $data[$i]->totalbilling + $data[$i]->totalmappingtarif;
            $data[$i]->new_claim  = $this->new_claim($data[$i]);
            $data[$i]->set_claim_data  = $this->set_claim_data($data[$i], $depRI, $depIGD);
            $data[$i]->grouper  = $this->grouper($data[$i]);
            $data[$i]->delete_claim  = $this->delete_claim($data[$i]);


            $i = $i + 1;
        }
        $data = $data->toArray();
        // if(isset($r['isNotDiagnosis']) && $r['isNotDiagnosis'] == 'true'){
        //     $dataR = [];
        //     // return count($data['data']);
        //     for ($i = count($data['data']) - 1; $i >= 0; $i--) {
        //         if($data['data'][$i]->icd10 == false){
        //             $dataR[] = $data['data'][$i];
        //         }
        //     }
        //     $data['data'] = $dataR;
        // }

        // $result['total'] = count($data);
        // $result['data'] = $data;
        return $this->respond($data);
    }
    function new_claim($r)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'new_claim',
            ),
            'data' =>
            array(
                'nomor_kartu' => $r->nomor_kartu,
                'nomor_sep' => $r->nomor_sep,
                'nomor_rm' => $r->nomor_rm,
                'nama_pasien' => $r->nama_pasien,
                'tgl_lahir' => $r->tgl_lahir,
                'gender' => $r->gender,
            )
        );
        return $data;
    }
    function set_claim_data($r, $depRI, $depIGD)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'set_claim_data',
                'nomor_sep' => $r->nomor_sep,
            ),
            'data' =>
            array(
                'nomor_sep' => $r->nomor_sep,
                'nomor_kartu' => $r->nomor_kartu,
                'tgl_masuk' => $r->tgl_masuk,
                'tgl_pulang' => $r->tgl_pulang,
                'cara_masuk' =>  $r->inacbg_kode ? $r->inacbg_kode : 'other',
                'jenis_rawat' => $depRI == $r->deptid ? 1 : ($depIGD == $r->deptid ? 2 : 2), // 1 = rawat inap, 2 = rawat jalan, 3 = rawat igd
                'kelas_rawat' => $depRI == $r->deptid ? $r->kelas_rawat : $r->kelas_dijamin,
                'adl_sub_acute' => '',
                'adl_chronic' =>  '',
                'icu_indikator' =>  '',
                'icu_los' => '',
                'ventilator_hour' =>  '',
                'ventilator' =>
                array(
                    'use_ind' => '',
                    'start_dttm' => '',
                    'stop_dttm' => '',
                ),
                'upgrade_class_ind' => $depRI == $r->deptid && $r->kelas_dijamin  != null && $r->kelas_rawat  != $r->kelas_dijamin ? '1' : '',
                'upgrade_class_class' => $depRI == $r->deptid && $r->kelas_dijamin  != null &&  $r->kelas_rawat  != $r->kelas_dijamin ? $r->kelas_rawat_nama : '',
                'upgrade_class_los' => '',
                'upgrade_class_payor' => $depRI == $r->deptid && $r->kelas_dijamin  != null &&   $r->kelas_rawat  != $r->kelas_dijamin ? 'peserta' : '', //"peserta/pemberi_kerja/asuransi_tambahan
                'add_payment_pct' => '',
                'birth_weight' => '',
                'sistole' => '',
                'diastole' => '',
                'discharge_status' => $r->discharge_status ? $r->discharge_status : '1',
                'diagnosa' => $r->icd10,
                'procedure' => $r->icd9,
                'diagnosa_inagrouper' => $r->icd10,
                'procedure_inagrouper' =>  $r->icd9,
                'tarif_rs' => $r->tarif_rs,
                'pemulasaraan_jenazah' => '',
                'kantong_jenazah' => '',
                'peti_jenazah' => '',
                'plastik_erat' => '',
                'desinfektan_jenazah' => '',
                'mobil_jenazah' => '',
                'desinfektan_mobil_jenazah' => '',
                'covid19_status_cd' => '',
                'nomor_kartu_t' => 'kartu_jkn',
                'episodes' => '',
                'covid19_cc_ind' => '',
                'covid19_rs_darurat_ind' => '',
                'covid19_co_insidense_ind' => '',
                'covid19_penunjang_pengurang' =>
                array(
                    'lab_asam_laktat' => '',
                    'lab_procalcitonin' => '',
                    'lab_crp' => '',
                    'lab_kultur' => '',
                    'lab_d_dimer' => '',
                    'lab_pt' => '',
                    'lab_aptt' => '',
                    'lab_waktu_pendarahan' => '',
                    'lab_anti_hiv' => '',
                    'lab_analisa_gas' => '',
                    'lab_albumin' => '',
                    'rad_thorax_ap_pa' => '',
                ),
                'terapi_konvalesen' => '',
                'akses_naat' => '',
                'isoman_ind' => '',
                'bayi_lahir_status_cd' => $r->payor_id == 7 ? '1' : '',
                'dializer_single_use' => '',
                'kantong_darah' => '',
                'apgar' =>
                array(
                    'menit_1' =>
                    array(
                        'appearance' => '',
                        'pulse' => '',
                        'grimace' => '',
                        'activity' => '',
                        'respiration' => '',
                    ),
                    'menit_5' =>
                    array(
                        'appearance' => '',
                        'pulse' => '',
                        'grimace' => '',
                        'activity' => '',
                        'respiration' => '',
                    ),
                ),
                'persalinan' =>
                array(
                    'usia_kehamilan' => '',
                    'gravida' => '',
                    'partus' => '',
                    'abortus' => '',
                    'onset_kontraksi' => '',
                    'delivery' =>
                    array(
                        0 =>
                        array(
                            'delivery_sequence' => '',
                            'delivery_method' => '',
                            'delivery_dttm' => '',
                            'letak_janin' => '',
                            'kondisi' => '',
                            'use_manual' => '',
                            'use_forcep' => '',
                            'use_vacuum' => '',
                            'shk_spesimen_ambil' => 'tidak',
                            'shk_alasan' => 'tidak-dapat',
                            'shk_lokasi' => '',
                            'shk_spesimen_dttm' => '',
                        ),
                        1 =>
                        array(
                            'delivery_sequence' => '',
                            'delivery_method' => '',
                            'delivery_dttm' => '',
                            'letak_janin' => '',
                            'kondisi' => '',
                            'use_manual' => '',
                            'use_forcep' => '',
                            'use_vacuum' => '',
                            'shk_spesimen_ambil' => 'tidak',
                            'shk_alasan' => 'tidak-dapat',
                            'shk_lokasi' => '',
                            'shk_spesimen_dttm' => '',
                        ),
                    ),
                ),
                'tarif_poli_eks' => '',
                'nama_dokter' => $r->nama_dokter,
                'kode_tarif' => $r->kodetarif,
                'payor_id' =>  $r->payor_id,
                'payor_cd' => 'JKN',
                'cob_cd' => '#',
                'coder_nik' =>  $r->codernik,
            ),
        );
        return $data;
    }
    function grouper($r)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'grouper',
                "stage" => "1"
            ),
            'data' =>
            array(
                'nomor_sep' => $r->nomor_sep,
            )
        );
        return $data;
    }
    function delete_claim($r)
    {
        $data = array(
            'metadata' =>
            array(
                'method' => 'delete_claim',
            ),
            'data' =>
            array(
                'nomor_sep' => $r->nomor_sep,
                'coder_nik' => $r->codernik,
            )
        );
        return $data;
    }
    public function saveStatusBridgingINACBG(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request['data'] as $value) {
                $pd = PasienDaftar::where('norec', $value['norec'])
                    ->first();
                $pd->inacbg_status = $value['inacbg_status'] == 'delete_claim' ? null : $value['inacbg_status'];
                if (isset($value['kemkes_dc_status'])) {
                    $pd->kemkes_dc_status = $value['kemkes_dc_status'];
                }
                if (isset($value['bpjs_dc_status'])) {
                    $pd->bpjs_dc_status = $value['bpjs_dc_status'];
                }
                if (isset($value['cob_dc_status'])) {
                    $pd->cob_dc_status = $value['cob_dc_status'];
                }
                if (isset($value['diagnosa'])) {
                    $pd->inacbg_diagnosa = $value['diagnosa'];
                }
                if (isset($value['procedure'])) {
                    $pd->inacbg_procedure = $value['procedure'];
                }
                if (isset($value['diagnosa_inagrouper'])) {
                    $pd->inacbg_diagnosa_inagrouper = $value['diagnosa_inagrouper'];
                }
                if (isset($value['procedure_inagrouper'])) {
                    $pd->inacbg_procedure_inagrouper = $value['procedure_inagrouper'];
                }
                if ($value['inacbg_status'] == 'delete_claim') {
                    $pd->inacbg_totalgrouper = null;
                    $pd->inacbg_biayanaikkelas = null;
                    $pd->inacbg_totaltarifrs = null;
                    $pd->inacbg_grouper = null;
                }

                if ($value['inacbg_status'] == 'sitb_validate') {
                    $pd->sitb_id = $value['sitb_id'];
                }
                if (isset($value['dokterdpjp']) && $value['dokterdpjp'] != null) {
                    if ($pd->objectpegawaifk != $value['dokterdpjp']) {
                        $dokterlama = Pegawai::mine()->where('id', $pd->objectpegawaifk)->first();
                        $dokterbaru = Pegawai::mine()->where('id', $value['dokterdpjp'])->first();
                        $pd->objectpegawaifk = $value['dokterdpjp'];
                        $this->LOGGING(
                            'Registrasi Pasien',
                            $pd->norec,
                            'pasiendaftar_t',
                            'Merubah DPJP di Noregistrasi ' . $pd->noregistrasi . '  dari ' . $dokterlama->namalengkap . ' ke ' . $dokterbaru->namalengkap . ' tgl ' . date('Y-m-d')
                        );
                    }
                }
                if (isset($value['inacbg_penggunaan_dializer'])) {
                    $pd->inacbg_penggunaan_dializer = $value['inacbg_penggunaan_dializer'];
                }
                if (isset($value['inacbg_kantong_darah'])) {
                    $pd->inacbg_kantong_darah = $value['inacbg_kantong_darah'];
                }
                $pd->save();
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
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveGroupingINACBG(Request $r)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $pd = PasienDaftar::where('norec', $r['norec'])->first();
            $totalBiaya =  PelayananPasien::totalTagihan($pd->noregistrasi);

            $totalpertindakan = PelayananPasien::where('kdprofile', $kdProfile)
                ->where('statusenabled', true)
                ->where('noregistrasi', $pd->noregistrasi)
                ->select(DB::raw("norec,
                COALESCE (hargadiscount, 0)  as diskon,
                ( (hargasatuan  - COALESCE (hargadiscount, 0))   * jumlah)
                + ( COALESCE (jasa, 0)) as totalbiayapertindakan"))
                ->get();
            foreach ($totalpertindakan->chunk(1000) as $chunk) {
                $cases = [];
                $ids = [];
                foreach ($chunk as $item) {
                    $proporsipertindakan = ((float)$r['totaldijamin'] / (float)$totalBiaya) * (float)$item->totalbiayapertindakan;
                    $cases[] = "WHEN '{$item->norec}' then " . $proporsipertindakan;
                    $ids[] = "'" . $item->norec . "'";
                }
                $ids = implode(',', $ids);
                $cases = implode(' ', $cases);

                if (!empty($ids)) {
                    DB::update("UPDATE pelayananpasien_t SET piutangpenjamin = CASE norec {$cases} END WHERE norec in ({$ids})");
                }
            }

            $pd->inacbg_totalgrouper = (float)$r['totaldijamin'];
            $pd->inacbg_biayanaikkelas = (float)$r['biayanaikkelas'];
            $pd->inacbg_totaltarifrs = (float)$totalBiaya;
            $pd->inacbg_grouper = json_encode($r['inacbg_grouper']);
            $pd->inacbg_topup = isset($r['totaltopup']) ? $r['totaltopup'] : null;
            $pd->save();
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
    public function listDokterPaging(Request $r)
    {
        $result['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $result['dokter'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $result['idJenisPegawaiDokter'])
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function getStatusBridgingINACBG(Request $request)
    {
        $d = PasienDaftar::where('norec', $request['norec'])->first();
        $status = INACBG_Status::mine()->get();
        $stat = $d->inacbg_status;
        foreach ($status as $it) {
            if ($it->inacbg_status == $d->inacbg_status) {
                $stat =  $it->status;
            }
        }

        $res['inacbg_status'] = $stat;
        $res['kemkes_dc_status'] = $d->kemkes_dc_status;
        $res['bpjs_dc_status'] = $d->bpjs_dc_status;
        $res['cob_dc_status'] = $d->cob_dc_status;
        return $this->respond($res);
    }
    public function getGroupingINACBG(Request $request)
    {
        $d = PasienDaftar::where('norec', $request['norec'])->first()->inacbg_grouper;
        if ($d != null) {
            $d =  json_decode($d);
        }
        return $this->respond($d);
    }
    public function saveDokumenINACBG(Request $dataReq)
    {
        DB::beginTransaction();
        try {
            $uploadBerkasPasien = $dataReq->file('fileBerkas');
            $dataRegistrasi = PasienDaftar::where('norec', $dataReq['norec_pd'])->first();
            $path = 'dokumen_klaim/' . $dataRegistrasi->noregistrasi;

            $extension = $uploadBerkasPasien->getClientOriginalExtension();
            $filename = $dataReq['namafile'] . '_' . $dataRegistrasi->noregistrasi . '.' . $extension;

            // $filename = $dataReq['namafile'] . '_' . date('YmdHis') . '.' . $extension;
            // DB::table('monitoringdokklaim_t')
            // ->where('noregistrasifk', $dataReq['norec_pd'])
            // ->where('documentklaimfk', $dataReq['documentklaimfk'])
            // ->update(['statusenabled' => false]);

            $dataInsert = array(
                "norec" => Uuid::uuid4(),
                "kdprofile" => $this->kdProfile,
                "statusenabled" => true,
                "filename" => $filename,
                "filepath" => $path . '/' . $filename,
                "nocmfk" => $dataRegistrasi->nocmfk,
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
                "tglregistrasi" => $dataReq['tglregistrasi'],
            );
            DB::table('monitoringdokklaim_t')->updateOrInsert([
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
            ], $dataInsert);
            // $dataReq->file('fileBerkas')->move($path, $filename);

            $dataReq->fPut(
                $path . '/' . $filename,
                File::get($dataReq->file('fileBerkas')->getRealPath()),
                'ftp'
            );
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "filename" => $filename
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

    public function deleteDokumenMonitoring(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        DB::beginTransaction();
        try {
            $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
            $Dkmn = DB::table("monitoringdokklaim_t")
                ->where('noregistrasifk', $dataRegistrasi->norec)
                ->where('documentklaimfk', $request['documentklaimfk'])
                ->where('kdprofile', $idProfile)
                ->first();

            // delete file
            $path = public_path($Dkmn->filepath);
            if (File::exists($Dkmn->filepath)) {
                File::delete($path);
            }

            // detele data
            DB::table("monitoringdokklaim_t")
                ->where('noregistrasifk', $dataRegistrasi->norec)
                ->where('documentklaimfk', $request['documentklaimfk'])
                ->where('kdprofile', $idProfile)
                ->delete();
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
    // public function bundleDokumen(Request $request)
    // {

    //     $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])
    //         ->where('kdprofile', $this->kdProfile)
    //         ->first();
    //     $dataDokumen = DB::table('monitoringdokklaim_t as mk')
    //         ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
    //         ->select('mk.*')
    //         ->where('mk.noregistrasifk', $dataRegistrasi->norec)
    //         ->where('mk.kdprofile', $this->kdProfile)
    //         ->where('mk.statusenabled', true)
    //         ->orderBy('dk.nourut')
    //         ->get();
    //     $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
    //     $disk = 'app/public/';
    //     $pathbundle = $disk . 'dokumen_klaim/' . $request['noregistrasi'] . "/" . $fileName;
    //     if (File::exists($pathbundle)) {
    //         File::delete($pathbundle);
    //     }
    //     // $localDisk = empty(config('app.s3telkom'));
    //     // $storage = 'local';
    //     // if ($localDisk) {
    //     //     $disk = $storage;
    //     // } else {
    //     //     $disk = 's3'.$storage;
    //     // }

    //     // dd($disk);
    //     if (count($dataDokumen) > 0) {
    //         $file = [];
    //         foreach ($dataDokumen as $item) {
    //             array_push($file, storage_path($disk . $item->filepath));
    //         }

    //         $pdf = PDFMerger::init();
    //         foreach ($file as $data) {
    //             $pdf->addPDF($data, 'all');
    //         }
    //         $pdf->merge();
    //         $pdf->save(storage_path($pathbundle));

    //         if (!File::exists(storage_path($pathbundle))) {
    //             return '
    //             <script language="javascript">
    //                 window.alert("Tidak ada data.");
    //                 window.close()
    //             </script>';
    //         }
    //         $file = File::get(storage_path($pathbundle));

    //         $type = File::mimeType(storage_path($pathbundle));

    //         $response = response()->make($file, 200);
    //         $response->header("Content-Type", $type);
    //         return $response;
    //     } else {
    //         return '
    //         <script language="javascript">
    //             window.alert("Tidak ada data.");
    //             window.close()
    //         </script>';
    //     }
    // }

    public function bundleDokumen(Request $request)
    {
        $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])
            ->where('kdprofile', $this->kdProfile)
            ->first();

        $dataDokumen = DB::table('monitoringdokklaim_t as mk')
            ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
            ->select('mk.*')
            ->where('mk.noregistrasifk', $dataRegistrasi->norec)
            ->where('mk.kdprofile', $this->kdProfile)
            ->where('mk.statusenabled', true)
            ->orderBy('dk.nourut')
            ->get();

        $fileName = 'bundle_' . $request['noregistrasi'] . '.pdf';
        $disk = 'ftp';
        $remotePath = 'dokumen_klaim/' . $request['noregistrasi'] . "/";
        $pathbundle = $remotePath . $fileName;

        // Ensure the previous bundle file is deleted if it exists
        if (Storage::disk($disk)->exists($pathbundle)) {
            Storage::disk($disk)->delete($pathbundle);
        }

        if (count($dataDokumen) > 0) {
            $localFiles = [];
            foreach ($dataDokumen as $item) {
                // Download each file from the FTP server to a local temporary directory
                $localPath = storage_path('app/temp/' . basename($item->filepath));
                Storage::disk('local')->put('temp/' . basename($item->filepath), Storage::disk($disk)->get($item->filepath));

                $localFiles[] = $localPath;
            }

            // Initialize PDFMerger
            $pdf = PDFMerger::init();
            foreach ($localFiles as $file) {
                $pdf->addPDF($file, 'all');
            }
            $pdf->merge();

            // Save the merged PDF to local storage first
            $localTempPath = storage_path('app/temp/' . $fileName);
            $pdf->save($localTempPath);

            // Upload the merged PDF to the FTP server
            Storage::disk($disk)->put($pathbundle, file_get_contents($localTempPath));

            // Clean up the temporary files
            foreach ($localFiles as $file) {
                unlink($file);
            }
            unlink($localTempPath);

            if (!Storage::disk($disk)->exists($pathbundle)) {
                return '
                <script language="javascript">
                    window.alert("Tidak ada data.");
                    window.close()
                </script>';
            }

            // Retrieve the merged PDF from the FTP server for the response
            $file = Storage::disk($disk)->get($pathbundle);
            $type = Storage::disk($disk)->mimeType($pathbundle);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type);
            $response->header("Content-Disposition", "attachment; filename=\"{$fileName}\"");

            return $response;
        } else {
            return '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
        }
    }

    public function downloadAllDocuments(Request $request)
    {
        try {
            $startDate = $request['start_date'];
            $endDate = $request['end_date'];

            Log::info('Starting document download process from ' . $startDate . ' to ' . $endDate);

            // Fetch all registrations within the specified period
            $registrations = $request['data'];

            $zipFileName = 'all_documents_' . $startDate . '_to_' . $endDate . '.zip';
            $localZipPath = storage_path('app/temp/' . $zipFileName);
            $zip = new ZipArchive;

            // Create the temp directory if it doesn't exist
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
                Log::info('Created temp directory at ' . $tempDir);
            }

            if ($zip->open($localZipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
                Log::info('ZIP archive created at ' . $localZipPath);

                foreach ($registrations as $registration) {
                    $noregistrasi = $registration['registrasi'];
                    Log::info('Processing registration: ' . $noregistrasi);

                    $bundlePath = $this->bundleDokumenLocal($noregistrasi);

                    if ($bundlePath) {
                        $fullLocalBundlePath = storage_path('app/temp/' . $bundlePath);
                        if (file_exists($fullLocalBundlePath)) {
                            $zip->addFile($fullLocalBundlePath, basename($bundlePath));
                            Log::info('Added file to ZIP: ' . $bundlePath);
                        } else {
                            Log::warning('Bundle file not found: ' . $fullLocalBundlePath);
                        }
                    } else {
                        Log::warning('No bundle found for registration: ' . $noregistrasi);
                    }
                }

                $zip->close();
                Log::info('ZIP archive closed');

                // Check if the file exists right after closing the zip
                if (file_exists($localZipPath)) {
                    Log::info('ZIP file exists at ' . $localZipPath);
                } else {
                    Log::error('ZIP file not found immediately after close: ' . $localZipPath);
                }
            } else {
                Log::error('Failed to create ZIP file: ' . $localZipPath);
                return response()->json(['error' => 'Failed to create ZIP file.'], 500);
            }

            if (!file_exists($localZipPath)) {
                Log::error('ZIP file not found after creation: ' . $localZipPath);
                return response()->json(['error' => 'ZIP file not found.'], 404);
            }

            return response()->download($localZipPath)->deleteFileAfterSend(true);
        } catch (\Throwable $th) {
            Log::error('Error in downloadAllDocuments: ' . $th->getMessage(), ['exception' => $th]);
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }

    private function bundleDokumenLocal($noregistrasi)
    {
        try {
            $dataRegistrasi = PasienDaftar::where('noregistrasi', $noregistrasi)
                ->where('kdprofile', $this->kdProfile)
                ->first();

            if (!$dataRegistrasi) {
                Log::warning('No registration found for: ' . $noregistrasi);
                return null;
            }

            $dataDokumen = DB::table('monitoringdokklaim_t as mk')
                ->join("dokumenklaim_m as dk", "dk.id", "=", "mk.documentklaimfk")
                ->select('mk.*')
                ->where('mk.noregistrasifk', $dataRegistrasi->norec)
                ->where('mk.kdprofile', $this->kdProfile)
                ->where('mk.statusenabled', true)
                ->orderBy('dk.nourut')
                ->get();

            $fileName = 'bundle_' . $noregistrasi . '.pdf';
            $disk = 'ftp';
            $remotePath = 'dokumen_klaim/' . $noregistrasi . "/";
            $pathbundle = $remotePath . $fileName;

            // Ensure the previous bundle file is deleted if it exists
            if (Storage::disk($disk)->exists($pathbundle)) {
                Storage::disk($disk)->delete($pathbundle);
            }

            if (count($dataDokumen) > 0) {
                $localFiles = [];
                foreach ($dataDokumen as $item) {
                    // Download each file from the FTP server to a local temporary directory
                    $localPath = storage_path('app/temp/' . basename($item->filepath));
                    Storage::disk('local')->put('temp/' . basename($item->filepath), Storage::disk($disk)->get($item->filepath));
                    $localFiles[] = $localPath;
                }

                // Initialize PDFMerger
                $pdf = PDFMerger::init();
                foreach ($localFiles as $file) {
                    $pdf->addPDF($file, 'all');
                }
                $pdf->merge();

                // Save the merged PDF to local storage first
                $localTempPath = storage_path('app/temp/' . $fileName);
                $pdf->save($localTempPath);

                // Upload the merged PDF to the FTP server
                Storage::disk($disk)->put($pathbundle, file_get_contents($localTempPath));

                // Clean up the temporary files
                foreach ($localFiles as $file) {
                    unlink($file);
                }
                unlink($localTempPath);

                if (!Storage::disk($disk)->exists($pathbundle)) {
                    Log::warning('Bundled document not found on FTP server: ' . $pathbundle);
                    return null;
                }

                return $fileName; // Return the local file path
            }

            Log::warning('No documents found to bundle for registration: ' . $noregistrasi);
            return null;
        } catch (\Throwable $th) {
            Log::error('Error in bundleDokumenLocal: ' . $th->getMessage(), ['exception' => $th]);
            return null;
        }
    }


    public function collectSemuaDokumenINACBG(Request $request)
    {
        $dokumen = $request['dokumen'];
        $dataRegistrasi = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
        try {
            DB::beginTransaction();
            $dataFileSuccess = [];

            try {
                $dataRegistrasiIndi = PasienDaftar::where('norec', $dokumen[0]['norec_pd'])->first();
                if (!$dataRegistrasiIndi) {
                    return $this->respond(['message' => 'Data registrasi tidak ditemukan'], 404);
                }
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $dataRegistrasiIndi->noregistrasi;
                $objetoRequest['user'] = $this->getNamaPegawai();
                $objetoRequest['storage'] = true;

                $route = explode('@', $dokumen[0]['api']);
                $fixroute = "App\Http\Controllers\\" . str_replace('-', "\\", $route[0]);
                $fix = str_replace("'", '', $fixroute);

                $funccollect = explode('-', $route[1]);
                $func = $funccollect[0];
                $collection = isset($funccollect[1]) ? $funccollect[1] : "";

                try {
                    if ($collection) {
                        $pdf = app($fix)->$func($collection, $objetoRequest);
                    } else {
                        $pdf = app($fix)->$func($objetoRequest);
                    }

                    if ($pdf) {
                        $extension = 'pdf';
                        $path = 'dokumen_klaim/' . $objetoRequest['noregistrasi'];
                        $filename = $dokumen[0]['kodeexternal'] . $objetoRequest['noregistrasi'] . '.' . $extension;

                        $dataInsert = [
                            "norec" => Uuid::uuid4(),
                            "kdprofile" => $this->kdProfile,
                            "statusenabled" => true,
                            "filename" => $filename,
                            "filepath" => $path . '/' . $filename,
                            "nocmfk" => $dataRegistrasi->nocmfk,
                            "noregistrasifk" => $dokumen[0]['norec_pd'],
                            "documentklaimfk" => $dokumen[0]['documentklaimfk'],
                            "tglregistrasi" => $dokumen[0]['tglregistrasi'],
                        ];

                        DB::table('monitoringdokklaim_t')->updateOrInsert(
                            [
                                "noregistrasifk" => $dokumen[0]['norec_pd'],
                                "documentklaimfk" => $dokumen[0]['documentklaimfk'],
                            ],
                            $dataInsert
                        );
                        DB::commit();
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('Gagal membuat PDF: ' . $e->getMessage());
                }
            } catch (\Exception $e) {
                Log::error('Terjadi kesalahan: ' . $e->getMessage());
            }

            foreach ($dokumen as $data) {
                $dataReq = new \Illuminate\Http\Request();
                $dataReq['api'] = $data['api'];
                $dataReq['namafile'] = $data['kodeexternal'];
                $dataReq['noregistrasi'] = $dataRegistrasi->noregistrasi;
                $dataReq['norec_pd'] = $dataRegistrasi->norec;
                $dataReq['tglregistrasi'] = $dataRegistrasi->tglregistrasi;
                $dataReq['documentklaimfk'] = $data['documentklaimfk'];
                $dataReq['user'] = $this->getNamaPegawai();
                $dataReq['storage'] = true;
                if (isset($data['api']) && $data['api'] !== null) {
                    $route =  explode('@', $dataReq['api']);
                    $fixroute = "App\Http\Controllers\'" . str_replace('-', "\'", $route[0]);
                    $fix =  str_replace("'", '', $fixroute);
                    $funccollect = explode('-', $route[1]);
                    $func = $funccollect[0];
                    $collection = isset($funccollect[1]) ? $funccollect[1] : "";
                    if (isset($funccollect[1])) {
                        // FROM CETAK EMR
                        $pdf = app($fix)->$func($collection, $dataReq);
                    } else {
                        $pdf = app($fix)->$func($dataReq);
                    }

                    $extension = 'pdf';
                    $path = 'dokumen_klaim/' . $dataReq['noregistrasi'];
                    $filename = $dataReq['namafile']  . $dataReq['noregistrasi'] . '.' . $extension;
                    $content = null;
                    if ($dataReq['namafile'] != 'klaim_indi') {
                        if (empty($pdf) || !$pdf) {

                        }else{
                            try {
                                $content = $pdf->download()->getOriginalContent();
                                $dataInsert = array(
                                "norec" => Uuid::uuid4(),
                                "kdprofile" => $this->kdProfile,
                                "statusenabled" => true,
                                "filename" => $filename,
                                "filepath" => $path . '/' . $filename,
                                "nocmfk" => $dataRegistrasi->nocmfk,
                                "noregistrasifk" => $dataReq['norec_pd'],
                                "documentklaimfk" => $dataReq['documentklaimfk'],
                                "tglregistrasi" => $dataReq['tglregistrasi'],
                            );
                            DB::table('monitoringdokklaim_t')->updateOrInsert([
                                "noregistrasifk" => $dataReq['norec_pd'],
                                "documentklaimfk" => $dataReq['documentklaimfk'],
                            ], $dataInsert);
                            if ($dataReq['namafile'] != 'klaim_indi') {
                                $dataReq->fPut(
                                    $path . '/' . $filename,
                                    $content,
                                    'ftp'
                                );
                            }
                            $dataFileSuccess[$dataReq['namafile']] = $filename;
                            $transMessage = "Sukses";
                            DB::commit();
                            } catch (\Throwable $th) {
                                return $dataReq['namafile'];
                            }
                        }
                    }
                } else {
                    continue;
                }
            }
        } catch (\Throwable $th) {
            return "error".$th;
        }

        $result = array(
            "status" => 200,
            "result" => array(
                "as" => '@epic',
                "filename" => $dataFileSuccess,
            ),
        );

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function collectDokumenINACBG(Request $dataReq)
    {
        DB::beginTransaction();
        try {

            $dataRegistrasi = PasienDaftar::where('norec', $dataReq['norec_pd'])->first();

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['noregistrasi'] = $dataRegistrasi->noregistrasi;
            $objetoRequest['user'] = $this->getNamaPegawai();
            $objetoRequest['storage'] = true;
            $route =  explode('@', $dataReq['api']);
            $fixroute = "App\Http\Controllers\'" . str_replace('-', "\'", $route[0]);
            $fix =  str_replace("'", '', $fixroute);
            $funccollect = explode('-', $route[1]);
            $func = $funccollect[0];
            $collection = isset($funccollect[1]) ? $funccollect[1] : "";
            if (isset($funccollect[1])) {
                // FROM CETAK EMR
                $pdf = app($fix)->$func($collection, $objetoRequest);
            } else {
                $pdf = app($fix)->$func($objetoRequest);
            }

            $extension = 'pdf';
            $path = 'dokumen_klaim/' . $objetoRequest['noregistrasi'];
            $filename = $dataReq['namafile']  . $objetoRequest['noregistrasi'] . '.' . $extension;
            $content = null;
            if ($dataReq['namafile'] != 'klaim_indi') {
                if (empty($pdf)) {
                    $transMessage = "Data belum ada";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result"  => null
                    );

                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $content = $pdf->download()->getOriginalContent();
            }else{
                if (empty($pdf)) {
                    $transMessage = "Data belum ada";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result"  => null
                    );

                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
            }

            $dataInsert = array(
                "norec" => Uuid::uuid4(),
                "kdprofile" => $this->kdProfile,
                "statusenabled" => true,
                "filename" => $filename,
                "filepath" => $path . '/' . $filename,
                "nocmfk" => $dataRegistrasi->nocmfk,
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
                "tglregistrasi" => $dataReq['tglregistrasi'],
            );
            DB::table('monitoringdokklaim_t')->updateOrInsert([
                "noregistrasifk" => $dataReq['norec_pd'],
                "documentklaimfk" => $dataReq['documentklaimfk'],
            ], $dataInsert);
            if ($dataReq['namafile'] != 'klaim_indi') {
                $dataReq->fPut(
                    $path . '/' . $filename,
                    $content,
                    'ftp'
                );
            }
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "filename" => $filename
                ),
            );
        } catch (\Exception $e) {
            $transMessage = $e->getMessage(); //"Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getFlafonINACBG(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $depRI =  $this->settingFix('kdDepartemenRanapFix');
        $depIGD =   $this->settingFix('idDepartemenIGD');
        $deptRanap = explode(',', $depRI);
        $kdDepartemenRawatInap = [];
        foreach ($deptRanap as $itemRanap) {
            $kdDepartemenRawatInap[] =  (int)$itemRanap;
        }
        $data  = DB::table('settingdatafixed_m')
            ->select('namafield', 'nilaifield')
            ->where('kelompok', "INACBG's")
            ->where('kdprofile', $kdProfile)
            ->get();


        $codernik = '';
        $key = '';
        $url = '';
        $kodetarif = '';
        $kelompokPasienINACBG = '';
        foreach ($data as $item) {
            if ($item->namafield == 'codernik') {
                $codernik = $item->nilaifield;
            }
            if ($item->namafield == 'key') {
                $key = $item->nilaifield;
            }
            if ($item->namafield == 'url') {
                $url = $item->nilaifield;
            }
            if ($item->namafield == 'kodetarif') {
                $kodetarif = $item->nilaifield;
            }
            if ($item->namafield == 'kelompokPasienINACBG') {
                $kelompokPasienINACBG = $item->nilaifield;
            }
        }

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'asu.id', '=', 'pas.objectasuransipasienfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'asu.objectkelasdijaminfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asalrujukan_m as asa', 'asa.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->select(
                'pd.norec',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglpulang as tgl_pulang',
                'ps.nocm as nomor_rm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien as nama_pasien',
                'kp.kelompokpasien',
                'pd.statuspasien',
                'pg.id as pgid',
                'pg.namalengkap as nama_dokter',
                'kp.id as kpid',
                'pd.objectruanganlastfk as ruanganid',
                'pas.nosep as nomor_sep',
                'pas.norec as norec_pa',
                'ps.nobpjs as nomor_kartu',
                'ps.tgllahir as tgl_lahir',
                'ps.objectjeniskelaminfk as gender',
                'dept.id as deptid',
                'kls.kodebpjs as kelas_rawat',
                'kls.namabpjs as kelas_rawat_nama',
                'pas.klsrawathak_kode as kelas_dijamin',
                'kls.reportdisplay as namakelasdaftar',
                'pas.klsrawathak_nama as namakelas',
                'pd.objectstatuspulangfk',
                'ps.beratbadan',
                'rk.id as idrekanan',
                'ic.status as inacbg_status',
                'pd.inacbg_totaltarifrs',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_biayanaikkelas',
                'pas.statuscovid',
                'ps.noidentitas',
                'jk.jeniskelamin',
                'asa.inacbg_kode',
                'sp.inacbg_kode as discharge_status',
                'pd.nocmfk'

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile);
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', '=', $r['norec_pd']);
        }
        $data = $data->limit(1);
        $data = $data->get();

        $norec_APD = '';
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            try {
                $APD = AntrianPasienDiperiksa::where('noregistrasifk', $r['norec_pd'])
                    ->where('objectruanganfk', $data[0]->ruanganid)
                    ->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->first();
            } catch (Exception $ee) {
            }
        }
        $i = 0;
        $dtdt = '';

        $dataDiagnosa = DB::table('detaildiagnosapasien_t as dp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'dp.objectdiagnosafk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->select('dg.kddiagnosa', 'apd.objectasalrujukanfk', 'pd.norec')
            ->wherein('dp.objectjenisdiagnosafk', explode(',', $this->settingFix('jenisDiagnosaINACBG')))

            ->where('pd.kdprofile', $kdProfile)
            ->orderBy('dp.objectjenisdiagnosafk', 'asc');

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataDiagnosa = $dataDiagnosa->where('pd.norec',  $r['norec_pd']);
        }

        $dataDiagnosa = $dataDiagnosa->get();
        foreach ($data as $item) {
            $dtdt = '';
            $asalRujukan = '';
            $covid19_status_cd = '';
            foreach ($dataDiagnosa as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' .  $item2->kddiagnosa;
                    $asalRujukan = $item2->objectasalrujukanfk;
                }
            }
            $data[$i]->icd10 = substr($dtdt, 1, strlen($dtdt) - 1);
            $data[$i]->codernik = $codernik;
            $data[$i]->objectasalrujukanfk = $asalRujukan;
            $data[$i]->kodetarif = $kodetarif;


            $i = $i + 1;
        }

        $i = 0;
        $dtdt = '';
        $dataICD9 = DB::table('diagnosatindakanpasien_t as dpa')
            ->join('detaildiagnosatindakanpasien_t as dp', 'dpa.norec', '=', 'dp.objectdiagnosatindakanpasienfk')
            ->join('diagnosatindakan_m as dg', 'dg.id', '=', 'dp.objectdiagnosatindakanfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'dpa.objectpasienfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->select('dg.kddiagnosatindakan', 'pd.norec')
            ->where('pd.kdprofile', $kdProfile);

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $dataICD9 = $dataICD9->where('pd.norec',  $r['norec_pd']);
        }
        $dataICD9 = $dataICD9->get();
        foreach ($data as $item) {
            $data[$i]->jenis_rawat = 2;
            foreach ($kdDepartemenRawatInap as $kddept) {
                if ($kddept == $item->deptid) {
                    $data[$i]->jenis_rawat = 1;
                }
            }
            $dtdt = '';
            foreach ($dataICD9 as $item2) {
                if ($item2->norec == $data[$i]->norec) {
                    $dtdt = $dtdt . '#' . $item2->kddiagnosatindakan;
                }
            }
            $data[$i]->icd9 = substr($dtdt, 1, strlen($dtdt) - 1);
            $i = $i + 1;
        }

        $dariawal = '';
        $sampaiakhir = '';
        $noregs = '';
        $norms = '';
        $namas = '';
        $norec_pd = '';

        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_pd'] != "undefined") {
            $norec_pd = " and pd.norec='$r[norec_pd]'";
        }
        $dataTarif16Non = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                    case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal,pp.produkfk,pr.namaproduk
                    from pasiendaftar_t as pd
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                    INNER JOIN produk_m as pr on pr.id=pp.produkfk
                    LEFT JOIN kelompokprodukbpjs_m as kpb on pr.objectkelompokprodukbpjsfk  =kpb.id
                    left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                    where pd.statusenabled=true
                    and pd.kdprofile=$kdProfile
                    and pr.objectkelompokprodukbpjsfk is null
                    $dariawal
                    $sampaiakhir

                    $noregs
                    $namas
                    $norms

                    $norec_pd
                    group by pd.norec,kpb.namaexternal ,pp.produkfk,pr.namaproduk
                    order by pd.norec")
        );


        $dataTarif16 = DB::select(
            DB::raw("select pd.norec, sum(((pp.hargasatuan - case when pp.hargadiscount is null then 0 else pp.hargadiscount end) * pp.jumlah)+
                    case when pp.jasa is null then 0 else pp.jasa end) as ttl,kpb.namaexternal
                    from pasiendaftar_t as pd
                    inner join pasien_m as ps on ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                    INNER JOIN produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN kelompokprodukbpjs_m as kpb on kpb.id=pr.objectkelompokprodukbpjsfk
                    left join kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk
                    where pd.statusenabled=true
                    and pd.kdprofile=$kdProfile

                    $dariawal
                    $sampaiakhir
                    $noregs
                    $namas
                    $norms

                    $norec_pd
                    group by pd.norec,kpb.namaexternal
                    order by pd.norec")
        );
        $i = 0;
        $datatatat = array(
            'prosedur_non_bedah' => 0,
            'prosedur_bedah' => 0,
            'konsultasi' => 0,
            'tenaga_ahli' => 0,
            'keperawatan' => 0,
            'penunjang' => 0,
            'radiologi' => 0,
            'laboratorium' => 0,
            'pelayanan_darah' => 0,
            'rehabilitasi' => 0,
            'kamar' => 0,
            'rawat_intensif' => 0,
            'obat' => 0,
            'obat_kronis' => 0,
            'obat_kemoterapi' => 0,
            'alkes' => 0,
            'bmhp' => 0,
            'sewa_alat' => 0,
        );
        $keys = array_keys($datatatat);
        $new_claim = null;
        $set_claim_data = null;
        $grouper =  null;
        $delete_claim = null;
        foreach ($data as $item) {
            if (!empty($APD)) {
                if ($APD->noregistrasifk == $data[$i]->norec) {
                    $data[$i]->norec_apd = $APD->norec;
                }
            }
            $data[$i]->payor_id  = 3;
            $data[$i]->belum_mapping = [];
            $data[$i]->totalmappingtarif = 0;
            $data[$i]->totalbilling = 0;
            $data[$i]->umur_tahun = $this->getAgeYear($data[$i]->tgl_lahir, date('Y-m-d'));
            foreach ($dataTarif16 as $itm) {
                if ($itm->norec == $data[$i]->norec) {
                    foreach ($keys as $k) {
                        if ($itm->namaexternal == $k) {
                            $datatatat[$k] = (float)$itm->ttl;
                            $data[$i]->totalmappingtarif = $data[$i]->totalmappingtarif + (float)$itm->ttl;;
                            break;
                        }
                    }
                }
            }
            foreach ($dataTarif16Non as $itms) {
                if ($itms->norec == $data[$i]->norec) {
                    $data[$i]->totalbilling = $data[$i]->totalbilling + (float)$itms->ttl;;
                    $data[$i]->belum_mapping[] = $itms;
                }
            }

            $data[$i]->tarif_rs = $datatatat;
            $data[$i]->totalbilling = $data[$i]->totalbilling + $data[$i]->totalmappingtarif;
            $data[$i]->new_claim  = $this->new_claim($data[$i]);
            $data[$i]->set_claim_data  = $this->set_claim_data($data[$i], $depRI, $depIGD);
            $data[$i]->grouper  = $this->grouper($data[$i]);
            $data[$i]->delete_claim  = $this->delete_claim($data[$i]);
            $new_claim =   $data[$i]->new_claim;
            $set_claim_data =  $data[$i]->set_claim_data;
            $grouper =    $data[$i]->grouper;
            $delete_claim =   $data[$i]->delete_claim;

            $i = $i + 1;
        }

        // $result['total'] = count($data);

        $result['new_claim'] = $new_claim;
        $result['set_claim_data'] = $set_claim_data;
        $result['grouper'] = $grouper;
        $result['delete_claim'] = $delete_claim;
        $result['data'] = $data;
        return $this->respond($result);
    }

    public function claimPRINT(Request $request)
    {

        $data =  DB::table('pemakaianasuransi_t as pa')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'pa.noregistrasifk')
            ->select('pa.nosep')
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->where('pd.kdprofile', $this->kdProfile)
            ->first();

        if (empty($data)) {
            return null;
        }
        if ($data->nosep == null || $data->nosep == '') {
            return null;
        }

        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['data'] = [
            array(
                "metadata" => array(
                    "method" => "claim_print"
                ),
                "data" =>  array(
                    "nomor_sep" => $data->nosep
                )
            )
        ];
        $response =  $this->saveBridgingINACBG($objreqhis, true);
        if (isset($response['dataresponse'][0]['dataresponse']->metadata) && $response['dataresponse'][0]['dataresponse']->metadata->code == 200) {
            // $fileData = 'data:application/pdf;base64,'. $response['dataresponse'][0]['dataresponse']->data;
            $sourcePdf = $response['dataresponse'][0]['dataresponse']->data;
            //    return view('report.registrasi.preview-pdf', compact('sourcePdf'));

            $extension = 'pdf';
            $path = 'dokumen_klaim/' . $request['noregistrasi'];
            $filename = 'klaim_indi' . $request['noregistrasi'] . '.' . $extension;
            $fileData = base64_decode($sourcePdf);

            $request->fPut(
                $path . '/' . $filename,
                $fileData,
                'ftp'
            );
            return 'Ok';
        } else {
            return null;
        }
    }
    public function savePemakaianAsuransi(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save
            $kdProfile = $this->kdProfile;

            $cekPA =  PemakaianAsuransi::where('noregistrasifk', $r['norec_pd'])
                ->where('statusenabled', true)
                ->where('kdprofile', $kdProfile)
                ->first();
            if (empty($cekPA)) {
                $cek = AsuransiPasien::where('noasuransi', $r['nomor_kartu'])->where('kdprofile', $kdProfile)
                    ->where('statusenabled', true)
                    ->where('kdprofile', $kdProfile)
                    ->first();
                if (empty($cek)) {
                    $id = $this->SEQUENCE_MASTER(new AsuransiPasien, 'id', $kdProfile); // $this->Uuid4();
                    $model_AP = new AsuransiPasien();
                    $model_AP->id = $id;
                    $model_AP->norec = $model_AP->generateNewId();
                    $model_AP->kdprofile = $kdProfile;
                    $model_AP->statusenabled = true;
                } else {
                    $model_AP  = $cek;
                }
                $model_AP->kdpenjaminpasien = $r['kpid'];
                $model_AP->namapeserta =  $r['namapasien'];
                $model_AP->noasuransi =   $r['nomor_kartu'];
                $model_AP->nocmfk = $r['nocmfk'];
                $model_AP->kelompokpasienfk =  $r['kpid'];
                $model_AP->save();

                $model_PA = new PemakaianAsuransi();
                $model_PA->norec = $model_PA->generateNewId();
                $model_PA->kdprofile = $kdProfile;
                $model_PA->statusenabled = true;
            } else {
                $model_PA  = $cekPA;
            }
            $model_PA->noregistrasifk =  $r['norec_pd'];
            $model_PA->nosep = $r['nomor_sep'];
            $model_PA->nokartu = $r['nomor_kartu'];
            $model_PA->nomr = $r['nocm'];
            $model_PA->user = $this->getNamaPegawai();

            $model_PA->save();

            $pasien = Pasien::where('id', $r['nocmfk'])->first();
            if ($pasien->nobpjs == null) {
                $pasien->nobpjs = $r['nomor_kartu'];
                $pasien->save();
            }
            //endregion

            $this->LOGGING(
                'Pemakaian Asuransi',
                $model_PA->norec,
                'pemakaianasuransi',
                'Tambah No. SEP ' . $r['nomor_sep'] . '  dibuat MANUAL di INACBG DETAIL  tgl ' . date('Y-m-d') . ' pada Pasien ' .
                    $r['namapasien'] . ' (' .   $r['nocm'] . ') - ' . $r['noregistrasi']
            );

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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDaftarKlaim(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join(DB::raw('(SELECT * FROM antrianpasiendiperiksa_t AS apd1
                     WHERE tglmasuk = (SELECT MAX(tglmasuk)
                                       FROM antrianpasiendiperiksa_t AS apd2
                                       WHERE apd1.noregistrasi = apd2.noregistrasi
                                       AND apd1.objectruanganfk = apd2.objectruanganfk)
                   ) as apd'), function($join) {
                    $join->on('pd.noregistrasi', '=', 'apd.noregistrasi')
                        ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
                })
            ->leftJoin('kamar_m as kmr', 'kmr.id', 'apd.objectkamarfk')
            ->leftJoin('tempattidur_m as tmt', 'tmt.id', 'apd.nobed')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dep', 'dep.id', '=', 'ru.objectdepartemenfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('monitoringklaim_t as mk', 'mk.nosep', '=', 'pa.nosep')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->select(
                'ru.namaruangan',
                'ru.id as idruangan',
                'ps.nobpjs',
                'ps.namapasien',
                'ps.nocm',
                // 'pd.statuskoderincbgs',
                'ps.objectjeniskelaminfk',
                'ps.nohp',
                'al.alamatlengkap as alamatrmh',
                DB::raw("to_char(ps.tgllahir,'YYYY-MM-DD') as tgllahir"),
                'ps.nobpjs',
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'ic.status',
                DB::raw("to_char(pd.tglregistrasi,'YYYY-MM-DD HH:mm') as tglregistrasi"),
                'pa.nosep',
                'pa.flagprocedure_kode',
                'pa.tujuankun_kode',
                'pa.poli_kode',
                'pa.tglcreate',
                'asu.nmprovider',
                'jk.jeniskelamin',
                'klp.kelompokpasien',
                'kls.namakelas',
                'pg.namalengkap as dokter',
                'mk.status as ketKlaim',
                'pd.inacbg_totalgrouper',
                'pd.statuskelengkapandok',
                'pd.tglregistrasi as tgl_masuk',
                'pd.tglmeninggal',
                'pd.nocmfk',
                DB::raw("to_char(pd.tglpulang,'YYYY-MM-DD HH:mm') as tglpulang"),
                'pd.objectstatuspulangfk',
                'apd.tglmasuk',
                'kmr.namakamar',
                'tmt.reportdisplay as bed',
                'dep.id as id_dep'
            )
            ->whereIN('ic.inacbg_status', ['send_claim_individual'])
            ->groupBy(
                'ru.namaruangan',
                'ru.id',
                'ps.nobpjs',
                'ps.namapasien',
                'ps.nocm',
                'ps.objectjeniskelaminfk',
                'ps.nohp',
                'al.alamatlengkap',
                'ps.tgllahir',
                'ps.nobpjs',
                'pd.norec',
                'pd.noregistrasi',
                'ic.status',
                'pd.tglregistrasi',
                'pa.nosep',
                'pa.flagprocedure_kode',
                'pa.tujuankun_kode',
                'pa.poli_kode',
                'pa.tglcreate',
                'asu.nmprovider',
                'jk.jeniskelamin',
                'klp.kelompokpasien',
                'kls.namakelas',
                'pg.namalengkap',
                'mk.status',
                'pd.inacbg_totalgrouper',
                'pd.statuskelengkapandok',
                'pd.tglregistrasi',
                'pd.tglmeninggal',
                'pd.tglpulang',
                'pd.objectstatuspulangfk',
                'apd.tglmasuk',
                'kmr.namakamar',
                'tmt.reportdisplay',
                'dep.id'
            )->orderBy('pd.tglregistrasi');

        if (isset($request->ispulang) && $request->ispulang == true) {
            if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                $data = $data->where('pd.tglpulang', '>=', $request->tglawal . " 00:00");
            }
            if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                $data = $data->where('pd.tglpulang', '<=', $request->tglakhir . " 23:59");
            }
        } else {
            if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                $data = $data->where('pd.tglregistrasi', '>=', $request->tglawal . " 00:00");
            }
            if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                $data = $data->where('pd.tglregistrasi', '<=', $request->tglakhir . " 23:59");
            }
        }

        if (isset($request->search) && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->Where('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('pa.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
            });
        }

        if (isset($request->ruanganid) && $request->ruanganid != "" && $request->ruanganid != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', '=', $request->ruanganid);
        }
        if (isset($request->depid) && $request->depid != "" && $request->depid != "undefined") {
            $data = $data->where('dep.id', '=', $request->depid);
        }
        if (isset($request->norec_pd) && $request->norec_pd != "" && $request->norec_pd != "undefined") {
            $data = $data->where('pd.norec', '=', $request->norec_pd);
        }
        if (isset($request->verifikasi_koder) && $request->verifikasi_koder == "true") {
            $data = $data->where('pd.isverifikasikoder', true);
        }

        $data = $data->get();

        $dataulasan = DB::table("ulasanklaim_t")
            ->where('statusenabled', true);

        if (isset($request->ispulang) && $request->ispulang == true) {
            if (isset($request->tglAwal) && $request->tglAwal != "" && $request->tglAwal != "undefined") {
                $dataulasan = $dataulasan->where('tglpulang', '>=', $request->tglAwal);
            }
            if (isset($request->tglAkhir) && $request->tglAkhir != "" && $request->tglAkhir != "undefined") {
                $dataulasan = $dataulasan->where('tglpulang', '<=', $request->tglAkhir);
            }
        } else {
            if (isset($request->tglAwal) && $request->tglAwal != "" && $request->tglAwal != "undefined") {
                $dataulasan = $dataulasan->where('tglregistrasi', '>=', $request->tglAwal);
            }
            if (isset($request->tglAkhir) && $request->tglAkhir != "" && $request->tglAkhir != "undefined") {
                $dataulasan = $dataulasan->where('tglregistrasi', '<=', $request->tglAkhir);
            }
        }
        $dataulasan = $dataulasan->get();
        $arrDataUlasan = [];
        if ($dataulasan) {
            $arrDataUlasan = json_decode(json_encode($dataulasan), true);
        }

        $kdProfile = $this->kdProfile;

        $i = 0;
        foreach ($data as $item) {
            $norec_pd = $item->norec_pd;
            $filterUlasan = array_filter($arrDataUlasan, function ($val) use ($norec_pd) {
                return ($val['noregistrasifk'] == $norec_pd);
            });
            $filterUlasan = array_merge($filterUlasan);
            $ulasanBelumdibaca = array_filter($arrDataUlasan, function ($val) use ($norec_pd) {
                return ($val['noregistrasifk'] == $norec_pd && $val['isread'] == true && $val['isread'] == false);
            });
            $data[$i]->ulasan = "Tot Ulasan " . count($filterUlasan);

            $data[$i]->inacbg_totalgrouper = !empty($item->inacbg_totalgrouper) ? $item->inacbg_totalgrouper : "Belum Ada";

            $dataMaster = DB::table('dokumenklaim_m')
            ->where("statusenabled", true)
            ->where("kdprofile", $kdProfile)->where('objectdepartemenfk', '=', $item->id_dep)->orderBy('nourut')->get();
            foreach ($dataMaster as $itemXX) {

                if ($item->id_dep == $itemXX->objectdepartemenfk) {
                    $data[$i]->dokumen[] = array(
                        'name' => $itemXX->dokumen,
                        'kodeexternal' => $itemXX->kodeexternal,
                        'norec_pd' =>   $data[$i]->norec_pd,
                        'noregistrasi' =>   $data[$i]->noregistrasi,
                        'tglregistrasi' =>   $data[$i]->tgl_masuk,
                        'documentklaimfk' => $itemXX->id,
                        'api' => $itemXX->api,
                        'doc' => null
                    );
                }
            }

            $dataDokKlaim = DB::table("monitoringdokklaim_t")
            ->where("noregistrasifk", $item->norec_pd)
            ->where("kdprofile", $kdProfile)
            ->where("statusenabled", true);
            $dataDokKlaim = $dataDokKlaim->get();


            foreach ($dataDokKlaim as $datDok) {
                if ($datDok->noregistrasifk == $item->norec_pd) {
                    foreach ($data[$i]->dokumen as $dd => $vv) {
                        if ($datDok->documentklaimfk == $vv['documentklaimfk']) {
                            $data[$i]->dokumen[$dd]['doc'] = $datDok->filename;
                        }
                    }
                }
            }

            $tglLahir = new \DateTime($item->tgllahir);
            $today = new \DateTime("today");
            $y = $today->diff($tglLahir)->y;
            $m = $today->diff($tglLahir)->m;
            $d = $today->diff($tglLahir)->d;
            $umur = $y;

            $data[$i]->umur = $umur;

            $i++;
        }
        return $this->respond($data);
    }

    public function getMonitoringKlaim(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join(DB::raw('(SELECT * FROM antrianpasiendiperiksa_t AS apd1
                        WHERE tglkeluar = (SELECT MAX(tglkeluar)
                                        FROM antrianpasiendiperiksa_t AS apd2
                                        WHERE apd1.noregistrasi = apd2.noregistrasi
                                        AND apd1.objectruanganfk = apd2.objectruanganfk)
                    ) as apd'), function($join) {
                    $join->on('pd.noregistrasi', '=', 'apd.noregistrasi')
                        ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
                })
            ->leftJoin('kamar_m as kmr', 'kmr.id', 'apd.objectkamarfk')
            ->leftJoin('tempattidur_m as tmt', 'tmt.id', 'apd.nobed')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dep', 'dep.id', '=', 'ru.objectdepartemenfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('monitoringklaim_t as mk', 'mk.nosep', '=', 'pa.nosep')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('inacbg_status as ic', 'ic.inacbg_status', '=', 'pd.inacbg_status')
            ->select(
                'ru.namaruangan',
                'ru.id as idruangan',
                'ps.nobpjs',
                'ps.namapasien',
                'ps.nocm',
                'ps.objectjeniskelaminfk',
                'ps.nohp',
                'al.alamatlengkap as alamatrmh',
                DB::raw("to_char(ps.tgllahir,'YYYY-MM-DD') as tgllahir"),
                'ps.nobpjs',
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'ic.status',
                DB::raw("to_char(pd.tglregistrasi,'YYYY-MM-DD HH:mm') as tglregistrasi"),
                'pa.nosep',
                'pa.flagprocedure_kode',
                'pa.tujuankun_kode',
                'pa.poli_kode',
                'pa.tglcreate',
                'asu.nmprovider',
                'jk.jeniskelamin',
                'klp.kelompokpasien',
                'klp.id',
                'kls.namakelas',
                'pg.namalengkap as dokter',
                'mk.status as ketKlaim',
                'pd.inacbg_totalgrouper',
                'pd.statuskelengkapandok',
                'pd.tglmeninggal',
                'pd.nocmfk',
                DB::raw("to_char(pd.tglpulang,'YYYY-MM-DD HH:mm') as tglpulang"),
                'pd.objectstatuspulangfk',
                'apd.tglmasuk',
                'kmr.namakamar',
                'tmt.reportdisplay as bed',
                'dep.id as id_dep'
            )
            ->whereIn('klp.id', [2, 4])
            ->where('pa.statusenabled', true)
            ->where('pd.statusenabled', true)
            // ->groupBy(
            //     'ru.namaruangan',
            //     'ru.id',
            //     'ps.nobpjs',
            //     'ps.namapasien',
            //     'ps.nocm',
            //     'ps.objectjeniskelaminfk',
            //     'ps.nohp',
            //     'al.alamatlengkap',
            //     'ps.tgllahir',
            //     'ps.nobpjs',
            //     'pd.norec',
            //     'pd.noregistrasi',
            //     'ic.status',
            //     'pd.tglregistrasi',
            //     'pa.nosep',
            //     'pa.flagprocedure_kode',
            //     'pa.tujuankun_kode',
            //     'pa.poli_kode',
            //     'pa.tglcreate',
            //     'asu.nmprovider',
            //     'jk.jeniskelamin',
            //     'klp.kelompokpasien',
            //     'klp.id',
            //     'kls.namakelas',
            //     'pg.namalengkap',
            //     'mk.status',
            //     'pd.inacbg_totalgrouper',
            //     'pd.statuskelengkapandok',
            //     'pd.tglmeninggal',
            //     'pd.tglpulang',
            //     'pd.objectstatuspulangfk',
            //     'apd.tglmasuk',
            //     'kmr.namakamar',
            //     'tmt.reportdisplay',
            //     'dep.id'
            // )
            ->orderBy('pd.tglregistrasi');

        if (isset($request->ispulang) && $request->ispulang == true) {
            if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                $data = $data->where('pd.tglpulang', '>=', $request->tglawal . " 00:00");
            }
            if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                $data = $data->where('pd.tglpulang', '<=', $request->tglakhir . " 23:59");
            }
        } else {
            if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                $data = $data->where('pd.tglregistrasi', '>=', $request->tglawal . " 00:00");
            }
            if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                $data = $data->where('pd.tglregistrasi', '<=', $request->tglakhir . " 23:59");
            }
        }

        if (isset($request->search) && $request->search != '') {
            $searchTerm = '%' . $request->search . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->Where('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('pa.nosep', 'ilike', $searchTerm)
                    ->orWhere('ps.namapasien', 'ilike', $searchTerm);
            });
        }

        if (isset($request->ruanganid) && $request->ruanganid != "" && $request->ruanganid != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', '=', $request->ruanganid);
        }
        if (isset($request->depid) && $request->depid != "" && $request->depid != "undefined") {
            $data = $data->where('dep.id', '=', $request->depid);
        }

        if (isset($request['inacbg_status']) && $request['inacbg_status'] != "" && $request['inacbg_status'] != "undefined") {
            if ($request['inacbg_status'] == "belum_koding") {
                $data = $data->whereNull('pd.inacbg_status');
            } else {
                $data = $data->where('pd.inacbg_status', '=', $request['inacbg_status']);
            }
        }


        if (isset($request->norec_pd) && $request->norec_pd != "" && $request->norec_pd != "undefined") {
            $data = $data->where('pd.norec', '=', $request->norec_pd);
        }

        $data = $data->get();

        $i = 0;
        foreach ($data as $item) {
            $data[$i]->inacbg_totalgrouper = !empty($item->inacbg_totalgrouper) ? $item->inacbg_totalgrouper : "Belum Ada";

            $tglLahir = new \DateTime($item->tgllahir);
            $today = new \DateTime("today");
            $y = $today->diff($tglLahir)->y;
            $m = $today->diff($tglLahir)->m;
            $d = $today->diff($tglLahir)->d;
            $umur = $y;

            $data[$i]->umur = $umur;

            $i++;
        }
        return $this->respond($data);
    }

    public function dataHistoryKamar(Request $request)
    {
        $data = DB::table('antrianpasiendiperiksa_t as apd')
                ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                ->leftJoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                ->join('pemakaianasuransi_t as pa', 'pa.noregistrasifk', 'pd.norec')
                ->leftJoin('tempattidur_m as tmp', 'tmp.id', 'apd.nobed')
                ->leftJoin('inacbg_status as ic', 'ic.inacbg_status', 'pd.inacbg_status')
                ->select(
                    'pa.nosep',
                    'ru.namaruangan',
                    'tmp.reportdisplay as kamar',
                    'apd.tglmasuk',
                    'apd.tglkeluar',
                    'pd.tglpulang',
                    'ic.status'
                )
                ->where('pd.statusenabled', true)
                ->where('ru.objectdepartemenfk', 16);

                if (isset($request->ispulang) && $request->ispulang == true) {
                    if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                        $data = $data->where('pd.tglpulang', '>=', $request->tglawal . " 00:00");
                    }
                    if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                        $data = $data->where('pd.tglpulang', '<=', $request->tglakhir . " 23:59");
                    }
                } else {
                    if (isset($request->tglawal) && $request->tglawal != "" && $request->tglawal != "undefined") {
                        $data = $data->where('pd.tglregistrasi', '>=', $request->tglawal . " 00:00");
                    }
                    if (isset($request->tglakhir) && $request->tglakhir != "" && $request->tglakhir != "undefined") {
                        $data = $data->where('pd.tglregistrasi', '<=', $request->tglakhir . " 23:59");
                    }
                }

                if (isset($request->ruanganid) && $request->ruanganid != "" && $request->ruanganid != "undefined") {
                    $data = $data->where('pd.objectruanganlastfk', '=', $request->ruanganid);
                }
                if (isset($request->depid) && $request->depid != "" && $request->depid != "undefined") {
                    $data = $data->where('dep.id', '=', $request->depid);
                }

                if (isset($request['inacbg_status']) && $request['inacbg_status'] != "" && $request['inacbg_status'] != "undefined") {
                    if ($request['inacbg_status'] == "belum_koding") {
                        $data = $data->whereNull('pd.inacbg_status');
                    } else {
                        $data = $data->where('pd.inacbg_status', '=', $request['inacbg_status']);
                    }
                }

        $data = $data->orderBy('nosep')->orderBy('tglmasuk')->get();
        return $this->respond($data);
    }

    public function getDaftarKlaimHasilLab(Request $request)
    {
        // hasil bridging
        $kdProfile = $this->kdProfile;
        $dataOrder = DB::table('strukorder_t')
            ->select('noorder', 'tglorder', 'noregistrasifk')
            ->where('noregistrasifk', $request['norec_pd'])
            ->where('keteranganorder', 'Order Laboratorium')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->get();

        if (count($dataOrder) == 0) {
            $res['data'] = [];
            return $this->respond($res);
        }

        $noorder = [];
        foreach ($dataOrder as $item) {
            array_push($noorder, $item->noorder);
        }
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));
        $dataBrid = DB::select(DB::raw("SELECT hl.nama_pemeriksaan as namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,
        hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.user_validasi, hl.tgl_hasil, hl.metode, so.noorder
        FROM lab_hasil hl
        INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        WHERE so.noorder IN ($placeholders)"), $noorder);

        $result = [];
        foreach ($dataOrder as $key => $item) {
            $item->hasil = [];
            foreach ($dataBrid as $item2) {
                if ($item->noorder == $item2->noorder) {
                    $item->hasil[] = [
                        'namaproduk' => $item2->namaproduk,
                        'detailpemeriksaan' => $item2->detailpemeriksaan,
                        'hasil' => $item2->hasil,
                        'flag' => $item2->flag,
                        'nilaitext' => $item2->nilaitext,
                        'satuanstandar' => $item2->satuanstandar,
                        'analis' => $item2->user_validasi,
                        'tglhasil' => $item2->tgl_hasil,
                        'metode' => $item2->metode,
                    ];
                }
            }
            // masukin data yg ada hasilnya aja
            if (count($item->hasil) > 0) {
                array_push($result, $item);
            }
        }

        $res['data'] = $result;
        return $this->respond($res);
    }

    public function getDaftarKlaimHasilRad(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('hasilradiologi_t as hr')
            ->join('pelayananpasien_t as pp', 'hr.pelayananpasienfk', '=', 'pp.norec')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'hr.pegawaifk')
            ->join('produk_m AS pr', 'pr.id', '=', 'pp.produkfk')
            ->select('hr.keterangan', 'hr.tanggalreport', 'so.noorder', 'pr.namaproduk', 'pg.namalengkap as dokterrab')
            ->where('so.keteranganorder', 'Order Radiologi')
            ->where('pd.norec', $request['norec_pd'])
            ->distinct()
            ->orderby('hr.tanggalreport', 'asc')
            ->get();

        if ($result) {
            $result = json_decode(json_encode($result), true);
        }
        $i = 0;
        foreach ($result as $item) {
            $result[$i]['id'] = $i + 1;
            $result[$i]['keterangan'] = nl2br(str_replace('~', '<br />', $item['keterangan']));
            $i++;
        }
        return $this->respond($result);
    }

    public function getDaftarKlaimEMR(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $EMR_FORM = DB::connection('mongodb')
            ->table('#ResumeEMR')
            ->where('kdprofile', $kdProfile)
            ->where('noregistrasifk', $request['norec_pd'])
            ->where('table', '!=', 'VitalSign')
            ->where('table', '!=', 'resumeMedis')
            ->where('statusenabled', true)
            ->orderByDesc('last_update')
            ->get();

        $emrpasien = DB::table("emrpasien_t")
            ->where('noregistrasifk', $request['norec_pd'])
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();

        $result = [];
        foreach ($EMR_FORM as $item) {
            // dd($item);
            $item["norec_apd"] = '';
            $item["noregistrasi"] = '';
            foreach ($emrpasien as $item2) {
                if ($item2->norec == $item["emrpasienfk"]) {
                    $item["norec_apd"] = $item2->norec_apd;
                    $item["noregistrasi"] = $item2->noregistrasi;
                }
            }
            array_push($result, $item);
        }

        return $this->respond($result);
    }

    public function saveUlasan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {

            $data = DB::table("pasiendaftar_t")
                ->where('noregistrasi', $request->noregistrasi)
                ->first();

            $ulasanKlaim = new UlasanKlaim;
            $ulasanKlaim->norec = $ulasanKlaim->generateNewId();
            $ulasanKlaim->kdprofile = $kdProfile;
            $ulasanKlaim->statusenabled = true;
            $ulasanKlaim->noregistrasifk = $data->norec;
            $ulasanKlaim->tglregistrasi = $data->tglregistrasi;
            $ulasanKlaim->tglpulang = $data->tglpulang;
            $ulasanKlaim->ulasan = $request->ulasan;
            $ulasanKlaim->namapetugas = $request->namapetugas;
            $ulasanKlaim->isbpjs = $request->isbpjs;
            $ulasanKlaim->isread = false;
            $ulasanKlaim->tglulasan = date('Y-m-d H:i:s');
            $ulasanKlaim->save();

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $ulasanKlaim,
                "message" => "Sukses"
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
                "message" => "Gagal",

            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function loadUlasan(Request $request)
    {
        $result = DB::table('ulasanklaim_t')
            ->where('statusenabled', true)
            ->where('noregistrasifk', $request->noregistrasifk)
            ->orderby('created_at', 'ASC')
            ->get();

        $update = UlasanKlaim::where('noregistrasifk', $request->noregistrasifk)
            ->where('isbpjs', '!=', $request->isbpjs)
            ->update(['isread' => true]);

        $res['data'] = $result;
        return $this->respond($result);
    }

    public function getBerkasbyNoCM(Request $request)
    {
        $nocm = $request->query('nocm');

        if ($nocm) {
            $berkas = DB::table('emrdokumen_t')
                ->where('nocm', $nocm)
                ->get(); // ambil semua kolom

            return response()->json([
                'metaData' => [
                    'code' => 200,
                    'message' => 'OK'
                ],
                'response' => [
                    'data' => $berkas
                ]
            ]);
        }

        return response()->json([
            'metaData' => [
                'code' => 400,
                'message' => 'Parameter nocm tidak ditemukan'
            ],
            'response' => null
        ], 400);
    }
}
