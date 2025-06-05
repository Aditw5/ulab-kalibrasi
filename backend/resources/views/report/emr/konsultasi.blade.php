<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Konsultasi</title>

    <style>
        @page {
            padding: 0;
            margin-top: 3rem;
            margin-bottom: 3rem;
        }

        small {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .block {
            display: block;
        }

        table{
            margin-top: 1px;
        }

        .tebal {
            font-weight: bold
        }

        .inner-table,
        .inner-th,
        .inner-td {
            border: 1px solid black;
        }

        .inner-td {
            padding: 3px;
        }

        .normal {
            font-weight: normal;
            font-family: Arial;
        }

        .label-top {
            vertical-align: top;
        }

        .medium {
            font-size: 10.5pt;
        }

        hr {
            border: 0.5px solid black;
            margin: 1px;
        }

        .styled-pre {
            font-weight: normal;
            font-family: Arial;
            font-size: 10.5;
            color: black;
            display: unset;
        }
    </style>
</head>

<body>
    @foreach($raw as $index => $raws)
        <table style="border: 1px solid black;width:100%;border-collapse: collapse; @if($index !== 0) page-break-before: always; @endif">
            <thead>
                <tr>
                    <th width="60%" style="border: 1px solid black">
                        <table style="width:100%;border-collapse: collapse">
                            <tr>
                                <td width="20%" style="text-align: right">
                                    <img src="img/logo-rs.png" style="width: 50px; padding-left:5px">
                                </td>
                                <td>
                                    <table style="border-collapse: collapse;" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td style=" text-align: center;padding:0px">
                                                <small style="text-transform:uppercase;font-size:9pt;display: block;"
                                                    class="normal">Pemerintah Daerah
                                                    Kota Cirebon</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; padding:0px">
                                                <small style="text-transform:uppercase;font-size:9pt;display: block;"
                                                    class="normal">Dinas Kesehatan
                                                </small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; padding:0px">
                                                <small style="text-transform:uppercase;font-size:9pt;font-weight:bold;"
                                                    class="normal">Rumah
                                                    Sakit Daerah Gunung Jati</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; padding:0px">
                                                <small style="font-size:9pt;" class="normal">Jl. Kesambi No 56. Tlp(0231)
                                                    206330-202444,
                                                    Fax(0231)20633</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: center; padding:0px">
                                                <small style="font-size:9pt;" class="normal">Email :
                                                    rsudgunungjati@cirebonkota.go.id</small>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </th>
                    <th width="40%" rowspan="3"
                        style="border-top: 1px solid black;vertical-align:baseline;position:relative;">
                        <table style="margin-top:3px; padding:0px;position:relative;top:1.5%" width="100%">
                            <tr>
                                <td style="width:35%;">
                                    <span class="medium normal">Nomor RM</span>
                                </td>
                                <td style="width:2%;">
                                    <span class="medium normal">:</span>
                                </td>
                                {{-- {{dd($data['registrasi']['objectdepartemenfk'])}} --}}
                                <td>
                                    <span class="medium tebal">{{ $identitas->nocm }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="label-top">
                                    <span class="medium normal">Nama</span>
                                </td>
                                <td class="label-top">
                                    <span class="medium normal">:</span>
                                </td>
                                <td>
                                    <span class="medium tebal">{{ $identitas->namapasien }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="medium normal">Tanggal Lahir</span>
                                </td>
                                <td>
                                    <span class="medium normal">:</span>
                                </td>
                                <td>
                                    <span
                                        class="medium tebal">{{ \Carbon\Carbon::parse($identitas->tgllahir)->format('d-m-Y') }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="medium normal">Jenis Kelamin</span>
                                </td>
                                <td>
                                    <span class="medium normal">:</span>
                                </td>
                                <td>
                                    <span class="medium tebal">{{ $identitas->jeniskelamin }}</span>
                                </td>
                            </tr>
                        </table>
                    </th>
                </tr>
                <tr>
                    <th style="border: 1px solid black;text-align: center;position:relative;">
                        <span class=tebal
                            style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">KONSULTASI</span>
                    </th>
                </tr>
            </thead>
        </table>
        <table style="border: 1px solid black;width:100%;border-collapse: collapse; margin-top: -35px;">
            <tbody style="border: 1px solid black">
                <tr>
                    <td colspan="2">
                        {!!nl2br($raws->keteranganorder)!!}
                    </td>
                </tr>
                <tr>
                    <td style="width: 60%"></td>
                    <td style="text-align: center">
                        {{$raws->tglorder}} <br>
                        <img src="data:image/png;base64, {!! $raws->tteorder !!}"> <br>
                        {{$raws->namalengkap}} <br>
                    </td>
                </tr>
            </tbody>
        </table>
        <table style="border: 1px solid black;width:100%;border-collapse: collapse;">
            <tbody style="border: 1px solid black">
                <tr>
                    <td colspan="2">
                        <b>Jawaban Konsul</b> <br>
                        {!!nl2br($raws->keteranganlainnya)!!}
                    </td>
                </tr>
                <tr>
                    <td style="width: 60%"></td>
                    <td style="text-align: center">
                        {{$raws->tglmasuk}} <br>
                        <img src="data:image/png;base64, {!! $raws->ttejawab !!}"> <br>
                        {{$raws->dokter}} <br>
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach

</body>

</html>
