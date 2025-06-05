<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\JenisDiet;
use App\Models\Master\JenisWaktu;
use App\Models\Master\Kamar;
use App\Models\Master\KategoryDiet;
use App\Models\Master\Kelas;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Models\Master\SatuanGizi;
use App\Models\Master\VendorGizi;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardGiziCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasienGizi(Request $r)
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
                'ps.email',
                'kp.kelompokpasien'
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
        $diagnosa = DB::table('diagnosapasien_t as dp')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jp', 'jp.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dp.noregistrasifk as norec_apd',
                'dp.ketdiagnosis',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jp.jenisdiagnosa'
            )
            ->where('dp.kdprofile', (int)$this->kdProfile)
            ->where('dp.statusenabled', true)
            ->where('ddp.statusenabled', true)
            ->where('dp.noregistrasifk', $last->norec_apd)
            ->where('jp.id', 1)
            ->first();


        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['diagnosa'] = $diagnosa;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listOrderGizi(Request $r)
    {
        $dataProduk = DB::table('produk_m as pr')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->join('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar')
            ->where('pr.statusenabled', true)
            ->whereIN('kp.id', explode(',', $this->settingFix('kdKelasNonKelasRegistrasi'), $this->kdProfile))
            ->groupBy('pr.id', 'pr.namaproduk', 'ss.id', 'ss.satuanstandar')
            ->orderBy('pr.namaproduk')
            ->get();

        $set = explode(',',$this->settingFix('idPelayananGizi'));
        $res['ruangan'] = Ruangan::mine()->where('objectdepartemenfk', 5)->get();
        $res['ruangan_ranap'] = Ruangan::mine()->where('objectdepartemenfk', 16)->orderBy('namaruangan')->get();
        $res['jeniswaktu'] = JenisWaktu::mine()->get();
        $res['jenisdiet'] = JenisDiet::mine()->get();
        $res['kategorydiet'] = KategoryDiet::mine()->get();
        $res['vendorgizi'] = VendorGizi::mine()->get();
        $res['satuangizi'] = SatuanGizi::mine()->get();
        $res['kelas'] = Kelas::mine()->get();
        $res['produk'] = $dataProduk;
        // return $set;

        return $this->respond($res);
    }

    public function getPasienInap(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->select(
                DB::raw("
                    DISTINCT on (apd.noregistrasifk)
                    ru.id,
                    ru.statusenabled,
                    ru.namaruangan,
                    pd.norec as norec_pd,
                    pd.objectruanganasalfk,
                    pd.nocmfk,
                    pd.tglpulang,
                    ru.objectdepartemenfk,
                    jk.jeniskelamin,
                    pd.objectruanganlastfk,
                    pd.objectpegawaifk,
                    pd.noregistrasi,
                    pg.namalengkap,
                    ps.namapasien,
                    ps.nobpjs,
                    ps.noidentitas,
                    apd.norec as norec_apd,
                    CAST(pd.tglregistrasi AS DATE)
                ")

            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('pd.tglpulang')
            ->whereNull('apd.tglkeluar');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['ruanganranapid']) && $r['ruanganranapid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganranapid']);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('pd.nocm', '=',  $r['nocm']);
        }

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                      ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                      ->orWhere('ps.nocm', 'ilike', $searchTerm)
                      ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                      ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }

        $data = $data->get();

        $produk  = DB::table('stokprodukdetail_t as spd')
        ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
        ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
        ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
        ->select(
            'ru.id',
            'ru.namaruangan',
            'spd.qtyproduk',
            'pr.namaproduk',
            'ap.asalproduk',
            'spd.harganetto1',
            'spd.harganetto2',

        )
        ->where('spd.kdprofile', $this->kdProfile)
        ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdRuanganGizi')))
        ->where('spd.statusenabled', true);

    if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
        $produk = $produk->where('ru.id', '=',  $r['ruanganid']);
    }

        $produk =  $produk->get();

        $res['produk'] = $produk;
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function riwayatOrderGizi(Request $r)
    {
        $data = DB::table('orderpelayanan_t as op')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', function($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
            })
            ->leftJoin('tempattidur_m as tt','tt.id','apd.nobed')
            ->leftjoin ('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=' , 'kp.id')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('strukkirim_t as sk', 'sk.norec', '=', 'op.strukkirimfk')
            ->leftjoin('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->leftjoin('jenisdiet_m as jd', 'jd.id', '=', 'op.objectjenisdietfk')
            ->leftjoin('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'op.objectkelasfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'pd.norec',
                'so.norec as norec_so',
                'op.norec as norec_op',
                'so.noorder',
                'tt.reportdisplay',
                'so.tglorder',
                'so.tglpelayananawal as tglmenu',
                'so.statusorder',
                'pd.tglregistrasi',
                'ps.tgllahir',
                'ps.namapasien',
                'pd.nocmfk',
                'ru.namaruangan as ruanganasal',
                'jk.jeniskelamin',
                'op.objectjeniswaktufk',
                'op.objectruanganfk',
                'jw.jeniswaktu',
                // 'jd.jenisdiet',
                'op.strukorderfk',
                'op.objectkategorydietfk',
                'kd.kategorydiet',
                'op.qtyproduk',
                'op.keteranganlainnya',
                'op.statusgizi as jenisorder',
                'op.qtyprodukinuse as cc',
                'op.jumlah as volume',
                'op.objectkelasfk',
                'kls.namakelas',
                'pd.noregistrasi',
                'pg.namalengkap as pegawaiorder',
                'sk.nokirim',
                'sk.qtyproduk',
                'pd.norec',
                'so.objectruangantujuanfk',
                'op.strukkirimfk',
                'sk.nokirim',
                'ru2.namaruangan as ruangantujuan',
                'jw.jeniswaktu',
                'op.objectjeniswaktufk',
                'op.arrjenisdiet',
                'ps.nocm',
                'ps.nobpjs',
                'ps.noidentitas',
                'kp.kelompokpasien',
                'op.statusordergizi',
                DB::raw("
                case when op.strukkirimfk is not null then 'Sudah Dikirim' 
                else 'Belum Dikirim' end as statuskirim,
                case 
                    when op.statusordergizi = 0 THEN 'Belum Verifikasi'
                    when op.statusordergizi = 1 THEN 'Terverifikasi'
                    when op.statusordergizi = 2 THEN 'Diteruskan'
                    when op.statusordergizi = 3 THEN 'Diproses'
                    when op.statusordergizi = 4 THEN 'Dikirim'
                    when op.statusordergizi = 5 THEN 'Selesai'
                end as statusgizi
                ")
            )
            ->where('op.kdprofile', $this->kdProfile)
            ->where('op.statusenabled', true)
            ->where('so.statusenabled', true)
            ->whereNotNull('tt.reportdisplay')
            // ->whereNull('pd.tglpulang')
            ->whereIN('so.objectkelompoktransaksifk', explode(',', $this->settingFix('idPelayananGizi')));

            // if (isset($r['norec_pd'])) {
            //     $data = $data->where('pd.norec', $r['norec_pd']);
            // }
        if (isset($r['nocm'])) {
                $data = $data->where('pd.nocmfk', $r['nocm']);
            }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"),'>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"),'<=', $r->sampai);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }

        if (isset($r['qsearch']) && $r['qsearch'] != '') {
            $searchTerm = '%' . $r['qsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                      ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                      ->orWhere('ps.nocm', 'ilike', $searchTerm)
                      ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                      ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }

        if (isset($r['norec_so']) && $r['norec_so'] != '') {
            $data = $data->where('so.norec', $r['norec_so']);
        }

        if (isset($r['statusorder']) && $r['statusorder'] != '') {
            $data = $data->where('op.statusordergizi', $r['statusorder']);
        }

        if (isset($r['jeniswaktuid']) && $r['jeniswaktuid'] != '') {
            $data = $data->where('op.objectjeniswaktufk', $r['jeniswaktuid']);
        }

        $data = $data->whereNull('pd.tglpulang');
        $data = $data->groupBy(
            'pd.norec',
            'so.norec',
            'op.norec',
            'so.noorder',
            'tt.reportdisplay',
            'so.tglorder',
            'so.tglpelayananawal',
            'so.statusorder',
            'pd.tglregistrasi',
            'ps.tgllahir',
            'ps.namapasien',
            'pd.nocmfk',
            'ru.namaruangan',
            'jk.jeniskelamin',
            'op.objectjeniswaktufk',
            'op.objectruanganfk',
            'jw.jeniswaktu',
            'op.strukorderfk',
            'op.objectkategorydietfk',
            'kd.kategorydiet',
            'op.qtyproduk',
            'op.keteranganlainnya',
            'op.statusgizi',
            'op.qtyprodukinuse',
            'op.jumlah',
            'op.objectkelasfk',
            'kls.namakelas',
            'pd.noregistrasi',
            'pg.namalengkap',
            'sk.nokirim',
            'sk.qtyproduk',
            'pd.norec',
            'so.objectruangantujuanfk',
            'op.strukkirimfk',
            'sk.nokirim',
            'ru2.namaruangan',
            'jw.jeniswaktu',
            'op.objectjeniswaktufk',
            'op.arrjenisdiet',
            'ps.nocm',
            'ps.nobpjs',
            'ps.noidentitas',
            'kp.kelompokpasien',
            'op.statusordergizi'
        );
        $data = $data->orderBy('so.noorder', 'desc');
        $data = $data->get();

        $menu = array();
        if (count($data) > 0) {
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['tglorder'] = date('Y-m-d', strtotime($data[0]->tglorder));
            $objetoRequest['kelasid'] = $data[0]->objectkelasfk;
            $responseJson = app('App\Http\Controllers\Gizi\MasterMenuGiziCtrl')->getMenuGiziByKelas($objetoRequest, true);
            $menu = $responseJson->getData()->response;
        }
        foreach ($data as $item) {
            $item->umur = $this->getAge($item->tgllahir, $item->tglregistrasi);
            if (count($menu->data) > 0) {
                foreach ($menu->data as $itemDetail) {
                    if ($item->objectjeniswaktufk == $itemDetail->jeniswaktufk) {
                        $item->menu = $itemDetail->menu;
                        $item->keteranganmenu = $itemDetail->keteranganmenu;
                    }
                }
            }
        }

        $dataResult = array(
            'message' =>  '@epic',
            'data' =>  $data,

        );
        return $this->respond($dataResult);
    }

    public function laporanOrderGizi(Request $r)
    {
        $data = DB::table('orderpelayanan_t as op')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('tempattidur_m as tt','tt.id','apd.nobed')
            ->leftjoin ('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=' , 'kp.id')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftjoin('jenisdiet_m as jd', 'jd.id', '=', 'op.objectjenisdietfk')
            ->leftjoin('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'op.objectkelasfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'pd.norec',
                'so.norec as norec_so',
                'op.norec as norec_op',
                'so.noorder',
                'tt.reportdisplay',
                'so.tglorder',
                'so.tglpelayananawal as tglmenu',
                'pd.tglregistrasi',
                'ps.tgllahir',
                'ps.nocm',
                'ps.namapasien',
                'pd.nocmfk',
                'ps.id as nocmfk',
                'ru.namaruangan as ruanganasal',
                'op.objectruanganfk',
                'op.strukorderfk',
                'op.objectkategorydietfk',
                'kd.kategorydiet',
                'op.qtyproduk',
                'op.keteranganlainnya',
                'op.statusgizi as jenisorder',
                'op.qtyprodukinuse as cc',
                'op.jumlah as volume',
                'op.objectkelasfk',
                'kls.namakelas',
                'pd.noregistrasi',
                'pg.namalengkap as pegawaiorder',
                'pd.norec',
                'so.objectruangantujuanfk',
                'op.strukkirimfk',
                'ru2.namaruangan as ruangantujuan',
                'op.arrjenisdiet',
                'kp.kelompokpasien',
            )
            ->where('op.kdprofile', $this->kdProfile)
            ->where('op.statusenabled', true)
            ->whereNotNull('tt.reportdisplay')
            ->whereIN('so.objectkelompoktransaksifk', explode(',', $this->settingFix('idPelayananGizi')));

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"),'>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("so.tglorder::date"),'<=', $r->sampai);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }

        $data = $data->whereNull('pd.tglpulang');
        $data = $data->orderBy('so.noorder', 'desc');
        $data = $data->get();
        $dataResult = array(
            'message' =>  '@epic',
            'data' =>  $data,
        );
        return $this->respond($dataResult);
    }

    public function simpanOrderGizi(Request $r)
    {

        DB::beginTransaction();
        try {
            $item = $r['strukorder'];
            $tglorder =  date('Y-m-d H:i:s');
            //            foreach ($r['strukorder'] as $item){
            if ($item['norec_so'] == '') {
                $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 11, 'G' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $this->kdProfile;
                $dataSO->statusenabled = true;
                $dataSO->isdelivered = 1;
                $dataSO->noorder = $noOrder;
                $dataSO->noorderintern = $noOrder;
            } else {
                $dataSO = StrukOrder::where('norec', $item['norec_so'])->where('kdprofile', $this->kdProfile)->first();
                $del = OrderPelayanan::where('strukorderfk', $item['norec_so'])->where('kdprofile', $this->kdProfile)->delete();
            }
            $dataSO->noregistrasifk = $item['noregistrasifk'];
            $dataSO->objectpegawaiorderfk = $this->getUserId();
            $dataSO->qtyjenisproduk = 1;
            $dataSO->qtyproduk = 2;
            $dataSO->objectruangantujuanfk = $this->settingFix('kdRuanganGizi', $this->kdProfile);
            $dataSO->keteranganorder = 'Order Gizi';
            $dataSO->objectkelompoktransaksifk = 8; /* Pelayanan Gizi*/
            $dataSO->tglorder = $item['tglorder']; // date('Y-m-d H:i:s');
            // $dataSO->tglpelayananawal = $item['tglmenu']; //$item['tglorder'];
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            if (isset($item['statusorder'])) {
                $dataSO->statusorder = $item['statusorder'];
            }
            $dataSO->save();
            $dataSOnorec = $dataSO->norec;

            foreach ($item['details'] as $itemDetails) {
                $data = implode(',', $itemDetails['datas']);
                $dataOP = new OrderPelayanan;
                $dataOP->norec = $dataOP->generateNewId();
                $dataOP->kdprofile = $this->kdProfile;
                $dataOP->statusenabled = true;
                $dataOP->iscito = 0;
                $dataOP->arrjenisdiet = $data;
                $dataOP->objectjeniswaktufk = $itemDetails['objectjeniswaktufk'];
                $dataOP->objectkategorydietfk = $itemDetails['objectkategorydietfk'];
                $dataOP->keteranganlainnya = $itemDetails['keteranganlainnya']; //'Order Gizi';
                $dataOP->nocmfk = $itemDetails['nocmfk'];
                $dataOP->noregistrasifk = $itemDetails['norec_pd'];
                $dataOP->noorderfk = $dataSOnorec;
                $dataOP->qtyproduk = 1;
                $dataOP->objectkelasfk = $itemDetails['objectkelasfk'];
                $dataOP->qtyprodukretur = 0;
                $dataOP->objectruanganfk =  $itemDetails['objectruanganlastfk'];
                $dataOP->objectruangantujuanfk = $this->settingFix('kdRuanganGizi', $this->kdProfile);;
                $dataOP->strukorderfk = $dataSOnorec;
                $dataOP->tglpelayanan = $item['tglorder'];
                $dataOP->statusordergizi = 0;
                $dataOP->save();

                // foreach($itemDetails['datas'] as $jenisDiet){
                //     $dataOP->arrjenisdiet = $jenisDiet;
                //     $dataOP->save();
                //  }
            }
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "data"  => $dataSO,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function deleteOrderGizi(Request $r)
    {
        DB::beginTransaction();
        try {
            $model = OrderPelayanan::where('norec', $r['norec'])->delete();
            
            $orderPel = OrderPelayanan::where('noorderfk', $r['norec_so'])->get();

            if (count($orderPel) == 0) {
                StrukOrder::where('norec', $r['norec_so'])->update([
                    'statusenabled' => false
                ]);
            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Data Berhasil Di Hapus";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $model,
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

    public function saveKirimGizi(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['strukkirim']['norec_sk'] == '') {
                $noKirim = $this->SEQUENCE(new StrukKirim, 'nokirim', 14, 'KM-' . $this->getDateTime()->format('ym'), $this->kdProfile);
                $dataSK = new StrukKirim();
                $dataSK->norec = $dataSK->generateNewId();
                $dataSK->nokirim = $noKirim;
                $dataSK->kdprofile = $this->kdProfile;
                $dataSK->statusenabled = true;
                $dataSK->tglkirim = date('Y-m-d H:i:s');
            } else {
                $dataSK = StrukKirim::where('norec', $r['strukkirim']['norec_sk'])->first();
                $delKP = KirimProduk::where('nokirimfk', $r['strukkirim']['norec_sk'])->delete();
            }
            $dataSK->objectpegawaipengirimfk = $this->getUserId();
            $dataSK->objectruanganasalfk = $r['strukkirim']['objectruanganasalfk']; /*ruang gizi */
            $dataSK->objectruanganfk = $r['strukkirim']['objectruanganfk'];
            $dataSK->jenispermintaanfk = 1; /*transfer */
            $dataSK->objectkelompoktransaksifk = 98;
            // $dataSK->keteranganlainnyakirim = $r['strukkirim']['keterangan'];
            $dataSK->qtydetailjenisproduk = 0;
            $dataSK->qtyproduk = 1;
            $dataSK->totalbeamaterai = 0;
            $dataSK->totalbiayakirim = 0;
            $dataSK->totalbiayatambahan = 0;
            $dataSK->totaldiscount = 0;
            $dataSK->totalhargasatuan = 0;
            $dataSK->totalharusdibayar = 0;
            $dataSK->totalpph = 0;
            $dataSK->totalppn = 0;
            $dataSK->noregistrasifk = $r['strukkirim']['noregistrasifk'];

            $dataSK->save();
            $norecSK = $dataSK->norec;

            foreach ($r['strukkirim']['details'] as $items) {
                $dataKP = new KirimProduk;
                $dataKP->norec = $dataKP->generateNewId();
                $dataKP->kdprofile = $this->kdProfile;
                $dataKP->statusenabled = true;
                $dataKP->hargadiscount = 0;
                $dataKP->harganetto = 0;
                $dataKP->hargapph = 0;
                $dataKP->hargappn = 0;
                $dataKP->hargasatuan = 0;
                $dataKP->hargatambahan = 0;
                $dataKP->objectprodukfk = $items['produk'];
                $dataKP->objectprodukkirimfk = $items['produk'];
                $dataKP->nokirimfk = $norecSK;
                $dataKP->persendiscount = 0;
                $dataKP->qtyproduk = $items['qtyproduk'];
                $dataKP->qtyprodukkonfirmasi = $items['qtyproduk'];
                $dataKP->qtyprodukretur = 0;
                $dataKP->qtyprodukterima = $items['qtyproduk'];
                $dataKP->objectruanganpengirimfk = $r['strukkirim']['objectruanganasalfk'];
                $dataKP->satuan = '-';
                $dataKP->tglpelayanan = date('Y-m-d H:i:s');
                $dataKP->qtyprodukterimakonversi = $items['qtyproduk'];
                $dataKP->save();
            }

            $kdProfile = $this->kdProfile;

            $norec_pd = $r['strukkirim']['noregistrasifk'];
            $pd = DB::table('pasiendaftar_t as pd')->where('pd.norec', $norec_pd)
           ->join('pasien_m as pe', 'pe.id','=','pd.nocmfk')
           ->select('pe.namapasien', 'pe.nocm', 'pd.*')
            ->first();

            $apd = AntrianPasienDiperiksa::where('noregistrasifk', $norec_pd )
                    ->where('objectruanganfk', $r['strukkirim']['objectruanganasalfk'])
                    ->first();
            $data = DB::select(
                DB::raw("select * from pelayananpasien_t as pp
                    where pp.noregistrasi = '$pd->noregistrasi' and pp.keteranganlain = 'gizi otomatis' and pp.kdprofile = $this->kdProfile
                    and pp.statusenabled=true")

            );
            $jenispelayananfk = " and hett.objectjenispelayananfk = " . $pd->jenispelayanan;

            $objectrekananfk = "  AND hett.objectpenjaminfk IS NULL";
            if (count($data) == 0) {
                $set = explode(',', $this->settingFix('kodePelayananGiziOtomatis'));

                foreach ($set as $dd) {
                    $sirahMacan = DB::select(
                        DB::raw("select hett.* from harganettoprodukbykelas_m as hett
                        where hett.objectkelasfk='$apd->objectkelasfk'
                        and hett.kdprofile =$kdProfile
                        and hett.objectprodukfk = $dd
                        and hett.statusenabled=true
                        $objectrekananfk
                        $jenispelayananfk
                        ")
                    );
                    $produk = Produk::where('id', $dd)
                    ->first();
                    if(count($sirahMacan) == 0){

                        $this->LOGGING(
                            'Pelayanan Gizi Otomatis',
                            $pd->noregistrasi,
                            'pasiendaftar_t',
                            'Pelayanan Gizi Otomatis gagal Master tarif tidak ada ('.$produk->namaproduk.') pada Pasien ' .  $pd->namapasien . ' (' . $pd->nocm . ') - ' . $pd->noregistrasi
                        );

                    }
                    else {
                        $PelPasien = new PelayananPasien();
                        $PelPasien->norec = $PelPasien->generateNewId();
                        $PelPasien->kdprofile = $kdProfile;
                        $PelPasien->statusenabled = true;
                        $PelPasien->noregistrasifk =  $apd->norec;
                        $PelPasien->tglregistrasi = $pd->tglregistrasi;
                        $PelPasien->hargadiscount = 0;
                        $PelPasien->hargajual =  $sirahMacan[0]->hargasatuan;
                        $PelPasien->hargasatuan =  $sirahMacan[0]->hargasatuan;
                        $PelPasien->jumlah = 1;
                        $PelPasien->kelasfk =  $apd->objectkelasfk;
                        $PelPasien->kdkelompoktransaksi =  1;
                        $PelPasien->piutangpenjamin =  0;
                        $PelPasien->piutangrumahsakit = 0;
                        $PelPasien->produkfk =  $sirahMacan[0]->objectprodukfk;
                        $PelPasien->stock =  1;
                        $PelPasien->tglpelayanan = date('Y-m-d H:i:s');
                        $PelPasien->keteranganlain = 'gizi otomatis';
                        $PelPasien->harganetto =  $sirahMacan[0]->harganetto1;
                        $PelPasien->noregistrasi = $pd->noregistrasi;
                        $PelPasien->save();
                        $PPnorec = $PelPasien->norec;

                        $buntutMacan = DB::select(
                            DB::raw("select hett.* from harganettoprodukbykelasd_m as hett
                            where hett.objectkelasfk='$apd->objectkelasfk'
                            and hett.kdprofile =$kdProfile
                            and hett.objectprodukfk = $dd
                            and hett.statusenabled=true
                            $objectrekananfk
                            $jenispelayananfk
                            ")
                        );

                        foreach ($buntutMacan as $itemKomponen) {
                            $PelPasienDetail = new PelayananPasienDetail();
                            $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                            $PelPasienDetail->kdprofile = $kdProfile;
                            $PelPasienDetail->statusenabled = true;
                            $PelPasienDetail->noregistrasifk = $apd->norec;
                            $PelPasienDetail->aturanpakai = '-';
                            $PelPasienDetail->hargadiscount = 0;
                            $PelPasienDetail->hargajual = $itemKomponen->hargasatuan;
                            $PelPasienDetail->hargasatuan = $itemKomponen->hargasatuan;
                            $PelPasienDetail->jumlah = 1;
                            $PelPasienDetail->keteranganlain = '-';
                            $PelPasienDetail->keteranganpakai2 = '-';
                            $PelPasienDetail->komponenhargafk = $itemKomponen->objectkomponenhargafk;
                            $PelPasienDetail->pelayananpasien = $PPnorec;
                            $PelPasienDetail->piutangpenjamin = 0;
                            $PelPasienDetail->piutangrumahsakit = 0;
                            $PelPasienDetail->produkfk = $itemKomponen->objectprodukfk;
                            $PelPasienDetail->stock = 1;
                            $PelPasienDetail->tglpelayanan = date('Y-m-d H:i:s');
                            $PelPasienDetail->harganetto = $itemKomponen->harganetto1;
                            $PelPasienDetail->noregistrasi = $pd->noregistrasi;
                            $PelPasienDetail->save();

                            $diskon = 0;
                        }


                        $this->LOGGING(
                            'Pelayanan Gizi Otomatis',
                            $PPnorec,
                            'pelayananpasien_t',
                            'Pelayanan Gizi Otomatis Sukses, Layanan : '.$produk->namaproduk.' pada pasien ' .  $pd->namapasien . ' (' . $pd->nocm . ') - ' . $pd->noregistrasi
                        );
                    }


                }

            }

            OrderPelayanan::where('norec', $r['strukkirim']['norec_op'])->update([
                'strukkirimfk' => $norecSK,
            ]);

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan Berhasil",
                "result" => array(
                    "data"  => $dataSK,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();

            $result = array(
                "status" => 400,
                "message" => 'Gagal',
                "result"  => null

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    function riwayatKirimGizi(Request $r)
    {
        $data = DB::table('strukkirim_t as sk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sk.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sk.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sk.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sk.objectpegawaipengirimfk')
            ->select(
                'sk.norec as norec_sk',
                'sk.nokirim',
                'pg.namalengkap as pegawaikirim',
                'ru2.namaruangan as ruangantujuan',
                'sk.tglkirim',
                'ru.namaruangan as ruanganasal',
                'sk.objectruangantujuanfk',
                'sk.objectruanganasalfk',
                'sk.objectpegawaipengirimfk',
                'pd.noregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'sk.keteranganlainnyakirim',
                'sk.qtyproduk'
            )
            ->where('sk.statusenabled', true)
            ->where('sk.objectkelompoktransaksifk', 98);

        if (isset($r['tgl']) && $r['tgl'] != '') {
                $data = $data->whereRaw("to_char(sk.tglkirim,'yyyy-MM-dd') = '$r[tgl]'");
            }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }

        $data = $data->orderBy('sk.nokirim', 'desc');
        $data = $data->get();
        $result = [];
        foreach ($data as $item) {
            $details2 = DB::select(
                DB::raw("
                    select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.objectprodukfk as produkfk
                    from kirimproduk_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where nokirimfk=:norec_sk and spd.qtyproduk <> 0"),
                array(
                    'norec_sk' => $item->norec_sk,
                )
            );
            $details = DB::select(
                DB::raw("
                    select  pd.nocmfk,pd.noregistrasi,ps.namapasien,ps.nocm,
                    kd.kategorydiet,jd.jenisdiet,ru.namaruangan
                  ,jw.jeniswaktu
                    from orderpelayanan_t as op
                     JOIN pasiendaftar_t as pd on pd.norec=op.noregistrasifk
                    left JOIN pasien_m as ps on ps.id=pd.nocmfk
                    left JOIN kategorydiet_m as kd on kd.id=op.objectkategorydietfk
                    left JOIN jenisdiet_m as jd on jd.id=op.objectjenisdietfk
                    left JOIN jeniswaktu_m as jw on jw.id=op.objectjeniswaktufk
                     left JOIN ruangan_m as ru on ru.id=op.objectruanganfk

                    where op.strukkirimfk=:norec_sk "),
                array(
                    'norec_sk' => $item->norec_sk,
                )
            );

            $result[] = array(
                'norec_sk' => $item->norec_sk,
                'nokirim' => $item->nokirim,
                'pegawaikirim' => $item->pegawaikirim,
                'ruangantujuan' => $item->ruangantujuan,
                'tglkirim' => $item->tglkirim,
                'ruanganasal' => $item->ruanganasal,
                'objectruangantujuanfk' => $item->objectruangantujuanfk,
                'objectruanganasalfk' =>  $item->objectruanganasalfk,
                'objectpegawaipengirimfk' => $item->objectpegawaipengirimfk,
                'noregistrasi' => $item->noregistrasi,
                'nocm' => $item->nocm,
                'namapasien' => $item->namapasien,
                'keterangan' => $item->keteranganlainnyakirim,
                'qtyproduk' => $item->qtyproduk,
                'details' => $details,
                'details2' => $details2,
            );
        }

        $dataResult = array(
            'message' =>  '@epic',
            'data' =>  $result,

        );
        return $this->respond($dataResult);
    }

    public function riwayatOrderGiziNew(Request $request)
    {
        $data = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'so.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', function($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
            })
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('orderpelayanan_t as op', 'op.noorderfk', '=', 'so.norec')
            ->leftJoin('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->leftJoin('jenisdiet_m as jd', 'jd.id', '=', 'op.objectjenisdietfk')
            ->select(
                'so.norec as norec_so',
                'so.tglorder',
                'so.noorder',
                'so.statusorder',
                DB::raw("
                    CASE 
                        WHEN so.statusorder = 0 THEN 'Menunggu Verifikasi'
                        WHEN so.statusorder = 1 THEN 'Terverifikasi'
                        WHEN so.statusorder = 2 THEN 'Diteruskan'
                        WHEN so.statusorder = 3 THEN 'Diproses'
                        WHEN so.statusorder = 4 THEN 'Dikirim'
                        WHEN so.statusorder = 5 THEN 'Selesai'
                        ELSE 'Selesai' END
                    AS status
                "),
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'pd.nocmfk',
                'pd.objectruanganlastfk',
                'pd.tglregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'ps.nocm',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'op.objectkategorydietfk',
                'op.objectjenisdietfk',
                'op.keteranganlainnya',
                'kd.kategorydiet',
                'jd.jenisdiet',
                'tt.reportdisplay',
                'pd.objectkelasfk',
                'kl.namakelas'
            )
            ->where('so.kdprofile', (int)$this->kdProfile)
            ->where('so.statusenabled', true)
            ->whereIN('so.objectkelompoktransaksifk', explode(',', $this->settingFix('idPelayananGizi')))
            ->whereBetween('so.tglorder', [$request['dari'], $request['sampai']]);

        if (isset($r['qsearch']) && $request['qsearch'] != '') {
            $searchTerm = '%' . $r['qsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($request['ruanganid']) && $request['ruanganid'] != '') {
            $data = $data->where('pd.objectruanganlastfk', $request['ruanganid']);
        }

        if (isset($request['statusorder']) && $request['statusorder'] != '') {
            $data = $data->where('so.statusorder', $request['statusorder']);
        }

        if (isset($request['jeniswaktuid']) && $request['jeniswaktuid'] != '') {
            $data = $data->where('op.objectjeniswaktufk', $request['jeniswaktuid']);
        }

        $data = $data->whereNull('pd.tglpulang');
        $data = $data->groupBy(
            'so.norec',
            'so.tglorder',
            'so.noorder',
            'so.statusorder',
            'pd.norec',
            'pd.noregistrasi',
            'pd.nocmfk',
            'pd.objectruanganlastfk',
            'pd.tglregistrasi',
            'ru.namaruangan',
            'ps.namapasien',
            'ps.nocm',
            'ps.objectjeniskelaminfk',
            'jk.jeniskelamin',
            'op.objectkategorydietfk',
            'op.objectjenisdietfk',
            'kd.kategorydiet',
            'jd.jenisdiet',
            'tt.reportdisplay',
            'op.keteranganlainnya',
            'pd.objectkelasfk',
            'kl.namakelas'
        );
        $data = $data->orderBy('so.noorder', 'desc');
        $data = $data->get();

        $arrNorecSo = [];
        foreach ($data as $value) {
            $arrNorecSo[] = $value->norec_so;
        }

        $dataDetail = DB::table('orderpelayanan_t as op')
            ->leftJoin('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->select(
                'op.noorderfk',
                'op.norec as norec_op',
                'op.objectjeniswaktufk',
                'jw.jeniswaktu',
                'op.statusordergizi as statusordergiziid',
                DB::raw("
                    case when op.strukkirimfk is not null then 'Sudah Dikirim' 
                    else 'Belum Dikirim' end as statuskirim,
                    case 
                        when op.statusordergizi = 0 THEN 'Belum Verifikasi'
                        when op.statusordergizi = 1 THEN 'Terverifikasi'
                        when op.statusordergizi = 2 THEN 'Diteruskan'
                        when op.statusordergizi = 3 THEN 'Diproses'
                        when op.statusordergizi = 4 THEN 'Dikirim'
                        when op.statusordergizi = 5 THEN 'Selesai'
                    end as statusordergizi
                ")
            )
            ->where('op.kdprofile', (int)$this->kdProfile)
            ->where('op.statusenabled', true)
            ->whereIn('op.noorderfk', $arrNorecSo)
            ->get();

        $result = [];

        $groupedDetail = [];
        foreach ($dataDetail as $detail) {
            $orderId = $detail->noorderfk;
            $groupedDetail[$orderId][] = $detail;
        }

        foreach ($data as $value) {
            $orderId = $value->norec_so;
            $details = $groupedDetail[$orderId] ?? [];

            if (count($details) === 0) {
                $value->status = 'Belum Diproses';
                continue;
            }

            $countBelum = 0;
            $countProses = 0;
            $countSelesai = 0;
            $total = count($details);

            foreach ($details as $detail) {
                $statusId = $detail->statusordergiziid;
                if ($statusId <= 2) {
                    $countBelum++;
                } else if ($statusId >= 3 && $statusId < 5) {
                    $countProses++;
                } else if ($statusId == 5) {
                    $countSelesai++;
                }
            }

            if ($countSelesai === $total) {
                $value->status = 'Selesai';
            } else if ($countBelum > 0) {
                $value->status = 'Belum Diproses';
            } else {
                $value->status = 'Diproses';
            }

            $value->detail = $details;
            $result[] = $value;
        }

        $dataResult = array(
            'message' =>  '@epic',
            'data' =>  $result,

        );
        
        return $this->respond($dataResult);
    }

    public function saveStatusOrderGizi(Request $request)
    {
        DB::beginTransaction();
        try {
            if (isset($request['norec_op'])) {
                $modelDetail = OrderPelayanan::where('norec', $request['norec_op'])->update([
                    'statusordergizi' => $request['statusorder']
                ]);

                if ($request['statusorder'] == 3 || $request['statusorder'] == 4) {
                    $model = StrukOrder::where('norec', $request['norec_so'])->update([
                        'statusorder' => 4
                    ]);
                } else if ($request['statusorder'] == 5) {
                    $model = StrukOrder::where('norec', $request['norec_so'])->update([
                        'statusorder' => 5
                    ]);
                }
            } else {
                $model = StrukOrder::where('norec', $request['norec_so'])->update([
                    'statusorder' => $request['statusorder']
                ]);

                $modelDetail = OrderPelayanan::where('noorderfk', $request['norec_so'])->update([
                    'statusordergizi' => $request['statusorder']
                ]);
            }

            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus == true) {
            $transMessage = "Data Berhasil Di Verif";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => null,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Verif Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' - ' . $e->getLine()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getLaporanOrderGizi(Request $request)
    {
        $data = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.noorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'op.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', function($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
            })
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('kamar_m as kmr', 'kmr.id', '=', 'apd.objectkamarfk')
            ->leftJoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->select(
                'so.tglorder',
                'ps.namapasien',
                'ru.namaruangan',
                'kmr.namakamar',
                'tt.nomorbed',
                'pd.objectkelasfk',
                'op.objectjeniswaktufk',
                DB::raw("
                    case 
                        when op.statusordergizi = 0 THEN 'Belum Verifikasi'
                        when op.statusordergizi = 1 THEN 'Terverifikasi'
                        when op.statusordergizi = 2 THEN 'Diteruskan'
                        when op.statusordergizi = 3 THEN 'Diproses'
                        when op.statusordergizi = 4 THEN 'Dikirim'
                        when op.statusordergizi = 5 THEN 'Selesai'
                    end as statusordergizi
                ")
            )
            ->where('op.kdprofile', $this->kdProfile)
            ->where('op.statusenabled', true)
            ->where('so.statusenabled', true)
            ->whereIN('so.objectkelompoktransaksifk', explode(',', $this->settingFix('idPelayananGizi')))
            ->whereBetween('so.tglorder', [$request['tglAwal'], $request['tglAkhir']]);

        if (isset($request['ruanganId']) && $request['ruanganId'] != '' && $request['ruanganId'] != 'undefined') {
            $data = $data->where('ru.id', $request['ruanganId']);
        }

        $data = $data->groupBy(
            'so.tglorder',
            'ps.namapasien',
            'ru.namaruangan',
            'kmr.namakamar',
            'tt.nomorbed',
            'pd.objectkelasfk',
            'op.objectjeniswaktufk',
            'op.statusordergizi'
        );
        $data = $data->orderBy('so.tglorder');
        $data = $data->get();

        $menu = array();
        if (count($data) > 0) {
            $objetoRequest = new \Illuminate\Http\Request();
            $objetoRequest['tglorder'] = date('Y-m-d', strtotime($data[0]->tglorder));
            $objetoRequest['kelasid'] = $data[0]->objectkelasfk;
            $responseJson = app('App\Http\Controllers\Gizi\MasterMenuGiziCtrl')->getMenuGiziByKelas($objetoRequest, true);
            $menu = $responseJson->getData()->response;
        }
        foreach ($data as $item) {
            if (count($menu->data) > 0) {
                foreach ($menu->data as $itemDetail) {
                    if ($item->objectjeniswaktufk == $itemDetail->jeniswaktufk) {
                        $item->menu = $itemDetail->menu;
                        $item->keteranganmenu = $itemDetail->keteranganmenu;
                    }
                }
            }
        }

        $dataResult = array(
            'message' =>  '@epic',
            'data' =>  $data,

        );
        return $this->respond($dataResult);
    }
}
