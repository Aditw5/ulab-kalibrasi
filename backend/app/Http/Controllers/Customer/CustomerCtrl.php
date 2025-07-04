<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Master\PaketKalibrasi;
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

    public function historyOrderKelompok(Request $r)
    {

        $data  = DB::table('mitra_m as mt')
            ->leftjoin('mitraregistrasi_t as mtr', 'mtr.nomitrafk', '=', 'mt.id')
            ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->leftJoin('users as us', DB::raw('CAST(us.mitrafk AS TEXT)'), '=', 'mt.id')
            ->select(
                'mt.id',
                'mt.namaperusahaan',
                'mt.tgldaftar',
                'mt.email',
                'mt.nohp',
                'mt.foto',
                'mt.progress',
                'mtr.tglregistrasi',
                'mtr.petugas',
                'mtr.nopendaftaran',
                'mtr.lokasikalibrasi',
                'mtr.lokasirepair',
                'mtr.norec as iddetail',
                'mtr.statusorder',
                'mtr.jenisorder',
                'mtr.verifregiscustomer',
            )
            ->where('mt.statusenabled', true)
            ->where('mtr.isregiscustomer', true)
            ->where('mt.id', $r['mtrauser'])
            ->where('mtr.statusenabled', true);

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('mtr.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->orderByDesc('mtr.tglregistrasi');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

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
                'mtrd.noorderalat',
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
                'mtr.verifregiscustomer',
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
                    ->orWhere('mtr.nopendaftaran', 'ilike', $searchTerm)
                    ->orWhere('mtrd.noorderalat', 'ilike', $searchTerm)
                    ->orWhere('sn.namaserialnumber', 'ilike', $searchTerm);
            });
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }

        $totalQuery = clone $data;
        $totalData = $totalQuery->count();

        $totalVerifQuery = clone $data;
        $countVerif = $totalVerifQuery->where('mtr.verifregiscustomer', true)->count();

        $totalBelumVerifQuery = clone $data;
        $countBelumVerif = $totalBelumVerifQuery->whereNull('mtr.verifregiscustomer')->count();

        $data = $data->orderBy('mtr.jenisorder');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        $res['data'] = $data;
        $res['totalData'] = $totalData;
        $res['countVerif'] = $countVerif;
        $res['countBelumVerif'] = $countBelumVerif;
        return $this->respond($res);
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
            $APD = json_decode($r->input('mitraregistrasidetail'), true);

            $file = $r->file('fileCustomer');
            $allowedExtensions = ['pdf'];
            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $allowedExtensions)) {
                throw new \Exception("File harus berupa gambar (pdf).");
            }
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $file->move(public_path('berkas-customer'), $filename);

            $fileTools = $r->file('fileCustomerTools');
            $allowedExtensionsTools = ['xls', 'xlsx'];
            $extensionTools = strtolower($fileTools->getClientOriginalExtension());
            if (!in_array($extensionTools, $allowedExtensionsTools)) {
                throw new \Exception("File harus berupa excel.");
            }
            $filenameTools = time() . '_' . preg_replace('/\s+/', '_', $fileTools->getClientOriginalName());
            $fileTools->move(public_path('berkas-customer'), $filenameTools);

            $customerId = $this->getPegawaiId();
            $groupedByJenisOrder = collect($APD)->groupBy('jenisorder');
            $registrasiData = [];
            foreach ($groupedByJenisOrder as $jenisorder => $alatList) {
                $model_PD = new MitraRegistrasi();
                $model_PD->norec = $model_PD->generateNewId();
                $model_PD->statusenabled = true;
                $model_PD->nomitrafk = $r['nomitrafk'];
                $model_PD->namapenanggungjawab = $r['namapenanggungjawab'];
                $model_PD->jabatanpenanggungjawab = $r['jabatanpenanggungjawab'];
                $model_PD->catatan = $r['catatan'];
                if ($jenisorder == 'kalibrasi') {
                    $model_PD->lokasikalibrasi = $r['lokasi'];
                    $model_PD->paketkalibrasi = $r['paketkalibrasi'];
                    $model_PD->rentangUkur = $r['rentangUkur'];
                    $model_PD->rentangUkurketPermintaanPelanggan = $r['rentangUkurketPermintaanPelanggan'];
                } else {
                    $model_PD->lokasirepair = $r['lokasi'];
                }
                $model_PD->tglregistrasi = now();
                $model_PD->customerfk = $customerId;
                $model_PD->isregiscustomer = true;
                $model_PD->statusorder = 0;
                $model_PD->statusordermanager = 0;
                $model_PD->jenisorder = $jenisorder;
                $model_PD->filecustomerams = $filename;
                $model_PD->filecustomertools = $filenameTools;
                $model_PD->save();

                $durasikalibrasi = null;
                if (!empty($r['paketkalibrasi'])) {
                    $paket = PaketKalibrasi::find($r['paketkalibrasi']);
                    if ($paket) {
                        $durasikalibrasi = $paket->hari;
                    }
                }

                foreach ($alatList as $alat) {
                    $model_APD = new MitraRegistrasiDetail;
                    $model_APD->norec = $model_APD->generateNewId();
                    $model_APD->statusenabled = true;
                    $model_APD->namaalatfk = $alat['id'] ?? null;
                    $model_APD->namamerkfk = $alat['idmerk'] ?? null;
                    $model_APD->namatipefk = $alat['idtipe'] ?? null;
                    $model_APD->serialnumberfk = $alat['idsn'] ?? null;
                    $model_APD->noregistrasifk = $model_PD->norec;
                    if ($durasikalibrasi !== null) {
                        $model_APD->durasikalbrasi = $durasikalibrasi;
                    }
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

    public function getStatusCustomer(Request $r)
    {
        $data = DB::table('users')
            ->select(
                'id',
                'name',
                'email',
                'mitrafk',
                'jabatan',
                'isuserbaru',
            )
            ->where('id', $this->getPegawaiId())
            ->first();

        $result['data'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
    }

    public function saveStatusCustomer(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['statusCustomer'];
            DB::table('users')
                ->where('id', $this->getPegawaiId())
                ->update([
                    'mitrafk' => $VI['mitrafk'],
                    'jabatan' => $VI['jabatan'],
                    'isuserbaru' => false
                ]);

            $transMessage = "Simpan Status Customer Sukses";
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

    public function hapusKeranjangCustomer(Request $r)
    {
        DB::beginTransaction();
        try {
            $norecList = $r->input('norec');
            if (!is_array($norecList) || count($norecList) === 0) {
                return response()->json([
                    "metaData" => [
                        "code" => 400,
                        "message" => "Data norec kosong/tidak valid"
                    ],
                    "response" => null
                ], 400);
            }

            foreach ($norecList as $item) {
                DB::table('keranjangcustomer_t')
                    ->where('norec', $item)
                    ->update([
                        'statusenabled' => false,
                    ]);
            }

            $transMessage = "Hapus Keranjang Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@adit',
                ],
            ];
        } catch (\Exception $e) {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = [
                "status" => 400,
                "result"  => $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
