<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class DashboardKasirCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function listTagihanPasien(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->JOIN('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as r', 'r.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dept', 'dept.id', '=', 'r.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as k', 'k.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'p.nocm',
                'p.namapasien',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'r.namaruangan',
                'kp.kelompokpasien',
                'sp.totalharusdibayar',
                'sp.totaliurbayar',
                'sp.norec',
                'sp.nostruk',
                'k.namakelas',
                'sp.tglstruk',
                'sp.totalprekanan',
                'sp.totalprekanan_pasien',
                'r.id as ruanganId',
                'dept.id as departmentId',
                'pd.tglpulang',
                DB::raw("case when sp.nosbmlastfk is not null
                or sp.nosbklastfk is not null
                then 'Lunas' else 'Belum Lunas' end
                as statusbayar ,
                sp.totalharusdibayar + ( COALESCE (sp.totaliurbayar, 0)  ) as  totalharusdibayar")
            )
            ->where('sp.kdprofile', $kdProfile)
            ->where('sp.statusenabled', true)
            ->whereNotNull('sp.totalharusdibayar')
            ->whereRaw("( sp.totalharusdibayar != 0 or sp.totaliurbayar != 0 )  ");

        $filter = false;

        if (isset($r['status']) && $r['status'] != "") {
            $filter = true;
            $data = $data->where('pd.statusbayar', '=', $r['status']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("pd.tglpulang::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("pd.tglpulang::date"), '<=', $r->sampai);
        }

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('p.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('p.nocm', 'ilike', $searchTerm)
                    ->orWhere('p.noidentitas', 'ilike', $searchTerm);
            });
        }

        $data =  $data->get();

        return $this->respond($data);
    }

    // Tagihan Non Layanan

    public function TagihanNonLayanan(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $filter = $request->all();
        $list = explode(',', $this->settingFix('kdTransaksiNonLayanan', $kdProfile));
        $KdList = [];
        foreach ($list as $item) {
            $KdList[] =  (int)$item;
        }
        $datakelompokuser = DB::table('loginuser_s as lu')
            ->select('lu.objectkelompokuserfk')
            ->where('lu.kdprofile', $kdProfile)
            ->where('lu.id', '=', $filter['userData']['id'])
            ->get();

        if ($datakelompokuser[0]->objectkelompokuserfk == 58) {
            $dataNonLayanan = DB::table('strukpelayanan_t as sp')
                ->join('kelompoktransaksi_m as kt', 'sp.objectkelompoktransaksifk', '=', 'kt.id')
                ->select(
                    'sp.norec',
                    'sp.tglstruk',
                    'sp.namapasien_klien',
                    'kt.reportdisplay as jenistagihan',
                    'keteranganlainnya',
                    'sp.nosbklastfk',
                    'sp.nosbmlastfk',
                    'kt.id as jenisTagihanId',
                    DB::raw("CAST(sp.totalharusdibayar) AS totalharusdibayar AS FLOAT")
                )
                ->where('sp.kdprofile', $kdProfile)
                ->whereNotNull('sp.totalharusdibayar')
                ->whereIn('sp.objectkelompoktransaksifk', $KdList)
                ->where('kt.id', '=', 13);
        } else {
            $dataNonLayanan = DB::table('strukpelayanan_t as sp')
                ->leftjoin('kelompoktransaksi_m as kt', 'sp.objectkelompoktransaksifk', '=', 'kt.id')
                ->select(
                    'sp.norec',
                    'sp.tglstruk',
                    'sp.namapasien_klien',
                    'kt.reportdisplay as jenistagihan',
                    'keteranganlainnya',
                    'sp.nosbklastfk',
                    'sp.nosbmlastfk',
                    'kt.id as jenisTagihanId',
                    DB::raw("CAST(sp.totalharusdibayar AS FLOAT) AS totalharusdibayar")
                )
                ->where('sp.kdprofile', $kdProfile)
                ->whereNotNull('sp.totalharusdibayar')
                ->whereIn('sp.objectkelompoktransaksifk', $KdList);
        }

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataNonLayanan = $dataNonLayanan->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir']; //." 23:59:59";
            $dataNonLayanan = $dataNonLayanan->where('sp.tglstruk', '<=', $tgl);
        }
        //
        if (isset($filter['jenisTagihanId']) && $filter['jenisTagihanId'] != "") {
            $dataNonLayanan = $dataNonLayanan->where('kt.id', '=', $filter['jenisTagihanId']);
        }

        if (isset($filter['namaPelanggan']) && $filter['namaPelanggan'] != "") {
            $dataNonLayanan = $dataNonLayanan->where('sp.namapasien_klien', 'ilike', '%' . $filter['namaPelanggan'] . '%');
        }
        //
        if (isset($filter['status']) && $filter['status'] != "") {
            if ($filter['status'] == 'Lunas') {
                $dataNonLayanan = $dataNonLayanan->whereNotNull('sp.nosbmlastfk');
            } else {
                $dataNonLayanan = $dataNonLayanan->whereNull('sp.nosbmlastfk');
            }
        }
        if (isset($filter['statusK']) && $filter['statusK'] != "") {
            if ($filter['statusK'] == 'Lunas') {
                $dataNonLayanan = $dataNonLayanan->whereNotNull('sp.nosbklastfk');
            } else {
                $dataNonLayanan = $dataNonLayanan->whereNull('sp.nosbklastfk');
            }
        }



        $dataNonLayanan = $dataNonLayanan->where('sp.statusenabled', '=', true);
        $dataNonLayanan = $dataNonLayanan->get();
        $result = array();
        foreach ($dataNonLayanan as $item) {
            $statusBayar = "Belum Bayar";
            if ($item->nosbmlastfk != null || $item->nosbklastfk != null) {
                $statusBayar = "Lunas";
            }
            $result[] = array(
                'noRec' => $item->norec,
                'tglTransaksi' => $item->tglstruk,
                'namaPelanggan' => $item->namapasien_klien,
                'jenisTagihan' => $item->jenistagihan,
                'total' => $item->totalharusdibayar,
                'keterangan' => $item->keteranganlainnya,
                'jenisTagihanId' => $item->jenisTagihanId,
                'statusBayar' => $statusBayar

            );
        }

        return $this->respond($result);
    }

    //Daftar Piutang

    public function daftarPiutang(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $filter = $request->all();
        $dataPiutang = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pasien_m as p', 'p.id', '=', 'sp.nocmfk')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftjoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(
                'kp.kelompokpasien',
                'spp.norec',
                'pd.tglpulang as tglstruk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                'ru.namaruangan',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'spp.totalsudahdibayar',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'rkn.namarekanan',
                'php.noposting',
                'spt.statusenabled',
                'pd.norec as norec_pd',
                'php.statusenabled as sttts'
            )
            ->where('spp.kdprofile', $kdProfile)
            ->whereNotNull('spp.noverifikasi')
            ->where('sp.statusenabled', true);

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataPiutang = $dataPiutang->where('pd.tglpulang', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $dataPiutang = $dataPiutang->where('pd.tglpulang', '<=', $tgl);
        }

        if (isset($filter['kelompokpasienfk']) && $filter['kelompokpasienfk'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectkelompokpasienlastfk', '=', $filter['kelompokpasienfk']);
        }

        if (isset($filter['penjaminID']) && $filter['penjaminID'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectkelompokpasienlastfk', '=', $filter['penjaminID']);
        }
        if (isset($filter['rekananfk']) && $filter['rekananfk'] != "") {
            $dataPiutang = $dataPiutang->where('pd.objectrekananfk', '=', $filter['rekananfk']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "") {
            $dataPiutang = $dataPiutang->where('ru.id', '=', $filter['ruanganId']);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $dataPiutang = $dataPiutang->where('p.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        if (isset($filter['noregistrasi']) && $filter['noregistrasi'] != "") {
            $dataPiutang = $dataPiutang->where('pd.noregistrasi', 'ilike', '%' . $filter['noregistrasi'] . '');
        }
        if (isset($filter['nocm']) && $filter['nocm'] != "") {
            $dataPiutang = $dataPiutang->where('p.nocm', '=', $filter['nocm']);
        }
        if (isset($filter['jmlRows']) && $filter['jmlRows'] != "" && $filter['jmlRows'] != "undefined") {
            $dataPiutang = $dataPiutang->take($filter['jmlRows']);
        }
        $dataPiutang = $dataPiutang->orderBy('pd.tglpulang');
        $dataPiutang = $dataPiutang->get();
        $result = array();
        $no = 1;
        foreach ($dataPiutang as $item) {
            if ($item->statusenabled ==  1 || is_null($item->statusenabled)) {
                if ($item->sttts == 1 || is_null($item->sttts)) {
                    if ($item->totalppenjamin > $item->totalsudahdibayar) {
                        if (!isset($item->noposting)) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Collecting';
                        }
                    } else {
                        $status = 'Lunas';
                    }

                    $result[] = array(
                        'no' => $no++,
                        'noRec' => $item->norec,
                        'tglTransaksi' => $item->tglstruk,
                        'noRegistrasi' => $item->noregistrasi,
                        'namaPasien' => $item->namapasien,
                        'ruangan' => $item->namaruangan,
                        'kelasRawat' => $item->kelompokpasien,
                        'jenisPasien' => $item->kelompokpasien,
                        'umur' => $this->hitungUmur($item->tglstruk),
                        'kelasPenjamin' => "-",
                        'totalBilling' => $item->totalbiaya,
                        'totalKlaim' => $item->totalppenjamin,
                        'totalBayar' => $item->totalsudahdibayar,
                        'rekanan' => $item->namarekanan,
                        'status' => $status,
                        'norec_pd' => $item->norec_pd,
                        'noposting' => $item->noposting,
                        'stts' => $item->statusenabled,
                    );
                }
            }
        }
        return $this->respond($result);
    }
    public function daftarVerif(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->select(
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                DB::raw("
                CASE WHEN pd.nostruklastfk IS NOT NULL AND pd.nosbmlastfk IS NOT NULL THEN '-'
                WHEN pd.nostruklastfk IS NOT NULL AND pd.nosbmlastfk IS NULL THEN 'Verifikasi'
                ELSE 'Belum Verifikasi' END AS statusverif
            ")
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereNull('pd.tglpulang');


        $data = $data->groupBy(
            'pd.tglregistrasi',
            'ps.nocm',
            'pd.noregistrasi',
            'ru.namaruangan',
            'ps.namapasien',
            'kp.kelompokpasien',
            'pd.tglpulang',
            'pd.statuspasien',
            'pd.nostruklastfk',
            'pd.nosbmlastfk'
        );

        $data = $data->get();

        $result = array();
        foreach ($data as $pasienD) {
            $result[] = array(
                'tanggalMasuk' => $pasienD->tglregistrasi,
                'noCm' => $pasienD->nocm,
                'noRegistrasi' => $pasienD->noregistrasi,
                'namaRuangan' => $pasienD->namaruangan,
                'namaPasien' => $pasienD->namapasien,
                'jenisAsuransi' => $pasienD->kelompokpasien,
                'tanggalPulang' => $pasienD->tglpulang,
                'statusverif' => $pasienD->statusverif,
                'status' => $pasienD->statuspasien
            );
        }
        return $this->respond($result);
    }

    public function daftarPasienPulang(Request $r)
    {
        $totalSp = DB::table('strukpelayanan_t')
            ->select(
                'noregistrasi',
                DB::raw('SUM(totalharusdibayar) as totalverif'),
                DB::raw('SUM(totaliurbayar) as totaliurbayar'),
                DB::raw('SUM(totalprekanan) as totalklaim'),
                DB::raw('SUM(totalprekanan_pasien) as totalklaim_pasien')
            )
            ->where('statusenabled', true)
            ->groupBy('noregistrasi');

            $totalSpp = DB::table('strukpelayanan_t as sp')
            ->join('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->select(
                'sp.noregistrasi',
                DB::raw('SUM(spp.totalsudahdibayar) as totalsudahdibayar')
            )
            ->groupBy('sp.noregistrasi');

            $totalSbm = DB::table('strukpelayanan_t as sp')
    ->join('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
    ->where('sbm.statusenabled', true)
    ->select(
        'sp.noregistrasi',
        DB::raw('SUM(sbm.totaldibayar) as totaldibayar')
    )
    ->groupBy('sp.noregistrasi');
        $data = DB::table('pasiendaftar_t as pd')
    ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
    ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
    ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
    ->leftJoin('pelayananpasien_t as pp', function ($q) {
        $q->on('pp.noregistrasi', '=', 'pd.noregistrasi')->where('pp.statusenabled', true);
    })
    ->leftJoinSub($totalSp, 'sp', fn($join) => $join->on('sp.noregistrasi', '=', 'pd.noregistrasi'))
    ->leftJoinSub($totalSpp, 'spp', fn($join) => $join->on('spp.noregistrasi', '=', 'pd.noregistrasi'))
    ->leftJoinSub($totalSbm, 'sbm', fn($join) => $join->on('sbm.noregistrasi', '=', 'pd.noregistrasi'))
    ->where('pd.statusenabled', true)
    ->where('pd.kdprofile', $this->kdProfile)
    ->whereNotNull('pd.tglpulang')
    ->groupBy(
        'pd.norec',
        'pd.nocmfk',
        'pd.tglregistrasi',
        'pd.statusbayar',
        'ps.nocm',
        'pd.noregistrasi',
        'pd.isclosing',
        'ru.namaruangan',
        'ps.namapasien',
        'kp.kelompokpasien',
        'pd.objectkelompokpasienlastfk',
        'pd.tglpulang',
        'ru.objectdepartemenfk',
        'spp.totalsudahdibayar',
        'sbm.totaldibayar',
        'sp.totalverif',
        'sp.totaliurbayar',
        'sp.totalklaim',
        'sp.totalklaim_pasien'
    )
    ->select(
        'pd.norec as norec_pd',
        'pd.nocmfk',
        DB::raw("COALESCE(spp.totalsudahdibayar, 0) as totalsudahdibayar"),
        'pd.statusbayar',
        'ps.nocm',
        'pd.noregistrasi',
        'pd.isclosing',
        'ru.namaruangan',
        'ps.namapasien',
        'kp.kelompokpasien',
        'pd.objectkelompokpasienlastfk',
        'pd.tglpulang',
        'ru.objectdepartemenfk',
        DB::raw("COALESCE(ROUND(SUM((COALESCE(pp.hargasatuan,0)-COALESCE(pp.hargadiscount,0))*pp.jumlah + COALESCE(pp.jasa,0))),0) as totaltagihan"),
        DB::raw("COALESCE(sp.totalverif,0) as totalverif"),
        DB::raw("COALESCE(sbm.totaldibayar,0) as totalbayar"),
        DB::raw("COALESCE(sp.totaliurbayar,0) as totaliurbayar"),
        DB::raw("COALESCE(sp.totalklaim,0) as totalklaim"),
        DB::raw("COALESCE(sp.totalklaim_pasien,0) as totalklaim_pasien")
    );



        // Filter opsional
        if (!empty($r['dari'])) {
            $data->whereBetween('pd.tglpulang', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        }
        if (!empty($r['ruanganfk'])) {
            $data->where('pd.objectruanganlastfk', $r['ruanganfk']);
        }
        if (!empty($r['namapasien'])) {
            $data->where(function ($q) use ($r) {
                $q->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%')
                    ->orWhere('ps.nocm', 'ilike', '%' . $r['namapasien'] . '%')
                    ->orWhere('pd.noregistrasi', 'ilike', '%' . $r['namapasien'] . '%');
            });
        }
        if (!empty($r['kelompokpasienfk'])) {
            $data->where('pd.objectkelompokpasienlastfk', $r['kelompokpasienfk']);
        }
        if (!empty($r['rows'])) {
            $data->limit($r['rows']);
        }

        $data = $data->get();

        // return $result;

        $dep = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));

        // return $data;
        foreach ($data as $d) {
            $d->isallowbatalplg = true;
            if (in_array($d->objectdepartemenfk, $dep)) {
                $d->isallowbatalplg = false;
            }
            $d->totalbayar = $d->totalbayar - $d->totaliurbayar;
            if ($d->objectkelompokpasienlastfk == 1) {
                $d->sisa = $d->totaltagihan - $d->totalbayar - $d->totalsudahdibayar - $d->totalklaim_pasien;
            } else {
                $d->sisa = $d->totaltagihan - ($d->totalbayar - $d->totaliurbayar) - $d->totalklaim - $d->totalklaim_pasien;
            }

            $d->sisa = round($d->sisa);

            if ((float)$d->totaliurbayar == 0 && $d->totalverif == 0 && $d->totalbayar == 0 && $d->totalklaim == 0) {
                $d->statusbayar = 'Belum Verifikasi';
                $d->color_statusbayar = 'danger';
            } elseif ($d->sisa > 0) {
                $d->statusbayar = 'Masih ada tagihan';
                $d->color_statusbayar = 'info';
            } elseif (((float)$d->totalklaim - $d->totalsudahdibayar) > 0) {
                $d->statusbayar = 'Terdapat Piutang';
                $d->color_statusbayar = 'warning';
            } elseif (((float)$d->totalklaim_pasien - $d->totalsudahdibayar) > 0) {
                $d->statusbayar = 'Terdapat Piutang Pasien';
                $d->color_statusbayar = 'warning';
            } elseif ($d->totalbayar == 0) {
                $d->statusbayar = 'Belum Lunas';
                $d->color_statusbayar = 'warning';
            } elseif ($d->sisa == 0) {
                $d->statusbayar = 'Lunas';
                $d->color_statusbayar = 'success';
            }
        }

        // return $this->respond(['norecSpp' => $norecSpp, 'data' => $data]);

        return $this->respond($data);
    }
    // public function daftarPasienPulang(Request $r)
    // {
    //     $totalTagihan = DB::table('pelayananpasien_t as pp')
    //         ->where('pp.statusenabled', true)
    //         ->groupBy('pp.noregistrasi')
    //         ->select(DB::raw("pp.noregistrasi,
    //         sum(((
    //         COALESCE (pp.hargasatuan, 0)
    //         - COALESCE (pp.hargadiscount, 0)) * pp.jumlah)
    //         + COALESCE (pp.jasa, 0)) as totaltagihan"));
    //     $bayar = DB::table('strukpelayanan_t as sp')
    //         ->leftjoin('strukbuktipenerimaan_t as sbm', function ($j) {
    //             $j->on('sbm.nostrukfk', '=', 'sp.norec')->where('sbm.statusenabled', true);
    //         })
    //         ->where('sp.statusenabled', true)
    //         ->groupBy('sp.noregistrasi')
    //         ->select(DB::raw("
    //         sum( sp.totalharusdibayar ) as totalverif,
    //         sp.noregistrasi,
    //         sum( sbm.totaldibayar ) as totalbayar,
    //         sum( sp.totaliurbayar ) as totaliurbayar
    //         "));
    //     $klaim = DB::table('strukpelayanan_t as sp')
    //         ->where('sp.statusenabled', true)
    //         ->groupBy('sp.noregistrasi')
    //         ->select(DB::raw("
    //             sp.noregistrasi,
    //             sum(sp.totalprekanan) as totalklaim
    //             "));
    //     $klaimPasien = DB::table('strukpelayanan_t as sp')
    //         ->where('sp.statusenabled', true)
    //         ->groupBy('sp.noregistrasi')
    //         ->select(DB::raw("
    //             sp.noregistrasi,
    //             sum(sp.totalprekanan_pasien) as totalklaim_pasien
    //             "));

    //     $data = DB::table('pasiendaftar_t as pd')
    //         ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
    //         // ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
    //         // ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasi', '=', 'pd.noregistrasi')
    //         ->leftJOIN('strukpelayanan_t as sp', 'sp.noregistrasi', '=', 'pd.noregistrasi')
    //         ->leftJoin('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
    //         ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
    //         ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
    //         ->leftJoinSub($bayar, 'byr', 'pd.noregistrasi', '=', 'byr.noregistrasi')
    //         ->leftJoinSub($klaim, 'kla', 'pd.noregistrasi', '=', 'kla.noregistrasi')
    //         ->leftJoinSub($klaimPasien, 'klap', 'pd.noregistrasi', '=', 'klap.noregistrasi')
    //         ->leftJoinSub($totalTagihan, 'bil', 'pd.noregistrasi', '=', 'bil.noregistrasi')
    //         ->groupBy(
    //             'pd.norec',
    //             'pd.nocmfk',
    //             'pd.tglregistrasi',
    //             'pd.statusbayar',
    //             'ps.nocm',
    //             'pd.noregistrasi',
    //             'pd.isclosing',
    //             'ru.namaruangan',
    //             'ps.namapasien',
    //             'kp.kelompokpasien',
    //             'pd.objectkelompokpasienlastfk',
    //             'pd.tglpulang',
    //             'pd.statuspasien',
    //             'ru.objectdepartemenfk',
    //             'bil.totaltagihan',
    //             'byr.totalverif',
    //             'byr.totalbayar',
    //             'kla.totalklaim',
    //             'klap.totalklaim_pasien',
    //             'byr.totaliurbayar',
    //         )
    //         ->select(
    //             'pd.norec as norec_pd',
    //             'pd.nocmfk',
    //             // DB::raw("STRING_AGG(DISTINCT spp.norec, ',') as norec_spp"),
    //             DB::raw("SUM(spp.totalsudahdibayar) as totalsudahdibayar"),
    //             DB::raw("SUM(DISTINCT spp.totalsudahdibayar) as totalsudahdibayar"),
    //             // 'pd.tglregistrasi',
    //             'pd.statusbayar',
    //             'ps.nocm',
    //             'pd.noregistrasi',
    //             'pd.isclosing',
    //             'ru.namaruangan',
    //             'ps.namapasien',
    //             'kp.kelompokpasien',
    //             'pd.objectkelompokpasienlastfk',
    //             'pd.tglpulang',
    //             // 'pd.statuspasien',
    //             'ru.objectdepartemenfk',
    //             DB::raw("COALESCE(ROUND(bil.totaltagihan), 0) as totaltagihan"),
    //             DB::raw("COALESCE(byr.totalverif, 0) as totalverif"),
    //             DB::raw("COALESCE(byr.totalbayar, 0) as totalbayar"),
    //             DB::raw("COALESCE(kla.totalklaim, 0) as totalklaim"),
    //             DB::raw("COALESCE(klap.totalklaim_pasien, 0) as totalklaim_pasien"),
    //             DB::raw("COALESCE(byr.totaliurbayar, 0) as totaliurbayar")
    //         )
    //         ->distinct()
    //         ->where('pd.statusenabled', true)
    //         // ->where('pp.statusenabled', true)
    //         ->where('pd.kdprofile', $this->kdProfile)
    //         ->whereNotNull('pd.tglpulang');

    //     if (isset($r['dari']) && $r['dari'] != '') {
    //         $data = $data->whereBetween('pd.tglpulang', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
    //     }
    //     if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
    //         $data = $data->where('pd.objectruanganlastfk', $r['ruanganfk']);
    //     }
    //     // if (isset($r['nocm']) && $r['nocm'] != '') {
    //     //     $data = $data->where('ps.nocm', $r['nocm']);
    //     // }
    //     if (isset($r['namapasien']) && $r['namapasien'] != '') {
    //         $data->where(function ($q) use ($r) {
    //             $q->where('ps.namapasien', 'ilike', '%' . $r->namapasien . '%')
    //               ->orWhere('ps.nocm', 'ilike', '%' . $r->namapasien . '%');
    //         });
    //         // $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
    //     }
    //     // if (isset($r['namapasien']) && $r['namapasien'] != '') {
    //     //     $data = $data->orWhere('ps.nocm', 'ilike', '%' . $r['namapasien'] . '%');
    //     // }
    //     // if (isset($r['noreg']) && $r['noreg'] != '') {
    //     //     $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
    //     // }
    //     if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
    //         $data = $data->where('pd.objectruanganlastfk', $r['ruanganfk']);
    //     }
    //     if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != '') {
    //         $data = $data->where('pd.objectkelompokpasienlastfk', $r['kelompokpasienfk']);
    //     }
    //     // if (isset($r['noreg']) && $r['noreg'] != '') {
    //     //     $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noreg'] . '%');
    //     // }
    //     if (isset($r['rows']) && $r['rows'] != '') {
    //         $data = $data->limit($r['rows']);
    //     }
    //     $data = $data->get();
    //     $dep = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));

    //     // return $data;
    //     foreach ($data as $d) {
    //         $d->isallowbatalplg = true;
    //         if (in_array($d->objectdepartemenfk, $dep)) {
    //             $d->isallowbatalplg = false;
    //         }
    //         $d->totalbayar = $d->totalbayar - $d->totaliurbayar;
    //         if ($d->objectkelompokpasienlastfk == 1) {
    //             $d->sisa = $d->totaltagihan - $d->totalbayar - $d->totalsudahdibayar - $d->totalklaim_pasien;
    //         } else {
    //             $d->sisa = $d->totaltagihan - ($d->totalbayar - $d->totaliurbayar) - $d->totalklaim - $d->totalklaim_pasien;
    //         }

    //         $d->sisa = round($d->sisa);

    //         if ((float)$d->totaliurbayar == 0 && $d->totalverif == 0 && $d->totalbayar == 0 && $d->totalklaim == 0) {
    //             $d->statusbayar = 'Belum Verifikasi';
    //             $d->color_statusbayar = 'danger';
    //         } elseif ($d->sisa > 0) {
    //             $d->statusbayar = 'Masih ada tagihan';
    //             $d->color_statusbayar = 'info';
    //         } elseif (((float)$d->totalklaim - $d->totalsudahdibayar) > 0) {
    //             $d->statusbayar = 'Terdapat Piutang';
    //             $d->color_statusbayar = 'warning';
    //         } elseif (((float)$d->totalklaim_pasien - $d->totalsudahdibayar) > 0) {
    //             $d->statusbayar = 'Terdapat Piutang Pasien';
    //             $d->color_statusbayar = 'warning';
    //         } elseif ($d->totalbayar == 0) {
    //             $d->statusbayar = 'Belum Lunas';
    //             $d->color_statusbayar = 'warning';
    //         } elseif ($d->sisa == 0) {
    //             $d->statusbayar = 'Lunas';
    //             $d->color_statusbayar = 'success';
    //         }
    //     }

    //     // return $this->respond(['norecSpp' => $norecSpp, 'data' => $data]);

    //     return $this->respond($data);
    // }
    public function countDashboardKasir(Request $r)
    {
        $kdProfile = $this->kdProfile;

        $regis  =  PasienDaftar::where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            $regis = $regis->whereBetween('tglpulang', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        }
        $regis = $regis->get();

        $bayar  =  StrukBuktiPenerimaan::where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            $bayar = $bayar->whereBetween('tglsbm', [$r['dari'] . ' 00:00', $r['sampai'] . ' 23:59']);
        }
        $bayar = $bayar->get();
        $res['c_total'] = count($regis);
        $res['c_lunas'] =  count($bayar);

        return $this->respond($res);
    }
    // public function detailVerifikasi(Request $r)
    // {
    //     $data = DB::table('strukpelayanan_t as sp')
    //         ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
    //         ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
    //         ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
    //         ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'sbm.objectpegawaipenerimafk')
    //         ->leftJoin('strukpelayananpenjamin_t as spp', 'spp.nostrukfk', '=', 'sp.norec')
    //         ->select(DB::raw("pd.noregistrasi,sp.norec,sp.nostruk,sp.tglstruk,pg.namalengkap as petugasverif,sbm.tglsbm,pd.norec as norec_pd,
    //                                 CASE WHEN sp.nosbmlastfk IS NULL THEN 'Belum Bayar' ELSE 'Lunas' END AS status,
    // 	                            CASE WHEN sp.nosbmlastfk IS NULL THEN NULL ELSE pg1.namalengkap END AS kasir,
    //                                 DB::raw("STRING_AGG(DISTINCT spp.norec, ',') as norec_spp"),
    //                                 pd.objectkelompokpasienlastfk,sp.nosbmlastfk,spp.noverifikasi,
    // 	                            CASE WHEN sp.totalprekanan IS NULL THEN 0 ELSE sp.totalprekanan END AS totalprekanan,
    //                                 CASE WHEN sp.totaldiscount IS NULL THEN 0 ELSE sp.totaldiscount END totaldiscount,
    //                                 (sp.totalharusdibayar + CASE WHEN sp.totalprekanan IS NULL THEN 0 ELSE sp.totalprekanan END +
    // 			                    CASE WHEN sp.totaldiscount IS NULL THEN 0 ELSE sp.totaldiscount END) AS totaltagihanverifikasi,
    //                                 sp.totalharusdibayar + ( COALESCE (sp.totaliurbayar, 0)  ) as  totalharusdibayar"))
    //         ->where('pd.kdprofile', $this->kdProfile)
    //         ->where('sp.statusenabled', true)
    //         ->where('pd.noregistrasi',  $r['noregistrasi'])
    //         ->where('sp.objectkelompoktransaksifk',   $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN'))
    //         ->get();

    //     return $this->respond($data);
    // }
    public function detailVerifikasi(Request $r)
    {
        $data = DB::table('strukpelayanan_t as sp')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftJoin('strukpelayananpenjamin_t as spp', 'spp.nostrukfk', '=', 'sp.norec')
            ->groupBy(
                'pd.noregistrasi',
                'sp.norec',
                'sp.nostruk',
                'sp.tglstruk',
                'pg.namalengkap',
                'pg1.namalengkap',
                'sbm.tglsbm',
                'pd.norec',
                'sp.totalprekanan',
                'sp.totaldiscount',
                'sp.totalharusdibayar',
                'pd.objectkelompokpasienlastfk',
                'sp.nosbmlastfk',
                // 'spp.noverifikasi',
            )
            ->select(
                'pd.noregistrasi',
                'sp.norec',
                'sp.nostruk',
                'sp.tglstruk',
                'pg.namalengkap as petugasverif',
                // 'sbm.tglsbm',
                DB::raw("TO_CHAR(sbm.tglsbm, 'YYYY-MM-DD') as tglsbm"),
                'pd.norec as norec_pd',
                DB::raw("CASE WHEN sp.nosbmlastfk IS NULL THEN 'Belum Bayar' ELSE 'Lunas' END AS status"),
                DB::raw("CASE WHEN sp.nosbmlastfk IS NULL THEN NULL ELSE pg1.namalengkap END AS kasir"),
                DB::raw("STRING_AGG(DISTINCT spp.norec, ',') as norec_piutang"),
                DB::raw("STRING_AGG(DISTINCT spp.noverifikasi, ',') as noverifikasi"),
                'pd.objectkelompokpasienlastfk',
                'sp.nosbmlastfk',
                DB::raw("SUM(DISTINCT spp.totalsudahdibayar) as totalsudahdibayar"),
                // 'spp.noverifikasi',
                DB::raw("COALESCE(sp.totalprekanan, 0) AS totalprekanan"),
                DB::raw("COALESCE(sp.totaldiscount, 0) AS totaldiscount"),
                DB::raw("sp.totalharusdibayar + COALESCE(sp.totaliurbayar, 0) + COALESCE(sp.totalprekanan, 0) + COALESCE(sp.totaldiscount, 0) AS totaltagihanverifikasi"),
                DB::raw("sp.totalharusdibayar + COALESCE(sp.totaliurbayar, 0) AS totalharusdibayar")
            )
            ->distinct()
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('VERIFIKASI TAGIHAN PASIEN'))
            ->get();

        return $this->respond($data);
    }

    public function batalVerifikasiTagihan(Request $r)
    {
        DB::beginTransaction();
        try {
            $sbm = StrukBuktiPenerimaan::where('nostrukfk', $r['norec_sp'])->first();
            $sbk = StrukBuktiPengeluaran::where('nostrukfk',  $r['norec_sp'])->first();
            if (!empty($sbm)) {
                $transMessage =  'Tagihan ini sudah di bayarkan';
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "result"  => null
                );
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            if (!empty($sbm)) {
                $sbk->statusenabled = false;
                $sbk->save();
            }

            PelayananPasien::where('strukfk', $r['norec_sp'])
                ->update([
                    'strukfk' => null,
                ]);
            PelayananPasienDetail::where('strukfk', $r['norec_sp'])
                ->update([
                    'strukfk' => null,
                ]);
            StrukPelayananPenjamin::where('nostrukfk', $r['norec_sp'])
                ->update([
                    'statusenabled' => false
                ]);

            PasienDaftar::where('norec', $r['norec_pd'])
                ->update([
                    'nostruklastfk' => null,
                    'statusbayar' => 'Belum Verifikasi',
                ]);

            StrukPelayanan::where('norec', $r['norec_sp'])
                ->update([
                    'statusenabled' => false,
                ]);


            $transStatus = true;
        } catch (Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage =  "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getDataComboKasir()
    {
        $res['ruanganFarmasi'] = Ruangan::select('namaruangan', 'id')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereIn('objectdepartemenfk', [$this->settingFix('idInstalasiFarmasi')])
            ->get();

        $res['kelompokpasien'] = KelompokPasien::mine()->get();

        return $this->respond($res);
    }
}
