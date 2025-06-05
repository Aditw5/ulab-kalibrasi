@extends('template.layout-lab-pa')
@section('title', 'Cetak Hasil Lab')
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

        @page {
            margin: 30px 0;
        }
    </style>
@endsection
@section('content')
    @php
        // $profile = App\Http\Controllers\Controller::static_profile();
        $index = 0;
    @endphp

    @foreach ($dataReport['details'] as $order)
    @php
        $index++;
        $no_lab = '';
        $no_order = '';
        $dok_jaga = '';
        $dok_order = '';
        $tgl_daftar = '';
        $tgl_hasil = '';
        $catatan_klinis = '';
        $ruangan_asal = '';
        $analis = '';
        $ttePemeriksa = '';
        $tteDokter = '';
        foreach ($order as $value) {
            foreach ($value as $item) {
                if ($item->tgl_hasil != '') {
                    $no_lab = $item->no_lab;
                    $no_order = $item->noorder;
                    $dok_jaga = $item->dok_jaga;
                    $dok_order = $item->dok_order;
                    $tgl_daftar = $item->tgl_daftar;
                    $tgl_hasil = $item->tgl_hasil;
                    $catatan_klinis = $item->catatanklinis;
                    $ruangan_asal = $item->ruanganasal;
                    $analis = $item->user_validasi;
                    $ttePemeriksa = $item->ttdePemeriksa;
                    $tteDokter = $item->ttdeDokter;
                    break;
                }
            }
        }
    @endphp
        @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
            <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:0 30px !important;">
            @else
                <table cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0" style="padding:0 30px !important"
                    width="{{ $pageWidth }}">
        @endif
        {{-- {{dd($profile)}} --}}
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" border="0">
                        <tr>
                            <td rowspan="5">
                                <p class="text-right">
                                    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                        <img src="{{ 'img/logo-kota.png' }}" width="80px" border="0">
                                    @else
                                        {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
                                        <img src="{{ asset('img/logo-kota.png') }}" width="80px" border="0">
                                        {{-- @else
                                    <img src="{{ asset('service/img/logo-rs.png') }}" width="80px" border="0">
                                @endif --}}
                                    @endif
                                </p>
                            </td>
                            <td class="text-center">
                                <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! strtoupper($profile->namapemerintahan) !!}
                                </span>
                            </td>
                            <td rowspan="5">
                                <p class="text-right">
                                    @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                        <img src="{{ 'img/logo-rs.png' }}" width="80px" border="0">
                                    @else
                                        {{-- @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), '127.0.0.1') !== false) --}}
                                        <img src="{{ asset('img/logo-rs.png') }}" width="80px" border="0">
                                        {{-- @else
                                    <img src="{{ asset('service/img/logo-rs.png') }}" width="80px" border="0">
                                @endif --}}
                                    @endif
                                </p>
                            </td>
                        </tr>
                        {{-- <tr>
                    <td class="text-center">
                        <span style="font-size: 14pt;font-weight: 600;color:#000000">PEMERINTAH KOTA BANDUNG
                        </span>
                    </td>
                </tr> --}}
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! strtoupper($profile->namalengkap) !!}

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    {!! strtoupper('unit pelayanan instalasi laboratorium terintegrasi') !!}

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center">
                                <span style="font-size: 10pt;color:#000000">
                                    {!! $profile->alamatlengkap !!}

                                    Telp.{{ $profile->fixedphone . 'Fax. ' . $profile->faksimile }}
                                </span>
                            </td>
                        </tr>
                    </table>
                    <hr class="baris1" style="margin-top:-20px">
                    {{-- <hr class="baris2"> --}}
                </td>
            </tr>
        </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent">
            <tr>
                <td style="padding-left: 10px;padding-top: 10px;padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">No Lab </font>
                </td>
                <td style="padding-top: 10px; padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $no_lab ?? '-' }}/{{ $no_order ?? '-' }}</font>
                </td>
                <td style="padding-top: 10px; padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Nama</font>
                </td>
                <td style="padding-right: 10px;padding-top: 10px; padding-bottom:7px;">
                    <font style="font-size: 9pt; font-weight: bold" color="#000000">:
                        {{ $dataReport['header']->namapasien ?? '' }}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-bottom:7px;padding-left: 10px;">
                    <font style="font-size: 9pt" color="#000000">Ruangan Asal</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $ruangan_asal ?? '' }}</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Tempat Lahir / Umur</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">:
                        {{ $dataReport['header']->tempatlahir ?? '' }},{{ $dataReport['header']->tglkelahiran ?? '' }} /
                        {{ \Carbon\Carbon::parse($dataReport['header']->tglkelahiran)->age }}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 10px; padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Kelas</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $dataReport['header']->namakelas }}</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Alamat</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">
                        @php
                            $alamat = $dataReport['header']->alamatlengkap;
                            $alamatBaru = wordwrap($alamat, 50, '<br/>', true);
                            echo ': ' . $alamatBaru;
                        @endphp
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 10px; padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Jenis</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $dataReport['header']->kelompokpasien }}</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">NO RM</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $dataReport['header']->nocm }}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 10px;">
                    <font style="font-size: 9pt" color="#000000">Tanggal Periksa</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $tgl_daftar ?? '-' }}</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Penanggung Jawab</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $dok_jaga ?? '-' }}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-left: 10px;padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Waktu Validasi</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $tgl_hasil ?? '-' }}</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">Dokter Pengirim</font>
                </td>
                <td style="padding-bottom:7px;">
                    <font style="font-size: 9pt" color="#000000">: {{ $dok_order ?? '-' }}</font>
                </td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td style="padding-bottom: 40px">
                    <font style="font-size: 9pt" color="#000000">Diagnosa</font>
                </td>
                <td style="padding-bottom: 40px">
                    <font style="font-size: 9pt" color="#000000">: {{ $catatan_klinis ?? '-' }}</font>
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" class="table-parent table-bordered">
            <thead>
                <tr>
                    <th style="text-align:center;border: 2px solid #3b3b3b;  width:25%;" class="none-top">
                        <font style="font-size: 10pt;font-weight:700;" color="#000000">Jenis Pemeriksaan</font>
                    </th>
                    <th style="text-align:center;border: 2px solid #3b3b3b;  width:50%;" class="none-top">
                        <font style="font-size: 10pt;font-weight:700;" color="#000000">Hasil</font>
                    </th>
                    <th style="text-align:center;border: 2px solid #3b3b3b;  width:15%;" class="none-top">
                        <font style="font-size: 10pt;font-weight:700;" color="#000000">Nilai Rujukan</font>
                    </th>
                    <th style="text-align:center;border: 2px solid #3b3b3b;border-right: none;" class="none-top">
                        <font style="font-size: 10pt;font-weight:700;" color="#000000">Satuan</font>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order as $key => $items)
                    <tr>
                        <td colspan="4" style="padding-left: 10px;font-weight: bold;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">
                            {{ $key }}
                        </td>
                    </tr>
                    @foreach ($items as $item)
                        <tr>
                            <td style="padding-left: 30px;font-weight: none;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">
                                {{ $item->detailpemeriksaan }}
                            </td>
                            <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">
                                <table style="width: 100%">
                                    <tr>
                                        <td style="width: 50%;text-align: center;{{ $item->flag !== '' ? 'font-weight: bold;' : '' }}">
                                            {{ $item->flag }}
                                        </td>
                                        <td style="text-align: left;{{ $item->flag !== '' ? 'font-weight: bold;' : '' }}">
                                            {{ $item->hasil }}
                                        </td>
                                    </tr>
                                </table>
                            </td>
                            <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">
                                {{ $item->nilaitext }}
                            </td>
                            <td style="text-align:center;border: 2px solid #3b3b3b;border-top: none;border-bottom: 1px solid #7c7a7a;">
                                {{ $item->satuanstandar }}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td>
                    <font style="font-size: 8pt;" color="#000000">Catatan</font>
                </td>
                <td>
                    <font style="font-size: 8pt;" color="#000000"> : </font>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    @php
                        $catatan;
                    @endphp
                    @foreach($order as $item)
                        @php
                        $catatan = $item[0]->catatan;
                        @endphp
                    @endforeach
                    <font style="font-size: 8pt;" color="#000000">{!! nl2br($catatan ?? '') !!}</font>
                </td>
            </tr>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" border="0" style="{{$index != count((array)$dataReport['details']) ? 'page-break-after: always':''}}">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <font style="font-size: 8pt;" color="#000000">Jenis Sample</font>
                            </td>
                            <td>
                                <font style="font-size: 8pt;" color="#000000"> : </font>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <font style="font-size: 8pt;" color="#000000">Terima Sample</font>
                            </td>
                            <td>
                                <font style="font-size: 8pt;" color="#000000">:
                                    {{ $dataReport['header']->tglregistrasi }}
                                </font>
                            </td>
                            <td>
                                <font style="font-size: 8pt;" color="#000000"></font>
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="text-align:center">
                </td>
                <td style="text-align:center">
                    <font style="font-size: 8pt;" color="#000000">
                        {{ $profile->namakota ?? 'Cirebon' }},{{ \Carbon\Carbon::parse($tgl_hasil)->format('d/m/Y') }}
                    </font>
                </td>
            </tr>
            <tr>
                <td colspan="3" height="30"></td>
            </tr>
            <tr>
                <td style="30%"></td>
                <td style="text-align:center">
                    <font style="font-size: 10pt;font-weight: 600" color="#000000">
                        <img src="data:image/png;base64, {!! $tteDokter !!}"><br>
                        <br>
                        <u>{{ $dok_jaga ?? '----------------' }}</u>
                        <br> <span style="font-weight: none">(Dokter Patalogi Klinis)</span>
                    </font>
                </td>
                <td style="text-align:center;">
                    <font style="font-size: 10pt;font-weight: 600" color="#000000">
                        <img src="data:image/png;base64, {!! $ttePemeriksa !!}"><br>
                        <br>
                        <u>{{ $analis ?? '----------------' }}</u>
                        <br> <span style="font-weight: none">Pemeriksa</span>
                    </font>
                </td>
            </tr>
            <tr>
                <td style="padding-bottom:30px;">
                    <font style="font-size: 8pt;font-weight: 600" color="#000000"></font>
                </td>
                <td style="padding-bottom:30px;"></td>
            </tr>
        </table>
    @endforeach
@endsection
