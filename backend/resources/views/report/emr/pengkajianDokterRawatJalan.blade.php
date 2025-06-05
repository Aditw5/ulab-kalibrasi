<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetakan Pengkajian Dokter Rawat Jalan</title>

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
    </style>
</head>

@php
    $kodeDepartemen = $data['registrasi']['objectdepartemenfk'] ?? 18;
@endphp

<body>

    <table class="salinan">
        <thead>
            <tr>
                <td><span class="tebal">SALINAN</span></td>
            </tr>
        </thead>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse;border-bottom:none">
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
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">PENGKAJIAN
                        DOKTER RAWAT JALAN</span>
                </th>
            </tr>
        </thead>
    </table>


    <table style="border-collapse: collapse; width: 100%; margin-top: -35px; border: 1px solid black; " border="1"  >
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; border-bottom-style: hidden;" rowspan="2">Keluhan Utama :</td>
                <td style="width: 53.4091%; height: 36px; border-left-style: hidden; border-bottom-style: hidden;" colspan="2" rowspan="2">
                    @if (!empty($data))
                        {{ $data['KeluhanUtama'] ?? '-' }}
                    @endif
                </td>
                    <td style="width: 21.59%; height: 18px; border-bottom-style: hidden; border-left-style: hidden;">
                        <input type="checkbox" name="allo" {{ isset($data) && $data['statusAnamnesis'] == "Allo Anamnesis" ? 'checked' : '' }}>
                        <label for="allo">Allo Anamnesis</label>
                    </td>    
            </tr>
            <tr style="height: 18px;">
                <td style="width: 21.59%; height: 18px; border-bottom-style: hidden; border-left-style: hidden;">
                    <input type="checkbox" name="allo" {{ isset($data) && $data['statusAnamnesis'] == "Auto Anamnesis" ? 'checked' : '' }}>
                    <label for="allo">Auto Anamnesis</label>
                </td>   
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; border-top-style: hidden;">Riwayat Penyakit Sekarang :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden; border-top-style: hidden;" colspan="3">
                    @if (!empty($data))
                        {{ $data['riwayatKesehatanSekarang'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; ">Riwayat Penyakit Dahulu :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden; " colspan="3">
                    @if (!empty($data))
                        {{ $data['riwayatPenyakitDahulu'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; ">Riwayat Alergi :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden; " colspan="3">
                    @if (!empty($data))
                        {{ $data['riwayatAlergi'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; ">Riwayat Obat yang diminum :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden; " colspan="3">
                    @if (!empty($data))
                        {{ $data['riwayatObatYangDiminum'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; ">Status Gizi :</td>
                <td style="width: 30.5398%; height: 36px; text-align: left; border-left-style: hidden;">
                    <?php
                    if (isset($data) ? $data : '') {
                    ?>
                        <?= (isset($data['statusGizi']) && $data['statusGizi'] == "Gizi Kurang / Buruk") ? '<input type="checkbox" name="kurang" checked>
                        <label for="vehicle3"> Gizi Kurang / Buruk</label>' : '<input type="checkbox" name="kurang">
                        <label for="vehicle3"> Gizi Kurang / Buruk</label>' ?>
                    <?php
                    } else {
                    ?>
                        <input type="checkbox" name="kurang">
                        <label for="vehicle3"> Gizi Kurang / Buruk</label>
                    <?php
                    }
                    ?>
                <td style="width: 22.8693%; height: 36px; text-align: left; border-left-style: hidden;">
                    <?php
                    if (isset($data) ? $data : '') {
                    ?>
                        <?= (isset($data->statusGizi) && $data->statusGizi == "Gizi Cukup") ? '<input type="checkbox" name="cukup" checked>
                        <label for="vehicle3"> Gizi Cukup</label>' : '<input type="checkbox" name="cukup">
                        <label for="vehicle3"> Gizi Cukup</label>' ?>
                    <?php
                    } else {
                    ?>
                        <input type="checkbox" name="cukup">
                        <label for="vehicle3"> Gizi Cukup</label>
                    <?php
                    }
                    ?>
                <td style="width: 21.5909%; height: 36px; text-align: left; border-left-style: hidden;">
                    <?php
                    if (isset($data) ? $data : '') {
                    ?>
                        <?= (isset($data->statusGizi) && $data->statusGizi == "Gizi Baik") ? '<input type="checkbox" name="Baik" checked>
                        <label for="vehicle3"> Gizi Lebih</label>' : '<input type="checkbox" name="Baik">
                        <label for="vehicle3"> Gizi Lebih</label>' ?>
                    <?php
                    } else {
                    ?>
                        <input type="checkbox" name="Baik">
                        <label for="vehicle3"> Gizi Lebih</label>
                    <?php
                    }
                    ?>
            </tr>
            <tr style="height: 54px;">
                <td style="width: 25%; height: 54px;">Pemeriksaan Fisik (O) :</td>
                <td style="width: 75%; height: 54px;" colspan="3">
                    {{ $data['pemeriksaanFisik'] ?? '-' }}
                </td>
            </tr>            
            <tr style="height: 54px;">
                <td style="width: 25%; height: 54px;">Diagnosis (A) :</td>
                <td style="width: 75%; height: 54px;" colspan="3">
                    {{ $data['diagnosis'] ?? '-' }}
                </td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; border-bottom-style: hidden;">Rencana Terapi (P) :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden; border-bottom-style: hidden;" colspan="3"><?= !empty($data['recanaTerapi']) ? $data['recanaTerapi'] : '-' ?></td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden; border-bottom-style: hidden; border-top-style: hidden;">Instruksi :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden; border-bottom-style: hidden; border-top-style: hidden;" colspan="3">&nbsp;</td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px; border-right-style: hidden;  border-top-style: hidden;">Sasaran :</td>
                <td style="width: 75%; height: 36px; border-left-style: hidden;  border-top-style: hidden;" colspan="3">&nbsp;</td>
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px;">Rencana Pemeriksaan Penunjang</td>
                <td style="width: 75%; height: 54px;" colspan="3">
                    {{ $data['rencanaPemeriksaanPenunjang'] ?? '-' }}
                </td>
                {{-- <td style="width: 75%; height: 36px;" colspan="3"></td> --}}
            </tr>
            <tr style="height: 36px;">
                <td style="width: 25%; height: 36px;">Dirujuk / Konsul</td>
                <td style="width: 75%; height: 36px;" colspan="3">
                    {{ $data['namaruangan']['label'] ?? '-' }}
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; text-align: center; height: 18px;">Edukasi</td>
                <td style="width: 30.5398%; text-align: center; height: 18px;">Tanda Tangan Pasien/<br />Keluarga Pasien </td>
                <td style="width: 22.8693%; text-align: center; height: 18px;" colspan="2">Tanda Tangan Petugas</td>
            </tr>
            <tr style="height: 78px;">
                <td style="width: 25%; text-align: center; height: 78px;">&nbsp;</td>
                <td style="width: 30.5398%; text-align: center; height: 78px;">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{ $data['namapasien']['label'] ?? $data['namapasien'] }}"> --}}
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{$data['namapasien']['label'] ?? $data['namapasien'] . '+' . $data['tglPembuatan'] }}">
                </td>
                <td style="width: 22.8693%; text-align: center; height: 78px;" colspan="2">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; text-align: center; height: 18px;">Tanggal &amp; Jam</td>
                <td style="width: 30.5398%; text-align: center; height: 18px;">Nama Dokter</td>
                <td style="width: 22.8693%; text-align: center; height: 18px;" colspan="2">Tanda Tangan</td>
            </tr>
            <tr style="height: 78px;">
                <td style="width: 30.54%; text-align: center; height: 78px;">
                    {{ \Carbon\Carbon::parse($data['tglPembuatan'])->translatedFormat('d F Y H:m') }}
                </td>
                <td style="width: 30.5398%; text-align: center; height: 78px;"><?= $data['registrasi']['dokter'] ?></td>
                {{-- <td style="width: 22.8693%; text-align: center; height: 78px;" colspan="2">&nbsp;</td> --}}
                <td style="width: 22.8693%; text-align: center; height: 78px;" colspan="2">
                    <img src="data:image/png;base64, {!! $tte !!}">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $identitas['tte'] }}" /> --}}
                </td>
            </tr>
        </tbody>
    </table>

    
</body>

</html>
