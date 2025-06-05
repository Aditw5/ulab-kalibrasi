<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengkajian Awal Pasien Rawat Inap</title>

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
            line-height: 1;
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
                <td><span class="styled-pre">RM D.1.1 Rev 01 (1/4)</span></td>
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
                PENGKAJIAN AWAL PASIEN RAWAT INAP
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">PENGKAJIAN KEPERAWATAN</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Masuk Ruang Rawat</span>
                        </td>
                        <td width="15%">
                            <span class="block">: {{ isset($data['namaruangan']['label']) ? $data['namaruangan']['label'] : '..........' }}</span>
                        </td>
                        <td width="15%">
                            <span class="block">Kelas</span>
                        </td>
                        <td width="15%">
                            <span class="block">: {{ isset($data['namakelas']['label']) ? $data['namakelas']['label'] : '..........' }}</span>
                        </td>
                        <td width="15%">
                            <span class="block">Tanggal / Pkl</span>
                        </td>
                        <td width="15%">
                            <span class="block">: {{ isset($data['tanggalWaktuRegistrasi']) ? $data['tanggalWaktuRegistrasi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Cara masuk RS</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['caraMasukRS']) && $data['caraMasukRS'] == 'Tenang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> IRJ</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['caraMasukRS']) && $data['caraMasukRS'] == 'Cemas' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> IGD</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['caraMasukRS']) && $data['caraMasukRS'] == 'Takut' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> IGH</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['caraMasukRS']) && $data['caraMasukRS'] == 'DokterPribadi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Dokter Pribadi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['caraMasukRS']) && $data['caraMasukRS'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Dokter yang merawat</span>
                        </td>
                        <td width="75%">
                            <span class="block">: {{ isset($data['dokter']['label']) ? $data['dokter']['label'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Diagnosis Medis</span>
                        </td>
                        <td width="75%">
                            <span class="block">: {{ isset($data['namadiagnosa']['label']) ? $data['namadiagnosa']['label'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="33%">
                            <span class="block">Tiba di ruang rawat dengan cara</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['KursiRoda']) && $data['KursiRoda'] == 'Jalan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Jalan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tibaRS']) && $data['tibaRS'] == 'KursiRoda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kursi Roda</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tibaRS']) && $data['tibaRS'] == 'Brankar' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Brankar</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 31%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tibaRS']) && $data['tibaRS'] == 'Inkubator' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Inkubator</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Macam Kasus Trauma*</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 1%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['macamTrauma']) && $data['macamTrauma'] == 'KLL' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> KLL</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 1%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['macamTrauma']) && $data['macamTrauma'] == 'KDRT' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> KDRT</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 22%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['macamTrauma']) && $data['macamTrauma'] == 'KecelakaanKerja' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kecelakaan Kerja</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['macamTrauma']) && $data['macamTrauma'] == 'ChildAbuse(Kekerasan Anak)' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Child Abuse (Kekerasan Anak)</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">DATA KESEHATAN PASIEN</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">    
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="100%">
                            <span class="block tebal">RIWAYAT PENYAKIT SEKARANG</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Keluhan Utama</span>
                        </td>
                        <td width="25%">
                            <span class="block">: {{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '..........' }}</span>
                        </td>
                        <td width="20%">
                            <span class="block">Lama Keluhan</span>
                        </td>
                        <td width="30%">
                            <span class="block">: {{ isset($data['lamaKeluhan']) ? $data['lamaKeluhan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Mulai Timbul Keluhan</span>
                        </td>
                        <td width="25%">
                            <span class="block">: {{ isset($data['mulaiTimbulKeluhan']) ? $data['mulaiTimbulKeluhan'] : '..........' }}</span>
                        </td>
                        <td width="20%">
                            <span class="block">Sifat Keluhan</span>
                        </td>
                        <td width="30%">
                            <span class="block">: {{ isset($data['sifatKeluhan']) ? $data['sifatKeluhan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Nyeri']) && $data['Nyeri'] == 'Nyeri' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Deformitas']) && $data['Deformitas'] == 'Deformitas' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Deformitas</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Hilang_Fungsi']) && $data['Hilang_Fungsi'] == 'Hilang_Fungsi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hilang Fungsi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Kekakuan_Sendi']) && $data['Kekakuan_Sendi'] == 'Kekakuan_Sendi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kekakuan Sendi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 48%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Bengkak_Hematoma']) && $data['Bengkak_Hematoma'] == 'Bengkak_Hematoma' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Bengkak / Hematoma</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td style="width: 25%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Keterbatasan_Gerak']) && $data['Keterbatasan_Gerak'] == 'Keterbatasan_Gerak' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Keterbatasan Gerak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 75%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['Bengkak_Tumor']) && $data['Bengkak_Tumor'] == 'Bengkak_Tumor' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Bengkak Tumor</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="15%">
                            <span class="block">Faktor Pencetus</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['faktorPencetus']) && $data['faktorPencetus'] == 'Trauma' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Trauma</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['faktorPencetus']) && $data['faktorPencetus'] == 'Infeksi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Infeksi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 19%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['faktorPencetus']) && $data['faktorPencetus'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['faktorPencetus']) && $data['faktorPencetus'] == 'LainLain' ? $data['faktorPencetusLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="26%">
                            <span class="block">Perjalanan Penyakit</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['perjalananPenyakit']) && $data['perjalananPenyakit'] == 'Akut' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Akut</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['perjalananPenyakit']) && $data['perjalananPenyakit'] == 'Kronis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kronis</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Pengobatan yang telah dilakukan</span>
                        </td>
                        <td width="65%">
                            <span class="block">: {{ isset($data['pengobatanDilakukan']) ? $data['pengobatanDilakukan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Riwayat Penyakit Dahulu</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Diabetesmelitus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diabetes Melitus</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Hepatitis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hepatitis</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Stroke' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Stroke</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Ginjal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ginjal</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 24%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Hipertensi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hipertensi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="26%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 6%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'TBC' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> TBC</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Jantung' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Jantung</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'Keganasan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Keganasan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 48%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, sebutkan: {{ isset($data['riwayatPenyakitDahulu']) && $data['riwayatPenyakitDahulu'] == 'LainLain' ? $data['riwayatPenyakitDahuluLain'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="15%">
                            <span class="block">Pernah dirawat</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 4%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernahDirawat']) && $data['pernahDirawat'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernahDirawat']) && $data['pernahDirawat'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td width="5%">
                            <span class="block">Kapan</span>
                        </td>
                        <td width="13%">
                            <span class="block">: 
                                @if(isset($data['pernahDirawat']) && $data['pernahDirawat'] == 'Ya')
                                    {{ \Carbon\Carbon::parse($data['kapanDirawat'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }}
                                @else
                                    ..........
                                @endif
                            </span>
                        </td>
                        <td width="6%">
                            <span class="block">Dimana</span>
                        </td>
                        <td width="13%">
                            <span class="block">: {{ isset($data['pernahDirawat']) && $data['pernahDirawat'] == 'Ya' ? $data['dimanaDirawat']['label'] : '..........' }}</span>
                        </td>
                        <td width="10%">
                            <span class="block">Sakit apa</span>
                        </td>
                        <td width="23%">
                            <span class="block">: {{ isset($data['pernahDirawat']) && $data['pernahDirawat'] == 'Ya' ? $data['sakitApa']['label'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Riwayat operasi</span>
                        </td>
                        <td width="65%">
                            <span class="block">: {{ isset($data['riwayatOperasi']) ? $data['riwayatOperasi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Obat yang saat ini digunakan</span>
                        </td>
                        <td width="65%">
                            <span class="block">: {{ isset($data['obatDigunakanSaatIni']) ? $data['obatDigunakanSaatIni'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Apakah ada terapi komplementari</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 15%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terapiKomplementari']) && $data['terapiKomplementari'] == 'JamuHerbal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Jamu/Herbal</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hubunganPterapiKomplementariasien']) && $data['terapiKomplementari'] == 'Acupunture' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Acupuncture</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terapiKomplementari']) && $data['terapiKomplementari'] == 'PijatMassage' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pijat/Massage</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 29%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terapiKomplementari']) && $data['terapiKomplementari'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="30%">
                            <span class="block">Riwayat Alergi Obat / Makanan</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 19%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatAlergiObatMakanan']) && $data['riwayatAlergiObatMakanan'] == 'TidakAda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak ada / No</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 14%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatAlergiObatMakanan']) && $data['riwayatAlergiObatMakanan'] == 'Ada' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada / Yes,</td>
                                </tr>
                            </table>
                        </td>
                        <td width="9%">
                            <span class="block">sebutkan:</span>
                        </td>
                        <td width="27%">
                            <span class="block"> {{ isset($data['riwayatAlergiObatMakanan']) && $data['riwayatAlergiObatMakanan'] == 'Ada' ? $data['riancianRiwayatAlergiObatMakanan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="25%">
                            <span class="block">Apakah ada kebiasaan</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 23%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Merokok' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Merokok</td>
                                </tr>
                            </table>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] !== 'Merokok' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 8%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Merokok' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya ,</td>
                                </tr>
                            </table>
                        </td>
                        <td width="35%">
                            <span class="block">{{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Merokok' ? $data['jumlahKebiasaanRokok'] : '..........' }} batang/hari</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="26%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 23%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Alkohol' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Alkohol</td>
                                </tr>
                            </table>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] !== 'Alkohol' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 8%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Alkohol' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya ,</td>
                                </tr>
                            </table>
                        </td>
                        <td width="35%">
                            <span class="block">{{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Alkohol' ? $data['jumlahKebiasaanAlkohol'] : '..........' }} gelas/hari</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="26%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 23%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'ObatTidur_Narkoba' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Obat Tidur/Narkoba</td>
                                </tr>
                            </table>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] !== 'ObatTidur_Narkoba' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 43%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'ObatTidur_Narkoba' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="26%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 23%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Olahraga' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Olahraga</td>
                                </tr>
                            </table>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] !== 'Olahraga' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 43%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebiasaan']) && $data['kebiasaan'] == 'Olahraga' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">DATA KESEHATAN PASIEN</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Apakah dalam keadaan hamil</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keadaanHamil']) && $data['keadaanHamil'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 8%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keadaanHamil']) && $data['keadaanHamil'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya ,</td>
                                </tr>
                            </table>
                        </td>
                        <td width="49%">
                            <span class="block">Perkiraan Kelahiran: {{ isset($data['keadaanHamil']) && $data['keadaanHamil'] == 'Ya' ? $data['perkiraanKelahiran'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Apakah sedang menyusui</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['menyusui']) && $data['menyusui'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 57%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['menyusui']) && $data['menyusui'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <span class="block">Riwayat Kelahiran</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatKelahiran']) && $data['riwayatKelahiran'] == 'Spontan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Spontan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatKelahiran']) && $data['riwayatKelahiran'] == 'Operasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Operasi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 17%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatKelahiran']) && $data['riwayatKelahiran'] == 'CukupBulan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Cukup Bulan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 27%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatKelahiran']) && $data['riwayatKelahiran'] == 'KurangBulan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kurang Bulan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: 1px solid black;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">RIWAYAT IMUNISASI</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td style="width: 5%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiBCG']) && $data['riwayatImunisasiBCG'] == 'BCG' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> BCG</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 5%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiDPT']) && $data['riwayatImunisasiDPT'] == 'DPT' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> DPT</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 8%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiPolio']) && $data['riwayatImunisasiPolio'] == 'Polio' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Polio</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 8%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiCampak']) && $data['riwayatImunisasiCampak'] == 'Campak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Campak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiHepatitisB']) && $data['riwayatImunisasiHepatitisB'] == 'HepatitisB' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hepatitis B</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 5%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiPCV']) && $data['riwayatImunisasiPCV'] == 'PCV' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> PCV</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiVericela']) && $data['riwayatImunisasiVericela'] == 'Vericela' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Varicela</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiTypoid']) && $data['riwayatImunisasiTypoid'] == 'Typoid' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Typoid</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 34%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiRotervirus']) && $data['riwayatImunisasiRotervirus'] == 'Rotervirus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Rotavirus</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td style="width: 5%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiMMR']) && $data['riwayatImunisasiMMR'] == 'MMR' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> MMR</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiInfluenza']) && $data['riwayatImunisasiInfluenza'] == 'Influenza' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Influenza</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiPnemuokokus']) && $data['riwayatImunisasiPnemuokokus'] == 'Pnemuokokus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pneumokokus</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 5%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiHPV']) && $data['riwayatImunisasiHPV'] == 'HPV' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> HPV</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiTetanus']) && $data['riwayatImunisasiTetanus'] == 'Tetanus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tetanus</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiZooster']) && $data['riwayatImunisasiZooster'] == 'Zooster' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Zooster</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 14%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiMeningitis']) && $data['riwayatImunisasiMeningitis'] == 'Meningitis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Meningitis</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 31%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiHIB']) && $data['riwayatImunisasiHIB'] == 'HIB' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> HIB</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td style="width: 17%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiYellowFever']) && $data['riwayatImunisasiYellowFever'] == 'YellowFever' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Yellow Fever</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 83%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatImunisasiHepatitis']) && $data['riwayatImunisasiHepatitis'] == 'Hepatitis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hepatitis</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">RIWAYAT PENYAKIT KELUARGA</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitKeluargaAsma']) && $data['riwayatPenyakitKeluargaAsma'] == 'Asma' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Asma</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitKeluargaHipertensi']) && $data['riwayatPenyakitKeluargaHipertensi'] == 'Hipertensi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hipertensi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitKeluargaJantung']) && $data['riwayatPenyakitKeluargaJantung'] == 'Jantung' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Jantung</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitKeluargaDiabetesMelitus']) && $data['riwayatPenyakitKeluargaDiabetesMelitus'] == 'DiabetesMelitus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diabetes Melitus</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 50%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitKeluargaLainLain']) && $data['riwayatPenyakitKeluargaLainLain'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['riwayatPenyakitKeluargaLainLain']) && $data['riwayatPenyakitKeluargaLainLain'] == 'LainLain' ? $data['riwayatPenyakitKeluargaLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">KEADAAN UMUM</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 13%;">
                <span class="block">Kesadaran</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block"> {{ isset($data['kesadaran']) ? $data['kesadaran'] : '..........' }}</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 16%; border-left: 1px solid black;">
                <span class="block">Tekanan Darah</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block"> {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '..........' }} mmHg</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block">Suhu</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block"> {{ isset($data['suhu']) ? $data['suhu'] : '..........' }} °C</span>               
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 13%;">
                <span class="block">GCS</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block">E {{ isset($data['kesadaranE']) ? $data['kesadaranE'] : '....' }} M {{ isset($data['kesadaranM']) ? $data['kesadaranM'] : '....' }} V {{ isset($data['kesadaranV']) ? $data['kesadaranV'] : '....' }}</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 16%; border-left: 1px solid black;">
                <span class="block">Frekuensi Nadi</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block"> {{ isset($data['nadi']) ? $data['nadi'] : '..........' }} x / Menit</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block">Frekuensi Nafas</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%;">
                <span class="block"> {{ isset($data['nafas']) ? $data['nafas'] : '..........' }} x / Menit</span>               
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 13%; border-top: 1px solid black;">
                <span class="block">Berat Badan</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%; border-top: 1px solid black;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%; border-top: 1px solid black;">
                <span class="block"> {{ isset($data['beratBadan']) ? $data['beratBadan'] : '..........' }} Kg</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 16%; border-left: 1px solid black; border-top: 1px solid black;">
                <span class="block">Tinggi Badan</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%; border-top: 1px solid black;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%; border-top: 1px solid black;">
                <span class="block"> {{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '..........' }} Cm</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%; border-top: 1px solid black;">
                <span class="block">Lingkar Kepala</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 1%; border-top: 1px solid black;">
                <span class="block">:</span>               
            </td>
            <td style="text-align: left; padding-left: 4px; padding-top: 2px; width: 17%; border-top: 1px solid black;">
                <span class="block"> {{ isset($data['lingkarKepala']) ? $data['lingkarKepala'] : '..........' }} Cm</span>               
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">PENILAIAN FISIK</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; background-color: #d3d3d3; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Yang Dinilai</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; background-color: #d3d3d3; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Hasil Penilaian</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pernapasan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Vesikuner' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Vesikuner</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Wheezing' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Wheezing</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Ronchi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ronchi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Dispnea' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Dispnea</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Stridor' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Stridor</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'CupingHidung' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Cuping Hidung</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="12%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Batuk' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Batuk</td>
                                </tr>
                            </table>
                        </td>
                        <td width="12%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Skret' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Skret</td>
                                </tr>
                            </table>
                        </td>
                        <td width="26%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'OtotBahuNafas' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Otot Bantu Nafas</td>
                                </tr>
                            </table>
                        </td>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Sianosis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sianosis</td>
                                </tr>
                            </table>
                        </td>
                        <td width="40%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pernapasan']) && $data['pernapasan'] == 'Krepitasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Krepitasi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Sirkulasi / Cairan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Diaforesis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diaforesis</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Pucat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pucat</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Hematemesis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hematemesis</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Melena' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Melena</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Ascites' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ascites</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'AkralDingin' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Akral Dingin</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="20%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'AkralHangat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Akral Hangat</td>
                                </tr>
                            </table>
                        </td>
                        <td width="12%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Disritma' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Disritmia</td>
                                </tr>
                            </table>
                        </td>
                        <td width="12%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Anemesis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Anemis</td>
                                </tr>
                            </table>
                        </td>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'Epistaksis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Epistaksis</td>
                                </tr>
                            </table>
                        </td>
                        <td width="46%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sirkulasiCairan']) && $data['sirkulasiCairan'] == 'MukosaMulutKering' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Mukosa mulut kering</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Penglihatan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penglihatan']) && $data['penglihatan'] == 'Baik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Baik</td>
                                </tr>
                            </table>
                        </td>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penglihatan']) && $data['penglihatan'] == 'Buram' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Buram</td>
                                </tr>
                            </table>
                        </td>
                        <td width="28%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penglihatan']) && $data['penglihatan'] == 'TidakBisaMelihat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Bisa Melihat</td>
                                </tr>
                            </table>
                        </td>
                        <td width="52%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penglihatan']) && $data['penglihatan'] == 'PakaiALatBantu' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pakai Alat Bantu</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pendengaran</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pendengaran']) && $data['pendengaran'] == 'Baik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Baik</td>
                                </tr>
                            </table>
                        </td>
                        <td width="22%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pendengaran']) && $data['pendengaran'] == 'KurangJelas' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kurang Jelas</td>
                                </tr>
                            </table>
                        </td>
                        <td width="40%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pendengaran']) && $data['pendengaran'] == 'TidakBisaMendengar(Tuli)' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Bisa Mendengar (Tuli)</td>
                                </tr>
                            </table>
                        </td>
                        <td width="28%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pendengaran']) && $data['pendengaran'] == 'PakaiALatBantu' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pakai Alat Bantu</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pengecapan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pengecapan']) && $data['pengecapan'] == 'Baik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Baik</td>
                                </tr>
                            </table>
                        </td>
                        <td width="90%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pengecapan']) && $data['pengecapan'] == 'AdaGangguan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada Gangguan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Penciuman</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penciuman']) && $data['penciuman'] == 'Baik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Baik</td>
                                </tr>
                            </table>
                        </td>
                        <td width="90%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penciuman']) && $data['penciuman'] == 'AdaGangguan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada Gangguan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Bicara</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bicara']) && $data['bicara'] == 'Normal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Normal</td>
                                </tr>
                            </table>
                        </td>
                        <td width="90%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bicara']) && $data['bicara'] == 'AdaGangguan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada Gangguan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Peningkatan TIK</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="18%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['peningkatanTik']) && $data['peningkatanTik'] == 'TidakAda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Ada</td>
                                </tr>
                            </table>
                        </td>
                        <td width="21%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['peningkatanTik']) && $data['peningkatanTik'] == 'SakitKepala' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sakit Kepala</td>
                                </tr>
                            </table>
                        </td>
                        <td width="11%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['peningkatanTik']) && $data['peningkatanTik'] == 'Pusing' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pusing</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['peningkatanTik']) && $data['peningkatanTik'] == 'MuntahProyektil' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Muntah Proyektil</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Integritas Kulit</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'KondisiBersih' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kondisi Bersih</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'KondisiKotor' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kondisi Kotor</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'Laserasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Laserasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'JaringanParut' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Jaringan Parut</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'Bulae' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Bulae</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'Kemerahan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kemerahan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'Memar' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Memar</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'Ulceerasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ulceerasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'lukaDiarea' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Luka di area {{ isset($data['integrasiKulit']) && $data['integrasiKulit'] == 'lukaDiarea' ? $data['integrasiKulitLukaArea'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pakai Alat Bantu</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pakaiAlatBantu']) && $data['pakaiAlatBantu'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pakaiAlatBantu']) && $data['pakaiAlatBantu'] == 'Tongkat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tongkat</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pakaiAlatBantu']) && $data['pakaiAlatBantu'] == 'Walker' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Walker</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pakaiAlatBantu']) && $data['pakaiAlatBantu'] == 'KursiRoda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kursi Roda</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pakaiAlatBantu']) && $data['pakaiAlatBantu'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['pakaiAlatBantu']) && $data['pakaiAlatBantu'] == 'LainLain' ? $data['pakaiAlatBantuLain'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Terpasang Retraksi</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Beban: {{ isset($data['bebanRetraksi']) ? $data['bebanRetraksi'] : '..........' }} Kg</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terpasangRetaraksi']) && $data['terpasangRetaraksi'] == 'Terpasang_Gips' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Terpasang Gips</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terpasangRetaraksi']) && $data['terpasangRetaraksi'] == 'TerpasangInternal_eksternaFixasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Terpasang internal/eksternal fixasi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Eliminasi</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Pola BAB: {{ isset($data['eliminasiPolaBAB']) ? $data['eliminasiPolaBAB'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasi']) && $data['eliminasi'] == 'Konstipasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Konstipasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasi']) && $data['eliminasi'] == 'Diare' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diare</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasi']) && $data['eliminasi'] == 'Melena' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Melena</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Konsistensi: {{ isset($data['konsistensiEliminasi']) ? $data['konsistensiEliminasi'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Warna: {{ isset($data['warnaEliminasi']) ? $data['warnaEliminasi'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Bising Usus: {{ isset($data['bisingUsusEliminasi']) ? $data['bisingUsusEliminasi'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="35%">
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Pola BAB: {{ isset($data['eliminasiPolaBABxHr']) ? $data['eliminasiPolaBABxHr'] : '..........' }} x / Hari</td>
                                </tr>
                            </table>
                        </td>
                        <td width="65%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['warnaEliminasiXhr']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Warna {{ isset($data['warnaEliminasiXhr']) ? $data['warnaEliminasiXhr'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Hematuri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hematuri</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Poliuri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Poliuri</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Oligura' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Oliguria</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Disuria' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Disuria</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Inkontinensi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Inkontinensi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Kateter' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kateter</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['eliminasiXhr']) && $data['eliminasiXhr'] == 'Retensi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Retensi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Higiene</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['higieneOral']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Oral: {{ isset($data['higieneOral']) ? $data['higieneOral'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['higieneKulit']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kulit: {{ isset($data['higieneKulit']) ? $data['higieneKulit'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['higieneGenital']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Genital: {{ isset($data['higieneGenital']) ? $data['higieneGenital'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['higieneKuku']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kuku: {{ isset($data['higieneKuku']) ? $data['higieneKuku'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['higieneRambutKepala']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Rambut Kepala: {{ isset($data['higieneRambutKepala']) ? $data['higieneRambutKepala'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['higieneTelinga']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Telinga: {{ isset($data['higieneTelinga']) ? $data['higieneTelinga'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Seksualitas</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['seksualitas']) && $data['seksualitas'] == 'Impoten' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Impoten</td>
                                </tr>
                            </table>
                        </td>
                        <td width="35%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['seksualitas']) && $data['seksualitas'] == 'PerubahanSeksualita' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Perubahan Seksualitas</td>
                                </tr>
                            </table>
                        </td>
                        <td width="55%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['seksualitas']) && $data['seksualitas'] == 'Frigiditas' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Frigiditas</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;  border-top: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Aktivitas / Istirahat</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;  border-top: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">Lama Tidur: {{ isset($data['aktivitasIstirahatLamaTidur']) ? $data['aktivitasIstirahatLamaTidur'] : '..........' }} Jam</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'Insomnia' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Insomnia</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'Tremor' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tremor</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'MalaiseFatique' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Malaise / Fatique</td>
                                </tr>
                            </table>
                        </td>        
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'ROMMenurun' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> ROM Menurun</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'MobilitasDibatasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Mobilitas dibatasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'AktivitasDenganBantuan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Aktivitas dengan bantuan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['aktivitasIstirahat']) && $data['aktivitasIstirahat'] == 'Kontraktur' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kontraktur</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['aktivitasIstirahatFraktur']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Fraktur: {{ isset($data['aktivitasIstirahatFraktur']) ? $data['aktivitasIstirahatFraktur'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['aktivitasIstirahatTonus']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tonus otot: {{ isset($data['aktivitasIstirahatTonus']) ? $data['aktivitasIstirahatTonus'] : '..........' }} </</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ !empty($data['aktivitasIstirahatAlatBantu']) ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Alat bantu: {{ isset($data['aktivitasIstirahatAlatBantu']) ? $data['aktivitasIstirahatAlatBantu'] : '..........' }} </</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Musculo Skeletal</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Bentuk Tubuh:</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bentukTubuhMusculo']) && $data['bentukTubuhMusculo'] == 'Tegap' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tegap</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bentukTubuhMusculo']) && $data['bentukTubuhMusculo'] == 'Tidaktegap' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Tegap</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bentukTubuhMusculo']) && $data['bentukTubuhMusculo'] == 'Gibus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Gibus</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bentukTubuhMusculo']) && $data['bentukTubuhMusculo'] == 'Kiposis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kiposis</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bentukTubuhMusculo']) && $data['bentukTubuhMusculo'] == 'Lordosis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lordosis</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['bentukTubuhMusculo']) && $data['bentukTubuhMusculo'] == 'Skoliosis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Skoliosis</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tulang:</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'FakturTerbuka' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Fraktur terbuka, Grade {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'FakturTerbuka' ? $data['bentukTubuhTerbukaGrade'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'FakturTertutup' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Fraktur tertutup</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'FakturKompresi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Fraktur kompresi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'FakturPatologis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Fraktur patoligis</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'Amputasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Amputasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'BrusFraktur' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Brus fruktur</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'TumorTulang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tumor tulang</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tulangMusculo']) && $data['tulangMusculo'] == 'NyeriTulang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri tulang</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sendi:</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'Dislokasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Dislokasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'Infeksi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Infeksi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'Nyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'Odema' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Odema</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'ROM' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> ROM {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'ROM' ? $data['sendiROM'] : '..........' }} derajat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'kontrakturArea' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kontraktur area {{ isset($data['sendiMusculo']) && $data['sendiMusculo'] == 'kontrakturArea' ? $data['sendiKontrakturArea'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">PSIKOSOSIAL:</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'Denail(Menolak/Tidak Percaya)' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Denail (Menolak/tidak percaya)</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'AngerMarah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Anger (Marah)</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'BergainingTawarMenawar' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Bergaining (Tawar Menawar)</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'Depresi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Depresi (Depresi)</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'TidakSemangat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Semangat</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'MenerimaAcception' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Menerima (Acception)</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'RasaTertekann' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Rasa Tertekan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'CepatLelah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Cepat Lelah</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'SulitBerbicara' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sulit Berbicara</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'SulitKonsentrasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sulit Konsentrasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'MerasaBersalah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Merasa Bersalah</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['psikososial']) && $data['psikososial'] == 'SulitTidur' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sulit Tidur</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sosial support:</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sosialSupportSuami']) && $data['sosialSupportSuami'] == 'Suami' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Suami</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sosialSupportOrangTua']) && $data['sosialSupportOrangTua'] == 'OrangTua' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Orang tua</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sosialSupportMertua']) && $data['sosialSupportMertua'] == 'Mertua' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Mertua</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sosialSupportAnak']) && $data['sosialSupportAnak'] == 'Anak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Anak</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['sosialSupportKeluargaLain']) && $data['sosialSupportKeluargaLain'] == 'KeluargaLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Keluarga lain</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">BUDAYA PASIEN</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Agama : {{ isset($data['agama']['label']) ? $data['agama']['label'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Suku : {{ isset($data['suku']['label']) ? $data['suku']['label'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Bahasa : {{ isset($data['bahasa']) ? $data['bahasa'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> 
                                        Nilai Budaya yang dimiliki terkait dengan "Penyebab Penyakit/ masalah Kesehatan" sakit adalah:
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nilaiBudayaPenyebabPenyakit']) && $data['nilaiBudayaPenyebabPenyakit'] == 'Hukuman' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hukuman</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nilaiBudayaPenyebabPenyakit']) && $data['nilaiBudayaPenyebabPenyakit'] == 'Ujian' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ujian</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nilaiBudayaPenyebabPenyakit']) && $data['nilaiBudayaPenyebabPenyakit'] == 'Kesehatan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kesehatan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nilaiBudayaPenyebabPenyakit']) && $data['nilaiBudayaPenyebabPenyakit'] == 'Takdir' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Takdir</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nilaiBudayaPenyebabPenyakit']) && $data['nilaiBudayaPenyebabPenyakit'] == 'BuatanOrangLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Buatan orang lain</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nilaiBudayaPenyebabPenyakit']) && $data['nilaiBudayaPenyebabPenyakit'] == 'Keturunan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Keturunan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> 
                                        Kebiasaan pasien saat sakit (pola aktivitas dan istirahat): {{ isset($data['kebiasaanPasien']) ? $data['kebiasaanPasien'] : '..........' }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pola Komunikasi</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['polaKomunikasi']) && $data['polaKomunikasi'] == 'Normal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Normal</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['polaKomunikasi']) && $data['polaKomunikasi'] == 'Introvet' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Introvet</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['polaKomunikasi']) && $data['polaKomunikasi'] == 'Ekstrovet' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ekstrovet</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['polaKomunikasi']) && $data['polaKomunikasi'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['polaKomunikasi']) && $data['polaKomunikasi'] == 'LainLain' ? $data['polaKomunikasiLain'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pola Makan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['polaMakan']) && $data['polaMakan'] == 'Sehat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sehat</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['polaMakan']) && $data['polaMakan'] == 'TidakSehat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Sehat ; Makanan Pokok :</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['makananPokok']) && $data['makananPokok'] == 'Nasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nasi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['makananPokok']) && $data['makananPokok'] == 'SelainNasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Selain Nasi, {{ isset($data['makananPokok']) && $data['makananPokok'] == 'SelainNasi' ? $data['makananPokokLain'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pantang Makanan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pantangMakanan']) && $data['pantangMakanan'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pantangMakanan']) && $data['pantangMakanan'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya: {{ isset($data['pantangMakanan']) && $data['pantangMakanan'] == 'Ya' ? $data['pantangMakananKet'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> 
                                        Mempunyai pengaruh kepercayaan yang dianut terhadap penyakit:
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pengaruhKepercayaan']) && $data['pengaruhKepercayaan'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pengaruhKepercayaan']) && $data['pengaruhKepercayaan'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya : {{ isset($data['pengaruhKepercayaan']) && $data['pengaruhKepercayaan'] == 'Ya' ? $data['pengaruhKepercayaanKet'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>    
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">KEBUTUHAN BELAJAR / EDUKASI</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Aktivitas' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Aktivitas</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Kontrol' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kontrol</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Makan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Makan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Senam' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Senam</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Pengobatan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pengobatan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'RawatLuka' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Rawat luka</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Tumbang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tumbang</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Seksual' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seksual</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'ModifikasiLingkungan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Modifikasi lingkungan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'ManajemenStress' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Manajemen stress</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'PencegaahanPenyakit' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pencegahan penyakit</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanBelajar']) && $data['kebutuhanBelajar'] == 'Pencegaahan Komplikasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Pencegahan komplikasi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 40%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pemahaman tentang penyakit</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 60%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanPenyakit']) && $data['pemahamanPenyakit'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanPenyakit']) && $data['pemahamanPenyakit'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 40%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pemahaman tentang pengobatan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 60%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanPengobatan']) && $data['pemahamanPengobatan'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanPengobatan']) && $data['pemahamanPengobatan'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 40%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pemahaman tentang perawatan</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 60%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanPerawatan']) && $data['pemahamanPerawatan'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanPerawatan']) && $data['pemahamanPerawatan'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 40%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Pemahaman tentang Nutrisi / Diet</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 60%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="10%">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanNutrisi']) && $data['pemahamanNutrisi'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pemahamanNutrisi']) && $data['pemahamanNutrisi'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <div style="page-break-before: always;"></div>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: 1px solid black;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">HAMBATAN UNTUK MENERIMA EDUKASI</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-bottom: none;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'TidakAda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak ada</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaGangguanPengelihatan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada gangguan penglihatan</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaGangguanPendengaran' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada gangguan pendengaran</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'BelumMelekHuruf' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Belum Melek Huruf</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%; border-top: none;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaGangguanEmosi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada gangguan emosi</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaGangguanFisik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada gangguan fisik</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaGangguanKognitif' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada gangguan kognitif</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'KeterbatasanMotifasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Keterbatasan Motifasi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaKeterbatasanDalamHalBudayaSpiritualAgama' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada keterbatasan dalam hal Budaya / Spritual / Agama</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hambatanMenerimaNutrisi']) && $data['hambatanMenerimaNutrisi'] == 'AdaKeterbatasanDalamBerbahasa' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada keterbatasan dalam bahasa</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">SKRINING NUTRISI</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">Indikator Penilaian Malnutrisi</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">Penilaian</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">1. Apakah Indeks Masa Tubuh (IMT) &lt; 18,5 Kg/m<sup>2</sup> atau &gt; 25 Kg/m<sup>2</sup></span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['indeksMasaTubuh']) && $data['indeksMasaTubuh'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['indeksMasaTubuh']) && $data['indeksMasaTubuh'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">2. Apakah pasien kehilangan berat badan 5% dalam waktu 3 bulan terakhir?</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kehilanganBB']) && $data['kehilanganBB'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kehilanganBB']) && $data['kehilanganBB'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">3. Apakah asupan makan pasien kurang dalam 1 minggu terakhir?</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['asupanMakananPasienKurang']) && $data['asupanMakananPasienKurang'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['asupanMakananPasienKurang']) && $data['asupanMakananPasienKurang'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">4. Apakah pasien menderita penyakit yang berat?</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penyakitBeratPasien']) && $data['penyakitBeratPasien'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penyakitBeratPasien']) && $data['penyakitBeratPasien'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; padding-top: 4px; padding-bottom: 4px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Cara menghitung : IMT = BB / TB<sup>2</sup> dalam M</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; padding-top: 4px; padding-bottom: 4px; width: 80%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Jika ada jawaban YA 1 atau lebih maka berarti sudah harus dikonsultasikan ke Gizi</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 20%; border-left: 1px solid black; border-bottom: 1px solid black;">
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">PENILAIAN RISIKO JATUH</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%; border-bottom: 1px solid black;">                
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Metode: </td>
                                    <td>
                                        <input type="checkbox" {{ isset($data['MetodeRisikoJatuh']) && $data['MetodeRisikoJatuh'] == 'Morse' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Morse</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Jumlah Skor: {{ isset($data['jumlahSkorRisikoJatuh']) ? $data['jumlahSkorRisikoJatuh'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,34%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Kategori: {{ isset($data['kategoriRisikoJatuh']) ? $data['kategoriRisikoJatuh'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">PENILAIAN RISIKO DECUBITUS</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%; border-bottom: 1px solid black;">                
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Metoda: </td>
                                    <td>
                                        <input type="checkbox" {{ isset($data['MetodaRisikoDecubitus']) && $data['MetodaRisikoDecubitus'] == 'Norton' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Norton</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Jumlah Skor: {{ isset($data['jumlahSkorRisikoDecubitus']) ? $data['jumlahSkorRisikoDecubitus'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,34%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Kategori: {{ isset($data['kategoriRisikoDecubitus']) ? $data['kategoriRisikoDecubitus'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">PENILAIAN RISIKO JATUH</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Apakah ada Keluhan Nyeri</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 66,67%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keluhanNyeri']) && $data['keluhanNyeri'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keluhanNyeri']) && $data['keluhanNyeri'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, Bila Ya bagaimana skala nyerinya: {{ isset($data['keluhanNyeri']) && $data['keluhanNyeri'] == 'Ya' ? $data['skalaKeluhanNyeri'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Metode Penilaian Nyeri</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 66,67%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['metodePenilaianNyeri']) && $data['metodePenilaianNyeri'] == 'VAS' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> VAS</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['metodePenilaianNyeri']) && $data['metodePenilaianNyeri'] == 'BPS' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> BPS</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['metodePenilaianNyeri']) && $data['metodePenilaianNyeri'] == 'WongBakerFACES' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Wong-Baker FACES</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 33,33%; border-bottom: 1px solid black;">
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 66,67%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block">Skala: {{ isset($data['skalaPenilaianNyeri']) ? $data['skalaPenilaianNyeri'] : '..........' }} </span>
                        </td>
                        <td>
                            <span class="block">Kategori: {{ isset($data['kategoriPenilaianNyeri']) ? $data['kategoriPenilaianNyeri'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 40%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Lokasi Nyeri</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 60%; border-left: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>   
                                    <td style="vertical-align: middle; transform: translateY(1px);">
                                        Apakah nyeri nya berpindah dari tempat satu ke tempat lain?
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 40%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <img src="img/asesmen_medis.png" style="width: 200px; padding-left:5px">
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 60%; border-left: 1px solid black; border-bottom: none;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr style="border-bottom: 1px solid black;">
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nyeriBerpindah']) && $data['nyeriBerpindah'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td colspan="2">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['nyeriBerpindah']) && $data['nyeriBerpindah'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Berapa lama nyeri ini?</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['lamaNyeri']) && $data['lamaNyeri'] == '<3Bulan=akut' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> &lt; 3 bulan = akut</td>
                                </tr>
                            </table>
                        </td>
                        <td colspan="2">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['lamaNyeri']) && $data['lamaNyeri'] == '>3Bulan=kronik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> &gt; 3 bulan = kronik</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Rasa Nyeri:</td>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'Tajam' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tajam</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'NyeriTumpul' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri tumpul</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiDitarik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti ditarik</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiDitusuk' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti ditusuk</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiDipukul' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti dipukul</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiDibakar' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti dibakar</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="border-bottom: 1px solid black;">
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiBerdenyut' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti berdenyut</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiDitikam' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti ditikam</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rasaNyeri']) && $data['rasaNyeri'] == 'SepertiKram' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seperti kram</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Seberapa sering anda mengalami nyeri ini? Berapa lama?</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Setiap: </td>
                                    <td>
                                        <input type="checkbox" {{ isset($data['seberapaSeringNyeri']) && $data['seberapaSeringNyeri'] == 'Setiap1-2Jam' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> 1-2 Jam</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['seberapaSeringNyeri']) && $data['seberapaSeringNyeri'] == 'Setiap3-4Jam' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> 3-4 Jam</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);">, selama</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['berapaLamaNyeri']) && $data['berapaLamaNyeri'] == 'Selama<30Menit' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> &gt; 30 menit</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['berapaLamaNyeri']) && $data['berapaLamaNyeri'] == 'Selama>30Menit' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> &gt; 30 menit</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Apa yang membuat nyeri berkurang atau bertambah parah?</td>
                                </tr>
                            </table>
                        </td>
                    </tr>                    
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 40%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Lokasi Nyeri diberi tanda arsiran</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 60%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penyebabNyeriBerkurangBertambah']) && $data['penyebabNyeriBerkurangBertambah'] == 'KompresHangat/Dingin' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kompres hangat/dingin</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penyebabNyeriBerkurangBertambah']) && $data['penyebabNyeriBerkurangBertambah'] == 'AktivitasDikurangi/Bertambah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Aktivitas dikurangi/bertambah</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <div style="page-break-before: always;"></div>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: 1px solid black;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%; border-bottom: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">DIAGNOSIS KEPERAWATAN (MASALAH)</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%; border-left: 1px solid black; border-bottom: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">RENCANA KEPERAWATAN</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 50%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'PenurunanKesdaran' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penurunan Kesadaran</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'Kejang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kejang</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'KetidakEfektifan/BersihanJalanPanas' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ketidakefektifan/bersihan jalan napas</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'Sesak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Sesak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'Nyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'GangguanHamadinamik' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Gangguan Hemadinamik</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'GangguanIntegritasKulit' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Gangguan Integritas Kulit</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'GangguanKeseimbanganCairandanElektrilitIntegritasKulit' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Gangguan Keseimbangan Cairan dan Elektrilit</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'PeningkatanSuhuTubuh' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Peningkatan suhu tubuh</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'Lain-Lain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain: {{ isset($data['diagnosisKeperawatan']) && $data['diagnosisKeperawatan'] == 'Lain-Lain' ? $data['diagnosisKeperawatanLain'] : '..........' }} </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 50%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">SKRINING FAKTOR RISIKO PASIEN PULANG</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">
                                        Rencana Tanggal Pasien Pulang : Apakah Pasien / Keluarga tahu rencana pulangnya? 
                                    </td>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pasienKeluargaTahuRencanaPulang']) && $data['pasienKeluargaTahuRencanaPulang'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pasienKeluargaTahuRencanaPulang']) && $data['pasienKeluargaTahuRencanaPulang'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 50%; border-bottom: 1px solid black; background-color: #d3d3d3;" colspan="2">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Faktor Risiko Pasien Pulang</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 7%; border-left: 1px solid black; border-bottom: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Ya</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 7%; border-left: 1px solid black; border-bottom: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Tidak</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Keterangan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">1.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah Pasien tinggal sendiri?</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['apakahPasienTinggalSendiri']) && $data['apakahPasienTinggalSendiri'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['apakahPasienTinggalSendiri']) && $data['apakahPasienTinggalSendiri'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketApakahPasienTinggalSendiri']) ? $data['ketApakahPasienTinggalSendiri'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">2.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah mereka khawatir ketika kembali kerumah</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['kawatirKetikaKembaliKerumah']) && $data['kawatirKetikaKembaliKerumah'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['kawatirKetikaKembaliKerumah']) && $data['kawatirKetikaKembaliKerumah'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketKawatirKetikaKembaliKerumah']) ? $data['ketKawatirKetikaKembaliKerumah'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">3.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah Pasien dirumah ada yang merawat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['apakhDirumahAdaYangMerawat']) && $data['apakhDirumahAdaYangMerawat'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['apakhDirumahAdaYangMerawat']) && $data['apakhDirumahAdaYangMerawat'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketApakhDirumahAdaYangMerawat']) ? $data['ketApakhDirumahAdaYangMerawat'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">4.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Bagaimana jenis tempat tinggal Pasien</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black" colspan="3">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['jenisTempatTinggalPasien']) && $data['jenisTempatTinggalPasien'] == 'Rumah' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Rumah</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['jenisTempatTinggalPasien']) && $data['jenisTempatTinggalPasien'] == 'Kost' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kost</td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['jenisTempatTinggalPasien']) && $data['jenisTempatTinggalPasien'] == 'Apartemen' ? 'checked' : '' }}/>
                                    </td>
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Apartemen</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">5.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah tempat tinggal ada tangga</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['tempatTinggalAdaTangga']) && $data['tempatTinggalAdaTangga'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['tempatTinggalAdaTangga']) && $data['tempatTinggalAdaTangga'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketTempatTinggalAdaTangga']) ? $data['ketTempatTinggalAdaTangga'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">6.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah Pasien memiliki tanggung jawab memelihara anak/keluarga atau peliharaan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['tanggungJawabMemelihara']) && $data['tanggungJawabMemelihara'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['tanggungJawabMemelihara']) && $data['tanggungJawabMemelihara'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketTanggungJawabMemelihara']) ? $data['ketTanggungJawabMemelihara'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">7.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah Ketika pulang masih ada perawatan lanjutan yang harus dilakukan di rumah (rawat luka dll)</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['perawatanLajutanDiruamah']) && $data['perawatanLajutanDiruamah'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['perawatanLajutanDiruamah']) && $data['perawatanLajutanDiruamah'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketPerawatanLajutanDiruamah']) ? $data['ketPerawatanLajutanDiruamah'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">8.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah Pasien pulang dengan jumlah obat lebih dari 6 Jenis / Macam Obat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['enamJenisObatSaatPulang']) && $data['enamJenisObatSaatPulang'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['enamJenisObatSaatPulang']) && $data['enamJenisObatSaatPulang'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketEnamJenisObatSaatPulang']) ? $data['ketEnamJenisObatSaatPulang'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">9.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Apakah Pasien mengajukan permohonan untuk pendampingan dari rumah sakit</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['permohonanPendampingan']) && $data['permohonanPendampingan'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['permohonanPendampingan']) && $data['permohonanPendampingan'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketPermohonanPendampingan']) ? $data['ketPermohonanPendampingan'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-bottom: 4px; padding-right: 0px; width: 4%; border-bottom: 1px solid black;">
                <span style="vertical-align: top; transform: translateY(1px);">10.</span>
            </td>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 46%; border-left: none; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td style="vertical-align: middle; transform: translateY(1px);">Bagaimana transportasi Pasien untuk pulang</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['transportasiSaatPulang']) && $data['transportasiSaatPulang'] == 'Ya' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 12px; padding-right: 0px; width: 7%; border-bottom: 1px solid black; border-left: 1px solid black">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        {!! isset($data['transportasiSaatPulang']) && $data['transportasiSaatPulang'] == 'Tidak' 
                                            ? '<input type="checkbox" checked />' : '' !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; vertical-align: top; padding-left: 4px; padding-right: 0px; padding-top: 6px; width: 36%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block"> {{ isset($data['ketTransportasiSaatPulang']) ? $data['ketTransportasiSaatPulang'] : '..........' }} </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 25%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">&nbsp;</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 25%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">&nbsp;</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 25%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">&nbsp;</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 25%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">&nbsp;</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 100%; border-bottom: 1px solid black;" colspan="4">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Yang Melakukan Kajian</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 33,33%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Tanggal & Jam</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 33,33%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Nama</span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 33,33%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block">Tanda Tangan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 33,33%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block"> {{ \Carbon\Carbon::parse($data['tglPembuatan'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 33,33%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block"> ({{ isset($data['petugasPerawat']['label']) ? $data['petugasPerawat']['label'] : '' }}) </span>
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: center; padding-left: 0px; padding-right: 0px; width: 33,33%; border-left: 1px solid black; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 50%;">
                            <img src="data:image/png;base64, {!! $tte !!}" style="max-width: 30%; height: auto;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- {{ dd($data) }} --}}

</body>

</html>
