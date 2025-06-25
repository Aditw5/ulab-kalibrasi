<!DOCTYPE html>
<html>

<head>
    <title>LAPORAN HASIL REPAIR</title>
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
            left: 0;
            right: 0;
            height: 100px;
            width: 100%;
            z-index: 10;
        }

        .pdf-footer {
            position: fixed;
            bottom: -50px;
            left: 0;
            right: 0;
            height: 70px;
            width: 100%;
            z-index: 10;
        }


        .pdf-content {}

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    {{-- {{ dd($res) }} --}}

    <div class="pdf-header">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <thead style="display: none"></thead>
            <tbody>
                <tr>
                    <td rowspan="5">
                        <img src="img/pln.png" width="80px" border="0" style="margin-top:-20px">
                    </td>
                    <td align="center">
                        <span style="font-size: 13pt;font-weight: 600;color:#000000"><b>
                                LABORATORIUM KALIBRASI PT PLN NP UMRO
                            </b></span>
                    </td>
                    <td rowspan="5">
                        <img src="img/ulab.png" width="80px" border="0" style="margin-top:-20px">
                    </td>
                </tr>

                <tr>
                    <td align="center">
                        <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                                FORMULIR
                            </b></span>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                                LAPORAN HASIL REPAIR
                            </b></span>
                    </td>
                </tr>

                <tr>

                    <table width="80%" cellspacing="0" cellpadding="0" border="0"
                        style="border-collapse: collapse; margin-left: 70px">
                        <tbody>
                            <tr>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    U-LAB/R/2025/022
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Revisi : 01
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Halaman: 1 dari 2
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </tr>
            </tbody>
        </table>

        <hr class="baris1" style="margin-top:1px">
        <hr class="baris1" style="margin-top:-5px">
    </div>


    <div class="pdf-content">

        <table width="100%" style="margin-top: 30px;" border="0">
            <tr>
                <td width="30%">
                    <div
                        style="font-size: 9pt; color:#000000; font-weight: bold; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 2px;">
                        STATUS ALAT
                    </div><br>
                    <span
                        style="font-style: italic; font-size: 7pt; font-family: 'Courier New', monospace; color:#000000;">
                        Instrument Status
                    </span>
                </td>
            </tr>
        </table>
        <table style="margin-top: 20px; " width="100%" border="1" cellspacing="0" cellpadding="5"
            style="font-size: 10pt; border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="font-size: 9pt;">Bagian Alat </th>
                    <th style="font-size: 9pt;">Dokumentasi</th>
                    <th style="font-size: 9pt;">Kondisi</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td width="25%">
                        <span style="font-size: 9pt;" class="text-biasa">
                            {{ $res['alat']->namaproduk }}
                        </span>
                    </td>
                    <td align="center">
                        <span style="font-size: 9pt;" class="text-biasa">
                            <img src="{{ 'berkas-mitra/' . $res['alat']->namafile }}" width="170px"
                                style="margin-top:50px ;">
                        </span>
                    </td>
                    <td> <span style="font-size: 9pt;" class="text-biasa">{{ $res['alat']->keterangan }}</span>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" style="margin-top: 30px;" border="0">
            <tr>
                <td width="30%">
                    <div
                        style="font-size: 9pt; color:#000000; font-weight: bold; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 2px;">
                        TINDAKAN TEKNIS
                    </div><br>
                    <span
                        style="font-style: italic; font-size: 7pt; font-family: 'Courier New', monospace; color:#000000;">
                        Repair Action
                    </span>
                </td>
            </tr>
        </table>
        <table style="margin-top: 20px; " width="100%" border="1" cellspacing="0" cellpadding="5"
            style="font-size: 10pt; border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="font-size: 9pt;">No</th>
                    <th style="font-size: 9pt;">Bagian Alat</th>
                    <th style="font-size: 9pt;">Penanganan</th>
                    <th style="font-size: 9pt;">Status</th>
                    <th style="font-size: 9pt;">Sparepart & Material Comsumable</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($res['laporanRepair'] as $index => $alat)
                    <tr>
                        <td width="5%"> <span style="font-size: 9pt;" class="text-biasa">{{ $index + 1 }} </span>
                        </td>
                        <td width="25%">
                            {{-- <img src="{{ 'berkas-laporan-repair/' . $alat->fotoalatrepair }}" width="170px"
                                style="margin:2px 0;"> --}}
                        </td>
                        <td width="15%">
                            <span style="font-size: 9pt;" class="text-biasa">
                                {{ $alat->penanganan }}
                            </span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 9pt;" class="text-biasa">
                                {{ $alat->status }}
                            </span>
                        </td>
                        <td width="15%">
                            <span style="font-size: 9pt;" class="text-biasa">
                                {{ $alat->sparepart }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <div class="sheet">
        <table width="100%" style="margin-top: 30px;" border="0">
            <tr>
                <td width="30%">
                    <div
                        style="font-size: 9pt; color:#000000; font-weight: bold; border-bottom: 2px solid #000; display: inline-block; padding-bottom: 2px;">
                        KESIMPULAN
                    </div><br>
                    <span
                        style="font-style: italic; font-size: 7pt; font-family: 'Courier New', monospace; color:#000000;">
                        Conclusion
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px;" border="0">
            <tr>
                <td style="width: 100%;">
                    <div
                        style="font-size: 9pt; color: #000000; text-align: justify; margin: 0 auto; max-width: 95%; font-weight: bold;">
                        @foreach (explode("\n", trim($res['alat']->kesimpulanrepair)) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p style="margin: 6px 0;">
                                    {{ $paragraph }}
                                </p>
                            @endif
                        @endforeach
                    </div>

                    {{-- <div
                        style="font-size: 9pt; color: #000000; text-align: justify; margin: 20px auto 0 auto; max-width: 95%; font-style: italic; font-weight: normal;">
                        @foreach (explode("\n", trim($res['alat']->kesimpulanrepair_en)) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p style="margin: 6px 0;">
                                    {{ $paragraph }}
                                </p>
                            @endif
                        @endforeach
                    </div> --}}
                </td>
            </tr>
        </table>


    </div>
</body>

</html>
