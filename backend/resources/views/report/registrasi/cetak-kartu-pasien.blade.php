<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        Report
    </title>

    <link href="css/style.css" rel="stylesheet">

    <link href="https://fonts.cdnfonts.com/css/3-of-9-barcode" rel="stylesheet">
</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: auto;
            margin: 0;
            /* size: portrait; */
        }

        footer {
            display: none
        }

        header {
            display: none
        }

        body {
            -webkit-print-color-adjust: exact !important;
        }
    }

    tr td {
        /*padding:2px 4px 2px 4px;*/
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    body {
        font-family: Tahoma, Geneva, sans-serif;
    }
</style>
{{-- --}}
@if(isset($res['pdf']) && $res['pdf'] == true)
<body style="margin: 0">
@else
    <body style="background-color: #CCCCCC;margin: 0" onLoad="window.print()">
@endif
    <div style="text-align:left">
        @if(isset($res['pdf']) && $res['pdf'] == true)
           <table style="background-color:#FFFFFF; padding-top:0; width:400px; border:0px">
            @else
            <table style="background-color:#FFFFFF; padding-top:15px; width:500px; border:0px">
            @endif
            <caption style="display: none"></caption>
            <tbody>
                <tr style="display: none">
                    <th scope="col"></th>
                </tr>
                <tr>
                    <td>
                        <table bgcolor="#FFFFFF" border="0" width="400">
                            <caption style="display: none"></caption>
                        @foreach($dataReport['data'] as $item)
                            <tr style="display: none">
                                <th scope="col"></th>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 20pt;font-weight: 800;"
                                        face="Tahoma">{{ $item->nocm }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <font style="font-size: 16pt;"
                                        face="Tahoma">{{ $item->namapasien }}
                                    </font>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="font-size: 14pt"
                                        face="Tahoma">{{ $item->tanggal_lahir }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 14pt"
                                        face="Tahoma">{{ $item->umur }}
                                    </span>
                                </td>
                                <td>
                                    <span style="font-size: 14pt;"
                                        face="Tahoma">{{ $item->jeniskelamin }}
                                    </span>
                                </td>
                            </tr>
                            {{-- <tr>
                                <td>
                                    <span style="font-size: 14pt;"
                                        face="Tahoma">{{ $item->kelompokpasien }}</span>
                                </td>
                            </tr> --}}
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 14pt"
                                        face="Tahoma">{{ $item->alamatlengkap }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 14pt"
                                        face="Tahoma">{{ $item->desa }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 14pt"
                                        face="Tahoma">{{ $item->kecamatan }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <span style="font-size: 14pt"
                                        face="Tahoma">{{ $item->kota }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <span style="font-size: 14pt;"
                                        face="Tahoma">{{ date('d/m/Y H:i:s', strtotime( $item->tglregistrasi )) }} </span>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <img src="https://bwipjs-api.metafloor.com/?bcid=code128&text={{ $item->barcode}}&scale=1&scaleY=1&scaleX=2" style="height: 50px"><br/>
                       
                                    {{-- <font style="font-size: 24pt;font-family: '3 of 9 Barcode', sans-serif;"
                                        face="Tahoma">{{$item->barcode}}
                                    </font> --}}
                                </td>
                            </tr>
                            <tr>
                                <td style="padding-top: 30px;">
                                    <span style="font-size: 14pt;font-weight:bold;"
                                        face="Tahoma"></span>
                                </td>
                            </tr>
                        @endforeach
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</body>

</html>
