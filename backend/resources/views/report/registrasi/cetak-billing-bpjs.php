<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>

    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), 'transmedika') !== false)
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
    @endif

</head>
<style type="text/css" media="print">
    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0;
        /* this affects the margin in the printer settings */
    }
</style>
<style>
    tr td {
        padding: 1px 2px 1px 2px;
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    body {
        font-family: Tahoma, Geneva, sans-serif;
    }

    @page {
        size: A4
    }

    .garis6 td {
        padding: 3px !important;
    }
</style>
@php
    if (isset($_GET['namafile'])) {
        header('Content-type: application/vnd-ms-excel');
        header('Content-Disposition: attachment; filename=' . $_GET['namafile'] . '.xls');
    }
    $noreg = $r['noregistrasi'];
    $d = App\Http\Controllers\Report\ReportController::getProfile();
@endphp


<body style="background-color: #CCCCCC">

    <div style="text-align:left">
        <table class="bayangprint" style="width:{{ $pageWidth }};background-color:#FFFFFF;padding:25px; border:0">
            <caption style="display: none"></caption>
<tr style="display: none"><th scope="col"></th></tr>
            <tbody>
                <tr>
                    <td>
                        <table style="width:100%;padding:0;border:0">
                            <caption style="display: none"></caption>
<tr style="display: none"><th scope="col"></th></tr>
                            <tr>
                                <td style="width:10%">
                                    <p style="text-align:left">
                                        <img alt="" src="{{ asset('service/img/logo_grandmed.png') }}"
                                            style="width: 80px;border:0" alt="">
                                    </p>
                                </td>
                                <td style="width:60%;padding-right:100px;">
                                    <span style="font-size: 12pt;font-weight: bold"
                                        color="#000000">{!! $d['namaprofile'] !!}</span>
                                    <br>
                                    <span style="font-size: 10pt;" color="#000000">
                                        {!! $d['alamat'] . '<br/>'.
                                         $d['email'] . ', ' . $d['website'] !!}</span>
                                </td>
                                <td style="text-align:center; width:30%">
                                    <span style="font-size: 10pt;" color="#000000">Priny by {{ $r['nama'] }}</span>
                                    <br>
                                    <span style="font-size: 10pt;" color="#000000">{{ date('d/m/Y H:i') }}</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        <table style="border-top:2px solid black;border-bottom:2px solid black; width:100%;padding:0">
                            <caption style="display: none"></caption>
<tr style="display: none"><th scope="col"></th></tr>
                            <tr>
                                <td style=" text-align: center">
                                    <span style="font-size: 13pt;font-weight: bold" color="#000000">DETAIL BIAYA
                                        PELAYANAN</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:20px">
                        <table style="width:100%;padding:0">
                            <caption style="display: none"></caption>
<tr style="display: none"><th scope="col"></th></tr>
                            <tr>
                                {{-- col 1 identitas --}}
                                <td style="width:12%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">No. Reg/Kunj</span></td>
                                <td style="width:25%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->noregistrasi . ' / ' . $identitas[0]->nokunjungan }}</span>
                                </td>

                                {{-- col 2 identitas --}}
                                <td style="width:13%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Unit</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->unit }}</span></td>

                                {{-- col 3 identitas --}}
                                <td style="width:10%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Tgl Masuk</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->tglregistrasi }}</span></td>
                            </tr>
                            <tr>
                                {{-- col 1 identitas --}}
                                <td style="width:12%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">No. RM</span></td>
                                <td style="width:25%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->nocm }}</span></td>

                                {{-- col 2 identitas --}}
                                <td style="width:13%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Kamar</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->namakamar }}</span></td>

                                {{-- col 3 identitas --}}
                                <td style="width:10%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Tgl Pulang</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->tglpulang }}</span></td>
                            </tr>
                            <tr>
                                {{-- col 1 identitas --}}
                                <td style="width:12%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Nama Pasien</span></td>
                                <td style="width:25%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->namapasienjk }}</span></td>

                                {{-- col 2 identitas --}}
                                <td style="width:13%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Kelas</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->namakelaspd }}</span></td>

                                {{-- col 3 identitas --}}
                                <td style="width:10%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Tipe</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->tipepasien }}</span></td>
                            </tr>
                            <tr>
                                @php
                                    $umur = App\Http\Controllers\Report\ReportController::getUmurna(
                                        $identitas[0]->tgllahir,
                                         $identitas[0]->tglregistrasi);
                                @endphp
                                {{-- col 1 identitas --}}
                                <td style="width:12%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Umur</span></td>
                                <td style="width:25%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $umur['umurtahun'] }}</span></td>

                                {{-- col 2 identitas --}}
                                <td style="width:13%"><span style="font-size: 8pt;font-weight: bold"
                                        color="#000000;">Dokter P.Jawab</span></td>
                                <td style="width: 20%"><span style="font-size: 8pt;" color="#000000">:
                                        {{ $identitas[0]->dokterpj }}</span></td>

                                {{-- col 3 identitas --}}
                                <td style="width:10%"><span style="font-size: 11pt;font-weight: bold"
                                        color="#000000;">Penjamin</span></td>
                                <td style="width: 20%"><span style="font-size: 11pt;" color="#000000">:
                                        {{ $identitas[0]->namarekanan }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:10px">
                        <table style="width:100%;padding:0">
                            <caption style="display: none"></caption>
                            <tr>
                                <th scope="col" style="text-align:left; border-top:1px solid black;border-bottom:1px solid black;">
                                    <span style="font-size: 8pt;" color="#000000">No</span>
                                </th>
                                 <th style="text-align:left; border-top:1px solid black;border-bottom:1px solid black;">
                                    <span style="font-size: 8pt;" color="#000000">Deskripsi</span>
                                </th>
                                <th
                                    style="text-align:center;
                                     border-top:1px solid black;border-bottom:1px solid black;">
                                    <span style="font-size: 8pt;" color="#000000">Qty</span>
                                </th>
                                <th
                                    style="text-align:right; border-top:1px solid black;border-bottom:1px solid black;">
                                    <span style="font-size: 8pt;" color="#000000">Harga (Rp)</span>
                                </th>
                                <th
                                    style="text-align:right; border-top:1px solid black;border-bottom:1px solid black;">
                                    <span style="font-size: 8pt;" color="#000000">Total (Rp)</span>
                                </th>
                            </tr>
                            @foreach ($jenistindakan as $jns)
                                @if ($ins->namadepartemen == $jns->namadepartemen)
                                    <tr>
                                        <td colspan="4"><span style="font-size: 11pt;"
                                                color="#000000">{{ strtoupper($jns->jenistindakan) }}</span></td>
                                        <td style="text-align: right"><span style="font-size: 11pt;font-weight:bold;"
                                                color="#000000">{{ number_format($jns->total, 2, '.', ',') }}</span>
                                        </td>
                                    </tr>
                                    @php
                                        if (strtoupper($jns->jenistindakan) == 'OBAT / ALKES') {
                                            $datana = App\Http\Controllers\Report\ReportController::getTagihanObat(
                                                $noreg, $ins->namadepartemen,
                                                strtoupper($jns->jenistindakan));
                                        } else {
                                            $datana = App\Http\Controllers\Report\ReportController::getTagihan(
                                                $noreg, $ins->namadepartemen,
                                                $jns->jenistindakan);
                                        }
                                    @endphp
                                    @php $nomor = 1; @endphp
                                    @foreach ($datana as $data)
                                        <tr>
                                            <td style="width:2%;text-align:center"><span style="font-size: 11pt;"
                                                    color="#000000">{{ $nomor }}</span></td>
                                              <td style="text-align: left; width:50%"><span style="font-size: 11pt;"
                                                color="#000000">{{ $data->namaproduklengkap }}</span></td>
                                            <td style="width:3%;text-align:center"><span style="font-size: 11pt;"
                                                    color="#000000">{{ $data->jumlah }}</span></td>
                                            <td style="text-align:right; width:10%"><span style="font-size: 11pt;"
                                                    color="#000000">{{ number_format($data->hargajual,
                                                     2, '.', ',') }}</span>
                                            </td>
                                            <td style="text-align:right; width:10%"><span style="font-size: 11pt;"
                                                    color="#000000">{{ number_format($data->total,
                                                     2, '.', ',') }}</span>
                                            </td>
                                        </tr>
                                        @php $nomor++; @endphp
                                    @endforeach
                                @endif
                            @endforeach
                            @endforeach
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:10px">
                        @php
                            $Totaltotal = App\Http\Controllers\Report\ReportController::getTotalTotalTagihan($noreg);
                            $Terbilang = App\Http\Controllers\Report\ReportController::terbilang(
                                $Totaltotal['jumlahbiaya']);
                        @endphp
                        <table style="width:100%;padding:0;border-top:2px solid black;">
                            <caption style="display: none"></caption>
<tr style="display: none"><th scope="col"></th></tr>
                            <tr>
                                <td style="width:25%"><span style="font-size: 11pt;font-weight:bold;"
                                        color="#000000">JUMLAH BIAYA</span></td>
                                <td style="text-align: right; width:75%"><span
                                        style="font-size: 11pt;font-weight:bold;" color="#000000">
                                        {{ number_format($Totaltotal['jumlahbiaya'], 2, '.', ',') }}</span></td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <span style="font-size: 11pt;font-weight:bold;" color="#000000">TERBILANG</span>
                                    <span style="font-size: 11pt;font-style:italic;"
                                        color="#000000">({{ strtoupper($Terbilang) }})</span>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:20px">
                        <table style="width:100%;padding:0">
                            <caption style="display: none"></caption>
<tr style="display: none"><th scope="col"></th></tr>
                            <tr>
                                <td style="width:50%; text-align:center"><span style="font-size: 11pt; color:#000000; text-align:center">
                                    </span></td>
                                <td style="width:50%; text-align:center"><span style="font-size: 11pt;"
                                        color="#000000">{!! $d['namakota'] !!}&nbsp;&nbsp;{{ date('d/m/Y') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:50%; text-align:center"><span style="font-size: 11pt;"
                                        color="#000000">Penanggung Biaya</span></td>
                                <td style="width:50%; text-align:center"><span style="font-size: 11pt;"
                                        color="#000000">Kasir</span></td>
                            </tr>
                            <tr>
                                <td  style="vertical-align:text-bottom;height:100;width:25%;text-align:center">
                                    <span style="font-size: 11pt;"
                                        color="#000000">{{ str_replace('( L )', '',
                                         str_replace('( P )', '',
                                         $identitas[0]->namapasienjk)) }}</span>
                                </td>
                                <td style="vertical-align:text-bottom;height:100;width:25%;text-align:center">
                                    <span style="font-size: 11pt;" color="#000000">{{ $r['nama'] }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.js"
    integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
        crossorigin="anonymous"></script>
</body>

</html>
