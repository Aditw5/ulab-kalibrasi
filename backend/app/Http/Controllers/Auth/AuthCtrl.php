<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Master\Alamat;
use App\Models\Master\JenisPegawai as MasterJenisPegawai;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Profile;
use App\Models\Master\Ruangan;
use App\Models\Master\User;
use App\Models\Standar\JenisPegawai;
use App\Models\Standar\KelompokUser;
use App\Models\Standar\LoginUser;
use DateTimeImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Signer\Hmac\Sha512;
use Lcobucci\JWT\Builder;
use Illuminate\Auth\Events\Registered;
use App\Traits\Valet;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Str;


class AuthCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
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

        $loginInput = $r->namaUser;
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            return $this->loginByEmail($r);
        }
        return $this->loginByUsername($r);
    }

    protected function loginByUsername(Request $r)
    {
        $login = LoginUser::where('namauser', '=', $r->input('namaUser'))
            ->where('statusenabled', true)
            ->first();
        if (!empty($login) && $this->checkHashEncypt($r->input('kataSandi'), $login->katasandi)) {
            $kelompokUser = KelompokUser::select('id', 'kelompokuser as kelompokUser', 'menu')
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
                ->select(
                    'id',
                    'namalengkap as namaprofile',
                    'alamatlengkap',
                    'alamatemail',
                    'fixedphone',
                    'website',
                    'namakota',
                    'lat',
                    'lng',
                    'logoprofil',
                    'namaexternal'
                )
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

            $expired =  date("Y-m-d H:i:s", strtotime("+" . config('app.JWT_EXPIRED_MINUTE') . " minutes"));
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

    protected function loginByEmail(Request $r)
    {
        $user = User::where('email', $r->input('namaUser'))
            ->where('statusenabled', true)
            ->first();

        if (! $user || ! $this->checkHashEncypt($r->input('kataSandi'), $user->password)) {
            return response()->json([
                'metaData' => ['code' => 400, 'message' => 'Login gagal, Email atau Password salah'],
                'response' => null
            ], 200);
        }
        if (is_null($user->email_verified_at)) {
            return response()->json([
                'metaData' => ['code' => 403, 'message' => 'Email belum diverifikasi. Cek inbox/spam.'],
                'response' => null
            ], 200);
        }
        if (!empty($user) && $this->checkHashEncypt($r->input('kataSandi'), $user->password)) {
            $kelompokUser = KelompokUser::select('id', 'kelompokuser as kelompokUser', 'menu')
                ->where('id', '=', 46)
                ->where('statusenabled', true)
                ->first();
            $pegawai = User::where('id', $user->id)
                ->where('statusenabled', true)
                ->first();

            $profile =  Profile::where('id', '=', $user->kdprofile)
                ->select(
                    'id',
                    'namalengkap as namaprofile',
                    'alamatlengkap',
                    'alamatemail',
                    'fixedphone',
                    'website',
                    'namakota',
                    'lat',
                    'lng',
                    'logoprofil',
                    'namaexternal'
                )
                ->first();
            $resPegawai = array(
                'id' => $pegawai->id,
                'namaLengkap' => $pegawai->name,
                'statusEnabled' => $pegawai->statusenabled,
            );

            $dataLogin = array(
                'id' => $user->id,
                'kdProfile' => $user->kdprofile,
                'namaUser' => $user->name,
                'kelompokUser' => $kelompokUser,
                'pegawai' => $resPegawai,
                'profile' => $profile,
            );

            $expired =  date("Y-m-d H:i:s", strtotime("+" . config('app.JWT_EXPIRED_MINUTE') . " minutes"));
            $response = array(
                'metaData' => array(
                    "code" => 200,
                    "message" => 'Sukses',
                ),
                'response' => array(
                    'token' =>  $this->createToken($user->email) . '' . '.' . base64_encode((string)$user->kdprofile),
                    'expired' => $expired,
                    'data' => $dataLogin,
                ),
            );
        }

        return response()->json($response, $response['metaData']['code']);
    }

    public function registerUser(Request $r)
    {
        DB::beginTransaction();
        try {
            $PSN =  $r['loginuser'];

            if (User::where('email', $PSN['email'])->exists()) {
                return $this->respond(
                    [
                        'status' => 400,
                        'result' => 'Email sudah digunakan'
                    ],
                    400,
                    'Gagal'
                );
            }

            $user  = new User();
            $user->id = $this->SEQUENCE_MASTER(new User(), 'id', $this->kdProfile);
            $user->kdprofile = (int)$this->kdProfile;
            $user->statusenabled = true;
            $user->name = $PSN['name'];
            $user->password = $this->hashing_password($PSN['password']);
            $user->email = $PSN['email'];
            $user->nowa = $PSN['nowa'];
            $user->save();
            $user->sendEmailVerificationNotification();

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses, Silahkan Verifikasi Email anda";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $user,
                    "as" => '@adit',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  =>  $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result, $result['status'], $transMessage);
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

    public function verifyEmail(Request $request, $id, $hash)
    {
        if (! $request->hasValidSignature()) {
            return response()->json([
                'status' => 403,
                'message' => 'Tautan verifikasi tidak valid atau sudah kedaluwarsa.',
            ], 403);
        }

        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json([
                'status' => 403,
                'message' => 'Hash verifikasi tidak cocok.',
            ], 403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return response()->json([
            'status' => 200,
            'message' => 'Email berhasil diverifikasi.',
        ]);
    }
}
