<?php

namespace App\Http\Controllers\Logistik;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDaftarUsulanPermintaan(Request $request)
    {
        $kdProfile = $this->getDataKdProfile($request);
        $idProfile = (int) $kdProfile;
        $dataLogin = $request->all();
        $dataRuangan = DB::table('maploginusertoruangan_s as mlu')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'mlu.objectruanganfk')
            ->select('ru.id')
            ->where('mlu.objectloginuserfk', $this->getPegawaiId())
            ->get();
        $strRuangan = [];
        foreach ($dataRuangan as $epic) {
            $strRuangan[] = $epic->id;
        }

        $data = DB::table('strukorder_t as sp')
            ->JOIN('orderpelayanan_t as op', function ($j) {
                $j->on('op.noorderfk', '=', 'sp.norec')->on('op.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('strukverifikasi_t as sv', function ($j) {
                $j->on('sv.norec', '=', 'sp.objectsrukverifikasifk')->on('sv.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('riwayatrealisasi_t as rr', function ($j) {
                $j->on('rr.objectstrukfk', '=', 'sp.norec')->on('rr.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('strukrealisasi_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'rr.objectstrukrealisasifk')->on('sr.kdprofile', '=', 'rr.kdprofile');
            })
            ->LEFTJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sp.objectpegawaiorderfk')->on('pg.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('pegawai_m as pg2', function ($j) {
                $j->on('pg2.id', '=', 'sp.objectpetugasfk')->on('pg2.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'sp.objectruanganfk')->on('ru.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sp.objectruangantujuanfk')->on('ru2.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('strukkonfirmasi_t as sk', function ($j) {
                $j->on('sk.norec', '=', 'sp.objectkonfirmasifk')->on('sk.kdprofile', '=', 'sp.kdprofile');
            })
            ->LEFTJOIN('strukkonfirmasi_t as sk1', function ($j) {
                $j->on('sk1.norec', '=', 'sp.konfirmasidkfk')->on('sk1.kdprofile', '=', 'sp.kdprofile');
            })
            ->select(
                'sp.norec',
                'sp.tglorder',
                'sp.noorder',
                'pg.namalengkap as penanggungjawab',
                'pg2.namalengkap as mengetahui',
                'sp.tglvalidasi as tglkebutuhan',
                'sp.alamattempattujuan',
                'sp.keteranganlainnya',
                'sp.tglvalidasi',
                'sp.noorderintern',
                'sp.keterangankeperluan',
                'sp.keteranganorder',
                'ru.namaruangan as ruangan',
                'ru.id as ruid',
                'ru2.namaruangan as ruangantujuan',
                'ru2.id as ruidtujuan',
                'sv.tglverifikasi',
                'sv.noverifikasi',
                'sp.totalhargasatuan',
                'sp.status',
                'sr.norec as norecrealisasi',
                'sk.nokonfirmasi',
                'sk.tglkonfirmasi',
                'sk1.nokonfirmasi as nokonfirmasidk',
                'sk1.tglkonfirmasi as tglkonfirmasidk',
                'sp.namarekanansales',
                'sp.keteranganorder'
            )
            ->where('sp.kdprofile', $idProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sp.tglorder', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('sp.tglorder', '<=', $tgl);
        }
        if (isset($request['noorder']) && $request['noorder'] != "" && $request['noorder'] != "undefined") {
            $data = $data->where('sp.noorder', 'ILIKE', '%' . $request['noorder']);
        }
        if (isset($request['noKontrak']) && $request['noKontrak'] != "" && $request['noKontrak'] != "undefined") {
            $data = $data->where('sp.nokontrakspk', 'ILIKE', '%' . $request['noKontrak']);
        }
        if (isset($request['keterangan']) && $request['keterangan'] != "" && $request['keterangan'] != "undefined") {
            $data = $data->where('sp.keteranganorder', 'ILIKE', '%' . $request['keterangan']);
        }
        if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
            $data = $data->where('op.objectprodukfk', '=', $request['produkfk']);
        }

        $data = $data->distinct();
        $data = $data->where('sp.statusenabled', true);
        $data = $data->where('sp.objectkelompoktransaksifk', 89);
        $data = $data->orderBy('sp.tglorder');
        $data = $data->get();

        $results = array();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("
                     select  pr.namaproduk,
                    ss.satuanstandar,spd.qtyproduk,spd.hargasatuan,spd.hargadiscount,spd.hargappn,
                    ((spd.qtyproduk*spd.hargasatuan)-(spd.qtyproduk*spd.hargadiscount))+spd.hargappn as total,
                    spd.tglpelayananakhir as tglkebutuhan,spd.deskripsiprodukquo as spesifikasi,pr.id as prid
                     from orderpelayanan_t as spd
                    left JOIN produk_m as pr on pr.id=spd.objectprodukfk and pr.kdprofile = spd.kdprofile
                    left JOIN satuanstandar_m as ss on ss.id=spd.objectsatuanstandarfk and ss.kdprofile = spd.kdprofile
                    where spd.kdprofile = $idProfile and strukorderfk=:norec"),
                array(
                    'norec' => $item->norec,
                )
            );
            $results[] = array(
                'tglorder' => $item->tglorder,
                'noorder' => $item->noorder,
                'norec' => $item->norec,
                'penanggungjawab' => $item->penanggungjawab,
                'keterangan' => $item->keteranganorder,
                'koordinator' => $item->keteranganlainnya,
                'tglkebutuhan' => $item->tglkebutuhan,
                'tglusulan' => $item->tglorder,
                'nousulan' => $item->noorderintern,
                'namapengadaan' => $item->keterangankeperluan,
                'mengetahui' => $item->mengetahui,
                'ruangan' => $item->ruangan,
                'ruangantujuan' => $item->ruangantujuan,
                'totalhargasatuan' => $item->totalhargasatuan,
                'tglverifikasi' => $item->tglverifikasi,
                'noverifikasi' => $item->noverifikasi,
                'status' => $item->status,
                'details' => $details,
                'norecrealisasi' => $item->norecrealisasi,
                'nokonfirmasi' => $item->nokonfirmasi,
                'tglkonfirmasi' => $item->tglkonfirmasi,
                'nokonfirmasidk' => $item->nokonfirmasidk,
                'tglkonfirmasidk' => $item->tglkonfirmasidk,
                'namarekanansales' => $item->namarekanansales,
                'keteranganorder' => $item->keteranganorder,
            );
        }

        $result = array(
            'daftar' => $results,
            'datalogin' => $dataLogin,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getDataProdukLogistik(Request $request)
    {
        $req = $request->all();
        $dataProduk = DB::table('produk_m as pr')
            ->JOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'pr.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'pr.kdprofile');
            })
            ->JOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->leftJOIN('satuanstandar_m as ss', function ($j) {
                $j->on('ss.id', '=', 'pr.objectsatuanstandarfk')->on('ss.kdprofile', '=', 'pr.kdprofile');
            })
            ->select('pr.id', 'pr.namaproduk', 'ss.id as ssid', 'ss.satuanstandar', 'pr.kdproduk')
            ->where('pr.statusenabled', true)
            ->where('pr.kdprofile', $this->kdProfile)
            ->orderBy('pr.namaproduk');

        if (
            isset($req['namaproduk']) && $req['namaproduk'] != "" && $req['namaproduk'] != "undefined"
        ) {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ILIKE', '%' . $req['namaproduk'] . '%');
        };

        if (
            isset($req['filter']['filters'][0]['value']) &&
            $req['filter']['filters'][0]['value'] != "" &&
            $req['filter']['filters'][0]['value'] != "undefined"
        ) {
            $dataProduk = $dataProduk->where('pr.namaproduk', 'ILIKE', '%' . $req['filter']['filters'][0]['value'] . '%');
        };
        $dataProduk = $dataProduk->take(20);
        $dataProduk = $dataProduk->get();

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


        $dataProdukResult = [];
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
                'ssid' =>   $item->ssid,
                'satuanstandar' =>   $item->satuanstandar,
                'konversisatuan' => $satuanKonversi,
                'kdproduk' => $item->kdproduk,
            );
        }

        return $this->respond($dataProdukResult);
    }

    public function getHargaTerakhir(Request $request)
    {
        $maxHarga = DB::table("stokprodukdetail_t as spd")
                      ->join('produk_m as pr','pr.id','spd.objectprodukfk')
                      ->join('satuanstandar_m as ss','ss.id','pr.objectsatuanstandarfk')
                      ->leftjoin('detailjenisproduk_m as djp','djp.id','pr.objectdetailjenisprodukfk')
                      ->join('strukpelayanandetail_t as spdl','spdl.nostrukfk','spd.nostrukterimafk')
                      ->select(DB::raw("distinct spd.tglpelayanan,spd.harganetto1 as harga,spdl.harganetto,pr.id,pr.kdproduk,pr.namaproduk,ss.satuanstandar"))
                      ->where('spd.kdprofile', $this->kdProfile)
                      ->where('spd.objectprodukfk',$request['produkfk'])
                      ->where('djp.objectjenisprodukfk',97)
                      ->orderBy('spd.tglpelayanan','desc')
                      ->get();

        $dataNyawaTerakhir = [];
        $samateu = false;
        foreach ($maxHarga as $item) {
            $samateu = false;
            foreach ($dataNyawaTerakhir as $itemsss) {
                if ($item->id == $itemsss['id']) {
                    $samateu = true;
                    if ($item->tglpelayanan > date($itemsss['tglpelayanan'])) {
                        $itemsss['harga'] = $item->harga;
                        $itemsss['hargapenerimaan'] = $item->harganetto;
                        $itemsss['tglpelayanan'] = $item->tglpelayanan;
                        break;
                    }
                }
            }
            if ($samateu == false) {
                $dataNyawaTerakhir[] = array(
                    'id' => $item->id,
                    'tglpelayanan' => $item->tglpelayanan,
                    'harga' => $item->harga,
                    'hargapenerimaan' => $item->harganetto,
                    'kdproduk' => $item->kdproduk,
                    'namaproduk' => $item->namaproduk,
                    'satuanstandar' => $item->satuanstandar,
                );
            }
        }


        $result = array(
            'detail' => $dataNyawaTerakhir,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }
}
