
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-5 pt-0 pb-0">
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
              <div class="column is-3 pt-0 pb-0">
                <span>Departemen</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.departemen" :suggestions="d_departemen"
                      @complete="fetchDepartemen($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                      :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pasien..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4 mt-5" style="margin-left: auto:  !important;">
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
      <h3 class="title is-5 mb-2">Laporan Surveilans</h3>
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
            <Column field="no_pendaftaran" header="Nomor Pendaftaran" frozen :sortable="true" style="min-width: 100px">
            </Column>
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaayah" header="Nama Ayah" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaibu" header="Nama Ibu" :sortable="true" style="min-width: 200px"></Column>
            <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
            <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px"></Column>
            <Column field="umur" header="Umur" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tglregistrasi" header="Tanggal Masuk" :sortable="true" style="min-width: 200px"></Column>
            <Column field="statuspulang" header="Status Pulang" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tglpulang" header="Tanggal Pulang" :sortable="true" style="min-width: 200px"></Column>
            <Column field="kelompokpasien" header="Jenis Pasien" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaruangan" header="Nama Ruangan" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namalengkap" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakelas" header="Kelas" :sortable="true" style="min-width: 100px"></Column>
            <Column field="namakotakabupaten" header="Kabupaten" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakecamatan" header="Kecamatan" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namadesakelurahan" header="Kelurahan" :sortable="true" style="min-width: 200px"></Column>
            <Column field="rtrw" header="RT/RW" :sortable="true" style="min-width: 100px"></Column>
            <Column field="namapropinsi" header="Provinsi" :sortable="true" style="min-width: 200px"></Column>
            <Column field="nohp" header="No. Telp/HP" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namadiagnosa" header="Nama Diagnosa" :sortable="true" style="min-width: 200px"></Column>
            <Column field="kddiagnosa" header="Kode Diagnosa" :sortable="true" style="min-width: 100px"></Column>
            <Column field="statuspasien" header="Status" :sortable="true" style="min-width: 100px">
              <template #body="slotProps">
                <VTag class="ml-4" color="primary" rounded>{{ slotProps.data.statuspasien }}</VTag>
              </template>
            </Column>
            <Column field="id_dokter" header="ID Dokter" :sortable="true" style="min-width: 100px"></Column>
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
  title: 'Laporan Surveilans - ' + import.meta.env.VITE_PROJECT,
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

const fetchDepartemen = async (filter: any) => {
  d_departemen.value = [{ value: '9', label: 'IGD' }, { value: '16', label: 'Rawat Inap' }, { value: '18', label: 'Rawat Jalan' },]
}

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let d_departemen: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let departemen = item.value.departemen ? `&departemen=${item.value.departemen.value}` : ''

  const periodeText = `Periode : ${moment(item.value.qFilterTgl.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.qFilterTgl.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get(`/report/pelayanan/get-laporan-surveilans?${tglAwal}${tglAkhir}${departemen}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isPlaceLoad.value = false

}

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN SURVEILANS'],
    [periode.value],
    [],
    [
      'No', 'Nomor Pendaftaran', 'Nama Pasien', 'Nama Ayah', 'Nama Ibu', 'No CM', 'Jenis Kelamin',
      'Tanggal Lahir', 'Umur' ,'Tanggal Registrasi', 'Status Pulang', 'Tanggal Pulang',
      'Jenis Pasien', 'Nama Ruangan', 'Dokter', 'Nama Kelas', 'Nama Kabupaten',
      'Nama Kecamatan', 'Nama Desa/Kelurahan', 'RT/TW', 'Nama Provinsi', 'No. Telp/HP',
      'Nama Diagnosa', 'Kode Diagnosa', 'Status Pasien', 'ID Dokter'
    ],
    ...dataSource.value.map((e) => [
      e.no, e.no_pendaftaran, e.namapasien, e.namaayah, e.namaibu, e.nocm, e.jeniskelamin,
      e.tgllahir, e.umur, e.tglregistrasi, e.statuspulang, e.tglpulang,
      e.kelompokpasien, e.namaruangan, e.namalengkap, e.namakelas, e.namakotakabupaten,
      e.namakecamatan, e.namadesakelurahan, e.rtrw, e.namapropinsi,e.nohp,
      e.namadiagnosa, e.kddiagnosa, e.statuspasien, e.id_dokter
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 20 },
    { wch: 25 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
    { wch: 10 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 21 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 21 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 21 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 21 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 21 } };

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
  const desiredFileName = 'laporan_surveilans' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

// const maskNamaPasien = (nama: string): string => {
//   if (!nama) return '';

//   const parts = nama.split(' ');
//   const maskedParts = parts.map((part) => {
//     if (part.length <= 2) {
//       return part;
//     }
//     const first = part[0];
//     const last = part[part.length - 1];
//     const mask = '*'.repeat(part.length - 2);
//     return `${first}${mask}${last}`;
//   });

//   return maskedParts.join(' ');
// };

fetchData()
</script>
