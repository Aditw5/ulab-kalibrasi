<html>

<head>
    <title>
        Tanda Bukti Pembayaran
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
        size: 215 mm x 140 mm landscape;
        margin: 0;
    }
</style>
<style>
    tr td {
        padding: 2px 4px 2px 4px;
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
    // $noreg = $r['noregistrasi'];

@endphp
<!-- onload="window.print()" -->

<body style="background-color: #CCCCCC" onLoad="window.print()">

    <table border="1px" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th width="25%">
                    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                        <img src="{{ 'img/logo-rs.png' }}" width="65px" border="0">
                    @else
                        <img src="{{ asset('img/logo-rs.png') }}" width="65px" border="0">
                    @endif
                </th>
                <th>
                    <table style="border-collapse: collapse">
                        <tr>
                            <td style="text-align: center;padding:0px">
                                <span style="font-weight: 700;text-transform:uppercase">Pemerintah Kota Cirebon</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding:0px">
                                <span style="font-weight: 700;text-transform:uppercase">Rumah Sakit Umum Daerah Gunung Jati</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding:0px">
                                <span>Jl. Kesambi No 56. Tlp(0231) 20394-29394,
                                    Fax(0231)205336</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center; padding:0px">
                                <span style="font-weight: 700;text-transform:uppercase">Cirebon</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </thead>
    </table>
</body>

</html>
