<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\SettingDataFixed;
use App\Traits\Valet;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;
use Exception;

class BridgingBPJSCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    function getSetting()
    {
        $res = [
            'BPJS_urlAplicare' => '',
            'BPJS_urlAntrol' => '',
            'BPJS_userKeyAntrol' => '',
            'BPJS_urlVclaim' => '',
            'BPJS_ConsID' => '',
            'BPJS_ConsPassword' => '',
            'BPJS_userKeyVclaim' => '',
            'BPJS_EnabledDummyRegistrasi' => '',
            'BPJS_EnabledKioskDummy' => '',
            'BPJS_namaPPKRujukan' => '',
            'BPJS_kodePPKRujukan' => ''
        ];

        $data = DB::table('settingdatafixed_m')->where('kdprofile', session('kdProfile'))
            ->select('namafield', 'nilaifield')
            ->where('statusenabled', true)
            ->whereIn('namafield', [
                'BPJS_urlAplicare',
                'BPJS_urlAntrol',
                'BPJS_userKeyAntrol',

                'BPJS_urlVclaim',
                'BPJS_ConsID',
                'BPJS_ConsPassword',
                'BPJS_userKeyVclaim',

                'BPJS_EnabledDummyRegistrasi',
                'BPJS_EnabledKioskDummy',
                'BPJS_namaPPKRujukan',
                'BPJS_kodePPKRujukan'
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
    function getHeaderBPJSV2()
    {
        $set = $this->getSetting();

        $data = $set['BPJS_ConsID'];

        $secretKey = $set['BPJS_ConsPassword'];
        date_default_timezone_set('UTC');
        $tStamp = strval(time() - strtotime('1970-01-01 00:00:00'));
        $signature = hash_hmac('sha256', $data . "&" . $tStamp, $secretKey, true);

        $encodedSignature = base64_encode($signature);

        $header = array(
            "Content-Type" => "Application/x-www-form-urlencoded",
            "X-cons-id" => $data,
            "X-signature" => $encodedSignature,
            "X-timestamp" => $tStamp,
            "user_key" => $set['BPJS_userKeyVclaim']

        );

        return $header;
    }
    public function bpjsTools(Request $request, $local = false)
    {
        try {
            $headers = $this->getHeaderBPJSV2();
            $set = $this->getSetting();
            $dataJsonSend = null;

            if ($request['data'] != null) {
                $dataJsonSend = $request['data']; //json_encode($request['data']);
            }

            $methods = $request['method'];
            $baseURL = $set['BPJS_urlVclaim'];
            $keyDecrypt = $set['BPJS_ConsID'] . $set['BPJS_ConsPassword'] . $headers['X-timestamp'];

            if (isset($request['jenis']) && $request['jenis'] == 'antrean') {
                $baseURL = $set['BPJS_urlAntrol'];
                $headers['user_key'] =  $set['BPJS_userKeyAntrol'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'i-care') {
                $baseURL = $set['BPJS_urlVclaim'];
                $baseURL =  str_replace('vclaim-rest/', 'wsihs/', $baseURL);
                $headers['Content-Type']  = 'application/json';
                $headers['user_key'] =  $set['BPJS_userKeyVclaim'];
            }
            if (isset($request['jenis']) && $request['jenis'] == 'aplicare') {
                $baseURL = $set['BPJS_urlVclaim'];
                $baseURL =  str_replace('vclaim-rest/', 'aplicaresws/', $baseURL);

                $headers['Content-Type']  = 'application/json';
                $headers['user_key'] =  $set['BPJS_userKeyVclaim'];
            }
            $methods = strtolower($methods);
            $url = $baseURL . $request['url'];

            if (empty($dataJsonSend)) {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url);
            } else {
                $response = Http::withHeaders($headers)
                    ->withoutVerifying()
                    ->withOptions(["verify" => false])
                    ->{$methods}($url, $dataJsonSend);
            }
            // $cekData = array(
            //     'request' => array(
            //         'url' => $url,
            //         'headers' =>  $headers,
            //         'payload' => $dataJsonSend,
            //     ), 'response' =>  $response->json()
            // );
            // return $cekData;
            if ($response->ok()) {
                $metadata = isset($response->json()['metadata']) ? $response->json()['metadata'] : $response->json()['metaData'];
                if ($metadata['code'] != 200 && $metadata['code'] != 1) {
                    if ($metadata['code'] == 0) {
                        $metadata['code'] = 201;
                    }
                    $result = $metadata;
                    return $this->respond($result,  $metadata['code'], $metadata['message']);
                }
                if (!isset($response->json()['response'])) {
                    if ($metadata['code'] = 1) {
                        $metadata['code'] = 200;
                    }
                    $result = null;
                    return $this->respond($result,  $metadata['code'], $metadata['message']);
                }
                $bahan = $response->json()['response'];

                try {
                    $encrypt_method = 'AES-256-CBC';
                    $key_hash = hex2bin(hash('sha256', $keyDecrypt));
                    $iv = substr(hex2bin(hash('sha256', $keyDecrypt)), 0, 16);
                    $output = openssl_decrypt(base64_decode($bahan), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
                    $x = \LZCompressor\LZString::decompressFromEncodedURIComponent($output);
                    $result = json_decode($x);
                } catch (Exception $e) {
                    $result = $bahan;
                }

                if ($metadata['code'] == 1) {
                    $metadata['code'] = 200;
                }
                if ($local) {
                    return array(
                        'metaData' => $metadata,
                        'response' => $result,
                    );
                }
                return $this->respond($result, (int)$metadata['code'] == 500 ? 201 : (int)$metadata['code'], $metadata['message']);
            } else {
                $metadata['code'] = 201;
                $metadata['message'] = 'Response Dari BPJS : ' . json_encode($response);
                if ($local) {
                    return array(
                        'metaData' => $metadata,
                        'response' => $metadata,
                    );
                }
                return $this->respond(null,  $metadata['code'], $metadata['message']);
            }
        } catch (\Exception $e) {
            $metadata['code'] = 201;
            $metadata['message'] = $e->getMessage() . ' ' . $e->getLine();

            if ($local) {
                return array(
                    'metaData' => $metadata,
                    'response' => $metadata,
                );
            }
            return $this->respond(null,  $metadata['code'], $metadata['message']);
        }
    }

    public function getNoRujukanPcareNoKartu(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "Rujukan/" . $request['nokartu'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoRujukanRs(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "Rujukan/RS/" . $request['nokartu'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoPeserta(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "Peserta/nokartu/" . $request['nokartu'] . "/tglSEP/" . $request['tglsep'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getMonitoringHistori($noKartu)
    {
        // $tglMulai = Carbon::now()->subMonth(3)->format('Y-m-d');
        $tglMulai = date('Y-m-d', strtotime("-90 days"));
        $tglAkhir = Carbon::now()->format('Y-m-d');

        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] = "monitoring/HistoriPelayanan/NoKartu/" . $noKartu . "/tglAwal/" . $tglMulai . "/tglAkhir/" . $tglAkhir;
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoRujukanPcare(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] =  "Rujukan/" . $request['norujukan'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getNoRujukanRsNoKartu(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] =  "Rujukan/RS/Peserta/" . $request['nokartu'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getDokterDPJP(Request $request)
    {
        $objreqhis = new \Illuminate\Http\Request();
        $objreqhis['url'] =   "referensi/dokter/pelayanan/" . $request['jenisPelayanan'] . "/tglPelayanan/"
            . $request['tglPelayanan'] . "/Spesialis/" . $request['kodeSpesialis'];
        $objreqhis['method'] = "GET";
        $objreqhis['data'] = null;
        $response =  $this->bpjsTools($objreqhis, true);

        return $this->respond($response);
    }

    public function getListPemakaianAsuransi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglawal = $request['tgl'] . " 00:00";
        $tglakhir = $request['tgl'] . " 23:59";
        $data = DB::table("pemakaianasuransi_t as pa")
            ->select("pa.nosep", "pa.nmprovider")
            ->join("pasiendaftar_t as pd", "pd.norec", "=", "pa.noregistrasifk")
            ->where("pd.statusenabled", true)
            ->where("pd.kdprofile", (int)$kdProfile)
            ->whereBetween("pd.tglregistrasi", [$tglawal, $tglakhir])
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'hs@epic',
        );
        return $this->respond($result);
    }

    public function saveMonitoringKlaim(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::table('monitoringklaim_t')
            ->where('jenispelayanan', $request['jenispelayanan'])
            ->where('statusklaimfk', $request['statusklaimfk'])
            ->whereRaw("to_char(tglpulang,'yyyy-MM') ='$request[bulan]'")
            ->delete();

        $dataInsert = [];
        $newData2 = $request['details'];
        foreach ($newData2 as $item) {
            $dataInsert[] = array(
                'norec' => substr(Uuid::generate(), 0, 32),
                'kdprofile' => $kdProfile,
                'statusenabled' => true,
                'nofpk' => $item['nofpk'],
                'tglpulang' => $item['tglpulang'],
                'jenispelayanan' => $request['jenispelayanan'],
                'nosep' => $item['nosep'],
                'tglsep' => $item['tglsep'],
                'status' => $item['status'],
                'totalpengajuan' => $item['totalpengajuan'],
                'totalsetujui' => $item['totalsetujui'],
                'totaltarifrs' => $item['totaltarifrs'],
                'statusklaimfk' => $request['statusklaimfk'],
                'totalgrouper' => $item['totalgrouper'],
            );


            if (count($dataInsert) > 100) {
                DB::table('monitoringklaim_t')->insert($dataInsert);
                $dataInsert = [];
            }
        }

        DB::table('monitoringklaim_t')->insert($dataInsert);

        $result = array(
            'data3' => $dataInsert,
            'message' => 'er@epic',
            'messages' => 'Sukses',
        );

        return $this->respond($result);
    }
    public function getDaftarMappingDokterBpjsToDokterRs(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $kdProfile = $this->kdProfile;
        $data = DB::table('pegawai_m as pg')
            ->selectRaw("
                    pg.id,pg.kddokterbpjs,pg.namalengkap
                ")
            ->where('pg.kdprofile', $kdProfile)
            ->where('pg.statusenabled', true)
            ->where('pg.objectjenispegawaifk', 1)
            ->wherenotNull('pg.kddokterbpjs');

        if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['idPegawai']);
        }

        if (isset($request['kodeDokterBpjs']) && $request['kodeDokterBpjs'] != "" && $request['kodeDokterBpjs'] != "undefined") {
            $data = $data->where('pg.kddokterbpjs', '=', $request['kodeDokterBpjs']);
        } else {
            if (isset($request['dokterbpjsArr']) && $request['dokterbpjsArr'] != "" && $request['dokterbpjsArr'] != "undefined") {
                $arrRuang = explode(',', $request['dokterbpjsArr']);
                $kodeRuang = [];
                foreach ($arrRuang as $item) {
                    $kodeRuang[] = (int)$item;
                }
                $data = $data->whereIn('pg.kddokterbpjs', $kodeRuang);
            }
        }
        $data = $data->orderBy('pg.namalengkap', 'asc');
        $count = $data->count();
        $data = $data->when($limit, function ($query) use ($limit) {
            return $query->limit($limit);
        });
        $data = $data->when($offset, function ($query) use ($offset) {
            return $query->offset($offset);
        });
        $result = [
            'data' => $data->get(),
            'total' => $count
        ];
        return $this->respond($result);
    }
    public function saveMappingDokterBpjsDokterRs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Pegawai::where('id', $request['idpegawai'])
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = $request['kodedokterbpjs'];
            $data->kddokterbpjs = $request['kodedokterbpjs'];
            $data->save();
            $this->LOGGING(
                'Mapping Dokter Bpjs To Dokter Rs',
                $data->norec ?? null,
                'pegawai_m',
                'Mapping Dokter Bpjs To Dokter Rs ' . $data->namalengkap ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Gagal" . $e->getMessage() . $e->getLine();
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "as" => 'ea@epic',
                "result" => null
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
    public function saveHapusMappingDokterBpjsDokterRs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = Pegawai::where('id', $request['id'])
                ->where('kdprofile', $kdProfile)->first();
            $data->kodeexternal = "V3-RSGJ";
            $data->kddokterbpjs = null;
            $this->LOGGING(
                'Hapus Mapping Dokter Bpjs To Dokter Rs',
                $data->norec ?? null,
                'pegawai_m',
                'Hapus Mapping Dokter Bpjs To Dokter Rs ' . $data->namalengkap ?? null
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Gagal" . $e->getMessage() . $e->getLine();
        }

        if ($transStatus == 'true') {
            $transMessage = "Hapus Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $transMessage,
                "result" => $data,
                "as" => 'ea@epic',
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $transMessage,
                "result" => null,
                "as" => 'ea@epic',
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function updateAplicaresBedAfter(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $kodeppk =  $this->settingFix('BPJS_kodePPKRujukan', $kdProfile);

        $data = collect(DB::select("
            select x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang,sum(x.tersedia) as tersedia,sum(x.tersediapria) as tersediapria,
            sum(x.tersediawanita) as tersediawanita,sum(x.tersediapriawanita) as tersediapriawanita,
            sum(x.kapasitas) as kapasitas
                from (SELECT
                        CASE WHEN kmr.kodeexternal IS NOT NULL THEN kmr.kodeexternal ELSE kl.namaexternal END AS kodekelas,
                        ru.ID AS koderuang,
                        CASE WHEN 	kmr.kodeexternal IS NOT NULL THEN  kmr.kodeexternal ELSE kl.namakelas END AS namakelas,
                        ru.namaruangan AS namaruang,
                        0 AS tersediapria,
                        0 AS tersediawanita,
                        0 AS tersediapriawanita,
                        COUNT ( tt.ID ) AS kapasitas,
                        SUM ( CASE WHEN sb.ID = 2 THEN 1 ELSE 0 END ) AS tersedia
                        FROM tempattidur_m AS tt
                        INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
                        INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
                        INNER JOIN kelas_m AS kl ON kl. ID = kmr.objectkelasfk
                        INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
                        WHERE tt.statusenabled = TRUE
                        AND kmr.statusenabled = TRUE
                        AND ru.id=" . $request->idruangan . "
                            AND kl.id=" . $request->idkelas . "
                        GROUP BY
                        kl.namakelas,ru.id,kl.namaexternal,
                        ru.namaruangan, kmr.kodeexternal

            ) as x group by  x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang
            "))->first();

        if (!empty($data)) {
            $json = array(
                "kodekelas" => $data->kodekelas,
                "koderuang" => $data->koderuang,
                "namaruang" => $data->namaruang,
                "kapasitas" => $data->kapasitas,
                "tersedia" => $data->tersedia,
                "tersediapria" => $data->tersediapria,
                "tersediawanita" => $data->tersediawanita,
                "tersediapriawanita" => $data->tersediapriawanita
            );

            $objreqhis = new \Illuminate\Http\Request();
            $objreqhis['url'] =   "rest/bed/update/" . $kodeppk;
            $objreqhis['method'] = "POST";
            $objreqhis['jenis'] = "aplicare";
            $objreqhis['data'] = $json;
            $response =  $this->bpjsTools($objreqhis, true);

            return $this->respond($response);
        }
    }

    public function getKamarRS(Request $request)
    {

        $kdProfile = $this->kdProfile;
        $kodeppk =  $this->settingFix('BPJS_kodePPKRujukan', $kdProfile);

        $paramRuangan = '';
        if (isset($request['namaruangan']) && $request['namaruangan'] != 'undefined' && $request['namaruangan'] != '') {
            $paramRuangan = " and ru.namaruangan ilike '%" . $request['namaruangan'] . "%'";
        }
        $paramKelas = '';
        if (isset($request['kelas']) && $request['kelas'] != 'undefined' && $request['kelas'] != '') {
            $paramKelas = " and kl.namakelas ilike '%" . $request['kelas'] . "%'";
        }

        $data = DB::select(DB::raw("
            select x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang,sum(x.tersedia) as tersedia,sum(x.tersediapria) as tersediapria,
            sum(x.tersediawanita) as tersediawanita,sum(x.tersediapriawanita) as tersediapriawanita,
            sum(x.kapasitas) as kapasitas

            from (SELECT
                    CASE WHEN kmr.kodeexternal IS NOT NULL THEN kmr.kodeexternal ELSE kl.namaexternal END AS kodekelas,
                    ru.ID AS koderuang,
                    CASE WHEN 	kmr.kodeexternal IS NOT NULL THEN  kmr.kodeexternal ELSE kl.namakelas END AS namakelas,
                    ru.namaruangan AS namaruang,
                    0 AS tersediapria,
                    0 AS tersediawanita,
                    0 AS tersediapriawanita,
                    COUNT ( tt.ID ) AS kapasitas,
                    SUM ( CASE WHEN sb.ID = 2 THEN 1 ELSE 0 END ) AS tersedia
                        FROM tempattidur_m AS tt
                        INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
                        INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
                        INNER JOIN kelas_m AS kl ON kl. ID = kmr.objectkelasfk
                        INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
                        WHERE tt.statusenabled = TRUE
                        AND kmr.statusenabled = TRUE
                        $paramRuangan
                        $paramKelas
                        GROUP BY
                        kl.namakelas,ru.id,kl.namaexternal,
                        ru.namaruangan, kmr.kodeexternal

            ) as x group by  x.kodekelas,
            x.namakelas,x.koderuang,x.namaruang
    "));

    $res = [
        'data' => $data,
        'setting' => $kodeppk
    ];

        return $this->respond($res);
    }
}


