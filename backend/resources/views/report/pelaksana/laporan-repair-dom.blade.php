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
            /* Negatif dari margin-top, biar header pas */
            left: 0;
            right: 0;
            height: 100px;
            width: 100%;
            z-index: 10;
        }

        .pdf-footer {
            position: fixed;
            bottom: -50px;
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


    {{-- @if ($res['alat']->setujuilembarkerjamanager == true && $res['halamanPertama'] == true)
        <div class="pdf-content">
            <table width="100%" style="margin-top: -35px; " border="0">
                <tr>
                    <td width="35%">
                    </td>
                    <td width="30%">
                        <img src="{{ public_path('img/pln.png') }}" alt="Footer Surat PLN"
                            style="height:5%; width: 95%">
                    </td>
                    <td width="35%">
                    </td>
                </tr>
            </table>
            <table width="100%" style="margin-top: -5px; " border="0">
                <tr>
                    <td width="35%">

                    </td>
                    <td align="center" width="30%" style="line-height: 1.1;">
                        <div style="font-size: 14pt; color:#000000; font-weight: bold; margin-bottom: 2px;">
                            Sertifikat Kalibrasi
                        </div>
                        <div
                            style="font-size: 9pt; color:#000000; font-style: italic; font-weight: bold; margin-bottom: 2px;">
                            Calibration Certificate
                        </div>
                        <div style="font-size: 7pt; color:#000000; margin-bottom: 1px;">
                            Nomor Sertifikat : U-LAB/J/2025/123
                        </div>
                        <div style="font-size: 7pt; color:#000000;">
                            No Order : {{ $res['alat']->noorderalat }}
                        </div>
                    </td>
                    <td width="35%">
                    </td>
                </tr>
            </table>

            <table width="100%" style="padding-left: 5px margin-top: 20px; ">
                <tr>
                    <td width="30%">
                        <div style="font-size: 9pt; color:#000000; font-weight: bold; margin-bottom: 1px;">
                            Identitas Alat
                        </div>
                        <div style="font-size: 8pt; color:#000000; font-style: italic;">
                            Instrument Details
                        </div>
                    </td>
                </tr>
            </table>
            <table width="100%" style="padding-left: 45px; margin-top: 5px; border-collapse: collapse;">
                <tr style="line-height: 1.1;">
                    <td width="10%" style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;"><b>Nama Alat Ukur</b></span><br>
                        <span style="font-size: 8pt; color: #000000; font-style: italic;">Instrument</span>
                    </td>
                    <td width="1%" style="padding-bottom: 2px;">:</td>
                    <td width="30%" style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;">{{ $res['alat']->namaproduk }}</span>
                    </td>
                </tr>
                <tr style="line-height: 1.1;">
                    <td style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;"><b>Merk Pabrik / Tipe</b></span><br>
                        <span style="font-size: 8pt; color: #000000; font-style: italic;">Manufacture/ type</span>
                    </td>
                    <td style="padding-bottom: 2px;">:</td>
                    <td style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;">{{ $res['alat']->namamerk }} /
                            {{ $res['alat']->namatipe }}</span>
                    </td>
                </tr>
                <tr style="line-height: 1.1;">
                    <td style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;"><b>Nomor Seri</b></span><br>
                        <span style="font-size: 8pt; color: #000000; font-style: italic;">Serial number</span>
                    </td>
                    <td style="padding-bottom: 2px;">:</td>
                    <td style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;">{{ $res['alat']->namaserialnumber }}</span>
                    </td>
                </tr>
                <tr style="line-height: 1.1;">
                    <td style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;"><b>Identifikasi lain</b></span><br>
                        <span style="font-size: 8pt; color: #000000; font-style: italic;">Other identification</span>
                    </td>
                    <td style="padding-bottom: 2px;">:</td>
                    <td style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;">-</span>
                    </td>
                </tr>
            </table>
            <table border="0" width="100%" style="padding-left: 5px; margin-top: 10px; ">
                <tr>
                    <td width="30%">
                        <span style="font-size: 9pt;color:#000000"><b>
                                Idetitas Pemilik </b>
                        </span><br>
                        <span style="font-size: 8pt;color:#000000; font-style: italic;">
                            Owner's Identification
                        </span>
                    </td>
                </tr>
            </table>
            <table width="100%" style="padding-left: 45px; margin-top: 5px; border-collapse: collapse;">
                <tr style="line-height: 1.1;">
                    <td width="10%" style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;"><b>Nama</b></span><br>
                        <span style="font-size: 8pt; color: #000000; font-style: italic;">Designation</span>
                    </td>
                    <td width="1%" style="padding-bottom: 2px;">:</td>
                    <td width="30%" style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;">{{ $res['identitas']->namaperusahaan }}</span>
                    </td>
                </tr>
                <tr style="line-height: 1.1;">
                    <td width="10%" style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;"><b> Alamat</b></span><br>
                        <span style="font-size: 8pt; color: #000000; font-style: italic;">Address</span>
                    </td>
                    <td width="1%" style="padding-bottom: 2px;">:</td>
                    <td width="30%" style="padding-bottom: 2px;">
                        <span style="font-size: 9pt; color: #000000;">{{ $res['identitas']->alamatktr }}</span>
                    </td>
                </tr>
            </table>

            <table border="0" width="100%" style="padding-left: 5px; margin-top: 10px; ">
                <tr>
                    <td width="30%">
                        <span style="font-size: 9pt;color:#000000"><b>
                                Pengesahan </b>
                        </span><br>
                        <span style="font-size: 8pt;color:#000000; font-style: italic;">
                            Authorization
                        </span>
                    </td>
                </tr>
            </table>
            <table width="100%" style="margin-top: -10px;">
                <tr>
                    <!-- Kolom Kiri -->
                    <td width="60%" valign="top" style="padding-left: 15px;">
                        <!-- Bisa isi Pengesahan jika diperlukan -->
                    </td>

                    <!-- Kolom Kanan Tengah -->
                    <td width="40%" align="center">
                        <table cellspacing="0" cellpadding="0" style="line-height: 1.1;">
                            <tr>
                                <td style="font-size: 9pt; font-weight: bold; color:#000000;">
                                    Sertifikat ini terdiri dari
                                </td>
                                <td style="font-size: 9pt; font-weight: bold; color:#000000;" align="center"
                                    width="30">
                                    {{ $jumlahHalaman ?? '...' }}
                                </td>
                                <td style="font-size: 9pt; font-weight: bold; color:#000000;">
                                    Halaman
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 8pt; font-style: italic; color:#000000;">
                                    This certificate consist of
                                </td>
                                <td style="font-size: 8pt; font-style: italic; color:#000000;" align="center">
                                    {{ $jumlahHalaman ?? '...' }}
                                </td>
                                <td style="font-size: 8pt; font-style: italic; color:#000000;">
                                    Pages
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 9pt; font-weight: bold; color:#000000;">
                                    Diterbitkan tanggal
                                </td>
                                <td></td>
                                <td style="font-size: 9pt; color:#000000;">
                                    {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                            </tr>
                            <tr>
                                <td style="font-size: 8pt; font-style: italic; color:#000000;">
                                    Date of Issue
                                </td>
                                <td></td>
                                <td style="font-size: 8pt; color:#000000;">
                                    {{ \Carbon\Carbon::now()->format('F d, Y') }}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>



            <table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: 1px">
                <tbody style="font-size: 11pt">
                    <tr>
                        <td width="30%">
                        </td>
                        <td width="30%" class="text-center">
                        </td>
                        <td align="center" width="40%" class="text-center">
                            <span style="font-size: 9pt;color:#000000"><b>
                                    Manager Repair </b>
                            </span><br>
                            <span style="font-size: 8pt;color:#000000; font-style: italic;">
                                Manager Repair
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td align="center">
                            <img src="data:image/png;base64, {!! $res['ttdManager'] !!}"
                                style="margin-top: 5px; margin-bottom: 5px">
                        </td>
                    </tr>
                    <tr>
                        <td>
                        </td>
                        <td>
                        </td>
                        <td align="center" height="10" valign="bottom" height="100" width="15%"
                            class="text-center">
                            <span style="font-size: 10pt;" class="text-biasa">
                                <b> {{ $res['alat']->namamanager }}</span></b>
                        </td>
                    </tr>
                <tbody>
            </table>
            <table border="0" width="100%" style="padding-left: 5px; margin-top: 5px; ">
                <tr>
                    <td width="30%">
                        <span style="font-size: 9pt;color:#000000"><b>
                                Keterangan </b>
                        </span><br>
                        <span style="font-size: 8pt;color:#000000; font-style: italic;">
                            Notes
                        </span>
                    </td>
                </tr>
            </table>
            <table border="0" width="100%" style="padding-left: 45px; margin-top: -5px;">
                <tr>
                    <td width="100%">
                        <div style="margin-bottom: 6px;">
                            <div style="font-size: 9pt; color:#000000;"><b>
                                    Kalibrasi atau pengukuran yang dilaporkan dalam sertifikat ini tercakup dalam
                                    lingkup
                                    akreditasi menurut SNI ISO/IEC 17025:2017 oleh Komite Akreditasi Nasional, kecuali
                                    dinyatakan lain dalam badan sertifikat.
                                </b></div>
                            <div style="font-size: 8pt; color:#000000; font-style: italic;">
                                The calibration or measurement reported in this certificate is covered in the
                                accreditation
                                scope according to SNI ISO/IEC 17025:2017 by the
                                National Accreditation Committee of Indonesia, unless marked otherwise in the body of
                                certificate.
                            </div>
                        </div>

                        <div style="margin-bottom: 6px;">
                            <div style="font-size: 9pt; color:#000000;"><b>
                                    Sertifikat ini hanya berlaku untuk peralatan dengan spesifikasi yang dinyatakan di
                                    atas.
                                </b></div>
                            <div style="font-size: 8pt; color:#000000; font-style: italic;">
                                This certificate applies only for the item specified above.
                            </div>
                        </div>

                        <div>
                            <div style="font-size: 9pt; color:#000000;"><b>
                                    Dilarang keras mengutip/memperbanyak dan/atau mempublikasikan sebagian isi
                                    sertifikat
                                    ini
                                    tanpa ijin tertulis dari PT PLN NP UMRO.
                                </b></div>
                            <div style="font-size: 8pt; color:#000000; font-style: italic;">
                                It is prohibited to quote/reproduce and/or publish part of this certificate without
                                written
                                permission from PT PLN NP UMRO.
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="page-break"></div>
    @endif --}}


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
                            <img src="{{ 'berkas-laporan-repair/' . $alat->fotoalatrepair }}" width="170px"
                                style="margin:2px 0;">
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

                    <div
                        style="font-size: 9pt; color: #000000; text-align: justify; margin: 20px auto 0 auto; max-width: 95%; font-style: italic; font-weight: normal;">
                        @foreach (explode("\n", trim($res['alat']->kesimpulanrepair_en)) as $paragraph)
                            @if (trim($paragraph) !== '')
                                <p style="margin: 6px 0;">
                                    {{ $paragraph }}
                                </p>
                            @endif
                        @endforeach
                    </div>
                </td>
            </tr>
        </table>


    </div>
</body>

</html>
