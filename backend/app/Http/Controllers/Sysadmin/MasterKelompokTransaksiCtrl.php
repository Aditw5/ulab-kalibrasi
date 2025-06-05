<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokTransaksi;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKelompokTransaksiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKelompokTransaksi(Request $r)
    {
        $data  = DB::table('kelompoktransaksi_m')
            ->select(
                'id',
                'statusenabled',
                'kelompoktransaksi',
                'namaexternal',
                'reportdisplay',
                'iscostinout'
            )
            ->where('kdprofile', $this->kdProfile);
            if (isset($r['id']) && $r['id'] != '') {
                $data = $data->where('id', '=',  $r['id']);
            }
            if (isset($r['kelompoktransaksi']) && $r['kelompoktransaksi'] != '') {
                $data = $data->where('kelompoktransaksi', 'ilike', '%' . $r['kelompoktransaksi'] . '%');
            }
            if (isset($r['statusenabled']) && $r['statusenabled'] != '' && $r['statusenabled'] == 'true') {
                $data = $data->where('statusenabled', '=', $r['statusenabled']);
            }
            if (isset($r['_total']) && $r['_total'] != '') {
            }
    
        $data = $data->orderByDesc('kelompoktransaksi');
        $data = $data->get();

         foreach ($data as $d) {
            $d->statusenabled;
            $d->status = 'Aktif';
            $d->status_c = 'info';
            if ($d->statusenabled != 'false') {
                $d->status = 'Nonaktif';
                $d->status_c = 'danger';
            }

        }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function savekelompoktransaksi(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save kelompoktransaksi

            $PSN =  $r['kelompoktransaksi'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new KelompokTransaksi(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new KelompokTransaksi();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = KelompokTransaksi::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->kelompoktransaksi =  $PSN['kelompoktransaksi'];
            $dataPS->namaexternal =  $PSN['kelompoktransaksi'];
            $dataPS->reportdisplay =  $PSN['kelompoktransaksi'];
            $dataPS->iscostinout =  $PSN['iscostinout'];
           
            $dataPS->save();
            //endregion

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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteKelompokTransaksi(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = KelompokTransaksi::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);

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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
