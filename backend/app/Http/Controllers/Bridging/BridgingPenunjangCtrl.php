<?php

namespace App\Http\Controllers\Bridging;

use App\Http\Controllers\Controller;
use App\Models\Transaksi\OrderBridgeDLIS;
use App\Models\Transaksi\OrderBridgeLIS;
use App\Models\Transaksi\OrderPelayanan;
use App\Models\Transaksi\RisOrder;
use App\Models\Transaksi\StrukOrder;
use App\Traits\Valet;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as Psr7Request;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Master\HargaNettoProdukByKelas;
use App\Models\Transaksi\OrderLab;
use Exception;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BridgingPenunjangCtrl extends Controller
{
    use Valet;
    public function __construct()
    {
        parent::__construct($is_encrypt = true);
    }

    public function saveBridgingZeta(Request $request)
    {

        $kdProfile = (int)$this->kdProfile;
        $noorder = $request['noorder'];
        if (isset($request['details'])) {

            DB::beginTransaction();
            try {

                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float) $item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }

                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['produkid'];
                    $dataOP->qtyproduk = $item['qtyproduk'] ?? 0;
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    if (isset($request['tglpelayanan'])) {
                        $dataOP->tglpelayanan = $request['tglpelayanan'];
                    } else {
                        $dataOP->tglpelayanan = $struk->tglorder; // date('Y-m-d H:i:s');
                    }
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;

                    $dataOP->save();
                }
                $stt = 'true';
            } catch (\Exception $e) {
                $stt = 'false';
            }
            if ($stt == 'true') {
                DB::commit();
            } else {
                DB::rollBack();
            }
        }

        DB::beginTransaction();


        try {
            StrukOrder::where('noorder', $noorder)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusorder' => 1
                    ]
                );
            $raw = DB::select(DB::raw("select op.norec, op.kdprofile as kdprofile,
		     op.iscito as cito,  op.tglpelayanan, prd.id as produkid , op.nourut, prd.namaproduk,
             ps.nocm,  so.noorderintern, so.noorder as noorder, ps.namapasien,  ps.tgllahir,  jk.jeniskelamin,
             alm.alamatlengkap, ru.id as ruanganid, ru.namaruangan, pd.noregistrasi,

             pd.noregistrasi, pg.id as pgid,pg.namalengkap,
             dp.id as departemenid ,prd.objectdetailjenisprodukfk,
			 djp.detailjenisproduk,djp.kodeexternal as kodeexternaldjp ,djp.objectjenisprodukfk,
			 jp.jenisproduk,prd.modality,kps.kelompokpasien,so.tglorder,
			 gdr.golongandarah,ris.order_key
             FROM orderpelayanan_t as op
			 left join produk_m as prd on prd.id=op.objectprodukfk
			 left join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
             left join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
			 left join strukorder_t as so on so.norec=op.strukorderfk
			 left join pasien_m as ps on ps.id =so.nocmfk
			 left join jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
			 left join alamat_m as alm on alm.nocmfk=ps.id

			 left join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
			 left join kelompokpasien_m as kps on kps.id=pd.objectkelompokpasienlastfk
			 left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
			 left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
			 left join departemen_m as dp on dp.id=ru.objectdepartemenfk
			 left join golongandarah_m as gdr on gdr.id=ps.objectgolongandarahfk
			 left join ris_order as ris on ris.order_no=so.noorder
                and ris.order_code=cast( op.objectprodukfk as text)
                and ris.kdprofile = so.kdprofile
       where so.noorder = '$noorder'
       and so.kdprofile = $kdProfile "));

            $errorrr =  "";


            //            try {
            foreach ($raw as $item) {
                $getAccNumber = $this->getAccNumber($item->kodeexternaldjp, $item->modality);

                if ($item->modality == null) {
                    $errorrr = "Modality belum disetting";
                    $modality = null;
                    //                    return $this->setStatusCode(400)->respond('', 'Kode External Bridging Produk '.$item->namaproduk. ' Kosong');
                } else {
                    if (strlen(trim($item->modality)) > 4) {
                        $errorrr = "Modality tidak dikenal";
                        $modality = null;
                    } else {
                        $modality = str_limit(trim($item->modality), 5);
                    }
                }

                if ($modality == null) {
                    $errorrr = '';
                    // break;
                }

                // if (empty($item->nourut)) {
                //   $errorrr = "Tidak ada nomor urut order pelayanan";
                //   break;
                // }

                $newBRG = new RisOrder();
                //                    if (is_null($item->order_key)) {
                $newId = RisOrder::max('order_key');

                $newId = $newId + 1;
                $nourut = RisOrder::where('patient_id', $item->nocm)->max('order_cnt');

                if (is_null($nourut) || empty($nourut) || $nourut == null) {
                    $nourut = 0;
                }

                $nourut = $nourut + 1;
                $newBRG->order_cnt = $nourut;

                $newBRG->order_key = $newId;
                $newBRG->norec_op_fk = $item->norec;
                //                    }else{
                //                        $newBRG = RisOrder::where('order_key',$item->order_key)->first();
                //                    }
                $newBRG->accession_num = $getAccNumber;
                $newBRG->aetitle = '-';
                $newBRG->charge_doc_id = $request['iddokterverif']; //dokter rad
                $newBRG->charge_doc_name =  $request['namadokterverif']; //dokter rad
                $newBRG->consult_doc_id =  $item->pgid;
                $newBRG->consult_doc_name =  $item->namalengkap;
                $newBRG->create_date = (string)date('YmdHi');
                $newBRG->extension1 = $item->noregistrasi;
                /*
               * if JenisDiagnosa==1 : isi namadiagnosa ke extension2 & extension4
               */
                $newBRG->extension2 = '-';
                $newBRG->extension4 = '-';
                /*
               * if JenisDiagnosa==2 : isi namadiagnosa ke eextension3
               */
                $newBRG->extension3 = '-';
                $newBRG->extension5 = $item->kelompokpasien;
                $newBRG->extension6 = $this->getUserId();
                $newBRG->extension7 = '-';
                $newBRG->extension8 = '-';
                $newBRG->extension9 = '-';
                $newBRG->extension10 = '-';
                //            $newBRG->first_name = '';
                $newBRG->flag = 'Y';
                $newBRG->group1 = '-';
                $newBRG->group2 = '-';
                $newBRG->group3 = '-';
                /*
                * - 18 : R. Jalan - 16 : R. Inap
                */
                if ($raw[0]->departemenid == 18) {
                    $io_date = 'E'; // AWALNYA O HARUSNYA E
                } else {
                    $io_date = 'I';
                }
                $newBRG->io_date = $io_date;
                //            $newBRG->last_name = '';
                $newBRG->middle_name = '-';
                $newBRG->order_bodypart = '-';
                $newBRG->order_code = $item->produkid;
                $newBRG->order_comment = '-';
                $newBRG->order_date = (string)date('YmdHi', strtotime($item->tglorder));;
                $newBRG->order_dept = $request['objectruangantujuanfk'];
                //            $newBRG->order_diag = $item->modality;

                $newBRG->order_modality = $modality;
                $newBRG->order_name = $item->namaproduk;
                $newBRG->order_no = $request['noorder'];
                $newBRG->order_reason = '-';
                $newBRG->order_status = 'NW';
                $newBRG->patient_birth_date = (string)date('Ymd', strtotime($item->tgllahir));
                $newBRG->patient_blood = $item->golongandarah;

                // $idPasien = ($item->nourut == null) ? $item->nocm : $item->nocm . '-' . $item->nourut;
                $idPasien = $item->nocm;

                $newBRG->patient_id = $idPasien;
                /*- E: Cito
                  -	*/
                if ($item->cito == '1') {
                    $patient_io = 'E'; // E = Emergency
                } else {
                    $patient_io = 'U'; // awalnya I, harusnya isinya bisa R = Routine, bisa A = Accident, bisa U = Urgent, bisa N = Newborn
                }
                $newBRG->patient_io = $patient_io;
                $newBRG->patient_name = $item->namapasien;
                /*
                   * - M : Laki-laki - F : Perempuan - O : Tidak diketahui
                   */
                if (strtolower($raw[0]->jeniskelamin) == 'laki-laki') {
                    $jk = 'M';
                } else if (strtolower($raw[0]->jeniskelamin) == 'perempuan') {
                    $jk = 'F';
                } else {
                    $jk = 'O';
                }
                $newBRG->patient_sex = $jk;
                $newBRG->patient_uid = '-';
                $newBRG->patient_ward = $item->namaruangan;
                $newBRG->study_remark = '-';
                $newBRG->study_reserv_date = (string)date('YmdHi', strtotime($item->tglpelayanan));
                $newBRG->kelas = $request['objectkelasfk'];

                $newBRG->save();
            }

            $transStatus = (empty($errorrr)) ? 'true' : 'false';
        } catch (\Exception $e) {
            $errorrr = $e->getMessage();

            $transStatus = 'false';
        }

        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noorder'] = $noorder;
            // $ihs = app('App\Http\Controllers\Bridging\IHSController')->ServiceRequest($objetoRequest, true);

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $newBRG,
                    // "ServiceRequest" => $ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan RIS gagal penyebab: " . $errorrr;
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }
    public function getAccNumber($kdExternal, $namaExternal)
    {
        $d[0] = $kdExternal;
        $d[1] = $namaExternal;

        $kode = '';
        if ($namaExternal == null) {
            $kode = "5";
        } else if ($namaExternal == "CR") {
            if ($kdExternal == "1") {
                $kode = "1";
            } else {
                $kode = "2";
            }
        } else if ($namaExternal == "US" || $namaExternal == "EC") {
            $kode = "3";
        } else if ($namaExternal == "CT") {
            $kode = "4";
        } else if ($namaExternal == "MR") {
            $kode = "5";
        } else if ($namaExternal == "DX") {
            $kode = "6";
        } else if ($namaExternal == "XA") {
            $kode = "7";
        } else {
            $kode = "2";
        }

        if (!empty($kode)) {
            $month = (string)date('m');
            $year = (string)date('Y');
            $accessNum = DB::select(DB::raw("select max(a.accession_num) as max
                            from ris_order a where
                            substring(a.accession_num, 1, 1)= '$kode' and
                            substring(a.create_date, 5, 2) = '$month' and
                            substring(a.create_date, 1, 4)= '$year'"));



            if ($accessNum[0] != null) {
                $accessNum = $accessNum[0]->max;
                $yearNow = (string)date('y');

                $digit = null;
                $number = null;
                if (!empty($accessNum)) {
                    $number =  (substr($accessNum, 5)) + 1;
                } else {
                    $number = 1;
                }

                if (strlen($number) == 1) {
                    $digit = "000";
                } else if (strlen($number) == 2) {
                    $digit = "00";
                } else if (strlen($number) == 3) {
                    $digit = "0";
                }
                if (strlen($month) == 1)
                    $month = "0" . $month;

                $noUsulan = $kode . $yearNow . $month . $digit . $number;
            } else {
                $yearNow = (string)date('y');

                $number = 1;
                if (strlen($number) == 1) {
                    $digit = "000";
                } else if (strlen($number) == 2) {
                    $digit = "00";
                } else if (strlen($number) == 3) {
                    $digit = "0";
                }
                if (strlen($month) == 1)
                    $month = "0" . $month;

                $noUsulan = $kode . $yearNow . $month . $digit . $number;
            }
        }

        return $noUsulan;
    }
    public function saveBridgingVansLabOld(Request $request)
    {
        $kdProfile = (int)$this->kdProfile;
        DB::beginTransaction();
        try {
            $noorder = $request['noorder'];
            if (isset($request['details'])) {
                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float) $item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }
                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['produkid'];
                    $dataOP->qtyproduk = $item['qtyproduk'] ?? 0;
                    $dataOP->objectkelasfk = 2;
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    if (isset($request['tglpelayanan'])) {
                        $dataOP->tglpelayanan = $request['tglpelayanan'];
                    } else {
                        $dataOP->tglpelayanan = $struk->tglorder;
                    }
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->ihs_id =  isset($item['ihs_service_request']) ? $item['ihs_service_request'] : null;
                    $dataOP->save();
                }
            }


            $raw = DB::select(DB::raw("
                    SELECT pd.norec as norec_pd, op.norec,op.iscito AS cito,  so.tglorder,to_char(so.tglorder, 'HH:mm') AS jamorder,
                    prd.ID AS produkid,prd.namaproduk, ps.nocm,so.noorder AS noorder,	ps.namapasien,ps.tgllahir,jk. ID AS jkid,jk.jeniskelamin,
                    CASE WHEN alm.alamatlengkap IS NULL THEN   '-'   ELSE (   alm.alamatlengkap || ' ' || (
                        CASE WHEN ds.namadesakelurahan IS NOT NULL THEN 'Kel. ' ||  ds.namadesakelurahan  ELSE    ''   END ) || ' ' || (
                        CASE WHEN kc.namakecamatan IS NOT NULL THEN 'Kec. ' || kc.namakecamatan ELSE ''  END  ) || ' ' || (
                        CASE WHEN kk.namakotakabupaten IS NOT NULL THEN  kk.namakotakabupaten ELSE  '' END  ) || ' ' || (
                        CASE WHEN pro.namapropinsi IS NOT NULL THEN  'Prov. ' ||   pro.namapropinsi ELSE  ''  END  ) )
                    END AS alamatlengkap, ru.ID AS ruanganid, ru.namaruangan, pd.noregistrasi, pg. ID AS pgid,pg.namalengkap,
                 dp. ID AS departemenid, date_part('year', age(ps.tgllahir)) usia, kp. ID AS kode_cara_bayar,  kp.kelompokpasien AS cara_bayar, ru2. ID AS ideuangantujuan,
                 ru2.namaruangan AS ruangantujuan, ps.noidentitas as nik, alm.objectkotakabupatenfk, kk.namakotakabupaten,ps.nohp,
                 ru.objectdepartemenfk ,dp.namadepartemen,pd.objectkelasfk,kl.namakelas,so.objectpegawaiorderfk,  pg.namalengkap
                FROM
                    orderpelayanan_t AS op
                INNER JOIN produk_m AS prd ON prd. ID = op.objectprodukfk
                INNER JOIN strukorder_t AS so ON so.norec = op.strukorderfk
                INNER JOIN pasien_m AS ps ON ps. ID = so.nocmfk
                LEFT JOIN alamat_m as alm on alm.nocmfk=ps.id
                LEFT JOIN desakelurahan_m as ds on ds.id=alm.objectdesakelurahanfk
                LEFT JOIN kotakabupaten_m as kk on kk.id=alm.objectkotakabupatenfk
                LEFT JOIN kecamatan_m as kc on kc.id=alm.objectkecamatanfk
                LEFT JOIN propinsi_m as pro on pro.id=alm.objectpropinsifk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
                INNER JOIN kelompokpasien_m AS kp ON kp. ID = pd.objectkelompokpasienlastfk
                INNER JOIN ruangan_m AS ru ON ru. ID = pd.objectruanganlastfk
                INNER JOIN ruangan_m AS ru2 ON ru2. ID = so.objectruangantujuanfk
                LEFT JOIN jeniskelamin_m AS jk ON jk. ID = ps.objectjeniskelaminfk
                INNER JOIN kelas_m AS kl ON kl.id = pd.objectkelasfk
                LEFT JOIN pegawai_m AS pg ON pg. ID = so.objectpegawaiorderfk
                LEFT JOIN departemen_m AS dp ON dp. ID = ru.objectdepartemenfk
                WHERE
                    so.noorder = '$noorder'
                    and so.kdprofile=$kdProfile
                    and so.statusenabled=true
               "));



            $pdnorec = $raw[0]->norec_pd;
            $diag =  DB::select(DB::raw("
                select DISTINCT dg.kddiagnosa , dg.namadiagnosa,pd.noregistrasi,dg.id
                from pasiendaftar_t as pd
                join antrianpasiendiperiksa_t as apd on apd.noregistrasifk =pd.norec
                join detaildiagnosapasien_t  as ddp on ddp.noregistrasifk=apd.norec
                join diagnosa_m  as dg on dg.id=ddp.objectdiagnosafk
                where pd.kdprofile=$kdProfile and ddp.objectjenisdiagnosafk = 1
                and pd.statusenabled = true and pd.norec ='$pdnorec'
            "));

            $arrDiag = '';
            $kdDiag = '';
            if (count($diag) > 0) {
                foreach ($diag as $d) {
                    $arrDiag = $d->kddiagnosa . '-' . $d->namadiagnosa .  ' ,' . $arrDiag;
                    $kdDiag = $d->id;
                }
            }

            StrukOrder::where('noorder', $noorder)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusorder' => 1
                    ]
                );


            $cek = OrderBridgeLIS::where('order_number', $noorder)->first();
            if (!empty($cek)) {
                OrderBridgeLIS::where('order_number', $noorder)->delete();
            }

            $newBRG = new OrderBridgeLIS();
            $newBRG->norec = $newBRG->generateNewId();
            $newBRG->patient_id = $raw[0]->nocm;
            $newBRG->gender_id = $raw[0]->jkid == 1 ? 'L' : ($raw[0]->jkid == 2 ? 'P' : '');
            $newBRG->gender_name = $raw[0]->jeniskelamin;
            $newBRG->date_of_birth = date('Y/m/d', strtotime($raw[0]->tgllahir));
            $newBRG->patient_name = $raw[0]->namapasien;
            $newBRG->patient_address = $raw[0]->alamatlengkap;
            $newBRG->city_id = $raw[0]->objectkotakabupatenfk;
            $newBRG->city_name = $raw[0]->namakotakabupaten;
            $newBRG->phone_number =  $raw[0]->nohp;
            $newBRG->fax_number = '';
            $newBRG->mobile_number =  $raw[0]->nohp;
            $newBRG->email = '';
            $newBRG->visit_number =   $raw[0]->noregistrasi;
            $newBRG->order_number = $noorder;
            $newBRG->order_datetime =  $raw[0]->tglorder;
            $newBRG->service_unit_id = $raw[0]->objectdepartemenfk;
            $newBRG->service_unit_name = $raw[0]->namadepartemen;
            $newBRG->guarantor_id =  $raw[0]->kode_cara_bayar;
            $newBRG->guarantor_name =  $raw[0]->cara_bayar;
            $newBRG->agreement_id =  $raw[0]->objectkelasfk;
            $newBRG->agreement_name =  $raw[0]->namakelas;
            $newBRG->doctor_id = $raw[0]->objectpegawaiorderfk; // $request['iddokterverif'] ;
            $newBRG->doctor_name = $raw[0]->namalengkap; //$request['namadokterverif'];
            $newBRG->class_id = $raw[0]->objectkelasfk;
            $newBRG->class_name = $raw[0]->namakelas;
            $newBRG->ward_id =  $raw[0]->ruanganid;
            $newBRG->ward_name = $raw[0]->namaruangan;
            $newBRG->room_id =  $raw[0]->ideuangantujuan;
            $newBRG->room_name = $raw[0]->ruangantujuan;
            $newBRG->bed_id = '-';
            $newBRG->bed_name = '-';
            $newBRG->diagnosa_id = $kdDiag;
            $newBRG->diagnosa_name = $arrDiag;
            $newBRG->iscito = $raw[0]->cito;
            $newBRG->reg_user_id = $raw[0]->objectpegawaiorderfk;
            $newBRG->reg_user_name = $raw[0]->namalengkap;
            $newBRG->lis_reg_no = null;
            $newBRG->retrieved_dt = null;
            $newBRG->retrieved_flag = null;
            $newBRG->nik = $raw[0]->nik;
            $newBRG->save();

            foreach ($request['bridging'] as $item) {

                $cek = OrderBridgeDLIS::where('order_number', $noorder)
                    ->where('order_item_id', $item['produkfk'])->first();
                if (!empty($cek)) {
                    OrderBridgeDLIS::where('order_number', $noorder)
                        ->where('order_item_id', $item['produkfk'])->delete();
                }
                $newBRG2 = new OrderBridgeDLIS();
                $newBRG2->norec = $newBRG2->generateNewId();
                $newBRG2->order_number =  $raw[0]->noorder;
                $newBRG2->order_item_id = $item['produkfk'];
                $newBRG2->order_item_name = $item['namaproduk'];
                $newBRG2->orderbridgefk = $newBRG->norec;
                $newBRG2->save();
            }

            $transStatus = 'true';
        } catch (\Exception $e) {
            $transStatus = 'false';
        }
        if ($transStatus == 'true') {
            $transMessage = "Sukses";
            DB::commit();
            // $objetoRequest = new \Illuminate\Http\Request();
            // $objetoRequest['noorder'] = $noorder;
            // $ihs = app('App\Http\Controllers\Bridging\IHSController')->ServiceRequest($objetoRequest, true);

            $result = array(
                "status" => 200,
                "result" => array(
                    "data"  => $newBRG,
                    // "ServiceRequest" => $ihs,
                    "as" => '@epic',
                ),
            );
        } else {
            $transMessage = "Simpan LIS gagal";
            DB::rollBack();
            $result = array(
                "status" => 400,
                "result"  => $e->getMessage() . ' ' . $e->getLine(), //null

            );
        }
        return $this->respond($result['result'], $result['status'], $transMessage);
    }

    public function saveBridgingVansLab(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        DB::beginTransaction();
        try {
            if (isset($request['details'])) {
                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan();
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float)$item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }
                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['idProduk'];
                    $dataOP->qtyproduk = $item['jumlah'] ?? 0;
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->noregistrasi = $request['noregistrasi'];
                    $dataOP->ihs_id =  isset($item['ihs_service_request']) ? $item['ihs_service_request'] : null;
                    $dataOP->save();
                }
            }

            $raw = DB::select(DB::raw("
                SELECT pd.norec as norec_pd
                ,pd.noregistrasi
                ,op.norec
                ,op.iscito AS cito
                ,so.tglorder
                ,prd.id AS produkid
                ,prd.namaproduk
                ,ps.nocm
                ,so.noorder
                ,ps.namapasien
                ,ps.tgllahir
                ,jk.id AS jkid
                ,jk.jeniskelamin
                ,alm.alamatlengkap
                ,date_part('year', age(ps.tgllahir)) || ' Thn' as usia
                ,ru.id AS ruanganid
                ,ru.namaruangan
                ,dp.id AS departemenid
                ,CASE WHEN pg.id IS NULL THEN pg.id ELSE pg2.id END AS pgid
                ,CASE WHEN pg.namalengkap IS NULL THEN pg.namalengkap ELSE pg2.namalengkap END AS namalengkap
                ,kp.id AS kode_cara_bayar
                ,kp.kelompokpasien AS cara_bayar
                ,ru2.id AS idruangantujuan
                ,ru2.namaruangan AS ruangantujuan
                ,ps.noidentitas as nik
                FROM orderpelayanan_t AS op
                INNER JOIN strukorder_t AS so ON so.norec = op.strukorderfk
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
                INNER JOIN produk_m AS prd ON prd.id = op.objectprodukfk
                INNER JOIN pasien_m AS ps ON ps.id = so.nocmfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
                INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                INNER JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
                LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                LEFT JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
                LEFT JOIN pegawai_m AS pg2 ON pg2.id = pd.objectpegawaifk
                LEFT JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                WHERE so.noorder = '$noorder'
                AND so.kdprofile = $kdProfile
                AND so.statusenabled = true
            "));

            StrukOrder::where('noorder', $noorder)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusorder' => 1
                    ]
                );

            foreach ($raw as $item) {
                if ($item->jkid == 1)
                    $jk = 'L';
                else
                    $jk =
                        'P';

                if ($item->cito == '1')
                    $priority = 'U';
                else
                    $priority = 'R';

                $deptRanap = explode(',', $this->settingDataFixed('kdDepartemenRanapFix', $kdProfile));
                $jenisRawat = '';
                foreach ($deptRanap as $itemRanap) {
                    if ((int)$itemRanap == $item->departemenid) {
                        $jenisRawat = 'RANAP';
                        break;
                    } else {
                        $jenisRawat = 'RAJAL';
                        break;
                    }
                }

                $harga =  HargaNettoProdukByKelas::where('objectprodukfk', $item->produkid)
                    ->where('kdprofile', $kdProfile)
                    ->where('objectkelasfk', 2)
                    ->where('statusenabled', true)
                    ->first();

                $cek = OrderLab::where('no_lab', $noorder)->where('kode_test', $item->produkid)->first();
                if (!empty($cek)) {
                    OrderLab::where('no_lab', $noorder)->where('kode_test', $item->produkid)->delete();
                }
                $newBRG = new OrderLab();
                $newBRG->norec = $newBRG->generateNewId();
                $newBRG->asal_lab = $item->ruangantujuan; // $jenisRawat;
                $newBRG->no_lab = $item->noorder;
                $newBRG->no_registrasi = $item->noregistrasi;
                $newBRG->no_rm = $item->nocm;
                $newBRG->tgl_order = $item->tglorder;
                $newBRG->nama_pas = $item->namapasien;
                $newBRG->jenis_kel = $jk;
                $newBRG->tgl_lahir = $item->tgllahir;
                $newBRG->usia = $item->usia;
                $newBRG->alamat = $item->alamatlengkap;
                // $newBRG->kode_dok_kirim = $request['iddokterverif'];
                // $newBRG->nama_dok_kirim = substr($request['namadokterverif'], 0, 30);
                $newBRG->kode_dok_kirim = $item->pgid;
                $newBRG->nama_dok_kirim = $item->namalengkap;
                $newBRG->kode_ruang = $item->ruanganid;
                $newBRG->nama_ruang = $item->namaruangan;
                $newBRG->kode_cara_bayar = $item->kode_cara_bayar;
                $newBRG->cara_bayar = $item->cara_bayar;
                if (isset($request['catatan_klinis'])) {
                    $newBRG->ket_klinis = $request['catatan_klinis'];
                }
                $newBRG->kode_test = $item->produkid;
                $newBRG->test = $item->namaproduk;
                $newBRG->Harga = (float)$harga->hargasatuan;
                $newBRG->waktu_kirim = date('Y-m-d H:i:s');
                $newBRG->prioritas = $priority;
                $newBRG->jns_rawat = $jenisRawat;
                // $newBRG->dok_jaga = $item->namalengkap;
                $newBRG->dok_jaga = $request->namadokterverif;
                $newBRG->status = 0;
                $newBRG->NIK = $item->nik;
                $newBRG->Jumlah_test = count($raw);
                $newBRG->save();
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $newBRG,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went wrong",
                "data"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function saveBridgingVansLabTerjadwal(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        DB::beginTransaction();
        try {
            if (isset($request['details'])) {
                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan();
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float)$item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }
                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['idProduk'];
                    $dataOP->qtyproduk = $item['jumlah'] ?? 0;
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->noregistrasi = $request['noregistrasi'];
                    $dataOP->ihs_id =  isset($item['ihs_service_request']) ? $item['ihs_service_request'] : null;
                    $dataOP->save();
                }
            }

            $raw = DB::select(DB::raw("
                SELECT pd.norec as norec_pd
                ,pd.noregistrasi
                ,op.norec
                ,op.iscito AS cito
                ,so.tglorder
                ,prd.id AS produkid
                ,prd.namaproduk
                ,ps.nocm
                ,so.noorder
                ,ps.namapasien
                ,ps.tgllahir
                ,jk.id AS jkid
                ,jk.jeniskelamin
                ,alm.alamatlengkap
                ,date_part('year', age(ps.tgllahir)) || ' Thn' as usia
                ,ru.id AS ruanganid
                ,ru.namaruangan
                ,dp.id AS departemenid
                ,CASE WHEN pg.id IS NULL THEN pg.id ELSE pg2.id END AS pgid
                ,CASE WHEN pg.namalengkap IS NULL THEN pg.namalengkap ELSE pg2.namalengkap END AS namalengkap
                ,kp.id AS kode_cara_bayar
                ,kp.kelompokpasien AS cara_bayar
                ,pp.pemeriksaanterjadwal
                ,ru2.id AS idruangantujuan
                ,ru2.namaruangan AS ruangantujuan
                ,ps.noidentitas as nik
                FROM orderpelayanan_t AS op
                INNER JOIN strukorder_t AS so ON so.norec = op.strukorderfk
                LEFT JOIN pelayananpasien_t AS pp ON pp.strukorderfk = so.norec
                INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
                INNER JOIN produk_m AS prd ON prd.id = op.objectprodukfk
                INNER JOIN pasien_m AS ps ON ps.id = so.nocmfk
                LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
                INNER JOIN kelompokpasien_m AS kp ON kp.id = pd.objectkelompokpasienlastfk
                INNER JOIN ruangan_m AS ru ON ru.id = pd.objectruanganlastfk
                INNER JOIN ruangan_m AS ru2 ON ru2.id = so.objectruangantujuanfk
                LEFT JOIN jeniskelamin_m AS jk ON jk.id = ps.objectjeniskelaminfk
                LEFT JOIN pegawai_m AS pg ON pg.id = so.objectpegawaiorderfk
                LEFT JOIN pegawai_m AS pg2 ON pg2.id = pd.objectpegawaifk
                LEFT JOIN departemen_m AS dp ON dp.id = ru.objectdepartemenfk
                WHERE so.noorder = '$noorder'
                AND so.kdprofile = $kdProfile
                AND so.statusenabled = true
            "));

            StrukOrder::where('noorder', $noorder)
                ->where('kdprofile', $kdProfile)
                ->update(
                    [
                        'statusorder' => 1
                    ]
                );

            foreach ($raw as $item) {
                if ($item->jkid == 1)
                    $jk = 'L';
                else
                    $jk =
                        'P';

                if ($item->cito == '1')
                    $priority = 'U';
                else
                    $priority = 'R';

                $deptRanap = explode(',', $this->settingDataFixed('kdDepartemenRanapFix', $kdProfile));
                $jenisRawat = '';
                foreach ($deptRanap as $itemRanap) {
                    if ((int)$itemRanap == $item->departemenid) {
                        $jenisRawat = 'RANAP';
                        break;
                    } else {
                        $jenisRawat = 'RAJAL';
                        break;
                    }
                }

                $harga =  HargaNettoProdukByKelas::where('objectprodukfk', $item->produkid)
                    ->where('kdprofile', $kdProfile)
                    ->where('objectkelasfk', 2)
                    ->where('statusenabled', true)
                    ->first();

                $cek = OrderLab::where('no_lab', $noorder)->where('kode_test', $item->produkid)->first();
                if (!empty($cek)) {
                    OrderLab::where('no_lab', $noorder)->where('kode_test', $item->produkid)->delete();
                }
                $newBRG = new OrderLab();
                $newBRG->norec = $newBRG->generateNewId();
                $newBRG->asal_lab = $item->ruangantujuan; // $jenisRawat;
                $newBRG->no_lab = $item->noorder;
                $newBRG->no_registrasi = $item->noregistrasi;
                $newBRG->no_rm = $item->nocm;
                $newBRG->tgl_order = $item->tglorder;
                $newBRG->nama_pas = $item->namapasien;
                $newBRG->jenis_kel = $jk;
                $newBRG->tgl_lahir = $item->tgllahir;
                $newBRG->usia = $item->usia;
                $newBRG->alamat = $item->alamatlengkap;
                $newBRG->kode_dok_kirim = $item->pgid;
                $newBRG->nama_dok_kirim = $item->namalengkap;
                $newBRG->kode_ruang = $item->ruanganid;
                $newBRG->nama_ruang = $item->namaruangan;
                $newBRG->kode_cara_bayar = $item->kode_cara_bayar;
                $newBRG->cara_bayar = $item->cara_bayar;
                if (isset($request['catatan_klinis'])) {
                    $newBRG->ket_klinis = $request['catatan_klinis'];
                }
                $newBRG->kode_test = $item->produkid;
                $newBRG->test = $item->namaproduk;
                $newBRG->Harga = (float)$harga->hargasatuan;
                // $newBRG->waktu_kirim = date('Y-m-d H:i:s');
                $newBRG->waktu_kirim = $request->pemeriksaanterjadwal;
                $newBRG->prioritas = $priority;
                $newBRG->jns_rawat = $jenisRawat;
                // $newBRG->dok_jaga = $item->namalengkap;
                $newBRG->dok_jaga = $request->namadokterverif;
                $newBRG->status = 0;
                $newBRG->NIK = $item->nik;
                $newBRG->Jumlah_test = count($raw);
                $newBRG->save();
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses",
                "data" => $newBRG,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Something Went wrong",
                "data"  => $e->getMessage() . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function saveBridgingPacs(Request $request)
    {
        $dataLogin = $request->all();
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        DB::beginTransaction();
        try {
            if (isset($request['details'])) {
                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float) $item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }

                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['produkfk'];
                    $dataOP->qtyproduk = $item['qtyproduk'] ?? 0;
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    if (isset($request['tglpelayanan'])) {
                        $dataOP->tglpelayanan = $request['tglpelayanan'];
                    } else {
                        $dataOP->tglpelayanan = $struk->tglorder;
                    }
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->nourut = isset($item['nourut']) ? $item['nourut'] : null;

                    $dataOP->save();
                }
            }

            $raw = DB::select(DB::raw("select op.norec, op.kdprofile as kdprofile,
            op.iscito as cito, op.tglpelayanan, prd.id as produkid, op.nourut, prd.namaproduk,
            ps.nocm, so.noorderintern, so.noorder, ps.namapasien, ps.tgllahir, jk.jeniskelamin,
            alm.alamatlengkap, ru.id as ruanganid, ru.namaruangan, pd.noregistrasi, pd.norec as norec_pd, ps.nohp,
            pg.id as pgid, pg.namalengkap, dp.id as departemenid ,prd.objectdetailjenisprodukfk,
            kps.kelompokpasien,so.tglorder, gdr.golongandarah,ris.order_key, prd.modality
            FROM orderpelayanan_t as op
            left join produk_m as prd on prd.id=op.objectprodukfk
            left join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
            left join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
            left join strukorder_t as so on so.norec=op.strukorderfk
            left join pasien_m as ps on ps.id =so.nocmfk
            left join jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
            left join alamat_m as alm on alm.nocmfk=ps.id
            left join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
            left join kelompokpasien_m as kps on kps.id=pd.objectkelompokpasienlastfk
            left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
            left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
            left join departemen_m as dp on dp.id=ru.objectdepartemenfk
            left join golongandarah_m as gdr on gdr.id=ps.objectgolongandarahfk
            left join ris_order as ris on ris.order_no=so.noorder
            and ris.order_code=cast( op.objectprodukfk as text)
            and ris.kdprofile = so.kdprofile
            where so.noorder = '$noorder'
            and so.kdprofile = $kdProfile"));

            $errorrr =  "";
            $cek = null;
            $deptRajal = explode(',', $this->settingDataFixed('kdDepartemenRawatJalanFix', $kdProfile));
            foreach ($raw as $key => $item) {
                $cek = RisOrder::where('order_no', $request['noorder'])
                    ->where('order_code', $item->produkid)
                    ->first();
                if (empty($cek)) {
                    $newBRG = new RisOrder();
                    $newId = RisOrder::max('order_key');
                    $newId = $newId + 1;
                } else {
                    $newBRG  = RisOrder::where('order_key', $cek->order_key)->first();
                    $newId = $newBRG->order_key;
                }

                $getAccNumber = $noorder . "" . (string)$newId; //$this->getAccNumber($item->kodeexternaldjp , $item->namaexternaljp);
                $nourut = RisOrder::where('patient_id', $item->nocm)->max('order_cnt');

                if (is_null($nourut) || empty($nourut) || $nourut == null) {
                    $nourut = 0;
                }

                $nourut = $nourut + 1;
                $newBRG->order_cnt = $nourut;
                $newBRG->order_key = $newId;
                $newBRG->norec_op_fk = $item->norec;
                $newBRG->accession_num = $getAccNumber;
                $newBRG->aetitle = '-';
                $newBRG->charge_doc_id = $request['iddokterverif']; //dokter rad
                $newBRG->charge_doc_name =  $request['namadokterverif']; //dokter rad
                $newBRG->consult_doc_id =  $item->pgid;
                $newBRG->consult_doc_name =  $item->namalengkap;
                $newBRG->create_date = (string)date('YmdHi');
                $newBRG->extension1 = $item->noregistrasi;
                /*
                * if JenisDiagnosa==1 : isi namadiagnosa ke extension2 & extension4
                */
                $newBRG->extension2 = '-';
                $newBRG->extension4 =  $request['keterangan'];
                /*
                * if JenisDiagnosa==2 : isi namadiagnosa ke eextension3
                */
                $newBRG->extension3 = '-';
                $newBRG->extension5 = $item->kelompokpasien;
                $newBRG->extension6 = $dataLogin['userData']['id'];
                $newBRG->extension7 = '-';
                $newBRG->extension8 = '-';
                $newBRG->extension9 = '-';
                $newBRG->extension10 = '-';
                $newBRG->flag = 'Y';
                $newBRG->group1 = '-';
                $newBRG->group2 = '-';
                $newBRG->group3 = '-';
                /*
                * - 18 : R. Jalan - 16 : R. Inap
                */
                if (in_array($raw[0]->departemenid, $deptRajal)) {
                    $io_date = 'E';
                } else {
                    $io_date = 'I';
                }
                $newBRG->io_date = $io_date;
                $newBRG->middle_name = '-';
                $newBRG->order_bodypart = '-';
                $newBRG->order_code = $item->produkid;
                $newBRG->order_comment = '-';
                $newBRG->order_date = (string)date('YmdHi', strtotime($item->tglorder));;
                $newBRG->order_dept = $request['objectruangantujuanfk'];
                $newBRG->order_modality = $item->modality;
                $newBRG->order_name = $item->namaproduk;
                $newBRG->order_no = $request['noorder'];
                $newBRG->order_reason = '-';
                $newBRG->order_status = 'NW';
                $newBRG->patient_birth_date = (string)date('Ymd', strtotime($item->tgllahir));
                $newBRG->patient_blood = $item->golongandarah;
                $newBRG->patient_id = $item->nocm;
                /*- E: Cito
                *
                */
                if ($item->cito == '1') {
                    $patient_io = 'E'; // E = Emergency
                } else {
                    $patient_io = 'U'; // awalnya I, harusnya isinya bisa R = Routine, bisa A = Accident, bisa U = Urgent, bisa N = Newborn
                }
                $newBRG->patient_io = $patient_io;
                $newBRG->patient_name = $item->namapasien;
                /*
                * - M : Laki-laki - F : Perempuan - O : Tidak diketahui
                */
                if (strtolower($raw[0]->jeniskelamin) == 'laki-laki') {
                    $jk = 'M';
                } else if (strtolower($raw[0]->jeniskelamin) == 'perempuan') {
                    $jk = 'F';
                } else {
                    $jk = 'O';
                }
                $newBRG->patient_sex = $jk;
                $newBRG->patient_uid = '-';
                $newBRG->patient_ward = $item->namaruangan;
                $newBRG->study_remark = '-';
                $newBRG->study_reserv_date = (string)date('YmdHi', strtotime($item->tglpelayanan));
                $newBRG->kelas = $request['objectkelasfk'];
                $newBRG->kdprofile = $kdProfile;
                $newBRG->save();
                $response = null;
                // cek status brigding atau enggak
                $statusBrigding =  $this->settingFix('isBridgingPACS', $kdProfile);
                if (!empty($statusBrigding) && $statusBrigding == 'true') {
                    if ($item->modality == null) {
                        $errorrr = "Modality belum disetting";
                        $modality = null;
                    } else {
                        if (strlen(trim($item->modality)) > 4) {
                            $errorrr = "Modality tidak dikenal";
                            $modality = null;
                        } else {
                            $modality = strtoupper(trim($item->modality));
                        }
                    }

                    if ($modality == null) {
                        break;
                    }

                    $dataJson = array(
                        "Order" => array(
                            "patient" => array(
                                "id" => $item->nocm, // str_pad($item->nocm, 10, '0', STR_PAD_LEFT),
                                "first_name" => "",
                                "middle_name" => "",
                                "last_name" => $item->namapasien,
                                "sex" => $jk,
                                "birthDate" => (string) date('Y-m-d', strtotime($item->tgllahir)),
                                "phone" => substr($item->nohp, 0, 12),
                                "address" => $item->alamatlengkap,
                                "height" => "0",
                                "weight" => "0",
                                "priority" => $patient_io,
                                "department" => $item->namaruangan,
                                "AdmissionType" => explode('/', $item->kelompokpasien)[0]
                            ),
                            "order" => array(
                                "id" => $getAccNumber,
                                "serviceCode" => (string)$item->produkid,
                                "serviceName" => $item->namaproduk,
                                "status" => "UPDATE",
                                "orderDate" => date('Y-m-d H:i:s'), //date('Y-m-d', strtotime($item->tglorder)),
                                "doctor" => $request['namadokterverif'],
                                "modality" => $item->modality
                            ),
                        )
                    );
                    $baseurl =  $this->settingFix('urlSendOrderPACS', $kdProfile);
                    $response = Http::timeout(30)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($baseurl, $dataJson);
                    Log::info("Response dari API LAB PACS : " . json_encode($response));
                    Log::channel('orderRadiologiLOG')->info("Response dari API LAB PACS " . $request['noorder'] . ": " . json_encode($response));
                    $cek['url'] = $baseurl;
                    $cek['body'] = $dataJson;
                    $cek['status'] = $response->status();
                    $cek['response'] = $response->body();
                    $cek['json'] = $response->json();

                    if ($response->json()['code'] != 200) {
                        $errorrr = 'KIRIM PACS GAGAL';
                    }
                }
            }

            if (!empty($errorrr)) {
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "message" => $errorrr,
                    "data"  => $errorrr
                );

                return $this->respond($result['data'], $result['status'], $result['message']);
                // throw new Exception($errorrr);
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan RIS sukses",
                "data" => array(
                    "ris_order" => $newBRG,
                    "integrasi" => $cek,
                    "pacs" => !empty($response) ? $response->json() : null,
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data"  => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }
    public function saveBridgingPacsTerjadwal(Request $request)
    {
        $dataLogin = $request->all();
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        DB::beginTransaction();
        try {
            if (isset($request['details'])) {
                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan;
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float) $item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }

                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['produkfk'];
                    $dataOP->qtyproduk = $item['qtyproduk'] ?? 0;
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    if (isset($request['tglpelayanan'])) {
                        $dataOP->tglpelayanan = $request['tglpelayanan'];
                    } else {
                        $dataOP->tglpelayanan = $struk->tglorder;
                    }
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->nourut = isset($item['nourut']) ? $item['nourut'] : null;

                    $dataOP->save();
                }
            }

            $raw = DB::select(DB::raw("select op.norec, op.kdprofile as kdprofile,
            op.iscito as cito, op.tglpelayanan, prd.id as produkid, op.nourut, prd.namaproduk,
            ps.nocm, so.noorderintern, so.noorder, ps.namapasien, ps.tgllahir, jk.jeniskelamin,
            alm.alamatlengkap, ru.id as ruanganid, ru.namaruangan, pd.noregistrasi, pd.norec as norec_pd, ps.nohp,
            pg.id as pgid, pg.namalengkap, dp.id as departemenid ,prd.objectdetailjenisprodukfk,
            kps.kelompokpasien,so.tglorder, gdr.golongandarah,ris.order_key, prd.modality
            FROM orderpelayanan_t as op
            left join produk_m as prd on prd.id=op.objectprodukfk
            left join detailjenisproduk_m as djp on djp.id=prd.objectdetailjenisprodukfk
            left join jenisproduk_m as jp on jp.id=djp.objectjenisprodukfk
            left join strukorder_t as so on so.norec=op.strukorderfk
            left join pasien_m as ps on ps.id =so.nocmfk
            left join jeniskelamin_m as jk on jk.id=ps.objectjeniskelaminfk
            left join alamat_m as alm on alm.nocmfk=ps.id
            left join pasiendaftar_t as pd on pd.norec=so.noregistrasifk
            left join kelompokpasien_m as kps on kps.id=pd.objectkelompokpasienlastfk
            left join ruangan_m as ru on ru.id=pd.objectruanganlastfk
            left join pegawai_m as pg on pg.id=so.objectpegawaiorderfk
            left join departemen_m as dp on dp.id=ru.objectdepartemenfk
            left join golongandarah_m as gdr on gdr.id=ps.objectgolongandarahfk
            left join ris_order as ris on ris.order_no=so.noorder
            and ris.order_code=cast( op.objectprodukfk as text)
            and ris.kdprofile = so.kdprofile
            where so.noorder = '$noorder'
            and so.kdprofile = $kdProfile"));

            $errorrr =  "";
            $cek = null;
            $deptRajal = explode(',', $this->settingDataFixed('kdDepartemenRawatJalanFix', $kdProfile));
            foreach ($raw as $key => $item) {
                $cek = RisOrder::where('order_no', $request['noorder'])
                    ->where('order_code', $item->produkid)
                    ->first();
                if (empty($cek)) {
                    $newBRG = new RisOrder();
                    $newId = RisOrder::max('order_key');
                    $newId = $newId + 1;
                } else {
                    $newBRG  = RisOrder::where('order_key', $cek->order_key)->first();
                    $newId = $newBRG->order_key;
                }

                $getAccNumber = $noorder . "" . (string)$newId; //$this->getAccNumber($item->kodeexternaldjp , $item->namaexternaljp);
                $nourut = RisOrder::where('patient_id', $item->nocm)->max('order_cnt');

                if (is_null($nourut) || empty($nourut) || $nourut == null) {
                    $nourut = 0;
                }

                $nourut = $nourut + 1;
                $newBRG->order_cnt = $nourut;
                $newBRG->order_key = $newId;
                $newBRG->norec_op_fk = $item->norec;
                $newBRG->accession_num = $getAccNumber;
                $newBRG->aetitle = '-';
                $newBRG->charge_doc_id = $request['iddokterverif']; //dokter rad
                $newBRG->charge_doc_name =  $request['namadokterverif']; //dokter rad
                $newBRG->consult_doc_id =  $item->pgid;
                $newBRG->consult_doc_name =  $item->namalengkap;
                $newBRG->create_date = (string)date('YmdHi');
                $newBRG->extension1 = $item->noregistrasi;
                /*
                * if JenisDiagnosa==1 : isi namadiagnosa ke extension2 & extension4
                */
                $newBRG->extension2 = '-';
                $newBRG->extension4 =  $request['keterangan'];
                /*
                * if JenisDiagnosa==2 : isi namadiagnosa ke eextension3
                */
                $newBRG->extension3 = '-';
                $newBRG->extension5 = $item->kelompokpasien;
                $newBRG->extension6 = $dataLogin['userData']['id'];
                $newBRG->extension7 = '-';
                $newBRG->extension8 = '-';
                $newBRG->extension9 = '-';
                $newBRG->extension10 = '-';
                $newBRG->flag = 'Y';
                $newBRG->group1 = '-';
                $newBRG->group2 = '-';
                $newBRG->group3 = '-';
                /*
                * - 18 : R. Jalan - 16 : R. Inap
                */
                if (in_array($raw[0]->departemenid, $deptRajal)) {
                    $io_date = 'E';
                } else {
                    $io_date = 'I';
                }
                $newBRG->io_date = $io_date;
                $newBRG->middle_name = '-';
                $newBRG->order_bodypart = '-';
                $newBRG->order_code = $item->produkid;
                $newBRG->order_comment = '-';
                $newBRG->order_date = (string)date('YmdHi', strtotime($item->tglorder));;
                $newBRG->order_dept = $request['objectruangantujuanfk'];
                $newBRG->order_modality = $item->modality;
                $newBRG->order_name = $item->namaproduk;
                $newBRG->order_no = $request['noorder'];
                $newBRG->order_reason = '-';
                $newBRG->order_status = 'NW';
                $newBRG->patient_birth_date = (string)date('Ymd', strtotime($item->tgllahir));
                $newBRG->patient_blood = $item->golongandarah;
                $newBRG->patient_id = $item->nocm;
                /*- E: Cito
                *
                */
                if ($item->cito == '1') {
                    $patient_io = 'E'; // E = Emergency
                } else {
                    $patient_io = 'U'; // awalnya I, harusnya isinya bisa R = Routine, bisa A = Accident, bisa U = Urgent, bisa N = Newborn
                }
                $newBRG->patient_io = $patient_io;
                $newBRG->patient_name = $item->namapasien;
                /*
                * - M : Laki-laki - F : Perempuan - O : Tidak diketahui
                */
                if (strtolower($raw[0]->jeniskelamin) == 'laki-laki') {
                    $jk = 'M';
                } else if (strtolower($raw[0]->jeniskelamin) == 'perempuan') {
                    $jk = 'F';
                } else {
                    $jk = 'O';
                }
                $newBRG->patient_sex = $jk;
                $newBRG->patient_uid = '-';
                $newBRG->patient_ward = $item->namaruangan;
                $newBRG->study_remark = '-';
                // $newBRG->study_reserv_date = (string)date('YmdHi',strtotime($item->tglpelayanan));
                $newBRG->study_reserv_date = (string)date('YmdHi', strtotime($request->pemeriksaanterjadwal));
                $newBRG->kelas = $request['objectkelasfk'];
                $newBRG->kdprofile = $kdProfile;
                $newBRG->save();
                $response = null;
                // cek status brigding atau enggak
                $statusBrigding =  $this->settingFix('isBridgingPACS', $kdProfile);
                if (!empty($statusBrigding) && $statusBrigding == 'true') {
                    if ($item->modality == null) {
                        $errorrr = "Modality belum disetting";
                        $modality = null;
                    } else {
                        if (strlen(trim($item->modality)) > 4) {
                            $errorrr = "Modality tidak dikenal";
                            $modality = null;
                        } else {
                            $modality = strtoupper(trim($item->modality));
                        }
                    }

                    if ($modality == null) {
                        break;
                    }

                    $dataJson = array(
                        "Order" => array(
                            "patient" => array(
                                "id" => $item->nocm, // str_pad($item->nocm, 10, '0', STR_PAD_LEFT),
                                "first_name" => "",
                                "middle_name" => "",
                                "last_name" => $item->namapasien,
                                "sex" => $jk,
                                "birthDate" => (string) date('Y-m-d', strtotime($item->tgllahir)),
                                "phone" => substr($item->nohp, 0, 12),
                                "address" => $item->alamatlengkap,
                                "height" => "0",
                                "weight" => "0",
                                "priority" => $patient_io,
                                "department" => $item->namaruangan,
                                "AdmissionType" => explode('/', $item->kelompokpasien)[0]
                            ),
                            "order" => array(
                                "id" => $getAccNumber,
                                "serviceCode" => (string)$item->produkid,
                                "serviceName" => $item->namaproduk,
                                "status" => "UPDATE",
                                // "orderDate" => date('Y-m-d H:i:s'),//date('Y-m-d', strtotime($item->tglorder)),
                                "orderDate" => date('Y-m-d H:i:s', strtotime($request->pemeriksaanterjadwal)),
                                "doctor" => $request['namadokterverif'],
                                "modality" => $item->modality
                            ),
                        )
                    );
                    $baseurl =  $this->settingFix('urlSendOrderPACS', $kdProfile);
                    $response = Http::timeout(30)
                        ->withHeaders(['Content-Type' => 'application/json'])
                        ->post($baseurl, $dataJson);
                    $cek['url'] = $baseurl;
                    $cek['body'] = $dataJson;
                    $cek['status'] = $response->status();
                    $cek['response'] = $response->body();
                    $cek['json'] = $response->json();

                    if ($response->json()['code'] != 200) {
                        $errorrr = 'KIRIM PACS GAGAL';
                    }
                }
            }

            if (!empty($errorrr)) {
                DB::rollBack();
                $result = array(
                    "status" => 400,
                    "message" => $errorrr,
                    "data"  => $errorrr
                );

                return $this->respond($result['data'], $result['status'], $result['message']);
                // throw new Exception($errorrr);
            }

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan RIS sukses",
                "data" => array(
                    "ris_order" => $newBRG,
                    "integrasi" => $cek,
                    "pacs" => !empty($response) ? $response->json() : null,
                ),
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data"  => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function sendPACS($data, $kdProfile)
    {
        $baseurl = $this->settingDataFixed('urlSendOrderPACS', $kdProfile);
        $dataJsonSend = json_encode($data);
        $header = array(
            'Content-Type: application/json'
        );

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $baseurl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $dataJsonSend,
            CURLOPT_HTTPHEADER => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function saveSendBack(Request $request)
    {
        $kdProfile = $this->kdProfile;
        DB::beginTransaction();
        try {
            $data = DB::table('ris_order as ro')
                ->select(
                    'ro.patient_id',
                    'ro.last_name',
                    'ro.patient_sex',
                    'ro.patient_birth_date',
                    'ro.patient_io',
                    'ro.extension5',
                    'ro.order_code',
                    'ro.order_name',
                    'ro.order_date',
                    'ro.consult_doc_name',
                    'ro.order_modality',
                    'ro.accession_num'
                )
                ->where('ro.accession_num', $request['accession_num'])
                ->first();

            RisOrder::where('accession_num', $request['accession_num'])
                ->update(
                    [
                        'order_complete' => 1,
                        'description' => $request['description'],
                        'report_date' => $request['report_date'],
                        'charge_doc_id' => $request['charge_doc_id'],
                        'charge_doc_name' => $request['charge_doc_name'],
                        'link' => $request['link']
                    ]
                );

            $dataJson = array(
                "Order" => array(
                    "patient" => array(
                        "id" => $data->patient_id,
                        "first_name" => "",
                        "middle_name" => "",
                        "last_name" => $data->last_name,
                        "sex" => $data->patient_sex,
                        "birthDate" => (string) date('Y-m-d', strtotime($data->patient_birth_date)),
                        "phone" => "",
                        "address" => "Indonesia",
                        "height" => "0",
                        "weight" => "0",
                        "priority" => $data->patient_io,
                        "department" => "Radiologi",
                        "AdmissionType" => $data->extension5,
                    ),
                    "order" => array(
                        "id" => $data->accession_num,
                        "serviceCode" => $data->order_code,
                        "serviceName" => $data->order_name,
                        "status" => "UPDATE",
                        "orderDate" => (string) date('Y-m-d', strtotime($data->order_date)),
                        "doctor" => $data->consult_doc_name,
                        "modality" => $data->order_modality
                    ),
                    "report" => array(
                        "description" => $request['description'],
                        "reportDate" => $request['report_date'],
                        "doctorID" => $request['charge_doc_id'],
                        "doctorName" => $request['charge_doc_name'],
                        "link" => "null"
                    ),

                )
            );

            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Simpan RIS sukses",
                "data" => $dataJson,
            );
        } catch (Exception $e) {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data"  => $e->getMessage() . " " . $e->getLine()
            );
        }
        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function saveBridgingVansLabNew(Request $request)
    {
        $kdProfile = $this->kdProfile;
        $noorder = $request['noorder'];
        DB::beginTransaction();
        try {
            if (isset($request['details'])) {
                $struk = StrukOrder::where('noorder', $noorder)->where('kdprofile', $kdProfile)->where('statusenabled', true)->first();
                OrderPelayanan::where('strukorderfk', $struk->norec)->delete();
                foreach ($request['details'] as $item) {
                    $dataOP = new OrderPelayanan();
                    $dataOP->norec = $dataOP->generateNewId();
                    $dataOP->kdprofile = $kdProfile;
                    $dataOP->statusenabled = true;
                    if (isset($item['iscito'])) {
                        $dataOP->iscito = (float)$item['iscito'];
                    } else {
                        $dataOP->iscito = 0;
                    }
                    $dataOP->noorderfk = $struk->norec;
                    $dataOP->objectprodukfk = $item['idProduk'];
                    $dataOP->qtyproduk = $item['jumlah'] ?? 0;
                    $dataOP->objectkelasfk = $request['objectkelasfk'];
                    $dataOP->qtyprodukretur = 0;
                    $dataOP->objectruanganfk = $struk->objectruanganfk;
                    $dataOP->objectruangantujuanfk = $struk->objectruangantujuanfk;
                    $dataOP->strukorderfk = $struk->norec;
                    $dataOP->tglpelayanan = date('Y-m-d H:i:s');
                    $dataOP->objectnamapenyerahbarangfk = $struk->objectpegawaiorderfk;
                    $dataOP->noregistrasi = $request['noregistrasi'];
                    $dataOP->ihs_id =  isset($item['ihs_service_request']) ? $item['ihs_service_request'] : null;
                    $dataOP->save();
                }
            }

            $raw = DB::select(DB::raw("
                SELECT
                    pd.norec AS norec_pd,
                    pd.noregistrasi,
                    op.norec,
                    op.iscito AS cito,
                    so.tglorder,
                    prd.ID AS produkid,
                    prd.namaproduk,
                    ps.nocm,
                    so.noorder,
                    ps.namapasien,
                    ps.tgllahir,
                    jk.ID AS jkid,
                    jk.jeniskelamin,
                    alm.alamatlengkap,
                    date_part( 'year', age( ps.tgllahir ) ) || ' Thn' AS usia,
                    ru.ID AS ruanganid,
                    ru.namaruangan,
                    dp.ID AS departemenid,
                    CASE
                        WHEN pg.ID IS NOT NULL THEN pg.ID
                        ELSE pg2.ID
                    END AS pgid,

                    CASE
                        WHEN pg.namalengkap IS NOT NULL THEN pg.namalengkap
                        ELSE pg2.namalengkap
                    END AS namalengkap,

                    kp.ID AS kode_cara_bayar,
                    kp.kelompokpasien AS cara_bayar,
                    ru2.ID AS idruangantujuan,
                    ru2.namaruangan AS ruangantujuan,
                    ps.noidentitas AS nik,
                    kls.id AS kelasid,
                    kls.namakelas
                FROM
                    orderpelayanan_t AS op
                    INNER JOIN strukorder_t AS so ON so.norec = op.strukorderfk
                    INNER JOIN pasiendaftar_t AS pd ON pd.norec = so.noregistrasifk
                    INNER JOIN produk_m AS prd ON prd.ID = op.objectprodukfk
                    INNER JOIN pasien_m AS ps ON ps.ID = so.nocmfk
                    LEFT JOIN alamat_m AS alm ON alm.nocmfk = ps.
                    ID INNER JOIN kelompokpasien_m AS kp ON kp.ID = pd.objectkelompokpasienlastfk
                    INNER JOIN ruangan_m AS ru ON ru.ID = pd.objectruanganlastfk
                    INNER JOIN ruangan_m AS ru2 ON ru2.ID = so.objectruangantujuanfk
                    LEFT JOIN jeniskelamin_m AS jk ON jk.ID = ps.objectjeniskelaminfk
                    LEFT JOIN pegawai_m AS pg ON pg.ID = so.objectpegawaiorderfk
                    LEFT JOIN pegawai_m AS pg2 ON pg2.ID = pd.objectpegawaifk
                    LEFT JOIN departemen_m AS dp ON dp.ID = ru.objectdepartemenfk
                    LEFT JOIN kelas_m AS kls ON kls.id = pd.objectkelasfk
                WHERE
                    so.noorder = '$noorder'
                    AND so.kdprofile = $kdProfile
                    AND so.statusenabled = TRUE
            "));

            // StrukOrder::where('noorder', $noorder)
            //     ->where('kdprofile', $kdProfile)
            //     ->update(
            //         [
            //             'statusorder' => 1
            //         ]
            //     );

            $noregis = $raw[0]->noregistrasi;
            $diagnosaPasien = DB::select(DB::raw("
                SELECT
	                dg.kddiagnosa, dg.namadiagnosa
                FROM diagnosapasien_t AS dgp
                LEFT JOIN detaildiagnosapasien_t AS ddgp ON ddgp.objectdiagnosapasienfk = dgp.norec AND ddgp.objectjenisdiagnosafk = 1
                LEFT JOIN diagnosa_m AS dg ON dg.id = ddgp.objectdiagnosafk
                LEFT JOIN antrianpasiendiperiksa_t AS apd ON apd.norec = dgp.noregistrasifk
                LEFT JOIN pasiendaftar_t AS pd ON pd.norec = apd.noregistrasifk
                WHERE pd.kdprofile = $kdProfile
                AND pd.statusenabled = TRUE
                AND dgp.statusenabled = TRUE
                AND ddgp.statusenabled = TRUE
                AND pd.noregistrasi = '$noregis'
            "));

            $strDiagnosa = '';
            if (count($diagnosaPasien) > 0) {
                $strDiagnosa = $diagnosaPasien[0]->kddiagnosa . ' - ' . $diagnosaPasien[0]->namadiagnosa;
            }

            $arrLayanan = [];
            $sex = '1';
            $patienttype = '1';
            foreach ($raw as $item) {
                if ($item->jkid == 1) {

                    $jk = 'L';
                    $sex = '1';
                } else {
                    $jk = 'P';
                    $sex = '2';
                }

                if ($item->cito == '1')
                    $priority = 'U';
                else
                    $priority = 'R';

                $deptRanap = explode(',', $this->settingDataFixed('kdDepartemenRanapFix', $kdProfile));
                $jenisRawat = '';
                foreach ($deptRanap as $itemRanap) {
                    if ((int)$itemRanap == $item->departemenid) {
                        $jenisRawat = 'RANAP';
                        $patienttype = '1';
                        break;
                    } else {
                        $jenisRawat = 'RAJAL';
                        $patienttype = '0';
                        break;
                    }
                }

                $harga =  HargaNettoProdukByKelas::where('objectprodukfk', $item->produkid)
                    ->where('kdprofile', $kdProfile)
                    ->where('objectkelasfk', 2)
                    ->where('statusenabled', true)
                    ->first();

                $cek = OrderLab::where('no_lab', $noorder)->where('kode_test', $item->produkid)->first();
                if (!empty($cek)) {
                    OrderLab::where('no_lab', $noorder)->where('kode_test', $item->produkid)->delete();
                }
                $newBRG = new OrderLab();
                $newBRG->norec = $newBRG->generateNewId();
                $newBRG->asal_lab = $item->ruangantujuan; // $jenisRawat;
                $newBRG->no_lab = $item->noorder;
                $newBRG->no_registrasi = $item->noregistrasi;
                $newBRG->no_rm = $item->nocm;
                $newBRG->tgl_order = $item->tglorder;
                $newBRG->nama_pas = $item->namapasien;
                $newBRG->jenis_kel = $jk;
                $newBRG->tgl_lahir = $item->tgllahir;
                $newBRG->usia = $item->usia;
                $newBRG->alamat = $item->alamatlengkap;
                // $newBRG->kode_dok_kirim = $request['iddokterverif'];
                // $newBRG->nama_dok_kirim = substr($request['namadokterverif'], 0, 30);
                $newBRG->kode_dok_kirim = $item->pgid;
                $newBRG->nama_dok_kirim = $item->namalengkap;
                $newBRG->kode_ruang = $item->ruanganid;
                $newBRG->nama_ruang = $item->namaruangan;
                $newBRG->kode_cara_bayar = $item->kode_cara_bayar;
                $newBRG->cara_bayar = $item->cara_bayar;
                if (isset($request['catatan_klinis'])) {
                    $newBRG->ket_klinis = $request['catatan_klinis'];
                }
                $newBRG->kode_test = $item->produkid;
                $newBRG->test = $item->namaproduk;
                $newBRG->Harga = (float)$harga->hargasatuan;
                $newBRG->waktu_kirim = isset($request['pemeriksaanterjadwal']) ? date('Y-m-d H:i:s', strtotime($request['pemeriksaanterjadwal'])) : date('Y-m-d H:i:s');
                $newBRG->prioritas = $priority;
                $newBRG->jns_rawat = $jenisRawat;
                // $newBRG->dok_jaga = $item->namalengkap;
                $newBRG->dok_jaga = $request->namadokterverif;
                $newBRG->status = 0;
                $newBRG->NIK = $item->nik;
                $newBRG->Jumlah_test = count($raw);
                $newBRG->save();

                $arrLayanan[] = array(
                    'ItemCode' => $item->produkid,
                    'ItemName' => $item->namaproduk
                );
            }

            $statusBrigding = $this->settingFix('isBridgingLIS', $kdProfile);
            if (!empty($statusBrigding) && $statusBrigding == 'true') {
                $arrData = array(
                    'PatientCode' => $raw[0]->nocm,
                    'OrderNumber' => $raw[0]->noorder,
                    'PatientName' => $raw[0]->namapasien,
                    'IdentityNumber' => '-',
                    'PatientDob' => $raw[0]->tgllahir,
                    'PatientSexCode' => $sex,
                    'PatientAddress' => $raw[0]->alamatlengkap,
                    'RegNumber' => $raw[0]->noregistrasi,
                    'KodeRuangTujuan' => $raw[0]->idruangantujuan,
                    'NamaRuangTujuan' => $raw[0]->ruangantujuan,
                    'DiagnosisName' => isset($request['catatan_klinis']) ? $request['catatan_klinis'] : '-',
                    'OrderDateTime' => isset($request['pemeriksaanterjadwal']) ? date('Y-m-d H:i:s', strtotime($request['pemeriksaanterjadwal'])) : date('Y-m-d H:i:s'),
                    'DoctorOrderCode' => $raw[0]->pgid,
                    'DoctorOrderName' => $raw[0]->namalengkap,
                    'ServiceClassCode' => $raw[0]->kelasid,
                    'ServiceClassName' => $raw[0]->namakelas,
                    'WardRoomCode' => $raw[0]->ruanganid,
                    'WardRoomName' => $raw[0]->namaruangan,
                    'GuarantorId' => $raw[0]->kode_cara_bayar,
                    'GuarantorName' => $raw[0]->cara_bayar,
                    'OrderedItems' => $arrLayanan,
                    'PatientType' => $patienttype,
                    'OrderState' => 'N',
                    'IsCito' => (int)$raw[0]->cito
                );

                $errorMessage = '';
                $transStatus = true;

                if ($struk->objectruangantujuanfk != 84 && $struk->objectruangantujuanfk != 206) {
                    $jsonData = json_encode($arrData);
                    $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
                    $xid = $this->settingFix('xidPromlis', $kdProfile);
                    $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
                    $header = $this->generateHeaderPromLis($xid, $xkey);

                    $url = $baseUrl . 'post_order';
                    $method = 'POST';
                    $response = $this->curlSendLis($url, $header, $method, $jsonData);
                    Log::info("Response dari API LAB LIS : " . json_encode($response));
                    Log::channel('orderLaboratoriumLOG')->info("Response dari API LAB LIS " . $raw[0]->noorder ?? 'null' . ": " . json_encode($response));
                    if (!empty($response)) {
                        if ($response->status == 200) {
                            $transStatus = true;
                            StrukOrder::where('noorder', $raw[0]->noorder)->update([
                                'nolab_lis' => $response->data->nolab
                            ]);
                            Log::info("Response dari LAB : " . $response->data->nolab);
                        } else {
                            $transStatus = false;
                            $errorMessage = $response->message;
                            Log::error("Response gagal order " . $raw[0]->noorder ?? "" . " dari LAB : " . $errorMessage);
                            Log::channel('orderLaboratoriumLOG')->error("Response gagal order " . $raw[0]->noorder ?? "" . " dari LAB : " . $errorMessage);
                        }
                    } else {
                        $transStatus = false;
                        $errorMessage = $response->message;
                    }
                }
            }

            // DB::commit();
            // $result = array(
            //     "status" => 200,
            //     "message" => "Sukses",
            //     "data" => $newBRG,
            // );
        } catch (Exception $e) {
            // DB::rollBack();
            // $result = array(
            //     "status" => 400,
            //     "message" => "Something Went wrong",
            //     "data"  => $e->getMessage() . $e->getLine()
            // );
            $transStatus = false;
        }

        if ($transStatus) {
            DB::commit();
            $result = array(
                "status" => 200,
                "message" => "Sukses kirim ke LIS",
                "data" => $newBRG,
            );
        } else {
            DB::rollBack();
            $result = array(
                "status" => 400,
                "message" => "Gagal kirim ke LIS",
                "data"  => isset($e) ? $e->getMessage() . ' - ' . $e->getLine() : $errorMessage,
            );
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function getHasilLabBridgingNew(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
        $xid = $this->settingFix('xidPromlis', $kdProfile);
        $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
        $header = $this->generateHeaderPromLis($xid, $xkey);
        $arrNoorder = explode(',', $request['noorder']);
        $arrNoorderUniq = array_unique($arrNoorder);



        $hasilLab = [];
        foreach ($arrNoorderUniq as $value) {
            $strukOrder = DB::table('strukorder_t')->where('noorder', $value)->first();
            // if($strukOrder->objectruangantujuanfk == 200){
            //     // $url = $baseUrl . 'microbilogi-result?OrderNumber=L2411004917&PatientCode=00020631';
            //     $url = $baseUrl . 'microbilogi-result?OrderNumber=' . $value . '&PatientCode=' . $request['nocm'];
            // }else{
            //     $url = $baseUrl . 'get_results?OrderNumber=' . $value . '&PatientCode=' . $request['nocm'];
            // }
            // $method = 'GET';
            // $response = $this->curlSendLis($url, $header, $method, null);
            // Log::channel('laboratoriumLOG')->info('Response dari LAB untuk ' . $value . ': ' . json_encode($response->message ?? $response));
            // if (isset($response->status) && $response->status == 200) {
            //     $hasilLab[] = $response->data->ResultList;
            // }else{
            // }
            $hasilBridg = DB::table('RESULT_DATA')
                ->select(
                    'test_flag_critical as CriticalFlag',
                    'test_flag_sign as Flag',
                    'test_method as Method',
                    'is_header as IsHeader',
                    'test_group as OrderTestGroup',
                    'sequence as DispSequence',
                    'his_test_order as OrderTestId',
                    'test_name as TestName',
                    'greaterthan_value as CriticalValueMax',
                    'lessthan_value as CriticalValueMin',
                    'reference_value as RefRange',
                    'result as ResultValue',
                    'lis_test_id as TestCode',
                    'result_comment as TestComment',
                    'test_units_name as Unit',
                    'authorization_user as ValidateBy',
                    'authorization_date as ValidateOn',
                )
                ->where('his_reg_no', $value)
                ->orderByRaw('CAST(sequence AS INTEGER)')
                ->get();
            if(count($hasilBridg) > 0){
                $hasilLab[] = $hasilBridg;
            }
        }

        return $this->respond($hasilLab);
    }

    public function getHasilLabBridgingNewRegistrasi(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
        $xid = $this->settingFix('xidPromlis', $kdProfile);
        $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
        $header = $this->generateHeaderPromLis($xid, $xkey);
        $arrNoorder = explode(',', $request['noorder']);
        $arrNoorderUniq = array_unique($arrNoorder);

        $strukOrder = DB::table('strukorder_t')
            ->where('noregistrasi', $request['noregistrasi'])
            ->where('objectkelompoktransaksifk', 93)
            ->where('statusenabled', true)
            ->get();

        $hasilLab = [];
        foreach ($strukOrder as $value) {
            $url = $baseUrl . 'get_result?OrderNumber=' . $value->noorder . '&PatientCode=' . $request['nocm'];
            $method = 'GET';
            $response = $this->curlSendLis($url, $header, $method, null);
            if (isset($response) && $response->status == 200) {
                $hasilLab[] = $response->data->ResultList;
            } else {
                $hasilLab[] = DB::table('RESULT_DATA')
                    ->select(
                        'test_flag_critical as CriticalFlag',
                        'test_flag_sign as Flag',
                        'test_method as Method',
                        'sequence as DispSequence',
                        'his_test_order as OrderTestId',
                        'test_name as TestName',
                        'greaterthan_value as CriticalValueMax',
                        'lessthan_value as CriticalValueMin',
                        'reference_value as RefRange',
                        'result as ResultValue',
                        'lis_test_id as TestCode',
                        'result_comment as TestComment',
                        'test_units_name as Unit',
                        'authorization_user as ValidateBy',
                        'authorization_date as ValidateOn',
                    )
                    ->where('his_reg_no', $value->noorder)
                    ->orderByRaw('CAST(sequence AS INTEGER)')
                    ->get();
            }
        }

        return $this->respond($hasilLab);
    }

    public function cetakHasilLabNew(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $KodeJasaMedis      = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab    = $this->settingFix('KdKomponenTarifJasaDokter');

        // start generate parameter kebutuhan save dokumen
        if (isset($request['noregistrasi'])) {
            $registrasi = DB::table("pasiendaftar_t as pd")
                ->select("apd.norec")
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->leftJoin('hasillaboratorium_t as hh', 'hh.noregistrasifk', '=', 'apd.norec')
                ->where('pd.statusenabled', true)
                ->where('pd.kdprofile', $kdProfile)
                ->first();

            if (empty($registrasi)) {
                $request['norec_apd'] = "";
                $request['norec'] = "";
            } else {
                $request['norec_apd'] = $registrasi->norec;
                $request['norec'] = "";
            }
        }

        $dataOrderSim = DB::table('strukorder_t as so')
            ->leftjoin('order_lab as ol', 'ol.no_lab', 'so.noorder')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'pd.objectpegawaifk')
            ->select('so.noorder', 'ru.namaruangan as ruanganasal', 'pg.namalengkap as pegawaiorder', 'pg2.namalengkap as dpjp', 'ol.dok_jaga')
            ->where('so.kdprofile', $kdProfile)
            ->where('so.statusenabled', true)
            ->where('so.keteranganorder', 'Order Laboratorium');
        if(isset($request['noregistrasi'])){
            $dataOrderSim = $dataOrderSim->where('pd.noregistrasi', $request['noregistrasi']);
        }
        if (isset($request['noorder'])) {
            // Split the comma-separated string into an array
            $noorderArray = explode(',', $request['noorder']);
            // Use whereIn to filter by multiple values
            $dataOrderSim = $dataOrderSim->whereIn('so.noorder', $noorderArray);
            // $dataOrderSim = $dataOrderSim->where('so.noorder', $request['noorder']);
        }
        $dataOrderSim = $dataOrderSim->groupBy('so.noorder', 'ru.namaruangan', 'pg.namalengkap', 'pg2.namalengkap', 'ol.dok_jaga')->get();
        $arrayOrder = [];
        foreach ($dataOrderSim as $key => $value) {
            $arrayOrder[] = $value->noorder;
        }
        // $pdf = $arrayOrder;
        // return $pdf;

        // return $dataOrderSim;


        // end generate parameter kebutuhan save dokumen
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        $profile    = $this->profile();
        $data       = $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');

        $dataBrid = [];

        $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
        $xid = $this->settingFix('xidPromlis', $kdProfile);
        $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
        $header = $this->generateHeaderPromLis($xid, $xkey);
        $arrNoorder = explode(',', $request['noorder']);
        if(isset($request['noorder'])){
            $arrNoorderUniq = array_unique($arrNoorder);
        }else{
            $arrNoorderUniq = array_unique($arrayOrder);
        }
        foreach ($arrNoorderUniq as $value) {
            $hasilShare = DB::table('RESULT_DATA')
                ->select(
                    'test_flag_critical as CriticalFlag',
                    'test_flag_sign as Flag',
                    'test_method as Method',
                    'sequence as DispSequence',
                    'his_test_order as OrderTestId',
                    'test_name as TestName',
                    'greaterthan_value as CriticalValueMax',
                    'lessthan_value as CriticalValueMin',
                    'reference_value as RefRange',
                    'result as ResultValue',
                    'lis_test_id as TestCode',
                    'result_comment as TestComment',
                    'test_units_name as Unit',
                    'is_header as IsHeader',
                    'authorization_user as ValidateBy',
                    'authorization_date as ValidateOn',
                    'ORDERTIME as OrderDateTime',
                    'DIAGNOSA as DiagnosisName',
                    'LISREGNO as OrderNumberLIS',
                    'HISREGNO as OrderNumber',
                    'test_group as OrderTestGroup',
                    'CATATAN as Catatan'
                )
                ->join('RESULT_HEADER', 'HISREGNO', 'his_reg_no')
                ->where('his_reg_no', $value)
                ->orderByRaw('CAST(sequence AS INTEGER)')
                ->get();
            foreach ($dataOrderSim as $item) {
                if ($item->noorder == $value) {
                    foreach ($hasilShare as $value2) {
                        $dataBrid[] = [
                            'namaproduk' => $value2->OrderTestGroup ?? '',
                            'detailpemeriksaan' => $value2->TestName,
                            'tglorder' => $value2->OrderDateTime,
                            'catatanklinis' => $value2->DiagnosisName,
                            'hasil' => $value2->ResultValue,
                            'flag' => $value2->Flag,
                            'nilaitext' => $value2->RefRange,
                            'satuanstandar' => $value2->Unit,
                            'tgl_daftar' => $value2->OrderDateTime,
                            'no_lab' => $value2->OrderNumberLIS,
                            'user_validasi' => $value2->ValidateBy,
                            'tgl_hasil' => $value2->ValidateOn,
                            'metode' => $value2->Method,
                            'noorder' => $value2->OrderNumber,
                            'isheader' => $value2->IsHeader,
                            'ruanganasal' => $item->ruanganasal,
                            'dok_jaga' => $item->dok_jaga,
                            'dok_order' => $item->pegawaiorder,
                            'catatan' => $value2->Catatan,
                        ];
                    }
                }
            }
            // }
        }

        if (count($dataBrid) == 0) {
            return null;
        }

        $groupedData = [];

        foreach ($dataBrid as $item) {
            // Menyusun data ke dalam array berdasarkan nilai noorder
            $item['ttdePemeriksa'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['noorder'] . "\n" . $item['tglorder'] . "\n" . $item['user_validasi']));
            $item['ttdeDokter'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['noorder'] . "\n" . $item['tglorder'] . "\n" . $item['dok_jaga']));
            $noorder = $item['noorder'];
            if (!isset($groupedData[$noorder])) {
                $groupedData[$noorder] = [];
            }
            $groupedData[$noorder][] = $item;
        }

        foreach ($groupedData as $key => $value) {
            $groupHasil = array_reduce($value, function ($result, $item) {
                $result[$item['namaproduk']][] = $item;
                return $result;
            });
            $groupedData[$key] = $groupHasil;
        }

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($groupedData), FALSE),
        );
        $res['pdf']  = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab';
        if (isset($request['storage']) && $request['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                "report.laboratorium.hasil-lab-new",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'profile' => $profile,
                )
            );
            return $pdf;

        }
        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-new",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'profile' => $profile,
                )
            );
            return $pdf->stream();
        }
        return view(
            'report.laboratorium.hasil-lab-new',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function cetakHasilLabNewNonToken(Request $request)
    {
        $kdProfile          = $this->kdProfile;
        $KodeJasaMedis      = $this->settingFix('KdKomponenTarifJasaDokter');
        $kdDepartemenLab    = $this->settingFix('KdKomponenTarifJasaDokter');

        // start generate parameter kebutuhan save dokumen
        if (isset($request['noregistrasi'])) {
            $registrasi = DB::table("pasiendaftar_t as pd")
                ->select("apd.norec")
                ->join('antrianpasiendiperiksa_t as apd', 'apd.noregistrasifk', '=', 'pd.norec')
                ->leftJoin('hasillaboratorium_t as hh', 'hh.noregistrasifk', '=', 'apd.norec')
                ->where('pd.statusenabled', true)
                ->where('pd.kdprofile', $kdProfile)
                ->first();

            if (empty($registrasi)) {
                $request['norec_apd'] = "";
                $request['norec'] = "";
            } else {
                $request['norec_apd'] = $registrasi->norec;
                $request['norec'] = "";
            }
        }

        $dataOrderSim = DB::table('strukorder_t as so')
            ->leftjoin('order_lab as ol', 'ol.no_lab', 'so.noorder')
            ->leftjoin('pasiendaftar_t as pd', 'pd.norec', '=', 'so.noregistrasifk')
            ->leftjoin('ruangan_m as ru', 'ru.id', '=', 'so.objectruanganfk')
            ->leftjoin('pegawai_m as pg', 'pg.id', '=', 'so.objectpegawaiorderfk')
            ->leftjoin('pegawai_m as pg2', 'pg2.id', '=', 'pd.objectpegawaifk')
            ->select('so.noorder', 'ru.namaruangan as ruanganasal', 'pg.namalengkap as pegawaiorder', 'pg2.namalengkap as dpjp', 'ol.dok_jaga')
            ->where('so.kdprofile', $kdProfile)
            ->where('so.statusenabled', true)
            ->where('so.keteranganorder', 'Order Laboratorium');
        if(isset($request['noregistrasi'])){
            $dataOrderSim = $dataOrderSim->where('pd.noregistrasi', $request['noregistrasi']);
        }
        if (isset($request['noorder'])) {
            // Split the comma-separated string into an array
            $noorderArray = explode(',', $request['noorder']);
            // Use whereIn to filter by multiple values
            $dataOrderSim = $dataOrderSim->whereIn('so.noorder', $noorderArray);
            // $dataOrderSim = $dataOrderSim->where('so.noorder', $request['noorder']);
        }
        $dataOrderSim = $dataOrderSim->groupBy('so.noorder', 'ru.namaruangan', 'pg.namalengkap', 'pg2.namalengkap', 'ol.dok_jaga')->get();
        $arrayOrder = [];
        foreach ($dataOrderSim as $key => $value) {
            $arrayOrder[] = $value->noorder;
        }
        // $pdf = $arrayOrder;
        // return $pdf;

        // return $dataOrderSim;


        // end generate parameter kebutuhan save dokumen
        $noregistrasi = $request['noregistrasi'];
        $norec_apd = $request['norec_apd'];
        $user = $request['user'];
        $norecPp = '';
        $datNorec = explode('|', $request['norec']);
        foreach ($datNorec as $ob) {
            $norecPp = $norecPp . ",'" . $ob . "'";
        }
        $norecPp = substr($norecPp, 1, strlen($norecPp) - 1);
        $paramsPp = "";
        if ($norecPp != '') {
            $paramsPp = " AND tp.norec IN (" . $norecPp . ")";
        }
        $profile    = $this->profile();
        $data       = $this->indentitasCetak($kdProfile, $noregistrasi);

        $sDokterPemeriksa = $this->settingFix('jenisPetugasDokterPemeriksa');

        $dataBrid = [];

        $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
        $xid = $this->settingFix('xidPromlis', $kdProfile);
        $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
        $header = $this->generateHeaderPromLis($xid, $xkey);
        $arrNoorder = explode(',', $request['noorder']);
        if(isset($request['noorder'])){
            $arrNoorderUniq = array_unique($arrNoorder);
        }else{
            $arrNoorderUniq = array_unique($arrayOrder);
        }
        foreach ($arrNoorderUniq as $value) {
            $hasilShare = DB::table('RESULT_DATA')
                ->select(
                    'test_flag_critical as CriticalFlag',
                    'test_flag_sign as Flag',
                    'test_method as Method',
                    'sequence as DispSequence',
                    'his_test_order as OrderTestId',
                    'test_name as TestName',
                    'greaterthan_value as CriticalValueMax',
                    'lessthan_value as CriticalValueMin',
                    'reference_value as RefRange',
                    'result as ResultValue',
                    'lis_test_id as TestCode',
                    'result_comment as TestComment',
                    'test_units_name as Unit',
                    'is_header as IsHeader',
                    'authorization_user as ValidateBy',
                    'authorization_date as ValidateOn',
                    'ORDERTIME as OrderDateTime',
                    'DIAGNOSA as DiagnosisName',
                    'LISREGNO as OrderNumberLIS',
                    'HISREGNO as OrderNumber',
                    'test_group as OrderTestGroup',
                    'CATATAN as Catatan'
                )
                ->join('RESULT_HEADER', 'HISREGNO', 'his_reg_no')
                ->where('his_reg_no', $value)
                ->orderByRaw('CAST(sequence AS INTEGER)')
                ->get();
            foreach ($dataOrderSim as $item) {
                if ($item->noorder == $value) {
                    foreach ($hasilShare as $value2) {
                        $dataBrid[] = [
                            'namaproduk' => $value2->OrderTestGroup ?? '',
                            'detailpemeriksaan' => $value2->TestName,
                            'tglorder' => $value2->OrderDateTime,
                            'catatanklinis' => $value2->DiagnosisName,
                            'hasil' => $value2->ResultValue,
                            'flag' => $value2->Flag,
                            'nilaitext' => $value2->RefRange,
                            'satuanstandar' => $value2->Unit,
                            'tgl_daftar' => $value2->OrderDateTime,
                            'no_lab' => $value2->OrderNumberLIS,
                            'user_validasi' => $value2->ValidateBy,
                            'tgl_hasil' => $value2->ValidateOn,
                            'metode' => $value2->Method,
                            'noorder' => $value2->OrderNumber,
                            'isheader' => $value2->IsHeader,
                            'ruanganasal' => $item->ruanganasal,
                            'dok_jaga' => $item->dok_jaga,
                            'dok_order' => $item->pegawaiorder,
                            'catatan' => $value2->Catatan,
                        ];
                    }
                }
            }
            // }
        }

        if (count($dataBrid) == 0) {
            return null;
        }

        $groupedData = [];

        foreach ($dataBrid as $item) {
            // Menyusun data ke dalam array berdasarkan nilai noorder
            $item['ttdePemeriksa'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['noorder'] . "\n" . $item['tglorder'] . "\n" . $item['user_validasi']));
            $item['ttdeDokter'] = base64_encode(QrCode::format('svg')->size(75)->generate($item['noorder'] . "\n" . $item['tglorder'] . "\n" . $item['dok_jaga']));
            $noorder = $item['noorder'];
            if (!isset($groupedData[$noorder])) {
                $groupedData[$noorder] = [];
            }
            $groupedData[$noorder][] = $item;
        }

        foreach ($groupedData as $key => $value) {
            $groupHasil = array_reduce($value, function ($result, $item) {
                $result[$item['namaproduk']][] = $item;
                return $result;
            });
            $groupedData[$key] = $groupHasil;
        }

        $pageWidth = 950;
        $dataReport = array(
            'namaprofile' => $profile->namalengkap,
            'alamat' => $profile->namakota,
            'user' => $user,
            'judul' => "Hasil Laboratorium",
            'header' => $data,
            'details' => json_decode(json_encode($groupedData), FALSE),
        );
        $res['pdf']  = true;
        $judul = 'Cetak Hasil Lab';
        $blade = 'report.laboratorium.hasil-lab';
        if (isset($request['storage']) && $request['storage']) {
            $pdf = App::make('dompdf.wrapper');
            // $customPaper = array(0, 0, 210, 295);
            // $pdf->setPaper($customPaper);
            $pdf->loadView(
                "report.laboratorium.hasil-lab-new",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'profile' => $profile,
                )
            );
            return $pdf;

        }
        if ($res['pdf'] == true) {
            $pdf = App::make('dompdf.wrapper');
            $pdf->setpaper('a4', 'portrait');
            $pdf->loadView(
                "report.laboratorium.hasil-lab-new",
                array(
                    'dataReport' => $dataReport,
                    'pageWidth' => $pageWidth,
                    'res' => $res,
                    'profile' => $profile,
                )
            );
            return $pdf->stream("Hasil_Lab-{$dataReport['header']->namapasien}({$dataReport['header']->noregistrasi}).pdf");
        }
        return view(
            'report.laboratorium.hasil-lab-new',
            compact('dataReport', 'pageWidth', 'profile')
        );
    }

    public function generateHeaderPromLis($xid, $xkey)
    {
        $x_id = $xid;  // X-id value
        $x_key = $xkey;  // Your secret key (X-key)
        $x_timestamp = strval(time() - strtotime('1970-01-01 00:00:00')); // X-timestamp value (UNIX timestamp)

        // Combine the X-id and X-timestamp into a single string (the format depends on API requirements)
        $data_to_sign = $x_id . "&" . $x_timestamp;  // Assuming colon (:) separator

        // Generate the HMAC-SHA256 hash and encode it in base64
        $signature = base64_encode(hash_hmac('sha256', $data_to_sign, $x_key, true));

        $result = array(
            'X-id:' . $x_id,
            'X-timestamp:' . $x_timestamp,
            'X-signature:' . $signature,
            'Content-Type:application/json'
        );

        return $result;
    }

    public function curlSendLis($url, $header, $method, $dataJson)
    {
        $curl = curl_init();

        if ($dataJson != null) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_POSTFIELDS => $dataJson,
                CURLOPT_HTTPHEADER => $header
            ));
        } else {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $header,
            ));
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response);
    }

    function indentitasCetak($kdProfile, $noregistrasi)
    {
        $data = collect(DB::select("
            SELECT pd.noregistrasi,ps.nocm,ps.tgllahir,to_char(ps.tgllahir, 'DD-MM-YYYY') as tglkelahiran,ps.namapasien, ps.noidentitas, ps.nohp,ps.tempatlahir as tempatlahir , ng.namanegara,
                pd.tglregistrasi,jk.reportdisplay AS jk,ru2.namaruangan AS ruanganperiksa,ru.namaruangan AS ruangakhir,
                ks.namakelas,ar.asalrujukan,ps.notelepon,CASE WHEN rek.namarekanan is null then '-' else rek.namarekanan END as namapenjamin,
                CASE WHEN kmr.namakamar is null then '-' else kmr.namakamar END as namakamar,alm.alamatlengkap,kp.kelompokpasien,pp.namalengkap AS dpjp
            FROM pasiendaftar_t AS pd
            INNER JOIN pasien_m AS ps ON pd.nocmfk = ps.id
            INNER JOIN jeniskelamin_m AS jk ON ps.objectjeniskelaminfk = jk.id
            INNER JOIN kelompokpasien_m AS kp ON pd.objectkelompokpasienlastfk = kp.id
            INNER JOIN antrianpasiendiperiksa_t AS apdp ON apdp.noregistrasifk = pd.norec
            INNER JOIN ruangan_m AS ru ON apdp.objectruanganfk = ru.id
            LEFT JOIN pegawai_m AS pp ON apdp.objectpegawaifk = pp.id
            LEFT JOIN kelas_m AS ks ON apdp.objectkelasfk = ks.id
            LEFT JOIN asalrujukan_m AS ar ON apdp.objectasalrujukanfk = ar.id
            left JOIN rekanan_m AS rek ON rek.id= pd.objectrekananfk
            left JOIN kamar_m as kmr on apdp.objectkamarfk=kmr.id
            INNER join ruangan_m  as ru2 on ru2.id=apdp.objectruanganfk
            LEFT JOIN alamat_m as alm on alm.nocmfk = ps.id
            LEFT JOIN negara_m as ng on ng.id = ps.objectnegarafk
            WHERE pd.kdprofile = $kdProfile AND pd.statusenabled = true AND pd.noregistrasi = '$noregistrasi'
            "))->first();
        return $data;
    }

    public function kirimHasilLabWA(Request $request)
    {
        DB::beginTransaction();
        try {
            // }
            $dataidentitaspasien = $this->indentitasCetak($this->kdProfile, $request['noregistrasi']);
            if (!$dataidentitaspasien) {
                return $this->respond('', 400, "Data tidak ditemukan");
            }
            if (empty($dataidentitaspasien->nohp)) {
                return $this->respond('', 400, "No HP pasien belum ada");
            }

            // convert no hp
            $convertPhoneNumber = function ($phone) {
                if (strpos($phone, "0") === 0) {
                    return "62" . substr($phone, 1);
                }
                return $phone;
            };
            $convertedPhone = $convertPhoneNumber($dataidentitaspasien->nohp);
            $Link           = "https://rsdgunungjati.com/service/bridging/penunjang/cetakan-hasil-lab-new-non-token?noregistrasi=" . $request['noregistrasi'] .
                            "&noorder=" . $request['noorder'];
            $finalLink      = str_replace(' ', '%20', $Link);
            // $pesan = "Salam hormat, Berikut kami sampaikan hasil dari pemeriksaan radiologi tanggal {$dataidentitaspasien->tglregistrasi}, a/n pasien {$dataidentitaspasien->namapasien}\n" . $request['pesan'];
            $pesan = "Salam hormat,
    Berikut kami sampaikan hasil dari pemeriksaan laboratorium tanggal {$dataidentitaspasien->tglregistrasi}, a/n pasien {$dataidentitaspasien->namapasien}

    dapat dilihat dengan cara klik link berikut ini :

    {$finalLink}
    *Jika link di atas tidak bisa diklik, silahkan simpan nomor ini terlebih dahulu dan  atau forward pesan ini ke nomor sendiri !*
    Terima kasih,
    RSD Gunung Jati Kota Cirebon";
            $client = new Client();

            $dataWA = [
                'phone' => $convertedPhone,
                'isGroup' => false,
                'isNewsletter' => false,
                'message' => $pesan,
            ];


            $WAurl = $this->settingFix('APIWA_url');
            $WAauth = 'Bearer ' . $this->settingFix('APIWA_token');

            $response = $client->post($WAurl, [
                'headers' => [
                    'Authorization' => $WAauth,
                ],
                'json' => $dataWA,
            ]);

            $result = array(
                "status" => 201,
                "message" => "Kirim WA sukses",
                "data" => $dataWA,
            );
        } catch (Exception $e) {
            $result = array(
                "status" => 400,
                "message" => $e->getMessage() . " " . $e->getLine(),
                "data"  => $e->getMessage() . " " . $e->getLine()
            );
        }

        return $this->respond($result['data'], $result['status'], $result['message']);
    }

    public function getHasilLabBridgingMicrobiologi(Request $request)
    {
        $kdProfile = $this->kdProfile;

        $baseUrl = $this->settingFix('urlBridgingLISPromlis', $kdProfile);
        $xid = $this->settingFix('xidPromlis', $kdProfile);
        $xkey = $this->settingFix('xkeyPromlis', $kdProfile);
        $header = $this->generateHeaderPromLis($xid, $xkey);
        $arrNoorder = explode(',', $request['noorder']);
        $arrNoorderUniq = array_unique($arrNoorder);

        $hasilLab = [];
        foreach ($arrNoorderUniq as $value) {
            $url = $baseUrl . 'microbilogi-result?OrderNumber=' . $value . '&PatientCode=' . $request['nocm'];
            $method = 'GET';
            $response = $this->curlSendLis($url, $header, $method, null);
            if ($response->status == 200) {
                $hasilLab[] = $response->data->ResultList;
            }
        }

        return $this->respond($hasilLab);
    }
}
