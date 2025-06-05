<?php

namespace App\Http\Controllers\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class OrderLaboratoriumCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function paketPreOP(Request $r){
        if($r['usia'] < 40){
            return $this->respond($this->settingFix('preOpKurang40'));
        }else{
            return $this->respond($this->settingFix('preOpLebih40'));
        }
    }
    public function paketLab($namaPaket){
        return $this->respond($this->settingFix($namaPaket));
        // switch ($namaPaket) {
        //     case 'elektrolit':
        //         return $this->respond($this->settingFix('elektrolit'));
        //         break;
        //     case 'fungsiginjal':
        //         return $this->respond($this->settingFix('fungsiginjal'));
        //         break;
        //     case 'fungsiginjal':
        //         return $this->respond($this->settingFix('fungsiginjal'));
        //         break;
        //     default:
        //         # code...
        //         break;
        // }
    }
    public function headerPasienOrder(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =   DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last  = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listDropdown(Request $r)
    {
        $res['ruanganLab'] = Ruangan::mine()
            ->where('objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->orderBy('kodeexternal', 'ASC')
            ->get();

        return $this->respond($res);
    }
    public function listTindakanForOrder(Request $r)
    {
        $setting = $this->settingFix('isFilterProdukLab');
        $kdProfile = $this->kdProfile;
        $detail = DB::table('detailjenisproduk_m')
            ->select('id', 'detailjenisproduk')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->get();
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'prd.objectdetailjenisprodukfk',
                'mpr.objectruanganfk',
                'prd.namaproduk',
                DB::raw('MAX(hnp.hargasatuan) as hargasatuan')
            )
            ->leftjoin('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.objectruanganfk', $r['ruanganfk'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        // if(!empty($setting) && $setting == 'true'){
        //     $data = $data->where('prd.isorderlab', true);
        // }else{
        //     $data = $data->whereRaw("(prd.isorderlab is null or prd.isorderlab =false ) ");
        // }
        $data = $data->groupBy('mpr.objectprodukfk', 'prd.namaproduk', 'prd.objectdetailjenisprodukfk', 'mpr.objectruanganfk');
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        foreach ($detail as $key => $value) {
            $value->details = [];
        }
        $i = 0;
        $detail = $detail->toArray();
        foreach ($detail as $value) {
            foreach ($data as $value2) {
                if ($detail[$i]->id == $value2->objectdetailjenisprodukfk) {
                    $detail[$i]->details[] = $value2;
                }
            }
            $i++;
        }

        for ($i = count($detail) - 1; $i >= 0; $i--) {
            if (count($detail[$i]->details) == 0) {
                array_splice($detail, $i, 1);
            }
        }
        $result = array(
            'list_tindakan' => $detail,
            'data' => $data,
            'as' => '@epic',
        );

        return $this->respond($result);
    }

    public function listTindakan(Request $r)
    {
        $data = DB::table('mapruangantoproduk_m as mpr')
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            ->select(
                'mpr.objectprodukfk as id',
                'prd.namaproduk',
                'mpr.objectruanganfk',
                'prd.namaproduk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('mpr.objectruanganfk', $r['idruangan'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);

        if (
            isset($r['name']) &&
            $r['name'] != "" &&
            $r['name'] != "undefined"
        ) {
            $data = $data
                ->where('prd.namaproduk', 'ilike', '%' . $r['name'] . '%');
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }
        $data = $data->orderBy('prd.namaproduk', 'ASC');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function simpanOrderLab(Request $request)
    {

        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;


            $dataPD = PasienDaftar::where('norec', $request['norec_pd'])->first();
            $pasien = DB::table('pasien_m')->where('id', $dataPD->nocmfk)->first();

            if ($request['norec_so'] == "") {
                $setting = json_decode($this->settingFix('settingSeqNoOrder'));
                $noOrder = '';
                $ket = '';
                $kelompokTransaksi = null;
                foreach ($setting as $set) {
                    if ($request['departemenfk']  == $set->objectdepartemenfk) {
                        $noOrder = $this->SEQUENCE_NEXVAL(new StrukOrder(), $set->seqname, 11, $set->prefix . date('ym'), $idProfile);
                        $ket = $set->desc;
                        $kelompokTransaksi = $set->kelompoktransfk;
                        break;
                    }
                }

                if ($noOrder == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array("status" => 400, "result"  => null);
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }

                $dataSO = new StrukOrder;
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $idProfile;
                $dataSO->statusenabled = true;
                $dataSO->nocmfk = $dataPD->nocmfk;

            }
            else{
                $dataSO =  StrukOrder::where('norec',$request['norec_so'] )->first();
                $noOrder = $dataSO->noorder;
                $ket = $dataSO->keteranganorder;
                $kelompokTransaksi =  $dataSO->objectkelompoktransaksifk;
                OrderPelayanan::where('strukorderfk',$request['norec_so'])->delete();
            }

            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $dataPD->norec;
            $dataSO->objectpegawaiorderfk = $request['pegawaiorderfk'];
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = $request['qtyproduk'];
            $dataSO->objectruanganfk = $request['objectruanganfk'];
            $dataSO->objectruangantujuanfk = $request['objectruangantujuanfk'];
            $dataSO->keteranganorder = $ket;
            $dataSO->objectkelompoktransaksifk = $kelompokTransaksi;
            $dataSO->tglpelayananakhir = $request['tgloperasi'];
            $dataSO->tglpelayananawal = $request['tgloperasi'];
            $dataSO->tglrencana = $request['tglrencana'];
            $dataSO->keteranganlainnya = $request['keterangan'];
            $dataSO->catatanklinis = $request['catatanKlinis'];
            $dataSO->jenisoperasifk = $request['jenisoperasifk'];
            $dataSO->tglorder = $request['tanggal'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->statusorder = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->cito = $request['iscito'];
            $dataSO->estimasiwaktuoperasi = isset($request['estimasiwaktuoperasi']) ? $request['estimasiwaktuoperasi'] : null;
            $dataSO->noregistrasi = $request['noregistrasi'];
            $dataSO->norec_apd = $request['norec_apd'];
            $dataSO->objectpetugasfk = $this->getPegawaiId();
            $dataSO->save();

            $dataSOnorec = $dataSO->norec;

            $listProduk = '';
            $prod = [];

            foreach ($request['details'] as $item) {
                if (isset($request['status'])  && $request['status']== 'bridinglangsung') {
                    PelayananPasien::where('norec', $item['norec_pp'])
                        ->where('kdprofile', $idProfile)
                        ->update(
                            [
                                'strukorderfk' => $dataSOnorec
                            ]
                        );
                }
                $pro = DB::table('produk_m')->where('id', $item['produkfk'])->first();
                $prod[] = $pro->namaproduk;

                $listProduk = $listProduk . ',' . $pro->namaproduk;
                $listProduk = substr($listProduk, 1, strlen($listProduk) - 1);

                $dataOP = new OrderPelayanan();
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $idProfile;
                $dataOP->statusenabled = true;
                $dataOP->iscito = $request['iscito'];
                $dataOP->noorderfk = $dataSOnorec;
                $dataOP->objectprodukfk = $item['produkfk'];
                $dataOP->qtyproduk = $item['qtyproduk'] ?? 0;
                // $dataOP->objectkelasfk = $item['objectkelasfk'];
                $dataOP->objectkelasfk = 2;
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectruanganfk = $request['objectruanganfk'];
                $dataOP->objectruangantujuanfk = $request['objectruangantujuanfk'];
                $dataOP->strukorderfk = $dataSOnorec;
                if (isset($item['pemeriksaanluar'])) {
                    if ($item['pemeriksaanluar'] == 1) {
                        $dataOP->keteranganlainnya = 'isPemeriksaanKeluar';
                    }
                }
                if (isset($item['tglrencana'])) {
                    $dataOP->tglpelayanan = $item['tglrencana'];
                } else {
                    $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                }
                if (isset($item['dokterid']) && $item['dokterid'] != "") {
                    $dataOP->objectnamapenyerahbarangfk = $item['dokterid'];
                }
                if(isset($item['posisi'])){
                    $dataOP->keteranganlainnya = $item['posisi'];
                }
                $dataOP->nourut = $item['nourut'];
                $dataOP->noregistrasi = $request['noregistrasi'];
                $dataOP->save();
            }

            $dataSO->tanggal_server =  date('Y-m-d H:i:s');

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
                    "data"  => $dataSO,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  =>$e->getMessage(). ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function listRiwayatOrder(Request $r){
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenLab');

        $query1 = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'op.noorderfk', '=', 'so.norec')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as p', 'p.id', '=', 'so.objectpegawaiorderfk')
            ->selectRaw("
                so.tglorder,
                so.noorder,
                pr.id,
                pr.namaproduk,
                op.qtyproduk,
                so.norec,
                ru.namaruangan as ruanganasal,
                p.namalengkap as dokter,
                ru2.namaruangan as ruanganlab,
                case 
                    when so.statusorder = 1 then 'verifikasi'
                    when so.statusorder = 2 then 'selesai'
                    else 'pending'
                end as status,
                case 
                    when so.statusorder = 1 then 'info'
                    when so.statusorder = 2 then 'success'
                    else 'warning'
                end as color_status,
                so.norec_apd,
                op.norec as norec_op
            ")
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->where('so.statusenabled', true)
            ->where('ru2.objectdepartemenfk', $depLab);

            if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
                $query1 = $query1->where('pd.nocmfk', $r['nocmfk']);
            }
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $query1 = $query1->where('pd.norec', $r['norec_pd']);
            }
        

        $query2 = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'pd.objectruanganlastfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftJoin('pegawai_m as p', 'p.id', '=', 'apd.objectpegawaifk')
            ->selectRaw("
                pp.tglpelayanan as tglorder,
                null as noorder,
                pr.id,
                pr.namaproduk,
                pp.jumlah as qtyproduk,
                pp.norec,
                ru2.namaruangan as ruanganasal,
                p.namalengkap as dokter,
                ru.namaruangan as ruanganlab,
                'selesai' as status,
                'success' as color_status,
                apd.norec as norec_apd,
                null as norec_op
            ")
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->whereNull('pp.strukorderfk')
            ->where('ru.objectdepartemenfk', $depLab);

            if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
                $query2 = $query2->where('pd.nocmfk', $r['nocmfk']);
            }
            if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
                $query2 = $query2->where('pd.norec', $r['norec_pd']);
            }

        $data = $query1->unionAll($query2)
            ->orderBy('tglorder')
            ->get();

        $group = [];
        $detailsMap = [];
        
        foreach ($data as $item) {
            if (!isset($detailsMap[$item->norec])) {
                $detailsMap[$item->norec] = [];
            }
            $detailsMap[$item->norec][] = [
                'namaproduk' => $item->namaproduk,
                'pp_norec' => $item->norec,
            ];
        
            if (!isset($group[$item->norec])) {
                $group[$item->norec] = [
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'norec' => $item->norec,
                    'ruanganasal' => $item->ruanganasal,
                    'ruanganlab' => $item->ruanganlab,
                    'dokter' => $item->dokter,
                    'color_status' => $item->color_status,
                    'status' => $item->status,
                    'norec_apd' => $item->norec_apd,
                    'details' => [], 
                ];
            }
        }
        foreach ($group as $norec => &$itemGroup) {
            $itemGroup['details'] = $detailsMap[$norec];
        }
        
        $group = array_values($group);
        
        return $this->respond($group);            
    }

    public function hapusOrderLab(Request $request)
    {
        DB::beginTransaction();
        try {
            $idProfile = $this->kdProfile;

            StrukOrder::where('noorder',$request['noorder'])
            ->where('kdprofile',$idProfile)
            ->delete();

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
                    "data"  => null,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Data Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function detailOrder(Request $r){
        $kdProfile =  $this->kdProfile;

        $data = collect(DB::select("select so.tglorder,so.noorder,
        pr.id as produkfk,pr.namaproduk,op.qtyproduk,so.norec,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,
        so.objectpegawaiorderfk,so.objectruangantujuanfk,
        so.keteranganlainnya,
        op.norec as norec_op,so.cito
        from strukorder_t as so
        inner join orderpelayanan_t as op on op.noorderfk = so.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and so.norec='$r[norec]'
        "));
        return $this->respond($data);
    }

    public function saveBerkasLab(Request $r)
    {
        DB::beginTransaction();
        try {

            $uploadBerkasPasien = $r->file('file');
            $path = 'berkas_lab/' . $r['norec_so'];
            $extension = $uploadBerkasPasien->getClientOriginalExtension();
            $filename =  $r['norec_so']. '.' . $extension;
            StrukOrder::where('norec',$r['norec_so'])->update([
            'namafile' => $filename]);


            $r->fPut(
                $path . '/' . $filename,
                File::get($r->file('file')->getRealPath()),
                'public'
            );
            DB::commit();
            $transMessage = 'Sukses';
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = 'Simpan Gagal';
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function updateFilterProd(Request $request)
    {
        DB::beginTransaction();
        try {
            SettingDataFixed::where('namafield', 'isFilterProdukLab')
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'nilaifield' => $request['isFilterProdukLab']
                    ]
                );

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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  =>$e->getMessage(). ' '.$e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}

