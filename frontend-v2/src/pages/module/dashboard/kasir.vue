<template>
  <div class="soccer-dashboard">
    <div class="soccer-dashboard-inner">
      <div class="columns">
        <div class="column is-4">
          <div class="live-match">
            <div class="head">
              <h3 class="title is-6">
                &nbsp;
                <VTag @click="modalFilter = true" color="danger" rounded elevated class="is-pulled-right
                  is-clickable">{{
                    H.formatDateNoTime(item.filterDate.start) != H.formatDateNoTime(item.filterDate.end) ?
                    H.formatDateNoTime(item.filterDate.start) + ' - ' +
                    H.formatDateNoTime(item.filterDate.end) : H.formatDateNoTime(item.filterDate.start)
                  }} <i class="fas fa-filter ml-3 " aria-hidden="true"></i></VTag>
              </h3>
            </div>
            <div class="match">
              <div class="left">
                <img class="team-logo" src="/images/avatars/svg/totalpasien.png" alt="" />
                <span class="team-name">Total Pasien</span>
              </div>
              <div class="center">
                <span class="score">{{ item.totalPending }}</span>
                <span class="separator">:</span>
                <span class="score">{{ item.totalSelesai }}</span>
              </div>
              <div class="right">
                <img class="team-logo" src="/images/avatars/svg/kwitansi.png" alt="" />
                <span class="team-name">Total Kwitansi</span>
              </div>
            </div>
          </div>
          <div class="leagues">
            <div class="head" style="display: flex;justify-content: space-between;margin-bottom:0.5rem">
              <h3 class="title is-6">Daftar Pasien Pulang</h3>
              <VIconButton circle icon="feather:refresh-cw" raised bold @click="fetchPulang" :loading="isLoading"
                v-tooltip.bubble="'Cari'" class="mt-2-min">
              </VIconButton>
            </div>
            <div class="columns is-multiline pb-3 pt-3">
              <div class="column is-6 p-0">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="fetchPulang()"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-6 p-0 pl-1">
                <VField class="is-rounded-select is-autocomplete-select">
                  <VControl icon="feather:search" class="prime-auto-select">
                    <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien" @complete="fetchKelompokPasien($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"  @item-select="fetchPulang()"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Kelompok Pasien..."/>
                  </VControl>
                </VField>
                <!-- <VField class="is-rounded-select is-autocomplete-select">
                  <VControl icon="feather:search" class="prime-auto-select" :loading="isLoadingSelect">
                    <Dropdown v-model="item.kelompokPasien" :options="d_KelompokPasien" :optionLabel="'label'"
                      placeholder="Kelompok Pasien" style="width: 100%;" showClear :filter="true" />
                  </VControl>
                </VField> -->
              </div>
            </div>
            <div class="leagues-list">
              <div class="columns is-multiline  p-0 mb-0">
                <div class="column is-12 p-0" style="margin:0">
                  <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField addons>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.start" v-on="inputEvents.start" />
                        </VControl>
                        <VControl>
                          <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                        </VControl>
                        <VControl icon="feather:calendar">
                          <VInput :value="inputValue.end" v-on="inputEvents.end" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column is-9 p-0">
                  <VField>
                    <VControl icon="feather:search">
                      <input v-model="item.qnama" type="text" class="input " placeholder="Search..." v-on:keyup.enter="fetchPulang()" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-3 pt-0 pr-0 mb-0">
                  <VField>
                    <VControl icon="feather:bar-chart">
                      <input v-model="item.qRows" type="text" class="input " placeholder="Row..." />
                    </VControl>
                  </VField>
                </div>
              </div>
              <!-- <div class="field">
                <div class="control">
                  <input type="text" v-model="item.qFilterPulang" class="input" placeholder="Search..." />
                </div>
              </div> -->
              <div class="tile-grid tile-grid-v2">
                <p style="font-size: 0.8rem; padding: 0px;margin:0px">
                  Menampilkan <b>{{ dataSourcePulang_F.length }}</b> data .
                </p>
                <TransitionGroup name="list" tag="div" class="columns is-multiline p-0"
                  style=" max-height: 1000px;  overflow: auto;margin-top: 0px !important;">
                  <div class="list-view list-view-v1 p-2 mt-0 mb-0" v-if="isLoading">
                    <div class="list-view-inner">
                      <div v-for="key in 5" :key="key" class="list-view-item">
                        <VPlaceloadWrap>
                          <VPlaceloadAvatar size="medium" />
                          <VPlaceloadText last-line-width="80%" class="mx-2" />
                          <VPlaceload class="mx-2" disabled />
                          <VPlaceload class="mx-2 h-hidden-tablet-p" />
                        </VPlaceloadWrap>
                      </div>
                    </div>
                  </div>
                  <VPlaceholderPage v-if="dataSourcePulang_F.length == 0 && isLoading == false" :title="''"
                    :subtitle="H.assets().notFound">
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                    </template>
                  </VPlaceholderPage>
                  <div v-else v-for="item in dataSourcePulang_F" :key="item.id" class="column is-12 ">
                    <div class="tile-grid-item" style="padding:0.75rem">
                      <VDropdown icon="feather:more-vertical" spaced right class="is-pulled-right">
                        <template #content>
                          <a role="menuitem" @click="detailVerifikasi(item)" class="dropdown-item is-media">
                            <div class="icon">
                              <i aria-hidden="true" class="lnir lnir-checkmark-circle"></i>
                            </div>
                            <div class="meta">
                              <span>Detail Verifikasi</span>
                            </div>
                          </a>
                          <a role="menuitem" @click="billing(item)" class="dropdown-item is-media">
                            <div class="icon">
                              <i aria-hidden="true" class="lnir lnir-file-name"></i>
                            </div>
                            <div class="meta">
                              <span>Billing</span>
                            </div>
                          </a>
                          <hr class="dropdown-divider" />
                          <a role="menuitem" @click="batalPulang(item)" class="dropdown-item is-media" v-if="item.isallowbatalplg">
                            <div class="icon">
                              <i aria-hidden="true" class="lnil lnil-reload"></i>
                            </div>
                            <div class="meta">
                              <span>Batal Pulang</span>
                            </div>
                          </a>
                        </template>
                      </VDropdown>
                      <div class="tile-grid-item-inner">
                        <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared bordered />
                        <div class="meta">
                          <span class="dark-inverted">{{ item.namapasien }}</span>
                          <span class="dark-inverted">{{ item.nocm }}</span>
                          <span class="dark-inverted">{{ H.formatDateIndoSimple(item.tglpulang) }}</span>
                          <span class="dark-inverted">{{ item.kelompokpasien }}</span>
                          <p style="font-size: 0.8rem; padding-top: 0px;margin-top:-5px">
                            <i class="iconify mr-2 mt-2" data-icon="fa:bed"></i>
                            {{ item.namaruangan }}
                          </p>
                          <p style="font-size: 0.8rem; padding-top: 0px;margin-top:2px">
                          <table class="w-100">
                            <tr>
                              <td>Total Tagihan</td>
                              <td style="width:10px">:</td>
                              <td style="font-weight:bold"> {{ H.formatRp(item.totaltagihan, 'Rp.') }}</td>
                            </tr>
                            <tr>
                              <td>Total Klaim</td>
                              <td>:</td>
                              <td style="font-weight:bold"> {{ H.formatRp(item.totalklaim, 'Rp.') }}</td>
                            </tr>
                            <tr>
                              <td>Piutang Pasien</td>
                              <td>:</td>
                              <td style="font-weight:bold"> {{ H.formatRp(item.totalklaim_pasien, 'Rp.') }}</td>
                            </tr>
                            <tr v-if="item.totaliurbayar != 0">
                              <td>IUR Bayar</td>
                              <td>:</td>
                              <td style="font-weight:bold"> {{ H.formatRp(item.totaliurbayar, 'Rp.') }}</td>
                            </tr>
                            <tr>
                              <td>Sisa Tagihan</td>
                              <td>:</td>
                              <td style="font-weight:bold"> {{ H.formatRp(item.sisa, 'Rp.') }}</td>
                            </tr>
                          </table>
                          </p>

                        </div>
                      </div>
                      <div style="justify-content: space-between;display: flex;margin-top:10px">
                        <VButton color="success" icon="feather:arrow-right" raised outlined @click="verifikasi(item)"
                          class="btn-slim">
                          Verifikasi </VButton>
                        <VTag :label="item.statusbayar" :color="item.color_statusbayar"
                          style="margin-left: 10px; margin-top: 10px;" rounded elevated />
                      </div>
                    </div>
                  </div>
                </TransitionGroup>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-8">
          <div class="illustration-header-2">
            <div class="header-image">
              <img src="/@src/assets/illustrations/dashboards/lifestyle/kasir.png" alt=""
                style="max-width:85%; margin-left: 4rem; margin-top: 1.5rem;" />
            </div>
            <div class="header-meta">
              <p><i class="fas fa-cash-register"></i> Kasir </p>
              <h3 style="color:white"> Layanan Kasir</h3>
              <p>
                Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
              </p>
            </div>
          </div>

          <TabView class="tabview-custom mt-3" :scrollable="true" @tab-click="klikTab($event)">
            <TabPanel>
              <template #header>
                <span>Daftar Tagihan Pasien</span>
                <!-- <i class="fas fa-money-bill-wave ml-2" aria-hidden="true"></i>  -->
              </template>
              <DaftarTagihan v-if="activeTab == 0" />
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>Daftar Tagihan Non Layanan</span>
                <!-- <i class="pi pi-user ml-2"></i> -->
              </template>
              <DaftarTagihanNonLayanan :hideColumn="true" v-if="activeTab == 1" />
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>Daftar Pasien Aktif</span>
                <!-- <i class="pi pi-cog ml-2"></i> -->
              </template>
              <DaftarPasienAktif v-if="activeTab == 2" />
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>Daftar Piutang</span>
                <!-- <i class="pi pi-cog ml-2"></i> -->
              </template>
              <DaftarPiutangPasien :hideColumn="true" v-if="activeTab == 3" />
            </TabPanel>
            <TabPanel>
              <template #header>
                <span>Daftar Penerimaan</span>
                <!-- <i class="pi pi-cog ml-2"></i> -->
              </template>
              <DaftarPenerimaan :hideColumn="true" v-if="activeTab == 4" />
            </TabPanel>
          </TabView>

          <!--
          <VTabs slider centered selected="tagihan" :tabs="[
            { label: 'Daftar Tagihan Pasien', value: 'tagihan' },
            { label: 'Daftar Tagihan Non Layanan', value: 'nonlayanan' },
            { label: 'Daftar Pasien Aktif', value: 'pasienaktif' },
            { label: 'Daftar Piutang', value: 'piutang' },
            { label: 'Daftar Penerimaan', value: 'sbm' },

          ]" style="margin-top: 2rem;;">
            <template #tab="{ activeValue }">
              <p v-if="activeValue === 'tagihan'">
              <div class="matches-card">
                <DaftarTagihan></DaftarTagihan>
              </div>
              </p>
              <p v-else-if="activeValue === 'piutang'">

              </p>
              <p v-else-if="activeValue === 'sbm'">
                <DaftarPenerimaan :hideColumn="true"></DaftarPenerimaan>
              </p>
            </template>
          </VTabs> -->
        </div>
      </div>
    </div>
  </div>
  <VModal :open="modalFilter" title=" Periode" :noclose="false" size="small" actions="right" @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterDate" is-range class="is-centered" trim-weeks :max-date="new Date()" />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="fetchTotal()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>
  <Dialog v-model:visible="modalDetail" modal header="Detail Verifikasi" :style="{ width: '50vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <div class="flex-list-inner mb-2 mt-5" v-if="isLoadingDetail">
          <div class="flex-table-item grid-item mb-1" v-for="key in 5" :key="key">
            <VPlaceloadWrap>
              <VPlaceloadAvatar size="small" />
              <VPlaceloadText last-line-width="60%" class="mx-2" />
              <VPlaceload class="mx-2" disabled />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2 h-hidden-tablet-p" />
              <VPlaceload class="mx-2" />
            </VPlaceloadWrap>
          </div>
        </div>
        <div class="flex-list-inner" v-else-if="dataSourceDetail.length === 0">
          <VPlaceholderSection :title="H.assets().notFound" class="my-6">
            <template #image>
              <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" style="width: 100px;" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                style="width: 100px;" />
            </template>
          </VPlaceholderSection>
        </div>
        <div v-else-if="dataSourceDetail.length > 0" style="max-height: 400px;overflow-x: auto;padding: 10px;">
          <VFlexTable v-if="dataSourceDetail.length" :data="dataSourceDetail" :columns="columns" rounded>
            <template #body>
              <div name="list" tag="div" class="flex-list-inner">
                <!--Table item-->
                <div v-for="(item, i)  in dataSourceDetail" :key="item.id" class="flex-table-item">

                  <VFlexTableCell>
                    <span class="light-text">{{ H.formatDateNoTime(item.tglstruk) }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ item.nostruk }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <VTag :label="item.status" color="warning" rounded elevated
                      style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Belum Bayar'" />
                    <VTag :label="item.status" color="success" rounded elevated
                      style="font-size:0.8rem;font-weight: bold;" v-if="item.status == 'Lunas'" />
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text is-pulled-right">{{ H.formatRp(item.totalharusdibayar, 'Rp. ') }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell v-if="item.totalsudahdibayar !== '0'">
                    <span class="light-text is-pulled-right">{{ H.formatRp(item.totalsudahdibayar, 'Rp. ') }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell>
                    <span class="light-text">{{ item.petugasverif }}</span>
                  </VFlexTableCell>
                  <VFlexTableCell :column="{ align: 'end' }">
                    <VIconButton circle icon="fas fa-times-circle" color="danger" raised bold @click="batalVerif(item)"
                      v-tooltip.bubble="'Batal Verifikasi'">
                    </VIconButton>
                  </VFlexTableCell>
                </div>
              </div>
            </template>
          </VFlexTable>
          <VCard>
            <div class="columns is-multiline">
              <div class="column is-4 is-offset-8">
                <b>TOTAL : {{ H.formatRp(item.totalAll, 'Rp. ') }}</b>
              </div>
            </div>
          </VCard>

        </div>
      </div>
    </div>
  </Dialog>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import TabView from 'primevue/tabview';
import AutoComplete from 'primevue/autocomplete';
import TabPanel from 'primevue/tabpanel';
import DaftarTagihan from '../kasir/daftar-tagihan.vue';
import DaftarTagihanNonLayanan from '../kasir/daftar-tagihan-non-layanan.vue';
import DaftarPasienAktif from '../kasir/daftar-pasien-aktif-kasir.vue';
import DaftarPenerimaan from '../kasir/daftar-penerimaan.vue';
import DaftarPiutangPasien from '../kasir/daftar-piutang-pasien.vue';
// const daftarTagihan = () => import('../kasir/daftar-tagihan.vue')


useHead({
  title: 'Dashboard Kasir - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const activeTab = ref(0)
const router = useRouter()
const modalInput = ref(false)
const modalDetail = ref(false)
const isLoadingDetail = ref(false)
const dataSourceDetail: any = ref([])
const d_Ruangan: any = ref([])
const d_KelompokPasien: any = ref([])
const item: any = ref({
  qAktif: true,
  filterDate: reactive({
    start: new Date(),
    end: new Date(),
  }),
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
  qPeriode: [
    new Date(),
    new Date()
  ],
  totalPending: 0,
  totalSelesai: 0,
  c_antrian: 0,
  c_dilayani: 0,
  c_registrasi: 0,
  c_reservasi: 0,
  totalAll: 0,
  qRows: 50
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const columns = {
  tglstruk: 'TGL',
  nostruk: 'NO VERIF',
  status: 'STATUS',
  totalharusdibayar: {
    label: 'SUBTOTAL',
    cellClass: 'h-hidden-tablet-p',
  },
  totalsudahdibayar: 'PIUTANG TERBAYAR',
  petugasverif: 'PETUGAS',
  actions: {
    label: 'Batal',
    align: 'end',
  },
} as const
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let isLoading: any = ref(false)
const filters = ref('')
// if (dataSourceDetail.value.totalsudahdibayar === "0") {
//   delete columns.totalsudahdibayar;
// }
// console.log(dataSourceDetail.length)

const dataSourcePulang_F = computed(() => {
  if (!item.value.qnama) {
    return dataSourcePulang.value
  }

  let key = new RegExp(item.value.qnama, 'i')
  return dataSourcePulang.value.filter((item: any) => {
    return (
      item.namapasien.match(key) ||
      item.nocm.match(key) ||
      item.noregistrasi.match(key)
    )
  })
})

const dataTagihanLunas = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return items.namapasien.match(new RegExp(filters.value, 'i'))
  })
})

const route = useRoute()
isLoading.value = false

async function fetchJumlah() {
  // await useApi()
  //   .get(`/dashboard/order-today`)
  //   .then((response: any) => {
  //     item.value = response
  //   })
}

async function fetchTotal() {

  let dari = H.formatDate(item.value.filterDate.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.value.filterDate.end, 'YYYY-MM-DD')

  isLoading.value = true
  item.value.totalPending = 0
  item.value.totalSelesai = 0
  const response = await useApi().get(`/dashboard/kasir?dari=${dari}&sampai=${sampai}`)
  item.value.totalPending = response.c_total
  item.value.totalSelesai = response.c_lunas
  isLoading.value = false
  modalFilter.value = false
}

async function fetchPulang() {
  let dari = ''
  if (item.value.qFilterTgl) {
    dari = H.formatDate(item.value.qFilterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.qFilterTgl) {
    sampai = H.formatDate(item.value.qFilterTgl.end, 'YYYY-MM-DD')
  }

  let pasien = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
  let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
  let noreg = item.value.qnoreg ? `&noreg=${item.value.qnoreg}` : ''
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan.value}` : ''
  let kelompokpasien = item.value.kelompokpasien ? `&kelompokpasienfk=${item.value.kelompokpasien.value}` : ''
  let rows = item.value.qRows ? `&rows=${item.value.qRows}` : ''
  isLoading.value = true
  await useApi().get(`/dashboard/daftar-pasien-pulang?dari=${dari}&sampai=${sampai}${pasien}${nocm}${noreg}${ruangan}${kelompokpasien}${rows}`).then((response: any) => {
      isLoading.value = false
      dataSourcePulang.value = response
    })
}
function verifikasi(e: any) {
  router.push({
    name: 'module-kasir-verifikasi-tagihan',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
    },
  })
}
function billing(e: any) {
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

function batalPulang(e: any) {

  useApi().post('/rawatinap/batal-pulang-pasien',{'norec_pd' : e.norec_pd}).then((response)=>{
    fetchPulang()
  })
}

async function detailVerifikasi(e: any) {
  isLoadingDetail.value = true
  dataSourceDetail.value = []
  item.value.totalAll = 0
  await useApi()
    .get('/dashboard/daftar-pasien-pulang/detail-verif?noregistrasi=' + e.noregistrasi)
    .then((response: any) => {
      isLoadingDetail.value = false
      for (let x = 0; x < response.length; x++) {
        const element = response[x];
        item.value.totalAll = item.value.totalAll + parseFloat(element.totalharusdibayar)
      }
      dataSourceDetail.value = response
    })
  modalDetail.value = true
}
async function batalVerif(e: any) {
  let json = {
    'norec_pd': e.norec_pd,
    'norec_sp': e.norec
  }
  isLoadingDetail.value = true
  await useApi()
    .post('/dashboard/daftar-pasien-pulang/batal-verif', json)
    .then((response: any) => {
      isLoadingDetail.value = false
      detailVerifikasi(e)
      fetchPulang()
    }, (err => {
      isLoadingDetail.value = false
    }))

}

function klikTab(e: any) {
  activeTab.value = e.index
}

const fetchRuangan = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

watch(
  () => item.value.qFilterTgl,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchPulang()
    }
  }
)

fetchTotal()
fetchPulang()
fetchJumlah()
</script>

<style lang="scss">
@import '/@src/scss/module/kasir/dashboard-kasir';

.form-layout .form-outer .form-body {
  padding: 0;
}
</style>
