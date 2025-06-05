<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lembar Masuk Keluar</title>

    <style>
        @media print {
            td.merah {
                background-color: #d54242 !important;
                -webkit-print-color-adjust: exact;
            }

            td.kuning {
                background-color: #c5d542 !important;
                -webkit-print-color-adjust: exact;
            }

            td.hijau {
                background-color: #42d55b !important;
                -webkit-print-color-adjust: exact;
            }

            td.hitam {
                background-color: #000000 !important;
                -webkit-print-color-adjust: exact;
            }

            @page {
                size: 215mm 275mm;
            }

                {
                width: 215mm 275mm;
            }

        }


        /*@media print {*/
        /*    body {margin:0}*/
        /*}*/
        .double-border {

            border: 4px solid #000;

        }

        .double-border:before {

            border: 4px solid #fff;

        }

        .box {
            border: 2px solid black;
            /*border-radius: 6px;*/
        }

        .garis6 td {
            padding: 3px;
        }

        .bold {
            font-weight: bold;
        }

        .f-s-15 {
            font-size: 12px;
        }

        .top-height {
            height: 50px;
            vertical-align: text-top;
            width: 15%;
        }

        .text-top {
            vertical-align: text-top;
        }

        table {
            width: 100%;
        }

        .hitam {
            background-color: #000000 !important;
        }

        .table-ex {
            /* border: 1px solid black; */
            border-collapse: collapse !important;
            width: 100%;
        }

        .table-sub {
            padding: 5px;
            border: 1px black;
            border-collapse: collapse;
            width: 100%;
        }

        .tr-sub,
        .th-sub {
            /* padding:3px; */
            text-align: center;
            border: 1px solid black;
        }

        .th-ex,
        td.ex {
            border: 1px solid black;
            padding: 2px;
            padding-top: 0px;
            text-align: left;
        }

        .label {
            font-size: 11px;
            color: #000000;
        }

        .label-sub {
            font-size: 13px;
            font-weight: 600;
            color: #000000;
        }
        .coret{
            text-decoration: line-through;
        }
    </style>
</head>

@php
    // $kodeDepartemen = $data['registrasi']['objectdepartemenfk'] ?? 16;
@endphp

<body>
    <section class="" style="font-family:Tahoma;overflow: hidden;">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <thead>
                <tr>
                    <th style=" text-align: center">
                        <span
                            style="font-size: 14pt;font-weight: 500;letter-spacing: 4px; font-family:Times New Roman;text-transform: uppercase;"
                            color="#000000">
                            Rumah Sakit Umum Daerah Gunung Jati
                        </span>
                    </th>
                </tr>
                <tr>
                    <th style=" text-align: center;padding-top:5px">
                        <span style="font-size: 11pt;font-weight: 500;letter-spacing: 1px;" color="#000000">
                            Jl.Kesambi No 56 Kodepos : 45134
                        </span>
                    </th>
                </tr>
            </thead>
        </table>
        <table width="100%">
            <thead>
                <tr>
                    <th width="80%" style=" text-align: center;padding-left: 9rem;">
                        <span style="font-size: 13pt;font-weight: 500;letter-spacing: 1px; font-family:Times New Roman"
                            color="#000000">
                            Tlp.0231263330. Faxs.0231203336
                        </span>
                    </th>
                    <th width="20%" style=" text-align: center">
                        <span style="font-size: 17pt;font-weight: 600;letter-spacing: 1px;" color="#000000">
                            {{ $data['registrasi']['kelompokpasien'] }}
                        </span>
                    </th>
                </tr>
            </thead>
        </table>
        <hr style="border:2px solid #000;margin:unset">
        <table>
            <thead>
                <tr>
                    <th style="padding:5px;">
                        <span style="font-size: 12pt;font-weight: 500;letter-spacing: 1px;text-transform: uppercase"
                            color="#000000">
                            lembar masuk dan keluar
                        </span>
                    </th>
                </tr>
            </thead>
        </table>
        <table class="table-ex">
            <tr>
                <th width="50%" class="th-ex">
                    <table class="table-ex">
                        <tr>
                            <th width="30%">
                                <span style="font-size: 11pt; color:#000000;font-weight: 400">NAMA PASIEN</span>
                            </th>
                            <th>
                                <span style="font-size: 11pt; color:#000000;font-weight: 400">:</span>
                            </th>
                            <th>
                                <span style="font-size: 11pt; color:#000000">{{ $data['pasien']['namapasien'] }}</span>
                            </th>
                        </tr>
                    </table>
                </th>
                <th width="50%" class="th-ex">
                    <table class="table-ex">
                        <tr>
                            <th width="10%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">NO CM</span>
                            </th>
                            <th width="1%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">:</span>
                            </th>
                            <th width="15%">
                                <span style="font-size: 13px; color:#000000">{{ $data['pasien']['nocm'] }}</span>
                            </th>
                            <th width="15%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">No. Regis</span>
                            </th>
                            <th width="1%">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">:</span>
                            </th>
                            <th width="15%">
                                <span
                                    style="font-size: 13px; color:#000000">{{ $data['registrasi']['noregistrasi'] }}</span>
                            </th>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th rowspan="2" class="th-ex">
                    <table class="table-ex">
                        <tr>
                            <th width="10%" style="vertical-align: baseline;padding-top: 5px;">
                                <span style="font-size: 13px; color:#000000;font-weight: 400">UMUR</span>
                            </th>
                            <th width="30%">
                                <span>{{ $data['pasien']['umur'] }}</span>
                            </th>
                            <th width="25%">
                                <table>
                                    <tr style="text-align: center">
                                        <th>
                                            <span
                                                style="font-size: 13px; color:#000000;font-weight: 400">KELAMIN:</span>
                                        </th>
                                    </tr>
                                    <tr style="text-align: center">
                                        <td>
                                            <span style="font-weight: 100">{{ $data['pasien']['jeniskelamin'] }}</span>
                                        </td>
                                    </tr>
                                    <tr style="text-align: center">
                                        <td>
                                            <span {{-- style="font-weight: 100">{{ date('d/m/Y', strtotime($data['registrasi']['tgllahir'])) }}</span> --}} </td>
                                    </tr>
                                </table>
                            </th>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal">Pekerjaan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['pekerjaan'] }}</td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal">Agama</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['agama'] }}</td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Ayah</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['namaayah'] }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Ibu</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['namaibu'] }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Suami / Istri</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['namasuamiistri'] }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="25%" style="font-weight: normal">Pendidikan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['pendidikan'] }}</td>
                        </tr>
                        <tr>
                            <td width="25%" style="font-weight: normal">Cara Masuk RS</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['caraMasukPasien'] == 'Darurat' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Darurat</span>
                            </td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['caraMasukPasien'] == 'Biasa' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Biasa</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Status Perkawinan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['statusperkawinan'] }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kasus Polisi</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['kasusPolisi'] == 'Ya' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Ya</span>
                            </td>
                            <td width="40%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['kasusPolisi'] == 'Tidak' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Tidak</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="25%" style="font-weight: normal">Peserta PHB / ASKES</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['pesertaPHBS'] == 'Ya' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Ya</span>
                            </td>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['pesertaPHBS'] == 'Tidak' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Tidak</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Alamat</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['pasien']['alamatlengkap'] }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kampung</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['kampungPasien'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Desa</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['desaPasien'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kota</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-transform: uppercase">{{ $data['kotaPasien'] ?? '' }}
                            </td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Dirujuk Oleh</td>
                            <td width="2%" style="font-weight: normal">:</td>
                        </tr>
                        <tr>
                            <td width="10%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['dirujukOleh'] == 'Rumah Sakit' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Rumah Sakit</span>
                            </td>
                            <td width="20%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['dirujukOleh'] == 'Puskesmas' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Puskesmas</span>
                            </td>
                            <td width="20%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['dirujukOleh'] == 'Dokter' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Dokter</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="30%" style="font-weight: normal">
                                <input type="checkbox" {{ $data['dirujukOleh'] == 'Paramedis' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Paramedis</span>
                            </td>
                            <td width="20%" style="font-weight: normal;position: absolute;">
                                <input type="checkbox"
                                    {{ $data['dirujukOleh'] == 'Kemauan Sendiri' ? 'checked' : '' }} />
                                <span style="font-size: 11px" color="#000000">Kemauan Sendiri</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex" rowspan="2">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Nama Penangung Jawab</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['penanggungjawabPasien'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Alamat</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['alamatPenanggungJawabPasien'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Desa</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['desaPenanggungJawabPasien'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kecamatan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['kecamatanPenanggungJawabPasien'] ?? '' }}</td>
                        </tr>
                        <tr>
                            <td width="40%" style="font-weight: normal">Kota</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['kotaPenanggungJawabPasien'] ?? '' }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="15%" style="font-weight: normal">Tanggal Masuk</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="20%" style="font-weight: normal">
                                {{ isset($data['tglMasukPasien']) ? \Carbon\Carbon::parse($data['tglMasukPasien'])->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '' }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="15%" style="font-weight: normal">Tanggal Keluar</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="20%" style="font-weight: normal">
                                {{ isset($data['tglKeluarPasien']) ? \Carbon\Carbon::parse($data['tglKeluarPasien'])->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '' }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Ruang</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['ruanganRawat']['label'] ?? '' }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="15%" style="font-weight: normal">Tanggal Meninggal</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td width="20%" style="font-weight: normal">
                                {{ isset($data['tglMeninggalPasien']) ? \Carbon\Carbon::parse($data['tglMeninggalPasien'])->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') : '' }}
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="10%" style="font-weight: normal">Kelas</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['registrasi']['namakelas'] }}</td>
                        </tr>
                        <tr>
                            <td width="10%" style="font-weight: normal">UPP</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td>{{ $data['uppPasien'] }}</td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td width="40%" style="font-weight: normal">Lama Dirawat</td>
                            <td width="20%">{{ $data['lamaRawatPasien'] }}</td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th class="th-ex">
                    <table>
                        <tr>
                            <td style="font-weight: normal">Diagnosa Masuk</td>
                        </tr>
                    </table>
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal">Utama</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            {{-- <td>{{ $data['registrasi']['diagawal_nama'] }}</td> --}}
                        </tr>
                        <tr>
                            <td width="20%" style="font-weight: normal">Tambahan</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                    </table>
                </th>
                <th class="th-ex" style="margin: 0px;padding:0px">
                    <table width="100%" style="border-collapse: collapse;">
                        <tr>
                            <td rowspan="2" colspan="2" width="33%"
                                style="border-right: 1px solid black;padding: 8px;">
                                <span>Kepala Ruangan</span>
                            </td>
                            <td
                                style="border-right: 1px solid black;text-align:center;border-bottom: 1px solid black;">
                                <span>Nama</span>
                            </td>
                            <td style="text-align:center;border-bottom: 1px solid black;">
                                <span>Paraf</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-right: 1px solid black;padding:5px">
                                <span>{{ $data['kepalaRuangan']['label'] ?? '' }}</span>
                            </td>
                            <td style="padding:5px">
                                <span></span>
                            </td>
                        </tr>
                    </table>
                </th>

            </tr>
        </table>
        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <th rowspan="3" width="30%" style="border-right:1px solid black">
                    <table>
                        <tr>
                            <td>
                                <span style="font-weight: normal;text-transform: uppercase">Diagnosa Akhir</span>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="font-weight: normal">(Jangan Disingkat, Ditulis dengan huruf CETAK)</span>
                            </td>
                        </tr>
                    </table>
                </th>
                <th width="40%" style="border-right:1px solid black;border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal;text-align: left;">Utama</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td style="text-align: left"></td>
                        </tr>
                    </table>
                </th>
                <th style="border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="28%" style="font-weight: normal">No Code</td>
                            <td width="2%" style="font-weight: normal">:</td>
                            <td></td>
                        </tr>
                    </table>
                </th>
            </tr>
            <tr>
                <th width="40%" style="border-right:1px solid black;border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal;vertical-align: top;">Tambahan</td>
                            <td width="2%" style="font-weight: normal;vertical-align: top;">:</td>
                            <td style="text-align: left">
                                <table>
                                    <tr>
                                        <td style="text-align: left;font-weight: normal">1</td>
                                    </tr>
                                    <tr>
                                        <td style="text-align: left;font-weight: normal">2</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
                <th style="border-bottom:1px solid black">

                </th>
            </tr>
            <tr>
                <th width="40%" style="border-right:1px solid black;border-bottom:1px solid black">
                    <table>
                        <tr>
                            <td width="20%" style="font-weight: normal;vertical-align: top;">Komplikasi</td>
                            <td width="2%" style="font-weight: normal;vertical-align: top;">:</td>
                            <td style="text-align: left">
                                <table>
                                    <tr>
                                        <td style="text-align: left;font-weight: normal"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </th>
                <th style="border-bottom:1px solid black">

                </th>
            </tr>
        </table>

        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            <tr>
                <td style="border-right: 1px solid black;padding:3px">
                    <span style="font-weight: normal;text-transform: uppercase">Nama Operasi Biopsi</span>
                </td>
                <td style="border-right: 1px solid black;text-align:center;padding:3px">
                    <span style="font-weight: normal;text-transform: uppercase;">tanggal</span>
                </td>
                <td>
                    <span style="font-weight: normal;padding:3px">No. Code</span>
                </td>
            </tr>
        </table>
        <table width="100%"
            style="border-collapse: collapse; border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black">
            {{-- {{dd($data)}} --}}
            @foreach ($data['namaBiopsi'] as $index => $item)
                <tr>
                    <td style="padding:10px;border-bottom:1px solid black">
                        <span>{{ $index + 1 }}</span>
                    </td>
                    <td style="padding:10px;border-bottom:1px solid black">
                        <span>{{ $item['namaTindakanBiopsi']['label'] }}</span>
                    </td>
                </tr>
            @endforeach
        </table>
        <table width="100%" style="border-collapse: collapse; border:1px solid;">
            <tr>
                <th style="border-right:1px solid black;text-align:left;padding:8px;vertical-align: top;"
                    width="40%">
                    <p style="font-weight: normal;text-transform: uppercase;font-size:11px;margin:0px">Keadaan Keluar
                    </p>
                    <div style="display:flex;justify-content: space-between;padding-top: 5px;">
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['keadaanKeluar'] !== 'Sembuh' ? 'coret' : ''}}">1. Sembuh</span>
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['keadaanKeluar'] !== 'Meninggal <48 Jam' ? 'coret' : ''}}"> 4. Meninggal < 48
                                Jam </span>
                    </div>
                    <div style="display:flex;justify-content: space-between;">
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['keadaanKeluar'] !== 'Berobat Jalan' ? 'coret' : ''}}"> 2. BerobatJalan
                        </span>
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['keadaanKeluar'] !== 'Meninggal >48 Jam' ? 'coret' : ''}}"> 5. Meninggal >48
                            Jam
                        </span>
                    </div>
                    <div style="display:flex;justify-content: space-between;">
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['keadaanKeluar'] !== 'Cacat' ? 'coret' : ''}}">3. Cacat</span>
                        <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['keadaanKeluar'] !== 'Lain-lain' ? 'coret' : ''}}">6. Lain-lain</span>
                    </div>
                </th>

                <th style="border-right:1px solid black;text-align:left;padding:8px;vertical-align: top;"
                    width="25%">
                    <p style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;margin:0px">
                        Cara Keluar</p>
                    <span
                        style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;padding-top: 5px;" class="{{$data['caraKeluar'] !== 'Izin Dokter' ? 'coret' : ''}}">1.
                        Izin Dokter</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block" class="{{$data['caraKeluar'] !== 'Permintaan Sendiri' ? 'coret' : ''}}">2.
                        Permintaan Sendiri</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px" class="{{$data['caraKeluar'] !== 'Dirujuk ke RS Lain' ? 'coret' : ''}}">3. Dirujuk ke RS
                        Lain</span>
                </th>
                <th style="text-align:left;padding:8px" width="35%">
                    <p style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;margin:0px">
                        Catatan Khusus</p>
                    <span
                        style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block;padding-top: 5px;" class="{{$data['catatanKhusus'] !== 'Alergi' ? 'coret' : ''}}">1.
                        Alergi</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block" class="{{$data['catatanKhusus'] !== 'Transfusi' ? 'coret' : ''}}">2.
                        Transfusi</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block" class="{{$data['catatanKhusus'] !== 'Gol Darah' ? 'coret' : ''}}">3. Gol
                        Darah</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block" class="{{$data['catatanKhusus'] !== 'Penyakit Berat' ? 'coret' : ''}}">4.
                        Penyakit Berat</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block" class="{{$data['catatanKhusus'] !== 'Penyakit Menular' ? 'coret' : ''}}">5.
                        Penyakit Menular</span>
                    <span style="font-weight: normal;text-transform: uppercase;font-size:11px;display:block" class="{{$data['catatanKhusus'] !== 'Lain-lain' ? 'coret' : ''}}">6.
                        DLL</span>
                </th>
            </tr>
        </table>
        <table width="100%" style="border: 1px solid black">
            <tr>
                <td>
                    <span>Nama Dokter Tanggung Jawab : </span>
                </td>
                <td style="text-align: center">
                    <img src="data:image/svg+xml;base64, {!! $tte !!}" alt="QR Code">
                    <br>
                    {{ $data['dpjpPasien']['label'] ?? '' }} <br>
                    {{ $identitas['nosip'] ?? '' }}
                </td>
            </tr>
        </table>
    </section>
</body>

</html>
