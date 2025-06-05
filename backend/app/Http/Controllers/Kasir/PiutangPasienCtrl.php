<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Rekanan;
use App\Models\Master\SettingDataFixed;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPenerimaanCaraBayar;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\StrukPelayananPenjamin;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukVerifikasi;
use App\Models\Master\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Exception;
use LDAP\Result;
use Psy\Command\HistoryCommand;

class PiutangPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function daftarPiutangPasien(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $dataPiutang = DB::table('strukpelayanan_t as sp')
            ->leftjoin('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.strukfk', '=', 'sp.norec')
            ->leftjoin('antrianpasiendiperiksa_t as ap', 'ap.norec', '=', 'pp.noregistrasifk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.noregistrasifk')
            ->leftjoin('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'p.objectjeniskelaminfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'sp.noregistrasifk')
            ->leftjoin('bpjsklaimtxt_t as bpjs', 'bpjs.sep', '=', 'pa.nosep')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', '=', 'pd.objectrekananfk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'p.id')
            ->leftjoin('kotakabupaten_m as kab', 'kab.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('kecamatan_m as kec', 'kec.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('desakelurahan_m as kel', 'kel.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('propinsi_m as prp', 'prp.id', '=', 'al.objectpropinsifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'spp.kdrekananpenjamin')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('postinghutangpiutang_t as php', 'php.nostrukfk', '=', 'spp.norec')
            ->leftJoin('strukposting_t as spt', 'spt.noposting', '=', 'php.noposting')
            ->select(
                'kp.kelompokpasien',
                'kel.namadesakelurahan',
                'prp.namapropinsi',
                'spp.norec as norec_spp',
                'spp.jenis_piutang',
                'sp.tglstruk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'p.nocm',
                'p.namapasien',
                'p.nohp',
                'p.penanggungjawab',
                'p.telponpenanggungjawab',
                'al.alamatlengkap',
                'al.rtrw',
                'kab.namakotakabupaten',
                'kec.namakecamatan',
                'spp.totalppenjamin',
                'spp.totalharusdibayar',
                'bpjs.tarif_inacbg as tarifklaim',
                'spp.totalsudahdibayar',
                'r.namarekanan as rekanan_multi',
                'jk.jeniskelamin',
                'jk.id as jkid',
                'spp.totalbiaya',
                'spp.noverifikasi',
                'rkn.namarekanan',
                'pd.norec as norec_pd',
                'pd.tglpulang',
                'php.noposting',
                'spt.statusenabled',

            )
            ->where('sp.statusenabled', true)
            ->whereBetween(DB::raw("pd.tglpulang::date"), $rangeDate)
            ->where('sp.kdprofile', $this->kdProfile);

        if ($request['status']) {
            if ($request['status'] == '1') {
                $dataPiutang = $dataPiutang->whereNotNull('spp.noverifikasi');
            }
            if ($request['status'] == '2') {
                $dataPiutang = $dataPiutang->whereNull('spp.noverifikasi');
            }
        }

        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "") {
            $dataPiutang = $dataPiutang->where('kp.id', '=', $request['kelompokpasien']);
        }

        // if (isset($request['namaPasien']) && $request['namaPasien'] != "") {
        //     $dataPiutang = $dataPiutang->where('p.namapasien', 'ilike', '%' . $request['namaPasien'] . '%');
        // }

        if (isset($request['norm']) && $request['norm'] != "") {
            $dataPiutang = $dataPiutang->where('p.nocm', 'ilike', '%' . $request['norm'] . '%');
        }

        if (isset($request['noregis']) && $request['noregis'] != "") {
            $dataPiutang = $dataPiutang->where('pd.noregistrasi', '=', $request['noregis']);
        }
        // if (isset($request['search']) && $request['search'] != '') {
        //     $filter = true;
        //     $searchTerm = '%' . $request['search'] . '%';
        //     $dataPiutang = $dataPiutang->where(function ($query) use ($searchTerm) {
        //         $query->where('p.namapasien', 'ilike', $searchTerm)
        //             ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
        //             ->orWhere('p.nocm', 'ilike', $searchTerm);
        //     });
        // }

        if (isset($request['search']) && $request['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $request['search'] . '%';
            $dataPiutang = $dataPiutang->where(function ($query) use ($searchTerm) {
                $query->where('p.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('p.nocm', 'ilike', $searchTerm);
            });
        }

        if (isset($request['offset']) && $request['offset'] != '') {
            $dataPiutang = $dataPiutang->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $dataPiutang = $dataPiutang->limit($request['limit']);
        }

        $dataPiutang = $dataPiutang->groupBy(
            'kp.kelompokpasien',
            'kel.namadesakelurahan',
            'prp.namapropinsi',
            'al.rtrw',
            'norec_spp',
            'spp.jenis_piutang',
            'sp.tglstruk',
            'pd.noregistrasi',
            'pd.tglregistrasi',
            'p.nocm',
            'p.namapasien',
            'p.nohp',
            'p.penanggungjawab',
            'p.telponpenanggungjawab',
            'al.alamatlengkap',
            'kab.namakotakabupaten',
            'kec.namakecamatan',
            'spp.totalppenjamin',
            'spp.totalharusdibayar',
            'spp.totalsudahdibayar',
            'r.namarekanan',
            'jk.jeniskelamin',
            'jk.id',
            'spp.totalbiaya',
            'spp.noverifikasi',
            'rkn.namarekanan',
            'norec_pd',
            'pd.tglpulang',
            'php.noposting',
            'spt.statusenabled',
            'bpjs.tarif_inacbg'
        );
        $dataPiutang = $dataPiutang->orderBy('p.namapasien');
        $dataPiutang = $dataPiutang->get();
        $result = [];
        foreach ($dataPiutang as $item) {
            if ($item->noverifikasi != null) {
                $statusVerifikasi = "Verifikasi";
                $isVerified = true;
            } else {
                $statusVerifikasi = "Belum Diverifikasi";
                $isVerified = false;
            }
            if ($item->tarifklaim == null) {
                $tarifklaim = 0;
                $selisihKlaim = 0;
            } else {
                $tarifklaim = (float)$item->tarifklaim;
                $selisihKlaim = (float)$item->tarifklaim - (float)$item->totalppenjamin;
            }
            $totalBayar = (float) $item->totalsudahdibayar ;//+ (float) $item->totaldiskon;
            if (isset($item->noposting)) {
                if($totalBayar < $item->totalsudahdibayar ){
                    $status = "Collecting";
                }elseif($totalBayar == $item->totalsudahdibayar || $totalBayar > $item->totalsudahdibayar){
                    $status = "Lunas";
                }
            }else{
                $status = 'Piutang';
            }

            $result[] = array(
                'norec_spp' => $item->norec_spp,
                'tglTransaksi' => $item->tglstruk,
                'noRegistrasi' => $item->noregistrasi,
                'jenis_piutang' => $item->jenis_piutang,
                'nocm' => $item->nocm,
                'namaPasien' => $item->namapasien,
                'jeniskelamin' => $item->jeniskelamin,
                'kdJenisKelamin' => $item->jkid == 1 ? 'L' : 'P',
                'kelasRawat' => '-',
                'jenisPasisen' => $item->kelompokpasien,
                'alamatlengkap' => $item->alamatlengkap,
                'namakotakabupaten' => $item->namakotakabupaten,
                'namakelurahan' => $item->namadesakelurahan,
                'namakecamatan' => $item->namakecamatan,
                'namapropinsi' => $item->namapropinsi,
                'rtrw' => $item->rtrw,
                'nohp' => $item->nohp,
                'penanggungjawab' => $item->penanggungjawab,
                'telponpenanggungjawab' => $item->telponpenanggungjawab,
                'kelasPenjamin' => "-",
                'totalBilling' => $item->totalbiaya,
                'totalKlaim' => $item->totalppenjamin,
                'totalBayar' => $item->totalsudahdibayar,
                'statusVerifikasi' => $statusVerifikasi,
                'rekanan' => $item->rekanan_multi,
                'norec_pd' => $item->norec_pd,
                'tglpulang' => $item->tglpulang,
                'isVerified' => $isVerified,
                'noposting' => $item->noposting,
                'tarifselisihklaim' => $selisihKlaim,
                'tarifinacbgs' => $tarifklaim,
                'verifikasi' => $item->noverifikasi,
                'sisabayar' => (float)$item->totalppenjamin - (float)$item->totalsudahdibayar,
                'status' => $status,
            );
        }

        $datadata = array(
            'data' =>   $result,
        );
        return $this->respond($datadata);
    }

    public function autofillPasien(Request $request)
    {
        $norec_spp = $request->norec_spp;

        $dataRekanan = DB::table('strukpelayanan_t as sp')
            ->join('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pelayananpasien_t as pp', 'pp.strukfk', '=', 'sp.norec')
            ->join('antrianpasiendiperiksa_t as ap', 'ap.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.noregistrasifk')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'spp.kdrekananpenjamin')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'p.id')
            ->leftjoin('hubungankeluarga_m as hb', 'hb.kdhubungankeluarga', '=', 'p.hubungankeluargapj')
            ->select(
                'r.alamatlengkap', 'al.alamatlengkap as alamat_pasien', 'p.namapasien', 'p.penanggungjawab', 'hb.hubungankeluarga'
            )
            ->where('spp.norec', $norec_spp)
            ->where('sp.statusenabled', true)
            ->first();

        if ($dataRekanan) {
            $alamatrekanan = $dataRekanan->alamatlengkap;
            $alamatPasien = $dataRekanan->alamat_pasien;
            $namaPasien = $dataRekanan->namapasien;
            $penanggungJawab = $dataRekanan->penanggungjawab;
            $hubunganKeluarga = $dataRekanan->hubungankeluarga;
        } else {
            $alamatrekanan = null;
            $alamatPasien = null;
            $namaPasien = null;
            $penanggungJawab = null;
            $hubunganKeluarga = null;
        }

        return $this->respond(['alamatrekanan' => $alamatrekanan, 'alamatpasien' => $alamatPasien, 'namaPasien' => $namaPasien, 'penanggungJawab' => $penanggungJawab, 'hubunganKeluarga' => $hubunganKeluarga]);
    }


    public function autofillPasienSitb(Request $request)
    {
        $id_pasien = $request->id_pasien;

        $dataPasien = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->select(
                'p.sitb_id'
            )
            ->where('p.id', $id_pasien)
            ->where('p.statusenabled', true)
            ->first();

        if ($dataPasien) {
            $sitb_pasien = $dataPasien->sitb_id;
        } else {
            $sitb_pasien = null;
        }

        return $this->respond(['sitb_pasien' => $sitb_pasien]);
    }


    public function cetakSuratTagihanKontraktor(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as ap', 'pd.norec', '=', 'ap.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'ap.norec')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->join('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'spp.kdrekananpenjamin')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pekerjaan_m as pj', 'pj.id', '=', 'ps.objectpekerjaanfk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kab', 'kab.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('kecamatan_m as kec', 'kec.id', '=', 'al.objectkecamatanfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pj.pekerjaan',
                'al.alamatlengkap as alamat_pasien',
                'kab.namakotakabupaten',
                'kec.namakecamatan',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'r.namarekanan',
                'r.alamatlengkap',
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
             ->where('spp.norec', $r['norec'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    COALESCE (pp.hargadiscount, 0)  as diskon,
                    ( (COALESCE(pp.hargasatuan,0)  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah)
                    + ( COALESCE (pp.jasa, 0)) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                    case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasi', $r['noregistrasi'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $res['total'] = 0;
        foreach ($data as $item) {
            $res['total']  = (float) $res['total']  + (float) $item->total;
        }
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $klaimResult = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalppenjamin');
        $sudahDibayar = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalsudahdibayar');
        $totalBiaya = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalbiaya');
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['sudahBayar'] = $sudahDibayar;
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['klaim'] = $klaimResult - $res['sudahBayar'];
        $res['sisa'] = $totalBiaya - $res['sudahBayar'] - $res['deposit'] -  $res['dibayar'];
        // $res['klaim']  = StrukPelayananPenjamin::totalppenjamin($r['norec']);
        // $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        // $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['totalklaimiur'] = round($res['total']) - $res['iurbayar'];
        $res['pdf']  = $r['pdf'];
        $res['alamatBaru'] = isset($r['alamat']) ? $r['alamat'] : '';
        $res['kepala'] = Pegawai::select('namalengkap')->where('id', 2020)->first();
        // return $res['kepala'];
        $res['ttdKepala'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['kepala']['namalengkap']));


        $blade = 'report.kasir.surat-tagihan-kontraktor';

        if ($res['pdf'] =='true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade.'-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }
    public function cetakSuratTagihanPerorangan(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as ap', 'pd.norec', '=', 'ap.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'ap.norec')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->join('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pekerjaan_m as pj', 'pj.id', '=', 'ps.objectpekerjaanfk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kab', 'kab.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('kecamatan_m as kec', 'kec.id', '=', 'al.objectkecamatanfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->select(
                'spp.kdrekananpenjamin',
                'spp.jenis_piutang',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pj.pekerjaan',
                'al.alamatlengkap as alamat_pasien',
                'kab.namakotakabupaten',
                'kec.namakecamatan',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'rk.alamatlengkap',
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->where('spp.norec', $r['norec'])
            ->first();
        $res['rekanan'] =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as ap', 'pd.norec', '=', 'ap.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'ap.norec')
            ->join('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->join('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftJoin('rekanan_m as r', 'r.id', '=', 'spp.kdrekananpenjamin')
            ->select(
                'r.namarekanan',
                'r.alamatlengkap',
                'spp.kdrekananpenjamin',
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('spp.norec', $r['norec'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'jp.jenisproduk',
                'dp.namadepartemen',
                'dp2.namadepartemen as deparemenfarmasi',
                DB::raw("
                    case when pp.jasa is not null then pp.jasa else 0 end jasa,
                    COALESCE (pp.hargadiscount, 0)  as diskon,
                    ( (COALESCE(pp.hargasatuan,0)  - COALESCE (pp.hargadiscount, 0))   * pp.jumlah)
                    + ( COALESCE (pp.jasa, 0)) as total,
                    to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                    case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis,
                    case when pp.strukresepfk is null then ru.namaruangan else ru2.namaruangan end as ruang_group ,
                    case when pp.strukresepfk is null then dp.namadepartemen else dp2.namadepartemen end as departemen_group
                ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasi', $r['noregistrasi'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $res['total'] = 0;
        foreach ($data as $item) {
            $res['total']  = (float) $res['total']  + (float) $item->total;
        }
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $klaimResult = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalppenjamin');
        $sudahDibayar = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalsudahdibayar');
        $totalBiaya = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalbiaya');
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['sudahBayar'] = $sudahDibayar;
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['klaim'] = $klaimResult - $res['sudahBayar'];
        $res['sisa'] = $totalBiaya - $res['sudahBayar'] - $res['deposit'] -  $res['dibayar'];
        // $res['klaim']  = StrukPelayananPenjamin::totalppenjamin($r['norec']);
        $res['klaim_bpjs']  = StrukPelayanan::totalKlaim($r['noregistrasi']) - $res['klaim'];
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        // $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['totalklaimiur'] = round($res['total']) - $res['iurbayar'];
        $res['pdf']  = $r['pdf'];
        $res['alamatBaru'] = isset($r['alamat']) ? $r['alamat'] : '';
        $res['namabaru'] = isset($r['namabaru']) ? $r['namabaru'] : '';
        $res['kepala'] = Pegawai::select('namalengkap')->where('id', 2020)->first();
        $res['ttdKepala'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['kepala']['namalengkap']));


        $blade = 'report.kasir.surat-tagihan-perorangan';

        if ($res['pdf'] =='true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade.'-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }
    public function daftarPiutang(Request $request)
    {
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
            ->where('spp.kdprofile', $this->kdProfile)
            ->whereNotNull('spp.noverifikasi')
            ->where('sp.statusenabled', true)
            ->get();
        return $this->respond($dataPiutang);

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
        return $this->respond($result,);
    }

    public function simpanUpdateRekananPD(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('norec', $request['norec_pd'])
                ->update(
                    [
                        'objectrekananfk' => $request['objectrekananfk'],
                        'objectkelompokpasienlastfk' => $request['objectkelompokpasienlastfk'],
                    ]
                );
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Update Rekanan berhasil",
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went Wrong",
                "data" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function verifyPiutangPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $verifikasi = new StrukVerifikasi();
            $noVerif = $this->generateCode(new StrukVerifikasi, 'noverifikasi', 12, 'VP' . $this->getDateTime()->format('dmy'), $this->kdProfile);
            $verifikasi->norec = $verifikasi->generateNewId();
            $verifikasi->kdprofile = $this->kdProfile;
            $verifikasi->objectkelompoktransaksifk = 1; ///ambil dari datafixed pastinya
            $verifikasi->objectpegawaipjawabfk = 1;
            $verifikasi->objectruanganfk = 1; //ambil dari pegawai yang ada ruangankerja
            $verifikasi->namaverifikasi = "Verifikasi Piutang Penjamin";
            $verifikasi->noverifikasi = $noVerif; //$this->generateCode(new StrukVerifikasi, 'noverifikasi', 10, 'VP');
            $verifikasi->tglverifikasi = $this->getDateTime();
            $verifikasi->save();

            $strukPelayanan = StrukPelayananPenjamin::where('norec', $request['norec'])
                ->update(['noverifikasi' => $verifikasi->noverifikasi]);

            DB::commit();
            $result = array(
                'status' => 201,
                'message' => "Verifikasi Piutang Berhasil",
                'result' => $verifikasi,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  =>  "Verifikasi Piutang Gagal",
                'result' => $e->getMessage(),
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cancelVerifyPiutangPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $strukPelayanan = StrukPelayananPenjamin::where('norec', $request['norec'])
                ->where('kdprofile', $this->kdProfile)
                ->update(['noverifikasi' => null]);
            DB::commit();
            $result = array(
                'status' => 201,
                'message' =>  "Unverifikasi Piutang Berhasil",
                'result' => $strukPelayanan,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                'status' => 400,
                'message'  => "Unverifikasi Piutang Gagal",
                'result' => $e->getMessage(),
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function editRekanan(Request $request)
    {
        DB::beginTransaction();

        try {
            PasienDaftar::where('norec', $request['norec_pd'])
                ->update(
                    [
                        'objectrekananfk' => $request['objectrekananfk'],
                        'objectkelompokpasienlastfk' => $request['objectkelompokpasienlastfk'],
                    ]
                );

            DB::commit();
            $result = array(
                "status" => 200,
                "message" =>  "Update Rekanan berhasil",
            );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" =>  "Somthing Went Wrong",
                "result" => $e->getMessage()
            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function loadListData()
    {

        $result['kelompokpasien'] = KelompokPasien::where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->mine()
            ->get();

        $result['rekanan'] = Rekanan::where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->mine()
            ->get();

        return $this->respond($result);
    }


    public function detailPiutangPasien(Request $request)
    {
        $spp = StrukPelayananPenjamin::where('norec', $request->norec_spp)
        ->where('kdprofile', $this->kdProfile)
        ->where('statusenabled',true)->first();
        $sbp = StrukBuktiPenerimaan::where('nostrukfk', $spp->nostrukfk)
        ->where('kdprofile', $this->kdProfile)
        ->where('statusenabled',true)
        ->orderBy('nosbm')->get();

        $detailPembayaran = array();
        foreach ($sbp as $item) {
            $detailPembayaran[] = array(
                'noSbm' => $item->nosbm,
                'tglPembayaran' => $item->tglsbm,
                'jlhPembayaran' => $item->totaldibayar,
                'jlhPembayaranKontraktor' => $item->totalpiutangdibayar
            );
        }
        $dibayarAwal = $spp->totalbiaya - $spp->totalppenjamin;
        $data = array(
            "totalTagihan" => $spp->totalbiaya,
            "jenis_piutang" => $spp->jenis_piutang,
            "sudahDibayar" => $spp->totalsudahdibayar + $dibayarAwal,
            "sisaPiutang" => $spp->totalbiaya - $dibayarAwal - $spp->totalsudahdibayar,
            "detailPembayaran" => $detailPembayaran
        );

        return $this->respond($data);
    }
}

