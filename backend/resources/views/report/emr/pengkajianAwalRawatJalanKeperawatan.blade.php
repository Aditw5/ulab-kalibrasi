<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> Pengkajian Awal Pasien Rawat Jalan </title>

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
    </style>
</head>

@php
    $kodeDepartemen = $data['registrasi']['objectdepartemenfk'] ?? 16;
@endphp

<body>
    <table class="salinan">
        <thead>
            <tr>
                <td><span class="styled-pre">RM A.2.1 (1/2)</span></td>
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
                Pengkajian Awal Pasien Rawat Jalan
            </td>
        </tr>
    </table>
    <table class="standard" style="margin-top:20px;border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">DETAIL KUNJUNGAN PASIEN</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table style=" border: 1px solid black;width:100%;border-collapse: collapse; position:relative; ">
        <tr>
            <td width="12%">
                <input type="checkbox"
                    {{ isset($data['allow']) && $data['allow'] == 'Allo Anamnesis' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Allo Anamnesis</span>
            </td>
            <td width="12%">
                <input type="checkbox"
                    {{ isset($data['allow']) && $data['allow'] == 'Auto Anamnesis' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Auto Anamnesis</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="33%">
                <span class="normal" style="font-size:9pt">Usia Saat Kunjuangan :</span>
                <span class="normal" style="font-size:9pt;margin-left:10px">{{ $data['umur'] }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="33%">
                <span class="normal" style="font-size:9pt">Tanggal Registrasi :</span>
                <span class="normal" style="font-size:9pt;margin-left:10px">{{ $data['tanggalWaktuRegistrasi'] }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="33%">
                <span class="normal" style="font-size:9pt">Tanggal Registrasi :</span>
                <span class="normal" style="font-size:9pt;margin-left:10px">{{ $data['tanggalWaktuRegistrasi'] }}</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%">
                <span class="normal" style="font-size:9pt">Alasan Kunjungan/keluhan Utama :</span>
                <span class="normal"
                    style="font-size:9pt;margin-left:10px">{{ isset($data['alasanKunjunagn']) ? $data['alasanKunjunagn'] : '' }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%">
                <span class="normal" style="font-size:9pt">Riwayat Penyakit Dahulu :</span>
                <span class="normal"
                    style="font-size:9pt;margin-left:10px">{{ isset($data['riwayatPenyakitDahulu']) ? $data['riwayatPenyakitDahulu'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%">
                <span class="normal" style="font-size:9pt">Riwayat Alergi :</span>
                <span class="normal"
                    style="font-size:9pt;margin-left:10px">{{ isset($data['riwayatAlergi']) ? $data['riwayatAlergi'] : '' }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%">
                <span class="normal" style="font-size:9pt">Riwayat Obat :</span>
                <span class="normal"
                    style="font-size:9pt;margin-left:10px">{{ isset($data['riwayatObat']) ? $data['riwayatObat'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="100%">
                <span class="normal" style="font-size:9pt">Poli :</span>
                <span class="normal"
                    style="font-size:9pt;margin-left:10px">{{ isset($data['namaruangan']) ? $data['namaruangan'] : '' }}</span>
            </td>
        </tr>
    </table>


    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">RIWAYAT PSIKOSOSIAL DAN SPRITUAL</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="33%">
                <span class="normal" style="font-size:9pt">Hubungan pasien dengan keluarga :</span>
            </td>
            <td style="text-align: left; padding-left:4px;border-top:none" width="33%">
                <input type="checkbox"
                    {{ isset($data['hubunganPasien']) && $data['hubunganPasien'] == 'Baik' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Baik</span>
            </td>
            <td style="border-right: 1px solid black;;text-align: left; padding-left:4px;border-top:none"
                width="33%">
                <input type="checkbox"
                    {{ isset($data['hubunganPasien']) && $data['hubunganPasien'] == 'Tidak Baik' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Baik</span>
            </td>
        </tr>

    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Hubungan pasien dengan keluarga :</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="20%">
                <input type="checkbox"
                    {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Tenang' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tenang</span>
            </td>
            <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                <input type="checkbox"
                    {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Cemas' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Cemas</span>
            </td>
            <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                <input type="checkbox"
                    {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Takut' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Takut</span>
            </td>
            <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                <input type="checkbox"
                    {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Marah' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Marah</span>
            </td>
            <td style="border-right: 1px solid black;;text-align: left; padding-left:4px;border-top:none"
                width="20%">
                <input type="checkbox"
                    {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Sedih' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sedih</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Spritual :</span>
            </td>
        </tr>
    </table>
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="50%">
                <input type="checkbox"
                    {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'laporanBunuhdiri' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kecenderuangan bunuh diri
                    dilaporkan ke</span>
            </td>
            <td style="border-right: 1px solid black; text-align: left; padding-left: 4px; border-top: none"
                width="50%">
                <span class="normal" style="font-size: 9pt; margin-left: 10px;top:-7px">
                    {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'laporanBunuhdiri' ? $data['ketLaporkan'] ?? '' : '' }}
                </span>
            </td>
        </tr>
        <tr>
            <td style="border-bottom: 1px solid black;border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="50%">
                <input type="checkbox"
                    {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'kecenderuanganLain' ? 'checked' : '' }} />
                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain sebutkan</span>
            </td>
            <td style="border-bottom: 1px solid black;border-right: 1px solid black; text-align: left; padding-left: 4px; border-top: none"
                width="50%">
                <span class="normal" style="font-size: 9pt; margin-left: 10px; top:-7px">
                    {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'kecenderuanganLain' ? $data['ketKecenderuangan'] ?? '' : '' }}
                </span>
            </td>
        </tr>
    </table>


    <div style="display: block;position:relative;">
        <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
            <tr>
                <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black; background-color: #d3d3d3;">
                    <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                        <tr>
                            <td>
                                <span class="block tebal">PEMERIKSAAN FISIK</span>
                            </td>
                        </tr>
                    </table>                
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium">Pemeriksaan Fisik</span>
                </td>
                <td width="2%" class="label-top">
                    <span class="normal medium">:</span>
                </td>

                <td>
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal medium block">TD</span>
                            </td>
                            <td width="20%">
                                <span
                                    class="normal medium block">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '' }}
                                    mmhg</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium block">Nadi</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium block">{{ isset($data['nadi']) ? $data['nadi'] : '' }}
                                    x/mnt</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium block">Pernafasan</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span
                                    class="normal medium block">{{ isset($data['pernapasan']) ? $data['pernapasan'] : '' }}
                                    x/mnt</span>
                            </td>
                            <td width="5%" style="text-align: right">
                                <span class="normal medium">Suhu</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium">{{ isset($data['suhu']) ? $data['suhu'] : '' }}C</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                                <span class="normal medium">TB</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span
                                    class="normal medium">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '' }}
                                    cm</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium">BB</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium"
                                    style="text-align: left">{{ isset($data['beratBadan']) ? $data['beratBadan'] : '' }}
                                    kg</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td width="25%" class="label-top">
                </td>
                <td width="2%" class="label-top"><span class="normal medium "></span></td>
                <td>
                    <span class="normal medium block">Status Gizi :</span>
                </td>
                <td>
                    <span
                        class="normal medium block">{{ isset($data['statusGizi']) ? $data['statusGizi'] : '' }}</span>
                </td>
            </tr>
        </table>
    </div>

    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">RENGKAJIAN SISTEM</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>

    {{-- Respirasi --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Respirasi :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['respirasi']) || $data['respirasi'] == 'respirasiNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['respirasi']) && $data['respirasi'] == 'respirasiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['respirasi']) && $data['respirasi'] == 'respirasiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['respirasi']) && $data['respirasi'] == 'respirasiTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['respirasi']) && $data['respirasi'] == 'respirasiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['respirasi']) && $data['respirasi'] == 'respirasiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalVesikuler']) && $data['respirasiTidakNormalVesikuler'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Vesikuler</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalRonchi']) && $data['respirasiTidakNormalRonchi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ronchi</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalBatukBerdahak']) && $data['respirasiTidakNormalBatukBerdahak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Batuk Berdahak</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalNafasTidakTeratur']) && $data['respirasiTidakNormalNafasTidakTeratur'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nafas Tidak Teratur</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalNafasLambat']) && $data['respirasiTidakNormalNafasLambat'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nafas Lambat/Dispune</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalPenggunaanOtotBahu']) && $data['respirasiTidakNormalPenggunaanOtotBahu'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Penggunaan Otot Bahu
                        Nafas</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalBatukKering']) && $data['respirasiTidakNormalBatukKering'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Batuk Kering</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalBatukDarah']) && $data['respirasiTidakNormalBatukDarah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Batuk darah</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalWheezing']) && $data['respirasiTidakNormalWheezing'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Wheezing</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalSulitDahak']) && $data['respirasiTidakNormalSulitDahak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sulit Mengeluarkan
                        Dahak</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalNafasCepat']) && $data['respirasiTidakNormalNafasCepat'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nafas Cepat</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['respirasiTidakNormalApnoe']) && $data['respirasiTidakNormalApnoe'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Apnoe</span>
                </td>
            </tr>
        </table>
    @endif


    {{-- Sirkulasi --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Sirkulasi :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['sirkulasi']) || $data['sirkulasi'] == 'sirkulasiNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasi']) && $data['sirkulasi'] == 'sirkulasiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasi']) && $data['sirkulasi'] == 'sirkulasiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['sirkulasi']) && $data['sirkulasi'] == 'sirkulasiTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasi']) && $data['sirkulasi'] == 'sirkulasiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasi']) && $data['sirkulasi'] == 'sirkulasiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiBerdebar']) && $data['sirkulasiBerdebar'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Berdebar-debar</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiNyeriDada']) && $data['sirkulasiNyeriDada'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nyeri Dada</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiPingsan']) && $data['sirkulasiPingsan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pingsan</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiMurmur']) && $data['sirkulasiMurmur'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Murmur</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiGailop']) && $data['sirkulasiGailop'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Gailop</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiMerasaDingin']) && $data['sirkulasiMerasaDingin'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">MerasaDingin</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiSianosis']) && $data['sirkulasiSianosis'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sianosis/Pucat</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiCapilary']) && $data['sirkulasiCapilary'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Capilary Refil</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiBengkak']) && $data['sirkulasiBengkak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Bengkak Anasarka</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiKesemutan']) && $data['sirkulasiKesemutan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kesemutan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['sirkulasiLainLain']) && $data['sirkulasiLainLain'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain</span>
                </td>
                @if (isset($data['sirkulasiLainLain']) && $data['sirkulasiLainLain'] == true)
                    <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                        width="25%">
                        <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ket Lain :
                            {{ $data['sirkulasiKetLainLain'] ?? '' }}</span>
                    </td>
                @endif
            </tr>
        </table>
    @endif

    {{-- Nutrisi --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Nutrisi dan Cairan :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['nutrisi']) || $data['nutrisi'] == 'nutrisiNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisi']) && $data['nutrisi'] == 'nutrisiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisi']) && $data['nutrisi'] == 'nutrisiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['nutrisi']) && $data['nutrisi'] == 'nutrisiTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisi']) && $data['nutrisi'] == 'nutrisiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisi']) && $data['nutrisi'] == 'nutrisiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">TB</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nutrisiTB']) ? $data['nutrisiTB'] : '' }}cm</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">BB</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nutrisiBB']) ? $data['nutrisiBB'] : '' }}Kg</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <table width="100%">
                        <tr>
                            <td width="70%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lingkar
                                    Kepala</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nutrisiLingkarKepala']) ? $data['nutrisiLingkarKepala'] : '' }}cm</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiPolaMakandanMinum']) && $data['nutrisiPolaMakandanMinum'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pola Makan dan Minum</span>
                </td>
                @if (isset($data['nutrisiPolaMakandanMinum']) && $data['nutrisiPolaMakandanMinum'] == true)
                    <td style="text-align: left; padding-left:4px;border-top:none" width="50%">
                        <span class="normal" style="font-size:9pt;position: relative;top:-7px">(Frekwensi, jenis,
                            jumlah,
                            kebiasaan dan patungan): {{ $data['nutrisiPolaMakandanMinumLainLain'] ?? '' }}</span>
                    </td>
                @endif
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="75%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiPolaMakandanMinumUntukBayi']) && $data['nutrisiPolaMakandanMinumUntukBayi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pola Makan dan Minum Untuk
                        Bayi</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="21%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiDiare']) && $data['nutrisiDiare'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Diare</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiUbun']) && $data['nutrisiUbun'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ubun-ubung Cekung</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiMataCekung']) && $data['nutrisiMataCekung'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Mata Cekung</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="19%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiKulitKering']) && $data['nutrisiKulitKering'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kulit Kering</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="21%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiTandaDehidrasi']) && $data['nutrisiTandaDehidrasi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tanda Dehidrasi</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiAnoreksia']) && $data['nutrisiAnoreksia'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Anoreksia</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiMuntah']) && $data['nutrisiMuntah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Muntah</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="19%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiKurangMinum']) && $data['nutrisiKurangMinum'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kurang Minum</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="21%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiTifakNAfsuMakan']) && $data['nutrisiTifakNAfsuMakan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Nafsu Makan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiSulitMenelan']) && $data['nutrisiSulitMenelan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sulit Menelan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiSulitMengunyah']) && $data['nutrisiSulitMengunyah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sulit Mengunyah</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="19%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiGangguanMenghisap']) && $data['nutrisiGangguanMenghisap'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Gangguan Menghisap</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="21%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiKebersihanMulut']) && $data['nutrisiKebersihanMulut'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kebersihan Mulut</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiSelaluMerasaKenyang']) && $data['nutrisiSelaluMerasaKenyang'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Selalu Merasa
                        Kenyang</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiTidakBisaKentut']) && $data['nutrisiTidakBisaKentut'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Bisa Kentut</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="19%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiBisingUsus']) && $data['nutrisiBisingUsus'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Bising Usus</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="21%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiKembung']) && $data['nutrisiKembung'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kembung</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="59%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiKebiasaanKonsumsi']) && $data['nutrisiKebiasaanKonsumsi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kebiasaan Konsumsi yang
                        tidak
                        sehat seperti : garam/fast food/alkohol</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="21%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="79%">
                    <input type="checkbox"
                        {{ isset($data['nutrisiLainlain']) && $data['nutrisiLainlain'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain</span>
                </td>
            </tr>
        </table>
    @endif

    {{-- Eliminsai --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Eliminasi :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['eliminasi']) || $data['eliminasi'] == 'eliminasiNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['eliminasi']) && $data['eliminasi'] == 'eliminasiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['eliminasi']) && $data['eliminasi'] == 'eliminasiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['eliminasi']) && $data['eliminasi'] == 'eliminasiTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['eliminasi']) && $data['eliminasi'] == 'eliminasiNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['eliminasi']) && $data['eliminasi'] == 'eliminasiTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="80%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAK</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKAyang']) && $data['eliminasiBAKAyang'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ayang-ayangan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingTertahan']) && $data['eliminasiBAKKencingTertahan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Tertahan</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKSukaMenahanKencing']) && $data['eliminasiBAKSukaMenahanKencing'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Suka Menahan Kencing</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingTidakPuas']) && $data['eliminasiBAKKencingTidakPuas'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Tidak Puas</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingNyeri']) && $data['eliminasiBAKKencingNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKNgompol']) && $data['eliminasiBAKNgompol'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ngompol Saat Tidur</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingBeser']) && $data['eliminasiBAKKencingBeser'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Beser</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingMenetes']) && $data['eliminasiBAKKencingMenetes'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Menetes</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingDarah']) && $data['eliminasiBAKKencingDarah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Darah</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingTidakTerkontrol']) && $data['eliminasiBAKKencingTidakTerkontrol'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Tidak
                        Terkontro</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKKencingMalam']) && $data['eliminasiBAKKencingMalam'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kencing Malam</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBAKFrekwensiBertambah']) && $data['eliminasiBAKFrekwensiBertambah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAK Frekwensi
                        Bertambah</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="80%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABKeras']) && $data['eliminasiBABKeras'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB Keras</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABCair']) && $data['eliminasiBABCair'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB Cair</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABArusKemerahan']) && $data['eliminasiBABArusKemerahan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Arus Kemerahan</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABTidakBisaDitahan']) && $data['eliminasiBABTidakBisaDitahan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB Tidak Bisa
                        Ditahan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABNyeri']) && $data['eliminasiBABNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABKecilkecil']) && $data['eliminasiBABKecilkecil'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB Kecil-kecil</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABkebiasaanMenggunakan']) && $data['eliminasiBABkebiasaanMenggunakan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kebiasaan Menggunakan
                        Pencahar</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABLebih2Kalipermg']) && $data['eliminasiBABLebih2Kalipermg'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB < 2 kali/m</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABKurang2kalipermg']) && $data['eliminasiBABKurang2kalipermg'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB kurang 2 kali/mg</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABberdarah']) && $data['eliminasiBAKKencingTidakTerkontrol'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">BAB berdarah</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['eliminasiBABLeb']) && $data['eliminasiBABLeb'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                </td>
            </tr>
        </table>
    @endif


    {{-- Neurosensori --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Neurosensori :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['neurosensori']) || $data['neurosensori'] == 'neurosensoriNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensori']) && $data['neurosensori'] == 'neurosensoriNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensori']) && $data['neurosensori'] == 'neurosensoriTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['neurosensori']) && $data['neurosensori'] == 'neurosensoriTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensori']) && $data['neurosensori'] == 'neurosensoriNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensori']) && $data['neurosensori'] == 'neurosensoriTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">gcs</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['neurosensoriGcs']) ? $data['neurosensoriGcs'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ukuran
                                    Pupil</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['neurosensoriUkuranPupil']) ? $data['neurosensoriUkuranPupil'] : '' }}cm</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: left; padding-left:4px;border-top: none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="70%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Respon Pada
                                    Cahaya
                                    Kepala</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['neurosensoriResponCahaya']) ? $data['neurosensoriResponCahaya'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <table width="100%">
                        <tr>
                            <td width="70%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kemerahan
                                    Kepala</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['neurosensoriKemerahan']) ? $data['neurosensoriKemerahan'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriComposMentis']) && $data['neurosensoriComposMentis'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Compos Mentis</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriSomnolen']) && $data['neurosensoriSomnolen'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Somnolen</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriSoporocoma']) && $data['neurosensoriSoporocoma'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Soporocoma</span>
                </td>
                <td style="border-right: 1px solid black; text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriKoma']) && $data['neurosensoriKoma'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Koma</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriSakitKepala']) && $data['neurosensoriSakitKepala'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sakit kepala</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriPusing']) && $data['neurosensoriPusing'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pusing</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriMudahLupa']) && $data['neurosensoriMudahLupa'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Mudah Lupa</span>
                </td>
                <td style="border-right: 1px solid black; text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriLinglung']) && $data['neurosensoriLinglung'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Linglung</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriGelisah']) && $data['neurosensoriGelisah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Gelisah</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriTidakterKoordinsai']) && $data['neurosensoriTidakterKoordinsai'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Terkoordinsai</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriTidakMengingat']) && $data['neurosensoriTidakMengingat'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Mengingat</span>
                </td>
                <td style="border-right: 1px solid black; text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriIngatanJangkaPendek']) && $data['neurosensoriIngatanJangkaPendek'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ingatan Jangka Pendek</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriKesemutan']) && $data['neurosensoriKesemutan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kesemutan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriHilangSensasi']) && $data['neurosensoriHilangSensasi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Hilang Sensasi</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriMatiRasa']) && $data['neurosensoriMatiRasa'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Mati Rasa</span>
                </td>
                <td style="border-right: 1px solid black; text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriPengecapanTurun']) && $data['neurosensoriPengecapanTurun'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pengecapan Turun</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriAsomsia']) && $data['neurosensoriAsomsia'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Asomsia</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriPelo']) && $data['neurosensoriPelo'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pelo</span>
                </td>
                <td style="border-right: 1px solid black; text-align: left; padding-left:4px;border-top:none" width="40%">
                    <input type="checkbox"
                        {{ isset($data['neurosensoriRiwayatJatuhKecelakaan']) && $data['neurosensoriRiwayatJatuhKecelakaan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Riwayat Jatuh Kecelakaan</span>
                </td>
            </tr>
        </table>
        @if (isset($data['neurosensoriLainlain']))
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="80%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain :
                        {{ $data['neurosensoriLainlain'] ?? '' }}</span>
                </td>
            </tr>
        </table>
        @endif
    @endif

    {{-- Aktivitas Tidur --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Aktivitas Tidur :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['aktivitasTidur']) || $data['aktivitasTidur'] == 'aktivitasTidurNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidur']) && $data['aktivitasTidur'] == 'aktivitasTidurNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidur']) && $data['aktivitasTidur'] == 'aktivitasTidurTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['aktivitasTidur']) && $data['aktivitasTidur'] == 'aktivitasTidurTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidur']) && $data['aktivitasTidur'] == 'aktivitasTidurNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidur']) && $data['aktivitasTidur'] == 'aktivitasTidurTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurNyeriSaatTidur']) && $data['aktivitasTidurNyeriSaatTidur'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nyeri Saat Tidur</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurTidakBisagerak']) && $data['aktivitasTidurTidakBisagerak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Bisa Gerak</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurTidurBerlebih']) && $data['aktivitasTidurTidurBerlebih'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidur Berlebih</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurSesakSaatBergerak']) && $data['aktivitasTidurSesakSaatBergerak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sesak Saat Bergerak</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurTanganKakiBergetar']) && $data['aktivitasTidurTanganKakiBergetar'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tangan Kaki Bergetar</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurLetih']) && $data['aktivitasTidurLetih'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Letih</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurSulitTidur']) && $data['aktivitasTidurSulitTidur'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Sulit Tidur</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurPerubahanPolaTidur']) && $data['aktivitasTidurPerubahanPolaTidur'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Perubahan Pola Tidur</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurTidakPunyaTenaga']) && $data['aktivitasTidurTidakPunyaTenaga'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Punya Tenaga</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurKesemutan']) && $data['aktivitasTidurKesemutan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kesemutan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurEngganBergerak']) && $data['aktivitasTidurEngganBergerak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Enggan Bergerak</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurTidakPulihSaatTidur']) && $data['aktivitasTidurTidakPulihSaatTidur'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Pulih Saat Tidur</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurAsomsia']) && $data['aktivitasTidurAsomsia'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Asomsia
                        Terkontro</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurLemah']) && $data['aktivitasTidurLemah'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lemah</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="80%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Status Fungsional</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurNyeriSaatGerak']) && $data['aktivitasTidurNyeriSaatGerak'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nyeri Saat Gerak</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurDibantuSebagian']) && $data['aktivitasTidurDibantuSebagian'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Dibantu Sebagian</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurButuhMobilisasi']) && $data['aktivitasTidurButuhMobilisasi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Butuh Mobilisasi</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurButuhBantuanEliminasi']) && $data['aktivitasTidurButuhBantuanEliminasi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Bantuan Eliminasi
                        Ditahan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['aktivitasTidurButuhMandi']) && $data['aktivitasTidurButuhMandi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Butuh Mandi</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Skrining Jatuh :</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ya</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pernah Jatuh dalam Kurun 3 Bulan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhPernahJatuhJrun3BulanYa']) && $data['skringJatuhPernahJatuhJrun3BulanYa'] == true ? 'checked' : '' }} />
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhPernahJatuhJrun3BulanTidak']) && $data['skringJatuhPernahJatuhJrun3BulanTidak'] == true ? 'checked' : '' }} />
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Diagnosis sekunder lebih dari satu</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhDiagnosisSekunderLebihDariSatuYa']) && $data['skringJatuhDiagnosisSekunderLebihDariSatuYa'] == true ? 'checked' : '' }} />
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhDiagnosisSekunderLebihDariSatuTidak']) && $data['skringJatuhDiagnosisSekunderLebihDariSatuTidak'] == true ? 'checked' : '' }} />
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Bed rest</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhBedRestYa']) && $data['skringJatuhBedRestYa'] == true ? 'checked' : '' }} />
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhBedRestTidak']) && $data['skringJatuhBedRestTidak'] == true ? 'checked' : '' }} />
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Walker/Kruk</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhWalkerYa']) && $data['skringJatuhWalkerYa'] == true ? 'checked' : '' }} />
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhWalkerTidak']) && $data['skringJatuhWalkerTidak'] == true ? 'checked' : '' }} />
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Berpegangan dengan perabotan</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhBerpeganganDenganPerabotanYa']) && $data['skringJatuhBerpeganganDenganPerabotanYa'] == true ? 'checked' : '' }} />
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhBerpeganganDenganPerabotanTidak']) && $data['skringJatuhBerpeganganDenganPerabotanTidak'] == true ? 'checked' : '' }} />
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="15%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="45%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Infus</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhInfusYa']) && $data['skringJatuhInfusYa'] == true ? 'checked' : '' }} />
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="10%">
                    <input type="checkbox"
                        {{ isset($data['skringJatuhInfusTidak']) && $data['skringJatuhInfusTidak'] == true ? 'checked' : '' }} />
                </td>
            </tr>
        </table>
    @endif

    {{-- Nyeri dan Keamanan --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Nyeri dan Keamanan :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['nyerikeamanan']) || $data['nyerikeamanan'] == 'nyerikeamananNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamanan']) && $data['nyerikeamanan'] == 'nyerikeamananNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamanan']) && $data['nyerikeamanan'] == 'nyerikeamananTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['nyerikeamanan']) && $data['nyerikeamanan'] == 'nyerikeamananTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamanan']) && $data['nyerikeamanan'] == 'nyerikeamananNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamanan']) && $data['nyerikeamanan'] == 'nyerikeamananTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">P :</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananP']) ? $data['nyerikeamananP'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">Q :</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananQ']) ? $data['nyerikeamananQ'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">R :</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananR']) ? $data['nyerikeamananR'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">S :</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananS']) ? $data['nyerikeamananS'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal" style="font-size:9pt;position: relative;top:-7px">T :</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal"
                                    style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananT']) ? $data['nyerikeamananT'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="25%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="50%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Nyeri Pada Pasien Yang Tidak Kompeten Dlaam Subyektif :</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="30%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananNyeriPadaPasienTidakKompeten']) ? $data['nyerikeamananNyeriPadaPasienTidakKompeten'] : '' }}</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="50%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Rasa Tidak Nyaman :</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="30%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">{{ isset($data['nyerikeamananRasaTidakNayam']) ? $data['nyerikeamananRasaTidakNayam'] : '' }}</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamananTidakAdaNyeri']) && $data['nyerikeamananTidakAdaNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">0 -1 = Tidak Ada Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="60%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamananSedikitNyeri']) && $data['nyerikeamananSedikitNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">2 - 3 = Sedikit Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="60%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamananCukupNyeri']) && $data['nyerikeamananCukupNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">4 - 5 = Cukup Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="60%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamananLumayanNyeri']) && $data['nyerikeamananLumayanNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">6 - 7 = Lumayan Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="60%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamananSangatNyeri']) && $data['nyerikeamananSangatNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">8 - 9 = Sangat Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="60%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['nyerikeamananAmatSangatNyeri']) && $data['nyerikeamananAmatSangatNyeri'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">10 = Amat Sangat Nyeri</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="60%"></td>
            </tr>
        </table>
    @endif

    {{-- Kebutuhan Belajar dan Penyuluhan --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Kebutuhan Belajar dan Penyuluhan :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['kebutuhanBelajar']) || $data['kebutuhanBelajar'] == 'kebutuhanBelajarNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'kebutuhanBelajarNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'kebutuhanBelajarTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'kebutuhanBelajarTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'kebutuhanBelajarNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'kebutuhanBelajarTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="80%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="30%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajarHambatanPengetahuanFisik']) && $data['kebutuhanBelajarHambatanPengetahuanFisik'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Hambatan Pengetahuan Fisik</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="30%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajarHambatanMotivasi']) && $data['kebutuhanBelajarHambatanMotivasi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Hambatan Motivasi</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%"></td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="30%">
                    <input type="checkbox"
                        {{ isset($data['kebutuhanBelajarBahasa']) && $data['kebutuhanBelajarBahasa'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Hambatan Bahasa</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="30%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Edukasi Keperawatan Yang Dibutuhkan :</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%">
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">{{ isset($data['kebutuhanBelajarEdukasiYgDibutuhkan']) ? $data['kebutuhanBelajarEdukasiYgDibutuhkan'] : '' }}</span>
                </td>
            </tr>
        </table>
    @endif

    {{-- Kebersihan Diri --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Kebersihan Diri :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['kebersihanDiri']) || $data['kebersihanDiri'] == 'kebersihanDiriNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiri']) && $data['kebersihanDiri'] == 'kebersihanDiriNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiri']) && $data['kebersihanDiri'] == 'kebersihanDiriTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['kebersihanDiri']) && $data['kebersihanDiri'] == 'kebersihanDiriTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiri']) && $data['kebersihanDiri'] == 'kebersihanDiriNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiri']) && $data['kebersihanDiri'] == 'kebersihanDiriTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiriRambit']) && $data['kebersihanDiriRambit'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Rambut</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiriGenetalia']) && $data['kebersihanDiriGenetalia'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Genetalia</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiriGigiMulut']) && $data['kebersihanDiriGigiMulut'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Gigi Mulut</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiriMandi']) && $data['kebersihanDiriMandi'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kebutuhan Mandi</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['kebersihanDiriLainlain']) && $data['kebersihanDiriLainlain'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain</span>
                </td>
                @if (isset($data['kebersihanDiriLainlain']) && $data['kebersihanDiriLainlain'] == true)
                    <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                        width="25%">
                        <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ket Lain :
                            {{ $data['kebersihanDiriLainlainKet'] ?? '' }}</span>
                    </td>
                @endif
                @if (!isset($data['kebersihanDiriLainlain']) || $data['kebersihanDiriLainlain'] == false)
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="25%">
                </td>
                @endif
            </tr>
        </table>
    @endif

    {{-- Proteksi Keamanan --}}
    <table style="width:100%;border-collapse: collapse; position:relative;">
        <tr>
            <td style="border-left: 1px solid black;border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                width="100%">
                <span class="normal" style="font-size:9pt">Proteksi Keamanan :</span>
            </td>
        </tr>
    </table>
    @if (!isset($data['proteksiKeamanan']) || $data['proteksiKeamanan'] == 'proteksiKeamananNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamanan']) && $data['proteksiKeamanan'] == 'proteksiKeamananNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamanan']) && $data['proteksiKeamanan'] == 'proteksiKeamananTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="60%"></td>
            </tr>
        </table>
    @endif
    @if (isset($data['proteksiKeamanan']) && $data['proteksiKeamanan'] == 'proteksiKeamananTidakNormal')
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamanan']) && $data['proteksiKeamanan'] == 'proteksiKeamananNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%"></td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="20%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamanan']) && $data['proteksiKeamanan'] == 'proteksiKeamananTidakNormal' ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Tidak Normal</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananKetidakseimbangan']) && $data['proteksiKeamananKetidakseimbangan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ketidakseimbangan Termoregulasi</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananPruiritus']) && $data['proteksiKeamananPruiritus'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pruiritus</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananPendengaran']) && $data['proteksiKeamananPendengaran'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Pendengaran</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananKerusakanIntegumen']) && $data['proteksiKeamananKerusakanIntegumen'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Kerusakan Integumen</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananHipertemia']) && $data['proteksiKeamananHipertemia'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Hipertemia</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananPenglihatan']) && $data['proteksiKeamananPenglihatan'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Penglihatan</span>
                </td>
            </tr>
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%"></td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananHipotermia']) && $data['proteksiKeamananHipotermia'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Hipotermia</span>
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none" width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananLuka']) && $data['proteksiKeamananLuka'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Luka</span>
                </td>
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananPotensiCidera']) && $data['proteksiKeamananPotensiCidera'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Potensi Cidera</span>
                </td>
            </tr>
        </table>
        <table style="width:100%;border-collapse: collapse; position:relative;">
            <tr>
                <td style="border-left: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="20%">
                </td>
                <td style="text-align: left; padding-left:4px;border-top:none"
                    width="25%">
                    <input type="checkbox"
                        {{ isset($data['proteksiKeamananLainlain']) && $data['proteksiKeamananLainlain'] == true ? 'checked' : '' }} />
                    <span class="normal" style="font-size:9pt;position: relative;top:-7px">Lain-lain</span>
                </td>
                @if (isset($data['proteksiKeamananLainlain']) && $data['proteksiKeamananLainlain'] == true)
                    <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none"
                        width="50%">
                        <span class="normal" style="font-size:9pt;position: relative;top:-7px">Ket Lain :
                            {{ $data['proteksiKeamananLainlainKet'] ?? '' }}</span>
                    </td>
                @endif
                @if (!isset($data['proteksiKeamananLainlain']) || $data['proteksiKeamananLainlain'] == false)
                <td style="border-right: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="50%">
                </td>
                @endif
            </tr>
        </table>
    @endif

    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">SKRINING GIZI:</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block tebal">Berdasarkan <i>Malnutrition Screening Tool / (MST)</i></span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>        
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block tebal"><u>Parameter Skor</u></span>
            </td>
        </tr>
    </table>
    <table class="standard" style="padding: 0px 0px; line-height: 1; border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-top: none;">
        <tr>       
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right;">
                            <span class="block">1.</span>
                        </td>
                        <td style="width: 98%;" colspan="2">
                            <span class="block">Apakah pasien mengalami penurunan BB yang tidak diinginkan dalam 6 bulan terakhir?</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">a. Tidak ada penurunan berat badan &nbsp;</td>
                                    <td>
                                        {!! isset($data['tidakAdaTurunBeratBadan']) && $data['tidakAdaTurunBeratBadan'] == 0 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">0</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>           
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">b. Tidak yakin/tidak tahu/terasa baju lebih longgar &nbsp;</td>
                                    <td>
                                        {!! isset($data['tidakAdaTurunBeratBadan']) && $data['tidakAdaTurunBeratBadan'] == 2 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">2</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>       
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top;">
                            <span class="block"></span>
                        </td>
                        <td style="width: 80%;" colspan="2">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">c. Jika ya, berapa penurunan berat badan tersebut</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1-5 Kg &nbsp;</td>
                                    <td>
                                        {!! isset($data['turunBeratBadan']) && $data['turunBeratBadan'] == 1 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">1</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6-10 Kg &nbsp;</td>
                                    <td>
                                        {!! isset($data['turunBeratBadan']) && $data['turunBeratBadan'] == 2 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">2</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;11-15 Kg &nbsp;</td>
                                    <td>
                                        {!! isset($data['turunBeratBadan']) && $data['turunBeratBadan'] == 3 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">3</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>        
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;>15 Kg &nbsp;</td>
                                    <td>
                                        {!! isset($data['turunBeratBadan']) && $data['turunBeratBadan'] == 4 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">4</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>           
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right;">
                            <span class="block">2.</span>
                        </td>
                        <td style="width: 98%;" colspan="2">
                            <span class="block">Apakah asupan makan berkurang karena tidak nafsu makan?</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">a. Tidak &nbsp;</td>
                                    <td>
                                        {!! isset($data['asupanMakan']) && $data['asupanMakan'] == 0 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">0</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>          
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top; text-align: right"></td>
                        <td style="width: 80%;">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">b. Ya &nbsp;</td>
                                    <td>
                                        {!! isset($data['asupanMakan']) && $data['asupanMakan'] == 1 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">1</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 2%; vertical-align:top;">
                            <span class="block"></span>
                        </td>
                        <td style="width: 80%; text-align: right;">
                            <span class="block tebal">Total Skor: &nbsp;</span>
                        </td>
                        <td style="width: 18%;">
                            <span class="block">{{ isset($data['totalSkor']) ? $data['totalSkor'] : '' }}</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>          
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="text-align: right; width: 2%; border-left: none; border-bottom: none; border-right: none;">
                            <span class="block">3.</span>
                        </td>
                        <td style="text-align: left; width: 98%; border-right: none; border-bottom: none;" colspan="2">
                            <table>
                                <tr>
                                    <td style="vertical-align: middle;">Pasien dengan diagnosis khusus &nbsp;</td>
                                    <td>
                                        <input type="checkbox" 
                                            {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' ? 'checked' : '' }}
                                        />
                                    </td>
                                    <td style="vertical-align: middle;">Ya</td>
                                    <td>
                                        <input type="checkbox" 
                                            {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Tidak' ? 'checked' : '' }}
                                        />
                                    </td>
                                    <td style="vertical-align: middle;">Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td width="2%">
                            <input type="checkbox" 
                                {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'dm' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;" width="25%">DM</td>
                        <td width="2%">
                            <input type="checkbox" 
                                {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Kemoterapi' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;" width="23%">Kemoterapi</td>
                        <td width="2%">
                            <input type="checkbox" 
                                {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Hemodialisa' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;" width="23%">Hemodialisa</td>
                        <td width="2%">
                            <input type="checkbox" 
                                {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'Geriatri' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;" width="21%">Geriartri</td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>         
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td width="2%">
                            <input type="checkbox" 
                                {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'immunitasmMnurun' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;" width="25%">Immunitas menurun</td>
                        <td width="2%">
                            <input type="checkbox" 
                                {{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'lain_lain' ? 'checked' : '' }}
                            />
                        </td>
                        <td style="vertical-align: middle;" width="71%">Lain-lain ({{ isset($data['adaDiagnosaKhusus']) && $data['adaDiagnosaKhusus'] == 'Ya' && isset($data['diagnosaKhusus']) && $data['diagnosaKhusus'] == 'lain_lain' ? $data['diagnosaLain'] : '..........' }})</td>
                    </tr>
                </table>     
            </td>
        </tr>
        <tr>           
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 100%;" colspan="3">
                            <span class="block tebal">(Bila skor >2 dan atau pasien dengan diagnosis/kondisi khusus dilaporkan ke dokter pemeriksa)</span>
                        </td>
                    </tr>
                </table>     
            </td>
        </tr>        
    </table>
     <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; background-color: #d3d3d3;">
                <span class="block tebal">STATUS FUNGSIONAL:</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-top: 1px solid black;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusFungsional']) && $data['statusFungsional'] == 'mandiri' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Mandiri</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusFungsional']) && $data['statusFungsional'] == 'perluBantuan' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Perlu Bantuan</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusFungsional']) && $data['statusFungsional'] == 'alatBantu' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Alat Bantu</td>
                        <td>    
                            <input type="checkbox"
                            {{ isset($data['statusFungsional']) && $data['statusFungsional'] == 'prothesa' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Prothesa</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusFungsional']) && $data['statusFungsional'] == 'cacatTubuh' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Cacat Tubuh</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusFungsional']) && $data['statusFungsional'] == 'ketergantunganTotal' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">
                            Ketergantungan total, dilaporkan ke <b>dokter</b> pukul 
                            @if(isset($data['statusFungsional']) && $data['statusFungsional'] == 'ketergantunganTotal' )
                                {{ \Carbon\Carbon::parse($data['tanggalWaktuDilaporkanPadaDokter'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('H:i') }}
                            @endif
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-top: 1px solid black;" colspan="2">
                <span class="block tebal">SKRINING RISIKO CEDERA/ JATUH:</span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top; text-align: left; padding-left: 4px; padding-right: 0px; width: 2%;">
                <span class="block">a.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 83%;">
                <span class="block">Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak tidak seimbang (sempoyongan / limbung)?</span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top; text-align: left; width: 2%;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; width: 83%;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['pasienTidakSeimbang']) && $data['pasienTidakSeimbang'] == 'ya' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['pasienTidakSeimbang']) && $data['pasienTidakSeimbang'] == 'tidak' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top; text-align: left; padding-left: 4px; padding-right: 0px; width: 2%;">
                <span class="block">b.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 83%;">
                <span class="block">Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk?</span>
            </td>
        </tr>
        <tr>
            <td style="vertical-align:top; text-align: left; width: 2%;">
                <span class="block"></span>
            </td>
            <td style="text-align: left; width: 83%;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['butuhBantuanJalan']) && $data['butuhBantuanJalan'] == 'ya' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Ya</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['butuhBantuanJalan']) && $data['butuhBantuanJalan'] == 'tidak' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 5%; border-top: none;">
                <span class="block tebal">Hasil:</span>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-top: none;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['resikoPasien']) && $data['resikoPasien'] == 'tidakBerisiko' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Tidak Berisiko (tidak ditemukan a dan b)</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['resikoPasien']) && $data['resikoPasien'] == 'resikoTinggi' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Risiko Tinggi (a dan b ditemukan)</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 5%; border-top: none;">
                <span class="block tebal"></span>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-top: none;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['resikoPasien']) && $data['resikoPasien'] == 'resikoRendah' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Risiko Rendah (ditemukan a atau b)</td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-right: 0px; width: 85%; border-top: none;" colspan="2">
                <table>
                    <tr>
                        <td style="vertical-align: middle;">Diberitahukan ke <b>dokter</b> </td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['diberitahuanKeDokter']) && $data['diberitahuanKeDokter'] == 'ya' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">
                            Ya, pukul 
                            @if(isset($data['diberitahuanKeDokter']) && $data['diberitahuanKeDokter'] == 'ya' )
                                {{ \Carbon\Carbon::parse($data['tanggalWaktuCederaDiberitahuanPadaDokter'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('H:i') }}
                            @endif
                        </td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['diberitahuanKeDokter']) && $data['diberitahuanKeDokter'] == 'tidak' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 85%; background-color: #d3d3d3; border-top: 1px solid black;">
                <span class="block tebal">SKRINING NYERI:</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 100px; padding-right: 0px; width: 85%; border-top: 1px solid black;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusNyeri']) && $data['statusNyeri'] == 'tidakAdaNyeri' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Tidak ada nyeri</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusNyeri']) && $data['statusNyeri'] == 'nyeriKronis' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Nyeri Kronis</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['statusNyeri']) && $data['statusNyeri'] == 'nyeriAkut' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Nyeri akut</td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Skala nyeri: {{ isset($data['sklanyeri']) ? $data['sklanyeri'] : '' }} &nbsp; Lokasi: {{ isset($data['lokasiNyeri']) ? $data['lokasiNyeri'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Durasi: {{ isset($data['sklandurasiNyeriyeri']) ? $data['durasiNyeri'] : '' }} &nbsp; Frekuensi: {{ isset($data['frekuensiNyeri']) ? $data['frekuensiNyeri'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Nyeri hilang, bila:</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 100px; padding-right: 0px; width: 85%; border-top: none;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['penghilangNyeri']) && $data['penghilangNyeri'] == 'minumObat' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Minum obat</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['penghilangNyeri']) && $data['penghilangNyeri'] == 'mendengarkanMusik' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Mendengar musik</td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 100px; padding-right: 0px; width: 85%; border-top: none;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['penghilangNyeri']) && $data['penghilangNyeri'] == 'istirahat' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Istirahat</td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['penghilangNyeri']) && $data['penghilangNyeri'] == 'berubahPosisiTidur' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Berubah posisi tidur</td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 100px; padding-right: 0px; width: 85%; border-top: none;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['penghilangNyeri']) && $data['penghilangNyeri'] == 'lainLain' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Lain-lain, sebutkan ({{ isset($data['penghilangNyeri']) && $data['penghilangNyeri'] == 'lainLain' ? $data['penghilangNyeriLain'] : '' }})</td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Diberitahukan ke dokter:</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 100px; padding-right: 0px; width: 85%; border-top: none;">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['nyeriDiberitahukanKeDokter']) && $data['nyeriDiberitahukanKeDokter'] == 'ya' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">
                            Ya, pukul 
                            @if(isset($data['nyeriDiberitahukanKeDokter']) && $data['nyeriDiberitahukanKeDokter'] == 'ya' )
                                {{ \Carbon\Carbon::parse($data['tanggalWaktuNyeriDiberitahuanPadaDokter'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('H:i') }}
                            @endif
                        </td>
                        <td>
                            <input type="checkbox"
                            {{ isset($data['nyeriDiberitahukanKeDokter']) && $data['nyeriDiberitahukanKeDokter'] == 'tidak' ? 'checked' : '' }} />
                        </td>
                        <td style="vertical-align: middle;">Tidak</td>
                    </tr>                    
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-top: 1px solid black; ">
                @php
                    $diagnosa = isset($data['diagnosaKeperawatn']) 
                        ? DB::table('diagnosakeperawatan_m')->where('id', $data['diagnosaKeperawatn'])->first()
                        : null;
                @endphp

                <span class="block tebal">DAFTAR MASALAH KEPERAWATAN: 
                    {{ $diagnosa ? $diagnosa->diagnosakep : '' }}
                </span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>

            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-top: 1px solid black; ">
                @php
                    $tujuan = isset($data['tujuanKeperawatan']) 
                        ? DB::table('tujuanperawatan_m')->where('id', $data['tujuanKeperawatan'])->first()
                        : null;
                @endphp

                <span class="block tebal">RENCANA KEPERAWATAN: 
                    {{ $tujuan ? $tujuan->tujuankep : '' }}
                </span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <span class="block tebal">INSTRUKSI KEPERAWATAN:</span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                <span class="block tebal">SASARAN</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative;">
            <td style="text-align: center; padding-left: 4px; width: 50%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                @php
                    $intervensi = isset($data['intervensiKeperawatan']) 
                        ? DB::table('intervensi_m')->where('id', $data['intervensiKeperawatan'])->first()
                        : null;
                @endphp

                <span class="block tebal"> 
                    {{ $intervensi ? $intervensi->name : '' }}
                </span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                @php
                    $implementasi = isset($data['implementasiKeperawatan']) 
                        ? DB::table('implementasi_m')->where('id', $data['implementasiKeperawatan'])->first()
                        : null;
                @endphp

                <span class="block tebal"> 
                    {{ $implementasi ? $implementasi->name : '' }}
                </span>
            </td>
        </tr>
    </table>
    <table class="standard" style="width:100%; border-collapse: collapse; position:relative">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 50%;">
                <span class="block">&nbsp;</span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                <span class="block"></span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 50%;">
                <span class="block"></span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                <span class="block">Tanggal
                {{ \Carbon\Carbon::parse($data['tglPembuatan'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 50%;">
                <span class="block"></span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                <span class="block">Perawat yang melakukan pengkajian</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 50%;">
                <span class="block"></span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                <img src="data:image/png;base64, {!! $tte !!}" style="max-width: 30%; height: auto;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 50%;">
                <span class="block"></span>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                <span class="block">({{ isset($data['petugasPerawat']['label']) ? $data['petugasPerawat']['label'] : '' }})</span>
            </td>
        </tr>
    </table>

</body>

</html>
