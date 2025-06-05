<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
         Order Resep</title>
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <style>
        :root {
            font-family: 'Tahoma', 'sans-serif';
        }
        font{
            font-family: 'Tahoma', 'sans-serif';
        }
        @font-face {
            font-family: 'Tahoma','sans-serif';
        }
        .table-bordered tr td{
            border: 1px solid #444444;
        }
        .label-strong {
            text-align: center;
            font-size: 13.4pt;
        }

        .label-normal {
            font-weight:400;
            text-align:left;
            padding-bottom: 3px;
            font-size: 13.4pt;
        }

        .label-right {
            text-align: right;
            font-size: 13.4pt;
            font-weight: normal;
        }
        .label-left {
            text-align: left;
            font-size: 13.4pt;
            font-weight: normal;
        }
        body {
            font-family: Tahoma, sans-serif;
        }
    </style>
</head>

    @php
        $profile = App\Http\Controllers\Controller::static_profile();
        $index = 0;
    @endphp

@foreach ($dataReport as $dataReports)
    @php
        $index++;
    @endphp
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <th>
                <img src="{{ 'img/logo-rs.png' }}" width="70px">
            </th>
            <th width="90%">
                <table width="100%"  style="left:-2rem;position:relative">
                    <tr>
                        <td class="label-strong" style="text-align:center">
                            <font>{{ strtoupper($profile->namapemerintahan) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-strong" style="text-align:center">
                            <font>{{ strtoupper($profile->namalengkap) }}</font>
                        </td>
                    </tr>
                    <tr>
                        <td class="label-normal" style="text-align:center">
                            <font>Jalan Kesambi No. 56 Telp.{{ $profile->fixedphone }} Fax.{{ $profile->faksimile }} Kode Pos {{ $profile->kodepos }}</font>
                        </td>
                    </tr>
                </table>
            </th>
        </tr>
    </table>
    <hr style="margin: 0px">
    <table width="100%" cellspacing="0" cellpadding="0"  border="0">
            <tr>
                <td colspan="2" height="10"></td>
            </tr>
            <tr>
                <td style="text-align: center;" colspan="8">
                    <div style="display: inline-block; border-bottom: 2px solid black; padding-bottom: 5px;">
                        <font style="font-size: 14pt; font-weight: 600;">PERMINTAAN RESEP</font>
                    </div>
                </td>
            </tr>
            <tr>
                <td  colspan="2">
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <tr>
                            <td colspan="6" style="padding-bottom: 20px;">
                                <span style="font-size: 12px;">
                                    Print by {{ $user }} &nbsp;&nbsp;&nbsp;
                                    {{ date('d/m/Y H:i') }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    No. Resep
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->noresep }}
                                </span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Tanggal Resep
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->tglorder }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Nama Pasien
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->namapasien }}
                                </span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Rekam Medik
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->nocm }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Tanggal Lahir
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->tgllahir }}
                                </span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Poli/Ruangan
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->namaruangan }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Umur
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->umur }}
                                </span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Nama Dokter
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->namalengkap }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Jenis Kelamin
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->jeniskelamin }}
                                </span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Tipe Pasien
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->kelompokpasien }} &nbsp; ({{ $dataReport['data']->namarekanan }})
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Alergi
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
        
                                </span>
                            </td>
                            <td width="20%">
                                <span style="font-size: 12px;">
        
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
        
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    TB / BB
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->tinggibadan }} Cm / {{ $dataReport['data']->beratbadan }} Kg
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="20%">
                                <span style="font-size: 12px;">
                                    Diagnosa
                                </span>
                            </td>
                            <td width="5%">
                                <span style="font-size: 12px;">
                                    :
                                </span>
                            </td>
                            <td width="25%">
                                <span style="font-size: 12px;">
                                    {{ $dataReport['data']->kddiagnosa }} -- {{ $dataReport['data']->namadiagnosa }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tr style="border-bottom-style: solid;border-top-style: solid;border:1px ;background: #d6d4d4;">

            <td class="solid">
                <b>R Ke</b>
            </td>
            <td class="solid">
                <b>Nama Obat</b>
            </td>
            <td class="solid">
                <b>Dosis</b>
            </td>
            <td class="solid">
                <b>Jumlah</b>
            </td>
            <td class="solid">
                <b>Aturan Pakai</b>
            </td>


        </tr>
        @php
            $nomor = 1;
            $totaltagihan = 0;
            $totaldiskon = 0;
            $jumlahbill = 0;
            $totaldiklaim = 0;
        @endphp
        @foreach ($dataDetails['detail']->groupBy('jenisracikan') as $item)
            <tr>
                <td colspan="6">
                    <span style="font-size: 13px;font-weight: bold;">{{ $item[0]->jenisracikan }}
                    </span>
                </td>
            </tr>
            @foreach ($item as $dat)
                <tr>
                    <td>
                        R.{{ $dat->rke }}
                    </td>
                    <td>
                        {{ $dat->namaproduk }}
                    </td>
                    @php
                        if ($dat->jenisracikan == "Racikan") {
                            $dosis = $dat->dosis;
                            $jumlahMakan = ($dat->jumalah / $dat->dosis) * $dat->kekuatan;
                        } else {
                            $dosis =1;
                            $jumlahMakan =$dat->jumlah;
                        }
                    @endphp
                    <td>
                       {{$jumlahMakan }}/{{ $dosis  }}/{{$dat->kekuatan}}
                    </td>
                    <td>
                        {{ $dat->jumlah }}
                    </td>
                    <td>
                        {{ $dat->aturanpakai }}
                    </td>



                </tr>
                @php
                    $nomor = $nomor + 1;
                @endphp
            @endforeach
        @endforeach
    </table>

    
    <table width="100%" cellspacing="0" cellpadding="1" style="{{$index != count($dataReport) && count($dataReport) > 0 ? 'page-break-after: always':''}}">
        <tr>
            <td width="60%"></td>
            <td>
                <table>
                    <tr>
                        <td></td>
                        <td style="text-align: center">
                            <span style="font-size: 9pt">Cirebon,
                                {{-- {{ $dataReport['raw']->tglorder }} --}}
                                {{ \Carbon\Carbon::parse($dataReport['data']->tglorder)->format('d-m-Y')}}</span>
                            <br>
                            <span style="font-size: 9pt;">Dokter Pengorder</span>
                        </td>
                    </tr>

                    <tr>
                        <td height="100"></td>
                        <td align="center">
                            <img src="data:image/png;base64, {!!$dataReport['data']->ttde !!}"><br>
                            <br>
                            <span style="font-size: 9pt"><b>{{$dataReport['data']->namalengkap}}
                                </b><br><i>{{$dataReport['data']->nosip != null ? '( '.$dataReport['data']->nosip.' )' : ''}} </i></span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

@endforeach
</body>
</html>
