<?php

namespace App\Http\Controllers\Laboratorium;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\HasilLaboratorium;
use App\Models\Transaksi\HasilPemeriksaanLab;
use App\Models\Transaksi\HasilPemeriksaanPcr;
use App\Models\Transaksi\HasilPemeriksaanMikro;
use App\Models\Transaksi\HasilPemeriksaanImunohistokimia;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi\HasilPemeriksaanPapSmear;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class LaboratoriumCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function dokterLab(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $res['dokter'] = Pegawai::mine()->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();
        return $this->respond($res);
    }

    public function LayananLab(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            // ->leftJOIN('order_bridge as lis', 'lis.order_number', '=', 'so.noorder')
            ->leftJOIN('order_lab as lis', function ($join) {
                $join->on('lis.no_lab', '=', 'so.noorder');
                $join->on(DB::raw('lis.kode_test::int'), '=', 'pp.produkfk');
            })
            ->select(
                // 'pp.norec as norec_pp',
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap',
                'apd.norec as norec_apd',
                'so.noorder',
                'so.keteranganlainnya',
                'pp.strukfk',
                'pp.produkfk',
                'ru.objectdepartemenfk',
                'lis.no_lab as idbridging',
                'so.namafile',
                'so.norec as norec_so',
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
            ->whereNull('pp.strukresepfk')
            ->where('pp.kdprofile', $kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('apd.noregistrasifk', $r['norec_pd']);
            // ->where(DB::raw('DATE(pp.tglpelayanan)'), $r['tglpelayanan']);
        // ->where('pp.tglpelayanan', $r['tglpelayanan']);
        // if (isset($r['tglpelayanan']) && $r['tglpelayanan'] != '' && $r['tglpelayanan'] != 'undefined') {
        //     $data = $data->where('pp.tglpelayanan', $r['tglpelayanan']);
        // } else {
        //     $data = $data->whereDate('pp.tglpelayanan', '=', now()->toDateString());
        // }
        if (isset($r['tglpelayanan']) && $r['tglpelayanan'] != '' && $r['tglpelayanan'] != 'undefined') {
            $data = $data->whereDate('pp.tglpelayanan', '=', Carbon::parse($r['tglpelayanan'])->toDateString());
        } else {
            $data = $data->whereDate('pp.tglpelayanan', '=', now()->toDateString());
        }

        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        // if (isset($r['norec_pp']) && $r['norec_pp'] != '' && $r['norec_pp'] == null) {
        //     $data = $data->where('pp.norec',$r['norec_pp']);
        // }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            // $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            // foreach ($pelayananpetugas as $itemd) {
            //     if ($itemd->pelayananpasien == $item->norec_pp) {
            //         $item->dokterpemeriksa = $itemd->namalengkap;
            //     }
            // }
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
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


        $result['length'] = count($data);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['list_ruangan'] = AntrianPasienDiperiksa::listRuangan($pd->noregistrasi);
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function getHasilLabManual(Request $r)
    {
        $norecpp = explode(',', $r['norec_pp']);
        $kdProfile = $this->kdProfile;
        $jenis = DB::table('pasien_m as ps')
            ->join('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.objectjeniskelaminfk',
            )
            ->where('ps.id', $r['nocmfk'])
            ->where('ps.statusenabled', true)
            ->first();
        if (!empty($jenis)) {

            $date = new \DateTime($jenis->tgllahir);
            $now = new \DateTime();
            $jenis->umur = $now->diff($date)->format("%a");
        }
        $data = DB::select(DB::raw("SELECT
        pp.noregistrasifk AS norec_apd,
        djp.detailjenisproduk,
        pp.produkfk,
        prd.namaproduk,
        pdl.detailpemeriksaan,
        hh.hasil,
        pdlm.rangemin AS nilaimin,
        pdlm.rangemax AS nilaimax,
        pdlm.refrange AS nilaitext,
        ss.satuanstandar,
        pdl.id AS iddetailproduk,
        hh.metode,
        pp.norec AS norec_pp,
        hh.metode,
        hh.norec AS norec_hasil,
        hh.keterangan,
        hh.flag,
        hh.catatan,
        pg.id as iddokter,
        pg.namalengkap as namadokter
    FROM
        pelayananpasien_t AS pp
        INNER JOIN produk_m AS prd ON prd.id = pp.produkfk
        INNER JOIN detailjenisproduk_m AS djp ON djp.id = prd.objectdetailjenisprodukfk
        LEFT JOIN produkdetaillaboratorium_m AS pdl ON pdl.produkfk = prd.id AND pdl.statusenabled = TRUE
        LEFT JOIN produkdetaillaboratoriumnilainormal_m AS pdlm ON pdlm.produkdetaillabfk = pdl.id
        AND pdlm.jeniskelaminfk = '$jenis->objectjeniskelaminfk'
        AND '$jenis->umur' between pdlm.ageminday and pdlm.agemaxday
        left JOIN hasillaboratorium_t AS hh ON hh.norecpelayanan = pp.norec
        AND hh.statusenabled = TRUE
        AND pp.noregistrasifk = hh.noregistrasifk
        AND pp.norec = hh.norecpelayanan
        AND hh.produkdetaillabfk = pdl.id
        LEFT JOIN satuanstandar_m AS ss ON ss.id = pdl.satuanstandarfk
        LEFT JOIN pegawai_m AS pg ON pg.id = hh.pegawaifk::integer
    WHERE
         pp.noregistrasifk = '$r[norec_apd]'
        AND pp.kdprofile = $kdProfile
        AND pdl.statusenabled = TRUE
    ORDER BY
        djp.qdetailjenisproduk,
        pdl.nourut"));

        $hasil = [];
        foreach ($data as $datas) {
            if ($r['norec_pp'] == '') {
                $hasil[] = [
                    'namaproduk' => $datas->namaproduk,
                    'detailpemeriksaan' => $datas->detailpemeriksaan,
                    'hasil' => $datas->hasil,
                    'flag' => $datas->flag,
                    'noregistrasifk' => $datas->norec_apd,
                    'produkdetaillabfk' => $datas->iddetailproduk,
                    'produkfk' => $datas->produkfk,
                    'nilaitext' => $datas->nilaitext,
                    'satuanstandar' => $datas->satuanstandar,
                    'norec_pp' => $datas->norec_pp,
                    'nilaimin' => $datas->nilaimin,
                    'nilaimax' => $datas->nilaimax,
                    'metode' => $datas->metode,
                    'catatan' => $datas->catatan,
                    'iddokter' => $datas->iddokter,
                    'namadokter' => $datas->namadokter,
                ];
            } else {
                foreach ($norecpp as $norec_pp) {
                    if (trim($datas->norec_pp) == trim($norec_pp)) {
                        $hasil[] = [
                            'namaproduk' => $datas->namaproduk,
                            'detailpemeriksaan' => $datas->detailpemeriksaan,
                            'hasil' => $datas->hasil,
                            'flag' => $datas->flag,
                            'noregistrasifk' => $datas->norec_apd,
                            'produkdetaillabfk' => $datas->iddetailproduk,
                            'produkfk' => $datas->produkfk,
                            'nilaitext' => $datas->nilaitext,
                            'satuanstandar' => $datas->satuanstandar,
                            'norec_pp' => $norec_pp,
                            'nilaimin' => $datas->nilaimin,
                            'nilaimax' => $datas->nilaimax,
                            'metode' => $datas->metode,
                            'catatan' => $datas->catatan,
                            'iddokter' => $datas->iddokter,
                            'namadokter' => $datas->namadokter,
                        ];
                    }
                }
            }
        }
        $res['data'] = $hasil;
        return $this->respond($res);
    }

    public function getHasilLabBridging(Request $r)
    {
        // hasil bridging
        $noorder = explode(',', $r['noorder']);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));
        $dataBrid = DB::select(DB::raw("SELECT hl.nama_pemeriksaan as namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,
        hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.user_validasi, hl.tgl_hasil, hl.metode
        FROM lab_hasil hl
        INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        --INNER JOIN produk_m AS prd ON prd.id = hl.kode_pemeriksaan::int
        WHERE so.noorder IN ($placeholders)"), $noorder);

        $hasilbrid = [];
        foreach ($dataBrid as $item) {
            $hasilbrid[] = [
                'namaproduk' => $item->namaproduk,
                'detailpemeriksaan' => $item->detailpemeriksaan,
                'hasil' => $item->hasil,
                'flag' => $item->flag,
                'nilaitext' => $item->nilaitext,
                'satuanstandar' => $item->satuanstandar,
                'analis' => $item->user_validasi,
                'tglhasil' => $item->tgl_hasil,
                'metode' => $item->metode,
            ];
        }

        $res['data'] = $hasilbrid;
        return $this->respond($res);
    }
    public function saveHasilLabManual(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            foreach ($request['hasil'] as  $value) {
                $h =  HasilLaboratorium::where([
                    'noregistrasifk' => $value['noregistrasifk'],
                    'norecpelayanan' => $value['norec_pp'],
                    'produkfk' => $value['produkfk'],
                    'detailpemeriksaan' => $value['detailpemeriksaan'],
                ])->first();

                if (!empty($h)) {
                    HasilLaboratorium::where([
                        'noregistrasifk' => $value['noregistrasifk'],
                        'norecpelayanan' => $value['norec_pp'],
                        'produkfk' => $value['produkfk'],
                        'detailpemeriksaan' => $value['detailpemeriksaan'],
                    ])->update(
                        [
                            'hasil' => $value['hasil'],
                            'flag' => $value['flag'],
                            'satuan' => $value['satuanstandar'],
                            'nilainormal' => $value['nilaitext'],
                            'group' => $value['namaproduk'],
                            'metode' => isset($value['metode']) ? $value['metode'] : null,
                            'nilaimin' => $value['nilaimin'],
                            'nilaimax' => $value['nilaimax'],
                            'pegawaifk' => $request['pegawaifk'],
                            'catatan' => $request['catatan'],
                        ]
                    );
                } else {
                    $h = new HasilLaboratorium();
                    $h->norec = $h->generateNewId();
                    $h->kdprofile = $kdProfile;
                    $h->statusenabled = true;
                    $h->tglhasil = date('Y-m-d H:i:s');
                    $h->pegawaifk = $value['pegawaifk'];
                    $h->hasil = $value['hasil'];
                    $h->noregistrasifk = $value['noregistrasifk'];
                    $h->produkfk = $value['produkfk'];
                    $h->flag = $value['flag'];
                    $h->produkdetaillabfk = $value['produkdetaillabfk'];
                    $h->detailpemeriksaan = $value['detailpemeriksaan'];
                    $h->norecpelayanan = $value['norec_pp'];
                    $h->satuan = $value['satuanstandar'];
                    $h->nilainormal = $value['nilaitext'];
                    $h->group = $value['namaproduk'];
                    $h->metode = isset($value['metode']) ? $value['metode'] : null;
                    $h->nilaimin = $value['nilaimin'];
                    $h->nilaimax = $value['nilaimax'];
                    $h->pegawaifk = $request['pegawaifk'];
                    $h->catatan = $request['catatan'];
                    $h->save();
                }
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => $h,
                "message" => "Simpan Hasil Laboratorium Sukses"
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Hemooh",
                "result"  => $e->getMessage() . ' ' . $e->getLine()

            );
        }

        return $this->respond($result, $result['status'], $result['message']);
    }

    public function getHasilPemeriksaanLab(Request $request)
    {
        $data = DB::table('hasilpemeriksaanlab_t as ar')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ar.pegawaifk')
            ->select('ar.*', 'pg.namalengkap')
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->where('ar.pelayananpasienfk', $request['norec_pp'])
            ->orderBy('tanggal', 'DESC')
            ->first();

        return $this->respond($data);
    }
    public function getHasilPemeriksaanLabPapSmear(Request $request)
    {
        $data = DB::table('hasillabpapsmear_t as ar')
            ->select('ar.*')
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->where('ar.pelayananpasienfk', $request['norec_pp'])
            ->first();

        return $this->respond($data);
    }
    public function getHasilPemeriksaanImunohistokimia(Request $request)
    {
        $data = DB::table('hasilpemeriksaanimunohistokimia_t as ar')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ar.pegawaifk')
            ->select('ar.*', 'pg.namalengkap')
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->where('ar.pelayananpasienfk', $request['norec_pp'])
            ->orderBy('tanggalditerima', 'DESC')
            ->first();

        return $this->respond($data);
    }

    // public function saveHasilLabPA(Request $request)
    // {
    //     $kdProfile = $this->kdProfile;
    //     DB::beginTransaction();
    //     try {
    //         if ($request['norec'] == "") {
    //             $dataSO = new HasilPemeriksaanLab();
    //             $dataSO->norec = $dataSO->generateNewId();
    //             $dataSO->kdprofile = $kdProfile;
    //             $dataSO->statusenabled = true;
    //         } else {
    //             $dataSO =  HasilPemeriksaanLab::where('norec', $request['norec'])->first();
    //         }
    //         $dataSO->nomorpa = $request['nomorpa'];
    //         $dataSO->tanggal = date('Y-m-d H:i:s');
    //         $dataSO->pegawaifk = $request['pegawaifk'];
    //         $dataSO->dokterpengirimfk = $request['dokterpengirimfk'];
    //         $dataSO->jenis = $request['jenis'];
    //         $dataSO->pelayananpasienfk = $request['pelayananpasienfk'];
    //         $dataSO->noregistrasifk = $request['noregistrasifk'];
    //         $dataSO->diagnosaklinik = $request['diagnosaklinik'];
    //         $dataSO->keteranganklinik = $request['keteranganklinik'];
    //         $dataSO->diagnosapb = $request['diagnosapb'];
    //         $dataSO->keteranganpb = $request['keteranganpb'];
    //         $dataSO->topografi = $request['topografi'];
    //         $dataSO->morfologi = $request['morfologi'];
    //         $dataSO->makroskopik = $request['makroskopik'];
    //         $dataSO->mikroskopik = $request['mikroskopik'];
    //         $dataSO->kesimpulan = $request['kesimpulan'];
    //         $dataSO->anjuran = $request['anjuran'];
    //         $dataSO->jaringanasal = $request['jaringanasal'];
    //         // return $dataSO;
    //         $dataSO->save();

    //         //
    //         $transStatus = 'true';
    //     } catch (\Exception $e) {
    //         $transStatus = 'false';
    //     }

    //     if ($transStatus == 'true') {
    //         $transMessage = "Sukses";
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "data"  => $dataSO,
    //                 "as" => '@epic',
    //             ),
    //         );
    //     } else {
    //         $transMessage = "Simpan Gagal";
    //         DB::rollBack();
    //         $result = array(
    //             "status" => 400,
    //             "result"  => $e->getMessage() . ' ' . $e->getLine()

    //         );
    //     }
    //     return $this->respond($result['result'], $result['status'], $transMessage);
    // }

    public function saveHasilLabPA(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            // Jika norec ada, update data, jika tidak buat baru
            if (empty($request['norec'])) {
                $dataSO = new HasilPemeriksaanLab();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdProfile;
                $dataSO->statusenabled = true;
            } else {
                // Update data jika norec sudah ada
                $dataSO =  HasilPemeriksaanLab::where('norec', $request['norec'])->first();
            }

            // Set semua data yang akan disimpan/diupdate
            $dataSO->nomorpa = $request['nomorpa'];
            $dataSO->tanggal = $request['tanggal'];
            $dataSO->pegawaifk = $request['pegawaifk'];
            $dataSO->dokterpengirimfk = $request['dokterpengirimfk'];
            $dataSO->jenis = $request['jenis'];
            $dataSO->pelayananpasienfk = $request['pelayananpasienfk'];
            $dataSO->noregistrasifk = $request['noregistrasifk'];
            $dataSO->diagnosaklinik = $request['diagnosaklinik'];
            $dataSO->keteranganklinik = $request['keteranganklinik'];
            $dataSO->diagnosapb = $request['diagnosapb'];
            $dataSO->keteranganpb = $request['keteranganpb'];
            $dataSO->topografi = $request['topografi'];
            $dataSO->morfologi = $request['morfologi'];
            $dataSO->makroskopik = $request['makroskopik'];
            $dataSO->mikroskopik = $request['mikroskopik'];
            $dataSO->kesimpulan = $request['kesimpulan'];
            $dataSO->anjuran = $request['anjuran'];
            $dataSO->jaringanasal = $request['jaringanasal'];

            // Save atau update data ke database
            $dataSO->save();

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataSO,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status']);
    }

    public function saveHasilLabImunohistokimia(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            // Jika norec ada, update data, jika tidak buat baru
            if (empty($request['norec'])) {
                $dataSO = new HasilPemeriksaanImunohistokimia();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdProfile;
                $dataSO->statusenabled = true;
            } else {
                // Update data jika norec sudah ada
                $dataSO =  HasilPemeriksaanImunohistokimia::where('norec', $request['norec'])->first();
            }

            // Set semua data yang akan disimpan/diupdate
            $dataSO->nomorih = $request['nomorih'];
            $dataSO->tanggalditerima = $request['tanggalditerima'];
            $dataSO->tanggaldijawab = $request['tanggaldijawab'];
            $dataSO->pegawaifk = $request['pegawaifk'];
            $dataSO->dokterpengirimfk = $request['dokterpengirimfk'];
            $dataSO->pelayananpasienfk = $request['pelayananpasienfk'];
            $dataSO->noregistrasifk = $request['noregistrasifk'];
            $dataSO->lca = $request['lca'];
            $dataSO->ck = $request['ck'];
            $dataSO->er = $request['er'];
            $dataSO->pr = $request['pr'];
            $dataSO->herneu = $request['herneu'];
            $dataSO->ki67 = $request['ki67'];
            $dataSO->kesimpulan = $request['kesimpulan'];

            // Save atau update data ke database
            $dataSO->save();

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataSO,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status']);
    }

    public function hapusTindakanLab(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {
                PelayananPasienDetail::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienPetugas::where('pelayananpasien', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasien::where('norec', $item['norec_pp'])->where('kdprofile', $this->kdProfile)->delete();
                StrukOrder::where('noorder', $item['noorder'])
                    ->where('kdprofile', $this->kdProfile)
                    ->update(
                        [
                            'statusorder' => 0,
                        ]
                    );

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
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function detailPetugasLab(Request $r)
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

    public function savePetugasLab(Request $r)
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

            $pg = Pegawai::where('id', $r['objectpegawaifk'])->first();
            $ps = PasienDaftar::detailPasien($r['noregistrasi']);

            $this->LOGGING(
                $log . 'Petugas Tindakan',
                $new_PPP->norec,
                'pelayananpasienpetugas_t',
                $log . 'Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' . $r['namaruangan'] . ' pada Pasien ' .
                    $r['noregistrasi']
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
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deletePetugasLab(Request $r)
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
                'Hapus Petugas Tindakan ' . $pg->namalengkap . ' pelayanan ' . $r['namaproduk'] . ' di ' .  ' pada Pasien ' .
                    $r['noregistrasi']
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
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function LayananLabPerTindakan(Request $request)
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
        if ($request['so_norec']) {
            $paramsPp = "AND tp.strukorderfk = '" . $request['so_norec'] . "'";
        }
        $profile = $this->profile();

        $ruangan = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            ->select('ru.namaruangan')
            ->where('pd.noregistrasi', '=', $noregistrasi)
            ->get();

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        $data->ruanganasal = $ruangan[0]->namaruangan;
        // return $data;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total, x.namaruangan
            FROM ( SELECT tp.tglpelayanan, ru.namaruangan, (select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
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
            'judul' => "BUKTI LAYANAN LABORATORIUM",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.laboratorium.bukti-layanan-lab',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
        SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien, ps.noidentitas, ps.nohp,ps.tempatlahir as tempatlahir , ng.namanegara,
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
        LEFT JOIN negara_m as ng on ng.id = ps.objectnegarafk
        WHERE pd.kdprofile = $kdProfile AND pd.statusenabled = true AND pd.noregistrasi = '$noregistrasi'
"))->first();
        return $data;
    }


    public function cetakHasilLab(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $KodeJasaMedis      = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab    = $this->settingFix('KdKomponenTarifJasaDokter');

        // start generate parameter kebutuhan save dokumen
        if (isset($request['noregistrasi'])) {
            $registrasi = DB::table("pasiendaftar_t as pd")
                ->select("apd.norec")
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->join('hasillaboratorium_t as hh', 'hh.noregistrasifk', '=', 'apd.norec')
                ->where('pd.statusenabled', true)
                ->where('pd.kdprofile', $kdProfile)
                ->first();

            if (empty($registrasi)) {
                $request['norec_apd'] = "";
                $request['norec'] = "";
            } else {
                $request['norec_apd'] = $registrasi->norec;
                $request['norec'] = "";
            }
        }

        // end generate parameter kebutuhan save dokumen
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
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
        $profile    = $this->profile();
        $data       = $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        if (isset($request['noorder'])) {
            $noorder = explode(',', $request['noorder']);
            $placeholders = implode(',', array_fill(0, count($noorder), '?'));
            $dataBrid = DB::select(DB::raw("SELECT DISTINCT hl.nama_pemeriksaan as  namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,so.tglorder,so.catatanklinis,
            hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.tgl_daftar, hl.no_lab, hl.user_validasi, hl.tgl_hasil, hl.metode,so.noorder, ol.dok_jaga, hl.no_urut,
            ru.namaruangan as ruanganasal, pg.namalengkap as dok_order
            FROM lab_hasil hl
            INNER JOIN strukorder_t so ON so.noorder = hl.no_order
            -- INNER JOIN produk_m AS prd ON cast(prd.id as text) = hl.kode_sir
            INNER JOIN order_lab ol ON ol.no_lab = so.noorder
            left JOIN ruangan_m AS ru ON ru.id = so.objectruanganfk
            left JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
            WHERE hl.no_order IN ($placeholders)
            ORDER BY hl.no_urut"), $noorder);
        } else {
            $dataBrid = DB::select(DB::raw("SELECT DISTINCT hl.nama_pemeriksaan as  namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,so.tglorder,so.catatanklinis,
            hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.tgl_daftar, hl.no_lab, hl.user_validasi, hl.tgl_hasil, hl.metode,so.noorder, ol.dok_jaga, hl.no_urut,
            ru.namaruangan as ruanganasal, pg.namalengkap as dok_order
            FROM lab_hasil hl
            INNER JOIN strukorder_t so ON so.noorder = hl.no_order
            -- INNER JOIN produk_m AS prd ON cast(prd.id as text) = hl.kode_sir
            INNER JOIN order_lab ol ON ol.no_lab = so.noorder
            left JOIN ruangan_m AS ru ON ru.id = so.objectruanganfk
            left JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
            WHERE hl.no_registrasi = :noreg
            ORDER BY so.noorder,hl.no_urut
            "), [':noreg' => $noregistrasi]);
        }
        if(!$dataBrid){
            return null;
        }

        // return $dataBrid;

        $groupedData = [];

        foreach ($dataBrid as $item) {
            // Menyusun data ke dalam array berdasarkan nilai noorder
            $noorder = $item->noorder;
            if (!isset($groupedData[$noorder])) {
                $groupedData[$noorder] = [];
            }
            $groupedData[$noorder][] = $item;
        }

        // return $groupedData;
        $hasilbrid = [];
        foreach ($dataBrid as $item) {
            $hasilbrid[] = [
                'namaproduk' => $item->namaproduk,
                'detailpemeriksaan' => $item->detailpemeriksaan,
                'hasil' => $item->hasil,
                'tglOrder' => $item->tglorder,
                'flag' => $item->flag,
                'nilaitext' => $item->nilaitext,
                'satuanstandar' => $item->satuanstandar,
                'analis' => $item->user_validasi,
                'tglhasil' => $item->tgl_hasil,
                'noorder' => $item->noorder,
                'ruanganasal' => $item->ruanganasal,
                'metode' => $item->metode,
                'dokter' => $item->dok_jaga,
            ];
            // $data->dokter = $item->dok_jaga;
        };
        foreach ($dataBrid as $item) {
            // Menyusun data ke dalam array berdasarkan nilai noorder
            $item->ttdePemeriksa = base64_encode(QrCode::format('svg')->size(75)->generate($item->noorder . "\n" . $item->tglorder . "\n" . $item->user_validasi));
            $item->ttdeDokter = base64_encode(QrCode::format('svg')->size(75)->generate($item->noorder . "\n" . $item->tglorder . "\n" . $item->dok_jaga));
            // $noorder = $item['noorder'];
            // if (!isset($groupedData[$noorder])) {
            //     $groupedData[$noorder] = [];
            // }
            // $groupedData[$noorder][] = $item;
        }

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' =>   json_decode(json_encode($groupedData), FALSE),
        );
        $res['pdf']  = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab';
        if (isset($request['storage']) && $request['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                "report.laboratorium.hasil-lab",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.laboratorium.hasil-lab',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }


    public function listPasienLab(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'pd.norec as norec_pd',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pd.nostruklastfk',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'ps.nobpjs',
                'jk.jeniskelamin',
                // 'apd.norec as norec_apd',
                'ps.noidentitas',
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            // ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')))
            // ->whereBetween(DB::raw("pd.tglregistrasi::date"),$rangeDate)
            ->where('pd.statusenabled', true);
        // ->where('apd.statusenabled', true);


        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }


        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function saveTransaksi(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $idKelasRadLab = (int) $this->settingFix('kdKelasLabRad');

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewAPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;

            PasienDaftar::where('norec', $r_NewPD['norec_pd'])
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'objectkelasfk' => $idKelasRadLab
                    ]
                );

            // $pd = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            // $dataAPD->objectasalrujukanfk = $r_NewPD['asalrujukanfk'];
            $dataAPD->objectkelasfk =  $r_NewPD['objectkelasfk'];
            $dataAPD->noantrian = $noAntrian;
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            // $dataAPD->objectpegawaifk = $r_NewPD['dokterfk'];
            $dataAPD->objectruanganfk = $r_NewAPD['objectruangantujuanfk'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            // $dataAPD->objectruanganasalfk = $r_NewPD['objectruanganasalfk'];;
            $dataAPD->tglregistrasi = $r_NewPD['tglregistrasi'];
            // $dataAPD->tglregistrasi = $r_NewPD->tglregistrasi; //date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->save();

            $this->LOGGING(
                'Registrasi Transaksi Pelayanan',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                ' pada Pasien ' .
                    $r_NewPD['norec_pd'] . 'ke' . $r_NewAPD['objectruangantujuanfk']
            );



            DB::commit();
            $result = array(
                "status" => 200,
                "message" => 'Berhasil',
                "result" => $dataAPD,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            ];
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function getExpertise(Request $r)
    {
    }

    public function cetakEkspertiseEcho(Request $r)
    {
        $data = DB::table('hasilpemeriksaanlab_t as ar')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'ar.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'ar.dokterpengirimfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'ar.pegawaifk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as kl', 'kl.id', 'pd.objectkelasfk')
            ->where('ar.pelayananpasienfk', $r->pelayananpasienfk)
            ->select(
                'ar.noregistrasifk as hasilpemerikasaan',
                'pp.noregistrasifk as pelayanan_pasien',
                'apd.noregistrasifk as antrianpasiendiperiksa',
                'pp.norec as pasiendaftar',
                'pd.noregistrasi',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'ps.tgllahir',
                'alm.alamatlengkap as alamatlengkap',
                'kp.kelompokpasien',
                'ar.tanggal as tanggalpemeriksaan',
                'pp.tglpelayanan as tanggalpelayanan',
                'pg1.namalengkap  as penanggungjawab',
                'pg2.nosip',
                'pg2.namalengkap  as dokterpengirim',
                'ar.diagnosaklinik as diagnosa',
                'ar.diagnosaklinik',
                'ar.morfologi',
                'ar.topografi',
                'ar.diagnosapb',
                'ar.keteranganpb',
                'ar.jenis',
                'ar.makroskopik',
                'ar.mikroskopik',
                'ar.kesimpulan',
                'ar.anjuran',
                'kl.namakelas',
                'ar.nomorpa'
            )
            ->orderBy('ar.tanggal', 'DESC')
            ->first();

        $blade      = "report.laboratorium.expertise";
        $pageWidth  = 950;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.laboratorium.expertise',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data

                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view($blade, compact('pageWidth', 'data', 'res'));
        }
    }
    public function cetakHasilLabPapSmear(Request $request)
{
    $data = DB::table('hasillabpapsmear_t as hs')
        ->where('hs.norec', $request['norec'])
        ->join('pelayananpasien_t as pp', 'pp.norec', 'hs.pelayananpasienfk')
        ->leftJoin('produk_m as p', 'p.id', 'pp.produkfk')
        ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', 'pp.noregistrasifk')
        ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
        ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
        ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
        ->leftJoin('ruangan_m as rm', 'rm.id', 'so.objectruanganfk')
        ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
        ->leftJoin('pegawai_m as pg', 'pg.id', 'ppp.objectpegawaifk')
        ->leftJoin('pegawai_m as pgo', 'pgo.id', 'so.objectpegawaiorderfk')
        ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
        ->select(
            'ps.namapasien',
            'pd.noregistrasi',
            'pd.tglregistrasi',
            'ps.tgllahir',
            'ps.paspor',
            'pg.namalengkap',
            'pg.alamat',
            'ps.noidentitas',
            'hs.tglTerima',
            'hs.tglJawab',
            'pgo.namalengkap as dokter_order',
            'alm.alamatlengkap',
            'hs.nopemerpapsmear',
            'hs.kategoriumumnegatifless',
            'hs.kategoriumumabnormal',
            'hs.kategoriumumneoplasma',
            'hs.kategoriumumlain',
            'hs.adekuasiPemeriksaanMemuaskan',
            'hs.adekuasiPemeriksaanTidakMemuaskan',
            'hs.ketedukasi',
            'hs.infeksiorganismetrikomonas',
            'hs.infeksiorganismekandida',
            'hs.infeksiorganismekokobasili',
            'hs.infeksiorganismeaktinomises',
            'hs.infeksiorganismevirusherpes',
            'hs.selskuamosaaktipik',
            'hs.selskuamosaaktipikacsus',
            'hs.selskuamosaaktipikacsh',
            'hs.selskuamosalesiintraepitelial',
            'hs.selskuamosalesiintraepitelialhpv',
            'hs.selskuamosalesiintraepitelialcin1',
            'hs.selskuamosalesiintraepitelialcin1infeksi',
            'hs.selskuamosalesiintraepitelialhsil',
            'hs.selskuamosalesiintraepitelialhsilcin2',
            'hs.selskuamosalesiintraepitelialhsilcin3',
            'hs.selSkuamosaLesiIntraePitelialHsilCis',
            'hs.selskuamosalesiintraepitelialhsilinvasi',
            'hs.selglandularAntipik',
            'hs.selglandularAntipikEndoserviks',
            'hs.selglandularAntipikEndometrium',
            'hs.selglandularAntipikGlandular',
            'hs.selGlandularAntipikFavor',
            'hs.selGlandularAntipikFavorEndoserviks',
            'hs.selGlandularAntipikFavorGlandular',
            'hs.selGlandularAdenokarsinomaIsSitu',
            'hs.selGlandularAdenokarsinoma',
            'hs.selGlandularAdenokarsinomaEndoserviks',
            'hs.selGlandularAdenokarsinomaExtra',
            'hs.selGlandularAdenokarsinomaEndometrium',
            'hs.selGlandularAdenokarsinomaTidakDapat',
            'hs.neoplasmaGanas',
            'hs.nonneoplastikperubahanreaktif',
            'hs.nonNeoPlastikPerubahanReaktifIud',
            'hs.nonNeoPlastikPerubahanReaktifPeradangan',
            'hs.nonNeoPlastikPerubahanReaktifRadiasi',
            'hs.nonneoplastikselepitel',
            'hs.nonneoplastikatrofi',
            'hs.selSkuamosaKarsinomaSel',
            'so.noorder',
            'hs.kesimpulan',
            'hs.saran',
            'rm.namaruangan',
        )
        ->first();

    if ($data) {
        $data->umur = $this->getAgeYear($data->tgllahir, $data->tglregistrasi) . ' thn';

        $blade = "report.laboratorium.hasil-papsmear";
        $pageWidth = 950;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.laboratorium.hasil-papsmear',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data
                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view($blade, compact('pageWidth', 'data', 'res'));
        }
    } else {
         return abort(404);
    }
}

    public function hasilLab(Request $request)
    {

        $dataHasil = DB::table('lab_hasil as hl')
            ->join('strukorder_t as so', 'so.noorder', 'hl.no_order')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->select(DB::raw("TO_CHAR(hl.tgl_hasil, 'DD/MM/YYYY HH24:MI:SS') ||'  '|| hl.nama_pemeriksaan ||'  '|| hl.hasil ||'  '|| 'Normal : ' || hl.normal || '\n' as hasilLab"))
            // ->select('hl.no_order','hl.nama_pemeriksaan','hl.normal','hl.metode','hl.hasil','hl.unit','hl.user_validasi','hl.tgl_hasil','hl.no_registrasi')
            ->where('hl.no_registrasi', $request['noregistrasi'])
            ->where('hl.hasil', '!=', '');
        if (isset($request['noorder']) && $request['noorder'] != '') {
            $dataHasil = $dataHasil->whereIn('hl.no_order', explode(',', $request['noorder']));
        }
        if (isset($request['nourut']) && $request['nourut'] != '') {
            $dataHasil = $dataHasil->whereIn('hl.no_urut', explode(',', $request['nourut']));
        }
        $dataHasil = $dataHasil->orderBy('hl.tgl_hasil')->get();

        return $this->respond($dataHasil);
    }

    public function sourceHasilLab(Request $request)
    {
        $dataHasil = DB::table('lab_hasil as hl')
            ->join('strukorder_t as so', 'so.noorder', 'hl.no_order')
            // ->leftJoin(DB::raw("produk_m AS prd ON cast(prd.id as text) = hl.kode_sir"))
            ->leftJoin('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
            ->select('hl.nama_pemeriksaan', 'hl.tgl_hasil', 'hl.normal', 'hl.no_order', 'hl.no_urut', 'hl.hasil')
            ->where('hl.no_registrasi', $request['noregistrasi'])
            ->where('hl.hasil', '!=', '')
            ->get();

        return $this->respond($dataHasil);
    }
    public function saveHasilLabPCR(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == "") {
                $hasilPcr = new HasilPemeriksaanPcr();
                $hasilPcr->norec = $hasilPcr->generateNewId();
                $hasilPcr->kdprofile = $kdProfile;
                $hasilPcr->statusenabled = true;
            } else {
                $hasilPcr = HasilPemeriksaanPcr::where('pelayananpasienfk', $request['pelayananpasienfk'])->orWhere('norec', $request['norec'])->first();
            }
            $hasilPcr->nopemerikasaan = $request['nopemerikasaan'];
            $hasilPcr->objectruanganfk = $request['objectruanganfk'];
            $hasilPcr->jenisspesimenfk = $request['jenisspesimenfk'];
            $hasilPcr->metodeperiksafk = $request['metodeperiksafk'];
            $hasilPcr->tglterimasampel = $request['tglterimasampel'];
            $hasilPcr->tglselesaisampel = $request['tglselesaisampel'];
            $hasilPcr->kodesampel = $request['kodesampel'];
            $hasilPcr->sample = $request['sample'];
            $hasilPcr->nilaiRujukan = $request['nilaiRujukan'];
            $hasilPcr->hasil = $request['hasil'];
            $hasilPcr->keterangan = $request['keterangan'];
            $hasilPcr->keperluaan = $request['keperluaan'];
            $hasilPcr->keadaan = $request['keadaan'];
            $hasilPcr->keluarkota = $request['keluarkota'];
            $hasilPcr->kotanegara = $request['kotanegara'];
            $hasilPcr->petugasfk = $request['petugasfk'];
            $hasilPcr->petugasverifikatorfk = $request['petugasverifikatorfk'];
            $hasilPcr->petuggasapprovalfk = $request['petuggasapprovalfk'];
            $hasilPcr->pelayananpasienfk = $request['pelayananpasienfk'];
            $hasilPcr->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $hasilPcr,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function saveHasilLabPapSmear(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == "") {
                $hasilPapSmear = new HasilPemeriksaanPapSmear();
                $hasilPapSmear->norec = $hasilPapSmear->generateNewId();
                $hasilPapSmear->kdprofile = $kdProfile;
                $hasilPapSmear->statusenabled = true;
            } else {
                $hasilPapSmear = HasilPemeriksaanPapSmear::where('pelayananpasienfk', $request['pelayananpasienfk'])->orWhere('norec', $request['norec'])->first();
            }
            $hasilPapSmear->nopemerpapsmear = $request['nopemerpapsmear'];
            $hasilPapSmear->pelayananpasienfk = $request['pelayananpasienfk'];
            $hasilPapSmear->tglTerima = $request['tglTerima'];
            $hasilPapSmear->tglJawab = $request['tglJawab'];
            $hasilPapSmear->selSkuamosaKarsinomaSel = $request['selSkuamosaKarsinomaSel'];
            $hasilPapSmear->kategoriumumnegatifless = $request['kategoriumumnegatifless'];
            $hasilPapSmear->kategoriumumabnormal = $request['kategoriumumabnormal'];
            $hasilPapSmear->kategoriumumneoplasma = $request['kategoriumumneoplasma'];
            $hasilPapSmear->kategoriumumlain = $request['kategoriumumlain'];
            $hasilPapSmear->edukasipemeriksaan = $request['edukasipemeriksaan'];
            $hasilPapSmear->ketedukasi = $request['ketedukasi'];
            $hasilPapSmear->infeksiorganismetrikomonas = $request['infeksiorganismetrikomonas'];
            $hasilPapSmear->infeksiorganismekandida = $request['infeksiorganismekandida'];
            $hasilPapSmear->infeksiorganismekokobasili = $request['infeksiorganismekokobasili'];
            $hasilPapSmear->infeksiorganismeaktinomises = $request['infeksiorganismeaktinomises'];
            $hasilPapSmear->infeksiorganismevirusherpes = $request['infeksiorganismevirusherpes'];
            $hasilPapSmear->nonneoplastikperubahanreaktif = $request['nonneoplastikperubahanreaktif'];
            $hasilPapSmear->nonneoplastikselepitel = $request['nonneoplastikselepitel'];
            $hasilPapSmear->selskuamosalesiintraepitelial = $request['selskuamosalesiintraepitelial'];
            $hasilPapSmear->selSkuamosaLesiIntraePitelialHsilCis = $request['selSkuamosaLesiIntraePitelialHsilCis'];
            $hasilPapSmear->nonneoplastikatrofi = $request['nonneoplastikatrofi'];
            // $hasilPapSmear->optionnonneoplastik = $request['optionnonneoplastik'];
            $hasilPapSmear->selskuamosaaktipik = $request['selskuamosaaktipik'];
            $hasilPapSmear->selskuamosaaktipikacsus = $request['selskuamosaaktipikacsus'];
            $hasilPapSmear->selskuamosaaktipikacsh = $request['selskuamosaaktipikacsh'];
            $hasilPapSmear->selskuamosalesiintraepitelialhpv = $request['selskuamosalesiintraepitelialhpv'];
            $hasilPapSmear->selskuamosalesiintraepitelialcin1 = $request['selskuamosalesiintraepitelialcin1'];
            $hasilPapSmear->selskuamosalesiintraepitelialcin1infeksi = $request['selskuamosalesiintraepitelialcin1infeksi'];
            $hasilPapSmear->selskuamosalesiintraepitelialhsil = $request['selskuamosalesiintraepitelialhsil'];
            $hasilPapSmear->selskuamosalesiintraepitelialhsilcin2 = $request['selskuamosalesiintraepitelialhsilcin2'];
            $hasilPapSmear->selskuamosalesiintraepitelialhsilcin3 = $request['selskuamosalesiintraepitelialhsilcin3'];
            $hasilPapSmear->selskuamosalesiintraepitelialhsilinvasi = $request['selskuamosalesiintraepitelialhsilinvasi'];
            $hasilPapSmear->selglandularAntipik = $request['selglandularAntipik'];
            $hasilPapSmear->selglandularAntipikEndoserviks = $request['selglandularAntipikEndoserviks'];
            $hasilPapSmear->selglandularAntipikEndometrium = $request['selglandularAntipikEndometrium'];
            $hasilPapSmear->selglandularAntipikGlandular = $request['selglandularAntipikGlandular'];
            $hasilPapSmear->selGlandularAntipikFavor = $request['selGlandularAntipikFavor'];
            $hasilPapSmear->selGlandularAntipikFavorEndoserviks = $request['selGlandularAntipikFavorEndoserviks'];
            $hasilPapSmear->selGlandularAntipikFavorGlandular = $request['selGlandularAntipikFavorGlandular'];
            $hasilPapSmear->selGlandularAdenokarsinomaIsSitu = $request['selGlandularAdenokarsinomaIsSitu'];
            $hasilPapSmear->selGlandularAdenokarsinoma = $request['selGlandularAdenokarsinoma'];
            $hasilPapSmear->selGlandularAdenokarsinomaEndoserviks = $request['selGlandularAdenokarsinomaEndoserviks'];
            $hasilPapSmear->selGlandularAdenokarsinomaEndometrium = $request['selGlandularAdenokarsinomaEndometrium'];
            $hasilPapSmear->selGlandularAdenokarsinomaExtra = $request['selGlandularAdenokarsinomaExtra'];
            $hasilPapSmear->selGlandularAdenokarsinomaTidakDapat = $request['selGlandularAdenokarsinomaTidakDapat'];
            $hasilPapSmear->neoplasmaGanas = $request['neoplasmaGanas'];
            $hasilPapSmear->kesimpulan = $request['kesimpulan'];
            $hasilPapSmear->saran = $request['saran'];
            $hasilPapSmear->adekuasiPemeriksaanMemuaskan = $request['adekuasiPemeriksaanMemuaskan'];
            $hasilPapSmear->adekuasiPemeriksaanTidakMemuaskan = $request['adekuasiPemeriksaanTidakMemuaskan'];
            $hasilPapSmear->nonNeoPlastikPerubahanReaktifPeradangan = $request['nonNeoPlastikPerubahanReaktifPeradangan'];
            $hasilPapSmear->nonNeoPlastikPerubahanReaktifRadiasi = $request['nonNeoPlastikPerubahanReaktifRadiasi'];
            $hasilPapSmear->nonNeoPlastikPerubahanReaktifIud = $request['nonNeoPlastikPerubahanReaktifIud'];
            $hasilPapSmear->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $hasilPapSmear,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getHasilPemeriksaanPcr(Request $request)
    {
        $data = DB::table('hasillabpcr_t as pc')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', 'pc.petugasfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', 'pc.petugasverifikatorfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', 'pc.petuggasapprovalfk')
            ->leftJoin('ruangan_m as rm', 'rm.id', 'pc.objectruanganfk')
            ->leftJoin('metodepemeriksanpcr_m as mp', 'mp.id', 'pc.metodeperiksafk')
            ->leftJoin('spesimenpcr_m as js', 'js.id', 'pc.jenisspesimenfk')
            ->select(
                'pc.*',
                'pg1.id as idpegawaipemeriksa',
                'pg1.namalengkap as pegawaipemeriksa',
                'pg2.id as idpegawaiverifikator',
                'pg2.namalengkap as pegawaiverifikator',
                'rm.id as idruangan',
                'rm.namaruangan',
                'mp.id as idmetodeperiksa',
                'mp.namametodeperiksa',
                'js.id as idjenisspesimen',
                'js.namajenisspesimen as namajenisspesimen',
                'pg3.id as idpetugasapproval',
                'pg3.namalengkap as namapetuggasapprovalfk'
            )
            ->where('pc.kdprofile', $this->kdProfile)
            ->where('pc.statusenabled', true)
            ->where('pc.pelayananpasienfk', $request['norec_pp'])
            ->first();

        return $this->respond($data);
    }

    public function saveHasilLabMikro(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec'] == "") {
                $hasilMikro = new HasilPemeriksaanMikro();
                $hasilMikro->norec = $hasilMikro->generateNewId();
                $hasilMikro->kdprofile = $kdProfile;
                $hasilMikro->statusenabled = true;
            } else {
                $hasilMikro = HasilPemeriksaanMikro::where('pelayananpasienfk', $request['pelayananpasienfk'])->orWhere('norec', $request['norec'])->first();
            }
            $hasilMikro->kodespesimen = $request['kodespesimen'];
            $hasilMikro->tglterimasampel = $request['tglterimasampel'];
            $hasilMikro->tglkeluarhasil = $request['tglkeluarhasil'];
            $hasilMikro->jenisspesimen = $request['jenisspesimen'];
            $hasilMikro->asalspesimen = $request['asalspesimen'];
            $hasilMikro->namajenisspesimen = $request['namajenisspesimen'];
            $hasilMikro->namaasalspesimen = $request['namaasalspesimen'];
            $hasilMikro->pmn = $request['pmn'];
            $hasilMikro->gpc = $request['gpc'];
            $hasilMikro->gpr = $request['gpr'];
            $hasilMikro->gnr = $request['gnr'];
            $hasilMikro->gndc = $request['gndc'];
            $hasilMikro->sec = $request['sec'];
            $hasilMikro->gncb = $request['gncb'];
            $hasilMikro->ycphh = $request['ycphh'];
            $hasilMikro->note = $request['note'];
            $hasilMikro->pemeriksaan = $request['pemeriksaan'];
            $hasilMikro->pemeriksaanpenunjang = $request['pemeriksaanpenunjang'];
            $hasilMikro->bas = $request['bas'];
            $hasilMikro->eos = $request['eos'];
            $hasilMikro->bat = $request['bat'];
            $hasilMikro->seg = $request['seg'];
            $hasilMikro->limf = $request['limf'];
            $hasilMikro->sun = $request['sun'];
            $hasilMikro->antibiotik = $request['antibiotik'];
            $hasilMikro->tglkultur = $request['tglkultur'];
            $hasilMikro->observasikultur = $request['observasikultur'];
            $hasilMikro->pemeriksakultur = $request['pemeriksakultur'];
            $hasilMikro->namapemeriksakultur = $request['namapemeriksakultur'];
            $hasilMikro->hasilakhirkultur = $request['hasilakhirkultur'];
            $hasilMikro->hasilujikepekaan = $request['hasilujikepekaan'];
            $hasilMikro->nocmfk = $request['nocmfk'];
            $hasilMikro->norec_pd = $request['norec_pd'];
            $hasilMikro->norec_apd = $request['norec_apd'];
            $hasilMikro->pelayananpasienfk = $request['pelayananpasienfk'];
            $hasilMikro->save();
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $hasilMikro,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getHasilPemeriksaanMikro(Request $request)
    {
        $data = DB::table('hasilmikro_t as pc')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', 'pc.pemeriksakultur')
            ->select(
                'pc.*',
                'pg1.id as pemeriksakultur',
            )
            ->where('pc.kdprofile', $this->kdProfile)
            ->where('pc.statusenabled', true)
            ->where('pc.pelayananpasienfk', $request['norec_pp'])
            ->first();

        return $this->respond($data);
    }

    public function getLaporanGlucotest(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $ruangan            = $request->ruangan;
        $tglAwal            = Carbon::parse($request['tglAwal'])->format('Y-m-d H:i') ?? date('Y-m-d H:i');
        $tglAkhir           = Carbon::parse($request['tglAkhir'])->format('Y-m-d H:i') ?? date('Y-m-d H:i');
        $kelompokpasien     = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp         = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa   = $request['dokter'] == "undefined" ? "" : $request['dokter'];
        $rangeDate          = [$tglAwal, $tglAkhir];
        $idProduk = [6715218, 1002130691];
        // return $rangeDate;
        try {
            $data = DB::table('pasiendaftar_t AS pd')
                ->select(
                    // 'apd.tanggal as test',
                    'pp.norec',
                    'pp.tglpelayanan',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'pp.statusenabled',
                    'apd.objectruanganfk',
                    'jk.jeniskelamin',
                    // 'klp.kelompokpasien',
                    'pro.namaproduk',
                    'pp.jumlah',
                    'pp.hargajual',
                    'djp.detailjenisproduk',
                    // 'hr.tanggal as tglexpertise',
                    'pg.namalengkap AS dokterdpjp',
                    'ru1.namaruangan AS ruangan'
                    // 'pg1.namalengkap AS dokter',
                    // 'pg2.namalengkap AS radiografer',
                    // DB::raw('
                    //     CASE
                    //     WHEN ru1.namaruangan IS NOT NULL THEN ru1.namaruangan
                    //     ELSE ru2.namaruangan
                    //     END AS ruangan

                    // '),
                    // 'ppp.objectjenispetugaspefk  as ppp',
                    // 'pppr.objectjenispetugaspefk as pppr'
                )
                ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', 'pd.norec')
                ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
                // ->leftJoin('pelayananpasienpetugas_t as ppp', function ($join) {
                //     $join->on('ppp.pelayananpasien', 'pp.norec');
                // })
                // ->leftJoin('pelayananpasienpetugas_t as pppr', function ($join) {
                //     $join->on('pppr.pelayananpasien', 'pp.norec');
                // })
                ->join('pasien_m AS ps', 'ps.id', 'pd.nocmfk')
                ->join('jeniskelamin_m AS jk', 'jk.id', 'ps.objectjeniskelaminfk')
                ->join('ruangan_m AS  rg', 'rg.id', 'pd.objectruanganlastfk')
                // ->leftJoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
                ->leftJoin('produk_m AS pro', 'pro.id', 'pp.produkfk')
                ->join('detailjenisproduk_m as djp', 'djp.id', 'pro.objectdetailjenisprodukfk')
                ->leftJoin('strukorder_t AS so', 'so.norec', 'apd.objectstrukorderfk')
                ->leftJoin('ruangan_m AS ru1', 'ru1.id', 'apd.objectruanganfk')
                ->leftJoin('batalregistrasi_t AS  br', 'br.pasiendaftarfk', 'pd.norec')
                ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
                // ->leftJoin('pegawai_m AS pg1', 'pg1.id', 'ppp.objectpegawaifk')
                // ->join('pegawai_m AS pg2', 'pg2.id', 'pppr.objectpegawaifk')
                // ->leftJoin('hasilradiologi_t as hr',  function ($join) {
                //     $join->on('hr.pelayananpasienfk', 'pp.norec');
                //     $join->on('hr.statusenabled', '=', DB::raw('\'t\''));
                // })
                ->where('pd.kdprofile', $kdProfile)
                ->whereNotNull('pro.namaproduk')
                ->where('br.norec', null)
                // ->where('apd.objectruanganfk', 78)
                ->when($ruangan, function ($query) use ($ruangan) {
                    return $query->where('ru1.id', $ruangan);
                })
                ->when($dokterdpjp, function ($query) use ($dokterdpjp) {
                    return $query->where('pg.id', $dokterdpjp);
                })
                // ->when($sDokterPemeriksa, function ($query) use ($sDokterPemeriksa) {
                //     return $query->where('pg1.id', $sDokterPemeriksa);
                // })
                // ->orderBy('apd.tglmasuk', 'DESC')
                // ->where('pd.kdprofile', $kdProfile)
                ->wherein('pro.id', $idProduk);
            // ->whereBetween(DB::raw("CAST(pp.tglpelayanan AS DATE)"), $rangeDate)
            // ->whereNotNull('hr.tanggal')
            if (isset($request['tglAwal']) && isset($request['tglAkhir'])) {
                $data = $data->whereBetween(DB::raw("CAST(pp.tglpelayanan as DATE)"), $rangeDate);
            }
            $data = $data->get();
            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function getLaporanHasil(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $ruangan            = $request->ruangan;
        $tglAwal            = Carbon::parse($request['tglAwal'])->startOfDay()->format('Y-m-d H:i:s'); // 00:00:00
        $tglAkhir           = Carbon::parse($request['tglAkhir'])->endOfDay()->format('Y-m-d H:i:s');   // 23:59:59
        $kelompokpasien     = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp         = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa   = $request['dokter'] == "undefined" ? "" : $request['dokter'];
        $rangeDate          = [$tglAwal, $tglAkhir];

        try {
            $data = DB::table('pasiendaftar_t as pd')
                ->join('pasien_m as pas', 'pas.id', '=', 'pd.nocmfk')
                ->leftJoin('lab_hasil as lh', function ($join) {
                    $join->on('lh.no_registrasi', '=', 'pd.noregistrasi')
                        ->where('lh.type', '=', 'U');
                })
                ->leftJoin('RESULT_HEADER as rh', 'rh.REGNUMBER', '=', 'pd.noregistrasi')
                ->leftJoin('RESULT_DATA as rd', function ($join) {
                    $join->on('rd.his_reg_no', '=', 'rh.HISREGNO')
                        ->where('rd.is_header', false);
                })
                ->whereBetween('pd.tglregistrasi', [$tglAwal, $tglAkhir]) // Gunakan nilai yang sudah diformat
                ->whereNotNull(DB::raw('COALESCE(lh.nama_pemeriksaan, rd.test_name)'))
                ->select([
                    'pd.noregistrasi',
                    'pas.nocm',
                    'pas.namapasien',
                    DB::raw('COALESCE(lh.nama_pemeriksaan, rd.test_name) AS nama_pemeriksaan'),
                    DB::raw('COALESCE(lh.hasil, rd.result) AS hasil'),
                    DB::raw('COALESCE(lh.normal, rd.reference_value) AS normal'),
                    DB::raw('COALESCE(lh.tgl_hasil, rd.authorization_date) AS tgl_hasil'),
                    DB::raw("
                        CASE
                            WHEN lh.nama_pemeriksaan IS NOT NULL THEN 'vanslab'
                            WHEN rd.test_name IS NOT NULL THEN 'prolims'
                            ELSE NULL
                        END AS sumber_data
                    ")
                ])
                ->orderBy('pd.noregistrasi')
                ->get();

            $result = [
                'status' => 200,
                'message' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            $result = [
                "status" => 400,
                "message" =>  $e->getMessage() . $e->getLine(),
                "data"  => []
            ];
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

     public function cetakImunohistokimia(Request $r)
    {
        $data = DB::table('hasilpemeriksaanimunohistokimia_t as ar')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'ar.noregistrasifk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->leftJoin('pegawai_m as pg1', 'pg1.id', '=', 'ar.dokterpengirimfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'ar.pegawaifk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'ps.id')
            ->leftJoin('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('kelas_m as kl', 'kl.id', 'pd.objectkelasfk')
            ->where('ar.pelayananpasienfk', $r->pelayananpasienfk)
            ->select(
                'ar.noregistrasifk as hasilpemerikasaan',
                'pp.noregistrasifk as pelayanan_pasien',
                'apd.noregistrasifk as antrianpasiendiperiksa',
                'pp.norec as pasiendaftar',
                'pd.noregistrasi',
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'ps.tgllahir',
                'alm.alamatlengkap as alamatlengkap',
                'kp.kelompokpasien',
                'ar.tanggalditerima',
                'ar.tanggaldijawab',
                'pp.tglpelayanan as tanggalpelayanan',
                'pg1.namalengkap  as penanggungjawab',
                'pg2.nosip',
                'pg2.namalengkap  as dokterpengirim',
                'ar.lca',
                'ar.ck',
                'ar.er',
                'ar.pr',
                'ar.herneu',
                'ar.ki67',
                'ar.kesimpulan',
                'kl.namakelas',
                'ar.nomorih'
            )
            ->orderBy('ar.tanggalditerima', 'DESC')
            ->first();

        $blade      = "report.laboratorium.imunohistokimia";
        $pageWidth  = 950;
        $res['pdf'] = isset($request['pdf']) ? $request['pdf'] : true;
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.laboratorium.imunohistokimia',
                array(
                    'res' => $res,
                    'pageWidth' => $pageWidth,
                    'data' => $data

                )
            );
            $pdf->setPaper('A4', 'portrait');
            return $pdf->stream();
        } else {
            return view($blade, compact('pageWidth', 'data', 'res'));
        }
    }
}
