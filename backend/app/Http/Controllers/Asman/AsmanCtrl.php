<?php

namespace App\Http\Controllers\Asman;

use App\Http\Controllers\Controller;
use App\Models\Master\PaketKalibrasi;
use App\Traits\Valet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\App;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\File;
use Stichoza\GoogleTranslate\GoogleTranslate;

class AsmanCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function listMitraAsmanGrid(Request $r)
    {
        $tglAwal = $r['dari'];
        $tglAkhir = $r['sampai'];
        $data  = DB::table('mitra_m as mt')
            ->leftjoin('mitraregistrasi_t as mtr', 'mtr.nomitrafk', '=', 'mt.id')
            ->leftjoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
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
            )
            ->where('mt.statusenabled', true)
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->whereBetween('mtr.tglregistrasi', [$tglAwal, $tglAkhir]);
        $filter = false;
        if (isset($r['search']) && $r['search'] != '') {
            $filter = true;
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('mtr.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        if (isset($r['statusorder']) && $r['statusorder'] != '') {
            $data = $data->where('mtr.statusorder', '=', $r['statusorder']);
        }
        $page = 1;
        if (isset($r['page']) && $r['page'] != '') {
            $page = $r['page'];
        }
        $data = $data->orderByDesc('mtr.tglregistrasi');
        $data = $data->paginate(isset($r['limit']) ? $r['limit'] : 10, ['*'], 'page', $page);

        foreach ($data as $d) {
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
        }

        return $this->respond($data);
    }

    public function getAlatAsman(Request $r)
    {
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
                'mtrd.noorderalat',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.tglsetujuasmanlembarkerja',
                'mtrd.asmansetujulembarkerjafk',
                'mtrd.statusorderasman',
                'mtrd.tglverifpelaksana',
                'mtrd.penyeliasetujulaporanrepairfk',
                'mtrd.asmansetujulaporanrepairfk',
                'mtr.jenisorder',
            )
            ->where('mtr.statusorder', 1)
            ->where('mtr.iskaji', true)
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true);

        if (isset($r['dari']) && $r['dari'] != '') {
            $data = $data->where(DB::raw("mtr.tglverifasman::date"), '>=', $r->dari);
        }
        if (isset($r['sampai']) && $r['sampai'] != '') {
            $data = $data->where(DB::raw("mtr.tglverifasman::date"), '<=', $r->sampai);
        }
        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('mt.noorderalat', 'ilike', $searchTerm)
                    ->orWhere('mt.namaproduk', 'ilike', $searchTerm)
                    ->orWhere('mt.nopendaftaran', 'ilike', $searchTerm);
            });
        }
        if (isset($r['statusorderasman']) && $r['statusorderasman'] != '') {
            $data = $data->where('mtrd.statusorderasman', '=', $r['statusorderasman']);
        }
        $data = $data->orderBy('lp.lingkupkalibrasi');
        if (isset($r['limit'])) {
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        foreach ($data as $item) {
            $durasi = (int) $item->durasikalbrasi;
            $tglAwal = $item->tglverifpelaksana ? Carbon::parse($item->tglverifpelaksana)->startOfDay() : null;
            $tglAkhir = $item->tglsetujuasmanlembarkerja ? Carbon::parse($item->tglsetujuasmanlembarkerja)->startOfDay() : null;

            if ($tglAwal && $tglAkhir) {
                $selisih = $tglAkhir->diffInDays($tglAwal);

                if ($selisih == $durasi) {
                    $item->statusPengerjaan = 'Kalibrasi Tepat waktu';
                    $item->statusColor = 'warning';
                } elseif ($selisih > $durasi) {
                    $item->statusPengerjaan = 'Kalibrasi Terlambat ' . ($selisih - $durasi) . ' hari';
                    $item->statusColor = 'danger';
                } else {
                    $item->statusPengerjaan = 'Kalibrasi Lebih cepat ' . ($durasi - $selisih) . ' hari';
                    $item->statusColor = 'info';
                }
            } else {
                $item->statusPengerjaan = null;
                $item->statusColor = null;
            }
        }
        $res['data'] = $data;
        return $this->respond($res);
    }

    public function getLembarKerjaAsman(Request $request)
    {
        $data = DB::table('lembarkerja_t as lk')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
            ->select('lk.*')
            ->where('mtrd.norec', $request->norecdetail)
            ->where('lk.statusenabled', true)
            ->get();

        return $this->respond($data);
    }

    public function setujuiSertifikatAsman(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['verif'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'setujuilembarkerjaasman' => true,
                    'asmansetujulembarkerjafk' => $this->getPegawaiId(),
                    'tglsetujuasmanlembarkerja' => now(),
                    'statusorderasman' => 2
                ]);


            $transMessage = "Simpan Setujui Sertifikat Sukses";
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

    public function getAsmanDetail(Request $r)
    {
        $jabatanIds = [17, 20, 3, 7, 8, 4, 19, 9, 5, 18, 13, 15, 12, 16, 14];

        $pegawaiJakarta = DB::table('pegawai_m as pg')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->select(
                'pg.id as pegawai_id',
                'pg.namalengkap',
                'pg.jabatan1fk',
                'jb.id as jabatan_id',
                'jb.namajabatanulab'
            )
            ->where('pg.statusenabled', true)
            ->whereIn('jb.id', $jabatanIds)
            ->where('pg.lokasikalibrasifk', 1)
            ->where('jb.statusenabled', true);

        if (isset($r['limit']) && $r['limit'] != '') {
            $pegawaiJakarta->limit($r['limit']);
        }

        $pegawaiJakarta->orderBy('jb.namajabatanulab');
        $pegawaiJakarta = $pegawaiJakarta->get();

        $pegawaiGresik = DB::table('pegawai_m as pg')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->select(
                'pg.id as pegawai_id',
                'pg.namalengkap',
                'pg.jabatan1fk',
                'jb.id as jabatan_id',
                'jb.namajabatanulab'
            )
            ->where('pg.statusenabled', true)
            ->whereIn('jb.id', $jabatanIds)
            ->where('pg.lokasikalibrasifk', 2)
            ->where('jb.statusenabled', true);

        if (isset($r['limit']) && $r['limit'] != '') {
            $pegawaiGresik->limit($r['limit']);
        }

        $pegawaiGresik->orderBy('jb.namajabatanulab');
        $pegawaiGresik = $pegawaiGresik->get();

        $res['pegawaiJakarta'] = $pegawaiJakarta;
        $res['pegawaiGresik'] = $pegawaiGresik;
        return $this->respond($res);
    }

    public function LayananVerif(Request $r)
    {
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
            ->leftjoin('lokasikalibrasi_m as lk1', 'lk1.id', '=', 'mtrd.lokasirepairfk')
            ->leftjoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->leftjoin('paketkalibrasi_m as pk', 'pk.id', '=', 'mtr.paketkalibrasi')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.alasanpenolakanregis',
                'mtrd.tanggalpenolakanregis',
                'prd.namaproduk',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mtr.jenisorder',
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
                'lk1.id as lokasirepairfk',
                'lk1.lokasi as lokasirepair',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
                'pk.id as idpaket',
                'pk.namapaket',
                'pk.hari as hari_paket'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtr.norec', $r['norec_pd']);

        if (isset($r['norecdetail']) && $r['norecdetail'] != "" && $r['norecdetail'] != "undefined") {
            $data = $data->where('mtrd.norec', '=', $r['norecdetail']);
        }

        $data = $data->orderByDesc('prd.namaproduk')->get();

        $alatPerRegistrasi = [];
        foreach ($data as $item) {
            $alatPerRegistrasi[$item->norec][] = $item;
        }

        foreach ($data as $d) {
            $alatList = isset($alatPerRegistrasi[$d->norec]) ? $alatPerRegistrasi[$d->norec] : [];
            $countPerLingkup = [];
            foreach ($alatList as $alat) {
                $lingkup = $alat->lingkupfk;
                if (!$lingkup) continue;
                if (!isset($countPerLingkup[$lingkup])) {
                    $countPerLingkup[$lingkup] = 0;
                }
                $countPerLingkup[$lingkup]++;
            }
            $maxAlat = 0;
            foreach ($countPerLingkup as $total) {
                if ($total > $maxAlat) $maxAlat = $total;
            }
            $hariPaket = $d->hari_paket ?? 0;
            $totalDurasi = $maxAlat * $hariPaket;
            $d->totalDurasi = $totalDurasi;
            $tglDasar = $d->tglverifasman ?? \Carbon\Carbon::now()->format('Y-m-d');
            if ($totalDurasi > 0) {
                $d->tanggalSelesai = \Carbon\Carbon::parse($tglDasar)
                    ->addDays($totalDurasi)
                    ->format('d-m-Y');
            } else {
                $d->tanggalSelesai = null;
            }
        }

        $result['length'] = count($data);
        $result['detail'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
    }


    public function detailProduk(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtr.asmanveriffk')
            ->leftJoin('pegawai_m as pg4', 'pg4.id', '=', 'mtrd.pelaksanaisilembarkerjafk')
            ->leftJoin('pegawai_m as pg5', 'pg5.id', '=', 'mtrd.penyeliasetujulembarkerjafk')
            ->leftJoin('pegawai_m as pg6', 'pg6.id', '=', 'mtrd.asmansetujulembarkerjafk')
            ->leftJoin('pegawai_m as pg7', 'pg7.id', '=', 'mtrd.managersetujulembarkerjafk')
            ->leftJoin('pegawai_m as pg8', 'pg8.id', '=', 'mtrd.pelaksanaisilaporanrepairfk')
            ->leftJoin('pegawai_m as pg9', 'pg9.id', '=', 'mtrd.penyeliaisilaporanrepairfk')
            ->leftJoin('pegawai_m as pg10', 'pg10.id', '=', 'mtrd.penyeliasetujulaporanrepairfk')
            ->leftJoin('pegawai_m as pg11', 'pg11.id', '=', 'mtrd.asmansetujulaporanrepairfk')
            ->leftJoin('pegawai_m as pg12', 'pg12.id', '=', 'mtrd.managersetujulaporanrepairfk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftJoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderasman',
                'mtrd.statusorderpenyelia',
                'mtrd.statusorderpelaksana',
                'mtr.tglverifasman',
                'mtrd.tglverifpenyelia',
                'mtrd.tglverifpelaksana',
                'mtrd.tglisilembarkerjapelaksana',
                'mtrd.pelaksanaisilembarkerjafk',
                'mtrd.tglisilembarkerjapenyelia',
                'mtrd.penyeliaisilembarkerjafk',
                'mtrd.tglisilaporanrepairpelaksana',
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
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'pg4.id as pelaksanaisilembarkerjafk',
                'pg4.namalengkap as pelaksanaisilembarkerja',
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
                'pg5.id as penyeliasetujuilembarkerjafk',
                'pg5.namalengkap as penyeliasetujuilembarkerja',
                'pg6.id as asmansetujuilembarkerjafk',
                'pg6.namalengkap as asmansetujuilembarkerja',
                'mtrd.setujuilembarkerjamanager',
                'mtrd.tglsetujumanagerlembarkerja',
                'mtrd.managersetujulembarkerjafk',
                'mtrd.tglisilaporanrepairpenyelia',
                'mtrd.tglsetujupenyelialaporanrepair',
                'mtrd.tglsetujuasmanlaporanrepair',
                'mtrd.tglsetujumanagerlaporanrepair',
                'pg7.id as managersetujuilembarkerjafk',
                'pg7.namalengkap as managersetujuilembarkerja',
                'pg8.id as pelaksanaisilaporanrepairfk',
                'pg8.namalengkap as pelaksanaisilaporanrepair',
                'pg9.id as penyeliaisilaporanrepairfk',
                'pg9.namalengkap as penyeliaisilaporanrepair',
                'pg10.id as penyeliasetujulaporanrepairfk',
                'pg10.namalengkap as penyeliasetujulaporanrepair',
                'pg11.id as asmansetujulaporanrepairfk',
                'pg11.namalengkap as asmansetujulaporanrepair',
                'pg12.id as managersetujulaporanrepairfk',
                'pg12.namalengkap as managersetujulaporanrepair',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_pd'])
            ->orderByDesc('prd.namaproduk')
            ->get();

        $timeline = [];

        foreach ($data as $item) {
            if (!is_null($item->tglsetujumanagerlaporanrepair)) {
                $timeline[] = [
                    'date' => $item->tglsetujumanagerlaporanrepair,
                    'type' => 'Laporan Repair Disetujui Oleh Manager',
                    'nama' => $item->managersetujulaporanrepair ?? '-',
                ];
            }
            if (!is_null($item->tglsetujuasmanlaporanrepair)) {
                $timeline[] = [
                    'date' => $item->tglsetujuasmanlaporanrepair,
                    'type' => 'Laporan Repair Disetujui Oleh Asman',
                    'nama' => $item->asmansetujulaporanrepair ?? '-',
                ];
            }
            if (!is_null($item->tglsetujupenyelialaporanrepair)) {
                $timeline[] = [
                    'date' => $item->tglsetujupenyelialaporanrepair,
                    'type' => 'Laporan Repair Disetujui Oleh Penyelia',
                    'nama' => $item->penyeliasetujulaporanrepair ?? '-',
                ];
            }
            if (!is_null($item->tglisilaporanrepairpenyelia)) {
                $timeline[] = [
                    'date' => $item->tglisilaporanrepairpenyelia,
                    'type' => 'Diisi Laporan Repair Oleh Penyelia',
                    'nama' => $item->penyeliaisilaporanrepair ?? '-',
                ];
            }
            if (!is_null($item->tglisilaporanrepairpelaksana)) {
                $timeline[] = [
                    'date' => $item->tglisilaporanrepairpelaksana,
                    'type' => 'Diisi Laporan Repair Oleh Pelaksana',
                    'nama' => $item->pelaksanaisilaporanrepair ?? '-',
                ];
            }
            if (!is_null($item->tglsetujumanagerlembarkerja)) {
                $timeline[] = [
                    'date' => $item->tglsetujumanagerlembarkerja,
                    'type' => 'Sertifikat Di Setujui Oleh Manager',
                    'nama' => $item->managersetujuilembarkerja ?? '-',
                ];
            }
            if (!is_null($item->tglsetujuasmanlembarkerja)) {
                $timeline[] = [
                    'date' => $item->tglsetujuasmanlembarkerja,
                    'type' => 'Sertifikat Di Setujui Oleh Asman',
                    'nama' => $item->asmansetujuilembarkerja ?? '-',
                ];
            }
            if (!is_null($item->tglsetujupenyelialembarkerja)) {
                $timeline[] = [
                    'date' => $item->tglsetujupenyelialembarkerja,
                    'type' => 'Sertifikat Di Setujui Oleh Penyelia',
                    'nama' => $item->penyeliasetujuilembarkerja ?? '-',
                ];
            }
            if (!is_null($item->tglverifasman)) {
                $timeline[] = [
                    'date' => $item->tglverifasman,
                    'type' => 'Diverifikasi Oleh Asman',
                    'nama' => $item->asamanverifikasi ?? '-',
                ];
            }
            if (!is_null($item->tglverifpenyelia)) {
                $timeline[] = [
                    'date' => $item->tglverifpenyelia,
                    'type' => 'Diverifikasi Oleh Penyelia Teknik',
                    'nama' => $item->penyeliateknik ?? '-',
                ];
            }
            if (!is_null($item->tglverifpelaksana)) {
                $timeline[] = [
                    'date' => $item->tglverifpelaksana,
                    'type' => 'Diverifikasi Oleh Pelaksana Teknik',
                    'nama' => $item->pelaksanateknik ?? '-',
                ];
            }
            if (!is_null($item->tglisilembarkerjapelaksana)) {
                $timeline[] = [
                    'date' => $item->tglisilembarkerjapelaksana,
                    'type' => 'Diisi Lembar Kerja Oleh Pelaksana Teknik',
                    'nama' => $item->pelaksanateknik ?? '-',
                ];
            }
            if (!is_null($item->tglisilembarkerjapenyelia)) {
                $timeline[] = [
                    'date' => $item->tglisilembarkerjapenyelia,
                    'type' => 'Diisi Lembar Kerja Oleh Penyelia Teknik',
                    'nama' => $item->penyeliateknik ?? '-',
                ];
            }
        }

        usort($timeline, function ($a, $b) {
            return strtotime($a['date']) <=> strtotime($b['date']);
        });

        $result = [
            'length' => count($data),
            'timeline' => $timeline,
            'as' => '@adit'
        ];

        return $this->respond($result);
    }

    public function saveVerifItem(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['veriItem'];

            if ($VI['lokasirepair'] != null) {
                DB::table('mitraregistrasidetail_t')
                    ->where('norec', $VI['norec_detail'])
                    ->update([
                        'iskaji' => true,
                        'statusenabled' => true,
                        'lokasirepairfk' => $VI['lokasirepair'],
                        'penyeliateknikfk' => $VI['penyeliateknik'],
                        'pelaksanateknikfk' => $VI['pelaksana'],
                        'tanggalpenolakanregis' => null,
                        'alasanpenolakanregis' => null,
                    ]);
            } else {
                DB::table('mitraregistrasidetail_t')
                    ->where('norec', $VI['norec_detail'])
                    ->update([
                        'iskaji' => true,
                        'statusenabled' => true,
                        'lokasikajifk' => $VI['lokasikalibrasi'],
                        'lingkupkalibrasifk' => $VI['lingkupkalibrasi'],
                        'penyeliateknikfk' => $VI['penyeliateknik'],
                        'pelaksanateknikfk' => $VI['pelaksana'],
                        'durasikalbrasi' => $VI['durasikalbrasi'],
                        'tanggalpenolakanregis' => null,
                        'alasanpenolakanregis' => null,
                    ]);
            }

            $transMessage = "Simpan Verif Item Sukses";
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

    public function updatePaket(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['updatePaket'];

            DB::table('mitraregistrasi_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'paketkalibrasi' =>  $VI['paketkalibrasi'],
                ]);

            $durasikalibrasi = null;
            $paket = PaketKalibrasi::find($VI['paketkalibrasi']);
            if ($paket) {
                $durasikalibrasi = $paket->hari;
            }

            if ($durasikalibrasi !== null) {
                DB::table('mitraregistrasidetail_t')
                    ->where('noregistrasifk', $VI['norec'])
                    ->update([
                        'durasikalbrasi' => $durasikalibrasi
                    ]);
            }

            $transMessage = "Simpan Update Paket Sukses";
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

    public function saveVerif(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['verif'];


            if ($VI['lokasirepair'] != null) {
                $lokasi = DB::table('lokasikalibrasi_m')
                    ->where('id', $VI['lokasirepair'])
                    ->first();
            } else {
                $lokasi = DB::table('lokasikalibrasi_m')
                    ->where('id', $VI['lokasikalibrasi'])
                    ->first();
            }

            $lokasiInisial = '';
            if ($lokasi) {
                $lokasiInisial = $lokasi->inisial ?? strtoupper(substr($lokasi->lokasi, 0, 1));
            }

            $tahun = date('y');
            $bulan = date('m');
            $details = DB::table('mitraregistrasidetail_t')
                ->where('noregistrasifk', $VI['norec'])
                ->get();

            $urut = DB::table('mitraregistrasidetail_t')
                ->whereYear('tglverifasman', date('Y'))
                ->whereMonth('tglverifasman', date('m'))
                ->count();

            foreach ($details as $i => $detail) {
                $lingkup = DB::table('lingkupkalibrasi_m')
                    ->where('id', $detail->lingkupkalibrasifk)
                    ->first();

                $lingkupInisial = '';
                if ($lingkup) {
                    $lingkupInisial = $lingkup->inisial ?? strtoupper(substr($lingkup->lingkupkalibrasi, 0, 1));
                }

                $noUrut = $urut + $i;
                $urutStr = str_pad($noUrut, 3, '0', STR_PAD_LEFT);
                $noorderalat = "{$lokasiInisial}{$lingkupInisial}-{$tahun}.{$bulan}.{$urutStr}";

                DB::table('mitraregistrasidetail_t')
                    ->where('norec', $detail->norec)
                    ->update([
                        'noorderalat' => $noorderalat,
                        'tglverifasman' => now(),
                        'statusordermanager' => 0,
                    ]);
            }

            DB::table('mitraregistrasi_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'statusorder' => 1,
                    'asmanveriffk' => $this->getPegawaiId(),
                    'tglverifasman' => now(),
                ]);

            $transMessage = "Simpan Verif Sukses";
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



    public function HeaderMitra(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->leftJoin('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
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
                'mtr.norec as norec_pd',
            )
            ->where('mt.statusenabled', true)
            ->where('mtr.statusenabled', true)
            // ->where('mt.id', $r['nocmfk'])
            ->where('mtr.norec', $r['norec_pd'])
            ->first();

        $result['mitra'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

    public function cetakSPK(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $res['identitas'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtr.petugaskaji')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->select(
                'jb.id as idjabatan',
                'jb.namajabatanulab as namajabanpetugaskaji',
                'mtr.norec',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mt.namaperusahaan',
                'mt.alamatktr',
                'pg.id as petugaskajifk',
                'pg.namalengkap as namapetugaskaji',
                'lk.lokasi',
                'mtr.jabatanpenanggungjawab',
                'mtr.namapenanggungjawab'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtr.norec', $r['norec'])
            ->first();

        $res['alat'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtrd.asmanveriffk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftJoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg2.jabatan1fk')
            ->leftJoin('jabatan_m as jb1', 'jb1.id', '=', 'pg.jabatan1fk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.keterangan',
                'mtrd.namafile',
                'mtrd.pelaksanateknikfk',
                'mtrd.noorderalat',
                'mtrd.durasikalbrasi',
                'mtrd.namamanager',
                'mtrd.namaasman',
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
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'jb1.namajabatanulab as jabatanpenyelia',
                'jb.namajabatanulab as jabatanpelaksana',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtr.norec', $r['norec'])
            ->where('mtrd.penyeliateknikfk', $r['penyeliateknikfk'])
            ->orderByDesc('prd.namaproduk')
            ->get();

        $res['totalDurasi'] = $res['alat']->sum('durasikalbrasi');
        $res['pdf']  = $r['pdf'];
        $res['ttdManager'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->namamanager));
        $res['ttdAsman'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->namaasman));
        $res['ttdPenyelia'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat'][0]->pelaksanateknik));
        $res['penyelia'] = true;


        $blade = 'report.asman.surat-perintah-kerja';

        if ($res['pdf'] == 'true') {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf->stream();
        }
        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }

        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }

    public function cetakSertifikatLembarKerja(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $res['identitas'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtr.petugaskaji')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->select(
                'jb.id as idjabatan',
                'jb.namajabatanulab as namajabanpetugaskaji',
                'mtr.norec',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mt.namaperusahaan',
                'mt.alamatktr',
                'pg.id as petugaskajifk',
                'pg.namalengkap as namapetugaskaji',
                'lk.lokasi',
                'mtr.jabatanpenanggungjawab',
                'mtr.namapenanggungjawab'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtr.norec', $r['norec'])
            ->first();

        $res['lembarKerja'] = DB::table('lembarkerja_t as lk')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
            ->select(
                'lk.*',
                'mtrd.tglkalibrasilembarkerja',
                'mtrd.tempatKalibrasilembarkerja',
                'mtrd.kondisiRuanganlembarkerja',
                'mtrd.suhulembarkerja',
                'mtrd.kelembabanRelatiflembarkerja'
            )
            ->where('mtrd.norec', $r['norec_detail'])
            ->where('lk.statusenabled', true)
            ->get();

        $res['instruksikerja'] = DB::table('daftarinstruksikerja_t as dik')
            ->leftJoin('instruksikerja_m as ik', 'ik.id', '=', 'dik.idalatinstruksikerja')
            ->select('dik.detailregistrasifk', 'ik.id', 'ik.namainstruksikerja', 'ik.noisntruksikerja')
            ->where('dik.detailregistrasifk', $r['norec_detail'])
            ->where('dik.statusenabled', true)
            ->where('ik.statusenabled', true)
            ->get();

        $res['alastandar'] = DB::table('daftaralatstandar_t as das')
            ->leftJoin('peralatanstandar_m as pas', 'pas.id', '=', 'das.alatstandarfk')
            ->leftJoin('merkalat_m as mk', 'mk.id', '=', 'das.merkstandarfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'das.tipestandarfk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'das.snstandarfk')
            ->select(
                'das.detailregistrasifk',
                'pas.id as value',
                'pas.namaalatstandar',
                'pas.duedate',
                'mk.namamerk',
                'tp.namatipe',
                'sn.namaserialnumber'
            )
            ->where('das.detailregistrasifk', $r['norec_detail'])
            ->where('das.statusenabled', true)
            ->where('pas.statusenabled', true)
            ->get();

        $res['alat'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtr.asmanveriffk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->select(
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'mtrd.noorderalat',
                'mtrd.namamanager',
                'mtrd.setujuilembarkerjamanager',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.setujuilembarkerjapenyelia',
                'prd.namaproduk',
                'mrk.namamerk',
                'tp.namatipe',
                'sn.namaserialnumber',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_detail'])
            ->first();

        $res['pdf']  = $r['pdf'];
        $res['ttdPelaksana'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->pelaksanateknik));
        $res['ttdAsman'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->asamanverifikasi));
        $res['ttdPenyelia'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->penyeliateknik));
        $res['ttdManager'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->namamanager));
        $res['halamanPertama'] = true;

        $blade = 'report.pelaksana.sertifikat-lembar-kerja';

        if ($res['pdf'] == 'true') {
            $pdfDummy = App::make('dompdf.wrapper');
            $pdfDummy->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'jumlahHalaman' => null,
                )
            );
            $dompdfDummy = $pdfDummy->getDomPDF();
            $dompdfDummy->render();
            $jumlahHalaman = $dompdfDummy->getCanvas()->get_page_count();

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'jumlahHalaman' => $jumlahHalaman,
                )
            );


            $dompdf = $pdf->getDomPDF();
            $canvas = $dompdf->get_canvas();
            $canvas->page_text(230, 780, "Halaman ke {PAGE_NUM} dari {PAGE_COUNT} halaman", null, 8, array(0, 0, 0));
            $font = $dompdf->getFontMetrics()->getFont('Helvetica', 'italic');
            $canvas->page_text(260, 788, "Page {PAGE_NUM} of {PAGE_COUNT} pages", $font, 7, array(0, 0, 0));

            return $pdf->stream();
        }

        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }


        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }

    public function downloadFileTerunggah(Request $request)
    {
        $norec = $request->get('norec');
        $data = DB::table('mitraregistrasidetail_t')
            ->select('namafileexcel')
            ->where('norec', $norec)
            ->first();
        if (!$data || !$data->namafileexcel) {
            return '
            <script language="javascript">
                alert("File tidak ditemukan di database.");
                window.close();
            </script>';
        }
        $filename = $data->namafileexcel;
        $pathbundle = 'berkas-mitra-excel/' . $filename;
        $path = public_path($pathbundle);

        if (File::exists($path)) {
            $file = File::get($path);
            $type = File::mimeType($path);

            $response = response()->make($file, 200);
            $response->header("Content-Type", $type)
                ->header('Content-disposition', 'attachment; filename="' . $filename . '"');

            return $response;
        } else {
            return '
            <script language="javascript">
                alert("File tidak ditemukan di direktori.");
                window.close();
            </script>';
        }
    }


    public function getExcelLength(Request $request)
    {
        $norec = $request['norec'];
        $data = DB::table('mitraregistrasidetail_t')
            ->select('namafileexcel')
            ->where('norec', $norec)
            ->first();

        $result['data'] = $data;
        $result['as'] = '@adit';

        return $this->respond($result);
    }

    public function getLaporanRepairAsman(Request $request)
    {
        $data = DB::table('laporanrepair_t as lp')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lp.detailregistrasifk')
            ->join('mitraregistrasi_t as mtr', 'mtr.norec', '=', 'mtrd.noregistrasifk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'lp.petugas')
            ->select(
                'lp.*',
                'mtr.norec as norecregis',
                'mtrd.kesimpulanrepair',
                'pg.namalengkap as petugasrepair'
            )
            ->where('mtrd.norec', $request->norecdetail)
            ->where('lp.statusenabled', true)
            ->get();

        $dataStatus = DB::table('statusalatrepair_t as sp')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'sp.detailregistrasifk')
            ->join('mitraregistrasi_t as mtr', 'mtr.norec', '=', 'mtrd.noregistrasifk')
            ->join('pegawai_m as pg', 'pg.id', '=', 'sp.petugas')
            ->select(
                'sp.*',
                'mtr.norec as norecregis',
                'pg.namalengkap as petugasisistatus'
            )
            ->where('mtrd.norec', $request->norecdetail)
            ->where('sp.statusenabled', true)
            ->get();

        $result['data'] = $data;
        $result['datastatus'] = $dataStatus;
        $result['as'] = '@adit';

        return $this->respond($result);
    }

    public function detailAlatRepairAsman(Request $r)
    {
        $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtrd.asmanveriffk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtrd.lokasikajifk')
            ->leftJoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderasman',
                'mtrd.statusorderpenyelia',
                'mtrd.statusorderpelaksana',
                'mtrd.tglverifasman',
                'mtrd.tglverifpenyelia',
                'mtrd.tglverifpelaksana',
                'mtrd.tglkalibrasilembarkerja',
                'mtrd.tempatKalibrasilembarkerja',
                'mtrd.kondisiRuanganlembarkerja',
                'mtrd.suhulembarkerja',
                'mtrd.kelembabanRelatiflembarkerja',
                'mtrd.noorderalat',
                'mtrd.kesimpulanrepair',
                'prd.namaproduk',
                'mtr.catatan',
                'mrk.id as idmerk',
                'mrk.namamerk',
                'tp.id as idtipe',
                'tp.namatipe',
                'sn.id as idsn',
                'sn.namaserialnumber',
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_pd'])
            ->orderByDesc('prd.namaproduk')
            ->first();

        $result = [
            'data' => $data,
            'as' => '@adit'
        ];

        return $this->respond($result);
    }

    public function hapusLaporanRepairAsman(Request $r)
    {
        DB::beginTransaction();
        try {
            $laporan = DB::table('laporanrepair_t')
                ->where('norec', $r['norec'])
                ->first();

            if ($laporan && $laporan->fotoalatrepair) {
                $filePath = public_path('berkas-laporan-repair/' . $laporan->fotoalatrepair);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            DB::table('laporanrepair_t')
                ->where('norec', $r['norec'])
                ->delete();

            $transMessage = "Hapus Laporan Repair Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@adit',
                ],
            ];
        } catch (\Exception $e) {
            $transMessage = "Hapus Laporan Repair Gagal";
            DB::rollBack();
            $result = [
                "status" => 400,
                "result"  => $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function setujuiLaporanRepairAsman(Request $r)
    {
        DB::beginTransaction();
        try {
            $VI = $r['verif'];
            DB::table('mitraregistrasidetail_t')
                ->where('norec', $VI['norec'])
                ->update([
                    'asmansetujulaporanrepairfk' => $this->getPegawaiId(),
                    'tglsetujuasmanlaporanrepair' => now(),
                    'statusorderasman' => 2,
                ]);


            $transMessage = "Simpan Setujui Laporan Repair Sukses";
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

    public function cetakLaporanRepairAsman(Request $r)
    {

        $profile = $this->profile();
        $print = false;
        $pageWidth = 950;

        $res['identitas'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitra_m as mt', 'mt.id', '=', 'mtr.nomitrafk')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtr.petugaskaji')
            ->leftJoin('jabatan_m as jb', 'jb.id', '=', 'pg.jabatan1fk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasikalibrasi')
            ->select(
                'jb.id as idjabatan',
                'jb.namajabatanulab as namajabanpetugaskaji',
                'mtr.norec',
                'mtr.tglregistrasi',
                'mtr.nopendaftaran',
                'mtr.catatan',
                'mt.namaperusahaan',
                'mt.alamatktr',
                'pg.id as petugaskajifk',
                'pg.namalengkap as namapetugaskaji',
                'lk.lokasi',
                'mtr.jabatanpenanggungjawab',
                'mtr.namapenanggungjawab'
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtr.norec', $r['norec'])
            ->first();

        $res['lembarKerja'] = DB::table('lembarkerja_t as lk')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lk.detailregistraifk')
            ->select(
                'lk.*',
                'mtrd.tglkalibrasilembarkerja',
                'mtrd.tempatKalibrasilembarkerja',
                'mtrd.kondisiRuanganlembarkerja',
                'mtrd.suhulembarkerja',
                'mtrd.kelembabanRelatiflembarkerja'
            )
            ->where('mtrd.norec', $r['norec_detail'])
            ->where('lk.statusenabled', true)
            ->get();

        $res['laporanRepair'] = DB::table('laporanrepair_t as lp')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'lp.detailregistrasifk')
            ->select(
                'lp.*',
            )
            ->where('mtrd.norec', $r['norec_detail'])
            ->where('lp.statusenabled', true)
            ->get();

        $res['statusRepair'] = DB::table('statusalatrepair_t as sp')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.norec', '=', 'sp.detailregistrasifk')
            ->select(
                'sp.*',
            )
            ->where('mtrd.norec', $r['norec_detail'])
            ->where('sp.statusenabled', true)
            ->get();

        $res['alat'] =  $data = DB::table('mitraregistrasi_t as mtr')
            ->join('mitraregistrasidetail_t as mtrd', 'mtrd.noregistrasifk', '=', 'mtr.norec')
            ->leftJoin('pegawai_m as pg', 'pg.id', '=', 'mtrd.penyeliateknikfk')
            ->leftJoin('pegawai_m as pg2', 'pg2.id', '=', 'mtrd.pelaksanateknikfk')
            ->leftJoin('pegawai_m as pg3', 'pg3.id', '=', 'mtr.asmanveriffk')
            ->join('produk_m as prd', 'prd.id', '=', 'mtrd.namaalatfk')
            ->leftJoin('merkalat_m as mrk', 'mrk.id', '=', 'mtrd.namamerkfk')
            ->leftJoin('tipealat_m as tp', 'tp.id', '=', 'mtrd.namatipefk')
            ->leftJoin('serialnumber_m as sn', 'sn.id', '=', 'mtrd.serialnumberfk')
            ->leftJoin('lokasikalibrasi_m as lk', 'lk.id', '=', 'mtr.lokasirepair')
            ->select(
                'pg.id as penyeliateknikfk',
                'pg.namalengkap as penyeliateknik',
                'pg2.id as pelaksanateknikfk',
                'pg2.namalengkap as pelaksanateknik',
                'pg3.id as asmanfk',
                'pg3.namalengkap as asamanverifikasi',
                'mtrd.noorderalat',
                'mtrd.namamanager',
                'mtrd.setujuilembarkerjamanager',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.setujuilembarkerjapenyelia',
                'mtrd.kesimpulanrepair',
                'mtrd.keterangan',
                'mtrd.namafile',
                'prd.namaproduk',
                'mrk.namamerk',
                'tp.namatipe',
                'sn.namaserialnumber',
                'lk.lokasi as lokasirepair',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtr.iskaji', true)
            ->where('mtrd.statusenabled', true)
            ->where('mtrd.norec', $r['norec_detail'])
            ->first();

        $tr = new GoogleTranslate('en');
        $translated = $tr->translate($data->kesimpulanrepair);
        $res['alat']->kesimpulanrepair_en = $translated;
        $res['pdf']  = $r['pdf'];
        $res['ttdPelaksana'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->pelaksanateknik));
        $res['ttdAsman'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->asamanverifikasi));
        $res['ttdPenyelia'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->penyeliateknik));
        $res['ttdManager'] = base64_encode(QrCode::format('svg')->size(75)->generate($res['alat']->namamanager));
        $res['halamanPertama'] = true;

        $blade = 'report.pelaksana.laporan-repair';

        if ($res['pdf'] == 'true') {
            $pdfDummy = App::make('dompdf.wrapper');
            $pdfDummy->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'jumlahHalaman' => null,
                )
            );
            $dompdfDummy = $pdfDummy->getDomPDF();
            $dompdfDummy->render();
            $jumlahHalaman = $dompdfDummy->getCanvas()->get_page_count();

            $pdf = App::make('dompdf.wrapper');
            $pdf->loadView(
                $blade . '-dom',
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                    'jumlahHalaman' => $jumlahHalaman,
                )
            );


            $dompdf = $pdf->getDomPDF();
            $canvas = $dompdf->get_canvas();
            $canvas->page_text(230, 780, "Halaman ke {PAGE_NUM} dari {PAGE_COUNT} halaman", null, 8, array(0, 0, 0));
            $font = $dompdf->getFontMetrics()->getFont('Helvetica', 'italic');
            $canvas->page_text(260, 788, "Page {PAGE_NUM} of {PAGE_COUNT} pages", $font, 7, array(0, 0, 0));

            return $pdf->stream();
        }

        if (isset($r['storage'])) {
            $res['storage']  = true;
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'landscape');
            $pdf->loadView(
                $blade,
                array(
                    'profile' => $profile,
                    'pageWidth' => $pageWidth,
                    'print' => $print,
                    'res' => $res,
                )
            );
            return $pdf;
        }


        return view(
            $blade,
            compact('profile', 'pageWidth', 'print', 'res')
        );
    }

    public function hapusLaporanStatus(Request $r)
    {
        DB::beginTransaction();
        try {
            $status = DB::table('statusalatrepair_t')
                ->where('norec', $r['norec'])
                ->first();

            if ($status && $status->fotoalatstatus) {
                $filePath = public_path('berkas-laporan-repair/' . $status->fotoalatstatus);
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            DB::table('statusalatrepair_t')
                ->where('norec', $r['norec'])
                ->delete();

            $transMessage = "Hapus Status Alat Repair Sukses";
            DB::commit();

            $result = [
                "status" => 200,
                "result" => [
                    "as" => '@adit',
                ],
            ];
        } catch (\Exception $e) {
            $transMessage = "Hapus Status ALat Repair Gagal";
            DB::rollBack();
            $result = [
                "status" => 400,
                "result"  => $e->getMessage()
            ];
        }

        return $this->respond($result['result'], $result['status'], $transMessage);
    }
}
