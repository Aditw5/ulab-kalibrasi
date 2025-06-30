<?php

namespace App\Http\Controllers\Laporan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanPelaksanaCtrl extends Controller
{
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function getLaporanAlatPelaksana(Request $request)
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
            ->leftjoin('lokasikalibrasi_m as lk1', 'lk1.id', '=', 'mtr.lokasirepair')
            ->leftjoin('lingkupkalibrasi_m as lp', 'lp.id', '=', 'mtrd.lingkupkalibrasifk')
            ->select(
                'mtr.norec',
                'mtrd.norec as norec_detail',
                'mtrd.iskaji',
                'mtrd.durasikalbrasi',
                'mtrd.namafile',
                'mtrd.keterangan',
                'mtrd.statusorderpenyelia',
                'mtrd.pelaksanaisilembarkerjafk',
                'mtrd.tglverifasman',
                'prd.namaproduk',
                'mtr.tglregistrasi',
                'mtr.catatan',
                'mtr.jenisorder',
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
                'lk.id as lokasikalibrasifk',
                'lk.lokasi',
                'lk.lokasi as lokasirepair',
                'lp.id as lingkupfk',
                'lp.lingkupkalibrasi',
                'mtrd.statusorderasman',
                'mtrd.tglverifpelaksana',
                'mtrd.noorderalat',
                'mtrd.tglisilembarkerjapelaksana',
                'mtrd.tglisilembarkerjapenyelia',
                'mtrd.tglsetujupenyelialembarkerja',
                'mtrd.tglsetujuasmanlembarkerja',
                'mtrd.tglsetujumanagerlembarkerja',
                'mtrd.tglisilaporanrepairpelaksana',
                'mtrd.tglisilaporanrepairpenyelia',
                'mtrd.tglsetujupenyelialaporanrepair',
                'mtrd.tglsetujuasmanlaporanrepair',
                'mtrd.tglsetujumanagerlaporanrepair',
            )
            ->where('mtrd.pelaksanateknikfk', $request['id'])
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
                    ->orWhere('tp.namatipe', 'ilike', $searchTerm)
                    ->orWhere('sn.namaserialnumber', 'ilike', $searchTerm)
                    ->orWhere('prd.namaproduk', 'ilike', $searchTerm);
            });
        }

        $data = $data->orderBy('mtr.tglregistrasi');
        $data = $data->get();

        foreach ($data as $item) {
            $item->statusAlat = null;
            $item->lokasiAlat = null;
            if ($item->jenisorder == 'kalibrasi') {
                if ($item->tglsetujumanagerlembarkerja != null) {
                    $item->statusAlat = 'Lembar Kerja Disetujui Manager';
                    $item->color = 'success';
                } elseif ($item->tglsetujuasmanlembarkerja != null) {
                    $item->statusAlat = 'Lembar Kerja Disetujui Asman';
                    $item->color = 'success';
                } elseif ($item->tglsetujupenyelialembarkerja != null) {
                    $item->statusAlat = 'Lembar Kerja Disetujui Penyelia';
                    $item->color = 'success';
                } elseif ($item->tglisilembarkerjapenyelia != null) {
                    $item->statusAlat = 'Lembar Kerja Diisi Penyelia';
                    $item->color = 'primary';
                } elseif ($item->tglisilembarkerjapelaksana != null) {
                    $item->statusAlat = 'Lembar Kerja Diisi Pelaksana';
                    $item->color = 'primary';
                } else {
                    $item->statusAlat = 'Lembar Kerja Belum Diisi';
                    $item->color = 'warning';
                }
                $item->lokasiAlat = $item->lokasi;
            } else {
                if ($item->tglsetujumanagerlaporanrepair != null) {
                    $item->statusAlat = 'Laporan Repair Disetujui Manager';
                    $item->color = 'success';
                } elseif ($item->tglsetujuasmanlaporanrepair != null) {
                    $item->statusAlat = 'Laporan Repair Disetujui Asman';
                    $item->color = 'success';
                } elseif ($item->tglsetujupenyelialaporanrepair != null) {
                    $item->statusAlat = 'Laporan Repair Disetujui Penyelia';
                    $item->color = 'success';
                } elseif ($item->tglisilaporanrepairpenyelia != null) {
                    $item->statusAlat = 'Laporan Repair Diisi Penyelia';
                    $item->color = 'primary';
                } elseif ($item->tglisilaporanrepairpelaksana != null) {
                    $item->statusAlat = 'Laporan Repair Diisi Pelaksana';
                    $item->color = 'primary';
                } else {
                    $item->statusAlat = 'Laporan Repair Belum Diisi';
                    $item->color = 'warning';
                }
                $item->lokasiAlat = $item->lokasirepair;
            }
        }

        return $this->respond([
            'data' => $data,
            'message' => 'ea@epic',
        ]);
    }
}
