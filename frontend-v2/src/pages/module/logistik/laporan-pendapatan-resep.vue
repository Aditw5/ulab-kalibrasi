<template>
  <div class="columns is-multiline">
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Pendpatan Resep</h3>
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
              <VLabel>Depo Farmasi</VLabel>
              <VControl icon="feather:search" :loading="isLoading">
                <Multiselect mode="single" v-model="item.ruangan" :options="listRuanganStok" placeholder="Pilih ruangan"
                  :searchable="true" />
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
        <div class="table-scroll">
          <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoadingBtn">
            <template #empty> Belum ada Data. </template>
            <ColumnGroup type="header">
              <Row>
                <Column header="No" :rowspan="2" style="min-width: 100px;"/>
                <Column header="Nama Pasien" :rowspan="2" style="min-width: 150px;"/>
                <Column header="NOCM" :rowspan="2" style="min-width: 100px;"/>
                <Column header="Dokter" :rowspan="2" style="min-width: 150px;"/>
                <Column header="Ruang" :rowspan="2" style="min-width: 200px;"/>
                <Column header="Depo" :rowspan="2" style="min-width: 200px;"/>
                <Column header="Jenis" :rowspan="2" style="min-width: 100px;"/>
                <Column header="Kota Cirebon" :colspan="3" style="min-width: 200px;"/>
                <Column header="Kab Cirebon" :colspan="3" style="min-width: 200px;"/>
                <Column header="Kab Kuningan" :colspan="3" style="min-width: 200px;"/>
                <Column header="Kab Indramayu" :colspan="3" style="min-width: 200px;"/>
                <Column header="Kab Majalengka" :colspan="3" style="min-width: 200px;"/>
                <Column header="Kab Lain-Lain" :colspan="3" style="min-width: 200px;"/>
              </Row>
              <Row>
                  <!-- cirebon -->
                  <Column header="RP" sortable field="kota_cirebon" />
                  <Column header="L" sortable field="lembar_kota_cirebon" />
                  <Column header="R" sortable field="resep_kota_cirebon" />
                  <!-- Kab cirebon -->
                  <Column header="RP" sortable field="kab_cirebon" />
                  <Column header="L" sortable field="lembar_kab_cirebon" />
                  <Column header="R" sortable field="resep_kab_cirebon" />
                  <!-- Kab kuningan -->
                  <Column header="RP" sortable field="kab_kuningan" />
                  <Column header="L" sortable field="lembar_kab_kuningan" />
                  <Column header="R" sortable field="resep_kab_kuningan" />
                  <!-- Kab indramayu -->
                  <Column header="RP" sortable field="kab_indramayu" />
                  <Column header="L" sortable field="lembar_kab_indramayu" />
                  <Column header="R" sortable field="resep_kab_indramayu" />
                  <!-- Kab majalengka -->
                  <Column header="RP" sortable field="kab_majalengka" />
                  <Column header="L" sortable field="lembar_kab_majalengka" />
                  <Column header="R" sortable field="resep_kab_majalengka" />
                  <!-- Kab lain -->
                  <Column header="RP" sortable field="kota_lain" />
                  <Column header="L" sortable field="lembar_kota_lain" />
                  <Column header="R" sortable field="resep_kota_lain" />
              </Row>
            </ColumnGroup>
              <Column field="no" />
              <Column field="namapasien"></Column>
              <Column field="nocm"></Column>
              <Column field="namalengkap"></Column>
              <Column field="ruangan_pasien"></Column>
              <Column field="depo"></Column>
              <Column field="jenis_barang" />
              <!-- cirebon -->
              <Column field="kota_cirebon">
                <template #body="slotProps">
                    {{formatCurrency(slotProps.data.kota_cirebon)}}
                </template>
              </Column>
              <Column field="lembar_kota_cirebon" />
              <Column field="resep_kota_cirebon" />
              <!-- kab Cirebon -->
              <Column field="kab_cirebon">
                <template #body="slotProps">
                    {{formatCurrency(slotProps.data.kab_cirebon)}}
                </template>
              </Column>
              <Column field="lembar_kab_cirebon" />
              <Column field="resep_kab_cirebon" />
              <!-- kab Kuningan -->
              <Column field="kab_kuningan">
                <template #body="slotProps">
                    {{formatCurrency(slotProps.data.kab_kuningan)}}
                </template>
              </Column>
              <Column field="lembar_kab_kuningan" />
              <Column field="resep_kab_kuningan" />
              <!-- kab indramayu -->
              <Column field="kab_indramayu">
                <template #body="slotProps">
                    {{formatCurrency(slotProps.data.kab_indramayu)}}
                </template>
              </Column>
              <Column field="lembar_kab_indramayu" />
              <Column field="resep_kab_indramayu" />
              <!--kab_majalengka  -->
              <Column field="kab_majalengka">
                <template #body="slotProps">
                    {{formatCurrency(slotProps.data.kab_majalengka)}}
                </template>
              </Column>
              <Column field="lembar_kab_majalengka" />
              <Column field="resep_kab_majalengka" />
              <!--kota_lain -->
              <Column field="kota_lain">
                <template #body="slotProps">
                    {{formatCurrency(slotProps.data.kota_lain)}}
                </template>
              </Column>
              <Column field="lembar_kota_lain" />
              <Column field="resep_kota_lain" />
            <ColumnGroup type="footer">
                <Row>
                    <Column footer="Obat :" :colspan="7" footerStyle="text-align:right"/>
                    <!-- kota cire -->
                    <Column :footer="total_kota_cirebon_obat"/>
                    <Column :rowspan="2" :footer="total_lembar_kota_cirebon"/>
                    <Column :footer="total_resep_kota_cirebon_obat"/>
                    <!-- kab cirebon -->
                    <Column :footer="total_kab_cirebon_obat"/>
                    <Column :rowspan="2" :footer="total_lembar_kab_cirebon"/>
                    <Column :footer="total_resep_kab_cirebon_obat"/>
                    <!-- kab kuningan -->
                    <Column :footer="total_kab_kuningan_obat"/>
                    <Column :rowspan="2" :footer="total_lembar_kab_kuningan"/>
                    <Column :footer="total_resep_kab_kuningan_obat"/>
                    <!-- kab indramayu -->
                    <Column :footer="total_kab_indramayu_obat"/>
                    <Column :rowspan="2" :footer="total_lembar_kab_indramayu"/>
                    <Column :footer="total_resep_kab_indramayu_obat"/>
                    <!-- kab majalengka -->
                    <Column :footer="total_kab_majalengka_obat"/>
                    <Column :rowspan="2" :footer="total_lembar_kab_majalengka"/>
                    <Column :footer="total_resep_kab_majalengka_obat"/>
                    <!-- kota lain -->
                    <Column :footer="total_kota_lain_obat"/>
                    <Column :rowspan="2" :footer="total_lembar_kota_lain"/>
                    <Column :footer="total_resep_kota_lain_obat"/>
                </Row>
                <Row>
                    <Column footer="Alkes :" :colspan="7" footerStyle="text-align:right"/>
                    <!-- kota cire -->
                    <Column :footer="total_kota_cirebon_alkes"/>
                    <Column :footer="total_resep_kota_cirebon_alkes"/>
                    <!-- kab cirebon -->
                    <Column :footer="total_kab_cirebon_alkes"/>
                    <Column :footer="total_resep_kab_cirebon_alkes"/>
                    <!-- kab kuningan -->
                    <Column :footer="total_kab_kuningan_alkes"/>
                    <Column :footer="total_resep_kab_kuningan_alkes"/>
                    <!-- kab indramayu -->
                    <Column :footer="total_kab_indramayu_alkes"/>
                    <Column :footer="total_resep_kab_indramayu_alkes"/>
                    <!-- kab majalengka -->
                    <Column :footer="total_kab_majalengka_alkes"/>
                    <Column :footer="total_resep_kab_majalengka_alkes"/>
                    <!-- kota lain -->
                    <Column :footer="total_kota_lain_alkes"/>
                    <Column :footer="total_resep_kota_lain_alkes"/>
                </Row>
                <Row>
                    <Column footer="Total :" :colspan="7" footerStyle="text-align:center"/>
                    <!-- kota cire -->
                    <Column :footer="total_kota_cirebon"/>
                    <Column :footer="total_lembar_kota_cirebon"/>
                    <Column :footer="total_resep_kota_cirebon"/>
                    <!-- kab cirebon -->
                    <Column :footer="total_kab_cirebon"/>
                    <Column :footer="total_lembar_kab_cirebon"/>
                    <Column :footer="total_resep_kab_cirebon"/>
                    <!-- kab kuningan -->
                    <Column :footer="total_kab_kuningan"/>
                    <Column :footer="total_lembar_kab_kuningan"/>
                    <Column :footer="total_resep_kab_kuningan"/>
                    <!-- kab indramayu -->
                    <Column :footer="total_kab_indramayu"/>
                    <Column :footer="total_lembar_kab_indramayu"/>
                    <Column :footer="total_resep_kab_indramayu"/>
                    <!-- kab majalengka -->
                    <Column :footer="total_kab_majalengka"/>
                    <Column :footer="total_lembar_kab_majalengka"/>
                    <Column :footer="total_resep_kab_majalengka"/>
                    <!-- kota lain -->
                    <Column :footer="total_kota_lain"/>
                    <Column :footer="total_lembar_kota_lain"/>
                    <Column :footer="total_resep_kota_lain"/>
                </Row>
            </ColumnGroup>
          </DataTable>
        </div>
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
  title: 'Transmedic - Laporan Pendapatan Resep',
})

useViewWrapper().setFullWidth(true)
const remakeData: any = ref([])
const namaruangan: any = ref([]);
const namaproduk = ref('');
const periode = ref('');
let dataSource: any = ref([])
let total_kota_cirebon_obat: any = ref([])
let total_kab_cirebon_obat: any = ref([])
let total_kab_kuningan_obat: any = ref([])
let total_kab_indramayu_obat: any = ref([])
let total_kab_majalengka_obat: any = ref([])
let total_kota_lain_obat: any = ref([])

let total_kota_cirebon_alkes: any = ref([])
let total_kab_cirebon_alkes: any = ref([])
let total_kab_kuningan_alkes: any = ref([])
let total_kab_indramayu_alkes: any = ref([])
let total_kab_majalengka_alkes: any = ref([])
let total_kota_lain_alkes: any = ref([])

let total_resep_kota_cirebon_obat: any = ref([])
let total_resep_kab_cirebon_obat: any = ref([])
let total_resep_kab_kuningan_obat: any = ref([])
let total_resep_kab_indramayu_obat: any = ref([])
let total_resep_kab_majalengka_obat: any = ref([])
let total_resep_kota_lain_obat: any = ref([])


let total_resep_kota_cirebon_alkes: any = ref([])
let total_resep_kab_cirebon_alkes: any = ref([])
let total_resep_kab_kuningan_alkes: any = ref([])
let total_resep_kab_indramayu_alkes: any = ref([])
let total_resep_kab_majalengka_alkes: any = ref([])
let total_resep_kota_lain_alkes: any = ref([])

let total_kota_cirebon: any = ref([])
let total_lembar_kota_cirebon: any = ref([])
let total_resep_kota_cirebon: any = ref([])
let total_kab_cirebon: any = ref([])
let total_lembar_kab_cirebon: any = ref([])
let total_resep_kab_cirebon: any = ref([])
let total_kab_kuningan: any = ref([])
let total_lembar_kab_kuningan: any = ref([])
let total_resep_kab_kuningan: any = ref([])
let total_kab_indramayu: any = ref([])
let total_lembar_kab_indramayu: any = ref([])
let total_resep_kab_indramayu: any = ref([])
let total_kab_majalengka: any = ref([])
let total_lembar_kab_majalengka: any = ref([])
let total_resep_kab_majalengka: any = ref([])
let total_kota_lain: any = ref([])
let total_lembar_kota_lain: any = ref([])
let total_resep_kota_lain: any = ref([])
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
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const formatCurrency = (value) => {
  // console.log(value);
  return H.formatRp(value, '')
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

const exportExcel = () => {
  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN PENDAPATAN REESEP BPJS LUNAS'],
    [periode.value],
    [],
    ['', '', '', '', '', '', '',  'Kota Cirebon', '', '', 'Kab. Cirebon','', '', 'Kab. Kuningan', '', '', 'Kab. Indramayu', '', '', 'Kab. Majalengka', '', '', 'Kab. Lain-lain' ],
    [
    'No', 'Nama Pasien', 'NO CM', 'Dokter', 'Ruang', 'Depo', 'Jenis', 'Rp', 'L', 'R', 'Rp', 'L', 'R', 'Rp', 'L', 'R', 'Rp', 'L', 'R', 'Rp', 'L', 'R', 'Rp', 'L', 'R',
    ],
    ...dataSource.value.map((e: any, index: any) => [
      e.no, e.namapasien, e.nocm, e.namalengkap, e.ruangan_pasien, e.depo, e.jenis_barang, e.kota_cirebon, e.lembar_kota_cirebon, e.resep_kota_cirebon, e.kota_cirebon,
      e.lembar_kota_cirebon, e.resep_kab_cirebon, e.kab_kuningan, e.lembar_kab_kuningan, e.resep_kab_kuningan, e.kab_indramayu, e.lembar_kab_indramayu, e.resep_kab_indramayu,
      e.kab_majalengka, e.lembar_kab_majalengka, e.resep_kab_majalengka, e.kota_lain, e.lembar_kota_lain, e.resep_kota_lain
    ]),
    ['','','','','','','Total Obat',
     total_kota_cirebon_obat.value, total_lembar_kota_cirebon.value, total_resep_kota_cirebon_obat.value,
     total_kab_cirebon_obat.value, total_lembar_kab_cirebon.value, total_resep_kab_cirebon_obat.value,
     total_kab_kuningan_obat.value, total_lembar_kab_kuningan.value, total_resep_kab_kuningan_obat.value,
     total_kab_indramayu_obat.value, total_lembar_kab_indramayu.value, total_resep_kab_indramayu_obat.value,
     total_kab_majalengka_obat.value, total_lembar_kab_majalengka.value, total_resep_kab_majalengka_obat.value,
     total_kota_lain_obat.value, total_lembar_kota_lain.value, total_resep_kota_lain_obat.value
    ],
    ['','','','','','','Total Alkes',
     total_kota_cirebon_alkes.value,'', total_resep_kota_cirebon_alkes.value,
     total_kab_cirebon_alkes.value, '', total_resep_kab_cirebon_alkes.value,
     total_kab_kuningan_alkes.value, '', total_resep_kab_kuningan_alkes.value,
     total_kab_indramayu_alkes.value, '', total_resep_kab_indramayu_alkes.value,
     total_kab_majalengka_alkes.value,'', total_resep_kab_majalengka_alkes.value,
     total_kota_lain_alkes.value, '', total_resep_kota_lain_alkes.value
    ],
    ['','','','','','Total','', 
    total_kota_cirebon.value, total_lembar_kota_cirebon.value, total_resep_kota_cirebon.value,
    total_kab_cirebon.value, total_lembar_kab_cirebon.value, total_resep_kab_cirebon.value, 
    total_kab_kuningan.value, total_lembar_kab_kuningan.value, total_resep_kab_kuningan.value, 
    total_kab_indramayu.value, total_lembar_kab_indramayu.value, total_resep_kab_indramayu.value, 
    total_kab_majalengka.value, total_lembar_kab_majalengka.value, total_resep_kab_majalengka.value, 
    total_kota_lain.value, total_lembar_kota_lain.value, total_resep_kota_lain.value
    ]
  ]);

  const columnWidths = [
    { wch: 5 },
    { wch: 30 },
    { wch: 20 },
    { wch: 30 },
    { wch: 30 },
    { wch: 30 },
    { wch: 10 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
    { wch: 20 },
  ];
  worksheet['!cols'] = columnWidths;

  const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 0, c: 23 } };

  const mergeSubtitle1 = { s: { r: 1, c: 0 }, e: { r: 1, c: 23 } };
  const mergeSubtitle2 = { s: { r: 2, c: 0 }, e: { r: 2, c: 23 } };
  const mergeSubtitle4 = { s: { r: 4, c: 0 }, e: { r: 4, c: 23 } };
  const mergeSubtitle5 = { s: { r: 5, c: 0 }, e: { r: 5, c: 23 } };

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
  const desiredFileName = 'laporan_pendapatan_resep' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

async function fetchData() {
  isLoadingBtn.value = true;
  let limit = currentPage.value.limit;
  let offset = route.query.page ? route.query.page : 1;
  offset = offset * limit - limit;
  let rows = currentPage.value.rows;
  let tglAwal = '?tglawal=' + moment(item.value.periode.start).format('YYYY-MM-DD');
  let tglAkhir = '&tglakhir=' + moment(item.value.periode.end).format('YYYY-MM-DD');
  let ruanganfk = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : ''

  const periodeText = `Periode : ${moment(item.value.periode.start).format('DD/MM/YYYY HH:mm:ss')} s/d ${moment(item.value.periode.end).format('DD/MM/YYYY HH:mm:ss')}`;
  periode.value = periodeText;

  await useApi().get('report/farmasi/laporan-pendapatan-resep' + tglAwal + tglAkhir + '&offset=' + offset + '&limit=' + limit + '&rows=' + rows + ruanganfk).then((response) => {
    response.data.forEach((element, i) => {
      element.no = i + 1;
    });
    dataSource.value = response.data;
    // kota cirebom
    total_kota_cirebon.value = calculateTotal(response.data, 'kota_cirebon');
    total_kota_cirebon_obat.value = calculateTotalObat(response.data, 'kota_cirebon');
    total_kota_cirebon_alkes.value = calculateTotalAlkes(response.data, 'kota_cirebon');
    total_lembar_kota_cirebon.value = calculateTotalNoCurrency(response.data, 'lembar_kota_cirebon');
    total_resep_kota_cirebon.value = calculateTotalNoCurrency(response.data, 'resep_kota_cirebon');
    total_resep_kota_cirebon_obat.value = calculateTotalObatNoCurrency(response.data, 'resep_kota_cirebon');
    total_resep_kota_cirebon_alkes.value = calculateTotalAlkesNoCurrency(response.data, 'resep_kota_cirebon');
    // kab cirebon
    total_kab_cirebon.value = calculateTotal(response.data, 'kab_cirebon');
    total_kab_cirebon_obat.value = calculateTotalObat(response.data, 'kab_cirebon');
    total_kab_cirebon_alkes.value = calculateTotalAlkes(response.data, 'kab_cirebon');
    total_lembar_kab_cirebon.value = calculateTotalNoCurrency(response.data, 'lembar_kab_cirebon');
    total_resep_kab_cirebon.value = calculateTotalNoCurrency(response.data, 'resep_kab_cirebon');
    total_resep_kab_cirebon_obat.value = calculateTotalObatNoCurrency(response.data, 'resep_kab_cirebon');
    total_resep_kab_cirebon_alkes.value = calculateTotalAlkesNoCurrency(response.data, 'resep_kab_cirebon');
    // kab kuningan
    total_kab_kuningan.value = calculateTotal(response.data, 'kab_kuningan');
    total_kab_kuningan_obat.value = calculateTotalObat(response.data, 'kab_kuningan');
    total_kab_kuningan_alkes.value = calculateTotalAlkes(response.data, 'kab_kuningan');
    total_lembar_kab_kuningan.value = calculateTotalNoCurrency(response.data, 'lembar_kab_kuningan');
    total_resep_kab_kuningan.value = calculateTotalNoCurrency(response.data, 'resep_kab_kuningan');
    total_resep_kab_kuningan_obat.value = calculateTotalObatNoCurrency(response.data, 'resep_kab_kuningan');
    total_resep_kab_kuningan_alkes.value = calculateTotalAlkesNoCurrency(response.data, 'resep_kab_kuningan');
    // kab indramayu
    total_kab_indramayu.value = calculateTotal(response.data, 'kab_indramayu');
    total_kab_indramayu_obat.value = calculateTotalObat(response.data, 'kab_indramayu');
    total_kab_indramayu_alkes.value = calculateTotalAlkes(response.data, 'kab_indramayu');
    total_lembar_kab_indramayu.value = calculateTotalNoCurrency(response.data, 'lembar_kab_indramayu');
    total_resep_kab_indramayu.value = calculateTotalNoCurrency(response.data, 'resep_kab_indramayu');
    total_resep_kab_indramayu_obat.value = calculateTotalObatNoCurrency(response.data, 'resep_kab_indramayu');
    total_resep_kab_indramayu_alkes.value = calculateTotalAlkesNoCurrency(response.data, 'resep_kab_indramayu');
    // kab majalengka
    total_kab_majalengka.value = calculateTotal(response.data, 'kab_majalengka');
    total_kab_majalengka_obat.value = calculateTotalObat(response.data, 'kab_majalengka');
    total_kab_majalengka_alkes.value = calculateTotalAlkes(response.data, 'kab_majalengka');
    total_lembar_kab_majalengka.value = calculateTotalNoCurrency(response.data, 'lembar_kab_majalengka');
    total_resep_kab_majalengka.value = calculateTotalNoCurrency(response.data, 'resep_kab_majalengka');
    total_resep_kab_majalengka_obat.value = calculateTotalObatNoCurrency(response.data, 'resep_kab_majalengka');
    total_resep_kab_majalengka_alkes.value = calculateTotalAlkesNoCurrency(response.data, 'resep_kab_majalengka');
    // kota_lain
    total_kota_lain.value = calculateTotal(response.data, 'kota_lain');
    total_kota_lain_obat.value = calculateTotalObat(response.data, 'kota_lain');
    total_kota_lain_alkes.value = calculateTotalAlkes(response.data, 'kota_lain');
    total_lembar_kota_lain.value = calculateTotalNoCurrency(response.data, 'lembar_kota_lain');
    total_resep_kota_lain.value = calculateTotalNoCurrency(response.data, 'resep_kota_lain');
    total_resep_kota_lain_obat.value = calculateTotalObatNoCurrency(response.data, 'resep_kota_lain');
    total_resep_kota_lain_alkes.value = calculateTotalAlkesNoCurrency(response.data, 'resep_kota_lain');
  });
  isLoadingBtn.value = false;
}

function calculateTotalObatNoCurrency(data, column) {
  let total = 0;
  data.forEach((row) => {
    if (row['jenis_barang'] === 'Obat' && row[column]) {
      total += parseFloat(row[column]);
    }
  });
  return total;
}

function calculateTotalAlkesNoCurrency(data, column) {
  let total = 0;
  data.forEach((row) => {
    if (row['jenis_barang'] === 'Alkes' && row[column]) {
      total += parseFloat(row[column]);
    }
  });
  return total;
}

function calculateTotalObat(data, column) {
  let total = 0;
  data.forEach((row) => {
    if (row['jenis_barang'] === 'Obat' && row[column]) {
      total += parseFloat(row[column]);
    }
  });
  return formatCurrency(total);
}

function calculateTotalAlkes(data, column) {
  let total = 0;
  data.forEach((row) => {
    if (row['jenis_barang'] === 'Alkes' && row[column]) {
      total += parseFloat(row[column]);
    }
  });
  return formatCurrency(total);
}

function calculateTotal(data, column) {
  let total = 0;
  data.forEach((row) => {
    if (row[column]) {
      total += parseFloat(row[column]);
    }
  });
  return formatCurrency(total);
}

function calculateTotalNoCurrency(data, column) {
  let total = 0;
  data.forEach((row) => {
    if (row[column]) {
      total += parseFloat(row[column]);
    }
  });
  return total;
}

function calculateTotalKabCirebon(data) {
  let totalKotaCirebon = 0;
  data.forEach((row) => {
    if (row.kota_cirebon) {
      totalKotaCirebon += parseFloat(row.kab_cirebon);
    }
  });
  total_kab_cirebon.value = formatCurrency(totalKotaCirebon);
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
