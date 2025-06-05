<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\AntrianApotik;
use App\Models\Transaksi\KartuStok;
use App\Models\Transaksi\PelayananPasien;
use App\Models\Transaksi\PelayananPasienDetail;
use App\Models\Transaksi\PelayananPasienPetugas;
use App\Models\Transaksi\StokProdukDetail;
use App\Models\Transaksi\StrukOrder;
use App\Models\Transaksi\StrukResep;
use App\Traits\Valet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Bridging\BridgingBPJSCtrl;
use App\Models\Master\ListNotif;
use App\Models\Master\Pasien;
use App\Models\Master\Printer;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\SuratKeterangan;
use Illuminate\Support\Facades\Http;

class GeneralCtrl extends Controller
{
    use Valet;

    protected $bridgingBPJSCtrl;
    public function __construct(BridgingBPJSCtrl $bridgingBPJSCtrl)
    {
        parent::__construct($is_encrypt = true);
        $this->bridgingBPJSCtrl = $bridgingBPJSCtrl;
    }
    public function pasienRegistrasiSearching(Request $r)
    {
        $dari = date('Y-m-d 00:00:00');
        $sampai = date('Y-m-d 23:59:59');
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'ru.namaruangan',
                'pd.norec',
                'pd.tglregistrasi',
                'ps.id'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('ps.statusenabled', true)
            ->whereRaw("(
                pd.tglregistrasi between '$dari' and '$sampai'
                or pd.tglpulang is null
            )");
        if (isset($r['query']) && $r['query'] != '') {
            $data = $data->whereRaw("(
                ps.namapasien ilike '%$r[query]%'
                or ps.nocm =  '$r[query]'
            )
            ");
        }
        $data = $data->get();
        return $this->respond($data);
    }
    public function listDokterPaging(Request $r)
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
    public function headerPasien(Request $r)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('catatandokter_t as ct', 'ct.nocmfk', '=', 'ps.id')
            ->leftJoin('catatandokter_t as latest_ct', function ($join) {
                $join->on('ct.nocmfk', '=', 'latest_ct.nocmfk')
                    ->on('ct.tanggal', '<', 'latest_ct.tanggal');
            })
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.tgllahir',
                'ct.catatan',
                'pd.isclosing',
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
                'ps.email'
            )
            ->where('ps.kdprofile', (int)$this->kdProfile)
            ->where('ps.statusenabled', true);
        if (isset($r['nocmfk']) && $r['nocmfk'] != '') {
            $data = $data->where('ps.id', $r['nocmfk']);
        } else {
            if (isset($r['norec_pd'])) {
                $data = $data->where('pd.norec', $r['norec_pd']);
            }
        }
        $data = $data->first();
        if (!empty($data)) {
            $data->umur =  $this->getAge($data->tgllahir, date('Y-m-d H:i:s'));
        }
        $registrasi = [];
        if (isset($r['nocmfk']) && $r['nocmfk'] != '' && isset($r['norec_pd']) && $r['norec_pd'] != '') {
            $registrasi =   DB::table('pasiendaftar_t as pd')
                ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
                ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
                ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
                ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
                ->LEFTJOIN('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
                ->LEFTJOIN('asuransipasien_m as asu', 'asu.id', '=', 'pa.objectasuransipasienfk')
                ->LEFTJOIN('kelas_m as kls', 'kls.id', '=', 'asu.objectkelasdijaminfk')
                ->LEFTJOIN('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
                ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
                ->select(
                    'pd.noregistrasi',
                    'pd.norec as norec_pd',
                    'pd.tglregistrasi',
                    'pd.tglpulang',
                    'pd.isclosing',
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
                    'apd.tglkeluar',
                    'pd.objectkelompokpasienlastfk',
                    'pd.objectrekananfk',
                    'pd.jenispelayanan as jenispelayananfk',
                    'pd.statusbayar',
                    'rk.namarekanan',
                    'pg.namalengkap as dokter',
                    'pd.objectruanganlastfk',
                    'pd.inacbg_totalgrouper',
                    'kls.namakelas as kelasditanggung',
                    'asu.nmprovider',
                    'pa.nosep as nosep',
                    'kl.kodebpjs as kelas_rawat',
                    'kls.kodebpjs as kelas_dijamin',
                    'pa.klsrawathak_kode'
                )
                ->where('pd.kdprofile', (int)$this->kdProfile)
                ->where('pd.statusenabled', true)
                ->where('pd.nocmfk', $r['nocmfk'])
                ->where('pd.norec', $r['norec_pd'])
                ->get();
        }

        $last = array();
        $tgl = date('2000-01-01 00:00');
        foreach ($registrasi as $d) {
            if ($d->statusbayar == null) {
                $d->statusbayar  = 'Belum Verifikasi';
            }
            if ($d->objectruanganlastfk == $d->objectruanganfk && $tgl < $d->tglmasuk) {
                $tgl = $d->tglmasuk;
                $last  = $d;
            }
        }
        $result['pasien'] = $data;
        $result['registrasi'] = $registrasi;
        $result['last_registrasi'] = $last;
        $result['isFilterProdukLab']  = $this->settingFix('isFilterProdukLab');
        $result['nominal'] = $r['nominal'] ?  $this->terbilang($r['nominal']) : null;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function headerPasienFirst(Request $r)
    {

        $registrasi =   DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->JOIN('departemen_m as dp', 'dp.id', '=', 'ru.objectdepartemenfk')
            ->JOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('rekanan_m as rek', 'rek.id', '=', 'pd.objectrekananfk')
            ->JOIN('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('kotakabupaten_m as kab', 'kab.id', '=', 'alm.objectkotakabupatenfk')
            ->leftjoin('kecamatan_m as kec', 'kec.id', '=', 'alm.objectkecamatanfk')
            ->select(
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'dp.namadepartemen',
                'kp.kelompokpasien',
                'pd.objectruanganlastfk',
                'ru.namaruangan',
                'pd.objectkelasfk',
                'kl.namakelas',
                'pd.isclosing',
                'pd.nocmfk',
                'kab.namakotakabupaten',
                'kec.namakecamatan',
                'ps.nohp',
                'pd.objectkelompokpasienlastfk',
                'pd.objectrekananfk',
                'pd.jenispelayanan as jenispelayananfk',
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
                'rek.namarekanan'
            )
            ->where('pd.kdprofile', (int)$this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pd.norec', $r['norec_pd'])
            ->first();
        if (!empty($registrasi)) {
            $registrasi->umur =  $this->getAge($registrasi->tgllahir, date('Y-m-d H:i:s'));
        }
        $result['registrasi'] = $registrasi;
        $result['as'] = '@epic';

        return $this->respond($result);
    }
    public function showFileGeneral(Request $r)
    {

        return response()->fShow($r['path'], 'ftp');
    }
    public function saveLoggingAll(Request $r)
    {
        try {
            $this->LOGGING(
                $r['jenislog'],
                $r['noreff'],
                $r['referensi'],
                $r['keterangan']
            );

            $transMessage = "Sukses";
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
    public function settingPPKBPJS(Request $r)
    {
        return $this->respond($this->bridgingBPJSCtrl->getSetting());
    }
    public function getTemplateExpertice(Request $request)
    {

        $data = DB::table('templateexpertiseecho_m')
            ->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->get();

        $result = array(
            'data' => $data,
            'as' => '@epic'
        );

        return $this->respond($result);
    }
    public function apiTOOLS(Request $request)
    {
        $dataJsonSend = null;
        $url = $request['url'];//str_replace('http://app.rsjpparamarta.com', 'http://10.20.30.40', $request['url']);
        if ($request['data'] != null) {
            $dataJsonSend = $request['data'];
        }
        if (empty($dataJsonSend)) {
            $response = Http::withHeaders($request['headers'])
                ->{$request['method']}($url);
        } else {
            $response = Http::withHeaders($request['headers'])
                ->{$request['method']}($url, $dataJsonSend);
        }

        $res = null;
        if ($response->ok()) {
            $res = $response->json();
        }
        return $this->respond($res);
    }

    public function SaveSuratKeteranganKematian(Request $request)
    {
        DB::beginTransaction();
        $kdProfile = (int) $this->getDataKdProfile($request);
        $kdJenisSurat = (int) $this->settingDataFixed('SuratKematian', $kdProfile);
        $tglAyeuna = date('Y-m-d H:i:s');
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->first();
        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('id', $kdJenisSurat)
            ->first();
        $kodeSurat = '';
        if (!empty($jenisSurat)) {
            $kodeSurat = $jenisSurat->namaexternal;
        }
        try {

            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4);
                $SKM = new SuratKeterangan();
                $norecNew = $SKM->generateNewId();
                $SKM->kdprofile = $kdProfile;
                $SKM->norec = $norecNew;
                $SKM->nosurat = $genSurat['nosurat'];
                $SKM->nosint = $genSurat['noint'];
                $SKM->statusenabled = true;
                $SKM->jenissuratfk = $kdJenisSurat;
            } else {
                $SKM =  SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $SKM->keterangan = $request['keterangan'];
            $SKM->tglawal = $request['tglmeninggal'];
            $SKM->tglakhir = $request['tglmeninggal'];
            $SKM->pegawaifk = $dataPegawai->objectpegawaifk;
            $SKM->tglsurat = $tglAyeuna;
            $SKM->pasiendaftarfk = $request['norec_pd'];
            $SKM->dokterfk = $request['dokterfk'];
            $SKM->save();
            $norecSKM = $SKM->norec;

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "";
        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage . "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result);
    }


    public function SaveSuratKeteranganMeninggal(Request $request)
    {
        DB::beginTransaction();
        $kdProfile = (int) $this->getDataKdProfile($request);
        $kdJenisSurat = (int) $this->settingDataFixed('SuratMeninggal', $kdProfile);
        $tglAyeuna = date('Y-m-d H:i:s');
        $dataLogin = $request->all();
        $dataPegawai = DB::table('loginuser_s as lu')
            ->select('lu.objectpegawaifk')
            ->where('lu.id', $dataLogin['userData']['id'])
            ->first();
        $jenisSurat = DB::table('jenissurat_m')
            ->select('*')
            ->where('id', $kdJenisSurat)
            ->first();
        $kodeSurat = '';
        if (!empty($jenisSurat)) {
            $kodeSurat = $jenisSurat->namaexternal;
        }
        try {

            if ($request['norec'] == '') {
                $genSurat = $this->genSurat(new SuratKeterangan(), 'nosint', $kodeSurat, 4);
                $SKM = new SuratKeterangan();
                $norecNew = $SKM->generateNewId();
                $SKM->kdprofile = $kdProfile;
                $SKM->norec = $norecNew;
                $SKM->nosurat = $genSurat['nosurat'];
                $SKM->nosint = $genSurat['noint'];
                $SKM->statusenabled = true;
                $SKM->jenissuratfk = $kdJenisSurat;
            } else {
                $SKM =  SuratKeterangan::where('norec', $request['norec'])->where('kdprofile', $kdProfile)->first();
            }
            $SKM->keterangan = $request['keterangan'];
            $SKM->tglawal = $request['tglmeninggal'];
            $SKM->tglakhir = $request['tglmeninggal'];
            $SKM->pegawaifk = $dataPegawai->objectpegawaifk;
            $SKM->tglsurat = $tglAyeuna;
            $SKM->pasiendaftarfk = $request['norec_pd'];
            $SKM->dokterfk = $request['dokterfk'];
            $SKM->save();
            $norecSKM = $SKM->norec;

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        $transMessage = "";
        if ($transStatus == 'true') {
            $transMessage = $transMessage . "Simpan Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        } else {
            $transMessage = $transMessage . "Simpan Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "data" => $norecSKM,
                "by" => 'ea@epic',
            );
        }
        return $this->setStatusCode($result['status'])->respond($result);
    }


    public function getDataKdProfile(Request $request)
    {
        $dataLogin = $request->all();
        $idUser = $dataLogin['userData']['id'];
        $data = LoginUser::where('id', $idUser)->first();
        if (!empty($data)) {
            $idKdProfile = (int)$data->kdprofile;
            $Query = DB::table('profile_m')
                ->where('id', '=', $idKdProfile)
                ->first();
            $Profile = $Query;
            return (int)$Profile->id;
        } else {
            $data = Pasien::where('id', $idUser)->first();
            if (!empty($data)) {
                $idKdProfile = (int)$data->kdprofile;
                $Query = DB::table('profile_m')
                    ->where('id', '=', $idKdProfile)
                    ->first();
                $Profile = $Query;
                return (int)$Profile->id;
            } else {
                return null;
            }
        }
    }
    public function settingFixData($setting)
    {
        $set = $this->settingFix($setting);
        return $this->respondV2($set);
    }
    public function masterPrinter(Request $r)
    {
        $data  = DB::table('printer_m')
        ->select(
            '*'

        )
        ->where('kdprofile', $this->kdProfile)
        ->where('statusenabled', true);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('id', '=',  $r['id']);
        }
        if (isset($r['namaexternal']) && $r['namaexternal'] != '') {
            $data = $data->where('namaexternal', '=',  $r['namaexternal'] );
        }
        if (isset($r['printerdefault']) && $r['printerdefault'] != '') {
            $data = $data->where('printerdefault', 'ilike', '%' . $r['printerdefault'] . '%');
        }
        if (isset($r['device']) && $r['device'] != '') {
            $data = $data->where('devicename', '=', $r['device'] );
        }

        $data = $data->orderByDesc('id','desc');
        $data = $data->get();
        // if(count($data)> 0 &&  isset($r['qz']) && $r['qz'] == 'true'){
        //     $arr = [];
        //     foreach($data as $d){
        //         if(gethostname() == $d->devicename){
        //             $arr[]  = $d;
        //             break;
        //         }
        //     }
        //     if(count($arr) > 0){
        //         $data = $arr;
        //     }
        // }

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function savePrinter(Request $r)
    {
        DB::beginTransaction();
        try {

            if ($r['id'] == '') {
                $id = $this->SEQUENCE_MASTER(new Printer(),'id',$this->kdProfile);
                $dataPS = new Printer();
                $dataPS->id = $id;
                $dataPS->kdprofile = (int)$this->kdProfile;
                $dataPS->statusenabled = true;
            } else {
                $dataPS = Printer::where('id', $r['id'])->first();
                $id =  $dataPS->id;
            }
            $dataPS->namaexternal =  $r['namaexternal'];
            $dataPS->printerdefault =  $r['printerdefault'];
            $dataPS->orientation =  $r['orientation'];
            $dataPS->keterangan =  $r['keterangan'] ? $r['keterangan'] : null;
            $dataPS->height =  $r['height'];
            $dataPS->width =  $r['width'];
            $dataPS->devicename =  isset($r['devicename']) ? $r['devicename'] : gethostname();
            $dataPS->save();


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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function deletePrinter(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = Printer::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
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
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getLogUser(Request $r){
        $kdProfile      = (int)$this->getDataKdProfile($r);
        $tglAwal = date('Y-m-d 00:00:00', strtotime($r->tglAwal));
        $tglAkhir = date('Y-m-d 23:59:59', strtotime($r->tglAkhir));        
        $nama           = $r->nama;
        $keterangan     = $r->keterangan;
        $rows           = $r->rows;
        $data =DB::table('logginguser_t as lo')
        ->select(
            'lo.norec',
            'lo.namauser as username',
            'lo.namapegawai as namalengkap',
            'lo.tanggal as tanggal',
            'lo.jenislog as jenis',
            'lo.keterangan as keterangan',
        )
        ->where('lo.kdprofile' ,$kdProfile)
        ->whereBetween('lo.tanggal', [$tglAwal, $tglAkhir]);
        if ($nama !== '' && $nama !== 'undefined') {
            $data->whereRaw('LOWER(lo.namapegawai) LIKE ?', ["%{$nama}%"]);
        }

        if ($keterangan !== '' && $keterangan !== 'undefined') {
            $data->whereRaw('LOWER(lo.keterangan) LIKE ?', ["%{$keterangan}%"]);
        }

        $logData = $data
            ->orderByDesc('lo.tanggal')
            ->limit($rows)
            ->get();

        return $this->respond([
            'data' => $logData,
            'message' => '@epic',
        ]);
    }
    public function storeNotif(Request $request){
        DB::beginTransaction();
        try {
            $cek = null;
            if( $request['method'] == 'save'){
                // $cek = ListNotif::where('norec_trans',$request['norec'])->first();
                // if(empty($cek)){
                    $gl = $request['tgl'];
                    $da =  new ListNotif();

                    $da->norec = $this->Uuid4();
                    $da->norec_trans  = $request['norec'];
                    $da->judul = $request['judul'];
                    $da->jenis = $request['jenis'];
                    $da->kelompokuser = $request['kelompokUser'];
                    $da->kelompokuserfk = $request['idKelompokUser'];
                    $da->ruangantujuanfk = $request['idRuanganTujuan'];
                    $da->ruangantujuan = $request['ruanganTujuan'];
                    $da->ruanganasalfk = $request['idRuanganAsal'];
                    $da->ruanganasal = $request['ruanganAsal'];
                    $da->pegawaifk = $request['idPegawai'];
                    $da->keterangan = $request['pesanNotifikasi'];
                    $da->tgl = $gl;
                    $da->tgl_string =  $request['tgl_string'];
                    $da->urlform =  isset($request['urlForm']) ? $request['urlForm'] : null;
                    $da->params = isset($request['params']) ? json_encode($request['params']) : null;
                    $da->dataarray = isset($request['dataArray']) ? json_encode($request['dataArray']) : null;
                    $da->statusenabled = true;
                    $da->group = $request['group'];
                    $da->namapegawai = $request['namapegawai'];

                    $da->save();
                    $cek = $da;
                // }

            }
            if( $request['method'] =='delete'){
                $cek = ListNotif::where('norec_trans',$request['norec'])->delete();

            }
            if( $request['method'] =='get'){
                $cek =  ListNotif::orderBy('tgl','desc')->limit(10)->get();

            }

            $transStatus = true;
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus) {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $cek,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' '.$e->getline()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function generateSeq(Request $request)
    {
        DB::beginTransaction();
        try {

            $idProfile = (int) $this->kdProfile;
           if(isset($request['new'])){
            $noResep = $this->SEQUENCE_NEXVAL(new PasienDaftar(), 'noregistrasi', 10, date('ymd'), $idProfile);
           }else{
            $noResep = $this->SEQUENCE(new PasienDaftar, 'noregistrasi', 10, date('ymd'), $idProfile);
           }



            $transStatus = 'true';
        } catch (\Exception $e) {
             $transStatus = 'false';
         }

        if ($transStatus == 'true') {
            $transMessage = "Simpan  Berhasil";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "seq"  => $noResep,

                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan  Gagal!!";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
