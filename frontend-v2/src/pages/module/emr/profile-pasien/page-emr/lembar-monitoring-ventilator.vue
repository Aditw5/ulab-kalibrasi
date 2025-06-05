<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>

  <VButton type="button" class="m-4" outlined icon="feather:plus"
    @click="tambahPersetujuan" color="success" v-tooltip-prime.top="'Tambah '">Tambah
  </VButton>

  <Fieldset v-for="(item, indexData) in input.details" :legend="`Peresetujuan/Penolakan ke ${item.no}`" :toggleable="true">
    <VButton type="button" class="m-4" outlined icon="feather:trash"
    @click="hapusPersetujuan(indexDetail)" color="danger" v-tooltip-prime.top="'Tambah '">Hapus Daftar
  </VButton>
    <VCard>
      <h3 class="title is-5 mb-2" style="text-align: center">VENTILATOR ASSOCIATED PNEUMONIA (VAP)</h3>
      <p>
      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">NAMA PASIEN</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.namapasi" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">NO REGISTER</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.registering" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">RUANGAN</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.ruangan1" />
            </VControl>
          </VField>
        </div>
      </div>


      </p>
    </VCard>

    <vCard>
      <h3 class="title is-5 mb-2" style="text-align: center">VERIFIKASI DATA INSTRUKSI DOKTER DENGAN FORMULIR DAN DARAH YANG ADA</h3>
      <p>
        <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
          <thead>
            <tr>
    <th rowspan="3" style="width: 5%; background-color: #add8e6; text-align: center;">No</th>
    <th rowspan="3" style="width: 30%; background-color: #add8e6; text-align: center;">Komponen</th>
    <th colspan="22" style="background-color: #add8e6; text-align: center;">TANGGAL</th>
</tr>

          <tr>

            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80%;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
            <th colspan="2"><input type="text" placeholder="Masukkan Tanggal" style="width: 80px;" /></th>
          </tr>

    <tr>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Ya</th>
      <th style="width: 7%;">Tidak</th>
      
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>1</td>
    <td>Posisi Kepala 30 - 45°</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.jenis3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>2</td>
    <td>Oral hygiene dengan Chlorhexidine 0.2% setiap 4-6 Jam</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.oral20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>3</td>
    <td>Sikat gigi/Hari</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gigi20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>4</td>
    <td>Hand Hygiene</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.hand20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>5</td>
    <td>Manajemen Sekresi oral dan Trakheal</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.sekresi20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>6</td>
    <td>Penggunaan APD saat suction</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.apd20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>7</td>
    <td>Pengkajian setiap hari "sedasi dan ekstubasi"</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.pengkajian20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>8</td>
    <td>
      <strong>Dekontaminasi peralatan:</strong>
      <ul>
        <li>1. Segera buang cairan kondensasi</li>
        <li>2. Ganti sirkuit ventilator jika kotor</li>
        <li>3. Saat intubasi laryngoscope di alkoholise</li>
        <li>4. Kebersihan lingkungan pasien</li>
      </ul>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dekontaminasi20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>9</td>
    <td>Jarak Tempat tidur lebih dari 2 meter</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.tidur20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>10</td>
    <td>Penggunaan propilaktik DVT</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.dvt20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
  <tr>
    <td>11</td>
    <td>Penggunaan propilatik peptic ulcer</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer10" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer11" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer12" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer13" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer14" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer5" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer6" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer7" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer8" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer9" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer15" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer16" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer17" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer18" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer19" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.ulcer20" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

        </tbody>
      </table>
      </p>
    </vCard>
  </Fieldset>

  <div class="column">
    <!-- form emr -->
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_produk: any = ref([])
const d_Tindakan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('daftarTransfusiDarah') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{ no: 1 }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const tambahPersetujuan = () => {
  input.value.details.unshift({
      no : input.value.details.length + 1,
      tglPembuatanBawaPulang : new Date(),
    tglPembuatanObatIGD : new Date(),
    alamatlengkap : props.pasien.alamatlengkap,
    tglregis : props.registrasi.tglregistrasi,
    dokterDPJP : props.registrasi.dokter,
    namapasienKeluarga : props.pasien.namapasien,
    namaPasien : props.pasien.namapasien,
    pasienYangMenyatakan : props.pasien.namapasien,
    jenisKelamin : props.pasien.jeniskelamin,
    tglLahir : props.pasien.tgllahir,
    alamatPasien : props.pasien.alamatlengkap,
  })
}

const hapusPersetujuan = (indexDetail: any) => {
  input.value.details.splice(indexDetail, 1)
}

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].ttdsaksi1) {
          H.tandaTangan().set("signatureSaksi1", response[0].ttdsaksi1)
        }
        if (response[0].ttdsaksi2) {
          H.tandaTangan().set("signatureSaksi2", response[0].ttdsaksi2)
        }
        if (response[0].ttdyangMenyatakan) {
          H.tandaTangan().set("signatureYangMenyatakan", response[0].ttdyangMenyatakan)
        }
        if (response[0].ttdDokteryangMenyatakan) {
          H.tandaTangan().set("signatureDokterYangMenyatakan", response[0].ttdDokteryangMenyatakan)
        }
        if (response[0].ttdPasienyangMenyatakan) {
          H.tandaTangan().set("signaturePasienYangMenyatakan", response[0].ttdPasienyangMenyatakan)
        }
        if (response[0].tandaTanganPerawatObatRawatInap) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawatObatRawatInap)
        }
        if (response[0].tandaTanganApotekerObatRawatInap) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganApotekerObatRawatInap)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdsaksi1 = H.tandaTangan().get("signatureSaksi1")
  object.ttdsaksi2 = H.tandaTangan().get("signatureSaksi2")
  object.tandaTanganPerawatObatRawatInap = H.tandaTangan().get("signature_1")
  object.tandaTanganApotekerObatRawatInap = H.tandaTangan().get("signature_2")
  object.ttdyangMenyatakan = H.tandaTangan().get("signatureYangMenyatakan")
  object.ttdDokteryangMenyatakan = H.tandaTangan().get("signatureDokterYangMenyatakan")
  object.ttdPasienyangMenyatakan = H.tandaTangan().get("signaturePasienYangMenyatakan")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const addNewItemObatRawatInap = () => {
  input.value.detailObatRawatInap.push({
    no: input.value.detailObatRawatInap[input.value.detailObatRawatInap.length - 1].no + 1,
  });
}
const removeItemObatRawatInap = (index: any) => {
  input.value.detailObatRawatInap.splice(index, 1)
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchTindakan = (filter: any) => {
  let nama = filter ? `?filterTindakan=${filter.query}` : ''
  useApi().get(`dashboard/data-tindakan${nama}`).then((response) => {
    d_Tindakan.value = response
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.details.forEach((element) => {
    element.tglPembuatanBawaPulang = new Date()
    element.tglPembuatanObatIGD = new Date()
    element.alamatlengkap = props.pasien.alamatlengkap
    element.tglregis = props.registrasi.tglregistrasi
    element.dokterDPJP = props.registrasi.dokter
    element.namapasienKeluarga = props.pasien.namapasien
    element.namaPasien = props.pasien.namapasien
    element.pasienYangMenyatakan = props.pasien.namapasien
    element.jenisKelamin = props.pasien.jeniskelamin
    element.tglLahir = props.pasien.tgllahir
    element.alamatPasien = props.pasien.alamatlengkap
  });
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
  font-weight: bold;
}
</style>
