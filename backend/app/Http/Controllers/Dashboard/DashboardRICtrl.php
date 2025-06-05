<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\TotalPasien\TotalPasienResource;
use App\Models\Master\Departemen;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Kamar;
use App\Models\Master\Ruangan;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukResep;
use App\Models\Transaksi\SuratKeterangan;
use App\Traits\Valet;
use Exception;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class DashboardRICtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function getRuanganRanap()
    {
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlur.statusenabled', true)
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        return $ruanganTersedia;
    }

    // DROPDOWN RUANGAN
    public function getDropdown()
    {
        $listRuangan = $this->getRuanganRanap();

        $set = explode(',', $this->settingFix('idDepRawatInap'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->whereIn('id', $listRuangan)->get();

        return $this->respond($res);
    }

    // PASIEN PER RUANGAN
    // public function getRIPasien(Request $r)
    // {

    //     $ruanganTersedia = $this->getRuanganRanap();

    //     $data  = DB::table('pasiendaftar_t as pd')
    //         ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
    //         ->join('kelas_m as kls1', 'kls1.id', 'pd.objectkelasrawatfk')
    //         ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
    //         ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
    //         ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
    //         ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
    //         ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
    //         ->leftjoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
    //         ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
    //         ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
    //         ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
    //         ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
    //         ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
    //         ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
    //         ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
    //         ->JOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
    //         ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
    //         ->select(
    //             DB::raw("
    //             apd.noregistrasifk, pd.tglregistrasi,
    //             apd.nobed,
    //             ru.id,
    //             ps.tgllahir,
    //             pd.isresumemedis,
    //             ps.sitb_id,
    //             pd.objectkelasrawatfk,
    //             apd.kelasrawatfk,
    //             apd.tglmasuk,
    //             pd.objectkelasfk,
    //             ps.nohp,
    //             pas.nosep,
    //             pd.isonsiteservice as tandaigd,
    //             alm.alamatlengkap,
    //             pg.namalengkap as dokter,
    //             jk.jeniskelamin,
    //             asru.asalrujukan,
    //             kp.kelompokpasien,
    //             ru.statusenabled,
    //             ru.namaruangan,
    //             kls.namakelas,
    //             kls1.namakelas as namakelasrawat,
    //             kmr.namakamar,
    //             kmr.norec as norec_kamar,
    //             pd.norec as norec_pd,
    //             pd.objectruanganasalfk,
    //             pd.nocmfk,
    //             pd.tglpulang,
    //             ru.objectdepartemenfk,
    //             tt.reportdisplay,
    //             pd.objectruanganlastfk,
    //             pd.objectpegawaifk,
    //             pd.objectpegawairawatbersamafk,
    //             pd.noregistrasi,
    //             pg.namalengkap,
    //             pg2.namalengkap as nama,
    //             ps.namapasien,
    //             ps.nocm,
    //             ps.alamatrmh,
    //             pd.catatan,
    //             ps.nobpjs,
    //             ds.namadesakelurahan,
    //             km.namakecamatan,
    //             kbp.namakotakabupaten,
    //             ps.noidentitas,
    //             apd.norec as norec_apd,
    //             pd.tglregistrasi,
    //             FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) / (24 * 3600)) || ' hari ' ||
    //                 FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % (24 * 3600) / 3600) || ' jam ' ||
    //                 FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % 3600 / 60) || ' menit' AS selisihWaktu
    //             ")
    //         )
    //         ->where('pd.kdprofile', $this->kdProfile)
    //         ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRanapFix')))
    //         ->where('pd.statusenabled', 't')
    //         ->where('apd.statusenabled', 't')
    //         ->whereNull('apd.tglkeluar')
    //         ->whereNull('pd.tglpulang')
    //         ->where('apd.kdprofile', $this->kdProfile)
    //         ->orderBy('pd.noregistrasi', 'DESC')
    //         ->orderBy('pd.tglregistrasi', 'DESC');

    //     if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
    //         $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
    //     } else {
    //         $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
    //     }

    //     // else {
    //     //     $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
    //     // }

    //     if (isset($r['search']) && $r['search'] != '') {
    //         $searchTerm = '%' . $r['search'] . '%';
    //         $data = $data->where(function ($query) use ($searchTerm) {
    //             $query->where('ps.namapasien', 'ilike', $searchTerm)
    //                 ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
    //                 ->orWhere('ps.nocm', 'ilike', $searchTerm)
    //                 ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
    //                 ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
    //         });
    //     }

    //     // if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
    //     //     $data = $data->where('pd.objectpegawaifk', '=',  $r['idpegawai']);
    //     // }
    //     if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
    //         $data = $data->where(function($query) use ($r) {
    //             $query->where('pd.objectpegawaifk', '=', $r['idpegawai'])
    //                   ->orWhere('pd.objectpegawairawatbersamafk', '=', $r['idpegawai']);
    //         });
    //     }
    //     if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
    //         $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
    //     }
    //     if (isset($r['nocm']) && $r['nocm'] != '') {
    //         $data = $data->where('ps.nocm', '=',  $r['nocm']);
    //     }
    //     if (isset($r['namapasien']) && $r['namapasien'] != '') {
    //         $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
    //     }
    //     $page = 1;
    //     if (isset($r['page']) && $r['page'] != '') {
    //         $page = $r['page'];
    //     }
    //     $data = $data->distinct('pd.noregistrasi')->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);
    //     // $data = $data->get();

    //     // foreach ($data as $d) {
    //     //     $d->lamarawat =  $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
    //     // }

    //     // $res['data'] = $data;
    //     foreach ($data as $d) {
    //         $dokterTambahan = DB::table('rawatbersama_t as rb')->join('pegawai_m as pg', 'rb.objectpegawaifk', 'pg.id')->select('rb.*', 'pg.namalengkap')->where('noregistrasifk', $d->norec_pd)->get();
    //         $d->dokterTambahan = $dokterTambahan;
    //         $d->umur =  $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
    //     }
    //     return $this->respond($data);
    // }

    public function getRIPasien(Request $r)
{
    $ruanganTersedia = $this->getRuanganRanap();
    DB::statement("SET TIME ZONE 'Asia/Jakarta';");

    $data = DB::table('pasiendaftar_t as pd')
        ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
        ->join('kelas_m as kls1', 'kls1.id', 'pd.objectkelasrawatfk')
        ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
        ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
        ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
        ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
        ->leftJoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
        ->leftJoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
        ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
        ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
        ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
        ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
        ->leftJoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
        ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
        ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
        ->join('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
        ->leftJoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
        ->leftJoin('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
        ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
        ->leftJoin('suratketerangan_t as st', 'st.pasiendaftarfk', '=', 'pd.norec')
        ->leftJoin('pasienperjanjian_t as rm', 'rm.objectsuratfk', '=', 'st.norec')
        ->select(
            DB::raw("
                apd.noregistrasifk, pd.tglregistrasi,
                tip.norec as norec_ppi,
                ifp.namainfeksippi,
                apd.nobed,
                ru.id,
                ps.tgllahir,
                pd.isresumemedis,
                ps.sitb_id,
                pd.objectkelasrawatfk,
                apd.kelasrawatfk,
                apd.tglmasuk,
                pd.objectkelasfk,
                ps.nohp,
                pas.nosep,
                pd.isonsiteservice as tandaigd,
                alm.alamatlengkap,
                pg.namalengkap as dokter,
                jk.jeniskelamin,
                asru.asalrujukan,
                kp.kelompokpasien,
                ru.statusenabled,
                ru.namaruangan,
                kls.namakelas,
                kls1.namakelas as namakelasrawat,
                kmr.namakamar,
                kmr.norec as norec_kamar,
                pd.norec as norec_pd,
                pd.objectruanganasalfk,
                pd.nocmfk,
                pd.tglpulang,
                ru.objectdepartemenfk,
                tt.reportdisplay,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.objectpegawairawatbersamafk,
                pd.noregistrasi,
                pg.namalengkap,
                pg2.namalengkap as nama,
                ps.namapasien,
                ps.nocm,
                ps.alamatrmh,
                pd.catatan,
                ps.nobpjs,
                ds.namadesakelurahan,
                km.namakecamatan,
                kbp.namakotakabupaten,
                ps.noidentitas,
                apd.norec as norec_apd,
                pd.tglregistrasi,
                ps.iskanker,
                st.jenissuratfk,
                FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) / (24 * 3600)) || ' hari ' ||
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % (24 * 3600) / 3600) || ' jam ' ||
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % 3600 / 60) || ' menit' AS selisihWaktu
                ")
        )
        ->where('pd.kdprofile', $this->kdProfile)
        ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
        ->where('pd.statusenabled', 't')
        ->where('apd.statusenabled', 't')
        ->whereNull('apd.tglkeluar')
        ->whereNull('pd.tglpulang')
        ->where('apd.kdprofile', $this->kdProfile)
        ->orderBy('pd.noregistrasi', 'DESC')
        ->orderBy('pd.tglregistrasi', 'DESC');

    if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
        $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
    } else {
        $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
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

    if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
        $idpegawai = $r['idpegawai'];
        $data = $data->where(function ($query) use ($idpegawai) {
            $query->where('pd.objectpegawaifk', '=', $idpegawai)
                ->orWhere('pd.objectpegawairawatbersamafk', '=', $idpegawai)
                ->orWhereExists(function ($subQuery) use ($idpegawai) {
                    $subQuery->select(DB::raw(1))
                        ->from('rawatbersama_t as rb')
                        ->whereColumn('rb.noregistrasifk', 'pd.norec')
                        ->where('rb.objectpegawaifk', '=', $idpegawai);
                });
        });
    }

    if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
        $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
    }
    if (isset($r['nocm']) && $r['nocm'] != '') {
        $data = $data->where('ps.nocm', '=', $r['nocm']);
    }
    if (isset($r['namapasien']) && $r['namapasien'] != '') {
        $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
    }
    $page = 1;
    if (isset($r['page']) && $r['page'] != '') {
        $page = $r['page'];
    }
    $data = $data->distinct('pd.noregistrasi')->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

    foreach ($data as $d) {
        $dokterTambahan = DB::table('rawatbersama_t as rb')
            ->join('pegawai_m as pg', 'rb.objectpegawaifk', 'pg.id')
            ->select('rb.*', 'pg.namalengkap')
            ->where('noregistrasifk', $d->norec_pd)
            ->get();
        $d->dokterTambahan = $dokterTambahan;
        $d->umur = $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
    }

    return $this->respond($data);
}

    public function getPasienRanapPPI(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kelas_m as kls1', 'kls1.id', 'pd.objectkelasrawatfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftJoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->join('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
            ->leftJoin('diagnosapasien_t as dg', 'dg.noregistrasifk', '=', 'apd.norec')
            ->leftJoin('detaildiagnosapasien_t as ddg', 'ddg.objectdiagnosapasienfk', '=', 'dg.norec')
            ->leftJoin('diagnosa_m as dm', function ($join) {
                $join->on('dm.id', '=', 'ddg.objectdiagnosafk')
                    ->whereNotNull('dm.penularan');
            })
            ->selectRaw("
                DATE_PART('year', AGE(now(), ps.tgllahir)) AS umur,
                dm.namadiagnosa,
                dm.id as kddiagnosa,
                dm.penularan,
                apd.noregistrasifk,
                pd.tglregistrasi,
                tip.norec as norec_ppi,
                ifp.namainfeksippi,
                apd.nobed,
                ps.tgllahir,
                pd.objectkelasrawatfk,
                apd.kelasrawatfk,
                apd.tglmasuk,
                pd.objectkelasfk,
                pas.nosep,
                alm.alamatlengkap,
                pg.namalengkap as dokter,
                jk.jeniskelamin,
                kp.kelompokpasien,
                ru.namaruangan,
                kls.namakelas,
                kls1.namakelas as namakelasrawat,
                kmr.namakamar,
                pd.norec as norec_pd,
                pd.objectruanganasalfk,
                pd.nocmfk,
                pd.tglpulang,
                ru.objectdepartemenfk,
                tt.reportdisplay,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.objectpegawairawatbersamafk,
                pd.noregistrasi,
                pg.namalengkap,
                pg2.namalengkap as nama,
                ps.namapasien,
                ps.nocm,
                ps.nobpjs,
                km.namakecamatan,
                kbp.namakotakabupaten,
                ps.noidentitas,
                apd.norec as norec_apd,
                pd.tglregistrasi
            ")
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereRaw('ru.objectdepartemenfk IN (' . $this->settingFix('kdDepartemenRanapFix') . ')')
            ->where('pd.statusenabled', 't')
            ->where('apd.statusenabled', 't')
            ->where('apd.kdprofile', $this->kdProfile)
            ->orderBy('pd.noregistrasi', 'DESC')
            ->orderBy('pd.tglregistrasi', 'DESC');

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data->where(function ($q) use ($searchTerm) {
                $q->where('ps.namapasien', 'ilike', $searchTerm)
                ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                ->orWhere('ps.nocm', 'ilike', $searchTerm)
                ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        $page = $r['page'] ?? 1;
        $limit = $r['limit'] ?? 10;
        $data = $data->distinct('pd.noregistrasi')->paginate($limit, ['*'], 'page', $page);

        return $this->respond($data);
    }

    public function getPasienRajalPPI(Request $r)
    {
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
            ->leftjoin('antrianpasienregistrasi_t as apr', function($join) {
                $join->on('apr.nobpjs', 'ps.nobpjs')
                ->where('apr.statusenabled', true)
                     ->whereRaw('DATE(pd.tglregistrasi) = DATE(apr.tanggalreservasi)');
            })
            ->join('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
            ->leftjoin('diagnosapasien_t as dg' , 'dg.noregistrasifk' , '=' , 'apd.norec')
            ->leftjoin('detaildiagnosapasien_t as ddg' , 'ddg.objectdiagnosapasienfk' , '=' , 'dg.norec')
            ->leftjoin('jenisdiagnosa_m as jd' , 'jd.id' , '=' , 'ddg.objectjenisdiagnosafk')
            ->leftJoin('diagnosa_m as dm', function ($join) {
                $join->on('dm.id', '=', 'ddg.objectdiagnosafk')
                     ->whereNotNull('dm.penularan');
            })
            ->select(
                'dm.namadiagnosa',
                'dm.id as kddiagnosa',
                'dm.penularan',
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
                'ds.namadesakelurahan',
                'km.namakecamatan',
                'kbp.namakotakabupaten',
                'ps.alamatrmh',
                'ps.nobpjs',
                'ps.noidentitas',
                'ps.tgllahir',
                'pd.objectpegawaifk',
                'pd.noregistrasi',
                'pg.namalengkap',
                'pg.id as pgid',
                'ps.namapasien',
                'jk.jeniskelamin',
                'apr.isconfirm',
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
                ps.objectjeniskelaminfk")
            )

            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->whereNotNull('apd.tglkeluar')
            ->where('ps.statusenabled',true)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true);
        $filter = false;

        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
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
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
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
        return $this->respond($data);
    }

    public function getKMKB(Request $r)
    {
        try {
            //code...
            $tglAwal            = Carbon::parse($r['tglawal'])->format('Y-m-d') ?? date('Y-m-d');
            $tglAkhir           = Carbon::parse($r['tglakhir'])->format('Y-m-d') ?? date('Y-m-d');
            $ruanganTersedia = $this->getRuanganRanap();

            if (isset($r['kmkb']) && $r['kmkb']) {
                $ruangan = DB::table('ruangan_m')
                            ->select('id', 'namaruangan')
                            ->where('objectdepartemenfk', $this->settingFix('idDepRawatInap'))
                            ->where('statusenabled', 't')
                            ->get();
                $ruanganTersedia = [];
                foreach ($ruangan as $ru) {
                    $ruanganTersedia[] = $ru->id;
                }
            }

            $data  = DB::table('pasiendaftar_t as pd')
                ->leftJoin('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
                ->leftJoin('kelas_m as kls1', 'kls1.id', 'pd.objectkelasrawatfk')
                ->leftJoin('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
                ->leftJoin('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
                ->leftJoin('tempattidur_m as tt', 'tt.id', 'apd.nobed')
                ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
                ->leftjoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
                ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
                ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
                ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
                ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
                ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
                ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->leftJOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
                ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
                ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasi','pd.noregistrasi')
                ->leftjoin('detaildiagnosapasien_t as dp', 'dp.noregistrasi', '=', 'pd.noregistrasi')
                ->select(
                    DB::raw("
                    apd.noregistrasifk, pd.tglregistrasi,
                    apd.nobed,
                    ru.id,
                    ps.tgllahir,
                    pd.isresumemedis,
                    pd.objectkelasrawatfk,
                    apd.kelasrawatfk,
                    apd.tglmasuk,
                    pd.objectkelasfk,
                    ps.nohp,
                    pas.nosep,
                    pd.inacbg_totalgrouper,
                    alm.alamatlengkap,
                    pg.namalengkap as dokter,
                    jk.jeniskelamin,
                    asru.asalrujukan,
                    kp.kelompokpasien,
                    ru.statusenabled,
                    ru.namaruangan,
                    kls.namakelas,
                    pd.ihs_diagnosis,
                    kls1.namakelas as namakelasrawat,
                    kmr.namakamar,
                    kmr.norec as norec_kamar,
                    pd.norec as norec_pd,
                    pd.objectruanganasalfk,
                    pd.nocmfk,
                    pd.tglpulang,
                    ru.objectdepartemenfk,
                    tt.reportdisplay,
                    pd.objectruanganlastfk,
                    pd.objectpegawaifk,
                    pd.objectpegawairawatbersamafk,
                    pd.noregistrasi,
                    pg.namalengkap,
                    pg2.namalengkap as nama,
                    ps.namapasien,
                    ps.nocm,
                    ps.alamatrmh,
                    pd.catatan,
                    ps.nobpjs,
                    ds.namadesakelurahan,
                    km.namakecamatan,
                    kbp.namakotakabupaten,
                    ps.noidentitas,
                    apd.norec as norec_apd,
                    pd.tglregistrasi,
                    dp.objectdiagnosafk as diagnosa,
                    SUM(((pp.hargasatuan - pp.hargadiscount) * pp.jumlah) + COALESCE(pp.jasa, 0)) AS realcost,
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) / (24 * 3600)) || ' hari ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % (24 * 3600) / 3600) || ' jam ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % 3600 / 60) || ' menit' AS selisihWaktu
                    ")
                )
                ->where('pd.kdprofile', $this->kdProfile)
                // ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRanapFix')))
                // ->where('pd.statusenabled', 't')
                ->where('pp.statusenabled', 't')
                ->where('apd.statusenabled', 't')
                ->where('apd.kdprofile', $this->kdProfile)
                ->groupBy(
                    'apd.noregistrasifk',
                    'pd.tglregistrasi',
                    'apd.nobed',
                    'ru.id',
                    'ps.tgllahir',
                    'pd.isresumemedis',
                    'pd.objectkelasrawatfk',
                    'apd.kelasrawatfk',
                    'apd.tglmasuk',
                    'pd.objectkelasfk',
                    'ps.nohp',
                    'pas.nosep',
                    'pd.inacbg_totalgrouper',
                    'alm.alamatlengkap',
                    'pg.namalengkap',
                    'jk.jeniskelamin',
                    'asru.asalrujukan',
                    'kp.kelompokpasien',
                    'ru.statusenabled',
                    'ru.namaruangan',
                    'kls.namakelas',
                    'kls1.namakelas',
                    'kmr.namakamar',
                    'kmr.norec',
                    'pd.norec',
                    'pd.objectruanganasalfk',
                    'pd.nocmfk',
                    'pd.tglpulang',
                    'ru.objectdepartemenfk',
                    'tt.reportdisplay',
                    'dp.objectdiagnosafk',
                    'pd.objectruanganlastfk',
                    'pd.objectpegawaifk',
                    'pd.objectpegawairawatbersamafk',
                    'pd.noregistrasi',
                    'pg.namalengkap',
                    'pg2.namalengkap',
                    'ps.namapasien',
                    'ps.nocm',
                    'ps.alamatrmh',
                    'pd.catatan',
                    'ps.nobpjs',
                    'ds.namadesakelurahan',
                    'km.namakecamatan',
                    'kbp.namakotakabupaten',
                    'ps.noidentitas',
                    'apd.norec',
                )
                ->orderBy('pd.noregistrasi', 'DESC')
                ->orderBy('pd.tglregistrasi', 'DESC');

            if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
                $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
            } else {
                $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
            }

            if (isset($r['isTanggalPulang']) && $r['isTanggalPulang']) {
                $data = $data->whereBetween('pd.tglpulang', [$tglAwal . " 00:00", $tglAkhir . " 23:59"]);
            } else {
                $data = $data->where(function ($q) use ($tglAwal, $tglAkhir) {
                    $q->whereNull('apd.tglkeluar')
                      ->orWhereNull('pd.tglpulang')
                      ->orWhereBetween('pd.tglregistrasi', [$tglAwal . " 00:00", $tglAkhir . " 23:59"]);
                });
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

            if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
                $data = $data->where('pd.objectpegawaifk', '=',  $r['idpegawai']);
            }

            $data = $data->distinct('pd.noregistrasi');
            $data = $data->get();
            foreach ($data as $d) {
                $d->umur =  $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
            }
            return $this->respond($data);
        } catch (\Exception $e) {
            return $this->setStatusCode(500)->respondError('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // PASIEN SUDAH PULANG
    public function getRIPasienTotal(Request $r)
    {
        $ruanganTersedia = $this->getRuanganRanap();

        $data  = DB::table('pasiendaftar_t as pd')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->JOIN('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->select(
                DB::raw("
                DISTINCT on (apd.noregistrasifk, pd.tglregistrasi)
                ru.id,
                ps.tgllahir,
                ps.nohp,
                pg.namalengkap as dokter,
                pd.isresumemedis,
                asru.asalrujukan,
                kp.kelompokpasien,
                kl.namakelas,
                alm.alamatlengkap,
                jk.jeniskelamin,
                pas.nosep,
                pd.objectstatuspulangfk,
                sp.statuspulang,
                ru.statusenabled,
                ru.namaruangan,
                pd.objectruanganasalfk,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.norec as norec_pd,
                apd.norec as norec_apd,
                pd.nocmfk,
                ps.nocm,
                pd.noregistrasi,
                pg.namalengkap,
                pg.id as dokterfk,
                ps.namapasien,
                ps.sitb_id,
                ps.nobpjs,
                ps.noidentitas,
                pd.tglmeninggal,
                pd.tglregistrasi,
                pd.tglpulang
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->whereNotNull('pd.tglpulang');
        // if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
        //     $data = $data->where('ru.id', '=',  $r['ruanganid']);
        // }
        // if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
        //     $data = $data->where('pd.noregistrasi', '=',  $r['noregistrasi']);
        // }
        // if (isset($r['nocm']) && $r['nocm'] != '') {
        //     $data = $data->where('pd.nocm', '=',  $r['nocm']);
        // }

        // if (isset($r['namapasien']) && $r['namapasien'] != '') {
        //     $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        // }

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglpulang::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglpulang::date"), '<=', $r->sampai);
        }
        // if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
        //     $data = $data->where('pd.objectpegawaifk', '=',  $r['idpegawai']);
        // }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
        }

        if (isset($r['qsearch']) && $r['qsearch'] != '') {
            $searchTerm = '%' . $r['qsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $data = $data->where('pd.objectpegawaifk', '=',  $r['idpegawai']);
        }
        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        foreach ($data as $d) {
            $d->lamarawat =  $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
            $d->umur =  $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
        }

        $resourse = TotalPasienResource::collection(new Collection($data));
        $res['data'] = $resourse;
        return $this->respond($data);
    }

    public function getRIPasienResume(Request $r)
    {
        $ruanganTersedia = $this->getRuanganRanap();
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");

        $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kelas_m as kls1', 'kls1.id', 'pd.objectkelasrawatfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftJoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftJoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->leftJoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
            ->select(
                DB::raw("
                    apd.noregistrasifk, pd.tglregistrasi,
                    tip.norec as norec_ppi,
                    ifp.namainfeksippi,
                    apd.nobed,
                    ru.id,
                    ps.tgllahir,
                    pd.isresumemedis,
                    ps.sitb_id,
                    pd.objectkelasrawatfk,
                    apd.kelasrawatfk,
                    apd.tglmasuk,
                    pd.objectkelasfk,
                    ps.nohp,
                    pas.nosep,
                    pd.isonsiteservice as tandaigd,
                    alm.alamatlengkap,
                    pg.namalengkap as dokter,
                    jk.jeniskelamin,
                    asru.asalrujukan,
                    kp.kelompokpasien,
                    ru.statusenabled,
                    ru.namaruangan,
                    kls.namakelas,
                    kls1.namakelas as namakelasrawat,
                    kmr.namakamar,
                    kmr.norec as norec_kamar,
                    pd.norec as norec_pd,
                    pd.objectruanganasalfk,
                    pd.nocmfk,
                    pd.tglpulang,
                    ru.objectdepartemenfk,
                    tt.reportdisplay,
                    pd.objectruanganlastfk,
                    pd.objectpegawaifk,
                    pd.objectpegawairawatbersamafk,
                    pd.noregistrasi,
                    pg.namalengkap,
                    pg2.namalengkap as nama,
                    ps.namapasien,
                    ps.nocm,
                    ps.alamatrmh,
                    pd.catatan,
                    ps.nobpjs,
                    ds.namadesakelurahan,
                    km.namakecamatan,
                    kbp.namakotakabupaten,
                    ps.noidentitas,
                    apd.norec as norec_apd,
                    pd.tglregistrasi,
                    ps.iskanker,
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) / (24 * 3600)) || ' hari ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % (24 * 3600) / 3600) || ' jam ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % 3600 / 60) || ' menit' AS selisihWaktu
                    ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->where('pd.statusenabled', 't')
            ->where('apd.statusenabled', 't')
            ->whereNull('apd.tglkeluar')
            ->whereNull('pd.tglpulang')
            ->where('apd.kdprofile', $this->kdProfile)
            ->orderBy('pd.noregistrasi', 'DESC')
            ->orderBy('pd.tglregistrasi', 'DESC');

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '<=', $r->sampai);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
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

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $idpegawai = $r['idpegawai'];
            $data = $data->where(function ($query) use ($idpegawai) {
                $query->where('pd.objectpegawaifk', '=', $idpegawai)
                    ->orWhere('pd.objectpegawairawatbersamafk', '=', $idpegawai)
                    ->orWhereExists(function ($subQuery) use ($idpegawai) {
                        $subQuery->select(DB::raw(1))
                            ->from('rawatbersama_t as rb')
                            ->whereColumn('rb.noregistrasifk', 'pd.norec')
                            ->where('rb.objectpegawaifk', '=', $idpegawai);
                    });
            });
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=', $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->distinct('pd.noregistrasi')->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        foreach ($data as $d) {
            $dokterTambahan = DB::table('rawatbersama_t as rb')
                ->join('pegawai_m as pg', 'rb.objectpegawaifk', 'pg.id')
                ->select('rb.*', 'pg.namalengkap')
                ->where('noregistrasifk', $d->norec_pd)
                ->get();
            $d->dokterTambahan = $dokterTambahan;
            $d->umur = $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
        }

        return $this->respond($data);
    }

    public function getPasienRanapRemueNull(Request $r)
    {
        $ruanganTersedia = $this->getRuanganRanap();
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");

        $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kelas_m as kls1', 'kls1.id', 'pd.objectkelasrawatfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftJoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftJoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftJoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->leftJoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->leftJoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
            ->select(
                DB::raw("
                    apd.noregistrasifk, pd.tglregistrasi,
                    tip.norec as norec_ppi,
                    ifp.namainfeksippi,
                    apd.nobed,
                    ru.id,
                    ps.tgllahir,
                    pd.isresumemedis,
                    ps.sitb_id,
                    pd.objectkelasrawatfk,
                    apd.kelasrawatfk,
                    apd.tglmasuk,
                    pd.objectkelasfk,
                    ps.nohp,
                    pas.nosep,
                    pd.isonsiteservice as tandaigd,
                    alm.alamatlengkap,
                    pg.namalengkap as dokter,
                    jk.jeniskelamin,
                    asru.asalrujukan,
                    kp.kelompokpasien,
                    ru.statusenabled,
                    ru.namaruangan,
                    kls.namakelas,
                    kls1.namakelas as namakelasrawat,
                    kmr.namakamar,
                    kmr.norec as norec_kamar,
                    pd.norec as norec_pd,
                    pd.objectruanganasalfk,
                    pd.nocmfk,
                    pd.tglpulang,
                    ru.objectdepartemenfk,
                    tt.reportdisplay,
                    pd.objectruanganlastfk,
                    pd.objectpegawaifk,
                    pd.objectpegawairawatbersamafk,
                    pd.noregistrasi,
                    pg.namalengkap,
                    pg2.namalengkap as nama,
                    ps.namapasien,
                    ps.nocm,
                    ps.alamatrmh,
                    pd.catatan,
                    ps.nobpjs,
                    ds.namadesakelurahan,
                    km.namakecamatan,
                    kbp.namakotakabupaten,
                    ps.noidentitas,
                    apd.norec as norec_apd,
                    pd.tglregistrasi,
                    ps.iskanker,
                    FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) / (24 * 3600)) || ' hari ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % (24 * 3600) / 3600) || ' jam ' ||
                        FLOOR(EXTRACT(EPOCH FROM NOW() - pd.tglregistrasi) % 3600 / 60) || ' menit' AS selisihWaktu
                    ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->where('pd.statusenabled', 't')
            ->where('apd.statusenabled', 't')
            ->whereNull('apd.tglkeluar')
            ->whereNull('pd.tglpulang')
            ->where('pd.isresumemedis', null)
            ->where('apd.kdprofile', $this->kdProfile)
            ->orderBy('pd.noregistrasi', 'DESC')
            ->orderBy('pd.tglregistrasi', 'DESC');

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '<=', $r->sampai);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
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

        if (isset($r['idpegawai']) && $r['idpegawai'] != '') {
            $idpegawai = $r['idpegawai'];
            $data = $data->where(function ($query) use ($idpegawai) {
                $query->where('pd.objectpegawaifk', '=', $idpegawai)
                    ->orWhere('pd.objectpegawairawatbersamafk', '=', $idpegawai)
                    ->orWhereExists(function ($subQuery) use ($idpegawai) {
                        $subQuery->select(DB::raw(1))
                            ->from('rawatbersama_t as rb')
                            ->whereColumn('rb.noregistrasifk', 'pd.norec')
                            ->where('rb.objectpegawaifk', '=', $idpegawai);
                    });
            });
        }

        if (isset($r['noregistrasi']) && $r['noregistrasi'] != '') {
            $data = $data->where('pd.noregistrasi', '=', $r['noregistrasi']);
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=', $r['nocm']);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->distinct('pd.noregistrasi')->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        return $this->respond($data);
    }

    // PASIEN SUDAH PULANG RESUME BELUM ISI
    public function getRIPasienTotalResumeNull(Request $r)
    {
        // Mendapatkan daftar ruangan yang tersedia
        $ruanganTersedia = $this->getRuanganRanap();

        // Query awal untuk mengambil data pasien rawat inap
        $data = DB::table('pasiendaftar_t as pd')
            ->leftjoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->join('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('asalrujukan_m as asru', 'asru.id', '=', 'pd.asalrujukanfk')
            ->select(DB::raw("
                DISTINCT on (apd.noregistrasifk, pd.tglregistrasi)
                ru.id,
                ps.tgllahir,
                ps.nohp,
                pg.namalengkap as dokter,
                pd.isresumemedis,
                asru.asalrujukan,
                kp.kelompokpasien,
                kl.namakelas,
                alm.alamatlengkap,
                jk.jeniskelamin,
                pas.nosep,
                pd.objectstatuspulangfk,
                sp.statuspulang,
                ru.statusenabled,
                ru.namaruangan,
                pd.objectruanganasalfk,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.norec as norec_pd,
                apd.norec as norec_apd,
                pd.nocmfk,
                ps.nocm,
                pd.noregistrasi,
                pg.namalengkap,
                pg.id as dokterfk,
                ps.namapasien,
                ps.sitb_id,
                ps.nobpjs,
                ps.noidentitas,
                pd.tglmeninggal,
                pd.tglregistrasi,
                pd.tglpulang
            "))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('pd.isresumemedis', null)
            ->whereNotNull('pd.tglpulang');

        // Filter waktu
        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("pd.tglpulang::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("pd.tglpulang::date"), '<=', $r->sampai);
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('apd.objectruanganfk', explode(',', $r['ruanganfk']));
        } else {
            $data = $data->whereIn('apd.objectruanganfk', $ruanganTersedia);
        }

        // Implementasi pencarian dengan istilah pencarian (qsearch)
        if (isset($r['qsearch']) && $r['qsearch'] != '') {
            $searchTerm = '%' . $r['qsearch'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        // Implementasi pagination
        $page = isset($r['page']) ? $r['page'] : 1;
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        foreach ($data as $d) {
            $d->lamarawat = $this->getAge($d->tglregistrasi, $d->tglpulang ? $d->tglpulang : date('Y-m-d H:i:s'));
            $d->umur = $this->getAge($d->tgllahir, date('Y-m-d H:i:s'));
        }

        // Format hasil dan respons
        $resourse = TotalPasienResource::collection($data);
        $res['data'] = $resourse;
        return $this->respond($res);
    }


    public function getDetailRI(Request $r)
    {

        $ruanganTersedia = $this->getRuanganRanap();

        $now = $this->hari_ini(date('Y-m-d'));
        $jadwal  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                DB::raw("lower(jd.hari) as hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->where('jd.hari', 'ilike', '%' . $now . '%')
            ->where('jd.statusenabled', '=', 'true')
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRanapFix')));

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $jadwal = $jadwal->whereIn('ru.id', explode(',', $r['ruanganid']));
        } else {
            $jadwal = $jadwal->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $jadwal = $jadwal->where('pg.namalengkap', 'ilike',  '%' . $r['namadokter'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $jadwal = $jadwal->limit($r['limit']);
        }
        $jadwal->orderBy('pg.namalengkap');
        $jadwal =  $jadwal->get();
        foreach ($jadwal as $d) {
            $d->hari = $now;
        }

        $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
        $SET['idStatusBedIsi'] =  $this->settingFix('idStatusBedIsi');

        $data = DB::table('tempattidur_m as tt')
            ->join('kamar_m as kmr', 'kmr.id', 'tt.objectkamarfk')
            ->join('ruangan_m as ru', 'ru.id', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', 'kmr.objectkelasfk')
            ->select(
                'ru.id',
                'ru.namaruangan',
                'kl.namakelas',
                'kmr.namakamar',
                DB::raw("
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong]  then  1 else 0 end ) as isi,
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedIsi] then  1 else 0 end ) as kosong,
                    sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong] then  1 else 0 end ) +
                    sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end ) as total
                    ")
            )
            ->where('tt.kdprofile', $this->kdProfile)
            ->where('kmr.statusenabled', true)
            ->where('tt.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'));
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('ru.id', explode(',', $r['ruanganfk']));
        } else {
            // $data = $data->whereIn('ru.id', $ruanganTersedia);
        }
        $data = $data->groupBy('ru.id', 'ru.namaruangan', 'kl.namakelas', 'kmr.namakamar');
        $data = $data->get();

        $bedIsi = 0;
        $bedKosong = 0;
        foreach ($data as $d) {
            $bedKosong = $bedKosong + $d->kosong;
            $bedIsi = $bedIsi + $d->isi;
        }
        // $data  =  DB::select(DB::raw("
        //     select
        //     ru.id,
        //     ru.namaruangan,
        //     kl.namakelas,
        //     kmr.namakamar,
        //     sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong]  then  1 else 0 end )as isi,
        //     sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end )as kosong,
        //     sum(case when tt.objectstatusbedfk = $SET[idStatusBedKosong] then  1 else 0 end ) +
        //     sum(case when tt.objectstatusbedfk =$SET[idStatusBedIsi] then  1 else 0 end ) as total
        //     from tempattidur_m as tt
        //     join kamar_m as kmr on kmr.id =tt.objectkamarfk
        //     join ruangan_m as ru on ru.id = kmr.objectruanganfk
        //     join kelas_m as kl on kl.id = kmr.objectkelasfk
        //     where tt.kdprofile =  $this->kdProfile
        //     and kmr.statusenabled =TRUE
        //     and tt.statusenabled=TRUE
        //     and ru.statusenabled =TRUE
        //     and ru.objectdepartemenfk in ( $dep)
        //     $ruang
        //     GROUP BY ru.id,ru.namaruangan,kl.namakelas,kmr.namakamar"));

        // $bedIsi = 0;
        // $bedKosong = 0;
        // foreach ($data as $d) {
        //     $bedKosong = $bedKosong + $d->kosong;
        //     $bedIsi = $bedIsi + $d->isi;
        // }

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
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->where('spd.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $produk = $produk->where('ru.id', '=',  $r['ruanganid']);
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

        $PasienPulang = DB::table('pasiendaftar_t as pd')
            ->where('pd.kdprofile', $this->kdProfile)
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->where('pd.statusenabled', true)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))

            ->whereNotNull('pd.tglpulang');
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $PasienPulang = $PasienPulang->where('pd.objectruanganlastfk', '=',  $r['ruanganid']);
        }
        if (isset($r['tgl']) && $r['tgl'] != '') {
            $PasienPulang = $PasienPulang->whereRaw("to_char(pd.tglpulang,'yyyy-MM-dd') = '$r[tgl]'");
        }
        $PasienPulang = $PasienPulang->count();

        $PasienRawat = PasienDaftar::where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->whereNull('tglpulang');
        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $PasienRawat = $PasienRawat->where('objectruanganlastfk', '=',  $r['ruanganid']);
        }
        $PasienRawat = $PasienRawat->count();

        $jumlahDokter = DB::table('jadwaldokter_m as jd')
            ->where('jd.kdprofile', $this->kdProfile)
            ->join('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->where('jd.statusenabled', true)
            ->whereIN('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')));

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $jumlahDokter = $jumlahDokter->where('jd.objectruanganfk', '=',  $r['ruanganid']);
        }
        $jumlahDokter = $jumlahDokter->count();


        $res['jadwal'] = $jadwal;
        $res['produk'] = $produk;
        $res['data'] = $data;
        $res['totalPulang'] = $PasienPulang;
        $res['totalRawat'] = $PasienRawat;
        $res['jumlahDokter'] =  $jumlahDokter;
        // $res['tempatTidur'] = $tempatTidur;
        $res['totalBedIsi'] = $bedIsi;
        $res['totalBedKosong'] = $bedKosong;
        return $this->respond($res);
    }

    public function Ruangandropdown(Request $r)
    {
        $res['namadepartemen'] = Departemen::mine()->get();

        return $this->respond($res);
    }

    public function BatalRawatInap(Request $request)
    {
        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];

            $pd = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
                ->join('ruangan_m as rutu', 'rutu.id', '=', 'pd.objectruanganlastfk')
                ->select(
                    'ru.objectdepartemenfk',
                    'pd.norec',
                    'pd.objectruanganasalfk',
                    'pd.objectruanganlastfk',
                    'rutu.namaruangan as ruanganTerakhir',
                    'ru.namaruangan as ruanganSebelumnya',
                    'pd.objectkelasfk',
                )
                ->where('pd.norec', $r_NewPD['norec_pd'])
                ->where('pd.kdprofile', $this->kdProfile)
                ->first();

            $apd = DB::table('antrianpasiendiperiksa_t as apd')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                ->select(
                    'ru.objectdepartemenfk',
                    'apd.norec',
                    'apd.objectruanganfk',
                    'apd.objectruanganasalfk',
                    'apd.objectkelasfk',
                    'apd.nobed',
                    'pd.norec',
                    'apd.objectkamarfk'
                )
                ->where('apd.noregistrasifk', $r_NewPD['norec_pd'])
                ->where('apd.kdprofile', $this->kdProfile)
                ->first();

            $cekPP = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();
            $cekSR = StrukResep::where('pasienfk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();

            if (!empty($cekPP)) {
                DB::rollBack();
                $transMessage = 'Pasien sudah Mendapatkan Pelayanan : '
                    . 'Pada : ' . $cekPP->tglpelayanan;
                $result = array("status" => 400, "result"  => $cekPP);
                return $this->respond($result['result'], $result['status'], $transMessage);
            } elseif (!empty($cekSR)) {
                $message = 'Pasien sudah Mendapatkan Resep!';
                return $this->respond($message);
            } else {
                PasienDaftar::where('norec', $r_NewPD['norec_pd'])
                    ->where('kdprofile', $kdProfile)
                    ->update([
                        'statusenabled' => true,
                        'objectruanganlastfk' => $pd->objectruanganasalfk,
                        'objectruanganasalfk' => $pd->objectruanganasalfk,
                        'catatan' => null,
                        'tglpulang' => date('Y-m-d H:i:s'),
                    ]);



                $spd = AntrianPasienDiperiksa::where('norec', $r_NewAPD['norec_apd'])->first();

                $SET =  $this->settingFix('idStatusBedKosong');
                TempatTidur::where('id', $spd->nobed)
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' => $SET]);

                $this->historyBED([
                    "tempattidurfk" =>   $spd->nobed,
                    "statusbedfk" => $SET,
                    "ruanganfk" => $spd->objectruanganfk,
                    "kamarfk" => $spd->objectkamarfk,
                ]);

                AntrianPasienDiperiksa::where('noregistrasifk', $r_NewPD['norec_pd'])
                    ->whereNotNull('nobed')
                    ->where('kdprofile', $kdProfile)
                    ->update(['statusenabled' => false]);

                AntrianPasienDiperiksa::where('noregistrasifk', $r_NewPD['norec_pd'])
                    ->where('objectruanganfk', $pd->objectruanganasalfk)
                    ->where('kdprofile', $kdProfile)
                    ->update(['tglkeluar' => null]);

                $message = 'Berhasil Batal Rawat Inap';
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $message,
                "result" => 'Berhasil Batal Rawat Inap'
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went Wrong",
                "result"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $result['message']);
    }

    public function SaveSuratKeteranganDokter(Request $request)
    {
        DB::beginTransaction();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSehat', $this->kdProfile);
        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('id', $kdJenisSurat)
            ->first();
        $kodeSurat = '';

        if (!empty($kdJenisSurat)) {
            $kodeSurat = $jenisSurat->kodeexternal;
        }

        try {
            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4, '', $this->kdProfile);
                $SKD = new SuratKeterangan();
                $norecNew = $SKD->generateNewId();
                $SKD->kdprofile = $this->kdProfile;
                $SKD->norec = $norecNew;
                $SKD->nosurat = $genSurat['nosurat'];
                $SKD->nosint = $genSurat['noint'];
                $SKD->statusenabled = true;
                $SKD->jenissuratfk = $kdJenisSurat;
            } else {
                $SKD =  SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
            }

            $SKD->pegawaifk = $this->getPegawaiId();
            $SKD->tglsurat =  date('Y-m-d H:i:s');
            $SKD->pasiendaftarfk = $request['norec_pd'];
            $SKD->dokterfk = $request['dokterfk'];
            $SKD->tinggibadan = $request['tinggibadan'];
            $SKD->beratbadan = $request['beratbadan'];
            $SKD->tekanandarah = $request['tekanandarah'];
            $SKD->denyutjantung = $request['denyutjantung'];
            $SKD->save();
            $norecSKD = $SKD->norec;

            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Simpan Berhasil",
                "data" => $norecSKD
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => "Simpan Gagal",
                "data" => $norecSKD
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function SaveSuratKeteranganSakit(Request $request)
    {

        DB::beginTransaction();

        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganSakit', $this->kdProfile);

        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('kdprofile', $this->kdProfile)
            ->where('id', $kdJenisSurat)
            ->first();
        if (!empty($jenisSurat)) {
            $kodeSurat = $jenisSurat->kodeexternal;
        }

        try {

            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4, '', $this->kdProfile);
                $SKS = new SuratKeterangan();
                $norecNew = $SKS->generateNewId();
                $SKS->kdprofile = $this->kdProfile;
                $SKS->norec = $norecNew;
                $SKS->nosurat = $genSurat['nosurat'];
                $SKS->nosint = $genSurat['noint'];
                $SKS->statusenabled = true;
                $SKS->jenissuratfk = $kdJenisSurat;
            } else {
                $SKS =  SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $this->kdProfile)->first();
            }
            $SKS->keterangan = $request['keterangan'];
            $SKS->diagnosa = $request['diagnosa'];
            $SKS->indikasi = $request['indikasi'];
            $SKS->hasilpemeriksaan = $request['hasilpemeriksaan'];
            $SKS->tglkontrol = $request['tglkontrol'];
            $SKS->tglawal = $request['tglijinawal'];
            $SKS->tglakhir = $request['tglijinakhir'];
            $SKS->pegawaifk = $this->getPegawaiId();
            $SKS->tglsurat =  date('Y-m-d H:i:s');
            $SKS->pasiendaftarfk = $request['norec_pd'];
            $SKS->dokterfk = $request['dokterfk'];
            $SKS->save();
            $norecSKS = $SKS->norec;
            DB::commit();

            $respond = [
                "status" => 200,
                "message" => "Simpan Berhasil",
                "data" => $norecSKS
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $respond = [
                "status" => 401,
                "message" => "Something want wrong",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }

    public function getDataSuratKeterangan(Request $request)
    {

        $kdJenisSurat = (int) $this->settingFix($request['jenissurat'], $this->kdProfile);

        $data = DB::table('suratketerangan_t as sk')
            ->join('pasiendaftar_t as pd', function ($join) {
                $join->on('pd.norec', '=', 'sk.pasiendaftarfk')->on('pd.kdprofile', '=', 'sk.kdprofile');
            })
            ->join('pasien_m as pm', function ($join) {
                $join->on('pm.id', '=', 'pd.nocmfk')->on('pm.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('alamat_m as alm', function ($join) {
                $join->on('alm.nocmfk', '=', 'pm.id')->on('alm.kdprofile', '=', 'pm.kdprofile');
            })
            ->join('pegawai_m as pg', function ($join) {
                $join->on('pg.id', '=', 'sk.dokterfk')->on('pg.kdprofile', '=', 'sk.kdprofile');
            })
            ->join('jeniskelamin_m as jk', function ($join) {
                $join->on('jk.id', '=', 'pm.objectjeniskelaminfk')->on('jk.kdprofile', '=', 'pm.kdprofile');
            })
            ->select(DB::raw("sk.*,pm.nocm,pd.noregistrasi,pm.namapasien,pm.tgllahir,pm.tempatlahir,jk.jeniskelamin,alm.alamatlengkap,sk.diagnosa"))
            ->where('sk.kdprofile', $this->kdProfile)
            ->where('sk.jenissuratfk', $kdJenisSurat)
            ->where('sk.pasiendaftarfk', $request['norec_pd'])
            ->first();
        return $this->respond($data);
    }
    public function getPendapatan(Request $request)
    {
        $datas = [
            'jumlahPasien' => $this->countPasien($request['idpegawai']),
            'jumlahTindakan' => $this->countPasienTindakan($request['idpegawai']),
            'pendapatanJasa' => $this->countJasaPelayanan($request['idpegawai'])
        ];
        return $this->respond($datas);
    }

    public function countPasien($pegawaifk)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            ->distinct('pd.noregistrasi')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', '=', 'ru.id')
            ->where('apd.statusenabled', true)
            ->where('apd.kdprofile', $this->kdProfile)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->whereNull('apd.tglkeluar')
            ->where('pd.statusenabled', 't')
            ->where('apd.statusenabled', 't')
            ->whereNull('pd.tglpulang');
        if ($pegawaifk) {
            $data = $data->where('pd.objectpegawaifk', $pegawaifk);
        }
        $data = $data->count();
        return $data;
    }

    public function countPasienTindakan($pegawaifk)
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
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereNull('apd.tglkeluar')
            ->whereNull('pd.tglpulang');
        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }
        $data = $data->count();

        return $data;
    }

    public function countJasaPelayanan($pegawaifk)
    {
        $data =  DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasi', 'pd.noregistrasi')
            ->join('ruangan_m as ru', 'apd.objectruanganfk', 'ru.id')
            ->join('pelayananpasiendetail_t as ppd', 'ppd.noregistrasi', 'pd.noregistrasi')
            ->join('pelayananpasienpetugas_t as ppp', 'ppp.noregistrasi', 'pd.noregistrasi')
            // ->join('produk_m as pr','pr.id','ppd.produkfk')
            ->select('ppd.hargajual')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pp.statusenabled', true)
            ->where('ppp.statusenabled', true)
            ->where('ppd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereNull('apd.tglkeluar')
            ->whereNull('pd.tglpulang')
            ->where('ppd.komponenhargafk', $this->settingFix('kdJasaPelayanan'))
            ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')));

        if ($pegawaifk) {
            $data = $data->where('ppp.objectpegawaifk', $pegawaifk);
        }

        $data = $data->sum('pp.hargajual');

        return $data;
    }

    public function getRiwayatMutasiRanap(Request $r)
    {

        $dateBetween = [$r->tglAwal, $r->tglAkhir];
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('kamar_m as kmr', 'kmr.objectkelasfk', 'kls.id')
            ->join('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganasalfk');
            })
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as rulast', 'rulast.id', '=', 'pd.objectruanganasalfk')
            ->join('tempattidur_m as tt', 'tt.id', 'apd.nobed')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('pegawai_m as pg2', 'pd.objectpegawairawatbersamafk', '=', 'pg2.id')
            ->leftjoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('kotakabupaten_m as kbp', 'alm.objectkotakabupatenfk', '=', 'kbp.id')
            ->leftjoin('kecamatan_m as km', 'alm.objectkecamatanfk', '=', 'km.id')
            ->leftjoin('desakelurahan_m as ds', 'alm.objectdesakelurahanfk', '=', 'ds.id')
            ->select(
                DB::raw("
                DISTINCT on (apd.noregistrasifk)
                ru.statusenabled,
                kls.namakelas,
                kmr.namakamar,
                apd.norec as norec_apd,
                pd.norec as norec_pd,
                pd.nocmfk,
                ps.nocm,
                ds.namadesakelurahan,
                km.namakecamatan,
                apd.tglmasuk,
                apd.tglkeluar,
                kbp.namakotakabupaten,
                ps.nobpjs,
                ps.alamatrmh,
                ru.namaruangan as ruanganSekarang,
                rulast.namaruangan as ruanganAsal,
                ps.noidentitas,
                ru.objectdepartemenfk,
                tt.reportdisplay,
                pd.objectruanganlastfk,
                pd.objectpegawaifk,
                pd.objectpegawairawatbersamafk,
                pd.noregistrasi,
                pg.namalengkap,
                pd.objectruanganasalfk,
                pg2.namalengkap as nama,
                ps.namapasien,
                CAST(pd.tglregistrasi AS DATE)
                ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->whereRaw("CAST(pd.objectruanganlastfk AS INT) != CAST(pd.objectruanganasalfk AS INT)")
            ->where('ru.objectdepartemenfk',  $this->settingFix('idDepRawatInap'))
            ->where('rulast.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglkeluar AS DATE)"), $dateBetween)
            // ->whereNull('apd.tglkeluar')
            ->where('apd.kdprofile', $this->kdProfile);
        // ->whereNull('pd.tglpulang');

        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->whereIn('pd.objectruanganasalfk', explode(',', $r['ruanganfk']));
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTermMutasi = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTermMutasi) {
                $query->where('pd.noregistrasi', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.nocm', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.namapasien', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.nobpjs', 'ilike', $searchTermMutasi)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTermMutasi);
            });
        }

        $data = $data->get();
        foreach ($data as $d) {
            $d->lamarawat =  $this->getAge($d->tglmasuk, $d->tglkeluar ? $d->tglkeluar : date('Y-m-d H:i:s'));
        }

        return $this->respond($data);
    }

    public function getDaftarPasienRawatInap(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as rm1', 'rm1.id', '=', 'pd.objectruanganasalfk')
            ->join('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->join('tempattidur_m as tp', 'tp.id', '=', 'apd.nobed')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'pd.asalrujukanfk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                'ps.tgllahir',
                'pd.tglregistrasi as tglmasuk',
                'kp.kelompokpasien as jenis_pasien',
                'rm.namaruangan as namaruanganrawat',
                'rm1.namaruangan as namaruanganasal',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                'km.namakamar',
                DB::raw("EXTRACT(DAY FROM ((CASE WHEN pd.tglpulang IS NULL THEN CURRENT_TIMESTAMP ELSE pd.tglpulang END) - pd.tglregistrasi)) || ' Hari' AS lamarawat"),
                'tp.reportdisplay as bed',
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                'pd.statuspasien',
                'ar.asalrujukan',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('apd.tglkeluar', null)
            ->where('pd.tglpulang', null)
            ->where('rm.objectdepartemenfk', '=', 16);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rm.id', '=', $request['ruanganId']);
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
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('rm.namaruangan');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function kirimWASuratDokterRanap(Request $request)
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

                    $finalLink = "https://rsdgunungjati.com/service/dashboard/registrasi/cetak-surat-keterangan-dokter?&noregistrasi={$datapasien->noregistrasi}&dokter={$datapasien->dokter}&kelompokpasien={$datapasien->kelompokpasien}&objectdepartemenfk=16&tglregistrasi={$datapasien->tglregistrasi}&norec_pd={$datapasien->norec}&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzUxMDE2NDN9.y2rs5nFOX8J4PK63Uxv_Df1AFY7MoDR1e8WmdLxDmxtolZNg9XrylQgMl2W5Ng1MYhIxgXYwnjEqbD9If_8vyw.MQ==";

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
