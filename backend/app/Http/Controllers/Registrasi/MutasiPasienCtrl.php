<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\AsalRujukan;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pekerjaan;
use App\Models\Master\Pendidikan;
use App\Models\Master\Ruangan;
use App\Models\Master\TempatTidur;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MutasiPasienCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function headMutasi(Request $r)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('pemakaianasuransi_t as pas', 'pas.noregistrasifk', '=', 'pd.norec')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.tempatlahir',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'ps.objectagamafk',
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
                'pas.klsrawathak_nama as kelasrawat'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true)
            ->where('ps.id', $r['nocmfk'])
            ->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi =   DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->LEFTJOIN('ruangan_m as rupes', 'rupes.id', '=', 'pd.ruangannextschedule')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'apd.norec as norec_apd',
                'pd.objectruanganlastfk',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.nocmfk',
                'apd.tglmasuk',
                'pd.ruangannextschedule',
                'rupes.namaruangan as namaruanganpesanan',
                'apd.tglkeluar',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->where('pd.nocmfk', $r['nocmfk'])
            ->where('pd.norec', $r['norec_pd'])
            ->get();
        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last  = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['idKelompokPasienBPJS'] = explode(',', $this->settingFix('idKelompokPasienBPJS'));
        $result['idKelompokPasienUMUM'] = $this->settingFix('idKelompokPasienUMUM');

        $set = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $result['ruangan'] = Ruangan::mine()->whereIn('objectdepartemenfk', $set)->get();

        $result['jenispelayanan'] = JenisPelayanan::mine()->get();
        $result['asalrujukan'] = AsalRujukan::mine()->get();
        $result['kelompokpasien'] = KelompokPasien::mine()->get();
        $result['pekerjaan'] = Pekerjaan::mine()->get();
        $result['pendidikan'] = Pendidikan::mine()->get();
        $result['kelas'] = Kelas::mine()->get();
        $result['as'] = '@epic';

        return $this->respond($result);
    }


    public function dokterMutasi(Request $r)
    {
        $result['idJenisPegawaiDokter'] = explode(',', $this->settingFix('idJenisPegawaiDokter'));
        $result['dokter'] =
            Pegawai::mine()
            ->where('objectjenispegawaifk', $result['idJenisPegawaiDokter'])
            ->search($r['name'])
            ->paging($r['limit'])
            ->get();
        return $this->respond($result);
    }
    public function listKelasMutasi(Request $r)
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
    public function listKamarMutasi(Request $r)
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
            ->where('statusenabled', true)
            ->where('objectstatusbedfk', $result['idStatusBedKosong'])
            ->orderBy('reportdisplay')
            ->get();

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
    public function listPenjaminMutasi(Request $r)
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

    // public function saveMutasiPasien(Request $request) {
    //     $kdProfile = $this->kdProfile;
    //          DB::beginTransaction();
    //          if ($request['tglpulang']!= 'null'){
    //              $ddddd=PasienDaftar::where('norec', $request['norec'])
    //                  ->update([
    //                          'tglpulang' => null,
    //                          'objectruanganlastfk' => $request['ruangan']['id'],
    //                          'objectkelompokpasienlastfk' => $request['kelompokPasien']['id'],
    //                          'objectpegawaifk' => $request['pegawai']['id'],
    //                          'objectkelasfk' => $request['kelas']['id'],
    //                          'nostruklastfk' => null,
    //                          'nosbmlastfk' => null

    //                      ]
    //                  );
    //          }
    //          try{

    //              $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk',$request['ruangan']['id'])
    //                  ->where('tglregistrasi', '>=', $request['tglRegisDateOnly'].' 00:00')
    //                  ->where('tglregistrasi', '<=', $request['tglRegisDateOnly'].' 23:59')
    //                  ->count('norec');
    //              $noAntrian = $countNoAntrian + 1;

    //              $dataAPD = new AntrianPasienDiperiksa();
    //              $dataAPD->kdprofile = $kdProfile;
    //              $dataAPD->statusenabled = true;
    //              $dataAPD->norec =  $dataAPD->generateNewId();
    //              $dataAPD->objectasalrujukanfk =  $request['asalRujukan']['id'];
    //              $dataAPD->objectkamarfk = $request['kamar']['id'];
    //              $dataAPD->objectkasuspenyakitfk = null;
    //              $dataAPD->objectkelasfk = $request['kelas']['id'];
    //              $dataAPD->noantrian = $noAntrian; //count tgl pasien perruanga
    //              $dataAPD->nobed = $request['nomorTempatTidur']['id'];
    //              $dataAPD->noregistrasifk = $request['noRecPasienDaftar'];
    //              $dataAPD->objectpegawaifk = $request['pegawai']['id'];
    //              $dataAPD->objectruanganfk = $request['ruangan']['id'];
    //              $dataAPD->statusantrian = 0;
    //              $dataAPD->statuskunjungan = $request['statusPasien'];
    //              $dataAPD->statuspasien = 1;
    //              $dataAPD->tglregistrasi =  $request['tglRegistrasi'];
    //              $dataAPD->objectruanganasalfk = $request['objectruanganasalfk'];
    //              $dataAPD->tglkeluar = null;
    //              $dataAPD->tglmasuk =$request['tglRegistrasi'];
    //              $dataAPD->israwatgabung = null;

    //              $dataAPD->save();

    //              //update statusbed jadi Isi
    //              TempatTidur::where('id',$request['nomorTempatTidur']['id'])->update(['objectstatusbedfk'=>1]);

    //              $transStatus = 'true';
    //     } catch (\Exception $e) {
    //         $transStatus = 'false';
    //     }

    //     if ($transStatus == 'true') {
    //         $transMessage = "Sukses";
    //         DB::commit();
    //         $result = array(
    //             "status" => 200,
    //             "result" => array(
    //                 "registrasi"  => array(
    //                     "pd" => $ddddd,
    //                     "apd" => $dataAPD,
    //                 ),
    //                 "as" => '@epic',
    //             ),
    //         );
    //     } else {
    //         $transMessage = "Simpan Gagal";
    //         DB::rollBack();
    //         $result = array(
    //             "status" => 400,
    //             "result"  => null
    //         );
    //     }
    //     return $this->respond($result['result'], $result['status'], $transMessage);
    // }

    public function saveMutasi(Request $request)
    {
        DB::beginTransaction();
        try {
            $kdProfile = $this->kdProfile;
            $r_NewPD = $request['pasiendaftar'];
            $r_NewAPD = $request['antrianpasiendiperiksa'];
            $r_Pasien = $request['pasien'];
            $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
            $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');

            $cekDepartemen = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as ru', 'pd.objectruanganlastfk', 'ru.id')
                ->select('ru.objectdepartemenfk', 'ru.namaruangan', 'pd.noregistrasi')
                ->where('pd.norec', $r_NewPD['norec_pd'])
                ->where('pd.kdprofile', $this->kdProfile)
                ->first();

            $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';
            if ($isIGD == 'true') {
                // update tgl keluar apd
                AntrianPasienDiperiksa::where('norec', $r_NewAPD)->where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                    ->update(['tglkeluar' => date('Y-m-d H:i:s')]);
                $cekRI = null;
            } else {
                $cekRI = PasienDaftar::where('norec', $r_NewPD['norec_pd'])
                    ->whereNull('tglpulang')
                    ->where('statusenabled', true)
                    ->first();
            }

            if (!empty($cekRI)) {
                DB::rollBack();
                $transMessage = 'Pasien Terdaftar di Rawat Inap No. Registrasi : '
                    . $cekRI->noregistrasi . ' (' . $cekRI->tglregistrasi . ')';
                $result = array("status" => 400, "result"  => $cekRI);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }

            if ($request['tglpulang'] != 'null') {
                PasienDaftar::where('norec', $r_NewPD['norec_pd'])->update([
                    'tglpulang' => null,
                    'objectruanganlastfk' => $r_NewPD['objectruangantujuanfk'],
                    'objectkelasfk' => $r_NewPD['objectkelasfk'],
                    'objectkelasrawatfk' => $r_NewPD['objectkelasrawatfk'],
                    'objectkelompokpasienlastfk' => $r_NewPD['objectkelompokpasienlastfk'],
                    'objectpegawaifk' => $r_NewPD['objectpegawaifk'],
                    'bahasa' => $r_NewPD['bahasa'],
                    'bantuanpenerjemah' => $r_NewPD['bantuanpenerjemah'],
                    'bantuanpelayanan' => $r_NewPD['bantuanpelayanan'],
                    'dikunjungi' => $r_NewPD['dikunjungi'],
                    'residencefk' => $r_NewPD['residencefk'],
                    'alatbantu' => $r_NewPD['alatbantu'],
                    'nostruklastfk' => null,
                    'nosbmlastfk' => null,
                    'ruangannextschedule' => null,
                    'statusschedule' => null,
                    'catatan' => $r_NewPD['isrencanabpjs'] ? $r_NewPD['isrencanabpjs'] : null,
                ]);
            }

            Pasien::where('id', $r_NewPD['nocmfk'])->update([
                'penanggungjawab'=> $r_Pasien['penanggungjawab'],
                'hubungankeluargapj'=> $r_Pasien['hubungankeluargapj'],
                'telponpenanggungjawab'=> $r_Pasien['telponpenanggungjawab'],
                'jeniskelaminpenanggungjawab'=> $r_Pasien['jeniskelaminpenanggungjawab'],
                'umurpenanggungjawab'=> $r_Pasien['umurpenanggungjawab'],
                'pekerjaanpenangggungjawab'=> $r_Pasien['pekerjaanpenanggungjawab'],
                'pendidikanpenanggungjawab'=> $r_Pasien['pendidikanpenanggungjawab'],
                'alamatrmh'=> $r_Pasien['alamatrmh'],
            ]);

            $countNoAntrian = AntrianPasienDiperiksa::where('objectruanganfk', $r_NewPD['objectruangantujuanfk'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($r_NewAPD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=',  date('Y-m-d', strtotime($r_NewAPD['tglregistrasi'])) . ' 23:59')
                ->count();
            $noAntrian = $countNoAntrian + 1;

            $pd = PasienDaftar::where('norec', $r_NewPD['norec_pd'])->first();

            $dataAPD = new AntrianPasienDiperiksa;
            $dataAPD->norec = $dataAPD->generateNewId();
            $dataAPD->kdprofile = $kdProfile;
            $dataAPD->statusenabled = true;
            $dataAPD->objectruanganfk = $r_NewPD['objectruangantujuanfk'];
            $dataAPD->objectasalrujukanfk =  $r_NewAPD['objectasalrujukanfk'];
            $dataAPD->objectkamarfk = $r_NewAPD['objectkamarfk'];
            $dataAPD->objectpegawaifk = $r_NewAPD['objectpegawaifk'];
            $dataAPD->objectkelasfk = $r_NewAPD['objectkelasfk'];
            $dataAPD->kelasrawatfk = $r_NewAPD['objectkelasrawatfk'];
            $dataAPD->noantrian = $noAntrian; //count tgl pasien perruanga
            $dataAPD->nobed = $r_NewAPD['objectbedfk'];
            $dataAPD->noregistrasifk = $r_NewPD['norec_pd'];
            $dataAPD->statusantrian = 0;
            $dataAPD->status = "Belum Dipanggil";

            // $dataAPD->statuskunjungan =$r_NewPD['statuspasien'];
            $dataAPD->statuspasien = 1;
            // $dataAPD->tglregistrasi =  $pd->tglregistrasi; //$r_NewAPD['tglregistrasi'];
            $dataAPD->objectruanganasalfk = $r_NewAPD['objectruanganasalfk'];
            $dataAPD->tglkeluar = null;
            $dataAPD->tglmasuk = $r_NewAPD['tglregistrasi'];
            $dataAPD->israwatgabung = $r_NewAPD['israwatgabung'];
            $dataAPD->noregistrasi = $cekDepartemen->noregistrasi;

            $dataAPD->save();

            $dataPasien = DB::table('pasiendaftar_t as pd')
                ->join('ruangan_m as rutu', 'rutu.id', 'pd.objectruanganlastfk')
                ->join('ruangan_m as ruas', 'ruas.id', 'pd.objectruanganasalfk')
                ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
                ->select('ps.namapasien', 'rutu.namaruangan as ruangantujuan', 'ruas.namaruangan as ruanganasal', 'pd.noregistrasi', 'ps.nocm')
                ->where('pd.kdprofile', $this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.norec', $r_NewPD['norec_pd'])
                ->first();

            //update statusbed jadi Isi
            $cek = DB::table('tempattidur_m')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('id',  $dataAPD->nobed)
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
                ->where('id', $dataAPD->nobed)
                ->lockForUpdate()
                ->update(['objectstatusbedfk' =>  $SET['idStatusBedIsi']]);

            $this->historyBED([
                "tempattidurfk" => $dataAPD->nobed,
                "statusbedfk" => $SET['idStatusBedIsi'],
                "ruanganfk" => $r_NewPD['objectruangantujuanfk'],
                "kamarfk" => $r_NewAPD['objectkamarfk'],
            ]);
            $this->LOGGING(
                'Mutasi Pasien',
                $r_NewPD['norec_pd'],
                'pasiendaftar_t',
                'Mutasi pasien atas nama ' . $dataPasien->namapasien . ' (' .  $dataPasien->nocm  . ') ' .
                ' dari ' . $dataPasien->ruanganasal . ' ke ' . $dataPasien->ruangantujuan . ' - '. $dataPasien->noregistrasi
            );


            $objetoRequest3 = new Request();
            $objetoRequest3['norec'] = $pd->norec;
            $objetoRequest3['norec_apd'] = $dataAPD->norec;
            // $objetoRequest2['user'] = $this->getNamaPegawai();
            $post3 = app('App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl')->saveAdministrasi($objetoRequest3);


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
}
