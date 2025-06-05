<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokTransaksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DaftarPenerimaanKasirCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function daftarPenerimaan(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $data = DB::table('strukbuktipenerimaan_t as sbm')
            ->join('strukpelayanan_t as sp', 'sbm.nostrukfk', '=', 'sp.norec')
            ->leftjoin('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pegawai_m as p', 'p.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sbm.ruanganfk')
            // ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('strukbuktipenerimaancarabayar_t as sbmcr', 'sbmcr.nosbmfk', '=', 'sbm.norec')
            ->leftjoin('carabayar_m as cb', 'cb.id', '=', 'sbmcr.objectcarabayarfk')
            ->leftjoin('kelompoktransaksi_m as kt', 'kt.id', '=', 'sbm.objectkelompoktransaksifk')
            ->select(
                'sbm.norec',
                'sbm.totalpiutangdibayar',
                'sbmcr.norec as norec_sbmcr',
                'cb.carabayar',
                'sbmcr.objectcarabayarfk',
                'sbm.objectkelompoktransaksifk',
                'kt.kelompoktransaksi',
                'sbm.keteranganlainnya',
                'sbm.objectpegawaipenerimafk',
                'p.namalengkap as kasir',
                'sbm.nosbm',
                // 'sbm.totaldibayar',
                'ru.namaruangan',
                'sbm.tglsbm',
                'pd.noregistrasi',
                'sp.norec as norec_sp',
                'ps.nocm',
                'sbmcr.totaldibayar',
                DB::raw("case when sbm.noclosingfk is null then 'Belum Setor' else 'Setor' end as statussetor,
                case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien
                ")
            )
            ->where('sbm.statusenabled', true)
            ->where('sbm.kdprofile', $kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '>=', $r['dari']);
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '<=',  $r['sampai']);
        }
        if (isset($r['idPegawai']) && $r['idPegawai'] != "" && $r['idPegawai'] != "undefined") {
            $data = $data->where('sbm.objectpegawaipenerimafk', '=', $r['idPegawai']);
        }
        if (isset($r['carabayar']) && $r['carabayar'] != "" && $r['carabayar'] != "undefined") {
            $data = $data->where('cb.id', '=', $r['carabayar']);
        }
        if (isset($r['idKelTransaksi']) && $r['idKelTransaksi'] != "" && $r['idKelTransaksi'] != "undefined") {
            $data = $data->where('kt.id', $r['idKelTransaksi']);
        }
        if (isset($r['ins']) && $r['ins'] != "" && $r['ins'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', $r['ins']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->where('ru.id', $r['ruang']);
        }
        if (isset($r['nosbm']) && $r['nosbm'] != "" && $r['nosbm'] != "undefined") {
            $data = $data->where('sbm.nosbm', 'ilike', '%' . $r['nosbm'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
            $data = $data->orWhere('sp.namapasien_klien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['desk']) && $r['desk'] != "" && $r['desk'] != "undefined") {
            $data = $data->where('sp.namapasien_klien', 'ilike', '%' . $r['desk'] . '%');
        }
        if (isset($r['ruangkasir']) && $r['ruangkasir'] != "" && $r['ruangkasir'] != "undefined") {
            $data = $data->where('sbm.ruanganfk', $r['ruangkasir']);
        }
        if (isset($r['kasirArr']) && $r['kasirArr'] != "" && $r['kasirArr'] != "undefined") {
            $arrRuang = explode(',', $r['kasirArr']);
            $kodeRuang = [];
            foreach ($arrRuang as $item) {
                $kodeRuang[] = (int) $item;
            }
            $data = $data->whereIn('p.id', $kodeRuang);
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
        $total = $data->get();

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }

        $data = $data->orderByDesc('sbm.tglsbm');
        $data = $data->get();

        $cara = CaraBayar::mine()->get();
        $totalAll = 0;
        foreach ($total as $d) {
            $totalAll =    $totalAll +  (float) $d->totaldibayar;
            foreach ($cara as $dd) {
                if ($dd->id == $d->objectcarabayarfk) {
                    $dd->total = (float)  $dd->total + (float) $d->totaldibayar;
                }
            }
        }

        $result['data'] = $data;
        $result['count'] = count($total);
        $result['total'] = $totalAll;
        $result['carabayar'] = $cara;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function daftarPenerimaanDropdown(Request $r)
    {
        $res['carabayar'] = CaraBayar::mine()->get();
        // $res['kasir'] = Pegawai::mine()->get();
        $res['kasir'] = Pegawai::mine()->where('objectjenispegawaifk', $this->settingFix('idPegawaiKasir') )->get();
        $res['departemen'] = Departemen::mine()->get()->toArray();
        $ru = Ruangan::mine()->get();
        foreach ($res['departemen']  as $k => $d) {
            $res['departemen'][$k]['ruangan'] = [];
            foreach ($ru  as $dd) {
                if ($dd->objectdepartemenfk == $d['id']) {
                    $res['departemen'][$k]['ruangan'][] = $dd;
                }
            }
        }
        $res['kelompoktransaksi'] = KelompokTransaksi::mine()->get();
        $res['ruangankasir'] = Ruangan::mine()->where('objectdepartemenfk', $this->settingFix('idDepartemenKasir'))->get();

        return $this->respond($res);
    }
    public function saveUbahCaraBayar(Request $r)
    {
        DB::beginTransaction();
        try {
            StrukBuktiPenerimaanCaraBayar::where('norec', $r['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['objectcarabayarfk' => $r['carabayar']]);

            $this->LOGGING(
                'Ubah Cara Bayar',
                $r['norec'],
                'strukbuktipenerimaan_t',
                'Ubah Cara Bayar ke ' . $r['carabayarname'] . ' pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $r['nosbm']
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
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveBatalBayar(Request $r)
    {

        DB::beginTransaction();
        try {
            $kdProfile = (int)$this->kdProfile;
            // $produkDeposit = $this->settingFix('idProdukDeposit');
            $sbm = StrukBuktiPenerimaan::where('norec', $r['norec'])->first();

            // if ($r['isdeposit'] == true && $sbm->noregistrasi != null) {
            //     $getPP = DB::select(DB::raw("
            //     select pp.norec ,pp.produkfk,pp.hargasatuan
            //     from  pelayananpasien_t as pp
            //     where pd.kdprofile = $kdProfile
            //     and pd.noregistrasi ='$sbm->noregistrasi'
            //     and pp.produkfk='$produkDeposit'"));
            //     if (count($getPP) > 0) {
            //         foreach ($getPP as $item) {
            //             if ($item->hargasatuan == $sbm['totaldibayar']) {
            //                 PelayananPasien::where('norec', $item->norec)
            //                     ->where('kdprofile', $kdProfile)
            //                     ->delete();
            //             }
            //         }
            //     }
            // }
            if ($sbm->keteranganlainnya == 'Pembayaran Cicilan Tagihan Pasien') {
                $strukPelayananPenjamin = StrukPelayananPenjamin::where('nostrukfk', $r['norec_sp'])
                    ->where('kdprofile', $kdProfile)
                    ->first();
                $strukPelayananPenjamin->totalsudahdibayar = $strukPelayananPenjamin->totalsudahdibayar - $sbm->totaldibayar;
                $strukPelayananPenjamin->save();
            }
            StrukPelayanan::where('norec', $r['norec_sp'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbmlastfk'    => null,
                    ]
                );
            StrukBuktiPenerimaan::where('norec', $r['norec'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusenabled' => false,
                        'nostrukfk'    => null,
                    ]
                );
            PasienDaftar::where('nostruklastfk', $r['norec_sp'])
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'nosbmlastfk'    => null,
                    ]
                );

            PasienDaftar::where('noregistrasi', $sbm->noregistrasi)->update(
                [
                    'statusbayar' => 'Belum Bayar'
                ]
            );
            //  //update flag deposit
            //  $KT = $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN');
            //  StrukBuktiPenerimaan::where('noregistrasi',$sbm->noregistrasi)
            //  ->where('kdprofile',$kdProfile)
            //  ->where('objectkelompoktransaksifk', $KT)
            //  ->update([
            //      'isbayar' => false
            //  ]);
            $this->LOGGING(
                'Batal Bayar',
                $r['norec'],
                'strukbuktipenerimaan_t',
                'Batal Bayar pada Pasien ' .
                    $r['namapasien'] .
                    ' (' . $r['nocm'] . ') - ' .
                    $r['nosbm']
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
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function cetakKwitansi(Request $r)
    {
        $profile = $this->profile();
        $kdProfile = $profile->id;
        $namapegawai = $this->getNamaPegawai();
        $tglcetak = $r['tanggalcetak'];
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
                sbp.nosbm as nokwitansi,
                sbp.totaldibayar,
                sbp.tglsbm,
                sbp.keteranganlainnya,
                sbp.objectpegawaipenerimafk,
                ps.namapasien,alm.alamatlengkap,
                rm.namaruangan,
                ps.nocm,
                sp.norec as norec_sp,
                case when sbp.namapegawaipenerima is null then ps.namapasien else sbp.namapegawaipenerima end as namapenangung,
                ps.nocm,
                pd.noregistrasi,
                sp.namatempattujuan
            FROM
                strukbuktipenerimaan_t as sbp
                LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                LEFT JOIN ruangan_m as rm on rm.id =  pd.objectruanganlastfk
                LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                left join alamat_m as alm on alm.nocmfk = ps.id
                LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
            WHERE
            sbp.norec = ?
            and pd.statusenabled= true
            and sbp.kdprofile=?
            ", [$r['norec'], $kdProfile]));

        if (count($identitas) == 0) {

            $identitas = collect(DB::select("
                SELECT
                    sbp.nosbm as nokwitansi,
                    sp.namatempattujuan,
                    sbp.totaldibayar,
                    sbp.tglsbm,
                    sbp.keteranganlainnya,
                    sp.norec as norec_sp,
                    sbp.objectpegawaipenerimafk,alm.alamatlengkap,
                    case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
                    case when sbp.namapegawaipenerima is null then sp.namapasien_klien else namapasien end as namapenangung,
                    case when ps.nocm is null then '-' else  ps.nocm end as nocm,
                    '-' as namaruangan,
                    case when pd.noregistrasi is null then sp.nostruk else  pd.noregistrasi end as noregistrasi

                FROM
                    strukbuktipenerimaan_t as sbp
                    LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                    LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                    LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                    left join alamat_m as alm on alm.nocmfk = ps.id
                    LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
                WHERE
                sbp.norec = ?
                and sbp.kdprofile=?
                ", [$r['norec'], $kdProfile]));
            // return $identitas;
        }
        // return $identitas;
        $detailLayanan = DB::table('strukpelayanandetail_t as sp')
            ->join('produk_m as pr', 'sp.objectprodukfk', '=', 'pr.id')
            ->select('pr.namaproduk', 'sp.objectprodukfk', 'sp.namaproduk as produk')
            ->where('sp.statusenabled', true)
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.nostrukfk', $identitas['0']->norec_sp)
            ->get();

        // return $identitas;
        $terbilang = $this->makeTerbilang($identitas[0]->totaldibayar);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'report.kasir.kwitansi',
            array(
                'identitas' => $identitas,
                'namaalias' => $r['namabaru'],
                'pdf' => isset($r['pdf']) ? $r['pdf'] : true,
                'terbilang' => $terbilang,
                'pageWidth' => $pageWidth,
                'detailLayanan' => $detailLayanan,
                'namapegawai' => $namapegawai,
                'profile' => $profile,
                'tglcetak' => $tglcetak
            )
        );
        $pdf->setPaper('A5', 'landscape');
        return $pdf->stream();
        // return view(
        //     'report.kasir.kwitansi',
        //     compact('identitas', 'namapegawai', 'tglcetak', 'terbilang', 'pageWidth', 'r', 'profile')
        // );
    }

    public function cetakKwitansiBayarPiutang(Request $r)
    {
        $profile = $this->profile();
        $kdProfile = $profile->id;
        $namapegawai = $this->getNamaPegawai();
        $tglcetak = $r['tanggalcetak'];
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
                sbp.nosbm as nokwitansi,
                sbp.totaldibayar,
                sbp.totalpiutangdibayar,
                sbp.tglsbm,
                sbp.keteranganlainnya,
                sbp.objectpegawaipenerimafk,
                ps.namapasien,alm.alamatlengkap,
                rm.namaruangan,
                ps.nocm,
                sp.norec as norec_sp,
                case when sbp.namapegawaipenerima is null then ps.namapasien else sbp.namapegawaipenerima end as namapenangung,
                ps.nocm,
                pd.noregistrasi
            FROM
                strukbuktipenerimaan_t as sbp
                LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                LEFT JOIN ruangan_m as rm on rm.id =  pd.objectruanganlastfk
                LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                left join alamat_m as alm on alm.nocmfk = ps.id
                LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
            WHERE
            sbp.norec = ?
            and pd.statusenabled= true
            and sbp.kdprofile=?
            ", [$r['norec'], $kdProfile]));

        if (count($identitas) == 0) {

            $identitas = collect(DB::select("
                SELECT
                    sbp.nosbm as nokwitansi,
                    sbp.totaldibayar,
                    sbp.totalpiutangdibayar,
                    sbp.tglsbm,
                    sbp.keteranganlainnya,
                    sp.norec as norec_sp,
                    sbp.objectpegawaipenerimafk,alm.alamatlengkap,
                    case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
                    case when sbp.namapegawaipenerima is null then sp.namapasien_klien else namapasien end as namapenangung,
                    case when ps.nocm is null then '-' else  ps.nocm end as nocm,
                    '-' as namaruangan,
                    case when pd.noregistrasi is null then sp.nostruk else  pd.noregistrasi end as noregistrasi

                FROM
                    strukbuktipenerimaan_t as sbp
                    LEFT JOIN strukpelayanan_t as sp on sp.norec = sbp.nostrukfk
                    LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                    LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                    left join alamat_m as alm on alm.nocmfk = ps.id
                    LEFT JOIN pegawai_m as pg on pg.id = sbp.objectpegawaipenerimafk
                WHERE
                sbp.norec = ?
                and sbp.kdprofile=?
                ", [$r['norec'], $kdProfile]));
            // return $identitas;
        }
        // return $identitas;
        $detailLayanan = DB::table('strukpelayanandetail_t as sp')
            ->join('produk_m as pr', 'sp.objectprodukfk', '=', 'pr.id')
            ->select('pr.namaproduk', 'sp.objectprodukfk', 'sp.namaproduk as produk')
            ->where('sp.statusenabled', true)
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.nostrukfk', $identitas['0']->norec_sp)
            ->get();

        // return $identitas;
        $terbilang = $this->makeTerbilang($identitas[0]->totalpiutangdibayar);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'report.kasir.kwitansi-bayar-piutang',
            array(
                'identitas' => $identitas,
                'namaalias' => $r['namabaru'],
                'pdf' => isset($r['pdf']) ? $r['pdf'] : true,
                'terbilang' => $terbilang,
                'pageWidth' => $pageWidth,
                'detailLayanan' => $detailLayanan,
                'namapegawai' => $namapegawai,
                'profile' => $profile,
                'tglcetak' => $tglcetak
            )
        );
        $pdf->setPaper('A5', 'landscape');
        return $pdf->stream();
        // return view(
        //     'report.kasir.kwitansi',
        //     compact('identitas', 'namapegawai', 'tglcetak', 'terbilang', 'pageWidth', 'r', 'profile')
        // );
    }
    public function cetakKwitansiPiutang(Request $r)
    {
        $profile = $this->profile();
        $kdProfile = $profile->id;
        $namapegawai = $this->getNamaPegawai();
        $tglcetak = $r['tanggalcetak'];
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
                r.namarekanan,
                r.alamatlengkap as alamat_rekanan,
                spp.nospp as nokwitansi,
                spp.totalbiaya,
                ps.namapasien,
                alm.alamatlengkap,
                rm.namaruangan,
                ps.nocm,
                sp.norec as norec_sp,
                ps.nocm,
                pd.noregistrasi
            FROM
                strukpelayananpenjamin_t as spp
                LEFT JOIN strukpelayanan_t as sp on sp.norec = spp.nostrukfk
                LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                LEFT JOIN ruangan_m as rm on rm.id =  pd.objectruanganlastfk
                LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                left join alamat_m as alm on alm.nocmfk = ps.id
                LEFT JOIN rekanan_m as r on r.id = spp.kdrekananpenjamin
            WHERE
            spp.norec = ?
            and pd.statusenabled= true
            and spp.kdprofile=?
            ", [$r['norec'], $kdProfile]));

        if (count($identitas) == 0) {

            $identitas = collect(DB::select("
                SELECT
                    r.namarekanan,
                    r.alamatlengkap as alamat_rekanan,
                    spp.nospp as nokwitansi,
                    spp.totalbiaya,
                    sp.norec as norec_sp,
                    alm.alamatlengkap,
                    case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end as namapasien,
                    case when ps.nocm is null then '-' else  ps.nocm end as nocm,
                    '-' as namaruangan,
                    case when pd.noregistrasi is null then sp.nostruk else  pd.noregistrasi end as noregistrasi

                FROM
                    strukpelayananpenjamin_t as spp
                    LEFT JOIN strukpelayanan_t as sp on sp.norec = spp.nostrukfk
                    LEFT JOIN pasiendaftar_t as pd on pd.norec = sp.noregistrasifk
                    LEFT JOIN pasien_m as ps on ps.id = sp.nocmfk
                    left join alamat_m as alm on alm.nocmfk = ps.id
                    LEFT JOIN rekanan_m as r on r.id = spp.kdrekananpenjamin
                WHERE
                spp.norec = ?
                and spp.kdprofile=?
                ", [$r['norec'], $kdProfile]));
            // return $identitas;
        }
        // return $identitas;
        $detailLayanan = DB::table('strukpelayanandetail_t as sp')
            ->join('produk_m as pr', 'sp.objectprodukfk', '=', 'pr.id')
            ->select('pr.namaproduk', 'sp.objectprodukfk', 'sp.namaproduk as produk')
            ->where('sp.statusenabled', true)
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.nostrukfk', $identitas['0']->norec_sp)
            ->get();

        // return $identitas;
        $terbilang = $this->makeTerbilang($identitas[0]->totalbiaya);
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'report.kasir.kwitansi-piutang',
            array(
                'identitas' => $identitas,
                'pdf' => isset($r['pdf']) ? $r['pdf'] : true,
                'terbilang' => $terbilang,
                'pageWidth' => $pageWidth,
                'detailLayanan' => $detailLayanan,
                'namapegawai' => $namapegawai,
                'profile' => $profile,
                'tglcetak' => $tglcetak
            )
        );
        $pdf->setPaper('A5', 'landscape');
        return $pdf->stream();
        // return view(
        //     'report.kasir.kwitansi',
        //     compact('identitas', 'namapegawai', 'tglcetak', 'terbilang', 'pageWidth', 'r', 'profile')
        // );
    }
}
