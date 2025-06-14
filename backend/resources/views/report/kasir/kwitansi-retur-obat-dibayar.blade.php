<html>

<head>
    <title>
        Kwitansi Retur Barang Telah Dibayar
    </title>

    @if (stripos(\Request::url(), 'localhost') !== false || stripos(\Request::url(), 'transmedika') !== false)
        <link rel="stylesheet" href="{{ asset('css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('css/tabel.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('service/css/paper.css') }} ">
        <link rel="stylesheet" href="{{ asset('service/css/table-v2.css') }}">
        <link rel="stylesheet" href="{{ asset('service/css/tabel.css') }}">
    @endif

</head>
<style type="text/css" media="print">
    @page {
        size: auto;
        /* auto is the initial value */
        margin: 0;
        /* this affects the margin in the printer settings */
    }
</style>
<style>
    tr td {
        padding: 2px 4px 2px 4px;
    }

    .th-init {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }

    .td-init {
        padding-top: 5px !important;
    }

    .borderss {
        border-bottom: 1px solid black;
    }

    body {
        font-family: Tahoma, Geneva, sans-serif;
    }

    @page {
        size: A4
    }

    .garis6 td {
        padding: 3px !important;
    }
</style>
@php
    if (isset($_GET['namafile'])) {
        header('Content-type: application/vnd-ms-excel');
        header('Content-Disposition: attachment; filename=' . $_GET['namafile'] . '.xls');
    }
    
@endphp
<!-- onload="window.print()" -->

<body style="background-color: #CCCCCC">

    <div style="text-align: center">
        <table class="bayangprint" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" border="0"
            width="{{ $pageWidth }}" style="padding:25px">
            <tbody>
                <tr>
                    <td style="text-align:center;display: grid;">
                        <span>{{ $profile->namapemerintahan }}</span>
                        <span>{{ $profile->namalengkap }}</span>
                        <span>{!! $profile->alamatlengkap !!} </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <hr>
                    </td>
                </tr>

                <tr>
                    <td align="center">
                        <span style="font-size: 14pt; font-weight: bold;" color="#000000">KWITANSI</span>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 2rem">
                            <tr>
                                <td width="10%"><span style="font-size: 12pt;" color="#000000;">No. Registrasi
                                    </span></td>
                                <td width="40%"><span style="font-size: 12pt;" color="#000000">:
                                        {{ $result['dataPasien']->noregistrasi }}</span></td>
                            </tr>
                            <tr>
                                <td width="10%"><span style="font-size: 12pt;" color="#000000;">TGL. Registrasi
                                    </span></td>
                                <td width="40%"><span style="font-size: 12pt;" color="#000000">:
                                        {{ $result['dataPasien']->tglregistrasi }}</span></td>
                            </tr>
                            <tr>
                                <td width="10%"><span style="font-size: 12pt;" color="#000000;">Nama Pasien </span>
                                </td>
                                <td width="40%"><span style="font-size: 12pt;" color="#000000">:
                                        {{ $result['dataPasien']->namapasien }}</span></td>
                            </tr>
                            <tr>
                                <td width="10%"><span style="font-size: 12pt;" color="#000000;">TGL. Lahir </span>
                                </td>
                                <td width="40%"><span style="font-size: 12pt;" color="#000000">:
                                        {{ $result['dataPasien']->tgllahir }}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="padding-top:2rem">
                        <table width="100%" cellspacing="0" cellpadding="0" align="center">
                            <tr>
                                <th class="class-2 th-init"><span>No</span></th>
                                <th class="class-2 th-init"><span>Nama Produk</span></th>
                                {{-- <th class="class-2 th-init"><span>Qty Order</span></th> --}}
                                <th class="class-2 th-init"><span>Qty Retur</span></th>
                                <th class="class-2 th-init"><span>Harga Jual</span></th>
                                <th class="class-2 th-init"><span>Diskon</span></th>
                                <th class="class-2 th-init"><span>Jasa</span></th>
                            </tr>
                            @foreach ($result['returOrder'] as $item)
                                <tr style="text-align: center;">
                                    <td class="td-init"><span class="text-biasa">{{ $loop->iteration }}</span></td>
                                    <td class="td-init"><span class="text-biasa">{{ $item->namaproduk }}</span></td>
                                    <td class="td-init"><span class="text-biasa">{{ $item->qtyRetur }}</span></td>
                                    <td class="td-init"><span
                                            class="text-biasa">{{ number_format($item->hargajual) }}</span></td>
                                    <td class="td-init"><span
                                            class="text-biasa">{{ number_format($item->hargadiscount) }}</span></td>
                                    <td class="td-init"><span
                                            class="text-biasa">{{ number_format($item->jasa) }}</span>
                                    </td>
                            @endforeach
                        </table>
                    </td>
                </tr>

                <tr>
                    <td>
                        <table width="100%" cellspacing="0" cellpadding="0" style="padding-top: 2rem">
                            <tr>
                                <td width="15%"><span style="font-size: 12pt;" color="#000000;">Total Nilai Kembalian
                                    </span></td>
                                <td width="40%"><span style="font-size: 12pt;" color="#000000">:
                                        {{ $result['kembalianPasien']}}</span></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</html>



{{-- <!DOCTYPE html>
<html>
<head>
	<title>contoh surat pengunguman</title>
	<style type="text/css">
		table {
			border-style: double;
			border-width: 3px;
			border-color: white;
		}
		table tr .text2 {
			text-align: right;
			font-size: 13px;
		}
		table tr .text {
			text-align: center;
			font-size: 13px;
		}
		table tr td {
			font-size: 13px;
		}

	</style>
</head>
<body>
	<center>
		<table>
			<tr>
				<td><img src="LOGO.jpg" width="90" height="90"></td>
				<td>
				<center>
					<font size="4">LEMBAGA PERATIKUM 2019</font><br>
					<font size="5"><b>SMK BAITUL HIKAH</b></font><br>
					<font size="2">Bidang Keahlian : Bisnis dan Menejemen - Teknologi informasi dan Komunikasi</font><br>
					<font size="2"><i>Jln Cut Nya'Dien No. 02 Kode Pos : 68173 Telp./Fax (0331)758005 Tempurejo Jember Jawa Timur</i></font>
				</center>
				</td>
			</tr>
			<tr>
				<td colspan="2"><hr></td>
			</tr>
		<table width="625">
			<tr>
				<td class="text2">Jember, 16 mei 2019</td>
			</tr>
		</table>
		</table>
		<table>
			<tr class="text2">
				<td>Nomer</td>
				<td width="572">: -</td>
			</tr>
			<tr>
				<td>Perihal</td>
				<td width="564">: -</td>
			</tr>
		</table>
		<br>
		<table width="625">
			<tr>
		       <td>
			       <font size="2">Kpd yth.<br>Siswa Smk Baitul Hikmah kelas x<br>Di tempat</font>
		       </td>
		    </tr>
		</table>
		<br>
		<table width="625">
			<tr>
		       <td>
			       <font size="2">Assalamu'alaikum wr.wb<br>Dalam rangka praktikum simulasi digital yg jatuh pada tanggal 16 mei 2019
Siswa smk baitul hikmah <b>kelas X</b> akan mengadakan peraktikum, jadi di harapkan siswa di minta hadir
pada tempat yang sudah di siapkan.</font>
		       </td>
		    </tr>
		</table>
		<br>
		</table>
		<table>
			<tr class="text2">
				<td>Hari Tanggal</td>
				<td width="541">: <b>Selasa/16 mei 2019</b></td>
			</tr>
			<tr>
				<td>Jam</td>
				<td width="525">: 08:30</td>
			</tr>
			<tr>
				<td>Tempat</td>
				<td width="525">: Ruang lap komputer</td>
			</tr>
		</table>
		<br>
		<table width="625">
			<tr>
		       <td>
			       <font size="2">Diharapkan atas kehadiranya, Demikian surat ini di sampaikan, atas perhatian dan kerjasamanya kami
ucapkan terima kasih.<br><br>Wassalamu'alaikum wr.wb.
</font>
		       </td>
		    </tr>
		</table>
		<br>
		<table width="625">
			<tr>
				<td width="430"><br><br><br><br></td>
				<td class="text" align="center">Wali kelas<br><br><br><br>Bpk Fauzy.s.kom</td>
			</tr>
	     </table>
	</center>
</body>
</html>
contoh.html
Menampilkan contoh.html. --}}
