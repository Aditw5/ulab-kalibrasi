<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\LogAcc;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukPelayananPenjaminDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DaftarPasienPulangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function daftarPasienPulang(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(
                'pd.norec AS norec_pd',
                'pd.tglregistrasi',
                'p.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'p.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'pd.tglmeninggal',
                'p.nosuratkematian',
                'pd.objectkelompokpasienlastfk',
                'dept.id as deptid',
                'pd.tglclosing',
                'pd.objectruanganlastfk',
                'pd.objectkelasfk',
                'p.tgllahir',
                'rek.namarekanan',
                'pa.nosep as nosep',
                'pa.norec as norec_pa',
                'pa.objectasuransipasienfk',
                'pa.ppkrujukan',
                'pa.objectdiagnosafk as iddiagnosabpjs',
                'pg.namalengkap AS dokter'                               
            )
    
            ->where('pd.statusenabled', true)
            ->whereNotNull('pd.tglpulang');

        $filter = $request->all();
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglpulang', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $data = $data->where('pd.tglpulang', '<=', $tgl);
        }

        if (isset($filter['instalasiId']) && $filter['instalasiId'] != "" && $filter['instalasiId'] != "undefined") {
            $data = $data->where('dept.id', '=', $filter['instalasiId']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "" && $filter['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $filter['ruanganId']);
        }

        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }

        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noReg'] . '%');
        }

        if (isset($filter['noRm']) && $filter['noRm'] != "" && $filter['noRm'] != "undefined") {
            $data = $data->where('p.nocm', 'ilike', '%' . $filter['noRm'] . '%');
        }
        if (isset($filter['kelompokPasienId']) && $filter['kelompokPasienId'] != "" && $filter['kelompokPasienId'] != "undefined") {
            $data = $data->where('kp.id', '=', $filter['kelompokPasienId']);
        }

        if (isset($filter['status']) && $filter['status'] != "" && $filter['status'] != "undefined") {
            if ($filter['status'] == 'Belum Verifikasi') {
                $data = $data->whereNull('pd.nostruklastfk')->whereNull('pd.nosbmlastfk');
            } elseif ($filter['status'] == 'Verifikasi') {
                $data = $data->whereNotNull('pd.nostruklastfk')->whereNull('pd.nosbmlastfk');
            } elseif ($filter['status'] == 'Lunas') {
                $data = $data->whereNotNull('pd.nostruklastfk')->whereNotNull('pd.nosbmlastfk');;
            }
        }
        if (isset($filter['jmlRows']) && $filter['jmlRows'] != "" && $filter['jmlRows'] != "undefined") {
            $data = $data->take($filter['jmlRows']);
        }

        $data = $data->get();


        $result = array();
        foreach ($data as $pasienD) {
            $status = "-";

            if ($pasienD->nostruklastfk == null && $pasienD->nosbmlastfk == null) {
                $status = "Belum Verifikasi";
            } elseif ($pasienD->nostruklastfk != null && $pasienD->nosbmlastfk == null) {
                $status = "Verifikasi";
            } elseif ($pasienD->nostruklastfk != null && $pasienD->nosbmlastfk != null) {
                $status = '-'; //"Lunas";
            }

            $result[] = array(
                'tanggalMasuk'  => $pasienD->tglregistrasi,
                'noCm'  => $pasienD->nocm,
                'noRegistrasi'  => $pasienD->noregistrasi,
                'namaRuangan'  => $pasienD->namaruangan,
                'namaPasien'  => $pasienD->namapasien,
                'jenisAsuransi'  => $pasienD->kelompokpasien,
                'tanggalPulang' => $pasienD->tglpulang,
                'tglmeninggal' =>  $pasienD->tglmeninggal,
                'norec_pd' => $pasienD->norec_pd,
                'status'    =>  $status,
                'deptid' => $pasienD->deptid,
                'tglclosing' => $pasienD->tglclosing,
                'nosuratkematian' => $pasienD->nosuratkematian,
                'kelasid' => $pasienD->objectkelasfk,
                'ruanganid' => $pasienD->objectruanganlastfk,
                'statuspasien' => $pasienD->statuspasien,

                'tgllahir' => $pasienD->tgllahir,
                'namarekanan' => $pasienD->namarekanan,
                'objectkelompokpasienlastfk' => $pasienD->objectkelompokpasienlastfk,
                'nosep' => $pasienD->nosep,
                'norec_pa' => $pasienD->norec_pa,
                'iddiagnosabpjs' => $pasienD->iddiagnosabpjs,
                'dokter' => $pasienD->dokter
            );
        }
        return $this->respond($result);
    }

    protected function getProdukIdDeposit()
    {
        $set = SettingDataFixed::where('namafield', 'idProdukDeposit')->first();
        $this->id = ($set) ? (int)$set->nilaifield : null;
        return $this->id;
    }

    public function verifikasiTagihan(Request $request)
    {
        $norec_pd = $request['norec_pd'];
        $kdProfile =  $this->kdProfile;
        $idProfile = (int) $kdProfile;
        $pelayanan = DB::select(
            DB::raw("select pd.objectruanganlastfk,pd.nostruklastfk,ps.id as psid,ps.nocm,
            ps.namapasien,pd.tglpulang,kps.kelompokpasien,kl.namakelas,
            pd.objectruanganlastfk,ru.objectdepartemenfk,
            pd.noregistrasi,pp.*,ps.email ,ps.nohp,pd.norec as norec_pd
            from pasiendaftar_t pd
            left JOIN antrianpasiendiperiksa_t apd on apd.noregistrasifk=pd.norec
            left JOIN pelayananpasien_t pp on pp.noregistrasifk=apd.norec
            left JOIN pasien_m ps on ps.id=pd.nocmfk
            left JOIN kelas_m kl on kl.id=pd.objectkelasfk
            left JOIN kelompokpasien_m kps on kps.id=pd.objectkelompokpasienlastfk
            left JOIN ruangan_m ru on ru.id=pd.objectruanganlastfk
            where pd.kdprofile = :kdprofile and pd.norec=:norec and pp.strukfk is null;"),
            array(
                'norec' => $norec_pd,
                'kdprofile' => $idProfile,
            )
        );

        $pelayanantidakterklaim = DB::select(
            DB::raw("select pd.objectruanganlastfk,pd.nostruklastfk,ps.id as psid,ps.nocm,
            ps.namapasien,pd.tglpulang,kps.kelompokpasien,kl.namakelas,
            pd.objectruanganlastfk,ru.objectdepartemenfk,
            pd.noregistrasi,pp.* from pasiendaftar_t pd
            left JOIN antrianpasiendiperiksa_t apd on apd.noregistrasifk=pd.norec
            left JOIN pelayananpasientidakterklaim_t pp on pp.noregistrasifk=apd.norec
            left JOIN pasien_m ps on ps.id=pd.nocmfk
            left JOIN kelas_m kl on kl.id=pd.objectkelasfk
            left JOIN kelompokpasien_m kps on kps.id=pd.objectkelompokpasienlastfk
            left JOIN ruangan_m ru on ru.id=pd.objectruanganlastfk
            where pd.kdprofile = :kdprofile and pd.norec=:norec and pp.strukfk is null;"),
            array(
                'norec' => $norec_pd,
                'kdprofile' => $idProfile,
            )
        );

        $totalBilling = 0;
        $totalKlaim = 0;
        $totalDeposit = 0;
        $totaltakterklaim = 0;

        foreach ($pelayanantidakterklaim as $values) {
            $totaltakterklaim = $totaltakterklaim + (($values->hargajual - $values->hargadiscount) * $values->jumlah) + $values->jasa;
        }

        foreach ($pelayanan as $value) {
            if ($value->produkfk == $this->getProdukIdDeposit()) {
                $totalDeposit = $totalDeposit + $value->hargajual;
            } else {

                $value->jasa = (float) $value->jasa;
                $value->jumlah = (float)$value->jumlah;
                $value->hargadiscount =  (float) $value->hargadiscount;
                $value->hargajual = (float)$value->hargajual;
                $totalBilling = (($value->hargajual - $value->hargadiscount) * $value->jumlah) + $value->jasa;
            }
        }

        $totalBilling = $totalBilling;
        $pelayanan = $pelayanan[0];
        $isRawatInap = false;
        if ($pelayanan->objectruanganlastfk != null) {
            if ((int)$pelayanan->objectdepartemenfk == 16) {
                $isRawatInap = true;
            }
        }

        $totalDeposit = $totalDeposit;
        $totalKlaim = 0;

        $nohp = $pelayanan->nohp;
        if ($nohp == null || strlen($nohp) <= 8) {
            $nohp = '00000000000';
        }
        $result = array(
            'pasienID' => $pelayanan->psid,
            'noCm' => $pelayanan->nocm,
            'noRegistrasi' => $pelayanan->noregistrasi,
            'namaPasien' => $pelayanan->namapasien,
            'tglPulang' => $pelayanan->tglpulang,
            'jenisPasien' => $pelayanan->kelompokpasien,
            'kelasRawat' => $pelayanan->namakelas,
            'noAsuransi' => '-',
            'kelasPenjamin' => '-',
            'billing' => $totalBilling,
            'penjamin' => '', //$penjamin=$this->getPenjamin($pelayanan)->namarekanan,
            'deposit' => $totalDeposit,
            'totalKlaim' => $totalKlaim,
            'jumlahBayar' => $totalBilling - $totalDeposit - $totalKlaim,
            'jumlahBayarNew' =>  $totalBilling - $totalDeposit - $totalKlaim - $totaltakterklaim, //jumlah bayar dengan tindakan yang tidak d klaim
            'jumlahPiutang' => 0,
            'needDokument' => true,
            'dokuments' => [],
            'totaltakterklaim' => $totaltakterklaim,
            'isRawatInap' => $isRawatInap,
            'email' => $pelayanan->email != null ? $pelayanan->email : '',
            'nohp' => $nohp,
            'norec_pd' => $pelayanan->norec_pd,

        );
        return $this->respond($result);
    }

    // Detail Tagihan Verifikasi

    public function detailTagihanVerifikasi(Request $request)
    {
        $norec_pd = $request['norec_pd'];
        $dataRuangan = DB::table('pasiendaftar_t as pd')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select('ru.namaruangan as namaruangan')
            ->where('pd.norec', $norec_pd)
            ->first();
        $pelayanan = [];
        $pelayanan = DB::table('pasiendaftar_t as pd')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')

            ->select(
                'pp.norec',
                'pp.tglpelayanan',
                'pp.rke',
                'pr.id as prid',
                'pr.namaproduk',
                'pp.jumlah',
                'kl.id as klid',
                'kl.namakelas',
                'ru.id as ruid',
                'ru.namaruangan',
                'pp.produkfk',
                'pp.hargajual',
                'pp.hargadiscount',
                'sp.nostruk',
                'sp.tglstruk',
                'apd.norec as norec_apd',
                'sbm.nosbm',
                'sp.norec as norec_sp',
                'pp.jasa',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.norec as norec_pd',
                'pd.tglpulang',
                'pd.objectrekananfk as rekananid',
                'pp.jasa',
                'sp.totalharusdibayar',
                'sp.totalprekanan',
                'sp.totalbiayatambahan',
                'pd.kdprofile',
                'pp.aturanpakai',
                'pp.iscito',
                'pd.statuspasien',
                'pp.isparamedis'
            )

            ->where('pd.norec', $norec_pd)
            ->orderBy('pp.tglpelayanan');

        $pelayanan = $pelayanan->get();


        if (count($pelayanan) > 0) {

            $totalBilling = 0;
            $norecAPD = '';
            $norecSP = '';
            $details = array();
            $dibayar = 0;
            $diverif = 0;
            foreach ($pelayanan as $value) {
                if ($value->produkfk == $this->getProdukIdDeposit()) {
                    continue;
                }
                if ($value->namaproduk == null) {
                    continue;
                }
                $jasa = 0;
                if (isset($value->jasa) && $value->jasa != "" && $value->jasa != "undefined") {
                    $jasa = $value->jasa;
                }
                $kmpn = [];

                $harga = (float)$value->hargajual;
                $diskon = (float)$value->hargadiscount;
                $detail = array(
                    'norec' => $value->norec,
                    'tglPelayanan' => $value->tglpelayanan,
                    'namaPelayanan' => $value->namaproduk,
                    //                    'dokter' => $NamaDokter,
                    'jumlah' => $value->jumlah,
                    'kelasTindakan' => @$value->namakelas,
                    'ruanganTindakan' => @$value->namaruangan,
                    'harga' => $harga,
                    'diskon' => $diskon,
                    'total' => (($harga - $diskon) * $value->jumlah) + $jasa,
                    'jppid' => '',
                    'jenispetugaspe' => '',
                    'strukfk' => $value->nostruk . ' / ' . $value->nosbm,
                    'sbmfk' => $value->nosbm,
                    'pgid' => '',
                    'ruid' => $value->ruid,
                    'prid' => $value->prid,
                    'klid' => $value->klid,
                    'norec_apd' => $value->norec_apd,
                    'norec_pd' => $value->norec_pd,
                    'norec_sp' => $value->norec_sp,
                    'komponen' => $kmpn,
                    'jasa' => $jasa,
                    'aturanpakai' => $value->aturanpakai,
                    'iscito' => $value->iscito,
                    'isparamedis' => $value->isparamedis
                );
                $details[] = $detail;
            }
        }

        $arrHsil = array(
            'details' => $details
        );
        return $this->respond($arrHsil);
    }

    protected function getKelompokPasienPerjanjian()
    {
        $set = SettingDataFixed::where('namafield', 'idJenisPasienPerjanjian')->first();
        $this->id = ($set) ? (int)$set->nilaifield : null;
        return $this->id;
    }



  
}


