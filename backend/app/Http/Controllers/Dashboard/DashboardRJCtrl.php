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
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StokProdukDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use GuzzleHttp\Client;
use Exception;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DashboardRJCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    // DROPDOWN RUANGAN

    public function getDD(Request $r)
    {
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('mlur.statusenabled', true)
            ->where('mlur.objectloginuserfk', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $set = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->whereIn('id', $ruanganTersedia)->get();

        return $this->respond($res);
    }

    public function  getRawatJalanDetail(Request $r)
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
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->where('jd.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jd.hari', 'ilike', '%' . $now . '%')
            ->where('pg.namalengkap', '<>', 'Dokter Umum');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $dokter = $dokter->where('jd.objectpegawaifk', '=',  $r['idpegawai']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit($r['limit']);
        }
        $dokter->orderBy('pg.namalengkap');

        $dokter =  $dokter->get();

        foreach ($dokter as $d) {
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
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['nama']) && $r['nama'] != '') {
            $produk->where('pr.namaproduk', 'ilike',  '%' . $r['nama'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $produk->limit($r['limit']);
        }
        $produk->groupBy('ru.id', 'ru.namaruangan',  'pr.namaproduk',  'ap.asalproduk',  'spd.harganetto1', 'spd.harganetto2');
        $produk->orderBy('pr.namaproduk');
        $produk =  $produk->get();


        $res['dokter'] = $dokter;
        $res['produk'] = $produk;
        return $this->respond($res);
    }
    public function  getJadDokter(Request $r)
    {
        $now = $this->hari_ini($r['tanggal']);
        //  $now = $this->hari_ini(date('Y-m-d'));
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->where('jd.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->where('jd.hari', 'ilike', '%' . $now . '%');

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $dokter = $dokter->where(function ($query) use ($searchTerm) {
                $query->where('pg.namalengkap', 'ilike', $searchTerm)
                    ->orWhere('jd.objectpegawaifk', 'ilike', $searchTerm);
            });
        }
        $dokter->orderBy('pg.namalengkap');

        $dokter =  $dokter->get();

        foreach ($dokter as $d) {
            $d->hari = $now;
        }

        $res['dokter'] = $dokter;
        return $this->respond($res);
    }

    public function getRJPasien(Request $r)
    {

        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('mlur.objectloginuserfk', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $data  = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukorder_t as so', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pgi', 'pgi.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pgk', 'pgk.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('kendalidokumen_t as kd', 'kd.noregistrasifk', '=', 'pd.norec')
            ->leftJoin(
                DB::raw("(SELECT pasiendaftarfk, MAX(tglsurat) as tglsurat_terbaru
                          FROM suratketerangan_t
                          GROUP BY pasiendaftarfk) as st_terbaru"),
                function ($join) {
                    $join->on('pd.norec', '=', 'st_terbaru.pasiendaftarfk');
                }
            )
            ->leftJoin('suratketerangan_t as st', function ($join) {
                $join->on('st.pasiendaftarfk', '=', 'st_terbaru.pasiendaftarfk')
                    ->on('st.tglsurat', '=', 'st_terbaru.tglsurat_terbaru');
            })
            ->leftJoin('pasienperjanjian_t as rm', 'st.norec', '=', 'rm.objectsuratfk')
            ->leftjoin('antrianpasienregistrasi_t as apr', function ($join) {
                $join->on('apr.nobpjs', 'ps.nobpjs')
                    ->whereRaw('CAST(apr.objectruanganfk AS TEXT) = CAST(pd.objectruanganlastfk AS TEXT)')
                    ->where('apr.statusenabled', true)
                    ->whereRaw('DATE(pd.tglregistrasi) = DATE(apr.tanggalreservasi)');
            })
            ->leftjoin('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
            // ->leftjoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->select(
                'rm.norec AS skdp',
                'so.konsultasi',
                'tip.norec as norec_ppi',
                'ifp.namainfeksippi',
                'pgi.id as pegawaifk',
                'pgi.namalengkap as dokter_konsul',
                'pgk.id as id_dokter_konsul_verif',
                'pgk.namalengkap as dokter_konsul_verif',
                'ru.namaruangan',
                'ru.id as id_ruangan',
                'pd.norec as norec_pd',
                'pd.nocmfk',
                'ps.nocm',
                'ps.sitb_id',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ps.nobpjs',
                'pd.noreservasi',
                'ps.noidentitas',
                'ps.tgllahir',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pg.namalengkap',
                'pg.id as pgid',
                'ps.namapasien',
                'jk.jeniskelamin',
                'apr.isconfirm',
                DB::raw('COALESCE(apr.noantrian, apd.noantrian) AS noantrian'),
                'apr.noantrian as antrian_loket',
                'apd.noantrian as antrian_daftar',
                'per.namalengkap as perawat',
                'apd.status',
                'apd.norec as norec_apd',
                'kp.kelompokpasien',
                'pa.nosep',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pd.perawatfk',
                'kl.namakelas',
                'kd.isdikirim',
                'pd.nosbmlastfk',
                'apd.objectstrukorderfk',
                'apd.tglkeluar',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'ps.iskanker',
                DB::raw("CAST(pd.tglregistrasi AS DATE),
                (case when pd.iscppt=true then 'Selesai' else 'Menunggu Pelayanan' end) as statuspelayanan,
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->whereNotNull('apd.tglkeluar')
            ->where('ps.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true);
        $filter = false;
        // if(isset($r['ruanganfk']) && $r['idpegawai'] != ''){
        //     $data = $data
        // }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $idpegawai = $r['idpegawai'];
            $data = $data->where(function ($query) use ($idpegawai) {
                $query->where('pd.objectpegawaifk', '=', $idpegawai)
                    ->orWhere('pd.objectpegawairawatbersamafk', '=', $idpegawai)
                    ->orWhere('pgk.id', '=', $idpegawai)
                    ->orWhereExists(function ($subQuery) use ($idpegawai) {
                        $subQuery->select(DB::raw(1))
                            ->from('rawatbersama_t as rb')
                            ->whereColumn('rb.noregistrasifk', 'pd.norec')
                            ->where('rb.objectpegawaifk', '=', $idpegawai);
                    });
            });
        }

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('pd.noreservasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $filter = true;
            $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $filter = true;
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        if (isset($r['konsul']) && $r['konsul'] != '') {
            $filter = true;
            $data = $data->where('so.konsultasi', '=', $r['konsul']);
        }
        if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
            $filter = true;
            if ($r['statuspanggil'] == 'Belum Selesai') {
                $data = $data->where('apd.status', '!=',  'Selesai');
            } else {
                $data = $data->where('apd.status', '=',  $r['statuspanggil']);
            }
        }
        // if (isset($r['limit']) && $r['limit'] != '') {
        //     $data = $data->limit($r['limit']);
        // }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->orderBy('noantrian');
        // $data = $data->get();
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);
        foreach ($data as $d) {
            $dokterTambahan = DB::table('rawatbersama_t as rb')
                ->join('pegawai_m as pg', 'rb.objectpegawaifk', 'pg.id')
                ->select('rb.*', 'pg.namalengkap')
                ->where('noregistrasifk', $d->norec_pd)
                ->get();
            $d->dokterTambahan = $dokterTambahan;
            $d->umur =  $this->getAgeYear($d->tgllahir, $d->tglregistrasi) . ' thn';
        }

        $laboratRad = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->select('so.norec', 'so.noregistrasifk', 'so.keteranganorder')
            ->whereIn('pd.nocmfk', $data->pluck('nocmfk'))
            ->where('so.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->orderByDesc('so.tglorder')
            ->get();

        foreach ($data as $d) {
            $d->laboratorium = [];
            $d->radiologi = [];
            $d->resep = [];
            $d->konsul = [];
            foreach ($laboratRad as $ss) {
                if ($d->norec_pd == $ss->noregistrasifk) {
                    if ($ss->keteranganorder == 'Order Radiologi') {
                        $d->radiologi[] = $ss;
                    } elseif ($ss->keteranganorder == 'Order Laboratorium') {
                        $d->laboratorium[] = $ss;
                    } elseif ($ss->keteranganorder == 'Order Farmasi') {
                        $d->resep[] = $ss;
                    } elseif (strpos($ss->keteranganorder, 'Kepada Yth') !== false) {
                        $d->konsul[] = $ss;
                    }
                }
            }
        }

        $skdp = DB::table('pasienperjanjian_t as rm')
            ->leftJoin('pasien_m as ps', 'rm.objectpasienfk', '=', 'ps.id')
            ->leftJoin('suratketerangan_t as st', 'st.norec', '=', 'rm.objectsuratfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'st.pasiendaftarfk')
            ->select('rm.norec as norec_perjanjian', 'ps.namapasien', 'pd.norec as norec_pd', 'st.jenissuratfk')
            ->whereIn('pd.norec', $data->pluck('norec_pd'))
            ->where('rm.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->get();

        foreach ($data as $ds) {
            $ds->perjanjian = [];
            foreach ($skdp as $sd) {
                if ($ds->norec_pd == $sd->norec_pd) {
                    if ($sd->jenissuratfk == 17) {
                        $ds->perjanjian[] = $sd;
                    }
                }
            }
        }
        // $res['data'] = $data;
        return $this->respond($data);
    }
    public function getRJPasienTotal(Request $r)
    {

        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $data  = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukorder_t as so', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pgi', 'pgi.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->select(
                'apd.status',
                'apd.objectstrukorderfk',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->whereNotNull('apd.tglkeluar')
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true);
        $filter = false;
        // if(isset($r['ruanganfk']) && $r['idpegawai'] != ''){
        //     $data = $data
        // }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $filter = true;
            $data = $data->where(function ($query) use ($r) {
                $query->where('pg.id', '=', $r['idpegawai'])
                    ->orWhere('pgi.id', '=', $r['idpegawai']);
            });
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '<=', $r->sampai);
        }
        if (isset($r['status']) && $r['status'] != '') {
            $filter = true;
            $data = $data->where('pd.ispelayananpasien', '=', $r['status']);
        }
        if (isset($r['statuspanggil']) && $r['statuspanggil'] != '') {
            $filter = true;
            if ($r['statuspanggil'] == 'Belum Selesai') {
                $data = $data->where('apd.status', '!=',  'Selesai');
            } else {
                $data = $data->where('apd.status', '=',  $r['statuspanggil']);
            }
        }
        $data = $data->get();
        // $res['data'] = $data;
        return $this->respond($data);
    }
    public function getRJPasienKonsul(Request $r)
    {

        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();
        // ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $data  = DB::table('antrianpasiendiperiksa_t  as apd')
            ->join('pasiendaftar_t as pd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukorder_t as so', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pgi', 'pgi.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('pegawai_m as per', 'pd.perawatfk', '=', 'per.id')
            ->select(
                'so.konsultasi',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->whereNotNull('apd.tglkeluar')
            ->where('so.konsultasi', true)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true);
        $filter = false;
        // if(isset($r['ruanganfk']) && $r['idpegawai'] != ''){
        //     $data = $data
        // }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $filter = true;
            $data = $data->where(function ($query) use ($r) {
                $query->where('pg.id', '=', $r['idpegawai'])
                    ->orWhere('pgi.id', '=', $r['idpegawai']);
            });
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $filter = true;
            $data = $data->where(DB::raw("apd.tglregistrasi::date"), '<=', $r->sampai);
        }
        $data = $data->get();
        // $res['data'] = $data;
        return $this->respond($data);
    }

    public function getRJPasienReservasi(Request $r)
    {
        $reservasi  = DB::table('antrianpasienregistrasi_t as ap')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'ap.pasiendaftarfk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'ap.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'ap.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ap.objectpegawaifk')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftJoin('kelompokpasien_m as kps', 'kps.id', '=', 'ap.objectkelompokpasienfk')
            ->select(
                'ap.norec',
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.nocm',
                'ps.nobpjs',
                'ps.noidentitas',
                'ap.noreservasi',
                'ap.tanggalreservasi',
                'ru.namaruangan',
                'ap.isconfirm',
                'pg.namalengkap as dokter',
                'ap.notelepon',
                'ps.namapasien',
                'ap.namapasien',
                'kps.kelompokpasien',
                'ap.tglinput',
                'ap.tipepasien',
                'ap.tgllahir',
                'ap.nocmfk',
                'ap.objectruanganfk',
                'ap.objectkelompokpasienfk',
                'ap.objectjeniskelaminfk',
                'ap.objectpegawaifk',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'pg.namalengkap as dokter',
                DB::raw('(case when ps.namapasien is null then ap.namapasien else ps.namapasien end) as namapasien,
            (case when ap.isconfirm=\'true\' then \'Confirm\' else \'Reservasi\' end) as status')
            )

            ->where('ap.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->where('ap.statusenabled', true)
            ->whereNotNull('ap.noreservasi')
            ->where('ap.noreservasi', '!=', '-');

        // ->where('ap.tanggalreservasi', '>=', date('Y-m-d 00:00:00'));

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $reservasi = $reservasi->where('ap.objectpegawaifk', '=',  $r['idpegawai']);
        }
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $reservasi = $reservasi->whereIn('ru.id',  explode(',', $r['ruanganid']));
        }

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $reservasi = $reservasi->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ap.noreservasi', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['noreservasi']) && $r['noreservasi'] != '') {
            $reservasi =  $reservasi->where('ap.noreservasi', '=',  $r['noreservasi']);
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $reservasi =  $reservasi->where('ap.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $reservasi = $reservasi->where(DB::raw("ap.tanggalreservasi::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $reservasi = $reservasi->where(DB::raw("ap.tanggalreservasi::date"), '<=', $r->sampai);
        }



        $reservasi =  $reservasi->get();

        foreach ($reservasi as $d) {
            if ($d->objectkelompokpasienfk == null) {
                $d->objectkelompokpasienfk = 1;
                $d->kelompokpasien = 'Umum/Pribadi';
            }
            $d->status = 'Confirm';
            $d->status_c = 'purple';
            if ($d->isconfirm == null) {
                $d->status = 'Reservasi';
                $d->status_c = 'danger';
            }
        }

        $res['reservasi'] =  $reservasi;
        return $this->respond($res);
    }


    public function Ruangandropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }
    public function HitungAntrian(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $data =  DB::table('antrianpasiendiperiksa_t as at')
            ->join('ruangan_m as ru', 'ru.id', '=', 'at.objectruanganfk')
            ->select(DB::raw("count (at.norec) as total,
            case when at.status is null   then 'Belum Dipanggil' else  at.status end as name "))
            ->where('at.statusenabled', true)
            ->where('at.kdprofile', $kdProfile)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')));
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $data = $data->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where('at.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where('at.tglregistrasi', '<=',  $r['sampai'] . ' 23:59');
        }
        $data = $data->groupBy('at.status', 'at.statusantrian');
        $data = $data->get();

        $seriesJK = [0, 0, 0];
        $labelJK = ['Belum Dipanggil', 'Sudah Dipanggil', 'Selesai'];
        foreach ($data as $d) {
            if ($d->name == 'Belum Dipanggil') {
                $seriesJK[0] = $d->total;
            }
            if ($d->name == 'Sudah Dipanggil') {
                $seriesJK[1] = $d->total;
            }
            if ($d->name == 'Selesai') {
                $seriesJK[2] = $d->total;
            }

            // $labelJK[]  = $d->name;
        }
        $result['chartStatus']['series'] = $seriesJK;
        $result['chartStatus']['labels'] = $labelJK;
        return $this->respond($result);
    }
    public function panggilPasien(Request $r)
    {

        DB::beginTransaction();
        try {
            $status =  'Sudah Dipanggil';
            $kelompokUser = KelompokUser::where('id', session('kelompokuser_id'))->first();
            if (!empty($kelompokUser) && $kelompokUser->kelompokuser == 'dokter') {
                $update =  [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggildokter' => date('Y-m-d H:i:s'),
                ];
            } else {
                $update =  [
                    'status' => $status,
                    'statusantrian' => 1,
                    'tgldipanggilsuster' => date('Y-m-d H:i:s'),
                ];
            }
            AntrianPasienDiperiksa::where('norec', $r->norec_apd)
                ->where('kdprofile', $this->kdProfile)
                ->update($update);

            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "status" => $status,
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

    public function getDaftarKonsulFromOrder(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->Join('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('alamat_m as alm', 'ps.id', '=', 'alm.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            // ->leftJoin('departemen_m as dept','dept.id','=','ru.objectdepartemenfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'apd.residencefk')
            ->Join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftjoin('antrianpasienregistrasi_t as apr', 'apr.noreservasi', '=', 'pd.statusschedule')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelas_m as klstg', 'klstg.id', '=', 'asu.objectkelasdijaminfk')
            ->select(
                'apd.tglmasuk as tglregistrasi',
                'ps.nocm',
                'pd.noregistrasi',
                'ps.namapasien',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'kls.id as idkelas',
                'kls.namakelas',
                'kp.kelompokpasien',
                'rek.namarekanan',
                'apd.objectpegawaifk',
                'pg.namalengkap as namadokter',
                'pd.norec as norec_pd',
                'apd.norec as norec_apd',
                'apd.objectasalrujukanfk',
                'apd.tgldipanggildokter',
                'apd.statuspasien as statuspanggil',
                'pd.statuspasien',
                'apd.tgldipanggildokter',
                'apd.tgldipanggilsuster',
                'apr.noreservasi',
                'apd.noantrian',
                'apr.tanggalreservasi',
                'alm.alamatlengkap',
                'klstg.namakelas as kelasdijamin',
                'ru.ipaddress',
                'ps.iskompleks',
                'apd.residencefk',
                'pg2.namalengkap as residence',
                DB::raw('case when apd.ispelayananpasien is null then \'false\' else \'true\' end as statuslayanan')
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglmasuk as Date)"), $dateRange);

        $data = $data->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')));
        //        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->orderBy('apd.noantrian');
        $data = $data->get();
        return $this->respond($data);
    }

    public function getComboCount(Request $request)
    {
        $dinamicRuangan = explode(',', $request['ruanganfk']);

        $defaultRuangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($defaultRuangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        $tglBetween = [$request->dari, $request->sampai];
        $datas = [
            'jumlahPasien' => $this->countPasien($tglBetween, $request['idpegawai'], $dinamicRuangan, $ruanganTersedia),
            'jumlahTindakan' => $this->countPasienTindakan($tglBetween, $request['idpegawai'], $dinamicRuangan, $ruanganTersedia),
            'pendapatanJasa' => $this->countJasaPelayanan($tglBetween, $request['idpegawai'])
        ];
        return $this->respond($datas);
    }

    public function countPasien($rangeDate, $pegawaifk, $dinamicRuangan, $defaultRuangan)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            // ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            // ->where('apd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->whereNotNull('pd.tglpulang')
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $rangeDate)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')));
        if ($pegawaifk) {
            $data = $data->where('pd.objectpegawaifk', $pegawaifk);
        }
        if ($dinamicRuangan && $dinamicRuangan != [""]) {
            $data = $data->whereIn('pd.objectruanganlastfk', $dinamicRuangan);
        } else {
            $data = $data->whereIn('pd.objectruanganlastfk', $defaultRuangan);
        }
        $data = $data->count();
        return $data;
    }

    public function countPasienTindakan($rangeDate, $pegawaifk, $dinamicRuangan, $defaultRuangan)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', 'ru.id')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('pelayananpasiendetail_t as ppd', 'ppd.pelayananpasien', 'pp.norec')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->where('apd.statusenabled', true)
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('pp.keteranganlain', 'Tindakan')
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $rangeDate)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $rangeDate)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('KdDeptPasienRJ')));

        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }
        if ($dinamicRuangan && $dinamicRuangan != [""]) {
            $data = $data->whereIn('apd.objectruanganfk', $dinamicRuangan);
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $defaultRuangan);
        }
        $data = $data->count();

        return $data;
    }

    public function countJasaPelayanan($rangeDate, $pegawaifk)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('pelayananpasiendetail_t as ppd', 'ppd.pelayananpasien', 'pp.norec')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.pelayananpasien', 'pp.norec')
            ->select(DB::raw("SUM(ppd.hargajual) as total"))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $rangeDate);

        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }

        $data = $data->first();

        return $data;
    }

    public function checkinJkn(Request $request)
    {
        date_default_timezone_set(config('app.timezone')); // set timezone
        $kdprofile = $this->kdProfile;
        $objReq = new Request();
        $objReq['kodebooking'] = $request->kodebooking;
        $objReq['waktu'] = strtotime(date('Y-m-d H:i:s')) * 1000;
        $objReq['user'] = $this->getNamaPegawai();
        $post = app('App\Http\Controllers\Bridging\AntrianOnlineCtrl')->checkIn($objReq, false);
        $post = json_decode($post->content(), true);
        return $this->respond(null, $post['metadata']['code'], $post['metadata']['message']);
    }

    public function batalJkn(Request $request)
    {
        $kdprofile = $this->kdProfile;
        $objReq = new Request();
        $objReq['kodebooking'] = $request->kodebooking;
        $objReq['keterangan'] = "Tidak jadi berobat";
        $objReq['user'] = $this->getNamaPegawai();
        $post = app('App\Http\Controllers\Bridging\AntrianOnlineCtrl')->batalAntrean($objReq, false);
        $post = json_decode($post->content(), true);
        return $this->respond(null, $post['metadata']['code'], $post['metadata']['message']);
    }

    public function CountKonsul(Request $request)
    {
        $data = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->where('so.kdprofile', $this->kdProfile)
            ->whereNull('apd.norec')
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->orderBy('so.tglorder', 'desc');

        // if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
        //     $data = $data->wherenull('apd.norec');
        // }

        if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
            $data = $data->where('pg.id', $request['idpegawai']);
        }

        $data = $data->count();

        return $this->respond($data);
    }
    public function CountSITB(Request $request)
    {
        $data = DB::table('strukorder_t as so')
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->where('so.statusorder', 0)
            ->where('so.objectkelompoktransaksifk', 432)
            ->orderBy('so.tglorder', 'desc');

        // if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
        //     $data = $data->wherenull('apd.norec');
        // }

        // if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
        //     $data = $data->where('pg.id', $request['idpegawai']);
        // }

        $data = $data->count();

        return $this->respond($data);
    }

    public function getDetailKonsul(Request $request)
    {
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->Join('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'apd.objectruanganasalfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpetugasfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'so.rawatbersama',
                'so.konsultasi',
                'so.lainlain',
                'ru2.namaruangan as ruanganasal',
                'ru.namaruangan as ruangantujuan',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'pg2.namalengkap',
                'pg.namalengkap as dokter',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd',
                'so.keteranganlainnya',
                'pd.objectkelasfk as kelasfk_pd'
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('apd.norec', $request['norec_apd'])
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'));


        $data = $data->orderBy('so.tglorder', 'desc');
        $data = $data->get();

        return $this->respond($data);
    }

    public function kirimWASuratDokter(Request $request)
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

            // $finalLink = "https://sim.rsdgunungjati.com/service/dashboard/registrasi/cetak-surat-keterangan-dokter?&noregistrasi={$datapasien->noregistrasi}&dokter={$datapasien->dokter}&kelompokpasien={$datapasien->kelompokpasien}&objectdepartemenfk=18&tglregistrasi={$datapasien->tglregistrasi}&norec_pd={$datapasien->norec}&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzUxMDE2NDN9.y2rs5nFOX8J4PK63Uxv_Df1AFY7MoDR1e8WmdLxDmxtolZNg9XrylQgMl2W5Ng1MYhIxgXYwnjEqbD9If_8vyw.MQ==";

            $finalLink = "https://rsdgunungjati.com/service/dashboard/registrasi/cetak-surat-keterangan-dokter?&noregistrasi={$datapasien->noregistrasi}&dokter={$datapasien->dokter}&kelompokpasien={$datapasien->kelompokpasien}&objectdepartemenfk=18&tglregistrasi={$datapasien->tglregistrasi}&norec_pd={$datapasien->norec}&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzUxMDE2NDN9.y2rs5nFOX8J4PK63Uxv_Df1AFY7MoDR1e8WmdLxDmxtolZNg9XrylQgMl2W5Ng1MYhIxgXYwnjEqbD9If_8vyw.MQ==";

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

            $url = $this->settingFix('APIWA_url');
            $token = 'Bearer ' . $this->settingFix('APIWA_token');

            // if($dataOrder->objectdepartemenfk == '18'){
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => $token,
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

    public function cetakKonsul(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $params1 = '';
        $params2 = '';

        if (isset($r['noregistrasi'])) {
            $data = DB::table("pasiendaftar_t")
                ->select('norec','nocmfk')
                ->where('noregistrasi', $r['noregistrasi'])
                ->first();

            $params1 = " AND pd.noregistrasi = '" . $r['noregistrasi'] . "'";
        }

        if (isset($r['norec'])) {
            $params1 = " AND so.norec = '" . $r['norec'] . "'";
        }

        $raw = DB::table('antrianpasiendiperiksa_t as apd')
            ->Join('strukorder_t as so', 'so.norec', '=', 'apd.objectstrukorderfk')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('ruangan_m as ru2', 'ru2.id', '=', 'apd.objectruanganasalfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'so.rawatbersama',
                'so.konsultasi',
                'so.lainlain',
                'ru2.namaruangan as ruanganasal',
                'ru.namaruangan as ruangantujuan',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'pg2.namalengkap',
                'pg.namalengkap as dokter',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd',
                'apd.tglmasuk',
                'so.keteranganlainnya',
                'pd.objectkelasfk as kelasfk_pd'
            )
            ->where('so.noregistrasifk', $data->norec)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->whereNotNull('so.keteranganlainnya')
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'));


        $raw = $raw->orderBy('so.tglorder', 'desc');
        $raw = $raw->get();

        foreach ($raw as $item) {
            $item->tteorder = "RM D.4".$item->namalengkap.'Order Konsul'.$item->tglorder.$item->ruanganasal;
            $item->tteorder = base64_encode(QrCode::format('svg')->size(75)->generate($item->tteorder));
            $item->ttejawab = "RM D.4".$item->dokter.'Jawaban Konsul'.$item->tglmasuk.$item->ruangantujuan;
            $item->ttejawab = base64_encode(QrCode::format('svg')->size(75)->generate($item->ttejawab));
        }

        // $raw = DB::table('strukorder_t')
        //         ->where('noorder', 'like', 'K%')
        //         ->where('statusenabled', true)
        //         ->where('noregistrasifk', $data->norec)->get();

        $identitas = DB::table('pasien_m as pas')
        ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'pas.objectjeniskelaminfk')
        ->select(
            'pas.nocm',
            'pas.namapasien',
            'pas.tgllahir',
            'jk.jeniskelamin'
        )
        ->where('pas.id', $data->nocmfk)
        ->first();


        if (!$raw || empty($raw)) {
            return null;
        }

        if (isset($r['noregistrasi'])) {
            if (!$data || empty($data)) {
                return null;
            }
        }


        $pageWidth = 950;

        $res['pdf']  =  true;

        $title = 'KONSULTASI';
        $blade = 'report.emr.konsultasi';
        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'raw' => $raw,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'identitas' => $identitas,
                    'title' => $title,
                )
            );
            return $pdf;
        }
        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'raw' => $raw,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'identitas' => $identitas,
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
            $blade,
            compact('raw', 'pageWidth', 'img', 'title', 'res', 'identitas')
        );
    }

    private function encryptData($data, $key, $cipher = "AES-128-CTR")
    {
        $options = 0;
        $encryption_iv = '1234567891011121';
        return openssl_encrypt($data, $cipher, $key, $options, $encryption_iv);
    }

    private function decryptData($encryptedData, $key, $cipher = "AES-128-CTR")
    {
        $options = 0;
        $decryption_iv = '1234567891011121';
        return openssl_decrypt($encryptedData, $cipher, $key, $options, $decryption_iv);
    }
}
