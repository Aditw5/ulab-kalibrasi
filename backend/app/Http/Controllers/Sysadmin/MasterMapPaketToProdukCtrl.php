<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\JenisProduk;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokProduk;
use App\Models\Master\MapPaketToProduk;
use App\Models\Master\MapRuanganToProduk;
use App\Models\Master\Paket;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterMapPaketToProdukCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterMapPaketToProduk(Request $r)
    {
        $data  = DB::table('mappakettoproduk_m as mp')
        ->join('paket_m as pr', 'mp.objectpaketfk', '=', 'pr.id')
        ->join('produk_m as ru', 'mp.objectprodukfk', '=', 'ru.id')
        ->select(
            'mp.id',
            'mp.kodeexternal',
            'mp.namaexternal',
            'mp.norec',
            'mp.reportdisplay',
            'mp.objectpaketfk',
            'mp.objectprodukfk',
            'pr.namapaket',
            'ru.namaproduk',
        )
            ->where('mp.statusenabled', true)
            ->where('mp.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('mp.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('mp.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['objectpaketfk']) && $r['objectpaketfk'] != '') {
            $data = $data->where('mp.objectpaketfk', '=', $r['objectpaketfk'] );
        }
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveMapPaketToProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            //region Save Paket To Produk

            $delete = MapPaketToProduk::where('objectpaketfk', $r['objectpaketfk'])
            ->update(['statusenabled'=> false]);
            foreach($r['detail'] as $item){
                $dataPS = new MapPaketToProduk();
                $dataPS->id =  $this->SEQUENCE_MASTER(new MapPaketToProduk,'id',$this->kdProfile);//$this->Uuid4();
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
                $dataPS->objectpaketfk =  $r['objectpaketfk'];
                $dataPS->objectprodukfk = $item['produkfk'];
                $dataPS->save();
            }
              
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
    
    public function deleteMapPaketToProduk(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = MapPaketToProduk::where('id', $r['id'])
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
    public function masterMapPaketToProdukdropdown(Request $r)
    {
        $res['namapaket'] = Paket::mine()->get();
        $res['namaproduk'] = DB::table('produk_m as pr')
        ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        ->select('pr.id', 'pr.namaproduk')
        ->where('pr.kdprofile', $this->kdProfile)
        ->where('pr.statusenabled', true)
        ->whereNotIn('djp.objectjenisprodukfk', explode(',', $this->settingFix('kdJenisProdukObat')))
        ->get();


        return $this->respond($res);
    }
   


}

