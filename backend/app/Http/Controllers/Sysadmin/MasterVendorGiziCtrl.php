<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\VendorGizi;
use App\Traits\Valet;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MasterVendorGiziCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function masterVendorGizi(Request $request)
    {
        $data = DB::table('vendorgizi_m as vg')
            ->select(
                'vg.id',
                'vg.vendorgizi',
                'vg.statusenabled'
            )
            ->where('vg.kdprofile', $this->kdProfile);
        
        if (isset($request['id']) && $request['id'] != '') {
            $data = $data->where('vg.id', $request['id']);
        }

        if (isset($request['vendorgizi']) && $request['vendorgizi'] != '') {
            $data = $data->where('vg.vendorgizi', 'ilike', '%' . $request['vendorgizi'] . '%');
        }

        if (isset($request['statusenabled']) && $request['statusenabled'] != '') {
            $data = $data->where('vg.statusenabled', $request['statusenabled']);
        }

        $data = $data->orderBy('vg.vendorgizi');
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

    public function saveVendorGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            $faker = Factory::create();
            $count = VendorGizi::count();
            $PSN =  $request['vendorgizi'];
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($PSN['id'] == '') {
                $data = new VendorGizi();
                $data->id = $this->SEQUENCE_MASTER(new VendorGizi(), 'id', $this->kdProfile);
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;
                $data->statusenabled = true;

                $transMessage = 'Simpan Data Berhasil';
            } else {
                $data = VendorGizi::where('id', $PSN['id'])->first();
                $data->statusenabled = $PSN['statusenabled'];

                $transMessage = 'Update Data Berhasil';
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->namaexternal = $PSN['vendorgizi'];
            $data->reportdisplay = $PSN['vendorgizi'];
            $data->vendorgizi = $PSN['vendorgizi'];
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

    public function deleteVendorGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = VendorGizi::where('id', $request['id'])
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
