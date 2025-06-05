<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pengkajian Awal Pasien Rawat Jalan</title>

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
                PENGKAJIAN AWAL PASIEN <br> RAWAT JALAN
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="text-align: center; padding-left: 4px; width: 15%; border-right: 1px solid black">
                <span class="block tebal">Poli:</span>
            </td>
            <td style="text-align: center; padding-left: 4px; width: 85%;">
                <span class="block tebal">Tgl/Jam Kunjungan:{{ \Carbon\Carbon::parse($data['tanggalWaktuKunjuangan'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }} Usia Saat Kunjungan: {{ isset($data['umur']) ? $data['umur'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none;">
        <tr>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal">
                    <tr>
                        <td style="vertical-align: middle;">•Anak</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Anak' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block tebal">ALASAN KUNJUNGAN:</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Anastesi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Anestesi' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block">{{ isset($data['alasanKunjunagn']) ? $data['alasanKunjunagn'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table style="vertical-align: middle;" class="tebal">
                    <tr>
                        <td style="vertical-align: middle; font-size: 12px;">Bedah:</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block tebal">BIOLOGI:</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Anak</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Anak' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 85%;"></td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Digesif</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Digesif' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black; background-color: #d3d3d3;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block tebal">RIWAYAT PSIKOSOSIAL:</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Ortopedi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Digesif' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td colspan="3" style="width: 60%;"><span class="block">Hubungan pasien dengan anggota keluarga</span></td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hubunganPasien']) && $data['namaruangan'] == 'Poliklinik Bedah Digesif' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Baik</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['hubunganPasien']) && $data['hubunganPasien'] == 'Baik' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Tidak Baik</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Syaraf</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Syaraf' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-bottom: 1px solid black; border-top: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td>
                            <span class="block tebal">Status Psikologis:</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Umum</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Umum' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-bottom: 1px solid black;">
                <table style="width: 100%; border-collapse: collapse; position: relative; text-align: left;">
                    <tr>
                        <td width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Tenang' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Tenang</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Cemas' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Cemas</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Takut' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Takut</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Marah' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Marah</td>
                                </tr>
                            </table>
                        </td>
                        <td style="width: 20%;">
                            <table style="vertical-align: middle;">
                                <tr>
                                    <td>
                                        <input type="checkbox" {{ isset($data['statusPsikologisPasien']) && $data['statusPsikologisPasien'] == 'Sedih' ? 'checked' : '' }}/>
                                    </td>                                    
                                    <td style="vertical-align: middle;"> Sedih</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position: relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="vertical-align: middle; text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Urologi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Urologi' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%;">
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table style="vertical-align: middle;" class="tebal">
                    <tr>
                        <td style="vertical-align: middle; font-size: 12px;">Gilut:</td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block">
                    <table class="tabel-utama" style="vertical-align: middle;">
                        <tr>
                            <td>
                                <input type="checkbox" {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'laporanBunuhdiri' ? 'checked' : '' }}/>
                            </td>
                            <td style="vertical-align: middle;"> Kecenderungan bunuh diri dilaporkan ke {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'laporanBunuhdiri' ? $data['ketLaporkan'] : '.....' }}</td>
                            <td>
                                <input type="checkbox" {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'kecenderuanganLain' ? 'checked' : '' }}/>
                            </td>
                            <td style="vertical-align: middle;"> Lain-lain, sebutkan {{ isset($data['spritualPasien']) && $data['spritualPasien'] == 'kecenderuanganLain' ? $data['ketKecenderuangan'] : '.....' }}</td>
                        </tr>
                    </table>
                </span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-right: 1px solid black;">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Gigi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Gigi' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
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
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-right: 1px solid black;">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Kon. Gigi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Konservasi Gigi' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 33,33%;">
                            <span class="block">TD: {{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '' }} mmHg</span>
                        </td>
                        <td style="width: 33,33%;">
                            <span class="block">Nadi: {{ isset($data['nadi']) ? $data['nadi'] : '' }} x/mnt</span>
                        </td>
                        <td style="width: 33,33%;">
                            <span class="block">Pernafasan: {{ isset($data['pernapasan']) ? $data['pernapasan'] : '' }} x/mnt</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Orthodonti</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Orthodonti' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
            <td style="text-align: left; width: 85%;">
                <table style="width: 100%; border-collapse: collapse; position: relative;">
                    <tr>
                        <td style="width: 33,33%;">
                            <span class="block">Suhu: {{ isset($data['suhu']) ? $data['suhu'] : '' }} °C</span>
                        </td>
                        <td style="width: 33,33%;">
                            <span class="block">TB: {{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '' }} cm</span>
                        </td>
                        <td style="width: 33,33%;">
                            <span class="block">BB: {{ isset($data['beratBadan']) ? $data['beratBadan'] : '' }} Kg</span>
                        </td>
                    </tr>
                </table>                
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-right: 1px solid black;">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Prostodonti</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Prostodonti' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;" class="miring">&nbsp;•Bedah Mulut</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Bedah Mulut' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block tebal">Berdasarkan <i>Malnutrition Screening Tool / (MST)</i></span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Gizi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Gizi' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
            <td style="text-align: left; padding-left: 4px; width: 85%;">
                <span class="block tebal"><u>Parameter Skor</u></span>
            </td>
        </tr>
    </table>
    <table class="standard" style="padding: 0px 0px; line-height: 1; border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Hemodialisa</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Hemodialisa' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Jantung</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Jantung' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Jiwa</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Jiwa' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Kulit</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Kulit & Kelamin' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Mata</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Mata' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Neurologi</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Neurologi' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Obgyn</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Kandungan' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•ODC</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Eksekutif Pakungwati (PEP)' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•P.Dalam</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Dalam' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Paru</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Paru' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•PTRM</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'PTRM' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">Rehab Med</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && strpos($data['namaruangan']['label'], 'Rehab Medik') !== false  
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•Seroja</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Seroja' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•THT</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik THT' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr>
                        <td style="vertical-align: middle;">•TumBang</td>
                        <td>
                            {!! isset($data['namaruangan']['label']) && $data['namaruangan']['label'] == 'Poliklinik Tumbuh Kembang Anak' 
                                ? '<input type="checkbox" checked />' : '' !!}
                        </td>
                    </tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-right: 1px solid black;">
                <table class="tabel-poli tebal" style="vertical-align: middle;">
                    <tr></tr>
                </table>
            </td>            
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; background-color: #d3d3d3;">
                <span class="block tebal">STATUS FUNGSIONAL:</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 85%; border-top: 1px solid black;" colspan="2">
                <span class="block tebal">SKRINING RISIKO CEDERA/ JATUH:</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="vertical-align:top; text-align: left; padding-left: 4px; padding-right: 0px; width: 2%;">
                <span class="block">a.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 83%;">
                <span class="block">Perhatikan cara berjalan pasien saat akan duduk di kursi. Apakah pasien tampak tidak seimbang (sempoyongan / limbung)?</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="vertical-align:top; text-align: left; padding-left: 4px; padding-right: 0px; width: 2%;">
                <span class="block">b.</span>
            </td>
            <td style="text-align: left; padding-left: 4px; padding-right: 0px; width: 83%;">
                <span class="block">Apakah pasien memegang pinggiran kursi atau meja atau benda lain sebagai penopang saat akan duduk?</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: center; padding-left: 4px; padding-right: 0px; width: 85%; background-color: #d3d3d3; border-top: 1px solid black;">
                <span class="block tebal">SKRINING NYERI:</span>
            </td>
        </tr>
    </table>
    <table class="standard" style="border: 1px solid black; width:100%; border-collapse: collapse; position:relative; border-bottom: none; border-top: none;">
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Skala nyeri: {{ isset($data['sklanyeri']) ? $data['sklanyeri'] : '' }} &nbsp; Lokasi: {{ isset($data['lokasiNyeri']) ? $data['lokasiNyeri'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Durasi: {{ isset($data['sklandurasiNyeriyeri']) ? $data['durasiNyeri'] : '' }} &nbsp; Frekuensi: {{ isset($data['frekuensiNyeri']) ? $data['frekuensiNyeri'] : '' }}</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Nyeri hilang, bila:</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
            <td style="text-align: left; padding-left: 104px; padding-right: 0px; width: 85%;">
                <span class="block">Diberitahukan ke dokter:</span>
            </td>
        </tr>
        <tr>
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-top: none; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
            <td style="text-align: left; padding-left: 4px; width: 15%; border-left: 1px solid black; border-right: 1px solid black; border-bottom: none;" width="25%">
            </td>
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
    

{{-- {{ dd($data) }} --}}

</body>

</html>
