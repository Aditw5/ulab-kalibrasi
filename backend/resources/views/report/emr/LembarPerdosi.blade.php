<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Lembar Perdosri (Lembar C)</title>
    <style>
        @page {
            margin: 0
        }

        body {
            margin: 0;
            padding: 25px;
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
</head>

<body>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <thead style="display: none"></thead>
        <tbody>
            <tr>
                <td rowspan="5">
                    <img src="img/logo-kota.png" width="80px" border="0" style="margin-top:-20px">
                </td>
                <td class="text-center">
                    <span style="font-size: 11pt;font-weight: 600;letter-spacing: 1px;color:#000000"><b>
                            PEMERINTAH DAERAH KOTA CIREBON
                        </b></span>
                </td>
                <td rowspan="5">
                    <div style="width: 80px;"></div>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt;font-weight: 600;letter-spacing: 1px;color:#000000"><b>
                            DINAS KESEHATAN
                        </b></span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16pt;font-weight: 600;letter-spacing: 1px;color:#000000"><b>
                            RUMAH SAKIT DAERAH GUNUNG JATI
                        </b></span>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 11pt;color:#000000">
                        Jalan Kesambi Nomor 56, Cirebon 45134
                    </span>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 11pt;color:#000000">
                        Telepon. (0231) 206330 Faks. (0231) 203336 Email : <a href="#"> {!! $profile->alamatemail !!} </a>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>

    <hr class="baris1" style="margin-top:1px">
    <hr class="baris1" style="margin-top:-5px">

    <table width="100%">
        <tr>
            <td>
                <table width="70%" cellspacing="0" cellpadding="0" style="margin-left: 190px;">
                    <thead style="display: none"></thead>
                    <tbody>
                        <tr>
                            <td width="50%" class="text-center" style="border:1px solid black;">
                                <span class="text-judul" style="font-size: 11pt">Lembar Perdosri (Lembar C)</span>
                            </td>
                            <td width="20%" class="text-center">
                                <span class="text-judul" style="font-size: 11pt">C</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%">
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 20px;border:1px solid black;">
                    <thead style="display: none"></thead>
                    <tbody>
                        <table width="100%" cellspacing="0" cellpadding="6">
                            <thead style="display: none"></thead>
                            <tbody>
                                <tr style="font-size: 11pt;margin-top:40px;border-bottom:1px solid black;">
                                    <td width="40%">
                                        <span class="text-normal">No.RM</span>
                                    </td>
                                    <td width="60%" style="border-left:1px solid black;">
                                        <span class="text-normal">: {{ $identitas['nocm'] }}</span>
                                    </td>
                                </tr>
                                <tr style="font-size: 11pt;margin-top:40px;border-bottom:1px solid black;">
                                    <td width="40%">
                                        <span class="text-normal">Nama Pasien</span>
                                    </td>
                                    <td width="60%" style="border-left:1px solid black;">
                                        <span class="text-normal">: {{ $identitas['namapasien'] }}</span>
                                    </td>
                                </tr>
                                <tr style="font-size: 11pt;margin-top:40px;border-bottom:1px solid black;">
                                    <td width="40%">
                                        <span class="text-normal">Diagnosa</span>
                                    </td>
                                    <td width="60%" style="border-left:1px solid black;">
                                        <span class="text-normal">: {{ $identitas['diagnosa'] ?? '' }}</span>
                                    </td>
                                </tr>
                                <tr style="font-size: 11pt;margin-top:40px;">
                                    <td width="40%">
                                        <span class="text-normal">Permintaan Terapi</span>
                                    </td>
                                    <td width="60%" style="border-left:1px solid black;">
                                        <span class="text-normal">: {{ $identitas['permintaanTerapi'] ?? '' }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table cellspacing="7" cellpadding="7" style="border-collapse: collapse; border: 1px solid black;" width="100%">
        <tr>
            <th width="30%" rowspan="2" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">PROGRAM</span>
            </th>
            <th rowspan="2" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">REASSESSMENT</span>
            </th>
            <th width="12%" rowspan="2" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">TANGGAL</span>
            </th>
            <th width="58%" colspan="3" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">TTD</span>
            </th>
        </tr>
        <tr>
            <th style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">PASIEN</span>
            </th>
            <th style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">DOKTER</span>
            </th>
            <th style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">TERAPIS</span>
            </th>
        </tr>
        @if (isset($identitas['detaiperdosi']) && is_array($identitas['detaiperdosi']))
        @foreach ($identitas['detaiperdosi'] as $item)
        <tr>
            <td style="max-width: 20px;border: 1px solid black;">
                <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['namaProgram2']) ? $item['namaProgram2'] : '' }}</span>
            </td>
            <td style="max-width: 20px;border: 1px solid black; text-align: center;">
                <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">
                    <input type="checkbox" {{ isset($item['reassessment']) && $item['reassessment'] ? 'checked' : '' }}>
                </span>
            </td>
            <td style="text-align:center;max-width: 20px;border: 1px solid black;">
                @if (is_array($item['tglregistrasi2']) && isset($item['tglregistrasi2']['tglregistrasi']))
                    <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">
                        {{ \Carbon\Carbon::parse($item['tglregistrasi2']['tglregistrasi'])->timezone('Asia/Jakarta')->format('d-m-Y') }}</span>
                @elseif(is_string($item['tglregistrasi2']))
                    <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">
                        {{ \Carbon\Carbon::parse($item['tglregistrasi2'])->timezone('Asia/Jakarta')->format('d-m-Y') }}</span>
                @else
                    <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">Invalid Date</span>
                @endif
            </td>
            <td style="text-align:center;max-width: 20px;border: 1px solid black;">
                @if (is_array($item['tglregistrasi2']) && isset($item['tglregistrasi2']['tglregistrasi']))
                <img src="data:image/png;base64, {!! $item['ttePasien'] !!}"><br>
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=60x60&data={{ isset($item['namapasien2']) ? $item['namapasien2']: '' }}, {{ isset($item['tglregistrasi2']['tglregistrasi']) ? $item['tglregistrasi2']['tglregistrasi']: '' }}" alt=""><br> --}}
                    <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['namapasien2']) ? $item['namapasien2'] : '' }}</span>
                @elseif(is_string($item['tglregistrasi2']))
                <img src="data:image/png;base64, {!! $item['ttePasien'] !!}"><br>
                    <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['namapasien2']) ? $item['namapasien2'] : '' }}</span>
                @else
                    <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">Invalid Date</span>
                @endif
            </td>
            <td style="text-align:center;max-width: 20px;border: 1px solid black;">
                <img src="data:image/png;base64, {!! $item['tteDokter'] !!}"><br>
                <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['namadokter2']['label']) ? $item['namadokter2']['label'] : '' }}</span>
            </td>
            <td style="text-align:center;max-width: 20px;border: 1px solid black;">
                @if(isset($item['namaterapis2']) && $item['namaterapis2'] !== '' && $item['tteTerapis'] != '')
                <img src="data:image/png;base64, {!! $item['tteTerapis'] !!}"><br>
                @endif
                <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['namaterapis2']['label']) ? $item['namaterapis2']['label'] : '' }}</span>
            </td>
        </tr>
        @endforeach
        @endif
    </table>
</body>

</html>
