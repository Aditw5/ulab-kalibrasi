<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\KSM;
use App\Models\Master\MapKsmToRuangan;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MapKsmToRuanganCtrl extends Controller
{
  use Valet;

  public function __construct()
  {
      parent::__construct($is_encrypt = true);
  }

  public function getComboMapKsm(Request $request)
  {
    $res['ruangan'] = Ruangan::mine()->get();
    $res['kelompokstafmedis'] = KSM::mine()->get();

    return $this->respond($res);
  }

  public function getMapKsmToRuangan(Request $request)
  {
    $data = DB::table('mapkelompokstafmedistoruangan_m as mkr')
      ->join('ruangan_m as ru', 'ru.id', '=', 'mkr.objectruanganfk')
      ->join('kelompokstafmedis_m as ksm', 'ksm.id', '=', 'mkr.objectkelompokstafmedisfk')
      ->select('mkr.*', 'ru.namaruangan', 'ksm.kelompokstafmedis', DB::raw("CASE WHEN mkr.statusenabled = TRUE THEN 'Aktif' ELSE 'Tidak Aktif' END as status"))
      ->where('mkr.kdprofile', $this->kdProfile)
      ->where('ru.statusenabled', true)
      ->where('ksm.statusenabled', true);
    
    if (isset($request['status']) && $request['status'] == '' && $request['status'] != 'undefiend')
    {
      $data = $data->where('mkr.statusenabled', $request['status']);
    }

    if (isset($request['idruangan']) && $request['idruangan'] != '' && $request['idruangan'] != 'undefined')
    {
      $data = $data->where('ru.id', $request['idruangan']);
    }

    if (isset($request['idksm']) && $request['idksm'] != '' && $request['idksm'] != 'undefiend')
    {
      $data = $data->where('ksm.id', $request['idksm']);
    }

    $data = $data->get();
    $res['data'] = $data;

    return $this->respond($res);
  }

  public function saveMapKsmToRuangan(Request $request)
  {
    $Map = [];
    DB::beginTransaction();
    try {
      //code...
      foreach ($request['arrksm'] as $value) {
        if ($request['id'] == '') {
          $id = $this->SEQUENCE_MASTER(new MapKsmToRuangan(),'id',$this->kdProfile);
          $dataMP = new MapKsmToRuangan();
          $dataMP->id = $id;
          $dataMP->norec = $id;
          $dataMP->kdprofile = (int)$this->kdProfile;
          $dataMP->statusenabled = $request['aktif'];
        } else {
          $dataMP = MapKsmToRuangan::where('id', $request['id'])->first();
          $dataMP->statusenabled = $request['aktif'];
        }
        $dataMP->objectruanganfk = $request['ruangan'];
        $dataMP->objectkelompokstafmedisfk = $value;
        $dataMP->save();
        $Map[] = $dataMP;
      }

      $tranStatus = true;
    } catch (Exception $e) {
      //throw $th;
      $tranStatus = false;
    }

    if ($tranStatus) {
      $transMessage = "Sukses";
      DB::commit();

      $result = array(
        "status" => 201,
        "result" => array(
          "data"  => $Map,
          "as" => 'epic',
        ),
      );
    } else {
      $transMessage = "Gagal";
      DB::rollBack();

      $result = array(
        "status" => 400,
        "result" => $e->getMessage() . ' - ' . $e->getLine()
      );
    }

    return $this->respond($result['result'], $result['status'], $transMessage);
  }

  public function deleteMapKsmToRuangan(Request $request)
  {
    DB::beginTransaction();
    try {
      //code...
      MapKsmToRuangan::where('id', $request['id'])->delete();

      $tranStatus = true;
    } catch (Exception $e) {
      //throw $th;
      $tranStatus = false;
    }

    if ($tranStatus) {
      $transMessage = "Sukses";
      DB::commit();

      $result = array(
        "status" => 201,
        "result" => array(
          "as" => 'epic',
        ),
      );
    } else {
      $transMessage = "Gagal";
      DB::rollBack();

      $result = array(
        "status" => 400,
        "result" => $e->getMessage() . ' - ' . $e->getLine()
      );
    }

    return $this->respond($result['result'], $result['status'], $transMessage);
  }
}