<?php

namespace App\Http\Controllers\Sysadmin;

use App\Http\Controllers\Controller;
use App\Models\Master\JadwalDokter;
use App\Models\Master\Pegawai;
use App\Models\Master\Ruangan;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery\Exception\InvalidOrderException;

class MasterJadwalDokterCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function masterJadwalDokter(Request $r)
    {
        $data  = DB::table('jadwaldokter_m as jd')
            ->join('ruangan_m as ru', 'jd.objectruanganfk', '=', 'ru.id')
            ->join('pegawai_m as pe', 'jd.objectpegawaifk', '=', 'pe.id')

            ->select(
                'jd.id',
                'jd.objectpegawaifk',
                'jd.objectruanganfk',
                'jd.statusenabled',
                'ru.namaruangan',
                'pe.namalengkap',
                'jd.hari',
                'jd.jammulai',
                'jd.jamakhir',
                'jd.keterangan'
            )
            ->where('jd.kdprofile', $this->kdProfile);

        if (isset($r['statusenabled']) && $r['statusenabled'] != '') {
            $data = $data->where('jd.statusenabled', '=',  $r['statusenabled']);
        }
        if (isset($r['namalengkap']) && $r['namalengkap'] != '') {
            $data = $data->where('pe.namalengkap', 'ilike', '%' . $r['namalengkap'] . '%');
        }
        if (isset($r['namaruangan']) && $r['namaruangan'] != '') {
            $data = $data->where('ru.id', '=', $r['namaruangan']);
        }
        if (isset($r['namalengkap']) && $r['namalengkap'] != '') {
            $data = $data->where('pe.id', '=', $r['namalengkap']);
        }
        if (isset($r['objectruanganfk']) && $r['objectruanganfk'] != '') {
            $data = $data->where('jd.objectruanganfk', '=', $r['objectruanganfk'] );
        }
        if (isset($r['objectpegawaifk']) && $r['objectpegawaifk'] != '') {
            $data = $data->where('jd.objectpegawaifk', '=', $r['objectpegawaifk'] );
        }
        if (
            isset($r['limit']) &&
            $r['limit'] != "" &&
            $r['limit'] != "undefined"
        ) {
            $data = $data->take($r['limit']);
        }

        $data = $data->orderByDesc('jd.id', 'desc');
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }
    public function masterJadwalDokterdropdown(Request $r)
    {
        $res['namaruangan'] = Ruangan::mine()->get();
        $res['namalengkap'] = Pegawai::mine()->get();
        return $this->respond($res);
    }

    public function saveJadwalDokter(Request $r){
        DB::beginTransaction();
        try {
            $count = JadwalDokter::count();
            $PSN =  $r['jadwaldokter'];

            if ($PSN['id'] == '') {
                $dokter = new JadwalDokter();
                $dokter->id = $this->SEQUENCE_MASTER(new JadwalDokter(),'id',$this->kdProfile);//(string)Uuid::uuid4();
                $dokter->statusenabled = true;
                $transMessage = "Proses Simpan Data Berhasil";
            } else {
                $dokter = JadwalDokter::where('id', $PSN['id'])->first();
                $dokter->statusenabled = $PSN['statusenabled'];
                $transMessage = "Proses Update Data Berhasil";
                $dokter->id;
            }
            $dokter->kdprofile = (int)$this->kdProfile;
            $dokter->objectruanganfk =  $PSN['objectruanganfk'];
            $dokter->objectpegawaifk =  $PSN['objectpegawaifk'];
            $dokter->hari =  isset($PSN['hari'])?$PSN['hari']:null;
            $dokter->jammulai = $PSN['jammulai'];
            $dokter->jamakhir = $PSN['jamakhir'];
            $dokter->keterangan = $PSN['keterangan'];
            $dokter->save();

            DB::commit();

            $result = [
                "statusCode" => 201,
                "message" => $transMessage,
                "result" => [
                    "data"  => $dokter,
                    "as" => '@epic',
                ],
            ];
            return $this->respond($result['result'], $result['statusCode'], $result['message']);

        } catch (InvalidOrderException $e) {
            DB::rollBack();
            $result = array(
                "statusCode" => $e->getCode(),
                "message" => "Something Want Wrong",
                "result"  => $e->getMessage()

            );
            return $this->respond($result['result'], $result['statusCode'], $result['message']);
        }
    }

    public function deleteJadwalDokter(Request $r)
    {
        DB::beginTransaction();
        try {

            $dataPS = JadwalDokter::where('id', $r['id'])
                ->update([
                    'statusenabled' => false
                ]);

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $dataPS,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Hapus Gagal";
            DB::rollBack();

            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
