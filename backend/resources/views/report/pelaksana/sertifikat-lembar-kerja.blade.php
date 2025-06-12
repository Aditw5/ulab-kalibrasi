<!DOCTYPE html>
<html>
<head>
    <title>Sertifikat Lembar Kerja</title>
    <style>
        @page {
            header: kop;
            footer: footer;
            margin-top: 110px;
            margin-bottom: 65px;
        }
        body {
            margin: 0;
            padding: 0 35px;
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            color: #111;
        }
        /* ...tambahkan style lain jika perlu... */
    </style>
</head>


<body
    style="margin:0; padding:30px 35px 0 35px; font-family: Arial, Helvetica, sans-serif; background:#fff; color:#111;">
    <table width="100%" style="margin-top: -20px; ">
        <tr>
            <td width="40%">
                <span style="font-size: 9pt;olor:#000000">

                </span>
            </td>
            <td width="20%">
                <span style="font-size: 9pt;color:#000000"><b>
                        Hasil Kalibrasi: </b>
                </span>
                <div style="font-size:7pt; color:#000;">
                    Calibration Result :
                </div>
            </td>
            <td width="40%">
                <span style="font-size: 9pt;color:#000000">

                </span>
            </td>
        </tr>
    </table>
    <hr style="border:none; border-top:3px double #000; margin:-4px 0 0 0;">
    <hr style="border:none; border-top:1.2px solid #000; margin:-4px 0 8px 0;">
    <table width="100%" style="margin-top: 30px; ">
        <tr>
            <td width="30%">
                <span style="font-size: 8pt;color:#000000"><b>
                        Tanggal Kalibrasi /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    Calibration Date
                </span>
            </td>
            <td width="2%">
                <span style="font-size: 8pt;color:#000000">
                    :
                </span>
            </td>
            <td>
                <span style="font-size: 8pt;color:#000000">
                    {{-- {{ $res['alat'][0]->pelaksanateknik }} --}}
                </span>
            </td>
        </tr>
        <tr>
            <td width="30%">
                <span style="font-size: 8pt;color:#000000"><b>
                        Tempat Kalibrasi /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    Calibration Place
                </span>
            </td>
            <td width="2%">
                <span style="font-size: 8pt;color:#000000">
                    :
                </span>
            </td>
            <td>
                <span style="font-size: 8pt;color:#000000">
                    {{-- {{ $res['alat'][0]->pelaksanateknik }} --}}
                </span>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 10px; ">
        <tr>
            <td width="30%">
                <span style="font-size: 8pt;color:#000000"><b>
                        Kondisi Ruangan /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    Environmental Condition
                </span>
            </td>
            <td width="2%">
                <span style="font-size: 8pt;color:#000000">

                </span>
            </td>
            <td>
                <span style="font-size: 8pt;color:#000000">
                    {{-- {{ $res['alat'][0]->pelaksanateknik }} --}}
                </span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span style="font-size: 8pt;color:#000000"><b>
                        Suhu /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    Temperature
                </span>
            </td>
            <td width="2%">
                <span style="font-size: 8pt;color:#000000">
                    :
                </span>
            </td>
            <td>
                <span style="font-size: 8pt;color:#000000">
                    {{-- {{ $res['alat'][0]->pelaksanateknik }} --}}
                </span>
            </td>
        </tr>
        <tr>
            <td width="25%">
                <span style="font-size: 8pt;color:#000000"><b>
                        Kelembaban Relatif /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    Relative Humidity
                </span>
            </td>
            <td width="2%">
                <span style="font-size: 8pt;color:#000000">
                    :
                </span>
            </td>
            <td>
                <span style="font-size: 8pt;color:#000000">
                    {{-- {{ $res['alat'][0]->pelaksanateknik }} --}}
                </span>
            </td>
        </tr>
    </table>
    {{-- {{ dd($res) }} --}}
    @php
        $grouped = collect($res['lembarKerja'])->groupBy('group');
    @endphp
    @foreach ($grouped as $groupName => $dataGroup)
        <div style="font-size:9pt; font-weight:700; margin-top:18px; margin-bottom:2px; color:#111;">
            {{ $groupName }}
        </div>
        <table width="100%" border="0" cellpadding="2" cellspacing="0"
            style="border-collapse:collapse; font-size:7pt; margin-bottom:20px;">
            <thead>
                <tr>
                    <th
                        style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                        Rentang<br>
                        <span style="font-size:7pt; font-style:italic; font-weight:400;">Range</span>
                    </th>
                    <th
                        style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                        Penunjukan Standar<br>
                        <span style="font-size:7pt; font-style:italic; font-weight:400;">Calibrator Output</span>
                    </th>
                    <th
                        style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                        Pembacaan Alat<br>
                        <span style="font-size:7pt; font-style:italic; font-weight:400;">Instrument Reading</span>
                    </th>
                    <th
                        style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                        Koreksi<br>
                        <span style="font-size:7pt; font-style:italic; font-weight:400;">Correction</span>
                    </th>
                    <th
                        style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                        Ketidakpastian<br>
                        <span style="font-size:7pt; font-style:italic; font-weight:400;">Uncertainty</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataGroup as $row)
                    <tr>
                        <td align="center" style="padding:2px;">{{ $row->rentang ?? '' }}
                            {{ $row->rentang_satuan ?? '' }}</td>
                        <td align="center" style="padding:2px;">{{ $row->penunjukan_standar ?? '' }}
                            {{ $row->penunjukan_standar_satuan ?? '' }}</td>
                        <td align="center" style="padding:2px;">{{ $row->pembacaan_alat ?? '' }}
                            {{ $row->pembacaan_alat_satuan ?? '' }}</td>
                        <td align="center" style="padding:2px;">{{ $row->koreksi ?? '' }}
                            {{ $row->koreksi_satuan ?? '' }}</td>
                        <td align="center" style="padding:2px;">{{ $row->ketidakpastian ?? '' }}
                            {{ $row->ketidakpastian_satuan ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" style="border-bottom:2pt solid #000; height:2px;"></td>
                </tr>
            </tfoot>
        </table>
    @endforeach
</body>

</html>
