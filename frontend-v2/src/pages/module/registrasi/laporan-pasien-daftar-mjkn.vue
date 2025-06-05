
<template>
  <div class="page-content-inner">
    <div class="is-navbar">
      <div class="form-layout">
        <div class="form-outer">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
            <div class="form-header-inner">
              <div class="left">
                <h3>Laporan Pasien Daftar MJKN</h3>
              </div>
              <div class="right">
                <div class="buttons">
                  <VButton icon="lnir lnir-arrow-left rem-100"
                    :to="{ name: 'module-registrasi-daftar-pembatalan-pasien' }" light dark-outlined>
                    Cancel
                  </VButton>
                  <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                    @click="cariRiwayat()"> Cari
                  </VButton>
                </div>
              </div>
            </div>
          </div>
          <div class="form-body">
            <!--Fieldset-->
            <div class="form-fieldset">
              <div class="fieldset-heading">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Filter Pencarian</h1>
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <div class="columns is-multiline mb-3 mt-2">
                        <div class="column is-6">
                          <label style=" margin-bottom: 2rem;">Tanggal Awal
                          </label>
                          <VDatePicker v-model="input.periode" is-range color="pink" trim-weeks class="pt-2">
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
                        <div class="column is-6">
                          <label style=" margin-bottom: 0.5rem;">No Registrasi
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.noReq"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6">
                          <label style=" margin-bottom: 0.5rem;">No Rm
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.noCm"></VInput>
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-6">
                          <label style=" margin-bottom: 0.5rem;">Nama Pasien
                          </label>
                          <VField>
                            <VControl raw subcontrol>
                              <VInput type="text" class="mt-3" v-model="input.namaPasien">
                              </VInput>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                    <div class="column is-12">
                      <div class="columns is-multiline">
                        <div class="mt-1 ml-3 flex flex-wrap align-items-center justify-content-between gap-2">
                        <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                          to
                          Excel </VButton>
                      </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <!--Fieldset-->
            <div class="form-fieldset">
              <!-- <div class="fieldset-heading mb-5">
                <h1 style="font-weight: bold; margin-bottom: 1rem;">Daftar Pasien Meninggal</h1>
              </div> -->
              <div class="column" v-if="isLoading">
                <VPlaceloadWrap v-for="data in 10">
                  <VPlaceload class="mx-2 mb-3" />
                </VPlaceloadWrap>
              </div>
              <div class="column" v-else>
                <VPlaceholderPage v-if="d_ListPasien.length == 0" title="Data Tidak di Temukan."
                  subtitle="Silakan gunakan filter lain" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
                <div v-else>
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <DataTable :value="d_ListPasien" class="p-datatable-sm" :loading="isLoading" :paginator="true"
                        :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                        <Column field="no" header="NO" frozen :sortable="true"></Column>
                        <Column field="tglregistrasi" frozen :sortable="true" header="Tgl Registrasi"></Column>
                        <Column field="noregistrasi" header="No Reg"></Column>
                        <Column field="nocm" header="No Rm"></Column>
                        <Column field="namapasien" header="Nama Pasien"></Column>
                        <Column field="nobpjs" header="NO BPJS"></Column>
                        <Column field="namaruangan" header="Ruangan"></Column>
                        <Column field="kelompokpasien" header="Kelompokpasien"></Column>
                        <Column field="ismobilejkn" header="MJKN"></Column>
                      </DataTable>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import moment from 'moment'
import * as XLSX from "xlsx";
const input: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let isLoading = ref(false)
const d_ListPasien: any = ref([])
const dataSourceICD9 = ref([])
const { y } = useWindowScroll()
const periode = ref('');
const isStuck = computed(() => {
  return y.value > 30
})


useHead({
  title: import.meta.env.VITE_PROJECT + ' - Laporan Pasien Daftar MJKN',
})
useViewWrapper().setFullWidth(true)
async function cariRiwayat() {
  let object: any = {}

  object = input.value

  isLoading.value = true;
  let startDate = moment(input.value.periode.start).format('YYYY-MM-DD');
  let endDate = moment(input.value.periode.end).format('YYYY-MM-DD');
  const noReg = object.noReg ? object.noReg : "";
  const noCm = object.noCm ? object.noCm : "";
  const namaPasien = object.namaPasien ? object.namaPasien : "";

  const periodeText = `Periode : ${moment(input.value.periode.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(input.value.periode.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  useApi().get(
    `/resgistrasi/laporan-pasien-daftar-mjkn?tglAwal=${startDate}&tglAkhir=${endDate}&noReg=${noReg}&noCm=${noCm}&namaPasien=${namaPasien}`).then((response: any) => {
      response.data.forEach((element: any, i: any) => {
        element.no = i + 1
      });
      d_ListPasien.value = response.data
      isLoading.value = false
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN PASIEN DAFTAR MJKN'],
    [periode.value],
    [],
    [
    'No', 'TGL Registrasi', 'noregistrasi', 'NO CM', 'Nama Pasien', 'NOBPJS', 'Ruangan',
    'Kelompok Pasien', 'MJKN'
    ],
    ...d_ListPasien.value.map((e) => [
    e.no, e.tglregistrasi, e.noregistrasi, e.nocm, e.namapasien, e.nobpjs, e.namaruangan, e.kelompokpasien, e.ismobilejkn
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
  saveAsExcel(excelBuffer, 'products');
}

const saveAsExcel = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_pasien_daftar_mjkn' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

const setAutoFill = () => {
  input.value.tglAwal = new Date()
  input.value.tglAkhir = new Date()
}

const convertDate = (date) => {
  const dateObject = new Date(date);
  const day = dateObject.getDate().toString().padStart(2, '0');
  const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
  const year = dateObject.getFullYear();
  const formattedDate = `${year}-${month}-${day}`;
  return formattedDate;
}
cariRiwayat();
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
  max-width: 1300px;
  margin: 0 auto;
}

.form-fieldset {
  padding: 10px 0;
  max-width: 100%;
  margin: 0 auto;
}

.table-pi {
  width: 1400px;
  border: 1px solid #929090;
}

.table-scroll {
  overflow-x: scroll;
}

.date {
  background-color: #9b9b9b;
  color: #fff;
}
</style>
