<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\Diagnosa;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\EMRPasienForm;
use App\Models\Transaksi\HasilPemeriksaanPcr;
use App\Models\Transaksi\PasienDaftar;
// use Dompdf\Adapter\CPDF::$PAPER_SIZES;
use App\Traits\Valet;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Transaksi\StrukOrder;

class ReportCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function cetakResep(Request $request)
    {
        $kdProfile  = $this->kdProfile;
        $norec      = $request['norec'];
        $params     = '';
        $params2    = '';
        $params3    = '';
        $dataLogin  = $request->all();
        // $idUser     = $dataLogin['userData']['id'];


        if (isset($request['norec']) && $request['norec'] != '' && $request['norec'] != 'null') {
            $params = " and sr.norec='$request[norec]' ";
        } else {
            if (isset($request['norec_order']) &&  $request['norec_order'] != '') {
                return $this->orderResep($request);
            }
        }
        if (isset($request['noregistrasi']) &&  $request['noregistrasi'] != '') {
            $params2 = " and pd.noregistrasi='$request[noregistrasi]' ";
        }


        $profile = Profile::where('id', $this->kdProfile)->first();
        $data = DB::select("
        SELECT DISTINCT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk, pas.nosep, ps.noidentitas as nikpasien,
        rek.namarekanan AS penjamin,ps.nohp AS noteleponfaks,ru.namaruangan as ruanganpengorder, sr.norec as norec_resep,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,sr.tglresep, sr.petugas,
        so.isiter, so.tglorder, pgw.namalengkap,pgw.nosip, kpp.kelompokpasien as kelopokpasien, pgw.noidentitas as nikdokter, pgw2.noidentitas as nikpetugas
        FROM strukresep_t AS sr
        INNER JOIN pasiendaftar_t AS pd ON sr.noregistrasi = pd.noregistrasi
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id and ps.kdprofile = pd.kdprofile
        left join alamat_m as al on al.nocmfk=ps.id and ps.kdprofile = al.kdprofile
        left join pemakaianasuransi_t as pas on pas.noregistrasifk = pd.norec
        INNER JOIN ruangan_m AS ru ON pd.objectruanganlastfk = ru.id
        LEFT JOIN strukorder_t AS so ON so.norec = sr.orderfk
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id and sr.kdprofile = ru2.kdprofile
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id and sr.kdprofile = pgw.kdprofile
        INNER JOIN pegawai_m AS pgw2 ON sr.petugas = pgw2.namalengkap and sr.kdprofile = pgw.kdprofile
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id and pd.kdprofile = kpp.kdprofile
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk  and pd.kdprofile = rek.kdprofile

        WHERE sr.kdprofile = $kdProfile
        and sr.statusenabled=true
        $params
        $params2
        ");

        // return $data;

        $detail = DB::table('pelayananpasien_t as pp')
                    ->leftjoin('produk_m as pr', 'pr.id', 'pp.produkfk')
                    ->leftjoin('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
                    ->leftjoin('satuanstandar_m as sstd', 'sstd.id', 'pp.satuanviewfk')
                    ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
                    ->leftJoin('strukresep_t as sr', 'sr.norec', 'pp.strukresepfk')
                    ->leftJoin('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                    ->leftjoin('pasiendaftar_t as pd', 'pd.noregistrasi', 'pp.noregistrasi')
                    ->leftJoin('kelompokpasien_m as kpp', 'kpp.id', 'pd.objectkelompokpasienlastfk')
                    ->leftJoin('stokprodukdetail_t as spd', 'spd.norec', 'pp.stokprodukdetailfk')
                    ->leftJoin('asalproduk_m AS ap', 'ap.id', '=', 'spd.objectasalprodukfk')
                    ->select(
                        'pp.jumlah',
                        'pp.iskronis',
                        'pp.isreseppulang',
                        'pr.namaproduk AS namaprodukstandar',
                        'djp.detailjenisproduk',
                        'sstd.satuanstandar',
                        'ap.asalproduk',
                        'pp.hargasatuan',
                        'so.namapengambilorder as pengambil',
                        'pp.strukresepfk',
                        DB::raw('(pp.jumlah * (pp.hargasatuan - (CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount END))) + CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS totalharga'),
                        DB::raw('CASE WHEN kpp.kelompokpasien = \'BPJS\' THEN (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) + COALESCE(pp.jasa, 0) ELSE 0 END AS totalplatofon' ),
                        DB::raw('CASE WHEN kpp.kelompokpasien = \'BPJS\' THEN 0 ELSE ((pp.jumlah * pp.hargasatuan) - (CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.qtydetailresep END)) + COALESCE(pp.jasa, 0) END AS totalbiaya'),
                    );
                    // ->where('pp.strukresepfk', $data->first()->norec_resep);
                    if (isset($request['norec']) && $request['norec'] != '') {
                        $detail = $detail->where('sr.norec', $request['norec']);
                    }
                    if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
                        $detail = $detail->where('pd.noregistrasi', $request['noregistrasi']);
                    }
                    $detail = $detail->get();
        if (!$data || empty($data)) {
            return null;
        }
        if (!$detail) {
            return null;
        }

        foreach ($data as $item) {
            // $item->jenis = 'Verif';
            $item->ttde = base64_encode(QrCode::format('svg')->size(75)->generate($item->noresep . "\n" .$item->tglresep . "\n" .$item->nikdokter . "\n" .$item->namalengkap));
            $item->ttdePasien = base64_encode(QrCode::format('svg')->size(75)->generate($item->noresep . "\n" .$item->tglresep . "\n" .$item->nocm . "\n" .$item->nikpasien . "\n" .$item->namapasienjk));
            $item->ttdePetugas = base64_encode(QrCode::format('svg')->size(75)->generate($item->noresep . "\n" .$item->tglresep . "\n" .$item->nikpetugas . "\n" .$item->petugas));
            $item->detail = [];
            foreach ($detail as $itemd) {
                if ($item->norec_resep == $itemd->strukresepfk) {
                    $item->detail[] = $itemd;
                }
            }
        }

        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : false;
        $blade = "report.farmasi.resep-obat";
        if (isset($request['storage']) && $request['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'landscape');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $data,
                    'res' => $res,
                    'profile' => $profile,
                    // 'user' => $this->getNamaPegawai()
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'landscape');
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper([0, 0, 461.55, 841.00],'landscape');
            // $pdf->setPaper('a2');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $data,
                    'res' => $res,
                    'profile' => $profile,
                    'user' => $this->getNamaPegawai()
                )
            );
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper('A5', 'landscape');
            return $pdf->stream();
        }
        return view($blade, compact('dataReport', 'res', 'profile'));
    }

    public function cetakResepObatBebas(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $dataReport = DB::table('strukpelayanan_t as sp')
            ->leftJoin('pasien_m as ps', 'sp.nocmfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->leftJoin('strukpelayanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->leftJoin('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->leftJoin('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->select(
                'djp.detailjenisproduk',
                'ps.nocm',
                'sp.tglfaktur as tgllahir',
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'sp.namapasien_klien',
                'pg.namalengkap',
                'ru.namaruangan',
                'sp.norec',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.aturanpakai',
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                'spd.objectjeniskemasanfk',
                'spd.resepke',
                'spd.ispagi AS pagi',
                'spd.issore AS sore',
                'spd.issiang AS siang',
                'spd.ismalam AS malam',
                'spd.hargasatuan',
                'spd.hargadiscountsave AS discount'
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.norec', $norec)
            ->get();
        // return $dataReport;
        // return response()->json(['data' =>$dataReport]);
        $profile        = Profile::where('id', $this->kdProfile)->first();
        $pageWidth      = 400;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'landscape');
            $pdf->loadView(
                'report.farmasi.cetak-resep-bebas',
                array(
                    'dataReport'    => $dataReport,
                    'pageWidth'     => $pageWidth,
                    'r'             => $request,
                    'request'       => $request,
                    'profile'       => $profile,
                    'user' => $this->getNamaPegawai(),
                    'res'           => array(
                        'pdf' => true
                    ),
                    'profile' => $profile,
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.cetak-resep-bebas',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    public function orderResep($r)
    {

        $profile = Profile::where('id', $this->kdProfile)->first();
        $raw = DB::table('strukorder_t AS st')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'st.noregistrasifk')
            ->join('pasien_m AS pm2', 'pm2.id', '=', 'st.nocmfk')
            ->leftJoin('jeniskelamin_m AS jm', 'jm.id', '=', 'pm2.objectjeniskelaminfk')
            ->leftJoin('pegawai_m AS pm3', 'pm3.id', '=', 'st.objectpegawaiorderfk')
            ->leftJoin('ruangan_m AS rm', 'rm.id', '=', 'st.objectruanganfk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.noregistrasi' , '=', 'pd.noregistrasi')
            ->leftjoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->select(
                'pm2.nocm',
                DB::raw("to_char(pm2.tgllahir, 'dd-mm-yyyy') AS tgllahir"),
                'jm.jeniskelamin',
                'pm2.namapasien',
                'pm3.namalengkap',
                'pm3.nosip',
                'pm3.noidentitas',
                'rm.namaruangan',
                DB::raw("to_char(st.tglorder, 'dd-mm-yyyy MM:ss') AS tglorder"),
                'pm3.nosip',
                'kp.kelompokpasien',
                DB::raw("'' AS apoteker"),
                DB::raw("'' AS sipa"),
                'st.noorder AS noresep',
                'rkn.namarekanan',
                'pd.tinggibadan',
                'pd.beratbadan',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                DB::raw("EXTRACT (YEAR FROM AGE(pd.tglregistrasi,pm2.tgllahir)) || ' Thn ' ||
                      EXTRACT (MONTH FROM AGE(pd.tglregistrasi,pm2.tgllahir)) || ' bln ' ||
                      EXTRACT (day FROM AGE(pd.tglregistrasi,pm2.tgllahir)) || ' hr' as umur")
            )
            ->where('st.norec', '=', $r->norec_order)
            ->where('st.kdprofile', '=', $this->kdProfile)
            ->first();


        $detel = [];

        $details = DB::table('strukorder_t AS st')
            ->join('orderpelayanan_t AS ot', 'ot.strukorderfk', '=', 'st.norec')
            ->join('produk_m AS pm', 'pm.id', '=', 'ot.objectprodukfk')
            ->join('jeniskemasan_m AS jek', 'jek.id', '=', 'ot.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jer', 'jer.id', '=', 'ot.jenisobatfk')
            ->leftJoin('satuanstandar_m AS ss', 'ss.id', '=', 'ot.objectsatuanstandarfk')
            ->select(
                'ot.rke',
                'pm.namaproduk',
                'ot.dosis',
                'ot.jumlah AS jumlah',
                'pm.kekuatan AS kekuatan',
                'ot.aturanpakai',
                'ot.qtykemasan',
                'jek.jeniskemasan as  jeniskemasan',
                'ot.qtyprodukinuse',
                'ot.qtykemasan',
                DB::raw("CASE WHEN ot.jeniskemasanfk = '2' THEN jek.jeniskemasan ELSE jek.jeniskemasan || ' R' || ot.rke || ' Jumlah ' || ot.qtykemasan END AS jenisobat"),
                DB::raw("CASE WHEN ot.jeniskemasanfk = '2' THEN jek.jeniskemasan ELSE jer.namaexternal || ' R' || ot.rke || ' ' || ot.qtykemasan END AS jenisracikan"),
                'ss.satuanstandar'
            )
            ->where('st.norec', '=', $r->norec_order)
            ->where('st.kdprofile', '=', $this->kdProfile)
            ->get();

            $raw->ttde = base64_encode(
                QrCode::format('svg')
                    ->size(75)
                    ->generate(
                        $raw->noresep . "\n" .
                        $raw->tglorder . "\n" .
                        $raw->noidentitas . "\n" .
                        $raw->namalengkap
                    )
            );


        $pageWidth = 800;
        $dataReport = array(
            'user' => $this->getNamaPegawai(),
            'raw' => $raw,
            'detail' => $details,
            'tanggal' => date('Y-m-d H:i:s'),
        );
        if ($r['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setOptions(['defaultFont' => 'Tahoma, sans-serif']);
            $pdf->loadView(
                'report.farmasi.cetak-order-resep',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $r,
                    'details' => $details,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.cetak-order-resep',
                compact('dataReport', 'pageWidth', 'r', 'details', 'profile')
            );
        }
    }

    public function cetakOrderResep(Request $r)
    {
        $kdProfile = $this->kdProfile;
        // $norec = $r['norec'] ?? null;
        $noregistrasi = $r['noregistrasi'];
    
        // // Ambil norec dari strukorder_t berdasarkan noregistrasi
        // $noorder = DB::table("strukorder_t")
        //     ->select('norec')
        //     ->where('noregistrasi', $noregistrasi)
        //     ->where('kdprofile', $kdProfile)
        //     ->first();

        $noorder = StrukOrder::where('noregistrasi', $noregistrasi)->first();


        // Ambil data profile rumah sakit
        $profile = Profile::where('id', $this->kdProfile)->first();

        // Query untuk mengambil data utama (Resep)
        $data = DB::table('strukorder_t AS st')
            ->join('pasiendaftar_t AS pd', 'pd.norec', '=', 'st.noregistrasifk')
            ->join('pasien_m AS pm2', 'pm2.id', '=', 'st.nocmfk')
            ->leftJoin('jeniskelamin_m AS jm', 'jm.id', '=', 'pm2.objectjeniskelaminfk')
            ->leftJoin('pegawai_m AS pm3', 'pm3.id', '=', 'st.objectpegawaiorderfk')
            ->leftJoin('ruangan_m AS rm', 'rm.id', '=', 'st.objectruanganfk')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.noregistrasi' , '=', 'pd.noregistrasi')
            ->leftjoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->select(
                'pm2.nocm',
                DB::raw("to_char(pm2.tgllahir, 'dd-mm-yyyy') AS tgllahir"),
                'jm.jeniskelamin',
                'pm2.namapasien',
                'pm3.namalengkap',
                'pm3.nosip',
                'pm3.noidentitas',
                'rm.namaruangan',
                DB::raw("to_char(st.tglorder, 'dd-mm-yyyy HH:mm') AS tglorder"),
                'pm3.nosip',
                'kp.kelompokpasien',
                DB::raw("'' AS apoteker"),
                DB::raw("'' AS sipa"),
                'st.noorder AS noresep',
                'rkn.namarekanan',
                'st.norec',
                'pd.tinggibadan',
                'pd.beratbadan',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                DB::raw("EXTRACT(YEAR FROM AGE(pd.tglregistrasi, pm2.tgllahir)) || ' Thn ' ||
                        EXTRACT(MONTH FROM AGE(pd.tglregistrasi, pm2.tgllahir)) || ' bln ' ||
                        EXTRACT(DAY FROM AGE(pd.tglregistrasi, pm2.tgllahir)) || ' hr' AS umur")
            )
            // ->where('st.norec', '=', '15a1d5b1-f8d4-494d-83f9-52ee080a5edb')
            // ->where('st.norec', '=', $noorder)
            ->where('st.noregistrasi', '=', $r['noregistrasi'])
            ->where('st.kdprofile', '=', $this->kdProfile)
            ->first();

        // Query untuk mengambil detail resep
        $details = DB::table('strukorder_t AS st')
            ->join('orderpelayanan_t AS ot', 'ot.strukorderfk', '=', 'st.norec')
            ->join('produk_m AS pm', 'pm.id', '=', 'ot.objectprodukfk')
            ->join('jeniskemasan_m AS jek', 'jek.id', '=', 'ot.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jer', 'jer.id', '=', 'ot.jenisobatfk')
            ->leftJoin('satuanstandar_m AS ss', 'ss.id', '=', 'ot.objectsatuanstandarfk')
            ->select(
                'ot.rke',
                'pm.namaproduk',
                'ot.dosis',
                'ot.jumlah AS jumlah',
                'pm.kekuatan AS kekuatan',
                'ot.aturanpakai',
                'ot.qtykemasan',
                'jek.jeniskemasan as jeniskemasan',
                'ot.qtyprodukinuse',
                'ot.qtykemasan',
                'st.norec',
                DB::raw("CASE WHEN ot.jeniskemasanfk = '2' THEN jek.jeniskemasan ELSE jek.jeniskemasan || ' R' || ot.rke || ' Jumlah ' || ot.qtykemasan END AS jenisobat"),
                DB::raw("CASE WHEN ot.jeniskemasanfk = '2' THEN jek.jeniskemasan ELSE jer.namaexternal || ' R' || ot.rke || ' ' || ot.qtykemasan END AS jenisracikan"),
                'ss.satuanstandar'
            )
            // ->where('st.norec', '=', '15a1d5b1-f8d4-494d-83f9-52ee080a5edb')
            // ->where('st.norec', '=', $noorder)
            ->where('st.noregistrasi', '=', $r['noregistrasi'])
            ->where('st.kdprofile', '=', $this->kdProfile)
            ->get();

            if (!$data || empty($data)) {
                return null;
            }
            if (!$details) {
                return null;
            }

        // Generate QR Code
        $data->ttde = base64_encode(
            QrCode::format('svg')
                ->size(75)
                ->generate(
                    $data->noresep . "\n" .
                    $data->tglorder . "\n" .
                    $data->noidentitas . "\n" .
                    $data->namalengkap
                )
        );

        // Data untuk laporan
        $pageWidth = 800;
        $user = $this->getNamaPegawai();
        $dataDetails = [
            'detail' => $details,
        ];
        $dataReport = [
            // 'user' => $this->getNamaPegawai(),
            'data' => $data,
            // 'detail' => $details,
            // 'tanggal' => date('Y-m-d H:i:s'),
        ];

        $res['pdf'] = isset($r['pdf']) ? $r['pdf'] : false;
        $blade = "report.farmasi.cetak-order-resep1";

        // Jika ingin menyimpan sebagai PDF
        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, [
                'dataReport' => $dataReport,
                'res' => $res,
                'profile' => $profile,
                'user' => $this->getNamaPegawai(),
                'dataDetails' => $dataDetails,
            ]);
            return $pdf; // Menyimpan sebagai PDF
        }

        // Jika ingin menampilkan sebagai PDF di browser
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, [
                'dataReport' => $dataReport,
                'res' => $res,
                'profile' => $profile,
                'user' => $this->getNamaPegawai(),
                'dataDetails' => $dataDetails,
            ]);
            return $pdf->stream();
        }

        
        // Jika ingin menampilkan sebagai tampilan HTML biasa
        return view($blade, compact('dataReport', 'dataDetails', 'pageWidth', 'res', 'user','profile'));
    }


    public function cetakCopyResep(Request $request)
    {
        $arrayOP = explode(',', $request->norec_op);
        $profile = Profile::where('id', $this->kdProfile)->first();
        $apoteker = Pegawai::select('namalengkap as nama', 'nosipa')->where('id', $request['apoteker'])
            ->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->first();
        $noorder = $request['norec_order'];
        $Iter = isset($request['Iter']) ? $request['Iter'] : null;
        $pageWidth = 950;
        $identitas = collect(DB::select("
            select pm2.nocm ,to_char(pm2.tgllahir,'dd-mm-yyyy') as tgllahir,age(pm2.tgllahir) as umur ,jm.jeniskelamin ,pm2.namapasien ,pm3.namalengkap, rm.namaruangan,
                   to_char(st.tglorder,'dd-mm-yyyy MM:ss') as tglorder,pm3.nosip, kp.kelompokpasien,'-' AS apoteker, '-' AS sipa,st.noorder as noresep
            from strukorder_t st
            inner join pasien_m pm2 on pm2.id = st.nocmfk
            inner join jeniskelamin_m jm on jm.id = pm2.objectjeniskelaminfk
            inner join pegawai_m pm3 on pm3.id = st.objectpegawaiorderfk

            inner join ruangan_m rm on rm.id = st.objectruanganfk
            inner join pasiendaftar_t AS pd ON pd.norec = st.noregistrasifk
            left join kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
            where st.norec = '$noorder'
        "))->first();
        if (empty($identitas)) {
            $identitas = collect(DB::select("
                select pm2.nocm,to_char(pm2.tgllahir,'dd-mm-yyyy') as tgllahir,age(pm2.tgllahir) as umur,jm.jeniskelamin,
                       pm2.namapasien,pm3.namalengkap,rm.namaruangan,to_char(st.tglresep,'dd-mm-yyyy MM:ss') as tglorder,
                       pm3.nosip, kp.kelompokpasien,'-' AS apoteker, '-' AS sipa,st.noresep
                from strukresep_t st
                INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = st.pasienfk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                inner join pasien_m pm2 on pm2.id = pd.nocmfk
                inner join jeniskelamin_m jm on jm.id = pm2.objectjeniskelaminfk
                inner join pegawai_m pm3 on pm3.id = st.penulisresepfk

                inner join ruangan_m rm on rm.id = st.ruanganfk
                left join kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                where st.norec = '$request[norec_resep]'
            "))->first();
        }

         // Update keterangan in orderpelayanan_t based on ot.norec
        if ($request->has('keterangan')) {
            DB::table('orderpelayanan_t')
                ->whereIn('norec', explode(',', $request->norec_op))
                ->update(['keterangancopyresep' => $request->keterangan]);
        }

        $details = DB::table('strukorder_t as st')
            ->join('orderpelayanan_t as ot', 'ot.strukorderfk', 'st.norec')
            ->join('produk_m as pm', 'pm.id', 'ot.objectprodukfk')
            ->join('jeniskemasan_m AS jek', 'jek.id', '=', 'ot.jeniskemasanfk')
            ->select(DB::raw(
                "ot.rke,pm.namaproduk,ot.dosis,pm.kekuatan,ot.jumlah as jumlah,ot.aturanpakai,
                CASE WHEN ot.iter IS NULL THEN '' ELSE ot.iter END AS iter,ot.keteranganpakai,ot.objectprodukfk,jek.jeniskemasan as  jeniskemasan,
                ot.keterangancopyresep"
            ))
            ->where('st.norec', $request['norec_order'])
            ->whereIn('ot.norec', $arrayOP)
            ->get();

        if (count($details) == 0) {
            $details = DB::select(DB::raw("
                select ot.rke,pm.namaproduk,ot.dosis,pm.kekuatan ,ot.jumlah as jumlah,
                       ot.aturanpakai,CASE WHEN ot.iter IS NULL THEN '' ELSE ot.iter END AS iter,ot.keteranganpakai,ot.keterangancopyresep,
                       ot.produkfk AS objectprodukfk,jek.jeniskemasan as  jeniskemasan
                from strukresep_t st
                inner join pelayananpasien_t ot on ot.strukresepfk = st.norec
                inner join produk_m pm on pm.id = ot.produkfk
                left  join jeniskemasan_m as jek on  jek.id = ot.jeniskemasanfk
                where st.norec = '$request[norec_resep]'
            "));
        }


        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
            'details' => $details,
        );

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setOptions(['defaultFont' => 'Arial, Helvetica, sans-serif']);
            $pdf->loadView(
                'report.farmasi.copy-resep',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'apoteker' => $request['apoteker'] ? $apoteker : $this->getPegawai(),
                    'Iter' => $Iter,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.copy-resep',
                compact('dataReport', 'pageWidth', 'profile', 'Iter')
            );
        }
    }
    public function apotikRekapLabelKecilObatBebas(Request $request)
    {
        $dataRacikan = DB::table('strukpelayanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->LEFTJOIN('strukpelayanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->LEFTJOIN('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->LEFTJOIN('satuanresep_m AS sr', 'sr.id', 'spd.satuanresepfk')
            ->LEFTJOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
            // ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'spd.objectjeniskemasanfk',
                'sp.namapasien_klien as namapasien',
                'pg.namalengkap',
                'sp.tglfaktur as tgllahir',
                'sp.nostruk_intern as nocm',
                'ru.namaruangan',
                'sp.norec',
                'jer.jeniskemasan',
                'sr.satuanresep',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.aturanpakai',
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                DB::raw(
                    "
                CASE WHEN spd.ispagi = true THEN 'Pagi' ELSE '' END AS pagi,
                CASE WHEN spd.issiang = true THEN 'Siang' ELSE '' END AS siang,
                CASE WHEN spd.issore = true THEN 'Sore' ELSE '' END AS sore,
                CASE WHEN spd.ismalam = true THEN 'Malam' ELSE '' END AS malam"
                )
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('spd.objectjeniskemasanfk', 1)
            ->where('sp.norec', $request->norec)
            ->get();

        $racikan = count($dataRacikan) > 0 ? $dataRacikan : 0;

        $dataNonRacikan = DB::table('strukpelayanan_t as sp')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenanggungjawabfk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->LEFTJOIN('strukpelayanandetail_t as spd', 'sp.norec', 'spd.nostrukfk')
            ->LEFTJOIN('produk_m as pr', 'pr.id', 'spd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m AS ss', 'ss.id', 'spd.objectsatuanstandarfk')
            ->LEFTJOIN('satuanresep_m AS sr', 'sr.id', 'spd.satuanresepfk')
            ->LEFTJOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'spd.objectjeniskemasanfk')
            // ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->select(
                'sp.tglstruk',
                'sp.nostruk',
                'sp.nostruk_intern',
                'spd.objectjeniskemasanfk',
                'sp.namapasien_klien as namapasien',
                'pg.namalengkap',
                'sp.tglfaktur as tgllahir',
                'sp.nostruk_intern as nocm',
                'ru.namaruangan',
                'sp.norec',
                'jer.jeniskemasan',
                'sr.satuanresep',
                'sp.nocmfk as nocm',
                'sp.noteleponfaks',
                'sp.namatempattujuan',
                'sbm.nosbm',
                'sp.totalharusdibayar',
                'sp.namakurirpengirim',
                'sp.namapasien_klien AS namapasien',
                'sp.nostruk',
                'pr.namaproduk',
                'spd.tglkadaluarsa',
                'spd.qtyproduk as qty',
                'spd.aturanpakai',
                'ss.satuanstandar AS satuan',
                'spd.keteranganpakai',
                DB::raw(
                    "
            CASE WHEN spd.ispagi = true THEN 'Pagi' ELSE '' END AS pagi,
            CASE WHEN spd.issiang = true THEN 'Siang' ELSE '' END AS siang,
            CASE WHEN spd.issore = true THEN 'Sore' ELSE '' END AS sore,
            CASE WHEN spd.ismalam = true THEN 'Malam' ELSE '' END AS malam"
                )
            )
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('spd.objectjeniskemasanfk', 2)
            ->where('sp.norec', $request->norec)
            ->get();

        $nonRacikan = count($dataNonRacikan) > 0 ? $dataNonRacikan : 0;

        $profile        = Profile::where('id', $this->kdProfile)->first();
        $pageWidth      = '500p';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 327.15, 290.40]);
            $pdf->loadView(
                'report.farmasi.rekap-label-kecil-bebas',
                array(
                    'nonRacikan' => $nonRacikan,
                    'racikan'    => $racikan,
                    'pageWidth'     => $pageWidth,
                    'r'             => $request,
                    'request'       => $request,
                    'profile'       => $profile,
                    'res'           => array(
                        'pdf' => true
                    ),
                    'profile' => $profile,
                )
            );

            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label-kecil-bebas',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }
    public function apotikRekapLabelKecil(Request $request)
    {

        $dataRacikan = DB::table('strukresep_t AS sr')->select(
            'pd.tglregistrasi',
            'ps.nocm',
            'ps.namapasien',
            'ps.tgllahir',
            'pr.namaproduk',
            'ss.satuanstandar',
            'sr.tglresep',
            'sr.noresep',
            'pp.jumlah',
            'jer.jeniskemasan as  jeniskemasan',
            DB::raw("CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi"),
            DB::raw("CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang"),
            DB::raw("CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore"),
            DB::raw("CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam"),
            'pp.keteranganpakai',
            'jrc.jenisracikan',
            'sn.satuanresep',
            'pp.aturanpakai',
            'pp.tglkadaluarsa'
        )
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoin('satuanresep_m as sn', 'pp.satuanresepfk', '=', 'sn.id')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('jer.jeniskemasan', 'Racikan')
            ->where('pd.norec', $request['norecpd'])
            ->get();

        $racikan = count($dataRacikan) > 0 ? $dataRacikan : 0;

        // if (isset($request['norecpd'])) {
        //     return $this->cetakLabelRacikanResep($request);
        // }
        $profile = Profile::where('id', $this->kdProfile)->first();
        $norec = $request['norec'];
        $orderfk = $request['orderfk'];

        $pageWidth  = '500p';
        $produkfk = '';

        if ($request['orderfk']) {
            $dataNonRacikan = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,jer.jeniskemasan,
            pr.namaproduk,ss.satuanstandar,sr.tglresep,ss.id as ssid,
            sr.noresep,pp.jenisracikanfk,
            pp.jumlah,CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi,
            CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang,
            CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore,
            CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam,
            pp.keteranganpakai,sn.satuanresep,pp.aturanpakai,pp.tglkadaluarsa
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
            join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
            join jeniskemasan_m AS jer on jer.id = pp.jeniskemasanfk
            join produk_m as pr on pr.id=pp.produkfk
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
            join pasien_m as ps  on ps.id=pd.nocmfk
            left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
            left join satuanresep_m as sn on pp.satuanresepfk =sn.id
            where sr.kdprofile = $this->kdProfile
            and sr.orderfk='$orderfk'
            and pp.jeniskemasanfk = 2
            and ss.id = 501
            "));
        } else {
            $dataNonRacikan = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,
            pr.namaproduk,ss.satuanstandar,sr.tglresep,
            sr.noresep,ss.id as ssid,pp.rke,
            pp.jumlah,CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi,
            CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang,
            CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore,
            CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam,
            jer.jeniskemasan,
            pp.keteranganpakai,sn.satuanresep,pp.aturanpakai,pp.tglkadaluarsa,pp.jenisracikanfk
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
            join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
            join jeniskemasan_m AS jer on jer.id = pp.jeniskemasanfk
            join produk_m as pr on pr.id=pp.produkfk
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
            join pasien_m as ps  on ps.id=pd.nocmfk
            left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
            left join satuanresep_m as sn on pp.satuanresepfk =sn.id
            where sr.kdprofile = $this->kdProfile
            and pp.jeniskemasanfk = 2
            and sr.norec='$norec'
            "));
        }
        // return $dataNonRacikan;
        $nonRacikan = count($dataNonRacikan) > 0 ? $dataNonRacikan : 0;
        // return $nonRacikan;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 323.15, 290.40]);
            $pdf->setPaper([0, 0, 327.15, 290.40]);
            $pdf->loadView(
                'report.farmasi.rekap-label-kecil',
                array(
                    'nonRacikan' => $nonRacikan,
                    'racikan' => $racikan,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label-kecil',
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    public function apotikCetakNama(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        $profile = collect(DB::select("
                select * from profile_m where id = $kdProfile limit 1
            "))->first();

        $norec = $request['norec'];
        $dataReport = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.nohp,ps.tgllahir,ps.alamatrmh,sr.noresep,aa.noantri,
            jk.jeniskelamin,ru.namaruangan,pd.noregistrasi,kp.kelompokpasien,aa.jenis,TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') as umur
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk and apd.kdprofile =sr.kdprofile
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            join jeniskelamin_m as jk  on jk.id=ps.objectjeniskelaminfk and pd.kdprofile =jk.kdprofile
            left join antrianapotik_t as aa on aa.noresep =sr.noresep  and sr.kdprofile =aa.kdprofile
            join ruangan_m as ru on ru.id =sr.ruanganfk  and sr.kdprofile =ru.kdprofile
            left join kelompokpasien_m as kp on kp.id =pd.objectkelompokpasienlastfk  and pd.kdprofile =kp.kdprofile
            where sr.kdprofile=$kdProfile
            and sr.norec='$norec'
        "))->first();

        $statusonline = "";
        $status = "Kartu ini adalah bukti antrian resep anda";


        $pageWidth = '500p';
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'tglregistrasi' => date('d-M-Y H:i', strtotime($dataReport->tglregistrasi)),
            'noregistrasi' => $dataReport->noregistrasi,
            'norm' => $dataReport->nocm,
            'umur' => $dataReport->umur,
            'alamatrmh' => $dataReport->alamatrmh,
            'tgllahir' => $dataReport->tgllahir,
            'nohp' => $dataReport->nohp,
            'namapasien' => $dataReport->namapasien,
            'jeniskelamin' => $dataReport->jeniskelamin,
            'ruangan' => $dataReport->namaruangan,
            'noantrian' => $dataReport->jenis . '-' . $dataReport->noantri,
            'kelompokpasien' => $dataReport->kelompokpasien,
            'status' => $status,
        );


        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 327.15, 290.40]);
            $pdf->loadView(
                'report.farmasi.cetak-nama-pasien',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.antrian-apotik',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakAntrianKiosk(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $norec = $request['norec'];
        $TglAwal =  date('Y-m-d ') . '00:00';
        $TglAkhir = date('Y-m-d ') . '23:59';
        $tglAyeuna = date('Y-m-d H:i:s');
        $profile = collect(DB::select("
            select * from profile_m where id = $kdProfile AND statusenabled = true limit 1
            "))->first();

        $antrian = DB::table('antrianpasienregistrasi_t AS apr')
            ->leftJoin('ruangan_m AS ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('mapdepotoruangan_t as mdr', 'mdr.objectruanganfk', 'apr.objectruanganfk')
            ->leftjoin('ruangan_m as depo', 'depo.id', 'mdr.objectdepofk')
            ->leftjoin('pasien_m as pas', 'apr.nobpjs', 'pas.nobpjs')
            ->select(DB::raw("apr.*,pas.nocm,CASE WHEN ru.namaruangan IS NULL
                                        THEN '' ELSE ru.namaruangan END AS namaruangan, depo.namaruangan as nama_depo"))
            ->where('apr.norec', '=', $norec)
            ->where('mdr.statusenabled', true)
            ->first();

        $str = $antrian->jenis;
        $jmlAntrian = DB::table('antrianpasienregistrasi_t')
            ->select(DB::raw("count(noantrian) as jmlantri"))
            ->where('statuspanggil', '=', 0)
            ->whereBetween('tanggalreservasi', [$TglAwal, $TglAkhir])
            ->where('jenis', '=', $str)
            ->first();

        $noAntrian = $antrian->noantrian;
        $strJenis = $antrian->jenis;
        $jenis = "";
        if ($antrian->nobpjs == null) {
            $jenis = "NON BPJS";
        } else {
            $jenis = "BPJS";
        }
        if (strlen($antrian->noantrian) == 1) {
            $noAntrian = "00" . $antrian->noantrian;
        } else {
            $noAntrian = "0" . $antrian->noantrian;
        }
        $pageWidth = 250;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'jenis' => $jenis,
            'noantrian' => $strJenis . "-" . $noAntrian,
            'jmlantrian' => $jmlAntrian->jmlantri,
            // 'pageWidth'  => 365,
            'pageWidth'  => 455,
            'tanggal'  => $tglAyeuna,
            'namapasien'  => $antrian->namapasien,
            'nocm'  => $antrian->nocm,
            'nobpjs'  => $antrian->nobpjs,
            'namaruangan'  => $antrian->namaruangan,
            'depo' => $antrian->nama_depo,
            'loket'  => $antrian->loketkiosk,
            'jenisPasien' => $request['jenisPasien']
        );

        //        $pdf = PDF::loadView('report.pendaftaran.antrian', array(
        //                'dataReport' => $dataReport,
        //        ));
        //        return $pdf->stream();

        if (isset($request['pdf']) && $request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.registrasi.antrian-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'dataReport' => $dataReport,

                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.registrasi.antrian',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakBuktiPendaftaran(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $profile = collect(DB::select("
                 select * from profile_m where id = $kdProfile limit 1
            "))->first();
        // dd($request['norec_pd']);
        $registrasi = DB::table('pasiendaftar_t AS pd')
            ->Join('antrianpasiendiperiksa_t as apdp', 'apdp.noregistrasifk', '=', 'pd.norec')
            ->Join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as ap', 'ap.nocmfk', '=', 'ps.id')
            ->leftJoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftJoin('pegawai_m as pp', 'pd.objectpegawaifk', '=', 'pp.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftJoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->select(DB::raw("pd.noregistrasi,ps.nocm,ps.tgllahir,ps.namapasien,to_char(pd.tglregistrasi, 'DD-MM-YYYY HH:mm') AS tglregistrasi,jk.reportdisplay AS jk,
                     ap.alamatlengkap,ap.mobilephone2,ru.namaruangan AS ruanganperiksa,pp.namalengkap AS namadokter,
                     kp.kelompokpasien,apdp.noantrian,pd.statuspasien,apr.noreservasi,CASE WHEN apr.tanggalreservasi IS NULL THEN ''
                     ELSE to_char(apr.tanggalreservasi, 'DD-MM-YYYY HH:mm') END AS tanggalreservasi"))
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->orWhere('pd.norec', $request['norec_pd'])
            ->first();



        $statusonline = "";
        $status = "";
        if ($registrasi->tanggalreservasi != '') {
            $statusonline = "PASIEN ONLINE";
            $status = "Kartu ini adalah bukti anda mendaftar hari ini";
        } else {
            $statusonline = "Kartu ini adalah bukti anda mendaftar hari ini";
        }
        $pageWidth = 365;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'tglregistrasi' => $registrasi->tglregistrasi,
            'noregistrasi' => $registrasi->noregistrasi,
            'norm' => $registrasi->nocm,
            'tgllahir' => $registrasi->tgllahir,
            'namapasien' => $registrasi->namapasien,
            'jeniskelamin' => $registrasi->jk,
            'alamatlengkap' => $registrasi->alamatlengkap,
            'mobilephone2' => $registrasi->mobilephone2,
            'ruangan' => $registrasi->ruanganperiksa,
            'namadokter' => $registrasi->namadokter,
            'kelompokpasien' => $registrasi->kelompokpasien,
            'noantrian' => $registrasi->noantrian,
            'statuspasien' => $registrasi->statuspasien,
            'noreservasi' => $registrasi->noreservasi,
            'tanggalreservasi' => $registrasi->tanggalreservasi,
            'statusonline' => $statusonline,
            'status' => $status,
        );

        if (isset($request['pdf']) && $request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.registrasi.buktipendaftaran-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'dataReport' => $dataReport,

                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.registrasi.buktipendaftaran',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakSuratKeteranganSehat(Request $request)
    {
        $noreg = $request['noregistrasi'];
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 950;
        $identitas = collect(DB::select("
            SELECT
            sk.*,
            pg2.namalengkap as namapembuat,
            jp.jenispegawai as jabatan,
            pm.namapasien,
            pm.nocm,
            pm.noidentitas,
            pm.tempatlahir,
            pm.tgllahir,
            jk.jeniskelamin,
            pd.tglregistrasi,
            al.alamatlengkap,
            pj.pekerjaan,
            pg.namalengkap as dokterdpjp,
            pg.nosip
            FROM suratketerangan_t sk
            INNER JOIN pasiendaftar_t pd on pd.norec = sk.pasiendaftarfk and pd.kdprofile = sk.kdprofile
            INNER JOIN pasien_m pm on pm.id = pd.nocmfk and pm.kdprofile = pd.kdprofile
            LEFT JOIN jeniskelamin_m jk on pm.objectjeniskelaminfk = jk.id and jk.kdprofile = pm.kdprofile
            LEFT JOIN pekerjaan_m pj on pj.id = pm.objectpekerjaanfk and pj.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m al on pm.id = al.nocmfk and al.kdprofile = pm.kdprofile
            LEFT JOIN pegawai_m pg on pg.id = sk.dokterfk and pg.kdprofile = sk.kdprofile
            LEFT JOIN pegawai_m pg2 on pg2.id = sk.pegawaifk and pg2.kdprofile = sk.kdprofile
            LEFT JOIN jenispegawai_m jp on jp.id = pg2.objectjenispegawaifk and jp.kdprofile = pg2.kdprofile
            WHERE pd.noregistrasi = ?
            AND sk.jenissuratfk = ?
            and pd.kdprofile = ?
        ", [$noreg, $kdJenisSurat, $this->kdProfile]))->first();
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
        );
        // return $dataReport;
        return view(
            'report.pendaftaran.suratsehat',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakLembarRawatInap(Request $request)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'pd.noregistrasi as  noreg',
                'ps.namapasien as nama',
                'rk.desakelurahan as kelurahan',
                'rk.kecamatan',
                'rk.kotakabupaten',
                'rk.alamatlengkap',
                'kp.kelompokpasien',
                'ps.nocm',
                'pd.*'

            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.norec', $request['noregistrasi'])
            ->first();

        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddp.noregistrasifk')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'jd.jenisdiagnosa',
                'ddp.keterangan',
                DB::raw("'ICD X' as jenis")
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $data->norec)
            ->orderByDesc('ddp.tglinputdiagnosa')
            ->get();
        $diagnosaIX = DB::table('detaildiagnosatindakanpasien_t as ddt')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ddt.noregistrasifk')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->select(
                'dt.kddiagnosatindakan as kddiagnosa',
                'dt.namadiagnosatindakan as namadiagnosa',
                'ddt.keterangantindakan as keterangan',
                'ddt.tglinputdiagnosa',
                DB::raw("'ICD IX' as jenis, null as jenisdiagnosa")
            )
            ->where('ddt.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $data->norec)
            ->orderByDesc('ddt.tglinputdiagnosa')
            ->get();

        $diagnosa = array_merge($diagnosaX->toArray(), $diagnosaIX->toArray());


        $pageWidth = 890;
        $blade = 'report.pendaftaran.cetak-lemabar-rawat-inap';
        $res['profile'] = Profile::where('id', $this->getDataKdProfile($request))->first();
        $res['pdf'] = 'true';
        $res['storage']  = true;
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            $blade,
            array(
                'data' => $data,
                'res' => $res,
                'pageWidth' => $pageWidth,
                'penaggungJawab' => $request->penaggungJawab,
                'alamat' => $request->alamat,
                'diagnosa' => $diagnosa
            )
        );
        return $pdf->stream();
    }

    public static function getUmurna($tgllahir, $tglregis)
    {
        $data = DB::select(DB::raw("
            SELECT
            EXTRACT (YEAR FROM AGE('$tglregis', '$tgllahir' )) || ' Tahun ' as thnumur,
            EXTRACT (MONTH  FROM AGE('$tglregis', '$tgllahir' )) || ' Bulan ' as blnumur,
            EXTRACT (DAY  FROM  AGE('$tglregis', '$tgllahir' )) || ' Hari' as hrumur
        "));
        $res['umurtahun'] = $data[0]->thnumur;
        $res['umurbulan'] = $data[0]->blnumur;
        $res['umurhari'] = $data[0]->hrumur;
        return $res;
    }

    public function cetakGelangPasien(Request $request)
    {
        $kdProfile = (int)$this->getDataKdProfile($request);
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m AS jk', function ($join) {
                $join->on('jk.id', '=', 'pm.objectjeniskelaminfk')
                    ->on('jk.kdprofile', '=', 'pm.kdprofile');
            })
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'jk.reportdisplay as jeniskelamin',
                'pm.tgllahir',
                'pd.tglregistrasi'

            )
            ->where('pd.noregistrasi', '=', $request['noregistrasi'])
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 50;
        $height = 600;
        $blade = 'report.pendaftaran.gelangpasien';
        $pdf = App::make('dompdf.wrapper');
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 200, 80]);
            $pdf->loadView(
                $blade,
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data
                )
            );
            // $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.gelangpasien',
                compact('res', 'pageWidth', 'data')
            );
        }
    }

    public function cetakSuratKematian(Request $request)
    {

        $raw = DB::table('pasiendaftar_t as ep')
            ->join('pasien_m AS ps', 'ps.id', '=', 'ep.nocmfk')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'ep.norec')
            ->join('suratketerangan_t as sk', 'sk.pasiendaftarfk', '=', 'ep.norec')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'ep.objectruanganlastfk')
            ->leftJoin('pegawai_m as pgl', 'pgl.id', '=', 'ep.objectpegawaifk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('penyebabkematian_m  as pk', 'pk.id', 'ep.objectpenyebabkematianfk')
            ->where('ep.norec', $request['norec'])
            ->select(
                'ps.namapasien',
                'ep.noregistrasi',
                'ps.noidentitas',
                'ps.tempatlahir',
                'ps.nocm',
                "ps.tgllahir AS tgllahir",
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'kbs.name',
                'ep.nosuratmeninggal as nosurat',
                // 'sk.nosurat',
                'sk.nosint',
                'ps.tglmeninggal',
                'ru.namaruangan',
                'sk.diagnosa',
                'sk.keterangan',
                'ep.tglregistrasi',
                // 'sk.nosurat',
                'pgl.namalengkap',
                'pk.reportdisplay as kematian'
            )
            ->orderBy('sk.tglsurat', 'DESC')
            ->first();

        $dataForensik = DB::table('pasiendaftar_t as pd')
                        ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasi', 'pd.noregistrasi')
                        ->leftjoin('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
                        ->leftjoin('pegawai_m as pg', 'pg.id', 'ppp.objectpegawaifk')
                        ->select('pg.namalengkap')
                        ->where('pd.norec', $request['norec'])
                        ->where('pp.produkfk', 1002130843)
                        ->first();

        $date1 = new DateTime($raw->tglmeninggal);
        $date2 = new DateTime($raw->tglregistrasi);

        $interval = $date1->diff($date2);
        $res['pdf'] = 'true';
        $res['storage']  = true;
        $pageWidth = 780;
        $tgl_lahir = Carbon::parse($raw->tgllahir);
        $umur = $tgl_lahir->diff(Carbon::now());

        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($dataForensik->namalengkap) {
            $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($dataForensik->namalengkap ?? ""));
        } else {
            $qrcode = null;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.suratkematian',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'raw' => $raw,
                    'dataForensik' => $dataForensik,
                    'umur' => $umur,
                    'interval' => $interval,
                    'qrcode' => $qrcode
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.suratkematian',
                compact('res', 'pageWidth', 'raw', 'dataForensik', 'interval', 'umur', 'qrcode')
            );
        }
    }
    public function cetakSuratMeninggal(Request $request)
    {

        $norec_sk = $request['noregistrasi'];
        $type = $request->type ?? "LABORATORIUM";

        $raw = DB::table('pasiendaftar_t as ep')
            ->join('pasien_m AS ps', 'ps.id', '=', 'ep.nocmfk')
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'ep.norec')
            ->join('suratketerangan_t as sk', 'sk.pasiendaftarfk', '=', 'ep.norec')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('kebangsaan_m as kbs', 'kbs.id', '=', 'ps.objectkebangsaanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'ep.objectruanganlastfk')
            ->leftJoin('pegawai_m as pgl', 'pgl.id', '=', 'ep.objectpegawaifk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->where('ep.norec', $request['norec'])
            ->select(
                'ps.namapasien',
                'ep.noregistrasi',
                'ps.noidentitas',
                'ps.tempatlahir',
                "ps.tgllahir AS tgllahir",
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'kbs.name',
                'sk.nosurat',
                'sk.nosint',
                'ps.tglmeninggal',
                'ru.namaruangan',
                'sk.diagnosa',
                'sk.keterangan',
                'ep.tglregistrasi',
                'sk.nosurat',
                'pgl.namalengkap'
            )
            ->orderBy('sk.tglsurat', 'DESC')
            ->first();

        $tgl_lahir = Carbon::parse($raw->tgllahir);
        $umur = $tgl_lahir->diff(Carbon::now());

        if ($raw->namalengkap) {
            $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($raw->namalengkap ?? ""));
        } else {
            $qrcode = null;
        }
        $pageWidth = 780;

        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.suratmeninggal',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'raw' => $raw,
                    'umur' => $umur,
                    'qrcode' => $qrcode
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.suratmeninggal',
                compact('res', 'pageWidth', 'raw', 'umur', 'qrcode')
            );
        }
    }
    public function cetakOrder(Request $request)
    {
        $norec = $request->noregistrasi;
        $type = strtoupper($request->type) ?? "LABORATORIUM";
        $datas = DB::table('strukorder_t as so')
            ->join('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->join('produk_m as pr', 'pr.id', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->leftJoin('alamat_m AS al', 'al.nocmfk', 'ps.id')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', 'so.norec')
            ->select(
                'so.noorder',
                'so.tglorder',
                'ps.nocm',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.email',
                'al.alamatlengkap',
                'ps.nohp',
                'ru.namaruangan AS ruanganasal',
                'kp.kelompokpasien',
                'pr.namaproduk AS pemeriksaan',
                'pg.namalengkap AS pengorder',
                'pg.notlp AS tlpdokter',
                'pg.alamat AS alamatdokter',
                'ps.penanggungjawab'
            )
            ->where('so.norec', $norec)
            ->get();

        $pageWidth = 780;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.cetak-order',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'datas' => $datas,
                    'type' => $type
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.cetak-order',
                compact('res', 'pageWidth', 'type', 'datas')
            );
        }
    }
    public function getDataKdProfile(Request $request)
    {
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];
        $data = LoginUser::where('id', $idUser)->first();
        if (!empty($data)) {
            $idKdProfile = (int)$data->kdprofile;
            $Query = DB::table('profile_m')
                ->where('id', '=', $idKdProfile)
                ->first();
            $Profile = $Query;
            return (int)$Profile->id;
        } else {
            $data = Pasien::where('id', $idUser)->first();
            if (!empty($data)) {
                $idKdProfile = (int)$data->kdprofile;
                $Query = DB::table('profile_m')
                    ->where('id', '=', $idKdProfile)
                    ->first();
                $Profile = $Query;
                return (int)$Profile->id;
            } else {
                return null;
            }
        }
    }
    public function cetakLabelTindakan(Request $request)
    {
        $norec              = $request->norec;
        $type               = $request->type;
        $kdProfile          = (int)$this->kdProfile;
        $datas = DB::table('pelayananpasien_t AS pp')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pr.namaproduk AS pemeriksaan',
                'pg.namalengkap AS dokter',
                'pm.tgllahir AS  tgllahir',
                'apd.tglmasuk AS tglmasuk',
                'ru.objectdepartemenfk',
            )
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.norec', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pasien_m AS pm', 'pm.id', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'pd.objectdokterpemeriksafk')
            ->when($type, function ($query) use ($type) {
                if ($type == "radiologi") {
                    return $query->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'));
                } else {
                    return $query->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'));
                }
            })
            ->where('apd.noregistrasifk', $norec)
            ->get();
        $pageWidth = 780;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.pendaftaran.label-tindakan',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'datas' => $datas

                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view(
                'report.pendaftaran.label-tindakan',
                compact('res', 'pageWidth', 'datas')
            );
        }
    }
    public function getDataWaktuMinum(Request $request)
    {

        $idProfile = (int) $this->kdProfile;
        $Norec = $request['Norec_sr'];
        $data = DB::select(
            DB::raw("SELECT  CASE WHEN pp.ispagi = true THEN 'Pagi : 07:00 - 07:30' ELSE '-' END AS pagi,
                             CASE WHEN pp.issiang = true THEN 'Siang : 13:00 - 13:30' ELSE '-' END AS siang,
                             CASE WHEN pp.issore = true THEN 'Sore : 13:00 - 13:30' ELSE '-' END AS sore,
                             CASE WHEN pp.ismalam = true THEN 'Malam : 19:00 - 20:00' ELSE '-' END AS malam
                             FROM pelayananpasien_t AS pp
                             INNER JOIN strukresep_t AS sr ON sr.norec = pp.strukresepfk
                             WHERE pp.kdprofile = ? and pp.jeniskemasanfk = 2 AND sr.norec = ?

                             UNION ALL
                             SELECT
                             CASE WHEN pp.ispagi = true THEN 'Pagi : 07:00 - 07:30' ELSE '-' END AS pagi,
                             CASE WHEN pp.issiang = true THEN 'Siang : 13:00 - 13:30' ELSE '-' END AS siang,
                             CASE WHEN pp.issore = true THEN 'Sore : 13:00 - 13:30' ELSE '-' END AS sore,
                             CASE WHEN pp.ismalam = true THEN 'Malam : 19:00 - 20:00' ELSE '-' END AS malam

                             FROM strukresep_t AS sr
                             INNER JOIN pelayananpasien_t AS pp ON sr.norec = pp.strukresepfk
                             WHERE sr.kdprofile = ? and  pp.jeniskemasanfk = 1 AND sr.norec = ? "),
            [$idProfile, $Norec, $idProfile, $Norec]
        );
        return $this->respond($data);
    }

    public function apotikRekapLabel(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $norec = $request['norec'];
        $waktuMinum = explode(',', $request['waktuMinum']);
        $profile = collect(DB::select("
            select * from profile_m where id = $kdProfile limit 1
        "))->first();
        $pageWidth = 400;
        $produkfk = '';
        if (isset($request['produkfk']) && $request['produkfk'] != '') {
            $produkfk = " and pr.id in ($request[produkfk])";
        }
        $dataReport = collect(DB::select("select
                pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,
                pr.namaproduk,ss.satuanstandar,sr.tglresep,
                pp.jumlah,CASE WHEN pp.ispagi = true THEN 'Pagi : 07:00 - 07:30' ELSE '-' END AS pagi,
                CASE WHEN pp.issiang = true THEN 'Siang : 13:00 - 13:30' ELSE '-' END AS siang,
                CASE WHEN pp.issore = true THEN 'Sore : 13:00 - 13:30' ELSE '-' END AS sore,
                CASE WHEN pp.ismalam = true THEN 'Malam : 19:00 - 20:00' ELSE '-' END AS malam,
                pp.keteranganpakai,sn.satuanresep,pp.aturanpakai
                from strukresep_t as sr
                join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk
                join pelayananpasien_t as pp on pp.strukresepfk=sr.norec
                join produk_m as pr on pr.id=pp.produkfk
                join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk
                join pasien_m as ps  on ps.id=pd.nocmfk
                left join satuanstandar_m as ss on ss.id=pr.objectsatuanstandarfk
                left join satuanresep_m as sn on pp.satuanresepfk =sn.id
                where sr.kdprofile=$kdProfile
                and sr.norec='$norec'
                $produkfk
        "));

        return $dataReport;
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 420.15, 260.50]);
            // $pdf->setOptions(['defaultFont' => 'Arial, Helvetica, sans-serif']);
            $pdf->loadView(
                'report.farmasi.rekap-label',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'waktuMinum' => $waktuMinum,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.rekap-label',
                compact('dataReport', 'pageWidth', 'profile', 'request', 'waktuMinum')
            );
        }
    }
    public function apotikCetakAntrian(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        $profile = collect(DB::select("
                select * from profile_m where id = $kdProfile limit 1
            "))->first();

        $norec = $request['norec'];
        $dataReport = collect(DB::select("select
            pd.tglregistrasi, ps.nocm,ps.namapasien,ps.tgllahir,sr.noresep,aa.noantri,
            jk.jeniskelamin,ru.namaruangan,pd.noregistrasi,kp.kelompokpasien,aa.jenis
            from strukresep_t as sr
            join antrianpasiendiperiksa_t as apd on apd.norec=sr.pasienfk and apd.kdprofile =sr.kdprofile
            join pasiendaftar_t as pd  on pd.norec=apd.noregistrasifk and apd.kdprofile =pd.kdprofile
            join pasien_m as ps  on ps.id=pd.nocmfk and pd.kdprofile =ps.kdprofile
            join jeniskelamin_m as jk  on jk.id=ps.objectjeniskelaminfk and pd.kdprofile =jk.kdprofile
            left join antrianapotik_t as aa on aa.noresep =sr.noresep  and sr.kdprofile =aa.kdprofile
            join ruangan_m as ru on ru.id =sr.ruanganfk  and sr.kdprofile =ru.kdprofile
            left join kelompokpasien_m as kp on kp.id =pd.objectkelompokpasienlastfk  and pd.kdprofile =kp.kdprofile
            where sr.kdprofile=$kdProfile
            and sr.norec='$norec'
        "))->first();

        $statusonline = "";
        $status = "Kartu ini adalah bukti antrian resep anda";

        $pageWidth = 365;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'tglregistrasi' => date('d-M-Y H:i', strtotime($dataReport->tglregistrasi)),
            'noregistrasi' => $dataReport->noregistrasi,
            'norm' => $dataReport->nocm,
            'tgllahir' => $dataReport->tgllahir,
            'namapasien' => $dataReport->namapasien,
            'jeniskelamin' => $dataReport->jeniskelamin,
            'ruangan' => $dataReport->namaruangan,
            'noantrian' => $dataReport->jenis . '-' . $dataReport->noantri,
            'kelompokpasien' => $dataReport->kelompokpasien,
            'status' => $status,
        );
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.farmasi.antrian-apotik',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.antrian-apotik',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }

    public function cetakSuratKeteranganSakit(Request $request)
    {

        $noreg = $request['noregistrasi'];
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSakit', $profile->kdprofile);
        $pageWidth = 950;

        $identitas = collect(DB::select("
            SELECT
            sk.*,
            pg2.namalengkap as namapembuat,
            jp.jenispegawai as jabatan,
            pm.namapasien,
            pm.nocm,
            pm.noidentitas,
            pm.tempatlahir,
            pm.tgllahir,
            jk.jeniskelamin,
            pd.tglregistrasi,
            al.alamatlengkap,
            pj.pekerjaan,
            sk.diagnosa,
            pg.namalengkap as dokterdpjp,
            pg.nosip
            FROM suratketerangan_t sk
            INNER JOIN pasiendaftar_t pd on pd.norec = sk.pasiendaftarfk and pd.kdprofile = sk.kdprofile
            INNER JOIN pasien_m pm on pm.id = pd.nocmfk and pm.kdprofile = pd.kdprofile
            LEFT JOIN jeniskelamin_m jk on pm.objectjeniskelaminfk = jk.id and jk.kdprofile = pm.kdprofile
            LEFT JOIN pekerjaan_m pj on pj.id = pm.objectpekerjaanfk and pj.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m al on pm.id = al.nocmfk and al.kdprofile = pm.kdprofile
            LEFT JOIN pegawai_m pg on pg.id = sk.dokterfk and pg.kdprofile = sk.kdprofile
            LEFT JOIN pegawai_m pg2 on pg2.id = sk.pegawaifk and pg2.kdprofile = sk.kdprofile
            LEFT JOIN jenispegawai_m jp on jp.id = pg2.objectjenispegawaifk and jp.kdprofile = pg2.kdprofile
            WHERE pd.noregistrasi = ?
            AND jenissuratfk = ?
            AND pd.kdprofile = ?
        ", [$noreg, $kdJenisSurat, $this->kdProfile]))->first();
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
        );
        return view(
            'report.pendaftaran.suratketerangansakit',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    function getDataLaporanPenerimaanHarianPDF(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idPegawai = $this->getPegawaiId();
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $namapegawai = $this->getNamaPegawai();

        $idKasir = '';
        $idRuangan = '';
        $idKelompokPasien = '';
        // if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
        //     $idKasir = 'AND pg2.id =' . $request['idKasir'];
        // }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $idRuangan = 'AND sbm.ruanganfk =' . $request['idRuangan'];
        }
        if (isset($request['idKelompokPasien']) && $request['idKelompokPasien'] != "" && $request['idKelompokPasien'] != "undefined") {
            $idKelompokPasien = 'AND kp.id =' . $request['idKelompokPasien'];
        }
        $ruangan = null;
        $data = DB::table('strukbuktipenerimaan_t as sbm')
            ->leftJOIN('strukbuktipenerimaancarabayar_t as sbmc', 'sbmc.nosbmfk', '=', 'sbm.norec')
            ->leftJOIN('carabayar_m as cb', 'cb.id', '=', 'sbmc.objectcarabayarfk')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'sbm.nostrukfk')
            ->leftJOIN('loginuser_s as lu', 'lu.id', '=', 'sbm.objectpegawaipenerimafk')
            ->leftJOIN('pegawai_m as pg2', 'pg2.id', '=', 'lu.objectpegawaifk')
            ->leftJOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'sp.noregistrasifk')
            ->leftJOIN('pasien_m as ps', 'ps.id', '=', 'sp.nocmfk')
            ->leftJOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'sbm.tglsbm',
                'sbm.nosbm',
                'totaldibayarbefore',
                'ps.nocm',
                'ru.namaruangan',
                'pg.namalengkap',
                'pg2.namalengkap as kasir',
                'sp.totalharusdibayar',
                'sbmc.totaldibayar',
                'sbm.keteranganlainnya',
                'cb.carabayar',
                'sbmc.objectcarabayarfk',
                DB::raw('( case when pd.noregistrasi is null then sp.nostruk else pd.noregistrasi end) as noregistrasi,
            (case when ps.namapasien is null then sp.namapasien_klien else ps.namapasien end) as namapasien,
            (case when kp.kelompokpasien is null then null else kp.kelompokpasien end) as kelompokpasien,
            (CASE WHEN sp.totalprekanan is null then 0 else sp.totalprekanan end) as hutangpenjamin,
            (case when cb.id = 1 then sbmc.totaldibayar else 0 end) as tunai,
            (case when cb.id != 1 then sbmc.totaldibayar else 0 end) as nontunai')
            )
            ->where('sbm.kdprofile', $kdProfile)
            ->where('sbm.statusenabled', true);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('sbm.tglsbm', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('sbm.tglsbm', '<=', $tgl);
        }
        if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
            $data = $data->where('sbm.objectpegawaipenerimafk', '=', $request['idKasir']);
        }
        if (isset($request['idDokter']) && $request['idDokter'] != "" && $request['idDokter'] != "undefined") {
            $data = $data->where('pd.objectpegawaifk', '=', $request['idDokter']);
        }
        if (isset($request['idDept']) && $request['idDept'] != "" && $request['idDept'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', '=', $request['idDept']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('sbm.ruanganfk', '=', $request['idRuangan']);
            $ruangan = Ruangan::where('id', $request['idRuangan'])->first()->namaruangan;
        }
        if (isset($request['idKelompokPasien']) && $request['idKelompokPasien'] != "" && $request['idKelompokPasien'] != "undefined") {
            $data = $data->where('kp.kdkelompokpasien', '=', $request['idKelompokPasien']);
        }
        $data->when($request->namapasien, function ($query) use ($request) {
            return $query->where('ps.namapasien', 'like', '%' . $request->namapasien . '%');
        });


        $data = $data->orderBy('pd.noregistrasi', 'ASC');

        $data = $data->get();

        // dd($data);

        $totalsaldo = 0;
        foreach ($data as $d) {
            $totalsaldo += $d->totaldibayar;
        }
        $terbilang = $this->terbilang($totalsaldo);
        $profile = Profile::where('id', $this->kdProfile)->first();

        $carabayar = CaraBayar::mine()->get();
        $totalAll = 0;
        foreach ($data as $d) {
            $totalAll =    $totalAll +  (float) $d->totaldibayar;
            foreach ($carabayar as $dd) {
                if ($dd->id == $d->objectcarabayarfk) {
                    $dd->total = (float)  $dd->total + (float) $d->totaldibayar;
                }
            }
        }

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');

            $pdf->loadView(
                'report.kasir.laporan-penerimaan-kasir-harian',
                array(
                    'data' => $data,
                    'namaPegawai' => $namapegawai,
                    'carabayar' => $carabayar,
                    'terbilang' => $terbilang,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'ruangan' => $ruangan,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.kasir.laporan-penerimaan-kasir-harian',
                compact('data', 'terbilang', 'tglAwal', 'tglAkhir', 'profile', 'carabayar', 'namaPegawai', 'ruangan')
            );
        }
    }
    function getDataLaporanPenerimaanSemuaKasirPDF(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];

        $idKasir = '';
        $idRuangan = '';
        if (isset($request['idKasir']) && $request['idKasir'] != "" && $request['idKasir'] != "undefined") {
            $idKasir = 'AND p.id =' . $request['idKasir'];
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $idRuangan = 'AND sbm.ruanganfk =' . $request['idRuangan'];
        }

        $data = DB::select(DB::raw(
            "
                    SELECT
                        p.namalengkap AS namapenerima,
                        sum(cast(sbm.totaldibayar AS float)) AS totalpenerimaan,
                        '' AS keterangan
                    FROM strukbuktipenerimaan_t AS sbm
                    INNER JOIN strukpelayanan_t AS sp ON sbm.nostrukfk = sp.norec and sp.kdprofile = sbm.kdprofile
                    LEFT JOIN pasiendaftar_t AS pd ON sp.noregistrasifk = pd.norec and pd.kdprofile = pd.kdprofile
                    LEFT JOIN ruangan_m as ru ON ru.id=pd.objectruanganlastfk and ru.kdprofile = pd.kdprofile
                    LEFT JOIN loginuser_s AS lu ON lu.id = sbm.objectpegawaipenerimafk and lu.kdprofile = sbm.kdprofile
                    LEFT JOIN pegawai_m AS p ON p.id = sbm.objectpegawaipenerimafk and p.kdprofile = sbm.kdprofile
                    WHERE
                        sbm.kdprofile = ?
                        AND sbm.tglsbm >= ?
                        AND sbm.tglsbm <= ?
                        $idKasir
                        $idRuangan
                    GROUP BY p.namalengkap"
        ), [$kdProfile, $tglAwal, $tglAkhir]);

        // dd($data);

        $totalsaldo = 0;
        foreach ($data as $d) {
            $totalsaldo += $d->totalpenerimaan;
        }
        $terbilang = $this->terbilang($totalsaldo);
        $profile = Profile::where('id', $this->kdProfile)->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.kasir.laporan-penerimaan-kasir',
                array(
                    'data' => $data,
                    'terbilang' => $terbilang,
                    'tglAwal' => $tglAwal,
                    'tglAkhir' => $tglAkhir,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.kasir.laporan-penerimaan-kasir',
                compact('data', 'terbilang', 'tglAwal', 'tglAkhir', 'profile')
            );
        }
    }


    public  function getTotalKlaim($noregistrasi, $kdProfile)
    {
        $pelayanan = collect(DB::select("select sum(x.totalppenjamin) as totalklaim
         from (select spp.norec,spp.totalppenjamin
         from pasiendaftar_t as pd
            join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec and apd.kdprofile = pd.kdprofile
            join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec and pp.kdprofile = apd.kdprofile
            join strukpelayanan_t as sp on sp.norec= pp.strukfk and sp.kdprofile = pp.kdprofile
            join strukpelayananpenjamin_t as spp on spp.nostrukfk=sp.norec and spp.kdprofile = sp.kdprofile
            where pd.noregistrasi = ?
        --and spp.statusenabled is null
        and pd.kdprofile= ?
        GROUP BY spp.norec,spp.totalppenjamin

        ) as x", [$noregistrasi, $kdProfile]))->first();
        if (!empty($pelayanan) && $pelayanan->totalklaim != null) {
            return (float) $pelayanan->totalklaim;
        } else {
            return 0;
        }
    }

    public function getTotolBayar($noregistrasi, $kdProfile)
    {
        $pelayanan = collect(DB::select("select sum(x.totaldibayar) as totaldibayar
         from (select sbm.norec,sbm.totaldibayar
         from pasiendaftar_t as pd
        join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec and apd.kdprofile = pd.kdprofile
        join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec and pp.kdprofile = apd.kdprofile
        join strukpelayanan_t as sp on sp.norec= pp.strukfk and sp.kdprofile = pp.kdprofile
        join strukbuktipenerimaan_t as sbm on sbm.nostrukfk = sp.norec and sbm.kdprofile = sp.kdprofile
        where pd.noregistrasi = ?
        and sbm.statusenabled =true
        and pd.kdprofile= ?
        AND sbm.keteranganlainnya <> 'Pengembalian Deposit Pasien'
        GROUP BY sbm.norec,sbm.totaldibayar

        ) as x", [$noregistrasi, $kdProfile]))->first();
        if (!empty($pelayanan) && $pelayanan->totaldibayar != null) {
            return (float) $pelayanan->totaldibayar;
        } else {
            return 0;
        }
    }
    public function cetakBillbpjs(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $profile = Profile::where('id', $this->kdProfile)->first();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $noregistrasi = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t AS apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pelayananpasien_t AS pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')->on('pd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kl', function ($j) {
                $j->on('kl.id', '=', 'pd.objectkelasfk')->on('pd.kdprofile', '=', 'kl.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('dp.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('kelompokpasien_m as kp', function ($j) {
                $j->on('kp.id', '=', 'pd.objectkelompokpasienlastfk')->on('kp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('ps.kdprofile', '=', 'jk.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectpegawaifk')->on('pd.kdprofile', '=', 'pg.kdprofile');
            })
            ->leftjoin('rekanan_m as rk', function ($j) {
                $j->on('rk.id', '=', 'pd.objectrekananfk')->on('rk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('pemakaianasuransi_t as pa', function ($j) {
                $j->on('pa.noregistrasifk', '=', 'pd.norec')->on('pa.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('tempattidur_m AS tt', function ($j) {
                $j->on('tt.id', '=', 'apd.nobed')->on('tt.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('strukpelayanan_t AS sp', function ($j) {
                $j->on('sp.norec', '=', 'pp.strukfk')->on('sp.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftjoin('strukbuktipenerimaan_t AS sbm', function ($j) {
                $j->on('sp.nosbmlastfk', '=', 'sbm.norec')->on('sbm.kdprofile', '=', 'sp.kdprofile');
            })
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'pa.nosep',
                'ps.namaayah',
                'ps.penanggungjawab',
                'tt.reportdisplay AS nobed',
                'sbm.nosbm',
                'ps.nocm',
                'ps.tgllahir'
            )


            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        // $res['identitas']->umur = $this->hitungUmur($res['identitas']->tgllahir);
        $res['identitas']->tgllahir = $this->getDateIndo($res['identitas']->tgllahir);

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.norec', '=', 'pp.noregistrasifk')->on('apd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('pasiendaftar_t as pd', function ($j) {
                $j->on('pd.norec', '=', 'apd.noregistrasifk')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('kelas_m as kls', function ($j) {
                $j->on('kls.id', '=', 'apd.objectkelasfk')->on('apd.kdprofile', '=', 'kls.kdprofile');
            })
            ->join('produk_m as prd', function ($j) {
                $j->on('prd.id', '=', 'pp.produkfk')->on('prd.kdprofile', '=', 'pp.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', '=', 'apd.objectruanganfk')->on('apd.kdprofile', '=', 'ru.kdprofile');
            })
            ->join('departemen_m as dp', function ($j) {
                $j->on('dp.id', '=', 'ru.objectdepartemenfk')->on('ru.kdprofile', '=', 'dp.kdprofile');
            })
            ->leftJOIN('strukresep_t as sr', function ($j) {
                $j->on('sr.norec', '=', 'pp.strukresepfk')->on('sr.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('ruangan_m as ru2', function ($j) {
                $j->on('ru2.id', '=', 'sr.ruanganfk')->on('sr.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('departemen_m as dp2', function ($j) {
                $j->on('dp2.id', '=', 'ru2.objectdepartemenfk')->on('dp2.kdprofile', '=', 'ru2.kdprofile');
            })
            ->leftJOIN('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'sr.penulisresepfk')->on('pg.kdprofile', '=', 'sr.kdprofile');
            })
            ->leftJOIN('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'prd.objectdetailjenisprodukfk')->on('djp.kdprofile', '=', 'prd.kdprofile');
            })
            ->leftJOIN('jenisproduk_m as jp', function ($j) {
                $j->on('jp.id', '=', 'djp.objectjenisprodukfk')->on('jp.kdprofile', '=', 'djp.kdprofile');
            })
            ->leftJOIN('strukpelayanan_t as sp', function ($j) {
                $j->on('sp.norec', '=', 'pp.strukfk')->on('sp.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJOIN('strukbuktipenerimaan_t as sbm', function ($j) {
                $j->on('sbm.norec', '=', 'sp.nosbmlastfk')->on('sbm.kdprofile', '=', 'sp.kdprofile');
            })
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'prd.objectdetailjenisprodukfk',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                'sbm.nosbm',
                DB::raw("
            case when pp.jasa is not null then pp.jasa else 0 end jasa,
            COALESCE (pp.hargadiscount, 0)  as diskon,
            case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
            (
                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                 * pp.jumlah)
            + (case when pp.jasa is not null then pp.jasa else 0 end)
             as total,
            to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
            case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
            case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
            case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
           ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['noregistrasi']);
        if (isset($r['pembayaranumum']) && $r['pembayaranumum'] == "true") {
            $data = $data->whereNotNull('sbm.nosbm');
        }
        // else {
        //     $data = $data->whereNull('sbm.nosbm');
        // }
        $data = $data->orderBy('pp.tglpelayanan', 'desc');
        $data = $data->get();

        $sDokterPemeriksa = $this->settingDataFixed('jenisPetugasDokterPemeriksa', $r['kdprofile']);
        $pelayananpetugas = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('pelayananpasienpetugas_t as ptu', function ($join) {
                $join->on('ptu.nomasukfk', '=', 'apd.norec')->on('ptu.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'ptu.objectpegawaifk')->on('pg.kdprofile', '=', 'ptu.kdprofile');
            })
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.objectjenispetugaspefk', 4)
            ->where('pd.kdprofile', $r['kdprofile'])
            ->where('pd.noregistrasi', $r['kdprofile'])
            ->get();



        $res['total'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $res['total']  = $res['total']  + (float) $item->total;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                }
            }
        }

        $res['billing'] =  collect($data)->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $res['klaim']  = $this->getTotalKlaim($r['noregistrasi'], $r['kdprofile']);
        $res['deposit'] = $this->getDepositPasien($r['noregistrasi']);
        $res['dibayar'] = $this->getTotolBayar($r['noregistrasi'], $r['kdprofile']);
        $res['sisa'] =   $res['total']  -  $res['dibayar'] - $res['deposit'] - $res['klaim'];
        $res['pdf']  = false;
        $blade = 'report.kasir.cetak-billing-bpjs';

        // dd($res);

        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =   collect($data)->groupBy('departemen_group');
            $blade = 'report.kasir.cetak-billing-bpjs';
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res', 'r')
        );
    }
    public function getLaporanPenyerahanObat(Request $request)
    {
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $kdProfile = (int) $this->kdProfile;

        $data = DB::table('strukresep_t as sr')
            ->leftJoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
            ->leftJoin('strukorder_t as so', 'so.norec', '=', 'sr.orderfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sr.ruanganfk')
            ->leftJoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruanganfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->where('sr.kdprofile', $kdProfile)
            ->whereBetween('sr.tglresep', [$tglAwal, $tglAkhir])
            ->whereNotNull('aa.noantri')
            ->select(
                'so.noorder',
                'sr.noresep',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'jk.jeniskelamin',
                'so.tglorder',
                'sr.tglresep as tglverifikasi',
                DB::raw("CONCAT(aa.jenis,'-', aa.noantri) AS noantri"),
                'so.namapengambilorder',
                'so.tglambilorder',
                'ru.namaruangan as namaruanganapotik',
                'kp.kelompokpasien',
                'so.keterangankeperluan',
                'so.isreseppulang as checkreseppulang',
                'ru2.namaruangan AS namaruanganrawat'
            );

        if (isset($request['jeniskemasan']) && $request['jeniskemasan'] != "" && $request['jeniskemasan'] != "undefined") {
            if ($request['jeniskemasan'] == 1) {
                $data = $data->where('aa.jenis', 'R');
            } else {
                $data = $data->where('aa.jenis', 'N');
            }
        }

        if (isset($request['IdFarmasi']) && $request['IdFarmasi'] != "" && $request['IdFarmasi'] != "undefined") {
            $data = $data->where('sr.ruanganfk', $request['IdFarmasi']);
        }

        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }

    public function suratPendaftaranRanap(Request $request)
    {

        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 1100;

        $mantanRanap = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->select('pd.nocmfk', 'ru.namaruangan')
            ->where('pd.nocmfk', $request->nocmfk)
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->count();

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->leftjoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', 'jk.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', 'kp.id')
            ->leftjoin('agama_m as ag', 'ag.id', 'ps.objectagamafk')
            ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->leftjoin('asalrujukan_m as ar', 'pd.asalrujukanfk', 'ar.id')
            ->leftjoin('statusperkawinan_m as sp', 'sp.id', 'ps.objectstatusperkawinanfk')
            ->leftjoin('pekerjaan_m as pg', 'pg.id', 'ps.objectpekerjaanfk')
            ->leftjoin('kebangsaan_m as kb', 'kb.id', 'ps.objectkebangsaanfk')
            ->leftjoin('suku_m as sku', 'sku.id', 'ps.objectsukufk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('propinsi_m as pro', 'pro.id', 'alm.objectpropinsifk')
            ->leftJoin('kotakabupaten_m as kk', 'kk.id', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as kt', 'kt.id', 'alm.objectkecamatanfk')
            ->leftJoin('desakelurahan_m as dk', 'dk.id', 'alm.objectdesakelurahanfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'ps.alamatrmh',
                'ps.nocm',
                'pd.objectkelompokpasienlastfk',
                'kp.kelompokpasien',
                'pd.tglregistrasi',
                'ps.tempatlahir',
                'ps.nohp',
                'ps.bahasa',
                'kb.name as kebangsaan',
                'jk.jeniskelamin',
                'ps.umurpenanggungjawab',
                'ps.jeniskelaminpenanggungjawab',
                'ps.pekerjaanpenangggungjawab',
                'ps.hubungankeluargapj',
                'ps.penanggungjawab',
                'ps.telponpenanggungjawab',
                'ag.agama',
                'sp.statusperkawinan',
                'ru.namaruangan',
                'ar.asalrujukan',
                'ps.namakeluarga',
                'alm.alamatlengkap',
                'jk.id as jkid',
                DB::raw("EXTRACT(YEAR FROM AGE(NOW(), ps.tgllahir)) AS tahun"),
                'pro.namapropinsi',
                'kk.namakotakabupaten',
                'alm.rtrw',
                'kt.namakecamatan',
                'dk.namadesakelurahan',
                'alm.kodepos',
                'sku.suku',
                'ps.objectpendidikanfk',
                'sp.id as statuskawinid',
                'ps.nohp',
                'pg.pekerjaan',
                'pd.bahasa',
                'pd.dikunjungi',
                'pd.bantuanpelayanan',
                'pd.bantuanpenerjemah'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $request->norec)
            ->first();

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $data,
            'isRanap' => $mantanRanap > 1 ? true : false
        );

        // return $dataReport;
        return view(
            'report.registrasi.suratpendaftaranranap',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function suratKeluarMasuk(Request $request)
    {
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 1100;
        $data = DB::table('pasiendaftar_t as pd')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', 'kp.id')
            ->leftJoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', 'pd.norec')
            ->join('pasien_m as ps', 'pd.nocmfk', 'ps.id')
            ->leftJoin('pegawai_m as peg', 'peg.id', 'pd.objectpegawaifk')
            ->leftJoin('rekanan_m as rke', 'rke.id', 'pd.objectrekananfk')
            ->leftjoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', 'jk.id')
            ->leftjoin('agama_m as ag', 'ag.id', 'ps.objectagamafk')
            ->leftjoin('kelas_m as ks', 'ks.id', 'pd.objectkelasfk')
            ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
            ->leftjoin('asalrujukan_m as ar', 'pd.asalrujukanfk', 'ar.id')
            ->leftjoin('statusperkawinan_m as sp', 'sp.id', 'ps.objectstatusperkawinanfk')
            ->leftjoin('pekerjaan_m as pg', 'pg.id', 'ps.objectpekerjaanfk')
            ->leftjoin('kebangsaan_m as kb', 'kb.id', 'ps.objectkebangsaanfk')
            ->leftjoin('suku_m as sku', 'sku.id', 'ps.objectsukufk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('propinsi_m as pro', 'pro.id', 'alm.objectpropinsifk')
            ->leftJoin('kotakabupaten_m as kk', 'kk.id', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as kt', 'kt.id', 'alm.objectkecamatanfk')
            ->leftJoin('desakelurahan_m as dk', 'dk.id', 'alm.objectdesakelurahanfk')
            ->leftJoin('pendidikan_m as pend', 'pend.id', 'ps.objectpendidikanfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'ps.alamatrmh',
                'ps.nocm',
                'pd.tglregistrasi',
                'ps.tempatlahir',
                'ps.nohp',
                'ps.bahasa',
                'peg.namalengkap',
                'peg.nip',
                'kb.name as kebangsaan',
                'jk.jeniskelamin',
                'ag.agama',
                'sp.statusperkawinan',
                'ru.namaruangan',
                'ar.asalrujukan',
                'alm.alamatlengkap',
                'pd.noregistrasi',
                'apd.tglmasuk',
                'jk.id as jkid',
                DB::raw("EXTRACT(YEAR FROM AGE(NOW(), ps.tgllahir)) AS tahun,EXTRACT(MONTH FROM AGE(NOW(), ps.tgllahir)) AS bulan,EXTRACT(DAY FROM AGE(NOW(), ps.tgllahir)) AS hari"),
                'pro.namapropinsi',
                'kk.namakotakabupaten',
                'alm.rtrw',
                'kt.namakecamatan',
                'dk.namadesakelurahan',
                'alm.kodepos',
                'ks.namakelas',
                'pd.tglmeninggal',
                'pd.tglpulang',
                'ps.namaayah',
                'ps.namaibu',
                'ps.namasuamiistri',
                'ps.penanggungjawab',
                'pend.pendidikan',
                'rke.namarekanan',
                'kp.kelompokpasien',
                'pa.nosep',
                'pa.diagawal_nama',
                'pg.pekerjaan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            // ->where('apd.objectruanganfk', '=','pd.objectruanganlastfk')
            // ->whereNull('apd.tglkeluar')
            ->where('pd.norec', $request->norec)
            ->first();

        $ttde = base64_encode(QrCode::format('svg')->size(40)->generate($data->noregistrasi . "\n" .$data->tglregistrasi . "\n" .$data->nip . "\n" .$data->namalengkap));

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $data,
            'ttd' => $ttde,
        );

        // return $dataReport;
        return view('report.registrasi.lembar-masuk-keluar', compact('dataReport', 'pageWidth', 'profile'));
    }

    public function buktiPembayaran(Request $request)
    {
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $profile->id);
        $pageWidth = 1100;

        return view('report.kasir.tandabuktipembayaran', compact('pageWidth', 'profile'));
    }

    public function cetakResumMedis(Request $request)
    {
        $profile = DB::table('profile_m')->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->first();
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                'report.resume-medis',
                array(
                    'profile' => $profile,
                    // 'data' => $data,
                    // 'terbilang' => $terbilang,
                    // 'tglAwal' => $tglAwal,
                    // 'tglAkhir' => $tglAkhir,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                'report.resume-medis',
                compact('profile'),
            );
        }
    }
    public function cetakLabelRacikanResep(Request $request)
    {
        $norecpd    = $request->norecpd;
        $pageWidth  = '500p';
        $dataReport = DB::table('strukresep_t AS sr')->select(
            'pd.tglregistrasi',
            'ps.nocm',
            'ps.namapasien',
            'ps.tgllahir',
            'pr.namaproduk',
            'ss.satuanstandar',
            'sr.tglresep',
            'sr.noresep',
            'pp.jumlah',
            'jer.jeniskemasan as  jeniskemasan',
            DB::raw("CASE WHEN pp.ispagi = true THEN 'Pagi' ELSE '' END AS pagi"),
            DB::raw("CASE WHEN pp.issiang = true THEN 'Siang' ELSE '' END AS siang"),
            DB::raw("CASE WHEN pp.issore = true THEN 'Sore' ELSE '' END AS sore"),
            DB::raw("CASE WHEN pp.ismalam = true THEN 'Malam' ELSE '' END AS malam"),
            'pp.keteranganpakai',
            'jrc.jenisracikan',
            'sn.satuanresep',
            'pp.aturanpakai',
            'pp.tglkadaluarsa'
        )
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->leftJoin('satuanresep_m as sn', 'pp.satuanresepfk', '=', 'sn.id')
            ->leftJoin('jeniskemasan_m AS jer', 'jer.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m AS jrc', 'jrc.id', '=', 'pp.jenisracikanfk')
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('jer.jeniskemasan', 'Racikan')
            ->where('pd.norec', '=', $norecpd)
            ->get();

        $blade = "report.farmasi.cetak-label-racikan";
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper([0, 0, 323.15, 270.40]);
            $pdf->setPaper([0, 0, 323.15, 290.40]);
            $pdf->loadView(
                $blade,
                array(
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'dataReport' => $dataReport,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    public function cetakLabelGizi(Request $request)
    {
        $profile = Profile::where('statusenabled', true)->where('id', $this->kdProfile)->first();
        // $profile = collect(DB::select("select * from profile_m where id = $this->kdProfile "))->first();

        $data = DB::table('orderpelayanan_t as op')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'op.noregistrasifk')
            ->leftjoin('rekanan_m as rek', 'pd.objectrekananfk', '=', 'rek.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'op.objectkelasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', function($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                ->on('pd.objectruanganlastfk', '=', 'apd.objectruanganfk');
            })
            ->leftjoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'op.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->leftjoin('strukkirim_t as sk', 'sk.norec', '=', 'op.strukkirimfk')
            ->leftjoin('jeniswaktu_m as jw', 'jw.id', '=', 'op.objectjeniswaktufk')
            ->leftjoin('jenisdiet_m as jd', 'jd.id', '=', 'op.objectjenisdietfk')
            ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'tt.id')
            ->leftjoin('kategorydiet_m as kd', 'kd.id', '=', 'op.objectkategorydietfk')
            ->select(
                'ps.namapasien AS nama',
                'ps.nocm',
                'kp.kelompokpasien',
                'rek.namarekanan AS Penjamin',
                'ps.tgllahir AS tgllahir',
                'pd.noregistrasi AS noregistrasi',
                'ru.namaruangan AS ruangan',
                'tt.reportdisplay AS bed',
                'kd.kategorydiet AS kategorydiet',
                'jd.jenisdiet',
                'jw.jeniswaktu AS waktu',
                'op.keteranganlainnya AS keterangan',
                'op.arrjenisdiet',
                'kl.namakelas AS kelas',
                'km.namakamar AS kamar',
            )
            ->where('op.norec', $request->norec)
            ->first();


        // return response()->json(['data' =>$data]);
        $blade = "report.gizi._label-gizi";
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            // $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                $blade,
                array(
                    'r' => $request,
                    'request' => $request,
                    'pageWidth' => 365,
                    'res' => array(
                        'pdf' => true
                    ),
                    'data' => $data,
                    'profile' => $profile
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('request', 'data', 'profile')
            );
        }
    }
    public function cetakResepObat23(Request $request)
    {

        $norec = $request['norec'];
        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
        "))->first();

        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk, pas.nosep,
        kpp.kelompokpasien AS kelopokpasien, rek.namarekanan AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        left join pemakaianasuransi_t as pas on pas.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec'
        "))->first();

        $detail = collect(DB::select("
        SELECT
            pd.noregistrasi,
            ps.nocm,
            '=' AS umur,
            ps.namapasien AS namapasienjk,
            jk.jeniskelamin AS jk,
            CONCAT(kpp.kelompokpasien, ' (', rek.namarekanan, ')') AS penjamin,
            ps.nohp AS noteleponfaks,
            al.alamatlengkap AS alamat,
            TO_CHAR(ps.tgllahir, 'DD-MM-YYYY') AS tgllahir,
            TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur,
            pd.tglregistrasi,
            ru.namaruangan AS ruanganpasien,
            '-' AS alergi,
            sr.noresep,
            djp.detailjenisproduk,
            ru2.namaruangan,
            pp.tglpelayanan AS tgl,
            pp.rke,
            pr.id AS kdproduk,
            pr.namaproduk AS namaprodukstandar,
            sstd.satuanstandar,
            pp.jumlah,
            COALESCE(pp.jasa, 0) AS jasa,
            pp.hargasatuan,
            TO_CHAR(sr.tglresep, 'DD-MM-YYYY') AS tglresep,
            pp.dosis,
            CONCAT(pp.aturanpakai, ' ', COALESCE(ssr.satuanresep, '')) AS aturanpakai,
            pp.jumlah AS qtyhrg,
            (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) +
                COALESCE(pp.jasa, 0) AS totalharga,
            jnskem.jeniskemasan,
            pgw.namalengkap,
            pgw.nosip,
            CASE
                WHEN kpp.kelompokpasien = 'BPJS' THEN 0
                ELSE ((pp.jumlah * pp.hargasatuan) - COALESCE(pp.hargadiscount, 0) * pp.jumlah) + COALESCE(pp.jasa, 0)
            END AS totalbiaya,
            pp.qtydetailresep,
            CASE
                WHEN kpp.kelompokpasien = 'BPJS' THEN
                    (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) +
                    COALESCE(pp.jasa, 0)
                ELSE 0
            END AS totalplatofon
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        LEFT JOIN alamat_m AS al ON al.nocmfk = ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        LEFT JOIN rekanan_m AS rek ON rek.id = pd.objectrekananfk
        LEFT JOIN detailjenisproduk_m AS djp ON djp.id = pr.objectdetailjenisprodukfk
        LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec = :norec
        ORDER BY pp.rke ASC
    ", ['norec' => $norec]));



        $detail = $detail->groupBy('jeniskemasan');


        $pageWidth = 950;

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail,
            'user' => $this->getNamaPegawai()
        );

        // if ($request['pdf'] == 'true') {
        //     $pdf = App::make('dompdf.wrapper');
        //     $pdf->loadView(
        //         'report.farmasi.cetak-resep-obat-23-dom',
        //         array(
        //             'r' => $request,
        //             'profile' => $profile,
        //             'dataReport' => $dataReport,
        //             'res' => array(
        //                 'pdf' => true
        //             ),
        //             'pageWidth' => $pageWidth
        //         )
        //     );
        //     return $pdf->stream();
        // } else {
        //     return view(
        //         'report.farmasi.cetak-resep-obat-23-new',
        //         compact('dataReport', 'pageWidth', 'profile')
        //     );
        // }
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : false;
        $blade = "report.farmasi.cetak-resep-obat-23-new";
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'landscape');
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper([0, 0, 461.55, 841.00],'landscape');
            // $pdf->setPaper('a2');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'res' => $res,
                    'profile' => $profile,
                    'user' => $this->getNamaPegawai()
                )
            );
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper('A5', 'landscape');
            return $pdf->stream();
        } else {
            return view($blade, compact('dataReport', 'res', 'profile'));
        }
    }
    public function cetakResepObatPulang(Request $request)
    {

        $norec = $request['norec'];
        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
        "))->first();

        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk, pas.nosep,
        kpp.kelompokpasien AS kelopokpasien, rek.namarekanan AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatpulang_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        left join pemakaianasuransi_t as pas on pas.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec'
        "))->first();

        $detail = collect(DB::select("
        SELECT
            pd.noregistrasi,
            ps.nocm,
            '=' AS umur,
            ps.namapasien AS namapasienjk,
            jk.jeniskelamin AS jk,
            CONCAT(kpp.kelompokpasien, ' (', rek.namarekanan, ')') AS penjamin,
            ps.nohp AS noteleponfaks,
            al.alamatlengkap AS alamat,
            TO_CHAR(ps.tgllahir, 'DD-MM-YYYY') AS tgllahir,
            TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') AS umur,
            pd.tglregistrasi,
            ru.namaruangan AS ruanganpasien,
            '-' AS alergi,
            sr.noresep,
            djp.detailjenisproduk,
            ru2.namaruangan,
            pp.tglpelayanan AS tgl,
            pp.rke,
            pr.id AS kdproduk,
            pr.namaproduk AS namaprodukstandar,
            sstd.satuanstandar,
            pp.jumlah,
            COALESCE(pp.jasa, 0) AS jasa,
            pp.hargasatuan,
            TO_CHAR(sr.tglresep, 'DD-MM-YYYY') AS tglresep,
            pp.dosis,
            CONCAT(pp.aturanpakai, ' ', COALESCE(ssr.satuanresep, '')) AS aturanpakai,
            pp.jumlah AS qtyhrg,
            (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) +
                COALESCE(pp.jasa, 0) AS totalharga,
            jnskem.jeniskemasan,
            pgw.namalengkap,
            pgw.nosip,
            CASE
                WHEN kpp.kelompokpasien = 'BPJS' THEN 0
                ELSE ((pp.jumlah * pp.hargasatuan) - COALESCE(pp.hargadiscount, 0) * pp.jumlah) + COALESCE(pp.jasa, 0)
            END AS totalbiaya,
            pp.qtydetailresep,
            CASE
                WHEN kpp.kelompokpasien = 'BPJS' THEN
                    (pp.jumlah * (pp.hargasatuan - COALESCE(pp.hargadiscount, 0))) +
                    COALESCE(pp.jasa, 0)
                ELSE 0
            END AS totalplatofon
        FROM pelayananpasienobatpulang_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        LEFT JOIN alamat_m AS al ON al.nocmfk = ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        LEFT JOIN rekanan_m AS rek ON rek.id = pd.objectrekananfk
        LEFT JOIN detailjenisproduk_m AS djp ON djp.id = pr.objectdetailjenisprodukfk
        LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec = :norec
        ORDER BY pp.rke ASC
    ", ['norec' => $norec]));



        $detail = $detail->groupBy('jeniskemasan');


        $pageWidth = 950;

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail,
            'user' => $this->getNamaPegawai()
        );

        // if ($request['pdf'] == 'true') {
        //     $pdf = App::make('dompdf.wrapper');
        //     $pdf->loadView(
        //         'report.farmasi.cetak-resep-obat-23-dom',
        //         array(
        //             'r' => $request,
        //             'profile' => $profile,
        //             'dataReport' => $dataReport,
        //             'res' => array(
        //                 'pdf' => true
        //             ),
        //             'pageWidth' => $pageWidth
        //         )
        //     );
        //     return $pdf->stream();
        // } else {
        //     return view(
        //         'report.farmasi.cetak-resep-obat-23-new',
        //         compact('dataReport', 'pageWidth', 'profile')
        //     );
        // }
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : false;
        $blade = "report.farmasi.cetak-resep-obat-pulang";
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 595.28, 941.89], 'landscape');
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper([0, 0, 461.55, 841.00],'landscape');
            // $pdf->setPaper('a2');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'res' => $res,
                    'profile' => $profile,
                    'user' => $this->getNamaPegawai()
                )
            );
            // $pdf->setPaper('A4', 'landscape');
            // $pdf->setPaper('A5', 'landscape');
            return $pdf->stream();
        } else {
            return view($blade, compact('dataReport', 'res', 'profile'));
        }
    }
    public function cetakKwitansiObat23(Request $request)
    {

        $norec = $request['norec'];
        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
        "))->first();

        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk,
        kpp.kelompokpasien || ' ( ' || rek.namarekanan || ' ) ' AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM bln DD hr') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec'
        "))->first();

        $detail = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,'=' AS umur,ps.namapasien AS namapasienjk,jk.jeniskelamin AS jk,
        kpp.kelompokpasien || ' ( ' || rek.namarekanan || ' ) ' AS penjamin,ps.nohp AS noteleponfaks,
        al.alamatlengkap AS alamat, to_char(ps.tgllahir, 'DD-MM-YYYY') as tgllahir, TO_CHAR(age(ps.tgllahir), 'YY thn MM Bulan') as umur,pd.tglregistrasi,ru.namaruangan AS ruanganpasien,
        '-' AS alergi,sr.noresep,ru2.namaruangan,pp.tglpelayanan AS tgl,pp.rke,pr. ID AS kdproduk,
        pr.namaproduk || ' / ' || sstd.satuanstandar AS namaprodukstandar,pp.jumlah,
        CASE WHEN pp.jasa IS NULL THEN 0 ELSE pp.jasa END AS jasa,pp.hargasatuan,to_char(sr.tglresep, 'DD-MM-YYYY') AS tglresep,pp.dosis,pp.aturanpakai || ' ' || CASE WHEN ssr.satuanresep IS NULL THEN '' ELSE ssr.satuanresep END AS aturanpakai,
        pp.jumlah AS qtyhrg,(pp.jumlah * (pp.hargasatuan-(case when pp.hargadiscount is null then 0 else pp.hargadiscount end )) )+case when pp.jasa is null then 0 else pp.jasa end as totalharga,jnskem.jeniskemasan,pgw.namalengkap,pgw.nosip ,
        CASE WHEN pp.hargadiscount IS NULL THEN 0 ELSE pp.hargadiscount * pp.jumlah END AS totaldiscound,pp.hargasatuan,pp.hargadiscount,((pp.hargasatuan-pp.hargadiscount)*pp.jumlah)+pp.jasa as total,
        ((pp.jumlah * pp.hargasatuan ) - (CASE when pp.hargadiscount isnull then 0 ELSE  pp.hargadiscount * pp.jumlah end))+case when pp.jasa is null then 0 else pp.jasa end as totalbiaya,pp.qtydetailresep
        FROM pelayananpasienobatkronis_t AS pp
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON pp.noregistrasifk = apdp.norec
        INNER JOIN pasiendaftar_t AS pd ON apdp.noregistrasifk = pd.norec
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        left join alamat_m as al on al.nocmfk=ps.id
        INNER JOIN produk_m AS pr ON pp.produkfk = pr.id
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        INNER JOIN strukresep_t AS sr ON pp.strukresepfk = sr.norec
        INNER JOIN ruangan_m AS ru2 ON sr.ruanganfk = ru2.id
        INNER JOIN jeniskemasan_m AS jnskem ON pp.jeniskemasanfk = jnskem.id
        INNER JOIN pegawai_m AS pgw ON sr.penulisresepfk = pgw.id
        INNER JOIN satuanstandar_m AS sstd ON pp.satuanviewfk = sstd.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kpp ON pd.objectkelompokpasienlastfk = kpp.id
        left JOIN rekanan_m as rek on rek.id=pd.objectrekananfk LEFT JOIN satuanresep_m AS ssr ON ssr.id = pp.satuanresepfk
        WHERE sr.norec='$norec' order by pp.rke asc
        "));


        $detail = $detail->groupBy('jeniskemasan');

        $pageWidth = 950;

        $dataReport = array(
            'datas' => $data,
            'detail' => $detail,

        );
        if (isset($request["pdf"])) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView('report.farmasi.cetak-kwitansi-obat-23-dom', array(
                'dataReport' => $dataReport,
                'pageWidth' => $pageWidth,
                'profile' => $profile,
            ));
            return $pdf->stream();
        } else {
            return view(
                'report.farmasi.cetak-kwitansi-obat-23',
                compact('dataReport', 'pageWidth', 'profile')
            );
        }
    }


    public function labelCustom(Request $request)
    {

        $arrayProdukfk = explode(',', $request->produkfk);

        $data = DB::table('strukresep_t as sr')
            ->JOIN('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', 'pd.norec')
            ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->LeftJOIN('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LeftJOIN('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->JOIN('stokprodukdetail_t as spd', 'spd.norec', '=', 'pp.stokprodukdetailfk')
            ->select(
                'sr.tglresep',
                'sr.pasienfk',
                'sr.noresep',
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'pp.hargasatuan',
                'pp.tglregistrasi',
                'ps.tgllahir',
                'jk.jeniskemasan',
                'pp.aturanpakai',
                'rt.name as route',
                'pr.namaproduk',
                'ss.satuanstandar',
                'pp.jumlah',
                'pp.dosis',
                'pp.jenisracikanfk',
                'pp.ispagi',
                'pp.issiang',
                'pp.ismalam',
                'pp.issore',
                'pr.kekuatan',
                'pp.keteranganpakai',
                'pp.iskronis',
                'sn.satuanresep',
                'pp.tglkadaluarsa',
            )
            ->where('sr.kdprofile', $this->kdProfile)
            ->where('sr.norec', $request['norec_resep'])
            ->whereIn('pp.produkfk', $arrayProdukfk)
            ->get();

        $pageWidth  = '500p';
        $blade =  $request['injeksi'] == 'true' ? "report.farmasi.cetak-label-inject" : "report.farmasi.cetak-label-gabung";

        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper([0, 0, 323.15, 290.40]);
            $pdf->loadView(
                $blade,
                array(
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'dataReport' => $data,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('dataReport', 'pageWidth', 'profile', 'request')
            );
        }
    }

    function cetakRekapExpertise(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $kelompokpasien     = $request->kelompokpasien;
        $tglAwal            = Carbon::parse($request['tglAwal'])->format('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglAkhir'])->format('Y-m-d');
        $sDokterPemeriksa   = $request['dokter'];
        try {
            $data = DB::table('hasilradiologi_t as hr')
                ->join(DB::raw('(SELECT pelayananpasienfk, MAX(tanggalreport) as latest_tanggalreport
                                FROM hasilradiologi_t
                                GROUP BY pelayananpasienfk) as latest_hr'), function ($join) {
                    $join->on('hr.pelayananpasienfk', '=', 'latest_hr.pelayananpasienfk')
                        ->on('hr.tanggalreport', '=', 'latest_hr.latest_tanggalreport');
                })
                ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'hr.pegawaifk')
                ->join('pelayananpasien_t as pp', 'pp.norec', '=', 'hr.pelayananpasienfk')
                ->leftJoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
                ->when($tglAwal && $tglAkhir, function ($query) use ($tglAwal, $tglAkhir) {
                    $query->where('hr.tanggalreport', '>=', "$tglAwal 00:00:00")
                        ->where('hr.tanggalreport', '<=', "$tglAkhir 23:59:59");
                })
                ->when($sDokterPemeriksa, function ($query, $sDokterPemeriksa) {
                    return $query->where('pg1.id', $sDokterPemeriksa);
                })
                ->where('hr.statusenabled', true)
                ->groupBy('pr.namaproduk', 'pg1.namalengkap', 'djp.detailjenisproduk')
                ->select('pr.namaproduk', 'pg1.namalengkap as dokter', 'djp.detailjenisproduk', DB::raw('count(hr.norec) as qty'))
                ->get();

            if ($sDokterPemeriksa !== "") {
                $dokter = DB::table('pegawai_m')->select('namalengkap')->where('id', $sDokterPemeriksa)->first();
            } else {
                $dokter = "";
            }

            $pageWidth = 950;
            $result = [
                "data" => $data,
                "pageWidth" => $pageWidth,
                "tglAwal" => $tglAwal,
                "tglAkhir" => $tglAkhir,
                "dokter" => $dokter,
            ];

        } catch (Exception $e) {
            $data = [];
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }
        return view('report.radiologi.rekap-expertise', compact('data', 'pageWidth', 'tglAwal', 'tglAkhir', 'dokter'));
    }
    function cetakRekapKirimBarangFarmasi(Request $request)
    {
        $tglAwal            = Carbon::parse($request['tglawal'])->format('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglakhir'])->format('Y-m-d');
        $dateRange = [$request->tglawal, $request->tglakhir];
        $isruangan =  $request['idruangan'];
        try {
            $data = DB::table('strukkirim_t as sp')
                ->leftJoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
                ->Join('produk_m as pro', 'pro.id', '=', 'kp.objectprodukfk')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
                ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'sp.objectruangantujuanfk')
                ->leftJoin('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pro.id')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pro.objectdetailjenisprodukfk')
                ->leftJoin('satuanstandar_m as st', 'st.id', '=', 'pro.objectsatuanstandarfk')
                ->whereBetween(DB::raw("cast(sp.tglkirim as DATE)"), $dateRange)
                ->groupBy(
                    'sp.tglkirim',
                    'pro.namaproduk',
                    'djp.detailjenisproduk',
                    'sp.qtyproduk',
                    'st.satuanstandar',
                    'kp.hargasatuan',
                    'ru1.namaruangan',
                    'ru.namaruangan',
                    'kp.objectprodukfk',
                    'sp.objectruanganasalfk',
                    'sp.objectruangantujuanfk'
                )
                ->select(
                    'sp.tglkirim',
                    'pro.namaproduk',
                    'kp.objectprodukfk',
                    'djp.detailjenisproduk',
                    'sp.qtyproduk AS jumlah',
                    'st.satuanstandar',
                    'kp.hargasatuan',
                    'sp.objectruanganasalfk',
                    'sp.objectruangantujuanfk',
                    'ru1.namaruangan AS ruangan_tujuan',
                    'ru.namaruangan AS ruangan_asal'
                )
                ->orderBy('djp.detailjenisproduk', 'asc');

            if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
                $data = $data->where('sp.objectruanganasalfk', '=', $request['idruangan']);
            }
            $data = $data->get();
            if ($isruangan !== "") {
                $ruangan  = DB::table('ruangan_m')->select('namaruangan')->where('id', $isruangan)->first();
            } else {
                $ruangan = "";
            }
            $pageWidth = 950;
            $result = [
                "data" => $data,
                "ruangan" => $ruangan,
                "pageWidth" => $pageWidth,
                "tglAwal" => $tglAwal,
                "tglAkhir" => $tglAkhir
            ];
        } catch (Exception $e) {
            $data = [];
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }
        return view('report.farmasi.rekap-kirim-barang', compact('data', 'pageWidth', 'tglAwal', 'tglAkhir', 'ruangan'));
    }

    public function getLaporanSurveilans(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rg', 'rg.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'kbp.id', '=', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as kcm', 'kcm.id', '=', 'alm.objectkecamatanfk')
            ->leftJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
            ->leftJoin('propinsi_m as pps', 'pps.id', '=', 'alm.objectpropinsifk')
            ->leftJoin('diagnosapasien_t as dg', 'dg.noregistrasifk', '=', 'apd.norec')
            ->leftJoin('detaildiagnosapasien_t as ddg', function ($join) {
                $join->on('ddg.objectdiagnosapasienfk', '=', 'dg.norec')
                    ->on('ddg.objectjenisdiagnosafk', '=', DB::raw('1'));
            })
            ->leftJoin('diagnosa_m as dm', 'dm.id', '=', 'ddg.objectdiagnosafk')
            ->select(
                'pd.noregistrasi as no_pendaftaran',
                'ps.namapasien',
                'ps.nocm',
                'ps.nohp',
                'jk.jeniskelamin',
                'ps.tgllahir',
                'pd.tglregistrasi',
                'sp.statuspulang',
                'pd.tglpulang',
                'kp.kelompokpasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'kls.namakelas',
                'kbp.namakotakabupaten',
                'kcm.namakecamatan',
                'ds.namadesakelurahan',
                'ps.namaayah',
                'ps.namaibu',
                'alm.rtrw',
                'pps.namapropinsi',
                'namadiagnosa',
                'kddiagnosa',
                'pd.statuspasien',
                'pg.id as id_dokter',
            )
            ->groupBy(
                'pd.noregistrasi',
                'ps.namapasien',
                'ps.nocm',
                'ps.nohp',
                'jk.jeniskelamin',
                'ps.tgllahir',
                'ps.namaayah',
                'ps.namaibu',
                'pd.tglregistrasi',
                'sp.statuspulang',
                'pd.tglpulang',
                'kp.kelompokpasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'kls.namakelas',
                'kbp.namakotakabupaten',
                'kcm.namakecamatan',
                'ds.namadesakelurahan',
                'alm.rtrw',
                'pps.namapropinsi',
                'namadiagnosa',
                'kddiagnosa',
                'pd.statuspasien',
                'pg.id',
            )
            ->whereNotNull('pd.noregistrasi')
            ->whereNotNull('pd.tglpulang')
            // ->where('rg.objectdepartemenfk', '=', 9)
            ->where('pg.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('pd.kdprofile', $this->kdProfile);

        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rg.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('klp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['departemen']) && $request['departemen'] != "" && $request['departemen'] != "undefined") {
            $data = $data->Where('rg.objectdepartemenfk', 'ilike', '%' . $request['departemen'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglpulang', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        foreach ($data as $item) {
            $item->umur =  $this->getAgeYear($item->tgllahir, $item->tglregistrasi) . ' thn';
        }

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanJPInap(Request $request)
    {
        try {
            ini_set('max_execution_time', 10000);
            $rangeDate = [$request->tglAwal, $request->tglAkhir];
            $data = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
                ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftJoin('pelayananpasienpetugas_t as ppt', 'ppt.pelayananpasien', '=', 'pp.norec')
                ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'ppt.objectpegawaifk')
                ->join('pegawai_m AS pg1', 'pg1.id', '=', 'pd.objectpegawaifk')
                ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
                ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
                ->join('pelayananpasiendetail_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
                ->selectRaw('
                    pp.tglpelayanan,
                    sbm.tglsbm AS tgl_bkm,
                    pg.namalengkap AS dokter_pemeriksa,
                    pg1.namalengkap AS dpjp,
                    ps.nocm,
                    pd.noregistrasi,
                    pd.tglpulang,
                    pa.nosep,
                    ps.namaexternal AS namapasien,
                    kp.kelompokpasien,
                    prd.namaproduk,
                    ru.namaruangan,
                    kls.namakelas,
                    sbm.totaldibayar,
                    pp.harganetto,
                    SUM(CASE WHEN ppd.komponenhargafk = 93 THEN ppd.harganetto ELSE NULL END) AS JASA_SARANA,
                    SUM(CASE WHEN ppd.komponenhargafk = 35 THEN ppd.harganetto ELSE NULL END) AS JASA_DOKTER,
                    SUM(CASE WHEN ppd.komponenhargafk = 97 THEN ppd.harganetto ELSE NULL END) AS SARANA_RS,
                    SUM(CASE WHEN ppd.komponenhargafk = 94 THEN ppd.harganetto ELSE NULL END) AS JASA_PELAYANAN,
                    SUM(CASE WHEN ppd.komponenhargafk = 107 THEN ppd.harganetto ELSE NULL END) AS BA,
                    SUM(CASE WHEN ppd.komponenhargafk = 9 THEN ppd.harganetto ELSE NULL END) AS Harga_netto,
                    SUM(CASE WHEN ppd.komponenhargafk = 12 THEN ppd.harganetto ELSE NULL END) AS profit_margin,
                    SUM(CASE WHEN ppd.komponenhargafk = 99 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter2,
                    SUM(CASE WHEN ppd.komponenhargafk = 100 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat,
                    SUM(CASE WHEN ppd.komponenhargafk = 101 THEN ppd.harganetto ELSE NULL END) AS sarana_rs2,
                    SUM(CASE WHEN ppd.komponenhargafk = 104 THEN ppd.harganetto ELSE NULL END) AS obat_dan_alkes,
                    SUM(CASE WHEN ppd.komponenhargafk = 109 THEN ppd.harganetto ELSE NULL END) AS jasa_rumah_sakit,
                    SUM(CASE WHEN ppd.komponenhargafk = 110 THEN ppd.harganetto ELSE NULL END) AS jasa_rem,
                    SUM(CASE WHEN ppd.komponenhargafk = 111 THEN ppd.harganetto ELSE NULL END) AS bahan_dan_alat,
                    SUM(CASE WHEN ppd.komponenhargafk = 112 THEN ppd.harganetto ELSE NULL END) AS jasa_operator,
                    SUM(CASE WHEN ppd.komponenhargafk = 113 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat_ok,
                    SUM(CASE WHEN ppd.komponenhargafk = 116 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter_pk,
                    SUM(CASE WHEN ppd.komponenhargafk = 117 THEN ppd.harganetto ELSE NULL END) AS jasa_atlm,
                    SUM(CASE WHEN ppd.komponenhargafk = 118 THEN ppd.harganetto ELSE NULL END) AS jasa_klinik_sakura,
                    sbm.nosbm')
                ->groupBy(
                    'pp.tglpelayanan',
                    'sbm.tglsbm',
                    'pg.namalengkap',
                    'pd.tglpulang',
                    'ps.nocm',
                    'ps.namaexternal',
                    'kp.kelompokpasien',
                    'prd.namaproduk',
                    'pg1.namalengkap',
                    'ru.namaruangan',
                    'kls.namakelas',
                    'sbm.totaldibayar',
                    'pp.harganetto',
                    'sbm.nosbm',
                    'pa.nosep',
                    'pd.noregistrasi'
                )
                ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
                ->where('pp.statusenabled', true)
                ->where('ru.objectdepartemenfk', '16')
                ->whereNotIn('prd.objectdetailjenisprodukfk', [474, 1346, 1318]);
            // ->whereIn('kp.kelompokpasien', ['BPJS', 'BPJS Non PBI', 'Bpjs Rencana Rawat', 'BPJS PBI', 'BPJS KETENAGAKERJAAN']);

            if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
                $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
            }

            $data = $data->orderBy('pp.tglpelayanan', 'ASC');
            $data = $data->distinct();
            $data =  $data->get();

            $result = array(
                'data' => $data,
                'message' => 'ea@epic',
            );

            return $this->respond($result);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json(['error' => $errorMessage], 500);
        }
    }
    public function getLaporanJPRajal(Request $request)
    {
        try {
            ini_set('max_execution_time', 10000);
            $rangeDate = [$request->tglAwal, $request->tglAkhir];
            $data = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
                ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftJoin('pelayananpasienpetugas_t as ppt', 'ppt.pelayananpasien', '=', 'pp.norec')
                ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'ppt.objectpegawaifk')
                ->join('pegawai_m AS pg1', 'pg1.id', '=', 'pd.objectpegawaifk')
                ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
                ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
                ->join('pelayananpasiendetail_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
                ->selectRaw('
                    pp.tglpelayanan,
                    sbm.tglsbm AS tgl_bkm,
                    pg.namalengkap AS dokter_pemeriksa,
                    pg1.namalengkap AS dpjp,
                    ps.nocm,
                    pd.noregistrasi,
                    pa.nosep,
                    ps.namaexternal AS namapasien,
                    kp.kelompokpasien,
                    prd.namaproduk,
                    ru.namaruangan,
                    kls.namakelas,
                    sbm.totaldibayar,
                    pp.harganetto,
                    pp.jumlah,
                    SUM(CASE WHEN ppd.komponenhargafk = 93 THEN ppd.harganetto ELSE NULL END) AS JASA_SARANA,
                    SUM(CASE WHEN ppd.komponenhargafk = 35 THEN ppd.harganetto ELSE NULL END) AS JASA_DOKTER,
                    SUM(CASE WHEN ppd.komponenhargafk = 97 THEN ppd.harganetto ELSE NULL END) AS SARANA_RS,
                    SUM(CASE WHEN ppd.komponenhargafk = 94 THEN ppd.harganetto ELSE NULL END) AS JASA_PELAYANAN,
                    SUM(CASE WHEN ppd.komponenhargafk = 107 THEN ppd.harganetto ELSE NULL END) AS BA,
                    SUM(CASE WHEN ppd.komponenhargafk = 9 THEN ppd.harganetto ELSE NULL END) AS Harga_netto,
                    SUM(CASE WHEN ppd.komponenhargafk = 12 THEN ppd.harganetto ELSE NULL END) AS profit_margin,
                    SUM(CASE WHEN ppd.komponenhargafk = 99 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter2,
                    SUM(CASE WHEN ppd.komponenhargafk = 100 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat,
                    SUM(CASE WHEN ppd.komponenhargafk = 101 THEN ppd.harganetto ELSE NULL END) AS sarana_rs2,
                    SUM(CASE WHEN ppd.komponenhargafk = 104 THEN ppd.harganetto ELSE NULL END) AS obat_dan_alkes,
                    SUM(CASE WHEN ppd.komponenhargafk = 109 THEN ppd.harganetto ELSE NULL END) AS jasa_rumah_sakit,
                    SUM(CASE WHEN ppd.komponenhargafk = 110 THEN ppd.harganetto ELSE NULL END) AS jasa_rem,
                    SUM(CASE WHEN ppd.komponenhargafk = 111 THEN ppd.harganetto ELSE NULL END) AS bahan_dan_alat,
                    SUM(CASE WHEN ppd.komponenhargafk = 112 THEN ppd.harganetto ELSE NULL END) AS jasa_operator,
                    SUM(CASE WHEN ppd.komponenhargafk = 113 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat_ok,
                    SUM(CASE WHEN ppd.komponenhargafk = 116 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter_pk,
                    SUM(CASE WHEN ppd.komponenhargafk = 117 THEN ppd.harganetto ELSE NULL END) AS jasa_atlm,
                    SUM(CASE WHEN ppd.komponenhargafk = 118 THEN ppd.harganetto ELSE NULL END) AS jasa_klinik_sakura,
                    sbm.nosbm')
                ->groupBy(
                    'pp.tglpelayanan',
                    'sbm.tglsbm',
                    'pg.namalengkap',
                    'ps.nocm',
                    'ps.namaexternal',
                    'kp.kelompokpasien',
                    'prd.namaproduk',
                    'pg1.namalengkap',
                    'ru.namaruangan',
                    'kls.namakelas',
                    'sbm.totaldibayar',
                    'pp.harganetto',
                    'pp.jumlah',
                    'sbm.nosbm',
                    'pa.nosep',
                    'pd.noregistrasi'
                )
                ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
                ->where('pp.statusenabled', true)
                ->where('ru.objectdepartemenfk', '18')
                ->whereNotIn('prd.objectdetailjenisprodukfk', [474, 1346, 1318]);
            // ->whereIn('kp.kelompokpasien', ['BPJS', 'BPJS Non PBI', 'Bpjs Rencana Rawat', 'BPJS PBI', 'BPJS KETENAGAKERJAAN']);

            if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
                $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
            }

            $data = $data->orderBy('pp.tglpelayanan', 'ASC');
            $data = $data->distinct();
            $data =  $data->get();

            $result = array(
                'data' => $data,
                'message' => 'ea@epic',
            );

            return $this->respond($result);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json(['error' => $errorMessage], 500);
        }
    }
    public function getLaporanJPPenunjang(Request $request)
    {
        try {
            ini_set('max_execution_time', 10000);
            $rangeDate = [$request->tglAwal, $request->tglAkhir];
            $data = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
                ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftJoin('pelayananpasienpetugas_t as ppt', 'ppt.pelayananpasien', '=', 'pp.norec')
                ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'ppt.objectpegawaifk')
                ->join('pegawai_m AS pg1', 'pg1.id', '=', 'pd.objectpegawaifk')
                ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
                ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
                ->join('pelayananpasiendetail_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
                ->selectRaw('
                    pp.tglpelayanan,
                    sbm.tglsbm AS tgl_bkm,
                    pg.namalengkap AS dokter_pemeriksa,
                    pg1.namalengkap AS dpjp,
                    ps.nocm,
                    pd.noregistrasi,
                    pa.nosep,
                    ps.namaexternal AS namapasien,
                    kp.kelompokpasien,
                    prd.namaproduk,
                    ru.namaruangan,
                    kls.namakelas,
                    sbm.totaldibayar,
                    pp.harganetto,
                    SUM(CASE WHEN ppd.komponenhargafk = 93 THEN ppd.harganetto ELSE NULL END) AS JASA_SARANA,
                    SUM(CASE WHEN ppd.komponenhargafk = 35 THEN ppd.harganetto ELSE NULL END) AS JASA_DOKTER,
                    SUM(CASE WHEN ppd.komponenhargafk = 97 THEN ppd.harganetto ELSE NULL END) AS SARANA_RS,
                    SUM(CASE WHEN ppd.komponenhargafk = 94 THEN ppd.harganetto ELSE NULL END) AS JASA_PELAYANAN,
                    SUM(CASE WHEN ppd.komponenhargafk = 107 THEN ppd.harganetto ELSE NULL END) AS BA,
                    SUM(CASE WHEN ppd.komponenhargafk = 9 THEN ppd.harganetto ELSE NULL END) AS Harga_netto,
                    SUM(CASE WHEN ppd.komponenhargafk = 12 THEN ppd.harganetto ELSE NULL END) AS profit_margin,
                    SUM(CASE WHEN ppd.komponenhargafk = 99 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter2,
                    SUM(CASE WHEN ppd.komponenhargafk = 100 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat,
                    SUM(CASE WHEN ppd.komponenhargafk = 101 THEN ppd.harganetto ELSE NULL END) AS sarana_rs2,
                    SUM(CASE WHEN ppd.komponenhargafk = 104 THEN ppd.harganetto ELSE NULL END) AS obat_dan_alkes,
                    SUM(CASE WHEN ppd.komponenhargafk = 109 THEN ppd.harganetto ELSE NULL END) AS jasa_rumah_sakit,
                    SUM(CASE WHEN ppd.komponenhargafk = 110 THEN ppd.harganetto ELSE NULL END) AS jasa_rem,
                    SUM(CASE WHEN ppd.komponenhargafk = 111 THEN ppd.harganetto ELSE NULL END) AS bahan_dan_alat,
                    SUM(CASE WHEN ppd.komponenhargafk = 112 THEN ppd.harganetto ELSE NULL END) AS jasa_operator,
                    SUM(CASE WHEN ppd.komponenhargafk = 113 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat_ok,
                    SUM(CASE WHEN ppd.komponenhargafk = 116 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter_pk,
                    SUM(CASE WHEN ppd.komponenhargafk = 117 THEN ppd.harganetto ELSE NULL END) AS jasa_atlm,
                    SUM(CASE WHEN ppd.komponenhargafk = 118 THEN ppd.harganetto ELSE NULL END) AS jasa_klinik_sakura,
                    sbm.nosbm')
                ->groupBy(
                    'pp.tglpelayanan',
                    'sbm.tglsbm',
                    'pg.namalengkap',
                    'ps.nocm',
                    'ps.namaexternal',
                    'kp.kelompokpasien',
                    'prd.namaproduk',
                    'pg1.namalengkap',
                    'ru.namaruangan',
                    'kls.namakelas',
                    'sbm.totaldibayar',
                    'pp.harganetto',
                    'sbm.nosbm',
                    'pa.nosep',
                    'pd.noregistrasi'
                )
                ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
                ->where('pp.statusenabled', true)
                ->wherein('ru.objectdepartemenfk', [27, 3, 37, 46, 45])
                ->whereNotIn('prd.objectdetailjenisprodukfk', [474, 1346, 1318]);
            // ->whereIn('kp.kelompokpasien', ['BPJS', 'BPJS Non PBI', 'Bpjs Rencana Rawat', 'BPJS PBI', 'BPJS KETENAGAKERJAAN']);

            if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
                $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
            }

            $data = $data->orderBy('pp.tglpelayanan', 'ASC');
            $data = $data->distinct();
            $data =  $data->get();

            $result = array(
                'data' => $data,
                'message' => 'ea@epic',
            );

            return $this->respond($result);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json(['error' => $errorMessage], 500);
        }
    }
    public function getLaporanJPradiologi(Request $request)
    {
        try {
            ini_set('max_execution_time', 10000);
            $rangeDate = [$request->tglAwal, $request->tglAkhir];
            $data = DB::table('hasilradiologi_t as hr')
                ->join('pegawai_m as pg', 'pg.id', '=', 'hr.pegawaifk')
                ->join('pelayananpasien_t as pp', 'pp.norec', '=', 'hr.pelayananpasienfk')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
                ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
                ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.nostrukfk', '=', 'sp.norec')
                ->leftJoin('pelayananpasiendetail_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
                ->selectRaw('
                    pp.tglpelayanan,
                    sbm.tglsbm AS tgl_bkm,
                    pg.namalengkap,
                    ps.nocm,
                    ps.namaexternal AS namapasien,
                    kp.kelompokpasien,
                    prd.namaproduk,
                    ru.namaruangan,
                    kls.namakelas,
                    sbm.totaldibayar,
                    pp.harganetto,
                    SUM(CASE WHEN ppd.komponenhargafk = 93 THEN ppd.harganetto ELSE NULL END) AS JASA_SARANA,
                    SUM(CASE WHEN ppd.komponenhargafk = 35 THEN ppd.harganetto ELSE NULL END) AS JASA_DOKTER,
                    SUM(CASE WHEN ppd.komponenhargafk = 97 THEN ppd.harganetto ELSE NULL END) AS SARANA_RS,
                    SUM(CASE WHEN ppd.komponenhargafk = 94 THEN ppd.harganetto ELSE NULL END) AS JASA_PELAYANAN,
                    SUM(CASE WHEN ppd.komponenhargafk = 107 THEN ppd.harganetto ELSE NULL END) AS BA,
                    SUM(CASE WHEN ppd.komponenhargafk = 9 THEN ppd.harganetto ELSE NULL END) AS Harga_netto,
                    SUM(CASE WHEN ppd.komponenhargafk = 12 THEN ppd.harganetto ELSE NULL END) AS profit_margin,
                    SUM(CASE WHEN ppd.komponenhargafk = 99 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter2,
                    SUM(CASE WHEN ppd.komponenhargafk = 100 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat,
                    SUM(CASE WHEN ppd.komponenhargafk = 101 THEN ppd.harganetto ELSE NULL END) AS sarana_rs2,
                    SUM(CASE WHEN ppd.komponenhargafk = 104 THEN ppd.harganetto ELSE NULL END) AS obat_dan_alkes,
                    SUM(CASE WHEN ppd.komponenhargafk = 109 THEN ppd.harganetto ELSE NULL END) AS jasa_rumah_sakit,
                    SUM(CASE WHEN ppd.komponenhargafk = 110 THEN ppd.harganetto ELSE NULL END) AS jasa_rem,
                    SUM(CASE WHEN ppd.komponenhargafk = 111 THEN ppd.harganetto ELSE NULL END) AS bahan_dan_alat,
                    SUM(CASE WHEN ppd.komponenhargafk = 112 THEN ppd.harganetto ELSE NULL END) AS jasa_operator,
                    SUM(CASE WHEN ppd.komponenhargafk = 113 THEN ppd.harganetto ELSE NULL END) AS jasa_perawat_ok,
                    SUM(CASE WHEN ppd.komponenhargafk = 116 THEN ppd.harganetto ELSE NULL END) AS jasa_dokter_pk,
                    SUM(CASE WHEN ppd.komponenhargafk = 117 THEN ppd.harganetto ELSE NULL END) AS jasa_atlm,
                    SUM(CASE WHEN ppd.komponenhargafk = 118 THEN ppd.harganetto ELSE NULL END) AS jasa_klinik_sakura,
                    sbm.nosbm')
                ->groupBy(
                    'pp.tglpelayanan',
                    'sbm.tglsbm',
                    'ps.nocm',
                    'ps.namaexternal',
                    'kp.kelompokpasien',
                    'prd.namaproduk',
                    'pg.namalengkap',
                    'ru.namaruangan',
                    'kls.namakelas',
                    'sbm.totaldibayar',
                    'pp.harganetto',
                    'sbm.nosbm'
                )
                ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
                ->where('hr.statusenabled', true);

            if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
                $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
            }

            $data = $data->orderBy('pp.tglpelayanan', 'ASC');
            $data = $data->distinct();
            $data =  $data->get();

            $result = array(
                'data' => $data,
                'message' => 'ea@epic',
            );

            return $this->respond($result);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            return response()->json(['error' => $errorMessage], 500);
        }
    }

    public function getLaporanDokterPerpasienRajal(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pelayananpasienpetugas_t as ppt', 'ppt.pelayananpasien', '=', 'pp.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'ppt.objectpegawaifk', '=', 'pg.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ru.namaruangan',
				'pg.namalengkap AS dokter',
				'pd.noregistrasi',
				'kp.kelompokpasien',
				'kls.namakelas'
            )
            ->groupBy(
                'pd.tglregistrasi',
                'ps.namapasien',
                'ru.namaruangan',
                'pg.namalengkap',
                'kls.namakelas',
                'kp.kelompokpasien',
                'pd.noregistrasi',
                'ps.nocm'
            )
            ->where('pd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 18);

            if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
                $data = $data->Where('pg.id', '=', $request['dokter']);
            }

        $data = $data->orderBy('pd.tglregistrasi', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanDokterPerpasienRanap(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pelayananpasienpetugas_t as ppt', 'ppt.pelayananpasien', '=', 'pp.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'ppt.objectpegawaifk', '=', 'pg.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ru.namaruangan',
				'pg.namalengkap AS dokter',
				'pd.noregistrasi',
				'kp.kelompokpasien',
				'kls.namakelas'
            )
            ->groupBy(
                'pd.tglregistrasi',
                'ps.namapasien',
                'ru.namaruangan',
                'pg.namalengkap',
                'kls.namakelas',
                'kp.kelompokpasien',
                'pd.noregistrasi',
                'ps.nocm'
            )
            ->where('pd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 16);

            if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
                $data = $data->Where('pg.id', '=', $request['dokter']);
            }

        $data = $data->orderBy('pd.tglregistrasi', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdAsalRujukan(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('asalrujukan_m as asl', 'asl.id', '=', 'pd.asalrujukanfk')
            ->select(
                'asl.asalrujukan',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                'asl.asalrujukan'
            )
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 9);

            if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
                $data = $data->where('ru.id', '=', $request['ruanganId']);
            }

        $data = $data->orderBy('asl.asalrujukan', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdCaraBayar(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'kp.kelompokpasien',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                'kp.kelompokpasien'
            )
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 9);

            if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
                $data = $data->where('ru.id', '=', $request['ruanganId']);
            }

        $data = $data->orderBy('kp.kelompokpasien', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdCaraPulang(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
        ->select(DB::raw("
        COALESCE(subquery.statuspulang, 'Lain-lain') as statuspulang,
        COUNT(*) as jumlah
        "))
        ->fromSub(function ($query) use ($rangeDate, $request) {
        $query->select(DB::raw("
                DISTINCT
        pd.noregistrasi,
        CASE
            WHEN apd.objectruanganfk <> pd.objectruanganlastfk THEN 'Dirawat'
            WHEN pd.tglpulang IS NOT NULL
                AND apd.objectruanganfk = pd.objectruanganlastfk THEN
                CASE
                    WHEN sp.id = 1 THEN 'BPL'
                    WHEN sp.id = 4 AND kp.id <> 7 THEN 'Meninggal'
                    WHEN sp.id IN (3, 18) THEN 'APS'
                    WHEN sp.id = 4 AND kp.id = 7 THEN 'DOA'
                    ELSE 'Lain-lain'
                END
            ELSE 'Lain-lain'
        END AS statuspulang
            "))
            ->from('antrianpasiendiperiksa_t as apd')
            ->leftJoin('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'apd.noregistrasi')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('ruangan_m as ru2', 'ru2.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftJoin('kondisipasien_m as kp', 'kp.id', '=', 'pd.objectkondisipasienfk')
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('pd.statusenabled', true)
            ->where('ru.objectdepartemenfk', 9);

        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $query->where('ru.id', '=', $request['ruanganId']);
        }
        }, 'subquery')
        ->groupBy('statuspulang')
        ->orderByDesc('jumlah')
        ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdDiagnosa(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('diagnosa_m as dp', 'dp.kddiagnosa', '=', 'pd.inacbg_diagnosa')
            ->select(
                'dp.kddiagnosa',
                'dp.namadiagnosa',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                'dp.kddiagnosa',
                'dp.namadiagnosa'
            )
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 9);

            if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
                $data = $data->where('ru.id', '=', $request['ruanganId']);
            }

        $data = $data->orderBy('dp.namadiagnosa', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdDpjp(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->select(
                'pd.tglregistrasi',
                'ps.namapasien',
                'pg.namalengkap as dokter',
            )
            ->groupBy(
                'pd.tglregistrasi',
                'ps.namapasien',
                'pg.namalengkap'
            )
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 9);

            if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
                $data = $data->where('ru.id', '=', $request['ruanganId']);
            }

        $data = $data->orderBy('pd.tglregistrasi', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdJumlahDpjp(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->select(
                'pg.namalengkap as dokter',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                'pg.namalengkap'
            )
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 9);

            if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
                $data = $data->where('ru.id', '=', $request['ruanganId']);
            }

        $data = $data->orderBy('pg.namalengkap', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdJenisKelamin(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'jk.jeniskelamin',
                DB::raw('COUNT(*) as jumlah')
            )
            ->groupBy(
                'jk.jeniskelamin'
            )
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ru.objectdepartemenfk', '=', 9);

            if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
                $data = $data->where('ru.id', '=', $request['ruanganId']);
            }

        $data = $data->orderBy('jk.jeniskelamin', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdDomisili(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
    ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
    ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
    ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
    ->leftJoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
    ->leftJoin('kotakabupaten_m as kb', 'kb.id', '=', 'al.objectkotakabupatenfk')
    ->select(
        DB::raw("CASE
                    WHEN kb.id IN (169, 182, 172, 170, 168) THEN kb.namakotakabupaten
                    ELSE 'Lainnya'
                 END as namakotakabupaten"),
        DB::raw('COUNT(*) as jumlah')
    )
    ->groupBy(
        DB::raw("CASE
                    WHEN kb.id IN (169, 182, 172, 170, 168) THEN kb.namakotakabupaten
                    ELSE 'Lainnya'
                 END")
    )
    ->where('apd.statusenabled', true)
    ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
    ->where('ru.objectdepartemenfk', '=', 9);

if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
    $data = $data->where('ru.id', '=', $request['ruanganId']);
}

$data = $data->orderBy('namakotakabupaten', 'ASC');
$data = $data->distinct();
$data = $data->get();


        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanIgdMutasi(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', '=', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('ruangan_m as ruas', 'ruas.id', '=', 'apd.objectruanganasalfk')
            ->join('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'pd.objectpegawairawatbersamafk')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'kbp.id', '=', 'alm.objectkotakabupatenfk')
            ->leftJoin('kecamatan_m as km', 'km.id', '=', 'alm.objectkecamatanfk')
            ->leftJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
            ->select(
                'pd.tglregistrasi',
                'apd.tglmasuk',
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'ruas.namaruangan as Ruangan_asal_IGD',
                'ru.namaruangan as Ruangan_Mutasi',
                'tt.reportdisplay',
                'pg.namalengkap as Dokter',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.nobpjs',
                'ps.alamatrmh',
                'ps.noidentitas as NIK',
                'kls.namakelas'
            )
            ->where('ru.objectdepartemenfk', '16')
            ->where('ruas.objectdepartemenfk', '9')
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('kmr.statusenabled', true)
            ->whereBetween(DB::raw("DATE(apd.tglmasuk)"), $rangeDate);

        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganId']);
        }
        if (isset($request['ruanganIdIGD']) && $request['ruanganIdIGD'] != "" && $request['ruanganIdIGD'] != "undefined") {
            $data = $data->where('ruas.id', '=', $request['ruanganIdIGD']);
        }

        $data = $data->orderBy('apd.tglmasuk', 'ASC');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }
    public function getLaporanIgdDiagnosaPerpasien(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t')
            ->leftJoin('kelas_m', 'kelas_m.id', '=', 'antrianpasiendiperiksa_t.objectkelasfk')
            ->leftJoin('pasiendaftar_t', 'antrianpasiendiperiksa_t.noregistrasifk', '=', 'pasiendaftar_t.norec')
            ->leftJoin('statuspulang_m', 'pasiendaftar_t.objectstatuspulangfk', '=', 'statuspulang_m.id')
            ->leftJoin('kondisipasien_m', 'pasiendaftar_t.objectkondisipasienfk', '=', 'kondisipasien_m.id')
            ->leftJoin('pegawai_m AS pg1', 'pg1.id', '=', 'pasiendaftar_t.objectpegawaifk')
            ->leftJoin('ruangan_m', 'antrianpasiendiperiksa_t.objectruanganfk', '=', 'ruangan_m.id')
            ->leftJoin('diagnosatindakanpasien_t', 'diagnosatindakanpasien_t.objectpasienfk', '=', 'antrianpasiendiperiksa_t.norec')
            ->leftJoin('detaildiagnosatindakanpasien_t', 'detaildiagnosatindakanpasien_t.objectdiagnosatindakanpasienfk', '=', 'diagnosatindakanpasien_t.norec')
            ->join('diagnosatindakan_m', 'diagnosatindakan_m.id', '=', 'detaildiagnosatindakanpasien_t.objectdiagnosatindakanfk')
            ->leftJoin('pasien_m', 'pasiendaftar_t.nocmfk', '=', 'pasien_m.id')
            ->leftJoin('alamat_m', 'alamat_m.nocmfk', '=', 'pasien_m.id')
            ->leftJoin('kotakabupaten_m', 'alamat_m.objectkotakabupatenfk', '=', 'kotakabupaten_m.id')
            ->leftJoin('kecamatan_m', 'alamat_m.objectkecamatanfk', '=', 'kecamatan_m.id')
            ->leftJoin('propinsi_m', 'alamat_m.objectpropinsifk', '=', 'propinsi_m.id')
            ->leftJoin('desakelurahan_m', 'alamat_m.objectdesakelurahanfk', '=', 'desakelurahan_m.id')
            ->join('jeniskelamin_m', 'jeniskelamin_m.id', '=', 'pasien_m.objectjeniskelaminfk')
            ->join('kelompokpasien_m', 'kelompokpasien_m.id', '=', 'pasiendaftar_t.objectkelompokpasienlastfk')
            ->select(
                'pasiendaftar_t.noregistrasi',
                'pasien_m.nocm',
                'pasien_m.namaexternal AS namapasien',
                'jeniskelamin_m.jeniskelamin',
                'pasien_m.tgllahir',
                'pasiendaftar_t.tglregistrasi AS tglmasuk',
                'statuspulang_m.statuspulang',
                'kondisipasien_m.kondisipasien',
                'pasiendaftar_t.tglpulang',
                'kelompokpasien_m.kelompokpasien AS jenis_pasien',
                'ruangan_m.namaruangan',
                'pg1.namalengkap AS dokter',
                'kelas_m.namakelas',
                'diagnosatindakan_m.kddiagnosatindakan',
                'diagnosatindakan_m.namadiagnosatindakan',
                'alamat_m.alamatlengkap',
                'kotakabupaten_m.namakotakabupaten',
                'kecamatan_m.namakecamatan',
                'desakelurahan_m.namadesakelurahan',
                'alamat_m.rtrw',
                'propinsi_m.namapropinsi',
                'pasiendaftar_t.statuspasien',
                'pasiendaftar_t.objectpegawaifk AS id_dokter'
            )
            ->where('antrianpasiendiperiksa_t.statusenabled', true)
            ->whereBetween(DB::raw("DATE(pasiendaftar_t.tglregistrasi)"), $rangeDate)
            ->where('ruangan_m.objectdepartemenfk', '16')
            ->groupBy(
                'propinsi_m.namapropinsi',
                'kondisipasien_m.kondisipasien',
                'statuspulang_m.statuspulang',
                'alamat_m.rtrw',
                'desakelurahan_m.namadesakelurahan',
                'kecamatan_m.namakecamatan',
                'pasiendaftar_t.objectpegawaifk',
                'pg1.namalengkap',
                'pasiendaftar_t.statuspasien',
                'alamat_m.alamatlengkap',
                'kotakabupaten_m.namakotakabupaten',
                'ruangan_m.namaruangan',
                'kelas_m.namakelas',
                'kelompokpasien_m.kelompokpasien',
                'pasien_m.tgllahir',
                'pasiendaftar_t.tglregistrasi',
                'pasiendaftar_t.tglpulang',
                'jeniskelamin_m.jeniskelamin',
                'pasiendaftar_t.noregistrasi',
                'diagnosatindakan_m.kddiagnosatindakan',
                'diagnosatindakan_m.namadiagnosatindakan',
                'pasien_m.nocm',
                'pasien_m.namaexternal'
            )
            ->orderBy('diagnosatindakan_m.namadiagnosatindakan', 'ASC')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }


    function cetakRekapTerimaBarangFarmasi(Request $request)
    {
        $tglAwal            = Carbon::parse($request['tglawal'])->format('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglakhir'])->format('Y-m-d');
        $dateRange = [$request->tglawal, $request->tglakhir];
        $isruangan =  $request['idruangan'];
        try {
            $data = DB::table('strukkirim_t as sp')
                ->leftJoin('kirimproduk_t as kp', 'kp.nokirimfk', '=', 'sp.norec')
                ->Join('produk_m as pro', 'pro.id', '=', 'kp.objectprodukfk')
                ->leftJoin('strukorder_t as so', 'so.norec', 'sp.noorderfk')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganasalfk')
                ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'sp.objectruangantujuanfk')
                ->leftJoin('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'pro.id')
                ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pro.objectdetailjenisprodukfk')
                ->leftJoin('satuanstandar_m as st', 'st.id', '=', 'pro.objectsatuanstandarfk')
                ->whereBetween(DB::raw("cast(sp.tglkirim as DATE)"), $dateRange)
                ->where('so.statusorder', 3)
                ->groupBy(
                    'sp.tglkirim',
                    'pro.namaproduk',
                    'djp.detailjenisproduk',
                    'sp.qtyproduk',
                    'st.satuanstandar',
                    'kp.hargasatuan',
                    'ru1.namaruangan',
                    'ru.namaruangan',
                    'kp.objectprodukfk',
                    'sp.objectruanganasalfk',
                    'sp.objectruangantujuanfk'
                )
                ->select(
                    'sp.tglkirim',
                    'pro.namaproduk',
                    'kp.objectprodukfk',
                    'djp.detailjenisproduk',
                    'sp.qtyproduk AS jumlah',
                    'st.satuanstandar',
                    'kp.hargasatuan',
                    'sp.objectruanganasalfk',
                    'sp.objectruangantujuanfk',
                    'ru1.namaruangan AS ruangan_tujuan',
                    'ru.namaruangan AS ruangan_asal'
                )
                ->orderBy('djp.detailjenisproduk', 'asc');

            if (isset($request['idruangantujuan']) && $request['idruangantujuan'] != "" && $request['idruangantujuan'] != "undefined") {
                $data = $data->where('sp.objectruangantujuanfk', '=', $request['idruangantujuan']);
            }
            $data = $data->get();
            if ($isruangan !== "") {
                $ruangan  = DB::table('ruangan_m')->select('namaruangan')->where('id', $isruangan)->first();
            } else {
                $ruangan = "";
            }
            $pageWidth = 950;
            $result = [
                "data" => $data,
                "ruangan" => $ruangan,
                "pageWidth" => $pageWidth,
                "tglAwal" => $tglAwal,
                "tglAkhir" => $tglAkhir
            ];
        } catch (Exception $e) {
            $data = [];
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }
        return view('report.farmasi.rekap-terima-barang', compact('data', 'pageWidth', 'tglAwal', 'tglAkhir', 'ruangan'));
    }

    public function cetakMCU()
    {
        $pageWidth = 950;
        return view('report.mcu.cetak-mcu', compact('pageWidth'));
    }

    function loadEMR($r)
    {
        if (empty($r['collection'])) {
            return $this->respond([]);
        }
        $nocmfk = strlen($r['nocmfk']) >= 32 ? $r['nocmfk'] : $r['nocmfk'];

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            // ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $nocmfk)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $res = $res->where('registrasi.norec_pd', '=', $r['norec_pd']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get();

        $cppt = DB::connection('mongodb')
            ->table('CPPTDetail')
            // ->where('norec_pd', $r['norec_pd'])
            ->where('nocmfk', $nocmfk)
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $cppt = $cppt->where('emrpasienfk', '=', $r['emrpasienfk']);
        }
        if (isset($r['norec_pd']) &&  $r['norec_pd'] != '') {
            $cppt = $cppt->where('norec_pd', '=', $r['norec_pd']);
        }
        $cppt = $cppt->get();


        if (count($res) > 0) {
            $res = $res->toArray();
            $cppt = $cppt->toArray();
            foreach ($res as $k => $rr) {
                $res[$k]['details'] = [];
                foreach ($cppt as $z => $x) {
                    unset($cppt[$z]['_id']);
                    if ($rr['emrpasienfk'] == $cppt[$z]['emrpasienfk']) {
                        $res[$k]['details'][] = $cppt[$z];
                    }
                }
                unset($res[$k]['_id']);
            }
        }
        return $res;
    }

    public function cetakSKS(Request $r)
    {

        // $data = $this->respond($res);
        // return $data;
        // $id = $this->getPegawaiId();
        // $dokter = $this->getNamaPegawai();
        $res = $this->loadEMR($r);
        $id = $res[0]['dokterPemeriksa']['value'];
        $dokter = DB::table('pegawai_m')->where('id', $id)->select("*")->get();
        $noDokumen = 'RM D.24.4.L';
        $tte = base64_encode(QrCode::format('svg')->size(75)->generate($noDokumen . $res[0]['created_at'] . $dokter[0]->namalengkap . $dokter[0]->nip));

        $pageWidth = 950;
        return view('report.mcu.cetak-mcu-kesehatan', compact('res', 'pageWidth', 'dokter', 'noDokumen', 'tte'));
    }
    public function cetakSKJiwa(Request $r)
    {

        // $data = $this->respond($res);
        // return $data;
        $res = $this->loadEMR($r);
        $id = isset($res[0]['dokterPemeriksaJiwa']) ? $res[0]['dokterPemeriksaJiwa']['value'] : '';
        if ($id === '') {
            $dokter = '';
        } else {
            $dokter = DB::table('pegawai_m')->where('id', $id)->select("*")->get();
        }
        $noDokumen = "";
        $pageWidth = 950;
        $tte = base64_encode(QrCode::format('svg')->size(75)->generate(
            $noDokumen .
            $res[0]['created_at'] .
            (isset($dokter[0]) ? $dokter[0]->namalengkap : '').
            (isset($dokter[0]) ? $dokter[0]->nip : '')
        ));


        return view('report.mcu.cetak-mcu-pemeriksaan-kesehatan-jiwa', compact('res', 'pageWidth', 'dokter', 'noDokumen', 'tte'));
    }
    public function cetakSKNapza(Request $r)
    {

        // $data = $this->respond($res);
        // return $data;
        $res = $this->loadEMR($r);
        // $id = $res[0]['dokterPemeriksaNapza']['value'];
        $id = isset($res[0]['dokterPemeriksaNapza']) ? $res[0]['dokterPemeriksaNapza']['value'] : '';
        if ($id === '') {
            $dokter = '';
        } else {
            $dokter = DB::table('pegawai_m')->where('id', $id)->select("*")->get();
        }
        $noDokumen = 'RM D.24.5.L';
        $tte = base64_encode(QrCode::format('svg')->size(75)->generate(
            $noDokumen .
            $res[0]['created_at'] .
            (isset($dokter[0]) ? $dokter[0]->namalengkap : '').
            (isset($dokter[0]) ? $dokter[0]->nip : '')
        ));
        $pageWidth = 950;
        return view('report.mcu.cetak-mcu-pemeriksaan-napza', compact('res', 'pageWidth', 'dokter', 'tte', 'noDokumen'));
    }
    public function cetakHasilAntigen(Request $request)
    {
        $data = DB::table('hasillabpcr_t as hs')->where('hs.norec', $request['norec'])
            ->join('pelayananpasien_t as pp', 'pp.norec', 'hs.pelayananpasienfk')
            ->leftJoin('produk_m as p', 'p.id', 'pp.produkfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jm', 'jm.id', 'ps.objectjeniskelaminfk')
            ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
            ->leftJoin('ruangan_m as rm', 'rm.id', 'so.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
            ->leftJoin('spesimenpcr_m as pc', 'pc.id', 'hs.jenisspesimenfk')
            ->leftJoin('metodepemeriksanpcr_m as mp', 'mp.id', 'hs.metodeperiksafk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('pelayananpasienpetugas_t as pps', 'pps.pelayananpasien', 'pp.norec')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'hs.petugasverifikatorfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', 'pps.objectpegawaifk')
            ->leftJoin('pegawai_m as pg4', 'pg4.id', 'hs.petugasfk')
            ->leftJoin('pegawai_m as pg5', 'pg5.id', 'hs.petuggasapprovalfk')
            ->leftJoin('produk_m as pro', 'pro.id', 'pp.produkfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'jm.jeniskelamin',
                'ps.paspor',
                'pg.namalengkap as pengirim',
                'ps.noidentitas',
                'pc.namajenisspesimen as jenisspesimen',
                'pc.specimentname as typespeciment',
                'hs.tglterimasampel',
                'hs.tglselesaisampel',
                'alm.alamatlengkap as alamat',
                'hs.kodesampel',
                'hs.nilaiRujukan',
                'mp.judulmetode',
                'mp.methodname',
                'mp.methodtitle',
                'so.noorder as noorder',
                'mp.namametodeperiksa',
                'hs.nopemerikasaan',
                'hs.hasil',
                'pg5.nosip as sip',
                'hs.keterangan',
                'rm.namaruangan as ruanganpengorder',
                'pps.pegawaiverifikatorfk',
                'pg2.namalengkap as petugasverifikator',
                'pg3.namalengkap as verifikator2',
                'pg4.namalengkap as petugaspemeriksa',
                'pg5.namalengkap as petugasapproval',
                'pro.namaproduk as namaproduk'
            )
            ->first();
        $dataQr = $data->noorder . ' ' . $data->namapasien . ' ' . $data->jeniskelamin . ' NIK : ' . $data->noidentitas . ' Passport: ' . $data->paspor . ' Telah melakukan ' . $data->namaproduk . ' ' . $data->tglselesaisampel . ' DENGAN HASIL : ' . ($data->hasil == true ? ' Positif' : ' Negatif');
        $blade = 'report.laboratorium.cetak-antigen';
        $pageWidth = 950;
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->errorCorrection('H')->generate($dataQr ?? ""));
        $qrcodedok = base64_encode(QrCode::format('svg')->size(75)->generate($data->petugasapproval));
        $request['pdf'] = 'true';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'qrcode' => $qrcode,
                    'dataQr' => $dataQr,
                    'qrcodedok' => $qrcodedok,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'data' => $data,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('data', 'pageWidth', 'request')
            );
        }
    }
    public function cetakHasilMikro(Request $request)
    {
        $data = DB::table('hasilmikro_t as hs')->where('hs.norec', $request['norec'])
            ->join('pelayananpasien_t as pp', 'pp.norec', 'hs.pelayananpasienfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jm', 'jm.id', 'ps.objectjeniskelaminfk')
            ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'hs.pemeriksakultur')
            ->join('pegawai_m as dr', 'dr.id', 'pd.objectpegawaifk')
            ->leftJoin('produk_m as pro', 'pro.id', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            // ->join('diagnosa_m as diag', 'diag.kddiagnosa', 'pd.inacbg_diagnosa')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'jm.jeniskelamin',
                'ps.paspor',
                'pp.tglregistrasi as tanggalmasukrs',
                'dr.namalengkap as dpjp',
                'ps.noidentitas',
                'hs.namajenisspesimen as jenisspesimen',
                'hs.namaasalspesimen as asalspesimen',
                'hs.pmn',
                'hs.gpc',
                'hs.gpr',
                'hs.gndc',
                'hs.sec',
                'hs.gnr',
                'hs.gncb',
                'hs.bas',
                'hs.eos',
                'hs.bat',
                'hs.seg',
                'hs.limf',
                'hs.sun',
                'hs.ycphh',
                'hs.pemeriksaan',
                'hs.pemeriksaanpenunjang',
                'hs.tglkultur',
                'hs.namapemeriksakultur',
                'hs.observasikultur',
                'hs.hasilakhirkultur',
                'hs.hasilujikepekaan',
                'hs.tglkeluarhasil',
                'hs.antibiotik',
                'hs.note',
                // 'diag.namadiagnosa as diagnosa',
                'hs.tglterimasampel',
                'hs.tglkeluarhasil',
                'hs.tglkultur',
                'alm.alamatlengkap as alamat',
                'hs.kodespesimen',
                'ps.nocm',
                'ru.namaruangan',
                // 'mp.methodtitle',
                'so.noorder as noorder',
                // 'mp.namametodeperiksa',
                // 'hs.nopemerikasaan',
                // 'hs.hasil',
                // 'hs.keterangan',
                // 'pps.pegawaiverifikatorfk',
                'pg2.namalengkap as pemeriksakultur',
                // 'pg3.namalengkap as verifikator2',
                // 'pg4.namalengkap as petugaspemeriksa',
                // 'pg5.namalengkap as petugasapproval',
                'pro.namaproduk as namaproduk'
            )
            ->first();
        // $dataQr =$data->noorder .$data->namapasien .$data->jeniskelamin . 'NIK :' .$data->noidentitas . 'Passport:' .$data->paspor. 'Telah melakukan' . $data->namaproduk .$data->tglselesaisampel .'DENGAN HASIL' .$data->hasil == true ? 'Positif' : 'Negatif';
        $blade = 'report.laboratorium.cetak-mikro';
        $pageWidth = 950;
        // $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($dataQr ?? ""));
        $request['pdf'] = 'true';
        if ($request['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    // 'qrcode' =>$qrcode,
                    'pageWidth' => $pageWidth,
                    'r' => $request,
                    'request' => $request,
                    'data' => $data,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        } else {
            return view(
                $blade,
                compact('data', 'pageWidth', 'request')
            );
        }
    }

    public function getDataLaporanNarkotika(Request $request)
    {
        $tglAwal            = Carbon::parse($request['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
        $rangeDate          = [$tglAwal, $tglAkhir];
        $data = DB::table('strukresep_t as sr')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'sr.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'pp.noregistrasi')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'sr.ruanganfk')
            ->leftJoin('kotakabupaten_m as kab', 'alm.objectkotakabupatenfk', '=', 'kab.id')
            ->join('profile_m', 'profile_m.kdprofile', '=', 'pg.kdprofile')
            ->where('pd.kdprofile', '=', 1)
            ->select(
                'pr.namaproduk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'sr.tglresep',
                'pp.tglpelayanan',
                'pp.jumlah',
                'ps.namapasien',
                'alm.alamatlengkap',
                'kab.namakotakabupaten',
                'pg.namalengkap',
                'profile_m.namalengkap as rs'
            )
            ->where('pr.isnarkotika', 't');
        if (isset($request['idproduk']) && $request['idproduk'] != '') {
            $data = $data->where('pr.id', $request['idproduk']);
        }
        if (isset($request['tglawal']) && isset($request['tglakhir'])) {
            $data = $data->whereBetween(DB::raw("CAST(sr.tglresep as DATE)"), $rangeDate);
        }
        $data = $data->get();

        return $this->respond($data);
    }

    public function laporanPendapatanResep (Request $request)
    {
        $tglAwal   = Carbon::parse($request['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir  = Carbon::parse($request['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
        $dateRange = [$request->tglawal, $request->tglakhir];

        $data = DB::table('pelayananpasien_t as pp')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            // ->leftJoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJoin('strukresep_t as sr', function ($join) use ($tglAwal, $tglAkhir) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                    ->whereBetween('tglresep', [$tglAwal . " 00:00", $tglAkhir . " 23:59"]);
            })
            ->join('ruangan_m as ru1', 'ru1.id', '=', 'sr.ruanganfk')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->select(
                'ru1.namaruangan as depo',
                'klp.kelompokpasien',
                'ps.namapasien',
                'ps.nocm',
                'pg.namalengkap',
                'ru.namaruangan as ruangan_pasien',
                'kt.namakotakabupaten',
                DB::raw("CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 'Lunas' END AS status_piutang"),
                DB::raw("SUM((pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0)) AS total_pemasukan"),
                DB::raw("COUNT(CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN pp.norec END) AS jumlah_resep_lunas"),
                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN pp.strukresepfk END) AS jumlah_lembar_lunas"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kota Cirebon' THEN (pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0) ELSE 0 END) AS kota_cirebon"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Cirebon' THEN (pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0) ELSE 0 END) AS kab_cirebon"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Kuningan' THEN (pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0) ELSE 0 END) AS kab_kuningan"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Indramayu' THEN (pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0) ELSE 0 END) AS kab_indramayu"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Majalengka' THEN (pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0) ELSE 0 END) AS kab_majalengka"),
                DB::raw("SUM(CASE
                    WHEN kt.namakotakabupaten NOT IN ('Kota Cirebon', 'Kab. Cirebon', 'Kab. Kuningan', 'Kab. Indramayu', 'Kab. Majalengka') THEN (pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0)
                    ELSE 0
                END) AS kota_lain"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kota Cirebon' THEN CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 1 ELSE 0 END ELSE 0 END) AS resep_kota_cirebon"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Cirebon' THEN CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 1 ELSE 0 END ELSE 0 END) AS resep_kab_cirebon"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Kuningan' THEN CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 1 ELSE 0 END ELSE 0 END) AS resep_kab_kuningan"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Indramayu' THEN CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 1 ELSE 0 END ELSE 0 END) AS resep_kab_indramayu"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten = 'Kab. Majalengka' THEN CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 1 ELSE 0 END ELSE 0 END) AS resep_kab_majalengka"),
                DB::raw("SUM(CASE WHEN kt.namakotakabupaten NOT IN ('Kota Cirebon', 'Kab. Cirebon', 'Kab. Kuningan', 'Kab. Indramayu', 'Kab. Majalengka') THEN CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 1 ELSE 0 END ELSE 0 END) AS resep_kota_lain"),

                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 AND kt.namakotakabupaten = 'Kota Cirebon' THEN pp.strukresepfk END) AS lembar_kota_cirebon"),
                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 AND kt.namakotakabupaten = 'Kab. Cirebon' THEN pp.strukresepfk END) AS lembar_kab_cirebon"),
                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 AND kt.namakotakabupaten = 'Kab. Kuningan' THEN pp.strukresepfk END) AS lembar_kab_kuningan"),
                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 AND kt.namakotakabupaten = 'Kab. Indramayu' THEN pp.strukresepfk END) AS lembar_kab_indramayu"),
                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 AND kt.namakotakabupaten = 'Kab. Majalengka' THEN pp.strukresepfk END) AS lembar_kab_majalengka"),
                DB::raw("COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin, 0) = 0 AND kt.namakotakabupaten NOT IN ('Kota Cirebon', 'Kab. Cirebon', 'Kab. Kuningan', 'Kab. Indramayu', 'Kab. Majalengka') THEN pp.strukresepfk END) AS lembar_kota_lain"),
                DB::raw("CASE
                            WHEN prd.objectdetailjenisprodukfk = 474 THEN 'Obat'
                            WHEN prd.objectdetailjenisprodukfk = 1346 THEN 'Alkes'
                            ELSE 'Lainnya'
                        END AS jenis_barang")
            )
            ->groupBy(
                'ru1.namaruangan',
                'klp.kelompokpasien',
                'status_piutang',
                'ps.namapasien',
                'ps.nocm',
                'pg.namalengkap',
                'ru.namaruangan',
                'kt.namakotakabupaten',
                'jenis_barang'
            )
            ->where('pp.statusenabled', true)
            // ->whereNull('pp.piutangpenjamin')
            ->where(function ($query) {
                $query->whereRaw('COALESCE(pp.piutangpenjamin, 0) = 0');
            })
            ->whereIn('klp.id', [18, 2, 4]);
            // ->whereBetween(DB::raw("cast(sr.tglresep as DATE)"), $dateRange);

            if (isset($request['idruangan']) && $request['idruangan'] != "" && $request['idruangan'] != "undefined") {
                $data = $data->where('sr.ruanganfk', '=', $request['idruangan']);
            }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function laporanInstalasiFarmasi(Request $request)
    {
        $tglAwal            = Carbon::parse($request['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
        $rangeDate          = [$tglAwal, $tglAkhir];
        $query = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJoin('strukresep_t as sr', function ($join) use ($tglAwal, $tglAkhir) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                    ->whereBetween('tglresep', [$tglAwal . " 00:00", $tglAkhir . " 23:59"]);
            })
            ->join('ruangan_m as ru1', 'ru1.id', '=', 'sr.ruanganfk')
            ->where('pp.statusenabled', true);

        $query->selectRaw(
            "ru1.namaruangan,
            klp.kelompokpasien,
            CASE
                WHEN pp.piutangpenjamin > 0 THEN 'Bon'
                WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 'Lunas'
            END AS status_piutang,
            SUM((pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0)) AS total_pemasukan,
            COUNT(pp.norec) AS jumlah_resep,
            COUNT(DISTINCT pp.strukresepfk) AS jumlah_lembar,
            SUM(CASE WHEN pp.piutangpenjamin > 0 THEN pp.piutangpenjamin ELSE 0 END) AS piutang_bon,
            SUM(CASE WHEN COALESCE(pp.piutangpenjamin,0) = 0 THEN pp.piutangpenjamin ELSE 0 END) AS piutang_lunas,
            COUNT(CASE WHEN pp.piutangpenjamin > 0 THEN pp.norec END) AS jumlah_resep_bon,
            COUNT(DISTINCT CASE WHEN pp.piutangpenjamin > 0 THEN pp.strukresepfk END) AS jumlah_lembar_bon,
            COUNT(CASE WHEN COALESCE(pp.piutangpenjamin,0) = 0 THEN pp.norec END) AS jumlah_resep_lunas,
            COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin,0) = 0 THEN pp.strukresepfk END) AS jumlah_lembar_lunas"
        );

        $query->groupBy('ru1.namaruangan', 'kelompokpasien', 'status_piutang');

        $results = $query->get();

        // Inisialisasi respons
        $response = [];

        // Ambil semua ruangan yang tersedia
        $ruanganDepo = Ruangan::select('id', 'namaruangan')
            ->where('objectdepartemenfk', $this->settingFix('idInstalasiFarmasi'))
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->orderBy('namaruangan')
            ->get();

        $dataProduksi = DB::table('strukkirim_t as sk')
            ->leftjoin('kirimproduk_t as kp', 'sk.norec', '=', 'kp.nokirimfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'sk.objectruanganfk')
            ->select(
                DB::raw('sum(kp.hargasatuan * kp.qtyprodukkonfirmasi) as total_pemasukan'),
                'ru.namaruangan',
            )
            ->where('sk.objectruanganfk', 189)
            ->whereBetween(DB::raw("CAST(sk.tglkirim as DATE)"), $rangeDate)
            ->groupBy('ru.namaruangan')
            ->first();
        if (isset($dataProduksi)) {
            $results[] = (object)[
                'namaruangan' => $dataProduksi->namaruangan,
                'kelompokpasien' => 'Produksi',
                'status_piutang' => '',
                'total_pemasukan' => $dataProduksi->total_pemasukan,
                "jumlah_resep" => 0,
                "jumlah_lembar" => 0,
                "piutang_bon" => 0,
                "piutang_lunas" => 0,
                "jumlah_resep_bon" => 0,
                "jumlah_lembar_bon" => 0,
                "jumlah_resep_lunas" => 0,
                "jumlah_lembar_lunas" => 0
            ];
        }

        foreach ($results as $result) {
            $detail = (array) $result;

            // Cari indeks grup berdasarkan "Kelompok Pasien"
            $groupIndex = array_search($result->kelompokpasien, array_column($response, 'kelompokpasien'));

            // Jika grup tidak ditemukan, tambahkan grup baru
            if ($groupIndex === false) {
                $response[] = [
                    'kelompokpasien' => $result->kelompokpasien,
                    'groups' => [
                        [
                            'status_piutang' => $result->status_piutang,
                            'details' => [$detail],
                        ],
                    ],
                ];
            } else {
                // Jika grup ditemukan, cari indeks grup status_piutang
                $statusIndex = array_search($result->status_piutang, array_column($response[$groupIndex]['groups'], 'status_piutang'));

                // Jika grup status_piutang tidak ditemukan, tambahkan grup baru
                if ($statusIndex === false) {
                    $response[$groupIndex]['groups'][] = [
                        'status_piutang' => $result->status_piutang,
                        'details' => [$detail],
                    ];
                } else {
                    // Jika grup status_piutang ditemukan, tambahkan detail ke grup tersebut
                    $response[$groupIndex]['groups'][$statusIndex]['details'][] = $detail;
                }
            }
        }

        // Tambahkan ruangan yang kosong jika tidak ada data
        foreach ($response as &$group) {
            foreach ($ruanganDepo as $depo) {
                foreach ($group['groups'] as &$statusGroup) {
                    $found = false;
                    foreach ($statusGroup['details'] as $detail) {
                        if ($detail['namaruangan'] == $depo->namaruangan) {
                            $found = true;
                            break;
                        }
                    }
                    if (!$found) {
                        $statusGroup['details'][] = [
                            "namaruangan" => $depo->namaruangan,
                            "kelompokpasien" => $group['kelompokpasien'],
                            "status_piutang" => $statusGroup['status_piutang'],
                            "total_pemasukan" => 0,
                            "jumlah_resep" => 0,
                            "jumlah_lembar" => 0,
                            "piutang_bon" => "0",
                            "piutang_lunas" => null,
                            "jumlah_resep_bon" => 0,
                            "jumlah_lembar_bon" => 0,
                            "jumlah_resep_lunas" => 0,
                            "jumlah_lembar_lunas" => 0,
                        ];
                    }
                }
            }
        }

        usort($response, function ($a, $b) {
            return strcmp($a['kelompokpasien'], $b['kelompokpasien']) ?: strcmp($a['status_piutang'], $b['status_piutang']);
        });

        foreach ($response as &$group) {
            usort($group['groups'], function ($a, $b) {
                return strcmp($a['status_piutang'], $b['status_piutang']);
            });

            foreach ($group['groups'] as &$statusGroup) {
                usort($statusGroup['details'], function ($a, $b) {
                    return strcmp($a['namaruangan'], $b['namaruangan']);
                });
            }
        }

        return $this->respond($response);
    }
    public function laporanResepDokter(Request $request)
    {
        $tglAwal            = Carbon::parse($request['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
        $rangeDate          = [$tglAwal, $tglAkhir];
        $query = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJoin('strukresep_t as sr', function ($join) use ($tglAwal, $tglAkhir) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                ->whereBetween('tglresep', [$tglAwal . " 00:00", $tglAkhir . " 23:59"]);
            })
            ->join('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->where('pp.statusenabled', true);

        $query->selectRaw(
            "pg.namalengkap,
            COUNT(DISTINCT pp.strukresepfk) AS jumlah_lembar,
            COUNT(pp.norec) AS jumlah_resep,
            COUNT(CASE WHEN prd.objectgenerikfk IS NOT NULL THEN 1 END) AS resep_generik
            "
        );

        $query->groupBy('pg.namalengkap');

        $results = $query->get();

        return $this->respond($results);
    }
    public function laporanRehabMedik(Request $request)
    {
        $tglAwal = Carbon::parse($request['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir = Carbon::parse($request['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
        $rangeDate = [$tglAwal, $tglAkhir];
        $arrayRuanganRehab = [171, 172, 173, 167, 168, 169, 170];

        $query = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', 'pd.norec')
            ->leftJoin('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->leftjoin('strukorder_t as so', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->join('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('ruangan_m as ru1', 'ru1.id', '=', 'apd.objectruanganfk')
            ->whereIn('ru1.id', $arrayRuanganRehab)
            ->whereBetween(DB::raw("CAST(apd.tglmasuk as DATE)"), $rangeDate);

        $query->selectRaw(
            "ru1.namaruangan,
        klp.kelompokpasien,
        SUM((pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0)) AS total_pemasukan,
        COUNT(DISTINCT pd.norec) AS jumlah_pasien"
        );

        if(isset($request['isRanap']) && $request['isRanap']){
            $query = $query->whereNotNull('apd.objectstrukorderfk');
        }else{
            $query = $query->whereNull('apd.objectstrukorderfk');
        }

        $query->groupBy('ru1.namaruangan', 'klp.kelompokpasien');

        $results = $query->get();

        // Initialize the response array
        $response = [];

        foreach ($results as $result) {
            $detail = $result;

            // Find or create the room entry in the response array
            $roomIndex = array_search($result->namaruangan, array_column($response, 'namaruangan'));
            if ($roomIndex === false) {
                $response[] = [
                    'namaruangan' => $result->namaruangan,
                    'groups' => [],
                ];
                $roomIndex = count($response) - 1; // Get the index of the newly added room
            }

            // Add the current patient group to the corresponding room entry
            $response[$roomIndex]['groups'][] = [
                'kelompokpasien' => $result->kelompokpasien,
                'jumlah_pasien' => $result->jumlah_pasien,
                'total_pemasukan' => (float) $result->total_pemasukan,
            ];
        }

        // Ensure that all expected patient groups are present for each room
        $expectedGroups = DB::table('kelompokpasien_m')->select('kelompokpasien')->where('statusenabled', true)->get();

        foreach ($response as &$room) {
            foreach ($expectedGroups as $expectedGroup) {
                $groupExists = false;
                foreach ($room['groups'] as $statusGroup) {
                    if ($statusGroup['kelompokpasien'] == $expectedGroup->kelompokpasien) {
                        $groupExists = true;
                        break;
                    }
                }
                if (!$groupExists) {
                    $room['groups'][] = [
                        'kelompokpasien' => $expectedGroup->kelompokpasien,
                        'jumlah_pasien' => 0,
                        'total_pemasukan' => 0,
                    ];
                }
            }
        }

        return $this->respond($response);
    }


    public function laporanPendapatan(Request $request)
    {
        $tglAwal            = Carbon::parse($request['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
        $rangeDate          = [$tglAwal, $tglAkhir];
        $NPICU = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJoin('strukresep_t as sr', function ($join) use ($tglAwal, $tglAkhir) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                    ->whereBetween('tglresep', [$tglAwal . " 00:00", $tglAkhir . " 23:59"]);
            })
            ->where('apd.objectruanganfk', 116)
            ->orWhere('apd.objectruanganfk', 110)
            ->where('pp.statusenabled', true);

        $NPICU->selectRaw(
            "ru.namaruangan,
            klp.kelompokpasien,
            CASE
                WHEN pp.piutangpenjamin > 0 THEN 'Bon'
                WHEN COALESCE(pp.piutangpenjamin, 0) = 0 THEN 'Lunas'
            END AS status_piutang,
            SUM((pp.hargasatuan - COALESCE(pp.hargadiscount, 0)) * pp.jumlah + COALESCE(pp.jasa, 0)) AS total_pemasukan,
            COUNT(pp.norec) AS jumlah_resep,
            COUNT(DISTINCT pp.strukresepfk) AS jumlah_lembar,
            SUM(CASE WHEN pp.piutangpenjamin > 0 THEN pp.piutangpenjamin ELSE 0 END) AS piutang_bon,
            SUM(CASE WHEN COALESCE(pp.piutangpenjamin,0) = 0 THEN pp.piutangpenjamin ELSE 0 END) AS piutang_lunas,
            COUNT(CASE WHEN pp.piutangpenjamin > 0 THEN pp.norec END) AS jumlah_resep_bon,
            COUNT(DISTINCT CASE WHEN pp.piutangpenjamin > 0 THEN pp.strukresepfk END) AS jumlah_lembar_bon,
            COUNT(CASE WHEN COALESCE(pp.piutangpenjamin,0) = 0 THEN pp.norec END) AS jumlah_resep_lunas,
            COUNT(DISTINCT CASE WHEN COALESCE(pp.piutangpenjamin,0) = 0 THEN pp.strukresepfk END) AS jumlah_lembar_lunas"
        );

        $NPICU->groupBy('ru.namaruangan', 'kelompokpasien', 'status_piutang');

        $results = $NPICU->get();

        // Inisialisasi respons
        $response = [];

        // Ambil semua ruangan yang tersedia
        $kelompokpasien = [];

        $dataProduksi = DB::table('strukkirim_t as sk')
            ->leftjoin('kirimproduk_t as kp', 'sk.norec', '=', 'kp.nokirimfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'sk.objectruanganfk')
            ->select(
                DB::raw('sum(kp.hargasatuan * kp.qtyprodukkonfirmasi) as total_pemasukan'),
                'ru.namaruangan',
            )
            ->where('sk.objectruanganfk', 189)
            ->whereBetween(DB::raw("CAST(sk.tglkirim as DATE)"), $rangeDate)
            ->groupBy('ru.namaruangan')
            ->first();
        $penjualanBebas = DB::table('strukpelayanan_t as sp')
            ->join('strukpelayanandetail_t as spd', 'sp.norec', '=', 'spd.nostrukfk')
            ->where('sp.keteranganlainnya', 'Penjualan Obat Bebas')
            ->whereBetween(DB::raw("CAST(sp.tglstruk as DATE)"), $rangeDate)
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN sp.nosbmlastfk is null THEN spd.nostrukfk END) as lembar_bon'),
                DB::raw('COUNT(CASE WHEN sp.nosbmlastfk is null THEN spd.norec END) as resep_bon'),
                DB::raw('COUNT(DISTINCT CASE WHEN sp.nosbmlastfk is not null THEN spd.nostrukfk END) as lembar_lunas'),
                DB::raw('COUNT(CASE WHEN sp.nosbmlastfk is not null THEN spd.norec END) as resep_lunas'),
                DB::raw('COUNT(spd.norec) as jumlah_resep'),
                DB::raw('COUNT(DISTINCT(spd.nostrukfk)) as jumlah_lembar'),
                DB::raw('SUM(CASE WHEN sp.nosbmlastfk is null THEN sp.totalharusdibayar ELSE 0 END) as pendapatan_bon'),
                DB::raw('SUM(CASE WHEN sp.nosbmlastfk is not null THEN sp.totalharusdibayar ELSE 0 END) as pendapatan_lunas')
            )
            ->first();

        $results[] = (object)[
            'namaruangan' => 'BMHP',
            'kelompokpasien' => 'Produksi',
            'status_piutang' => '',
            'total_pemasukan' => isset($dataProduksi->total_pemasukan) ? $dataProduksi->total_pemasukan : 0,
            "jumlah_resep" => 0,
            "jumlah_lembar" => 0,
            "piutang_bon" => 0,
            "piutang_lunas" => 0,
            "jumlah_resep_bon" => 0,
            "jumlah_lembar_bon" => 0,
            "jumlah_resep_lunas" => 0,
            "jumlah_lembar_lunas" => 0
        ];

        $results[] = (object)[
            'namaruangan' => 'Penjualan Bebas',
            'kelompokpasien' => 'Umum',
            'status_piutang' => 'Bon',
            'total_pemasukan' => isset($penjualanBebas->pendapatan_bon) ? $penjualanBebas->pendapatan_bon : 0,
            "jumlah_resep" => $penjualanBebas->resep_bon,
            "jumlah_lembar" => $penjualanBebas->lembar_bon,
            "piutang_bon" => 0,
            "piutang_lunas" => 0,
            "jumlah_resep_bon" => 0,
            "jumlah_lembar_bon" => 0,
            "jumlah_resep_lunas" => 0,
            "jumlah_lembar_lunas" => 0
        ];


        $results[] = (object)[
            'namaruangan' => 'Penjualan Bebas',
            'kelompokpasien' => 'Umum',
            'status_piutang' => 'Lunas',
            'total_pemasukan' => isset($penjualanBebas->pendapatan_lunas) ? $penjualanBebas->pendapatan_lunas : 0,
            "jumlah_resep" => $penjualanBebas->resep_lunas,
            "jumlah_lembar" => $penjualanBebas->lembar_lunas,
            "piutang_bon" => 0,
            "piutang_lunas" => 0,
            "jumlah_resep_bon" => 0,
            "jumlah_lembar_bon" => 0,
            "jumlah_resep_lunas" => 0,
            "jumlah_lembar_lunas" => 0
        ];

        $allKelompokPasien = DB::table('kelompokpasien_m')->select('kelompokpasien')->where('statusenabled', true)->get();
        $allKelompokPasien[] = (object)['kelompokpasien' => 'Produksi'];
        // $allKelompokPasien = ['BPJS', 'BPJS Non PBI', 'Umum', 'JASA RAHARJA', 'Produksi'];
        $allStatusPiutang = ['', 'Bon', 'Lunas'];
        foreach ($results as $result) {
            $groupIndex = array_search($result->namaruangan, array_column($response, 'namaruangan'));
            if ($groupIndex === false) {
                // Initialize the kelompokpasien and status_piutang arrays for this namaruangan with all values set to 0
                $kelompokPasienArray = [];
                foreach ($allKelompokPasien as $kelompok) {
                    if (!isset($kelompok->kelompokpasien)) {
                        return $kelompok;
                    }
                    $kelompokPasien = $kelompok->kelompokpasien;
                    foreach ($allStatusPiutang as $statusPiutang) {
                        // Skip initializing status '' for kelompokpasien other than 'Produksi'
                        if ($kelompokPasien !== 'Produksi' && $statusPiutang === '') {
                            continue;
                        }
                        if ($kelompokPasien === 'Produksi' && $statusPiutang !== '') {
                            continue;
                        }

                        $kelompokPasienArray[$kelompokPasien][$statusPiutang] = [
                            'total_pemasukan' => 0,
                            'jumlah_resep' => 0,
                            'jumlah_lembar' => 0,
                            'piutang_bon' => 0,
                            'piutang_lunas' => 0,
                            'jumlah_resep_bon' => 0,
                            'jumlah_lembar_bon' => 0,
                            'jumlah_resep_lunas' => 0,
                            'jumlah_lembar_lunas' => 0,
                        ];
                    }
                }

                $response[] = [
                    'namaruangan' => $result->namaruangan,
                    'kelompokpasien' => $kelompokPasienArray,
                ];
            }
        }

        // Filling the kelompokpasien and status_piutang array for each namaruangan
        foreach ($response as &$group) {
            foreach ($results as $result) {
                if ($result->namaruangan === $group['namaruangan']) {
                    // Find the corresponding kelompokpasien and status_piutang entry and update its values
                    $group['kelompokpasien'][$result->kelompokpasien][$result->status_piutang] = [
                        'total_pemasukan' => $result->total_pemasukan,
                        'jumlah_resep' => $result->jumlah_resep,
                        'jumlah_lembar' => $result->jumlah_lembar,
                        'piutang_bon' => $result->piutang_bon,
                        'piutang_lunas' => $result->piutang_lunas,
                        'jumlah_resep_bon' => $result->jumlah_resep_bon,
                        'jumlah_lembar_bon' => $result->jumlah_lembar_bon,
                        'jumlah_resep_lunas' => $result->jumlah_resep_lunas,
                        'jumlah_lembar_lunas' => $result->jumlah_lembar_lunas,
                    ];
                }
            }
        }



        // // usort($response, function ($a, $b) {
        // //     return strcmp($a['kelompokpasien'], $b['kelompokpasien']) ?: strcmp($a['status_piutang'], $b['status_piutang']);
        // // });

        // foreach ($response as &$group) {
        //     usort($group['groups'], function ($a, $b) {
        //         return strcmp($a['status_piutang'], $b['status_piutang']);
        //     });

        //     foreach ($group['groups'] as &$statusGroup) {
        //         usort($statusGroup['details'], function ($a, $b) {
        //             return strcmp($a['namaruangan'], $b['namaruangan']);
        //         });
        //     }
        // }


        return $this->respond($response);
    }
}
