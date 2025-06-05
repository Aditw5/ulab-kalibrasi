
<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-12">
        <div class="columns is-multiline">
          <!--Header-->
          <div class="column is-12">
            <div class="illustration-header-2">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                  style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
              </div>
              <div class="header-meta">
                <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Gizi</h3>
                <p>
                  Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                </p>
                <VControl>
                  <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan" placeholder="Pilih ruangan"
                    :searchable="true" autocomplete="off" @select="changeRuang(item.filterRuangan)" />
                </VControl>
              </div>
            </div>
          </div>

          <div class="column is-12" style="margin-top: 1rem;">
            <VTabs slider selected="Pasien" :tabs="[
              { label: 'Dashboard Order', value: 'Pasien' },
              { label: 'Daftar Order Gizi', value: 'Riwayat' },
            ]">

              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'Pasien'">
                  <!-- <div class="list-view list-view-v3"> -->
                    <div class="search-menu-gizi" style="margin-bottom : 1rem;">
                      <div class="search-location-gizi" style="width: 100%">
                          <i class="iconify" data-icon="feather:search"></i>
                          <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK" v-model="item.search" v-on:keyup.enter="fetchOrder(order)" />
                      </div>
                      <VButton raised class="search-button-gizi" @click="fetchOrder()" :loading="isLoading"> Cari Data</VButton>
                    </div>
                    <VControl class="mb-3">
                      <Multiselect mode="single" v-model="item.filterRuanganRanap" :options="d_RuanganRanap" placeholder="Pilih ruangan" :searchable="true" autocomplete="off" @select="changeRuangOrder(item.filterRuanganRanap)" />
                    </VControl>
                    <div class="list-view list-view-v3">
                      <div v-if="dataOrder.loading">
                        <div v-for="key in 4" :key="key" class="column is-12">
                          <VCard>
                            <div class="tile-grid-item">
                              <div class="tile-grid-item-inner placeload-wrap">
                                <div class="columns">
                                  <div class="column is-1">
                                    <VPlaceloadAvatar rounded="sm" />
                                  </div>
                                  <div class="column">
                                    <div class="column mb-4 pt-0">
                                      <VPlaceload class="mx-2" width="30%" />
                                    </div>
                                    <div class="columns pl-5">
                                      <VPlaceload class="mx-2" width="30%" />
                                      <VPlaceload class="mx-2" width="30%" />
                                      <VPlaceload class="mx-2" width="30%" />
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </VCard>
                        </div>
                      </div>
      
                      <div v-else class="column is-12 p-0" style="overflow: scroll;height: 44rem;">
                        <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                          <TransitionGroup name="list-complete" tag="div">
                            <Vcard>
                              <div v-if="dataOrder.length > 0" v-for="item in dataOrder" :key="item.id" class="list-view-item">
                                <div class="list-view-item-inner">
                                  <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                  <div class="meta-left">
                                    <h3>
                                      {{ item.namapasien }} <VTag :color="item.statusorder == 0 ? 'warning' : 'primary'" :label="item.status" rounded />
          
                                    </h3>
          
                                    <span>
          
                                      <i aria-hidden="true" class="iconify" data-icon="feather:user"></i>
                                      <span>{{ item.jeniskelamin }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                      <span>{{ item.namaruangan }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                      <span>{{ item.tglregistrasi }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                      <span>{{ item.noregistrasi }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                      <span>{{ item.noorder }}</span>
          
                                    </span>
                                    <br>
          
                                  </div>
                                  <div class="meta-right">
                                    <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                      color="info" raised circle class="mr-2" :loading="isLoading">
                                    </VIconButton>
                                    <VIconButton v-if="item.statusorder == 0" v-tooltip.bottom.left="'Edit Order Gizi'" icon="feather:edit" @click="editOrder(item)"
                                      color="warning" raised circle class="mr-2" :loading="isLoading">
                                    </VIconButton>
                                    <VIconButton v-if="item.statusorder == 0" v-tooltip.bottom.left="'Verifikasi Order Gizi'" icon="feather:check" @click="verifikasiOrder(item)"
                                      color="primary" raised circle class="mr-2" :loading="isLoading">
                                    </VIconButton>
                                  </div>
                                </div>
                              </div>
                              <div v-else class="p-0" style="margin-top: -58px;">
                                <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                                    style="max-width: 38%;margin-top: 2rem;" />
                                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                                    style="max-width: 38%;margin-top: 2rem;" />
                                </div>
                                <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                                  Daftar Permintaan Saat ini tidak tersedia</h3>
                              </div>
                            </Vcard>
                          </TransitionGroup>
                        </div>
                      </div>
                    </div>
                  <!-- </div> -->
                </p>
                <p v-else-if="activeValue === 'Riwayat'">
                  <div class="columns is-multiline">
                    <div class="column is-4" >
                      <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                    <div class="column is-3">
                      <VControl>
                        <Multiselect mode="single" v-model="item.filterRuanganOrder" :options="d_RuanganRanap" placeholder="Pilih ruangan" :searchable="true" autocomplete="off" @select="changeRuangRiwayat(item.filterRuanganOrder)" />
                      </VControl>
                    </div>
                    <div class="column is-4">
                      <VField>
                        <VControl>
                          <VInput type="text" v-model="item.qsearch" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK" v-on:keyup.enter="fetchRiwayat()" />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-1">
                      <VButton @click="fetchRiwayat()" :loading="isLoading" color="primary" raised>Cari Data</VButton>
                    </div>
                  </div>
                  <div class="mb-3">
                    <VCard class="text-center pt-0 pb-0 mt-0">
                      <VRadio v-model="order" value="99" label="Semua" name="outlined_radio"
                          color="danger" />
                      <VRadio v-model="order" value="3" label="Proses" name="outlined_radio"
                          color="warning" />
                      <VRadio v-model="order" value="4" label="Dikirim" name="outlined_radio"
                          color="warning" />
                      <VRadio v-model="order" value="5" label="Selesai" name="outlined_radio"
                          color="primary" />
                    </VCard>
                  </div>
                  <div v-if="dataRiwayat.loading">
                    <div v-for="key in 4" :key="key" class="column is-12">
                      <VCard>
                        <div class="tile-grid-item">
                          <div class="tile-grid-item-inner placeload-wrap">
                            <div class="columns">
                              <div class="column is-1">
                                <VPlaceloadAvatar rounded="sm" />
                              </div>
                              <div class="column">
                                <div class="column mb-4 pt-0">
                                  <VPlaceload class="mx-2" width="30%" />
                                </div>
                                <div class="columns pl-5">
                                  <VPlaceload class="mx-2" width="30%" />
                                  <VPlaceload class="mx-2" width="30%" />
                                  <VPlaceload class="mx-2" width="30%" />
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </VCard>
                    </div>
                  </div>
  
                  <div v-else class="column is-12 p-0" style="overflow: scroll;height: 44rem;">
                    <div class="column is-12 p-0 pb-3" v-if="dataRiwayat.length > 0" v-for="(item) in dataRiwayat"
                      :key="item.id">
                      <VCard>
                        <div class="columns is-multiline">
                          <div class="column is-1" style="padding-top:37px">
                            <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared
                              bordered />
                          </div>
                          <div class="column is-11" style="padding-left: 23px;">
                            <div class="columns is-multiline">
                              <div class="column is-4 pb-0">
                                <label style="font-weight: 400;color: darkgray;font-size: 12px;">Pasien</label>
                                <h3 class="field mt-1" style="font-weight:500">{{ item.namapasien }} - ({{ item.jeniskelamin
                                  == 'Perempuan' ? 'P' : 'L' }})</h3>
                              </div>
                              <div class="column is-4 pb-0">
                                <label style="font-weight: 400;color: darkgray;font-size: 12px;">Ruangan Asal</label>
                                <h3 class="field mt-1" style="font-weight:500">{{ item.ruanganasal }}</h3>
                              </div>
                              <div class="column pb-0">
                                <label style="font-weight: 400;color: darkgray;font-size: 12px;">No Bed</label>
                                <h3 class="field mt-1" style="font-weight:500">{{ item.reportdisplay }}</h3>
                              </div>
                              <div class="column is-2 pb-0 is-flex is-flex-direction-column">
                                <!-- <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary" circle
                                outlined raised v-tooltip.bottom="'Aksi'" @click="toggle($event, item)">
                                </VIconButton> -->
                                <VTag :color="item.statusordergizi == 0 ? 'warning' : 'primary'" :label="item.statusgizi" rounded class="mb-3" />
                                <div>
                                  <VIconButton type="button" icon="feather:printer" color="warning" outlined raised class="mr-2 is-align-self-flex-start" v-tooltip.top="'Cetak Label'" @click="cetakLabelGizi(item)" :loading="isLoading"></VIconButton>
                                  <VIconButton v-if="item.statusordergizi == 4" type="button" icon="feather:check" color="primary" outlined raised class="mr-2 is-align-self-flex-start" v-tooltip.top="'Selesai'" @click="updateStatusSelesai(item)" :loading="isLoading"></VIconButton>
                                </div>
                              </div>
                              
                             
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-4">
                                <label style="font-weight: 400;color: darkgray;font-size: 12px;">Kategori Diet - Jenis Waktu</label>
                                <h3 class="field mt-1" style="font-weight:500">{{ item.kategorydiet }} - {{ item.jeniswaktu }}</h3>
                              </div>
                              <div class="column is-4">
                                <label style="font-weight: 400;color: darkgray;font-size: 12px;">Jenis Diet</label>
                                <h3 class="field mt-1" style="font-weight:500">{{ item.arrjenisdiet && item.arrjenisdiet !== '' ? item.arrjenisdiet : '-' }}</h3>
                              </div>
                              <div class="column is-4">
                                <label style="text-align: center;">No Order</label>
                                <h3 class="field mt-1" style="font-weight:500">{{ item.noorder }}</h3>
                              </div>
                            </div>
                          </div>
                        </div>
                      </VCard>
                    </div>
  
                    <div v-else class="p-0" style="margin-top: -58px;">
                      <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                          style="max-width: 38%;margin-top: 2rem;" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                          style="max-width: 38%;margin-top: 2rem;" />
                      </div>
                      <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                        Daftar Permintaan Saat ini tidak tersedia</h3>
                    </div>
                  </div>
                </p>
              </template>

            </VTabs>
          </div>

        </div>

      </div>
    </div>
  </div>

  <VModal :open="modalDetailGizi" title="Detail Order Gizi" :noclose="true" size="big" actions="right"
    @close="modalDetailGizi = false">
    <template #content>
      <form class="modal-form">
        <div class="timeline-wrapper">
          <div class="timeline-header">

          </div>
          <div class="timeline-wrapper-inner">
            <div class="timeline-container">
              <div class="timeline-item is-unread" v-for="(items, index) in dataDetailOrder" :key="items.norec">
                <div class="date">
                  <span>{{ H.formatDateIndo(items.tglorder) }}</span>
                </div>
                <div :class="'dot is-' + listColor[index + 1]"></div>
                <div class="content-wrap is-grey">
                  <div class="content-box">
                    <div class="status"></div>
                    <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                      <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                    </VIconBox>
                    <div class="box-text" style="width: 70%">
                      <div class="meta-text">
                        <p>
                          <span>{{ items.jeniswaktu + ' -- ' + items.kategorydiet }}</span>
                        </p>
                        <table style="width: 100%; margin-top: 10px">
                          <tr>
                            <td class="font-labels" width="38%">Jenis Diet</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="60%">
                              {{ items.arrjenisdiet }}
                            </td>
                          </tr>
                          <tr>
                            <td class="font-labels" width="18%">No. Order</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="80%">{{ items.noorder }}</td>
                          </tr>
                          <tr>
                            <td class="font-labels" width="18%">Keterangan</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="80%">{{ items.keteranganlainnya }}</td>
                          </tr>
                        </table>
    
                      </div>
                      <div class="columns is-multiline is-justify-content-flex-end">
                        <div class="column is-10">
                          Menu : <b style="margin-right: 2.5rem; margin-top: -0.3rem;"> {{ items.menu ? items.menu : 'Belum dijadwalkan' }} </b>
                        </div>
                        <div class="column is-10">
                          Keterangan Menu : <b style="margin-right: 2.5rem; margin-top: -0.3rem;"> {{ items.keteranganmenu ? items.keteranganmenu : 'Belum dijadwalkan / Tidak di isi' }} </b>
                        </div>
                      </div>
                    </div>
    
                    <div class="box-end" style="width: 30%">
    
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <div class="status is-pulled-right mt-2 ml-2"></div>
                          <VTag :label="items.statusgizi" color="warning" class="is-pulled-right" rounded />
                          <!-- <VTag :label="items.statuskirim" color="warning" class="is-pulled-right" rounded /> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailGiziVerif" title="Detail Order Gizi" :noclose="true" size="big" actions="right"
    @close="modalDetailGiziVerif = false">
    <template #content>
      <form class="modal-form">
        <div class="timeline-wrapper">
          <div class="timeline-header">

          </div>
          <div class="timeline-wrapper-inner">
            <div class="timeline-container">
              <div class="timeline-item is-unread" v-for="(items, index) in dataDetailOrder" :key="items.norec">
                <div class="date">
                  <span>{{ H.formatDateIndo(items.tglorder) }}</span>
                </div>
                <div :class="'dot is-' + listColor[index + 1]"></div>
                <div class="content-wrap is-grey">
                  <div class="content-box">
                    <div class="status"></div>
                    <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                      <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                    </VIconBox>
                    <div class="box-text" style="width: 70%">
                      <div class="meta-text">
                        <p>
                          <span>{{ items.jeniswaktu + ' -- ' + items.kategorydiet }}</span>
                        </p>
                        <table style="width: 100%; margin-top: 10px">
                          <tr>
                            <td class="font-labels" width="38%">Jenis Diet</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="60%">
                              {{ items.arrjenisdiet }}
                            </td>
                          </tr>
                          <tr>
                            <td class="font-labels" width="18%">No. Order</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="80%">{{ items.noorder }}</td>
                          </tr>
                          <tr>
                            <td class="font-labels" width="18%">Keterangan</td>
                            <td class="font-labels">:</td>
                            <td class="font-values" width="80%">{{ items.keteranganlainnya }}</td>
                          </tr>
                        </table>
    
                      </div>
                      <div class="columns is-multiline is-justify-content-flex-end">
                        <div class="column is-10">
                          Menu : <b style="margin-right: 2.5rem; margin-top: -0.3rem;"> {{ items.menu ? items.menu : 'Belum dijadwalkan' }} </b>
                        </div>
                        <div class="column is-10">
                          Keterangan Menu : <b style="margin-right: 2.5rem; margin-top: -0.3rem;"> {{ items.keteranganmenu ? items.keteranganmenu : 'Belum dijadwalkan / Tidak di isi' }} </b>
                        </div>
                      </div>
                    </div>
    
                    <div class="box-end" style="width: 30%">
    
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <div class="status is-pulled-right mt-2 ml-2"></div>
                          <VTag :label="items.statusgizi" color="warning" class="is-pulled-right" rounded />
                          <!-- <VTag :label="items.statuskirim" color="warning" class="is-pulled-right" rounded /> -->
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:check" @click="verifikasi(dataDetailOrder)" :loading="isLoading" color="primary" raised>
        Verifikasi</VButton>
    </template>
  </VModal>

  <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterDate" class="is-centered" />
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

  <VModal :open="modalKirim" title="Kirim Menu Gizi" actions="right" size="big" @close="modalKirim = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                  <VCard class="is-grey">
                    <div class="columns is-multiline p-1">
                      <div class="column is-6">
                        <VField label="Nama Menu" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                          <VControl icon="feather:plus-circle" fullwidth>
                            <Multiselect mode="single" v-model="item.produk" :options="d_MenuGizi"
                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />

                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField label="Jumlah">
                          <VControl icon="feather:bookmark">
                            <VInput type="number" v-model="item.qtyproduk" placeholder="Jumlah Produk"
                              class="is-rounded" />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-1 mt-3" style="margin-top: 1rem;">
                        <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                          icon="feather:trash" @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </div>
                      <div class="column is-1 is-flex mt-3" style="margin-top: 1rem;">
                        <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                          @click="addNewItem()"> Tambah Menu
                        </VButton>
                      </div>
                    </div>
                  </VCard>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:plus" @click="tambah()" :loading="isLoading" color="primary" raised>Kirim Menu
      </VButton>
    </template>
  </VModal>

  <OverlayPanel ref="overlaypanel">
    <VIconButton type="button" icon="feather:arrow-right" color="primary" outlined raised class="mr-2"
      v-tooltip.top="'Kirim Menu'" @click="showKirim(selected)"></VIconButton>
    <VIconButton type="button" icon="feather:printer" color="warning" outlined raised class="mr-2"
      v-tooltip.top="'Cetak Label'" @click="cetakLabelGizi(selected)"></VIconButton>
  </OverlayPanel>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import MultiSelect from 'primevue/multiselect';
import { h, ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as qzService from '/@src/utils/qzTrayService'
import Dialog from 'primevue/dialog';
import moment, { isDate } from 'moment'
import ApexChart from 'vue3-apexcharts'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import OverlayPanel from 'primevue/overlaypanel';

useHead({
  title: 'Dashboard Gizi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)


var date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });

const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const modalFilter: any = ref(false)
const modalDetailGizi: any = ref(false)
const modalDetailGiziVerif: any = ref(false)
const item: any = ref({
  aktif: true,
  filterNama : '',  
  filterDate: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const listItem: any = ref([
  {
    produk: [],
    qtyproduk: [],
  }
])

const chart: any = ref({
  aktif: true
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let listColor: any = ref(Object.keys(useThemeColors()))
const modalDetailProduk = ref(false)

let ID_RUANGAN = useRoute().query.id as string

let dataStok: any = ref([])
let dataKirim: any = ref([])
let dataOrder: any = ref([])
let dataRiwayat: any = ref([])
let dataDetailOrder: any = ref([])
let dataPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_RuanganRanap: any = ref([])
let d_JenisDiet: any = ref([])
let d_JenisWaktu: any = ref([])
let d_MenuGizi: any = ref([])
let d_KategoryDiet: any = ref([])

let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let chartJK: any = ref({
  series: [],
})
let countRuangan: any = ref([])
const modalKirim: any = ref(false)
const filters = ref('')
const overlaypanel: any = ref();
const selected: any = ref({})
const order: any = ref(99)
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataStok.value
  }
  return dataStok.value.filter((item: any) => {
    return (
      item.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})
const dataSourcePasien = computed(() => {
  if (!filters.value) {
    return dataPasien.value
  }
  return dataPasien.value.filter((item: any) => {
    return item.namapasien.match(new RegExp(filters.value, 'i'))
  })
})

const route = useRoute()
isLoading.value = false

async function fetchdDropdown() {
  const response = await useApi().get(`/dashboard/dropdown-order-gizi`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_RuanganRanap.value = response.ruangan_ranap.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_JenisDiet.value = response.jenisdiet.map((e: any) => { return { name: e.jenisdiet, id: e.id } })
  d_JenisWaktu.value = response.jeniswaktu.map((e: any) => { return { label: e.jeniswaktu, value: e.id, default: e } })
  d_KategoryDiet.value = response.kategorydiet.map((e: any) => { return { label: e.kategorydiet, value: e.id, default: e } })
  d_MenuGizi.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e.id, default: e } })
}

async function fetchRiwayat() {
  let ruanganid = ''
  if (item.value.filterRuangan) { ruanganid = item.value.filterRuangan }
  let namapasien = ''
  if (item.value.filterNama) { namapasien = item.value.filterNama }
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  }
  let qsearch = ''
  if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
  // let namapasien = ''
  //   , nocm = ''
  //   , noregistrasi = ''

  // if (item.value.nama) namapasien = `&namapasien=${item.value.nama}`
  // if (item.value.noreg) noreg = `&noregistrasi=${item.value.noreg}`
  // if (item.value.nocm) nocm = `&nocm=${item.value.nocm}`
  let status = ''
  if (order.value != 99) {
    status = order.value
  }

  isLoading.value = true
  dataRiwayat.value.loading = true
  const response = await useApi().get(
    `/dashboard/riwayat-order-gizi?ruanganid=` + ruanganid
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&namapasien=' + namapasien
    + '&statusorder=' + status
    + '&qsearch=' + qsearch
    // + '&nocm=' + nocm
    // + '&noregistrasi=' + noregistrasi
  )
  isLoading.value = false
  dataRiwayat.value.loading = false
  dataRiwayat.value = response.data
}

async function fetchOrder() {
  let ruanganid = ''
  if (item.value.filterRuanganRanap) { ruanganid = item.value.filterRuanganRanap }
  let qsearch = ''
  if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
  
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59')
  }

  let status = ''
  // if (order.value != 99) {
  //   status = order.value
  // }

  isLoading.value = true
  dataOrder.value.loading = true
  const response = await useApi().get(
    `/dashboard/riwayat-order-gizi-new?ruanganid=` + ruanganid
    + '&qsearch=' + qsearch
    + '&statusorder=' + status
    + '&dari=' + dari
    + '&sampai=' + sampai,

  )
  isLoading.value = false
  dataOrder.value.loading = false
  dataOrder.value = response.data
}

const cetakLabelGizi = (e: any) => {
  // qzService.printData(`report/gizi/cetak-label?norec=${e.norec_op}&pdf=true`,'LABEL GIZI',1)
  H.printBlade(`report/gizi/cetak-label?norec=${e.norec_op}&pdf=true`)
}

const toggle = (event, e) => {
  overlaypanel.value.toggle(event);
  selected.value = e
}

function changeRuang(e: any) {
  for (let x = 0; x < d_Ruangan.value.length; x++) {
    const element = d_Ruangan.value[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  fetchOrder()
  fetchRiwayat()
}

function changeRuangOrder(e: any) {
  for (let x = 0; x < d_RuanganRanap.value.length; x++) {
    const element = d_RuanganRanap[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  fetchOrder()
}

function changeRuangRiwayat(e: any) {
  for (let x = 0; x < d_RuanganRanap.value.length; x++) {
    const element = d_RuanganRanap[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  fetchRiwayat()
}

function reload() {
  fetchRiwayat()
}
function showModalFilter() {
  modalFilter.value = true
}

if (userLogin.mapLoginUserToRuangan.length) {
  for (let i = 0; i < userLogin.mapLoginUserToRuangan.length; i++) {
    const element = userLogin.mapLoginUserToRuangan[i];
    if (element.departemen.toLowerCase().indexOf('rawat jalan') > -1) {
      item.namaruangan = element.namaruangan
      item.departemen = element.departemen
      item.id_ruangan = element.id
      item.id_departemen = element.objectdepartemenfk
      break
    }
  }
}
function showKirim(e: any) {
  clear()
  item.tglkirim = new Date()
  item.norec_op = e.norec_op
  item.norec_so = e.norec_so
  // item.norec_pd = e.norec
  item.RUANGAN_ASAL = e.objectruanganfk
  item.RUANGAN_LAST = e.objectruangantujuanfk
  item.NOREC_PD = e.norec
  modalKirim.value = true

  console.log(item.norec_so)
}

function addNewItem() {
  listItem.value.push({
    produk: [],
    qtyproduk: null,
  });
}
function removeItem(index: any) {
  listItem.value.splice(index, 1)
}
async function tambah() {
  var objSave = {
    'strukkirim':
    {
      'norec_sk': item.norec_sk ? item.norec_sk : '',
      'tglkirim': H.formatDate(item.tglkirim, 'YYYY-MM-DD HH:mm:ss'),
      'objectruanganasalfk': item.RUANGAN_ASAL,
      'objectruanganfk': item.RUANGAN_LAST,
      'noregistrasifk': item.NOREC_PD,
      'norec_op': item.norec_op,
      'norec_so' : item.norec_so,
      'details': listItem.value
    },

  }
  isLoading.value = true
  await useApi().post(
    `dashboard/save-kirim-gizi`, objSave).then((response: any) => {
      isLoading.value = false
      clear()
      fetchRiwayat()
    }, (error) => {
      isLoading.value = false
      // console.log(error)
    })
}


function filter() {
  item.isDate = false
  // fetchPasienTotal()
}


function clear() {

  item.value.id = ''
  item.value.objectdepartemenfk = ''


  modalInput.value = false
  modalKirim.value = false
}

const changeSwitch = (e: any) => {
    fetchRiwayat()
}

watch(
    () => [
        order.value
    ], () => {
        changeSwitch(order.value)
    }
)

function showModalDetail(e:any) {
  isLoading.value = true
  useApi().get('/dashboard/riwayat-order-gizi?norec_so=' + e.norec_so)
  .then((response:any) => {
      isLoading.value = false
      dataDetailOrder.value = response.data
      modalDetailGizi.value = true
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

function verifikasiOrder(e:any) {
  isLoading.value = true
  useApi().get('/dashboard/riwayat-order-gizi?norec_so=' + e.norec_so)
  .then((response:any) => {
      isLoading.value = false
      dataDetailOrder.value = response.data
      modalDetailGiziVerif.value = true
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

function editOrder(e:any) {
  if (e.statusorder != 0) {
    H.alert('info', 'Order sedang diproses / sudah selesai tidak bisa diubah')
    return
  }
  
  router.push({
    name: 'module-gizi-order-gizi',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
    }
  })
}

function verifikasi(e:any) {
  isLoading.value = true
  let json = {
    norec_so: e[0].norec_so,
    statusorder: 1
  }
  useApi().post('/dashboard/save-status-order-gizi', json)
    .then((response:any) => {
      isLoading.value = false
      fetchOrder()
      modalDetailGiziVerif.value = false
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

function updateStatusSelesai(e:any) {
  isLoading.value = true
  let json = {
    norec_so: e.norec_so,
    norec_op: e.norec_op,
    statusorder: 5
  }
  useApi().post('/dashboard/save-status-order-gizi', json)
    .then((response:any) => {
      isLoading.value = false
      fetchRiwayat()
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

fetchOrder()
fetchRiwayat()
// fetchKirim()

// fetchDetail()

fetchdDropdown()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/rawat-jalan.scss';
@import '/@src/scss/custom/timeline-css';


.user-grid-v2 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    @include vuero-s-card;

    text-align: center;

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .people {
      display: flex;
      justify-content: center;
      padding: 8px 0 30px;

      .v-avatar {
        margin: 0 4px;
      }
    }

    .buttons {
      display: flex;
      justify-content: space-between;

      .button {
        width: calc(50% - 4px);
        color: var(--light-text);

        &:hover,
        &:focus {
          border-color: var(--fade-grey-dark-4);
          color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
      }
    }
  }

  .grid-item-wrap {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    transition: all 0.3s; // transition-all test

    .grid-item-head {
      background: #fafafa;
      border-radius: var(--radius-large) 6px 0 0;
      padding: 20px;

      .flex-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;

        .meta {
          span {
            display: flex;

            &:first-child {
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.95rem;
              color: var(--dark-text);
            }

            &:nth-child(2) {
              font-size: 0.9rem;
              color: white;
            }
          }
        }

        .status-icon {
          height: 28px;
          width: 28px;
          min-width: 28px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey-dark-3);
          display: flex;
          align-items: center;
          justify-content: center;

          &.is-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
          }

          &.is-warning {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
          }

          &.is-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
          }

          i {
            font-size: 8px;
          }
        }
      }

      .buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0;

        .button,
        .v-button {
          width: calc(50% - 4px);
          color: var(--light-text);
          margin-bottom: 0;

          &:hover,
          &:focus {
            border-color: var(--fade-grey-dark-4);
            color: var(--primary);
            box-shadow: var(--light-box-shadow);
          }
        }
      }
    }

    .grid-item {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .user-grid-v2 {
    .grid-item-wrap {
      border-color: var(--dark-sidebar-light-12);

      .grid-item-head {
        background: var(--dark-sidebar-light-4);
      }
    }
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
  background: var(--success) !important
}

.page-placeholder .placeholder-content h3 {
  font-size: 1rem;
  font-weight: 600;
  font-family: var(--font-alt);
  color: var(--dark-text);
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .user-grid-v2 {
    .columns {
      display: flex;

      .column {
        min-width: 50% !important;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .user-grid-v2 {
    .columns {
      .column {
        min-width: 33.3% !important;
      }
    }
  }
}
.search-menu-gizi {
    height: 56px !important;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: white;
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

    .search-location-gizi,
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
            background-color: transparent;
            border: none;
        }

        svg {
            margin-right: 0.5rem;
            width: 18px;
            color: var(--primary);
            flex-shrink: 0;
        }
    }

    .search-button-gizi {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px !important;
        border: none;
        font-weight: 500;
        font-family: var(--font);
        padding: 0 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: white;
        cursor: pointer;
        margin-left: auto;
    }
}

.search-widget {
    flex: 1;
    display: inline-block;
    width: 100%;
    padding: 10px;
    background-color: var(--white);
    border-radius: 16px;
    border: 1px solid var(--fade-grey-dark-3);
    transition: all 0.3s;
}
.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item[data-v-4206d2a0]::before {
  content: none;
}

</style>
