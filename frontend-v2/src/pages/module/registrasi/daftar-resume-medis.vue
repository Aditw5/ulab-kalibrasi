<template>
  <section>
    <div>
      <div class="columns">
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="illustration-header-2">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                    style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                </div>
                <div class="header-meta">
                  <p>
                    Selamat Datang, {{ userLogin.pegawai.namaLengkap }}
                  </p>
                  <VControl>
                    <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label" filter
                      placeholder="Pilih Ruangan" :maxSelectedLabels="3" style="display:flex"
                      @change="changeRuang(sourceRuangan)" />
                  </VControl>
                </div>
              </div>
            </div>
            <div class="column is-12">
              <div class="writing-stats">
                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-user-check mr-2" aria-hidden="true"></i>
                      <span>Pasien Dirawat</span>
                      <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 0">
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">
                          <div class="column is-2" style="margin-top: 10px;">
                            <label>
                                <input type="checkbox" v-model="item.isResumeRawatNull" @change="handleFetchPasienRawat" />
                                Belum Resume
                            </label>
                          </div>
                          <div class="column is-4" style="margin-top: 10px;">
                            <VDatePicker v-model="item.perioderanap" is-range color="pink" trim-weeks>
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
                          </div>
                          <div class="search-location" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <!-- <input type="text" placeholder="Pencarian ..." v-model="item.qsearch"
                              v-on:keyup.enter="fetchPasienPulang()" /> -->
                              <input type="text" placeholder="Pencarian ..." v-model="item.qsearch" v-on:keyup.enter="handleFetchPasienRawat()" />
                          </div>
                          <!-- <VButton color="primary" raised class="search-button" @click="fetchPasienPulang()"
                            :loading="isLoadingTT"> Cari Data </VButton> -->
                            <VButton color="primary" raised class="search-button-ranap" @click="handleFetchPasienRawat" :loading="isLoadingTT"> Cari Data </VButton>
                        </div>
                        <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien." subtitle="Refresh Halaman Atau Periksa Jaringan Anda."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:450px;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataPasien" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared
                                  bordered />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noregistrasi }}</span>
                                    <i v-if="item.nosep" aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i v-if="item.nosep" aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.nosep }}</span>
                                    <i v-if="item.noidentitas" aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i v-if="item.noidentitas" aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noidentitas }}</span>
                                  </span>
                                  <div>
                                    <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                    <VTag :label="item.selisihwaktu" :color="'warning'" class="mr-2 mt-3-min"
                                      v-tooltip.bubble="'LAMA RAWAT'" />
                                  </div>
                                </div>
                                <div class="meta-right">
                                  <div class="buttons">
                                    <RouterLink :to="{
                                      // H.cacheHelper().set('xxx_cache_menu', undefined)
                                      name: 'module-emr-profile-pasien',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        norec_apd: item.norec_apd,
                                      }
                                    }">
                                      <VIconButton color="primary" class="mr-2" circle icon="fas fa-stethoscope" outlined
                                        raised v-tooltip.bottom.left="'EMR'">
                                      </VIconButton>
                                    </RouterLink>
                                  </div>
                                  <VIconButton icon="fas fa-book-open" light raised bold @click="toggle($event, item)"
                                    color="danger" v-tooltip.bubble="'RESUME MEDIS'" style="margin-left: 10px" />
                                  <VButton  v-if="item.isresumemedis == true" type="button" icon="fas fa-check" circle outlined
                                    color="info" light raised bold style="margin-left: 10px" disabled>
                                    Terdapat Resume Medis 
                                  </VButton>
                                  <!-- <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary" circle
                                    outlined raised v-tooltip.bottom="'Aksi'" @click="toggle($event, item)">
                                  </VIconButton> -->
                                </div>
                              </div>
                            </div>
                          </TransitionGroup>
                        </div>

                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                          :total-items="totalDataPulang" :max-links-displayed="5">
                          <template #before-pagination>
                          </template>
                          <template #before-navigation>
                            <VFlex class="mr-4 mt-1" column-gap="1rem">
                              <VField>

                              </VField>
                              <VField>
                                <VControl>
                                  <div class="select is-rounded">
                                    <select v-model="currentPage.limit">
                                      <option :value="1">1 results per page</option>
                                      <option :value="5">5 results per page</option>
                                      <option :value="10">10 results per page</option>
                                      <option :value="15">15 results per page</option>
                                      <option :value="25">25 results per page</option>
                                      <option :value="50">50 results per page</option>
                                      <option :value="100">100 results per page</option>
                                    </select>
                                  </div>
                                </VControl>
                              </VField>
                            </VFlex>
                          </template>
                        </VFlexPagination>
                      </div>
                    </div>
                  </TabPanel>
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-user-check mr-2" aria-hidden="true"></i>
                      <span>Pasien Pulang</span>
                      <Badge :value="totalDataPulang" v-if="totalDataPulang > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 1">
                      <div class="list-view list-view-v3">
                        <div class="search-menu" style="margin-bottom : 1rem;">
                          <div class="column is-2" style="margin-top: 10px;">
                            <label>
                                <input type="checkbox" v-model="item.isResumeNull" @change="handleFetchPasienPulang" />
                                Belum Resume
                            </label>
                          </div>
                          <div class="column is-4" style="margin-top: 10px;">
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
                          </div>
                          <div class="search-location" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <!-- <input type="text" placeholder="Pencarian ..." v-model="item.qsearch"
                              v-on:keyup.enter="fetchPasienPulang()" /> -->
                              <input type="text" placeholder="Pencarian ..." v-model="item.qsearch" v-on:keyup.enter="handleFetchPasienPulang()" />
                          </div>
                          <!-- <VButton color="primary" raised class="search-button" @click="fetchPasienPulang()"
                            :loading="isLoadingTT"> Cari Data </VButton> -->
                            <VButton color="primary" raised class="search-button-ranap" @click="handleFetchPasienPulang" :loading="isLoadingTT"> Cari Data </VButton>
                        </div>
                        <VPlaceholderPage :class="[dataPasienPulang.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien." subtitle="Refresh Halaman Atau Periksa Jaringan Anda."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:450px;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataPasienPulang" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared
                                  bordered />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }}
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noregistrasi }}</span>
                                    <i v-if="item.nosep" aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i v-if="item.nosep" aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.nosep }}</span>
                                    <i v-if="item.noidentitas" aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i v-if="item.noidentitas" aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noidentitas }}</span>
                                    <br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:"></i>
                                    <span>
                                      {{ H.formatDateIndo(item.tglpulang) }}
                                    </span>

                                  </span>
                                  <div>
                                    <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                    <VTag :label="item.lamarawat" :color="'warning'" class="mr-2 mt-3-min"
                                      v-tooltip.bubble="'LAMA RAWAT'" />
                                  </div>

                                </div>

                                <div class="meta-right">
                                  <div class="buttons">
                                    <RouterLink :to="{
                                      // H.cacheHelper().set('xxx_cache_menu', undefined)
                                      name: 'module-emr-profile-pasien',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        norec_apd: item.norec_apd,
                                      }
                                    }">
                                      <VIconButton color="primary" class="mr-3" circle icon="fas fa-stethoscope" outlined
                                        raised v-tooltip.bottom.left="'EMR'">
                                      </VIconButton>
                                    </RouterLink>
                                  </div>
                                  <VIconButton icon="fas fa-book-open" light raised bold @click="toggle($event, item)"
                                    color="danger" v-tooltip.bubble="'RESUME MEDIS'" style="margin-left: 10px" />
                                  <VButton  v-if="item.isresumemedis == true" type="button" icon="fas fa-check" circle outlined
                                    color="info" light raised bold style="margin-left: 10px" disabled>
                                    Terdapat Resume Medis 
                                  </VButton>
                                </div>
                              </div>
                            </div>
                          </TransitionGroup>
                        </div>

                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                          :total-items="totalDataPulang" :max-links-displayed="5">
                          <template #before-pagination>
                          </template>
                          <template #before-navigation>
                            <VFlex class="mr-4 mt-1" column-gap="1rem">
                              <VField>
                              </VField>
                              <VField>
                                <VControl>
                                  <div class="select is-rounded">
                                    <select v-model="currentPage.limit">
                                      <option :value="1">1 results per page</option>
                                      <option :value="5">5 results per page</option>
                                      <option :value="10">10 results per page</option>
                                      <option :value="15">15 results per page</option>
                                      <option :value="25">25 results per page</option>
                                      <option :value="50">50 results per page</option>
                                      <option :value="100">100 results per page</option>
                                    </select>
                                  </div>
                                </VControl>
                              </VField>
                            </VFlex>
                          </template>
                        </VFlexPagination>
                      </div>
                    </div>
                  </TabPanel>
                </TabView>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
      @close="modalFilter = false">
      <template #content>
        <form class="modal-form">
          <div class="columns">
            <div class="column is-12" style="text-align: center">
              <VField class="is-centered">
                <v-date-picker v-model="item.filterTgl" class="is-centered" trim-weeks :max-date="new Date()" />
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:search" @click="reload()" :loading="isLoading" color="primary" raised>
          Filter</VButton>
      </template>
    </VModal>
    <Dialog v-model:visible="modalResumeMedis" modal header="Resume Medis" :style="{ width: '80vw' }">
      <Resume :pasien="pasien" :registrasi="selectedRegistrasi" />
    </Dialog>
    <OverlayPanel ref="op">
    </OverlayPanel>
  </section>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import SpeedDial from 'primevue/speeddial'
import { useViewWrapper } from '/@src/stores/viewWrapper'
let isLoadCount: any = ref(false)
import { formatRp } from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import MultiSelect from 'primevue/multiselect';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Dialog from 'primevue/dialog';
import Resume from "../emr/profile-pasien/page-emr/resume-medis.vue";
import Badge from 'primevue/badge';
import OverlayPanel from 'primevue/overlaypanel';
import * as qzService from '/@src/utils/qzTrayService'
import Calendar from 'primevue/calendar';
useHead({
  title: 'Daftar Resume Medis - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const themeColors = useThemeColors()
const op = ref();
const activeTab = ref(0);
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const item: any = ref({
  isPasien: kelompokUser === 'dokter',
  aktif: true,
  filterTgl: new Date(),
  totalPulang: 0,
  totalRawat: 0,
  totalBedKosong: 0,
  totalBedIsi: 0,
  jumlahDokter: 0,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  perioderanap: reactive({
    start: new Date(),
    end: new Date(),
  }),
  periodeMutasi: reactive({
    start: new Date(),
    end: new Date(),
  })
})
const modalFilter: any = ref(false)
const currentPage: any = ref({
  limit: 50,
  rows: 1000,
})
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const modalSuratRawatInap = ref(false)
const modalSuratKematian = ref(false)
const modalResumeMedis = ref(false)
let ID_RUANGAN = useRoute().query.id as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
const totalData: any = ref(0)
const totalDataPulang: any = ref(0)
let dataSource: any = ref([])
let dataPasien: any = ref([])
let dataPasienPulang: any = ref([])
let d_Ruangan: any = ref([])
const pasien: any = ref({})
const selectedRegistrasi: any = ref({})
let dataStok: any = ref([])
let sourceRuangan: any = ref([])
let dataDokter: any = ref([])
let dataKamar: any = ref([])
let d_Dokter2: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let isLoad: any = ref(true)
const d_Dokter: any = ref([])
const itemSource: any = ref([])
const filters = ref('')
const filter = ref('')
const input = ref('')
const sourceItem = ref([])
const sourceMutasi = ref([])
const selectedItem = ref()
const sourceItemSK = ref([])
const cetak = ref([]);
const dataPenangguangJawab = ref([]);
const btnLoadSimpan: any = ref(false)

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataDokter.value
  }
  return dataDokter.value.filter((item: any) => {
    return item.namapegawai.match(new RegExp(filters.value, 'i'))
  })
})


const detailProduk = (e: any) => {
  item.value.id = e.id
  item.value.objectprodukfk = e.namaproduk
  item.value.objectasalprodukfk = e.asalproduk
  item.value.harganetto1 = e.harganetto1
  item.value.harganetto2 = e.harganetto2
  item.value.qtyproduk = e.qtyproduk
  modalDetailProduk.value = true
}

const route = useRoute()
isLoading.value = false

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(
  () => currentPage.value.page,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchData()
      fetchPasienPulang()
      fetchPasienPulangResumeNull()
      fetchPasienRawatResumeNull()
    }
  }
)
watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchData()
      fetchPasienPulang()
      fetchPasienPulangResumeNull()
      fetchPasienRawatResumeNull()
    }
  }
)

const fetchData = async (e: any) => {
  isLoad.value = true
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  let dari = H.formatDate(item.value.perioderanap.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.value.perioderanap.end, 'YYYY-MM-DD')
  let begin = `&dari=${dari}`
  let last = `&sampai=${sampai}`
  let chacePeriode = { 'awal': dari, 'akhir': sampai }
  H.cachePeriode().set('perioderanap', chacePeriode);
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
  // let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''


  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1
  isLoading.value = true
  dataPasien.value = []
  await useApi().get(`/dashboard/rawat-inap-resume?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${search}`).then((response) => {
    dataPasien.value = response.data
    totalData.value = response.total //i don't know why the paginate have 18 multiple
  })
  if (kelompokUser == 'dokter') {
    await countPasien(idPegawai)
  }

  isLoad.value = false
  isLoading.value = false
}

const fetchPasienRawatResumeNull = async (e: any) => {
  isLoad.value = true
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  let dari = H.formatDate(item.value.perioderanap.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.value.perioderanap.end, 'YYYY-MM-DD')
  let begin = `&dari=${dari}`
  let last = `&sampai=${sampai}`
  let chacePeriode = { 'awal': dari, 'akhir': sampai }
  H.cachePeriode().set('perioderanap', chacePeriode);
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
  // let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''


  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1
  isLoading.value = true
  dataPasien.value = []
  await useApi().get(`/dashboard/pasien-rawat-inap-resume-null?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${search}`).then((response) => {
    dataPasien.value = response.data
    totalData.value = response.total //i don't know why the paginate have 18 multiple
  })
  if (kelompokUser == 'dokter') {
    await countPasien(idPegawai)
  }

  isLoad.value = false
  isLoading.value = false
}

const fetchDetail = async () => {
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `ruanganfk=${itemsRuang}`
  }

  let tgl = item.value.filterTgl ? `&tgl=${H.formatDate(item.value.filterTgl, 'YYYY-MM-DD')}` : ''

  dataDokter.value = []
  dataKamar.value = []
  isLoading.value = true
  const response = await useApi().get(`/dashboard/detail-rawat-inap?${ruanganid}${tgl}&limit=10`)
  isLoading.value = false
  dataDokter.value = response.jadwal
  dataKamar.value = response.data
  dataStok.value = response.produk
  item.value.totalPulang = response.totalPulang
  item.value.totalRawat = response.totalRawat
  item.value.totalBedIsi = response.totalBedIsi
  item.value.totalBedKosong = response.totalBedKosong
  item.value.jumlahDokter = response.jumlahDokter
}

const detailDokter = (e: any) => {
  item.value.id = e.id
  item.value.objectpegawaifk = e.namalengkap
  item.value.objectruanganfk = e.namaruangan
  item.value.hari = e.hari
  item.value.jammulai = e.jammulai
  item.value.jamakhir = e.jamakhir
  modalDetailDokter.value = true
}


let periode = H.cachePeriode().get('periode')
if (periode != undefined) {
  item.value.periode.start = new Date(periode['awal']);
  item.value.periode.end = new Date(periode['akhir']);
}

let perioderanap = H.cachePeriode().get('periode')
if (perioderanap != undefined) {
  item.value.perioderanap.start = new Date(perioderanap['awal']);
  item.value.perioderanap.end = new Date(perioderanap['akhir']);
}

const handleFetchPasienPulang = () => {
  if (item.value.isResumeNull) {
    fetchPasienPulangResumeNull();
  } else {
    fetchPasienPulang();
  }
};

const handleFetchPasienRawat = () => {
  if (item.value.isResumeRawatNull) {
    fetchPasienRawatResumeNull();
  } else {
    fetchData();
  }
};

const fetchDropdown = async () => {
  const response = await useApi().get('/dashboard/dropdown-rawat-inap')
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element) => {
    sourceRuangan.value.push(element)
  })
}

const fetchPasienPulang = async (e: any) => {

  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  let dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let begin = `&dari=${dari}`
  let last = `&sampai=${sampai}`
  let chacePeriode = { 'awal': dari, 'akhir': sampai }
  H.cachePeriode().set('periode', chacePeriode);
  // debugger
  let qsearch = item.value.qsearch ? `&qsearch=${item.value.qsearch}` : ''
  let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
  let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1

  isLoadingTT.value = true
  dataPasienPulang.value = []
  // pasien.value = {}
  // selectedRegistrasi.value = {}
  await useApi().get(`/dashboard/rawat-inap-pasien-total?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${qsearch}`).then((response) => {
    dataPasienPulang.value = response.data
    totalDataPulang.value = response.total
    // pasien.value = response.data
    // selectedRegistrasi.value = response.data
  })
  isLoad.value = false
  isLoadingTT.value = false
  // console.log(pasien.value)
}

const fetchPasienPulangResumeNull = async (e: any) => {

let ruanganid = ''
if (sourceRuangan.value != undefined) {
  let itemsRuang = []
  sourceRuangan.value.forEach((element: any) => {
    itemsRuang = [...new Set([...itemsRuang, element.value])]
  });
  ruanganid = `&ruanganfk=${itemsRuang}`
}
let dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
let sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
let begin = `&dari=${dari}`
let last = `&sampai=${sampai}`
let chacePeriode = { 'awal': dari, 'akhir': sampai }
H.cachePeriode().set('periode', chacePeriode);
// debugger
let qsearch = item.value.qsearch ? `&qsearch=${item.value.qsearch}` : ''
let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''

let limit: any = currentPage.value.limit
let offset: any = route.query.page ? route.query.page : 1
offset = (parseInt(offset) - 1) * limit
let page: any = route.query.page ? route.query.page : 1

isLoadingTT.value = true
dataPasienPulang.value = []
// pasien.value = {}
// selectedRegistrasi.value = {}
await useApi().get(`/dashboard/rawat-inap-pasien-total-resume-null?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${qsearch}`).then((response) => {
  dataPasienPulang.value = response.data
  totalDataPulang.value = response.total
  // pasien.value = response.data
  // selectedRegistrasi.value = response.data
})
isLoad.value = false
isLoadingTT.value = false
// console.log(pasien.value)
}

if (userLogin.mapLoginUserToRuangan.length) {
  for (let i = 0; i < userLogin.mapLoginUserToRuangan.length; i++) {
    const element = userLogin.mapLoginUserToRuangan[i];
    if (element.departemen.toLowerCase().indexOf('rawat inap') > -1) {
      // item.value.namaruangan = element.namaruangan
      item.value.departemen = element.departemen
      item.value.id_ruangan = element.id
      item.value.id_departemen = element.objectdepartemenfk
      break
    }
  }
}

const changeRuang = async (e: any) => {
  console.log('ee')
  reload()
  // fetchJadwalDokter(items)
}

const showModalFilter = () => {
  modalFilter.value = true
}
const reload = () => {
  fetchDetail()
  fetchPasienPulang()
  fetchData()
}
const emr = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    }
  })
}

const modalRawatInap = (e: any) => {
  modalSuratRawatInap.value = true;
  cetak.value = e;
}


const resumeMedis = (data) => {
  modalResumeMedis.value = true;
  itemSource.value = data;
  pasien.value = data;
  selectedRegistrasi.value = data;
  console.log(pasien.value)
  console.log(selectedRegistrasi.value)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const toggle = (event, e) => {
  selectedItem.value = e;
  op.value.toggle(event);
  resumeMedis(selectedItem.value);
};

const resumeMedisFromButton = () => {
  if (selectedItem.value) {
    toggle(event, selectedItem.value);
    resumeMedis(selectedItem.value);
  }
}

const countPasien = async (pegawai: any) => {
  await useApi().get(`dashboard/get-jumlah-pendapatan?${pegawai}`).then((response) => {
    item.value.totalPasien = response.jumlahPasien
    item.value.totalTindakan = response.jumlahTindakan
    item.value.pendapatanJasa = response.pendapatanJasa
    isLoadCount.value = false
  })
}

const detailRegistrasi = (e: any) => {

  router.push({
    name: 'module-registrasi-detail-registrasi',
    query: {
      noregistrasi: e.noregistrasi,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    },
  })
}

const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchPasienPulang()
  }
}


watch(
  () => item.value.isPasien, () => {
    fetchData()
    fetchPasienPulang()
  }
)

onMounted(async () => {
  await fetchDropdown()
  await fetchData(),
    await fetchDetail(),
    await fetchPasienPulang()
})


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative !important;
  background: var(--fade-grey-light-2) !important;
  border: 1px solid var(--fade-grey) !important;
  max-width: 70% !important;
  height: 35px !important;
  border-bottom: none !important;
}

.lifestyle-dashboard-v4 {
  .illustration-header-2 {
    display: flex;
    align-items: center;
    padding: 10px;
    border-radius: 16px;
    background: var(--primary-dark-24);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .header-image {
      position: relative;
      height: 175px;
      width: 320px;

      img {
        position: absolute;
        top: 0;
        left: -40px;
        display: block;
        pointer-events: none;
      }
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .writing-stats {
    display: flex;
    margin-bottom: 1rem;
    margin-left: -8px;
    margin-right: -8px;

    .writing-stat {
      @include vuero-l-card;

      margin: 8px;
      width: calc(33.3% - 16px);
      padding: 12px;

      span {
        display: block;

        &:first-child {
          font-family: var(--font-alt);
          font-size: 0.8rem;
          font-weight: 500;
          text-transform: uppercase;
          margin-bottom: 5px;
          color: var(--light-text);
        }

        &:nth-child(2) {
          font-family: var(--font);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--dark-text);
        }
      }
    }
  }

  .featured-authors {
    @include vuero-l-card;

    padding: 20px;

    .featured-authors-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 30px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
      }

      .action-link {
        font-size: 0.9rem;
      }
    }

    .featured-authors-list {
      .featured-authors-item {
        &:not(:last-child) {
          margin-bottom: 20px;
        }

        .media-flex-center {
          .flex-end {
            span {
              font-family: var(--font-alt);
              font-weight: 600;
              color: var(--dark-text);
            }
          }
        }
      }
    }
  }

  .updates {
    @include vuero-l-card;

    padding: 20px;
    margin-top: 8px;

    .updates-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
      }

      .action-link {
        font-size: 0.9rem;
      }
    }

    .updates-list {
      .update-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--fade-grey-dark-3);

        &:last-child {
          margin-bottom: 0;
          border-bottom: none;
        }

        p {
          font-size: 0.9rem;
        }

        span {
          display: block;
          min-width: 60px;
          text-align: right;
          font-family: var(--font);
          font-weight: 600;
          font-size: 0.8rem;
          color: var(--dark-text);
        }
      }
    }
  }

  .page-placeholder .placeholder-content h3 {
    font-size: 0.9rem;
    font-weight: 600;
    font-family: var(--font-alt);
    color: var(--dark-text);
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-s-card;

      border-radius: 14px;
      padding: 16px;
      cursor: pointer;

      &:hover,
      &:focus {
        border-color: var(--primary);
        box-shadow: var(--light-box-shadow);
      }

      .tile-grid-item-inner {
        display: flex;
        align-items: center;

        >img {
          display: block;
          width: 50px;
          height: 50px;
          min-width: 50px;
        }

        .meta {
          margin-left: 10px;
          line-height: 1.4;

          span {
            display: block;
            font-family: var(--font);

            &:first-child {
              color: var(--dark-text);
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.9rem;
            }

            &:nth-child(2) {
              display: flex;
              align-items: center;

              span {
                display: inline-block;
                color: var(--light-text);
                font-size: 0.8rem;
                font-weight: 400;
              }

              .icon-separator {
                position: relative;
                font-size: 4px;
                color: var(--light-text);
                padding: 0 6px;
              }
            }
          }
        }

        .dropdown {
          margin-left: auto;
        }
      }
    }
  }

  .is-dark {
    .tile-grid {
      .tile-grid-item {
        @include vuero-card--dark;
      }
    }

    .tile-grid-v2 {
      .tile-grid-item {
        @include vuero-card--dark;

        &:hover,
        &:focus {
          border-color: var(--primary) !important;
        }
      }
    }
  }

  .articles-feed {
    background: var(--widget-grey);
    padding: 30px;
    border-radius: 12px;

    .articles-feed-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-weight: 600;
        font-size: 1.1rem;
        color: var(--dark-text);
      }

      .action-link {
        font-size: 0.9rem;
      }
    }

    .articles-feed-subheader {
      margin-bottom: 20px;

      .selector {
        .button {
          font-size: 0.8rem;
          border-radius: 50px;
          margin-right: 4px;

          &.is-selected {
            background: var(--primary);
            color: var(--white);
            border-color: var(--primary);
            box-shadow: var(--primary-box-shadow);
          }
        }
      }
    }

    .articles-feed-list {
      .articles-feed-list-inner {
        .articles-feed-item {
          display: block;

          &:not(:last-child) {
            margin-bottom: 20px;
          }

          .featured-image {
            height: 180px;
            overflow: hidden;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;

            img {
              display: block;
              width: 100%;
              height: 100%;
              object-fit: cover;
            }
          }

          .featured-content {
            position: relative;
            padding: 25px;
            border-radius: 18px;
            background: var(--white);
            margin-top: -40px;
            z-index: 1;

            h4,
            p {
              margin-bottom: 10px;
            }

            h4 {
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
            }

            .media-flex-center {
              .flex-meta {
                span {
                  font-size: 0.8rem;
                }
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .lifestyle-dashboard-v4 {
    .illustration-header-2 {
      background: var(--dark-sidebar);
      box-shadow: none;
    }

    .writing-stats {
      .writing-stat {
        @include vuero-card--dark;
      }
    }

    .updates,
    .featured-authors {
      @include vuero-card--dark;
    }

    .articles-feed {
      background: var(--dark-sidebar-light-8);

      .articles-feed-subheader {
        .selector {
          .button {
            &.is-selected {
              background: var(--primary) !important;
              border-color: var(--primary) !important;
              box-shadow: var(--primary-box-shadow) !important;
              color: var(--white) !important;
            }
          }
        }
      }

      .articles-feed-list {
        .articles-feed-list-inner {
          .articles-feed-item {
            .featured-content {
              background: var(--dark-sidebar);
            }
          }
        }
      }
    }
  }
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 50px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 30px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        >.v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .feed-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    .button {
      font-size: 0.8rem;
      border-radius: 8px;
      margin-right: 4px;

      &.is-selected {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 2rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative;
  background: var(--fade-grey-light-2);
  border: 1px solid var(--fade-grey);
  max-width: 70%;
  height: 35px;
  border-bottom: none;

}

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.tile-grid-v2 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;
    cursor: pointer;

    &:hover,
    &:focus {
      border-color: var(--primary);
      box-shadow: var(--light-box-shadow);
    }

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      >img {
        display: block;
        width: 50px;
        height: 50px;
        min-width: 50px;
      }

      .meta {
        margin-left: 10px;
        line-height: 1.4;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            display: flex;
            align-items: center;

            span {
              display: inline-block;
              color: var(--light-text);
              font-size: 0.8rem;
              font-weight: 400;
            }

            .icon-separator {
              position: relative;
              font-size: 4px;
              color: var(--light-text);
              padding: 0 6px;
            }
          }
        }
      }

      .dropdown {
        margin-left: auto;
      }
    }
  }
}

.title.is-5 {
  font-size: 1.05rem;
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }

  .tile-grid-v2 {
    .tile-grid-item {
      @include vuero-card--dark;

      &:hover,
      &:focus {
        border-color: var(--primary) !important;
      }
    }
  }
}

.soccer-dashboard {
  .soccer-dashboard-inner {
    .live-match {
      @include vuero-l-card;

      padding: 1.5rem;
      margin-bottom: 1.5rem;

      .head {
        margin-bottom: 1.5rem;

        .league {
          display: flex;
          align-items: center;
          justify-content: space-between;

          .left {
            span {
              display: block;
              font-family: var(--font);

              &:first-child {
                color: var(--dark-text);
              }

              &:nth-child(2) {
                color: var(--light-text);
                font-size: 0.9rem;
              }
            }
          }

          .right {
            .live-block {
              display: inline-flex;
              align-items: center;
              padding: 0.25rem 0.75rem;
              border-radius: 50rem;
              background: var(--danger);
              color: var(--white);
              box-shadow: var(--danger-box-shadow);

              span {
                display: inline-block;
                font-family: var(--font);
                font-size: 0.85rem;
                margin-left: 0.25rem;
              }
            }
          }
        }
      }

      .match {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;

        .left,
        .right {
          text-align: center;

          .team-logo {
            display: block;
            min-width: 50px;
            max-width: 50px;
            text-align: center;
            margin-bottom: 0.25rem;
          }

          .team-name {
            display: block;
            font-family: var(--font);
            font-weight: 500;
          }
        }

        .center {
          display: flex;
          justify-content: center;
          align-items: center;

          .score {
            display: block;
            font-family: var(--font);
            font-weight: 700;
            font-size: 2.20rem;
          }

          .separator {
            position: relative;
            top: -2px;
            display: block;
            padding: 0 0.5rem;
            font-family: var(--font);
            font-weight: 700;
            font-size: 1.75rem;
          }
        }
      }

      .action {
        .v-button {
          border-radius: 0.65rem;
          height: 44px;
        }
      }
    }

    .leagues {
      @include vuero-l-card;

      padding: 2rem;

      .head {
        margin-bottom: 1.5rem;
      }

      .leagues-list {
        .league-item {
          display: flex;
          align-items: center;

          &:not(:last-child) {
            margin-bottom: 1rem;
          }

          .league-logo {
            display: block;
            min-width: 38px;
            max-width: 38px;
          }

          .meta {
            margin-left: 0.5rem;
            line-height: 1.2;

            .league-name {
              display: block;
              font-family: var(--font-alt);
              font-size: 1rem;
              font-weight: 600;
              color: var(--dark-text);
            }

            .league-country {
              display: block;
              font-family: var(--font);
              font-size: 0.9rem;
              color: var(--light-text);
            }
          }

          .end {
            margin-left: auto;
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
          }
        }
      }
    }

    .dashboard-cta {
      background-color: var(--primary);
      padding: 2rem;
      border-radius: 1rem;
      position: relative;
      margin-bottom: 1.5rem;

      .dashboard-cta-title {
        font-family: var(--font-alt);
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--white);
        margin: 0 0 0.25rem;
      }

      .dashboard-cta-text {
        color: var(--white);
        opacity: 0.9;
        font-family: var(--font);
        line-height: 1.7;
        margin-top: 0;
        max-width: 58%;
        margin-bottom: 1rem;
      }

      .dashboard-cta-img {
        width: 40%;
        max-width: 350px;
        position: absolute;
        overflow: hidden;
        height: calc(110%);
        top: -10%;
        right: 2rem;

        img {
          width: 100%;
          height: auto;
        }
      }
    }

    .matches-card {
      @include vuero-l-card;

      padding: 0;
      overflow: hidden;

      .matches-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 3rem;
        padding: 2rem 2rem 0;

        .header-nav {
          display: flex;

          .nav-item {
            .nav-link {
              font-family: var(--font);
              margin-right: 1rem;
              border-bottom: 3px solid transparent;
              padding-bottom: 1rem;
              color: var(--light-text);

              &.is-active {
                color: var(--dark-text);
                border-bottom-color: var(--primary);
              }
            }
          }
        }
      }

      .matches-card-body {
        overflow-x: auto;

        .table {
          width: 100%;

          thead th {
            border: none;
            font-family: var(--font);
            font-size: 0.8rem;
            text-transform: uppercase;
          }

          tr {

            th:first-child,
            td:first-child {
              padding-left: 2rem;
            }

            th:last-child,
            td:last-child {
              padding-right: 2rem;
            }

            td {
              padding-top: 1.5rem;
              padding-bottom: 1.5rem;

              &.score-cell {
                min-width: 300px;
              }

              .match-time-row {
                display: flex;
                align-items: center;

                .match-time {
                  font-family: var(--font);
                  color: var(--light-text);
                  margin-right: 0.75rem;
                }

                .tag {
                  font-family: var(--font);

                  svg {
                    color: var(--warning);
                  }

                  &.is-live {
                    svg {
                      color: var(--danger);
                    }
                  }
                }
              }

              .table-action {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 36px;
                width: 36px;
                border-radius: 50%;
                color: var(--light-text);
                transition: background-color 0.3s;

                &:hover,
                &:focus {
                  background: var(--widget-grey);
                }
              }
            }
          }

          .score {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;

            .score-vertical {
              justify-content: flex-start;
            }

            .score-team {
              text-align: center;

              span {
                display: block;
                font-weight: 500;
                padding-top: 0.25rem;
              }

              img {
                width: 40px;
              }

              &.score-team-vertical {
                display: flex;
                align-items: center;
                flex: 1;

                &:first-child {
                  justify-content: flex-end;
                }

                span {
                  white-space: nowrap;
                  font-size: inherit;
                }

                img {
                  width: 32px;
                  margin: 0 0.5rem;
                }
              }
            }

            .score-result {
              text-align: center;
              width: 100%;
              font-weight: 900;
              font-size: 1.75rem;
              margin: 0;
              letter-spacing: 0.4em;

              &.score-result-not-started {
                color: gray;
              }

              &.score-result-vertical {
                letter-spacing: 0.2em;
                font-size: inherit;
                flex: 0 0 auto;
                width: auto;
              }
            }
          }
        }
      }
    }

    .matches {
      .nav-item {
        &:first-child {
          .nav-link {
            padding-left: 0;
          }
        }

        .nav-link {
          padding-top: 0;
          padding-bottom: 0;
        }
      }
    }
  }
}

.is-dark {
  .soccer-dashboard {
    .soccer-dashboard-inner {

      .live-match,
      .leagues {
        @include vuero-card--dark;

        .head {
          .title {
            color: var(--white) !important;
          }
        }

        .match {

          .left,
          .right {
            .team-name {
              color: var(--white) !important;
            }
          }
        }

        .leagues-list {
          .league-item {
            .meta {
              span:first-child {
                color: var(--white) !important;
              }
            }
          }
        }
      }

      .matches-card {
        @include vuero-card--dark;

        .matches-card-header {
          .header-nav {
            .nav-item {
              .nav-link {
                &.is-active {
                  color: var(--white) !important;
                }
              }
            }
          }
        }
      }

      .matches-card-body {
        .table {
          .score {
            .score-team {
              &.score-team-vertical {
                >span {
                  color: var(--white) !important;
                }
              }
            }
          }

          tr td {
            .table-action {
              &:hover {
                background: var(--dark-sidebar-light-2) !important;
              }
            }
          }
        }
      }
    }
  }
}

.user-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    position: relative;
    @include vuero-s-card;

    text-align: center;

    &:hover,
    &:focus {
      .button-wrap {
        >div {
          a {
            opacity: 1;
            pointer-events: all;
          }
        }
      }
    }

    .dropdown {
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: left;
    }

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.85rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.7rem;
    }

    .button-wrap {
      margin: 20px 0 0;

      .v-button {
        width: 100%;
        max-width: 120px;
        margin: 0 auto;
      }

      >div {
        margin: 6px 0 0;

        a {
          opacity: 0;
          pointer-events: none;
          color: var(--light-text);
          font-weight: 500;
          font-size: 0.8rem;
          transition: opacity 0.3s, color 0.3s;

          &:hover,
          &:focus {
            color: var(--primary);
          }
        }
      }
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .hr-dashboard {
    .block-header {
      background: var(--dark-sidebar);
      box-shadow: none;

      .center {
        border-color: var(--dark-sidebar-light-10);

        .candidates {
          button {
            background: var(--dark-sidebar-light-10);
            border: 1px solid transparent;
            transition: all 0.3s; // transition-all test

            &:hover {
              border-color: var(--primary);

              svg {
                color: var(--primary);
              }
            }
          }
        }
      }
    }

    .feed-settings {
      .button {
        &.is-selected {
          background: var(--primary) !important;
          border-color: var(--primary) !important;
          box-shadow: var(--primary-box-shadow) !important;
          color: var(--white) !important;
        }
      }
    }

    .recent-rookies {
      .user-grid {
        &.user-grid-v4 {
          .grid-item {
            @include vuero-card--dark;
          }
        }
      }
    }
  }
}

.list-view-v3 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      >img {
        width: 100%;
        max-width: 60px;
        min-width: 60px;
        max-height: 60px;
        min-height: 60px;
        border-radius: var(--radius-rounded);
        border: 1px solid var(--fade-grey);
      }

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 500;
          font-size: 0.85rem;
          line-height: 1;
        }

        >span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            position: relative;
            top: 1px;
            height: 12px;
            width: 12px;
          }

          .icon-separator {
            position: relative;
            top: -3px;
            font-size: 5px;
            color: var(--light-text);
            padding: 0 8px;
          }

          .iconify {
            margin-right: 0.25rem;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .buttons {
          margin-bottom: 0;
          margin-right: 10px;
        }
      }
    }
  }
}

.illustration-header-2 {
  display: flex;
  align-items: center;
  padding: 10px;
  border-radius: 16px;
  background: var(--primary-dark-24);
  font-family: var(--font);
  box-shadow: var(--primary-box-shadow);

  .header-image {
    position: relative;
    height: 175px;
    width: 320px;

    img {
      position: absolute;
      top: 0;
      left: -40px;
      display: block;
      pointer-events: none;
    }
  }

  .header-meta {
    margin-left: 0;
    padding-right: 30px;

    h3 {
      color: var(--smoke-white);
      font-family: var(--font-alt);
      font-weight: 700;
      font-size: 1.3rem;
      max-width: 280px;
    }

    p {
      font-weight: 400;
      color: var(--smoke-white-dark-2);
      margin-bottom: 16px;
      max-width: 320px;
    }

    .action-link {
      span {
        font-size: 0.8rem;
        text-transform: uppercase;
        margin-right: 6px;
      }

      i {
        font-size: 12px;
      }
    }
  }
}

.is-dark {
  .list-view-v3 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        >img {
          border-color: var(--dark-sidebar-light-12);
        }

        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .buttons {
            .button {
              &:nth-child(2) {
                background: var(--dark-sidebar-light-2);
                border-color: var(--dark-sidebar-light-8);
                color: var(--dark-dark-text);
                transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                  height 0.3s, width 0.3s;

                &:hover,
                &:focus {
                  border-color: var(--primary);
                  color: var(--primary);
                }
              }
            }
          }
        }
      }
    }
  }
}

.search-menu {
  height: 56px;
  white-space: nowrap;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  background-color: whitesmoke;
  border-radius: 8px;
  width: 100%;
  padding-left: 0.75rem;

  >div:not(:last-of-type) {
    border-right: 1px solid var(--search-border-color);
  }

  .search-bar {
    height: 55px;
    width: 100%;
    position: relative;
    display: flex;
    align-items: center;
    padding-right: 1.5rem;

    .field {
      width: 100%;
    }

    .multiselect-tags {
      padding-left: 2.5rem;
    }
  }

  .search-location,
  .search-job,
  .search-salary {
    display: flex;
    align-items: center;
    width: 50%;
    font-size: 14px;
    font-weight: 500;
    padding: 0 25px;
    height: 100%;
    font-family: var(--font);

    input {
      width: 100%;
      height: 90%;
      display: block;
      font-family: var(--font);
      color: var(--input-color);
      background-color: whitesmoke;
      border: none;
    }

    svg {
      margin-right: 0.5rem;
      width: 18px;
      color: var(--primary);
      flex-shrink: 0;
    }
  }

  .search-button {
    background-color: var(--primary);
    min-width: 100px;
    height: 56px;
    border: none;
    font-weight: 500;
    font-family: var(--font);
    padding: 0 1rem;
    border-radius: 0 0.75rem 0.75rem 0;
    color: var(--button-color);
    cursor: pointer;
    margin-left: auto;
  }
}

.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link {
  background: #ffffff !important;
  border-color: #4CAF50 !important;
  color: #4CAF50 !important;
}

@media only screen and (max-width: 767px) {
  .lifestyle-dashboard-v4 {
    .illustration-header-2 {
      flex-direction: column;
      text-align: center;

      .header-image {
        height: auto;
        width: 100%;

        img {
          position: relative;
          width: 100%;
          max-width: 260px;
          margin: 0 auto;
          top: 0;
          left: 0;
          margin-top: -34px;
        }
      }

      .header-meta {
        padding: 20px;

        >p {
          max-width: 280px;
          margin-left: auto;
          margin-right: auto;
        }
      }
    }

    .writing-stats {
      .writing-stat {
        text-align: center;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .lifestyle-dashboard-v4 {
    .articles-feed {
      .articles-feed-list {
        .articles-feed-list-inner {
          display: flex;
          flex-wrap: wrap;
          margin-left: -12px;
          margin-right: -12px;

          .articles-feed-item {
            width: calc(50% - 24px);
            margin: 12px;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .lifestyle-dashboard-v4 {
    .updates {
      .updates-list {
        .update-item {
          >span {
            display: none;
          }
        }
      }
    }

    .articles-feed {
      padding: 20px;
    }
  }
}
</style>
