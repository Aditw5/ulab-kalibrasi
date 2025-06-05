<?php

namespace App\Http\Controllers\Antrian;

use App\Http\Controllers\Controller;
use App\Models\Master\Profile;
use App\Models\Transaksi\AntrianPasienRegistrasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use App\Traits\Valet;
use Carbon\Carbon;
use Exception;

class AntrianCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct(true);
    }
    public function getListAntrian(Request $r)
    {
        $now = date('Y-m-d');
        $kdProfile = $this->kdProfile;
        $data = DB::select(DB::raw("select jenis, count(noantrian) as last
             from antrianpasienregistrasi_t
             where statuspanggil ='0'
             and tanggalreservasi between '$now 00:00' and '$now 23:59'
             and kdprofile = $kdProfile
             group by jenis order by jenis"));
        $data2 = DB::select(DB::raw("select jenis, max(noantrian) as last
             from antrianpasienregistrasi_t
             where statuspanggil !='0'
             and statuspanggil !=''
             and tanggalreservasi between '$now 00:00' and '$now 23:59'
             and kdprofile = $kdProfile
             group by jenis order by jenis"));

        $jenisAntrian = DB::table('slottingkiosk_m as sk')
        ->select('sk.*', 'ru.namaruangan', 'ru.noruangan')
        ->join('ruangan_m as ru', 'ru.id', '=', 'sk.objectruanganfk')
        ->where('sk.statusenabled', true)
        ->where('sk.kdprofile', $kdProfile)
        ->where('sk.loket', $r['loket'])
        ->where('sk.tanggal', $now)
        ->whereNotNull('ru.noruangan')
        ->get();
        $respond = [];
        foreach ($jenisAntrian as $item) {
            array_push($respond, array(
                'namaruangan' => $item->namaruangan,
                'jenis' => $item->noruangan,
                'sekarang' => 0,
                'sisa' => 0
            ));
        }
        // $respond = [
        //     array('jenis' => 'A', 'sekarang' => 0, 'sisa' => 0),
        //     array('jenis' => 'B', 'sekarang' => 0, 'sisa' => 0),
        //     array('jenis' => 'C', 'sekarang' => 0, 'sisa' => 0),
        //     array('jenis' => 'D', 'sekarang' => 0, 'sisa' => 0),
        //     array('jenis' => 'E', 'sekarang' => 0, 'sisa' => 0),
        //     array('jenis' => 'F', 'sekarang' => 0, 'sisa' => 0),
        //     array('jenis' => 'G', 'sekarang' => 0, 'sisa' => 0)
        // ];
        $i = 0;
        $j = 0;
        $last = [];
        foreach ($respond as $res) {
            foreach ($data2 as $d) {
                if ($d->jenis == $res['jenis']) {
                    $respond[$i]['sekarang'] = $d->last;
                    $last[] = array(
                        'nomer' => $d->last,
                        'jenis' =>  $res['jenis']
                    );
                }
            }
            foreach ($data as $d2) {
                if ($d2->jenis == $res['jenis']) {
                    $respond[$i]['sisa'] = $d2->last;
                }
            }
            $i++;
        }
        $res['last'] = $last;
        $res['data'] = $respond;
        return $this->respondV2($res);
    }

    public function updatePanggil(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $now = date('Y-m-d');
        $data = collect(DB::select("select norec, noantrian
            from antrianpasienregistrasi_t where
            statuspanggil ='0' and
            jenis ='$r[jenis]' and
            tanggalreservasi between '$now 00:00' and '$now 23:59'
            and kdprofile = $kdProfile
            order by noantrian asc"))
            ->first();
        $res['msg'] = 'Antrian Habis';
        if (!empty($data)) {
            AntrianPasienRegistrasi::where('statuspanggil', '1')
                ->where('tempatlahir', $r['loket'])
                ->where('kdprofile', $kdProfile)
                ->update([
                    'statuspanggil' => '2'
                ]);
            AntrianPasienRegistrasi::where('norec', $data->norec)
                ->where('kdprofile', $kdProfile)
                ->update([
                    'statuspanggil' => '1',
                    'tempatlahir' => $r['loket'],
                    'tglinput' => date('Y-m-d H:i:s'),
                    'devicememanggil' => gethostname(),
                ]);
            $res['msg'] = 'Antrian Ada';
        }

        return $this->respondV2($res);
    }

    public function getViewer(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $awal = date('Y-m-d 00:00');
        $akhir = date('Y-m-d 23:59');
        $data = AntrianPasienRegistrasi::whereIn('statuspanggil', ['1', '2'])
            ->whereBetween('tanggalreservasi', [$awal, $akhir])
            ->where('kdprofile', $kdProfile)
            ->orderBy('tanggalreservasi', 'desc')
            ->orderBy('tglinput', 'desc')
            ->get();

        foreach ($data as $item) {
            $item->antrianjenis = $item->jenis ."-". str_pad($item->noantrian, 3, "0", STR_PAD_LEFT);
        }
        return $this->respondV2($data);
    }
    public function getSettingViewer(Request $r)
    {
        $idProfile = $this->kdProfile;

        // $deptJalan = explode(',', $this->settingFix('listRuanganViewer'));
        // $ruangan = [];
        // foreach ($deptJalan as $item) {
        //     $ruangan[] = array(
        //         'id' => $item,
        //         'namaruangan' => $item,
        //         'nocounter' => null
        //     );
        // }
        $deptJalan =  explode(',', $this->settingFix('kdDepartemenRawatJalanFix'));
        $ruangan = DB::table('ruangan_m')
            ->select('id', 'namaruangan', 'nocounter')
            ->where('statusenabled', true)
            ->wherein('objectdepartemenfk', $deptJalan)
            ->orderBy('namaruangan')
            ->get();
        $farmasi = DB::table('ruangan_m')
            ->select('id', 'namaruangan', 'nocounter')
            ->where('statusenabled', true)
            ->where('kdprofile', $idProfile)
            ->wherein('objectdepartemenfk', [14])
            ->orderBy('namaruangan')
            ->get();
        // $res['ruangan'] = $ruangan;
        $res['farmasi'] = $farmasi;
        $res['ruangan'] = $ruangan;

        return $this->respondV2($res);
    }
    public function getDipanggil(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $arruangn = [];
        foreach (explode(',', $r['ruangan']) as $z) {
            $arruangn[] = $z;
        }
        $apd = DB::table('antrianpasiendiperiksa_t as apd')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
            ->join('pasien_m as ps', 'ps.id', '=', 'pd.nocmfk')
            ->select('ps.nocm', 'ps.namapasien', 'apd.noantrian', 'apd.objectruanganfk')
            ->where('apd.statusenabled', true)
            ->where('apd.kdprofile', $kdProfile)
            ->whereIn('apd.objectruanganfk', $arruangn)
            ->where('apd.statusantrian', 1)
            ->whereNotNull('apd.tgldipanggilsuster')
            ->orderByRaw("apd.noantrian asc,apd.tgldipanggilsuster asc")
            ->first();
        return $this->respondV2($apd);
    }
    public function getListAntrianFarm(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $statusPengerjaanViewerFar = $this->settingFix('statusPengerjaanViewerFar');
        $tglAwal = date('Y-m-d 00:00');
        $tglAkhir = date('Y-m-d 23:59');
        $data = DB::select(DB::raw("
        SELECT sr.noresep,aa.noantri AS aanoantri,aa.jenis AS aajenis,aa.keterangan,sr.tglresep,ru2.namaruangan,ru.namaruangan as ruanganasal,
        CASE
        when sr.tglambilresep !=null  then 'Sudah Di Ambil'
        when st.statuspengerjaan is not null then st.statuspengerjaan
        else ''  end as statusorder,pd.noregistrasi,ps.nocm,ps.namapasien,
        EXTRACT (YEAR FROM AGE(pd.tglregistrasi,ps.tgllahir )) || ' Thn ' as umur,
        CASE WHEN ps.objectjeniskelaminfk = 1 THEN 'L' ELSE 'P' END as jk
        FROM strukresep_t AS sr
        INNER JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = sr.pasienfk
        INNER JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
        INNER JOIN pasien_m AS ps ON ps.id = pd.nocmfk
        INNER JOIN ruangan_m AS ru2 ON ru2.id = sr.ruanganfk
        LEFT JOIN ruangan_m AS ru ON ru.id = apd.objectruanganfk
        INNER JOIN antrianapotik_t AS aa ON aa.noresep = sr.noresep
        LEFT JOIN statuspengerjaan_m AS st ON st.id = aa.status
        WHERE sr.kdprofile = $kdProfile AND sr.tglresep between '$tglAwal' and '$tglAkhir'
        AND sr.statusenabled = 't'
        and sr.kdprofile = $kdProfile
        and sr.tglambilresep is null
        and aa.status in ($statusPengerjaanViewerFar)
        order by sr.tglresep asc
        "));
        return $data;
    }
    public function getViewerFar(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $awal = date('Y-m-d 00:00');
        $akhir = date('Y-m-d 23:59');
        $data = DB::table('antrianapotik_t')
            ->where('status', '5')
            ->where('kdprofile', $kdProfile)
            ->whereBetween('tglresep', [$awal, $akhir])
            ->orderBy('tglresep', 'desc')
            ->get();

        $farmasi = DB::table('ruangan_m')
            ->select('id', 'namaruangan', 'nocounter')
            ->where('statusenabled', true)
            ->where('kdprofile', $kdProfile)
            ->wherein('objectdepartemenfk', [14])
            ->orderBy('namaruangan')
            ->get();
        $res['farmasi'] = $farmasi;
        $res['data'] = $data;
        return $this->respondV2($res);
    }
    public function getListAntrian_awal(Request $r)
    {
        $kdProfile = $this->kdProfile;
        $now = date('Y-m-d');
        $data = DB::select(DB::raw("select jenis, count(noantrian) as last from antrianpasienregistrasi_t
             where  statuspanggil ='0'
             and tanggalreservasi between '$now 00:00' and '$now 23:59'
             and kdprofile= $kdProfile
             group by jenis order by jenis"));
        $data2 = DB::select(DB::raw("select jenis, max(noantrian) as last from antrianpasienregistrasi_t
             where  statuspanggil !='0'
             and tanggalreservasi between '$now 00:00' and '$now 23:59'
             and kdprofile= $kdProfile
             group by jenis order by jenis"));
        // dd($data2);
        $respond = [
            array('jenis' => 'LT1', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT2', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT3', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT4', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT5', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT6', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT7', 'sekarang' => 0, 'sisa' => 0),
            array('jenis' => 'LT8', 'sekarang' => 0, 'sisa' => 0)
        ];
        $i = 0;
        $j = 0;
        $last = [];
        foreach ($respond as $res) {
            foreach ($data2 as $d) {
                if ($d->jenis == $res['jenis']) {
                    $respond[$i]['sekarang'] = $d->last;
                    $last[] = array(
                        'nomer' => $d->last,
                        'jenis' =>  $res['jenis']
                    );
                }
            }
            foreach ($data as $d2) {
                if ($d2->jenis == $res['jenis']) {
                    $respond[$i]['sisa'] = $d2->last;
                }
            }
            $i++;
        }
        $res['last'] = $last;
        $res['data'] = $respond;
        return $this->respondV2($res);
    }
    public function getDetail(Request $r)
    {
        $now = date('Y-m-d');
        $data = DB::select(DB::raw("select noantrian, 'Loket ' || tempatlahir as loket, tglinput, devicememanggil from antrianpasienregistrasi_t where
            statuspanggil != '0' and
            jenis ='$r[jenis]' and
            tanggalreservasi between '$now 00:00' and '$now 23:59'
            and kdprofile= $this->kdProfile
            order by tanggalreservasi"));
        return $this->respondV2($data);
    }
    public function getListCallerByRuangan(Request $request){
        $kdProfile = $this->kdProfile;
        $now = date('Y-m-d');
        $loket = $request->loket;
        // Membagi string menjadi array menggunakan koma sebagai pemisah
        $arrayNumbers = explode(',', $loket);

        // Menambahkan tanda kutip pada setiap elemen array
        $quotedNumbers = array_map(function ($number) {
            return "'$number'";
        }, $arrayNumbers);

        // Menggabungkan array menjadi string dengan koma sebagai pemisah
        $outputString = implode(',', $quotedNumbers);

        $data = DB::select(DB::raw("
        SELECT x.* FROM (
            SELECT rm.namaruangan
            ,apr.jenis
            ,apr.noantrian
            ,ROW_NUMBER() OVER (PARTITION BY apr.jenis ORDER BY apr.tglinput DESC) as nomor
            FROM antrianpasienregistrasi_t apr
            INNER JOIN ruangan_m rm on rm.id = apr.objectruanganfk
            WHERE apr.statuspanggil in ('1','2')
            AND apr.tanggalreservasi between '$now 00:00' AND '$now 23:59'
            AND apr.tempatlahir in ($outputString)
            and apr.kdprofile = $kdProfile
        ) x
        WHERE x.nomor = 1
        "));

        foreach ($data as $item) {
            $item->antrian = $item->jenis ."-". str_pad($item->noantrian, 3, "0", STR_PAD_LEFT);
        }
        return $this->respondV2($data);
    }
}
