
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
                      <VField label="Bulan">
                        <Calendar v-model="item.bulan" selectionMode="single" :manualInput="false" class="w-100" :showIcon="true"
                          :showTime="false" view="month" :date-format="'MM'" />
                      </VField>
                  </div>
                  <div class="column is-6">
                    <VField label="Tahun">
                        <Calendar v-model="item.tahun" selectionMode="single" :manualInput="false" class="w-100" :showIcon="true"
                          :showTime="false" view="year" :date-format="'yy'" />
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
        <VPlaceholderPage v-if="dataSource.data.length == 0" title="Data Tidak di Temukan."
          subtitle="Silakan filter pencarian di tanggal lain" larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>

        <div v-else>
          <DataTable :value="dataSource.data" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25]" scrollable rowGroupMode="rowspan" groupRowsBy="periode"
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
                <Column header="Periode" :rowspan="3"/>
                <Column header="Jenis Pelayanan" :rowspan="3" headerStyle="min-width: 50%;" frozen :sortable="true"/>
                <Column :header="'Jenis Pasien'" :colspan="dataSource.kelompok.length*2+1"/>
                <Column :header="'Status Pasien'" :colspan="5"/>
              </Row>
              <Row>
                <Column v-for="kelompok in dataSource.kelompok" :key="kelompok.kelompokpasien" :header="kelompok.kelompokpasien" :colspan="2"/>
                <Column :header="'Total'" :rowspan="2"/>
                <Column :header="'Baru'" :colspan="2"/>
                <Column :header="'Lama'" :colspan="2"/>
                <Column :header="'Total'" :rowspan="2"/>
              </Row>
              <Row>
                <template v-for="(kelompok, index) in dataSource.kelompok">
                  <Column :header="index % 2 === 0 ? 'L' : 'P'" />
                </template>
                <template v-for="(kelompok, index) in dataSource.kelompok">
                  <Column :header="index % 2 === 0 ? 'L' : 'P'" />
                </template>
                <Column header="L"/>
                <Column header="P"/>
                <Column header="L"/>
                <Column header="P"/>
              </Row>
            </ColumnGroup>
            <Column field="periode" :rowspan="41"/>
            <Column field="ruangan"  style="min-width: 200px" frozen/>
            <template v-for="(kelompok, index) in dataSource.kelompok">
              <Column :field="`${kelompok.kelompokpasien}laki`" />
              <Column :field="`${kelompok.kelompokpasien}perempuan`"/>
            </template>
            <Column field="kelompokTotal" />
            <Column field="lakibaru"  />
            <Column field="lakilama"  />
            <Column field="perempuanbaru"  />
            <Column field="perempuanlama"  />
            <Column field="statusTotal" />
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
    bulan: new Date(),
    tahun: new Date()
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
  let bulan = 'bulan=' + moment(item.value.bulan).format('MM')
  let tahun = '&tahun=' + moment(item.value.tahun).format('YYYY')

  await useApi().get(`pelayanan/get-laporan-pengunjung-status-kelompok?${bulan}${tahun}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
      element.periode = `${moment(item.value.bulan).format('MMMM')}-${moment(item.value.tahun).format('YYYY')}`
    });
    dataSource.value = response
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
  let bulan = 'bulan=' + moment(item.value.bulan).format('MM')
  let tahun = '&tahun=' + moment(item.value.tahun).format('YYYY')
  H.printBlade(`pelayanan/get-laporan-pengunjung-status-kelompok?${bulan}${tahun}&isPDF=true`);
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
  () => [item.value.bulan,item.value.tahun],
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchData()
    }
  }
)
// watch(
//   () => item.value.tahun,
//   (newValue, oldValue) => {
//     if (newValue != oldValue) {
//       fetchData()
//     }
//   }
// )

fetchData()
</script>
