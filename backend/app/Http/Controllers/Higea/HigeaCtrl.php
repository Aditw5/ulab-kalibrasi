<?php

namespace App\Http\Controllers\Higea;

use App\Http\Controllers\Higea\BaseHigeaCtrl;
use App\Http\Controllers\Higea\ServerResponse;
use App\Models\Master\Agama;
use App\Models\Master\Alamat;
use App\Models\Master\AsalRujukan;
use App\Models\Master\JenisKelamin;
use App\Models\Master\Kelas;
use App\Models\Master\KelompokPasien;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Pendidikan;
use App\Models\Master\Rekanan;
use App\Models\Master\Ruangan;
use App\Models\Master\RunningNumber;
use App\Models\Standar\LoginUser;
use App\Models\Transaksi\AntrianPasienDiperiksa;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Builder;

class HigeaCtrl extends BaseHigeaCtrl
{
    use Valet;
    public function createToken($namaUser)
    {
        $class = new Builder();
        $signer = new Sha512();
        $now = time();
        $token = $class->setHeader('alg', config('app.JWT_ALG'))
            ->set('sub', $namaUser)
            ->expiresAt($now + (config('app.JWT_EXPIRED_MINUTE') * 60))
            ->sign($signer, config('app.JWT_KEY'))
            ->getToken();
        return $token;
    }
    public function checkHashEncypt($password, $dbpass)
    {
        if (config('app.IS_PASSWORD_HASH')) {
            return Hash::check($password, $dbpass);
        } else {
            return $this->hashing_password($password) == $dbpass;
        }
    }
    public function getAccessToken(Request $r)
    {
        $validator = Validator::make($r->all(), [
            'username' => 'required',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->error(ServerResponse::VALIDATION, 400, ['error' => $validator->errors()]);
        }
        $login = LoginUser::where('namauser', '=', $r->input('username'))
            ->where('statusenabled', true)
            ->first();
        if ($login) {
            $expired =  date("Y-m-d H:i:s", strtotime("+" . config('app.JWT_EXPIRED_MINUTE') . " minutes"));
            $response = [
                'user' => [
                    'userLogin' => $login->namauser
                ],
                'accessToken' => [
                    'token' =>  $this->createToken($login->namauser) . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired
                ]
            ];
            return $this->success(ServerResponse::SUCCESS, $response, 200);
        }
        return $this->error(ServerResponse::DATA_NOT_FOUND, 404);
    }
    public function getDoctor(Request $r)
    {
        $limit = $r->get('limit');
        $search = $r->get('search');
        $kdDokter = explode(',', $this->settingFix('kdPsikologDokter'));
        $result = Pegawai::mine()
            ->select('id', 'namalengkap')
            ->whereIn('objectjenispegawaifk', $kdDokter)
            ->when($search, function ($query) use ($search) {
                $query->like('namalengkap', 'LIKE', '%' . $search . '%');
            })
            ->when($limit, function ($query) use ($limit) {
                $query->limit($limit);
            })
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getRuangan(Request $r)
    {
        $isRawatInap = $r->get('isRawatinap');
        $filter['idDepartemenRI'] = explode(',', $this->settingFix('kdDepartemenRanapFix'));
        $filter['idDepartemenRJ'] = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $result =  Ruangan::when($isRawatInap, function ($query)  use ($isRawatInap, $filter) {
            if ($isRawatInap == 'true') {
                $query->whereIn('objectdepartemenfk', $filter['idDepartemenRI']);
            } else {
                $query->whereIn('objectdepartemenfk', $filter['idDepartemenRJ']);
            }
        })
            ->mine()
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getKelas(Request $r)
    {
        if ($r->ruanganId) {
            $result = DB::table('mapruangantokelas_m as mrk')
                ->join('kelas_m as kl', 'kl.id', '=', 'mrk.objectkelasfk')
                ->select('kl.id', 'kl.namakelas')
                ->where('mrk.objectruanganfk', $r->ruanganId)
                ->where('mrk.kdprofile',  $this->kdProfile)
                ->where('mrk.statusenabled', true)
                ->where('kl.statusenabled', true)
                ->orderBy('kl.namakelas')
                ->get();
        } else {
            $result = Kelas::mine()->get();
        }
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getKamarByKelas(Request $r)
    {
        $result = DB::table('kamar_m as kmr')
            ->join('ruangan_m as ru', 'ru.id', '=', 'kmr.objectruanganfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'kmr.objectkelasfk')
            ->select(
                'kmr.id as idKamar',
                'kmr.namakamar as namaKamar',
                'kl.id as id_kelas as idKelas',
                'ru.id as id_ruangan as idRuangan',
                'ru.namaruangan as namaRuangan',
                'kmr.jumlakamarisi as jumalhKamarIsi',
                'kmr.qtybed'
            )
            ->where('kmr.objectruanganfk', $r['idRuangan'])
            ->where('kmr.objectkelasfk', $r['idKelas'])
            ->where('kmr.statusenabled', true)
            ->where('kmr.kdprofile', $this->kdProfile)
            ->orderBy('kmr.namakamar')
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $result], 200);
    }
    public function getBed(Request $r)
    {
        $result['idStatusBedKosong'] = explode(',', $this->settingFix('idStatusBedKosong'));
        $data = DB::table('tempattidur_m')
            ->select('id', 'reportdisplay', 'nomorbed', 'objectkamarfk')
            ->where('kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('objectstatusbedfk', $result['idStatusBedKosong'])
            ->orderBy('reportdisplay')
            ->where('objectkamarfk', $r->idKamar)
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $data], 200);
    }

    public function getRujukan(Request $r)
    {
        $data = AsalRujukan::mine()->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $data], 200);
    }
    public function registrasi(Request $r)
    {
        try {
            //region Save
            $kdProfile  = $this->kdProfile;
            $PS         = $r['pasien'];
            $PD         = $r['pasienDaftar'];
            $APD        = $r['antrianPasienDiperiksa'];
            DB::beginTransaction();
            // checking pasien
            $pasien = Pasien::where('noidentitas', $PS['nik'])->where('statusenabled', true)->first();
            if (!$pasien) {
                $SET['idRunningNumberPasien']       = $this->settingFix('idRunningNumberPasien');
                $SET['idRunningNumberPasienBayi']   = $this->settingFix('idRunningNumberPasienBayi');
                $SET['HubunganKeluargaSendiri']     = $this->settingFix('HubunganKeluargaSendiri');
                $SET['jenisAlamatRumah']            = $this->settingFix('jenisAlamatRumah');

                $idRunningNumber    = $SET['idRunningNumberPasien'];
                $runningNumber      = RunningNumber::where('id', $idRunningNumber)->where('kdprofile', $kdProfile)->first();

                $noCm = $this->SEQUENCE(new Pasien, 'nocm',  (float)$runningNumber->length, $runningNumber->prefix, $kdProfile);
                RunningNumber::where('id', $idRunningNumber)
                    ->where('kdprofile', $kdProfile)
                    ->update(
                        [
                            'nomer_terbaru' => $noCm
                        ]
                    );
                $dataPS                 = new Pasien();
                $newId                  = $dataPS->generateNewId();
                $dataPS->id             = $newId;
                $dataPS->kdprofile      = (int)$this->kdProfile;
                $dataPS->statusenabled  = true;
                $dataPS->norec          = $newId;
                $dataPS->istelemedicine = false;
                $gender                 = ($PS['gender'] == 'M') ? 'L' : (($PS['gender'] == 'F') ? 'P' : NULL);

                $dataPS->namaexternal           = $PS['name'];
                $dataPS->reportdisplay          = $PS['name'];
                $dataPS->noidentitas            = $PS['nik'];
                $dataPS->objectagamafk          = null;
                $dataPS->objectjeniskelaminfk   = JenisKelamin::where('reportdisplay', $gender)->first()->id ?? null;
                $dataPS->objectpendidikanfk     = Pendidikan::where('id', (int)$PS['education'])->first()->id ?? null;

                $dataPS->namapasien     = $PS['name'];
                $dataPS->qpasien        = 1;
                $dataPS->tgldaftar      = date('Y-m-d H:i:s');
                $dataPS->tgllahir       = isset($PS['birthDate']) ? $PS['birthDate'] : null;
                $dataPS->email          = isset($PS['email']) ? $PS['email'] : null;
                $dataPS->nocm           = $noCm;
                $dataPS->save();

                $nocmfk                 = $dataPS->id;
                $dataAL                 = new Alamat();
                $idAlamat               = $dataAL->generateNewId();
                $dataAL->id             = $idAlamat;
                $dataAL->kdprofile      = (int)$kdProfile;
                $dataAL->statusenabled  = true;
                $dataAL->norec          = $idAlamat;
                $dataAL->nocmfk         = $nocmfk;
                $dataAL->alamatlengkap  = $PS['address'];
                $dataAL->save();
            } else {
                $dataPS = $pasien;
            }

            // checking depertemnt IGD
            $cekDepartemen = Ruangan::where('statusenabled', true)->where('kdprofile', $this->kdProfile)
                ->where('id', $PD['ruangan'])
                ->first();
            $isIGD = $cekDepartemen->objectdepartemenfk == $this->settingFix('idDepartemenIGD') ? 'true' : 'false';

            // setting data kelas
            $SET['idNonKelas']              = (int) $this->settingFix('idNonKelas');
            $SET['idJenisPelayananEksek']   = (int) $this->settingFix('idJenisPelayananEksek');
            $SET['idKelasIPKKU']            = (int) $this->settingFix('idKelasIPKKU');
            // noregistrasi generate
            $noregistrasi                   = $this->SEQUENCE(new PasienDaftar(), 'noregistrasi', 10, date('ymd'), $kdProfile);
            $noAntrian                      = 0;
            if ($noregistrasi == '') {
                abort(400, 'SEQ ERROR');
            }
            // insert pasien daftar
            $model_PD                       = new PasienDaftar();
            $model_PD->norec                = $model_PD->generateNewId();
            $model_PD->kdprofile            = $kdProfile;
            $model_PD->statusenabled        = true;
            $model_PD->objectruanganasalfk  = $PD['ruangan'];
            $model_PD->statuspasien         = $PD['statuspasien'];
            $namaLog = 'Tambah Registrasi ke Ruang ' . Ruangan::mine()->where('id', $PD['ruangan'])->first()->namaruangan . ' ';
            if ($PD['israwatinap'] == 'true') {
                $model_PD->tglpulang = null;
            }
            $model_PD->objectruanganlastfk  = $PD['ruangan'];
            $model_PD->objectpegawaifk      = $PD['pegawai'];
            $model_PD->jenispelayanan       = $PD['jenispelayananfk'];
            if ($PD['israwatinap'] == 'true') {
                $model_PD->objectkelasfk        = $PD['kelas'];
                $model_PD->objectkelasrawatfk   = $PD['kelasRawat'];
                $model_PD->tglpulang            = null;
            } else {

                if ($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek']) {
                    $model_PD->objectkelasfk =  $SET['idKelasIPKKU'];
                } else {
                    $model_PD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_PD->tglpulang =  $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_PD->objectkelompokpasienlastfk   = KelompokPasien::where('kelompokpasien', 'like', '%' . 'HIGEA' . '%')->first()->id ?? null;
            $model_PD->nocmfk                       = $dataPS->id;
            $model_PD->objectrekananfk              = Rekanan::where('namarekanan', 'like', '%' . 'HIGEA' . '%')->first()->id ?? null;
            $model_PD->ismobilejkn                  = null;
            $model_PD->tglregistrasi                = $PD['tglregistrasi'];
            $model_PD->asalrujukanfk                = $PD['rujukan'];
            $model_PD->keteranganasalrujukan        = $PD['rujukan'] == 5 ? null : (isset($PD['keteranganasalrujukan']) ? $PD['keteranganasalrujukan'] : null);
            $model_PD->noregistrasi                 = $noregistrasi;
            $model_PD->petugas                      = $this->getNamaPegawai();
            $model_PD->iskiosk                      = isset($PD['iskiosk']) ? $PD['iskiosk'] : null;
            $model_PD->save();


            if ($PD['israwatinap'] == 'true') {
                $SET['idStatusBedKosong'] = $this->settingFix('idStatusBedKosong');
                $SET['idStatusBedIsi'] = $this->settingFix('idStatusBedIsi');
            }

            $max = AntrianPasienDiperiksa::where('objectruanganfk', $PD['ruangan'])
                ->where('tglregistrasi', '>=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 00:00')
                ->where('tglregistrasi', '<=', date('Y-m-d', strtotime($PD['tglregistrasi'])) . ' 23:59')
                ->where('statusenabled', true)
                ->max('noantrian');
            $noAntrian = $max + 1;

            $model_APD                  = new AntrianPasienDiperiksa;
            $model_APD->norec           = $model_APD->generateNewId();
            $model_APD->kdprofile       = (int)$kdProfile;
            $model_APD->statusenabled   = true;
            $model_APD->noantrian       = $noAntrian;

            $model_APD->objectasalrujukanfk     = $PD['rujukan'];
            $model_APD->objectkamarfk           = $APD['objectkamarfk'];
            $model_APD->objectruanganfk         = $PD['ruangan'];
            if ($PD['israwatinap'] == 'true') {
                $model_APD->objectkelasfk       = $PD['kelas'];
                $model_APD->kelasrawatfk        = $PD['kelasRawat'];
                $model_APD->tglkeluar           = null;
            } else {

                if ($PD['jenispelayananfk'] == $SET['idJenisPelayananEksek']) {
                    $model_APD->objectkelasfk =  $SET['idKelasIPKKU'];
                } else {
                    $model_APD->objectkelasfk =  $SET['idNonKelas'];
                }
                $model_APD->tglkeluar = $isIGD == 'true' ? null : $PD['tglregistrasi'];
            }
            $model_APD->nobed               = $APD['nobed'];
            $model_APD->noregistrasifk      = $model_PD->norec;
            $model_APD->objectpegawaifk     = $PD['pegawai'];
            $model_APD->statusantrian       = 0;
            $model_APD->status              = "Belum Dipanggil";
            $model_APD->statuskunjungan     = $PD['statuspasien'];
            $model_APD->tglregistrasi       = $PD['tglregistrasi'];
            $model_APD->tglmasuk            = $PD['tglregistrasi'];
            $model_APD->israwatgabung       = null;
            $model_APD->noregistrasi        = $noregistrasi;
            $model_APD->save();

            //endregion
            $ps = Pasien::where('id', $dataPS->id)->first();
            $this->LOGGING(
                'Registrasi Pasien',
                $model_PD->norec,
                'pasiendaftar_t',
                $namaLog . ' pada Pasien ' .  $ps->namapasien . ' (' . $ps->nocm . ') - ' . $noregistrasi
            );

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            if ((isset($r['pasiendaftar']['isjkn']) && $r['pasiendaftar']['isjkn'] == true)) {
            } else {
                DB::commit();
            }
            $result = array(
                'status' => 200,
                'noregitrasi' => $noregistrasi
            );
            return $this->success(ServerResponse::SUCCESS, ['data' => $result['noregitrasi']], $result['status']);
        } else {
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getline()
            );
            return $this->success(ServerResponse::INTERNAL_SERVER_ERROR, ['stack' => $result['result']], $result['status']);
        }
    }

    public function getPasienRegistrasi(Request $r)
    {
        $kdProfile = (int) $this->kdProfile;
        $filter = $r->all();
        $data  = DB::table('pasien_m as ps')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftJoin('pasiendaftar_t as pd', function ($join) use ($r) {
                $dari = date('Y-m-d 00:00:00');
                $sampai = date('Y-m-d 23:59:59');
                $join->on('ps.id', '=', 'pd.nocmfk');
                $join->where('pd.statusenabled', true);
                if (isset($r['pasien_aktif']) && $r['pasien_aktif'] != '' && $r['pasien_aktif'] == 'true') {
                    $join->whereNotNull('pd.norec');
                    if (isset($r['dari']) && $r['dari'] != '' && isset($r['sampai']) && $r['sampai'] != '') {
                        $join->whereBetween('pd.tglregistrasi', [$r['dari'],  $r['sampai']]);
                    }
                } else {
                    $join->whereRaw("(
                        pd.tglregistrasi between '$dari' and '$sampai'
                        or pd.tglpulang is null
                    )");
                }
            })
            ->leftjoin('antrianpasienregistrasi_t as apr', 'apr.norec', '=', 'pd.antrianpasienregistrasifk')
            ->leftjoin('pemakaianasuransi_t as pa', 'pa.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('ruangan_m as ru', 'pd.objectruanganlastfk', '=', 'ru.id')
            ->leftjoin('departemen_m as dp', 'ru.objectdepartemenfk', '=', 'dp.id')
            ->leftjoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftjoin('kelompokpasien_m as kp', 'pd.objectkelompokpasienlastfk', '=', 'kp.id')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.id',
                'ps.namapasien',
                'ps.nocm',
                'ps.noidentitas',
                'ps.nohp',
                'ps.nobpjs',
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'ps.tempatlahir',
                'ps.tgllahir',
                'ps.tglmeninggal',
                'ps.namaibu',
                'ps.progress',
                'ru.namaruangan',
                'pd.noregistrasi',
                'pd.norec as norec_pd',
                'pd.tglregistrasi',
                'ps.foto',
                'pg.namalengkap as dokter',
                'pd.objectkelompokpasienlastfk',
                'kp.kelompokpasien',
                'ru.objectdepartemenfk',
                'kp.kelompokpasien',
                'ps.id as nocmfk',
                'pa.nosep',
                'pd.bahasa',
                'pd.bantuanpelayanan',
                'pd.bantuanpenerjemah',
                'pd.dikunjungi',
                'pd.ismobilejkn',
                'pd.ischeckin',
                'apr.noreservasi',
                'dp.namadepartemen',
                'pd.objectruanganlastfk'
            )
            ->where('ps.statusenabled', true)
            ->where('ps.kdprofile', $this->kdProfile)
            ->where('pd.objectkelompokpasienlastfk', 30)
            ->limit(4)
            ->get();
        return $this->success(ServerResponse::SUCCESS, ['data' => $data], 200);
    }
    public function daftarPasienPulang(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as p', 'p.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(
                'pd.norec AS norec_pd',
                'pd.tglregistrasi',
                'p.nocm as noCm',
                'p.namapasien as namaPasien',
                'ru.namaruangan as namaRuangan',
                'pd.tglpulang as tanggalPulang',
            )
            ->where('pd.statusenabled', true)
            ->whereNotNull('pd.tglpulang')
            ->where('pd.objectkelompokpasienlastfk', 30);

        $filter = $request->all();
        if (isset($filter['tglAwal']) && $filter['tglAwal'] != "" && $filter['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglpulang', '>=', $filter['tglAwal']);
        }

        if (isset($filter['tglAkhir']) && $filter['tglAkhir'] != "" && $filter['tglAkhir'] != "undefined") {
            $tgl = $filter['tglAkhir'];
            $data = $data->where('pd.tglpulang', '<=', $tgl);
        }
        $data = $data->get();

        return $this->success(ServerResponse::SUCCESS, ['data' => $data], 200);
    }
}
