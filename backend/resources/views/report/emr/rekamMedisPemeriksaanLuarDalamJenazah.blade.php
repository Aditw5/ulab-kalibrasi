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
                        PEMERIKSAAN LUAR DAN DALAM JENAZAH</span>
                </th>
            </tr>
        </thead>
    </table>

    <table cellpadding="5" cellspacing="0" width="100%" style="border: 1px solid black;border-collapse: collapse;border-bottom:none;font-size:9pt;">
        <tr>
            <td colspan="6" class="medium tebal">
                PEMERIKSAAN LUAR
            </td>
        </tr>
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

    <table cellpadding="5" cellspacing="0" width="100%" style="border: 1px solid black;border-collapse: collapse;border-bottom:none;font-size:9pt;">
        <tr>
            <td colspan="6" class="medium tebal">
                PEMERIKSAAN DALAM
            </td>
        </tr>
        <tr>
            <td colspan="12">
                1. Lemak subcutis dada dan perut warna : {{ isset($data['pemeriksaanDalam1Lemak']) ? $data['pemeriksaanDalam1Lemak'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, tebal : {{ isset($data['pemeriksaanDalam1LemakTebal']) ? $data['pemeriksaanDalam1LemakTebal'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, Kelainan : {{ isset($data['pemeriksaanDalam1LemakKelainan']) ? $data['pemeriksaanDalam1LemakKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Otot dada dan perut warna : {{ isset($data['pemeriksaanDalam1OtotDada']) ? $data['pemeriksaanDalam1OtotDada'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, tebal : {{ isset($data['pemeriksaanDalam1OtotDadaTebal']) ? $data['pemeriksaanDalam1OtotDadaTebal'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, Kelainan : {{ isset($data['pemeriksaanDalam1OtotDadaKelainan']) ? $data['pemeriksaanDalam1OtotDadaKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Peritoneum warna : {{ isset($data['pemeriksaanDalam1PeritoneumWarna']) ? $data['pemeriksaanDalam1PeritoneumWarna'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, omentum menutup  {{ isset($data['pemeriksaanDalam1PeritoneumOmentum']) ? $data['pemeriksaanDalam1PeritoneumOmentum'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;usus. 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Dalam rongga perut terdapat {{ isset($data['pemeriksaanDalam1Rongga']) ? $data['pemeriksaanDalam1Rongga'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;:  {{ isset($data['pemeriksaanDalam1RonggaKet']) ? $data['pemeriksaanDalam1RonggaKet'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Sternum : {{ isset($data['pemeriksaanDalam1Sternum']) ? $data['pemeriksaanDalam1Sternum'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Clavicula dextra : {{ isset($data['pemeriksaanDalam1CDextra']) ? $data['pemeriksaanDalam1CDextra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Clavicula sinistra : {{ isset($data['pemeriksaanDalam1CSinistra']) ? $data['pemeriksaanDalam1CSinistra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Scapula  dextra : {{ isset($data['pemeriksaanDalam1SDextra']) ? $data['pemeriksaanDalam1SDextra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Scapula  sinistra : {{ isset($data['pemeriksaanDalam1SSinistra']) ? $data['pemeriksaanDalam1SSinistra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Costae anterior /posterior dextra : {{ isset($data['pemeriksaanDalam1CAPDextra']) ? $data['pemeriksaanDalam1CAPDextra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Costae anterior/ posterior sinistra : {{ isset($data['pemeriksaanDalam1CAPSinistra']) ? $data['pemeriksaanDalam1CAPSinistra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Vertebrae : {{ isset($data['pemeriksaanDalam1Vertebrae']) ? $data['pemeriksaanDalam1Vertebrae'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Diafragma dextra setinggi Intercostal Space : {{ isset($data['pemeriksaanDalam1DiafragmaDextra']) ? $data['pemeriksaanDalam1DiafragmaDextra'] : '-' }} 
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="12" >
                &nbsp;&nbsp;&nbsp;&nbsp;Diafragma sinistra setinggi Intercostal Space : {{ isset($data['pemeriksaanDalam1DiafragmaSinistra']) ? $data['pemeriksaanDalam1DiafragmaSinistra'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">2. Jaringan kulit (cutis) leher bagian dalam : {{ isset($data['pemeriksaanDalam2Jaringan']) ? $data['pemeriksaanDalam2Jaringan'] : '-' }}</td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Otot-otot (musculus) leher : {{ isset($data['pemeriksaanDalam2Otot']) ? $data['pemeriksaanDalam2Otot'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Arteri carotis dextra : {{ isset($data['pemeriksaanDalam2ACD']) ? $data['pemeriksaanDalam2ACD'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, sinistra :  {{ isset($data['pemeriksaanDalam2ACS']) ? $data['pemeriksaanDalam2ACS'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Vena jugularis dextra : {{ isset($data['pemeriksaanDalam2VJD']) ? $data['pemeriksaanDalam2VJD'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, sinistra :  {{ isset($data['pemeriksaanDalam2VJS']) ? $data['pemeriksaanDalam2VJS'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Lidah Warna : {{ isset($data['pemeriksaanDalam2LidahWarna']) ? $data['pemeriksaanDalam2LidahWarna'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, kelainan :  {{ isset($data['pemeriksaanDalam2LidahKelainan']) ? $data['pemeriksaanDalam2LidahKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Tonsil dextra : {{ isset($data['pemeriksaanDalam2TonsilDextra']) ? $data['pemeriksaanDalam2TonsilDextra'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, sinistra :  {{ isset($data['pemeriksaanDalam2TonsilSinistra']) ? $data['pemeriksaanDalam2TonsilSinistra'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Katup pangkal tenggorokan : {{ isset($data['pemeriksaanDalam2Tenggorokan']) ? $data['pemeriksaanDalam2Tenggorokan'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pita suara : {{ isset($data['pemeriksaanDalam2PitaSuara']) ? $data['pemeriksaanDalam2PitaSuara'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Os hyoid : {{ isset($data['pemeriksaanDalam2OsHyoid']) ? $data['pemeriksaanDalam2OsHyoid'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Cartilago thyroid : {{ isset($data['pemeriksaanDalam2CartilagoThyroid']) ? $data['pemeriksaanDalam2CartilagoThyroid'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Cartilago cricothyroid : {{ isset($data['pemeriksaanDalam2CartilagoCricothyroid']) ? $data['pemeriksaanDalam2CartilagoCricothyroid'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Vertebrae cervical : {{ isset($data['pemeriksaanDalam2VertebraeCervical']) ? $data['pemeriksaanDalam2VertebraeCervical'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Tiroid : {{ isset($data['pemeriksaanDalam2Tiroid']) ? $data['pemeriksaanDalam2Tiroid'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, Berat : {{ isset($data['pemeriksaanDalam2TiroidBerat']) ? $data['pemeriksaanDalam2TiroidBerat'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, kelainan : {{ isset($data['pemeriksaanDalam2TiroidKelainan']) ? $data['pemeriksaanDalam2TiroidKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Thymus : {{ isset($data['pemeriksaanDalam2Thymus']) ? $data['pemeriksaanDalam2Thymus'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, Berat : {{ isset($data['pemeriksaanDalam2ThymusBerat']) ? $data['pemeriksaanDalam2ThymusBerat'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, kelainan : {{ isset($data['pemeriksaanDalam2ThymusKelainan']) ? $data['pemeriksaanDalam2ThymusKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Esofagus : {{ isset($data['pemeriksaanDalam2Esofagus']) ? $data['pemeriksaanDalam2Esofagus'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;,  {{ isset($data['pemeriksaanDalam2EsofagusIsi']) ? $data['pemeriksaanDalam2EsofagusIsi'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, mukosa warna : {{ isset($data['pemeriksaanDalam2EsofagusMukosa']) ? $data['pemeriksaanDalam2EsofagusMukosa'] : '-' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Trakea : {{ isset($data['pemeriksaanDalam2Trakea']) ? $data['pemeriksaanDalam2Trakea'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;,  {{ isset($data['pemeriksaanDalam2TrakeaIsi']) ? $data['pemeriksaanDalam2TrakeaIsi'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, mukosa warna : {{ isset($data['pemeriksaanDalam2TrakeaMukosa']) ? $data['pemeriksaanDalam2TrakeaMukosa'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">3. Cavum thorax dextra terdapat : {{ isset($data['pemeriksaanDalam3CTD']) ? $data['pemeriksaanDalam3CTD'] : '-' }} </td> 
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Cavum thorax sinistra terdapat : {{ isset($data['pemeriksaanDalam3CTS']) ? $data['pemeriksaanDalam3CTS'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pericardium tampak : {{ isset($data['pemeriksaanDalam3PericardiumTampak']) ? $data['pemeriksaanDalam3PericardiumTampak'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;centimeter, diantara kedua tepi paru-paru, berisi : {{ isset($data['pemeriksaanDalam3PericardiumBerisi']) ? $data['pemeriksaanDalam3PericardiumBerisi'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Cor warna : {{ isset($data['pemeriksaanDalam3CorWarna']) ? $data['pemeriksaanDalam3CorWarna'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;, berat : {{ isset($data['pemeriksaanDalam3CorBerat']) ? $data['pemeriksaanDalam3CorBerat'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan : {{ isset($data['pemeriksaanDalam3CorKelainan']) ? $data['pemeriksaanDalam3CorKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Arteri Coronaria : {{ isset($data['pemeriksaanDalam3ArteriCoronaria']) ? $data['pemeriksaanDalam3ArteriCoronaria'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ukuran valvula atrioventrikular dextra : {{ isset($data['pemeriksaanDalam3UkuranVAD']) ? $data['pemeriksaanDalam3UkuranVAD'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;centimeter
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ukuran valvula atrioventrikular sinistra : {{ isset($data['pemeriksaanDalam3UkuranVAS']) ? $data['pemeriksaanDalam3UkuranVAS'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;centimeter
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Valvula aorta : {{ isset($data['pemeriksaanDalam3ValvulaAorta']) ? $data['pemeriksaanDalam3ValvulaAorta'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;centimeter, valvula arteri pulmonal {{ isset($data['pemeriksaanDalam3ValvulaArteriPulmonal']) ? $data['pemeriksaanDalam3ValvulaArteriPulmonal'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;centimeter
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Tebal otot ventrikel dextra : {{ isset($data['pemeriksaanDalam3TebalOtotVentrikelDextra']) ? $data['pemeriksaanDalam3TebalOtotVentrikelDextra'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;cm, ventrikel sinistra {{ isset($data['pemeriksaanDalam3TebalOtotVentrikelSinistra']) ? $data['pemeriksaanDalam3TebalOtotVentrikelSinistra'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;cm
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Aorta diameter : {{ isset($data['pemeriksaanDalam3AortaDiameter']) ? $data['pemeriksaanDalam3AortaDiameter'] : '-' }} &nbsp;&nbsp;&nbsp;&nbsp;centimeter, kelainan {{ isset($data['pemeriksaanDalam3AortaKelainan']) ? $data['pemeriksaanDalam3AortaKelainan'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Septum cordis kelainan : {{ isset($data['pemeriksaanDalam3SeptumCordisKelainan']) ? $data['pemeriksaanDalam3SeptumCordisKelainan'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pleura dextra
                <?php
                if (isset($data['pemeriksaanDalam3DextraPerlekatan'])) {
                    if ($data['pemeriksaanDalam3DextraPerlekatan'] == 'Tidak') {
                ?>
                <label for="Tidak">Tidak Ada Perlekatan</label>
                <label> / </label>
                <label for="Ada"><s>Ada Perlekatan</s></label>
                <?php
                    } else {
                ?>
                <label for="Ada">Ada Perlekatan</label>
                <label> / </label>
                <label for="Tidak"><s>Tidak Ada Perlekatan</s></label>
                <?php
                    }
                } else {
                ?>
                <label for="Tidak">Tidak Ada Perlekatan</label>
                <label> / </label>
                <label for="Ada">Ada Perlekatan</label>
                <?php
                }
                ?>
                &nbsp;pada <?= isset($data['pemeriksaanDalam3PerDextralekatanKet']) ? $data['pemeriksaanDalam3PerDextralekatanKet'] : '-' ?>
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pleura sinistra
                <?php
                if (isset($data['pemeriksaanDalam3SinistraPerlekatan'])) {
                    if ($data['pemeriksaanDalam3SinistraPerlekatan'] == 'Tidak') {
                ?>
                <label for="Tidak">Tidak Ada Perlekatan</label>
                <label> / </label>
                <label for="Ada"><s>Ada Perlekatan</s></label>
                <?php
                    } else {
                ?>
                <label for="Ada">Ada Perlekatan</label>
                <label> / </label>
                <label for="Tidak"><s>Tidak Ada Perlekatan</s></label>
                <?php
                    }
                } else {
                ?>
                <label for="Tidak">Tidak Ada Perlekatan</label>
                <label> / </label>
                <label for="Ada">Ada Perlekatan</label>
                <?php
                }
                ?>
                &nbsp;pada <?= isset($data['pemeriksaanDalam3SinistraPerlekatanKet']) ? $data['pemeriksaanDalam3SinistraPerlekatanKet'] : '-' ?>
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pulmo dextra  {{ isset($data['pemeriksaanDalam3AortaPulmoDextra']) ? $data['pemeriksaanDalam3AortaPulmoDextra'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;lobus, warna {{ isset($data['pemeriksaanDalam3AortaPulmoDextraWarna']) ? $data['pemeriksaanDalam3AortaPulmoDextraWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam3AortaPulmoDextraBerat']) ? $data['pemeriksaanDalam3AortaPulmoDextraBerat'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan : {{ isset($data['pemeriksaanDalam3AortaPulmoDextraKelainan']) ? $data['pemeriksaanDalam3AortaPulmoDextraKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam3AortaPulmoDextraIrisan']) ? $data['pemeriksaanDalam3AortaPulmoDextraIrisan'] : '-' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pulmo Sinistra  {{ isset($data['pemeriksaanDalam3AortaPulmoSinistra']) ? $data['pemeriksaanDalam3AortaPulmoSinistra'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;lobus, warna {{ isset($data['pemeriksaanDalam3AortaPulmoSinistraWarna']) ? $data['pemeriksaanDalam3AortaPulmoSinistraWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam3AortaPulmoSinistraBerat']) ? $data['pemeriksaanDalam3AortaPulmoSinistraBerat'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan : {{ isset($data['pemeriksaanDalam3AortaPulmoSinistraKelainan']) ? $data['pemeriksaanDalam3AortaPulmoSinistraKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam3AortaPulmoSinistraIrisan']) ? $data['pemeriksaanDalam3AortaPulmoSinistraIrisan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                4. Gaster
                <?php
                if (isset($data['pemeriksaanDalam4Gaster'])) {
                    if ($data['pemeriksaanDalam4Gaster'] == 'Kosong') {
                ?>
                <label for="Kosong">Kosong</label>
                <label> / </label>
                <label for="Isi"><s>Isi</s></label>
                <?php
                    } else {
                ?>
                
                <label for="Kosong"><s>Kosong</s></label>
                <label> / </label>
                <label for="Isi">Isi</label>
                <?php
                    }
                } else {
                ?>
                <label for="Kosong">Kosong</label>
                <label> / </label>
                <label for="Isi">Isi</label>
                <?php
                }
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;:  {{ isset($data['pemeriksaanDalam4GasterIsi']) ? $data['pemeriksaanDalam4GasterIsi'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, mukosa warna {{ isset($data['pemeriksaanDalam4GasterMukosaWarna']) ? $data['pemeriksaanDalam4GasterMukosaWarna'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Duodenum panjang  {{ isset($data['pemeriksaanDalam4DuodenumPanjang']) ? $data['pemeriksaanDalam4DuodenumPanjang'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, mukosa warna {{ isset($data['pemeriksaanDalam4DuodenumMukosaWarna']) ? $data['pemeriksaanDalam4DuodenumMukosaWarna'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Jejunum dan ileum panjang  {{ isset($data['pemeriksaanDalam4JejunumIleumPanjang']) ? $data['pemeriksaanDalam4JejunumIleumPanjang'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, mukosa warna {{ isset($data['pemeriksaanDalam4JejunumIleumMukosaWarna']) ? $data['pemeriksaanDalam4JejunumIleumMukosaWarna'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Appendix panjang  {{ isset($data['pemeriksaanDalam4AppendixPanjang']) ? $data['pemeriksaanDalam4AppendixPanjang'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, diameter {{ isset($data['pemeriksaanDalam4AppendixDiameter']) ? $data['pemeriksaanDalam4AppendixDiameter'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, mukosa warna {{ isset($data['pemeriksaanDalam4AppendixMukosaWarna']) ? $data['pemeriksaanDalam4AppendixMukosaWarna'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Colon panjang  {{ isset($data['pemeriksaanDalam4ColonPanjang']) ? $data['pemeriksaanDalam4ColonPanjang'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, mukosa warna {{ isset($data['pemeriksaanDalam4ColonMukosaWarna']) ? $data['pemeriksaanDalam4ColonMukosaWarna'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Lien warna  {{ isset($data['pemeriksaanDalam4LienWarna']) ? $data['pemeriksaanDalam4LienWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam4LienBerat']) ? $data['pemeriksaanDalam4LienBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan {{ isset($data['pemeriksaanDalam4LienKelainan']) ? $data['pemeriksaanDalam4LienKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam4LienIrisan']) ? $data['pemeriksaanDalam4LienIrisan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Hepar warna  {{ isset($data['pemeriksaanDalam4HeparWarna']) ? $data['pemeriksaanDalam4HeparWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam4HeparBerat']) ? $data['pemeriksaanDalam4HeparBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan {{ isset($data['pemeriksaanDalam4HeparKelainan']) ? $data['pemeriksaanDalam4HeparKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam4HeparIrisan']) ? $data['pemeriksaanDalam4HeparIrisan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Vesica fellea: berisi cairan warna kuning kehijauan sebanyak  {{ isset($data['pemeriksaanDalam4VesicaFellea']) ? $data['pemeriksaanDalam4VesicaFellea'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cc, saluran empedu
                <?php
                if (isset($data['pemeriksaanDalam4VesicaFelleaSumbatanEmpedu'])) {
                    if ($data['pemeriksaanDalam4VesicaFelleaSumbatanEmpedu'] == 'Ada') {
                ?>
                <label for="Ada">Ada</label>
                <label> / </label>
                <label for="Tidak"><s>Tidak ada</s></label>
                <?php
                    } else {
                ?>
                
                <label for="Ada"><s>Ada</s></label>
                <label> / </label>
                <label for="Tidak">Tidak ada</label>
                <?php
                    }
                } else {
                ?>
                <label for="Ada">Ada</label>
                <label> / </label>
                <label for="Tidak">Tidak ada</label>
                <?php
                }
                ?>
                &nbsp;&nbsp;sumbatan
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Pankreas warna  {{ isset($data['pemeriksaanDalam4PankreasWarna']) ? $data['pemeriksaanDalam4PankreasWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam4PankreasBerat']) ? $data['pemeriksaanDalam4PankreasBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan {{ isset($data['pemeriksaanDalam4PankreasKelainan']) ? $data['pemeriksaanDalam4PankreasKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam4PankreasIrisan']) ? $data['pemeriksaanDalam4PankreasIrisan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Glandula suprarenalis dextra warna  {{ isset($data['pemeriksaanDalam4GSDWarna']) ? $data['pemeriksaanDalam4GSDWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, Glandula suprarenalis sinistra warna {{ isset($data['pemeriksaanDalam4GSSWarna']) ? $data['pemeriksaanDalam4GSSWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, kelainan {{ isset($data['pemeriksaanDalam4GlandulaKelainan']) ? $data['pemeriksaanDalam4GlandulaKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ren dextra warna :  {{ isset($data['pemeriksaanDalam4RenDextraWarna']) ? $data['pemeriksaanDalam4RenDextraWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam4RenDextraBerat']) ? $data['pemeriksaanDalam4RenDextraBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan {{ isset($data['pemeriksaanDalam4RenDextraKelainan']) ? $data['pemeriksaanDalam4RenDextraKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam4RenDextraIrisan']) ? $data['pemeriksaanDalam4RenDextraIrisan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ren Sinistra warna :  {{ isset($data['pemeriksaanDalam4RenSinistraWarna']) ? $data['pemeriksaanDalam4RenSinistraWarna'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, berat {{ isset($data['pemeriksaanDalam4RenSinistraBerat']) ? $data['pemeriksaanDalam4RenSinistraBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan {{ isset($data['pemeriksaanDalam4RenSinistraKelainan']) ? $data['pemeriksaanDalam4RenSinistraKelainan'] : '-' }}
                &nbsp;&nbsp;&nbsp;&nbsp;pada irisan penampang: : {{ isset($data['pemeriksaanDalam4RenSinistraIrisan']) ? $data['pemeriksaanDalam4RenSinistraIrisan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ureter: dextra
                <?php
                if (isset($data['pemeriksaanDalam4UreterDextra'])) {
                    if ($data['pemeriksaanDalam4UreterDextra'] == 'Ada') {
                ?>
                <label for="Ada">Ada</label>
                <label> / </label>
                <label for="Tidak"><s>Tidak ada</s></label>
                <?php
                    } else {
                ?>
                
                <label for="Ada"><s>Ada</s></label>
                <label> / </label>
                <label for="Tidak">Tidak ada</label>
                <?php
                    }
                } else {
                ?>
                <label for="Ada">Ada</label>
                <label> / </label>
                <label for="Tidak">Tidak ada</label>
                <?php
                }
                ?>
                &nbsp;&nbsp;sumbatan
                &nbsp;&nbsp;&nbsp;&nbsp;Sinistra
                <?php
                if (isset($data['pemeriksaanDalam4UreterSinistra'])) {
                    if ($data['pemeriksaanDalam4UreterSinistra'] == 'Ada') {
                ?>
                <label for="Ada">Ada</label>
                <label> / </label>
                <label for="Tidak"><s>Tidak ada</s></label>
                <?php
                    } else {
                ?>
                
                <label for="Ada"><s>Ada</s></label>
                <label> / </label>
                <label for="Tidak">Tidak ada</label>
                <?php
                    }
                } else {
                ?>
                <label for="Ada">Ada</label>
                <label> / </label>
                <label for="Tidak">Tidak ada</label>
                <?php
                }
                ?>
                &nbsp;&nbsp;sumbatan
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Vesica urinaria
                <?php
                if (isset($data['pemeriksaanDalam4VesicaUrinaria'])) {
                    if ($data['pemeriksaanDalam4VesicaUrinaria'] == 'Kosong') {
                ?>
                <label for="Kosong">Kosong</label>
                <label> / </label>
                <label for="Isi"><s>Isi</s></label>
                <?php
                    } else {
                ?>
                
                <label for="Kosong"><s>Kosong</s></label>
                <label> / </label>
                <label for="Isi">Isi</label>
                <?php
                    }
                } else {
                ?>
                <label for="Kosong">Kosong</label>
                <label> / </label>
                <label for="Isi">Isi</label>
                <?php
                }
                ?>
                &nbsp;&nbsp;&nbsp;&nbsp;:  {{ isset($data['pemeriksaanDalam4VesicaUrinariaIsi']) ? $data['pemeriksaanDalam4VesicaUrinariaIsi'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, selaput lendir warna {{ isset($data['pemeriksaanDalam4VesicaUrinariaWarna']) ? $data['pemeriksaanDalam4VesicaUrinariaWarna'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Prostat:  {{ isset($data['pemeriksaanDalam4Prostat']) ? $data['pemeriksaanDalam4Prostat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, Testis: {{ isset($data['pemeriksaanDalam4Testis']) ? $data['pemeriksaanDalam4Testis'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, kelainan: {{ isset($data['pemeriksaanDalam4TestisKelainan']) ? $data['pemeriksaanDalam4TestisKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Uterus: ukuran  {{ isset($data['pemeriksaanDalam4UterusUkuran']) ? $data['pemeriksaanDalam4UterusUkuran'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, berat {{ isset($data['pemeriksaanDalam4UterusBerat']) ? $data['pemeriksaanDalam4UterusBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan {{ isset($data['pemeriksaanDalam4UterusKelainan']) ? $data['pemeriksaanDalam4UterusKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Tuba falopii dextra panjang  {{ isset($data['pemeriksaanDalam4TubaFalopiiDextraPanjang']) ? $data['pemeriksaanDalam4TubaFalopiiDextraPanjang'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, sinistra panjang {{ isset($data['pemeriksaanDalam4TubaFalopiiSinistraPanjang']) ? $data['pemeriksaanDalam4TubaFalopiiSinistraPanjang'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, kelainan {{ isset($data['pemeriksaanDalam4TubaFalopiiKelainan']) ? $data['pemeriksaanDalam4TubaFalopiiKelainan'] : '-' }}
            </td>
        </tr>
        <tr style="border: 1px solid black;border-top:none">
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ovarium dextra ukuran  {{ isset($data['pemeriksaanDalam4OvariumDextraUkuran']) ? $data['pemeriksaanDalam4OvariumDextraUkuran'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, sinistra ukuran {{ isset($data['pemeriksaanDalam4OvariumSinistraUkuran']) ? $data['pemeriksaanDalam4OvariumSinistraUkuran'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;cm, kelainan {{ isset($data['pemeriksaanDalam4OvariumKelainan']) ? $data['pemeriksaanDalam4OvariumKelainan'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="2">
                5.	Kulit Kepala bagian dalam : {{ isset($data['pemeriksaanDalam5Kepala']) ? $data['pemeriksaanDalam5Kepala'] : '-' }}
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Calvaria :  {{ isset($data['pemeriksaanDalam5Calvaria']) ? $data['pemeriksaanDalam5Calvaria'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Basis Cranii :  {{ isset($data['pemeriksaanDalam5BasisCranii']) ? $data['pemeriksaanDalam5BasisCranii'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Duramater :  {{ isset($data['pemeriksaanDalam5Duramater']) ? $data['pemeriksaanDalam5Duramater'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Cerebrum berat :  {{ isset($data['pemeriksaanDalam5CerebrumBerat']) ? $data['pemeriksaanDalam5CerebrumBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan:  {{ isset($data['pemeriksaanDalam5CerebrumKelainan']) ? $data['pemeriksaanDalam5CerebrumKelainan'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Ventrikel :  {{ isset($data['pemeriksaanDalam5Ventrikel']) ? $data['pemeriksaanDalam5Ventrikel'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Cerebellum berat :  {{ isset($data['pemeriksaanDalam5CerebellumBerat']) ? $data['pemeriksaanDalam5CerebellumBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan:  {{ isset($data['pemeriksaanDalam5CerebellumKelainan']) ? $data['pemeriksaanDalam5CerebellumKelainan'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Medulla oblongata berat :  {{ isset($data['pemeriksaanDalam5MedullaOblongataBerat']) ? $data['pemeriksaanDalam5MedullaOblongataBerat'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;gram, kelainan:  {{ isset($data['pemeriksaanDalam5MedullaOblongataKelainan']) ? $data['pemeriksaanDalam5MedullaOblongataKelainan'] : '-' }} 
            </td>
        </tr>
        <tr>
            <td colspan="12">
                &nbsp;&nbsp;&nbsp;&nbsp;Circulus Willisii :  {{ isset($data['pemeriksaanDalam5CirculusWillisii']) ? $data['pemeriksaanDalam5CirculusWillisii'] : '-' }} 
                &nbsp;&nbsp;&nbsp;&nbsp;, kelainan:  {{ isset($data['pemeriksaanDalam5CirculusWillisiiKelainan']) ? $data['pemeriksaanDalam5CirculusWillisiiKelainan'] : '-' }} 
            </td>
        </tr>
    </table>

    {{-- {{ dd($data) }} --}}
    {{ dd() }}

@endsection
