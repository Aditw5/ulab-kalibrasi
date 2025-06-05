<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;

use App\Models\Master\Ruangan;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProfilePasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function headerPasien(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->LeftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('agama_m as agm', 'ps.objectagamafk', '=', 'agm.id')
            ->leftJoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftJoin('pendidikan_m as pe', 'ps.objectpendidikanfk', '=', 'pe.id')
            ->leftJoin('suku_m as su', 'ps.objectsukufk', '=', 'su.id')
            ->leftJoin('catatandokter_t as ct', 'ct.nocmfk', '=', 'ps.id')
            ->leftJoin('catatandokter_t as latest_ct', function ($join) {
                $join->on('ct.nocmfk', '=', 'latest_ct.nocmfk')
                    ->on('ct.tanggal', '<', 'latest_ct.tanggal');
            })
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('statusperkawinan_m as sp', 'sp.id', 'ps.objectstatusperkawinanfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'su.suku',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email',
                'agm.agama',
                'pe.pendidikan',
                'pk.pekerjaan',
                'ps.isfoto',
                'ps.filename',
                'ps.namasuamiistri',
                'ps.penanggungjawab',
                'sp.statusperkawinan',
                'ct.catatan',
                'latest_ct.catatan as catatan_terbaru',
                'ps.sitb_id'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();

        $alamat = DB::table('pasien_m as ps')
            ->select('ds.namadesakelurahan', 'kec.namakecamatan', 'alm.rtrw', 'kab.namakotakabupaten')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
            ->leftJoin('kecamatan_m as kec', 'kec.id', '=', 'alm.objectkecamatanfk')
            ->leftJoin('kotakabupaten_m as kab', 'kab.id', '=', 'alm.objectkotakabupatenfk')
            ->union(
                DB::table('pasien_m as ps')
                    ->select('ds.namadesakelurahan', 'kec.namakecamatan', 'alm.rtrw', 'kab.namakotakabupaten')
                    ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                    ->rightJoin('desakelurahan_m as ds', 'ds.id', '=', 'alm.objectdesakelurahanfk')
                    ->rightJoin('kecamatan_m as kec', 'kec.id', '=', 'alm.objectkecamatanfk')
                    ->rightJoin('kotakabupaten_m as kab', 'kab.id', '=', 'alm.objectkotakabupatenfk')
                    ->where('ps.kdprofile', (int)$this->kdProfile)
                    ->where('ps.statusenabled', true)
                    ->where('ps.id', $r['nocmfk'])
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->get();

        // return $alamat;

        $registrasi = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
                // $join->limit))
            })
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->LEFTJOIN('jenispelayanan_m as jp', 'jp.id', '=', 'pd.jenispelayanan')
            // ->leftjoin('strukorder')
            ->select(
                'pd.noregistrasi',
                'pd.norec',
                'pd.isclosing',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'asru.asalrujukan',
                'ru.namaruangan',
                'kl.namakelas',
                'pd.nocmfk',
                'rk.namarekanan',
                'pg.namalengkap as dokter',
                'pg2.namalengkap as dokterBersama',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'apd.norec as norec_apd',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan',
                'pd.jenispelayanan as jenispelayananfk',
                'pd.objectkelasfk',
                'ru.objectdepartemenfk',
                'pd.objectpegawaifk',
                'pa.nosep',
                'pd.inacbg_totalgrouper',
                'pd.inacbg_topup',
                'ru.kdsubspesialisbpjs',
                'ru.namasubspesialisbpjs',
                'pa.dpjplayan_kode',
                'pa.dpjplayan_nama',
                'pd.tinggibadan',
                'pd.beratbadan',
                'pd.suhu',
                'pd.nadi',
                'pd.pernafasan',
                'pd.tekanandarah',
                'pd.spo2',
                'jp.jenispelayanan as jenispelayananBaru',
                'pd.isberkas',
                'pd.isresumemedis'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk']);

        if (isset($r['dari']) && $r['dari']) {
            $registrasi =  $registrasi->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai']) {
            $registrasi =  $registrasi->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }

        $jmlRegis = $registrasi->count();
        if (isset($r['limit']) && $r['limit'] != '') {
            $registrasi = $registrasi->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $registrasi = $registrasi->offset($r['offset']);
        }
        $registrasi =  $registrasi->orderByDesc('pd.tglregistrasi');
        $registrasi =  $registrasi->orderByDesc('dp.namadepartemen');
        // ->limit(10)
        $registrasi =  $registrasi->get();
        $apd = null;
        if (isset($r['norec_apd']) && $r['norec_apd'] != 'undefined' &&  $r['norec_apd'] != '') {
            $apd = DB::table('antrianpasiendiperiksa_t as apd')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
                ->select(
                    'apd.norec as norec_apd',
                    'apd.objectkelasfk',
                    'apd.objectruanganfk',
                    'ru.namaruangan',
                    'kl.namakelas',
                    'apd.noregistrasifk',
                    'apd.tglmasuk as tglregistrasi'
                )
                ->where('apd.kdprofile', (int)$this->kdProfile)
                ->where('apd.statusenabled', true)
                ->where('apd.norec',  $r['norec_apd'])
                ->first();
        }
        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', 'ddp.noregistrasi')
            ->leftjoin('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            // ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'dg.kddiagnosa',
                'dg.id',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                // 'jd.jenisdiagnosa',
                'ddp.keterangan',
                'ddp.noregistrasi',
                'ddp.objectjenisdiagnosafk',
                DB::raw("case when ddp.iskasusbaru is not null then 'Baru'
            when ddp.iskasuslama is not null then 'Lama'
            else ''
            end as kasus  ")
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.statusenabled', true)
            ->whereIn('ddp.objectjenisdiagnosafk', explode(',', $this->settingFix('kdDiagnosaUtama')));
        if (isset($r['dari']) && $r['dari']) {
            $diagnosaX =  $diagnosaX->where('ddp.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai']) {
            $diagnosaX =  $diagnosaX->where('ddp.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $diagnosaX = $diagnosaX->limit($r['limit']);
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $diagnosaX = $diagnosaX->offset($r['offset']);
        }
        $diagnosaX = $diagnosaX->orderByDesc('ddp.tglinputdiagnosa');
        $diagnosaX = $diagnosaX->get();

        $laboratRad = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')

            ->select(
                'so.norec',
                'so.noregistrasifk',
                'so.keteranganorder'
            )
            ->where('so.kdprofile', (int)$this->kdProfile)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('so.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('so.tglorder')
            ->get();


        foreach ($registrasi as $d) {
            $dokterTambahan = DB::table('rawatbersama_t as rb')
            ->join('pegawai_m as pg', 'rb.objectpegawaifk', 'pg.id')
            ->select('pg.namalengkap')
            ->where('noregistrasifk', $d->norec_pd)
            ->get();
            $d->dokterTambahan = $dokterTambahan;
            $d->apd = null;
            if (!empty($apd)) {
                if ($d->norec == $apd->noregistrasifk) {
                    $d->apd = $apd;
                }
            }
            $d->billing = 0;
            if (!empty($data)) {
                $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
            }
            $d->diagnosis = [];
            foreach ($diagnosaX as $dd) {
                if ($d->noregistrasi == $dd->noregistrasi) {
                    $d->diagnosis[]    = $dd;
                }
            }
            $d->laboratorium = [];
            $d->radiologi = [];
            foreach ($laboratRad as $ss) {
                if ($d->norec == $ss->noregistrasifk) {

                    if ($ss->keteranganorder == 'Order Radiologi') {
                        $d->radiologi[]    = $ss;
                    }
                    if ($ss->keteranganorder == 'Order Laboratorium') {
                        $d->laboratorium[]    = $ss;
                    }
                }
            }
        }
        $totalBiaya = 0;
        if (count($registrasi) > 0) {
            // $totalBiaya =  PelayananPasien::totalTagihan($registrasi[0]->noregistrasi);
            $registrasi[0]->billing = $totalBiaya;
        }
        if ($data->isfoto) {
            $path = 'foto_pasien/' . $data->nocmfk . '/' . $data->filename;
            $disk = Storage::disk('public');


            if (!$disk->exists($path)) {
                $data->foto = null;
            } else {
                $file = $disk->read($path);

                $b64Doc = chunk_split(base64_encode($file));
                $sourcePdf = "data:application/pdf;base64," . $b64Doc;

                $data->foto  =  $sourcePdf;
            }
        }
        $data->isFilterProdukLab = $this->settingFix('isFilterProdukLab');
        $result['pasien'] = $data;
        $result['alamat'] = $alamat;
        $result['registrasi'] = $registrasi;
        $result['count_registrasi'] = $jmlRegis;
        $result['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['pasien']->enabledEMRSimrsLama = $this->settingFix('enabledEMRSimrsLama');
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function countDialisis(Request $request)
    {
        $result = DB::table('pasiendaftar_t as pd')
                ->select(
                    DB::raw('count(norec)')
                )
                ->where('pd.nocmfk', $request->nocmfk)
                ->where('pd.objectruanganlastfk', 220)
                ->where('pd.statusenabled', true)
                ->first();
        return $this->respond($result);
    }

    public function curlSendLis($url, $header, $method, $dataJson) {
        $curl = curl_init();

        if ($dataJson != null) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataJson,
                CURLOPT_HTTPHEADER => $header
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $header,
            ));
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    public function generateHeaderPromLis($xid, $xkey) {
        $x_id = $xid;  // X-id value
        $x_key = $xkey;  // Your secret key (X-key)
        $x_timestamp = strval(time()-strtotime('1970-01-01 00:00:00')); // X-timestamp value (UNIX timestamp)

        // Combine the X-id and X-timestamp into a single string (the format depends on API requirements)
        $data_to_sign = $x_id."&".$x_timestamp;  // Assuming colon (:) separator

        // Generate the HMAC-SHA256 hash and encode it in base64
        $signature = base64_encode(hash_hmac('sha256', $data_to_sign, $x_key, true));

        $result = array(
            'X-id:' . $x_id,
            'X-timestamp:' . $x_timestamp,
            'X-signature:' . $signature,
            'Content-Type:application/json'
        );

        return $result;
    }

    public function detailPelayanan(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $status = true;
        $paramsLimit = 1000;
        $statusPasien =  DB::table('pasiendaftar_t as pd')
            ->leftJoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftJoin('kondisipasien_m as kp', 'kp.id', '=', 'pd.objectkondisipasienfk')
            ->select(
                'pd.alergi',
                'pd.statuspasien',
                'sp.statuspulang',
                'kp.kondisipasien',
                'pd.noregistrasi',
                'pd.tglpulang',
                DB::raw("(pd.tglpulang::date - pd.tglregistrasi::date)  + 1 || ' hari' AS lamarawat")
            )
            ->where('pd.norec', $r['norec_pd'])
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $vitalSign = DB::connection('mongodb')
            ->table('VitalSign')
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->first();

        $orderBedah = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', 'so.noregistrasifk')
            ->join('ruangan_m as ruTu', 'so.objectruangantujuanfk', 'ruTu.id')
            ->join('pegawai_m as peg', 'peg.id', 'so.objectpegawaiorderfk')
            ->join('departemen_m as dep2', 'dep2.id', 'ruTu.objectdepartemenfk')

            ->where('so.kdprofile', $this->kdProfile)
            ->where('pd.norec', $r['norec_pd'])
            ->whereIn('dep2.id', explode(',', $this->settingFix('idDepartemenBedah')))
            ->where('so.statusenabled', true)
            ->count();

        $skdp = DB::table('pasienperjanjian_t as rm')
                ->select(
                    'rm.norec',
                    'rm.objectdokterfk',
                    'pg.namalengkap',
                    'rm.jumlahkujungan',
                    'rm.keterangan',
                    'rm.objectpasienfk',
                    'rm.tglinput',
                    'rm.tglperjanjian',
                    'st.tglkontrol AS tglperjanjian',
                    'rm.noperjanjian',
                    'rm.objectruanganfk',
                    'ru.namaruangan',
                    'ru.kdinternal',
                    'pg.kddokterbpjs',
                    'ps.nocm',
                    'pd.noregistrasi',
                    'ps.namapasien',
                    'st.diagnosa',
                    'st.indikasi',
                    'rm.objectsuratfk'
                )
                ->leftJoin('pasien_m as ps', 'rm.objectpasienfk', '=', 'ps.id')
                ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'rm.objectdokterfk')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'rm.objectruanganfk')
                ->leftJoin('suratketerangan_t as st', 'st.norec', '=', 'rm.objectsuratfk')
                ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'st.pasiendaftarfk')
                ->where('rm.kdprofile', $this->kdProfile)
                ->where('pd.norec', $r['norec_pd'])
                ->where('rm.statusenabled', true)
                ->get();

        $tindakanResep = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            // ->leftJOIN('strukresep_t as sr', function ($join) {
            //     $join->on('sr.norec', '=', 'pp.strukresepfk')
            //         ->whereNull('sr.orderfk');
            // })
            // ->leftJOIN('strukorder_t as so', function ($join) {
            //     $join->on('so.norec', '=', 'pp.strukorderfk')
            //         ->whereNull('pp.strukorderfk');
            // })
            ->leftJOIN('strukresep_t as sr', function ($join) {
                    $join->on('sr.norec', '=', 'pp.strukresepfk');
                })
            ->leftJOIN('strukorder_t AS so', 'so.norec', '=', 'sr.orderfk')
            ->leftJOIN ('pegawai_m AS peg',  'peg.id','=' , 'so.objectpegawaiorderfk')
            ->leftJOIN ('pegawai_m AS peg1',  'peg1.id','=' , 'so.objectpetugasfk')
            ->select(
                'prd.namaproduk',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'peg.namalengkap as dokterpengorder',
                'peg1.namalengkap as pegawaipengorder',
                DB::raw("

                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)

                 as total
                 ,'Selesai' as status")
            )
            ->whereNull('pp.strukorderfk')
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('pp.tglpelayanan')
            ->limit($paramsLimit)
            ->get();

        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
        $orderResep = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
            ->join('produk_m as prd', 'prd.id', '=', 'op.objectprodukfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
            ->leftJOIN ('pegawai_m AS peg',  'peg.id','=' , 'so.objectpegawaiorderfk')
            ->leftJOIN ('pegawai_m AS peg1',  'peg1.id','=' , 'so.objectpetugasfk')
            ->select(
                'prd.namaproduk',
                'so.tglorder as tglpelayanan',
                'ru.namaruangan',
                'so.norec as strukresepfk',
                'op.jumlah',
                'peg.namalengkap as dokterpengorder',
                'peg1.namalengkap as pegawaipengorder',
                DB::raw("

                (
                    (op.hargasatuan  - case when op.hargadiscount is null then 0 else op.hargadiscount end)
                     * op.jumlah)

                 as total
                 ,'Pending' as status")
            )
            ->where('so.statusenabled', true)
            ->where('so.kdprofile', $kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd'])
            ->where('so.objectkelompoktransaksifk',  $set->objectkelompoktransaksifk)
            ->where('so.statusorder', $this->settingFix('statusMenungguApotik'))
            ->orderByDesc('so.tglorder')
            ->limit($paramsLimit)
            ->get();

        $konsul = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            // ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->select(
                'so.norec',
                'so.konsultasi',
                'so.rawatbersama',
                'so.tglorder',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'ru.namaruangan as ruanganasal',
                'pg.id as pegawaifk',
                'pg.namalengkap',
                'pd.objectkelasfk as kelasfk_pd',
                'apd.objectkelasfk as kelasfk_apd',
                // 'kls.namakelas',
                'rutuju.namaruangan as ruangantujuan',
                'pet.namalengkap as pengonsul',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'so.keteranganlainnya',
                DB::raw("case when so.keteranganlainnya is not null
                then 'Selesai' else 'Menunggu Jawaban' end
                as status")
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd'])
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->orderBy('so.tglorder', 'desc')
            ->get();

        $berkas = DB::table('emrdokumen_t as emrdk')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', 'emrdk.noregistrasi')
            ->select('emrdk.*')
            ->where('emrdk.statusenabled', true)
            ->where('emrdk.kdprofile', $this->kdProfile)
            ->where('pd.norec', $r['norec_pd'])
            ->get();


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
            ->where('ddp.kdprofile', $kdProfile)
            ->where('ddp.statusenabled', true)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('ddp.tglinputdiagnosa')
            ->limit($paramsLimit)
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
            ->where('ddt.kdprofile', $kdProfile)
            ->where('ddt.statusenabled', true)
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->orderByDesc('ddt.tglinputdiagnosa')
            ->limit($paramsLimit)
            ->get();
        $depLab = $this->settingFix('idDepartemenLab');
        $depRad = $this->settingFix('idDepartemenRadiologi');
        $depBedah = $this->settingFix('idDepartemenBedah');
        $expertise = DB::table('hasilradiologi_t as hh')
            ->join('pelayananpasien_t as pp', function ($j) {
                $j->on('pp.norec', '=', 'hh.pelayananpasienfk')->where('pp.statusenabled', true);
            })
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hh.noregistrasifk')
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->where('hh.statusenabled', true)
            ->select('pp.strukorderfk', 'hh.keterangan as expertise', 'pp.norec as norec_pp', 'hh.norec as norec_exper');

        $laboratRad = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'op.noorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJoinSub($expertise, 'hh', 'so.norec', '=', 'hh.strukorderfk')
            ->leftjoin('ris_order as ris', function ($j) {
                $j->on('ris.order_no', '=', 'so.noorder')->on(DB::raw("cast(op.objectprodukfk as text)"), '=', 'ris.order_code');
            })
            // ->leftjoin('hasilradiologi_t as hh', 'hh.pelayananpasienfk', '=', 'so.objectruangantujuanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpetugasfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'op.norec as norec_op',
                'pr.namaproduk',
                'pg.namalengkap',
                'ru.objectdepartemenfk',
                'so.tglpelayananawal as tgloperasi',
                'so.estimasiwaktuoperasi',
                'so.keteranganlainnya',
                'pg.namalengkap as dokterpengorder',
                'pg2.namalengkap as pegawaipengorder',
                DB::raw(" case when so.statusorder = 1 AND hh.expertise is null then 'Order sudah diverifikasi'
                when so.statusorder = 1 AND hh.expertise is not null then 'Selesai'
                when so.statusorder = 1 then 'Order sudah diverifikasi'
                when so.statusorder = 2 then 'Belum ada hasil'
                when so.statusorder = 2 then 'Selesai'
                when so.statusorder = 7 then 'Ditunda'
                else 'Pending (order belum diverifikasi)' end as status,
                case when so.statusorder = 1 then 'info'
                when so.statusorder = 2 then 'succes'
                when so.statusorder = 7 then 'danger'
                else 'warning' end as color_status,hh.expertise,hh.norec_pp,hh.norec_exper,
                so.objectruangantujuanfk,
                ris.patient_id || '-' || ris.order_cnt as radiologiid,
                ris.order_complete,ris.accession_num,
                op.objectprodukfk")

            )
            ->where('so.kdprofile', $kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd'])
            ->whereIn('ru.objectdepartemenfk', [$depLab, $depRad, $depBedah])
            ->where('so.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('so.tglorder')
            ->limit($paramsLimit)
            ->get();

        //  $hasil_LAB = collect(DB::select("select
        //     res.his_reg_no AS visit_trans_id,  res.lis_test_id AS tarif_id,
        //     res.test_name AS examination_name, res.result AS result_value,  res.test_units_name AS unit,
        //     res.reference_value AS normal_value,  res.result_comment AS metode,   res.test_group AS treatment_name,
        //     res.sequence AS urut, orz.patient_id AS rm_number,  res.authorization_date AS visit_date,    res.his_test_id,
        //     res.test_name AS tarif_name,  res.test_flag_sign AS flag ,res.authorization_user as analis
        //     from result_bridge as res
        //     join order_bridge as orz on orz.order_number=res.his_reg_no
        //     join pasiendaftar_t as pd on pd.noregistrasi=orz.visit_number
        //     where pd.norec = '$r[norec_pd]'
        //     "))->toArray();

        $hasil_VansLAB = $this->hasilLab($r['norec_pd'])['hasil_VansLAB'];

        $hasil_LAB_MANUAL = $this->hasilLab($r['norec_pd'])['hasil_LAB_MANUAL'];

        $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
        $xid = $this->settingFix('xidPromlis', $kdProfile);
        $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
        $header = $this->generateHeaderPromLis($xid, $xkey);
        $arrNoorder = explode(',', $r['noorder']);
        $arrNoorderUniq = array_unique($arrNoorder);

        $strukOrder = DB::table('strukorder_t')
                        ->where('noregistrasifk', $r['norec_pd'])
                        ->where('objectkelompoktransaksifk', 93)
                        ->where('statusenabled', true)
                        ->get();

        $dataPasien = DB::table('pasien_m')->where('id', $r['nocmfk'])->first();


        $hasilLab = [];
        $hasilMikroSharing = [];
        foreach ($strukOrder as $value) {
            // $url = $baseUrl . 'get_result?OrderNumber=' . $value->noorder . '&PatientCode=' . $dataPasien->nocm;
            // $method = 'GET';
            // $response = $this->curlSendLis($url, $header, $method, null);
            // // Log::info($response->message);
            // if (isset($response) && $response->status == 200) {
            //     $hasilLab[] = $response->data;
            // }else{
                $hasilSharing = DB::table('RESULT_HEADER')
                    ->select(
                        'CATATAN as ConclusionText',
                        'DIAGNOSA as DiagnosaName',
                        'KODERUANGTUJUAN as KodeRuangTujuan',
                        'NAMARUANGTUJUAN as NamaRuangTujuan',
                        'ORDERTIME as OrderDateTime',
                        'HISREGNO as OrderNumber',
                        'LISREGNO as OrderNumberLIS',
                        'PATID as PatientCode',
                        'PATNAME as PatientName',
                        'REGNUMBER as RegNumber',
                    )
                    ->where('HISREGNO', $value->noorder)
                    ->first();

                $hasilMikroSharing = DB::table('MICRO_ORGANISM as mo')
                        ->select(
                            'mo.*',
                            'so.noorder as OrderNumber',
                            'op.objectprodukfk as OrderTestId',
                        )
                        ->join('strukorder_t as so', 'so.noorder', 'mo.noorder')
                        ->join('orderpelayanan_t as op', 'op.noorderfk', 'so.norec')
                        ->where('mo.noorder', $value->noorder)
                        ->first();

                if ($hasilSharing) {
                    // Pastikan hasilSharing adalah objek dan tambahkan properti ResultList
                    $hasilSharing->ResultList = DB::table('RESULT_DATA')
                        ->select(
                            'test_flag_critical as CriticalFlag',
                            'test_flag_sign as Flag',
                            'test_method as Method',
                            'sequence as DispSequence',
                            'his_test_order as OrderTestId',
                            'test_name as TestName',
                            'greaterthan_value as CriticalValueMax',
                            'lessthan_value as CriticalValueMin',
                            'reference_value as RefRange',
                            'result as ResultValue',
                            'lis_test_id as TestCode',
                            'result_comment as TestComment',
                            'test_units_name as Unit',
                            'authorization_user as ValidateBy',
                            'authorization_date as ValidateOn',
                        )
                        ->where('his_reg_no', $value->noorder)
                        ->orderBy('sequence')
                        ->get();
                    $hasilSharing->isMikro = false;
                }

                if($hasilMikroSharing){
                    $hasilMikroSharing->ResultList = DB::table('MICRO_ANTIBIOTIC')
                                                    ->where('nolab', $hasilMikroSharing->nolab)
                                                    ->get();
                    $hasilMikroSharing->isMikro = true;
                }

                if (isset($hasilSharing->ResultList) && $hasilSharing->ResultList->isNotEmpty()) {
                    $hasilLab[] = $hasilSharing;
                }
                if (isset($hasilMikroSharing->ResultList) && $hasilMikroSharing->ResultList->isNotEmpty()) {
                    $hasilLab[] = $hasilMikroSharing;
                }
            // }
        }



        $asesmenAwal = DB::table('emrpasien_t as emrp')
            ->where('emrp.statusenabled', true)
            ->where('emrp.kdprofile', $this->kdProfile)
            ->where('emrp.noregistrasifk', $r['norec_pd'])
            ->where('emrp.jenisemr', '=', 'asesmenawal')
            ->orderByDesc('emrp.tglemr')
            ->first();

        // $EMR_ = DB::table('emrpasien_t as emrp')
        //     ->where('emrp.statusenabled', true)
        //     ->where('emrp.kdprofile', $this->kdProfile)
        //     ->where('emrp.noregistrasifk', $r['norec_pd'])
        //     ->where('emrp.jenisemr', '=', 'asesmen_medis')
        //     ->get();


        // $EMR_FORM = DB::table('emrpasienform_t as emrp')
        //     ->where('emrp.statusenabled', true)
        //     ->where('emrp.kdprofile', $this->kdProfile)
        //     ->where('emrp.noregistrasifk', $r['norec_pd'])
        //     ->where('emrp.table', '!=', 'VitalSign')
        //     ->get();
        $EMR_FORM = DB::connection('mongodb')
            ->table('#ResumeEMR')
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasifk', $r['norec_pd'])
            ->where('table', '!=', 'VitalSign')
            ->where('table', '!=', 'AsesmenAwal')
            ->where('statusenabled', true)
            ->orderByDesc('last_update')
            ->get();
        $EMR_FORM_ =  DB::connection('mongodb')
            ->table('#ResumeEMR')
            ->where('kdprofile', $this->kdProfile)
            ->where('noregistrasifk', $r['norec_pd'])
            ->where('table', 'VitalSign')
            ->where('statusenabled', true)
            ->limit(1)
            ->orderByDesc('last_update')
            ->get();

        $emr = array_merge($EMR_FORM->toArray(), $EMR_FORM_->toArray());
        $tindakan = [];
        $resep = [];
        $laborat = [];
        $radiologi = [];
        $bedah = [];

        // $emr = [];
        $diagnosa = array_merge($diagnosaX->toArray(), $diagnosaIX->toArray());
        foreach ($tindakanResep as $items) {
            if ($items->strukresepfk == null) {
                $tindakan[] = $items;
            } else {
                $resep[] = $items;
            }
        }
        foreach ($orderResep as $items) {
            $resep[] = $items;
        }

        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($laboratRad as $items) {
            $items->url_pacs_hasil = null;
            $exp = explode(',', $urlPACSHasil);
            $exp[0] = $exp[0] . $items->accession_num;
            $exp[1] = $exp[1] . $items->accession_num;
            $urlPACSHasil = implode(",", $exp);
            $items->url_pacs_hasil = $urlPACSHasil;
            if ($items->order_complete == 1) {
                $items->status = 'selesai';
                $items->color_status = 'success';
            }

            $items->hasil_lab = [];
            foreach ($hasil_VansLAB as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            foreach ($hasil_LAB_MANUAL as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            // return $hasilLab;
            foreach ($hasilLab as $hLabItems) {
                if($hLabItems->isMikro){
                    foreach($hLabItems->ResultList as $itemLab){
                        if($items->noorder == $hLabItems->OrderNumber && $items->objectprodukfk == $hLabItems->OrderTestId){
                            $itemLab->ismikro = true;
                            $items->ismikro = true;
                            $items->hasil_lab[] = $itemLab;
                        }
                    }
                }else{
                    foreach($hLabItems->ResultList as $itemLab){
                        if($items->noorder == $hLabItems->OrderNumber && ($items->objectprodukfk == $itemLab->OrderTestId)){
                            $items->ismikro = false;
                            $items->hasil_lab[] = $itemLab;
                        }
                    }
                }
            }
            // foreach ($hasilSharing as $hLabItems) {
            //     if($items->noorder == $hLabItems->his_reg_no && $items->objectprodukfk == $hLabItems->his_test_order){
            //         $items->hasil_lab[] = $hLabItems;
            //     }
            // }
            if ($items->objectdepartemenfk == $depLab) {
                $laborat[] = $items;
            }
            if ($items->objectdepartemenfk == $depRad) {
                $radiologi[] = $items;
            }
            if ($items->objectdepartemenfk == $depBedah) {
                $bedah[] = $items;
            }
        }

        foreach ($emr as $k => $items2) {
            $emr[$k]['icon'] = isset($items2['icon']) ?  $items2['icon'] : 'fas fa-laptop-medical';
        }

        if (count($tindakan) > 0) {
            $status = false;
        }
        if (count($resep) > 0) {
            $status = false;
        }
        if (count($diagnosa) > 0) {
            $status = false;
        }
        if (count($laborat) > 0) {
            $status = false;
        }
        if (count($radiologi) > 0) {
            $status = false;
        }
        if ($orderBedah > 0) {
            $status = false;
        }
        if (count($emr) > 0) {
            $status = false;
        }
        if (!empty($asesmenAwal)) {
            $status = false;
        }
        if (count($konsul) > 0) {
            $status = false;
        }
        $result['enabledEMRSimrsLama'] = $this->settingFix('enabledEMRSimrsLama');
        $result['vitalSign'] = $vitalSign != null ? true : false;
        $result['tindakan'] = $tindakan;
        $result['resep'] = $resep;
        $result['diagnosis'] = $diagnosa;
        $result['laboratorium'] = $laborat;
        $result['radiologi'] = $radiologi;
        $result['bedah'] = $bedah;
        $result['skdp'] = $skdp;
        $result['orderBedah'] = $orderBedah != null ? true : false;
        $result['konsul'] = $konsul;
        $result['asesmenawal'] = $asesmenAwal;
        $result['emr'] = $emr;
        $result['empty'] = $status;
        $result['statuspasien'] = $statusPasien;
        $result['berkas'] = $berkas;
        $result['as'] = '@epic';
        return $this->respond($result);
    }
    public function listPasienRJ(Request $r)
    {
        $data  = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->join('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->select(
                'apd.norec as norec_apd',
                'pd.norec as norec_pd',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ps.nocm',
                'pd.tglregistrasi',
                'ps.namapasien',
                'apd.noantrian',
                'apd.status',
                'ru.namaruangan',
                'ps.noidentitas',
                'ps.nobpjs',
                'kp.kelompokpasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')));

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where('apd.tglmasuk', '>=', $r['dari'] . ' 00:00');
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
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where('apd.tglmasuk', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['ruid']) && $r['ruid'] != '' && $r['ruid'] != 'undefined') {
            $data = $data->where('ru.id', '=',  $r['ruid']);
        }
        if (isset($r['dokid']) && $r['dokid'] != '' && $r['dokid'] != 'undefined') {
            $data = $data->where('apd.objectpegawaifk', '=',  $r['dokid']);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['status']) && $r['status'] != '') {
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        if (isset($r['statuspanggil']) && $r['statuspanggil'] != '' && $r['statuspanggil'] != 'undefined') {
            $data = $data->where('apd.status', '=',  $r['statuspanggil']);
        }
        if (isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $data = $data->where('pd.norec', '!=', $r['norec_pd']);
        }
        $data = $data->orderBy('apd.noantrian');
        $data = $data->get();
        $res['dipanggil'] = 0;
        $res['belumpanggil'] = 0;
        foreach ($data as $d) {
            if ($d->status == null || $d->status == 'Belum Dipanggil') {
                $res['belumpanggil'] = $res['belumpanggil'] + 1;
            } else {
                $res['dipanggil'] = $res['dipanggil'] + 1;
            }
        }
        $res['data'] = $data;
        $res['total'] = count($data);

        return $this->respond($res);
    }
    public function getTotalBilling(Request $r)
    {
        $totalBiaya =  PelayananPasien::totalTagihan($r['noregistrasi']);
        return $this->respond($totalBiaya);
    }
    public function infoPasien(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('agama_m as agm', 'ps.objectagamafk', '=', 'agm.id')
            ->leftJoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftJoin('pendidikan_m as pe', 'ps.objectpendidikanfk', '=', 'pe.id')
            ->leftJoin('suku_m as su', 'ps.objectsukufk', '=', 'su.id')
            ->leftjoin('golongandarah_m as gd', 'ps.objectgolongandarahfk', 'gd.id')
            ->leftjoin('statusperkawinan_m as spk', 'spk.id', 'ps.objectstatusperkawinanfk')
            // ->leftjoin('kebangsaan_m as kb', 'kb.id', '=', 'ps.objectkebangsaanfk')
            ->leftjoin('negara_m as ng', 'ng.id', '=', 'alm.objectnegarafk')
            ->leftjoin('desakelurahan_m as dsk', 'dsk.id', '=', 'alm.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kcm', 'kcm.id', '=', 'alm.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kkb', 'kkb.id', '=', 'alm.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as prp', 'prp.id', '=', 'alm.objectpropinsifk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.noidentitas',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'su.suku',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'alm.alamatlengkap',
                'alm.kodepos',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email',
                'agm.agama',
                'pe.pendidikan',
                'pk.pekerjaan',
                'ps.isfoto',
                'ps.filename',
                'gd.golongandarah',
                'spk.statusperkawinan',
                'ng.namanegara',
                'dsk.namadesakelurahan',
                'kcm.namakecamatan',
                'kkb.namakotakabupaten',
                'prp.namapropinsi',
                // 'kb.kebangsaan',
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        return $this->respond($data);
    }

    public function simpanAlergiPasien(Request $request)
    {

        try {
            DB::beginTransaction();
            PasienDaftar::where('norec', $request['norec_pd'])->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['alergi' => $request['alergi']]);

            DB::commit();
            $response = [
                'data' => $request['alergi'],
                'status' => 200,
                'message' => 'Simpan Alergi Pasien Berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'data' => $e->getMessage(),
                'status' => 400,
                'message' => 'Simpan Alergi Pasien Gagal'
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function changeClosing(Request $request)
    {

        PasienDaftar::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
            ->where('norec', $request['norec_pd'])
            ->update(
                ['isclosing' => $request['closing']]
            );

        $status = $request['closing'] == true ? 'Pasien Berhasil diclosing' : 'Batal Closing Berhasil';
        return $this->respond('Berhasil', 200, $status);
    }
    public function detailPelayananSIMRSLama(Request $r)
    {
        $set =  explode(',', $this->settingFix('urlEMRSimrsLama'));
        $prefix = $set[1];
        if ($r['local'] == 'true') {
            $prefix = $set[0];
        }
        // return $prefix;
        $url1 = $prefix . 'apiriwayat/riwayat/' . $r['prefix'];
        // $url2 = $prefix .'rme/riwayat/diagnosa';
        // $url3 = $prefix .'rme/riwayat/catatan_dokter';
        // $url4 = $prefix .'rme/riwayat/rujukan_penunjang';
        // $url5 = $prefix .'rme/riwayat/rujukan_nonpenunjang';
        // $url6 = $prefix .'rme/riwayat/order_resep';
        $json = array(
            "nocm" => $r['nocm']
        );

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            "username" => "userapi",
            "password" => "api2023"
        ])
            ->withoutVerifying()
            ->withOptions(["verify" => false])
            ->post($url1, $json);

        $res = $response->json();
        // return $response;

        $resp = isset($res['response']['message']) && $res['response']['message'] == 'true' ? $res['metadata']['data'] : [];
        return $this->respond($resp);
    }

    public function saveCatatanDokter(Request $request)
    {
        try {
            $pegawai = LoginUser::where('id', $request->userData['id'])->first();
            DB::beginTransaction();
            $data = [
                'norec' => $this->Uuid4(),
                'kdprofile' => $this->kdProfile,
                'statusenabled' => true,
                'catatan' => $request->catatan,
                'tanggal' => date($request->tanggal),
                'nocmfk' => $request->nocmfk,
                'noregistrasifk' => $request->noregistrasifk,
                'pegawaifk' => $pegawai->objectpegawaifk
            ];
            DB::table('catatandokter_t')->insert($data);
            DB::commit();
            $response = [
                'data' => $data,
                'status' => 200,
                'message' => 'Simpan catatan dokter  Berhasil'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                'data' => $e->getMessage(),
                'status' => 400,
                'message' => 'Simpan Catatan Dokter Gagal'
            ];
        }
        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function getCatatanDokter(Request $request)
    {
        $data = DB::table('catatandokter_t as ct')->where('ct.nocmfk', $request->nocmfk)
            ->join('pegawai_m as p', 'p.id', 'ct.pegawaifk')
            ->select(
                'ct.catatan',
                'ct.norec',
                'ct.tanggal',
                'p.namalengkap as dokter'
            )->orderBy('ct.tanggal', 'DESC')
            ->limit(100)
            ->get();
        return $this->respond($data);
    }
    public function hasilLab($norec)
    {

        $res['hasil_VansLAB'] = collect(DB::select("select
            res.no_order AS visit_trans_id, res.kode_pemeriksaan AS tarif_id,
            res.nama_pemeriksaan AS examination_name, res.hasil AS result_value, res.unit,
            res.normal AS normal_value, res.metode, res.nama_alat AS treatment_name,
            res.no_urut AS urut, res.no_rm AS rm_number,  res.tgl_hasil AS visit_date, res.kode_sir as his_test_id,
            res.nama_pemeriksaan AS tarif_name, res.flag, res.user_validasi as analis
            from lab_hasil as res
            join pasiendaftar_t as pd on pd.noregistrasi=res.no_registrasi
            where pd.norec = '$norec'
            "))->toArray();


        $res['hasil_LAB_MANUAL']  = collect(DB::select(
            "select case when so.noorder is null then  pd.noregistrasi  else so.noorder end as visit_trans_id,
                res.produkfk AS tarif_id,
                pr.namaproduk AS examination_name, res.hasil AS result_value,  res.satuan AS unit,
                res.nilainormal AS normal_value,  res.metode AS metode,   res.group AS treatment_name,
                null AS urut,  ps.nocm AS rm_number,  res.tglhasil AS visit_date,    res.produkfk as his_test_id,
                pr.namaproduk AS tarif_name,  res.flag,pg.namalengkap as analis
                from hasillaboratorium_t as res
                join produk_m as pr on pr.id=  res.produkfk
                join antrianpasiendiperiksa_t as apd on  apd.norec= res.noregistrasifk
                join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
                left join strukorder_t as so on so.noregistrasifk=pd.norec
                join pasien_m as ps on ps.id=pd.nocmfk
                left join pegawai_m as pg on cast(pg.id as text)=res.pegawaifk
                where pd.norec= '$norec'"
        ))->toArray();

        return $res;
    }
    public function getHasilLab(Request $r)
    {
        $hasil_VansLAB = $this->hasilLab($r['norec_pd'])['hasil_VansLAB'];

        $hasil_LAB_MANUAL = $this->hasilLab($r['norec_pd'])['hasil_LAB_MANUAL'];

        $data = '';
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        $depLab = $this->settingFix('idDepartemenLab');
        $depRad = $this->settingFix('idDepartemenRadiologi');
        $depBedah = $this->settingFix('idDepartemenBedah');
        $expertise = DB::table('hasilradiologi_t as hh')
            ->join('pelayananpasien_t as pp', function ($j) {
                $j->on('pp.norec', '=', 'hh.pelayananpasienfk')->where('pp.statusenabled', true);
            })
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'hh.noregistrasifk')
            ->where('apd.noregistrasifk', $r['norec_pd'])
            ->where('hh.statusenabled', true)
            ->select('pp.strukorderfk', 'hh.keterangan as expertise', 'pp.norec as norec_pp');

        $laboratRad = DB::table('orderpelayanan_t as op')
            ->join('strukorder_t as so', 'op.noorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJoinSub($expertise, 'hh', 'so.norec', '=', 'hh.strukorderfk')
            ->leftjoin('ris_order as ris', function ($j) {
                $j->on('ris.order_no', '=', 'so.noorder')->on(DB::raw("cast(op.objectprodukfk as text)"), '=', 'ris.order_code');
            })
            // ->leftjoin('hasilradiologi_t as hh', 'hh.pelayananpasienfk', '=', 'so.objectruangantujuanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'op.norec as norec_op',
                'pr.namaproduk',
                'pg.namalengkap',
                'ru.objectdepartemenfk',
                'so.tglpelayananawal as tgloperasi',
                'so.estimasiwaktuoperasi',
                'so.keteranganlainnya',
                DB::raw(" case when so.statusorder = 1 then 'verifikasi'
            when so.statusorder = 2 then 'selesai'
            else 'pending' end as status,
            case when so.statusorder = 1 then 'danger'
            when so.statusorder = 2 then 'green'
            else 'orange' end as color_status,hh.expertise,hh.norec_pp,
            so.objectruangantujuanfk,
            ris.patient_id || '-' || ris.order_cnt as radiologiid,
            ris.order_complete,ris.accession_num,
            op.objectprodukfk")

            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.noregistrasifk', $r['norec_pd']);
        if (isset($r['islab']) && $r['islab'] == 'true') {
            $laboratRad = $laboratRad->whereIn('ru.objectdepartemenfk', [$depLab]);
        }
        if (isset($r['israd']) && $r['israd'] == 'true') {
            $laboratRad = $laboratRad->whereIn('ru.objectdepartemenfk', [$depRad]);
        }

        $laboratRad = $laboratRad->where('so.statusenabled', true);
        $laboratRad = $laboratRad->orderByDesc('so.tglorder');
        $laboratRad = $laboratRad->get();
        $laborat = [];
        $radiologi = [];
        foreach ($laboratRad as $items) {
            $items->url_pacs_hasil = null;
            if ($items->order_complete == 1) {
                $exp = explode(',', $urlPACSHasil);
                $exp[0] = $exp[0] . $items->accession_num;
                $exp[1] = $exp[1] . $items->accession_num;
                $urlPACSHasil = implode(",", $exp);
                $items->url_pacs_hasil = $urlPACSHasil;
                $items->status = 'selesai';
                $items->color_status = 'success';
            }

            $items->hasil_lab = [];
            foreach ($hasil_VansLAB as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            foreach ($hasil_LAB_MANUAL as $hLab) {
                if ($hLab->visit_trans_id == $items->noorder && $hLab->his_test_id == $items->objectprodukfk) {
                    $items->hasil_lab[] = $hLab;
                }
            }
            if ($items->objectdepartemenfk == $depLab) {
                $laborat[] = $items;
            }
            if ($items->objectdepartemenfk == $depRad) {
                $radiologi[] = $items;
            }
        }
        $result['laboratorium'] = $laborat;
        $result['radiologi'] = $radiologi;
        return $this->respond($result);
    }
    public function hasilLabPA(Request $r)
    {
        $data = DB::table('hasilpemeriksaanlab_t as ar')
            ->join('pelayananpasien_t as pp', 'pp.norec', '=', 'ar.pelayananpasienfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'ar.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->select(
                'pr.namaproduk',
                'ar.tanggal as tanggalpemeriksaan',
                'ar.diagnosaklinik as diagnosa',
                'ar.diagnosaklinik',
                'ar.morfologi',
                'ar.diagnosapb',
                'ar.jenis',
                'ar.makroskopik',
                'ar.mikroskopik',
                'ar.kesimpulan',
                'ar.anjuran',

            )
            ->where('pd.norec', $r['norec_pd'])
            ->orderBy('ar.tanggal', 'DESC')
            ->get();

        return $this->respond($data);
    }
}
