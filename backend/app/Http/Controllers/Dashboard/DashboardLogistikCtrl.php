<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\KirimProduk;
use App\Models\Transaksi\LoggingUser;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StokProdukKadaluarsa;
use App\Models\Transaksi\StrukKirim;
use App\Models\Transaksi\StrukOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;

class DashboardLogistikCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getListRuangan(Request $request)
    {
        // $set = $this->settingFix('idInstalasiLogistik');
        $dataRuangan  = DB::table('maploginusertoruangan_s as mp')
            ->join('ruangan_m as ru', 'ru.id', 'mp.objectruanganfk')
            ->select('ru.id', 'ru.namaruangan')
            ->where('mp.kdprofile', $this->kdProfile)
            ->where('mp.statusenabled', true)
            ->where('mp.objectloginuserfk', $request['userData']['id'])
            ->get();
        return $this->respond($dataRuangan);
    }

    public function getDaftarOrderBarang(Request $request)
    {

        $idProfile = $this->kdProfile;
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->join('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.kdprofile', $idProfile)
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukorder_t as sp')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.noorder',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'ru.namaruangan as ruanganasal',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.qtyjenisproduk',
                DB::raw("CAST(sp.tglorder AS DATE)")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruangantujuanfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data = $data->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $data = $data->orderBy('sp.noorder');
        $data = $data->get();

        $detail = DB::table('orderpelayanan_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
            ->select(
                DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,
            ss.satuanstandar,spd.qtyproduk, spd.strukorderfk")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruangantujuanfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail = $detail->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $detail = $detail->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $detail = $detail->orderBy('sp.noorder');
        $detail = $detail->get();

        $results = array();
        $status = '';
        foreach ($data as $item) {
            $item->details = [];
            foreach ($detail as $item2) {
                if ($item->norec == $item2->strukorderfk) {
                    $item->details[] = $item2;
                }
            }

            if ($item->statusorder == 0) {
                $status = '';
            } else if ($item->statusorder == 1) {
                $status = 'Sudah Kirim';
            } else if ($item->statusorder == 2) {
                $status = 'Batal Kirim';
            }

            $results[] = array(
                'status' => 'Terima Order Barang',
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
                'norec' => $item->norec,
                'namaruanganasal' => $item->ruanganasal,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganorder,
                'statusorder' => $status,
                'jmlitem' => $item->qtyjenisproduk,
                'details' =>  $item->details,
            );
        }

        $data2 = DB::table('strukorder_t as sp')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaiorderfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.noorder',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'ru.namaruangan as ruanganasal',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganorder',
                'sp.statusorder',
                'sp.qtyjenisproduk',
                DB::raw("CAST(sp.tglorder AS DATE)")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruanganfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data2 = $data2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data2 = $data2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $data2 = $data2->orderBy('sp.noorder');
        $data2 = $data2->get();

        $detail2 = DB::table('orderpelayanan_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukorder_t as sp', 'sp.norec', '=', 'spd.strukorderfk')
            ->select(
                DB::raw("pr.id as kdproduk,pr.kdproduk as kdsirs,pr.namaproduk,
            ss.satuanstandar,spd.qtyproduk, spd.strukorderfk")
            )
            ->where('sp.kdprofile', $idProfile)
            ->whereBetween(DB::raw("sp.tglorder::date"), $rangeDate)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', $this->kelompokTransaksi('ORDER BARANG RUANGAN'))
            ->wherein('sp.objectruanganfk', $strRuangan);

        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail2 = $detail2->where('sp.objectruangantujuanfk', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $detail2 = $detail2->where('sp.noorder',  'ILIKE', '%' . $request['noorder'] . '%');
        }

        $detail2 = $detail2->orderBy('sp.noorder');
        $detail2 = $detail2->get();
        $status = '';
        foreach ($data2 as $item) {
            $item->details = [];
            foreach ($detail2 as $item2) {
                if ($item->norec == $item2->strukorderfk) {
                    $item->details[] = $item2;
                }
            }

            if ($item->statusorder == 0) {
                $status = '';
            } else if ($item->statusorder == 1) {
                $status = 'Sudah Kirim';
            } else if ($item->statusorder == 2) {
                $status = 'Batal Kirim';
            }

            $results[] = array(
                'status' => 'Kirim Order Barang',
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'jeniskirim' => $item->jenispermintaanfk == 1 ? "Amprahan" : "Transfer",
                'norec' => $item->norec,
                'namaruanganasal' => $item->ruanganasal,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganorder,
                'statusorder' => $status,
                'jmlitem' => $item->qtyjenisproduk,
                'details' =>  $item->details,
            );
        }

        $result = array(
            'daftar' => $results,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function getDaftarPenerimaanBarang(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('strukpelayanan_t as sp')
            ->leftJoin('strukpelayanandetail_t as spd', 'spd.nostrukfk', 'sp.norec')
            ->leftJOIN('rekanan_m as rkn', 'rkn.id', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', 'sp.objectpegawaipenerimafk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', 'sp.nosbklastfk')
            ->select(
                DB::raw("
                sp.tglstruk, sp.nostruk, rkn.namarekanan, pg.namalengkap, sp.nokontrak,
                ru.namaruangan, sp.norec, sp.nofaktur, sp.tglfaktur, CAST(sp.totalharusdibayar AS FLOAT), sbk.nosbk,
                sp.nosppb, sp.noorderfk, sp.qtyproduk
            ")
            )
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->where('sp.kdprofile', $this->kdProfile)
            ->groupBy(
                'sp.tglstruk',
                'sp.nostruk',
                'rkn.namarekanan',
                'pg.namalengkap',
                'sp.nokontrak',
                'ru.namaruangan',
                'sp.norec',
                'sp.nofaktur',
                'sp.tglfaktur',
                'sp.totalharusdibayar',
                'sbk.nosbk',
                'sp.nosppb',
                'sp.noorderfk',
                'sp.qtyproduk'
            )
            ->WhereBetween('sp.tglstruk', $rangeDate);

        if (isset($request['ruangan'])) {
            $data = $data->where('ru.id', $request['ruangan']);
        }

        // if (isset($request['nostruk'])) {
        //     $data = $data->where('sp.nostruk', $request['nostruk']);
        // }

        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();

        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("select  pr.namaproduk,ss.satuanstandar,spd.qtyproduk,spd.qtyprodukretur,spd.hargasatuan,spd.hargadiscount,
                    --spd.hargappn,((spd.hargasatuan-spd.hargadiscount+spd.hargappn)*spd.qtyproduk) as total,spd.tglkadaluarsa,spd.nobatch
                    --spd.hargappn,((spd.hargasatuan * spd.qtyproduk)-spd.hargadiscount+spd.hargappn) as total,spd.tglkadaluarsa,spd.nobatch
                    spd.hargappn,CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*
                    ((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS total,
                    spd.tglkadaluarsa,spd.nobatch
                    from strukpelayanandetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk
                    where spd.kdprofile=:kdProfile and nostrukfk=:norec"),
                array(
                    'kdProfile' => $this->kdProfile,
                    'norec' => $item->norec,
                )
            );
            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'nofaktur' => $item->nofaktur,
                'tglfaktur' => $item->tglfaktur,
                'namarekanan' => $item->namarekanan,
                'norec' => $item->norec,
                'namaruangan' => $item->namaruangan,
                'namapenerima' => $item->namalengkap,
                'totalharusdibayar' => $item->totalharusdibayar,
                'nosbk' => $item->nosbk,
                'nosppb' => $item->nosppb,
                'nokontrak' => $item->nokontrak,
                'noorderfk' => $item->noorderfk,
                'jmlitem' => $item->qtyproduk,
                'details' => $details,
            );
        }
        if (count($data) == 0) {
            $result = [];
        }

        $result = array(
            'daftar' => $result,
            // 'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function chartCountRuanganByDate()
    {
        $dateBetween = [date("Y-m-d", strtotime("-1 month")), date("Y-m-d")];
        // return $this->respond($dateBetween);
        $dataRequest = DB::table('strukorder_t as so')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->select(
                DB::raw('count(so.objectruanganfk) as jumlah'),
                'ru.namaruangan'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idPermintaanBarang'))
            ->whereBetween(DB::raw("CAST(so.tglorder AS DATE)"), $dateBetween)
            ->groupBy('ru.namaruangan')
            ->get();

        foreach ($dataRequest as $d) {
            $jumlahPermintaan[] = $d->jumlah;
            $labelRuangan[]  = $d->namaruangan;
        }
        $result['chartRNG']['count'] = $jumlahPermintaan;
        $result['chartRNG']['ruangan'] = $labelRuangan;
        return $this->respond($result);
    }

    public function stokProdukByRuangan(Request $request)
    {
        $kdJenisObat =  $this->settingFix('kdJenisProdukObat');
        $data = DB::table('stokprodukdetail_t as spd')
            ->join('produk_m as pr', function ($join) {
                $join->on('spd.objectprodukfk', '=', 'pr.id')->on('spd.kdprofile', '=', 'pr.kdprofile');
            })
            ->join('detailjenisproduk_m as djp', function ($join) {
                $join->on('djp.id', '=', 'pr.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'pr.kdprofile');
            })
            ->join('ruangan_m as ru', function ($join) {
                $join->on('ru.id', '=', 'spd.objectruanganfk')->on('ru.kdprofile', '=', 'spd.kdprofile');
            })
            ->select(
                'pr.id',
                'pr.kdprofile',
                'pr.namaproduk',
                'djp.detailjenisproduk',
                DB::raw("case when pr.objectdetailjenisprodukfk = $kdJenisObat
                                then 'Medis'
                                else 'Non Medis' end
                                AS jenis, SUM (spd.qtyproduk) as stok
                            "),
                'spd.qtyproduk',
                'ru.namaruangan'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.qtyproduk', '!=', null)
            ->where('spd.qtyproduk', '>', 0);
        if (isset($request['ruangan'])) {
            $data = $data->where('ru.id', $request['ruangan']);
        }
        if (isset($request['namaproduk'])) {
            $data = $data->where('pr.namaproduk', 'ILIKE', '%' .  $request['namaproduk']  . '%');
            // $data =  $data->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }

        $data = $data->groupBy(
            'pr.id',
            'pr.kdprofile',
            'pr.namaproduk',
            'jenis',
            'spd.qtyproduk',
            'ru.namaruangan',
            'djp.detailjenisproduk',
            'djp.objectjenisprodukfk'
        );
        $result = $data->limit(10);
        $result = $data->get();

        return $this->respond($result);
    }

    public function chartMedisNonMedis()
    {
        $minggu = Carbon::now()->subMonth(11)->format('Y-m-d');
        $tanggal = Carbon::now()->addMonth(1)->format('Y-m-d');

        $begin = new \DateTime($minggu);
        $end = new \DateTime($tanggal);
        $interval = \DateInterval::createFromDateString('1 month');
        $period = new \DatePeriod($begin, $interval, $end);
        $kdJenisObat =  $this->settingFix('kdJenisProdukObat');
        $rng =  collect(DB::select("
            select
            kt.tglkejadian, kt.saldoakhir,pr.namaproduk,djp.detailjenisproduk,
            case when djp.objectjenisprodukfk = ? then 'Medis' else 'Non Medis' end as jenis
            from kartustok_t as kt
            join produk_m as pr on pr.id= kt.produkfk and pr.kdprofile = kt.kdprofile
            join detailjenisproduk_m as djp on djp.id=pr.objectdetailjenisprodukfk and djp.kdprofile = pr.kdprofile
            where kt.saldoakhir is not null
            and kt.kdprofile = ?
            order by tglkejadian;
        ", [$kdJenisObat, $this->kdProfile]));
        $array2 = [];
        foreach ($period as $dt) {
            $array2[] = array(
                'bulan' => $dt->format("M"),
                'tgl' => $dt->format("Y-m"),
                'medis' => 0,
                'non' => 0
            );
        }
        foreach ($rng as $r) {
            foreach ($array2 as $z => $d) {
                if (date('Y-m', strtotime($r->tglkejadian)) == $d['tgl']) {
                    if ($r->jenis == 'Medis') {
                        $array2[$z]['medis'] =   $array2[$z]['medis'] + $r->saldoakhir;
                    }
                    if ($r->jenis == 'Non Medis') {
                        $array2[$z]['non'] =   $array2[$z]['non'] + $r->saldoakhir;
                    }
                }
            }
        }
        $seriesM = array([
            'name' => 'Medis',
            'data' => []
        ], [
            'name' => 'Non Medis',
            'data' => []
        ]);
        $categories = [];
        foreach ($array2 as $d) {
            array_push($categories, $d['bulan']);
            array_push($seriesM[0]['data'], $d['medis']);
            array_push($seriesM[1]['data'], $d['non']);
        }
        $result['chartMedis']['categories'] = $categories;
        $result['chartMedis']['series'] = $seriesM;

        return $this->respond($result);
    }

    public function getDaftarDistribusiBarang(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $idProfile = (int)$this->kdProfile;
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.objectloginuserfk', $this->getUserId())
            ->where('mlu.kdprofile', $idProfile)
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }
        $data = DB::table('strukkirim_t as sp')
            ->leftjoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipengirimfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'sp.objectruangantujuanfk')
            ->select(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id as ruasalid',
                'ru.namaruangan as ruanganasal',
                'ru2.id as rutujuanid',
                'ru2.namaruangan as ruangantujuan',
                'sp.keteranganlainnyakirim',
                DB::raw('count(kp.objectprodukfk) as jmlitem')
            )
            ->where('sp.kdprofile', $idProfile)
            ->groupBy(
                'sp.norec',
                'sp.tglkirim',
                'sp.nokirim',
                'sp.jenispermintaanfk',
                'pg.namalengkap',
                'sp.noorderfk',
                'ru.id',
                'ru.namaruangan',
                'ru2.id',
                'ru2.namaruangan',
                'sp.keteranganlainnyakirim'
            )
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan)
            ->where('sp.noregistrasifk', '=', 0)
            ->whereBetween(DB::raw("sp.tglkirim::date"), $rangeDate)
            ->orderBy('sp.nokirim');

        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $data =  $data->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']  . '%');
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data =  $data->where('sp.objectruanganfk', '=', $request['ruangantujuanfk']);
        }
        $data = $data->get();

        $detail = DB::table('kirimproduk_t as spd')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftjoin('satuanstandar_m as ss', 'ss.id', '=', 'spd.objectsatuanstandarfk')
            ->leftjoin('strukkirim_t as sp', 'sp.norec', '=', 'spd.nokirimfk')
            ->select(
                'pr.id as kdproduk',
                'pr.namaproduk',
                'spd.qtyprodukretur',
                'spd.objectprodukfk',
                'spd.nokirimfk',
                'ss.satuanstandar',
                DB::raw("sum(spd.qtyproduk) as qtyproduk")
            )
            ->where('sp.kdprofile', $idProfile)
            ->where('sp.statusenabled', true)
            ->where('sp.objectkelompoktransaksifk', 34)
            ->wherein('sp.objectruanganasalfk', $strRuangan)
            ->whereBetween(DB::raw("sp.tglkirim::date"), $rangeDate)
            ->where('sp.noregistrasifk', '=', 0);

        if (isset($request['nokirim']) && $request['nokirim'] != "" && $request['nokirim'] != "undefined") {
            $detail =  $detail->where('sp.nokirim', 'ILIKE', '%' . $request['nokirim']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $detail =  $detail->where('sp.objectruanganfk', '=', $request['ruangantujuanfk']);
        }
        // $detail = $detail->orderBy('sp.nokirim');
        $detail = $detail->groupBy('pr.id', 'pr.namaproduk', 'spd.qtyprodukretur', 'spd.objectprodukfk', 'spd.nokirimfk', 'ss.satuanstandar');
        $detail = $detail->get();

        $results = array();
        foreach ($data as $item) {
            $item->details = [];
            foreach ($detail as $item2) {
                if ($item->norec == $item2->nokirimfk) {
                    $item->details[] = $item2;
                }
            }
            $results[] = array(
                'status' => 'Kirim Barang',
                'tglstruk' => $item->tglkirim,
                'nostruk' => $item->nokirim,
                'noorderfk' => $item->noorderfk,
                'jenispermintaanfk' => $item->jenispermintaanfk,
                'jeniskirim' => $item->jenispermintaanfk == 1 ? 'Amprahan' : 'Transfer',
                'norec' => $item->norec,
                'ruasalid' => $item->ruasalid,
                'namaruanganasal' => $item->ruanganasal,
                'rutujuanid' => $item->rutujuanid,
                'namaruangantujuan' => $item->ruangantujuan,
                'petugas' => $item->namalengkap,
                'keterangan' => $item->keteranganlainnyakirim,
                'jmlitem' => $item->jmlitem,
                'details' => $item->details,
            );
        }

        $result = array(
            'daftar' => $results,
        );

        return $this->respond($result);
    }

    // Tambahan Titi
    public function BatalKirimTerima(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;
        DB::beginTransaction();
        $dataLogin = $request->all();
        try {
            if ($request['strukkirim']['noorderfk'] != '') {
                $dataAing = StrukOrder::where('norec', $request['strukkirim']['noorderfk'])
                    ->update(['statusorder' => 2]);
            }
            $dataSK = StrukKirim::where('norec', $request['strukkirim']['noreckirim'])->where('kdprofile', $idProfile)->first();
            $getDetails = DB::table('kirimproduk_t as kp')
                            ->join('produk_m as pr','pr.id','kp.objectprodukfk')
                            ->select('kp.*','pr.namaproduk')
                            ->where('kp.kdprofile', $idProfile)
                            ->where('kp.qtyproduk', '>', 0)
                            ->where('kp.statusenabled',true)
                            ->where('nokirimfk', $request['strukkirim']['noreckirim'])
                            ->get();
            // KirimProduk::where('nokirimfk', $request['strukkirim']['noreckirim'])
            //     ->where('kdprofile', $idProfile)
            //     ->where('qtyproduk', '>', 0)
            //     ->get();
            foreach ($getDetails as $item) {
                //PENGIRIM
                $saldoAwalPengirim = 0;
                $saldoAwalPenerima = 0;
                $ruAsal = $request['strukkirim']['objectruanganasal'];
                $dataSaldoAwalK = collect(DB::select("
                    select sum(qtyproduk) as qty from stokprodukdetail_t
                    where kdprofile = $idProfile and objectruanganfk=$ruAsal and objectprodukfk= $item->objectprodukfk
                "))->first();

                $saldoAwalPengirim = (float)$dataSaldoAwalK->qty + (float)$item->qtyproduk;

                $tambah = StokProdukDetail::where('nostrukterimafk', $item->nostrukterimafk)
                    ->where('kdprofile', $idProfile)
                    ->where('objectruanganfk', $request['strukkirim']['objectruanganasal'])
                    ->where('objectprodukfk', $item->objectprodukfk)
                    ->first();
   
                DB::table('stokprodukdetail_t')
                    ->where('kdprofile', $kdProfile)
                    ->where('norec', $tambah->norec)
                    ->sharedLock()
                    ->increment('qtyproduk', (float)$item->qtyproduk);

                $this->kartu_STOK(array(
                    "saldoawal" => (float)$dataSaldoAwalK->qty,
                    "qtyin" => (float)$item->qtyproduk,
                    "qtyout" => 0,
                    "saldoakhir" => (float)$dataSaldoAwalK->qty + (float)$item->qtyproduk,
                    "keterangan" => 'Batal Kirim Barang ' .$item->namaproduk. ', ke Ruangan ' . $request['strukkirim']['namaruangantujuan'] . ' No Kirim: ' . $dataSK->nokirim,
                    "produkfk" => $item->objectprodukfk,
                    "ruanganfk" => $request['strukkirim']['objectruanganasal'],
                    "tglinput" => date('Y-m-d H:i:s'),
                    "tglkejadian" => date('Y-m-d H:i:s'),
                    "nostrukterimafk" => $item->nostrukterimafk,
                    "norectransaksi" => $request['strukkirim']['noreckirim'],
                    "tabletransaksi" => 'strukkirim_t',
                    "stokprodukdetailfk" => $tambah->norec,
                    "flagfk" => null,
                ));

                if ($request['strukkirim']['jenispermintaanfk'] == 2) {
                    //PENERIMA
                    $ruTujuan = $request['strukkirim']['obejectruangantujuan'];
                    $dataSaldoAwalT = collect(DB::select("select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $idProfile and objectruanganfk=$ruTujuan and objectprodukfk=$item->objectprodukfk
                        "))->first();
                    $saldoAwalPenerima = (float)$dataSaldoAwalT->qty - (float)$item->qtyproduk;

                    $kurang = StokProdukDetail::where('nostrukterimafk', $item->nostrukterimafk)
                        ->where('objectruanganfk', $request['strukkirim']['obejectruangantujuan'])
                        ->where('objectprodukfk', $item->objectprodukfk)
                        //                        ->where('qtyproduk','>',0)
                        ->first();

                    DB::table('stokprodukdetail_t')
                        ->where('kdprofile', $kdProfile)
                        ->where('norec', $kurang->norec)
                        ->sharedLock()
                        ->decrement('qtyproduk', (float)$item->qtyproduk);

                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$saldoAwalPenerima,
                        "qtyin" => 0,
                        "qtyout" => (float)$item->qtyproduk,
                        "saldoakhir" => (float)$dataSaldoAwalT->qty - (float)$item->qtyproduk,
                        "keterangan" => 'Batal Terima Barang '. $item->namaproduk .', dari Ruangan ' . $request['strukkirim']['namaruanganasal'] . ' No Kirim: ' . $dataSK->nokirim,
                        "produkfk" => $item->objectprodukfk,
                        "ruanganfk" => $request['strukkirim']['obejectruangantujuan'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $item->nostrukterimafk,
                        "norectransaksi" => $request['strukkirim']['noreckirim'],
                        "tabletransaksi" => 'strukkirim_t',
                        "stokprodukdetailfk" => $tambah->norec,
                        "flagfk" => null,
                    ));
                }
            }
            StrukKirim::where('norec', $request['strukkirim']['noreckirim'])
                ->where('kdprofile', $idProfile)
                ->update([
                    'statusenabled' => false
                ]);
            $this->LOGGING(
                'Batal Kirim',
                $request['strukkirim']['noreckirim'],
                'strukkirim_t',
                'Batal Kirim Barang dari Ruangan ' . $request['strukkirim']['namaruanganasal']
                    . ' ke Ruangan ' . $request['strukkirim']['namaruangantujuan'] . ' Jumlah '
                    . $request['strukkirim']['jmlitem']
                    . ' (' . $request['strukkirim']['nostruk'] . ')'
            );


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
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getBarangKadaluarsa(Request $request)
    {

        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('stokprodukkadaluarsa_t as spk')
            ->join('produk_m as pr', 'pr.id', 'spk.objectprodukfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', 'djp.objectjenisprodukfk')
            ->join('satuanstandar_m as sa', 'sa.id', 'pr.objectsatuanstandarfk')
            ->join('ruangan_m as ru', 'ru.id', 'spk.objectruanganfk')
            ->select('pr.id', 'pr.namaproduk', 'pr.kdproduk', 'spk.tglkadaluarsa', DB::raw("spk.qtyproduk"), 'ru.namaruangan')
            ->whereBetween(DB::raw("CAST(spk.tglkadaluarsa as Date)"), $rangeDate)
            ->where('spk.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->where('pr.statusenabled', true)
            ->where('qtyproduk', '>', 0)
            ->groupBy('pr.id', 'pr.kdprofile', 'pr.namaproduk', 'spk.tglkadaluarsa', 'spk.qtyproduk', 'ru.namaruangan')
            ->orderBy('namaproduk', 'asc')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getDataComboKadaluarsa(Request $request)
    {
        $dataProduk =  DB::select(
            DB::raw("select * from
                        (select pr.id,pr.kdproduk as kdsirs,pr.namaproduk,sa.id as ssid,sa.satuanstandar,SUM(spd.qtyproduk) as qtyproduk,spd.objectruanganfk
                        from stokprodukdetail_t as spd
                        INNER JOIN produk_m as pr on pr.id = spd.objectprodukfk and pr.kdprofile = spd.kdprofile
                        INNER JOIN detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk and djp.kdprofile = pr.kdprofile
                        INNER JOIN jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk and jp.kdprofile = djp.kdprofile
                        INNER JOIN satuanstandar_m as sa on sa.id = pr.objectsatuanstandarfk and sa.kdprofile = pr.kdprofile
                        where spd.kdprofile = $this->kdProfile and pr.statusenabled=true and spd.objectruanganfk=:objectruangan
                        GROUP BY pr.id,pr.kdproduk,pr.namaproduk,sa.id,sa.satuanstandar,spd.objectruanganfk) as x
                        where x.qtyproduk > 0
                        order by x.namaproduk asc"),
            array(
                'objectruangan' => $request['objectruangan'],
            )
        );
        // return $dataProduk;

        $dataKonversiProduk = DB::table('konversisatuan_t as ks')
            ->JOIN('satuanstandar_m as ss', function ($j) {
                $j->on('ss.id', '=', 'ks.satuanstandar_asal')->on('ss.kdprofile', '=', 'ks.kdprofile');
            })
            ->JOIN('satuanstandar_m as ss2', function ($j) {
                $j->on('ss2.id', '=', 'ks.satuanstandar_tujuan')->on('ss2.kdprofile', '=', 'ks.kdprofile');
            })
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
                'kdsirs' => $item->kdsirs,
                'kdproduk' => $item->kdsirs,
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'qtyproduk' => $item->qtyproduk,
            );
        }

        return $this->respond($dataProdukResult);
    }

    public function saveBarangKadaluarsa(Request $request)
    {
        DB::beginTransaction();

        $idProfile = (int) $this->kdProfile;
        $ruanganAsal = DB::select(
            DB::raw("select ru.namaruangan from ruangan_m as ru where ru.kdprofile = $idProfile and ru.id=:id"),
            array(
                'id' => $request['strukkirim']['objectruanganfk'],
            )
        );
        $strRuanganAsal = '';
        $norecSpd = '';
        $strRuanganAsal = $ruanganAsal[0]->namaruangan;
        $noStrukTerima = '';
        $norecSpd = '';
        $dataSPD = '';
        try {
            foreach ($request['details'] as $item) {
                $saldoAwal = DB::table('stokprodukdetail_t as spd')
                    ->join('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
                    ->select(
                        'spd.qtyproduk as qty',
                        'spd.nostrukterimafk',
                        'spd.norec',
                        'spd.objectasalprodukfk as asalprodukfk',
                        'spd.hargadiscount',
                        'spd.harganetto1 as harganetto',
                        'spd.harganetto1 as hargasatuan',
                        'pr.namaproduk'
                    )
                    ->where('spd.kdprofile', $this->kdProfile)
                    ->where('spd.objectruanganfk', $request['strukkirim']['objectruanganfk'])
                    ->where('spd.objectprodukfk', $item['produkfk'])
                    ->where('spd.qtyproduk', '>', 0)
                    ->get();

                $saldoAwalPengirim = 0;
                $jumlah = (float)$item['jumlah'] * (float)$item['nilaikonversi'];
                foreach ($saldoAwal as $items) {
                    $saldoAwalPengirim = (float)$items->qty;
                    $dataStok = StokProdukDetail::where('norec', $items->norec)
                        ->where('kdprofile', $idProfile)
                        ->orderBy('tglpelayanan', 'desc')
                        ->first();
                    $noStrukTerima = $dataStok->nostrukterimafk;
                    if ($request['strukkirim']['norec'] == '') {
                        $dataNewSPD = new StokProdukKadaluarsa();
                        $dataNewSPD->norec = $dataNewSPD->generateNewId();
                        $dataNewSPD->kdprofile = $idProfile;
                        $dataNewSPD->statusenabled = true;
                        $norecSpd = $dataNewSPD->norec;
                    } else {
                        $dataNewSPD = StokProdukKadaluarsa::where('norec', $request['strukkirim']['norec'])->where('kdprofile', $idProfile)->first();
                    }
                    if ((float)$items->qty <= $jumlah) {
                        $dataNewSPD->objectasalprodukfk = $item['asalprodukfk'];
                        $dataNewSPD->hargadiscount = 0;
                        $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                        $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                        $dataNewSPD->persendiscount = 0;
                        $dataNewSPD->objectprodukfk = $item['produkfk'];
                        $dataNewSPD->qtyproduk = (float)$items->qty;
                        $dataNewSPD->qtyprodukonhand = 0;
                        $dataNewSPD->qtyprodukoutext = 0;
                        $dataNewSPD->qtyprodukoutint = 0;
                        $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruanganfk'];
                        $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                        $dataNewSPD->tglkadaluarsa = $request['strukkirim']['tglkadaluarsa'];
                        $dataNewSPD->tglpelayanankadaluarsa =  $this->getDateTime()->format('Y-m-d H:i:s');
                        $dataNewSPD->keteranganlainnya = $request['strukkirim']['keterangan'];
                        $dataNewSPD->save();
                        $dataSPD = $dataNewSPD;
                        $jumlah = $jumlah - (float)$items->qty;
                        StokProdukDetail::where('norec', $items->norec)
                            ->where('kdprofile', $idProfile)
                            ->update(['qtyproduk' => 0]);
                    } else {
                        $dataNewSPD->objectasalprodukfk = $item['asalprodukfk'];
                        $dataNewSPD->hargadiscount = 0;
                        $dataNewSPD->harganetto1 = $dataStok->harganetto1;
                        $dataNewSPD->harganetto2 = $dataStok->harganetto2;
                        $dataNewSPD->persendiscount = 0;
                        $dataNewSPD->objectprodukfk = $item['produkfk'];
                        $dataNewSPD->qtyproduk = $jumlah;
                        $dataNewSPD->qtyprodukonhand = 0;
                        $dataNewSPD->qtyprodukoutext = 0;
                        $dataNewSPD->qtyprodukoutint = 0;
                        $dataNewSPD->objectruanganfk = $request['strukkirim']['objectruanganfk'];
                        $dataNewSPD->nostrukterimafk = $dataStok->nostrukterimafk;
                        $dataNewSPD->tglkadaluarsa = $request['strukkirim']['tglkadaluarsa'];
                        $dataNewSPD->tglpelayanankadaluarsa =  $this->getDateTime()->format('Y-m-d H:i:s');
                        $dataNewSPD->keteranganlainnya = $request['strukkirim']['keterangan'];
                        // return $dataNewSPD;
                        $dataNewSPD->save();
                        $dataSPD = $dataNewSPD;
                        $saldoakhir = (float)$items->qty - $jumlah;
                        $dataStok = StokProdukDetail::where('norec', $items->norec)
                            ->where('kdprofile', $idProfile)
                            ->where('objectprodukfk', $item['produkfk'])
                            ->update(['qtyproduk' => (float)$saldoakhir]);
                    }

                    //## KartuStok

                    $this->kartu_STOK(array(
                        "saldoawal" => $saldoAwalPengirim,
                        "qtyin" => 0,
                        "qtyout" => $jumlah,
                        "saldoakhir" => $saldoAwalPengirim - $jumlah,
                        "keterangan" => "Barang Kadaluarsa, dari Ruangan" . $strRuanganAsal,
                        "produkfk" => $item['produkfk'],
                        "ruanganfk" => $request['strukkirim']['objectruanganfk'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" =>  $noStrukTerima,
                        "norectransaksi" => $norecSpd,
                        "tabletransaksi" => 'stokprodukkadaluarsa_t',
                        "stokprodukdetailfk" => $dataNewSPD->norec,
                        "flagfk" => null,
                    ));
                }

                //## Logging User
                // $newId = LoggingUser::max('id');
                // $newId = $newId + 1;
                // $logUser = new LoggingUser();
                // $logUser->id = $newId;
                // $logUser->norec = $logUser->generateNewId();
                // $logUser->kdprofile = $idProfile;
                // $logUser->statusenabled = true;
                // $logUser->jenislog = 'Input Barang Kadaluarsa';
                // $logUser->noreff = $norecSpd;
                // $logUser->referensi = 'norec Stok Produk Kadaluarsa';
                // $logUser->objectloginuserfk =  $dataLogin['userData']['id'];
                // $logUser->tanggal = $this->getDateTime()->format('Y-m-d H:i:s');
                // $logUser->keterangan = 'Input Barang Kadaluarsa tanggal ' . $request['strukkirim']['tglkadaluarsa'];
                // $logUser->save();
            }

            DB::commit();
            $result = [
                "message" => "Simpan Barang Kadaluarsa Berhasil",
                "status" => 200,
                "data" => $dataSPD,
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "message" => "Simpan Barang Kadaluarsa Gagal",
                "status" => 400,
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function getDaftarPenerimaanSuplier(Request $request)
    {

        $dataLogin = $request->all();
        $data = DB::table('strukpelayanan_t as sp')
            ->JOIN('strukpelayanandetail_t as spd', 'spd.nostrukfk', '=', 'sp.norec')
            ->leftJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')->on('pg.kdprofile', '=', 'sp.kdprofile')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
            ->select(
                DB::raw("
                sp.tglstruk, sp.nostruk, rkn.namarekanan, pg.namalengkap, sp.nokontrak,
                ru.namaruangan, sp.norec, sp.nofaktur, sp.tglfaktur, CAST(sp.totalharusdibayar AS FLOAT), sbk.nosbk,
                sp.nosppb, sp.noorderfk, sp.qtyproduk
            ")
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->groupBy(
                'sp.tglstruk',
                'sp.nostruk',
                'rkn.namarekanan',
                'pg.namalengkap',
                'sp.nokontrak',
                'ru.namaruangan',
                'sp.norec',
                'sp.nofaktur',
                'sp.tglfaktur',
                'sp.totalharusdibayar',
                'sbk.nosbk',
                'sp.nosppb',
                'sp.noorderfk',
                'sp.qtyproduk'
            );

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglstruk', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('sp.tglstruk', '<=', $tgl);
        }
        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('sp.nostruk', 'ILIKE', '%' . $request['nostruk']);
        }
        if (isset($request['namarekanan']) && $request['namarekanan'] != "" && $request['namarekanan'] != "undefined") {
            $data = $data->where('rkn.namarekanan', 'ILIKE', '%' . $request['namarekanan'] . '%');
        }
        if (isset($request['nofaktur']) && $request['nofaktur'] != "" && $request['nofaktur'] != "undefined") {
            $data = $data->where('sp.nofaktur', 'ILIKE', '%' . $request['nofaktur'] . '%');
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('spd.objectprodukfk', '=', $request['produkfk']);
        }
        if (isset($request['noSppb']) && $request['noSppb'] != "" && $request['noSppb'] != "undefined") {
            $data = $data->where('sp.nosppb', 'ILIKE', '%' . $request['noSppb'] . '%');
        }
        if (isset($request['noterima']) && $request['noterima'] != "" && $request['noterima'] != "undefined") {
            $data = $data->where('sp.nostruk', 'ILIKE', '%' . $request['noterima'] . '%');
        }
        //        $data = $data->distinct();
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', 35);
        $data = $data->orderBy('sp.nostruk');
        $data = $data->get();
        return $data;
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("select  pr.namaproduk,ss.satuanstandar,spd.qtyproduk,spd.qtyprodukretur,spd.hargasatuan,spd.hargadiscount,
                    --spd.hargappn,((spd.hargasatuan-spd.hargadiscount+spd.hargappn)*spd.qtyproduk) as total,spd.tglkadaluarsa,spd.nobatch
                    --spd.hargappn,((spd.hargasatuan * spd.qtyproduk)-spd.hargadiscount+spd.hargappn) as total,spd.tglkadaluarsa,spd.nobatch
                    spd.hargappn,CAST(((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))+(spd.persenppn*((spd.qtyproduk*spd.hargasatuan)-(((spd.persendiscount*spd.hargasatuan)/100)*spd.qtyproduk))/100) AS FLOAT) AS total,
                    spd.tglkadaluarsa,spd.nobatch
                    from strukpelayanandetail_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk and pr.kdprofile = spd.kdprofile
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk and ss.kdprofile = spd.kdprofile
                    where spd.kdprofile = $this->kdProfile and nostrukfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $result[] = array(
                'tglstruk' => $item->tglstruk,
                'nostruk' => $item->nostruk,
                'nofaktur' => $item->nofaktur,
                'tglfaktur' => $item->tglfaktur,
                'namarekanan' => $item->namarekanan,
                'norec' => $item->norec,
                'namaruangan' => $item->namaruangan,
                'namapenerima' => $item->namalengkap,
                'totalharusdibayar' => $item->totalharusdibayar,
                'nosbk' => $item->nosbk,
                'nosppb' => $item->nosppb,
                'nokontrak' => $item->nokontrak,
                'noorderfk' => $item->noorderfk,
                'jmlitem' => $item->qtyproduk,
                'details' => $details,
            );
        }
        if (count($data) == 0) {
            $result = [];
        }

        $result = array(
            'daftar' => $result,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function getInformasiStok(Request $request)
    {

        $data = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'ru.id', 'spd.objectruanganfk')
            ->join('strukpelayanan_t as sk', 'sk.norec', 'spd.nostrukterimafk')
            ->select(
                'sk.norec',
                'spd.objectprodukfk',
                'sk.tglstruk',
                'spd.objectasalprodukfk',
                'spd.harganetto2',
                'spd.hargadiscount',
                'ru.namaruangan',
                DB::raw("CAST(sum(spd.qtyproduk) AS FLOAT) as qtyproduk"),
                'spd.objectruanganfk as kdruangan'
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->where('spd.objectprodukfk', $request['produkfk'])
            ->where('ru.statusenabled', true)
            ->where('spd.kdprofile', true)
            ->groupBy(
                'sk.norec',
                'spd.objectprodukfk',
                'sk.tglstruk',
                'spd.objectasalprodukfk',
                'spd.harganetto2',
                'spd.hargadiscount',
                'ru.namaruangan',
                'spd.objectruanganfk'
            )
            ->orderBy('sk.tglstruk')
            ->get();

        $jmlstok = 0;
        foreach ($data as $item) {
            $jmlstok = $jmlstok + $item->qtyproduk;
        }
        $a = [];
        foreach ($data as $nenden) {
            $i = 0;
            $sama = false;
            foreach ($a as $hideung) {
                if ($nenden->kdruangan == $a[$i]['kdruangan']) {
                    $sama = true;
                    $a[$i]['qtyproduk'] = $a[$i]['qtyproduk'] + $nenden->qtyproduk;
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                $a[] = array(
                    'qtyproduk' => $nenden->qtyproduk,
                    'kdruangan' => $nenden->kdruangan,
                    'namaruangan' => $nenden->namaruangan,
                );
            }
        }

        $result = array(
            'jmlstok' => $jmlstok,
            'infostok' => $a,
            'detail' => $data,
            'message' => 'inhuman@epic',
        );
        return $this->respond($result);
    }
}
