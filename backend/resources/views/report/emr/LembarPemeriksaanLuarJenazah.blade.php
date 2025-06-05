@extends('template.head-emr')
@section('title', 'EMR - Lembar Monitoring Rehabilitasi Medik')
@section('about', 'Lembar Monitoring Rehabilitasi Medik')

@push('style')
    {{-- <style>
        span {
            font-size: 11pt;
        }
    </style> --}}
@endpush

@section('content')

    <style>
        @page {
            padding: 0;
            margin-top: 5rem;
            margin-bottom: 3rem;
        }

        small {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .block {
            display: block;
        }

        .salinan {
            width: auto;
            border: 1px solid black;
            padding: 1px 17px;
            margin-bottom: .6rem;
            margin-top: -2rem;
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

        .styled-pre {
            font-weight: normal;
            font-family: Arial;
            font-size: 10.5;
            color: black;
            display: unset;
        }

        li.nostyle {
            list-style-type: none;
        }
    </style>

    <table style="border: 1px solid black;width:100%;border-collapse: collapse;border-bottom:none">
        <thead>
            <tr>
                <th width="60%" style="border: 1px solid black">
                    <table style="width:100%;border-collapse: collapse">
                        <tr>
                            <td width="20%" style="text-align: right">
                                <img src="https://rsdgunungjati.com/images/simrs/logo-rs.png"
                                    style="width: 50px; padding-left:5px">
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
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">LEMBAR
                        PEMERIKSAAN LUAR JENAZAH</span>
                </th>
            </tr>
        </thead>
    </table>

    <table cellpadding="5" cellspacing="0" width="100%" style="border: 1px solid black;border-collapse: collapse;border-bottom:none;font-size:9pt;">
        <tr>
            <td colspan="6">
                1. Tutup/Bungkus mayat dari luar ke dalam :
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6" style="padding-left: 23px;">
                {{ isset($data['pemeriksaanLuar1']) ? $data['pemeriksaanLuar1'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="6">2. Benda di samping mayat :</td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6" style="padding-left: 23px;">
                {{ isset($data['pemeriksaanLuar2']) ? $data['pemeriksaanLuar2'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="6">3. Perhiasan yang dipakai oleh mayat :</td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6" style="padding-left: 23px;">
                {{ isset($data['pemeriksaanLuar3']) ? $data['pemeriksaanLuar3'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="6">4. Mayat menggunakan pakaian : (Sebutkan dengan lengkap, jenis pakaian, warna dasar, corak,
                robekan, urutan dari luar ke dalam)</td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6" style="padding-left: 23px;">
                {{ isset($data['pemeriksaanLuar4']) ? $data['pemeriksaanLuar4'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">5. - Kaku mayat terdapat pada :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar5']) ? $data['pemeriksaanLuar5'] : '-' }}
            </td>
            <td>

                <?php
                if (isset($data['pemeriksaanLuar5Lengkap'])) {
                    if ($data['pemeriksaanLuar5Lengkap'] == 'Belum') {
                ?>

                <label for="belum">&#9745; Belum Lengkap</label>
                <label for="sudah">&#9744; Sudah Lengkap</label>
                <?php
                    } else {
                        ?>
                <label for="belum">&#9744; Belum Lengkap</label>
                <label for="sudah">&#9745; Sudah Lengkap</label>
                <?php
                    }
                }
                ?>
            </td>
            <td>
                <?php
                if (isset($data['pemeriksaanLuar5dilawan'])) {
                    if ($data['pemeriksaanLuar5dilawan'] == 'Mudah') {
                ?>
                <label for="mudah">&#9745; Mudah Dilawan</label>
                <label for="sukar">&#9744; Sukar Dilawan</label>
                <?php
                    } else {
                        ?>
                <label for="mudah">&#9744; Mudah Dilawan</label>
                <label for="sukar">&#9745; Sukar Dilawan</label>
                <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Lebam mayat terdapat pada :</td>
            <td colspan="4">
                {{ isset($data['pemeriksaanLuar5Lebam']) ? $data['pemeriksaanLuar5Lebam'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Warna :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar5Warna']) ? $data['pemeriksaanLuar5Warna'] : '-' }}
            </td>
            <td colspan="2">
                <?php
                if (isset($data['pemeriksaanLuar5Penekanan'])) {
                    if ($data['pemeriksaanLuar5Penekanan'] == 'hilang') {
                ?>
                <label for="mudah">&#9745; Hilang Pada Penekanan</label>
                <label for="sukar">&#9744; Tidak Hilang Pada Penekanan</label>
                <?php
                    } else {
                        ?>
                <label for="mudah">&#9744; Hilang Pada Penekanan</label>
                <label for="sukar">&#9745; Tidak Hilang Pada Penekanan</label>
                <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Kulit perut kanan bawah :</td>
            <td colspan="4">
                <?php
                if (isset($data['pemeriksaanLuar5Kulit'])) {
                    if ($data['pemeriksaanLuar5Kulit'] == 'Sudah') {
                ?>
                <label for="mudah">&#9745; Sudah Tampak warna biru kehijauan</label>
                <label for="sukar">&#9744; Belum Tampak warna biru kehijauan</label>
                <?php
                    } else {
                        ?>
                <label for="mudah">&#9744; Sudah Tampak warna biru kehijauan</label>
                <label for="sukar">&#9745; Belum Tampak warna biru kehijauan</label>
                <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="2" style="padding-left: 23px;">- Keterangan lain :</td>
            <td colspan="4">
                {{ isset($data['pemeriksaanLuar5Lain']) ? $data['pemeriksaanLuar5Lain'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">6. - Mayat adalah seorang :</td>
            <td colspan="4">
                <?php
                if (isset($data['pemeriksaanLuar6'])) {
                    if ($data['pemeriksaanLuar6'] == 'Laki-laki') {
                ?>
                <label for="mudah">&#9745; Laki-laki</label>
                <label for="sukar">&#9744; Perempuan</label>
                <?php
                    } else {
                        ?>
                <label for="mudah">&#9744; Laki-laki</label>
                <label for="sukar">&#9745; Perempuan</label>
                <?php
                    }
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Berumur sekitar :</td>
            <td>{{ isset($data['pemeriksaanLuar6Thn']) ? $data['pemeriksaanLuar6Thn'] : '0' }} Tahun</td>
            <td>{{ isset($data['pemeriksaanLuar6Bln']) ? $data['pemeriksaanLuar6Bln'] : '0' }} Bulan</td>
            <td colspan="2"></td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Warna Kulit :</td>
            <td colspan="4">{{ isset($data['pemeriksaanLuar6Warna']) ? $data['pemeriksaanLuar6Warna'] : '-' }}</td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Gizi :</td>
            <td colspan="4">
                {{ isset($data['pemeriksaanLuar6Gizi']) && $data['pemeriksaanLuar6Gizi'] == 'Baik' ? '☑ Baik' : '☐ Baik' }}
                {{ isset($data['pemeriksaanLuar6Gizi']) && $data['pemeriksaanLuar6Gizi'] == 'Cukup' ? '☑ Cukup' : '☐ Cukup' }}
                {{ isset($data['pemeriksaanLuar6Gizi']) && $data['pemeriksaanLuar6Gizi'] == 'Kurang' ? '☑ Kurang' : '☐ Kurang' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Zakar :</td>
            <td colspan="4">
                {{ isset($data['pemeriksaanLuar6Zakar']) && $data['pemeriksaanLuar6Zakar'] == 'Sunat' ? '☑ Sunat' : '☐ Sunat' }}
                {{ isset($data['pemeriksaanLuar6Zakar']) && $data['pemeriksaanLuar6Zakar'] == 'Tidak' ? '☑ Tidak Sunat' : '☐ Tidak Sunat' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="2" style="padding-left: 23px;">- Panjang Badan :</td>
            <td colspan="2">{{ isset($data['pemeriksaanLuar6PB']) ? $data['pemeriksaanLuar6PB'] : '0' }} Cm</td>
            <td>- Berat Badan :</td>
            <td>{{ isset($data['pemeriksaanLuar6BB']) ? $data['pemeriksaanLuar6BB'] : '0' }} Kg</td>
        </tr>
        <tr>
            <td colspan="6">
                7. Identifikasi Khusus : (Kelainan bawaan, cacat tubuh, jaringan parut luka, tattoo dll)
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6" style="padding-left: 23px;">
                {{ isset($data['pemeriksaanLuar7']) ? $data['pemeriksaanLuar7'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">8. - Rambut-rambut :</td>
            <td colspan="4">
                {{ isset($data['pemeriksaanLuar8Rambut']) && $data['pemeriksaanLuar8Rambut'] == 'Mudah' ? '☑ Mudah Dicabut' : '☐ Mudah Dicabut' }}
                {{ isset($data['pemeriksaanLuar8Rambut']) && $data['pemeriksaanLuar8Rambut'] == 'Tidak' ? '☑ Tidak Mudah Dicabut' : '☐ Tidak Mudah Dicabut' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Rambut kepala warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambut']) ? $data['pemeriksaanLuar8WarnaRambut'] : '-' }}</td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatRambut']) && $data['pemeriksaanLuar8LebatRambut'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatRambut']) && $data['pemeriksaanLuar8LebatRambut'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang {{ isset($data['pemeriksaanLuar8PanjangRambut']) ? $data['pemeriksaanLuar8PanjangRambut'] : '0' }}
                Cm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Alis mata warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutAlis']) ? $data['pemeriksaanLuar8WarnaRambutAlis'] : '-' }}</td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatAlis']) && $data['pemeriksaanLuar8LebatAlis'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatAlis']) && $data['pemeriksaanLuar8LebatAlis'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang {{ isset($data['pemeriksaanLuar8PanjangAlis']) ? $data['pemeriksaanLuar8PanjangAlis'] : '0' }} Cm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Bulu mata warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutBulu']) ? $data['pemeriksaanLuar8WarnaRambutBulu'] : '-' }}</td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatBulu']) && $data['pemeriksaanLuar8LebatBulu'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatBulu']) && $data['pemeriksaanLuar8LebatBulu'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang {{ isset($data['pemeriksaanLuar8PanjangBulu']) ? $data['pemeriksaanLuar8PanjangBulu'] : '0' }} Cm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Kumis warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutKumis']) ? $data['pemeriksaanLuar8WarnaRambutKumis'] : '-' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatKumis']) && $data['pemeriksaanLuar8LebatKumis'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatKumis']) && $data['pemeriksaanLuar8LebatKumis'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang {{ isset($data['pemeriksaanLuar8PanjangKumis']) ? $data['pemeriksaanLuar8PanjangKumis'] : '0' }} Cm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Jenggot warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutJenggot']) ? $data['pemeriksaanLuar8WarnaRambutJenggot'] : '-' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatJenggot']) && $data['pemeriksaanLuar8LebatJenggot'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatJenggot']) && $data['pemeriksaanLuar8LebatJenggot'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang
                {{ isset($data['pemeriksaanLuar8PanjangJenggot']) ? $data['pemeriksaanLuar8PanjangJenggot'] : '0' }} Cm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Rambut Ketiak warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutJenggot']) ? $data['pemeriksaanLuar8WarnaRambutJenggot'] : '-' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatJenggot']) && $data['pemeriksaanLuar8LebatJenggot'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatJenggot']) && $data['pemeriksaanLuar8LebatJenggot'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang
                {{ isset($data['pemeriksaanLuar8PanjangJenggot']) ? $data['pemeriksaanLuar8PanjangJenggot'] : '0' }} Cm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">- Rambut Kemaluan warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutKemaluan']) ? $data['pemeriksaanLuar8WarnaRambutKemaluan'] : '-' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatKemaluan']) && $data['pemeriksaanLuar8LebatKemaluan'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatKemaluan']) && $data['pemeriksaanLuar8LebatKemaluan'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang
                {{ isset($data['pemeriksaanLuar8PanjangKemaluan']) ? $data['pemeriksaanLuar8PanjangKemaluan'] : '0' }} Cm
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="2" style="padding-left: 23px;">- Rambut Tungkai warna :</td>
            <td>{{ isset($data['pemeriksaanLuar8WarnaRambutTungkai']) ? $data['pemeriksaanLuar8WarnaRambutTungkai'] : '-' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatTungkai']) && $data['pemeriksaanLuar8LebatTungkai'] == 'Lebat' ? '☑ Tumbuh Lebat' : '☐ Tumbuh Lebat' }}
            </td>
            <td>
                {{ isset($data['pemeriksaanLuar8LebatTungkai']) && $data['pemeriksaanLuar8LebatTungkai'] == 'Jarang' ? '☑ Jarang' : '☐ Jarang' }}
            </td>
            <td>
                Panjang
                {{ isset($data['pemeriksaanLuar8PanjangTungkai']) ? $data['pemeriksaanLuar8PanjangTungkai'] : '0' }} Cm
            </td>
        </tr>
        <tr>
            <td colspan="2">9. Kepala bentuk :</td>
            <td colspan="4">
                {{ isset($data['pemeriksaanLuar9KepalaBentuk']) && $data['pemeriksaanLuar9KepalaBentuk'] == 'Simetris' ? '☑ Simetris' : '☐ Simetris' }}
                {{ isset($data['pemeriksaanLuar9KepalaBentuk']) && $data['pemeriksaanLuar9KepalaBentuk'] == 'Asimetris' ? '☑ Asimetris' : '☐ Asimetris' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">Mata</td>
            <td colspan="2">
                Mata kanan
                {{ isset($data['pemeriksaanLuar9MataKanan']) && $data['pemeriksaanLuar9MataKanan'] == 'Tertutup' ? '☑ Tertutup' : '☐ Tertutup' }}
                {{ isset($data['pemeriksaanLuar9MataKanan']) && $data['pemeriksaanLuar9MataKanan'] == 'Terbuka' ? '☑ Terbuka' : '☐ Terbuka' }}
                <br>Diameter
                :{{ isset($data['pemeriksaanLuar9MataKananMM']) ? $data['pemeriksaanLuar9MataKananMM'] : '0' }} mm
            </td>
            <td colspan="2">
                Mata kiri
                {{ isset($data['pemeriksaanLuar9MataKiri']) && $data['pemeriksaanLuar9MataKiri'] == 'Tertutup' ? '☑ Tertutup' : '☐ Tertutup' }}
                {{ isset($data['pemeriksaanLuar9MataKiri']) && $data['pemeriksaanLuar9MataKiri'] == 'Terbuka' ? '☑ Terbuka' : '☐ Terbuka' }}
                <br>Diameter :{{ isset($data['pemeriksaanLuar9MataKiriMM']) ? $data['pemeriksaanLuar9MataKiriMM'] : '0' }}
                mm
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">Selaput kelopak mata warna :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananKelopak']) ? $data['pemeriksaanLuar9MataKananKelopak'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKiriKelopak']) ? $data['pemeriksaanLuar9MataKiriKelopak'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">Selaput bola mata warna :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananBola']) ? $data['pemeriksaanLuar9MataKananBola'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKiriKBola']) ? $data['pemeriksaanLuar9MataKiriKBola'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">Selaput bening mata warna :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananBening']) ? $data['pemeriksaanLuar9MataKananBening'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKiriKBening']) ? $data['pemeriksaanLuar9MataKiriKBening'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">Tirai mata warna :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananTirai']) ? $data['pemeriksaanLuar9MataKananTirai'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKiriKTirai']) ? $data['pemeriksaanLuar9MataKiriKTirai'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="6" style="padding-left: 23px;">Teleng mata :</td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 23px;">a. Bentuk :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananBentuk']) ? $data['pemeriksaanLuar9MataKananBentuk'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananBentuk']) ? $data['pemeriksaanLuar9MataKananBentuk'] : '-' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="2" style="padding-left: 23px;">b. Diameter :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananDiameter']) ? $data['pemeriksaanLuar9MataKananDiameter'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar9MataKananDiameter']) ? $data['pemeriksaanLuar9MataKananDiameter'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">10. Hidung :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10Hidung']) && $data['pemeriksaanLuar10Hidung'] == 'Simetris' ? '☑ Simetris' : '☐ Simetris' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10Hidung']) && $data['pemeriksaanLuar10Hidung'] == 'Asimetris' ? '☑ Asimetris' : '☐ Asimetris' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">- Lubang kanan :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10HidungKanan']) && $data['pemeriksaanLuar10HidungKanan'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar10HidungKanan']) && $data['pemeriksaanLuar10HidungKanan'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10HidungKananKet']) ? $data['pemeriksaanLuar10HidungKananKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">- Lubang kiri :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10HidungKiri']) && $data['pemeriksaanLuar10HidungKiri'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar10HidungKiri']) && $data['pemeriksaanLuar10HidungKiri'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10HidungKiriKet']) ? $data['pemeriksaanLuar10HidungKiriKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">Telinga :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10Telinga']) && $data['pemeriksaanLuar10Telinga'] == 'Simetris' ? '☑ Simetris' : '☐ Simetris' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10Telinga']) && $data['pemeriksaanLuar10Telinga'] == 'Asimetris' ? '☑ Asimetris' : '☐ Asimetris' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">- Lubang kanan :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10TelingaKanan']) && $data['pemeriksaanLuar10TelingaKanan'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar10TelingaKanan']) && $data['pemeriksaanLuar10TelingaKanan'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10TelingaKet']) ? $data['pemeriksaanLuar10TelingaKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">- Lubang kiri :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10TelingaKiri']) && $data['pemeriksaanLuar10TelingaKiri'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar10TelingaKiri']) && $data['pemeriksaanLuar10TelingaKiri'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10TelingaKiriKet']) ? $data['pemeriksaanLuar10TelingaKiriKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">Mulut</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10Mulut']) && $data['pemeriksaanLuar10Mulut'] == 'Tertutup' ? '☑ Tertutup' : '☐ Tertutup' }}
                {{ isset($data['pemeriksaanLuar10Mulut']) && $data['pemeriksaanLuar10Mulut'] == 'Terbuka' ? '☑ Terbuka' : '☐ Terbuka' }}
                <br>Ket: {{ isset($data['pemeriksaanLuar10MulutKet']) ? $data['pemeriksaanLuar10MulutKet'] : '-' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10MulutTampak']) && $data['pemeriksaanLuar10MulutTampak'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar10MulutTampak']) && $data['pemeriksaanLuar10MulutTampak'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
                <br>Ket:
                {{ isset($data['pemeriksaanLuar10MulutTampakKet']) ? $data['pemeriksaanLuar10MulutTampakKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">Bibir</td>
            <td colspan="2">
                Warna {{ isset($data['pemeriksaanLuar10BibirWarna']) ? $data['pemeriksaanLuar10BibirWarna'] : '-' }}
            </td>
            <td colspan="2">
                Tebal {{ isset($data['pemeriksaanLuar10BibirTebal']) ? $data['pemeriksaanLuar10BibirTebal'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">Lidah</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10Lidah']) && $data['pemeriksaanLuar10Lidah'] == 'Menjulur' ? '☑ Menjulur' : '☐ Menjulur' }}
                {{ isset($data['pemeriksaanLuar10Lidah']) && $data['pemeriksaanLuar10Lidah'] == 'Tergigit' ? '☑ Tergigit' : '☐ Tergigit' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10LidahKet']) ? $data['pemeriksaanLuar10LidahKet'] : '-' }} cm dari
                permukaan gigi
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">Gigi geligi :</td>
            <td colspan="4">
                Jumlah {{ isset($data['pemeriksaanLuar10Gigi']) ? $data['pemeriksaanLuar10Gigi'] : '0' }}
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 28px;">Tulang rahang :</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10RahangKelainan']) && $data['pemeriksaanLuar10RahangKelainan'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar10RahangKet']) ? $data['pemeriksaanLuar10RahangKet'] : '-' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6">
                <pre>
    Keterangan: X = Tanggal / Copot
        O = Berlubang
        V = Tinggal akar
        # = Patah
        Y = Belum / Tidak tumbuh

        1.8  1.7  1.6  1.5  1.4  1.3  1.2  1.1  :  2.1  2.2  2.3  2.4  2.5  2.6  2.7  2.8 
        ---------------------------------------------------------------------------------
        4.8  4.7  4.6  4.5  4.4  4.3  4.2  4.1  :  3.1  3.2  3.3  3.4  3.5  3.6  3.7  3.8
                </pre>
            </td>
        </tr>
        <tr >
            <td colspan="2">11. Dari lubang kemaluan</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar11KemaluanKelainan']) && $data['pemeriksaanLuar11KemaluanKelainan'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar11KemaluanKelainan']) && $data['pemeriksaanLuar11KemaluanKelainan'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
            </td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar11KemaluanKelainanKet']) ? $data['pemeriksaanLuar11KemaluanKelainanKet'] : '-' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="2" style="padding-left: 28px;">Dari lubang Pelepasan</td>
            <td colspan="2">
                {{ isset($data['pemeriksaanLuar11KemaluanPelepasanKelainan']) && $data['pemeriksaanLuar11KemaluanPelepasanKelainan'] == 'Tidak' ? '☑ Tidak tampak kelainan' : '☐ Tidak tampak kelainan' }}
                {{ isset($data['pemeriksaanLuar11KemaluanPelepasanKelainan']) && $data['pemeriksaanLuar11KemaluanPelepasanKelainan'] == 'Keluar' ? '☑ Keluar' : '☐ Keluar' }}
            </td>
            <td colspan="2" tyle="padding-left: 28px;">
                {{ isset($data['pemeriksaanLuar11KemaluanPelepasanKet']) ? $data['pemeriksaanLuar11KemaluanPelepasanKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="6">12. Luka-luka : ( Sebutkan secara sistematik: Regio, Koordinat, Jenis, Ukuran, Arah, Bentuk,
                Tepi, Resapan darah, Jembatan jaringan, Dalam, Dasar, Benda asing, sekitar dll )</td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="6" style="padding-left: 28px;">
                {{ isset($data['pemeriksaanLuar12']) ? $data['pemeriksaanLuar12'] : '-' }}
            </td>
        </tr>

    </table>

    {{-- {{ dd($data) }} --}}
    {{ dd() }}

@endsection
