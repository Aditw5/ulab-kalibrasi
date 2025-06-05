
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-4 pt-0 pb-0">
                <span>Periode</span>
                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column pt-0 pb-0 is-4">
                <span>Dokter</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column is-11 ">
                  <input type="text" v-model="item.namaPasien" v-on:keyup.enter="fetchData()" class="input"
                    placeholder="Search..." />
                </div>
                <div class="column" style="margin-left: auto:  !important;">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isPlaceLoad">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <h3 class="title is-5 mb-2">Laporan Data Pasien Rehab Medik</h3>
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
              <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                  to
                  Excel </VButton>
              </div>
            </template>

            <Column field="no" header="#" frozen></Column>
            <Column field="tglregistrasi" header="Tanggal Registrasi" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
              </template>
            </Column>
            <Column field="namaruangan" header="Ruangan Pasien" :sortable="true" style="min-width: 200px"></Column>
            <!-- <Column field="realcost" header="Realcost" :sortable="true" style="min-width: 200px"></Column> -->
            <Column field="realcost" header="Realcost" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                    {{formatCurrency(slotProps.data.realcost)}}
                </template>
            </Column>
            <Column field="inacbg_totalgrouper" header="Total Grouper" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                    {{formatCurrency(slotProps.data.inacbg_totalgrouper)}}
                </template>
            </Column>
            <!-- <Column field="inacbg_totalgrouper" header="Total Grouper" :sortable="true" style="min-width: 200px"></Column> -->
            <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
            <Column field="noregistrasi" header="Noregistrasi" :sortable="true" style="min-width: 100px"></Column>
            <Column field="kelompokpasien" header="Kelompok Pasien" :sortable="true" style="min-width: 100px"></Column>
            <Column field="namakelas" header="Kelas" :sortable="true" style="min-width: 100px"></Column>
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
  title: 'Laporan Data Pasien rehab Medik - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const periode = ref('');
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})
const formatCurrency = (value) => {
  return H.formatRp(value, '')
}
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`pelayanan/get-laporan-data-rehab-medik?${tglAwal}${tglAkhir}${dokter}${nama}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isPlaceLoad.value = false

}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN DATA PASIEN REHAB MEDIK'],
    [periode.value],
    [],
    [
      'No', 'Tanggal Registrasi', 'NOCM', 'Nama Pasien', 'Ruangan Pasien', 'Realcost', 'Total Grouper', 'Dokter', 'Noregistrasi', 'Kelompok Pasien', 'Kelas'
    ],
    ...dataSource.value.map((e) => [
      e.no, e.tglregistrasi, e.nocm, maskNamaPasien(e.namapasien), e.namaruangan, e.realcost, e.inacbg_totalgrouper, e.dokter, e.noregistrasi, e.kelompokpasien, e.namakelas
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
    { wch: 25 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 10 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 10 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 10 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 10 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 10 } };

  worksheet['!merges'] = [mergeTitle, mergeSubtitle1, mergeSubtitle2, mergeSubtitle4, mergeSubtitle5];
  const workbook = XLSX.utils.book_new();
  XLSX.utils.book_append_sheet(workbook, worksheet, 'data');

  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_data_pasien_rehab_medik' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}
const maskNamaPasien = (nama: string): string => {
  if (!nama) return '';
  
  const parts = nama.split(' ');
  const maskedParts = parts.map((part) => {
    if (part.length <= 2) {
      return part; 
    }
    const first = part[0]; 
    const last = part[part.length - 1]; 
    const mask = '*'.repeat(part.length - 2); 
    return `${first}${mask}${last}`;
  });

  return maskedParts.join(' ');
};
fetchData()
</script>
