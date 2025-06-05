<!DOCTYPE html>
<html>
@php
$profile = App\Http\Controllers\Controller::static_profile();
@endphp

<head>
    <title>Cetak Hasil PAP SMEAR</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
    @else
    {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Montserrat:ital@0;1&family=Mukta&family=Poppins:wght@400&display=swap"
            rel="stylesheet"> --}}
    @endif

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <style>
        /* @font-face {
            font-family:'Poppins', sans-serif;
            src: url('fonts/Poppins-Bold.ttf');
        } */
        :root {
            /* font-family: 'Montserrat', sans-serif;
            font-family: 'Poppins', sans-serif; */
            /* --font:Tahoma,Verdana,Segoe,sans-serif;  */
            --font: Arial, Helvetica, sans-serif;
        }
    </style>

    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
    <link rel="stylesheet" href="css/paper.css ">
    <link rel="stylesheet" href="css/table-v2.css">
    {{-- <link rel="stylesheet" href="css/tabel.css"> --}}
    <link rel="stylesheet" href="css/style.css">
    <style>
        body,
        td,
        th,
        span,
        p {
            font-family: Arial, Helvetica, sans-serif !important;
        }
    </style>
    @else
    {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
    <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('css/tabel.css') }}"> --}}
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    {{-- @else
            <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
    <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
    <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
    <link rel="stylesheet" href="{{ asset('service/css/style.css') }}">
    @endif --}}
    @endif


</head>
<style type="text/css" media="print">
    @media print {
        @page {
            size: 215 mm 140 mm landscape;
            margin: 0;
            /* size: portrait; */
        }

        footer {
            display: none
        }

        header {
            display: none
        }


    }
</style>
<style>
    body,
    td,
    th,
    span,
    p {
        font-family: Tahoma, Geneva, sans-serif;
        !important;
        font-size: 11px;
    }

    tr td {
        padding: 2px 4px 2px 4px;
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    .baris1 {
        border: 2px solid #000000;
    }

    .baris2 {
        border: 1px solid #000000;
    }

    .garishalus {
        border: 0.01em solid #9a9a9a;
    }

    .garishalus tr td {
        border: 0.01em solid #9a9a9a;
        /* border: thin solid #9a9a9a; */
    }

    .garis6 td {
        padding: 3px !important;
    }

    .table-parent {
        border: 2px solid #353434;
        border-collapse: collapse;
    }

    .none-top {
        border-top: none;
    }

    .table-bordered tr {}

    .table-bottom tr td {
        width: 33%;
    }

    .merah {
        color: red;
    }
    .custom-checkbox {
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 2px solid #555;
        outline: none;
        cursor: pointer;
        background-color: transparent;
    }

    .custom-checkbox::before {
        content: '\2713';
        display: block;
        width: 12px;
        height: 12px;
        line-height: 12px;
        text-align: center;
        border-radius: 50%;
        background: white;
        transition: background 0.3s, color 0.3s;
        font-size: 15   px;
        color: transparent;
    }

    .custom-checkbox:checked::before {
        color: black;
    }
</style>

@if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))

<body style="margin: 0">
    @else
    @if (isset($print) && $print == true)

    <body style="background-color: #CCCCCC;margin: 0" onLoad="window.print()">
        @else

        <body style="background-color: #CCCCCC;margin: 0">
            @endif
            @endif

            <body>
                @yield('page-style')
                <div align="center">
                    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                    <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:3px">
                        @else
                        <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:25px" width="{{ $pageWidth }}">
                            @endif
                            <tbody>
                                <tr>
                                    <td>
                                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td rowspan="4">
                                                    <p class="text-right" style="position: relative; top: -20px;">
                                                        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                                        <img src="{{ 'img/logo-rs.png' }}" width="60px" border="0">
                                                        @else
                                                        {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
                                                        <img src="{{ asset('img/logo-rs.png') }}" width="20px" border="0">
                                                        {{-- @else
                                            <img src="{{ asset('service/img/logo-rs.png') }}" width="80px" border="0">
                                                        @endif --}}
                                                        @endif
                                                    </p>
                                                </td>
                                                <td class="text-center" style="text-align: center;padding-left: 60px">
                                                    <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                                        RUMAH SAKIT DAERAH GUNUNG JATI
                                                    </span>
                                                </td>
                                                <td rowspan="4" class="text-center" style="text-align: left; padding-left: 60px;">
                                                    <table style="position: relative; top: -10px;">
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">No. Sediaan</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">:</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">{{$data->nopemerpapsmear}}</span><br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">No. Lab</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">:</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">{{$data->noorder}}</span><br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">No. Register</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">:</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">{{$data->noregistrasi}}</span><br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">Tgl Terima</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">:</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">{{\Carbon\Carbon::parse($data->tglTerima)->isoFormat('DD MMMM Y')}}</span><br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">Tgl Jawab</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">:</span><br>
                                                            </td>
                                                            <td>
                                                                <span style="font-size: 9pt; font-weight: 10; letter-spacing: 0px; color: #000000;">{{\Carbon\Carbon::parse($data->tglJawab)->isoFormat('DD MMMM Y')}}</span><br>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- <tr>
                            <td class="text-center">
                                <span style="font-size: 14pt;font-weight: 600;color:#000000">PEMERINTAH KOTA BANDUNG
                                </span>
                            </td>
                        </tr> --}}
                                            <tr>
                                                <td class="text-center" style="text-align: center;padding-left: 60px">
                                                    <span style="font-size: 10pt;color:#000000">
                                                        Jalan Kesambi 56 Cirebon
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="text-center" style="text-align: center;padding-left: 60px">
                                                    <span style="font-size: 10pt;color:#000000">
                                                        Telp. 206330 - 202444 - 202544 Fax. 203336
                                                    </span>
                                                </td>
                                            </tr>
                                        </table>
                                        <span style="font-size: 12pt; font-weight: bold; text-align: center; display: block;">PEMERIKSAAN PAP SMEAR (BETHESDA SYSTEM)</span>
                                    </td>
                                </tr>
                                <tr>
                                <tr>
                                    <td>
                                        <div class="table-container">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent">
                                                <tr>
                                                    <td style="padding-left: 10px;padding-top: 10px;padding-bottom:7px;" border="1">
                                                        <font style="font-size: 9pt" color="#000000">Dokter Pengirim</font>
                                                    </td>
                                                    <td width="30%" style="padding-top: 10px; padding-bottom:7px;border-right: 1px solid black;">
                                                        <font style="font-size: 9pt" color="#000000">: {{$data->dokter_order}}</font>
                                                    </td>
                                                    <td style="padding-top: 10px; padding-bottom:7px;">
                                                        <font style="font-size: 9pt" color="#000000">Nama Pasien</font>
                                                    </td>
                                                    <td style="padding-right: 10px;padding-top: 10px; padding-bottom:7px;">
                                                        <font style="font-size: 9pt" color="#000000">: {{$data->namapasien}}</font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-left: 10px;padding-bottom:7px;" border="1">
                                                        <font style="font-size: 9pt" color="#000000">YTh. Ts. Dr.</font>
                                                    </td>
                                                    <td width="30%" style=" padding-bottom:7px;border-right: 1px solid black;">
                                                        <font style="font-size: 9pt" color="#000000">: {{$data->namalengkap}}</font>
                                                    </td>
                                                    <td style="padding-bottom:7px;">
                                                        <font style="font-size: 8pt;" color="#000000">Umur</font>
                                                    </td>
                                                    <td style="padding-bottom:7px;">
                                                        <font style="font-size: 9pt" color="#000000">: {{$data->umur}}</font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom:7px;padding-left: 10px;">
                                                        <font style="font-size: 9pt" color="#000000">Alamat</font>
                                                    </td>
                                                    <td style="padding-bottom:7px;border-right: 1px solid black;">
                                                        <font style="font-size: 9pt" color="#000000">: Gunung Jati</font>
                                                    </td>
                                                    <td style="padding-bottom:7px;">
                                                        <font style="font-size: 8pt;" color="#000000">Alamat</font>
                                                    </td>
                                                    <td style="padding-bottom:7px;">
                                                        <font style="font-size: 9pt" color="#000000">: {{$data->alamatlengkap}}</font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom:7px;padding-left: 10px;">
                                                        <font style="font-size: 9pt" color="#000000">Di</font>
                                                    </td>
                                                    <td style="padding-bottom:7px;border-right: 1px solid black;">
                                                        <font style="font-size: 9pt" color="#000000">: Cirebon</font>
                                                    </td>
                                                    <td style="padding-top: 10px; padding-bottom:7px;">
                                                    </td>
                                                    <td style="padding-right: 10px;padding-top: 10px; padding-bottom:7px;">
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%">
                                                <tr>
                                                    <td style="padding-left: 10px;padding-top: 10px;padding-bottom:7px;">
                                                        <font style="font-size: 7pt" color="#000000">ADEKUASI BAHAN PEMERIKSAAN</font>
                                                    </td>
                                                    <td style="padding-top: 10px;padding-bottom:7px;">
                                                        <font style="font-size: 7pt" color="#000000">KATEGORI UMUM</font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="70%" style="padding-bottom: 7px; padding-left: 10px; vertical-align: top;">
                                                        <input type="checkbox" {{ isset($data->adekuasiPemeriksaanMemuaskan) && $data->adekuasiPemeriksaanMemuaskan == 'true' ? 'checked' : '' }} />
                                                        <span class="medium{{ isset($data->adekuasiPemeriksaanMemuaskan) && $data->adekuasiPemeriksaanMemuaskan == 'true' ? ' merah' : '' }}" style="font-size: 10px; position: relative; top: -7px;">Memuaskan untuk evaluasi,</span><br>
                                                        <input type="checkbox" {{ isset($data->adekuasiPemeriksaanTidakMemuaskan) && $data->adekuasiPemeriksaanTidakMemuaskan == 'true' ? 'checked' : '' }} />
                                                        <span class="medium{{ isset($data->adekuasiPemeriksaanTidakMemuaskan) && $data->adekuasiPemeriksaanTidakMemuaskan == 'true' ? ' merah' : '' }}" style="font-size: 10px; position: relative; top: -7px;">Tidak Memuaskan untuk evaluasi,</span> <br>
                                                        @if (isset($data->ketedukasi) && $data->ketedukasi)
                                                            <span class="medium" style="font-size: 10px; position: relative; top: -7px;">Keterangan edukasi : {{$data->ketedukasi ?? "-" }}</span>
                                                        @endif
                                                    </td>
                                                    <td width="30%" style="padding-bottom:7px;">
                                                        <input type="checkbox" {{ isset($data->kategoriumumnegatifless) && $data->kategoriumumnegatifless == 'true' ? 'checked' : '' }} />
                                                        <span class="medium{{ isset($data->kategoriumumnegatifless) && $data->kategoriumumnegatifless == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Negatif lesi intrapitelial dan keganasan</span><br>
                                                        <input type="checkbox" {{ isset($data->kategoriumumabnormal) && $data->kategoriumumabnormal == 'true' ? 'checked' : '' }} />
                                                        <span class="medium{{ isset($data->kategoriumumabnormal) && $data->kategoriumumabnormal == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Abnormalitas sel epitel</span><br>
                                                        <input type="checkbox" {{ isset($data->kategoriumumlain) && $data->kategoriumumlain == 'true' ? 'checked' : '' }} />
                                                        <span class="medium{{ isset($data->kategoriumumlain) && $data->kategoriumumlain == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Lain-lain : Sel Endometrium >= 40 th</span><br>
                                                        <input type="checkbox" {{ isset($data->kategoriumumneoplasma) && $data->kategoriumumneoplasma == 'true' ? 'checked' : '' }} />
                                                        <span class="medium{{ isset($data->kategoriumumneoplasma) && $data->kategoriumumneoplasma == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Neoplasma Ganas Lain</span>
                                                    </td>
                                                </tr>
                                            </table>
                                            <span style="font-weight:bold;font-size: 12px;position: relative;top:-10px;margin-left: 13px">DIAGNOSIS DESKRIPTIF</span>
                                            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent">
                                                <thead>
                                                    <tr>
                                                        <th style="border: 1px solid black;">
                                                            Negatif Lesi Intraepital dan keganasan
                                                        </th>
                                                        <th style="border: 1px solid black;">
                                                            Abnormalitas Sel Epitel
                                                        </th>
                                                        <th style="border: 1px solid black;">
                                                            Neoplasma Ganas Lain
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td style="border: 1px solid black;vertical-align: top;">
                                                            <span style="font-weight: bold;"> INFEKSI / ORGANISME </span><br>
                                                            <input type="checkbox" {{ isset($data->infeksiorganismetrikomonas) && $data->infeksiorganismetrikomonas == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->infeksiorganismetrikomonas) && $data->infeksiorganismetrikomonas == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Trikomonas vaginalis</span> <br>
                                                            <input type="checkbox" {{ isset($data->infeksiorganismekandida) && $data->infeksiorganismekandida == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->infeksiorganismekandida) && $data->infeksiorganismekandida == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Kandida sp</span><br>
                                                            <input type="checkbox" {{ isset($data->infeksiorganismekokobasili) && $data->infeksiorganismekokobasili == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->infeksiorganismekokobasili) && $data->infeksiorganismekokobasili == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Kokobasili</span><br>
                                                            <input type="checkbox" {{ isset($data->infeksiorganismeaktinomises) && $data->infeksiorganismeaktinomises == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->infeksiorganismeaktinomises) && $data->infeksiorganismeaktinomises == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Aktinomises sp</span><br>
                                                            <input type="checkbox" {{ isset($data->infeksiorganismevirusherpes) && $data->infeksiorganismevirusherpes == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->infeksiorganismevirusherpes) && $data->infeksiorganismevirusherpes == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Virus Herpes simpleks</span><br>

                                                            <span style="font-weight: bold;"> NON NEOPLASTIK LAIN </span><br>
                                                            <input type="checkbox" {{ isset($data->nonneoplastikperubahanreaktif) && $data->nonneoplastikperubahanreaktif == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->nonneoplastikperubahanreaktif) && $data->nonneoplastikperubahanreaktif == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Perubahan Reaktif</span> <br>
                                                            <input style="margin-left: 15px;" type="checkbox" {{ isset($data->nonNeoPlastikPerubahanReaktifPeradangan) && $data->nonNeoPlastikPerubahanReaktifPeradangan == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->nonNeoPlastikPerubahanReaktifPeradangan) && $data->nonNeoPlastikPerubahanReaktifPeradangan == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Perdangan</span><br>
                                                            <input style="margin-left: 15px;" type="checkbox" {{ isset($data->nonNeoPlastikPerubahanReaktifRadiasi) && $data->nonNeoPlastikPerubahanReaktifRadiasi == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->nonNeoPlastikPerubahanReaktifRadiasi) && $data->nonNeoPlastikPerubahanReaktifRadiasi == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Radiasi</span><br>
                                                            <input style="margin-left: 15px;" type="checkbox" {{ isset($data->nonNeoPlastikPerubahanReaktifIud) && $data->nonNeoPlastikPerubahanReaktifIud == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->nonNeoPlastikPerubahanReaktifIud) && $data->nonNeoPlastikPerubahanReaktifIud == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">IUD</span><br>

                                                            <input type="checkbox" {{ isset($data->nonneoplastikselepitel) && $data->nonneoplastikselepitel == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->nonneoplastikselepitel) && $data->nonneoplastikselepitel == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Sel epitel kelenjar jinak post histerektomi</span> <br>
                                                            <input type="checkbox" {{ isset($data->nonneoplastikatrofi) && $data->nonneoplastikatrofi == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->nonneoplastikatrofi) && $data->nonneoplastikatrofi == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Atrofi ( ) peradangan</span>
                                                        </td>
                                                        <td style="border: 1px solid black;">
                                                            <span style="font-weight: bold;"> SEL SKUAMOSA </span><br>
                                                            <input type="checkbox" {{ isset($data->selskuamosaaktipik) && $data->selskuamosaaktipik == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosaaktipik) && $data->selskuamosaaktipik == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Sel Skuamosa Atipik</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosaaktipikacsus) && $data->selskuamosaaktipikacsus == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosaaktipikacsus) && $data->selskuamosaaktipikacsus == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Tidak dapat ditentukan artinya ( ASC-US : Atypical Squanamos Cell of understimed significance)</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosaaktipikacsh) && $data->selskuamosaaktipikacsh == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosaaktipikacsh) && $data->selskuamosaaktipikacsh == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">ASC-H ( Atypical sguamous cell, cannot exlude HSIL )</span><br>

                                                            <input type="checkbox" {{ isset($data->selskuamosalesiintraepitelial) && $data->selskuamosalesiintraepitelial == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelial) && $data->selskuamosalesiintraepitelial == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Lesi intraepitelial skuamosa derajat rendah (LSIL)</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosalesiintraepitelialhpv) && $data->selskuamosalesiintraepitelialhpv == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialhpv) && $data->selskuamosalesiintraepitelialhpv == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">HPV (Human Papiloma Virus)</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosalesiintraepitelialcin1) && $data->selskuamosalesiintraepitelialcin1 == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialcin1) && $data->selskuamosalesiintraepitelialcin1 == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Displasia ringan / CIN 1</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosalesiintraepitelialcin1infeksi) && $data->selskuamosalesiintraepitelialcin1infeksi == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialcin1infeksi) && $data->selskuamosalesiintraepitelialcin1infeksi == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Displasia ringan / CIN 1 dengan infeksi HPV</span><br>

                                                            <input type="checkbox" {{ isset($data->selskuamosalesiintraepitelialhsil) && $data->selskuamosalesiintraepitelialhsil == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialhsil) && $data->selskuamosalesiintraepitelialhsil == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Lesi intraepitelial skuamosa derajat tinggi (HSIL)</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosalesiintraepitelialhsilcin2) && $data->selskuamosalesiintraepitelialhsilcin2 == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialhsilcin2) && $data->selskuamosalesiintraepitelialhsilcin2 == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Displasia sedang /CIN 2</span>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selSkuamosaLesiIntraePitelialHsilCis) && $data->selSkuamosaLesiIntraePitelialHsilCis == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selSkuamosaLesiIntraePitelialHsilCis) && $data->selSkuamosaLesiIntraePitelialHsilCis == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Karsinoma in situ /CIS</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosalesiintraepitelialhsilcin3) && $data->selskuamosalesiintraepitelialhsilcin3 == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialhsilcin3) && $data->selskuamosalesiintraepitelialhsilcin3 == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Displasia berat / CIN 3</span>
                                                            <input style="margin-left: 22px;" class="custom-checkbox" type="checkbox" {{ isset($data->selskuamosalesiintraepitelialhsilinvasi) && $data->selskuamosalesiintraepitelialhsilinvasi == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selskuamosalesiintraepitelialhsilinvasi) && $data->selskuamosalesiintraepitelialhsilinvasi == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Kecurigaan Invasi</span><br>

                                                            <input type="checkbox" {{ isset($data->selSkuamosaKarsinomaSel) && $data->selSkuamosaKarsinomaSel == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selSkuamosaKarsinomaSel) && $data->selSkuamosaKarsinomaSel == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Karsinoma sel skuamosa</span><br>

                                                            <span style="font-weight: bold;"> SEL GLANDULAR </span><br>
                                                            <input type="checkbox" {{ isset($data->selglandularAntipik) && $data->selglandularAntipik == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selglandularAntipik) && $data->selglandularAntipik == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Atipik</span>
                                                            <input style="margin-left: 10px;" class="custom-checkbox" type="checkbox" {{ isset($data->selglandularAntipikEndoserviks) && $data->selglandularAntipikEndoserviks == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selglandularAntipikEndoserviks) && $data->selglandularAntipikEndoserviks == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Endoserviks</span>
                                                            <input style="margin-left: 10px;" class="custom-checkbox" type="checkbox" {{ isset($data->selglandularAntipikEndometrium) && $data->selglandularAntipikEndometrium == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selglandularAntipikEndometrium) && $data->selglandularAntipikEndometrium == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Endometrium</span>
                                                            <input style="margin-left: 10px;" class="custom-checkbox" type="checkbox" {{ isset($data->selglandularAntipikGlandular) && $data->selglandularAntipikGlandular == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selglandularAntipikGlandular) && $data->selglandularAntipikGlandular == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Glandular</span><br>

                                                            <input type="checkbox" {{ isset($data->selGlandularAntipikFavor) && $data->selGlandularAntipikFavor == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAntipikFavor) && $data->selGlandularAntipikFavor == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Atipik</span>
                                                            <input style="margin-left: 10px;" class="custom-checkbox" type="checkbox" {{ isset($data->selGlandularAntipikFavorEndoserviks) && $data->selGlandularAntipikFavorEndoserviks == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAntipikFavorEndoserviks) && $data->selGlandularAntipikFavorEndoserviks == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Endoserviks, kemungkinan neoplasma</span><br>
                                                            <input style="margin-left: 57px;" class="custom-checkbox" type="checkbox" {{ isset($data->selGlandularAntipikFavorGlandular) && $data->selGlandularAntipikFavorGlandular == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAntipikFavorGlandular) && $data->selGlandularAntipikFavorGlandular == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Glandular, kemungkinan neoplasma</span><br>

                                                            <input type="checkbox" {{ isset($data->selGlandularAdenokarsinomaIsSitu) && $data->selGlandularAdenokarsinomaIsSitu == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAdenokarsinomaIsSitu) && $data->selGlandularAdenokarsinomaIsSitu == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Adenokarsinoma in situ serviks</span><br>

                                                            <input type="checkbox" {{ isset($data->selGlandularAdenokarsinoma) && $data->selGlandularAdenokarsinoma == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAdenokarsinoma) && $data->selGlandularAdenokarsinoma == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Adenokarsinoma</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selGlandularAdenokarsinomaEndoserviks) && $data->selGlandularAdenokarsinomaEndoserviks == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAdenokarsinomaEndoserviks) && $data->selGlandularAdenokarsinomaEndoserviks == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Endoserviks</span>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selGlandularAdenokarsinomaExtra) && $data->selGlandularAdenokarsinomaExtra == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAdenokarsinomaExtra) && $data->selGlandularAdenokarsinomaExtra == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Extra Uterine</span><br>
                                                            <input style="margin-left: 15px;" class="custom-checkbox" type="checkbox" {{ isset($data->selGlandularAdenokarsinomaEndometrium) && $data->selGlandularAdenokarsinomaEndometrium == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAdenokarsinomaEndometrium) && $data->selGlandularAdenokarsinomaEndometrium == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Endometrium</span>
                                                            <input style="margin-left: 11px;" class="custom-checkbox" type="checkbox" {{ isset($data->selGlandularAdenokarsinomaTidakDapat) && $data->selGlandularAdenokarsinomaTidakDapat == 'true' ? 'checked' : '' }} />
                                                            <span class="medium{{ isset($data->selGlandularAdenokarsinomaTidakDapat) && $data->selGlandularAdenokarsinomaTidakDapat == 'true' ? ' merah' : '' }}" style="font-size: 10px;position: relative;top:-7px">Tidak dpat ditemukan asalnya</span>
                                                        </td>
                                                        <td style="border: 1px solid black;vertical-align: top;">
                                                            <input type="checkbox" {{ isset($data->neoplasmaGanas) && strlen($data->neoplasmaGanas) > 0 ? 'checked' : '' }} /><br>
                                                            <span class="medium" style="font-size: 10px;position: relative;top:-7px">{{$data->neoplasmaGanas}}</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <table width="100%" style="border-left: 2px solid black; border-bottom: 2px solid black; border-right: 2px solid black">
                                                <tr>
                                                    <td width="50%" style="vertical-align: top;">
                                                        <span class="medium" style="font-size: 12px;font-weight: bold;">KESIMPULAN :</span><br>
                                                        <span class="medium" style="font-size: 12px;">{{$data->kesimpulan}}</span>
                                                    </td>
                                                    <td width="50%" style="vertical-align: top;border-left: 1px solid black;">
                                                        <span class="medium" style="font-size: 12px;font-weight: bold;">SARAN :</span><br>
                                                        <span class="medium" style="font-size: 12px;">{{$data->saran}}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table width="100%">
                                                <tr>
                                                    <td width="50%">
                                                    </td>
                                                    <td width="50%" style="text-align: center">
                                                        <span class="medium" style="font-size: 11px;">Cirebon, {{\Carbon\Carbon::parse($data->tglJawab)->isoFormat('DD MMMM Y')}}</span><br>
                                                        <img src="data:image/png;base64, {{base64_encode(QrCode::format('svg')->size(75)->generate($data->namalengkap))}}"><br>
                                                        <span>{{$data->namalengkap}}</span>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </td>
                                </tr>
                                </tr>

                            </tbody>
                        </table>
                </div>
            </body>

</html>
