@extends('template.layout-expertise')
@section('title', 'Expertise Radiologi')
@section('page-style')
    <style>
        * {
            font-family: Tahoma, Geneva, Verdana, sans-serif;
        }

        .infopasien tbody tr td {
            vertical-align: top;
            padding: 2px;
            font-size: 10pt;
        }

        .infopasien tbody tr td:first-child,
        .infopasien tbody tr td:nth-child(4) {
            font-weight: bold;
        }

        @page {
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')

    @foreach ($raw as $raws)
        <tr>
            <td width="100%">
                <table width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td style="text-align:center" width="10%" rowspan="3">
                            @if ((isset($res['pdf']) && $res['pdf']) || (isset($res['storage']) && $res['storage']))
                                <img src="{{ 'img/logo-rs.png' }}" width="50px" border="0">
                            @else
                                <img src="{{ asset('img/logo-rs.png') }}" width="50px" border="0">
                            @endif
                        </td>
                        <td class="text-center">
                            <span
                                style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000; margin-left: -30px">
                                RUMAH SAKIT UMUM GUNUNG JATI
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <span
                                style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000;margin-left: -30px"">
                                INSTALASI RADIOLOGI
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-center">
                            <span
                                style="font-size: 10pt;font-weight: 600;letter-spacing: 0px;color:#000000;margin-left: -30px"">
                                JL. Kesambi No. 56 Kota Cirebon 45134 Telp. 0231 - 205330, Ext 1072
                            </span>
                        </td>
                    </tr>
                    {{-- <tr>
                            <td class="text-center">
                                <span style="font-size: 14pt;font-weight: 600;color:#000000">PEMERINTAH KOTA BANDUNG
                                </span>
                            </td>
                        </tr> --}}
                    {{-- <tr>
                            <td class="text-center" border="1px solid black">
                                <span style="font-size: 11pt;font-weight: 600;letter-spacing: 0px;color:#000000">
                                    INSTALASI RADIOLOGI
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center" border="1px solid black">
                                <span style="font-size: 11pt;color:#000000">
                                    {!! $profile->alamatlengkap !!}
                                </span>
                            </td>
                        </tr> --}}
                    {{-- <tr>
                            <td class="text-center">
                                <span style="font-size: 12pt; color:#000000">
                                    Email : <a href="#"> {!! $profile->alamatemail !!} </a>
                                    Website : <a href="#"> {!! $profile->website !!} </a>
                                    Whatsapp : <a href="#"> {!! $profile->whatsapp !!} </a>
                                </span>
                            </td>
                        </tr> --}}
                </table>
                <hr class="baris2">
            </td>
        </tr>
        <tr>
            <td style="padding-top:2px;">
                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien">
                    <tbody>
                        <tr>
                            <td width="17%;">NoCm</td>
                            <td width="1%;">:</td>
                            <td width="34%;">{{ $raws->nocm }}</td>
                            <td width="15%;">No Expertise</td>
                            <td width="1%;">:</td>
                            <td width="34%;"> {{ $raws->nofoto }}</td>
                        </tr>
                        <tr>
                            <td>No Radiologi</td>
                            <td>:</td>
                            <td>{{ $raws->noregistrasi }} </td>
                            <td>Dokter Pengirim</td>
                            <td>:</td>
                            <td>{{ $raws->perujuk ?? '' }} </td>

                        </tr>
                        <tr>
                            <td>Nama Pasien</td>
                            <td>:</td>
                            <td>{{ $raws->namapasien }} </td>
                            <td>Ruangan Pengirim</td>
                            <td>:</td>
                            {{-- <td>{{ App\Traits\Valet::getDateIndo($raws->tanggal).' '.substr($raws->tanggal,10,9) }} </td> --}}
                            <td>{{ $raws->namaruangan }} </td>
                        </tr>
                        <tr>
                            <td>Tanggal Lahir / JK</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($raws->tgllahir)->format('d-m-Y') }} / {{ $raws->jeniskelamin }}
                            </td>
                            <td>Dokter Radiologi</td>
                            <td>:</td>
                            <td>{{ $raws->dokterrad }} </td>

                        </tr>
                        <tr>
                            <td>Tanggal Pemeriksaan</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($raws->tglverif)->format('d-m-Y') }}</td>
                            {{-- <td>Cetak</td>
                    <td>:</td>
                    <td>{{ App\Traits\Valet::getDateIndo(date('Y-m-d')).date(' H:i:s')   }}  </td> --}}

                        </tr>
                        {{-- <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td>{{  $raws->alamatlengkap }} </td>
                    <td>Dokter Perujuk</td>
                    <td>:</td>
                    <td>{{ $raws->perujuk  }}  </td>
                </tr> --}}
                    </tbody>
                </table>
                <hr>
                <table cellspacing="0" cellpadding="0" border="0" width="100%" class="infopasien">
                    <tbody>
                        <tr>
                            <td width="16%;">Jenis Pemeriksaan</td>
                            <td width="1%;">:</td>
                            <td width="29%;">{{ (string) $raws->namaproduk }}</td>
                            <td width="15%;">No Photo</td>
                            <td width="1%;">:</td>
                            <td width="19%;"> {{ $raws->nofoto }}</td>
                            <td width="10%;"><b>Klinis</b></td>
                            <td width="1%;">:</td>
                            <td width="19%;">{{ $raws->catatanklinis }}</td>
                        </tr>
                    </tbody>
                </table>

            </td>
        </tr>

        @php
            $exper = explode("\n", $raws->keterangan);
        @endphp

        @foreach ($exper as $index => $row)
            <tr>
                <td style="font-size: 16px">
                    @if ($row !== '')
                        {{ str_replace("\u{200B}", '', trim($row)) }}
                    @else
                        <div style="height: 20px;"></div>
                    @endif

                </td>
            </tr>
        @endforeach



        <tr style="page-break-after: always">
            <td>
                <table style="margin-top:5px;" width="100%">
                    <tr>
                        <td width="60%"></td>
                        <td>
                            <table>
                                <tr>
                                    <td></td>
                                    <td style="text-align: center">
                                        <span style="font-size: 9pt">Cirebon,
                                            {{ \Carbon\Carbon::parse($raws->tglexpertise)->format('d-m-Y') }}</span>
                                        <br>
                                        <span style="font-size: 9pt;">Pemeriksa</span>
                                    </td>
                                </tr>

                                <tr>
                                    <td height="100"></td>
                                    <td align="center">
                                        {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=75x75&data={{ $raws->dokterrad }}"><br> --}}
                                        <img src="data:image/png;base64, {!! $raws->ttde !!}"><br>
                                        {{-- @if (stripos(\Request::url(), 'localhost') !== false)
                                        @if (file_exists(public_path('img/radiologi/' . $raws->pgidrad . '.png')))
                                        <img src="{{ asset('img/radiologi/'.$raws->pgidrad.'.png') }}"
                                            style="max-width:200px;height:100px;margin-top: -40px;" border="0">
                                        @endif
                                    @else
                                        @if (file_exists(public_path('img/radiologi/' . $raws->pgidrad . '.png')))
                                        <img src="{{ asset('service/img/radiologi/'.$raws->pgidrad.'.png') }}"
                                            style="max-width:200px;height:100px;margin-top: -40px;" border="0">
                                        @endif
                                    @endif --}}

                                        <br>
                                        <span style="font-size: 9pt"><b>{{ '( ' . $raws->dokterrad . ' )' }}
                                            </b><br><i>{{ $raws->dokterradnosip != null ? $raws->dokterradnosip : '' }}
                                            </i></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    @endforeach
@endsection
