
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
                <div class="column is-3 pt-0 pb-0">
                  <span>Ruangan</span>
                  <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" />
                    </VControl>
                  </VField>
                </div>
                <div class="column pt-0 pb-0 is-4">
                  <span>Departemen</span>
                  <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                    <VControl icon="feather:search">
                      <AutoComplete v-model="item.departemen" :suggestions="d_departemen"
                        @complete="fetchDepartemen($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                        :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pasien..." />
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
                  <div class="column" style="margin-left: auto !important;">
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
        <h3 class="title is-5 mb-2">Laporan Kunjungan</h3>
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
              :rowsPerPageOptions="[10, 25, 50]" scrollable
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
              <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                <template #body="slotProps">
                  <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                </template>
              </Column>
              <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
              <Column field="tglregistrasi" header="Tanggal" :sortable="true" style="min-width: 200px">
                <template #body="slotProps">
                  <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span>
                </template>
              </Column>
              <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width: 200px"></Column>
              <Column field="kelompokpasien" header="Bayar" :sortable="true" style="min-width: 100px"></Column>
              <Column field="namarekanan" header="Rekanan" :sortable="true" style="min-width: 100px"></Column>
              <Column field="diagnosa" header="Diagnosa" :sortable="true" style="min-width: 100px"></Column>
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
title: 'Laporan Insidensi Pegawai Sakit - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const item: any = ref({
qFilterTgl: {
    start: new Date(),
    end: new Date()
},
})


const currentPage: any = ref({
limit: 5,
rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_departemen: any = ref([])
let d_KelompokPasien: any = ref([])
let d_JenisPasien: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')


const fetchDepartemen = async (filter: any) => {
    d_departemen.value = [{ value: '9', label: 'IGD' }, { value: '16', label: 'Rawat Inap' }, { value: '18', label: 'Rawat Jalan' },]
}

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
        item.value.kelompokpasien = d_KelompokPasien.value
        console.log("kelompok pasien,", item.value.kelompokpasien)
    })
    await fetchData()
}

const fetchData = async () => {
    isPlaceLoad.value = true
    let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
    let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
    let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
    let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasien=${item.value.kelompokpasien[0].value}` : ''
    let departemen = item.value.departemen ? `&departemen=${item.value.departemen.value}` : ''

    await useApi().get(`pelayanan/get-laporan-insidensi-pegawai-sakit?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}${kelompokpasien}${departemen}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        dataSource.value = response.data
    })
    isPlaceLoad.value = false
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const exportExcel = () => {
    console.log(dataSource.value)
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            NamaPasien: maskNamaPasien(e.namapasien),
            NoRM: e.nocm, 
            Tanggal: e.tglregistrasi,
            Ruangan: e.namaruangan,
            Bayar: e.kelompokpasien,
            Rekanan: e.namarekanan,
            Diagnosa: e.diagnosa,
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    // Make sure buffer is a valid object and not undefined or null
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    // Check if window.URL and window.open are available
    const _url = window.URL?.createObjectURL(data);
    if (_url) {
        // Open the URL in a new window if _url is valid
        const openedWindow = window.open(_url, EXCEL_EXTENSION);
        if (openedWindow) {
            openedWindow.focus();
        }
    } else {
        console.error("Failed to create object URL.");
    }
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
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

fetchKelompokPasien({ query: "Karyawan RS" })
</script>
  