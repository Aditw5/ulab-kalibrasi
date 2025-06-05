<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Monitoring Evaluasi Terapi Gizi</title>

    <style>
        @page {
            padding: 0;
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .tebal {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .tebal-atas {
            font-weight: bold;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: white;
        }

        .tebal-putih {
            font-weight: bold;
            color: black;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .inner-table,
        .inner-th,
        .inner-td {
            border: 1px solid black;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .inner-td {
            padding: 3px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .normal {
            font-weight: normal;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .label-top {
            vertical-align: top;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .medium {
            font-size: 10.5pt;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        hr {
            border: 0.5px solid black;
            margin: 1px;
        }

        .styled-pre {
            font-weight: normal;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 10.5;
            color: black;
            display: unset;
        }
    </style>
</head>

<body>
    <table width="100%" style="background: black;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
        <thead>
            <tr style="text-align: center;">
                <td><span class="tebal-atas">Monitoring & Evaluasi Terapi Gizi</span></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="background: #d6d4d4;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
        <thead>
            <tr style="text-align: center;">
                <td><span class="tebal-atas-putih">Asupan Makan</span></td>
            </tr>
        </thead>
    </table>
    <table style="width:100%;border-collapse: collapse;border-bottom:none;">
        <tr>
            <td>
                <table style="border-collapse: collapse;border:none;" width="100%">
                    <tr>
                        <th class="inner-th" width="40%">
                            <span class="medium tebal" style="font-size: 12px;">Target Kebutuhan Energi & Protein </span>
                        </th>
                        <th class="inner-th" width="20%">
                            <span class="medium tebal" style="font-size: 12px;">Jenis & Jalur Terapi Gizi</span>
                        </th>
                    </tr>
                    <tr>
                        <td class="inner-td" width="40%">
                            <span class="medium" style="font-size: 12px;">{{ isset($identitas['targetKebutuhanEnergiProtein']) ? $identitas['targetKebutuhanEnergiProtein'] : ''}}</span>
                        </td>
                        <td class="inner-td" width="60%">
                            <input type="checkbox" {{ isset($identitas['jenisJalurTerapiGiziOral']) && $identitas['jenisJalurTerapiGiziOral'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Oral (Padat/Lunak)</span>
                            <input style="margin-left: 80px;" type="checkbox" {{ isset($identitas['jenisJalurTerapiGiziEternalNutrition']) && $identitas['jenisJalurTerapiGiziEternalNutrition'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Eternal Nutrition</span><br>
                            <input type="checkbox" {{ isset($identitas['jenisJalurTerapiGiziOralPlusONS']) && $identitas['jenisJalurTerapiGiziOralPlusONS'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Oral (Padat / Lunak) + ONS*</span>
                            <input style="margin-left: 30px;" type="checkbox" {{ isset($identitas['jenisJalurTerapiGiziEternalNutritionPlusParentalNurition']) && $identitas['jenisJalurTerapiGiziEternalNutritionPlusParentalNurition'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Enteral + Parenteral Nutrition</span><br>
                            <input type="checkbox" {{ isset($identitas['jenisJalurTerapiGiziOralDietCair']) && $identitas['jenisJalurTerapiGiziOralDietCair'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Oral (Diet Cair)</span>
                            <input style="margin-left: 102px;" type="checkbox" {{ isset($identitas['jenisJalurTerapiGiziTotalParenteralNutrition']) && $identitas['jenisJalurTerapiGiziTotalParenteralNutrition'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Total Parenteral Nutrition</span><br>
                            <span class="medium" style="font-size: 12px;position: relative;top: 0px">*ONS = Oral Nutritional Supplementation</span><br>
                        </td>
                    </tr>
                </table>
                <table style="width:100%;border-collapse: collapse;border-bottom:none;position: relative;top:-1px">
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;border:none;" width="100%">
                                <tr>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Tanggal</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Terapi Gizi</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Monitoring</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Evaluasi</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Keterangan</span>
                                    </th>
                                </tr>
                                @if (isset($data['diagnosisKerja']) && is_array($data['diagnosisKerja']))
                                @foreach ($data['diagnosisKerja'] as $item)
                                <tr>
                                    <td class="inner-td" style="text-align: center;">
                                        <span class="medium" style="font-size: 12px;">{{ \Carbon\Carbon::parse(isset($item['tglPembuatan']) ? $item['tglPembuatan'] : '')->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['terapiGizi']) ? $item['terapiGizi'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['monitoring']) ? $item['monitoring'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['evaluasi']) ? $item['evaluasi'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($item['keterangan']) ? $item['keterangan'] : '' }}</span>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="background: #d6d4d4;border-left: 1px solid black;border-right: 1px solid black;position: relative;top:-1px">
                    <thead>
                        <tr style="text-align: center;">
                            <td><span class="tebal-atas-putih">Antropometri & Pemeriksaan Fisik</span></td>
                        </tr>
                    </thead>
                </table>
                <table style="width:100%;border-collapse: collapse;border-bottom:none;">
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;border:none;" width="100%">
                                <tr>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Kriteria</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Monitoring</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Evaluasi</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Keterangan</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Berat Badan / IMT</span><br>
                                        <span class="medium tebal" style="font-size: 12px;">{{ isset($identitas['IMT']) ? $identitas['IMT'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisiBeratBadankMonitoring']) ? $identitas['AntropometriPemeriksaanFisiBeratBadankMonitoring'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisikBeratBadanEvaluasi']) ? $identitas['AntropometriPemeriksaanFisikBeratBadanEvaluasi'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium tebal" style="font-size: 12px;">Evaluasi Mingguan</span><br>
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguan']) ? $identitas['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguan'] : '' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium tebal" style="font-size: 12px;">Penurunan Kekuatan Tangan</span><br>
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisikPenurunanKekuatanTangan']) ? $identitas['AntropometriPemeriksaanFisikPenurunanKekuatanTangan'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisikPenurunanKekuatanTanganMonitoring']) ? $identitas['AntropometriPemeriksaanFisikPenurunanKekuatanTanganMonitoring'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisikPenurunanKekuatanTanganEvaluasi']) ? $identitas['AntropometriPemeriksaanFisikPenurunanKekuatanTanganEvaluasi'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium tebal" style="font-size: 12px;">Evaluasi Mingguan/Bulanan</span><br>
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguanBulanan']) ? $identitas['AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguanBulanan'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="background: #d6d4d4;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black;position: relative;top:-1px">
                    <thead>
                        <tr style="text-align: center;">
                            <td><span class="tebal-atas-putih">Pemeriksaan Laboratorium</span></td>
                        </tr>
                    </thead>
                </table>
                <table style="width:100%;border-collapse: collapse;border-bottom:none;position: relative;top:-2px">
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;border:none;" width="100%">
                                <tr>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Kriteria</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Monitoring</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Evaluasi</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">Keterangan</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium tebal" style="font-size: 12px;">Albumin</span><br>
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['pemeriksaanLabAlbumin']) ? $identitas['pemeriksaanLabAlbumin'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['pemeriksaanLaboratoriumAlbuminkMonitoring']) ? $identitas['pemeriksaanLaboratoriumAlbuminkMonitoring'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium" style="font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['pemeriksaanLaboratoriumAlbuminEvaluasi']) ? $identitas['pemeriksaanLaboratoriumAlbuminEvaluasi'] : '' }}</span>
                                    </td>
                                    <td class="inner-td" style="max-width: 20px;">
                                        <span class="medium tebal" style="font-size: 12px;">Data Dasar</span><br>
                                        <span class="medium tebal" style="font-size: 12px;position: relative;top:-9px">lalu Evaluasi Mingguan</span><br>
                                        <span class="medium" style="position: relative;top:-3px; font-size: 12px; display: inline-block; word-wrap: break-word; max-width: 100%;">{{ isset($identitas['pemeriksaanLaboratoriumAlbuminEvaluasiMingguan']) ? $identitas['pemeriksaanLaboratoriumAlbuminEvaluasiMingguan'] : '' }}</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 20px">
                    <tr>
                        <td>
                            <span class="medium tebal" style="font-size:9.5pt">*Evaluasi Mingguan/Bulanan dilakukan jika pasien dirawat lebih dari 1 minggu / bulan.</span><span class="medium normal"><sup>(PNPK Malnutrisi Dewasa 2019. Hal. 71-77)</sup></span>
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 2px">
                    <tr>
                        <td>
                            <span class="medium tebal" style="font-size:9.5pt"><u>Keterangan Tambahan:</u></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="medium" style="font-size:9.5pt">{{ isset($identitas['keteranganTambahan']) ? $identitas['keteranganTambahan'] : '' }}</span>
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 20px; margin-left: 400px">
                    <tr>
                        <td style="width: 55%">
                        </td>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <table>
                                            <tr>
                                                <td align="center">
                                                    <span class="normal medium block">Dokter Spesialis Gizi Klinis</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <img src="data:image/png;base64, {!! $tte !!}">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td align="center">
                                                    <span class="normal medium block">{{ isset($identitas['dokterSpesialisGizi']['label']) ? $identitas['dokterSpesialisGizi']['label'] : '' }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
</body>

</html>