<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KSM;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterKSMCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterKSM(Request $r)
    {
        $data  = DB::table('kelompokstafmedis_m')
            ->select(
                'id',
                'statusenabled',
                'kelompokstafmedis',
                'namaexternal',
                'reportdisplay',
            )
            ->where('kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['kelompokstafmedis']) && $r['kelompokstafmedis'] != '') {
            $data = $data->where('kelompokstafmedis', 'ilike', '%' . $r['kelompokstafmedis'] . '%');
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('statusenabled', '=', $r['statusenabled']);
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }

        $data = $data->orderByDesc('kelompokstafmedis');
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

    public function saveKSM(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save KSM

            $PSN =  $r['kelompokstafmedis'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new KSM(),'id',$this->kdProfile);//$this->Uuid4();
                $dataPS = new KSM();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = KSM::where('id', $PSN['id'])->first();
                $dataPS->statusenabled =  $PSN['statusenabled'];
                $id =  $dataPS->id;
            }
            $dataPS->kelompokstafmedis =  $PSN['kelompokstafmedis'];
            $dataPS->namaexternal =  $PSN['kelompokstafmedis'];
            $dataPS->reportdisplay =  $PSN['reportdisplay'];

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

    public function deleteKSM(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = KSM::where('id', $r['id'])
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
