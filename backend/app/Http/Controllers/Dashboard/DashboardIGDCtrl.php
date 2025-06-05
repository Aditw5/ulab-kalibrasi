<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Kamar;
use App\Models\Master\Ruangan;
use App\Models\Standar\KelompokUser;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StokProdukDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use Exception;

class DashboardIGDCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // DROPDOWN RUANGAN

    public function getIGD(Request $r)
    {
        $set = explode(',', $this->settingFix('idDepartemenIGD'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();

        return $this->respond($res);
    }

    public function getIGDDetail(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('jd.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jd.hari', 'ilike', '%'.$now .'%')
            ->where('pg.namalengkap', '<>','Dokter Umum');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%'.$r['namadokter'].'%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit( $r['limit']);
        }
        $dokter->orderBy('pg.namalengkap');

        $dokter =  $dokter->get();

        foreach($dokter as $d){
            $d->hari = $now;
        }
        $produk  = DB::table('stokprodukdetail_t as spd')
            ->join('ruangan_m as ru', 'spd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('produk_m as pr', 'spd.objectprodukfk', '=', 'pr.id')
            ->leftjoin('asalproduk_m as ap', 'spd.objectasalprodukfk', '=', 'ap.id')
            ->select(
                DB::raw("sum(spd.qtyproduk) as qtyproduk,
                ru.id,
                ru.namaruangan,
                pr.namaproduk,
                ap.asalproduk,
                spd.harganetto1,
                spd.harganetto2")
            )
            ->where('spd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
           $produk->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['nama']) && $r['nama'] != '') {
            $produk->where('pr.namaproduk', 'ilike',  '%'.$r['nama'].'%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
           $produk->limit( $r['limit']);
        }
        $produk->groupBy('ru.id', 'ru.namaruangan',  'pr.namaproduk',  'ap.asalproduk',  'spd.harganetto1', 'spd.harganetto2');
        $produk->orderBy('pr.namaproduk');
        $produk =  $produk->get();


        $res['dokter'] = $dokter;
        $res['produk'] = $produk;
        return $this->respond($res);
    }

    public function getIGDPasien(Request $r)
    {
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");
        $data  = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('ruangan_m as rupes', 'pd.ruangannextschedule', '=', 'rupes.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('suratketerangan_t as st', 'st.pasiendaftarfk', '=', 'pd.norec')
            ->leftjoin('pasienperjanjian_t as rm', 'rm.objectsuratfk', '=', 'st.norec')
            ->leftJoin('penandapasien_t as pp', 'pp.noregistrasi', 'pd.noregistrasi')
            ->select(
                'rm.norec as norec_perjanjian',
                'st.jenissuratfk',
                'ru.namaruangan',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'pd.nocmfk',
                'ps.nocm',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ru.objectdepartemenfk',
                'ps.tgllahir',
                'ps.nobpjs',
                'ps.noidentitas',
                'pd.objectpegawaifk',
                'pd.ruangannextschedule',
                'rupes.namaruangan as namaruanganpesanan',
                'pd.noregistrasi',
                'pg.namalengkap',
                'ps.namapasien',
                'jk.jeniskelamin',
                'kp.kelompokpasien',
                'apd.tglkeluar as tglkeluar',
                'pd.tglpulang as tglpulang',
                'pp.penanda',
                'pp.kategori_usia as kategoriUsia',
                'pp.kategori_insiden as kategoriInsiden',
                'pa.nosep',
                DB::raw("CAST(pd.tglregistrasi
                AS DATE),
                (case when pd.ispelayananpasien=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk"),
            DB::raw(
                "
                CASE
                    WHEN apd.tglkeluar IS NULL THEN
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) / (24 * 3600)) || ' hari ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % (24 * 3600) / 3600) || ' jam ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % 3600 / 60) || ' menit'
                    ELSE
                        FLOOR(EXTRACT(EPOCH FROM apd.tglkeluar - apd.tglmasuk) / (24 * 3600)) || ' hari ' ||
                        FLOOR(EXTRACT(EPOCH FROM apd.tglkeluar - apd.tglmasuk) % (24 * 3600) / 3600) || ' jam ' ||
                        FLOOR(EXTRACT(EPOCH FROM apd.tglkeluar - apd.tglmasuk) % 3600 / 60) || ' menit'
                END AS selisihWaktu"
                )
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('idDepartemenIGD')))
            // ->whereBetween(DB::raw("pd.tglregistrasi::date"),$rangeDate)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ;

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }


        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noregistrasi'] . '%');
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm',  'ilike', '%' . $r['nocm'] . '%');
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
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
        // if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
        //     $data = $data->whereNotNull('pd.tglpulang');
        // }else{
        //     $data = $data->whereNull('pd.tglpulang');
        // }
        if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
            if ($r['statuspanggil'] == 'true') {
                $data = $data->whereNotNull('pd.tglpulang');
            } elseif ($r['statuspanggil'] == 'rawat') {
                $data = $data->whereNull('pd.tglpulang')
                             ->whereNull('apd.tglkeluar');
            }
        }
        $data = $data->orderBy('apd.tglregistrasi');
        if(isset($r['limit'])){
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function Ruangandropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }

    public function HitungAntrianIGD(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data =  DB::table('antrianpasiendiperiksa_t as at')
        ->join('ruangan_m as ru','ru.id','=','at.objectruanganfk')
        ->select(DB::raw("count (ru.id) as total, case when at.status is null or (at.statusantrian is null or at.statusantrian = '0')  then 'Belum Dipanggil' else  at.status end as name "))
        ->where('at.statusenabled',true)
        ->where('at.kdprofile',$kdProfile)
        ->whereIN('ru.objectdepartemenfk',explode(',', $this->settingFix('idDepartemenIGD')));
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where('at.tglregistrasi','>=', $r['dari'].' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where('at.tglregistrasi', '<=',  $r['sampai'].' 23:59');
        }
        $data = $data->groupBy('at.status','at.statusantrian');
        $data = $data->get();

        $seriesJK = [];
        $labelJK = [];
        foreach ($data as $d) {
            $seriesJK[] = $d->total;
            $labelJK[]  = $d->name;
        }
        $result['chartStatus']['series'] = $seriesJK;
        $result['chartStatus']['labels'] = $labelJK;
        return $this->respond($result);
    }

    public function panggilPasienIGD(Request $r)
    {

        DB::beginTransaction();
        try{
            $status =  'Sudah Dipanggil';
            $kelompokUser = KelompokUser::where('id',session('kelompokuser_id'))->first();
            if(!empty($kelompokUser) && $kelompokUser->kelompokuser == 'dokter'){
               $update =  [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggildokter' => date('Y-m-d H:i:s'),
               ];
            }else{
                $update =  [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggilsuster' => date('Y-m-d H:i:s'),
               ];
            }
            AntrianPasienDiperiksa::where('norec',$r->norec_apd)
            ->where('kdprofile', $this->kdProfile)
            ->update($update);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "status" =>$status,
                    "as" => '@epic',
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null
            );

        }
        return $this->respond($result['result'], $result['status'], $transMessage);

    }

    public function getRiwayatMutasiIGD(Request $r)
    {

        $data  = DB::table('pasiendaftar_t as pd')
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
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'pd.tglregistrasi',
                'apd.tglmasuk',
                'ps.namapasien',
                'ps.nocm',
                'ps.objectjeniskelaminfk',
                'pd.noregistrasi',
                'ruas.namaruangan',
                'ru.namaruangan as Ruangan_Mutasi',
                'tt.reportdisplay',
                'pg.namalengkap',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.nobpjs',
                'ps.alamatrmh',
                'kls.namakelas',
                'pd.tglpulang',
                'apd.noregistrasifk',
                'pd.norec as norec_pd',
                'pd.nocmfk',
                'pd.objectruanganlastfk',
                'pd.objectpegawaifk',
                'pd.objectpegawairawatbersamafk',
                'pd.objectruanganasalfk',
                'apd.noregistrasifk',
                'ruas.id',
                'ps.noidentitas',
                'ru.objectdepartemenfk',
                'ru.statusenabled',
                // 'kmr.namakamar',
                'pg2.namalengkap as nama',
                'apd.norec as norec_apd',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk',  $this->settingFix('idDepRawatInap'))
            ->where('ruas.objectdepartemenfk',explode(',', $this->settingFix('idDepartemenIGD')))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            // ->whereNull('apd.tglkeluar')
            ->where('apd.kdprofile', $this->kdProfile);
            // ->whereNull('pd.tglpulang');

            if (isset($r['dari']) && $r['dari'] != '') {
                $data = $data->where(DB::raw("apd.tglmasuk::date"), '>=', $r->dari);
            }
            if (isset($r['sampai']) && $r['sampai'] != '') {
                $data = $data->where(DB::raw("apd.tglmasuk::date"), '<=', $r->sampai);
            }

            if (isset($r['searchmutasi']) && $r['searchmutasi'] != '') {
                $searchTermMutasi = '%' . $r['searchmutasi'] . '%';
                $data = $data->where(function ($query) use ($searchTermMutasi) {
                    $query->where('pd.noregistrasi', 'ilike', $searchTermMutasi)
                          ->orWhere('ps.nocm', 'ilike', $searchTermMutasi)
                          ->orWhere('ps.namapasien', 'ilike', $searchTermMutasi)
                          ->orWhere('ps.nobpjs', 'ilike', $searchTermMutasi)
                          ->orWhere('ps.noidentitas', 'ilike', $searchTermMutasi);
                });
            } elseif (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
                $data = $data->where('pd.noregistrasi', 'ilike', '%' . $r['noregistrasi'] . '%');
            } elseif (isset($r['nocm']) && $r['nocm'] != '') {
                $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
            } elseif (isset($r['namapasien']) && $r['namapasien'] != '') {
                $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
            }

            $data = $data->orderBy('apd.tglmasuk', 'ASC');
            $data = $data->distinct();
            $data =  $data->get();

        foreach ($data as $d) {
            $d->lamarawat =  $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
        }

        return $this->respond($data);
    }

    public function kirimWASuratDokterIGD(Request $request)
    {
        try {

            // Ambil data pasien dari database
            $datapasien = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
                ->join('pasien_m as pas', 'pas.id', '=', 'pd.nocmfk')
                ->join('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
                ->join('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
                ->select(
                    'pas.nohp',
                    'pas.id as patient_id',
                    'pas.nocm AS patient_cm',
                    'pas.namapasien as patient_name',
                    'pd.norec',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'pg.namalengkap AS dokter',
                    'ru.objectdepartemenfk',
                    'kp.kelompokpasien'
                )
                ->where('pd.norec', $request['norec_pd'])
                ->first();
                // $otp = rand(100000, 999999);

            $client = new Client();
                    $encryptionKey = 'simrsMaju';

                    $date = date('Y-m-d'); // Replace with actual dates
                    $patientName = $datapasien->patient_name; // Replace with actual patient name
                    $patientID = $datapasien->patient_id; // Replace with actual patient name
                    $encryptedPatientID = $this->encryptData($patientID, $encryptionKey);
                    $encryptedPatientName = $this->encryptData($patientName, $encryptionKey);

                    // $finalLink = "https://rsdgunungjati.com/service/dashboard/registrasi/cetak-surat-keterangan-dokter?&noregistrasi={$datapasien->noregistrasi}&dokter={$datapasien->dokter}&kelompokpasien={$datapasien->kelompokpasien}&objectdepartemenfk=18&tglregistrasi={$datapasien->tglregistrasi}&norec_pd={$datapasien->norec}&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzUxMDE2NDN9.y2rs5nFOX8J4PK63Uxv_Df1AFY7MoDR1e8WmdLxDmxtolZNg9XrylQgMl2W5Ng1MYhIxgXYwnjEqbD9If_8vyw.MQ==";

                    $finalLink = "https://rsdgunungjati.com/service/dashboard/registrasi/cetak-surat-keterangan-dokter?&noregistrasi={$datapasien->noregistrasi}&dokter={$datapasien->dokter}&kelompokpasien={$datapasien->kelompokpasien}&objectdepartemenfk=9&tglregistrasi={$datapasien->tglregistrasi}&norec_pd={$datapasien->norec}&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3NDIyNjMxMDl9.xwQJ8PJjpHmkv993zK_yspbdjd1MbUj8EtknZI8LeVaM6ubla1Pk_WDBlW7KAi_aq6BBhVBQrSrbe0NpMRjOpg.MQ==";

                    $finalLinkspasi = str_replace(' ', '%20', $finalLink);

                    $pesan = "Salam hormat, \n
Berikut kami sampaikan Surat Keterangan Dokter tanggal {$date}, a/n pasien {$patientName} ({$datapasien->patient_cm}) dapat dilihat dengan cara klik link berikut ini :
\n ";
$pesan .= "{$finalLinkspasi}";
$pesan .= "\n

\n \n
Terima kasih, \n
*RSD Gunung Jati Kota Cirebon*";

                    $convertPhoneNumber = function ($phone) {
                        if (strpos($phone, "0") === 0) {
                            return "62" . substr($phone, 1);
                        }
                        return $phone;
                    };

                    $phone = $datapasien->nohp;
                    // $phone = '085295611136';
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



}
