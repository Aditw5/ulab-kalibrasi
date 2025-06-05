@extends('template.head-emr')
@section('title', 'FORMULIR EDUKASI PASIEN DAN KELUARGA TERINTEGRASI')
@section('about', 'FORMULIR EDUKASI PASIEN DAN KELUARGA TERINTEGRASI')

@push('style')
    <style>
        .judulLabel {
            font-weight: bold;
        }

        .subJudul {
            position: relative;
            left: 15px;
        }

        .subJudul1 {
            position: relative;
            left: 18px;
        }

        .detail {
            position: relative;
            left: 20px;
        }

        .detail-2 {
            position: relative;
            left: 32px;
        }

        .detail-3 {
            position: relative;
            left: 38px;
        }
    </style>
@endpush

@section('content')

<table width="100%" border="1" style="border: solid black; border-collapse: collapse; font-family: Arial;">
    <tbody>
        <tr>
            <td width="7%" rowspan="5" align="center" valign="middle" style="border-right: none"><img
                src="https://rsdgunungjati.com/images/simrs/logo-rs.png" width="116" height="107" alt="" /></td>
                <td colspan="6" rowspan="5" align="center" valign="middle" style="border-left: none">
                    PEMERINTAH KOTA CIREBON<br>
                    RUMAH SAKIT DAERAH<br>
                    <span style="font-weight: bold; font-size: 18px;">RSD GUNUNG JATI KOTA CIREBON</span><br>
                    Jl. Kesambi No. 56 Tlp. (0231) 206330 - 202444 Fax. (0231) 206336<br>
                    Email: rsdgunungjati@cirebonkota.go.id
                </td>
                <td colspan="4" rowspan="5" align="center" valign="middle"
                style="font-size: 20px; font-family: Arial; font-style: normal; font-weight: bold;">
                FORMULIR EDUKASI PASIEN DAN<br>
                KELUARGA TERINTEGRASI
            </td>
            <td colspan="2" style="font-family: Arial">NO. RM</td>
            <td colspan="3" style="font-family: Arial">: {{ $identitas['nocm'] }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial">Nama</td>
            <td colspan="3" style="font-family: Arial">: {{ $identitas['namapasien'] }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial">Jenis Kelamin</td>
            <td colspan="3" style="font-family: Arial">: {{ $identitas['jeniskelamin'] }}</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial">Tanggal Lahir</td>
            <td colspan="3" style="font-family: Arial">: {{ $identitas['tgllahir'] }}</td>
        </tr>
        {{-- @if() ? : --}}
        {{-- {{ isset($data['pasien']['namapasien']) ? $data['pasien']['namapasien'] : '' }} --}}
        <tr>
            <td colspan="5" style="text-align: center; font-family: Arial;">
                (Mohon diisi atau tempelkan stiker jika ada)
            </td>
        </tr>
        <tr>
            <td colspan="14" style="font-family: Arial">
                INTRUKSI: Beri tanda ceklis (&#10004;) pada kotak yang sesuai, dapat lebih dari satu sesuai dengan
                kebutuhan pasien dan keluarga
            </td>
            <td colspan="2" style="font-family: Arial; text-align: center;">Kebutuhan Edukasi</td>
        </tr>
        <tr>
            <td colspan="14" style="font-family: Arial; border-bottom: none;">Persiapan Edukasi/Belajar</td>
            <td colspan="2" rowspan="10" valign="top" nowrap="nowrap" style="font-family: Arial">
                {{isset($data['kebutuhanEdukasiDiagnosis']) && $data['kebutuhanEdukasiDiagnosis'] == 'true' ? '☑' : '☐' }} Diagnois, tanda gejala, tatalaksana<br>
                {{isset($data['kebutuhanEdukasiProsedur']) && $data['kebutuhanEdukasiProsedur'] == 'true' ? '☑' : '☐' }} Prosedur diagnosis tertentu<br>
                {{isset($data['kebutuhanEdukasiManfaat']) && $data['kebutuhanEdukasiManfaat'] == 'true' ? '☑' : '☐' }} Manfaat, efek samping, interaksi obat dan makanan<br>
                {{isset($data['kebutuhanEdukasiProgamDiet']) && $data['kebutuhanEdukasiProgamDiet'] == 'true' ? '☑' : '☐' }} Program diet dan nutrisi<br>
                {{isset($data['kebutuhanEdukasiManajemen']) && $data['kebutuhanEdukasiManajemen'] == 'true' ? '☑' : '☐' }} Manajemen nyeri, cuci tangan, penggunaan APD<br>
                {{isset($data['kebutuhanEdukasiPenggunaanALat']) && $data['kebutuhanEdukasiPenggunaanALat'] == 'true' ? '☑' : '☐' }} Penggunaan alat kesehatan<br>
                {{isset($data['kebutuhanEdukasiProsedur']) && $data['kebutuhanEdukasiProsedur'] == 'true' ? '☑' : '☐' }} Prosedur perawatan (spesifik)<br>
                {{isset($data['kebutuhanEdukasiTeknikRehabilitasi']) && $data['kebutuhanEdukasiTeknikRehabilitasi'] == 'true' ? '☑' : '☐' }} Teknik rehabilitasi<br>
                {{isset($data['kebutuhanEdukasiWaktuKontrol']) && $data['kebutuhanEdukasiWaktuKontrol'] == 'true' ? '☑' : '☐' }} Waktu kontrol dan penggunaan obat di rumah
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-bottom: none; border-right: none;border-top: none;">
                Bahasa</td>
                <td width="1%" style="font-family: Arial; border: none;">:</td>
                <td colspan="2" style="font-family: Arial; border: none;">{{isset($data['bahasa']) && $data['bahasa'] == 'indonesia' ? '☑' : '☐' }} Indonesia</td>
                <td style="font-family: Arial; border: none;">{{isset($data['bahasa']) && $data['bahasa'] == 'inggris' ? '☑' : '☐' }} Inggris</td>
                <td colspan="2" style="font-family: Arial; border: none;">{{isset($data['bahasa']) && $data['bahasa'] == 'daerah' ? '☑' : '☐' }} Daerah</td>
                <td colspan="6" style="font-family: Arial; border-bottom: none; border-top:none;border-left:none;">
                    {{isset($data['bahasa']) && $data['bahasa'] == 'indonesia' ? '☑' : '☐' }} Lain-lain</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Kebutuhan Penterjemah</td>
                <td style="font-family: Arial;border: none;">:</td>
                <td colspan="2" style="font-family: Arial;border: none;">{{isset($data['kebutuhanPenerjemah']) && $data['kebutuhanPenerjemah'] == 'ya' ? '☑' : '☐' }} Ya</td>
                <td colspan="9" style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;">
                    {{isset($data['kebutuhanPenerjemah']) && $data['kebutuhanPenerjemah'] == 'tidak' ? '☑' : '☐'}} Tidak
                </td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Pendidikan Pasien</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td colspan="2" style="font-family: Arial; border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'sd' ? '☑' : '☐'}} SD</td>
            <td style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'sltp' ? '☑' : '☐'}} SLTP</td>
            <td style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'slta' ? '☑' : '☐'}} SLTA</td>
            <td width="7%" style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 's1' ? '☑' : '☐'}} S-1</td>
            <td colspan="6" style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;">
                {{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'lain-lain' ? '☑' : '☐'}} Lain-lain
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Baca dan tulis</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td colspan="2" style="font-family: Arial;border:none;">{{isset($data['bacaDanTulis']) && $data['bacaDanTulis'] == 'baik' ? '☑' : '☐'}} Baik</td>
            <td colspan="9" style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;">
                {{isset($data['bacaDanTulis']) && $data['bacaDanTulis'] == 'kurang' ? '☑' : '☐'}} Kurang
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Pilihan tipe pembelajaran</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td colspan="2" style="font-family: Arial;border:none;">{{isset($data['tipePemebelajaran']) && $data['tipePemebelajaran'] == 'Verbal' ? '☑' : '☐'}} Verbal</td>
            <td colspan="9" style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;">
                {{isset($data['tipePemebelajaran']) && $data['tipePemebelajaran'] == 'Tulisan' ? '☑' : '☐'}} Tulisan</td>
        </tr>
        <tr>
            <td colspan="14" style="font-family: Arial;border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Hambatan edukasi</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td width="5%" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasiTidakAda']) && $data['HambatanEdukasiTidakAda'] == 'true' ? '☑' : '☐'}} Tidak ada</td>
            <td colspan="2" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasiPenglihatanTerganggu']) && $data['HambatanEdukasiPenglihatanTerganggu'] == 'true' ? '☑' : '☐'}} Penglihatan terganggu</td>
            <td colspan="2" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasiBahasa']) && $data['HambatanEdukasiBahasa'] == 'true' ? '☑' : '☐'}} Bahasa</td>
            <td style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasiKognitifTerbatas']) && $data['HambatanEdukasiKognitifTerbatas'] == 'true' ? '☑' : '☐'}} Kognitif terbatas</td>
            <td width="6%" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasiMotivasiKurang']) && $data['HambatanEdukasiMotivasiKurang'] == 'true' ? '☑' : '☐'}} Motivasi kurang</td>
            <td colspan="4" style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">
                {{isset($data['HambatanEdukasiBudayaAgama']) && $data['HambatanEdukasiBudayaAgama'] == 'true' ? '☑' : '☐'}} Budaya/agama/spiritual
            </td>
        </tr>
        <tr>
            <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                &nbsp;
            </td>
            <td width="5%" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasaiEmosional']) && $data['HambatanEdukasaiEmosional'] == 'true' ? '☑' : '☐'}} Emosional</td>
            <td colspan="2" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasaiPendengaranTerganggu']) && $data['HambatanEdukasaiPendengaranTerganggu'] == 'true' ? '☑' : '☐'}} Pendengaran terganggu</td>
            <td colspan="2" style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasaiGangguanBicara']) && $data['HambatanEdukasaiGangguanBicara'] == 'true' ? '☑' : '☐'}} Gangguan bicara</td>
            <td style="font-family: Arial;border: none;">{{isset($data['HambatanEdukasaiFisikLemah']) && $data['HambatanEdukasaiFisikLemah'] == 'true' ? '☑' : '☐'}} Fisik lemah</td>
            <td colspan="5" style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">
                {{isset($data['HambatanEdukasaiLainlain']) && $data['HambatanEdukasaiLainlain'] == 'true' ? '☑' : '☐'}} Lain-lain
            </td>
        </tr>
        <tr>
            <td colspan="14" style="font-family: Arial;border-top:none">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" rowspan="2" nowrap="nowrap" style="text-align: center">KEBUTUHAN EDUKASI<br>
                TOPIK EDUKASI</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">TERLAKSANA</td>
            <td width="6%" rowspan="2" nowrap="nowrap" style="text-align: center">TGL EDUKASI</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">SASARAN<br>
                (PASIEN/KELURAGA/LAIN-LAIN)</td>
            <td width="6%" rowspan="2" nowrap="nowrap" style="text-align: center">TINGKAT<br>
                PEMAHAMAN<br>
                AWAL</td>
            <td width="7%" rowspan="2" nowrap="nowrap" style="text-align: center">METODE<br>
                EDUKASI</td>
            <td width="5%" rowspan="2" nowrap="nowrap" style="text-align: center">MATERIAL<br>
                EDUKASI</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">EDUKATOR</td>
            <td width="5%" rowspan="2" nowrap="nowrap" style="text-align: center">EVALUASI</td>
            <td width="6%" rowspan="2" nowrap="nowrap" style="text-align: center">TGL <br>
                RE-EDUKASI</td>
        </tr>
        <tr>
            <td width="4%" nowrap="nowrap" style="text-align: center">YA</td>
            <td width="6%" nowrap="nowrap" style="text-align: center">TIDAK</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">NAMA</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">TTD</td>
            <td width="4%" nowrap="nowrap" style="text-align: center">NAMA</td>
            <td width="8%" nowrap="nowrap" style="text-align: center">TTD</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="1">
                    <li>
                        Hak dan kewajiban pasien dan keluarga
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['hakKewajibanPasienTrue']) && $data['hakKewajibanPasienTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['hakKewajibanPasienTerlaksanaYa']) && $data['hakKewajibanPasienTerlaksanaYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['hakKewajibanPasienTerlaksanaTidak']) && $data['hakKewajibanPasienTerlaksanaTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">
                {{isset($data['hakKewajibanPasienTglEdukasi']) && $data['hakKewajibanPasienTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['hakKewajibanPasienTglEdukasi'])) : ''}}
            </td>
            <td width="7%" nowrap="nowrap" style="text-align: center">
                {{isset($data['hakKewajibanPasienNamaSasaran']) && $data['hakKewajibanPasienNamaSasaran'] != '' ? $data['hakKewajibanPasienNamaSasaran'] : ''}}
            </td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator0']) && $data['ttdedukator0'] != '')
                    <img src="{{ $data['ttdedukator0'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td width="6%" valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['hakKewajibanPasienPemahamanSudahMengerti']) && $data['hakKewajibanPasienPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['hakKewajibanPasienPemahamanEdukasiUlang']) && $data['hakKewajibanPasienPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['hakKewajibanPasienPemahamanHalBaru']) && $data['hakKewajibanPasienPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td width="7%" nowrap="nowrap" style="text-align: left">
                {{isset($data['hakKewajibanPasienMetodEdukasiIndvidu']) && $data['hakKewajibanPasienMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['hakKewajibanPasienMetodEdukasikelompok']) && $data['hakKewajibanPasienMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['hakKewajibanPasienMetodEdukasiCeramah']) && $data['hakKewajibanPasienMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['hakKewajibanPasienMetodEdukasiDemonstrasi']) && $data['hakKewajibanPasienMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td width="5%" nowrap="nowrap" style="text-align: left">
                {{isset($data['hakKewajibanPasienMaterialEdukasiLeaflet']) && $data['hakKewajibanPasienMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['hakKewajibanPasienMaterialEdukasiBooklet']) && $data['hakKewajibanPasienMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['hakKewajibanPasienMaterialEdukasiLembarBalik']) && $data['hakKewajibanPasienMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['hakKewajibanPasienMaterialEdukasiAudioVisual']) && $data['hakKewajibanPasienMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['hakKewajibanPasienNamaEdukator']['label']) && $data['hakKewajibanPasienNamaEdukator']['label'] != '' ? $data['hakKewajibanPasienNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran0']) && $data['ttdsasaran0'] != '')
                    <img src="{{ $data['ttdsasaran0'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['hakKewajibanPasienEvaluasiReedukasi']) && $data['hakKewajibanPasienEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['hakKewajibanPasienEvaluasiRedemonstrasi']) && $data['hakKewajibanPasienEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['hakKewajibanPasienEvaluasiSudahMengerti']) && $data['hakKewajibanPasienEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">
                {{isset($data['hakKewajibanPasienTglReEdukasi']) && $data['hakKewajibanPasienTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['hakKewajibanPasienTglReEdukasi'])) : ''}}
            </td>
        </tr>
        
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="2">
                    <li>
                        Pengertian penyakit dan dasar diagnosa
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['pengertianPenyakitTrue']) && $data['pengertianPenyakitTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['pengertianPenyakitTerlaksanaYa']) && $data['pengertianPenyakitTerlaksanaYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['pengertianPenyakitTerlaksanaTidak']) && $data['pengertianPenyakitTerlaksanaTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">
                {{isset($data['pengertianPenyakitTglEdukasi']) && $data['pengertianPenyakitTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['pengertianPenyakitTglEdukasi'])) : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                {{isset($data['pengertianPenyakitNamaSasaran']) && $data['pengertianPenyakitNamaSasaran'] != '' ? $data['pengertianPenyakitNamaSasaran'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator1']) && $data['ttdedukator1'] != '')
                    <img src="{{ $data['ttdedukator1'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['pengertianPenyakitPemahamanSudahMengerti']) && $data['pengertianPenyakitPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['pengertianPenyakitPemahamanEdukasiUlang']) && $data['pengertianPenyakitPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['pengertianPenyakitPemahamanHalBaru']) && $data['pengertianPenyakitPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['pengertianPenyakitMetodEdukasiIndvidu']) && $data['pengertianPenyakitMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['pengertianPenyakitMetodEdukasikelompok']) && $data['pengertianPenyakitMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['pengertianPenyakitMetodEdukasiCeramah']) && $data['pengertianPenyakitMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['pengertianPenyakitMetodEdukasiDemonstrasi']) && $data['pengertianPenyakitMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['pengertianPenyakitMaterialEdukasiLeaflet']) && $data['pengertianPenyakitMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['pengertianPenyakitMaterialEdukasiBooklet']) && $data['pengertianPenyakitMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['pengertianPenyakitMaterialEdukasiLembarBalik']) && $data['pengertianPenyakitMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['pengertianPenyakitMaterialEdukasiAudioVisual']) && $data['pengertianPenyakitMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td nowrap="nowrap" style="text-align: center">
                {{isset($data['pengertianPenyakiNamaEdukator']['label']) && $data['pengertianPenyakiNamaEdukator']['label'] != '' ? $data['pengertianPenyakiNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran1']) && $data['ttdsasaran1'] != '')
                    <img src="{{ $data['ttdsasaran1'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['pengertianPenyakitEvaluasiReedukasi']) && $data['pengertianPenyakitEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['pengertianPenyakitEvaluasiRedemonstrasi']) && $data['pengertianPenyakitEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['pengertianPenyakitEvaluasiSudahMengerti']) && $data['pengertianPenyakitEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['pengertianPenyakitTglReEdukasi']) && $data['pengertianPenyakitTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['pengertianPenyakitTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="3">
                    <li>
                        Masalah klinis
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['masalahKlinisTrue']) && $data['masalahKlinisTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['masalahKlinisTerlaksanaYa']) && $data['masalahKlinisTerlaksanaYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['masalahKlinisTerlaksanaTidak']) && $data['masalahKlinisTerlaksanaTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['masalahKlinisTglEdukasi']) && $data['masalahKlinisTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['masalahKlinisNamaSasaran']) && $data['masalahKlinisNamaSasaran'] != '' ? $data['masalahKlinisNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator2']) && $data['ttdedukator2'] != '')
                    <img src="{{ $data['ttdedukator2'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['masalahKlinisPemahamanSudahMengerti']) && $data['masalahKlinisPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['masalahKlinisPemahamanEdukasiUlang']) && $data['masalahKlinisPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['masalahKlinisPemahamanHalBaru']) && $data['masalahKlinisPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['masalahKlinisMetodEdukasiIndvidu']) && $data['masalahKlinisMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['masalahKlinisMetodEdukasikelompok']) && $data['masalahKlinisMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['masalahKlinisMetodEdukasiCeramah']) && $data['masalahKlinisMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['masalahKlinisMetodEdukasiDemonstrasi']) && $data['masalahKlinisMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['masalahKlinisMaterialEdukasiLeaflet']) && $data['masalahKlinisMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['masalahKlinisMaterialEdukasiBooklet']) && $data['masalahKlinisMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['masalahKlinisMaterialEdukasiLembarBalik']) && $data['masalahKlinisMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['masalahKlinisMaterialEdukasiAudioVisual']) && $data['masalahKlinisMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['masalahKlinisNamaEdukator']['label']) && $data['masalahKlinisNamaEdukator']['label'] != '' ? $data['masalahKlinisNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran2']) && $data['ttdsasaran2'] != '')
                    <img src="{{ $data['ttdsasaran2'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['masalahKlinisEvaluasiReedukasi']) && $data['masalahKlinisEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['masalahKlinisEvaluasiRedemonstrasi']) && $data['masalahKlinisEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['masalahKlinisEvaluasiSudahMengerti']) && $data['masalahKlinisEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['masalahKlinisTglReEdukasi']) && $data['masalahKlinisTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="4">

                    <li>
                        Tanda dan gejala suatu penyakit
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tandaGejalaPenyakitTrue']) && $data['tandaGejalaPenyakitTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tandaGejalaPenyakitYa']) && $data['tandaGejalaPenyakitYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tandaGejalaPenyakitTidak']) && $data['tandaGejalaPenyakitTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tandaGejalaPenyakitTglEdukasi']) && $data['tandaGejalaPenyakitTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['tandaGejalaPenyakitNamaSasaran']) && $data['tandaGejalaPenyakitNamaSasaran'] != '' ? $data['tandaGejalaPenyakitNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator3']) && $data['ttdedukator3'] != '')
                    <img src="{{ $data['ttdedukator3'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['tandaGejalaPenyakitPemahamanSudahMengerti']) && $data['tandaGejalaPenyakitPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['tandaGejalaPenyakitPemahamanEdukasiUlang']) && $data['tandaGejalaPenyakitPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['tandaGejalaPenyakitPemahamanHalBaru']) && $data['tandaGejalaPenyakitPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['tandaGejalaPenyakitMetodEdukasiIndvidu']) && $data['tandaGejalaPenyakitMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['tandaGejalaPenyakitMetodEdukasikelompok']) && $data['tandaGejalaPenyakitMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['tandaGejalaPenyakitMetodEdukasiCeramah']) && $data['tandaGejalaPenyakitMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['tandaGejalaPenyakitMetodEdukasiDemonstrasi']) && $data['tandaGejalaPenyakitMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['tandaGejalaPenyakitMaterialEdukasiLeaflet']) && $data['tandaGejalaPenyakitMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['tandaGejalaPenyakitMaterialEdukasiBooklet']) && $data['tandaGejalaPenyakitMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['tandaGejalaPenyakitMaterialEdukasiLembarBalik']) && $data['tandaGejalaPenyakitMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['tandaGejalaPenyakitMaterialEdukasiAudioVisual']) && $data['tandaGejalaPenyakitMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['tandaGejalaPenyakitNamaEdukator']['label']) && $data['tandaGejalaPenyakitNamaEdukator']['label'] != '' ? $data['tandaGejalaPenyakitNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran3']) && $data['ttdsasaran3'] != '')
                    <img src="{{ $data['ttdsasaran3'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['tandaGejalaPenyakitEvaluasiReedukasi']) && $data['tandaGejalaPenyakitEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['tandaGejalaPenyakitEvaluasiRedemonstrasi']) && $data['tandaGejalaPenyakitEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['tandaGejalaPenyakitEvaluasiSudahMengerti']) && $data['tandaGejalaPenyakitEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tandaGejalaPenyakitTglReEdukasi']) && $data['tandaGejalaPenyakitTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['tandaGejalaPenyakitTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="5">
                    <li>
                        Penatalaksaan penyakit
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penatalaksanaanPenyakiTrue']) && $data['penatalaksanaanPenyakiTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penatalaksanaanPenyakiYa']) && $data['penatalaksanaanPenyakiYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penatalaksanaanPenyakiTidak']) && $data['penatalaksanaanPenyakiTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penatalaksanaanPenyakiTglEdukasi']) && $data['penatalaksanaanPenyakiTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['penatalaksanaanPenyakiNamaSasaran']) && $data['penatalaksanaanPenyakiNamaSasaran'] != '' ? $data['penatalaksanaanPenyakiNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator4']) && $data['ttdedukator4'] != '')
                    <img src="{{ $data['ttdedukator4'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['penatalaksanaanPenyakiPemahamanSudahMengerti']) && $data['penatalaksanaanPenyakiPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['penatalaksanaanPenyakiPemahamanEdukasiUlang']) && $data['penatalaksanaanPenyakiPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['penatalaksanaanPenyakiPemahamanHalBaru']) && $data['penatalaksanaanPenyakiPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['penatalaksanaanPenyakiMetodEdukasiIndvidu']) && $data['penatalaksanaanPenyakiMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['penatalaksanaanPenyakiMetodEdukasikelompok']) && $data['penatalaksanaanPenyakiMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['penatalaksanaanPenyakiMetodEdukasiCeramah']) && $data['penatalaksanaanPenyakiMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['penatalaksanaanPenyakiMetodEdukasiDemonstrasi']) && $data['penatalaksanaanPenyakiMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['penatalaksanaanPenyakiMaterialEdukasiLeaflet']) && $data['penatalaksanaanPenyakiMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['penatalaksanaanPenyakiMaterialEdukasiBooklet']) && $data['penatalaksanaanPenyakiMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['penatalaksanaanPenyakiMaterialEdukasiLembarBalik']) && $data['penatalaksanaanPenyakiMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['penatalaksanaanPenyakiMaterialEdukasiAudioVisual']) && $data['penatalaksanaanPenyakiMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['penatalaksanaanPenyakiNamaEdukator']['label']) && $data['penatalaksanaanPenyakiNamaEdukator']['label'] != '' ? $data['penatalaksanaanPenyakiNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran4']) && $data['ttdsasaran4'] != '')
                    <img src="{{ $data['ttdsasaran4'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['penatalaksanaanPenyakiEvaluasiReedukasi']) && $data['penatalaksanaanPenyakiEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['penatalaksanaanPenyakiEvaluasiRedemonstrasi']) && $data['penatalaksanaanPenyakiEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['penatalaksanaanPenyakiEvaluasiSudahMengerti']) && $data['penatalaksanaanPenyakiEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penatalaksanaanPenyakiTglReEdukasi']) && $data['penatalaksanaanPenyakiTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['penatalaksanaanPenyakiTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="6">
                    <li>
                        Prosedur diagnostik tertentu, bila ada (sebutkan)
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurDiagnostikTrue']) && $data['prosedurDiagnostikTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurDiagnostikYa']) && $data['prosedurDiagnostikYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurDiagnostikTidak']) && $data['prosedurDiagnostikTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurDiagnostikTglEdukasi']) && $data['prosedurDiagnostikTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurDiagnostikNamaSasaran']) && $data['prosedurDiagnostikNamaSasaran'] != '' ? $data['prosedurDiagnostikNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator5']) && $data['ttdedukator5'] != '')
                    <img src="{{ $data['ttdedukator5'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurDiagnostikPemahamanSudahMengerti']) && $data['prosedurDiagnostikPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurDiagnostikPemahamanEdukasiUlang']) && $data['prosedurDiagnostikPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurDiagnostikPemahamanHalBaru']) && $data['prosedurDiagnostikPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurDiagnostikMetodEdukasiIndvidu']) && $data['prosedurDiagnostikMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurDiagnostikMetodEdukasikelompok']) && $data['prosedurDiagnostikMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurDiagnostikMetodEdukasiCeramah']) && $data['prosedurDiagnostikMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurDiagnostikMetodEdukasiDemonstrasi']) && $data['prosedurDiagnostikMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurDiagnostikMaterialEdukasiLeaflet']) && $data['prosedurDiagnostikMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurDiagnostikMaterialEdukasiBooklet']) && $data['prosedurDiagnostikMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurDiagnostikMaterialEdukasiLembarBalik']) && $data['prosedurDiagnostikMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurDiagnostikMaterialEdukasiAudioVisual']) && $data['prosedurDiagnostikMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurDiagnostikNamaEdukator']['label']) && $data['prosedurDiagnostikNamaEdukator']['label'] != '' ? $data['prosedurDiagnostikNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran5']) && $data['ttdsasaran5'] != '')
                    <img src="{{ $data['ttdsasaran5'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurDiagnostikEvaluasiReedukasi']) && $data['prosedurDiagnostikEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurDiagnostikEvaluasiRedemonstrasi']) && $data['prosedurDiagnostikEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurDiagnostikEvaluasiSudahMengerti']) && $data['prosedurDiagnostikEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurDiagnostikTglReEdukasi']) && $data['prosedurDiagnostikTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurDiagnostikTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="7">
                    <li>
                        Komplikasi
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['komplikasiTrue']) && $data['komplikasiTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['komplikasiYa']) && $data['komplikasiYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['komplikasiTidak']) && $data['komplikasiTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['komplikasiTglEdukasi']) && $data['komplikasiTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['komplikasiNamaSasaran']) && $data['komplikasiNamaSasaran'] != '' ? $data['komplikasiNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator6']) && $data['ttdedukator6'] != '')
                    <img src="{{ $data['ttdedukator6'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['komplikasiPemahamanSudahMengerti']) && $data['komplikasiPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['komplikasiPemahamanEdukasiUlang']) && $data['komplikasiPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['komplikasiPemahamanHalBaru']) && $data['komplikasiPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['komplikasiMetodEdukasiIndvidu']) && $data['komplikasiMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['komplikasiMetodEdukasikelompok']) && $data['komplikasiMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['komplikasiMetodEdukasiCeramah']) && $data['komplikasiMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['komplikasiMetodEdukasiDemonstrasi']) && $data['komplikasiMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['komplikasiMaterialEdukasiLeaflet']) && $data['komplikasiMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['komplikasiMaterialEdukasiBooklet']) && $data['komplikasiMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['komplikasiMaterialEdukasiLembarBalik']) && $data['komplikasiMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['komplikasiMaterialEdukasiAudioVisual']) && $data['komplikasiMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['komplikasiNamaEdukator']['label']) && $data['komplikasiNamaEdukator']['label'] != '' ? $data['komplikasiNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran6']) && $data['ttdsasaran6'] != '')
                    <img src="{{ $data['ttdsasaran6'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['komplikasiEvaluasiReedukasi']) && $data['komplikasiEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['komplikasiEvaluasiRedemonstrasi']) && $data['komplikasiEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['komplikasiEvaluasiSudahMengerti']) && $data['komplikasiEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['komplikasiTglReEdukasi']) && $data['komplikasiTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['komplikasiTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="8">
                    <li>
                        Kemungkinan hasil yang diharapkan/tidak terduga 
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['kemungkinanHasilTrue']) && $data['kemungkinanHasilTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['kemungkinanHasilYa']) && $data['kemungkinanHasilYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['kemungkinanHasilTidak']) && $data['kemungkinanHasilTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['kemungkinanHasilTglEdukasi']) && $data['kemungkinanHasilTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['kemungkinanHasilNamaSasaran']) && $data['kemungkinanHasilNamaSasaran'] != '' ? $data['kemungkinanHasilNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator7']) && $data['ttdedukator7'] != '')
                    <img src="{{ $data['ttdedukator7'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['kemungkinanHasilPemahamanSudahMengerti']) && $data['kemungkinanHasilPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['kemungkinanHasilPemahamanEdukasiUlang']) && $data['kemungkinanHasilPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['kemungkinanHasilPemahamanHalBaru']) && $data['kemungkinanHasilPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['kemungkinanHasilMetodEdukasiIndvidu']) && $data['kemungkinanHasilMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['kemungkinanHasilMetodEdukasikelompok']) && $data['kemungkinanHasilMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['kemungkinanHasilMetodEdukasiCeramah']) && $data['kemungkinanHasilMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['kemungkinanHasilMetodEdukasiDemonstrasi']) && $data['kemungkinanHasilMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['kemungkinanHasilMaterialEdukasiLeaflet']) && $data['kemungkinanHasilMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['kemungkinanHasilMaterialEdukasiBooklet']) && $data['kemungkinanHasilMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['kemungkinanHasilMaterialEdukasiLembarBalik']) && $data['kemungkinanHasilMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['kemungkinanHasilMaterialEdukasiAudioVisual']) && $data['kemungkinanHasilMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['kemungkinanHasilNamaEdukator']['label']) && $data['kemungkinanHasilNamaEdukator']['label'] != '' ? $data['kemungkinanHasilNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran7']) && $data['ttdsasaran7'] != '')
                    <img src="{{ $data['ttdsasaran7'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['kemungkinanHasilEvaluasiReedukasi']) && $data['kemungkinanHasilEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['kemungkinanHasilEvaluasiRedemonstrasi']) && $data['kemungkinanHasilEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['kemungkinanHasilEvaluasiSudahMengerti']) && $data['kemungkinanHasilEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['kemungkinanHasilTglReEdukasi']) && $data['kemungkinanHasilTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['kemungkinanHasilTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="9">
                    <li>
                        Prognosis
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prognosisTrue']) && $data['prognosisTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prognosisYa']) && $data['prognosisYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prognosisTidak']) && $data['prognosisTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prognosisTglEdukasi']) && $data['prognosisTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prognosisNamaSasaran']) && $data['prognosisNamaSasaran'] != '' ? $data['prognosisNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator8']) && $data['ttdedukator8'] != '')
                    <img src="{{ $data['ttdedukator8'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prognosisPemahamanSudahMengerti']) && $data['prognosisPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prognosisPemahamanEdukasiUlang']) && $data['prognosisPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prognosisPemahamanHalBaru']) && $data['prognosisPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prognosisMetodEdukasiIndvidu']) && $data['prognosisMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prognosisMetodEdukasikelompok']) && $data['prognosisMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prognosisMetodEdukasiCeramah']) && $data['prognosisMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prognosisMetodEdukasiDemonstrasi']) && $data['prognosisMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prognosisMaterialEdukasiLeaflet']) && $data['prognosisMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prognosisMaterialEdukasiBooklet']) && $data['prognosisMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prognosisMaterialEdukasiLembarBalik']) && $data['prognosisMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prognosisMaterialEdukasiAudioVisual']) && $data['prognosisMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prognosisNamaEdukator']['label']) && $data['prognosisNamaEdukator']['label'] != '' ? $data['prognosisNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran8']) && $data['ttdsasaran8'] != '')
                    <img src="{{ $data['ttdsasaran8'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prognosisEvaluasiReedukasi']) && $data['prognosisEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prognosisEvaluasiRedemonstrasi']) && $data['prognosisEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prognosisEvaluasiSudahMengerti']) && $data['prognosisEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prognosisTglReEdukasi']) && $data['prognosisTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prognosisTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="10">
                    <li>
                        Alternatif
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['alternatifTrue']) && $data['alternatifTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['alternatifYa']) && $data['alternatifYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['alternatifTidak']) && $data['alternatifTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['alternatifTglEdukasi']) && $data['alternatifTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['masalahKlinisTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['alternatifNamaSasaran']) && $data['alternatifNamaSasaran'] != '' ? $data['alternatifNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator9']) && $data['ttdedukator9'] != '')
                    <img src="{{ $data['ttdedukator9'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['alternatifPemahamanSudahMengerti']) && $data['alternatifPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['alternatifPemahamanEdukasiUlang']) && $data['alternatifPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['alternatifPemahamanHalBaru']) && $data['alternatifPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['alternatifMetodEdukasiIndvidu']) && $data['alternatifMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['alternatifMetodEdukasikelompok']) && $data['alternatifMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['alternatifMetodEdukasiCeramah']) && $data['alternatifMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['alternatifMetodEdukasiDemonstrasi']) && $data['alternatifMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['alternatifMaterialEdukasiLeaflet']) && $data['alternatifMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['alternatifMaterialEdukasiBooklet']) && $data['alternatifMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['alternatifMaterialEdukasiLembarBalik']) && $data['alternatifMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['alternatifMaterialEdukasiAudioVisual']) && $data['alternatifMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['alternatifNamaEdukator']['label']) && $data['alternatifNamaEdukator']['label'] != '' ? $data['alternatifNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran9']) && $data['ttdsasaran9'] != '')
                    <img src="{{ $data['ttdsasaran9'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['alternatifEvaluasiReedukasi']) && $data['alternatifEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['alternatifEvaluasiRedemonstrasi']) && $data['alternatifEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['alternatifEvaluasiSudahMengerti']) && $data['alternatifEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['alternatifTglReEdukasi']) && $data['alternatifTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['alternatifTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="11">
                    <li>
                        Manfaat Obat-obatan yang diberikan 
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['manfaatObatTrue']) && $data['manfaatObatTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['manfaatObatYa']) && $data['manfaatObatYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['manfaatObatTidak']) && $data['manfaatObatTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['manfaatObatTglEdukasi']) && $data['manfaatObatTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['manfaatObatTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['manfaatObatNamaSasaran']) && $data['manfaatObatNamaSasaran'] != '' ? $data['manfaatObatNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator10']) && $data['ttdedukator10'] != '')
                    <img src="{{ $data['ttdedukator10'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['manfaatObatPemahamanSudahMengerti']) && $data['manfaatObatPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['manfaatObatPemahamanEdukasiUlang']) && $data['manfaatObatPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['manfaatObatPemahamanHalBaru']) && $data['manfaatObatPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['manfaatObatMetodEdukasiIndvidu']) && $data['manfaatObatMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['manfaatObatMetodEdukasikelompok']) && $data['manfaatObatMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['manfaatObatMetodEdukasiCeramah']) && $data['manfaatObatMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['manfaatObatMetodEdukasiDemonstrasi']) && $data['manfaatObatMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['manfaatObatMaterialEdukasiLeaflet']) && $data['manfaatObatMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['manfaatObatMaterialEdukasiBooklet']) && $data['manfaatObatMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['manfaatObatMaterialEdukasiLembarBalik']) && $data['manfaatObatMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['manfaatObatMaterialEdukasiAudioVisual']) && $data['manfaatObatMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['manfaatObatNamaEdukator']['label']) && $data['manfaatObatNamaEdukator']['label'] != '' ? $data['manfaatObatNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran10']) && $data['ttdsasaran10'] != '')
                    <img src="{{ $data['ttdsasaran10'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['manfaatObatEvaluasiReedukasi']) && $data['manfaatObatEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['manfaatObatEvaluasiRedemonstrasi']) && $data['manfaatObatEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['manfaatObatEvaluasiSudahMengerti']) && $data['manfaatObatEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['manfaatObatTglReEdukasi']) && $data['manfaatObatTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['manfaatObatTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="12">
                    <li>
                        Efek samping obat-obaatan yang diberikan
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['efekSampingTrue']) && $data['efekSampingTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['efekSampingYa']) && $data['efekSampingYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['efekSampingTidak']) && $data['efekSampingTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['efekSampingTglEdukasi']) && $data['efekSampingTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['efekSampingTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['efekSampingNamaSasaran']) && $data['efekSampingNamaSasaran'] != '' ? $data['efekSampingNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator11']) && $data['ttdedukator11'] != '')
                    <img src="{{ $data['ttdedukator11'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['efekSampingPemahamanSudahMengerti']) && $data['efekSampingPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['efekSampingPemahamanEdukasiUlang']) && $data['efekSampingPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['efekSampingPemahamanHalBaru']) && $data['efekSampingPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['efekSampingMetodEdukasiIndvidu']) && $data['efekSampingMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['efekSampingMetodEdukasikelompok']) && $data['efekSampingMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['efekSampingMetodEdukasiCeramah']) && $data['efekSampingMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['efekSampingMetodEdukasiDemonstrasi']) && $data['efekSampingMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['efekSampingMaterialEdukasiLeaflet']) && $data['efekSampingMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['efekSampingMaterialEdukasiBooklet']) && $data['efekSampingMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['efekSampingMaterialEdukasiLembarBalik']) && $data['efekSampingMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['efekSampingMaterialEdukasiAudioVisual']) && $data['efekSampingMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['efekSampingNamaEdukator']['label']) && $data['efekSampingNamaEdukator']['label'] != '' ? $data['efekSampingNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran11']) && $data['ttdsasaran11'] != '')
                    <img src="{{ $data['ttdsasaran11'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['efekSampingEvaluasiReedukasi']) && $data['efekSampingEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['efekSampingEvaluasiRedemonstrasi']) && $data['efekSampingEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['efekSampingEvaluasiSudahMengerti']) && $data['efekSampingEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['efekSampingTglReEdukasi']) && $data['efekSampingTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['efekSampingTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="13">
                    <li>
                        Interaksi obat dan makanan
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['interaksiObatmakananTrue']) && $data['interaksiObatmakananTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['interaksiObatmakananYa']) && $data['interaksiObatmakananYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['interaksiObatmakananTidak']) && $data['interaksiObatmakananTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['interaksiObatmakananTglEdukasi']) && $data['interaksiObatmakananTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['interaksiObatmakananTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['interaksiObatmakananNamaSasaran']) && $data['interaksiObatmakananNamaSasaran'] != '' ? $data['interaksiObatmakananNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator12']) && $data['ttdedukator12'] != '')
                    <img src="{{ $data['ttdedukator12'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['interaksiObatmakananPemahamanSudahMengerti']) && $data['interaksiObatmakananPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['interaksiObatmakananPemahamanEdukasiUlang']) && $data['interaksiObatmakananPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['interaksiObatmakananPemahamanHalBaru']) && $data['interaksiObatmakananPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['interaksiObatmakananMetodEdukasiIndvidu']) && $data['interaksiObatmakananMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['interaksiObatmakananMetodEdukasikelompok']) && $data['interaksiObatmakananMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['interaksiObatmakananMetodEdukasiCeramah']) && $data['interaksiObatmakananMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['interaksiObatmakananMetodEdukasiDemonstrasi']) && $data['interaksiObatmakananMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['interaksiObatmakananMaterialEdukasiLeaflet']) && $data['interaksiObatmakananMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['interaksiObatmakananMaterialEdukasiBooklet']) && $data['interaksiObatmakananMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['interaksiObatmakananMaterialEdukasiLembarBalik']) && $data['interaksiObatmakananMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['interaksiObatmakananMaterialEdukasiAudioVisual']) && $data['interaksiObatmakananMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['interaksiObatmakananNamaEdukator']['label']) && $data['interaksiObatmakananNamaEdukator']['label'] != '' ? $data['interaksiObatmakananNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran12']) && $data['ttdsasaran12'] != '')
                    <img src="{{ $data['ttdsasaran12'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['interaksiObatmakananEvaluasiReedukasi']) && $data['interaksiObatmakananEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['interaksiObatmakananEvaluasiRedemonstrasi']) && $data['interaksiObatmakananEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['interaksiObatmakananEvaluasiSudahMengerti']) && $data['interaksiObatmakananEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['interaksiObatmakananTglReEdukasi']) && $data['interaksiObatmakananTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['interaksiObatmakananTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="14">
                    <li>
                        Program diet dan nutrisi sebutkan
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['programDietTrue']) && $data['programDietTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['programDietYa']) && $data['programDietYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['programDietTidak']) && $data['programDietTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['programDietTglEdukasi']) && $data['programDietTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['programDietTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['programDietNamaSasaran']) && $data['programDietNamaSasaran'] != '' ? $data['programDietNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator13']) && $data['ttdedukator13'] != '')
                    <img src="{{ $data['ttdedukator13'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['programDietPemahamanSudahMengerti']) && $data['programDietPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['programDietPemahamanEdukasiUlang']) && $data['programDietPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['programDietPemahamanHalBaru']) && $data['programDietPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['programDietMetodEdukasiIndvidu']) && $data['programDietMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['programDietMetodEdukasikelompok']) && $data['programDietMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['programDietMetodEdukasiCeramah']) && $data['programDietMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['programDietMetodEdukasiDemonstrasi']) && $data['programDietMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['programDietMaterialEdukasiLeaflet']) && $data['programDietMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['programDietMaterialEdukasiBooklet']) && $data['programDietMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['programDietMaterialEdukasiLembarBalik']) && $data['programDietMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['programDietMaterialEdukasiAudioVisual']) && $data['programDietMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['programDietNamaEdukator']['label']) && $data['programDietNamaEdukator']['label'] != '' ? $data['programDietNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran13']) && $data['ttdsasaran13'] != '')
                    <img src="{{ $data['ttdsasaran13'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['programDietEvaluasiReedukasi']) && $data['programDietEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['programDietEvaluasiRedemonstrasi']) && $data['programDietEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['programDietEvaluasiSudahMengerti']) && $data['programDietEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['programDietTglReEdukasi']) && $data['programDietTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['programDietTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="15">
                    <li>
                        Manajemen Nyeri VAS<br>
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['ManajemenNyeriTrue']) && $data['ManajemenNyeriTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['ManajemenNyeriYa']) && $data['ManajemenNyeriYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['ManajemenNyeriTidak']) && $data['ManajemenNyeriTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['ManajemenNyeriTglEdukasi']) && $data['ManajemenNyeriTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['ManajemenNyeriTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['ManajemenNyeriNamaSasaran']) && $data['ManajemenNyeriNamaSasaran'] != '' ? $data['ManajemenNyeriNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator14']) && $data['ttdedukator14'] != '')
                    <img src="{{ $data['ttdedukator14'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['ManajemenNyeriPemahamanSudahMengerti']) && $data['ManajemenNyeriPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['ManajemenNyeriPemahamanEdukasiUlang']) && $data['ManajemenNyeriPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['ManajemenNyeriPemahamanHalBaru']) && $data['ManajemenNyeriPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['ManajemenNyeriMetodEdukasiIndvidu']) && $data['ManajemenNyeriMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['ManajemenNyeriMetodEdukasikelompok']) && $data['ManajemenNyeriMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['ManajemenNyeriMetodEdukasiCeramah']) && $data['ManajemenNyeriMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['ManajemenNyeriMetodEdukasiDemonstrasi']) && $data['ManajemenNyeriMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['ManajemenNyeriMaterialEdukasiLeaflet']) && $data['ManajemenNyeriMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['ManajemenNyeriMaterialEdukasiBooklet']) && $data['ManajemenNyeriMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['ManajemenNyeriMaterialEdukasiLembarBalik']) && $data['ManajemenNyeriMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['ManajemenNyeriMaterialEdukasiAudioVisual']) && $data['ManajemenNyeriMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['ManajemenNyeriNamaEdukator']['label']) && $data['ManajemenNyeriNamaEdukator']['label'] != '' ? $data['ManajemenNyeriNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran14']) && $data['ttdsasaran14'] != '')
                    <img src="{{ $data['ttdsasaran14'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['ManajemenNyeriEvaluasiReedukasi']) && $data['ManajemenNyeriEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['ManajemenNyeriEvaluasiRedemonstrasi']) && $data['ManajemenNyeriEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['ManajemenNyeriEvaluasiSudahMengerti']) && $data['ManajemenNyeriEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['ManajemenNyeriTglReEdukasi']) && $data['ManajemenNyeriTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['ManajemenNyeriTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="16">
                    <li>Penggunaan alat kedokteran (alat kesehatan), (sebutkan) :<br>
                        ..................................................
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAlatKedokteranTrue']) && $data['penggunaanAlatKedokteranTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAlatKedokteranYa']) && $data['penggunaanAlatKedokteranYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAlatKedokteranTidak']) && $data['penggunaanAlatKedokteranTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAlatKedokteranTglEdukasi']) && $data['penggunaanAlatKedokteranTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['penggunaanAlatKedokteranTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAlatKedokteranNamaSasaran']) && $data['penggunaanAlatKedokteranNamaSasaran'] != '' ? $data['penggunaanAlatKedokteranNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator15']) && $data['ttdedukator15'] != '')
                    <img src="{{ $data['ttdedukator15'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAlatKedokteranPemahamanSudahMengerti']) && $data['penggunaanAlatKedokteranPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['penggunaanAlatKedokteranPemahamanEdukasiUlang']) && $data['penggunaanAlatKedokteranPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['penggunaanAlatKedokteranPemahamanHalBaru']) && $data['penggunaanAlatKedokteranPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAlatKedokteranMetodEdukasiIndvidu']) && $data['penggunaanAlatKedokteranMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['penggunaanAlatKedokteranMetodEdukasikelompok']) && $data['penggunaanAlatKedokteranMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['penggunaanAlatKedokteranMetodEdukasiCeramah']) && $data['penggunaanAlatKedokteranMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['penggunaanAlatKedokteranMetodEdukasiDemonstrasi']) && $data['penggunaanAlatKedokteranMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAlatKedokteranMaterialEdukasiLeaflet']) && $data['penggunaanAlatKedokteranMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['penggunaanAlatKedokteranMaterialEdukasiBooklet']) && $data['penggunaanAlatKedokteranMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['penggunaanAlatKedokteranMaterialEdukasiLembarBalik']) && $data['penggunaanAlatKedokteranMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['penggunaanAlatKedokteranMaterialEdukasiAudioVisual']) && $data['penggunaanAlatKedokteranMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['penggunaanAlatKedokteranNamaEdukator']['label']) && $data['penggunaanAlatKedokteranNamaEdukator']['label'] != '' ? $data['penggunaanAlatKedokteranNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran15']) && $data['ttdsasaran15'] != '')
                    <img src="{{ $data['ttdsasaran15'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAlatKedokteranEvaluasiReedukasi']) && $data['penggunaanAlatKedokteranEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['penggunaanAlatKedokteranEvaluasiRedemonstrasi']) && $data['penggunaanAlatKedokteranEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['penggunaanAlatKedokteranEvaluasiSudahMengerti']) && $data['penggunaanAlatKedokteranEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAlatKedokteranTglReEdukasi']) && $data['penggunaanAlatKedokteranTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['penggunaanAlatKedokteranTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="17">
                    <li>Cuci tangan yang benar<br>
                        ..................................................
                        <br>
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['cuciTanganTrue']) && $data['cuciTanganTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['cuciTanganYa']) && $data['cuciTanganYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['cuciTanganTidak']) && $data['cuciTanganTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['cuciTanganTglEdukasi']) && $data['cuciTanganTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['cuciTanganTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['cuciTanganNamaSasaran']) && $data['cuciTanganNamaSasaran'] != '' ? $data['cuciTanganNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator16']) && $data['ttdedukator16'] != '')
                    <img src="{{ $data['ttdedukator16'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['cuciTanganPemahamanSudahMengerti']) && $data['cuciTanganPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['cuciTanganPemahamanEdukasiUlang']) && $data['cuciTanganPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['cuciTanganPemahamanHalBaru']) && $data['cuciTanganPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['cuciTanganMetodEdukasiIndvidu']) && $data['cuciTanganMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['cuciTanganMetodEdukasikelompok']) && $data['cuciTanganMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['cuciTanganMetodEdukasiCeramah']) && $data['cuciTanganMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['cuciTanganMetodEdukasiDemonstrasi']) && $data['cuciTanganMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['cuciTanganMaterialEdukasiLeaflet']) && $data['cuciTanganMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['cuciTanganMaterialEdukasiBooklet']) && $data['cuciTanganMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['cuciTanganMaterialEdukasiLembarBalik']) && $data['cuciTanganMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['cuciTanganMaterialEdukasiAudioVisual']) && $data['cuciTanganMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['cuciTanganNamaEdukator']['label']) && $data['cuciTanganNamaEdukator']['label'] != '' ? $data['cuciTanganNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran16']) && $data['ttdsasaran16'] != '')
                    <img src="{{ $data['ttdsasaran16'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['cuciTanganEvaluasiReedukasi']) && $data['cuciTanganEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['cuciTanganEvaluasiRedemonstrasi']) && $data['cuciTanganEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['cuciTanganEvaluasiSudahMengerti']) && $data['cuciTanganEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['cuciTanganTglReEdukasi']) && $data['cuciTanganTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['cuciTanganTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="18">
                    <li>Penggunaan APD (masker dan sarung tangan)
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAPDTrue']) && $data['penggunaanAPDTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAPDYa']) && $data['penggunaanAPDYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAPDTidak']) && $data['penggunaanAPDTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAPDTglEdukasi']) && $data['penggunaanAPDTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['penggunaanAPDTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAPDNamaSasaran']) && $data['penggunaanAPDNamaSasaran'] != '' ? $data['penggunaanAPDNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator17']) && $data['ttdedukator17'] != '')
                    <img src="{{ $data['ttdedukator17'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAPDPemahamanSudahMengerti']) && $data['penggunaanAPDPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['penggunaanAPDPemahamanEdukasiUlang']) && $data['penggunaanAPDPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['penggunaanAPDPemahamanHalBaru']) && $data['penggunaanAPDPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAPDMetodEdukasiIndvidu']) && $data['penggunaanAPDMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['penggunaanAPDMetodEdukasikelompok']) && $data['penggunaanAPDMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['penggunaanAPDMetodEdukasiCeramah']) && $data['penggunaanAPDMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['penggunaanAPDMetodEdukasiDemonstrasi']) && $data['penggunaanAPDMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAPDMaterialEdukasiLeaflet']) && $data['penggunaanAPDMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['penggunaanAPDMaterialEdukasiBooklet']) && $data['penggunaanAPDMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['penggunaanAPDMaterialEdukasiLembarBalik']) && $data['penggunaanAPDMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['penggunaanAPDMaterialEdukasiAudioVisual']) && $data['penggunaanAPDMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['penggunaanAPDNamaEdukator']['label']) && $data['penggunaanAPDNamaEdukator']['label'] != '' ? $data['penggunaanAPDNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran17']) && $data['ttdsasaran17'] != '')
                    <img src="{{ $data['ttdsasaran17'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['penggunaanAPDEvaluasiReedukasi']) && $data['penggunaanAPDEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['penggunaanAPDEvaluasiRedemonstrasi']) && $data['penggunaanAPDEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['penggunaanAPDEvaluasiSudahMengerti']) && $data['penggunaanAPDEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['penggunaanAPDTglReEdukasi']) && $data['penggunaanAPDTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['penggunaanAPDTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="19">
                    <li>Prosedur perawatan spesifik
                    </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanATrue']) && $data['prosedurKeperawatanATrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanAYa']) && $data['prosedurKeperawatanAYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanATidak']) && $data['prosedurKeperawatanATidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanATglEdukasi']) && $data['prosedurKeperawatanATglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanATglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanANamaSasaran']) && $data['prosedurKeperawatanANamaSasaran'] != '' ? $data['prosedurKeperawatanANamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanAPemahamanSudahMengerti']) && $data['prosedurKeperawatanAPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurKeperawatanAPemahamanEdukasiUlang']) && $data['prosedurKeperawatanAPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurKeperawatanAPemahamanHalBaru']) && $data['prosedurKeperawatanAPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanAMetodEdukasiIndvidu']) && $data['prosedurKeperawatanAMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurKeperawatanAMetodEdukasikelompok']) && $data['prosedurKeperawatanAMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurKeperawatanAMetodEdukasiCeramah']) && $data['prosedurKeperawatanAMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurKeperawatanAMetodEdukasiDemonstrasi']) && $data['prosedurKeperawatanAMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanAMaterialEdukasiLeaflet']) && $data['prosedurKeperawatanAMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurKeperawatanAMaterialEdukasiBooklet']) && $data['prosedurKeperawatanAMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurKeperawatanAMaterialEdukasiLembarBalik']) && $data['prosedurKeperawatanAMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurKeperawatanAMaterialEdukasiAudioVisual']) && $data['prosedurKeperawatanAMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurKeperawatanANamaEdukator']['label']) && $data['prosedurKeperawatanANamaEdukator']['label'] != '' ? $data['prosedurKeperawatanANamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanAEvaluasiReedukasi']) && $data['prosedurKeperawatanAEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurKeperawatanAEvaluasiRedemonstrasi']) && $data['prosedurKeperawatanAEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurKeperawatanAEvaluasiSudahMengerti']) && $data['prosedurKeperawatanAEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanATglReEdukasi']) && $data['prosedurKeperawatanATglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanATglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="font-family: Arial">
                <ol start="2" type="a">
                    <li> ..... </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanBTrue']) && $data['prosedurKeperawatanBTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanBYa']) && $data['prosedurKeperawatanBYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanBTidak']) && $data['prosedurKeperawatanBTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanBTglEdukasi']) && $data['prosedurKeperawatanBTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanBTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanBNamaSasaran']) && $data['prosedurKeperawatanBNamaSasaran'] != '' ? $data['prosedurKeperawatanBNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanBPemahamanSudahMengerti']) && $data['prosedurKeperawatanBPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurKeperawatanBPemahamanEdukasiUlang']) && $data['prosedurKeperawatanBPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurKeperawatanBPemahamanHalBaru']) && $data['prosedurKeperawatanBPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanBMetodEdukasiIndvidu']) && $data['prosedurKeperawatanBMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurKeperawatanBMetodEdukasikelompok']) && $data['prosedurKeperawatanBMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurKeperawatanBMetodEdukasiCeramah']) && $data['prosedurKeperawatanBMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurKeperawatanBMetodEdukasiDemonstrasi']) && $data['prosedurKeperawatanBMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanBMaterialEdukasiLeaflet']) && $data['prosedurKeperawatanBMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurKeperawatanBMaterialEdukasiBooklet']) && $data['prosedurKeperawatanBMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurKeperawatanBMaterialEdukasiLembarBalik']) && $data['prosedurKeperawatanBMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurKeperawatanBMaterialEdukasiAudioVisual']) && $data['prosedurKeperawatanBMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurKeperawatanBNamaEdukator']['label']) && $data['prosedurKeperawatanBNamaEdukator']['label'] != '' ? $data['prosedurKeperawatanBNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanBEvaluasiReedukasi']) && $data['prosedurKeperawatanBEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurKeperawatanBEvaluasiRedemonstrasi']) && $data['prosedurKeperawatanBEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurKeperawatanBEvaluasiSudahMengerti']) && $data['prosedurKeperawatanBEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanBTglReEdukasi']) && $data['prosedurKeperawatanBTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanBTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="font-family: Arial">
                <ol start="3" type="a">
                    <li> ..... </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanCTrue']) && $data['prosedurKeperawatanCTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanCYa']) && $data['prosedurKeperawatanCYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanCTidak']) && $data['prosedurKeperawatanCTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanCTglEdukasi']) && $data['prosedurKeperawatanCTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanCTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanCNamaSasaran']) && $data['prosedurKeperawatanCNamaSasaran'] != '' ? $data['prosedurKeperawatanCNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanCPemahamanSudahMengerti']) && $data['prosedurKeperawatanCPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurKeperawatanCPemahamanEdukasiUlang']) && $data['prosedurKeperawatanCPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurKeperawatanCPemahamanHalBaru']) && $data['prosedurKeperawatanCPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanCMetodEdukasiIndvidu']) && $data['prosedurKeperawatanCMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurKeperawatanCMetodEdukasikelompok']) && $data['prosedurKeperawatanCMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurKeperawatanCMetodEdukasiCeramah']) && $data['prosedurKeperawatanCMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurKeperawatanCMetodEdukasiDemonstrasi']) && $data['prosedurKeperawatanCMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanCMaterialEdukasiLeaflet']) && $data['prosedurKeperawatanCMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurKeperawatanCMaterialEdukasiBooklet']) && $data['prosedurKeperawatanCMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurKeperawatanCMaterialEdukasiLembarBalik']) && $data['prosedurKeperawatanCMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurKeperawatanCMaterialEdukasiAudioVisual']) && $data['prosedurKeperawatanCMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurKeperawatanCNamaEdukator']['label']) && $data['prosedurKeperawatanCNamaEdukator']['label'] != '' ? $data['prosedurKeperawatanCNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanCEvaluasiReedukasi']) && $data['prosedurKeperawatanCEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurKeperawatanCEvaluasiRedemonstrasi']) && $data['prosedurKeperawatanCEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurKeperawatanCEvaluasiSudahMengerti']) && $data['prosedurKeperawatanCEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanCTglReEdukasi']) && $data['prosedurKeperawatanCTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanCTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="font-family: Arial">
                <ol start="4" type="a">
                    <li> ..... </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanDTrue']) && $data['prosedurKeperawatanDTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanDYa']) && $data['prosedurKeperawatanDYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanDTidak']) && $data['prosedurKeperawatanDTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanDTglEdukasi']) && $data['prosedurKeperawatanDTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanDTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanDNamaSasaran']) && $data['prosedurKeperawatanDNamaSasaran'] != '' ? $data['prosedurKeperawatanDNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanDPemahamanSudahMengerti']) && $data['prosedurKeperawatanDPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurKeperawatanDPemahamanEdukasiUlang']) && $data['prosedurKeperawatanDPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurKeperawatanDPemahamanHalBaru']) && $data['prosedurKeperawatanDPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanDMetodEdukasiIndvidu']) && $data['prosedurKeperawatanDMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurKeperawatanDMetodEdukasikelompok']) && $data['prosedurKeperawatanDMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurKeperawatanDMetodEdukasiCeramah']) && $data['prosedurKeperawatanDMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurKeperawatanDMetodEdukasiDemonstrasi']) && $data['prosedurKeperawatanDMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanDMaterialEdukasiLeaflet']) && $data['prosedurKeperawatanDMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurKeperawatanDMaterialEdukasiBooklet']) && $data['prosedurKeperawatanDMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurKeperawatanDMaterialEdukasiLembarBalik']) && $data['prosedurKeperawatanDMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurKeperawatanDMaterialEdukasiAudioVisual']) && $data['prosedurKeperawatanDMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurKeperawatanDNamaEdukator']['label']) && $data['prosedurKeperawatanDNamaEdukator']['label'] != '' ? $data['prosedurKeperawatanDNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanDEvaluasiReedukasi']) && $data['prosedurKeperawatanDEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurKeperawatanDEvaluasiRedemonstrasi']) && $data['prosedurKeperawatanDEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurKeperawatanDEvaluasiSudahMengerti']) && $data['prosedurKeperawatanDEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanDTglReEdukasi']) && $data['prosedurKeperawatanDTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanDTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="font-family: Arial">
                <ol start="5" type="a">
                    <li> ..... </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanETrue']) && $data['prosedurKeperawatanETrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanEYa']) && $data['prosedurKeperawatanEYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanETidak']) && $data['prosedurKeperawatanETidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanETglEdukasi']) && $data['prosedurKeperawatanETglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanETglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanENamaSasaran']) && $data['prosedurKeperawatanENamaSasaran'] != '' ? $data['prosedurKeperawatanENamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanEPemahamanSudahMengerti']) && $data['prosedurKeperawatanEPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurKeperawatanEPemahamanEdukasiUlang']) && $data['prosedurKeperawatanEPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurKeperawatanEPemahamanHalBaru']) && $data['prosedurKeperawatanEPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanEMetodEdukasiIndvidu']) && $data['prosedurKeperawatanEMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurKeperawatanEMetodEdukasikelompok']) && $data['prosedurKeperawatanEMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurKeperawatanEMetodEdukasiCeramah']) && $data['prosedurKeperawatanEMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurKeperawatanEMetodEdukasiDemonstrasi']) && $data['prosedurKeperawatanEMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanEMaterialEdukasiLeaflet']) && $data['prosedurKeperawatanEMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurKeperawatanEMaterialEdukasiBooklet']) && $data['prosedurKeperawatanEMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurKeperawatanEMaterialEdukasiLembarBalik']) && $data['prosedurKeperawatanEMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurKeperawatanEMaterialEdukasiAudioVisual']) && $data['prosedurKeperawatanEMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurKeperawatanENamaEdukator']['label']) && $data['prosedurKeperawatanENamaEdukator']['label'] != '' ? $data['prosedurKeperawatanENamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanEEvaluasiReedukasi']) && $data['prosedurKeperawatanEEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurKeperawatanEEvaluasiRedemonstrasi']) && $data['prosedurKeperawatanEEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurKeperawatanEEvaluasiSudahMengerti']) && $data['prosedurKeperawatanEEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanETglReEdukasi']) && $data['prosedurKeperawatanETglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanETglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="font-family: Arial">
                <ol start="6" type="a">
                    <li> ..... </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanFTrue']) && $data['prosedurKeperawatanFTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanFYa']) && $data['prosedurKeperawatanFYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanFTidak']) && $data['prosedurKeperawatanFTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanFTglEdukasi']) && $data['prosedurKeperawatanFTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanFTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanFNamaSasaran']) && $data['prosedurKeperawatanFNamaSasaran'] != '' ? $data['prosedurKeperawatanFNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanFPemahamanSudahMengerti']) && $data['prosedurKeperawatanFPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['prosedurKeperawatanFPemahamanEdukasiUlang']) && $data['prosedurKeperawatanFPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['prosedurKeperawatanFPemahamanHalBaru']) && $data['prosedurKeperawatanFPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanFMetodEdukasiIndvidu']) && $data['prosedurKeperawatanFMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['prosedurKeperawatanFMetodEdukasikelompok']) && $data['prosedurKeperawatanFMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['prosedurKeperawatanFMetodEdukasiCeramah']) && $data['prosedurKeperawatanFMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['prosedurKeperawatanFMetodEdukasiDemonstrasi']) && $data['prosedurKeperawatanFMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanFMaterialEdukasiLeaflet']) && $data['prosedurKeperawatanFMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['prosedurKeperawatanFMaterialEdukasiBooklet']) && $data['prosedurKeperawatanFMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['prosedurKeperawatanFMaterialEdukasiLembarBalik']) && $data['prosedurKeperawatanFMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['prosedurKeperawatanFMaterialEdukasiAudioVisual']) && $data['prosedurKeperawatanFMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['prosedurKeperawatanFNamaEdukator']['label']) && $data['prosedurKeperawatanFNamaEdukator']['label'] != '' ? $data['prosedurKeperawatanFNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['prosedurKeperawatanFEvaluasiReedukasi']) && $data['prosedurKeperawatanFEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['prosedurKeperawatanFEvaluasiRedemonstrasi']) && $data['prosedurKeperawatanFEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['prosedurKeperawatanFEvaluasiSudahMengerti']) && $data['prosedurKeperawatanFEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['prosedurKeperawatanFTglReEdukasi']) && $data['prosedurKeperawatanFTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['prosedurKeperawatanFTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="21">
                    <li>Teknik-teknik rehabilitasi</li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tekniRehabTrue']) && $data['tekniRehabTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tekniRehabYa']) && $data['tekniRehabYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tekniRehabTidak']) && $data['tekniRehabTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tekniRehabTglEdukasi']) && $data['tekniRehabTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['tekniRehabTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['tekniRehabNamaSasaran']) && $data['tekniRehabNamaSasaran'] != '' ? $data['tekniRehabNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['tekniRehabPemahamanSudahMengerti']) && $data['tekniRehabPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['tekniRehabPemahamanEdukasiUlang']) && $data['tekniRehabPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['tekniRehabPemahamanHalBaru']) && $data['tekniRehabPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['tekniRehabMetodEdukasiIndvidu']) && $data['tekniRehabMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['tekniRehabMetodEdukasikelompok']) && $data['tekniRehabMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['tekniRehabMetodEdukasiCeramah']) && $data['tekniRehabMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['tekniRehabMetodEdukasiDemonstrasi']) && $data['tekniRehabMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['tekniRehabMaterialEdukasiLeaflet']) && $data['tekniRehabMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['tekniRehabMaterialEdukasiBooklet']) && $data['tekniRehabMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['tekniRehabMaterialEdukasiLembarBalik']) && $data['tekniRehabMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['tekniRehabMaterialEdukasiAudioVisual']) && $data['tekniRehabMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['tekniRehabNamaEdukator']['label']) && $data['tekniRehabNamaEdukator']['label'] != '' ? $data['tekniRehabNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['tekniRehabEvaluasiReedukasi']) && $data['tekniRehabEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['tekniRehabEvaluasiRedemonstrasi']) && $data['tekniRehabEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['tekniRehabEvaluasiSudahMengerti']) && $data['tekniRehabEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['tekniRehabTglReEdukasi']) && $data['tekniRehabTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['tekniRehabTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                <ol start="20">
                    <li>Waktu kontrol dan penggunaan obat-obatan di rumah</li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['waktuKontrolTrue']) && $data['waktuKontrolTrue'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['waktuKontrolYa']) && $data['waktuKontrolYa'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['waktuKontrolTidak']) && $data['waktuKontrolTidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['waktuKontrolTglEdukasi']) && $data['waktuKontrolTglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['waktuKontrolTglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['waktuKontrolNamaSasaran']) && $data['waktuKontrolNamaSasaran'] != '' ? $data['waktuKontrolNamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['waktuKontrolPemahamanSudahMengerti']) && $data['waktuKontrolPemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['waktuKontrolPemahamanEdukasiUlang']) && $data['waktuKontrolPemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['waktuKontrolPemahamanHalBaru']) && $data['waktuKontrolPemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['waktuKontrolMetodEdukasiIndvidu']) && $data['waktuKontrolMetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['waktuKontrolMetodEdukasikelompok']) && $data['waktuKontrolMetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['waktuKontrolMetodEdukasiCeramah']) && $data['waktuKontrolMetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['waktuKontrolMetodEdukasiDemonstrasi']) && $data['waktuKontrolMetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['waktuKontrolMaterialEdukasiLeaflet']) && $data['waktuKontrolMaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['waktuKontrolMaterialEdukasiBooklet']) && $data['waktuKontrolMaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['waktuKontrolMaterialEdukasiLembarBalik']) && $data['waktuKontrolMaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['waktuKontrolMaterialEdukasiAudioVisual']) && $data['waktuKontrolMaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['waktuKontrolNamaEdukator']['label']) && $data['waktuKontrolNamaEdukator']['label'] != '' ? $data['waktuKontrolNamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['waktuKontrolEvaluasiReedukasi']) && $data['waktuKontrolEvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['waktuKontrolEvaluasiRedemonstrasi']) && $data['waktuKontrolEvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['waktuKontrolEvaluasiSudahMengerti']) && $data['waktuKontrolEvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['waktuKontrolTglReEdukasi']) && $data['waktuKontrolTglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['waktuKontrolTglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre1True']) && $data['textAre1True'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre1Ya']) && $data['textAre1Ya'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre1Tidak']) && $data['textAre1Tidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre1TglEdukasi']) && $data['textAre1TglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['textAre1TglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['textAre1NamaSasaran']) && $data['textAre1NamaSasaran'] != '' ? $data['textAre1NamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre1PemahamanSudahMengerti']) && $data['textAre1PemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['textAre1PemahamanEdukasiUlang']) && $data['textAre1PemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['textAre1PemahamanHalBaru']) && $data['textAre1PemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre1MetodEdukasiIndvidu']) && $data['textAre1MetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['textAre1MetodEdukasikelompok']) && $data['textAre1MetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['textAre1MetodEdukasiCeramah']) && $data['textAre1MetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['textAre1MetodEdukasiDemonstrasi']) && $data['textAre1MetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre1MaterialEdukasiLeaflet']) && $data['textAre1MaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['textAre1MaterialEdukasiBooklet']) && $data['textAre1MaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['textAre1MaterialEdukasiLembarBalik']) && $data['textAre1MaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['textAre1MaterialEdukasiAudioVisual']) && $data['textAre1MaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['textAre1NamaEdukator']['label']) && $data['textAre1NamaEdukator']['label'] != '' ? $data['textAre1NamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre1EvaluasiReedukasi']) && $data['textAre1EvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['textAre1EvaluasiRedemonstrasi']) && $data['textAre1EvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['textAre1EvaluasiSudahMengerti']) && $data['textAre1EvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre1TglReEdukasi']) && $data['textAre1TglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['textAre1TglReEdukasi'])) : ''}}</td>
        </tr>
        <tr>
            <td colspan="3" nowrap="nowrap" style="font-family: Arial">
                
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre2True']) && $data['textAre2True'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre2Ya']) && $data['textAre2Ya'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre2Tidak']) && $data['textAre2Tidak'] == true ? '☑' : '☐'}}</td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre2TglEdukasi']) && $data['textAre2TglEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['textAre2TglEdukasi'])) : ''}}</td>
            <td width="7%" nowrap="nowrap" style="text-align: center">{{isset($data['textAre2NamaSasaran']) && $data['textAre2NamaSasaran'] != '' ? $data['textAre2NamaSasaran'] : ''}}</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdedukator18']) && $data['ttdedukator18'] != '')
                    <img src="{{ $data['ttdedukator18'] }}" alt="Tanda Tangan Sasaran" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre2PemahamanSudahMengerti']) && $data['textAre2PemahamanSudahMengerti'] == true ? '☑' : '☐'}} Sudah mengerti<br>
                {{isset($data['textAre2PemahamanEdukasiUlang']) && $data['textAre2PemahamanEdukasiUlang'] == true ? '☑' : '☐'}} Edukasi ulang<br>
                {{isset($data['textAre2PemahamanHalBaru']) && $data['textAre2PemahamanHalBaru'] == true ? '☑' : '☐'}} Hal baru
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre2MetodEdukasiIndvidu']) && $data['textAre2MetodEdukasiIndvidu'] == true ? '☑' : '☐'}} Individu<br>
                {{isset($data['textAre2MetodEdukasikelompok']) && $data['textAre2MetodEdukasikelompok'] == true ? '☑' : '☐'}} Kelompok<br>
                {{isset($data['textAre2MetodEdukasiCeramah']) && $data['textAre2MetodEdukasiCeramah'] == true ? '☑' : '☐'}} Ceramah<br>
                {{isset($data['textAre2MetodEdukasiDemonstrasi']) && $data['textAre2MetodEdukasiDemonstrasi'] == true ? '☑' : '☐'}} Demonstrasi
            </td>
            <td nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre2MaterialEdukasiLeaflet']) && $data['textAre2MaterialEdukasiLeaflet'] == true ? '☑' : '☐'}} Leaflet<br>
                {{isset($data['textAre2MaterialEdukasiBooklet']) && $data['textAre2MaterialEdukasiBooklet'] == true ? '☑' : '☐'}} Booklet<br>
                {{isset($data['textAre2MaterialEdukasiLembarBalik']) && $data['textAre2MaterialEdukasiLembarBalik'] == true ? '☑' : '☐'}} Lembar balik<br>
                {{isset($data['textAre2MaterialEdukasiAudioVisual']) && $data['textAre2MaterialEdukasiAudioVisual'] == true ? '☑' : '☐'}} Audiovisual
            </td>
            <td width="4%" nowrap="nowrap" style="text-align: center">
                {{isset($data['textAre2NamaEdukator']['label']) && $data['textAre2NamaEdukator']['label'] != '' ? $data['textAre2NamaEdukator']['label'] : ''}}
            </td>
            <td nowrap="nowrap" style="text-align: center">
                @if(isset($data['ttdsasaran18']) && $data['ttdsasaran18'] != '')
                    <img src="{{ $data['ttdsasaran18'] }}" alt="Tanda Tangan Edukator" style="max-width: 100px; max-height: 50px;">
                @endif
            </td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                {{isset($data['textAre2EvaluasiReedukasi']) && $data['textAre2EvaluasiReedukasi'] == true ? '☑' : '☐'}} Re-edukasi<br>
                {{isset($data['textAre2EvaluasiRedemonstrasi']) && $data['textAre2EvaluasiRedemonstrasi'] == true ? '☑' : '☐'}} Re-demonstrasi<br>
                {{isset($data['textAre2EvaluasiSudahMengerti']) && $data['textAre2EvaluasiSudahMengerti'] == true ? '☑' : '☐'}} Sudah Mengerti
            </td>
            <td nowrap="nowrap" style="text-align: center">{{isset($data['textAre2TglReEdukasi']) && $data['textAre2TglReEdukasi'] != '' ? date('d-m-Y H:i:s', strtotime($data['textAre2TglReEdukasi'])) : ''}}</td>
        </tr>
    </tbody>
</table>
{{-- {{ dd($data) }} --}}
{{dd()}}
@endsection
