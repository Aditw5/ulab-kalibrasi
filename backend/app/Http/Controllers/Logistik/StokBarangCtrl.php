<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokProduk;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StokProdukDetailOpname;
use App\Models\Transaksi\StrukClosing;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class StokBarangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getStokProduck(Request $request)
    {
        // $kdProfile = $this->getDataKdProfile($request);

        $data = DB::table('pelayananpasien_t as pp')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->leftjoin('detailjenisproduk_m as djp', 'djp.id', 'prd.objectdetailjenisprodukfk')
            ->leftjoin('jenisproduk_m as jp', 'jp.id', 'djp.objectjenisprodukfk')
            ->leftjoin('kelompokproduk_m as kp', 'kp.id', 'jp.objectkelompokprodukfk')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->select(
                'apd.objectruanganfk',
                'ru.namaruangan',
                DB::raw("CAST(pp.tglpelayanan AS DATE)"),
                'pp.produkfk',
                'prd.namaproduk',
                'pp.jumlah',
                'kp.id as idkelompokproduk',
                'kp.kelompokproduk',
                'jp.id as idjenisproduk',
                DB::raw("CAST(pp.tglkadaluarsa AS DATE)"),
                'jp.jenisproduk',
                'pp.tglregistrasi',
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.isobat', true);

        if (isset($request['dari']) && $request['dari'] != "" && $request['dari'] != "undefined") {
            $data = $data->where('pp.tglpelayanan', '>=', $request['dari']);
        }
        if (isset($request['sampai']) && $request['sampai'] != "" && $request['sampai'] != "undefined") {
            $data = $data->where('pp.tglpelayanan', '<=', $request['sampai']);
        }

        if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
            $data = $data->where('apd.objectruanganfk', $request['idruangan']);
        }
        if (isset($request['idkelproduk']) && $request['idkelproduk'] != "" && $request['idkelproduk'] != "undefined") {
            $data = $data->where('kp.id', $request['idkelproduk']);
        }

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;
        // $isExpired = false;
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->produkfk == $data10[$i]['produkfk']) {
                    $sama = true;
                    $jml = (float)$hideung['total'] + 1 * $item->jumlah;
                    $data10[$i]['total'] = $jml;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $data10[] = array(
                    'objectruanganfk' => $item->objectruanganfk,
                    'namaruangan' => $item->namaruangan,
                    'tglpelayanan' => $item->tglpelayanan,
                    'produkfk' => $item->produkfk,
                    'tglkadaluarsa' => $item->tglkadaluarsa,
                    'namaproduk' => $item->namaproduk,
                    'idkelompokproduk' => $item->idkelompokproduk,
                    'idjenisproduk' => $item->idjenisproduk,
                    'total' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['total'];
            }
            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'ramdanegie',

        );
        return $this->respond($result);
    }

    public function itemDropdown()
    {
        $res['kelompokproduk'] = KelompokProduk::mine()->get();
        $res['namaruangan'] = Ruangan::mine()->get();

        return $this->respond($res);
    }

    public function getDaftarStokOpname(Request $request)
    {
        $idProfile = $this->kdProfile;
        $detailjenisprodukfk = '';
        $jenisprodukfk = '';
        $namaproduk = '';
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $ruanganId = '';

        if (isset($request['jeniskprodukid']) &&  $request['jeniskprodukid'] != '') {
            $jenisprodukfk = "and djp.objectjenisprodukfk in (" . $request['jeniskprodukid'] . ")";
        }
        if (isset($request['namaproduk']) &&  $request['namaproduk'] != '') {
            $namaproduk = "and pr.namaproduk  ILIKE '%" . $request['namaproduk'] . "%'";
        }
        if (isset($request['detailjenisprodukfk']) &&  $request['detailjenisprodukfk'] != '') {
            $detailjenisprodukfk = "and djp.id in (" . $request['detailjenisprodukfk'] . ")";
        }
        if (isset($request['ruanganfk']) &&  $request['ruanganfk'] != '') {
            $ruanganId = "and ru.id =" . $request['ruanganfk'];
        }

        $data = DB::select(DB::raw("
				SELECT
					x.kdproduk,
					x.tglclosing,
					x.namaproduk,
					x.satuanstandar,
					x.namaruangan,
					x.tglkadaluarsa,
					SUM (x.qtyprodukreal) AS qtyprodukreal,
					SUM (x.harganetto1) AS harganetto1,
					SUM (x.total) AS total
				FROM
					(
						SELECT DISTINCT
							pr.id AS kdproduk,
							sp.tglstruk,
							sc.tglclosing,
							pr.namaproduk,
							ss.satuanstandar,
							spd.qtyprodukreal,
							spd.harganetto1,
							spd.qtyprodukreal * spd.harganetto1 AS total,
							ru.namaruangan,
							spdt.tglkadaluarsa
						FROM
							strukclosing_t sc
						LEFT JOIN stokprodukdetailopname_t spd ON spd.noclosingfk = sc.norec
						LEFT JOIN strukpelayanan_t sp ON sp.norec = spd.nostrukterimafk
						LEFT JOIN strukpelayanandetail_t spdt ON spdt.noclosingfk = sc.norec
						LEFT JOIN produk_m pr ON pr.id = spd.objectprodukfk
						LEFT JOIN detailjenisproduk_m djp ON djp.id = pr.objectdetailjenisprodukfk
						LEFT JOIN satuanstandar_m ss ON ss.id = pr.objectsatuanstandarfk
						LEFT JOIN ruangan_m ru ON ru.id = spd.objectruanganfk
						WHERE sc.kdprofile =$idProfile and CAST(sc.tglclosing as DATE) BETWEEN '$tglAwal' AND '$tglAkhir'
                        $ruanganId $namaproduk $detailjenisprodukfk $jenisprodukfk
					) AS x
				GROUP BY
					x.kdproduk,
					x.tglclosing,
					x.namaproduk,
					x.satuanstandar,
					x.namaruangan,
					x.tglkadaluarsa
			"));

        return $this->respond($data);

    }

    public function getStokRuanganSO(Request $request)
    {
        $idProfile = $this->kdProfile;
        $data = DB::table('produk_m as pr')
            ->leftJoin('stokprodukdetail_t as spd', 'pr.id', '=', 'spd.objectprodukfk')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->select(DB::raw('sum(spd.qtyproduk) as qty, pr.id as prid,pr.namaproduk,ss.satuanstandar'))
            ->where('pr.kdprofile', $idProfile)
            ->where('spd.kdprofile', $idProfile)
            ->where('spd.objectruanganfk', $request['ruanganfk'])
            ->groupBy(
                'pr.id',
                'pr.namaproduk',
                'ss.satuanstandar',
            )
            ->orderBy('pr.namaproduk');

        if (isset($request['kelompokprodukid']) && $request['kelompokprodukid'] != "" && $request['kelompokprodukid'] != "undefined") {
            $data = $data->where('jp.objectkelompokprodukfk', '=', $request['kelompokprodukid']);
        }
        if (isset($request['namaproduk']) && $request['namaproduk'] != "" && $request['namaproduk'] != "undefined") {
            $data = $data->where('pr.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('spd.objectruanganfk', '=', $request['ruanganfk']);
        }
        $data = $data->where('pr.statusenabled', true);
        $data = $data->where('spd.statusenabled', true);
        $data = $data->get();
        $data2 = [];
        foreach ($data as $item) {
            $data2[] = array(
                'kodeProduk' => $item->prid,
                'namaProduk' => strtoupper($item->namaproduk),
                'qtyProduk' => $item->qty,
                'satuanStandar' => $item->satuanstandar,
            );
        }
        $result = array(
            'data' => $data2,
            'message' => '@epic',
        );
        return $this->respond($result);
    }

    public function saveStockOpname(Request $request)
    {
        // return $this->respond($request);
        $idProfile = (int) $this->kdProfile;
        ini_set('max_execution_time', 10000);
        $dataReq = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataReq['userData']['id'])
            ->first();
        $datas = array();

        DB::beginTransaction();
        try {
            $noClosing = $this->SEQUENCE(new StrukClosing(), 'noclosing', 10, 'PN/' . $this->getDateTime()->format('ym'), $this->kdProfile);
            $dataSC = new StrukClosing();
            $dataSC->norec = $dataSC->generateNewId();
            $dataSC->kdprofile = $idProfile;
            $dataSC->statusenabled = true;
            $dataSC->objectpegawaidiclosefk = $dataPegawai->objectpegawaifk;
            $dataSC->objectkelompoktransaksifk = 12;
            $dataSC->keteranganlainnya = 'Stock Opname ' . $request['namaRuangan'];
            $dataSC->noclosing = $noClosing;
            $dataSC->objectruangandiclosefk = $request['ruanganId'];
            $dataSC->objectruanganfk = $request['ruanganId'];
            $dataSC->tglclosing = $request['tglClosing'];
            $dataSC->save();
            $norecSC = $dataSC->norec;

            foreach ($request['stokProduk'] as $item) {
                $dataSPDK = StokProdukDetail::where('objectprodukfk', $item['kodeProduk'])
                    ->where('kdprofile', $idProfile)
                    ->where('objectruanganfk', $request['ruanganId'])
                    ->orderby('tglkadaluarsa')
                    ->first();
                if ($dataSPDK == null) {
                    $dataSPDK2 = StokProdukDetail::where('objectprodukfk', $item['kodeProduk'])
                        ->where('kdprofile', $idProfile)
                        ->orderby('tglkadaluarsa')
                        ->first();

                    if (!empty($dataSPDK2) || $dataSPDK2 != null) {

                        $dataSPD = new StokProdukDetailOpname();
                        $dataSPD->norec = $dataSPD->generateNewId();
                        $dataSPD->kdprofile = $idProfile;
                        $dataSPD->statusenabled = true;
                        $dataSPD->objectasalprodukfk = $dataSPDK2->objectasalprodukfk;
                        $dataSPD->hargadiscount = 0;
                        $dataSPD->harganetto1 = $dataSPDK2->harganetto1;
                        $dataSPD->harganetto2 = $dataSPDK2->harganetto2;
                        $dataSPD->persendiscount = 0;
                        $dataSPD->objectprodukfk = $item['kodeProduk'];
                        $dataSPD->qtyprodukreal = $item['qtyReal'];
                        $dataSPD->qtyproduksystem = $item['qtyProduk'];
                        $dataSPD->qtyprodukinext = $item['selisih'];
                        $dataSPD->objectruanganfk = $request['ruanganId'];
                        $dataSPD->noclosingfk = $norecSC;
                        $dataSPD->nostrukterimafk = $dataSPDK2->nostrukterimafk;
                        $dataSPD->save();

                        $dataNewSPD = new StokProdukDetail;
                        $dataNewSPD->norec = $dataNewSPD->generateNewId();
                        $dataNewSPD->kdprofile = $idProfile;
                        $dataNewSPD->statusenabled = true;
                        $dataNewSPD->objectasalprodukfk = $dataSPDK2->objectasalprodukfk;
                        $dataNewSPD->hargadiscount = $dataSPDK2->hargadiscount;
                        $dataNewSPD->harganetto1 = $dataSPDK2->harganetto1;
                        $dataNewSPD->harganetto2 = $dataSPDK2->harganetto2;
                        $dataNewSPD->persendiscount = 0;
                        $dataNewSPD->objectprodukfk = $dataSPDK2->objectprodukfk;
                        $dataNewSPD->qtyproduk = (float)$item['selisih'];
                        $dataNewSPD->qtyprodukonhand = 0;
                        $dataNewSPD->qtyprodukoutext = 0;
                        $dataNewSPD->qtyprodukoutint = 0;
                        $dataNewSPD->objectruanganfk = $request['ruanganId'];
                        $dataNewSPD->nostrukterimafk = $dataSPDK2->nostrukterimafk;
                        $dataNewSPD->noverifikasifk = $dataSPDK2->noverifikasifk;
                        $dataNewSPD->nobatch = $dataSPDK2->nobatch;
                        $dataNewSPD->tglkadaluarsa = $dataSPDK2->tglkadaluarsa;
                        $dataNewSPD->tglpelayanan = $dataSPDK2->tglpelayanan;
                        $dataNewSPD->tglproduksi = $dataSPDK2->tglproduksi;
                        $dataNewSPD->save();

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("select qtyproduk as qty,norec from stokprodukdetail_t
                            where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeProduk'],
                            )
                        );

                        //## KartuStok
                        $this->kartu_STOK(array(
                            "saldoawal" => (float)$item['qtyProduk'],
                            "qtyin" =>   (float)$item['selisih'] > 0 ? (float)$item['selisih'] : 0,
                            "qtyout" => (float)$item['selisih'] < 0 ? (float)$item['selisih'] : 0,
                            "saldoakhir" => $item['qtyReal'],
                            "keterangan" => 'Stock Opname'.$item['namaProduk'].' Ruangan ' . $request['namaRuangan'],
                            "produkfk" =>  $item['kodeProduk'],
                            "ruanganfk" => $request['ruanganId'],
                            "tglinput" => date('Y-m-d H:i:s'),
                            "tglkejadian" => date('Y-m-d H:i:s'),
                            "nostrukterimafk" => $dataSPDK->nostrukterimafk,
                            "norectransaksi" =>  $dataSPDK->norec,
                            "stokprodukdetailfk" => $dataSPDK->norec,
                            "tabletransaksi" => 'stokprodukdetail_t',
                            "flagfk" => 5,
                        ));
                        // $newKS = new KartuStok();
                        // $norecKS = $newKS->generateNewId();
                        // $newKS->norec = $norecKS;
                        // $newKS->kdprofile = $idProfile;
                        // $newKS->statusenabled = true;
                        // $newKS->jumlah = (float)$item['selisih'];
                        // $newKS->keterangan = 'Stock Opname Ruangan ' . $request['namaRuangan'];
                        // $newKS->produkfk = $item['produkfk'];
                        // $newKS->ruanganfk = $request['ruanganId'];
                        // $newKS->saldoawal = (float)$item['selisih'];
                        // $newKS->status = 1;
                        // $newKS->tglinput = date('Y-m-d H:i:s'); //$r_SR['tglresep'];//$r_SR['tglresep']->format('Y-m-d H:i:s');
                        // $newKS->tglkejadian = date('Y-m-d H:i:s'); //$r_SR['tglresep'];// $r_SR['tglresep']->format('Y-m-d H:i:s');
                        // $newKS->nostrukterimafk =  $dataNewSPD->nostrukterimafk;
                        // $newKS->norectransaksi = $dataNewSPD->norec;
                        // $newKS->tabletransaksi = 'stokprodukdetail_t';
                        // $newKS->flagfk = 5;
                        // $newKS->save();
                    } else {
                        $dataBarang = DB::select(
                            DB::raw("select * from produk_m where kdprofile = $idProfile and id=:produkfk"),
                            array(
                                'produkfk' => $item['produkfk'],
                            )
                        );
                        foreach ($dataBarang as $poek) {
                            $datas[] = array(
                                "kdproduk" => $item['produkfk'],
                                "namaproduk" => $poek->namaproduk,
                                "stokSistem" => $item['stokSistem'],
                                "stokReal" => $item['stokReal'],
                                "selisih" => $item['selisih'],
                            );
                        }
                    }
                } else {
                    $dataSPD = new StokProdukDetailOpname();
                    $dataSPD->norec = $dataSPD->generateNewId();
                    $dataSPD->kdprofile = $idProfile;
                    $dataSPD->statusenabled = true;
                    $dataSPD->objectasalprodukfk = $dataSPDK->objectasalprodukfk;
                    $dataSPD->hargadiscount = 0;
                    $dataSPD->harganetto1 = $dataSPDK->harganetto1;
                    $dataSPD->harganetto2 = $dataSPDK->harganetto2;
                    $dataSPD->persendiscount = 0;
                    $dataSPD->objectprodukfk = $item['kodeProduk'];
                    $dataSPD->qtyprodukreal = $item['qtyReal'];
                    $dataSPD->qtyproduksystem = $item['qtyProduk'];
                    $dataSPD->qtyprodukinext = $item['selisih'];
                    $dataSPD->objectruanganfk = $request['ruanganId'];
                    $dataSPD->noclosingfk = $norecSC;
                    $dataSPD->nostrukterimafk = $dataSPDK->nostrukterimafk;
                    $dataSPD->save();

                    //STOK MINUS//
                    $dataStokMinus = DB::select(
                        DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                        where kdprofile = $this->kdProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk and qtyproduk < 0 "),
                        array(
                            'ruanganfk' => $request['ruanganId'],
                            'produkfk' => $item['kodeProduk'],
                        )
                    );

                    StokProdukDetail::where('objectruanganfk', $request['ruanganId'])
                        ->where('kdprofile',)
                        ->where('objectprodukfk', $item['kodeProduk'])
                        ->where('qtyproduk', '<', 0)
                        ->update([
                            'qtyproduk' => 0
                        ]);

                    if (count($dataStokMinus) != 0) {
                        foreach ($dataStokMinus as $items) {
                            $stokMinus = (float)$items->qty;
                        }
                    }
                    $saldoAwal = 0;
                    $jumlah = (float)$item['selisih'] + (float)$stokMinus;

                    if ($jumlah > 0) {
                        $dataStok = DB::select(
                            DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk and qtyproduk>0 limit 1"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeProduk'],
                            )
                        );

                        if (count($dataStok) == 0) {
                            $dataStok = DB::select(
                                DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                                  where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk limit 1"),
                                array(
                                    'ruanganfk' => $request['ruanganId'],
                                    'produkfk' => $item['kodeProduk'],
                                )
                            );
                        }
                        foreach ($dataStok as $items) {
                            StokProdukDetail::where('norec', $items->norec)
                                ->where('kdprofile', $idProfile)
                                ->update(
                                    [
                                        'qtyproduk' => (float)$items->qty + (float)$jumlah
                                    ]
                                );
                        }

                        $dataSTOKDETAIL = DB::select(
                            DB::raw("select qtyproduk as qty,norec from stokprodukdetail_t
                            where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk "),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeProduk'],
                            )
                        );
                    } else {
                        $jumlah = $jumlah * (-1);
                        $dataStok = DB::select(
                            DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeProduk'],
                            )
                        );
                        foreach ($dataStok as $items) {
                            if ((float)$items->qty < $jumlah) {
                                $jumlah = $jumlah - (float)$items->qty;
                                StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->update(
                                        [
                                            'qtyproduk' => 0
                                        ]
                                    );
                            } else {
                                $saldoakhir = (float)$items->qty - $jumlah;
                                $jumlah = 0;
                                StokProdukDetail::where('norec', $items->norec)
                                    ->where('kdprofile', $idProfile)
                                    ->update(
                                        [
                                            'qtyproduk' => (float)$saldoakhir
                                        ]
                                    );
                            }
                        }

                        $dataSTOKDETAIL[] = DB::select(
                            DB::raw("select qtyproduk as qty,norec,nostrukterimafk from stokprodukdetail_t
                        where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                            array(
                                'ruanganfk' => $request['ruanganId'],
                                'produkfk' => $item['kodeProduk'],
                            )
                        );
                    }

                    $dataSaldoAwalK = DB::select(
                        DB::raw("select sum(qtyproduk) as qty from stokprodukdetail_t
                                 where kdprofile = $idProfile and objectruanganfk=:ruanganfk and objectprodukfk=:produkfk"),
                        array(
                            'ruanganfk' => $request['ruanganId'],
                            'produkfk' => $item['kodeProduk'],
                        )
                    );
                    $saldoAwal = 0;
                    foreach ($dataSaldoAwalK as $items) {
                        $saldoAwal = (float)$items->qty - $item['selisih'];
                    }

                    $statusssss = 0;
                    $flagfk = 0;
                    $saldoin = 0;
                    $saldoOut = 0;
                    if ($item['selisih'] < 0) {
                        $statusssss = 0;
                        $selisih = (float)$item['selisih'] * (-1);
                        $saldoOut = $selisih;
                        $flagfk = 5;
                    } else {
                        $statusssss = 1;
                        $selisih = (float)$item['selisih'];
                        $saldoin = $selisih;
                        $saldoOut = 0;
                        $flagfk = 4;
                    }
                    //## KartuStok
                    $this->kartu_STOK(array(
                        "saldoawal" => (float)$saldoAwal,
                        "qtyin" =>  $saldoin,
                        "qtyout" => $saldoOut,
                        "saldoakhir" => $item['qtyReal'],
                        "keterangan" => 'Stock Opname'.$item['namaProduk'].' Ruangan ' . $request['namaRuangan'],
                        "produkfk" =>  $item['kodeProduk'],
                        "ruanganfk" => $request['ruanganId'],
                        "tglinput" => date('Y-m-d H:i:s'),
                        "tglkejadian" => date('Y-m-d H:i:s'),
                        "nostrukterimafk" => $dataSPDK->nostrukterimafk,
                        "norectransaksi" =>  $dataSPDK->norec,
                        "stokprodukdetailfk" => $dataSPDK->norec,
                        "tabletransaksi" => 'stokprodukdetail_t',
                        "flagfk" => $flagfk,
                    ));
                }
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Simpan Berhasil',
                "databarangtaktersave" => $datas,
                "noSO" => $dataSC,
                "detailstok" => $dataSTOKDETAIL,
                "dataSPDK" => $dataSPDK,
                "by" => '@epic',
            );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message"  => 'Simpan Gagal',
                "databarangtaktersave" => $datas,
                "noSO" => $dataSC,
                "detailstok" => $dataSTOKDETAIL,
                "dataSPDK" => $dataSPDK,
                "by" => '@epic',
            );
        }

        return $this->respond($result, $result['status'], $result['message']);

    }
}
