<template>
  <div class="columns is-multiline">
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Pengeluaran Narkotika</h3>
    </div>
    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
      <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
        to
        Excel </VButton>
    </div>

    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filters</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Clear All
            </a>
          </div>
          <div class="column is-12">
            <VField label="Periode">
              <!-- <VLabel></VLabel> -->
              <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField addons>
                    <VControl icon="feather:calendar">
                      <VInput :value="inputValue.start" v-on="inputEvents.start" />
                    </VControl>
                    <VControl>
                      <VButton static icon="feather:arrow-right" />
                    </VControl>
                    <VControl subcontrol icon="feather:calendar">
                      <VInput :value="inputValue.end" v-on="inputEvents.end" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>

          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Nama Produk</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.nmProduk" :options="listNamaProduk" placeholder="Pilih produk" :searchable="true"  @complete="fetchProduk($event)"/>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="filter()" :loading="isLoadingBtn" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                        class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple" resizableColumns
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoadingBtn">
          <template #empty> Belum ada Data. </template>
          <ColumnGroup type="header">
            <Row>
              <Column header="No." :rowspan="2"/>
              <Column header="Nama Barang" :sortable="true" :rowspan="2" style="min-width: 50%;"/>
              <Column header="No & Tanggal Resep" :rowspan="2"/>
              <Column header="Tanggal Penyerahan Resep" :rowspan="2"/>
              <Column header="Jumlah" :rowspan="2"/>
              <Column header="Ruangan" :rowspan="2"/>
              <Column header="Pasien" :colspan="2"/>
              <Column header="Dokter" :colspan="2"/>
              <Column header="Keterangan" :rowspan="2"/>
            </Row>
            <Row>
              <Column header="Nama"/>
              <Column header="Alamat"/>
              <Column header="Nama"/>
              <Column header="Alamat"/>
            </Row>
          </ColumnGroup>
          <Column field="no"></Column>
          <Column field="namaproduk"></Column>
          <Column field="tglresep"></Column>
          <Column field="tglpelayanan"></Column>
          <Column field="jumlah"></Column>
          <Column field="namaruangan"></Column>
          <Column field="namapasien"></Column>
          <Column field="alamatlengkap"></Column>
          <Column field="namalengkap"></Column>
          <Column field="rs"></Column>
        </DataTable>
      </div>

    </div>
  </VCard>
</div>
</template>

<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete'
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import * as XLSX from "xlsx";
import moment from 'moment'
useHead({
  title: 'Transmedic - Laporan Penggunaan Obat Alkes',
})
const remakeData: any = ref([])
const namaruangan = ref('');
const namaproduk = ref('');
const periode = ref('');
let dataSource: any = ref([])
let isLoading: any = ref(false)
let isLoadingBtn: any = ref(false)
let item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let listRuanganStok: any = ref([])
let listNamaProduk: any = ref([])
const route = useRoute()

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN PENGELUARAN NARKOTIKA'],
    [periode.value],
    [],
    ['No', 'Nama Produk', 'No/Tgl Resep', 'Tanggal Penyerahan Resep', 'Jumlah', 'Ruangan', 'Nama Pasien', 'Alamat', 'Nama Dokter', 'Alamat', 'Keterangan'],
    ...dataSource.value.map((e: any) => [
      e.no,
      e.namaproduk,
      e.noregistrasi+" "+e.tglresep,
      e.tglpelayanan,
      e.jumlah || 0,
      e.namaruangan,
      e.namapasien,
      `${e.alamatlengkap} ${e.namakotakabupaten ? e.namakotakabupaten:''}`,
      e.namalengkap,
      e.rs,
      '',
    ]),
    [],
  ]);

  const columnWidths = [
    { wch: 5 },
    { wch: 30 },
    { wch: 20 },
    { wch: 20 },
    { wch: 5 },
    { wch: 20 },
    { wch: 40 },
    { wch: 50 },
    { wch: 40 },
    { wch: 30 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 8 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 8 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 8 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 8 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 8 } };

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
  const desiredFileName = 'rekap_pengeluaran_obat_narkotika' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

async function fetchData() {
  isLoadingBtn.value = true;
  let tglAwal = 'tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD');
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD');
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk}` : '';

  periode.value = `${moment(item.value.periode.start).format('YYYY-MM-DD')} sampai ${moment(item.value.periode.end).format('YYYY-MM-DD')}`

  const response = await useApi().get(`report/farmasi/laporan-pengeluaran-narkotika?${tglAwal}${tglAkhir}${produkfk}`)
  if(response != null){
    for (let index = 0; index < response.length; index++) {
      const element = response[index];
      element.no = index+1
    }
    dataSource.value = response
    isLoadingBtn.value = false;
  }
}

async function listDropdown() {
  isLoading.value = true
  const response = await useApi().get(`emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&kondisi=isnarkotika,t`)
  listNamaProduk.value = response

  isLoading.value = false
}

async function fetchProduk(filter: any) {
  let query = ''
  if (filter) {
    query = filter.toLowerCase()
  }
  const response = await useApi().get(`emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${filter.query}kondisi=isnarkotika,t`)
  return response
}

function clearFilter() {
  delete item.value.nmProduk
  delete item.value.ruangan
  fetchData()
}
function filter() {
  fetchData()
}

listDropdown()
fetchData()
</script>
<style lang="scss">
.additional-row {
  margin-top: 10px;
  padding: 10px;
  border-top: 1px solid #ccc;
  display: flex;
  justify-content: space-between;
}
.ruangan-row{
  margin-top: 10px;
  padding: 10px;
  display: flex;
  justify-content: space-between;
}

@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';
</style>
