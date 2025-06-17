<template>
  <div class="columns is-multiline">
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Laporan Instalasi Rehab Medik</h3>
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
            <div class="column is-2">
            <VField label="Bulan Awal">
              <Calendar v-model="item.bulan" selectionMode="single" :manualInput="false" class="w-100" :showIcon="true"
                :showTime="false" view="month" :date-format="'MM'" />
            </VField>
          </div>
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
              <template v-for="(kelompok) in namaruangan">
                <Column :header="kelompok.kelompokpasien" :body="formatCurrency" :colspan="2" headerStyle="width: 40%"/>
              </template>
              <Column header="Total Pasien" :rowspan="2" style="min-width: 50%;"/>
              <Column header="Total Biaya" :rowspan="2" style="min-width: 50%;"/>
            </Row>
            <Row>
              <template v-for="(ds, index) in namaruangan" :key="index">
                  <Column :header="index % 2 === 0 ? 'Jumlah Pasien' : 'Biaya' "/>
              </template>
              <template v-for="(ds, index) in namaruangan" :key="index">
                  <Column :header="index % 2 === 0 ? 'Jumlah Pasien' : 'Biaya' "/>
              </template>
            </Row>
          </ColumnGroup>
          <Column field="namaruangan"/>
          <template v-for="(ds, index) in namaruangan" :key="index">
              <Column :field="`${ds.kelompokpasien+'jumlah_pasien'}`"/>
              <Column :field="`${ds.kelompokpasien+'total_pendapatan'}`"/>
            </template>
          <Column field="jumlah_pasien"></Column>
          <Column field="total_ruangan"></Column>
          <ColumnGroup type="footer">
              <Row>
                <Column footer="TOTAL" footerStyle="text-align:center" />
                <template v-for="(ds, index) in arrayTotal">
                  <Column :footer="index % 2 == 0 ? ds.value : H.formatRupiah(ds.value.toFixed(2), 'Rp. ')" footerStyle="text-align:center" />
                </template>
                <Column :footer="grandTotalPasien" footerStyle="text-align:center"/>
                <Column :footer="H.formatRp(grandTotal, 'Rp. ')" footerStyle="text-align:center"/>
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
import Calendar from 'primevue/calendar'
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
  title: 'ULAB - Laporan Penggunaan Obat Alkes',
})

useViewWrapper().setFullWidth(true)
const remakeData: any = ref([])
const namaruangan: any = ref([]);
const namaproduk = ref('');
const periode = ref('');
let dataSource: any = ref([])
let totalPendapatan: any = ref([])
let grandTotal: any = ref(0)
let grandTotalPasien: any = ref(0)
let grandTotalLembar: any = ref(0)
let isLoading: any = ref(false)
let isLoadingBtn: any = ref(false)
let arrayTotal: any = ref([])
let item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  bulan: new Date(),
})
let listRuanganStok: any = ref([])
let listNamaProduk: any = ref([])
const route = useRoute()

const formatCurrency = (value) => {
  console.log(value);
  return H.formatRp(value, '')
}

const exportExcel = () => {
  const columnRuangan = ['SUB UNIT']
  const RPLR = ['']
  const rows = []

  namaruangan.value.forEach(element=>{
    columnRuangan.push(element.kelompokpasien)
    columnRuangan.push('')
    RPLR.push('Jumlah Pasien')
    RPLR.push('Biaya')
  })
  columnRuangan.push('Total')
  RPLR.push('Jumlah Pasien')
  RPLR.push('Biaya')

  const worksheet = XLSX.utils.aoa_to_sheet([
    ['PEMERINTAH KOTA CIREBON'],
    ['RSD GUNUNG JATI'],
    ['Jl. Kesambi No.56, Kesambi, Kec. Kesambi, Kota Cirebon, Jawa Barat 45134 (0231) 206330'],
    [],
    ['LAPORAN INSTALASI REHAB MEDIK'],
    [periode.value],
    [],
    columnRuangan,
    RPLR,
    ...dataSource.value.map((e) => {
      const row = [e.namaruangan];
      const namakelompokData = namaruangan.value.map((element) => [
        !isNaN(e[`${element.kelompokpasien}jumlah_pasien`]) ? parseFloat(e[`${element.kelompokpasien}jumlah_pasien`]) : '',
        !isNaN(e[`${element.kelompokpasien}total_pendapatan`]) ? parseFloat(e[`${element.kelompokpasien}total_pendapatan`]) : '',
      ]);
      row.push(...namakelompokData.flat());
      row.push(e.jumlah_pasien)
      row.push(e.total_ruangan)
      // row.push(
      //   namakelompokData.reduce((acc, val) => acc + val[0], 0), // Total Pendapatan
      //   namakelompokData.reduce((acc, val) => acc + val[1], 0), // Total Lembar
      //   namakelompokData.reduce((acc, val) => acc + val[2], 0)  // Total Resep
      // );
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
  const desiredFileName = 'laporan_instalasi_rehab_medik' + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
}

async function fetchData() {
  await getRajal()
  await getRanap()
}

async function getRajal() {
  isLoadingBtn.value = true;
  let tglAwal = 'tglawal=' + moment(item.value.bulan).startOf('month').format('YYYY-MM-DD');
  let tglAkhir = '&tglakhir=' + moment(item.value.bulan).endOf('month').format('YYYY-MM-DD');
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk}` : '';
  const newObject = {namaruangan: 'JUMLAH I'}
  let totalPasienRajal = 0
  let totalPendapatanRajal = 0

  periode.value = `${moment(item.value.periode.start).format('YYYY-MM-DD')} sampai ${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  const response = await useApi().get(`report/laporan-rehab-medik?${tglAwal}${tglAkhir}`)
  dataSource.value = [{namaruangan: 'RAWAT JALAN'}]
// Fungsi untuk mengambil total pendapatan dan jumlah pasien untuk setiap kelompok pasien di setiap ruangan
function getRuanganData(ruangan) {
    const ruanganData = { namaruangan: ruangan.namaruangan };
    let total = 0
    let totalPasien = 0
    ruangan.groups.forEach(group => {
        ruanganData[`${group.kelompokpasien}total_pendapatan`] = group.total_pemasukan.toFixed(2);
        // ruanganData[`${group.kelompokpasien}total_pendapatan`] = H.formatRupiah(group.total_pemasukan.toFixed(2), '');
        ruanganData[`${group.kelompokpasien}jumlah_pasien`] = group.jumlah_pasien;
        total += group.total_pemasukan
        totalPasien += group.jumlah_pasien
    });
    ruanganData['total_ruangan'] = total
    ruanganData['jumlah_pasien'] = totalPasien
    totalPasienRajal += totalPasien
    totalPendapatanRajal += total
    grandTotal.value += total
    grandTotalPasien.value += totalPasien
    return ruanganData;
}

  if(response != null){
    response.forEach((element, index) => {
      const newData = {
        'jenis_pelayanan': `${element.namaruangan}`,
      };
      namaruangan.value = element.groups.sort((a, b) => a.kelompokpasien.localeCompare(b.kelompokpasien));
    });
    const responseData = response.map(ruangan => getRuanganData(ruangan));
    dataSource.value = dataSource.value.concat(responseData)
    const kelompokPasienTotalGlobal = {};

// Iterasi melalui setiap ruangan dan grup
    response.forEach(ruangan => {
        ruangan.groups.forEach(group => {
            // Menambahkan total pendapatan untuk kelompok pasien ke dalam total global
            kelompokPasienTotalGlobal[`${group.kelompokpasien}jumlah_pasien`] = (kelompokPasienTotalGlobal[`${group.kelompokpasien}jumlah_pasien`] || 0) + parseFloat(group.jumlah_pasien);
            kelompokPasienTotalGlobal[`${group.kelompokpasien}total_pendapatan`] = (kelompokPasienTotalGlobal[`${group.kelompokpasien}total_pendapatan`] || 0) + parseFloat(group.total_pemasukan);
        });
    });

    totalPendapatan.value = []

    for (const kelompokPasien in kelompokPasienTotalGlobal) {
    // Assigning key-value pairs to the object newObject
        newObject[kelompokPasien] = kelompokPasienTotalGlobal[kelompokPasien];
      }
      newObject['jumlah_pasien'] = totalPasienRajal
      newObject['total_ruangan'] = totalPendapatanRajal
    dataSource.value = dataSource.value.concat(newObject)
    totalPendapatan.value.push(newObject)

    // totalPendapatan.value = kelompokPasienTotalGlobal
    isLoadingBtn.value = false;
  }
}

async function getRanap() {
  isLoadingBtn.value = true;
  let tglAwal = 'tglawal=' + moment(item.value.bulan).startOf('month').format('YYYY-MM-DD');
  let tglAkhir = '&tglakhir=' + moment(item.value.bulan).endOf('month').format('YYYY-MM-DD');
  let produkfk = item.value.nmProduk ? `&idproduk=${item.value.nmProduk}` : '';
  let isRanap = '&isRanap=true'
  const newObject = {namaruangan: 'JUMLAH II'}
  let totalPasienRanap = 0
  let totalPendapatanRanap = 0

  periode.value = `${moment(item.value.periode.start).format('YYYY-MM-DD')} sampai ${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  const response = await useApi().get(`report/laporan-rehab-medik?${tglAwal}${tglAkhir}${isRanap}`)
  dataSource.value = dataSource.value.concat([{namaruangan: 'RAWAT INAP'}])
// Fungsi untuk mengambil total pendapatan dan jumlah pasien untuk setiap kelompok pasien di setiap ruangan
function getRuanganData(ruangan) {
    const ruanganData = { namaruangan: ruangan.namaruangan };
    let total = 0
    let totalPasien = 0
    ruangan.groups.forEach(group => {
        ruanganData[`${group.kelompokpasien}total_pendapatan`] = group.total_pemasukan.toFixed(2);
        // ruanganData[`${group.kelompokpasien}total_pendapatan`] = H.formatRupiah(group.total_pemasukan.toFixed(2), '');
        ruanganData[`${group.kelompokpasien}jumlah_pasien`] = group.jumlah_pasien;
        total += group.total_pemasukan
        totalPasien += group.jumlah_pasien
    });
    ruanganData['total_ruangan'] = total
    ruanganData['jumlah_pasien'] = totalPasien
    totalPasienRanap += totalPasien
    totalPendapatanRanap += total
    grandTotal.value += total
    grandTotalPasien.value += totalPasien
    return ruanganData;
}

  if(response != null){
    response.forEach((element, index) => {
      const newData = {
        'jenis_pelayanan': `${element.namaruangan}`,
      };
      namaruangan.value = element.groups.sort((a, b) => a.kelompokpasien.localeCompare(b.kelompokpasien));
    });
    const responseData = response.map(ruangan => getRuanganData(ruangan));
    dataSource.value = dataSource.value.concat(responseData)
    const kelompokPasienTotalGlobal = {};

// Iterasi melalui setiap ruangan dan grup
    response.forEach(ruangan => {
        ruangan.groups.forEach(group => {
            // Menambahkan total pendapatan untuk kelompok pasien ke dalam total global
            kelompokPasienTotalGlobal[`${group.kelompokpasien}jumlah_pasien`] = (kelompokPasienTotalGlobal[`${group.kelompokpasien}jumlah_pasien`] || 0) + parseFloat(group.jumlah_pasien);
            kelompokPasienTotalGlobal[`${group.kelompokpasien}total_pendapatan`] = (kelompokPasienTotalGlobal[`${group.kelompokpasien}total_pendapatan`] || 0) + parseFloat(group.total_pemasukan);
        });
    });
    arrayTotal.value = []
    for (const kelompokPasien in kelompokPasienTotalGlobal) {
    // Assigning key-value pairs to the object newObject
        newObject[kelompokPasien] = kelompokPasienTotalGlobal[kelompokPasien];
      }
      newObject['jumlah_pasien'] = totalPasienRanap
      newObject['total_ruangan'] = totalPendapatanRanap
      for (const kelompokPasien in kelompokPasienTotalGlobal) {
      totalPendapatan.value.forEach(element => {
          arrayTotal.value.push({value: element[kelompokPasien] + kelompokPasienTotalGlobal[kelompokPasien], label: kelompokPasien})
        });
      }
    dataSource.value = dataSource.value.concat(newObject)

    // totalPendapatan.value = kelompokPasienTotalGlobal
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
