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

<table width="100%" border="1" style="border: solid black; border-collapse: collapse; font-family: Arial; font-weight: normal;">
    <tbody>
        <tr>
            <td width="7%" rowspan="5" align="center" valign="middle" style="border-right: none"><img
                    src="https://rsdgunungjati.com/images/simrs/logo-rs.png" width="116" height="107" alt="" /></td>
            <td colspan="3" rowspan="5" align="center" valign="middle" style="border-left: none">
                PEMERINTAH KOTA CIREBON<br>
                RUMAH SAKIT DAERAH<br>
                <span style="font-weight: bold; font-size: 18px;">RSD GUNUNG JATI KOTA CIREBON</span><br>
                Jl. Kesambi No. 56 Tlp. (0231) 206330 - 202444 Fax. (0231) 206336<br>
                Email: rsdgunungjati@cirebonkota.go.id
            </td>
            <td colspan="3" rowspan="5" align="center" valign="middle" style="border-left: none"><span style="font-size: 20px; font-family: Arial; font-style: normal; font-weight: bold;">FORMULIR EDUKASI PASIEN DAN<br>
          KELUARGA TERINTEGRAS</span></td>
            <td valign="top"
                style="font-family: Arial; font-style: normal; font-weight: bold; text-align: left;"><span style="font-family: Arial; font-weight: normal;">NO. RM</span></td>
            <td colspan="7" align="center" valign="top" style="font-family: Arial; font-style: normal; text-align: left;">
                : {{ $data['pasien']['nocm'] }}
            </td>
        </tr>
        <tr>
          <td valign="top"
                style="font-family: Arial; font-style: normal; font-weight: normal; text-align: left;">Nama</td>
          <td colspan="7" align="left" valign="top"
                style="font-family: Arial; font-style: normal; ext-align: left;">: {{ $data['pasien']['namapasien'] }}</td>
        </tr>
        <tr>
          <td valign="top"
                style="font-family: Arial; font-style: normal; text-align: left;"><span>Jenis Kelamin</span></td>
          <td colspan="7" align="left" valign="top"
                style="font-family: Arial; font-style: normal; text-align: left;">: {{ $data['pasien']['jeniskelamin'] }}</td>
        </tr>
        <tr>
          <td valign="top"
                style="font-family: Arial; font-style: normal; text-align: left;"><span>Tanggal Lahir</span></td>
          <td colspan="7" align="center" valign="top"
                style="font-family: Arial; font-style: normal; text-align: left;">: {{ $data['pasien']['tgllahir'] }}</td>
        </tr>
        <tr>
          <td colspan="8" align="center" valign="middle"
                style="text-align: center"><span>(Mohon diisi atau tempelkan stiker jika ada) </span></td>
        </tr>
        <tr>
            <td colspan="15" style="font-family: Arial">
                INTRUKSI: Beri tanda ceklis (&#10004;) pada kotak yang sesuai, dapat lebih dari satu sesuai dengan
                kebutuhan pasien dan keluarga
            </td>
        </tr>
        <tr>
          <td colspan="15" style="font-family: Arial; border-bottom: none;"><strong>Persiapan Edukasi/Belajar</strong></td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-bottom: none; border-right: none;border-top: none;">
                Bahasa</td>
            <td width="1%" style="font-family: Arial; border: none;">:</td>
            <td colspan="2" style="font-family: Arial; border: none;">{{isset($data['bahasa']) && $data['bahasa'] == 'indonesia' ? '☑' : '☐' }} Indonesia</td>
            <td style="font-family: Arial; border: none;">{{isset($data['bahasa']) && $data['bahasa'] == 'inggris' ? '☑' : '☐' }} Inggris</td>
            <td colspan="3" style="font-family: Arial; border: none;">{{isset($data['bahasa']) && $data['bahasa'] == 'daerah' ? '☑' : '☐' }} Daerah</td>
            <td style="font-family: Arial; border-bottom: none; border-top:none;border-left:none;border-right:none;">
                {{isset($data['bahasa']) && $data['bahasa'] == 'lain-lain' ? '☑' : '☐' }} Lain-lain</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Kebutuhan Penterjemah</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td colspan="2" style="font-family: Arial;border: none;">{{isset($data['kebutuhanPenerjemah']) && $data['kebutuhanPenerjemah'] == 'ya' ? '☑' : '☐' }} Ya</td>
            <td colspan="10" style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;">
                {{isset($data['kebutuhanPenerjemah']) && $data['kebutuhanPenerjemah'] == 'tidak' ? '☑' : '☐' }} Tidak
            </td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Pendidikan Pasien</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td colspan="2" style="font-family: Arial; border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'sd' ? '☑' : '☐' }} SD</td>
            <td style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'sltp' ? '☑' : '☐' }} SLTP</td>
            <td style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'slta' ? '☑' : '☐' }} SLTA</td>
            <td width="7%" colspan="2" style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 's1' ? '☑' : '☐' }} S-1</td>
            <td style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;border-right:none;">
                {{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'lain-lain' ? '☑' : '☐' }} Lain-lain</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">
                Baca dan tulis</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td colspan="2" style="font-family: Arial;border:none;">{{isset($data['pendidikanPasien']) && $data['pendidikanPasien'] == 'sd' ? '☑' : '☐' }} Baik</td>
            <td colspan="10" style="font-family: Arial;border-bottom: none; border-top:none;border-left:none;">
                &#9744; Kurang
            </td>
        </tr>
        <tr>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
            <td style="font-family: Arial;border: none;">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">Pilih tipe pembelajarani</td>
            <td style="font-family: Arial;border: none;">:</td>
            <td width="5%" style="font-family: Arial;border: none;">&#9744; Tidak ada</td>
            <td colspan="2" style="font-family: Arial;border: none;">&#9744; Bahasa</td>
            <td colspan="3" style="font-family: Arial;border: none;">&#9744; Kognitif terbatas</td>
            <td style="font-family: Arial;border: none;">&#9744; Motivasi kuarng</td>
        </tr>
        <tr>
            <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">&nbsp;
                
            </td>
            <td width="5%" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Budaya/agama/spiritual</span></td>
            <td colspan="2" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Emosional</span></td>
            <td colspan="3" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Pendengaran terganggu</span></td>
            <td style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Gangguan bicara</span></td>
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Penglihatan terganggu</span></td>
          <td colspan="2" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Fisik lemah</span></td>
          <td colspan="3" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Lain-lain</span></td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">Pilih topik pembelajaran pada kotak yang tersedia</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
          <td colspan="2" style="font-family: Arial;border: none;">&nbsp;</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Jenis Penyakit</span></td>
          <td colspan="3" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Rencana tindakan/teraoi</span></td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
          
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">&nbsp;</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
          
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;"><strong>Standar dan Prosedur yang diberikan atau diperlukan</strong></td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
        
        </tr>
        <tr>
          <td colspan="6" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">Jenis pelayanan termasuk terjadian yang diharapankan dan tidak diharapkan lagi</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
          
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">.......................</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">Pasien dan/atau keluarga untuk menerima informasi data edukasi</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
          
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Ya</span></td>
          <td colspan="3" style="font-family: Arial;border: none;"><span style="font-family: Arial;border-left: none;border-top: none;border-bottom: none;">&#9744; Tidak</span></td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
          
        </tr>
        <tr>
          <td colspan="3" style="font-family: Arial; border-top: none; border-bottom: none; border-right: none;">......................................</td>
          <td colspan="3" style="font-family: Arial;border: none;">........................................</td>
          <td colspan="3" style="font-family: Arial;border: none;">&nbsp;</td>
          <td style="font-family: Arial;border: none;">&nbsp;</td>
      
        </tr>
        <tr>
            <td rowspan="2" nowrap="nowrap" style="text-align: center">TANGGAL</td>
            <td rowspan="2" nowrap="nowrap" style="text-align: center">POLITEKNIK</td>
            <td colspan="3" rowspan="2" nowrap="nowrap" style="text-align: center">INFORMASI TENTANG</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">EDUKATOR</td>
            <td colspan="3" nowrap="nowrap" style="text-align: center">SASARAN<br>
                (PASIEN/KELURAGA/LAIN-LAIN)</td>
            
            <td width="6%" rowspan="2" nowrap="nowrap" style="text-align: center">EVALUASI</td>
        </tr>
        <tr>
            <td width="6%" nowrap="nowrap" style="text-align: center">Nama/Profesi</td>
            <td width="6%" nowrap="nowrap" style="text-align: center">TTD</td>
            <td width="7%" colspan="2" nowrap="nowrap" style="text-align: center">NAMA</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">TTD</td>
            
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="1">
                    <li>
                          Penyakit yang diderita pasien
                      </li>
                </ol>                
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="7%" colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="6%" valign="top" nowrap="nowrap" style="text-align: left">
                &#9744; Sudah mengerti<br>
                &#9744; Re-Edukasi<br>
                &#9744; Re-Demontrasi
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="2">
                    <li>
                          Rencana tindakan / terapi
                      </li>
                </ol>     
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="3">
                    <li>
                          Pengobatan dan prosedur yang diberikan atau diperlukan
                      </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="7%" colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="6%" valign="top" nowrap="nowrap" style="text-align: left">
                &#9744; Sudah mengerti<br>
                &#9744; Re-Edukasi<br>
                &#9744; Re-Demontrasi
            </td>
            
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="4">
                    <li>
                          Hasil pelayanan,termasuk terjadinya kejadian yang diharapkan dan tidak diharapkan
                      </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                &#9744; Sudah mengerti<br>
                &#9744; Re-Edukasi<br>
                &#9744; Re-Demontrasi
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="5">
                    <li>
                          Tatalaksana covid
                      </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="7%" colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="6%" valign="top" nowrap="nowrap" style="text-align: left">
                &#9744; Sudah mengerti<br>
                &#9744; Re-Edukasi<br>
                &#9744; Re-Demontrasi
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="1" type="a">
                    <li>
                          Hasil pemeriksaan
                      </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="text-align: left">
                &#9744; Sudah mengerti<br>
                &#9744; Re-Edukasi<br>
                &#9744; Re-Demontrasi
            </td>
        </tr>
        <tr>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td valign="top" nowrap="nowrap" style="font-family: Arial">&nbsp;</td>
            <td colspan="3" valign="top" nowrap="nowrap" style="font-family: Arial">
                <ol start="2" type="a">
                    <li>
                          Rencanakan tindakan dan pengobatan (isolasi mandiri/isolasi di RS/Karantina
                      </li>
                </ol>
            </td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="7%" colspan="2" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="10%" nowrap="nowrap" style="text-align: center">&nbsp;</td>
            <td width="6%" valign="top" nowrap="nowrap" style="text-align: left">
                &#9744; Sudah mengerti<br>
                &#9744; Re-Edukasi<br>
                &#9744; Re-Demontrasi
            </td>
        </tr>
        
        
    </tbody>
</table>
{{ dd($data) }}

@endsection
