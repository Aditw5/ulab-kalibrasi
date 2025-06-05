<!DOCTYPE html>
<html lang="en">

<head>
    <title>Rekap Penerimaan Kasir</title>
</head>

<body>
    <style type="text/css">
        body {
            color: #333;
            font-family: Arial, Helvetica, sans-serif;
        }

        table {
            border-collapse: collapse;
            font-size: 12px;
            width: 100%;
        }

        p {
            font-size: 13px;
        }

        .custom-table thead {
            background-color: #e1e1e1;
        }

        .custom-table tr>th,
        .custom-table tr>td {
            border: 1px solid #ccc;
            box-shadow: none;
            padding: 5px;
        }

        .custom-table-no-border thead {
            background-color: #e1e1e1;
        }

        .custom-table-no-border tr>th,
        .custom-table-no-border tr>td {
            box-shadow: none;
            padding: 5px;
        }

        .text-center {
            text-align: center;
        }

        .top-table {
            margin-bottom: 10px;
        }

        .top-table tr>td {
            padding: 3px 10px;
        }
    </style>

    <div>
        <h4>

            {{ strtoupper($profile->namapemerintahan) }} <br>
            DINAS KESEHATAN, Cirebon- Telp. <br>
            {{$profile->namalengkap}}
        </h4>
    </div>

    <div style="text-align:center">
        <h3 style="padding: 10px 0; background-color: #ccc">DAFTAR PENYERAHAN BUKTI PENERIMAAN PASIEN</h3>
        <P style="margin: 0; padding: 0; font-size: 1em">Periode : {{ $tglAwal . ' - ' . $tglAkhir }}</P>
    </div>
    <div>Ruangan Kasir: <b>{{!empty($ruangan) ? $ruangan:'..............................'}}</b></div>

    <br>

    <table class="custom-table" style="width: 100%">
        <caption style="display: none"></caption>
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">NO BKM</th>
                <th scope="col">Nama Pasien</th>
                <th scope="col">NO CM</th>
                <th scope="col">Ruangan Terakhir</th>
                <th scope="col">Jenis Pasien</th>
                <th scope="col">Uang Muka</th>
                <th scope="col">Jumlah Bayar</th>
                <th scope="col">Hutang Penjamin</th>
                <th scope="col">Tanggungan RS</th>
                <th scope="col">Pembebasan</th>
                <th scope="col">Total Biaya</th>
                {{-- <th scope="col">Keterangan</th> --}}
                {{-- <th scope="col">Hutang Penjamin</th> --}}
                {{-- <th scope="col">Total Harus Bayar (Rp)</th> --}}
                {{-- <th  scope="col">Total Bayar (Rp)</th> --}}

                {{-- <th scope="col">Cara Bayar</th> --}}
                {{-- <th scope="col">Tunai (Rp)</th>
                <th scope="col">Non Tunai (Rp)</th> --}}
            </tr>
        </thead>
        <tbody>
            @php
                $i = 1;
                $temp = 0;
                $tempuangmuka = 0;
                $temptanggungan = 0;
                $temphutang = 0;
                $temppembebasan = 0;
                @endphp
            @foreach ($data->groupBy('kasir') as $item)
            {{-- <?php $kasir = $item[0]->kasir; ?>
                <tr  style="font-style:italic">
                    <td colspan="10">
                        <span class="text-normal-1">
                            <b>     KASIR: {{ strtoupper($item[0]->kasir) }}</b>
                        </span>
                    </td>
                </tr> --}}
                @foreach ($item as $d)
                <tr>
                    <?php $temp = $temp + $d->totaldibayar; ?>
                    <?php $tempuangmuka = $tempuangmuka + $d->totaldibayarbefore; ?>
                    <?php $temphutang = $temphutang + $d->totaldibayarbefore; ?>
                    <?php $temptanggungan = $temptanggungan + $d->totaldibayarbefore; ?>
                    <?php $temppembebasan = $temppembebasan + $d->totaldibayarbefore; ?>
                    <td style=" text-align: center">{{ $i++ }}</td>
                    <td style="text-align:center">{{ $d->nosbm }}</td>
                    <td>{{ $d->namapasien }}</td>
                    <td>{{ $d->nocm }}</td>
                    <td>{{ $d->namaruangan }}</td>
                    <td>{{ $d->kelompokpasien }}</td>
                    <td>Rp. {{ number_format($d->totaldibayarbefore) }}</td>
                    <td>Rp. {{ number_format($d->totaldibayar) }}</td>
                    <td>Rp. {{ number_format($d->totaldibayarbefore) }}</td>
                    <td>Rp. {{ number_format($d->totaldibayarbefore) }}</td>
                    <td>Rp. {{ number_format($d->totaldibayarbefore) }}</td>
                    <td>Rp. {{ number_format($d->totaldibayar) }}</td>
                    {{-- <td>{{ $d->keteranganlainnya }}</td> --}}

                    {{-- <td>{{ $d->hutangpenjamin }}</td> --}}
                    {{-- <td style="text-align:right">{{ number_format($d->totalharusdibayar, 2, ',', '.') }}</td> --}}
                    {{-- <td style="text-align:right">{{ number_format($d->totaldibayar, 2, ',', '.') }}</td>
                    <td >{{ $d->carabayar }}</td> --}}
                    {{-- <td style="text-align:right">{{ number_format($d->tunai, 2, ',', '.') }}</td>
                    <td style="text-align:right">{{ number_format($d->nontunai, 2, ',', '.') }}</td>
                </tr> --}}
                @endforeach
            @endforeach
            <tr>
                <td colspan="6"></td>
                <td style="text-align:right;">Rp. {{ number_format($tempuangmuka, 2, ',', '.') }}</td>
                <td style="text-align:right;">Rp. {{ number_format($temp, 2, ',', '.') }}</td>
                <td style="text-align:right;">Rp. {{ number_format($temphutang, 2, ',', '.') }}</td>
                <td style="text-align:right;">Rp. {{ number_format($temptanggungan, 2, ',', '.') }}</td>
                <td style="text-align:right;">Rp. {{ number_format($temppembebasan, 2, ',', '.') }}</td>
                <td style="text-align:right;">Rp. {{ number_format($temp, 2, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    <script>
        console.log($data);
    </script>

    {{-- <br>
    <table class="custom-table" style="width: 30%">
        <caption style="display: none"></caption>

        <tbody>
            @foreach ($carabayar as $d)
                @if($d->total > 0)
                <tr>
                    <td><b>{{$d->carabayar}}</b></td>
                    <td>:</td>
                    <td>Rp. {{number_format($d->total, 2, ',', '.')}}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table> --}}
    <br>
    <table class="custom-table-no-border" style="width: 100%;">
        <caption style="display: none"></caption>
        {{-- <tr style="display: none">
            <th scope="col"></th>
        </tr>

        <tr>
            <td colspan="5"><b>Terbilang : "<i>{{ ucwords($terbilang) }} Rupiah."</i></b></td>
        </tr>
        <tr>
            <td style="visibility:hidden">2</td>
            <td style="visibility:hidden">2</td>
            <td></td>
            <td style="visibility:hidden">2</td>
            <td></td>
        </tr> --}}
        <tr>
            <td style="text-align: center"><b>Kasubag. Keuangan</b></td>
            <td></td>
            <td></td>
            <td style="visibility:hidden">-</td>
            <td style="text-align: center"><b>Kasir</b></td>
        </tr>
        <tr>
            <td style="visibility:hidden">4</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">4</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="text-align: center"><b><u>Nenden Ratnawati, SE., MM</u></b></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center"><b><u>{{($namaPegawai)}}</u></b></td>
        </tr>
        <tr>
            <td style="text-align: center"><b>NIP.</b></td>
            <td></td>
            <td></td>
            <td></td>
            <td style="text-align: center"><b>NIP.</td>
        </tr>
        {{-- <tr>
            <td style="visibility:hidden">5</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>KA. INSTALASI</b></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">6</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">7</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td style="visibility:hidden">8</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td><b>..............................</b></td>
            <td></td>
            <td></td>
        </tr> --}}
    </table>
    <div style="page-break-before: always" width="100%">
        <table width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <th>
                    <img src="{{ 'img/logo-rs.png' }}" width="50px">
                </th>
                <th width="90%">
                    <table width="100%" style="left:-2rem;position:relative">
                        <tr>
                            <td class="label-strong">
                                <font>{{ strtoupper($profile->namapemerintahan) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-strong">
                                <font>{{ strtoupper($profile->namalengkap) }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-normal">
                                <font>Jalan Kesambi No. 56 Telp.{{ $profile->fixedphone }} Fax.{{ $profile->faksimile }}
                                    Kode Pos {{ $profile->kodepos }}</font>
                            </td>
                        </tr>
                        <tr>
                            <td class="label-strong">
                                <font>{{ strtoupper($profile->namakota) }}</font>
                            </td>
                        </tr>
                    </table>
                </th>
            </tr>
        </table>
        <hr style="margin: 0px">
        <table width="100%">
            <tr>
                <th class="label-normal">
                    {{-- <font>Nomer Bukti : {{ $identitas[0]->nokwitansi }}</font> --}}
                </th>
            </tr>
            <tr>
                <th class="label-strong" style="text-transform:uppercase">
                    <font>Tanda Bukti Pembayaran</font>
                </th>
            </tr>
        </table>

        <table width="100%">
            <tr>
                <td class="label-normal" style="text-align: left !important" width="32%">
                    <font>Telah menerima uang sebesar</font>
                </td>
                <td class="label-strong" style="text-align: left !important;font-weight:bold">
                    <font style="font-size:18pt">Rp.  {{ number_format($temp, 2, ',', '.') }}</font>
                </td>
            </tr>
            <tr>
                <td class="label-normal" style="text-align: left !important">
                    <font>dengan huruf</font>
                </td>
                <td class="label-strong" style="text-align: left;font-weight:bold ; margin-left:-30px;">
                    <font>({{ strtoupper($terbilang) }})</font>
                </td>
            </tr>
            <tr>
                <td class="label-normal" style="text-align: left !important;padding-top:.5">
                    <font>dari</font>
                </td>
            </tr>
        </table>

        <table style="margin-top:-.5rem;margin-left:3rem;margin-right:3rem; width: 90%;">
            <tr>
                <td class="label-left" width="25%">
                    <font style="font-weight: bold">Nama</font>
                </td>
                <td width="3%" class="label-normal">
                    <font style="font-weight: bold">:</font>
                </td>

                <td class="label-normal" style="text-align: left">
                    <font><b>{{!empty($ruangan) ? $ruangan:''}}</b></font>
                </td>
            </tr>
            <tr>
                <td class="label-left">
                    <font style="font-weight: bold">Alamat</font>
                </td>
                <td class="label-normal">
                    <font style="font-weight: bold">:</font>
                </td>
                <td class="label-normal" style="text-align: left">
                    <font>{{$profile->namalengkap}}</font>
                </td>
            </tr>
            <tr>
                <td style="padding-bottom:5px;text-align:left;vertical-align:top;font-size:10pt; width:5%">
                    <font>Sebagai Pembayaran</font>
                </td>
                <td style="padding-bottom:5px;text-align:center;vertical-align:top;font-size:10pt; width:5%">
                    <font style="font-weight: bold">:</font>
                </td>

                <td class="label-normal" style="text-align: left; width:80%">
                    <font>Setoran Penerimaan Pasien Ruangan {{!empty($ruangan) ? $ruangan:''}} Jenis Pasien <span style="text-transform: uppercase">{{ $data[0]->kelompokpasien }}</span> dari {{ $tglAwal }} Sampai {{ $tglAkhir }}</font>
                </td>
            </tr>
        </table>

        <table
            style="margin-left:3rem;margin-right:3rem; border-collapse:collapse;border:1px solid black;margin-top:.5rem; width: 90%;">
            <tr>
                <th style="padding:3px;border: 1px solid black" class="label-normal" width="5%">
                    <font>No</font>
                </th>
                <th style="padding:3px;border: 1px solid black" class="label-normal"  width="20%">
                    <font>No Registrasi</font>
                </th>
                <th style="padding:3px;border: 1px solid black" class="label-normal" width="40%">
                    <font>Jumlah</font>
                </th>
            </tr>
            <tr>
                <td class="label-normal" style="border: 1px solid black">
                    {{-- <font>1</font> --}}
                </td>
                <td class="label-normal" style="border: 1px solid black">
                    {{-- <font>{{ strtoupper($identitas[0]->noregistrasi) }}</font> --}}
                </td>
                <td class="label-right" style="border: 1px solid black">
                    <font>Rp. {{ number_format($temp, 2, ',', '.') }}</font>
                </td>
            </tr>
        </table>

        <table width="90%" style="margin-left:3rem;margin-right:3rem;margin-top:.2rem">
            <tr>
                <td width="45%" class="label-right">
                    <font>Tanggal Diterima Uang</font>
                </td>
                <td width="4%" class="label-normal">
                    <font>:</font>
                </td>
                <td style="text-align:left !important">
                    <font style="font-size:10pt"> {{ \Carbon\Carbon::parse(date('Y-m-d H:i:s'))->isoFormat('D MMMM Y HH:mm:ss') }}</font>
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-bottom: 20px;">
            <tr>
                <td class="label-normal" width="25%" rowfont="2">
                    <font>Mengetahui</font>
                </td>
                <td class="label-normal">
                </td>
                <td class="label-normal" rowfont="2">
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top:-10px;">
            <tr>
                <td class="label-normal" width="25%" rowfont="2">
                    <font>Bendahara Penerimaan</font>
                </td>
                <td></td>
                <td class="label-normal" rowfont="2">
                    <font>Pembayar Penyetor</font>
                </td>
            </tr>
        </table>

        <table width="100%" style="margin-top:.5rem">
            <tr>
                <td class="label-normal">
                    <br>
                    <br>
                    <br>
                </td>
                <td class="label-normal">
                    <br>
                    <br>
                    <br>
                </td>
                <td class="label-normal">
                    <br>
                    <br>
                    <br>
                </td>
            </tr>
            <tr>
                <td class="label-normal" width="25%">
                    <font style="font-style:bold; text-transform: uppercase">Ade Rohyani, SE</font>
                </td>
                <td class="label-normal" style="vertical-align: bottom">
                    {{-- <font>{{ $namapegawai }}</font> --}}
                </td>
                <td class="label-normal">
                    <font style="border-bottom:1px solid black">
                        <font><b>{{!empty($ruangan) ? $ruangan:''}}</b></font>
                    </font>
                </td>
            </tr>
        </table>
    </div>
</body>
<style>
    font {
        /* font-family: Arial, Helvetica, sans-serif; */
        font-family: Tahoma, "Trebuchet MS", sans-serif !important;
        color: #000000 !important;
        font-weight: bold !important;
    }

    .table-bordered tr td {
        border: 1px solid #444444;
    }

    .label-strong {
        text-align: center;
        font-size: 14pt;
    }

    .label-normal {
        text-align: center;
        font-size: 12pt;
        font-weight: normal;
    }

    .label-right {
        text-align: right;
        font-size: 10pt;
        font-weight: normal;
    }

    .label-left {
        text-align: left;
        font-size: 10pt;
        font-weight: normal;
    }
</style>

</html>
