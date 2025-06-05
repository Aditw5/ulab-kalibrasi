<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
        <link rel="stylesheet" href="css/paper.css">
        <link rel="stylesheet" href="css/table-v2.css">
        <link rel="stylesheet" href="css/tabel.css">
    @else
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @endif
</head>
<style >
    body,
    td,
    th,
    span,
  
    p {
        font-family: Tahoma, Geneva, sans-serif !important;
        font-size: 11px;
    }
</style>


<body style="margin: 0">

    <table style="background-color:#FFFFFF; width:365px; border:0">
        <caption style="display: none"></caption>
        <tbody>
            <tr style="display: none">
                <th scope="col"></th>
            </tr>
            <tr>
                <td style="padding: 30px;text-align: left">
                    <span style="font-size: 12pt;" > <b>{{ $dataReport['namaprofile'] }}</b></span><br>
                    <span style="font-size: 10pt;" >{{ $dataReport['alamat'] }}</span>
                    <br>
                    <hr style="margin-top: 6px;" />
                    <span style="font-size: 12pt;" ><b>No Antrian :
                            {{ $dataReport['noantrian'] == null ? 'Belum tersedia untuk DEPO ini' : $dataReport['noantrian'] }}</b></span><br />
                    <hr />
                    <table style="border:0; width:100%; ">
                        <caption style="display: none"></caption>
                        <tr style="display: none">
                            <th scope="col"></th>
                        </tr>
                        <tr>
                            <td style="height:5px; width:30%">
                                <span style="font-size: 8pt;" >No RM</span>
                            </td>
                            <td style="height:5px; width:2%">
                                <span size="1">:</span>
                            </td>
                            <td style="height:5px; width:68%">
                                <span style="font-size: 8pt;" >
                                    {{ $dataReport['norm'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:5px; width:30%">
                                <span style="font-size: 8pt;" >Nama Pasien</span>
                            </td>
                            <td style="height:5px; width:2%">
                                <span size="1">:</span>
                            </td>
                            <td style="height:5">
                                <span style="font-size: 8pt;" >
                                    {{ $dataReport['namapasien'] }}/{{ $dataReport['jeniskelamin'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:5px; width:30%">
                                <span style="font-size: 8pt;" >Jenis Pasien</span>
                            </td>
                            <td style="height:5px; width:2%">
                                <span size="1">:</span>
                            </td>
                            <td style="height:5px; width:68%">
                                <span style="font-size: 8pt;" >
                                    {{ $dataReport['kelompokpasien'] }}</span>
                            </td>
                        </tr>

                        <tr>
                            <td style="height:5px; width:30%">
                                <span style="font-size: 8pt;" >Ruangan</span>
                            </td>
                            <td style="height:5px; width:2%">
                                <span size="1">:</span>
                            </td>
                            <td style="height:5px; width:68%">
                                <span style="font-size: 8pt;" >
                                    {{ $dataReport['ruangan'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height:5px; width:100%" colspan="3">
                                <hr />
                            </td>
                        
                          
                        </tr>
                        <tr>
                            <td style="text-align: center" colspan="3">
                                <span style="font-size: 8pt;" >
                                    {{ $dataReport['status'] }}</span>
                            </td>
                        </tr>
                      
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
