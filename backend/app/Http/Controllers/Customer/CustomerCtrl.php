<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\KeranjangCustomer;
use App\Models\Transaksi\MitraRegistrasi;
use App\Models\Transaksi\MitraRegistrasiDetail;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CustomerCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function getAlatCustomer(Request $r)
    {
        $data = DB::table('produk_m as pm')
            ->leftJoin('mapmitratoproduk_m as mmp', 'mmp.objectprodukfk', '=', 'pm.id')
            ->select(
                'pm.id',
                'pm.namaproduk',
                'pm.fotoproduk',
            )
            ->where('mmp.statusenabled', true)
            // ->where('mmp.objectmitrafk', $r['idmitra'])
            ->where('pm.statusenabled', true);

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('pm.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('pm.id', 'ilike', $searchTerm);
            });
        }

        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }

        $data = $data->orderBy('pm.namaproduk');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        return $this->respond($data);
    }

    public function keranjangCustomer(Request $r)
    {
        $data = DB::table('keranjangcustomer_t as kc')
            ->leftJoin('produk_m as pm', 'pm.id', '=', 'kc.idalat')
            ->leftjoin('merkalat_m as mrk', 'mrk.id', '=', 'kc.merkalat')
            ->leftjoin('tipealat_m as tp', 'tp.id', '=', 'kc.tipealat')
            ->leftjoin('serialnumber_m as sn', 'sn.id', '=', 'kc.serialnumber')
            ->select(
                'kc.norec',
                'kc.jenisorder',
                'pm.id',
                'pm.namaproduk',
                'mrk.id as idmerk',
                'mrk.namamerk',
                'tp.id as idtipe',
                'tp.namatipe',
                'sn.id as idsn',
                'sn.namaserialnumber',
            )
            ->where('kc.customerid', $this->getPegawaiId())
            ->where('pm.statusenabled', true)
            ->where('mrk.statusenabled', true)
            ->where('tp.statusenabled', true)
            ->where('kc.statusenabled', true);

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('pm.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('pm.id', 'ilike', $searchTerm)
                    ->orWhere('mrk.namamerk', 'ilike', $searchTerm)
                    ->orWhere('tp.namatipe', 'ilike', $searchTerm)
                    ->orWhere('sn.namaserialnumber', 'ilike', $searchTerm);
            });
        }
        $data = $data->orderBy('kc.jenisorder');
        $data = $data->get();

        return $this->respond($data);
    }

    public function historyOrder(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftjoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftjoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftjoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
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
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
                'mtr.jenisorder',
            )
            ->where('mtr.customerfk', $this->getPegawaiId())
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true);

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('prd.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('prd.id', 'ilike', $searchTerm)
                    ->orWhere('mrk.namamerk', 'ilike', $searchTerm)
                    ->orWhere('tp.namatipe', 'ilike', $searchTerm)
                    ->orWhere('sn.namaserialnumber', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }

        $data = $data->orderBy('mtr.jenisorder');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        return $this->respond($data);
    }

    public function saveKeranjangCustomer(Request $r)
    {
        DB::beginTransaction();
        try {
            $PD = $r['keranjangcustomer'];

            $model_PD = new KeranjangCustomer();
            $model_PD->norec = $model_PD->generateNewId();
            $model_PD->statusenabled = true;
            $model_PD->idalat = $PD['idalat'];
            $model_PD->merkalat =  $PD['merkalat'];
            $model_PD->tipealat = $PD['tipealat'];
            $model_PD->serialnumber = $PD['serialnumber'];
            $model_PD->jenisorder = $PD['jenisorder'];
            $model_PD->customerid = $this->getPegawaiId();
            $model_PD->save();

            $transMessage = "Simpan Keranjang Customer Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "as" => '@epic',
                ),
            );
        } catch (Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage()
            );
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveRegistrasiCustomer(Request $r)
    {
        DB::beginTransaction();
        try {
            $PD = $r['mitraregistrasi'];
            $APD = $r['mitraregistrasidetail'];

            $customerId = $this->getPegawaiId();

            $groupedByJenisOrder = collect($APD)->groupBy('jenisorder');
            $registrasiData = [];

            foreach ($groupedByJenisOrder as $jenisorder => $alatList) {
                $model_PD = new MitraRegistrasi();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->statusenabled = true;
                $model_PD->nomitrafk = $PD['nomitrafk'];
                $model_PD->tglregistrasi = now();
                $model_PD->customerfk = $customerId;
                $model_PD->isregiscustomer = true;
                $model_PD->statusordermanager = 0;
                $model_PD->jenisorder = $jenisorder; 
                $model_PD->save();

                foreach ($alatList as $alat) {
                    $model_APD = new MitraRegistrasiDetail;
                    $model_APD->norec = $model_APD->generateNewId();
                    $model_APD->statusenabled = true;
                    $model_APD->namaalatfk = $alat['id'] ?? null;
                    $model_APD->namamerkfk = $alat['idmerk'] ?? null;
                    $model_APD->namatipefk = $alat['idtipe'] ?? null;
                    $model_APD->serialnumberfk = $alat['idsn'] ?? null;
                    $model_APD->noregistrasifk = $model_PD->norec;
                    $model_APD->save();

                    DB::table('keranjangcustomer_t')
                        ->where('norec', $alat['norec'])
                        ->update([
                            'statusenabled' => false
                        ]);
                }

                $registrasiData[] = $model_PD->norec;
            }

            DB::commit();

            $transMessage = "Simpan Registrasi Sukses untuk " . count($registrasiData) . " jenis order.";
            $result = [
                "status" => 200,
                "result" => [
                    "registrasi_norec" => $registrasiData,
                ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            $result = [
                "status" => 400,
                "result" => $e->getMessage(),
            ];
            $transMessage = "Simpan Gagal";
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
