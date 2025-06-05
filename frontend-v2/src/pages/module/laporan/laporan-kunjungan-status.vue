
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                      <label style=" margin-bottom: 2rem;">Tanggal Awal
                      </label>
                      <VField>
                          <VDatePicker v-model="item.tglAwal" mode="dateTime" style="width: 100%"
                              trim-weeks :max-date="new Date()" is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                  <VField>
                                      <VControl icon="feather:calendar" fullwidth>
                                          <VInput :value="inputValue" placeholder="Tanggal"
                                              v-on="inputEvents" />
                                      </VControl>
                                  </VField>
                              </template>
                          </VDatePicker>
                      </VField>
                  </div>
                  <div class="column is-6">
                      <label style=" margin-bottom: 2rem;">Tanggal Akhir
                      </label>
                      <VField>
                          <VDatePicker v-model="item.tglAkhir" mode="dateTime"
                              style="width: 100%" trim-weeks :max-date="new Date()" is24hr>
                              <template #default="{ inputValue, inputEvents }">
                                  <VField>
                                      <VControl icon="feather:calendar" fullwidth>
                                          <VInput :value="inputValue" placeholder="Tanggal"
                                              v-on="inputEvents" />
                                      </VControl>
                                  </VField>
                              </template>
                          </VDatePicker>
                      </VField>
                  </div>
                </div>
              </div>
              <!-- <div class="column mt-5" style="margin-left: auto:  !important;">
                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                  @click="fetchData()" :loading="isPlaceLoad">
                </VIconButton>
              </div> -->
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <h3 class="title is-5 mb-2">Laporan Kunjungan Pasien Rawat Jalan by Status</h3>
      <div class="column" v-if="isPlaceLoad">
        <VPlaceloadWrap v-for="data in 25">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column" v-else>
        <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
          subtitle="Silakan filter pencarian di tanggal lain" larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>

        <div v-else>
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <template #header>
              <div class="flex flex-wrap align-items-center gap-2">
                <!-- <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                  to
                  Excel </VButton> -->
                <VButton color="danger" class="mr-4 mb-3" icon="fas fa-file-pdf" raised @click="exportPDF()"> Export
                  to
                  PDF </VButton>
              </div>
            </template>

            <ColumnGroup type="header">
              <Row>
                <Column header="No" :rowspan="2"/>
                <Column header="Jenis Pelayanan" :rowspan="2" headerStyle="min-width: 50%;" frozen :sortable="true"/>
                <Column header="Baru" :colspan="2"/>
                <Column header="Lama" :colspan="2"/>
                <Column header="Total" :rowspan="2"/>
              </Row>
              <Row>
                <Column header="Laki-laki"/>
                <Column header="Perempuan"/>
                <Column header="Laki-laki"/>
                <Column header="Perempuan"/>
              </Row>
            </ColumnGroup>
            <Column field="no" frozen/>
            <Column field="namaruangan"  style="min-width: 200px"/>
            <Column field="lakibaru"  style="min-width: 200px"/>
            <Column field="lakilama"  style="min-width: 200px"/>
            <Column field="perempuanbaru"  style="min-width: 200px"/>
            <Column field="perempuanlama"  style="min-width: 200px"/>
            <Column field="total" style="min-width: 200px"/>
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
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
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
  title: 'Laporan Kunjungan - ' + import.meta.env.VITE_PROJECT,
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
    tglAwal: new Date().setHours(0, 0, 0, 0),
    tglAkhir: new Date()
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
let d_KelompokPasien: any = ref([])
let d_JenisPasien: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
  if (!item.value.qFilter) {
    return dataHutang.value
  }
  return dataHutang.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
      items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const fetchJenisPasien = async (filter: any) => {
    d_JenisPasien.value = [{value: 'LAMA', label:'LAMA'},{value: 'BARU', label:'BARU'},]
}

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.tglAwal).format('YYYY-MM-DD HH:mm')
  let tglAkhir = '&tglAkhir=' + moment(item.value.tglAkhir).format('YYYY-MM-DD HH:mm')

  await useApi().get(`pelayanan/get-laporan-pengunjung-status?${tglAwal}${tglAkhir}`).then((response: any) => {
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

const exportPDF = () => {
  let tglAwal = 'tglAwal=' + moment(item.value.tglAwal).format('YYYY-MM-DD HH:mm')
  let tglAkhir = '&tglAkhir=' + moment(item.value.tglAkhir).format('YYYY-MM-DD HH:mm')
  H.printBlade(`pelayanan/get-laporan-pengunjung-status?${tglAwal}${tglAkhir}&isPDF=true`);
}

const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, NoRM: e.nocm, JenisKelamin: e.jeniskelamin,
      Antrian: e.noantrian, Tanggal: e.tglregistrasi, Dokter: e.namalengkap, Ruangan: e.namaruangan,
      KelompokPasien: e.kelompokpasien, Status: e.statuspasien,
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

watch(
  () => [item.value.tglAwal,item.value.tglAkhir],
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchData()
    }
  }
)
// watch(
//   () => item.value.tglAkhir,
//   (newValue, oldValue) => {
//     if (newValue != oldValue) {
//       fetchData()
//     }
//   }
// )

fetchData()
</script>
