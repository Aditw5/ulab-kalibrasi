<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
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

    public function uploadImageBarcode(Request $request)
    {
        return $request->file('image');
    }

}
