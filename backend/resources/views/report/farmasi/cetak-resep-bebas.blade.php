<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Cetak Resep Obat</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            font-family: 'Tahoma', 'sans-serif';
        }
        font{
            font-family: 'Tahoma', 'sans-serif';
        }
        @font-face {
            font-family: 'Tahoma','sans-serif';
        }
        .table-bordered tr td{
            border: 1px solid #444444;
        }
        .label-strong {
            text-align: center;
            font-size: 13.4pt;
        }

        .label-normal {
            font-weight:400;
            text-align:left;
            padding-bottom: 3px;
            font-size: 13.4pt;
        }

        .label-right {
            text-align: right;
            font-size: 13.4pt;
            font-weight: normal;
        }
        .label-left {
            text-align: left;
            font-size: 13.4pt;
            font-weight: normal;
        }
        body {
            font-family: Tahoma, sans-serif;
        }
    </style>
</head>
<body>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th>
                <img src="{{ 'img/logo-rs.png' }}" width="70px">
            </th>
            <th width="90%">
                <table width="100%"  style="left:-2rem;position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:center">
                            <font>{{ strtoupper($profile->namapemerintahan) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-strong" style="text-align:center">
                            <font>{{ strtoupper($profile->namalengkap) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" style="text-align:center">
                            <font>Jalan Kesambi No. 56 Telp.{{ $profile->fixedphone }} Fax.{{ $profile->faksimile }} Kode Pos {{ $profile->kodepos }}</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td  colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">No CM</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">{{$dataReport[0]->nocm}}</font>
                            </td>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">No Pendaftaran</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">-</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">Nama Pasien</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">{{$dataReport[0]->namapasien_klien}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Tanggal Pendaftaran</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">-</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">Tgl Lahir</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">{{\Carbon\Carbon::parse($dataReport[0]->tgllahir)->format('d-m-Y') ?? '' }}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Jenis Pasien</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">-</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">Alamat</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">{{$dataReport[0]->namatempattujuan}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Penjamin</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">-</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">Ruangan</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">{{$dataReport[0]->namaruangan}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Dokter</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">{{$dataReport[0]->namalengkap}}</font>
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
                                <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >NOTA / RESEP</font>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;" >
                                    No.Nama Barang
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Jenis\Satuan
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Asal Barang
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Jumlah
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                   Harga Satuan
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Total
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Plafon
                                </font>
                            </td>
                            <td style="text-align: center;border-top: 1px solid #444444;border-bottom: 1px solid #444444;padding: 3px 0 3px 0;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Dibayar Pasen
                                </font>
                            </td>
                        </tr>
                       @php
                            $totalharga = 0;
                            $totaltagihan = 0;
                            $totalDiskon = 0;
                            $maxRacikan = 0;
                            $iterasi = 1;
                        @endphp
                        @foreach ($dataReport  as $key => $detail)
                            @if ($detail->objectjeniskemasanfk === 1)
                                @if($maxRacikan < $detail->resepke)
                                    @php
                                        $maxRacikan = $detail->resepke;
                                    @endphp
                                @endif
                            @endif
                        <tr>
                            <td style="text-align: left;padding-top:3px" width="25%">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    {{$loop->iteration}}.{{$detail->namaproduk}}
                                </font>
                            </td>
                            <td style="text-align: center;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    {{$detail->detailjenisproduk}}\{{$detail->satuan}}
                                </font>
                            </td>
                            <td style="text-align: center;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    -
                                </font>
                            </td>
                            <td style="text-align: center;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    {{$detail->qty}}
                                </font>
                            </td>
                            <td style="text-align: left;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan) }}
                                </font>
                            </td>
                            <td style="text-align: left;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan * $detail->qty) }}
                                </font>
                            </td>
                            <td style="text-align: left;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString($detail->discount) }}
                                </font>
                            </td>
                            <td style="text-align: left;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString(($detail->hargasatuan * $detail->qty) - $detail->discount) }}
                                </font>
                            </td>
                        </tr>
                        @php
                            $totaltagihan   = $totaltagihan +($detail->hargasatuan * $detail->qty) - $detail->discount;
                            $totalDiskon    = $detail->discount;
                            $totalharga     = $totalharga + ($detail->hargasatuan * $detail->qty);
                            $iterasi++;
                        @endphp
                        @endforeach
                        @if ($maxRacikan > 0)
                        {{count($dataReport)}}
                        <tr>
                            <td style="text-align: left;padding-top:3px" width="25%">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    {{$iterasi}}.JASA RACIKAN
                                </font>
                            </td>
                            <td style="text-align: center;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >

                                </font>
                            </td>
                            <td style="text-align: center;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    -
                                </font>
                            </td>
                            <td style="text-align: center;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    {{$maxRacikan}}
                                </font>
                            </td>
                            <td style="text-align: left;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString(11000) }}
                                </font>
                            </td>
                            <td style="text-align: left;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString(11000 * $maxRacikan) }}
                                </font>
                            </td>
                            <td style="text-align: left;" >
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString(0) }}
                                </font>
                            </td>
                            <td style="text-align: left;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                    Rp.{{ \App\Traits\Valet::getMoneyFormatString(11000 * $maxRacikan) }}
                                </font>
                            </td>
                        </tr>
                        @php
                            $totaltagihan   = $totaltagihan + (11000 * $maxRacikan);
                            $totalharga     = $totalharga + (11000 * $maxRacikan);
                        @endphp
                        @endif
                        <tr>
                            <td style="padding-top:5px"></td>
                        </tr>
                        <tr>
                            <td colspan="8" style="border-top: 1px solid #444444;padding: 3px 0 3px 0;">
                                <font style="font-size: 13.4pt;font-weight: 400;text-align: center;font-style: italic">
                                    Terbilang {{ \App\Traits\Valet::terbilang2($totaltagihan)}}
                                </font>
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
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                                Dikerjakan Oleh
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center">
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                                Entri Data
                                            </font>
                                        </td>
                                        <td style="text-align: center">
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                                Etiket Resep
                                            </font>
                                        </td>
                                        <td style="text-align: center">
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                                Mengemas
                                            </font>
                                        </td>
                                        <td style="text-align: center">
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                                                Penyerahan
                                            </font>
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
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                TOTAL
                                            </font>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totalharga, 2, '.', ',') }}
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                PLAFON
                                            </font>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totalDiskon, 2, '.', ',') }}
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                SUBSIDI
                                            </font>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                Rp.0
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left" width="70%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                SISA YANG HARUS DIBAYAR
                                            </font>
                                        </td>
                                        <td style="text-align: right" width="30%">
                                            <font style="font-size: 13.4pt;font-weight: 600;text-align: center" >
                                                Rp.{{ number_format($totaltagihan, 2, '.', ',') }}
                                            </font>
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
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                Keterangan:
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                Total Pemakaian Obat dan Alkes Samapai Dengan
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('d-M-Y')}}
                                            </font>
                                        </td>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::now())->format('H:i:s')}}
                                            </font>
                                        </td>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                Rp.{{ \App\Traits\Valet::getMoneyFormatString($totaltagihan) }}
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center;font-style: italic">
                                                Terbilang {{ \App\Traits\Valet::terbilang2($totaltagihan)}}
                                            </font>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td with="2%"></td>
                            <td width="19%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: left">
                                                Penerima
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">

                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">

                                            </font>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td width="19%">
                                <table width="100%" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                Petugas
                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td height="20"></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">

                                            </font>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                                {{$user}}
                                            </font>
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
