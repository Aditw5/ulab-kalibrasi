<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="css/paper.css "> -->
    <!-- <link rel="stylesheet" href="css/table-v2.css"> -->
    <link rel="stylesheet" href="css/style.css">
    <title>Formulir Tanda Terima Alat</title>
</head>
<style>
    @page {
        margin-top: 110px;
        margin-bottom: 60px;
        margin-left: 35px;
        margin-right: 35px;
    }

    body {
        font-family: Arial, Helvetica, sans-serif;
        color: #111;
    }

    .pdf-header {
        position: fixed;
        top: -90px;
        /* Negatif dari margin-top, biar header pas */
        left: 0;
        right: 0;
        height: 100px;
        width: 100%;
        z-index: 10;
    }

    /* Supaya konten tidak nabrak header/footer */
    .pdf-content {
        /* kosong, gunakan margin @page */
    }

    .page-break {
        page-break-before: always;
    }
</style>

<body style="padding:25px">
    <div class="pdf-header">
        <table width="100%" cellspacing="0" cellpadding="0" border="0">
            <thead style="display: none"></thead>
            <tbody>
                <tr>
                    <td rowspan="5">
                        <img src="img/pln.png" width="80px" border="0" style="margin-top:-20px">
                    </td>
                    <td class="text-center">
                        <span style="font-size: 13pt;font-weight: 600;color:#000000"><b>
                                LABORATORIUM KALIBRASI PT PLN NP UMRO
                            </b></span>
                    </td>
                    <td rowspan="5">
                        <img src="img/ulab.png" width="80px" border="0" style="margin-top:-20px">
                    </td>
                </tr>

                <tr>
                    <td class="text-center">
                        <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                                FORMULIR
                            </b></span>
                    </td>
                </tr>
                <tr>
                    <td class="text-center">
                        <span style="font-size: 10pt;font-weight: 600;color:#000000"><b>
                                SURAT PERINTAH KERJA {{ strtoupper($res['alat'][0]->lingkupkalibrasi) }}
                            </b></span>
                    </td>
                </tr>

                <tr>

                    <table width="100%" cellspacing="0" cellpadding="0" border="0"
                        style="border-collapse: collapse;">
                        <tbody>
                            <tr>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    No.Dok : FMMO-163-14.4.3.b-74.4
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Revisi : 01
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Halaman: 1 dari 2
                                </td>
                            </tr>
                        </tbody>
                    </table>

                </tr>
            </tbody>
        </table>

        <hr class="baris1" style="margin-top:1px">
        <hr class="baris1" style="margin-top:-5px">
    </div>

    {{-- {{ dd($res) }} --}}

    <table width="100%" style="margin-top: -19px; ">
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>
                            Diterima Oleh
                        </b></span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Nama
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->namapenanggungjawab }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Jabatan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->jabatanpenanggungjawab }}
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>
                            Penerima ALat
                        </b></span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Nama
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->namapetugaskaji }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Jabatan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->namajabanpetugaskaji }}
                    </span>
                </td>
            </tr>
        </table>
        <table width="100%" style="margin-top: 5px; ">
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;font-weight: 600;olor:#000000"><b>
                            Diteriima Pada
                        </b></span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000"><b>

                        </b></span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Tanggal
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Tempat
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        Laboratorium Kalibrasi PT PLN NUSANTARA POWER UMRO JAKARTA
                    </span>
                </td>
            </tr>
        </table>

        <table style="margin-top: 20px; " width="100%" border="1" cellspacing="0" cellpadding="5"
            style="font-size: 10pt; border-collapse: collapse;">
            <thead style="background-color: #f2f2f2;">
                <tr>
                    <th style="font-size: 9pt;">No</th>
                    <th style="font-size: 9pt;">Nama Barang</th>
                    <th style="font-size: 9pt;">Merk/Tipe</th>
                    <th style="font-size: 9pt;">S/N</th>
                    <th style="font-size: 9pt;">Jumlah</th>
                    <th style="font-size: 9pt;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($res['alat'] as $index => $alat)
                    <tr>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $index + 1 }} </span></td>
                        <td width="25%">
                            <span
                                style="font-size: 9pt; {{ !empty($alat->alasanpenolakanregis) ? 'color: red;' : '' }}"
                                class="text-biasa">
                                {{ $alat->namaproduk }}
                                @if (!empty($alat->alasanpenolakanregis))
                                    (Ditolak)
                                @endif
                            </span>
                        </td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ trim($alat->namamerk) }}
                                {{ $alat->namatipe }}</span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaserialnumber }}</span>
                        </td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">1 Set </span></td>
                        <td>
                            <span style="font-size: 9pt;" class="text-biasa">

                                @if (!empty($alat->keterangan))
                                    {{ $alat->keterangan }}
                                @elseif (!empty($alat->alasanpenolakanregis))
                                    <div style="color: red; font-size: 12px; margin-top: 10px;">
                                        {{ $alat->alasanpenolakanregis }}
                                    </div>
                                @else
                                    <span style="color: gray; font-size: 12px;">
                                        Tidak ada file atau alasan penolakan</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px">
            <tbody style="font-size: 11pt">
                <tr>
                    <td width="50%">
                    </td>
                    <td width="50%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">{{ $res['identitas']->lokasi }},
                            {{ \Carbon\Carbon::parse(date('Y-m-d H:i'))->isoFormat('DD MMMM Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa"> Pelanggan </span>
                    </td>
                    <td width="50%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa"> Penerima Alat</span>
                    </td>
                </tr>
                <tr>
                    <td align="center">
                        <img src="data:image/png;base64, {!! $res['ttdPelanggan'] !!}"
                            style="margin-top: 5px; margin-bottom: 5px">
                        {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                    </td>
                    <td align="center">
                        <img src="data:image/png;base64, {!! $res['ttdPetugas'] !!}"
                            style="margin-top: 5px; margin-bottom: 5px">
                        {{-- <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt=""> --}}
                    </td>
                </tr>
                <tr>
                    <td height="10" valign="bottom" height="100" width="15%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">(
                            {{ $res['identitas']->namapenanggungjawab }}
                            )</span>
                    </td>
                    <td height="10" valign="bottom" height="100" width="15%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">( {{ $res['identitas']->namapetugaskaji }}
                            )</span>
                    </td>
                </tr>
            <tbody>
        </table>
</body>

<div class="page-break"></div>

<!-- Halaman Kedua -->
<div class="sheet" style="padding:25px">
    <table style="margin-top: 20px; " width="100%" border="1" cellspacing="0" cellpadding="5"
        style="font-size: 10pt; border-collapse: collapse;">
        <thead style="background-color: #f2f2f2;">
            <tr>
                <th style="font-size: 9pt;">No</th>
                <th style="font-size: 9pt;">Nama Barang</th>
                <th style="font-size: 9pt;">Merk/Tipe</th>
                <th style="font-size: 9pt;">S/N</th>
                <th style="font-size: 9pt;">Jumlah</th>
                <th style="font-size: 9pt;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($res['alat'] as $index => $alat)
                <tr>
                    <td width="5%"> <span style="font-size: 9pt;" class="text-biasa">{{ $index + 1 }} </span>
                    </td>
                    <td width="25%">
                        <span style="font-size: 9pt; {{ !empty($alat->alasanpenolakanregis) ? 'color: red;' : '' }}"
                            class="text-biasa">
                            {{ $alat->namaproduk }}
                            @if (!empty($alat->alasanpenolakanregis))
                                (Ditolak)
                            @endif
                        </span>
                    </td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">{{ trim($alat->namamerk) }}
                            {{ $alat->namatipe }}</span></td>
                    <td width="15%"> <span style="font-size: 9pt;"
                            class="text-biasa">{{ $alat->namaserialnumber }}</span>
                    </td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">1 Set </span></td>
                    <td width="25%" style="text-align: center">
                        @if (!empty($alat->namafile))
                            <img src="{{ 'berkas-mitra/' . $alat->namafile }}" width="170px" style="margin:2px 0;">
                        @elseif (!empty($alat->alasanpenolakanregis))
                            <div style="color: red; font-size: 12px; margin-top: 10px;">
                                {{ $alat->alasanpenolakanregis }}
                            </div>
                        @else
                            <span style="color: gray; font-size: 12px;">Tidak ada file atau alasan penolakan</span>
                        @endif
                    </td>


                </tr>
            @endforeach
        </tbody>
    </table>
</div>


</html>
