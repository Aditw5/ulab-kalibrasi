<?php

namespace App\Http\Controllers\EMR;

use App\Http\Controllers\Controller;
use App\Models\Master\Diagnosa;
use App\Models\Master\Pegawai;
use App\Models\Transaksi\EMRPasien;
use App\Models\Transaksi\EMRPasienForm;
use App\Traits\Valet;
use DateTime;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\App;
use Ramsey\Uuid\Uuid;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ReportEMRCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    function dateLocalID($format, $time = false)
    { // "Asia/Tokyo"
        $day   = array('Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min');
        $days  = array('Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu');
        $month = array('', 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des');
        $months = array('', 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');

        if (!is_a($time, 'DateTime')) {
            if (is_int($time)) {
                $time = new DateTime(date('Y-m-d H:i:s.u', $time));
            } elseif (is_string($time)) {
                try {
                    $time = new DateTime($time);
                } catch (Exception $e) {
                    $time = new DateTime();
                }
            } else {
                $time = new DateTime();
            }
        }
        $ret = '';
        for ($i = 0; $i < strlen($format); $i++) {
            switch ($format[$i]) {
                case 'D':
                    $ret .= $day[$time->format('w')];
                    break;
                case 'l':
                    $ret .= $days[$time->format('w')];
                    break;
                case 'M':
                    $ret .= $month[$time->format('n')];
                    break;
                case 'F':
                    $ret .= $months[$time->format('n')];
                    break;
                case '\\':
                    $ret .= $format[$i + 1];
                    $i++;
                    break;
                default:
                    $ret .= $time->format($format[$i]);
                    break;
            }
        }
        return $ret;
    }

    public function init(Request $r)
    {

        $res = DB::connection('mongodb')
            ->table($r['collection'])
            ->where('registrasi.norec_pd', $r['norec_pd'])
            ->where('pasien.nocmfk', $r['nocmfk'])
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true);
        if (isset($r['emrpasienfk']) &&  $r['emrpasienfk'] != '') {
            $res = $res->where('emrpasienfk', $r['emrpasienfk']);
            $res = $res->where('emrpasienfk', $r['emrpasienfk']);
        }
        $res = $res->orderByDesc('created_at');
        $res = $res->get()->toArray();
        $data = $res[0];
        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
        ];
        $result = [
            "pasien" => $pasien,
            "datas" => $data,
        ];

        return $result;
    }

    public function getDiagnosaPasienICD9($nocm)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosatindakanpasien_t as dtp', 'dtp.objectpasienfk', '=', 'apd.norec')
            ->join('detaildiagnosatindakanpasien_t as ddt', 'ddt.objectdiagnosatindakanpasienfk', '=', 'dtp.norec')
            ->join('diagnosatindakan_m as dt', 'dt.id', '=', 'ddt.objectdiagnosatindakanfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddt.objectpegawaifk')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ru.namaruangan',
                'ddt.objectdiagnosatindakanfk',
                'dt.kddiagnosatindakan',
                'dt.namadiagnosatindakan',
                'ddt.keterangantindakan',
                'pg.namalengkap',
                DB::raw("CAST(ddt.tglinputdiagnosa as DATE)")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ps.nocm', '=', $nocm)
            ->orderby('ddt.tglinputdiagnosa', 'desc')
            ->get();

        return $data;
    }

    public function getDiagnosaPasienICD10($nocm)
    {

        $data = DB::table('pasiendaftar_t as pd')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'ddp.objectdiagnosafk',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.objectjenisdiagnosafk',
                'jd.jenisdiagnosa',
                'ddp.tglinputdiagnosa',
                DB::raw("CAST(ddp.tglinputdiagnosa as DATE)"),
                'pg.namalengkap',
                'dp.ketdiagnosis',
                'ddp.keterangan',
                'dp.iskasusbaru',
                'dp.iskasuslama'
            )
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'ddp.objectpegawaifk')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ps.nocm', $nocm)
            ->orderby('ddp.tglinputdiagnosa', 'desc')
            ->get();

        return $data;
    }

    public function cetakAsesmenMedisRI(Request $request)
    {

        $data = $this->init($request);

        $pasien = $data['pasien'];
        $data = $data['datas'];
        $profile = $this->profile();
        $icd9 = $this->getDiagnosaPasienICD9($pasien['nocm']);
        $icd10 = $this->getDiagnosaPasienICD10($pasien['nocm']);

        return view('report.emr.asesmen-medis-ri', compact('profile', 'pasien', 'data', 'icd9', 'icd10'));
    }

    public function cetakAsesmenGiziAwal(Request $request)
    {

        $data = $this->init($request);

        $pasien = $data['pasien'];
        $data = $data['datas'];
        $profile = $this->profile();

        return view('report.emr.asesmen-awal-gizi', compact('profile', 'pasien', 'data'));
    }

    public function cetakAsesmenAwalKeperawatanRI(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        $tgl = explode('T', $data[' '])[0];
        $waktu = explode('T', $data['tgldanJam'])[1];
        $jam = explode('.', $waktu)[0];
        return view('report.emr.asesmen-awal-keperawatan-ri', compact('profile', 'data', 'pasien', 'tgl', 'jam'));
    }

    public function cetakTriase(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        return view('report.emr.triase', compact('profile', 'data', 'pasien'));
    }

    public function cetakResumeRJ(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $tgl = explode(' ', $data['created_at'])[0];
        $waktu = explode(' ', $data['created_at'])[1];
        return view('report.emr.resume-medis-rj', compact('profile', 'data', 'pasien', 'tgl', 'waktu'));
    }

    public function cetakAsesmenAwalKeperRJ(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        return view('report.emr.asesmen-awal-keperawata-rj', compact('profile', 'data', 'pasien'));
    }

    public function cetakAsesmenMedisRJ(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $icd9 = $this->getDiagnosaPasienICD9($pasien['nocm']);
        $icd10 = $this->getDiagnosaPasienICD10($pasien['nocm']);
        $profile = $this->profile();

        return view('report.emr.asesmen-medis-rj', compact('profile', 'data', 'pasien', 'icd9', 'icd10'));
    }

    public function cetakHasilPemeriksaanMCU(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.hasil-pemeriksaan-mcu', compact('profile', 'data', 'pasien'));
    }

    public function cetakPolaNafasTidakEfektif(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.pola-nafas-tidak-efektif', compact('profile', 'data', 'pasien'));
    }
    public function cetakPengkajianDokterRi(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.pengkajian-dokter-ri',compact('profile','data','pasien'));
    }

    public function cetakFormulirSkriningIGD(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.formulir-skrining-igd', compact('profile', 'data', 'pasien'));
    }

    // public function cetakResumeMedis(Request $request)
    // {
    //     $datas = $this->init($request);
    //     $data =  $datas['datas'];
    //     $pasien = $datas['pasien'];
    //     $profile = $this->profile();

    //     return view('report.emr.resume-medis-lk',compact('profile','data','pasien'));
    // }

    public function cetakFormulirPermintaanKonselingGizi(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();

        return view('report.emr.formulir-permintaan-konseling-gizi', compact('profile', 'data', 'pasien'));
    }

    public function cetakAsesmenKeperawatanIGD(Request $request)
    {
        $datas = $this->init($request);
        $data =  $datas['datas'];
        $pasien = $datas['pasien'];
        $profile = $this->profile();
        $hari = $this->dateLocalID('l', $data['waktuPemeriksaan']) . ' / ' . date('d-m-Y', strtotime($data['waktuPemeriksaan']));
        // $tidakanIGD = $data['details']
        return view('report.emr.asesmen-keperawatan-igd', compact('profile', 'data', 'pasien', 'hari'));
    }
    public function cetakEMR($collection, Request $r)
    {
        // Display request data in a more readable format
        // echo "<pre>";
        // var_dump($r->all());
        // echo "</pre>";
        // exit();


        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            return null;
        }

        $registrasi = DB::table('pasiendaftar_t as pd')
                        ->join('kelompokpasien_m as klp', 'klp.id', 'pd.objectkelompokpasienlastfk')
                        ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
                        ->join('asalrujukan_m as ar', 'ar.id', 'pd.asalrujukanfk')
                        ->select(
                            'pd.norec',
                            'pd.noregistrasi',
                            'pd.noregistrasi',
                            'pd.tglregistrasi',
                            'klp.kelompokpasien',
                            'pd.tglpulang',
                            'ru.namaruangan',
                            'ar.asalrujukan'
                        );
        if(isset($r['noregistrasi'])){
            $registrasi = $registrasi->where('pd.noregistrasi', $r['noregistrasi']);
        }else{
            $registrasi = $registrasi->where('pd.noregistrasi', $data['registrasi']['noregistrasi']);
        }
        $registrasi = (array) $registrasi->first();

        if(!isset($data['registrasi']['objectpegawaifk'])){
            if(isset($r['storage']) && $r['storage']){
                return null;
            }
            return $this->respond('null',400,'DPJP belum diketahui, Silahkan simpan ulang');
        }

        if ($collection == 'PelaksanaanDanEvaluasiKeperawatan') {
            foreach ($data['detailTindakan'] as &$item) {
                $item['ttePetugas'] = (isset($item['namaParafPetugas']['value']) ? $item['namaParafPetugas']['value'] : '').(isset($item['namaParafPetugas']['label']) ? $item['namaParafPetugas']['label'] : '');
                if($item['ttePetugas'] == ''){
                    return $item;
                }
                $item['ttePetugas'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['ttePetugas']));
            }
        }

        $dataDokter = DB::table('pegawai_m')->where('id', $data['registrasi']['objectpegawaifk'])->first();
        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
            "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
            'tte' => $data['registrasi']['objectpegawaifk'] . $data['registrasi']['dokter'].',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
            'dpjp' => $data['registrasi']['dokter'],
            'nosip' => $dataDokter->nosip ? $dataDokter->nosip : '',
        ];
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;
        $selsesi = $r['final'];
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            if ($collection == 'PerencanaanKeperawatan' || $collection == 'PelaksanaanDanEvaluasiKeperawatan') {
                $pdf->setPaper('A4', 'landscape'); 
            } else {
                $pdf->setPaper('A4', 'portrait');
            }
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $registrasi, 'data' => $data, 'isSelesai' => $r['final']]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $registrasi, 'data' => $data, 'isSelesai' => $r['final']]);
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    public function cetakSuratObatEMR($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            echo '
            <script language="javascript">
                window.alert("Tidak ada data.");
                window.close()
            </script>';
            die;
        }

        if(!$data['registrasi']['objectpegawaifk']){
            return $this->respond('null',400,'DPJP belum diketahui, Silahkan simpan ulang');
        }
        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
            "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
            'tte' => $data['registrasi']['objectpegawaifk'] . ',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
            'dpjp' => $data['registrasi']['dokter'],
            'dokterPenanggungJawab' => isset($data['dokterPenanggungJawab']['label']) ? $data['dokterPenanggungJawab']['label'] : null,
            'jabatanDokter' => isset($data['jabatanDokter']) ? $data['jabatanDokter'] : null,
            'umur' => $data['umur'],
            'tgldirawat' => $data['tglDirawat'],
            'tglPembuatan' => $data['tglPembuatan'],
            'obatDiperlukan' => isset($data['obatDiperlukan']) ? $data['obatDiperlukan'] : null,
            'diagnosa' => isset($data['diagnosa']['label']) ? $data['diagnosa']['label'] : null,
        ];
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.' . $collection,
                array(
                    'profile' => $profile,
                    'data' => $data,
                    'pasien' => $pasien,
                    'identitas' => $pasien,
                    'tte' => $qrcode,
                    'header_use' => $header_use . "-dom",
                )
            );
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    public function cetakRehabLayanan($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $pasiendaftar = DB::table('pasiendaftar_t')
            ->where('noregistrasi', $r['noregistrasi'])
            ->first();

            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('pasien.nocmfk', $pasiendaftar->nocmfk)
                // ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->orderByDesc('tglPelayanan')
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            return null;
        }

        $dokterData = DB::table('pegawai_m')
            ->where('id',  $data['dokterttd']['value'])
            ->value('nosip');

        $nosipDokterTtd = $dokterData;

        $pasien = [
            'nosipDokterTtd' => $nosipDokterTtd,
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
            "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
            'ttePasien' => $data['namaPasien'] . ',' . $data['pasien']['nocm'] . ',' . date('Y-m-d'),
            'tte' => $data['dokterttd']['label'] . ',' . $data['registrasi']['objectpegawaifk'] . ',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
            'dpjp' => $data['registrasi']['dokter'],
            'umur' => $data['umur'],
            'namaPasien' => $data['namaPasien'],
            'alamat' => isset($data['alamat']) ? $data['alamat'] : null,
            'nohp' => isset($data['nohp']) ? $data['nohp'] : null,
            'hubunganDenganTertanggungAnak' => isset($data['hubunganDenganTertanggungAnak']) ? $data['hubunganDenganTertanggungAnak'] : false,
            'hubunganDenganTertanggungSuamiIstri' => isset($data['hubunganDenganTertanggungSuamiIstri']) ? $data['hubunganDenganTertanggungSuamiIstri'] : false,
            'suspekPenyakitAkibatKerjaYa' => isset($data['suspekPenyakitAkibatKerjaYa']) ? $data['suspekPenyakitAkibatKerjaYa'] : false,
            'suspekPenyakitAkibatKerjaTidak' => isset($data['suspekPenyakitAkibatKerjaTidak']) ? $data['suspekPenyakitAkibatKerjaTidak'] : false,
            'suspekPenyakitAkibatKerjaYaKeterangan' => isset($data['suspekPenyakitAkibatKerjaYaKeterangan']) ? $data['suspekPenyakitAkibatKerjaYaKeterangan'] : null,
            'auamnesa' => isset($data['auamnesa']) ? $data['auamnesa'] : null,
            'pemeriksaanFisikUjiFungsi' => isset($data['pemeriksaanFisikUjiFungsi']) ? $data['pemeriksaanFisikUjiFungsi'] : null,
            'tglPelayanan' => isset($data['tglPelayanan']) ? $data['tglPelayanan'] : null,
            'tglTTDDOkter' => isset($data['tglTTDDOkter']) ? $data['tglTTDDOkter'] : null,
            'pemeriksaanPenunjang' => isset($data['pemeriksaanPenunjang']) ? $data['pemeriksaanPenunjang'] : null,
            'tataLaksanaKFR' => isset($data['tataLaksanaKFR']) ? $data['tataLaksanaKFR'] : null,
            'anjuran' => isset($data['anjuran']) ? $data['anjuran'] : null,
            'evaluasi' => isset($data['evaluasi']) ? $data['evaluasi'] : null,
            'dokterttd' => isset($data['dokterttd']) ? $data['dokterttd'] : null,
            'tgldirawat' => $data['tglDirawat'],
            'tglPembuatan' => $data['tglPembuatan'],
            'diagnosa' => $data['diagnosa'],
        ];
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        $qrcodePasien = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['ttePasien']));
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'ttePasien' => $qrcodePasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.' . $collection,
                array(
                    'profile' => $profile,
                    'data' => $data,
                    'pasien' => $pasien,
                    'identitas' => $pasien,
                    'tte' => $qrcode,
                    'ttePasien' => $qrcodePasien,
                    'header_use' => $header_use . "-dom",
                )
            );
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    // public function cetakRehabPerdosi($collection, Request $r)
    // {
    //     // start generate parameter kebutuhan save dokumen
    //     if (isset($r['noregistrasi'])) {
    //         $dataEMR = DB::connection('mongodb')
    //             ->table($collection)
    //             ->where('profile.kdprofile', $this->kdProfile)
    //             ->where('statusenabled', true)
    //             ->where('registrasi.noregistrasi', $r['noregistrasi'])
    //             ->first();

    //         if (!empty($dataEMR))
    //             $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
    //         else
    //             $r['emrpasienfk'] = null;
    //     }
    //     // start generate parameter kebutuhan save dokumen
    //     $data = DB::connection('mongodb')
    //         ->table($collection)
    //         ->where('profile.kdprofile', $this->kdProfile)
    //         ->where('statusenabled', true)
    //         ->where('emrpasienfk', $r['emrpasienfk'])
    //         ->first();
    //     if (empty($data)) {
    //         return null;
    //     }
    //     // return $data['detaiperdosi'];

    //     // Filter detaiperdosi berdasarkan tanggal yang sesuai
    //     $filteredDetaiperdosi = $this->filterDetaiperdosiByTanggal($data['detaiperdosi'], $r->input('tanggal'), 'pilih');
    //     if (isset($r['storage']) && $r['storage']) {
    //         // return $r->input('tanggal');
    //         $filteredDetaiperdosi = $this->filterDetaiperdosiByTanggal($data['detaiperdosi'], $data['registrasi']['tglregistrasi'], 'klaim');
    //         // return $filterSebelumRegis;
    //     }
    //     // return $filteredDetaiperdosi;

    //     $dokterData = DB::table('pegawai_m')
    //         ->whereIn('id', array_column($filteredDetaiperdosi, 'namadokter2.value'))
    //         ->pluck('nosip');
    //     $doctorIds = [];

    //     foreach ($filteredDetaiperdosi as $item) {
    //         $doctorIds[] = $item['namadokter2']['label'];
    //     }
    //     $nosipDokterTtd = $doctorIds;

    //     $pasien = [
    //         'nosipDokterTtd' => $nosipDokterTtd,
    //         "nocm" => $data['pasien']['nocm'],
    //         "namapasien" => $data['pasien']['namapasien'],
    //         "jeniskelamin" => $data['pasien']['jeniskelamin'],
    //         "tgllahir" => $data['pasien']['tgllahir'],
    //         "namaruangan" => $data['registrasi']['namaruangan'],
    //         "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
    //         "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
    //         'ttePasien' => $data['namaPasien'] . ',' . $data['pasien']['nocm'] . ',' . date('Y-m-d'),
    //         'tte' => $data['dokter'] . ',' . $data['registrasi']['objectpegawaifk'] . ',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
    //         'dpjp' => $data['registrasi']['dokter'],
    //         'umur' => $data['umur'],
    //         'dokterPerdosi' => isset($filteredDetaiperdosi[0]['namadokter2']) ? $filteredDetaiperdosi[0]['namadokter2'] : '',
    //         // 'detaiperdosi' => $filteredDetaiperdosi,
    //         'detaiperdosi' => !empty($filteredDetaiperdosi) ? $filteredDetaiperdosi : (isset($data['detaiperdosi']) ? $data['detaiperdosi'] : []),
    //         'namaPasien' => $data['namaPasien'],
    //         'permintaanTerapi' => isset($data['permintaanTerapi']) ? $data['permintaanTerapi'] : null,
    //         'tglPembuatan' => $data['tglPembuatan'],
    //         'diagnosa' => $data['diagnosa'],
    //     ];
    //     $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
    //     $qrcodePasien = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['ttePasien']));
    //     $identitas = $pasien;
    //     $profile = $this->profile();
    //     $header_use = "head-emr";
    //     $blade = 'report.emr.' . $collection;
    //     if ($r['pdf'] == 'true') {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->setPaper('Legal', 'portrait');
    //         $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'ttePasien' => $qrcodePasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
    //         return $pdf->stream();
    //     }

    //     if (isset($r['storage']) && $r['storage']) {
    //         $pdf = App::make('dompdf.wrapper');
    //         $pdf->loadView(
    //             'report.emr.' . $collection,
    //             array(
    //                 'profile' => $profile,
    //                 'data' => $data,
    //                 'pasien' => $pasien,
    //                 'identitas' => $pasien,
    //                 'tte' => $qrcode,
    //                 'ttePasien' => $qrcodePasien,
    //                 'header_use' => $header_use . "-dom",
    //             )
    //         );
    //         return $pdf;
    //     }
    //     $registrasi = $data['registrasi'];
    //     return view('report.emr.' . $collection, compact('profile', 'data', 'pasien', 'identitas', 'registrasi', 'header_use', 'qrcode'));
    // }

    public function cetakRehabPerdosi($collection, Request $r)
{
    // Start generate parameter kebutuhan save dokumen
    if (isset($r['noregistrasi'])) {
        $dataPasien = DB::table('pasiendaftar_t as pd')
                        ->select(
                            'pas.nocm',
                            'pd.noregistrasi',
                            'pd.tglregistrasi',
                            'pd.objectruanganlastfk',
                        )
                        ->join('pasien_m as pas', 'pd.nocmfk', 'pas.id')
                        ->where('pd.noregistrasi', $r['noregistrasi'])->first();
        $dataEMR = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('pasien.nocm', $dataPasien->nocm)
            ->first();

        if (!empty($dataEMR))
            $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
        else
            $r['emrpasienfk'] = null;
    }

    // Start generate parameter kebutuhan save dokumen
    $data = DB::connection('mongodb')
        ->table($collection)
        ->where('profile.kdprofile', $this->kdProfile)
        ->where('statusenabled', true)
        ->where('emrpasienfk', $r['emrpasienfk'])
        ->first();
        $validRuanganIds = [167, 168, 169, 170, 171, 172, 173];

        // Periksa kondisi
        if (empty($data)) {
            return null;
        }

        if(isset($dataPasien) && !in_array($dataPasien->objectruanganlastfk, $validRuanganIds)){
            return null;
        }

    // Filter and sort detaiperdosi based on tanggal
    foreach ($data['detaiperdosi'] as &$item) {
        $item['tteDokter'] = (isset($item['namadokter2']['label']) ? $item['namadokter2']['label'] : $item['namadokter2']).(isset($item['namadokter2']['nosip']) ? $item['namadokter2']['nosip'] : '');
        if($item['tteDokter'] == ''){
            return $item;
        }
        $item['tteDokter'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['tteDokter']));
        $item['tteTerapis'] = (isset($item['namaterapis2']['label']) ? $item['namaterapis2']['label'] : '').(isset($item['namaterapis2']['value']) ? $item['namaterapis2']['value'] : '');
        $item['tteTerapis'] != '' ? $item['tteTerapis'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['tteTerapis'])) : '';
        $item['ttePasien'] = (isset($item['namapasien2']) ? $item['namapasien2']: '').(isset($item['tglregistrasi2']['tglregistrasi']) ? $item['tglregistrasi2']['tglregistrasi']: $item['tglregistrasi2']);
        $item['ttePasien'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['ttePasien']));
        // return $item;
    }


    $filteredDetaiperdosi = $this->filterDetaiperdosiByTanggal($data['detaiperdosi'], $r->input('tanggal'), 'pilih');
    if (isset($r['storage']) && $r['storage']) {
        $filteredDetaiperdosi = $this->filterDetaiperdosiByTanggal($data['detaiperdosi'], $dataPasien->tglregistrasi, 'klaim');
    }


    // Sort the filtered detaiperdosi by tglregistrasi2
    usort($filteredDetaiperdosi, function ($a, $b) {
        $dateA = is_array($a['tglregistrasi2']) ? $a['tglregistrasi2']['tglregistrasi'] : $a['tglregistrasi2'];
        $dateB = is_array($b['tglregistrasi2']) ? $b['tglregistrasi2']['tglregistrasi'] : $b['tglregistrasi2'];
        return strtotime($dateA) - strtotime($dateB);
    });

    $dokterData = DB::table('pegawai_m')
        ->whereIn('id', array_column($filteredDetaiperdosi, 'namadokter2.value'))
        ->pluck('nosip');
    $doctorIds = [];

    // foreach ($filteredDetaiperdosi as $item) {
    //     $doctorIds[] = $item['namadokter2']['label'] ?? $item['namadokter2']['label'];
    // }
    // $nosipDokterTtd = $doctorIds;

    $pasien = [
        // 'nosipDokterTtd' => $nosipDokterTtd,
        "nocm" => $data['pasien']['nocm'],
        "namapasien" => $data['pasien']['namapasien'],
        "jeniskelamin" => $data['pasien']['jeniskelamin'],
        "tgllahir" => $data['pasien']['tgllahir'],
        "namaruangan" => $data['registrasi']['namaruangan'],
        "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
        "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
        'ttePasien' => $data['namaPasien'] . ',' . $data['pasien']['nocm'] . ',' . date('Y-m-d'),
        'tte' => $data['dokter'] . ',' . $data['registrasi']['objectpegawaifk'] . ',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
        'dpjp' => $data['registrasi']['dokter'],
        'umur' => $data['umur'],
        'dokterPerdosi' => isset($filteredDetaiperdosi[0]['namadokter2']) ? $filteredDetaiperdosi[0]['namadokter2'] : '',
        'detaiperdosi' => !empty($filteredDetaiperdosi) ? $filteredDetaiperdosi : (isset($data['detaiperdosi']) ? $data['detaiperdosi'] : []),
        'namaPasien' => $data['namaPasien'],
        'permintaanTerapi' => isset($data['permintaanTerapi']) ? $data['permintaanTerapi'] : null,
        'tglPembuatan' => $data['tglPembuatan'],
        'diagnosa' => $data['diagnosa'],
    ];
    $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
    $qrcodePasien = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['ttePasien']));
    // return $data['detaiperdosi'];
    $identitas = $pasien;
    $profile = $this->profile();
    $header_use = "head-emr";
    $blade = 'report.emr.' . $collection;
    if ($r['pdf'] == 'true') {
        $pdf = App::make('dompdf.wrapper');
        $pdf->setPaper('Legal', 'portrait');
        $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'ttePasien' => $qrcodePasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
        return $pdf->stream();
    }

    if (isset($r['storage']) && $r['storage']) {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView(
            'report.emr.' . $collection,
            array(
                'profile' => $profile,
                'data' => $data,
                'pasien' => $pasien,
                'identitas' => $pasien,
                'tte' => $qrcode,
                'ttePasien' => $qrcodePasien,
                'header_use' => $header_use . "-dom",
            )
        );
        return $pdf;
    }
    $registrasi = $data['registrasi'];
    return view('report.emr.' . $collection, compact('profile', 'data', 'pasien', 'identitas', 'registrasi', 'header_use', 'qrcode'));
}


    private function filterDetaiperdosiByTanggal($detaiperdosi, $tanggal, $type)
    {
        // return $detaiperdosi;
        if($type == 'klaim'){
            return array_filter($detaiperdosi, function($item) use ($tanggal) {
                if (isset($item['tglregistrasi2'])) {
                    $itemDate = new DateTime(isset($item['tglregistrasi2']['tglregistrasi']) ? $item['tglregistrasi2']['tglregistrasi'] : $item['tglregistrasi2'] );
                    $filterDate = new DateTime($tanggal);
                    return $itemDate->format('Y-m-d') <= $filterDate->format('Y-m-d');
                }
                return false;
            });
        }else{
            return array_filter($detaiperdosi, function($item) use ($tanggal) {
                if (isset($item['tglregistrasi2'])) {
                    $itemDate = new DateTime(isset($item['tglregistrasi2']['tglregistrasi']) ? $item['tglregistrasi2']['tglregistrasi'] : $item['tglregistrasi2'] );
                    $filterDate = new DateTime($tanggal);
                    return $itemDate <= $filterDate;
                }
                return false;
            });

        }
    }

    public function cetakRehabProsedur($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $pasiendaftar = DB::table('pasiendaftar_t')
            ->where('noregistrasi', $r['noregistrasi'])
            ->first();

            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('pasien.nocmfk', $pasiendaftar->nocmfk)
                // ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->orderByDesc('tglPelayanan')
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            return null;
        }

        $dokterData = DB::table('pegawai_m')
            ->where('id',  $data['dokterPemeriksa']['value'])
            ->value('nosip');

        $nosipDokterTtd = $dokterData;

        $pasien = [
            'nosipDokterTtd' => $nosipDokterTtd,
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
            "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
            'tte' => $data['dokterPemeriksa']['label'] . ',' . $data['registrasi']['objectpegawaifk'] . ',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
            'dpjp' => $data['registrasi']['dokter'],
            'umur' => $data['umur'],
            'namaPasien' => $data['namaPasien'],
            'alamat' => isset($data['alamatlengkap']) ? $data['alamatlengkap'] : null,
            'nohp' => isset($data['nohp']) ? $data['nohp'] : null,
            'dokterPemeriksa' => isset($data['dokterPemeriksa']) ? $data['dokterPemeriksa'] : null,
            'tglPemeriksaan' => isset($data['tglPemeriksaan']) ? $data['tglPemeriksaan'] : null,
            'pemeriksaan' => isset($data['pemeriksaan']) ? $data['pemeriksaan'] : null,
            'fungsional' => isset($data['fungsional']) ? $data['fungsional'] : null,
            'diagnosa' => isset($data['diagnosa']) ? $data['diagnosa'] : null,
            'instrumenUjiFungsiProsedurKFR' => isset($data['instrumenUjiFungsiProsedurKFR']) ? $data['instrumenUjiFungsiProsedurKFR'] : null,
            'hasilYangDidapat' => isset($data['hasilYangDidapat']) ? $data['hasilYangDidapat'] : null,
            'kesimpulan' => isset($data['kesimpulan']) ? $data['kesimpulan'] : null,
            'rekomendasi' => isset($data['rekomendasi']) ? $data['rekomendasi'] : null,
        ];
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    public function cetakSuratPengantarRananp($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $pasiendaftar = DB::table('pasiendaftar_t')
            ->where('noregistrasi', $r['noregistrasi'])
            ->first();

            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('pasien.nocmfk', $pasiendaftar->nocmfk)
                // ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->orderByDesc('tglPelayanan')
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            return null;
        }


        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            "dateNow" => date('d') . ' ' . $this->dateLocalID('F ', date('Y-m-d')) . ' ' . date('Y'),
            "tglkontrol" => isset($data['tglperjanjian']) ? date('d', strtotime($data['tglperjanjian'])) . ' ' . $this->dateLocalID('F ', $data['tglperjanjian']) . ' ' . date('Y', strtotime($data['tglperjanjian'])) : '',
            'tte' => $data['registrasi']['objectpegawaifk'] . $data['registrasi']['dokter'].',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
            'dpjp' => $data['registrasi']['dokter'],
            'umur' => $data['pasien']['umur'],
            // 'nosip' => $dataDokter->nosip ? $dataDokter->nosip : '',
        ];
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . 'suratPengantarRawatInapDua';
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    public function cetakLembarAssesmenGizi($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            return null;
        }

        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            'tglPembuatan' => $data['tglPembuatan'],
            'tanggalJam' => isset($data['tanggalJam']) ? $data['tanggalJam'] : '',
            'asupanEnergiPenyakitAtauCederaAkut75persen7hari' => isset($data['asupanEnergiPenyakitAtauCederaAkut75persen7hari']) ? $data['asupanEnergiPenyakitAtauCederaAkut75persen7hari'] : false,
            'asupanEnergiPenyakitKronis75persen1bulan' => isset($data['asupanEnergiPenyakitKronis75persen1bulan']) ? $data['asupanEnergiPenyakitKronis75persen1bulan'] : false,
            'asupanEnergiKondisiSosialLingkungan75persen3bulan' => isset($data['asupanEnergiKondisiSosialLingkungan75persen3bulan']) ? $data['asupanEnergiKondisiSosialLingkungan75persen3bulan'] : false,
            'kehilanganBBPenyakitAtauCederaAkut1sampai2persen1minggu' => isset($data['kehilanganBBPenyakitAtauCederaAkut1sampai2persen1minggu']) ? $data['kehilanganBBPenyakitAtauCederaAkut1sampai2persen1minggu'] : false,
            'kehilanganBBPenyakitAtauCederaAkut5persen1bulan' => isset($data['kehilanganBBPenyakitAtauCederaAkut5persen1bulan']) ? $data['kehilanganBBPenyakitAtauCederaAkut5persen1bulan'] : false,
            'kehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan' => isset($data['kehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan']) ? $data['kehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan'] : false,
            'kehilanganBBPenyakitKronis5persen1bulan' => isset($data['kehilanganBBPenyakitKronis5persen1bulan']) ? $data['kehilanganBBPenyakitKronis5persen1bulan'] : false,
            'kehilanganBBPenyakitKronis7koma5persen3bulan' => isset($data['kehilanganBBPenyakitKronis7koma5persen3bulan']) ? $data['kehilanganBBPenyakitKronis7koma5persen3bulan'] : false,
            'kehilanganBBPenyakitKronis7koma10persen6bulan' => isset($data['kehilanganBBPenyakitKronis7koma10persen6bulan']) ? $data['kehilanganBBPenyakitKronis7koma10persen6bulan'] : false,
            'kehilanganBBPenyakitKronis20persen1tahun' => isset($data['kehilanganBBPenyakitKronis20persen1tahun']) ? $data['kehilanganBBPenyakitKronis20persen1tahun'] : false,
            'kehilanganBBKondisiSosialLingkungan5persen1bulan' => isset($data['kehilanganBBKondisiSosialLingkungan5persen1bulan']) ? $data['kehilanganBBKondisiSosialLingkungan5persen1bulan'] : false,
            'kehilanganBBKondisiSosialLingkungan7koma5persen3bulan' => isset($data['kehilanganBBKondisiSosialLingkungan7koma5persen3bulan']) ? $data['kehilanganBBKondisiSosialLingkungan7koma5persen3bulan'] : false,
            'kehilanganBBKondisiSosialLingkungan7koma10persen6bulan' => isset($data['kehilanganBBKondisiSosialLingkungan7koma10persen6bulan']) ? $data['kehilanganBBKondisiSosialLingkungan7koma10persen6bulan'] : false,
            'kehilanganBBKondisiSosialLingkungan20persen1tahun' => isset($data['kehilanganBBKondisiSosialLingkungan20persen1tahun']) ? $data['kehilanganBBKondisiSosialLingkungan20persen1tahun'] : false,
            'kehilanganMassaLemakPenyakitAtauCederaAkutRingan' => isset($data['kehilanganMassaLemakPenyakitAtauCederaAkutRingan']) ? $data['kehilanganMassaLemakPenyakitAtauCederaAkutRingan'] : false,
            'kehilanganMassaLemakPenyakitKronisRingan' => isset($data['kehilanganMassaLemakPenyakitKronisRingan']) ? $data['kehilanganMassaLemakPenyakitKronisRingan'] : false,
            'kehilanganMassaLemakKondisiSosialLingkunganRingan' => isset($data['kehilanganMassaLemakKondisiSosialLingkunganRingan']) ? $data['kehilanganMassaLemakKondisiSosialLingkunganRingan'] : false,
            'kehilanganMassaOtotPenyakitAtauCederaAkutRingan' => isset($data['kehilanganMassaOtotPenyakitAtauCederaAkutRingan']) ? $data['kehilanganMassaOtotPenyakitAtauCederaAkutRingan'] : false,
            'kehilanganMassaOtotPenyakitKronisRingan' => isset($data['kehilanganMassaOtotPenyakitKronisRingan']) ? $data['kehilanganMassaOtotPenyakitKronisRingan'] : false,
            'kehilanganMassaOtotKondisiSosialLingkunganRingan' => isset($data['kehilanganMassaOtotKondisiSosialLingkunganRingan']) ? $data['kehilanganMassaOtotKondisiSosialLingkunganRingan'] : false,
            'edemaAscitesPenyakitAtauCederaAkutRingan' => isset($data['edemaAscitesPenyakitAtauCederaAkutRingan']) ? $data['edemaAscitesPenyakitAtauCederaAkutRingan'] : false,
            'edemaAscitesPenyakitKronisRingan' => isset($data['edemaAscitesPenyakitKronisRingan']) ? $data['edemaAscitesPenyakitKronisRingan'] : false,
            'edemaAscitesKondisiSosialLingkunganRingan' => isset($data['edemaAscitesKondisiSosialLingkunganRingan']) ? $data['edemaAscitesKondisiSosialLingkunganRingan'] : false,
            'malnutrsiBeratAsupanEnergiPenyakitAtauCederaAkut50persen5hari' => isset($data['malnutrsiBeratAsupanEnergiPenyakitAtauCederaAkut50persen5hari']) ? $data['malnutrsiBeratAsupanEnergiPenyakitAtauCederaAkut50persen5hari'] : false,
            'malnutrsiBeratAsupanEnergiPenyakitKronis75persen1bulan' => isset($data['malnutrsiBeratAsupanEnergiPenyakitKronis75persen1bulan']) ? $data['malnutrsiBeratAsupanEnergiPenyakitKronis75persen1bulan'] : false,
            'malnutrisBeratAsupanEnergiKondisiSosialLingkungan50persen1bulan' => isset($data['malnutrisBeratAsupanEnergiKondisiSosialLingkungan50persen1bulan']) ? $data['malnutrisBeratAsupanEnergiKondisiSosialLingkungan50persen1bulan'] : false,
            'malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut2persen1minggu' => isset($data['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut2persen1minggu']) ? $data['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut2persen1minggu'] : false,
            'malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut5persen1bulan' => isset($data['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut5persen1bulan']) ? $data['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut5persen1bulan'] : false,
            'malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan' => isset($data['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan']) ? $data['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan'] : false,
            'malnutrisiBeratKehilanganBBPenyakitKronis5persen1bulan' => isset($data['malnutrisiBeratKehilanganBBPenyakitKronis5persen1bulan']) ? $data['malnutrisiBeratKehilanganBBPenyakitKronis5persen1bulan'] : false,
            'malnutrisiBeratKehilanganBBPenyakitKronis7koma5persen3bulan' => isset($data['malnutrisiBeratKehilanganBBPenyakitKronis7koma5persen3bulan']) ? $data['malnutrisiBeratKehilanganBBPenyakitKronis7koma5persen3bulan'] : false,
            'malnutrisiBeratKehilanganBBPenyakitKronis7koma10persen6bulan' => isset($data['malnutrisiBeratKehilanganBBPenyakitKronis7koma10persen6bulan']) ? $data['malnutrisiBeratKehilanganBBPenyakitKronis7koma10persen6bulan'] : false,
            'malnutrisiBeratKehilanganBBPenyakitKronis20persen1tahun' => isset($data['malnutrisiBeratKehilanganBBPenyakitKronis20persen1tahun']) ? $data['malnutrisiBeratKehilanganBBPenyakitKronis20persen1tahun'] : false,
            'malnutrisiBeratKehilanganBBKondisiSosialLingkungan5persen1bulan' => isset($data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan5persen1bulan']) ? $data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan5persen1bulan'] : false,
            'malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma5persen3bulan' => isset($data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma5persen3bulan']) ? $data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma5persen3bulan'] : false,
            'malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma10persen6bulan' => isset($data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma10persen6bulan']) ? $data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma10persen6bulan'] : false,
            'malnutrisiBeratKehilanganBBKondisiSosialLingkungan20persen1tahun' => isset($data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan20persen1tahun']) ? $data['malnutrisiBeratKehilanganBBKondisiSosialLingkungan20persen1tahun'] : false,
            'malnutrisiBeratKehilanganMassaLemakPenyakitAtauCederaAkutRingan' => isset($data['malnutrisiBeratKehilanganMassaLemakPenyakitAtauCederaAkutRingan']) ? $data['malnutrisiBeratKehilanganMassaLemakPenyakitAtauCederaAkutRingan'] : false,
            'malnutrisiBeratKehilanganMassaLemakPenyakitKronisBerat' => isset($data['malnutrisiBeratKehilanganMassaLemakPenyakitKronisBerat']) ? $data['malnutrisiBeratKehilanganMassaLemakPenyakitKronisBerat'] : false,
            'malnutrisiBeratKehilanganMassaLemakKondisiSosialLingkunganBerat' => isset($data['malnutrisiBeratKehilanganMassaLemakKondisiSosialLingkunganBerat']) ? $data['malnutrisiBeratKehilanganMassaLemakKondisiSosialLingkunganBerat'] : false,
            'malnutrisiBeratKehilanganMassaOtotPenyakitAtauCederaAkutRingan' => isset($data['malnutrisiBeratKehilanganMassaOtotPenyakitAtauCederaAkutRingan']) ? $data['malnutrisiBeratKehilanganMassaOtotPenyakitAtauCederaAkutRingan'] : false,
            'malnutrisiBeratKehilanganMassaOtotPenyakitKronisBerat' => isset($data['malnutrisiBeratKehilanganMassaOtotPenyakitKronisBerat']) ? $data['malnutrisiBeratKehilanganMassaOtotPenyakitKronisBerat'] : false,
            'malnutrisiBeratKehilanganMassaOtotKondisiSosialLingkunganBerat' => isset($data['malnutrisiBeratKehilanganMassaOtotKondisiSosialLingkunganBerat']) ? $data['malnutrisiBeratKehilanganMassaOtotKondisiSosialLingkunganBerat'] : false,
            'malnutrisiBeratEdemaAscitesPenyakitAtauCederaAkutRingan' => isset($data['malnutrisiBeratEdemaAscitesPenyakitAtauCederaAkutRingan']) ? $data['malnutrisiBeratEdemaAscitesPenyakitAtauCederaAkutRingan'] : false,
            'malnutrisiBeratEdemaAscitesPenyakitKronisRingan' => isset($data['malnutrisiBeratEdemaAscitesPenyakitKronisRingan']) ? $data['malnutrisiBeratEdemaAscitesPenyakitKronisRingan'] : false,
            'malnutrisiBeratEdemaAscitesKondisiSosialLingkunganRingan' => isset($data['malnutrisiBeratEdemaAscitesKondisiSosialLingkunganRingan']) ? $data['malnutrisiBeratEdemaAscitesKondisiSosialLingkunganRingan'] : false,
            'malnutrisiBeratPenurunanTanganPenyakitAtauCederaAkutMenurun' => isset($data['malnutrisiBeratPenurunanTanganPenyakitAtauCederaAkutMenurun']) ? $data['malnutrisiBeratPenurunanTanganPenyakitAtauCederaAkutMenurun'] : false,
            'malnutrisiBeratPenurunanTanganPenyakitKronisMenurun' => isset($data['malnutrisiBeratPenurunanTanganPenyakitKronisMenurun']) ? $data['malnutrisiBeratPenurunanTanganPenyakitKronisMenurun'] : false,
            'malnutrisiBeratPenurunanTanganKondisiSosialLingkunganMenurun' => isset($data['malnutrisiBeratPenurunanTanganKondisiSosialLingkunganMenurun']) ? $data['malnutrisiBeratPenurunanTanganKondisiSosialLingkunganMenurun'] : false,
            'malnutrisiSedangAntropometri' => isset($data['malnutrisiSedangAntropometri']) ? $data['malnutrisiSedangAntropometri'] : false,
            'malnutrisiSedangPemeriksaanLaboratorium' => isset($data['malnutrisiSedangPemeriksaanLaboratorium']) ? $data['malnutrisiSedangPemeriksaanLaboratorium'] : false,
            'malnutrisiBeratAntropometri' => isset($data['malnutrisiBeratAntropometri']) ? $data['malnutrisiBeratAntropometri'] : false,
            'malnutrisiBeratPemeriksaanLaboratorium' => isset($data['malnutrisiBeratPemeriksaanLaboratorium']) ? $data['malnutrisiBeratPemeriksaanLaboratorium'] : false,
            'kesimpulanMalnutrisiSedang' => isset($data['kesimpulanMalnutrisiSedang']) ? $data['kesimpulanMalnutrisiSedang'] : false,
            'kesimpulanMalnutrisiBerat' => isset($data['kesimpulanMalnutrisiBerat']) ? $data['kesimpulanMalnutrisiBerat'] : false,
        ];
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('Legal', 'portrait');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien,'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.' . $collection,
                array(
                    'profile' => $profile,
                    'data' => $data,
                    'pasien' => $pasien,
                    'identitas' => $pasien,
                    'header_use' => $header_use . "-dom",
                )
            );
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    public function cetakLembarAssesmenGiziMonitoring($collection, Request $r)
    {
        // start generate parameter kebutuhan save dokumen
        if (isset($r['noregistrasi'])) {
            $dataEMR = DB::connection('mongodb')
                ->table($collection)
                ->where('profile.kdprofile', $this->kdProfile)
                ->where('statusenabled', true)
                ->where('registrasi.noregistrasi', $r['noregistrasi'])
                ->first();

            if (!empty($dataEMR))
                $r['emrpasienfk'] = $dataEMR['emrpasienfk'];
            else
                $r['emrpasienfk'] = null;
        }
        // start generate parameter kebutuhan save dokumen
        $data = DB::connection('mongodb')
            ->table($collection)
            ->where('profile.kdprofile', $this->kdProfile)
            ->where('statusenabled', true)
            ->where('emrpasienfk', $r['emrpasienfk'])
            ->first();
        if (empty($data)) {
            return null;
        }

        $pasien = [
            "nocm" => $data['pasien']['nocm'],
            "namapasien" => $data['pasien']['namapasien'],
            "jeniskelamin" => $data['pasien']['jeniskelamin'],
            "tgllahir" => $data['pasien']['tgllahir'],
            "namaruangan" => $data['registrasi']['namaruangan'],
            'tglPembuatan' => $data['tglPembuatan'],
            'dpjp' => $data['registrasi']['dokter'],
            'tte' => $data['registrasi']['objectpegawaifk'] . ',' . $data['registrasi']['noregistrasi'] . ',' . date('Y-m-d'),
            'targetKebutuhanEnergiProtein' => isset($data['targetKebutuhanEnergiProtein']) ? $data['targetKebutuhanEnergiProtein'] : '',
            'jenisJalurTerapiGiziOral' => isset($data['jenisJalurTerapiGiziOral']) ? $data['jenisJalurTerapiGiziOral'] : false,
            'jenisJalurTerapiGiziEternalNutrition' => isset($data['jenisJalurTerapiGiziEternalNutrition']) ? $data['jenisJalurTerapiGiziEternalNutrition'] : false,
            'jenisJalurTerapiGiziOralPlusONS' => isset($data['jenisJalurTerapiGiziOralPlusONS']) ? $data['jenisJalurTerapiGiziOralPlusONS'] : false,
            'jenisJalurTerapiGiziEternalNutritionPlusParentalNurition' => isset($data['jenisJalurTerapiGiziEternalNutritionPlusParentalNurition']) ? $data['jenisJalurTerapiGiziEternalNutritionPlusParentalNurition'] : false,
            'jenisJalurTerapiGiziOralDietCair' => isset($data['jenisJalurTerapiGiziOralDietCair']) ? $data['jenisJalurTerapiGiziOralDietCair'] : false,
            'jenisJalurTerapiGiziTotalParenteralNutrition' => isset($data['jenisJalurTerapiGiziTotalParenteralNutrition']) ? $data['jenisJalurTerapiGiziTotalParenteralNutrition'] : false,
            'diagnosisKerja' => isset($data['diagnosisKerja']) ? $data['diagnosisKerja'] : '',
            'IMT' => isset($data['IMT']) ? $data['IMT'] : '',
            'AntropometriPemeriksaanFisiBeratBadankMonitoring' => isset($data['AntropometriPemeriksaanFisiBeratBadankMonitoring']) ? $data['AntropometriPemeriksaanFisiBeratBadankMonitoring'] : '',
            'AntropometriPemeriksaanFisikBeratBadanEvaluasi' => isset($data['AntropometriPemeriksaanFisikBeratBadanEvaluasi']) ? $data['AntropometriPemeriksaanFisikBeratBadanEvaluasi'] : '',
            'AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguan' => isset($data['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguan']) ? $data['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguan'] : '',
            'AntropometriPemeriksaanFisikPenurunanKekuatanTangan' => isset($data['AntropometriPemeriksaanFisikPenurunanKekuatanTangan']) ? $data['AntropometriPemeriksaanFisikPenurunanKekuatanTangan'] : '',
            'AntropometriPemeriksaanFisikPenurunanKekuatanTanganMonitoring' => isset($data['AntropometriPemeriksaanFisikPenurunanKekuatanTanganMonitoring']) ? $data['AntropometriPemeriksaanFisikPenurunanKekuatanTanganMonitoring'] : '',
            'AntropometriPemeriksaanFisikPenurunanKekuatanTanganEvaluasi' => isset($data['AntropometriPemeriksaanFisikPenurunanKekuatanTanganEvaluasi']) ? $data['AntropometriPemeriksaanFisikPenurunanKekuatanTanganEvaluasi'] : '',
            'AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguanBulanan' => isset($data['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguanBulanan']) ? $data['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguanBulanan'] : '',
            'pemeriksaanLabAlbumin' => isset($data['pemeriksaanLabAlbumin']) ? $data['pemeriksaanLabAlbumin'] : '',
            'pemeriksaanLaboratoriumAlbuminkMonitoring' => isset($data['pemeriksaanLaboratoriumAlbuminkMonitoring']) ? $data['pemeriksaanLaboratoriumAlbuminkMonitoring'] : '',
            'pemeriksaanLaboratoriumAlbuminEvaluasi' => isset($data['pemeriksaanLaboratoriumAlbuminEvaluasi']) ? $data['pemeriksaanLaboratoriumAlbuminEvaluasi'] : '',
            'pemeriksaanLaboratoriumAlbuminEvaluasiMingguan' => isset($data['pemeriksaanLaboratoriumAlbuminEvaluasiMingguan']) ? $data['pemeriksaanLaboratoriumAlbuminEvaluasiMingguan'] : '',
            'keteranganTambahan' => isset($data['keteranganTambahan']) ? $data['keteranganTambahan'] : '',
            'dokterSpesialisGizi' => isset($data['dokterSpesialisGizi']) ? $data['dokterSpesialisGizi'] : '',
        ];
        $qrcode = base64_encode(QrCode::format('svg')->size(75)->generate($pasien['tte']));
        $identitas = $pasien;
        $profile = $this->profile();
        $header_use = "head-emr";
        $blade = 'report.emr.' . $collection;
        if ($r['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView($blade, ['profile' => $profile, 'identitas' => $pasien, 'tte' => $qrcode, 'registrasi' => $data['registrasi'], 'data' => $data]);
            return $pdf->stream();
        }

        if (isset($r['storage']) && $r['storage']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.' . $collection,
                array(
                    'profile' => $profile,
                    'data' => $data,
                    'pasien' => $pasien,
                    'identitas' => $pasien,
                    'tte' => $qrcode,
                    'header_use' => $header_use . "-dom",
                )
            );
            return $pdf;
        }
        $registrasi = $data['registrasi'];
        return view('report.emr.' . $collection, compact('profile', 'data', 'pasien','identitas','registrasi', 'header_use','qrcode'));
    }
    public function cetakSuratKontrol(Request $request)
    {
        $noreg = $request['noregistrasi'];
        $norec = $request['norec'];
        $profile = DB::table('profile_m')->where('kdprofile', $this->kdProfile)->first();
        $kdJenisSurat = (int) $this->settingFix('SuratKeteranganKontrol', $profile->id);

        $pageWidth = 950;

        $identitas = collect(DB::select("
            SELECT
            sk.*,
            pg2.namalengkap as namapembuat,
            jp.jenispegawai as jabatan,
            pm.namapasien,
            pm.nocm,
            pm.noidentitas,
            pm.tempatlahir,
            pm.tgllahir,
            jk.jeniskelamin,
            pd.tglregistrasi,
            al.alamatlengkap,
            pj.pekerjaan,
            pg.namalengkap as dokterdpjp,
            pg.nosip
            FROM suratketerangan_t sk
            INNER JOIN pasiendaftar_t pd on pd.norec = sk.pasiendaftarfk
            INNER JOIN pasien_m pm on pm.id = pd.nocmfk
            LEFT JOIN jeniskelamin_m jk on pm.objectjeniskelaminfk = jk.id
            LEFT JOIN pekerjaan_m pj on pj.id = pm.objectpekerjaanfk
            LEFT JOIN alamat_m al on pm.id = al.nocmfk
            LEFT JOIN pegawai_m pg on pg.id = sk.dokterfk
            LEFT JOIN pegawai_m pg2 on pg2.id = sk.pegawaifk
            LEFT JOIN jenispegawai_m jp on jp.id = pg2.objectjenispegawaifk
            WHERE pd.noregistrasi = '$noreg'
            AND sk.norec = '$norec'
            AND jenissuratfk = '$kdJenisSurat'
            order by sk.tglsurat desc
        "))->first();

        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$identitas->dokterfk)
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
            'identitas' => $identitas,
            'ttdimg' => $img
        );
        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.emr.suratketerangankontrol',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.emr.suratketerangankontrol',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }
    public static function getUmurna($tgllahir, $tglregis)
    {
        $data = DB::select(DB::raw("
            SELECT
            EXTRACT (YEAR FROM AGE('$tglregis', '$tgllahir' )) || ' Tahun ' as thnumur,
            EXTRACT (MONTH  FROM AGE('$tglregis', '$tgllahir' )) || ' Bulan ' as blnumur,
            EXTRACT (DAY  FROM  AGE('$tglregis', '$tgllahir' )) || ' Hari' as hrumur
        "));
        $res['umurtahun'] = $data[0]->thnumur;
        $res['umurbulan'] = $data[0]->blnumur;
        $res['umurhari'] = $data[0]->hrumur;
        return $res;
    }

    public function cetakSPRI(Request $request)
    {
        $req = $request->all();

        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
            "))->first();

        $dataImg = DB::connection('mongodb')
            ->table('TandaTangan')
            ->where('pegawaifk', (int)$req['iddok'])
            ->where('statusenabled', true)
            ->where('kdprofile', (int) $this->kdProfile)
            ->first();

        $img = null;
        if (!empty($dataImg)) {
            $img = $dataImg['ttd'];
        }

        $pageWidth = 950;
        $dataReport = array(
            'datas' =>  $req,
            'ttdimg' => $img
        );
        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView(
            'report.registrasi.cetak-skdp',
            array(
                'dataReport' => $dataReport,
                'pageWidth' => $pageWidth,
                'profile' => $profile,
                'res' => array(
                    'pdf' => true
                ),
            )
        );

        return $pdf->stream();
        // return view('report.registrasi.cetak-skdp',
        //     compact('dataReport', 'pageWidth','profile'));
    }

    public function cetakResumeMedis(Request $request)
    {

        $profile = DB::table('profile_m')->where('kdprofile', $this->kdProfile)->first();

        $pageWidth = 950;

        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->alamatlengkap,
        );

        if ($request['pdf']) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'portrait');
            $pdf->loadView(
                'report.emr.resumemedis',
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.emr.resumemedis',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakRujukan(Request $request)
    {
        $req = $request->all();

        $profile = collect(DB::select("
            select * from profile_m where id = $this->kdProfile limit 1
            "))->first();



        $pageWidth = 950;
        $dataReport = array(
            'datas' =>  $req,
        );
        $dataReport['datas']['namappkRumahSakit'] = $this->settingFix('BPJS_namaPPKRujukan');

        $pdf = App::make('dompdf.wrapper');

        $pdf->loadView(
            'report.registrasi.cetak-rujukan',
            array(
                'dataReport' => $dataReport,
                'pageWidth' => $pageWidth,
                'profile' => $profile,
                'res' => array(
                    'pdf' => true
                ),
            )
        );

        return $pdf->stream();
    }


    public function getResep(Request $request)
    {
        $tindakanResep = DB::table('pelayananpasien_t as pp')
            ->leftjoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->leftjoin('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->leftjoin('strukresep_t as sr', function ($join) {
                $join->on('sr.norec', '=', 'pp.strukresepfk')
                    ->whereNull('sr.orderfk');
            })
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'pp.satuanresepfk')
            ->select(
                'prd.namaproduk',
                'prd.id as objectprodukfk',
                'pp.aturanpakai',
                'pp.dosis',
                'pp.tglpelayanan',
                'ru.namaruangan',
                'pp.strukresepfk',
                'sn.satuanresep',
                'pp.jumlah',
                DB::raw("
                (
                    (pp.hargasatuan  - case when pp.hargadiscount is null then 0 else pp.hargadiscount end)
                     * pp.jumlah)
                + (case when pp.jasa is not null then pp.jasa else 0 end)

                 as total
                 ,'Selesai' as status")
            )
            ->distinct()
            ->whereNull('pp.strukorderfk')
            ->where('pp.statusenabled', true)
            ->where('pp.kdprofile', $this->kdProfile)
            ->where('apd.noregistrasifk', $request['norec_pd'])
            ->orderByDesc('pp.tglpelayanan')
            ->get();

            $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
            $orderResep = DB::table('orderpelayanan_t as op')
                ->leftJoin('strukorder_t as so', 'so.norec', '=', 'op.strukorderfk')
                ->leftJoin('produk_m as prd', 'prd.id', '=', 'op.objectprodukfk')
                ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'op.objectruanganfk')
                ->select(
                    'prd.namaproduk',
                    'so.tglorder as tglpelayanan',
                    'ru.namaruangan',
                    'so.norec as strukresepfk',
                    'op.jumlah',
                    'op.dosis',
                    'op.aturanpakai',
                    DB::raw("

                    (
                        (op.hargasatuan  - case when op.hargadiscount is null then 0 else op.hargadiscount end)
                         * op.jumlah)

                     as total
                     ,'Pending' as status")
                )
                ->distinct()
                ->where('so.statusenabled', true)
                ->where('so.kdprofile', $this->kdProfile)
                ->where('so.noregistrasifk', $request['norec_pd'])
                ->where('so.objectkelompoktransaksifk',  $set->objectkelompoktransaksifk)
                ->where('so.statusorder', $this->settingFix('statusMenungguApotik'))
                ->orderByDesc('so.tglorder')
                ->get();

            $resep = [];
            foreach ($tindakanResep as $items) {
                if ($items->strukresepfk == null) {
                    $tindakan[] = $items;
                } else {
                    $resep[] = $items;
                }
            }
            foreach ($orderResep as $items) {
                $resep[] = $items;
            }

        return $this->respond($resep);
    }

    public function kirimWARujukan(Request $request)
    {
        try {
            // Validasi data wajib
            $requiredFields = ['noRujukan', 'tglRujukan', 'noKartu', 'nama', 'namaPpkDirujuk', 'namaPoliRujukan', 'kelamin', 'diagRujukan', 'namaDiagRujukan', 'catatan', 'namaTipeRujukan', 'jnsPelayanan'];
            foreach ($requiredFields as $field) {
                if (!isset($request[$field])) {
                    throw new \Exception("Field {$field} tidak ditemukan di request");
                }
            }

            // Ambil data pasien dari DB
            $dataOrder = DB::table('pasien_m as pas')
                ->select('pas.nohp', 'pas.tgllahir')
                ->where('pas.nobpjs', $request['noKartu'])
                ->first();

            if (!$dataOrder) {
                throw new \Exception("Data pasien tidak ditemukan");
            }

            $diagnosa = isset($request['diagRujukan'], $request['namaDiagRujukan']) 
            ? $request['diagRujukan'] . ' - ' . $request['namaDiagRujukan'] 
            : '';

            // Susun link dan pesan WA
            $otp = rand(100000, 999999);
            $date = date('Y-m-d');
            $Link           = "https://rsdgunungjati.com/service/emr/cetak-rujukan?noRujukan=" . $request['noRujukan'] . 
                            "&tglRujukan=" . $request['tglRujukan'] . 
                            "&noKartu=" . $request['noKartu'] . 
                            "&nama=" . $request['nama'] . 
                            "&tglLahir=" . $request['tglLahir'] . 
                            "&namaPpkDirujuk=" . $request['namaPpkDirujuk'] . 
                            "&poliTujuan=" . $request['namaPoliRujukan'] . 
                            "&sex=" . $request['kelamin'] . 
                            "&diagnosa=" . $diagnosa . 
                            "&catatan=" . $request['catatan'] . 
                            "&tiperujukan=" . $request['namaTipeRujukan'] . 
                            "&jnsPelayanan=" . $request['jnsPelayanan'] . 
                            "&tglRencanaKunjungan=" . $request['tglRencanaKunjungan'] . 
                            "&kdprofile=1&token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzUxMiJ9.eyJzdWIiOiJoaXMuaXQiLCJleHAiOjE3MzgwMjc0Njd9.qj-lKbcfehnAUXo2tcvfV_lJ8sUqwgaZi27qEY7fgmSW1HK5IPAwIy_B2pi0Gt2Yd_cPLq202kat78DNJNB0DA.MQ==";
            $finalLink      = str_replace(' ', '%20', $Link);

            $pesan = "Salam hormat,\nBerikut kami sampaikan surat rujukan dari RSD Gunung Jati Kota Cirebon tanggal {$date}, a/n pasien {$request['nama']} ({$request['noKartu']}). Silakan masukkan kode OTP berikut untuk mengakses hasil: *{$otp}*.\n\nDapat dilihat dengan cara klik link berikut ini :\n\n{$finalLink}\n\n*Jika link di atas tidak bisa diklik, silahkan simpan nomor ini terlebih dahulu dan forward pesan ini ke nomor sendiri!*\n\nTerima kasih,\nRSD Gunung Jati Kota Cirebon";

            $convertPhoneNumber = function ($phone) {
                if (strpos($phone, "0") === 0) {
                    return "62" . substr($phone, 1);
                }
                return $phone;
            };

            $phone = $dataOrder->nohp; // Gunakan default jika nohp tidak ditemukan
            $convertedPhone = $convertPhoneNumber($phone);

            $dataWA = [
                'phone'         => $convertedPhone,
                'isGroup'       => false,
                'isNewsletter'  => false,
                'message'       => $pesan,
            ];

            // Inisialisasi Guzzle Client
            $client = new Client();

            $response = $client->post('http://192.168.0.70/api/rsudgj3/send-message', [
                'headers' => [
                    'Authorization' => 'Bearer $2b$10$JiGVPk_DjqU6HBI.nxkjjemiQ6E5gRpL77ekRvqx9_wl8_4j2ND36',
                ],
                'json' => $dataWA,
            ]);

            $result = [
                "status" => 200,
                "message" => "Berhasil Kirim",
                "result" => json_decode($response->getBody(), true),
            ];
        } catch (\Exception $e) {
            $result = [
                "status" => 400,
                "message" => "Gagal Kirim",
                "result"  => $e->getMessage() . " di baris " . $e->getLine(),
            ];
        }

        return $this->respond($result);
    }
}
