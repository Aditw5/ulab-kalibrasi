<!DOCTYPE html>
<html>

<head>
    <title>Sertifikat Lembar Kerja</title>
    <style>
        @page {
            margin-top: 110px;
            margin-bottom: 60px;
            margin-left: 35px;
            margin-right: 35px;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #111;
        }

        .pdf-header {
            position: fixed;
            top: -90px;
            /* Negatif dari margin-top, biar header pas */
            left: 0;
            right: 0;
            height: 100px;
            width: 100%;
            z-index: 10;
        }

        .pdf-footer {
            position: fixed;
            bottom: -60px;
            /* Negatif dari margin-bottom */
            left: 0;
            right: 0;
            height: 70px;
            width: 100%;
            z-index: 10;
        }

        /* Supaya konten tidak nabrak header/footer */
        .pdf-content {
            /* kosong, gunakan margin @page */
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="pdf-header">
        <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-top:5px;">
            <tr>
                <td>
                    <img src="{{ public_path('img/kop-surat.jpg') }}" alt="Kop Surat Sertifikat Kalibrasi PLN NP"
                        style="width:100%; height:auto; display:block;">
                </td>
            </tr>
        </table>
        <hr style="border:none; border-top:3px double #000; margin:8px 0 2px 0;">
        <hr style="border:none; border-top:1.2px solid #000; margin:-5px 0 0 0;">
    </div>

    <div class="pdf-footer">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width:33%; vertical-align:bottom; text-align:left; padding-left:8px;">
                    <span style="font-size:8pt; color:#111;">FMMO-163-14.4.3.b-78.1</span>
                </td>
                <td style="width:34%; text-align:center; vertical-align:bottom;">
                    <div style="font-size:8pt; color:#111; font-weight:bold;">
                        &nbsp;
                    </div>
                    <div style="font-size:7pt; color:#222; font-style:italic;">
                        &nbsp;
                    </div>
                </td>
                <td style="width:33%; vertical-align:bottom; text-align:right; padding-right:8px;">
                    <img src="{{ public_path('img/foot-surat.jpg') }}" alt="Footer Surat PLN" style="height:35px;">
                </td>
            </tr>
        </table>
    </div>

    {{-- {{ dd($res) }} --}}


    <div class="pdf-content">
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
                        {{ $res['lembarKerja'][0]->tglkalibrasilembarkerja }}
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
                        {{ $res['lembarKerja'][0]->tempatKalibrasilembarkerja }}
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
                        {{ $res['lembarKerja'][0]->kondisiRuanganlembarkerja }}
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
                        {{ $res['lembarKerja'][0]->suhulembarkerja }}
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
                        {{ $res['lembarKerja'][0]->kelembabanRelatiflembarkerja }}
                    </span>
                </td>
            </tr>
        </table>

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

    </div>
</body>

<div class="page-break"></div>

<div class="sheet">
    <table width="100%" style="margin-top: -5px; " border="0">
        <tr>
            <td width="30%">
                <span style="font-size: 9pt;color:#000000"><b>
                        Catatan /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    Notes
                </span>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 5px; " border="0">
        <tr>
            <td width="30%">
                <span style="font-size: 9pt;color:#000000"><b>
                        Hasil kalibrasi ini diperoleh berdasarkan daftar instruksi kerja dengan menggunakan alat standar
                        yang tertelusur ke SI
                        melalui SNSU-BSN./</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    This calibration result was acquired based on the list of work instruction calibration using the
                    standard instrument that is
                    traceable to SI through SNSU-BSN.
                </span>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 5px; " border="0">
        <tr>
            <td width="30%">
                <span style="font-size: 9pt;color:#000000"><b>
                        Ketidakpastian pengukuran dihitung dengan tingkat kepercayaan tidak kurang dari 95% dan faktor
                        cakupan k = 2./
                    </b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    uncertainty of measurement was calculated with the confidence level not less than 95% and coverage
                    factor of k = 2.
                </span>
            </td>
        </tr>
    </table>

    <div style="margin-top:18px; margin-bottom:2px;">
        <span style="font-size: 9pt;color:#000000"><b>
                Daftar Instruksi Kerja /</b>
        </span>
        <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
            List of work instruction
        </span>
    </div>
    <table width="100%" border="0" cellpadding="2" cellspacing="0"
        style="border-collapse:collapse; font-size:7pt; margin-bottom:20px;">
        <thead>
            <tr>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    NO
                </th>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    No Instruksi Kerja
                </th>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    Nama Instruksi Kerja
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($res['instruksikerja'] as $row)
                <tr>
                    <td align="center" style="padding:2px;">
                        {{ $loop->iteration }}
                    </td>
                    <td align="center" style="padding:2px;">{{ $row->noisntruksikerja ?? '' }}</td>
                    <td align="center" style="padding:2px;">{{ $row->namainstruksikerja ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="border-bottom:2pt solid #000; height:2px;"></td>
            </tr>
        </tfoot>
    </table>

    <div style="margin-top:18px; margin-bottom:2px;">
        <span style="font-size: 9pt;color:#000000"><b>
                Daftar Peralatan Standar /</b>
        </span>
        <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
            List of Standard
        </span>
    </div>
    <table width="100%" border="0" cellpadding="2" cellspacing="0"
        style="border-collapse:collapse; font-size:7pt; margin-bottom:20px;">
        <thead>
            <tr>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    NO
                </th>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    Nama Standard
                </th>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    Merk/ Tipe
                </th>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    No Serial
                </th>
                <th
                    style="border-top:2pt solid #000; border-bottom:2pt solid #000; font-size:8pt; font-weight:bold; text-align:center; padding:3px;">
                    Due Date
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($res['alastandar'] as $row)
                <tr>
                    <td align="center" style="padding:2px;">
                        {{ $loop->iteration }}
                    </td>
                    <td align="center" style="padding:2px;">{{ $row->namaalatstandar ?? '' }}</td>
                    <td align="center" style="padding:2px;">{{ $row->namamerk ?? '' }} - {{ $row->namatipe ?? '' }}
                    </td>
                    <td align="center" style="padding:2px;">{{ $row->namaserialnumber ?? '' }}</td>
                    <td align="center" style="padding:2px;">{{ $row->duedate ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" style="border-bottom:2pt solid #000; height:2px;"></td>
            </tr>
        </tfoot>
    </table>
    <table width="100%" style="margin-top: -29px; text-align:center;  " border="0">
        <tr>
            <td width="30%">
                <span style="font-size: 9pt;color:#000000"><b>
                        Akhir dari Sertifikat /</b>
                </span>
                <span style="margin-left:-10px ;font-style:italic;font-size: 8pt;color:#000000">
                    End of certificate
                </span>
            </td>
        </tr>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: 50px">
        <tbody style="font-size: 11pt">
            <tr>
                <td align="center" width="30%">
                     <span style="font-size: 10pt;" class="text-biasa"> 
                         Pelaksana Kalibrasi 
                     </span>
                </td>
                <td align="center" width="30%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa"> 
                         Penyelia Kalibrasi
                    </span>
                </td>
                <td align="center" width="30%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa"> 
                        Asman NMW
                    </span>
                </td>
            </tr>
            <tr>
                <td align="center">
                    <img src="data:image/png;base64, {!! $res['ttdPelaksana'] !!}"
                        style="margin-top: 5px; margin-bottom: 5px">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                </td>
                <td align="center">
                    <img src="data:image/png;base64, {!! $res['ttdPenyelia'] !!}"
                        style="margin-top: 5px; margin-bottom: 5px">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                </td>
                <td align="center">
                    <img src="data:image/png;base64, {!! $res['ttdAsman'] !!}"
                        style="margin-top: 5px; margin-bottom: 5px">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                </td>
            </tr>
            <tr>
                <td align="center" height="10" valign="bottom" height="100" width="15%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa">( {{ $res['alat']->pelaksanateknik }}
                        )</span>
                </td>
                <td align="center" height="10" valign="bottom" height="100" width="15%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa">( {{ $res['alat']->penyeliateknik }}
                        )</span>
                </td>
                <td align="center" height="10" valign="bottom" height="100" width="15%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa">
                        ( {{ $res['alat']->asamanverifikasi }} )</span>
                </td>
            </tr>
        <tbody>
    </table>
     {{-- {{ dd($res) }} --}}

</div>

</html>
