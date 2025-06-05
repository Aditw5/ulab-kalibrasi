<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Operasi</title>

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
                <td><span class="styled-pre">RM D.11.7.L (1/2) Rev 2022</span></td>
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
                Laporan Operasi
            </td>
        </tr>
    </table>

    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; ">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Operator :</span>
                <span>{{ isset($data['namaOperator']['label']) ? $data['namaOperator']['label'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; ">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Asisten Operator :</span>
                <span>{{ isset($data['namaAsistenOperator1']['label']) ? $data['namaAsistenOperator1']['label'] : '' }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Asisten Operator :</span>
                <span>{{ isset($data['namaAsistenOperator2']['label']) ? $data['namaAsistenOperator2']['label'] : '' }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Asisten Operator :</span>
                <span>{{ isset($data['namaAsistenOperator3']['label']) ? $data['namaAsistenOperator3']['label'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; ">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Ahli Anestesi :</span>
                <span>{{ isset($data['namaAhliAnastesi']['label']) ? $data['namaAhliAnastesi']['label'] : '' }}</span>
            </td>
        </tr>
    </table>
    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; ">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Asisten Ahli Anastesi :</span>
                <span>{{ isset($data['namaAsistenAnastesi1']['label']) ? $data['namaAsistenAnastesi1']['label'] : '' }}</span>
            </td>
            {{-- <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Asisten Ahli Anastesi :</span>
                <span>{{ isset($data['namaAsistenAnastesi1']['label']) ? $data['namaAsistenAnastesi1']['label'] : '' }}</span>
            </td>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="25%">
                <span class="block">Nama Asisten Ahli Anastesi :</span>
                <span>{{ isset($data['namaAsistenAnastesi1']['label']) ? $data['namaAsistenAnastesi1']['label'] : '' }}</span>
            </td> --}}
        </tr>
    </table>

    <table width="100%" class="header">
        <tr>
            <td width="30%" class="border1" class="medium">Jenis Anestesi</td>
            <td class="border1" colspan="2" class="medium">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisAnastesiUmum']) && $data['JenisAnastesiUmum'] ? 'checked' : '' }} />
                            <span class="normal medium" style="position: relative;top:-7px">Umum</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisAnastesiSpiral']) && $data['JenisAnastesiSpiral'] ? 'checked' : '' }} />
                            <span class="normal medium" style="position: relative;top:-7px">Spinalm</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisAnastesiEpidurai']) && $data['JenisAnastesiEpidurai'] ? 'checked' : '' }} />
                            <span class="normal medium" style="position: relative;top:-7px">Epidurial</span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisAnastesiBSP']) && $data['JenisAnastesiBSP'] ? 'checked' : '' }} />
                            <span class="normal medium" style="position: relative;top:-7px">BSP*</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisAnastesiCSE']) && $data['JenisAnastesiCSE'] ? 'checked' : '' }} />
                            <span class="normal medium" style="position: relative;top:-7px">CSE**</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisAnastesiLokal']) && $data['JenisAnastesiLokal'] ? 'checked' : '' }} />
                            <span class="normal medium" style="position: relative;top:-7px">Lokal</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Jenis Operasi</td>
            <td class="border1" colspan="2" class="medium">
                <table>
                    <tr>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisOperasiKecil']) && $data['JenisOperasiKecil'] ? 'checked' : '' }} />
                            <span class="" style="position: relative;top:-7px">Kecil</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisOperasiSedang']) && $data['JenisOperasiSedang'] ? 'checked' : '' }} />
                            <span class="" style="position: relative;top:-7px">Sedang I/II/III</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisOperasiBesar']) && $data['JenisOperasiBesar'] ? 'checked' : '' }} />
                            <span class="" style="position: relative;top:-7px">Besar I/II/III</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['JenisOperasiKhusus']) && $data['JenisOperasiKhusus'] ? 'checked' : '' }} />
                            <span class="" style="position: relative;top:-7px">Khusus</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Diagnosis PraBedah***</td>
            <td class="border1" colspan="2" class="medium">
                {{ isset($data['diagnosaPraBedah']['label']) ?
                $data['diagnosaPraBedah']['label'] : isset($data['diagnosaPraBedah']) ? $data['diagnosaPraBedah'] : '' }}</td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Diagnosis PascaBedah</td>
            <td class="border1" colspan="2" class="medium">
                {{ isset($data['diagnosaPascaBedah']['label']) ?
                $data['diagnosaPascaBedah']['label'] : isset($data['diagnosaPascaBedah']) ? $data['diagnosaPascaBedah'] : '' }}</td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Indikasi Operasi</td>
            <td class="border1" colspan="2" class="medium">
                {{ isset($data['indikasiOperasi']) ? $data['indikasiOperasi'] : '' }}</td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Nama Operasi</td>
            <td class="border1" colspan="2" class="medium">
                {{ isset($data['namaOperasi']) ? $data['namaOperasi'] : '' }}</td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Jaringan yang dieksekusi</td>
            <td width="30%" class="border1" class="medium">
                {{ isset($data['jaringanYangDieksisi']) ? $data['jaringanYangDieksisi'] : '' }}</td>
            <td class="border1">
                <table>
                    <tr>
                        <td>Pemeriksaan PA</td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['pemeriksaanPAYa']) && $data['pemeriksaanPAYa'] ? 'checked' : '' }} />
                            <span class="" style="position: relative;top:-7px">Ya</span>
                        </td>
                        <td>
                            <input type="checkbox"
                                {{ isset($data['pemeriksaanPATidak']) && $data['pemeriksaanPATidak'] ? 'checked' : '' }} />
                            <span class="" style="position: relative;top:-7px">Tidak</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table class="header">
        <tr>
            <td class="border1" width="30%">Implan</td>
            <td class="border1">NO : {{ isset($data['implanNo']) ? $data['implanNo'] : '' }}</td>
            <td class="border1">Nama : {{ isset($data['implanNama']) ? $data['implanNama'] : '' }}</td>
            <td class="border1">Jenis : {{ isset($data['implanJenis']) ? $data['implanJenis'] : '' }}</td>
        </tr>
    </table>
    <table class="header" style="text-align: center">
        <tr>
            <td class="border1">Tanggal Operasi</td>
            {{-- <td class="border1">Jam Operasi</td> --}}
            <td class="border1">Mulai Jam</td>
            <td class="border1">Operasi Selesai</td>
            <td class="border1">Durasi Operasi</td>
        </tr>
        <tr>
            <td class="border1">
                {{ \Carbon\Carbon::parse($data['tglOperasi'])->setTimeZone('Asia/Jakarta')->format('d-m-Y') }}</td>
            {{-- <td class="border1">
                {{ \Carbon\Carbon::parse($data['jamOperasi'])->setTimeZone('Asia/Jakarta')->format('H:i') }}</td> --}}
            <td class="border1">
                {{ \Carbon\Carbon::parse($data['mulaiJamOperasi'])->setTimeZone('Asia/Jakarta')->format('H:i') }}</td>
            <td class="border1">
                {{ \Carbon\Carbon::parse($data['jamOperasiSelesai'])->setTimeZone('Asia/Jakarta')->format('H:i') }}
            </td>
            <td class="border1">{{ isset($data['durasiOpperasi']) ? $data['durasiOpperasi'] : '' }}</td>
        </tr>
    </table>
    <table width="100%" class="header">
        <tr>
            <td width="30%" class="border1" class="medium">Komplikasi Operasi</td>
            <td class="border1" colspan="2" class="medium">
                {{ isset($data['komplikasiOperasi']) ? $data['komplikasiOperasi'] : '' }}</td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Jumlah Perdaharan <br>
                {{ isset($data['jumlahPerdarahan']) ? $data['jumlahPerdarahan'] : '' }}</td>
            <td class="border1" width="30%" class="medium">Darah yang hilang : <br>
                {{ isset($data['darahYangHilang']) ? $data['darahYangHilang'] : '' }}</td>
            <td class="border1" class="medium">Darah yang masuk lewat transfusi : <br>
                {{ isset($data['darahYangMasukLewatTransfusi']) ? $data['darahYangMasukLewatTransfusi'] : '' }}</td>
        </tr>
        <tr>
            <td width="30%" class="border1" class="medium">Perawatan pasca operasi</td>
            <td width="30%">
                <input type="checkbox"
                    {{ isset($data['perawatanPascaOperasiRuangan']) && $data['perawatanPascaOperasiRuangan'] ? 'checked' : '' }} />
                <span class="" style="position: relative;top:-7px">Ruangan</span> <br>
                <span>
                    {{ isset($data['registrasi']['namaruangan']) ? $data['registrasi']['namaruangan'] : '' }}
                </span>
            </td>
            <td>
                <input type="checkbox"
                    {{ isset($data['perawatanPascaOperasiHCU']) && $data['perawatanPascaOperasiHCU'] ? 'checked' : '' }} />
                <span class="" style="position: relative;top:-7px">HCU</span> <br>
            </td>
        </tr>
    </table>

    <table class="header">
        <tr>
            <td>Laporan Operasi***</td>
        </tr>
        <tr>
            <td>
                {!! isset($data['laporanOperasi']) ? nl2br($data['laporanOperasi']) : '' !!}
            </td>
        </tr>
    </table>
    <table class="header" style="page-break-before: always;">
        <tr>
            <td style="padding: 4px">Laporan Post Operasi :</td>
        </tr>
        <tr>
            <td style="padding: 4px">
                S: <br>{!! isset($data['S']) ? nl2br($data['S']) : '' !!} <br><br><br>
                O: <br>{!! isset($data['O']) ? nl2br($data['O']) : '' !!} <br><br><br>
                A: <br>{!! isset($data['A']) ? nl2br($data['A']) : '' !!} <br><br><br>
                P: <br>{!! isset($data['P']) ? nl2br($data['P']) : '' !!} <br><br><br>
            </td>
        </tr>
        <tr>
            <td style="text-align: center">
                Cirebon,
                {{ \Carbon\Carbon::parse($data['tglPembuatan'])->setTimeZone('Asia/Jakarta')->locale('id')->translatedFormat('d F Y H:i') }}
            </td>
        </tr>
        <tr>
            <td style="padding: 4px">
                <div>Dokter Penanggung Jawab Pasien</div><br>
                <div style="text-align: center; width: 30%"><img src="data:image/png;base64, {!! $tte !!}">
                </div><br>
                <span class="tebal garis-bawah block">{{ $identitas['dpjp'] }}</span>
                <span class="block">No. SIP {{ $identitas['nosip'] }}</span>
            </td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 30px">
        <tr>
            <td>Keterangan</td>
            <td>:</td>
            <td>*BSP (Blok Saraf Perifer)</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>**CSE (Combine Spinal Epidurial)</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td>***dapat dilanjutkan dibelakang</td>
        </tr>
    </table>

</body>

</html>
