<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Master\Kamar;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\JadwalBedah;
use App\Models\Transaksi\MasterPelaksanaOperasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Mockery\Exception\InvalidOrderException;
use PhpParser\Node\Stmt\TryCatch;

class DashboardBedahCtrl extends Controller
{

    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listBedah(Request $r)
    {
        $set = explode(',', $this->settingFix('idDepartemenBedah'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();
        $res['kamar'] = Kamar::mine()->get();
        $res['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $res['namalengkap'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $res['idJenisPegawaiDokter'])
            ->search($r['label'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($res);
    }

    public function getOrderBedah(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->leftJoin('jenisoperasi_m as jp', 'jp.id', 'so.jenisoperasifk')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk','pd.norec')
            ->join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->join('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->join('departemen_m as dep', 'dep.id', 'ruAs.objectdepartemenfk')
            ->join('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'so.statusjadwalbedah',
                'so.norec_apd',
                'jp.jenisoperasi',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'so.tglpelayananawal as tgloperasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'pd.nocmfk',
                'so.estimasiwaktuoperasi as estimasiwaktuoperasi',
                'pas.objectjeniskelaminfk',
                DB::raw("CAST(so.tglorder AS DATE)"),
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'pd.objectkelasfk',
                'pd.objectrekananfk',
                'dep.namadepartemen as asldepartemen',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'so.objectruangantujuanfk',
                'so.objectpegawaiorderfk',
                'so.cito',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pas.noidentitas',
                'pas.nobpjs',
                'pa.nosep',
                'pd.objectkelasfk'
            )
            // ->where(DB::raw("CAST(so.tglorder AS DATE)"), Date("Y-m-d"))
            ->where('so.kdprofile', $this->kdProfile)
            // ->where('so.objectruangantujuanfk', $this->settingFix('idDepartemenLab'))
            ->whereIn('dep2.id', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('so.statusenabled', true);

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $dataOrder = $dataOrder->where(function ($query) use ($searchTerm) {
                $query->where('pas.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('pas.nocm', 'ilike', $searchTerm)
                    ->orWhere('pas.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('so.noorder', 'ilike', $searchTerm)
                    ->orWhere('pas.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=',  $request['ruanganid']);
        }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['dari']) && $request['dari'] != '') {
            $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '<=', $request->sampai);
        }
        if (isset($request['qnamapasien']) && $request['qnamapasien'] != '') {
            $dataOrder = $dataOrder->where('pas.namapasien', '=', $request['qnamapasien']);
        }
        if (isset($request['qnoregistrasi']) && $request['qnoregistrasi'] != '') {
            $dataOrder = $dataOrder->where('pd.noregistrasi', '=', $request['qnoregistrasi']);
        }
        if (isset($request['qnocm']) && $request['qnocm'] != '') {
            $dataOrder = $dataOrder->where('pas.nocm', '=', $request['qnocm']);
        }


        $dataOrder = $dataOrder->get();

        $result = [];
        foreach ($dataOrder as $datas) {

            // $detail = DB::table('detaildiagnosapasien_t as ddp')
            //     ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            //     ->Join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            //     ->select('dg.kddiagnosa', 'dg.namadiagnosa', 'jd.jenisdiagnosa')
            //     // ->where('ddp.objectdiagnosapasienfk', $datas->dp_norec)
            //     ->where('ddp.kdprofile', $this->kdProfile)
            //     ->get();

            $result[] = [
                'namapasien' => $datas->namapasien,
                'noidentitas' => $datas->noidentitas,
                'noregistrasi' => $datas->noregistrasi,
                'so_norec' => $datas->norec,
                'jenisoperasi' => $datas->jenisoperasi,
                'pd_norec' => $datas->pd_norec,
                'nocmfk' => $datas->nocmfk,
                'iscito' => $datas->cito,
                'jenispelayananfk' => $datas->jenispelayananfk,
                'noorder' => $datas->noorder,
                'tglregistrasi' => $datas->tglregistrasi,
                'tgloperasi' => $datas->tgloperasi,
                'nocm' => $datas->nocm,
                'statusorder' => $datas->statusorder,
                'statusjadwalbedah' => $datas->statusjadwalbedah,
                'norec_apd' => $datas->norec_apd,
                'jeniskelamin' => $datas->jeniskelamin,
                'id_jenisK' => $datas->objectjeniskelaminfk,
                'kelompokpasien' => $datas->kelompokpasien,
                'namakelas' => $datas->namakelas,
                'objectrekananfk' => $datas->objectrekananfk,
                'objectkelasfk' => $datas->objectkelasfk,
                'asal_departemen' => $datas->asldepartemen,
                'asal_ruangan' => $datas->asalruangan,
                'ruangantujuan' => $datas->ruangantujuan,
                'objectruangantujuanfk' => $datas->objectruangantujuanfk,
                'departementujuan' => $datas->departementujuan,
                'nama_pegawai' => $datas->namalengkap,
                'tglorder' => $datas->tglorder,
                'objectpegawaiorderfk' => $datas->objectpegawaiorderfk,
                'estimasiwaktuoperasi' => $datas->estimasiwaktuoperasi,
                'umur' => $this->getAge($datas->tgllahir, $datas->tglorder),
                'nosep' => $datas->nosep,
                'objectkelasfk' => $datas->objectkelasfk
                // 'detailDiagnosa' => $detail
            ];
        }
        return $this->respond($result);
    }

    public function getJadwalOperasi(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'so.norec', '=', 'op.strukorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
            ->join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->leftjoin('pegawai_m as peg', 'peg.id', 'apd.objectpegawaifk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')

            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')

            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'so.tglpelayananawal',
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'peg.namalengkap',
                'pr.namaproduk',
            )
            ->where(DB::raw("CAST(so.tglpelayananawal AS DATE)"), $request['tgl'])
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusorder', '1')
            ->where('ruTu.objectdepartemenfk', $this->settingFix('idDepartemenBedah'))
            ->where('so.statusenabled', true);

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=',  $request['ruanganid']);
        }
        // if(isset($request['tgl']) && $request['tgl'] !=''){
        //     $dataOrder = $dataOrder ->whereRaw("to_char(so.tglpelayananawal,'yyyy-MM-dd') = '$request[tgl]'");
        // }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }

        $dataOrder = $dataOrder->get();

        $res['dataOperasi'] = $dataOrder;
        return $this->respond($res);
    }

    public function getOrderPelayananBedah(Request $request)
    {
        $so = StrukOrder::where('norec', $request['strukorderfk'])->where('kdprofile', $this->kdProfile)->first();
        $pasienDaftar = PasienDaftar::where('norec', $so->noregistrasifk)->where('kdprofile', $this->kdProfile)->first();
        $jp = (int)$pasienDaftar->jenispelayanan;
        $idpenjamin = '-1'; // $pasienDaftar->objectrekananfk == null ? '-1' : $pasienDaftar->objectrekananfk;

        if ($idpenjamin != "-1") {
            $dataOrderPelayanan = DB::table('orderpelayanan_t as op')
                ->leftjoin('strukorder_t as so', 'so.norec', 'op.strukorderfk')
                ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', 'hnp.objectprodukfk', 'pr.id')
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru2', 'so.objectruanganfk', 'ru2.id')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                // ->leftjoin('jadwalbedah_t as jmbt', 'jmbt.noregistrasifk', '')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')->on('op.objectprodukfk', '=', 'pps.produkfk');
                })
                ->select(DB::raw(
                    "DISTINCT op.norec as norec_op,
                    so.tglpelayananawal as tgloperasi,
                    pr.id as prid,
                    pr.namaproduk,
                    hnp.objectkelasfk,
                    op.tglpelayanan,
                    op.qtyproduk,
                    ru.namaruangan as ruangantujuan,
                    ru2.namaruangan as asalruangan,
                    ru.objectdepartemenfk,
                    op.strukorderfk,
                    so.objectruangantujuanfk,
                    hnp.hargasatuan,
                    kls.namakelas,
                    dpm.namadepartemen,
                    pps.norec as norec_pp, CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('op.kdprofile', $this->kdProfile)
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('kls.id', $request['objectkelasfk'])
                ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.objectjenispelayananfk', $jp)
                ->where('hnp.objectpenjaminfk', $idpenjamin)
                ->get();
        } else {
            $dataOrderPelayanan = [];
        }
        if (count($dataOrderPelayanan) == 0) {
            $dataOrderPelayanan = DB::table('orderpelayanan_t as op')
                ->leftjoin('strukorder_t as so', 'so.norec', 'op.strukorderfk')
                ->leftjoin('produk_m as pr', 'pr.id', 'op.objectprodukfk')
                ->leftjoin('harganettoprodukbykelas_m as hnp', 'pr.id', 'hnp.objectprodukfk')
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'so.objectruangantujuanfk')
                ->leftjoin('ruangan_m as ru2',  'ru2.id', 'so.objectruanganfk')
                ->leftjoin('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
                ->leftjoin('pelayananpasien_t as pps', function ($join) {
                    $join->on('pps.strukorderfk', '=', 'so.norec')
                        ->on('pps.produkfk', '=', 'pr.id')->on('pps.kdprofile', 'so.kdprofile');
                })

                ->select(DB::raw("op.norec as norec_op,
                pr.id as prid,
                so.tglpelayananawal as tgloperasi,
                pr.namaproduk,op.tglpelayanan,
                op.qtyproduk ,
                ru.namaruangan as ruangantujuan,
                ru2.namaruangan as asalruangan,
                ru.objectdepartemenfk,
                op.strukorderfk,
                so.objectruangantujuanfk,
                hnp.objectkelasfk,
                hnp.hargasatuan,
                kls.namakelas,
                dpm.namadepartemen,
                pps.norec as norec_pp, CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"))
                ->where('op.kdprofile', $this->kdProfile)
                ->where('hnp.objectkelasfk', $request['objectkelasfk'])
                ->where('hnp.statusenabled', true)
                ->where('hnp.objectpenjaminfk', null)
                ->where('op.strukorderfk', $request['strukorderfk'])
                ->where('hnp.objectjenispelayananfk', $jp)
                ->get();
        } else {
            return $this->respond('nothing');
        }

        $result = [];
        foreach ($dataOrderPelayanan as $item) {
            $datas = DB::table('harganettoprodukbykelasd_m as hnp')
                ->leftjoin('produk_m as prd', 'prd.id', 'hnp.objectprodukfk')
                ->leftjoin('komponenharga_m as kh', 'kh.id', 'hnp.objectkomponenhargafk')
                ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                ->select(DB::raw(
                    "distinct hnp.objectkomponenhargafk,kh.komponenharga,hnp.hargasatuan,
                                          hnp.objectprodukfk,hnp.objectjenispelayananfk,
                                          CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                ))
                ->where('hnp.kdprofile', $this->kdProfile)
                ->where('hnp.objectkelasfk', $item->objectkelasfk)
                ->where('hnp.objectpenjaminfk', $idpenjamin)
                ->where('hnp.statusenabled', true)
                ->where('objectjenispelayananfk', $jp)
                ->where('prd.id', $item->prid)
                ->get();

            if (count($datas) == 0) {
                $datas = DB::table('harganettoprodukbykelasd_m as hnp')
                    ->leftjoin('produk_m as prd', 'prd.id', 'hnp.objectprodukfk')
                    ->leftjoin('komponenharga_m as kh', 'kh.id', 'hnp.objectkomponenhargafk')
                    ->leftjoin('kelas_m as kls', 'kls.id', 'hnp.objectkelasfk')
                    ->select(DB::raw(
                        "distinct hnp.objectkomponenhargafk,kh.komponenharga,hnp.hargasatuan,
                                              hnp.objectprodukfk,hnp.objectjenispelayananfk,
                                              CASE WHEN hnp.hargadijamin IS NULL THEN 0 ELSE hnp.hargadijamin END AS hargadijamin"
                    ))
                    ->where('hnp.kdprofile', $this->kdProfile)
                    ->where('hnp.objectkelasfk', $item->objectkelasfk)
                    ->where('hnp.objectpenjaminfk', null)
                    ->where('hnp.statusenabled', true)
                    ->where('hnp.objectjenispelayananfk', $jp)
                    ->where('prd.id', $item->prid)
                    ->get();
            }
            $nilaiCito = 0;
            $nilaiStatusCito = 0;

            $result[] = array(
                'norec_op' => $item->norec_op,
                'norec_pp' => $item->norec_pp,
                'prid' => $item->prid,
                'tgloperasi' => $item->tgloperasi,
                'namaproduk' => $item->namaproduk,
                'qtyproduk' => $item->qtyproduk,
                'tglpelayanan' => $item->tglpelayanan,
                'idruangan' => $item->objectruangantujuanfk,
                'ruangantujuan' => $item->ruangantujuan,
                'asalruangan' => $item->asalruangan,
                'hargasatuan' => $item->hargasatuan,
                'hargadijamin' => $item->hargadijamin,
                'namakelas' => $item->namakelas,
                'objectdepartemenfk' => $item->objectdepartemenfk,
                'namadepartemen' => $item->namadepartemen,
                'cito' => $nilaiCito,
                'nilaiStatusCito' => $nilaiStatusCito,
                'objectrekananfk' => $pasienDaftar->objectrekananfk,
                'komponenharga' => $datas,
            );
        }

        return $this->respond($result);
    }

    public function getKomponenHargaBedah(Request $request)
    {
        $data = DB::table('harganettoprodukbykelasd_m as hnp')
            ->join('produk_m as prd', 'prd.id', '=', 'hnp.objectprodukfk')
            ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
            ->join('kelas_m as kls', 'kls.id', '=', 'hnp.objectkelasfk')
            ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'hnp.objectprodukfk', 'kh.iscito')
            ->where('hnp.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idKelas'])
            ->where('hnp.objectprodukfk', $request['idProduk'])
            ->where('hnp.objectjenispelayananfk', $request['idJenLayan'])
            ->where('hnp.statusenabled', true)
            ->distinct()
            ->get();

        return $this->respond($data);
    }

    public function getPelayanaBedah(Request $request)
    {
        $datas = DB::table('mapruangantoproduk_m as mpr')
            ->join('harganettoprodukbykelas_m as hnp', function ($join) {
                $join->on('hnp.objectprodukfk', 'mpr.objectprodukfk')
                    ->where('hnp.statusenabled', true);
            })
            ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
            // ->join ('kelas_m as kls','kls.id','hnp.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mpr.objectruanganfk')
            ->select(
                'mpr.id',
                'prd.id as idpro',
                'prd.namaproduk',
                'hnp.hargasatuan',
                'ru.namaruangan',
                'mpr.objectprodukfk',
                'mpr.objectruanganfk'
            )
            ->where('mpr.kdprofile', $this->kdProfile)
            ->where('hnp.objectkelasfk', $request['idkelas'])
            ->where('hnp.objectjenispelayananfk', $request['idjenispelayanan'])
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('mpr.statusenabled', true);

        if ($request->idProduk) {
            $datas = $datas->where('mpr.objectprodukfk', $request['idProduk']);
        }
        $datas = $datas->get();

        return $this->respond($datas);
    }

    // public function ListVerif()
    // {
    //     $praktek  = DB::table('jadwaldokter_m as jd')
    //         ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
    //         ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
    //         ->select(
    //             'ru.id',
    //             'ru.namaruangan',
    //             'pg.namalengkap',
    //             'jd.jammulai',
    //             'jd.jamakhir',
    //             DB::raw("lower(jd.hari) as hari"),
    //         )
    //         ->where('jd.kdprofile', $this->kdProfile)
    //         ->where('jd.statusenabled', '=', 'true')
    //         ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenBedah'));

    //     $praktek =  $praktek->get();

    //     return $this->respond($praktek);
    // }

    public function savePelayananPasienBedah(Request $request)
    {
        $parameter = $request['parameter'];

        // return $parameter;
        
        $dataOrder = $request['data'];
        DB::beginTransaction();
        $apd = AntrianPasienDiperiksa::where('noregistrasifk', $parameter['pd_norec'])
            ->where('kdprofile', $this->kdProfile)
            ->where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('statusenabled', true)
            ->first();
        $getLastAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $parameter['idruangtujuan'])
            ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 00:00')
            ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($parameter['tglregistrasi'])) . ' 23:59')
            ->where('statusenabled', true)
            ->max('noantrian');
        if (!$apd) {

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;;
            $dataAPD->objectasalrujukanfk = 1;
            $dataAPD->statusenabled = true;
            $dataAPD->objectkelasfk = $parameter['objectkelasfk'];
            $dataAPD->noantrian = $getLastAntrian + 1;
            $dataAPD->noregistrasifk = $parameter['pd_norec'];
            $dataAPD->objectpegawaifk = $parameter['objectpegawaiorderfk'];
            $dataAPD->objectruanganfk = $parameter['idruangtujuan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectstrukorderfk = $parameter['so_norec'];
            $dataAPD->tglregistrasi = $parameter['tglregistrasi']; // date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->noregistrasi =  $parameter['noregistrasi'];
            $dataAPD->save();
            $dataAPDnorec = $dataAPD->norec;
            $dataAPDtglPel = $dataAPD->tglregistrasi;
        } else {
            $dataAPDnorec = $apd->norec;
            $dataAPDtglPel = $apd->tglregistrasi;
        }

        try {
            StrukOrder::where('norec', $parameter['so_norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'statusorder' => 1,
                        'norec_apd' => $dataAPDnorec,
                        'estimasiwaktuoperasi' => $parameter['estimasiwaktuoperasi']
                    ]
                );

            foreach ($dataOrder as $data) {
                // return $this->respond($data['idProduk']);
                $dataPelayanan = new PelayananPasien();
                $dataPelayanan->norec = $dataPelayanan->generateNewId();
                $dataPelayanan->kdprofile = $this->kdProfile;
                $dataPelayanan->statusenabled = true;
                $dataPelayanan->noregistrasifk = $dataAPDnorec;
                $dataPelayanan->aturanpakai = '-';
                $dataPelayanan->hargadiscount = 0;
                $dataPelayanan->hargajual = $data['hargaLayanan'];
                $dataPelayanan->hargasatuan = $data['hargaLayanan'];
                $dataPelayanan->jumlah = $data['jumlah'];
                $dataPelayanan->kdkelompoktransaksi =  1;
                $dataPelayanan->piutangpenjamin = 0;
                $dataPelayanan->piutangrumahsakit = 0;
                $dataPelayanan->produkfk =  $data['idProduk'];
                $dataPelayanan->stock = 1;
                $dataPelayanan->strukorderfk =  $parameter['so_norec'];
                $dataPelayanan->tglpelayanan =  date('Y-m-d H:i:s');
                $dataPelayanan->harganetto = $data['hargaLayanan'];
                $dataPelayanan->noregistrasi =  $parameter['noregistrasi'];
                $dataPelayanan->save();


                foreach ($data['pelayananpetugas'] as $items) {
                    foreach ($items['listpegawai'] as $itemsPPP) {
                        $new_PPP = new PelayananPasienPetugas();
                        $new_PPP->norec = $new_PPP->generateNewId();
                        $new_PPP->kdprofile = $this->kdProfile;
                        $new_PPP->statusenabled = true;
                        $new_PPP->nomasukfk = $dataAPDnorec;
                        $new_PPP->objectjenispetugaspefk = $items['objectjenispetugaspefk'];
                        $new_PPP->objectpegawaifk = $itemsPPP['id'];
                        $new_PPP->pelayananpasien = $dataPelayanan->norec;
                        $new_PPP->noregistrasi =  $parameter['noregistrasi'];
                        $new_PPP->save();
                    }
                }

                // $PelPasienPetugas = new PelayananPasienPetugas();
                // $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                // $PelPasienPetugas->kdprofile = $this->kdProfile;
                // $PelPasienPetugas->statusenabled = true;
                // $PelPasienPetugas->nomasukfk = $dataAPDnorec;
                // // $PelPasienPetugas->object $parameter['dokterverify']; //$request['objectpegawaiorderfk'];
                // $PelPasienPetugas->objectpegawaifk = $parameter['dokteroperasi'];
                // $PelPasienPetugas->tglpelayanan =  date('Y-m-d H:i:s');
                // $PelPasienPetugas->objectjenispetugaspefk = $this->settingFix('idDokterPemeriksa'); //$jenisPetugasPe->objectjenispetugaspefk;
                // $PelPasienPetugas->pelayananpasien = $dataPelayanan->norec;
                // $PelPasienPetugas->noregistrasi =  $parameter['noregistrasi'];
                // $PelPasienPetugas->save();

                // $JadwalBedah = new JadwalBedah();
                // $JadwalBedah->norec = $JadwalBedah->generateNewId();
                // $JadwalBedah->kdprofile = $this->kdProfile; 
                // $JadwalBedah->statusenabled = true; 
                // $JadwalBedah->pengorderfk = $parameter['objectpegawaiorderfk'];
                // $JadwalBedah->tgloperasi = $parameter['tgloperasi'];
                // $JadwalBedah->estimasiwaktu = $parameter['estimasiwaktuoperasi'];
                // $JadwalBedah->ruanganfk = $parameter['ruanganoperasi'];
                // $JadwalBedah->kamarfk = $parameter['kamaroperasi'];
                // $JadwalBedah->noregistrasifk = $dataAPDnorec;

                // return $JadwalBedah;
                // $JadwalBedah->save();

                $PPnorec = $dataPelayanan->norec;
                foreach ($data['komponenharga'] as $itemKomponen) {
                    $PelPasienDetail = new PelayananPasienDetail();
                    $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                    $PelPasienDetail->kdprofile = $this->kdProfile;
                    $PelPasienDetail->statusenabled = true;
                    $PelPasienDetail->noregistrasifk = $dataAPDnorec;
                    $PelPasienDetail->aturanpakai = '-';
                    $PelPasienDetail->hargadiscount = 0;
                    $PelPasienDetail->hargajual = $itemKomponen['hargasatuan'];
                    $PelPasienDetail->hargasatuan = $itemKomponen['hargasatuan'];
                    // if (isset($item['hargadijamin'])) {
                    //     $PelPasienDetail->hargadijamin =  $item['hargadijamin'];
                    // }
                    $PelPasienDetail->jumlah = 1;
                    $PelPasienDetail->keteranganlain = '-';
                    $PelPasienDetail->keteranganpakai2 = '-';
                    $PelPasienDetail->komponenhargafk = $itemKomponen['objectkomponenhargafk'];
                    $PelPasienDetail->pelayananpasien = $PPnorec;
                    $PelPasienDetail->piutangpenjamin = 0;
                    $PelPasienDetail->piutangrumahsakit = 0;
                    $PelPasienDetail->produkfk =  $itemKomponen['objectprodukfk'];
                    $PelPasienDetail->stock = 1;
                    $PelPasienDetail->strukorderfk =  $parameter['so_norec'];
                    $PelPasienDetail->tglpelayanan = $dataAPDtglPel;
                    $PelPasienDetail->harganetto = $itemKomponen['hargasatuan'];
                    $PelPasienDetail->noregistrasi =  $parameter['noregistrasi'];
                    $PelPasienDetail->save();

                    $PPDnorec = $PelPasienDetail->norec;
                    $transStatus = 'true';
                }
            }

            DB::commit();
            $result = [
                'message' => 'Data Berhasil disimpan',
                'pelPasien' => $dataPelayanan,
                // 'petugaspelayanan' => $PelPasienPetugas,
                'detailPelayanan' => $PelPasienDetail,
                'kode' => 201
            ];
        } catch (InvalidOrderException  $e) {
            DB::rollBack();

            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'kode' => 400
            ];
        }
        return $this->respond($result, $result['kode'], $result['message']);
    }



    public function getBedahVerif(Request $request)
    {

        $datas = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftjoin('pegawai_m as pe', 'pe.id', 'so.objectpegawaiorderfk')
            ->select(
                'pp.tglpelayanan',
                'pe.namalengkap',
                'pp.hargasatuan',
                'so.tglpelayananawal as tgloperasi',
                DB::raw("(pp.jumlah*pp.hargasatuan) as total"),
                'prd.namaproduk',
                'pp.jumlah'
            )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->get();

        return $this->respond($datas);
    }

    public function getpetugasVerif(Request $request)
    {
        $datas = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->leftjoin('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'ppp.objectjenispetugaspefk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ppp.objectpegawaifk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftjoin('pegawai_m as pe', 'pe.id', 'so.objectpegawaiorderfk')
            ->select(
                'pp.tglpelayanan',
                'jp.jenispetugaspe',
                'ppp.objectpegawaifk',
                'ppp.objectjenispetugaspefk',
                DB::raw('MAX(pg.namalengkap) as namalengkappetugas'), 
            )
            ->where('pp.strukorderfk', $request['norec_so'])
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.statusenabled', true)
            ->whereIn('ppp.objectjenispetugaspefk', [6, 17])
            ->groupBy(
                'pp.tglpelayanan',
                'jp.jenispetugaspe',
                'ppp.objectpegawaifk',
                'ppp.objectjenispetugaspefk',
            )
            ->get();

        $result = [];
        $processedIds = [];
        foreach ($datas as $data) {
            $key = $data->objectjenispetugaspefk;

            if (!in_array($key, $processedIds)) {
                $processedIds[] = $key;
                $result[] = $data;
            }
        }

        return $this->respond($result);
    }






    public function  getBedahDetail(Request $r)
    {
        // $now = $this->hari(date('Y-m-d'));
        $kdProfile = $this->kdProfile;
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('idDepartemenBedah')))
            // ->where('jd.hari', 'ilike', '%'.$now .'%')
            ->where('jd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit($r['limit']);
        }

        $dokter =  $dokter->get();

        $produk  = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select(
                DB::raw("sum(spd.qtyproduk) as qtyproduk,
            ru.id,
            ru.namaruangan,
            pr.namaproduk,
            ap.asalproduk,
            spd.harganetto1,
            spd.harganetto2")
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['nama']) && $r['nama'] != '') {
            $produk->where('pr.namaproduk', 'ilike',  '%' . $r['nama'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $produk->limit($r['limit']);
        }
        $produk->groupBy('ru.id', 'ru.namaruangan',  'pr.namaproduk',  'ap.asalproduk',  'spd.harganetto1', 'spd.harganetto2');
        $produk->orderBy('pr.namaproduk');
        $produk =  $produk->get();

        $res['dokter'] = $dokter;
        $res['produk'] = $produk;
        return $this->respond($res);
    }

    // Laporan Tindakan Operasi

    public function LapTindakanOperasi(Request $request)
    {
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pelayananpasien_t as pp', 'pp.strukorderfk', 'so.norec')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk','pd.norec')
            ->join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
            ->leftjoin('ruangan_m as ruAs', 'so.objectruanganfk', 'ruAs.id')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->join('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->join('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
            ->join('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->join('departemen_m as dep', 'dep.id', 'ruAs.objectdepartemenfk')
            ->join('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->join('pegawai_m as peg2', 'peg2.id', 'ppp.objectpegawaifk')
            // ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'apd.norec')
            ->select(
                'so.norec',
                'pd.noregistrasi',
                'pd.norec as pd_norec',
                'so.noorder',
                'so.statusorder',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.tglregistrasi',
                'so.tglpelayananawal as tgloperasi',
                'pas.namapasien',
                'pas.tgllahir',
                'pas.nocm',
                'pas.objectjeniskelaminfk',
                DB::raw("CAST(so.tglorder AS DATE)"),
                'pas.noidentitas',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kls.namakelas',
                'dep.namadepartemen as asldepartemen',
                'ruAs.namaruangan as asalruangan',
                'ruTu.namaruangan as ruangantujuan',
                'dep2.namadepartemen as departementujuan',
                'peg.namalengkap',
                'pas.noidentitas',
                'pp.norec',
                'pr.namaproduk',
                'peg2.namalengkap as dokterpemeriksa',
                DB::raw("(pp.jumlah*pp.hargasatuan) as total"),
                'pp.jumlah',
                'pp.hargasatuan'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->whereIn('dep2.id', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('so.statusenabled', true);

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $dataOrder = $dataOrder->where('so.statusorder', '=', $request['statusorder']);
        }
        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $dataOrder = $dataOrder->where('ruTu.id', '=',  $request['ruanganid']);
        }
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataOrder = $dataOrder->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['dari']) && $request['dari'] != '') {
            $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $dataOrder = $dataOrder->where(DB::raw("so.tglorder::date"), '<=', $request->sampai);
        }


        $dataOrder = $dataOrder->get();

        return $this->respond($dataOrder);
    }

    public function getJadwalOperasiNew(Request $request)
    {
        $data = DB::table('jadwalbedah_t as jb')
        ->select('ps.namapasien', 'jb.estimasiwaktu', 'pg.namalengkap', 'jb.tgloperasi')
        ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'jb.noregistrasifk')
        ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        ->leftJoin('pasien_m as ps', 'ps.norec', '=', 'pd.nocmfk')
        ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'jb.pengorderfk')
        ->whereDate('jb.tgloperasi', '=', date('Y-m-d')) // Membandingkan dengan tanggal hari ini
        ->orderBy('jb.tgloperasi', 'asc')               // Urut berdasarkan tanggal operasi
        ->get();

        return $this->respond($data);
    }

    public function getJadwalOperasi2(Request $request)
    {
        $data = DB::table('jadwalbedah_t as jb')
        ->select('*')
        ->where('strukorderfk', '=', $request['so_norec'])
        ->first();

        return $this->respond($data);
    }


    public function getMasterPelaksanaOperasi(Request $request)
    {
        $data = DB::table('masterpelaksanabedah_m as mpb')
        ->leftJoin('jenisoperasi_m as jo', 'jo.id', '=', 'mpb.jenisoperasifk')
        ->leftJoin('jenispetugaspelaksana_m as jpp', 'jpp.id', '=', 'mpb.jenispetugaspelaksanafk')
        ->select([
            'mpb.id as id_master',
            'jo.id as id_jenisoperasi',
            'jo.jenisoperasi',
            'jpp.id as id_jenispetugas',
            'jpp.jenispetugaspe',
            'mpb.point'
        ])
        ->get();

        return $this->respond($data);
    }

    public function saveJadwalBedah(Request $request)
    {
        DB::beginTransaction(); // Memulai transaksi database
        // Ambil data dari request
        $parameter = $request->input('data');

        // Cek apakah StrukOrder sudah ada
        $so = StrukOrder::where('norec', $parameter['so_norec'])
            ->where('kdprofile', $this->kdProfile)
            ->first();
            
        // return $so;
    
        try {
            if ($so && ($so->statusjadwalbedah === null || $so->statusjadwalbedah === '' || $so->statusjadwalbedah === false)) {
                // Jika statusjadwalbedah kosong/null/false, buat JadwalBedah baru
                $JadwalBedah = new JadwalBedah();
                $JadwalBedah->norec = $JadwalBedah->generateNewId();
                $JadwalBedah->kdprofile = $this->kdProfile;
                $JadwalBedah->statusenabled = true;
                $JadwalBedah->pengorderfk = $parameter['objectpegawaiorderfk'];
                $JadwalBedah->tgloperasi = $parameter['tgloperasi'];
                $JadwalBedah->estimasiwaktu = $parameter['lama_operasi'];
                $JadwalBedah->ruanganfk = $parameter['ruanganoperasi'];
                $JadwalBedah->kamarfk = $parameter['kamaroperasi'];
                $JadwalBedah->tglmulaioperasi = $parameter['operasimulai'];
                $JadwalBedah->tglselesaioperasi = $parameter['operasiselesai'];
                $JadwalBedah->noregistrasifk = $parameter['noregistrasifk'];
                $JadwalBedah->strukorderfk = $parameter['so_norec'];
    
                $JadwalBedah->save(); // Simpan data ke database

                    // Update statusjadwalbedah di StrukOrder
                if ($so) {
                    // return 'masuk so new';
                    $so->statusjadwalbedah = true;
                    $so->save();
                }
        
            } else {
                // Jika sudah ada JadwalBedah, lakukan update
                $JadwalBedah = JadwalBedah::where('strukorderfk', $parameter['so_norec'])->first();
    
                if ($JadwalBedah) {
                    $JadwalBedah->tgloperasi = $parameter['tgloperasi'];
                    $JadwalBedah->estimasiwaktu = $parameter['lama_operasi'];
                    $JadwalBedah->ruanganfk = $parameter['ruanganoperasi'];
                    $JadwalBedah->kamarfk = $parameter['kamaroperasi'];
                    $JadwalBedah->tglmulaioperasi = $parameter['operasimulai'];
                    $JadwalBedah->tglselesaioperasi = $parameter['operasiselesai'];
                    $JadwalBedah->save();
                }

                if ($so) {
                    // return 'masuk so lama';
                    $so->statusjadwalbedah = true;
                    $so->save();
                }
            }
    
            DB::commit(); // Commit transaksi
    
            $result = [
                'message' => 'Data Berhasil Disimpan',
                'jadwalOperasi' => $JadwalBedah,
                'kode' => 201
            ];
        } catch (\Exception $e) {
            DB::rollBack();
    
            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'kode' => 400
            ];
        }
    
        return $this->respond($result, $result['kode'], $result['message']);
    }


    public function saveMasterPelaksanaOperasi(Request $request)
    {
        DB::beginTransaction(); // Memulai transaksi database
        // Ambil data dari request
        $parameter = $request->input('data');
    
        try {
            $datamaster = new MasterPelaksanaOperasi();
            $datamaster->kdprofile = $this->kdProfile;
            $datamaster->statusenabled = true;
            $datamaster->jenisoperasifk = $parameter['jenisoperasifk'];
            $datamaster->jenispetugaspelaksanafk = $parameter['jenispelaksanafk'];
            $datamaster->point = $parameter['point'];
            $datamaster->save(); // Simpan data ke database

            DB::commit(); // Commit transaksi
    
            $result = [
                'message' => 'Data Berhasil Disimpan',
                'jadwalOperasi' => $datamaster,
                'kode' => 201
            ];
        } catch (\Exception $e) {
            DB::rollBack();
    
            $result = [
                'message' => 'Data Gagal Disimpan, Silakan Periksa Kembali Data',
                'status' => $e->getMessage(),
                'kode' => 400
            ];
        }
    
        return $this->respond($result, $result['kode'], $result['message']);
    }

    public function getMasterPelaksana(Request $request)
    {
        $query = DB::table('masterpelaksanabedah_m as mpb')
        ->leftJoin('jenisoperasi_m as jo', 'jo.id', '=', 'mpb.jenisoperasifk')
        ->leftJoin('jenispetugaspelaksana_m as jpp', 'jpp.id', '=', 'mpb.jenispetugaspelaksanafk')
        ->select([
            'mpb.id as id_master',
            'jo.id as id_jenisoperasi',
            'jo.jenisoperasi',
            'jpp.id as id_jenispetugas',
            'jpp.jenispetugaspe',
            'mpb.point'
        ]);

        // Jika request memiliki parameter 'id' dan tidak kosong, gunakan where + first()
        if ($request->has('id') && !empty($request->id)) {
            $query->where('mpb.id', $request->id);
            $data = $query->first(); // Ambil hanya satu hasil
        } else {
            $data = $query->get(); // Ambil semua data jika 'id' tidak diberikan
        }

        return $this->respond($data);
    }

    public function getMasterPelaksanaDropdown(Request $request)
    {
        $idProfile = (int)$this->kdProfile;

        $res['jenisoperasi'] = DB::table('jenisoperasi_m')
        ->select('id', 'jenisoperasi')
        ->where('statusenabled', true)
        ->get();

        $res['jenispelaksana'] = DB::table('jenispetugaspelaksana_m')
        ->select('id', 'jenispetugaspe')
        ->where('statusenabled', true)
        ->get();

        return $this->respond($res);
    }

    public function deleteJadwalDokter(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = MasterPelaksanaOperasi::where('id', $r['id'])
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
