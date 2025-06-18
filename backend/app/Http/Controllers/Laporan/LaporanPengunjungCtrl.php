<?php

namespace App\Http\Controllers\Laporan;

use App\Datatrans\PasienDaftar;
use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Master\SlottingKiosk;
use App\Models\Transaksi\LoggingUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Master\Profile;
use Illuminate\Support\Facades\App;

class LaporanPengunjungCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getLaporanPengunjungPemeriksaan(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftjoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftjoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftjoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftjoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderpenyelia',
                'mtrd.tglisilembarkerjapelaksana',
                'mtrd.pelaksanaisilembarkerjafk',
                'mtrd.tglverifasman',
                'prd.namaproduk',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mrk.id as idmerk',
                'mrk.namamerk',
                'tp.id as idtipe',
                'tp.namatipe',
                'sn.id as idsn',
                'sn.namaserialnumber',
                'mt.namaperusahaan',
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
                'mtrd.setujuilembarkerjapenyelia',
                'mtrd.tglsetujupenyelialembarkerja',
                'mtrd.penyeliasetujulembarkerjafk',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.tglsetujuasmanlembarkerja',
                'mtrd.asmansetujulembarkerjafk',
                'mtrd.statusorderasman',
                'mtrd.tglverifpelaksana',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(mtr.tglregistrasi AS DATE)"), $rangeDate);

        if (isset($request['search']) && $request['search'] != '') {
            $searchTerm = '%' . $request['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('lk.lokasi', 'ilike', $searchTerm)
                    ->orWhere('mrk.namamerk', 'ilike', $searchTerm)
                    ->orWhere('lp.lingkupkalibrasi', 'ilike', $searchTerm)
                    ->orWhere('mtr.nopendaftaran', 'ilike', $searchTerm)
                    ->orWhere('prd.namaproduk', 'ilike', $searchTerm);
            });
        }

        $data = $data->orderBy('mtr.tglregistrasi');
        $data = $data->get();

        return $this->respond([
            'data' => $data,
            'message' => 'ea@epic',
        ]);
    }

    public function getLaporanPenyakitTidakMenular(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jl', 'jl.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('alamat_m as al', 'ps.id', '=', 'al.nocmfk')
            ->leftJoin('pendidikan_m as pdk', 'pdk.id', '=', 'ps.objectpendidikanfk')
            ->leftJoin('pekerjaan_m as pk', 'pk.id', '=', 'ps.objectpekerjaanfk')
            ->leftJoin('statusperkawinan_m as spk', 'spk.id', '=', 'ps.objectstatusperkawinanfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->select(
                'pd.tglregistrasi',
                'ps.noidentitas as nik',
                'ps.namapasien',
                'ps.tgllahir',
                'jl.jeniskelamin',
                'al.alamatlengkap',
                'ps.nohp',
                'pdk.pendidikan',
                'pk.pekerjaan',
                'spk.statusperkawinan',
                DB::raw("dg.kddiagnosa || ' - ' || dg.namadiagnosa as diagnosa"),
                'pd.norec as norec_pd'
            )
            ->where('dp.kdprofile', '1')
            ->where('dp.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('ddp.objectjenisdiagnosafk', 1)
            ->whereIn('dg.kddiagnosa', [
                'I10',
                'I25.9',
                'I50.9',
                'E14.9',
                'E66.9',
                'E07.9',
                'I64',
                'J45.9',
                'M32.9',
                'D56.9',
                'J44.9',
                'M81.99',
                'N03.9',
                'D24',
                'C69.2',
                'C95.9',
                'C53.9'
            ])
            ->orderBy('pd.tglregistrasi', 'ASC');
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        $data =  $data->get();

        $norecPds = $data->pluck('norec_pd')->toArray();
        $vitalSigns = DB::connection('mongodb')
            ->table('VitalSign')
            ->whereIn('registrasi.norec_pd', $norecPds)
            ->get()
            ->keyBy('registrasi.norec_pd');

        $diagnosaSeconds = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'apd.norec')
            ->join('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->select('pd.norec as norec_pd', DB::raw("dg.kddiagnosa || ' - ' || dg.namadiagnosa as diagnosa"))
            ->where('dp.kdprofile', '1')
            ->where('dp.statusenabled', true)
            ->where('ddp.objectjenisdiagnosafk', 2)
            ->whereIn('pd.norec', $norecPds)
            ->get()
            ->groupBy('norec_pd');

        foreach ($data as $item) {
            $vitalSign = $vitalSigns->get($item->norec_pd);
            $item->imt = $vitalSign['IMT'] ?? null;
            $item->tekananDarah = $vitalSign['tekananDarah'] ?? null;
            $item->tinggiBadan = $vitalSign['tinggiBadan'] ?? null;
            $item->beratBadan = $vitalSign['beratBadan'] ?? null;
            $item->lingkarPerut = $vitalSign['lingkarPerut'] ?? null;

            $diagnosaList = $diagnosaSeconds->get($item->norec_pd) ?? collect();
            $item->diagnosa2 = $diagnosaList->count() > 0 ? $diagnosaList[0]->diagnosa : null;
            $item->diagnosa3 = $diagnosaList->count() > 1 ? $diagnosaList[1]->diagnosa : null;
        }

        $result = [
            'data' => $data,
            'message' => 'ea@epic',
        ];
        return $this->respond($result);
    }
    public function getLaporanDataPasienRehabMedik(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pelayananpasien_t as pp', 'pp.noregistrasi', '=', 'pd.noregistrasi')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ru.namaruangan',
                DB::raw('SUM(((pp.hargasatuan - pp.hargadiscount) * pp.jumlah) + COALESCE(pp.jasa, 0)) AS realcost'),
                'pd.inacbg_totalgrouper',
                'pg.namalengkap AS dokter',
                'kp.kelompokpasien',
                'kls.namakelas'
            )
            ->groupBy('pd.tglregistrasi', 'ps.namapasien', 'ru.namaruangan', 'pd.inacbg_totalgrouper', 'pg.namalengkap', 'kls.namakelas', 'kp.kelompokpasien', 'pd.noregistrasi', 'ps.nocm')
            ->whereIn('ru.id', [171, 172, 173, 167, 168, 169, 170])
            ->where('pp.statusenabled', true)
            ->where('pd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('pd.kdprofile', $this->kdProfile);

        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglregistrasi', 'ASC');
        $data =  $data->get();
        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanPengunjungStatus(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->join('ruangan_m as rg', 'rg.id', '=', 'apd.objectruanganfk')
            ->leftjoin('logginguser_t AS lg', function ($join) {
                $join->on('lg.noreff', '=', 'pd.norec')
                    ->on('lg.kdprofile', '=', 'pd.kdprofile')
                    ->where('lg.jenislog', '=', 'Pendaftaran Pasien');
            })
            ->leftJoin('loginuser_s AS lu', 'lu.id', '=', 'lg.objectloginuserfk')
            ->leftJoin('pegawai_m AS pg1', 'pg1.id', '=', 'lu.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'rg.namaruangan',
                DB::raw(
                    '
                    COUNT(CASE WHEN jk.id = 1 AND pd.statuspasien ILIKE \'%baru%\' THEN apd.norec END) AS lakibaru,
                    COUNT(CASE WHEN jk.id = 1 AND pd.statuspasien ILIKE \'%lama%\' THEN apd.norec END) AS lakilama,
                    COUNT(CASE WHEN jk.id = 2 AND pd.statuspasien ILIKE \'%baru%\' THEN apd.norec END) AS perempuanbaru,
                    COUNT(CASE WHEN jk.id = 2 AND pd.statuspasien ILIKE \'%lama%\' THEN apd.norec END) AS perempuanlama,
                    COUNT(apd.norec) as total'
                )
            )
            ->groupBy(
                'rg.namaruangan'
            )
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglregistrasi AS DATE)"), $rangeDate)
            ->where('rg.objectdepartemenfk', 18)
            ->where('pd.kdprofile', $this->kdProfile);

        $data = $data->orderBy('rg.namaruangan');
        $data =  $data->get();

        if ($request['isPDF'] == 'true') {
            $profile = Profile::where('id', $this->kdProfile)->first();
            $pageWidth = 950;
            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                'report.kunjungan.kunjungan-status',
                array(
                    'dataReport' => $data,
                    'pageWidth' => $pageWidth,
                    'profile' => $profile,
                    'periode' => \Carbon\Carbon::parse($request->tglAwal)->locale('id_ID')->isoFormat('D MMMM Y HH:mm') . " - " . \Carbon\Carbon::parse($request->tglAkhir)->locale('id_ID')->isoFormat('D MMMM Y HH:mm'),
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        }

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanPengunjungStatusKelompok(Request $request)
    {
        // $tglAwal = $request->tahun.'-'.$request->bulan.'-01 00:00:00';
        // $tglAkhir = $request->tahun.'-'.$request->bulan.'-31 23:59:59';
        // $rangeDate = [$tglAwal, $tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('ruangan_m as rg', 'rg.id', '=', 'apd.objectruanganfk')
            ->leftjoin('logginguser_t AS lg', function ($join) {
                $join->on('lg.noreff', '=', 'pd.norec')
                    ->on('lg.kdprofile', '=', 'pd.kdprofile')
                    ->where('lg.jenislog', '=', 'Pendaftaran Pasien');
            })
            ->leftJoin('loginuser_s AS lu', 'lu.id', '=', 'lg.objectloginuserfk')
            ->leftJoin('pegawai_m AS pg1', 'pg1.id', '=', 'lu.objectpegawaifk')
            ->select(
                'rg.namaruangan',
                DB::raw(
                    '
                    DATE_TRUNC(\'month\', apd.tglmasuk) AS groupedTanggal,
                    COUNT(CASE WHEN jk.id = 1 AND pd.statuspasien ILIKE \'%baru%\' THEN apd.norec END) AS lakibaru,
                    COUNT(CASE WHEN jk.id = 1 AND pd.statuspasien ILIKE \'%lama%\' THEN apd.norec END) AS lakilama,
                    COUNT(CASE WHEN jk.id = 2 AND pd.statuspasien ILIKE \'%baru%\' THEN apd.norec END) AS perempuanbaru,
                    COUNT(CASE WHEN jk.id = 2 AND pd.statuspasien ILIKE \'%lama%\' THEN apd.norec END) AS perempuanlama,
                    COUNT(apd.norec) as total
                    ',
                ),
            )
            ->groupBy(
                'rg.namaruangan',
                DB::raw('DATE_TRUNC(\'month\', apd.tglmasuk)')
            )
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereRaw('EXTRACT(MONTH FROM apd.tglregistrasi) = ?', [$request->bulan])
            ->whereRaw('EXTRACT(YEAR FROM apd.tglregistrasi) = ?', [$request->tahun])
            ->where('rg.objectdepartemenfk', 18)
            ->where('pd.kdprofile', $this->kdProfile);

        $data = $data->orderBy(DB::raw('DATE_TRUNC(\'month\', apd.tglmasuk)'));
        $data =  $data->get();

        $dataKelompok = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->leftjoin('ruangan_m as rg', 'rg.id', '=', 'apd.objectruanganfk')
            ->leftjoin('logginguser_t AS lg', function ($join) {
                $join->on('lg.noreff', '=', 'pd.norec')
                    ->on('lg.kdprofile', '=', 'pd.kdprofile')
                    ->where('lg.jenislog', '=', 'Pendaftaran Pasien');
            })
            ->leftJoin('loginuser_s AS lu', 'lu.id', '=', 'lg.objectloginuserfk')
            ->leftJoin('pegawai_m AS pg1', 'pg1.id', '=', 'lu.objectpegawaifk')
            ->leftJoin('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->select(
                'rg.namaruangan',
                'klp.kelompokpasien',
                DB::raw(
                    '
                    DATE_TRUNC(\'month\', apd.tglmasuk) AS groupedTanggal,
                    COUNT(CASE WHEN jk.id = 1 THEN apd.norec END) AS laki,
                    COUNT(CASE WHEN jk.id = 2 THEN apd.norec END) AS perempuan,
                    COUNT(apd.norec) as total
                    ',
                ),
            )
            ->groupBy(
                'rg.namaruangan',
                DB::raw('DATE_TRUNC(\'month\', apd.tglmasuk)'),
                'klp.kelompokpasien'
            )
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereRaw('EXTRACT(MONTH FROM apd.tglregistrasi) = ?', [$request->bulan])
            ->whereRaw('EXTRACT(YEAR FROM apd.tglregistrasi) = ?', [$request->tahun])
            ->where('rg.objectdepartemenfk', 18)
            ->where('pd.kdprofile', $this->kdProfile);

        $dataKelompok = $dataKelompok->orderBy(DB::raw('DATE_TRUNC(\'month\', apd.tglmasuk)'));
        $dataKelompok =  $dataKelompok->get();

        $ruangan = DB::table('ruangan_m as ru')
            ->select('namaruangan')
            ->join('pasiendaftar_t as pd', 'pd.objectruanganlastfk', 'ru.id')
            ->whereRaw('EXTRACT(MONTH FROM pd.tglregistrasi) = ?', [$request->bulan])
            ->whereRaw('EXTRACT(YEAR FROM pd.tglregistrasi) = ?', [$request->tahun])
            ->where('ru.statusenabled', true)
            ->where('ru.objectdepartemenfk', 18)
            ->distinct('namaruangan')
            ->get();
        $kelompok = DB::table('kelompokpasien_m as klp')
            ->select('kelompokpasien')
            ->join('pasiendaftar_t as pd', 'pd.objectkelompokpasienlastfk', 'klp.id')
            ->whereRaw('EXTRACT(MONTH FROM pd.tglregistrasi) = ?', [$request->bulan])
            ->whereRaw('EXTRACT(YEAR FROM pd.tglregistrasi) = ?', [$request->tahun])
            ->where('klp.statusenabled', true)
            ->distinct('kelompokpasien')
            ->get();
        $responseTanggal = [];
        $lastDate = '';
        foreach ($data as $item) {
            if ($item->groupedtanggal != $lastDate) {
                $responseTanggal[] = $item->groupedtanggal;
            }
            $lastDate = $item->groupedtanggal;
        }

        $response = [];
        foreach ($responseTanggal as $tanggal) {
            foreach ($ruangan as $item) {
                $responseItem = new \stdClass();
                $responseItem->tanggal = $tanggal;
                $responseItem->ruangan = $item->namaruangan;

                $total = 0;
                foreach ($kelompok as $row) {
                    $responseItem->{$row->kelompokpasien . "laki"} = 0;
                    $responseItem->{$row->kelompokpasien . "perempuan"} = 0;
                    foreach ($dataKelompok as $itemKel) {
                        if ($itemKel->kelompokpasien == $row->kelompokpasien && $itemKel->namaruangan == $item->namaruangan) {
                            $responseItem->{$itemKel->kelompokpasien . "laki"} = $itemKel->laki;
                            $responseItem->{$itemKel->kelompokpasien . "perempuan"} = $itemKel->perempuan;
                            $total += $itemKel->total;
                        }
                    }
                }
                $responseItem->kelompokTotal = $total;

                foreach ($data as $status) {
                    if ($status->namaruangan == $item->namaruangan) {
                        $responseItem->lakibaru = $status->lakibaru;
                        $responseItem->lakilama = $status->lakilama;
                        $responseItem->perempuanbaru = $status->perempuanbaru;
                        $responseItem->perempuanlama = $status->perempuanlama;
                        $responseItem->statusTotal = $status->total;
                    }
                }
                $response[] = $responseItem;
            }
        }

        if ($request['isPDF'] == 'true') {
            $profile = Profile::where('id', $this->kdProfile)->first();
            $pdf = App::make('dompdf.wrapper');
            $pdf->setPaper('A4', 'landscape');
            $pdf->getDomPDF()->set_option('margin-top', 20);
            $pdf->getDomPDF()->set_option('page-break-before', 'always');
            $pdf->getDomPDF()->set_option('page-break-after', 'always');
            $pdf->loadView(
                'report.kunjungan.kunjungan-status-kelompok',
                array(
                    'dataReport' => $response,
                    'profile' => $profile,
                    'kelompok' => $kelompok,
                    'periode' => \Carbon\Carbon::parse($request->tahun . '-' . $request->bulan . '-01')->locale('id_ID')->isoFormat('MMMM') . " " . \Carbon\Carbon::parse($request->tahun . '-' . $request->bulan . '-01')->locale('id_ID')->isoFormat('YYYY'),
                    'res' => array(
                        'pdf' => true
                    ),
                )
            );
            return $pdf->stream();
        }

        $result = array(
            'data' => $response,
            'kelompok' => $kelompok,
            'message' => '',
        );
        return $this->respond($result);
    }

    public function getDaftarPasienRawatInap(Request $request)
    {
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as rm1', 'rm1.id', '=', 'pd.objectruanganasalfk')
            ->join('kamar_m as km', 'km.id', '=', 'apd.objectkamarfk')
            ->join('tempattidur_m as tp', 'tp.id', '=', 'apd.nobed')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                'ps.tgllahir',
                'pd.tglregistrasi as tglmasuk',
                'kp.kelompokpasien as jenis_pasien',
                'rm.namaruangan as namaruanganrawat',
                'rm1.namaruangan as namaruanganasal',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                'km.namakamar',
                DB::raw("EXTRACT(DAY FROM ((CASE WHEN pd.tglpulang IS NULL THEN CURRENT_TIMESTAMP ELSE pd.tglpulang END) - pd.tglregistrasi)) || ' Hari' AS lamarawat"),
                'tp.reportdisplay as bed',
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                'pd.statuspasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('apd.tglkeluar', null)
            ->where('pd.tglpulang', null)
            ->where('rm.objectdepartemenfk', '=', 16);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rg.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('klp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('rm.namaruangan');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanPengunjungTindakan(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $idProfile = (int) $kdProfile;

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', function ($j) {
                $j->on('apd.noregistrasifk', '=', 'pd.norec')
                    ->on('apd.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftJoin('pelayananpasien_t as pp', function ($j) {
                $j->on('pp.noregistrasifk', '=', 'apd.norec')
                    ->on('pp.kdprofile', '=', 'apd.kdprofile')
                    ->on('apd.objectruanganfk', '=', 'pd.objectruanganlastfk')
                    ->on('pd.kdprofile', '=', 'apd.kdprofile');
            })
            ->join('pasien_m as ps', function ($j) {
                $j->on('ps.id', '=', 'pd.nocmfk')
                    ->on('ps.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('jeniskelamin_m as jk', function ($j) {
                $j->on('jk.id', '=', 'ps.objectjeniskelaminfk')
                    ->on('jk.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('agama_m as ag', function ($j) {
                $j->on('ag.id', '=', 'ps.objectagamafk')
                    ->on('ag.kdprofile', '=', 'ps.kdprofile');
            })
            ->leftjoin('pendidikan_m as pdd', function ($j) {
                $j->on('pdd.id', '=', 'ps.objectpendidikanfk')
                    ->on('pdd.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('pekerjaan_m as pkr', function ($j) {
                $j->on('pkr.id', '=', 'ps.objectpekerjaanfk')
                    ->on('pkr.kdprofile', '=', 'ps.kdprofile');
            })
            ->join('alamat_m as alm', function ($j) {
                $j->on('alm.nocmfk', '=', 'ps.id')
                    ->on('ps.kdprofile', '=', 'alm.kdprofile');
            })
            ->join('desakelurahan_m as dsk', function ($j) {
                $j->on('dsk.id', '=', 'alm.objectdesakelurahanfk')
                    ->on('dsk.kdprofile', '=', 'alm.kdprofile');
            })
            ->join('kotakabupaten_m as kkb', function ($j) {
                $j->on('kkb.id', '=', 'alm.objectkotakabupatenfk')
                    ->on('kkb.kdprofile', '=', 'alm.kdprofile');
            })
            ->leftjoin('statusperkawinan_m as sp', function ($j) {
                $j->on('sp.id', '=', 'ps.objectstatusperkawinanfk')
                    ->on('sp.kdprofile', '=', 'ps.kdprofile');
            })
            ->leftjoin('pegawai_m as pg', function ($j) {
                $j->on('pg.id', '=', 'pd.objectdokterpemeriksafk')
                    ->on('pg.kdprofile', '=', 'pd.kdprofile');
            })
            ->join('ruangan_m as rg', function ($j) {
                $j->on('rg.id', '=', 'pd.objectruanganlastfk')
                    ->on('rg.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftjoin('diagnosapasien_t as dp', function ($j) {
                $j->on('dp.noregistrasifk', '=', 'apd.norec')
                    ->on('dp.kdprofile', '=', 'apd.kdprofile');
            })
            ->leftJoin('kelompokpasien_m as klp', function ($j) {
                $j->on('klp.id', '=', 'pd.objectkelompokpasienlastfk')
                    ->on('klp.kdprofile', '=', 'pd.kdprofile');
            })
            ->leftJoin('detaildiagnosapasien_t as ddp', function ($j) {
                $j->on('dp.norec', '=', 'ddp.objectdiagnosapasienfk')
                    ->on('dp.kdprofile', '=', 'ddp.kdprofile');
            })
            ->join('diagnosa_m as dg', function ($j) {
                $j->on('ddp.objectdiagnosafk', '=', 'dg.id')
                    ->on('ddp.kdprofile', '=', 'dg.kdprofile');
            })
            ->leftJoin('produk_m as pro', function ($j) {
                $j->on('pro.id', '=', 'pp.produkfk')
                    ->on('pro.kdprofile', '=', 'pp.kdprofile');
            })
            ->leftJoin('detailjenisproduk_m as djp', function ($j) {
                $j->on('djp.id', '=', 'pro.objectdetailjenisprodukfk')
                    ->on('djp.kdprofile', '=', 'pro.kdprofile');
            })
            ->select(
                'pd.norec',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'ag.agama',
                'pdd.pendidikan',
                'pkr.pekerjaan',
                'alm.alamatlengkap',
                'dsk.namadesakelurahan',
                'alm.kecamatan',
                'kkb.namakotakabupaten',
                'sp.statusperkawinan',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'djp.detailjenisproduk',
                'pro.namaproduk',
                DB::raw('EXTRACT(YEAR FROM AGE(ps.tgllahir)) AS tahun'),
                DB::raw('EXTRACT(MONTH FROM AGE(ps.tgllahir)) AS bulan'),
                DB::raw('EXTRACT(DAY FROM AGE(ps.tgllahir)) AS hari')
            )
            ->groupBy(
                'pd.norec',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'ag.agama',
                'pdd.pendidikan',
                'pkr.pekerjaan',
                'alm.alamatlengkap',
                'dsk.namadesakelurahan',
                'alm.kecamatan',
                'kkb.namakotakabupaten',
                'sp.statusperkawinan',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'djp.detailjenisproduk',
                'pro.namaproduk'
            )
            ->where('pd.statusenabled', true)
            ->where('pd.kdprofile', $idProfile)
            ->where('ddp.objectjenisdiagnosafk', 1)
            ->whereNotIn('djp.id', [
                1405,
                1406,
                1407,
                1408,
                1409,
                1587,
                1588,
                1589,
                1590,
                1591,
                1592,
                1593,
                1594,
                1595,
                1596,
                1597,
                1598,
                1599,
                1600,
                1601,
                1346,
                1347,
                1348,
                1349,
                1350,
                1351,
                1352,
                1353,
                1354,
                1355,
                1356,
                1357,
                1358,
                1359,
                1360,
                1361,
                1362,
                1363,
                1364,
                1365,
                1366,
                1367,
                1368,
                1369,
                1370,
                1371,
                1372,
                1373,
                1374,
                1375,
                1376,
                1377,
                1378,
                1379,
                1380,
                1381,
                1382,
                1383,
                1384,
                1385,
                1386,
                1387,
                1388,
                1389,
                1390,
                1391,
                1392,
                1393,
                1394,
                1395,
                1396,
                1397,
                1398,
                1399,
                1400,
                1401,
                1402,
                1403,
                474
            ])
            ->whereIn('pro.id', [
                6717117,
                1002130905,
                6712418,
                6713354,
                6713363,
                6713365,
                6713376,
            ]);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data->where('pd.tglregistrasi', '<=', $request['tglAkhir']);
        }
        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data->where('rg.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data->where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data->where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kotaKab']) && $request['kotaKab'] != "" && $request['kotaKab'] != "undefined") {
            $data->where('kkb.id', '=', $request['kotaKab']);
        }

        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );

        return $this->respond($result);
    }

    public function getLaporanPenyerahanObat(Request $request)
    {
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $kdProfile = (int) $this->kdProfile;

        $data = DB::table('strukresep_t as sr')
            ->leftJoin('antrianapotik_t as aa', 'aa.noresep', '=', 'sr.noresep')
            ->leftJoin('strukorder_t as so', 'so.norec', '=', 'sr.orderfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('alamat_m as alm', 'alm.nocmfk', '=', 'pm.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'sr.ruanganfk')
            ->leftJoin('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruanganfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->where('sr.kdprofile', $kdProfile)
            ->whereBetween('sr.tglresep', [$tglAwal, $tglAkhir])
            ->whereNotNull('aa.noantri')
            ->select(
                'so.noorder',
                'sr.noresep',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'jk.jeniskelamin',
                'so.tglorder',
                'sr.tglresep as tglverifikasi',
                DB::raw("CONCAT(aa.jenis,'-', aa.noantri) AS noantri"),
                'so.namapengambilorder',
                'so.tglambilorder',
                'ru.namaruangan as namaruanganapotik',
                'kp.kelompokpasien',
                'so.keterangankeperluan',
                'so.cito',
                'so.isreseppulang as checkreseppulang',
                'ru2.namaruangan AS namaruanganrawat'
            );

        if (isset($request['jeniskemasan']) && $request['jeniskemasan'] != "" && $request['jeniskemasan'] != "undefined") {
            if ($request['jeniskemasan'] == 1) {
                $data = $data->where('aa.jenis', 'R');
            } else {
                $data = $data->where('aa.jenis', 'N');
            }
        }

        if (isset($request['IdFarmasi']) && $request['IdFarmasi'] != "" && $request['IdFarmasi'] != "undefined") {
            $data = $data->where('sr.ruanganfk', $request['IdFarmasi']);
        }

        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }


    public function getDaftarReturPenerimaanSuplierDetail(Request $request)
    {

        $data = DB::table('strukretur_t as sr')
            ->leftJOIN('strukpelayanan_t as sp', 'sp.norec', '=', 'sr.strukterimafk')
            ->LEFTJOIN('strukreturdetail_t as srd', 'srd.strukreturfk', '=', 'sr.norec')
            ->LEFTJOIN('produk_m as pr', 'pr.id', '=', 'srd.objectprodukfk')
            ->LEFTJOIN('satuanstandar_m as ss', 'ss.id', '=', 'pr.objectsatuanstandarfk')
            ->LEFTJOIN('rekanan_m as rkn', 'rkn.id', '=', 'sp.objectrekananfk')
            ->LEFTJOIN('pegawai_m as pg', 'pg.id', '=', 'sp.objectpegawaipenerimafk')
            ->LEFTJOIN('pegawai_m as pg1', 'pg1.id', '=', 'sr.objectpegawaifk')
            ->LEFTJOIN('ruangan_m as ru', 'ru.id', '=', 'sp.objectruanganfk')
            ->LEFTJOIN('strukbuktipengeluaran_t as sbk', 'sbk.norec', '=', 'sp.nosbklastfk')
            ->select(DB::raw("sr.norec,sr.tglretur,sr.noretur,sp.nostruk,sp.nosppb,sp.nokontrak,sp.nofaktur,sp.tglfaktur,
                                    sp.objectruanganfk,ru.namaruangan,rkn.namarekanan,pg.namalengkap,pg1.namalengkap as pegawairetur,
                                    pr.namaproduk,ss.satuanstandar,srd.qtyproduk,srd.harganetto1,srd.hargadiscount,
							        ((srd.harganetto1-srd.hargadiscount)*srd.qtyproduk) as total,srd.tglkadaluarsa,srd.nobatch"))
            ->where('sr.kdprofile', $this->kdProfile);

        // if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
        //     $data = $data->where('sr.tglretur', '>=', $request['tglAwal']);
        // }
        // if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
        //     $tgl = $request['tglAkhir'];
        //     $data = $data->where('sr.tglretur', '<=', $tgl);
        // }
        // if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
        //     $data = $data->where('sp.nostruk', 'ILIKE', '%' . $request['nostruk']);
        // }
        // if (isset($request['namarekanan']) && $request['namarekanan'] != "" && $request['namarekanan'] != "undefined") {
        //     $data = $data->where('rkn.namarekanan', 'ILIKE', '%' . $request['namarekanan'] . '%');
        // }
        // if (isset($request['nofaktur']) && $request['nofaktur'] != "" && $request['nofaktur'] != "undefined") {
        //     $data = $data->where('sp.nofaktur', 'ILIKE', '%' . $request['nofaktur'] . '%');
        // }
        // if (isset($request['produkfk']) && $request['produkfk'] != "" && $request['produkfk'] != "undefined") {
        //     $data = $data->where('srd.objectprodukfk', '=', $request['produkfk']);
        // }
        // if (isset($request['noSppb']) && $request['noSppb'] != "" && $request['noSppb'] != "undefined") {
        //     $data = $data->where('sp.nosppb', 'ILIKE', '%' . $request['noSppb'] . '%');
        // }
        // if (isset($request['noretur']) && $request['noretur'] != "" && $request['noretur'] != "undefined") {
        //     $data = $data->where('sr.noretur', 'ILIKE', '%' . $request['noretur'] . '%');
        // }

        //        $data = $data->wherein('sp.objectruanganfk',$strRuangan);
        $data = $data->where('sr.statusenabled', true);
        $data = $data->where('sr.objectkelompoktransaksifk', 9);
        $data = $data->orderBy('sr.noretur');
        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );
        return $this->respond($result);
    }

    public function getDaftarReturObatDetail(Request $request)
    {

        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('strukretur_t as srt')
            ->leftJoin('strukresep_t as sr', 'sr.norec', '=', 'srt.strukresepfk')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'sr.pasienfk')
            ->leftJoin('strukpelayanan_t as sp', 'sp.norec', '=', 'srt.strukresepfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->leftJoin('pelayananpasienretur_t as spd', 'spd.strukreturfk', '=', 'srt.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'spd.produkfk')
            ->join('jeniskemasan_m as jkm', 'jkm.id', '=', 'spd.jeniskemasanfk')
            ->join('satuanstandar_m as ss', 'ss.id', '=', 'spd.satuanviewfk')
            ->leftJoin('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'srt.objectpegawaifk')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'srt.objectruanganfk')
            ->leftJoin('ruangan_m as ru1', 'ru1.id', '=', 'apd.objectruanganfk')
            ->select(DB::raw("srt.tglretur,srt.noretur,CASE WHEN pd.noregistrasi IS NULL THEN '-' ELSE pd.noregistrasi END AS noregistrasi,
                    CASE WHEN ps.nocm IS NULL THEN sp.nostruk_intern ELSE ps.nocm END AS nocm,
                    CASE WHEN ps.namapasien IS NULL THEN sp.namapasien_klien ELSE ps.namapasien END AS namapasien,
                    CASE WHEN ru1.namaruangan IS NULL THEN '-' ELSE ru1.namaruangan END as unitlayanan,
                    ps.namapasien,pg.namalengkap,ru.namaruangan AS depo,srt.norec,srt.keteranganlainnya,
                    spd.tglpelayanan, spd.rke,jkm.jeniskemasan,pr.namaproduk,ss.satuanstandar,spd.jumlah,spd.hargasatuan,
                    spd.hargadiscount,spd.jasa,((spd.hargasatuan-spd.hargadiscount)*spd.jumlah)+spd.jasa as total"))
            ->where('srt.kdprofile', $this->kdProfile)
            ->where('srt.statusenabled', true)
            ->whereBetween(DB::raw("CAST(srt.tglretur as Date)"), $rangeDate);

        if (isset($request['nostruk']) && $request['nostruk'] != "" && $request['nostruk'] != "undefined") {
            $data = $data->where('srt.noretur', 'ilike', '%' . $request['nostruk']);
        }

        if (isset($request['idRuangLayanan']) && $request['idRuangLayanan'] != "" && $request['idRuangLayanan'] != "undefined") {
            $data = $data->where('ru1.id', '=', $request['idRuangLayanan']);
        }

        $data = $data->orderBy('srt.noretur');
        $data = $data->get();

        $result = array(
            'daftar' => $data,
            'message' => 'as@epic',
        );

        return $this->respond($result);
    }
    public function getLaporanAntrianKuotaPoli(Request $request)
    {
        $date = $request->date;
        $kdProfile = $this->kdProfile;
        $set = explode(',', $this->settingFix('kdDepartemenKiosk', $kdProfile));
        $dp = [];
        foreach ($set as $it) {
            $dp[] =  (int)$it;
        };

        $kuotaPoli = DB::table('slottingkiosk_m as sk')
            ->join('ruangan_m as ru', 'ru.id', 'sk.objectruanganfk',)
            ->selectRaw("ru.namaruangan,sk.objectruanganfk,sk.quota,sk.quotafix,0 as terpakai,
                                0 as batal, 0 as sisa, 0 as bersedia, 0 as mjkn, 0 as kiosk")
            ->where('sk.statusenabled', true)
            ->where('sk.tanggal', $date)
            ->where('sk.kdprofile', $this->kdProfile);
        if (isset($request['ruanganfk'])) {
            $kuotaPoli =  $kuotaPoli->where('sk.objectruanganfk', $request['ruanganfk']);
        }
        $kuotaPoli = $kuotaPoli->get();

        $data = DB::table('antrianpasienregistrasi_t as apr')
            ->join('ruangan_m as ru', 'ru.id', 'apr.objectruanganfk')
            ->join('slottingkiosk_m as sk', 'sk.objectruanganfk', 'ru.id')
            ->select(
                'ru.namaruangan',
                'apr.objectruanganfk',
                'sk.quota',
                'sk.quotafix',
                DB::raw(
                    "COUNT(apr.objectruanganfk) as terpakai,
                     CASE WHEN(sk.quotafix - COUNT(apr.objectruanganfk)) < 0 THEN 0
                     ELSE (sk.quotafix - COUNT(apr.objectruanganfk)) END as sisa,
                     COUNT(CASE WHEN apr.statusenabled = 'f' THEN 1 END) as batal,
                     COUNT(CASE WHEN apr.statusenabled = 't' THEN 1 END) as bersedia,
                     COUNT(CASE WHEN apr.ismobilejkn = 't' THEN 1 END) as mjkn,
                     COUNT(CASE WHEN apr.iskiosk = 't' THEN 1 END) as kiosk
                    "
                )
            )
            ->whereRaw("to_char(apr.tanggalreservasi, 'yyyy-MM-dd') = '$date'")
            ->where('sk.tanggal', $date)
            ->where('sk.statusenabled', true)
            ->whereNotNull('apr.jenis')
            ->where('apr.kdprofile', $kdProfile);
        if (isset($request['ruanganfk'])) {
            $data =  $data->where('apr.objectruanganfk', $request['ruanganfk']);
        }
        $data = $data->groupBy('apr.objectruanganfk', 'ru.namaruangan', 'sk.quota', 'sk.quotafix');
        $data = $data->get();
        foreach ($kuotaPoli as $item) {
            $found = false;
            foreach ($data as $dat) {
                if ($item->objectruanganfk == $dat->objectruanganfk) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $data[] = $item;
            }
        }
        // $data = DB::table('pasiendaftar_t as pd')
        //     ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
        //     ->join('slottingkiosk_m as sk', 'sk.objectruanganfk', 'ru.id')
        //     ->select(
        //         'ru.namaruangan',
        //         'pd.objectruanganlastfk',
        //         'sk.quota',
        //         'sk.quotafix',
        //         DB::raw(
        //             "COUNT(pd.objectruanganlastfk) as terpakai,
        //              CASE WHEN(sk.quotafix - COUNT(pd.objectruanganlastfk)) < 0 THEN 0
        //              ELSE (sk.quotafix - COUNT(pd.objectruanganlastfk)) END as sisa,
        //              COUNT(CASE WHEN pd.statusenabled = 'f' THEN 1 END) as batal,
        //              COUNT(CASE WHEN pd.statusenabled = 't' THEN 1 END) as bersedia,
        //              COUNT(CASE WHEN pd.ismobilejkn = 't' THEN 1 END) as reservasi,
        //              COUNT(CASE WHEN pd.ismobilejkn is null THEN 1 END) as langsung
        //             "
        //         )
        //     )
        //     ->whereRaw("to_char(pd.tglregistrasi, 'yyyy-MM-dd') = '$date'")
        //     ->where('sk.tanggal', $date)
        //     ->where('sk.statusenabled', true)
        //     ->where('pd.kdprofile', $kdProfile)
        //     ->groupBy('pd.objectruanganlastfk', 'ru.namaruangan', 'sk.quota', 'sk.quotafix');

        // $result = $data1->union($data)->get();
        // return $result;
        // if (isset($request['ruanganfk'])) {
        //     $data =  $data->where('ru.id', $request['ruanganfk']);
        // }
        // $data = $data->groupBy('pd.objectruanganlastfk', 'ru.namaruangan', 'sk.quota', 'sk.quotafix');
        // $data = $data->get();

        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';

        $loggings = DB::table('logginguser_t')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->where('jenislog', 'Simpan Slotting Kios')
            ->select('namapegawai', 'tanggal', 'keterangan')
            ->get();

        $logging = $loggings->map(function ($log) {
            $decode = json_decode($log->keterangan, true);
            return [
                'namapegawai' => $log->namapegawai,
                'tanggal' => $log->tanggal,
                'namarungan' => $decode['namaRuangan'] ?? null,
                'konvensionalold' => $decode['old'] ?? null,
                'konvensiolanNew' => $decode['konvensionalNew'] ?? null,
            ];
        });
        $data = [
            'data' => $data,
            'loggings' => $logging
        ];
        return $this->respond($data);
    }

    public function getRuanganPoli()
    {
        $data = Ruangan::select('namaruangan', 'id')->where('kdprofile', $this->kdProfile)
            ->where('objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRawatJalanFix')))
            ->get();
        return $this->respond($data);
    }

    public function informasiAntrianPasienAntrol(Request $request)
    {
        $tglAwal = Carbon::parse($request->tglAwal)->startOfDay();
        $tglAkhir = Carbon::parse($request->tglAkhir)->endOfDay();
        $objectpegawaifk = $request->objectpegawaifk;
        $search   = $request->q;
        $pasien = DB::table('antrianpasienregistrasi_t AS ar')->where('ismobilejkn', true)->where('ar.statusenabled', true)
            ->join('pasien_m as ps', 'ar.nocmfk', 'ps.id')
            ->leftJoin('jeniskelamin_m AS jk', 'jk.id', '=', 'ar.objectjeniskelaminfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'ar.objectpegawaifk')
            ->leftJoin('ruangan_m as rm', 'rm.id', '=', 'ar.objectruanganfk')
            ->select(
                'ps.namapasien',
                'ps.tgllahir',
                'ar.alamatlengkap AS alamatlengkap',
                'pg.namalengkap AS dokter',
                'pg.id',
                'ar.noreservasi AS kodebooking',
                'ps.nocm AS nocm',
                'ps.nobpjs AS nobpjs',
                'ps.noidentitas AS nik',
                'ar.tanggalreservasi as tanggalreservasi',
                'jk.jeniskelamin'
            )
            ->when($search, function ($query) use ($search) {
                $lowercaseSearch = strtolower($search);
                return $query->whereRaw('LOWER(ps.namapasien) like ?', ['%' . $lowercaseSearch . '%']);
            })
            ->when($objectpegawaifk, function ($query) use ($objectpegawaifk) {
                return $query->where('pg.id', $objectpegawaifk);
            })
            ->when($tglAwal && $tglAkhir, function ($query) use ($tglAwal, $tglAkhir) {
                return $query->whereBetween('ar.tanggalreservasi', [$tglAwal, $tglAkhir]);
            });
        $kuota = DB::table('antrianpasienregistrasi_t AS ar')
            ->where('ismobilejkn', true)
            ->where('ar.statusenabled', true)
            ->leftJoin('ruangan_m as rm', 'rm.id', '=', 'ar.objectruanganfk')
            ->groupBy('rm.id')
            ->when($tglAwal && $tglAkhir, function ($query) use ($tglAwal, $tglAkhir) {
                return $query->whereBetween('ar.tanggalreservasi', [$tglAwal, $tglAkhir]);
            })
            ->select('rm.namaruangan AS nama_ruangan', DB::raw('count(*) as jumlah_pasien'))
            ->get();
        $data = [
            'pasien' => $pasien->get(),
            'kuota' => $kuota,
        ];
        return $this->respond($data);
    }

    public function getLaporanIndexingRI(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('kondisipasien_m as kd', 'kd.id', '=', 'pd.objectkondisipasienfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->leftjoin('diagnosapasien_t as dg', 'dg.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('detaildiagnosapasien_t as ddg', 'ddg.objectdiagnosapasienfk', '=', 'dg.norec')
            ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddg.objectjenisdiagnosafk')
            ->leftjoin('diagnosa_m as dm', 'dm.id', '=', 'ddg.objectdiagnosafk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                'dg.ketdiagnosis',
                'ps.tgllahir',
                'pd.tglregistrasi as tglmasuk',
                'sp.statuspulang',
                'pd.tglpulang',
                'kd.kondisipasien as kondisipulang',
                'kp.kelompokpasien as jenis_pasien',
                'rm.namaruangan as namaruanganrawat',
                'ru.namaruangan as namaruanganasal',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                DB::raw("EXTRACT(DAY FROM ((CASE WHEN pd.tglpulang IS NULL THEN CURRENT_TIMESTAMP ELSE pd.tglpulang END) - pd.tglregistrasi)) || ' Hari' AS lamarawat"),
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                'jd.jenisdiagnosa',
                'dm.kddiagnosa as kodediagnosa',
                'dm.namadiagnosa as namadiagnosa',
                'pd.statuspasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.tglpulang', '!=', null)
            ->where('rm.objectdepartemenfk', '=', 16)
            ->whereBetween(DB::raw("CAST(pd.tglpulang AS DATE)"), $rangeDate);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rm.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglpulang');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getRuanganRanap()
    {
        $ruangan = DB::table('maploginusertoruangan_s as mlur')
            ->join('loginuser_s as lu', function ($j) {
                $j->on('lu.id', 'mlur.objectloginuserfk')->on('lu.kdprofile', 'mlur.kdprofile');
            })
            ->join('ruangan_m as ru', function ($j) {
                $j->on('ru.id', 'mlur.objectruanganfk')->on('ru.kdprofile', 'mlur.kdprofile');
            })
            ->select('ru.id', 'ru.namaruangan')
            ->where('mlur.statusenabled', true)
            ->where('lu.kdprofile', $this->kdProfile)
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepRawatInap'))
            ->where('ru.statusenabled', true)
            ->where('lu.id', $this->getUserId())
            ->groupBy('ru.id', 'ru.namaruangan')
            ->get();

        $ruanganTersedia = [];
        foreach ($ruangan as $ru) {
            $ruanganTersedia[] = $ru->id;
        }

        return $ruanganTersedia;
    }

    public function getLaporanInfeksiPPI(Request $request)
    {
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");

        $data = DB::table('pasiendaftar_t as pd')
            ->join('kelas_m as kls', 'kls.id', 'pd.objectkelasfk')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->leftJoin('pegawai_m as pg', 'pd.objectpegawaifk', '=', 'pg.id')
            ->leftJoin('pasien_m as ps', 'pd.nocmfk', '=', 'ps.id')
            ->Join('transaksiinfeksippi_t as tip', 'tip.noregistrasifk', '=', 'pd.norec')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'tip.objectinfeksippifk')
            ->leftjoin('diagnosapasien_t as dg', 'dg.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('detaildiagnosapasien_t as ddg', 'ddg.objectdiagnosapasienfk', '=', 'dg.norec')
            ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddg.objectjenisdiagnosafk')
            ->leftJoin('diagnosa_m as dm', function ($join) {
                $join->on('dm.id', '=', 'ddg.objectdiagnosafk')
                    ->whereNotNull('dm.penularan');
            })
            ->select(
                DB::raw("
                    ifp.namainfeksippi,
                    dm.namadiagnosa,
                    dm.id as kddiagnosa,
                    dm.penularan,
                    tip.tanggalinput as tanggalinputinfeksi,
                    apd.nobed,
                    ru.id,
                    apd.tglmasuk,
                    ps.nohp,
                    pg.namalengkap as dokter,
                    ru.statusenabled,
                    ru.namaruangan,
                    kls.namakelas,
                    pd.noregistrasi,
                    pg.namalengkap,
                    ps.namapasien,
                    ps.nocm,
                    pd.tglregistrasi
                    ")
            )
            ->where('pd.kdprofile', $this->kdProfile)
            // ->whereIn('ru.objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->whereIn('ru.objectdepartemenfk', array_merge(
                explode(',', $this->settingFix('kdDepartemenRanapFix')),
                explode(',', $this->settingFix('kdDepartemenRawatJalanFix'))
            ))
            ->where('pd.statusenabled', 't')
            ->where('apd.statusenabled', 't')
            // ->whereNull('apd.tglkeluar')
            // ->whereNull('pd.tglpulang')
            ->where('apd.kdprofile', $this->kdProfile)
            ->orderBy('pd.noregistrasi', 'DESC')
            ->orderBy('pd.tglregistrasi', 'DESC');

        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['dari']) && $request['dari'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '>=', $request->dari);
        }
        if (isset($request['sampai']) && $request['sampai'] != '') {
            $data = $data->where(DB::raw("apd.tglmasuk::date"), '<=', $request->sampai);
        }

        $data = $data->distinct('pd.noregistrasi');
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanTindakanRI(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('kondisipasien_m as kd', 'kd.id', '=', 'pd.objectkondisipasienfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->leftJoin('detaildiagnosatindakanpasien_t as ddt', 'pd.noregistrasi', 'ddt.noregistrasi')
            ->leftJoin('diagnosatindakan_m as dt', 'ddt.objectdiagnosatindakanfk', 'dt.id')
            // ->leftjoin('diagnosapasien_t as dg' , 'dg.noregistrasifk' , '=' , 'apd.norec')
            // ->leftjoin('detaildiagnosapasien_t as ddg' , 'ddg.objectdiagnosapasienfk' , '=' , 'dg.norec')
            // ->leftjoin('jenisdiagnosa_m as jd' , 'jd.id' , '=' , 'ddg.objectjenisdiagnosafk')
            // ->leftjoin('diagnosa_m as dm' , 'dm.id' , '=' , 'ddg.objectdiagnosafk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                // 'dg.ketdiagnosis',
                'ps.tgllahir',
                'pd.tglregistrasi as tglmasuk',
                'sp.statuspulang',
                'pd.tglpulang',
                'kd.kondisipasien as kondisipulang',
                'kp.kelompokpasien as jenis_pasien',
                'rm.namaruangan as namaruanganrawat',
                'ru.namaruangan as namaruanganasal',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                DB::raw("EXTRACT(DAY FROM ((CASE WHEN pd.tglpulang IS NULL THEN CURRENT_TIMESTAMP ELSE pd.tglpulang END) - pd.tglregistrasi)) || ' Hari' AS lamarawat"),
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                'dt.kddiagnosatindakan as kodetindakan',
                'dt.namadiagnosatindakan as namatindakan',
                'pd.statuspasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.tglpulang', '!=', null)
            ->where('rm.objectdepartemenfk', '=', 16)
            ->whereBetween(DB::raw("CAST(pd.tglpulang AS DATE)"), $rangeDate);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rm.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglpulang');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
    public function getLaporanRI(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('kondisipasien_m as kd', 'kd.id', '=', 'pd.objectkondisipasienfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'pd.asalrujukanfk')
            // ->leftjoin('diagnosapasien_t as dg' , 'dg.noregistrasifk' , '=' , 'apd.norec')
            // ->leftjoin('detaildiagnosapasien_t as ddg' , 'ddg.objectdiagnosapasienfk' , '=' , 'dg.norec')
            // ->leftjoin('jenisdiagnosa_m as jd' , 'jd.id' , '=' , 'ddg.objectjenisdiagnosafk')
            // ->leftjoin('diagnosa_m as dm' , 'dm.id' , '=' , 'ddg.objectdiagnosafk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                // 'dg.ketdiagnosis',
                'ps.tgllahir',
                'pd.tglregistrasi as tglmasuk',
                'sp.statuspulang',
                'pd.tglpulang',
                'kd.kondisipasien as kondisipulang',
                'kp.kelompokpasien as jenis_pasien',
                'rm.namaruangan as namaruanganrawat',
                'ru.namaruangan as namaruanganasal',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                DB::raw("EXTRACT(DAY FROM ((CASE WHEN pd.tglpulang IS NULL THEN CURRENT_TIMESTAMP ELSE pd.tglpulang END) - pd.tglregistrasi)) || ' Hari' AS lamarawat"),
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                // 'jd.jenisdiagnosa',
                // 'dm.kddiagnosa as kodediagnosa',
                // 'dm.namadiagnosa as namadiagnosa',
                'pd.statuspasien',
                'ar.asalrujukan',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.tglpulang', '!=', null)
            ->where('rm.objectdepartemenfk', '=', 16)
            ->whereBetween(DB::raw("CAST(pd.tglpulang AS DATE)"), $rangeDate);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rm.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglpulang');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanIndexingGD(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganasalfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('statuspulang_m as sp', 'sp.id', '=', 'pd.objectstatuspulangfk')
            ->leftjoin('kondisipasien_m as kd', 'kd.id', '=', 'pd.objectkondisipasienfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'pd.asalrujukanfk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->leftjoin('diagnosapasien_t as dg', 'dg.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('detaildiagnosapasien_t as ddg', 'ddg.objectdiagnosapasienfk', '=', 'dg.norec')
            ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddg.objectjenisdiagnosafk')
            ->leftjoin('diagnosa_m as dm', 'dm.id', '=', 'ddg.objectdiagnosafk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                'dg.ketdiagnosis',
                'ps.tgllahir',
                'pd.tglregistrasi as tglmasuk',
                'sp.statuspulang',
                'pd.tglpulang',
                'kd.kondisipasien as kondisipulang',
                'kp.kelompokpasien as jenis_pasien',
                'rm.namaruangan as namaruanganrawat',
                'ru.namaruangan as namaruanganasal',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                'ar.asalrujukan',
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                'jd.jenisdiagnosa',
                'dm.kddiagnosa as kodediagnosa',
                'dm.namadiagnosa as namadiagnosa',
                'pd.statuspasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('pd.tglpulang', '!=', null)
            ->where('rm.objectdepartemenfk', '=', 9)
            ->whereBetween(DB::raw("CAST(pd.tglpulang AS DATE)"), $rangeDate);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rm.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglpulang');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getPenandaPasien(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('penandapasien_t as pp', 'pp.noregistrasi', 'pd.noregistrasi')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'ps.tgllahir',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'jk.jeniskelamin',
                'rm.namaruangan as namaruanganrawat',
                'ru.namaruangan as namaruanganasal',
                'pp.penanda',
                'pp.kategori_usia as kategoriUsia',
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('ru.objectdepartemenfk', '=', 9)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglpulang');
        $data = $data->distinct();
        $data =  $data->get();

        foreach ($data as $item) {
            $item->umur = $this->getAgeYear($item->tgllahir, $item->tglregistrasi) . ' thn';
        }

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getLaporanIndexingRJ(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as rm', 'rm.id', '=', 'pd.objectruanganlastfk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('kelas_m as kl', 'kl.id', '=', 'pd.objectkelasfk')
            ->join('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftjoin('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->leftjoin('alamat_m as al', 'al.nocmfk', '=', 'ps.id')
            ->leftjoin('desakelurahan_m as ds', 'ds.id', '=', 'al.objectdesakelurahanfk')
            ->leftjoin('kecamatan_m as kc', 'kc.id', '=', 'al.objectkecamatanfk')
            ->leftjoin('kotakabupaten_m as kt', 'kt.id', '=', 'al.objectkotakabupatenfk')
            ->leftjoin('propinsi_m as pp', 'pp.id', '=', 'al.objectpropinsifk')
            ->leftjoin('diagnosapasien_t as dg', 'dg.noregistrasifk', '=', 'apd.norec')
            ->leftjoin('detaildiagnosapasien_t as ddg', 'ddg.objectdiagnosapasienfk', '=', 'dg.norec')
            ->leftjoin('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddg.objectjenisdiagnosafk')
            ->leftjoin('diagnosa_m as dm', 'dm.id', '=', 'ddg.objectdiagnosafk')
            ->select(
                'ps.namapasien',
                'ps.nocm',
                'pd.noregistrasi',
                'jk.jeniskelamin',
                'dg.ketdiagnosis',
                'ps.tgllahir',
                'pd.tglregistrasi',
                'kp.kelompokpasien as jenis_pasien',
                'pg.namalengkap as dokter',
                'kl.namakelas',
                'rm.namaruangan',
                DB::raw("EXTRACT(DAY FROM ((CASE WHEN pd.tglpulang IS NULL THEN CURRENT_TIMESTAMP ELSE pd.tglpulang END) - pd.tglregistrasi)) || ' Hari' AS lamarawat"),
                'kt.namakotakabupaten',
                'kc.namakecamatan',
                'ds.namadesakelurahan',
                'pp.namapropinsi',
                'jd.jenisdiagnosa',
                'dm.kddiagnosa as kodediagnosa',
                'dm.namadiagnosa as namadiagnosa',
                'pd.statuspasien'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->where('rm.objectdepartemenfk', '=', 18)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi AS DATE)"), $rangeDate);


        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rm.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('kp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['statuspasien']) && $request['statuspasien'] != "" && $request['statuspasien'] != "undefined") {
            $data = $data->Where('pd.statuspasien', 'ilike', '%' . $request['statuspasien'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }

        $data = $data->orderBy('pd.tglregistrasi');
        $data = $data->distinct();
        $data =  $data->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getlaporanAntol(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $deptJalan = $this->settingFix('kdDepartemenPoli');
        $idKelBPJS = (int) $this->settingFix('idKelompokPasienBPJS');
        $tglawal = $request->tglAwal;
        $tglakhir = $request->tglAkhir;

        $nocm = "";
        if (isset($request->norm) && $request->norm != '' && $request->norm != null) {
            $nocm = " and ps.nocm = '" . $request->norm . "'";
        }
        $nama = "";
        if (isset($request->nama) && $request->nama != '' && $request->nama != null) {
            $nama = " and ps.namapasien ilike '%" . $request->nama . "%'";
        }
        $ruangId = "";
        if (isset($request->ruangId) && $request->ruangId != '' && $request->ruangId != null) {
            $ruangId = " and rm.id = '" . $request->ruangId . "'";
        }
        $kdBooking = "";
        if (isset($request->kdBooking) && $request->kdBooking != '' && $request->kdBooking != null) {
            $kdBooking = " and sx.noregistrasi = '" . $request->kdBooking . "'";
        }

        $data = DB::select(DB::raw("
        select sx.* ,string_agg(lg.keterangan, ' | ' ) as log from (
            select
            ps.nocm as norm,
            case when pd.ismobilejkn = true then pd.noreservasi else pd.noregistrasi end as noregistrasi,
            mt.noregistrasifk,
            pd.tglregistrasi,
            ps.namapasien,
            rm.namaruangan,
            mt.taskid,
            mt.waktu,
            mt.statuskirim,
            mt.dataantrean,
            row_number() over(PARTITION BY mt.noregistrasifk,mt.taskid ORDER BY taskid) as nomor
            from monitoringtaskid_t mt
            inner join pasiendaftar_t pd on pd.norec = mt.noregistrasifk
            inner join ruangan_m rm on rm.id = pd.objectruanganlastfk
            inner join pasien_m ps on ps.id = pd.nocmfk
            left join kelompokpasien_m kp on kp.id = pd.objectkelompokpasienlastfk
            left join antrianpasienregistrasi_t apr on apr.nocmfk = pd.nocmfk
            and apr.objectruanganfk=pd.objectruanganlastfk
            and to_char(pd.tglregistrasi,'yyyy-MM-dd') =  to_char(apr.tanggalreservasi,'yyyy-MM-dd')
            where pd.statusenabled = true
            and rm.kdspesialisbpjs <> 'HDL'
            and rm.objectdepartemenfk = $deptJalan
            and pd.tglregistrasi between '$tglawal' and '$tglakhir'
            and pd.objectkelompokpasienlastfk = $idKelBPJS
            and case when pd.ismobilejkn = true then pd.ischeckin = true else pd.ischeckin is null end
            $nocm
            $nama
            $ruangId
        ) sx
        left join logginguser_t lg on lg.noreff = sx.noregistrasi
        where sx.nomor = 1
        $kdBooking

        GROUP BY sx.norm, sx.noregistrasi, sx.tglregistrasi, sx.namapasien, sx.namaruangan, sx.noregistrasifk, sx.taskid,sx.waktu,
        sx.statuskirim,sx.dataantrean,sx.nomor
        ORDER BY sx.tglregistrasi asc
        "));

        $dataGROUP = [];
        foreach ($data as $item) {
            $status_1 = false;
            $taksid_1 = null;
            $status_2 = false;
            $taksid_2 = null;
            $status_3 = false;
            $taksid_3 = null;
            $status_4 = false;
            $taksid_4 = null;
            $status_5 = false;
            $taksid_5 = null;
            $status_6 = false;
            $taksid_6 = null;
            $status_7 = false;
            $taksid_7 = null;
            $sama = false;
            $i = 0;
            foreach ($dataGROUP as $item2) {
                if ($item->noregistrasi == $dataGROUP[$i]['noregistrasi']) {
                    $sama = true;
                    if ($item->taskid == 1) {
                        $dataGROUP[$i]['status_1'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_1'] = $item->waktu;
                    }
                    if ($item->taskid == 2) {
                        $dataGROUP[$i]['status_2'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_2'] = $item->waktu;
                    }
                    if ($item->taskid == 3) {
                        $dataGROUP[$i]['status_3'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_3'] = $item->waktu;
                    }
                    if ($item->taskid == 4) {
                        $dataGROUP[$i]['status_4'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_4'] = $item->waktu;
                    }
                    if ($item->taskid == 5) {
                        $dataGROUP[$i]['status_5'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_5'] = $item->waktu;
                    }
                    if ($item->taskid == 6) {
                        $dataGROUP[$i]['status_6'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_6'] = $item->waktu;
                    }
                    if ($item->taskid == 7) {
                        $dataGROUP[$i]['status_7'] = $item->statuskirim;
                        $dataGROUP[$i]['taksid_7'] = $item->waktu;
                    }
                    if ($item->dataantrean != null)
                        $dataGROUP[$i]['dataantrean'] = json_decode($item->dataantrean);
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->taskid == 1) {
                    $status_1 = $item->statuskirim;
                    $taksid_1 = $item->waktu;
                }
                if ($item->taskid == 2) {
                    $status_2 = $item->statuskirim;
                    $taksid_2 = $item->waktu;
                }
                if ($item->taskid == 3) {
                    $status_3 = $item->statuskirim;
                    $taksid_3 = $item->waktu;
                }
                if ($item->taskid == 4) {
                    $status_4 = $item->statuskirim;
                    $taksid_4 = $item->waktu;
                }
                if ($item->taskid == 5) {
                    $status_5 = $item->statuskirim;
                    $taksid_5 = $item->waktu;
                }
                if ($item->taskid == 6) {
                    $status_6 = $item->statuskirim;
                    $taksid_6 = $item->waktu;
                }
                if ($item->taskid == 7) {
                    $status_7 = $item->statuskirim;
                    $taksid_7 = $item->waktu;
                }

                $dataGROUP[] = array(
                    'norm' => $item->norm,
                    'noregistrasi' => $item->noregistrasi,
                    'tglregistrasi' => $item->tglregistrasi,
                    'namapasien' =>  $item->namapasien,
                    'namaruangan' => $item->namaruangan,
                    'noregistrasifk' => $item->noregistrasifk,
                    'status_1' => $status_1,
                    'status_2' => $status_2,
                    'status_3' => $status_3,
                    'status_4' => $status_4,
                    'status_5' => $status_5,
                    'status_6' => $status_6,
                    'status_7' => $status_7,
                    'taksid_1' => $taksid_1,
                    'taksid_2' => $taksid_2,
                    'taksid_3' => $taksid_3,
                    'taksid_4' => $taksid_4,
                    'taksid_5' => $taksid_5,
                    'taksid_6' => $taksid_6,
                    'taksid_7' => $taksid_7,
                    'dataantrean' => $item->dataantrean,
                    'log' => $item->log,
                );
            }
        }

        $dataGROUP = json_decode(json_encode($dataGROUP));
        foreach ($dataGROUP as $item) {
            $message = '';
            if ($item->log != null) {
                $log = explode("|", $item->log);

                $logmes = '';
                if (count($log) > 0) {
                    foreach ($log as $l => $vv) {
                        $logs = json_decode($vv);
                        if (isset($logs->metaData) && isset($logs->metaData->message)) {
                            $message = $message . ',' . $logs->metaData->message;
                        }
                    }
                }
            }

            $item->log =  substr($message, 1, strlen($message) - 1);
            if ($item->log == false) {
                $item->log = '';
            }
            $item->interval = ceil(($item->taksid_4 - $item->taksid_3) / 60000);
            $item->taksid_1 = $item->taksid_1 == null ? "-" : date('Y-m-d H:i', $item->taksid_1 / 1000);
            $item->taksid_2 = $item->taksid_2 == null ? "-" : date('Y-m-d H:i', $item->taksid_2 / 1000);
            $item->taksid_3 = $item->taksid_3 == null ? "-" : date('Y-m-d H:i', $item->taksid_3 / 1000);
            $item->taksid_4 = $item->taksid_4 == null ? "-" : date('Y-m-d H:i', $item->taksid_4 / 1000);
            $item->taksid_5 = $item->taksid_5 == null ? "-" : date('Y-m-d H:i', $item->taksid_5 / 1000);
            $item->taksid_6 = $item->taksid_6 == null ? "-" : date('Y-m-d H:i', $item->taksid_6 / 1000);
            $item->taksid_7 = $item->taksid_7 == null ? "-" : date('Y-m-d H:i', $item->taksid_7 / 1000);
            $item->total = floor($item->interval / 60) . " jam " . ($item->interval % 60) . " menit";
        }

        $result = array(
            "response" => $dataGROUP,
            "metaData" => array(
                "message" => "Ok",
                "code" => 200,
            )
        );

        return response()->json($result, $result["metaData"]["code"]);
    }

    public function getLaporanInsidensiPegawaiSakit(Request $request)
    {
        $rangeDate = [$request->tglAwal, $request->tglAkhir];
        $kdDiagnosaUtama = $this->settingFix("kdDiagnosaUtama");

        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'apd.objectpegawaifk')
            ->join('ruangan_m as rg', 'rg.id', '=', 'apd.objectruanganfk')
            ->leftJoin('kelompokpasien_m as klp', 'klp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('rekanan_m as rk', 'rk.id', '=', 'pd.objectrekananfk')
            ->select(
                'pd.norec',
                'pd.noregistrasi',
                'pd.created_at as tglregistrasi',
                'apd.tglmasuk',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'pd.statuspasien',
                'apd.noantrian',
                'apd.objectruanganfk',
                'jk.reportdisplay',
                'rk.namarekanan'
            )
            ->groupBy(
                'pd.norec',
                'pd.noregistrasi',
                'pd.created_at',
                'apd.tglmasuk',
                'ps.nocm',
                'ps.namapasien',
                'ps.nohp',
                'ps.tgllahir',
                'jk.jeniskelamin',
                'pd.statuspasien',
                'rg.namaruangan',
                'pg.namalengkap',
                'ps.tgldaftar',
                'klp.kelompokpasien',
                'pd.statuspasien',
                'apd.noantrian',
                'apd.objectruanganfk',
                'jk.reportdisplay',
                'rk.namarekanan'
            )
            ->where('pd.statusenabled', true)
            ->where('apd.statusenabled', true)
            ->whereBetween(DB::raw("CAST(apd.tglmasuk AS DATE)"), $rangeDate)
            ->where('pd.kdprofile', $this->kdProfile);

        if (isset($request['ruanganId']) && $request['ruanganId'] != "" && $request['ruanganId'] != "undefined") {
            $data = $data->where('rg.id', '=', $request['ruanganId']);
        }
        if (isset($request['nocm']) && $request['nocm'] != "" && $request['nocm'] != "undefined") {
            $data = $data->where('ps.nocm', 'ilike', '%' . $request['nocm'] . '%');
        }
        if (isset($request['nama']) && $request['nama'] != "" && $request['nama'] != "undefined") {
            $data = $data->Where('ps.namapasien', 'ilike', '%' . $request['nama'] . '%');
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['kelompokpasien']) && $request['kelompokpasien'] != "" && $request['kelompokpasien'] != "undefined") {
            $data = $data->Where('klp.id', '=', $request['kelompokpasien']);
        }
        if (isset($request['dokter']) && $request['dokter'] != "" && $request['dokter'] != "undefined") {
            $data = $data->Where('pg.id', '=', $request['dokter']);
        }
        if (isset($request['departemen']) && $request['departemen'] != "" && $request['departemen'] != "undefined") {
            $data = $data->Where('rg.objectdepartemenfk', 'ilike', '%' . $request['departemen'] . '%');
        }

        $data = $data->orderBy('apd.tglmasuk');
        $data = $data->distinct();
        $data =  $data->get();

        $diagnosaX = DB::table('detaildiagnosapasien_t as ddp')
            ->join('diagnosa_m as dg', 'dg.id', '=', 'ddp.objectdiagnosafk')
            ->join('jenisdiagnosa_m as jd', 'jd.id', '=', 'ddp.objectjenisdiagnosafk')
            ->select(
                'ddp.noregistrasi',
                'dg.kddiagnosa',
                'dg.namadiagnosa',
                'ddp.tglinputdiagnosa',
                'jd.jenisdiagnosa',
                'ddp.keterangan',
                DB::raw("'ICD X' as jenis")
            )
            ->where('ddp.kdprofile', $this->kdProfile)
            ->where('ddp.statusenabled', true)
            ->where('jd.id', $kdDiagnosaUtama)
            ->whereBetween(DB::raw("CAST(ddp.tglinputdiagnosa AS DATE)"), $rangeDate)
            ->orderByDesc('ddp.tglinputdiagnosa')
            ->get();

        // Create a lookup array for diagnoses, keyed by 'noregistrasi'
        $diagnosaLookup = [];
        foreach ($diagnosaX as $dx) {
            // You could store both 'kddiagnosa' and 'namadiagnosa' as an array or as a string.
            $diagnosaLookup[$dx->noregistrasi] = $dx->kddiagnosa . ' ' . $dx->namadiagnosa;
        }
        // Now loop through $data and assign the corresponding diagnoses
        foreach ($data as $d) {
            // Check if the 'noregistrasi' exists in the lookup array
            if (isset($diagnosaLookup[$d->noregistrasi])) {
                // Assign the concatenated diagnosa
                $d->diagnosa = $diagnosaLookup[$d->noregistrasi];
            } else {
                // If no diagnosis found, set to an empty string or some default value
                $d->diagnosa = '';
            }
        }

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }
}
