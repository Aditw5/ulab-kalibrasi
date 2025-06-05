<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\BahanProduk;
use App\Models\Master\BahanSample;
use App\Models\Master\BentukProduk;
use App\Models\Master\Departemen;
use App\Models\Master\DetailGolonganProduk;
use App\Models\Master\DetailJenisProduk;
use App\Models\Master\DetailObat;
use App\Models\Master\GeneralProduk;
use App\Models\Master\Generik;
use App\Models\Master\GolonganDarah;
use App\Models\Master\GolonganProduk;
use App\Models\Master\JenisPeriksaPenunjang;
use App\Models\Master\JenisProduk;
use App\Models\Master\JenisRekanan;
use App\Models\Master\KategoryProduk;
use App\Models\Master\Pegawai;
use App\Models\Master\Provinsi;
use App\Models\Master\Rekanan;
use App\Models\Master\KelompokPasien;
use App\Models\Master\KelompokProduk;
use App\Models\Master\KelompokProdukBpjs;
use App\Models\Master\LevelProduk;
use App\Models\Master\MerkProduk;
use App\Models\Master\Produk;
use App\Models\Master\ProdusenProduk;
use App\Models\Master\Rhesus;
use App\Models\Master\SatuanStandar;
use App\Models\Master\StatusProduk;
use App\Models\Master\TypeProduk;
use App\Models\Master\WarnaProduk;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MasterProdukCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function masterProduk(Request $r)
    {

        $kelompokUser = DB::table('loginuser_s as lg')
            ->join('kelompokuser_s as ku', 'ku.id', 'lg.objectkelompokuserfk', 'ku.id')
            ->select('lg.namauser', 'ku.kelompokuser', 'ku.id')
            ->where('lg.id', $this->getUserId())
            ->first();


        $data  = DB::table('produk_m as pk')
            ->leftjoin('detailjenisproduk_m as djp', 'pk.objectdetailjenisprodukfk', '=', 'djp.id')
            ->leftjoin('jenisproduk_m as jp', 'djp.objectjenisprodukfk', '=', 'jp.id')
            ->leftjoin('bentukproduk_m as bp', 'pk.objectbentukprodukfk', '=', 'bp.id')
            ->leftjoin('bahanproduk_m as bh', 'pk.objectbahanprodukfk', '=', 'bh.id')
            ->leftjoin('kelompokprodukbpjs_m as kpb', 'pk.objectkelompokprodukbpjsfk', '=', 'kpb.id')
            ->select(
                'pk.id',
                'jp.jenisproduk',
                'djp.detailjenisproduk',
                'pk.statusenabled',
                'pk.isgeneric',
                'pk.kodeexternal',
                'pk.namaexternal',
                'pk.keterangan',
                'pk.norec',
                'pk.reportdisplay',
                'pk.deskripsiproduk',
                'pk.isprodukintern',
                'pk.kdbarcode',
                'pk.kdproduk',
                'pk.kdproduk_intern',
                'pk.objectdetailjenisprodukfk',
                'djp.objectjenisprodukfk',
                'pk.objectkategoryprodukfk',
                'pk.kekuatan',
                'pk.namaproduk',
                'pk.nilainormal',
                'pk.qproduk',
                'pk.qtyjualterkecil',
                'pk.qtykalori',
                'pk.qtykarbohidrat',
                'pk.objectkelompokprodukfk',
                'pk.objectgenerikfk',
                'pk.objectjenisgenerikfk',
                'pk.objectlevelprodukfk',
                'pk.objectdepartemenfk',
                'pk.objectbentukprodukfk',
                'pk.objectbahanprodukfk',
                'pk.objecttypeprodukfk',
                'pk.objectwarnaprodukfk',
                'pk.objectmerkprodukfk',
                'pk.objectdetailobatfk',
                'pk.objectgolonganprodukfk',
                'pk.objectdetailgolonganprodukfk',
                'pk.objectsatuanstandarfk',
                'pk.qtylemak',
                'pk.qtyporsi',
                'pk.qtyprotein',
                'pk.qtysatukemasan',
                'pk.qtyterkecil',
                'pk.kdprodukintern',
                'pk.objectjenisperiksapenunjangfk',
                'pk.bahansamplefk',
                'pk.kodebmn',
                'pk.spesifikasi',
                'pk.golongandarahfk',
                'pk.objectstatusprodukfk',
                'pk.rhesusfk',
                'pk.tglproduksi',
                'pk.status',
                'pk.verifikasianggaran',
                'pk.isarvdonasi',
                'pk.isnarkotika',
                'pk.ispsikotropika',
                'pk.isonkologi',
                'pk.isoot',
                'pk.isprekusor',
                'pk.objectprodusenprodukfk',
                'bp.namabentukproduk',
                'pk.objectrekananfk',
                'bh.namabahanproduk',
                'kpb.kelompokprodukbpjs',
                'pk.objectkelompokprodukbpjsfk'
            )
            ->where('pk.kdprofile', $this->kdProfile)
            ->where(function ($query) use ($kelompokUser) {
                if ($kelompokUser && $kelompokUser->id == 6) {
                    $query->whereIn('pk.objectdetailjenisprodukfk', [
                        1469, 1470, 1471, 1472, 1473, 1474, 1475,
                        1587, 1588, 1589, 1591, 1597, 474, 1346, 1463,
                        1464, 1476, 1593, 1904, 1346, 1346, 1346, 1346,
                        1346, 1346, 1346, 1346, 1346, 444, 899, 1318,
                        763, 796, 797, 808, 1082, 1398, 1430, 1434,
                        1493, 1657, 1910, 1956, 1958, 2219
                    ]);
                }
            })
            ->where('pk.statusenabled', true);

        $count = 0;
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pk.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('pk.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pk.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['objectdetailjenisprodukfk']) && $r['objectdetailjenisprodukfk'] != '') {
            $data = $data->where('pk.objectdetailjenisprodukfk', '=', $r['objectdetailjenisprodukfk']);
        }
        $count = $data->count();
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->orderByDesc('pk.id', 'desc');
        $data = $data->get();

        $res['data'] = $data;
        $res['count'] = $count;
        $res['keluser'] = $kelompokUser;
        return $this->respond($res);
    }
    public function masterProdukTidakAktif(Request $r)
    {

        $kelompokUser = DB::table('loginuser_s as lg')
            ->join('kelompokuser_s as ku', 'ku.id', 'lg.objectkelompokuserfk', 'ku.id')
            ->select('lg.namauser', 'ku.kelompokuser', 'ku.id')
            ->where('lg.id', $this->getUserId())
            ->first();


        $data  = DB::table('produk_m as pk')
            ->leftjoin('detailjenisproduk_m as djp', 'pk.objectdetailjenisprodukfk', '=', 'djp.id')
            ->leftjoin('jenisproduk_m as jp', 'djp.objectjenisprodukfk', '=', 'jp.id')
            ->leftjoin('bentukproduk_m as bp', 'pk.objectbentukprodukfk', '=', 'bp.id')
            ->leftjoin('bahanproduk_m as bh', 'pk.objectbahanprodukfk', '=', 'bh.id')
            ->leftjoin('kelompokprodukbpjs_m as kpb', 'pk.objectkelompokprodukbpjsfk', '=', 'kpb.id')
            ->select(
                'pk.id',
                'jp.jenisproduk',
                'djp.detailjenisproduk',
                'pk.statusenabled',
                'pk.isgeneric',
                'pk.kodeexternal',
                'pk.namaexternal',
                'pk.keterangan',
                'pk.norec',
                'pk.reportdisplay',
                'pk.deskripsiproduk',
                'pk.isprodukintern',
                'pk.kdbarcode',
                'pk.kdproduk',
                'pk.kdproduk_intern',
                'pk.objectdetailjenisprodukfk',
                'djp.objectjenisprodukfk',
                'pk.objectkategoryprodukfk',
                'pk.kekuatan',
                'pk.namaproduk',
                'pk.nilainormal',
                'pk.qproduk',
                'pk.qtyjualterkecil',
                'pk.qtykalori',
                'pk.qtykarbohidrat',
                'pk.objectkelompokprodukfk',
                'pk.objectgenerikfk',
                'pk.objectjenisgenerikfk',
                'pk.objectlevelprodukfk',
                'pk.objectdepartemenfk',
                'pk.objectbentukprodukfk',
                'pk.objectbahanprodukfk',
                'pk.objecttypeprodukfk',
                'pk.objectwarnaprodukfk',
                'pk.objectmerkprodukfk',
                'pk.objectdetailobatfk',
                'pk.objectgolonganprodukfk',
                'pk.objectdetailgolonganprodukfk',
                'pk.objectsatuanstandarfk',
                'pk.qtylemak',
                'pk.qtyporsi',
                'pk.qtyprotein',
                'pk.qtysatukemasan',
                'pk.qtyterkecil',
                'pk.kdprodukintern',
                'pk.objectjenisperiksapenunjangfk',
                'pk.bahansamplefk',
                'pk.kodebmn',
                'pk.spesifikasi',
                'pk.golongandarahfk',
                'pk.objectstatusprodukfk',
                'pk.rhesusfk',
                'pk.tglproduksi',
                'pk.status',
                'pk.verifikasianggaran',
                'pk.isarvdonasi',
                'pk.isnarkotika',
                'pk.ispsikotropika',
                'pk.isonkologi',
                'pk.isoot',
                'pk.isprekusor',
                'pk.objectprodusenprodukfk',
                'bp.namabentukproduk',
                'pk.objectrekananfk',
                'bh.namabahanproduk',
                'kpb.kelompokprodukbpjs',
                'pk.objectkelompokprodukbpjsfk'
            )
            ->where('pk.statusenabled', false)
            ->where(function ($query) use ($kelompokUser) {
                if ($kelompokUser && $kelompokUser->id == 6) {
                    $query->whereIn('pk.objectdetailjenisprodukfk', [
                        1469, 1470, 1471, 1472, 1473, 1474, 1475,
                        1587, 1588, 1589, 1591, 1597, 474, 1346, 1463,
                        1464, 1476, 1593, 1904, 1346, 1346, 1346, 1346,
                        1346, 1346, 1346, 1346, 1346, 444, 899, 1318,
                        763, 796, 797, 808, 1082, 1398, 1430, 1434,
                        1493, 1657, 1910, 1956, 1958, 2219
                    ]);
                }
            })   
            ->where('pk.kodeexternal', '=', 'OFFSIS')
            ->where('pk.kdprofile', $this->kdProfile);

        $count = 0;
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('pk.id', '=',  $r['id']);
        }
        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('pk.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pk.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['objectdetailjenisprodukfk']) && $r['objectdetailjenisprodukfk'] != '') {
            $data = $data->where('pk.objectdetailjenisprodukfk', '=', $r['objectdetailjenisprodukfk']);
        }
        $count = $data->count();
        if (isset($r['_total']) && $r['_total'] != '') {
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->orderByDesc('pk.id', 'desc');
        $data = $data->get();

        $res['data'] = $data;
        $res['count'] = $count;
        return $this->respond($res);
    }
    public function saveProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            $PSN =  $r['produk'];
            if ($PSN['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Produk(), 'id', $this->kdProfile); //$this->Uuid4();
                $dataPS = new Produk();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                // $dataPS->statusenabled = true;
            } else {
                $dataPS = Produk::where('id', $PSN['id'])
                    // ->where('statusenabled', true)
                    ->first();
                $id =  $dataPS->id;
            }
            $dataPS->kdproduk =  $PSN['kdproduk'];
            $dataPS->kdproduk_intern =  $PSN['kdproduk_intern'];
            $dataPS->kdbarcode =  $PSN['kdbarcode'];
            $dataPS->kodebmn =  $PSN['kodebmn'];
            $dataPS->namaproduk =  $PSN['namaproduk'];
            $dataPS->statusenabled = $PSN['statusenabled'];
            if ($PSN['statusenabled'] === "true") {
                $dataPS->kodeexternal = "ONSIS";
            } elseif ($PSN['statusenabled'] === "false") {
                $dataPS->kodeexternal = "OFFSIS";
            } 
            $dataPS->deskripsiproduk =  $PSN['deskripsiproduk'];
            $dataPS->isgeneric = $PSN['isgeneric'];
            $dataPS->keterangan = $PSN['keterangan'];
            $dataPS->reportdisplay = $PSN['reportdisplay'];
            $dataPS->objectdetailjenisprodukfk = $PSN['objectdetailjenisprodukfk'];
            $dataPS->objectkategoryprodukfk = $PSN['objectkategoryprodukfk'];
            $dataPS->objectkelompokprodukfk = $PSN['objectkelompokprodukfk'];
            $dataPS->objectgenerikfk = $PSN['objectgenerikfk'];
            $dataPS->objectjenisgenerikfk = $PSN['objectjenisgenerikfk'];
            $dataPS->objectlevelprodukfk = $PSN['objectlevelprodukfk'];
            $dataPS->isprodukintern = $PSN['isprodukintern'];
            $dataPS->objectdepartemenfk = $PSN['objectdepartemenfk'];
            $dataPS->objectbentukprodukfk = $PSN['objectbentukprodukfk'];
            $dataPS->objectbahanprodukfk = $PSN['objectbahanprodukfk'];
            $dataPS->objecttypeprodukfk = $PSN['objecttypeprodukfk'];
            $dataPS->objectwarnaprodukfk =  $PSN['objectwarnaprodukfk'];
            $dataPS->kekuatan =  $PSN['kekuatan'];
            $dataPS->objectmerkprodukfk =  $PSN['objectmerkprodukfk'];
            $dataPS->objectdetailobatfk =  $PSN['objectdetailobatfk'];
            $dataPS->spesifikasi =  $PSN['spesifikasi'];
            $dataPS->objectgolonganprodukfk =  $PSN['objectgolonganprodukfk'];
            $dataPS->objectdetailgolonganprodukfk =  $PSN['objectdetailgolonganprodukfk'];
            $dataPS->objectsatuanstandarfk =  $PSN['objectsatuanstandarfk'];
            $dataPS->qtykalori =  $PSN['qtykalori'];
            $dataPS->qtykarbohidrat =  $PSN['qtykarbohidrat'];
            $dataPS->qtylemak =  $PSN['qtylemak'];
            $dataPS->qtyporsi =  $PSN['qtyporsi'];
            $dataPS->qtyprotein =  $PSN['qtyprotein'];
            $dataPS->qtykarbohidrat =  $PSN['qtykarbohidrat'];
            $dataPS->objectjenisperiksapenunjangfk =  $PSN['objectjenisperiksapenunjangfk'];
            $dataPS->nilainormal =  $PSN['nilainormal'];
            $dataPS->bahansamplefk =  $PSN['bahansamplefk'];
            $dataPS->golongandarahfk =  $PSN['golongandarahfk'];
            $dataPS->rhesusfk =  $PSN['rhesusfk'];
            $dataPS->objectstatusprodukfk =  $PSN['objectstatusprodukfk'];
            $dataPS->isarvdonasi =  $PSN['isarvdonasi'];
            $dataPS->isnarkotika =  $PSN['isnarkotika'];
            $dataPS->ispsikotropika =  $PSN['ispsikotropika'];
            $dataPS->isonkologi =  $PSN['isonkologi'];
            $dataPS->isoot =  $PSN['isoot'];
            $dataPS->isprekusor =  $PSN['isprekusor'];
            $dataPS->isvaksindonasi =  $PSN['isvaksindonasi'];
            $dataPS->objectprodusenprodukfk =  $PSN['objectprodusenprodukfk'];
            $dataPS->objectrekananfk =  $PSN['objectrekananfk'];
            $dataPS->objectkelompokprodukbpjsfk =  $PSN['objectkelompokprodukbpjsfk'];


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
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function deleteProduk(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Produk::where('id', $r['id'])
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
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function masterProdukdropdown(Request $r)
    {
        //kategori
        $res['jenisproduk'] = JenisProduk::mine()->get();
        $res['detailjenisproduk'] = DetailJenisProduk::mine()->get();
        $res['kelompokproduk'] = KelompokProduk::mine()->get();
        $res['kategoryproduk'] = KategoryProduk::mine()->get();
        $res['levelproduk'] = LevelProduk::mine()->get();
        $res['generik'] = Generik::mine()->get();
        $res['namagproduk'] = GeneralProduk::mine()->get();
        //departemen
        $res['namadepartemen'] = Departemen::mine()->get();
        //spesifikasi
        $res['namabentukproduk'] = BentukProduk::mine()->get();
        $res['namabahanproduk'] = BahanProduk::mine()->get();
        $res['typeproduk'] = TypeProduk::mine()->get();
        $res['warnaproduk'] = WarnaProduk::mine()->get();
        $res['merkproduk'] = MerkProduk::mine()->get();
        $res['name'] = DetailObat::mine()->get();
        $res['golonganproduk'] = GolonganProduk::mine()->get();
        $res['detailgolonganproduk'] = DetailGolonganProduk::mine()->get();
        //satuan standar
        $res['satuanstandar'] = SatuanStandar::mine()->get();
        //penunjang
        //jenis periksa
        $res['jenisperiksa'] = JenisPeriksaPenunjang::mine()->get();
        $res['namabahansample'] = BahanSample::mine()->get();
        $res['golongandarah'] = GolonganDarah::mine()->get();
        $res['rhesus'] = Rhesus::mine()->get();
        // keuangan
        //farmasi
        $res['statusproduk'] = StatusProduk::mine()->get();
        //rekanan
        $res['namaprodusenproduk'] = ProdusenProduk::mine()->get();
        $res['namarekanan'] = Rekanan::mine()->get();
        $res['kelompokprodukbpjs'] = KelompokProdukBpjs::mine()->get();



        return $this->respond($res);
    }

    public function produkByID(Request $r)
    {
        $data  = DB::table('produk_m as pk')
            ->leftjoin('detailjenisproduk_m as djp', 'pk.objectdetailjenisprodukfk', '=', 'djp.id')
            ->leftjoin('jenisproduk_m as jp', 'djp.objectjenisprodukfk', '=', 'jp.id')
            ->leftjoin('bentukproduk_m as bp', 'pk.objectbentukprodukfk', '=', 'bp.id')
            ->select(
                'pk.id',
                'jp.jenisproduk',
                'djp.detailjenisproduk',
                'pk.statusenabled',
                'pk.isgeneric',
                'pk.kodeexternal',
                'pk.namaexternal',
                'pk.norec',
                'pk.reportdisplay',
                'pk.objectaccountfk',
                'pk.objectbahanprodukfk',
                'pk.objectbentukprodukfk',
                'pk.objectdepartemenfk',
                'pk.objectdetailgolonganprodukfk',
                'pk.objectdetailjenisprodukfk',
                'pk.objectkategoryprodukfk',
                'pk.objectprodusenprodukfk',
                'pk.objectsatuanbesarfk',
                'pk.objectsatuankecilfk',
                'pk.objectsatuanstandarfk',
                'pk.objectstatusprodukfk',
                'pk.objecttypeprodukfk',
                'pk.objectwarnaprodukfk',
                'pk.deskripsiproduk',
                'pk.isprodukintern',
                'pk.kdbarcode',
                'pk.kdproduk',
                'pk.kdproduk_intern',
                'pk.kekuatan',
                'pk.namaproduk',
                'pk.nilainormal',
                'pk.qproduk',
                'pk.qtyjualterkecil',
                'pk.qtykalori',
                'pk.qtykarbohidrat',
                'pk.qtylemak',
                'pk.qtyporsi',
                'pk.qtyprotein',
                'pk.qtysatukemasan',
                'pk.qtyterkecil',
                'pk.kdprodukintern',
                'pk.bahansamplefk',
                'pk.objectdetailobatfk',
                'pk.objectgenerikfk',
                'pk.objectmerkprodukfk',
                'pk.objectrekananfk',
                'pk.objectsediaanfk',
                'pk.objectstatusbarangfk',
                'pk.golongandarahfk',
                'pk.rhesusfk',
                'pk.kodebmn',
                'pk.spesifikasi',
                'pk.tglproduksi',
                'pk.status',
                'pk.verifikasianggaran',
                'pk.isarvdonasi',
                'pk.isnarkotika',
                'pk.ispsikotropika',
                'pk.isonkologi',
                'pk.isoot',
                'bp.namabentukproduk'

            )
            ->where('pk.kdprofile', (int)$this->kdProfile)
            ->where('pk.statusenabled', true);
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('pk.id', $r['id']);
        }
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('pk.id', $r['id']);
        }
        $result = array(
            'produk' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }

    public function listJenisProduk(Request $r)
    {
        $result = DB::table('kelompokproduk_m as kp')
            ->join('jenisproduk_m as jp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select('jp.id', 'jp.jenisproduk')
            ->where('kp.id', $r['id'])
            ->where('kp.statusenabled', true)
            ->where('jp.statusenabled', true)
            ->where('kp.kdprofile', (int)$this->kdProfile)
            ->orderBy('jp.jenisproduk')
            ->get();
        return $this->respond($result);
    }
    public function listDetailJenisProduk(Request $r)
    {
        $result = DB::table('jenisproduk_m as jp')
            ->join('detailjenisproduk_m as djp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->select('djp.id', 'djp.detailjenisproduk')
            ->where('jp.id', $r['id'])
            ->where('jp.statusenabled', true)
            ->where('djp.statusenabled', true)
            ->where('jp.kdprofile', (int)$this->kdProfile)
            ->orderBy('djp.detailjenisproduk')
            ->get();

        return $this->respond($result);
    }
    public function getCombo(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $dataJenisProduk = DB::table('jenisproduk_m as jp')
            ->where('jp.statusenabled', true)
            ->orderBy('jp.jenisproduk')
            ->get();

        $dataDetailJenisProduk = DB::table('detailjenisproduk_m as djp')
            ->where('djp.statusenabled', true)
            ->orderBy('djp.detailjenisproduk')
            ->get();

        $res['jenisproduk'] = $dataJenisProduk;
        $res['detailjenisproduk'] = $dataDetailJenisProduk;

        return $this->respond($res);
    }
    public function masterProdukSatset(Request $r)
    {
        $data  = DB::table('produk_m as pr')
            ->join('detailjenisproduk_m as dp', 'pr.objectdetailjenisprodukfk', '=', 'dp.id')
            ->select(
                'pr.id',
                'pr.namaproduk',
                'dp.detailjenisproduk',
                'pr.ihs_id',
                'pr.ihs_kfa_code',
                'pr.ihs_sediaan',
                'pr.ihs_sediaan_display',
                'pr.ihs_numerator_value',
                'pr.ihs_numerator_code',
                'pr.ihs_denominator_value',
                'pr.ihs_denominator_code',
                'pr.ihs_kfa_display',
                'pr.ihs_kfa_code_brand',
                'pr.ihs_kfa_code_kemasan',
                'pr.ihs_loinc_id',
                'pr.ihs_loinc_common_name',
                'pr.response_kfa'
            )
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->whereNotNull('pr.namaproduk');

        if (isset($r['ihs_id']) && $r['ihs_id'] != '') {
            $data = $data->whereNotNull('pr.ihs_id');
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        if (isset($r['obat']) && $r['obat'] != '') {
            $data = $data->whereIn('dp.objectjenisprodukfk', explode(',', $this->settingFix('kdJenisProdukObat')));
        }
        if (isset($r['nonobat']) && $r['nonobat'] != '') {
            $data = $data->whereNotIn('dp.objectjenisprodukfk', explode(',', $this->settingFix('kdJenisProdukObat')));
        }

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data = $data->orderByRaw('pr.namaproduk');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function updateProduk(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = Produk::where('id', $r['id'])->update([
                'ihs_loinc_id' => $r['ihs_loinc_id'],
                'ihs_loinc_common_name' => $r['ihs_loinc_common_name'],
                'ihs_kfa_code' => $r['ihs_kfa_code'],
                'ihs_kfa_code_brand' => $r['ihs_kfa_code_brand'],
                'ihs_kfa_code_kemasan' => $r['ihs_kfa_code_kemasan'],
                'ihs_sediaan' => $r['ihs_sediaan'],
                'ihs_sediaan_display' => $r['ihs_sediaan_display'],
                'response_kfa' => $r['response_kfa'],

            ]);

            DB::commit();
            $result = [
                'status' => 201,
                'message' => 'Sukses',
                'result' => $dataPS,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            $result = [
                'status' => 400,
                'message' => "Something Want Wrong",
                'result' => $e->getMessage(),
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }
}
