<?php

namespace App\Http\Controllers\Gizi;

use App\Http\Controllers\Controller;
use App\Models\Master\MapJadwalMenuGizi;
use App\Models\Master\MapJadwalMenuGiziDetail;
use App\Models\Master\MenuGizi;
use App\Models\Master\MenuGiziDetailKelas;
use App\Traits\Valet;
use Faker\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MasterMenuGiziCtrl extends Controller
{
    use Valet;

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getMenuGizi(Request $request)
    {
        $data = DB::table('menugizi_m as mg')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'mg.kategoridietfk')
            ->join('satuangizi_m as sg', 'sg.id', '=', 'mg.satuangizifk')
            ->join('vendorgizi_m as vg', 'vg.id', '=', 'mg.vendorgizifk')
            ->select(
                'mg.id',
                'mg.kategoridietfk',
                'mg.satuangizifk',
                'mg.vendorgizifk',
                'mg.reportdisplay',
                'mg.namaexternal',
                'mg.menu',
                'kd.kategorydiet',
                'sg.satuangizi',
                'vg.vendorgizi',
                DB::raw("
                    TO_CHAR(mg.tglinput::timestamp, 'YYYY-MM-DD HH24:MI') as tglinput
                ")
            )
            ->where('mg.kdprofile', $this->kdProfile)
            ->where('mg.statusenabled', true);

        if (isset($request['search']) && $request['search'] != '' && $request['search'] != 'undefined') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mg.menu', 'ilike', $searchTerm);
            });
        }

        $data = $data->get();

        $detailKelas = DB::table('menugizidetailkelas_m as mgd')
            ->join('kelas_m as kls', 'kls.id', '=', 'mgd.kelasfk')
            ->select(
                'mgd.menugizifk',
                'mgd.kelasfk',
                'kls.namakelas'
            )
            ->where('mgd.kdprofile', $this->kdProfile)
            ->where('mgd.statusenabled', true)
            ->get();
        
        $result = [];
        foreach ($data as $value) {
            $detail = [];
            foreach ($detailKelas as $valueDetail) {
                if ($value->id == $valueDetail->menugizifk) {
                    $detail[] = $valueDetail;
                }
            }
            $result[] = [
                'id' => $value->id,
                'kategoridietfk' => $value->kategoridietfk,
                'satuangizifk' => $value->satuangizifk,
                'vendorgizifk' => $value->vendorgizifk,
                'reportdisplay' => $value->reportdisplay,
                'namaexternal' => $value->namaexternal,
                'menu' => $value->menu,
                'kategorydiet' => $value->kategorydiet,
                'satuangizi' => $value->satuangizi,
                'vendorgizi' => $value->vendorgizi,
                'tglinput' => $value->tglinput,
                'detailkelas' => $detail
            ];
        }

        $res['data'] = $result;
        $res['total'] = count($result);

        return $this->respond($res);
    }

    public function saveMenuGizi(Request $request)
    {
        DB::beginTransaction();
        try {

            $faker = Factory::create();
            $count = MenuGizi::count();
            $codeUnique = $count < 1 ? 1 : $count + 1 ;

            if ($request['id'] == '') {
                $data = new MenuGizi();
                $data->id = MenuGizi::max('id') + 1;
                $data->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUnique;

                $transMessage = 'Simpan Data Berhasil';
            } else {
                $data = MenuGizi::where('id', $request['id'])->first();
                MenuGiziDetailKelas::where('menugizifk', $request['id'])->delete();

                $transMessage = 'Update Data Berhasil';
            }
            $data->kdprofile = (int)$this->kdProfile;
            $data->statusenabled = true;
            $data->tglinput = $request['tglinput'];
            $data->kategoridietfk = $request['kategoridietfk'];
            $data->satuangizifk = $request['satuangizifk'];
            $data->vendorgizifk = $request['vendorfk'];
            $data->namaexternal = $request['menu'];
            $data->reportdisplay = $request['menu'];
            $data->menu = $request['menu'];
            $data->save();

            foreach ($request['detailkelas'] as  $value) {
                $dataDetail = new MenuGiziDetailKelas();
                $dataDetail->id = MenuGiziDetailKelas::max('id') + 1;
                $dataDetail->kdprofile = (int)$this->kdProfile;
                $dataDetail->statusenabled = true;
                $dataDetail->menugizifk = $data->id;
                $dataDetail->kelasfk = $value['kelasfk'];
                $dataDetail->save();
            }

            DB::commit();
            $result = [
                'statusCode' => 201,
                'message' => $transMessage,
                'result' => [
                    'data' => $data,
                    'as' => '@epic'
                ]
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

    public function deleteMenuGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            MenuGizi::where('id', $request['id'])->update([
                'statusenabled' => false
            ]);

            DB::commit();
            $result = [
                'statusCode' => 201,
                'message' => 'Hapus Data Berhasil',
                'result' => [
                    'data' => null,
                    'as' => '@epic'
                ]
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

    public function getDataJadwalMenuGizi(Request $request)
    {
        $data = DB::table('mapjadwalmenugizi_m as map')
            ->join('jeniswaktu_m as jw', 'jw.id', '=', 'map.jeniswaktufk')
            ->join('kategorydiet_m as kd', 'kd.id', '=', 'map.kategorydietfk')
            ->select(
                'map.id',
                'map.tgljadwal',
                'map.jeniswaktufk',
                'map.kategorydietfk',
                'jw.jeniswaktu',
                'kd.kategorydiet'
            )
            ->where('map.kdprofile', $this->kdProfile)
            ->where('map.statusenabled', true)
            ->whereBetween('map.tgljadwal', [$request['tglAwal'], $request['tglAkhir']]);

        $data = $data->orderBy('map.tgljadwal');
        $data = $data->get();
        
        $arrIdMap = [];
        if (count($data) > 0) {
            foreach ($data as $value) {
                $arrIdMap[] = $value->id;
            }
        } else {
            $res['data'] = [];
            
            return $this->respond($res);
        }

        $dataDetail = DB::table('mapjadwalmenugizidetail_m as map')
            ->join('menugizi_m as mg', 'mg.id', '=', 'map.menugizifk')
            ->select(
                'map.id as iddetail',
                'map.mapjadwalmenugizifk',
                'map.menugizifk',
                'mg.menu'
            )
            ->where('map.kdprofile', $this->kdProfile)
            ->where('mg.statusenabled', true)
            ->where('map.statusenabled', true)
            ->whereIn('map.mapjadwalmenugizifk', $arrIdMap)
            ->get();

        $result = [];
        foreach ($data as $value) {
            $detail = [];
            foreach ($dataDetail as $valueDetail) {
                if ($value->id == $valueDetail->mapjadwalmenugizifk) {
                    $detail[] = $valueDetail;
                }
            }
            if (count($detail) > 0) {
                $result[] = [
                    'id' => $value->id,
                    'tgljadwal' => $value->tgljadwal,
                    'kategorydietfk' => $value->kategorydietfk,
                    'kategorydiet' => $value->kategorydiet,
                    'jeniswaktufk' => $value->jeniswaktufk,
                    'jeniswaktu' => $value->jeniswaktu,
                    'menu' => $detail
                ];
            }
        }

        $res['data'] = $result;

        return $this->respond($res);
    }

    public function saveMapJadwalMenuGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            $dataTemp = [];
            foreach ($request['dataMap'] as $value) {
                $faker = Factory::create();
                $countMap = MapJadwalMenuGizi::count();
                $codeUniqueMap = $countMap < 1 ? 1 : $countMap + 1;

                if ($value['id'] == '') {
                    $dataMap = new MapJadwalMenuGizi();
                    $dataMap->id = MapJadwalMenuGizi::max('id') + 1;
                    $dataMap->kodeexternal = $faker->regexify('[A-Z]{2}-').$codeUniqueMap;

                    $transMessage = 'Simpan Data Berhasil';
                } else {
                    $dataMap = MapJadwalMenuGizi::where('id', $value['id'])->first();
                    
                    MapJadwalMenuGiziDetail::where('mapjadwalmenugizifk', $dataMap->id)->delete();

                    $transMessage = 'Update Data Berhasil';
                }
                $dataMap->kdprofile = (int)$this->kdProfile;
                $dataMap->statusenabled = true;
                $dataMap->tgljadwal = $value['tgljadwal'];
                $dataMap->jeniswaktufk = $value['jeniswaktufk'];
                $dataMap->kategorydietfk = $value['kategorydietfk'];
                $dataMap->save();
                $dataTemp[] = $dataMap;

                foreach ($value['listmenu'] as $valueDetail) {
                    $countMapD = MapJadwalMenuGiziDetail::count();
                    $codeUniqueMapD = $countMapD < 1 ? 1 : $countMapD + 1;
                    
                    $dataMapD = new MapJadwalMenuGiziDetail();
                    $dataMapD->id = MapJadwalMenuGiziDetail::max('id') + 1;
                    $dataMapD->kdprofile = (int)$this->kdProfile;
                    $dataMapD->statusenabled = true;
                    $dataMapD->mapjadwalmenugizifk = $dataMap->id;
                    $dataMapD->menugizifk = $valueDetail['menugizifk'];
                    $dataMapD->keteranganlainnya = isset($valueDetail['keteranganlainnya']) ? $valueDetail['keteranganlainnya'] : null;
                    $dataMapD->keteranganmenu = isset($valueDetail['keteranganmenu']) ? $valueDetail['keteranganmenu'] : null;
                    $dataMapD->save();
                }
            }

            DB::commit();
            $result = [
                'statusCode' => 201,
                'message' => $transMessage,
                'result' => [
                    'data' => $dataTemp,
                    'as' => '@epic'
                ]
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

    public function deleteMapJadwalMenuGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            MapJadwalMenuGizi::where('id', $request['id'])->update([
                'statusenabled' => false
            ]);

            MapJadwalMenuGiziDetail::where('mapjadwalmenugizifk', $request['id'])->update([
                'statusenabled' => false
            ]);

            DB::commit();
            $result = [
                'statusCode' => 201,
                'message' => 'Hapus Data Berhasil',
                'result' => [
                    'data' => null,
                    'as' => '@epic'
                ]
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

    public function getMenuBerdasarkanJadwal(Request $request) 
    {
        $data = DB::table('menugizi_m as mg')
            ->join('satuangizi_m as sg', 'sg.id', '=', 'mg.satuangizifk')
            ->join('mapjadwalmenugizidetail_m as mapd', 'mapd.menugizifk', 'mg.id')
            ->join('mapjadwalmenugizi_m as map', 'map.id', '=', 'mapd.mapjadwalmenugizifk')
            ->join('jeniswaktu_m as jw', 'jw.id', '=', 'map.jeniswaktufk')
            ->select(
                'mg.tglinput',
                'mg.id as menuid',
                'mg.menu',
                'mg.satuangizifk',
                'mg.keteranganmenu',
                'mg.kategoridietfk',
                'mg.jenisdietfk',
                'sg.satuangizi',
                'jw.jeniswaktu',
                'map.jeniswaktufk',
                'map.id as mapid',
                'mapd.id as mapdid',
            )
            ->whereBetween('map.tgljadwal', [$request['tglAwal'], $request['tglAkhir']])
            ->where('mg.kelasfk', $request['kelasid'])
            ->get();
        
        $res['data'] = $data;

        return $this->respond($res);
    }

    public function getMenuGiziByKelas(Request $request)
    {
        $tglAwal = $request['tglorder'] . ' 00:00';
        $tglAkhir = $request['tglorder'] . ' 23:59';
        
        $dataJadwal = DB::table('menugizidetailkelas_m as mgk')
            ->join('menugizi_m AS mg', 'mg.id', '=', 'mgk.menugizifk')
            ->leftJoin('satuangizi_m as sg', 'sg.id', '=', 'mg.satuangizifk')
            ->leftJoin('kategorydiet_m as kd', 'kd.id', '=', 'mg.kategoridietfk')
            ->join('mapjadwalmenugizidetail_m AS mapd', 'mapd.menugizifk', '=', 'mg.id')
            ->join('mapjadwalmenugizi_m AS map', 'map.id', '=', 'mapd.mapjadwalmenugizifk')
            ->leftJoin('jeniswaktu_m as jw', 'jw.id', '=', 'map.jeniswaktufk')
            ->select(
                'mg.id AS menugizifk',
                'mg.menu',
                'mapd.keteranganmenu',
                'mapd.keteranganlainnya',
                'mg.satuangizifk',
                'mg.kategoridietfk',
                'map.jeniswaktufk',
                'map.id as mapjadwalfk',
                'jw.jeniswaktu',
                'sg.satuangizi',
                'kd.kategorydiet'
            )
            ->where('mg.kdprofile', $this->kdProfile)
            ->where('mg.statusenabled', true)
            ->where('map.statusenabled', true)
            ->whereBetween('map.tgljadwal', [$tglAwal, $tglAkhir]);
        
        if (isset($request['kelasid']) && $request['kelasid'] != 'undefined' && $request['kelasid'] != '') {
            $dataJadwal = $dataJadwal->where('mgk.kelasfk', $request['kelasid']);
        }
        if (isset($request['jeniswaktuid']) && $request['jeniswaktuid'] != 'undefined' && $request['jeniswaktuid'] != '') {
            $dataJadwal = $dataJadwal->where('map.jeniswaktufk', $request['jeniswaktuid']);
        }

        $dataJadwal = $dataJadwal->groupBy(
            'mg.id',
            'mg.menu',
            'mapd.keteranganmenu',
            'mapd.keteranganlainnya',
            'mg.satuangizifk',
            'mg.kategoridietfk',
            'map.jeniswaktufk',
            'map.id',
            'jw.jeniswaktu',
            'sg.satuangizi',
            'kd.kategorydiet'
        );

        $dataJadwal = $dataJadwal->get();

        $res['data'] = $dataJadwal;

        return $this->respond($res);
    }
}
