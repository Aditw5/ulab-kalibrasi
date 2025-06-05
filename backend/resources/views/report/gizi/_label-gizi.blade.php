<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetak Label Gizi</title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/paper.css">
        <link rel="stylesheet" href="css/table-v2.css">
        <link rel="stylesheet" href="css/tabel.css">
    @else
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @endif
    <style>
        .container{
            width: 800px;
            border: 1px solid #29272700;
        }
    </style>
</head>
<body>
    <div  class="container">
        <table class="receipt" style="background-color:#FFFFFF;padding:10px; border:0">
            <thead>
                <tr>
                    <th colspan="3" style="text-align: center; font-size:12pt;">
                        <span style="text-transform:uppercase">Instalasi Gizi</span>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center; font-size:12pt;" colspan="3">
                        <span style="text-transform:uppercase">RSD Gunung Jati Kota Cirebon</span>
                    </th>
                </tr>
                <tr>
                    <th colspan="3">
                        <hr style="padding: 0px;margin:0px;">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="100%" height="5px" colspan="3"></td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="15%">
                        <span>NoCm/Registrasi</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="80%">
                        <span style="text-transform:uppercase;">: {{$data->nocm}} / {{$data->noregistrasi}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="15%">
                        <span>Nama</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{$data->nama}}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="15%">
                        <span>Tanggal Lahir</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="10%">
                        <span>: {{date("d/m/Y", strtotime($data->tgllahir))}}</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="10%">
                        <span></span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="15%">
                        <span>Penjamin</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{$data->kelompokpasien}} / {{$data->Penjamin}}
                    </td>
                </tr>
                <tr>
                    <th colspan="3">
                        <hr style="padding: 0px;margin:0px;">
                    </th>
                </tr>                
                <tr>
                    <td style="text-align: left"  width="19%">
                        <span>Ruangan</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{$data->ruangan}} / {{$data->kelas}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="19%">
                        <span>Kamar</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{ $data->kamar }} / {{ $data->bed }}</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="19%">
                        <span>Alergi</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: </span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="19%">
                        <span>Kategori Diet</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{$data->kategorydiet}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left"  width="19%">
                        <span>Jenis Diet</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{$data->arrjenisdiet != null ? $data->arrjenisdiet : '-'}}</span>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; margin-bottom:5px;"  width="19%">
                        <span>Keterangan</span>
                    </td>
                    <td style="text-align: left;font-weight: 600"  width="69%">
                        <span>: {{$data->keterangan}}</span>
                    </td>
                </tr>
                <tr>
                    <th colspan="3">
                        <hr style="padding: 0px;margin:0px;">
                    </th>
                </tr>
                <tr>
                    <td colspan="3" style="text-align: center;font-weight: 600; margin-top:5px" width="100%" >
                        <span>
                            {{date("d/m/Y", strtotime(now()))}}  &nbsp; - &nbsp; Makan {{$data->waktu}}<br>
                            Selamat Makan
                        </span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>

