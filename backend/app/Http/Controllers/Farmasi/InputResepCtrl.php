<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalProduk;
use App\Models\Master\JenisKemasan;
use App\Models\Master\JenisRacikan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Master\RouteFarmasi;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanResep;
use App\Models\Master\Signa;
use App\Models\Standar\MapDepoToRuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienObatKronis;
use App\Models\Transaksi\PelayananPasienObatPulang;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\PelayananPasienRetur;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use App\Models\Transaksi\StrukRetur;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use Ramsey\Uuid\Uuid;

class InputResepCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getHeader(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->join('ruangan_m AS ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m AS ru1', 'ru1.id', '=', 'apd.objectruanganfk')
            ->leftjoin('departemen_m AS dp', 'dp.id', '=', 'ru1.objectdepartemenfk')
            ->join('kelas_m AS kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pegawai_m AS pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->select(
                'ps.nocm',
                'alm.alamatlengkap',
                'kp.kelompokpasien',
                'pas.nosep',
                'ps.tgllahir',
                'ru.namaruangan as ruangrawat',
                'ru.namaruangan as ruanginput',
                'apd.objectpegawaifk',
                'pg.namalengkap',
                'pd.statusbayar',
                'dp.namadepartemen',
                'pd.tglpulang',
                'pd.tglmeninggal',
                'ru.objectdepartemenfk',
                'kp.id as kpid',
                'pd.statusschedule as noreservasi',
                'ps.namapasien',
                'pd.tglregistrasi',
                'rek.namarekanan',
                'jk.jeniskelamin',
                'kl.id as klsid',
                'kl.namakelas',
                'pd.noregistrasi',
                'pd.nocmfk'
            )

            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true);

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != "" && $r['noregistrasi'] != "undefined") {
            $data = $data->where('pd.noregistrasi', $r['noregistrasi']);
        }
        if (isset($r['norec_apd']) && $r['norec_apd'] != "" && $r['norec_apd'] != "undefined") {
            $data = $data->where('apd.norec', $r['norec_apd']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != "" && $r['norec_apd'] != "undefined") {
            $data = $data->where('pd.norec', $r['norec_pd']);
        }

        $data = $data->first();
        if (!empty($data)) {
            $data->umur = $this->getAge($data->tgllahir, $data->tglregistrasi);
            $data->beratbadan = '-';
            $data->tinggibadan = '-';
        }

        $res['data'] = $data;
        return $this->respond($res);
    }


    public function ruanganToDepo(Request $request)
    {
        $mapDepoToRuangan = DB::table('ruangan_m as ru')
            ->join('mapdepofarmasitoruangan_s as map', 'ru.id', 'map.headruanganfk')
            ->select('ru.namaruangan')
            ->whereIn('ru.id', ['map.childruanganfk'])
            ->get();
    }

    public function getCombo(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        $res['penulisresep'] = Pegawai::mine()->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();
        $res['signa'] = Signa::mine()->get();

        $cekDepo = 0;
        if (isset($request['ruanganfk']) && isset($request['departemenfk']) && $request['ruanganfk'] != '' && $request['departemenfk'] != '') {
            $cekDepo = MapDepoToRuangan::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                ->where('objectruanganfk', $request['ruanganfk'])
                ->count();
        }

        if ($cekDepo > 0) {
            $res['ruangan'] = DB::table('mapdepotoruangan_t as rupo')
                ->join('ruangan_m as ru', 'ru.id', 'rupo.objectruanganfk')
                ->join('ruangan_m as depo', 'depo.id', 'rupo.objectdepofk')
                ->select('depo.namaruangan', 'depo.id')
                ->where('rupo.kdprofile', $this->kdProfile)
                ->where('ru.statusenabled', true)
                ->where('depo.statusenabled', true)
                ->where('rupo.objectruanganfk', $request['ruanganfk'])
                ->where('rupo.statusenabled', true)
                ->orderBy('depo.id')
                ->get();
        } else {
            $res['ruangan'] =  DB::table('maploginusertoruangan_s as mlu')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
                ->select('ru.id', 'ru.namaruangan')
                ->where('mlu.kdprofile', $idProfile)
                ->where('ru.statusenabled', true)
                ->where('mlu.statusenabled', true)
                ->whereIn('ru.objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
                ->where('mlu.objectloginuserfk', $this->getUserId())
                ->orderByDesc('ru.id')
                ->get();
        }

        $res['ruanganFarmasi'] = Ruangan::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();


        $res['jeniskemasan'] = JenisKemasan::mine()->get();
        $res['jenisracikan'] = JenisRacikan::mine()->get();
        $res['asalproduk'] = AsalProduk::mine()->get();
        $res['route'] = RouteFarmasi::mine()->get();
        $res['satuanresep'] = SatuanResep::mine()->get();

        // $dataKonversiProduk = DB::table('konversisatuan_t as ks')
        //     ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
        //     ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
        //     ->select(
        //         'ks.objekprodukfk',
        //         'ks.satuanstandar_asal',
        //         'ss.satuanstandar',
        //         'ks.satuanstandar_tujuan',
        //         'ss2.satuanstandar as satuanstandar2',
        //         'ks.nilaikonversi'
        //     )
        //     ->where('ks.kdprofile', $idProfile)
        //     ->where('ks.statusenabled', true)
        //     ->get();

        // $dataProduk = DB::table('produk_m as pr')
        //     ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
        //     ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
        //     ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
        //     ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
        //     ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
        //     ->where('pr.kdprofile', $idProfile)
        //     ->where('pr.statusenabled', true)
        //     ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        // if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
        //     $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        // }
        // //->where('spd.qtyproduk','>',0)
        // $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        // $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        // $dataProduk = $dataProduk->get();

        $dataProdukResult = [];
        // foreach ($dataProduk as $item) {
        //     $satuanKonversi = [];
        //     foreach ($dataKonversiProduk  as $item2) {
        //         if ($item->id == $item2->objekprodukfk) {
        //             $satuanKonversi[] = array(
        //                 'ssid' =>   $item2->satuanstandar_tujuan,
        //                 'satuanstandar' =>   $item2->satuanstandar2,
        //                 'nilaikonversi' =>   $item2->nilaikonversi,
        //             );
        //         }
        //     }

        //     $dataProdukResult[] = array(
        //         'id' =>   $item->id,
        //         'namaproduk' =>   $item->namaproduk,
        //         'ssid' =>   $item->ssid,
        //         'satuanstandar' =>   $item->satuanstandar,
        //         'konversisatuan' => $satuanKonversi,
        //     );
        // }
        $res['produk'] = $dataProdukResult;
        $res['tarifadminresep'] = $this->settingFix('tarifadminresep');

        return $this->respond($res);
    }
    public function getProduk(Request $r)
    {
        $idProfile = (int)$this->kdProfile;
        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $idProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', '', 'ss.id as ssid', 'ss.satuanstandar', 'pr.nama')
            ->where('pr.kdprofile', $idProfile)
            ->where('pr.statusenabled', true)
            ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ilike', '%' . $r['namaproduk'] . '%');
        }
        //->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->get();

        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }

            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' =>   $item->namaproduk,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
            );
        }
        $res['produk'] = $dataProdukResult;
        return $this->respond($res);
    }
    public function getProdukDetail(Request $request, $lokal = false)
    {

        /*
        | MetodeAmbilHargaNetto	0	KOSONG .: pengambilan hn1 atau hn2

        | MetodeHargaNetto	2	AVG
        | MetodeHargaNetto	3	Harga Tertinggi
        | MetodeHargaNetto	1	Harga Netto #

        | MetodeStokHargaNetto	2	LIFO
        | MetodeStokHargaNetto	3	FEFO #
        | MetodeStokHargaNetto	4	LEFO
        | MetodeStokHargaNetto	1	FIFO
        | MetodeStokHargaNetto	5	Summary

        | SistemHargaNetto	7	Harga Terakhir #
        | SistemHargaNetto	6	LEFO
        | SistemHargaNetto	2	LIFO
        | SistemHargaNetto	3	Harga Tertinggi
        | SistemHargaNetto	4	AVG
        | SistemHargaNetto	5	FEFO
        | SistemHargaNetto	1	FIFO

        | KETERANGAN : (#) setting yg dipakai
        */
        $idProfile = $this->kdProfile;
        $kdJenisTransOA = $this->settingFix('jenisTransaksiOA');
        $jenisTransaksi = DB::table('jenistransaksi_m as jt')
            ->where('jt.id', $kdJenisTransOA)
            ->where('jt.statusenabled', true)
            ->first();

        if (empty($jenisTransaksi)) {
            return $this->respond(null, 500, 'Setting jenistransaksi_m dulu');
        }
        if (isset($request['isorder']) && $request['isorder'] && $request['isorder'] != 'undefined') {
            $isorder = '';
        } else {
            $isorder = 'and spd.qtyproduk > 0';
        }
        $strMetodeAmbilHargaNetto = $jenisTransaksi->metodeambilharganetto;
        //        $strMetodeHargaNetto = $jenisTransaksi->metodeharganetto; //ketika penerimaan saja
        $strMetodeStokHargaNetto = $jenisTransaksi->metodestokharganetto;
        $strSistemHargaNetto = $jenisTransaksi->sistemharganetto;

        if (empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto) || empty($strMetodeAmbilHargaNetto)) {
            return $this->respond(null, 500, 'Setting Data Fixed Belum ada');
        }
        $defaultKP = 0;
        $defaultKP = 0;
        // if (isset($request['kpid']) && $request['kpid'] != "" && $request['kpid'] != "undefined") {
        //     if ($request['kpid'] == 1 || $request['kpid'] == 2) {
        //         $defaultKP = $request['kpid'];
        //     } else {
        //         $defaultKP = 1;
        //     }
        // }

        $persenHargaJualProduk = DB::table('persenhargajualproduk_m as phjp')
            ->JOIN('range_m as rg', 'rg.id', '=', 'phjp.objectrangefk')
            ->select('rg.rangemin', 'rg.rangemax', 'phjp.persenuphargasatuan', 'phjp.objectrangefk', 'phjp.objectkelompokpasienfk')
            ->where('phjp.objectjenistransaksifk', $this->settingFix('jenisTransaksiOA'))
            ->where('phjp.statusenabled', true)
            ->where('phjp.objectkelompokpasienfk', $defaultKP)
            ->get();

        if (count($persenHargaJualProduk) == 0) {
            return $this->respond(array(
                'Error' => 'Setting persenhargajualproduk_m dulu',
                'message' => 'as@epic',
            ));
        }


        $strHN = '';
        $strMSHT = '';
        $SistemHargaNetto = '';
        $MetodeAmbilHargaNetto = '';
        $MetodeStokHargaNetto = '';
        $results = [];
        // ### FIFO ### //
        if ($strSistemHargaNetto == 1) {
            $SistemHargaNetto = 'FIFO';

            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }

            // edit by dnz 20/02/2025
            $result = DB::select(
                DB::raw("select sk.norec,spd.objectprodukfk, $strMSHT as tgl,spd.objectasalprodukfk,$strHN as harganetto ,
                      spd.hargadiscount,sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk,spd.tglkadaluarsa,
                      spd.norec as norec_spd,sk.nofaktur,sk.nostruk
                from stokprodukdetail_t as spd
                left JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId and spd.objectruanganfk =:ruanganid
                group by sk.norec,spd.objectprodukfk, $strMSHT,spd.objectasalprodukfk,
                        $strHN,spd.hargadiscount, spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk, spd.norec,sk.nofaktur,sk.nostruk
                order By $strMSHT"),
                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );
            $results = [];
            $persenUpHargaSatuan = 0;
            $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
            $pesenUpDepo = [];
            foreach ($pesenUpDepos as $item) {
                // Pisahkan string menjadi id dan value menggunakan explode
                list($id, $value) = explode('|', $item);
                if ($request['ruanganfk'] == $id) {
                    $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                }
            }
            foreach ($result as $item) {
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                        }
                    }
                }
                $results[] = array(
                    'norec' => $item->norec,
                    'objectprodukfk' => $item->objectprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    'hargajual' => (float)$item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100),
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'objectruanganfk' => $item->objectruanganfk,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,

                );
            }
        }

        // ### END-FIFO ### //

        // ### Harga Tertinggi ### //
        if ($strSistemHargaNetto == 3) {
            $SistemHargaNetto = 'Harga Tertinggi';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = '';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }
            $maxHarga = DB::select(
                DB::raw("select $strHN as harga
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId and spd.objectruanganfk =:ruanganid"),
                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );
            $hargaTertinggi = 0;
            foreach ($maxHarga as $item) {
                if ($hargaTertinggi < (float)$item->harga) {
                    $hargaTertinggi = (float)$item->harga;
                }
            }

            // edit by dnz 20/02/2025
            $result = DB::select(
                DB::raw("select sk.norec,spd.objectprodukfk, $strMSHT as tgl,spd.objectasalprodukfk,$hargaTertinggi as harganetto ,
                        $hargaTertinggi  as hargajual,spd.hargadiscount,sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk,
                        spd.tglkadaluarsa, spd.norec as norec_spd
                from stokprodukdetail_t as spd
                left JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId and spd.objectruanganfk =:ruanganid
                group by sk.norec,spd.objectprodukfk, $strMSHT,spd.objectasalprodukfk,
                        spd.hargadiscount,
                spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk, spd.norec
                order By $strMSHT"),

                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );
            $results = [];
            $persenUpHargaSatuan = 0;
            $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
            $pesenUpDepo = [];
            foreach ($pesenUpDepos as $item) {
                // Pisahkan string menjadi id dan value menggunakan explode
                list($id, $value) = explode('|', $item);
                if ($request['ruanganfk'] == $id) {
                    $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                }
            }
            foreach ($result as $item) {
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                        }
                    }
                }
                $results[] = array(
                    'norec' => $item->norec,
                    'objectprodukfk' => $item->objectprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => $item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    'hargajual' => (float)$item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100),
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'objectruanganfk' => $item->objectruanganfk,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,
                );
            }
        }
        // ### END-Harga Tertinggi ### //

        // ### Harga Terakhir ### //
        if ($strSistemHargaNetto == 7) {
            $SistemHargaNetto = 'Harga Terakhir';
            if ($strMetodeAmbilHargaNetto == 1) { //HN1
                $strHN = 'spd.harganetto1';
                $MetodeAmbilHargaNetto = 'HN1';
            }
            if ($strMetodeAmbilHargaNetto == 2) { //HN2
                $strHN = 'spd.harganetto2';
                $MetodeAmbilHargaNetto = 'HN2';
            }

            if ($strMetodeStokHargaNetto == 1) { //FIFO
                $strMSHT = 'sk.tglstruk';
                $MetodeStokHargaNetto = 'FIFO';
            }
            if ($strMetodeStokHargaNetto == 2) { //LIFO
                $strMSHT = 'sk.tglstruk desc';
                $MetodeStokHargaNetto = 'LIFO';
            }
            if ($strMetodeStokHargaNetto == 3) { //FEFO
                $strMSHT = 'spd.tglkadaluarsa';
                $MetodeStokHargaNetto = 'FEFO';
            }
            if ($strMetodeStokHargaNetto == 4) { //LEFO
                $strMSHT = 'spd.tglkadaluarsa desc';
                $MetodeStokHargaNetto = 'LEFO';
            }
            if ($strMetodeStokHargaNetto == 5) { //Summary
                $strMSHT = '';
                $MetodeStokHargaNetto = 'Summary';
            }
            $maxHarga = DB::select(
                DB::raw("select spd.tglpelayanan, $strHN as harga
                from stokprodukdetail_t as spd
                inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId "),
                array(
                    'produkId' => $request['produkfk'],
                )
            );
            $hargaTerakhir = 0;
            $tgl = date('2000-01-01 00:00');
            foreach ($maxHarga as $item) {
                if ($tgl < $item->tglpelayanan) {
                    $tgl = $item->tglpelayanan;
                    $hargaTerakhir = (float)$item->harga;
                }
            }
            $result = [];
            // edit by dnz 20/02/2025
            $result = DB::select(
                DB::raw("select sk.norec,spd.objectprodukfk, $strMSHT as tgl,spd.objectasalprodukfk,$hargaTerakhir as harganetto ,
                        $hargaTerakhir  as hargajual,spd.hargadiscount,spd.nostrukterimafk, sk.nostruk,
                sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,ap.asalproduk,spd.tglkadaluarsa,
                spd.norec as norec_spd
                from stokprodukdetail_t as spd
                left JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                inner JOIN asalproduk_m as ap on ap.id=spd.objectasalprodukfk
                where spd.kdprofile = $idProfile and spd.objectprodukfk =:produkId and spd.objectruanganfk =:ruanganid $isorder
                group by sk.norec,spd.objectprodukfk, $strMSHT, spd.objectasalprodukfk,
                        spd.hargadiscount,sk.nostruk,
                spd.objectruanganfk,ap.asalproduk,spd.nostrukterimafk, spd.norec
                order By $strMSHT"),
                array(
                    'produkId' => $request['produkfk'],
                    'ruanganid' => $request['ruanganfk'],
                )
            );

            $results = [];
            $persenUpHargaSatuan = 0;
            $pesenUpDepos = explode(',', $this->settingFix('marginUpDepo'));
            $pesenUpDepo = [];
            foreach ($pesenUpDepos as $item) {
                // Pisahkan string menjadi id dan value menggunakan explode
                list($id, $value) = explode('|', $item);
                if ($request['ruanganfk'] == $id) {
                    $pesenUpDepo = ['id' => $id, 'value' => (float)$value];
                }
            }
            foreach ($result as $item) {
                if (isset($pesenUpDepo['id'])) {
                    $persenUpHargaSatuan = (float)$pesenUpDepo['value'];
                } else {
                    foreach ($persenHargaJualProduk as $hitem) {
                        if ((float)$hitem->rangemin < (float)$item->harganetto && (float)$hitem->rangemax > (float)$item->harganetto) {
                            $persenUpHargaSatuan = (float)$hitem->persenuphargasatuan;
                        }
                    }
                }
                $results[] = array(
                    'norec' => $item->norec,
                    'objectprodukfk' => $item->objectprodukfk,
                    'tgl' => $item->tgl,
                    'objectasalprodukfk' => $item->objectasalprodukfk,
                    'asalproduk' => $item->asalproduk,
                    'harganetto' => (float)$item->harganetto, //$item->harganetto,
                    'hargadiscount' => $item->hargadiscount,
                    'hargajual' => (float)$item->harganetto + (((float)$item->harganetto * (float)$persenUpHargaSatuan) / 100),
                    'persenhargajualproduk' => $persenUpHargaSatuan,
                    'qtyproduk' => (float)$item->qtyproduk,
                    'objectruanganfk' => $item->objectruanganfk,
                    'nostrukterimafk' => $item->nostrukterimafk,
                    'nostruk' => $item->nostruk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'persenup' => $persenUpHargaSatuan,
                    'norec_spd' => $item->norec_spd,
                );
            }
        }
        // ### END-Harga Terakhir ### //

        $jmlstok = 0;
        foreach ($result as $item) {
            $jmlstok = $jmlstok + $item->qtyproduk;
        }


        $getKekuatan = DB::table('produk_m as pr')
            ->leftjoin('rm_sediaan_m as sdn', 'sdn.id', 'pr.objectsediaanfk')
            ->select('pr.kekuatan', 'sdn.name')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.id', $request['produkfk'])
            ->first();

        // $cekKekuatanSupranatural = DB::select(
        //     DB::raw("

        //     select pr.kekuatan,sdn.name as sediaan from produk_m as pr
        //     inner join rm_sediaan_m as sdn on sdn.id=pr.objectsediaanfk
        //     where pr.kdprofile = $idProfile and pr.id=:produkfk;

        //     "),
        //     array(
        //         'produkfk' =>  $request['produkfk'],
        //     )
        // );
        $kekuatan = 0;
        $sediaan = '';
        if ($getKekuatan) {
            $kekuatan = $getKekuatan->kekuatan;
            $sediaan = $getKekuatan->name;
        }
        // $kekuatan = 0;
        // $sediaan = 0;
        // if()
        // if (count($cekKekuatanSupranatural) > 0) {
        //     $kekuatan = (float)$cekKekuatanSupranatural[0]->kekuatan;
        //     $sediaan = $cekKekuatanSupranatural[0]->sediaan;
        //     if ($kekuatan == null) {
        //         $kekuatan = 0;
        //     }
        // }


        $result = array(
            'detail' => $results,
            'jmlstok' => $jmlstok,
            'kekuatan' => $kekuatan,
            'sediaan' => $sediaan,
            'sistemharganetto' => $SistemHargaNetto,
            'metodeambilharganetto' => $MetodeAmbilHargaNetto,
            'metodestokharganetto' => $MetodeStokHargaNetto,
            'message' => 'as@epic',
        );
        if ($lokal) {
            return $result;
        }
        return $this->respond($result);
    }

    public function simpanResep(Request $request)
    {
        DB::beginTransaction();
        try {
            //region @SIMPAN PELAYANAN OBAT IEU
            $idProfile = (int) $this->kdProfile;
            $racikanORnonracikan = 'N';
            $SET['depoRajal'] = explode(',', $this->settingFix('kdRuanganDepoRajal'));
            $SET['statusVerif'] = $this->settingFix('statusVerifApotik');
            $SET['statusSelesai'] = $this->settingFix('statusSelesaiApotik');
            $SET['kelTrans'] = $this->settingFix('kelompokTransaksiPelayanan');
            $SET['komponenHargaNetto'] = $this->settingFix('komponenHargaNetto');
            $SET['komponenHargaProfit'] = $this->settingFix('komponenHargaProfit');
            $SET['jenisPetugasDokterPJ'] = $this->settingFix('jenisPetugasDokterPJ');


            $tglTrans =  date('Y-m-d H:i:s');
            $r_SR = $request['strukresep'];
            if ($r_SR['noorder'] != '' && $r_SR['noorder'] != 'EditResep') {
                $dataOrder = StrukOrder::where('norec', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
                $dataOrder->statusorder =  $SET['statusSelesai'];
                $dataOrder->save();
            }

            $namaPasien = $r_SR['nocm'] . ' ' . $r_SR['namapasien'];
            if ($r_SR['noresep'] == '-') {
                $newSR = new StrukResep();
                if (isset($request['strukresep']['isobatalkes']) && $request['strukresep']['isobatalkes'] == true) {
                    $noResep = $this->SEQUENCE_NEXVAL(new StrukResep(), 'noresep', 12, 'OA/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                } else {
                    $noResep = $this->SEQUENCE_NEXVAL(new StrukResep, 'noresep', 12, 'O/' . $this->getDateTime()->format('ym') . '/', $idProfile);
                }
                if ($noResep == '') {
                    $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $norecSR = $newSR->generateNewId();
                /** Obat ALKES */
                $newSR->norec = $norecSR;
            } else {
                $newSR = StrukResep::where('norec', $r_SR['norecResep'])->where('kdprofile', $idProfile)->first();
                $noResep = $newSR->noresep;
                $resepOld = $newSR;
            }
            $newSR->kdprofile = $idProfile;
            $newSR->statusenabled = 1;
            $newSR->noresep = $noResep;
            $newSR->pasienfk = $r_SR['pasienfk'];
            $newSR->penulisresepfk = $r_SR['penulisresepfk'];
            $newSR->ruanganfk = $r_SR['ruanganfk'];
            $newSR->status = $SET['statusVerif'];
            $newSR->tglresep =  $r_SR['tglresep'];
            $newSR->noregistrasi =  $r_SR['noregistrasi'];
            $newSR->petugas =  $this->getNamaPegawai();
            $newSR->isreseppulang = isset($r_SR['isreseppulang']) ? $r_SR['isreseppulang'] : null;
            if (isset($r_SR['cito'])) {
                $newSR->cito =  $r_SR['cito'];
            }
            $newSR->save();
            $norec_SR = $newSR->norec;
            $dokterPenulis =  $newSR->penulisresepfk;

            if ($r_SR['noorder'] != '' && $r_SR['noorder'] != 'EditResep') {
                $DataOrder = StrukOrder::where('norec', $r_SR['noorder'])->where('kdprofile', $idProfile)->first();
                $norecOrder = $DataOrder->norec;
                StrukResep::where('norec', $norec_SR)->update(['orderfk' => $norecOrder]);
            }

            // $TambahStok = 0;
            if ($r_SR['noresep'] != '-') {

                KartuStok::where('keterangan',  'Pelayanan Obat Alkes No. '  . $noResep . ' ' . $namaPasien)
                    ->where('kdprofile', $idProfile)
                    ->update(['flagfk' => null]);

                $tglUbah = date('Y-m-d H:i:s', strtotime('-5 seconds', strtotime($tglTrans)));

                //##PENAMBAHAN KEMBALI STOKPRODUKDETAIL
                $dataKembaliStok = collect(DB::select("
                            select pp.norec,pp.stokprodukdetailfk,pr.namaproduk,pp.strukterimafk as nostrukterimafk,pp.jumlah,pp.nilaikonversi,sr.ruanganfk,pp.produkfk
                            from pelayananpasien_t as pp
                            INNER JOIN produk_m as pr on pr.id = pp.produkfk
                            INNER JOIN strukresep_t sr on sr.norec=pp.strukresepfk
                            where pp.kdprofile = $idProfile
                            and sr.kdprofile = $idProfile
                            and sr.norec='$norec_SR'
                    "));


                if ($r_SR['ruanganfk'] == $resepOld->ruanganfk) {

                    foreach ($dataKembaliStok as $item5) {
                        $saldoAwal = 0;
                        $saldoAkhir = 0;
                        $TambahStok = (float)$item5->jumlah;
                        $dataSaldoAwal = collect(DB::select("
                                select sum(qtyproduk) as qty from stokprodukdetail_t
                                where kdprofile = $idProfile and objectruanganfk='$resepOld->ruanganfk' and objectprodukfk='$item5->produkfk'"))
                            ->first();
                        $saldoAwal = (float)$dataSaldoAwal->qty;
                        $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $idProfile)
                            ->where('norec', $item5->stokprodukdetailfk)
                            ->sharedLock()
                            ->increment('qtyproduk', (float)$TambahStok);

                        $this->kartu_STOK(array(
                            "saldoawal" => $saldoAwal,
                            "qtyin" => (float)$TambahStok,
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAkhir,
                            "keterangan" => 'Ubah Pelayanan Obat Alkes No. '  . $noResep . '. pada produk ' .  $item5->namaproduk . '. Atas pasien ' . $namaPasien,
                            "produkfk" => $item5->produkfk,
                            "ruanganfk" => $r_SR['ruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $item5->nostrukterimafk,
                            "norectransaksi" => $item5->norec,
                            "tabletransaksi" => 'pelayananpasien_t',
                            "stokprodukdetailfk" => $item5->stokprodukdetailfk,
                            "flagfk" => null,
                        ));
                    }
                } else {
                    foreach ($dataKembaliStok as $item5) {
                        $TambahStok = (float)$item5->jumlah;
                        $saldoAwal = 0;
                        $saldoAkhir = 0;

                        $dataSaldoAwal = collect(DB::select("
                            select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $idProfile and objectruanganfk='$resepOld->ruanganfk' and objectprodukfk='$item5->produkfk'"))
                            ->first();


                        $saldoAwal = (float)$dataSaldoAwal->qty;
                        $saldoAkhir = (float)$dataSaldoAwal->qty + $TambahStok;

                        DB::table('stokprodukdetail_t')
                            ->where('kdprofile', $idProfile)
                            ->where('norec', $item5->stokprodukdetailfk)
                            ->sharedLock()
                            ->increment('qtyproduk', (float)$TambahStok);

                        $this->kartu_STOK(array(
                            "saldoawal" => $saldoAwal,
                            "qtyin" => (float)$TambahStok,
                            "qtyout" => 0,
                            "saldoakhir" => $saldoAkhir,
                            "keterangan" => 'Ubah Resep No. '  . $noResep . '. pada produk ' .  $item5->namaproduk . '. Atas pasien ' . $namaPasien,
                            "produkfk" => $item5->produkfk,
                            "ruanganfk" => $r_SR['ruanganfk'],
                            "tglinput" => $tglUbah,
                            "tglkejadian" => $tglUbah,
                            "nostrukterimafk" => $item5->nostrukterimafk,
                            "norectransaksi" => $item5->norec,
                            "tabletransaksi" => 'pelayananpasien_t',
                            "stokprodukdetailfk" => $item5->stokprodukdetailfk,
                            "flagfk" => null,
                        ));
                    }
                }

                //END##PENAMBAHAN KEMBALI STOKPRODUKDETAIL

                //### LOGACC untuk penjurnalan blm ada
                $HapusPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->get();
                foreach ($HapusPP as $pp) {
                    $HapusPPD = PelayananPasienDetail::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
                    $HapusPPP = PelayananPasienPetugas::where('pelayananpasien', $pp['norec'])->where('kdprofile', $idProfile)->delete();
                }
                $HpsPP = PelayananPasien::where('strukresepfk', $norec_SR)->where('kdprofile', $idProfile)->delete();
            }

            //## PelayananPasien
            $r_PP = $request['pelayananpasien'];

            foreach ($r_PP as $r_PPL) {

                $qtyJumlah = (float)$r_PPL['jumlah'] * (float)$r_PPL['nilaikonversi'];
                $newPP = new PelayananPasien();
                $norecPP = $newPP->generateNewId();
                $newPP->norec = $norecPP;
                $newPP->kdprofile = $idProfile;
                $newPP->statusenabled = true;
                $newPP->noregistrasifk = $r_PPL['noregistrasifk'];
                $newPP->tglregistrasi =  $r_SR['tglregistrasi'];
                $newPP->aturanpakai = $r_PPL['aturanpakai'];

                $newPP->generik = $r_PPL['generik'];
                $newPP->hargadiscount = $r_PPL['hargadiscount'];
                $newPP->persendiscount = isset($r_PPL['persendiscount']) ? $r_PPL['persendiscount'] : 0;
                $newPP->hargajual = $r_PPL['hargajual'];
                $newPP->hargasatuan = $r_PPL['hargasatuan'];
                $newPP->jenisracikanfk = $r_PPL['jenisobatfk'];
                $newPP->jenisobatfk = $r_PPL['jenisobatfk'];
                $newPP->jumlah = $qtyJumlah;
                $newPP->kelasfk = $r_PPL['kelasfk'];
                $newPP->kdkelompoktransaksi = $SET['kelTrans'];
                $newPP->produkfk = $r_PPL['produkfk'];
                if (isset($r_PPL['routefk'])) {
                    $newPP->routefk = $r_PPL['routefk'];
                }
                $newPP->stock = $r_PPL['stock'];
                $newPP->tglpelayanan = $r_SR['tglresep'];
                $newPP->harganetto = $r_PPL['harganetto'];
                $newPP->jeniskemasanfk = $r_PPL['jeniskemasanfk'];
                $newPP->rke = $r_PPL['rke'];
                $newPP->strukresepfk = $norec_SR;
                $newPP->satuanviewfk = $r_PPL['satuanviewfk'];
                $newPP->nilaikonversi = $r_PPL['nilaikonversi'];
                $newPP->strukterimafk = $r_PPL['nostrukterimafk'];
                $newPP->dosis = $r_PPL['dosis'];
                $newPP->jasa = $r_PPL['jasa'];
                $newPP->qtydetailresep = $r_PPL['jumlahobat'];
                $newPP->isobat = true;
                $newPP->ispagi = isset($r_PPL['ispagi']) ?  $r_PPL['ispagi'] : null;
                $newPP->issiang = isset($r_PPL['issiang']) ? $r_PPL['issiang'] : null;
                $newPP->ismalam = isset($r_PPL['ismalam']) ? $r_PPL['ismalam'] : null;
                $newPP->issore = isset($r_PPL['issore ']) ? $r_PPL['issore'] : null;
                $newPP->keteranganpakai = $r_PPL['keterangan'];
                if (isset($r_PPL['iskronis'])) {
                    $newPP->iskronis = $r_PPL['iskronis'];
                }
                if (isset($r_PPL['ispulang'])) {
                    $newPP->isreseppulang = $r_PPL['ispulang'];
                }
                if (isset($r_PPL['satuanresepfk'])) {
                    $newPP->satuanresepfk = $r_PPL['satuanresepfk'];
                }
                if (isset($r_PPL['tglkadaluarsa']) && $r_PPL['tglkadaluarsa'] != 'Invalid date' && $r_PPL['tglkadaluarsa'] != '') {
                    $newPP->tglkadaluarsa = $r_PPL['tglkadaluarsa'];
                }
                $newPP->stokprodukdetailfk = $r_PPL['norec_spd'];
                $newPP->jenisracikanfk = $r_PPL['jenisobatfk'];
                $newPP->noregistrasi =  $r_SR['noregistrasi'];
                if (isset($r_PPL['jumlahxmakan'])) {
                    $newPP->qtyracikan = $r_PPL['jumlahxmakan'];
                }
                $newPP->save();
                if ((int)$r_PPL['jeniskemasanfk'] == 1) {
                    $racikanORnonracikan = 'R';
                }

                if(!isset($r_SR['isreseppulang']) || !$r_SR['isreseppulang']) {
                    $cekKPO = DB::table('kpo_t')
                        ->select('norec', 'qty')
                        ->where('noregistrasi', $r_SR['noregistrasi'])
                        ->where('produkveriffk', $r_PPL['produkfk'])
                        ->where('aturanpakai', $r_PPL['aturanpakai'])
                        ->whereNull('tglstop')
                        ->first();

                    if ($cekKPO !== null) { // Pastikan data ditemukan sebelum update
                        DB::table('kpo_t')
                            ->where('norec', $cekKPO->norec)
                            ->whereNull('tglstop')
                            ->update([
                                'qty' => $cekKPO->qty + $qtyJumlah
                            ]);
                    }
                }

                $dataPP[] = $newPP;
                $norec_PP = $newPP->norec;
                //### PelayananPasienDetail
                $dataKomponen = [];
                $dataKomponen[] = array(
                    'komponenfk' => $SET['komponenHargaNetto'],
                    'komponen' => 'Harga Netto',
                    'harga' => (float)$r_PPL['harganetto']
                );
                $dataKomponen[] = array(
                    'komponenfk' => $SET['komponenHargaProfit'],
                    'komponen' => 'Profit',
                    'harga' => (float)$r_PPL['hargasatuan'] - (float)$r_PPL['harganetto']
                );

                foreach ($dataKomponen as $itemKomponen) {
                    $newPPD = new PelayananPasienDetail();
                    $norecPPD = $newPPD->generateNewId();
                    $newPPD->norec = $norecPPD;
                    $newPPD->kdprofile = $idProfile;
                    $newPPD->statusenabled = true;
                    $newPPD->noregistrasifk = $r_PPL['noregistrasifk'];
                    $newPPD->tglregistrasi = $r_SR['tglregistrasi'];
                    $newPPD->aturanpakai = $r_PPL['aturanpakai'];
                    $newPPD->generik = $r_PPL['generik'];
                    $newPPD->hargadiscount = 0;
                    $newPPD->hargajual = $itemKomponen['harga'];
                    $newPPD->hargasatuan = $itemKomponen['harga'];
                    $newPPD->jenisobatfk = $r_PPL['jenisobatfk'];
                    $newPPD->jumlah = $qtyJumlah;
                    $newPPD->komponenhargafk = $itemKomponen['komponenfk'];
                    $newPPD->pelayananpasien = $norec_PP;
                    $newPPD->produkfk = $r_PPL['produkfk'];
                    $newPPD->routefk = $r_PPL['routefk'];
                    $newPPD->stock = 0;
                    $newPPD->tglpelayanan =  $r_SR['tglresep'];
                    $newPPD->harganetto = $itemKomponen['harga'];
                    $newPP->jasa = $r_PPL['jasa'];
                    $newPPD->noregistrasi =  $r_SR['noregistrasi'];
                    $newPPD->save();
                }
                //## StokProdukDetail

                $jmlPengurang = (float)$qtyJumlah;
                $dataSaldoAwal = collect(DB::select("
                     select sum(qtyproduk) as qty from stokprodukdetail_t
                     where kdprofile = $idProfile
                     and objectruanganfk='$r_PPL[ruanganfk]'
                     and objectprodukfk='$r_PPL[produkfk]'"))
                    ->first();
                // dd($dataSaldoAwal);
                $namaProduk = $r_PPL['namaproduk'];
                $saldoAwalIn = (float)$dataSaldoAwal->qty;
                $saldoAkhirIn = (float)$dataSaldoAwal->qty - $jmlPengurang;
                // dd($saldoAwalIn);
                $newSPD = StokProdukDetail::where('norec', $r_PPL['norec_spd'])
                    ->where('kdprofile', $idProfile)
                    ->where('qtyproduk', '>=', $jmlPengurang)
                    ->first();
                if (empty($newSPD)) {
                    $transMessage = "Simpan Resep Gagal, cek stok barang " . $namaProduk;
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $idProfile)
                    ->where('norec',  $r_PPL['norec_spd'])
                    ->sharedLock()
                    ->decrement('qtyproduk', (float)$jmlPengurang);

                $dataKS = [];

                if ((float)$dataSaldoAwal->qty == 0 || $jmlPengurang > (float)$dataSaldoAwal->qty) {
                    $transMessage = "Simpan Resep Gagal, Stok Produk " . $namaProduk . ", ada " . (float)$dataSaldoAwal->qty . " Data Stok Kurang Dari Qty Resep !";
                    DB::rollBack();
                    $result = array(
                        "status" => 400,
                        "result" => null
                    );
                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $this->kartu_STOK(array(
                    "saldoawal" => $saldoAwalIn,
                    "qtyin" => 0,
                    "qtyout" => (float)$qtyJumlah,
                    "saldoakhir" => (float) $saldoAkhirIn,
                    "keterangan" => 'Pelayanan Obat Alkes No. '  . $noResep . '. Pada produk ' . $r_PPL['namaproduk'] . '. Atas pasien ' . $namaPasien,
                    "produkfk" => $r_PPL['produkfk'],
                    "ruanganfk" => $r_SR['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => $tglTrans,
                    "nostrukterimafk" => $r_PPL['nostrukterimafk'],
                    "norectransaksi" => $norec_PP,
                    "tabletransaksi" => 'pelayananpasien_t',
                    "stokprodukdetailfk" => $r_PPL['norec_spd'],
                    "flagfk" => 7,
                ));

                //## Petugas
                $newP3 = new PelayananPasienPetugas();
                $norecKS = $newP3->generateNewId();
                $newP3->norec = $norecKS;
                $newP3->kdprofile = $idProfile;
                $newP3->statusenabled = true;
                $newP3->nomasukfk = $r_PPL['noregistrasifk'];
                $newP3->objectasalprodukfk = $r_PPL['asalprodukfk'];
                $newP3->objectjenispetugaspefk = $SET['jenisPetugasDokterPJ'];
                $newP3->objectprodukfk = $r_PPL['produkfk'];
                $newP3->objectruanganfk = $r_PPL['ruanganfk'];
                $newP3->pelayananpasien = $norec_PP;
                $newP3->tglpelayanan = $r_SR['tglresep'];
                $newP3->objectpegawaifk = $dokterPenulis;
                $newP3->noregistrasi =  $r_SR['noregistrasi'];
                $newP3->save();
            }


            if ($r_SR['noorder'] != 'EditResep' && in_array($r_SR['ruanganfk'], $SET['depoRajal'])) {

                $dari = date('Y-m-d 00:00', strtotime($r_SR['tglresep']));
                $sampai = date('Y-m-d 23:59', strtotime($r_SR['tglresep']));
                $countAntrian = AntrianApotik::where('kdprofile', $idProfile)
                    ->whereBetween('tglresep', [$dari, $sampai])
                    ->max('noantri');

                $noAntriApotik = (str_pad((int)$countAntrian + 1, 4, "0", STR_PAD_LEFT));

                $newAA = new AntrianApotik();
                $newAA->norec = $newAA->generateNewId();;
                $newAA->kdprofile = $idProfile;
                $newAA->statusenabled = true;
                $newAA->noantri = $noAntriApotik;
                $newAA->keterangan = $namaPasien;
                $newAA->jenis = $racikanORnonracikan;
                $newAA->tglresep = date('Y-m-d H:i:s', strtotime($r_SR['tglresep']));
                $newAA->noresep = $noResep;
                $newAA->status = $SET['statusVerif'];
                $newAA->save();


                if ($r_SR['noorder'] != 'EditResep' && $r_SR['noorder'] != '') {
                    $dataOrder = StrukOrder::where('norec', $r_SR['noorder'])
                        ->where('kdprofile', $idProfile)
                        ->update([
                            'noantri' => $noAntriApotik,
                            'jenis' => $racikanORnonracikan,
                            'keterangaantrian' => $namaPasien,
                        ]);
                }
            }

            $responseResep = DB::table('strukresep_t as sr')
                ->leftjoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
                ->select(
                    'sr.norec',
                    'aa.jenis',
                    'sr.noresep',
                    'sr.noregistrasi',
                    'sr.pasienfk',
                    'sr.penulisresepfk',
                    'sr.petugas',
                    'sr.ruanganfk',
                    'sr.ruanganfk',
                    'sr.status'
                )
                ->where('sr.norec', $norec_SR)
                ->first();

            //endregion
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Pelayanan Apotik Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "noresep"  => $responseResep,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Pelayanan Apotik Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDetailResep(Request $request)
    {
        $idProfile = $this->kdProfile;
        $dataAsalProduk = AsalProduk::mine()->get();
        $data = DB::table('strukresep_t as sr')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->LeftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'pp.satuanviewfk')
            ->LeftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
            ->select(
                'pg.id as pgid',
                'pg.namalengkap',
                'ru2.id as ruidresep',
                'ru2.namaruangan as ruanganresep',
                'sr.tglresep',
                'sr.noresep',
                'pp.hargasatuan',
                'pp.tglregistrasi',
                'pp.stock',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.rke',
                'pp.jeniskemasanfk',
                'jk.id as jkid',
                'jk.jeniskemasan',
                'pp.aturanpakai',
                'pp.routefk',
                'pp.noregistrasifk',
                'rt.name as route',
                'pp.produkfk',
                'pr.namaproduk',
                'pp.nilaikonversi',
                'pr.objectsatuanstandarfk',
                'ss.satuanstandar',
                'pp.satuanviewfk',
                'ss2.satuanstandar as ssview',
                'pp.jumlah',
                'pp.hargadiscount',
                'pp.dosis',
                'pp.jenisracikanfk',
                'pp.jasa',
                'pp.hargajual',
                'pp.hargasatuan',
                'pp.strukterimafk',
                'pp.qtydetailresep',
                'pp.ispagi',
                'pp.issiang',
                'pp.ismalam',
                'pp.issore',
                'pr.kekuatan',
                'pp.keteranganpakai',
                'pp.iskronis',
                'pp.satuanresepfk',
                'sn.satuanresep',
                'pp.tglkadaluarsa',
                'pp.norec as norecpp',
                'pp.jenisobatfk',
                'apd.objectkelasfk as kelasfk',
                'pp.persendiscount',
                'pp.stokprodukdetailfk as norec_spd',
                'pp.strukterimafk',
                'spd.objectasalprodukfk',
                'spd.qtyproduk as jmlstok'
            )
            ->where('sr.kdprofile', $idProfile)
            ->where('sr.norec', $request['norecResep'])
            ->get();
        // ->where()
        // if (isset($request['norecResep']) && $request['norecResep'] != "" && $request['norecResep'] != "undefined") {
        //     $data = $data->where('sr.norec', '=', $request['norecResep']);
        // }

        $asalprodukfk = 0;
        $asalproduk = '';
        $hargadiscount = 0;
        $total = 0;
        $totalbayar = 0;
        $aturanpakaifk = null;
        $aturanpakai = null;
        foreach ($data as $i => $item) {
            // return $item->aturanpakai;
            $asalprodukfk = $item->objectasalprodukfk;

            $total = (((float)$item->jumlah * ((float) $item->hargasatuan - (float) $item->hargadiscount)));

            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            $jmlxMakan = (((float)$item->jumlah / (float)$item->nilaikonversi) / (float)$item->dosis) * (float)$item->kekuatan;

            $item->no =  $i + 1;
            $item->generik = null;
            $item->stock = $item->jmlstok;
            $item->hargajual = $item->hargajual;
            $item->harganetto = $item->hargasatuan;
            $item->hargasatuan =  $item->hargasatuan;
            $item->hargadiscount = $hargadiscount;
            $item->nostrukterimafk =  $item->strukterimafk;
            $item->ruanganfk =  $item->ruidresep;
            // $item->aturanpakaifk = $aturanpakaifk;
            $item->aturanpakai = $item->aturanpakai;
            $item->route = $item->route;
            $item->asalprodukfk = $asalprodukfk;
            $item->asalproduk = $asalproduk;
            $item->satuanstandarfk = $item->satuanviewfk;
            $item->satuanstandar = $item->ssview;
            $item->asalproduk = $asalproduk;
            $item->satuanview = $item->ssview;
            $item->jmlstok = $item->stock;
            $item->jumlah = $item->jumlah / $item->nilaikonversi;
            $item->jumlahobat = $item->qtydetailresep;
            $item->total = $total + $item->jasa;
            $item->jmldosis = (string)$jmlxMakan . '/' . (string)$item->dosis . '/' . $item->kekuatan;
            $item->keterangan = $item->keteranganpakai;

            $dataStruk['pgid'] = $item->pgid;
            $dataStruk['namalengkap'] = $item->namalengkap;
            $dataStruk['id'] = $item->ruidresep;
            $dataStruk['namaruangan'] = $item->ruanganresep;
            $dataStruk['tglresep'] = $item->tglresep;
            $dataStruk['noresep'] = $item->noresep;
        }

        $result = array(
            'detailresep' => $dataStruk,
            'pelayananPasien' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getDetailOrder(Request $request)
    {
        $idProfile =  $this->kdProfile;
        $dataAsalProduk = AsalProduk::mine()->get();
        $dataSigna = Signa::mine()->get();
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
        $dataStruk = DB::table('strukorder_t as so')
            ->JOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->select('so.noorder as norec_order', 'so.isiter', 'so.iterJumlah', 'pg.id as pgid', 'pg.namalengkap', 'ru.id', 'ru.namaruangan', 'so.tglorder', 'so.statusorder')
            ->where('so.kdprofile', $idProfile);


        if (isset($request['isiter']) && !$request['isiter']) {
            $dataStruk = $dataStruk->where('so.statusorder', $set->statusorder);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $dataStruk = $dataStruk->where('so.noorder', '=', $request['noorder']);
        }
        if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
            $dataStruk = $dataStruk->where('so.norec', '=', $request['norec']);
        }
        $dataStruk = $dataStruk->first();
        if (empty($dataStruk)) {
            $result = array(
                'strukorder' => null,
                'orderpelayanan' => [],
            );
            return $this->respond($result);
        }


        if (isset($request['isiter']) && $request['isiter'] == 'true') {
            $strukResep = DB::table('strukresep_t as sr')
                ->join('strukorder_t as so', 'so.norec', 'sr.orderfk')
                ->select('sr.norec')
                ->where('so.norec', $request['norec'])
                ->first();
            $data = DB::table('strukresep_t as sr')
                ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'sr.ruanganfk')
                ->leftJOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
                ->leftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->join('stokprodukdetail_t as spd', function ($join) {
                    $join->on('spd.objectprodukfk', '=', 'pp.produkfk')
                         ->on('spd.objectruanganfk', '=', 'sr.ruanganfk');
                })
                ->leftJOIN('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
                ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
                ->leftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
                ->select(
                    'sr.norec as norecResep',
                    'pp.hargasatuan',
                    DB::raw('MAX(spd.qtyproduk) as qtystokcurrent'),
                    'sr.ruanganfk as objectruangantujuanfk',
                    'ru.namaruangan',
                    'pp.rke',
                    'pp.jeniskemasanfk',
                    'jk.id as jkid',
                    'jk.jeniskemasan',
                    'pp.aturanpakai',
                    'pp.routefk',
                    'rt.name as namaroute',
                    'pp.produkfk as objectprodukfk',
                    'pr.namaproduk',
                    'pp.nilaikonversi as hasilkonversi',
                    'pp.satuanviewfk as objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'pp.satuanviewfk',
                    'ss.satuanstandar as ssview',
                    'pp.qtydetailresep as qtyproduk',
                    'pp.hargadiscount',
                    'pp.dosis',
                    'pp.jenisobatfk',
                    'pp.hargasatuan',
                    'pp.hargadiscount',
                    'pr.kekuatan',
                    'sdn.name as sediaan',
                    'pp.ispagi',
                    'pp.issiang',
                    'pp.ismalam',
                    'pp.issore',
                    'pp.keteranganpakai',
                    'pp.satuanresepfk',
                    'sn.satuanresep',
                    'pp.tglkadaluarsa',
                    'pp.strukresepfk',
                    DB::raw('null as isreseppulang')
                )
                ->where('pp.statusenabled', true)
                ->where('pp.strukresepfk', $strukResep->norec)
                ->distinct()
                ->groupBy(
                    'sr.norec',
                    'pp.hargasatuan',
                    'sr.ruanganfk',
                    'ru.namaruangan',
                    'pp.rke',
                    'pp.jeniskemasanfk',
                    'jk.id',
                    'jk.jeniskemasan',
                    'pp.aturanpakai',
                    'pp.routefk',
                    'rt.name',
                    'pp.produkfk',
                    'pr.namaproduk',
                    'pp.nilaikonversi',
                    'pp.satuanviewfk',
                    'ss.satuanstandar',
                    'pp.satuanviewfk',
                    'ss.satuanstandar',
                    'pp.qtydetailresep',
                    'pp.hargadiscount',
                    'pp.dosis',
                    'pp.jenisobatfk',
                    'pp.hargasatuan',
                    'pp.hargadiscount',
                    'pr.kekuatan',
                    'sdn.name',
                    'pp.ispagi',
                    'pp.issiang',
                    'pp.ismalam',
                    'pp.issore',
                    'pp.keteranganpakai',
                    'pp.satuanresepfk',
                    'sn.satuanresep',
                    'pp.tglkadaluarsa',
                    'pp.strukresepfk'
                ) // Applying distinct to the whole query
                ->get();
        } else {
            $data = DB::table('strukorder_t as so')
                ->JOIN('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
                ->leftJOIN('jeniskemasan_m as jk', 'jk.id', '=', 'op.jeniskemasanfk')
                ->leftJOIN('routefarmasi as rt', 'rt.id', '=', 'op.routefk')
                ->JOIN('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
                ->leftJOIN('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
                ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'op.objectsatuanstandarfk')
                ->leftJOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'op.satuanviewfk')
                ->leftJOIN('satuanresep_m as sn', 'sn.id', '=', 'op.satuanresepfk')
                ->select(
                    'so.noorder as norec_order',
                    'op.hargasatuan',
                    'op.qtystokcurrent',
                    'so.objectruangantujuanfk',
                    'ru.namaruangan',
                    'op.rke',
                    'op.jeniskemasanfk',
                    'jk.id as jkid',
                    'jk.jeniskemasan',
                    'op.aturanpakai',
                    'op.routefk',
                    'rt.name as namaroute',
                    'op.objectprodukfk',
                    'pr.namaproduk',
                    'op.hasilkonversi',
                    'op.objectsatuanstandarfk',
                    'ss.satuanstandar',
                    'op.satuanviewfk',
                    'ss2.satuanstandar as ssview',
                    'op.qtyproduk',
                    'op.hargadiscount',
                    'op.hasilkonversi',
                    'op.qtystokcurrent',
                    'op.dosis',
                    'op.jenisobatfk',
                    'op.hargasatuan',
                    'op.hargadiscount',
                    'pr.kekuatan',
                    'sdn.name as sediaan',
                    'op.ispagi',
                    'op.issiang',
                    'op.ismalam',
                    'op.issore',
                    'op.keteranganpakai',
                    'op.satuanresepfk',
                    'sn.satuanresep',
                    'op.tglkadaluarsa',
                    'so.isreseppulang'
                )
                ->where('so.kdprofile', $idProfile);

            if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
                $data = $data->where('so.noorder', '=', $request['noorder']);
            }
            if (isset($request['norec']) && $request['norec'] != "" && $request['norec'] != "undefined") {
                $data = $data->where('so.norec', '=', $request['norec']);
            }
            $data = $data->get();
        }



        $dateBetween = [date("Y-m-d", strtotime("-1 month")), date("Y-m-d")];
        $getLastOrder = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select('ps.namapasien', 'pp.tglpelayanan', 'pr.namaproduk', 'ps.nocm', 'pp.produkfk')
            ->distinct()
            // ->where('pp.produkfk',$request['produkfk'])
            ->where('pd.nocmfk', $request['nocmfk'])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $dateBetween)
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $orderPelayanan = [];
        $i = 0;
        $dataStok = DB::select(
            DB::raw("select
                    sk.norec,spd.objectprodukfk, sk.tglstruk,spd.objectasalprodukfk,
                    spd.harganetto2 as hargajual,spd.harganetto2 as harganetto,
                    spd.hargadiscount,
                    sum(spd.qtyproduk) as qtyproduk,spd.objectruanganfk,spd.norec as norec_spd
                    from stokprodukdetail_t as spd
                    inner JOIN strukpelayanan_t as sk on sk.norec=spd.nostrukterimafk
                    where spd.kdprofile = :kdprofile and spd.objectruanganfk =:ruanganid
                    group by
                    sk.norec,spd.objectprodukfk,
                    sk.tglstruk,spd.objectasalprodukfk,
                    spd.harganetto2,spd.hargadiscount,  spd.objectruanganfk,
                    spd.norec
                    order By sk.tglstruk"),
            array(
                'ruanganid' => $dataStruk->id,
                'kdprofile' => $idProfile
            )
        );


        $hargajual = 0;
        $harganetto = 0;
        $nostrukterimafk = '';
        $norec_SPD = '';
        $asalprodukfk = 0;
        $asalproduk = '';
        $jmlstok = 0;
        $hargasatuan = 0;
        $hargadiscount = 0;
        $total = 0;
        $totalbayar = 0;
        $aturanpakaifk = 0;
        $rke = '0';
        $tarifadminjasa = $this->settingFix('tarifadminresep');
        foreach ($data as $item) {

            $tglLast = '';
            foreach ($getLastOrder as $last) {
                if ($last->produkfk == $item->objectprodukfk) {
                    $tglLast = $last->tglpelayanan;
                }
            }

            $i = $i + 1;
            $hargajual = 0;
            $harganetto = 0;
            $jmlstok = 0;
            $hargasatuan = 0;
            $hargadiscount = 0;
            $total = 0;
            $tarifjasa = 0;
            $qty20 = 0;
            if ($item->jkid == 2) {
                $tarifjasa = 0; // $tarifadminjasa;
            }
            if ($item->jkid == 1) {
                if ($rke != $item->rke) {
                    if ($item->qtyproduk > 20) {
                        $qty20 = number_format($item->qtyproduk / 20, 0);
                        if ($item->qtyproduk % 20 == 0) {
                            $qty20 = $qty20;
                        } else {
                            $qty20 = $qty20 + 1;
                        }

                        $tarifjasa = $tarifadminjasa * $qty20;
                    } else {
                        $tarifjasa = $tarifadminjasa;
                    }
                    $rke = $item->rke;
                }
            }
            $hargajual = round($item->hargasatuan, 0);
            $hargasatuan = round($item->hargasatuan, 0);
            $harganetto = round($item->hargadiscount, 0);

            foreach ($dataStok as $item2) {
                if ($item2->objectprodukfk == $item->objectprodukfk) {
                    if ($item2->qtyproduk > $item->qtyproduk * $item->hasilkonversi) {

                        $nostrukterimafk = $item2->norec;
                        $asalprodukfk = $item2->objectasalprodukfk;
                        $jmlstok = $item2->qtyproduk;
                        $norec_SPD = $item2->norec_spd;
                        $hargadiscount = $item2->hargadiscount;
                        $total = (((float)ceil($item->qtyproduk) * ((float)$hargasatuan - (float)$hargadiscount))) + $tarifjasa;
                        // $total = (((float)ceil($item->qtyproduk) * ((float)$hargasatuan - (float)$hargadiscount)) * $item->hasilkonversi) + $tarifjasa;
                        $totalbayar += $total;
                        break;
                    }
                }
            }
            foreach ($dataAsalProduk as $item3) {
                if ($asalprodukfk == $item3->id) {
                    $asalproduk = $item3->asalproduk;
                }
            }
            foreach ($dataSigna as $item4) {
                if ($item->aturanpakai == $item4->id) {
                    $aturanpakaifk = $item4->id;
                }
            }
            if ((float)$item->dosis == 0) {
                $item->dosis = 1;
            }
            $orderPelayanan[] = array(
                'no' => $i,
                'noregistrasifk' => '',
                'tglregistrasi' => '',
                'generik' => null,
                'hargajual' => $hargajual,
                'jenisobatfk' => $item->jenisobatfk,
                'kelasfk' => '',
                'lastorder' => $tglLast,
                'stock' => $jmlstok,
                'harganetto' => $harganetto,
                'nostrukterimafk' => $nostrukterimafk,
                'ruanganfk' => $item->objectruangantujuanfk,
                'rke' => $item->rke,
                'jeniskemasanfk' => $item->jeniskemasanfk,
                'jeniskemasan' => $item->jeniskemasan,
                'aturanpakaifk' => $aturanpakaifk,
                'aturanpakai' => $item->aturanpakai,
                'routefk' => $item->routefk,
                'route' => $item->namaroute,
                'asalprodukfk' => $asalprodukfk,
                'asalproduk' => $asalproduk,
                'produkfk' => $item->objectprodukfk,
                'namaproduk' => $item->namaproduk,
                'nilaikonversi' => $item->hasilkonversi,
                'satuanstandarfk' => $item->satuanviewfk,
                'satuanstandar' => $item->ssview,
                'satuanviewfk' => $item->satuanviewfk,
                'satuanview' => $item->ssview,
                'jmlstok' => $item->qtystokcurrent ?? 0,
                'jumlah' => ceil($item->qtyproduk),
                'jumlahobat' => $item->qtyproduk,
                'dosis' => $item->dosis,
                'kekuatan' => $item->kekuatan,
                'hargasatuan' => $hargasatuan,
                'hargadiscount' => $hargadiscount,
                'total' => $total,
                'sediaan' => $item->sediaan,
                'jmldosis' => (string)$item->qtyproduk / $item->dosis . '/' . (string)$item->dosis . '/' . (string)$item->kekuatan,

                'jasa' => $tarifjasa,
                'ispagi' => $item->ispagi,
                'issiang' =>  $item->issiang,
                'ismalam' =>  $item->ismalam,
                'issore' =>  $item->issore,
                "keterangan" => $item->keteranganpakai,
                'satuanresepfk' =>  $item->satuanresepfk,
                "satuanresep" => $item->satuanresep,
                "tglkadaluarsa" => $item->tglkadaluarsa,
                "isreseppulang" => $item->isreseppulang,
                "norec_spd" => $norec_SPD
            );
        }

        $result = array(
            'strukorder' => $dataStruk,
            'orderpelayanan' => $orderPelayanan,
            'totalbayar' => $totalbayar,
        );
        return $this->respond($result);
    }

    public function SimpanReturPelayananObat(Request $request)
    {
        $r_SR = $request['strukresep'];
        $norec_SR = $r_SR['norecResep'];
        $r_PP = $request['pelayananpasien'];

        DB::beginTransaction();
        try {
            $newSRetur = new StrukRetur();
            $norecSRetur = $newSRetur->generateNewId();
            $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
            $newSRetur->norec = $norecSRetur;
            $newSRetur->kdprofile = $this->kdProfile;
            $newSRetur->statusenabled = true;
            $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
            $newSRetur->keteranganalasan = $r_SR['alasan'];
            $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
            $newSRetur->noretur = $noRetur;
            $newSRetur->objectruanganfk = $r_SR['ruanganfk'];
            $newSRetur->objectpegawaifk = $this->getPegawaiId();
            $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
            $newSRetur->strukresepfk = $norec_SR;
            // $newSRetur->totalretur = $r_SR['totalretur'];
            // $newSRetur->jumlahitem = $r_SR['jumlahitem'];
            $newSRetur->save();
            $norec_retur = $newSRetur->norec;

            foreach ($r_PP as $r_PPLXXXX) {
                if (isset($r_PPLXXXX['jmlretur'])) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX['noregistrasifk'];
                    $newPPR->tglregistrasi = $r_PPLXXXX['tglregistrasi'];
                    $newPPR->aturanpakai = $r_PPLXXXX['aturanpakai'];
                    $newPPR->generik = $r_PPLXXXX['generik'];
                    $newPPR->hargadiscount = $r_PPLXXXX['hargadiscount'];
                    $newPPR->hargajual = $r_PPLXXXX['hargajual'];
                    $newPPR->hargasatuan = $r_PPLXXXX['hargasatuan'];
                    $newPPR->jenisobatfk = $r_PPLXXXX['jenisobatfk'];
                    $newPPR->jumlah = $r_PPLXXXX['jmlretur'];
                    $newPPR->kelasfk = $r_PPLXXXX['kelasfk'];
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX['produkfk'];
                    if (isset($r_PPL['routefk'])) {
                        $newPPR->routefk = $r_PPLXXXX['routefk'];
                    }
                    $newPPR->stock = $r_PPLXXXX['stock'];
                    $newPPR->tglpelayanan = $r_SR['tglresep'];
                    $newPPR->harganetto = $r_PPLXXXX['harganetto'];
                    $newPPR->jeniskemasanfk = $r_PPLXXXX['jeniskemasanfk'];
                    $newPPR->rke = $r_PPLXXXX['rke'];
                    $newPPR->strukresepfk = $norec_SR;
                    $newPPR->satuanviewfk = $r_PPLXXXX['satuanviewfk'];
                    $newPPR->nilaikonversi = $r_PPLXXXX['nilaikonversi'];
                    $newPPR->strukterimafk = $r_PPLXXXX['nostrukterimafk'];
                    $newPPR->dosis = $r_PPLXXXX['dosis'];
                    if ((int)$r_PPLXXXX['jumlah'] == 0) {
                        $newPPR->jasa = $r_PPLXXXX['jasa'];
                    } else {
                        $newPPR->jasa = 0;
                    }
                    $newPPR->strukreturfk = $norec_retur;
                    $newPPR->save();

                    $TambahStok = (float)$r_PPLXXXX['jmlretur'] * (float)$r_PPLXXXX['nilaikonversi'];
                    $dataSaldoAwal = collect(DB::select("
                         select sum(qtyproduk) as qty from stokprodukdetail_t
                         where kdprofile = $this->kdProfile and objectruanganfk=$r_SR[ruanganfk]
                         and objectprodukfk=$r_PPLXXXX[produkfk]
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $r_SR['ruanganfk'])
                        ->where('objectprodukfk', $r_PPLXXXX['produkfk'])
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();

                    DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('norec', $newSPD->norec)
                        ->sharedLock()
                        ->increment('qtyproduk', (float)$TambahStok);

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep Pasien No. ' . $r_PPLXXXX['noresep'],
                        "produkfk" => $r_PPLXXXX['produkfk'],
                        "ruanganfk" => $r_SR['ruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $newSPD->nostrukterimafk,
                        "norectransaksi" => $newSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => $newSPD->norec,
                        "flagfk" => 3,
                    ));

                    $jumlahNow = (int)$r_PPLXXXX['jumlah'] - (int) $r_PPLXXXX['jmlretur'];
                    PelayananPasien::where('norec', $r_PPLXXXX['norecpp'])->update(['jumlah' =>  $jumlahNow]);
                }
            }

            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => ['strukRetur' => $newSRetur]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function SimpanReturResepDibayar(Request $request)
    {
        $r_SR = $request['strukresep'];
        $norec_SR = $r_SR['norecResep'];
        $r_PP = $request['pelayananpasien'];

        DB::beginTransaction();
        try {
            $newSRetur = new StrukRetur();
            $norecSRetur = $newSRetur->generateNewId();
            $noRetur = $this->generateCode(new StrukRetur, 'noretur', 12, 'Ret/' . $this->getDateTime()->format('ym') . '/', $this->kdProfile);
            $newSRetur->norec = $norecSRetur;
            $newSRetur->kdprofile = $this->kdProfile;
            $newSRetur->statusenabled = true;
            $newSRetur->objectkelompoktransaksifk = $this->kelompokTransaksi('RETUR RUANGAN');
            $newSRetur->keteranganalasan = $r_SR['alasan'];
            $newSRetur->keteranganlainnya = 'RETUR OBAT ALKES';
            $newSRetur->noretur = $noRetur;
            $newSRetur->objectruanganfk = $r_SR['ruanganfk'];
            $newSRetur->objectpegawaifk = $this->getPegawaiId();
            $newSRetur->tglretur = $this->getDateTime()->format('Y-m-d H:i:s');
            $newSRetur->strukresepfk = $norec_SR;
            $newSRetur->jumlahitem = $r_SR['jumlahitem'];
            $newSRetur->totalretur = $r_SR['totalretur'];
            $newSRetur->save();
            $norec_retur = $newSRetur->norec;

            foreach ($r_PP as $r_PPLXXXX) {
                if (isset($r_PPLXXXX['jmlretur'])) {
                    $newPPR = new PelayananPasienRetur();
                    $norecPPR = $newPPR->generateNewId();
                    $newPPR->norec = $norecPPR;
                    $newPPR->kdprofile = $this->kdProfile;
                    $newPPR->statusenabled = true;
                    $newPPR->noregistrasifk = $r_PPLXXXX['noregistrasifk'];
                    $newPPR->tglregistrasi = $r_PPLXXXX['tglregistrasi'];
                    $newPPR->aturanpakai = $r_PPLXXXX['aturanpakai'];
                    $newPPR->generik = $r_PPLXXXX['generik'];
                    $newPPR->hargadiscount = $r_PPLXXXX['hargadiscount'];
                    $newPPR->hargajual = $r_PPLXXXX['hargajual'];
                    $newPPR->hargasatuan = $r_PPLXXXX['hargasatuan'];
                    $newPPR->jenisobatfk = $r_PPLXXXX['jenisobatfk'];
                    $newPPR->jumlah = $r_PPLXXXX['jmlretur'];
                    $newPPR->kelasfk = $r_PPLXXXX['kelasfk'];
                    $newPPR->kdkelompoktransaksi = 1;
                    $newPPR->produkfk = $r_PPLXXXX['produkfk'];
                    if (isset($r_PPL['routefk'])) {
                        $newPPR->routefk = $r_PPLXXXX['routefk'];
                    }
                    $newPPR->stock = $r_PPLXXXX['stock'];
                    $newPPR->tglpelayanan = $r_SR['tglresep'];
                    $newPPR->harganetto = $r_PPLXXXX['harganetto'];
                    $newPPR->jeniskemasanfk = $r_PPLXXXX['jeniskemasanfk'];
                    $newPPR->rke = $r_PPLXXXX['rke'];
                    $newPPR->strukresepfk = $norec_SR;
                    $newPPR->satuanviewfk = $r_PPLXXXX['satuanviewfk'];
                    $newPPR->nilaikonversi = $r_PPLXXXX['nilaikonversi'];
                    $newPPR->strukterimafk = $r_PPLXXXX['nostrukterimafk'];
                    $newPPR->dosis = $r_PPLXXXX['dosis'];
                    if ((int)$r_PPLXXXX['jumlah'] == 0) {
                        $newPPR->jasa = $r_PPLXXXX['jasa'];
                    } else {
                        $newPPR->jasa = 0;
                    }
                    $newPPR->strukreturfk = $norec_retur;
                    $newPPR->save();

                    $saldoAwal = 0;
                    $TambahStok = (float)$r_PPLXXXX['jmlretur'] * (float)$r_PPLXXXX['nilaikonversi'];
                    $dataSaldoAwal = collect(DB::select("
                         select sum(qtyproduk) as qty from stokprodukdetail_t
                         where kdprofile = $this->kdProfile and objectruanganfk=$r_SR[ruanganfk]
                         and objectprodukfk=$r_PPLXXXX[produkfk]
                    "))->first();

                    $saldoAkhir = (float)$dataSaldoAwal->qty + (float)$TambahStok;;

                    $newSPD = StokProdukDetail::where('nostrukterimafk', $r_PPLXXXX['nostrukterimafk'])
                        ->where('kdprofile', $this->kdProfile)
                        ->where('objectruanganfk', $r_SR['ruanganfk'])
                        ->where('objectprodukfk', $r_PPLXXXX['produkfk'])
                        ->orderby('tglkadaluarsa', 'desc')
                        ->first();

                    DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $this->kdProfile)
                        ->where('norec', $newSPD->norec)
                        ->sharedLock()
                        ->increment('qtyproduk', (float)$TambahStok);

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$dataSaldoAwal->qty,
                        "qtyin" => (float)$TambahStok,
                        "qtyout" => 0,
                        "saldoakhir" => $saldoAkhir,
                        "keterangan" => 'Retur Resep sudah dibayar No. ' . $r_PPLXXXX['noresep'],
                        "produkfk" => $r_PPLXXXX['produkfk'],
                        "ruanganfk" => $r_SR['ruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $newSPD->nostrukterimafk,
                        "norectransaksi" => $newSPD->norec,
                        "tabletransaksi" => 'pelayananpasien_t',
                        "stokprodukdetailfk" => $newSPD->norec,
                        "flagfk" => 3,
                    ));
                }
            }

            DB::commit();
            $result = [
                "status" => 200,
                "message" => 'Retur resep berhasil disimpan',
                "result" => ['strukRetur' => $newSRetur]
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message"  => 'Something Went Wrong',
                "result" => $e->getMessage()
            ];
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cetakKwitansi(Request $request)
    {

        $profile = $this->profile();

        $dataOrder = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftJoin('strukorder_t as so', 'so.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('strukresep_t as sr', 'sr.orderfk', '=', 'so.norec')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'ru.namaruangan',
                'pd.noregistrasi',
                'ps.nocm',
                'pd.statusbayar',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'kl.namakelas',
                DB::raw("CAST(pd.tglregistrasi as DATE)"),
                'pd.tglpulang',
                'ps.tgllahir',
                'sr.norec as norec_sr',
                'pd.nostruklastfk',
                'pd.norec as norec_pd',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $request->norec_pd)
            ->first();

        if (!empty($dataOrder)) {
            $dataOrder->umur = $this->getAge($dataOrder->tgllahir, $dataOrder->tglregistrasi);
        }
        $returAfterPrice = StrukRetur::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
            ->where('strukresepfk', $dataOrder->norec_sr)->orderby('created_at', 'desc')
            ->first();

        $datasOrder = DB::table('strukretur_t as sr')
            ->join('strukresep_t AS srep', 'srep.norec', 'sr.strukresepfk')
            ->join('pelayananpasienretur_t AS ppr', 'ppr.strukreturfk', 'sr.norec')
            ->join('produk_m AS pr', 'pr.id', 'ppr.produkfk')
            ->select(
                'srep.norec as norec_resp',
                'pr.namaproduk',
                'ppr.jumlah AS qtyRetur',
                'ppr.hargajual',
                'ppr.hargadiscount',
                'ppr.jasa',
            )
            ->where('sr.norec', $returAfterPrice->norec)
            ->get();

        $datasCountOrder = PelayananPasien::where('strukresepfk', $datasOrder[0]->norec_resp)->select('jumlah', 'hargajual', 'strukresepfk', 'hargadiscount')->get();
        $no = 0;
        $totalTagihan = 0;
        foreach ($datasCountOrder as $data) {
            $totalTagihan =  $data->hargajual * (float) $data->jumlah + $totalTagihan - (float)$data->hargadiscount;
        }

        $totalRetur = 0;
        foreach ($datasOrder as $data) {
            $totalRetur = $totalRetur + ((float)$data->hargajual * (float)$data->qtyRetur - (float)$data->hargadiscount + (float)$data->jasa);
        }

        $pageWidth = 950;

        $result = [
            'dataPasien' => $dataOrder,
            'returOrder' => $datasOrder,
            'hargaAwal' => $totalTagihan,
            'kembalianPasien' => $totalRetur,
            'hargaAkhir' => $totalTagihan - $totalRetur,
        ];

        // return $this->respond($profile);

        return view(
            'report.kasir.kwitansi-retur-obat-dibayar',
            compact('result', 'pageWidth', 'profile')
        );
    }
    public function dropdownObat(Request $r)
    {
        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'ks.satuanstandar_asal')
            ->JOIN('satuanstandar_m as ss2', 'ss2.id', '=', 'ks.satuanstandar_tujuan')
            ->select(
                'ks.objekprodukfk',
                'ks.satuanstandar_asal',
                'ss.satuanstandar',
                'ks.satuanstandar_tujuan',
                'ss2.satuanstandar as satuanstandar2',
                'ks.nilaikonversi'
            )
            ->where('ks.kdprofile', $this->kdProfile)
            ->where('ks.statusenabled', true)
            ->get();

        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJOIN('rm_generik_m as rgm', 'rgm.id', '=', 'pr.objectgenerikfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pr.id')
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.namaexternal', 'pr.statusenabled', 'rgm.generik as nama_generik')
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->whereIn('jp.id', explode(',', $this->settingFix('kdJenisProdukObat')));

        if (isset($r['limit']) && $r['limit'] != '') {
            $dataProduk = $dataProduk->limit($r['limit']);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $dataProduk = $dataProduk->where('spd.objectruanganfk', $r['ruanganfk']);
        }
        if (isset($r['namaproduk']) && $r['namaproduk'] != '') {
            $searchTerm = '%' . $r['namaproduk'] . '%';
            if(isset($r['igd']) && $r['igd']){
                $dataProduk = $dataProduk->where(function ($query) use ($searchTerm) {
                    $query->where('pr.namaproduk', 'ilike', $searchTerm);
                });
            }else{
                $dataProduk = $dataProduk->where(function ($query) use ($searchTerm) {
                    $query->where('pr.namaproduk', 'ilike', $searchTerm)
                        ->orWhere('pr.namaexternal', 'ilike', $searchTerm);
                });
            }
        }
        //->where('spd.qtyproduk','>',0)
        $dataProduk = $dataProduk->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar', 'rgm.generik');
        $dataProduk = $dataProduk->orderBy('pr.namaproduk');
        $dataProduk = $dataProduk->get();

        $dataProdukResult = [];
        foreach ($dataProduk as $item) {
            $satuanKonversi = [];
            foreach ($dataKonversiProduk  as $item2) {
                if ($item->id == $item2->objekprodukfk) {
                    $satuanKonversi[] = array(
                        // 'id' => $item->id,
                        'ssid' =>   $item2->satuanstandar_tujuan,
                        'satuanstandar' =>   $item2->satuanstandar2,
                        'nilaikonversi' =>   $item2->nilaikonversi,
                    );
                }
            }


            $dataProdukResult[] = array(
                'id' =>   $item->id,
                'namaproduk' => $item->namaproduk,
                'generik' =>   $item->nama_generik,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'statusenabled' => $item->statusenabled
            );
        }
        return $this->respond($dataProdukResult);
    }
    public function simpanResepKronis(Request $request)
    {

        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_SR = $request['strukresep'];
            $dataPPOK = PelayananPasienObatKronis::where('strukresepfk', $request['norecresep'])->first();

            foreach ($request['pelayananpasienobatkronis'] as $item) {
                if ($dataPPOK == null) {
                    $newPP = new PelayananPasienObatKronis();
                    $norecPPOK = $newPP->generateNewId();
                    $newPP->norec = $norecPPOK;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $qtyJumlah = $item['jumlahcetak'];
                    $newPP->noregistrasifk = $item['noregistrasifk'];
                    $newPP->aturanpakai = $item['aturanpakai'];
                    if (isset($item['generik'])) {
                        $newPP->generik = $item['generik'];
                    }
                    $newPP->hargadiscount = $item['hargadiscount'];
                    $newPP->hargajual = $item['hargajual'];
                    $newPP->hargasatuan = $item['hargasatuan'];
                    $newPP->jenisobatfk = $item['jenisobatfk'];
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->kelasfk = $item['kelasfk'];
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $item['produkfk'];
                    if (isset($item['routefk'])) {
                        $newPP->routefk = $item['routefk'];
                    }
                    $newPP->stock = $item['stock'];
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->harganetto = $item['harganetto'];
                    $newPP->jeniskemasanfk = $item['jeniskemasanfk'];
                    $newPP->rke = $item['rke'];
                    $newPP->strukresepfk = $request['norecresep'];
                    $newPP->satuanviewfk = $item['satuanviewfk'];
                    $newPP->nilaikonversi = $item['nilaikonversi'];
                    $newPP->strukterimafk = $item['nostrukterimafk'];
                    $newPP->dosis = $item['dosis'];
                    $newPP->jasa = $item['jasa'];
                    $newPP->isobat = 1;
                    $newPP->petugas =  $this->getNamaPegawai();
                    $newPP->noregistrasi = $request['noregistrasi'];
                    $newPP->save();
                } else {
                    $delPP = PelayananPasienObatKronis::where('strukresepfk', $request['norecresep'])->delete();
                    $newPP = new PelayananPasienObatKronis();
                    $norecPPOK = $newPP->generateNewId();
                    $newPP->norec = $norecPPOK;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $qtyJumlah = $item['jumlahcetak'];
                    $newPP->noregistrasifk = $item['noregistrasifk'];
                    $newPP->aturanpakai = $item['aturanpakai'];
                    if (isset($item['generik'])) {
                        $newPP->generik = $item['generik'];
                    }
                    $newPP->hargadiscount = $item['hargadiscount'];
                    $newPP->hargajual = $item['hargajual'];
                    $newPP->hargasatuan = $item['hargasatuan'];
                    $newPP->jenisobatfk = $item['jenisobatfk'];
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->kelasfk = $item['kelasfk'];
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $item['produkfk'];
                    if (isset($item['routefk'])) {
                        $newPP->routefk = $item['routefk'];
                    }
                    $newPP->stock = $item['stock'];
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->harganetto = $item['harganetto'];
                    $newPP->jeniskemasanfk = $item['jeniskemasanfk'];
                    $newPP->rke = $item['rke'];
                    $newPP->strukresepfk = $request['norecresep'];
                    $newPP->satuanviewfk = $item['satuanviewfk'];
                    $newPP->nilaikonversi = $item['nilaikonversi'];
                    $newPP->strukterimafk = $item['nostrukterimafk'];
                    $newPP->dosis = $item['dosis'];
                    $newPP->jasa = $item['jasa'];
                    $newPP->isobat = 1;
                    $newPP->petugas =  $this->getNamaPegawai();
                    $newPP->noregistrasi = $request['noregistrasi'];
                    $newPP->save();
                }

                $ruanganfk = $request['strukresep']['ruanganfk'];


                $jmlPengurang = (float)$qtyJumlah;
                $kurangStok = (float)0;
                $dataSaldoAwal = collect(DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $kdProfile
                            and objectruanganfk=$ruanganfk
                            and objectprodukfk=$item[produkfk]"))
                    ->first();

                $saldoAwalIn = (float)$dataSaldoAwal->qty - $jmlPengurang;


                $newSPD = StokProdukDetail::where('norec', $item['norec_spd'])
                    ->where('kdprofile', $kdProfile)
                    ->first();

                if ((float)$newSPD->qtyproduk <= (float)$jmlPengurang) {
                    $kurangStok = (float)$newSPD->qtyproduk;
                    $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                } else {
                    $kurangStok = (float)$jmlPengurang;
                    $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                }

                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('norec', $newSPD->norec)
                    ->lockForUpdate()
                    ->decrement('qtyproduk', (float)$kurangStok);


                $this->kartu_STOK(array(
                    "saldoawal" => $dataSaldoAwal->qty,
                    "qtyin" => 0,
                    "qtyout" => (float) $qtyJumlah,
                    "saldoakhir" => $saldoAwalIn,
                    "keterangan" =>
                    'Pelayanan Obat Kronis No. '  . $request['noresep']
                        . '. Pada produk ' . $item['namaproduk']
                        . '. Atas pasien ' .
                        $request['strukresep']['nocm']
                        . ' ' .
                        $request['strukresep']['namapasien'],

                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $item['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item['nostrukterimafk'],
                    "norectransaksi" => $norecPPOK,
                    "tabletransaksi" => 'pelayananpasienobatkronis_t',
                    "stokprodukdetailfk" => $newSPD->norec,
                    "flagfk" => null,
                ));
            }
            PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update([
                'iskronis' => true,
            ]);


            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Obat Kronis";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "noresep"  => $dataPPOK,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Obat Kronis Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . '' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function simpanResepPulang(Request $request)
    {

        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_SR = $request['strukresep'];
            $dataPPOP = PelayananPasienObatPulang::where('strukresepfk', $request['norecresep'])->first();

            foreach ($request['pelayananpasienobatpulang'] as $item) {
                if ($dataPPOP == null) {
                    $newPP = new PelayananPasienObatPulang();
                    $norecPPOP = $newPP->generateNewId();
                    $newPP->norec = $norecPPOP;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $qtyJumlah = $item['jumlahcetak'];
                    $newPP->noregistrasifk = $item['noregistrasifk'];
                    $newPP->aturanpakai = $item['aturanpakai'];
                    if (isset($item['generik'])) {
                        $newPP->generik = $item['generik'];
                    }
                    $newPP->hargadiscount = $item['hargadiscount'];
                    $newPP->hargajual = $item['hargajual'];
                    $newPP->hargasatuan = $item['hargasatuan'];
                    $newPP->jenisobatfk = $item['jenisobatfk'];
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->kelasfk = $item['kelasfk'];
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $item['produkfk'];
                    if (isset($item['routefk'])) {
                        $newPP->routefk = $item['routefk'];
                    }
                    $newPP->stock = $item['stock'];
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->harganetto = $item['harganetto'];
                    $newPP->jeniskemasanfk = $item['jeniskemasanfk'];
                    $newPP->rke = $item['rke'];
                    $newPP->strukresepfk = $request['norecresep'];
                    $newPP->satuanviewfk = $item['satuanviewfk'];
                    $newPP->nilaikonversi = $item['nilaikonversi'];
                    $newPP->strukterimafk = $item['nostrukterimafk'];
                    $newPP->dosis = $item['dosis'];
                    $newPP->jasa = $item['jasa'];
                    $newPP->isobat = 1;
                    $newPP->petugas =  $this->getNamaPegawai();
                    $newPP->noregistrasi = $request['noregistrasi'];
                    $newPP->save();
                } else {
                    $delPP = PelayananPasienObatPulang::where('strukresepfk', $request['norecresep'])->delete();
                    $newPP = new PelayananPasienObatPulang();
                    $norecPPOP = $newPP->generateNewId();
                    $newPP->norec = $norecPPOP;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $qtyJumlah = $item['jumlahcetak'];
                    $newPP->noregistrasifk = $item['noregistrasifk'];
                    $newPP->aturanpakai = $item['aturanpakai'];
                    if (isset($item['generik'])) {
                        $newPP->generik = $item['generik'];
                    }
                    $newPP->hargadiscount = $item['hargadiscount'];
                    $newPP->hargajual = $item['hargajual'];
                    $newPP->hargasatuan = $item['hargasatuan'];
                    $newPP->jenisobatfk = $item['jenisobatfk'];
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->kelasfk = $item['kelasfk'];
                    $newPP->kdkelompoktransaksi = 1;
                    $newPP->produkfk = $item['produkfk'];
                    if (isset($item['routefk'])) {
                        $newPP->routefk = $item['routefk'];
                    }
                    $newPP->stock = $item['stock'];
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->harganetto = $item['harganetto'];
                    $newPP->jeniskemasanfk = $item['jeniskemasanfk'];
                    $newPP->rke = $item['rke'];
                    $newPP->strukresepfk = $request['norecresep'];
                    $newPP->satuanviewfk = $item['satuanviewfk'];
                    $newPP->nilaikonversi = $item['nilaikonversi'];
                    $newPP->strukterimafk = $item['nostrukterimafk'];
                    $newPP->dosis = $item['dosis'];
                    $newPP->jasa = $item['jasa'];
                    $newPP->isobat = 1;
                    $newPP->petugas =  $this->getNamaPegawai();
                    $newPP->noregistrasi = $request['noregistrasi'];
                    $newPP->save();
                }

                $ruanganfk = $request['strukresep']['ruanganfk'];


                $jmlPengurang = (float)$qtyJumlah;
                $kurangStok = (float)0;
                $dataSaldoAwal = collect(DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                            where kdprofile = $kdProfile
                            and objectruanganfk=$ruanganfk
                            and objectprodukfk=$item[produkfk]"))
                    ->first();

                $saldoAwalIn = (float)$dataSaldoAwal->qty - $jmlPengurang;


                $newSPD = StokProdukDetail::where('norec', $item['norec_spd'])
                    ->where('kdprofile', $kdProfile)
                    ->first();

                if ((float)$newSPD->qtyproduk <= (float)$jmlPengurang) {
                    $kurangStok = (float)$newSPD->qtyproduk;
                    $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                } else {
                    $kurangStok = (float)$jmlPengurang;
                    $jmlPengurang = (float)$jmlPengurang - (float)$kurangStok;
                }

                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('norec', $newSPD->norec)
                    ->lockForUpdate()
                    ->decrement('qtyproduk', (float)$kurangStok);


                $this->kartu_STOK(array(
                    "saldoawal" => $dataSaldoAwal->qty,
                    "qtyin" => 0,
                    "qtyout" => (float) $qtyJumlah,
                    "saldoakhir" => $saldoAwalIn,
                    "keterangan" =>
                    'Pelayanan Obat Pulang No. '  . $request['noresep']
                        . '. Pada produk ' . $item['namaproduk']
                        . '. Atas pasien ' .
                        $request['strukresep']['nocm']
                        . ' ' .
                        $request['strukresep']['namapasien'],

                    "produkfk" => $item['produkfk'],
                    "ruanganfk" => $item['ruanganfk'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item['nostrukterimafk'],
                    "norectransaksi" => $norecPPOP,
                    "tabletransaksi" => 'pelayananpasienobatpulang_t',
                    "stokprodukdetailfk" => $newSPD->norec,
                    "flagfk" => null,
                ));
            }
            PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update([
                'ispulang' => true,
            ]);


            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Simpan Obat Pulang";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "noresep"  => $dataPPOP,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Obat Pulang Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . '' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function chekPeriodeObat(Request $request)
    {
        // $startDate = Carbon::now()->subDays(30);
        $dateBetween = [date("Y-m-d", strtotime("-1 month")), date("Y-m-d")];
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->select('ps.namapasien', 'pp.tglpelayanan', 'pr.namaproduk', 'ps.nocm')
            ->where('pp.produkfk', $request['produkfk'])
            ->where('pd.nocmfk', $request['nocmfk'])
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $dateBetween)
            ->orderByDesc('pp.tglpelayanan')
            ->first();

        return $this->respond($data);;
    }
    public function getComboRuang(Request $request)
    {

        $res['ruanganFarmasi'] = Ruangan::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();

        return $this->respond($res);
    }
}
