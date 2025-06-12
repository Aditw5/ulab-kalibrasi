<template>
  <section>
    <div class="lifestyle-dashboard lifestyle-dashboard-v4">
      <div class="columns">
        <div class="column is-4">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <h3 class="title is-5 mb-2">Rekap Pendapatan
                  </h3>
                </div>
              </div>
              <div class="columns is-multiline">
                <div class="column is-12">
                  <CardCountRev icon="/images/simrs/icon-reservasi.png" straight
                    :total="H.formatRp(item.pendapatanJasa, 'Rp.')" label="Pendapatan" />
                </div>
              </div>
            </VCard>
          </div>

          <UIWidget class="search-widget" style="margin-top: 0.3rem;">
            <template #body>
              <div class="field" style="padding: 2px">
                <div class="control">
                  <input v-model="filter" class="input custom-text-filter" placeholder="Cari Pegawai" />
                  <button class="searcv-button" @click="fetchDetail()">
                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                  </button>
                </div>
              </div>
            </template>
          </UIWidget>

          <div class="featured-authors">
            <!--Header-->
            <div class="featured-authors-header">
              <h3 class="dark-inverted">Pegawai</h3>
            </div>

            <div class="tile-grid tile-grid-v2">

              <!--List Empty Search Placeholder -->
              <VPlaceholderPage :class="[dataDokter.length !== 0 && 'is-hidden']" title=" Data Tidak Ditemukan"
                subtitle="Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt=""
                    style="margin-top:-4rem;" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt=""
                    style="margin-top:-4rem;" />
                </template>
              </VPlaceholderPage>

              <!--Tile Grid v1-->

              <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <!--Grid item-->
                <div class="columns is-multiline p-2" style="max-height:500px; overflow: auto;">
                  <div v-for="item in dataDokter" :key="item.id" class="column is-12">
                    <div class="tile-grid-item" @click="detailDokter(item)">
                      <div class="tile-grid-item-inner">

                        <VAvatar size="small" picture="/images/avatars/svg/user2.svg" color="primary" squared
                          bordered />

                        <div class="meta">
                          <span class="dark-inverted">{{ item.namalengkap }}</span>
                          <span style="font-size: 0.9rem; padding: 2px;"> <i aria-hidden="true" class="iconify"
                              data-icon="feather:clock" style="padding-right: 3px;"></i> {{ item.jammulai }} s.d {{
                                item.jamakhir }}</span>

                        </div>
                        <VTag style="margin-left: 2rem;" color="info" label="Tag Label" rounded elevated> {{ item.hari
                        }}
                        </VTag>

                      </div>

                    </div>
                  </div>
                </div>
              </TransitionGroup>
            </div>
          </div>

        </div>
        <div class="column is-8">
          <div class="columns is-multiline">
            <!--Header-->
            <div class="column is-12" style="margin-left : 2rem;">
              <div class="illustration-header-2">
                <div class="header-image">
                  <img src="/@src/assets/illustrations/dashboards/lifestyle/Picture1.png" alt=""
                    style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                </div>
                <div class="header-meta">
                  <h4 style="color:white"> <i class="fas fa-bed"></i> {{ item.departemen }} </h4>
                  <h3> Dashboard Manager </h3>
                  <p>
                    Selamat Datang, {{ userLogin.pegawai.namaLengkap }}
                  </p>
                  <VControl>
                    <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label" filter
                      placeholder="Pilih Lingkup" :maxSelectedLabels="3" style="display:flex"
                      @change="changeRuang(sourceRuangan)" />
                    <!-- <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" placeholder="Pilih ruangan"
                    :searchable="true" autocomplete="off" @select="changeRuang(item.filterRuangan)" /> -->
                  </VControl>
                </div>
              </div>
            </div>

            <!--Content-->
            <div class="column is-12" style="margin-left : 2rem;">

              <div class="writing-stats">
                <!--Stat-->
                <div class="writing-stat">
                  <span>Mitra Registrasi</span>
                  <div v-if="isLoad">
                    <VPlaceload class="mx-2 mt-3" width="100%" />
                    <VIconBox color="blue" size="small" rounded style="margin-top: -1.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user"></i>
                    </VIconBox>
                  </div>
                  <div v-else>
                    <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);"> {{
                      item.jumlahDokter }} </span>
                    <VIconBox color="blue" size="small" rounded style="margin-top: -2.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user"></i>
                    </VIconBox>
                  </div>
                </div>
                <!--Stat-->
                <div class="writing-stat">
                  <span>Jumlah Alat</span>
                  <div v-if="isLoad">
                    <VPlaceload class="mx-2 mt-3" width="100%" />
                    <VIconBox color="danger" size="small" rounded style="margin-top: -1.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-product"></i>
                    </VIconBox>
                  </div>
                  <div v-else>
                    <span class="dark-inverted" style="font-weight: 700;font-size: 1.8rem;color: var(--dark-text);">
                      {{ totalData }}</span>
                    <VIconBox color="danger" size="small" rounded style="margin-top: -2.5rem; margin-left: 8rem">
                      <i aria-hidden="true" class="fas fa-user-check"></i>
                    </VIconBox>
                  </div>
                </div>
              </div>
              <div class="featured-authors">
                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar Mitra Registrasi</span>
                      <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 0">
                      <div class="list-view list-view-v3">
                        <div class="search-menu-ranap" style="margin-bottom : 1rem;">

                          <div class="search-location-ranap" style="width: 100% !important;">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Cari Nama Perusaahn, No Pendaftaran" v-model="item.search"
                              v-on:keyup.enter="fetchData()" />
                          </div>
                          <VButton raised class="search-button-ranap" :loading="isLoading" @click="fetchData()"> Cari
                            Data
                          </VButton>
                        </div>
                        <VPlaceholderPage :class="[totalData !== 0 && 'is-hidden']" title="Tidak Ada Data Saat Ini.">
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                              alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:500px; min-height: 300PX;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <div v-for="(items, m) in dataOrder" :key="m" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                                  :initials="items.initials" />
                                <div class="meta-left">
                                  <h3>
                                    {{ items.namaperusahaan }} <i aria-hidden="true"></i>
                                  </h3>
                                  <span style="color: black">
                                    <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                    <span> {{ items.nopendaftaran }}</span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                    <span>{{ items.tgldaftar }}</span>
                                  </span>
                                  <br>
                                </div>
                                <div class="meta-right">
                                  <VIconButton v-tooltip.bottom.left="'Verifikasi'" label="Bottom Left" color="primary"
                                    circle icon="pi pi-check-circle" v-if="items.statusorder == 0"
                                    @click="orderVerify(items)" style="margin-right: 15px;" />

                                  <VIconButton v-tooltip.bottom.left="'Detail'" label="Bottom Left" v-else
                                    color="primary" circle icon="pi pi-book" @click="getDetailVerify(items)"
                                    style="margin-right: 15px;" />
                                  <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'CETAK'">
                                    <template #content>
                                      <a role="menuitem" class="dropdown-item is-media" @click="cetakSEP(items)">
                                        <div class="icon">
                                          <i class="iconify" data-icon="feather:printer" aria-hidden="true"></i>
                                        </div>
                                        <div class="meta">
                                          <span>Cetak SEP</span>
                                          <span>Cetak Surat Elegibilitas</span>
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
                      <i class="fas fa-user-check mr-2" aria-hidden="true"></i>
                      <span> Daftar Alat</span>
                      <Badge :value="totalDataPulang" v-if="totalDataPulang > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 1">
                      <div class="list-view list-view-v3">
                        <div class="search-menu-ranap" style="margin-bottom : 1rem;">
                          <div class="column is-6" style="margin-top: 10px;">
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
                          <!-- <div class="search-location-ranap" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Pencarian ..." v-model="item.qsearch"
                              v-on:keyup.enter="fetchPasienPulang()" />
                          </div>
                          <VButton color="primary" raised class="search-button-ranap" @click="fetchPasienPulangResumeNull()"
                            :loading="isLoadingTT"> Belum Resume </VButton>
                          <VButton color="primary" raised class="search-button-ranap" @click="fetchPasienPulang()"
                            :loading="isLoadingTT"> Cari Data </VButton> -->
                          <div class="search-location-ranap" style="width: 100%">
                            <i class="iconify" data-icon="feather:search"></i>
                            <input type="text" placeholder="Pencarian ..." v-model="item.qsearch"
                              v-on:keyup.enter="handleFetchPasienPulang()" />
                          </div>

                          <VButton color="primary" raised class="search-button-ranap" @click="handleFetchPasienPulang"
                            :loading="isLoadingTT"> Cari Data </VButton>
                        </div>
                        <div class="search-menu-ranap">
                          <label>
                            <input type="checkbox" v-model="item.isResumeNull" @change="handleFetchPasienPulang" />
                            Belum Resume
                          </label>
                        </div>
                        <VPlaceholderPage :class="[dataPasienPulang.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Pasien Pulang Saat Ini." subtitle="Silakan Pulangkan Pasien dari Rawat Inap."
                          larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                              alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          </template>
                        </VPlaceholderPage>

                        <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <!--Item-->
                            <div v-for="item in dataPasienPulang" :key="item.id" class="list-view-item">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" color="primary" squared bordered
                                  :picture="jeniskelamin === 'Laki-Laki' ? '/images/avatars/svg/pasien.svg' : '/images/avatars/svg/vuero-4.svg'" />
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
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <VTag :color="item.color" :label="item.status" />
                                    <br>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:"></i>
                                    <span>
                                      {{ H.formatDateIndo(item.tglpulang) }}
                                    </span>
                                    <i aria-hidden="true" class="fas fa-circle icon-separator"
                                      v-if="item.tglmeninggal"></i>
                                    <VTag v-if="item.tglmeninggal" label="Pasien Meninggal" :color="'danger'"
                                      class="mr-2 mt-3-min" v-tooltip.bubble="'Pasien Meninggal'" />

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
                                      <VIconButton color="primary" class="mr-3" circle icon="fas fa-stethoscope"
                                        outlined raised v-tooltip.bottom.left="'EMR'">
                                      </VIconButton>
                                    </RouterLink>
                                  </div>

                                  <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary"
                                    circle outlined raised v-tooltip.bottom="'Aksi'" @click="toggle($event, item)">
                                  </VIconButton>

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
    <VModal :open="modalDetailProduk" title="Detail Stok Produk" actions="right" @close="modalDetailProduk = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VLabel>Nama Produk</VLabel>
                <VControl icon="fas fa-box">

                  <VInput type="textarea" v-model="item.objectprodukfk" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Asal Produk</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="textarea" v-model="item.objectasalprodukfk" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Jumlah Stok</VLabel>
                <VControl icon="fas fa-database">
                  <VInput type="textarea" v-model="item.qtyproduk" placeholder="Jumlah" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Harga Beli</VLabel>
                <VControl icon="fas fa-cart-plus" aria-hidden="true">
                  <VInput type="textarea" v-model="item.harganetto1" placeholder="Harga Beli" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField>
                <VLabel>Harga Jual</VLabel>
                <VControl icon="fas fa-shopping-cart" aria-hidden="true">
                  <VInput type="textarea" v-model="item.harganetto2" class="is-rounded"
                    style="background-color: #0398E2; color:white;" disabled />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
    </VModal>
    <VModal :open="modalSuratRawatInap" title="Cetak Surat Rawat Inap" :noclose="true" size="medium" actions="right"
      @close="modalSuratRawatInap = false">
      <template #content>
        <form class="modal-form">
          <div class="columns">
            <div class="column is-12">
              <VField class="is-centered">
                <VControl>
                  <VInput type="text" v-model="dataPenangguangJawab.penanggungJawab" placeholder="Penaggung Jawab"
                    required />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-12">
              <VField class="is-centered">
                <VControl>
                  <VInput type="text" v-model="dataPenangguangJawab.alamat" placeholder="Alamat" required />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <!-- <VButton icon="feather:search" :loading="isLoading" color="primary" raised @click="saveSKDokter(item)">
        Simpan</VButton> -->
        <VButton icon="feather:printer" @click="printSuratRawatInap()" :loading="isLoading" color="primary" raised>
          Cetak</VButton>
      </template>
    </VModal>
    <OverlayPanel ref="op">
      <VButton type="button" icon="fas fa-eye" class="mr-2" circle outlined color="info" raised
        @click="detailRegistrasi(selectedItem)">
        Detail Registrasi
      </VButton>
      <VButton type="button" icon="fas fa-file" class="mr-2" circle outlined color="black" raised
        @click="RequestSITB(selectedItem)" :disabled="selectedItem.sitb_id">
        {{ selectedItem.sitb_id ?? "Request SITB" }}
      </VButton>
      <VButton type="button" icon="fas fa-door-open" class="mr-2" circle outlined color="black" raised
        @click="openModalBatalPulang(selectedItem)">
        Batal Pulang
      </VButton>
      <VButton type="button" icon="fas fa-calendar-alt" class="mr-2" circle outlined color="warning" raised
        @click="openModalUbahTglPulang(selectedItem)">
        Ubah Tanggal Pulang
      </VButton>
      <VButton type="button" icon="fas fa-th-list" class="mr-2" circle outlined color="primary" raised
        @click="billing(selectedItem)">
        Billing
      </VButton>
      <VButton type="button" icon="fas fa-clipboard-check" :disabled="selectedItem.statuspulang != 'Meninggal'"
        class="mr-2" circle outlined color="danger" raised @click="saveSuratKematian(selectedItem)">
        Surat Meninggal
      </VButton>
      <VButton type="button" icon="fas fa-file" class="mr-2" circle outlined color="warning" raised
        @click="cetakLembarKeluar(selectedItem)">
        Lembar Keluar Masuk
      </VButton>
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
import Badge from 'primevue/badge';
import OverlayPanel from 'primevue/overlaypanel';
import Dialog from 'primevue/dialog';
import * as qzService from '/@src/utils/qzTrayService'
import Calendar from 'primevue/calendar';
import { useToaster } from '/@src/composable/toaster'
useHead({
  title: 'Dashboard Manager - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const themeColors = useThemeColors()
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
  isResumeNull: false,
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
const dataOrder: any = ref(0)
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const modalDetailKamar = ref(false)
const modalSuratRawatInap = ref(false)
const modalSuratKematian = ref(false)
const modalSuratSakit = ref(false)
const modalChangeDokter = ref(false)
const modalDelegasiDokter = ref(false)
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
let dataPasienPulang: any = ref([])
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
const isLoadingPop: any = ref(false)

const dataSourceStok = computed(() => {
  if (!filters.value) {
    return dataStok.value
  }
  return dataStok.value.filter((item: any) => {
    return item.namaproduk.match(new RegExp(filters.value, 'i'))
  })
})

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataDokter.value
  }
  return dataDokter.value.filter((item: any) => {
    return item.namapegawai.match(new RegExp(filters.value, 'i'))
  })
})

const tambahDokter = () => {
  listDokterTambahan.value.push({
    no: listDokterTambahan.value.length + 1,
  })
}


const fetchDataOrder = async (q: any) => {
    statusOrder.value = q
    isLoading.value = true
    let dari = 'dari=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD 00:00')
    let sampai = '&sampai=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD  23:59')
    let search = ''
    let qnocm = ''
    let qnoregistrasi = ''
    let StatusOrder = ''
    item.value.statusOrder = q
    if (order) StatusOrder = '&statusorder=' + q
    if (item.value.search) search = '&search=' + item.value.search
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    let page: any = route.query.page ? route.query.page : 1

    await useApi().get(`asman/list-mitra-regis?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + dari + sampai + StatusOrder + search).then((response) => {
        // modalFilter.value = false
        dataOrder.value = response.data
        totalData.value = response.total
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            let ini = element.namaperusahaan.split(' ')
            let init = element.namaperusahaan.substr(0, 2)
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1)
            }
            element.initials = init
        });
        isData.value = response.total
        isLoading.value = false
    }).catch((err) => {
        // modalFilter.value = false
    })
    isLoading.value = false
}

const fetchDropdown = async () => {
  const response = await useApi().get('/dashboard/dropdown-rawat-inap')
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element) => {
    sourceRuangan.value.push(element)
  })
  // d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  // console.log(d_Ruangan.value)
  // debugger
  // d_Ruangan.value.forEach((element) => {
  //   sourceRuangan.value.push(element)
  // })
  // const response = await useApi().get('/dashboard/dropdown-rawat-inap')
  // console.log(response)
  // debugger
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
      fetchPasienPulang()
      fetchPasienPulangResumeNull()
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
  await useApi().get(`/dashboard/rawat-inap/list?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${search}`).then((response) => {
    dataPasien.value = response.data
    response.data.forEach(pasien => {
      if (pasien.dokterTambahan.length > 0) {
        pasien.dokterTambahan.forEach((element, index) => {
          element.no = index + 1,
            element.dokterPemeriksaTambahan = element ? { value: element.objectpegawaifk, label: element.namalengkap } : ''
        });
      }
    })
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

const InputSitb = (e: any) => {
  item.value.norec_pd = e.norec_pd
  item.value.nositb = e.sitb_id
  item.value.nocmfk = e.nocmfk
  modalInputSitb.value = true
}

const InputPpi = (e: any) => {
  item.value.norec_pd = e.norec_pd
  item.value.norec = e.norec_ppi
  item.value.namapasien = e.namapasien
  item.value.nocm = e.nocm
  modalInputPPI.value = true
}

const tandaPasienIGD = async (e: any) => {
  console.log(e)
  let json = {
    'norec': e.norec_pd,
    'isonsiteservice': true,
  }

  await useApi().post(`/dashboard/update-tanda-pasien`, json)
    .then(response => {
      fetchData()
    }).catch(err => {
      console.error(err);
      fetchData()
    })
}

const batalTandaPasienIGD = async (e: any) => {
  console.log(e)
  let json = {
    'norec': e.norec_pd,
    'isonsiteservice': null,
  }

  await useApi().post(`/dashboard/update-tanda-pasien`, json)
    .then(response => {
      fetchData()
    }).catch(err => {
      console.error(err);
      fetchData()
    })
}

const saveInputSitb = async () => {
  // if (!item.value.alasanpembatalan) { H.alert('warning', 'Alasan Pembatalan harus di isi'); return }
  let json = {
    pasiendaftar: {
      'norec': item.value.norec_pd,
      'sitb': item.value.nositb,
      'nocmfk': item.value.nocmfk,
    }
  }
  await useApi()
    .post(`/dashboard/save-nositb-pasien`, json)
    .then((response: any) => {
      isLoading.value = false
      fetchData()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  modalInputSitb.value = false
}

const saveInputPPI = async () => {
  console.log(item.value.namapasien)
  let json = {
    datainfeksi: {
      'norec_pd': item.value.norec_pd,
      'norec': item.value.norec ?? '',
      'namapasien': item.value.namapasien,
      'nocm': item.value.nocm,
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

const RequestSITB = async (e) => {
  await useApi()
    .post(`/dashboard/order-nositb-pasien`, e)
    .then((response: any) => {
      isLoading.value = false
      fetchData()
      fetchPasienPulang()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  // console.log(e)
  H.alert('success', 'Order Sudah terkirim, menunggu verifikasi');
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

const handleFetchPasienPulang = () => {
  if (item.value.isResumeNull) {
    fetchPasienPulangResumeNull();
  } else {
    fetchPasienPulang();
  }
};

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
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1

  isLoadingTT.value = true
  dataPasienPulang.value = []
  await useApi().get(`/dashboard/rawat-inap-pasien-total?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${qsearch}`).then((response) => {
    dataPasienPulang.value = response.data
    totalDataPulang.value = response.total
  })
  isLoad.value = false
  isLoadingTT.value = false
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
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  let page: any = route.query.page ? route.query.page : 1

  isLoadingTT.value = true
  dataPasienPulang.value = []
  await useApi().get(`/dashboard/rawat-inap-pasien-total-resume-null?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${ruanganid}${noregistrasi}${nocm}${idPegawai}${namapasien}${begin}${last}${qsearch}`).then((response) => {
    dataPasienPulang.value = response.data
    totalDataPulang.value = response.total
  })
  isLoad.value = false
  isLoadingTT.value = false
}

const billing = (e: any) => {
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: e.norec_pd,
    },
  })
}

function batalPulang(e: any) {
  if (!item.value.namakelas) {
    H.alert('error', 'Kelas ditanggung harus diisi')
    return
  }

  if (!item.value.kamar) {
    H.alert('error', 'Kamar harus dipilih')
    return
  }
  if (!item.value.bed) {
    H.alert('error', 'Bed harus dipilih')
    return
  }
  const objSave = {
    'norec_apd': itemSource.value.norec_apd,
    'norec_pd': itemSource.value.norec_pd,
    'objectkelas': item.value.namakelas,
    'objectkamar': item.value.kamar,
    'objekbed': item.value.bed,
  }
  useApi().post('/rawatinap/batal-pulang-pasien', objSave).then((response) => {
    fetchPasienPulang()
    modalBatalPulang.value = false
  })
}

const BatalRawatInap = async (e: any) => {
  let json = {
    pasiendaftar: {
      norec_pd: e.norec_pd,
    },
    antrianpasiendiperiksa: {
      norec_apd: e.norec_apd,
    }
  }
  isLoading.value = true
  isLoad.value = true
  await useApi()
    .post(`/dashboard/batal-ranap`, json)
    .then((response: any) => {
      reload()
      isLoading.value = false
      isLoad.value = false
    })

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

const PulangPindah = async (e: any) => {
  router.push({
    name: 'module-rawat-inap-pindah-pulang',
    query: {
      nocmfk: item.nocmfk,
      norec_pd: item.norec_pd,

    },
  })
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
  fetchPasienMutasi()
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

const showModalSKDokter = async (e: any) => {
  sourceItem.value = e
  await useApi().get('dashboard/get-data-surat-keterangan?norec_pd=' + e.norec_pd + '&jenissurat=' + 'SuratKeteranganSehat').then((response) => {
    modalSKDokter.value = true
    item.value.tinggiBadan = response.tinggibadan ? response.tinggibadan : ''
    item.value.beratBadan = response.beratbadan ? response.beratbadan : ''
    item.value.tekananDarah = response.tekanandarah ? response.tekanandarah : ''
    item.value.denyutNadi = response.denyutjantung ? response.denyutjantung : ''
    sourceItemSK.value = response
  })
}

const showModalSKSakit = async (e: any) => {
  sourceItem.value = e
  await useApi().get('dashboard/get-data-surat-keterangan?norec_pd=' + e.norec_pd + '&jenissurat=' + 'SuratKeteranganSakit').then((response) => {
    modalSuratSakit.value = true
    item.value.tglAwalSakit = response.tglawal ? response.tglawal : ''
    item.value.tglAkhirSakit = response.tglakhir ? response.tglakhir : ''
    item.value.tglKembaliRS = response.tglkontrol ? response.tglkontrol : ''
    item.value.diagnosaSakit = response.diagnosa ? response.diagnosa : ''
    item.value.hasilPeriksa = response.hasilpemeriksaan ? response.hasilpemeriksaan : ''
    item.value.indikasiKembali = response.indikasi ? response.indikasi : ''
    item.value.catatanSakit = response.keterangan ? response.keterangan : ''
    sourceItemSK.value = response
  })
}

const test = () => {
  console.log('ee')
}

const modalRawatInap = (e: any) => {
  modalSuratRawatInap.value = true;
  cetak.value = e;
}

const printSuratRawatInap = async (e: any) => {
  if (dataPenangguangJawab.value.penanggungJawab == undefined || dataPenangguangJawab.value.alamat == undefined) {
    H.alert('error', 'isi semua data')
    return
  }
  H.printBlade(`report/cetak-lembar-ranap?noregistrasi=${cetak.value.norec_pd}&penaggungJawab=${dataPenangguangJawab.value.penanggungJawab}&alamat=${dataPenangguangJawab.value.alamat}`)
  dataPenangguangJawab.value.penanggungJawab = null;
  dataPenangguangJawab.value.alamat = null;
}

const cetakGelangPasien = (e: any) => {
  cetak.value = e;
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${cetak.value.noregistrasi}`, 'GELANG PASIEN', 1)
  // H.printBlade(`report/cetak-gelang-pasien?noregistrasi=${cetak.value.noregistrasi}`)
}

const cetakLabelPasien = (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
  // let dokter = `&dokter=${e.namalengkap}`
  // let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?noregistrasi=${e.noregistrasi}${dokter}${tglregistrasi}`);
}

const cetakSuratKeteranganDokter = async (e: any) => {

  let dokter = `&dokter=${e.dokter}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=${e.objectdepartemenfk}`
  let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  let norec_pd = `&norec_pd=${e.norec_pd}`
  H.printBlade(`dashboard/registrasi/cetak-surat-keterangan-dokter?noregistrasi=${e.noregistrasi}${dokter}${kelompokpasien}${objectdepartemenfk}${tglregistrasi}${norec_pd}`);
}

const kirimWASuratKeteranganDokter = async (e: any) => {
  isLoadingPop.value = true;

  try {
    if (!e.namalengkap || !e.kelompokpasien || !e.tglregistrasi || !e.norec_pd) {
      throw new Error("Data tidak lengkap. Harap isi semua informasi yang diperlukan.");
    }

    const response = await useApi().post('/dashboard/send-WA-Ranap', {
      dokter: e.namalengkap,
      kelompokpasien: e.kelompokpasien,
      objectdepartemenfk: '16',
      tglregistrasi: e.tglregistrasi,
      norec_pd: e.norec_pd,
    });

    if (response && response.data) {
      console.log("Response berhasil:", response.data);
    } else {
      console.warn("Response dari server tidak valid:", response);
    }
  } catch (error: any) {
    console.error("Gagal mengirim WA:", error.message || error);
  } finally {
    isLoadingPop.value = false;
  }
}

const saveSuratKematian = async (e: any) => {
  cetak.value = e;

  let json = {
    norec_pd: cetak.value.norec_pd,
    tglmeninggal: cetak.value.tglmeninggal,
    dokterfk: cetak.value.dokterfk,
    ketarangan: "sakit"
  }

  isLoading.value = true
  await useApi()
    .post(`/general/save-surat-keterangan-kematian`, json)
    .then((response: any) => {
      isLoading.value = false
      fetchData()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  H.printBlade(`report/cetak-surat-kematian?jenissuratfk=21&norec=${cetak.value.norec_pd}`)
}

const cetakLembarKeluar = (e: any) => {
  // useApi().get('report/cetak-surat-ranap')
  H.printBlade(`report/cetak-lembar-keluar-masuk?norec=${e.norec_pd}`)
}

const cetakRegisRanap = (e: any) => {

  // H.printBlade(`report/cetak-surat-pendaftaran-ranap?norec=${e.norec_pd}`)
  useApi().get(`report/cetak-surat-pendaftaran-ranap?norec=${e.norec_pd}&nocmfk=${e.nocmfk}`)
}

const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
    'SEP', 1)
}

const saveSuratMeninggal = async (e: any) => {
  cetak.value = e;
  let json = {
    norec_pd: cetak.value.norec_pd,
    tglmeninggal: cetak.value.tglmeninggal,
    dokterfk: cetak.value.dokterfk,
    ketarangan: "sakit"
  }

  isLoading.value = true
  await useApi()
    .post(`/general/save-surat-keterangan-meninggal`, json)
    .then((response: any) => {
      isLoading.value = false
      H.printBlade(`report/cetak-surat-meninggal?jenissuratfk=22&norec=${cetak.value.norec_pd}`)
      fetchData()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const openModalDpjp = (data: any) => {
  modalChangeDokter.value = true
  item.value.dokterPemeriksa = data.objectpegawaifk ? { value: data.objectpegawaifk, label: data.namalengkap } : ''
  item.value.dokterPemeriksa2 = data.objectpegawairawatbersamafk ? { value: data.objectpegawairawatbersamafk, label: data.nama } : ''
  itemSource.value = data
  listDokterTambahan.value = data.dokterTambahan
}

const openModalDelegasiDpjp = (data: any) => {
  modalDelegasiDokter.value = true
  item.value.dokterPemeriksa = data.objectpegawaifk ? { value: data.objectpegawaifk, label: data.namalengkap } : ''
  itemSource.value = data
}

const openModalUbahTglPulang = (data) => {
  modalChangeTglPulang.value = true;
  item.value.tglpulang = data.tglpulang ? data.tglpulang : '';
  itemSource.value = data;
  console.log(data);
}

const openModalBatalPulang = (data) => {
  modalBatalPulang.value = true;
  item.value.namaruangan = data.objectruanganlastfk
  setKelas(data.objectruanganlastfk)
  fetchKelas()
  itemSource.value = data;
}

const openModalUbahBed = (data) => {
  modalInput.value = true;
  item.value.kamar = null;
  item.value.bed = null;
  fetchKelas();
  item.value.namaruanganPindah = data.namaruangan ? data.namaruangan : '';
  itemSource.value = data;
  setAutoFill()
  changeRuangPindah();
  console.log(data);
}

const changeRuangPindah = () => {
  const e = itemSource.value.objectruanganlastfk;
  item.namakelas = null;
  item.namakelasrawat = null;
  item.kamar = null;
  item.bed = null;
  if (e) {
    setKelas(e);
  }
};

const setKelas = async (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/rawatinap/kelas-ranap-by-ruangan?id=${itemSource.value.objectruanganlastfk ? itemSource.value.objectruanganlastfk : e}`)
    .then((response: any) => {
      if (response.length == 1) {
        item.namakelas = response[0].id
      }
      isLoadingTT.value = false
      d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
  // console.log(e)
}

const changeKelas = async (e: any) => {
  d_Kamar.value = []
  item.kamar = null;
  item.bed = null;
  if (e && itemSource.value.objectruanganlastfk) {
    isLoadingTT.value = true
    useApi().get(
      `/rawatinap/kamar-ranap-by-kelas?id=${e}&idRuangan=${itemSource.value.objectruanganlastfk}&isRG=false`)
      .then((response: any) => {
        isLoadingTT.value = false
        d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
      })
      .catch((error: any) => { isLoadingTT.value = false })
  }
  console.log(e)
  // console.log(itemSource.value.namaruangan)
}
const fetchKelas = async () => {
  await useApi().get(
    `emr/dropdown/kelas_m?select=id,namakelas`
  ).then((response) => {
    d_KelasAll.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
  })
}
const changeKamar = async (e: any) => {
  d_TempatTidur.value = []
  if (e) {
    for (let x = 0; x < d_Kamar.value.length; x++) {
      const element = d_Kamar.value[x];
      if (element.value == e) {
        d_TempatTidur.value = element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
      }
    }
  }
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

const simpanPindah = async () => {
  if (!item.value.namakelas) {
    useToaster().warn('Nama Kelas harus di isi')
    return
  }
  if (!item.value.namakelasrawat) {
    useToaster().warn('Nama Kelas Rawat  harus di isi')
    return
  }
  if (!item.value.kamar) {
    useToaster().warn('Nama Kamar Rawat  harus di isi')
    return
  }
  if (!item.value.bed) {
    useToaster().warn('Nama Bed Rawat  harus di isi')
    return
  }
  item.tglpindah = new Date();
  let json = {
    pasien: {
      nocm: itemSource.value.nocm,
      namapasien: itemSource.value.namapasien,
      noregistrasi: itemSource.value.noregistrasi,
    },
    pasiendaftar: {
      norec_pd: itemSource.value.norec_pd,
      objectruangantujuanfk: itemSource.value.objectruanganlastfk,
    },
    antrianpasiendiperiksa: {
      norec_apd: itemSource.value.norec_apd,
      tglkeluar: H.formatDate(item.tglpindah, 'YYYY-MM-DD HH:mm:ss'),
      objectkelasfk: item.value.namakelas,
      objectkelasrawatfk: item.value.namakelasrawat,
      objectkamarfk: item.value.kamar ? item.value.kamar : null,
      objectbedfk: item.value.bed ? item.value.bed : null,
    },
  }
  isLoading.value = true
  await useApi()
    .post(`/rawatinap/save-pindah-bed-pasien`, json)
    .then((response: any) => {
      isLoading.value = false
      item.value.kamar = null;
      item.value.bed = null;
      modalInput.value = false
      fetchData()
    })
    .catch((e: any) => {
      if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
        changeKelas(item.namakelasrawat)
      }
      isLoading.value = false
    })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const saveChangeDokter = async () => {
  if (!item.value.dokterPemeriksa) {
    H.alert('error', 'Dokter Wajib Dipilih')
    return
  }
  // btnLoadSimpan.value = true
  console.log(listDokterTambahan.value)
  await useApi().post('registrasi/change-dokter-dpjp', { 'pasien': itemSource.value, 'norec_pd': itemSource.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value, 'objectpegawairawatbersamafk': item.value.dokterPemeriksa2.value, dokterTambahan: listDokterTambahan.value }).then((response) => {
    btnLoadSimpan.value = false
    modalChangeDokter.value = false
    fetchData()
  }).catch((err) => {
    console.log(err)
  })
}
const saveDelegasiDokter = async () => {
  if (!item.value.dokterPemeriksa) {
    H.alert('error', 'Dokter Wajib Dipilih')
    return
  }
  // btnLoadSimpan.value = true
  await useApi().post('registrasi/change-dokter-dpjp', { 'pasien': itemSource.value, 'norec_pd': itemSource.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value, 'isDelegasi': true }).then((response) => {
    btnLoadSimpan.value = false
    modalDelegasiDokter.value = false
    fetchData()
  }).catch((err) => {
    console.log(err)
  })
}

const saveChangeTglPulang = async () => {
  btnLoadSimpan.value = true;
  try {
    if (itemSource.value.nobpjs) {
      let json = {
        "url": "SEP/2.0/updtglplg",
        "method": "PUT",
        "data": {
          "request": {
            "t_sep": {
              "noSep": itemSource.value.nosep,
              "statusPulang": itemSource.value.objectstatuspulangfk,
              "noSuratMeninggal": item.nosuratmeninggal == undefined ? "" : item.nosuratmeninggal,
              "tglMeninggal": item.statuspulang == 4 ? H.formatDate(item.tglmeninggal, 'YYYY-MM-DD') : "",
              "tglPulang": H.formatDate(item.value.tglpulang, 'YYYY-MM-DD'),
              "noLPManual": item.nolpmanual ?? null,
              "user": H.namaPegawai()
            }
          }
        }
      };
      await useApi().post(`/bridging/bpjs/tools`, json);
    }
    const formattedDate = H.formatDate(item.value.tglpulang, 'YYYY-MM-DD HH:mm:ss');
    const response = await useApi().post('registrasi/change-tglpulang', {
      'norec_pd': itemSource.value.norec_pd,
      'tglpulang': formattedDate
    });
    modalChangeTglPulang.value = false;
    fetchPasienPulang();
  } catch (error) {
    console.error(error);
  } finally {
    btnLoadSimpan.value = false;
  }
}


const toggle = (event: any, e: any) => {
  selectedItem.value = e
  op.value.toggle(event);
}

const countPasien = async (pegawai: any) => {
  await useApi().get(`dashboard/get-jumlah-pendapatan?${pegawai}`).then((response) => {
    item.value.totalPasien = response.jumlahPasien
    item.value.totalTindakan = response.jumlahTindakan
    item.value.pendapatanJasa = response.pendapatanJasa
    isLoadCount.value = false
  })
}

const fetchPasienMutasi = async () => {
  let dari = `?tglAwal=${H.formatDate(item.value.periodeMutasi.start, 'YYYY-MM-DD')}`
  let sampai = `&tglAkhir=${H.formatDate(item.value.periodeMutasi.end, 'YYYY-MM-DD')}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }

  let search = item.value.qsearchmutasi ? `&search=${item.value.qsearchmutasi}` : ''
  isLoadingTT.value = true
  await useApi().get(`dashboard/get-mutasi-ranap${dari}${sampai}${search}${ruanganid}`).then((response) => {
    sourceMutasi.value = response
    isLoadingTT.value = false
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
    console.log('eeee')
    fetchData()
  }
  if (activeTab.value == 1) {
    fetchPasienPulang()
  }
}

const openModalAccKanker = (e: any) => {
  noCmFkPasien.value = e.nocmfk
  modalAccKanker.value = true
}

const openModalAccKanker2 = (e: any) => {
  noCmFkPasien.value = e.nocmfk
  modalAccKanker2.value = true
}

const batalkanPasienKanker = async (e: any) => {
  if (noCmFkPasien.value == "") {
    H.alert('error', 'Pilih Pasin Terlebih Dahulu!')
    return
  }

  let formData = {
    'norec_pasien': noCmFkPasien.value,
  }
  isLoading.value = true
  await useApi().post('/pasien/batal-status-kanker', formData).then((r) => {
    isLoading.value = false
    noCmFkPasien.value = ""
    modalAccKanker2.value = false
    reload()
    // modalInput.value = false
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const jadikanPasienKanker = async (e: any) => {
  if (noCmFkPasien.value == "") {
    H.alert('error', 'Pilih Pasin Terlebih Dahulu!')
    return
  }
  let formData = {
    'norec_pasien': noCmFkPasien.value,
  }
  isLoading.value = true
  await useApi().post('/pasien/jadikan-status-kanker', formData).then((r) => {
    isLoading.value = false
    noCmFkPasien.value = ""
    modalAccKanker.value = false
    reload()
    // modalInput.value = false
  }).catch((e: any) => {
    isLoading.value = false
  })
}


// onMounted(async () => {
//   await fetchDropdown(),
//     await fetchData(),
//     await fetchDetail(),
//     await fetchPasienPulang(),
//     await fetchPasienMutasi()
// })

fetchDataOrder(0)


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
