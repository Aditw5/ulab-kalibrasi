 @extends('template.layout')
 @section('title', 'Laporan Kunjungan Berdasarkan Status Pasien')
 @section('page-style')

 @endsection
 @section('content')
 <style>
    .table{
        width: 100%;
        margin-top: 20px;
    }

    .table tr,
    .table th,
    .table td,
    .table
     {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 10pt;
    }
 </style>
 @php
    $totallakibaru = 0;
    $totallakilama = 0;
    $totalperempuanbaru = 0;
    $totalperempuanlama = 0;
    $grandtotal = 0;
@endphp
 <tr>
     <td style="padding-top:10px">
         <table cellspacing="0" cellpadding="0" border="0" width="100%">
             <tr>
                 <td align="center">
                     <font style="font-size: 14pt;font-weight: 600;text-decoration: underline;" color="#000000">
                        Laporan Kunjungan Berdasarkan Status Pasien
                     </font> <br>
                     <font style="font-size: 10pt;font-weight: 600;" color="#000000">
                        Periode : {{ $periode }}
                     </font>
                 </td>
             </tr>
         </table>
     </td>
 </tr>
 <tr>
    <td>
        <table class="table">
            <thead>
                <tr>
                    <th rowspan="2">No</th>
                    <th rowspan="2">Nama Poliklinik</th>
                    <th colspan="2">Baru</th>
                    <th colspan="2">Lama</th>
                    <th rowspan="2">Jumlah</th>
                </tr>
                <tr>
                    <th>Laki-Laki</th>
                    <th>Perempuan</th>
                    <th>Laki-Laki</th>
                    <th>Perempuan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dataReport as $index => $item)
                    <tr>
                        <td>{{$index+1}}</td>
                        <td>{{$item->namaruangan}}</td>
                        <td align="right">{{$item->lakibaru}}</td>
                        <td align="right">{{$item->perempuanbaru}}</td>
                        <td align="right">{{$item->lakilama}}</td>
                        <td align="right">{{$item->perempuanlama}}</td>
                        <td align="right">{{$item->total}}</td>
                    </tr>
                    @php
                        $totallakibaru += $item->lakibaru;
                        $totallakilama += $item->lakilama;
                        $totalperempuanbaru += $item->perempuanbaru;
                        $totalperempuanlama += $item->perempuanlama;
                        $grandtotal += $item->total;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <td align="right">{{$totallakibaru}}</td>
                    <td align="right">{{$totalperempuanbaru}}</td>
                    <td align="right">{{$totallakilama}}</td>
                    <td align="right">{{$totalperempuanlama}}</td>
                    <td align="right">{{$grandtotal}}</td>
                </tr>
            </tfoot>
        </table>
    </td>
 </tr>
 @endsection
