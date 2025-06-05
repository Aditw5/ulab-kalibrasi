
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
                    @click="fetchData()" :loading="isLoading">
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
      <div class="column" v-if="isPlaceLoad">
        <VPlaceloadWrap v-for="data in 25">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>

      <div class="column p-0" v-else>
        <div class="column c-title">
          <div class="columns">
            <div class="column is-10 pt-0">
              <label class="title-page">Stok Opname</label>
              <!-- <label for="">Form Stok Opname</label> -->
            </div>
          </div>
        </div>

        <div class="column">
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
            :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLinsk PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
            <template #header>
              <div class="flex justify-content-between">
                <VButton icon="fas fa-file-excel" color="primary"> Export</VButton>
                <VButton icon="fas fa-plus-square" color="info" :to="{ name: 'module-logistik-form-produk-kadaluarsa' }">
                  Tambah</VButton>
              </div>
            </template>
            <Column field="no" header="No"></Column>
            <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 250px"></Column>
            <Column field="qtyproduk" header="Stok" frozen :sortable="true" style="min-width: 50px"></Column>
            <Column field="kdproduk" header="Kode Sirs" :sortable="true" style="min-width: 50px"></Column>
            <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width: 150px"></Column>
            <Column header="Tanggal Kadaluarsa" :sortable="true" style="min-width: 80px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tglkadaluarsa) }}</span>
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
useHead({
  title: 'Daftar Barang Kadaluarsa - ' + import.meta.env.VITE_PROJECT,
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
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')


const fetchData = async () => {
  isPlaceLoad.value = true
  isLoading.value = true
  let tglAwal = '?tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  // let ruanganfk = item.value.ruanganfk ? `&ruanganId=${item.value.ruanganfk.value}` : ''
  // let dokter = item.value.dokterfk ? `&dokter=${item.value.dokterfk.value}` : ''
  // let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''

  await useApi().get(`dashboard/logistik/get-barang-kadaluarsa${tglAwal}${tglAkhir}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    isPlaceLoad.value = false
    dataSource.value = response.data
  })
  isLoading.value = false

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



fetchData()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/sysadmin/master-data.scss';
</style>
