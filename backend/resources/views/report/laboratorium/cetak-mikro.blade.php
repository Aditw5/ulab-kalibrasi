@extends('template.layout-mikro')
@section('title', 'Cetak Hasil Mikrobiolgi')
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
            font-style: italic
        }

        * {
            font-family: initial
        }

        tr {
            justify-content: center;
            align-items: center;
            padding: 0;
            /* text-align: center; */
        }

        td {
            justify-content: center;
            align-items: center;
            padding: 0;
            font-size: 12pt;
            /* text-align: center; */
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
        .border{
            border: 1px solid black;
        }
    </style>
@endsection

@section('content')
    <tr>
        <td style="padding-top:2px;">
            <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien">
                <tbody>
                    <tr>
                        <td></td>
                        <td></td>
                        <td width="30%" class="border">Tanggal Masuk RS: {{$data->tanggalmasukrs}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-transform: uppercase"><b>kultur lab mikrobiologi rsd Gunung Jati</b></td>
                        <td>DPJP: {{$data->dpjp}}</td>
                    </tr>
                    <tr class="border" style="vertical-align: top;">
                        <td class="border">
                            Kode Spesimen <br>
                            {{$data->kodespesimen}}
                        </td>
                        <td>
                            <table cellpadding="0" cellspacing="0">
                                <tr style="padding: 0">
                                    <td style="padding: 0">Nama</td>
                                    <td style="padding: 0">:</td>
                                    <td style="padding: 0">{{$data->namapasien}}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0">Usia</td>
                                    <td style="padding: 0">:</td>
                                    <td style="padding: 0">{{ \Carbon\Carbon::parse($data->tgllahir)->age }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0">Jenis Kelamin</td>
                                    <td style="padding: 0">:</td>
                                    <td style="padding: 0">{{ $data->jeniskelamin }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0">No. CM</td>
                                    <td style="padding: 0">:</td>
                                    <td style="padding: 0">{{ $data->nocm }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0">Ruangan</td>
                                    <td style="padding: 0">:</td>
                                    <td style="padding: 0">{{ $data->namaruangan }}</td>
                                </tr>
                            </table>
                        </td>
                        <td class="border">
                            <table cellpadding="0" cellspacing="">
                                <tr>
                                    <td>Diagnosa Awal</td>
                                    <td>:</td>
                                    {{-- <td>{{$data->diagnosa}}</td> --}}
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Diagnosa Akhir</td>
                                    <td>:</td>
                                    {{-- <td>{{$data->diagnosa}}</td> --}}
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr style="vertical-align: top">
                        <td colspan="2" class="border">Spesimen (Tipe/Asal/Jumlah):</td>
                        <td class="border">Tanggal & Waktu Penerimaan: {{$data->tglterimasampel}}</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    {{-- {{dd($data)}} --}}
    <tr>
        <td style="padding-top: 20px">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr>
                    <td colspan="4">Pemeriksaan Mikroskopis ({{$data->jenisspesimen}})</td>
                    <td width="30%">Asal: ({{$data->asalspesimen}})</td>
                </tr>
                <tr class="border" style="vertical-align: top">
                    <td class="border">PMN: {{$data->pmn}}</td>
                    <td class="border">GPC: {{$data->gpc}}</td>
                    <td class="border">GPR: {{$data->gpr}}</td>
                    <td class="border">GNDC: {{$data->gndc}}</td>
                    <td rowspan="3">Note: {{$data->note}}</td>
                </tr>
                <tr class="border">
                    <td class="border">PMN: {{$data->sec}}</td>
                    <td class="border">GPC: {{$data->gnr}}</td>
                    <td class="border">GPR: {{$data->gncb}}</td>
                    <td class="border">GNDC: {{$data->ycphh}}</td>
                </tr>
                <tr class="border">
                    <td class="border" colspan="4">Pemeriksaan: {{$data->pemeriksaan}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 20px">
            <table cellspacing="0" cellpadding="0" width="100%">
                <tr style="vertical-align: top">
                    <td colspan="3" rowspan="6">Pemeriksaan Penunjang: {{$data->pemeriksaanpenunjang}}</td>
                    <td width="7.5%" class="border">Bas</td>
                    <td width="7.5%" class="border">Eos</td>
                    <td width="7.5%" class="border">Bat</td>
                    <td width="7.5%" class="border">Seg</td>
                    <td width="7.5%" class="border">Limf</td>
                    <td width="7.5%" class="border">Sun</td>
                </tr>
                <tr>
                    <td class="border">{{$data->bas}}</td>
                    <td class="border">{{$data->eos}}</td>
                    <td class="border">{{$data->bat}}</td>
                    <td class="border">{{$data->seg}}</td>
                    <td class="border">{{$data->limf}}</td>
                    <td class="border">{{$data->sun}}</td>
                </tr>
                <tr>
                    <td colspan="6" class="border">Antibiotik yang diberikan: {{$data->antibiotik}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="margin-top: 20px">
            Pemeriksaan Kultur:
            <table cellspacing="0" cellpadding="0" width="100%" border="1">
                <tr class="border">
                    <td class="border">Tanggal</td>
                    <td class="border">Pemeriksa</td>
                    <td class="border">Observasi Hasil dan Tindak Lanjut</td>
                </tr>
                <tr class="border">
                    <td class="border">{{$data->tglkultur}}</td>
                    <td class="border">{{$data->namapemeriksakultur}}</td>
                    <td class="border">{{$data->observasikultur}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="margin-top: 20px">
            Hasil Akhir Pelaporan Kultur:
            <table cellspacing="0" cellpadding="0" width="100%" border="1">
                <tr class="border" style="vertical-align: top;">
                    <td class="border" style="height: 100px">{{$data->hasilakhirkultur}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="margin-top: 20px">
            Hasil Uji Kepekaan dan Ekspertise:
            <table cellspacing="0" cellpadding="0" width="100%" border="1">
                <tr class="border" style="vertical-align: top">
                    <td class="border" style="height: 100px">{{$data->hasilujikepekaan}}</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td style="padding-top: 20px">
            <table width="100%">
                <tr>
                    <td></td>
                    <td width="30%">Cirebon, {{ \Carbon\Carbon::parse($data->tglkeluarhasil)->format('d-m-Y') }}</td>
                </tr>
            </table>
        </td>
    </tr>
@endsection
