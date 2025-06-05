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
                            <span class="text-judul">RINCIAN BIAYA PELAYANAN</span>
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

                        {{-- <td width="15%">
                            <span class="text-normal">Kelas</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namakelas }}</span>
                        </td> --}}
                    </tr>
                    <tr>

                        {{-- <td width="15%">
                            <span class="text-normal">Tgl Masuk</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y H:i') }}</span>
                        </td> --}}
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
                            <span class="text-normal">Tipe</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->kelompokpasien }}</span>
                        </td>
                    </tr>
                    <tr>

                        {{-- <td width="15%">
                            <span class="text-normal">No SEP</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->nosep }}</span>
                        </td> --}}


                        <td width="15%">
                            <span class="text-normal">Penjamin</span>
                        </td>
                        <td width="35%">
                            <span class="text-normal">:
                                {{ $res['identitas']->namarekanan }}</span>
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
                            <span class="text-normal-1">No</span>
                        </th>
                        <th class="th-class  text-center">
                            <span class="text-normal-1">Tanggal</span>
                        </th>
                        <th class="th-class text-left">
                            <span class="text-normal-1">Deskripsi</span>
                        </th>
                        {{-- <th class="th-class text-left">
                            <span class="text-normal-1">Kelas</span>
                        </th> --}}
                        <th class="th-class text-left">
                            <span class="text-normal-1">Dokter</span>
                        </th>
                        <th class="th-class  text-center">
                            <span class="text-normal-1">Qty</span>
                        </th>
                        <th class="th-class  text-center">
                            <span class="text-normal-1">Tarif</span>
                        </th>
                        <th class="th-class  text-center">
                            <span class="text-normal-1">Diskon</span>
                        </th>
                        <th class="th-class  text-center">
                            <span class="text-normal-1">Jasa</span>
                        </th>
                        <th class="th-class  text-right">
                            <span class="text-normal-1">Sub Total</span>
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
                            <td colspan="10">
                                <span class="text-normal-1 bold">
                                    <b>   {{ strtoupper($ruangan[0]->namaruangan) }}</b>
                                </span>
                            </td>

                        </tr>
                        @foreach ($ruangan->groupBy('jenisproduk') as $item)
                            <tr  style="font-style:italic">
                                <td colspan="10">
                                    <span class="text-normal-1">
                                        <b>     JENIS: {{ strtoupper($item[0]->jenisproduk) }}</b>
                                    </span>
                                </td>
                            </tr>
                            @php
                                $total = 0;
                                $diskon = 0;
                            @endphp
                            @foreach ($item as $data)
                                <tr>
                                    <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $nomor }}</span>
                                    </td>
                                    <td class="text-top text-center">
                                        <span class="text-normal-1">
                                            {{ date_format(date_create($data->tglpelayanan), 'd/m/Y') }}</span>
                                    </td>
                                    <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $data->namaproduk }}</span>
                                    </td>
                                    {{-- <td class="text-top text-left">
                                        <span class="text-normal-1">{{ $data->namakelas }}</span>
                                    </td> --}}
                                    @if ($data->penulisresep === null)
                                        <td class="text-top text-left">
                                            <span class="text-normal-1">{{ $data->dokter }}</span>
                                        </td>
                                    @else
                                        <td class="text-top text-left">
                                            <span class="text-normal-1">{{ $data->penulisresep }} </span>
                                        </td>
                                    @endif
                                    <td class="text-top text-center">
                                        <span class="text-normal-1">{{ $data->jumlah }}</span>
                                    </td>
                                    <td class="text-top text-right">
                                        <span class="text-normal-1"> {{ number_format($data->hargasatuan, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="text-top text-center">
                                        <span class="text-normal-1">{{ number_format($data->diskon, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="text-top text-center">
                                        <span class="text-normal-1">{{ number_format($data->jasa, 0, '.', ',') }}</span>
                                    </td>
                                    <td class="text-top text-right">
                                        <span class="text-normal-1"> {{ number_format($data->total, 0, '.', ',') }}</span>
                                    </td>
                                </tr>
                                @php
                                    $nomor = $nomor + 1;
                                    $total = $total + $data->total;
                                    $diskon = $diskon + $data->diskon;
                                @endphp
                            @endforeach
                            <tr>
                                <td class="text-right" colspan="10">
                                    <span class="text-normal-1">
                                        {{ number_format($total, 0, '.', ',') }}
                                    </span>
                                </td>
                            </tr>
                            @php
                                $totaltagihan = $totaltagihan + $total;
                                $totaldiskon = $totaldiskon + $diskon;
                                $jumlahbill = $totaltagihan - $totaldiskon;
                            @endphp
                        @endforeach
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
                                {{ number_format( $res['total'] , 0, '.', ',') }}</span>
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
                                {{ number_format( $res['deposit'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @if($res['pengembalian'] > 0)
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">PENGEMBALIAN DEPOSIT</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format( $res['pengembalian'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @endif
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
                                {{ number_format($totaldiskon, 0, '.', ',') }}</span>
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
                                {{ number_format($res['dibayar'], 0, '.', ',') }}</span>
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
                        @if($res['iurbayar'] != 0)
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format($res['totalklaimiur'], 0, '.', ',') }}</span>
                        </td>
                         @elseif ($res['iurbayar'] == 0) 
                        <td class="text-right">
                            <span class="text-biasa">
                                {{ number_format( $res['klaim'], 0, '.', ',') }}</span>
                        </td>
                        @endif
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
                                {{ number_format($res['sisa'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @if($res['iurbayar'] != 0)
                    <tr>
                        <td width="50%"></td>
                        <td width="20%" class="text-left">
                            <span class="text-biasa">IUR BAYAR</span>
                        </td>
                        <td class="text-center">
                            <span class="text-biasa">:</span>
                        </td>
                        <td class="text-right">
                            <span class="text-biasa bold">
                                {{ number_format($res['iurbayar'], 0, '.', ',') }}</span>
                        </td>
                    </tr>
                    @endif
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
                                    {{ strtoupper(App\Traits\Valet::static_terbilang( $res['total'])) }} #</i>
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
                                        class="text-biasa">{{ number_format($item->totalppenjamin, 0, '.', ',') }}</span>
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
                                    {{ number_format($total, 0, '.', ',') }}
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
