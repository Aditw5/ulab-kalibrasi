<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EMR\TindakanCtrl;
use App\Http\Controllers\Farmasi\InputResepCtrl;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayanan;
use App\Traits\Valet;
use Dompdf\Options;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BillingCtrl extends Controller
{
    use Valet;
    protected $tindakanCtrl;
    protected $inputResepCtrl;
    public function __construct(TindakanCtrl $tindakanCtrl, InputResepCtrl $inputResepCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->tindakanCtrl = $tindakanCtrl;
        $this->inputResepCtrl = $inputResepCtrl;
    }

    public function billingPasien(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $sDokterOperasi = $this->settingFix('jenisPetugasDokterOperasi');
        $sDokterAnastesi = $this->settingFix('jenisPetugasDokterAnastesi');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();

        if (empty($pd)) {
            $result['as'] = '@epic';
            return $this->respond($result);
        }

        $norecSpp = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->join('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->pluck('spp.norec')
            ->first();

        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJoin('strukpelayananpenjamin_t as spp', 'sp.norec', '=', 'spp.nostrukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftjoin('ruangan_m as ru1', 'ru1.id', '=', 'sr.ruanganfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('strukbuktipenerimaan_t as sbm', 'sbm.norec', '=', 'sp.nosbmlastfk')
            ->groupBy(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'ru1.namaruangan',
                'pp.hargasatuan',
                'pg.namalengkap',
                'apd.norec',
                'pp.keteranganlain',
                'pp.strukfk',
                'sbm.nosbm',
                'sr.noresep',
                'sp.totalprekanan_pasien'
            )
            ->select(
                'pp.norec',
                DB::raw("STRING_AGG(DISTINCT spp.norec, ',') as norec_spp"),
                DB::raw("SUM(DISTINCT spp.totalsudahdibayar) as totalsudahdibayar"),
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'ru1.namaruangan as ruangan',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'apd.norec as norec_apd',
                'pp.keteranganlain',
                'pp.strukfk',
                'sbm.nosbm',
                'sr.noresep',
                'sp.totalprekanan_pasien',

                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                    * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)
                as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
            ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd']);
        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        if (isset($r['istindakan']) && $r['istindakan'] != '' && $r['istindakan'] == 'true') {
            $data = $data->whereNull('pp.strukresepfk');
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'ptu.objectjenispetugaspefk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'jp.jenispetugaspe')
            ->where('ptu.kdprofile', $kdProfile)
            ->where(function ($query) use ($sDokterPemeriksa, $sDokterOperasi, $sDokterAnastesi) {
                $query->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterOperasi)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterAnastesi);
            })
            // ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            // ->whereIn('ptu.objectjenispetugaspefk', [6,17])
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['totalprekanan_pasien'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['totalprekanan_pasien']  = (float) $item->totalprekanan_pasien;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            // foreach ($pelayananpetugas as $itemd) {
            //     if ($itemd->pelayananpasien == $item->norec) {
            //         $item->dokterpemeriksa = $itemd->namalengkap;
            //     }
            // }
            // foreach ($pelayananpetugas as $itemd) {
            //     if ($itemd->pelayananpasien == $item->norec) {
            //         $item->dokterpemeriksa = $itemd->namalengkap;
            //         $item->dokteroperasi = $itemd->namalengkap;
            //         if ($itemd->jenispetugaspe == 'Dokter Anestesi') {
            //             $item->dokteranastesi = $itemd->namalengkap;
            //         }
            //     }
            // }
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                    // $item->dokteroperasi = $itemd->namalengkap;
                    if ($itemd->jenispetugaspe == 'Dokter Operasi 1') {
                        $item->dokteroperasi = $itemd->namalengkap;
                    }
                    elseif ($itemd->jenispetugaspe == 'Dokter Anestesi') {
                        $item->dokteranastesi = $itemd->namalengkap;
                    }
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }

        $sudahDibayar = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $norecSpp)
            ->value('totalsudahdibayar');
        $totalBiaya = DB::table('strukpelayananpenjamin_t')
                ->where('norec', $norecSpp)
                ->value('totalbiaya');
        $result['tarif_inacbg']  = $pd->inacbg_totalgrouper;
        $result['klaim']  = StrukPelayanan::totalKlaim($pd->noregistrasi);
        $result['deposit'] = StrukBuktiPenerimaan::deposit($pd->noregistrasi);
        $result['sudahBayar'] = $sudahDibayar;
        $result['dibayar'] = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
        $result['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($pd->noregistrasi);
        $result['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($pd->noregistrasi);
        $result['sisa'] =  $result['total'] -  $result['klaim'] - $result['deposit'] - $result['dibayar'] + $result['pengembalian'] - $result['totalprekanan_pasien'];
        // $result['sisa'] =   $result['total'] - $result['dibayar'] - $result['deposit'] - $result['klaim'] + $result['pengembalian']; // -  $result['diskon'];
        $result['length'] = count($data);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['as'] = '@epic';
        $result['norec_spp'] = $norecSpp;

        return $this->respond($result);
    }

    public function cetakBilling(Request $r)
    {   

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
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
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'ps.namapasien',
                'ps.noidentitas',
                'ps.nocm',
                'ru.namaruangan',
                'kl.namakelas',
                'kp.kelompokpasien',
                'dp.namadepartemen',
                'jk.jeniskelamin',
                'pg.namalengkap',
                'rk.namarekanan',
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->leftjoin('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sp.objectpegawaipenerimafk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->select(
                'pp.norec',
                'sp.totalprekanan_pasien',
                'sp.nostruk',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'ru2.namaruangan as ruanganfarmasi',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'pg1.namalengkap as petugas',
                'pg1.noidentitas as nikpetugas',
                'sp.objectpegawaipenerimafk',
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
            ->where('pp.noregistrasi', $r['noregistrasi'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $sDokterOperasi = $this->settingFix('jenisPetugasDokterOperasi');
        $sDokterAnastesi = $this->settingFix('jenisPetugasDokterAnastesi');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'ptu.objectjenispetugaspefk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'jp.jenispetugaspe')
            ->where('ptu.kdprofile', $this->kdProfile)
            ->where(function ($query) use ($sDokterPemeriksa, $sDokterOperasi, $sDokterAnastesi) {
                $query->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterOperasi)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterAnastesi);
            })
            // ->whereIn('ptu.objectjenispetugaspefk', [6,17])
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        $res['totalprekanan_pasien'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->dokteranastesi = $item->strukresepfk;
            $item->dokteroperasi = $item->strukresepfk;
            $res['total']  = (float) $res['total']  + (float) $item->total;
            $res['totalprekanan_pasien']  = (float) $item->totalprekanan_pasien;
            // foreach ($pelayananpetugas as $itemd) {
            //     if ($itemd->pelayananpasien == $item->norec) {
            //         $item->dokter = $itemd->namalengkap;
            //     }
            // }
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                    // $item->dokteroperasi = $itemd->namalengkap;
                    if ($itemd->jenispetugaspe == 'Dokter Operasi 1') {
                        $item->dokteroperasi = $itemd->namalengkap;
                    }
                    elseif ($itemd->jenispetugaspe == 'Dokter Anestesi') {
                        $item->dokteranastesi = $itemd->namalengkap;
                    }
                }
            }
            $res['ttePetugas'] = base64_encode(QrCode::format('svg')->size(75)->generate(date('d/m/Y H:i'). "\n" .$item->nikpetugas. "\n" .$item->petugas));
            $res['namaPetugas'] = $item->petugas;
        }
        $res['ttePasien'] = base64_encode(QrCode::format('svg')->size(75)->generate(date('d/m/Y H:i'). "\n" .$res['identitas']->nocm . "\n" .$res['identitas']->noidentitas . "\n" .$res['identitas']->namapasien));
        $sudahDibayar = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalsudahdibayar');
        $totalBiaya = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalbiaya');
        $res['total'] = round($res['total']);
        // $res['total_biaya']  = $totalBiaya;
        $res['billing'] =  $data->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $res['sudahBayar'] = round($sudahDibayar);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] = $res['total'] - $res['deposit'] - $res['klaim'] - $res['dibayar'] + $res['pengembalian'] - $res['totalprekanan_pasien'];
        // $res['sisa'] =   round($res['total'])  -  $res['dibayar'] - $res['deposit'] - $res['klaim'] + $res['pengembalian'];
        $res['totalklaimiur'] = $res['total'] - $res['iurbayar'];
        $res['pdf']  = $r['pdf'];

        $res['sisa'] = round($res['sisa']);

        $blade = 'report.kasir.billing';


        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing';
        }
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
                $blade.'-dom',
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

    public function hapusTindakan(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {
                PelayananPasienDetail::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienPetugas::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasien::where('norec', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();

                $this->LOGGING(
                    'Hapus Tindakan',
                    $item['norec_pp'],
                    'pelayananpasien_t',
                    'Hapus Tindakan ' . $item['namaproduk'] . ' di ' . $item['namaruangan'] . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );
            }


            $transStatus = true;
        } catch (\Exception $e) {
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
            $transMessage = "Hapus Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function detailPetugasTindakan(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasienpetugas_t as pp')
            ->join('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'pp.objectjenispetugaspefk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pp.objectpegawaifk')
            ->select(
                'pp.norec',
                'pg.namalengkap',
                'jp.jenispetugaspe',
                'pp.objectpegawaifk',
                'pp.objectjenispetugaspefk',
                'pp.nomasukfk',
                'pp.pelayananpasien'
            )
            ->where('pp.pelayananpasien', $r['norec'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();

        return $this->respond($result);
    }

    public function saveJenisPetugasTindakan(Request $r)
    {
        DB::beginTransaction();
        try {
            if ($r['norec'] == '') {
                $log = 'Input ';
                $new_PPP = new PelayananPasienPetugas();
                $new_PPP->norec = $new_PPP->generateNewId();
                $new_PPP->kdprofile = $this->kdProfile;
                $new_PPP->statusenabled = true;
            } else {
                $new_PPP = PelayananPasienPetugas::where('norec', $r['norec'])->first();
                $log = 'Ubah ';
            }

            $new_PPP->nomasukfk = $r['nomasukfk'];
            $new_PPP->objectjenispetugaspefk = $r['objectjenispetugaspefk'];
            $new_PPP->objectpegawaifk = $r['objectpegawaifk'];
            $new_PPP->pelayananpasien = $r['pelayananpasien'];
            $new_PPP->noregistrasi = $r['noregistrasi'];
            $new_PPP->save();

            $pg =  Pegawai::where('id', $r['objectpegawaifk'])->first();
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                $log . 'Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
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
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deleteJenisPetugasTindakan(Request $r)
    {
        DB::beginTransaction();
        try {

            PelayananPasienPetugas::where('norec', $r['norec'])->delete();

            $ps = PasienDaftar::detailPasien($r['noregistrasi']);
            $pg =  Pegawai::where('id', $r['objectpegawaifk'])->first();
            $this->LOGGING(
                'Hapus Petugas Tindakan',
                $r['norec'],
                'pelayananpasienpetugas_t',
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $ps->ruanganlast . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
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
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function updateTglTindakan(Request $r)
    {
        DB::beginTransaction();
        try {

            PelayananPasien::where('norec', $r['norec'])->update([
                'tglpelayanan' => $r['tglpelayanan']
            ]);

            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                'Ubah Tgl Pelayanan',
                $r['norec'],
                'pelayananpasien_t',
                'Ubah Tgl Pelayanan ' . $r['tglpelayanan'] . ' ' . $r['namaproduk'] . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
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
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function detailKomponenTindakan(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $result = DB::table('pelayananpasiendetail_t as pp')
            ->join('komponenharga_m as km', 'km.id', '=', 'pp.komponenhargafk')
            ->select(
                'pp.norec',
                'km.komponenharga',
                'pp.hargadiscount',
                'pp.hargasatuan',
                'pp.jumlah',
                'pp.pelayananpasien',
                'pp.jasa'
            )
            ->where('pp.pelayananpasien', $r['norec'])
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->get();
        $res['data'] = $result;
        $total = 0;
        foreach ($result as $d) {
            $total =  $total + ((($d->hargasatuan  - $d->hargadiscount)  * $d->jumlah) + $d->jasa);
        }
        $res['total'] = $total;

        return $this->respond($res);
    }
    public function updateDiskon(Request $r)
    {
        DB::beginTransaction();
        try {
            $nilaiCito = 0;
            $totalJasa = 0;
            if ($r['jasa'] != 0) {
                $nilaiCito = (float) $this->settingFix('tarifCito');
            }
            PelayananPasienDetail::where('norec', $r['norec'])
                ->update(
                    [
                        'hargadiscount' => $r['hargadiscount'],
                        'jasa' => ($r['hargasatuan'] - $r['hargadiscount']) * $nilaiCito,
                    ]
                );
            $totalDiskon = 0.0;
            $dataaa = PelayananPasienDetail::where('pelayananpasien', $r['norec_pp'])->get();
            foreach ($dataaa as $item) {
                $totalDiskon = $totalDiskon + $item->hargadiscount;
                $totalJasa = $totalJasa + $item->jasa;
            }
            PelayananPasien::where('norec', $r['norec_pp'])
                ->update([
                    'hargadiscount' => $totalDiskon,
                    'jasa' => $totalJasa
                ]);
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);
            $this->LOGGING(
                'Diskon Komponen',
                $r['norec'],
                'pelayananpasiendetail_t',
                'Diskon Komponen Rp. ' . $r['hargadiscount'] . ' '  . $r['namaproduk'] . ' pada Pasien ' .
                    $ps->namapasien . ' (' .  $ps->nocm . ') - ' . $r['noregistrasi']
            );
            $transStatus = true;
        } catch (\Exception $e) {
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
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function cetakBuktiLayananJasa(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        $profile = $this->profile();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $idKomponeJasaMedis = $this->settingFix('komponenHargaJasaDokter');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM (SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   (select case when hargasatuan is null then 0 else hargasatuan end as hargajual from pelayananpasiendetail_t where pelayananpasien=tp.norec and komponenhargafk=$idKomponeJasaMedis limit 1) as hargasatuan,
                   (select case when hargadiscount is null then 0 else hargadiscount end as hargadiscount from pelayananpasiendetail_t where pelayananpasien=tp.norec and komponenhargafk=$idKomponeJasaMedis limit 1) as diskon,
                   (select case when jasa is null then 0 else jasa end as jasa from pelayananpasiendetail_t where pelayananpasien=tp.norec and komponenhargafk=$idKomponeJasaMedis limit 1) as jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile AND tp.statusenabled = true
            $paramsPp

            ) AS x
            ORDER BY x.tglpelayanan
        "));
        $pageWidth = 950;
        // $totalbayar = $data->totaldibayar;
        // $terbilang = $this->terbilang($totalbayar); //strtoupper($this->terbilang($totalbayar));
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI LAYANAN JASA MEDIS",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.kasir.bukti-layanan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakBuktiLayananPerTindakan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        $profile = $this->profile();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        // dd($data);
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM ( SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   tp.hargajual as hargasatuan,CASE WHEN tp.hargadiscount IS NULL THEN 0 ELSE tp.hargadiscount END AS diskon,
                   CASE WHEN tp.jasa IS NULL THEN 0 ELSE tp.jasa END AS jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile
            AND tp.statusenabled = true
            $paramsPp

            ) AS x
            ORDER BY x.tglpelayanan
        "));

        $pageWidth = 950;

        // $totalbayar = $data->totaldibayar;
        // $terbilang = $this->terbilang($totalbayar); //strtoupper($this->terbilang($totalbayar));
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI LAYANAN RUANGAN PER TINDAKAN",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.kasir.bukti-layanan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien,
               pd.tglregistrasi,jk.reportdisplay AS jk,ru2.namaruangan AS ruanganperiksa,ru.namaruangan AS ruangakhir,
               ks.namakelas,ar.asalrujukan,ps.notelepon,CASE WHEN rek.namarekanan is null then '-' else rek.namarekanan END as namapenjamin,
               CASE WHEN kmr.namakamar is null then '-' else kmr.namakamar END as namakamar,alm.alamatlengkap,kp.kelompokpasien,pp.namalengkap AS dpjp
        FROM pasiendaftar_t AS pd
        INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
        INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
        INNER JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
        INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.noregistrasifk = pd.norec
        INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
        LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
        LEFT JOIN kelas_m AS ks ON apdp.objectkelasfk = ks.id
        LEFT JOIN asalrujukan_m AS ar ON apdp.objectasalrujukanfk = ar.id
        left JOIN rekanan_m AS rek ON rek.id= pd.objectrekananfk
        left JOIN kamar_m as kmr on apdp.objectkamarfk=kmr.id
        INNER join ruangan_m  as ru2 on ru2.id=apdp.objectruanganfk
        LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
        WHERE pd.kdprofile = $kdProfile AND pd.statusenabled = true AND pd.noregistrasi = '$noregistrasi'
"))->first();
        return $data;
    }
    public function cetakBuktiLayananRuangan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KodeJasaMedis = $this->settingFix('KdKomponenTarifJasaDokter');
        $noregistrasi = $request['noregistrasi'];
        $user = $request['user'];
        $norecApd = $request['norec_apd'];
        $profile = $this->profile();
        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');

        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM (SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah,
                   tp.hargajual as hargasatuan,CASE WHEN tp.hargadiscount IS NULL THEN 0 ELSE tp.hargadiscount END AS diskon,
                   CASE WHEN tp.jasa IS NULL THEN 0 ELSE tp.jasa END AS jasa
            FROM pelayananpasien_t AS tp
            LEFT JOIN produk_m AS pro ON tp.produkfk = pro.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.norec = tp.noregistrasifk
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            WHERE tp.kdprofile = $kdProfile AND tp.statusenabled = true
            and apdp.norec = '$norecApd'
            ) AS x
            ORDER BY x.tglpelayanan
        "));


        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI LAYANAN",
            'header' => $data,
            'details' =>  $details,
        );

        return view(
            'report.kasir.bukti-layanan',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    public function detailKonversiHarga(Request $request) {

        $idProfile = (int) $this->kdProfile;

        $pelayanan = DB::table('pasiendaftar_t as pd')
        ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
        ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
        ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
        ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
        ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
        ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
        ->leftjoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
        ->select('pp.*', 'pr.id as prid', 'kl.id as klid', 'kl.namakelas',
            'ru.id as ruid', 'ru.namaruangan', 'sp.nostruk', 'sp.tglstruk', 'apd.norec as norec_apd',
            'sbm.nosbm', 'sp.norec as norec_sp', 'pd.nocmfk', 'apd.objectruanganfk', 'pd.jenispelayanan',
            'pd.nostruklastfk','pd.noregistrasi',
            'pd.tglregistrasi', 'pd.norec as norec_pd', 'pd.tglpulang',
            'pd.objectrekananfk as rekananid',
            'sp.totalharusdibayar', 'sp.totalprekanan',
            'sp.totalbiayatambahan', 'pd.kdprofile','pd.statuspasien','sr.ruanganfk','pr.namaproduk')
        ->where('pd.kdprofile', $idProfile)
        ->where('pd.norec',  $request['norec_pd']);

        $pelayanandetail = DB::table('pasiendaftar_t as pd')
        ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
        ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
        ->leftjoin('pelayananpasiendetail_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
        ->select('ppd.*')
        ->where('pd.kdprofile', $idProfile)
        ->where('pd.norec',  $request['norec_pd']);

        if(isset($request['idruangan']) && $request['idruangan']!="" && $request['idruangan']!="undefined"){
            $pelayanan = $pelayanan->where('apd.objectruanganfk','=', $request['idruangan']);
            $pelayanandetail = $pelayanandetail->where('apd.objectruanganfk','=', $request['idruangan']);
        }

        if(isset($request['tglawal']) && $request['tglawal']!="" && $request['tglawal']!="undefined"  && $request['tglawal']!="null"){
            $pelayanan = $pelayanan->where('pp.tglpelayanan','>=', $request['tglawal']);
            $pelayanandetail = $pelayanandetail->where('pp.tglpelayanan','>=', $request['tglawal']);

        }

        if(isset($request['tglakhir']) && $request['tglakhir']!="" && $request['tglakhir']!="undefined"  && $request['tglakhir'] !="null"){
            $pelayanan = $pelayanan->where('pp.tglpelayanan','<=', $request['tglakhir']);
            $pelayanandetail = $pelayanandetail->where('pp.tglpelayanan','<=', $request['tglakhir']);
        }

        $pelayanan->orderBy('pp.tglpelayanan');
        $pelayanan = $pelayanan->orderBy('pp.rke')->get();
        $pelayanandetail->orderBy('pp.tglpelayanan');
        $pelayanandetail = $pelayanandetail->orderBy( 'pp.rke')->get();

        if (count($pelayanan) > 0) {
            $details = array();
            foreach ($pelayanan as $value) {
                if ($value->produkfk == $this->settingFix('idProdukDeposit')) {
                    continue;
                }
                if ($value->namaproduk == null){
                    continue;
                }
                $jasa = 0;
                if (isset($value->jasa) && $value->jasa != "" && $value->jasa != "undefined") {
                    $jasa = $value->jasa;
                }
                $kmpn = [];

                $harga = (float)$value->hargajual;
                $diskon = (float)$value->hargadiscount;

                $value->harga = $harga;
                $value->diskon = $diskon;
                $value->total = (($harga - $diskon) * $value->jumlah) + $jasa;
                $value->strukfk = $value->nostruk . ' / ' . $value->nosbm;
                $value->jasa = $jasa;
                foreach($pelayanandetail as $detail){
                    if($value->norec !== $detail->pelayananpasien) continue;
                    $value->detailpelayanan[] = $detail;
                }
                $details[] = $value;
            }
        }
        $arrHsil = array(
            'details' => $details
        );
        return $this->respond($arrHsil);
    }
    public function detailKonversiHargaDropdown(Request $request) {
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        $res['kelas'] = Kelas::mine()->get();
        return $this->respond($res);
    }
    public function konversiharga(Request $request) {

        DB::beginTransaction();
        try {
            $kdProfile = (int) $this->kdProfile;
            $noRegister = $request['noregistrasi'];
            $pelayananpasien = [];
            $pelayananpasiendetail = [];
            $SET['komponenHargaProfit'] = $this->settingFix('komponenHargaProfit');

            $datatagihan = $request['data'];

            foreach($datatagihan as $data) {
                DB::table('pelayananpasien_temp_t')->where('noregistrasifk', $data['noregistrasifk'])->delete();
                DB::table('pelayananpasiendetail_temp_t')->where('noregistrasifk', $data['noregistrasifk'])->delete();
                if ($data['produkfk'] == $this->settingFix('idProdukDeposit')) {
                    continue;
                }
                if($data['strukresepfk'] == null){
                    // tindakan
                    $objetoRequest = new \Illuminate\Http\Request();
                    $objetoRequest ['idRuangan'] =  $data['objectruanganfk'];
                    $objetoRequest ['idKelas'] =    $request['idKelas'];
                    $objetoRequest ['idProduk'] =  $data['produkfk'];
                    $objetoRequest ['idJenisPelayanan'] =  $data['jenispelayanan'];
                    $objetoRequest ['idPenjamin'] =  $request['idPenjamin'];

                    $datkonversi = $this->tindakanCtrl->listTindakanKomponen(
                        $objetoRequest, true
                    );

                    if (count($datkonversi['komponen']) == 0) continue;
                    $jasa = 0;
                    if(isset($data['detailpelayanan'])){

                        $detailpelayanan = $data['detailpelayanan'];

                        // return $datkonversi['komponen'];
                        // return $detailpelayanan;
                        $norecdetailprev = '';
                        foreach ($detailpelayanan as $detail) {
                            if (count($datkonversi['komponen']) == 0) continue;

                            foreach ($datkonversi['komponen'] as $komponen) {
                                if ($komponen->objectkomponenhargafk == $detail['komponenhargafk']) {
                                    if ($detail['norec'] === $norecdetailprev) continue;
                                    if ((float)$detail['hargajual'] > 0) {
                                        $jasa = ((float)$komponen->hargasatuan - (float)$detail['hargajual']) * ((float)$detail['jasa'] / (float)$detail['hargajual']);
                                        $jasa = $detail['jasa'] + $jasa;
                                    }

                                    $pelayananpasiendetail[] = [
                                        "norec" => $detail['norec'],
                                        "kdprofile" => $detail['kdprofile'],
                                        "statusenabled" => $detail['statusenabled'],
                                        "noregistrasifk" => $detail['noregistrasifk'],
                                        'tglregistrasi' => $detail['tglregistrasi'],
                                        "aturanpakai" => $detail['aturanpakai'],
                                        'generik' => $detail['generik'],
                                        "hargadiscount" => $detail['hargadiscount'],
                                        "hargajual" => $detail['hargajual'],
                                        "hargasatuan" => $detail['hargasatuan'],
                                        'jenisobatfk' => $detail['jenisobatfk'],
                                        "jumlah" => $detail['jumlah'],
                                        "keteranganlain" => $detail['keteranganlain'],
                                        "keteranganpakai2" => $detail['keteranganpakai2'],
                                        "komponenhargafk" => $detail['komponenhargafk'],
                                        "pelayananpasien" => $detail['pelayananpasien'],
                                        "piutangpenjamin" => $detail['piutangpenjamin'],
                                        "piutangrumahsakit" => $detail['piutangrumahsakit'],
                                        "produkfk" => $detail['produkfk'],
                                        'routefk' => $detail['routefk'],
                                        "stock" => $detail['stock'],
                                        "tglpelayanan" => $detail['tglpelayanan'],
                                        "harganetto" => $detail['harganetto'],
                                        "hargadijamin" => $detail['hargadijamin'],
                                        "jasa" => $jasa,
                                        "harganettokonversi" => $komponen->hargasatuan,
                                        "hargajualkonversi" => $komponen->hargasatuan,
                                        "hargasatuankonversi" => $komponen->hargasatuan,
                                    ];


                                }
                                $norecdetailprev = $detail['norec'];
                            }
                        }


                        $pelayananpasien[] = [
                            "norec" => $data['norec'],
                            "kdprofile" => $data['kdprofile'],
                            "statusenabled" => $data['statusenabled'],
                            "noregistrasifk" => $data['noregistrasifk'],
                            "tglregistrasi" => $data['tglregistrasi'],
                            'aturanpakai' => $data['aturanpakai'],
                            'generik' => $data['generik'],
                            "hargadiscount" => $data['hargadiscount'],
                            "hargajual" => $data['hargajual'],
                            "hargasatuan" => $data['hargasatuan'],
                            'jenisobatfk' => $data['jenisobatfk'],
                            "jumlah" => $data['jumlah'],
                            "kelasfk" => $data['kelasfk'],
                            "kdkelompoktransaksi" => $data['kdkelompoktransaksi'],
                            "keteranganlain" => $data['keteranganlain'],
                            "piutangpenjamin" => $data['piutangpenjamin'],
                            "piutangrumahsakit" => $data['piutangrumahsakit'],
                            "produkfk" => $data['produkfk'],
                            'routefk' => $data['routefk'],
                            "stock" => $data['stock'],
                            "tglpelayanan" => $data['tglpelayanan'],
                            "harganetto" => $data['harganetto'],
                            'jeniskemasanfk' => $data['jeniskemasanfk'],
                            'rke' => $data['rke'],
                            'strukresepfk' => $data['strukresepfk'],
                            'satuanviewfk' => $data['satuanviewfk'],
                            'nilaikonversi' => $data['nilaikonversi'],
                            'strukterimafk' => $data['strukterimafk'],
                            'dosis' => $data['dosis'],

                            'qtydetailresep' => $data['qtydetailresep'],
                            'isobat' => $data['isobat'],
                            'ispagi' => $data['ispagi'],
                            'issiang' => $data['issiang'],
                            'ismalam' => $data['ismalam'],
                            'issore' => $data['issore'],
                            'keteranganpakai' => $data['keteranganpakai'],
                            'iskronis' => $data['iskronis'],
                            "iscito" => $data['iscito'],
                            "isparamedis" => $data['isparamedis'],
                            "jenispelayananfk" => $data['jenispelayananfk'],
                            "istarifdetault" => $data['istarifdetault'],
                            "hargadijamin" => $data['hargadijamin'],
                            "istuslah" => $data['istuslah'],
                            'satuanresepfk' => $data['satuanresepfk'],
                            'tglkadaluarsa' => $data['tglkadaluarsa'],
                            "jasa" => $jasa,
                            "harganettokonversi" => $datkonversi['harga']->hargasatuan,
                            "hargajualkonversi" => $datkonversi['harga']->hargasatuan,
                            "hargasatuankonversi" => $datkonversi['harga']->hargasatuan,
                        ];
                    }
                }else{
                     //obat
                     $hargaupnya = 0;
                     $harganettonya = 0;
                     $nilaikonversi = $data['nilaikonversi'] == null ? 1 : (float)$data['nilaikonversi'];
                     $objetoRequest = new \Illuminate\Http\Request();
                     $objetoRequest ['produkfk'] =  $data['produkfk'];
                     $objetoRequest ['ruanganfk'] = $data['ruanganfk'];
                     $objetoRequest ['kpid'] =  $request['idKelPasien'];
                     $objetoRequest ['norec_apd'] =  $data['norec_apd'];
                     $datkonversi = $this->inputResepCtrl->getProdukDetail($objetoRequest,true);

                     if(count($datkonversi['detail']) == 0) continue;
                     foreach ($datkonversi['detail'] as $datakon)
                     {
                         $hargaupnya = (float)$datakon['hargajual'] * $nilaikonversi;
                         $harganettonya = (float)$datakon['hargajual'];
                     }
                     $detailpelayanan = $data['detailpelayanan'];
                     foreach($detailpelayanan as $detail) {
                         $pelayananpasiendetail[] = [
                             'norec' => $detail['norec'],
                             'kdprofile' => $detail['kdprofile'],
                             'statusenabled' => $detail['statusenabled'],
                             'noregistrasifk' => $detail['noregistrasifk'],
                             'tglregistrasi' => $detail['tglregistrasi'],
                             'aturanpakai' => $detail['aturanpakai'],
                             'generik' => $detail['generik'],
                             'hargadiscount' => $detail['hargadiscount'],
                             'hargajual' => $detail['hargajual'],
                             'hargasatuan' => $detail['hargasatuan'],
                             'jenisobatfk' => $detail['jenisobatfk'],
                             'jumlah' => $detail['jumlah'],
                             "keteranganlain" => $detail['keteranganlain'],
                             "keteranganpakai2" => $detail['keteranganpakai2'],
                             'komponenhargafk' => $detail['komponenhargafk'],
                             'pelayananpasien' => $detail['pelayananpasien'],
                             "piutangpenjamin" => $detail['piutangpenjamin'],
                             "piutangrumahsakit" => $detail['piutangrumahsakit'],
                             'produkfk' => $detail['produkfk'],
                             'routefk' => $detail['routefk'],
                             'stock' => $detail['stock'],
                             'tglpelayanan' => $detail['tglpelayanan'],
                             'harganetto' => $detail['harganetto'],
                             "hargadijamin" => $detail['hargadijamin'],
                             'jasa' => $detail['jasa'],
                             "harganettokonversi" => $detail['komponenhargafk'] == $SET['komponenHargaProfit']? $hargaupnya - $harganettonya : $hargaupnya,
                             "hargajualkonversi" => $detail['komponenhargafk'] == $SET['komponenHargaProfit']? $hargaupnya - $harganettonya : $hargaupnya,
                             "hargasatuankonversi" => $detail['komponenhargafk'] == $SET['komponenHargaProfit']? $hargaupnya - $harganettonya : $hargaupnya,
                         ];
                     }
                     $pelayananpasien[] = [
                         'norec' => $data['norec'],
                         'kdprofile' => $data['kdprofile'],
                         'statusenabled' => $data['statusenabled'],
                         'noregistrasifk' => $data['noregistrasifk'],
                         'tglregistrasi' => $data['tglregistrasi'],
                         'aturanpakai' => $data['aturanpakai'],
                         'generik' => $data['generik'],
                         'hargadiscount' => $data['hargadiscount'],
                         'hargajual' => $data['hargajual'],
                         'hargasatuan' => $data['hargasatuan'],
                         'jenisobatfk' => $data['jenisobatfk'],
                         'jumlah' => $data['jumlah'],
                         'kelasfk' => $data['kelasfk'],
                         'kdkelompoktransaksi' => $data['kdkelompoktransaksi'],
                         "keteranganlain" => $data['keteranganlain'],
                         "piutangpenjamin" => $data['piutangpenjamin'],
                         "piutangrumahsakit" => $data['piutangrumahsakit'],
                         'produkfk' => $data['produkfk'],
                         'routefk' => $data['routefk'],
                         'stock' => $data['stock'],
                         'tglpelayanan' => $data['tglpelayanan'],
                         'harganetto' => $data['harganetto'],
                         'jeniskemasanfk' => $data['jeniskemasanfk'],
                         'rke' => $data['rke'],
                         'strukresepfk' => $data['strukresepfk'],
                         'satuanviewfk' => $data['satuanviewfk'],
                         'nilaikonversi' => $data['nilaikonversi'],
                         'strukterimafk' => $data['strukterimafk'],
                         'dosis' => $data['dosis'],
                         'qtydetailresep' => $data['qtydetailresep'],
                         'isobat' => $data['isobat'],
                         'ispagi' => $data['ispagi'],
                         'issiang' => $data['issiang'],
                         'ismalam' => $data['ismalam'],
                         'issore' => $data['issore'],
                         'keteranganpakai' => $data['keteranganpakai'],
                         'iskronis' => $data['iskronis'],
                         "iscito" => $data['iscito'],
                         "isparamedis" => $data['isparamedis'],
                         "jenispelayananfk" => $data['jenispelayananfk'],
                         "istarifdetault" => $data['istarifdetault'],
                         "hargadijamin" => $data['hargadijamin'],
                         "istuslah" => $data['istuslah'],
                         'satuanresepfk' => $data['satuanresepfk'],
                         'tglkadaluarsa' => $data['tglkadaluarsa'],
                         'jasa' => $data['jasa'],
                         "harganettokonversi" => $hargaupnya,
                         "hargajualkonversi" => $hargaupnya,
                         "hargasatuankonversi" => $hargaupnya,
                     ];

                }

            }

            DB::table('pelayananpasien_temp_t')->insert($pelayananpasien);
            DB::table('pelayananpasiendetail_temp_t')->insert($pelayananpasiendetail);
            DB::commit();
            $datakonversi = $this->detailHasilKonversiHargaLayanan($kdProfile, $noRegister, $request['tglawal'], $request['tglakhir'], isset($request['idRuangan']) ? $request['idRuangan'] : 0);
            $transMessage = "Konversi data Berhasil.";
            $result = array(
                "status" => 200,
                "result" => array(
                    "details"  => $datakonversi,
                    "as" => '@epic',
                ),
            );
        }  catch (Exception $e) {
            DB::rollBack();
            $transMessage = "Konversi data Gagal. " . (count($pelayananpasien) == 0 ? 'harga tujuan tidak ada':'');
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function detailHasilKonversiHargaLayanan ($idProfile, $noRegister, $tglawal, $tglakhir, $idRuangan){
        $pelayanan = DB::table('pasiendaftar_t as pd')
        ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
        ->leftjoin('pelayananpasien_temp_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
        ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
        ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
        ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
        ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
        ->leftjoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
        ->select('pp.*', 'pr.id as prid', 'kl.id as klid', 'kl.namakelas',
            'ru.id as ruid', 'ru.namaruangan', 'sp.nostruk', 'sp.tglstruk', 'apd.norec as norec_apd',
            'sbm.nosbm', 'sp.norec as norec_sp', 'pd.nocmfk', 'apd.objectruanganfk', 'pd.jenispelayanan',
            'pd.nostruklastfk','pd.noregistrasi',
            'pd.tglregistrasi', 'pd.norec as norec_pd', 'pd.tglpulang',
            'pd.objectrekananfk as rekananid',
            'sp.totalharusdibayar', 'sp.totalprekanan',
            'sp.totalbiayatambahan', 'pd.kdprofile','pd.statuspasien', 'sr.ruanganfk', 'pr.namaproduk')
        ->where('pd.kdprofile', $idProfile)
        ->where('pd.noregistrasi', $noRegister);

        if(isset($tglawal) && $tglawal!="" && $tglawal!="undefined"  && $tglawal!="null"){
            $pelayanan= $pelayanan->where('pp.tglpelayanan', '>=', $tglawal);
        }
        if(isset($tglakhir) && $tglakhir!="" && $tglakhir!="undefined"  && $tglakhir!="null"){
            $pelayanan= $pelayanan->where('pp.tglpelayanan', '<=', $tglakhir);
        }
        if($idRuangan != 0) {
            $pelayanan = $pelayanan->where('apd.objectruanganfk', $idRuangan);
        }
        $pelayanan = $pelayanan->orderBy('pp.tglpelayanan');
        $pelayanan = $pelayanan->orderBy( 'pp.rke');
        $pelayanan = $pelayanan->get();

        $pelayanandetail = DB::table('pasiendaftar_t as pd')
        ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
        ->leftjoin('pelayananpasien_temp_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
        ->leftjoin('pelayananpasiendetail_temp_t as ppd', 'pp.norec', '=', 'ppd.pelayananpasien')
        ->select('ppd.*')
        ->where('pd.kdprofile', $idProfile)
        ->where('pd.noregistrasi', $noRegister);

        if(isset($tglawal) && $tglawal!="" && $tglawal!="undefined"  && $tglawal!="null"){
            $pelayanandetail= $pelayanandetail->where('pp.tglpelayanan', '>=', $tglawal);
        }
        if(isset($tglakhir) && $tglakhir!="" && $tglakhir!="undefined"  && $tglakhir!="null"){
            $pelayanandetail= $pelayanandetail->where('pp.tglpelayanan', '<=', $tglakhir);
        }

        $pelayanandetail= $pelayanandetail->orderBy('pp.tglpelayanan');
        $pelayanandetail= $pelayanandetail->orderBy('pp.rke');
        $pelayanandetail= $pelayanandetail->get();

        $details = array();
        if (count($pelayanan) > 0) {
            foreach ($pelayanan as $value) {
                if ($value->produkfk ==$this->settingFix('idProdukDeposit')) {
                    continue;
                }
                if ($value->namaproduk == null){
                    continue;
                }
                $jasa = 0;
                if (isset($value->jasa) && $value->jasa != "" && $value->jasa != "undefined") {
                    $jasa = $value->jasa;
                }
                $kmpn = [];

                $harga = (float)$value->hargajual;
                $hargakonversi = (float)$value->hargajualkonversi;
                $diskon = (float)$value->hargadiscount;

                $value->harga = $harga;
                $value->diskon = $diskon;
                $value->total = (($harga - $diskon) * $value->jumlah) + $jasa;
                $value->strukfk = $value->nostruk . ' / ' . $value->nosbm;
                $value->jasa = $jasa;
                $value->hargakonversi = $hargakonversi;
                $value->totalkonversi = (($hargakonversi - $diskon) * $value->jumlah) + $jasa;
                foreach($pelayanandetail as $detail){
                    if($value->norec !== $detail->pelayananpasien) continue;
                    $value->detailpelayanan[] = $detail;
                }
                $details[] = $value;
            }
        }

        return $details;
    }

    public function simpankonversiharga(Request $request) {

        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $norecpp = [];
            $jasa = [];
            $hargajual = [];
            $hargasatuan = [];
            $harganetto = [];
            $norec_apd = '';

            $datatagihan = $request['data'];
            foreach($datatagihan as $data) {
                if ($data['produkfk'] ==$this->settingFix('idProdukDeposit')) {
                    continue;
                }

                $jasa[] = "WHEN '{$data['norec']}' then ". (is_null($data['jasa']) ? 0 : $data['jasa']);
                $hargajual[] = "WHEN '{$data['norec']}' then ". (is_null($data['hargajualkonversi']) ? 0 : $data['hargajualkonversi']);
                $hargasatuan[] = "WHEN '{$data['norec']}' then ". (is_null($data['hargasatuankonversi']) ? 0 : $data['hargasatuankonversi']);
                $harganetto[] = "WHEN '{$data['norec']}' then ". (is_null($data['harganettokonversi']) ? 0 : $data['harganettokonversi']);
                $norecpp[] = "'".$data['norec']."'";

                $norecppd = [];
                $jasad = [];
                $hargajuald = [];
                $hargasatuand = [];
                $harganettod = [];

                $detailpelayanan = $data['detailpelayanan'];
                foreach($detailpelayanan as $detail) {
                    $jasad[] = "WHEN '{$detail['norec']}' then ". (is_null($detail['jasa']) ? 0 : $detail['jasa']);
                    $hargajuald[] = "WHEN '{$detail['norec']}' then ". (is_null($detail['hargajualkonversi']) ? 0 : $detail['hargajualkonversi']);
                    $hargasatuand[] = "WHEN '{$detail['norec']}' then ". (is_null($detail['hargasatuankonversi']) ? 0 : $detail['hargasatuankonversi']);
                    $harganettod[] = "WHEN '{$detail['norec']}' then ". (is_null($detail['harganettokonversi']) ? 0 : $detail['harganettokonversi']);
                    $norecppd[] = "'".$detail['norec']."'";
                }
                $norecppd = implode(',', $norecppd);
                $hargajuald = implode(' ', $hargajuald);
                $hargasatuand = implode(' ', $hargasatuand);
                $harganettod = implode(' ', $harganettod);
                $jasad = implode(' ', $jasad);
                if (!empty($norecppd)) {
                    DB::update("UPDATE pelayananpasiendetail_t SET jasa = CASE norec {$jasad} END, hargajual = CASE norec {$hargajuald} END, hargasatuan = CASE norec {$hargasatuand} END, harganetto = CASE norec {$harganettod} END WHERE kdprofile = $kdProfile and norec in ({$norecppd})");
                }
                $norec_apd = $data['norec_apd'];
            }
            $norecpp = implode(',', $norecpp);
            $hargajual = implode(' ', $hargajual);
            $hargasatuan = implode(' ', $hargasatuan);
            $harganetto = implode(' ', $harganetto);
            $jasa = implode(' ', $jasa);
            if (!empty($norecpp)) {
                DB::update("UPDATE pelayananpasien_t SET jasa = CASE norec {$jasa} END, hargajual = CASE norec {$hargajual} END, hargasatuan = CASE norec {$hargasatuan} END, harganetto = CASE norec {$harganetto} END WHERE kdprofile = $kdProfile and  norec in ({$norecpp})");
                $updateapd = DB::table('antrianpasiendiperiksa_t')->where('norec', $norec_apd)->update(['objectkelasfk' => $request['idKelas']]);
            }
            DB::commit();
            $transMessage = "Konversi data Berhasil.";
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        }  catch (Exception $e) {
            DB::rollBack();
            $transMessage = "Konversi data Gagal.";
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function cetakBillingTerverif(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;
        $res['user'] = $r['user'];
        $res['noregistrasi'] = $r['noregistrasi'];
        $res['identitas'] =  DB::table('pasiendaftar_t as pd')
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
                'pa.nosep'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.noregistrasi', $r['noregistrasi'])
            ->first();

        $data =  DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->leftjoin('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukpelayananpenjamin_t as spp', 'spp.nostrukfk', '=', 'sp.norec')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.norec', 'sp.nosbmlastfk')
            ->leftjoin('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('ruangan_m as ru2', 'ru2.id', '=', 'sr.ruanganfk')
            ->leftJOIN('departemen_m as dp2', 'dp2.id', '=', 'ru2.objectdepartemenfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'prd.objectdetailjenisprodukfk')
            ->leftJOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->select(
                'pp.norec',
                'spp.nospp',
                'sbm.nosbm',
                'sp.totalprekanan_pasien',
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
            ->whereNotNull('sbm.nosbm')
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('pp.noregistrasi', $r['noregistrasi'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $sDokterOperasi = $this->settingFix('jenisPetugasDokterOperasi');
        $sDokterAnastesi = $this->settingFix('jenisPetugasDokterAnastesi');
        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->leftjoin('jenispetugaspelaksana_m as jp', 'jp.id', '=', 'ptu.objectjenispetugaspefk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'jp.jenispetugaspe')
            ->where('ptu.kdprofile', $this->kdProfile)
            ->where(function ($query) use ($sDokterPemeriksa, $sDokterOperasi, $sDokterAnastesi) {
                $query->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterOperasi)
                      ->orWhere('ptu.objectjenispetugaspefk', $sDokterAnastesi);
            })
            ->where('ptu.noregistrasi', $r['noregistrasi'])
            ->get();
        $res['total'] = 0;
        $res['totalprekanan_pasien'] = 0;
        foreach ($data as $item) {
            $item->dokter = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->dokteranastesi = $item->strukresepfk;
            $item->dokteroperasi = $item->strukresepfk;
            $res['total']  = (float) $res['total']  + (float) $item->total;
            $res['totalprekanan_pasien']  = (float) $item->totalprekanan_pasien;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokter = $itemd->namalengkap;
                    if ($itemd->jenispetugaspe == 'Dokter Operasi 1') {
                        $item->dokteroperasi = $itemd->namalengkap;
                    }
                    elseif ($itemd->jenispetugaspe == 'Dokter Anestesi') {
                        $item->dokteranastesi = $itemd->namalengkap;
                    }
                }
            }
        }
        $sudahDibayar = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalsudahdibayar');
        $totalBiaya = DB::table('strukpelayananpenjamin_t')
            ->where('norec', $r['norec'])
            ->value('totalbiaya');
        $res['total'] = round($res['total']);
        $res['billing'] =  $data->groupBy('namaruangan');
        $res['ismultipenjamin']  = false;
        $res['sudahBayar'] = round($sudahDibayar);
        $res['deposit'] = StrukBuktiPenerimaan::deposit($r['noregistrasi']);
        $res['dibayar'] = StrukBuktiPenerimaan::totalBayar($r['noregistrasi']);
        $res['klaim']  = StrukPelayanan::totalKlaim($r['noregistrasi']);
        $res['iurbayar'] = StrukBuktiPenerimaan::totalBayarIUR($r['noregistrasi']);
        $res['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($r['noregistrasi']);
        $res['sisa'] = $res['total'] - $res['deposit'] - $res['klaim'] - $res['dibayar'] + $res['pengembalian'] - $res['totalprekanan_pasien'];
        $res['totalklaimiur'] = $res['total'] - $res['iurbayar'];
        $res['pdf']  = $r['pdf'];
        $res['sisa'] = round($res['sisa']);
        $blade = 'report.kasir.billing-terverif';


        if (isset($r['rekap']) && $r['rekap'] == true) {
            $res['billing'] =  $data->groupBy('departemen_group');
            $blade = 'report.kasir.rekap-billing';
        }
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
                $blade.'-dom',
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

}
