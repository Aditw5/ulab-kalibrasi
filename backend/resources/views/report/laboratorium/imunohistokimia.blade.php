@extends('template.layout')
@section('title', 'Imunohistokimia Lab')
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
                        <font style="font-size: 9pt" color="#000000" >: {{$data->nomorih}}</font>
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
                        <font style="font-size: 9pt" color="#000000" >:   {{ \Carbon\Carbon::parse($data->tanggalditerima)->format('d-m-Y') }}</font>
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
                        <font style="font-size: 9pt" color="#000000" >Tanggal Dijawab</font>
                    </td>
                    <td style="padding-bottom:7px;">
                        <font style="font-size: 9pt" color="#000000" >:
                            {{ \Carbon\Carbon::parse($data->tanggaldijawab)->format('d-m-Y') }}
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
            </table>

            <table cellspacing="0" cellpadding="0" border="none" width="100%" style="table-layout: fixed; margin-left: 2px; margin-top:10px; font-size: 10pt">
                <p style="padding-left: 10px;">
                    <font style="font-size: 9pt" color="#000000" >
                        Telah dilakukan pemeriksaan imunohistokimia LCA, CK, ER, PR, Her2Neu, dan Ki 67 pada sediaan no. {{$data->nomorih}} dengan hasil sebagai berikut:
                    </font>
                </p>
            </table>     

            <table cellspacing="0" cellpadding="0" border="none" width="100%" style="table-layout: fixed; margin-left: 2px; margin-top:10px; font-size: 10pt">
                <tr>
                    <td colspan="4" style="padding-left: 10px;">
                        <strong><font style="font-size: 9pt" color="#000000" >Hasil Imunohistokimia:</font></strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >LCA</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >: {{$data->lca}}</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >CK</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >: {{$data->ck}}</font></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >ER</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >: {{$data->er}}</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >PR</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >: {{$data->pr}}</font></td>
                </tr>
                <tr>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >Her2Neu</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >: {{$data->herneu}}</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >Ki 67</font></td>
                    <td style="padding-left: 10px;"><font style="font-size: 9pt" color="#000000" >: {{$data->ki67}}</font></td>
                </tr>
                <br>
                <tr>
                    <td colspan="4" style="padding-left: 10px;">
                        <strong><font style="font-size: 9pt" color="#000000" >Kesimpulan:</font></strong> {{$data->kesimpulan}}
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
                            Cirebon, {{ \Carbon\Carbon::parse($data->tanggaldijawab)->translatedFormat('d F Y')}}
                        </font>
                    </td>
                </tr>
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

