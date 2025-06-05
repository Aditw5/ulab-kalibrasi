<template>
  <div class="columns is-multiline">
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Instalasi Farmasi</h3>
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
                      v-model:filters="filters" filterDisplay="row" :globalFilterFields="['jenis_pelayanan']" style="width: 100%">
          <template #empty> Belum ada Data. </template>
          <ColumnGroup type="header">
            <Row>
              <Column header="Jenis Pelayanan" :rowspan="2" headerStyle="min-width: 50%;"/>
              <Column header="Depo" :colspan="namaruangan.length + 1"/>
            </Row>
            <Row>
              <template v-for="(depo) in namaruangan">
                <Column :header="depo.header" :body="formatCurrency" headerStyle="width: 40%"/>
              </template>
              <Column header="Total" :rowspan="2" style="min-width: 50%;"/>
            </Row>
          </ColumnGroup>
          <Column field="jenis_pelayanan"/>
          <!-- <Column field="Depo Farmasi IGD"/> -->
          <template v-for="(depo) in namaruangan">
            <Column :field="depo.header"></Column>
          </template>
          <Column field="total_pendapatan_kelompok"></Column>
          <ColumnGroup type="footer">
              <Row>
                <Column footer="TOTAL" footerStyle="text-align:center" />
                <template v-for="(pendapatan) in totalPendapatan">
                  <Column :footer="H.formatRp(pendapatan.totalPemasukan, 'Rp. ')" />
                </template>
                <Column :footer="H.formatRp(grandTotal, 'Rp. ')" />
              </Row>
              <Row>
                <Column footer="JUMLAH LEMBAR" footerStyle="text-align:center" />
                <template v-for="(lembar) in totalPendapatan">
                  <Column :footer="lembar.totalLembar" footerStyle="text-align:center"/>
                </template>
                <Column :footer="grandTotalLembar" footerStyle="text-align:center"/>
              </Row>
              <Row>
                <Column footer="JUMLAH RESEP" footerStyle="text-align:center" />
                <template v-for="(resep) in totalPendapatan">
                  <Column :footer="resep.totalResep" footerStyle="text-align:center"/>
                </template>
                <Column :footer="grandTotalResep" footerStyle="text-align:center"/>
              </Row>
            </ColumnGroup>
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
const namaruangan: any = ref([]);
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

const exportExcel = () => {
  const columnRuangan = ['Jenis Pelayanan']
  namaruangan.value.forEach(element=>{
    columnRuangan.push(element.header)
  })
  columnRuangan.push('Total')
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN INSTALASI FARMASI'],
    [periode.value],
    [],
    columnRuangan,
    ...dataSource.value.map((e: any, index: any) => [
      e.jenis_pelayanan,
      ...namaruangan.value.map((element) => e.jenis_pelayanan == 'L/R' ? e[element.header] : parseFloat(e[element.header])),
      parseFloat(e.total_pendapatan_kelompok),
    ]),
    ['Jumlah Total',
      ...totalPendapatan.value.map(e => e.totalPemasukan)
    ],
    ['Jumlah Lembar',
      ...totalPendapatan.value.map(e => e.totalLembar)
    ],
    ['Jumlah Resep',
      ...totalPendapatan.value.map(e => e.totalResep)
    ],
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
  const desiredFileName = 'laporan_instalasi_farmasi' + EXCEL_EXTENSION;
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
  const response = await useApi().get(`report/farmasi/laporan-instalasi-farmasi?${tglAwal}${tglAkhir}`)
  dataSource.value = []
  if(response != null){
    response.forEach(element => {
      element.groups.forEach(status=>{
        const newData = {
          'jenis_pelayanan': `${element.kelompokpasien} ${status.status_piutang}`,
          'status_piutang': status.status_piutang,
        };
        const lembarData = {
          'jenis_pelayanan': 'L/R',
        }
        let total_pendapatan_kelompok = 0
        let total_resep_kelompok = 0
        let total_lembar_kelompok = 0
        status.details.forEach(data => {
          newData[data.namaruangan] = status.status_piutang=='Bon' ?
                                      parseFloat(data.piutang_bon).toFixed(2) :
                                      parseFloat((data.total_pemasukan ?? 0)).toFixed(2);
          lembarData[data.namaruangan] = `${data.jumlah_lembar} ${data.jumlah_resep}`
          const angka = status.status_piutang =='Bon' ?
                                      parseFloat(data.piutang_bon).toFixed(2) :
                                      parseFloat((data.total_pemasukan ?? 0)).toFixed(2);
          total_pendapatan_kelompok += parseFloat(angka)
          total_resep_kelompok += data.jumlah_resep
          total_lembar_kelompok += data.jumlah_lembar
        });
        newData['total_pendapatan_kelompok'] = parseFloat(total_pendapatan_kelompok).toFixed(2)
        lembarData['total_pendapatan_kelompok'] = `${total_lembar_kelompok} ${total_resep_kelompok}`

        dataSource.value.push(newData);
        dataSource.value.push(lembarData);
      })
    });

    // Array untuk menyimpan total pendapatan per ruangan
    const totalPendapatanPerRuanganArray = [];

    // Iterasi melalui data response
    response.forEach(element => {
      element.groups.forEach(status => {
        status.details.forEach(detail => {
          const namaRuangan = detail.namaruangan;
          // if(namaRuangan == 'Depo Farmasi IGD'){
          // }
            const angka = status.status_piutang=='Bon' ?
                                      parseFloat(detail.piutang_bon).toFixed(2) :
                                      parseFloat((detail.total_pemasukan ?? 0)).toFixed(2);
          const totalPemasukan = parseFloat((angka ?? 0));
          const totalLembar = detail.jumlah_lembar
          const totalResep = detail.jumlah_resep

          // Cari apakah ruangan sudah ada di dalam array
          const existingRuangan = totalPendapatanPerRuanganArray.find(item => item.namaRuangan === namaRuangan);

          grandTotal.value += parseFloat(totalPemasukan)
          grandTotalLembar.value += totalLembar
          grandTotalResep.value += totalResep
          // Jika ruangan sudah ada, tambahkan total pendapatan
          if (existingRuangan) {
            existingRuangan.totalPemasukan += parseFloat(totalPemasukan);
            existingRuangan.totalResep += totalResep;
            existingRuangan.totalLembar += totalLembar;
          } else {
            // Jika ruangan belum ada, tambahkan ruangan baru ke dalam array
            totalPendapatanPerRuanganArray.push({
              namaRuangan: namaRuangan,
              totalPemasukan: parseFloat(totalPemasukan),
              totalResep: totalResep,
              totalLembar: totalLembar
            });
          }
        });
      });
    });

    // Menampilkan total pendapatan per ruangan dalam bentuk array
    totalPendapatan.value = totalPendapatanPerRuanganArray


    const fetchRuang = await useApi().get('/dashboard/apotik/data-combo')
    namaruangan.value = fetchRuang.ruangan
    .map((e) => ({ header: e.namaruangan, value: e.id, default: e }))
    .sort((a, b) => a.header.localeCompare(b.header));

    totalPendapatan.value.sort((a,b) => a.namaRuangan.localeCompare(b.namaRuangan))

    dataSource.value
    isLoadingBtn.value = false;
  }
}

async function listDropdown() {
  isLoading.value = true
  const response = await useApi().get(`emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&kondisi=isnarkotika,t`)
  listNamaProduk.value = response

  isLoading.value = false
}

function clearFilter() {
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
