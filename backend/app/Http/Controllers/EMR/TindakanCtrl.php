<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPetugasPelaksana;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienLimit;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TindakanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasien(Request $r)
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
                'pd.jenispelayanan',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->orderBy('apd.tglmasuk','desc')
            ->get();
        $last =$registrasi[0];
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if (isset($r['norec_apd']) && $r['norec_apd'] != '' && $r['norec_apd'] == $d->norec_apd) {
                $last  = $d;
                break;
            }
            // else {
            //     if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
            //         $tgl = $d->tglmasuk;
            //         $last  = $d;
            //     }
            // }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['as'] = '@epic';

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
            )->distinct()
            ->where('mpr.kdprofile', $this->kdProfile)
            // ->where('mpr.objectruanganfk', $r['idruangan'])
            ->where('mpr.statusenabled', true)
            ->where('prd.statusenabled', true);
        if(isset( $r['idruangan']) &&  $r['idruangan']!='' &&  $r['idruangan']!='undefined'){
            $data = $data->where('mpr.objectruanganfk', $r['idruangan']);
        }else{
            $data = $data->where('mpr.objectruanganfk',0);
        }    
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
        if (
            isset($r['idkelas']) &&
            $r['idkelas'] != "" &&
            $r['idkelas'] != "undefined"
        ) {

            $sk =  DB::table('suratkeputusan_m')
            ->where('statusenabled', true)
            ->where('objectjeniskeputusanfk', $this->settingFix('jenisSK_TARIF'))->first();
            $skID = !empty($sk) ? $sk->id : 0;

            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.objectprodukfk'
                )
                ->where('mpr.objectruanganfk', $r['idruangan'])
                ->where('hnp.objectkelasfk', $r['idkelas'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('hnp.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile',  $this->kdProfile)
                ->where('prd.statusenabled', true)
                ->get();
            $filterData = [];
            foreach($data as $d){
                foreach($data2 as $d2){
                    if($d->id == $d2->objectprodukfk){
                        $filterData[] = $d;
                        break;
                    }
                }
            }
            $data = $filterData;
        }

        $result['data'] = $data;
        // $result['filterData'] = count($filterData);
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function listJenisPetugasPE(Request $r)
    {
        $res['jenispetugaspelaksana'] = JenisPetugasPelaksana::mine()->get();
        $res['cito'] = $this->settingFix('tarifCito');

        return $this->respond($res);
    }
    public function listMapJenisPetugasPE(Request $r)
    {
        $data = DB::table('mapjenispetugasptojenispegawai_m as mpp')
            ->join('jenispegawai_m as jp', 'jp.id', '=', 'mpp.objectjenispegawaifk')
            ->join('pegawai_m as pg', 'pg.objectjenispegawaifk', '=', 'jp.id')
            ->join('jenispetugaspelaksana_m as jpp', 'jpp.id', '=', 'mpp.objectjenispetugaspefk')
            ->select(
                'pg.namalengkap',
                'pg.id'
            )
            ->where('mpp.kdprofile', $this->kdProfile)
            ->where('mpp.objectjenispetugaspefk', $r['idJenisPetugas'])
            ->where('mpp.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jpp.statusenabled', true)
            ->orderBy('pg.namalengkap', 'ASC')
            ->get();

        return $this->respond($data);
    }
    public function listTindakanKomponen(Request $request, $lokal = false)
    {   
        $idProfile = (int) $this->kdProfile;
        // $set = $this->settingFix('idPenjaminUmum');

        // if ($set == $request['idPenjamin']) {
            $request['idPenjamin'] = null;
        // }
        $sk =  DB::table('suratkeputusan_m')
            ->where('statusenabled', true)
            ->where('objectjeniskeputusanfk', $this->settingFix('jenisSK_TARIF'))->first();
        $skID = !empty($sk) ? $sk->id : 0;
        if (
            isset($request['idPenjamin']) && $request['idPenjamin'] != 'null' && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select('hnp.objectkomponenhargafk', 'kh.komponenharga', 'hnp.hargasatuan', 'mpr.objectprodukfk', 'kh.iscito', 'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectpenjaminfk', $request['idPenjamin'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();
        } else {
            $data = [];
        }


        if (count($data) == 0) {
            $data = DB::table('harganettoprodukbykelasd_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('komponenharga_m as kh', 'kh.id', '=', 'hnp.objectkomponenhargafk')
                ->select(
                    'hnp.objectkomponenhargafk',
                    'kh.komponenharga',
                    'hnp.hargasatuan',
                    'mpr.objectprodukfk',
                    'kh.iscito',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->whereNull('hnp.objectpenjaminfk')
                ->where('mpr.statusenabled', true)
                ->where('hnp.statusenabled', true)
                ->where('prd.statusenabled', true);
            $data = $data->distinct();
            $data = $data->get();
        }

        if (
            isset($request['idPenjamin']) && $request['idPenjamin'] != 'null' && $request['idPenjamin'] != ''
            && $request['idPenjamin'] != null
        ) {
            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->where('hnp.objectpenjaminfk', $request['idPenjamin'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
        } else {
            $data2 = null;
        }

        if (empty($data2)) {

            $data2 = DB::table('harganettoprodukbykelas_m as hnp')
                ->join('mapruangantoproduk_m as mpr', 'mpr.objectprodukfk', '=', 'hnp.objectprodukfk')
                ->join('produk_m as prd', 'prd.id', '=', 'mpr.objectprodukfk')
                ->join('suratkeputusan_m as sk', 'hnp.suratkeputusanfk', '=', 'sk.id')
                ->select(
                    'hnp.hargasatuan',
                    'hnp.hargadijamin',
                    DB::raw("CASE WHEN hnp.hargadiscount IS NULL THEN 0 ELSE hnp.hargadiscount END AS diskon")
                )
                ->where('mpr.objectruanganfk', $request['idRuangan'])
                ->where('hnp.objectkelasfk', $request['idKelas'])
                ->where('mpr.objectprodukfk', $request['idProduk'])
                ->where('hnp.suratkeputusanfk', $skID)
                ->where('hnp.objectjenispelayananfk', $request['idJenisPelayanan'])
                ->whereNull('hnp.objectpenjaminfk')
                ->where('hnp.statusenabled', true)
                ->where('sk.statusenabled', true)
                ->where('mpr.statusenabled', true)
                ->where('hnp.kdprofile', $idProfile)
                ->where('prd.statusenabled', true)
                ->distinct()
                ->first();
            $istarifpenjamin = false;
        }

        $result = array(
            'komponen' => $data,
            'harga' => $data2,
            'istarifpenjamin' => $istarifpenjamin,
            'as' => '@epic',
        );
        if($lokal){
            return $result;
        }

        return $this->respond($result);
    }
    public function saveTindakan(Request $r)
    {
        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            $kpID = $this->settingFix('idKelompokTransaksiPelayanan');
            $tuslah = (float) $this->settingFix('persenUpTuslah');
            $nilaiCito  = (float) $this->settingFix('tarifCito');
            $komponenHargaJasaDokter  = $this->settingFix('komponenHargaJasaDokter');

            $totalJasa = 0;
            $totJasa = 0;
            $penjumlahanJasa = 0;
            $penjumlahanJasaTuslah = 0;
            foreach ($r['pelayananpasien'] as $item) {
                $totalJasa = 0;
                $totJasa = 0;
                $penjumlahanJasa = 0;
                $penjumlahanJasaTuslah = 0;
                if(isset($item['limit']) && $item['limit'] == true) {
                    $new = new PelayananPasienLimit();
                } else {
                    $new = new PelayananPasien();
                }
                $new->norec = $new->generateNewId();
                $new->kdprofile = $idProfile;
                $new->statusenabled = true;
                $new->noregistrasifk =  $item['norec_apd'];
                $new->tglregistrasi = $item['tglregistrasi'];
                $new->hargadiscount =  $item['diskon'];
                $new->hargajual =  $item['hargasatuan'];
                $new->hargasatuan =  $item['hargasatuan'];
                $new->jumlah =  $item['jumlah'];
                $new->kelasfk =  $item['kelasfk'];
                $new->kdkelompoktransaksi = $kpID;
                $new->keteranganlain = 'Tindakan';
                $new->piutangpenjamin =  0;
                $new->piutangrumahsakit = 0;
                $new->produkfk =  $item['produkfk'];
                $new->stock =  1;
                $new->tglpelayanan =  $item['tglpelayanan'];
                $new->harganetto =  $item['hargasatuan'];
                $new->iscito =  $item['iscito'];
                $new->isparamedis =  $item['isparamedis'];
                $new->jenispelayananfk = isset($item['jenispelayananfk']) ? $item['jenispelayananfk'] : null;
                $new->istarifdetault = isset($item['istarifdetault']) ? $item['istarifdetault'] : null;
                $new->hargadijamin = isset($item['hargadijamin']) ? $item['hargadijamin'] : null;
                $new->noregistrasi = $r['noregistrasi'];
                $new->jasa = 0;
                $new->petugas = $this->getNamaPegawai();
                $new->save();


                $this->LOGGING(
                    'Tambah Tindakan',
                    $new->norec,
                    'pelayananpasien_t',
                    'Tambah Tindakan ' . $item['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );


                foreach ($item['pelayananpetugas'] as $items) {
                    foreach ($items['listpegawai'] as $itemsPPP) {
                        $new_PPP = new PelayananPasienPetugas();
                        $new_PPP->norec = $new_PPP->generateNewId();
                        $new_PPP->kdprofile = $idProfile;
                        $new_PPP->statusenabled = true;
                        $new_PPP->nomasukfk = $item['norec_apd'];
                        $new_PPP->objectjenispetugaspefk = $items['objectjenispetugaspefk'];
                        $new_PPP->objectpegawaifk = $itemsPPP['id'];
                        $new_PPP->pelayananpasien = $new->norec;
                        $new_PPP->noregistrasi = $r['noregistrasi'];
                        $new_PPP->save();
                    }
                }

                foreach ($item['komponenharga'] as $itemKomponen) {
                    $new_PPD = new PelayananPasienDetail();
                    $new_PPD->norec = $new_PPD->generateNewId();
                    $new_PPD->kdprofile = $idProfile;
                    $new_PPD->statusenabled = true;
                    $new_PPD->noregistrasifk = $item['norec_apd'];
                    $new_PPD->tglregistrasi =  $item['tglregistrasi'];
                    $new_PPD->aturanpakai = null;
                    $new_PPD->hargadiscount = $item['diskon'];
                    $new_PPD->hargajual = $itemKomponen['hargasatuan'];
                    $new_PPD->hargasatuan = $itemKomponen['hargasatuan'];
                    $new_PPD->jumlah = 1;
                    $new_PPD->komponenhargafk = $itemKomponen['objectkomponenhargafk'];
                    $new_PPD->pelayananpasien =  $new->norec;
                    $new_PPD->piutangpenjamin = 0;
                    $new_PPD->piutangrumahsakit = 0;
                    $new_PPD->produkfk = $item['produkfk'];
                    $new_PPD->stock = 1;
                    $new_PPD->tglpelayanan = $item['tglpelayanan'];
                    $new_PPD->harganetto = $itemKomponen['hargasatuan'];
                    $new_PPD->hargadijamin =  isset($itemKomponen['hargadijamin']) ? $itemKomponen['hargadijamin'] : null;
                    if ($item['iscito'] == true) {
                        if ($tuslah > 0) {
                            $penjumlahanJasa = ($itemKomponen['hargasatuan'] - $item['diskon']) * $nilaiCito;
                            $penjumlahanJasaTuslah =  (((float)$itemKomponen['hargasatuan'] * (int)$tuslah) / 100);
                            $totalJasa = $totalJasa +  $penjumlahanJasa + $penjumlahanJasaTuslah;
                            $new_PPD->jasa = $penjumlahanJasa + $penjumlahanJasaTuslah;
                        } else {
                            $penjumlahanJasa = ($itemKomponen['hargasatuan'] - $item['diskon']) * $nilaiCito;
                            $new_PPD->jasa = $penjumlahanJasa;
                            $totalJasa = $totalJasa +  $penjumlahanJasa;
                        }
                    } else {
                        if ($tuslah > 0) {
                            $penjumlahanJasaTuslah = ((float)$itemKomponen['hargasatuan'] * (int)$tuslah) / 100;
                            $new_PPD->jasa = $penjumlahanJasaTuslah;
                            $totalJasa = $totalJasa +  $penjumlahanJasaTuslah;
                        } else {
                            $penjumlahanJasa = 0;
                            $new_PPD->jasa = $penjumlahanJasa;
                            $totalJasa = $totalJasa +  $penjumlahanJasa;
                        }
                    }
                    $new_PPD->noregistrasi = $r['noregistrasi'];
                    $new_PPD->save();
                    $transStatus = 'true';
                }


                if ($item['iscito'] == true) {
                    $dataaa = PelayananPasienDetail::where('pelayananpasien',  $new->norec)->get();
                    foreach ($dataaa as $itemss) {
                        $totJasa = $totJasa + $itemss->jasa;
                    }
                    PelayananPasien::where('norec',  $new->norec)
                        ->update([
                            'jasa' => $totalJasa
                        ]);
                }


                if ($tuslah > 0) {
                    PelayananPasien::where('norec',  $new->norec)
                        ->update([
                            'istuslah' => 1,
                            'jasa' => $totalJasa
                        ]);
                }


                if ($item['diskon'] != 0) {
                    PelayananPasienDetail::where('pelayananpasien',  $new->norec)
                        ->where('komponenhargafk', $komponenHargaJasaDokter)
                        ->update(
                            [
                                'hargadiscount' => $item['diskon']
                            ]
                        );
                }
            }
            AntrianPasienDiperiksa::where(
                'norec',
                $r['pelayananpasien'][0]['norec_apd']
            )
                ->update([
                    'ispelayananpasien' => true,
                    // 'status' => 'Selesai',
                ]);

            PasienDaftar::where(
                'norec',
                $r['pelayananpasien'][0]['norec_pd']
            )
                ->update([
                    'ispelayananpasien' => true
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
                    "data"  => $new,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function listPaket(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('paket_m as mpr')
            ->select('mpr.id', 'mpr.namapaket','mpr.harga' )
            ->where('mpr.kdprofile', $kdProfile)
            ->where('mpr.statusenabled',true)
            ->orderBy('mpr.namapaket', 'ASC')
            ->get();

        $data2 =[];
        foreach ($data as $item){
            $idPaket = $item->id;
            $details = DB::select(DB::raw("select
                    maps.id,prd.namaproduk, maps.objectprodukfk,prd.objectdetailjenisprodukfk
                    from mappakettoproduk_m as maps
                    join produk_m as prd on prd.id =maps.objectprodukfk
                    where maps.kdprofile = $kdProfile and maps.objectpaketfk='$idPaket'
                    and maps.statusenabled = true
                     and maps.statusenabled = true"));
            if(count($details) > 0){
                $data2 [] = array(
                    'id'=>   $item->id,
                    'namapaket'=>   $item->namapaket,
                    'jml' => count($details),
                    'hargapaket' => $item->harga == null ? 0 : (float) $item->harga,
                    'details' => $details
                ) ;
            }

        }

        return $this->respond($data2);
    }

}
