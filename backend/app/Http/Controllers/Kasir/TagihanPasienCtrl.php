<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;

class TagihanPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // Tagihan Lunas
    public function DaftarTagihanLunas(Request $request)
    {
        $result = array();
        $filter = $request->all();
        $kdProfile =  $this->kdProfile;
        $dataStrukPelayanan = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId'
            )
            ->where('sp.kdprofile', $kdProfile)
            ->whereNotNull('sp.nosbmlastfk');

        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('pd.noregistrasi', $filter['noReg']);
        }

        if (isset($filter['noRm']) && $filter['noRm'] != "" && $filter['noRm'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('p.nocm', 'ilike', '%' . $filter['noRm'] . '%');
        }

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '<=', $tgl);
        }

        if (isset($filter['instalasiId']) && $filter['instalasiId'] != "" && $filter['instalasiId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('dept.id', '=', $filter['instalasiId']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "" && $filter['ruanganId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('r.id', '=', $filter['ruanganId']);
        }

        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }

        if (isset($filter['kelompokPasienId']) && $filter['kelompokPasienId'] != "" && $filter['kelompokPasienId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokPasienId']);
        }


        if (isset($filter['status']) && $filter['status'] != "") {
            if ($filter['status'] == 'Lunas') {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.nosbmlastfk');
            } else {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNull('sp.nosbmlastfk');
            }
        }
        $dataStrukPelayanan = $dataStrukPelayanan->take(50);
        $dataStrukPelayanan = $dataStrukPelayanan->whereRaw('(sp.statusenabled is null or sp.statusenabled =true)');
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.totalharusdibayar');
        $dataStrukPelayanan  = $dataStrukPelayanan->where('sp.totalharusdibayar', '<>', 0);
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.noregistrasifk');
        if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->get();
        } else if (empty($filter['tglAwal']) && empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && $filter['instalasiId'] == "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else {
            $dataStrukPelayanan    = $dataStrukPelayanan->get();
        }

        foreach ($dataStrukPelayanan as $key => $item) {
            $sp = StrukPelayanan::find($item->norec);
            $result[] = array(
                'noRec' => $item->norec,
                'tglStruk' => $item->tglstruk,
                'tglMasuk' => $item->tglregistrasi,
                'tglPulang' => $item->tglpulang,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'noCm' => $item->nocm,
                'kelasRawat' => $item->namakelas,
                'lastRuangan' => $item->namaruangan,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => "-",
                'totalBilling' => $item->totalharusdibayar,
                'totalKlaim' => $item->totalprekanan,
                'totalBayar' => $item->totalharusdibayar,
                'statusBayar' => $sp->statusBayar,
                'ruanganId' => $item->ruanganId,
                'departmentId' => $item->departmentId,
            );
        }
        return $this->respond($result);
    }

    // Tagihan Belum Lunas
    public function DaftarTagihanBelumLunas(Request $request)
    {
        $result = array();
        $filter = $request->all();
        $kdProfile =  $this->kdProfile;
        $dataStrukPelayanan = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId'
            )
            ->where('sp.kdprofile', $kdProfile)
            ->whereNull('sp.nosbmlastfk');

        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('pd.noregistrasi', $filter['noReg']);
        }

        if (isset($filter['noRm']) && $filter['noRm'] != "" && $filter['noRm'] != "undefined") {
            $dataStrukPelayanan  = $dataStrukPelayanan->where('p.nocm', 'ilike', '%' . $filter['noRm'] . '%');
        }

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $dataStrukPelayanan = $dataStrukPelayanan->where('sp.tglstruk', '<=', $tgl);
        }

        if (isset($filter['instalasiId']) && $filter['instalasiId'] != "" && $filter['instalasiId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('dept.id', '=', $filter['instalasiId']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "" && $filter['ruanganId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('r.id', '=', $filter['ruanganId']);
        }

        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }

        if (isset($filter['kelompokPasienId']) && $filter['kelompokPasienId'] != "" && $filter['kelompokPasienId'] != "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokPasienId']);
        }


        if (isset($filter['status']) && $filter['status'] != "") {
            if ($filter['status'] == 'Lunas') {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.nosbmlastfk');
            } else {
                $dataStrukPelayanan  = $dataStrukPelayanan->whereNull('sp.nosbmlastfk');
            }
        }
        $dataStrukPelayanan = $dataStrukPelayanan->take(50);
        $dataStrukPelayanan = $dataStrukPelayanan->whereRaw('(sp.statusenabled is null or sp.statusenabled =true)');
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.totalharusdibayar');
        $dataStrukPelayanan  = $dataStrukPelayanan->where('sp.totalharusdibayar', '<>', 0);
        $dataStrukPelayanan  = $dataStrukPelayanan->whereNotNull('sp.noregistrasifk');
        if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->get();
        } else if (empty($filter['tglAwal']) && empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && empty($filter['instalasiId'])) {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else if (!empty($filter['tglAwal']) && !empty($filter['tglAkhir']) && empty($filter['noReg']) && empty($filter['noRm']) && empty($filter['status']) && empty($filter['ruanganId']) && empty($filter['namaPasien']) && $filter['instalasiId'] == "undefined") {
            $dataStrukPelayanan = $dataStrukPelayanan->limit(10)->get();
        } else {
            $dataStrukPelayanan    = $dataStrukPelayanan->get();
        }

        foreach ($dataStrukPelayanan as $key => $item) {
            $sp = StrukPelayanan::find($item->norec);
            $spj[] = array(
                'noRec' => $item->norec,
                'tglStruk' => $item->tglstruk,
                'tglMasuk' => $item->tglregistrasi,
                'tglPulang' => $item->tglpulang,
                'noRegistrasi' => $item->noregistrasi,
                'namaPasien' => $item->namapasien,
                'noCm' => $item->nocm,
                'kelasRawat' => $item->namakelas,
                'lastRuangan' => $item->namaruangan,
                'jenisPasien' => $item->kelompokpasien,
                'kelasPenjamin' => "-",
                'totalBilling' => $item->totalharusdibayar,
                'totalKlaim' => $item->totalprekanan,
                'totalBayar' => $item->totalharusdibayar,
                'statusBayar' => $sp->statusBayar,
                'ruanganId' => $item->ruanganId,
                'departmentId' => $item->departmentId,
            );
        }
        return $this->respond($spj);
    }

    public function getDaftarDepositPasien(Request $request)
    {

        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $datas = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('pasien_m as pas', 'pas.id', '=', 'pd.nocmfk')
            ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sbm.objectpegawaipenerimafk')
            ->select(
                'pas.namapasien',
                'pas.nocm',
                'pg.namalengkap as kasir',
                'sp.nostruk',
                'sp.tglstruk',
                'pd.tglregistrasi',
                'sbm.totaldibayar',
                'sbm.tglsbm',
                'sbm.nosbm',
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'sp.nosbklastfk',
            )
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as DATE)"),$rangeDate)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk',   $this->kelompokTransaksi('PEMBAYARAN DEPOSIT PASIEN'));

        if (isset($request['nocm'])) {
            $datas = $datas->where('pas.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['noregis'])) {
            $datas = $datas->where('pd.noregistrasi', 'ilike', '%' . $request['noregis'] . '%');
        }
        if (isset($request['nosbm'])) {
            $datas = $datas->where('sbm.nosbm', 'ilike', '%' . $request['nosbm'] . '%');
        }
        $datas = $datas->get();

        return $this->respond($datas);

    }


    // Detail Tagihan
    public function detailTagihanPasien(Request $request)
    {

        $kdProfile =  $this->kdProfile;
        $norec_pd = $request['norec_pd'];
        $dataStrukPelayanan = DB::table('strukpelayanan_t as sp')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'r.id as ruanganId',
                'dept.id as departmentId'
            )
            ->where('sp.kdprofile', $kdProfile)
            ->where('sp.norec', $norec_pd)
            ->first();
        // $strukPelayanan = StrukPelayanan::where('norec', $norec_pd)->where('kdprofile', $kdProfile)->first();
        return $this->respond($dataStrukPelayanan);
     
    }

    public function detailBayaran(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $norec_pd = $request['norec_pd'];
        $strukPelayanan = StrukPelayanan::where('norec', $norec_pd)->where('kdprofile', $kdProfile)->first();
        if ($strukPelayanan) {
            //return notfound
        }
        $pelayanan_pasien = $strukPelayanan->pelayanan_pasien;
        $deposit = 0;
        $detailTagihan = array();

        foreach ($pelayanan_pasien as $value) {
            $harga = ($value->hargajual == null) ? 0 : $value->hargajual;
            $diskon = ($value->hargadiscount == null) ? 0 : $value->hargadiscount;
            if ($value->nilainormal == -1) {
                $deposit += $harga;
            } else {
                $detailTagihan[] = array(
                    'namaLayanan'  => $value->produk->namaproduk,
                    "ruangan" => @$value->antrian_pasien_diperiksa->ruangan->reportdisplay,
                    'jumlah'  => $value->jumlah,
                    'harga'  => $harga,
                    'diskon'  => $diskon,
                    'total'  => ($harga - $diskon) * $value->jumlah,
                );
            }
        }

        $noregistasi = $strukPelayanan->pasien_daftar->noregistrasi;
        $result = array(
            "noRegistrasi"  => $strukPelayanan->pasien_daftar->noregistrasi,
            "noCm"  => $strukPelayanan->pasien_daftar->pasien->nocm,
            "namaPasien"  => $strukPelayanan->pasien_daftar->pasien->namapasien,
            "jenisPenjamin"  => $strukPelayanan->pasien_daftar->kelompok_pasien->kelompokpasien,
            "jenisKelamin"  => $strukPelayanan->pasien_daftar->pasien->jenis_kelamin->jeniskelamin,
            "umur"  => $strukPelayanan->pasien_daftar->pasien->Umur,
            "totalDeposit"  => $this->getDepositPasien($noregistasi), // $deposit,
            "jumlahBayar"  => $strukPelayanan->totalharusdibayar, //+ $this->getDepositPasien($noregistasi),
            "totalPenjamin" => ($strukPelayanan->totalprekanan == null) ? 0 : $strukPelayanan->totalprekanan,
            "detailTagihan"  => $detailTagihan,

        );
        return $this->respond($result, "Detail Tagihan Pasien");
    }
}
