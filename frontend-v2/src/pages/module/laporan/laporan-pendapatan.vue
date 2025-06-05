<template>
  <div class="columns is-multiline">
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Pendapatan</h3>
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
            <VButton @click="filter()" :loading="isLoadingBtn" type="button" icon="feather:search"
              class="is-fullwidth mr-3" color="info" raised>
              Apply Filters
            </VButton>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <DataTable :value="dataSource" :rows="20" v-model:expandedRows="expandedRows"
                      :rowsPerPageOptions="[5, 10, 15, 20, 50, 100]" :loading="isLoadingBtn" class="p-datatable-sm"
                      responsiveLayout="stack" breakpoint="960px" selectionMode="single" sortMode="multiple"
                      dataKey="norec" resizableColumns
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                      v-model:filters="filters" filterDisplay="row" :globalFilterFields="['namaruangan']" style="width: 100%">
          <template #empty> Belum ada Data. </template>

          <ColumnGroup type="header">
            <Row>
              <Column header="Ruangan/Poli" :rowspan="2" headerStyle="min-width: 50%;"/>
              <template v-for="(ds) in namakelompok" :key="ds.namaruangan">
                <Column :header="ds" headerStyle="width: 40%" :colspan="3"/>
              </template>
            </Row>
            <Row>
              <template v-for="(ds, index) in namakelompok" :key="ds.namaruangan">
                  <Column :header="index % 3 === 0 ? 'RP' : index % 3 === 1 ? 'L':'R'" />
              </template>
              <template v-for="(ds, index) in namakelompok" :key="ds.namaruangan">
                  <Column :header="index % 3 === 0 ? 'RP' : index % 3 === 1 ? 'L':'R'" />
              </template>
              <template v-for="(ds, index) in namakelompok" :key="ds.namaruangan">
                  <Column :header="index % 3 === 0 ? 'RP' : index % 3 === 1 ? 'L':'R'" />
              </template>
            </Row>
          </ColumnGroup>
          <Column field="namaruangan"/>
          <!-- <Column :field="'JASA RAHARJA Bontotal_pendapatan'"/> -->
            <template v-for="(ds, index) in namakelompok" :key="ds.namaruangan">
              <Column :field="`${ds+'total_pendapatan'}`"/>
              <Column :field="`${ds+'total_lembar'}`"/>
              <Column :field="`${ds+'total_resep'}`"/>
            </template>
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

useViewWrapper().setFullWidth(true)
const remakeData: any = ref([])
const namakelompok: any = ref([]);
const namaproduk = ref('');
const periode = ref('');
let dataSource: any = ref([])
let totalPendapatan: any = ref([])
let grandTotal: any = ref(0)
let grandTotalResep: any = ref(0)
let grandTotalLembar: any = ref(0)
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

const formatCurrency = (value) => {
  console.log(value);
  return H.formatRp(value, '')
}

const dataRows = dataSource.value.map((e) => {
  const row = [e.namaruangan];
  namakelompok.value.forEach((element) => {
    row.push(parseFloat(e[`${element}total_pendapatan`]));
    row.push(parseFloat(e[`${element}total_lembar`]));
    row.push(parseFloat(e[`${element}total_resep`]));
  });
  return row;
});

const exportExcel = () => {
  const columnRuangan = ['Ruangan/Poli']
  const RPLR = ['']
  const rows = []
  dataSource.value.forEach(element => {

  })
  namakelompok.value.forEach(element=>{
    columnRuangan.push(element)
    columnRuangan.push('')
    columnRuangan.push('')
    RPLR.push('RP')
    RPLR.push('L')
    RPLR.push('R')
  })
  columnRuangan.push('Total')
  RPLR.push('RP')
    RPLR.push('L')
    RPLR.push('R')
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN INSTALASI FARMASI'],
    [periode.value],
    [],
    columnRuangan,
    RPLR,
    ...dataSource.value.map((e) => {
      const row = [e.namaruangan];
      const namakelompokData = namakelompok.value.map((element) => [
        parseFloat(e[`${element}total_pendapatan`]),
        parseFloat(e[`${element}total_lembar`]),
        parseFloat(e[`${element}total_resep`]),
      ]);
      row.push(...namakelompokData.flat());
      row.push(
        namakelompokData.reduce((acc, val) => acc + val[0], 0), // Total Pendapatan
        namakelompokData.reduce((acc, val) => acc + val[1], 0), // Total Lembar
        namakelompokData.reduce((acc, val) => acc + val[2], 0)  // Total Resep
      );
      return row;
    }),
  ]);

  const columnWidths = [
    { wch: 5 },
    { wch: 30 },
    { wch: 20 },
    { wch: 20 },
    { wch: 5 },
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
  const desiredFileName = 'laporan_pendapatan ' + moment(item.value.periode.start).format('YYYY-MM-DD') +' sampai '+moment(item.value.periode.end).format('YYYY-MM-DD')+ EXCEL_EXTENSION;
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

    periode.value = `${moment(item.value.periode.start).format('YYYY-MM-DD')} sampai ${moment(item.value.periode.end).format('YYYY-MM-DD')}`
    const response = await useApi().get(`report/farmasi/laporan-pendapatan?${tglAwal}${tglAkhir}`);
    dataSource.value = []

    if(response !== null){
      let newData = [];
      let jumlah = {
        namaruangan: 'Jumlah',
      }

      response.forEach((ruangan) => {
        let ruanganObj = {
          namaruangan: ruangan.namaruangan,
        };

        Object.keys(ruangan.kelompokpasien).forEach((kelompok) => {
          Object.keys(ruangan.kelompokpasien[kelompok]).forEach((status) => {
            let keyPrefix = `${kelompok} ${status}`;
            if(!namakelompok.value.includes(keyPrefix)){
              namakelompok.value.push(keyPrefix)
            }

            ruanganObj[keyPrefix + "total_pendapatan"] =
              parseFloat(ruangan.kelompokpasien[kelompok][status].total_pemasukan).toFixed(2) || 0;

            ruanganObj[keyPrefix + "total_resep"] =
              ruangan.kelompokpasien[kelompok][status].jumlah_resep || 0;

            ruanganObj[keyPrefix + "total_lembar"] =
              ruangan.kelompokpasien[kelompok][status].jumlah_lembar || 0;
          });
        });
        newData.push(ruanganObj);
      });
      dataSource.value = newData
      isLoadingBtn.value = false
    }
    console.log(dataSource.value)
  }

const columns = [
  {field: 'namaruangan', header: 'Nama Ruangan'}
]

const calculateColspan = (kelompokpasien) => {
      return Object.keys(kelompokpasien).length;
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
