@extends('template.layout')
@section('title', 'Expertise Lab')
@section('page-style')
<style>
    .table-parent{
        border: 2px solid #353434;
        border-collapse: collapse;
    }
    .none-top{
        border-top: none;
    }
    .table-bordered tr{

    }
    .table-bottom tr td {
        width: 33%;
    }
</style>
@endsection

@section('content')
<tr>
    <td>
        <div class="table-container">
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent">
                <tr>
                    <td style="padding-left: 10px;padding-top: 10px;padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >No. Sediaan</font>
                    </td>
                    <td style="padding-top: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->nomorpa}}</font>
                    </td>
                    <td style="padding-top: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Nama</font>
                    </td>
                    <td style="padding-right: 10px;padding-top: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->namapasien}}</font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-bottom:7px;padding-left: 10px;">
                        <font style="font-size: 9pt" color="#000000" >Ruangan Asal</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:  {{$data->namaruangan}}</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 8pt;" color="#000000" >T.Lahir/Umur</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{ \Carbon\Carbon::parse($data->tgllahir)->format('d-m-Y')}} /  {{ \Carbon\Carbon::parse($data->tgllahir)->age }}</font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Kelas</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:  {{$data->namakelas}}</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Alamat</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >
                            @php
                                $alamat = $data->alamatlengkap;
                                $alamatBaru = wordwrap($alamat, 50, "<br/>", true);
                                echo ": " . $alamatBaru;
                            @endphp
                        </font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px; padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Jenis</font>
                    </td>
                    <td  style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:  {{$data->kelompokpasien}} </font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >NO RM</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->nocm}} </font>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Tanggal Diterima</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:   {{ \Carbon\Carbon::parse($data->tanggalpelayanan)->format('d-m-Y') }}</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Dokter PJ</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:
                            NURBAITI, dr, Sp.PA. M.Kes.
                        </font>
                    </td>
                </tr>
                <tr >
                    <td style="padding-left: 10px;">
                        <font style="font-size: 9pt" color="#000000" >Tanggal Pemeriksa</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:
                            {{ \Carbon\Carbon::parse($data->tanggalpemeriksaan)->format('d-m-Y') }}
                        </font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Dokter Pengirim</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->penanggungjawab}}</font>
                    </td>
                </tr>
                <tr >
                    <td style="padding-left: 10px;">

                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >

                        </font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >Dokter Baca</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >: {{$data->dokterpengirim}}</font>
                    </td>
                </tr>
                @if($data->diagnosaklinik)
                    <tr>
                        <td style="padding-left: 10px;">
                            <font style="font-size: 9pt" color="#000000" >Diagnosa</font>
                        </td>
                        <td style="padding-bottom:7px;" colspan="3">
                            <font style="font-size: 9pt" color="#000000" >: {{$data->diagnosaklinik}}</font>
                        </td>
                    </tr>
                @endif
                @if($data->jenis == 'pa')
                <tr>
                    <td colspan="4">
                        <table width="100%">
                            <tr>
                                @if($data->diagnosapb)
                                    <th align="left">Diagnosis PB</th>
                                @endif
                                @if($data->keteranganpb)
                                    <th align="left">Keterangan PB</th>
                                @endif
                                @if($data->topografi)
                                <th align="left">Topografi</th>
                                @endif
                                @if($data->morfologi)
                                <th align="left">Morfologi</th>
                                @endif
                            </tr>
                            <tr>
                                <td>{{$data->diagnosapb}}</td>
                                <td>{{$data->keteranganpb}}</td>
                                <td>{{$data->topografi}}</td>
                                <td>{{$data->morfologi}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                @endif
            </table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent none-top table-bordered">
                <tr>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-left: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >No</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Jenis Pemeriksaan</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Hasil</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Nilai Rujukan</font></td>
                    <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-right: none" class="none-top"><font style="font-size: 10pt;font-weight:700" color="#000000" >Satuan</font></td>
                </tr>
                <tr>
                    <td colspan="5" height="15" style="text-align:left;border-bottom: 1px solid #888080"></td>
                </tr>
                {{-- <tr>
                    <th colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px;">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            HEMATOLOGI
                        </font>
                    </th>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px;">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            MORFOLOGI APUS DARAH PUTIH
                        </font>
                    </td>
                </tr> --}}
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:400" color="#000000" >
                            makroskopik : <br> {!! nl2br(e($data->makroskopik)) !!}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:400" color="#000000" >
                            mikroskopik : <br>{!! nl2br(e($data->mikroskopik)) !!}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:600" color="#000000" >
                            kesimpulan :  <br>{!! nl2br(e($data->kesimpulan)) !!}
                        </font>
                    </td>
                </tr>
                <tr>
                    <td colspan="5" style="padding-left:10px ;text-align:left;border-bottom: 1px solid #888080;padding-top: 5px;padding-bottom: 5px">
                        <font style="font-size: 9pt;font-weight:400" color="#000000" >
                            anjuran :  <br>{!! nl2br(e($data->anjuran)) !!}
                        </font>
                    </td>
                </tr>
            </table>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-bottom">
                <tr>
                    <td colspan="3" height="40"></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td style="text-align: center">
                        <font style="font-size: 9pt;font-weight:500" color="#000000" >
                            <!-- Cirebon, {{ \Carbon\Carbon::parse($data->tanggalpelayanan)->translatedFormat('d F Y')}} -->
                            Cirebon, {{ \Carbon\Carbon::parse($data->tanggalpemeriksaan)->translatedFormat('d F Y')}}
                        </font>
                    </td>
                </tr>
                {{-- <tr>
                    <td colspan="3" height="40"></td>
                </tr> --}}
                <tr>
                    <td></td>
                    <td style="text-align: center;">

                    </td>
                    <td style="text-align: center; padding-top: 20px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{$data->dokterpengirim}}">
                        <br>
                        <br>
                        <font style="font-size: 9pt;font-weight:600;" color="#000000" >
                            <u>{{$data->dokterpengirim}}</u>
                        </font>
                        <br>
                        <font style="font-size: 9pt;font-weight:600;" color="#000000" >
                            No. SIP : {{$data->nosip ?? '-'}}
                        </font>
                    </td>
                </tr>
            </table>
        </div>
    </td>
</tr>

@endsection

