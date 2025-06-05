@extends('template.head-emr')
@section('title', 'EMR - Monitoring Transfusi Darah')
@section('about', 'Monitoring Transfusi Darah')

@push('style')

@endpush

@section('content')

<table width="100%" cellspacing="0">
       
    <tr>
        <td width="100%">
            <table width="100%" border="1" bordercolor="#000000" class="garis6">
                      
                <tr bgcolor="#f08080">
                        <th rowspan='2'>No</th>
                        <th rowspan='2'>Tanggal</th>
                        <th rowspan='2'>Jam</th>
                        <th rowspan='2'>Kantong Ke</th>
                        <th colspan='4'>TTV</th>       
                        <th rowspan='2'>Lokasi Insersi</th>    
                        <th rowspan='2'>Nama Perawat</th>
                        <th rowspan='2'>Paraf</th>
                        <th rowspan='2'>Reaksi Alergi</th>
                        <th rowspan='2'>Keterangan</th>      
                </tr>
                <tr bgcolor="#f08080">
                    <th>TD</th>
                    <th>HR</th>
                    <th>RR</th>
                    <th>S</th>
                </tr>
                @foreach($data['details'] as $item)
                <tr bgcolor="">
                    <td align="left">{{$item['no']}}</td>
                    <td align="left">
                        {{ isset($item['tgl']) ? date('d-m-Y', strtotime($item['tgl'])) : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['tgl']) ? date('H:m', strtotime($item['tgl'])) : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['kantong']) ? $item['kantong'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['tekananDarah']) ? $item['tekananDarah'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['nadi']) ? $item['nadi'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['pernapasan']) ? $item['pernapasan'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['suhu']) ? $item['suhu'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['lokasiInsersi']) ? $item['lokasiInsersi'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['dokterRawatBersama']) ? $item['dokterRawatBersama']['label'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['paraf']) ? $item['paraf'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['reaksiAlergi']) ? $item['reaksiAlergi'] : '-' }}
                    </td>
                    <td align="left">
                        {{ isset($item['keterangan']) ? $item['keterangan'] : '-' }}
                    </td>
                </tr>
                @endforeach
            </table>
        </td>
    </tr>
</table>

@endsection
