<html lang="en">

<head>
    <title>
        Report
    </title>

</head>

<style>

body{
    font-family: Arial, Helvetica, sans-serif
}
</style>

<body style="margin: 0;padding:0">
  
        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" width="230">
            <tbody>
                <tr>
                    <td style="padding: 5px;text-align: left">
                        <span style="font-size: 12pt;" color="#000000">
                            <b>{{ $dataReport['namaprofile'] }}</b></span><br>
                        <span style="font-size: 10pt;" color="#000000">{{ $dataReport['alamat'] }}</span>
                        <br>
                        <hr style="margin-top: 6px;" />
                        <span style="font-size: 12pt;" color="#000000"><b>No Antrian :
                                {{ $dataReport['noantrian'] }}</b></span><br />
                        <hr />
                        <table cellspacing="0" cellpadding="0" border="0" width="100%" align="center">
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Tgl Registrasi</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['tglregistrasi'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Noregistrasi</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['noregistrasi'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">No Rm</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['norm'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Nama Pasien</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['namapasien'] }}/{{ $dataReport['jeniskelamin'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Jenis Pasien</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['kelompokpasien'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Status Pasien</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['statuspasien'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Ruangan</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['ruangan'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Tgl Reservasi</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['tanggalreservasi'] }}</span>
                                </td>
                            </tr>
                            <tr>
                                <td height="5" width="30%">
                                    <span style="font-size: 8pt;" color="#000000">Petugas</span>
                                </td>
                                <td height="5" width="2%">
                                    <span size="1">:</span>
                                </td>
                                <td height="5" width="68%">
                                    <span style="font-size: 8pt;" color="#000000">
                                        {{ $dataReport['namadokter'] }}</span>
                                </td>
                            </tr>
                            <tr >
                                <td  width="100%" colspan="3">
                                <table ccellspacing="0" cellpadding="0" border="0" width="100%">
                                    <tr>
                                        <td style="text-align: center">
                                            <hr />
                                            <span style="font-size: 8pt;" color="#000000">
                                                {{ $dataReport['statusonline'] }}</span><br />
                                            <hr />
                                            <span style="font-size: 8pt;" color="#000000">
                                                {{ $dataReport['status'] }}</span>
                                        </td>
                                    </tr>
                                </table>
                                </td>
                            </tr>
                        </table>
                       
                    </td>
                </tr>
            </tbody>
        </table>

</body>

</html>
