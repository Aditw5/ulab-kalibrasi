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

<body style="padding-top:25px">
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
                        <img src="img/UMRO.png" width="80px" border="0" style="margin-top:-20px">
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
                                Permintaan Kalibrasi dan Kontrak
                            </b></span>
                    </td>
                </tr>

                <tr>

                    <table width="75%" cellspacing="0" cellpadding="0" border="0"
                        style="border-collapse: collapse; margin-left: 90px">
                        <tbody>
                            <tr>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    No.Dok : FMMO-163-14.4.3.b-71.1
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Revisi : 01
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Tanggal : {{ now()->locale('id')->isoFormat('D MMMM Y') }}
                                </td>
                                <td style="font-size:9pt;color:#000;font-weight:600;padding:2px 4px;text-align:center;">
                                    Halaman: 1 dari 1
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

    <table width="100%" style="margin-top: -10px; ">
        <table border="0" width="100%" style="margin-top: 15px; ">
            <tr>
                <td width="30%">
                    <span style="font-size: 9pt;color:#000000">
                        No. Urut Pendaftaran
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->nopendaftaran }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Nama Perusahaan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->namaperusahaan }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Alamat Perusahaan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->alamatktr }}
                    </span>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" style="margin-top: 15px; ">
            <tr>
                <td width="30%">
                    <span style="font-size: 9pt;color:#000000">
                        Telp./Fax
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->nohp }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        COntact Person/Penanggung Jawab
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        -
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Email
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['identitas']->email ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Reantang Ukur Kalibrasi Yang Diminta
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <table width="100%">
                        <tr>
                            <td width="15%">
                                <input type="checkbox"
                                    {{ isset($res['identitas']->rentangUkur) && $res['identitas']->rentangUkur == 'standarLab' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;position: relative;top:-7px">Standar Lab</span>
                            </td>
                            <td width="15%">
                                <input type="checkbox"
                                    {{ isset($res['identitas']->rentangUkur) && $res['identitas']->rentangUkur == 'permintaanPelanggan' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;position: relative;top:-7px">Permintaan Pelanggan</span>
                            </td>
                            <td width="15%">
                                <input type="checkbox"
                                    {{ isset($res['identitas']->rentangUkur) && $res['identitas']->rentangUkur == 'lainLain' ? 'checked' : '' }} />
                                <span style="font-size: 9pt;position: relative;top:-7px">Lain-Lain</span>
                            </td>
                        </tr>
                        @if ($res['identitas']->rentangUkur == 'permintaanPelanggan')
                            <tr>
                                <td width="15%">
                                    <span style="font-size: 9pt;color:#000000">
                                        {{ $res['identitas']->rentangUkurketPermintaanPelanggan ?? '-' }}
                                    </span>
                                </td>
                            </tr>
                        @endif
                    </table>
                </td>
            </tr>
        </table>
        {{-- <table border="0" width="100%" style="margin-top: 10px; ">
                <tr>
                    <td width="30%">
                        <span style="font-size: 9pt;color:#000000"><b>
                                Denagn menandatangani formulir ini saya nyatakan data diatas benar </b>
                        </span><br>
                    </td>
                </tr>
            </table> --}}
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: -30px">
            <tbody style="font-size: 11pt">
                <tr>
                    <td width="90%">
                        <span style="font-size: 9pt;color:#000000"><b>
                                Denagn menandatangani formulir ini saya nyatakan data diatas benar </b>
                        </span>
                    </td>
                    <td align="center" width="40%" class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">{{ $res['identitas']->lokasi }},
                            {{ \Carbon\Carbon::parse(date('Y-m-d H:i'))->isoFormat('DD MMMM Y') }}</span>
                    </td>
                </tr>
                {{-- <tr>
                    <td  width="90%">
                    </td>
                    <td align="center">
                        <img src="data:image/png;base64, {!! $res['ttdPelanggan'] !!}"
                            style="margin-top: 5px; margin-bottom: 5px">
                    </td>
                </tr> --}}
                @php
                    $ttdData = json_decode($res['identitas']->ttdpenanggungjawab);
                @endphp
                <tr>
                    <td width="90%">
                    </td>
                    <td align="center">
                        @if (!empty($ttdData->ttdPenanggungJawab))
                            <img src="{{ $ttdData->ttdPenanggungJawab }}"
                                style="margin-top: 5px; margin-bottom: 5px; max-height: 80px;" />
                        @endif
                    </td>
                </tr>
                <tr>
                    <td width="90%">
                    </td>
                    <td align="center" height="8" valign="bottom" height="100" width="15%"
                        class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">
                            <b> ({{ $res['identitas']->namapenanggungjawab }})</span></b>
                    </td>
                </tr>
            <tbody>
        </table>
        <table border="0" width="100%" style="margin-top: 10px; ">
            <tr>
                <td width="30%">
                    <span style="font-size: 9pt;color:#000000"><b>
                            Status Pendaftaran</b>
                    </span>
                    <span style="font-size: 8pt;color:#000000">
                        (diisi oleh petuugas U-Lab)
                    </span>
                </td>
                <td width="30%">
                    <span style="font-size: 9pt;color:#000000"><b>
                            Status Alat</b>
                    </span>
                    <span style="font-size: 9pt;color:#000000"><b>
                            Diisi saat alat diterima oleh U-Lab</b>
                    </span>

                </td>
            </tr>
            <tr>
                <td width="30%" style="vertical-align: top;">

                    <input type="checkbox" />
                    <span style="font-size: 9pt;position: relative;top:-7px">
                        Pendaftaran diterima, Alat harus diserahkan ke U-Lab
                    </span><br>
                    <span style="font-size: 9pt;position: relative;top:-7px; margin-left: 24px">
                        Tanggal .... s//d ...
                    </span><br>
                    <input type="checkbox" />
                    <span style="font-size: 9pt;position: relative;top:-7px">
                        Layanan ditangguhkan karena
                    </span><br>
                    <input type="checkbox" style="margin-left: 24px" />
                    <span style="font-size: 9pt;position: relative;top:-7px">
                        Kapasitas penuh
                    </span><br>
                    <input type="checkbox" style="margin-left: 24px" />
                    <span style="font-size: 9pt;position: relative;top:-7px">
                        Diluar lingkup pelayanan
                    </span><br>
                    <input type="checkbox" style="margin-left: 24px" />
                    <span style="font-size: 9pt;position: relative;top:-7px">
                        Sedang dalam pemilihan standar
                    </span><br>
                    <input type="checkbox" style="margin-left: 24px" />
                    <span style="font-size: 9pt;position: relative;top:-7px">
                        Lain-lain :
                    </span><br>

                </td>
                <td width="30%" style="vertical-align: top;">
                    <table border="0" width="100%" style="margin-top: -10px; ">
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    No. Order
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 9pt;color:#000000">

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    Tanggal Penyerahan
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 9pt;color:#000000">

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    Kelengkapan alat
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 9pt;color:#000000">

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    Catatan Kondisi alat
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 9pt;color:#000000">

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 7pt;color:#000000">
                                    (visual dan fungsional cek)
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 7pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 7pt;color:#000000">

                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    Catatan Kondisi alat
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <img src="data:image/png;base64, {!! $res['ttdPetugas'] !!}"
                                    style="margin-top: 5px; margin-bottom: 5px">
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    Nama
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 9pt;color:#000000">
                                    {{ $res['identitas']->namapetugaskaji ?? '-' }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td width="40%">
                                <span style="font-size: 9pt;color:#000000">
                                    Catatan penyimpangan hasil obeservasi teknis
                                </span>
                            </td>
                            <td width="2%">
                                <span style="font-size: 9pt;color:#000000">
                                    :
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 9pt;color:#000000">

                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" style="margin-top: 15px; ">
            <tr>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        <b>Kontrak kesepakatan</b>
                    </span>
                    <span style="font-size: 7pt;color:#000000">
                        (diisi oleh petugas U-Lab dan ttd oleh pelanggan saat penyerahan alat)
                    </span>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" style="margin-top: 1px; ">
            <tr>
                <td width="30%">
                    <span style="font-size: 9pt;color:#000000">
                        Tanggal Order
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['identitas']->tglregistrasi ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Tanggal selesai
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        {{ $res['alat'][0]->tanggalSelesai ?? '-' }}
                    </span>
                </td>
            </tr>
            <tr>
                <td width="15%">
                    <span style="font-size: 9pt;color:#000000">
                        Durasi Pekerjaan
                    </span>
                </td>
                <td width="2%">
                    <span style="font-size: 9pt;color:#000000">
                        :
                    </span>
                </td>
                <td>
                    <span style="font-size: 9pt;;color:#000000">
                        {{ $res['alat'][0]->totalDurasi ?? '-' }} Hari Kerja
                    </span>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" style="margin-top: 0px; ">
            <tr>
                <td>
                    <span style="font-size: 9pt;color:#000000">
                        Kontrak kesepakatan telah disepakati oleh kedua belah pihak, apabila ada perubahan dalam kontrak
                        akan diberitahukan sebelumnya.
                    </span>
                </td>
            </tr>
        </table>
        <table border="0" width="100%" cellspacing="0" cellpadding="0" style="padding-top: -30px">
            <tbody style="font-size: 11pt">
                @php
                    $ttdData = json_decode($res['identitas']->ttdpenanggungjawab);
                @endphp
                <tr>
                    <td width="50%" align="center">
                      
                    </td>
                    <td width="50%" align="center">
                           <span style="font-size: 10pt;" class="text-biasa">{{ $res['identitas']->lokasi }},
                            {{ \Carbon\Carbon::parse(date('Y-m-d H:i'))->isoFormat('DD MMMM Y') }}</span>
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="center">
                        <span style="font-size: 10pt;" class="text-biasa">
                            <b> Pelanggan</span></b>
                    </td>
                    <td width="50%" align="center">
                          <span style="font-size: 10pt;" class="text-biasa">
                            <b> Asman</span></b>
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="center">
                        @if (!empty($ttdData->ttdPenanggungJawab))
                            <img src="{{ $ttdData->ttdPenanggungJawab }}"
                                style="margin-top: 5px; margin-bottom: 5px; max-height: 80px;" />
                        @endif
                    </td>
                    <td width="50%" align="center">
                        <img src="data:image/png;base64, {!! $res['ttdAsman'] !!}"
                                    style="margin-top: 5px; margin-bottom: 5px">
                    </td>
                </tr>
                <tr>
                    <td width="50%" align="center" height="8" valign="bottom" height="100" width="15%"
                        class="text-center">
                        <span style="font-size: 10pt;" class="text-biasa">
                            <b> ({{ $res['identitas']->namapenanggungjawab }})</span></b>
                    </td>
                    <td width="50%" align="center">
                         <span style="font-size: 10pt;" class="text-biasa">
                            <b> ({{ $res['alat'][0]->asamanverifikasi }})</span></b>
                    </td>
                </tr>
            <tbody>
        </table>
        {{-- <table width="100%" style="margin-top: 5px; ">
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
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaproduk }}</span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ trim($alat->namamerk) }}
                                {{ $alat->namatipe }}</span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->namaserialnumber }}</span>
                        </td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">1 Set </span></td>
                        <td> <span style="font-size: 9pt;" class="text-biasa">{{ $alat->keterangan }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table> --}}
        {{-- <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 20px">
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
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt="">
                    </td>
                    <td align="center">
                        <img src="data:image/png;base64, {!! $res['ttdPetugas'] !!}"
                            style="margin-top: 5px; margin-bottom: 5px">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&data={{isset($res) ? $res['kepala']->namalengkap : ''}}" alt="">
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
        </table> --}}
</body>

{{-- <div class="page-break"></div> --}}

<!-- Halaman Kedua -->
{{-- <div class="sheet" style="padding:25px">
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
                    <td width="25%"> <span style="font-size: 9pt;"
                            class="text-biasa">{{ $alat->namaproduk }}</span></td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">{{ trim($alat->namamerk) }}
                            {{ $alat->namatipe }}</span></td>
                    <td width="15%"> <span style="font-size: 9pt;"
                            class="text-biasa">{{ $alat->namaserialnumber }}<< /span>
                    </td>
                    <td width="15%"> <span style="font-size: 9pt;" class="text-biasa">1 Set </span></td>
                    <td width="25%" style="text-align: center">
                        @if ($alat->namafile)
                            <img src="{{ 'berkas-mitra/' . $alat->namafile }}" width="170px" style="margin:2px 0;">
                        @endif
                    </td>

                </tr>
            @endforeach
        </tbody>
    </table>
</div> --}}


</html>
