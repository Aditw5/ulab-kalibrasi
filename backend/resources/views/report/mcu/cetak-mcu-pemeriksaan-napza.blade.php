@extends('template.layout-mcu')
{{-- @section('title', $dataReport['judul']) --}}
@section('page-style')
    <style>
        table tr td {
            font-size: 12pt;
        }

        .header-font {
            font-size: 14pt;
        }

        .header-title {
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            text-decoration-thickness: 2pt;
        }

        .normal-font {
            font-size: 12pt;
        }

        .table-identitas {
            width: 90%;
            margin-left: 30px;
        }

        .page {
            padding: 0 60px
        }

        .hasil-table,
        .hasil-table tr,
        .hasil-table td {
            border-collapse: collapse;
            border: 1px solid black;
            font-size: 10pt;
        }

        input[type="checkbox"]:checked {
            background-color: #000 !important;
            /* Warna hitam */
            border-color: #000 !important;
            /* Border hitam */
        }

        /* Hilangkan transparansi dari disabled checkbox */
        input[type="checkbox"]:disabled {
            opacity: 1 !important;
            /* Tidak transparan */
            cursor: not-allowed;
            /* Tetap menunjukkan tidak bisa diklik */
        }

        /* Centang putih di atas latar belakang hitam */
        input[type="checkbox"]:checked:after {
            color: #fff;
            /* Warna putih */
            display: block !important;
            text-align: center !important;
            font-size: 16px !important;
            line-height: 16px !important;
        }
    </style>
@endsection
@section('content')
    @php
        function intToRoman($num)
        {
            $map = [
                10 => 'X',
                9 => 'IX',
                5 => 'V',
                4 => 'IV',
                1 => 'I',
            ];

            $result = '';
            while ($num > 0) {
                foreach ($map as $key => $value) {
                    if ($num >= $key) {
                        $num -= $key;
                        $result .= $value;
                        break;
                    }
                }
            }
            return $result;
        }
    @endphp
    <tr>
        <td class="page">
            <table width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center" colspan="3">
                        {{-- <hr style="border:0.5px solid #000;margin-top:5px" /> --}}
                        <font class="header-font header-title" color="#000000">Surat keterangan pemeriksaan napza</font>
                        {{-- <hr style="border:0.5px solid #000;margin-top:5px; width:100%" /> --}}
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <font class="header-font">
                            No:
                            F.{{ isset($res[0]['nomorsurat']) ? $res[0]['nomorsurat'] : '' }}/IRJ-PEP/RSDGJ/{{ intToRoman(date('m')) }}/{{ \Carbon\Carbon::parse(date('Y'))->isoFormat('Y') }}
                        </font>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        Yang bertanda tangan di bawah ini :
                    </td>
                </tr>
            </table>
            <table class="table-identitas">
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Nama
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            {{ isset($dokter[0]) ? $dokter[0]->namalengkap : '' }}
                            {{-- {{isset($res[0]['dokterPemeriksaNapza']['label']) ? $res[0]['dokterPemeriksaNapza']['label'] : ''}} --}}
                        </font>
                    </td>
                </tr>
                {{-- <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        SIP
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {SIP DOKTER}
                    </font>
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <font class="normal-font">
                        NIP
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        :
                    </font>
                </td>
                <td>
                    <font class="normal-font">
                        {NIP DOKTER}
                    </font>
                </td>
            </tr> --}}
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Jabatan
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            DOKTER SPESIALIS KEDOKTERAN JIWA
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Instansi
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            {{ isset($res[0]['profile']['namaprofile']) ? $res[0]['profile']['namaprofile'] : '' }}
                        </font>
                    </td>
                </tr>
            </table>

            <table>
                <tr>
                    <td>
                        Telah melakukan pemeriksaan psikiatrik pada Tanggal
                        {{ \Carbon\Carbon::parse(isset($res[0]['tgl']) ? $res[0]['tgl'] : '')->isoFormat('DD MMMM Y') }},
                        Terhadap:
                    </td>
                </tr>
            </table>
            <table class="table-identitas">
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Nama
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            {{ isset($res[0]['namaPasien']) ? $res[0]['namaPasien'] : '' }}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Tempat Tgl.Lahir
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            {{ isset($res[0]['tempatLahir']) ? $res[0]['tempatLahir'] : '' }},
                            {{ \Carbon\Carbon::parse(isset($res[0]['tglLahir']) ? $res[0]['tglLahir'] : '')->isoFormat('DD MMMM Y') }}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Jenis Kelamin
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            {{ isset($res[0]['pasien']['jeniskelamin']) ? $res[0]['pasien']['jeniskelamin'] : '' }}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <font class="normal-font">
                            Alamat
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            :
                        </font>
                    </td>
                    <td>
                        <font class="normal-font">
                            {{ isset($res[0]['alamat']) ? $res[0]['alamat'] : '' }}
                        </font>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td>
                        <font class="normal-font">
                            Dan berdasarkan pemeriksaan :
                        </font>
                    </td>
                </tr>
            </table>
            <table style="border: 1px solid black; width: 100%" style="width: 100%">
                <tr>
                    @php
                        $date = $res[0]['tglPemeriksaanJiwa'];
                    @endphp
                    <td colspan="4">1. Pemeriksaan Fisik Diagnostik</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Tanda Vital</td>
                    <td>:</td>
                    <td>
                        {{-- {{dd($res[0])}} --}}
                        TD : {{ isset($res[0]['tensiPasien']) ? $res[0]['tensiPasien'] : '' }} mmHg
                        N : {{ isset($res[0]['nadiPasien']) ? $res[0]['nadiPasien'] : '' }} BPM
                        RR: {{ isset($res[0]['pernafasanPasien']) ? $res[0]['pernafasanPasien'] : '' }} XPM
                        SB: {{ isset($res[0]['suhuPasien']) ? $res[0]['suhuPasien'] : '' }}<sup>o</sup>C
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Penampilan</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 60px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['penampilan']) && $res[0]['penampilan'] === 'Rapih' ? 'checked' : '' }}>
                            Rapih
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['penampilan']) && $res[0]['penampilan'] === 'Kurang Rapih' ? 'checked' : '' }}>
                            Kurang Rapih
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Cara Berjalan</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 10px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['caraJalan']) && $res[0]['caraJalan'] === 'Sempoyongan' ? 'checked' : '' }}>
                            Sempoyongan
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['caraJalan']) && $res[0]['caraJalan'] === 'Biasa' ? 'checked' : '' }}>
                            Biasa
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Cara Bicara</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 32px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['caraBicara']) && $res[0]['caraBicara'] === 'Pelo/Cadel' ? 'checked' : '' }}>
                            Pelo/Cadel
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['caraBicara']) && $res[0]['caraBicara'] === 'Biasa' ? 'checked' : '' }}>
                            Biasa
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Mata Pupil</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 58px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['mataPupil']) && $res[0]['mataPupil'] === 'Miosis' ? 'checked' : '' }}>
                            Miosis
                        </label>
                        <label style="margin-right: 58px;">
                            <input type="checkbox"
                                {{ isset($res[0]['mataPupil']) && $res[0]['mataPupil'] === 'Midriasis' ? 'checked' : '' }}>
                            Midriasis
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['mataPupil']) && $res[0]['mataPupil'] === 'Normal' ? 'checked' : '' }}>
                            Normal
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Konjungtiva</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 28px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['konjungtiva']) && $res[0]['konjungtiva'] === 'Kemerahan' ? 'checked' : '' }}>
                            Kemerahan
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['konjungtiva']) && $res[0]['konjungtiva'] === 'Normal' ? 'checked' : '' }}>
                            Normal
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Lakrimasi</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 28px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['lakrimasi']) && $res[0]['lakrimasi'] === 'Meningkat' ? 'checked' : '' }}>
                            Meningkat
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['lakrimasi']) && $res[0]['lakrimasi'] === 'Normal' ? 'checked' : '' }}>
                            Normal
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Rhinorea</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 70px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['trrhinoreaemor']) && $res[0]['rhinorea'] === 'Ada' ? 'checked' : '' }}>
                            Ada
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['rhinorea']) && $res[0]['rhinorea'] === 'Tidak ada' ? 'checked' : '' }}>
                            Tidak ada
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Tremor</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 70px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['tremor']) && $res[0]['tremor'] === 'Ada' ? 'checked' : '' }}>
                            Ada
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['tremor']) && $res[0]['tremor'] === 'Tidak ada' ? 'checked' : '' }}>
                            Tidak ada
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Needle track</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 70px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['needletrack']) && $res[0]['needletrack'] === 'Ada' ? 'checked' : '' }}>
                            Ada
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['needletrack']) && $res[0]['needletrack'] === 'Tidak ada' ? 'checked' : '' }}>
                            Tidak ada
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Piloreksi</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 70px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['piloreksi']) && $res[0]['piloreksi'] === 'Ada' ? 'checked' : '' }}>
                            Ada
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['piloreksi']) && $res[0]['piloreksi'] === 'Tidak ada' ? 'checked' : '' }}>
                            Tidak ada
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">2. Pemeriksaan Psikiatrik</td>
                </tr>
                <tr>
                    <td></td>
                    <td>Alur Pembicaraan</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 50px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['alurPembicaraan']) && $res[0]['alurPembicaraan'] === 'Normal' ? 'checked' : '' }}>
                            Normal
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['alurPembicaraan']) && $res[0]['alurPembicaraan'] === 'Tidak normal' ? 'checked' : '' }}>
                            Tidak normal
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Waham</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 70px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['waham']) && $res[0]['waham'] === 'Ada' ? 'checked' : '' }}>
                            Ada
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['waham']) && $res[0]['waham'] === 'Tidak ada' ? 'checked' : '' }}>
                            Tidak ada
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Halusinasi</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['halusinasi']) && $res[0]['halusinasi'] === 'halusinasi' ? 'checked' : '' }}>
                            halusinasi
                        </label>
                        <label style="margin-right: 72px;">
                            <input type="checkbox"
                                {{ isset($res[0]['halusinasi']) && $res[0]['halusinasi'] === 'Visual' ? 'checked' : '' }}>
                            Visual
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['halusinasi']) && $res[0]['halusinasi'] === 'Tidak ada' ? 'checked' : '' }}>
                            Tidak ada
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Lain-lain</td>
                    <td>:</td>
                    <td>
                        {{ isset($res[0]['lainlain']) ? $res[0]['lainlain'] : '' }}
                    </td>
                </tr>
                <tr>
                    <td colspan="3">3. Pemeriksaan Laboratorium Penunjang </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Cannabis</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['cannabis']) && $res[0]['cannabis'] === 'Reaktif' ? 'checked' : '' }}>
                            Reaktif
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['cannabis']) && $res[0]['cannabis'] === 'Non Reaktif' ? 'checked' : '' }}>
                            Non Reaktif
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Opiate</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['oppiate']) && $res[0]['oppiate'] === 'Reaktif' ? 'checked' : '' }}>
                            Reaktif
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['oppiate']) && $res[0]['oppiate'] === 'Non Reaktif' ? 'checked' : '' }}>
                            Non Reaktif
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Coccain</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['coccain']) && $res[0]['coccain'] === 'Reaktif' ? 'checked' : '' }}>
                            Reaktif
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['coccain']) && $res[0]['coccain'] === 'Non Reaktif' ? 'checked' : '' }}>
                            Non Reaktif
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Metamphetamine</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['metamphetamine']) && $res[0]['metamphetamine'] === 'Reaktif' ? 'checked' : '' }}>
                            Reaktif
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['metamphetamine']) && $res[0]['metamphetamine'] === 'Non Reaktif' ? 'checked' : '' }}>
                            Non Reaktif
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>MDMA</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['mdma']) && $res[0]['mdma'] === 'Reaktif' ? 'checked' : '' }}>
                            Reaktif
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['mdma']) && $res[0]['mdma'] === 'Non Reaktif' ? 'checked' : '' }}>
                            Non Reaktif
                        </label>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>Benzodiazepin</td>
                    <td>:</td>
                    <td>
                        <label style="margin-right: 35px;">
                            <input type="checkbox" style="background-color: #000"
                                {{ isset($res[0]['benzodiazepin']) && $res[0]['benzodiazepin'] === 'Reaktif' ? 'checked' : '' }}>
                            Reaktif
                        </label>
                        <label>
                            <input type="checkbox"
                                {{ isset($res[0]['benzodiazepin']) && $res[0]['benzodiazepin'] === 'Non Reaktif' ? 'checked' : '' }}>
                            Non Reaktif
                        </label>
                    </td>
                </tr>
                <tr>
                    <td colspan="4">4. Catatan : {{ $res[0]['catatanNapza'] }}</td>
                </tr>
            </table>
            <table style="margin-top: 10px">
                <tr>
                    <td>Kesimpulan: Pada saat pemeriksaan <span
                            style="font-weight: bold; text-decoration: {{ $res[0]['gejalaNarkotika'] == 'Tidak ada' ? 'line-through' : '' }}">DITEMUKAN</span>/
                        <span
                            style="font-weight: bold; text-decoration: {{ $res[0]['gejalaNarkotika'] == 'Ada' ? 'line-through' : '' }}">TIDAK
                            DITEMUKAN</span> tanda atau gejala penyalahgunaan narkotika / zat psikoaktif lainnya.</td>
                </tr>
                <tr>
                    <td style="padding: 5px 0">
                        <font class="normal-font">
                            Surat keterangan ini dibuat untuk keperluan :
                            {{ isset($res[0]['permintaan']) ? strtoupper($res[0]['permintaan']) : '' }}
                        </font>
                    </td>
                </tr>
            </table>
            <table>
                <tr>
                    <td style="width: 55%; margin-top: 40px">
                        <font class="normal-font">
                            <b>Surat keterangan ini tidak dapat digunakan selain untuk kepentingan yang tercantum di
                                atas</b>
                        </font>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <table>
                                        <tr>
                                            <td align="center">Cirebon,
                                                {{ \Carbon\Carbon::parse($res[0]['tgl'])->isoFormat('DD MMMM Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td align="center">Dokter yang memeriksa</td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <img src="data:image/svg+xml;base64,{{ $tte }}" alt="QR Code">
                                                {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($dokter[0]) ? $dokter[0]->namalengkap : ''}}" alt=""> --}}
                                                {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res[0]['dokterPemeriksaNapza']['label']) ? $res[0]['dokterPemeriksaNapza']['label'] : ''}}" alt=""> --}}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center">
                                                <b><u>{{ isset($dokter[0]) ? $dokter[0]->namalengkap : '' }}</u></b> <br>
                                                SIP. {{ isset($dokter[0]) ? $dokter[0]->nosip : '' }} <br>
                                                NIP. {{ isset($dokter[0]) ? $dokter[0]->nip : '' }}
                                                {{-- {{isset($res[0]['dokterPemeriksaNapza']['label']) ? $res[0]['dokterPemeriksaNapza']['label'] : ''}} --}}
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            {{-- {{dd($res[0])}} --}}

        </td>
    </tr>
@endsection
