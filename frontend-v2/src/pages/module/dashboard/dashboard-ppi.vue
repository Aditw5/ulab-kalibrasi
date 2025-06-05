<template>
  <section>
    <div class="lifestyle-dashboard lifestyle-dashboard-v4">
      <div class="columns">
        <div class="column is-12">
          <div class="columns is-multiline">
            <!--Header-->
            <div class="column is-12" style="margin-left : 2rem;">
              <div class="illustration-header-2">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                    style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                </div>
                <div class="header-meta">
                  <!-- <h4 style="color:white"> <i class="fas fa-bed"></i> {{ item.departemen }} </h4> -->
                  <h3> Dashboard PPI </h3>
                  <p>
                    Selamat Datang, {{ userLogin.pegawai.namaLengkap }}
                  </p>
                </div>
              </div>
            </div>

            <!--Content-->
            <div class="column is-12" style="margin-left : 2rem;">
              <div class="featured-authors">
                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar PPI Rawat Inap</span>
                      <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 0">
                      <div class="list-view list-view-v3">
                        <div class="search-menu-ranap" style="margin-bottom : 1rem;">

                          <div class="search-location-ranap" style="width: 100% !important;">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                              v-model="item.search" v-on:keyup.enter="fetchData()" />
                          </div>
                          <VButton raised class="search-button-ranap" :loading="isLoading" @click="fetchData()"> Cari Data
                          </VButton>
                        </div>
                        <VPlaceholderPage :class="[totalData !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien PPI Saat Ini."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:500px; min-height: 300PX;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataPasien" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <!-- <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" squared
                                  bordered /> -->
                                <VAvatar size="small" color="primary" squared bordered
                                  :picture="item.jeniskelamin === 'Laki-Laki' ? '/images/avatars/svg/pasien.svg' : '/images/avatars/svg/vuero-4.svg'" />
                                <div class="meta-left">
                                  <h3>
                                    {{ item.namapasien }} |
                                    <VTag v-if="item.kelompokpasien != null" class="mt-3 ml-2"
                                      :label="item.kelompokpasien"
                                      :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'" rounded /> |
                                    <i class="bulet fas fa-circle"></i>
                                    {{ item.namakelas }}
                                    <span v-if="item.objectpegawairawatbersamafk != null">|</span>
                                    <VTag v-if="item.objectpegawairawatbersamafk != null" class="mt-3 ml-2"
                                      label="Rawat Bersama"  :color="'blue'" rounded />
                                    <span v-if="item.namainfeksippi != null">|</span>
                                    <VTag v-if="item.namainfeksippi != null" class="mt-3 ml-1"
                                      :label="`Terdeteksi Infeksi: ${item.namainfeksippi}`" :color="'danger'" rounded />
                                  </h3>
                                  <span>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                    <span>{{ item.namaruangan }} - {{ item.reportdisplay }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                    <span>{{ H.formatDateIndo(item.tglregistrasi) }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noregistrasi }}</span><br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nocm }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                    <span>{{ item.nobpjs }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                    <span>{{ item.noidentitas }}</span> 
                                  </span><br>
                                  <span style="font-weight: bold;">DPJP :
                                    <i aria-hidden="true" class="iconify"></i>
                                    <span>{{ item.namalengkap }}</span>
                                  </span><br>
                                  <span style="font-weight: bold;">Dokter Pemeriksa :
                                    <i aria-hidden="true" class="iconify"></i>
                                    <span>{{ item.nama }}</span>
                                  </span><br>
                                  <VTag size="medium" v-if="item.penularan == 'Airbone'" class="ml-4" color="danger" rounded></VTag>
                                  <VTag size="medium" v-if="item.penularan == 'Kontak'" class="ml-4" color="warning" rounded></VTag>
                                  <VTag size="medium" v-if="item.penularan == 'Droplet'" class="ml-4 custom-grey-tag" rounded></VTag>
                                  <VTag size="medium" v-if="item.penularan == 'Lain-lain'" class="ml-4" color="blue" rounded></VTag>
                                  <div v-if="item.penularan === 'Airbone/Kontak'">
                                    <VTag  class="ml-4" color="danger" rounded></VTag>
                                    <VTag  class="ml-4" color="warning" rounded></VTag>
                                  </div>
                                  <div v-if="item.penularan === 'Airbone/droplet'">
                                    <VTag  class="ml-4" color="danger" rounded></VTag>
                                    <VTag  class="ml-4 custom-grey-tag" rounded></VTag>
                                  </div>
                                  <div v-if="item.penularan === 'Kontak/Droplet'">
                                    <VTag  class="ml-4" color="warning" rounded></VTag>
                                    <VTag  class="ml-4 custom-grey-tag" rounded></VTag>
                                  </div>
                                  <!-- <span>
                                  <i aria-hidden="true" class="iconify" data-icon="healthicons:doctor-male-outline"></i>
                                  <span>{{ item.namalengkap }}</span>
                                </span> -->
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
                                      <VButton color="primary" icon="fas fa-stethoscope" outlined raised
                                        @click="emr(item)">
                                        RME
                                      </VButton>
                                    </RouterLink>
                                  </div>
                                  <VDropdown icon="feather:more-vertical" spaced right
                                    v-tooltip.bottom.left="'TINDAKAN PASIEN'">
                                    <template #content>
                                      <a role="menuitem" @click="InputPpi(item)" class="dropdown-item is-media">
                                        <div class="icon">
                                          <i class="fas fa-file" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Edit PPI</span>
                                        </div>
                                      </a>
                                    </template>
                                  </VDropdown>
                                </div>
                              </div>
                            </div>
                          </TransitionGroup>
                        </div>

                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                          :total-items="totalData" :max-links-displayed="5">
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
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar PPI Rawat Jalan</span>
                      <Badge :value="dataPasienRajal.total" v-if="dataPasienRajal.total > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 1">
                      <div class="search-menu-rajal mb-2" :class="'is-10 mt-2 py-2 pl-2 bussiness-dashboard'">
                        <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks class="mt-2 mx-1 p-1">
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
                      <div class="list-view list-view-v3">
                        <div class="search-menu-ranap" style="margin-bottom : 1rem;">
                          <div class="search-location-ranap" style="width: 100% !important;">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                              v-model="item.search" v-on:keyup.enter="fetchPasienRawatJalan()" />
                          </div>
                          <VButton raised class="search-button-ranap" :loading="isLoading" @click="fetchPasienRawatJalan()"> Cari Data
                          </VButton>
                        </div>
                        <VPlaceholderPage :class="[dataPasienRajal.total !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien PPI Saat Ini."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                          <div name="list-complete" tag="div">
                            <div v-for="(item, rowIndex) in dataPasienRajal" :key="rowIndex">
                              <div class="list-view-item ">
                                <div class="list-view-item-inner">
                                  <VAvatar size="small" color="primary" squared bordered
                                    :picture="item.jeniskelamin === 'Laki-Laki' ? '/images/avatars/svg/pasien.svg' : '/images/avatars/svg/vuero-4.svg'" />
                                  <div class="meta-left" style="width: 20%">
                                    <h3>
                                      {{ item.namapasien }}
                                      <i :class="item.objectjeniskelaminfk == 2 ? 'fas fa-venus' : 'fas fa-mars'"
                                        aria-hidden="true"
                                        :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                    </h3>
                                    <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                    <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span>
                                  </div>
                                  <div class="meta-left">
                                    <span>
                                      <VTag v-if="item.namainfeksippi != null" class="mt-3 ml-1"
                                        :label="`Terdeteksi Infeksi: ${item.namainfeksippi}`" :color="'danger'" rounded /><br>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                      <span>{{ item.tglregistrasi }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                      <span>{{ item.noregistrasi }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                      <span>{{ item.umur }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                      <span>{{ item.kelompokpasien }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:layers"></i>
                                      <span>{{ item.namakelas }}</span>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.nocm }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.nobpjs }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                      <span>{{ item.noidentitas }}</span>
                                    </span>
                                    <div>
                                      <!-- <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i> -->
                                      <VTag :label="'SEP : ' + item.nosep" v-if="item.nosep != null" :color="'success'"
                                        class="mr-2" />
                                      <VTag :label="'Mobile JKN'" v-if="item.ismobilejkn == true" :color="'success'"
                                        class="mr-2" />
                                    </div>
                                    <div>
                                      <span style="font-weight: bold;">DPJP :
                                        {{ item.namalengkap ? item.namalengkap : '-' }}
                                      </span>
                                      <div v-if="item.dokter_konsul_verif && item.konsultasi" style="font-weight: bold"> Dokter Konsul : {{item.dokter_konsul_verif}}</div>
                                      <div v-if="item.dokterTambahan.length > 0" style="font-weight: bold;">
                                        Dokter Pemeriksa : <span v-for="data in item.dokterTambahan">{{ data.namalengkap }},</span>
                                      </div>
                                    </div><br>
                                      <VTag size="medium" v-if="item.penularan == 'Airbone'" class="ml-4" color="danger" rounded></VTag>
                                      <VTag size="medium" v-if="item.penularan == 'Kontak'" class="ml-4" color="warning" rounded></VTag>
                                      <VTag size="medium" v-if="item.penularan == 'Droplet'" class="ml-4 custom-grey-tag" rounded></VTag>
                                      <VTag size="medium" v-if="item.penularan == 'Lain-lain'" class="ml-4" color="blue" rounded></VTag>
                                      <div v-if="item.penularan === 'Airbone/Kontak'">
                                        <VTag  class="ml-4" color="danger" rounded></VTag>
                                        <VTag  class="ml-4" color="warning" rounded></VTag>
                                      </div>
                                      <div v-if="item.penularan === 'Airbone/droplet'">
                                        <VTag  class="ml-4" color="danger" rounded></VTag>
                                        <VTag  class="ml-4 custom-grey-tag" rounded></VTag>
                                      </div>
                                      <div v-if="item.penularan === 'Kontak/Droplet'">
                                        <VTag  class="ml-4" color="warning" rounded></VTag>
                                        <VTag  class="ml-4 custom-grey-tag" rounded></VTag>
                                      </div>
                                  </div>
                                  <div class="meta-right">
                                    <div class="buttons">
                                      <RouterLink :to="{
                                        name: 'module-emr-profile-pasien',
                                        query: {
                                          nocmfk: item.nocmfk,
                                          norec_pd: item.norec_pd,
                                          norec_apd: item.norec_apd,
                                        }
                                      }">
                                        <VButton color="primary" icon="fas fa-stethoscope" outlined raised
                                          @click="emr(item)">
                                          RME
                                        </VButton>
                                      </RouterLink>
                                    </div>
                                    <VDropdown icon="feather:more-vertical" spaced right
                                      v-tooltip.bottom.left="'TINDAKAN PASIEN'">
                                      <template #content>
                                        <a role="menuitem" @click="InputPpi(item)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="fas fa-file" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Edit PPI</span>
                                          </div>
                                        </a>
                                      </template>
                                    </VDropdown>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                          :total-items="dataPasienRajal.total" :max-links-displayed="5">
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
            <!--Content-->
          </div>
        </div>

      </div>
    </div>

    <VModal :open="modalInputPPI" title="Terdeteksi Infeksi" size="medium" actions="right"
      @close="modalInputPPI = false" cancelLabel="Tutup">
      <template #content>
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>Infeksi PPI</VLabel>
              <VControl class="prime-auto-cus ">
                  <AutoComplete v-model="item.infeksippi" :suggestions="d_InfeksiPPI"
                      :optionLabel="'label'" @complete="fetchInfeksi($event)" :dropdown="true"
                      :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="Infeksi..." class="mt-2  is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
      </template>
      <template #action>
        <VButton icon="feather:plus" color="primary" @click="saveInputPPI()" :loading="isLoading" raised>Simpan
        </VButton>
      </template>
    </VModal>
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
import Badge from 'primevue/badge';
import OverlayPanel from 'primevue/overlaypanel';
import Dialog from 'primevue/dialog';
import * as qzService from '/@src/utils/qzTrayService'
import Calendar from 'primevue/calendar';
import { useToaster } from '/@src/composable/toaster'
useHead({
  title: 'Dashboard PPI - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const themeColors = useThemeColors()
const isReservasi: any = ref([])
const op = ref();
const activeTab = ref(0);
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const modalInputSitb = ref(false)
const modalInputPPI = ref(false)
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const item: any = ref({
  isPasien: kelompokUser === 'dokter',
  aktif: true,
  // filterTgl: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  totalPulang: 0,
  totalRawat: 0,
  totalBedKosong: 0,
  totalBedIsi: 0,
  jumlahDokter: 0,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  isResumeNull: false,
  periodeMutasi: reactive({
    start: new Date(),
    end: new Date(),
  })
})
const modalFilter: any = ref(false)
const currentPage: any = ref({
  limit: 50,
})
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const modalDetailKamar = ref(false)
const modalSuratRawatInap = ref(false)
const modalSuratKematian = ref(false)
const modalSuratSakit = ref(false)
const modalChangeDokter = ref(false)
const modalChangeTglPulang = ref(false)
const modalBatalPulang = ref(false)
let ID_RUANGAN = useRoute().query.id as string
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
const totalData: any = ref(0)
const totalDataPulang: any = ref(0)
let dataSource: any = ref([])
let dataPasien: any = ref([])
let listDokterTambahan: any = ref([])
let dataPasienRajal: any = ref([])
let d_Ruangan: any = ref([])
let dataStok: any = ref([])
let sourceRuangan: any = ref([])
let dataDokter: any = ref([])
let dataKamar: any = ref([])
const d_Kelas: any = ref([])
const d_KelasAll: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
let d_Dokter2: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let isNewInput: any = ref(false)
let isLoad: any = ref(true)
const d_Dokter: any = ref([])
const itemSource: any = ref([])
let modalSKDokter: any = ref(false)
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
const noCmFkPasien: any = ref("")
const modalAccKanker = ref(false)
const modalAccKanker2 = ref(false)
const d_InfeksiPPI = ref([])

const fetchDropdown = async () => {
  const response = await useApi().get('/dashboard/dropdown-rawat-inap')
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element) => {
    sourceRuangan.value.push(element)
  })
}

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
    }
  }
)
watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchData()
    }
  }
)

const fetchData = async (e: any) => {
  isLoad.value = true
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let namapasien = item.value.namapasien ? `&namapasien=${item.value.namapasien}` : ''
  let noregistrasi = item.value.noregistrasi ? `&noregistrasi=${item.value.noregistrasi}` : ''
  let nocm = item.value.nocm ? `&nocm=${item.value.nocm}` : ''
  // let idPegawai = e ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''


  let limit: any = currentPage.value.limit
  let page: any = route.query.page ? route.query.page : 1
  isLoading.value = true
  dataPasien.value = []
  await useApi().get(`/dashboard/pasien-ppi-ranap?page=${page}&limit=${limit}${noregistrasi}${nocm}${idPegawai}${namapasien}${search}`).then((response) => {
    dataPasien.value = response.data
    totalData.value = response.total //i don't know why the paginate have 18 multiple
  })
  if (kelompokUser == 'dokter') {
    await countPasien(idPegawai)
  }

  isLoad.value = false
  isLoading.value = false
}

const fetchPasienRawatJalan = async (e: any) => {

let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
let limit: any = currentPage.value.limit
let page: any = route.query.page ? route.query.page : 1
let search = item.value.search ? `&search=${item.value.search}` : ''
let namapasien = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
let qnoreg = item.value.qnoreg ? `&noregistrasi=${item.value.qnoreg}` : ''
let konsul = item.value.pasienkonsul ? `&konsul=${item.value.pasienkonsul}` : ''
isReservasi.value = false
isLoading.value = true
isLoadCount.value = true
dataPasienRajal.value = []
await useApi().get(`/dashboard/pasien-ppi-rajal${dari}${sampai}${idPegawai}${namapasien}${nocm}${qnoreg}${konsul}${search}&page=${page}&limit=${limit}`).then((response) => {
  if(page > response.last_page){
    router.push({
      name: 'module-dashboard-rawat-jalan'
    })
  }
  isLoading.value = false
  response.data.forEach(pasien => {
      if(pasien.dokterTambahan.length > 0){
        pasien.dokterTambahan.forEach((element,index) => {
          element.no = index + 1,
          element.dokterPemeriksaTambahan = element ? { value: element.objectpegawaifk, label: element.namalengkap } : ''
        });
      }
    })
    dataPasienRajal.value = response.data
    dataPasienRajal.value.total = response.total
})
if (kelompokUser == 'dokter') {
  countPasien(dari, sampai, idPegawai, e)
}
}

const InputPpi = (e: any) => {
  item.value.norec_pd = e.norec_pd
  item.value.norec = e.norec_ppi
  item.value.namapasien = e.namapasien
  modalInputPPI.value = true
}


const saveInputPPI = async () => {
  console.log(item.value.namapasien)
  let json = {
    datainfeksi: {
      'norec_pd': item.value.norec_pd,
      'norec': item.value.norec ?? '',
      'namapasien': item.value.namapasien,
      'infeksi': item.value.infeksippi.value,
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/dashboard/save-inputan-infeksi-ppi`, json)
    .then((response: any) => {
      isLoading.value = false
      item.value.infeksippi = '' 
      item.value.namapasien = ''
      fetchData()
      fetchPasienRawatJalan()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
    modalInputPPI.value = false
}

const fetchInfeksi = async (filter: any) => {
    // let data = filter.query ? filter.query : filter
    await useApi().get(
        `emr/dropdown/infeksippi_m?select=id,namainfeksippi&param_search=namainfeksippi&query=${filter.query}&limit=10`
    ).then((response) => {
        d_InfeksiPPI.value = response
    })
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

const detailKamar = (e: any) => {
  item.value.id = e.id
  item.value.namakelas = e.namakelas
  item.value.namaruangan = e.namaruangan
  item.value.name = e.name
  item.value.namakamar = e.namakamar
  item.value.kosong = e.kosong
  item.value.isi = e.isi
  modalDetailKamar.value = true
}

let periode = H.cachePeriode().get('periode')
if (periode != undefined) {
  item.value.periode.start = new Date(periode['awal']);
  item.value.periode.end = new Date(periode['akhir']);
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

const showModalFilter = () => {
  modalFilter.value = true
}
const reload = () => {
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

const saveSKDokter = async () => {

  isLoading.value = true
  let objSave = {
    "norec": sourceItemSK.value.norec,
    "norec_pd": sourceItem.value.norec_pd,
    "dokterfk": sourceItem.value.objectpegawaifk,
    "tinggibadan": item.value.tinggiBadan,
    "beratbadan": item.value.beratBadan,
    "tekanandarah": item.value.tekananDarah,
    "denyutjantung": item.value.denyutNadi,
  }

  await useApi().post('dashboard/save-surat-keterangan-dokter', objSave).then((respon) => {
    isLoading.value = false
    modalSKDokter.value = false
    // useApi().get('report/ranap/cetak-surat-sehat?noregistrasi=' + sourceItem.value.noregistrasi)
    H.printBlade('report/ranap/cetak-surat-sehat?noregistrasi=' + sourceItem.value.noregistrasi)
  })
}

const saveSKSakit = async () => {
  isLoading.value = true
  let objSave = {
    "norec": sourceItemSK.value.norec,
    "norec_pd": sourceItem.value.norec_pd,
    "dokterfk": sourceItem.value.objectpegawaifk,
    "tglijinawal": item.value.tglAwalSakit,
    "tglijinakhir": item.value.tglAkhirSakit,
    "keterangan": item.value.catatanSakit,
    "indikasi": item.value.indikasiKembali,
    "hasilpemeriksaan": item.value.hasilPeriksa,
    "diagnosa": item.value.diagnosaSakit,
    "tglkontrol": item.value.tglKembaliRS,
  }
  await useApi().post('dashboard/save-surat-keterangan-sakit', objSave).then((response) => {
    isLoading.value = false
    modalSuratSakit.value = false
  })
  H.printBlade('report/ranap/cetak-surat-sakit?noregistrasi=' + sourceItem.value.noregistrasi)
}


const setAutoFill = () => {
  try {
    const objectkelasrawatfk = itemSource.value.kelasrawatfk;
    const objectkelasfk = itemSource.value.objectkelasfk;
    const norecKamar = itemSource.value.norec_kamar;
    item.value.namakelasrawat = objectkelasrawatfk || '';
    item.value.namakelas = objectkelasfk || '';
    // item.value.kamar = norecKamar || '';
    // console.log(objectkelasrawatfk)
    // console.log(norecKamar)

    changeKelas(item.value.namakelasrawat);
  } catch (error) {
    console.error('Error setting autofill:', error);
    item.namakelasrawat = '';
    item.namakelas = '';
    // item.kamar = '';
  }
};


const countPasien = async (pegawai: any) => {
  await useApi().get(`dashboard/get-jumlah-pendapatan?${pegawai}`).then((response) => {
    item.value.totalPasien = response.jumlahPasien
    item.value.totalTindakan = response.jumlahTindakan
    item.value.pendapatanJasa = response.pendapatanJasa
    isLoadCount.value = false
  })
}


const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    console.log('eeee')
    fetchData()
  }
  if (activeTab.value == 1) {
    fetchPasienRawatJalan()
  }
}

watch(
  () => item.value.isPasien, () => {
    fetchData()
    fetchPasienRawatJalan()
  }
)

onMounted(async () => {
  await fetchDropdown(),
    await fetchData(),
    await fetchPasienRawatJalan()
})
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/dashboard/rawat-jalan.scss';

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

.search-menu-ranap {
  height: 56px !important;
  white-space: nowrap !important;
  display: flex !important;
  flex-shrink: 0 !important;
  align-items: center !important;
  background-color: white !important;
  border-radius: 8px !important;
  width: 100% !important;
  padding-left: 0.75rem !important;

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

  .search-location-ranap,
  .search-job,
  .search-salary {
    display: flex !important;
    align-items: center !important;
    width: 50% !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    padding: 0 25px !important;
    height: 100% !important;
    font-family: var(--font);

    input {
      width: 100%;
      height: 90%;
      display: block;
      font-family: var(--font);
      color: var(--input-color);
      background-color: white;
      border: none;
    }

    svg {
      margin-right: 0.5rem;
      width: 18px;
      color: var(--primary);
      flex-shrink: 0;
    }
  }

  .search-button-ranap {
    background-color: var(--primary);
    min-width: 100px;
    height: 56px !important;
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
