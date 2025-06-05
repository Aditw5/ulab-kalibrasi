<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Diagnosa Keperawatan</title>
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
                <table width="50%" cellspacing="0" cellpadding="0" style="margin-left: 190px;">
                    <thead style="display: none"></thead>
                    <tbody>
                        <tr>
                            <td width="50%" class="text-center" style="border:1px solid black;">
                                <span class="text-judul" style="font-size: 11pt">Diagnosa Keperawatan</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </table>
    <table cellspacing="7" cellpadding="7" style="border-collapse: collapse; border: 1px solid black;" width="100%">
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
        </tbody>
    </table>
    <table cellspacing="7" cellpadding="7" style="margin-top: 10px ;border-collapse: collapse; border: 1px solid black;" width="100%">
        <tr>
            <th width="10%" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">No.</span>
            </th>
            <th width="70%" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">Diagnosa</span>
            </th>
            <th width="20%" style="border: 1px solid black;">
                <span class="medium tebal" style="font-size: 12px;">Prioritas</span>
            </th>
        </tr>
        @if (isset($data['diagnosisKerja']) && is_array($data['diagnosisKerja']))
            @foreach ($data['diagnosisKerja'] as $item)
                <tr>
                    <td style="border: 1px solid black; text-align: center;">
                        {{ $item['no'] ?? '-' }}
                    </td>
                    <td style="border: 1px solid black;">
                        @if (isset($item['diagnosakeperawatan']) && is_array($item['diagnosakeperawatan']))
                            <ul style="margin: 5px; padding-left: 15px;">
                                @foreach ($item['diagnosakeperawatan'] as $key => $value)
                                    @if (strtolower($key) === 'diagnosakep')
                                        <span class="medium tebal" style="font-size: 12px;">{{ $value }}</span>
                                    @endif
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>                    
                    <td style="border: 1px solid black; text-align: center;">
                        {{ $item['prioritas'] ?? '-' }}
                    </td>
                </tr>
            @endforeach
        @endif
    </table>    
    {{-- {{ dd($data) }} --}}

    
</body>

</html>
