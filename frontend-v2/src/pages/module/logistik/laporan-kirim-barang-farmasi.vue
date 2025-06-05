<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Kirim Barang Farmasi {{ item.namaProdukHeader }}</h3>
    </div>
    <div class="coloumn">
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
          <div class="column is-6">
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

          <div class="column is-6">
            <VField class="is-autocomplete-select">
              <VLabel>Nama Produk</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.nmProduk" :options="listNamaProduk" placeholder="Pilih produk"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>

          <div class="column is-6">
            <VField class="is-autocomplete-select">
              <VLabel>Ruangan Asal</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.ruangan" :options="listRuanganStok" placeholder="Pilih ruangan"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField class="is-autocomplete-select">
              <VLabel>Ruangan Tujuan</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.ruanganTujuan" :options="listRuanganStok"
                  placeholder="Pilih ruangan" :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2 mt-4 ">
            <VButton @click="filter()" :loading="isLoadingBtn" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
          <div class="mt-5 flex flex-wrap align-items-center justify-content-between gap-2">
            <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
              to
              Excel </VButton>
          </div>
        </div>
      </div>
    </div>
    <div class="column">
      <div class="table-scroll">
        <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoadingBtn">
          <Column field="no" header="No"></Column>
          <Column style="min-width: 150px; text-align:center;" field="nokirim" header="No Kirim"></Column>
          <Column style="min-width: 150px" field="tglkirim" header="Tgl Kirim"></Column>
          <!-- <Column field="namaproduk" header="Produk" :sortable="true"></Column> -->
          <Column style="min-width: 200px" field="namaproduk" header="Nama Produk"></Column>
          <Column style="min-width: 100px" field="detailjenisproduk" header="Jenis Produk"></Column>
          <Column style="min-width: 100px" field="asal_barang" header="Asal Barang"></Column>
          <Column style="min-width: 100px" field="jumlah" header="Jumlah"></Column>
          <Column style="min-width: 100px" field="satuanstandar" header="Satuan"></Column>
          <Column style="min-width: 100px" field="hargasatuan" header="Harga Satuan"></Column>
          <Column style="min-width: 150px" field="ruangan_asal" header="Ruangan Asal"></Column>
          <Column style="min-width: 150px" field="ruangan_tujuan" header="Ruangan Tujuan"></Column>
          <Column style="min-width: 200px" field="namalengkap" header="Operator"></Column>
          <!-- <Column field="transaksi" header="Transaksi"></Column> -->
        </DataTable>
      </div>

    </div>
  </VCard>
</template>

<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
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
  title: 'Transmedic - Laporan Kirim Barang Farmasi',
})
const remakeData: any = ref([])
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
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const route = useRoute()

const exportExcel = () => {
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      'No': e.no,
      'No Kirim': e.nokirim,
      'Tgl Kirim': e.tglkirim,
      'Nama Produk': e.namaproduk,
      'Jenis Produk': e.detailjenisproduk,
      'Asal Barang': e.asal_barang,
      'Jumlah': e.jumlah,
      'Satuan Standar': e.satuanstandar,
      'Harga Satuan': e.hargasatuan,
      'Ruangan Asal': e.ruangan_asal,
      'Ruangan Tujuan': e.ruangan_tujuan,
      'Operator': e.namalengkap,
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const columnWidths = [
    { wch: 5 }, // Kolom A
    { wch: 10 }, // Kolom A
    { wch: 10 }, // Kolom B
    { wch: 19 }, // Kolom C
    { wch: 10 }, // Kolom D
    { wch: 10 }, // Kolom E
    { wch: 10 }, // Kolom F
    { wch: 10 }, // Kolom G
    { wch: 10 }, // Kolom H
    { wch: 19 }, // Kolom I
    { wch: 19 }, // Kolom J
    { wch: 19 }, // Kolom K
  ];
  worksheet['!cols'] = columnWidths;

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
  // const _url = window.URL.createObjectURL(data)
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'laporan_kirim_barang_farmasi' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

async function fetchData() {
  isLoadingBtn.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk.id}` : ''
  let ruanganfk = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : ''
  let ruanganfkTujuan = item.value.ruanganTujuan ? `&idruangantujuan=${item.value.ruanganTujuan}` : ''

  await useApi().get('/logistik/kirim-barang-farmasi' + tglAwal + tglAkhir + '&offset=' + offset + '&limit=' + limit + '&rows=' + rows + produkfk + ruanganfk + ruanganfkTujuan).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
  })
  isLoadingBtn.value = false
}
async function listDropdown() {
  isLoading.value = true
  const response = await useApi().get(`/logistik/list-order-cbo`)
  listRuanganStok.value = response.rutu.map((e: any): any => {
    return { label: e.namaruangan, value: e.id }
  })

  listNamaProduk.value = response.produk.map((e: any): any => {
    return { label: e.namaproduk, value: e }
  })

  isLoading.value = false
}

// async function fetchProduk(filter: any) {
//   let query = ''
//   if (filter) {
//     query = filter.toLowerCase()
//   }
//   const response = await useApi().get(
//     `/logistik/kartu-stok/list-produk?name=${query}&limit=20`)

//   return response.produk.map((item: any) => {
//     return { value: item.id, label: item.namaproduk }
//   })
// }

function clearFilter() {
  delete item.value.nmProduk
  delete item.value.ruangan
  delete item.value.ruanganTujuan
  fetchData()
}
function filter() {
  fetchData()
}

listDropdown()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';
</style>
