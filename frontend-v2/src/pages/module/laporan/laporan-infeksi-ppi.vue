
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column pt-0 pb-0 is-3">
                <span>Periode Tanggal Masuk</span>
                <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks class="mt-2 mx-1 p-1">
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
              </div>
              <div class="column pt-0 pb-0 is-2">
                <span>Dokter</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Dokter..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0 pb-0">
                <span>Ruangan</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <span>Nama Pasien</span>
                <input type="text" v-model="item.namaPasien" v-on:keyup.enter="fetchData()" class="input"
                  placeholder="Search..." />
              </div>
              <div class="column mt-5" style="margin-left: auto:  !important;">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isPlaceLoad">
                  </VIconButton>
                </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <h3 class="title is-5 mb-2">Laporan Pasien Infeksi</h3>
      <div class="column" v-if="isPlaceLoad">
        <VPlaceloadWrap v-for="data in 25">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column" v-else>
        <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
         larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>

        <div v-else>
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="20"
            :rowsPerPageOptions="[20, 40, 60]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <template #header>
              <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                  to
                  Excel </VButton>
              </div>
            </template>

            <Column field="no" header="#" frozen></Column>
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
            <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 100px"></Column>
            <Column field="namainfeksippi" header="Jenis Infeksi" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tglmasuk" header="Tanggal Masuk" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tglmasuk) }}</span>
              </template>
            </Column>
            <Column field="tanggalinputinfeksi" header="Tanggal Infeksi" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tanggalinputinfeksi) }}</span>
              </template>
            </Column>
            <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namadiagnosa" header="Diagnosa Penyakit Menular" :sortable="true" style="min-width: 200px"></Column>
            <Column field="penularan" header="Jenis Transmisi" :sortable="true" style="min-width: 100px">
              <template #body="slotProps">
                <VTag v-if="slotProps.data.penularan == 'Airbone'" class="ml-4" color="danger" rounded></VTag>
                <VTag v-if="slotProps.data.penularan == 'Kontak'" class="ml-4" color="warning" rounded></VTag>
                <VTag v-if="slotProps.data.penularan == 'Droplet'" class="ml-4 custom-grey-tag" rounded></VTag>
                <VTag v-if="slotProps.data.penularan == 'Lain-lain'" class="ml-4" color="blue" rounded></VTag>
                <div v-if="slotProps.data.penularan === 'Airbone/Kontak'">
                  <VTag  class="ml-4" color="danger" rounded></VTag>
                  <VTag  class="ml-4" color="warning" rounded></VTag>
                </div>
                <div v-if="slotProps.data.penularan === 'Airbone/droplet'">
                  <VTag  class="ml-4" color="danger" rounded></VTag>
                  <VTag  class="ml-4 custom-grey-tag" rounded></VTag>
                </div>
                <div v-if="slotProps.data.penularan === 'Kontak/Droplet'">
                  <VTag  class="ml-4" color="warning" rounded></VTag>
                  <VTag  class="ml-4 custom-grey-tag" rounded></VTag>
                </div>
              </template>
            </Column>
          </DataTable>
        </div>
      </div>

    </VCard>
  </div>

</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan PPI - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
  filterTgl: {
    start: new Date(),
    end: new Date()
  },
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')


const fetchData = async () => {
  isPlaceLoad.value = true
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''

  await useApi().get(`pelayanan/get-laporan-infeksi-ppi${dari}${sampai}${ruanganfk}${dokter}${nama}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, NoRM: e.nocm, NoRegistrasi: e.noregistrasi, JenisInfeksi: e.namainfeksippi, TanggalMasuk: e.tglmasuk, TanggalInputInfeksi: e.tanggalinputinfeksi,
      Dokter: e.dokter, Ruangan: e.namaruangan, NamaDiagnosa: e.namadiagnosa, JenisTransmisi: e.penularan
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus();
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

fetchData()
</script>
