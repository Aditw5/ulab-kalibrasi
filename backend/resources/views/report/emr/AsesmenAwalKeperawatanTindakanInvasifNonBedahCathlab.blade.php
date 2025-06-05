<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Asesmen Awal Keperawatan Tindakan Invasif Non Bedah</title>

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
                <td><span class="styled-pre">RM 2.8</span></td>
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
                ASESMEN AWAL KEPERAWATAN<br>TINDAKAN INVASIF NON BEDAH
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: 1px solid black; border-top: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 100%; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: center;">
                    <tr>
                        <td>
                            <span class="block tebal">DATA IDENTITAS</span>
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
                            <span class="block">Tanggal Masuk</span>
                        </td>
                        <td width="75%">
                            <span class="block">: {{ \Carbon\Carbon::parse($data['tanggalMasuk'])->setTimezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y \J\a\m H:i') }}</span>
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
                            <span class="block">Diagnosa Medis</span>
                        </td>
                        <td width="75%">
                            <span class="block">: {{ isset($data['namadiagnosa']['label']) ? $data['namadiagnosa']['label'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="25%">
                            <span class="block">Tujuan Tindakan</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'Angiografi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Angiografi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'Phlebectomy' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Phlebectomy</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 60%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'DSETEEEndoskopi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> DSE/TEE/Endoskopi</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px">
                    <tr>
                        <td width="26%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 27%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'PTCACathStabdyPCI' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> PTCA/Cath Stabdy PCI</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 21%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'EndovenousIase' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Endovenous Iase</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 26%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['tujuanTindakan']) && $data['tujuanTindakan'] == 'LainLain' ? $data['tujuanTindakanLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -12px;">
                    <tr>
                        <td width="25%">
                            <span class="block">Status Fungsional</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusFungsionalCathlab']) && $data['statusFungsionalCathlab'] == 'Mandiri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Mandiri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 67%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusFungsionalCathlab']) && $data['statusFungsionalCathlab'] == 'MenggunakanKursiRoda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Menggunakan kursi roda</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px">
                    <tr>
                        <td width="26%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 26%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusFungsionalCathlab']) && $data['statusFungsionalCathlab'] == 'JalanDenganBantuan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Jalan dengan bantuan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 48%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusFungsionalCathlab']) && $data['statusFungsionalCathlab'] == 'MenggunakanBrankar' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Menggunakan brankar</td>
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
                            <span class="block tebal">ASESMEN PRA TINDAKAN</span>
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
                        <td width="4%">
                            <span class="block">1.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Keluhan Utama</span>
                        </td>
                        <td width="75%">
                            <span class="block">: {{ isset($data['keluhanUtama']) ? $data['keluhanUtama'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td width="4%">
                            <span class="block">2.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Status Psikologis</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Tenang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tenang</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Takut' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Takut</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Gelisah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Gelisah</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'Marah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Marah</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 46%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['statusPsikologis']) && $data['statusPsikologis'] == 'LainLain' ? $data['statusPsikologisLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -4px;">
                    <tr>
                        <td width="4%">
                            <span class="block">3.</span>
                        </td>
                        <td width="96%">
                            <span class="block">Riwayat Penyakit Dahulu</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'Hipertensi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hipertensi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'Asma' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Asma</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'DiabetesMelitus' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diabetes Melitus</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'PenyakitJantungBawaan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penyakit Jantung Bawaan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 32%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'PenyakitGinjal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penyakit Ginjal</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'Kanker' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kanker</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 21%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'RiwayatOperasi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Riwayat Operasi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 25%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'RiwayatTuberkulosis' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Riwayat Tuberkulosis</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 44%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['riwayatPenyakitDahuluCathlab']) && $data['riwayatPenyakitDahuluCathlab'] == 'LainLain' ? $data['riwayatPenyakitDahuluCathlabLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="4%">
                            <span class="block">4.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Sistem Pernafasan</span>
                        </td>
                        <td width="30%">
                            <span class="block">: Warna sputum : {{ isset($data['warnaSputum']) ? $data['warnaSputum'] : '..........' }}</span>
                        </td>
                        <td width="21%">
                            <span class="block"> Konsistensi sputum :</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['konsistensiSputum']) && $data['konsistensiSputum'] == 'Kental' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Kental</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 17%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['konsistensiSputum']) && $data['konsistensiSputum'] == 'Encer' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Encer</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="4%">
                            <span class="block">5.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Sistem Pencernaan</span>
                        </td>
                        <td width="15%">
                            <span class="block">: Muntah darah</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['muntahDarah']) && $data['muntahDarah'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 53%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['muntahDarah']) && $data['muntahDarah'] == 'Tidak' ? 'checked' : '' }}/>
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
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="25%">
                            <span class="block"></span>
                        </td>
                        <td width="15%">
                            <span class="block">&nbsp; Muntah darah</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['muntahDarahYa']) && $data['muntahDarahYa'] == 'Normal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Normal</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['muntahDarahYa']) && $data['muntahDarahYa'] == 'Hitam' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Hitam</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 46%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['muntahDarahYa']) && $data['muntahDarahYa'] == 'DarahSegar' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Darah Segar</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">6.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Sistem Perkemihan</span>
                        </td>
                        <td width="75%">
                            <span class="block">: Urin / 24 jam : {{ isset($data['urin24Jam']) ? $data['urin24Jam'] : '..........' }} cc</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">7.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Riwayat Pengobatan</span>
                        </td>
                        <td width="20%">
                            <span class="block">: Double antiplatelet</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['doubleAntiplatelet']) && $data['doubleAntiplatelet'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 48%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['doubleAntiplatelet']) && $data['doubleAntiplatelet'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak, lama penggunaan : {{ isset($data['doubleAntiplatelet']) && $data['doubleAntiplatelet'] == 'Tidak' ? $data['doubleAntiplateletLamaPenggunaan'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="25%">
                            <span class="block"></span>
                        </td>
                        <td width="20%">
                            <span class="block">: Beta bloker</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['betaBloker']) && $data['betaBloker'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 48%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['betaBloker']) && $data['betaBloker'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak, lama penggunaan : {{ isset($data['betaBloker']) && $data['betaBloker'] == 'Tidak' ? $data['betaBlokerLamaPenggunaan'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="25%">
                            <span class="block"></span>
                        </td>
                        <td width="20%">
                            <span class="block">: Simarc</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['simarc']) && $data['simarc'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 48%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['simarc']) && $data['simarc'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak, lama penggunaan : {{ isset($data['simarc']) && $data['simarc'] == 'Tidak' ? $data['simarcLamaPenggunaan'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">8.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Riwayat Alergi</span>
                        </td>                        
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 14%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'TidakAda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Ada</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 60%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, sebutkan : {{ isset($data['riwayatAlergi']) && $data['riwayatAlergi'] == 'Ya' ? $data['riwayatAlergiSebutkan'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">9.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Tanda-Tanda Vital</span>
                        </td>
                        <td width="30%">
                            <span class="block">: Tekanan darah : {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '..........' }} mmHg</span>
                        </td>
                        <td width="20%">
                            <span class="block"> Nadi : {{ isset($data['nadi']) ? $data['nadi'] : '..........' }} x/mnt</span>
                        </td>
                        <td width="25%">
                            <span class="block"> Saturasi : {{ isset($data['saturasi']) ? $data['saturasi'] : '..........' }} %</span>
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
                            <span class="block"></span>
                        </td>
                        <td width="30%">
                            <span class="block">: Pernapasan : {{ isset($data['pernapasan']) ? $data['pernapasan'] : '..........' }} x/mnt</span>
                        </td>
                        <td width="45%">
                            <span class="block"> Suhu : {{ isset($data['suhu']) ? $data['suhu'] : '..........' }} °C</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">10.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Tes Allen Kanan</span>
                        </td>
                        <td width="25%">
                            <span class="block">: Radialis kanan, kondisi</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['radialisKanan']) && $data['radialisKanan'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 43%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['radialisKanan']) && $data['radialisKanan'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="25%">
                            <span class="block">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kiri</span>
                        </td>
                        <td width="25%">
                            <span class="block">: Radialis kiri, kondisi</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['radialisKiri']) && $data['radialisKiri'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 43%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['radialisKiri']) && $data['radialisKiri'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">11.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Arteri Dorsalis Pedis</span>
                        </td>
                        <td width="25%">
                            <span class="block">: Pedis kanan, kondisi</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pedisKanan']) && $data['pedisKanan'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 43%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pedisKanan']) && $data['pedisKanan'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -10px;">
                    <tr>
                        <td width="25%">
                            <span class="block"></span>
                        </td>
                        <td width="25%">
                            <span class="block">: Pedis kiri, kondisi</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pedisKiri']) && $data['pedisKiri'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 43%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['pedisKiri']) && $data['pedisKiri'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">12.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Berat Badan</span>
                        </td>
                        <td width="15%">
                            <span class="block">: {{ isset($data['beratBadan']) ? $data['beratBadan'] : '..........' }} Kg</span>
                        </td>
                        <td width="60%">
                            <span class="block"> Tinggi Badan : {{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '..........' }} Cm</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">13.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Keluhan Nyeri</span>
                        </td>                        
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keluhanNyeri']) && $data['keluhanNyeri'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 67%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keluhanNyeri']) && $data['keluhanNyeri'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, lokasi : {{ isset($data['keluhanNyeri']) && $data['keluhanNyeri'] == 'Ya' ? $data['keluhanNyeriLokasi'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td width="21%">
                            <span class="block">Pencetus Nyeri (P)</span>
                        </td>
                        <td width="25%">
                            <span class="block">: {{ isset($data['pencetusNyeri']) ? $data['pencetusNyeri'] : '..........' }}</span>
                        </td>
                        <td width="15%">
                            <span class="block"> Skala (S)</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="34%">
                            <span class="block">{{ isset($data['skala']) ? $data['skala'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td width="21%">
                            <span class="block">Kualitas (Q)</span>
                        </td>
                        <td width="25%">
                            <span class="block">: {{ isset($data['kualitas']) ? $data['kualitas'] : '..........' }}</span>
                        </td>
                        <td width="15%">
                            <span class="block"> Lamanya (T)</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="34%">
                            <span class="block">{{ isset($data['lamanya']) ? $data['lamanya'] : '..........' }} menit</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td width="21%">
                            <span class="block">Penjalaran (R)</span>
                        </td>                        
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penjalaranNyeri']) && $data['penjalaranNyeri'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 67%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['penjalaranNyeri']) && $data['penjalaranNyeri'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, ke : {{ isset($data['penjalaranNyeri']) && $data['penjalaranNyeri'] == 'Ya' ? $data['penjalaranNyeriKe'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -4px;">
                    <tr>
                        <td width="4%">
                            <span class="block">14.</span>
                        </td>
                        <td width="96%">
                            <span class="block">Kebutuhan Edukasi</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 16%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'ObatObatan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Obat-obatan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'DietdanNutrisi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diet dan Nutrisi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'DiagnosisdanManajemen' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diagnosis dan Manajemen</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'PerawatanLuka' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Perawatan Luka</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 34%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'RehabilitasiManajemenNyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Rehabilitasi Manajemen Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 28%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'DiagnostikNonInvasif' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Diagnostik Non Invasif</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 34%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'IntervensiNonBedah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Intervensi Non Bedah</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td width="4%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 96%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['kebutuhanEdukasi']) && $data['kebutuhanEdukasi'] == 'LainLain' ? $data['kebutuhanEdukasiLain'] : '..........' }}</td>
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
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="4%">
                            <span class="block">15.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Laboratorium</span>
                        </td>
                        <td width="25%">
                            <span class="block">: Hb : {{ isset($data['Hb']) ? $data['Hb'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> Ur : {{ isset($data['Ur']) ? $data['Ur'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> Anti HCV : {{ isset($data['antiHCV']) ? $data['antiHCV'] : '..........' }}</span>
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
                            <span class="block"></span>
                        </td>
                        <td width="25%">
                            <span class="block">: Ht : {{ isset($data['Ht']) ? $data['Ht'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> Cr : {{ isset($data['Cr']) ? $data['Cr'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> PT/INR : {{ isset($data['ptINR']) ? $data['ptINR'] : '..........' }}</span>
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
                            <span class="block"></span>
                        </td>
                        <td width="25%">
                            <span class="block">: Leukosit : {{ isset($data['leukosit']) ? $data['leukosit'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> HbSAg : {{ isset($data['hbSAg']) ? $data['hbSAg'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> PT/APTT : {{ isset($data['ptAPTT']) ? $data['ptAPTT'] : '..........' }}</span>
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
                            <span class="block"></span>
                        </td>
                        <td width="25%">
                            <span class="block">: Na : {{ isset($data['Na']) ? $data['Na'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> K : {{ isset($data['K']) ? $data['K'] : '..........' }}</span>
                        </td>
                        <td width="25%">
                            <span class="block"> GDS : {{ isset($data['GDS']) ? $data['GDS'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">16.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Skrining Jatuh</span>
                        </td>
                        <td width="19%">
                            <span class="block">: Skor : {{ isset($data['skorJatuh']) ? $data['skorJatuh'] : '..........' }}</span>
                        </td>
                        <td style="width: 17%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['risikoJatuh']) && $data['risikoJatuh'] == 'RisikoTinggi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Tinggi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 19%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['risikoJatuh']) && $data['risikoJatuh'] == 'RisikoSedang' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Sedang</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['risikoJatuh']) && $data['risikoJatuh'] == 'RisikoRendah' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Rendah</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="4%">
                            <span class="block">17.</span>
                        </td>
                        <td width="21%">
                            <span class="block">Hasil Echo</span>
                        </td>                        
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 14%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hasilEcho']) && $data['hasilEcho'] == 'TidakAda' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Ada</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 60%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hasilEcho']) && $data['hasilEcho'] == 'Ada' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ada, kesan : {{ isset($data['hasilEcho']) && $data['hasilEcho'] == 'Ada' ? $data['hasilEchoKesan'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -4px;">
                    <tr>
                        <td width="100%">
                            <span class="block tebal">Masalah Keperawatan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'PenurunanCurahJantung' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penurunan Curah Jantung</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'Nyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 25%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'RisikoPerdarahan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Perdarahan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'PenurunanPerfusiJaringan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penurunan Perfusi Jaringan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>                    
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'RisikoTinggiInfeksi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Tinggi Infeksi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'RisikoJatuh' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Jatuh</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 52%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);">{{ isset($data['masalahKeperawatan']) && $data['masalahKeperawatan'] == 'LainLain' ? $data['masalahKeperawatanLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -4px;">
                    <tr>
                        <td width="100%">
                            <span class="block tebal">Rencana Tindakan Keperawatan (Mandiri)</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td style="width: 27%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatan']) && $data['rencanaTindakanKeperawatan'] == 'ManajemenNyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Manajemen Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatan']) && $data['rencanaTindakanKeperawatan'] == 'MonitoringTandaVital' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Monitoring Tanda-Tanda Vital</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 38%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatan']) && $data['rencanaTindakanKeperawatan'] == 'MonitoringPerubahanKesadaran' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Monitoring Perubahan Kesadaran</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>                    
                        <td style="width: 27%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatan']) && $data['rencanaTindakanKeperawatan'] == 'MonitoringPerdarahan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Monitoring Perdarahan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 73%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatan']) && $data['rencanaTindakanKeperawatan'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);">{{ isset($data['rencanaTindakanKeperawatan']) && $data['rencanaTindakanKeperawatan'] == 'LainLain' ? $data['rencanaTindakanKeperawatanLain'] : '..........' }}</td>
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
                            <span class="block tebal">OBSERVASI INTRA DAN PASKA TINDAKAN</span>
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
                            <span class="block">
                                Observasi dimulai Intra sampai paska tindakan (Observasi setiap 5 s/d 15 menit)
                                <br>Observasi paska tindakan 15 menit selama 1 jam pertama, dilanjutkan 30 menit selama 1 jam kedua, dan setiap 1 jam selama 2 jam. (sesuai kebutuhan)
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 4px; padding-top: 4px; padding-bottom: 4px; width: 100%;">
                <table style="width: 100%; border: 1px solid black; border-collapse: collapse; position: relative; text-align: left;">
                    @php 
                        $colspan = 5 + (count($data['tandaVitalObservasiIntradanPaskaTindakan']) * 4); 
                    @endphp
                    <tr>
                        <td width="100%" style="text-align:center; border-bottom: 1px solid black;" colspan="{{ $colspan }}">
                            <span class="block">Jam</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="3%" style="border: 1px solid black; text-align:center;" rowspan="11">
                            <span class="block tebal">T<br>A<br>N<br>D<br>A<br><br>V<br>I<br>T<br>A<br>L</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block tebal">BP</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block tebal">N</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block tebal">P</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block tebal">S</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                        <td style="border: 1px solid black; text-align:center" colspan="4">
                            <span class="block tebal">{{ $tandaVital['jamTandaVital'] }}</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">200</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">200</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">44</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">40</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 181 && $tandaVital['BPTandaVital1'] <= 200) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 181 && $tandaVital['BPTandaVital2'] <= 200) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 181 && $tandaVital['NTandaVital'] <= 200) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 41 && $tandaVital['PTandaVital'] <= 44) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 40 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">180</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">180</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">40</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">39</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 161 && $tandaVital['BPTandaVital1'] <= 180) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 161 && $tandaVital['BPTandaVital2'] <= 180) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 161 && $tandaVital['NTandaVital'] <= 180) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 33 && $tandaVital['PTandaVital'] <= 40) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 39 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">160</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">160</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">32</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">38</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 141 && $tandaVital['BPTandaVital1'] <= 160) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 141 && $tandaVital['BPTandaVital2'] <= 160) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 141 && $tandaVital['NTandaVital'] <= 160) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 29 && $tandaVital['PTandaVital'] <= 32) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 38 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">140</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">140</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">28</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">37</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 121 && $tandaVital['BPTandaVital1'] <= 140) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 121 && $tandaVital['BPTandaVital2'] <= 140) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 121 && $tandaVital['NTandaVital'] <= 140) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 25 && $tandaVital['PTandaVital'] <= 28) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 37 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">120</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">120</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">24</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">36</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 101 && $tandaVital['BPTandaVital1'] <= 120) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 101 && $tandaVital['BPTandaVital2'] <= 120) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 101 && $tandaVital['NTandaVital'] <= 120) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 21 && $tandaVital['PTandaVital'] <= 24) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 36 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">100</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">100</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">20</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">35</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 81 && $tandaVital['BPTandaVital1'] <= 100) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 81 && $tandaVital['BPTandaVital2'] <= 100) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 81 && $tandaVital['NTandaVital'] <= 100) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 17 && $tandaVital['PTandaVital'] <= 20) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 35 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">80</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">80</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">16</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">34</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 61 && $tandaVital['BPTandaVital1'] <= 80) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 61 && $tandaVital['BPTandaVital2'] <= 80) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 61 && $tandaVital['NTandaVital'] <= 80) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 13 && $tandaVital['PTandaVital'] <= 16) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 34 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">60</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">60</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">12</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">33</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 41 && $tandaVital['BPTandaVital1'] <= 60) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 41 && $tandaVital['BPTandaVital2'] <= 60) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 41 && $tandaVital['NTandaVital'] <= 60) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 9 && $tandaVital['PTandaVital'] <= 12) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 33 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">40</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">40</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">8</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">32</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 21 && $tandaVital['BPTandaVital1'] <= 40) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 21 && $tandaVital['BPTandaVital2'] <= 40) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 21 && $tandaVital['NTandaVital'] <= 40) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 5 && $tandaVital['PTandaVital'] <= 8) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 32 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">20</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">20</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">4</span>
                        </td>
                        <td width="5%" style="border: 1px solid black; text-align:center">
                            <span class="block">31</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">
                                    {{ 
                                        (($tandaVital['BPTandaVital1'] >= 0 && $tandaVital['BPTandaVital1'] <= 20) ? 'u' : '') . 
                                        (($tandaVital['BPTandaVital2'] >= 0 && $tandaVital['BPTandaVital2'] <= 20) ? 'n' : '') 
                                    }}
                                </span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['NTandaVital'] >= 0 && $tandaVital['NTandaVital'] <= 20) ? 'N' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ ($tandaVital['PTandaVital'] >= 0 && $tandaVital['PTandaVital'] <= 4) ? 'P' : '' }}</span>
                            </td>
                            <td style="border: 1px solid black; text-align:center">
                                <span class="block">{{ $tandaVital['STandaVital'] == 31 ? 'S' : '' }}</span>
                            </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:left;" colspan="5">
                            <span class="block">Saturasi Oksigen</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                        <td style="border: 1px solid black;" colspan="4">
                            <span class="block">{{ $tandaVital['SOTandaVital'] }}</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:left;" colspan="5">
                            <span class="block">Pulsasi Distal</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                        <td style="border: 1px solid black;" colspan="4">
                            <span class="block">{{ $tandaVital['PDTandaVital'] }}</span>
                        </td>
                        @endforeach
                    </tr>
                    <tr>
                        <td width="5%" style="border: 1px solid black; text-align:left;" colspan="5">
                            <span class="block">Reflek Menelan</span>
                        </td>
                        @foreach ($data['tandaVitalObservasiIntradanPaskaTindakan'] as $tandaVital)
                        <td style="border: 1px solid black;" colspan="4">
                            <span class="block">{{ $tandaVital['RMTandaVital'] }}</span>
                        </td>
                        @endforeach
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
                        <td width="24%">
                            <span class="block">Pasien Tiba di RR Jam</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="9%">
                            <span class="block">{{ \Carbon\Carbon::parse($data['jamPasienTiba'])->setTimezone('Asia/Jakarta')->locale('id')->translatedFormat('H:i') }}</span>
                        </td>
                        <td width="22%">
                            <span class="block">Lokasi pungsi/sayatan</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="23%">
                            <span class="block">{{ isset($data['lokasiPungsiSayatan']) ? $data['lokasiPungsiSayatan'] : '..........' }}</span>
                        </td>
                        <td width="9%">
                            <span class="block">Jumlah</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="10%">
                            <span class="block">{{ isset($data['jumlahPungsiSayatan']) ? $data['jumlahPungsiSayatan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Terpasang Sheath</span>
                        </td>                   
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terpasangSheath']) && $data['terpasangSheath'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['terpasangSheath']) && $data['terpasangSheath'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td width="50%">
                            <span class="block">Lokasi : {{ isset($data['terpasangSheath']) && $data['terpasangSheath'] == 'Ya' ? $data['terpasangSheathLokasi'] : '..........' }}</span>
                        </td>    
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Pulsasi A.Radialis</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="5%">
                            <span class="block">Kanan</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiARadialisKanan']) && $data['PulsasiARadialisKanan'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 21%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiARadialisKanan']) && $data['PulsasiARadialisKanan'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td width="4%">
                            <span class="block">Kiri</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiARadialisKiri']) && $data['PulsasiARadialisKiri'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 31%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiARadialisKiri']) && $data['PulsasiARadialisKiri'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Pulsasi A.Dorsalis Pedis</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="5%">
                            <span class="block">Kanan</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiADorsalisKanan']) && $data['PulsasiADorsalisKanan'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 21%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiADorsalisKanan']) && $data['PulsasiADorsalisKanan'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td width="4%">
                            <span class="block">Kiri</span>
                        </td>
                        <td style="width: 7%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiADorsalisKiri']) && $data['PulsasiADorsalisKiri'] == 'Adekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Adekuat</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 31%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['PulsasiADorsalisKiri']) && $data['PulsasiADorsalisKiri'] == 'TidakAdekuat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak Adekuat</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Alat yang terpasang</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 12%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'IVCath' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> IV Cath</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 23%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'DowercathKondom' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Dowercath/Kondom</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'IABP' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> IABP</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'Ventilator' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ventilator</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 15%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'TPM' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> TPM</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="25%">
                            <span class="block"></span>
                        </td>
                        <td style="width: 12%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'PPM' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> PPM</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 63%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lain-lain, {{ isset($data['alatYangTerpasang']) && $data['alatYangTerpasang'] == 'LainLain' ? $data['alatYangTerpasangLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Jenis anestesi</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['jenisAnestesi']) && $data['jenisAnestesi'] == 'Umum' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Umum</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['jenisAnestesi']) && $data['jenisAnestesi'] == 'Lokal' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Lokal</td>
                                </tr>
                            </table>
                        </td>
                        <td width="20%">
                            <span class="block">Dosis Pemberian</span>
                        </td>
                        <td width="19%">
                            <span class="block">Pre prosedur</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="15%">
                            <span class="block">{{ isset($data['preProsedurAnestesi']) ? $data['preProsedurAnestesi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 10px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="65%">
                            <span class="block"></span>
                        </td>
                        <td width="19%">
                            <span class="block">Intra prosedur</span>
                        </td>
                        <td width="1%" style="padding-left: 3px;">
                            <span class="block">:</span>
                        </td>
                        <td width="15%">
                            <span class="block">{{ isset($data['intraProsedurAnestesi']) ? $data['intraProsedurAnestesi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 10px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="65%">
                            <span class="block"></span>
                        </td>
                        <td width="19%">
                            <span class="block">Post prosedur</span>
                        </td>
                        <td width="1%" style="padding-left: 3px;">
                            <span class="block">:</span>
                        </td>
                        <td width="15%">
                            <span class="block">{{ isset($data['postProsedurAnestesi']) ? $data['postProsedurAnestesi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="24%">
                            <span class="block">Sedasi</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="75%">
                            <span class="block">{{ isset($data['sedasi']) ? $data['sedasi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="24%">
                            <span class="block">Antikoagulan</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['Antikoagulan']) ? $data['Antikoagulan'] : '..........' }}</span>
                        </td>
                        <td width="20%">
                            <span class="block">Dosis Pemberian</span>
                        </td>
                        <td width="19%">
                            <span class="block">Pre prosedur</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="15%">
                            <span class="block">{{ isset($data['preProsedurAntikoagulan']) ? $data['preProsedurAntikoagulan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="65%">
                            <span class="block"></span>
                        </td>
                        <td width="19%">
                            <span class="block">Intra prosedur</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="15%">
                            <span class="block">{{ isset($data['intraProsedurAntikoagulan']) ? $data['intraProsedurAntikoagulan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="65%">
                            <span class="block"></span>
                        </td>
                        <td width="19%">
                            <span class="block">Post prosedur</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="15%">
                            <span class="block">{{ isset($data['postProsedurAntikoagulan']) ? $data['postProsedurAntikoagulan'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Hematoma</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hematoma']) && $data['hematoma'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hematoma']) && $data['hematoma'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, Lokasi: {{ isset($data['hematoma']) && $data['hematoma'] == 'Ya' ? $data['hematomaLokasi'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="10%">
                            <span class="block">Ukuran :</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['hematomaUkuran']) ? $data['hematomaUkuran'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Laserasi</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['laserasi']) && $data['laserasi'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['laserasi']) && $data['laserasi'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, Lokasi: {{ isset($data['laserasi']) && $data['laserasi'] == 'Ya' ? $data['laserasiLokasi'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                        <td width="10%">
                            <span class="block">Ukuran :</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['laserasiUkuran']) ? $data['laserasiUkuran'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Reaksi alergi</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['reaksiAlergi']) && $data['reaksiAlergi'] == 'Tidak' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Tidak</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 65%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['reaksiAlergi']) && $data['reaksiAlergi'] == 'Ya' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Ya, {{ isset($data['reaksiAlergi']) && $data['reaksiAlergi'] == 'Ya' ? $data['reaksiAlergiYa'] : '..........' }}</td>
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
                        <td width="100%">
                            <span class="block tebal">Keseimbangan Cairan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="14%">
                            <span class="block">Intake</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="14%">
                            <span class="block">Minum</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['keseimbanganCairanMinum']) ? $data['keseimbanganCairanMinum'] : '..........' }} ml</span>
                        </td>
                        <td width="14%">
                            <span class="block">Intake</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="14%">
                            <span class="block">Perdarahan</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['keseimbanganCairanPerdarahan']) ? $data['keseimbanganCairanPerdarahan'] : '..........' }} ml</span>
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
                            <span class="block"></span>
                        </td>
                        <td width="14%">
                            <span class="block">IV Line</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['keseimbanganCairanIVLine']) ? $data['keseimbanganCairanIVLine'] : '..........' }} ml</span>
                        </td>
                        <td width="15%">
                            <span class="block"></span>
                        </td>
                        <td width="14%">
                            <span class="block">Urine</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="20%">
                            <span class="block">{{ isset($data['keseimbanganCairanUrine']) ? $data['keseimbanganCairanUrine'] : '..........' }} ml</span>
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
                            <span class="block"></span>
                        </td>
                        <td width="25%">
                            <hr style="margin: 0; border: none; border-bottom: 1px solid black;">
                        </td>
                        <td width="25%">
                            <span class="block"></span>
                        </td>
                        <td width="25%">
                            <hr style="margin: 0; border: none; border-bottom: 1px solid black;">
                        </td>
                        <td width="10%">
                            <span class="block"></span>
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
                            <span class="block"></span>
                        </td>
                        <td width="20%">
                            <span class="block">
                                {{
                                    (isset($data['keseimbanganCairanMinum']) ? $data['keseimbanganCairanMinum'] : 0) +
                                    (isset($data['keseimbanganCairanIVLine']) ? $data['keseimbanganCairanIVLine'] : 0)
                                }} ml
                            </span>
                        </td>
                        <td width="30%">
                            <span class="block"></span>
                        </td>
                        <td width="20%">
                            <span class="block">
                                {{
                                    (isset($data['keseimbanganCairanPerdarahan']) ? $data['keseimbanganCairanPerdarahan'] : 0) +
                                    (isset($data['keseimbanganCairanUrine']) ? $data['keseimbanganCairanUrine'] : 0)
                                }} ml
                            </span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="100%">
                            <span class="block tebal">Instruksi Paska Tindakan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="100%">
                            <span class="block">Imobilisasi sampai jam {{ isset($data['jamImmobilisasi']) ? $data['jamImmobilisasi'] : '..........' }} (6-7 jam paska anestesi spinal)</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="14%">
                            <span class="block">EKG</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="35%">
                            <span class="block">{{ isset($data['EKG']) ? $data['EKG'] : '..........' }}</span>
                        </td>
                        <td width="14%">
                            <span class="block">Foto Rontgen</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="35%">
                            <span class="block">{{ isset($data['fotoRontgen']) ? $data['fotoRontgen'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="14%">
                            <span class="block">Antibiotik</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="35%">
                            <span class="block">{{ isset($data['antibiotik']) ? $data['antibiotik'] : '..........' }}</span>
                        </td>
                        <td width="14%">
                            <span class="block">Dosis</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="35%">
                            <span class="block">{{ isset($data['dosis']) ? $data['dosis'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="14%">
                            <span class="block">Hidrasi</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td width="85%">
                            <span class="block">{{ isset($data['hidrasi']) ? $data['hidrasi'] : '..........' }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -4px;">
                    <tr>
                        <td width="100%">
                            <span class="block tebal">Masalah Keperawatan</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'PenurunanCurahJantung' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penurunan Curah Jantung</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'Nyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 25%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'RisikoPerdarahan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Perdarahan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'PenurunanPerfusiJaringan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Penurunan Perfusi Jaringan</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>                    
                        <td style="width: 30%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'RisikoTinggiInfeksi' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Tinggi Infeksi</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 18%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'RisikoJatuh' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Risiko Jatuh</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 52%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);">{{ isset($data['masalahKeperawatanObservasi']) && $data['masalahKeperawatanObservasi'] == 'LainLain' ? $data['masalahKeperawatanObservasiLain'] : '..........' }}</td>
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
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="100%">
                            <span class="block tebal">Rencana Tindakan Keperawatan (Mandiri)</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>
                        <td style="width: 27%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatanObservasi']) && $data['rencanaTindakanKeperawatanObservasi'] == 'ManajemenNyeri' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Manajemen Nyeri</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 35%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatanObservasi']) && $data['rencanaTindakanKeperawatanObservasi'] == 'MonitoringTandaVital' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Monitoring Tanda-Tanda Vital</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 38%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatanObservasi']) && $data['rencanaTindakanKeperawatanObservasi'] == 'MonitoringPerubahanKesadaran' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Monitoring Perubahan Kesadaran</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 0px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px">
                    <tr>                    
                        <td style="width: 27%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatanObservasi']) && $data['rencanaTindakanKeperawatanObservasi'] == 'MonitoringPerdarahan' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Monitoring Perdarahan</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 73%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['rencanaTindakanKeperawatanObservasi']) && $data['rencanaTindakanKeperawatanObservasi'] == 'LainLain' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);">{{ isset($data['rencanaTindakanKeperawatanObservasi']) && $data['rencanaTindakanKeperawatanObservasi'] == 'LainLain' ? $data['rencanaTindakanKeperawatanObservasiLain'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: -6px;">
                    <tr>
                        <td width="24%">
                            <span class="block">Keputusan Rawat</span>
                        </td>
                        <td width="1%">
                            <span class="block">:</span>
                        </td>
                        <td style="width: 10%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keputusanRawat']) && $data['keputusanRawat'] == 'ODC' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> ODC</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 65%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['keputusanRawat']) && $data['keputusanRawat'] == 'Dirawat' ? 'checked' : '' }}/>
                                    </td>    
                                    <td style="vertical-align: middle; transform: translateY(1px);"> Dirawat, di {{ isset($data['keputusanRawat']) && $data['keputusanRawat'] == 'Dirawat' ? $data['keputusanRawatTempat'] : '..........' }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: 15px;">
                    <tr>
                        <td width="100%">
                            <span class="block">Cirebon, {{ \Carbon\Carbon::parse($data['tglPembuatan'])->setTimezone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y \J\a\m H:i') }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width="100%">
                            <span class="block">Yang mengobservasi,</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: 15px;">
                    <tr>
                        <td style="text-align: left; padding-left: 30px; padding-right: 0px; width: 50%;">
                            <img src="data:image/png;base64, {!! $tte !!}" style="max-width: 30%; height: auto;">
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 100%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left; margin-top: 5px;">
                    <tr>
                        <td width="100%">
                            <span class="block">({{ isset($data['petugasPerawat']['label']) ? $data['petugasPerawat']['label'] : '' }})</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {{-- {{ dd($data) }} --}}

</body>

</html>