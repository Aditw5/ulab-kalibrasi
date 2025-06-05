<?php

namespace App\Http\Controllers\laporan;

use App\Http\Controllers\Controller;
use App\Models\Master\CaraBayar;
use App\Models\Master\JenisLaporan;
use App\Models\Master\KelompokLaporan;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Models\Master\SlottingKiosk;
use App\Models\Transaksi\LoggingUser;
use App\Models\Transaksi\PasienDaftar;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanRekamMedisCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }


    public function getDataRL31RawatInapNew(Request $request)
    {
        $idProfile = $this->kdProfile;

        $kdRanap = $this->settingFix('kdDepartemenRanapFix');


        $idStatKelMeninggal = (int) $this->settingFix('kdMeninggal');
        $idKondisiPasienMeninggal = $this->settingFix('idKondisiPasienMeninggal');
        $kodeRS = $this->settingFix('kodeRS');
        $kodeProv = $this->settingFix('kodeProv');
        $getKota = $this->settingFix('getKota');
        $tglAwal = $request['tglAwal'];
        $tglAkhir = $request['tglAkhir'];
        $tahun = new \DateTime($tglAkhir);
        $tahun = date('Y');
        $datetime1 = new \DateTime($tglAwal);
        $datetime2 = new \DateTime($tglAkhir);
        $interval = $datetime1->diff($datetime2);
        $sehari = 1; //$interval->format('%d');
        $data10 = [];
        $jumlahTT = collect(DB::select("SELECT
                    tt.id,
                    tt.objectstatusbedfk
            FROM
                    tempattidur_m AS tt
            INNER JOIN kamar_m AS kmr ON kmr.id = tt.objectkamarfk
            INNER JOIN ruangan_m AS ru ON ru.id = kmr.objectruanganfk
            WHERE
                    tt.kdprofile = $idProfile
            AND tt.statusenabled = true
            AND kmr.statusenabled = true
            AND tt.reportdisplay NOT ILIKE '%cadangan%'
            "))->count();
        if ($jumlahTT == 0) {
            $data10[] = array(
                'lamarawat' => 0,
                'hariperawatan' => 0,
                'pasienpulang' => 0,
                'meninggal' => 0,
                'matilebih48' =>  0,
                'tahun' => 0,
                'bulan' => date('d-M-Y'), //(float)$item->bulanregis ,
                'bor' => 0,
                'alos' => 0,
                'bto' => 0,
                'toi' =>  0,
                'gdr' => 0,
                'ndr' =>  0,
            );

            return $data10;
        }

        $hariPerawatan = DB::select(DB::raw(
            "
           SELECT   COUNT (x.noregistrasi) AS jumlahhariperawatan
            FROM
            (
                SELECT
                    pd.noregistrasi,
                    pd.tglpulang,
                    to_char ( pd.tglregistrasi,'mm') AS bulanregis
                FROM
                    pasiendaftar_t AS pd
                INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                WHERE
                    ru.objectdepartemenfk in ($kdRanap)
                    and pd.kdprofile = $idProfile
                    and pd.tglregistrasi::date BETWEEN '$tglAwal' AND '$tglAkhir'   
                    and pd.statusenabled = true
            ) AS x"
        ));

        $lamaRawat = DB::select(DB::raw("
                        select sum(x.hari) as lamarawat, count(x.noregistrasi)as jumlahpasienpulang from (
                        SELECT
                            date_part('DAY', pd.tglpulang- pd.tglregistrasi) as hari ,pd.noregistrasi
                            FROM pasiendaftar_t AS pd
                        --  INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.noregistrasifk = pd.norec
                            INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
                            WHERE pd.kdprofile = $idProfile and
                            pd.tglpulang::date BETWEEN '$tglAwal' AND '$tglAkhir'
                            and pd.tglpulang is not null
                            and pd.statusenabled=true
                            and  ru.objectdepartemenfk in ($kdRanap)
                            GROUP BY pd.noregistrasi,pd.tglpulang,pd.tglregistrasi
                        -- order by pd.noregistrasi
                    ) as x
                "));


        $dataMeninggal = DB::select(DB::raw("select count(x.noregistrasi) as jumlahmeninggal, x.bulanregis,
                count(case when x.objectkondisipasienfk = $idKondisiPasienMeninggal then 1 end ) AS jumlahlebih48 FROM
                (
                select noregistrasi,to_char(tglregistrasi , 'mm')  as bulanregis ,statuskeluar,kondisipasien,objectkondisipasienfk
                from pasiendaftar_t
                join statuskeluar_m on statuskeluar_m.id =pasiendaftar_t.objectstatuskeluarfk
                left join kondisipasien_m on kondisipasien_m.id =pasiendaftar_t.objectkondisipasienfk
                where pasiendaftar_t.kdprofile = $idProfile and objectstatuskeluarfk = $idStatKelMeninggal
                and  tglregistrasi::date BETWEEN '$tglAwal' and '$tglAkhir'
                and pasiendaftar_t.statusenabled=true
                ) as x
                GROUP BY x.bulanregis;"));

        $ratarata = DB::select(DB::raw("with cte as(
            select extract(year from month) as year, sum(days) as jumlah from(
                SELECT date_trunc('month',dt) AS month,
                COUNT(*) as days
                FROM generate_series( DATE '$tglAwal',DATE '$tglAkhir',interval '1 DAY' )
                as dt group by date_trunc('month',dt) 
                order by month
            ) as x group by extract(year from month)
        )
        SELECT extract(year from apd.tglmasuk) as tgl, count(apd.norec), cte.jumlah, round(count(apd.norec)/cte.jumlah) as total
        FROM
        antrianpasiendiperiksa_t apd
        inner join ruangan_m ru on ru.id = apd.objectruanganfk
        inner join cte on cte.year = extract(year from apd.tglmasuk)
        WHERE apd.tglmasuk::date BETWEEN '$tglAwal' and '$tglAkhir'
        and apd.kdprofile = $idProfile
        and apd.statusenabled=true
        and ru.objectdepartemenfk in ($kdRanap)
        group by extract(year from apd.tglmasuk), cte.jumlah"));
        $year = Carbon::now()->year;
        $num_of_days = [];
        if ($year == date('Y'))
            $total_month = date('m');
        else
            $total_month = 12;

        for ($m = 1; $m <= $total_month; $m++) {
            $num_of_days[] = array(
                'bulan' =>  $m,
                'jumlahhari' =>  cal_days_in_month(CAL_GREGORIAN, $m, $year),
            );
        }
        $bor = 0;
        $alos = 0;
        $toi = 0;
        $bto = 0;
        $ndr = 0;
        $gdr = 0;
        $hariPerawatanJml = 0;
        $jmlPasienPlg = 0;
        $jmlLamaRawat = 0;
        $jmlMeninggal = 0;
        $jmlMatilebih48 = 0;
        $hasilRata = 0;
        foreach ($hariPerawatan as $item) {
            foreach ($lamaRawat as $itemLamaRawat) {
                foreach ($dataMeninggal as $itemDead) {
                    if ($itemLamaRawat->jumlahpasienpulang == 0) {
                        $itemLamaRawat->jumlahpasienpulang  = 1;
                    }
                    if ($itemDead->jumlahmeninggal == 0) {
                        $itemDead->jumlahmeninggal = 1;
                    }
                    if ($itemDead->jumlahlebih48 == 0) {
                        $itemDead->jumlahlebih48 = 1;
                    }
                    //                         if ($item->bulanregis == $itemLamaRawat->bulanpulang &&
                    //                             $itemLamaRawat->bulanpulang == $itemDead->bulanregis ) {
                    /** @var  $gdr = (Jumlah Mati dibagi Jumlah pasien Keluar (Hidup dan Mati) */
                    $gdr = (int) $itemDead->jumlahmeninggal * 1000 / (int)$itemLamaRawat->jumlahpasienpulang;
                    /** @var  $NDR = (Jumlah Mati > 48 Jam dibagi Jumlah pasien Keluar (Hidup dan Mati) */
                    $ndr = (int) $itemDead->jumlahlebih48 * 1000 / (int)$itemLamaRawat->jumlahpasienpulang;

                    $jmlMeninggal = (int) $itemDead->jumlahmeninggal;
                    $jmlMatilebih48 = (int) $itemDead->jumlahlebih48;
                    //                         }
                }
                //                if ($item->bulanregis == $itemLamaRawat->bulanpulang ) {
                /** @var  $alos = (Jumlah Lama Dirawat dibagi Jumlah pasien Keluar (Hidup dan Mati) */
                //                return $this->respond($itemLamaRawat->jumlahpasienpulang );
                if ((int)$itemLamaRawat->jumlahpasienpulang > 0) {
                    $alos = (int)$itemLamaRawat->lamarawat / (int)$itemLamaRawat->jumlahpasienpulang;
                }

                /** @var  $bto = Jumlah pasien Keluar (Hidup dan Mati) DIBAGI Jumlah tempat tidur */
                $bto = (int)$itemLamaRawat->jumlahpasienpulang / $jumlahTT;

                //                }
                //                foreach ($num_of_days as $numday){
                //                    if ($numday['bulan'] == $item->bulanregis){
                /** @var  $bor = (Jumlah hari perawatn RS dibagi ( jumlah TT x Jumlah hari dalam satu periode ) ) x 100 % */
                $bor = ((int)$item->jumlahhariperawatan * 100 / ($jumlahTT *  (float)$sehari)); //$numday['jumlahhari']));
                /** @var  $toi = (Jumlah TT X Periode) - Hari Perawatn DIBAGI Jumlah pasien Keluar (Hidup dan Mati)*/
                //                        $toi = ( ( $jumlahTT * $numday['jumlahhari'] )- (int)$item->jumlahhariperawatan ) /(int)$itemLamaRawat->jumlahpasienpulang ;
                if ((int)$itemLamaRawat->jumlahpasienpulang > 0) {
                    $toi = (($jumlahTT * (float)$sehari) - (int)$item->jumlahhariperawatan) / (int)$itemLamaRawat->jumlahpasienpulang;
                }
                $hariPerawatanJml = (int)$item->jumlahhariperawatan;
                $jmlPasienPlg = (int)$itemLamaRawat->jumlahpasienpulang;
                //                    }
                //                }
                foreach ($ratarata as $rata) {
                    $hasilRata = $rata->total;
                }
            }

            $data10[] = array(
                'koders' => $kodeRS,
                'kota' => $getKota,
                'kodeprov' => $kodeProv,
                'lamarawat' => (int)$itemLamaRawat->lamarawat,
                'hariperawatan' => $hariPerawatanJml,
                'pasienpulang' => $jmlPasienPlg,
                'meninggal' => $jmlMeninggal,
                'matilebih48' =>  $jmlMatilebih48,
                'tahun' => $tahun,
                'ratarataperhari' => $hasilRata,
                'bulan' => date('d-M-Y'), //(float)$item->bulanregis ,
                'bor' => (float) number_format($bor, 2),
                'alos' => (float) number_format($alos, 2),
                'bto' => (float) number_format($bto, 2),
                'toi' => (float)number_format($toi, 2),
                'gdr' => (float)number_format($gdr, 2),
                'ndr' => (float) number_format($ndr, 2),
                'hasil' => (float) number_format($hasilRata, 2),
            );
        }

        $result = array(
            'data' => $data10,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getDataRL31RawatInap(Request $request)
    {

        $deptId = explode(',', $this->settingFix('KdListDepartemen'));
        $KdJenisLaporan = $this->settingFix('KdJenisLapRanap');

        $data = DB::table('antrianpasiendiperiksa_t as app')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'app.objectasalrujukanfk')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'app.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'app.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('kelas_m as kls', 'kls.id', '=', 'app.objectkelasfk')
            ->leftjoin('statuskeluar_m as sk', 'sk.id', '=', 'pd.objectstatuskeluarfk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->select(
                'pd.tglregistrasi',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'app.objectasalrujukanfk',
                'ar.asalrujukan',
                'ru.namaruangan',
                'dpm.namadepartemen',
                'pg.namalengkap',
                'pm.objectjeniskelaminfk',
                'ru.objectdepartemenfk',
                'pd.objectstatuskeluarfk as objectstatuskeluarfk',
                'pd.objectkelasfk as objectkelasfk',
                'kl.kelompoklaporan as jenis_spesialisasi',
                'jl.jenislaporan',
                'pr.id as produkfk',
                DB::raw(
                    "
                    CASE
                        WHEN pd.tglpulang IS NULL THEN
                        DATE_PART('day', now() - pd.tglregistrasi::timestamp)
                        ELSE
                        DATE_PART('day', pd.tglpulang::TIMESTAMP - pd.tglregistrasi::timestamp)
                        END AS lamadirawat,
                        to_char( pd.tglregistrasi,'MM') as bulanregistrasi"
                )

            )
            ->where('app.kdprofile', $this->kdProfile)
            ->where('pr.objectdetailjenisprodukfk','!=' , 474)
            ->where(DB::raw("to_char(pp.tglpelayanan,'yyyy')"), $request['tahun'])
            ->whereIn('dpm.id', $deptId)
            ->where('jl.id', $KdJenisLaporan);

        $data = $data->get();
        $data10 = [];
        $jml = 0;
        $sama = false;

        $pasienAwalTahun = 0;
        $pasienMasuk = 0;
        $pasienKeluarHidup = 0;
        $matilebih48jam = 0;
        $matikurang48jam = 0;
        $jumlahLamaDirawat = 0;
        $pasienAkhirTahun = 0;
        $jumlahHariDirawat = 0;
        $rincianVipB = 0;
        $rincianVipA = 0;
        $rincianKel1 = 0;
        $rincianKel2 = 0;
        $rincianKel3 = 0;
        $rincianKelNonKelas = 0;
        $DiTerimaKembali = 0;


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenis_spesialisasi == $data10[$i]['jenis_spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    if ($item->bulanregistrasi == 1) {
                        $data10[$i]['pasienawaltahun'] = (float)$hideung['pasienawaltahun'] + 1;
                    } else if ($item->bulanregistrasi == 12) {
                        $data10[$i]['pasienakhirtahun'] = (float)$hideung['pasienakhirtahun'] + 1;
                    } else if ($item->objectdepartemenfk == 16 || $item->objectdepartemenfk == 25) {
                        $data10[$i]['pasienmasuk'] = (float)$hideung['pasienmasuk'] + 1;
                    } elseif ($item->objectstatuskeluarfk != 5) {
                        $data10[$i]['pasienkeluarhidup'] = (float)$hideung['pasienkeluarhidup'] + 1;
                    } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat < 48) {
                        $data10[$i]['matikurang48jam'] = (float)$hideung['matikurang48jam'] + 1;
                    } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat > 48) {
                        $data10[$i]['matilebih48jam'] = (float)$hideung['matilebih48jam'] + 1;
                    }

                    if ($item->objectkelasfk == 8) {
                        $data10[$i]['rincianvipb'] = (float)$hideung['rincianvipb'] + 1;
                    } elseif ($item->objectkelasfk == 5) {
                        $data10[$i]['rincianvipa'] = (float)$hideung['rincianvipa'] + 1;
                    } elseif ($item->objectkelasfk == 3) {
                        $data10[$i]['rinciankel1'] = (float)$hideung['rinciankel1'] + 1;
                    } elseif ($item->objectkelasfk == 2) {
                        $data10[$i]['rinciankel2'] = (float)$hideung['rinciankel2'] + 1;
                    } elseif ($item->objectkelasfk == 1) {
                        $data10[$i]['rinciankel3'] = (float)$hideung['rinciankel3'] + 1;
                    } elseif ($item->objectkelasfk == 6) {
                        $data10[$i]['rinciannonkelas'] = (float)$hideung['rinciannonkelas'] + 1;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->bulanregistrasi == 1) {
                    $pasienAwalTahun = 1;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } else if ($item->bulanregistrasi == 12) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 1;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } else if ($item->objectdepartemenfk == 16 || $item->objectdepartemenfk == 25) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 1;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk != 5) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 1;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat < 48) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 1;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectstatuskeluarfk == 5 && $item->lamadirawat > 48) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 1;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                }
                if ($item->objectkelasfk == 8) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 1;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 5) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 1;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 3) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 1;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 2) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 1;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 1) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 1;
                    $rincianKelNonKelas = 0;
                    $DiTerimaKembali = 0;
                } elseif ($item->objectkelasfk == 6) {
                    $pasienAwalTahun = 0;
                    $pasienMasuk = 0;
                    $pasienKeluarHidup = 0;
                    $matilebih48jam = 0;
                    $matikurang48jam = 0;
                    $jumlahLamaDirawat = 0;
                    $pasienAkhirTahun = 0;
                    $jumlahHariDirawat = 0;
                    $rincianVipB = 0;
                    $rincianVipA = 0;
                    $rincianKel1 = 0;
                    $rincianKel2 = 0;
                    $rincianKel3 = 0;
                    $rincianKelNonKelas = 1;
                    $DiTerimaKembali = 0;
                }

                $data10[] = array(
                    'jenis_spesialisasi' => $item->jenis_spesialisasi,
                    'pasienmasuk' => $pasienMasuk,
                    'lamadirawat' => $item->lamadirawat,
                    'pasienawaltahun' => $pasienAwalTahun,
                    'pasienakhirtahun' => $pasienAkhirTahun,
                    'pasienkeluarhidup' => $pasienKeluarHidup,
                    'matilebih48jam' => $matilebih48jam,
                    'matikurang48jam' => $matikurang48jam,
                    'jumlahlamadirawat' => $jumlahLamaDirawat,
                    'pasienakhirtahun' => $pasienAkhirTahun,
                    'jumlahharidirawat' => $jumlahHariDirawat,
                    'rincianvipb' => $rincianVipB,
                    'rincianvipa' => $rincianVipA,
                    'rinciankel1' => $rincianKel1,
                    'rinciankel2' => $rincianKel2,
                    'rinciankel3' => $rincianKel3,
                    'rinciannonkelas' => $rincianKelNonKelas,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $result = array(
            'data' => $data10,
            'message' => 'as@cepot',
        );

        return $this->respond($result);
    } 

    public function getComboMappingRL()
    {

        $result['ruanganRanap'] = Ruangan::mine()->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('objectdepartemenfk', explode(',', $this->settingFix('kdDepartemenRanapFix')))
            ->get();


        $result['ruanganRajal'] = Ruangan::mine()->where('statusenabled', true)
            ->where('kdprofile', $this->kdProfile)
            ->where('objectdepartemenfk', explode(',', $this->settingFix('idDepartemenRajal')))
            ->get();

        $result['caraBayar'] = CaraBayar::mine()->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
        $result['jenisLaporan'] = JenisLaporan::mine()->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();
        $result['kelompokLaporan'] = KelompokLaporan::mine()->where('kdprofile', $this->kdProfile)->where('statusenabled', true)->get();

        return $this->respond($result);
    }

    public function getProdukMapLaporanRL(Request $request)
    {

        $kdPelayanan = explode(',', $this->settingFix('KdlistPelayanan'));
        $dataProduk = DB::table('produk_m as pro')
            ->leftJoin('detailjenisproduk_m as djp', 'djp.id', '=', 'pro.objectdetailjenisprodukfk')
            ->leftJoin('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pro.id as idproduk',
                'pro.namaproduk',
                'djp.id as iddetail',
                'djp.detailjenisproduk',
                'jp.id as idjenis',
                'jp.jenisproduk',
                'jp.objectkelompokprodukfk',
                'kp.kelompokproduk'
            )
            ->where('pro.kdprofile', $this->kdProfile)
            ->whereIn('jp.objectkelompokprodukfk', $kdPelayanan)
            ->where('pro.statusenabled', true)
            ->orderBy('pro.namaproduk');
        if (isset($request['namaproduk'])) {
            $dataProduk = $dataProduk->where('pro.namaproduk', 'ilike', '%' . $request['namaproduk'] . '%');
        };

        $dataProduk = $dataProduk->limit($request['limit']);
        $dataProduk = $dataProduk->get();


        return $this->respond($dataProduk);
    }

    public function getLaporanRL4aRawatInap(Request $request)
    {
        $kdDeptRanapAll = explode(',', $this->settingFix('KdDepartemenRIAll'));
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as app', 'app.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->leftjoin('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->leftjoin('diagnosabantuan_m as dbn', 'dbn.kddiagnosa', '=', 'dm.kddiagnosa')
            ->leftjoin('diagnosadtd_m as ddtd', 'ddtd.nodtd', '=', 'dbn.nodtd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->leftjoin('mappingrlmorbiditas_m as mpr', 'mpr.kddiagnosa', '=', 'dm.kddiagnosa')
            ->select(
                'pd.tglregistrasi',
                'app.statuspenyakit',
                'dbn.nodtd',
                'dm.kddiagnosa',
                'ps.tgllahir as tglLahir',
                'ps.objectjeniskelaminfk',
                'pd.objectstatuskeluarfk',
                DB::raw('EXTRACT(YEAR from AGE(pd.tglregistrasi, ps.tgllahir)) as umuryear,
                                EXTRACT(MONTH from AGE(pd.tglregistrasi, ps.tgllahir)) as umurmonth,
                                EXTRACT(DAY from AGE(pd.tglregistrasi, ps.tgllahir)) as umurday,
                                lower(ddtd.golongansebabpenyakit) as golongansebabpenyakit
                       ')
            )
            ->whereIn('dpm.id', $kdDeptRanapAll)
            ->distinct();

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('pd.tglregistrasi', '<=', $tgl);
        }
        $data = $data->orderBy('dm.kddiagnosa');
        $data = $data->get();

        $data10 = [];
        $sama = false;
        $jml = 0;
        $jml6HariL = 0;
        $jml6HariP = 0;
        $jml28HariL = 0;
        $jml28HariP = 0;
        $jml1ThnL = 0;
        $jml1ThnP = 0;
        $jml4ThnL = 0;
        $jml4ThnP = 0;
        $jml14ThnL = 0;
        $jml14ThnP = 0;
        $jml24ThnL = 0;
        $jml24ThnP = 0;
        $jml44ThnL = 0;
        $jml44ThnP = 0;
        $jml64ThnL = 0;
        $jml64ThnP = 0;
        $jml65ThnL = 0;
        $jml65ThnP = 0;
        $totalMenurutL = 0;
        $totalMenurutP = 0;
        $totalKasusBaru = 0;
        $totalKunjungan = 0;
        $jmlPL = 0;

        $totalMenurutP = 0;
        $totalMenurutL = 0;
        $jmlKeluarMati = 0;
        $jmlKeluarHidup = 0;
        $statusKd = true;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;

            foreach ($data10 as $hideung) {
                if ($item->nodtd == $data10[$i]['nodtd']) {
                    $sama = true;
                    $jml = (float)$hideung['totalAll'] + 1;
                    $data10[$i]['totalAll'] = $jml;
                    $statusKd = false;

                    if (str_contains($data10[$i]['kddiagnosa'], $item->kddiagnosa)) {
                        $statusKd = true;
                    }
                    if ($statusKd == false) {
                        $data10[$i]['kddiagnosa'] = $data10[$i]['kddiagnosa'] . ',' . $item->kddiagnosa;
                    }

                    //Laki =1 && Perempuan=2
                    if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml6HariL'] = (float)$hideung['jml6HariL'] + 1;
                    } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml6HariP'] = (float)$hideung['jml6HariP'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml28HariL'] = (float)$hideung['jml28HariL'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml28HariP'] = (float)$hideung['jml28HariP'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml1ThnL'] = (float)$hideung['jml1ThnL'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml1ThnP'] = (float)$hideung['jml1ThnP'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml4ThnL'] = (float)$hideung['jml4ThnL'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml4ThnP'] = (float)$hideung['jml4ThnP'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml14ThnL'] = (float)$hideung['jml14ThnL'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml14ThnP'] = (float)$hideung['jml14ThnP'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml24ThnL'] = (float)$hideung['jml24ThnL'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml24ThnP'] = (float)$hideung['jml24ThnP'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml44ThnL'] = (float)$hideung['jml44ThnL'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml44ThnP'] = (float)$hideung['jml44ThnP'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml64ThnL'] = (float)$hideung['jml64ThnL'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml64ThnP'] = (float)$hideung['jml64ThnP'] + 1;
                    } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml65ThnL'] = (float)$hideung['jml65ThnL'] + 1;
                    } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml65ThnP'] = (float)$hideung['jml65ThnP'] + 1;
                    }

                    if ($item->objectstatuskeluarfk == 5) {
                        $data10[$i]['jmlKeluarMati'] = (float)$hideung['jmlKeluarMati'] + 1;
                    }
                    if ($item->objectstatuskeluarfk != 5) {
                        $data10[$i]['jmlKeluarHidup'] = (float)$hideung['jmlKeluarHidup'] + 1;
                    }

                    $data10[$i]['jmlPL'] = $data10[$i]['jml6HariL'] + $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariL'] + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnL'] + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnL'] + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnL'] + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnL'] + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnL'] + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnL'] + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnL'] + $data10[$i]['jml65ThnP'];
                    $data10[$i]['totalMenurutL'] = $data10[$i]['jml6HariL']
                        + $data10[$i]['jml28HariL']
                        + $data10[$i]['jml1ThnL']
                        + $data10[$i]['jml4ThnL']
                        + $data10[$i]['jml14ThnL']
                        + $data10[$i]['jml24ThnL']
                        + $data10[$i]['jml44ThnL']
                        + $data10[$i]['jml64ThnL']
                        + $data10[$i]['jml65ThnL'];
                    $data10[$i]['totalMenurutP'] = $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnP'];
                }
                $i = $i + 1;
            }

            //jika false
            if ($sama == false) {
                if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 1;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 1;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                    $jml28HariL = 1;
                    $jml6HariL = 1;
                    $jml6HariP = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 1;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 1;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 1;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 1;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 1;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 1;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 1;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 1;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 1;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 1;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 1;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 1;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 1;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 1;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 1;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 0;
                }
                if ($item->objectstatuskeluarfk == 5) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 1;
                    $jmlKeluarHidup = 0;
                }
                if ($item->objectstatuskeluarfk != 5) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKeluarMati = 0;
                    $jmlKeluarHidup = 1;
                }



                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'nodtd' => $item->nodtd,
                    'golongansebabpenyakit' => $item->golongansebabpenyakit,
                    'tglregistrasi' => $item->tglregistrasi,
                    'statuspenyakit' => $item->statuspenyakit,
                    'tglLahir' => $item->tglLahir,
                    'objectstatuskeluarfk' => $item->objectstatuskeluarfk,
                    'umuryear' => $item->umuryear,
                    'umurmonth' => $item->umurmonth,
                    'umurday' => $item->umurday,
                    'totalAll' => 1,
                    'jml6HariL' =>  $jml6HariL,
                    'jml6HariP' =>  $jml6HariP,
                    'jml28HariL' =>  $jml28HariL,
                    'jml28HariP' =>  $jml28HariP,
                    'jml1ThnL' =>  $jml1ThnL,
                    'jml1ThnP' =>  $jml1ThnP,
                    'jml4ThnL' =>  $jml4ThnL,
                    'jml4ThnP' =>  $jml4ThnP,
                    'jml14ThnL' =>  $jml14ThnL,
                    'jml14ThnP' =>  $jml14ThnP,
                    'jml24ThnL' =>  $jml24ThnL,
                    'jml24ThnP' =>  $jml24ThnP,
                    'jml44ThnL' =>  $jml44ThnL,
                    'jml44ThnP' =>  $jml44ThnP,
                    'jml64ThnL' =>  $jml64ThnL,
                    'jml64ThnP' =>  $jml64ThnP,
                    'jml65ThnL' =>  $jml65ThnL,
                    'jml65ThnP' =>  $jml65ThnP,
                    'totalMenurutL' => $totalMenurutL,
                    'jmlKeluarMati' => $jmlKeluarMati,
                    'jmlKeluarHidup' => $jmlKeluarHidup,
                    'totalMenurutP' => $totalMenurutP,
                    'totalHidupMati' => $jmlKeluarHidup + $jmlKeluarMati,
                    'jmlPL' => $totalMenurutL + $totalMenurutP,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['nodtd'];
            }

            array_multisort($count, SORT_ASC, $data10);
        }

        $result = array(
            'data' => $data10,
            'dept' => $kdDeptRanapAll,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL4bRawatJalan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdDeptRajalRehab = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $kdDepartemenRajalRehab = [];
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as app', 'app.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->leftjoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->leftjoin('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->leftjoin('diagnosabantuan_m as dbn', 'dbn.kddiagnosa', '=', 'dm.kddiagnosa')
            ->leftjoin('diagnosadtd_m as ddtd', 'ddtd.nodtd', '=', 'dbn.nodtd')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->join('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('mappingrlmorbiditas_m as mpr', 'mpr.kddiagnosa', '=', 'dm.kddiagnosa')
            ->select(
                'pd.tglregistrasi',
                'app.statuspenyakit',
                'dbn.nodtd',
                'dm.kddiagnosa',
                'ps.tgllahir as tglLahir',
                'ps.objectjeniskelaminfk',
                DB::raw('EXTRACT(YEAR from AGE(pd.tglregistrasi, ps.tgllahir)) as umuryear,
                                EXTRACT(MONTH from AGE(pd.tglregistrasi, ps.tgllahir)) as umurmonth,
                                EXTRACT(DAY from AGE(pd.tglregistrasi, ps.tgllahir)) as umurday,lower(ddtd.golongansebabpenyakit) as golongansebabpenyakit
                       ')
            )
            ->where('pd.kdprofile', $kdProfile)
            ->whereIn('dpm.id', $KdDeptRajalRehab)
            ->distinct();


        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $data = $data->where('pd.tglregistrasi', '<=', $tgl);
        }
        $data = $data->orderBy('dm.kddiagnosa');
        $data = $data->get();

        $data10 = [];
        $sama = false;
        $jml = 0;
        $jml6HariL = 0;
        $jml6HariP = 0;
        $jml28HariL = 0;
        $jml28HariP = 0;
        $jml1ThnL = 0;
        $jml1ThnP = 0;
        $jml4ThnL = 0;
        $jml4ThnP = 0;
        $jml14ThnL = 0;
        $jml14ThnP = 0;
        $jml24ThnL = 0;
        $jml24ThnP = 0;
        $jml44ThnL = 0;
        $jml44ThnP = 0;
        $jml64ThnL = 0;
        $jml64ThnP = 0;
        $jml65ThnL = 0;
        $jml65ThnP = 0;
        $jmlKasusBaruL = 0;
        $jmlKasusBaruP = 0;
        $totalKasusBaru = 0;
        $totalKunjungan = 0;
        $totalMenurutUmur = 0;
        $totalMenurutL = 0;
        $totalMenurutP = 0;
        $jmlPL = 0;

        $statusKd = true;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;

            foreach ($data10 as $hideung) {
                if ($item->nodtd == $data10[$i]['nodtd']) {
                    $sama = true;
                    $jml = (float)$hideung['totalAll'] + 1;
                    $data10[$i]['totalAll'] = $jml;
                    $statusKd = false;

                    if (str_contains($data10[$i]['kddiagnosa'], $item->kddiagnosa)) {
                        $statusKd = true;
                    }
                    if ($statusKd == false) {
                        $data10[$i]['kddiagnosa'] = $data10[$i]['kddiagnosa'] . ',' . $item->kddiagnosa;
                    }

                    //Laki =1 && Perempuan=2
                    if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml6HariL'] = (float)$hideung['jml6HariL'] + 1;
                    } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml6HariP'] = (float)$hideung['jml6HariP'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml28HariL'] = (float)$hideung['jml28HariL'] + 1;
                    } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml28HariP'] = (float)$hideung['jml28HariP'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml1ThnL'] = (float)$hideung['jml1ThnL'] + 1;
                    } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml1ThnP'] = (float)$hideung['jml1ThnP'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml4ThnL'] = (float)$hideung['jml4ThnL'] + 1;
                    } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml4ThnP'] = (float)$hideung['jml4ThnP'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml14ThnL'] = (float)$hideung['jml14ThnL'] + 1;
                    } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml14ThnP'] = (float)$hideung['jml14ThnP'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml24ThnL'] = (float)$hideung['jml24ThnL'] + 1;
                    } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml24ThnP'] = (float)$hideung['jml24ThnP'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml44ThnL'] = (float)$hideung['jml44ThnL'] + 1;
                    } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml44ThnP'] = (float)$hideung['jml44ThnP'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml64ThnL'] = (float)$hideung['jml64ThnL'] + 1;
                    } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml64ThnP'] = (float)$hideung['jml64ThnP'] + 1;
                    } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jml65ThnL'] = (float)$hideung['jml65ThnL'] + 1;
                    } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jml65ThnP'] = (float)$hideung['jml65ThnP'] + 1;
                    }
                    if ($item->statuspenyakit == 'BARU'  && $item->objectjeniskelaminfk == 1) {
                        $data10[$i]['jmlKasusBaruL'] = (float)$hideung['jmlKasusBaruL'] + 1;
                    } else if ($item->statuspenyakit == 'BARU' && $item->objectjeniskelaminfk == 2) {
                        $data10[$i]['jmlKasusBaruP'] = (float)$hideung['jmlKasusBaruP'] + 1;
                    }
                    $data10[$i]['totalKasusBaru'] = $data10[$i]['jmlKasusBaruP'] + $data10[$i]['jmlKasusBaruL'];

                    $data10[$i]['jmlPL'] = $data10[$i]['jml6HariL'] + $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariL'] + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnL'] + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnL'] + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnL'] + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnL'] + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnL'] + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnL'] + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnL'] + $data10[$i]['jml65ThnP'];
                    $data10[$i]['totalMenurutL'] = $data10[$i]['jml6HariL']
                        + $data10[$i]['jml28HariL']
                        + $data10[$i]['jml1ThnL']
                        + $data10[$i]['jml4ThnL']
                        + $data10[$i]['jml14ThnL']
                        + $data10[$i]['jml24ThnL']
                        + $data10[$i]['jml44ThnL']
                        + $data10[$i]['jml64ThnL']
                        + $data10[$i]['jml65ThnL'];
                    $data10[$i]['totalMenurutP'] = $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnP'];
                    $data10[$i]['totalKasusBaru'] = $data10[$i]['jmlKasusBaruP'] + $data10[$i]['jmlKasusBaruL'];

                    $data10[$i]['totalMenurutUmur'] = $data10[$i]['jml6HariL'] + $data10[$i]['jml6HariP']
                        + $data10[$i]['jml28HariL'] + $data10[$i]['jml28HariP']
                        + $data10[$i]['jml1ThnL'] + $data10[$i]['jml1ThnP']
                        + $data10[$i]['jml4ThnL'] + $data10[$i]['jml4ThnP']
                        + $data10[$i]['jml14ThnL'] + $data10[$i]['jml14ThnP']
                        + $data10[$i]['jml24ThnL'] + $data10[$i]['jml24ThnP']
                        + $data10[$i]['jml44ThnL'] + $data10[$i]['jml44ThnP']
                        + $data10[$i]['jml64ThnL'] + $data10[$i]['jml64ThnP']
                        + $data10[$i]['jml65ThnL'] + $data10[$i]['jml65ThnP'];
                }
                $i = $i + 1;
            }

            //jika false
            if ($sama == false) {
                if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 1;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday <= 6 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 1;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 1;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday >= 7 && $item->umurday <= 28 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 1;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 1;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umurday > 28 && $item->umuryear < 1 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 1;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 1;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 1 && $item->umuryear <= 4 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 1;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 1;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 5 && $item->umuryear <= 14 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 1;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 1;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 15 && $item->umuryear <= 24 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 1;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 1;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 25 && $item->umuryear <= 44 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 1;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 1;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 45 && $item->umuryear <= 64 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 1;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 65  && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 1;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                } else if ($item->umuryear >= 65 && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 1;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 0;
                }

                if ($item->statuspenyakit == 'BARU'  && $item->objectjeniskelaminfk == 1) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 1;
                    $jmlKasusBaruP = 0;
                } else if ($item->statuspenyakit == 'BARU' && $item->objectjeniskelaminfk == 2) {
                    $jml6HariL = 0;
                    $jml6HariP = 0;
                    $jml28HariL = 0;
                    $jml28HariP = 0;
                    $jml1ThnL = 0;
                    $jml1ThnP = 0;
                    $jml4ThnL = 0;
                    $jml4ThnP = 0;
                    $jml14ThnL = 0;
                    $jml14ThnP = 0;
                    $jml24ThnL = 0;
                    $jml24ThnP = 0;
                    $jml44ThnL = 0;
                    $jml44ThnP = 0;
                    $jml64ThnL = 0;
                    $jml64ThnP = 0;
                    $jml65ThnL = 0;
                    $jml65ThnP = 0;
                    $jmlKasusBaruL = 0;
                    $jmlKasusBaruP = 1;
                }

                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'nodtd' => $item->nodtd,
                    'golongansebabpenyakit' => $item->golongansebabpenyakit,
                    'tglregistrasi' => $item->tglregistrasi,
                    'statuspenyakit' => $item->statuspenyakit,
                    'tglLahir' => $item->tglLahir,
                    'umuryear' => $item->umuryear,
                    'umurmonth' => $item->umurmonth,
                    'umurday' => $item->umurday,
                    'totalAll' => 1,
                    'jml6HariL' =>  $jml6HariL,
                    'jml6HariP' =>  $jml6HariP,
                    'jml28HariL' =>  $jml28HariL,
                    'jml28HariP' =>  $jml28HariP,
                    'jml1ThnL' =>  $jml1ThnL,
                    'jml1ThnP' =>  $jml1ThnP,
                    'jml4ThnL' =>  $jml4ThnL,
                    'jml4ThnP' =>  $jml4ThnP,
                    'jml14ThnL' =>  $jml14ThnL,
                    'jml14ThnP' =>  $jml14ThnP,
                    'jml24ThnL' =>  $jml24ThnL,
                    'jml24ThnP' =>  $jml24ThnP,
                    'jml44ThnL' =>  $jml44ThnL,
                    'jml44ThnP' =>  $jml44ThnP,
                    'jml64ThnL' =>  $jml64ThnL,
                    'jml64ThnP' =>  $jml64ThnP,
                    'jml65ThnL' =>  $jml65ThnL,
                    'jml65ThnP' =>  $jml65ThnP,
                    'jmlKasusBaruL' => $jmlKasusBaruL,
                    'jmlKasusBaruP' => $jmlKasusBaruP,
                    'totalKasusBaru' => $totalKasusBaru,
                    'totalMenurutUmur' => $totalMenurutUmur,
                    'totalMenurutL' => $totalMenurutL,
                    'totalMenurutP' => $totalMenurutP,
                    'jmlPL' => $jmlPL,

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['nodtd'];
            }

            array_multisort($count, SORT_ASC, $data10);
        }

        $result = array(
            'data' => $data10,
            'dept' => $KdDeptRajalRehab,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL32RawatDarurat(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $kdDeptIGD = (int) $this->settingFix('idDepartemenIGD');
        $KdJenisLapIGD = (int) $this->settingFix('KdJenisLapIGD');
        $ruanganIGD = (int) $this->settingFix('kdRuanganIGD');

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftjoin('asalrujukan_m as ar', 'ar.id', '=', 'apd.objectasalrujukanfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pd.objectruanganasalfk as objectruanganasalfk ',
                'pd.objectruanganlastfk as objectruanganlastfk',
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'pr.objectdetailjenisprodukfk',
                'djp.detailjenisproduk',
                'apd.objectasalrujukanfk',
                'ar.asalrujukan',
                'pd.objectstatuskeluarfk',
                'kl.kelompoklaporan as jenispelayanan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('dpm.id', $kdDeptIGD)
            ->where('pr.objectdetailjenisprodukfk', '!=', 474)
            ->where('pd.statusenabled', true)
            ->where('pd.objectruanganasalfk', $ruanganIGD)
            ->where('jl.id', $KdJenisLapIGD)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

        $data10 = [];
        $jml = 0;
        $rujukan = 0;
        $nonrujukan = 0;
        $dirawat = 0;
        $dirujuk = 0;
        $pulang = 0;
        $mati = 0;
        // $doa = 0;
        $sama = false;


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenispelayanan == $data10[$i]['jenispelayanan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectasalrujukanfk == $this->settingFix('kdDatangSendiri')) {
                        $data10[$i]['nonrujukan'] = (float)$hideung['nonrujukan'] + 1;
                    } else if ($item->objectasalrujukanfk != $this->settingFix('kdDatangSendiri')) {
                        $data10[$i]['rujukan'] = (float)$hideung['rujukan'] + 1;
                    }
                    if ($item->objectruanganlastfk != $ruanganIGD) {
                        $data10[$i]['dirawat'] = (float)$hideung['dirawat'] + 1;
                    } else if ($item->objectstatuskeluarfk == $this->settingFix('kdRujuk')) {
                        $data10[$i]['dirujuk'] = (float)$hideung['dirujuk'] + 1;
                    } else if ($item->objectruanganlastfk == $ruanganIGD) {
                        $data10[$i]['pulang'] = (float)$hideung['pulang'] + 1;
                    } else if ($item->objectstatuskeluarfk == $this->settingFix('kdMeninggal') && $item->objectruanganlastfk == $ruanganIGD) {
                        $data10[$i]['mati'] = (float)$hideung['mati'] + 1;
                    } 
                    // else if ($item->objectstatuskeluarfk == 123456) {
                    //     $data10[$i]['doa'] = (float)$hideung['doa'] + 1;
                    // }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectasalrujukanfk == $this->settingFix('kdDatangSendiri')) {
                    $rujukan = 0;
                    $nonrujukan = 1;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $mati = 0;
                    // $doa = 0;
                } else if ($item->objectasalrujukanfk != $this->settingFix('kdDatangSendiri')) {
                    $rujukan = 1;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $mati = 0;
                    // $doa = 0;
                }
                if ($item->objectruanganlastfk != $ruanganIGD) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 1;
                    $dirujuk = 0;
                    $pulang = 0;
                    $mati = 0;
                    // $doa = 0;
                } else if ($item->objectstatuskeluarfk == $this->settingFix('kdRujuk')) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 1;
                    $pulang = 0;
                    $mati = 0;
                    // $doa = 0;
                } else if ($item->objectstatuskeluarfk == $this->settingFix('kdMeninggal') && $item->objectruanganlastfk == $ruanganIGD) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 0;
                    $mati = 1;
                    // $doa = 0;
                } else if ($item->objectruanganlastfk == $ruanganIGD) {
                    $rujukan = 0;
                    $nonrujukan = 0;
                    $dirawat = 0;
                    $dirujuk = 0;
                    $pulang = 1;
                    $mati = 0;
                    // $doa = 0;
                } 
                
                // else if ($item->objectstatuskeluarfk == 123456) {
                //     $rujukan = 0;
                //     $nonrujukan = 0;
                //     $dirawat = 0;
                //     $dirujuk = 0;
                //     $pulang = 0;
                //     $mati = 0;
                //     $doa = 1;
                // }

                $data10[] = array(
                    'jenispelayanan' => $item->jenispelayanan,
                    'jumlah' => 1,
                    'rujukan' => $rujukan,
                    'nonrujukan' => $nonrujukan,
                    'dirawat' => $dirawat,
                    'dirujuk' => $dirujuk,
                    'pulang' => $pulang,
                    'mati' => $mati,
                    // 'doa' => $doa,

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getKegiatanKesehatanGigidanMulut(Request $request)
    {
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $KdJenisLapGigiMulut = (int) $this->settingFix('KdJenisLapGigiMulut');
        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('produk_m as pr', 'pr.id', 'pp.produkfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', 'djp.objectjenisprodukfk')
            ->join('kelompokproduk_m as kp', 'kp.id', 'jp.objectkelompokprodukfk')
            ->join('strukpelayanan_t as sp', 'sp.norec', 'pp.strukfk')
            ->join('mapproduktolaporanrl_m as mpptrl', 'mpptrl.produkfk', 'pr.id')
            ->join('kelompoklaporan_m as kl', 'kl.id', 'mpptrl.objectkontenlaporanfk')
            ->join('jenislaporan_m as jl', 'jl.id', 'mpptrl.objectjenislaporanfk')
            ->select(
                'jl.jenislaporan',
                'kl.kelompoklaporan',
                (DB::raw('COUNT(mpptrl.objectkontenlaporanfk) as jumlah'))
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pd.statusenabled', true)
            ->where('pr.objectdetailjenisprodukfk', '!=', 474)
            ->where('jl.id', $KdJenisLapGigiMulut)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->groupBy('mpptrl.objectkontenlaporanfk', 'jl.jenislaporan', 'kl.kelompoklaporan')
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'kabayan',
        );

        return $this->respond($result);
    }

    public function getLaporanRL34Kebidanan(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];
        
        $KdJenisLapKebidanan = (int) $this->settingFix('KdJenisLapKebidanan');
        $KdRuangKebidanan = explode(',', $this->settingFix('KdRuanganKandungan'));

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'apd.objectasalrujukanfk')
            ->leftjoin('statuskeluar_m as stk', 'stk.id', '=', 'pd.objectstatuskeluarfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'apd.objectasalrujukanfk',
                'ar.asalrujukan',
                'pd.objectstatuskeluarfk',
                'apd.objectruanganfk',
                'kl.kelompoklaporan as jenispelayanan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapKebidanan)
            ->where('pr.objectdetailjenisprodukfk', '!=', 474)
            ->whereIn('apd.objectruanganfk', $KdRuangKebidanan)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();


        $data10 = [];
        $jml = 0;
        $rujukanRS = 0;
        $rujukanBidan = 0;
        $rujukanPuskes = 0;
        $rujukanFaskesLain = 0;
        $jmlMedisHidup = 0;
        $jmlMedisMati = 0;
        $jmlTotalMedis = 0;
        $jmlNonMedisHidup = 0;
        $jmlNonMedisMati = 0;
        $jmlTotalNonMedis = 0;
        $nonRujukanHidup = 0;
        $nonRujukanMati = 0;
        $totalNonRujukan = 0;
        $dirujuk = 0;
        $sama = false;

        $kdPulang = $this->settingFix('kdPulang');
        $kdPindah = $this->settingFix('kdPindah');
        $kdStatusRJ = $this->settingFix('kdStatusRJ');
        $kdRujuk = $this->settingFix('kdRujuk');
        $kdMeninggal = $this->settingFix('kdMeninggal');

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenispelayanan == $data10[$i]['jenispelayanan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    //RS=2, puskes=1, faskeslan=3, non rujikan =5, klinik=3
                    //statuskeluar --->1 pulang , 2 pindah,3 rajal, 4 rujuk, 5 meningggal


                    
                    if ($item->objectasalrujukanfk == $kdPindah) {
                        $data10[$i]['rujukanRS'] = (float)$hideung['rujukanRS'] + 1;
                    } else if ($item->objectasalrujukanfk == $kdPulang) {
                        $data10[$i]['rujukanPuskes'] = (float)$hideung['rujukanPuskes'] + 1;
                    } else if ($item->objectasalrujukanfk == $kdStatusRJ || $item->objectasalrujukanfk == $kdRujuk) {
                        $data10[$i]['rujukanFaskesLain'] = (float)$hideung['rujukanFaskesLain'] + 1;
                    }
                    if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdPulang) {
                        $data10[$i]['jmlMedisHidup'] = (float)$hideung['jmlMedisHidup'] + 1;
                    } else if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdMeninggal) {
                        $data10[$i]['jmlMedisMati'] = (float)$hideung['jmlMedisMati'] + 1;
                    }
                    if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdPulang) {
                        $data10[$i]['jmlNonMedisHidup'] = (float)$hideung['jmlNonMedisHidup'] + 1;
                    } else if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdMeninggal) {
                        $data10[$i]['jmlNonMedisMati'] = (float)$hideung['jmlNonMedisMati'] + 1;
                    }
                    if ($item->objectstatuskeluarfk == $kdRujuk) {
                        $data10[$i]['dirujuk'] = (float)$hideung['dirujuk'] + 1;
                    }

                    $data10[$i]['jmlTotalMedis'] = $data10[$i]['rujukanRS'] + $data10[$i]['rujukanPuskes'] + $data10[$i]['rujukanFaskesLain'] + $data10[$i]['jmlMedisHidup'] + $data10[$i]['jmlMedisMati'];
                    $data10[$i]['jmlTotalNonMedis'] = $data10[$i]['jmlNonMedisHidup'] + $data10[$i]['jmlNonMedisMati'];
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectasalrujukanfk == $kdPindah) {
                    $rujukanRS = 1;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if ($item->objectasalrujukanfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 1;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if ($item->objectasalrujukanfk == $kdStatusRJ || $item->objectasalrujukanfk == $kdRujuk) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 1;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 1;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdMeninggal) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 1;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 1;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdMeninggal) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 1;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if ($item->objectstatuskeluarfk == $kdRujuk) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 1;
                }

                $data10[] = array(
                    //                    'produkfk' => $item->produkfk,
                    'jenispelayanan' => $item->jenispelayanan,
                    'jumlah' => 1,

                    'rujukanRS' => $rujukanRS,
                    'rujukanFaskesLain' => $rujukanFaskesLain,
                    'rujukanPuskes' => $rujukanPuskes,
                    'rujukanBidan' => 0,
                    'jmlMedisHidup' => $jmlMedisHidup,
                    'jmlMedisMati' => $jmlMedisMati,
                    'jmlTotalMedis' => $jmlTotalMedis,
                    'jmlNonMedisHidup' => $jmlNonMedisHidup,
                    'jmlNonMedisMati' => $jmlNonMedisMati,
                    'jmlTotalNonMedis' => $jmlTotalNonMedis,
                    'nonRujukanHidup' => $nonRujukanHidup,
                    'nonRujukanMati' => $nonRujukanMati,
                    'totalNonRujukan' => $totalNonRujukan,
                    'dirujuk' => $dirujuk,
                    'jmlTotalMedis' => $jmlTotalMedis,
                    'jmlTotalNonMedis' => $jmlTotalNonMedis,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL35Perinatologi(Request $request)
    {

        $KdJenisLapPerinatologi = (int)$this->settingFix('KdJenisLapPerinatologi');
        // $KdRuangKebidanan = explode(',', $this->settingDataFixed('KdRuangKebidanan', $idProfile));
        $KdRuangKebidanan = explode(',', $this->settingFix('KdRuanganKandungan'));
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'apd.objectasalrujukanfk')
            ->leftjoin('statuskeluar_m as stk', 'stk.id', '=', 'pd.objectstatuskeluarfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'apd.objectasalrujukanfk',
                'ar.asalrujukan',
                'pd.objectstatuskeluarfk',
                'apd.objectruanganfk',
                'kl.kelompoklaporan as jenispelayanan'
            )
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('pr.objectdetailjenisprodukfk', '!=', 474)
            ->where('jl.id', $KdJenisLapPerinatologi) //laporan RL 35
            ->whereIn('apd.objectruanganfk', $KdRuangKebidanan)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();


        $data10 = [];
        $jml = 0;
        $rujukanRS = 0;
        $rujukanBidan = 0;
        $rujukanPuskes = 0;
        $rujukanFaskesLain = 0;
        $jmlMedisHidup = 0;
        $jmlMedisMati = 0;
        $jmlTotalMedis = 0;
        $jmlNonMedisHidup = 0;
        $jmlNonMedisMati = 0;
        $jmlTotalNonMedis = 0;
        $nonRujukanHidup = 0;
        $nonRujukanMati = 0;
        $totalNonRujukan = 0;
        $dirujuk = 0;
        $sama = false;

        $kdPulang = $this->settingFix('kdPulang');
        $kdPindah = $this->settingFix('kdPindah');
        $kdStatusRJ = $this->settingFix('kdStatusRJ');
        $kdRujuk = $this->settingFix('kdRujuk');
        $kdMeninggal = $this->settingFix('kdMeninggal');


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenispelayanan == $data10[$i]['jenispelayanan']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    //RS=2, puskes=1, faskeslan=3, non rujikan =5, klinik=3
                    //statuskeluar --->1 pulang , 2 pindah,3 rajal, 4 rujuk, 5 meningggal

                    if ($item->objectasalrujukanfk == $kdPindah) {
                        $data10[$i]['rujukanRS'] = (float)$hideung['rujukanRS'] + 1;
                    } else if ($item->objectasalrujukanfk == $kdPulang) {
                        $data10[$i]['rujukanPuskes'] = (float)$hideung['rujukanPuskes'] + 1;
                    } else if ($item->objectasalrujukanfk == $kdStatusRJ || $item->objectasalrujukanfk == $kdRujuk) {
                        $data10[$i]['rujukanFaskesLain'] = (float)$hideung['rujukanFaskesLain'] + 1;
                    }
                    if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdPulang) {
                        $data10[$i]['jmlMedisHidup'] = (float)$hideung['jmlMedisHidup'] + 1;
                    } else if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdMeninggal) {
                        $data10[$i]['jmlMedisMati'] = (float)$hideung['jmlMedisMati'] + 1;
                    }
                    if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdPulang) {
                        $data10[$i]['jmlNonMedisHidup'] = (float)$hideung['jmlNonMedisHidup'] + 1;
                    } else if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdMeninggal) {
                        $data10[$i]['jmlNonMedisMati'] = (float)$hideung['jmlNonMedisMati'] + 1;
                    }
                    if ($item->objectstatuskeluarfk == $kdRujuk) {
                        $data10[$i]['dirujuk'] = (float)$hideung['dirujuk'] + 1;
                    }

                    $data10[$i]['jmlTotalMedis'] = $data10[$i]['rujukanRS'] + $data10[$i]['rujukanPuskes'] + $data10[$i]['rujukanFaskesLain'] + $data10[$i]['jmlMedisHidup'] + $data10[$i]['jmlMedisMati'];
                    $data10[$i]['jmlTotalNonMedis'] = $data10[$i]['jmlNonMedisHidup'] + $data10[$i]['jmlNonMedisMati'];
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectasalrujukanfk == $kdPindah) {
                    $rujukanRS = 1;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if ($item->objectasalrujukanfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 1;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if ($item->objectasalrujukanfk == $kdStatusRJ || $item->objectasalrujukanfk == $kdRujuk) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 1;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 1;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if (($item->objectasalrujukanfk == $kdPindah || $item->objectasalrujukanfk == $kdPulang || $item->objectasalrujukanfk == $kdStatusRJ) && $item->objectstatuskeluarfk == $kdMeninggal) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 1;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdPulang) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 1;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                } else if (($item->objectasalrujukanfk == $kdMeninggal || $item->objectasalrujukanfk == $kdRujuk) && $item->objectstatuskeluarfk == $kdMeninggal) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 1;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 0;
                }
                if ($item->objectstatuskeluarfk == $kdRujuk) {
                    $rujukanRS = 0;
                    $rujukanBidan = 0;
                    $rujukanPuskes = 0;
                    $rujukanFaskesLain = 0;
                    $jmlMedisHidup = 0;
                    $jmlMedisMati = 0;
                    $jmlTotalMedis = 0;
                    $jmlNonMedisHidup = 0;
                    $jmlNonMedisMati = 0;
                    $jmlTotalNonMedis = 0;
                    $nonRujukanHidup = 0;
                    $nonRujukanMati = 0;
                    $totalNonRujukan = 0;
                    $dirujuk = 1;
                }

                $data10[] = array(
                    //                    'produkfk' => $item->produkfk,
                    'jenispelayanan' => $item->jenispelayanan,
                    'jumlah' => 1,

                    'rujukanRS' => $rujukanRS,
                    'rujukanFaskesLain' => $rujukanFaskesLain,
                    'rujukanPuskes' => $rujukanPuskes,
                    'rujukanBidan' => 0,
                    'jmlMedisHidup' => $jmlMedisHidup,
                    'jmlMedisMati' => $jmlMedisMati,
                    'jmlTotalMedis' => $jmlTotalMedis,
                    'jmlNonMedisHidup' => $jmlNonMedisHidup,
                    'jmlNonMedisMati' => $jmlNonMedisMati,
                    'jmlTotalNonMedis' => $jmlTotalNonMedis,
                    'nonRujukanHidup' => $nonRujukanHidup,
                    'nonRujukanMati' => $nonRujukanMati,
                    'totalNonRujukan' => $totalNonRujukan,
                    'dirujuk' => $dirujuk,

                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL36Pembedahan(Request $request)
    {

        $KdJenisLapPembedahan = (int) $this->settingFix('KdJenisLapPembedahan');
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        //        $dataLogin = $request->all();
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            ->join('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            ->leftJoin('kelompokproduk_m as kp', 'kp.id', '=', 'jp.objectkelompokprodukfk')
            ->select(
                'pp.tglpelayanan',
                'pp.produkfk',
                'pr.namaproduk',
                'pr.objectdetailjenisprodukfk',
                'djp.detailjenisproduk',
                'kl.kelompoklaporan as spesialisasi'
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('pr.objectdetailjenisprodukfk', '!=', 474)
            ->where('jl.id', $KdJenisLapPembedahan)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();
    
        $data10 = [];
        $jml = 0;
        $khusus = 0;
        $besar = 0;
        $sedang = 0;
        $kecil = 0;
        $lainnya = 0;

        $sama = false;

        $KdKelompokTindakanBesar = explode(',',$this->settingFix('KdKelompokTindakanBesar'));
        $KdKelompokTindakanKhusus = explode(',',$this->settingFix('KdKelompokTindakanKhusus'));
        $KdKelompokTindakanSedang = explode(',',$this->settingFix('KdKelompokTindakanSedang'));
        $KdKelompokTindakanKecil =  explode(',',$this->settingFix('KdKelompokTindakanKecil'));


        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->spesialisasi == $data10[$i]['spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanBesar)) {
                        $data10[$i]['besar'] = (float)$hideung['besar'] + 1;
                    } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKhusus)) {
                        $data10[$i]['khusus'] = (float)$hideung['khusus'] + 1;
                    } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanSedang)) {
                        $data10[$i]['sedang'] = (float)$hideung['sedang'] + 1;
                    } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKecil)) {
                        $data10[$i]['kecil'] = (float)$hideung['kecil'] + 1;
                    }
                
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanBesar)) {
                    $khusus = 0;
                    $besar = 1;
                    $sedang = 0;
                    $kecil = 0;
                    $lainnya = 0;
                } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKhusus)) {
                    $khusus = 1;
                    $besar = 0;
                    $sedang = 0;
                    $kecil = 0;
                    $lainnya = 0;
                } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanSedang)) {
                    $khusus = 0;
                    $besar = 0;
                    $sedang = 1;
                    $kecil = 0;
                    $lainnya = 0;
                } else if (in_array($item->objectdetailjenisprodukfk,$KdKelompokTindakanKecil)) {
                    $khusus = 0;
                    $besar = 0;
                    $sedang = 0;
                    $kecil = 1;
                    $lainnya = 0;
                }


                $data10[] = array(
                    'spesialisasi' => $item->spesialisasi,
                    'jumlah' => 1,
                    'khusus' => $khusus,
                    'besar' => $besar,
                    'sedang' => $sedang,
                    'kecil' => $kecil,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getLaporanRL37Radiologi(Request $request)
    {

        $KdJenisLapRadiologi = (int) $this->settingFix('KdJenisLapRadiologi');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        //////With Mapping****
        $data = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'apd.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mpptrl', 'mpptrl.produkfk', '=', 'pr.id')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mpptrl.objectkontenlaporanfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mpptrl.objectjenislaporanfk')
            ->select(
                'jl.jenislaporan',
                'kl.kelompoklaporan',
                (DB::raw('COUNT(mpptrl.objectkontenlaporanfk) as jumlah'))
            )
            ->where('apd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapRadiologi)
            ->where('pr.objectdetailjenisprodukfk', '!=', 474)
            ->groupBy('mpptrl.objectkontenlaporanfk', 'jl.jenislaporan', 'kl.kelompoklaporan')
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'er@epic',

        );

        return $this->respond($result);
    }

    public function getPemeriksaanLab(Request $request)
    {

        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', 'mprl.objectkontenlaporanfk')
            ->select(
                'ru.objectdepartemenfk',
                'dpm.namadepartemen',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.produkfk',
                'prd.id as idproduk',
                'kl.kelompoklaporan as namaproduk',
                DB::raw('
                COUNT(prd.namaproduk) as jmlProduk')
            )
            ->groupBy(
                'ru.objectdepartemenfk',
                'dpm.namadepartemen',
                'apd.objectruanganfk',
                'ru.namaruangan',
                'pp.produkfk',
                'prd.id',
                'prd.namaproduk',
                'kl.kelompoklaporan'
            )
            ->where('ru.objectdepartemenfk', $this->settingFix('idDepartemenLab'))
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('prd.objectdetailjenisprodukfk', '!=', 474)
            ->where('jl.id', $this->settingFix('KdJenisLapLaborat'))
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();


        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getPelayananRehab(Request $request)
    {
        $KdJenisLapRehab = $this->settingFix('KdJenisLapRehabMedik');
        $kdDepartemenRehabMedik = $this->settingFix('kdDepartemenRehabMedik');

        $dateRange = [$request->tglAwal, $request->tglAkhir];
        //#WIth Mapp
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk','apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', 'mprl.objectkontenlaporanfk')
            ->select(
                'kl.kelompoklaporan as namaproduk',
                DB::raw('COUNT(prd.namaproduk) as JmlTindakan')
            )
            ->groupBy('kl.kelompoklaporan')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('prd.objectdetailjenisprodukfk', '!=', 474)
            ->where('ru.objectdepartemenfk', $KdJenisLapRehab)
             ->where('jl.id', $kdDepartemenRehabMedik)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL310Khusus(Request $request)
    {
        $KdJenisLapPelKhusus = (int) $this->settingFix('KdJenisLapPelayananKhusus');
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        //#WIth Mapp
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', 'ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', 'mprl.objectkontenlaporanfk')
            ->select(
                'kl.kelompoklaporan as namaproduk',
                DB::raw('COUNT(prd.namaproduk) as JmlTindakan'))
            ->groupBy('kl.kelompoklaporan')
            ->where('prd.objectdetailjenisprodukfk', '!=', 474)
            //            ->where('ru.objectdepartemenfk', '=', '28')
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('jl.id', $KdJenisLapPelKhusus)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL311KesehatanJiwa(Request $request)
    {
   
        $KdJenisLapKesehatanJiwa = (int) $this->settingFix('KdJenisLapKesehatanJiwa');
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $kdRuanganJiwa = $this->settingFix('kdRuanganJiwa');
        //#WIth Mapp
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk','pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id','apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id','ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id','pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk','pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id','mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id','mprl.objectkontenlaporanfk')
            ->select(
                'kl.kelompoklaporan as namaproduk',
                DB::raw('
                 COUNT(prd.namaproduk) as jmltindakan')
            )
            ->groupBy('kl.kelompoklaporan')
            ->where('prd.objectdetailjenisprodukfk', '!=', 474)
            ->where('pd.kdprofile', $this->kdProfile)
            ->where('ru.id', $kdRuanganJiwa)
            ->where('jl.id', $KdJenisLapKesehatanJiwa)
            ->whereBetween(DB::raw("CAST(pp.tglpelayanan as Date)"), $dateRange)
            ->get();


        $result = array(
            'data' => $data,
            'message' => 'as@cepot',
        );
        return $this->respond($result);
    }

    public function getLaporanRL312KeluargaBerencana(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdJenisLapKB = (int) $this->settingFix('KdJenisLapKB');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
            ->leftjoin('pelayananpasien_t as pp', 'pp.noregistrasifk', '= ', 'apd.norec')
            ->join('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->select(
                'kl.kelompoklaporan as namaproduk',
                DB::raw('
                 COUNT(prd.namaproduk) as jmltindakan')
            )
            ->groupBy('kl.kelompoklaporan')
            ->where('pd.kdprofile', $kdProfile)
            ->where('jl.id', $KdJenisLapKB)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $dateRange)
            ->get();

        $result = array(
            'data' => $data,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    
    public function getRL314Rujukan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdJenisLapRujukan = (int) $this->settingFix('KdJenisLapRujukan');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        $data = DB::table('antrianpasiendiperiksa_t as app')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('pelayananpasien_t as pp', 'pp.noregistrasifk', '=', 'app.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'pp.produkfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('asalrujukan_m as ar', 'ar.id', '=', 'app.objectasalrujukanfk')
            ->join('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'pd.objectpegawaifk')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'pm.objectjeniskelaminfk')
            ->leftJoin('statuspulang_m as splng', 'splng.id', '=', 'pd.objectstatuspulangfk')
            ->leftJoin('statuskeluar_m as sklr', 'sklr.id', '=', 'pd.objectstatuskeluarfk')
            ->select(
                'pd.tglregistrasi',
                'pm.nocm',
                'pd.noregistrasi',
                'pm.namapasien',
                'app.objectasalrujukanfk',
                'ar.asalrujukan',
                'ru.namaruangan',
                'dpm.namadepartemen',
                'pg.namalengkap',
                'pm.objectjeniskelaminfk',
                'pd.objectstatuspulangfk',
                'splng.statuspulang',
                'sklr.statuskeluar',
                'kl.kelompoklaporan as jenis_spesialisasi'
            )
            ->where('app.kdprofile', $kdProfile)
            // ->where('jl.id', $KdJenisLapRujukan)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $dateRange);

        $data = $data->get();

        $data10 = [];
        $jml = 0;
        $sama = false;

        $DatangSendiri = 0;
        $FasilitasLain = 0;
        $Puskesmas = 0;
        $RsLain = 0;
        $PasienRujukan = 0;
        $DiterimaKembali = 0;

        $BackToPuskesmas = 0;
        $BackToRsAsal = 0;
        $BackToKlinik = 0;
        $BackToRs = 0;

        $kdPuskesmas = $this->settingFix('kdPuskesmas');
        $kdRumahSakit = $this->settingFix('kdRumahSakit');
        $kdDatangSendiri = $this->settingFix('kdDatangSendiri');
        $kdKlinik = $this->settingFix('kdKlinik');
        $kdPasRujukan = $this->settingFix('kdRujukanLain');
        $kdKembaliPuskes = $this->settingFix('kdPuskesmasAsal');
        $kdKembaliKlinik = $this->settingFix('kdKlinikAsal');
        $kdKembaliRS = $this->settingFix('kdRsAsal');
        $kdDiterimaKembali = $this->settingFix('kdDiterimaKembali');
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->jenis_spesialisasi == $data10[$i]['jenis_spesialisasi']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;

                    if ($item->objectasalrujukanfk == $kdDatangSendiri) {
                        $data10[$i]['dtgsendiri'] = (float)$hideung['dtgsendiri'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdPuskesmas) {
                        $data10[$i]['puskesmas'] = (float)$hideung['puskesmas'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdRumahSakit) {
                        $data10[$i]['hospital'] = (float)$hideung['hospital'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdKlinik) {
                        $data10[$i]['klinik'] = (float)$hideung['klinik'] + 1;
                    } elseif ($item->objectasalrujukanfk == $kdPasRujukan) {
                        $data10[$i]['PasienRujukan'] = (float)$hideung['PasienRujukan'] + 1;
                    }
                    if ($item->objectasalrujukanfk == $kdDiterimaKembali) {
                        $data10[$i]['DTrmaKembali'] = (float)$hideung['DTrmaKembali'] + 1;
                    }
                    if ($item->objectstatuspulangfk == $kdKembaliRS) {
                        $data10[$i]['DKembalikanKRs'] = (float)$hideung['DKembalikanKRs'] + 1;
                    }
                    if ($item->objectstatuspulangfk == $kdKembaliPuskes) {
                        $data10[$i]['DKembalikanKPuskes'] = (float)$hideung['DKembalikanKPuskes'] + 1;
                    }
                    if ($item->objectstatuspulangfk == $kdKembaliKlinik) {
                        $data10[$i]['DKembalikanKKlinik'] = (float)$hideung['DKembalikanKKlinik'] + 1;
                    }
                    //                    $data10[$i]['total'] = $data10[$i]['jmlBaruL'] + $data10[$i]['jmlBaruP'];
                }
                $i = $i + 1;
            }

            if ($sama == false) {

                if ($item->objectasalrujukanfk == $kdDatangSendiri) {
                    $DatangSendiri = 1;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdPuskesmas) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 1;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdRumahSakit) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 1;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdKlinik) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 1;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdDiterimaKembali) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 1;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectasalrujukanfk == $kdPasRujukan) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 1;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectstatuspulangfk == $kdKembaliRS) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 1;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectstatuspulangfk == $kdKembaliPuskes) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 1;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 0;
                    $BackToRs = 0;
                } elseif ($item->objectstatuspulangfk == $kdKembaliKlinik) {
                    $DatangSendiri = 0;
                    $FasilitasLain = 0;
                    $Puskesmas = 0;
                    $RsLain = 0;
                    $PasienRujukan = 0;
                    $DiterimaKembali = 0;

                    $BackToPuskesmas = 0;
                    $BackToRsAsal = 0;
                    $BackToKlinik = 1;
                    $BackToRs = 0;
                }

                $data10[] = array(
                    'jenis_spesialisasi' => $item->jenis_spesialisasi,
                    'DTrmaKembali' => $DiterimaKembali,
                    'dtgsendiri' => $DatangSendiri,
                    'puskesmas' => $Puskesmas,
                    'hospital' => $RsLain,
                    'klinik' => $FasilitasLain,
                    'PasienRujukan' => $PasienRujukan,
                    'DKembalikanKRs' => $BackToRsAsal,
                    'DKembalikanKPuskes' => $BackToPuskesmas,
                    'DKembalikanKKlinik' => $BackToKlinik,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }

        $result = array(
            'data' => $data10,
            'message' => 'ea@epic',
        );
        return $this->respond($result);
    }

    public function getRL315CaraBayar(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $KdJenisLapCaraBayar = $this->settingFix('KdJenisLapCaraBayar');
        $dateRange = [$request->tglAwal, $request->tglAkhir];
        $kdRanap = $this->settingFix('idDepRawatInap');
        $kdDepRad = $this->settingFix('KdInstalasiRadiologi');
        $kdDepLab = $this->settingFix('kdDepRadiologi');

        $data = DB::table('pasiendaftar_t as pd')
            ->join('pasien_m as p', 'p.id', 'pd.nocmfk')
            ->join('antrianpasiendiperiksa_t as apd', 'pd.norec', 'apd.noregistrasifk')
            ->leftJoin('strukbuktipenerimaan_t as sbm', 'sbm.norec', 'pd.nosbmlastfk')
            ->leftJoin('strukbuktipenerimaancarabayar_t as sbmcb', 'sbmcb.nosbmfk', 'sbm.norec')
            ->leftJoin('ruangan_m as ru', 'ru.id', 'apd.objectruanganfk')
            ->leftJoin('kelompokpasien_m as kp', 'kp.id', 'pd.objectkelompokpasienlastfk')
            ->leftJoin('departemen_m as dept', 'dept.id', 'ru.objectdepartemenfk')
            ->leftJoin('jeniskelamin_m as jk', 'jk.id', 'p.objectjeniskelaminfk')
            ->leftJoin('kelas_m as kel', 'kel.id', 'pd.objectkelasfk')
            ->leftJoin('carabayar_m as cb', 'cb.id', 'sbmcb.objectcarabayarfk')
            ->select(
                'pd.tglregistrasi',
                'p.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'p.namapasien',
                'kp.id',
                'kp.kelompokpasien as kdekelompokpasien',
                'pd.tglpulang as tglpulang',
                'pd.statuspasien',
                'jk.jeniskelamin',
                'p.tgllahir as tglLahir',
                'kel.namakelas',
                'pd.nosbmlastfk',
                'cb.carabayar',
                'ru.objectdepartemenfk as kddepartemen',
                DB::raw('EXTRACT(YEAR FROM current_date) - EXTRACT(YEAR FROM tgllahir) as umur,
                        (case when kp.id = 2 then \'Asuransi BPJS\'
                         when kp.id in (3, 5) then \'Asuransi Swasta\'
                         when kp.id = 6 then \'Perjanjian\'
                         when kp.id = 1 then \'Membayar Sendiri\'
                         else null end) as golbayar,
                         (case when kp.id = 2 then \'2.1\'
                          when kp.id in (3, 5) then \'2.2\'
                          when kp.id = 6 then \'3\'
                          when kp.id = 1 then \'1\'
                          else null end) as idbayar')

            )
            ->groupBy(
                'pd.tglregistrasi',
                'p.nocm',
                'pd.noregistrasi',
                'ru.namaruangan',
                'p.namapasien',
                'kp.kelompokpasien',
                'pd.tglpulang',
                'pd.statuspasien',
                'jk.jeniskelamin',
                'p.tgllahir',
                'pd.nosbmlastfk',
                'cb.carabayar',
                'kel.namakelas',
                'kp.id',
                'ru.objectdepartemenfk'
            )
            ->where('pd.kdprofile', $kdProfile)
            ->whereBetween(DB::raw("CAST(pd.tglregistrasi as Date)"), $dateRange)
            ->get();

        $data10 = [];
        $jml = 0;
        $sama = false;
        $pasienKeluarRI = 0;
        $pasienDirawatRI = 0;
        $pasienRawatJalan = 0;
        $pasienLab = 0;
        $pasienRad = 0;
        $pasienLain = 0;

        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->golbayar == $data10[$i]['golbayar']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    //                  Pasien RI Masih Dirawat
                    if ($item->tglpulang == null && $item->kddepartemen == $kdRanap) {
                        $data10[$i]['pasienDirawatRI'] = (float)$hideung['pasienDirawatRI'] + 1;
                    } else if ($item->tglpulang != null && $item->kddepartemen == $kdRanap) {
                        $data10[$i]['pasienKeluarRI'] = (float)$hideung['pasienKeluarRI'] + 1;
                    }
                    else if ($item->kddepartemen == $kdDepRad) {
                        $data10[$i]['pasienRad'] = (float)$hideung['pasienRad'] + 1;
                    } else if ($item->kddepartemen == $kdDepLab) {
                        $data10[$i]['pasienLab'] = (float)$hideung['pasienLab'] + 1;
                    } else if ($item->kddepartemen != $kdDepLab && $item->kddepartemen != $kdDepRad && $item->kddepartemen != $kdRanap) {
                        $data10[$i]['pasienLain'] = (float)$hideung['pasienLain'] + 1;
                    }
                    $data10[$i]['pasienRawatJalan'] = $data10[$i]['pasienLain'] + $data10[$i]['pasienRad'] + $data10[$i]['pasienLab'];
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                $data10[] = array(
                    'golbayar' => $item->golbayar,
                    'idbayar' => $item->idbayar,
                    'pasienKeluarRI' => $pasienKeluarRI,
                    'pasienDirawatRI' => $pasienDirawatRI,
                    'pasienRawatJalan' => $pasienRawatJalan,
                    'pasienLab' => $pasienLab,
                    'pasienRad' => $pasienRad,
                    'pasienLain' => $pasienLain,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['idbayar'];
            }

            array_multisort($count, SORT_ASC, $data10);
        }

        $result = array(
            'data' => $data10,
            'message' => 'er@epic',
        );
        return $this->respond($result);
    }

    public function getPengadaanObat(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $KdJenisLapFarmasi = (int) $this->settingFix('KdJenisLapFarmasi');
        $dateRange = [$request->tglAwal, $request->tglAkhir];

        //    #with Mapping
        $data = DB::table('produk_m as prd')
            ->join('stokprodukdetail_t as spd', 'spd.objectprodukfk', '=', 'prd.id')
            ->join('mapproduktolaporanrl_m as mprl', 'mprl.produkfk', '=', 'spd.objectprodukfk')
            ->join('jenislaporan_m as jl', 'jl.id', '=', 'mprl.objectjenislaporanfk')
            ->join('kelompoklaporan_m as kl', 'kl.id', '=', 'mprl.objectkontenlaporanfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'spd.objectruanganfk')
            ->join('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'prd.namaproduk',
                'kl.kelompoklaporan as detailobat',
                DB::raw('count(prd.id) as jumlahitem,sum(spd.qtyproduk) as jumlahtersedia')
            )
            ->groupBy('prd.namaproduk', 'prd.id', 'kl.kelompoklaporan')
            ->where('prd.kdprofile', $kdProfile)
            ->where('prd.objectdetailjenisprodukfk', 474)
            // ->where('jl.id', $KdJenisLapFarmasi)
            ->whereBetween(DB::raw("CAST(spd.tglpelayanan as Date)"), $dateRange)
            ->get();

        $data10 = [];
        foreach ($data as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->detailobat == $data10[$i]['detailobat']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                }
                $i = $i + 1;
            }

            if ($sama == false) {

                $data10[] = array(
                    'detailobat' => $item->detailobat,
                    'jumlahtersedia' => $item->jumlahtersedia,
                    'jumlahfortersedia' => 0,
                    'jumlah' => 1,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }
            array_multisort($count, SORT_DESC, $data10);
        }
        $result = array(
            'data' => $data10,
            'message' => 'as@lancelot',
        );
        return $this->respond($result);
    }


    public function getDataLaporanRL51Kujungan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('pasiendaftar_t as pd')
            ->leftjoin('pasien_m as pm', 'pm.id', '=', 'pd.nocmfk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->select(DB::raw('count(pd.norec) as jumlah,pd.statuspasien,ru.objectdepartemenfk'))
            ->where('pd.kdprofile', $kdProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $request['tglAkhir']);
        }
        if (isset($request['departementfk']) && $request['departementfk'] != "" && $request['departementfk'] != "undefined") {
            $data = $data->where('ru.objectdepartemenfk ', '=', $request['departementfk']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        $data = $data->whereNull('pd.tanggalpembatalan');
        $data = $data->groupBy('pd.statuspasien', 'ru.objectdepartemenfk');
        $data = $data->get();
        $result = array(
            'data' => $data,
            'message' => 'success',
        );
        return $this->respond($result);
    }


    public function getDataLaporanRL52KunjuanRawatJalan(Request $request)
    {
        $kdProfile = (int) $this->kdProfile;
        $data = DB::table('pasiendaftar_t as pd')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'pd.objectruanganlastfk')
            ->LEFTJOIN('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->LEFTJOIN('kelompokpasien_m as kp', 'kp.id', '=', 'pd.objectkelompokpasienlastfk')
            ->LEFTJOIN('departemen_m as dept', 'dept.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'ru.namaruangan',
                DB::raw('COUNT(ru.namaruangan) as jumlahKunjungan')
            )
            ->where('pd.kdprofile', $kdProfile);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $data = $data->where('pd.tglregistrasi', '<=', $request['tglAkhir']);
        }
        if (isset($request['departemenfk']) && $request['departemenfk'] != "" && $request['departemenfk'] != "undefined") {
            $data = $data->where('dpm.id', '=', $request['departemenfk']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('ru.id', '=', $request['ruanganfk']);
        }
        $data = $data->groupBy('dept.namadepartemen', 'ru.namaruangan');
        $data = $data->orderBy('ru.namaruangan', 'ASC');
        $data = $data->get();

        $result = array(
            'data' => $data,
            'message' => 'success',
        );

        return $this->respond($result);
    }
    public function getDataLaporanRL53PenyakitaRawatInap(Request $request)
    {
        $kdProfile      = (int) $this->kdProfile;
        $kdDeptRanapAll = explode(',', $this->settingFix('KdDepartemenRIAll'));
        $datadiagnosa = DB::table('antrianpasiendiperiksa_t as app')
            ->leftJoin('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->leftJoin('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->join('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->leftJoin('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->leftJoin('ruangan_m as ru', 'ru.id', '=', 'app.objectruanganfk')
            ->leftJoin('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->select(
                'dm.kddiagnosa',
                'dm.namadiagnosa',
                'pd.noregistrasi',
                'pd.objectstatuskeluarfk',
                'ps.objectjeniskelaminfk',
                'jk.jeniskelamin',
                'pd.tglregistrasi',
                'pd.tglpulang',
                'pd.tglmeninggal',
                'ru.reportdisplay',
                'app.noregistrasifk as noregistrasifk',
                'pd.statuspasien',
                'dpm.reportdisplay',
                'dpm.id'
            )
            ->where('app.kdprofile', $kdProfile)
            ->whereIn('dpm.id', $kdDeptRanapAll);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('pd.tglregistrasi', '>=', $request['tglAwal']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('app.objectruanganfk', '=', $request['idRuangan']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $datadiagnosa = $datadiagnosa->where('pd.tglregistrasi', '<=', $tgl);
        }
        $datadiagnosa = $datadiagnosa->get();

        $data10 = [];
        $jml = 0;
        $jmlM = 0;
        $jmlL = 0;
        $jmlLM = 0;
        $sama = false;
        $jmlP = 0;
        $jmlPM = 0;
        $jmlHidupMati = 0;
        foreach ($datadiagnosa as $item) {
            $sama = false;
            $i = 0;
            $o = 0;
            foreach ($data10 as $hideung) {
                if ($item->kddiagnosa == $data10[$i]['kddiagnosa']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectjeniskelaminfk == 1 && ($item->objectstatuskeluarfk != 5 || $item->objectstatuskeluarfk == '')) {
                        $data10[$i]['jumlahLKH'] = (float)$hideung['jumlahLKH'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && ($item->objectstatuskeluarfk != 5  || $item->objectstatuskeluarfk == '')) {
                        $data10[$i]['jumlahPRH'] = (float)$hideung['jumlahPRH'] + 1;
                    } else if ($item->objectjeniskelaminfk == 1 && $item->objectstatuskeluarfk == 5) {
                        $data10[$i]['jumlahLKM'] = (float)$hideung['jumlahLKM'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && $item->objectstatuskeluarfk == 5) {
                        $data10[$i]['jumlahPRM'] = (float)$hideung['jumlahPRM'] + 1;
                    }
                }
                $i = $i + 1;
            }

            if ($sama == false) {
                if ($item->objectjeniskelaminfk == 1 && $item->objectstatuskeluarfk == 1) {
                    $jmlL = 1;
                    $jmlP = 0;
                    $jmlPM = 0;
                    $jmlLM = 0;
                } else if ($item->objectjeniskelaminfk == 2 && $item->objectstatuskeluarfk == 1) {
                    $jmlL = 0;
                    $jmlP = 1;
                    $jmlPM = 0;
                    $jmlLM = 0;
                } else if ($item->objectjeniskelaminfk == 1 && $item->objectstatuskeluarfk == 5) {
                    $jmlL = 0;
                    $jmlP = 0;
                    $jmlPM = 0;
                    $jmlLM = 1;
                } else if ($item->objectjeniskelaminfk == 2 && $item->objectstatuskeluarfk == 5) {
                    $jmlL = 0;
                    $jmlP = 0;
                    $jmlLM = 0;
                    $jmlPM = 1;
                }

                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'namadiagnosa' => $item->namadiagnosa,
                    'jumlah' => 1,
                    'jumlahLKH' => $jmlL,
                    'jumlahPRH' => $jmlP,
                    'jumlahLKM' => $jmlLM,
                    'jumlahPRM' => $jmlPM,
                );
            }

            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }

            array_multisort($count, SORT_DESC, $data10);
        }
        $result = [
            'data' => $data10,
            'message' => 'success',

        ];

        return $this->respond($result);
    }
    public function getDataLaporanRL54PenyakitaRawatJalan(Request $request){
        $kdProfile = (int) $this->kdProfile;
        $KdDeptRajalAll = explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $datadiagnosa = DB::table('antrianpasiendiperiksa_t as app')
            ->LEFTJOIN('diagnosapasien_t as dp', 'dp.noregistrasifk', '=', 'app.norec')
            ->LEFTJOIN('detaildiagnosapasien_t as ddp', 'ddp.objectdiagnosapasienfk', '=', 'dp.norec')
            ->JOIN('diagnosa_m as dm', 'ddp.objectdiagnosafk', '=', 'dm.id')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'app.noregistrasifk')
            ->JOIN('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->LeftJOIN('jeniskelamin_m as jk', 'ps.objectjeniskelaminfk', '=', 'jk.id')
            ->LeftJOIN('ruangan_m as ru', 'ru.id', '=', 'app.objectruanganfk')
            ->LeftJOIN('departemen_m as dpm', 'dpm.id', '=', 'ru.objectdepartemenfk')
            ->select('dm.kddiagnosa', 'dm.namadiagnosa','app.statuspenyakit', 'pd.noregistrasi', 'ps.objectjeniskelaminfk', 'jk.jeniskelamin',
                'pd.tglregistrasi', 'pd.tglpulang', 'pd.tglmeninggal', 'ru.reportdisplay', 'app.noregistrasifk as noregistrasifk','pd.statuspasien',
                'dpm.reportdisplay', 'dpm.id')
            ->where('app.kdprofile', $kdProfile)
            ->whereIn('ru.objectdepartemenfk',$KdDeptRajalAll);

        if (isset($request['tglAwal']) && $request['tglAwal'] != "" && $request['tglAwal'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('pd.tglpulang', '>=', $request['tglAwal']);
        }
        if (isset($request['idRuangan']) && $request['idRuangan'] != "" && $request['idRuangan'] != "undefined") {
            $datadiagnosa = $datadiagnosa->where('app.objectruanganfk', '=', $request['idRuangan']);
        }
        if (isset($request['tglAkhir']) && $request['tglAkhir'] != "" && $request['tglAkhir'] != "undefined") {
            $tgl = $request['tglAkhir'];
            $datadiagnosa = $datadiagnosa->where('pd.tglpulang', '<=', $tgl);
        }
        $datadiagnosa = $datadiagnosa->get();

        $data10 = [];
        $sama = false;
        $jml = 0;
        $jmlL = 0;
        $jmlP = 0;
        $jmlLL = 0;
        $jmlPL = 0;
        foreach ($datadiagnosa as $item) {
            $sama = false;
            $i = 0;
            foreach ($data10 as $hideung) {
                if ($item->kddiagnosa == $data10[$i]['kddiagnosa']) {
                    $sama = true;
                    $jml = (float)$hideung['jumlah'] + 1;
                    $data10[$i]['jumlah'] = $jml;
                    if ($item->objectjeniskelaminfk == 1 && $item->statuspasien == 'BARU') {
                        $data10[$i]['jumlahLKH'] = (float)$hideung['jumlahLKH'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && $item->statuspasien == 'BARU')  {
                        $data10[$i]['jumlahPRH'] = (float)$hideung['jumlahPRH'] + 1;
                    }
                    else if ($item->objectjeniskelaminfk == 1 && $item->statuspasien == 'LAMA') {
                        $data10[$i]['jumlahLKL'] = (float)$hideung['jumlahLKL'] + 1;
                    } else if ($item->objectjeniskelaminfk == 2 && $item->statuspasien == 'LAMA')  {
                        $data10[$i]['jumlahPRL'] = (float)$hideung['jumlahPRL'] + 1;
                    }
                    $data10[$i]['totalbaru'] = $data10[$i]['jumlahLKH'] + $data10[$i]['jumlahPRH'];
                    $data10[$i]['totallama'] = $data10[$i]['jumlahLKL'] + $data10[$i]['jumlahPRL'];
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                if ($item->objectjeniskelaminfk == 1 && $item-> statuspasien == 'BARU') {
                    $jmlL = 1;
                    $jmlP = 0;
                } else if ($item->objectjeniskelaminfk == 2 && $item->statuspasien == 'BARU')  {
                    $jmlL = 0;
                    $jmlP = 1;
                }

                $data10[] = array(
                    'kddiagnosa' => $item->kddiagnosa,
                    'namadiagnosa' => $item->namadiagnosa,
                    'jumlah' => 1,
                    'jumlahLKH' => $jmlL,
                    'jumlahPRH' => $jmlP,
                    'jumlahLKL' => $jmlLL,
                    'jumlahPRL' => $jmlPL,
                    'totalbaru' => $jmlL + $jmlP,
                    'totallama' => $jmlLL + $jmlPL,
                );
            }


            foreach ($data10 as $key => $row) {
                $count[$key] = $row['jumlah'];
            }
            array_multisort($count, SORT_DESC, $data10);
        }

        $result = array(
            'data' => $data10,
            'message' => 'as@vandrian',

        );
        return $this->respond($result);
    }

}
