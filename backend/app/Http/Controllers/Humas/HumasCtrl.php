<?php

namespace App\Http\Controllers\Humas;

use App\Http\Controllers\Controller;
use App\Models\Master\JenisPelayanan;
use App\Models\Master\Kelas;
use App\Models\Master\Produk;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class HumasCtrl extends Controller
{
    use Valet;

    public function infoBed(Request $request)
    {
        $kdProfile =  $this->kdProfile;
        $ruanganfk = '';
        if ($request->ruanganfk) {
            $ruanganfk = 'and ru.id = ' . $request->ruanganfk;
        }

        $data = collect(DB::select("
        SELECT
          x.id,
          x.namaruangan,
          SUM (x.isi) AS isi,
          SUM (x.kosong) AS kosong,
          SUM (x.rusak) as rusak,
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
                  ru.id,
                  CASE 	WHEN sb. ID = 1 THEN	1	ELSE 0 END AS isi,
              CASE WHEN sb. ID = 2 THEN	1	ELSE 0	END AS kosong,
              CASE WHEN sb. ID = 11 THEN	1	ELSE 0	END AS rusak
          FROM
              tempattidur_m AS tt
          INNER JOIN statusbed_m AS sb ON sb. ID = tt.objectstatusbedfk
          INNER JOIN kamar_m AS kmr ON kmr. ID = tt.objectkamarfk
          INNER JOIN kelas_m AS kls ON kls. ID = kmr.objectkelasfk
          INNER JOIN ruangan_m AS ru ON ru. ID = kmr.objectruanganfk
          WHERE
            tt.kdprofile = $kdProfile
            $ruanganfk
            AND  tt.statusenabled = TRUE
            AND kmr.statusenabled = TRUE

          ) AS x
      GROUP BY
          x.namaruangan, x.id
      "));
        $totalKamar = $data->count();
        $totalBed = 0;
        $totalIsi = 0;
        $totalKosong = 0;
        $totalRusak = 0;
        foreach ($data as $item) {
            $totalBed =    $totalBed + (float) $item->total;
            $totalIsi =    $totalIsi + (float) $item->isi;
            $totalKosong =    $totalKosong + (float) $item->kosong;
            $totalRusak =    $totalRusak + (float) $item->rusak;
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
        $ruanganfk
        AND ru.statusenabled = true
        AND km.statusenabled = true
        AND tt.statusenabled = true
        AND tt.kdprofile = $kdProfile"));

        $data10 = [];
        $sama = false;
        $bed = 0;
        $isi = 0;
        $rusak = 0;
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
                    if ($item->idstatusbed == 11) {
                        $data10[$i]['rusak'] = (float)$hideung['rusak'] + 1;
                    }
                }
                $i = $i + 1;
            }
            if ($sama == false) {
                $dataMenunggu = DB::table('pasiendaftar_t')
                    ->select(DB::raw('COUNT(norec)'))
                    ->where('statusschedule', 'Waiting List')
                    ->where('ruangannextschedule', $item->idruangan)
                    ->first();
                if ($item->idstatusbed == 1) {
                    $isi = 1;
                    $kosong = 0;
                    $rusak = 0;
                }
                if ($item->idstatusbed == 2) {
                    $isi = 0;
                    $kosong = 1;
                    $rusak = 0;
                }
                if ($item->idstatusbed == 11) {
                    $isi = 0;
                    $kosong = 0;
                    $rusak = 1;
                }

                $data10[] = array(
                    'idruangan' => $item->idruangan,
                    'namaruangan' => $item->namaruangan,
                    'idstatusbed' => $item->idstatusbed,
                    'bed' => 1,
                    'kosong' => $kosong,
                    'isi' => $isi,
                    'rusak' => $rusak,
                    'menunggu' => $dataMenunggu->count
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

    public function getDetailBed(Request $request)
    {
        $ruangan = $request->ruangan;
        $data = DB::table('tempattidur_m AS tt')
            ->leftJoin('kamar_m as km', 'km.id', 'tt.objectkamarfk')
            ->leftJoin('kelas_m as kls', 'kls.id', 'km.objectkelasfk')
            ->leftJoin('ruangan_m as rm', 'rm.id', 'km.objectruanganfk')
            ->leftJoin('statusbed_m as sb', 'sb.id', 'tt.objectstatusbedfk')
            ->select(
                'tt.reportdisplay as nama',
                'kls.namakelas as kelas',
                'sb.statusbed as status'
            )
            ->orderBy('tt.id', 'ASC')
            ->where('rm.id', $ruangan)
            ->where('tt.statusenabled', true)
            ->get();
        $res['data'] = $data;
        return $this->respond($res);
    }
    public function getInfoLayanan(Request $request)
    {
        $data = DB::table('produk_m as pr')
            ->leftjoin('mapruangantoproduk_m as mprtp', 'mprtp.objectprodukfk', 'pr.id')
            ->leftjoin('harganettoprodukbykelas_m as hrpk', 'hrpk.objectprodukfk', 'pr.id')
            ->join('kelas_m as kls', 'kls.id', 'hrpk.objectkelasfk')
            ->join('jenispelayanan_m as jnsp', 'jnsp.id', 'hrpk.objectjenispelayananfk')
            ->join('ruangan_m as ru', 'ru.id', 'mprtp.objectruanganfk')
            ->leftjoin('rekanan_m as rkn', 'rkn.id', 'hrpk.objectpenjaminfk')
            ->select(
                'pr.id',
                'pr.namaproduk',
                'hrpk.harganetto1 AS hargalayanan',
                'kls.id as idkelas',
                'kls.namakelas',
                'jnsp.id as jenispelayananid',
                'jnsp.jenispelayanan',
                'mprtp.objectruanganfk as ruid',
                'ru.id as ruid',
                'ru.namaruangan',
                'rkn.namarekanan',
                DB::raw("CASE WHEN hrpk.hargadijamin IS NULL THEN 0 ELSE hrpk.hargadijamin END AS hargadijamin"),
                'hrpk.objectpenjaminfk'
            )
            ->where('pr.kdprofile', $this->kdProfile)
            ->where('pr.statusenabled', true)
            ->distinct();

        if (isset($request['idruangan']) && $request['idruangan'] != '') {
            $data = $data->where('mprtp.objectruanganfk', '=',  $request['idruangan']);
        }
        if (isset($request['idkelas']) && $request['idkelas'] != '') {
            $data = $data->where('kls.id', '=',  $request['idkelas']);
        }
        if (isset($request['idproduk']) && $request['idproduk'] != '') {
            $data = $data->where('pr.id', '=',  $request['idproduk']);
        }
        if (isset($request['jenispelayananid']) && $request['jenispelayananid'] != '') {
            $data = $data->where('jnsp.id', '=',  $request['jenispelayananid']);
        }

        if (isset($r['limit']) && $r['limit'] != '') {
            $data = $data->limit($r['limit']);
        }

        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getPilihan(Request $request)
    {
        $idProfile = (int)$this->kdProfile;
        $res['ruangan'] = Ruangan::select('id', 'namaruangan')->where('kdprofile', $idProfile)->where('statusenabled', true)->get();

        $res['kelas'] = Kelas::mine()->get();
        $res['jenispelayanan'] = JenisPelayanan::mine()->get();
        $res['produk'] = Produk::mine()->get();

        return $this->respond($res);
    }
}
