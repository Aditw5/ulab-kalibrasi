
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
                <h3 style="color:white"><i class="fas fa-home"></i> Vendor Gizi</h3>
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
            <VTabs slider selected="Menu" :tabs="[
              { label: 'Menu', value: 'Menu' },
              { label: 'Daftar Order Gizi', value: 'Riwayat' },
              { label: 'Laporan', value: 'Laporan' },
            ]">

              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'Menu'">
                  <div class="list-view list-view-v3">
                    <div class="search-menu-gizi mb-2">
                      <div class="search-location-gizi" style="width: 100%">
                          <i class="iconify" data-icon="feather:search"></i>
                          <input type="text" placeholder="Cari Menu ..." v-model="item.searchMenu" v-on:keyup.enter="fetchDataMenu()" />
                      </div>
                      <VButton raised class="search-button-gizi" @click="fetchDataMenu(order)"
                          :loading="isLoading"> Cari Data
                      </VButton>
                    </div>
                    <VCard classq="mb-2">
                      <VButton v-tooltip.bottom.left="'Tambah Menu Baru'" label="Bottom Left" color="primary" circle icon="fa fa-plus" @click="showModalTambahMenu()"> Menu </VButton>
                      <VButton v-tooltip.bottom.left="'Jadwal Makanan'" label="Bottom Left" color="primary" circle icon="fa fa-calendar" class="ml-3" @click="showModalBuatJadwal()"> Jadwalkan Makanan </VButton>
                    </VCard>
                    <!-- <VPlaceholderPage :class="[dataSourceMenu.length !== 0 && 'is-hidden']"
                      title="Data Menu Tidak Ditemukan."
                      subtitle="Silakan Input Menu baru" larger>
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                      </template>
                    </VPlaceholderPage> -->
                    <div v-if="dataSourceMenu.loading">
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
                    <div v-else class="list-view-inner" style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                      <div v-if="dataSourceMenu.length > 0">
                        <TransitionGroup name="list-complete" tag="div">
                          <div v-for="(items, i) in dataSourceMenu" :key="i" class="list-view-item">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]" :initials="items.number" />
                              <div class="meta-left">
                                <h3> 
                                  {{ items.kategorydiet }} | {{ items.satuangizi}} | {{ items.detailkelas.map(kelas => kelas.namakelas).join(', ') }}
                                </h3>
                                <span>
                                    <i class="fas fa-utensils"></i>
                                    <span class="ml-2">{{ items.menu }}</span>
                                </span>
                                <div>
                                  <span style="font-weight: bold;">Vendor :
                                      {{ items.vendorgizi ? items.vendorgizi : '-' }}
                                  </span>
                                </div>
                                <span>
                                  <i class="far fa-clock"></i>
                                  <span class="ml-2">{{ items.tglinput }}</span>
                              </span>
                              </div>
                              <div class="meta-right">
                                  <VIconButton v-tooltip.bottom.left="'Ubah'" label="Bottom Left" color="warning" circle icon="pi pi-pencil" @click="editMenuGizi(items)" :loading="isLoading" style="margin-right: 15px;" />
                                  <VIconButton v-tooltip.bottom.left="'Hapus'" label="Bottom Left" color="danger" circle icon="pi pi-trash" @click="deleteMenu(items)" style="margin-right: 15px;" :loading="isLoading" />
                              </div>
                            </div>
                          </div>
                        </TransitionGroup>
                      </div>
                      <div v-else>
                        <VPlaceholderPage :class="[dataSourceMenu.length !== 0 && 'is-hidden']"
                          title="Data Menu Tidak Ditemukan."
                          subtitle="Silakan Input Menu baru" larger>
                          <template #image>
                            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                          </template>
                        </VPlaceholderPage>
                      </div>
                    </div>
                </div>
                </p>
                <p v-if="activeValue === 'Riwayat'">
                  <div class="list-view list-menu-v3">
                    <div class="search-menu-gizi mb-2">
                      <div class="search-location-gizi" style="width: 100%">
                          <i class="iconify" data-icon="feather:search"></i>
                          <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK" v-model="item.searchMenuRiwayat" v-on:keyup.enter="fetchDataRiwayat(order)" />
                      </div>
                      <VButton raised class="search-button-gizi" @click="fetchDataRiwayat(order)"
                          :loading="isLoading"> Cari Data
                      </VButton>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-8">
                        <VCard class="text-center pt-0 pb-0 mt-0">
                          <VRadio v-model="order" value="Semua" label="Semua" name="outlined_radio"
                              color="danger" />
                          <VRadio v-model="order" value="Belum Diproses" label="Belum Diproses" name="outlined_radio"
                              color="warning" />
                          <VRadio v-model="order" value="Proses" label="Proses" name="outlined_radio"
                              color="info" />
                          <VRadio v-model="order" value="Dikirim" label="Dikirim" name="outlined_radio"
                              color="success" />
                          <VRadio v-model="order" value="Selesai" label="Selesai" name="outlined_radio"
                              color="primary" />
                        </VCard>
                      </div>
                      <div class="column is-4">
                        <VDatePicker v-model="item.filterTglRiwayat" is-range color="pink" trim-weeks>
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
                      <div v-if="order == 'Semua'" class="column is-12">
                        <div v-if="dataOrderAll.loading">
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
                          <div class="list-view list-view-v3">
                            <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                              <TransitionGroup name="list-complete" tag="div">
                                <Vcard>
                                  <div v-if="dataOrderAll.length > 0" v-for="item in dataOrderAll" :key="item.id" class="list-view-item">
                                    <div class="list-view-item-inner">
                                        <div class="meta-left">
                                          <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                        </div>
                                        <div class="meta-left">
                                          <h3>
                                            {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                          </h3>
                                          <div class="mt-2">
                                            <span>
                                              <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                              {{ item.tglregistrasi }}
                                            </span>
                                            <span class="ml-3">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                              {{ item.noregistrasi }}
                                            </span>
                                            <span class="ml-3">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                              {{ item.noorder }}
                                            </span>
                                          </div>
                                          <div class="mt-1">
                                            <span style="font-weight: 700;">
                                              <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                              {{ item.namaruangan }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                            </span>
                                          </div>
                                        </div>
                                        <div class="mate-left pr-3 pl-3">
                                          <VTag :color="item.statusorder == 0 ? 'warning' : 'primary'" :label="item.status" rounded />
                                          <div>
                                            <span>Jenis Waktu:</span>
                                          </div>
                                          <div v-for="itemDetail in item.detail" :key="itemDetail.noorderfk">
                                            <div>
                                              <span>{{ itemDetail.jeniswaktu }}</span> <span>({{ itemDetail.statusordergizi != 'Selesai' ? 'Belum' : itemDetail.statusordergizi }})</span>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="meta-right">
                                          <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                            color="info" raised circle class="mr-2" :loading="isLoading">
                                          </VIconButton>
                                          <VIconButton v-if="item.status != 'Selesai'" v-tooltip.bottom.left="'Kirim'" icon="feather:send" @click="kirimOrder(item)"
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
                      </div>
                      <div v-if="order == 'Belum Diproses'" class="column is-12">
                        <VTabs v-model:selected="selectedTabBelumProses" slider selected="Pagi" :tabs="[
                          { label: 'Pagi', value: 'Pagi' },
                          { label: 'Siang', value: 'Siang' },
                          { label: 'Sore / Malam', value: 'Sore' },
                        ]">
                          <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Pagi'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="columns is-multiline is-align-items-center">
                                  <div class="column is-2">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="item.checkAllBelumProses" label="Pilih Semua"
                                        color="info" @change="checkedAllBelumProses(item.checkAllBelumProses)" :value="item.checkAllBelumProses" />
                                    </VControl>
                                  </div>
                                  <div class="column">
                                    <VIconButton v-tooltip.bottom.right="'Verifikasi'" icon="feather:check" color="primary" raised
                                      circle class="mr-2" @click="verifikasiProses()">
                                    </VIconButton>
                                  </div>
                                </div>
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VControl raw subcontrol>
                                                <VCheckbox v-model="modelCheckBelumProses" :value="item.norec_op" color="info"
                                                  square :class="modelCheckBelumProses == true ? 'is-solid' : ''" />
                                              </VControl>
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Siang'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="columns is-multiline is-align-items-center">
                                  <div class="column is-2">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="item.checkAllBelumProses" label="Pilih Semua"
                                        color="info" @change="checkedAllBelumProses(item.checkAllBelumProses)" :value="item.checkAllBelumProses" />
                                    </VControl>
                                  </div>
                                  <div class="column">
                                    <VIconButton v-tooltip.bottom.right="'Verifikasi'" icon="feather:check" color="primary" raised
                                      circle class="mr-2" @click="verifikasiProses()">
                                    </VIconButton>
                                  </div>
                                </div>
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VControl raw subcontrol>
                                                <VCheckbox v-model="modelCheckBelumProses" :value="item.norec_op" color="info"
                                                  square :class="modelCheckBelumProses == true ? 'is-solid' : ''" />
                                              </VControl>
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Sore'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="columns is-multiline is-align-items-center">
                                  <div class="column is-2">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="item.checkAllBelumProses" label="Pilih Semua"
                                        color="info" @change="checkedAllBelumProses(item.checkAllBelumProses)" :value="item.checkAllBelumProses" />
                                    </VControl>
                                  </div>
                                  <div class="column">
                                    <VIconButton v-tooltip.bottom.right="'Verifikasi'" icon="feather:check" color="primary" raised
                                      circle class="mr-2" @click="verifikasiProses()">
                                    </VIconButton>
                                  </div>
                                </div>
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VControl raw subcontrol>
                                                <VCheckbox v-model="modelCheckBelumProses" :value="item.norec_op" color="info"
                                                  square :class="modelCheckBelumProses == true ? 'is-solid' : ''" />
                                              </VControl>
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                          </template>
                        </VTabs>
                      </div>
                      <div v-if="order == 'Proses'" class="column is-12">
                        <VTabs v-model:selected="selectedTabBelumProses" slider selected="Pagi" :tabs="[
                          { label: 'Pagi', value: 'Pagi' },
                          { label: 'Siang', value: 'Siang' },
                          { label: 'Sore / Malam', value: 'Sore' },
                        ]">
                          <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Pagi'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="columns is-multiline is-align-items-center">
                                  <div class="column is-2">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="item.checkAllBelumProses" label="Pilih Semua"
                                        color="info" @change="checkedAllBelumProses(item.checkAllBelumProses)" :value="item.checkAllBelumProses" />
                                    </VControl>
                                  </div>
                                  <div class="column">
                                    <VIconButton v-tooltip.bottom.right="'Verifikasi'" icon="feather:check" color="primary" raised
                                      circle class="mr-2" @click="verifikasiProses()">
                                    </VIconButton>
                                  </div>
                                </div>
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VControl raw subcontrol>
                                                <VCheckbox v-model="modelCheckBelumProses" :value="item.norec_op" color="info"
                                                  square :class="modelCheckBelumProses == true ? 'is-solid' : ''" />
                                              </VControl>
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Siang'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="columns is-multiline is-align-items-center">
                                  <div class="column is-2">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="item.checkAllBelumProses" label="Pilih Semua"
                                        color="info" @change="checkedAllBelumProses(item.checkAllBelumProses)" :value="item.checkAllBelumProses" />
                                    </VControl>
                                  </div>
                                  <div class="column">
                                    <VIconButton v-tooltip.bottom.right="'Verifikasi'" icon="feather:check" color="primary" raised
                                      circle class="mr-2" @click="verifikasiProses()">
                                    </VIconButton>
                                  </div>
                                </div>
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VControl raw subcontrol>
                                                <VCheckbox v-model="modelCheckBelumProses" :value="item.norec_op" color="info"
                                                  square :class="modelCheckBelumProses == true ? 'is-solid' : ''" />
                                              </VControl>
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Sore'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="columns is-multiline is-align-items-center">
                                  <div class="column is-2">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="item.checkAllBelumProses" label="Pilih Semua"
                                        color="info" @change="checkedAllBelumProses(item.checkAllBelumProses)" :value="item.checkAllBelumProses" />
                                    </VControl>
                                  </div>
                                  <div class="column">
                                    <VIconButton v-tooltip.bottom.right="'Verifikasi'" icon="feather:check" color="primary" raised
                                      circle class="mr-2" @click="verifikasiProses()">
                                    </VIconButton>
                                  </div>
                                </div>
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VControl raw subcontrol>
                                                <VCheckbox v-model="modelCheckBelumProses" :value="item.norec_op" color="info"
                                                  square :class="modelCheckBelumProses == true ? 'is-solid' : ''" />
                                              </VControl>
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                          </template>
                        </VTabs>
                      </div>
                      <div v-if="order == 'Dikirim'" class="column is-12">
                        <VTabs v-model:selected="selectedTabBelumProses" slider selected="Pagi" :tabs="[
                          { label: 'Pagi', value: 'Pagi' },
                          { label: 'Siang', value: 'Siang' },
                          { label: 'Sore / Malam', value: 'Sore' },
                        ]">
                          <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Pagi'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Siang'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Sore'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                          </template>
                        </VTabs>
                      </div>
                      <div v-if="order == 'Selesai'" class="column is-12">
                        <VTabs v-model:selected="selectedTabBelumProses" slider selected="Pagi" :tabs="[
                          { label: 'Pagi', value: 'Pagi' },
                          { label: 'Siang', value: 'Siang' },
                          { label: 'Sore / Malam', value: 'Sore' },
                        ]">
                          <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Pagi'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Siang'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                            <p v-if="activeValue === 'Sore'">
                              <div v-if="dataOrderBelumProses.loading">
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
                                <div class="list-view list-view-v3">
                                  <div class="list-view-inner" style="max-height:500px;overflow: auto;">
                                    <TransitionGroup name="list-complete" tag="div">
                                      <Vcard>
                                        <div v-if="dataOrderBelumProses.length > 0" v-for="item in dataOrderBelumProses" :key="item.id" class="list-view-item">
                                          <div class="list-view-item-inner">
                                            <div class="meta-left is-flex is-align-items-center">
                                              <VAvatar size="medium" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                                            </div>
                                            <div class="meta-left">
                                              <h3>
                                                {{ item.namapasien }} - ({{ item.jeniskelamin == 'Perempuan' ? 'P' : 'L' }})
                                              </h3>
                                              <div class="mt-2">
                                                <span>
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                  {{ item.tglregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                                  {{ item.noregistrasi }}
                                                </span>
                                                <span class="ml-3">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                                  {{ item.noorder }}
                                                </span>
                                              </div>
                                              <div class="mt-1">
                                                <span style="font-weight: 700;">
                                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                                  {{ item.ruanganasal }} - {{ item.reportdisplay }} - {{ item.namakelas }}
                                                </span>
                                              </div>
                                            </div>
                                            <div class="mate-left pr-3 pl-3">
                                              <VTag :color="item.statusorder == 5 ? 'primary' : 'warning'" :label="item.jeniswaktu" rounded />
                                            </div>
                                            <div class="meta-right">
                                              <VIconButton v-tooltip.bottom.left="'Detail Order Gizi'" icon="feather:info" @click="showModalDetail(item)"
                                                color="info" raised circle class="mr-2" :loading="isLoading">
                                              </VIconButton>
                                              <VIconButton v-if="item.status != 'Cetak Label'" v-tooltip.bottom.left="'Print'" icon="feather:printer" @click="cetakLabelGizi(item)"
                                                color="warning" raised circle class="mr-2" :loading="isLoading">
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
                            </p>
                          </template>
                        </VTabs>
                      </div>
                    </div>
                  </div>
                </p>
                <p v-else-if="activeValue === 'Laporan'">
                  <div class="column is-multiline">
                    <div class="column is-12">
                      <VCard>
                        <div class="columns is-multiline">
                          <div class="column is-4">
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
                              <Multiselect mode="single" v-model="item.filterRuanganOrder" :options="d_RuanganRanap" placeholder="Pilih ruangan" :searchable="true" autocomplete="off" @select="changeRuangLaporan(item.filterRuanganOrder)" />
                            </VControl>
                          </div>
                          <div class="column is-1">
                            <VButton @click="fetchLaporan()" :loading="isLoading" color="primary" raised>Cari Data</VButton>
                          </div>
                        </div>
                      </VCard>
                    </div>
                    <div class="column is-12">
                      <VCard>
                        <h3 class="title is-5 mb-2">Laporan Gizi</h3>
                        <div class="column" v-if="isLoading">
                          <VPlaceloadWrap v-for="data in 25">
                            <VPlaceload class="mx-2 mb-3" />
                            <VPlaceload class="mx-2" />
                          </VPlaceloadWrap>
                        </div>
                        <div class="column" v-else>
                          <VPlaceholderPage v-if="dataSourceLaporan.length == 0" title="Data Tidak di Temukan."
                            subtitle="Silakan filter pencarian di tanggal lain" larger>
                            <template #image>
                              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
                              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                            </template>
                          </VPlaceholderPage>

                          <div v-else>
                            <DataTable :value="dataSourceLaporan" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="10"
                              :rowsPerPageOptions="[5, 10, 25]" scrollable
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

                              <Column field="no" header="#" frozen>
                                <template #body="slotProps">
                                  {{ slotProps.index + 1 }}
                                </template>
                              </Column>
                              <Column field="tglorder" header="Tanggal" :sortable="true" style="min-width: 200px" frozen>
                                <template #body="slotProps">
                                  <span>{{ H.formatDateToLocalString(slotProps.data.tglorder) }}</span>
                                </template>
                              </Column>
                              <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
                              <Column field="namaruangan" header="Ruangan / Kamar" :sortable="true" style="min-width: 100px">
                                <template #body="slotProps">
                                  <span>{{ slotProps.data.namaruangan }} / {{ slotProps.data.namakamar }}</span>
                                </template>
                              </Column>
                              <Column field="menu" header="Menu" :sortable="true" style="min-width: 200px"></Column>
                              <Column field="statusordergizi" header="Status" :sortable="true" style="min-width: 200px"></Column>
                            </DataTable>
                          </div>
                        </div>
                      </VCard>
                    </div>
                  </div>
                </p>
              </template>
            </VTabs>
          </div>
          <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit" :total-items="totalData" :max-links-displayed="5">
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
    </div>

    <!-- Modal -->
    <VModal :open="modalTambahMenu" title="Tambah Menu" :noclose="true" size="large" actions="right"
      @close="modalTambahMenu = false">
      <template #content>
        <div class="columns is-multiline">
          <div class="column is-6">
            <VField label="Tanggal" class="is-rounded-select">
              <VControl class="prime-auto ">
                <div class="is-rounded is-rounded-select">
                  <Calendar v-model="item.tglinputmenu" selectionMode="single" :manualInput="true" class="w-100 is-rounded" showTime :showIcon ="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                </div>
              </VControl>
            </VField>
            <VField label="Vendor" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.vendormenu" :options="d_VendorGizi" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"  />
              </VControl>
            </VField>
            <VField label="Jenis Makanan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.kategoridietmenu" :options="d_KategoryDiet" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"  />
              </VControl>
            </VField>
            <VField label="Satuan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.satuangizimenu" :options="d_SatuanGizi" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"  />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Kelas" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth :loading="isLoadChange" class="prime-auto-select">
                  <MultiSelect v-model="item.kelasmenu" display="chip" class="w-100 is-rounded" :options="d_Kelas" optionLabel="label" optionValue="value" filter placeholder="Pilih Data" :maxSelectedLabels="3" />
              </VControl>
            </VField>
            <div class="columns is-multiline">
              <div v-for="(item, index) in listItemMenu" :key="index" class="column is-12">
                <div class="field is-grouped is-align-items-center">
                  <div class="control is-expanded">
                    <VField label="Menu">
                      <VControl icon="feather:list">
                        <VInput type="text" v-model="item.menu" placeholder="Menu" class="is-rounded" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="control mt-5">
                    <VIconButton v-if="listItemMenu.length > 1" type="button" color="danger" class="is-rounded" rounded raised icon="fas fa-trash" @click="removeMenu(index)"></VIconButton>
                  </div>
                  <div class="control mt-5">
                    <VIconButton type="button" color="success" class="is-rounded" rounded raised icon="fas fa-plus" @click="addMenu(item)"></VIconButton>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </template>
      <template #action>
        <VButton icon="feather:save" @click="saveMenu()" :loading="isLoading" color="primary" raised>
          Simpan</VButton>
      </template>
    </VModal>

    <VModal :open="modalPenjadwalanMenu" title="Penjadwalan Makanan" :noclose="true" size="big" actions="right"
      @close="modalPenjadwalanMenu = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-5">
              <VField label="Tanggal" class="is-rounded-select">
                <VControl class="prime-auto ">
                  <div class="is-rounded is-rounded-select">
                    <Calendar v-model="item.tgljadwal" selectionMode="single" :manualInput="true" class="w-100 is-rounded" showTime :showIcon ="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                  </div>
                </VControl>
              </VField>
              <VField label="Waktu" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:list" fullwidth :loading="isLoadChange" class="prime-auto-select">
                  <MultiSelect v-model="item.waktujadwal" display="chip" class="w-100 is-rounded" :options="d_JenisWaktu" optionLabel="label" optionValue="value" filter placeholder="Pilih Data" :maxSelectedLabels="3" />
                </VControl>
              </VField>
              <VField label="Jenis Makanan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:list" fullwidth>
                  <Multiselect mode="single" v-model="item.kategoridietjadwal" :options="d_KategoryDiet" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"  />
                </VControl>
              </VField>
              <VField label="Menu" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                <VControl icon="feather:list" fullwidth :loading="isLoadChange" class="prime-auto-select">
                  <MultiSelect v-model="item.menujadwal" display="chip" class="w-100 is-rounded" :options="d_MenuGizi" optionLabel="label" optionValue="value" filter :selectAll="false" :showToggleAll="false" placeholder="Pilih Data" :maxSelectedLabels="3" />
                </VControl>
              </VField>
              <VButton icon="feather:save" @click="savePenjadwalan()" :loading="isLoading" color="primary" raised>
                Simpan
              </VButton>
            </div>
            <div class="column is-7">
              <div class="columns is-multiline">
                <div class="column is-12">
                  <div class="columns is-multiline">
                    <div class="column is-6">
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
                    <div class="column is-1">
                      <VIconButton type="button" color="success" class="is-rounded" rounded raised icon="fas fa-search" @click="fetchJadwal()" :loading="isLoading"></VIconButton>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="user-grid user-grid-v2">
                    <DataTable :value="dataSourcePenjadwalan" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                      :loading="isLoading"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
        
                      <Column field="no" header="No" style="text-align: center;"></Column>
                      <Column field="tgljadwal" header="Tanggal Jadwal" :sortable="true">
                        <template #body="slotProps">
                          <span>{{ H.formatDateToLocalString(slotProps.data.tgljadwal) }}</span>
                        </template>
                      </Column>
                      <Column field="jeniswaktu" header="Waktu" :sortable="true"></Column>
                      <Column field="kategorydiet" header="Jenis Makanan" :sortable="true"></Column>
                      <Column field="menu" header="Menu" :sortable="true">
                        <template #body="slotProps">
                          <span v-if="Array.isArray(slotProps.data.menu)">
                            <VTag v-for="(item, index) in slotProps.data.menu" 
                                  :key="index" 
                                  color="primary" 
                                  rounded 
                                  class="ml-2 mb-1">
                              {{ item.menu }}
                            </VTag>
                          </span>
                          <span v-else>
                            <VTag color="primary" rounded>{{ slotProps.data.menu }}</VTag>
                          </span>
                        </template>
                      </Column>
                      <Column :exportable="false" header="Aksi" style="text-align: center;">
                        <template #body="slotProps">
                          <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                            v-tooltip.top="'Hapus'" @click="disabledMenu(slotProps.data)" :loading="isLoading">
                          </VIconButton>
                        </template>
                      </Column>
                    </DataTable>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </template>
    </VModal>

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

    <VModal :open="modalVerifikasiOrder" title="Verifikasi Order" :noclose="true" size="large" actions="right"
      @close="modalVerifikasiOrder = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12 text-center">
              <p>
                Apakah Anda yakin memproses orderan sebanyak ({{ dataOrderBelumProsesCheked.length }}) ? rincian :
              </p>
            </div>
            <div class="column is-12">
              <div class="user-grid user-grid-v2">
                <DataTable :value="dataOrderBelumProsesCheked" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                  :loading="isLoading"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
    
                  <Column header="No" style="text-align: center;">
                    <template #body="slotProps">
                      {{ slotProps.index + 1 }}
                    </template>
                  </Column>
                  <Column field="namapasien" header="Nama Pasien" :sortable="true"></Column>
                  <Column field="arrjenisdiet" header="jenis Diet" :sortable="true"></Column>
                  <Column field="kategorydiet" header="Kategori Diet" :sortable="true"></Column>
                  <Column field="jeniswaktu" header="Jenis Waktu" :sortable="true"></Column>
                  <Column field="menu" header="Menu" :sortable="true">
                    <template #body="slotProps">
                      <span v-if="Array.isArray(slotProps.data.menu)">
                        <VTag v-for="(item, index) in slotProps.data.menu" 
                              :key="index" 
                              color="primary" 
                              rounded 
                              class="ml-2 mb-1">
                          {{ item.menu }}
                        </VTag>
                      </span>
                      <span v-else>
                        <VTag color="primary" rounded>{{ slotProps.data.menu }}</VTag>
                      </span>
                    </template>
                  </Column>
                </DataTable>
              </div>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:check" @click="lanjutkanProses(dataOrderBelumProsesCheked)" :loading="isLoading" color="primary" raised>
          Lanjutkan</VButton>
      </template>
    </VModal>
    <!-- Modal -->

  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import MultiSelect from 'primevue/multiselect'
import { h, ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as qzService from '/@src/utils/qzTrayService'
import Dialog from 'primevue/dialog'
import moment, { isDate } from 'moment'
import ApexChart from 'vue3-apexcharts'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import OverlayPanel from 'primevue/overlaypanel'
import Calendar from 'primevue/calendar'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from "primevue/useconfirm"
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as XLSX from "xlsx";

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
const modalTambahMenu: any = ref(false)
const modalPenjadwalanMenu: any = ref(false)
const modalVerifikasiOrder: any = ref(false)
const item: any = ref({
  aktif: true,
  filterNama : '',  
  filterDate: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  filterTglRiwayat: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const listItemMenu: any = ref([
  {
    menu: null,
  }
])
const order = ref<string>('Semua')

const chart: any = ref({
  aktif: true
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})

const modalDetailProduk = ref(false)

let ID_RUANGAN = useRoute().query.id as string

let dataStok: any = ref([])
let dataKirim: any = ref([])
let dataRiwayat: any = ref([])
let dataMenu: any = ref([])
let dataPenjadwalan: any = ref([])
let dataSourceLaporan: any = ref([])
const remakeData: any = ref([])
let d_Ruangan: any = ref([])
let d_RuanganRanap: any = ref([])
let d_JenisDiet: any = ref([])
let d_JenisWaktu: any = ref([])
let d_MenuGizi: any = ref([])
let d_KategoryDiet: any = ref([])
let d_SatuanGizi: any = ref([])
let d_VendorGizi: any = ref([])
let d_Kelas: any = ref([])

let listColor: any = ref(Object.keys(useThemeColors()))
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let dataOrderAll: any = ref([])
let dataOrderBelumProses: any = ref([])
let dataOrderBelumProsesCheked: any = ref([])
let dataDetailOrder: any = ref([])
let chartJK: any = ref({
  series: [],
})
let countRuangan: any = ref([])
const modalKirim: any = ref(false)
const modalDetailGizi: any = ref(false)
const filters = ref('')
const overlaypanel: any = ref();
const selected: any = ref({})
const totalData: any = ref(0)
const confirm = useConfirm();
const selectedTabBelumProses = ref('Pagi')
const modelCheckBelumProses:any = ref([])

const dataSourceMenu = computed(() => {
  if (!filters.value) {
    return dataMenu.value
  }
  return dataMenu.value.filter((item: any) => {
    return item.match(new RegExp(filters.value, 'i'))
  })
})
const dataSourceRiwayat = computed(() => {
  if (!filters.value) {
    return dataRiwayat.value
  }
  return dataRiwayat.value.filter((item: any) => {
    return item.match(new RegExp(filters.value, 'i'))
  })
})
const dataSourcePenjadwalan = computed(() => {
  if (!filters.value) {
        return dataPenjadwalan.value
    }

    return dataPenjadwalan.value.filter((items: any) => {
        return (
            items.match(new RegExp(filters.value, 'i'))
        )
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
  // d_MenuGizi.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e.id, default: e } })
  d_VendorGizi.value = response.vendorgizi.map((e: any) => { return { label: e.vendorgizi, value: e.id, default: e } })
  d_SatuanGizi.value = response.satuangizi.map((e: any) => { return { label: e.satuangizi, value: e.id, default: e } })
  d_Kelas.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
}

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
        fetchDataMenu()
      }
    }
)
watch(
    () => currentPage.value.limit,
    (newValue, oldValue) => {
      if (newValue != oldValue) {
        fetchDataMenu()
      }
    }
)

async function fetchDataMenu() {
  try {
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    let page: any = route.query.page ? route.query.page : 1
    let search = item.value.searchMenu ? item.value.searchMenu : ''

    dataSourceMenu.value.loading = true
    totalData.value = 0
    let response = await useApi().get(`/gizi/get-daftar-menu-gizi?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&search=${search}`)
    dataSourceMenu.value.loading = false
    totalData.value = response.total
    let nomor = 1
    for (const element of response.data) {
      element.number = nomor++
    }
    dataMenu.value = response.data
    d_MenuGizi.value = response.data.map((e: any) => { return { label: e.menu, value: e.id, default: e } })

  } catch (err) {
    console.error(err)
    isLoading.value = false
    dataSourceMenu.value.loading = false
  } finally {
    isLoading.value = false
    dataSourceMenu.value.loading = false
  }
}

function showModalTambahMenu() {
  item.value.tglinputmenu = new Date()
  modalTambahMenu.value = true
}

function showModalBuatJadwal() {
  item.value.tgljadwal = new Date()
  item.value.kategoridietjadwal = undefined
  item.value.menujadwal = undefined
  item.value.waktujadwal = undefined
  dataPenjadwalan.value = []
  modalPenjadwalanMenu.value = true
  fetchJadwal()
}

function addMenu(data:any) {
  if (data.menu == null) {
    H.alert('warning', 'Menu masih kosong harap isi terlebih dahulu')
    return
  }
  listItemMenu.value.push({
    menu: null
  })
}

async function fetchJadwal() {
  try {
    let dari  = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD') + ' 00:00'
    let sampai  = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD') + ' 23:59'

    isLoading.value = true
    let response = await useApi().get(`gizi/get-daftar-jadwal-menu-gizi?tglAwal=${dari}&tglAkhir=${sampai}`)
    isLoading.value = false

    let nomor = 1
    for (const element of response.data) {
      element.no = nomor++
    }
    dataPenjadwalan.value = response.data
    
  } catch (err) {
    console.error(err)
    isLoading.value = false
  } finally {
    isLoading.value = false
  }
}

function editMenuGizi(data:any) {
  item.value.idmenu = data.id
  item.value.tglinputmenu = data.tglinput
  item.value.kategoridietmenu = data.kategoridietfk
  item.value.vendormenu = data.vendorgizifk
  item.value.satuangizimenu = data.satuangizifk
  let arrKelas = []
  for (const element of data.detailkelas) {
    arrKelas.push(element.kelasfk)
  }
  item.value.kelasmenu = arrKelas
  let arrMenu = data.menu.split(' + ')
  listItemMenu.value = []
  for (const element of arrMenu) {
    listItemMenu.value.push({
      menu: element
    })
  }
  modalTambahMenu.value = true
}

function deleteMenu(data:any) {
  if (data.id == undefined) {
    H.alert('warning', ' Terjadi kesalahan, coba lagi')
    return
  }

  let json = {
    id: data.id
  }
  isLoading.value = true
  useApi().post('gizi/delete-menu-gizi', json)
    .then((response: any) => {
      isLoading.value = false
      fetchDataMenu()
    })
    .catch((error:any) => {
      console.error(error)
      isLoading.value = false
    })
}


function removeMenu(index:any) {
  listItemMenu.value.splice(index, 1)
}

async function saveMenu() {
  if (listItemMenu.value.length == 0) {
    H.alert('warning', 'Menu belum diisi sama sekali, harap isi terlebih dahulu')
    return
  } else if (listItemMenu.value.length == 1 && listItemMenu.value[0].menu == null) {
    H.alert('warning', 'Menu belum diisi sama sekali, harap isi terlebih dahulu')
    return
  }

  if (item.value.tglinputmenu == undefined) {
    H.alert('warning', 'Tanggal masih kosong harap isi terlebih dahulu')
    return
  }

  if (item.value.vendormenu == undefined) {
    H.alert('warning', 'Vendor masih kosong harap isi terlebih dahulu')
    return
  }

  if (item.value.kategoridietmenu == undefined) {
    H.alert('warning', 'Jenis makanan masih kosong harap isi terlebih dahulu')
    return
  }

  if (item.value.satuangizimenu == undefined) {
    H.alert('warning', 'Satuan masih kosong harap isi terlebih dahulu')
    return
  }

  if (item.value.kelasmenu == undefined || item.value.kelasmenu.length == 0) {
    H.alert('warning', 'Kelas masih kosong harap isi terlebih dahulu')
    return
  }

  let strMenu = ''
  for (const element of listItemMenu.value) {
    if (strMenu == '') {
      strMenu = element.menu
    } else {
      strMenu += ' + ' + element.menu
    }
  }

  let detailKelas = []
  for (const element of item.value.kelasmenu) {
    detailKelas.push({
      kelasfk: element,
    })
  }

  let jsonSave = {
    id: item.value.idmenu ? item.value.idmenu : '',
    tglinput: item.value.tglinputmenu,
    vendorfk: item.value.vendormenu,
    kategoridietfk: item.value.kategoridietmenu,
    satuangizifk: item.value.satuangizimenu,
    menu: strMenu,
    detailkelas: detailKelas
  }

  isLoading.value = true
  await useApi().post('gizi/save-menu-gizi', jsonSave)
    .then((response:any) => {
      isLoading.value = false
      modalTambahMenu.value = false
      clearInputMenu()
      fetchDataMenu()
    })
    .catch((error:any) => {
      isLoading.value = false
      console.error(error)
    })
}

async function savePenjadwalan() {
  if (item.value.tgljadwal == undefined) {
    H.alert('warning', 'Tanggal penjadwalan masih kosong harap isi terlebih dahulu')
    return
  }

  if (item.value.waktujadwal == undefined || item.value.waktujadwal.length == 0) {
    H.alert('warning', 'Waktu penjadwalan masih kosong harap isi terlebih duhulu')
    return
  }

  if (item.value.kategoridietjadwal == undefined) {
    H.alert('warning', 'Jenis makanan masih kosong harap isi terlebih dahulu')
    return
  }

  if (item.value.menujadwal == undefined || item.value.menujadwal.length == 0) {
    H.alert('warning', 'Menu masih kosong harap isi terlebih dahulu')
    return
  }

  let arrMenu = []
  for (const element of item.value.menujadwal) {
    arrMenu.push({
      menugizifk: element
    })
  }

  let listJadwal = []
  for (const element of item.value.waktujadwal) {
    listJadwal.push({
      id: '',
      tgljadwal: H.formatDate(item.value.tgljadwal, 'YYYY-MM-DD HH:mm'),
      jeniswaktufk: element,
      kategorydietfk: item.value.kategoridietjadwal,
      listmenu: arrMenu
    })
  }
  
  let jsonSave = {
    dataMap: listJadwal
  }

  isLoading.value = true
  await useApi().post('gizi/save-map-jadwal-menu-gizi', jsonSave)
  .then((response:any) => {
      isLoading.value = false
      fetchJadwal()
      delete item.value.waktujadwal
      delete item.value.kategoridietjadwal
      delete item.value.menujadwal
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

async function disabledMenu(data:any) {
  if (data.id == undefined) {
    H.alert('warning', 'Terjadi kesalahan, coba lagi')
    return
  }

  let json = {
    id: data.id
  }

  isLoading.value = true
  await useApi().post('gizi/delete-map-jadwal-menu-gizi', json)
    .then((response:any) => {
      isLoading.value = false
      fetchJadwal()
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

function clearInputMenu() {
  item.value.tglinputmenu = undefined
  item.value.vendormenu = undefined
  item.value.kategoridietmenu = undefined
  item.value.satuangizimenu = undefined
  item.value.kelasmenu = undefined
  listItemMenu.value = [
    {
      menu: null
    }
  ]
}

function fetchDataRiwayat(e:any) {
  let jenisWaktu = 1
  if (selectedTabBelumProses.value == 'Siang') {
    jenisWaktu = 2
  } else if (selectedTabBelumProses.value == 'Sore') {
    jenisWaktu = 3
  }

  if (e == 'Semua') {
    fetchOrderAll()
  } else if (e == 'Belum Diproses') {
    fetchOrderBelumProses(jenisWaktu, 2)
  } else if (e == 'Proses') {
    fetchOrderBelumProses(jenisWaktu, 3)
  } else if (e == 'Dikirim') {
    fetchOrderBelumProses(jenisWaktu, 4)
  } else if (e == 'Selesai') {
    fetchOrderBelumProses(jenisWaktu, 5)
  }
}

async function fetchOrderAll() {
  let ruanganid = ''
  if (item.value.filterRuanganRanap) { ruanganid = item.value.filterRuanganRanap }
  let qsearch = ''
  if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
  
  let dari = ''
  if (item.value.filterTglRiwayat.start) {
    dari = H.formatDate(item.value.filterTglRiwayat.start, 'YYYY-MM-DD 00:00')
  }
  let sampai = ''
  if (item.value.filterTglRiwayat.end) {
    sampai = H.formatDate(item.value.filterTglRiwayat.end, 'YYYY-MM-DD 23:59')
  }

  let status = ''
  // if (order.value != 99) {
  //   status = order.value
  // }

  isLoading.value = true
  dataOrderAll.value.loading = true
  const response = await useApi().get(
    `/dashboard/riwayat-order-gizi-new?ruanganid=` + ruanganid
    + '&qsearch=' + qsearch
    + '&statusorder=' + status
    + '&dari=' + dari
    + '&sampai=' + sampai,

  )
  isLoading.value = false
  dataOrderAll.value.loading = false
  dataOrderAll.value = response.data
}

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

function kirimOrder(e:any) {
  if (e.status == 'Selesai') {
    H.alert('info', 'Pesanan gisi sudah selesai')
    return
  }

  order.value = 'Belum Diproses'
}

watch(order,
  (newVal:string, oldVal:string) => {
    if (oldVal != newVal) {
      selectedTabBelumProses.value = 'Pagi'
      if (newVal == 'Belum Diproses') {
        fetchOrderBelumProses(1, 2)
      } else if (newVal == 'Proses') {
        fetchOrderBelumProses(1, 3)
      } else if (newVal == 'Dikirim') {
        fetchOrderBelumProses(1, 4)
      } else if (newVal == 'Selesai') {
        fetchOrderBelumProses(1, 5)
      }
    }
  }
)

watch(selectedTabBelumProses, 
  (newVal, oldVal) => {
  if (oldVal != newVal) {
    modelCheckBelumProses.value = []
    item.value.checkAllBelumProses = false
    let jenisWaktu = 1
    if (newVal == 'Siang') {
      jenisWaktu = 2
    } else if (newVal == 'Sore') {
      jenisWaktu = 3
    }
    
    let statusOrder = 2 
    if (order.value == 'Belum Diproses') {
      statusOrder = 2
    } else if (order.value == 'Proses') {
      statusOrder = 3
    } else if (order.value == 'Dikirim') {
      statusOrder = 4
    } else if (order.value == 'Selesai') {
      statusOrder = 5
    }
    fetchOrderBelumProses(jenisWaktu, statusOrder)
  }
})

function fetchOrderBelumProses(jeniswaktu:any, statusorder:any) {
  dataOrderBelumProses.value = []

  let dari = ''
  if (item.value.filterTglRiwayat.start) {
    dari = H.formatDate(item.value.filterTglRiwayat.start, 'YYYY-MM-DD 00:00')
  }
  let sampai = ''
  if (item.value.filterTglRiwayat.end) {
    sampai = H.formatDate(item.value.filterTglRiwayat.end, 'YYYY-MM-DD 23:59')
  }

  isLoading.value = true
  dataOrderBelumProses.value.loading = true
  useApi().get(
    '/dashboard/riwayat-order-gizi?dari=' + dari
    + '&sampai=' + sampai
    + '&statusorder=' + statusorder 
    + '&jeniswaktuid=' + jeniswaktu
  )
  .then((response:any) => {
    isLoading.value = false
    dataOrderBelumProses.value.loading = false
    dataOrderBelumProses.value = response.data
  })
  .catch((err:any) => {
    isLoading.value = false
    dataOrderBelumProses.value.loading = false
  })
}

const cetakLabelGizi = (e: any) => {
  // qzService.printData(`report/gizi/cetak-label?norec=${e.norec_op}&pdf=true`,'LABEL GIZI',1)
  H.printBlade(`report/gizi/cetak-label?norec=${e.norec_op}&pdf=true`)
}

function checkedAllBelumProses(e:any) {
  if (e) {
    modelCheckBelumProses.value = dataOrderBelumProses.value.map((item) => item.norec_op)
  } else {
    modelCheckBelumProses.value = []
  }
}

watch(modelCheckBelumProses, (val) => {
  item.value.checkAllBelumProses = val.length === dataOrderBelumProses.value.length
})

function verifikasiProses() {
  dataOrderBelumProsesCheked.value = []
  for (const element of dataOrderBelumProses.value) {
    for (const element2 of modelCheckBelumProses.value) {
      if (element.norec_op == element2) {
        dataOrderBelumProsesCheked.value.push(element)
      } 
    }
  }
  
  if (dataOrderBelumProsesCheked.value.length == 0) {
    H.alert('warning', 'Cheklis terlebih dahulu data yang ingin di verifikasi')
    return
  }
  modalVerifikasiOrder.value = true
}

function lanjutkanProses(e:any) {
  if (e.length == 0) {
    H.alert('warning', 'Cheklis terlebih dahulu data yang ingin di verifikasi')
    return
  }

  for (const element of e) {
    let json = {
      norec_so: element.norec_so,
      norec_op: element.norec_op,
      statusorder: parseInt(element.statusordergizi) + 1
    }

    isLoading.value = true
    useApi().post('/dashboard/save-status-order-gizi', json)
    .then((response:any) => {
      isLoading.value = false
      fetchOrderBelumProses(element.objectjeniswaktufk, element.statusordergizi)
      modalVerifikasiOrder.value = false
    })
    .catch((error:any) => {
      isLoading.value = false
    })
  }
}

function fetchLaporan() {
  let dari  = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD') + ' 00:00'
  let sampai  = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD') + ' 23:59'

  let ruangan = ''
  if (item.value.filterRuanganOrder) {
    ruangan = item.value.filterRuanganOrder
  }
  
  isLoading.value = true
  useApi().get('/dashboard/get-laporan-order-gizi?tglAwal=' + dari + '&tglAkhir=' + sampai + '&ruanganId=' + ruangan)
  .then((response:any) => {
    isLoading.value = false
    dataSourceLaporan.value = response.data
  })
  .catch((error:any) => {
    isLoading.value = false
  })
}

function changeRuangLaporan(e:any) {
  fetchLaporan()
}

function exportExcel() {
  remakeData.value = dataSourceLaporan.value.map((e:any, index:number) => {
    return {
      No: index + 1,
      TglOrder: H.formatDateToLocalString(e.tglorder),
      NamaPasien: e.namapasien,
      Ruangan: e.namaruangan,
      Kamar: e.namakamar,
      Menu: e.menu,
      Status: e.statusordergizi
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

function saveAsExcelFile(buffer: any, fileName: string) {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus();
}

fetchOrderAll()
fetchDataMenu()
fetchdDropdown()
fetchLaporan()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
//@import '/@src/scss/module/dashboard/rawat-jalan.scss';
@import '/@src/scss/module/dashboard/bedah.scss';
@import '/@src/scss/module/dashboard/laboratorium.scss';


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

</style>
