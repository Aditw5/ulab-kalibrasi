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


  <div class="column">
    <VButton type="button" class="m-4" outlined icon="feather:plus"
  @click="tambahCatatan" color="success" v-tooltip-prime.top="'Tambah '">Tambah
</VButton>
<Fieldset :legend="`Catatan Transfer ke ${data.no}`" :toggleable="true" v-for="(data, indexDetail) in input.details">
  <VCard>
    <div class="column is-offset-10 is-2">
      <VButton type="button" class="w-full" outlined icon="feather:trash"
        @click="hapusPersetujuan(indexDetail)" color="danger" v-tooltip-prime.top="'Tambah '">Hapus Baris
      </VButton>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="Ruang Asal">
                <AutoComplete v-model="data.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Pindah Ke">
                <AutoComplete v-model="data.namaruanganPindah" :suggestions="d_Ruangan"
                  @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="ketik untuk mencari..." />
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Waktu">
                <VDatePicker v-model="data.tanggalWaktuPindah" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField label="Dokter Yang Merawat">
                <AutoComplete v-model="data.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Diagnosa Utama">
                <AutoComplete v-model="data.namadiagnosa" :suggestions="d_Diagnosa" @complete="fetchDiagnosa($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Diagnosa Sekundar">
                <AutoComplete v-model="data.namadiagnosaSekunder" :suggestions="d_Diagnosa"
                  @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="ketik untuk mencari..." />
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-6">
              <span> Alasan Dirawat : </span>
              <VField>
                <VTextarea rows="2" placeholder="Alasan Dirawat....." v-model="data.AlasanDirawat"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <span> Alasan Pindah : </span>
              <VField>
                <VTextarea rows="2" placeholder="Alasan Pindah....." v-model="data.AlasanPindah"></VTextarea>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> Alasan Lain : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.alasanLainSementaraTitipan" label="Sementara/Kelas titipan"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.alasanLainPulangAtasPermintaanSendiri"
                          label="Pulang Atas Permintaan Sendiri" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-5">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.alasanLainIjinMeninggalkanRS"
                          label="Ijin Meninggalkan Rumah Sakit Selama Periode Waktu Tertentu" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-6">
              <span> Hasil Pemeriksaan Selama di Rawat (Pemeriksaan Fisik & Penunjang Yang Mendukung Diagnosis) :
              </span>
              <VField class="mt-2">
                <VTextarea rows="2" placeholder="Hasil Pemeriksaan Selama di Rawat....."
                  v-model="data.hasilPemeriksaanSelamaRawat"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <span> Prosedur/Tindakan Yang Sudah Dilakukan : </span>
              <VField class="mt-2">
                <VTextarea rows="2" placeholder="Prosedur/Tindakan Yang Sudah Dilakukan....."
                  v-model="data.prosedurTindakanYangSudahDilakukan"></VTextarea>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>
    <div class="column is-12">
      <Fieldset class="p-fieldsets" legend="Kondisi Pasien Saat Pindah" :toggleable="true">
        <div class="columns is-multiline mb-5">
          <div class="column is-12 P-0">
            <div class="column is-12">
              <span> Kesadaran : </span>
            </div>
            <div class="column is-12">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="data.KesadaranCM" label="CM" class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="data.KesadaranApatis" label="Apatis" class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="data.KesadaranDelirium" label="Delirium" class="p-0" color="primary"
                        square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="data.KesadaranSopor" label="Sopor" class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VField>
                    <VControl raw subcontrol>
                      <VCheckbox v-model="data.KesadaranGCS" label="GCS" class="p-0" color="primary" square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="columns is-multiline mb-5">
          <div class="column is-12">
            <div class="column is-6 pb-0">
              <div class="columns">
                <div class="column is-1">
                  <h1 class="mb-3 emr" style="text-align: center;">No</h1>
                </div>
                <div class="column is-one-quarter" style="text-align: center;">
                  <h1 class="mb-3 emr">Parameter</h1>
                </div>
                <div class="column is-5" style="text-align: center;">
                  <h1 class="mb-3 emr">Nilai</h1>
                </div>
              </div>
              <div class="columns pb-4" v-for="(data) in kesadaran">
                <div class="column is-1 pt-0 " style="text-align: center;">
                  <h1 class="mb-3 emr"> {{ data.no }}</h1>
                </div>
                <div class="column  is-one-quarter pt-0" style="text-align: center;">
                  <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                </div>
                <div class="column is-5" style="text-align: end;">
                  <div class="columns is-multiline pb-5">
                    <div class="column is-4 pt-0" v-for="(pilihan) in data.nilai">
                      <VField>
                        <VControl raw subcontrol>
                          <VCheckbox class="p-0" :true-value="pilihan.poin" v-model="input[pilihan.model]"
                            :label="pilihan.poin" color="primary" />
                        </VControl>
                      </VField>
                    </div>
                  </div>

                </div>

              </div>
            </div>
          </div>
          <div class="columns is-multiline p-3">
            <div class="column is-3" v-for="(data, i) in vitalSign ">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> {{ data.label }} : </span>
                </div>
                <div class="column is-12">
                  <VPlaceloadText :lines="1" v-if="isLoadingVitalSign" />
                  <VField addons v-else>
                    <VControl>
                      <VInput type="text" class="input" :placeholder="data.label" v-model="input[data.model]" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>{{ data.addon }} </VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Skor EWS : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Skor EWS" v-model="data.SkorEWS" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>EWS</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline">
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Penggunaan Oksigen : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Penggunaan Oksigen" v-model="data.penggunaanOksien" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>L/mnt</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Cairan Parental : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Cairan Parental" v-model="data.cairanParental" />
                    </VControl>
                    <VControl style="70px" class="field-addon-body">
                      <VButton static>cc/24Jam</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-3">
              <div class=" columns is-multiline">
                <div class="column is-12" style="margin-top:0.5rem">
                  <span> Transfusi : </span>
                </div>
                <div class="column is-12">
                  <VField addons>
                    <VControl>
                      <VInput type="text" class="input" placeholder="Transfusi" v-model="data.transfusi" />
                    </VControl>
                    <VControl class="field-addon-body">
                      <VButton static>cc</VButton>
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span> Penggunaan Cateter : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.penggunaanCateterAda" label="Ada" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.penggunaanCateterTidak" label="Tidak" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4" v-if="data.penggunaanCateterAda">
                    <VField label="Tanggal Waktu">
                      <VDatePicker v-model="data.tanggalWaktuCateter" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                          <VField>
                            <VControl icon="feather:calendar" fullwidth>
                              <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                            </VControl>
                          </VField>
                        </template>
                      </VDatePicker>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4">
              <span> Diet : </span>
              <VField>
                <VTextarea rows="2" placeholder="Diet....." v-model="data.diet"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <span> Mobilisasi : </span>
              <VField>
                <VTextarea rows="2" placeholder="Mobilisasi....." v-model="data.mobilisasi"></VTextarea>
              </VField>
            </div>
            <div class="column is-4">
              <span> Edukasi : </span>
              <VField>
                <VTextarea rows="2" placeholder="Edukasi....." v-model="data.edukasi"></VTextarea>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12">
              <Fieldset :toggleable="true" legend="Obat - Obatan">
                <div class="column" style="overflow: auto;">
                  <table class="tg">
                    <thead>
                      <tr>
                        <td class="tg-0lax text-center">No</td>
                        <td class="tg-0lax text-center">Nama Obat</td>
                        <td class="tg-0lax text-center">Dosis</td>
                        <td class="tg-0lax text-center">Obat dilanjutkan saat masuk?</td>
                        <td class="tg-0lax text-center">Aksi</td>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(dataObat, index) in data.detailObatDibawaPasien" :key="index">
                        <td>{{ dataObat.no }}</td>
                        <td width="35%">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="dataObat.produk" :suggestions="d_produk"
                                @complete="fetchProduk($event)" :optionLabel="'namaproduk'" :dropdown="true"
                                :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                :field="'namaproduk'" placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl icon="feather:bookmark">
                              <VInput type="text" v-model="dataObat.dosis" placeholder="Dosis..." class="input" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="dataObat.DilanjutkanSaatMasuk" class="pt-1 pb-1 "
                                  true-value="Sudah Mengerti" label="Ya" color="primary" square />
                              </VControl>
                            </VField>
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="dataObat.DilanjutkanSaatMasuk" class="pt-1 pb-1 "
                                  true-value="Re-Edukasi" label="Tidak" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                        <td class="td-fkprj" style="vertical-align: inherit;">
                          <div class="column">
                            <VIconButton type="button" raised circle icon="feather:plus"
                              @click="addNewItemObatDibawaPulang(indexDetail)" color="info" v-tooltip.bubble="'Tambah '">
                            </VIconButton>
                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                              @click="removeItemObatDibawaPulang(indexDetail, index)" color="danger">
                            </VIconButton>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </Fieldset>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12">
              <VField class="mt-2">
                <VTextarea rows="2" placeholder="............." v-model="data.ketObatObatan"></VTextarea>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-6">
              <span>Riwayat Nyeri</span>
              <VField class="mt-2">
                <VTextarea rows="2" placeholder="Riwayat Nyeri......" v-model="data.riwayatAlergi"></VTextarea>
              </VField>
            </div>
            <div class="column is-6">
              <span>Program Nyeri</span>
              <VField class="mt-2">
                <VTextarea rows="2" placeholder="Program Nyeri......." v-model="data.programNyeri"></VTextarea>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>

    <div class="column is-12">
      <Fieldset :toggleable="true" legend="Riwayat Hambatan">
        <div class="column is-12 p-2">
          <div class="columns is-multiline">
            <div class="column" style="overflow: auto;">
              <div class="columns is-multiline">
                <h1>Keterangan :</h1>
              </div>
              <div class="columns is-multiline">
                <h1 class="mr-3">M : Mandiri</h1>
                <h1 class="mr-3">PP : Perlu Pendamping</h1>
                <h1 class="mr-3">TM : Tidak Mampu</h1>
              </div>
              <table class="tg">
                <thead>
                  <tr>
                    <th class="tg-0lax text-center" colspan="3">Riwayat Hambatan</th>
                    <th class="tg-0lax text-center" colspan="4">Tingkat Kemampuan Fungsi</th>
                  </tr>
                  <tr>
                    <th class="tg-0lax">Penglihatan</th>
                    <th class="tg-0lax">Pendengaran</th>
                    <th class="tg-0lax">Komunikasi</th>
                    <th class="tg-0lax"></th>
                    <th class="tg-0lax">M</th>
                    <th class="tg-0lax">PP</th>
                    <th class="tg-0lax">TM</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td width="170px">
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.adeKuat" class="pt-1 pb-1 " label="Adekuat" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.kacaMata" class="pt-1 pb-1 " label="Kaca Mata" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.buta" class="pt-1 pb-1 " label="Buta" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="190px">
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.earingAidSD" class="pt-1 pb-1 " label="Earing Aid S/D"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.tuliSebagian" class="pt-1 pb-1 " label="Tuli Sebagian"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.TuliTotal" class="pt-1 pb-1 " label="Tuli Total" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="200px">
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.bicaraNormal" class="pt-1 pb-1 " label="Bicara Normal"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.nonVerbal" class="pt-1 pb-1 " label="Non Verbal" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.afasia" class="pt-1 pb-1 " label="Afasia" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.tidakBisaBacaTulis" class="pt-1 pb-1 "
                              label="Tidak Bisa Baca Tulis" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="200px">
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.bedActivity" class="pt-1 pb-1 " label="Bed Activity"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.personalHygiene" class="pt-1 pb-1 " label="Personal Hygiene"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.dressing" class="pt-1 pb-1 " label="Dressing" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.eating" class="pt-1 pb-1 " label="Eating" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="data.transfer" class="pt-1 pb-1 " label="Transfer" color="primary"
                              square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="130px">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.M1" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.M2" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.M3" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.M4" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.M5" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="130px">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.PP1" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.PP2" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.PP3" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.PP4" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.PP5" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="130px">
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.TM1" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.TM2" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.TM3" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.TM4" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl>
                            <VCheckbox v-model="data.TM5" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column is-12">
      <VCard class="p-3">
        <div class="column is-12 px-2">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <span> Barang-barang yang Diserahkan : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanrekamMedisLengkap" label="Rekam Medis Lengkap"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanthoraxFoto" label="Thorax Foto" class="p-0"
                          color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanUSG" label="USG" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanctScan" label="CT Scan" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanecho" label="Echo" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanEKG" label="EKG" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanlab" label="Lab" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="data.barngYangDiserahkanLain" label="Lain-lain" class="p-0" color="primary"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3" v-if="data.barngYangDiserahkanLain">
                    <VField class="mt-2">
                      <VTextarea rows="2" placeholder="Lain-Lain....." v-model="data.barngYangDiserahkanLainKet">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12">
              <span> Catatan Khusus :
              </span>
              <VField class="mt-2">
                <VTextarea rows="2" placeholder="Catatan Khusus....." v-model="data.catatanKhusus"></VTextarea>
              </VField>
            </div>
          </div>
        </div>
      </VCard>
    </div>

    <div class="columns is-multiline">
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold; text-align: center"> Yang Membuat, DPJP </h1>
          <div class="mt-6" style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="data.dokter"
                      :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.dokter ? data.dokter : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Dokter DPJP</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="data.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter DPJP..." class="mt-2"
                  @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Diterima, tanggal/pkl : Perawat Yang Menerima </h1>
          <VField>
            <VDatePicker v-model="data.tglPerawatTerima" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="data.petugasPerawatTerima"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.petugasPerawatTerima ? data.petugasPerawatTerima.label : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="data.petugasPerawatTerima" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..."
                  class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
      <div class="column is-4" style="margin-left: auto;">
        <VCard>
          <h1 style="font-weight: bold;"> Diantar, tanggal/pkl : Perawat Yang Menyerahkan </h1>
          <VField>
            <VDatePicker v-model="data.tglPerawatMenyerahkan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
          <div style="text-align: center">
            <!-- <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan> -->
            <img v-if="data.petugasPerawatMenyerahkan"
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.petugasPerawatMenyerahkan ? data.petugasPerawatMenyerahkan.label : '-')">
          </div>
          <div>
            <h1 class="p-0" style="font-weight: bold;">Petugas Perawat</h1>
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="data.petugasPerawatMenyerahkan" :suggestions="d_Pegawai"
                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..."
                  class="mt-2" @item-select="setTandaTangan($event)" />
              </VControl>
            </VField>
          </div>
        </VCard>
      </div>
    </div>
  </VCard>
</Fieldset>
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
import * as EMR from '../page-emr-plugins/pengkajian-awal-pj'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let vitalSign = ref(EMR.vitalSign())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    diagnosis?: any
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
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_produk: any = ref([])
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const input: any = ref({
  details : [{
    no: 1,
    detailObatDibawaPasien: [{ no: 1 }]
  }]
})
const COLLECTION: any = ref('CatatanPasienPindahTransferRuangRawatDokter') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

console.log(props)
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
    if (response.length) {
      if(response[0].details){
        input.value = response[0]
      }else{
        input.value.details = []
        response[0].no = 1
        input.value.details.push(response[0])
        console.log(input.value.details);
      }
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
        if (response[0].tandaTanganPerawatTerima) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPerawatTerima)
        }
        if (response[0].tandaTanganPerawatSerah) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPerawatSerah)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const tambahCatatan = () => {
  input.value.details.push({
    no: input.value.details.length + 1,
    detailObatDibawaPasien: [{ no: 1 }],
    tanggalWaktuRegistrasi : props.registrasi.tglregistrasi,
    tanggalWaktuKunjuangan : new Date(),
    tglPembuatan : new Date(),
    umur : props.pasien.umur,
    namaruangan : props.registrasi.namaruangan,
    namakelas : props.registrasi.namakelas,
    dokter : props.registrasi.dokter,
    // namadiagnosa = props.diagnosis.namadiagnosa

  })
  useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if(response != null){
      input.value.details[input.value.details.length - 1].beratBadan = response.beratBadan
      input.value.details[input.value.details.length - 1].tinggiBadan = response.tinggiBadan
      input.value.details[input.value.details.length - 1].IMT = response.IMT
      input.value.details[input.value.details.length - 1].lingkarPerut = response.lingkarPerut
      input.value.details[input.value.details.length - 1].tekananDarah = response.tekananDarah
      input.value.details[input.value.details.length - 1].pernapasan = response.pernapasan
      input.value.details[input.value.details.length - 1].suhu = response.suhu
      input.value.details[input.value.details.length - 1].nadi = response.nadi
    }
    isLoadingVitalSign.value =false;
  })
}

const hapusPersetujuan = (indexDetail: any) => {
  input.value.details.splice(indexDetail, 1)
}


const addNewItemObatDibawaPulang = (indexData: any) => {
  input.value.details[indexData].detailObatDibawaPasien.push({
    no: input.value.details[indexData].detailObatDibawaPasien.length + 1,
  });
}
const removeItemObatDibawaPulang = (indexDetail:any, index: any) => {
  input.value.details[indexDetail].detailObatDibawaPasien.splice(index, 1)
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganDokter = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
  object.tandaTanganPerawatTerima = H.tandaTangan().get("signature_2")
  object.tandaTanganPerawatSerah = H.tandaTangan().get("signature_3")
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


let kesadaran = ref([
  {
    "no": 1,
    "parameter": "E",
    "nilai": [
      {
        "model": "kesadaranE",
        "poin": "1"
      },
      {
        "model": "kesadaranE",
        "poin": "2"
      },
      {
        "model": "kesadaranE",
        "poin": "3"
      },
      {
        "model": "kesadaranE",
        "poin": "4"
      },
    ]
  },
  {
    "no": 2,
    "parameter": "M",
    "nilai": [
      {
        "model": "kesadaranM",
        "poin": "1"
      },
      {
        "model": "kesadaranM",
        "poin": "2"
      },
      {
        "model": "kesadaranM",
        "poin": "3"
      },
      {
        "model": "kesadaranM",
        "poin": "4"
      },
      {
        "model": "kesadaranM",
        "poin": "5"
      },
      {
        "model": "kesadaranM",
        "poin": "6"
      },
    ]
  },
  {
    "no": 3,
    "parameter": "V",
    "nilai": [
      {
        "model": "kesadaranV",
        "poin": "5"
      },
      {
        "model": "kesadaranV",
        "poin": "4"
      },
      {
        "model": "kesadaranV",
        "poin": "3"
      },
      {
        "model": "kesadaranV",
        "poin": "2"
      },
      {
        "model": "kesadaranV",
        "poin": "1"
      },
    ]
  },
])

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.details.forEach(element => {
    element.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
    element.tanggalWaktuKunjuangan = new Date()
    element.tglPembuatan = new Date()
    element.umur = props.pasien.umur
    element.namaruangan = props.registrasi.namaruangan
    element.namakelas = props.registrasi.namakelas
    element.dokter = props.registrasi.dokter
    element.petugasPerawatTerima = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    element.petugasPerawatMenyerahkan = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    // element.namadiagnosa = props.diagnosis.namadiagnosa

    isLoadingVitalSign.value = true;
    useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
    ).then((response) => {
      if(response != null){
        element.beratBadan = response.beratBadan
        element.tinggiBadan = response.tinggiBadan
        element.IMT = response.IMT
        element.lingkarPerut = response.lingkarPerut
        element.tekananDarah = response.tekananDarah
        element.pernapasan = response.pernapasan
        element.suhu = response.suhu
        element.nadi = response.nadi
      }
      isLoadingVitalSign.value =false;
    })

    // diet
    useApi().get(
      "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
      "&collection=pengkajianDokterGawatDarurat" + "&field=rencanaTerapiDietKet,rencanaTerapiDiet"
    ).then((response) => {
      if(response != null) {
        element.diet = response.rencanaTerapiDietKet
      }
    })
  })
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
</style>
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
}</style>
