<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Perintah Rawat Inap</title>

    <style>
        @page {
            padding: 0;
        }

        /* * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        } */

        .block {
            display: block;
        }

        .tebal {
            font-weight: bold
        }

        .inner-table,
        .inner-th,
        .inner-td {
            border: 1px solid black;
        }

        .inner-td {
            padding: 3px;
        }

        .normal {
            font-weight: normal;
            font-family: Arial;
        }

        .label-top {
            vertical-align: top;
        }

        .medium {
            font-size: 10.5pt;
        }

        hr {
            border: 0.5px solid black;
            margin: 1px;
        }

        .bg-red {
            background-color: rgb(255, 79, 79);
        }

        .bg-yellow {
            background-color: rgb(255, 237, 79);
        }

        .bg-green {
            background-color: rgb(79, 255, 79);
        }

        .styled-pre {
            font-weight: normal;
            font-family: Arial;
            font-size: 10.5;
            color: black;
            display: unset;
        }

        .tbl-data,
        .tbl-data tr,
        .tbl-data tr td {
            border: 1px solid black;
        }
    </style>
</head>

@php
    $kodeDepartemen = $data['registrasi']['objectdepartemenfk'] ?? 16;
    $getUsia = explode('thn', $data['pasien']['umur']);
@endphp
@php
use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<body>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse;">
        <thead>
            <tr>
                <th width="60%" style="border: 1px solid black">
                    <table style="width:100%;border-collapse: collapse">
                        <tr>
                            <td width="20%" style="text-align: right">
                                <img src="img/logo-rs.png" style="width: 50px; padding-left:5px"/>
                            </td>
                            <td>
                                <table style="border-collapse: collapse;" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td style=" text-align: center;padding:0px">
                                            <small style="text-transform:uppercase;font-size:9pt;display: block;"
                                                class="normal">Pemerintah Daerah
                                                Kota Cirebon</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="text-transform:uppercase;font-size:9pt;display: block;"
                                                class="normal">Dinas Kesehatan
                                            </small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="text-transform:uppercase;font-size:9pt;font-weight:bold;"
                                                class="normal">Rumah
                                                Sakit Daerah Gunung Jati</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="font-size:9pt;" class="normal">Jl. Kesambi No 56. Tlp(0231)
                                                206330-202444,
                                                Fax(0231)20633</small>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: center; padding:0px">
                                            <small style="font-size:9pt;" class="normal">Email :
                                                rsudgunungjati@cirebonkota.go.id</small>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
                <th width="40%" rowspan="3"
                    style="border-top: 1px solid black;vertical-align:baseline;position:relative;">
                    <table style="margin-top:3px; padding:0px;position:relative;top:1.5%" width="100%">
                        <tr>
                            <td style="width:35%;">
                                <span class="medium normal">Nomor RM</span>
                            </td>
                            <td style="width:2%;">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['nocm'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-top">
                                <span class="medium normal">Nama</span>
                            </td>
                            <td class="label-top">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['namapasien'] }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="medium normal">Tanggal Lahir</span>
                            </td>
                            <td>
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span
                                    class="medium tebal">{{ \Carbon\Carbon::parse($identitas['tgllahir'])->format('d-m-Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="medium normal">Jenis Kelamin</span>
                            </td>
                            <td>
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ $identitas['jeniskelamin'] }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th style="border: 1px solid black;text-align: center;border-bottom:none;position:relative;">
                    <span class=tebal
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">SURAT PERINTAH RAWAT INAP</span>
                </th>
            </tr>
        </thead>
    </table>
    <table width="100%">
        <tr>
            <td width="100%" colspan="4">
                <span style="font-size: 11pt;font-weight: bold">Mohon dirawat seorang penderita: </span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" height="10"></td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Nama Pasien</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['namaPasien']) ? $data['namaPasien'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Tanggal Lahir</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ \Carbon\Carbon::parse($identitas['tgllahir'])->format('d-m-Y') }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Pekerjaan</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;"></span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Diagnosa Masuk</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['diagnosaMasuk']) ? $data['diagnosaMasuk'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Indikasi Rawat</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['lokasi']) ? $data['lokasi'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Ruang Perawatan</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['namaruangan']['label']) ? $data['namaruangan']['label'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Keterangan</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['keteranganLainnya']) ? $data['keteranganLainnya'] : '' }}</span>
            </td>
        </tr>

        <tr style="font-size: 11pt;margin-top:40px;">
            <td width="20%"></td>
            <td width="1%"></td>
            <td width="79%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tbody style="font-size: 11pt">
                        <tr>
                            <td></td>
                            <td height="20" valign="bootom" width="50%" align="center"><span class="text-biasa">
                                    Dokter RSD Gunung Jati,
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px;"></td>
                            <td style="height: 50px;" align="center">
                                @if(isset($data['dokterDPJP']) && $data['dokterDPJP'] !== '')
                                @php
                                    $qrcode1 = base64_encode(QrCode::format('svg')->size(75)->generate($data['dokterDPJP']));
                                @endphp
                                <img src="data:image/svg+xml;base64, {{ $qrcode1 }}"/>
                            @else
                                <span style="color: rgb(21, 21, 21);">-</span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td width="50%" align="center">
                                  <span style="font-size: 11pt;">{{ isset($data['dokterDPJP']) ? $data['dokterDPJP'] : '' }}</span>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" style="text-align: center">
                <span style="font-size: 11pt;font-weight: bold">KONFIRMASI PETUGAS PENERIMAAN PASIEN RAWAT INAP (ADMISION): </span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Saat ini tanggal</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span class="text-normal"> {{ \Carbon\Carbon::parse(isset($data['tglDirawat']) ? $data['tglDirawat'] : '')->timezone('Asia/Jakarta')->format('d-m-Y h:i') }} WIB</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Ruang Rawat Penuh Untuk</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['ruanganPenuh']['label']) ? $data['ruanganPenuh']['label'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Pasien bisa dirawat di ruang rawat</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['ruangan']) ? $data['ruangan'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Kelas</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['kelas']) ? $data['kelas'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Kamar No</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['kamar']) ? $data['kamar'] : '' }}</span>
            </td>
        </tr>
        <tr style="font-size: 11pt;margin-top:40px;">
            <td width="20%"></td>
            <td width="1%"></td>
            <td width="79%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tbody style="font-size: 11pt">
                        <tr>
                            <td></td>
                            <td height="20" valign="bootom" width="50%" align="center"><span class="text-biasa">
                                    Petugas TPP,
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px;"></td>
                            <td style="height: 50px;" align="center">
                                @if(isset($data['petugasRanap']['label']) && $data['petugasRanap']['label'] !== '')
                                @php
                                    $qrcode1 = base64_encode(QrCode::format('svg')->size(75)->generate($data['petugasRanap']['label']));
                                @endphp
                                <img src="data:image/svg+xml;base64, {{ $qrcode1 }}"/>
                            @else
                                <span style="color: rgb(21, 21, 21);">-</span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td width="50%" align="center">
                                  <span style="font-size: 11pt;">{{ isset($data['petugasRanap']['label']) ? $data['petugasRanap']['label'] : '' }}</span>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="3">
                <div style="height: 235px;"></div>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4" style="text-align: center">
                <span style="font-size: 11pt;font-weight: bold">KARENA TEMPAT INI PENUH MOHON SURAT INI DIKEMBALIKAN KEPADA DOKTER YANG MERAWAT, UNTUK MENDAPAT TINDAK LANJUT: </span>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan="4">
                <table width="100%">
                    <tr>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['pasienAkanDirujuk']) && $data['pasienAkanDirujuk'] ? 'checked' : '' }} />
                            <span style="font-size: 11pt;">Pasien akan dirujuk</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['pasienIgd']) && $data['pasienIgd'] ? 'checked' : '' }}/>
                            <span style="font-size: 11pt;">Pasien tetap di igd dengan surat pernyataan</span>
                        </td>
                        <td width="15%">
                            <input type="checkbox" {{ isset($data['pasienTunggu']) && $data['pasienTunggu'] ? 'checked' : '' }} />
                            <span style="font-size: 11pt;">Mohon untuk didaftarkan sebagai pasien daftar tunggu / reservasi</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr style="font-size: 11pt;margin-top:40px;">
            <td width="20%"></td>
            <td width="1%"></td>
            <td width="79%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tbody style="font-size: 11pt">
                        <tr>
                            <td></td>
                            <td height="20" valign="bootom" width="50%" align="center"><span class="text-biasa">
                                    Dokter,
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px;"></td>
                            <td style="height: 50px;" align="center">
                                @if(isset($data['dokterRSD']['label']) && $data['dokterRSD']['label'] !== '')
                                @php
                                    $qrcode1 = base64_encode(QrCode::format('svg')->size(75)->generate($data['dokterRSD']['label']));
                                @endphp
                                <img src="data:image/svg+xml;base64, {{ $qrcode1 }}"/>
                            @else
                                <span style="color: rgb(21, 21, 21);">-</span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td></td>
                            <td width="50%" align="center">
                                  <span style="font-size: 11pt;">{{ isset($data['dokterRSD']['label']) ? $data['dokterRSD']['label'] : '' }}</span>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Saat ini tanggal</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span class="text-normal"> {{ \Carbon\Carbon::parse(isset($data['tglPembuatanTiga']) ? $data['tglPembuatanTiga'] : '')->timezone('Asia/Jakarta')->format('d-m-Y h:i') }} WIB</span>
            </td>
        </tr>

        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Pasien bisa dirawat di ruang rawat</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['dokterRuangan']) ? $data['dokterRuangan'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Kelas</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['dokterKelas']) ? $data['dokterKelas'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td width="20%">
                <span style="font-size: 11pt;">Kamar No</span>
            </td>
            <td width="1%">
                <span style="font-size: 11pt;">:</span>
            </td>
            <td width="79%">
                <span style="font-size: 11pt;">{{ isset($data['dokterKamar']) ? $data['dokterKamar'] : '' }}</span>
            </td>
        </tr>
        <tr style="font-size: 11pt;margin-top:40px;">
            <td width="20%"></td>
            <td width="1%"></td>
            <td width="79%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tbody style="font-size: 11pt">
                        <tr>
                            <td></td>
                            <td height="20" valign="bootom" width="50%" align="center"><span class="text-biasa">
                                    Petugas TPP,
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 50px;"></td>
                            <td style="height: 50px;" align="center">
                                @if(isset($data['petugasRanap2']['label']) && $data['petugasRanap2']['label'] !== '')
                                @php
                                    $qrcode1 = base64_encode(QrCode::format('svg')->size(75)->generate($data['petugasRanap2']['label']));
                                @endphp
                                <img src="data:image/svg+xml;base64, {{ $qrcode1 }}"/>
                            @else
                                <span style="color: rgb(21, 21, 21);">-</span>
                            @endif
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td width="50%" align="center">
                                  <span style="font-size: 11pt;">{{ isset($data['petugasRanap2']['label']) ? $data['petugasRanap2']['label'] : '' }}</span>
                            </td>
                        </tr>
                    <tbody>
                </table>
            </td>
        </tr>
    </table>



</body>

</html>
