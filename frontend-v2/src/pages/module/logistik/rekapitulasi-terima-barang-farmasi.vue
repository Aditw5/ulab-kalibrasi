<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Rekapitulasi Kirim Barang Farmasi {{ item.namaProdukHeader }}</h3>
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
              <VLabel>Ruangan </VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.ruanganTujuan" :options="listRuanganStok" placeholder="Pilih ruangan"
                  :searchable="true" />
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
          <div class="mt-4">
            <VButton color="info" class="mt-3 mr-4" fullwidth="true" icon="fas fa-file-pdf" raised @click="cetakRekap()">
              Cetak Rekapitulasi </VButton>
          </div>
        </div>
      </div>
    </div>
    <div class="column">
      <div class="ruangan-row" v-if="item.ruangan">
        <div>Ruangan : {{ ruangan_tujuan }}</div>
      </div>
      <div class="table-scroll">
        <DataTable :value="dataSource" :paginator="true" rowGroupMode="rowspan" groupRowsBy="detailjenisproduk" :rows="10"
          :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoadingBtn">
          <Column field="no" header="No"></Column>
          <Column :rowspan="4" frozen :sortable="true" style="min-width: 100px" field="detailjenisproduk"
            header="Jenis Produk"></Column>
          <Column :rowspan="4" frozen :sortable="true" style="min-width: 200px" field="namaproduk" header="Nama Barang">
          </Column>
          <Column frozen :sortable="true" style="min-width: 100px" field="satuanstandar" header="Satuan"></Column>
          <Column frozen :sortable="true" style="min-width: 100px" field="jumlah" header="Jumlah"></Column>
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
  title: 'Transmedic - Rekapitulasi Kirim Barang Farmasi',
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let isLoading: any = ref(false)
const ruangan_asal = ref('');
const periode = ref('');
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

const cetakRekap = () => {
  // let object: any = {}
  // object = input.value
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganTujuan ? `&idruangantujuan=${item.value.ruanganTujuan}` : ''
  H.printBlade(`report/farmasi/cetak-rekap-terima-barang-farmasi${tglAwal}${tglAkhir}${ruanganfk}`)
}

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['REKAPITULASI TERIMA BARANG FARMASI'],
    [periode.value],
    [],
    // ['Ruangan : ' , ruangan_asal.value],
    ['No', 'Jenis Produk', 'Nama Barang', 'Satuan', 'Jumlah'],
    ...dataSource.value.map((e: any) => [
      e.no,
      e.detailjenisproduk,
      e.namaproduk,
      e.satuanstandar,
      e.jumlah,
      '',
    ])
  ]);

  const columnWidths = [
    { wch: 14 },
    { wch: 20 },
    { wch: 25 },
    { wch: 10 },
    { wch: 10 }
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
  // const _url = window.URL.createObjectURL(data)
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = 'rekap_terima_barang_farmasi' + EXCEL_EXTENSION;
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

  const periodeText = `Periode : ${moment(item.value.periode.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.periode.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get('/logistik/rekap-terima-barang-farmasi' + tglAwal + tglAkhir + '&offset=' + offset + '&limit=' + limit + '&rows=' + rows + produkfk + ruanganfk + ruanganfkTujuan).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSource.value = response.data
    ruangan_asal.value = response.data.length > 0 ? response.data[0].ruangan_asal : '';
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

.ruangan-row {
  margin-top: 10px;
  padding: 10px;
  display: flex;
  justify-content: space-between;
}

.ruangan-row div {
  font-weight: bold;
  font-size: 13px;
}

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
// @import '/@src/scss/module/sysadmin/master-data.scss';</style>
