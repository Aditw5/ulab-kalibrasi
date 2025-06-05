<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetakan Pengkajian Dokter Rawat Inap</title>

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
                        DOKTER RAWAT INAP</span>
                </th>
            </tr>
        </thead>
    </table>

    <table style="border-collapse: collapse; width: 100%; margin-top: -35px; border: 1px solid black; " border="1" >
        <tr style="border: 1px solid black !important;">
            <td  width="100%" style="padding:3px">
                <span style="font-weight:bold">A. ANAMNESIS</span>
            </td>
        </tr>
        <tr style="border: 1px solid black !important;">
            <td  style="padding:3px 5px; max-width: 300px;">
                1. Keluhan Utama : <span style="white-space: nowrap; word-wrap: break-all;">{{ isset($data['KeluhanUtama']) ? $data['KeluhanUtama'] : '-' }}</span>
            </td>
        </tr>
        <tr style="border: 1px solid black !important;">
            <td  style="padding:3px 5px; max-width: 300px; border-bottom-style: hidden ">
                2. Riwayat Penyakit Sekarang :
            </td>
        </tr>
        <tr style="border: 1px solid black !important; border-top-style: hidden;">
            <td style="padding: 10px 5px; width: 100%; max-width: 500px; height: 80px; vertical-align: top;">
                <span style="white-space: normal; word-wrap: break-word;">
                    {{ $data['riwayatPenyakitSekarang'] ?? '-' }}
                </span>
            </td>
        </tr>
        <tr style="border: 1px solid black !important;">
            <td style="padding:3px 5px; max-width: 80px; border-bottom-style: hidden ">
                3. Riwayat Alergi :
            </td>
        </tr>
        <tr style="border: 1px solid black !important; border-top-style: hidden;">
            <td style="padding: 10px 5px; width: 100%; max-width: 140px; height: 30px; vertical-align: top;">
                <span style="white-space: normal; word-wrap: break-word;">
                    {{ $data['riwayatAlergi'] ?? '-' }}
                </span>
            </td>
        </tr>
    </table>

    <table style="border-collapse: collapse; width: 100%; height: 304px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; text-align: left;" colspan="7"><strong>B. PEMERIKSAAN FISIK</strong></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">1.</td>
                <td style="width: 20%; height: 18px;">Keadaan Umum</td>
                <td style="width: 18.75%; height: 18px; text-align: left; vertical-align: top;">
                    <?php $checked = (isset($data['keadaanUmumSehat']) && $data['keadaanUmumSehat']) ? 'checked' : ''; ?>
                    <input type="checkbox" name="sehat" <?= $checked; ?>>
                    <label for="sehat"> Sehat</label>
                </td>
                <td style="width: 18.75%; height: 36px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['keadaanUmumRingan']) && $data['keadaanUmumRingan']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="ringan" name="ringan" <?= $checked; ?>>
                    <label for="ringan"> Sakit Ringan</label>
                </td>
                <td style="width: 18.75%; height: 36px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['keadaanUmumSedang']) && $data['keadaanUmumSedang']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="sedang" name="sedang" <?= $checked; ?>>
                    <label for="sedang"> Sakit Sedang</label>
                </td>
                <td style="width: 18.75%; height: 36px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['keadaanUmumBerat']) && $data['keadaanUmumBerat']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="berat" name="berat" <?= $checked; ?>>
                    <label for="berat"> Sakit Berat</label>
                </td>
                <td style="width: 0.1%; height: 18px; border-left-style: hidden;" colspan="1">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">2.</td>
                <td style="width: 20%; height: 18px;">Kesadaran</td>
                <td style="width: 15%; height: 18px; text-align: left;">
                    <?php $checked = (isset($data['KesadaranCM']) && $data['KesadaranCM']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="cm" name="cm" <?= $checked; ?>>
                    <label for="cm"> CM</label>
                </td>

                <td style="width: 15%; height: 18px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['KesadaranApatis']) && $data['KesadaranApatis']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="apatis" name="apatis" <?= $checked; ?>>
                    <label for="apatis"> Apatis</label>
                </td>

                <td style="width: 15%; height: 18px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['KesadaranSomnolen']) && $data['KesadaranSomnolen']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="somnolen" name="somnolen" <?= $checked; ?>>
                    <label for="somnolen"> Somnolen</label>
                </td>

                <td style="width: 15%; height: 18px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['KesadaranSopor']) && $data['KesadaranSopor']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="sopor" name="sopor" <?= $checked; ?>>
                    <label for="sopor"> Sopor</label>
                </td>

                <td style="width: 15%; height: 18px; text-align: left; border-left-style: hidden;">
                    <?php $checked = (isset($data['KesadaranKoma']) && $data['KesadaranKoma']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="koma" name="koma" <?= $checked; ?>>
                    <label for="koma"> Koma</label>
                </td>
            </tr>
                <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">3.</td>
                <td style="width: 20%; height: 18px;">Kepala</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['kepala']) && $data['kepala'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalkepala" name="normalkepala" <?= $checked; ?>>
                    <label for="normalkepala"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['kepala']) && $data['kepala'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalkepala" name="abnormalkepala" <?= $checked; ?>>
                    <label for="abnormalkepala"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketKepala'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">4.</td>
                <td style="width: 20%; height: 18px;">Mata</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['mata']) && $data['mata'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalmata" name="normalmata" <?= $checked; ?>>
                    <label for="normalkepala"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['mata']) && $data['mata'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalmata" name="abnormalmata" <?= $checked; ?>>
                    <label for="abnormalkepala"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketMata'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">5.</td>
                <td style="width: 20%; height: 18px;">Hidung</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['hidung']) && $data['hidung'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalhidung" name="normalhidung" <?= $checked; ?>>
                    <label for="normalhidung"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['hidung']) && $data['hidung'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalhidung" name="abnormalhidung" <?= $checked; ?>>
                    <label for="abnormalhidung"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketHidung'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">6.</td>
                <td style="width: 20%; height: 18px;">Gigi &amp; Mulut</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['gigiMulut']) && $data['gigiMulut'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalgilut" name="normalgilut" <?= $checked; ?>>
                    <label for="normalgilut"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['gigiMulut']) && $data['gigiMulut'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalgilut" name="abnormalgilut" <?= $checked; ?>>
                    <label for="abnormalgilut"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketGigiMulut'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 16px;">
                <td style="width: 5%; height: 16px; text-align: center;">7.</td>
                <td style="width: 20%; height: 16px;">Tenggorokan</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['tenggorokan']) && $data['tenggorokan'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normaltenggorokan" name="normaltenggorokan" <?= $checked; ?>>
                    <label for="normaltenggorokan"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['tenggorokan']) && $data['tenggorokan'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormaltenggorokan" name="abnormaltenggorokan" <?= $checked; ?>>
                    <label for="abnormaltenggorokan"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketTenggorokan'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">8.</td>
                <td style="width: 20%; height: 18px;">Telinga</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['telinga']) && $data['telinga'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normaltelinga" name="normaltelinga" <?= $checked; ?>>
                    <label for="normaltelinga"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['telinga']) && $data['telinga'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormaltelinga" name="abnormaltelinga" <?= $checked; ?>>
                    <label for="abnormaltelinga"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketTelinga'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">9.</td>
                <td style="width: 20%; height: 18px;">Leher</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['leher']) && $data['leher'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalleher" name="normalleher" <?= $checked; ?>>
                    <label for="normalleher"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['leher']) && $data['leher'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalleher" name="abnormalleher" <?= $checked; ?>>
                    <label for="abnormalleher"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketTeher'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">10.</td>
                <td style="width: 20%; height: 18px;">Thoraks</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['thoraks']) && $data['thoraks'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalthoraks" name="normalthoraks" <?= $checked; ?>>
                    <label for="normalthoraks"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['thoraks']) && $data['thoraks'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalthoraks" name="abnormalthoraks" <?= $checked; ?>>
                    <label for="abnormalthoraks"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketThoraks'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">11.</td>
                <td style="width: 20%; height: 18px;">Jantung</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['jantung']) && $data['jantung'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normaljantung" name="normaljantung" <?= $checked; ?>>
                    <label for="normaljantung"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['jantung']) && $data['jantung'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormaljantung" name="abnormaljantung" <?= $checked; ?>>
                    <label for="abnormaljantung"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketJantung'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">12.</td>
                <td style="width: 20%; height: 18px;">Paru</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['paru']) && $data['paru'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalparu" name="normalparu" <?= $checked; ?>>
                    <label for="normalparu"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['paru']) && $data['paru'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalparu" name="abnormalparu" <?= $checked; ?>>
                    <label for="abnormalparu"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketParu'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">13.</td>
                <td style="width: 20%; height: 18px;">Abdomen</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['abdomen']) && $data['abdomen'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalabdomen" name="normalabdomen" <?= $checked; ?>>
                    <label for="normalabdomen"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['abdomen']) && $data['abdomen'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalabdomen" name="abnormalabdomen" <?= $checked; ?>>
                    <label for="abnormalabdomen"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketAbdomen'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">14.</td>
                <td style="width: 20%; height: 18px;">Genitalia &amp; Anus</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['genitaliaAnus']) && $data['genitaliaAnus'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalgenitaliaAnus" name="normalgenitaliaAnus" <?= $checked; ?>>
                    <label for="normalgenitaliaAnus"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['genitaliaAnus']) && $data['genitaliaAnus'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalgenitaliaAnus" name="abnormalgenitaliaAnus" <?= $checked; ?>>
                    <label for="abnormalgenitaliaAnus"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketGenitaliaAnus'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">15.</td>
                <td style="width: 20%; height: 18px;">Ekstremitas</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['ekstremitas']) && $data['ekstremitas'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalekstremitas" name="normalekstremitas" <?= $checked; ?>>
                    <label for="normalekstremitas"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['ekstremitas']) && $data['ekstremitas'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalekstremitas" name="abnormalekstremitas" <?= $checked; ?>>
                    <label for="abnormalekstremitas"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketEkstremitas'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 18px; text-align: center;">16.</td>
                <td style="width: 20%; height: 18px;">Kulit</td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['kulit']) && $data['kulit'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="normalkulit" name="normalkulit" <?= $checked; ?>>
                    <label for="normalkulit"> Normal</label>
                </td>
                <td style="width: 13%; height: 18px; text-align: center;">
                    <?php $checked = (isset($data['kulit']) && $data['kulit'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalkulit" name="abnormalkulit" <?= $checked; ?>>
                    <label for="abnormalkulit"> Abnormal</label>
                </td>
                <td style="width: 52%; height: 18px;" colspan="3">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['ketKulit'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 100%; text-align: left; max-width: 500px; height: 130px;" colspan="7">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%;  height: 290px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;" colspan="2"><strong>STATUS LOKALIS</strong></td>
            </tr>
            <tr style="height: 290px;">
                <td style="width: 41.4953%; height: 290px;">&nbsp;</td>
                <td style="width: 58.5047%; height: 290px;">&nbsp;</td>
            </tr>
            <tr style="height: 10px;">
                <td style="width: 41.4953%; height: 10px; border-top-style: hidden;">
                <p style="text-align: center; font: 10px;">Stempel Gambar Sesuai Kebutuhan Masing Masing SMF</p>
            </td>
            <td style="width: 58.5047%; height: 10px; border-top-style: hidden;">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 253px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;" colspan="2"><strong>C. HASIL PEMERIKSAAN PENUNJANG</strong></td>
            </tr>
            <tr style="height: 345px;">
                <td style="width: 25.7203%; height: 79px; vertical-align: top; text-align: left;">Laboratorium</td>
                <td style="width: 74.2797%; height: 79px; vertical-align: top; text-align: left;">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['laboratorium'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 10px;">
                <td style="width: 25.7203%; height: 78px; vertical-align: top; text-align: left;">Radiologi</td>
                <td style="width: 74.2797%; height: 78px; vertical-align: top; text-align: left;">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['radiologi'] ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr style="height: 97px;">
                <td style="width: 25.7203%; height: 78px; vertical-align: top; text-align: left;">Penunjang Lain</td>
            <td style="width: 74.2797%; height: 78px; vertical-align: top; text-align: left;">
                <span style="white-space: normal; word-wrap: break-word;">
                    &nbsp;{{ $data['penunjangLain'] ?? '-' }}
                </span>
            </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 149px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;" colspan="2"><strong>D. DIAGNOSA / ASSESMENT</strong></td>
            </tr>
            <tr>
                <td style="width: 25.7203%; height: 18px; vertical-align: top; text-align: left; ">Diagnosa Utama</td>
                <td style="width: 74.2797%; height: 18px; vertical-align: top; text-align: left; ">
                    @php
                        $diagnosaUtama = [];
                        $diagnosaSekunder = [];
                        $diagnosaDokter = [];
                        // dd($data['diagnosaDokter']);
                        $primaryDiagnosa = [];
                        $secondaryDiagnosa = [];
                        foreach ($data['diagnosaDokter'] as $item) {
                            $diagnosaDokter[] = isset($item['ketDiagnosaDok'])
                                ? $item['ketDiagnosaDok']
                                : null;
                            if (isset($item['ketDiagnosaDok'])) {
                                if(isset($item['jenisDiagnosa'])){
                                    if ($item['jenisDiagnosa']['label'] == 'Primary / utama') {
                                        $primaryDiagnosa[] = $item['ketDiagnosaDok'];
                                    } elseif ($item['jenisDiagnosa']['label'] == 'Secondary') {
                                        $secondaryDiagnosa[] = $item['ketDiagnosaDok'];
                                    }
                                }
                            }
                            if (isset($item['jenisDiagnosa'])) {
                                if ($item['jenisDiagnosa']['label'] == 'Primary / utama') {
                                    if (isset($item['diagnosaIcd10']['label']) && $item['diagnosaIcd10'] != '') {
                                        $diagUtama = $item['diagnosaIcd10']['label'];
                                        $diagnosaUtama[] = explode('--', $diagUtama);
                                    }
                                } elseif ($item['jenisDiagnosa']['label'] == 'Secondary') {
                                    if (isset($item['diagnosaIcd10']['label']) && $item['diagnosaIcd10'] != '') {
                                        $diagSecond = $item['diagnosaIcd10']['label'];
                                        $diagnosaSekunder[] = explode('--', $diagSecond);
                                    }
                                }
                            }
                        }
                    @endphp
                    @foreach ($diagnosaUtama as $item)
                        <span class="normal medium block">{{ $item[1] }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">Diagnosa Sekunder</span>
                </td>
                <td width="75%" class="label-top">
                    @foreach ($diagnosaSekunder as $item)
                        <span class="normal medium block">{{ $loop->iteration }}.
                            {{ $item[1] }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">ICD 10 Utama</span>
                </td>
                <td width="75%" class="label-top">
                    @foreach ($diagnosaUtama as $item)
                        <span class="normal medium block">{{ $item[0] }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">ICD 10 Sekunder</span>
                </td>
                <td width="75%" class="label-top">
                    @foreach ($diagnosaSekunder as $item)
                        <span class="normal medium block">{{ $loop->iteration }}.
                            {{ $item[0] }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">Diagnosa Dokter</span>
                </td>
                <td width="75%" class="label-top">
                    @foreach ($primaryDiagnosa as $item)
                        <span class="normal medium block">{{ $item }}</span>
                    @endforeach
                    @foreach ($secondaryDiagnosa as $item)
                        <span class="normal medium block">{{ $item }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">Tindakan / Prosedur</span>
                </td>
                <td class="label-top" width="75%">
                    @php
                        $tindakan = [];
                        $tindakanDokter = [];
                        foreach ($data['detailTindakan'] as $item) {
                            $tindakanDokter[] = isset($item['ketTindakanDokter'])
                                ? $item['ketTindakanDokter']
                                : null;
                            $tindakans = isset($item['diagnosaIcd9']['label'])
                                ? $item['diagnosaIcd9']['label']
                                : null;
                            if ($tindakans != null) {
                                $tindakan[] = explode('--', $tindakans);
                            }
                        }
                    @endphp
                    @foreach ($tindakan as $item)
                        <span class="normal medium block">{{ $loop->iteration }} .
                            {{ $item[1] }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">ICD 9</span>
                </td>
                <td width="75%" class="label-top">
                    @foreach ($tindakan as $item)
                        <span class="normal medium block">{{ $loop->iteration }}.
                            {{ $item[0] }}</span>
                    @endforeach
                </td>
            </tr>
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block label-top">Tindakan Dokter</span>
                </td>
                <td width="75%" class="label-top">
                    @foreach ($tindakanDokter as $item)
                        <span class="normal medium block">{{ $item }}</span>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 149px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;"><strong>E. MASALAH MEDIS DAN KEPERAWATAN</strong></td>
            </tr>
            <tr style="height: 131px;">
                <td style="width: 100%; height: 131px; vertical-align: top; text-align: left; ">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['masalahMedisKeperawatan'] ?? '-' }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 149px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;"><strong>F. TATA LAKSANA (EDUKASI, PEMERIKSAAN DIAGNOSTIK, TERAPIK)</strong></td>
            </tr>
            <tr style="height: 131px;">
                <td style="width: 100%; height: 131px; vertical-align: top; text-align: left; ">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['tataLaksana'] ?? '-' }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 149px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;"><strong>G. INSTRUKSI</strong></td>
            </tr>
            <tr style="height: 131px;">
                <td style="width: 100%; height: 131px; vertical-align: top; text-align: left; ">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['instruksi'] ?? '-' }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 149px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; height: 18px;"><strong>H. SASARAN</strong></td>
            </tr>
            <tr style="height: 131px;">
                <td style="width: 100%; height: 131px; vertical-align: top; text-align: left; ">
                    <span style="white-space: normal; word-wrap: break-word;">
                        &nbsp;{{ $data['instruksi'] ?? '-' }}
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 177px;" border="1">
        <tbody>
            <tr style="height: 32px;">
                <td style="width: 25.6242%; height: 32px; border-right-style: hidden;"><strong>Rencana Lama Rawat :</strong></td>
                <td style="width: 9.43751%; height: 32px; border-right-style: hidden; border-left-style: hidden;">{{ $data['rencanaLamaRawat'] ?? '-' }}</td>
                <td style="width: 28.9164%; height: 32px; border-right-style: hidden; border-left-style: hidden;"><strong>Rencana Tanggal Pulang :</strong></td>
                <td style="width: 7.92855%; height: 32px; border-right-style: hidden; border-left-style: hidden;">
                    {{ \Carbon\Carbon::parse($data['tanggalRencanaPulang'])->translatedFormat('d F Y') }}
                </td>
                {{-- {{dd($data)}} --}}
                {{-- <td style="width: 28.0934%; height: 32px; text-align: right; border-left-style: hidden;"><strong>/ Belum Bisa diterapkan</strong></td> --}}
                <td style="width: 28.0934%; height: 32px; text-align: right; border-left-style: hidden;">
                    <strong>
                    @if(isset($data['belumBisaDitetapkan']) && $data['belumBisaDitetapkan'] != undefined && $data['belumBisaDitetapkan'] != null)
                        Belum bisa ditetapkan
                        {{-- {{ $data['belumBisaDitetapkan'] }} --}}
                    {{-- @else --}}
                    @endif
                    </strong>
                </td>
            </tr>
            <tr style="height: 100px;">
                <td style="width: 100%; height: 80px;" colspan="5">&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 235px;" border="1">
        <tbody>
            <tr style="height: 22px;">
                <td style="width: 33.3333%; text-align: center; height: 22px;" colspan="3"><strong>Yang Melakukan Pengkajian</strong></td>
            </tr>
            <tr style="height: 24px;">
                <td style="width: 33.3333%; height: 24px; text-align: center;"><strong>Tanggal &amp; Jam</strong></td>
                <td style="width: 33.3333%; height: 24px; text-align: center;"><strong>Nama DPJP</strong></td>
                <td style="width: 33.3333%; height: 24px; text-align: center;"><strong>Tanda Tangan</strong></td>
            </tr>
            <tr style="height: 109px;">
                <td style="width: 33.3333%; height: 84px; text-align: center; ">
                    {{ \Carbon\Carbon::parse($data['tglPembuatan'])->translatedFormat('d F Y H:m') }}
                </td>
                <td style="width: 33.3333%; height: 84px; text-align: center;"><?= $data['registrasi']['dokter'] ?></td>
                <td style="width: 33.3333%; height: 84px; text-align: center;">
                    <img src="data:image/png;base64, {!! $tte !!}">
                </td>
            </tr>
            <tr style="height: 130px;">
                <td style="width: 33.3333%; height: 105px; vertical-align: top; text-align: left;" colspan="2">Keluarga sudah edukasi hasil pemeriksaan dan rencana asuhan</td>
                <td style="width: 33.3333%; height: 105px; vertical-align: top; text-align: center;">
                    <p>Tgl Edukasi : {{ \Carbon\Carbon::parse($data['tglPembuatan'])->translatedFormat('d F Y') }}</p>
                    <p>Tanda tangan pasien / Keluarga</p>
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{$data['namapasien']['label'] ?? $data['namapasien'] . '+' . $data['tglPembuatan'] }}">

                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
