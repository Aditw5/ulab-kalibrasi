<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengkajian Awal Pasien Gawat Darurat</title>

    <style>
        @page {
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        small {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif !important;
        }

        .block {
            display: block;
        }

        .salinan {
            width: 100%;
            /* border: 1px solid black; */
            /* padding: 1px 17px; */
            text-align: right;
            margin-bottom: .6rem;
        }

        .tebal {
            font-weight: bold
        }

        .garis-bawah {
            text-decoration: underline;
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

        .standard {
            font-size: 15px;
            font-weight: normal;
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
    </style>
    <style>
        .header {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            padding: 0;
        }

        .header>* {
            vertical-align: middle;
        }

        .border1 {
            border: 1px solid black;
        }

        .tabel-parameter td {
            padding: 0px 0px;
            line-height: 1;
        }
    </style>
</head>


<body>

    <table class="salinan">
        <thead>
            <tr>
                <td><span class="styled-pre">RM C.2 (1/2)</span></td>
            </tr>
        </thead>
    </table>
    <table class="header">
        <tr class="border1">
            <td style="width: 60%">
                <table>
                    <tr>
                        <td width="20%" style="text-align: right">
                            <img src="img/logo-rs.png" style="width: 50px; padding-left:5px">
                        </td>
                        <td>
                            <table cellpadding="0" cellspacing="0">
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
            </td>
            <td rowspan="2" class="border1">
                <table style="padding:0px;" width="100%">
                    <tr>
                        <td style="width:35%;">
                            <span class="medium normal">Nomor RM</span>
                        </td>
                        <td style="width:2%;">
                            <span class="medium normal">:</span>
                        </td>
                        {{-- {{dd($data['registrasi']['objectdepartemenfk'])}} --}}
                        <td>
                            <span class="medium tebal">{{ isset($identitas['nocm']) ? $identitas['nocm'] : '' }}</span>
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
                            <span
                                class="medium tebal">{{ isset($identitas['namapasien']) ? $identitas['namapasien'] : '' }}</span>
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
                            <span
                                class="medium tebal">{{ isset($identitas['jeniskelamin']) ? $identitas['jeniskelamin'] : '' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>        
        <tr style="padding: 0;">
            <td style="text-align: center; padding: 0; text-transform: uppercase" class="tebal">
                PENGKAJIAN AWAL <br> PASIEN GAWAT DARURAT
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 25%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">PENGKAJIAN KEPERAWATAN</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black; width: 100%; border-collapse: collapse; position: relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; border-bottom: none;">
                <span class="block standard">Keluhan Utama</span>
                <span class="standard">{{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '' }}</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-top: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block standard">Medikasi</span>
                <span class="standard">{{ isset($data['medikasi']) ? $data['medikasi'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block standard">Mekanisme Cedera</span>
                <span class="standard">{{ isset($data['melanismeCedera']) ? $data['melanismeCedera'] : '' }}</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-right: 1px solid black; border-bottom: none;">
                <span class="block standard">Riwayat Penyakit Sebelumnya</span>
                <span class="standard">{{ isset($data['riwayatPenyakitSebelumnya']) ? $data['riwayatPenyakitSebelumnya'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block standard">Riwayat Penyakit Saat Ini</span>
                <span class="standard">{{ isset($data['riwayatPenyakitSaatIni']) ? $data['riwayatPenyakitSaatIni'] : '' }}</span>
            </td>
            <td style="text-align: left; padding-left: 0px; width: 25%; border-right: 1px solid black; border-bottom: none;">
                <table>
                    <tr>
                        <td style="vertical-align: middle;"><span class="block standard">Berat Badan</span></td>
                        <td>
                            <span class="standard" style="display: inline-block; border: 1px solid black; padding: 2px 5px; vertical-align: middle;">
                                {{ isset($data['beratBadan']) ? $data['beratBadan'] . ' Kg' : '' }}
                            </span>
                        </td>
                        <td style="vertical-align: middle;"><span class="block standard">Tinggi Badan</span></td>
                        <td>
                            <span class="standard" style="display: inline-block; border: 1px solid black; padding: 2px 5px; vertical-align: middle;">
                                {{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] . ' cm' : '' }}
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block standard">Alergi</span>
                <span class="standard">{{ isset($data['alergi']) ? $data['alergi'] : '' }}</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-right: 1px solid black; border-bottom: none;">
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">1. PENILAIAN NYERI</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-top: 1px solid black; border-right: 1px solid black; border-left: 1px solid black; border-bottom: none;">
                <table>
                    <tr>
                        <td class="standard" style="vertical-align: middle;">Apakah terdapat keluhan nyeri?</td>
                        <td class="standard">
                            <input type="checkbox"
                            {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] > 0 ? 'checked' : '' }} />
                        </td>
                        <td class="standard" style="vertical-align: middle;">Ya</td>
                        <td class="standard">
                            <input type="checkbox"
                            {{ isset($data['skoringNyeri']) && $data['skoringNyeri'] == 0 ? 'checked' : '' }} />
                        </td>
                        <td class="standard" style="vertical-align: middle;">Tidak</td>
                    </tr>
                </table>              
            </td>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-top: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block standard">Bila Ya, bagaimana skala nyerinya?</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="padding: 0px 0px; line-height: 1; border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%;" class="tebal">SKALA</td>
            <td style="text-align: left; width: 75%;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['skoringJatuh']) && $data['skoringJatuh'] == 'NRS' ? 'checked' : '' }} />
                        </td>
                        <td class="tebal" style="vertical-align: middle;">NRS</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['skoringJatuh']) && $data['skoringJatuh'] == 'WB' ? 'checked' : '' }} />
                        </td>
                        <td class="tebal" style="vertical-align: middle;">WB</td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['skoringJatuh']) && $data['skoringJatuh'] == 'FLACE' ? 'checked' : '' }} />
                        </td>
                        <td class="tebal" style="vertical-align: middle;">FLACE</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['skoringJatuh']) && $data['skoringJatuh'] == 'NIPS' ? 'checked' : '' }} />
                        </td>
                        <td class="tebal" style="vertical-align: middle;">NIPS</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['skoringJatuh']) && $data['skoringJatuh'] == 'BPS' ? 'checked' : '' }} />
                        </td>
                        <td class="tebal" style="vertical-align: middle;">BPS</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%;">
                <span class="block">Jumlah Skor: {{ isset($data['skoringNyeri']) ? $data['skoringNyeri'] : '' }}</span>
            </td>
            <td style="text-align: left; width: 75%; padding-left: 4px;">
            <span class="block">
                Kategori: 
                @php
                    $kategoriNyeri = "";
                    if (!isset($data['skoringNyeri'])) {
                        $kategoriNyeri = "Tidak Diketahui";
                    } elseif ($data['skoringNyeri'] >= 0 && $data['skoringNyeri'] <= 1) {
                        $kategoriNyeri = "Tidak Ada Nyeri";
                    } elseif ($data['skoringNyeri'] >= 2 && $data['skoringNyeri'] <= 3) {
                        $kategoriNyeri = "Sedikit Nyeri";
                    } elseif ($data['skoringNyeri'] >= 4 && $data['skoringNyeri'] <= 5) {
                        $kategoriNyeri = "Cukup Nyeri";
                    } elseif ($data['skoringNyeri'] >= 6 && $data['skoringNyeri'] <= 7) {
                        $kategoriNyeri = "Lumayan Nyeri";
                    } elseif ($data['skoringNyeri'] >= 8 && $data['skoringNyeri'] <= 9) {
                        $kategoriNyeri = "Sangat Nyeri";
                    } elseif ($data['skoringNyeri'] == 10) {
                        $kategoriNyeri = "Amat Sangat Nyeri";
                    }
                @endphp
                {{ $kategoriNyeri }}
            </span>
        </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">2. PENGKAJIAN RISIKO PASIEN JATUH</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="padding: 0px 0px; line-height: 1; border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%;" class="tebal">SKALA</td>
            <td style="text-align: left; width: 75%;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['jenisSkoringJatuh']) && $data['jenisSkoringJatuh'] == 'Morse' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Morse</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['jenisSkoringJatuh']) && $data['jenisSkoringJatuh'] == 'Humpty Dumpty' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Humpty Dumpty</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['jenisSkoringJatuh']) && $data['jenisSkoringJatuh'] == 'Ontario MOdified Stratify Sydnes Scoring' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Ontario Modified Stratify Sydnes Scoring</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 25%;">
                <span class="block">Jumlah Skor: {{ isset($data['skorJatuh']) ? $data['skorJatuh'] : '' }}</span>
            </td>
            <td style="text-align: left; width: 75%; padding-left: 4px;">
                <span class="block">Kategori: {{ isset($data['kategoriJatuh']) ? $data['kategoriJatuh'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">3. PEMERIKSAAN STATUS NUTRISI</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="padding: 0px 0px; line-height: 1; border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td colspan="2" style="text-align: center; padding-left: 4px; width: 93%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" class="tebal">
                <span class="block">Parameter</span>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" class="tebal">
                <span class="block">Skor</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-top: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block">1.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;">
                <span class="block"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">a. Tidak ada penurunan berat badan</td>
                        <td>
                            {!! isset($data['penurunanBB']['keterangan']) && $data['penurunanBB']['keterangan'] == 'Tidak' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">0</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">b. Tidak yakin/tidak tahu/terasa baju lebih longgar</td>
                        <td>
                            {!! isset($data['penurunanBB']['keterangan']) && $data['penurunanBB']['keterangan'] == 'Tidak Yakin' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">2</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">c. Jika ya, berapa penurunan berat badan tersebut</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp; 1-5 Kg</td>
                        <td>
                            {!! isset($data['penurunanBB']['keterangan']) && $data['penurunanBB']['keterangan'] == 'Ya,1-5 Kg' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">1</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp; 6-10 Kg</td>
                        <td>
                            {!! isset($data['penurunanBB']['keterangan']) && $data['penurunanBB']['keterangan'] == '6-10 Kg' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">2</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp; 11-15 Kg</td>
                        <td>
                            {!! isset($data['penurunanBB']['keterangan']) && $data['penurunanBB']['keterangan'] == '11-15 Kg' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">3</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp; >15 Kg</td>
                        <td>
                            {!! isset($data['penurunanBB']['keterangan']) && $data['penurunanBB']['keterangan'] == '> 15 Kg' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">4</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block">2.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">Apakah asupan makan berkurang karena tidak nafsu makan?</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">a. Tidak</td>
                        <td>
                            {!! isset($data['penurunanNafsuMakan']['keterangan']) && $data['penurunanNafsuMakan']['keterangan'] == 'Tidak' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">0</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">b. Ya</td>
                        <td>
                            {!! isset($data['penurunanNafsuMakan']['keterangan']) && $data['penurunanNafsuMakan']['keterangan'] == 'Ya' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block">1</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block">3.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td style="vertical-align: middle;">Pasien dengan diagnosis khusus &nbsp;</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; padding-left: 4px; width: 3%; border-left: 1px solid black; border-bottom: none; border-right: none;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 91%; border-right: 1px solid black; border-bottom: none;">
                <table class="tabel-parameter">
                    <tr>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'dm' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">DM</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'Kemoterapi' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Kemoterapi</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'Hemodialisa' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Hemodialisa</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'Geriatri' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Geriartri</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'immunitasmMnurun' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Immunitas menurun</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'lain_lain' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Lain-lain ({{ isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Ya' && isset($data['valueDiagnosaKhusus']) && $data['valueDiagnosaKhusus'] == 'lain_lain' ? $data['valueDiagnosaKhususLain'] : '' }})</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;">
                <span class="block"></span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: right; padding-left: 4px; width: 93%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" class="tebal">
                <span class="block">Total Skor: &nbsp;</span>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" class="tebal">
                <span class="block">{{ isset($data['totalNilaiPemeriksaanNutrisi']) ? $data['totalNilaiPemeriksaanNutrisi'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align: left; padding-left: 4px; width: 93%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" class="tebal">
                <span class="block">(Bila skor >2 dan atau pasien dengan diagnosis/kondisi khusus dilaporkan ke dokter pemeriksa)</span>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 6%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" class="tebal">
                <span class="block"></span>
            </td>
        </tr>        
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">4. STATUS FUNGSIONAL</span>
            </td>
        </tr>
        <tr class="standard">
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;" width="25%">
                <span class="block">{{ isset($data['statusFungsional']) ? $data['statusFungsional'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">5. STATUS BIOLOGIS</span>
            </td>
        </tr>
        <tr class="standard">
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: 1px solid black;;" width="25%">
                <span class="block">{{ isset($data['statusBiologis']) ? $data['statusBiologis'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">6. STATUS PSIKOLOGIS</span>
            </td>
        </tr>
        <tr class="standard">
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">{{ isset($data['statusPsikologis']) ? $data['statusPsikologis'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">7. PEMERIKSAAN FISIK</span>
            </td>
        </tr>
    </table>
    <table class="standard" class="tabel-parameter" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">Kelainan Pada Fisik (Beri Tanda Dengan Nomor)</span>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block"></span>
            </td>            
        </tr>
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <img src="img/body-man.svg" style="width: 200px; padding-left:5px">
            </td>
            <td style="text-align: left; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <table>
                    <tr>
                        <td style="vertical-align: middle;">1. Deformitas &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['deformitas']) && $data['deformitas'] == 'ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['deformitas']) && $data['deformitas'] == 'tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;">2. Contusio &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['contusio']) && $data['contusio'] == 'ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['contusio']) && $data['contusio'] == 'tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;">3. Abrasi &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['abrasi']) && $data['abrasi'] == 'ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['abrasi']) && $data['abrasi'] == 'tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;">4. Penetrasi &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['penetrasi']) && $data['penetrasi'] == 'ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['penetrasi']) && $data['penetrasi'] == 'tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;">5. Laserasi &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['leserasi']) && $data['leserasi'] == 'ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['leserasi']) && $data['leserasi'] == 'tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;">6. Edema &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['edema']) && $data['edema'] == 'ya' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox" 
                                {{ isset($data['edema']) && $data['edema'] == 'tidak' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: middle;">7. Keluhan Lain &nbsp;</td>
                        <td style="vertical-align: middle;">:</td>
                        <td style="vertical-align: middle;">{{ isset($data['keluhanLainya']) ? $data['keluhanLainya'] : '' }}</td>
                    </tr>
                </table>
            </td>            
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">8. DIAGNOSA KEPERAWATAN DAN RENCANA ASUHAN KEPERAWATAN</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;" width="25%">
                <span class="block tebal">Diagnosa Keperawatan</span>
            </td> 
            <td style="text-align: center; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;" width="25%">
                <span class="block tebal">Rencana Asuhan Keperawatan</span>
            </td>           
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <?php 
                if (!empty($data['diagnosaKeper'])) {
                    echo '<span class="block">';
                    $items = array_column($data['diagnosaKeper'], 'diagnosaKeperawatan'); 
                    echo '• ' . implode("<br>• ", $items); // Tambahkan titik di setiap item
                    echo '</span>';
                } 
                ?>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">{{ isset($data['rencanaKeperawatan']) ? $data['rencanaKeperawatan'] : '' }}</span>
            </td>           
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">9. MASALAH</span>
            </td>
        </tr>
        <tr class="standard">
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">{{ isset($data['masalahKeperawatn']) ? $data['masalahKeperawatn'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">10. INSTRUKSI</span>
            </td>
        </tr>
        <tr class="standard">
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">{{ isset($data['intruksiPerawat']) ? $data['intruksiPerawat'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%" class="tebal">
                <span class="block">11. SARAN</span>
            </td>
        </tr>
        <tr class="standard">
            <td style="text-align: left; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-top: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">{{ isset($data['saranPerawat']) ? $data['saranPerawat'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">Perawat: {{ isset($data['ppjpIGD']['label']) ? $data['ppjpIGD']['label'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">Tanggal & Jam:</span>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">Nama:</span>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block">Tanda Tangan:</span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align: top; text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;" width="25%">
                <span class="block">Cirebon,
                {{ \Carbon\Carbon::parse($data['tglPembuatan'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }}</span>
            </td>
            <td style="vertical-align: top; text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;" width="25%">
                <span class="block">{{ isset($data['ppjpIGD']['label']) ? $data['ppjpIGD']['label'] : '' }}</span>
            </td>
            <td style="vertical-align: top; text-align: center; padding-left: 4px; width: 100%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: 1px solid black;" width="25%">
                <div style="display: flex; justify-content: center; align-items: center; width: 100%;">
                    <img src="data:image/png;base64, {!! $tte !!}" style="max-width: 30%; height: auto;">
                </div>
            </td>
        </tr>
    </table>
    
    {{-- {{ dd($data) }} --}}


</body>

</html>
