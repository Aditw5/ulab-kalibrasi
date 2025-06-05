<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokRuanganCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // public function getDataGrid(Request $request)
    // {
    //     $idProfile = (int) $this->kdProfile;
    //     $data = DB::table('stokprodukdetail_t as spd')
    //         ->JOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'spd.nostrukterimafk')
    //         ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
    //         ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
    //         ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
    //         ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
    //         ->JOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
    //         ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
    //         ->select(
    //             'sp.nostruk as noterima',
    //             'ru.namaruangan',
    //             'spd.nostrukterimafk',
    //             'spd.objectruanganfk',
    //             'pr.kdproduk as kdsirs',
    //             'pr.namaproduk',
    //             'ap.asalproduk',
    //             'spd.qtyproduk',
    //             'ss.satuanstandar',
    //             'spd.tglkadaluarsa',
    //             'spd.nobatch',
    //             'spd.harganetto1 as harga',
    //             'spd.norec as norec_spd',
    //             'spd.nostrukterimafk'
    //         )
    //         ->where('pr.statusenabled', true)
    //         ->where('spd.statusenabled', true)
    //         ->where('pr.kdprofile', $idProfile)
    //         ->where('spd.kdprofile', $idProfile)
    //         ->where('spd.qtyproduk', '>', 0);

    //     if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
    //         $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
    //     }
    //     if (isset($request['idruangan'])  && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
    //         $data = $data->where('spd.objectruanganfk', '=', $request['idruangan']);
    //     }
    //     if (isset($request['idasalproduk']) && $request['v'] != "" && $request['idasalproduk'] != "undefined") {
    //         $data = $data->where('spd.objectasalprodukfk', '=', $request['idasalproduk']);
    //     }
    //     $data = $data->get();
    //     // $data2 = [];

    //     // $dataOrder = [];

    //     // foreach ($data as $item) {
    //     //     $data2[] = array(
    //     //         'noterima' => $item->noterima,
    //     //         'kodeproduk' => $item->objectprodukfk,
    //     //         // 'kdsirs' => $item->kdsirs,
    //     //         'namaproduk' => $item->namaproduk,
    //     //         'asalproduk' => $item->asalproduk,
    //     //         'qtyproduk' => $item->qtyproduk,
    //     //         'satuanstandar' => $item->satuanstandar,
    //     //         'tglkadaluarsa' => $item->tglkadaluarsa,
    //     //         'nobatch' => $item->nobatch,
    //     //         'harga' => $item->harganetto1,
    //     //         'norecspd' => $item->norec_spd,
    //     //         'nostrukterimafk' => $item->nostrukterimafk,
    //     //     );
    //     // }
    //     // $res['data'] = $data2;
    //     // $res['detailorder'] = $dataOrder;
    //     return $this->respond($data);
    // }

    public function getDataGrid(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('stokprodukdetail_t as spd')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'spd.nostrukterimafk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->JOIN('asalproduk_m as ap', 'ap.id', '=', 'spd.objectasalprodukfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select(
                'sp.nostruk as noterima',
                'ru.namaruangan',
                'spd.nostrukterimafk',
                'spd.objectruanganfk',
                'pr.kdproduk as kdsirs',
                'pr.namaproduk',
                'ap.asalproduk',
                'spd.qtyproduk',
                'ss.satuanstandar',
                'spd.tglkadaluarsa',
                'spd.nobatch',
                'spd.harganetto1 as harga',
                'spd.norec as norec_spd',
                'spd.nostrukterimafk'
            )
            ->where('pr.statusenabled', true)
            ->where('spd.statusenabled', true)
            ->where('pr.kdprofile', $idProfile)
            ->where('spd.kdprofile', $idProfile)
            ->where('spd.qtyproduk', '>', 0);

        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }
        if (isset($request['idruangan'])  && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('spd.objectruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idasalproduk']) && $request['v'] != "" && $request['idasalproduk'] != "undefined") {
            $data = $data->where('spd.objectasalprodukfk', '=', $request['idasalproduk']);
        }
        $data = $data->get();
        // $data2 = [];

        // $dataOrder = [];

        // foreach ($data as $item) {
        //     $data2[] = array(
        //         'noterima' => $item->noterima,
        //         'kodeproduk' => $item->objectprodukfk,
        //         // 'kdsirs' => $item->kdsirs,
        //         'namaproduk' => $item->namaproduk,
        //         'asalproduk' => $item->asalproduk,
        //         'qtyproduk' => $item->qtyproduk,
        //         'satuanstandar' => $item->satuanstandar,
        //         'tglkadaluarsa' => $item->tglkadaluarsa,
        //         'nobatch' => $item->nobatch,
        //         'harga' => $item->harganetto1,
        //         'norecspd' => $item->norec_spd,
        //         'nostrukterimafk' => $item->nostrukterimafk,
        //     );
        // }
        // $res['data'] = $data2;
        // $res['detailorder'] = $dataOrder;
        return $this->respond($data);
    }


    public function getCombo()
    {
        // $dataRuangan =  DB::table('maploginusertoruangan_s as mlur')
        //     ->join('ruangan_m as ru', 'ru.id', '=', 'mlur.objectruanganfk')
        //     ->join('loginuser_s as lu', 'lu.id', '=', 'mlur.objectloginuserfk')
        //     ->select('ru.id', 'ru.namaruangan')
        //     ->where('mlur.statusenabled', true)
        //     ->where('mlur.kdprofile', $this->kdProfile)
        //     ->get();

        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $dataSumberDana = DB::table('asalproduk_m as lu')
            ->select('lu.id', 'lu.asalproduk as asalProduk')
            ->where('lu.statusenabled', true)
            ->get();

        $res = ['ruangan' => $ruangan, 'asalproduk' => $dataSumberDana];

        return $this->respond($res);
    }
}
