
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column pt-0 pb-0 is-4">
                <span>Dokter</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Dokter..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0 pb-0">
                <span>Ruangan</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-4">
                <span>Kelompok Pasien</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien" @complete="fetchKelompokPasien($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Kelompok Pasien..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-4">
                <span>Jenis Pasien</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.jenispasien" :suggestions="d_JenisPasien" @complete="fetchJenisPasien($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pasien..."/>
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
                <div class="column" style="margin-left: auto:  !important;">
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
      <h3 class="title is-5 mb-2">Daftar Pasien Rawat Inap</h3>
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
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="20"
            :rowsPerPageOptions="[20, 40, 60]" scrollable
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
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
            <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 100px"></Column>
            <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tgllahir) }}</span>
              </template>
            </Column>
            <Column field="asalrujukan" header="Cara Masuk" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tglregistrasi" header="Tanggal Masuk" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tglmasuk) }}</span>
              </template>
            </Column>
            <Column field="jenis_pasien" header="Jenis Pasien" :sortable="true" style="min-width: 200px"></Column>
            <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaruanganasal" header="Ruangan Asal" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaruanganrawat" header="Ruangan Rawat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakelas" header="Kelas Rawat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakamar" header="Kamar" :sortable="true" style="min-width: 200px"></Column>
            <Column field="bed" header="No Bed" :sortable="true" style="min-width: 200px"></Column>
            <Column field="lamarawat" header="Lama Rawat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakotakabupaten" header="Asal Kota/Kabupaten" :sortable="true" style="min-width: 200px"></Column>
            <!-- <Column field="bayar" header="Bayar" :sortable="true" style="min-width: 100px"></Column> -->
            <Column field="statuspasien" header="Status" :sortable="true" style="min-width: 100px">
              <template #body="slotProps">
                <VTag class="ml-4" color="primary" rounded>{{ slotProps.data.statuspasien }}</VTag>
                <!-- <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span> -->
              </template>
            </Column>
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
  title: 'Daftar Pasien Rawat Inap - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

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
let d_KelompokPasien: any = ref([])
let d_JenisPasien: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
  if (!item.value.qFilter) {
    return dataHutang.value
  }
  return dataHutang.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
      items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const fetchJenisPasien = async (filter: any) => {
    d_JenisPasien.value = [{value: 'LAMA', label:'LAMA'},{value: 'BARU', label:'BARU'},]
}

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = 'tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
  let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasien=${item.value.kelompokpasien.value}` : ''
  let statuspasien = item.value.jenispasien ? `&statuspasien=${item.value.jenispasien.value}` : ''

  await useApi().get(`dashboard/get-daftarpasienrawatinap?${tglAwal}${tglAkhir}${ruanganfk}${dokter}${nama}${kelompokpasien}${statuspasien}`).then((response: any) => {
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
      NamaPasien: e.namapasien, NoRM: e.nocm, NoRegistrasi: e.noregistrasi, JenisKelamin: e.jeniskelamin, TanggalLahir: e.tgllahir, TanggalMasuk: e.tglmasuk, KelompokPasien: e.jenis_pasien,
      Tanggal: e.tglmasuk, Dokter: e.dokter,RuanganAsal: e.namaruanganasal , RuanganRawat: e.namaruanganrawat, Kelas: e.namakelas, Kamar: e.namakamar, Bed: e.bed, LamaRawat: e.lamarawat, KotaKabupaten: e.namakotakabupaten,
      Status: e.statuspasien,
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
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus();
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

fetchData()
</script>
