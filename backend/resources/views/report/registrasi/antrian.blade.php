<html>
<head>
    <title>
        Report
    </title>
    <link href="css/style.css" rel="stylesheet">
</head>
<style type="text/css" media="print">
    @media print
    {
        @page
        {
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
    .borderss{
        border-bottom: 1px solid black;
    }
    body{
        font-family: Tahoma, Geneva, sans-serif;
    }
</style>
{{----}}
<body style="background-color: #CCCCCC;margin: 0" onload="window.print()">
    <div align="left">
        <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="365">
            <tbody>
            <tr>
                <td style="text-align: left">
                    <font style="font-size: 11pt;" color="#000000" face="Tahoma"><b>Loket Pendaftaran di Lt. {{ $dataReport['loket'] }}</b></font><br>
                    <font style="font-size: 11pt;" color="#000000" face="Tahoma">{{$dataReport['namaprofile']}}</font><br>
                    <font style="font-size: 10pt;" color="#000000" face="Tahoma">{{$dataReport['alamat']}}</font><br>
                    <table  cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0">
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 8pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 16pt;text-align: left;" face="Tahoma">Nomor Antrian</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="40" style="text-align: center">
                                <font style="font-size: 50pt;text-align: left;" face="Tahoma"><b>{{$dataReport['noantrian']}}</b></font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{$dataReport['jmlantrian']}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;font-weight:bold;" face="Tahoma">{{ strtoupper($dataReport['namaruangan']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="15" style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">No. CM : {{ strtoupper($dataReport['nocm']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="15" style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{ strtoupper($dataReport['namapasien']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td height="15" style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{$dataReport['nobpjs']}}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 10pt;text-align: left;" face="Tahoma">{{ date_format(date_create($dataReport['tanggal']), "d/m/Y H:i:s") }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">Pelayanan obat di</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">{{ strtoupper($dataReport['depo']) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: left">
                                <font style="font-size: 12pt;text-align: left;" face="Tahoma">&nbsp;</font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12t;text-align: center;" face="Tahoma">
                                Datanglah sesuai dengan jadwal kontrol<br>
                                Pastikan Tepat Tanggal dan Tepat Jam
                                </font>
                            </td>
                        </tr>
                        <tr>
                            <td height="25" style="text-align: center">
                                <font style="font-size: 14pt;text-align: left;" face="Tahoma"><b>{{$dataReport['jenis']}}</b></font>
                            </td>
                        </tr>
                        <tr>
                            <td  style="text-align: center">
                                <font style="font-size: 12t;text-align: center;" face="Tahoma">
                                Pastikan rujukan BPJS masih BERLAKU
                                </font>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    </body>
</html>
