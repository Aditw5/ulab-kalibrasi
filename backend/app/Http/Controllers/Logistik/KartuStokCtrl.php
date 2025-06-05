<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KartuStokCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getDataGrid(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $dateRange = [$request->tglawal, $request->tglakhir];

        $data = DB::table('kartustok_t as ks')
            ->JOIN('produk_m as pro', 'pro.id', '=', 'ks.produkfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'ks.ruanganfk')
            // ->leftJoin('flag_m as fg','fg.id','=','ks.flagfk')
            ->select(
                'ks.keterangan',
                'ks.tglinput',
                'ks.tglkejadian',
                'ks.produkfk',
                'pro.namaproduk',
                'ks.ruanganfk',
                'ru.namaruangan',
                'ks.status',
                DB::raw('COALESCE(ks.saldoawal,0.0) as saldoawal, coalesce(ks.saldoakhir,0.0) as saldoakhir,
                    coalesce(ks.qtyin,0.0) as saldomasuk, coalesce(ks.qtyout,0.0) as saldokeluar')

            )
            ->where('ks.kdprofile', $idProfile)
            ->whereBetween(DB::raw("cast(ks.tglkejadian as DATE)"), $dateRange)
            ->where('ks.statusenabled', true)
            ->orderBy('ks.tglkejadian', 'asc');

        if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('ks.ruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
            $data = $data->where('ks.produkfk', '=', $request['idproduk']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getPenggunaanObatAlkes(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $tglAwal = Carbon::createFromFormat('d/m/Y', $request->tglawal)->format('Y-m-d');
        $tglAkhir = Carbon::createFromFormat('d/m/Y', $request->tglakhir)->format('Y-m-d');

        $data = DB::table('kartustok_t as ks')
            ->JOIN('produk_m as pro', 'pro.id', '=', 'ks.produkfk')
            ->leftJoin('stokprodukdetail_t as sto', 'sto.norec', '=', 'ks.stokprodukdetailfk')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pro.objectdetailjenisprodukfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'ks.ruanganfk')
            ->select(
                'ks.keterangan',
                'ks.tglinput',
                DB::raw("TO_CHAR(ks.tglkejadian, 'DD/MM/YYYY') as tglkejadian"),
                DB::raw("TO_CHAR(sto.tglkadaluarsa, 'DD/MM/YYYY') as tglkadaluarsa"),
                'ks.produkfk',
                'pro.namaproduk',
                'ks.ruanganfk',
                'ru.namaruangan',
                'ks.status',
                'sto.nobatch',
                'djp.detailjenisproduk',
                DB::raw('COALESCE(ks.saldoawal, 0.0) as saldoawal'),
                DB::raw('COALESCE(ks.saldoakhir, 0.0) as saldoakhir'),
                DB::raw('COALESCE(ks.qtyin, 0.0) as saldomasuk'),
                DB::raw('COALESCE(ks.qtyout, 0.0) as saldokeluar'),
                DB::raw('SUM(COALESCE(ks.qtyout, 0.0)) OVER (PARTITION BY ks.produkfk, CAST(ks.tglkejadian AS DATE)) as totalqtyout'),
                DB::raw('SUM(COALESCE(ks.qtyin, 0.0)) OVER (PARTITION BY ks.produkfk, CAST(ks.tglkejadian AS DATE)) as totalqtyin')
            )
            ->where('ks.kdprofile', $idProfile)
            ->whereBetween(DB::raw("cast(ks.tglkejadian as DATE)"), [$tglAwal, $tglAkhir]) // Format YYYY-MM-DD
            ->where('ks.statusenabled', true)
            ->orderBy('ks.tglkejadian', 'asc');

        if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('ks.ruanganfk', '=', $request['idruangan']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != "" && $request['idproduk'] != "undefined") {
            $data = $data->where('ks.produkfk', '=', $request['idproduk']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function listProduk(Request $r)
    {
        $data =  DB::table('produk_m as pr')
            ->select('pr.id', 'pr.namaproduk')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile')
            ->get();
        // if (isset($r['nama'])) {
        //     $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['nama'] . '%');
        // }
        // $data = $data->limit($r['limit']);
        // $data = $data->orderBy('pr.namaproduk');
        // $data = $data->get();

        return $this->respond($data);
    }

    public function getCombo(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataRuangan =  DB::table('maploginusertoruangan_s as mlur')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mlur.objectruanganfk')
            ->join('loginuser_s as lu', 'lu.id', '=', 'mlur.objectloginuserfk')
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlur.statusenabled', true)
            ->where('mlur.objectloginuserfk', $this->getUserId())
            ->where('ru.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('mlur.kdprofile', $this->kdProfile)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->select('pr.id', 'pr.namaproduk')
            ->where('pr.statusenabled', true)
            ->orderBy('pr.namaproduk')
            ->get();

        $res['ruangan'] = $dataRuangan;
        $res['produk'] = $dataProduk;

        return $this->respond($res);
    }
}
