<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Lembar Formulir Rawat Jalan Layanan Kedokteran Fisik dan Rehabilitasi</title>
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
                    <span style="font-size: 16pt;font-weight: 600;letter-spacing: 2px;color:#000000"><b>
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
        <tbody>
            <tr>
                <td>
                    <table width="70%" cellspacing="0" cellpadding="0" style="margin-left: 190px;">
                        <thead style="display: none"></thead>
                        <tbody>
                            <tr>
                                <td width="50%" class="text-center" style="border:2px solid black;">
                                    <span class="text-judul" style="font-size: 11pt">Lembar Formulir Rawat Jalan</span><br>
                                    <span class="text-judul" style="font-size: 11pt">Layanan Kedokteran Fisik dan Rehabilitasi</span>
                                </td>
                                <td width="20%" class="text-center">
                                    <span class="text-judul" style="font-size: 11pt">B</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 30px;border:2px solid black;">
                        <thead style="display: none"></thead>
                        <tbody>
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;">
                                        <td width="15%">
                                            <span style="margin-left: 10px;" class="text-normal">I.</span>
                                        </td>
                                        <td width="45%">
                                            <span class="text-normal">Diisi Oleh Pasien/Peserta</span>
                                        </td>
                                        <td width="40%">
                                            <span class="text-normal">No.RM/Reg : {{ $identitas['nocm'] }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%" cellspacing="0" cellpadding="6" style="margin-top: 7px;">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">Nama Pasien</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['namapasien'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">Tanggal Lahir</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['tgllahir'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">Alamat</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['alamat'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">Telp/HP</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['nohp'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">Hubungan dengan tertanggung</span>
                                        </td>
                                        <td width="50%">:
                                            <input type="checkbox" {{ isset($identitas['hubunganDenganTertanggungSuamiIstri']) && $identitas['hubunganDenganTertanggungSuamiIstri'] == 'true' ? 'checked' : '' }} />
                                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Suami/Istri</span>
                                            <input type="checkbox" {{ isset($identitas['hubunganDenganTertanggungAnak']) && $identitas['hubunganDenganTertanggungAnak'] == 'true' ? 'checked' : '' }} />
                                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Anak</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 30px;border:2px solid black;">
                        <thead style="display: none"></thead>
                        <tbody>
                            <table width="100%" cellspacing="0" cellpadding="0">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;">
                                        <td width="15%">
                                            <span style="margin-left: 10px;" class="text-normal">II.</span>
                                        </td>
                                        <td width="45%">
                                            <span class="text-normal">Diisi oleh Dokter SpKFR</span>
                                        </td>
                                        <td width="40%">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%" cellspacing="0" cellpadding="0" style="margin-top: 7px;">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;">
                                        <td width="51%">
                                            <span style="margin-left: 10px;" class="text-normal">Tanggal Pelayanan</span>
                                        </td>
                                        <td width="49%">
                                            <span class="text-normal">: {{\Carbon\Carbon::parse($identitas['tglPelayanan'])->isoFormat('DD MMMM Y')}}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="95%" cellspacing="0" cellpadding="6" style="margin-top: 7px;">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="20%"></td>
                                        <td width="40%" style="border-left:2px solid black;border-top:2px solid black;">
                                            <span class="text-normal">* Auamnesia</span>
                                        </td>
                                        <td width="30%" style="border-top:2px solid black;">
                                            <span class="text-normal">: {{ $identitas['auamnesa'] }}</span>
                                        </td>
                                        <td width="5%" style="border-top:2px solid black;border-right:2px solid black;">
                                            <span class="text-normal">A</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%" style="border-bottom:2px solid black;border-left:2px solid black;">
                                            <span class="text-normal">* Pemeriksaan Fisik dan Uji Fungsi</span>
                                        </td>
                                        <td width="50%" style="border-bottom:2px solid black;">
                                            <span class="text-normal">: {{ $identitas['pemeriksaanFisikUjiFungsi'] }}</span>
                                        </td>
                                        <td width="5%" style="border-bottom:2px solid black;border-right:2px solid black;">

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="100%" cellspacing="0" cellpadding="6" style="margin-top: 7px;">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">* Diagnosis Medis (ICD-10)</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['diagnosa'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">* Pemeriksaan Penunjang</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['pemeriksaanPenunjang'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">* Tata Laksana KFR (ICD 9CM)</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['tataLaksanaKFR'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">* Anjuran</span>
                                        </td>
                                        <td width="50%">
                                            <span class="text-normal">: {{ $identitas['anjuran'] }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="95%" cellspacing="0" cellpadding="6" style="margin-top: 7px;">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="20%"></td>
                                        <td width="42%" style="border-top:2px solid black;border-bottom:2px solid black;border-left:2px solid black;">
                                            <span class="text-normal">* Evaluasi</span>
                                        </td>
                                        <td width="50%" style="border-top:2px solid black;border-bottom:2px solid black;">
                                            <span class="text-normal">: {{ $identitas['evaluasi'] }}</span>
                                        </td>
                                        <td width="5%" style="border-top:2px solid black;border-bottom:2px solid black;border-right:2px solid black;">
                                            <span class="text-normal">D</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table width="95%" cellspacing="0" cellpadding="6" style="margin-top: 7px;">
                                <thead style="display: none"></thead>
                                <tbody>
                                    <tr style="font-size: 11pt;margin-top:40px;">
                                        <td width="15%"></td>
                                        <td width="35%">
                                            <span class="text-normal">* Suspek Penyakit Akibat Kerja</span>
                                        </td>
                                        <td width="50%">:
                                            <input type="checkbox" {{ isset($identitas['suspekPenyakitAkibatKerjaYa']) && $identitas['suspekPenyakitAkibatKerjaYa'] == 'true' ? 'checked' : '' }} />
                                            <span class="medium" style="font-size: 12px; position: relative; top: -7px">Ya</span>

                                            @if(isset($identitas['suspekPenyakitAkibatKerjaYa']) && $identitas['suspekPenyakitAkibatKerjaYa'] == 'true')
                                            <span class="medium" style="font-size: 12px; position: relative; top: -7px">
                                                ({{ $identitas['suspekPenyakitAkibatKerjaYaKeterangan'] }})
                                            </span>
                                            @endif

                                            <br>

                                            <input style="margin-left: 10px;" type="checkbox" {{ isset($identitas['suspekPenyakitAkibatKerjaTidak']) && $identitas['suspekPenyakitAkibatKerjaTidak'] == 'true' ? 'checked' : '' }} />
                                            <span class="medium" style="font-size: 12px; position: relative; top: -7px">Tidak</span>

                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </tbody>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px">
        <tbody style="font-size: 11pt">
            <tr>
                <td width="50%">
                </td>
                <td width="50%" class="text-center">
                    <span class="text-biasa">{{ \Carbon\Carbon::parse(isset($identitas['tglTTDDOkter']) ? $identitas['tglTTDDOkter'] : '')->isoFormat('DD MMMM Y')}}</span>
                </td>
            </tr>
            <tr>
                <td height="20" valign="bootom" width="50%" class="text-center"><span class="text-biasa">
                        Tanda Tangan Pasien
                    </span>
                </td>
                <td height="20" valign="bootom" width="50%" class="text-center"><span class="text-biasa">
                        Tanda Tangan Dokter,
                    </span>
                </td>
            </tr>
            <tr>
                <td width="50%" align="center">
                    <img src="data:image/png;base64, {!! $ttePasien !!}">   
                </td>
                <td width="50%" align="center">
                    <img src="data:image/png;base64, {!! $tte !!}">                
                </td>
            </tr>
            <tr>
                <td height="30" valign="bottom" height="100" width="15%" class="text-center">
                    <span class="text-biasa">{{ $identitas['namaPasien'] }}</span>
                </td>
                <td height="30" valign="bottom" height="100" width="15%" class="text-center">
                    <span class="text-biasa">{{ isset($identitas['dokterttd']['label']) ? $identitas['dokterttd']['label'] : '' }}</span>
                </td>
            </tr>
            <tr>
                <td>
                </td>
                <td width="25%" class="text-center">
                    <span class="text-biasa" style="border-top:2px solid black;">SIP.{{ $identitas['nosipDokterTtd'] }}</span>
                </td>
            </tr>
        <tbody>
    </table>
</body>

</html>