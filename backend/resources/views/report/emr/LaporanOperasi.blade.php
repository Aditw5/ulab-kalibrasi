@extends('template.'.$header_use)
@section('title', 'EMR')
@section('koderme', 'XXX.XX/FORM/X/RMIK/2023/Rev. XX')
@section('about', 'LAPORAN OPERASI')
@push('style')
<style>
.table-garis {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid black; /* Border untuk elemen table */
}

.table-garis th {
    border: 1px solid black; /* Border untuk seluruh sel dalam tabel */
    padding: 4px;
    font-size: 10pt;
}
.table-garis td {
    border: 1px solid black; /* Border untuk seluruh sel dalam tabel */
    padding: 4px;
    font-size: 8pt;
    vertical-align: top;
}
.table-garis td p {
    vertical-align: top;
}

.table-nongaris {
    border-collapse: collapse;
    width: 100%;
}

.table-nongaris th {
    padding: 4px;
    font-size: 10pt;
    border: none; /* Border untuk seluruh sel dalam tabel */
}
.table-nongaris td {
    padding: 4px;
    font-size: 8pt;
    vertical-align: top;
    border: none; /* Border untuk seluruh sel dalam tabel */
}
.table-nongaris td p {
    vertical-align: top;
}
.bold {
    font-weight:bold;
}
.text-top {
    vertical-align:top;
    margin-bottom:2px;
}
</style>
@endpush
@section('content')
    <table width="100%" cellspacing="0" class="table-garis">
        <tr>
            <td>
                <span class="bold">Dokter Bedah</span><br>
                <span>{{ isset($data['dokterBedah']) ? $data['dokterBedah']['label'] : "" }}</span>
            </td>
            <td>
                <span class="bold">Asisten Bedah I</span><br>
                <span>{{ isset($data['asistenBedahI']) ? $data['asistenBedahI']['label'] : "" }}</span>
            </td>
            <td>
                <span class="bold">Asisten Bedah II</span><br>
                <span>{{ isset($data['asistenBedahII']) ? $data['asistenBedahII']['label'] : "" }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="bold">Dokter Anestesi</span><br>
                <span>{{ isset($data['dokterAnestesi']) ? $data['dokterAnestesi']['label'] : "" }}</span>
            </td>
            <td>
                <span class="bold">Asisten Anestesi</span><br>
                <span>{{ isset($data['asistenAnestesi']) ? $data['asistenAnestesi']['label'] : "" }}</span>
            </td>
            <td>
                <span class="bold">Instrumenter</span><br>
                <span>{{ isset($data['instrumenter']) ? $data['instrumenter']['label'] : "" }}</span>
            </td>
        </tr>
    </table>
    <table width="100%" cellspacing="0" class="table-garis">
        <tr>
            <td colspan="4">
                <span class="bold">Diagnosa Pra-Bedah</span><br>
                <span>{{ isset($data['diagnosaPraBedah']) ? $data['diagnosaPraBedah']['label'] : "" }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="bold">Diagnosa Pasca-Bedah</span><br>
                <span>{{ isset($data['diagnosaPascaBedah']) ? $data['diagnosaPascaBedah']['label'] : "" }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="bold">Nama Operasi</span><br>
                <span>{{ isset($data['namaOperasi']) ? $data['namaOperasi'] : "" }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="bold">Jenis Operasi</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['lukaOperasi_0']) ? "checked" : "" }} />&nbsp;&nbsp;Cito</label>
                <label class="text-top"><input type="checkbox" {{ isset($data['lukaOperasi_1']) ? "checked" : "" }} />&nbsp;&nbsp;Elektif</label>
            </td>
             <td>
                <span class="bold">Golongan Operasi</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['golonganOperasi_0']) ? "checked" : "" }} />&nbsp;&nbsp;Kecil</label><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['golonganOperasi_1']) ? "checked" : "" }} />&nbsp;&nbsp;Sedang</label><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['golonganOperasi_2']) ? "checked" : "" }} />&nbsp;&nbsp;Besar</label><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['golonganOperasi_3']) ? "checked" : "" }} />&nbsp;&nbsp;Besar Khusus</label>
            </td>
            <td>
                <span class="bold">Jenis Anastesi</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['anestesi_0']) ? "checked" : "" }} />&nbsp;&nbsp;General</label><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['anestesi_1']) ? "checked" : "" }} />&nbsp;&nbsp;Elektif</label>
            </td>
            <td>
                <span class="bold">Dikirim untuk pemeriksaan PA</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['pemeriksaanPA_0']) ? "checked" : "" }} />&nbsp;&nbsp;Ya</label><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['pemeriksaanPA_1']) ? "checked" : "" }} />&nbsp;&nbsp;Tidak</label>
            </td>
        </tr>
        <tr>
            <td>
                <span class="bold">Perdarahan</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['perdarahan_0']) ? "checked" : "" }} />&nbsp;&nbsp;Ya</label>
                <label class="text-top"><input type="checkbox" {{ isset($data['perdarahan_2']) ? "checked" : "" }} />&nbsp;&nbsp;Tidak</label>
                <br>
                <span>{{ isset($data['Ketperdarahan_1']) ? $data['Ketperdarahan_1'] : "" }}</span><br>
            </td>
            <td>
                <span class="bold">Komplikasi</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['komplikasi_0']) ? "checked" : "" }} />&nbsp;&nbsp;Ada</label><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['komplikasi_1']) ? "checked" : "" }} />&nbsp;&nbsp;Tidak</label><br>
            </td>
            <td colspan="2">
                <span class="bold">Pemakaian Implan</span><br>
                <label class="text-top"><input type="checkbox" {{ isset($data['pemakaianImplan_0']) ? "checked" : "" }} />&nbsp;&nbsp;Ada</label>
                <label class="text-top"><input type="checkbox" {{ isset($data['pemakaianImplan_2']) ? "checked" : "" }} />&nbsp;&nbsp;Tidak</label>
                <br>
                <span>{{ isset($data['ketPemakaianImplan_1']) ? $data['ketPemakaianImplan_1'] : "" }}</span><br>
            </td>
        </tr>
        <tr>
            <td colspan="4">
                <span class="bold">Jaringan yang di eksisi/insisi</span><br>
                <span>{{ isset($data['jaringanEksisi']) ? $data['jaringanEksisi'] : "" }}</span>
            </td>
        </tr>
        <tr>
            <td>
                <span class="bold">Tanggal Operasi</span><br>
                <span>{{ isset($data['tglOperasi']) ? date('Y-m-d', strtotime($data['tglOperasi'])) : "" }}</span>
            </td>
            <td>
                <span class="bold">Jam Operasi Mulai</span><br>
                <span>{{ isset($data['jamStartOperasi']) ? date('H:i', strtotime($data['jamStartOperasi'])) : "" }}</span>
            </td>
            <td>
                <span class="bold">Jam Operasi Berakhir</span><br>
                <span>{{ isset($data['jamEndOperasi']) ? date('H:i', strtotime($data['jamEndOperasi'])) : "" }}</span>
            </td>
            <td>
                <span class="bold">Lama Operasi</span><br>
                <span>{{ isset($data['lamaOperasi']) ? $data['lamaOperasi'] : "" }}</span>
            </td>
        </tr>
        <tr>
            <td colspan="3" height="150">
                <span class="bold">Laporan Operasi</span><br>
                <span>{{ isset($data['laporan']) ? $data['laporan'] : "" }}</span>
            </td>
            <td height="150">
                <span class="bold">Jam Penulisan Laporan</span><br>
                <span>{{ isset($data['jamPenulisanLaporan']) ? date('H:i', strtotime($data['jamPenulisanLaporan'])) : "" }}</span><br><br><br>
                <table width="100%" cellspacing="0" class="table-nongaris">
                    <tr>
                        <td align="center">
                            @if(isset($data['dokterBedah']))
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ $data['dokterBedah']['label'] }}">
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td align="center">
                            ( {{ isset($data['dokterBedah']) ? $data['dokterBedah']['label'] : "" }} )
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
@endsection

