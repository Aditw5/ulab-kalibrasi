<?php

namespace App\Http\Controllers\Registrasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Pasien;
use App\Models\Transaksi\PasienDaftar;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception\InvalidOrderException;

class PasienLamaCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function pasienLama(Request $r)
    {
        $data  = DB::table('pasien_m as ps')
            ->join('alamat_m as alm', 'alm.nocmfk', '=', 'ps.id')
            ->join('jeniskelamin_m as jk', 'jk.id', '=', 'ps.objectjeniskelaminfk')
            ->leftJoin('infeksippi_m as ifp', 'ifp.id', '=', 'ps.objectinfeksippifk')
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
                'ps.isfoto',
                'ps.filename',
                'ifp.namainfeksippi'
            )
            ->where('ps.statusenabled', true)
            ->where('ps.kdprofile', $this->kdProfile);
        if (isset($r['id']) && $r['id'] != '') {
            $data = $data->where('ps.id', '=',  $r['id']);
        }
         $filter = false;
        if (isset($r['q']) && $r['q'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['q'] . '%');
        }

        if (isset($r['namapasien']) && $r['namapasien'] != '') {
            $filter = true;
            $data = $data->where('ps.namapasien', 'ilike', '%' . $r['namapasien'] . '%');

        }
        if (isset($r['nocm']) && $r['nocm'] != '') {
            $filter = true;
            $data = $data->where('ps.nocm', 'ilike', '%' . $r['nocm'] . '%');

        }
        if (isset($r['namainfeksippi']) && $r['namainfeksippi'] != '') {
            $filter = true;
            $data = $data->where('ps.namainfeksippi', 'ilike', '%' . $r['namainfeksippi'] . '%');

        }
        if (isset($r['nik']) && $r['nik'] != '') {
            $filter = true;
            $data = $data->where('ps.noidentitas', '=',  $r['nik']);
        }
        if (isset($r['nobpjs']) && $r['nobpjs'] != '') {
            $filter = true;
            $data = $data->where('ps.nobpjs', '=',  $r['nobpjs']);
        }
        if (isset($r['bpjs']) && $r['bpjs'] != '') {
            $filter = true;
            $data = $data->where('ps.nobpjs', '=',  $r['bpjs']);
        }
        if (isset($r['alamat']) && $r['alamat'] != '') {
            $filter = true;
            $data = $data->where('alm.alamatlengkap', '=',  $r['alamat']);
        }
        if (isset($r['tgllahir']) && $r['tgllahir'] != '') {
            $filter = true;
            $data = $data->where('ps.tgllahir', '=',  $r['tgllahir']);
        }
        if (isset($r['isbayi']) && $r['isbayi'] != '' && $r['isbayi'] == "true") {
            $data = $data->where('ps.isbayi', '=', $r['isbayi']);
            $filter = true;
        }
        // // if(  !$filter ){
        // if (isset($r['limit']) && $r['limit'] != '') {
        //     $data = $data->limit($r['limit']);
        // }
        // if (isset($r['offset']) && $r['offset'] != '') {
        //     $data = $data->offset($r['offset']);
        // }
        // // }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '' && $filter == false) {
            $page = $r['page'];
        }

        $data = $data->orderByDesc('ps.nocm');
        // $data = $data->get();
        $data = $data ->paginate(isset($r['limit'])?$r['limit']: 10, ['*'], 'page', $page);
        // dd(DB::getQueryLog());

        foreach ($data as $d) {
            $d->tgllahir = date('Y-m-d', strtotime($d->tgllahir));
            $d->umur =  $this->getAge($d->tgllahir,   date('Y-m-d H:i:s'));
            $d->status = 'Hidup';
            $d->status_c = 'purple';
            $d->foto = null;
            if ($d->tglmeninggal != null) {
                $d->status = 'Meninggal';
                $d->status_c = 'danger';
            }
            $d->progress = $d->progress == null ? 0 : (int)  $d->progress;
            if ((int)  $d->progress <= 50) {
                $d->class_proggress = 'danger';
            }
            if ((int)  $d->progress > 50 && (int)  $d->progress <= 80) {
                $d->class_proggress = 'warning';
            }
            if ((int)  $d->progress > 80) {
                $d->class_proggress = 'success';
            }
            if ($d->isfoto) {
                $path = 'foto_pasien/' . $d->id . '/' . $d->filename;
                $disk = Storage::disk('public');

                if (!$disk->exists($path)) {
                    $d->foto = null;
                } else {
                    $file = $disk->read($path);

                    $b64Doc = chunk_split(base64_encode($file));
                    $sourcePdf = "data:application/pdf;base64," . $b64Doc;

                    $d->foto  =  $sourcePdf;
                }
            }
        }

        return $this->respond($data);
    }

    public function deletePasien(Request $r)
    {
        DB::beginTransaction();
        try {
            $dataPS = Pasien::where('id', $r['id'])->update(['statusenabled' => false]);
            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => "Hapus Data Berhasil",
                "result" => [
                    "data"  => $dataPS,
                    "as" => '@epic',
                ],
            ];
        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Something Want Wrong",
                "result"  => $e->getMessage()

            );
        }

        return $this->respond($result['result'], $result['statusCode'], $result['message']);
    }
    public function cekPulangpasien(Request $r)
    {
        $cekRI = PasienDaftar::where('nocmfk', $r['id'])
            ->whereNull('tglpulang')
            ->where('statusenabled', true)
            ->first();

        return $this->respond($cekRI);
    }
    public function cekSEPpasien(Request $r)
    {
        $cekRI = DB::table('pasiendaftar_t as pd')
            ->join('pemakaianasuransi_t as pa', 'pa.noregistrasifk', 'pd.norec')
            ->join('ruangan_m as ru', 'ru.id', 'pd.objectruanganlastfk')
            ->select('pa.nosep','pd.noregistrasi', 'ru.namaruangan')
            ->where('pd.nocmfk', $r['id'])
            ->where('pd.statusenabled', true)
            ->whereBetween('pd.tglregistrasi', [now()->startOfDay(), now()->endOfDay()])
            ->first();

        return $this->respond($cekRI);
    }
}
