<?php
/**
 * Created by PhpStorm.
 * User: Egie Ramdan
 * Date: 10/3/2019
 * Time: 4:26 PM
 */
namespace App\Http\Controllers\KiosK;

use App\Http\Controllers\Controller;
use App\Models\Master\SlottingKiosk;
use App\Models\Master\JenisKelamin;
use App\Models\Master\StatusPerkawinan;
use App\Models\Master\Agama;
use App\Models\Master\Pendidikan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Traits\Valet;
use Illuminate\Support\Facades\DB;
use App\Models\SurveyKepuasanPelanggan;
use App\Models\Transaksi\AntrianPasienRegistrasi;

class KiosKController extends Controller
{
    use Valet;
    // , PelayananPasienTrait;

    public function __construct()
    {
        parent::__construct($skip_authentication = false);
    }
    public function saveAntrianTouchscreen(Request $request)
    {
         $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        $noRec = '';
        try {
        $tglAyeuna = date('Y-m-d H:i:s');
        $tglAwal = date('Y-m-d 00:00:00');
        $tglAkhir = date('Y-m-d 23:59:59');
        $kdRuanganTPP = $this->settingFix('idRuanganTPP1');

            $newptp = new AntrianPasienRegistrasi();
            $norec = $newptp->generateNewId();
            $nontrian = AntrianPasienRegistrasi::where('jenis', $request['jenis'])
                    ->where('loketkiosk', $request['loketid'])
                    ->whereBetween('tanggalreservasi', [$tglAwal, $tglAkhir])
                    ->max('noantrian') + 1;
            $newptp->norec = $norec;
            $newptp->kdprofile = $kdProfile;
            $newptp->statusenabled = true;
            $newptp->objectruanganfk = isset($request['ruanganfk']) ? $request['ruanganfk']: $kdRuanganTPP;
            $newptp->objectjeniskelaminfk = 0;
            $newptp->noantrian = $nontrian;
            $newptp->noreservasi = "-";
            $newptp->objectpendidikanfk = 0;
            $newptp->tanggalreservasi = $tglAyeuna;
            $newptp->tipepasien = "BARU";
            $newptp->type = "BARU";
            $newptp->jenis = $request['jenis'];
            $newptp->statuspanggil = 0;
            $newptp->iskiosk = true;
            $newptp->loketkiosk = $request['loketid'];
            $newptp->namapasien = isset($request['namapeserta']) ? $request['namapeserta'] : null;
            $newptp->nobpjs = isset($request['nopeserta']) ? $request['nopeserta'] : null;
            $newptp->save();
            $noRec = $newptp->norec;


            $noAntrian = $newptp->noantrian;
            $strJenis = $newptp->jenis;
            // $jenis = "";
            // if ($strJenis == "A"){
            //     $jenis = "Umum";
            // }elseif ($strJenis == "B"){
            //     $jenis = "BPJS";
            // }elseif ($strJenis == "C"){
            //     $jenis = "";
            // }elseif ($strJenis == "D"){
            //     $jenis = "";
            // }
            if (strlen($newptp->noantrian) == 1){
                $noAntrian = "00" . $newptp->noantrian;
            }else{
                $noAntrian = "0" . $newptp->noantrian;
            }

            $jmlAntrian = DB::table('antrianpasienregistrasi_t')
            ->select(DB::raw("count(noantrian) as jmlantri"))
            ->where('statuspanggil','=',0)
            ->whereBetween('tanggalreservasi',[$tglAwal, $tglAkhir])
            ->where('jenis','=', $request['jenis'])
            ->where('loketkiosk','=', $request['loketid'])
            ->first();

            $transMessage = "Simpan Antrian";
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Simpan Antrian Gagal";
        }

        if ($transStatus != 'false') {

            DB::commit();
            $result = array(
                "noRec" => $noRec,
                "last"=>$jmlAntrian!=null?$jmlAntrian->jmlantri:0,
                "status" => 200,
                "noAntri"=> $strJenis.'-'.$noAntrian,
                "message" => $transMessage,
            );
        } else {
            DB::rollBack();
            $result = array(
                "noRec" => $noRec,
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
        // return $this->setStatusCode($result['status'])->respond($result, $transMessage);

    }
    public function getRuanganByKodeInternal($kode,Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('ruangan_m')
            ->where('statusenabled',true)
            ->where('kdinternal', '=',$kode)
             ->where('kdprofile', '=',$kdProfile)
            ->whereRaw("(iseksekutif is null or iseksekutif =false)")
            ->orderBy('namaruangan')
            ->first();

        $result = array(
            'data' => $data,
            'as' => 'ramdan@epic',
        );
        return $this->respond($result);
    }
    public function getDiagnosaByKode($kode,Request $request)
    {
        $kdProfile = (int) $this->kdProfile;

        $data = DB::table('diagnosa_m')
            ->where('statusenabled',true)
            ->where('kddiagnosa', '=',$kode)
             ->where('kdprofile', '=',$kdProfile)
            ->first();

        $result = array(
            'data' => $data,
            'as' => 'ramdan@epic',
        );
        return $this->respond($result);
    }
    public function getKetersediaanTempatTidurView (Request $request)
    {

        $kdProfile = (int) $this->kdProfile;
        $namaruangan= $request['namaruangan'];
        $idkelas= $request['idkelas'];
        $dataLogin = $request->all();
        if($namaruangan == "" && $idkelas == ""){
            $data = DB::select(DB::raw("select  COUNT(x.idstatusbed) as kamartotal, SUM(x.kamarisi) as kamarisi, sum(x.kamarkosong) as kamarkosong,
			    sum(x.kamarprosesadmin) as kamarprosesadmin, sum(x.kamartakterpakai) as kamartakterpakai from
                (select
                 ru.namaruangan,
                 km.namakamar,
                 kl.id as kelasid,
                 kl.namakelas,
                 tt.reportdisplay,
                 tt.nomorbed,
                 sb.id as idstatusbed,
                 sb.statusbed,
                 (case when sb.id=1 then 1 else 0 end) as kamarisi,
                 (case when sb.id=2 then 1 else 0 end) as kamarkosong,
                 (case when sb.id=3 then 1 else 0 end) as kamarprosesadmin,
                 (case when sb.id=4 then 1 else 0 end) as kamartakterpakai
                 from tempattidur_m as tt
                 left join kamar_m as km on km.id = tt.objectkamarfk
                 left join ruangan_m as ru on ru.id = km.objectruanganfk
                 left join statusbed_m as sb on sb.id = tt.objectstatusbedfk
                 left join kelas_m as kl on kl.id = km.objectkelasfk
                 where ru.objectdepartemenfk in (16,35) and ru.statusenabled=true
                 and ru.kdprofile =$kdProfile
				 and km.statusenabled=true and tt.statusenabled=true)as x  limit 1"),
                array(
//                    'namaruangan' => $namaruangan,
//                    'idkelas' => $idkelas,
                )
            );
        } elseif ($namaruangan != "" && $idkelas == ""){
            $data = DB::select(DB::raw("select COUNT(x.idstatusbed) as kamartotal, SUM(x.kamarisi) as kamarisi, sum(x.kamarkosong) as kamarkosong,
			    sum(x.kamarprosesadmin) as kamarprosesadmin, sum(x.kamartakterpakai) as kamartakterpakai from
                (select
                 ru.namaruangan,
                 km.namakamar,
                 kl.id as kelasid,
                 kl.namakelas,
                 tt.reportdisplay,
                 tt.nomorbed,
                 sb.id as idstatusbed,
                 sb.statusbed,
                 (case when sb.id=1 then 1 else 0 end) as kamarisi,
                 (case when sb.id=2 then 1 else 0 end) as kamarkosong,
                 (case when sb.id=3 then 1 else 0 end) as kamarprosesadmin,
                 (case when sb.id=4 then 1 else 0 end) as kamartakterpakai
                 from tempattidur_m as tt
                 left join kamar_m as km on km.id = tt.objectkamarfk
                 left join ruangan_m as ru on ru.id = km.objectruanganfk
                 left join statusbed_m as sb on sb.id = tt.objectstatusbedfk
                 left join kelas_m as kl on kl.id = km.objectkelasfk
                 where ru.objectdepartemenfk in (16,35)
                  and ru.kdprofile =$kdProfile
                  and ru.namaruangan=:namaruangan)as x"),
                array(
                    'namaruangan' => $namaruangan,
//                    'idkelas' => $idkelas,
                )
            );
        } elseif ($namaruangan == "" && $idkelas != ""){
            $data = DB::select(DB::raw("select COUNT(x.idstatusbed) as kamartotal, SUM(x.kamarisi) as kamarisi, sum(x.kamarkosong) as kamarkosong,
			    sum(x.kamarprosesadmin) as kamarprosesadmin, sum(x.kamartakterpakai) as kamartakterpakai from
                (select
                 ru.namaruangan,
                 km.namakamar,
                 kl.id as kelasid,
                 kl.namakelas,
                 tt.reportdisplay,
                 tt.nomorbed,
                 sb.id as idstatusbed,
                 sb.statusbed,
                 (case when sb.id=1 then 1 else 0 end) as kamarisi,
                 (case when sb.id=2 then 1 else 0 end) as kamarkosong,
                 (case when sb.id=3 then 1 else 0 end) as kamarprosesadmin,
                 (case when sb.id=4 then 1 else 0 end) as kamartakterpakai
                 from tempattidur_m as tt
                 left join kamar_m as km on km.id = tt.objectkamarfk
                 left join ruangan_m as ru on ru.id = km.objectruanganfk
                 left join statusbed_m as sb on sb.id = tt.objectstatusbedfk
                 left join kelas_m as kl on kl.id = km.objectkelasfk
                 where ru.objectdepartemenfk in (16,35)
                  and ru.kdprofile =$kdProfile
                   and kl.id=:idkelas)as x"),
                array(
//                    'namaruangan' => $namaruangan,
                    'idkelas' => $idkelas,
                )
            );
        } else {
            $data = DB::select(DB::raw("select COUNT(x.idstatusbed) as kamartotal, SUM(x.kamarisi) as kamarisi, sum(x.kamarkosong) as kamarkosong,
			    sum(x.kamarprosesadmin) as kamarprosesadmin, sum(x.kamartakterpakai) as kamartakterpakai from
                (select
                 ru.namaruangan,
                 km.namakamar,
                 kl.id as kelasid,
                 kl.namakelas,
                 tt.reportdisplay,
                 tt.nomorbed,
                 sb.id as idstatusbed,
                 sb.statusbed,
                 (case when sb.id=1 then 1 else 0 end) as kamarisi,
                 (case when sb.id=2 then 1 else 0 end) as kamarkosong,
                 (case when sb.id=3 then 1 else 0 end) as kamarprosesadmin,
                 (case when sb.id=4 then 1 else 0 end) as kamartakterpakai
                 from tempattidur_m as tt
                 left join kamar_m as km on km.id = tt.objectkamarfk
                 left join ruangan_m as ru on ru.id = km.objectruanganfk
                 left join statusbed_m as sb on sb.id = tt.objectstatusbedfk
                 left join kelas_m as kl on kl.id = km.objectkelasfk
                 where ru.objectdepartemenfk in (16,35)
                  and ru.kdprofile =$kdProfile
                  and ru.namaruangan=:namaruangan and kl.id=:idkelas)as x"),
                array(
                    'namaruangan' => $namaruangan,
                    'idkelas' => $idkelas,
                )
            );
        }
        return $this->respond($data);
    }
    public function viewBed(Request $request)
    {
        $data= DB::table('tempattidur_m as tt')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'tt.objectkamarfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'km.objectruanganfk')
            ->leftjoin('statusbed_m as sb', 'sb.id', '=', 'tt.objectstatusbedfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'km.objectkelasfk')
            ->select('ru.id as idruangan','ru.namaruangan','km.id as idkamar','km.namakamar','tt.id as idtempattidur',
                'tt.reportdisplay','tt.nomorbed','sb.id as idstatusbed','sb.statusbed','kl.id as idkelas','kl.namakelas')
            ->whereIn('ru.objectdepartemenfk',array(16,35))
            ->where('ru.statusenabled',true)
            ->where('km.statusenabled',true)
            ->where('tt.statusenabled',true);

        if(isset($request['namaruangan']) && $request['namaruangan']!="" && $request['namaruangan']!="undefined"){
            $data = $data->where('ru.namaruangan','ilike','%'. $request['namaruangan'] .'%');
        };
        if(isset($request['namakamar']) && $request['namakamar']!="" && $request['namakamar']!="undefined"){
            $data = $data->where('km.namakamar','ilike','%'. $request['namakamar'] .'%');
        };
        if(isset($request['idkelas']) && $request['idkelas']!="" && $request['idkelas']!="undefined"){
            $data = $data->where('kl.id', $request['idkelas']);
        };
        if(isset($request['namabed']) && $request['namabed']!="" && $request['namabed']!="undefined"){
            $data = $data->where('tt.reportdisplay','ilike','%'. $request['namabed'] .'%');
        };
        if(isset($request['idstatusbed']) && $request['idstatusbed']!="" && $request['idstatusbed']!="undefined"){
            $data = $data->where('sb.id', $request['idstatusbed']);
        };
        $data = $data->get();


        return $this->respond($data);
    }
    public function getDataCombo(Request $request)
    {
        $dataLogin = $request->all();

        $dataRuangan = DB::table('ruangan_m as ru')
            ->select('ru.id','ru.namaruangan','ru.objectdepartemenfk','ru.kdinternal')
            ->where('ru.statusenabled', true)
            ->orderBy('ru.namaruangan')
            ->get();
        $dataKelas = DB::table('kelas_m as kl')
            ->select('kl.id', 'kl.namakelas')
            ->where('kl.statusenabled', true)
            ->orderBy('kl.namakelas')
            ->get();

        $result = array(
            'ruangan' => $dataRuangan,

            'kelas' => $dataKelas,

            'message' => 'ramdan',
        );

        return $this->respond($result);
    }
    public function getDaftarTarif(Request $request)
    {
        $filter = $request->all();
        $produkid= '';
        if  ($filter['produkId'] != ''){
            $produkid= 'AND pr.id = ' . $filter['produkId'];
        }
        $ruanganid = '';
        if ($filter['ruanganId'] != ''){
            $ruanganid ='AND mprtp.objectruanganfk =' . $filter['ruanganId'];
        }
        $kelasid = '';
        if ($filter['kelasId'] != ''){
            $kelasid ='AND kls.id =' . $filter['kelasId'];
        }
        $jenispelid = '';
        if ($filter['jenispelayananId'] != ''){
            $jenispelid ='AND jnsp.id =' . $filter['jenispelayananId'];
        }
        $namaproduk = '';
        if ($filter['namaproduk'] != ''){
            $namaproduk ="AND pr.namaproduk like '%" . $filter['namaproduk'] . "%'";
        }

//        $jenispelid =$filter['jenispelid'];
        $data =DB::select(DB::raw("
               SELECT
                distinct   pr.id,
                pr.namaproduk,
                hrpk.harganetto1 AS hargalayanan,kls.id as idkelas,kls.namakelas ,jnsp.id as jenispelayananid,jnsp.jenispelayanan,mprtp.objectruanganfk as ruid,
                  ru.id as ruid,ru.namaruangan
                FROM
                produk_m AS pr
                INNER JOIN mapruangantoproduk_m AS mprtp ON mprtp.objectprodukfk = pr.id


                LEFT JOIN harganettoprodukbykelas_m AS hrpk ON hrpk.objectprodukfk = pr.id
                INNER JOIN kelas_m as kls on kls.id=hrpk.objectkelasfk
                INNER JOIN jenispelayanan_m as jnsp on jnsp.id=hrpk.objectjenispelayananfk
                INNER JOIN ruangan_m as ru on ru.id=mprtp.objectruanganfk
                WHERE
                hrpk.statusenabled = true
                AND pr.statusenabled = true


                $produkid
                 $ruanganid
                 $kelasid $jenispelid $namaproduk

                 limit 100
                  ")
        );

        $results =array();

        $produkid= '';
        if  ($filter['produkId'] != ''){
            $produkid= 'AND pr.id = ' . $filter['produkId'];
        }
        $ruanganid = '';
        if ($filter['ruanganId'] != ''){
            $ruanganid ='AND mprtp.objectruanganfk =' . $filter['ruanganId'];
        }
        $kelasid =$filter['kelasId'];
//        $jenispelid =$filter['jenispelid'];
//        $details =DB::select(DB::raw("
//                select  pr.id as kdeproduk,pr.namaproduk,kls.id as klsid,kh.id as idkomponen,kh.komponenharga,hrpkd.harganetto1,
//                hrpkd.harganetto2,hrpkd.hargasatuan,jnspel.id as jnspelid
//                from produk_m as pr
//                INNER JOIN mapruangantoproduk_m AS mprtp ON mprtp.objectprodukfk = pr.id
//                left join detailjenisproduk_m as djp on djp.id = pr.objectdetailjenisprodukfk
//                left join jenisproduk_m as jp on jp.id = djp.objectjenisprodukfk
//                left join kelompokproduk_m as kp on kp.id = jp.objectkelompokprodukfk
//                left join harganettoprodukbykelasd_m as hrpkd on hrpkd.objectprodukfk = pr.id
//                inner join kelas_m as kls on kls.id = hrpkd.objectkelasfk
//                inner join komponenharga_m as kh on kh.id = hrpkd.objectkomponenhargafk
//                INNER JOIN jenispelayanan_m as jnspel on jnspel.id=hrpkd.objectjenispelayananfk
//                where pr.statusenabled=1 and hrpkd.statusenabled = 1
//                --and pr.id =1002120383
//                --and kls.id=1 and jnspel.id=1
//                $produkid $ruanganid ")
//        );
//        foreach ($data as $item){
//            foreach ($details as $dtl){
//                if  ($item->id == $dtl->kdeproduk){
//                    $results[] = array(
//                        'kodeproduk' => $item->id,
//                        'namaproduk' => $item->namaproduk,
//                        'kdruangan' => $item->koderuangan,
//                        'namaruangan' => $item->namaruangan,
//                        'idkelas' => $item->idkelas,
//                        'namakelas' => $item->namakelas,
//                        'hargalayanan' => $item->hargalayanan,
//                        'details' => $dtl,
//                    );
//                }
//            }
//
//
//        }
        $result = array(
            'data'=>$data,
//            'detail'=> $details,
            'message' => 'cepot',
        );
        return $this->respond($result);
    }
    public function getDataViewBed(Request $request){

        $data= DB::table('tempattidur_m as tt')
            ->leftjoin('kamar_m as km', 'km.id', '=', 'tt.objectkamarfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'km.objectruanganfk')
            ->leftjoin('statusbed_m as sb', 'sb.id', '=', 'tt.objectstatusbedfk')
            ->leftjoin('kelas_m as kl', 'kl.id', '=', 'km.objectkelasfk')
            ->select('ru.id as idruangan','ru.namaruangan','km.id as idkamar','km.namakamar','tt.id as idtempattidur',
                'tt.reportdisplay','tt.nomorbed','sb.id as idstatusbed','sb.statusbed','kl.id as idkelas','kl.namakelas')
            ->whereIn('ru.objectdepartemenfk',array(16,35))
            ->where('ru.statusenabled',true)
            ->where('km.statusenabled',true)
            ->where('tt.statusenabled',true);

        if(isset($request['namaruangan']) && $request['namaruangan']!="" && $request['namaruangan']!="undefined"){
            $data = $data->where('ru.namaruangan','ilike','%'. $request['namaruangan'] .'%');
        };
        if(isset($request['namakamar']) && $request['namakamar']!="" && $request['namakamar']!="undefined"){
            $data = $data->where('km.namakamar','ilike','%'. $request['namakamar'] .'%');
        };
        if(isset($request['idkelas']) && $request['idkelas']!="" && $request['idkelas']!="undefined"){
            $data = $data->where('kl.id', $request['idkelas']);
        };
        if(isset($request['namabed']) && $request['namabed']!="" && $request['namabed']!="undefined"){
            $data = $data->where('tt.reportdisplay','ilike','%'. $request['namabed'] .'%');
        };
        if(isset($request['idstatusbed']) && $request['idstatusbed']!="" && $request['idstatusbed']!="undefined"){
            $data = $data->where('sb.id', $request['idstatusbed']);
        };
        $data = $data->get();


        return $this->respond($data);
    }
      public function saveSurvey(Request $request)
        {
            $kdProfile = (int) $this->kdProfile;
            $dataLogin = $request->all();
            DB::beginTransaction();

            try {

                $newptp = new SurveyKepuasanPelanggan();
                $newptp->norec = $newptp->generateNewId();
                $newptp->kdprofile = $kdProfile;
                $newptp->statusenabled = true;
                $newptp->objectparameterkepuasanfk = $request['id'];
                $newptp->namalengkap =  $request['namalengkap'];
                $newptp->tglsurvey =  date('Y-m-d H:i:s');
                $newptp->save();
                $transMessage = "Simpan Survey";
                $transStatus = 'true';
            } catch (\Exception $e) {
                $transStatus = 'false';
                $transMessage = "Simpan Survey Gagal";
            }

            if ($transStatus != 'false') {

                DB::commit();
                $result = array(

                    "status" => 200,
                    "message" => $transMessage,
                );
            } else {
                DB::rollBack();
                $result = array(

                    "status" => 400,
                    "message" => $transMessage,
                );
            }
            return $this->setStatusCode($result['status'])->respond($result, $transMessage);

        }
        public function getComboDokterKios(Request $request){
            $kdProfile = (int) $this->kdProfile;
            $kdJenisPegawaiDokter = $this->settingFix('kdJenisPegawaiDokter');
            $req = $request->all();
            $data = DB::table('pegawai_m')
                ->select('id as kode','namalengkap as nama')
                ->where('statusenabled', true)
                ->where('objectjenispegawaifk',$kdJenisPegawaiDokter)
                ->where('kdprofile', (int)$kdProfile)
                ->orderBy('namalengkap');

            if(isset($req['namalengkap']) &&
                $req['namalengkap']!="" &&
                $req['namalengkap']!="undefined"){
                $data = $data->where('namalengkap','ilike','%'. $req['namalengkap'] .'%' );
            }


            $data = $data->take(50);
            $data = $data->get();

            return $this->respond($data);
    }
     public function getComboSettingKios(Request $request){
            $kdProfile = (int) $this->kdProfile;
            $kodePPKRujukan = $this->settingFix('kodePPKRujukan');
            $isTemporaryBrigding = $this->settingFix('isTemporaryBrigding');
            $isAdminOtomatisKiosk = $this->settingFix('isAdminOtomatisKiosk');
            $isAktifSlotRuanganKiosk = $this->settingFix('isAktifSlotRuanganKiosk');
            $isCetakDSKiosk = $this->settingFix('isCetakDSKiosk');

            $data['ppkpelayanan']= $kodePPKRujukan;
            $data['isTemporaryBrigding']= $isTemporaryBrigding;
            $data['isAdminOtomatisKiosk']= $isAdminOtomatisKiosk;
            $data['isAktifSlotRuanganKiosk']= $isAktifSlotRuanganKiosk;
            $data['isCetakDSKiosk']= $isCetakDSKiosk;
            return $this->respond($data);
    }

     public function getComboRuanganKios(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $set = explode (',',$this->settingFix('kdDepartemenKiosk'));
        $dp = [];
        foreach ($set as $it){
            $dp []=  (int)$it;
        }
        $tgl = date('Y-m-d');
//         $kiosk = DB::table('antrianpasienregistrasi_t as apr')
//             ->select('objectruanganfk as ruid', DB::raw('count(*) as total'))
// //            ->select('apr.norec','apr.tanggalreservasi','apr.objectruanganfk')
//             ->whereRaw(" to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tgl'")
// //            ->where('apr.objectruanganfk', $request['ruanganfk'])
//             ->where('apr.noreservasi','=','-')
//             ->where('apr.statusenabled',true)
//             ->where('apr.kdprofile',$kdProfile)
//             ->where('apr.statuspanggil','=',0)
//             ->groupBy('objectruanganfk')
//             ->get();

//         $dataSelfReg = DB::table('pasiendaftar_t')
//             ->select('objectruanganlastfk as ruid', DB::raw('count(*) as total'))
// //            ->select('norec','tglregistrasi as tanggalreservasi','objectruanganlastfk as objectruanganfk')
//             ->whereRaw(" to_char(tglregistrasi,'yyyy-MM-dd') = '$tgl'")
// //            ->where('objectruanganlastfk', $request['ruanganfk'])
//             ->where('statusenabled',true)
//             ->where('kdprofile',$kdProfile)
//             // ->where('statusschedule','Kios-K')
//             ->groupBy('objectruanganlastfk')
//             ->get();
//         $merge = array_merge($dataSelfReg,$kiosk);

        // SELECT
        //  objectruanganlastfk AS ruid,
        //     COUNT ( * ) AS total
        // FROM
        //     pasiendaftar_t
        // WHERE
        //     to_char( tglregistrasi, 'yyyy-MM-dd' ) = '$tgl'
        //     AND statusenabled = TRUE
        //     AND kdprofile = $kdProfile
        // GROUP BY
        //     objectruanganlastfk


        //     union all
        $merge = DB::select(DB::raw("select sum(x.total) as total ,x.ruid from (

        SELECT
            objectruanganfk AS ruid,
            COUNT ( * ) AS total
        FROM
            antrianpasienregistrasi_t AS apr
        WHERE
            to_char( apr.tanggalreservasi, 'yyyy-MM-dd' ) = '$tgl'
            --AND apr.noreservasi = '-'
            --AND apr.statusenabled = TRUE
            AND apr.jenis IS NOT NULL
            AND apr.kdprofile = $kdProfile
            --AND apr.statuspanggil = '0'
        GROUP BY
            objectruanganfk
            ) as x
            group by x.ruid"));

        $data = DB::table('ruangan_m as ru')
        ->join('slottingkiosk_m as sl', function ($join) use ($request,$tgl) {
            $join->on('sl.objectruanganfk','=','ru.id');
            $join->where('sl.statusenabled','=',true);
            $join->where('sl.loket','=',$request['loketid']);
            $join->where('sl.tanggal','=',$tgl);
        })
        ->where('ru.kdprofile',$kdProfile)
        ->where('ru.statusenabled',true)
        ->whereNotNull('ru.reportdisplay')
        ->whereNotNull('ru.noruangan')
        ->whereIn('ru.objectdepartemenfk',$dp)
        ->select('ru.id','ru.reportdisplay as namaruangan', 'ru.noruangan','ru.kdsubspesialisbpjs'
//               ,DB::raw("case when sl.quota is null then 0 else sl.quota end as quota, sl.hari")
        )
        ->orderby('ru.namaruangan')
        ->get();
        $hari = $this->hari_ini(date('Y-m-d'));
        $result = [];
        foreach ($data as $d){
            $d->quota = 0;
            $d->sisa = 0;
            $d->tedaftar = 0;
            $dataSlot = SlottingKiosk::where('objectruanganfk', $d->id)
                ->where('statusenabled',true)
                ->where('tanggal',date('Y-m-d'))
                // ->where('hari','ilike','%'.$hari.'%')
                ->first();
            if(!empty($dataSlot) ){
                $d->quota = $dataSlot->quota;
                $d->sisa = $dataSlot->quota;
            }
            foreach ($merge as $m){
                $total = 0;
                if($d->id == $m->ruid){
                    $total =  $total + (float)$m->total;
                    $d->tedaftar = $total;
                    $d->sisa =  (float) $d->quota - $total;
                }
                if($d->sisa < 0){
                    $d->sisa= 0;
                }
            }
            if(!empty($dataSlot))
                array_push($result, $d);
        }

        return $this->respond($result);
    }

    public function checkIsGetTicket(Request $request)
    {
        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->join('ruangan_m as ru', 'apr.objectruanganfk', '=', 'ru.id')
            ->select('ru.namaruangan', 'apr.noantrian', 'apr.jenis')
            ->where('apr.statusenabled', true)
            ->where('apr.kdprofile', $this->kdProfile)
            ->where('apr.nobpjs', $request->input('nobpjs'))
            ->whereDate('apr.tanggalreservasi', Carbon::today())
            ->orderByDesc('apr.tanggalreservasi')
            ->first();

        if ($data) {
            $result = array(
                'message' => 'Sudah pernah ambil tiket!',
                'data' => $data,
                'kode' => 409
            );
        } else {
            // Return 200 response if no ticket is taken
            $result = array(
                'message' => 'Tiket Available',
                'kode' => 200
            );
        }

        return $this->respond($result);
    }

    function filterByQuota($ruangan) {
        return $ruangan['quota'] != 0;
    }
    public function getSlottingKios(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $start  =$request->start;
        $end    =$request->end;
        $ruangan = DB::table('ruangan_m as ru')
        ->join('slottingkiosk_m as slot', 'slot.objectruanganfk', '=', 'ru.id')
        ->select('ru.id as idruangan','slot.tanggal','slot.id', 'ru.namaruangan', 'ru.objectdepartemenfk', 'slot.jambuka', 'slot.jamtutup','slot.quota','slot.hari', 'slot.loket',
        DB::raw("extract(hour from slot.jamtutup) -extract(hour from slot.jambuka)as totaljam"))
        ->where('ru.statusenabled', true)
        ->where('slot.kdprofile', $kdProfile)
        ->where('slot.statusenabled', true)
        ->when($start && $end ,function($query) use ($start,$end){
            return $query->whereBetween('slot.tanggal',[$start,$end]);
        })
        ->when($request->objectrunaganfk, function ($query) use ($request) {
            return $query->where('slot.objectruanganfk', $request->objectrunaganfk);
        });
        if(isset($request['namaRuangan']) && $request['namaRuangan']!='undefined' && $request['namaRuangan']!=''){
            $ruangan =$ruangan->where('ru.namaruangan','ilike','%'.$request['namaRuangan'].'%');
        }
        if(isset($request['quota']) && $request['quota']!='undefined' && $request['quota']!=''){
            $ruangan =$ruangan->where('slot.quota','=',$request['quota']);
        }
        $ruangan=$ruangan->orderBy('slot.tanggal');
        $ruangan=$ruangan->whereNotNull('slot.tanggal');
        $ruangan=$ruangan->get();


        $dataperloket = collect($ruangan)->groupBy('loket')->map(function ($items) {
            return collect($items)->groupBy('tanggal');
        })->toArray();
        $result = array(
            'data' => $ruangan,
            'dataperloket' => $dataperloket,
            'as' => 'er@epic',
        );
        return $this->respond($result);
    }
    public function saveSlottingKios(Request $request){
        $kdProfile = (int) $this->kdProfile;
        DB::beginTransaction();
        try {
            $startDate = Carbon::parse($request->input('start'));
            $endDate = Carbon::parse($request->input('end'));
            $kuotaSebelum = '';
            foreach ($this->getDateRange($startDate, $endDate) as $date) {
                if($request['id'] == ''){
                    $cek = SlottingKiosk::where('tanggal', $date)
                    ->where('loket', $request['loketid'])
                    ->where('objectruanganfk', $request['objectruanganfk'])
                    ->first();
                    if(empty($cek)){
                        $newptp = new SlottingKiosk();
                        $newptp->id = SlottingKiosk::max('id')+1;
                        $newptp->statusenabled = true;
                        $newptp->kdprofile = $kdProfile;
                    }else{
                        $newptp = $cek;
                    }
                    $kuotaSebelum = 0;
                }else{
                    $newptp = SlottingKiosk::where('id',$request['id'])->first();
                    $kuotaSebelumnya = SlottingKiosk::where('id',$request['id'])->first();
                    $kuotaSebelum = $kuotaSebelumnya->quotafix;
                }

                $newptp->objectruanganfk = $request['objectruanganfk'];
                $newptp->jambuka = $request['jambuka'];
                $newptp->jamtutup =  $request['jamtutup'];
                $newptp->quota =  $request['quota'];
                $newptp->quotafix =  $request['quota'];
                $newptp->hari =  $this->hari_ini($date) .',';
                $newptp->tanggal =$date;
                $newptp->loket = $request['loketid'];
                $newptp->save();
                // return $kuotaSebelumnya->quotafix;
                $log =[
                    'tanggal' =>$date,
                    'namaRuangan' =>DB::table('ruangan_m')->where('id',$newptp->objectruanganfk)->first()->namaruangan,
                    'konvensionalNew' => $newptp->quota,
                    'old' => $kuotaSebelum
                ];
                $this->LOGGING(
                    'Simpan Slotting Kios',
                    $newptp->norec,
                    'slottingkiosk_m',
                    json_encode($log)
                );
            }

            $transMessage = "Sukses";
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $error = $e->getMessage();
            $transMessage = "Simpan Slotting Gagal";
        }

        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "data" =>$newptp,
                "status" => 200,
                "message" => $transMessage,
            );
        } else {
            DB::rollBack();
            $result = array(
                "data" => $error,
                "status" => 400,
                "message" => $transMessage,
            );
        }

        return $this->respond($result['data'], $result['status'], $transMessage);
    }
    private function getDateRange(Carbon $start, Carbon $end)
    {
        $dates = [];

        while ($start->lte($end)) {
            $dates[] = $start->copy();
            $start->addDay();
        }

        return $dates;
    }
    public function deleteSlotting(Request $request){
        try {
            if(isset($request['loketid'])) {
                SlottingKiosk::where('loket',$request['loketid'])->delete();
            } else {
                SlottingKiosk::where('id',$request['id'])->delete();
            }
            $transMessage = "Sukses ";
            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus slotting Gagal";
        }
        if ($transStatus != 'false') {
            DB::commit();
            $result = array(
                "data" => [],
                "status" => 200,
                "message" => $transMessage,
            );
        } else {
            DB::rollBack();
            $result = array(
                "data" => [],
                "status" => 400,
                "message" => $transMessage,
            );
        }
        return $this->respond($result['data'], $result['status'], $transMessage);
    }
    public function getSlottingKosong(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $tgl = date('Y-m-d');
        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->select('apr.norec','apr.tanggalreservasi')
            ->whereRaw(" to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tgl'")
            ->where('apr.objectruanganfk', $request['ruanganfk'])
            // ->where('apr.noreservasi','=','-')
            // ->where('apr.statuspanggil','=',0)
            // ->where('apr.statusenabled',true)
            ->whereNotNull('apr.jenis')
            ->where('apr.kdprofile',$kdProfile)
            ->count();

            $dataSelfReg = 0;
        //  $dataSelfReg = DB::table('pasiendaftar_t')
        //     ->select('norec','tglregistrasi','noregistrasi')
        //     ->whereRaw(" to_char(tglregistrasi,'yyyy-MM-dd') = '$tgl'")
        //     ->where('objectruanganlastfk', $request['ruanganfk'])
        //     ->where('statusenabled',true)
        //     ->where('kdprofile',$kdProfile)
        //     // ->where('statusschedule','Kios-K')
        //     ->count();
        $dataSlot = SlottingKiosk::where('objectruanganfk', $request['ruanganfk'])
            ->where('statusenabled',true)
            ->get();
        $data10 = null;
        $hari = $this->hari_ini(date('Y-m-d'));
        for ($i = count($dataSlot) - 1; $i >= 0; $i--) {
            // $now = explode(', ',$dataSlot[$i]->hari);
            // for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                // if(strtoupper($now[$i2]) == strtoupper($hari)){
                    if(date('Y-m-d') ==$dataSlot[$i]->tanggal ){
                    // dd(strtoupper($now[$i2]));
                    $data10['hari'] = strtoupper($hari);
                    $data10['quota'] = $dataSlot[$i]->quota;
                    $data10['jamtutup'] = $dataSlot[$i]->jamtutup;

                    // array_splice($data,$i,1);
                // }
            }
        }
        $jamNow= date('H:i');
        $tutup =date('H:i',strtotime(  $data10['jamtutup']));
        $s['status'] = true;
        $s['countselfregis'] = $dataSelfReg;
        $s['slotting'] = $data10;
        $s['sisaSlot'] = $data10['quota'] - ($dataSelfReg+$data);
        $s['countkiosk'] = $data;
        // if( $jamNow > $tutup) {
        //     $s['status'] = 'Poli Sudah Tutup';
        //     return $this->respond($s);
        // }
        // return $data+  $dataSelfReg;

        if(count($dataSlot) == 0){
            $s['status'] = 'Kuota Ruangan belum di setting';
            return $this->respond($s);
        }

        if(($data +  $dataSelfReg) < (float)  $data10['quota']){
            $s['status'] = true;
        }else{
            $s['status'] = 'Kuota Sudah Habis, Mohon Hubungi Administrator IT !';
        }
        return $this->respond($s);


    }
    public function getListLoket(Request $request){
//        $kdProfile = (int) $this->kdProfile;
//        $loket = array(
//            'Loket '
//        );
//
//        return $this->respond($s);
    }
    public function getDokterInternal(Request $request)
    {
         $kdProfile = (int) $this->kdProfile;
        $dat = DB::table('pegawai_m')
        ->select('id','namalengkap')
        ->where('statusenabled',true)
        ->where('kdprofile',$kdProfile)
        ->where('kddokterbpjs',$request['kode'])
        ->first();
        if(!empty( $dat)){
           return $this->respond($dat);
       }else{
          return $this->respond(false);
       }
    }
    public function getComboKios2(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $deptJalan = explode (',',$this->settingFix('kdDepartemenRawatJalanFix'));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item){
            $kdDepartemenRawatJalan []=  (int)$item;
        }

        $dataRuanganJalan = DB::table('ruangan_m as ru')
            ->select('ru.id','ru.namaruangan','ru.objectdepartemenfk')
            ->where('ru.statusenabled', true)
            ->where('ru.kdprofile', $kdProfile)
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan);
         if(isset($request['eksek']) && $request['eksek'] !='' && $request['eksek']!='undefined' ){
            if($request['eksek']=='false'){
             $dataRuanganJalan =$dataRuanganJalan->whereRaw("(ru.iseksekutif is null or ru.iseksekutif =false)")   ;
            }else if($request['eksek']=='true'){
                 $dataRuanganJalan =$dataRuanganJalan->whereRaw(" ru.iseksekutif =true");
            }
         }

         $dataRuanganJalan=$dataRuanganJalan->orderBy('ru.namaruangan');
         $dataRuanganJalan=$dataRuanganJalan->get();

        $kdJenisPegawaiDokter = $this->settingFix('kdJenisPegawaiDokter',   $kdProfile );

        $dkoter = DB::table('pegawai_m')
            ->select('*')
            ->where('statusenabled', true)
              ->where('kdprofile', $kdProfile)
            ->where('objectjenispegawaifk',$kdJenisPegawaiDokter)
            ->orderBy('namalengkap')
            ->get();


        $dataRuanganSlot = DB::table('ruangan_m as ru')
        ->select('ru.id','ru.namaruangan','ru.objectdepartemenfk')
        ->where('ru.statusenabled', true)
        ->where('ru.kdprofile', $kdProfile)
        ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan)
        // ->whereNotExists(function ($query) {
        //     $query->select(DB::raw(1))
        //         ->from('slottingkiosk_m as sk')
        //         ->whereRaw('sk.objectruanganfk = ru.id');
        // })
        ->get();

        $result = array(
            'ruanganrajal' => $dataRuanganJalan,
            'ruanganrajalnew' => $dataRuanganSlot,
            'dokter' => $dkoter,
            'message' => 'ramdan@epic',
        );

        return $this->respond($result);
    }
    public function getJadwalDokter (Request $request){
        $kdProfile = (int) $this->kdProfile;
        $idProfile = (int) $kdProfile;

        $data = DB::table('jadwaldokter_m as jd')
            ->join('ruangan_m AS ru','ru.id','=','jd.objectruanganfk')
            ->join('pegawai_m as pg','pg.id','=','jd.objectpegawaifk')
            // ->leftJoin('hari_m AS hr','hr.id','=','jd.objecthariawal')
            // ->leftJoin('hari_m AS hr1','hr1.id','=','jd.objecthariakhir')
            ->select(DB::raw("jd.id as idjadwalpegawai,jd.objectruanganfk,ru.namaruangan,
                              jd.objectpegawaifk,pg.namalengkap,pg.nosip,pg.nostr,pg.noidentitas as nik,
                              jd.jammulai,jd.jamakhir,jd.keterangan, jd.hari"))
            ->where('jd.kdprofile', $idProfile)
            ->where('jd.statusenabled', true);

        if (isset($request['dokterId']) && $request['dokterId'] != "" && $request['dokterId'] != "undefined") {
            $data = $data->where('pg.id', '=', $request['dokterId']);
        }
        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganId']);
        }
         if (isset($request['nik']) && $request['nik'] != "" && $request['nik'] != "undefined") {
            $data = $data->where('pg.noidentitas', '=', $request['nik']);
        }
        if (isset($request['nostr']) && $request['nostr'] != "" && $request['nostr'] != "undefined") {
            $data = $data->where('pg.nostr', '=', $request['nostr']);
        }

        $data = $data->orderBy('pg.namalengkap', 'asc');
        $data = $data->get();
        $data10 = [];
        $hari = $this->hari_ini(date('Y-m-d'));
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $now = explode(', ',$data[$i]->hari);
            for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                if(strtoupper($now[$i2]) == strtoupper($hari)){
                    // dd(strtoupper($now[$i2]));
                      $data10 [] = $data[$i];
                   // array_splice($data,$i,1);
                }
            }
        }
        $result = array(
            'data'=> $data10,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getPasienByNoka(Request $r) {
        $kdProfile = $this->getDataKdProfile($r);
        $data = DB::table('pasien_m as ps')

            ->select('ps.nocm','ps.id as nocmfk','ps.namapasien','ps.objectjeniskelaminfk',
                'ps.nobpjs',
                DB::raw(" to_char ( ps.tgllahir,'yyyy-MM-dd') as tgllahir"))
            ->where('ps.statusenabled',true)
            ->where('ps.kdprofile',$kdProfile)
            ->where('ps.nobpjs','=',$r['nokartu']);
        $data = $data->get();

        $result = array(
            'data'=> $data,
            'message' => 'er@epic',
        );
        return $this->respond($result);
    }
    public function getQuisonerMaster(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $idProfile = (int) $kdProfile;
        if($request['emrid'] ==''){
            $result = array(
                'kolom1' => [],
                'kolom2' => [],
                'kolom3' => [],
                'kolom4' => [],
                'title' => '-',
                'classgrid' => [],
                'namaemr' => [],
                'message' => 'as@epic',
            );

            return $this->respond($result);
        }
        $dataRaw = DB::table('quisonerd_m as emrd')
            ->where('emrd.emrfk', $request['emrid'])
            ->where('emrd.statusenabled', '=', true)
            ->where('emrd.kdprofile',$idProfile)
            ->select('emrd.*')
            ->orderBy('emrd.nourut');
        $dataRaw = $dataRaw->get();
        $dataTitle = DB::table('quisoner_m as emr')
            ->where('emr.id', $request['emrid'])
            ->where('emr.kdprofile',$idProfile)
            ->select('emr.caption as captionemr', 'emr.classgrid','emr.namaemr')
            ->get();
        $title = $dataTitle[0]->captionemr;
        $classgrid = $dataTitle[0]->classgrid;
        $namaemr = $dataTitle[0]->namaemr;

        $dataraw3A = [];
        $dataraw3B = [];
        $dataraw3C = [];
        $dataraw3D = [];
        foreach ($dataRaw as $dataRaw2) {
            $head = '';
            $captioRadio = [];
            if( $dataRaw2->type =='radio' ){
                $cap =  explode(',', $dataRaw2->caption);
                foreach ($cap as $key => $values) {
                    $captioRadio[] = $values;
                }
            }

            if ($dataRaw2->kolom == 1) {
                $dataraw3A[] = array(
                    'kdprofile' => $dataRaw2->kdprofile,
                    'statusenabled' => $dataRaw2->statusenabled,
                    'kodeexternal' => $dataRaw2->kodeexternal,
                    'namaexternal' => $dataRaw2->namaexternal,
                    'reportdisplay' => $dataRaw2->reportdisplay,
                    'emrfk' => $dataRaw2->emrfk,
                    'caption' => $head . $dataRaw2->caption,
                    'type' => $dataRaw2->type,
                    'nourut' => $dataRaw2->nourut,
                    'satuan' => $dataRaw2->satuan,
                    'id' => $dataRaw2->id,
                    'headfk' => $dataRaw2->headfk,
                    'namaemr' => $namaemr,
                    'style' => $dataRaw2->style,
                    'cbotable' => $dataRaw2->cbotable,
                    'child' => [],
                    'captionradio' =>$captioRadio,
                );
            } elseif ($dataRaw2->kolom == 2) {
                $dataraw3B[] = array(
                    'kdprofile' => $dataRaw2->kdprofile,
                    'statusenabled' => $dataRaw2->statusenabled,
                    'kodeexternal' => $dataRaw2->kodeexternal,
                    'namaexternal' => $dataRaw2->namaexternal,
                    'reportdisplay' => $dataRaw2->reportdisplay,
                    'emrfk' => $dataRaw2->emrfk,
                    'caption' => $head . $dataRaw2->caption,
                    'type' => $dataRaw2->type,
                    'nourut' => $dataRaw2->nourut,
                    'satuan' => $dataRaw2->satuan,
                    'id' => $dataRaw2->id,
                    'headfk' => $dataRaw2->headfk,
                    'namaemr' => $namaemr,
                    'style' => $dataRaw2->style,
                    'cbotable' => $dataRaw2->cbotable,
                    'child' => [],
                    'captionradio' =>$captioRadio,
                );
            } elseif ($dataRaw2->kolom == 3) {
                $dataraw3C[] = array(
                    'kdprofile' => $dataRaw2->kdprofile,
                    'statusenabled' => $dataRaw2->statusenabled,
                    'kodeexternal' => $dataRaw2->kodeexternal,
                    'namaexternal' => $dataRaw2->namaexternal,
                    'reportdisplay' => $dataRaw2->reportdisplay,
                    'emrfk' => $dataRaw2->emrfk,
                    'caption' => $head . $dataRaw2->caption,
                    'type' => $dataRaw2->type,
                    'nourut' => $dataRaw2->nourut,
                    'satuan' => $dataRaw2->satuan,
                    'id' => $dataRaw2->id,
                    'headfk' => $dataRaw2->headfk,
                    'namaemr' => $namaemr,
                    'style' => $dataRaw2->style,
                    'cbotable' => $dataRaw2->cbotable,
                    'child' => [],
                    'captionradio' =>$captioRadio,
                );
            } else {
                $dataraw3D[] = array(
                    'kdprofile' => $dataRaw2->kdprofile,
                    'statusenabled' => $dataRaw2->statusenabled,
                    'kodeexternal' => $dataRaw2->kodeexternal,
                    'namaexternal' => $dataRaw2->namaexternal,
                    'reportdisplay' => $dataRaw2->reportdisplay,
                    'emrfk' => $dataRaw2->emrfk,
                    'caption' => $head . $dataRaw2->caption,
                    'type' => $dataRaw2->type,
                    'nourut' => $dataRaw2->nourut,
                    'satuan' => $dataRaw2->satuan,
                    'id' => $dataRaw2->id,
                    'headfk' => $dataRaw2->headfk,
                    'namaemr' => $namaemr,
                    'style' => $dataRaw2->style,
                    'cbotable' => $dataRaw2->cbotable,
                    'child' => [],
                    'captionradio' =>$captioRadio,
                );
            }

//            $title = $dataRaw2->captionemr;
//            $classgrid = $dataRaw2->classgrid;
        }
//        $dataA = $dataraw3A;

        function recursiveElements($data)
        {
            $elements = [];
            $tree = [];
            foreach ($data as &$element) {
//                $element['child'] = [];
                $id = $element['id'];
                $parent_id = $element['headfk'];

                $elements[$id] = &$element;
                if (isset($elements[$parent_id])) {
                    $elements[$parent_id]['child'][] = &$element;
                } else {
                    if ($parent_id <= 10) {
                        $tree[] = &$element;
                    }
                }
                //}
            }
            return $tree;
        }


        $dataA = [];
        $dataB = [];
        $dataC = [];
        $dataD = [];
        if (count($dataraw3A) > 0) {
            $dataA = recursiveElements($dataraw3A);
        }
        if (count($dataraw3B) > 0) {
            $dataB = recursiveElements($dataraw3B);
        }
        if (count($dataraw3C) > 0) {
            $dataC = recursiveElements($dataraw3C);
        }
        if (count($dataraw3D) > 0) {
            $dataD = recursiveElements($dataraw3D);
        }


        $result = array(
            'kolom1' => $dataA,
            'kolom2' => $dataB,
            'kolom3' => $dataC,
            'kolom4' => $dataD,
            'title' => $title,
            'classgrid' => $classgrid,
            'namaemr' =>$namaemr,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }
    public function getQuisonerTransaksiDetail(Request $request)
    {
        //todo : detail
        $kdProfile = (int) $this->kdProfile;

        $data = DB::table('quisonerd_t as emrdp')
            ->leftjoin('quisonerd_m as emrd', 'emrd.id', '=', 'emrdp.emrdfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'emrdp.pegawaifk')
            ->select('emrdp.*', 'emrd.caption', 'emrd.type', 'emrd.nourut', 'emrdp.emrfk', 'emrd.reportdisplay', 'emrd.kodeexternal as kodeex', 'emrd.satuan', 'pg.namalengkap')
            ->where('emrdp.statusenabled', true)
            ->where('emrdp.kdprofile',$kdProfile )
            ->whereNotNull('emrdp.value')
            ->where('emrdp.value','!=','Invalid date')
            ->orderBy('emrd.nourut');
        if (isset($request['noemr']) && $request['noemr'] != '') {
            $data = $data->where('emrdp.emrpasienfk', $request['noemr']);
        }
        if (isset($request['emrfk']) && $request['emrfk'] != '') {
            $data = $data->where('emrdp.emrfk', $request['emrfk']);
        }

        if (isset($request['objectid']) && $request['objectid'] != '') {
            $data = $data->where('emrdp.emrdfk', $request['objectid']);
        }
        if (isset($request['nik']) && $request['nik'] != '') {
            // $data = $data->where('ps.noidentitas', $request['nik']);
        }
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }
     public function getDataRuangan(Request $request)
    {
         $kdProfile = (int) $this->kdProfile;
         if($request['jenis'] == 'rj'){
            $deptJalan = explode (',',$this->settingFix('kdDepartemenRawatJalanFix'));
            $arr = [];
            foreach ($deptJalan as $item){
                $arr []=  (int)$item;
            }
         }else{
            $deptRanap = explode (',',$this->settingFix('kdDepartemenRanapFix'));
            $arr = [];
            foreach ($deptRanap as $itemRanap){
                $arr []=  (int)$itemRanap;
            }
         }

        $dataRuangan = DB::table('ruangan_m as ru')
            ->select('ru.id','ru.namaruangan')
            ->whereIn('ru.objectdepartemenfk',$arr)
            ->where('ru.statusenabled', true)
            ->where('ru.kdprofile',$kdProfile)
            ->orderBy('ru.namaruangan')
            ->get();

        $result = array(
            'ruangan' => $dataRuangan,
        );

        return $this->respond($result);
    }

    public function getComboRegBaru(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;;
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->join('pegawai_m as pg', 'pg.id', '=', 'lu.objectpegawaifk')
            ->select('lu.objectpegawaifk', 'pg.namalengkap')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->where('lu.kdprofile', $kdProfile)
            ->first();

        $jk = JenisKelamin::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(jeniskelamin) as jeniskelamin"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $agama = Agama::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(agama) as agama"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $statusPerkawinan = StatusPerkawinan::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(statusperkawinan) as statusperkawinan,namaexternal as namadukcapil"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $pendidikan = Pendidikan::where('statusenabled', true)
            ->select(DB::raw("id, UPPER(pendidikan) as pendidikan,namaexternal as namadukcapil"))
            ->where('kdprofile', $kdProfile)
            ->get();

        $pekerjaan = DB::table('pekerjaan_m')
            ->select(DB::raw("id, UPPER(pekerjaan) as pekerjaan,namaexternal as namadukcapil"))
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->get();

        $gd = DB::table('golongandarah_m')
            ->select(DB::raw("id, UPPER(golongandarah) as golongandarah,namaexternal as namadukcapil"))
            ->where('statusenabled', true)
            ->get();
        $suku = DB::table('suku_m')
            ->select(DB::raw("id, UPPER(suku) as suku"))
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->get();
        // $jenisidentitas = DB::table('rm_jenisidentitas_m')
        //     ->select('id', 'name')
        //     ->where('statusenabled', true)
        //     ->where('kdprofile', $kdProfile)
        //     ->get();
        $result = array(
            'jeniskelamin' => $jk,
            'agama' => $agama,
            'statusperkawinan' => $statusPerkawinan,
            'pendidikan' => $pendidikan,
            'pekerjaan' => $pekerjaan,
            'pegawaiLogin' => $dataPegawai->namalengkap,
            'golongandarah' => $gd,
            'suku' => $suku,
            // 'jenisidentitas' => $jenisidentitas,
            'message' => 'inhuman',
        );

        return $this->respond($result);
    }

    public function getPasienByNoCmTglLahir($nocm,$tgllahir) {
		$data = DB::table('pasien_m as ps')
			->leftJOIN ('alamat_m as alm','alm.nocmfk','=','ps.id')
			->leftjoin ('pendidikan_m as pdd','ps.objectpendidikanfk','=','pdd.id')
			->leftjoin ('pekerjaan_m as pk','ps.objectpekerjaanfk','=','pk.id')
			->leftjoin ('jeniskelamin_m as jk','jk.id','=','ps.objectjeniskelaminfk')
			->select('ps.nocm','ps.id as nocmfk','ps.namapasien','ps.objectjeniskelaminfk','jk.jeniskelamin',
				'alm.alamatlengkap','pdd.pendidikan','pk.pekerjaan','ps.noidentitas','ps.notelepon','ps.tempatlahir',
                'ps.nobpjs',
				DB::raw(" to_char ( ps.tgllahir,'yyyy-MM-dd') as tgllahir"))
            ->where('ps.statusenabled',true);

        if(isset($nocm) &&$nocm != "" && $nocm != "undefined" && $nocm != "null") {
            $data = $data->where('ps.nocm','=',$nocm)
             ->Orwhere('ps.noidentitas','=',$nocm);
        }
		$data = $data->get();

		$result = array(
			'data'=> $data,
			'message' => 'ramdanegie',
		);
		return $this->respond($result);
	}

    public function getPenjaminByKelompokPasien(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('mapkelompokpasientopenjamin_m as mkp')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'mkp.objectkelompokpasienfk')
            ->join('rekanan_m as rk', 'rk.id', '=', 'mkp.kdpenjaminpasien')
            ->select('rk.id', 'rk.namarekanan', 'kp.id as id_kelompokpasien', 'kp.kelompokpasien')
            //            ->where('mkp.objectkelompokpasienfk', $request['kdKelompokPasien'])
            ->where('mkp.statusenabled', true)
            ->where('mkp.kdprofile', (int)$kdProfile);
        //            ->get();

        if (isset($request['kdKelompokPasien']) && $request['kdKelompokPasien'] != "" && $request['kdKelompokPasien'] != "undefined") {
            $data = $data->where('mkp.objectkelompokpasienfk', '=', $request['kdKelompokPasien']);
        };
        $data = $data->get();

        $result = array(
            'rekanan' => $data,
            'message' => 'ramdanegie',
        );
        return $this->respond($result);
    }

    public function getRuanganBPJSInternal(Request $request)
    {

        $data = DB::table('ruangan_m')
            ->select('*')
            ->where('statusenabled', true)
            ->whereNotNull('kdinternal')
            ->orderBy('namaruangan');

        $data = $data->get();

        return $this->respond($data);
    }

    public function getJumlahLoket(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $jumlahLoketKiosk = $this->settingFix('jumlahLoketKiosk');

        $data['loket'] = $jumlahLoketKiosk;
        return $this->respond($data);
    }
    public function getDataPasien(Request $request, $identitas) {
        $kdProfile = $this->kdProfile;
        $data = DB::table('pasien_m as ps')
        ->select('ps.id', 'ps.nocm', 'ps.namapasien', 'ps.noidentitas', 'ps.nobpjs', 'ps.tgllahir', 'jk.id as id_jeniskelamin', 'jk.jeniskelamin', 'al.id as id_alamat', 'al.alamatlengkap',
        DB::raw("case when ps.notelepon = null then '12345678' when ps.notelepon='' then '12345678' else ps.notelepon end as notelepon"))
        ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
        ->join('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
        ->where('ps.statusenabled', true)
        ->where('ps.kdprofile', $kdProfile)
        ->where(function($joinna) use ($identitas) {
            $joinna->where('ps.nocm', $identitas)
            ->orWhere('ps.nobpjs', $identitas)
            ->orWhere('ps.noidentitas', $identitas);
        })->first();
        $kunjterakhir = ['namaruangan' => '-','tglregistrasi' => '-'];
        $flagresevpoli = false;
        $pendaftaran = null;
        if(!empty($data)) {
            $dateFrom = date('Y-m-d 00:00');
            $dateTo = date('Y-m-d 23:59');
            $deptJalan = explode(',', $this->settingFix('kdDepartemenKiosk', $kdProfile));
            $kdDepartemenRawatJalan = [];
            foreach ($deptJalan as $item) {
                $kdDepartemenRawatJalan[] =  (int)$item;
            }
            $pendaftaran = DB::table('pasiendaftar_t as pd')
            ->select('pd.*', 'pa.norujukan', 'pa.nosep', 'apd.norec as norec_apd','rm.namaruangan')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->join('antrianpasiendiperiksa_t as apd',function ($join) {
                $join->on('apd.noregistrasifk', '=', 'pd.norec');
                $join->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk');
            })
            ->join('ruangan_m as rm', 'apd.objectruanganfk', '=', 'rm.id')
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $kdProfile)
            ->whereBetween('pd.tglregistrasi', [$dateFrom, $dateTo])
            ->whereIn('rm.objectdepartemenfk', $kdDepartemenRawatJalan)
            ->where('pd.nocmfk', $data->id)
            ->first();

            if(!empty($pendaftaran)) {
                if($pendaftaran->statusschedule !== '' && $pendaftaran->ischeckin === false) {
                    $flagresevpoli = true;
                }
            }

            $kunjterakhir = DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd','pd.nocmfk','=','ps.id')
            ->join('ruangan_m as ru','ru.id','=','pd.objectruanganlastfk')
            ->join('kelompokpasien_m as kp','kp.id','=','pd.objectkelompokpasienlastfk')
            ->select(DB::raw("pd.norec,pd.tglregistrasi,ps.nocm,pd.noregistrasi,ps.namapasien,pd.objectruanganlastfk,kp.kelompokpasien,ru.namaruangan,
                              pd.tglpulang,ru.objectdepartemenfk"))
            ->where('pd.statusenabled',true)
            ->where('ps.kdprofile', (int)$kdProfile)
            ->where('ps.statusenabled',true)
            ->where('ps.id', '=',  $data->id)
            ->orderBy('pd.tglregistrasi','DESC')
            ->first();
            if ($kunjterakhir == null){
                $kunjterakhir = ['namaruangan' => '-','tglregistrasi' => '-'];
            }else{
                $kunjterakhir = $kunjterakhir;
            }
        }

        $result = array(
            'data'=> $data,
            'pendaftaran'=> $pendaftaran,
            'flagresevpoli'=> $flagresevpoli,
            'kunjterakhir' => $kunjterakhir,
            'message' => 'hs@epic',
        );
        return $this->respond($result);
    }

}
