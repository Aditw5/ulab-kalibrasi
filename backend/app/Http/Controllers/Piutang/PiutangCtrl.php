<?php

namespace App\Http\Controllers\Piutang;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PiutangCtrl extends Controller
{
    public function daftarPiutang(Request $request)
    {
        $filter = $request->all();
        $kdProfile = (int) $this->kdProfile;

        $dataPiutang = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pasien_m as p', 'p.id', '=', 'sp.nocmfk')
            ->join('pasiendaftar_t as pd', 'sp.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftjoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(DB::raw("
                kp.kelompokpasien,spp.norec,pd.tglpulang AS tglstruk,pd.noregistrasi,pd.tglregistrasi,p.nocm,pd.nosbmlastfk,
                p.namapasien,ru.namaruangan,spp.totalppenjamin,spp.totalharusdibayar,spp.totalsudahdibayar,
                spp.totalbiaya,spp.noverifikasi,rkn.namarekanan,php.noposting,spt.statusenabled,pd.norec AS norec_pd,
                php.statusenabled AS sttts,sp.totalharusdibayar AS totaltidakdiklaim,rkn.id as idrekanan
            "))
            ->where('spp.kdprofile', $kdProfile)
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
        $dataPiutang = $dataPiutang->get();
        $result = array();
        foreach ($dataPiutang as $item) {
            if ($item->statusenabled ==  1 || is_null($item->statusenabled)) {
                if ($item->sttts == 1 || is_null($item->sttts)) {
                    if ($item->nosbmlastfk == null && $item->totalppenjamin > $item->totalsudahdibayar) {
                        if (!isset($item->noposting)) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Collecting';
                        }
                    } elseif ($item->nosbmlastfk != null && $item->totalppenjamin > $item->totalsudahdibayar) {
                        $status = 'Piutang';
                    } else {
                        $status = 'Lunas';
                    }

                    $result[] = array(
                        'noRec' => $item->norec,
                        'tglTransaksi' => $item->tglstruk,
                        'noRegistrasi' => $item->noregistrasi,
                        'namaPasien' => $item->namapasien,
                        'ruangan' => $item->namaruangan,
                        'kelasRawat' => $item->kelompokpasien,
                        'jenisPasien' => $item->kelompokpasien,
                        'umur' => $this->getAge($item->tglstruk, date('Y-m-d H:i:s')),
                        'kelasPenjamin' => "-",
                        'totalBilling' => $item->totalbiaya,
                        'totalKlaim' => $item->totalppenjamin,
                        'totalBayar' => $item->totalsudahdibayar,
                        'totaltidakdiklaim' => $item->totaltidakdiklaim,
                        'sisautang' => (float)$item->totalppenjamin - $item->totalsudahdibayar,
                        'rekanan' => $item->namarekanan,
                        'status' => $status,
                        'norec_pd' => $item->norec_pd,
                        'noposting' => $item->noposting,
                        'stts' => $item->statusenabled,
                        'idrekanan' => $item->idrekanan
                    );
                }
            }
        }
        return $this->respond($result);
    }
    public function daftarPiutangNonLayanan(Request $request)
    {
        $filter         = $request->all();
        $kdProfile      = (int) $this->kdProfile;
        $dataPiutang    = DB::table('strukpelayananpenjamin_t as spp')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'sp.objectkelompokpasienfk')
            ->leftjoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftjoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(DB::raw("
                kp.kelompokpasien,spp.norec,sp.tglstruk,'-' AS noregistrasi,sp.tglstruk AS tglregistrasi,
                sp.nostruk_intern AS nocm,sp.namapasien_klien AS namapasien,'' AS namaruangan,spp.totalppenjamin,
                spp.totalharusdibayar,spp.totalsudahdibayar,spp.totalbiaya,spp.noverifikasi,rkn.namarekanan,php.noposting,
                spt.statusenabled,sp.norec AS norec_pd,php.statusenabled AS sttts,
                sp.totalharusdibayar AS totaltidakdiklaim,sp.nosbmlastfk
            "))
            ->where('spp.kdprofile', $kdProfile)
            ->where('sp.statusenabled', true);
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataPiutang = $dataPiutang->where('sp.tglstruk', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $dataPiutang = $dataPiutang->where('sp.tglstruk', '<=', $tgl);
        }

        if (isset($filter['objectkelompokpasienfk']) && $filter['objectkelompokpasienfk'] != "") {
            $dataPiutang = $dataPiutang->where('sp.objectkelompokpasienfk', '=', $filter['objectkelompokpasienfk']);
        }
        if (isset($filter['rekananfk']) && $filter['rekananfk'] != "") {
            $dataPiutang = $dataPiutang->where('sp.objectrekananfk', '=', $filter['rekananfk']);
        }

        if (isset($filter['ruanganId']) && $filter['ruanganId'] != "") {
            $dataPiutang = $dataPiutang->where('ru.id', '=', $filter['ruanganId']);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $dataPiutang = $dataPiutang->where('sp.namapasien_klien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        $dataPiutang = $dataPiutang->get();
        $result = array();
        foreach ($dataPiutang as $item) {
            if ($item->statusenabled ==  1 || is_null($item->statusenabled)) {
                if ($item->sttts == 1 || is_null($item->sttts)) {
                    if ($item->nosbmlastfk == null && $item->totalppenjamin > $item->totalsudahdibayar) {
                        if (!isset($item->noposting)) {
                            $status = 'Piutang';
                        } else {
                            $status = 'Collecting';
                        }
                    } else {
                        $status = 'Lunas';
                    }
                    $result[] = array(
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
                        'totaltidakdiklaim' => $item->totaltidakdiklaim,
                        'sisautang' => (float)$item->totalppenjamin - $item->totalsudahdibayar,
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
    public function daftarCollectedPiutang(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        $filter = $request->all();
        $dataCollector = DB::table('postinghutangpiutang_t as php')
            ->join('strukpelayananpenjamin_t as spp', 'spp.norec', '=', 'php.nostrukfk')
            ->join('strukpelayanan_t as spy', 'spy.norec', '=', 'spp.nostrukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'spy.noregistrasifk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->join('strukposting_t as sp', 'sp.noposting', '=', 'php.noposting')
            ->join('loginuser_s as lu', 'sp.kdhistorylogins', '=', 'lu.id')
            ->join('pegawai_m as p', 'lu.objectpegawaifk', '=', 'p.id')
            ->leftjoin('strukkwitansipiutang_t as skp', 'skp.norec', '=', 'php.strukkwitansipiutangfk')
            ->select(
                'sp.norec',
                'sp.tglposting',
                'php.noposting',
                'rkn.id as idrekanan',
                'rkn.namarekanan',
                'rkn.kodeexternal as partnercode',
                'php.statusenabled',
                'p.namalengkap',
                'php.nomorreferencebri',
                'skp.norec AS norec_skp',
                'skp.nokwitansi',
                DB::raw('SUM(spp.totalppenjamin) as totalpenjamin'),
                DB::raw('sum(spp.totalsudahdibayar) as sumtotalsudahdibayar'),
                DB::raw("count(php.noposting) as jlhpasien,CASE WHEN spp.totaldiskon IS NULL THEN 0 ELSE spp.totaldiskon END AS totaldiskon ")
            )
            ->where('php.kdprofile', $kdProfile);

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "") {
            $dataCollector = $dataCollector->where('sp.tglposting', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "") {
            $tgl = $filter['tglAkhir'] . " 23:59:59";
            $dataCollector = $dataCollector->where('sp.tglposting', '<=', $tgl);
        }

        if (isset($filter['status']) && $filter['status'] == "Collecting") {
            $dataCollector = $dataCollector->where('spp.totalsudahdibayar', '=', 0);
        }
        if (isset($filter['status']) && $filter['status'] == "Lunas") {
            $dataCollector = $dataCollector->where('spp.totalsudahdibayar', '>', 0);
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "") {
            $dataCollector = $dataCollector->where('p.namalengkap', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        if (isset($filter['noposting']) && $filter['noposting'] != "") {
            $dataCollector = $dataCollector->where('php.noposting', 'ilike', '%' . $filter['noposting'] . '');
        }
        if (isset($filter['namaRekanan']) && $filter['namaRekanan'] != "") {
            $dataCollector = $dataCollector->where('pd.objectrekananfk', '=', $filter['namaRekanan']);
        }
        $dataCollector = $dataCollector->where('sp.statusenabled', '=', '1');
        $dataCollector = $dataCollector->where('php.statusenabled', 1);

        $dataCollector = $dataCollector->groupBy(
            'sp.norec',
            'php.noposting',
            'sp.tglposting',
            'rkn.id',
            'rkn.namarekanan',
            'php.statusenabled',
            'p.namalengkap',
            'php.nomorreferencebri',
            'rkn.kodeexternal',
            'skp.norec',
            'skp.nokwitansi',
            'spp.totaldiskon'
        );
        $dataCollector = $dataCollector->get();

        $result = array();
        foreach ($dataCollector as $item) {
            $totalBayar = (float) $item->sumtotalsudahdibayar + (float) $item->totaldiskon;
            $status = '-';
            if ($totalBayar < $item->totalpenjamin) {
                $status = "Collecting";
            } elseif ($totalBayar == $item->totalpenjamin or $totalBayar > $item->totalpenjamin) {
                $status = "Lunas";
            }
            $result[] = array(
                'noPosting' => $item->noposting,
                'tglTransaksi' => $item->tglposting,
                'collector' => $item->namalengkap,
                'jlhPasien' => $item->jlhpasien,
                'totalKlaim' => $item->totalpenjamin,
                'status' => $status,
                'totalSudahDibayar' => $item->sumtotalsudahdibayar,
                'kelompokpasien' => "Perusahaan/Asuransi",
                'idrekanan' => $item->idrekanan,
                'namarekanan' => $item->namarekanan,
                'statusenabled' => $item->statusenabled,
                'nomorreferencebri' => $item->nomorreferencebri,
                'partnercode' => $item->partnercode,
                'norec_skp' => $item->norec_skp,
                'nokwitansi' => $item->nokwitansi,
                'totaldiskon' => $item->totaldiskon,

            );
        }
        return $this->respond($result);
    }
}
