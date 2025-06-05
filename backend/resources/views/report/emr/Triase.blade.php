<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Triase IGD</title>

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

<body>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse;">
        <thead>
            <tr>
                <th width="60%" style="border: 1px solid black">
                    <table style="width:100%;border-collapse: collapse">
                        <tr>
                            <td width="20%" style="text-align: right">
                                <img src="img/logo-rs.png" style="width: 50px; padding-left:5px">
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
                            {{-- {{dd($data['registrasi']['objectdepartemenfk'])}} --}}
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
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">TRIAGE
                        PASIEN GAWAT DARURAT</span>
                </th>
            </tr>
        </thead>
    </table>

    @if (isset($getUsia[0]) && (int) $getUsia[0] > 18)
        <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; margin-top: -35px;">
            <tr>
                <td>
                    <input type="checkbox"
                        {{ isset($data['infeksi']) && $data['infeksi'] == 'Infeksi' ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Infeksi</span>
                </td>
                <td colspan="4" align="center">TRIAGE PASIEN DEWASA</td>
            </tr>
            <tr>
                <td>
                    <input type="checkbox"
                        {{ isset($data['infeksi']) && $data['infeksi'] == 'Non Infeksi' ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Non Infeksi</span>
                </td>
                <td><span style="border : 1px solid black; padding: 2px;" class="{{ isset($data['resusitasi']) ? 'bg-red' : '' }}">merah</span></td>
                <td><span style="border : 1px solid black; padding: 2px;" class="{{ isset($data['urgent']) ? 'bg-yellow' : '' }}">kuning</span></td>
                <td><span style="border : 1px solid black; padding: 2px;" class="{{ isset($data['nonurgent']) ? 'bg-green' : '' }}">hijau</span></td>
                <td></td>
            </tr>
            <tr>
                <td class="inner-td tebal">PEMERIKSAAN</td>
                <td class="inner-td tebal bg-red">RESUSITASI</td>
                <td class="inner-td tebal bg-yellow">URGENT</td>
                <td class="inner-td tebal bg-green">NON URGENT</td>
                <td class="inner-td tebal">TANDA VITAL</td>
            </tr>
            <tr>
                <td class="inner-td">Jalan nafas</td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                        {{ isset($data['jalanNafas']) && $data['jalanNafas'] == 'Sumbatan' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Sumbatan</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['jalanNafas']) && $data['jalanNafas'] == 'BebasUrgent' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Bebas</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['jalanNafas']) && $data['jalanNafas'] == 'BebasNonUrgent' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Bebas</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>Tekanan darah</div>
                    <div class="tebal">{{ $data['tandaVitalPernafasa'] ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <td class="inner-td">Pernafasan</td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['hentiNafas']) && $data['hentiNafas'] == 'Henti Nafas' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Henti nafas</span>
                    </div>
                    {{-- <div>
                        <input type="checkbox"
                            {{ isset($data['frekuensiNafas10']) && $data['frekuensiNafas10'] == 'Frekuensi nafas< 10' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frekuensi nafas < 10</span>
                    </div> --}}
                    <div>
                        <input type="checkbox"
                            {{ isset($data['frekuensiNafas8']) && $data['frekuensiNafas8'] == 'Frekuensi nafas <8 atau >25' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek nafas &lt;8 atau &gt;25</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['sianosis']) && $data['sianosis'] == 'sianosis' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Sianosis</span>
                    </div>
                </td>
                <td class="inner-td">
                    {{-- <div>
                        <input type="checkbox"
                            {{ isset($data['freknafas22']) && $data['freknafas22'] == 'Frek. nafas >22-32' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek. nafas >23-32</span>
                    </div> --}}
                    <div>
                        <input type="checkbox"
                            {{ isset($data['freknafas21']) && $data['freknafas21'] == 'Frek. napas 21-24 atau 9-11' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek. napas 21-24 atau 9-11</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['Mengi']) && $data['Mengi'] == 'Mengi' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Mengi</span>
                    </div>
                </td>
                <td class="inner-td">
                    {{-- <div>
                        <input type="checkbox"
                            {{ isset($data['Pernafasan']) && $data['Pernafasan'] == 'frekNafasLebih20' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek. nafas >20-24</span>
                    </div> --}}
                    <div>
                        <input type="checkbox"
                            {{ isset($data['Pernafasan12']) && $data['Pernafasan12'] == 'Frek. napas 12-20' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relaPernafasan12tive;top:-7px">Frek. napas 12-20</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>Frekuensi Nadi</div>
                    <div class="tebal">{{ $data['frekuensiNadi'] ?? '' }}</div>
                    <div>Frekuensi Nafas</div>
                    <div class="tebal">{{ $data['frekuensiNafas'] ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <td class="inner-td">Sirkulasi</td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['hentiJantung']) && $data['hentiJantung'] == 'Henti jantung' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Henti jantung</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['nadiTidakTeraba']) && $data['nadiTidakTeraba'] == 'nadi tidak teraba atau teraba lemah' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">nadi tidak teraba atau teraba lemah</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['nadi<41atau>130']) && $data['nadi<41atau>130'] == 'nadi <41, atau >130' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">nadi &lt;41, atau &gt;130</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['pucar']) && $data['pucar'] == 'Pucat' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Pucat</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['akralDingin']) && $data['akralDingin'] == 'Akral dingin' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Akral dingin</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['spo2<92']) && $data['spo2<92'] == 'Spo2 <92' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Spo2 &lt;92</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['TDSistol<90atau>200']) && $data['TDSistol<90atau>200'] == 'TD Sistol < 90 atau >200' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">TD Sistol &lt;90 atau &gt;200</span>
                    </div>
                </td>
                <td class="inner-td">
                    {{-- <div>
                        <input type="checkbox"
                            {{ isset($data['frekNadi120']) && $data['frekNadi120'] == 'Frek. nadi 120-150' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek. nadi 120-150</span>
                    </div> --}}
                    <div>
                        <input type="checkbox"
                            {{ isset($data['frekNadi40']) && $data['frekNadi40'] == 'Nadi 40-60 atau 100-130' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Nadi 40-60 atau 100-130</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['tdSitol']) && $data['tdSitol'] == 'TD sistol >160' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">TD sistol >160</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['tdDiastol']) && $data['tdDiastol'] == 'TD Diastol >100' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">TD Diastol >100</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['spo29295']) && $data['spo29295'] == 'Spo2 92-95' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Spo2 92-95</span>
                    </div>
                </td>
                <td class="inner-td">
                    {{-- <div>
                        <input type="checkbox"
                            {{ isset($data['frekNadi']) && $data['frekNadi'] == 'Frek. nadi 100-120' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek. nadi 100-120</span>
                    </div> --}}
                    <div>
                        <input type="checkbox"
                            {{ isset($data['frekNadi60']) && $data['frekNadi60'] == 'Frek. nadi 60-100' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Frek. nadi 60-100</span>
                    </div>
                    {{-- <div>
                        <input type="checkbox"
                            {{ isset($data['tdSistol120']) && $data['tdSistol120'] == 'TD sistol ≥120-140' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">TD Sistol 120-140</span>
                    </div> --}}
                    <div>
                        <input type="checkbox"
                            {{ isset($data['tdSistol90']) && $data['tdSistol90'] == 'TD sistol ≥90-160' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">TD Sistol >90-160</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['sikulasi']) && $data['sikulasi'] == 'TD Diastol ≥80-100' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">TD Diastol >80-100</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['spo2>95']) && $data['spo2>95'] == 'Spo2 >95' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Spo2 >95</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>Suhu</div>
                    <div class="tebal">{{ $data['sikulasiSuhu'] ?? '' }}</div>
                    <div>Saturasi Oksigen</div>
                    <div class="tebal">{{ $data['saturasiOksigen'] ?? '' }}</div>
                    <div>Riwayat alergi</div>
                    <div class="tebal">{{ $data['sikulasiRiwayatAlergi'] ?? '' }}</div>
                    <div>Makanan</div>
                    <div class="tebal">{{ $data['sikulasiMakanan'] ?? '' }}</div>
                    <div>Lain-lain</div>
                    <div class="tebal">{{ $data['sikulasiLainLain'] ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <td class="inner-td">Kesadaran</td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['Kesadaran']) && $data['Kesadaran'] == 'GCS < 9' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">GCS < 9</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['Kesadaran']) && $data['Kesadaran'] == 'GCS >12' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">GCS > 12</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['Kesadaran']) && $data['Kesadaran'] == 'GCS 15' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">GCS 15</span>
                    </div>
                </td>
                <td class="inner-td">
                </td>
            </tr>
        </table>
    @else
        <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse;">
            <tr>
                <td colspan="4" align="center">TRIAGE PASIEN BAYI DAN ANAK</td>
            </tr>
            <tr>
                <td><span style="border : 1px solid black; padding: 2px;" class="{{ isset($data['resusitasi']) ? 'bg-red' : '' }}">merah</span></td>
                <td><span style="border : 1px solid black; padding: 2px;" class="{{ isset($data['urgent']) ? 'bg-yellow' : '' }}">kuning</span></td>
                <td><span style="border : 1px solid black; padding: 2px;" class="{{ isset($data['nonurgent']) ? 'bg-green' : '' }}">hijau</span></td>
                <td></td>
            </tr>
            <tr>
                <td class="inner-td tebal bg-red">RESUSITASI</td>
                <td class="inner-td tebal bg-yellow">URGENT</td>
                <td class="inner-td tebal bg-green">NON URGENT</td>
                <td class="inner-td tebal">TANDA VITAL</td>
            </tr>
            <tr>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['koma'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Koma</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['tandaTandaPrioritas'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Terdapat tanda prioritas</span>
                    </div>
                    </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['tidakAdaTandaGawatDarurat'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Tidak ada tanda gawat darurat</span>
                    </div></td>
                <td class="inner-td">
                    <div>Frekuensi Nadi</div>
                    <div class="tebal">{{ $data['frekuensiNadi'] ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <td class="inner-td">
                    <div><div>
                        <input type="checkbox"
                            {{ isset($data['kejang'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Kejang</span>
                    </div></div>
                </td>
                <td class="inner-td" rowspan="3">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['bayiKecil'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Bayi kecil < 2 bulan</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['suhuSangatPanas40'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Suhu sangat panas >40<sup>o</sup>C</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['traumaPerluTindakanBedah'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Trauma/perlu tindakan bedah segera</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['trismus'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Trismus</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['palor'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Palor (Sangat Pucat)</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['keracunan'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Keracunan</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['nyeriHebat'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Nyeri hebat</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['gelisah'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Gelisah</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['giziBuruk'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Gizi Buruk</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['udemaKeduaKaki'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Udema kedua tungkai</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['lukaBajarLuas'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Luka bakar luas</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>
                        <input type="checkbox"
                            {{ isset($data['tidakAdaTandaPrioritas'])? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Tidak ada tanda prioritas</span>
                    </div>
                </td>
                <td class="inner-td">
                    <div>Frekuensi Nafas</div>
                    <div class="tebal">{{ $data['frekuensiNafas'] ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <td class="inner-td">
                    <div>Jalan nafas dan pernafasan :</div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['anakJalanNafas']) && $data['anakJalanNafas'] == 'Obstruksi jalan nafas' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Obstruksi jalan nafas</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['anakJalanNafas']) && $data['anakJalanNafas'] == 'Sianosis' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Sianosis</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['anakJalanNafas']) && $data['anakJalanNafas'] == 'Sesak nafas berat' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Sesak nafas berat</span>
                    </div>
                </td>
                <td class="inner-td">
                </td>
                <td class="inner-td">
                    <div>Suhu</div>
                    <div class="tebal">{{ $data['anakSuhu'] ?? '' }}</div>
                    <div>Saturasi Oksigen</div>
                    <div class="tebal">{{ $data['anakSPO2'] ?? '' }}</div>
                    <div>Riwayat alergi</div>
                    <div class="tebal">{{ $data['anakRiwayatAlergi'] ?? '' }}</div>
                    <div>Makanan</div>
                    <div class="tebal">{{ $data['anakMakanan'] ?? '' }}</div>
                    <div>Lain-lain</div>
                    <div class="tebal">{{ $data['anakLainLain'] ?? '' }}</div>
                </td>
            </tr>
            <tr>
                <td class="inner-td">
                    <div>Sirkulasi :</div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['anakSirkulasi']) && $data['anakSiskulasi'] == 'Akral dingin, nadi cepat &lemah' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Akral dingin, nadi cepat & lemah</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['anakSirkulasi']) && $data['anakSiskulasi'] == 'Akral dingin,CRT >3 detik' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Akral dingin, CRT >3 detik</span>
                    </div>
                    <div>
                        <input type="checkbox"
                            {{ isset($data['anakSirkulasi']) && $data['anakSiskulasi'] == 'Dehidrasi berat (KU lemah,mata cekung,turgor sangat menurun)' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Dehidrasi berat (KU lemah, mata cekung, turgor sangat menurun)</span>
                    </div>
                </td>
                <td class="inner-td">
                </td>
                <td class="inner-td">
                </td>
            </tr>
        </table>
    @endif
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse;" class="tbl-data">
        <tr>
            <td align="center" colspan="3">
                Yang Melakukan Pengkajian
            </td>
        </tr>
        <tr align="center">
            <td width="25%">Tanggal & Jam</td>
            <td>Nama</td>
            <td width="15%">Tanda Tangan</td>
        </tr>
        <tr>
            <td rowspan="2">
                {{ \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->format('d F Y H:i:s') }}
                WIB
            </td>
            <td>
                <span class="normal medium block"><b>DPJP IGD :
                    </b>{{ $data['dpjpIGD']['label'] ?? $data['dpjpIGD'] }}</span>
            </td>
            <td align="center">
                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ $data['dpjpIGD']['label'] ?? $data['dpjpIGD'] }}" />
            </td>
        </tr>
        <tr>
            <td>
                <span class="normal medium block"><b>PPJP IGD :
                    </b>{{ $data['ppjpIGD']['label'] ?? $data['ppjpIGD'] }}</span>
            </td>
            <td align="center">
                <img
                    src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{ $data['ppjpIGD']['label'] ?? $data['ppjpIGD'] }}" />
            </td>
        </tr>
    </table>
    {{-- {{dd($data)}} --}}
</body>

</html>
