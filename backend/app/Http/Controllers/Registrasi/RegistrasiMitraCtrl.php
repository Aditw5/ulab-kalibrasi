<?php

namespace App\Http\Controllers\Registrasi;


use App\Http\Controllers\Controller;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\MitraRegistrasi;
use App\Models\Transaksi\MitraRegistrasiDetail;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasien;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class RegistrasiMitraCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function mitraRegistrasi(Request $r)
    {
        $data = DB::table('mitra_m as mt')
            ->select(
                'mt.id as nocmfk',
                'mt.namaperusahaan',
                'mt.nohp',
                'mt.email',
                'mt.tgldaftar',
                'mt.alamatktr',
            )
            ->where('mt.statusenabled', true);

        if (isset($r['id']) && $r['id'] != '' && $r['id'] != 'undefined') {
            $data = $data->where('mt.id', $r['id']);
        }

        $data = $data->first();
        
        $result['mitra'] = $data;
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

    public function saveRegistrasiMitra(Request $r)
    {
        DB::beginTransaction();
        try {
            $PD = $r['mitraregistrasi'];
            $APD = $r['mitraregistrasidetail']; 
            if ($PD['norec'] == '') {
                $lokasi = $PD['lokasikalibrasi'];
                switch ($lokasi) {
                    case 1: $lokasiPrefix = 'J'; break;
                    default: $lokasiPrefix = 'G';
                }
                $tahun = date('y');
                $lastPendaftaran = MitraRegistrasi::where('lokasikalibrasi', $lokasi)
                    ->whereYear('tglregistrasi', date('Y'))
                    ->orderByDesc('nopendaftaran')
                    ->first();

                if ($lastPendaftaran && preg_match('/(\d{3})$/', $lastPendaftaran->nopendaftaran, $m)) {
                    $urut = intval($m[1]) + 1;
                } else {
                    $urut = 1;
                }
                $urut3digit = str_pad($urut, 3, '0', STR_PAD_LEFT);
                $nopendaftaran = $lokasiPrefix . '-' . $tahun . '-' . $urut3digit;
                $model_PD = new MitraRegistrasi();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->statusenabled = true;
            } else {
                $model_PD =  MitraRegistrasi::where('norec', $PD['norec'])->first();
                $nopendaftaran = $model_PD->nopendaftaran;
            }
            $model_PD->nomitrafk = $PD['nomitrafk'];
            $model_PD->tglregistrasi =  $PD['tglregistrasi'];
            $model_PD->nopendaftaran = $nopendaftaran;
            $model_PD->catatan = $PD['catatan'];
            $model_PD->lokasikalibrasi = $PD['lokasikalibrasi'];
            $model_PD->namapenanggungjawab = $PD['namapenanggungjawab'];
            $model_PD->jabatanpenanggungjawab = $PD['jabatanpenanggungjawab'];
            $model_PD->petugas = $this->getNamaPegawai();
            $model_PD->statusorder = 0;
            $model_PD->statusordermanager = 0;
            $model_PD->rentangUkur = $PD['rentangUkur'];
            $model_PD->rentangUkurketPermintaanPelanggan = $PD['rentangUkurketPermintaanPelanggan'];
            $model_PD->save();

            $dataAPD = [];
            foreach ($APD as $alat) {
                if (isset($alat['norec']) && $alat['norec'] != '') {
                    $model_APD = MitraRegistrasiDetail::where('norec', $alat['norec'])->first();
                } else {
                    $model_APD = new MitraRegistrasiDetail;
                    $model_APD->norec = $model_APD->generateNewId();
                    $model_APD->statusenabled = true;
                }
                $model_APD->namaalatfk = $alat['namaalatfk'] ?? null; 
                $model_APD->namamerkfk = $alat['namamerkfk'] ?? null;
                $model_APD->namatipefk = $alat['namatipefk'] ?? null;
                $model_APD->serialnumberfk = $alat['serialnumberfk'] ?? null;
                $model_APD->noregistrasifk = $model_PD->norec;
                $model_APD->save();

                $dataAPD[] = $model_APD;
            }

           $transMessage = "Simpan Registrasi Mitra Sukses";
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
