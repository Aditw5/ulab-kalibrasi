<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Surat Tagihan Perorangan</title>
</head>
<style>
    @page {
        margin: 0
    }

    body {
        margin: 0
    }

    .sheet {
        margin: 0;
        overflow: auto;
        position: relative;
        box-sizing: border-box;
        page-break-after: always;
    }

    /** Paper sizes **/
    body.A3 .sheet {
        width: 297mm;
        height: 419mm
    }

    body.A3.landscape .sheet {
        width: 420mm;
        height: 296mm
    }

    body.A4 .sheet {
        width: 210mm;
        height: 296mm
    }

    body.G4 .sheet {
        width: 210mm;
        height: 296mm
    }

    body.A4.landscape .sheet {
        width: 297mm;
        height: 209mm
    }

    body.A5 .sheet {
        width: 148mm;
        height: 209mm
    }

    body.A5.landscape .sheet {
        width: 210mm;
        height: 147mm
    }

    body.F4 .sheet {
        width: 210mm;
        height: 330mm
    }

    body.letter .sheet {
        width: 216mm;
        height: 279mm
    }

    body.letter.landscape .sheet {
        width: 280mm;
        height: 215mm
    }

    body.legal .sheet {
        width: 216mm;
        height: 356mm
    }

    body.legal.landscape .sheet {
        width: 357mm;
        height: 215mm
    }

    /** Padding area **/
    .sheet.padding-10mm {
        padding: 10mm
    }

    .sheet.padding-15mm {
        padding: 15mm
    }

    .sheet.padding-20mm {
        padding: 20mm
    }

    .sheet.padding-25mm {
        padding: 25mm
    }

    /** For screen preview **/
    @media screen {
        body {
            background: white
        }

        .sheet {
            background: white;
            box-shadow: 0 .5mm 2mm rgba(0.3, 0.3, 0.3, .3);
            margin: 5mm auto;
        }
    }

    /** Fix for Chrome issue #273306 **/
    @media print {
        body.A3.landscape {
            width: 420mm
        }

        body.A3,
        body.A4.landscape {
            width: 297mm
        }

        body.A4,
        body.A5.landscape {
            width: 210mm
        }

        body.A5 {
            width: 148mm
        }

        body.F4 {
            width: 210mm
        }

        body.letter,
        body.legal {
            width: 216mm
        }

        body.letter.landscape {
            width: 280mm
        }

        body.legal.landscape {
            width: 357mm
        }
    }

    body,
    td,
    th,
    span {
        font-family: Arial, Helvetica, sans-serif;
    }
</style>

<body style="padding:25px">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <thead style="display: none"></thead>
        <tbody>
            <tr>
                <td rowspan="5">
                    <img src="img/logo-kota.png" width="80px" border="0" style="margin-top:-20px">
                </td>
                <td class="text-center">
                    <span style="font-size: 11pt;font-weight: 600;letter-spacing: 1px;color:#000000"><b>
                            PEMERINTAH KOTA CIREBON
                        </b></span>
                </td>
                <td rowspan="5">
                    <div style="width: 80px;">
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 12pt;font-weight: 600;letter-spacing: 1px;color:#000000"><b>
                            DINAS KESEHATAN
                        </b></span>
                </td>
            </tr>
            <tr>
                <td class="text-center">
                    <span style="font-size: 16pt;font-weight: 600;letter-spacing: 2px;color:#000000"><b>
                            RUMAH SAKIT DAERAH GUNUNG JATI
                        </b></span>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 11pt;color:#000000">
                        Jalan Kesambi Nomor 56, Cirebon 45134
                    </span>
                </td>
            </tr>

            <tr>
                <td class="text-center">
                    <span style="font-size: 11pt;color:#000000">
                        Telepon. (0231) 206330 Faks. (0231) 203336 Email : <a href="#"> {!! $profile->alamatemail !!}
                        </a>
                    </span>
                </td>
            </tr>



        </tbody>
    </table>

    <hr class="baris1" style="margin-top:1px">
    <hr class="baris1" style="margin-top:-5px">

    {{-- {{ dd($res) }} --}}

    <table width="100%" style="margin-top: -19px;">
        <table width="100%" style="margin-top: -5px; margin-left: 430px">
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                <table width="100%" style="font-size: 15px;" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="left" style="max-width: 330px;">Kepada,</td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="max-width: 330px;">Yth.
                                            {{ $res['namabaru'] && $res['namabaru'] != '-' ? $res['namabaru'] : $res['identitas']->namapasien }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td align="left" style="max-width: 330px;">
                                            <span
                                                style="display: inline-block; word-wrap: break-word; max-width: 100%;">di
                                                {{ $res['alamatBaru'] && $res['alamatBaru'] != '-' ? $res['alamatBaru'] : $res['rekanan']->alamatlengkap }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <tbody>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0"
                        style="border-bottom:2px solid black;margin-top: -8px;">
                        <thead style="display: none"></thead>
                        <tbody>
                            <tr>
                                <td class="text-center">
                                    <span class="text-judul" style="font-size: 11pt">NOTA PENAGIHAN</span>
                                </td>
                            </tr>
                            <tr>
                                <td width="15%">
                                    <span class="text-normal"> Mohon dengan hormat agar rincian hutang di bawah ini
                                        dapat diselesaikan dengan segera
                                        Atas bantuannya kami ucapkan terima kasih</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr>
                <td>
                    <table width="100%" cellspacing="0" cellpadding="0" style="border-bottom:2px solid black;">
                        <thead style="display: none"></thead>
                        <tbody>
                            <tr style="font-size: 11pt;">
                                <td width="15%">
                                    <span class="text-normal">Nama Pasien</span>
                                </td>
                                <td width="45%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->namapasien . ' (' . $res['identitas']->jeniskelamin . ')' }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">
                                <td width="15%">
                                    <span class="text-normal">Reg.</span>
                                </td>
                                <td width="45%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->nocm }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">
                                <td width="15%">
                                    <span class="text-normal">Pekerjaan</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->pekerjaan }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">
                                <td width="15%">
                                    <span class="text-normal">Alamat</span>
                                </td>
                                <td width="50%">
                                    <span class="text-normal">:
                                        {{ $res['identitas']->alamat_pasien }}, {{ $res['identitas']->namakecamatan }},
                                        {{ $res['identitas']->namakotakabupaten }}</span>
                                </td>
                            </tr>
                            <tr style="font-size: 11pt;">
                                <td width="15%">
                                    <span class="text-normal">Dirawat/Berobat TGL</span>
                                </td>
                                <td width="35%">
                                    <span class="text-normal">:
                                        {{ date_format(date_create($res['identitas']->tglregistrasi), 'd/m/Y H:i') }} -
                                        {{ date_format(date_create($res['identitas']->tglpulang), 'd/m/Y H:i') }}</span>
                                </td>
                            </tr>
            </tr>
        </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0">
        <thead style="display: none"></thead>
        <tbody>
            <tr>
                <td class="text-center">
                    <span class="text-judul" style="font-size: 11pt">PERINCIAN BIAYA</span>
                </td>
            </tr>
        </tbody>
    </table>

    <table width="100%" cellspacing="0" cellpadding="1" border="0">
        <thead style="display: none"></thead>
        <tbody style="font-size: 11pt;">
            @php
                $nomor = 1;
                $totaltagihan = 0;
                $totaldiskon = 0;
                $jumlahbill = 0;
                $totaldiklaim = 0;
            @endphp
            @foreach ($res['billing'] as $ruangan)
                <tr style="background: #d6d4d4;">
                    <td>
                        <span class="text-normal bold">
                            <b> {{ strtoupper($ruangan[0]->namaruangan) }}</b>
                        </span>
                    </td>
                </tr>
                @foreach ($ruangan->groupBy('jenisproduk') as $item)
                    @php
                        $total = 0;
                        $diskon = 0;
                    @endphp
                    @foreach ($item as $data)
                        @php
                            $nomor = $nomor + 1;
                            $total = $total + $data->total;
                            $diskon = $diskon + $data->diskon;
                        @endphp
                    @endforeach
                    <table width="100%" cellspacing="0" cellpadding="0">
                        <thead style="display: none"></thead>
                        <tbody style="font-size: 11pt;">
                            <tr>
                                <td width="60%" class="text-left">
                                    <span class="text-normal">
                                        {{ strtoupper($item[0]->jenisproduk) }}
                                    </span>
                                </td>
                                <td width="25%">
                                    <span class="text-biasa">:</span>
                                </td>
                                <td class="text-right">
                                    <span class="text" style="text-align: right;">
                                        {{ number_format(ceil($total), 0, '.', ',') }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                        </thead>
                    </table>
                    @php
                        $totaltagihan = $totaltagihan + $total;
                        $totaldiskon = $totaldiskon + $diskon;
                        $jumlahbill = $totaltagihan - $totaldiskon;
                    @endphp
                @endforeach
            @endforeach
        </tbody>
    </table>



    <table width="100%" cellspacing="0" cellpadding="0" style="border-top:2px solid black;">
        <thead style="display: none"></thead>
        <tbody style="font-size: 11pt;">
            <tr>
                <td width="50%" class="text-left">
                    <span class="text-biasa">JUMLAH TOTAL PERINCIAN</span>
                </td>
                <td class="text-center">
                    <span class="text-biasa">:</span>
                </td>
                <td class="text-right">
                    <span class="text-biasa">
                        {{ number_format(ceil($res['total']), 0, '.', ',') }}</span>
                </td>
            </tr>
            <tr>
                @if ($res['identitas']->jenis_piutang == 'Piutang Pasien')
                    <td width="20%" class="text-left">
                        <span class="text-biasa">DIBAYAR DIRI SENDIRI</span>
                    </td>
                @else
                    @if ($res['rekanan']->namarekanan === 'BPJS KESEHATAN' || $res['rekanan']->namarekanan === 'BPJS KETENAGAKERJAAN')
                        <td width="20%" class="text-left">
                            <span
                                class="text-biasa">{{ $res['identitas']->kelompokpasien == 'Umum' ? 'PIUTANG' : 'DIBAYAR BPJS (INACGs)' }}</span>
                        </td>
                    @endif
                    @if ($res['rekanan']->namarekanan !== 'BPJS KESEHATAN' && $res['rekanan']->namarekanan !== 'BPJS KETENAGAKERJAAN')
                        <td width="20%" class="text-left">
                            <span class="text-biasa">DIBAYAR {{ $res['rekanan']->namarekanan }}</span>
                        </td>
                    @endif
                @endif
                <td class="text-center">
                    <span class="text-biasa">:</span>
                </td>
                @if ($res['iurbayar'] != 0)
                    <td class="text-right">
                        <span class="text-biasa bold">
                            {{ number_format($res['totalklaimiur'] + ($res['total'] - $res['totalklaimiur'] - $res['dibayar']), 0, '.', ',') }}</span>
                    </td>
                @elseif ($res['iurbayar'] == 0)
                    <td class="text-right">
                        <span class="text-biasa bold">
                            {{ number_format($res['klaim'], 0, '.', ',') }}</span>
                    </td>
                @endif
            </tr>
            @if ($res['identitas']->jenis_piutang === 'Piutang Multi')
                <tr>
                    <td width="20%" class="text-left">
                        <span class="text-biasa">DIBAYAR PENJAMIN LAIN</span>
                    </td>
                    <td class="text-center">
                        <span class="text-biasa">:</span>
                    </td>
                    <td class="text-right">
                        <span class="text-biasa">
                            {{ number_format($res['klaim_bpjs'], 0, '.', ',') }}</span>
                    </td>
                </tr>
            @endif
            <tr>
                <td width="20%" class="text-left">
                    <span class="text-biasa">DEPOSIT - UANG MUKA</span>
                </td>
                <td class="text-center">
                    <span class="text-biasa">:</span>
                </td>
                <td class="text-right">
                    <span class="text-biasa">
                        {{ number_format($res['deposit'], 0, '.', ',') }}</span>
                </td>
            </tr>
            <tr>
                <td width="20%" class="text-left">
                    <span class="text-biasa">SUDAH DIBAYAR PASIEN</span>
                </td>
                <td class="text-center">
                    <span class="text-biasa">:</span>
                </td>
                <td class="text-right">
                    <span class="text-biasa">
                        {{ number_format($res['sudahBayar'] + $res['dibayar'], 0, '.', ',') }}</span>
                </td>
            </tr>
            <tr>
                <td width="20%" class="text-left">
                    <span class="text-biasa">JUMLAH TOTAL TAGIHAN</span>
                </td>
                <td class="text-center">
                    <span class="text-biasa">:</span>
                </td>
                <td class="text-right">
                    <span class="text-biasa bold">
                        {{ number_format(ceil($res['sisa']), 0, '.', ',') }}</span>
                </td>
            </tr>
            @if ($res['iurbayar'] != 0)
                <tr>
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
        </tbody>
    </table>
    <table width="100%" cellspacing="0" cellpadding="0">
        <tbody style="font-size: 11pt">
            <tr>
                <td>
                    <span class="text-biasa">TERBILANG :
                </td>
            </tr>
            <tr>
                <td>
                    <span class="text-biasa"><i>#
                            {{ strtoupper(App\Traits\Valet::static_terbilang($res['sisa'])) }}
                            #</i>
                </td>
            </tr>
        </tbody>
    </table>
    <table style="font-size: 11pt">
        @if ($res['ismultipenjamin'] == true)
            <tr>
                <td>
                    <span class="text-biasa">Multi Penjamin :
                </td>
            </tr>
            <tr>
                <td style="padding-top:20px">
                    <table width="100%" cellspacing="0" cellpadding="0" border="1">
                        <tbody>
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
                        </tbody>
                    </table>
                </td>
            </tr>
        @endif
    </table>
    <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px">
        <tbody style="font-size: 11pt">
            <tr>
                <td width="50%">
                </td>
                <td width="50%" class="text-center">
                    <span class="text-biasa">{!! $profile->namakota !!},
                        {{ \Carbon\Carbon::parse(date('Y-m-d H:i'))->isoFormat('DD MMMM Y') }}</span>
                </td>
            </tr>
            <tr>
                <td width="50%">
                </td>
                <td width="50%" class="text-center">
                    <span class="text-biasa">An. Direktur Wadir Umum dan Keuangan</span>
                    <span class="text-biasa"> Kepala Bagian Keuangan</span>
                </td>
            </tr>
            <tr>
                <td width="85%">
                </td>
                <td align="center">
                    <img src="data:image/png;base64, {!! $res['ttdKepala'] !!}">
                    {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                </td>
            </tr>
            <tr>
                <td width="85%">
                </td>
                <td height="10" valign="bottom" height="100" width="15%" class="text-center"
                    style="border-bottom:2px solid black;">
                    <span class="text-biasa">{{ $res['kepala']->namalengkap }}</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="text-biasa">
                        NB :
                    </span>
                </td>
                <td width="25%" class="text-center">
                    <span class="text-biasa">NIP. 19670627 201001 2 001</span>
                </td>
            </tr>
            <tr>
                <td>
                    <span class="text-biasa">Apabila ada kesalahan penyampaian penagihan piutang
                        Sebelumnya kami mohon maaf dan segera dikonfirmasi ke bagian kasir/bagian piutang
                    </span>
                </td>
                <td width="25%" class="text-center">
                </td>
            </tr>
        <tbody>
    </table>
</body>

</html>
