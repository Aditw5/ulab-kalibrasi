@extends('template.layout-lab')
@section('title', 'Cetak Hasil Antigen')
@section('page-style')
    <style>
        .table-parent {
            border: 2px solid #353434;
            border-collapse: collapse;
        }

        .none-top {
            border-top: none;
        }

        .table-bordered tr {}

        .table-bottom tr td {
            width: 33%;
        }

        h1 {
            font-size: 20px;
        }

        .h2 {
            font-size: 18px;
        }

        .h3 {
            font-size: 16px;
        }

        .h4 {
            font-size: 14px;
        }

        .h5 {
            font-size: 12px;
        }

        .h6 {
            font-size: 8px;
        }

        .bold {
            font-weight: 600
        }

        .italic {
            font-style: italic;
            font-size: 12px;
        }

        * {
            font-family: initial
        }

        tr {
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        td {
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .left {
            justify-content: left !important;
            align-items: left !important;
            text-align: left !important;
        }

        span {
            font-size: 10px;
        }

        .border {
            border: 1px solid #353434;
            border-radius: 20px;
        }

        .border tr {
            vertical-align: middle;
        }

        .border tr td {
            justify-content: left;
            text-align: left;
            text-align: left;
            padding: 5px 10px;
        }

        .border-a tbody tr {
            border-top: 1px solid #353434;
        }

        .border-b {
            border-bottom: 1px solid #353434;
        }
    </style>
@endsection

@section('content')
    <tr>
        <td style="padding-top:2px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien">
                <tbody>
                    <tr>
                        <th colspan="3">
                            <span class="h3 bold">HASIL PEMERIKSAAN LABORATORIUM MIKROBIOLOGI KLINIK</span>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="italic">Clinical Microbiologi Laboratory Test Result</span>
                        </td>
                    </tr>
                    <tr>
                        <th colspan="3">
                            <span class="h3 bold">{{ $data->judulmetode }}</span>
                        </th>
                    </tr>
                    <tr>
                        <td colspan="3">
                            <span class="italic">{{ $data->methodtitle }}</span>
                        </td>
                    </tr>
                    @if($data->namametodeperiksa == "Insulated Isothermal")
                    <tr>
                        <td colspan="3">
                            <span class="bold" style="font-size: 10pt;"> No. {{ $data->nopemerikasaan }}
                            </span>
                        </td>
                    </tr>
                    @else
                    <tr>
                        @php
                            function intToRoman($num) {
                                $romans = [
                                    'M'  => 1000,
                                    'CM' => 900,
                                    'D'  => 500,
                                    'CD' => 400,
                                    'C'  => 100,
                                    'XC' => 90,
                                    'L'  => 50,
                                    'XL' => 40,
                                    'X'  => 10,
                                    'IX' => 9,
                                    'V'  => 5,
                                    'IV' => 4,
                                    'I'  => 1,
                                ];

                                $result = '';

                                foreach ($romans as $roman => $value) {
                                    $matches = intval($num / $value);
                                    $result .= str_repeat($roman, $matches);
                                    $num = $num % $value;
                                }

                                return $result;
                            }
                        @endphp
                        <td colspan="3">
                            <span class="bold" style="font-size: 10pt;"> No. {{ $data->nopemerikasaan }}/
                                LMK/{{ intToRoman(date('m')) }}/ {{ date('Y') }}
                            </span>
                        </td>
                    </tr>
                    @endif
                        <tr style="margin-top:20px;">
                        <td colspan="3">
                            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="border">
                                <tr>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Nama Pasien</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Patient Name</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->namapasien }}</span>
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Jenis Kelamin</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Sex</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->jeniskelamin }}</span><br>
                                        <span
                                            class="italic h6" style="font-size: 8pt;">{{ $data->jeniskelamin == 'Laki-Laki' ? 'Male' : 'Female' }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Tanggal Lahir</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Date of Birth</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->tgllahir }}</span>
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Pengirim</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Unit</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%" style="font-size: 10pt;">{{ $data->ruanganpengorder }}</td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">No. KTP/NIK</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Resident ID Number</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->noidentitas }}</span>
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Jenis Sampel</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">sample Type</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->jenisspesimen }}</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">{{ $data->typespeciment }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">No. Passport</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Passport .NO</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->paspor ?? '-' }}</span>
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Tgl Terima Sample</span><br>
                                        <span class="italic h6" style="font-size: 7.7pt;">Sample Receiving Date</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 8.5pt;">
                                            {{ $data->tglterimasampel }}
                                        </span><br>
                                        <span
                                            class="italic h6" style="font-size: 7.5pt;">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->tglterimasampel)->format('M jS, Y g:i a') }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Alamat</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Address</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">{{ $data->alamat }}</span>
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 10pt;">Tgl Selesai Hasil</span><br>
                                        <span class="italic h6" style="font-size: 8pt;">Result/Print Out Date</span>
                                    </td>
                                    <td width="2%">
                                        :
                                    </td>
                                    <td width="24%">
                                        <span style="font-size: 8.5pt;">{{ $data->tglselesaisampel }}</span><br>
                                        <span
                                            class="italic h6" style="font-size: 7.5pt;">{{ \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->tglselesaisampel)->format('M jS, Y g:i a') }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3" height="20px"></td>
                    </tr>
                    {{-- <tr>
                        <td colspan="3" height="20px"></td>
                    </tr>
                    <tr>
                        <td width="9%" class="left">
                            <span class="h6" style="font-size: 8.5pt"></span>
                        </td>
                        <td width="90%" class="left">
                            <span class="h6" style="font-size: 8.5pt"></span>
                        </td>
                        <td width="90%" class="left">
                            <span class="h6" style="font-size: 8.5pt"></span>
                        </td>

                    </tr> --}}
                </tbody>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td colspan="3">
                        <table cellspacing="0" cellpadding="0" border="0" width="100%"class="border-a" >
                            <tbody>
                                @if($data->namametodeperiksa == "Insulated Isothermal")
                                    <tr>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">Kode Sampel</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Sample Code</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">Jenis Pemeriksaan</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Type of Examination</span>
                                        </td>
                                        <td width="25%">
                                            <span class="h6" style="font-size: 10pt;">Hasil</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Result</span>
                                        </td>
                                        <td width="25%" style="font-size: 10pt;">
                                            <span class="h6" style="font-size: 10pt;">Nilai Rujukan</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Reference</span><br>
                                            <span style="font-size: 8pt;">{{ __("Rasio <= 1.15") }}</span>
                                        </td>
                                        <td width="25%" style="font-size: 10pt;">
                                            <span class="h6" style="font-size: 10pt;">Keterangan</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Other Infromation/Explanation</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td width="25%">
                                            <span style="font-size: 10pt;">{{ $data->kodesampel }}</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 8.3pt;">{{ $data->namametodeperiksa }}</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">{{ $data->methodname }}</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 8.3ptpt;">{{ $data->hasil == true ? 'Positif' : 'Negatif' }}</span><br>
                                            <span
                                                class="h6 italic" style="font-size: 8pt;">{{ $data->hasil == true ? 'Positive' : 'Negative' }}</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">{{ $data->nilaiRujukan }}</span><br>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">Pockit <sup>TM</sup></span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">( Deteksi gen RdRP )</span>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td width="15%">
                                            <span style="font-size: 10pt;">Kode Sampel</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Sample Code</span>
                                        </td>
                                        <td width="35%">
                                            <span style="font-size: 10pt;">Jenis Pemeriksaan</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Type of Examination</span>
                                        </td>
                                        <td width="10%">
                                            <span class="h6" style="font-size: 10pt;">Hasil</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Result</span>
                                        </td>
                                        <td style="font-size: 10pt;">
                                            <span class="h6" style="font-size: 10pt;">Keterangan</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">Other Infromation/Explanation</span>
                                        </td>
                                    </tr>
                                    <tr class="border-b">
                                        <td width="25%">
                                            <span style="font-size: 10pt;">{{ $data->kodesampel }}</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">{{ $data->namametodeperiksa }}</span><br>
                                            <span class="h6 italic" style="font-size: 8pt;">{{ $data->methodname }}</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">{{ $data->hasil == true ? 'Positif' : 'Negatif' }}</span><br>
                                            <span
                                                class="h6 italic" style="font-size: 8pt;">{{ $data->hasil == true ? 'Positive' : 'Negative' }}</span>
                                        </td>
                                        <td width="25%">
                                            <span style="font-size: 10pt;">{{ $data->keterangan == '' ? $data->nilaiRujukan: $data->keterangan }}</span><br>
                                            {{-- <span class="h6 italic" style="font-size: 8pt;">{{ $data->keterangan }}</span> --}}
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="30%" class="left">
                        <span class="h6" style="font-size: 8.5pt">Diperiksa oleh / Checked by</span>
                    </td>
                    <td width="85%" class="left">
                        <span class="h6" style="font-size: 8.5pt">: {{ $data->petugaspemeriksa }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="30%" class="left">
                        <span class="h6" style="font-size: 8.5pt">Diverifikasi oleh / Verified by</span>
                    </td>
                    <td width="85%" class="left">
                        <span class="h6" style="font-size: 8.5pt">: {{ $data->petugasverifikator }}</span>
                    </td>
                </tr>
                {{-- <tr>
                    <td width="30%" class="left">
                        <span class="h6" style="font-size: 8.5pt">Disetujui oleh / Approval by</span>
                    </td>
                    <td width="85%" class="left">
                        <span class="h6" style="font-size: 8.5pt">: {{ $data->petugasapproval }}</span>
                    </td>
                </tr> --}}
            </table>
        </td>
    </tr>
    <tr>
        <td>
            <table width="100%">
                <tr>
                    <td width="60%">
                        <br>
                        <img src="data:image/jpeg;base64,{{ $qrcode }}" width="120px" border="0">
                    </td>
                    <td width="80%">
                        <span class="h5 bold">Dokter Penanggung Jawab</span><br>
                        <span class="italic">Doctor In Charge</span><br>
                        <img style="margin: 10px" src="data:image/jpeg;base64,{{ $qrcodedok }}" width="60px"
                            border="0"><br>
                        <span class="h5 bold">{{ $data->petugasapproval }}</span><br>
                        <span style="width: 100%"></span>
                        <span class="h5 bold">SIP. {{ $data->sip }}</span><br>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td height="20px"></td>
    </tr>
    <tr style="border-bottom: 2px solid #000; width: 100%">
        <td>
        </td>
    </tr>
    <tr>
        <td class="left">
            <span class="bold h5">Untuk informasi Lebih lanjut dapat menghubungi Hotline Laboratorium Mikrobiologi :
                +6282111034003</span>
        </td>
    </tr>
    <tr>
        <td class="left">
            <span class="italic h6">For Further Information, please contact Microbiology Laboratory Hotline Number :
                +6282111034003</span>
        </td>
    </tr>
@endsection
