<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Resep Obat Bebas</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            --font: Arial, Helvetica, sans-serif;
        }
        .table-bordered tr td{
            border: 1px solid #444444;
        }
    </style>
</head>
<body>
    <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <th width="20%" style="text-align:left">
                    <img src="{{ 'img/logo-rs.png' }}" width="50px" border="0">
                </th>
                <td style="text-align: center" width="80%">
                    <table cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align: center">
                                <span style="text-transform: uppercase;font-weight:600; font-size: 10px">
                                    {{ strtoupper($profile->namaexternal) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">
                                <span style="text-transform: uppercase;font-weight:600; font-size: 10px">
                                    {!! $profile->alamatlengkap !!}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">
                                <span style="font-weight:300; font-size: 10px">
                                    Email : <a href="#"> {!! $profile->alamatemail !!} </a>
                                    Website : <a href="#"> {!! $profile->website !!} </a>
                                    Whatsapp : <a href="#"> {!! $profile->whatsapp !!} </a>
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td  colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>No CM</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->nocm}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>No Pendaftaran</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>-</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Nama Pasien</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->namapasien_klien}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Tanggal Pendaftaran</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span>-</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Tgl Lahir</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->tgllahir}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Jenis Pasien</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Alamat</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>-</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Penjamin</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span></span>
                            </td>
                        </tr>
                        <tr>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="15%">
                                <span>Ruangan</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="30%">
                                <span>{{$dataReport[0]->namaruangan}}</span>
                            </td>
                            <td style="font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="25%">
                                <span>Dokter</span>
                            </td>
                            <td style="font-weight:400;text-align: left;padding-bottom: 3px;font-size: 9pt;" width="5%">
                                <span>:</span>
                            </td>
                            <td style="1px solid black;font-weight:400;text-align:left;padding-bottom: 3px;font-size: 9pt;" width="20%">
                                <span>{{$dataReport[0]->namalengkap}}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="8"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td style="text-align: center" colspan="8">
                                <span style="font-size: 10pt;font-weight: 600;text-align: center" >NOTA / RESEP</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    No.Nama Barang
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Jenis\Satuan
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Asal Barang
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Jumlah
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                   Harga Satuan
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Total
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Plafon
                                </span>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Dibayar Pasen
                                </span>
                            </td>
                        </tr>
                        @php
                            $totalharga = 0;
                            $totaltagihan = 0;
                            $totalDiskon = 0;
                        @endphp
                        @foreach ($dataReport  as $key => $detail)
                        <tr>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    {{$loop->iteration}}.{{$detail->namaproduk}}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    {{$detail->detailjenisproduk}}\{{$detail->satuan}}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    -
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    {{$detail->qty}}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan) }}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan * $detail->qty) }}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->discount) }}
                                </span>
                            </td>
                            <td style="text-align: left;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                   Rp.{{ \App\Traits\Valet::getMoneyFormatString(($detail->hargasatuan * $detail->qty) - $detail->discount) }}
                                </span>
                            </td>
                        </tr>
                        @php
                            $totaltagihan   = $totaltagihan +($detail->hargasatuan * $detail->qty) - $detail->discount;
                            $totalDiskon    = $detail->discount;
                            $totalharga     = $totalharga + ($detail->hargasatuan * $detail->qty);
                        @endphp
                        @endforeach
                        <tr>
                            <td colspan="8">
                                <span style="font-size: 9pt;font-weight: 400;text-align: center;font-style: italic">
                                    Terbilang {{ \App\Traits\Valet::terbilang2($totaltagihan)}}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="5"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="49%">
                                <table width="100%" cellspacing="0" cellpadding="0" class="table-bordered">
                                    <tr>
                                        <td style="text-align: center" colspan="4">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Dikerjakan Oleh
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Entri Data
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Etiket Resep
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Mengemas
                                            </span>
                                        </td>
                                        <td style="text-align: center">
                                            <span style="font-size: 9pt;font-weight: 400;text-align: center" >
                                                Penyerahan
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                        <td height="20"></td>
                                        <td height="20"></td>
                                        <td height="20"></td>
                                    </tr>
                                </table>
                            </td>
                            <td width="2%">

                            </td>
                            <td width="49%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                TOTAL
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totalharga, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                PLAFON
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totalDiskon, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                SUBSIDI
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.0
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                SISA YANG HARUS DIBAYAR
                                            </span>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <span style="font-size: 8pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totaltagihan, 2, '.', ',') }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="1">
                        <tr>
                            <td width="60%">
                                <table style="border: 1px solid #444444" width="100%">
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                Keterangan:
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                Total Pemakaian Obat dan Alkes Samapai Dengan
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y')}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('H:i:s')}}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                               Rp.{{ \App\Traits\Valet::getMoneyFormatString($totaltagihan) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center;font-style: italic">
                                                Terbilang {{ \App\Traits\Valet::terbilang2($totaltagihan)}}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td with="2%"></td>
                            <td width="19%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: left">
                                                Penerima
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">

                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                nama jelas
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="19%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                Petugas
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">

                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span style="font-size: 8pt;font-weight: 400;text-align: center">
                                                nama jelas
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
</body>
</html>
