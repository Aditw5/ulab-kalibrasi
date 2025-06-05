@extends('template.layout')
@section('title', 'Billing')
@section('page-style')

@endsection
@section('content')
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                    <tr>
                        <td class="text-center">
                            <span class="text-judul">REKAP BIAYA PELAYANAN</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td colspan="4" style="padding-bottom: 20px;">
                            <span class="text-normal">
                                Print by {{ $res['user'] }} &nbsp;&nbsp;&nbsp;
                                {{ date('d/m/Y H:i') }}
                            </span>
                        </td>
                    </tr>
                    <tr>

                        <td width="15%">
                            <span class="text-normal">No. Registrasi</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->noregistrasi }}</span>
                        </td>

                        <td width="15%">
                            <span class="text-normal">Unit</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namadepartemen }}</span>
                        </td>
                    </tr>
                    <tr>

                        <td width="15%">
                            <span class="text-normal">No. RM</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">: {{ $res['identitas']->nocm }}
                            </span>
                        </td>


                        <td width="15%">
                            <span class="text-normal">Ruang</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namaruangan }}</span>
                        </td>
                    </tr>
                    <tr>

                        <td width="15%">
                            <span class="text-normal">Nama Pasien</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namapasien . ' (' . $res['identitas']->jeniskelamin . ')' }}</span>
                        </td>

                        <td width="15%">
                            <span class="text-normal">Tgl Masuk</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y H:i') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%">
                            <span class="text-normal">Tgl Pulang</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y H:i') }}</span>
                        </td>


                        <td width="15%">
                            <span class="text-normal">DPJP</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namalengkap }}</span>
                        </td>
                    </tr>
                    <tr>

                        <td width="15%">
                            <span class="text-normal">No SEP</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->nosep }}</span>
                        </td>


                        <td width="15%">
                            <span class="text-normal">Penjamin</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namarekanan }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="15%">
                            <span class="text-normal">Tipe</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->kelompokpasien }}</span>
                        </td>
                    </tr>
                
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px">
                <table width="100%" cellspacing="0" cellpadding="1" border="0">
                    <tr>
                        <th class="th-class text-left">
                            <span class="text-normal">No</span>
                        </th>
                        <th class="th-class  text-center">
                            <span class="text-normal">Keterangan</span>
                        </th>
                        <th class="th-class text-right">
                            <span class="text-normal">Qty</span>
                        </th>
                        <th class="th-class  text-right">
                            <span class="text-normal">Sub Total</span>
                        </th>
                    </tr>
                    @php
                        $nomor = 1;
                        $totaltagihan = 0;
                        $totaldiskon = 0;
                        $jumlahbill = 0;
                        $totaldiklaim = 0;
                    @endphp
                    @foreach ($res['billing'] as $ruangan)
                        <tr style="background: #d6d4d4;">
                            <td colspan="9">
                                <span class="text-normal bold">
                                    <b>   {{ strtoupper($ruangan[0]->departemen_group) }}</b>
                                </span>
                            </td>
                        </tr>
                        @php
                        $n = 1;
                        $totalSUB = 0;
                         @endphp
                        @foreach ($ruangan->groupBy('ruang_group') as $k => $item)
                           
                            @php
                                $total = 0;
                                $diskon = 0;
                                $jml = 0;
                            @endphp
                            @foreach ($item as $data)
                                @php
                                $nomor = $nomor + 1;
                                $total = $total + $data->total;
                                $jml = $jml + $data->jumlah;
                                $diskon = $diskon + $data->diskon;
                                @endphp
                             @endforeach
                            <tr>
                                <td class="text-top text-left">
                                    <span class="text">{{ $n }}</span>
                                </td>
                              
                                <td class="text-top text-left">
                                    <span class="text">{{ $item[0]->ruang_group }}</span>
                                </td>
                                <td class="text-top text-right">
                                    <span class="text">{{ $jml}}</span>
                                </td>
                              
                                <td class="text-top text-right">
                                    <span class="text"> {{ number_format($total, 2, '.', ',') }}</span>
                                </td>
                            </tr>
                             {{-- <tr  style="font-style:italic">
                                <td colspan="9">
                                    <span class="text-normal">
                                        <b>     JENIS: {{ strtoupper($item[0]->namaruangan) }}</b>
                                    </span>
                                </td>
                            </tr> --}}
                               
{{--            
                            <tr>
                                <td class="text-right" colspan="9">
                                    <span class="text">
                                        {{ number_format($total, 2, '.', ',') }}
                                    </span>
                                </td>
                            </tr> --}}
                            @php
                           
                                $n = $n+1;
                                $totalSUB = $totalSUB + $total;
                                $totaltagihan = $totaltagihan + $total;
                                $totaldiskon = $totaldiskon + $diskon;
                                $jumlahbill = $totaltagihan - $totaldiskon;
                            @endphp
                        @endforeach
                        <tr>
                            <td class="text-right" colspan="9">
                                <span class="text">
                                    {{ number_format($totalSUB, 2, '.', ',') }}
                                </span>
                            </td>
                        </tr> 
                    @endforeach
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:10px">
                <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">TOTAL TAGIHAN</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format( $res['total'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">DEPOSIT - UANG MUKA</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format( $res['deposit'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    {{-- <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">DISKON</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa ">
                                {{ number_format($totaldiskon, 2, '.', ',') }}</span>
                        </td>
                    </tr> --}}
                  
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">TOTAL BAYAR </span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format($res['dibayar'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">TOTAL DIKLAIM</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format( $res['klaim'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">SISA HARUS BAYAR</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format($res['sisa'], 2, '.', ',') }}</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style="padding-top:20px">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <span class="text-biasa">TERBILANG :
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="text-biasa"><i>#
                                    {{ strtoupper(App\Traits\Valet::static_terbilang($jumlahbill)) }} #</i>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        @if ($res['ismultipenjamin'] == true)
            <tr>
                <td>
                    <span class="text-biasa">Multi Penjamin :
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tr class="garisatasbawah">
                            <th class="text-center"><span class="text-biasa">Penjamin</span>
                            </th>
                            <th class="text-center"><span class="text-biasa">Jumlah</span>
                            </th>
                        </tr>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($res['multipenjamin'] as $item)
                            @php
                                $total = $total + $item->totalppenjamin;
                            @endphp
                            <tr>
                                <td class="text-left"><span class="text-biasa">{{ $item->namarekanan }}</span>
                                </td>
                                <td class="text-right"><span
                                        class="text-biasa">{{ number_format($item->totalppenjamin, 2, '.', ',') }}</span>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="text-right">
                                <span class="text">
                                    <b>JUMLAH</b>
                                </span>
                            </td>
                            <td class="text-right">
                                <span class="text">
                                    {{ number_format($total, 2, '.', ',') }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        @endif
        <tr>
            <td style="padding-top:20px">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td width="50%" class="text-center">
                            <span class="text-biasa" class="text-center"></span>
                        </td>
                        <td width="50%" class="text-center">
                            <span class="text-biasa">{!! $profile->namakota !!},
                                &nbsp;&nbsp;{{ date('d/m/Y H:i') }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td width="50%" class="text-center"><span class="text-biasa">Penanggung Biaya</span>
                        </td>
                        <td width="50%" class="text-center"><span class="text-biasa">Kasir</span>
                        </td>
                    </tr>
                    <tr>
                        <td height="80" valign="bottom" height="100" width="25%" class="text-center">
                            <span class="text-biasa">
                                {{ str_replace('( Laki-laki )', '', str_replace('( Perempuan )', '', $res['identitas']->namapasien)) }}
                            </span>
                        </td>
                        <td height="80" valign="bottom" height="100"width="25%" class="text-center">
                            <span class="text-biasa">-</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    
@endsection
