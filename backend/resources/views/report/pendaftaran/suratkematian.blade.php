 @extends('template.layout-kematian')
 @section('title', 'Surat ketangan kematian')
 @section('page-style')

 @endsection
 @section('content')
 <tr>
     <td style="padding-top:10px">
         <table width="100%" cellspacing="0" cellpadding="0" border="0">
             <tr>
                 <td align="center">
                     <font style="font-size: 14pt;font-weight: 600;text-decoration: underline;" color="#000000">
                         SURAT KETERANGAN KEMATIAN
                     </font>
                     <br>
                     <font style="font-size: 12pt;font-weight: 600; text-transform: uppercase" color="#000000">
                         NO. {{ $raw->nosurat }}
                     </font>
                 </td>
             </tr>
         </table>
     </td>
 </tr>
 <tr>
     <td style="padding-top:10px">
         <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
             <tr>
                 <td width="100%" height="20" colspan="3">
                    <font style="font-size: 12pt;" color="#000000">Yang bertanda tangan di bawah ini <b>{{ $dataForensik->namalengkap ?? '' }}</b></font>
                 </td>
             </tr>
             <tr>
                 <td width="100%" height="20" colspan="3">
                    <font style="font-size: 12pt;" color="#000000">Telah memeriksa mayat :</font>
                 </td>
             </tr>
             <tr>
                 <td width="29%">
                     <font style="font-size: 12pt;" color="#000000">Nama</font>
                 </td>
                 <td width="1%">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                     <font style="font-size: 12pt;" color="#000000">{{ $raw->namapasien }}</font>
                 </td>
             </tr>
             <tr>
                 <td width="29%">
                     <font style="font-size: 12pt;" color="#000000">No. Rekam Medis</font>
                 </td>
                 <td width="1%">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                     <font style="font-size: 12pt;" color="#000000">{{ $raw->nocm }}</font>
                 </td>
             </tr>
             <tr>
                 <td width="29%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">NIK</font>
                 </td>
                 <td width="1%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                     <font style="font-size: 12pt;" color="#000000">{{ $raw->noidentitas }}</font>
                 </td>
             </tr>
             <tr>

                 <td width="29%">
                     <font style="font-size: 12pt;" color="#000000">Jenis Kelamin</font>
                 </td>
                 <td width="1%">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                     <font style="font-size: 12pt;" color="#000000">{{ $raw->jeniskelamin }}</font>
                 </td>
             </tr>
             <tr>
                 <td width="29%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">Tempat/Tanggal Lahir</font>
                 </td>
                 <td width="1%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                     <font style="font-size: 12pt;" color="#000000">{{ $raw->tempatlahir }}, {{ $raw->tgllahir }}</font>
                 </td>
             </tr>
             <tr>
                 <td width="29%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">Alamat</font>
                 </td>
                 <td width="1%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                     <font style="font-size: 12pt;" color="#000000">{{ $raw->alamatlengkap }}</font>
                 </td>
             </tr>
             <tr>
                 <td width="29%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">Meninggal tanggal</font>
                 </td>
                 <td width="1%" class="ontop">
                     <font style="font-size: 12pt;" color="#000000">:</font>
                 </td>
                 <td width="70%">
                    <font style="font-size: 12pt;" color="#000000">
                        {{ App\Traits\Valet::getDateIndo(\Carbon\Carbon::parse($raw->tglmeninggal)->format('Y-m-d')) }} {{\Carbon\Carbon::parse($raw->tglmeninggal)->format('h:i')}}
                    </font>
                 </td>
             </tr>

             <tr>
                 <td width="100%" height="5" colspan="3"></td>
             </tr>
             <tr>
                 <td width="100%" colspan="3">
                     <font style="font-size: 12pt;" color="#000000">Demikian surat keterangan ini untuk dilakukan penguburan</font>
                 </td>
             </tr>
         </table>
     </td>
 </tr>
 <tr>
     <td style="padding-top:50px">
         <table width="85%" cellspacing="0" cellpadding="0" border="0" align="center">
             <tr>
                 <td width="50%"></td>
                 <td width="50%" align="center">
                     <font style="font-size: 12pt;" color="#000000">Cirebon,
                         {{ App\Traits\Valet::getDateIndo(date('Y-m-d')) }}
                     </font>
                     <br><br>
                     <font style="font-size: 12pt;" color="#000000">Dokter Yang Menerangkan</font>
                 </td>
             </tr>
             @if($dataForensik->namalengkap)
             <tr>
                 <td width="50%"></td>
                 <td width="50%" align="center">
                     <font style="font-size: 12pt;font-weight:bold;text-decoration: underline;" color="#000000">
                     </font>
                     <br>
                     <img src="data:image/jpeg;base64,{{ $qrcode }}" width="80px" border="0">
                 </td>
             </tr>
             <tr>
                 <td width="50%">
                 </td>
                 <td width="50%" style="align-items: center;text-align: center">
                     <font style="font-size: 12pt;" color="#000000">{{ $dataForensik->namalengkap }}</font>
                     <br>
                 </td>
             </tr>
             @else
             <tr>
                 <td width="50%"></td>
                 <td width="50%" align="center" color="#000000">
                     <p>Dokter tidak ditemukan</p>
                 </td>
             </tr>
             @endif
         </table>
     </td>
 </tr>
 @endsection
