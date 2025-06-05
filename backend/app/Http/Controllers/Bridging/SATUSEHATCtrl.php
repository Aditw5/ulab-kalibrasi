<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Produk;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\DetailDiagnosaPasien;
use App\Models\Transaksi\DetailDiagnosaTindakanPasien;
use App\Models\Transaksi\HasilLaboratorium;
use App\Models\Transaksi\IHS_Transaction;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PasienPerjanjian;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StokProdukDetail;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Webpatser\Uuid\Uuid;

class SATUSEHATCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getList(Request $request)
    {

        // $url = 'http://10.20.30.40/service/medifirst2000/bridging/ihs/get-list?dari=' . $request['dari'] . '&sampai=' . $request['sampai']
        //     . '&resourcetype=' . $request['resourcetype'];

        // $response = Http::withHeaders(['X-AUTH-TOKEN' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMucmVnaXN0cmFzaSJ9.lUrRwlKGe3pGNfCTvbygULw-49KqLBbNaY5UzbvZX58t0KOQbRmVygRuazVEgEQ_NRlQnkzuhthvDR1LFVxFkA'])
        //     ->get($url);


        // $res = null;
        // if ($response->ok()) {
        //     $res = $response->json();
        // }
        // return $this->respond($res);

        $data = DB::table('ihs_transaction')
            ->select('*')
            ->where('resourcetype', '=', $request['resourcetype'])
            ->where('statusenabled', true);
        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where('date', '>=', $request['dari'] . ' 00:00:00');
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where('date', '<=', $request['sampai'] . ' 23:59:59');
        }
        $data = $data->orderby('date', 'desc');
        $data = $data->get();

        $data2 = [];
        foreach ($data as $d) {

            if ($d->resourcetype == 'Bundle') {
                $daaa = array(
                    'request' => json_decode($d->body),
                    'response' => json_decode($d->response),
                );
                $data2[] =  $daaa;
            } else {
                $daaa = json_decode($d->response);
            }

            if (isset($daaa->resourceType) && $daaa->resourceType != 'OperationOutcome') {
                $data2[] =  $daaa;
            }
        }
        return $this->respond($data2);
        // return response()->json($data2);
    }
    function getCredentialz()
    {
        $res['client_id'] = $this->settingFix('client_id_IHS', $this->kdProfile);
        $res['client_secret'] = $this->settingFix('client_secret_IHS', $this->kdProfile);
        $res['org'] = $this->settingFix('organization_id_IHS', $this->kdProfile);
        return $res;
    }
    function endPoint()
    {
        return $this->settingFix('base_url_IHS', $this->kdProfile);
    }
    function endPointKFAV2()
    {
        return $this->settingFix('base_url_IHS_KFA', $this->kdProfile);
    }
    public function generateToken($lokal = null)
    {
        $url = $this->settingFix('auth_url_IHS', $this->kdProfile);
        $headers = array(
            'Content-Type:application/x-www-form-urlencoded',
        );
        $cred = $this->getCredentialz();
        $client_id = $cred['client_id'];
        $client_secret = $cred['client_secret'];

        $dataJsonSend =   'client_id=' . $client_id . '&client_secret=' . $client_secret;
        $methods = 'POST';
        $url = $url . "?grant_type=client_credentials";
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $methods,
            CURLOPT_POSTFIELDS => $dataJsonSend,
            CURLOPT_HTTPHEADER => $headers
        ));
        $response = curl_exec($curl);
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpcode == 200) {
            $response =  json_decode($response);
        } else {
            $rss =  json_decode($response);
            if (isset($rss->resourceType)) {
                $response = $rss;
            } else {
                $response = array(
                    "result" => $response,
                    "body" => $dataJsonSend,
                    "url" => $url,
                    "method" => $methods,
                );
            }
        }

        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function getHeader()
    {
        try {
            // $date_now = session('satusehat_date');
            // $seconds = session("satusehat_exp");
            // $date = date("Y-m-d H:i:s", (strtotime(date($date_now)) + $seconds));

            // if (!empty(session("satusehat_token")) && $date > date('Y-m-d H:i:s')) {

            //     $token = session("satusehat_token");
            // } else {
            $datas = $this->generateToken(true);


            $token = $datas->access_token;

            //     $expires_in = $datas->expires_in;

            //     session(["satusehat_token" => $token]);
            //     session(["satusehat_date" => date('Y-m-d H:i:s')]);
            //     session(["satusehat_exp" => $expires_in]);
            // }
        } catch (Exception $e) {

            $token2 = $this->generateToken(true);
            \Log::info("TOKEN SATUSEHAT: " . json_encode($token2));
            $token = '';
        }

        $header = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        );

        return $header;
    }
    public function ihsTools(Request $request, $lokal = null)
    {
        try {
            $headers = $this->getHeader();
            $dataJsonSend = null;
            if ($request['data'] != null) {
                $dataJsonSend = $request['data'];
            }
            $methods = $request['method'];
            $url = $this->endPoint() . $request['url'];
            if (isset($request['jenis']) && $request['jenis'] == 'kfa' ) {
                $url = $this->endPointKFAV2() . $request['url'];
            }

            $response = $this->curlAPI($headers, $dataJsonSend, $url, $methods);

            $response =  $response->json();
            $response = json_decode(json_encode($response)); //Turn it into an object

        } catch (Exception  $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
            $response = json_decode(json_encode($response)); //Turn it into an object
        }

        if ($lokal) {
            return $response;
        }

        return response()->json($response);
    }
    public function Organization(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $q =  collect(DB::select("
        SELECT
        kd.*,
        pr.ihs_id AS ihs_profile,
        pr.ihs_province,
        pr.ihs_city,
        pr.ihs_district,
        pr.ihs_village,
        pr.kodepos,
        pr.alamatlengkap,
        pr.namakota,
        pr.alamatemail,
        pr.website,
        pr.fixedphone ,
        pr.namalengkap as namaprofile
    FROM
        departemen_m AS kd
        LEFT JOIN profile_m AS pr ON pr.ID = kd.kdprofile
    WHERE
        kd.kdprofile = $kdProfile
        AND kd.statusenabled = true
        AND kd.ID = $request[id_dept]"))->first();


        $data =  array(
            'resourceType' => 'Organization',
            'active' => $q->statusenabled,
            'identifier' =>
            array(
                0 =>
                array(
                    'use' => 'official',
                    'system' => 'http://sys-ids.kemkes.go.id/organization/' . $q->ihs_profile,
                    'value' => (string) $q->id,
                ),
            ),
            'type' =>
            array(
                0 =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://terminology.hl7.org/CodeSystem/organization-type',
                            'code' => 'dept',
                            'display' => 'Hospital Department',
                        ),
                    ),
                ),
            ),
            'name' => $q->namadepartemen,
            'telecom' =>
            array(
                [
                    'system' => 'phone',
                    'value' => $q->fixedphone,
                    'use' => 'work',
                ],
                [
                    'system' => 'email',
                    'value' => $q->alamatemail,
                    'use' => 'work',
                ],
                [
                    'system' => 'url',
                    'value' =>  $q->website,
                    'use' => 'work',
                ]
            ),
            'address' =>
            array(
                0 =>
                array(
                    'use' => 'work',
                    'type' => 'both',
                    'line' =>
                    array(
                        0 => $q->alamatlengkap,
                    ),
                    'city' => $q->namakota,
                    'postalCode' => $q->kodepos,
                    'country' => 'ID',
                    'extension' =>
                    array(
                        0 =>
                        array(
                            'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/administrativeCode',
                            'extension' =>
                            array(
                                0 =>
                                array(
                                    'url' => 'province',
                                    'valueCode' =>  $q->ihs_province,
                                ),
                                1 =>
                                array(
                                    'url' => 'city',
                                    'valueCode' =>  $q->ihs_city,
                                ),
                                2 =>
                                array(
                                    'url' => 'district',
                                    'valueCode' =>  $q->ihs_district,
                                ),
                                3 =>
                                array(
                                    'url' => 'village',
                                    'valueCode' =>  $q->ihs_village,
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'partOf' =>
            array(
                'reference' => 'Organization/' . $q->ihs_profile,
                'display' => $q->namaprofile,
            ),
        );

        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = $q->ihs_id == null ? $data['resourceType'] : $data['resourceType'] . '/' . $q->ihs_id;
        $objetoRequest['method'] = $q->ihs_id == null ? 'POST' : 'PUT';
        $objetoRequest['data'] = $data;

        $response =  $this->ihsTools($objetoRequest, true);

        $noterror = true;
        if ($response->resourceType == 'OperationOutcome') {
            $noterror = false;
        }

        if ($q->ihs_id == null) {
            $mod = new IHS_Transaction();
            $mod->norec = $mod->generateNewId();
            $mod->statusenabled = $noterror;
            $mod->kdprofile = $this->kdProfile;
        } else {
            $mod = IHS_Transaction::where('id', $q->ihs_id)->first();
        }
        $mod->resourcetype = $data['resourceType'];
        $mod->url =  $this->endPoint() . $data['resourceType'];
        $mod->method = 'POST';
        $mod->id = isset($response->id) ? $response->id : null;
        $mod->body = json_encode($data);
        $mod->response =  json_encode($response);
        $mod->date = date('Y-m-d H:i:s');
        $mod->save();
        if (isset($response->id)) {
            Departemen::where('id', $q->id)->update([
                'ihs_id' => $response->id
            ]);
        }
        return response()->json($response);
    }

    public function Location(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $q =  collect(DB::select("
        SELECT
        kd.*,
        pr.ihs_id AS ihs_profile,
        pr.ihs_province,
        pr.ihs_city,
        pr.ihs_district,
        pr.ihs_village,
        pr.kodepos,
        pr.alamatlengkap,
        pr.namakota,
        pr.alamatemail,
        pr.website,
        pr.fixedphone ,
        pr.namalengkap as namaprofile,
        pr.lat,
        pr.lng,
        dep.ihs_id as ihs_dep
    FROM
        ruangan_m AS kd
        LEFT JOIN profile_m AS pr ON pr.ID = kd.kdprofile
        LEFT JOIN departemen_m AS dep ON dep.ID = kd.objectdepartemenfk
    WHERE
        kd.kdprofile = $kdProfile
        AND kd.statusenabled = true
        AND kd.ID = $request[id_ru]"))->first();

        $data = array(
            'id' => $q->ihs_id,
            'resourceType' => 'Location',
            'identifier' =>
            array(
                0 =>
                array(
                    'system' => 'http://sys-ids.kemkes.go.id/location/' . $q->ihs_profile,
                    'value' => (string) $q->id,
                ),
            ),
            'status' => 'active',
            'name' =>  $q->namaruangan,
            'description' => $q->namaruangan,
            'mode' => 'instance',
            'telecom' =>
            array(
                0 =>
                array(
                    'system' => 'phone',
                    'value' => $q->fixedphone,
                    'use' => 'work',
                ),
                1 =>
                array(
                    'system' => 'email',
                    'value' =>  $q->alamatemail,
                    'use' => 'work',
                ),
                2 =>
                array(
                    'system' => 'url',
                    'value' =>  $q->website,
                    'use' => 'work',
                ),
            ),
            'physicalType' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://terminology.hl7.org/CodeSystem/location-physical-type',
                        'code' => 'ro',
                        'display' => 'Room',
                    ),
                ),
            ),
            'position' =>
            array(
                'longitude' => (float) $q->lng,
                'latitude' => (float)$q->lat,
                'altitude' => 0,
            ),
            'managingOrganization' =>
            array(
                'reference' => 'Organization/' . $q->ihs_dep,
            ),
        );
        if ($q->ihs_id == null) {
            unset($data['id']);
        }

        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = $q->ihs_id == null ? $data['resourceType'] : $data['resourceType'] . '/' . $q->ihs_id;
        $objetoRequest['method'] = $q->ihs_id == null ? 'POST' : 'PUT';
        $objetoRequest['data'] = $data;

        $response =  $this->ihsTools($objetoRequest, true);

        $noterror = true;
        if ($response->resourceType == 'OperationOutcome') {
            $noterror = false;
        }

        if ($q->ihs_id == null) {
            $mod = new IHS_Transaction();
            $mod->norec = $mod->generateNewId();
            $mod->statusenabled = $noterror;
            $mod->kdprofile = $this->kdProfile;
        } else {
            $mod = IHS_Transaction::where('id', $q->ihs_id)->first();
            if (empty($mod)) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            }
        }
        $mod->resourcetype = $data['resourceType'];
        $mod->url =  $this->endPoint() . $data['resourceType'];
        $mod->method = 'POST';
        $mod->id = isset($response->id) ? $response->id : null;
        $mod->body = json_encode($data);
        $mod->response =  json_encode($response);
        $mod->date = date('Y-m-d H:i:s');
        $mod->save();
        if (isset($response->id)) {
            Ruangan::where('id', $q->id)->update([
                'ihs_id' => $response->id
            ]);
        }
        return response()->json($response);
    }
    public function Encounter(Request $request, $lokal = null)
    {
        try {

            $kdProfile = $this->kdProfile;
            $q =  collect(DB::select("
        SELECT
        ps.ihs_number
        ,ps.noidentitas
        ,ps.namapasien
        ,case when ru.objectdepartemenfk=16 then  'IMP' else 'AMB'  end as code_class
        ,case when ru.objectdepartemenfk=16 then  'inpatient encounter' else 'ambulatory'  end as name_class
        ,pg.ihs_id as ihs_practitioner
        ,pg.namalengkap as dokter
        ,pg.noidentitas as nik_dokter
        ,ru.namaruangan
        ,ru.ihs_id as ihs_location
        ,pd.tglregistrasi
        ,pd.tglpulang
        ,pd.noregistrasi
        ,pd.ihs_id as ihs_encounter
        ,pro.ihs_id as ihs_service_provider
        ,pd.ihs_condition
        ,pd.ihs_in_progress
        ,pd.ihs_finished
        ,stt.statuspulang
         FROM pasiendaftar_t as pd
        join pasien_m as ps on ps.id=pd.nocmfk
        join ruangan_m as ru on ru .id=pd.objectruanganlastfk
        left join pegawai_m as pg on pg .id=pd.objectpegawaifk
        join profile_m as pro on pro.id=pd.kdprofile
        left join statuspulang_m as stt on stt.id=pd.objectstatuspulangfk
        where pd.noregistrasi='$request[noregistrasi]'
        "))->first();

            $diagnosis = [];
            if ($q->ihs_condition != null && $request['diagnosis']) {

                $use =
                    array(
                        'coding' => [
                            array(
                                "system" => "http://terminology.hl7.org/CodeSystem/diagnosis-role",
                                "code" => "DD",
                                "display" => "Discharge diagnosis"
                            )
                        ]
                    );

                $diagnosis = $request['diagnosis'];
                unset($diagnosis['use']);
            }
            if ($q->ihs_number == null) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $q->noidentitas;
                $objetoRequest['method'] = 'GET';
                $objetoRequest['data'] = null;

                $response2 =  $this->ihsTools($objetoRequest, true);
                if (isset($response2->resourceType) && $response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle'  &&  $response2->total != 0) {
                    $id_IHS = null;
                    foreach ($response2->entry  as $dd) {
                        $id_IHS = $dd->resource->id;
                    }
                    if ($id_IHS != null) {
                        Pasien::where('noidentitas', $q->noidentitas)->update([
                            'ihs_number' => $id_IHS
                        ]);
                    }
                } else {
                    if ($lokal == true) {
                        return $response2;
                    }
                    return response()->json($response2);
                }
                $IHS_NUMBER_PASIEN = $id_IHS;
            } else {
                $IHS_NUMBER_PASIEN = $q->ihs_number;
            }
            $IHS_NAMA_DOKTER =  $q->dokter;
            if ($q->ihs_practitioner == null && $q->nik_dokter != null) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $q->nik_dokter;
                $objetoRequest['method'] = 'GET';
                $objetoRequest['data'] = null;
                $id_IHSDOK = null;
                $response2 =  $this->ihsTools($objetoRequest, true);
                if (isset($response2->resourceType) && $response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle'  &&  $response2->total != 0) {

                    foreach ($response2->entry  as $dd) {
                        $id_IHSDOK = $dd->resource->id;
                    }
                    if ($id_IHSDOK != null) {
                        Pegawai::where('noidentitas', $q->nik_dokter)->update([
                            'ihs_id' => $id_IHSDOK
                        ]);
                    }
                }
                $IHS_NAMA_DOKTER =  $q->dokter;
                $IHS_NUMBER_DOKTER = $id_IHSDOK;
            } else {
                $IHS_NAMA_DOKTER = $q->dokter;
                $IHS_NUMBER_DOKTER = $q->ihs_practitioner;
            }

            $tglregistrasi =    strtotime(date($q->tglregistrasi));
            $tglregistrasi =  substr(date("Y-m-d\TH:i:s", $tglregistrasi), 0, 23) . date('P', $tglregistrasi);
            $ihs_in_progress = null;
            $ihs_finished = null;
            if ($q->ihs_in_progress != null) {
                $ihs_in_progress = strtotime(date($q->ihs_in_progress));
                $ihs_in_progress = substr(date("Y-m-d\TH:i:s", $ihs_in_progress), 0, 23) . date('P', $ihs_in_progress);
            }
            if ($q->ihs_finished != null) {
                $ihs_finished = strtotime(date($q->ihs_finished));
                $ihs_finished = substr(date("Y-m-d\TH:i:s", $ihs_finished), 0, 23) . date('P', $ihs_finished);
            }

            $statusPulang = '';
            if ($q->code_class == 'AMB') {
                $status =  'arrived';
                $statusPulang = 'Diketahui / diizinkan oleh dokter ';

                $tglpulang =    strtotime(date($q->tglpulang));
                $tglpulang =  substr(date("Y-m-d\TH:i:s", $tglpulang), 0, 23) . date('P', $tglpulang);
                $statusHis =   array(
                    0 =>
                    array(
                        'status' => 'arrived',
                        'period' =>
                        array(
                            'start' => $tglregistrasi,
                            'end' => $ihs_in_progress ? $ihs_in_progress : $tglregistrasi,
                        ),
                    )
                );
                if ($ihs_in_progress != null) {
                    $status =  'in-progress';
                    $statusHis =   array(
                        0 =>
                        array(
                            'status' => 'arrived',
                            'period' =>
                            array(
                                'start' => $tglregistrasi,
                                'end' => $ihs_in_progress,
                            ),
                        ),
                        1 =>
                        array(
                            'status' => 'in-progress',
                            'period' =>
                            array(
                                'start' => $ihs_in_progress,
                                'end' =>  $ihs_finished ? $ihs_finished :  $ihs_in_progress,
                            ),
                        ),
                    );
                }
                if ($ihs_finished != null) {
                    $status =  'finished';
                    $statusHis =   array(
                        0 =>
                        array(
                            'status' => 'arrived',
                            'period' =>
                            array(
                                'start' => $tglregistrasi,
                                'end' => $ihs_in_progress,
                            ),
                        ),
                        1 =>
                        array(
                            'status' => 'in-progress',
                            'period' =>
                            array(
                                'start' => $ihs_in_progress,
                                'end' =>  $ihs_finished,
                            ),
                        ),
                        2 =>
                        array(
                            'status' => 'finished',
                            'period' =>
                            array(
                                'start' => $ihs_finished,
                                'end' => $ihs_finished
                            ),
                        ),
                    );
                }
            } else {
                if ($q->tglpulang == null) {
                    $status = 'arrived';

                    $statusHis =   array(
                        0 =>
                        array(
                            'status' => 'arrived',
                            'period' =>
                            array(
                                'start' => $tglregistrasi,
                                'end' => $ihs_in_progress ? $ihs_in_progress : $tglregistrasi,
                            ),
                        ),

                    );
                    if ($ihs_in_progress != null) {
                        $status =  'in-progress';
                        $statusHis =   array(
                            0 =>
                            array(
                                'status' => 'arrived',
                                'period' =>
                                array(
                                    'start' => $tglregistrasi,
                                    'end' => $ihs_in_progress,
                                ),
                            ),
                            1 =>
                            array(
                                'status' => 'in-progress',
                                'period' =>
                                array(
                                    'start' => $ihs_in_progress,
                                    'end' =>  $ihs_finished ? $ihs_finished :  $ihs_in_progress,
                                ),
                            ),
                        );
                    }
                } else {
                    $statusPulang = $q->statuspulang;
                    if ($q->statuspulang  == null) {
                        $statusPulang = 'Diketahui / diizinkan oleh dokter ';
                    }

                    $status =   'finished';
                    $tglpulang =    strtotime(date($q->tglpulang));
                    $tglpulang =  substr(date("Y-m-d\TH:i:s", $tglpulang), 0, 23) . date('P', $tglpulang);
                    if ($ihs_in_progress != null) {
                        $status =  'in-progress';
                        $statusHis =   array(
                            0 =>
                            array(
                                'status' => 'arrived',
                                'period' =>
                                array(
                                    'start' => $tglregistrasi,
                                    'end' => $ihs_in_progress,
                                ),
                            ),
                            1 =>
                            array(
                                'status' => 'in-progress',
                                'period' =>
                                array(
                                    'start' => $ihs_in_progress,
                                    'end' =>  $ihs_finished ? $ihs_finished :  $ihs_in_progress,
                                ),
                            ),
                        );
                    }
                    if ($ihs_finished != null) {
                        $status =  'finished';
                        $statusHis =   array(
                            0 =>
                            array(
                                'status' => 'arrived',
                                'period' =>
                                array(
                                    'start' => $tglregistrasi,
                                    'end' => $ihs_in_progress,
                                ),
                            ),
                            1 =>
                            array(
                                'status' => 'in-progress',
                                'period' =>
                                array(
                                    'start' => $ihs_in_progress,
                                    'end' =>  $ihs_finished,
                                ),
                            ),
                            2 =>
                            array(
                                'status' => 'finished',
                                'period' =>
                                array(
                                    'start' => $ihs_finished,
                                    'end' => $ihs_finished
                                ),
                            ),
                        );
                    }
                }
            }

            $data = array(
                'id' => $q->ihs_encounter,
                'resourceType' => 'Encounter',
                'identifier' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/encounter/' . $q->ihs_service_provider,
                        'value' => $q->noregistrasi,
                    )
                ),
                'status' => $status,
                'class' =>
                array(
                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                    'code' =>  $q->code_class,
                    'display' =>  $q->name_class,
                ),
                'subject' =>
                array(
                    'reference' => 'Patient/' . $IHS_NUMBER_PASIEN,
                    'display' => $q->namapasien,
                ),
                'participant' =>
                array(
                    0 =>
                    array(
                        'type' =>
                        array(
                            0 =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://terminology.hl7.org/CodeSystem/participant-type',
                                        // 'code' => 'ATND',
                                        // 'display' => 'attender',
                                        'code' => 'translator',
                                        'display' => 'Translator',
                                    ),
                                ),
                            ),
                        ),
                        'individual' =>
                        array(
                            'reference' => 'Practitioner/' . $IHS_NUMBER_DOKTER,
                            'display' => $IHS_NAMA_DOKTER,
                        ),
                    ),
                ),
                'period' =>
                array(
                    'start' => $tglregistrasi,
                    'end' => $ihs_finished,
                ),
                'location' =>
                array(
                    0 =>
                    array(
                        'location' =>
                        array(
                            'reference' => 'Location/' . $q->ihs_location,
                            'display' => $q->namaruangan,
                        ),
                    ),
                ),
                'diagnosis' => $diagnosis,
                'serviceProvider' =>  array(
                    "reference" => "Organization/" . $q->ihs_service_provider
                ),
                'statusHistory' =>
                $statusHis,

                "hospitalization" => array(
                    "dischargeDisposition" =>  array(
                        "coding" => [
                            array(
                                "system" => "http://terminology.hl7.org/CodeSystem/discharge-disposition",
                                "code" => "home",
                                "display" => "Home"
                            )
                        ],
                        "text" => $statusPulang
                    )
                )

            );

            if ($IHS_NUMBER_DOKTER == null) {
                unset($data['participant']);
            }
            if ($q->ihs_encounter == null) {
                unset($data['id']);
            }
            if ($q->ihs_condition == null) {
                unset($data['diagnosis']);
            }
            if ($ihs_finished == null) {
                unset($data['period']['end']);
                unset($data['hospitalization']);
            }


            $method =  $q->ihs_encounter == null ? 'POST' : 'PUT';
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $q->ihs_encounter == null ? $data['resourceType'] : $data['resourceType'] . '/' . $q->ihs_encounter;
            $objetoRequest['method'] = $method;
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);

            $noterror = true;
            if (isset($response->resourceType) && $response->resourceType == 'OperationOutcome') {
                $noterror = false;
                if (isset($response->issue) && isset($response->issue[0]->code)  && $response->issue[0]->code == 'not-found') {
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest['url'] =  $data['resourceType'];
                    $objetoRequest['method'] = $method;
                    $objetoRequest['data'] = $data;
                    $response =  $this->ihsTools($objetoRequest, true);
                    $noterror = true;
                    if ($response->resourceType == 'OperationOutcome') {
                        $noterror = false;
                    }
                }
            }

            if ($q->ihs_encounter == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $q->ihs_encounter)->first();
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = $method;
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response =  json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                PasienDaftar::where('noregistrasi', $q->noregistrasi)->update([
                    'ihs_id' => $response->id
                ]);
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Condition(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q =  collect(DB::select("
        SELECT
        ps.ihs_number
        ,ps.namapasien
        ,pd.noregistrasi
        ,pd.ihs_id as ihs_encounter
        ,pd.ihs_condition as conditionhead
        ,dg.kddiagnosa
        ,dg.namadiagnosa
        ,pd.noregistrasi
        ,ddp.ihs_id as ihs_condition
        ,ddp.norec
        ,ps.noidentitas
         FROM pasiendaftar_t as pd
        join pasien_m as ps on ps.id=pd.nocmfk
        join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
        JOIN detaildiagnosapasien_t as ddp on ddp.noregistrasifk=apd.norec
        join diagnosa_m as dg on dg.id=ddp.objectdiagnosafk
        where pd.noregistrasi='$request[noregistrasi]'
        "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Diagnosis belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            // if ($q[0]->conditionhead == null) {


            if ($q[0]->ihs_number == null) {

                if( $q[0]->noidentitas == null ){
                    return 'Identitas Pasien Kosong';
                }
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = 'Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $q[0]->noidentitas;
                $objetoRequest['method'] = 'GET';
                $objetoRequest['data'] = null;

                $response2 =  $this->ihsTools($objetoRequest, true);
                if ($response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle' &&  $response2->total != 0) {
                    $id_IHS = null;
                    foreach ($response2->entry  as $dd) {

                        $id_IHS = $dd->resource->id;
                    }
                    if ($id_IHS != null) {
                        Pasien::where('noidentitas', $q[0]->noidentitas)->update([
                            'ihs_number' => $id_IHS
                        ]);
                    }
                }
                $IHS_NUMBER_PASIEN = $id_IHS;
            } else {
                $IHS_NUMBER_PASIEN = $q[0]->ihs_number;
            }
            $diagnosisArr = [];
            foreach ($q as $k =>  $item) {
                // $diagnosis[] = ;
                if($item->ihs_encounter == null){
                    return 'Encounter belum ada';
                }
                $data = array(
                    'id' => $item->ihs_condition,
                    'resourceType' => 'Condition',
                    'clinicalStatus' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                                'code' => 'active',
                                'display' => 'Active',
                            ),
                        ),
                    ),
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
                                    'code' => 'encounter-diagnosis',
                                    'display' => 'Encounter Diagnosis',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' => [
                            array(
                                'system' => 'http://hl7.org/fhir/sid/icd-10',
                                'code' => $item->kddiagnosa,
                                'display' =>  $item->namadiagnosa,
                            )
                        ]
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $IHS_NUMBER_PASIEN,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                );

                if ($item->ihs_condition == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_condition == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);


                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_condition == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_condition)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    PasienDaftar::where('noregistrasi', $item->noregistrasi)->update([
                        'ihs_condition' => $response->id
                    ]);
                    DetailDiagnosaPasien::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id
                    ]);

                    $diagnosisArr[] = array(
                        'condition' =>
                        array(
                            'reference' => 'Condition/' . $mod->id,
                            'display' =>  $item->namadiagnosa,
                        ),
                        'use' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    "code"=> "DD",
                                    "display"=> "Discharge diagnosis",
                                    "system"=> "http://terminology.hl7.org/CodeSystem/diagnosis-role"
                                ),
                            ),
                        ),
                        'rank' => $k + 1,
                    );
                }
            }

            if (count($diagnosisArr) > 0) {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['noregistrasi'] = $q[0]->noregistrasi;
                $objetoRequest['diagnosis'] = $diagnosisArr;
                PasienDaftar::where('noregistrasi', $q[0]->noregistrasi)->update([
                    'ihs_diagnosis' => json_encode($diagnosisArr)
                ]);
                $ihs = app('App\Http\Controllers\Bridging\IHSController')->Encounter($objetoRequest, true);
            }
            // }else{
            //     $response = array(
            //         "issue" => $e->getMessage() . ' ' . $e->getLine(),
            //         "resourceType" => "OperationOutcome"
            //     );
            // }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Encountertes(Request $request)
    {
        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['noregistrasi'] = $request['noregistrasi'];
        $ihs = app('App\Http\Controllers\Bridging\IHSController')->Encounter($objetoRequest);
        return $ihs;
    }
    // public function getList(Request $request)
    // {
    //     $data = DB::table('ihs_transaction')
    //         ->select('*')
    //         ->where('resourcetype', '=', $request['resourcetype'])
    //         ->where('statusenabled', true);
    //     if (isset($request['dari']) && $request['dari'] != '') {
    //         $data = $data->where('date', '>=', $request['dari'] . ' 00:00');
    //     }
    //     if (isset($request['sampai']) && $request['sampai'] != '') {
    //         $data = $data->where('date', '<=', $request['sampai'] . ' 23:59');
    //     }
    //     $data = $data->orderby('date', 'desc');
    //     $data = $data->get();
    //     $data2 = [];
    //     foreach ($data as $d) {
    //         // $d->body = json_decode( $d->body);
    //         // $d->response = json_decode( $d->response);
    //         if ($d->resourcetype == 'Bundle') {
    //             $daaa = array(
    //                 'request' => json_decode($d->body),
    //                 'response' => json_decode($d->response),
    //             );
    //             $data2[] =  $daaa;
    //         } else {
    //             $daaa = json_decode($d->response);
    //         }

    //         if (isset($daaa->resourceType) && $daaa->resourceType != 'OperationOutcome') {
    //             $data2[] =  $daaa;
    //         }
    //     }
    //     return response()->json($data2);
    // }
    public function Practitioner(Request $request)
    {

        $pegawai =  Pegawai::where('id', $request['id_nakes'])->first();
        $objetoRequest = new \Illuminate\Http\Request();
        $objetoRequest['url'] = 'Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $pegawai->noidentitas;
        $objetoRequest['method'] = 'GET';
        $objetoRequest['data'] = null;
        $id_IHSDOK = '';
        $response2 =  $this->ihsTools($objetoRequest, true);

        if ($response2->resourceType != 'OperationOutcome' && $response2->resourceType == 'Bundle' &&  $response2->total != 0) {
            foreach ($response2->entry  as $dd) {
                $id_IHSDOK = $dd->resource->id;
            }
        }

        if ($id_IHSDOK != '') {
            Pegawai::where('id', $request['id_nakes'])->update([
                'ihs_id' => $id_IHSDOK
            ]);
        }


        return response()->json($response2);
    }

    public function updateIHSPasien(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {

            $JD = Pasien::where('id', $request['id'])->where('kdprofile', $kdProfile)->update(
                [
                    'ihs_number' => $request['ihs_number']
                ]
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "pasien" => $JD,
                "as" => 'inhuman',
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "as" => 'inhuman',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result, $transMessage);
    }
    public function Medication(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $data =  collect(DB::select("
                SELECT
                kd.*
                ,kd.id as idprodukna
                ,pr.ihs_id AS ihs_profile
                ,pr.namalengkap as namaprofile

                FROM
                produk_m AS kd
                LEFT JOIN profile_m AS pr ON pr.ID = kd.kdprofile
                --LEFT JOIN ihs_map_bahanzat AS mbh ON mbh.produkfk = kd.id
                -- LEFT JOIN ihs_bahanzat AS bh ON bh.ID = mbh.ihs_bahanzat
                --LEFT JOIN ihs_sediaan AS sed ON sed.ID = kd.ihs_sediaan
                --LEFT JOIN ihs_kode_kf_a AS kff ON kff.ID = kd.ihs_kfa_code
                WHERE
                kd.kdprofile = $kdProfile
                AND kd.statusenabled = true
                AND kd.ID = $request[id]
          "));
            if (count($data) == 0) {
                return response()->json(null);
            }

            $ingredients = [];
            $ingredi = json_decode($data[0]->response_kfa);
            foreach ($ingredi->active_ingredients as $item) {

                $ingredients[] =  array(
                    'itemCodeableConcept' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                'code' => $item->kfa_code,
                                'display' =>  $item->zat_aktif,
                            ),
                        ),
                    ),
                    'isActive' => true,
                    'strength' =>
                    array(
                        'numerator' =>
                        array(
                            'value' => (float)$item->kekuatan_zat_aktif,
                            'system' => 'http://unitsofmeasure.org',
                            'code' => $ingredi->net_weight_uom_name,
                        ),
                        'denominator' =>
                        array(
                            'value' => 1,
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                            'code' =>'TAB',// $ingredi->dosage_form->name,
                        ),
                    ),
                );
            }
            $q = $data[0];
            // $SPD =  StokProdukDetail::where('objectprodukfk', $q->idprodukna)
            //     ->where('qtyproduk', '>', 0)
            //     ->orderBy('tglkadaluarsa', 'desc')
            //     ->get();

            $tglkadaluarsa = '';
            $nobatch = '';

            // foreach ($SPD as $d) {
            //     if ($d->nobatch != null) {
            //         $nobatch = $d->nobatch;
            //     }
            //     if ($d->tglkadaluarsa != null) {
            //         $tglkadaluarsa = date('Y-m-d', strtotime($d->tglkadaluarsa));
            //     }
            // }

            $data = array(
                'id' =>  $q->ihs_id,
                'resourceType' => 'Medication',
                'identifier' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/medication/' . $q->ihs_profile,
                        'use' => 'official',
                        'value' => (string) $q->ihs_profile .'-'.$q->idprodukna,
                    ),
                ),
                'code' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/kfa',
                            'code' => trim($q->ihs_kfa_code),
                            'display' => trim($ingredi->name),
                        ),
                    ),
                ),
                'status' => 'active',
                'manufacturer' =>
                array(
                    'reference' => 'Organization/' . $q->ihs_profile, //PT KIMIA FARMA, Tbk
                ),
                'form' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-form',
                            'code' => $q->ihs_sediaan,
                            'display' => $q->ihs_sediaan_display,
                        ),
                    ),
                ),
                'ingredient' =>
                $ingredients,

                'extension' =>
                array(
                    0 =>
                    array(
                        'url' => 'https://fhir.kemkes.go.id/r4/StructureDefinition/MedicationType',
                        'valueCodeableConcept' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.kemkes.go.id/CodeSystem/medication-type',
                                    'code' => 'NC',
                                    'display' => 'Non-compound',
                                ),
                            ),
                        ),
                    ),
                ),

                // 'batch' =>
                // array(
                //     'lotNumber' => $nobatch,
                //     'expirationDate' => $tglkadaluarsa,
                // ),
            );
// dd($ingredi);
            if ($q->ihs_id == null) {
                unset($data['id']);
            }



            $method =   $q->ihs_id == null ? 'POST' : 'PUT';
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $q->ihs_id == null ? $data['resourceType'] : $data['resourceType'] . '/' . $q->ihs_id;
            $objetoRequest['method'] = $method;
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);
            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;
            }

            if ($q->ihs_id == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $q->ihs_id)->first();
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method =  $q->ihs_id == null ? 'POST' : 'PUT';
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response =  json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                Produk::where('id', $q->idprodukna)->update([
                    'ihs_id' => $response->id
                ]);
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function MedicationRequest(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q  =  collect(DB::select("
                SELECT so.noorder,op.rke, jk.jeniskemasan, pr.namaproduk, ss.satuanstandar
                ,op.aturanpakai, op.jumlah, op.hargasatuan,op.keteranganpakai as keterangan,
                op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa
                ,pr.id as idproduk
                ,op.ihs_id as ihs_medication_request
                ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
                ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
                ,ru.namaruangan
                ,pr.ihs_id as ihs_medication
                ,pd.ihs_id as ihs_encounter
                ,ps.ihs_number as ihs_patient
                ,ps.namapasien
                ,so.tglorder
                ,case when pg.ihs_id is null then pg2.ihs_id else pg.ihs_id end as ihs_practitioner
                ,case when pg.ihs_id is null then pg2.namalengkap else pg.namalengkap end as pegawaiorder
                ,pr.ihs_denominator_code
                ,pr.ihs_kfa_display
                ,op.norec as norec_op
                ,op.ihs_noorder
                ,pr.response_kfa
                from strukorder_t as so
                inner join orderpelayanan_t as op on op.strukorderfk = so.norec
                inner join produk_m as pr on pr.id=op.objectprodukfk
                inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
                inner join pasien_m as ps on ps.id=pd.nocmfk
                left join jeniskemasan_m as jk on jk.id=op.jeniskemasanfk
                left join satuanstandar_m as ss on ss.id=op.objectsatuanstandarfk
                left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
                left join pegawai_m as pg2 on pg2.id=pd.objectpegawaifk
                left join satuanresep_m as sn on sn.id=op.satuanresepfk
                inner join ruangan_m as ru on ru.id=so.objectruanganfk

                where so.noorder='$request[noorder]'
                and so.statusenabled=true
                and so.kdprofile=$kdProfile
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'MedicationRequest belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $IDpRODUK = [];
            foreach ($q as $k =>  $item) {
                $IDpRODUK[] = $item->idproduk;
            }
            // $ingredientsS = Produk::where('statusenabled', true)
            // ->whereiN('id', $IDpRODUK)
            // ->get();
            // $ingredientsS = json_decode($data[0]->response_kfa);
            // $ingredientsS = DB::table('ihs_map_bahanzat as mm1')
            //     ->leftJoin('ihs_numerator_satuan as mm', 'mm.id', '=', 'mm1.numerartorsatuanfk')
            //     ->leftJoin('ihs_denom_satuan as mm3', 'mm3.id', '=', 'mm1.denomsatuanfk')
            //     ->leftJoin('ihs_bahanzat as bh', 'bh.id', '=', 'mm1.ihs_bahanzat')
            //     ->select(
            //         'mm1.*',
            //         'mm.nama as numerator',
            //         'mm3.nama as denominator',
            //         'mm1.qtynum as denominatorvalue',
            //         'mm1.qtydenom as numeratorvalue',
            //         'mm.id as numeratorfk',
            //         'mm3.id as denominatorfk',
            //         'mm1.aktif as isactive',
            //         'mm1.ihs_bahanzat as komposisizatfk',
            //         'bh.nama as komposisizat'
            //     )
            //     ->whereiN('mm1.produkfk', $IDpRODUK)
            //     ->get();
            $profile = Profile::where('statusenabled', true)->first();
            foreach ($q as $k =>  $item) {
                // if ($item->ihs_medication == null) {
                //     $objetoRequest = new \Illuminate\Http\Request();
                //     $objetoRequest['id'] = $item->idproduk;
                //     $ihs = app('App\Http\Controllers\Bridging\IHSController')->Medication($objetoRequest, true);
                //     if ($ihs->resourceType != 'OperationOutcome') {
                //         Produk::where('id', $item->idproduk)->update([
                //             'ihs_id' => $ihs->id
                //         ]);
                //         $item->ihs_medication = $ihs->id;
                //     }
                // }
                $DoseQtyunitUnit = $item->satuanstandar;
                $DoseQtyunitCode = $item->satuanstandar;
                $ingredientsS =   json_decode($item->response_kfa);

                foreach ($ingredientsS->active_ingredients as $ing) {

                        if ($ing->kekuatan_zat_aktif != null) {
                            $DoseQtyunitUnit =  $ingredientsS->volume_uom_name;//$ingredientsS->dose_per_unit;
                            $DoseQtyunitCode = 'TAB';// $ingredientsS->volume_uom_name;
                        }

                }

                $repeat_period = 1;
                $repeat_frequency = 1;
                $arr = explode('x', $item->aturanpakai);
                if (count($arr) == 2) {
                    $repeat_frequency = (float)$arr[0];
                    $repeat_period = (float) $arr[1];
                }
                $data = array(
                    'id' => $item->ihs_medication_request,
                    'resourceType' => 'MedicationRequest',
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' => $item->noorder,
                        ),
                        1 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription-item/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' =>  $item->noorder . '-' . ($k + 1),
                        ),
                    ),
                    'status' => 'completed',
                    'statusReason' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-status-reason',
                                'code' => 'clarif',
                                'display' => 'Prescription requires clarification',
                            ),
                        ),
                    ),
                    'intent' => 'order',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-category',
                                    'code' => $item->code_class,
                                    'display' => $item->name_class,
                                ),
                            ),
                        ),
                    ),
                    'priority' => 'routine',
                    'medicationReference' =>
                    array(
                        'reference' => 'Medication/' . $item->ihs_medication,
                        'display' => $item->ihs_kfa_display != null ? $item->ihs_kfa_display : $item->namaproduk,
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_patient,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                    'authoredOn' => $this->dateISO($item->tglorder),
                    'requester' =>
                    array(
                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        'display' => $item->pegawaiorder,
                    ),
                    'performerType' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => '309343006',
                                'display' => 'Physician',
                            ),
                        ),
                    ),
                    // 'reasonCode' =>
                    // array(
                    //     0 =>
                    //     array(
                    //         'coding' =>
                    //         array(
                    //             0 =>
                    //             array(
                    //                 'system' => 'http://hl7.org/fhir/sid/icd-10',
                    //                 'code' => 'A15.0',
                    //                 'display' => 'Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
                    //             ),
                    //         ),
                    //     ),
                    // ),
                    'courseOfTherapyType' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/medicationrequest-course-of-therapy',
                                'code' => 'seasonal', // 'continuous',
                                'display' => 'Seasonal', // 'Continuing long term therapy',
                            ),
                        ),
                    ),
                    'dosageInstruction' =>
                    array(
                        0 =>
                        array(
                            'sequence' => 1,
                            'text' => $item->aturanpakai,
                            'additionalInstruction' =>
                            array(
                                0 =>
                                array(
                                    'text' =>  $item->aturanpakai,
                                ),
                            ),
                            'patientInstruction' =>  $item->keterangan,
                            'timing' =>
                            array(
                                'repeat' =>
                                array(
                                    'frequency' => $repeat_frequency,
                                    'period' => $repeat_period,
                                    'periodUnit' => 'd',
                                ),
                            ),
                            'route' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://www.whocc.no/atc',
                                        'code' => 'O',
                                        'display' => 'Oral',
                                    ),
                                ),
                            ),
                            'doseAndRate' =>
                            array(
                                0 =>
                                array(
                                    'type' =>
                                    array(
                                        'coding' =>
                                        array(
                                            0 =>
                                            array(
                                                'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                                'code' => 'ordered',
                                                'display' => 'Ordered',
                                            ),
                                        ),
                                    ),

                                    'doseQuantity' =>
                                    array(
                                        'value' => (float) $item->jumlah,
                                        'unit' => $DoseQtyunitUnit,
                                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                        'code' => $DoseQtyunitCode,
                                    ),
                                ),
                            ),
                        ),
                    ),
                    'dispenseRequest' =>
                    array(
                        // 'dispenseInterval' =>
                        // array(
                        //     'value' => 1,
                        //     'unit' => 'days',
                        //     'system' => 'http://unitsofmeasure.org',
                        //     'code' => 'd',
                        // ),
                        // 'validityPeriod' =>
                        // array(
                        //     'start' => '2022-01-01',
                        //     'end' => '2022-01-30',
                        // ),
                        'numberOfRepeatsAllowed' => 0,
                        'quantity' =>
                        array(
                            'value' => (float) $item->jumlah,
                            'unit' => $DoseQtyunitUnit,
                            'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                            'code' => $DoseQtyunitCode,
                        ),
                        // 'expectedSupplyDuration' =>
                        // array(
                        //     'value' => 30,
                        //     'unit' => 'days',
                        //     'system' => 'http://unitsofmeasure.org',
                        //     'code' => 'd',
                        // ),
                        'performer' =>
                        array(
                            "reference" => "Organization/" . $profile->ihs_id
                        ),
                    ),
                );


                if ($item->ihs_medication_request == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_medication_request == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);


                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_medication_request == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_medication_request)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    OrderPelayanan::where('norec', $item->norec_op)->update([
                        'ihs_id' => $response->id,
                        'ihs_noorder' => $data['identifier'][1]['value']
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function MedicationDispense(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q  =  collect(DB::select("

                 SELECT
                sr.noresep,
                pp.hargasatuan,
                pp.stock,
                apd.objectruanganfk,
                ru.namaruangan,
                pp.rke,
                pp.jeniskemasanfk,
                jk.id AS jkid,
                jk.jeniskemasan,
                pp.aturanpakai,
                pp.routefk,
                rt.name AS namaroute,
                pp.produkfk,
                pp.produkfk as idproduk,
                pr.namaproduk,
                pp.nilaikonversi,
                pr.objectsatuanstandarfk,
                ss.satuanstandar,
                pp.satuanviewfk,
                ss2.satuanstandar AS ssview,
                pp.jumlah,
                pp.hargadiscount,
                pp.dosis,
                pp.jenisobatfk,
                pp.jasa,
                pp.hargajual,
                pp.hargasatuan,
                pp.strukterimafk,
                pp.qtydetailresep,
                pp.ispagi,
                pp.issiang,
                pp.ismalam,
                pp.issore,
                pr.kekuatan,
                pp.keteranganpakai,
                pp.iskronis,
                pp.satuanresepfk,
                sn.satuanresep,
                pp.tglkadaluarsa
                ,pp.ihs_id as ihs_medicationdispense
                ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
                ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
                ,ru.namaruangan
                ,pr.ihs_id as ihs_medication
                ,pd.ihs_id as ihs_encounter
                ,ps.ihs_number as ihs_patient
                ,ps.namapasien
                ,case when pg.ihs_id is null then pg2.ihs_id else pg.ihs_id end as ihs_practitioner
                ,case when pg.ihs_id is null then pg2.namalengkap else pg.namalengkap end as penulisresep
                ,ru2.ihs_id as ihs_apotik
                ,ru2.namaruangan as ihs_apotik_display
                ,op.ihs_id as ihs_medication_request
                ,pr.ihs_denominator_code
                ,sr.tglresep
                ,pr.ihs_kfa_display
                ,pp.norec as norec_pp
                ,pr.response_kfa
            FROM
                strukresep_t AS sr
                INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                INNER JOIN ruangan_m AS ru2 ON ru2.id = sr.ruanganfk
                INNER JOIN jeniskemasan_m AS jk ON jk.id = pp.jeniskemasanfk
                LEFT JOIN routefarmasi AS rt ON rt.id = pp.routefk
                INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
                INNER JOIN satuanstandar_m AS ss ON ss.id = pr.objectsatuanstandarfk
                INNER JOIN satuanstandar_m AS ss2 ON ss2.id = pp.satuanviewfk
                LEFT JOIN satuanresep_m AS sn ON sn.id = pp.satuanresepfk
                inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                inner join pasien_m as ps on ps.id=pd.nocmfk
                left join pegawai_m as pg on pg.id=sr.penulisresepfk
                left join pegawai_m as pg2 on pg2.id=pd.objectpegawaifk
                left join orderpelayanan_t as op on sr.orderfk = op.strukorderfk and op.objectprodukfk = pr.id
            WHERE
                sr.kdprofile = $kdProfile
                AND sr.noresep = '$request[noresep]'
                and sr.statusenabled=true

                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'MedicationDispense belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $IDpRODUK = [];
            foreach ($q as $k =>  $item) {
                $IDpRODUK[] = $item->idproduk;
            }

            // $ingredientsS = DB::table('ihs_map_bahanzat as mm1')
            //     ->leftJoin('ihs_numerator_satuan as mm', 'mm.id', '=', 'mm1.numerartorsatuanfk')
            //     ->leftJoin('ihs_denom_satuan as mm3', 'mm3.id', '=', 'mm1.denomsatuanfk')
            //     ->leftJoin('ihs_bahanzat as bh', 'bh.id', '=', 'mm1.ihs_bahanzat')
            //     ->select(
            //         'mm1.*',
            //         'mm.nama as numerator',
            //         'mm3.nama as denominator',
            //         'mm1.qtynum as denominatorvalue',
            //         'mm1.qtydenom as numeratorvalue',
            //         'mm.id as numeratorfk',
            //         'mm3.id as denominatorfk',
            //         'mm1.aktif as isactive',
            //         'mm1.ihs_bahanzat as komposisizatfk',
            //         'bh.nama as komposisizat'
            //     )
            //     ->whereiN('mm1.produkfk', $IDpRODUK)
            //     ->get();

            $profile = Profile::where('statusenabled', true)->first();
            foreach ($q as $k =>  $item) {
                $tglresep =    strtotime(date($item->tglresep));
                $tglresep =  substr(date("Y-m-d\TH:i:s", $tglresep), 0, 23) . date('P', $tglresep);
                $repeat_period = 1;
                $repeat_frequency = 1;
                $arr = explode('x', $item->aturanpakai);
                if (count($arr) == 2) {
                    $repeat_frequency = (float)$arr[0];
                    $repeat_period = (float) $arr[1];
                }
                $DoseQtyunitUnit = $item->satuanstandar;
                $DoseQtyunitCode = $item->satuanstandar;
                $ingredientsS =   json_decode($item->response_kfa);

                foreach ($ingredientsS->active_ingredients as $ing) {

                        if ($ing->kekuatan_zat_aktif != null) {
                            $DoseQtyunitUnit =  $ingredientsS->volume_uom_name;//$ingredientsS->dose_per_unit;
                            $DoseQtyunitCode = 'TAB';// $ingredientsS->volume_uom_name;
                        }

                }
                $data = array(
                    'id' => $item->ihs_medicationdispense,
                    'resourceType' => 'MedicationDispense',
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' =>   $item->noresep,
                        ),
                        1 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/prescription-item/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' => $item->noresep . '-' . ($k + 1),
                        ),
                    ),
                    'status' => 'completed',
                    'category' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
                                'code' => $item->code_class,
                                'display' => $item->name_class,
                            ),
                        ),
                    ),
                    'medicationReference' =>
                    array(
                        'reference' => 'Medication/' . $item->ihs_medication,
                        'display' => $item->ihs_kfa_display != null ? $item->ihs_kfa_display : $item->namaproduk,
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_patient,
                        'display' => $item->namapasien,
                    ),
                    'context' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'actor' =>
                            array(
                                'reference' => 'Practitioner/' . $item->ihs_practitioner,
                                'display' => $item->penulisresep,
                            ),
                        ),
                    ),
                    'location' =>
                    array(
                        'reference' => 'Location/' . $item->ihs_apotik,
                        'display' =>  $item->ihs_apotik_display,
                    ),
                    'authorizingPrescription' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'MedicationRequest/' . $item->ihs_medication_request,
                        ),
                    ),
                    'quantity' =>
                    array(
                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                        'code' => $DoseQtyunitCode,
                        'value' => (float)$item->jumlah,
                    ),
                    'daysSupply' =>
                    array(
                        'value' => 1,
                        'unit' => 'Day',
                        'system' => 'http://unitsofmeasure.org',
                        'code' => 'd',
                    ),
                    'whenPrepared' => $tglresep,
                    'whenHandedOver' => $tglresep,
                    'dosageInstruction' =>
                    array(
                        0 =>
                        array(
                            'sequence' => 1,
                            'text' => $item->aturanpakai,
                            'timing' =>
                            array(
                                'repeat' =>
                                array(
                                    'frequency' => $repeat_frequency,
                                    'period' => $repeat_period,
                                    'periodUnit' => 'd',
                                ),
                            ),
                            'doseAndRate' =>
                            array(
                                0 =>
                                array(
                                    'type' =>
                                    array(
                                        'coding' =>
                                        array(
                                            0 =>
                                            array(
                                                'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                                'code' => 'ordered',
                                                'display' => 'Ordered',
                                            ),
                                        ),
                                    ),
                                    'doseQuantity' =>
                                    array(
                                        'value' => (float) $item->jumlah,
                                        'unit' => $DoseQtyunitUnit,
                                        'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                        'code' =>  $DoseQtyunitCode,
                                    ),
                                ),
                            ),
                        ),
                    ),
                );




                if ($item->ihs_medicationdispense == null) {
                    unset($data['id']);
                }
                if ($item->ihs_medication_request == null) {
                    unset($data['authorizingPrescription']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_medicationdispense == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);
                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_medicationdispense == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_medicationdispense)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    PelayananPasien::where('norec', $item->norec_pp)->update([
                        'ihs_id' => $response->id,
                        'ihs_noresep' => $data['identifier'][1]['value']
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function MedicationDispenseObatBebas(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q  =  collect(DB::select("

            SELECT
                sp.tglstruk as tglresep
                ,sp.nostruk as noresep
                ,spd.aturanpakai
                ,sp.nostruk_intern
                ,sp.namapasien_klien
                ,spd.qtyproduk  as jumlah
                ,( ( spd.hargasatuan - spd.hargadiscount ) * spd.qtyproduk ) + spd.hargatambahan AS total,
                spd.tglkadaluarsa
                ,spd.ihs_id as ihs_medicationdispense
                ,pr.id as idproduk
                ,pr.namaproduk
                ,ss.satuanstandar
                ,case when ru.objectdepartemenfk=16 then  'inpatient' else 'outpatient'  end as code_class
                ,case when ru.objectdepartemenfk=16 then  'Inpatient' else 'Outpatient'  end as name_class
                ,pr.ihs_id as ihs_medication
                ,pg.ihs_id  as ihs_practitioner
                ,pg.namalengkap  as penulisresep
                ,ru.ihs_id as ihs_apotik
                ,ru.namaruangan as ihs_apotik_display
                ,spd.norec as norec_pp
                ,pr.ihs_kfa_code
                ,ko.namaproduk as  ihs_kfa_display
                ,pr.ihs_sediaan
                ,sed.nama as ihs_sediaan_display
                ,spd.urn_uuid_medication
                ,sp.ihs_bundle
                ,sp.norec as norec_sp
            FROM
                strukpelayanan_t AS sp
                INNER join strukpelayanandetail_t as spd on spd.nostrukfk=sp.norec
                LEFT JOIN pegawai_m AS pg ON pg.ID = sp.objectpegawaipenanggungjawabfk
                LEFT JOIN ruangan_m AS ru ON ru.ID = sp.objectruanganfk
                LEFT JOIN produk_m AS pr ON pr.ID = spd.objectprodukfk
                LEFT JOIN jeniskemasan_m AS jkm ON jkm.ID = spd.objectjeniskemasanfk
                LEFT JOIN satuanstandar_m AS ss ON ss.ID = spd.objectsatuanstandarfk
                left join ihs_kode_kf_a as ko on ko.id=pr.ihs_kfa_code
                LEFT JOIN ihs_sediaan AS sed ON sed.ID = pr.ihs_sediaan
                WHERE
                sp.kdprofile = $kdProfile
                and sp.nostruk = '$request[noresep]'
                AND sp.statusenabled = true
            ORDER BY
                sp.nostruk ASC

                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'MedicationDispense belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }

            $IDpRODUK = [];
            foreach ($q as $k =>  $item) {
                $IDpRODUK[] = $item->idproduk;
            }
            $SPD =  StokProdukDetail::whereIn('objectprodukfk', $IDpRODUK)
                ->where('qtyproduk', '>', 0)
                ->orderBy('tglkadaluarsa', 'desc')
                ->get();


            $ingredientsS = DB::table('ihs_map_bahanzat as mm1')
                ->leftJoin('ihs_numerator_satuan as mm', 'mm.id', '=', 'mm1.numerartorsatuanfk')
                ->leftJoin('ihs_denom_satuan as mm3', 'mm3.id', '=', 'mm1.denomsatuanfk')
                ->leftJoin('ihs_bahanzat as bh', 'bh.id', '=', 'mm1.ihs_bahanzat')
                ->select(
                    'mm1.*',
                    'mm.nama as numerator',
                    'mm3.nama as denominator',
                    'mm1.qtynum as denominatorvalue',
                    'mm1.qtydenom as numeratorvalue',
                    'mm.id as numeratorfk',
                    'mm3.id as denominatorfk',
                    'mm1.aktif as isactive',
                    'mm1.ihs_bahanzat as komposisizatfk',
                    'bh.nama as komposisizat'
                )
                ->whereiN('mm1.produkfk', $IDpRODUK)
                ->get();
            $profile = Profile::where('statusenabled', true)->first();
            $entry = [];


            foreach ($q as $k =>  $item) {
                $tglresep =    strtotime(date($item->tglresep));
                $tglresep =  substr(date("Y-m-d\TH:i:s", $tglresep), 0, 23) . date('P', $tglresep);
                $repeat_period = 1;
                $repeat_frequency = 1;
                $arr = explode('x', $item->aturanpakai);
                if (count($arr) == 2) {
                    $repeat_frequency = (float)$arr[0];
                    $repeat_period = (float) $arr[1];
                }
                $medi_DoseQtyunitUnit = $item->satuanstandar;
                $medi_DoseQtyunitCode = $item->satuanstandar;
                $medi_kodeKamusKFAobat = $item->ihs_kfa_code ? $item->ihs_kfa_code : '';
                $medi_namaKamusKFAobat = $item->ihs_kfa_display ? $item->ihs_kfa_display : $item->namaproduk;
                $ingredients = [];
                foreach ($ingredientsS as $ing) {
                    if ($ing->produkfk == $item->idproduk) {
                        if ($ing->denominator != null) {
                            $medi_DoseQtyunitUnit = $ing->denominator;
                            $medi_DoseQtyunitCode = $ing->denominatorfk;
                        }
                        $ingredients[] =  array(
                            'itemCodeableConcept' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                        'code' => $ing->komposisizatfk,
                                        'display' => $ing->komposisizat,
                                    ),
                                ),
                            ),
                            'isActive' => $ing->isactive ? $ing->isactive : true,
                            'strength' =>
                            array(
                                'numerator' =>
                                array(
                                    'value' => (float)$ing->numeratorvalue,
                                    'system' => 'http://unitsofmeasure.org',
                                    'code' => $ing->numeratorfk,
                                ),
                                'denominator' =>
                                array(
                                    'value' => (float)$ing->denominatorvalue,
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                    'code' => $ing->denominatorfk,
                                ),
                            ),
                        );
                    }
                }
                $tglkadaluarsa = '';
                $nobatch = '';
                foreach ($SPD as $d) {
                    if ($d->objectprodukfk == $item->idproduk) {
                        if ($d->nobatch != null) {
                            $nobatch = $d->nobatch;
                        }
                        if ($d->tglkadaluarsa != null) {
                            $tglkadaluarsa = date('Y-m-d', strtotime($d->tglkadaluarsa));
                        }
                    }
                }

                $uuidMedication = $item->urn_uuid_medication != null ? $item->urn_uuid_medication : substr(Uuid::generate(), 0, 36);
                $uuidMedicationDispense =   $item->ihs_medicationdispense != null ? $item->ihs_medicationdispense : substr(Uuid::generate(), 0, 36);
                $medication =  array(
                    'fullUrl' => 'urn:uuid:' . $uuidMedication,
                    'resource' =>
                    array(
                        'id' =>  $item->urn_uuid_medication,
                        'resourceType' => 'Medication',
                        'identifier' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://sys-ids.kemkes.go.id/medication/' . $profile->ihs_id,
                                'use' => 'official',
                                'value' => (string)$item->idproduk,
                            ),
                        ),
                        'code' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://sys-ids.kemkes.go.id/kfa',
                                    'code' => $medi_kodeKamusKFAobat,
                                    'display' => $medi_namaKamusKFAobat
                                ),
                            ),
                        ),
                        'status' => 'active',
                        'form' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'https://terminology.kemkes.go.id/CodeSystem/medication-form',
                                    'code' => $item->ihs_sediaan,
                                    'display' => $item->ihs_sediaan_display,
                                ),
                            ),
                        ),
                        'ingredient' =>
                        $ingredients,
                        'batch' =>
                        array(
                            'lotNumber' => $nobatch,
                            'expirationDate' => $tglkadaluarsa,
                        ),
                    ),
                    'request' =>
                    array(
                        'method' =>  $item->urn_uuid_medication != null ? 'PUT' : 'POST',
                        'url' => 'Medication',
                    ),
                );
                $medicationDispense =
                    array(
                        'fullUrl' => 'urn:uuid:' . $uuidMedicationDispense,
                        'resource' =>
                        array(
                            'id' =>  $item->ihs_medicationdispense,
                            'resourceType' => 'MedicationDispense',
                            'identifier' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://sys-ids.kemkes.go.id/medicationdispense/1000004',
                                    'value' =>   $item->noresep,
                                ),
                                1 =>
                                array(
                                    'system' => 'http://sys-ids.kemkes.go.id/medicationdispense-item/1000004',
                                    'value' => $item->noresep . '-' . ($k + 1),
                                ),
                            ),
                            'status' => 'completed',
                            'category' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://terminology.hl7.org/fhir/CodeSystem/medicationdispense-category',
                                        'code' => $item->code_class,
                                        'display' => $item->name_class,
                                    ),
                                ),
                            ),
                            'medicationReference' =>
                            array(
                                'reference' => 'urn:uuid:' . $uuidMedication,
                                'display' => $item->ihs_kfa_display != null ? $item->ihs_kfa_display : $item->namaproduk,
                            ),
                            'subject' =>
                            array(
                                'reference' => 'Group/PERSON-UNIDENTIFIED',
                                'display' =>   $item->namapasien_klien, //"PERSON-UNIDENTIFIED"
                            ),
                            'performer' =>
                            array(
                                0 =>
                                array(
                                    'actor' =>
                                    array(
                                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                                        'display' => $item->penulisresep,
                                    ),
                                ),
                                1 =>
                                array(
                                    'actor' =>
                                    array(
                                        'reference' => 'Organization/' . $profile->ihs_id,
                                    ),
                                ),
                            ),
                            'location' =>
                            array(
                                'reference' => 'Location/' . $item->ihs_apotik,
                                'display' =>  $item->ihs_apotik_display,
                            ),
                            'quantity' =>
                            array(
                                'value' => (float)$item->jumlah,
                                'unit' => $medi_DoseQtyunitUnit,
                                'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                'code' => $medi_DoseQtyunitCode,
                            ),
                            'whenPrepared' => $tglresep,
                            'whenHandedOver' => $tglresep,
                            'dosageInstruction' =>
                            array(
                                0 =>
                                array(
                                    'sequence' => 1,
                                    'text' => $item->aturanpakai,
                                    'timing' =>
                                    array(
                                        'repeat' =>
                                        array(
                                            'frequency' => $repeat_frequency,
                                            'period' => $repeat_period,
                                            'periodUnit' => 'd',
                                        ),
                                    ),
                                    'route' =>
                                    array(
                                        'coding' =>
                                        array(
                                            0 =>
                                            array(
                                                'system' => 'http://www.whocc.no/atc',
                                                'code' => 'O',
                                                'display' => 'Oral',
                                            ),
                                        ),
                                    ),
                                    'doseAndRate' =>
                                    array(
                                        0 =>
                                        array(
                                            'type' =>
                                            array(
                                                'coding' =>
                                                array(
                                                    0 =>
                                                    array(
                                                        'system' => 'http://terminology.hl7.org/CodeSystem/dose-rate-type',
                                                        'code' => 'ordered',
                                                        'display' => 'Ordered',
                                                    ),
                                                ),
                                            ),
                                            'doseQuantity' =>
                                            array(
                                                'value' => (float)$item->jumlah,
                                                'unit' => $medi_DoseQtyunitUnit,
                                                'system' => 'http://terminology.hl7.org/CodeSystem/v3-orderableDrugForm',
                                                'code' => $medi_DoseQtyunitCode,
                                            ),
                                        ),
                                    ),
                                ),
                            ),
                        ),
                        'request' =>
                        array(
                            'method' =>  $item->ihs_medicationdispense != null ? 'PUT' : 'POST',
                            'url' => 'MedicationDispense',
                        ),

                    );

                if ($item->ihs_medicationdispense == null) {
                    unset($medicationDispense['resource']['id']);
                }
                if ($item->urn_uuid_medication == null) {
                    unset($medication['resource']['id']);
                }

                $entry[] = $medication;
                $entry[] = $medicationDispense;
            }
            $data = array(
                'resourceType' => 'Bundle',
                'type' => 'transaction',
                'entry' => $entry
            );
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = '';
            $objetoRequest['method'] = 'POST';
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);
            // return response()->json($data);
            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;

                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }

            $responseBundle = '';
            foreach ($response->entry as $ddd) {
                if (isset($ddd->response->resourceID)) {
                    $responseBundle = $responseBundle . ',' . $ddd->response->resourceID;
                    StrukPelayananDetail::where('norec', $item->norec_pp)->update([
                        'ihs_id' => $ddd->response->resourceID,
                    ]);
                }
            }
            $responseBundle =  substr($responseBundle, 1, strlen($responseBundle) - 1);

            if ($q[0]->ihs_bundle == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $q[0]->ihs_bundle)->first();
            }
            if (empty($mod)) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = 'POST';
            $mod->id = $responseBundle;
            $mod->body = json_encode($data);
            $mod->response = json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Observation(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;

            $item = DB::connection('mongodb')
                ->table('VitalSign')
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $request['noregistrasi'])
                ->first();

            if (count($item) == 0) {
                $response = array(
                    "issue" => 'Observation belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }

            $profile = Profile::where('statusenabled', true)->first();
            $array = [];
            $pasien =  Pasien::where('id', $item['pasien']['nocmfk'])->first();
            $enc =  PasienDaftar::where('norec', $item['registrasi']['norec_pd'])->first();
            $pegawai =  Pegawai::where('id', $enc->objectpegawaifk)->first();

            if (isset($item['beratBadan'])) {
                $array[] = array(
                    'id' => isset($item['ihs_id_beratBadan']) ? $item['ihs_id_beratBadan'] : null,
                    'code' => '3141-9',
                    'display' => 'Weight',
                    'patient' => $pasien->ihs_number,
                    'patient_name' => $pasien->namapasien,
                    'encounter' => $enc->ihs_id,
                    'encounter_display' => 'Pemeriksaan Fisik Tinggi Badan ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                    'effectiveDateTime' => $item['tanggal'],
                    'unit' => 'kg',
                    'unitCode' => 'kg',
                    'value' => (float) $item['beratBadan'],
                    'norec' => $item['id'],
                    'tipe' => 'beratBadan'
                );
            }
            if (isset($item['tinggiBadan'])) {
                $array[] = array(
                    'id' => isset($item['ihs_id_tinggiBadan']) ? $item['ihs_id_tinggiBadan'] : null,
                    'code' => '8302-2',
                    'display' => 'Height',
                    'patient' => $pasien->ihs_number,
                    'patient_name' => $pasien->namapasien,
                    'encounter' => $enc->ihs_id,
                    'encounter_display' => 'Pemeriksaan Fisik Berat Badan ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                    'effectiveDateTime' => $item['tanggal'],
                    'unit' => 'cm',
                    'unitCode' => 'cm',
                    'value' => (float)$item['tinggiBadan'],
                    'norec' => $item['id'],
                    'tipe' => 'tinggiBadan'
                );
            }
            if (isset($item['pernapasan'])) {
                $array[] = array(
                    'id' => isset($item['ihs_id_pernapasan']) ? $item['ihs_id_pernapasan'] : null,
                    'code' => '9279-1',
                    'display' => 'Respiratory rate',
                    'patient' => $pasien->ihs_number,
                    'patient_name' => $pasien->namapasien,
                    'encounter' => $enc->ihs_id,
                    'encounter_display' => 'Pemeriksaan Fisik Pernafasan ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                    'effectiveDateTime' => $item['tanggal'],
                    'unit' => 'breaths/minute',
                    'unitCode' => '/min',
                    'value' => (float) $item['pernapasan'],
                    'norec' => $item['id'],
                    'tipe' => 'tinggiBadan'
                );
            }
            if (isset($item['tekananDarah'])) {
                $sis = explode('/', $item['tekananDarah']);
                if (count($sis) == 2) {
                    $array[] = array(
                        'id' => isset($item['ihs_id_tekananDarahSystolic']) ? $item['ihs_id_tekananDarahSystolic'] : null,

                        'code' => '8480-6',
                        'display' => 'Systolic blood pressure',
                        'patient' => $pasien->ihs_number,
                        'patient_name' => $pasien->namapasien,
                        'encounter' => $enc->ihs_id,
                        'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                        'effectiveDateTime' => $item['tanggal'],
                        'unit' => 'mm[Hg]',
                        'unitCode' => 'mm[Hg]',
                        'value' => (float) $sis[0],
                        'norec' => $item['id'],
                        'tipe' => 'tekananDarahSystolic'
                    );
                    $array[] = array(
                        'id' => isset($item['ihs_id_tekananDarahDiastolic']) ? $item['ihs_id_tekananDarahDiastolic'] : null,
                        'code' => '8462-4',
                        'display' => 'Diastolic blood pressure',
                        'patient' => $pasien->ihs_number,
                        'patient_name' => $pasien->namapasien,
                        'encounter' => $enc->ihs_id,
                        'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                        'effectiveDateTime' => $item['tanggal'],
                        'unit' => 'mm[Hg]',
                        'unitCode' => 'mm[Hg]',
                        'value' => (float) $sis[1],
                        'norec' => $item['id'],
                        'tipe' => 'tekananDarahDiastolic'
                    );
                } else {
                    $array[] = array(
                        'id' => isset($item['ihs_id_tekananDarah']) ? $item['ihs_id_tekananDarah'] : null,
                        'code' => '55284-4',
                        'display' => 'Blood pressure',
                        'patient' => $pasien->ihs_number,
                        'patient_name' => $pasien->namapasien,
                        'encounter' => $enc->ihs_id,
                        'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                        'effectiveDateTime' => $item['tanggal'],
                        'unit' => 'mm[Hg]',
                        'unitCode' => 'mm[Hg]',
                        'value' => (float) $item['tekananDarah'],
                        'norec' => $item['id'],
                        'tipe' => 'tekananDarah'
                    );
                }
            }
            if (isset($item['suhu'])) {
                $kode = '8310-5';
                $nama = 'Body temperature';
                $array[] = array(
                    'id' => isset($item['ihs_id_suhu']) ? $item['ihs_id_suhu'] : null,
                     'code' => $kode,
                    'display' => $nama,
                    'patient' => $pasien->ihs_number,
                    'patient_name' => $pasien->namapasien,
                    'encounter' => $enc->ihs_id,
                    'encounter_display' => 'Pemeriksaan Fisik Suhu ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                    'effectiveDateTime' => $item['tanggal'],
                    'unit' => 'C',
                    'unitCode' => 'Cel',
                    'value' => (float)$item['suhu'],
                    'norec' => $item['id'],
                    'tipe' => 'suhu'
                );
            }
            if (isset($item['nadi'])) {
                $kode = '8867-4';
                $nama = 'Heart rate';
                $array[] = array(
                    'id' => isset($item['ihs_id_nadi']) ? $item['ihs_id_nadi'] : null,
                    'code' => $kode,
                    'display' => $nama,
                    'patient' => $pasien->ihs_number,
                    'patient_name' => $pasien->namapasien,
                    'encounter' => $enc->ihs_id,
                    'encounter_display' => 'Pemeriksaan Fisik Nadi ' . $pasien->namapasien . ' di hari ' . $this->hari_ini($enc->tglregistrasi) . ', ' . $this->getDateIndo($enc->tglregistrasi),
                    'effectiveDateTime' => $item['tanggal'],
                    'unit' => 'beats/minute',
                    'unitCode' => '/min',
                    'value' => (float) $item['nadi'],
                    'norec' => $item['id'],
                    'tipe' => 'nadi'
                );
            }

            foreach ($array as $item) {
                $data = array(
                    'id' => $item['id'],
                    'resourceType' => 'Observation',
                    'status' => 'final',
                    "identifier" => [
                        array(
                            "system"=> "http://sys-ids.kemkes.go.id/observation/".$profile->ihs_id,
                            "value"=> $item['norec']
                        )
                    ],
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                    'code' => 'vital-signs',
                                    'display' => 'Vital Signs',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://loinc.org',
                                'code' => $item['code'],
                                'display' => $item['display'],
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item['patient'],
                        'display' => $item['patient_name'],
                    ),
                    "performer"=> array(
                        [ "reference"=> "Practitioner/".$pegawai->ihs_id]
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item['encounter'],
                        'display' => $item['encounter_display'],
                    ),
                    'effectiveDateTime' => $item['effectiveDateTime'],
                    'valueQuantity' =>
                    array(
                        'value' => $item['value'],
                        'unit' => $item['unit'],
                        'system' => 'http://unitsofmeasure.org',
                        'code' => $item['unitCode'],
                    ),
                );


                if ($data['id'] == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item['id'] == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                // if ($item['id'] == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
                // } else {
                //     $mod = IHS_Transaction::where('id', $item['id'])->first();
                // }
                // if (empty($mod)) {
                //     $mod = new IHS_Transaction();
                //     $mod->norec = $mod->generateNewId();
                //     $mod->statusenabled = $noterror;
                //     $mod->kdprofile = $this->kdProfile;
                // }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    DB::connection('mongodb')
                        ->table('VitalSign')
                        ->where('profile.kdprofile', $this->kdProfile)
                        ->where('statusenabled', true)
                        ->where('id', $item['norec'])
                        ->update([
                            'ihs_id_' . $item['tipe'] => $response->id,
                        ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Procedure(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    pd.noregistrasi,
                    pd.tglregistrasi,
                    apd.objectruanganfk,
                    ru.namaruangan,
                    apd.norec AS norec_apd,
                    ddt.objectdiagnosatindakanfk,
                    dt.kddiagnosatindakan,
                    dt.namadiagnosatindakan,
                    dtp.norec AS norec_diagnosapasien,
                    ddt.norec AS norec_detaildpasien,
                    dt.*,
                    ddt.keterangantindakan,
                    pg.namalengkap,
                    ddt.tglinputdiagnosa,
                    ddt.ihs_id as ihs_diagnosa,
                    ps.ihs_number,
                    ps.namapasien,
                    pd.ihs_id as ihs_encounter,
                    pg.ihs_id as ihs_practitioner
                FROM
                    pasiendaftar_t AS pd
                    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                    INNER JOIN diagnosatindakanpasien_t AS dtp ON dtp.objectpasienfk = apd.norec
                    INNER JOIN detaildiagnosatindakanpasien_t AS ddt ON ddt.objectdiagnosatindakanpasienfk = dtp.norec
                    INNER JOIN diagnosatindakan_m AS dt ON dt.id = ddt.objectdiagnosatindakanfk
                    LEFT JOIN pegawai_m AS pg ON pg.id = ddt.objectpegawaifk
                WHERE
                    pd.kdprofile = $kdProfile
                    and pd.noregistrasi ='$request[noregistrasi]'
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Procedure belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('statusenabled', true)->first();
            $array = [];
            foreach ($q as $k =>  $item) {
                $data = array(
                    'id' => $item->ihs_diagnosa,
                    'resourceType' => 'Procedure',
                    'status' => 'completed',
                    'category' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => '103693007',
                                'display' => 'Diagnostic procedure',
                            ),
                        ),
                        'text' => 'Diagnostic procedure',
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://hl7.org/fhir/sid/icd-9-cm',
                                'code' =>  $item->kddiagnosatindakan,
                                'display' => $item->namadiagnosatindakan,
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_number,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Tindakan ' . $item->keterangantindakan . ' ' . $item->namapasien . ' pada ' . $this->hari_ini($item->tglinputdiagnosa) . ', ' . $this->getDateIndo($item->tglinputdiagnosa),
                    ),
                    'performedPeriod' =>
                    array(
                        'start' => substr(date("Y-m-d\TH:i:s",  strtotime(date($item->tglinputdiagnosa))), 0, 23) . date('P',  strtotime(date($item->tglinputdiagnosa))),
                        'end' => substr(date("Y-m-d\TH:i:s",  strtotime(date($item->tglinputdiagnosa))), 0, 23) . date('P',  strtotime(date($item->tglinputdiagnosa))),
                    ),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'actor' =>
                            array(
                                'reference' => 'Practitioner/' . $item->ihs_practitioner,
                                'display' => $item->namalengkap,
                            ),
                        ),
                    ),
                    // 'reasonCode' =>
                    // array (
                    //   0 =>
                    //   array (
                    //     'coding' =>
                    //     array (
                    //       0 =>
                    //       array (
                    //         'system' => 'http://hl7.org/fhir/sid/icd-10',
                    //         'code' => 'A15.0',
                    //         'display' => 'Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
                    //       ),
                    //     ),
                    //   ),
                    // ),
                    // 'bodySite' =>
                    // array (
                    //   0 =>
                    //   array (
                    //     'coding' =>
                    //     array (
                    //       0 =>
                    //       array (
                    //         'system' => 'http://snomed.info/sct',
                    //         'code' => '302551006',
                    //         'display' => 'Entire Thorax',
                    //       ),
                    //     ),
                    //   ),
                    // ),
                    'note' =>
                    array(
                        0 =>
                        array(
                            'text' => $item->keterangantindakan,
                        ),
                    ),
                );

                if ($item->ihs_practitioner == null) {
                    unset($data['performer']);
                }
                if ($item->ihs_diagnosa == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_diagnosa == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);


                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                // if ($item->ihs_diagnosa == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
                // } else {
                //     $mod = IHS_Transaction::where('id', $item->ihs_diagnosa)->first();
                // }
                // if (empty($mod)) {
                //     $mod = new IHS_Transaction();
                //     $mod->norec = $mod->generateNewId();
                //     $mod->statusenabled = $noterror;
                //     $mod->kdprofile = $this->kdProfile;
                // }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    DetailDiagnosaTindakanPasien::where('norec', $item->norec_detaildpasien)->update([
                        'ihs_id' => $response->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function Composition(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    dtp.rencana as rekomendasi,
                    dtp.tanggalinput as tanggal,
                    ps.namapasien,
                    ps.ihs_number as ihs_pasien,
                    pd.ihs_id as ihs_encounter,
                    pd.tglregistrasi,
                    pg.namalengkap as dokter,
                    pg.ihs_id as ihs_practitioner,
                    dtp.ihs_id as ihs_composition,
                    dtp.norec,
                    dp.namadepartemen
                FROM
                    pasiendaftar_t AS pd
                    INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                    INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                    INNER JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
                    INNER JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                    INNER JOIN rencana_t AS dtp ON dtp.noregistrasifk = apd.norec
                    INNER JOIN pegawai_m AS pg ON pg.id = dtp.objectpetugas
                WHERE
                    pd.kdprofile = $kdProfile
                    and pd.noregistrasi ='$request[noregistrasi]'
                "));

            if (count($q) == 0) {
                $response = array(
                    "issue" => 'Procedure belum ada',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('statusenabled', true)->first();
            $array = [];
            foreach ($q as $k =>  $item) {
                $data = array(
                    'id' => $item->ihs_composition,
                    'resourceType' => 'Composition',
                    'identifier' =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/composition/' . $profile->ihs_id,
                        'value' => $item->norec,
                    ),
                    'status' => 'final',
                    'type' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://loinc.org',
                                'code' => '18842-5',
                                'display' => 'Discharge summary',
                            ),
                        ),
                    ),
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://loinc.org',
                                    'code' => 'LP173421-1',
                                    'display' => 'Report',
                                ),
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Kunjungan ' . $item->namapasien . ' di hari ' . $this->hari_ini($item->tanggal) . ', ' . $this->getDateIndo($item->tanggal),
                    ),
                    'date' => date('Y-m-d', strtotime($item->tanggal)),
                    'author' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_practitioner,
                            'display' => $item->dokter,
                        ),
                    ),
                    'title' => 'Resume Medis ' . (str_replace('Instalasi', '', $item->namadepartemen)),
                    'custodian' =>
                    array(
                        'reference' => 'Organization/' . $profile->ihs_id,
                    ),
                    'section' =>
                    array(
                        0 =>
                        array(
                            'code' =>
                            array(
                                'coding' =>
                                array(
                                    0 =>
                                    array(
                                        'system' => 'http://loinc.org',
                                        'code' => '42344-2',
                                        'display' => 'Discharge diet (narrative)',
                                    ),
                                ),
                            ),
                            'text' =>
                            array(
                                'status' => 'additional',
                                'div' => $item->rekomendasi,
                            ),
                        ),
                    ),
                );


                if ($item->ihs_composition == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_composition == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_composition == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_composition)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    Rencana::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function Immunization(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
            SELECT
            ps.ihs_number as ihs_pasien
            ,ps.namapasien
            ,pd.ihs_id as ihs_encounter
            ,pg.ihs_id as ihs_practitioner
            ,pg.namalengkap as dokterorder
            ,ru.namaruangan
            ,ru.ihs_id as ihs_location
            ,pd.noregistrasi


        FROM
             pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
            INNER JOIN pegawai_m AS pg ON pg.ID = pd.objectpegawaifk
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk

        WHERE
            pd.kdprofile = $kdProfile
            AND pd.noregistrasi = '$request[noregistrasi]';

       "))->first();

            $profile = Profile::where('statusenabled', true)->first();

            $data = array(
                'resourceType' => 'Immunization',
                'status' => 'entered-in-error',
                'vaccineCode' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/kfa',
                            'code' => '92000818',
                            'display' => 'Vaksin Diphteria Toxoid 20Lf / Tetanus Toxoid 5 Lf / Bordetella Pertusis 12 IU / Hepatitis B 10 ug / Haemophilus Influenza B 10 ug 0,5 mL',
                        ),
                    ),
                ),
                'patient' =>
                array(
                    'reference' => 'Patient/'.$q->ihs_pasien,
                    'display' =>$q->namapasien,
                ),
                'encounter' =>
                array(
                    'reference' => 'Encounter/'.$q->ihs_encounter,
                ),
                'occurrenceDateTime' => $this->dateISO(date('Y-m-d H:i:s')),
                'recorded' => $this->dateISO(date('Y-m-d H:i:s')),
                'primarySource' => true,
                'location' =>
                array(
                    'reference' => 'Location/'. $q->ihs_location,
                    'display' => $q->namaruangan,
                ),
                "reasonCode"=> [
                    array(
                        "coding"=> [
                            array(
                                "system"=> "http://terminology.kemkes.go.id/CodeSystem/immunization-reason",
                                "code"=> "IM-Dasar",
                                "display" => "Imunisasi Program Rutin Dasar"
                            ),
                            array(
                                "system"=>"http://terminology.kemkes.go.id/CodeSystem/immunization-routine-timing",
                                "code"=> "IM-Ideal",
                                "display" => "Imunisasi Ideal"
                            )
                        ]
                    )
                ],
                'lotNumber' => $q->noregistrasi,
                'route' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://www.whocc.no/atc',
                            'code' => 'inj.intramuscular',
                            'display' => 'Injection Intramuscular',
                        ),
                    ),
                ),
                'doseQuantity' =>
                array(
                    'value' => 1,
                    'unit' => 'mL',
                    'system' => 'http://unitsofmeasure.org',
                    'code' => 'ml',
                ),
                'performer' =>
                array(
                    0 =>
                    array(
                        'function' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0443',
                                    'code' => 'AP',
                                    'display' => 'Administering Provider',
                                ),
                            ),
                        ),
                        'actor' =>
                        array(
                            'reference' => 'Practitioner/'.$q->ihs_practitioner,
                        ),
                    ),
                ),
                'programEligibility' =>
                array(
                    0 =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'https://terminology.kemkes.go.id/CodeSystem/immunization-program-eligibility',
                                'code' => '1',
                                'display' => 'Diverifikasi',
                            ),
                        ),
                    ),
                ),
            );

            // if ($item->ihs_practitioner == null) {
            //     unset($data['performer']);
            // }
            // if ($item->ihs_diagnosa == null) {
            //     unset($data['id']);
            // }

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $data['resourceType'];
            // $objetoRequest['method'] = $item->ihs_diagnosa == null ? 'POST' : 'PUT';
            $objetoRequest['method'] = 'POST';
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);


            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;
            }

            // if ($item->ihs_diagnosa == null) {
            $mod = new IHS_Transaction();
            $mod->norec = $mod->generateNewId();
            $mod->statusenabled = $noterror;
            $mod->kdprofile = $this->kdProfile;
            // } else {
            //     $mod = IHS_Transaction::where('id', $item->ihs_diagnosa)->first();
            // }

            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = 'POST';
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response = json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                // DetailDiagnosaTindakanPasien::where('norec', $item->norec_detaildpasien)->update([
                //     'ihs_id' => $response->id,
                // ]);
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function ResourceType($resource, Request $request, $lokal = null)
    {
        try {
            $response = $this->$resource($request, $lokal);
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function ServiceRequest(Request $request,  $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                so.noorder
                ,so.tglorder
                ,pr.namaproduk
                ,op.objectprodukfk
                ,pr.ihs_loinc_id
                ,pr.ihs_loinc_common_name
                ,ps.ihs_number as ihs_pasien
                ,ps.namapasien
                ,pd.ihs_id as ihs_encounter
                ,pg.ihs_id as ihs_practitioner
                ,pg.namalengkap as dokterorder
                ,ru.namaruangan
                ,ru.ihs_id as ihs_ruangan
                ,so.keteranganlainnya
                ,pg2.ihs_id as ihs_performer
                ,pg2.namalengkap as nama_performer
                ,op.ihs_id as ihs_idforput
                ,op.norec
                ,ro.accession_num
            FROM
                strukorder_t AS so
                INNER JOIN orderpelayanan_t AS op ON op.strukorderfk = so.norec
                INNER JOIN pasiendaftar_t AS pd ON so.noregistrasifk = pd.norec
                INNER JOIN produk_m AS pr ON pr.ID = op.objectprodukfk
                INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
                INNER JOIN pegawai_m AS pg ON pg.ID = so.objectpegawaiorderfk
                INNER JOIN ruangan_m AS ru ON ru.ID = so.objectruanganfk
                LEFT JOIN pegawai_m AS pg2 ON pg2.ID = pd.objectpegawaifk
                LEFT JOIN ris_order as ro ON  ro.order_no = so.noorder and ro.order_code = cast( op.objectprodukfk as text)
            WHERE
                pd.kdprofile = $kdProfile
                AND so.noorder = '$request[noorder]';

           "));



            if (count($q) == 0) {
                $response = array(
                    "issue" =>  ' ServiceRequest doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();

            foreach ($q as $k =>  $item) {
                $assen = [];

                if (!empty($item->accession_num)) {
                    $assen = array(
                        "use" => "usual",
                        "type" => array(
                            "coding" =>
                            array(
                                0 => array(
                                    "system" => "http://terminology.hl7.org/CodeSystem/v2-0203",
                                    "code" => "ACSN"
                                )
                            )
                        ),
                        "system" => "http://sys-ids.kemkes.go.id/acsn/" . $profile->ihs_id,
                        "value" => $item->accession_num
                    );
                }

                // dd($assen);
                $data  = array(
                    'resourceType' => 'ServiceRequest',
                    'id' => $item->ihs_idforput,
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/servicerequest/' . $profile->ihs_id,
                            'value' => $item->noorder,
                        ),
                        $assen
                    ),
                    'status' => 'active',
                    'intent' => 'original-order',
                    'priority' => 'routine',
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => stripos($item->ihs_loinc_id, '-') !== FALSE ? 'http://loinc.org' : "http://snomed.info/sct",
                                'code' =>  $item->ihs_loinc_id ? $item->ihs_loinc_id : '',
                                'display' => $item->ihs_loinc_common_name ? $item->ihs_loinc_common_name : '',
                            ),
                        ),
                        'text' => $item->namaproduk
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' =>  $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Permintaan ' . $item->namaproduk . ' ' . $item->namapasien . ' di hari ' . $this->hari_ini($item->tglorder) . ', ' . $this->getDateIndo($item->tglorder) . ' ' . $this->getTime($item->tglorder),
                    ),
                    'occurrenceDateTime' => $this->dateISO($item->tglorder),
                    'authoredOn' => $this->dateISO($item->tglorder),
                    'requester' =>
                    array(
                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        'display' => $item->dokterorder,
                    ),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_performer,
                            'display' => $item->nama_performer,
                        ),
                    ),
                    'reasonCode' =>
                    array(
                        0 =>
                        array(
                            'text' => $item->keteranganlainnya ? $item->keteranganlainnya : '',
                        ),
                    ),
                );


                if ($item->ihs_idforput == null) {
                    unset($data['id']);
                }
                if (count($data['identifier'][1]) == 0) {
                    unset($data['identifier'][1]);
                }

                // dd($data);
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_idforput == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    OrderPelayanan::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id,
                        'ihs_specimen' => null,

                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    function dateISO($date)
    {
        $iso =  strtotime(date($date));
        $iso =  substr(date("Y-m-d\TH:i:s", $iso), 0, 23) . date('P', $iso);
        return  $iso;
    }
    function getTime($date)
    {
        return  date('H:i', strtotime(date($date)));
    }
    public function Specimen(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                so.noorder
                ,so.tglorder
                ,pr.namaproduk
                ,op.objectprodukfk
                ,pr.ihs_loinc_id
                ,pr.ihs_loinc_common_name
                ,ps.ihs_number as ihs_pasien
                ,ps.namapasien
                ,pd.ihs_id as ihs_encounter
                ,pg.ihs_id as ihs_practitioner
                ,pg.namalengkap as dokterorder
                ,ru.namaruangan
                ,ru.ihs_id as ihs_ruangan
                ,so.keteranganlainnya
                ,pg2.ihs_id as ihs_performer
                ,pg2.namalengkap as nama_performer
                ,op.ihs_specimen as ihs_idforput
                ,op.ihs_id as ihs_service_req
                ,op.norec
            FROM
                strukorder_t AS so
                INNER JOIN orderpelayanan_t AS op ON op.strukorderfk = so.norec
                INNER JOIN pasiendaftar_t AS pd ON so.noregistrasifk = pd.norec
                INNER JOIN produk_m AS pr ON pr.ID = op.objectprodukfk
                INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
                INNER JOIN pegawai_m AS pg ON pg.ID = so.objectpegawaiorderfk
                INNER JOIN ruangan_m AS ru ON ru.ID = so.objectruanganfk
                LEFT JOIN pegawai_m AS pg2 ON pg2.ID = pd.objectpegawaifk
            WHERE
                pd.kdprofile = $kdProfile
                AND so.noorder = '$request[noorder]';

           "));



            if (count($q) == 0) {
                $response = array(
                    "issue" =>  'Specimen doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            foreach ($q as $k =>  $item) {

                $data  = array(
                    'resourceType' => 'Specimen',
                    'id' => $item->ihs_idforput,
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/specimen/' . $profile->ihs_id,
                            'value' => '00001',
                            'assigner' =>
                            array(
                                'reference' => 'Organization/' . $profile->ihs_id,
                            ),
                        ),
                    ),
                    // 'status' => 'available',
                    'status' => 'unavailable',
                    'type' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => '260414001',
                                'display' => 'Nothing (qualifier value)',
                                // 'code' => '119297000',
                                // 'display' => 'Blood specimen',
                            ),
                        ),
                    ),
                    'collection' =>
                    array(
                        'method' =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://snomed.info/sct',
                                    // "code" => "82078001",
                                    // "display" => "Collection of blood specimen for laboratory"
                                    'code' => '281325009',
                                    'display' => 'No specimen collection time given (finding)',
                                ),
                            ),
                        ),
                        'collectedDateTime' =>  $this->dateISO($item->tglorder),
                        'collector' =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->namapasien,
                    ),
                    'request' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'ServiceRequest/' . $item->ihs_service_req,
                        ),
                    ),
                    'receivedTime' =>  $this->dateISO($item->tglorder),
                );



                if ($item->ihs_idforput == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_idforput == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    OrderPelayanan::where('ihs_id', $item->ihs_service_req)->update([
                        'ihs_specimen' => $response->id,

                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function ObservationRad(Request $request,  $lokal = null)
    {

        $kdProfile = $this->kdProfile;

        $q = collect(DB::select("
            select  so.tglorder,so.noorder, op.norec as norecopfk,
            pr.id, pr.namaproduk, op.qtyproduk,
            so.norec, pp.norec as norec_pp, hr.norec as norec_hr,
            hr.keterangan as expertise,
            op.ihs_id as ihs_service_request
            ,ps.ihs_number as ihs_pasien
            ,ps.namapasien
            ,ro.imaging_id as ihs_imaging_id
            ,ro.ihs_diagnosticreport
            ,ro.ihs_observation as ihs_id
            ,pd.ihs_id as ihs_encounter
            ,pr.ihs_loinc_id
            ,pr.ihs_loinc_common_name
            ,hr.tanggal
            ,p.ihs_id as ihs_practitioner
            from strukorder_t as so
            inner join orderpelayanan_t as op on op.noorderfk = so.norec  and (op.keteranganlainnya<>'laboratorium' or op.keteranganlainnya is null )
            left join pelayananpasien_t as pp on pp.strukorderfk = so.norec  and pp.produkfk=op.objectprodukfk
            left join hasilradiologi_t as hr on hr.pelayananpasienfk = pp.norec
            inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
            inner join pasien_m as ps on ps.id=pd.nocmfk
            inner join produk_m as pr on pr.id=op.objectprodukfk
            left join pegawai_m as p on p.id=so.objectpegawaiorderfk
            LEFT JOIN ris_order as ro ON  ro.order_no = so.noorder and ro.order_code = cast( op.objectprodukfk as text)
            where so.kdprofile = $kdProfile
            and pd.statusenabled=true
            and so.keteranganorder =  'Order Radiologi'
            and so.objectkelompoktransaksifk=94
            and so.statusenabled=true
            and  so.noorder = '$request[noorder]'
            "));

        try {

            if (count($q) == 0) {
                $response = array(
                    "issue" =>   'Observation doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            foreach ($q as $k =>  $item) {
                $data = array(
                    'resourceType' => 'Observation',
                    'id' => $item->ihs_id,
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/observation/' . $profile->ihs_id,
                            'value' => $item->norec_pp,
                        ),
                    ),
                    'status' => 'final',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                    'code' => 'imaging',
                                    'display' => 'Imaging',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => stripos($item->ihs_loinc_id, '-') !== FALSE ? 'http://loinc.org' : "http://snomed.info/sct",
                                'code' => $item->ihs_loinc_id,
                                'display' => $item->ihs_loinc_common_name,
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Observasi hasil' . $item->ihs_loinc_common_name . ' ' . $item->namapasien . ' di hari ' . $this->hari_ini($item->tanggal) . ', ' . $this->getDateIndo($item->tanggal),

                    ),
                    'effectiveDateTime' => date('Y-m-d', ($item->tanggal ? strtotime($item->tanggal) : date('Y-m-d'))),
                    'issued' => $this->dateISO(($item->tanggal ? strtotime($item->tanggal) : date('Y-m-d'))),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        ),
                        1 =>
                        array(
                            'reference' => 'Organization/' . $profile->ihs_id,
                        ),
                    ),
                    'basedOn' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'ServiceRequest/' . $item->ihs_service_request,
                        ),
                    ),
                    'bodySite' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => 80581009,
                                'display' => 'Upper abdomen structure',
                            ),
                        ),
                    ),
                    'derivedFrom' =>
                    array(
                        'reference' => 'ImagingStudy/' . $item->ihs_imaging_id,
                    ),
                );

                // if ($item->ihs_idforput == null) {
                unset($data['id']);
                // }
                // dd($data);

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';

                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_id == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_id)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    RisOrder::where('norec', $item->norec_hr)->update([
                        'ihs_observation' => $response->id,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function ObservationLab(Request $request,  $lokal = null)
    {

        $kdProfile = $this->kdProfile;

        // $pasien = DB::table('strukorder_t as so')
        // ->join('pasiendaftar_t as pd','pd.norec','=','so.noregistrasifk')
        // ->join('pasien_m as ps','ps.id','=','pd.nocmfk')
        // ->select('ps.objectjeniskelaminfk','ps.tgllahir','pd.tglregistrasi')
        // ->first();
        // $fdate = $pasien->tgllahir;
        // $tdate = $pasien->tglregistrasi;
        // $datetime1 = new \DateTime($fdate);
        // $datetime2 = new \DateTime($tdate);
        // $interval = $datetime1->diff($datetime2);
        // $days = $interval->format('%a');//now do whatever you like with $days

        // $umur =$days ;
        // $jk = $pasien->objectjeniskelaminfk;
        $q = collect(DB::select("
                SELECT
                st.noorder,
                st.tglorder,
                hh.tglhasil,
                pr.namaproduk,
                hh.produkfk,
                pr.ihs_loinc_id,
                pr.ihs_loinc_common_name,
                ps.ihs_number AS ihs_pasien,
                ps.namapasien,
                pd.ihs_id AS ihs_encounter,
                hh.pegawaifk ,
                pg.ihs_id AS ihs_practitioner,
                pg.namalengkap AS dokterorder,
                hh.ihs_id AS ihs_idforput,
                hh.norec,
                op.ihs_specimen,
                op.ihs_id as ihs_service_req,
                hh.nilainormal,
                hh.hasil,
                hh.satuan,
                hh.flag,
                hh.loinc_id,
                hh.loinc_name
            FROM
                hasillaboratorium_t AS hh
                LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = hh.noregistrasifk
                LEFT JOIN strukorder_t st ON st.norec = apd.objectstrukorderfk
                LEFT JOIN orderpelayanan_t op ON op.strukorderfk = st.norec
                --and op.objectprodukfk = hh.produkfk
                LEFT JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                LEFT JOIN produk_m AS pr ON pr.ID = hh.produkfk
                LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
                LEFT JOIN pegawai_m AS pg ON pg.ID = hh.pegawaifk
            WHERE
            hh.kdprofile = $kdProfile
            and  hh.norec = '$request[norec_hasil]'
                    "));
        // $q = collect(DB::select("
        //     SELECT
        //     distinct
        //     st.noorder,
        //     st.tglorder,
        //     hh.tglhasil,
        //     pr.namaproduk,
        //     hh.produkfk,
        //     pr.ihs_loinc_id,
        //     pr.ihs_loinc_common_name,
        //     ps.ihs_number AS ihs_pasien,
        //     ps.namapasien,
        //     pd.ihs_id AS ihs_encounter,
        //     hh.pegawaifk ,
        //     pg.ihs_id AS ihs_practitioner,
        //     pg.namalengkap AS dokterorder,
        //     hh.ihs_id AS ihs_idforput,
        //     hh.norec,
        //     op.ihs_specimen,
        //     op.ihs_id as ihs_service_req,
        //     hh.nilainormal,
        //     hh.hasil,
        //     hh.satuan,
        //     hh.flag
        //     ,maps.loinc_id
        //     ,maps.loinc_name

        //     FROM
        //     strukorder_t as st

        //     LEFT JOIN pasiendaftar_t AS pd ON pd.norec = st.noregistrasifk
        //         LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
        //         LEFT JOIN hasillaboratorium_t AS hh ON hh.norec = apd.objectstrukorderfk
        //         LEFT JOIN orderpelayanan_t op ON op.strukorderfk = st.norec
        //         --and op.objectprodukfk = hh.produkfk
        //         LEFT JOIN produk_m AS pr ON pr.ID = op.objectprodukfk
        //         LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
        //         LEFT JOIN pegawai_m AS pg ON pg.ID = hh.pegawaifk
        //         LEFT join maphasillab_m  as maps on maps.produkfk = pr.id
        //         LEFT join maphasillabdetail_m  as maps2 on maps2.maphasilfk = maps.id
        //         and maps2.jeniskelaminfk =$jk
        //         and maps2.kelompokumurfk in (select id from kelompokumur_m  kuu where $umur BETWEEN kuu.umurmin and kuu.umurmax)
        //         LEFT join nilainormal_m  as nn on nn.id = maps2.nilainormalfk
        //     WHERE
        //     st.kdprofile = $kdProfile
        //     and  st.noorder = '$request[noorder]'


        //         "));
        // dd($q);
        try {

            if (count($q) == 0) {
                $response = array(
                    "issue" =>   'Observation doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            foreach ($q as $k =>  $item) {
                $codeFlag = '';
                $displayFlag = '';
                if ($item->flag == 'N') {
                    $codeFlag = 'N';
                    $displayFlag = 'Normal';
                }
                if ($item->flag == 'H') {
                    $codeFlag = 'H';
                    $displayFlag = 'High';
                }
                if ($item->flag == 'L') {
                    $codeFlag = 'L';
                    $displayFlag = 'Low';
                }
                if ($item->flag == 'HH') {
                    $codeFlag = 'HH';
                    $displayFlag = 'Critical high';
                }
                if ($item->flag == 'LL') {
                    $codeFlag = 'LL';
                    $displayFlag = 'Critical Low';
                }
                if ($item->flag == 'Y') {
                    $codeFlag = 'A';
                    $displayFlag = 'Abnormal';
                }
                $data = array(
                    'resourceType' => 'Observation',
                    'id' => $item->ihs_idforput,
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/observation/' . $profile->ihs_id,
                            'value' => $item->norec,
                        ),
                    ),
                    'status' => 'final',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                    'code' => 'laboratory',
                                    'display' => 'Laboratory',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://loinc.org',
                                'code' => $item->loinc_id != null ? $item->loinc_id : $item->ihs_loinc_id,
                                'display' => $item->loinc_name != null ? $item->loinc_name : $item->ihs_loinc_common_name,
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Observasi hasil' . $item->ihs_loinc_common_name . ' ' . $item->namapasien . ' di hari ' . $this->hari_ini($item->tglhasil) . ', ' . $this->getDateIndo($item->tglhasil),

                    ),
                    'effectiveDateTime' => date('Y-m-d', strtotime($item->tglhasil)),
                    'issued' => $this->dateISO($item->tglhasil),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        ),
                        1 =>
                        array(
                            'reference' => 'Organization/' . $profile->ihs_id,
                        ),
                    ),
                    'specimen' =>
                    array(
                        'reference' => 'Specimen/' . $item->ihs_specimen,
                    ),
                    'basedOn' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'ServiceRequest/' . $item->ihs_service_req,
                        ),
                    ),
                    // 'valueCodeableConcept' =>
                    // array (
                    //   'coding' =>
                    //   array (
                    //     0 =>
                    //     array (
                    //       'system' => 'http://snomed.info/sct',
                    //       'code' => '260347006',
                    //       'display' => '+',
                    //     ),
                    //   ),
                    // ),
                    "valueQuantity" => array(
                        "value" =>  (float)$item->hasil,
                        "unit" => $item->satuan,
                        "system" =>  "http://unitsofmeasure.org",
                        "code" => $item->satuan,
                    ),
                    "interpretation" => [
                        array(
                            "coding" => [
                                array(
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                                    "code" => $codeFlag,
                                    "display" => $displayFlag
                                ),

                            ]
                        )
                    ],
                    'referenceRange' =>
                    array(
                        0 =>
                        array(
                            'text' => $item->nilainormal . ' ' . $item->satuan,
                        ),
                    ),
                );

                // if ($item->ihs_idforput == null) {
                unset($data['id']);
                // }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';

                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_idforput == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    HasilLaboratorium::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id,

                        'ihs_diagnosticreport' => null,
                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function DiagnosticReport(Request $request,  $lokal = null)
    {
        try {

            $kdProfile = $this->kdProfile;

            $params = '';
            foreach ($request['norec_arr'] as $d) {
                $params = $params . "','" . $d;
            }
            $params = substr($params, 2, strlen($params) - 2) . "'";


            $q = collect(DB::select("
                SELECT
                st.noorder,
                st.tglorder,
                hh.tglhasil,
                pr.namaproduk,
                hh.produkfk,
                pr.ihs_loinc_id,
                pr.ihs_loinc_common_name,
                ps.ihs_number AS ihs_pasien,
                ps.namapasien,
                pd.ihs_id AS ihs_encounter,
                hh.pegawaifk ,
                pg.ihs_id AS ihs_practitioner,
                pg.namalengkap AS dokterorder,
                hh.ihs_diagnosticreport AS ihs_idforput,
                hh.ihs_id AS ihs_observation,
                hh.norec,
                op.ihs_specimen,
                op.ihs_id as ihs_service_req,
                hh.nilainormal,
                hh.hasil,
                hh.satuan,
                hh.flag
            FROM
                hasillaboratorium_t AS hh
                LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = hh.noregistrasifk
                LEFT JOIN strukorder_t st ON st.norec = apd.objectstrukorderfk
                LEFT JOIN orderpelayanan_t op ON op.strukorderfk = st.norec

                --and op.objectprodukfk = hh.produkfk
                LEFT JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                LEFT JOIN produk_m AS pr ON pr.ID = hh.produkfk
                LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
                LEFT JOIN pegawai_m AS pg ON pg.ID = hh.pegawaifk
            WHERE
            hh.kdprofile = $kdProfile
            and  hh.norec  in ($params)"));



            if (count($q) == 0) {
                $response = array(
                    "issue" =>  'DiagnosticReport doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            $codeFlag = '';
            $displayFlag = '';
            $arr_result_ob = [];
            foreach ($q as $k =>  $item) {

                if ($item->flag == 'N') {
                    $codeFlag = 'N';
                    $displayFlag = ' Normal';
                }
                if ($item->flag == 'H') {
                    $codeFlag = 'H';
                    $displayFlag  = ' High';
                }
                if ($item->flag == 'L') {
                    $codeFlag = 'L';
                    $displayFlag = ' Low';
                }
                if ($item->flag == 'HH') {
                    $codeFlag = 'HH';
                    $displayFlag  = ' Critical high';
                }
                if ($item->flag == 'LL') {
                    $codeFlag = 'LL';
                    $displayFlag  = ' Critical Low';
                }
                if ($item->flag == 'Y') {
                    $codeFlag = 'A';
                    $displayFlag  = ' Abnormal';
                }
                $arr_result_ob[] =
                    array(
                        // "id" => $k + 1,
                        'reference' => 'Observation/' . $item->ihs_observation,
                    );
            }
            foreach ($arr_result_ob as $ddd) {
                // $ddd['id'] =(string) $ddd['id'] ;
            }

            $data = array(
                'resourceType' => 'DiagnosticReport',
                'id' => $item->ihs_idforput,
                'identifier' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/diagnostic/' . $profile->ihs_id . '/lab',
                        'use' => 'official',
                        'value' => $item->norec,
                    ),
                ),
                'status' => 'final',
                'category' =>
                array(
                    0 =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/v2-0074',
                                // 'code' => 'MB',
                                // 'display' => 'Microbiology',
                                'code' => 'LAB',
                                'display' => 'Laboratory',
                            ),
                        ),
                    ),
                ),
                'code' =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://loinc.org',
                            'code' => $item->ihs_loinc_id,
                            'display' => $item->ihs_loinc_common_name,
                        ),
                    ),
                ),
                'subject' =>
                array(
                    'reference' => 'Patient/' . $item->ihs_pasien,
                    'display' => $item->namapasien,
                ),
                'encounter' =>
                array(
                    'reference' => 'Encounter/' . $item->ihs_encounter,
                ),
                'effectiveDateTime' => date('Y-m-d', strtotime($item->tglhasil)),
                'issued' => $this->dateISO($item->tglhasil),
                'performer' =>
                array(
                    0 =>
                    array(
                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                    ),
                    1 =>
                    array(
                        'reference' => 'Organization/' . $profile->ihs_id,
                    ),
                ),
                'result' =>
                $arr_result_ob,
                'specimen' =>
                array(
                    0 =>
                    array(
                        'reference' => 'Specimen/' . $item->ihs_specimen,
                    ),
                ),
                'basedOn' =>
                array(
                    0 =>
                    array(
                        'reference' => 'ServiceRequest/' . $item->ihs_service_req,
                    ),
                ),
                // 'conclusionCode' =>
                // array(
                //     0 =>
                //     array(
                //         'coding' =>
                //         array(
                //             0 =>
                //             array(
                //                 'system' => "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                //                 'code' => $codeFlag,
                //                 'display' => $displayFlag,
                //             ),
                //         ),
                //     ),
                // ),
                'conclusion' => $displayFlag

            );
            // dd($data);


            // if ($item->ihs_idforput == null) {
            unset($data['id']);
            // }

            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $data['resourceType'];
            // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
            $objetoRequest['method'] = 'POST';
            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);

            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;
            }

            if ($item->ihs_idforput == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
            }
            if (empty($mod)) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = 'POST';
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response = json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                HasilLaboratorium::where('norec', $item->norec)->update([
                    'ihs_diagnosticreport' => $response->id,

                ]);
            }
            // }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function ObservationLabDiagnos(Request $request,  $lokal = null)
    {

        $kdProfile = $this->kdProfile;

        $q = collect(DB::select("select
            res.his_reg_no AS visit_trans_id,  res.lis_test_id AS tarif_id,
            res.test_name AS examination_name, res.result AS result_value,  res.test_units_name AS unit,
            res.reference_value AS normal_value,  res.result_comment AS metode,   res.test_group AS treatment_name,
            res.sequence AS urut, orz.patient_id AS rm_number,  res.authorization_date AS visit_date,    res.his_test_id,
            res.test_name AS tarif_name,  res.test_flag_sign AS flag ,res.authorization_user as analis ,

            pd.ihs_id AS ihs_encounter,
            ps.ihs_number AS ihs_pasien,
            pg.ihs_id AS ihs_practitioner,
            op.ihs_specimen,
            op.ihs_id as ihs_service_req,
            res.ihs_id AS ihs_idforput,
            res.loinc_id,
            res.loinc_name,
            res.lis_reg_no,
            pr.ihs_loinc_id,
            pr.ihs_loinc_common_name,
             ps.namapasien
            from result_bridge as res
            join order_bridge as orz on orz.order_number=res.his_reg_no
            LEFT JOIN strukorder_t st ON st.noorder = res.his_reg_no
            LEFT JOIN orderpelayanan_t op ON op.strukorderfk = st.norec
            LEFT JOIN pasiendaftar_t AS pd ON pd.norec = st.noregistrasifk
            LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
            LEFT JOIN produk_m AS pr ON pr.ID = res.his_test_id
            LEFT JOIN pegawai_m AS pg ON cast(pg.ID  as text)= orz.doctor_id
            where res.his_reg_no= '$request[noorder]'"))->toArray();

        // $q = collect(DB::select("
        //         SELECT
        //         st.noorder,
        //         st.tglorder,
        //         hh.tglhasil,
        //         pr.namaproduk,
        //         hh.produkfk,
        //         pr.ihs_loinc_id,
        //         pr.ihs_loinc_common_name,
        //         ps.ihs_number AS ihs_pasien,
        //         ps.namapasien,
        //         pd.ihs_id AS ihs_encounter,
        //         hh.pegawaifk ,
        //         pg.ihs_id AS ihs_practitioner,
        //         pg.namalengkap AS dokterorder,
        //         hh.ihs_id AS ihs_idforput,
        //         hh.norec,
        //         op.ihs_specimen,
        //         op.ihs_id as ihs_service_req,
        //         hh.nilainormal,
        //         hh.hasil,
        //         hh.satuan,
        //         hh.flag,
        //         hh.loinc_id,
        //         hh.loinc_name
        //     FROM
        //         hasillaboratorium_t AS hh
        //         LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = hh.noregistrasifk
        //         LEFT JOIN strukorder_t st ON st.norec = apd.objectstrukorderfk
        //         LEFT JOIN orderpelayanan_t op ON op.strukorderfk = st.norec
        //         --and op.objectprodukfk = hh.produkfk
        //         LEFT JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        //         LEFT JOIN produk_m AS pr ON pr.ID = hh.produkfk
        //         LEFT JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
        //         LEFT JOIN pegawai_m AS pg ON pg.ID = hh.pegawaifk
        //     WHERE
        //     hh.kdprofile = $kdProfile
        //     and  hh.norec = '$request[norec_hasil]'
        //             "));

        try {

            if (count($q) == 0) {
                $response = array(
                    "issue" =>   'Observation doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            $displayFlag = '';
            $arr_result_ob = [];
            foreach ($q as $k =>  $item) {
                $codeFlag = '';
                $displayFlag = '';
                if ($item->flag == 'N') {
                    $codeFlag = 'N';
                    $displayFlag = 'Normal';
                }
                if ($item->flag == 'H') {
                    $codeFlag = 'H';
                    $displayFlag = 'High';
                }
                if ($item->flag == 'L') {
                    $codeFlag = 'L';
                    $displayFlag = 'Low';
                }
                if ($item->flag == 'HH') {
                    $codeFlag = 'HH';
                    $displayFlag = 'Critical high';
                }
                if ($item->flag == 'LL') {
                    $codeFlag = 'LL';
                    $displayFlag = 'Critical Low';
                }
                if ($item->flag == 'Y') {
                    $codeFlag = 'A';
                    $displayFlag = 'Abnormal';
                }
                $data = array(
                    'resourceType' => 'Observation',
                    'id' => $item->ihs_idforput,
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/observation/' . $profile->ihs_id,
                            'value' => $item->lis_reg_no,
                        ),
                    ),
                    'status' => 'final',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                                    'code' => 'laboratory',
                                    'display' => 'Laboratory',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://loinc.org',
                                'code' => $item->loinc_id != null ? $item->loinc_id : $item->ihs_loinc_id,
                                'display' => $item->loinc_name != null ? $item->loinc_name : $item->ihs_loinc_common_name,
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Observasi hasil' . $item->ihs_loinc_common_name . ' ' . $item->namapasien . ' di hari ' . $this->hari_ini($item->visit_date) . ', ' . $this->getDateIndo($item->visit_date),

                    ),
                    'effectiveDateTime' => date('Y-m-d', strtotime($item->visit_date)),
                    'issued' => $this->dateISO($item->visit_date),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        ),
                        1 =>
                        array(
                            'reference' => 'Organization/' . $profile->ihs_id,
                        ),
                    ),
                    'specimen' =>
                    array(
                        'reference' => 'Specimen/' . $item->ihs_specimen,
                    ),
                    'basedOn' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'ServiceRequest/' . $item->ihs_service_req,
                        ),
                    ),
                    // 'valueCodeableConcept' =>
                    // array (
                    //   'coding' =>
                    //   array (
                    //     0 =>
                    //     array (
                    //       'system' => 'http://snomed.info/sct',
                    //       'code' => '260347006',
                    //       'display' => '+',
                    //     ),
                    //   ),
                    // ),
                    "valueQuantity" => array(
                        "value" =>  (float)$item->result_value,
                        "unit" => $item->unit,
                        "system" =>  "http://unitsofmeasure.org",
                        "code" => $item->unit,
                    ),
                    "interpretation" => [
                        array(
                            "coding" => [
                                array(
                                    "system" => "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                                    "code" => $codeFlag,
                                    "display" => $displayFlag
                                ),

                            ]
                        )
                    ],
                    'referenceRange' =>
                    array(
                        0 =>
                        array(
                            'text' => $item->normal_value . ' ' . $item->unit,
                        ),
                    ),
                );
                // dd($data);

                // if ($item->ihs_idforput == null) {
                unset($data['id']);
                // }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';

                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_idforput == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    DB::table('result_bridge')->where('lis_reg_no', $item->lis_reg_no)->update([
                        'ihs_id' => $response->id,
                        'ihs_diagnosticreport' => null,
                    ]);
                }

                $data = array(
                    'resourceType' => 'DiagnosticReport',
                    'id' => $item->ihs_idforput,
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/diagnostic/' . $profile->ihs_id . '/lab',
                            'use' => 'official',
                            'value' => $item->lis_reg_no,
                        ),
                    ),
                    'status' => 'final',
                    'category' =>
                    array(
                        0 =>
                        array(
                            'coding' =>
                            array(
                                0 =>
                                array(
                                    'system' => 'http://terminology.hl7.org/CodeSystem/v2-0074',
                                    // 'code' => 'MB',
                                    // 'display' => 'Microbiology',
                                    'code' => 'LAB',
                                    'display' => 'Laboratory',
                                ),
                            ),
                        ),
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://loinc.org',
                                'code' => $item->ihs_loinc_id,
                                'display' => $item->ihs_loinc_common_name,
                            ),
                        ),
                    ),
                    'subject' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->namapasien,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                    ),
                    'effectiveDateTime' => date('Y-m-d', strtotime($item->visit_date)),
                    'issued' => $this->dateISO($item->visit_date),
                    'performer' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Practitioner/' . $item->ihs_practitioner,
                        ),
                        1 =>
                        array(
                            'reference' => 'Organization/' . $profile->ihs_id,
                        ),
                    ),
                    'result' =>
                    $arr_result_ob,
                    'specimen' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'Specimen/' . $item->ihs_specimen,
                        ),
                    ),
                    'basedOn' =>
                    array(
                        0 =>
                        array(
                            'reference' => 'ServiceRequest/' . $item->ihs_service_req,
                        ),
                    ),
                    // 'conclusionCode' =>
                    // array(
                    //     0 =>
                    //     array(
                    //         'coding' =>
                    //         array(
                    //             0 =>
                    //             array(
                    //                 'system' => "http://terminology.hl7.org/CodeSystem/v3-ObservationInterpretation",
                    //                 'code' => $codeFlag,
                    //                 'display' => $displayFlag,
                    //             ),
                    //         ),
                    //     ),
                    // ),
                    'conclusion' => $displayFlag

                );
                // dd($data);


                // if ($item->ihs_idforput == null) {
                unset($data['id']);
                // }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_idforput == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    DB::table('result_bridge')->where('lis_reg_no', $item->lis_reg_no)->update([
                        'ihs_diagnosticreport' => $response->id,

                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function AllergyIntolerance(Request $request,  $lokal = null)
    {

        $kdProfile = $this->kdProfile;

        try {
            $q =  collect(DB::select("

            SELECT
                pap.*,
                al.namaalergi,
                pg.namalengkap ,
                ps.namapasien as given,
                pap.ihs_id  as ihs_idforput,
                ps.ihs_number AS ihs_pasien
                ,pd.ihs_id as ihs_encounter
                ,pd.tglregistrasi
                , pg.ihs_id  as ihs_practitioner
                ,al.kodesnomed
                ,pap.kategori
            FROM
                papalergi_t AS pap
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = pap.objectpasienfk
                INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
                INNER JOIN alergi_m AS al ON al.id = pap.objectalergifk
                INNER JOIN pegawai_m AS pg ON pap.pegawaifk = pg.id
            WHERE
                pap.kdprofile = $kdProfile
                AND pap.statusenabled = true
                AND pd.noregistrasi = '$request[noregistrasi]'

          "));

            if (count($q) == 0) {
                $response = array(
                    "issue" =>   'AllergyIntolerance doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            $displayFlag = '';
            $arr_result_ob = [];

            foreach ($q as $k =>  $item) {
                $tglemr =  strtotime(date($item->tglinput));
                $tglemr =  substr(date("Y-m-d\TH:i:s", $tglemr), 0, 23) . date('P', $tglemr);
                //   $idAL = $this->generateCodeBySeqTable(new PapAlergi, 'alergi_satusehat', 10, date('Ym'), $kdProfile);
                $data =  array(
                    'id' => $item->ihs_idforput,
                    'resourceType' => 'AllergyIntolerance',
                    'identifier' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://sys-ids.kemkes.go.id/allergy/' . $profile->ihs_id,
                            'use' => 'official',
                            'value' => $item->norec,
                        ),
                    ),
                    'clinicalStatus' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-clinical',
                                'code' => 'active',
                                'display' => 'Active',
                            ),
                        ),
                    ),
                    'verificationStatus' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/allergyintolerance-verification',
                                'code' => 'confirmed',
                                'display' => 'Confirmed',
                            ),
                        ),
                    ),
                    'category' =>
                    array(
                        0 =>  $item->kategori != null ? $item->kategori : $item->namaalergi,
                    ),
                    'code' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://snomed.info/sct',
                                'code' => (string)$item->kodesnomed,
                                'display' =>  $item->namaalergi,
                            ),
                        ),
                        'text' => $item->keterangandata,
                    ),
                    'patient' =>
                    array(
                        'reference' => 'Patient/' . $item->ihs_pasien,
                        'display' => $item->given,
                    ),
                    'encounter' =>
                    array(
                        'reference' => 'Encounter/' . $item->ihs_encounter,
                        'display' => 'Kunjungan ' . $item->given . ' di hari ' . $this->hari_ini($item->tglregistrasi) . ', ' . $this->getDateIndo($item->tglregistrasi),

                    ),
                    'recordedDate' => $tglemr, //'2022-06-14T15:37:31+07:00',
                    'recorder' =>
                    array(
                        'reference' => 'Practitioner/' . $item->ihs_practitioner,
                    ),
                );


                // if ($item->ihs_idforput == null) {
                unset($data['id']);
                // }


                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
                $objetoRequest['method'] = 'POST';

                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_idforput == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = 'POST';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    DB::table('papalergi_t')->where('norec', $item->norec)->update([
                        'ihs_id' => $response->id,

                    ]);
                }
            }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function ClinicalImpression(Request $request,  $lokal = null)
    {

        //prognosis
        $kdProfile = $this->kdProfile;
        try {

            $item = collect(DB::select(
                "
                select
                pd.norec,
                ps.ihs_number as ihs_pasien,
                pd.ihs_id as ihs_encounter,
                ps.namapasien,
                pd.prognosis,
                pd.tglregistrasi,pd.noregistrasi,
                pd.ihs_id as ihs_encounter
                ,pg.ihs_id as ihs_practitioner
                ,pg.namalengkap as dokter
                ,pd.prognosiscodeableconcept_kode
                ,pd.prognosiscodeableconcept
                ,pd.tglpulang
                ,pd.ihs_clinicalimpression as ihs_idforput
                from pasiendaftar_t as pd
                join pasien_m as ps on ps.id = pd.nocmfk
                left join pegawai_m as pg on pg.id = pd.objectpegawaifk
                where pd.kdprofile = $kdProfile
                and pd.noregistrasi = '$request[noregistrasi]'
                "
            ))->first();
            if (empty($item)) {
                $response = array(
                    "issue" =>   'ClinicalImpression doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();
            $diagnosis =  collect(DB::select("
                SELECT
                ps.ihs_number
                ,ps.namapasien
                ,pd.noregistrasi
                ,pd.ihs_id as ihs_encounter
                ,pd.ihs_condition as conditionhead
                ,dg.kddiagnosa
                ,dg.namadiagnosa
                ,pd.noregistrasi
                ,ddp.ihs_id as ihs_condition
                ,ddp.norec

                FROM pasiendaftar_t as pd
                join pasien_m as ps on ps.id=pd.nocmfk
                join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                JOIN detaildiagnosapasien_t as ddp on ddp.noregistrasifk=apd.norec
                join diagnosa_m as dg on dg.id=ddp.objectdiagnosafk
                where pd.noregistrasi='$request[noregistrasi]'
            "));
            $diagnosCond = [];
            foreach ($diagnosis as $dd) {
                $diagnosCond[]  =  array(
                    'itemCodeableConcept' =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://hl7.org/fhir/sid/icd-10',
                                'code' => $dd->kddiagnosa,
                                'display' => $dd->namadiagnosa,
                            ),
                        ),
                    ),
                    'itemReference' =>
                    array(
                        'reference' => 'Condition/' . $dd->ihs_condition,
                    ),
                );
            }



            $tgl =  strtotime(date($item->tglpulang));
            $tgl =  substr(date("Y-m-d\TH:i:s", $tgl), 0, 23) . date('P', $tgl);
            $data = array(
                'resourceType' => 'ClinicalImpression',
                'identifier' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://sys-ids.kemkes.go.id/clinicalimpression/' . $profile->ihs_id,
                        'use' => 'official',
                        'value' => $item->norec,
                    ),
                ),
                'status' => 'completed',
                'description' => $item->prognosis,
                'subject' =>
                array(
                    'reference' => 'Patient/' . $item->ihs_pasien,
                    'display' => $item->namapasien,
                ),
                'encounter' =>
                array(
                    'reference' => 'Encounter/' . $item->ihs_encounter,
                    'display' => 'Kunjungan ' . $item->namapasien . ' di hari ' . $this->hari_ini($item->tglregistrasi) . ', ' . $this->getDateIndo($item->tglregistrasi),

                ),
                'effectiveDateTime' => $tgl,
                'date' => $tgl,
                'assessor' =>
                array(
                    'reference' => 'Practitioner/' . $item->ihs_practitioner,
                ),
                // 'problem' =>
                // array (
                //   0 =>
                //   array (
                //     'reference' => 'Condition/f2bc12fe-0ab2-4e5c-a3cd-32c66150cbe9',
                //   ),
                // ),
                // 'investigation' =>
                // array (
                //   0 =>
                //   array (
                //     'code' =>
                //     array (
                //       'text' => 'Pemeriksaan Sputum BTA',
                //     ),
                //     'item' =>
                //     array (
                //       0 =>
                //       array (
                //         'reference' => 'DiagnosticReport/a0fa6244-7638-43ba-bbc2-2af954761540',
                //       ),
                //       1 =>
                //       array (
                //         'reference' => 'Observation/56819f05-28b9-43c2-b0d1-3785768aa886',
                //       ),
                //     ),
                //   ),
                // ),
                'summary' =>  $item->prognosis,
                'finding' => $diagnosCond,
                'prognosisCodeableConcept' =>
                array(
                    0 =>
                    array(
                        'coding' =>
                        array(
                            0 =>
                            array(
                                'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                'code' => $item->prognosiscodeableconcept_kode,
                                'display' => $item->prognosiscodeableconcept,
                            ),
                        ),
                    ),
                ),
            );



            // if ($item->ihs_idforput == null) {
            unset($data['id']);
            // }
            if (count($diagnosCond) == 0) {
                unset($data['finding']);
            }
            //    dd($data);



            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['url'] = $data['resourceType'];
            // $objetoRequest['method'] = $data->ihs_idforput == null ? 'POST' : 'PUT';
            $objetoRequest['method'] = 'POST';

            $objetoRequest['data'] = $data;

            $response =  $this->ihsTools($objetoRequest, true);

            $noterror = true;
            if ($response->resourceType == 'OperationOutcome') {
                $noterror = false;
            }

            if ($item->ihs_idforput == null) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            } else {
                $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
            }
            if (empty($mod)) {
                $mod = new IHS_Transaction();
                $mod->norec = $mod->generateNewId();
                $mod->statusenabled = $noterror;
                $mod->kdprofile = $this->kdProfile;
            }
            $mod->resourcetype = $data['resourceType'];
            $mod->url =  $this->endPoint() . $data['resourceType'];
            $mod->method = 'POST';
            $mod->id = isset($response->id) ? $response->id : null;
            $mod->body = json_encode($data);
            $mod->response = json_encode($response);
            $mod->date = date('Y-m-d H:i:s');
            $mod->save();
            if (isset($response->id)) {
                DB::table('pasiendaftar_t')->where('norec', $item->norec)->update([
                    'ihs_clinicalimpression' => $response->id,

                ]);
            }


            //     }
        } catch (\Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function ObservationKesadaran(Request $request,  $lokal = null)
    {

        //prognosis

        $kdProfile = $this->kdProfile;

        // try {

        // $q =[];
        // if (count($q) == 0) {
        //     $response = array(
        //         "issue" =>   'ClinicalImpression doesnt exist',
        //         "resourceType" => "OperationOutcome"
        //     );
        //     if ($lokal == true) {
        //         return $response;
        //     }
        //     return response()->json($response);
        // }
        $profile = Profile::where('id', $kdProfile)->first();
        // $displayFlag = '';
        // $arr_result_ob = [];

        // foreach ($q as $k =>  $item) {
        $response = array(
            'resourceType' => 'Observation',
            'status' => 'final',
            'category' =>
            array(
                0 =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://terminology.hl7.org/CodeSystem/observation-category',
                            'code' => 'exam',
                            'display' => 'Exam',
                        ),
                    ),
                ),
            ),
            'code' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://loinc.org',
                        'code' => '67775-7',
                        'display' => 'Level of responsiveness',
                    ),
                ),
            ),
            'subject' =>
            array(
                'reference' => 'Patient/100000030009',
            ),
            'performer' =>
            array(
                0 =>
                array(
                    'reference' => 'Practitioner/N10000001',
                ),
            ),
            'encounter' =>
            array(
                'reference' => 'Encounter/2823ed1d-3e3e-434e-9a5b-9c579d192787',
                'display' => 'Pemeriksaan Kesadaran Budi Santoso di hari Selasa, 14 Juni 2022',
            ),
            'effectiveDateTime' => '2022-07-14',
            'valueCodeableConcept' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                        'code' => 'TK000001',
                        'display' => 'Alert',
                    ),
                ),
            ),
        );


        //         // if ($item->ihs_idforput == null) {
        //         unset($data['id']);
        //         // }


        //         $objetoRequest = new \Illuminate\Http\Request();
        //         $objetoRequest['url'] = $data['resourceType'];
        //         // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
        //         $objetoRequest['method'] = 'POST';

        //         $objetoRequest['data'] = $data;

        //         $response =  $this->ihsTools($objetoRequest, true);

        //         $noterror = true;
        //         if ($response->resourceType == 'OperationOutcome') {
        //             $noterror = false;
        //         }

        //         if ($item->ihs_idforput == null) {
        //             $mod = new IHS_Transaction();
        //             $mod->norec = $mod->generateNewId();
        //             $mod->statusenabled = $noterror;
        //             $mod->kdprofile = $this->kdProfile;
        //         } else {
        //             $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
        //         }
        //         if (empty($mod)) {
        //             $mod = new IHS_Transaction();
        //             $mod->norec = $mod->generateNewId();
        //             $mod->statusenabled = $noterror;
        //             $mod->kdprofile = $this->kdProfile;
        //         }
        //         $mod->resourcetype = $data['resourceType'];
        //         $mod->url =  $this->endPoint() . $data['resourceType'];
        //         $mod->method = 'POST';
        //         $mod->id = isset($response->id) ? $response->id : null;
        //         $mod->body = json_encode($data);
        //         $mod->response = json_encode($response);
        //         $mod->date = date('Y-m-d H:i:s');
        //         $mod->save();
        //         if (isset($response->id)) {
        //             DB::table('papalergi_t')->where('norec', $item->norec)->update([
        //                 'ihs_id' => $response->id,

        //             ]);
        //         }


        //     }
        // } catch (\Exception $e) {
        //     $response = array(
        //         "issue" => $e->getMessage() . ' ' . $e->getLine(),
        //         "resourceType" => "OperationOutcome"
        //     );
        // }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function ProcedureEdukasi(Request $request,  $lokal = null)
    {



        $kdProfile = $this->kdProfile;

        // try {

        // $q =[];
        // if (count($q) == 0) {
        //     $response = array(
        //         "issue" =>   'ClinicalImpression doesnt exist',
        //         "resourceType" => "OperationOutcome"
        //     );
        //     if ($lokal == true) {
        //         return $response;
        //     }
        //     return response()->json($response);
        // }
        $profile = Profile::where('id', $kdProfile)->first();
        // $displayFlag = '';
        // $arr_result_ob = [];

        // foreach ($q as $k =>  $item) {
        $response = array(
            'resourceType' => 'Procedure',
            'status' => 'completed',
            'category' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://snomed.info/sct',
                        'code' => '409073007',
                        'display' => 'Education',
                    ),
                ),
                'text' => 'Education',
            ),
            'code' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                        'code' => 'ED000002',
                        'display' => 'Edukasi obat-obatan',
                    ),
                ),
            ),
            'subject' =>
            array(
                'reference' => 'Patient/100000030009',
                'display' => 'Budi Santoso',
            ),
            'encounter' =>
            array(
                'reference' => 'Encounter/2823ed1d-3e3e-434e-9a5b-9c579d192787',
                'display' => 'Edukasi minum obat OAT rutin kepada Budi Santoso di hari Selasa, 14 Juni 2022',
            ),
            'performedPeriod' =>
            array(
                'start' => '2022-06-14T13:31:00+01:00',
                'end' => '2022-06-14T14:27:00+01:00',
            ),
            'performer' =>
            array(
                0 =>
                array(
                    'actor' =>
                    array(
                        'reference' => 'Practitioner/N10000001',
                        'display' => 'Dokter Bronsig',
                    ),
                ),
            ),
            'reasonCode' =>
            array(
                0 =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://hl7.org/fhir/sid/icd-10',
                            'code' => 'A15.0',
                            'display' => 'Tuberculosis of lung, confirmed by sputum microscopy with or without culture',
                        ),
                    ),
                ),
            ),
            'note' =>
            array(
                0 =>
                array(
                    'text' => 'Edukasi minum OAT teratur.',
                ),
            ),
        );


        //         // if ($item->ihs_idforput == null) {
        //         unset($data['id']);
        //         // }


        //         $objetoRequest = new \Illuminate\Http\Request();
        //         $objetoRequest['url'] = $data['resourceType'];
        //         // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
        //         $objetoRequest['method'] = 'POST';

        //         $objetoRequest['data'] = $data;

        //         $response =  $this->ihsTools($objetoRequest, true);

        //         $noterror = true;
        //         if ($response->resourceType == 'OperationOutcome') {
        //             $noterror = false;
        //         }

        //         if ($item->ihs_idforput == null) {
        //             $mod = new IHS_Transaction();
        //             $mod->norec = $mod->generateNewId();
        //             $mod->statusenabled = $noterror;
        //             $mod->kdprofile = $this->kdProfile;
        //         } else {
        //             $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
        //         }
        //         if (empty($mod)) {
        //             $mod = new IHS_Transaction();
        //             $mod->norec = $mod->generateNewId();
        //             $mod->statusenabled = $noterror;
        //             $mod->kdprofile = $this->kdProfile;
        //         }
        //         $mod->resourcetype = $data['resourceType'];
        //         $mod->url =  $this->endPoint() . $data['resourceType'];
        //         $mod->method = 'POST';
        //         $mod->id = isset($response->id) ? $response->id : null;
        //         $mod->body = json_encode($data);
        //         $mod->response = json_encode($response);
        //         $mod->date = date('Y-m-d H:i:s');
        //         $mod->save();
        //         if (isset($response->id)) {
        //             DB::table('papalergi_t')->where('norec', $item->norec)->update([
        //                 'ihs_id' => $response->id,

        //             ]);
        //         }


        //     }
        // } catch (\Exception $e) {
        //     $response = array(
        //         "issue" => $e->getMessage() . ' ' . $e->getLine(),
        //         "resourceType" => "OperationOutcome"
        //     );
        // }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function ConditionSaatMeninggalkanRS(Request $request,  $lokal = null)
    {



        $kdProfile = $this->kdProfile;

        // try {

        // $q =[];
        // if (count($q) == 0) {
        //     $response = array(
        //         "issue" =>   'ClinicalImpression doesnt exist',
        //         "resourceType" => "OperationOutcome"
        //     );
        //     if ($lokal == true) {
        //         return $response;
        //     }
        //     return response()->json($response);
        // }
        $profile = Profile::where('id', $kdProfile)->first();
        // $displayFlag = '';
        // $arr_result_ob = [];

        // foreach ($q as $k =>  $item) {
        $response = array(
            'resourceType' => 'Condition',
            'clinicalStatus' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://terminology.hl7.org/CodeSystem/condition-clinical',
                        'code' => 'active',
                        'display' => 'Active',
                    ),
                ),
            ),
            'category' =>
            array(
                0 =>
                array(
                    'coding' =>
                    array(
                        0 =>
                        array(
                            'system' => 'http://terminology.hl7.org/CodeSystem/condition-category',
                            'code' => 'problem-list-item',
                            'display' => 'Problem List Item',
                        ),
                    ),
                ),
            ),
            'code' =>
            array(
                'coding' =>
                array(
                    0 =>
                    array(
                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                        'code' => 'MN000001',
                        'display' => 'Stabil',
                    ),
                ),
            ),
            'subject' =>
            array(
                'reference' => 'Patient/100000030009',
                'display' => 'Budi Santoso',
            ),
            'encounter' =>
            array(
                'reference' => 'Encounter/2823ed1d-3e3e-434e-9a5b-9c579d192787',
                'display' => 'Kunjungan Budi Santoso di hari Selasa, 14 Juni 2022',
            ),
        );


        //         // if ($item->ihs_idforput == null) {
        //         unset($data['id']);
        //         // }


        //         $objetoRequest = new \Illuminate\Http\Request();
        //         $objetoRequest['url'] = $data['resourceType'];
        //         // $objetoRequest['method'] = $item->ihs_idforput == null ? 'POST' : 'PUT';
        //         $objetoRequest['method'] = 'POST';

        //         $objetoRequest['data'] = $data;

        //         $response =  $this->ihsTools($objetoRequest, true);

        //         $noterror = true;
        //         if ($response->resourceType == 'OperationOutcome') {
        //             $noterror = false;
        //         }

        //         if ($item->ihs_idforput == null) {
        //             $mod = new IHS_Transaction();
        //             $mod->norec = $mod->generateNewId();
        //             $mod->statusenabled = $noterror;
        //             $mod->kdprofile = $this->kdProfile;
        //         } else {
        //             $mod = IHS_Transaction::where('id', $item->ihs_idforput)->first();
        //         }
        //         if (empty($mod)) {
        //             $mod = new IHS_Transaction();
        //             $mod->norec = $mod->generateNewId();
        //             $mod->statusenabled = $noterror;
        //             $mod->kdprofile = $this->kdProfile;
        //         }
        //         $mod->resourcetype = $data['resourceType'];
        //         $mod->url =  $this->endPoint() . $data['resourceType'];
        //         $mod->method = 'POST';
        //         $mod->id = isset($response->id) ? $response->id : null;
        //         $mod->body = json_encode($data);
        //         $mod->response = json_encode($response);
        //         $mod->date = date('Y-m-d H:i:s');
        //         $mod->save();
        //         if (isset($response->id)) {
        //             DB::table('papalergi_t')->where('norec', $item->norec)->update([
        //                 'ihs_id' => $response->id,

        //             ]);
        //         }


        //     }
        // } catch (\Exception $e) {
        //     $response = array(
        //         "issue" => $e->getMessage() . ' ' . $e->getLine(),
        //         "resourceType" => "OperationOutcome"
        //     );
        // }
        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
    public function EncounterList(Request $r)
    {

        $data =  DB::table('pasiendaftar_t')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->select('noregistrasi', 'tglregistrasi')
            ->whereRaw("to_char(tglregistrasi,'yyyy-MM-dd') <='$r[dari]'")
            ->whereRaw("to_char(tglregistrasi,'yyyy-MM-dd') >='$r[sampai]'")
            ->get();
        return $data;
    }

    public function curlAPI($headers, $dataJsonSend, $url, $methods)
    {
        if (empty($dataJsonSend)) {
            $response = Http::withHeaders($headers)
                ->{$methods}($url);
        } else {
            $response = Http::withHeaders($headers)
                ->{$methods}($url, $dataJsonSend);
        }
        // dd($response);

        // $res = null;
        // if ($response->ok()) {
        //     $res = $response->json();
        // }
        return $response;
    }
    function getSetting()
    {
        $res = [
            'base_url_IHS' => '',
            'auth_url_IHS' => '',
            'organization_id_IHS' => '',
            'client_id_IHS' => '',
            'client_secret_IHS' => '',
        ];

        $data = DB::table('settingdatafixed_m')->where('kdprofile', session('kdProfile'))
            ->select('namafield', 'nilaifield')
            ->where('statusenabled', true)
            ->whereIn('kelompok', ['SATUSEHAT'])
            ->get();


        foreach ($data as $v) {
            foreach (array_keys($res) as $v2) {
                if ($v->namafield == $v2) {
                    $res[$v->namafield] = $v->nilaifield;
                }
            }
        }

        return $this->respond($res);
    }
    public function getListRegis(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->where('pd.kdprofile', '=', $this->kdProfile)
            ->where('pd.statusenabled', true);

        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where('pd.tglregistrasi', '>=', $request['dari'] . ' 00:00:00');
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where('pd.tglregistrasi', '<=', $request['sampai'] . ' 23:59:59');
        }
        if (isset($request['condition']) && $request['condition'] != '') {
            $data = $data ->join('detaildiagnosapasien_t as dg', 'dg.noregistrasi', '=', 'pd.noregistrasi');
            $data = $data ->join('diagnosa_m as dm', 'dg.objectdiagnosafk', '=', 'dm.id');
            $data = $data->select('pd.noregistrasi','pd.ihs_id','dg.ihs_id as ihs_condition','dm.kddiagnosa');
            $data = $data ->whereNotNull('pd.ihs_id');
            $data = $data ->whereNotNull('pd.objectpegawaifk');
            $data = $data ->whereNull('dg.ihs_id');
        }else{
            $data = $data->select('pd.noregistrasi','pd.ihs_id');
            $data = $data ->whereNull('pd.ihs_id');
            $data = $data ->whereNotNull('pd.objectpegawaifk');
        }
        $data = $data->orderby('pd.tglregistrasi');
        $data = $data->get();


        return $this->respond($data);

    }
    public function getListRegisObservation(Request $request)
    {


        $data = DB::table('pasiendaftar_t as pd')
            ->where('pd.kdprofile', '=', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->select('pd.noregistrasi','pd.ihs_id')
            ->whereNotNull('pd.ihs_id')
            ->whereNotNull('pd.objectpegawaifk');

        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where('pd.tglregistrasi', '>=', $request['dari'] . ' 00:00:00');
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where('pd.tglregistrasi', '<=', $request['sampai'] . ' 23:59:59');
        }

        $data = $data->orderby('pd.tglregistrasi');
        $data = $data->get();

        $item = DB::connection('mongodb')
        ->table('VitalSign')
        ->where('profile.kdprofile', $this->kdProfile)
        ->where('statusenabled', true);
        if (isset($request['dari']) && $request['dari'] != '') {
            $item = $item->where('registrasi.tglregistrasi', '>=', $request['dari'] . ' 00:00:00');
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $item = $item->where('registrasi.tglregistrasi', '<=', $request['sampai'] . ' 23:59:59');
        }
        $item = $item->get();
        $data2 = [];
        foreach($data as $d){
            foreach($item as $dd){
                if($d->noregistrasi == $dd['registrasi']['noregistrasi']
                ){
                    if( isset($dd['ihs_id_beratBadan'])
                    ||  isset($dd['ihs_id_tinggiBadan'])
                    ||  isset($dd['ihs_id_tekananDarahSystolic'])
                    ||  isset($dd['ihs_id_pernapasan'])
                    ||  isset($dd['ihs_id_tekananDarahDiastolic'])
                    ||  isset($dd['ihs_id_tekananDarah'])
                    ||  isset($dd['ihs_id_suhu'])
                    ||  isset($dd['ihs_id_nadi'])){
                        break;
                    }else{
                        $data2[] = $d;
                    }
                }
            }
        }


        $filteredArray = [];
        foreach ($data2 as $item) {
            $ihsId = $item->noregistrasi;
            $filteredArray[$ihsId] = $item;
        }

        $finalArray = array_values($filteredArray);


        return $this->respond($finalArray);

    }

    public function MedicationStatement(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    pp.norec,
                    pp.ihs_medicationstatement AS ihs_ms,
                    pp.tglpelayanan,
                    CASE WHEN pp.keteranganpakai IS NOT NULL AND pp.keteranganpakai <> '' THEN pp.keteranganpakai ELSE 'Diminum 4-6 jam sekali' END AS keteranganpakai,
                    pr.namaproduk AS ihs_medication_name,
                    pr.ihs_id AS ihs_medication_id,
                    ps.namapasien AS ihs_pasien_name,
                    ps.ihs_number AS ihs_pasien_id,
                    pd.norec AS norec_pd,
                    pd.ihs_id AS ihs_encounter
                FROM strukresep_t AS sr
                INNER JOIN pelayananpasien_t AS pp ON pp.strukresepfk = sr.norec
                INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = pp.noregistrasifk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                WHERE sr.kdprofile = $kdProfile
                AND sr.noresep = '$request[noresep]'
                AND pr.ihs_id IS NOT NULL
            "));

            if (count($q) == 0) {
                $response = array(
                    "issue" =>  'MedicationStatement doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();

            foreach ($q as $key => $item) {
                $data = array(
                    'resourceType' => 'MedicationStatement',
                    'id' => $item->ihs_ms,
                    'status' => 'completed',
                    'category' => array(
                        'coding' => array(
                            0 => array(
                                'system' => 'http://terminology.hl7.org/CodeSystem/medication-statement-category',
                                'code' => 'outpatient',
                                'display' => 'Outpatient'
                            )
                        )
                    ),
                    'medicationReference' => array(
                        'reference' => 'Medication/' . $item->ihs_medication_id,
                        'display' => $item->ihs_medication_name
                    ),
                    'subject' => array(
                        'reference' => 'Patient/' . $item->ihs_pasien_id,
                        'display' => $item->ihs_pasien_name
                    ),
                    'dosage' => array(
                        0 => array(
                            'text' => $item->keteranganpakai,
                            'timing' => array(
                                'repeat' => array(
                                    'frequency' => 1,
                                    'period' => 4,
                                    'periodMax' => 6,
                                    'periodUnit' => 'h'
                                )
                            )
                        )
                    ),
                    'effectiveDateTime' => date('Y-m-d\TH:i:sP', strtotime($item->tglpelayanan)),
                    'dateAsserted' => date('Y-m-d\TH:i:sP'),
                    'informationSource' => array(
                        'reference' => 'Patient/' . $item->ihs_pasien_id,
                        'display' => $item->ihs_pasien_name
                    ),
                    'context' => array(
                        'reference' => 'Encounter/' . $item->ihs_encounter
                    )
                );

                if ($item->ihs_ms == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_ms == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_ms == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_ms)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = $item->ihs_ms == null ? 'POST' : 'PUT';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    PelayananPasien::where('norec', $item->norec)->update([
                        'ihs_medicationstatement' => $response->id,
                    ]);
                }
            }
        } catch (Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }

        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function QuestionnaireResponse(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    aa.*,
                    pd.ihs_id AS ihs_encounter,
                    ps.ihs_number AS ihs_pasien_id,
                    ps.namapasien AS ihs_pasien_name,
                    pg.ihs_id AS ihs_practioner_id,
                    pg.namalengkap AS ihs_practioner_name
                FROM antrianapotik_t AS aa
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = aa.noregistrasifk
                INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
                INNER JOIN pegawai_m AS pg ON pg.id = pd.objectpegawaifk
                WHERE aa.kdprofile = $kdProfile
                AND aa.noregistrasifk = '$request[noregistrasifk]'
            "));

            if (count($q) == 0) {
                $response = array(
                    "issue" =>  'QuestionnaireResponse doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();

            foreach ($q as $key => $item) {
                $data = array(
                    'resourceType' => 'QuestionnaireResponse',
                    'id' => $item->ihs_id,
                    'questionnaire' => 'https://fhir.kemkes.go.id/Questionnaire/Q0007',
                    'status' => 'completed',
                    'subject' => array(
                        'reference' => 'Patient/' . $item->ihs_pasien_id,
                        'display' => $item->ihs_pasien_name
                    ),
                    'encounter' => array(
                        'reference' => 'Encounter/' . $item->ihs_encounter
                    ),
                    'authored' => date('Y-m-d\TH:i:sP', strtotime($item->tglresep)),
                    'author' => array(
                        'reference' => 'Practitioner/' . $item->ihs_practioner_id,
                        'display' => $item->ihs_practioner_name
                    ),
                    'source' => array(
                        'reference' => 'Patient/' . $item->ihs_pasien_id
                    ),
                    'item' => array(
                        0 => array(
                            'linkId' => '1',
                            'text' => 'Persyaratan Administrasi',
                            'item' => array(
                                0 => array(
                                    'linkId' => '1.1',
                                    'text' => 'Apakah nama, umur, jenis kelamin, berat badan dan tinggi badan pasien sudah sesuai?',
                                    'answer' => array(
                                        0 => array(
                                            'valueCoding' => array(
                                                'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                'code' => 'OV000052',
                                                'display' => $item->persyaratanadministrasi1
                                            )
                                        )
                                    )
                                ),
                                1 => array(
                                    'linkId' => '1.2',
                                    'text' => 'Apakah nama, nomor ijin, alamat dan paraf dokter sudah sesuai?',
                                    'answer' => array(
                                        0 => array(
                                            'valueCoding' => array(
                                                'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                'code' => 'OV000052',
                                                'display' => $item->persyaratanadministrasi2
                                            )
                                        )
                                    )
                                ),
                                2 => array(
                                    'linkId' => '1.3',
                                    'text' => 'Apakah tanggal resep sudah sesuai?',
                                    'answer' => array(
                                        0 => array(
                                            'valueCoding' => array(
                                                'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                'code' => 'OV000052',
                                                'display' => $item->persyaratanadministrasi3
                                            )
                                        )
                                    )
                                ),
                                3 => array(
                                    'linkId' => '1.4',
                                    'text' => 'Apakah ruangan/unit asal resep sudah sesuai?',
                                    'answer' => array(
                                        0 => array(
                                            'valueCoding' => array(
                                                'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                'code' => 'OV000052',
                                                'display' => $item->persyaratanadministrasi4
                                            )
                                        )
                                    )
                                ),
                                4 => array(
                                    'linkId' => '2',
                                    'text' => 'Persyaratan Farmasetik',
                                    'item' => array(
                                        0 => array(
                                            'linkId' => '2.1',
                                            'text' => 'Apakah nama obat, bentuk dan kekuatan sediaan sudah sesuai?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueCoding' => array(
                                                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                        'code' => 'OV000052',
                                                        'display' => $item->persyaratanfarmasetik1
                                                    )
                                                )
                                            )
                                        ),
                                        1 => array(
                                            'linkId' => '2.2',
                                            'text' => 'Apakah dosis dan jumlah obat sudah sesuai?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueCoding' => array(
                                                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                        'code' => 'OV000052',
                                                        'display' => $item->persyaratanfarmasetik2
                                                    )
                                                )
                                            )
                                        ),
                                        2 => array(
                                            'linkId' => '2.3',
                                            'text' => 'Apakah stabilitas obat sudah sesuai?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueCoding' => array(
                                                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                        'code' => 'OV000052',
                                                        'display' => $item->persyaratanfarmasetik3
                                                    )
                                                )
                                            )
                                        ),
                                        3 => array(
                                            'linkId' => '2.4',
                                            'text' => 'Apakah aturan dan cara penggunaan obat sudah sesuai?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueCoding' => array(
                                                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                        'code' => 'OV000052',
                                                        'display' => $item->persyaratanfarmasetik4
                                                    )
                                                )
                                            )
                                        ),
                                    )
                                ),
                                5 => array(
                                    'linkId' => '3',
                                    'text' => 'Persyaratan Klinis',
                                    'item' => array(
                                        0 => array(
                                            'linkId' => '3.1',
                                            'text' => 'Apakah ketepatan indikasi, dosis, dan waktu penggunaan obat sudah sesuai?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueCoding' => array(
                                                        'system' => 'http://terminology.kemkes.go.id/CodeSystem/clinical-term',
                                                        'code' => 'OV000052',
                                                        'display' => $item->persyaratanklinis1
                                                    )
                                                )
                                            )
                                        ),
                                        1 => array(
                                            'linkId' => '3.2',
                                            'text' => 'Apakah ketepatan indikasi, dosis, dan waktu penggunaan obat sudah sesuai?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueBoolean' => $item->persyaratanklinis2 == 'Sesuai' ? true : false
                                                )
                                            )
                                        ),
                                        2 => array(
                                            'linkId' => '3.3',
                                            'text' => 'Apakah terdapat alergi dan reaksi obat yang tidak dikehendaki (ROTD)?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueBoolean' => $item->persyaratanklinis3 == 'Sesuai' ? true : false
                                                )
                                            )
                                        ),
                                        3 => array(
                                            'linkId' => '3.4',
                                            'text' => 'Apakah terdapat kontraindikasi pengobatan?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueBoolean' => $item->persyaratanklinis3 == 'Sesuai' ? true : false
                                                )
                                            )
                                        ),
                                        4 => array(
                                            'linkId' => '3.5',
                                            'text' => 'Apakah terdapat dampak interaksi obat?',
                                            'answer' => array(
                                                0 => array(
                                                    'valueBoolean' => $item->persyaratanklinis3 == 'Sesuai' ? true : false
                                                )
                                            )
                                        ),
                                    )
                                )
                            )
                        )
                    )
                );
                
                if ($item->ihs_id == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_id == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_id == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_id)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = $item->ihs_id == null ? 'POST' : 'PUT';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    AntrianApotik::where('norec', $item->norec)->update([
                        'ihs_id' => $response->id,
                    ]);
                }
            }
        } catch (Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }

        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }

    public function CarePlan(Request $request, $lokal = null)
    {
        try {
            $kdProfile = $this->kdProfile;
            $q = collect(DB::select("
                SELECT
                    pp.norec,
                    pp.ihs_id as ihs_srfup,
                    pp.ihs_careplan as ihs_cp,
                    pp.noperjanjian,
                    pp.keterangan,
                    pp.diagnosa,
                    pp.indikasikembali,
                    ru.ihs_id AS ihs_location_id,
                    ru.namaruangan AS ihs_location_name,
                    pg.ihs_id AS ihs_practioner_id,
                    pg.namalengkap AS ihs_practioner_name,
                    ps.ihs_number AS ihs_pasien_id,
                    ps.namapasien AS ihs_pasien_name,
                    pd.ihs_id AS ihs_encounter,
                    pd.tglregistrasi,
                    pp.tglperjanjian
                FROM pasienperjanjian_t AS pp
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = pp.noregistrasifk
                INNER JOIN ruangan_m AS ru ON ru.id = pp.objectruanganfk
                INNER JOIN pegawai_m AS pg ON pg.id = pp.objectdokterfk
                INNER JOIN pasien_m AS ps ON ps.id = pp.objectpasienfk
                WHERE pp.kdprofile = $kdProfile
                AND pp.norec = '$request[norec_pp]'
            "));
            if (count($q) == 0) {
                $response = array(
                    "issue" =>  'CarePlan doesnt exist',
                    "resourceType" => "OperationOutcome"
                );
                if ($lokal == true) {
                    return $response;
                }
                return response()->json($response);
            }
            $profile = Profile::where('id', $kdProfile)->first();

            foreach ($q as $key => $item) {
                $data = array(
                    'resourceType' => 'CarePlan',
                    'id' => $item->ihs_cp,
                    'status' => 'active',
                    'intent' => 'plan',
                    'title' => 'Kontrol Ulang',
                    'description' => $item->indikasikembali,
                    'category' => array(
                        0 => array(
                            'coding' => array(
                                0 => array(
                                    'system' => 'http://snomed.info/sct',
                                    'code' => '736271009',
                                    'display' => 'Outpatient care plan'
                                )
                            )
                        )
                    ),
                    'subject' => array(
                        'reference' => 'Patient/' . $item->ihs_pasien_id,
                        'display' => $item->ihs_pasien_name
                    ),
                    'encounter' => array(
                        'reference' => 'Encounter/' . $item->ihs_encounter
                    ),
                    'created' => date('Y-m-d\TH:i:sP'),
                    'author' => array(
                        'reference' => 'Practitioner/' . $item->ihs_practioner_id
                    )
                );

                if ($item->ihs_cp == null) {
                    unset($data['id']);
                }

                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['url'] = $data['resourceType'];
                $objetoRequest['method'] = $item->ihs_cp == null ? 'POST' : 'PUT';
                $objetoRequest['data'] = $data;

                $response =  $this->ihsTools($objetoRequest, true);

                $noterror = true;
                if ($response->resourceType == 'OperationOutcome') {
                    $noterror = false;
                }

                if ($item->ihs_cp == null) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                } else {
                    $mod = IHS_Transaction::where('id', $item->ihs_cp)->first();
                }
                if (empty($mod)) {
                    $mod = new IHS_Transaction();
                    $mod->norec = $mod->generateNewId();
                    $mod->statusenabled = $noterror;
                    $mod->kdprofile = $this->kdProfile;
                }
                $mod->resourcetype = $data['resourceType'];
                $mod->url =  $this->endPoint() . $data['resourceType'];
                $mod->method = $item->ihs_cp == null ? 'POST' : 'PUT';
                $mod->id = isset($response->id) ? $response->id : null;
                $mod->body = json_encode($data);
                $mod->response = json_encode($response);
                $mod->date = date('Y-m-d H:i:s');
                $mod->save();
                if (isset($response->id)) {
                    PasienPerjanjian::where('norec', $item->norec)->update([
                        'ihs_careplan' => $response->id,
                    ]);
                }
            }
        } catch(Exception $e) {
            $response = array(
                "issue" => $e->getMessage() . ' ' . $e->getLine(),
                "resourceType" => "OperationOutcome"
            );
        }

        if ($lokal == true) {
            return $response;
        }
        return response()->json($response);
    }
}
