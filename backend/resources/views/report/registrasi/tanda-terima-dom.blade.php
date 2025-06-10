<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Formulir Tanda Terima Alat</title>
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
                            TANDA TERIMA ALAT
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
                                    No.Dok : FMMO-163-25-74.2
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
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>
                            Diterima Oleh
                        </b></span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Nama
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->namapenanggungjawab }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Jabatan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->jabatanpenanggungjawab }}
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>
                            Penerima ALat
                        </b></span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Nama
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->namapetugaskaji }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Jabatan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->namajabanpetugaskaji }}
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>
                            Diteriima Pada
                        </b></span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Tanggal
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Tempat
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        Laboratorium Kalibrasi PT PLN NUSANTARA POWER UMRO JAKARTA
                    </span>
                </td>
            </tr>
        </table>

        <table style="margin-top: 20px; " width="100%" border="1" cellspacing="0" cellpadding="5"
            style="font-size: 10pt; border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="font-size: 9pt;">No</th>
                    <th style="font-size: 9pt;">Nama Barang</th>
                    <th style="font-size: 9pt;">Merk/Tipe</th>
                    <th style="font-size: 9pt;">S/N</th>
                    <th style="font-size: 9pt;">Jumlah</th>
                    <th style="font-size: 9pt;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($res['alat'] as $index => $alat)
                    <tr>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $index + 1 }} </span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaproduk }}</span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ trim($alat->namamerk) }}
                                {{ $alat->namatipe }}</span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaserialnumber }}<< /span>
                        </td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">1 Set </span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->keterangan }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px">
            <tbody style="font-size: 11pt">
                <tr>
                    <td width="50%">
                    </td>
                    <td width="50%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">{{ $res['identitas']->lokasi }},
                            {{ \Carbon\Carbon::parse(date('Y-m-d H:i'))->isoFormat('DD MMMM Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa"> Pelanggan </span>
                    </td>
                    <td width="50%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa"> Penerima Alat</span>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <img src="data:image/png;base64, {!! $res['ttdPelanggan'] !!}"
                            style="margin-top: 5px; margin-bottom: 5px">
                        {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                    </td>
                    <td align="center">
                        <img src="data:image/png;base64, {!! $res['ttdPetugas'] !!}"
                            style="margin-top: 5px; margin-bottom: 5px">
                        {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                    </td>
                </tr>
                <tr>
                    <td height="10" valign="bottom" height="100" width="15%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">(
                            {{ $res['identitas']->namapenanggungjawab }}
                            )</span>
                    </td>
                    <td height="10" valign="bottom" height="100" width="15%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">( {{ $res['identitas']->namapetugaskaji }}
                            )</span>
                    </td>
                </tr>
            <tbody>
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
                            TANDA TERIMA ALAT
                        </b></span>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <thead style="display: none"></thead>
                        <tbody>
                            <tr>
                                <td class="text-centar">
                                    <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                        No.Dok : FMMO-163-25-74.2
                                    </span>
                                </td>
                                <td class="text-centar">
                                    <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                        Revisi : 01
                                    </span>
                                </td>
                                <td class="text-centar">
                                    <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                        Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                                    </span>
                                </td>
                                <td class="text-centar">
                                    <span style="font-size: 9pt;color:#000000; font-weight: 600">
                                        Halaman: 2 dari 2
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

    <hr class="baris1" style="margin-top:1px">
    <hr class="baris1" style="margin-top:-5px">

    <table style="margin-top: 20px; " width="100%" border="1" cellspacing="0" cellpadding="5"
        style="font-size: 10pt; border-collapse: collapse;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th style="font-size: 9pt;">No</th>
                <th style="font-size: 9pt;">Nama Barang</th>
                <th style="font-size: 9pt;">Merk/Tipe</th>
                <th style="font-size: 9pt;">S/N</th>
                <th style="font-size: 9pt;">Jumlah</th>
                <th style="font-size: 9pt;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($res['alat'] as $index => $alat)
                <tr>
                    <td width="5%"> <span style="font-size: 9pt;" class="text-biasa">{{ $index + 1 }} </span></td>
                    <td width="25%"> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaproduk }}</span></td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">{{ trim($alat->namamerk) }}
                            {{ $alat->namatipe }}</span></td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaserialnumber }}<< /span>
                    </td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">1 Set </span></td>
                    <td width="25%" style="text-align: center">
                        @if ($alat->namafile)
                            <img src="{{ 'berkas-mitra/' . $alat->namafile }}" width="170px"
                                style="margin:2px 0;">
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div>


</html>
