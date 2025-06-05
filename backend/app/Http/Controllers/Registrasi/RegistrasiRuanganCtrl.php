<?php

namespace App\Http\Controllers\Registrasi;


use App\Http\Controllers\Controller;
use App\Models\Master\Agama;
use App\Models\Master\AsalRujukan;
use App\Models\Master\Departemen;
use App\Models\Master\GolonganDarah;
use App\Models\Master\JenisKelamin;
use App\Models\Master\JenisPegawai;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kebangsaan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Negara;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Suku;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\BatalRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\StrukResep;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class RegistrasiRuanganCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function pasienRegistrasi(Request $r)
    {
        $data = DB::table('pasien_m as ps')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'ps.objectagamafk',
                'ps.noidentitas',
                'ps.nobpjs',
                'ps.noasuransilain',
                'ps.notelepon',
                'ps.nohp',
                'ps.namaayah',
                'ps.namaibu',
                'ps.email'
            )
            ->where('ps.kdprofile', $this->kdProfile)
            ->where('ps.statusenabled', true);

        if (isset($r['noCm']) && $r['noCm'] != '' && $r['noCm'] != 'undefined') {
            $data = $data->where('ps.nocm', $r['noCm']);
        }
        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('ps.id', $r['id']);
        }

        $data = $data->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = null;
        if (isset($r['norec_pd']) && $r['norec_pd'] != '' && isset($r['norec_apd']) && $r['norec_apd'] != '') {
            $registrasi = DB::table('pasiendaftar_t as pd')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->leftjoin('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
                ->leftjoin('kamar_m as kmr', 'kmr.id', '=', 'apd.objectkamarfk')
                ->leftjoin('tempattidur_m as tt', 'tt.id', '=', 'apd.nobed')
                ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'pd.asalrujukanfk')
                ->leftjoin('kelompokpasien_m as kps', 'kps.id', '=', 'pd.objectkelompokpasienlastfk')
                ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
                ->leftjoin('jenispelayanan_m as jpl', 'jpl.id', '=', 'pd.jenispelayanan')
                ->select(
                    'pd.norec as norec_pd',
                    'pd.noregistrasi',
                    'pd.tglregistrasi',
                    'pd.objectruanganlastfk',
                    'pd.nocmfk',
                    'ru.namaruangan',
                    'pd.objectkelasfk',
                    'pd.objectkelasrawatfk',
                    'kls.namakelas',
                    'apd.objectkamarfk',
                    'kmr.namakamar',
                    'apd.nobed',
                    'pd.asalrujukanfk',
                    'ar.asalrujukan',
                    'pd.objectkelompokpasienlastfk',
                    'kps.kelompokpasien',
                    'pd.objectrekananfk',
                    'rk.namarekanan',
                    'pd.jenispelayanan as jenispelayananfk',
                    'jpl.jenispelayanan',
                    'pd.objectpegawaifk',
                    'pg.namalengkap as dokter',
                    'ru.objectdepartemenfk',
                    'tt.reportdisplay as tempattidur',
                    'pd.catatan',
                    'pd.statuspasien',
                    'tt.nomorbed'
                )
                ->where('pd.norec', $r['norec_pd'])
                ->where('pd.statusenabled', true)
                ->where('apd.statusenabled', true)
                ->where('apd.norec', $r['norec_apd'])
                ->where('pd.kdprofile', (int) $this->kdProfile)
                ->first();
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['idDepartemenIGD'] = explode(',', $this->settingFix('idDepartemenIGD'));
        $result['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $result['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $result['idKelompokPasienUMUM'] = $this->settingFix('idKelompokPasienUMUM');

        $ru =  Ruangan::mine()->get();
        $result['ruangan_RI'] = [];
        $result['ruangan_RJ'] = [];
        foreach ($ru as $item) {
            foreach ($result['idDepartemenRI']  as $ri) {
                if ($item->objectdepartemenfk == (int)$ri) {
                    $result['ruangan_RI'][]  = $item;
                }
            }
            foreach ($result['idDepartemenRJ']  as $rj) {
                if ($item->objectdepartemenfk == (int)$rj) {
                    $result['ruangan_RJ'][]  = $item;
                }
            }
            // foreach ($result['idDepartemenIGD']  as $igd) {
            //     if ($item->objectdepartemenfk == (int)$igd) {
            //         $result['ruangan_RJ'][]  = $item;
            //     }
            // }
        }
        if ($registrasi != null) {
            foreach ($result['idDepartemenRI']  as $ri) {
                $registrasi->israwatinap = false;
                if ($registrasi->objectdepartemenfk == $ri) {
                    $registrasi->israwatinap = true;
                    break;
                }
            }
        }

        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function listDokterPaging(Request $r)
    {
        $result['kdPsikologDokter'] = explode(',', $this->settingFix('kdPsikologDokter'));
        $result['dokter'] =
            Pegawai::mine()
            ->whereIn('objectjenispegawaifk', $result['kdPsikologDokter'])
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function listKelasByRuangan(Request $r)
    {
        $result = DB::table('mapruangantokelas_m as mrk')
            ->join('kelas_m as kl', 'kl.id', '=', 'mrk.objectkelasfk')
            ->select('kl.id', 'kl.namakelas')
            ->where('mrk.objectruanganfk', $r['id'])
            ->where('mrk.kdprofile',  $this->kdProfile)
            ->where('mrk.statusenabled', true)
            ->where('kl.statusenabled', true)
            ->orderBy('kl.namakelas')
            ->get();

        return $this->respond($result);
    }
    public function listKamarByKelas(Request $r)
    {
        $result['kamar'] = DB::table('kamar_m as kmr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
            ->select(
                'kmr.id',
                'kmr.namakamar',
                'kl.id as id_kelas',
                'kl.namakelas',
                'ru.id as id_ruangan',
                'ru.namaruangan',
                'kmr.jumlakamarisi',
                'kmr.qtybed'
            )
            ->where('kmr.objectruanganfk', $r['idRuangan'])
            ->where('kmr.objectkelasfk', $r['id'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();

        $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));
        $tt = DB::table('tempattidur_m')
            ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
            // if($r['isEdit'] == 'false'){
            // }
                $tt = $tt->where('objectstatusbedfk', $result['idStatusBedKosong']);
            $tt = $tt->orderBy('reportdisplay');
            $tt = $tt->get();

        $kamar = [];
        if (isset($r['isRG']) && $r['isRG'] != 'undefined' && $r['isRG'] == 'false') {
            for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
                $id =   $result['kamar'][$i]->id;
                $result['kamar'][$i]->details = [];
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $result['kamar'][$i]->details[] = $itemTT;
                    }
                }
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $kamar[] =  $result['kamar'][$i];
                        break;
                    }
                }
            }
        } else {
            for ($i = count($result['kamar']) - 1; $i >= 0; $i--) {
                $id =   $result['kamar'][$i]->id;
                $result['kamar'][$i]->details = [];
                foreach ($tt as $itemTT) {
                    if ($itemTT->objectkamarfk == $id) {
                        $result['kamar'][$i]->details[] = $itemTT;
                    }
                }
            }
            $kamar[] = $result['kamar'];
        }
        return $this->respond($kamar);
    }
    public function listPenjaminByKelompokPasien(Request $r)
    {
        $result = DB::table('mapkelompokpasientopenjamin_m as mkp')
            ->join('rekanan_m as rk', 'rk.id', '=', 'mkp.kdpenjaminpasien')
            ->select('rk.id', 'rk.namarekanan')
            ->where('mkp.objectkelompokpasienfk', $r['id'])
            ->where('mkp.statusenabled', true)
            ->where('rk.statusenabled', true)
            ->distinct()
            ->where('mkp.kdprofile', (int)$this->kdProfile)
            ->orderBy('rk.namarekanan')
            ->get();
        return $this->respond($result);
    }

    public function saveRegistrasi(Request $r)
    {
        if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
        } else {
        DB::beginTransaction();
        }
        try {
            //region Save
            $kdProfile = $this->kdProfile;
            $PD = $r['pasiendaftar'];
            $APD = $r['antrianpasiendiperiksa'];

            // $isLama = PasienDaftar::where('nocmfk',$PD['nocmfk'])->count();

            $cekDepartemen = Ruangan::where('statusenabled',true)->where('kdprofile',$this->kdProfile)
                                    ->where('id',$PD['objectruanganlastfk'])
                                    ->first();
            $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';

            $cekRI = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNull('tglpulang')
                ->where('statusenabled', true)
                ->first();
            if (!empty($cekRI) && $PD['norec'] == '') {
                DB::rollBack();
                $transMessage = 'Pasien belum dipulangkan dg No. Registrasi : '
                    . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
                $result = array("status" => 400, "result"  => $cekRI);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $cekStatus = PasienDaftar::where('nocmfk', $PD['nocmfk'])
                ->whereNotNull('tglmeninggal')
                ->where('statusenabled', true)
                ->first();
            if (!empty($cekStatus) && $PD['norec'] == '') {
                DB::rollBack();
                $transMessage = 'Pasien sudah Meninggal : '
                    . $cekStatus->noregistrasi . ' (' . $cekStatus->tglmeninggal . ')';
                $result = array("status" => 400, "result"  => $cekStatus);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            $SET['idNonKelas'] = (int) $this->settingFix('idNonKelas');
            $SET['idJenisPelayananEksek'] = (int) $this->settingFix('idJenisPelayananEksek');
            $SET['idKelasIPKKU']= (int) $this->settingFix('idKelasIPKKU');
            if ($PD['norec'] == '') {
                $noregistrasi = $this->SEQUENCE_NEXVAL(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $kdProfile);
                $noAntrian = 0;
                if ($noregistrasi == '') {
                    abort(400, 'SEQ ERROR');
                }
                $model_PD = new PasienDaftar();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->kdprofile = $kdProfile;
                $model_PD->statusenabled = true;
                $model_PD->objectruanganasalfk = $PD['objectruanganlastfk'];
                $model_PD->statuspasien = $PD['statuspasien'];
                $namaLog = 'Tambah Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['objectruanganlastfk'])->first()->namaruangan . ' ';
                if ($PD['israwatinap'] == 'true') {
                    $model_PD->tglpulang = null;
                }
            } else {
                $model_PD =  PasienDaftar::where('norec', $PD['norec'])->first();
                $noregistrasi = $model_PD->noregistrasi;
                $namaLog = 'Edit Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['objectruanganlastfk'])->first()->namaruangan . ' ';
            }
            $model_PD->objectruanganlastfk = $PD['objectruanganlastfk'];
            $model_PD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_PD->objectpegawairawatbersamafk =  isset($PD['objectpegawairawatbersamafk'])?$PD['objectpegawairawatbersamafk']:null;
            // $model_PD->jenispelayananfk =   $PD['jenispelayananfk'];
            $model_PD->jenispelayanan =   $PD['jenispelayananfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_PD->objectkelasfk = $PD['objectkelasfk'];
                $model_PD->objectkelasrawatfk = $PD['objectkelasrawatfk'];
                $model_PD->tglpulang = null;
            } else {

                if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
                    $model_PD->objectkelasfk =  $SET['idKelasIPKKU'];
                }else{
                    $model_PD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_PD->tglpulang =  $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_PD->objectkelompokpasienlastfk = $PD['objectkelompokpasienlastfk'];
            $model_PD->nocmfk = $PD['nocmfk'];
            $model_PD->objectrekananfk = $PD['objectrekananfk'];
            // $model_PD->statuspasien = $isLama == 0 ? 'BARU' : 'LAMA';
            $model_PD->ismobilejkn = isset($PD['isjkn']) ? $PD['isjkn'] : null;
            $model_PD->antrianpasienregistrasifk = isset($PD['antrianpasienregistrasifk']) ? $PD['antrianpasienregistrasifk'] : null;
            $model_PD->noreservasi = isset($PD['noreservasi']) ? $PD['noreservasi'] : null;
            $model_PD->tglregistrasi =  $PD['tglregistrasi'];
            $model_PD->sitb_id = isset($PD['sitb_id']) ? $PD['sitb_id'] : null;
            $model_PD->asalrujukanfk =  $PD['asalrujukanfk'];
            $model_PD->keteranganasalrujukan = $PD['asalrujukanfk'] == 5 ? null : (isset($PD['keteranganasalrujukan'])?$PD['keteranganasalrujukan']:null);
            $model_PD->noregistrasi = $noregistrasi;
            $model_PD->catatan = isset($PD['isRencanaBPJS']) ? $PD['isRencanaBPJS'] : null;
            $model_PD->petugas = $this->getNamaPegawai();
            $model_PD->iskiosk = isset($PD['iskiosk']) ? $PD['iskiosk'] : null;

            if(isset($PD['antrianpasienregistrasifk']) &&( isset($PD['statusschedule']) && $PD['statusschedule'] !='Kios-K')){
                $reserv = AntrianPasienRegistrasi::where('noreservasi', $PD['statusschedule'])->first();
                if(!empty($reserv)){
                    $model_PD->antrianpasienregistrasifk = $reserv->norec;
                }
            }

            $model_PD->save();

            // if ($model_PD->antrianpasienregistrasifk != null) {
            //     AntrianPasienRegistrasi::where('norec', $model_PD->antrianpasienregistrasifk)
            //         ->update(['isconfirm' => true, 'pasiendaftarfk' => $model_PD->norec]);
            // }
            if ($PD['israwatinap'] == 'true') {
                $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
                $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            }

            if ($APD['norec'] == '') {
                $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                    ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                    ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                    ->where('statusenabled', true)
                    ->max('noantrian');
                $noAntrian = $max + 1;

                $model_APD = new AntrianPasienDiperiksa;
                $model_APD->norec = $model_APD->generateNewId();
                $model_APD->kdprofile = (int)$kdProfile;
                $model_APD->statusenabled = true;
                $model_APD->noantrian = $APD['noantrian'] ?? $noAntrian;
            } else {
                $model_APD =  AntrianPasienDiperiksa::where('norec', $APD['norec'])->first();
                if ($PD['objectruanganlastfk'] != $model_APD->objectruanganfk) {
                    $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['objectruanganlastfk'])
                        ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                        ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                        ->where('statusenabled', true)
                        ->max('noantrian');
                    $noAntrian = $max + 1;
                    $model_APD->noantrian = $noAntrian;
                }
                if ($PD['israwatinap'] == 'true') {
                    DB::table('tempattidur_m')
                        ->where('id', $model_APD->nobed)
                        ->sharedLock()
                        ->update(['objectstatusbedfk' =>  $SET['idStatusBedKosong']]);
                    // TempatTidur::where('id', $model_APD->nobed)->update();
                }
            }

            $model_APD->objectasalrujukanfk =  $PD['asalrujukanfk'];
            $model_APD->objectkamarfk = $APD['objectkamarfk'];
            $model_APD->objectruanganfk = $PD['objectruanganlastfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_APD->objectkelasfk = $PD['objectkelasfk'];
                $model_APD->kelasrawatfk = $PD['objectkelasrawatfk'];
                $model_APD->tglkeluar = null;
            } else {

                if($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek'] ){
                    $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
                }else{
                    $model_APD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_APD->tglkeluar = $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_APD->nobed = $APD['nobed'];
            $model_APD->noregistrasifk = $model_PD->norec;
            $model_APD->objectpegawaifk =  $PD['objectpegawaifk'];
            $model_APD->statusantrian = 0;
            $model_APD->status = "Belum Dipanggil";
            $model_APD->statuskunjungan = $PD['statuspasien'];
            $model_APD->tglregistrasi =  $PD['tglregistrasi'];
            $model_APD->tglmasuk = $PD['tglregistrasi'];
            $model_APD->israwatgabung = isset($PD['israwatgabung']) ? $PD['israwatgabung'] : null;
            $model_APD->nojkn = isset($PD['isjkn']) ? $PD['nojkn'] : null;
            $model_APD->noregistrasi = $noregistrasi;
            $model_APD->save();

            if ($PD['israwatinap'] == 'true') {
                $cek = DB::table('tempattidur_m')
                ->where('kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id',  $APD['nobed'])
                ->first();

                if (!empty($cek) && $cek->objectstatusbedfk == $SET['idStatusBedIsi'] ) {
                    DB::rollBack();
                    $transMessage = 'Bed Sudah Terisi, Silakan Pilih Bed Lain';
                    $result = array("status" => 400 ,'message' => $transMessage, "result"  => $cek);
                    return $this->respond($result, $result['status'], $transMessage);
                }

                DB::table('tempattidur_m')
                    ->where('kdprofile', $this->kdProfile)
                    ->where('statusenabled', true)
                    ->where('id',  $APD['nobed'])
                    ->lockForUpdate()
                    ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

                $this->historyBED([
                    "tempattidurfk" =>  $APD['nobed'],
                    "statusbedfk" => $SET['idStatusBedIsi'],
                    "ruanganfk" => $PD['objectruanganlastfk'],
                    "kamarfk" => $APD['objectkamarfk'],
                ]);
            }

            //endregion
            $ps = Pasien::where('id', $PD['nocmfk'])->first();
            $this->LOGGING(
                'Registrasi Pasien',
                $model_PD->norec,
                'pasiendaftar_t',
                $namaLog . ' pada Pasien ' .  $ps->namapasien . ' (' . $ps->nocm . ') - ' . $noregistrasi
            );

            Pasien::where('id', $PD['nocmfk'])->update([
                'sitb_id' => isset($PD['sitb_id']) ? $PD['sitb_id'] : null
            ]);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
            } else {
            DB::commit();
            }
            $aplicare = null;
            if ($PD['israwatinap'] == 'true') {
                $objetoRequest = new \Illuminate\Http\Request();
                $objetoRequest['idruangan'] = $model_APD->objectruanganfk;
                $objetoRequest['idkelas'] = $model_APD->objectkelasfk;
                $aplicare = app('App\Http\Controllers\Bridging\BridgingBPJSCtrl')->updateAplicaresBedAfter($objetoRequest);
            }

            // $ihs = null;
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noregistrasi'] = $model_PD->noregistrasi;
            // $ihs = app('App\Http\Controllers\Bridging\SATUSEHATCtrl')->Encounter($objetoRequest, true);
            $result = array(
                "status" => 200,
                "result" => array(
                    "dataPD" => $model_PD,
                    "dataAPD" => $model_APD,
                    "registrasi"  => array(
                        "pd" => $model_PD,
                        "apd" => $model_APD,#
                        "nocm" => $ps->nocm,
                    ),
                    // 'Encounter' => $ihs,
                    'Aplicare' => $aplicare,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
                Log::info("Simpan JKN gagal ". $e->getMessage(). " ". $e->getLine() );
            } else {
            DB::rollBack();
            }
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function listRuanganByLoginUser(Request $r)
    {
        $result = DB::table('maploginusertoruangan_s as mkr')
            ->join('loginuser_s as lg', 'lg.id', '=', 'mkr.objectloginuserfk')
            ->join('ruangan_m as rk', 'rk.id', '=', 'mkr.objectruanganfk')
            ->select('rk.id', 'rk.namaruangan')
            ->where('lg.id', $this->getUserId())
            ->where('mkr.statusenabled', true)
            ->where('rk.statusenabled', true)
            ->distinct()
            ->where('mkr.kdprofile', $this->kdProfile)
            ->orderBy('rk.namaruangan')
            ->get();
        return $this->respond($result);
    }

    public function checkIsExsist(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru','pd.objectruanganlastfk','ru.id')
            ->select('ru.namaruangan')
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.nocmfk', $request['nocmfk'])
            ->whereDate('pd.tglregistrasi', Carbon::today())
            ->orderByDesc('pd.tglregistrasi')
            // ->latest();
            ->first();

        return $this->respond($data);
    }

    public function checkIsLessOneMonth(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru','pd.objectruanganlastfk','ru.id')
            // ->select('ru.namaruangan')
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.nocmfk', $request['nocmfk'])
            ->where('pd.objectruanganlastfk', $request['ruangan'])
            // ->whereDate('pd.tglregistrasi', Carbon::today())
            ->orderByDesc('pd.tglregistrasi')
            // ->latest();
            ->first();

            if ($data) {
                $tglRegistrasi = Carbon::parse($data->tglregistrasi);
                $now = Carbon::now();

                // Cek apakah sudah 1 bulan atau lebih
                if ($tglRegistrasi->diffInDays($now) >= 30) {
                    // Sudah lebih dari atau sama dengan 1 bulan
                    $data->isBelumSatuBulan = false;
                } else {
                    // Belum lebih dari 1 bulan
                    $data->isBelumSatuBulan = true;
                }
            } else {
            }

        return $this->respond($data);
    }

    public function confirmReservasi(Request $request)
    {
        $dataPD = DB::table('pasiendaftar_t')->select('*')->where('norec', $request->norec)->first();
        AntrianPasienRegistrasi::where('norec', $dataPD->antrianpasienregistrasifk)
                    ->update(['isconfirm' => true, 'pasiendaftarfk' => $dataPD->norec]);
        $result = array(
            "status" => 200,
            "result" => array(
                "as" => '@epic',
            ),
        );
        return $this->respond($result);
    }


    public function saveAdministrasi(Request $request){

        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;

             AntrianPasienDiperiksa::where('norec',$request['norec_apd'])
                ->update([
                    'ispelayananpasien' => true
                ]);
                $pasiendaftar = PasienDaftar::where('norec',$request['norec'])->first();
                $pasiendaftar->ispelayananpasien = true;
                $pasiendaftar->save();

                $pasien = Pasien::where('id',$pasiendaftar->nocmfk)->first();
                $data = DB::select(DB::raw("select pp.tglpelayanan,pd.objectkelasfk,
                    pd.objectruanganlastfk
                    from pasiendaftar_t as pd
                    INNER JOIN antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
                    INNER JOIN pelayananpasien_t as pp on pp.noregistrasifk=apd.norec
                    INNER JOIN produk_m as pr on pr.id=pp.produkfk
                    INNER JOIN ruangan_m as ru_pd on ru_pd.id=pd.objectruanganlastfk
                    where  pd.norec='$request[norec]'
                    and pd.kdprofile=$kdProfile
                    and pp.produkfk in (
                        select
                        objectprodukfk
                        from mapruangantoadministrasi_t
                        where objectruanganfk=pd.objectruanganlastfk
                        and statusenabled=true
                    )
                "));

                if (count($data) == 0) {
                    $sirahMacan = [];
                    $idpenjamin = "-1";//$pasiendaftar->objectrekananfk == null ? '-1' : $pasiendaftar->objectrekananfk;
                    if ($idpenjamin != "-1") {
                            $sirahMacan = DB::select(DB::raw("
                                    select hett.* from mapruangantoadministrasi_t as map
                                    INNER JOIN harganettoprodukbykelas_m as hett on hett.objectprodukfk=map.objectprodukfk
                                    and hett.objectjenispelayananfk =map.jenispelayananfk
                                    where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                    and map.jenispelayananfk=:jenispelayanan
                                    and map.kdprofile=:kdprofile
                                    and map.statusenabled=true
                                    and hett.statusenabled=true
                                    and hett.objectpenjaminfk = $idpenjamin
                            "),array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile

                            )
                        );
                    }else{
                        $sirahMacan = [];
                    }

                    if(count($sirahMacan) == 0){
                        $sirahMacan = DB::select(DB::raw("
                                    select hett.* from mapruangantoadministrasi_t as map
                                    INNER JOIN harganettoprodukbykelas_m as hett on hett.objectprodukfk=map.objectprodukfk
                                    and hett.objectjenispelayananfk =map.jenispelayananfk
                                    where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                    and map.jenispelayananfk=:jenispelayanan
                                    and map.kdprofile=:kdprofile
                                    and map.statusenabled=true
                                    and hett.statusenabled=true
                                    and hett.objectpenjaminfk IS NULL
                            "),array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile

                            )
                        );
                    }

                    $buntutMacan = [];
                    if ($idpenjamin != "-1") {
                        $buntutMacan = DB::select(DB::raw("
                                select hett.* from mapruangantoadministrasi_t as map
                                INNER JOIN harganettoprodukbykelasd_m as hett on hett.objectprodukfk=map.objectprodukfk
                                and hett.objectjenispelayananfk =map.jenispelayananfk
                                where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                and map.jenispelayananfk=:jenispelayanan
                                and map.kdprofile=:kdprofile
                                and map.statusenabled=true
                                and hett.statusenabled=true
                                and hett.objectpenjaminfk = $idpenjamin
                                "),
                            array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile
                            )
                        );
                    }else{
                        $buntutMacan = [];
                    }

                    if(count($buntutMacan) == 0){
                        $buntutMacan = DB::select(DB::raw("
                                select hett.* from mapruangantoadministrasi_t as map
                                INNER JOIN harganettoprodukbykelasd_m as hett on hett.objectprodukfk=map.objectprodukfk
                                and hett.objectjenispelayananfk =map.jenispelayananfk
                                where map.objectruanganfk=:ruanganid and hett.objectkelasfk=:kelasid
                                and map.jenispelayananfk=:jenispelayanan
                                and map.kdprofile=:kdprofile
                                and map.statusenabled=true
                                and hett.statusenabled = true
                                and hett.objectpenjaminfk IS NULL
                                "),
                            array(
                                'ruanganid' => $pasiendaftar->objectruanganlastfk,
                                'kelasid' => $pasiendaftar->objectkelasfk,
                                'jenispelayanan' => $pasiendaftar->jenispelayanan,
                                'kdprofile' =>$kdProfile
                            )
                        );
                    }
                    foreach ($sirahMacan as $k) {
                        $PelPasien = new PelayananPasien();
                        $PelPasien->norec = $PelPasien->generateNewId();
                        $PelPasien->kdprofile = $kdProfile;
                        $PelPasien->statusenabled = true;
                        $PelPasien->noregistrasifk = $request['norec_apd'];//$dataDong[0]->norec_apd;
                        $PelPasien->tglregistrasi = $pasiendaftar->tglregistrasi;
                        $PelPasien->hargadiscount = 0;//0;
                        $PelPasien->hargajual = $k->hargasatuan;
                        $PelPasien->hargasatuan = $k->hargasatuan;
                        $PelPasien->jumlah = 1;
                        $PelPasien->kelasfk = $pasiendaftar->objectkelasfk;
                        $PelPasien->kdkelompoktransaksi = 1;
                        $PelPasien->piutangpenjamin = 0;
                        $PelPasien->piutangrumahsakit = 0;
                        $PelPasien->produkfk = $k->objectprodukfk;
                        $PelPasien->stock = 1;
                        $PelPasien->tglpelayanan = date('Y-m-d H:i:s');
                        $PelPasien->harganetto = $k->harganetto1;
                        $PelPasien->isadministrasi = true;
                        $PelPasien->keteranganlain = 'administrasi otomatis';
                        $PelPasien->noregistrasi = $pasiendaftar->noregistrasi;
                        $PelPasien->save();
                        $PPnorec = $PelPasien->norec;
                        foreach ($buntutMacan as $itemKomponen) {
                            if($itemKomponen->objectprodukfk == $k->objectprodukfk) {
                                $PelPasienDetail = new PelayananPasienDetail();
                                $PelPasienDetail->norec = $PelPasienDetail->generateNewId();
                                $PelPasienDetail->kdprofile = $kdProfile;
                                $PelPasienDetail->statusenabled = true;
                                $PelPasienDetail->noregistrasifk = $request['norec_apd'];
                                $PelPasienDetail->aturanpakai = '-';
                                $PelPasienDetail->hargadiscount = 0;
                                $PelPasienDetail->hargajual = $itemKomponen->hargasatuan;
                                $PelPasienDetail->hargasatuan = $itemKomponen->hargasatuan;
                                $PelPasienDetail->jumlah = 1;
                                $PelPasienDetail->keteranganlain = 'admin otomatis';
                                $PelPasienDetail->keteranganpakai2 = '-';
                                $PelPasienDetail->komponenhargafk = $itemKomponen->objectkomponenhargafk;
                                $PelPasienDetail->pelayananpasien = $PPnorec;
                                $PelPasienDetail->piutangpenjamin = 0;
                                $PelPasienDetail->piutangrumahsakit = 0;
                                $PelPasienDetail->produkfk = $itemKomponen->objectprodukfk;
                                $PelPasienDetail->stock = 1;
                                $PelPasienDetail->tglpelayanan = date('Y-m-d H:i:s');
                                $PelPasienDetail->harganetto = $itemKomponen->harganetto1;
                                $PelPasienDetail->noregistrasi = $pasiendaftar->noregistrasi;
                                $PelPasienDetail->save();
                            }

                        }
                    }

                }

                $this->LOGGING(
                    'Administrasi Otomatis',
                    $pasiendaftar->norec,
                    'pasiendaftar_t',
                    'Administrasi Otomatis pada Pasien ' .  $pasien->namapasien . ' (' . $pasien->nocm . ') - ' . $pasiendaftar->noregistrasi
                );

            $transMessage = "Administrasi Otomatis";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
