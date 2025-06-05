<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\SatuanGizi;
use App\Traits\Valet;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MasterSatuanGiziCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function masterSatuanGizi(Request $request)
    {
        $data = DB::table('satuangizi_m as sg')
            ->select(
                'sg.id',
                'sg.satuangizi',
                'sg.statusenabled'
            )
            ->where('sg.kdprofile', $this->kdProfile);

        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('sg.id', $request['id']);
        }

        if (isset($request['satuangizi']) && $request['satuangizi'] != '') {
            $data = $data->where('sg.satuangizi', 'ilike', '%' . $request['satuangizi'] . '%');
        }

        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('sg.statusenabled', $request['statusenabled']);
        }

        $data = $data->orderBy('sg.satuangizi');
        $data = $data->get();

        foreach ($data as $value) {
            $value->status = 'Aktif';
            $value->status_c = 'info';
            if ($value->statusenabled != 'false') {
                $value->status = 'Nonaktif';
                $value->status_c = 'danger';
            }
        }

        $res['data'] = $data;

        return $this->respond($res);
    }

    public function saveSatuanGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = SatuanGizi::count();
            $PSN =  $request['satuangizi'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;
            
            if ($PSN['id'] == '') {
                $data = new SatuanGizi();
                $data->id = $this->SEQUENCE_MASTER(new SatuanGizi(), 'id', $this->kdProfile);
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;
                
                $transMessage = 'Simpan Data Berhasil';
            } else {
                $data = SatuanGizi::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];

                $transMessage = 'Update Data Berhasil';
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->namaexternal = $PSN['satuangizi'];
            $data->reportdisplay = $PSN['satuangizi'];
            $data->satuangizi = $PSN['satuangizi'];
            $data->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Simpan Gagal",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }

    public function deleteSatuanGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = SatuanGizi::where('id', $request['id'])
                ->update([
                    'statusenabled' => false
                ]);

            DB::commit();
            $result = [
                "statusCode" => 201,
                "message" => "Proses Hapus Data Berhasil",
                "result" => [
                    "data"  => $data,
                    "as" => '@epic',
                    ]
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = [
                "statusCode" => $e->getCode(),
                "message" => "Hapus Data Gagal, Silakan Cek Kembali Data",
                "result"  => [
                    "messageError" => $e->getMessage(),
                ]
            ];
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
}
