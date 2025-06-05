<?php

namespace App\Http\Controllers\Reservasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Alamat;
use App\Models\Master\Pasien;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use App\Models\Transaksi\PasienDaftar;
use App\Models\Transaksi\StrukBuktiPenerimaan;
use App\Models\Transaksi\StrukBuktiPengeluaran;
use App\Models\Transaksi\StrukPelayanan;
use App\Models\Transaksi\RisOrder;
use App\Traits\Valet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class ReservasiMobileCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getComboReservasi(Request $request)
    {
        $kdProfile = $this->kdProfile;
        // return  $kdProfile;
        $deptJalan = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $kdDepartemenRawatJalan = [];
        foreach ($deptJalan as $item) {
            $kdDepartemenRawatJalan[] =  (int)$item;
        }

        $dataRuanganJalan = DB::table('ruangan_m as ru')
            ->select('ru.id', 'ru.namaruangan', 'ru.objectdepartemenfk')
            ->where('ru.statusenabled', true)
            ->where('ru.kdprofile', $kdProfile)
            ->wherein('ru.objectdepartemenfk', $kdDepartemenRawatJalan)
            ->orderBy('ru.namaruangan')
            ->get();
        $jk = DB::table('jeniskelamin_m')
            ->select('id', 'jeniskelamin')
            ->where('statusenabled', true)
            ->orderBy('jeniskelamin')
            ->get();
        $kdJenisPegawaiDokter = $this->settingFix('idJenisPegawaiDokter');

        $dkoter = DB::table('pegawai_m')
            ->select('id', 'namalengkap')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->where('objectjenispegawaifk', $kdJenisPegawaiDokter)
            ->orderBy('namalengkap')
            ->get();

        $kelompokPasien = DB::table('kelompokpasien_m')
            ->select('id', 'kelompokpasien')
            ->where('kdprofile', $kdProfile)
            ->where('statusenabled', true)
            ->orderBy('kelompokpasien')
            ->get();

        $now = date('Y-m-d');
        $libur = DB::table('slottinglibur_m')
            ->select(DB::raw("to_char(tgllibur,'yyyy/MM/dd') as tgllibur,id,statusenabled"))
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->whereRaw("to_char(tgllibur,'yyyy-MM-dd') >= '$now'")
            ->orderBy('tgllibur')
            ->get();

        $result = array(
            'jeniskelamin' => $jk,
            'maxJamReservasi' => $this->settingFix('maxJamReservasi'),
            'isRentangReservasi' => (float) $this->settingFix('isRentangReservasi'),
            'kelompokpasien' => $kelompokPasien,
            'libur' => $libur,
            'ruanganrajal' => $dataRuanganJalan,
            'dokter' => $dkoter,


        );

        return $this->respond($result);
    }
    public function getDokterByRuang(Request $request)
    {

        if (!isset($request['id_ruangan']) || $request['id_ruangan'] == '') {
            return $this->respond([], 201, 'id_ruangan harus di isi');
        }
        if (!isset($request['tgl']) || $request['tgl'] == '') {

            return $this->respond([], 201, 'tgl harus di isi');
        }

        // $data10 = DB::table('pegawai_m')
        // ->select('id','namalengkap')
        // ->where('kdprofile',  $this->kdProfile)
        // ->where('statusenabled', true)
        // ->where('objectjenispegawaifk',  $this->settingFix('idJenisPegawaiDokter'))
        // ->orderBy('namalengkap')
        // ->get();

        $kdProfile = $this->kdProfile;
        $dokter = DB::table('jadwaldokter_m as slot')
            ->join('ruangan_m as ru', 'slot.objectruanganfk', '=', 'ru.id')
            ->join('pegawai_m as pg', 'slot.objectpegawaifk', '=', 'pg.id')
            ->select('pg.namalengkap as dokter', 'slot.hari', 'pg.id as idok')
            ->where('ru.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('ru.id', $request['id_ruangan'])
            ->where('slot.kdprofile', $kdProfile)
            ->where('slot.statusenabled', true)
            ->distinct()
            ->get();


        $hari = $this->hari_ini($request['tgl']);
        $data10 = [];
        for ($i = count($dokter) - 1; $i >= 0; $i--) {
            $now = explode(', ', $dokter[$i]->hari);
            for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                if (strtoupper($now[$i2]) == strtoupper($hari)) {
                    $data10[] = array(
                        'id' => $dokter[$i]->idok,
                        'namalengkap' => $dokter[$i]->dokter,
                        'jadwal' =>  strtoupper($hari),
                    );
                }
            }
        }
        if (count($data10) == 0) {
            $result = array(
                'list' => $data10,
            );
            return $this->respond($result, 201, 'Jadwal Dokter belum tersedia');
        }
        $result = array(
            'list' => $data10,
        );
        return $this->respond($result);
    }

    public function cekPasienByNik($nik)
    {
        try {
            $result = DB::table('pasien_m as ps')->select('*')->where('ps.noidentitas', $nik)->get();
        } catch (\Throwable $th) {
            return $this->respond($th);
        }

        return $this->respond($result);
    }

    public function getSlottingByRuanganDokter(Request $request)
    {

        try {
            $kdProfile = $this->kdProfile;
            $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
                ->select('apr.norec', 'apr.tanggalreservasi', 'apr.objectpegawaifk')
                ->whereRaw(" to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$request[tgl]'")
                ->where('apr.objectruanganfk', $request['id_ruangan'])
                // ->where('apr.objectpegawaifk', $request['id_dokter'])
                ->where('apr.noreservasi', '!=', '-')
                ->where('apr.kdprofile', $kdProfile)
                ->whereNotNull('apr.noreservasi')
                ->where('apr.statusenabled', true)
                ->get();


            //gabung sama kiosk & jkn
            $ruangan = DB::table('slottingkiosk_m as slot')
                ->join('ruangan_m as ru', 'slot.objectruanganfk', '=', 'ru.id')
                ->select(
                    'ru.id',
                    'ru.namaruangan',
                    'ru.objectdepartemenfk',
                    'slot.jambuka',
                    'slot.jamtutup',
                    'slot.quota',
                    'slot.hari',
                    DB::raw("( EXTRACT ( EPOCH FROM slot.jamtutup ) - EXTRACT ( EPOCH FROM slot.jambuka ) ) / 3600 AS totaljam ")
                )
                ->where('ru.statusenabled', true)
                ->where('ru.id', $request['id_ruangan'])
                ->where('slot.tanggal', $request['tgl'])
                ->where('slot.statusenabled', true)
                ->where('slot.kdprofile', $kdProfile)
                ->get();

            $dokter = DB::table('pegawai_m')
                ->select('id', 'namalengkap', 'namalengkap as dokter')
                ->where('kdprofile',  $this->kdProfile)
                ->where('statusenabled', true)
                ->where('id', $request['id_dokter'])
                ->first();

            if (empty($dokter)) {
                return $this->respond([], 201, ' Dokter tidak ditemukan');
            }

            // $ruangan = DB::table('jadwaldokter_m as slot')
            //     ->join('ruangan_m as ru', 'slot.objectruanganfk', '=', 'ru.id')
            //     ->join('pegawai_m as pg', 'slot.objectpegawaifk', '=', 'pg.id')
            //     ->select(
            //         'ru.id',
            //         'ru.namaruangan',
            //         'ru.objectdepartemenfk',
            //         'slot.jammulai as jambuka',
            //         'slot.jamakhir as jamtutup',
            //         'slot.quota',
            //         'pg.namalengkap as dokter',
            //         'slot.hari',
            //         'slot.objectpegawaifk',
            //         'pg.namalengkap',
            //         DB::raw("( EXTRACT ( EPOCH FROM slot.jamakhir ) - EXTRACT ( EPOCH FROM slot.jammulai ) ) / 3600 AS totaljam ")
            //     )
            //     ->where('ru.statusenabled', true)
            //     ->where('ru.statusenabled', true)
            //     ->where('ru.id', $request['id_ruangan'])
            //     ->where('slot.objectpegawaifk', $request['id_dokter'])
            //     ->where('slot.statusenabled', true)
            //     ->where('slot.kdprofile', $kdProfile)
            //     ->get();

            if (count($ruangan) == 0) {
                return $this->respond([], 201, 'Slot tidak ada/belum dijadwalkan');
            }
            $data10 = null;
            $hari = $this->hari_ini($request['tgl']);

            $quota = 0;
            foreach ($ruangan as $ruu) {
                $quota = $quota + $ruu->quota;
                // $now = explode(', ', $ruu->hari);
                // for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
                //     if (strtoupper($now[$i2]) == strtoupper($hari)) {
                $data10 = $ruu;
                //         break;
                //     }
                // }
            }

            if (empty($data10)) {
                return $this->respond([], 201, 'Jadwal Dokter tidak ditemukan');
            }


            $begin = new Carbon($data10->jambuka);
            $jamBuka = $begin->format('H:i');
            $end = new Carbon($data10->jamtutup);
            $jamTutup = $end->format('H:i');
            // $quota = (float)$data10->quota;
            if ($quota == 0) {
                return $this->respond([], 201, 'Kuota belum tersedia');
            }
            $waktuPerorang = ((float)$data10->totaljam / (float)$quota) * 60;

            $i = 0;
            $reservasi = [];
            foreach ($dataReservasi as $items) {
                $jamUse =  new Carbon($items->tanggalreservasi);
                $reservasi[] = array(
                    'jamreservasi' => $jamUse->format('H:i')
                );
            }

            $intervals = [];
            $begin = new \DateTime($jamBuka);
            $end = new \DateTime($jamTutup);
            $interval = \DateInterval::createFromDateString(floor($waktuPerorang) . ' minutes');

            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if ($request['tgl'] . ' ' . $dt->format("H:i") > date('Y-m-d H:i')) {
                    $intervals[] = array(
                        'jam' =>  $dt->format("H:i"),
                    );
                }
            }

            if (count($intervals) == 0) {
                return $this->respond([], 201, 'Slotting tidak ditemukan');
            }

            if (count($reservasi) > 0) {
                for ($j = count($reservasi) - 1; $j >= 0; $j--) {
                    for ($k = count($intervals) - 1; $k >= 0; $k--) {
                        if ($intervals[$k]['jam'] == $reservasi[$j]['jamreservasi']) {
                            // $intervals[$k]['terpakai'] = true;
                            array_splice($intervals, $k, 1);
                        }
                    }
                }
            }
            $slot  = array(
                'id_ruangan' => $data10->id,
                'namaruangan' => $data10->namaruangan,
                'id_dokter' => $dokter->id,
                'namalengkap' => $dokter->namalengkap,
                'hari' => $hari,
                'tgl' =>  $request['tgl'],
                'jambuka' => $jamBuka,
                'jamtutup' => $jamTutup,
                'totaljam' => $data10->totaljam,
                'quota' => (float)$quota,
                'interval' => $waktuPerorang,

            );
            $result = array(
                'slot' => $slot,
                'reservasi' => $reservasi,
                'listjam' => $intervals,

            );
        } catch (\Exception $e) {
            return $this->respond([], 201, $e->getMessage() . ' ' . $e->getLine());
        }

        return $this->respond($result);
    }
    public function saveReservasi(Request $request)
    {
        DB::beginTransaction();
        try {

            $kdProfile = $this->kdProfile;
            $tgl = $request['tglReservasiFix'];

            $dataReservasi = DB::table('antrianpasienregistrasi_t as apr')
                ->select('apr.norec', 'apr.tanggalreservasi')
                ->whereRaw("apr.tanggalreservasi = '$tgl'")
                ->where('apr.objectruanganfk', $request['poliKlinik']['id'])
                ->where('apr.noreservasi', '!=', '-')
                ->whereNotNull('apr.noreservasi')
                ->where('apr.statusenabled', true)
                ->where('apr.kdprofile', (int) $kdProfile);
            if (isset($request['dokter']) && $request['dokter'] != null && isset($request['dokter']['id'])) {
                $dataReservasi = $dataReservasi->where('apr.objectpegawaifk', $request['dokter']['id']);
            }
            $dataReservasi = $dataReservasi->get();

            if (count($dataReservasi) > 0) {
                return $this->respond([], 201, 'Mohon maaf dijam tersebut sudah ada yang reservasi, Coba di jadwal yang lain');
            }
            // if (isset($request['dokter']) && $request['dokter'] != null && isset($request['dokter']['id'])) {
            //     // $dokter = DB::table('jadwaldokter_m as slot')
            //     //     ->select('slot.hari', 'slot.objectruanganfk', 'slot.objectpegawaifk')
            //     //     ->where('slot.objectruanganfk', $request['poliKlinik']['id'])
            //     //     ->where('slot.objectpegawaifk', $request['dokter']['id'])
            //     //     ->where('slot.statusenabled', true)
            //     //     ->get();

            //      $dokter = DB::table('slottingkiosk_m as slot')
            //         ->select('slot.hari', 'slot.objectruanganfk')
            //         ->where('slot.objectruanganfk', $request['poliKlinik']['id'])
            //         // ->where('slot.objectpegawaifk', $request['dokter']['id'])
            //         ->where('slot.statusenabled', true)
            //         ->get();
            //     $hari = $this->hari_ini($request['tglReservasiFix']);

            //     $data10 = [];
            //     for ($i = count($dokter) - 1; $i >= 0; $i--) {
            //         $now = explode(', ', $dokter[$i]->hari);
            //         for ($i2 = count($now) - 1; $i2 >= 0; $i2--) {
            //             if (strtoupper($now[$i2]) == strtoupper($hari)) {
            //                 $data10[] = $dokter[$i];
            //             }
            //         }
            //     }
            //     if (count($data10) == 0) {
            //         return $this->respond([], 201, 'Jadwal Dokter tidak tersedia di Poli ini');
            //     }
            // }

            if ($request['isBaru'] == false) {
                $pasien  = Pasien::where('nocm', $request['noCm'])
                    ->where('statusenabled', true)->first();

                $tglCek = substr(str_replace('/', '-', $tgl), 0, 10);
                $tglCek = date('Y-m-d', strtotime($tglCek));
                $cekPerpasien = DB::table('antrianpasienregistrasi_t as apr')
                    ->select('apr.norec', 'apr.tanggalreservasi')
                    ->whereRaw("to_char(apr.tanggalreservasi,'yyyy-MM-dd') = '$tglCek'")

                    ->where('apr.noreservasi', '!=', '-')
                    ->whereNotNull('apr.noreservasi')
                    ->where('apr.statusenabled', true)
                    ->where('apr.nocmfk', $pasien->id)
                    ->where('apr.kdprofile', (int) $kdProfile);
                // if (isset($request['dokter']) && $request['dokter'] != null && isset($request['dokter']['id'])) {
                //     $cekPerpasien = $cekPerpasien->where('apr.objectpegawaifk', $request['dokter']['id']);
                // }
                if ($request['tipePembayaran']['id'] != 2) {
                    $cekPerpasien = $cekPerpasien->where('apr.objectruanganfk', $request['poliKlinik']['id']);
                }
                $cekPerpasien = $cekPerpasien->get();

                if (count($cekPerpasien) > 0) {
                    return $this->respond([], 201, 'Mohon maaf tidak bisa reservasi dihari yang sama');
                }
            }

            $ruangan = DB::table('ruangan_m')->where('id', $request['poliKlinik']['id'])->first();
            $kiosk = DB::table('slottingkiosk_m')->where('objectruanganfk', $request['poliKlinik']['id'])->latest()->first();

            $newptp = new AntrianPasienRegistrasi();
            $nontrian = AntrianPasienRegistrasi::where('jenis', $ruangan->noruangan)
                ->where('loketkiosk', $kiosk->loket)
                ->whereBetween('tanggalreservasi', [
                    date('Y-m-d 00:00:00', strtotime($tgl)),
                    date('Y-m-d 23:59:59', strtotime($tgl))
                ])
                ->max('noantrian') + 1;
            $newptp->norec = $newptp->generateNewId();
            $newptp->kdprofile = (int) $kdProfile;
            $newptp->statusenabled = true;
            $newptp->noantrian = $nontrian;
            $newptp->isconfirm = false;
            $newptp->jenis = $ruangan->noruangan;
            $newptp->loketkiosk = $kiosk->loket;
            $newptp->objectruanganfk = $request['poliKlinik']['id'];
            $newptp->objectjeniskelaminfk = $request['jenisKelamin']['id'];
            $newptp->noreservasi = substr(Uuid::uuid4()->toString(), 0, 7);
            $newptp->tanggalreservasi = $request['tglReservasiFix'];
            $newptp->tgllahir = $request['tglLahir'];
            $newptp->objectkelompokpasienfk = $request['tipePembayaran']['id'];
            $newptp->objectpendidikanfk = 0;
            $newptp->namapasien =  $request['namaPasien'];
            $newptp->noidentitas =  $request['nik'];
            $newptp->tglinput = date('Y-m-d H:i:s');
            if ($request['tipePembayaran']['id'] == 2) {
                $newptp->nobpjs = $request['noKartuPeserta'];
                $newptp->norujukan = $request['noRujukan'];
            } else {
                $newptp->noasuransilain = $request['noKartuPeserta'];
            }
            $newptp->notelepon = $request['noTelpon'];
            if (isset($request['dokter']['id'])) {
                $newptp->objectpegawaifk =  $request['dokter']['id'];
            }
            if (isset($request['caraDaftar'])) {
                $newptp->caradaftar =  $request['caraDaftar'];
            }

            if ($request['isBaru'] == true) {
                $newptp->tipepasien = "BARU";
                $newptp->type = "BARU";
            } else {
                $newptp->tipepasien = "LAMA";
                $newptp->type = "LAMA";
            }

            if (isset($pasien) && !empty($pasien)) {
                $newptp->objectagamafk = $pasien->objectagamafk;
                $alamat = Alamat::where('nocmfk', $pasien->id)->first();
                if (!empty($alamat)) {
                    $newptp->alamatlengkap = $alamat->alamatlengkap;
                    $newptp->objectdesakelurahanfk = $alamat->objectdesakelurahanfk;
                    $newptp->negara = $alamat->objectnegarafk;
                }
                $newptp->objectgolongandarahfk =  $pasien->objectgolongandarahfk;
                $newptp->kebangsaan = $pasien->objectkebangsaanfk;
                $newptp->namaayah = $pasien->namaayah;
                $newptp->namaibu = $pasien->namaibu;
                $newptp->namasuamiistri = $pasien->namasuamiistri;

                $newptp->noaditional = $pasien->noaditional;
                //                $newptp->noantrian= 0;
                $newptp->noidentitas = $pasien->noidentitas;
                $newptp->nocmfk =  $pasien->id;
                $newptp->statuspanggil =  0;
                $newptp->paspor =  $pasien->paspor;
                $newptp->objectpekerjaanfk =  $pasien->objectpekerjaanfk;
                $newptp->objectpendidikanfk = $pasien->objectpendidikanfk != null ? $pasien->objectpendidikanfk  : 0;
                $newptp->objectstatusperkawinanfk =  $pasien->objectstatusperkawinanfk;
                $newptp->tempatlahir = $pasien->tempatlahir;
            }
            $newptp->keterangan = "reservasi-online";
            $newptp->save();
            $newptp->namaruangan = Ruangan::where('id', $newptp->objectruanganfk)
                ->where('kdprofile', (int) $kdProfile)
                ->first()->namaruangan;

            if (isset($request['dokter']['id'])) {
                $cek = Pegawai::where('id', $request['dokter']['id'])
                    ->where('kdprofile', (int) $kdProfile)
                    ->first();
                $newptp->dokter = !empty($cek) ? $cek->namalengkap : '-';
            }
            $noAntrian = $newptp->noantrian;
            $strJenis = $newptp->jenis;
            if (strlen($newptp->noantrian) == 1) {
                $noAntrian = $strJenis . "-00" . $newptp->noantrian;
            } else {
                $noAntrian = $strJenis . "-0" . $newptp->noantrian;
            }
            $transStatus = true;

            if (isset($pasien) && !empty($pasien)) {

                $asalrujukan = 20;
            switch ($request->jeniskunjungan) {
                case 1:
                    $asalrujukan = 20;
                    break;
                case 2:
                    $asalrujukan = 5;
                    break;
                case 3:
                    $asalrujukan = 5;
                    break;
                case 4:
                    $asalrujukan = 2;
                    break;
            }

            $idNonKelas = (int) $this->settingFix('idNonKelas');
            $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
            $idrekananBPJS = (int) $this->settingFix('BPJS_kodeRekanan');
            $idtipelayananBPJS = (int) $this->settingFix('idJenisPelayananReguler');
            $norecAntrianRegis = $newptp->norec;
            $noAntrianRegistrasi = $newptp->jenis . '-' . str_pad($newptp->noantrian, 3, "0", STR_PAD_LEFT);

                $pasiendaftar = array(
                    'norec' => '',
                    'nocmfk' => $pasien->id,
                    'tglregistrasi' => $newptp->tanggalreservasi,
                    'objectruanganlastfk' => $ruangan->id,
                    'asalrujukanfk' => $asalrujukan,
                    'keteranganasalrujukan' => null,
                    'objectkelompokpasienlastfk' => $request['tipePembayaran']['id'],
                    'jenispelayananfk' => $idtipelayananBPJS,
                    'objectpegawaifk' => $request['dokter']['id'],
                    'objectpegawairawatbersamafk' => null,
                    'objectkelasfk' => $idNonKelas,
                    'israwatinap' => false,
                    'catatan' => null,
                    'statuspasien' => !empty($pasien) ? "LAMA" : "BARU",
                    'objectrekananfk' => $idrekananBPJS,
                    'nocm' => !empty($pasien) ? $pasien->nocm : null,
                    'namapasien' => !empty($pasien) ? $pasien->namapasien : null,
                    'antrianpasienregistrasifk' => $norecAntrianRegis,
                    'nojkn' => $noAntrianRegistrasi, //null,
                    'isjkn' => false,
                    'noreservasi' => $newptp->noreservasi,
                );
                $antrianpasiendiperiksa = array(
                    'norec' => '',
                    'objectkamarfk' => null,
                    'nobed' => null,
                    'israwatgabung' => null,
                );

                $objetoRequest = new Request();
                $objetoRequest['pasiendaftar'] = $pasiendaftar;
                $objetoRequest['antrianpasiendiperiksa'] = $antrianpasiendiperiksa;
                $objetoRequest['user'] = $this->getNamaPegawai();
                $cek = app('App\Http\Controllers\Registrasi\RegistrasiRuanganCtrl')->saveRegistrasi($objetoRequest);
            }
        } catch (\Exception $e) {
            $transStatus = false;
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "data" => $newptp,
                    "noantrian" => $noAntrian,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 200,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getHistoryReservasi(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('jeniskelamin_m as jks', 'jks.id', '=', 'apr.objectjeniskelaminfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', '=', 'pm.objectpendidikanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->select(
                'apr.norec',
                'pm.nocm',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.objectruanganfk',
                'apr.objectpegawaifk',
                'ru.namaruangan',
                'apr.isconfirm',
                'pg.namalengkap as dokter',
                'pm.id as nocmfk',
                'pm.namapasien',
                'apr.namapasien',
                'alm.alamatlengkap',
                'pk.pekerjaan',
                'pm.noasuransilain',
                'pm.noidentitas',
                'apr.nobpjs',
                'pm.nohp',
                'pdd.pendidikan',
                'apr.type',
                'apr.jenis as kode_loket',
                'apr.noantrian',
                'kps.kelompokpasien',
                'apr.objectkelompokpasienfk',
                'ru.objectdepartemenfk',
                'ru.prefixnoantrian',
                'apr.norujukan',
                'ru.id as idruangan',
                'pg.id as iddokter',
                DB::raw('(case when pm.namapasien is null then apr.namapasien else pm.namapasien end) as namapasien,
                (case when apr.isconfirm=true then \'Confirm\' else \'Reservasi\' end) as status,case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when jk.jeniskelamin is null then jks.jeniskelamin else jk.jeniskelamin end as jeniskelamin,
                case when apr.tgllahir is null then pm.tgllahir else apr.tgllahir end as tgllahir,
                case when apr.tipepasien = \'LAMA\' then pm.nohp else  apr.notelepon end as notelepon,
                case when apr.perjanjianfk is not null then  \'pasien-kontrol\' else  \'reservasi-online\' end as jenis')
            )

            ->where('apr.noreservasi', '!=', '-')
            ->whereNotNull('apr.noreservasi')
            ->where('apr.kdprofile',  $kdProfile)
            ->where('apr.statusenabled', true);

        if (isset($request['jenis']) && $request['jenis'] != "" && $request['jenis'] == "reservasi-online") {
            $data =  $data->whereNull('apr.perjanjianfk');
        }
        if (isset($request['nocmNama']) && $request['nocmNama'] != "" && $request['nocmNama'] != "undefined" && $request['nocmNama'] != "null") {
            $data = $data->whereRaw("(pm.nocm  = '$request[nocmNama]' or apr.namapasien ilike '%$request[nocmNama]%')");
        }
        if (isset($request['tgllahir']) && $request['tgllahir'] != "" && $request['tgllahir'] != "undefined" && $request['tgllahir'] != "null" &&  $request['tgllahir'] != 'Invaliddate') {
            $tgllahir = $request['tgllahir'];
            $data =  $data->whereRaw("to_char( apr.tgllahir, 'dd-MM-yyyy')  ='$tgllahir' ");
        }

        if (isset($request['noReservasi']) && $request['noReservasi'] != "" && $request['noReservasi'] != "undefined" && $request['noReservasi'] != "null") {
            $data = $data->where('apr.noreservasi', $request['noReservasi']);
        }
        if (isset($request['nik']) && $request['nik'] != "" && $request['nik'] != "undefined" && $request['nik'] != "null") {
            $data =   $data->where('apr.noidentitas', $request['nik']);
        }
        if (
            isset($request['id_ruangan']) && $request['id_ruangan'] != ""
            && $request['id_ruangan'] != "undefined" && $request['id_ruangan'] != "null"
        ) {
            $data =  $data->where('apr.objectruanganfk', $request['id_ruangan']);
        }
        if (
            isset($request['id_dokter']) && $request['id_dokter'] != ""
            && $request['id_dokter'] != "undefined" && $request['id_dokter'] != "null"
        ) {
            $data =  $data->where('apr.objectpegawaifk', $request['id_dokter']);
        }
        if (
            isset($request['dari']) && $request['dari'] != ""
            && $request['dari'] != "undefined" && $request['dari'] != "null"
        ) {
            $data =  $data->where('apr.tanggalreservasi', '>=', $request['dari'] . ' 00:00');
        }
        if (
            isset($request['sampai']) && $request['sampai'] != ""
            && $request['sampai'] != "undefined" && $request['sampai'] != "null"
        ) {
            $data =  $data->where('apr.tanggalreservasi', '<=', $request['sampai'] . ' 23:59');
        }

        if (isset($request['cekin']) && $request['cekin'] == "true") {
            $data =  $data->whereNull('apr.isconfirm');
        }
        if (isset($request['pasien_id']) && $request['pasien_id'] != "") {
            $data =  $data->where('pm.id',  $request['pasien_id']);
        }
        $data = $data->orderBy('apr.tanggalreservasi', 'desc');
        if (isset($request['jmlRows']) && $request['jmlRows'] != "" && $request['jmlRows'] != "undefined" && $request['jmlRows'] != "null" && $request['jmlRows'] != 0) {
            $data = $data->take($request['jmlRows']);
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined" && $request['limit'] != "null" && $request['limit'] != 0) {
            $data = $data->take($request['limit']);
        }

        if (isset($request['jmlOffset']) && $request['jmlOffset'] != "" && $request['jmlOffset'] != "undefined" && $request['jmlOffset'] != "null") {
            $data = $data->offset($request['jmlOffset']);
        }

        $data = $data->get();

        if ($data[0]->noantrian < 10) {
            $noAntrian = $data[0]->kode_loket . '-00' . $data[0]->noantrian;
        } else if ($data[0]->noantrian < 100) {
            $noAntrian = $data[0]->kode_loket . '-0' . $data[0]->noantrian;
        } else {
            $noAntrian = $data[0]->kode_loket . '-' . $data[0]->noantrian;
        }

        $result = array(
            'total' => count($data),
            'data' => $data,
            'noantrian' => $noAntrian,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function deleteReservasi(Request $request)
    {
        DB::beginTransaction();
        try {
            AntrianPasienRegistrasi::where('norec', $request['norec'])->update([
                'statusenabled' => false,
            ]);
            $transStatus = 'true';

            $transMessage = "Hapus Reservasi Sukses";
        } catch (\Exception $e) {
            $transStatus = 'false';
            $transMessage = "Hapus Reservasi Gagal";
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
                "status" => 200,
                "result"  => null
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function getPasienByNoka($nokartu)
    {
        $data = DB::table('pasien_m as ps')
            ->leftJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('pendidikan_m as pdd', 'ps.objectpendidikanfk', '=', 'pdd.id')
            ->leftjoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'pdd.pendidikan',
                'pk.pekerjaan',
                'ps.noidentitas',
                'ps.notelepon',
                'ps.tempatlahir',
                'ps.nobpjs',
                DB::raw(" to_char ( ps.tgllahir,'yyyy-MM-dd') as tgllahir")
            )
            ->where('ps.statusenabled', true)
            ->where('ps.nobpjs', '=', $nokartu)
            ->get();

        $result = array(
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function getDaftarRiwayatRegistrasi(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $data = DB::table('pasien_m as ps')
            ->join('pasiendaftar_t as pd', 'pd.nocmfk', '=', 'ps.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasi', '=', 'pd.noregistrasi')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->select(DB::raw("pd.norec as norec_pd,pd.tglregistrasi,ps.nocm,pd.noregistrasi,ps.namapasien,ru.namaruangan,
			                  pg.namalengkap as namadokter,pd.tglpulang,ps.tgllahir,kp.kelompokpasien,kl.namakelas,
                              sum((
                                (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                                 * pp.jumlah)
                            + (case when pp.jasa is not null then pp.jasa else 0 end))
                             as totalbilling"))
            ->where('pd.statusenabled',  true)
            ->where('pd.kdprofile',   $kdProfile);


        if (isset($request['norm']) && $request['norm'] != "" && $request['norm'] != "undefined") {
            $data = $data->where('ps.nocm', '=',  $request['norm']);
        };
        if (isset($request['namaPasien']) && $request['namaPasien'] != "" && $request['namaPasien'] != "undefined") {
            $data = $data->where('ps.namapasien', 'ilike', '%' . $request['namaPasien'] . '%');
        };
        if (isset($request['noReg']) && $request['noReg'] != "" && $request['noReg'] != "undefined") {
            $data = $data->where('pd.noregistrasi', '=', $request['noReg']);
        };
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $data = $data->where('pd.objectruanganlastfk', '=', $request['idRuangan']);
        };

        $data = $data->where('ps.statusenabled', true);
        $data = $data->orderBy('pd.tglregistrasi', 'desc');
        $data = $data->groupBy(
            'pd.norec',
            'pd.tglregistrasi',
            'ps.nocm',
            'pd.noregistrasi',
            'ps.namapasien',
            'ru.namaruangan',
            'pg.namalengkap',
            'pd.tglpulang',
            'ps.tgllahir',
            'kp.kelompokpasien',
            'kl.namakelas'
        );
        $data = $data->limit(100);
        $data = $data->get();
        foreach ($data as $d) {
            $d->totalbilling =  'Rp. ' . number_format((float)  $d->totalbilling, 2, ',', '.');
        }
        $result = array(
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }
    public function billingPasien(Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = PasienDaftar::where('kdprofile', $kdProfile)
            ->where('norec', $r['norec_pd'])
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->select(
                'pp.norec',
                'prd.namaproduk',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap as penulisresep',
                'apd.norec as norec_apd',
                'pp.strukfk',
                DB::raw("
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
               ")
            )
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $kdProfile)
            ->where('apd.noregistrasifk', $r['norec_pd']);
        if (isset($r['strukfk']) && $r['strukfk'] != '' && $r['strukfk'] == null) {
            $data = $data->whereNull('pp.strukfk');
        }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();

        $result['total'] = 0;
        $result['deposit'] = 0;
        $result['diskon'] = 0;
        $result['dibayar'] = 0;
        $result['sisa'] = 0;

        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $item->dokterpemeriksa = $item->strukresepfk != null ? $item->penulisresep : '-';
            $item->checked = false;
            $result['total']  = $result['total']  + (float) $item->total;
            $result['diskon']  = $result['diskon']  + (float) $item->hargadiscount;
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->namaruangan == $group[$i]['namaruangan']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'namaruangan' => $item->namaruangan,
                    'list_pelayanan' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['namaruangan'] == $d2->namaruangan) {
                    $group[$k]['list_pelayanan'][] = array(
                        'tglpelayanan' => $d2->tglpelayanan,
                        'namapelayanan' => $d2->namaproduk,
                        'jumlah' => $d2->jumlah,
                        'total' => 'Rp. ' . number_format((float)$d2->total, 2, ',', '.'),

                    );
                }
            }
        }


        $result['klaim_bpjs']  = StrukPelayanan::totalKlaim($pd->noregistrasi);
        $result['deposit'] = StrukBuktiPenerimaan::deposit($pd->noregistrasi);
        $result['dibayar'] = StrukBuktiPenerimaan::totalBayar($pd->noregistrasi);
        $result['pengembalian'] = StrukBuktiPengeluaran::totalPengembalian($pd->noregistrasi);
        $result['sisa'] =   $result['total'] - $result['dibayar'] - $result['deposit'] - $result['klaim_bpjs'] + $result['pengembalian']; // -  $result['diskon'];'
        $result['terbilang'] = $this->terbilang($result['total']) . ' RUPIAH';
        $result['diskon']  = 'Rp. ' . number_format((float) $result['diskon'], 2, ',', '.');
        $result['total']  = 'Rp. ' . number_format((float) $result['total'], 2, ',', '.');
        $result['klaim_bpjs']  = 'Rp. ' . number_format($result['klaim_bpjs'], 2, ',', '.');
        $result['deposit'] = 'Rp. ' . number_format($result['deposit'], 2, ',', '.');
        $result['dibayar'] = 'Rp. ' . number_format($result['dibayar'], 2, ',', '.');
        $result['pengembalian'] = 'Rp. ' . number_format($result['pengembalian'], 2, ',', '.');


        $result['sisa'] = 'Rp. ' . number_format($result['sisa'], 2, ',', '.');
        $result['length'] = count($data);
        $result['detail_perruangan'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');

        $result['as'] = '@epic';
        return $this->respond($result);
    }

    public function infoBed(Request $r)
    {
        $kdProfile =  $this->kdProfile;
        $data = collect(DB::select("
        SELECT
          x.namaruangan,
          SUM (x.isi) AS isi,
          SUM (x.kosong) AS kosong,
      count(x.tt_id) as total
      FROM
          (
              SELECT
                  CAST (tt.nomorbed AS INT) AS nomor,
                  tt. ID AS tt_id,
                  tt.nomorbed AS namabed,
                  kmr. ID AS kmr_id,
                  kmr.namakamar,
                  ru. ID AS id_ruangan,
                  ru.namaruangan,
                  kls.namakelas,
                  sb.statusbed,
                  CASE 	WHEN sb. ID = 1 THEN	1	ELSE 0 END AS isi,
              CASE WHEN sb. ID = 2 THEN	1	ELSE 0	END AS kosong
          FROM
              tempattidur_m AS tt
          INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
          INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
          INNER JOIN kelas_m AS kls ON kls. ID = kmr.objectkelasfk
          INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
          WHERE
              tt.kdprofile = $kdProfile
          AND tt.statusenabled = TRUE
          AND kmr.statusenabled = TRUE
          ) AS x
      GROUP BY
          x.namaruangan

      "));
        $totalKamar = $data->count();
        $totalBed = 0;
        $totalIsi = 0;
        $totalKosong = 0;
        foreach ($data as $item) {
            $totalBed =    $totalBed + (float) $item->total;
            $totalIsi =    $totalIsi + (float) $item->isi;
            $totalKosong =    $totalKosong + (float) $item->kosong;
        }

        $tt = collect(DB::select("SELECT
      ru.id AS idruangan,
      ru.namaruangan,
      km.id AS idkamar,
      km.namakamar,
      tt.id AS idtempattidur,
      tt.reportdisplay,
      tt.nomorbed,
      sb.id AS idstatusbed,
      sb.statusbed,
      kl.id AS idkelas,
      kl.namakelas
  FROM
      tempattidur_m AS tt
  LEFT JOIN kamar_m AS km ON km.id = tt.objectkamarfk
  LEFT JOIN ruangan_m AS ru ON ru.id = km.objectruanganfk
  LEFT JOIN statusbed_m AS sb ON sb.id = tt.objectstatusbedfk
  LEFT JOIN kelas_m AS kl ON kl.id = km.objectkelasfk
  WHERE
      ru.objectdepartemenfk IN (16, 35)
  AND ru.statusenabled = true
  AND km.statusenabled = true
  AND tt.statusenabled = true
  AND tt.kdprofile = $kdProfile"));

        $data10 = [];
        $sama = false;
        $bed = 0;
        $isi = 0;
        $kosong = 0;
        foreach ($tt as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->namaruangan == $data10[$i]['namaruangan']) {
                    $sama = 1;
                    $jml = (float)$hideung['bed'] + 1;
                    $data10[$i]['bed'] = $jml;
                    if ($item->idstatusbed == 1) {
                        $data10[$i]['isi'] = (float)$hideung['isi'] + 1;
                    }
                    if ($item->idstatusbed == 2) {
                        $data10[$i]['kosong'] = (float)$hideung['kosong'] + 1;
                    }
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->idstatusbed == 1) {
                    $isi = 1;
                    $kosong = 0;
                }
                if ($item->idstatusbed == 2) {
                    $isi = 0;
                    $kosong = 1;
                }

                $data10[] = array(
                    'idruangan' => $item->idruangan,
                    'namaruangan' => $item->namaruangan,
                    'idstatusbed' => $item->idstatusbed,
                    'bed' => 1,
                    'kosong' => $kosong,
                    'isi' => $isi,
                );
            }
        }

        $res['totalKamar'] = $totalKamar;
        $res['totalBed'] = $totalBed;
        $res['totalIsi'] = $totalIsi;
        $res['totalKosong'] = $totalKosong;

        $res['detail'] = $data10;
        $res['as'] = '@epic';
        return $this->respond($res);
    }

    public function  jadwalDokter(Request $r)
    {
        $now = $this->hari_ini(date('Y-m-d'));
        $dokter  = DB::table('jadwaldokter_m as jd')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'jd.objectpegawaifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'jd.objectruanganfk')
            ->select(
                'pg.namalengkap',
                'jd.jammulai',
                'jd.jamakhir',
                'ru.namaruangan',
                DB::raw("jd.hari"),
            )
            ->where('jd.kdprofile', $this->kdProfile)
            ->where('jd.statusenabled', true)
            ->where('ru.statusenabled', true)
            ->where('pg.statusenabled', true)
            ->whereIn('ru.objectdepartemenfk',  explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            // ->where('jd.hari', 'ilike', '%'.$now .'%')
            ->where('pg.namalengkap', '<>', 'Dokter Umum');

        if (isset($r['ruanganid']) && $r['ruanganid'] != '') {
            $dokter = $dokter->where('ru.id', '=',  $r['ruanganid']);
        }
        if (isset($r['namadokter']) && $r['namadokter'] != '') {
            $dokter = $dokter->where('pg.namalengkap', 'ilike',  '%' . $r['namadokter'] . '%');
        }
        if (isset($r['hari']) && $r['hari'] != '') {
            $dokter = $dokter->where('jd.hari', 'ilike',  '%' . $r['hari'] . '%');
        }
        if (isset($r['limit']) && $r['limit'] != '') {
            $dokter = $dokter->limit($r['limit']);
        }
        $dokter->orderBy('ru.namaruangan');

        $data =  $dokter->get();

        // foreach($dokter as $d){
        //     $d->hari = $now;
        // }
        $group = [];
        foreach ($data as $item) {

            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->namaruangan == $group[$i]['namaruangan']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'namaruangan' => $item->namaruangan,
                    'list_jadwal' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['namaruangan'] == $d2->namaruangan) {
                    $group[$k]['list_jadwal'][] = $d2;
                }
            }
        }
        $res['data'] = $group;
        $res['as'] = '@epic';
        return $this->respond($res);
    }
    public function getPasienByNoCmTglLahir($nocm, $tgllahir)
    {
        $data = DB::table('pasien_m as ps')
            ->leftJOIN('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->leftjoin('pendidikan_m as pdd', 'ps.objectpendidikanfk', '=', 'pdd.id')
            ->leftjoin('pekerjaan_m as pk', 'ps.objectpekerjaanfk', '=', 'pk.id')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->select(
                'ps.nocm',
                'ps.id as nocmfk',
                'ps.namapasien',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'alm.alamatlengkap',
                'pdd.pendidikan',
                'pk.pekerjaan',
                'ps.noidentitas',
                'ps.notelepon',
                'ps.tempatlahir',
                'ps.nobpjs',
                DB::raw(" to_char ( ps.tgllahir,'yyyy-MM-dd') as tgllahir")
            )
            ->where('ps.statusenabled', true);
        // ->where('ps.nocm', $nocm);
        // if(isset($tgllahir) &&$tgllahir != "" && $tgllahir != "undefined" && $tgllahir != "null") {
        //     $data = $data ->whereRaw("CONVERT(varchar, ps.tgllahir, 105)  ='$tgllahir' " );
        // }
        if (isset($nocm) && $nocm != "" && $nocm != "undefined" && $nocm != "null") {
            $data = $data->where('ps.nocm', '=', $nocm)
                ->Orwhere('ps.noidentitas', '=', $nocm);
        }
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ramdanegie',
        );
        return $this->respond($result);
    }
    public function  getPasienByNoRegistrasi($noregistrasi, Request $request)
    {
        $kdProfile = $this->kdProfile;
        $data = \DB::table('pasiendaftar_t as pd')
            ->leftjoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftjoin('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->leftjoin('kelompokpasien_m as kps', 'kps.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('alamat_m as alm', 'alm.id', '=', 'pd.nocmfk')
            ->leftjoin('agama_m as agm', 'agm.id', '=', 'ps.objectagamafk')
            ->select(
                'pd.norec as norec_pd',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.tgllahir',
                'ps.namakeluarga',
                'ru.namaruangan',
                'kls.namakelas',
                'kps.kelompokpasien',
                'rk.namarekanan',
                'alm.alamatlengkap',
                'jk.jeniskelamin',
                'agm.agama',
                'ps.nohp',
                'pd.statuspasien',
                'pd.tglpulang'
            )
            ->where('pd.noregistrasi', $noregistrasi)
            ->where('pd.kdprofile',   $kdProfile)
            ->first();

        $result = array(
            'data' => $data,
            'message' => 'ramdanegie',
        );
        return $this->respond($result);
    }

    public function getTagihanEbilling($noregistasi, Request $request)
    {
        $kdProfile = $this->kdProfile;
        $pelayanan = \DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'apd.objectkelasfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftjoin('strukbuktipenerimaan_t as sbm', 'sp.nosbmlastfk', '=', 'sbm.norec')
            ->leftjoin('strukresep_t as sre', 'sre.norec', '=', 'pp.strukresepfk')
            ->select(
                'pp.norec',
                'pp.tglpelayanan',
                'pp.rke',
                'pr.id as prid',
                'pr.namaproduk',
                'pp.jumlah',
                'kl.id as klid',
                'kl.namakelas',
                'ru.id as ruid',
                'ru.namaruangan',
                'pp.produkfk',
                'pp.hargajual',
                'pp.hargadiscount',
                'sp.nostruk',
                'sp.tglstruk',
                'apd.norec as norec_apd',
                'sbm.nosbm',
                'sp.norec as norec_sp',
                'pp.jasa',
                'pd.nocmfk',
                'pd.nostruklastfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.norec as norec_pd',
                'pd.tglpulang',
                'pd.objectrekananfk as rekananid',
                'pp.jasa',
                'sp.totalharusdibayar',
                'sp.totalprekanan',
                'sp.totalbiayatambahan',
                'pp.aturanpakai',
                'pp.iscito',
                'pd.statuspasien',
                'pp.isparamedis',
                'pp.strukresepfk'
            )
            ->where('pd.kdprofile', $kdProfile)
            ->where('pd.noregistrasi', $noregistasi);
        //          ->orderBy('pp.tglpelayanan', 'pp.rke');

        $pelayanan = $pelayanan->get();

        if (count($pelayanan) > 0) {
            $details = null;
            foreach ($pelayanan as $value) {
                // if($value->prid != $this->getProdukIdDeposit()){
                $jasa = 0;
                if (isset($value->jasa) && $value->jasa != "" && $value->jasa != null) {
                    $jasa = (float) $value->jasa;
                }

                $harga = (float)$value->hargajual;
                $diskon = (float)$value->hargadiscount;
                $detail = array(
                    'norec' => $value->norec,
                    'tglPelayanan' => $value->tglpelayanan,
                    'namaPelayanan' => $value->namaproduk,
                    'jumlah' => (float)$value->jumlah,
                    'kelasTindakan' => @$value->namakelas,
                    'ruanganTindakan' => @$value->namaruangan,
                    'harga' => $harga,
                    'diskon' => $diskon,
                    'total' => (($harga - $diskon) * $value->jumlah) + $jasa,
                    'strukfk' => $value->nostruk,
                    'sbmfk' => $value->nosbm,
                    'pgid' => '',
                    'ruid' => $value->ruid,
                    'prid' => $value->prid,
                    'klid' => $value->klid,
                    'norec_apd' => $value->norec_apd,
                    'norec_pd' => $value->norec_pd,
                    'norec_sp' => $value->norec_sp,
                    'jasa' => $jasa,
                    'aturanpakai' => $value->aturanpakai,
                    'iscito' => $value->iscito,
                    'isparamedis' => $value->isparamedis,
                    'strukresepfk' => $value->strukresepfk
                );

                $details[] = $detail;
                // }


            }
        }

        $arrHsil = array(
            'details' => $details,
            'deposit' => StrukBuktiPenerimaan::deposit($noregistasi),
            'totalklaim' =>   StrukPelayanan::totalKlaim($noregistasi),
            'bayar' => StrukBuktiPenerimaan::totalBayar($noregistasi),
        );
        return $this->respond($arrHsil);
    }

    public  function getTotalKlaim($noregistrasi, $kdProfile)
    {
        $pelayanan = collect(\DB::select("select sum(x.totalppenjamin) as totalklaim
         from (select spp.norec,spp.totalppenjamin
         from pasiendaftar_t as pd
            join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
            join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec
            join strukpelayanan_t as sp on sp.norec= pp.strukfk
            join strukpelayananpenjamin_t as spp on spp.nostrukfk=sp.norec
            where pd.noregistrasi ='$noregistrasi'
        --and spp.statusenabled is null
        and pd.kdprofile=$kdProfile
        GROUP BY spp.norec,spp.totalppenjamin

        ) as x"))->first();
        if (!empty($pelayanan) && $pelayanan->totalklaim != null) {
            return (float) $pelayanan->totalklaim;
        } else {
            return 0;
        }
    }
    public function getTotolBayar($noregistrasi, $kdProfile)
    {
        $pelayanan = collect(\DB::select("select sum(x.totaldibayar) as totaldibayar
         from (select sbm.norec,sbm.totaldibayar
         from pasiendaftar_t as pd
        join antrianpasiendiperiksa_t as apd on apd.noregistrasifk=pd.norec
        join pelayananpasien_t as pp on pp.noregistrasifk =apd.norec
        join strukpelayanan_t as sp on sp.norec= pp.strukfk
        join strukbuktipenerimaan_t as sbm on sbm.nostrukfk = sp.norec
        where pd.noregistrasi ='$noregistrasi'
        and sbm.statusenabled =true
        and pd.kdprofile=$kdProfile
        GROUP BY sbm.norec,sbm.totaldibayar

        ) as x"))->first();
        if (!empty($pelayanan) && $pelayanan->totaldibayar != null) {
            return (float) $pelayanan->totaldibayar;
        } else {
            return 0;
        }
    }
    public function getHistoryReservasiMobile(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'apr.nocmfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('jeniskelamin_m as jks', 'jks.id', '=', 'apr.objectjeniskelaminfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'pm.objectpekerjaanfk')
            ->leftJoin('pendidikan_m as pdd', 'pdd.id', '=', 'pm.objectpendidikanfk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'apr.objectruanganfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'apr.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as kps', 'kps.id', '=', 'apr.objectkelompokpasienfk')
            ->select(
                'apr.norec',
                'pm.nocm',
                'apr.noreservasi',
                'apr.tanggalreservasi',
                'apr.objectruanganfk',
                'apr.objectpegawaifk',
                'ru.namaruangan',
                'apr.isconfirm',
                'apr.jenis as kode_loket',
                'apr.noantrian',
                'pg.namalengkap as dokter',
                'pm.id as nocmfk',
                'pm.namapasien',
                'apr.namapasien',
                'alm.alamatlengkap',
                'pk.pekerjaan',
                'pm.noasuransilain',
                'pm.noidentitas',
                'apr.nobpjs',
                'pm.nohp',
                'pdd.pendidikan',
                'apr.type',
                'kps.kelompokpasien',
                'apr.objectkelompokpasienfk',
                'ru.objectdepartemenfk',
                'ru.prefixnoantrian',
                'apr.norujukan',
                'ru.id as idruangan',
                'pg.id as iddokter',
                DB::raw('(case when pm.namapasien is null then apr.namapasien else pm.namapasien end) as namapasien,
                (case when apr.isconfirm=true then \'Confirm\' else \'Reservasi\' end) as status,case when pm.tempatlahir is null then apr.tempatlahir else pm.tempatlahir end as tempatlahir,
                case when jk.jeniskelamin is null then jks.jeniskelamin else jk.jeniskelamin end as jeniskelamin,
                case when apr.tgllahir is null then pm.tgllahir else apr.tgllahir end as tgllahir,
                case when apr.tipepasien = \'LAMA\' then pm.nohp else  apr.notelepon end as notelepon,
                case when apr.perjanjianfk is not null then  \'pasien-kontrol\' else  \'reservasi-online\' end as jenis,
                case when apr.statusenabled = true  then \'Aktif\' else \'Batal\' end as status')
            )

            ->where('apr.noreservasi', '!=', '-')
            ->whereNotNull('apr.noreservasi')
            ->where('apr.kdprofile',  $kdProfile);
        // ->where('apr.statusenabled', true);

        if (isset($request['jenis']) && $request['jenis'] != "" && $request['jenis'] == "reservasi-online") {
            $data =  $data->whereNull('apr.perjanjianfk');
        }
        if (isset($request['nocmNama']) && $request['nocmNama'] != "" && $request['nocmNama'] != "undefined" && $request['nocmNama'] != "null") {
            $data = $data->whereRaw("(pm.nocm  = '$request[nocmNama]' or apr.namapasien ilike '%$request[nocmNama]%')");
        }
        if (isset($request['tgllahir']) && $request['tgllahir'] != "" && $request['tgllahir'] != "undefined" && $request['tgllahir'] != "null" &&  $request['tgllahir'] != 'Invaliddate') {
            $tgllahir = $request['tgllahir'];
            $data =  $data->whereRaw("to_char( apr.tgllahir, 'dd-MM-yyyy')  ='$tgllahir' ");
        }

        if (isset($request['noReservasi']) && $request['noReservasi'] != "" && $request['noReservasi'] != "undefined" && $request['noReservasi'] != "null") {
            $data = $data->where('apr.noreservasi', $request['noReservasi']);
        }
        if (isset($request['nik']) && $request['nik'] != "" && $request['nik'] != "undefined" && $request['nik'] != "null") {
            $data =   $data->where('apr.noidentitas', $request['nik']);
        }
        if (
            isset($request['id_ruangan']) && $request['id_ruangan'] != ""
            && $request['id_ruangan'] != "undefined" && $request['id_ruangan'] != "null"
        ) {
            $data =  $data->where('apr.objectruanganfk', $request['id_ruangan']);
        }
        if (
            isset($request['id_dokter']) && $request['id_dokter'] != ""
            && $request['id_dokter'] != "undefined" && $request['id_dokter'] != "null"
        ) {
            $data =  $data->where('apr.objectpegawaifk', $request['id_dokter']);
        }
        if (
            isset($request['dari']) && $request['dari'] != ""
            && $request['dari'] != "undefined" && $request['dari'] != "null"
        ) {
            $data =  $data->where('apr.tanggalreservasi', '>=', $request['dari'] . ' 00:00');
        }
        if (
            isset($request['sampai']) && $request['sampai'] != ""
            && $request['sampai'] != "undefined" && $request['sampai'] != "null"
        ) {
            $data =  $data->where('apr.tanggalreservasi', '<=', $request['sampai'] . ' 23:59');
        }

        if (isset($request['cekin']) && $request['cekin'] == "true") {
            $data =  $data->whereNull('apr.isconfirm');
        }
        if (isset($request['pasien_id']) && $request['pasien_id'] != "") {
            $data =  $data->where('pm.id',  $request['pasien_id']);
        }
        $data = $data->orderBy('apr.tanggalreservasi', 'desc');
        if (isset($request['jmlRows']) && $request['jmlRows'] != "" && $request['jmlRows'] != "undefined" && $request['jmlRows'] != "null" && $request['jmlRows'] != 0) {
            $data = $data->take($request['jmlRows']);
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined" && $request['limit'] != "null" && $request['limit'] != 0) {
            $data = $data->take($request['limit']);
        }

        if (isset($request['jmlOffset']) && $request['jmlOffset'] != "" && $request['jmlOffset'] != "undefined" && $request['jmlOffset'] != "null") {
            $data = $data->offset($request['jmlOffset']);
        }
        $data = $data->get();
        $akrif = 0;
        $batal = 0;
        foreach ($data as $d) {
            if ($d->status == 'Aktif') {
                $akrif = $akrif + 1;
            } else {
                $batal = $batal + 1;
            }
            if ($d->kode_loket == null) {
                $noAntrian = '-';
            } else {
                if ($d->noantrian < 10) {
                    $noAntrian = $d->kode_loket . '-00' . $d->noantrian;
                } else if ($d->noantrian < 100) {
                    $noAntrian = $d->kode_loket . '-0' . $d->noantrian;
                } else {
                    $noAntrian = $d->kode_loket . '-' . $d->noantrian;
                }
            }
            $d->antrianloket = $noAntrian;
        }
        $result = array(
            'total' => count($data),
            'aktif' => $akrif,
            'batal' => $batal,
            'data' => $data,
            'as' => '@epic',
        );
        return $this->respond($result);
    }

    public function getHasilRadiologi($noorder, Request $r)
    {
        $kdProfile = (int)$this->kdProfile;
        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');
        $pd = DB::table('pasiendaftar_t')->where('pasiendaftar_t.kdprofile', $kdProfile)
            ->join('strukorder_t', 'strukorder_t.noregistrasifk', 'pasiendaftar_t.norec')
            ->where('strukorder_t.noorder', $noorder)
            ->first();
        $data = DB::table('pelayananpasien_t as pp')
            ->leftJoin('hasilradiologi_t as hr', 'hr.pelayananpasienfk', 'pp.norec')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'apd.objectkelasfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'pp.strukfk')
            ->leftJOIN('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
            ->leftJOIN('strukorder_t as so', 'so.norec', '=', 'pp.strukorderfk')
            ->leftJOIN('ris_order as ris', 'ris.order_no', 'so.noorder')
            ->select(
                'pp.norec',
                'apd.norec as norec_apd',
                'prd.namaproduk',
                'hr.norec as norec_hr',
                'kls.namakelas',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'pp.jumlah',
                'pp.hargasatuan',
                'pg.namalengkap',
                'apd.norec as norec_apd',
                'so.noorder',
                'pp.strukfk',
                'pp.produkfk',
                'hr.keterangan',
                'ru.objectdepartemenfk',
                'ris.order_key as idbridging',
                'ris.order_complete',
                'ris.description',
                'ris.report_date',
                'ris.link',
                'ris.accession_num',
                'ris.charge_doc_id',
                'ris.charge_doc_name',
                'so.norec as norec_so',
                'so.objectruangantujuanfk',
                'so.catatanklinis',
                DB::raw("  ris.patient_id || '-' || ris.order_cnt as radiologiid,
                case when pp.jasa is not null then pp.jasa else 0 end jasa,
                case when pp.hargadiscount is not null then pp.hargadiscount else 0 end hargadiscount,
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)
                 as total,
                to_char(pp.tglpelayanan,'yyyy-MM-dd')  as tglpelayanan_group ,
                case when pp.strukresepfk is null then 'Layanan' else 'Resep' end as jenis
               ")
            )
            ->where('pp.statusenabled', true)
            ->whereNull('pp.strukresepfk')
            ->where('pp.kdprofile', $kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenRadiologi'))
            ->where('so.noorder', $noorder);
        // ->where('pp.tglpelayanan', $r['tglpelayanan']);
        // if (isset($r['tglpelayanan']) && $r['tglpelayanan'] != '' && $r['tglpelayanan'] != 'undefined') {
        //     $data = $data->where('pp.tglpelayanan', $r['tglpelayanan']);
        // } else {
        //     $data = $data->whereDate('pp.tglpelayanan', '=', now()->toDateString());
        // }
        $data = $data->orderByDesc('pp.tglpelayanan');
        $data = $data->get();

        $pelayananpetugas = DB::table('pelayananpasienpetugas_t as ptu')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ptu.objectpegawaifk')
            ->select('ptu.pelayananpasien', 'pg.namalengkap', 'pg.id')
            ->where('ptu.kdprofile', $kdProfile)
            ->where('ptu.objectjenispetugaspefk', $sDokterPemeriksa)
            ->where('ptu.noregistrasi', $pd->noregistrasi)
            ->get();


        $sama = false;
        $group  = [];
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($data as $item) {
            $item->checked = false;
            $item->url_pacs_hasil = null;
            if ($item->order_complete == 1) {
                $exp = explode(',', $urlPACSHasil);
                $exp[0] = $exp[0] . $item->accession_num;
                $exp[1] = $exp[1] . $item->accession_num;
                $item->url_pacs_hasil = $exp[1];
            }
            foreach ($pelayananpetugas as $itemd) {
                if ($itemd->pelayananpasien == $item->norec) {
                    $item->dokterpemeriksa = $itemd->namalengkap;
                    $item->pegawaifk = $itemd->id;
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->tglpelayanan_group == $group[$i]['tglpelayanan_group']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $group[] = array(
                    'tglpelayanan_group' => $item->tglpelayanan_group,
                    'details' => []
                );
            }
        }
        foreach ($group as $k => $d) {
            foreach ($data as $d2) {
                if ($d['tglpelayanan_group'] == $d2->tglpelayanan_group) {
                    $group[$k]['details'][] = $d2;
                }
            }
        }


        // $result['length'] = count($data);
        $result['detail'] = $group; //collect($data)->groupBy('tglpelayanan_group')->sortByDesc('tglpelayanan_group');
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function listRadiologi($pasienID)
    {
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenRadiologi');

        $nocmfk = '';
        $norec_pd = '';
        if (isset($pasienID) && $pasienID != '') {
            $nocmfk = " and pd.nocmfk='" . $pasienID . "'";
        }
        // if(isset($r['norec_pd']) && $r['norec_pd'] !=''){
        //     $norec_pd = " and pd.norec='".$r['norec_pd'] ."'";
        // }
        $echo = '';
        if (isset($r['echo'])) {
            $kdEcho = $this->settingFix('KdDetailJenisProdukEcho');
            $echo =  " and pr.objectdetailjenisprodukfk in ($kdEcho)";
        }

        $data = collect(DB::select("select so.tglorder,so.noorder, pp.status_tunda, pp.kettunda,
        pr.id,pr.namaproduk,op.qtyproduk,so.norec,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        when so.statusorder = 7 then 'Ditunda'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        when so.statusorder = 7 then 'danger'
        else 'warning' end as color_status,pp.produkfk,
        op.norec as norec_op,so.objectruangantujuanfk,
        pp.norec as norec_pp,hr.norec as norec_hr
        from strukorder_t as so
        inner join orderpelayanan_t as op on op.noorderfk = so.norec
        left join pelayananpasien_t as pp on pp.strukorderfk = so.norec and pp.produkfk=op.objectprodukfk
        left join hasilradiologi_t as hr on hr.pelayananpasienfk = pp.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and ru2.objectdepartemenfk =$depLab
        $nocmfk
        $echo

        union all

        select pp.tglpelayanan as tglorder,null as noorder, pp.status_tunda, pp.kettunda,
        pr.id,pr.namaproduk,pp.jumlah as qtyproduk ,pp.norec,
        ru2.namaruangan as ruanganasal,p.namalengkap as dokter,
        'selesai' as status,'success' as color_status,pp.produkfk,
        null as norec_op, apd.objectruanganfk as objectruangantujuanfk,
        pp.norec as norec_pp,hr.norec as norec_hr
        from pelayananpasien_t as pp
        left join hasilradiologi_t as hr on hr.pelayananpasienfk = pp.norec
        inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
        inner join ruangan_m as ru on ru.id=apd.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=pd.objectruanganlastfk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=pp.produkfk
        left join pegawai_m as p on p.id=apd.objectpegawaifk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and pp.strukresepfk is NULL
        and pp.strukorderfk is null
        and ru.objectdepartemenfk =$depLab
        $nocmfk
        ORDER BY tglorder"));

        $risorder = RisOrder::where('order_complete', true)->where('kdprofile', $kdProfile);
        if (isset($request['noregistrasi']) && $request['noregistrasi'] != "" && $request['noregistrasi'] != "undefined") {
            $risorder = $risorder->where('extension1', '=', $request['noregistrasi']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $risorder = $risorder->where('patient_id', '=', $request['nocm']);
        }
        $risorder = $risorder->get();
        $sama = false;
        $group  = [];
        $urlPACSHasil = $this->settingFix('urlPACSHasil');
        foreach ($data as $item) {
            $item->radiologiId = null;
            $item->url_pacs_hasil = null;
            foreach ($risorder as $ris) {
                if ($item->noorder == $ris->order_no && $item->id == $ris->order_code) {
                    $item->radiologiId =  $ris->patient_id . '-' . $ris->order_cnt;
                    if ($ris->order_complete == 1) {
                        $exp = explode(',', $urlPACSHasil);
                        $exp[0] = $exp[0] . $ris->accession_num;
                        $exp[1] = $exp[1] . $ris->accession_num;
                        $urlPACSHasil = implode(",", $exp);
                        $item->url_pacs_hasil = $urlPACSHasil;
                        $item->status = 'selesai';
                        $item->color_status = 'success';
                    }
                }
            }
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->norec == $group[$i]['norec']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $dataDetail0 = [];
                foreach ($data as $gg) {
                    if ($gg->norec == $item->norec) {
                        $dataDetail0[] = array(
                            'produkfk' => $gg->produkfk,
                            'namaproduk' =>  $gg->namaproduk,
                            'radiologiId' =>  $item->radiologiId,
                            'norec_pp' =>  $gg->norec_pp,
                            'url_pacs_hasil' => $item->url_pacs_hasil,
                        );
                    };
                }
                $group[] = array(
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'status_tunda' => $item->status_tunda,
                    'kettunda' => $item->kettunda,
                    'norec' => $item->norec,
                    'norec_hr' => $item->norec_hr,
                    'ruanganasal' => $item->ruanganasal,
                    'objectruangantujuanfk' => $item->objectruangantujuanfk,
                    'dokter' => $item->dokter,
                    'color_status' => $item->color_status,
                    'status' => $item->status,
                    'details' => $dataDetail0
                );
            }
        }
        return $this->respond($group);
    }

    public function riwayatOrderResep($pasienID)
    {
        $idProfile = $this->kdProfile;
        $nocmfk = '';
        $norec_pd = '';
        $penulisresep = '';
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
        $data = DB::table('strukorder_t as so')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.statusorder')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('strukresep_t as sr', function ($join) {
                $join->on('sr.orderfk', '=', 'so.norec');
                $join->where('sr.statusenabled', true);
            })
            ->select(
                'so.norec as norec_so',
                'so.noorder',
                'ru.namaruangan as namaruanganrawat',
                'so.tglorder',
                'so.tglorder as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.statusorder',
                'so.namapengambilorder',
                'so.noregistrasifk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.tglambilorder',
                'so.norec as norec_order',
                'so.isreseppulang',
                'so.isambilobat',
                'so.iskurir',
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'so.norec_apd',
                'so.objectruangantujuanfk',
                'so.objectpegawaiorderfk',
                'sr.norec as norec_resep'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk);

        if (isset($pasienID) && $pasienID != "" && $pasienID != "undefined") {
            $data = $data->where('pd.nocmfk', '=', $pasienID);
            $nocmfk = " and pd.nocmfk='" . $pasienID . "'";
        }
        $data = $data->orderByDesc('so.tglorder');
        $data = $data->get();

        $data2 = DB::table('strukresep_t as so')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.pasienfk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.ruanganfk')
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'so.status')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.penulisresepfk')
            ->select(
                'so.norec as norec_so',
                'so.noresep as noorder',
                'ru.namaruangan as namaruanganrawat',
                'so.tglresep as tglorder',
                'so.tglresep as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.status as statusorder',
                'so.namalengkapambilresep as namapengambilorder',
                'pd.norec as noregistrasifk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.tglambilresep as tglambilorder',
                'so.orderfk as norec_order',
                'so.isreseppulang',
                DB::raw("  null as isambilobat
            ,null as iskurir"),
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'apd.norec as norec_apd',
                'so.ruanganfk as objectruangantujuanfk',
                'so.penulisresepfk as objectpegawaiorderfk'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.statusenabled', true)
            ->whereNull('so.orderfk');

        if (isset($pasienID) && $pasienID != "" && $pasienID != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $pasienID);
        }
        $data2 = $data2->orderByDesc('so.tglresep');
        $data2 = $data2->get();

        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->join('pasiendaftar_t as pd', 'pd.noregistrasi', 'apd.noregistrasi')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'pp.jeniskemasanfk')
            ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'pp.jenisracikanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'pp.satuanviewfk')
            ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
            ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJOIN('konversisatuan_t as ks', function ($join) {
                $join->on('ks.objekprodukfk', '=', 'pr.id')
                    ->on('ks.satuanstandar_tujuan', '=', 'pp.satuanviewfk');
            })
            ->leftJOIN('rm_sediaan_m AS sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'pp.routefk')
            ->leftJoin('strukresep_t as so', 'so.norec', '=', 'pp.strukresepfk')
            ->select(
                DB::raw("pp.strukresepfk as norec_so,
            pp.rke, jk.jeniskemasan,
            pr.namaproduk, ss.satuanstandar, pp.aturanpakai,
            pp.jumlah, pp.hargasatuan,pp.keteranganpakai as keterangan,
            pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,pp.stock as jmlstok,

            pp.jeniskemasanfk,jk.id as jkid,pp.routefk,rt.name as namaroute,
            pp.produkfk as objectprodukfk,pr.namaproduk,pp.nilaikonversi as hasilkonversi,
            pr.objectsatuanstandarfk,ss.satuanstandar,pp.satuanviewfk,ss2.satuanstandar as ssview,
            pp.jumlah as qtyproduk,pp.hargadiscount,pp.nilaikonversi as hasilkonversi,pp.dosis,pp.jenisobatfk,
            pr.kekuatan,sdn.name as sediaan,pp.ispagi,pp.issiang,pp.ismalam,
            pp.issore,pp.keteranganpakai,pp.satuanresepfk,sn.satuanresep,pp.tglkadaluarsa,
            pp.strukterimafk,
            null  as asalprodukfk,null as asalproduk")
            )
            ->where('pp.kdprofile', $this->kdProfile)
            ->whereNotNull('pp.strukresepfk');
        if (isset($pasienID) && $pasienID != "" && $pasienID != "undefined") {
            $details2 = $details2->where('pd.nocmfk', '=', $pasienID);
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();

        foreach ($data as $item) {
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_resep == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }

        foreach ($data2 as $item) {
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }
        $data =  array_merge($data->toArray(), $data2->toArray());
        return $this->respond($data);
    }

    public function listLaboratorium($pasienID)
    {
        $kdProfile =  $this->kdProfile;
        $depLab = $this->settingFix('idDepartemenLab');
        $nocmfk = '';
        $norec_pd = '';
        if (isset($pasienID) && $pasienID != '') {
            $nocmfk = " and pd.nocmfk='" . $pasienID . "'";
        }
        // if(isset($r['norec_pd'] ) && $r['norec_pd'] !=''){
        //     $norec_pd = " and pd.norec='".$r['norec_pd'] ."'";
        // }
        $data = collect(DB::select("select so.tglorder,so.noorder,
        pr.id,pr.namaproduk,op.qtyproduk,so.norec,
        ru.namaruangan as ruanganasal,p.namalengkap as dokter,
        case when so.statusorder = 1 then 'verifikasi'
        when so.statusorder = 2 then 'selesai'
        else 'pending' end as status,
        case when so.statusorder = 1 then 'info'
        when so.statusorder = 2 then 'success'
        else 'warning' end as color_status,so.norec_apd,
        op.norec as norec_op
        from strukorder_t as so
        inner join orderpelayanan_t as op on op.noorderfk = so.norec
        inner join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=op.objectprodukfk
        inner join ruangan_m as ru on ru.id=so.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=so.objectruangantujuanfk
        left join pegawai_m as p on p.id=so.objectpegawaiorderfk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and so.statusenabled=true
        and ru2.objectdepartemenfk ='$depLab'
        $norec_pd
        $nocmfk
        union all

        select pp.tglpelayanan as tglorder,null as noorder,
        pr.id,pr.namaproduk,pp.jumlah as qtyproduk ,pp.norec,
        ru2.namaruangan as ruanganasal,p.namalengkap as dokter,
        'selesai' as status,'success' as color_status,apd.norec as norec_apd,
        null as norec_op
        from pelayananpasien_t as pp
        inner join antrianpasiendiperiksa_t as apd on apd.norec=pp.noregistrasifk
        inner join pasiendaftar_t as pd on pd.norec=apd.noregistrasifk
        inner join ruangan_m as ru on ru.id=apd.objectruanganfk
        inner join ruangan_m as ru2 on ru2.id=pd.objectruanganlastfk
        inner join pasien_m as ps on ps.id=pd.nocmfk
        inner join produk_m as pr on pr.id=pp.produkfk
        left join pegawai_m as p on p.id=apd.objectpegawaifk
        where
        pd.kdprofile=$kdProfile
        and pd.statusenabled=true
        and pp.strukresepfk is NULL
        and pp.strukorderfk is null
        and ru.objectdepartemenfk ='$depLab'
        $norec_pd
        $nocmfk
        ORDER BY tglorder"));
        $sama = false;
        $group  = [];
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($group as $itemg) {
                if ($item->norec == $group[$i]['norec']) {
                    $sama = true;
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $dataDetail0 = [];
                foreach ($data as $gg) {
                    if ($gg->norec == $item->norec) {
                        $dataDetail0[] = array(
                            'namaproduk' =>  $gg->namaproduk,
                            'pp_norec' =>  $gg->norec,
                        );
                    };
                }
                $group[] = array(
                    'tglorder' => $item->tglorder,
                    'noorder' => $item->noorder,
                    'norec' => $item->norec,
                    'ruanganasal' => $item->ruanganasal,
                    'dokter' => $item->dokter,
                    'color_status' => $item->color_status,
                    'status' => $item->status,
                    'norec_apd' => $item->norec_apd,
                    'details' => $dataDetail0
                );
            }
        }
        return $this->respond($group);
    }

    public function getHasilLaboratorium($noorder)
    {
        // hasil bridging
        $noorder = explode(',', $noorder);
        $placeholders = implode(',', array_fill(0, count($noorder), '?'));
        $dataBrid = DB::select(DB::raw("SELECT hl.nama_pemeriksaan as namaproduk, hl.nama_pemeriksaan as detailpemeriksaan,
        hl.hasil, hl.flag, hl.normal as nilaitext, hl.unit as satuanstandar, hl.user_validasi, hl.tgl_hasil, hl.metode
        FROM lab_hasil hl
        INNER JOIN strukorder_t so ON so.noorder = hl.no_order
        --INNER JOIN produk_m AS prd ON prd.id = hl.kode_pemeriksaan::int
        WHERE so.noorder IN ($placeholders)"), $noorder);

        $hasilbrid = [];
        foreach ($dataBrid as $item) {
            $hasilbrid[] = [
                'namaproduk' => $item->namaproduk,
                'detailpemeriksaan' => $item->detailpemeriksaan,
                'hasil' => $item->hasil,
                'flag' => $item->flag,
                'nilaitext' => $item->nilaitext,
                'satuanstandar' => $item->satuanstandar,
                'analis' => $item->user_validasi,
                'tglhasil' => $item->tgl_hasil,
                'metode' => $item->metode,
            ];
        }

        $res['data'] = $hasilbrid;
        return $this->respond($res);
    }
}
