<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master\Alamat;
use App\Models\Master\JenisPegawai as MasterJenisPegawai;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Standar\JenisPegawai;
use App\Models\Standar\KelompokUser;
use App\Models\Standar\LoginPasien;
use App\Models\Standar\LoginUser;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Builder;
// use Lcobucci\JWT\Configuration;


class AuthCtrl extends Controller
{
    public function login(Request $r)
    {
        if (empty($r->input('kataSandi')) || empty($r->input('namaUser'))) {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Username atau Password harus di isi',
                ),
                'response' => null,
            );

            return response()->json($response, $response['metaData']['code']);
        }
        $login = LoginUser::
            // where('katasandi', '=', $r->input('kataSandi')))
            where('namauser', '=', $r->input('namaUser'))
            ->where('statusenabled', true)
            ->first();
        if (!empty($login) && $this->checkHashEncypt($r->input('kataSandi'), $login->katasandi)) {
            $kelompokUser = KelompokUser::select('id', 'kelompokuser as kelompokUser','menu')
                ->where('id', '=', $login->objectkelompokuserfk)
                ->where('statusenabled', true)
                ->first();
            $pegawai = Pegawai::where('id', $login->objectpegawaifk)
                ->where('statusenabled', true)
                ->first();
            if (empty($pegawai)) {
                $response = array(
                    'metaData' => array(
                        "code" => 400,
                        "message" => 'Pegawai tidak aktif',
                    ),
                    'response' => null,
                );
                return response()->json($response, $response['metaData']['code']);
            }
            $jenisPegawai = MasterJenisPegawai::where('id', '=', $pegawai->objectjenispegawaifk)
                ->select('id', 'jenispegawai')
                ->first();

            $ruangKerja =  Ruangan::where('id', '=', $pegawai->objectruangankerjafk)
                ->where('statusenabled', true)
                ->first();
            $profile =  Profile::where('id', '=', $login->kdprofile)
                ->select('id', 'namalengkap as namaprofile', 'alamatlengkap', 'alamatemail', 'fixedphone', 'website', 'namakota', 'lat', 'lng', 'logoprofil',
                'namaexternal')
                ->first();
            $resPegawai = array(
                'id' => $pegawai->id,
                'namaLengkap' => $pegawai->namalengkap,
                'tempatLahir' => $pegawai->tempatlahir,
                'tglLahir' => $pegawai->tgllahir,
                'noIdentitas' => $pegawai->noidentitas,
                'statusEnabled' => $pegawai->statusenabled,
                'jenisPegawai' => $jenisPegawai,
                'ruangan' => $ruangKerja,
                'jenisKelamin_id' => $pegawai->objectjeniskelaminfk,
            );

            $dataLogin = array(
                'id' => $login->id,
                'kdProfile' => $login->kdprofile,
                'namaUser' => $login->namauser,
                'kelompokUser' => $kelompokUser,
                'pegawai' => $resPegawai,
                'profile' => $profile,
            );

            $expired=  date("Y-m-d H:i:s", strtotime("+".config('app.JWT_EXPIRED_MINUTE')." minutes"));

            // $en = base64_encode($expired);
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => array(
                    'token' =>  $this->createToken($login->namauser) . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ),
            );
        } else {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Login gagal, Username atau Password salah',
                ),
                'response' => null,
            );
        }
        return response()->json($response, $response['metaData']['code']);
    }
    public function checkHashEncypt($password, $dbpass)
    {
        if (config('app.IS_PASSWORD_HASH')) {
            return Hash::check($password, $dbpass);
        } else {
            return $this->hashing_password($password) == $dbpass;
        }
    }
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
    public function loginPasien(Request $r)
    {
        if (empty($r['norm']) || empty($r['tgllahir'])) {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'No Rekam Medis atau NIK atau Tgl Lahir harus di isi',
                ),
                'response' => null,
            );

            return response()->json($response, $response['metaData']['code']);
        }
        $login = Pasien::
            // where('nocm', '=', $r['norm'])
            where(function ($query) use ($r) {
                $query->where('nocm', '=',  $r['norm'])
                      ->orWhere('noidentitas', '=',  $r['norm']);
            })
            ->whereRaw("to_char(tgllahir,'dd-MM-yyyy' ) ='$r[tgllahir]'")
            ->where('statusenabled', true)
            ->first();
        if (!empty($login) ) {

            $kdProfile = $login->kdprofile;
            $pasien = DB::table('pasien_m as ps')
                ->leftjoin('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
                ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
                ->where('ps.id', $login->id)
                ->select(
                    'ps.id',
                    'ps.namapasien',
                    'ps.tgllahir',
                    'ps.tempatlahir',
                    'alm.alamatlengkap',
                    'jk.jeniskelamin',
                    'ps.nobpjs',
                    'ps.noidentitas',
                    'ps.nocm',
                    'ps.nohp',
                    'ps.objectjeniskelaminfk as jeniskelamin_id'
                )
                ->where('ps.statusenabled', true)
                ->where('ps.kdprofile', $kdProfile)
                ->first();


            if (empty($pasien)) {
                $response = array(
                    'metaData' => array(
                        "code" => 400,
                        "message" => 'Pasien sudah di nonaktifkan oleh pihak RS',
                    ),
                    'response' => null,
                );
                return response()->json($response, $response['metaData']['code']);
            }


            $profile =  Profile::where('id', '=', $login->kdprofile)
                ->select('namalengkap as namaprofile', 'alamatlengkap', 'alamatemail', 'fixedphone', 'website', 'namakota', 'lat', 'lng', 'logoprofil')
                ->first();


            $dataLogin = array(
                'id' => $login->id,
                'kdProfile' => $login->kdprofile,
                'namaUser' => $login->nocm,
                'pasien' => $pasien,
                'profile' => $profile,
            );
            $date = strtotime('+' . config('app.JWT_EXPIRED_MINUTE') . ' minutes', strtotime(date('Y-m-d H:i:s')));
            $expired = date('Y-m-d H:i:s', $date);

            // $en = base64_encode($expired);
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => array(
                    'token' =>  $this->createToken($login->nocm) . '' . '.' . base64_encode((string)$login->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ),
            );
        } else {
            $response = array(
                'metaData' => array(
                    "code" => 400,
                    "message" => 'Login gagal, No Rekam Medis atau Tgl Lahir tidak ditemukan',
                ),
                'response' => null,
            );
        }
        return response()->json($response, $response['metaData']['code']);
    }
}
