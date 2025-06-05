<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\Kelas;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class VerifikasiTagihanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function dataTagihan(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $sDokterOperasi = $this->settingFix('jenisPetugasDokterOperasi');
        $sDokterAnastesi = $this->settingFix('jenisPetugasDokterAnastesi');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'apd.norec as norec_apd',
                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    ((pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end))
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
               ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->whereNull('pp.strukfk')
            ->where('apd.noregistrasifk', $r['norec_pd']);
        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'ptu.objectjenispetugaspefk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap',  'jp.jenispetugaspe',)
            ->where('ptu.kdprofile', $kdProfile)
            ->where(function ($query) use ($sDokterPemeriksa, $sDokterOperasi, $sDokterAnastesi) {
                $query->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterOperasi)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterAnastesi);
            })
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            // ->whereIn('ptu.objectjenispetugaspefk', [6,17])
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            // foreach ($pelayananpetugas as $itemd) {
            //     if ($itemd->pelayananpasien == $item->norec) {
            //         $item->dokterpemeriksa = $itemd->namalengkap;
            //     }
            // }
            // foreach ($pelayananpetugas as $itemd) {
            //     if ($itemd->pelayananpasien == $item->norec) {
            //         $item->dokterpemeriksa = $itemd->namalengkap;
            //         $item->dokteroperasi = $itemd->namalengkap;
            //         if ($itemd->jenispetugaspe == 'Dokter Anestesi') {
            //             $item->dokteranastesi = $itemd->namalengkap;
            //         }
            //     }
            // }
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                    // $item->dokteroperasi = $itemd->namalengkap;
                    if ($itemd->jenispetugaspe == 'Dokter Operasi 1') {
                        $item->dokteroperasi = $itemd->namalengkap;
                    }
                    elseif ($itemd->jenispetugaspe == 'Dokter Anestesi') {
                        $item->dokteranastesi = $itemd->namalengkap;
                    }
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }
        $result['klaim']  = StrukPelayanan::totalKlaim($pd->noregistrasi);
        $result['verif']  = StrukPelayanan::totalVerif($pd->noregistrasi);
        $result['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($pd->noregistrasi);
        $result['deposit'] = StrukBuktiPenerimaan::deposit($pd->noregistrasi,true) - $result['pengembalian']  - $result['verif']  ;
        $result['deposit'] =  $result['deposit'] < 0? 0: $result['deposit'];
        $result['dibayar'] = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
        $result['sisa'] =   $result['total']  -  $result['dibayar'] - $result['deposit']; // -  $result['diskon'];
        $result['length'] = count($data);
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');

        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function simpanVerifikasiTagihan(Request $r)
    {

        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $pelayanan = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->whereNull('strukfk')
                ->get();
            if (count($pelayanan) == 0) {
                abort(400, 'Pelayanan yang dilakukan pasien tidak ada');
            }
            $noStruk = $this->SEQUENCETAGIHAN(new StrukPelayanan(), 10, 'S', $kdProfile);
            if ($noStruk == '') {
                abort(400, 'SEQ ERROR');
            }
            $PD = PasienDaftar::where('noregistrasi', $r['noregistrasi'])
                ->where('kdprofile', $kdProfile)
                ->first();
            // $totalBilling = 0;
            $totalKlaim = (float)$r['klaim'];
            $totalKlaim_pasien = (float)$r['klaim_pasien'];
            $totalKlaim_multi = (float)$r['klaim_multi'];
            // $pelayananH = PelayananPasien::where('noregistrasi', $r['noregistrasi'])
            // ->where('kdprofile', $kdProfile)
            // ->get();
            // foreach ($pelayananH as $pel) {
            //     $harga = ($pel->hargajual == null) ? 0 : $pel->hargajual;
            //     $diskon = ($pel->hargadiscount == null) ? 0 : $pel->hargadiscount;
            //     $totalBilling += (($harga - $diskon) * $pel->jumlah) + $pel->jasa;
            // }

            $SP = new StrukPelayanan();
            $SP->norec = $SP->generateNewId();
            $SP->kdprofile = $kdProfile;
            $SP->statusenabled = true;
            $SP->nocmfk = $PD->nocmfk;
            $SP->noregistrasifk = $PD->norec;
            $SP->noregistrasi = $PD->noregistrasi;
            $SP->objectkelaslastfk = $PD->objectkelasfk;
            $SP->objectkelompoktransaksifk = $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN');
            $SP->objectpegawaipenerimafk = $this->getPegawaiId();
            $SP->nostruk = $noStruk;
            $SP->totalhargasatuan = (float)$r['total'];
            $SP->totalharusdibayar = (float)$r['totalbayar'];
            $SP->tglstruk = $this->now();
            $SP->objectruanganfk = $PD->objectruanganlastfk;
            if (empty($r['klaim_multi'])) {
                $SP->totalprekanan = $totalKlaim;
            } else {
                $SP->totalprekanan = $r['klaim_multi'];
            }
            $SP->totalprekanan_pasien = $totalKlaim_pasien;
            $SP->totaliurbayar =  (float)$r['totaliurbayar'];
            $SP->save();
            foreach ($r['details'] as $chklist) {
                PelayananPasien::where('norec', $chklist['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->update(
                        [
                            'strukfk' => $SP->norec
                        ]
                    );
                PelayananPasienDetail::where('pelayananpasien', $chklist['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->update(
                        [
                            'strukfk' => $SP->norec
                        ]
                    );
            }

            if ($totalKlaim > 0) {
                $SPP = new StrukPelayananPenjamin();
                $SPP->norec = $SPP->generateNewId();
                $SPP->statusenabled = true;
                $SPP->kdprofile = $kdProfile;
                $SPP->jenis_piutang = "Piutang Penjamin";
                $SPP->kdkelompokpasien = $PD->objectkelompokpasienlastfk;
                $SPP->kdrekananpenjamin = $PD->objectrekananfk;
                $SPP->totalbiaya = (float)$r['totalbayar'] + $totalKlaim + (float)$r['deposit'];
                $SPP->totalsudahppenjamin = $totalKlaim;
                $SPP->totalsisaharusdibayar = $totalKlaim;
                $SPP->totalppenjamin = $totalKlaim;
                $SPP->totalharusdibayar = $totalKlaim;
                $SPP->totalsudahdibayar = 0;
                $SPP->totalsudahdibebaskan = 0;
                $SPP->totalsisapiutang = $totalKlaim;
                $SPP->totaldibayarlebih = 0;
                $SPP->nostrukfk = $SP->norec;
                $PD->nostruklastfk = $SP->norec;
                $SPP->nospp = $this->SEQUENCE(new StrukPelayananPenjamin, 'nospp', 14, 'SP-' . date('ym'), $kdProfile);
                $SPP->save();
            }
            if ($totalKlaim_pasien > 0) {
                $SPP = new StrukPelayananPenjamin();
                $SPP->norec = $SPP->generateNewId();
                $SPP->statusenabled = true;
                $SPP->kdprofile = $kdProfile;
                $SPP->jenis_piutang = "Piutang Pasien";
                $SPP->kdkelompokpasien = $PD->objectkelompokpasienlastfk;
                $SPP->kdrekananpenjamin = $PD->objectrekananfk;
                $SPP->totalbiaya = (float)$r['totalbayar'] + $totalKlaim_pasien + (float)$r['deposit'];
                $SPP->totalsudahppenjamin = $totalKlaim_pasien;
                $SPP->totalsisaharusdibayar = $totalKlaim_pasien;
                $SPP->totalppenjamin = $totalKlaim_pasien;
                $SPP->totalharusdibayar = $totalKlaim_pasien;
                $SPP->totalsudahdibayar = 0;
                $SPP->totalsudahdibebaskan = 0;
                $SPP->totalsisapiutang = $totalKlaim_pasien;
                $SPP->totaldibayarlebih = 0;
                $SPP->nostrukfk = $SP->norec;
                $PD->nostruklastfk = $SP->norec;
                $SPP->nospp = $this->SEQUENCE(new StrukPelayananPenjamin, 'nospp', 14, 'SP-' . date('ym'), $kdProfile);
                $SPP->save();
            }
            if ($totalKlaim_multi > 0) {
                foreach ($r['savemulti'] as $item){
                    $SPP = new StrukPelayananPenjamin();
                    $SPP->norec = $SPP->generateNewId();
                    $SPP->statusenabled = true;
                    $SPP->kdprofile = $kdProfile;
                    $SPP->jenis_piutang = "Piutang Multi";
                    $SPP->kdkelompokpasien = $PD->objectkelompokpasienlastfk;
                    $SPP->kdrekananpenjamin = $item['objectrekananfk'];
                    $SPP->totalbiaya = (float)$r['totalbayar'] + $item['klaim_multi'] + (float)$r['deposit'];
                    $SPP->totalsudahppenjamin = $item['klaim_multi'];
                    $SPP->totalsisaharusdibayar = $item['klaim_multi'];
                    $SPP->totalppenjamin = $item['klaim_multi'];
                    $SPP->totalharusdibayar = $item['klaim_multi'];
                    $SPP->totalsudahdibayar = 0;
                    $SPP->totalsudahdibebaskan = 0;
                    $SPP->totalsisapiutang = $item['klaim_multi'];
                    $SPP->totaldibayarlebih = 0;
                    $SPP->nostrukfk = $SP->norec;
                    $PD->nostruklastfk = $SP->norec;
                    $SPP->nospp = $this->SEQUENCE(new StrukPelayananPenjamin, 'nospp', 14, 'SP-' . date('ym'), $kdProfile);
                    $SPP->save();
                }
            }
            $PD->statusbayar = 'Verifikasi';
            if($r['totalbayar'] == 0){
                $PD->statusbayar = 'Lunas';
            }
            $PD->nostruklastfk = $SP->norec;

            $PD->save();

            $this->LOGGING(
                'Verifikasi Tagihan',
                 $SP->norec,
                'strukpelayanan_t',
                'Verifikasi Tagihan pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $SP->nostruk
            );
            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "sp"  => $SP,
                    "pd" => $PD,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage =  $e->getStatusCode() == 404 ? $e->getMessage() : "Simpan Gagal" ;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function listKelas(Request $r)
    {
        $res['kelas'] = DB::table('kelas_m')->where('kdprofile',$this->kdProfile)
        ->where('statusenabled',true)
        ->select('id','namakelas','namabpjs')
        ->orderBy('namakelas')
        ->get();
        return $this->respond($res);
    }
}
