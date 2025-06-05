<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Surat Pernyataan Pengakhiran MPP</title>

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

        .miring {
            font-style: italic;
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

        .tabel-poli td {
            padding: 0px 0px;
            line-height: 1;
            font-size: 12px;
        }

        .tabel-utama td {
            padding: 0px 0px;
            line-height: 1;
        }
    </style>
</head>


<body>

    <table class="salinan">
        <thead>
            <tr>
                <td><span class="styled-pre">D.21.4.L</span></td>
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
                        <!-- {{-- {{dd($data['registrasi']['objectdepartemenfk'])}} --}} -->
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
                SURAT PERNYATAAN PENGAKHIRAN <br> MANAJER PELAYANAN PASIEN
            </td>
        </tr>
    </table>

    <table style="border-collapse: collapse; width: 100%; height: 598px;" border="1">
        <tbody>
            <tr style="height: 598px;">
                <td style="width: 92.8767%; height: 598px;">&nbsp;</td>
                <td style="width: 7.12331%; height: 598px;">SURAT</td>
            </tr>
        </tbody>
    </table>
    
    

<!-- {{-- {{ dd($data) }} --}} -->

</body>

</html>
