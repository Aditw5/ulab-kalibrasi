<?php

namespace App\Http\Controllers\Farmasi;

use App\Http\Controllers\Controller;
use App\Models\Master\Ruangan;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\StrukOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Traits\Valet;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Carbon\Carbon;
use Exception;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\Uuid;

class OrderResepCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }
    public function simpanOrderResep(Request $request)
    {

        DB::beginTransaction();
        try {

            $kdProfile = (int) $this->kdProfile;
            foreach ($request['data'] as $data) {

                $r_SR = $data['strukorder'];

                $dataDetail = DB::table('antrianpasiendiperiksa_t as apd')
                    ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
                    ->select('pd.norec', 'pd.nocmfk', 'apd.objectruanganfk', 'pd.objectkelasfk', 'apd.norec as apdnorec')
                    ->where('apd.norec', $r_SR['noregistrasifk'])
                    ->first();

                if (empty($dataDetail)) {
                    $transMessage = "Data Tidak Ada";
                    DB::commit();
                    $result = array(
                        "status" => 200,
                        "result" => array(
                            "as" => '@epic',
                        ),
                    );

                    return $this->respond($result['result'], $result['status'], $transMessage);
                }
                $pasien = DB::table('pasien_m')->where('id', $dataDetail->nocmfk)->first();

                $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
                if ($r_SR['norec'] == '') {
                    $noOrder = $this->SEQUENCE_NEXVAL(new StrukOrder(), $set->seqname, 10, date('ym'), $kdProfile);
                    if ($noOrder == '') {
                        $transMessage = "Gagal mengumpukan data, Coba lagi.!";
                        DB::rollBack();
                        $result = array("status" => 400, "result"  => null);
                        return $this->respond($result['result'], $result['status'], $transMessage);
                    }

                    $newSO = new StrukOrder();
                    $newSO->norec = $newSO->generateNewId();
                    $newSO->kdprofile = $kdProfile;
                    $newSO->statusenabled = true;
                } else {
                    $newSO = StrukOrder::where('norec', $r_SR['norec'])->first();
                    $noOrder = $newSO->noorder;
                    OrderPelayanan::where('noorderfk', $newSO->norec)->delete();
                }


                $newSO->nocmfk = $dataDetail->nocmfk;
                $newSO->kddokter = $r_SR['penulisresepfk'];
                $newSO->isdelivered = 1;
                $newSO->objectkelompoktransaksifk = $set->objectkelompoktransaksifk;
                $newSO->keteranganorder =  $set->keteranganorder;
                $newSO->noorder = $noOrder;
                $newSO->noregistrasifk = $dataDetail->norec;
                $newSO->norec_apd = $r_SR['noregistrasifk'];
                $newSO->objectpegawaiorderfk = $r_SR['penulisresepfk'];
                $newSO->qtyproduk = $r_SR['qtyproduk'];
                $newSO->qtyjenisproduk = $r_SR['qtyproduk'];
                $newSO->objectruanganfk = $dataDetail->objectruanganfk;
                $newSO->objectruangantujuanfk = $r_SR['ruanganfk'];
                $newSO->isiter = $r_SR['isiter'];
                $newSO->iterJumlah = $r_SR['iterJumlah'];
                $newSO->statusorder = $set->statusorder;
                $newSO->tglorder = $r_SR['tglresep'];
                $newSO->totalbeamaterai = 0;
                $newSO->totalbiayakirim = 0;
                $newSO->totalbiayatambahan = 0;
                $newSO->totaldiscount = 0;
                $newSO->totalhargasatuan = 0;
                $newSO->totalharusdibayar = 0;
                $newSO->totalpph = 0;
                $newSO->totalppn = 0;
                $newSO->isreseppulang = $r_SR['isreseppulang'];
                $newSO->nourutruangan = isset($r_SR['noruangan']) ? $r_SR['noruangan'] : null;
                $newSO->isambilobat = isset($r_SR['isambilobat']) ? $r_SR['isambilobat'] : null;
                $newSO->jarak_kirim =  isset($r_SR['jarak_kirim']) ? $r_SR['jarak_kirim'] : null;
                $newSO->alamat_kirim =  isset($r_SR['alamat_kirim']) ? $r_SR['alamat_kirim'] : null;
                $newSO->harga_kirim =  isset($r_SR['harga_kirim']) ? $r_SR['harga_kirim'] : null;
                $newSO->no_hp =  isset($r_SR['no_hp']) ? $r_SR['no_hp'] : null;
                $newSO->iskurir = isset($r_SR['iskurir']) ? $r_SR['iskurir'] : null;
                $newSO->noregistrasi = $data['noregistrasi'];
                $newSO->objectpetugasfk = $this->getPegawaiId();
                $newSO->save();
                $norec_SR = $newSO->norec;

                $r_PP = $data['orderpelayanan'];
                $prod = [];
                foreach ($r_PP as $r_PPL) {
                    $r_PPL['nilaikonversi'] =    isset($r_PPL['nilaikonversi']) ? $r_PPL['nilaikonversi'] : 1;
                    $qtyJumlah = (float)$r_PPL['jumlah'] * (float)$r_PPL['nilaikonversi'];
                    // $pro = DB::table('produk_m')->where('id', $r_PPL['produkfk'])->first();
                    $prod[] =  $r_PPL['produkfk'];
                    $newPP = new OrderPelayanan();
                    $norecPP = $newPP->generateNewId();
                    $newPP->norec = $norecPP;
                    $newPP->kdprofile = $kdProfile;
                    $newPP->statusenabled = true;
                    $newPP->aturanpakai = $r_PPL['aturanpakai'];
                    $newPP->isreadystok = 1;
                    $newPP->kddokter = $r_SR['penulisresepfk'];;
                    $newPP->objectkelasfk = $dataDetail->objectkelasfk;;
                    $newPP->nocmfk = $dataDetail->nocmfk;
                    $newPP->noorderfk = $norec_SR;
                    $newPP->noregistrasifk = $dataDetail->norec;
                    $newPP->objectprodukfk = $r_PPL['produkfk'];
                    if (isset($r_PPL['jumlahxmakan'])) {
                        $newPP->qtykemasan = $r_PPL['jumlahxmakan'];
                    }
                    $newPP->qtyproduk = $r_PPL['jumlah'] ?? 0;
                    $newPP->qtystokcurrent = $r_PPL['jmlstok'] ?? 0;
                    $newPP->racikanke = $r_PPL['rke'];
                    $newPP->objectruanganfk = $dataDetail->objectruanganfk;
                    // $newPP->objectruangantujuanfk = $r_PPL['ruanganfk'];
                    // $newPP->objectsatuanstandarfk = $r_PPL['satuanstandarfk'];
                    $newPP->objectruangantujuanfk = $r_SR['ruanganfk'];
                    $newPP->objectsatuanstandarfk = $r_PPL['satuanviewfk'];
                    $newPP->strukorderfk = $norec_SR;
                    $newPP->tglpelayanan = $r_SR['tglresep'];
                    $newPP->jenisobatfk = isset($r_PPL['jenisobatfk']) ? $r_PPL['jenisobatfk'] : null;
                    $newPP->jumlah = $qtyJumlah;
                    $newPP->iscito = 0;
                    $newPP->hargasatuan = $r_PPL['hargasatuan'];
                    $newPP->hargadiscount = $r_PPL['hargadiscount'];
                    $newPP->qtyprodukretur = 0;
                    $newPP->hasilkonversi = isset($r_PPL['nilaikonversi']) ? $r_PPL['nilaikonversi'] : 1;
                    $newPP->jeniskemasanfk = $r_PPL['jeniskemasanfk'];
                    $newPP->dosis = $r_PPL['dosis'];
                    $newPP->rke = $r_PPL['rke'];
                    $newPP->satuanviewfk = $r_PPL['satuanviewfk'];
                    $newPP->ispagi = isset($r_PPL['ispagi']) ? $r_PPL['ispagi'] : null;
                    $newPP->issiang =  isset($r_PPL['issiang']) ? $r_PPL['issiang'] : null;
                    $newPP->ismalam = isset($r_PPL['ismalam']) ? $r_PPL['ismalam'] : null;
                    $newPP->issore =  isset($r_PPL['issore']) ? $r_PPL['issore'] : null;
                    $newPP->keteranganpakai = $r_PPL['keterangan'];
                    $newPP->satuanresepfk = isset($r_PPL['satuanresepfk']) ? $r_PPL['satuanresepfk'] : null;
                    $newPP->tglkadaluarsa = isset($r_PPL['tglkadaluarsa']) ? $r_PPL['tglkadaluarsa'] : null;
                    $newPP->isoutofstok =  isset($r_PPL['isoutofstok']) ? $r_PPL['isoutofstok'] : null;
                    $newPP->nostrukterimafk = isset($r_PPL['nostrukterimafk']) ? $r_PPL['nostrukterimafk'] : null;
                    $newPP->objectasalprodukfk = isset($r_PPL['asalprodukfk']) ? $r_PPL['asalprodukfk'] : null;
                    $newPP->noregistrasi = $data['noregistrasi'];
                    $newPP->save();
                }
            }
            $pro = DB::table('produk_m')->whereIn('id', $prod)->get();

            // $statusBot = false;
            // $telegram_id = '';
            // foreach ($request['data'] as $data) {
            //     if ($data['strukorder']['ruanganfk'] == 94) {
            //         $telegram_id = '-437464365';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 116) {
            //         $telegram_id = '-573225350';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 125) {
            //         $telegram_id = '-598519318';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 556) {
            //         $telegram_id = '-443840485';
            //         $statusBot = true;
            //     }
            //     if ($data['strukorder']['ruanganfk'] == 744) {

            //         $telegram_id = '-1001433844424';
            //         $statusBot = true;
            //     }
            //     $tglresep = $data['strukorder']['tglresep'];
            //     if ($statusBot) {
            //         $setting = $this->settingDataFixed('settingOrderTelegramLab', $idProfile);
            //         if (!empty($setting) &&  $setting == 'true') {
            //             // $from =  DB::table('ruangan_m')->where('id',$request['objectruanganfk'])->first();
            //             $to =  DB::table('ruangan_m')->where('id', $data['strukorder']['ruanganfk'])->first();
            //             $peg =  DB::table('pegawai_m')->where('id',  $dokter2)->first();
            //             $cito = '';
            //             if (isset($request['iscito']) && $request['iscito'] == 'true') {
            //                 $cito = ' Cito ';
            //             }
            //             if ($data['strukorder']['isreseppulang'] == 1) {
            //                 $cito = ' RESEP PULANG ';
            //             }
            //             $secret_token = "1545548931:AAHwGMJrXxGMc609WwO9e2UQTcRquu5ri-M";
            //             $produks = '';
            //             foreach ($prod as $key => $value) {
            //                 $produks = $produks . " \n " . $value;
            //             }

            //             $url = "https://api.telegram.org/bot" . $secret_token . "/sendMessage?parse_mode=html&chat_id=" . $telegram_id;
            //             $url = $url . "&text=" . urlencode("✅ Order Baru : <b> " . $to->namaruangan .
            //                 "</b>  \n Pengorder : <b>" . $peg->namalengkap . "</b>  \n Pasien : <b>" . $pasien->namapasien . " (" . $pasien->nocm . ") " .
            //                 "</b> \n Tgl Order: <b>" . $tglresep .
            //                 "</b> \n Status : <b>" . $cito . "</b>  \n Nama Obat : <b>" . $produks . "</b> \n\n");
            //             // return $url;
            //             $ch = curl_init();
            //             $optArray = array(
            //                 CURLOPT_URL => $url,
            //                 CURLOPT_RETURNTRANSFER => true
            //             );
            //             curl_setopt_array($ch, $optArray);
            //             $result = curl_exec($ch);
            //             curl_close($ch);
            //         }
            //     }
            // }
            $ruangan = Ruangan::where('id', $dataDetail->objectruanganfk)->first();
            $ruangan2 = Ruangan::where('id', $r_SR['ruanganfk'])->first();
            $mergeProduk = '';
            foreach ($pro as $value) {
                $mergeProduk = $mergeProduk . ", " . $value->namaproduk;
            }
            $mergeProduk = substr($mergeProduk, 1, strlen($mergeProduk) - 1);
            $this->LOGGING(
                'Order Resep',
                $newSO->norec,
                'strukorder_t',
                'Order Resep ' . $noOrder .
                    $mergeProduk
                    . ' dari ' . $ruangan->namaruangan
                    . ' ke ' . $ruangan2->namaruangan . ' pada Pasien ' .
                    $pasien->namapasien . ' (' .   $pasien->nocm . ') - ' .
                    $data['noregistrasi']
            );
            $transStatus = 'true';
        } catch (Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            $result = array(
                "status" => 200,
                "result" => array(
                    "resep" => $newSO,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine()
            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function simpanKPO(Request $request)
    {
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");
        try {
            // Siapkan data untuk dimasukkan ke tabel kpo_t
            $data = [
                'norec' => (string) \Illuminate\Support\Str::uuid(),
                'produkorderfk' => $request->produk_order ? $request->produk_order['id'] : null,
                'produkveriffk' => $request->id,
                'dokterfk' => $request->id_dokter,
                'pegawaivalidasifk' => $request->userData['id'],
                'qty' => $request->jumlah,
                'aturanpakai' => $request->aturanpakai,
                'lamahari' => $request->lamahari,
                'tglmulai' => $request->tanggalmulai,
                'strukresepfk' => $request->norec_resep,
                'noregistrasi' => $request['registrasi']['noregistrasi'],
                'iskpo' => true,
            ];
            // Simpan data ke tabel kpo_t
            DB::table('kpo_t')->insert($data);

            // Kembalikan respon berhasil
            $data = [
                'status' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $data,
            ];
        } catch (\Throwable $th) {
            // Tangani error
            $data = [
                'status' => 400,
                'message' => $th->getMessage(),
            ];
        }

        return $this->respond($data, $data['status']);
    }

    public function validasiPemberian(Request $request)
    {
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");
        try {

            $dataPemberian = DB::table('detailkpo_t')->where('kpofk', $request->id_kpo)->get();
            // Siapkan data untuk dimasukkan ke tabel kpo_t
            $data = [
                'norec' => (string) \Illuminate\Support\Str::uuid(),
                'kpofk' => $request->id_kpo,
                'pemberian' => count($dataPemberian) + 1,
                'jampemberian' => Carbon::parse($request->waktupemberian)->setTimezone('Asia/Jakarta')->format('H:i:s'),
                'tglpemberian' => Carbon::parse($request->waktupemberian)->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                'jaminput' => now(),
                'petugaspemberifk' => $request->petugaspemberifk
            ];

            // Simpan data ke tabel kpo_t
            DB::table('detailkpo_t')->insert($data);

            // Kembalikan respon berhasil
            $data = [
                'status' => 200,
                'message' => 'Data berhasil disimpan',
                'data' => $data,
            ];
        } catch (\Throwable $th) {
            // Tangani error
            $data = [
                'status' => 400,
                'message' => $th->getMessage(),
            ];
        }

        return $this->respond($data, $data['status']);
    }

    public function stopKPO(Request $request)
    {
        try {
            // Update data pada tabel kpo_t
            $affectedRows = DB::table('kpo_t')
                ->where('norec', $request->id_kpo)
                ->update([
                    'tglstop' => $request->tglstop,
                    'alasan' => $request->alasan,
                ]);

            // Jika tidak ada baris yang terpengaruh
            if ($affectedRows === 0) {
                $data = [
                    'status' => 404,
                    'message' => 'Data KPO tidak ditemukan atau sudah dihentikan',
                ];
            } else {
                $dataPemberian = DB::table('detailkpo_t')->where('kpofk', $request->id_kpo)->get();
                // Siapkan data untuk dimasukkan ke tabel kpo_t
                $data = [
                    'norec' => (string) \Illuminate\Support\Str::uuid(),
                    'kpofk' => $request->id_kpo,
                    'pemberian' => count($dataPemberian) + 1,
                    'jamstop' => Carbon::parse($request->tglstop)->setTimezone('Asia/Jakarta')->format('H:i:s'),
                    'tglstop' => Carbon::parse($request->tglstop)->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                    'jaminput' => now(),
                    'alasan' => $request->alasan,
                    'petugaspemberifk' => $request->petugaspemberifk
                ];

                // Simpan data ke tabel kpo_t
                DB::table('detailkpo_t')->insert($data);
            }

            // Kembalikan respon berhasil
            $data = [
                'status' => 200,
                'message' => 'KPO berhasil dihentikan',
                'data' => ['id_kpo' => $request->id_kpo, 'tglstop' => now()],
            ];
        } catch (\Throwable $th) {
            // Tangani error
            $data = [
                'status' => 400,
                'message' => $th->getMessage(),
            ];
        }

        return $this->respond($data, $data['status']);
    }

    public function gantiDosisKPO(Request $request)
    {
        try {
            // Update data pada tabel kpo_t
            $affectedRows = DB::table('kpo_t')
                ->where('norec', $request->id_kpo)
                ->update([
                    'tglstop' => now(),
                    'alasan' => "Ganti Dosis",
                ]);

            // Jika tidak ada baris yang terpengaruh
            if ($affectedRows === 0) {
                $data = [
                    'status' => 404,
                    'message' => 'Data KPO tidak ditemukan atau sudah dihentikan',
                ];
            } else {
                $dataPemberian = DB::table('detailkpo_t')->where('kpofk', $request->id_kpo)->get();
                // Siapkan data untuk dimasukkan ke tabel kpo_t
                $data = [
                    'norec' => (string) \Illuminate\Support\Str::uuid(),
                    'kpofk' => $request->id_kpo,
                    'pemberian' => count($dataPemberian) + 1,
                    'jamstop' => Carbon::parse(now())->setTimezone('Asia/Jakarta')->format('H:i:s'),
                    'tglstop' => Carbon::parse(now())->setTimezone('Asia/Jakarta')->format('Y-m-d'),
                    'jaminput' => now(),
                    'alasan' => "Ganti Dosis",
                    'petugaspemberifk' => $request->petugaspemberifk
                ];

                // Simpan data ke tabel kpo_t
                DB::table('detailkpo_t')->insert($data);
            }

            $data = [
                'norec' => (string) \Illuminate\Support\Str::uuid(),
                'produkorderfk' => $request->produk_order ? $request->produk_order['id'] : null,
                'produkveriffk' => $request->id,
                'dokterfk' => $request->userData['id'],
                'qty' => $request->jumlah,
                'aturanpakai' => $request->aturanpakaibaru,
                'lamahari' => $request->lamahari,
                'tglmulai' => $request->tanggalmulai,
                'strukresepfk' => $request->norec_resep,
                'iskpo' => true,
            ];

            // Simpan data ke tabel kpo_t
            DB::table('kpo_t')->insert($data);

            // Kembalikan respon berhasil
            $data = [
                'status' => 200,
                'message' => 'KPO berhasil diganti',
                'data' => ['id_kpo' => $request->id_kpo, 'tglstop' => now()],
            ];
        } catch (\Throwable $th) {
            // Tangani error
            $data = [
                'status' => 400,
                'message' => $th->getMessage(),
            ];
        }

        return $this->respond($data, $data['status']);
    }

    public function resepKPO(Request $request)
    {
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");
        try {
            $dataOrder = DB::table('strukorder_t as so')
                ->leftJoin('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                ->join('produk_m as prd', 'prd.id', 'op.objectprodukfk')
                ->join('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
                ->select(
                    'pg.namalengkap',
                    'pg.id as id_dokter',
                    'prd.id',
                    'prd.namaproduk',
                    'prd.objectgenerikfk as id_generik',
                    'op.jumlah',
                    'op.aturanpakai',
                    'so.norec as norec_order'
                )
                ->where('so.noregistrasifk', $request['norec_pd'])
                ->where('prd.objectkelompokprodukbpjsfk', 12)
                ->whereNull('so.isreseppulang')
                ->get();

            $dataVerif = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->leftJoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
                ->leftJoin('strukorder_t as so', 'so.norec', '=', 'sr.orderfk')
                ->leftJoin('kpo_t as kpo', function ($join) {
                    $join->on('kpo.strukresepfk', 'sr.norec')
                        ->on('kpo.produkveriffk', 'pp.produkfk');
                })
                ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
                ->join('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
                ->select(
                    'pg.namalengkap',
                    'pg.id as id_dokter',
                    'prd.id',
                    'prd.namaproduk',
                    'prd.objectgenerikfk as id_generik',
                    DB::raw('COALESCE(kpo.qty, pp.jumlah) as jumlah'),
                    DB::raw('COALESCE(kpo.aturanpakai, pp.aturanpakai) as aturanpakai'),
                    'so.norec as norec_order',
                    'sr.norec as norec_resep',
                    'kpo.norec as id_kpo',
                    'kpo.iskpo',
                    'kpo.lamahari',
                    'kpo.tglmulai',
                    'kpo.tglstop'
                )
                ->where('apd.noregistrasifk', $request['norec_pd'])
                ->where('prd.objectkelompokprodukbpjsfk', 12)
                ->whereNull('sr.isreseppulang')
                ->get();


            // Proses penggabungan data
            $mergedData = [];

            foreach ($dataVerif as $verif) {
                // Cari semua match di dataOrder dengan norec_order dan id_generik
                $matchingOrders = $dataOrder->filter(function ($order) use ($verif) {
                    return $order->norec_order === $verif->norec_order && $order->id_generik === $verif->id_generik;
                });

                $dataDetailKPO = DB::table('detailkpo_t as dkpo')
                    ->join('pegawai_m as pg', 'pg.id', 'dkpo.petugaspemberifk')
                    ->select('dkpo.*', 'pg.namalengkap')
                    ->where('kpofk', $verif->id_kpo)
                    ->get();

                foreach ($dataDetailKPO as $data) {
                    $data->tte = base64_encode(QrCode::format('svg')->size(75)->generate($data->namalengkap));
                }

                if (!isset($verif->tglstop)) {
                    if ($matchingOrders->isNotEmpty()) {
                        foreach ($matchingOrders as $orderMatch) {
                            if ($orderMatch->namaproduk !== $verif->namaproduk) {
                                // Jika produk berbeda, tambahkan data dari dataOrder
                                $mergedData[] = [
                                    'namalengkap' => $orderMatch->namalengkap,
                                    'id_dokter' => $orderMatch->id_dokter,
                                    'id' => $verif->id,
                                    'namaproduk' => $verif->namaproduk,
                                    'id_generik' => $orderMatch->id_generik,
                                    'jumlah' => $orderMatch->jumlah,
                                    'aturanpakai' => $orderMatch->aturanpakai,
                                    'norec_order' => $orderMatch->norec_order,
                                    'norec_resep' => $verif->norec_resep,
                                    'id_kpo' => $verif->id_kpo,
                                    'iskpo' => $verif->iskpo,
                                    'tglstop' => $verif->tglstop,
                                    'lamahari' => $verif->lamahari,
                                    'tglmulai' => $verif->tglmulai,
                                    'detailKPO' => $dataDetailKPO,
                                    'produk_order' => [
                                        'id' => $orderMatch->id,
                                        'namaproduk' => $orderMatch->namaproduk
                                    ],
                                    'replace' => true,
                                ];
                            } else {
                                // Tambahkan data dari dataVerif
                                $mergedData[] = [
                                    'namalengkap' => $verif->namalengkap,
                                    'id_dokter' => $verif->id_dokter,
                                    'id' => $verif->id,
                                    'namaproduk' => $verif->namaproduk,
                                    'id_generik' => $verif->id_generik,
                                    'jumlah' => $verif->jumlah,
                                    'aturanpakai' => $verif->aturanpakai,
                                    'norec_order' => $verif->norec_order,
                                    'norec_resep' => $verif->norec_resep,
                                    'id_kpo' => $verif->id_kpo,
                                    'iskpo' => $verif->iskpo,
                                    'tglstop' => $verif->tglstop,
                                    'lamahari' => $verif->lamahari,
                                    'tglmulai' => $verif->tglmulai,
                                    'detailKPO' => $dataDetailKPO,
                                    'produk_order' => [
                                        'id' => $verif->id,
                                        'namaproduk' => $verif->namaproduk
                                    ],
                                    'replace' => false,
                                ];
                            }
                        }
                    } else {
                        // Tambahkan data dari dataVerif
                        $mergedData[] = [
                            'namalengkap' => $verif->namalengkap,
                            'id_dokter' => $verif->id_dokter,
                            'id' => $verif->id,
                            'namaproduk' => $verif->namaproduk,
                            'id_generik' => $verif->id_generik,
                            'jumlah' => $verif->jumlah,
                            'aturanpakai' => $verif->aturanpakai,
                            'norec_order' => $verif->norec_order,
                            'norec_resep' => $verif->norec_resep,
                            'produk_order' => false,
                            'id_kpo' => $verif->id_kpo,
                            'iskpo' => $verif->iskpo,
                            'tglstop' => $verif->tglstop,
                            'lamahari' => $verif->lamahari,
                            'tglmulai' => $verif->tglmulai,
                            'detailKPO' => $dataDetailKPO,
                        ];
                    }
                }
            }

            foreach ($dataOrder as $order) {
                // Tambahkan data dari dataOrder yang tidak ada di dataVerif
                $matchingVerifs = $dataVerif->filter(function ($verif) use ($order) {
                    return $verif->norec_order === $order->norec_order && $verif->id_generik === $order->id_generik;
                });

                if ($matchingVerifs->isEmpty()) {
                    $mergedData[] = [
                        'namalengkap' => $order->namalengkap,
                        'id_dokter' => $order->id_dokter,
                        'id' => $order->id,
                        'namaproduk' => $order->namaproduk,
                        'id_generik' => $order->id_generik,
                        'jumlah' => $order->jumlah,
                        'aturanpakai' => $order->aturanpakai,
                        'norec_order' => $order->norec_order,
                        'norec_resep' => null,
                        'produk_order' => true,
                        'detailKPO' => [],
                    ];
                }
            }

            $data = [
                'status' => 200,
                'data' => $mergedData,
            ];
        } catch (\Exception $e) {
            $data = [
                'status' => 400,
                'message' => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
            ];
        }

        return $this->respond($data, $data['status']);
    }

    public function resepKPOStop(Request $request)
    {
        DB::statement("SET TIME ZONE 'Asia/Jakarta';");
        try {
            $dataOrder = DB::table('strukorder_t as so')
                ->leftJoin('orderpelayanan_t as op', 'op.strukorderfk', 'so.norec')
                ->join('produk_m as prd', 'prd.id', 'op.objectprodukfk')
                ->join('pegawai_m as pg', 'pg.id', 'so.objectpegawaiorderfk')
                ->select(
                    'pg.namalengkap',
                    'pg.id as id_dokter',
                    'prd.id',
                    'prd.namaproduk',
                    'prd.objectgenerikfk as id_generik',
                    'op.jumlah',
                    'op.aturanpakai',
                    'so.norec as norec_order'
                )
                ->where('so.noregistrasifk', $request['norec_pd'])
                ->where('prd.objectkelompokprodukbpjsfk', 12)
                ->whereNull('so.isreseppulang')
                ->get();

            $dataVerif = DB::table('pelayananpasien_t as pp')
                ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
                ->leftJoin('strukresep_t as sr', 'sr.norec', '=', 'pp.strukresepfk')
                ->leftJoin('strukorder_t as so', 'so.norec', '=', 'sr.orderfk')
                ->leftJoin('kpo_t as kpo', function ($join) {
                    $join->on('kpo.strukresepfk', 'sr.norec')
                        ->on('kpo.produkveriffk', 'pp.produkfk');
                })
                ->join('produk_m as prd', 'prd.id', '=', 'pp.produkfk')
                ->join('pegawai_m as pg', 'pg.id', '=', 'sr.penulisresepfk')
                ->select(
                    'pg.namalengkap',
                    'pg.id as id_dokter',
                    'prd.id',
                    'prd.namaproduk',
                    'prd.objectgenerikfk as id_generik',
                    DB::raw('COALESCE(kpo.qty, pp.jumlah) as jumlah'),
                    DB::raw('COALESCE(kpo.aturanpakai, pp.aturanpakai) as aturanpakai'),
                    'so.norec as norec_order',
                    'sr.norec as norec_resep',
                    'kpo.norec as id_kpo',
                    'kpo.iskpo',
                    'kpo.lamahari',
                    'kpo.tglmulai',
                    'kpo.tglstop'
                )
                ->where('apd.noregistrasifk', $request['norec_pd'])
                ->where('prd.objectkelompokprodukbpjsfk', 12)
                ->whereNull('sr.isreseppulang')
                ->orderBy('kpo.tglstop', 'DESC')
                ->get();


            // Proses penggabungan data
            $mergedData = [];

            foreach ($dataVerif as $verif) {
                $dataDetailKPO = DB::table('detailkpo_t')->where('kpofk', $verif->id_kpo)->get();
                // Cari semua match di dataOrder dengan norec_order dan id_generik
                $matchingOrders = $dataOrder->filter(function ($order) use ($verif) {
                    return $order->norec_order === $verif->norec_order && $order->id_generik === $verif->id_generik;
                });

                if (isset($verif->tglstop)) {
                    if ($matchingOrders->isNotEmpty()) {
                        foreach ($matchingOrders as $orderMatch) {
                            if ($orderMatch->namaproduk !== $verif->namaproduk) {
                                // Jika produk berbeda, tambahkan data dari dataOrder
                                $mergedData[] = [
                                    'namalengkap' => $orderMatch->namalengkap,
                                    'id_dokter' => $orderMatch->id_dokter,
                                    'id' => $verif->id,
                                    'namaproduk' => $verif->namaproduk,
                                    'id_generik' => $orderMatch->id_generik,
                                    'jumlah' => $orderMatch->jumlah,
                                    'aturanpakai' => $orderMatch->aturanpakai,
                                    'norec_order' => $orderMatch->norec_order,
                                    'norec_resep' => $verif->norec_resep,
                                    'id_kpo' => $verif->id_kpo,
                                    'iskpo' => $verif->iskpo,
                                    'tglstop' => $verif->tglstop,
                                    'lamahari' => $verif->lamahari,
                                    'tglmulai' => $verif->tglmulai,
                                    'detailKPO' => $dataDetailKPO,
                                    'jumlahpemberian' => count($dataDetailKPO) - 1,
                                    'produk_order' => [
                                        'id' => $orderMatch->id,
                                        'namaproduk' => $orderMatch->namaproduk
                                    ],
                                    'replace' => true,
                                ];
                            } else {
                                // Tambahkan data dari dataVerif
                                $mergedData[] = [
                                    'namalengkap' => $verif->namalengkap,
                                    'id_dokter' => $verif->id_dokter,
                                    'id' => $verif->id,
                                    'namaproduk' => $verif->namaproduk,
                                    'id_generik' => $verif->id_generik,
                                    'jumlah' => $verif->jumlah,
                                    'aturanpakai' => $verif->aturanpakai,
                                    'norec_order' => $verif->norec_order,
                                    'norec_resep' => $verif->norec_resep,
                                    'id_kpo' => $verif->id_kpo,
                                    'iskpo' => $verif->iskpo,
                                    'tglstop' => $verif->tglstop,
                                    'lamahari' => $verif->lamahari,
                                    'tglmulai' => $verif->tglmulai,
                                    'detailKPO' => $dataDetailKPO,
                                    'jumlahpemberian' => count($dataDetailKPO) - 1,
                                    'produk_order' => [
                                        'id' => $verif->id,
                                        'namaproduk' => $verif->namaproduk
                                    ],
                                    'replace' => false,
                                ];
                            }
                        }
                    } else {
                        // Tambahkan data dari dataVerif
                        $mergedData[] = [
                            'namalengkap' => $verif->namalengkap,
                            'id_dokter' => $verif->id_dokter,
                            'id' => $verif->id,
                            'namaproduk' => $verif->namaproduk,
                            'id_generik' => $verif->id_generik,
                            'jumlah' => $verif->jumlah,
                            'aturanpakai' => $verif->aturanpakai,
                            'norec_order' => $verif->norec_order,
                            'norec_resep' => $verif->norec_resep,
                            'produk_order' => false,
                            'id_kpo' => $verif->id_kpo,
                            'iskpo' => $verif->iskpo,
                            'tglstop' => $verif->tglstop,
                            'lamahari' => $verif->lamahari,
                            'tglmulai' => $verif->tglmulai,
                            'detailKPO' => $dataDetailKPO,
                            'jumlahpemberian' => count($dataDetailKPO) - 1,
                        ];
                    }
                }
            }

            $data = [
                'status' => 200,
                'data' => $mergedData,
            ];
        } catch (\Exception $e) {
            $data = [
                'status' => 400,
                'message' => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine(),
            ];
        }

        return $this->respond($data, $data['status']);
    }



    public function riwayatOrderResepCppt(Request $request)
    {
        $idProfile = $this->kdProfile;
        $nocmfk = '';
        $norec_pd = '';
        $penulisresep = '';
        $limit = $request['limit'] ?? 10;
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
                'so.isiter',
                'so.iterJumlah',
                'so.noorder',
                'ru.namaruangan as namaruanganrawat',
                'so.tglorder',
                'so.tglorder as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.statusorder',
                'so.isiter',
                'so.iterJumlah',
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
            ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk)
            ->take(5);

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('pd.nocmfk', '=', $request['nocmfk']);
            $nocmfk = " and pd.nocmfk='" . $request['nocmfk'] . "'";
        }
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', $request['norec_pd']);
            $norec_pd =  " and pd.norec='" . $request['norec_pd'] . "'";
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data = $data->where('so.objectpegawaiorderfk', $request['penulisresepfk']);
            $penulisresep =  " and so.objectpegawaiorderfk='" . $request['penulisresepfk'] . "'";
        }
        // if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
        // }
        $data = $data->limit($limit);
        $data = $data->orderByDesc('so.tglorder');
        $data = $data->get();

        $details = DB::select(
            DB::raw("
                SELECT
                so.norec as norec_so,
                op.rke, jk.jeniskemasan,
                pr.namaproduk, ss.satuanstandar, op.aturanpakai,
                op.jumlah, op.hargasatuan,op.keteranganpakai as keterangan,
                op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,
                op.qtystokcurrent as jmlstok,
                op.jeniskemasanfk,jk.id as jkid,op.routefk,rt.name as namaroute,
                op.objectprodukfk,pr.namaproduk,op.hasilkonversi,
                op.objectsatuanstandarfk,ss.satuanstandar,op.satuanviewfk,ss2.satuanstandar as ssview,
                op.qtyproduk,op.hargadiscount,op.hasilkonversi as nilaikonversi,op.dosis,op.jenisobatfk,
                pr.kekuatan,sdn.name as sediaan,op.ispagi,op.issiang,op.ismalam,
                op.issore,op.keteranganpakai,op.satuanresepfk,sn.satuanresep,op.tglkadaluarsa,op.nostrukterimafk,
                op.objectasalprodukfk as asalprodukfk,asl.asalproduk, pg.namalengkap
                from strukorder_t as so
                inner join orderpelayanan_t as op on op.strukorderfk = so.norec
                inner join pasiendaftar_t as pd on pd.norec = so.noregistrasifk
                inner join produk_m as pr on pr.id=op.objectprodukfk
                left join jeniskemasan_m as jk on jk.id=op.jeniskemasanfk
                left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
                left join satuanstandar_m as ss on ss.id=op.objectsatuanstandarfk
                left join satuanstandar_m as ss2 on ss2.id=op.satuanviewfk
                left join satuanresep_m as sn on sn.id=op.satuanresepfk
                JOIN ruangan_m as ru on ru.id=so.objectruangantujuanfk
                left join routefarmasi as rt on rt.id=op.routefk
                left join rm_sediaan_m as sdn on sdn.id=pr.objectsediaanfk
                left join asalproduk_m as asl on asl.id=op.objectasalprodukfk
                where so.kdprofile = $idProfile
                and so.objectkelompoktransaksifk = $set->objectkelompoktransaksifk
                and so.statusenabled = true
                $nocmfk
                $norec_pd
                $penulisresep
                ORDER BY op.rke
               ")
        );

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
            ->where('so.statusenabled', true);

        if (isset($request['isVerif']) && $request['isVerif'] != "" && $request['isVerif'] != "undefined") {
            $data2 = $data2->whereNotNull('so.kdprofile');
        } else {
            $data2 = $data2->whereNull('so.kdprofile');
        }

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
            $data2 = $data2->limit($request['limit']);
        }
        $data2 = $data2->orderByDesc('so.tglresep');
        $data2 = $data2->get();

        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
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

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();

        foreach ($data as $item) {
            $item->jenis = 'Order';
            $item->details = [];
            if (isset($request['verif']) && $request['verif']  == 'true') {
                foreach ($details2 as $itemd) {
                    if ($item->norec_resep == $itemd->norec_so) {
                        $item->details[] = $itemd;
                    }
                }
            } else {
                foreach ($details as $itemd) {
                    if ($item->norec_so == $itemd->norec_so) {
                        $item->details[] = $itemd;
                    }
                }
            }
            // if($item->norec_resep != null){ //ambil ke pelayanapasien kalo udah verif
            //     foreach ($details2 as $itemd) {
            //         if ($item->norec_resep == $itemd->norec_so) {
            //             $item->details[] = $itemd;
            //         }
            //     }
            // }else{
            //     foreach ($details as $itemd) {
            //         if ($item->norec_so == $itemd->norec_so) {
            //             $item->details[] = $itemd;
            //         }
            //     }
            // }

        }

        foreach ($data2 as $item) {
            $item->jenis = 'Verif';
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }


        // if(count($data2)> 0 && count($data2)> 0){
        $data =  array_merge($data->toArray(), $data2->toArray());
        // }

        // foreach ($data2 as $no => $item) {
        //     $nKonversi = 1;
        //     $item->no = $no + 1;
        //     if ($item->hasilkonversi != null) {
        //         $nKonversi = $item->hasilkonversi;
        //     }
        //     if ($item->satuanstandar != null) {
        //         $ss = $item->satuanstandar;
        //     } else {
        //         $ss = $item->satuanstandar2;
        //     }
        //     $JenisKemasan = $item->jeniskemasan;
        //     if ($item->jenisracikan != null) {
        //         $JenisKemasan = $item->jeniskemasan . '/' . $item->jenisracikan;
        //     }
        //     $jasa = 0;
        //     if ($item->jasa != null && $item->jasa != '') {
        //         $jasa = $item->jasa;
        //     }
        //     $item->jumlah =  (float)$item->jumlah / (float)$nKonversi;
        //     $item->satuanstandar =  $ss;
        //     $item->noregistrasi = $data->noregistrasi;
        //     $item->jasa = $jasa;
        //     $item->jeniskemasan = $JenisKemasan;

        //     $item->total = (((float)$item->jumlah * (float)$item->hargasatuan) - (float)$item->hargadiscount) + (float)     $item->jasa;
        // }


        return $this->respond($data);
    }

    public function riwayatOrderResep(Request $request)
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
                'so.isiter',
                'so.iterJumlah',
                'so.noorder',
                'ru.namaruangan as namaruanganrawat',
                'so.tglorder',
                'so.tglorder as tglorder_def',
                'pg.namalengkap',
                'ru2.namaruangan',
                'so.statusorder',
                'so.isiter',
                'so.iterJumlah',
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

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data = $data->where('pd.nocmfk', '=', $request['nocmfk']);
            $nocmfk = " and pd.nocmfk='" . $request['nocmfk'] . "'";
        }
        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data = $data->where('pd.norec', $request['norec_pd']);
            $norec_pd =  " and pd.norec='" . $request['norec_pd'] . "'";
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data = $data->where('so.objectpegawaiorderfk', $request['penulisresepfk']);
            $penulisresep =  " and so.objectpegawaiorderfk='" . $request['penulisresepfk'] . "'";
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data = $data->where('so.objectruanganfk', $request['ruanganfk']);
        }

        $page = 1;
        if (isset($request['page']) && $request['page'] != '') {
            $page = $request['page'];
        }

        $data = $data->orderByDesc('so.tglorder')
            ->paginate(isset($request['limit']) ? $request['limit'] : 5, ['*'], 'page', $page);

        $detailsQuery = DB::table('strukorder_t as so')
            ->select(
                'so.norec as norec_so',
                'op.rke',
                'jk.jeniskemasan',
                'pr.namaproduk',
                'ss.satuanstandar',
                'op.aturanpakai',
                'op.jumlah',
                'op.hargasatuan',
                'op.keteranganpakai as keterangan',
                'op.satuanresepfk',
                'sn.satuanresep',
                'op.tglkadaluarsa',
                'op.qtystokcurrent as jmlstok',
                'op.jeniskemasanfk',
                'jk.id as jkid',
                'op.routefk',
                'rt.name as namaroute',
                'op.objectprodukfk',
                'pr.namaproduk',
                'op.hasilkonversi',
                'op.objectsatuanstandarfk',
                'ss.satuanstandar',
                'op.satuanviewfk',
                'ss2.satuanstandar as ssview',
                'op.qtyproduk',
                'op.hargadiscount',
                'op.hasilkonversi as nilaikonversi',
                'op.dosis',
                'op.jenisobatfk',
                'pr.kekuatan',
                'sdn.name as sediaan',
                'op.ispagi',
                'op.issiang',
                'op.ismalam',
                'op.issore',
                'op.keteranganpakai',
                'op.satuanresepfk',
                'sn.satuanresep',
                'op.tglkadaluarsa',
                'op.nostrukterimafk',
                'op.objectasalprodukfk as asalprodukfk',
                'asl.asalproduk'
            )
            ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->leftJoin('jeniskemasan_m as jk', 'jk.id', '=', 'op.jeniskemasanfk')
            ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'op.objectsatuanstandarfk')
            ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'op.satuanviewfk')
            ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'op.satuanresepfk')
            ->join('ruangan_m as ru', 'ru.id', '=', 'so.objectruangantujuanfk')
            ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'op.routefk')
            ->leftJoin('rm_sediaan_m as sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            ->leftJoin('asalproduk_m as asl', 'asl.id', '=', 'op.objectasalprodukfk')
            ->where('so.kdprofile', $idProfile)
            ->where('so.objectkelompoktransaksifk', $set->objectkelompoktransaksifk)
            ->where('so.statusenabled', true)
            ->when($request->filled('nocmfk'), function ($query) use ($request) {
                return $query->where('pd.nocmfk', $request->nocmfk);
            })
            ->when($request->filled('norec_pd'), function ($query) use ($request) {
                return $query->where('pd.norec', $request->norec_pd);
            })
            ->when($request->filled('penulisresepfk'), function ($query) use ($request) {
                return $query->where('so.objectpegawaiorderfk', $request->penulisresepfk);
            })
            ->when($request->filled('ruanganfk'), function ($query) use ($request) {
                return $query->where('so.objectruanganfk', $request->ruanganfk);
            });

        if ($request->filled('namaobat')) {
            $detailsQuery->where('pr.namaproduk', 'ilike', '%' . $request->namaobat . '%');
        }

        $details = $detailsQuery->orderBy('op.rke')->get();


        foreach ($data as $item) {
            $item->jenis = 'Order';
            $item->details = [];
            foreach ($details as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }

        $dataArray = $data->toArray();

        return $this->respond($dataArray);
    }


    public function riwayatOrderResepVerif(Request $request)
    {
        $idProfile = $this->kdProfile;
        $nocmfk = '';
        $norec_pd = '';
        $penulisresep = '';
        // $limit = $request['limit'] ?? 10;
        $set = json_decode($this->settingFix('kelompokTransaksiOrderResep'));
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
            ->where('so.statusenabled', true);

        if (isset($request['isVerif']) && $request['isVerif'] != "" && $request['isVerif'] != "undefined") {
            $data2 = $data2->whereNotNull('so.kdprofile');
        } else {
            $data2 = $data2->whereNull('so.kdprofile');
        }

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        if (isset($request['ruanganfk']) && $request['ruanganfk'] != "" && $request['ruanganfk'] != "undefined") {
            $data2 = $data2->where('apd.objectruanganfk', $request['ruanganfk']);
        }
        // if (isset($request['limit']) && $request['limit'] != "" && $request['limit'] != "undefined") {
        //     $data2 = $data2->limit($request['limit']);

        // }

        $page = 1;
        if (isset($request['page']) && $request['page'] != '') {
            $page = $request['page'];
        }
        $data2 = $data2->orderByDesc('so.tglresep');
        $data2 = $data2->paginate(isset($request['limit']) ? $request['limit'] : 5, ['*'], 'page', $page);
        $noregistrasifk = null;
        foreach ($data2 as $item) {
            $noregistrasifk = $item->noregistrasifk;
            if ($noregistrasifk) {
                break;
            }
        }
        // $data2 = $data2->get();
        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
            ->JOIN('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
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

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
        }

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $details2 = $details2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        if (isset($request['namaobat']) && $request['namaobat'] != '') {
            $details2 = $details2->where('pr.namaproduk', 'ilike', '%' . $request['namaobat'] . '%');
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();

        foreach ($data2 as $item) {
            $item->jenis = 'Verif';
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }

        $data =  $data2->toArray();

        return $this->respond($data);
    }

    public function riwayatResepPulang(Request $request)
    {
        $idProfile = $this->kdProfile;
        $data2 = DB::table('strukresep_t as so')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.pasienfk')
            ->leftJoin('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'so.noregistrasi')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
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
                'ps.namapasien',
                'so.pasienfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'so.tglambilresep as tglambilorder',
                'so.orderfk as norec_order',
                'so.isreseppulang',
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'apd.norec as norec_apd',
                'so.ruanganfk as objectruangantujuanfk',
                'so.penulisresepfk as objectpegawaiorderfk'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.isreseppulang', true)
            ->where('so.statusenabled', true);
        // ->whereNotNull('so.orderfk');

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data2 = $data2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        $data2 = $data2->orderByDesc('so.tglresep');
        $data2 = $data2->get();

        // return $data2;

        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
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

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();
        foreach ($data2 as $item) {
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    $item->details[] = $itemd;
                }
            }
        }
        return $this->respond($data2);
    }

    public function riwayatOrderResepPulang(Request $request)
    {
        $idProfile = $this->kdProfile;
        $data2 = DB::table('strukorder_t as so')
            ->leftJoin('strukresep_t as sr', 'sr.orderfk', 'so.norec')
            ->leftJoin('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'so.norec_apd')
            ->leftJoin('pasiendaftar_t as pd', 'pd.noregistrasi', '=', 'so.noregistrasi')
            ->join('pasien_m as ps', 'ps.id', 'pd.nocmfk')
            ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            ->JOIN('ruangan_m as ru2', 'ru2.id', '=', 'so.objectruangantujuanfk')
            ->leftJOIN('statuspengerjaan_m as sp', 'sp.id', '=', 'sr.status')
            ->leftJOIN('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->select(
                'so.norec as norec_so',
                'sr.norec as norec_sr',
                'sr.noresep as noresep',
                'ru.namaruangan as namaruanganrawat',
                'so.tglorder',
                'sr.tglresep',
                'pg.namalengkap',
                'ru2.namaruangan',
                'sr.status as statusorder',
                'sr.namalengkapambilresep as namapengambilorder',
                'pd.norec as noregistrasifk',
                'ps.namapasien',
                'sr.pasienfk',
                'pd.noregistrasi',
                'pd.tglregistrasi',
                'sr.tglambilresep as tglambilorder',
                'sr.orderfk as norec_order',
                'so.isreseppulang',
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'apd.norec as norec_apd',
                'sr.ruanganfk as objectruangantujuanfk',
                'sr.penulisresepfk as objectpegawaiorderfk'
            )
            ->where('so.kdprofile', $idProfile)
            ->where('so.isreseppulang', true)
            ->where('so.statusenabled', true);
        // ->whereNotNull('sr.orderfk');

        if (isset($request['nocmfk']) && $request['nocmfk'] != "" && $request['nocmfk'] != "undefined") {
            $data2 = $data2->where('pd.nocmfk', '=', $request['nocmfk']);
        }

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $data2 = $data2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $data2 = $data2->where('sr.penulisresepfk', $request['penulisresepfk']);
        }
        $data2 = $data2->orderByDesc('so.tglorder');
        $data2 = $data2->get();

        // return $data2;

        $details2 = DB::table('orderpelayanan_t as op')
            ->JOIN('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            // ->JOIN('ruangan_m as ru', 'ru.id', '=', 'apd.objectruanganfk')
            // ->JOIN('jeniskemasan_m as jk', 'jk.id', '=', 'op.jeniskemasanfk')
            // ->leftJoin('jenisracikan_m as jra', 'jra.id', '=', 'op.jenisracikanfk')
            // ->leftJoin('satuanstandar_m as ss', 'ss.id', '=', 'op.satuanviewfk')
            // ->leftJoin('satuanstandar_m as ss2', 'ss2.id', '=', 'pr.objectsatuanstandarfk')
            // ->JOIN('detailjenisproduk_m as djp', 'djp.id', '=', 'pr.objectdetailjenisprodukfk')
            // ->JOIN('jenisproduk_m as jp', 'jp.id', '=', 'djp.objectjenisprodukfk')
            // ->leftJOIN('konversisatuan_t as ks', function ($join) {
            //     $join->on('ks.objekprodukfk', '=', 'pr.id')
            //         ->on('ks.satuanstandar_tujuan', '=', 'op.satuanviewfk');
            // })
            // ->leftJOIN('rm_sediaan_m AS sdn', 'sdn.id', '=', 'pr.objectsediaanfk')
            // ->leftJoin('satuanresep_m as sn', 'sn.id', '=', 'op.satuanresepfk')
            // ->leftJoin('routefarmasi as rt', 'rt.id', '=', 'op.routefk')
            ->JOIN('pasiendaftar_t as pd', 'pd.noregistrasi', 'op.noregistrasi')
            ->leftJoin("strukorder_t as so", 'so.norec', 'op.strukorderfk')
            // ->leftJoin('strukresep_t as sr', 'sr.orderfk', 'op.strukorderfk')
            ->select(
                DB::raw("op.strukorderfk as norec_so, pr.namaproduk, op.aturanpakai, op.jumlah, op.rke")
            )
            ->where('op.kdprofile', $this->kdProfile);

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('pd.norec', $request['norec_pd']);
        }
        if (isset($request['penulisresepfk']) && $request['penulisresepfk'] != "" && $request['penulisresepfk'] != "undefined") {
            $details2 = $details2->where('so.penulisresepfk', $request['penulisresepfk']);
        }
        $details2 = $details2->orderBy('op.tglpelayanan');
        $details2 = $details2->orderBy('op.rke');
        $details2 = $details2->get();
        // return $details2;
        foreach ($data2 as $item) {
            $item->details = [];
            foreach ($details2 as $itemd) {
                if ($item->norec_so == $itemd->norec_so) {
                    if($item->norec_sr){
                        $itemd->status = 'Verfikasi';
                    }else{
                        $itemd->status = 'Belum Verfikasi';
                    }
                    $item->details[] = $itemd;
                }
            }
        }
        return $this->respond($data2);
    }


    public function hapusOrderResep(Request $request)
    {

        DB::beginTransaction();
        try {
            $idProfile = (int) $this->kdProfile;
            $so = StrukOrder::where('norec', $request['norec'])->where('kdprofile', $idProfile)->first();
            if ($so->statusorder == 5) {
                $transMessage = "Tidak Bisa dihapus sudah Di Verifikasi";

                DB::rollBack();
                $result = array("status" => 400, "result"  => null);
                return $this->respond($result['result'], $result['status'], $transMessage);
            }
            StrukOrder::where('norec', $request['norec'])->update(['statusenabled' => false]);

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
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan Gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getOrderResepNow(Request $request)
    {
        $tglawal = date('Y-m-d') . " 00:00";
        $tglakhir = date('Y-m-d') . " 23:59";
        $dataOrder = DB::table('strukorder_t as so')
            ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->join('orderpelayanan_t as op', 'op.strukorderfk', '=', 'so.norec')
            ->join('produk_m as pr', 'pr.id', '=', 'op.objectprodukfk')
            ->select(
                'so.noorder as nopesan',
                'pr.id as idproduk',
                'pr.namaproduk',
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->whereBetween('so.tglorder', [$tglawal, $tglakhir])
            ->where('pd.norec', $request['norec'])
            ->get();

        // Aktifkan jika user pengen dari input resep juga
        // $dataResep = DB::table('strukresep_t as so')
        // ->join('pelayananpasien_t as pp', 'pp.strukresepfk', '=', 'so.norec')
        // ->join('produk_m as pr', 'pr.id', '=', 'pp.produkfk')
        // ->join('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
        // ->join('pasiendaftar_t as pd', 'pd.norec', '=', 'apd.noregistrasifk')
        // ->select(
        //     'so.noresep as nopesan',
        //     'pr.id as idproduk',
        //     'pr.namaproduk',
        // )
        // ->where('so.kdprofile', $this->kdProfile)
        // ->where('so.statusenabled', true)
        // ->whereBetween('so.tglresep', [$tglawal, $tglakhir])
        // ->where('pd.norec', $request['norec'])
        // ->get();

        // $result =  array_merge($dataOrder->toArray(),$dataResep->toArray());

        return $this->respond($dataOrder);
    }

    public function resepVerif(Request $request)
    {
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
                'sp.statuspengerjaan',
                'sp.reportdisplay as color_status',
                'apd.norec as norec_apd',
                'so.ruanganfk as objectruangantujuanfk',
                'so.penulisresepfk as objectpegawaiorderfk'
            )
            ->where('so.kdprofile', $this->kdProfile)
            ->where('so.statusenabled', true)
            ->whereNull('so.orderfk')
            ->where('pd.nocmfk', $request['nocmfk'])
            ->where('pd.norec', $request['norec_pd'])
            ->orderByDesc('so.tglresep')
            ->get();

        return $data2;

        $details2 = DB::table('pelayananpasien_t as pp')
            ->JOIN('antrianpasiendiperiksa_t as apd', 'apd.norec', '=', 'pp.noregistrasifk')
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

        if (isset($request['norec_pd']) && $request['norec_pd'] != "" && $request['norec_pd'] != "undefined") {
            $details2 = $details2->where('apd.noregistrasifk', $request['norec_pd']);
        }
        $details2 = $details2->orderBy('pp.tglpelayanan');
        $details2 = $details2->orderBy('pp.rke');
        $details2 = $details2->get();
    }

    public function getDataPaketObat(Request $request)
    {
        $result = [];
        $data = DB::table('paketobat_m as sp')
            ->select('sp.id as paketId', 'sp.namapaket')
            ->where('sp.kdprofile', $this->kdProfile)
            ->where('sp.statusenabled', true);

        if (isset($request['paketId']) && $request['paketId'] != '') {
            $data = $data->where('sp.id', $request['paketId']);
        }
        if (isset($request['namaPaket']) && $request['namaPaket'] != '') {
            $data = $data->where('sp.namapaket', 'ilike', '%' . $request['namaPaket'] . '%');
        }
        $data = $data->get();
        foreach ($data as $item) {
            $details = DB::select(
                DB::raw("SELECT pkd.*,pro.namaproduk,pro.objectsatuanstandarfk,ss.satuanstandar,
                         pkd.qty as jumlah,sn.satuanresep
                    FROM paketobatd_m as pkd
                    INNER JOIN produk_m As pro ON pro.id = pkd.produkfk
                    LEFT JOIN satuanstandar_m AS ss ON ss.id = pro.objectsatuanstandarfk
                    LEFT JOIN satuanresep_m AS sn ON sn.id = pkd.satuanresepfk
                    where pkd.kdprofile = $this->kdProfile and pkd.objectpaketobatfk=:norec"),
                array(
                    'norec' => $item->paketId,
                )
            );
            $result[] = array(
                'paketId' => $item->paketId,
                'namapaket' => $item->namapaket,
                'details' => $details,
            );
        }
        $result = array(
            'data' => $result,
            'as' => 'epic'
        );
        return $this->respond($result);
    }
}
