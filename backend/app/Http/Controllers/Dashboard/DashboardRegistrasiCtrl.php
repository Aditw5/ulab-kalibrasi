<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Master\Departemen;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Ruangan;
use App\Models\Master\Profile;
use App\Models\Master\SettingDataFixed;
use App\Models\Standar\MapLoginUserToRuangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\SuratKeterangan;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\TransaksiInfeksiPPI;
use App\Models\Master\TempatTidur;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Exception;

class DashboardRegistrasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getDropdown(Request $r)
    {
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
            ->where('pg.statusenabled', true);

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $jadwal = $jadwal->where('ru.id', $r['ruanganid']);
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


        $set = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        // array_push()
        $set2 = explode(',', $this->settingFix('listDepartemenPelayanan'));
        $res['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();
        $res['kelompokpasien'] = KelompokPasien::mine()->get();
        $res['departemen'] = Departemen::mine()->whereIn('id', $set2)->get();
        $res['jadwaldokter'] = $jadwal;

        return $this->respond($res);
    }
    public function dashboardRegis(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $reser = DB::table('antrianpasienregistrasi_t as apr')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['tgl']) && $r['tgl'] != '') {
            $reser = $reser->whereBetween('tanggalreservasi', [$r['tgl'] . ' 00:00', $r['tgl'] . ' 23:59']);
        }
        // ->where('tanggalreservasi', '>=', date('Y-m-d H:i:s'));
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $reser = $reser->where('apr.objectruanganfk', $r['ruanganfk']);
        }
        $reser = $reser->get();
        $regis  =  PasienDaftar::where('statusenabled', true)
            ->where('kdprofile', $kdProfile);
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $regis = $regis->where('objectruanganlastfk', $r['ruanganfk']);
        }
        if (isset($r['tgl']) && $r['tgl'] != '') {
            $regis = $regis->whereBetween('tglregistrasi', [$r['tgl'] . ' 00:00', $r['tgl'] . ' 23:59']);
        }
        $regis = $regis->get();
        $res['c_reservasi'] = 0;
        $res['c_antrian'] = 0;
        $res['c_registrasi'] = 0;
        $res['c_dilayani'] = 0;
        foreach ($regis as $d) {
            $res['c_registrasi'] =   $res['c_registrasi'] + 1;
            if ($d->ispelayananpasien) {
                $res['c_dilayani'] =   $res['c_dilayani'] + 1;
            }
        }
        foreach ($reser as $d) {
            if ($d->iskiosk) {
                $res['c_antrian'] =  $res['c_antrian'] + 1;
            } else {
                $res['c_reservasi'] =   $res['c_reservasi'] + 1;
            }
        }
        return $this->respond($res);
    }
    public function daftarReservasi(Request $r)
    {
        $count = 0;
        $data  = DB::table('antrianpasienregistrasi_t as apr')
            ->leftjoin('pasien_m as ps', 'apr.nocmfk', '=', 'ps.id')
            ->leftjoin('ruangan_m as ru', 'apr.objectruanganfk', '=', 'ru.id')
            ->leftjoin('pegawai_m as pg', 'apr.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('kelompokpasien_m as kp', 'apr.objectkelompokpasienfk', '=', 'kp.id')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apr.pasiendaftarfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->select(
                'apr.*',
                'apr.pasiendaftarfk as norec_pd',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nohp',
                'ps.nobpjs',
                'ps.tgllahir',
                'alm.alamatlengkap',
                'ru.namaruangan',
                'pg.namalengkap as dokter',
                'kp.kelompokpasien',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'jk.jeniskelamin',
                'pd.noregistrasi',
            DB::raw("case when ps.namapasien is null then apr.namapasien else ps.namapasien end as namapasien"))
            ->where('apr.statusenabled', true)
            ->where('apr.noreservasi', '!=', '-')
            ->where('apr.kdprofile', $this->kdProfile);

        if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->whereBetween('apr.tanggalreservasi', [$r['dari'],  $r['sampai']]);
        }
        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                      ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                      ->orWhere('ps.nocm', 'ilike', $searchTerm)
                      ->orWhere('ps.nobpjs', 'ilike', $searchTerm)
                      ->orWhere('apr.noreservasi', 'ilike', $searchTerm)
                      ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $data = $data->where('ps.nocm', '=',  $r['nocm']);
        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $data = $data->where('ps.noidentitas', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != '') {
            $data = $data->whereIN('apr.objectkelompokpasienlastfk',  explode(',', $r['kelompokpasienfk']));
        }
        if (isset($r['ruanganfk']) && $r['ruanganfk'] != '') {
            $data = $data->where('apr.objectruanganfk',  $r['ruanganfk']);
        }
        if (isset($r['_total']) && $r['_total'] != '') {
            $count = $data->count();
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data = $data->orderBy('apr.tanggalreservasi');
        $data = $data->get();

        foreach ($data as $d) {
            $d->umur =  $this->getAge($d->tgllahir,   date('Y-m-d H:i:s'));
        }
        if (count($data) > 0) {
            $price = array();
            $data = $data->toArray();
            foreach ($data as $key => $row) {
                $price[$key] = $row->tanggalreservasi;
            }
            array_multisort($price, SORT_DESC, $data);
        }
        $res['data'] = $data;
        $res['total'] = $count;
        return $this->respond($res);
    }
    public function hapusReservasi(Request $r)
    {
        DB::beginTransaction();
        try {

            $apr =  AntrianPasienRegistrasi::where('norec', $r['norec'])->first();
            if ($apr->nocmfk != null) {
                $ps = Pasien::where('id', $apr->nocmfk)->first();
                $this->LOGGING(
                    'Reservasi Online',
                    $apr->norec,
                    'antrianpasienregistrasi_t',
                    'Hapus Reservasi pada Pasien ' .
                        $ps->namapasien . ' (' . $ps->nocm . ') - ' . $apr->noreservasi
                );
            } else {
                $this->LOGGING(
                    'Reservasi Online',
                    $apr->norec,
                    'antrianpasienregistrasi_t',
                    'Hapus Reservasi pada Pasien ' .
                        $apr->namapasien . ' - ' . $apr->noreservasi
                );
            }


            AntrianPasienRegistrasi::where('norec', $r['norec'])->update([
                'statusenabled' => false,
            ]);
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

    public function cetakLabelPasien(Request $request)
    {
        $kdProfile                  = $this->kdProfile;
        $dokter                     = $request['dokter'];
        $kelompokpasien             = $request['kelompokpasien'];
        $objectdepartemenfk         = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi              = $request['tglregistrasi'];
        $tglAyeuna                  = date('d/m/Y');
        $tglAyeuna                  = date('Y-m-d H:i:s');
        $print                      = false;
        $profile                    = Profile::where('id', $this->kdProfile)->first();
        $noregistrasi               = explode(',', $request['noregistrasi']);
        $dataPasien = DB::table('pasiendaftar_t AS pd')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pm.nohp as notelepon',
                DB::raw("'*' || pm.nocm || '*' AS barcode"),
                'jk.reportdisplay AS jeniskelamin',
                DB::raw("CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik"),
                DB::raw("to_char(pm.tgllahir, 'DD-MM-YYYY') AS tanggal_lahir"),
                DB::raw("EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur"),
                DB::raw("CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin"),
                'pd.tglregistrasi',
                'pg.namalengkap as dokter',
                'dep.namadepartemen'
            )
            ->join('pasien_m AS pm', 'pd.nocmfk', 'pm.id')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->join('departemen_m AS dep', 'dep.id', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 850;

        $dataReport = array(
            'data' => $dataPasien,
        );
        $request['pdf']  = true;
        $judul = 'Cetak Label Pasien';
        $blade = 'report.registrasi.cetak-label-pasien';
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $request,
                    'dataPasien' => $dataPasien
                )
            );
            return $pdf->stream();
        }
        return view(
            $blade,
            compact('dataPasien', 'pageWidth', 'request')
        );
    }
    public function cetakLabelODC(Request $request)
    {
        $kdProfile                  = $this->kdProfile;
        $dokter                     = $request['dokter'];
        $kelompokpasien             = $request['kelompokpasien'];
        $objectdepartemenfk         = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi              = $request['tglregistrasi'];
        $tglAyeuna                  = date('d/m/Y');
        $tglAyeuna                  = date('Y-m-d H:i:s');
        $print                      = false;
        $profile                    = Profile::where('id', $this->kdProfile)->first();
        $noregistrasi               = explode(',', $request['noregistrasi']);
        $dataPasien = DB::table('pasiendaftar_t AS pd')
            ->select(
                'pm.nocm',
                'pm.namapasien',
                'pm.nohp as notelepon',
                DB::raw("'*' || pm.nocm || '*' AS barcode"),
                'jk.reportdisplay AS jeniskelamin',
                DB::raw("CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik"),
                DB::raw("to_char(pm.tgllahir, 'DD-MM-YYYY') AS tanggal_lahir"),
                DB::raw("EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur"),
                DB::raw("CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin"),
                'pd.tglregistrasi',
                'pg.namalengkap as dokter',
                'dep.namadepartemen'
            )
            ->join('pasien_m AS pm', 'pd.nocmfk', 'pm.id')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m AS alm', 'alm.nocmfk', 'pm.id')
            ->join('ruangan_m AS ru', 'ru.id', 'pd.objectruanganlastfk')
            ->join('departemen_m AS dep', 'dep.id', 'ru.objectdepartemenfk')
            ->join('kelompokpasien_m AS kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('pegawai_m AS pg', 'pg.id', 'pd.objectpegawaifk')
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.statusenabled', true)
            ->first();

        $pageWidth = 850;

        $dataReport = array(
            'data' => $dataPasien,
        );
        $request['pdf']  = true;
        $judul = 'Cetak Label Pasien';
        $blade = 'report.registrasi.cetak-label-pasien-odc';
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('A4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $request,
                    'dataPasien' => $dataPasien
                )
            );
            return $pdf->stream();
        }
        return view(
            $blade,
            compact('dataPasien', 'pageWidth', 'request')
        );
    }
    public function cetakKartuPasien(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            pd.tglregistrasi,
            alm.alamatlengkap as alamatlengkap,
            des.namadesakelurahan as desa,
            kec.namakecamatan as kecamatan,
            kab.namakotakabupaten as kota,
            prov.namapropinsi as province
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            LEFT JOIN desakelurahan_m AS des ON alm.objectdesakelurahanfk = des.id
            LEFT JOIN kecamatan_m AS kec ON alm.objectkecamatanfk = kec.id
            LEFT JOIN kotakabupaten_m AS kab ON alm.objectkotakabupatenfk = kab.id
            LEFT JOIN propinsi_m AS prov ON alm.objectpropinsifk = prov.id

        WHERE
          (pd.noregistrasi = '$request[noregistrasi]' or pd.norec = '$request[norec_pd]')
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        ");
        $pageWidth = 950;
        $dataReport = array(
            'data' => $dataPasien
        );
        $res['pdf']  = false;

        $judul = 'Cetak Kartu Pasien';

        // dd($dataReport['data']);

        $blade = 'report.registrasi.cetak-kartu-pasien';
        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
        if ($res['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade,
                array(
                    'dataReport' => $dataReport,
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'judul' => $judul,
                )
            );
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }
    public function getDataSuratKeteranganDokterAsli(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        // $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : ($request['objectdepartemenfk'] == 9 ? 'Gawat Darurat' : ''));
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            pm.tempatlahir,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            pm.tgllahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin,
            kp.kelompokpasien,
            alm.alamatlengkap,
            pd.tglregistrasi,
			dds.keterangan,
			dam.reportdisplay
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk and  pd.kdprofile = ru.kdprofile
            INNER JOIN departemen_m AS dep ON dep.ID = ru.objectdepartemenfk and  ru.kdprofile = dep.kdprofile
            INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk and  pd.kdprofile = kp.kdprofile
            LEFT JOIN rekanan_m as rk on rk.id = pd.objectrekananfk and  pd.kdprofile = rk.kdprofile
            LEFT JOIN detaildiagnosapasien_t as dds on dds.noregistrasi = pd.noregistrasi
            LEFT JOIN diagnosa_m as dam on dam.id = dds.objectdiagnosafk
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            and pd.kdprofile =$kdProfile
            
            and pd.statusenabled=true
        LIMIT 1
        ");

        // tadinya ada and dds.objectjenisdiagnosafk = 1 pada query

        $pageWidth = 950;

        $dataReport = array(
            'data' => $dataPasien,
            'dokter' => $dokter,
            'kelompokpasien' => $kelompokpasien,
            'objectdepartemenfk' => $objectdepartemenfk,
            'tglregistrasi' => $tglregistrasi,
        );

        $res['pdf']  = false;

        $judul = 'Cetak Keterangan Dokter';

        // dd($dataReport);

        $blade = 'report.registrasi.cetak-surat-keterangan-dokter';
        if ($res['pdf']) {
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
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }
    public function getDataSuratKeteranganKeluar(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            pm.tempatlahir,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            pm.tgllahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin,
            kp.kelompokpasien,
            alm.alamatlengkap,
            pd.tglregistrasi,
			dds.keterangan,
			dam.reportdisplay
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk and  pd.kdprofile = ru.kdprofile
            INNER JOIN departemen_m AS dep ON dep.ID = ru.objectdepartemenfk and  ru.kdprofile = dep.kdprofile
            INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk and  pd.kdprofile = kp.kdprofile
            LEFT JOIN rekanan_m as rk on rk.id = pd.objectrekananfk and  pd.kdprofile = rk.kdprofile
            LEFT JOIN detaildiagnosapasien_t as dds on dds.noregistrasi = pd.noregistrasi
            LEFT JOIN diagnosa_m as dam on dam.id = dds.objectdiagnosafk
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        ");

        $pageWidth = '950px';

        $dataReport = array(
            'data' => $dataPasien,
            'dokter' => $dokter,
            'kelompokpasien' => $kelompokpasien,
            'objectdepartemenfk' => $objectdepartemenfk,
            'tglregistrasi' => $tglregistrasi
        );

        $res['pdf']  = false;

        $judul = 'Cetak Rujukan Keluar';

        // dd($dataReport);

        $blade = 'report.registrasi.cetak-surat-pengantar-keluar';
        if ($res['pdf']) {
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
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }
    public function getDataBillingBpjs(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $dokter = $request['dokter'];
        $kelompokpasien = $request['kelompokpasien'];
        $objectdepartemenfk = $request['objectdepartemenfk'] == 18 ? 'Rawat Jalan' : ($request['objectdepartemenfk'] == 16 ? 'Rawat Inap' : '');
        $tglregistrasi = $request['tglregistrasi'];
        $tglAyeuna = date('d/m/Y');
        $tglAyeuna = date('Y-m-d H:i:s');
        $print = false;
        $profile = Profile::where('id', $this->kdProfile)->first();

        $dataPasien = DB::select("
        SELECT
            pm.nocm,
            pm.namapasien,
            '*' || pm.nocm || '*' AS barcode,
            jk.reportdisplay AS jeniskelamin,
            CASE WHEN pm.noidentitas IS NULL THEN '-' ELSE pm.noidentitas END AS nik,
            to_char( pm.tgllahir, 'DD-MM-YYYY' ) AS tanggal_lahir,
            EXTRACT (YEAR FROM AGE(pm.tgllahir )) || ' Thn ' as umur,
            CASE WHEN rk.namarekanan IS NULL THEN kp.kelompokpasien ELSE rk.namarekanan END AS penjamin,
            kp.kelompokpasien,
            alm.alamatlengkap,
            pd.tglregistrasi
        FROM
            pasiendaftar_t pd
            INNER JOIN pasien_m pm ON pd.nocmfk = pm.id
            LEFT JOIN jeniskelamin_m jk ON jk.ID = pm.objectjeniskelaminfk and  jk.kdprofile = pm.kdprofile
            LEFT JOIN alamat_m AS alm ON alm.nocmfk = pm.id and  alm.kdprofile = pm.kdprofile
            INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk and  pd.kdprofile = ru.kdprofile
            INNER JOIN departemen_m AS dep ON dep.ID = ru.objectdepartemenfk and  ru.kdprofile = dep.kdprofile
            INNER JOIN kelompokpasien_m as kp on kp.id = pd.objectkelompokpasienlastfk and  pd.kdprofile = kp.kdprofile
            LEFT JOIN rekanan_m as rk on rk.id = pd.objectrekananfk and  pd.kdprofile = rk.kdprofile
        WHERE
            pd.noregistrasi = '$request[noregistrasi]'
            and pd.kdprofile =$kdProfile
            and pd.statusenabled=true
        ");

        $pageWidth = 950;

        $dataReport = array(
            'data' => $dataPasien,
            'dokter' => $dokter,
            'kelompokpasien' => $kelompokpasien,
            'objectdepartemenfk' => $objectdepartemenfk,
            'tglregistrasi' => $tglregistrasi
        );

        $res['pdf']  = false;

        $judul = 'Cetak Label Pasien';

        // dd($dataReport);

        $blade = 'report.registrasi.cetak-billing-bpjs';
        if ($res['pdf']) {
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
            return $pdf->stream();
        }


        // dd($dataReport);

        return view(
            $blade,
            compact('dataReport', 'profile', 'pageWidth', 'print', 'res', 'judul')
        );
    }

    public function saveBatalRegis(Request $request)
    {

        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $deleteAdminAuto = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])
            ->where('statusenabled', true)
            ->where('isadministrasi', true)
            ->get();

            foreach ($deleteAdminAuto as $item) {
                PelayananPasienDetail::where('pelayananpasien',$item->norec)->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasienDetail::where('pelayananpasien',$item->norec)->where('kdprofile', $this->kdProfile)->delete();
                PelayananPasien::where('norec',$item->norec)->where('kdprofile', $this->kdProfile)->delete();
            }
            $cekPP = PelayananPasien::where('noregistrasifk', $r_NewAPD['norec_apd'])->where('statusenabled', true)->first();
            if ($cekPP) {
                $message = 'Pasien sudah mendapatkan Pelayanan ';
                DB::rollBack();

                $result = array(
                    "status" => 400,
                    "message" => $message,
                    "result"  => $cekPP
                );

                 return $this->respond($result['result'], $result['status'], $result['message']);
            } else {

                AntrianPasienDiperiksa::where('noregistrasifk', $r_NewPD['norec_pd'])
                    // ->whereNotNull('objectruanganasalfk')
                    ->where('kdprofile', $kdProfile)
                    ->update(['statusenabled' => false]);

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

                if($pd->objectruanganasalfk == $pd->objectruanganlastfk){
                    PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                        'tanggalpembatalan' => date('Y-m-d H:i:s'),
                        'alasanpembatalan' => $r_NewPD['alasanpembatalan'],
                        'statusenabled' => false,
                        'objectpegawaibatalfk' => $this->getPegawaiId(),
                    ]);
                }else{
                    PasienDaftar::where('norec', $r_NewPD['norec_pd'])
                        ->where('kdprofile', $kdProfile)
                        ->update([
                            'statusenabled' => true,
                            'objectruanganlastfk' => $pd->objectruanganasalfk,
                            'objectruanganasalfk' => $pd->objectruanganasalfk,
                            'tglpulang' => date('Y-m-d H:i:s'),
                        ]);
                }

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
                $message = 'Berhasil Batal Registrasi';
                $this->LOGGING(
                    'Batal Registrasi',
                    $r_NewPD['norec_pd'],
                    'pasiendaftar_t',
                    'Batal Registrasi ' . $r_NewPD['ruangan'] .' pada Pasien ' .
                    $r_NewPD['namapasien'] . ' (' . $r_NewPD['nocm'] . ') - ' . $r_NewPD['noregistrasi']
                );
            }
            $post = null;
            $post2 = null;
            $pasienDaftar = PasienDaftar::where('norec',$r_NewPD['norec_pd'])->first();
            if($pasienDaftar->antrianpasienregistrasifk != null){
                $apr = AntrianPasienRegistrasi::where('norec',$pasienDaftar->antrianpasienregistrasifk )->first();
                $apr->statusenabled= false;
                $apr->save();
                $waktukirim = strtotime(date('Y-m-d H:i:s')) * 1000;
                $json = array(
                    "kodebooking" => $apr->noreservasi,
                    "taskid" => 99, //pasien lama langsung task 99 //(tidak hadir/batal)
                    "waktu" => $waktukirim,
                );
                $post = $this->buildRequestbpjstools("antrean/updatewaktu", "antrean", "POST", $json);

                $jsons = array(
                    "kodebooking" => $apr->noreservasi,
                    "keterangan" => "Terjadi perubahan jadwal dokter, silahkan daftar kembali",
                );
                $post2 = $this->buildRequestbpjstools("antrean/batal", "antrean", "POST", $jsons);

            }
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => $message,
                "result" => array(
                    'antrol' => $post,
                    'postKeMJKN' => $post2
                )
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


    public function SaveSuratRegisRanap(Request $request)
    {

        DB::beginTransaction();
        // return $request['bantuanPelayanan'];
        try {
            PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(
                    [
                        'bantuanpelayanan' => $request['bantuanPelayanan'],
                        'bantuanpenerjemah' => $request['bantuanPenerjemah'],
                        'dikunjungi' => $request['dikunjungi'],
                        'bahasa' => $request['bahasa']
                    ]
                );
            $data =  PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->first();
            DB::commit();
            $respond = [
                "status" => 200,
                "message" => "Berhasil",
                "data" => $data,
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
    public function SaveTandaSelesai(Request $request)
    {
        DB::beginTransaction();

        try {
            PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['isselesai' => true]);

            $data = PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->first();

            DB::commit();

            $respond = [
                "status" => 200,
                "message" => "Berhasil Tanda Pasien",
                "data" => $data,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $respond = [
                "status" => 401,
                "message" => "Terjadi kesalahan",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }
    public function UpdateTandaPasienIGD(Request $request)
    {
        DB::beginTransaction();

        try {
            PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['isonsiteservice' => $request['isonsiteservice']]);

            $data = PasienDaftar::where('norec', $request['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->first();

            DB::commit();

            $respond = [
                "status" => 200,
                "message" => "Berhasil Tanda Pasien",
                "data" => $data,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $respond = [
                "status" => 401,
                "message" => "Terjadi kesalahan",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }
    public function SaveNoSITB(Request $request)
    {
        DB::beginTransaction();

        try {
            $r_NewPD = $request['pasiendaftar'];
            PasienDaftar::where('norec', $r_NewPD['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->update(['sitb_id' => $r_NewPD['sitb']]);

            $data = PasienDaftar::where('norec', $r_NewPD['norec'])
                ->where('statusenabled', true)
                ->where('kdprofile', $this->kdProfile)
                ->first();

            DB::commit();

            Pasien::where('id', $r_NewPD['nocmfk'])->update([
                'sitb_id' => isset($r_NewPD['sitb']) ? $r_NewPD['sitb'] : null
            ]);

            $respond = [
                "status" => 200,
                "message" => "Berhasil Input SITB Pasien",
                "data" => $data,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            $respond = [
                "status" => 401,
                "message" => "Terjadi kesalahan",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }

    public function OrderNoSITB(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            if ($request['norec_so'] == "") {
                $dataSO = new StrukOrder();
                $dataSO->norec = $dataSO->generateNewId();
                $dataSO->kdprofile = $kdProfile;
                $dataSO->statusenabled = true;
                $namaLog = 'Order No SITB';
                $noOrder = $this->SEQUENCE(new StrukOrder, 'noorder', 11, 'OS' . date('ym'), $kdProfile);
            } else {
                $namaLog = 'Order Ulang';
                $dataSO = StrukOrder::where('norec', $request['norec_so'])->where('kdprofile', $kdProfile)->first();
                $noOrder = $dataSO->noorder;
            }
            $dataSO->nocmfk = $request['nocmfk'];
            $dataSO->isdelivered = 1;
            $dataSO->noorder = $noOrder;
            $dataSO->noorderintern = $noOrder;
            $dataSO->noregistrasifk = $request['norec_pd'];
            $dataSO->objectpegawaiorderfk = $request['pegawaifk'];
            $dataSO->objectpetugasfk = $this->getPegawaiId();
            $dataSO->qtyjenisproduk = 0;
            $dataSO->qtyproduk = 0;
            $dataSO->statusorder = 0;
            $dataSO->objectruanganfk = $request['objectruanganlastfk'];
            $dataSO->objectruangantujuanfk = 155;
            $dataSO->objectkelompoktransaksifk = 432;
            $dataSO->tglorder = date('Y-m-d H:i:s');
            $dataSO->totalbeamaterai = 0;
            $dataSO->totalbiayakirim = 0;
            $dataSO->totalbiayatambahan = 0;
            $dataSO->totaldiscount = 0;
            $dataSO->totalhargasatuan = 0;
            $dataSO->totalharusdibayar = 0;
            $dataSO->totalpph = 0;
            $dataSO->totalppn = 0;
            $dataSO->save();

            Pasien::where('id', $request['nocmfk'])->update([
                'sitb_id' => "Menunggu Verifikasi"
            ]);

            $this->LOGGING(
                'Ordere SITB',
                $dataSO->norec,
                'strukorder_t',
                $namaLog . 'ke ruang ' . $request['ruangantujuan'] . ' pada Pasien ' .   $request['namapasien'] . ' (' . $request['nocm'] . ') - ' . $request['noregistrasi']
            );
            $transMessage = "Order Nomor SITB Berhasil, Menunggu Verifikasi";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "data" => $dataSO
                ),
            );
        } catch (\Exception $e) {

            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(), //$e->getMessage(). ' '. $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    // public function saveInfeksiPPI(Request $request)
    // {
    //     $kdProfile = (int) $this->kdProfile;
    //     DB::beginTransaction();
    //     try {
    //         $r_NewPPI = $request['datainfeksi'];
    //         if ($r_NewPPI['norec'] == "") {
    //             $dataPPI = new TransaksiInfeksiPPI();
    //             $dataPPI->norec = $dataPPI->generateNewId();
    //             $dataPPI->kdprofile = $kdProfile;
    //             $dataPPI->statusenabled = true;
    //         } else {
    //             $dataPPI = TransaksiInfeksiPPI::where('norec', $r_NewPPI['norec'])->where('kdprofile', $kdProfile)->first();
    //         }
    //         $dataPPI->noregistrasifk = $r_NewPPI['norec_pd'];
    //         $dataPPI->objectinfeksippifk = $r_NewPPI['infeksi'];
    //         // $dataPPI->objectpetugasfk = $this->getPegawaiId();
    //         $dataPPI->tanggalinput = date('Y-m-d H:i:s');
    //         $dataPPI->save();

    //         $this->LOGGING(
    //             'Simpan Infeksi PPI ',
    //             $dataPPI->norec,
    //             'transaksiinfeksippi_t',
    //             'Pada Pasien' . $r_NewPPI['namapasien'] .  'Dengan Registrasi' . $r_NewPPI['norec_pd']
    //         );
    //         $transMessage = "Simpan Infeksi PPI Berhasil";
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "as" => '@epic',
    //                 "data" => $dataPPI
    //             ),
    //         );
    //     } catch (\Exception $e) {

    //         $transMessage = "Simpan Gagal";
    //         DB::rollBack();
    //         $result = array(
    //             "status" => 400,
    //             "result"  => $e->getMessage() . ' ' . $e->getLine(), 
    //         );
    //     }
    //     return $this->respond($result['result'], $result['status'], $transMessage);
    // }

    public function saveInfeksiPPI(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            $r_NewPPI = $request['datainfeksi'];
            if ($r_NewPPI['norec'] == "") {
                $dataPPI = new TransaksiInfeksiPPI();
                $dataPPI->norec = $dataPPI->generateNewId();
                $dataPPI->kdprofile = $kdProfile;
                $dataPPI->statusenabled = true;
            } else {
                $dataPPI = TransaksiInfeksiPPI::where('norec', $r_NewPPI['norec'])
                    ->where('kdprofile', $kdProfile)
                    ->first();
            }
            $dataPPI->noregistrasifk = $r_NewPPI['norec_pd'];
            $dataPPI->objectinfeksippifk = $r_NewPPI['infeksi'];
            $dataPPI->nocmfk = $r_NewPPI['nocm'];
            $dataPPI->tanggalinput = date('Y-m-d H:i:s');
            $dataPPI->save();
    
            Pasien::where('nocm', $r_NewPPI['nocm'])->update([
                'objectinfeksippifk' => $r_NewPPI['infeksi']
            ]);
    
            $this->LOGGING(
                'Simpan Infeksi PPI ',
                $dataPPI->norec,
                'transaksiinfeksippi_t',
                'Pada Pasien' . $r_NewPPI['namapasien'] . ' Dengan Registrasi ' . $r_NewPPI['norec_pd']
            );
    
            $transMessage = "Simpan Infeksi PPI Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                    "data" => $dataPPI
                ),
            );
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(),
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    
    // START fungsi pendukung
    public function buildRequestbpjstools($url, $jenis, $method, $data) {
        $buildReq = new Request();
        $buildReq['url'] = $url;
        $buildReq['jenis'] = $jenis;
        $buildReq['method'] = $method;
        $buildReq['data'] = $data;
        $buildReq['user'] = $this->getNamaPegawai();
        $postBuild = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->bpjsTools($buildReq, false);
        $postBuild = json_decode($postBuild->content(), true);
        return $postBuild;
    }

    public function getAPD(Request $request){
        $apd = DB::table('antrianpasiendiperiksa_t AS apd')
        ->select('apd.norec as norec_apd','tglkeluar','tglmasuk')
        ->where('apd.noregistrasifk', $request['norec_pd'])
        ->where('apd.objectruanganfk', $request['objectruanganlastfk'])
        ->orderByDesc('tglmasuk')
        ->first();

        return $this->respond($apd);
    }
}
