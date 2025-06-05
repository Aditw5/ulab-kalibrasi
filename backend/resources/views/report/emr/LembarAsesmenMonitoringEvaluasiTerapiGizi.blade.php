<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Lembar Asesmen Monitoring Evaluasi Terapi Gizi</title>

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
                <td><span class="tebal-atas">LEMBAR ASESMEN, MONITORING & EVALUASI TERAPI GIZI</span></td>
            </tr>
        </thead>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse;border-bottom:none;">
        <thead>
            <tr>
                <th width="60%" style="border: 1px solid black">
                    <table width="100%">
                        <tr>
                            <td class="label-top">
                                <span class="medium normal">Nama Pasien</span>
                            </td>
                            <td class="label-top">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ isset($identitas['namapasien']) ? $identitas['namapasien'] : ''}}</span>
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
                                <span class="medium tebal">{{ \Carbon\Carbon::parse(isset($identitas['tgllahir']) ? $identitas['tgllahir'] : '')->format('d-m-Y') }}</span>
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
                                <span class="medium tebal">{{ isset($identitas['jeniskelamin']) ? $identitas['jeniskelamin'] : ''}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:35%;">
                                <span class="medium normal">Nomor RM</span>
                            </td>
                            <td style="width:2%;">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ isset($identitas['nocm']) ? $identitas['nocm'] : ''}}</span>
                            </td>
                        </tr>
                    </table>
                </th>
                <th width="40%" style="border: 1px solid black;vertical-align:baseline;position:relative;">
                    <table width="100%">
                        <tr>
                            <td style="width:35%;">
                                <span class="medium normal">Ruang</span>
                            </td>
                            <td style="width:2%;">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ isset($registrasi['namaruangan']) ? $registrasi['namaruangan'] : ''}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-top">
                                <span class="medium normal">Tanggal</span>
                            </td>
                            <td class="label-top">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ \Carbon\Carbon::parse(isset($identitas['tglPembuatan']) ? $identitas['tglPembuatan'] : '')->timezone('Asia/Jakarta')->format('d-m-Y') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-top">
                                <span class="medium normal">Jam</span>
                            </td>
                            <td class="label-top">
                                <span class="medium normal">:</span>
                            </td>
                            <td>
                                <span class="medium tebal">{{ \Carbon\Carbon::parse(isset($identitas['tglPembuatan']) ? $identitas['tglPembuatan'] : '')->timezone('Asia/Jakarta')->format('H:i:s') }}</span>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </thead>
    </table>
    <table width="100%" style="background: black;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
        <thead>
            <tr style="text-align: center;">
                <td><span class="tebal-atas">KRITERIA DIAGNOSIS MALNUTRISI</span></td>
            </tr>
        </thead>
    </table>
    <table width="100%" style="background: #d6d4d4;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
        <thead>
            <tr style="text-align: center;">
                <td><span class="tebal-atas-putih">MALNUTRISI SEDANG</span></td>
            </tr>
        </thead>
    </table>
    <table style="width:100%;border-collapse: collapse;border-bottom:none;">
        <tr>
            <td>
                <table style="border-collapse: collapse;border:none;" width="100%">
                    <tr>
                        <th class="inner-th" width="40%">
                            <span class="medium tebal" style="font-size: 12px;">KRITERIA ANAMNESIS &GEJALA KLINIS</span>
                        </th>
                        <th class="inner-th" width="20%">
                            <span class="medium tebal" style="font-size: 12px;">PENYAKIT ATAU CEDERA AKUT</span>
                        </th>
                        <th class="inner-th" width="20%">
                            <span class="medium tebal" style="font-size: 12px;">PENYAKIT KRONIS</span>
                        </th>
                        <th class="inner-th" width="20%">
                            <span class="medium tebal" style="font-size: 12px;">KONDISI SOSIAL DAN LINGKUNGAN</span>
                        </th>
                    </tr>
                    <tr>
                        <td class="inner-td">
                            <span class="medium tebal" style="font-size: 12px;">Asupan Energi</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['asupanEnergiPenyakitAtauCederaAkut75persen7hari']) && $identitas['asupanEnergiPenyakitAtauCederaAkut75persen7hari'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> &lt;75% dari kebutuhan energi selama >7 hari </span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['asupanEnergiPenyakitKronis75persen1bulan']) && $identitas['asupanEnergiPenyakitKronis75persen1bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">&lt;75% dari kebutuhan energy selama <u>&gt;</u> bulan </span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['asupanEnergiKondisiSosialLingkungan75persen3bulan']) && $identitas['asupanEnergiKondisiSosialLingkungan75persen3bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> &lt;75% dari kebutuhan energi selama <u>&gt;</u> bulan </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="inner-td">
                            <span class="medium tebal" style="font-size: 12px;">Kehilangan Berat badan </span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitAtauCederaAkut1sampai2persen1minggu']) && $identitas['kehilanganBBPenyakitAtauCederaAkut1sampai2persen1minggu'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> 1-2%/1 minggu </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitAtauCederaAkut5persen1bulan']) && $identitas['kehilanganBBPenyakitAtauCederaAkut5persen1bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> 5%/1 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan']) && $identitas['kehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> 7.5%/3 bulan </span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitKronis5persen1bulan']) && $identitas['kehilanganBBPenyakitKronis5persen1bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">5%/1 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitKronis7koma5persen3bulan']) && $identitas['kehilanganBBPenyakitKronis7koma5persen3bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">7.5%/3 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitKronis7koma10persen6bulan']) && $identitas['kehilanganBBPenyakitKronis7koma10persen6bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">10%/6 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBPenyakitKronis20persen1tahun']) && $identitas['kehilanganBBPenyakitKronis20persen1tahun'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">20%/1 tahun </span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganBBKondisiSosialLingkungan5persen1bulan']) && $identitas['kehilanganBBKondisiSosialLingkungan5persen1bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">5%/1 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBKondisiSosialLingkungan7koma5persen3bulan']) && $identitas['kehilanganBBKondisiSosialLingkungan7koma5persen3bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">7.5%/3 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBKondisiSosialLingkungan7koma10persen6bulan']) && $identitas['kehilanganBBKondisiSosialLingkungan7koma10persen6bulan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">10%/6 bulan </span><br>
                            <input type="checkbox" {{ isset($identitas['kehilanganBBKondisiSosialLingkungan20persen1tahun']) && $identitas['kehilanganBBKondisiSosialLingkungan20persen1tahun'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">20%/1 tahun </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="inner-td">
                            <span class="medium tebal" style="font-size: 12px;">Kehilangan Massa Lemak</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganMassaLemakPenyakitAtauCederaAkutRingan']) && $identitas['kehilanganMassaLemakPenyakitAtauCederaAkutRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Ringan</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganMassaLemakPenyakitKronisRingan']) && $identitas['kehilanganMassaLemakPenyakitKronisRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Ringan</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganMassaLemakKondisiSosialLingkunganRingan']) && $identitas['kehilanganMassaLemakKondisiSosialLingkunganRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Ringan</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="inner-td">
                            <span class="medium tebal" style="font-size: 12px;">Kehilangan Massa Otot</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganMassaOtotPenyakitAtauCederaAkutRingan']) && $identitas['kehilanganMassaOtotPenyakitAtauCederaAkutRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Ringan</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganMassaOtotPenyakitKronisRingan']) && $identitas['kehilanganMassaOtotPenyakitKronisRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Ringan</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['kehilanganMassaOtotKondisiSosialLingkunganRingan']) && $identitas['kehilanganMassaOtotKondisiSosialLingkunganRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Ringan</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="inner-td">
                            <span class="medium tebal" style="font-size: 12px;">Edema /Ascites </span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['edemaAscitesPenyakitAtauCederaAkutRingan']) && $identitas['edemaAscitesPenyakitAtauCederaAkutRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Ringan</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['edemaAscitesPenyakitKronisRingan']) && $identitas['edemaAscitesPenyakitKronisRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Ringan</span>
                        </td>
                        <td class="inner-td">
                            <input type="checkbox" {{ isset($identitas['edemaAscitesKondisiSosialLingkunganRingan']) && $identitas['edemaAscitesKondisiSosialLingkunganRingan'] == 'true' ? 'checked' : '' }} />
                            <span class="medium" style="font-size: 12px;position: relative;top:-7px">Ringan</span>
                        </td>
                    </tr>
                </table>
                <table width="100%" style="background: #d6d4d4;border-left: 1px solid black;border-right: 1px solid black">
                    <thead>
                        <tr style="text-align: center;">
                            <td><span class="tebal-atas-putih">MALNUTRISI BERAT</span></td>
                        </tr>
                    </thead>
                </table>
                <table style="width:100%;border-collapse: collapse;border-bottom:none;">
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;border:none;" width="100%">
                                <tr>
                                    <th class="inner-th" width="40%">
                                        <span class="medium tebal" style="font-size: 12px;">KRITERIA ANAMNESIS &GEJALA KLINIS</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">PENYAKIT ATAU CEDERA AKUT</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">PENYAKIT KRONIS</span>
                                    </th>
                                    <th class="inner-th" width="20%">
                                        <span class="medium tebal" style="font-size: 12px;">KONDISI SOSIAL DAN LINGKUNGAN</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Asupan Energi</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrsiBeratAsupanEnergiPenyakitAtauCederaAkut50persen5hari']) && $identitas['malnutrsiBeratAsupanEnergiPenyakitAtauCederaAkut50persen5hari'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> <u>&lt;</u> 50% dari kebutuhan energi selama <u>&gt;</u> 5 hari </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrsiBeratAsupanEnergiPenyakitKronis75persen1bulan']) && $identitas['malnutrsiBeratAsupanEnergiPenyakitKronis75persen1bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"><u>&lt;</u> 75% dari kebutuhan energi selama <u>&gt;</u> 1 bulan </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisBeratAsupanEnergiKondisiSosialLingkungan50persen1bulan']) && $identitas['malnutrisBeratAsupanEnergiKondisiSosialLingkungan50persen1bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> <u>&lt;</u> 50% dari kebutuhan energi selama <u>&gt;</u> 1 bulan </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Kehilangan Berat badan </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut2persen1minggu']) && $identitas['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut2persen1minggu'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> &gt; 2%/1 minggu </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut5persen1bulan']) && $identitas['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut5persen1bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> &gt; 5%/1 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan']) && $identitas['malnutrisiBeratKehilanganBBPenyakitAtauCederaAkut7koma5persen3bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> &gt; 7.5%/3 bulan </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitKronis5persen1bulan']) && $identitas['malnutrisiBeratKehilanganBBPenyakitKronis5persen1bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> &gt; 5%/1 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitKronis7koma5persen3bulan']) && $identitas['malnutrisiBeratKehilanganBBPenyakitKronis7koma5persen3bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 7.5%/3 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitKronis7koma10persen6bulan']) && $identitas['malnutrisiBeratKehilanganBBPenyakitKronis7koma10persen6bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 10%/6 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBPenyakitKronis20persen1tahun']) && $identitas['malnutrisiBeratKehilanganBBPenyakitKronis20persen1tahun'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 20%/1 tahun </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan5persen1bulan']) && $identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan5persen1bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 5%/1 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma5persen3bulan']) && $identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma5persen3bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 7.5%/3 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma10persen6bulan']) && $identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan7koma10persen6bulan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 10%/6 bulan </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan20persen1tahun']) && $identitas['malnutrisiBeratKehilanganBBKondisiSosialLingkungan20persen1tahun'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">&gt; 20%/1 tahun </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Kehilangan Massa Lemak</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganMassaLemakPenyakitAtauCederaAkutRingan']) && $identitas['malnutrisiBeratKehilanganMassaLemakPenyakitAtauCederaAkutRingan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Sedang</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganMassaLemakPenyakitKronisBerat']) && $identitas['malnutrisiBeratKehilanganMassaLemakPenyakitKronisBerat'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Berat</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganMassaLemakKondisiSosialLingkunganBerat']) && $identitas['malnutrisiBeratKehilanganMassaLemakKondisiSosialLingkunganBerat'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Berat</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Kehilangan Massa Otot</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganMassaOtotPenyakitAtauCederaAkutRingan']) && $identitas['malnutrisiBeratKehilanganMassaOtotPenyakitAtauCederaAkutRingan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Sedang</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganMassaOtotPenyakitKronisBerat']) && $identitas['malnutrisiBeratKehilanganMassaOtotPenyakitKronisBerat'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Berat</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratKehilanganMassaOtotKondisiSosialLingkunganBerat']) && $identitas['malnutrisiBeratKehilanganMassaOtotKondisiSosialLingkunganBerat'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Berat</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Edema /Ascites </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratEdemaAscitesPenyakitAtauCederaAkutRingan']) && $identitas['malnutrisiBeratEdemaAscitesPenyakitAtauCederaAkutRingan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Sedang</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratEdemaAscitesPenyakitKronisRingan']) && $identitas['malnutrisiBeratEdemaAscitesPenyakitKronisRingan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Berat</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratEdemaAscitesKondisiSosialLingkunganRingan']) && $identitas['malnutrisiBeratEdemaAscitesKondisiSosialLingkunganRingan'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Berat</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <span class="medium tebal" style="font-size: 12px;">Penurunan Kekuatan Tangan </span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratPenurunanTanganPenyakitAtauCederaAkutMenurun']) && $identitas['malnutrisiBeratPenurunanTanganPenyakitAtauCederaAkutMenurun'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Menurun</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratPenurunanTanganPenyakitKronisMenurun']) && $identitas['malnutrisiBeratPenurunanTanganPenyakitKronisMenurun'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Menurun</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratPenurunanTanganKondisiSosialLingkunganMenurun']) && $identitas['malnutrisiBeratPenurunanTanganKondisiSosialLingkunganMenurun'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px">Menurun</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <br>
                <br>
                <table width="100%" style="background: #d6d4d4;border-top: 1px solid black;border-left: 1px solid black;border-right: 1px solid black">
                    <thead>
                        <tr style="text-align: center;">
                            <td><span class="tebal-atas-putih">KRITERIA LAIN</span></td>
                        </tr>
                    </thead>
                </table>
                <table style="width:100%;border-collapse: collapse;border-bottom:none;">
                    <tr>
                        <td>
                            <table style="border-collapse: collapse;border:none;" width="100%">
                                <tr style="background: #d6d4d4;">
                                    <th class="inner-th" width="50%">
                                        <span class="medium tebal" style="font-size: 12px;">MALNUTRISI SEDANG</span>
                                    </th>
                                    <th class="inner-th" width="50%">
                                        <span class="medium tebal" style="font-size: 12px;">MASLNUTRISI BERAT</span>
                                    </th>
                                </tr>
                                <tr>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiSedangAntropometri']) && $identitas['malnutrisiSedangAntropometri'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Antropometri: IMT 16.0 - 16.9 kg/m<sup>2</sup> </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiSedangPemeriksaanLaboratorium']) && $identitas['malnutrisiSedangPemeriksaanLaboratorium'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> PemeriksaanLaboratorium :Albumin 2.9 - 2.5 g/dL</span>
                                    </td>
                                    <td class="inner-td">
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratAntropometri']) && $identitas['malnutrisiBeratAntropometri'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> Antropometri: IMT &lt; 16 kg/m<sup>2</sup> </span><br>
                                        <input type="checkbox" {{ isset($identitas['malnutrisiBeratPemeriksaanLaboratorium']) && $identitas['malnutrisiBeratPemeriksaanLaboratorium'] == 'true' ? 'checked' : '' }} />
                                        <span class="medium" style="font-size: 12px;position: relative;top:-7px"> PemeriksaanLaboratorium :Albumin &lt; 2.5 g/dL </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 2px">
                    <tr>
                        <td>
                            <span class="medium tebal" style="font-size:9.5pt"><u>CATATAN:</u></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="medium tebal" style="font-size:9.5pt">Diagnosis Malnutrisi ditegakkan apabila memenuhi minimal 2 dari 6 Kriteria DiagnosisMalnutrisi, ATAU memenuhi Kriteria Lain seperti Antropometri ATAU Pemeriksaan laboratorium.</span><span class="medium normal"><sup>(PNPK Malnutrisi Dewasa 2019. Hal. 30)</sup></span>
                        </td>
                    </tr>
                </table>
                <table style="margin-top: 2px">
                    <tr>
                        <td>
                            <span class="medium tebal" style="font-size:9.5pt"><u>KESIMPULAN:</u></span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox" {{ isset($identitas['kesimpulanMalnutrisiSedang']) && $identitas['kesimpulanMalnutrisiSedang'] == 'true' ? 'checked' : '' }} />
                            <span class="medium tebal" style="font-size: 12px;position: relative;top:-7px">MALNUTRISI SEDANG </span><br>
                            <input type="checkbox" {{ isset($identitas['kesimpulanMalnutrisiBerat']) && $identitas['kesimpulanMalnutrisiBerat'] == 'true' ? 'checked' : '' }} />
                            <span class="medium tebal" style="font-size: 12px;position: relative;top:-7px">MALNUTRISI BERAT</span>
                        </td>
                    </tr>
                </table>

</body>

</html>
