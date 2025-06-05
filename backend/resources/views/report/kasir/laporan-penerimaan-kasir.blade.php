<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rekap Penerimaan Kasir</title>
</head>

<body>
    <style type="text/css">
        body {
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            font-size: 12px;
        }

        p {
            font-size: 13px;
        }

        .custom-table thead {
            background-color: #e1e1e1;
        }

        .custom-table tr>th,
        .custom-table tr>td {
            border: 1px solid #ccc;
            box-shadow: none;
            padding: 5px;
        }

        .custom-table-no-border thead {
            background-color: #e1e1e1;
        }

        .custom-table-no-border tr>th,
        .custom-table-no-border tr>td {
            box-shadow: none;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .top-table {
            margin-bottom: 10px;
        }

        .top-table tr>td {
            padding: 3px 10px;
        }
    </style>

    <div style="text-align:center">
        <h3>REKAP PENERIMAAN KASIR  {{$profile->namalengkap}}
            <br>TANGGAL : {{ $tglAwal . ' - ' . $tglAkhir }}
        </h3>
    </div>

    <br>

    <table class="custom-table" style="width: 100%">
        <caption style="display: none"></caption>
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Kasir</th>
                <th colspan="2">Total Biaya (Rp)</th>
                <th scope="col">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $temp = 0;
            @endphp
            @foreach ($data as $d)
                <tr>
                    <?php $temp = $temp + $d->totalpenerimaan; ?>
                    <td style=" text-align: center">{{ $i++ }}</td>
                    <td>{{ $d->namapenerima }}</td>
                    <td colspan="2" style="text-align:right">{{ number_format($d->totalpenerimaan, 2, ',', '.') }}</td>
                    <td>{{ $d->keterangan }}</td>
                </tr>
            @endforeach
            <tr>
                <td></td>
                <td></td>
                <td style="text-align:left; border-right:none">Rp.</td>
                <td style="text-align:right; border-left:none">{{ number_format($temp, 2, ',', '.') }}</td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <br>
    <table class="custom-table-no-border" style="width: 100%;">
        <caption style="display: none"></caption>
        <tr style="display: none">
            <th scope="col"></th>
        </tr>
        <tr>
            <td colspan="5"><b>Terbilang : "<i>{{ ucwords($terbilang) }} Rupiah."</i></b></td>
        </tr>
        <tr>
            <td style="visibility:hidden">2</td>
            <td style="visibility:hidden">2</td>
            <td></td>
            <td style="visibility:hidden">2</td>
            <td></td>
        </tr>
        <tr>
            <td><b>PEMBUAT LAPORAN</b></td>
            <td></td>
            <td></td>
            <td style="visibility:hidden">-</td>
            <td><b>KASIR BJB</b></td>
        </tr>
        <tr>
            <td style="visibility:hidden">2</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">3</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">4</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td><b>..............................</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td><b>..............................</b></td>
        </tr>
        <tr>
            <td style="visibility:hidden">5</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>KA. INSTALASI</b></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">6</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">7</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">8</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>..............................</b></td>
            <td></td>
            <td></td>
        </tr>
    </table>
</body>

</html>
