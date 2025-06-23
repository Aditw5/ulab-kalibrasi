<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="illustration-header-2 large-screen">
            <div class="header-image">
              <img src="/@src/assets/illustrations/dashboards/lifestyle/Picture1.png" alt=""
                style="max-width:84%; margin-left: 2rem; margin-bottom: 1rem;" />
            </div>
            <div class="header-meta">
              <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                Registrasi</h3>
              <p>
                Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="business-dashboard hr-dashboard">
      <div class="columns">
        <div class="column is-12">
          <VCard radius="rounded">
            <div class="columns is-multiline">
              <div class="column is-4 mt-5">
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
              <div class="column is-4" v-if="activeTab == 0">
                <VField label="Cari Registrasi Mitra">
                  <VControl icon="fas fa-id-card" fullwidth>
                    <VInput type="text" placeholder="Nama Perusahaan, No Pendaftaran" autocomplete="off"
                      v-model="item.search" class="is-rounded" v-on:keyup.enter="cari()" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4" v-if="activeTab == 1">
                <VField label="Cari Registrasi Alat">
                  <VControl icon="fas fa-id-card" fullwidth>
                    <VInput type="text" placeholder="Nama Perusahaan, No Pendaftaran" autocomplete="off"
                      v-model="item.qsearch" class="is-rounded" v-on:keyup.enter="fetchAlatKalibrasi(orderAlat)" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-1 mt-5" v-if="activeTab == 0">
                <VIconButton type="button" color="success" class="is-rounded" rounded raised icon="fas fa-search"
                  @click="cari()" :loading="isLoading">
                </VIconButton>
              </div>
              <div class="column is-1 mt-5" v-if="activeTab == 1">
                <VIconButton type="button" color="success" class="is-rounded" rounded raised icon="fas fa-search"
                  @click="fetchAlatKalibrasi(orderAlat)" :loading="isLoading">
                </VIconButton>
              </div>
              <div class="column is-3 mt-5">
                <VField>
                  <RouterLink :to="{ name: 'module-registrasi-mitra-lama', }">
                    <VIconButton class="ml-3 is-pulled-right" type="button" color="info" rounded circle raised
                      icon="fas fa-users" v-tooltip.bubble="'Mitra Lama'">
                    </VIconButton>
                  </RouterLink>
                  <VButton class="ml-3 is-pulled-right" type="button" color="info" rounded raised
                    icon="fas fa-long-arrow-alt-right" @click="mitraBaru()">
                    Mitra Baru
                  </VButton>
                </VField>
              </div>
            </div>
          </VCard>
          <div class="column is-12" style="margin-top: 2rem;">
            <p>
              <VCard>
                <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar Mitra</span>
                      <Badge :value="ds_MITRA.length" v-if="ds_MITRA.length > 0" severity="danger" class="ml-2" />
                    </template>
                    <div v-if="activeTab == 0">
                      <div class="user-grid user-grid-v2">
                        <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                          class="my-6" v-if="ds_MITRA.length === 0">
                          <template #image>
                            <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                              alt="" />
                          </template>
                        </VPlaceholderPage>
                        <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else>
                          <div v-for="(item, key) in ds_MITRA" :key="key" class="column is-4">
                            <div class="grid-item-wrap is-clickable">
                              <!-- @click="clickCard(item)" -->
                              <div class="grid-item-head is-registrasi">
                                <div class="flex-head">

                                  <div class="meta">
                                    <span>
                                      {{
                                        H.formatDateIndoSimple(item.tglregistrasi)
                                      }}
                                    </span>
                                  </div>
                                  <VTag v-if="!item.iskaji" color="danger" rounded>Belum Kaji</VTag>
                                  <VTag v-if="item.iskaji && item.statusorder != 1" color="warning" rounded>Sudah Kaji
                                  </VTag>
                                  <VTag v-if="item.statusorder == 1" color="info" rounded>Sudah Diverif Asman</VTag>
                                </div>
                              </div>
                              <div class="flex-head" style=" display: flex; justify-content: space-between;">
                                <VDropdown icon="feather:more-vertical" spaced left>
                                  <template #content>
                                    <a role="menuitem" @click="batalRegis(item)" class="dropdown-item is-media">
                                      <div class="icon">
                                        <i aria-hidden="true" class="lnil lnil-trash"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Batal Registrasi</span>
                                      </div>
                                    </a>
                                    <a v-if="item.iskaji !== null" role="menuitem" @click="cetakTandaTerima(item)"
                                      class="dropdown-item is-media">
                                      <div class="icon">
                                        <i aria-hidden="true" class="lnil lnil-printer"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Cetak Tanda Terima </span>
                                      </div>
                                    </a>
                                    <a v-if="item.iskaji !== null && item.statusorder == 1 && item.tanggalkonfirmasipendaftaran != null" role="menuitem"
                                      @click="cetakPermintaanKalibrasi(item)" class="dropdown-item is-media">
                                      <div class="icon">
                                        <i aria-hidden="true" class="lnil lnil-printer"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Cetak Permintaan Kalibrasi dan Kontrak </span>
                                      </div>
                                    </a>
                                    <a v-if="item.iskaji !== null && item.statusorder == 1 && item.tanggalkonfirmasipendaftaran == null" role="menuitem" @click="konfirmaasiPendaftaran(item)"
                                      class="dropdown-item is-media">
                                      <div class="icon">
                                        <i aria-hidden="true" class="lnil lnil-checkmark"></i>
                                      </div>
                                      <div class="meta">
                                        <span>Konfirmasi Permintaan Kalibrasi dan kontrak</span>
                                      </div>
                                    </a>

                                  </template>
                                </VDropdown>
                              </div>
                              <div class="grid-item">
                                <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')"
                                  size="big" />
                                <h3 class="dark-inverted">{{ item.namaperusahaan }}</h3>
                                <h3 class="dark-inverted">{{ item.nopendaftaran }}</h3>
                                <!-- <p>{{ item.nocm }}</p> -->
                                <p>Email : {{ item.email }}</p>
                                <p>No HP : {{ item.nohp }}</p>
                                <div class="buttons mt-4" style="display: flex; justify-content: center;">
                                  <VIconButton v-if="item.statusorder != 1" v-tooltip.bottom.left="'Kaji Ulang'" label="Bottom center" color="info"
                                    outlined circle icon="pi pi-arrow-right" @click="kajiUlang(item)" />
                                  <VIconButton v-else  v-tooltip.bottom.left="'Kaji Ulang'" label="Bottom center" color="info"
                                    outlined circle icon="pi pi-arrow-right" disabled />
                                </div>
                              </div>
                            </div>
                          </div>
                        </TransitionGroup>
                      </div>
                    </div>
                  </TabPanel>
                  <TabPanel>
                    <template #header>
                      <i class="fas fa-users mr-2" aria-hidden="true"></i>
                      <span>Daftar Alat</span>
                      <Badge :value="dataAlatKalibrasi.length" v-if="dataAlatKalibrasi.length > 0" severity="danger"
                        class="ml-2" />
                    </template>
                    <div v-if="activeTab == 1">
                      <div class="list-view list-view-v3">
                        <VCard class="text-center pt-0 pb-0 mt-0">
                          <VRadio v-model="orderAlat" value="0" label="Belum Setujui Manager" name="outlined_radio"
                            color="success" />
                          <VRadio v-model="orderAlat" value="2" label="Sudah Setujui Manager" name="outlined_radio"
                            color="info" />
                        </VCard>
                        <VPlaceholderPage :class="[dataAlatKalibrasi.length !== 0 && 'is-hidden']"
                          title="Tidak Ada Alat Hari Ini." subtitle="Silakan Pilih Tanggal" larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                              alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                              alt="" />
                          </template>
                        </VPlaceholderPage>
                        <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                          <div name="list-complete" tag="div">
                            <div v-for="(item, rowIndex) in dataAlatKalibrasi" :key="rowIndex">
                              <div v-if="rowGroupMetadata[item.lingkupkalibrasi].index === rowIndex">
                                <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                  item.lingkupkalibrasi }}</span>
                                <Badge :value="rowGroupMetadata[item.lingkupkalibrasi].size"
                                  v-if="rowGroupMetadata[item.lingkupkalibrasi].size > 0" class="ml-2 mt-2-min" />
                              </div>
                              <div class="list-view-item ">
                                <div class="list-view-item-inner">
                                  <VAvatar size="small" picture="/images/avatars/svg/propinsi.svg" color="primary"
                                    bordered />
                                  <div class="meta-left">
                                    <h3>
                                      {{ item.namaproduk }}
                                      <VTag v-if="item.iskaji != true" label="Belum Kaji Ulang" :color="'danger'"
                                        class="ml-2" />
                                    </h3>
                                    <span>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:home"></i>
                                      <span>{{ item.namaperusahaan }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                      <span>{{ item.tglverifasman }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                      <span>{{ item.nopendaftaran }}</span>
                                      <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                      <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                      <span>{{ item.noorderalat }}</span>

                                    </span>
                                    <div>
                                      <VTag v-if="item.statusPengerjaan == null && item.iskaji == true"
                                        :label="'Durasi Kalibrasi : ' + item.durasikalbrasi" :color="'warning'"
                                        class="ml-2" />
                                      <VTag v-if="item.statusPengerjaan != null" :label="item.statusPengerjaan"
                                        :color="item.statusColor" class="ml-2" />
                                      <VTag
                                        v-if="item.setujuilembarkerjapenyelia != null && item.setujuilembarkerjapenyelia == true"
                                        :label="'Sertifikat Disetujui Penyelia'" :color="'primary'" class="ml-2" />
                                      <VTag
                                        v-if="item.setujuilembarkerjaasman != null && item.setujuilembarkerjaasman == true"
                                        :label="'Sertifikat Disetujui Asman'" :color="'primary'" class="ml-2" />
                                      <VTag
                                        v-if="item.setujuilembarkerjamanager != null || item.setujuilembarkerjamanager == true"
                                        :label="'Sertifikat Disetujui Manager'" :color="'primary'" class="ml-2" />
                                    </div>
                                    <div>
                                      <span style="font-weight: bold;">Penyelia Teknik
                                        :
                                        {{ item.penyeliateknik ?? '-' }}
                                      </span>
                                    </div>
                                    <div>
                                      <span style="font-weight: bold;">Pelaksana
                                        Teknik :
                                        {{ item.pelaksanateknik ?? '-' }}
                                      </span>
                                    </div>
                                  </div>
                                  <div class="meta-right flex justify-center items-center">
                                    <div class="buttons">
                                      <VIconButton
                                        v-if="item.setujuilembarkerjaasman != null && item.setujuilembarkerjaasman == true"
                                        v-tooltip.bottom.left="'Cetak Sertifikat'" icon="feather:printer"
                                        @click="cetakSertifikatLembarKerja(item)" color="info" raised circle
                                        class="mr-2">
                                      </VIconButton>
                                      <VIconButton
                                        v-if="item.setujuilembarkerjapenyelia != null && item.setujuilembarkerjapenyelia == true && (item.setujuilembarkerjaasman == null || item.setujuilembarkerjaasman == false)"
                                        color="info" circle icon="fas fa-pager" outlined raised
                                        @click="lembarKerja(item)" v-tooltip.bottom.left="'Lembar Kerja'" />
                                      <VIconButton v-tooltip.bottom.left="'Aktivitas'" icon="feather:activity"
                                        @click="detailOrder(item)" color="info" raised circle class="mr-2">
                                      </VIconButton>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>
                </TabView>
              </VCard>
            </p>
          </div>
        </div>

      </div>
      <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
        :total-items="ds_MITRA.total" :max-links-displayed="5">
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
                  </select>
                </div>
              </VControl>
            </VField>
          </VFlex>
        </template>
      </VFlexPagination>
    </div>
  </div>
  <VModal :open="modalRiwayat" noclose size="big" actions="right" @close="modalRiwayat = false, clear()"
    cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12 p-0">
              <div class="block-header">
                <div class="left column is-6 p-0">
                  <div class="current-user">
                    <h3>{{ item.namaproduk }}</h3>
                  </div>
                </div>
                <div class="left column is-6 p-0">
                  <div>
                    <div>
                      <h4 class="block-heading">Merk</h4>
                      <p class="block-hext">{{ item.namamerk }}</p>
                      <h4 class="block-heading">Tipe</h4>
                      <p class="block-hext">{{ item.namatipe }}</p>
                    </div>
                  </div>
                </div>
                <div class="center column is-6 p-0">
                  <div>
                    <div>
                      <h4 class="block-heading">S/N</h4>
                      <p class="block-hext">{{ item.namaserialnumber }}</p>
                      <h4 class="block-heading">Durasi</h4>
                      <p class="block-hext">{{ item.durasikalbrasi }}</p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <Fieldset legend="Data Alat" :toggleable="true">
          <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataDeatilOrder">
            <div class="columns is-multiline">
              <div class="column is-2" style="margin-top: 27px;">
                <VPlaceload class="mx-2" />
              </div>
              <div class="column">
                <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
              </div>

            </div>
          </div>
          <div class="timeline-wrapper" v-else>
            <div class="timeline-wrapper-inner">
              <div class="timeline-container">
                <div class="timeline-item is-unread" v-for="(item, index) in timelineItems" :key="index">
                  <div class="date">
                    <span>{{ H.formatDateIndo(item.date) }}</span>
                  </div>
                  <div :class="'dot is-' + listColor[index + 1]"></div>
                  <div class="content-wrap is-grey">
                    <div class="content-box">
                      <div class="status"></div>
                      <div class="box-text" style="width:70%">
                        <div class="meta-text">
                          <p>
                            <span>
                              {{ item.type }} : {{ item.nama }}
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
    </template>
    <template #action>
    </template>
  </VModal>
  <VModal :open="modalBatalRegis" title="Batal Registrasi" size="medium" actions="right"
    @close="modalBatalRegis = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-6">
          <VField>
            <VLabel class="required-field">Tanggal Batal</VLabel>
            <VDatePicker v-model="item.tanggalpembatalan" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
        <div class="column is-6">
          <VField>
            <VLabel class="required-field">Nama Peerusahaan</VLabel>
            <VControl icon="feather:map-pin">
              <VInput type="text" v-model="item.perusahaan" placeholder="Tempat Lahir" class="is-rounded_Z" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <span style="margin-bottom:1rem;font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Alasan
            Pembatalan
          </span>

          <VField>
            <VControl>
              <VTextarea class="textarea is-rounded" v-model="item.alasanpembatalan" rows="4"
                placeholder="Alasan Pembatalan" autocomplete="off" autocapitalize="off" spellcheck="true" />
            </VControl>
          </VField>
        </div>

      </div>
    </template>
    <template #action>
      <VButton icon="feather:plus" color="primary" @click="saveBatalRegis" :loading="isLoading" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalKonfirmasiPendaftaran" title="Konfirmasi Pendaftaran" size="big" actions="right"
    @close="modalKonfirmasiPendaftaran = false" cancelLabel="Tutup">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-6">
          <VField>
            <VLabel class="required-field">Tanggal Konfirmasi</VLabel>
            <VDatePicker v-model="item.tanggalkonfirmasi" mode="dateTime" style="width: 100%" trim-weeks
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar" fullwidth>
                    <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" disabled />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </VField>
        </div>
        <div class="column is-6">
          <VField>
            <VLabel class="required-field">Nama Perusahaan</VLabel>
            <VControl icon="feather:map-pin">
              <VInput type="text" v-model="item.perusahaan" placeholder="Tempat Lahir" class="is-rounded_Z" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <Fieldset legend="Data Alat" :toggleable="true">
            <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataOrder">
              <div class="columns is-multiline">
                <div class="column is-2" style="margin-top: 27px;">
                  <VPlaceload class="mx-2" />
                </div>
                <div class="column">
                  <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
                </div>

              </div>
            </div>
            <div class="timeline-wrapper" v-else>
              <div class="timeline-wrapper-inner">
                <div class="timeline-container">
                  <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan" :key="items.norec">
                    <div :class="'dot is-' + listColor[index + 1]"></div>

                    <div class="content-wrap is-grey">
                      <div class="content-box">
                        <div class="status"></div>
                        <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                          <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                        </VIconBox>
                        <div class="box-text" style="width:70%">
                          <div class="meta-text">
                            <p>
                              <span>{{ items.namaproduk }}</span>
                            </p>
                            <table class="tb-order">
                              <tr>
                                <td>Lingkup</td>
                                <td>:</td>
                                <td>{{ items.lingkupkalibrasi }} </td>
                              </tr>
                              <tr>
                                <td>Lokasi</td>
                                <td>:</td>
                                <td>{{ items.lokasi }} </td>
                              </tr>
                              <tr>
                                <td>Penyelias Teknik </td>
                                <td>:</td>
                                <td class="font-values">{{ items.penyeliateknik }}</td>
                              </tr>
                              <tr>
                                <td>Pelaksana Teknik</td>
                                <td>:</td>
                                <td>{{ items.pelaksanateknik }} </td>
                              </tr>
                              <tr>
                                <td>Durasi</td>
                                <td>:</td>
                                <td>
                                  <VTag v-if="items.durasikalbrasi" color="warning" rounded> {{ items.durasikalbrasi }}
                                  </VTag>
                                </td>
                              </tr>
                            </table>

                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-9" style="text-align: center;">

        </div>
        <div class="column is-3" style="text-align: center;">
          <p class="label-ppap" style="font-weight: bold;">Penanggung Jawab</p>
          <TandaTangan :elemenID="'signaturePenanggungJawab'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-2">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.namapenanggungjawab" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:plus" color="primary" @click="saveKonfirmasiPendaftaran" :loading="isLoading" raised>Simpan
      </VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import * as qzService from '/@src/utils/qzTrayService'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
// import TandaTangan from '../emr/profile-pasien/page-emr-plugins/tanda-tangan.vue'
import TandaTangan from '../registrasi/tanda-tangan.vue'

useHead({
  title: 'Dashboard Registrasi ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

let chartJK: any = ref({
  series: [],
})
let listColor: any = ref(Object.keys(useThemeColors()))
let isLoading: any = ref(false)
let btnLoadSimpan: any = ref(false)
let ds_MITRA: any = ref([])
isLoading.value = false
let dataAlatKalibrasi: any = ref([])
const isCetakResep: any = ref(false)
const route = useRoute()
const router = useRouter()
const filters = ref('')
const modalFilter: any = ref(false)
const modalBatalRegis: any = ref(false)
const modalKonfirmasiPendaftaran: any = ref(false)
let isLoadDataOrder: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const checkboxKel: any = ref([])
const checkboxInst: any = ref([])
const listChecked: any = ref([])
const listCheckedInst: any = ref([])
const HIDE_FILTER: any = ref(false)
const IS_REGISTRASI: any = ref(true)
const isBtnLoading: any = ref(false)
const dataSource: any = ref([])
let isLoadDataDeatilOrder: any = ref(false)
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const selectView: any = ref()
let modalRiwayat: any = ref(false)
selectView.value = 'grid'
const activeTab = ref(0)
const currentPage: any = ref({
  limit: 6,
  rows: 50,
})
const currentPageReservation: any = ref({
  limit: 6,
  rows: 50,
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchMitra()
})
currentPageReservation.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const timelineItems = ref([])
const rowGroupMetadata = ref({})
const order: any = ref(0)
const norecPd: string = ref('');
const apd: any = reactive({})
let detailOrderLayanan: any = ref(0)
const item = reactive({
  filterDate: new Date(),
  tanggalkonfirmasi: new Date(),
  qPeriode: [
    new Date(),
    new Date()
  ],
  filterTgl: {
    start: new Date(),
    end: new Date()
  }
})
const orderAlat: any = ref(0)
const chart: any = ref({
  aktif: true
})

const openModalCetakResep = (data) => {
  isCetakResep.value = true;
  norecPd.value = data.nopendaftaran;
  // console.log(norecPd.value)
}

const tutupCetakResep = async () => {
  isCetakResep.value = false;
}
const onPageChange = () => {
  console.log("successfully", currentPage.value);
  fetchMitra()
}


const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataDokter.value
  }
  return dataDokter.value.filter((item: any) => {
    return (
      item.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})

const fetchAlatKalibrasi = async (q: any) => {
  let dari = ''
  if (item.filterTgl.start) {
    dari = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.filterTgl.end) {
    sampai = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD')
  }
  let status = ''

  let statusordermanager = ''
    , search = ''
  item.statusordermanager = q
  if (orderAlat) statusordermanager = '&statusordermanager=' + q
  if (item.qsearch) search = item.qsearch
  isLoading.value = true
  dataAlatKalibrasi.value = []
  const response = await useApi().get(
    '/registrasi/get-alat-registrasi?dari=' + dari
    + '&sampai=' + sampai
    + '&search=' + search
    + statusordermanager
  )
  isLoading.value = false
  dataAlatKalibrasi.value = response.data

  updateRowGroupMetaData();

}

const updateRowGroupMetaData = () => {
  rowGroupMetadata.value = {};

  if (dataAlatKalibrasi.value) {
    for (let i = 0; i < dataAlatKalibrasi.value.length; i++) {
      let rowData = dataAlatKalibrasi.value[i];
      let lingkupkalibrasi = rowData.lingkupkalibrasi;

      if (i == 0) {
        rowGroupMetadata.value[lingkupkalibrasi] = { index: 0, size: 1 };
      }
      else {
        let previousRowData = dataAlatKalibrasi.value[i - 1];
        let previousRowGroup = previousRowData.lingkupkalibrasi;
        if (lingkupkalibrasi === previousRowGroup) {
          rowGroupMetadata.value[lingkupkalibrasi].size++;
        }
        else {
          rowGroupMetadata.value[lingkupkalibrasi] = { index: i, size: 1 };
        }
      }
    }
  }
}


const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchMitra()
  }
  if (activeTab.value == 1) {
    fetchAlatKalibrasi(0)
  }
}

const detailOrder = async (e) => {
  console.log(e)
  modalRiwayat.value = true
  item.namaproduk = e.namaproduk
  item.namamerk = e.namamerk
  item.namatipe = e.namatipe
  item.namaserialnumber = e.namaserialnumber
  item.durasikalbrasi = e.durasikalbrasi
  isLoadDataDeatilOrder.value = true
  const response = await useApi().get(`/asman/detail-produk?norec_pd=${e.norec_detail}`)
  timelineItems.value = response.timeline
  isLoadDataDeatilOrder.value = false
}

const fetchMitra = async () => {

  ds_MITRA.value = []
  ds_MITRA.value.loading = true

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let page: any = route.query.page ? route.query.page : 1
  let search = ''
  if (item.search) search = `&search=${item.search}`
  let dari = ''
  if (item.filterTgl.start) {
    dari = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD 00:00')
  }
  let sampai = ''
  if (item.filterTgl.end) {
    sampai = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD 23:59')
  }

  isLoading.value = true
  const response = await useApi().get(`/registrasi/list-mitra-grid?page=${page}&dari=${dari}&sampai=${sampai}&limit=${limit}&offset=${offset}${search}`)
  ds_MITRA.value.loading = false
  ds_MITRA.value = response.data
  ds_MITRA.value.total = response.total
  isLoading.value = false
  dataSource.value = response.data;
}


const filter = () => {
  fetchMitra()
}

const cari = () => {
  if (IS_REGISTRASI.value) {
    fetchMitra()
  }
}

const mitraBaru = () => {
  router.push({
    name: 'module-registrasi-mitra-baru',
  })
}

const cetakTandaTerima = (e) => {
  // console.log(e)
  H.printBlade(`registrasi/cetak-tanda-terima?pdf=true&norec=${e.iddetail}`);
}

const cetakPermintaanKalibrasi = (e) => {
  // console.log(e)
  H.printBlade(`registrasi/cetak-permintaan-kalibrasi?pdf=true&norec=${e.iddetail}`);
}


const cetakLabel = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

const detailRegistrasi = async (e: any) => {
  let apd = await getAPD(e);

  router.push({
    name: 'module-registrasi-detail-registrasi',
    query: {
      noregistrasi: e.noregistrasi,
      nopendaftaran: e.nopendaftaran,
      norec_apd: apd,
    },
  })
}

const batalRegis = async (e: any) => {
  item.perusahaan = e.namaperusahaan
  item.norecregis = e.iddetail

  modalBatalRegis.value = true
}

const konfirmaasiPendaftaran = async (e: any) => {
  console.log(e)
  detailOrderLayanan.value = []
  modalKonfirmasiPendaftaran.value = true
  item.perusahaan = e.namaperusahaan
  item.namapenanggungjawab = e.namapenanggungjawab
  item.norecregis = e.iddetail
  isLoadDataOrder.value = true
  const response = await useApi().get(`/asman/layanan-verif?norec_pd=${e.iddetail}`)
  response.detail.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  isLoadDataOrder.value = false
  detailOrderLayanan.value = response.detail
}

const saveBatalRegis = async () => {
  if (!item.alasanpembatalan) { H.alert('warning', 'Alasan Pembatalan harus di isi'); return }
  let json = {
    mitraregis: {
      'norecregis': item.norecregis,
      'tanggalpembatalan': item.tanggalpembatalan,
      'alasanpembatalan': item.alasanpembatalan,
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/registrasi/save-batal-regis-mitra`, json)
    .then((response: any) => {
      isLoading.value = false
      clear()
      fetchMitra()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const saveKonfirmasiPendaftaran = async () => {
  let object: any = {}
  object.ttdPenanggungJawab = H.tandaTangan().get("signaturePenanggungJawab")

  console.log(object.ttdPenanggungJawab)

  let json = {
    mitrakonfirmasi: {
      'norecregis': item.norecregis,
      'tanggalkonfirmasi': item.tanggalkonfirmasi,
      'namapenanggungjawab': item.namapenanggungjawab,
      'datattd': object,
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/registrasi/save-konfirmasi-pendaftaran`, json)
    .then((response: any) => {
      isLoading.value = false
      modalKonfirmasiPendaftaran.value = false
      clear()
      fetchMitra()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const detail = (e: any) => {
  // console.log(e)
  item.nocmfk_baru = e.id
  item.nocm_tujuan = e.nocm
  H.alert('success', 'Data telah terpilih')
  return
};

const clear = () => {
  item.alasanpembatalan = ''
  item.tanggalpembatalan = ''
  item.tanggalkonfirmasi = ''

  modalBatalRegis.value = false
}

const kajiUlang = (e: any) => {
  console.log(e)
  router.push({
    name: 'module-registrasi-kaji-ulang',
    query: {
      nocmfk: e.id,
      norec_mitra_daftar: e.iddetail,
      tglregistrasi: e.tglregistrasi
    },

  })
}


const listButton: any = ref([
  {
    label: 'Mitra Lama ',
    icon: 'fas fa-users',
    command: () => {
      router.push({ name: 'module-registrasi-mitra-lama' });
    }
  },
  {
    label: 'Mitra Baru',
    icon: 'fas fa-user-plus',
    command: () => {
      router.push({ name: 'module-registrasi-mitra-baru' });
    }
  }
])


const changeSwitchAlat = (e: any) => {
  fetchAlatKalibrasi(e)
}

watch(
  () => [
    orderAlat.value
  ], () => {
    changeSwitchAlat(orderAlat.value)
  }
)

const cetakSertifikatLembarKerja = (e) => {
  console.log(e)
  H.printBlade(`asman/cetak-sertifikat-lembar-kerja?pdf=true&norec=${e.norec}&norec_detail=${e.norec_detail}`);
}


qzService.connect()
// fetchdDropdown()
fetchMitra()
fetchAlatKalibrasi(0)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/registrasi.scss';
@import '/@src/scss/module/dashboard/penyelia.scss';
@import '/@src/scss/module/registrasi/list-pasien';
@import '/@src/scss/module/dashboard/bedah.scss';

.c-title {
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
}

.block-heading {
  font-family: var(--font-alt);
  font-weight: 600;
  font-size: 1.1rem;
  color: var(--white);
  margin-bottom: 4px;
}
</style>
