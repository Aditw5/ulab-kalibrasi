<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Perencanaan Pasien Pulang</title>

    <style>
        @page {
            padding: 0;
            margin-top: 5rem;
            margin-bottom: 0;
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
    $kodeDepartemen = $data['registrasi']['objectdepartemenfk'] ?? 16;
@endphp

<body>

    {{-- <table class="salinan">
        <thead>
            <tr>
                <td><span class="tebal">SALINAN</span></td>
            </tr>
        </thead>
    </table> --}}
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
                                                class="normal">Pemerintah Kota Cirebon</small>
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
                                    class="medium tebal">{{ \Carbon\Carbon::parse($identitas['tgllahir'])->translatedFormat('d-m-Y') }}</span>
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
                        style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;font-size:15pt;text-transform:uppercase;position:relative;top:-4px">PERENCANAAN PASIEN PULANG</span>
                </th>
            </tr>
        </thead>
    </table>

    <table style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top:-46px">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px" width="50%">
                <table>
                    <tr>
                        <td>Tanggal Masuk RS</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($registrasi['tglregistrasi'])->format('d-m-Y H:i') }}</td>
                    </tr>
                    <tr>
                        <td>Ruangan</td>
                        <td>:</td>
                        <td>{{ $registrasi['namaruangan'] }}</td>
                    </tr>
                </table>
            </td>
            <td style="border: 1px solid black;text-align: left;padding-left:4px">
                <table>
                    <tr>
                        <td>Tanggal Keluar RS</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($registrasi['tglpulang'])->format('d-m-Y H:i') }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">Keadan saat pulang dari Rumah Sakit</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['keaadaanSaatPulangSembuh']) && $data['keaadaanSaatPulangSembuh'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Sembuh</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['keaadaanSaatPulangRsPindahRs']) && $data['keaadaanSaatPulangRsPindahRs']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pindah RS Lain</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['keaadaanSaatPulangRsRujukanRsLain']) && $data['keaadaanSaatPulangRsRujukanRsLain']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Rujukan RS Lain</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['keaadaanSaatPulangMeneruskanberobatJalan']) && $data['keaadaanSaatPulangMeneruskanberobatJalan']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Meneruksan dengan berobat jalan</span>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox"
                    {{ isset($data['keaadaanSaatPulangAtasPermintaanSendiri']) && $data['keaadaanSaatPulangAtasPermintaanSendiri']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Atas Permintaan Sendiri</span><br>
                    <span class="normal medium" style="position: relative;top:-7px">Alasan Atas Permintaan Sendiri</span><br>
                    <span class="normal medium" style="position: relative;top:-7px">{{$data['keaadaanSaatPulangAtasPermintaanSendiri'] ?? ''}}</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['keaadaanSaatPulangMeninggal']) && $data['keaadaanSaatPulangMeninggal']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Meninggal</span>
                </div>
            </td>
        </tr>
    </table>

    <table style="width:100%; border: 1px solid black; border-collapse: collapse; position:relative; top:-46px">
        <tr>
            <td>
                A. Kontrol
            </td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>
                Waktu
            </td>
            <td>:</td>
            <td>{{ isset($data['kontrolWaktu']) ? \Carbon\Carbon::parse($data['kontrolWaktu'])->setTimeZone('Asia/Jakarta')->translatedFormat('d F Y') : ''}}</td>
        </tr>
        <tr>
            <td>
                Tempat
            </td>
            <td>:</td>
            <td>{{strtoupper($data['kontrolTempat']) ?? ''}}</td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">B. Lanjutan Perawatan di Rumah</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPerawatanLukaOperasi']) && $data['lanjutanPerawatanRumahPerawatanLukaOperasi'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Perawatan luka operasi</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPerawatanDiri']) && $data['lanjutanPerawatanRumahPerawatanDiri']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Perawatan diri/hifiene perseorangan</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPencegahanDecubitus']) && $data['lanjutanPerawatanRumahPencegahanDecubitus']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pencegahan Decubitus</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahLatihanPergerakanSendi']) && $data['lanjutanPerawatanRumahLatihanPergerakanSendi']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Latihan pergerakan sendir (ROM)</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahLatihanPerawatanKateter']) && $data['lanjutanPerawatanRumahLatihanPerawatanKateter']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Perawatan Kateter</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahRendamDuduk']) && $data['lanjutanPerawatanRumahRendamDuduk']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Redam Duduk</span>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPemberianMakanNGT']) && $data['lanjutanPerawatanRumahPemberianMakanNGT']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pemberian makan melalui NGT</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPenyuntikanInsulin']) && $data['lanjutanPerawatanRumahPenyuntikanInsulin']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Penyuntikan Insulin</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPerawatanPayudara']) && $data['lanjutanPerawatanRumahPerawatanPayudara']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Perawatan Payudara</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahVulva']) && $data['lanjutanPerawatanRumahVulva']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Vulva Hygiene</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahPerawatanTaliPusat']) && $data['lanjutanPerawatanRumahPerawatanTaliPusat']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Perawatan tali pusat</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['lanjutanPerawatanRumahImunisasiLanjutan']) && $data['lanjutanPerawatanRumahImunisasiLanjutan']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Imunisasi lanjutan</span>
                </div>
            </td>
        </tr>
    </table>

    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">C. Pengaturan Diet Nutrisi</td>
        </tr>
        <tr>
            <td width="50%">
                <table>
                    <tr>
                        <td>Pendidikan Gizi</td>
                        <td>:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox"
                        {{ isset($data['pendidikanGiziYa']) && $data['pendidikanGiziYa'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Ya, Jenis Diet</span>
                        </td>
                        <td>:</td>
                        <td>{{ $data['pendidikanGiziYaJenisDiet'] ?? ''}}</td>
                    </tr>
                    <tr>
                        <td>
                            <input type="checkbox"
                        {{ isset($data['pendidikanGiziTidak']) && $data['pendidikanGiziTidak'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Tidak, Alasan</span>
                        </td>
                        <td>:</td>
                        <td>{{ $data['pendidikanGiziTidakAlasan'] ?? ''}}</td>
                    </tr>
                </table>
            </td>
            <td>
                <div>
                    {{-- <input type="checkbox"
                    {{ isset($data['keaadaanSaatPulangAtasPermintaanSendiri']) && $data['keaadaanSaatPulangAtasPermintaanSendiri']? 'checked' : '' }} /> --}}
                    <span class="normal medium" style="position: relative;top:-7px">leaflet : </span>
                    <span class="normal medium" style="position: relative;top:-7px">
                        <span style="text-decoration: {{isset($data['leafletTidak']) && $data['leafletTidak'] ? 'line-through' : 'none'}}">ada</span>/<span style="text-decoration: {{isset($data['leafletAda']) && $data['leafletAda'] ? 'line-through' : 'unset'}}">tidak</span>
                    </span><br>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">D. Pemakaian alat bantu untuk pemenuhan nutris peroral/eliminia : NGT / Karakter </td>
        </tr>
        <tr>
            <td width="50%">
                <div>
                    <input type="checkbox"
                        {{ isset($data['tanggaPemasanganAlatBantu']) && $data['tanggaPemasanganAlatBantu']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Tgl. Pemasangan</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['tanggaGantiAlat']) && $data['tanggaGantiAlat']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Tgl. Ganti Alat</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['noNgtNoKateter']) && $data['noNgtNoKateter']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">No. NGT/No.Kateter</span>
                </div>
            </td>
            <td>
                <div>
                    <span class="normal medium" style="position: relative;top:-7px">Perawatan NGT / Karakter : {{$data['perawatanNgtKateter'] ?? ''}}</span>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td>E. Obat-obatan yang masih diminum jumlah dan yang harus diperhatikan selama minum obat (sebutkan) jenis obat dosis dna cara pemberiannya</td>
        </tr>
        <tr>
            <td>
                {{$data['obatObatanYangMasihDiminum'] ?? ''}}
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">F. Aktifitas dan istirahat : <br>Alat bantu yang digunakan pasien untuk perawatan di rumah</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahWalkerTreepod']) && $data['alatBantuPasienDirumahWalkerTreepod'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Walker / Treepod</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahCruch']) && $data['alatBantuPasienDirumahCruch']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Cruch</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahKursiRoda']) && $data['alatBantuPasienDirumahKursiRoda']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Kursi Roda</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahTempatTidur']) && $data['alatBantuPasienDirumahTempatTidur']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Tempat tidur khusus</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahBruce']) && $data['alatBantuPasienDirumahBruce']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Bruce</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahNackCollar']) && $data['alatBantuPasienDirumahNackCollar']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Neck collar</span>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahOksigen']) && $data['alatBantuPasienDirumahOksigen']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Oksigen</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahNebulizer']) && $data['alatBantuPasienDirumahNebulizer']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Nebulizer</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahAlatPemantau']) && $data['alatBantuPasienDirumahAlatPemantau']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Alat Pemantau</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['alatBantuPasienDirumahAlatPenghisap']) && $data['alatBantuPasienDirumahAlatPenghisap']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Alat penghisap lender/slym suction</span>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">G. Pelayanan kesehatan yang digunakan selama perawatan</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanPelayananDirumah']) && $data['pelayananSelamaPerawatanPelayananDirumah'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pelayanan kesehatan di rumah</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanPuskesmas']) && $data['pelayananSelamaPerawatanPuskesmas']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Puskesmas</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanPraktikMandiri']) && $data['pelayananSelamaPerawatanPraktikMandiri']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Praktik mandiri perawatan luka</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanDokterKeluarga']) && $data['pelayananSelamaPerawatanDokterKeluarga']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Dokter Keluarga</span>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanFisioterapi']) && $data['pelayananSelamaPerawatanFisioterapi']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Fisioterapi</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanSpeechTerpai']) && $data['pelayananSelamaPerawatanSpeechTerpai']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Speech Terapi</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pelayananSelamaPerawatanPekerjaSosial']) && $data['pelayananSelamaPerawatanPekerjaSosial']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pekerja sosial</span>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">H. Orang yang membantu pasien saat perawtan di rumah</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahSuamiIstri']) && $data['orangMembantuPerawatanDirumahSuamiIstri'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Suami / Istri</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahAnak']) && $data['orangMembantuPerawatanDirumahAnak']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Anak</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahOrangTua']) && $data['orangMembantuPerawatanDirumahOrangTua']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Orang tua</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahKakakAdik']) && $data['orangMembantuPerawatanDirumahKakakAdik']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Kakak / Adik</span>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahPerawat']) && $data['orangMembantuPerawatanDirumahPerawat']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Perawat</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahPos']) && $data['orangMembantuPerawatanDirumahPos']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">POS</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahSaudara']) && $data['orangMembantuPerawatanDirumahSaudara']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Saudara</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['orangMembantuPerawatanDirumahLain']) && $data['orangMembantuPerawatanDirumahLain']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Lain-lain (Pembantu) {{$data['orangMembantuPerawatanDirumahLainKet'] ?? ''}}</span>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">I. Hasil pemeriksaan diagnostik yang disertakan saat pulang</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangLaboratorium']) && $data['pemeriksaanDiagnostikSaatPulangLaboratorium'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Laboratorium</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangPaAnatomi']) && $data['pemeriksaanDiagnostikSaatPulangPaAnatomi']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Patalogi Anatomi</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangEkg']) && $data['pemeriksaanDiagnostikSaatPulangEkg']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">EKG</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangFotoRontgen']) && $data['pemeriksaanDiagnostikSaatPulangFotoRontgen']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Foto Rontgen</span>
                </div>
            </td>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangUsg']) && $data['pemeriksaanDiagnostikSaatPulangUsg']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pemeriksaan USG</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangEcho']) && $data['pemeriksaanDiagnostikSaatPulangEcho']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pemeriksaan ECHO</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangMri']) && $data['pemeriksaanDiagnostikSaatPulangMri']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Pemeriksaan MRI</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['pemeriksaanDiagnostikSaatPulangLain']) && $data['pemeriksaanDiagnostikSaatPulangLain']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Lain-lain {{$data['pemeriksaanDiagnostikSaatPulangLainKet'] ?? ''}}</span>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td>J. Catatan Khusus Perawat</td>
        </tr>
        <tr>
            <td>
                <div>
                    <input type="checkbox"
                        {{ isset($data['catatanKhusuPerawatCekGulaDarah']) && $data['catatanKhusuPerawatCekGulaDarah'] ? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Cek gula darah sehari sebelum kontrol</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['catatanKhusuPerawatFotoRontgenEkgLaboratHarusDibawa']) && $data['catatanKhusuPerawatFotoRontgenEkgLaboratHarusDibawa']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Foto Rontgen, EKG, hasil pemeriksaan laboratorium harus dibawa pada saat kontrol</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['catatanKhusuPerawatBilaAdaKeluhanSegeraKontrol']) && $data['catatanKhusuPerawatBilaAdaKeluhanSegeraKontrol']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Bila ada keluhan segera kontrol ke rumah sakit atau ke tempat pelayanan kesehatan terdeakat</span>
                </div>
                <div>
                    <input type="checkbox"
                        {{ isset($data['catatanKhusuPerawatLain']) && $data['catatanKhusuPerawatLain']? 'checked' : '' }} />
                    <span class="normal medium" style="position: relative;top:-7px">Lain-lain {{$data['catatanKhusuPerawatLainKet'] ?? ''}}</span>
                </div>
            </td>
        </tr>
    </table>
    <table width="100%" style="border: 1px solid black;width:100%;border-collapse: collapse; position:relative; top: -46px;" class="table-isi">
        <tr>
            <td colspan="2">Cirebon, {{\Carbon\Carbon::parse($data['tglPerawatRuangan'])->translatedFormat('d F Y') ?? ''}}</td>
        </tr>
        {{-- {{dd($data)}} --}}
        <tr style="text-align: center">
            <td wid>
                <div>Perawat Ruangan</div>
                <div style="margin: 10px 0">
                    {{-- {{dd($data)}} --}}
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{ $data['petugasPerawatTerima']['label'] ?? $data['petugasPerawatTerima'] }}">
                    {{-- <img src="data:image/png;base64, {{ base64_encode(QrCode::format('svg')->size(75)->generate($data['petugasPerawatTerima']['label'])) }}"> --}}
                </div>
                <div>({{$data['petugasPerawatTerima']['label'] ?? $data['petugasPerawatTerima']}})</div>
            </td>
            <td>
                <div>Pasien / Keluarga</div>
                <div style="margin: 10px 0; height: 75px;">
                    {{-- <img src="data:image/png;base64, {{ base64_encode(QrCode::format('svg')->size(75)->generate($data['petugasPerawatTerima']['label'])) }}"> --}}
                </div>
                <div>({{$data['namapasien'] ?? ''}})</div>
            </td>
        </tr>
    </table>
    {{-- {{dd($data)}} --}}

    {{-- <table style="width:100%;border-collapse: collapse; position:relative; top:-46px">
        <tr>
            <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none" width="40%">
                <span class="normal" style="font-size:10pt">Penangung Pembayaran :</span>
                <span class="normal"
                    style="font-size:10.5pt;margin-left:10px">{{ $registrasi['kelompokpasien'] }}</span>
            </td>
            @if ($kodeDepartemen == 16)
                <td style="border: 1px solid black;text-align: left;padding-left:4px;border-top:none">
                    <span class="normal" style="font-size:10pt">Diagnosis/Masalah waktu Masuk :</span>
                    <span class="normal" style="font-size:10.5pt;margin-left:10px"></span>
                </td>
            @else
                <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none">
                    <span class="normal" style="font-size:10pt">Asal Rujukan :</span>
                    <span class="normal"
                        style="font-size:10.5pt;margin-left:15px">{{ $registrasi['asalrujukan'] }}</span>
                </td>
            @endif
        </tr>
    </table>

    @if ($kodeDepartemen == 16)
        <table style="width:100%;border-collapse: collapse; position:relative; top:-46px">
            <tr>
                <td style="border: 1px solid black;text-align: left; padding-left:4px;border-top:none">
                    <span class="normal" style="font-size:10pt">Asal Rujukan :</span>
                    <span class="normal"
                        style="font-size:10.5pt;margin-left:15px">{{ $registrasi['asalrujukan'] }}</span>
                </td>
            </tr>
        </table>
    @endif

    <div style="display: block;position:relative;">
        <hr>
        <table width="100%">
            <tr>
                <td class="label-top" width="25%">
                    <span
                        class="normal medium block">{{ $kodeDepartemen == 16 ? 'Ringkasan Riwayat Penyakit' : 'Anamnesa' }}</span>
                </td>
                <td width="2%" class="label-top"><span class="normal medium block">:</span></td>
                <td class="label-top">
                    <span
                        class="normal medium">{{ isset($data['riwayatPenyakit']) ? $data['riwayatPenyakit'] : '' }}</span>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium">Pemeriksaan Fisik</span>
                </td>
                <td width="2%" class="label-top">
                    <span class="normal medium">:</span>
                </td>

                <td>
                    <table width="100%">
                        <tr>
                            <td width="5%">
                                <span class="normal medium block">TD</span>
                            </td>
                            <td width="20%">
                                <span
                                    class="normal medium block">{{ isset($data['tekananDarah']) ? $data['tekananDarah'] : '' }}
                                    mmhg</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium block">Nadi</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium block">{{ isset($data['nadi']) ? $data['nadi'] : '' }}
                                    x/mnt</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium block">Pernafasan</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span
                                    class="normal medium block">{{ isset($data['pernapasan']) ? $data['pernapasan'] : '' }}
                                    x/mnt</span>
                            </td>
                            <td width="5%" style="text-align: right">
                                <span class="normal medium">Suhu</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium">{{ isset($data['suhu']) ? $data['suhu'] : '' }}C</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="5%">
                                <span class="normal medium">SpO2</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium">{{ isset($data['SPO2']) ? $data['SPO2'] : '' }}%</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium">TB</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span
                                    class="normal medium">{{ isset($data['tinggiBadan']) ? $data['tinggiBadan'] : '' }}
                                    cm</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium">BB</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium"
                                    style="text-align: left">{{ isset($data['beratBadan']) ? $data['beratBadan'] : '' }}
                                    kg</span>
                            </td>
                            <td width="5%">
                                <span class="normal medium">IMT</span>
                            </td>
                            <td width="20%" style="text-align: left">
                                <span class="normal medium">{{ isset($data['IMT']) ? $data['IMT'] : '' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">Pemeriksaan Fisik Lainnya</span>
                </td>
                <td width="2%" class="label-top"><span class="normal medium ">:</span></td>
                <td>
                    <span
                        class="normal medium block">{{ isset($data['pemeriksaanFisikLain']) ? $data['pemeriksaanFisikLain'] : '' }}</span>
                </td>
            </tr>
        </table>
        <hr>
        <table width="27%">
            <tr>
                <td width="98%" class="label-top">
                    <span class="normal medium block">Pemeriksaan Penunjang /</span>
                    <span class="normal medium block">Diagnostik Terpenting</span>
                </td>
                <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                <td style="display: inline !important;white-space: normal;"></td>
            </tr>
        </table>
        <pre class="styled-pre label-top"
            style="position: relative;top:-2.1rem;left:12rem;padding-right:11.9rem !important;white-space: pre-wrap;">{{ isset($data['pemeriksaPenunjang']) ? $data['pemeriksaPenunjang'] : '' }}</pre>
        <hr>
        @if ($kodeDepartemen == 16)
            <table width="27%">
                <tr>
                    <td width="98%" class="label-top">
                        <span class="normal medium block">Terapi pengobatan selama di rumah sakit</span>
                    </td>
                    <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                    <td class="label-top">
                        <span class="normal medium block">{{ isset($data['terapi']) ? $data['terapi'] : '' }}</span>
                    </td>
                </tr>
            </table>
            <span class="normal medium block"
                style="position: relative;top:-2.2rem;left:12rem;padding-right:11.9rem;text-align:justify">{{ isset($data['terapi']) ? $data['terapi'] : '' }}</span>
            <hr>
            <table width="100%">
                <tr>
                    <td width="25%" class="">
                        <span class="normal medium block">Hasil Konsultasi</span>
                    </td>
                    <td width="2%"><span class="normal medium">:</span></td>
                    <td>
                        <span
                            class="normal medium block">{{ isset($data['hasilKonsultasi']) ? $data['hasilKonsultasi'] : '' }}</span>
                    </td>
                </tr>
            </table>
            <hr>
        @endif
        @if (isset($data['totalSkor']) && $data['totalSkor'] > 0)
            <table width="100%">
                <span class="normal medium block">Malnutrition Screening Tools (MST)</span>
                <thead>
                    <tr>
                        <th width="50%" class="">
                            <span class="normal medium block">Pertanyaan</span>
                        </th>
                        <th width="5%">
                            <span class="normal medium block">Keterangan</span>
                        </th>
                        <th width="45%">
                            <span class="normal medium block">Total Skor :
                                {{ isset($data['totalSkor']) ? $data['totalSkor'] : '' }}</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td width="60%" class="">
                            <span class="normal medium block">1. Apakah pasien mengalami penurunan berat badan yang
                                tidak direncanakan ?</span>
                        </td>
                        <td style="text-align: center;" width="38%"><span class="normal medium">
                                @if (isset($data['tidakAdaTurunBeratBadan']) ? $data['tidakAdaTurunBeratBadan'] : '' === 2)
                                    b. Tidak yakin / tidak tahu/ ya
                                @elseif(isset($data['tidakAdaTurunBeratBadan']) ? $data['tidakAdaTurunBeratBadan'] : '' === 0)
                                    a. Tidak
                                @endif
                            </span></td>
                        <td width="2%" style="text-align: center;"><span
                                class="normal medium">{{ isset($data['tidakAdaTurunBeratBadan']) ? $data['tidakAdaTurunBeratBadan'] : '' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="60%" class="">
                            <span class="normal medium block">2. Bila ya, berapa kilogram penurunan berat badan
                                tersebut</span>
                        </td>
                        <td style="text-align: center;" width="38%"><span class="normal medium">
                                @if (isset($data['turunBeratBadan']) ? $data['turunBeratBadan'] : '' === 1)
                                    1 - 5 kg
                                @elseif(isset($data['turunBeratBadan']) ? $data['turunBeratBadan'] : '' === 2)
                                    6 - 10 kg
                                @elseif(isset($data['turunBeratBadan']) ? $data['turunBeratBadan'] : '' === 3)
                                    11 - 15 kg
                                @elseif(isset($data['turunBeratBadan']) ? $data['turunBeratBadan'] : '' === 4)
                                    > 15 kg
                                @elseif(isset($data['turunBeratBadan']) ? $data['turunBeratBadan'] : '' === 5)
                                    Tidak pasti/tidak tahu
                                @endif
                            </span></td>
                        <td width="2%" style="text-align: center;"><span
                                class="normal medium">{{ isset($data['turunBeratBadan']) ? $data['turunBeratBadan'] : '' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="60%" class="">
                            <span class="normal medium block">3. Penurunan nafsu makan / asupan makan ?</span>
                        </td>
                        <td style="text-align: center;" width="38%"><span class="normal medium">
                                @if (isset($data['asupanMakan']) ? $data['asupanMakan'] : '' === 1)
                                    Ya
                                @elseif(isset($data['asupanMakan']) ? $data['asupanMakan'] : '' === 0)
                                    Tidak
                                @endif
                            </span></td>
                        <td width="2%" style="text-align: center;"><span
                                class="normal medium">{{ isset($data['asupanMakan']) ? $data['asupanMakan'] : '' }}</span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
        @endif
        <table style="width:100%">
            <tr>
                <td width="53%" class="label-top">
                    <table width="100%" class="label-top">
                        <tr>
                            <td width="25%" class="label-top">
                                <span class="normal medium block">Diagnosa Utama</span>
                            </td>
                            <td class="label-top" width="70%">
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
                            <td class="label-top">
                                <span class="normal medium block">Diagnosa Sekunder</span>
                            </td>
                            <td class="label-top">
                                @foreach ($diagnosaSekunder as $item)
                                    <span class="normal medium block">{{ $loop->iteration }}.
                                        {{ $item[1] }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="16%" class="label-top">
                    <table width="100%">
                        <tr>
                            <td width="45%" class="label-top">
                                <span class="normal medium block">ICD 10</span>
                            </td>
                            <td class="label-top">
                                @foreach ($diagnosaUtama as $item)
                                    <span class="normal medium block">{{ $item[0] }}</span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td class="label-top"></td>
                            <td class="label-top">
                                @foreach ($diagnosaSekunder as $item)
                                    <span class="normal medium block">{{ $loop->iteration }}.
                                        {{ $item[0] }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="23%" class="label-top">
                    <table width="100%">
                        <tr>
                            <td width="35%" class="label-top">
                                <span class="normal medium block">Diagnosa Dokter</span>
                            </td>
                            <td width="65%" class="label-top">
                                @foreach ($primaryDiagnosa as $item)
                                    <span class="normal medium block">{{ $item }}</span>
                                @endforeach
                                @foreach ($secondaryDiagnosa as $item)
                                    <span class="normal medium block">{{ $item }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table style="width:100%">
            <tr>
                <td width="53%" class="label-top">
                    <table width="100%" class="label-top">
                        <tr>
                            <td width="25%" class="label-top">
                                <span class="normal medium block">Tindakan / Prosedur</span>
                            </td>
                            <td class="label-top" width="70%">
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
                    </table>
                </td>
                <td width="16%" class="label-top">
                    <table width="100%">
                        <tr>
                            <td width="45%" class="label-top">
                                <span class="normal medium block">ICD 9</span>
                            </td>
                            <td class="label-top">
                                @foreach ($tindakan as $item)
                                    <span class="normal medium block">{{ $loop->iteration }}.
                                        {{ $item[0] }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="23%" class="label-top">
                    <table width="100%">
                        <tr>
                            <td width="35%" class="label-top">
                                <span class="normal medium block label-top">Tindakan Dokter</span>
                            </td>
                            <td width="65%" class="label-top">
                                @foreach ($tindakanDokter as $item)
                                    <span class="normal medium block">{{ $item }}</span>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <table width="50%" style="border: 1px solid black">
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        <table width="100%" style="border: 1px solid black">
            <tr>
                <td width="50%" class="label-top">
                    <table width="100%" style="border: 1px solid black">
                        <tr>
                            <td style="border: 1px solid black" class="label-top">
                                <span class="normal medium block">Diagnosis Utama</span>
                            </td>
                            <td style="border: 1px solid black">
                                <span class="normal medium block">Lorem, ipsum dolor sit amet consectetur adipisicing elit.</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="100%" style="border: 1px solid black">
                        <tr>
                            <td style="border: 1px solid black">
                                <span class="normal medium block">ICD 10</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="20%">
                    <table width="100%" style="border: 1px solid black">
                        <tr>
                            <td style="border: 1px solid black;" class="label-top">
                                <span class="normal medium block">Diagnosa Dokter</span>
                            </td>
                            <td style="border: 1px solid black" width="10%">
                                <span class="normal medium block">Lorem ipsum dolor sit amet consectetur adipisicing elit. Enim, repudiandae.</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="25%" class="">
                    <span class="normal medium block">Alergi (reaksi Obat)</span>
                    <span class="normal medium block">Diagnostik Terpenting</span>
                </td>
                <td width="2%"><span class="normal medium">:</span></td>
                <td>
                    <span class="normal medium block">-</span>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="25%" class="">
                    <span class="normal medium block">Hasil Laboratorium Belum selesai (Pending)</span>
                    <span class="normal medium block">Diagnostik Terpenting</span>
                </td>
                <td width="2%"><span class="normal medium">:</span></td>
                <td>
                    <span
                        class="normal medium block">{{ isset($data['hasilPenunjang']) ? $data['hasilPenunjang'] : '' }}</span>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="25%" class="">
                    <span class="normal medium block">Diet</span>
                    <span class="normal medium block">Diagnostik Terpenting</span>
                </td>
                <td width="2%"><span class="normal medium">:</span></td>
                <td>
                    <span class="normal medium block">{{ isset($data['diet']) ? $data['diet'] : '' }}</span>
                </td>
            </tr>
        </table>
        <hr>
        <table width="100%">
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">Interuksi/Anjuran dan Edukasi (Follow Up)</span>
                </td>
                <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                <td class="label-top">
                    <span
                        class="normal medium block">{{ isset($data['hasilIntruksi']) ? $data['hasilIntruksi'] : '' }}</span>
                </td>
            </tr>
        </table>
        <hr>
        @if ($kodeDepartemen == 16)
            <table width="100%">
                <tr>
                    <td width="25%" class="label-top">
                        <span class="normal medium block">Kesadaran</span>
                        <span class="normal medium block">Diagnostik Terpenting</span>
                    </td>
                    <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                    <td class="label-top">
                        <span
                            class="normal medium block">{{ isset($data['kesadaran']) ? $data['kesadaran'] : '' }}</span>
                    </td>
                    <td class="label-top" width="12%">
                        <span class="normal medium block">Skala Nyeri</span>
                    </td>
                    <td class="label-top" width="2%">
                        <span class="normal medium block">:</span>
                    </td>
                    <td class="label-top" width="20%">
                        <span
                            class="normal medium block">{{ isset($data['skalaNyeri']['label']) ? $data['skalaNyeri']['label'] : '' }}</span>
                    </td>
                    <td class="label-top" width="5%">
                        <span class="normal medium block">GCS</span>
                    </td>
                    <td class="label-top" width="2%">
                        <span class="normal medium block">:</span>
                    </td>
                    <td class="label-top">
                        <span class="normal medium block">{{ isset($data['gcs']) ? $data['gcs'] : '' }}</span>
                    </td>
                </tr>
            </table>
        @endif
        <table width="100%">
            <tr>
                <td width="25%" class="label-top">
                    <span class="normal medium block">Pengobatan dilanjut</span>
                    <span class="normal medium block">Diagnostik Terpenting</span>
                </td>
                <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                <td class="label-top">
                    <span
                        class="normal medium block">{{ isset($data['poli']['label']) ? $data['poli']['label'] : '' }}</span>
                </td>
                <td class="label-top">
                    <span class="normal medium block">Tanggal Kontrol Poliklinik</span>
                </td>
                <td class="label-top">
                    <span class="normal medium block">{{ $identitas['tglkontrol'] }}</span>
                    class="normal medium block">{{ isset($identitas['tglkontrol']) ? $identitas['tglkontrol'] : '' }}</span>
                </td>
            </tr>
        </table>
        @if ($kodeDepartemen == 16)
            <table width="100%">
                <tr>
                    <td width="21%" class="label-top">
                        <span class="normal medium block">Kondisi Waktu Keluar</span>
                    </td>
                    <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                    <td width="12%">
                        <input type="checkbox"
                            {{ isset($data['kondisiKeluar']) && $data['kondisiKeluar'] == 'Sembuh' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Sembuh</span>
                    </td>
                    <td width="14%">
                        <input type="checkbox"
                            {{ isset($data['kondisiKeluar']) && $data['kondisiKeluar']? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Pindah RS</span>
                    </td>
                    <td width="10%">
                        <input type="checkbox"
                            {{ isset($data['kondisiKeluar']) && $data['kondisiKeluar'] == 'PAPS' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">PAPS</span>
                    </td>
                    <td width="13%">
                        <input type="checkbox"
                            {{ isset($data['kondisiKeluar']) && $data['kondisiKeluar'] == 'Meninggal' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Meninggal</span>
                    </td>
                    <td width="12%">
                        <input type="checkbox"
                            {{ isset($data['kondisiKeluar']) && $data['kondisiKeluar'] == 'Lainnya' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Lain-lain</span>
                    </td>
                    @if (isset($data['kondisiKeluar']) && $data['kondisiKeluar'] == 'Lainnya')
                        <td width="2%">
                            <span class="normal medium" style="position: relative;top:-5px">Ket:</span>
                        </td>
                        <td width="10%">
                            <span class="normal medium"
                                style="position: relative;top:-5px">{{ $data['kondisiKeluarLainya'] ? $data['kondisiKeluarLainya'] : '' }}</span>
                        </td>
                    @endif

                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%" class="label-top">
                        <span class="normal medium block">Prognosis Ad. Function</span>
                        <span class="normal medium block">Diagnostik Terpenting</span>
                    </td>
                    <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                    <td width="15%">
                        <input type="checkbox"
                            {{ isset($data['prognosisAdFunct_1']) && $data['prognosisAdFunct_1'] == 'Ad Bonam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Ad. Bonam</span>
                    </td>
                    <td width="15%">
                        <input type="checkbox"
                            {{ isset($data['prognosisAdFunct_2']) && $data['prognosisAdFunct_2'] == 'Ad Malam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Ad. Malam</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox"
                            {{ isset($data['prognosisAdFunct_3']) && $data['prognosisAdFunct_3'] == 'Dubia Ad Bonam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Dubia Ad. Bonam</span>
                    </td>
                    <td>
                        <input type="checkbox"
                            {{ isset($data['prognosisAdFunct_4']) && $data['prognosisAdFunct_4'] == 'Dubia Ad Malam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Dubia Ad. Malam</span>
                    </td>
                </tr>
            </table>
            <table width="100%">
                <tr>
                    <td width="25%" class="label-top">
                        <span class="normal medium block">Prognosis Ad. Vitam</span>
                        <span class="normal medium block">Diagnostik Terpenting</span>
                    </td>
                    <td width="2%" class="label-top"><span class="normal medium">:</span></td>
                    <td width="15%">
                        <input type="checkbox"
                            {{ isset($data['prognosisAdVitam_1']) && $data['prognosisAdVitam_1'] == 'Ad Bonam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Ad. Bonam</span>
                    </td>
                    <td width="15%">
                        <input type="checkbox"
                            {{ isset($data['prognosisAdVitam_2']) && $data['prognosisAdVitam_2'] == 'Ad Malam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Ad. Malam</span>
                    </td>
                    <td width="20%">
                        <input type="checkbox"
                            {{ isset($data['prognosisAdVitam_3']) && $data['prognosisAdVitam_3'] == 'Dubia Ad Bonam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Dubia Ad. Bonam</span>
                    </td>
                    <td>
                        <input type="checkbox"
                            {{ isset($data['prognosisAdVitam_4']) && $data['prognosisAdVitam_4'] == 'Dubia Ad Malam' ? 'checked' : '' }} />
                        <span class="normal medium" style="position: relative;top:-7px">Dubia Ad. Malam</span>
                    </td>
                </tr>
            </table>
        @endif
        <table width="100%">
            <tr>
                <td class="label-top">
                    <span
                        class="normal medium block">{{ $kodeDepartemen == 16 ? 'Terapi Pulang ' : 'Terapi yang diberikan' }}:</span>
                    <span class="normal medium block">Diagnostik Terpenting</span>
                </td>
            </tr>
            <tr>
                <td>
                    <table style="border-collapse: collapse;" width="100%" class="inner-table" style="pag">
                        <tr>
                            <th class="inner-th" width="55%">
                                <span class="normal medium">Nama Obat</span>
                            </th>
                            <th class="inner-th" width="10%">
                                <span class="normal medium">Jumlah</span>
                            </th>
                            <th class="inner-th">
                                <span class="normal medium">Dosis</span>
                            </th>
                            <th class="inner-th" width="10%">
                                <span class="normal medium">Frekuensi</span>
                            </th>
                            <th class="inner-th">
                                <span class="normal medium">Waktu Pakai</span>
                            </th>
                        </tr>
                        @if (isset($data['detailObatResep']))
                            @foreach ($data['detailObatResep'] as $item)
                                @if (isset($item['obat']))
                                    <tr>
                                        <td class="inner-td">
                                            <span
                                                class="normal medium">{{ isset($item['obat']['label']) ? $item['obat']['label'] : '' }}</span>
                                        </td>
                                        <td class="inner-td" style="text-align: center">
                                            <span
                                                class="normal medium">{{ isset($item['jumlah']) ? $item['jumlah'] : '' }}</span>
                                        </td>
                                        <td class="inner-td" style="text-align: center">
                                            <span
                                                class="normal medium">{{ isset($item['dosis']) ? $item['dosis'] : '' }}</span>
                                        </td>
                                        <td class="inner-td" style="text-align: center">
                                            <span
                                                class="normal medium">{{ isset($item['aturanPakai']) ? $item['aturanPakai'] : '' }}</span>
                                        </td>
                                        <td class="inner-td" style="text-align: center">
                                            <span
                                                class="normal medium">{{ isset($item['waktuPakai']) ? $item['waktuPakai'] : '' }}</span>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td class="inner-td" colspan="5" style="text-align: center">
                                    <span class="normal medium">Belum Ada Data</span>
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>

        <br>
        <table width="100%">
            <tr>
                <td width="80%" style="vertical-align:bottom">
                    <table width="100%">
                        <tr>
                            <td>
                                <span class="normal medium block">Lembar 1 : Pasien</span>
                                <span class="normal medium block">Lembar 2 : Rekam Medis</span>
                                <span class="normal medium block">Lembar 3 : Penjamin</span>
                            </td>
                        </tr>
                    </table>
                </td>
                <td width="50%">
                    <table width="90%">
                        <tr>
                            <td>
                                <span class="normal medium block">Cirebon, {{ $identitas['dateNow'] }}</span>
                                <span class="normal medium block">Dokter Pengangung Jawab Pelayanan</span>
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align:center">
                                <img src="data:image/png;base64, {!! $tte !!}">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $identitas['tte'] }}" />
                            </td>
                        </tr>
                        <tr>
                            <td style="text-align: center">
                                <span class="normal medium block">{{ $identitas['dpjp'] }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table style="margin-top: 5px">
            <tr>
                <td>
                    <span class="normal medium block" style="font-size:9.5pt">Disclaimer : Dokumen ini milik pasien
                        yang bersifat rahasia,dan tidak boleh disebarluaskan</span>
                </td>
            </tr>
        </table> --}}

        <div>
</body>

</html>
