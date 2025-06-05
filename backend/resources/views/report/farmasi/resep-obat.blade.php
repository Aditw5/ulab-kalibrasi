<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
         Resep Obat</title>
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

    @php
        $profile = App\Http\Controllers\Controller::static_profile();
        $index = 0;
    @endphp

@foreach ($dataReport as $dataReports)
    @php
        $index++;
    @endphp
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
            @if( \Carbon\Carbon::parse(\Carbon\Carbon::parse($dataReports->tglresep))->format('d-M-Y') !=  \Carbon\Carbon::parse(\Carbon\Carbon::parse($dataReports->tglorder))->format('d-M-Y') && $dataReports->isiter)
            <th>
                RESEP ITER
            </th>
            @endif
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
                            <td class="label-normal" width="3%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="50%">
                                <font style="font-size:18pt">{{$dataReports->nocm}}</font>
                            </td>
                            <td class="label-normal" width="15%">
                                <font style="font-size:13pt">No Pendaftaran</font>
                            </td>
                            <td class="label-normal" width="2%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="30%">
                                <font style="font-size:13pt">{{$dataReports->noregistrasi}}</font>
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
                                <font style="font-size:18pt">{{$dataReports->namapasienjk}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Tanggal Pendaftaran</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">{{$dataReports->tglregistrasi}}</font>
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
                                <font style="font-size:13pt">{{$dataReports->tgllahir}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Ruangan Pengorder</font>
                            </td>
                            <td class="label-normal" width="2%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">{{$dataReports->ruanganpengorder}}</font>
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
                                <font style="font-size:13pt">{{$dataReports->alamat}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Jenis Pasien</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">{{$dataReports->kelopokpasien}}</font>
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
                                <font style="font-size:13pt">{{$dataReports->namaruangan}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Penjamin</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">{{$dataReports->penjamin}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">Dokter</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt">{{$dataReports->namalengkap}}</font>
                            </td>
                            <td class="label-normal" width="25%">
                                <font style="font-size:13pt">No SEP</font>
                            </td>
                            <td class="label-normal" width="5%">
                                <font style="font-size:13pt">:</font>
                            </td>
                            <td class="label-normal" width="20%">
                                <font style="font-size:13pt"><b>{{$dataReports->nosep}}</b></font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
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
        @endphp
        @foreach ($dataReports->detail as $detail)
        <tr>
            <td style="text-align: left;padding-top:3px" width="25%">
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                    {{$loop->iteration}}.{{$detail->namaprodukstandar}}
                    @if($detail->iskronis)
                        <b>(Obat Kronis)</b>
                    @endif
                    @if($detail->isreseppulang)
                        <b>(Obat Pulang)</b>
                    @endif
                </font>
            </td>
            <td style="text-align: center;">
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                    {{$detail->detailjenisproduk}}\{{$detail->satuanstandar}}
                </font>
            </td>
            <td style="text-align: center;" >
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                    {{$detail->asalproduk}}
                </font>
            </td>
            <td style="text-align: center;">
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                    {{$detail->jumlah}}
                </font>
            </td>
            <td style="text-align: left;" >
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                    {{  ($detail->hargasatuan > 0) ? "Rp." . \App\Traits\Valet::getMoneyFormatString($detail->hargasatuan) : "" }}
                </font>
            </td>
            <td style="text-align: left;" >
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                    {{ ($detail->totalharga > 0) ? "Rp." .  \App\Traits\Valet::getMoneyFormatString($detail->totalharga) : "" }}
                </font>
            </td>
            <td style="text-align: left;" >
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                   {{ ($detail->totalplatofon > 0) ? "Rp." .  \App\Traits\Valet::getMoneyFormatString($detail->totalplatofon)  :"" }}
                </font>
            </td>
            <td style="text-align: left;">
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center" >
                   {{ ($detail->totalbiaya > 0) ? "Rp." . \App\Traits\Valet::getMoneyFormatString($detail->totalbiaya) : ""}}
                </font>
            </td>
        </tr>
        @php
            $totaltagihan   = $totaltagihan + $detail->totalbiaya;
            $totalDiskon    = $totalDiskon + $detail->totalplatofon;
            $totalharga     = $totalharga + $detail->totalharga;
        @endphp
        @endforeach
        <tr>
            <td style="padding-top:5px"></td>
        </tr>
        <tr>
            <td colspan="8" style="border-top: 1px solid #444444;padding: 3px 0 3px 0;">
                <font style="font-size: 13.4pt;font-weight: 400;text-align: center;font-style: italic">
                    Terbilang {{ \App\Traits\Valet::terbilang2($totalharga)}}
                </font>
            </td>
        </tr>
    </table>

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
                            <font style="font-size: 13.5pt;font-weight: 600;text-align: center" >
                                TOTAL
                            </font>
                        </td>
                        <td style="text-align: right" width="30%">
                            <font style="font-size: 18pt;font-weight: 600;text-align: center" >
                                Rp.{{ number_format($totalharga, 2, ',', '.') }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="70%">
                            <font style="font-size: 13.5pt;font-weight: 600;text-align: center" >
                                PLAFON
                            </font>
                        </td>
                        <td style="text-align: right" width="30%">
                            <font style="font-size: 18pt;font-weight: 600;text-align: center" >
                                Rp.{{ number_format($totalDiskon, 2, ',', '.') }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="70%">
                            <font style="font-size: 13.5pt;font-weight: 600;text-align: center" >
                                SUBSIDI
                            </font>
                        </td>
                        <td style="text-align: right" width="30%">
                            <font style="font-size: 18pt;font-weight: 600;text-align: center" >
                                Rp.0
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: left" width="70%">
                            <font style="font-size: 13.5pt;font-weight: 600;text-align: center" >
                                SISA YANG HARUS DIBAYAR
                            </font>
                        </td>
                        <td style="text-align: right" width="30%">
                            <font style="font-size: 18pt;font-weight: 600;text-align: center" >
                                Rp.{{ number_format($totaltagihan, 2, ',', '.') }}
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="1" style="{{$index != count($dataReport) && count($dataReport) > 0 ? 'page-break-after: always':''}}">
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
                                Total Pemakaian Obat dan Alkes Sampai Dengan
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::parse($dataReports->tglresep))->format('d-M-Y')}}
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                {{ \Carbon\Carbon::parse(\Carbon\Carbon::parse($dataReports->tglresep))->format('H:i:s')}}
                            </font>
                        </td>
                        <td>
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                               Rp.{{ \App\Traits\Valet::getMoneyFormatString($totalharga) }}
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center;font-style: italic">
                                Terbilang {{ \App\Traits\Valet::terbilang2($totalharga)}}
                            </font>
                        </td>
                    </tr>
                </table>
            </td>
            <td with="2%"></td>
            <td width="19%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            {{-- <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                Dokter Pengorder
                            </font> --}}
                            <span style="font-size: 9pt"><b>Penerima</b></span>
                        </td>
                    </tr><br>
                    <tr>
                        <td align="center">
                            <img src="data:image/png;base64, {!!$dataReports->ttdePasien !!}"><br>
                            <br>
                            <span style="font-size: 9pt"><b>{{ '( ' .$dataReports->namapasienjk . ' )' }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                {{isset($dataReport['detail'][0]->pengambil) ? $dataReport['detail'][0]->pengambil : "-" }}
                            </font>
                        </td>
                    </tr> --}}
                </table>
            </td>
            <td width="19%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            {{-- <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                Dokter Pengorder
                            </font> --}}
                            <span style="font-size: 9pt"><b>Petugas</b></span>
                        </td>
                    </tr><br>
                    {{-- <tr>
                        <td height="20"></td>
                    </tr> --}}
                    <tr>
                        <td align="center">
                            <img src="data:image/png;base64, {!!$dataReports->ttdePetugas !!}"><br>
                            <br>
                            <span style="font-size: 9pt"><b>{{ '( ' .$dataReports->petugas . ' )' }}
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                {{isset($user) ? $user: ''}}
                            </font>
                        </td>
                    </tr> --}}
                </table>
            </td>
            <td width="19%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center">
                            {{-- <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                Dokter Pengorder
                            </font> --}}
                            <span style="font-size: 9pt"><b>Dokter Pengorder</b></span>
                        </td>
                    </tr><br>
                    {{-- <tr>
                        <td height="20"></td>
                    </tr> --}}
                    <tr>
                        <td align="center">
                            <img src="data:image/png;base64, {!!$dataReports->ttde !!}"><br>
                            <br>
                            <span style="font-size: 9pt"><b>{{ '( ' .$dataReports->namalengkap . ' )' }}</b></span>
                            <br>
                            <span style="font-size: 9pt">No SIP.{{  $dataReports->nosip }}</span>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td>
                            <font style="font-size: 13.4pt;font-weight: 400;text-align: center">
                                {{isset($user) ? $user: ''}}
                            </font>
                        </td>
                    </tr> --}}
                </table>
            </td>
        </tr>
    </table>
@endforeach
</body>
</html>
