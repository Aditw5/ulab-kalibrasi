<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Formulir Surat Perintah Kerja</title>
</head>
<style>
    @page {
        margin: 0
    }

    body {
        margin: 0
    }

    .sheet {
        margin: 0;
        overflow: auto;
        position: relative;
        box-sizing: border-box;
        page-break-after: always;
    }

    /** Paper sizes **/
    body.A3 .sheet {
        width: 297mm;
        height: 419mm
    }

    body.A3.landscape .sheet {
        width: 420mm;
        height: 296mm
    }

    body.A4 .sheet {
        width: 210mm;
        height: 296mm
    }

    body.G4 .sheet {
        width: 210mm;
        height: 296mm
    }

    body.A4.landscape .sheet {
        width: 297mm;
        height: 209mm
    }

    body.A5 .sheet {
        width: 148mm;
        height: 209mm
    }

    body.A5.landscape .sheet {
        width: 210mm;
        height: 147mm
    }

    body.F4 .sheet {
        width: 210mm;
        height: 330mm
    }

    body.letter .sheet {
        width: 216mm;
        height: 279mm
    }

    body.letter.landscape .sheet {
        width: 280mm;
        height: 215mm
    }

    body.legal .sheet {
        width: 216mm;
        height: 356mm
    }

    body.legal.landscape .sheet {
        width: 357mm;
        height: 215mm
    }

    /** Padding area **/
    .sheet.padding-10mm {
        padding: 10mm
    }

    .sheet.padding-15mm {
        padding: 15mm
    }

    .sheet.padding-20mm {
        padding: 20mm
    }

    .sheet.padding-25mm {
        padding: 25mm
    }

    /** For screen preview **/
    @media screen {
        body {
            background: white
        }

        .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0.3, 0.3, 0.3, .3);
            margin: 5mm auto;
        }
    }

    /** Fix for Chrome issue #273306 **/
    @media print {
        body.A3.landscape {
            width: 420mm
        }

        body.A3,
        body.A4.landscape {
            width: 297mm
        }

        body.A4,
        body.A5.landscape {
            width: 210mm
        }

        body.A5 {
            width: 148mm
        }

        body.F4 {
            width: 210mm
        }

        body.letter,
        body.legal {
            width: 216mm
        }

        body.letter.landscape {
            width: 280mm
        }

        body.legal.landscape {
            width: 357mm
        }
    }


    body,
    td,
    th,
    span {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<body style="padding:25px">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <thead style="display: none"></thead>
        <tbody>
            <tr>
                <td rowspan="5">
                    <img src="img/pln.png" width="80px" border="0" style="margin-top:-20px">
                </td>
                <td class="text-center">
                    <span style="font-size: 13pt;font-weight: 600;color:#000000"><b>
                            LABORATORIUM KALIBRASI PT PLN NP UMRO
                        </b></span>
                </td>
                <td rowspan="5">
                    <img src="img/ulab.png" width="80px" border="0" style="margin-top:-20px">
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                            FORMULIR
                        </b></span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                            SURAT PERINTAH KERJA {{ strtoupper($res['alat'][0]->lingkupkalibrasi) }}
                        </b></span>
                </td>
            </tr>

            <tr>

                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <thead style="display: none"></thead>
                    <tbody>
                        <tr>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    No.Dok : FMMO-163-25-74.4
                                    </a>
                                </span>
                            </td>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    Revisi : 01
                                    </a>
                                </span>
                            </td>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                                    </a>
                                </span>
                            </td>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    Halaman: 1 dari 2
                                    </a>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </tr>
        </tbody>
    </table>

    <hr class="baris1" style="margin-top:1px">
    <hr class="baris1" style="margin-top:-5px">

    {{-- {{ dd($res) }} --}}

    <table width="100%" style="margin-top: -19px; ">
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;olor:#000000">
                        No. Urut Pendaftaran
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->nopendaftaran }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Tanggal Order
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>
                            {{ $res['identitas']->tglregistrasi }}</b>
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Pemilik Alat
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->namaperusahaan }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Alamat
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->alamatktr }}
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="40%">
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>

                        </b></span>
                </td>
                <td width="20%">
                    <span style="font-size: 9pt;color:#000000"><b>
                            Menugaskan karyawan dibawah ini ,
                        </b></span>
                </td>
                <td width="40%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;olor:#000000">
                        Nama
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['alat'][0]->pelaksanateknik }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        NID
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Jabatan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['alat'][0]->jabatanpelaksana }}
                    </span>
                </td>
            </tr>
        </table>

        <table width="100%" border="1" cellspacing="0" cellpadding="5"
            style="font-size: 10pt; border-collapse: collapse; margin-top: 20px;">
            <thead>
                <tr>
                    <th colspan="6" style="text-align: left; font-size: 11pt;">
                        Untuk melaksanakan pekerjaan dibawah ini,
                    </th>
                </tr>
                <tr style="background-color: #f2f2f2;">
                    <th style="font-size: 9pt;">No. Order</th>
                    <th style="font-size: 9pt;">Nama Alat</th>
                    <th style="font-size: 9pt;">Merk/Tipe</th>
                    <th style="font-size: 9pt;">S/N</th>
                    <th style="font-size: 9pt;">Kontak</th>
                    <th style="font-size: 9pt;">Non - Kontak</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($res['alat'] as $alat)
                    <tr>
                        <td style="font-size: 9pt;">{{ $alat->noorderalat ?? '-' }}</td>
                        <td style="font-size: 9pt;">{{ $alat->namaproduk }}</td>
                        <td style="font-size: 9pt;">{{ trim($alat->namamerk) }} {{ $alat->namatipe }}</td>
                        <td style="font-size: 9pt;">{{ $alat->namaserialnumber }}</td>
                        <td style="font-size: 9pt;"></td>
                        <td style="font-size: 9pt;"></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="40%">
                    <span style="font-size: 9pt;olor:#000000">

                    </span>
                </td>
                <td width="20%">
                    <span style="font-size: 9pt;color:#000000"><b>
                            Uraian Kesepakatan Pekerjaan </b>
                    </span>
                </td>
                <td width="40%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
        </table>
        @php
            $totalDurasi = 0;
        @endphp

        @foreach ($res['alat'] as $alat)
            @php
                $totalDurasi += (int) $alat->durasikalbrasi; // atau $alat->durasikalbrasi jika object
            @endphp
            <!-- Baris tabel alat di sini -->
        @endforeach
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="30%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;olor:#000000">
                        Tanggal Order
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->tglregistrasi }}
                    </span>
                </td>
                <td width="35%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
            <tr>
                <td width="30%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;olor:#000000">
                        Rencana Selesai
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        {{ $totalDurasi }}
                    </span>
                </td>
                <td width="35%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
            <tr>
                <td width="30%">
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;olor:#000000">
                        Realisasi Selesai
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
                <td width="35%">
                    <span style="font-size: 9pt;color:#000000">
                        Hari Kerja
                    </span>
                </td>
            </tr>

        </table>

</body>

<!-- Halaman Kedua -->
<div class="sheet" style="padding:25px">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <thead style="display: none"></thead>
        <tbody>
            <tr>
                <td rowspan="5">
                    <img src="img/pln.png" width="80px" border="0" style="margin-top:-20px">
                </td>
                <td class="text-center">
                    <span style="font-size: 13pt;font-weight: 600;color:#000000"><b>
                            LABORATORIUM KALIBRASI PT PLN NP UMRO
                        </b></span>
                </td>
                <td rowspan="5">
                    <img src="img/ulab.png" width="80px" border="0" style="margin-top:-20px">
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                            FORMULIR
                        </b></span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                            SURAT PERINTAH KERJA {{ strtoupper($res['alat'][0]->lingkupkalibrasi) }}
                        </b></span>
                </td>
            </tr>

            <tr>

                <table width="100%" cellspacing="0" cellpadding="0" border="0">
                    <thead style="display: none"></thead>
                    <tbody>
                        <tr>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    No.Dok : FMMO-163-25-74.4
                                    </a>
                                </span>
                            </td>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    Revisi : 01
                                    </a>
                                </span>
                            </td>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                                    </a>
                                </span>
                            </td>
                            <td class="text-centar">
                                <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                    Halaman: 2 dari 2
                                    </a>
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </tr>
        </tbody>
    </table>

    <hr class="baris1" style="margin-top:1px">
    <hr class="baris1" style="margin-top:-5px">
    <table width="100%" style="margin-top: 100px; ">
        <tr>
            <td width="40%">
                <span style="font-size: 9pt;olor:#000000">

                </span>
            </td>
            <td width="20%">
                <span style="font-size: 9pt;color:#000000"><b>
                        Catatan Pekerjaan </b>
                </span>
            </td>
            <td width="40%">
                <span style="font-size: 9pt;color:#000000">

                </span>
            </td>
        </tr>
    </table>
    <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="25%">
                </td>
                <td width="50%">
                    <span style="font-size: 9pt;color:#000000">
                       1. Semua pekerjaan dilakukan sesuai dengan prosedur Penanganan Alat Yang Dikalibrasi.
                    </span>
                </td>
                <td width="25%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
            <tr>
                <td width="25%">
                </td>
                <td width="50%">
                    <span style="font-size: 9pt;color:#000000">
                        2. Wajib menggunakan Instruksi Keja (IK) saat kegiatan kalibrasi berlangsung.
                    </span>
                </td>
                <td width="25%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
            <tr>
                <td width="25%">
                </td>
                <td width="50%">
                    <span style="font-size: 9pt;color:#000000">
                        3. Wajib menggunakan Lembar Kerja (LK) yang dibuat oleh staf teknik.
                    </span>
                </td>
                <td width="25%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>
            <tr>
                <td width="25%">
                </td>
                <td width="50%">
                    <span style="font-size: 9pt;color:#000000">
                        4. Segala bentuk penyimpanagan dicatat dalam Formulir Ketidaksesuaian Pekerjaa
                    </span>
                </td>
                <td width="25%">
                    <span style="font-size: 9pt;color:#000000">

                    </span>
                </td>
            </tr>

        </table>
    <table  width="100%" cellspacing="0" cellpadding="0" style="padding-top: 50px">
        <tbody style="font-size: 11pt">
            <tr>
                <td width="40%">
                </td>
                <td width="30%" class="text-center">
                     <span style="font-size: 10pt;" class="text-biasa">
                        Mengetahui
                     </span>
                </td>
                <td width="30%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa">{{ $res['identitas']->lokasi }},
                        {{ \Carbon\Carbon::parse(date('Y-m-d H:i'))->isoFormat('DD MMMM Y') }}</span>
                </td>
            </tr>
            <tr>
                 <td width="40%">
                </td>
                <td width="30%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa"> Kepala Laboratorium Kalibrasi </span>
                </td>
                <td width="30%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa"> Penyelia Teknik {{$res['alat'][0]->lingkupkalibrasi}}</span>
                </td>
            </tr>
            <tr>
                 <td >
                </td>
                <td align="center">
                    <img src="data:image/png;base64, {!! $res['ttdPetugas'] !!}"
                        style="margin-top: 5px; margin-bottom: 5px">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                </td>
                <td align="center">
                    <img src="data:image/png;base64, {!! $res['ttdPenyelia'] !!}"
                        style="margin-top: 5px; margin-bottom: 5px">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                </td>
            </tr>
            <tr>
                 <td >
                </td>
                <td height="10" valign="bottom" height="100" width="15%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa">(
                                                )</span>
                </td>
                <td height="10" valign="bottom" height="100" width="15%" class="text-center">
                    <span style="font-size: 10pt;" class="text-biasa">( {{$res['alat'][0]->penyeliateknik}}
                        )</span>
                </td>
            </tr>
        <tbody>
    </table>
</div>


</html>
