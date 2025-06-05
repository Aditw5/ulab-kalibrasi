<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalRujukan;
use App\Models\Master\Departemen;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\KelompokTransaksi;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\RegistrasiPelayananPasien;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Master\Pasien;
use App\Models\Transaksi\PenandaPasien;
use App\Models\Transaksi\RawatBersama;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DaftarRegistrasiCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function listRegistrasi(Request $r)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelas_m as klstg', 'klstg.id', '=', 'asu.objectkelasdijaminfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec');
                $join->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
                $join->whereNull('apd.objectruanganasalfk');
            })
            ->leftjoin('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
            ->leftJOIN('jeniskelamin_m as jks', 'jks.id', '=', 'ps.objectjeniskelaminfk')
            ->distinct()
            ->select(
                'pd.norec',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'rek.namarekanan',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pa.norec as norec_pa',
                'pa.objectasuransipasienfk',
                'pd.objectkelompokpasienlastfk',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pa.nosep',
                'pd.nostruklastfk',
                'klstg.namakelas as kelasditanggung',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'pa.ppkrujukan',
                'ru.icons',
                'jpl.jenispelayanan',
                'pa.objectdiagnosafk as iddiagnosabpjs',
                'ps.nobpjs',
                'jks.jeniskelamin',
                'apd.norec as norec_apd'
            )

            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', (int) $this->kdProfile);

        if (isset($r['dari']) && $r['dari'] != "" && $r['dari'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $r['dari'] . ' 00:00');
        }
        if (isset($r['sampai']) && $r['sampai'] != "" && $r['sampai'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $r['sampai'] . ' 23:59');
        }
        if (isset($r['dep']) && $r['dep'] != "" && $r['dep'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk', '=', $r['dep']);
        }
        if (isset($r['ruang']) && $r['ruang'] != "" && $r['ruang'] != "undefined") {
            $data = $data->where('ru.id', '=', $r['ruang']);
        }
        if (isset($r['kelompokpasienfk']) && $r['kelompokpasienfk'] != "" && $r['kelompokpasienfk'] != "undefined") {

            $data = $data->whereIn('kp.id', explode(',', $r['kelompokpasienfk']));
        }
        if (isset($r['dokterfk']) && $r['dokterfk'] != "" && $r['dokterfk'] != "undefined") {
            $data = $data->where('pg.id', '=', $r['dokterfk']);
        }
        if (isset($r['statuspasien']) && $r['statuspasien'] != "" && $r['statuspasien'] != "undefined") {
            $data = $data->where('pd.statuspasien', '=', $r['statuspasien']);
        }
        if (isset($r['noreg']) && $r['noreg'] != "" && $r['noreg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $r['noreg']);
        }
        if (isset($r['nocm']) && $r['nocm'] != "" && $r['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');
        }
        if (isset($r['namapasien']) && $r['namapasien'] != "" && $r['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');
        }
        if (isset($r['offset']) && $r['offset'] != '') {
            $data = $data->offset($r['offset']);
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }
        $data = $data->orderByDesc('noregistrasi');
        $data = $data->get();
        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listRegistrasiDropdown(Request $r)
    {

        $result['listDepartemenPelayanan'] = explode(',', $this->settingFix('listDepartemenPelayanan'));
        $result['departemen'] = Departemen::mine()->get();
        $result['departemen_filter'] = [];
        foreach ($result['departemen'] as $de) {
            foreach ($result['listDepartemenPelayanan'] as $dep) {
                if ($de->id == $dep) {
                    $result['departemen_filter'][] = $de;
                }
            }
        }
        $result['ruangan'] = Ruangan::mine()->get();
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        // $result['kelas'] = Kelas::mine()->get();
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function batalRegistrasi(Request $request)
    {

        DB::beginTransaction();
        try {
            PasienDaftar::where('noregistrasi', $request->noregis)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->update(['statusenabled' => false]);

            AntrianPasienDiperiksa::where('noregistrasi', $request->noregis)
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->update(['statusenabled' => false]);
            DB::commit();
            $response = [
                "message" => "Registrasi Pasien Berhasil dibatalkan",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Registrasi Pasien Gagal dibatalkan",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function ubahDokter(Request $request)
    {

        DB::beginTransaction();
        try {

            $pelayananPasien = DB::select(DB::raw("select pp.norec,pp.tglpelayanan, prd.namaproduk,
                ppp.objectpegawaifk,apd.norec as norec_apd,pp.produkfk,  apd.objectruanganfk,ru.namaruangan
                from pasiendaftar_t as pd
                INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk =pd.norec
                INNER JOIN pelayananpasien_t as pp on apd.norec =pp.noregistrasifk
                inner join produk_m as prd on prd.id=pp.produkfk
                inner join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
                inner join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
                inner join ruangan_m as ru on ru.id=apd.objectruanganfk
                LEFT JOIN pelayananpasienpetugas_t as ppp on ppp.pelayananpasien =pp.norec
                where ppp.objectpegawaifk is null and pd.kdprofile = $this->kdProfile
                and pd.noregistrasi='$request->noregis'"));

            foreach ($pelayananPasien as $item) {
                $PelPasienPetugas = new PelayananPasienPetugas();
                $PelPasienPetugas->norec = $PelPasienPetugas->generateNewId();
                $PelPasienPetugas->kdprofile = $this->kdProfile;
                $PelPasienPetugas->statusenabled = true;
                $PelPasienPetugas->nomasukfk = $item->norec_apd;
                $PelPasienPetugas->objectjenispetugaspefk = 4; //dokter pemeriksa
                $PelPasienPetugas->objectpegawaifk = $request['objectpegawaifk'];
                $PelPasienPetugas->objectprodukfk = $item->produkfk;
                $PelPasienPetugas->objectruanganfk = $item->objectruanganfk;
                $PelPasienPetugas->pelayananpasien = $item->norec;
                $PelPasienPetugas->tglpelayanan = $item->tglpelayanan;
                $PelPasienPetugas->save();

                PelayananPasienPetugas::where('pelayananpasien', $item->norec)->where('kdprofile', $this->kdProfile)
                    ->update(['objectpegawaifk' => $request['objectpegawaifk']]);
            }

            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_pd)
                ->update([
                    'objectpegawaifk' => $request['objectpegawaifk'],
                    'objectdokterpemeriksafk' => $request['objectpegawaifk']
                ]);
            AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_apd)
                ->update(['objectpegawaifk' => $request->objectpegawaifk]);

            DB::commit();

            $response = [
                "message" => "Dokter Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $exc) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $exc->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function ubahDokterAPD(Request $request)
    {

        DB::beginTransaction();
        try {

            $dataPD = PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request['norec_pd'])
                ->first();

            if ($dataPD->objectruanganlastfk == $request['ruanganfk']) {
                PasienDaftar::where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('norec', $request['norec_pd'])
                    ->update(['objectpegawaifk' => $request->objectpegawaifk]);
            }
            AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_apd)
                ->update(['objectpegawaifk' => $request->objectpegawaifk]);

            DB::commit();
            $response = [
                "message" => "Dokter Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
    public function ubahDokterDPJP(Request $request)
    {
        DB::beginTransaction();
        try {
            if(!isset($request->isDelegasi)){
                PasienDaftar::where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('norec', $request->norec_pd)
                    ->update([
                        'objectpegawaifk' => $request->objectpegawaifk,
                        'objectpegawairawatbersamafk' => $request->objectpegawairawatbersamafk,
                    ]);
                $message = 'UBAH DPJP ';
                }else{
                PasienDaftar::where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('norec', $request->norec_pd)
                    ->update([
                        'objectpegawaifk' => $request->objectpegawaifk,
                    ]);
                    $message = 'DELEGASI DPJP ';
            }

            if(isset($request['dokterTambahan']) && count($request['dokterTambahan']) > 0){
                $deleteRawatBersama = DB::delete('delete from rawatbersama_t where noregistrasifk = ?', [$request['norec_pd']]);
                foreach ($request['dokterTambahan'] as $dokter) {
                    if(isset($dokter['dokterPemeriksaTambahan']['value'])){
                        $rawatBersama = new RawatBersama();
                        $rawatBersama->norec = $rawatBersama->generateNewId();
                        $rawatBersama->noregistrasifk = $request['norec_pd'];
                        $rawatBersama->objectpegawaifk = $dokter['dokterPemeriksaTambahan']['value'];
                        $rawatBersama->save();
                    }
                }
                $message = 'Rawat Bersama';
                // return $request['dokterTambahan'];
            }

            $this->LOGGING(
                'Perubahan DPJP',
                $request->norec_pd,
                'pasiendaftar_t',
                $message .
                $request->pasien['namapasien'] . ' (' . $request->pasien['nocm'] . ') - ' . $request->pasien['noregistrasi']
            );

            DB::commit();
            $response = [
                "message" => "Dokter Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function penandaPasien(Request $request)
    {
        DB::beginTransaction();
        try {
            $penandaPasien = PenandaPasien::where('noregistrasi', $request['pasien']['noregistrasi'])->first();

            if ($penandaPasien) {
                // Jika sudah ada, update data
                $penandaPasien->penanda = $request['penanda'];
                $penandaPasien->kategori_usia = $request['kategoriUsia'];
                $penandaPasien->kategori_insiden = $request['kategoriInsiden'];
                $message = "Penanda pasien diperbarui";
            } else {
                // Jika tidak ada, buat entri baru
                $penandaPasien = new PenandaPasien();
                $penandaPasien->norec = $penandaPasien->generateNewId();
                $penandaPasien->noregistrasi = $request['pasien']['noregistrasi'];
                $penandaPasien->penanda = $request['penanda'];
                $penandaPasien->kategori_usia = $request['kategoriUsia'];
                $penandaPasien->kategori_insiden = $request['kategoriInsiden'];
                $message = "Penanda pasien berhasil ditambahkan";
            }
            $penandaPasien->save();


            DB::commit();
            $response = [
                "message" => $message,
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Penanda pasien gagal",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function pesanRuangan(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_pd)
                ->update([
                    'ruangannextschedule' => $request->ruangannextschedule,
                    'statusschedule' => 'Waiting List',
                ]);

            DB::commit();
            $response = [
                "message" => "Ruangan Berhasil dipesan",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Ruangan Gagal dipesan",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
    public function ubahTglPulang(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_pd)
                ->update([
                    'tglpulang' => $request->tglpulang,
                ]);

            DB::commit();
            $response = [
                "message" => "Tanggal Pulang Berhasil diubah",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Tangggal Pulang Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
    public function mergePasien(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('nocmfk', $request->nocmfk_lama)
                ->update([
                    'nocmfk' => $request->nocmfk_baru,
                ]);


            DB::commit();
            $response = [
                "message" => "Merge Berhasil",
                "status" => 200,
                "data" => "Berhasil",
            ];
            Pasien::where('id', $request['nocmfk_lama'])->update([
                'statusenabled' => isset($request['statusenabled']) ? $request['statusenabled'] : 't',
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Merge Gagal",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }


    public function tetapkanPerawat(Request $request)
    {
        DB::beginTransaction();
        try {
            PasienDaftar::where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('norec', $request->norec_pd)
                ->update(['perawatfk' => $request->perawatfk]);

            DB::commit();
            $response = [
                "message" => "Penetapan Perawat Pada Pasien Berhasil",
                "status" => 200,
                "data" => "Berhasil",
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "message" => "Dokter Gagal diubah",
                "status" => 400,
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function detailRegistrasi(Request $request)
    {

        $pelayanan = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->join('pasien_m as pas', 'pas.id', '=', 'pd.nocmfk')
            ->leftjoin('agama_m as ag', 'ag.id', '=', 'pas.objectagamafk')
            ->leftjoin('jeniskelamin_m as jkel', 'jkel.id', '=', 'pas.objectjeniskelaminfk')
            ->leftjoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('kelas_m as kls2', 'kls2.id', '=', 'pd.objectkelasfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('kamar_m as kamar', 'kamar.id', '=', 'apd.objectkamarfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->select(
                'apd.norec as norec_apd',
                'pd.nocmfk',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'ag.id as agid',
                'ag.agama',
                'pas.tgllahir',
                'kp.id as kpid',
                'kp.kelompokpasien as jenisPasien',
                'pas.objectstatusperkawinanfk',
                'pas.namaayah',
                'pas.namasuamiistri',
                'pas.id as pasid',
                'pas.nobpjs  as nobpjs',
                'pas.nocm as noCm',
                'jkel.id as jkelid',
                'jkel.jeniskelamin',
                'jkel.reportdisplay as jenisKelamin',
                'pd.noregistrasi as noRegistrasi',
                'pas.namapasien as namaPasien',
                'pd.tglregistrasi as tglMasuk',
                'pd.norec as norec_pd',
                'pd.tglpulang as tglPulang',
                'pas.notelepon',
                'pd.objectrekananfk as rekananid',
                'kls2.id as klsid2',
                'kls2.namakelas as kelasRawat',
                'pg.id as pgid',
                'pg.namalengkap as namadokter',
                'rk.namarekanan as namaPenjamin',
                'rk.id as objectrekananfk',
                'klp.namaexternal AS kelompokpasien',
                'pd.objectkelompokpasienlastfk',
                'ru2.namaruangan as lastRuangan',
                'sp.nostruk',
                'sp.norec as strukfk',
                'pd.statuspasien as StatusPasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->first();


        $result = array(
            'data' => $pelayanan,
            'message' => 'setiawan@epic',
        );

        return $this->respond($result);
    }
    public function detailRegistrasiPasien(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJoin('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->leftJoin('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->leftJoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
            ->leftJoin('rekanan_m AS rk', 'rk.id', 'pd.objectrekananfk')
            ->leftJoin('kelompokpasien_m AS klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
            ->select(
                'apd.norec',
                'apd.tglregistrasi',
                'ru.id as ruid_asal',
                'ru.namaruangan',
                'kls.id as kelasid',
                'kls.namakelas',
                'km.namakamar',
                'tt.reportdisplay as nobed',
                'apd.statusantrian',
                'apd.statuskunjungan',
                'apd.tgldipanggildokter',
                'apd.tgldipanggilsuster',
                'pg.id as pgid',
                'pg.namalengkap as namadokter',
                'apd.objectasalrujukanfk',
                'pd.norec as norec_pd',
                'pd.nostruklastfk',
                'pd.nosbmlastfk',
                'apd.tglmasuk',
                'apd.tglkeluar',
                'ru.objectdepartemenfk',
                'dept.namadepartemen',
                'pm.tglmeninggal',
                'rk.namarekanan AS jenisrekanan',
                'klp.namaexternal AS kelompokpasien',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.noregistrasi', $request['noregistrasi'])
            ->orderBy('apd.tglmasuk', 'asc')
            ->get();

        return $this->respond($data);
    }

    public function getDataComboDetailRegis(Request $request)
    {
        $ruangan = Ruangan::mine()->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->get();
        $kelas = Kelas::mine()->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->get();
        $dokter = Pegawai::mine()->where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('objectjenispegawaifk', $this->settingFix('idJenisPegawaiDokter'))->get();

        $response = [
            "ruangan" => $ruangan,
            "kelas" => $kelas,
            "dokter" => $dokter
        ];

        return $this->respond($response);
    }

    public function simpanKonsul(Request $request)
    {
        DB::beginTransaction();
        try {
            $pd = PasienDaftar::where('norec', $request['norec_pd'])->first();
            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectasalrujukanfk = $request['asalRujukanfk'];
            $dataAPD->objectkelasfk = $request['kelasfk'];
            $max = AntrianPasienDiperiksa::where('objectruanganfk', $request['objectruangantujuan'])
                ->where('tglregistrasi', '>=', $pd->tglregistrasi)
                ->where('tglregistrasi', '<=', $pd->tglregistrasi)
                ->where('statusenabled', true)
                ->max('noantrian');
            $noAntrian = $max + 1;
            $dataAPD->noantrian = $noAntrian;
            $dataAPD->noregistrasifk = $request['norec_pd'];
            $dataAPD->objectpegawaifk = $request['dokterfk'];
            $dataAPD->objectruanganfk = $request['objectruangantujuan'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            $dataAPD->objectruanganasalfk = $request['objectruanganasalfk'];;
            $dataAPD->tglregistrasi = $pd->tglregistrasi; //date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->noregistrasi = $pd->noregistrasi;
            $dataAPD->save();

            DB::commit();
            $response = [
                "status" => 200,
                "message" => "Registrasi Konsul Berhasil",
                "data" => $dataAPD
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $response = [
                "status" => 400,
                "message" => "Registrasi Konsul Gagal",
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function hapusAPD(Request $request)
    {

        DB::beginTransaction();

        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['norec_pd'];
            $r_NewAPD = $request['norec_apd'];
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            $cekPP = PelayananPasien::where('noregistrasifk', $r_NewAPD)->where('statusenabled', true)->first();
            $dataPasien = DB::table('pasiendaftar_t as pd')
                        ->join('pasien_m as pas', 'pas.id', 'pd.nocmfk')
                        ->select(
                            'pas.namapasien',
                            'pas.nocm',
                            'pd.noregistrasi'
                        )
                        ->where('pd.norec', $r_NewPD)
                        ->first();
            if ($cekPP) {
                $message = 'Pasien sudah mendapatkan Pelayanan ';
                return $this->respond($cekPP, 400, $message);
            } else {

                $ruanganTerakhir = DB::table('antrianpasiendiperiksa_t as apd')
                    ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganasalfk')
                    ->leftjoin('ruangan_m as ruTu', 'ruTu.id', 'apd.objectruanganfk')
                    ->join('pasiendaftar_t as pd', 'pd.norec', 'apd.noregistrasifk')
                    ->select(
                        'ru.objectdepartemenfk',
                        'apd.norec',
                        'apd.objectruanganfk',
                        'apd.objectruanganasalfk',
                        'apd.objectkelasfk',
                        'apd.nobed',
                        'apd.tglkeluar',
                        'ru.namaruangan as ruanganAsal',
                        'ruTu.namaruangan as ruanganTujuan',
                        'pd.norec as norec_pd',
                        'apd.objectkamarfk'
                    )
                    ->where('apd.statusenabled', true)
                    ->where('apd.kdprofile', $kdProfile)
                    ->whereNull('apd.tglkeluar')
                    ->whereNotNull('apd.nobed')
                    ->where('apd.noregistrasifk', $r_NewPD)
                    ->first();
                // return $ruanganTerakhir;
                AntrianPasienDiperiksa::where('kdprofile', $this->kdProfile)->where('statusenabled', true)
                    ->where('noregistrasifk', $r_NewPD)
                    ->where('objectruanganfk', $ruanganTerakhir->objectruanganasalfk)
                    ->update(['tglkeluar' => null]);

                $ruanganSebelumnya = DB::table('antrianpasiendiperiksa_t as apd')
                    ->leftjoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
                    ->leftjoin('ruangan_m as ruAs', 'ruAs.id', '=', 'apd.objectruanganasalfk')
                    ->leftJoin('tempattidur_m as tt', 'tt.id', 'apd.nobed')
                    ->select(
                        'ru.namaruangan',
                        'apd.norec',
                        'apd.tglkeluar',
                        'apd.nobed',
                        'tt.reportdisplay',
                        'tt.objectstatusbedfk',
                        'ruAs.namaruangan as ruanganAsal',
                        'ruAs.id as ruanganasalfk',
                        'ru.id as ruanganlastfk',
                        'apd.objectkelasfk'
                    )
                    ->where('apd.kdprofile', $this->kdProfile)
                    ->where('apd.statusenabled', true)
                    ->where('apd.noregistrasifk', $r_NewPD)
                    ->where('apd.objectruanganfk', $ruanganTerakhir->objectruanganasalfk)
                    ->first();

                TempatTidur::where('statusenabled', true)->where('kdprofile', $this->kdProfile)->where('id', $ruanganSebelumnya->nobed)->update(['objectstatusbedfk' => $SET['idStatusBedIsi']]);

                PasienDaftar::where('norec', $r_NewPD)->update([
                    'objectruanganlastfk' => $ruanganSebelumnya->ruanganlastfk,
                    'objectkelasfk' => $ruanganSebelumnya->objectkelasfk,
                ]);

                TempatTidur::where('id', $ruanganTerakhir->nobed)->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->update(['objectstatusbedfk' => $this->settingFix('idStatusBedKosong')]);

                AntrianPasienDiperiksa::where('norec', $ruanganTerakhir->norec)->where('statusenabled', true)
                    ->where('kdprofile', $this->kdProfile)->whereNotNull('objectruanganasalfk')
                    ->update(['statusenabled' => false]);
            }
            DB::commit();
            $response = [
                "data" => "Terhapus",
                "message" => "Data Berhasil Dihapus",
                "status" => 200,
            ];
            $this->LOGGING(
                'Hapus Registrasi',
                $r_NewPD,
                'antrianpasiendiperiksa_t',
                'Hapus Registrasi ' . $ruanganTerakhir->ruanganTujuan. ' a/n '.
                $dataPasien->namapasien . ' (' . $dataPasien->namapasien . ') - ' . $dataPasien->noregistrasi
            );
        } catch (Exception $e) {
            $response = [
                "data" => "Gagal",
                "message" => "Tidak bisa dihapus, sudah ada tindakannya",
                "status" => 400,
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }

    public function ubahTanggalDetailRegis(Request $request)
    {
        DB::beginTransaction();
        //##Update Pasiendaftar##
        try {
            $dataRPP = RegistrasiPelayananPasien::where('noregistrasifk', $request['norec_pd'])->count();
            if ($request['tglregistrasi']) {
                PasienDaftar::where('noregistrasi', $request['noregistrasi'])->update(['tglregistrasi' => $request['tglregistrasi']]);
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['tglregistrasi' => $request['tglregistrasi']]);
            }

            if ($request['tglkeluar'] != '' && $request['tglmasuk'] != '') {
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(
                    [
                        'tglkeluar' => $request['tglkeluar'],
                        'tglmasuk' => $request['tglmasuk']
                    ]
                );
                $updateWrbPD = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
                if ($updateWrbPD->objectruanganlastfk == $request['ruanganasal']) {
                    $updateWrbPD->tglpulang = $request['tglkeluar'];
                    $updateWrbPD->save();
                }
                if ($dataRPP > 0) {
                    DB::table('registrasipelayananpasien_t')
                        ->select('noregistrasifk', 'objectruanganfk', 'rpp.tglkeluar')
                        ->where('objectruanganfk', $request['ruanganasal'])
                        ->where('noregistrasifk', $request['norec_pd'])
                        ->update(
                            [
                                'tglkeluar' => $request['tglkeluar'],
                                'tglmasuk' => $request['tglmasuk']
                            ]
                        );
                }
            }
            if ($request['tglkeluar'] == '' && $request['tglmasuk'] != '') {
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['tglmasuk' => $request['tglmasuk']]);
                if ($dataRPP > 0) {
                    DB::table('registrasipelayananpasien_t')
                        ->select('noregistrasifk', 'objectruanganfk', 'tglkeluar')
                        ->where('objectruanganfk', $request['ruanganasal'])
                        ->where('noregistrasifk', $request['norec_pd'])
                        ->update(['tglmasuk' => $request['tglmasuk']]);
                }
            }
            if ($request['tglkeluar'] != '' && $request['tglmasuk'] == '') {
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->update(['tglkeluar' => $request['tglkeluar']]);
                $updateWrbPD = PasienDaftar::where('noregistrasi', $request['noregistrasi'])->first();
                if ($updateWrbPD->objectruanganlastfk == $request['ruanganasal']) {
                    $updateWrbPD->tglpulang = $request['tglkeluar'];
                    $updateWrbPD->save();
                }
                if ($dataRPP  > 0) {
                    DB::table('registrasipelayananpasien_t')
                        ->select('noregistrasifk', 'objectruanganfk', 'tglkeluar')
                        ->where('objectruanganfk', $request['ruanganasal'])
                        ->where('noregistrasifk', $request['norec_pd'])
                        ->update(['tglkeluar' => $request['tglkeluar']]);
                }
            }

            DB::commit();

            $response = [
                "status" => 200,
                "message" => "Tanggal Berhasil diubah",
                "data" => $dataRPP
            ];
        } catch (Exception $e) {
            $response = [
                "status" => 400,
                "message" => "Tanggal Gagal diubah",
                "data" => $e->getMessage()
            ];
        }

        return $this->respond($response['data'], $response['status'], $response['message']);
    }
    public function getDaftarPasienMeninggal2(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        $filter = $request->all();
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', function ($join) {
                $join->on('ps.id', '=', 'pd.nocmfk')->on('ps.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('jeniskelamin_m as jk', function ($join) {
                $join->on('jk.id', '=', 'ps.objectjeniskelaminfk')->on('jk.kdprofile', '=', 'ps.kdprofile');
            })
            ->leftjoin('statuskeluar_m as sk', function ($j) {
                $j->on('sk.id', '=', 'pd.objectstatuskeluarfk')->on('sk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('statuspulang_m as sp', function ($j) {
                $j->on('sp.id', '=', 'pd.objectstatuspulangfk')->on('sp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('penyebabkematian_m as pk', function ($j) {
                $j->on('pk.id', '=', 'pd.objectpenyebabkematianfk')->on('pk.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('ruangan_m as ru', function ($join) {
                $join->on('ru.id', '=', 'pd.objectruanganlastfk')->on('ru.kdprofile', '=', 'pd.kdprofile');
            })
            ->select(DB::raw("pd.tglregistrasi,pd.noregistrasi,ps.nocm,ps.namapasien,jk.jeniskelamin,ps.tgllahir,
            sk.statuskeluar,sp.statuspulang,pd.namalengkapambilpasien,
            case
                when pd.objectpenyebabkematianfk = 4
                then pd.keteranganpenyebabkematian
                else pk.penyebabkematian
            end
               as keterangan,
            pd.tglmeninggal, ru.namaruangan,pk.namaexternal"))
            ->where('pd.objectstatuskeluarfk', 5)
            ->where('pd.kdprofile', $kdProfile);

        if (isset($filter['tglAwal']) && $filter['tglAwal'] != '' && isset($filter['tglAkhir']) && $filter['tglAkhir'] != '') {
            $data = $data->whereBetween('pd.tglmeninggal', [$filter['tglAwal'],  $filter['tglAkhir']]);
        }
        if (isset($filter['noReg']) && $filter['noReg'] != "" && $filter['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', 'ilike', '%' . $filter['noReg'] . '%');
        }
        if (isset($filter['noCm']) && $filter['noCm'] != "" && $filter['noCm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $filter['noCm'] . '%');
        }
        if (isset($filter['namaPasien']) && $filter['namaPasien'] != "" && $filter['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $filter['namaPasien'] . '%');
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => '@epic',
        );

        return $this->respond($result);
    }

    //    public function getDaftarKonsulFromOrder(Request $request)
    // {
    //     $data = DB::table('strukorder_t as so')
    //         ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
    //         ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
    //         ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
    //         ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
    //         ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
    //         ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
    //         ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
    //         ->select(
    //             'so.norec',
    //             'so.noorder',
    //             'so.tglorder',
    //             'so.rawatbersama',
    //             'so.konsultasi',
    //             'so.lainlain',
    //             'ru.namaruangan as ruanganasal',
    //             'pg.namalengkap',
    //             'rutuju.namaruangan as ruangantujuan',
    //             'pet.namalengkap as pengonsul',
    //             'pd.noregistrasi',
    //             'pd.tglregistrasi',
    //             'ps.nocm',
    //             'so.keteranganorder',
    //             'pd.norec as norec_pd',
    //             'ps.namapasien',
    //             'pg.id as pegawaifk',
    //             'so.objectruangantujuanfk',
    //             'so.objectruanganfk',
    //             'apd.norec as norec_apd',
    //             'so.keteranganlainnya',
    //             'pd.objectkelasfk as kelasfk_pd'
    //         )
    //         ->where('so.kdprofile', $this->kdProfile)
    //         ->where('so.statusenabled', true)
    //         ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
    //         ->orderBy('so.tglorder', 'desc');
    //     if (isset($request['tglAwal']) && $request['tglAwal'] != '') {
    //         $data = $data->where('so.tglorder', '>=', $request['tglAwal'] . ' 00:00');
    //     }
    //     if (isset($request['tglAkhir']) && $request['tglAkhir'] != '') {
    //         $data = $data->where('so.tglorder', '<=', $request['tglAkhir'] . ' 23:59');
    //     }
    //     if (isset($request['norecpd']) && $request['norecpd'] != '') {
    //         $data = $data->where('pd.norec', $request['norecpd']);
    //     }
    //     // if (isset($request['dokterid']) && $request['dokterid'] != '') {
    //     //     $data = $data->where('pg.id', $request['dokterid']);
    //     // }
    //     if (isset($request['idPegawai']) && $request['idPegawai'] != "" && $request['idPegawai'] != "undefined") {
    //         $data = $data->where('pg.id', '=', $request['idPegawai']);
    //     }
    //     if (isset($request['nocm']) && $request['nocm'] != '') {
    //         $data = $data->where('ps.nocm', $request['nocm']);
    //     }
    //     if (isset($request['noregistrasi']) && $request['noregistrasi'] != '') {
    //         $data = $data->where('pd.noregistrasi', $request['noregistrasi']);
    //     }
    //     if (isset($request['isnotverif']) && $request['isnotverif'] != '' &&  $request['isnotverif'] == 'true') {
    //         $data = $data->wherenull('apd.norec');
    //     }
    //     $data = $data->get();
    //     $result = array(
    //         'data' => $data,
    //     );
    //     return $this->respond($result);
    // }

    // Konsultasi
    public function getDaftarKonsulFromOrder(Request $request)
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

        $dateRange = [$request['tglAwal'], $request['tglAkhir']];
        $filter = $request->all();
        $data = DB::table('strukorder_t as so')
            ->Join('antrianpasiendiperiksa_t as apd', 'so.norec', '=', 'apd.objectstrukorderfk')
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
            ->where('so.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $this->settingFix('idKelompokTransaksiKonsul'))
            ->whereBetween(DB::raw("CAST(so.tglorder as Date)"), $dateRange);
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('ru.id', explode(',', $request['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->whereIn('ru.id', $ruanganTersedia);
        }

        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }

        if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
            $data = $data->where('pg.id', $request['idpegawai']);
        }

        if (isset($request['dokterfk']) && $request['dokterfk'] != '') {
            $data = $data->where('pg.id', $request['dokterfk']);
        }

        $data = $data->orderBy('so.tglorder');

        $data = $data->get();

        return $this->respond($data);
    }

    //Daftar Order
    public function getOrderKonsul(Request $request)
    {
        $dateRange = [$request['tglAwal'], $request['tglAkhir']];
        $idProfile = (int) $this->kdProfile;
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

        $kelTrans = KelompokTransaksi::where('kelompoktransaksi', 'KONSULTASI DOKTER')->where('kdprofile', $idProfile)->first();
        $data = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->select(
                'so.statusorder',
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'ru.namaruangan as ruanganasal',
                'pg.namalengkap',
                'rutuju.namaruangan as ruangantujuan',
                'pet.namalengkap as pengonsul',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.objectkelasfk as kelasfk_pd',
                'apd.objectkelasfk as kelasfk_apd',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            ->wherenull('apd.norec')
            ->where('so.objectkelompoktransaksifk', $kelTrans->id)
            // ->whereBetween(DB::raw("CAST(so.tglorder as Date)"),$dateRange)
            ->orderBy('so.tglorder', 'desc');

        if (isset($request['norecpd']) && $request['norecpd'] != '') {
            $data = $data->where('pd.norec', $request['norecpd']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
            $filter = true;
            $data = $data->whereIn('rutuju.id', explode(',', $request['ruanganfk']));
        } else {
            $filter = true;
            $data = $data->whereIn('rutuju.id', $ruanganTersedia);
        }

        if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
            $data = $data->where('pg.id', $request['idpegawai']);
        }

        if (isset($request['dokterfk']) && $request['dokterfk'] != '') {
            $data = $data->where('pg.id', $request['dokterfk']);
        }

        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }

        $total = $data->count();
        $data = $data->get();
        $result = array(
            'data' => $data,
            'total' => $total,
            'message' => 'Inhuman',
        );
        return $this->respond($result);
    }

    public function getOrderSITB(Request $request)
    {
        $idProfile = (int) $this->kdProfile;
        $data = DB::table('strukorder_t as so')
            ->Join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->Join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftJoin('ruangan_m as rutuju', 'rutuju.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftJoin('pegawai_m as pet', 'pet.id', '=', 'so.objectpetugasfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.objectstrukorderfk', '=', 'so.norec')
            ->select(
                'so.statusorder',
                'so.norec',
                'so.noorder',
                'so.tglorder',
                'ru.namaruangan as ruanganasal',
                'pg.namalengkap',
                'rutuju.namaruangan as ruangantujuan',
                'pet.namalengkap as pengonsul',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.noidentitas',
                'pd.objectkelasfk as kelasfk_pd',
                'apd.objectkelasfk as kelasfk_apd',
                'so.keteranganorder',
                'pd.norec as norec_pd',
                'ps.namapasien',
                'pg.id as pegawaifk',
                'so.objectruangantujuanfk',
                'so.objectruanganfk',
                'apd.norec as norec_apd'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            ->where('so.statusorder', 0)
            ->where('so.objectkelompoktransaksifk', 432)
            // ->whereBetween(DB::raw("CAST(so.tglorder as Date)"),$dateRange)
            ->orderBy('so.tglorder', 'desc');

        if (isset($request['norecpd']) && $request['norecpd'] != '') {
            $data = $data->where('pd.norec', $request['norecpd']);
        }
        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('ps.namapasien', 'ilike', $searchTerm)
                    ->orWhere('pd.noregistrasi', 'ilike', $searchTerm)
                    ->orWhere('ps.nocm', 'ilike', $searchTerm)
                    ->orWhere('ps.noidentitas', 'ilike', $searchTerm);
            });
        }
        // if (isset($request['ruanganfk']) && $request['ruanganfk'] != '') {
        //     $data = $data->whereIn('rutuju.id', explode(',', $request['ruanganfk']));
        // }

        // if (isset($request['idpegawai']) && $request['idpegawai'] != '') {
        //     $data = $data->where('pg.id', $request['idpegawai']);
        // }

        // if (isset($request['dokterfk']) && $request['dokterfk'] != '') {
        //     $data = $data->where('pg.id', $request['dokterfk']);
        // }

        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }

        $total = $data->count();
        $data = $data->get();
        $result = array(
            'data' => $data,
            'total' => $total,
            'message' => 'Inhuman',
        );
        return $this->respond($result);
    }

    public function getRuangan(){
        $idProfile = (int) $this->kdProfile;
        $ruangan = Ruangan::select('id', 'namaruangan')->where('kdprofile', $idProfile)->where('statusenabled', true)->get();
        return $this->respond($ruangan);
    }

    public function saveKonsulFromOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            $pd = PasienDaftar::where('norec', $request['norec_pd'])->first();
            $apd = AntrianPasienDiperiksa::where('noregistrasifk', $request['norec_pd'])->where('kdprofile', $this->kdProfile)->first();

            $noAntrian = 0;

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $this->kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectasalrujukanfk = $apd->objectasalrujukanfk;
            $dataAPD->objectkelasfk = $request['kelasfk'];

            // return $request['objectruangantujuanfk'];
            $max = AntrianPasienDiperiksa::where('objectruanganfk', $request['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d' . ' 00:00'))
                ->where('tglregistrasi', '<=', date('Y-m-d' . ' 23:59'))
                ->where('statusenabled', true)
                ->max('noantrian');
            $noAntrian = $max + 1;
            $dataAPD->noantrian = $noAntrian;

            $dataAPD->noregistrasifk = $request['norec_pd'];
            $dataAPD->objectpegawaifk = $request['dokterfk'];
            $dataAPD->objectruanganfk = $request['objectruangantujuanfk'];
            $dataAPD->statusantrian = 0;
            $dataAPD->statuspasien = 1;
            $dataAPD->statuskunjungan = 'LAMA';
            $dataAPD->statuspenyakit = 'BARU';
            $dataAPD->status = "Belum Dipanggil";
            $dataAPD->objectruanganasalfk = $request['objectruanganasalfk'];
            $dataAPD->tglregistrasi = date('Y-m-d H:i:s');
            $dataAPD->tglkeluar = date('Y-m-d H:i:s');
            $dataAPD->tglmasuk = date('Y-m-d H:i:s');
            $dataAPD->objectstrukorderfk = $request['norec_so'];
            $dataAPD->noregistrasi = $pd->noregistrasi;
            $dataAPD->save();

            // return $dataAPD;
            DB::commit();

            StrukOrder::where('norec', $request['norec_so'])->update([
                'statusorder' => 1,
            ]);

            $respond = [
                "status" => 200,
                "message" => "Berhasil Verifikasi",
                "data" => $dataAPD->norec
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $respond = [
                "status" => 400,
                "message" => "Gagal Verifikasi",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }

    public function saveSITBOrder(Request $request)
    {
        DB::beginTransaction();
        try {
            StrukOrder::where('norec', $request['norec_order'])->update(['statusorder' => 1]);
            Pasien::where('id', $request['nocmfk'])->update(['sitb_id' => $request['nositb']]);

            // return $dataAPD;
            DB::commit();

            $transMessage = "Nomor SITB sudah diupdate";
            $respond = [
                "status" => 200,
                "message" => "Berhasil Verifikasi",
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $respond = [
                "status" => 400,
                "message" => "Gagal Verifikasi",
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond);
    }

    public function updateDokterAntrian(Request $request)
    {

        DB::beginTransaction();
        try {

            if ($request['norec_apd'] != null) {
                $apd =  AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->where('kdprofile', $this->kdProfile)->first();
                AntrianPasienDiperiksa::where('norec', $request['norec_apd'])->where('kdprofile', $this->kdProfile)->update(['objectpegawaifk' => $request['iddokter']]);

                PasienDaftar::where('norec', $apd->noregistrasifk)->where('kdprofile', $this->kdProfile)->update(['objectpegawaifk' => $request['iddokter']]);
            }

            DB::commit();
            $respond = [
                "message" => "Update Berhasil",
                "status"  => 200,
                "data" => "Berhasil"
            ];
        } catch (Exception $e) {

            DB::rollBack();
            $respond = [
                "message" => "Update Gagal",
                "status" => 400,
                "data" => $e->getMessage(),
            ];
        }

        return $this->respond($respond['data'], $respond['status'], $respond['message']);
    }

    public function getDaftarWaitingList(Request $request)
    {
        $data  = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru2', 'ru2.id', '=', 'pd.ruangannextschedule')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->leftjoin('asuransipasien_m as asu', 'pa.objectasuransipasienfk', '=', 'asu.id')
            ->leftjoin('kelas_m as klstg', 'klstg.id', '=', 'asu.objectkelasdijaminfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec');
                $join->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->leftjoin('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
            ->leftJOIN('jeniskelamin_m as jks', 'jks.id', '=', 'ps.objectjeniskelaminfk')
            ->distinct()
            ->select(
                'pd.norec as norec_pd',
                'pd.statusenabled',
                'pd.tglregistrasi',
                'ps.nocm',
                'pd.nocmfk',
                'pd.noregistrasi',
                'ru.namaruangan',
                'ps.namapasien',
                'kp.kelompokpasien',
                'rek.namarekanan',
                'pg.namalengkap as namadokter',
                'pd.tglpulang',
                'pd.statuspasien',
                'pa.norec as norec_pa',
                'pa.objectasuransipasienfk',
                'pd.objectkelompokpasienlastfk',
                'pd.objectpegawaifk as pgid',
                'pd.objectruanganlastfk',
                'pd.ruangannextschedule',
                'pd.statusschedule',
                'pa.nosep',
                'pd.nostruklastfk',
                'klstg.namakelas as kelasditanggung',
                'kls.namakelas',
                'ps.tgllahir',
                'ru.objectdepartemenfk',
                'pd.objectkelasfk',
                'pa.ppkrujukan',
                'ru.icons',
                'jpl.jenispelayanan',
                'pa.objectdiagnosafk as iddiagnosabpjs',
                'ps.nobpjs',
                'jks.jeniskelamin',
                'apd.norec as norec_apd',
                'ru2.namaruangan as ruangantujuan'
            )
            ->where('apd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', (int) $this->kdProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal'] . ' 00:00');
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $request['tglAkhir'] . ' 23:59');
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        if (isset($request['ruangantujuanfk']) && $request['ruangantujuanfk'] != "" && $request['ruangantujuanfk'] != "undefined") {
            $data = $data->where('ru2.id', '=', $request['ruangantujuanfk']);
        }
        if (isset($request['namapasien']) && $request['namapasien'] != "" && $request['namapasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['namapasien'] . '%');
        }
        if (isset($request['statusschedule']) && $request['statusschedule'] != "" && $request['statusschedule'] != "undefined") {
            $data = $data->where('pd.statusschedule', 'ilike', '%' . $request['statusschedule'] . '%');
        }
        if (isset($request['offset']) && $request['offset'] != '') {
            $data = $data->offset($request['offset']);
        }
        if (isset($request['limit']) && $request['limit'] != '') {
            $data = $data->limit($request['limit']);
        }
        $data = $data->orderByDesc('noregistrasi');
        $data = $data->get();

        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
}
