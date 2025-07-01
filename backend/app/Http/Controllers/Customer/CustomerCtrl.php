<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
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
                'mtr.jenisorder',
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
                'mtrd.setujuilembarkerjapenyelia',
                'mtrd.tglsetujupenyelialembarkerja',
                'mtrd.penyeliasetujulembarkerjafk',
                'mtrd.noorderalat',
                'mtrd.setujuilembarkerjaasman',
                'mtrd.tglsetujuasmanlembarkerja',
                'mtrd.asmansetujulembarkerjafk',
                'mtrd.statusorderasman',
                'mtrd.setujuilembarkerjamanager',
                'mtrd.tglsetujumanagerlembarkerja',
                'mtrd.managersetujulembarkerjafk',
                'mtrd.tglverifpelaksana',
                'mtrd.penyeliasetujulaporanrepairfk',
                'mtrd.asmansetujulaporanrepairfk',
                'mtrd.managersetujulaporanrepairfk',
            )
            ->where('mtr.statusenabled', true)
            ->where('mtrd.statusenabled', true);

        if (isset($r['search']) && $r['search'] != '') {
            $searchTerm = '%' . $r['search'] . '%';
            $data = $data->where(function ($query) use ($searchTerm) {
                $query->where('mt.namaperusahaan', 'ilike', $searchTerm)
                    ->orWhere('mt.noorderalat', 'ilike', $searchTerm)
                    ->orWhere('mt.nopendaftaran', 'ilike', $searchTerm);
            });
        }

        $data = $data->orderBy('lp.lingkupkalibrasi');
        if (isset($r['limit'])) {
            $data = $data->limit($r['limit']);
        }
        $data = $data->get();

        $res['data'] = $data;
        return $this->respond($res);
    }

    public function produkByMitra(Request $r)
    {
        $search = $r['query'];
        $data = DB::table('produk_m as pm')
            ->leftJoin('mapmitratoproduk_m as mmp', 'mmp.objectprodukfk', '=', 'pm.id')
            ->select(
                'pm.id',
                'pm.namaproduk',
            )
            ->where('mmp.statusenabled', true)
            ->where('mmp.objectmitrafk', $r['idmitra'])
            ->where('pm.statusenabled', true);

        if (isset($r['param_search']) && $r['param_search'] != '') {
            $exp = explode(',', $r['param_search']);
            foreach ($exp as $items) {
                $where[] = [$items, 'ILIKE', '%' . $search . '%'];
            }
            $data = $data->where($where);
        }
        $data = $data->get();


        $result['data'] = $data;
        $result['as'] = '@epic';

        return $this->respond($result);
    }

}
