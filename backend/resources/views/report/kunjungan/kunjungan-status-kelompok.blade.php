 @extends('template.layout')
 @section('title', 'Laporan Kunjungan Berdasarkan Status Pasien')
 @section('page-style')

 @endsection
 @section('content')
 <style>
    table{
        width: 100%;
        border-collapse: collapse;
    }
    @page{
        margin: 20px 0;
    }

    .table tr,
    .table th,
    .table td{
        border: 1px solid black;
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
</tbody>
</table>
<div style="padding: 0 30px;">
    <table class="table">
        <thead>
            <tr>
                <th rowspan="3">Periode</th>
                <th rowspan="3">Nama Poliklinik</th>
                <th colspan="{{count($kelompok)*2+1}}">Jenis Pasien</th>
                <th colspan="5">Status Pasien</th>
            </tr>
            <tr>
                @foreach ($kelompok as $itemKel)
                <th colspan="2">{{$itemKel->kelompokpasien}}</th>
                @endforeach
                <th rowspan="2">Jumlah</th>
                <th colspan="2">Baru</th>
                <th colspan="2">Lama</th>
                <th rowspan="2">Jumlah</th>
            </tr>
            <tr>
                @foreach ($kelompok as $itemKel)
                    <th>L</th>
                    <th>P</th>
                @endforeach
                <th>L</th>
                <th>P</th>
                <th>L</th>
                <th>P</th>
            </tr>
        </thead>
        @php
            $itemsPerPage = 21;
            $dataChunks = array_chunk($dataReport, $itemsPerPage);
        @endphp
        @foreach ($dataChunks as $chunk)
        <tbody>
            @foreach ($chunk as $index => $item)
                <tr>
                    @if ($index == 0)
                        <td rowspan="{{count($chunk)}}">{{$periode}}</td>
                        @php
                            $periodeDisplayed = true;
                        @endphp
                    @endif
                    <td>{{$item->ruangan}}</td>
                    @foreach ($kelompok as $itemKel)
                        @php
                            $namakelompok = $itemKel->kelompokpasien;
                            $lakiProp = $namakelompok.'laki';
                            $perempuanProp = $namakelompok.'perempuan';
                        @endphp
                        <td align="right">{{$item->$lakiProp}}</td>
                        <td align="right">{{$item->$perempuanProp}}</td>
                        @php
                        if (!isset($totalKelompok[$namakelompok])) {
                            $totalKelompok[$namakelompok] = 0;
                        }

                        $totalKelompok[$namakelompok] += $item->$lakiProp + $item->$perempuanProp;
                    @endphp
                    @endforeach
                    <th align="center">{{$item->kelompokTotal}}</th>
                    <td align="right">{{$item->lakibaru}}</td>
                    <td align="right">{{$item->perempuanbaru}}</td>
                    <td align="right">{{$item->lakilama}}</td>
                    <td align="right">{{$item->perempuanlama}}</td>
                    <th align="center">{{$item->statusTotal}}</th>
                </tr>
                @php
                    $totallakibaru += $item->lakibaru;
                    $totallakilama += $item->lakilama;
                    $totalperempuanbaru += $item->perempuanbaru;
                    $totalperempuanlama += $item->perempuanlama;
                    $grandtotal += $item->statusTotal;
                @endphp
            @endforeach
        </tbody>
        @endforeach
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                @foreach ($totalKelompok as $kelompok => $total)
                    <th align="center" colspan="2">{{ $total }}</th>
                @endforeach
                <th align="center">{{$grandtotal}}</th>
                <th align="center">{{$totallakibaru}}</th>
                <th align="center">{{$totalperempuanbaru}}</th>
                <th align="center">{{$totallakilama}}</th>
                <th align="center">{{$totalperempuanlama}}</th>
                <th align="center">{{$grandtotal}}</th>
            </tr>
        </tfoot>
    </table>
</div>
 @endsection
