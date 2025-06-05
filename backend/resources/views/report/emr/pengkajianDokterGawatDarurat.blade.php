<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cetakan Pengkajian Dokter Gawat Darurat</title>

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
                        DOKTER GAWAT DARURAT</span>
                </th>
            </tr>
        </thead>
    </table>

    <table style="border-collapse: collapse; width: 100%; height: 365px; margin-top: -35px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="text-align: center; height: 18px; width: 116.667%;" colspan="4"><strong>SUBJEKTIF</strong><span><strong><br /></strong></span></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 50.3333%; height: 18px; border-bottom-style: hidden; border-right-style: hidden;">&nbsp;KELUHAN UTAMA :</td>
                <td style="width: 12.904%; height: 18px; border-bottom-style: hidden; border-right-style: hidden; ">&nbsp;</td>
                <td style="width: 25.5871%; height: 18px; border-bottom-style: hidden; border-right-style: hidden; ">
                    <?php $checked = (isset($data['alloAnamnesis']) && $data['alloAnamnesis']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="allo" name="allo" <?= $checked; ?>>
                    <label for="allo"> Allo Anamnesis</label>
                </td>
                <td style="width: 25.8422%; height: 18px; border-bottom-style: hidden; border-left-style: hidden;">
                    <?php $checked = (isset($data['autoAnamnesis']) && $data['autoAnamnesis']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="auto" name="auto" <?= $checked; ?>>
                    <label for="auto"> Auto Anamnesis</label>
                </td>
            </tr>
            <tr style="height: 52px;">
                <td style="width: 116.667%; height: 52px; border-bottom-style: hidden; vertical-align: top; text-align: left; " colspan="4">
                    @if (!empty($data))
                        &nbsp;{{ $data['keluhanUtama'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 50.3333%; height: 18px; border-bottom-style: hidden; border-right-style: hidden;">&nbsp;RIWAYAT PENYAKIT SEKARANG :</td>
                <td style="width: 66.3333%; height: 18px; border-bottom-style: hidden; " colspan="3">&nbsp;</td>
            </tr>
            <tr style="height: 57px;">
                <td style="width: 116.667%; height: 57px; border-bottom-style: hidden; vertical-align: top; text-align: left; " colspan="4">
                    @if (!empty($data))
                        &nbsp;{{ $data['riwayatPenyakitSekarang'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 50.3333%; height: 18px; border-bottom-style: hidden; border-right-style: hidden;">&nbsp;RIWAYAT PENYAKIT DAHULU :</td>
                <td style="width: 66.3333%; height: 18px; border-bottom-style: hidden;" colspan="3">&nbsp;</td>
            </tr>
            <tr style="height: 54px;">
                <td style="width: 116.667%; height: 54px; border-bottom-style: hidden; vertical-align: top; text-align: left;" colspan="4">
                    @if (!empty($data))
                        &nbsp;{{ $data['riwayatPenyakitDahulu'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 50.3333%; height: 18px; border-bottom-style: hidden; border-right-style: hidden;">&nbsp;ALERGI :</td>
                <td style="width: 66.3333%; height: 18px; border-bottom-style: hidden;" colspan="3">&nbsp;</td>
            </tr>
            <tr style="height: 54px;">
                <td style="width: 116.667%; height: 54px; border-bottom-style: hidden; vertical-align: top; text-align: left;" colspan="4">
                    @if (!empty($data))
                        &nbsp;{{ $data['alergiObat'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 50.3333%; height: 18px; border-bottom-style: hidden; border-right-style: hidden;">&nbsp;OBAT YANG DIMINUM SAAT INI :</td>
                <td style="width: 66.3333%; height: 18px; border-bottom-style: hidden;" colspan="3">&nbsp;</td>
            </tr>
            <tr style="height: 40px;">
                <td style="width: 116.667%; height: 40px; vertical-align: top; text-align: left;" colspan="4">
                    @if (!empty($data))
                        &nbsp;{{ $data['obatSaatIni'] ?? '-' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>

    <table style="border-collapse: collapse; width: 100%;" border="1">
        <tbody>
            <tr>
                <td style="width: 100%; text-align: center;" colspan="6"><strong>OBJEKTIF</strong></td>
            </tr>
            <tr>
                <td style="width: 69.6161%; text-align: center;" colspan="5"><strong>KONDISI PASIEN SAAT MASUK</strong></td>
                <td style="width: 30.3841%; text-align: center;"><strong>CATATAN KHUSUS / KETERANGAN LAIN</strong></td>
            </tr>
            <tr>
                <td style="width: 13.3745%;" rowspan="3">Kesadaran</td>
                <td style="width: 14.0604%; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['KesadaranCM']) && $data['KesadaranCM']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="cm" name="cm" <?= $checked; ?>>
                    <label for="cm"> CM</label>
                </td>
                <td style="width: 12.8258%; border-left-style: hidden; border-bottom-style: hidden; font:14px;">
                    <?php $checked = (isset($data['KesadaranSomnolen']) && $data['KesadaranSomnolen']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="somnolen" name="somnolen" <?= $checked; ?>>
                    <label for="somnolen"> Somnolen</label>
                </td>
                <td style="width: 13.5117%; border-left-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['kesadaranDelirium']) && $data['kesadaranDelirium']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="delirium" name="delirium" <?= $checked; ?>>
                    <label for="somnolen"> Delirium</label>
                </td>
                <td style="width: 15.8437%; border-left-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['KesadaranApatis']) && $data['KesadaranApatis']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="apatis" name="apatis" <?= $checked; ?>>
                    <label for="apatis"> Apatis</label>
                </td>
                <td style="width: 30.3841%; vertical-align: top; text-align: left;" rowspan="11">
                    @if (!empty($data))
                        &nbsp;{{ $data['catatanKhususKeteranganLain'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['KesadaranSopor']) && $data['KesadaranSopor']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="sopor" name="sopor" <?= $checked; ?>>
                    <label for="sopor"> Sopor</label>
                </td>
                <td style="width: 12.8258%; border-left-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['KesadaranKoma']) && $data['KesadaranKoma']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="koma" name="koma" <?= $checked; ?>>
                    <label for="koma"> Koma</label>
                </td>
                <td style="width: 13.5117%; border-left-style: hidden; border-bottom-style: hidden;">&nbsp;</td>
                <td style="width: 15.8437%; border-left-style: hidden; border-bottom-style: hidden;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden; ">GCS : 
                    @if (!empty($data))
                        &nbsp;{{ $data['kesadaranGCS'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 12.8258%; border-right-style: hidden;">E :
                    @if (!empty($data))
                        &nbsp;{{ $data['kesadaranE'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.5117%; border-right-style: hidden;">V :
                    @if (!empty($data))
                        &nbsp;{{ $data['kesadaranV'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%; border-left-style: hidden;">M :
                    @if (!empty($data))
                        &nbsp;{{ $data['kesadaranM'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr>
                <td style="width: 13.3745%;">Keadaan Umum</td>
                <td style="width: 14.0604%; border-right-style: hidden;">sakit :</td>
                <td style="width: 12.8258%; border-right-style: hidden;">
                    <?php $checked = (isset($data['keadaanUmumRingan']) && $data['keadaanUmumRingan']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="ringan" name="ringan" <?= $checked; ?>>
                    <label for="ringan"> Ringan</label>
                </td>
                <td style="width: 13.5117%; border-right-style: hidden;">
                    <?php $checked = (isset($data['keadaanUmumSedang']) && $data['keadaanUmumSedang']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="sedang" name="sedang" <?= $checked; ?>>
                    <label for="sedang"> Sedang</label>
                </td>
                <td style="width: 15.8437%; border-left-style: hidden;">
                    <?php $checked = (isset($data['keadaanUmumBerat']) && $data['keadaanUmumBerat']) ? 'checked' : ''; ?>
                    <input type="checkbox" id="berat" name="berat" <?= $checked; ?>>
                    <label for="berat"> Sakit Berat</label>
                </td>
            </tr>
            <tr>
                <td style="width: 13.3745%;" rowspan="7">Tanda vital</td>
                <td style="width: 14.0604%; border-right-style: hidden;">TD :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['tekananDarah'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">mmHg</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden;">Nadi :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['nadi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">x/mnt</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden;">Pernapasan :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['pernapasan'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">x/mnt</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden;">SaO2 :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['SPO2'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">C</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden;">Skor Nyeri :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['skorNyeri'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden;">EWS :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['SkorEWS'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">&nbsp;</td>
            </tr>
            <tr>
                <td style="width: 14.0604%; border-right-style: hidden;">PEWS :</td>
                <td style="width: 26.3375%; border-right-style: hidden;" colspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['SkorPEWS'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 15.8437%;">&nbsp;</td>
            </tr>
        </tbody>
    </table>

    <table style="border-collapse: collapse; width: 100%; height: 234px; margin-top: 100px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="height: 18px; width: 177.353%;" colspan="6"><strong>PEMERIKSAAN FISIK</strong></td>
            </tr>
            <tr style="height: 27px;">
                <td style="height: 27px; width: 5%; border-bottom-style: hidden;">1.</td>
                <td style="height: 27px; width: 20%; border-bottom-style: hidden; ">Kepala</td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; ">
                    <?php $checked = (isset($data['fisikKepala']) && $data['fisikKepala'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="kepala" name="kepala" <?= $checked; ?>>
                    <label for="normalkepala"> Normal</label>
                </td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikKepala']) && $data['fisikKepala'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalkepala" name="abnormalkepala" <?= $checked; ?>>
                    <label for="abnormalkepala"> Abnormal</label>
                </td>
                <td style="height: 27px; width: 25%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikKepalaAbnormalKonjungtivaAnemis']) && $data['fisikKepalaAbnormalKonjungtivaAnemis'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikKepalaAbnormalKonjungtivaAnemis" name="fisikKepalaAbnormalKonjungtivaAnemis" <?= $checked; ?>>
                    <label for="abnormalkepala"> Conjungtiva Anemis</label>
                </td>
                <td style="height: 45px; width: 20%; border-bottom-style: hidden; " rowspan="2">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 36px; " rowspan="2">&nbsp;</td>
                <td style="width: 20%; height: 36px; " rowspan="2">&nbsp;</td>
                <td style="width: 15%; height: 36px; " rowspan="2">&nbsp;</td>
                <td style="width: 15%; height: 36px; border-right-style: hidden;" rowspan="2">&nbsp;</td>
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikKepalaAbnormalSklera']) && $data['fisikKepalaAbnormalSklera'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikKepalaAbnormalSklera" name="fisikKepalaAbnormalSklera" <?= $checked; ?>>
                    <label for="abnormalkepala"> Sklera Ikterik</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikKepalaAbnormalLain']) && $data['fisikKepalaAbnormalLain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikKepalaAbnormalLain" name="fisikKepalaAbnormalLain" <?= $checked; ?>>
                    <label for="abnormalkepala"> Lain-lain</label>
                </td>
                <td style="width: 20%; height: 18px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['fisikKepalaAbnormalLainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 27px;">
                <td style="height: 27px; width: 5%; border-bottom-style: hidden; ">2.</td>
                <td style="height: 27px; width: 20%; border-bottom-style: hidden; ">Leher</td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; ">
                    <?php $checked = (isset($data['fisikLeher']) && $data['fisikLeher'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="leher" name="leher" <?= $checked; ?>>
                    <label for="leher"> Normal</label>
                </td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikLeher']) && $data['fisikLeher'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalleher" name="abnormalleher" <?= $checked; ?>>
                    <label for="abnormalleher"> Normal</label>
                </td>
                <td style="height: 27px; width: 25%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikLeherJTVMeningkat']) && $data['fisikLeherJTVMeningkat'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikLeherJTVMeningkat" name="fisikLeherJTVMeningkat" <?= $checked; ?>>
                    <label for="fisikLeherJTVMeningkat"> JTV Meningkat</label>
                </td>
                <td style="height: 45px; width: 20%; border-bottom-style: hidden; " rowspan="2">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 36px; " rowspan="2">&nbsp;</td>
                <td style="width: 20%; height: 36px; " rowspan="2">&nbsp;</td>
                <td style="width: 15%; height: 36px; " rowspan="2">&nbsp;</td>
                <td style="width: 15%; height: 36px; border-right-style: hidden;" rowspan="2">&nbsp;</td>
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikLeherTumor']) && $data['fisikLeherTumor'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikLeherTumor" name="fisikLeherTumor" <?= $checked; ?>>
                    <label for="fisikLeherTumor"> Tumor</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikLeherAbnormalLain']) && $data['fisikLeherAbnormalLain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikLeherAbnormalLain" name="fisikLeherAbnormalLain" <?= $checked; ?>>
                    <label for="fisikLeherAbnormalLain"> Lain-lain</label>
                </td>
                <td style="width: 20%; height: 18px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['fisikLeherAbnormalLainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 27px;">
                <td style="height: 27px; width: 5%; border-bottom-style: hidden; ">3.</td>
                <td style="height: 27px; width: 20%; border-bottom-style: hidden; ">Thoraks</td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; ">
                    <?php $checked = (isset($data['fisikThoraks']) && $data['fisikThoraks'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="thoraks" name="thoraks" <?= $checked; ?>>
                    <label for="thoraks"> Normal</label>
                </td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikThoraks']) && $data['fisikThoraks'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalthoraks" name="abnormalthoraks" <?= $checked; ?>>
                    <label for="abnormalthoraks"> Abnormal</label>
                </td>
                <td style="height: 27px; width: 25%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikThoraksAbnormalBunyiJantung']) && $data['fisikThoraksAbnormalBunyiJantung'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikThoraksAbnormalBunyiJantung" name="fisikThoraksAbnormalBunyiJantung" <?= $checked; ?>>
                    <label for="fisikThoraksAbnormalBunyiJantung"> Bunyi Jantung I / II : Reguler / Ireguler</label>
                <td style="height: 45px; width: 20%; border-bottom-style: hidden; " rowspan="4">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 36px; " rowspan="4">&nbsp;</td>
                <td style="width: 20%; height: 36px; " rowspan="4">&nbsp;</td>
                <td style="width: 15%; height: 36px; " rowspan="4">&nbsp;</td>
                <td style="width: 15%; height: 36px; border-right-style: hidden;" rowspan="4">&nbsp;</td>
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikThoraksAbnormalBisingJantung']) && $data['fisikThoraksAbnormalBisingJantung'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikThoraksAbnormalBisingJantung" name="fisikThoraksAbnormalBisingJantung" <?= $checked; ?>>
                    <label for="fisikThoraksAbnormalBisingJantung"> Bising Jantung</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikThoraksAbnormalRonchi']) && $data['fisikThoraksAbnormalRonchi'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikThoraksAbnormalRonchi" name="fisikThoraksAbnormalRonchi" <?= $checked; ?>>
                    <label for="fisikThoraksAbnormalRonchi"> Ronchi</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikThoraksAbnormalWheezing']) && $data['fisikThoraksAbnormalWheezing'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikThoraksAbnormalWheezing" name="fisikThoraksAbnormalWheezing" <?= $checked; ?>>
                    <label for="fisikThoraksAbnormalWheezing"> Wheezing</label>
                </td>
            </tr> 
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikThoraksAbnormalLain']) && $data['fisikThoraksAbnormalLain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikThoraksAbnormalLain" name="fisikThoraksAbnormalLain" <?= $checked; ?>>
                    <label for="fisikThoraksAbnormalLain"> Lain-lain</label>
                </td>
                <td style="width: 20%; height: 18px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['fisikThoraksAbnormalLainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 27px;">
                <td style="height: 27px; width: 5%; border-bottom-style: hidden; ">4.</td>
                <td style="height: 27px; width: 20%; border-bottom-style: hidden; ">Abdomen</td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; ">
                    <?php $checked = (isset($data['fisikAbdomen']) && $data['fisikAbdomen'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abdomen" name="abdomen" <?= $checked; ?>>
                    <label for="abdomen"> Normal</label>
                </td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikAbdomen']) && $data['fisikAbdomen'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalabdomen" name="abnormalabdomen" <?= $checked; ?>>
                    <label for="abnormalabdomen"> Normal</label>
                </td>
                <td style="height: 27px; width: 25%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikAbdomenAbnormalDatar']) && $data['fisikAbdomenAbnormalDatar'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikAbdomenAbnormalDatar" name="fisikAbdomenAbnormalDatar" <?= $checked; ?>>
                    <label for="fisikAbdomenAbnormalDatar"> Datar / Cembung</label>
                </td>
                <td style="height: 45px; width: 20%; border-bottom-style: hidden; " rowspan="4">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 36px; " rowspan="4">&nbsp;</td>
                <td style="width: 20%; height: 36px; " rowspan="4">&nbsp;</td>
                <td style="width: 15%; height: 36px; " rowspan="4">&nbsp;</td>
                <td style="width: 15%; height: 36px; border-right-style: hidden;" rowspan="4">&nbsp;</td>
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikAbdomenAbnormalBisingUsus']) && $data['fisikAbdomenAbnormalBisingUsus'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikAbdomenAbnormalBisingUsus" name="fisikAbdomenAbnormalBisingUsus" <?= $checked; ?>>
                    <label for="fisikAbdomenAbnormalBisingUsus"> Bising Usus</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikAbdomenAbnormalNyeri']) && $data['fisikAbdomenAbnormalNyeri'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikAbdomenAbnormalNyeri" name="fisikAbdomenAbnormalNyeri" <?= $checked; ?>>
                    <label for="fisikAbdomenAbnormalNyeri"> Nyeri Tekan</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikAbdomenAbnormalParkusi']) && $data['fisikAbdomenAbnormalParkusi'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikAbdomenAbnormalParkusi" name="fisikAbdomenAbnormalParkusi" <?= $checked; ?>>
                    <label for="fisikAbdomenAbnormalParkusi"> Parkusi</label>
                </td>
            </tr> 
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikAbdomenAbnormalLain']) && $data['fisikAbdomenAbnormalLain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikAbdomenAbnormalLain" name="fisikAbdomenAbnormalLain" <?= $checked; ?>>
                    <label for="fisikAbdomenAbnormalLain"> Lain-lain</label>
                </td>
                <td style="width: 20%; height: 18px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['fisikAbdomenAbnormalLainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 27px;">
                <td style="height: 27px; width: 5%; border-bottom-style: hidden; ">5.</td>
                <td style="height: 27px; width: 20%; border-bottom-style: hidden; ">Ekstremitas</td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; ">
                    <?php $checked = (isset($data['fisikEkstremitas']) && $data['fisikEkstremitas'] == "Normal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="ekstrimitas" name="ekstrimitas" <?= $checked; ?>>
                    <label for="ekstrimitas"> Normal</label>
                </td>
                <td style="height: 27px; width: 15%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikEkstremitas']) && $data['fisikEkstremitas'] == "Abnormal") ? 'checked' : ''; ?>
                    <input type="checkbox" id="abnormalekstrimitas" name="abnormalekstrimitas" <?= $checked; ?>>
                    <label for="abnormalekstrimitas"> Abnormal</label>
                </td>
                <td style="height: 27px; width: 25%; border-bottom-style: hidden; border-right-style: hidden;">
                    <?php $checked = (isset($data['fisikEkstrimitasAbnormalOedem']) && $data['fisikEkstrimitasAbnormalOedem'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikEkstrimitasAbnormalOedem" name="fisikEkstrimitasAbnormalOedem" <?= $checked; ?>>
                    <label for="fisikEkstrimitasAbnormalOedem"> Oedem</label>
                </td>
                <td style="height: 45px; width: 20%; border-bottom-style: hidden; " rowspan="4">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 5%; height: 36px; border-bottom-style: hidden; " rowspan="4">&nbsp;</td>
                <td style="width: 20%; height: 36px; border-bottom-style: hidden; " rowspan="4">&nbsp;</td>
                <td style="width: 15%; height: 36px; border-bottom-style: hidden; " rowspan="4">&nbsp;</td>
                <td style="width: 15%; height: 36px; border-right-style: hidden; border-bottom-style: hidden;" rowspan="4">&nbsp;</td>
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikEkstrimitasAbnormalKukuPucat']) && $data['fisikEkstrimitasAbnormalKukuPucat'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikEkstrimitasAbnormalKukuPucat" name="fisikEkstrimitasAbnormalKukuPucat" <?= $checked; ?>>
                    <label for="fisikEkstrimitasAbnormalKukuPucat"> Kuku Pucat</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikEkstrimitasAbnormalKukuSianosis']) && $data['fisikEkstrimitasAbnormalKukuSianosis'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikEkstrimitasAbnormalKukuSianosis" name="fisikEkstrimitasAbnormalKukuSianosis" <?= $checked; ?>>
                    <label for="fisikEkstrimitasAbnormalKukuSianosis"> Kuku Sianosis</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikEkstrimitasAbnormalKekuatanOtot']) && $data['fisikEkstrimitasAbnormalKekuatanOtot'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikEkstrimitasAbnormalKekuatanOtot" name="fisikEkstrimitasAbnormalKekuatanOtot" <?= $checked; ?>>
                    <label for="fisikEkstrimitasAbnormalKekuatanOtot"> Kekuatan Otot</label>
                </td>
            </tr> 
            <tr style="height: 18px;">
                <td style="width: 25%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['fisikEkstrimitasAbnormallain']) && $data['fisikEkstrimitasAbnormallain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="fisikEkstrimitasAbnormallain" name="fisikEkstrimitasAbnormallain" <?= $checked; ?>>
                    <label for="fisikEkstrimitasAbnormallain"> Lain-lain</label>
                </td>
                <td style="width: 20%; height: 18px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['fisikEkstrimitasAbnormallainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 400px;">
                <td style="height: 330px; width: 5%;  ">&nbsp;</td>
                <td style="height: 330px; width: 20%; ">&nbsp;</td>
                <td style="height: 330px; width: 15%; ">&nbsp;</td>
                <td style="height: 330px; width: 15%; border-right-style: hidden;">&nbsp;</td>
                <td style="height: 330px; width: 25%; border-right-style: hidden;">&nbsp;</td>
                <td style="height: 330px; width: 20%; " >&nbsp;</td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 106px; margin-top: 60px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 78.1207%; text-align: center; height: 18px;"><strong>DIAGNOSIS</strong></td>
                <td style="width: 21.8793%; text-align: center; height: 18px;"><strong>Kode ICD 10</strong></td>
            </tr>
            <tr style="height: 88px;">
                <td style="width: 78.1207%; height: 88px; vertical-align: top; text-align: left;">
                    @php
                        $diagnosaUtama = [];
                
                        if (!empty($data['diagnosaICD10']) && is_array($data['diagnosaICD10'])) {
                            foreach ($data['diagnosaICD10'] as $item) {
                                if (!empty($item['ICD10Diagnosa']['label'])) {
                                    // Ambil hanya bagian setelah " - " jika ada
                                    $labelParts = explode(" - ", $item['ICD10Diagnosa']['label']);
                                    $diagnosaUtama[] = $labelParts[1] ?? $item['ICD10Diagnosa']['label'];
                                }
                            }
                        }
                    @endphp
                
                    @foreach ($diagnosaUtama as $diagnosa)
                        <span class="normal medium block">{{ $diagnosa }}</span>
                    @endforeach
                </td>
                
                <td style="width: 78.1207%; height: 88px; vertical-align: top; text-align: left;">
                    @php
                        $diagnosaKode = [];
                
                        if (!empty($data['diagnosaICD10']) && is_array($data['diagnosaICD10'])) {
                            foreach ($data['diagnosaICD10'] as $item) {
                                if (!empty($item['ICD10Diagnosa']['label'])) {
                                    // Ambil hanya bagian sebelum " - " jika ada
                                    $labelParts = explode(" - ", $item['ICD10Diagnosa']['label']);
                                    $diagnosaKode[] = $labelParts[0] ?? $item['ICD10Diagnosa']['label'];
                                }
                            }
                        }
                    @endphp
                
                    @foreach ($diagnosaKode as $kode)
                        <span class="normal medium block">{{ $kode }}</span>
                    @endforeach
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 86px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; text-align: center; height: 18px;"><strong>MASALAH</strong></td>
            </tr>
            <tr style="height: 68px;">
                <td style="width: 100%; height: 68px; vertical-align: top; text-align: left;">
                    @if (!empty($data))
                        &nbsp;{{ $data['masalah'] ?? '-' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 345px;" border="1">
        <tbody>
            <tr style="height: 34px;">
                <td style="width: 24.1426%; text-align: center; height: 34px;" colspan="2"><strong>RENCANA TERAPI</strong></td>
                <td style="width: 16.3237%; text-align: center; height: 34px;"><strong>Kode ICD 9</strong></td>
            </tr>
            <tr style="height: 49px;">
                <td style="width: 24.1426%; height: 49px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['rencanaTerapiO2']) && $data['rencanaTerapiO2'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="rencanaTerapiO2" name="rencanaTerapiO2" <?= $checked; ?>>
                    <label for="rencanaTerapiO2"> O2 :</label>
                </td>
                <td style="width: 59.5336%; height: 49px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['rencanaTerapiO2Ket'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.3237%; height: 311px; vertical-align: top; text-align: left;" rowspan="6">
                    @php
                        $diagnosaKode = [];
                
                        if (!empty($data['diagnosaICD9']) && is_array($data['diagnosaICD9'])) {
                            foreach ($data['diagnosaICD9'] as $item) {
                                if (!empty($item['ICD9Diagnosa']['label'])) {
                                    // Ambil hanya bagian sebelum " - " jika ada
                                    $labelParts = explode(" - ", $item['ICD9Diagnosa']['label']);
                                    $diagnosaKode[] = $labelParts[0] ?? $item['ICD9Diagnosa']['label'];
                                }
                            }
                        }
                    @endphp
                
                    @foreach ($diagnosaKode as $kode)
                        <span class="normal medium block">{{ $kode }}</span>
                    @endforeach
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 24.1426%; height: 18px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['rencanaTerapiPemasanganInfusRL']) && $data['rencanaTerapiPemasanganInfusRL'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="rencanaTerapiPemasanganInfusRL" name="rencanaTerapiPemasanganInfusRL" <?= $checked; ?>>
                    <label for="rencanaTerapiPemasanganInfusRL"> Pemasangan Infus RL /
                        NaCL 0,9% / D5 / D10
                        Atau ... (tetes per menit
                        (makro / Mikro))</label>
                </td>
                <td style="width: 59.5336%; height: 18px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['rencanaTerapiPemasanganInfusRLTetes'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 58px;">
                <td style="width: 24.1426%; height: 58px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['rencanaTerapiInjeksi']) && $data['rencanaTerapiInjeksi'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="rencanaTerapiInjeksi" name="rencanaTerapiInjeksi" <?= $checked; ?>>
                    <label for="rencanaTerapiInjeksi"> Injeksi :</label>
                </td>
                <td style="width: 59.5336%; height: 58px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['rencanaTerapiInjeksiKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 63px;">
                <td style="width: 24.1426%; height: 58px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['rencanaTerapiOral']) && $data['rencanaTerapiOral'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="rencanaTerapiOral" name="rencanaTerapiOral" <?= $checked; ?>>
                    <label for="rencanaTerapiOral"> Oral :</label>
                </td>
                <td style="width: 59.5336%; height: 58px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['rencanaTerapiOralKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 60px;">
                <td style="width: 24.1426%; height: 58px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['rencanaTerapiDiet']) && $data['rencanaTerapiDiet'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="rencanaTerapiDiet" name="rencanaTerapiDiet" <?= $checked; ?>>
                    <label for="rencanaTerapiDiet"> Diet :</label>
                </td>
                <td style="width: 59.5336%; height: 58px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['rencanaTerapiDietKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 63px;">
                <td style="width: 24.1426%; height: 58px; border-right-style: hidden; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['rencanaTerapiLainLain']) && $data['rencanaTerapiLainLain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="rencanaTerapiLainLain" name="rencanaTerapiLainLain" <?= $checked; ?>>
                    <label for="rencanaTerapiLainLain"> Lain-lain :</label>
                </td>
                <td style="width: 59.5336%; height: 58px; border-bottom-style: hidden;">
                    @if (!empty($data))
                        &nbsp;{{ $data['rencanaTerapiLainLainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 86px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; text-align: center; height: 18px;"><strong>INSTRUKSI</strong></td>
            </tr>
            <tr style="height: 68px;">
                <td style="width: 100%; height: 68px; vertical-align: top; text-align: left;">
                    @if (!empty($data))
                        &nbsp;{{ $data['instruksi'] ?? '-' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 86px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; text-align: center; height: 18px;"><strong>SASARAN</strong></td>
            </tr>
            <tr style="height: 68px;">
                <td style="width: 100%; height: 68px; vertical-align: top; text-align: left;">
                    @if (!empty($data))
                        &nbsp;{{ $data['sasaran'] ?? '-' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 86px;" border="1">
        <tbody>
            <tr style="height: 18px;">
                <td style="width: 100%; text-align: center; height: 18px;"><strong>TINDAKAN/PROSEDUR</strong></td>
            </tr>
            <tr style="height: 68px;">
                <td style="width: 100%; height: 68px; vertical-align: top; text-align: left;">
                    @if (!empty($data))
                        &nbsp;{{ $data['tindakanProsedur'] ?? '-' }}
                    @endif
                </td>
            </tr>
        </tbody>
    </table>
    <table style="border-collapse: collapse; width: 100%; height: 225px; margin-top: 80px;" border="1">
        <tbody>
            <tr style="height: 35px;">
                <td style="width: 100%; text-align: center; height: 35px;" colspan="5"><strong>ORDER LIST DAN CATATAN WAKTU HASIL PEMERIKSAAN PENUNJANG &amp; KONSULTASI</strong></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 36px; text-align: center;" rowspan="2"><strong>Jenis Pemeriksaan</strong></td>
                {{-- <td style="width: 32.4554%; height: 18px; text-align: left;" colspan="2"><strong>&nbsp;Waktu : {{ \Carbon\Carbon::parse($data['waktuOrderList'])->translatedFormat('d F Y') }}</strong></td> --}}
                <td style="width: 32.4554%; height: 18px; text-align: left;" colspan="2"><strong>&nbsp;Waktu : </strong></td>
                <td style="width: 36.0493%; height: 36px; text-align: center;" rowspan="2"><strong>Keterangan</strong></td>
                <td style="width: 13.0042%; height: 36px; text-align: center;" rowspan="2"><strong>Kode ICD 9 CM</strong></td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 15.6104%; height: 18px; text-align: center;"><strong>Pemeriksaan/ Permintaan</strong></td>
                <td style="width: 16.845%; height: 18px; text-align: center;"><strong>Hasil</strong></td>
            </tr>
            <tr style="height: 75px;">
                <td style="width: 18.4911%; height: 75px;">EKG</td>
                <td style="width: 15.6104%; height: 75px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanEKG'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.845%; height: 75px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['hasilEKG'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 36.0493%; height: 75px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['keteranganEKG'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.0042%; height: 75px;">&nbsp;</td>
            </tr>
            <tr style="height: 79px;">
                <td style="width: 18.4911%; height: 79px;">Laboratorium</td>
                <td style="width: 15.6104%; height: 75px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanLab'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.845%; height: 75px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['hasilLab'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 36.0493%; height: 75px;">
                    @if (!empty($data))
                        &nbsp;{{ $data['keteranganLab'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.0042%; height: 75px;">&nbsp;</td>
            </tr>
            <tr style="height: 20px;">
                <td style="width: 18.4911%; height: 20px; border-bottom-style: hidden;">Radiologi :</td>
                <td style="width: 15.6104%; height: 75px;" rowspan="5">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanRadiologi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.845%; height: 75px;" rowspan="5">
                    @if (!empty($data))
                        &nbsp;{{ $data['hasilRadiologi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 36.0493%; height: 75px;" rowspan="5">
                    @if (!empty($data))
                        &nbsp;{{ $data['keteranganRadiologi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.0042%; height: 20px;" rowspan="5">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanThoraks']) && $data['pemeriksaanPermintaanThoraks'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanThoraks" name="pemeriksaanPermintaanThoraks" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanThoraks"> Thorax</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanSchedel']) && $data['pemeriksaanPermintaanSchedel'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanSchedel" name="pemeriksaanPermintaanSchedel" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanSchedel"> Schedel</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanRadLain']) && $data['pemeriksaanPermintaanRadLain'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanRadLain" name="pemeriksaanPermintaanRadLain" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanRadLain"> Lain-lain</label>
                </td>
            </tr>
            <tr>
                <td style="width: 18.4911%;">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanRadLainKet'] ?? '-' }}
                    @endif
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanCTScanKepala']) && $data['pemeriksaanPermintaanCTScanKepala'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanCTScanKepala" name="pemeriksaanPermintaanCTScanKepala" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanCTScanKepala"> CT Scan Kepala</label>
                </td>
                <td style="width: 15.6104%; height: 75px;" rowspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanCTScan'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.845%; height: 75px;" rowspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['hasilCTScan'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 36.0493%; height: 75px; " rowspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['keteranganCTScan'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.0042%; height: 18px;" rowspan="2">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; ">
                    <?php $checked = (isset($data['pemeriksaanPermintaanCTScanAbdomen']) && $data['pemeriksaanPermintaanCTScanAbdomen'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanCTScanAbdomen" name="pemeriksaanPermintaanCTScanAbdomen" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanCTScanAbdomen"> CT Scan Abdomen</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanUSGAbdomen']) && $data['pemeriksaanPermintaanUSGAbdomen'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanUSGAbdomen" name="pemeriksaanPermintaanUSGAbdomen" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanUSGAbdomen"> USG Abdomen</label>
                </td>
                <td style="width: 15.6104%; height: 75px;" rowspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanUSG'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.845%; height: 75px;" rowspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['hasilUSG'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 36.0493%; height: 75px;" rowspan="2">
                    @if (!empty($data))
                        &nbsp;{{ $data['keteranganUSG'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.0042%;" rowspan="2">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanUSGGinjal']) && $data['pemeriksaanPermintaanUSGGinjal'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanUSGGinjal" name="pemeriksaanPermintaanUSGGinjal" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanUSGGinjal"> USG Ginjal</label>
                </td>
            </tr>
            <tr style="height: 21px; border-bottom-style: hidden;">
                <td style="width: 18.4911%; height: 21px;">Konsultasi :</td>
                <td style="width: 15.6104%; height: 21px;" rowspan="6">
                    @if (!empty($data))
                        &nbsp;{{ $data['pemeriksaanPermintaanKonsultasi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 16.845%; height: 21px;" rowspan="6">
                    @if (!empty($data))
                        &nbsp;{{ $data['hasilKonsultasi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 36.0493%; height: 21px;" rowspan="6">
                    @if (!empty($data))
                        &nbsp;{{ $data['keteranganKonsultasi'] ?? '-' }}
                    @endif
                </td>
                <td style="width: 13.0042%; height: 21px;" rowspan="6">&nbsp;</td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanPenyakitDalam']) && $data['pemeriksaanPermintaanPenyakitDalam'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanPenyakitDalam" name="pemeriksaanPermintaanPenyakitDalam" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanPenyakitDalam"> Penyakit Dalam</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanJantung']) && $data['pemeriksaanPermintaanJantung'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanJantung" name="pemeriksaanPermintaanJantung" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanJantung"> Jantung</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanBedah']) && $data['pemeriksaanPermintaanBedah'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanBedah" name="pemeriksaanPermintaanBedah" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanBedah"> Bedah</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px; border-bottom-style: hidden;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanAnak']) && $data['pemeriksaanPermintaanAnak'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanAnak" name="pemeriksaanPermintaanAnak" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanAnak"> Anak</label>
                </td>
            </tr>
            <tr style="height: 18px;">
                <td style="width: 18.4911%; height: 18px;">
                    <?php $checked = (isset($data['pemeriksaanPermintaanSyaraf']) && $data['pemeriksaanPermintaanSyaraf'] == true) ? 'checked' : ''; ?>
                    <input type="checkbox" id="pemeriksaanPermintaanSyaraf" name="pemeriksaanPermintaanSyaraf" <?= $checked; ?>>
                    <label for="pemeriksaanPermintaanSyaraf"> Syaraf</label>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
