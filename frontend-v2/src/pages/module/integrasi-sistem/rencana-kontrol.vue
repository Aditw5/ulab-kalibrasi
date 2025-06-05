<template>
  <section>
    <VCard radius="smooth" elevated class="mb-3-min br-16">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VIconButton icon="feather:arrow-left" light dark-outlined @click="backPage()" />
          <span class="title-emr ml-3">Rencana Kontrol / Rencana Inap</span>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <TabView v-model:activeIndex="activeIdx">
            <TabPanel header="List Rencana Kontrol/RI">
              <div class="columns is-multiline">
                <div class="column is-2">
                  <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                    <VLabel>Filter</VLabel>
                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                      <Dropdown v-model="input.filter" :options="d_Filter" :optionLabel="'nama'" class="is-rounded"
                        placeholder="Filter" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField>
                    <VLabel>Tanggal</VLabel>
                    <VDatePicker v-model="input.filterTgl" is-range color="pink" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>

                  </VField>
                </div>
                <!-- <div class="column is-3">
                  <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
                    <VLabel>Tahun</VLabel>
                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                      <Dropdown v-model="input.tahun" :options="d_Tahun" :optionLabel="'nama'" class="is-rounded"
                        placeholder="Tahun" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3">
                  <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0" v-slot="{ id }">
                    <VLabel>Bulan</VLabel>
                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                      <Dropdown v-model="input.bulan" :options="d_Bulan" :optionLabel="'nama'" class="is-rounded"
                        placeholder="Bulan" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                  </VField>
                </div> -->
                <div class="column is-2">
                  <VIconButton icon="feather:search" @click="cariSKD" color="success" raised :loading="isLoadingSKD"
                    circle class="mt-5">
                  </VIconButton>
                </div>
                <div class="column is-12">
                  <DataTable :value="dataSourceSPRI" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                    v-model:filters="filters"
                    :globalFilterFields="['noSuratKontrol', 'namaJnsKontrol', 'namaPoliAsal', 'nama', 'noKartu']"
                    filterDisplay="menu" tableStyle="min-width: 50rem">
                    <template #header>
                      <div class="flex justify-content-end">
                        <span class="p-input-icon-left">
                          <i class="pi pi-search" />
                          <InputText v-model="filters.global.value" placeholder="Keyword Search" />
                        </span>
                      </div>
                    </template>
                    <template #empty> No data found. </template>
                    <Column header="Aksi" style="width: 220px">
                      <template #body="slotProps">
                        <VIconButton icon="feather:printer" @click="cetak(slotProps.data)" color="info" raised circle
                          :loading="slotProps.data.isLoading" class="mr-2">
                        </VIconButton>
                        <VIconButton icon="feather:edit" @click="edit(slotProps.data)" color="warning" raised circle
                          class="mr-2">
                        </VIconButton>
                        <VIconButton icon="feather:trash" @click="hapus(slotProps.data.noSuratKontrol)" color="danger"
                          raised circle class="mr-2">
                        </VIconButton>
                      </template>
                    </Column>
                    <Column field="noSuratKontrol" header="No Surat" style="width: 150px"></Column>
                    <Column field="namaJnsKontrol" header="Kontrol/Inap" style="width: 150px"></Column>
                    <Column field="tglRencanaKontrol" header="Tgl Rencana Kontrol" style="width: 150px">
                    </Column>
                    <Column field="tglTerbitKontrol" header="Tgl Entri" style="width: 150px"></Column>
                    <Column field="noSepAsalKontrol" header="No SEP Asal" style="width:150px"></Column>
                    <Column field="namaPoliAsal" header="Poli Asal" style="width: 150px"></Column>
                    <Column field="namaPoliTujuan" header="Poli Tuju" style="width: 150px"></Column>
                    <Column field="namaDokter" header="DPJP" style="width: 150px"></Column>
                    <Column field="terbitSEP" header="Terbit SEP" style="width: 100px">
                      <template #body="slotProps">
                        <VTag class="mr-1 mb-1" :color="slotProps.data.terbitSEP == 'Sudah' ? 'success' : 'error'"
                          :label="slotProps.data.terbitSEP" />
                      </template>
                    </Column>
                    <Column field="noKartu" header="No Kartu" style="width: 150px"></Column>
                    <Column field="nama" header="Nama Lengkap" style="width: 150px"></Column>
                    <Column field="jnsPelayanan" header="Jenpel" style="width: 150px"></Column>
                  </DataTable>
                </div>
              </div>
            </TabPanel>
            <TabPanel header="Buat Baru">
              <div class="columns is-multiline">
                <div class="column is-3">
                  <VControl>
                    <VLabel class="required-field">Pilih</VLabel>
                    <div class="columns is-multiline">
                      <div class="column is-6">
                        <VRadio v-model="input.jenis" :value="2" :label="'Rencana Kontrol'" name="jenisKntrl" square
                          color="primary" />
                      </div>
                      <div class="column is-6">
                        <VRadio v-model="input.jenis" :value="1" :label="'Rencana Rawat Inap'" name="jenisKntrl" square
                          color="primary" />
                      </div>
                    </div>
                  </VControl>
                </div>
                <div class="column is-3" v-if="input.jenis == 2">
                  <VField>
                    <VLabel class="required-field">No. SEP</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="input.noSEP" placeholder="No. SEP" class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3" v-if="input.jenis == 1">
                  <VField>
                    <VLabel class="required-field">No. Kartu</VLabel>
                    <VControl icon="feather:user">
                      <VInput type="text" v-model="input.noKartu" placeholder="No. Kartu" class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-2">
                  <VIconButton icon="feather:search" @click="cariPasien" color="success" raised :loading="isLoadingPasien"
                    circle class="mt-5">
                  </VIconButton>
                </div>
                <div class="column is-12">
                  <div class="columns is-multiline" v-if="peserta.nama">
                    <div class="column is-4">

                      <div class="s-card mt-5" style=" border-top: 3px solid var(--danger);">
                        <h3 class="title is-5 ">
                          <span> {{ peserta.nama }}</span>

                        </h3>
                        <p style=" font-size: 1rem;" class="mt-3-min"> NO RM : {{ peserta.mr.noMR }}</p>

                        <div class="boxx boxx-widget widget-user-2">
                          <div class="widget-user-header" v-if="isLoadingPasien">
                            <VFlexTableCell :column="{ grow: true, media: true }">

                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                              <VPlaceloadText :lines="2" width="80%" last-line-width="20%" class="mx-2" />
                            </VFlexTableCell>

                          </div>

                          <ul class="list-group list-group-unbordered" v-if="!isLoadingPasien">
                            <li class="list-group-item">
                              <span class="fas fa-address-card"></span> <a title="NIK" class="pull-right-container">{{
                                peserta.nik }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-credit-card"></span> <a title="No.Kartu Bapel JKK"
                                class="pull-right-container">{{ peserta.noKartu }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-calendar"></span> <a title="Tanggal Lahir"
                                class="pull-right-container">{{ peserta.tglLahir }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-info-circle"></span> <a title="PISA" class="pull-right-container">{{
                                peserta.statusPeserta.keterangan
                              }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-hospital-user"></span> <a title="Hak Kelas Rawat"
                                class="pull-right-container">{{ peserta.hakKelas.keterangan }} </a>

                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-stethoscope"></span> <a title="Faskes Tingkat 1"
                                class="pull-right-container">{{ peserta.provUmum.kdProvider }} - {{
                                  peserta.provUmum.nmProvider }}</a>

                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-calendar-plus"></span> <a title="TMT dan TAT Peserta"
                                class="pull-right-container">{{
                                  peserta.tglTMT }}
                                s.d
                                {{ peserta.tglTAT }}</a>

                            </li>
                            <li class="list-group-item">
                              <span class="fas fa-id-card-alt"></span> <a title="Jenis Peserta"
                                class="pull-right-container">{{ peserta.jenisPeserta.keterangan
                                }}</a>

                            </li>
                          </ul>

                        </div>
                      </div>
                      <div class="s-card mt-2" style=" border-top: 3px solid var(--info);" v-if="sep.noSep">
                        <h3 class="title is-5 ">
                          <span> SEP</span>

                        </h3>

                        <div class="boxx boxx-widget widget-user-2">

                          <ul class="list-group list-group-unbordered">
                            <li class="list-group-item">
                              <span class="fa fa-sort-asc"></span> <a title="NIK" class="pull-right-container">{{
                                sep.noSep }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-calendar"></span> <a title="Tgl.SEP" class="pull-right-container">{{
                                sep.tglSep }} </a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-medkit"></span> <a title="Tanggal Lahir" class="pull-right-container">{{
                                sep.jnsPelayanan }}</a>
                            </li>
                            <li class="list-group-item">
                              <span class="fa fa-heartbeat"></span> <a title="Diag" class="pull-right-container">{{
                                sep.diagnosa
                              }}</a>
                            </li>

                          </ul>

                        </div>
                      </div>
                    </div>
                    <div class="column is-8">
                      <div class="s-card mt-5 p-6" style=" border-top: 3px solid var(--orange);">
                        <h3 class="title is-5 head-sep ml-1">
                          <span v-if="noSuratKontrol"> {{ noSuratKontrol }}</span>
                        </h3>
                        <VField horizontal label="No. Rujukan Peserta" v-if="input.jenis == 2">
                          <span class="mr-2">{{ rujukanAktif.length == 0 ? '-' : rujukanAktif.noKunjungan }}</span>
                          <VTag :label="rujukanAktif.length == 0 ? '' : 'Exp : ' + rujukanAktif.tglExpired" :color="rujukanAktif.length == 0 ? '' : rujukanAktif.color_status" rounded
                              class="is-pulled-right"></VTag>
                        </VField>
                        <VField horizontal label="Tgl. Rencana Kontrol / Inap">
                          <Calendar v-model="input.tglRencanaKontrol" selectionMode="single" :manualInput="true"
                            style="width: 50%;" :showIcon="true" :showTime="false" hourFormat="24"
                            :date-format="'yy-mm-dd'" />
                          <!-- <VDatePicker v-model="input.tglRencanaKontrol" style="width: 50%;" trim-weeks mode="date">
                            <template #default="{ inputValue, inputEvents }">
                              <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                  <VInput :value="inputValue" v-on="inputEvents" />
                                </VControl>
                              </VField>
                            </template>
                          </VDatePicker> -->
                        </VField>

                        <VField horizontal label="Pelayanan" required>
                          <VControl icon="fas fa-ambulance" fullwidth>
                            <VInput type="text" placeholder="Pelayanan" autocomplete="off" v-model="input.pelayanan"
                              disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="No. Surat Kontrol" required>
                          <VControl icon="lnir lnir-hospital-alt-2" fullwidth>
                            <VInput type="text" placeholder="No. Surat Kontrol" autocomplete="off"
                              v-model="input.noSuratKontrol" disabled />
                          </VControl>
                        </VField>
                        <VField horizontal label="Sub/Spesialis" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:home" fullwidth class="prime-auto ">
                            <AutoComplete v-model="input.poliKontrol" :suggestions="d_Subspesialis"
                              @complete="fetchSupspesialis($event)" :optionLabel="'nama'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'nama'"
                              placeholder="ketik spesialis/subspesialis" @item-select="changeSpe(input.poliKontrol)" />
                            <!-- <Dropdown v-model="input.poliKontrol" :options="d_Subspesialis" :optionLabel="'namaPoli'"
                              placeholder="Sub/Spesialis" style="width: 100%;" :filter="true" @change="changeSpe(input.poliKontrol)" /> -->
                          </VControl>
                        </VField>
                        <VField horizontal label="DPJP Tujuan Kontrol / Inap" required
                          class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:users" fullwidth class="prime-auto">
                            <Dropdown v-model="input.kodeDokter" :options="d_dpjpLayan" :optionLabel="'namaDokter'"
                              placeholder="DPJP Tujuan Kontrol / Inap" style="width: 100%;" :filter="true" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="mt-2 is-pulled-right">
                        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="batal()">
                          Batal
                        </VButton>
                        <VButton type="button" color="warning" class="ml-2" rounded outlined raised icon="feather:printer"
                          @click="cetakSATU(noSuratKontrol)" v-if="noSuratKontrol" :loading="isLoadingCetak">
                          Cetak </VButton>
                        <VButton type="button" color="danger" class="ml-2" rounded outlined raised icon="feather:trash"
                          v-if="noSuratKontrol" @click="hapus(noSuratKontrol)">
                          Hapus </VButton>
                        <VButton type="button" color="primary" class="ml-2" rounded outlined raised icon="feather:save"
                          :loading="isLoading" @click="save()"> {{ input.noSuratKontrol ? 'Edit' : 'Simpan' }} </VButton>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </TabPanel>
          </TabView>
        </div>
      </div>
    </VCard>
  </section>
</template>
<script setup lang="ts">
import { ref, reactive, watch } from 'vue'
import { useRoute, useRouter, Router, } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dropdown from 'primevue/dropdown';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import InputText from 'primevue/inputtext';
import { useApi } from '/@src/composable/useApi'
import { FilterMatchMode } from 'primevue/api';
import sleep from '/@src/utils/sleep'
useHead({
  title: 'Rencana Kontrol - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const route = useRoute()
const activeIdx: any = ref(0)
const namappkRumahSakit = ref('')
const filters: any = ref({
  'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const d_Filter: any = ref([{ kode: 2, nama: 'Tgl Rencana Kontrol' }, { kode: 1, nama: 'Tgl Entri' }])
const input: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  filter: d_Filter.value[0],
  jenis: 1,
  // noKartu: '0000451793316',
  // noSEP: '1019R0010923V014504',
  tglRencanaKontrol: new Date()
})
const noSuratKontrol = ref()
const isLoadingPasien = ref(false)
const item: any = ref({})
const isLoadingSKD: any = ref(false)
const pasien: any = ref({})
const peserta: any = ref({
  hakKelas: {},
  jenisPeserta: {},
  mr: {},
  provUmum: {},
  statusPeserta: {},
  cob: {}
})
const sep: any = ref({})
const isLoadingCetak: any = ref(false)
const isLoading: any = ref(false)
const d_Bulan: any = ref(H.monthList())
const d_Tahun: any = ref(H.yearList())
const d_Subspesialis: any = ref([])
const d_dpjpLayan: any = ref([])
const dataSourceSPRI: any = ref([])
const rujukanAktif: any = ref([])
const cariSKD = () => {
  // let bulan = input.value.bulan.kode + 1

  // bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan
  let json = {
    // "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${input.value.tahun.kode}/Nokartu/${input.value.nobpjs}/filter/${input.value.filter.kode}`,
    "url": `RencanaKontrol/ListRencanaKontrol/tglAwal/${H.formatDate(input.value.filterTgl.start, 'YYYY-MM-DD')}/tglAkhir/${H.formatDate(input.value.filterTgl.end, 'YYYY-MM-DD')}/filter/${input.value.filter.kode}`,
    "method": "GET",
    "data": null
  }
  isLoadingSKD.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoadingSKD.value = false
    if (x.metaData.code == 200) {
      dataSourceSPRI.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })

}

const cetakSATU = async (e: any) => {
  let bulan :any= new Date(input.value.tglRencanaKontrol).getMonth() + 1
  if (bulan.length == 1) {
    bulan = '0' + bulan
  }
  let json = {
    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${new Date(input.value.tglRencanaKontrol).getFullYear()}/Nokartu/${input.value.noKartu}/filter/${2}`,
    "method": "GET",
    "data": null
  }
  isLoadingCetak.value = true
  let res = await useApi().postBPJS('/bridging/bpjs/tools', json)
  isLoadingCetak.value = false
  if (res.metaData.code == 200) {
    let stt = false
    for (let x = 0; x < res.response.list.length; x++) {
      const element = res.response.list[x];
      if (element.noSuratKontrol == e) {
        stt = true
        cetak(element)
        break
      }
    }
    if (!stt) {
      H.alert('error', 'Data tidak ada');
    }
  } else {
    H.alert('error', res.metaData.message);
  }


}
const edit = async (e: any) => {
  if (e.terbitSEP == 'Sudah') {
    H.alert('error', 'SEP sudah terbit tidak bisa di edit');
    return
  }
  activeIdx.value = 1
  await sleep(2000)
  if (e.namaJnsKontrol == 'SPRI') {
    input.value.jenis = 1
    input.value.noKartu = e.noKartu
  } else {
    input.value.jenis = 2
    input.value.noSEP = e.noSepAsalKontrol
    input.value.noKartu =  e.noKartu
  }


  input.value.noSuratKontrol = e.noSuratKontrol
  noSuratKontrol.value = e.noSuratKontrol
  input.value.tglRencanaKontrol = new Date(e.tglRencanaKontrol)

  await fetchSupspesialis({ query: e.namaPoliTujuan })
  for (let x = 0; x < d_Subspesialis.value.length; x++) {
    const element = d_Subspesialis.value[x];
    if (element.nama == e.namaPoliTujuan) {
      input.value.poliKontrol = element
      await changeSpe(element)
      break
    }
  }
  d_dpjpLayan.value.forEach(element => {
    if (element.kodeDokter == e.kodeDokter) {
      input.value.kodeDokter = element
    }
  });

  cariPasien()
}
const cetak = async (e: any) => {
  // let json = {
  //   "url": `/RencanaKontrol/noSuratKontrol/${nosurat}`,
  //   "method": "GET",
  //   "data": null
  // }
  let json = {
    "url": `Peserta/nokartu/${e.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  e.isLoading = true
  let response = await useApi().postBPJS('/bridging/bpjs/tools', json)
  e.isLoading = false
  let nosuratkontrol = e.noSuratKontrol
  let tglrencanakontrol = e.tglRencanaKontrol
  let txttglentrirencanakontrol = e.tglTerbitKontrol
  let noka = e.noKartu
  let nama = e.nama
  let tgllahir = response.response.peserta.tglLahir

  let namaPoliTujuan = e.namaPoliTujuan
  let jeniskelamin = response.response.peserta.sex
  let jnsKontrol = e.jnsKontrol
  let namaDokter = e.namaDokter
  let kddx = '-'
  let nmdpjpsepasal = '-';// e.namaDokter ? e.namaDokter : '-'
  let iddok = 'null'
  let dxawal = '-'

  if (e.noSepAsalKontrol != null) {
    let json = {
      "url": "sep/" + e.noSepAsalKontrol,
      "method": "GET",
      "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      if (x.metaData.code == 200) {
        dxawal = x.response.diagnosa

        cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
          nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
          jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


      } else {
        H.alert('error', x.metaData.message);
      }
    })

  } else {
    dxawal = '-'
    cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
      nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
      jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

  }
}
const cetakBladeSKDP = (nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
  nama, tgllahir, namappkRumahSakit, namaPoliTujuan, jeniskelamin, dxawal, jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok) => {


  H.printBlade('emr/cetak-spri?nosuratkontrol='
    + nosuratkontrol + '&tglrencanakontrol=' + tglrencanakontrol + '&txttglentrirencanakontrol=' + txttglentrirencanakontrol
    + '&noka=' + noka
    + '&tgllahir=' + tgllahir
    + '&namappkRumahSakit=' + namappkRumahSakit
    + '&namaPoliTujuan=' + namaPoliTujuan
    + '&jeniskelamin=' + jeniskelamin
    + '&dxawal=' + dxawal
    + '&jnsKontrol=' + jnsKontrol
    + '&kddx=' + kddx
    + '&namaDokter=' + namaDokter
    + '&nmdpjpsepasal=' + nmdpjpsepasal
    + '&iddok=' + iddok
    + '&nama=' + nama);
}
const cariPasien = () => {
  if (input.value.jenis == 1) {
    input.value.pelayanan = 'Rawat Inap'
  } else {
    input.value.pelayanan = 'Rawat Jalan'
  }
  let json: any = {}
  sep.value = {}
  if (input.value.jenis == 1) {
    json = {
      "url": `Peserta/nokartu/${input.value.noKartu.trim()}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
      "method": "GET",
      "data": null
    }
    isLoadingPasien.value = true
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      isLoadingPasien.value = false
      if (x.metaData.code == 200) {
        peserta.value = x.response.peserta

      } else {
        H.alert('error', x.metaData.message)
      }
    })
  } else {
    json = {
      "url": ` /RencanaKontrol/nosep/${input.value.noSEP.trim()}`,
      "method": "GET",
      "data": null
    }
    isLoadingPasien.value = true

    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
      isLoadingPasien.value = false
      if (x.metaData.code == 200) {
        sep.value = x.response
        let json2 = {
          "url": `Peserta/nokartu/${x.response.peserta.noKartu}/tglSEP/${H.formatDate(new Date(), 'YYYY-MM-DD')}`,
          "method": "GET",
          "data": null
        }
        useApi().postBPJS('/bridging/bpjs/tools', json2).then(async (xx) => {
          isLoadingPasien.value = false
          if (xx.metaData.code == 200) {
            peserta.value = xx.response.peserta
            const resrujukan = await apiRujukanAktif(x.response.peserta.noKartu)
            rujukanAktif.value = resrujukan
          } else {
            H.alert('error', xx.metaData.message)
          }
        })
      } else {
        H.alert('error', x.metaData.message)
      }
    })

  }


}
const fetchSubSpe = () => {
  let nomor = ''
  if (input.value.jenis == 1) {
    nomor = input.value.noKartu
  } else {
    nomor = input.value.noSEP
  }
  let json = {
    "url": `/RencanaKontrol/ListSpesialistik/JnsKontrol/${input.value.jenis}/nomor/${nomor}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }


  // isLoadingPasien.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    if (x.metaData.code == 200) {
      d_Subspesialis.value = x.response.list

    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const fetchSupspesialis = async (filter: any) => {
  if (!filter.query) return
  if (filter.query.length < 3) return
  let json = {
    "url": "referensi/poli/" + encodeURI(filter.query),
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(
    `/bridging/bpjs/tools`, json)
  if (res.metaData.code == 200) {
    d_Subspesialis.value = res.response.poli

  } else {
    H.alert('error', res.metaData.message)
  }
}
const changeSpe = async (e: any) => {
  let json = {
    "url": `/RencanaKontrol/JadwalPraktekDokter/JnsKontrol/${input.value.jenis}/KdPoli/${e.kode}/TglRencanaKontrol/${H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD')}`,
    "method": "GET",
    "data": null
  }
  await useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    if (x.metaData.code == 200) {
      d_dpjpLayan.value = x.response.list
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const save = () => {
  if (input.value.jenis == 1) {
    insertSPRI()
  } else {
    insertRencanaKontrol()
  }

}
const insertRencanaKontrol = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/Update`,
      "method": "PUT",
      "data": {
        "request": {
          "noSuratKontrol": input.value.noSuratKontrol,
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/insert`,
      "method": "POST",
      "data": {
        "request": {
          "noSEP": input.value.noSEP,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    let status
    if (x.metaData.code == 200) {
      let jsonAddAntrean = {
            nomorkartu: props.pasien.nobpjs,
            nik: props.pasien.noidentitas,
            nohp: props.pasien.nohp,
            kodepoli: input.value.poli.kdsubspesialisbpjs,
            norm: props.pasien.nocm,
            tanggalperiksa: H.formatDate(input.value.tanggal, 'YYYY-MM-DD'),
            kodedokter: input.value.dokter.kddokterbpjs,
            jeniskunjungan: 3,
            nomorreferensi: x.response.noSuratKontrol
          }

          useApi().post('/antrian-bpjs/antrol/antrean', jsonAddAntrean).then((data)=>{
            const jsonAntrol = {
              "kodebooking": data.response.kodebooking,
              "kodedokter": input.value.dokter.kddokterbpjs,
              "namadokter": input.value.dokter.namalengkap,
              "jampraktek": data.jampraktek,
              "jeniskunjungan": 3,
              "nomorreferensi": x.response.noSuratKontrol,
            }

              useApi().postNoMessage(`/bridging/antrol/sendDataAntrean`, jsonAntrol).then(async (response: any) => {
                if (response.metadata.code == 200) {
                  status = true
                  // send taks id 1
                  const jsont1 = {
                    "noregistrasifk": item.NOREC_PD,
                    "taskid": 1,
                    "waktu": item.waktutaksid1,
                  }
                  await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont1).then(async (rt1: any) => {
                    if (rt1.metaData.code == 200) {
                      // send taks id 2
                      const jsont2 = {
                        "noregistrasifk": item.NOREC_PD,
                        "taskid": 2,
                        "waktu": item.waktutaksid2,
                      }
                      await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont2).then(async (rt2: any) => {
                        if (rt2.metaData.code == 200) {
                          // send taks id 3
                          const jsont3 = {
                            "noregistrasifk": item.NOREC_PD,
                            "taskid": 3,
                            "waktu": new Date().getTime(),
                          }
                          await useApi().postNoMessage(`/bridging/antrol/sendTaskId`, jsont3).then(async (rt3: any) => {

                          })
                        }
                      })
                    }
                  })
                } else {
                  error = response.metadata.message
                  status = false
                }
              }).catch((e: any) => {
                status = false
              })
          })
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSuratKontrol
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const insertSPRI = () => {
  let json = {}

  if (input.value.noSuratKontrol) {
    json = {
      "url": `/RencanaKontrol/UpdateSPRI`,
      "method": "PUT",
      "data": {
        "request": {
          "noSPRI": input.value.noSuratKontrol,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  } else {
    json = {
      "url": `/RencanaKontrol/InsertSPRI`,
      "method": "POST",
      "data": {
        "request": {

          "noKartu": input.value.noKartu,
          "kodeDokter": input.value.kodeDokter.kodeDokter,
          "poliKontrol": input.value.poliKontrol.kode,
          "tglRencanaKontrol": H.formatDate(input.value.tglRencanaKontrol, 'YYYY-MM-DD'),
          "user": H.namaPegawai()
        }
      }
    }
  }
  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false
    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      noSuratKontrol.value = x.response.noSPRI
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const batal = () => {
  delete input.value.kodeDokter
  delete input.value.poliKontrol

  input.value = {
    filterTgl: {
      start: new Date(),
      end: new Date(),
    },
    filter: d_Filter.value[0],
    jenis: 1,
    tglRencanaKontrol: new Date()
  }
  pasien.value = {}
  sep.value= {}
}

const hapus = (nosurat) => {
  let json = {
    "url": `/RencanaKontrol/Delete`,
    "method": "DELETE",
    "data": {
      "request": {
        "t_suratkontrol": {
          "noSuratKontrol": nosurat,
          "user": H.namaPegawai()
        }
      }
    }
  }

  isLoading.value = true
  useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
    isLoading.value = false

    if (x.metaData.code == 200) {
      H.alert('success', x.metaData.message)
      delete noSuratKontrol.value
      batal()
    } else {
      H.alert('error', x.metaData.message)
    }
  })
}
const apiRujukanAktif = async (e: any) => {
  if (!e) {
    return []
  }

  let jsonPcare = {
    "url": `Rujukan/Peserta/${e}`,
    "method": "GET",
    "data": null
  }
  const res = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonPcare)
  if (res.metaData.code == 200) {
    res.response.rujukan.tglExpired = H.formatDate(new Date(new Date(res.response.rujukan.tglKunjungan).setDate(new Date(res.response.rujukan.tglKunjungan).getDate() + 90)), 'YYYY-MM-DD')
    res.response.rujukan.color_status = new Date(res.response.rujukan.tglExpired) <= new Date() ? 'danger' : 'success'
    return res.response.rujukan
  } else {
    let jsonRS = {
      "url": `Rujukan/RS/Peserta/${e}`,
      "method": "GET",
      "data": null
    }
    const res2 = await useApi().postBPJS(`/bridging/bpjs/tools`, jsonRS)
    if (res2.metaData.code == 200) {
      res2.response.rujukan.tglExpired = H.formatDate(new Date(new Date(res2.response.rujukan.tglKunjungan).setDate(new Date(res2.response.rujukan.tglKunjungan).getDate() + 90)), 'YYYY-MM-DD')
      res2.response.rujukan.color_status = new Date(res2.response.rujukan.tglExpired) <= new Date() ? 'danger' : 'success'
      return res.response.rujukan
    } else {
      return []
    }
  }
}
const init = () => {
  useApi().get(
    `general/ppk-bpjs`
  ).then((response) => {
    namappkRumahSakit.value = response.BPJS_namaPPKRujukan
  })
}
const backPage = ()=>{
  window.history.back()
}

watch(
  () => input.jenis,
  (newValue, oldValue) => {

  }
)
watch(
  () => activeIdx.value,
  (newValue, oldValue) => {
    batal()
  }
)

init()
if(route.query.nosep && route.query.nokartu){
  input.value.noKartu = route.query.nokartu
  input.value.noSEP = route.query.nosep
  input.value.jenis = 2
  cariPasien()
}
if(route.query.nosep == undefined && route.query.nokartu){
  input.value.noKartu = route.query.nokartu
  input.value.jenis = 1
  cariPasien()
}
// fetchSubSpe()
cariSKD()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/registrasi/pemakaian-asuransi.scss';
</style>
