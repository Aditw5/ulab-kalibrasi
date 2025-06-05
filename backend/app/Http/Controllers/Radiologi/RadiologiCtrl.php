<?php

namespace App\Http\Controllers\Radiologi;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\HasilRadiologi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Svg\Tag\Rect;

class RadiologiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function LayananRad(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
            $data = DB::table('pelayananpasien_t as pp')
            ->leftJoin('hasilradiologi_t as hr', function ($join) {
                $join->on('hr.pelayananpasienfk', '=', 'pp.norec')
                     ->whereRaw('hr.tanggalreport = (
                         SELECT MAX(hr_inner.tanggalreport)
                         FROM hasilradiologi_t hr_inner
                         WHERE hr_inner.pelayananpasienfk = pp.norec
                     )');
            })
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJoin('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftJoin(
                'ris_order as ris',
                'ris.order_no',
                '=',
                DB::raw('so.noorder AND ris.order_code=cast(pp.produkfk as text)')
            )
            ->select(
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'hr.norec as norec_hr',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap',
                'apd.norec as norec_apd',
                'so.noorder',
                'pp.strukfk',
                'pp.produkfk',
                'ru.objectdepartemenfk',
                'ris.order_key as idbridging',
                'ris.order_complete',
                'ris.description',
                'ris.report_date',
                'ris.link',
                'ris.accession_num',
                'ris.charge_doc_id',
                'ris.charge_doc_name',
                'so.norec as norec_so',
                'so.objectruangantujuanfk',
                'so.catatanklinis',
                'pp.keteranganlain as keteranganlainnya',
                DB::raw("  ris.patient_id || '-' || ris.order_cnt as radiologiid,
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
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->where('apd.noregistrasifk', $r['norec_pd']);

        if (isset($r['tglpelayanan']) && $r['tglpelayanan'] != '' && $r['tglpelayanan'] != 'undefined') {
            $data = $data->whereDate('pp.tglpelayanan', '=', Carbon::parse($r['tglpelayanan'])->toDateString());
        } else {
            $data = $data->whereDate('pp.tglpelayanan', '=', now()->toDateString());
        }

        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }

        $data = $data->orderByDesc('hr.tanggalreport')
                     ->get();


        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg.id')
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
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($data as $item) {
            $item->checked = false;
            $item->url_pacs_hasil = null;
            if ($item->order_complete == 1) {
                $exp = explode(',', $urlPACSHasil);
                $exp[0] = $exp[0] . $item->accession_num;
                $exp[1] = $exp[1] . $item->accession_num;
                $urlPACSHasil = implode(",", $exp);
                $item->url_pacs_hasil = $urlPACSHasil;
            }

            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                    $item->pegawaifk = $itemd->id;
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
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function hapusTindakanRad(Request $r)
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
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function hapusExpertise(Request $r)
    {
        DB::beginTransaction();
        try {
            foreach ($r['data'] as $item) {

                HasilRadiologi::where('norec', $item['norec_hr'])->where('kdprofile', $this->kdProfile)->delete();

                $this->LOGGING(
                    'Hapus Expertise',
                    $item['norec_hr'],
                    'hasilradiologi_t',
                    'Hapus Expertise ' . ' di ' . ' pada Pasien ' .
                        $r['namapasien'] . ' (' . $r['nocm'] . ') - ' . $r['noregistrasi']
                );
            }

            $countOrder = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'pp.noregistrasifk', 'apd.norec')
                ->leftjoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                ->where('pp.kdprofile', $this->kdProfile)
                ->where('pp.statusenabled', true)
                ->where('pp.noregistrasifk', $r['noregistrasifk'])
                ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
                ->count();

            $countHasilRad = HasilRadiologi::where('noregistrasifk', $r['noregistrasifk'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->count();
            if ($countOrder > $countHasilRad) {
                AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->where('norec', $r['noregistrasifk'])->update(['isExpertise' => null]);
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
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function detailPetugasRad(Request $r)
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

    public function savePetugasRad(Request $r)
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
    public function deletePetugasRad(Request $r)
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

    // public function saveExpertise(Request $r)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $hh = $r['hasil'];
    //         $kdProfile = $this->kdProfile;
    //         $cek = DB::table('hasilradiologi_t as hr')
    //             ->join('pelayananpasien_t as pp', 'pp.norec', 'hr.pelayananpasienfk')
    //             ->join('pegawai_m as pg', 'pg.id', 'hr.pegawaifk')
    //             ->select(
    //                 'hr.tanggal',
    //                 'hr.norec',
    //                 'pg.namalengkap',
    //                 'pp.produkfk',
    //                 'hr.pelayananpasienfk',
    //                 'hr.pegawaifk'
    //             )
    //             ->where('pp.norec', $r['norec'])
    //             ->where('hr.statusenabled', 'true')
    //             ->where('hr.kdprofile', $kdProfile)
    //             ->first();
    //         return $cek;
    //         if (!empty($cek)) {
    //             $h = $cek;
    //             HasilRadiologi::where('pelayananpasienfk', $r['norec'])->update(['keterangan' => $hh['keterangan'] ,'tanggal' => date('Y-m-d H:i:s')]);
    //             return 'cek';
    //         } else {
    //             $h = new HasilRadiologi();
    //             $h->norec = $h->generateNewId();
    //             $h->kdprofile = $kdProfile;
    //             $h->statusenabled = true;
    //             $h->tanggalreport = date('Y-m-d H:i:s');
    //             $h->pegawaifk = $hh['pegawaifk'];
    //             $h->keterangan = $hh['keterangan'];
    //             $h->pelayananpasienfk = $hh['pelayananpasienfk'];
    //             $h->noregistrasifk = $hh['noregistrasifk'];
    //             $h->save();
    //         }

    //         StrukOrder::where('norec', $r['norec_so'])
    //         ->where('kdprofile', $this->kdProfile)
    //         ->update(
    //             [
    //                 'tglexpertise' => date ('Y-m-d H:i:s'),
    //             ]
    //         );
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => $h,
    //             "message" => "Simpan Hasil Expertise Sukses"
    //         );
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         $result = array(
    //             "status" => 400,
    //             "message" => "Hemooh",
    //             "result"  => $e->getMessage() . ' ' . $e->getLine()
    //         );
    //     }
    //     return $this->respond($result, $result['status'], $result['message']);
    // }

    private function encryptData($data, $key, $cipher = "AES-128-CTR") {
        $options = 0;
        $encryption_iv = '1234567891011121';
        return openssl_encrypt($data, $cipher, $key, $options, $encryption_iv);
    }

    private function decryptData($encryptedData, $key, $cipher = "AES-128-CTR") {
        $options = 0;
        $decryption_iv = '1234567891011121';
        return openssl_decrypt($encryptedData, $cipher, $key, $options, $decryption_iv);
    }

    public function saveExpertise(Request $r)
    {
        DB::beginTransaction();

        try {
            $hh = $r['hasil'];
            $kdProfile = $this->kdProfile;
            $cek = DB::table('hasilradiologi_t as hr')
                ->join('pelayananpasien_t as pp', 'pp.norec', 'hr.pelayananpasienfk')
                ->join('pegawai_m as pg', 'pg.id', 'hr.pegawaifk')
                ->select(
                    'hr.tanggal',
                    'hr.norec',
                    'pg.namalengkap',
                    'pp.produkfk',
                    'hr.keterangan',
                    'hr.pelayananpasienfk',
                    'hr.pegawaifk'
                )
                ->where('hr.norec', $r['norec'])
                ->where('hr.statusenabled', 'true')
                ->where('hr.kdprofile', $kdProfile)
                ->first();
            if (!empty($cek)) {
                $message = 'Update Berhasil';
                HasilRadiologi::where('norec', $r['norec'])->update([
                    'keterangan' => $hh['keterangan'],
                    'pegawaifk' => $hh['pegawaifk'],
                    'tanggal' =>  date('Y-m-d H:i:s')
                ]);
                // update()
            } else {
                $message = 'Simpan Berhasil';
                $h = new HasilRadiologi();
                $h->norec = $h->generateNewId();
                $h->kdprofile = $kdProfile;
                $h->statusenabled = true;
                $h->tanggalreport = date('Y-m-d H:i:s');
                $h->pegawaifk = $hh['pegawaifk'];
                $h->keterangan = $hh['keterangan'];
                $h->pelayananpasienfk = $hh['pelayananpasienfk'];
                $h->noregistrasifk = $hh['noregistrasifk'];
                $h->save();
            }

            StrukOrder::where('norec', $r['norec_so'])->where('kdprofile', $this->kdProfile)->update(['tglexpertise' => date('Y-m-d H:i:s')]);

            $countOrder = PelayananPasien::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->where('strukorderfk', $r['norec_so'])->count();
            $countHasilRad = HasilRadiologi::where('noregistrasifk', $hh['noregistrasifk'])->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->count();

            if ($countOrder == $countHasilRad) {
                AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)->where('norec', $hh['noregistrasifk'])->update(['isExpertise' => true]);
            }

            PelayananPasienPetugas::where('pelayananpasien', $hh['pelayananpasienfk'])
                ->where('objectjenispetugaspefk', $this->settingFix('jenisPetugasDokterPemeriksa'))
                ->update([
                    'objectpegawaifk' => $hh['pegawaifk']
                ]);

            $dataOrder = DB::table('strukorder_t as so')
                        ->join('ris_order as ro', 'ro.order_no', 'so.noorder')
                        ->select(
                            'ro.patient_id',
                            'ro.patient_name',
                            'ro.accession_num',
                        )
                        ->where('so.norec', $r['norec_so'])
                        ->first();

                // bagian panggil API WA

//                 $client = new Client();
//                 $baseUrl = 'https://hasil.rsdgunungjati.com/portal/';
//                 $queryParams = "?user_name=hisris&password_encrypted=true&password=KGJGBCHIEJJDPCNCJGKEINFHNMHBKAOO&accession_number={$dataOrder->accession_num}";
//                 $fullUrl = rawurlencode($baseUrl . $queryParams);
//                 $fullUrlExpertise = rawurlencode("http://sim.rsdgunungjati.com/service/radiologi/cetak-ekspertise-nontoken?echo=true&norec={$r['norec']}");


//                 $encryptionKey = 'simrsMaju';

//                 $date = date('Y-m-d'); // Replace with actual dates
//                 $patientName = $dataOrder->patient_name; // Replace with actual patient name
//                 $patientID = $dataOrder->patient_id; // Replace with actual patient name
//                 $encryptedPatientID = $this->encryptData($patientID, $encryptionKey);
//                 $encryptedPatientName = $this->encryptData($patientName, $encryptionKey);
//                 $encryptedFullUrl = $this->encryptData(rawurlencode($fullUrl), $encryptionKey);
//                 $encryptedFullUrlExpertise = $this->encryptData(rawurlencode($fullUrlExpertise), $encryptionKey);

//                 $finalLink = "https://rsdgunungjati.com/exp?nocm={$encryptedPatientID}&nama_pasien={$encryptedPatientName}&linkPACS={$fullUrl}&link_expertise={$fullUrlExpertise}";

//                 $pesan = "Salam hormat,
// Berikut kami sampaikan hasil dari pemeriksaan radiologi tanggal {$date}, a/n pasien {$patientName} ({$dataOrder->patient_id}) dapat dilihat dengan cara klik link berikut ini :

// {$finalLink}

// Terima kasih,
// RSD Gunung Jati Kota Cirebon";

//                 $convertPhoneNumber = function ($phone) {
//                     if (strpos($phone, "0") === 0) {
//                         return "62" . substr($phone, 1);
//                     }
//                     return $phone;
//                 };

//                 $phone = "085156700853";
//                 $convertedPhone = $convertPhoneNumber($phone);

//                 $dataWA = [
//                     'phone' => $convertedPhone,
//                     'isGroup' => false,
//                     'isNewsletter' => false,
//                     'message' => $pesan,
//                 ];

//                 $response = $client->post('http://192.168.0.70/api/rsudgj/send-message', [
//                     'headers' => [
//                         'Authorization' => 'Bearer $2b$10$RYYPHqUmh_jUJkwXLJVqDO0X4_bdJxLFotsH12.B8DAne1VaWwYuO',
//                     ],
//                     'json' => $dataWA,
//                 ]);

            DB::commit();
            $result = array(
                "status" => 200,
                "result" => 'sukses',
                "message" => $message,
                'data' => array(
                    'norec' => isset($h->norec) ? $h->norec : $r['norec'],
                )
            );
        } catch (\Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Want Wrong",
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $result['message']);
    }

    public function cetakLayananRadiologi(Request $request)
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

        $data =  $this->indentitasCetak($kdProfile, $noregistrasi);
        // dd($data);
        // $so_norec = "5d16e070-5fa8-43a4-aeae-7d2641765631";
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $details = collect(DB::select("
            SELECT x.tglpelayanan,x.keteranganlain,x.namadokter,x.namaproduk,x.jumlah,x.hargasatuan,x.diskon,x.jasa,(x.jumlah * (x.hargasatuan - x.diskon)) + x.jasa AS total
            FROM ( SELECT tp.tglpelayanan,(select pg.namalengkap from pegawai_m as pg INNER JOIN pelayananpasienpetugas_t p3 on p3.objectpegawaifk = pg.id
                   WHERE p3.pelayananpasien = tp.norec AND p3.objectjenispetugaspefk = $sDokterPemeriksa limit 1) AS namadokter,tp.produkfk,pro.namaproduk,tp.jumlah, tp.keteranganlain,
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

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'user' => $user,
            'judul' => "BUKTI LAYANAN RADIOLOGI",
            'header' => $data,
            'details' =>  $details,
        );
        // dd($dataReport);
        return view(
            'report.radiologi.bukti-layanan-rad',
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

    public function getExpertise(Request $request)
    {
        $data = DB::table('hasilradiologi_t as ar')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ar.pegawaifk')
            // ->leftJoin('pelayananpasien_t as pp','pp.norec','pp.ar.pelayananpasienfk')
            ->orderBy('tanggal', 'DESC')
            ->select('ar.*', 'pg.namalengkap')
            ->where('ar.kdprofile', $this->kdProfile)
            ->where('ar.statusenabled', true)
            ->where('ar.pelayananpasienfk', $request['norec'])
            ->first();
        // if(isset($request['produkfk']) && $request['produkfk'] != ''){
        //     $data = $data->where('pp.produkfk',$request['produkfk']);
        // }

        return $this->respond($data);
    }

    public function kirimWA(Request $request)
    {
        try {
            $dataOrder = DB::table('strukorder_t as so')
                            ->join('ris_order as ro', 'ro.order_no', 'so.noorder')
                            ->join('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
                            ->join('pasien_m as pas', 'pas.id', 'so.nocmfk')
                            ->select(
                                'ro.patient_id',
                                'ro.patient_name',
                                'ro.accession_num',
                                'so.norec',
                                'ru.objectdepartemenfk',
                                'pas.nohp'
                            )
                            ->where('ro.accession_num', $request['accession_num'])
                            ->first();
                            $otp = rand(100000, 999999);
            $client = new Client();
                    $baseUrl = 'https://hasil.rsdgunungjati.com/portal/';
                    $queryParams = "?user_name=hisris&password_encrypted=true&password=KGJGBCHIEJJDPCNCJGKEINFHNMHBKAOO&accession_number={$dataOrder->accession_num}";
                    $fullUrl = rawurlencode($baseUrl . $queryParams);
                    $fullUrlExpertise = rawurlencode("http://rsdgunungjati.com/service/radiologi/cetak-ekspertise-nontoken?echo=true&norec={$dataOrder->norec}");


                    $encryptionKey = 'simrsMaju';

                    $date = date('Y-m-d'); // Replace with actual dates
                    $patientName = $dataOrder->patient_name; // Replace with actual patient name
                    $patientID = $dataOrder->patient_id; // Replace with actual patient name
                    $encryptedPatientID = $this->encryptData($patientID, $encryptionKey);
                    $encryptedPatientName = $this->encryptData($patientName, $encryptionKey);
                    $encryptedFullUrl = $this->encryptData(rawurlencode($fullUrl), $encryptionKey);
                    $encryptedFullUrlExpertise = $this->encryptData(rawurlencode($fullUrlExpertise), $encryptionKey);
                    $encryptedOtp = $this->encryptData($otp, $encryptionKey);
                    $finalLink = "https://sim.rsdgunungjati.com/exp?nocm={$encryptedPatientID}&nama_pasien={$encryptedPatientName}&linkPACS={$fullUrl}&link_expertise={$fullUrlExpertise}";


                    $pesan = "Salam hormat,
    Berikut kami sampaikan hasil dari pemeriksaan radiologi tanggal {$date}, a/n pasien {$patientName} ({$dataOrder->patient_id}) Silakan masukkan kode OTP berikut untuk mengakses hasil: *{$otp}*.

    dapat dilihat dengan cara klik link berikut ini :

    {$finalLink}
    *Jika link di atas tidak bisa diklik, silahkan simpan nomor ini terlebih dahulu dan  atau forward pesan ini ke nomor sendiri !*
    Terima kasih,
    RSD Gunung Jati Kota Cirebon";

                    $convertPhoneNumber = function ($phone) {
                        if (strpos($phone, "0") === 0) {
                            return "62" . substr($phone, 1);
                        }
                        return $phone;
                    };

                    $phone = $dataOrder->nohp;
                    $convertedPhone = $convertPhoneNumber($phone);

                    $dataWA = [
                        'phone' => $convertedPhone,
                        'isGroup' => false,
                        'isNewsletter' => false,
                        'message' => $pesan,
                    ];

                    // if($dataOrder->objectdepartemenfk == '18'){
                        $response = $client->post('http://192.168.0.70/api/rsudgj3/send-message', [
                            'headers' => [
                                'Authorization' => 'Bearer $2b$10$JiGVPk_DjqU6HBI.nxkjjemiQ6E5gRpL77ekRvqx9_wl8_4j2ND36',
                            ],
                            'json' => $dataWA,
                        ]);
                    // }
                    $result = array(
                        "status" => 200,
                        "message" => "Berhasil Kirim",
                        "result" => $dataWA
                    );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" => "Gagal Kirim",
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result);
    }

    public function cetakEkspertiseEcho(Request $r)
    {


        $kdProfile =  $this->kdProfile;
        $params1='';
        $params2='';

        if (isset($r['noregistrasi'])) {
            $data = DB::table("hasilradiologi_t as hr")
                ->select('hr.norec')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hr.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->where('pd.noregistrasi', $r['noregistrasi'])
                ->first();

            // if (!empty($data)) {
            //     $r['norec'] = $data->norec;
            // } else {
            //     $r['norec'] = null;
            // }

            $params1 = " AND pd.noregistrasi = '".$r['noregistrasi']."'";
        }

        if(isset($r['norec'])){
            // Use a condition to set $params1 only if $r['norec'] is not empty
            $params1 = " AND so.norec = '".$r['norec']."'";
        }

//         $raw = DB::select("
//     SELECT
//         so.nofoto, ps.nocm, ps.namapasien, ps.tgllahir, kp.kelompokpasien,
//         ru.namaruangan, so.tanggal, jk.jeniskelamin, sod.catatanklinis, sod.tglverif as tglverif, sod.tglexpertise as tglexpertise,
//         CASE WHEN alm.alamatlengkap IS NULL THEN '-' ELSE (alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi) END AS alamatlengkap,
//         pg.namalengkap as perujuk, pg2.namalengkap as dokterrad,
//         pr.namaproduk, so.keterangan, pd.noregistrasi, pg2.nippns, pg2.nosip as dokterradnosip,
//         pg2.id as pgid, pg.nosip as perujukdokternosip, pg.id as pgidperujuk,
//         ru.norec as noruangan, pd.tglregistrasi as tglregistrasi
//     FROM
//         hasilradiologi_t AS so
//     INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = so.noregistrasifk
//     INNER JOIN pelayananpasien_t AS pp ON pp.norec = so.pelayananpasienfk
//     LEFT JOIN strukorder_t as sod ON sod.norec = pp.strukorderfk
//     INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
//     INNER JOIN pasien_m AS ps ON ps.ID = pd.nocmfk
//     INNER JOIN kelompokpasien_m AS kp ON kp.ID = pd.objectkelompokpasienlastfk
//     INNER JOIN ruangan_m AS ru ON ru.ID = sod.objectruanganfk
//     INNER JOIN produk_m AS pr ON pr.id = pp.produkfk
//     LEFT JOIN jeniskelamin_m AS jk ON jk.ID = ps.objectjeniskelaminfk
//     LEFT JOIN alamat_m AS alm ON alm.nocmfk = ps.ID
//     LEFT JOIN desakelurahan_m as ds on ds.id = alm.objectdesakelurahanfk
//     LEFT JOIN kotakabupaten_m as kk on kk.id = alm.objectkotakabupatenfk
//     LEFT JOIN kecamatan_m as kc on kc.id = alm.objectkecamatanfk
//     LEFT JOIN propinsi_m as pro on pro.id = alm.objectpropinsifk
//     LEFT JOIN pegawai_m AS pg ON pg.ID = apd.objectpegawaifk
//     LEFT JOIN pegawai_m AS pg2 ON pg2.ID = so.pegawaifk
//     WHERE so.kdprofile = $kdProfile AND so.statusenabled = TRUE $params1
// ");

$raw = DB::table('hasilradiologi_t as hr')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'hr.noregistrasifk')
        ->join('pelayananpasien_t as pp', 'pp.norec', 'hr.pelayananpasienfk')
        ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
        ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
        ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
        ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
        ->leftJoin('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
        ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
        ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
        ->leftJoin('produk_m as pr', 'pr.id', 'pp.produkfk')
        ->leftJoin('desakelurahan_m as ds', 'ds.id', 'alm.objectdesakelurahanfk')
        ->leftJoin('kecamatan_m as kc', 'kc.id', 'alm.objectkecamatanfk')
        ->leftJoin('kotakabupaten_m as kk', 'kk.id', 'alm.objectkotakabupatenfk')
        ->leftJoin('propinsi_m as pro', 'pro.id', 'alm.objectpropinsifk')
        ->leftJoin('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
        ->leftJoin('pegawai_m as pg2', 'pg2.id', 'hr.pegawaifk')
        ->select(
            'hr.nofoto',
            'ps.nocm',
            'ps.namapasien',
            'ps.tgllahir',
            'kp.kelompokpasien',
            'ru.namaruangan',
            'hr.tanggal',
            'jk.jeniskelamin',
            'so.catatanklinis',
            'so.tglverif as tglverif',
            'so.tglexpertise as tglexpertise',
            DB::raw("
                CASE WHEN alm.alamatlengkap IS NULL THEN '-' ELSE (alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi) END AS alamatlengkap
            "),
            'pg.namalengkap as perujuk',
            'pg2.namalengkap as dokterrad',
            'pr.namaproduk',
            'hr.keterangan',
            'pd.noregistrasi',
            'pg2.nippns',
            'pg2.nosip as dokterradnosip',
            'pg2.id as pgid',
            'pg.nosip as perujukdokternosip',
            'pg.id as pgidperujuk',
            'ru.norec as noruangan',
            'pd.tglregistrasi as tglregistrasi',
        )
        ->where('hr.statusenabled', true);
            if(isset($r['norec'])){
                // Use a condition to set $params1 only if $r['norec'] is not empty
                $raw = $raw->where('hr.norec',$r['norec'])->orWhere('so.norec',$r['norec']);
            }
            if(isset($r['noregistrasi'])){
                // Use a condition to set $params1 only if $r['norec'] is not empty
                $raw = $raw->where('pd.noregistrasi',$r['noregistrasi']);
            }
        $raw = $raw->get();

// return $raw;

foreach ($raw as $item) {
    $item->ttde = $item->dokterrad;
    $item->keterangan = str_replace('#&#', "\n", $item->keterangan);
    $item->ttde = base64_encode(QrCode::format('svg')->size(75)->generate($item->dokterrad));
}

        // return $raw;
        // if (!empty($raw)) {
        //     $raw->umur = $this->getAge($raw->tgllahir, date('Y-m-d'));
        // } else {
        //     // Handle the case when the collection is empty
        //     return null;
        // }

        if (!$raw || empty($raw)) {
            return null;
        }

        if (isset($r['noregistrasi'])) {
            if (!$data || empty($data)) {
                return null;
            }
        }


        $pageWidth = 950;


        // $dataImg = DB::connection('mongodb')
        //     ->table('TandaTangan')
        //     ->where('pegawaifk', (int)$raw->first()->pgid)
        //     ->where('statusenabled', true)
        //     ->where('kdprofile', (int) $this->kdProfile)
        //     ->first();
        $res['pdf']  =  true;

        // $img = null;
        // if (!empty($dataImg)) {
        //     $img = $dataImg['ttd'];
        // }
        // $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($raw->first()->dokterrad));
        $title = isset($r['echo']) ? 'ECHOCARDIOGRAFI' : 'RADIOLOGI';
        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                'report.radiologi.expertise',
                array(
                    'raw' => $raw,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    // 'img' => $img,
                    'title' => $title,
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.radiologi.expertise',
                array(
                    'raw' => $raw,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    // 'img' => $img,
                    // 'ttde' => $qrcode,
                    'title' => $title,
                )
            );
            return $pdf->stream($title . '.pdf', ['Attachment' => false])
            ->header('Content-Type', 'application/pdf')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
            }
        return view(
            'report.radiologi.expertise',
            compact('raw', 'pageWidth', 'img', 'title', 'res')
        );
    }
    public function cetakEkspertiseEchoNonToken(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $params1='';
        $params2='';

        if (isset($r['noregistrasi'])) {
            $data = DB::table("hasilradiologi_t as hr")
                ->select('hr.norec')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hr.noregistrasifk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->where('pd.noregistrasi', $r['noregistrasi'])
                ->first();

            $params1 = " AND pd.noregistrasi = '".$r['noregistrasi']."'";
        }

        if(isset($r['norec'])){
            $params1 = " AND so.norec = '".$r['norec']."'";
        }

$raw = DB::table('hasilradiologi_t as hr')
        ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', 'hr.noregistrasifk')
        ->join('pelayananpasien_t as pp', 'pp.norec', 'hr.pelayananpasienfk')
        ->leftJoin('strukorder_t as so', 'so.norec', 'pp.strukorderfk')
        ->leftJoin('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
        ->leftJoin('pasien_m as ps', 'ps.id', 'pd.nocmfk')
        ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
        ->leftJoin('ruangan_m as ru', 'ru.id', 'so.objectruanganfk')
        ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'ps.objectjeniskelaminfk')
        ->leftJoin('alamat_m as alm', 'alm.nocmfk', 'ps.id')
        ->leftJoin('produk_m as pr', 'pr.id', 'pp.produkfk')
        ->leftJoin('desakelurahan_m as ds', 'ds.id', 'alm.objectdesakelurahanfk')
        ->leftJoin('kecamatan_m as kc', 'kc.id', 'alm.objectkecamatanfk')
        ->leftJoin('kotakabupaten_m as kk', 'kk.id', 'alm.objectkotakabupatenfk')
        ->leftJoin('propinsi_m as pro', 'pro.id', 'alm.objectpropinsifk')
        ->leftJoin('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
        ->leftJoin('pegawai_m as pg2', 'pg2.id', 'hr.pegawaifk')
        ->select(
            'hr.nofoto',
            'ps.nocm',
            'ps.namapasien',
            'ps.tgllahir',
            'kp.kelompokpasien',
            'ru.namaruangan',
            'hr.tanggal',
            'jk.jeniskelamin',
            'so.catatanklinis',
            'so.tglverif as tglverif',
            'so.tglexpertise as tglexpertise',
            DB::raw("
                CASE WHEN alm.alamatlengkap IS NULL THEN '-' ELSE (alm.alamatlengkap || ' ' || ds.namadesakelurahan || ' '|| kc.namakecamatan || ' ' || kk.namakotakabupaten || ' '  || pro.namapropinsi) END AS alamatlengkap
            "),
            'pg.namalengkap as perujuk',
            'pg2.namalengkap as dokterrad',
            'pr.namaproduk',
            'hr.keterangan',
            'pd.noregistrasi',
            'pg2.nippns',
            'pg2.nosip as dokterradnosip',
            'pg2.id as pgid',
            'pg.nosip as perujukdokternosip',
            'pg.id as pgidperujuk',
            'ru.norec as noruangan',
            'pd.tglregistrasi as tglregistrasi',
        )
        ->where('hr.statusenabled', true);
            if(isset($r['norec'])){
                // Use a condition to set $params1 only if $r['norec'] is not empty
                $raw = $raw->where('hr.norec',$r['norec'])->orWhere('so.norec',$r['norec']);
            }
            if(isset($r['noregistrasi'])){
                // Use a condition to set $params1 only if $r['norec'] is not empty
                $raw = $raw->where('pd.noregistrasi',$r['noregistrasi']);
            }
        $raw = $raw->get();

// return $raw;

foreach ($raw as $item) {
    $item->ttde = $item->dokterrad;
    $item->keterangan = str_replace('#&#', "\n", $item->keterangan);
    $item->ttde = base64_encode(QrCode::format('svg')->size(75)->generate($item->dokterrad));
}
        if(!$raw){
            return null;
        }


        $pageWidth = 950;

        $res['pdf']  =  false;
        $title = isset($r['echo']) ? 'ECHOCARDIOGRAFI' : 'RADIOLOGI';
        return view(
            'report.radiologi.expertiseNonToken',
            compact('raw', 'pageWidth', 'title', 'res')
        );
    }

    public function listRegisRadiologi(Request $r)
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
        if (isset($r['rsearch']) && $r['rsearch'] != '') {
            $searchTerm = '%' . $r['rsearch'] . '%';
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

    public function saveTransaksiRad(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewAPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewPD['tglregistrasi'])) . ' 23:59')
                ->count();

            $noAntrian = $countNoAntrian + 1;

            $pd = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

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
            $dataAPD->objectruanganasalfk = $r_NewPD['objectruanganlastfk'];;
            $dataAPD->tglregistrasi = $r_NewPD['tglregistrasi'];
            $dataAPD->tglregistrasi = $pd->tglregistrasi; //date('Y-m-d H:i:s');
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

    public function getLaporanTindakanRadiologi(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $kelompokpasien     = $request->kelompokpasien;
        $tglAwal            = Carbon::parse($request['tglAwal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglAkhir'])->format('Y-m-d') ?? date('Y-m-d');
        $rangeDate          = ["$tglAwal 00:00:00", "$tglAkhir 23:59:59"];
        $kelompokpasien     = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp         = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa   = $request['dokter'] == "undefined" ? "" : $request['dokter'];
        try {
            // Subquery to get the latest hasilradiologi_t record for each pelayananpasienfk
            $latestRadiologySubquery = DB::table('hasilradiologi_t as hr')
            ->select('hr.pelayananpasienfk', DB::raw('MAX(hr.tanggal) as latest_tanggal'))
            ->where('hr.statusenabled', true)
            ->groupBy('hr.pelayananpasienfk');

            $data = DB::table('pasiendaftar_t AS pd')
            ->select(
                'hr.tanggal as test',
                'pp.norec',
                'pp.tglpelayanan',
                'ps.nocm',
                'pd.noregistrasi',
                'ps.namapasien',
                'hr.statusenabled',
                'apd.objectruanganfk',
                'jk.jeniskelamin',
                'klp.kelompokpasien',
                'pro.namaproduk',
                'pp.jumlah',
                'pp.hargajual',
                'djp.detailjenisproduk',
                'hr.tanggal as tglexpertise',
                'pg.namalengkap AS dokterdpjp',
                'pg1.namalengkap AS dokter',
                'pg2.namalengkap AS radiografer',
                DB::raw('
                    CASE
                    WHEN ru1.namaruangan IS NOT NULL THEN ru1.namaruangan
                    ELSE ru2.namaruangan
                    END AS ruangan
                '),
                'ppp.objectjenispetugaspefk as ppp',
                'pppr.objectjenispetugaspefk as pppr'
            )
            ->join('antrianpasiendiperiksa_t AS apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftJoin('pelayananpasienpetugas_t as ppp', function ($join) {
                $join->on('ppp.pelayananpasien', '=', 'pp.norec');
            })
            ->leftJoin('pelayananpasienpetugas_t as pppr', function ($join) {
                $join->on('pppr.pelayananpasien', '=', 'pp.norec');
            })
            ->join('pasien_m AS ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m AS jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('ruangan_m AS rg', 'rg.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('kelompokpasien_m AS klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('produk_m AS pro', 'pro.id', '=', 'pp.produkfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pro.objectdetailjenisprodukfk')
            ->leftJoin('strukorder_t AS so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftJoin('ruangan_m AS ru1', 'ru1.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m AS ru2', 'ru2.id', '=', 'apd.objectruanganasalfk')
            ->leftJoin('batalregistrasi_t AS br', 'br.pasiendaftarfk', '=', 'pd.norec')
            ->leftJoin('pegawai_m AS pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJoin('pegawai_m AS pg1', 'pg1.id', '=', 'ppp.objectpegawaifk')
            ->join('pegawai_m AS pg2', 'pg2.id', '=', 'pppr.objectpegawaifk')
            ->leftJoinSub($latestRadiologySubquery, 'latest_hr', function ($join) {
                $join->on('latest_hr.pelayananpasienfk', '=', 'pp.norec');
            })
            ->leftJoin('hasilradiologi_t as hr', function ($join) {
                $join->on('hr.pelayananpasienfk', '=', 'latest_hr.pelayananpasienfk')
                    ->on('hr.tanggal', '=', 'latest_hr.latest_tanggal');
            })
            ->where('pd.kdprofile', $kdProfile)
            ->whereNotNull('pro.namaproduk')
            ->where('br.norec', null)
            ->where('apd.objectruanganfk', 78)
            ->when($kelompokpasien, function ($query) use ($kelompokpasien) {
                return $query->where('klp.id', $kelompokpasien);
            })
            ->when($dokterdpjp, function ($query) use ($dokterdpjp) {
                return $query->where('pg.id', $dokterdpjp);
            })
            ->when($sDokterPemeriksa, function ($query) use ($sDokterPemeriksa) {
                return $query->where('pg1.id', $sDokterPemeriksa);
            })
            ->whereBetween('pp.tglpelayanan', $rangeDate)
            ->orderBy('apd.tglmasuk', 'DESC')
            ->whereNotNull('hr.tanggal')
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

    public function getLaporanRekapTindakanRadiologi(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $kelompokpasien     = $request->kelompokpasien;
        $tglAwal            = Carbon::parse($request['tglAwal'])->format('Y-m-d') ?? date('Y-m-d');
        $tglAkhir           = Carbon::parse($request['tglAkhir'])->format('Y-m-d') ?? date('Y-m-d');
        $kelompokpasien     = $request['kelompokpasien'] == "undefined" ? "" : $request['kelompokpasien'];
        $dokterdpjp         = $request['dokterdpjp'] == "undefined" ? "" : $request['dokterdpjp'];
        $sDokterPemeriksa   = $request['dokter'] == "" ? "" : "and pg1.id =" . $request['dokter'];
        $rangeDate          = [$tglAwal, $tglAkhir];
        try {
            $data = collect(DB::select("	select pr.namaproduk,pg1.namalengkap as dokter,djp.detailjenisproduk, count(hr.norec) as qty
            from hasilradiologi_t as hr
            JOIN pegawai_m AS pg1 ON pg1.id = hr.pegawaifk
            join pelayananpasien_t as pp on pp.norec=hr.pelayananpasienfk
            join produk_m as pr on pr.id =pp.produkfk
             INNER JOIN detailjenisproduk_m AS djp ON djp.id = pr.objectdetailjenisprodukfk
             where hr.tanggalreport >= '$tglAwal 00:00:00'
             AND hr.tanggalreport <= '$tglAkhir 23:59:59'
             $sDokterPemeriksa
             and hr.statusenabled=true
             group by  pr.namaproduk,pg1.namalengkap,djp.detailjenisproduk
           "));

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
}
