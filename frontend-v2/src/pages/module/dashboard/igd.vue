
<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-8">
        <div class="columns is-multiline">
          <!--Header-->
          <div class="column is-12">
            <div class="illustration-header-2">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                  style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
              </div>
              <div class="header-meta">
                <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Gawat Darurat</h3>
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

          <!--Selector-->

          <!--Side Text-->


          <!--Incoming-->
          <div class="column is-12">
            <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
              <TabPanel>
                <template #header>
                  <i class="fas fa-users mr-2" aria-hidden="true"></i>
                  <span v-if="!isMutasi">Daftar Pasien</span>
                  <Badge :value="dataPasien.length" v-if="dataPasien.length > 0 && !isMutasi" severity="danger" class="ml-2" />
                  <span v-if="isMutasi">Daftar Pasien Mutasi</span>
                  <Badge :value="dataPasienMutasi.length" v-if="dataPasienMutasi.length > 0 && isMutasi" severity="danger" class="ml-2" />
                </template>

                <div v-if="activeTab == 0">
                  <div class="column is-6"
                    style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
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

                  <div class="list-view list-view-v3">
                    <div class="search-menu-igd mb-2">
                      <div class="search-location-igd" style="width: 100%" v-if="!isMutasi">
                        <i class="iconify" data-icon="feather:search"></i>
                        <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                          v-model="item.qsearch" v-on:keyup.enter="filter()" />
                      </div>
                      <div class="search-location-igd" style="width: 100%" v-if="isMutasi">
                        <i class="iconify" data-icon="feather:search"></i>
                        <input type="text" placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK Mutasi"
                          v-model="item.searchmutasi" v-on:keyup.enter="pasienMutasi()" />
                      </div>
                      <!-- <div class="search-location" v-if="isMutasiRegis">
                        <i class="iconify" data-icon="feather:activity"></i>
                        <input type="text" placeholder="No Registrasi Mutasi" v-model="item.noregistrasi" v-on:keyup.enter="pasienMutasi()"/>
                      </div>
                      <div class="search-salary" v-if="isMutasiCm">
                        <i class="iconify" data-icon="feather:clipboard"></i>
                        <input type="text" placeholder="No RM Mutasi" v-model="item.nocm" v-on:keyup.enter="pasienMutasi()"/>
                      </div>
                      <div class="search-job" v-if="isMutasi">
                        <i class="iconify" data-icon="feather:user"></i>
                        <input type="text" placeholder="Nama Pasien Mutasi" v-model="item.namapasien" v-on:keyup.enter="pasienMutasi()"/>
                      </div> -->
                      <VButton raised class="search-button-igd" v-if="!isMutasi" @click="fetchPasien()" :loading="isLoading"> Cari Data
                      </VButton>
                      <VButton raised class="search-button-igd" v-if="isMutasi" @click="pasienMutasi()" :loading="isLoading"> Cari Data
                      </VButton>
                    </div>
                    <VCard class="text-center pt-0 pb-0 mt-0">
                      <VRadio v-model="item.fStatusAntrian" value="rawat" label="Dirawat" name="outlined_radio"
                        color="success" />
                      <VRadio v-model="item.fStatusAntrian" value="true" label="Selesai Pelayanan" name="outlined_radio"
                        color="info" />
                      <VRadio v-model="item.fStatusAntrian" label="Riwayat Mutasi" name="outlined_radio" color="danger"
                        value="mutasi" />
                      <VRadio v-model="item.fStatusAntrian" value="" label="Semua" name="outlined_radio"
                        color="success" />
                    </VCard>
                    <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']" title="Tidak Ada Pasien Hari Ini."
                      subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger
                      v-if="item.fStatusAntrian != 'mutasi'">
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                      </template>
                    </VPlaceholderPage>
                    <VPlaceholderPage :class="[dataPasienMutasi.length !== 0 && 'is-hidden']"
                      title="Tidak Ada Pasien Hari Ini."
                      subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger v-else>
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                      </template>
                    </VPlaceholderPage>
                    <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                      <div name="list-complete" tag="div">
                        <div v-for="(item, rowIndex) in dataPasien" :key="rowIndex"
                          v-if="item.fStatusAntrian != 'mutasi'">
                          <div v-if="rowGroupMetadata[item.namaruangan].index === rowIndex">
                            <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                              item.namaruangan }}</span>
                            <Badge :value="rowGroupMetadata[item.namaruangan].size"
                              v-if="rowGroupMetadata[item.namaruangan].size > 0" class="ml-2 mt-2-min" />
                          </div>
                          <div class="list-view-item ">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                              <div class="meta-left">
                                <h3>
                                  {{ item.namapasien }}
                                  <i :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                    aria-hidden="true"
                                    :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                </h3>
                                <span>
                                  <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                  <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                  <span>{{ item.tglregistrasi }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                  <span>{{ item.noregistrasi }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                  <span>{{ item.namaruangan }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                  <span>{{ item.umur }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:plus"></i>
                                  <span>{{ item.kelompokpasien }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                  <span>{{ item.nocm }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                  <span>{{ item.nobpjs }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                  <span>{{ item.noidentitas }}</span>

                                </span>
                                <div>
                                  <VTag :label="item.selisihwaktu" :color="'info'" class="mr-2 mt-3-min"
                                    v-tooltip.bubble="'WAKTU DI IGD'" />
                                  <VTag :label="'SEP : ' + item.nosep" v-if="item.nosep != null" :color="'success'" />
                                  <VTag :label="item.tglpulang === null && item.tglkeluar === null ? 'Belum Pulang' : (item.tglpulang !== null ? 'Pulang' : 'Dimutasi')"   :color="item.tglpulang === null && item.tglkeluar === null ? 'info' : (item.tglpulang !== null ? 'danger' : 'warning')" class="ml-2" />
                                  <VTag :label="item.norec_perjanjian != null ? 'Sudah dibuat SKDP' : 'Belum dibuat SKDP'" :color="item.norec_perjanjian !== null ? 'info' : 'danger'" class="ml-2"/>
                                  <VTag :label="item.penanda.charAt(0).toUpperCase() + item.penanda.slice(1)" v-if="item.penanda != null" :color="'info'" class="ml-2"/>
                                  <VTag :label="item.kategoriUsia.charAt(0).toUpperCase() + item.kategoriUsia.slice(1)" v-if="item.kategoriUsia != null" :color="'warning'" class="ml-2"/>
                                  <VTag :label="item.kategoriInsiden.charAt(0).toUpperCase() + item.kategoriInsiden.slice(1)" v-if="item.kategoriInsiden != null" :color="'purple'" class="ml-2"/>
                                </div>
                                <div>
                                  <span style="font-weight: bold;">DPJP :
                                    {{ item.namalengkap ? item.namalengkap : '-' }}
                                  </span>
                                </div>
                              </div>
                              <div class="meta-right flex justify-center items-center">
                                <div class="buttons">
                                  <!-- <VIconButton v-tooltip.bottom.left="'Panggil Pasien'" label="Bottom Left"
                                      color="primary" circle icon="feather:phone" @click="panggil(item)"
                                      :loading="item.loading" /> -->
                                      <RouterLink :to="{
                                      // H.cacheHelper().set('xxx_cache_menu', undefined)
                                      name: 'module-emr-profile-pasien',
                                      query: {
                                        nocmfk: item.nocmfk,
                                        norec_pd: item.norec_pd,
                                        norec_apd: item.norec_apd,
                                      }
                                    }">
                                      <!-- <VButton color="primary" icon="fas fa-stethoscope" outlined raised
                                        @click="emr(item)">
                                        RME
                                      </VButton> -->
                                      <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                        @click="emr(item)" v-tooltip.bottom.left="'EMR'">
                                      </VIconButton>
                                    </RouterLink>
                                  <VIconButton color="primary" circle icon="pi pi-ellipsis-v" raised
                                    @click="toggleOP($event, item)" v-tooltip.bottom.left="'TINDAKAN'">
                                  </VIconButton>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        <div v-else v-for="(item, i) in dataPasienMutasi" :key="i">
                          <div class="list-view-item" v-if="dataPasienMutasi.length > 0">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                              <div class="meta-left">
                                <h3>
                                  {{ item.namapasien }}
                                  <i :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                    aria-hidden="true"
                                    :style="'color:' + (item.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                </h3>
                                <span>
                                  <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                  <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                  <span>{{ item.tglmasuk }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                  <span>{{ item.noregistrasi }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                  <span>{{ item.namaruangan }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                  <span>{{ item.nocm }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                  <span>{{ item.nobpjs }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                  <span>{{ item.noidentitas }}</span><br>
                                  <i aria-hidden="true" class="fas fa-person-booth"></i>
                                  <span class="ml-2">{{ item.Ruangan_Mutasi }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <span>{{ item.reportdisplay }}</span>
                                </span>
                              </div>
                              <div class="meta-right flex justify-center items-center">
                                <div class="buttons">
                                  <VIconButton color="danger" circle icon="fas fa-book-reader" outlined raised
                                    @click="riwayatMutasi(item)" v-tooltip.bottom.left="'Riwayat Mutasi'">
                                  </VIconButton>
                                  <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                    @click="emr(item)" v-tooltip.bottom="'EMR'">
                                  </VIconButton>
                                </div>
                              </div>
                            </div>
                          </div>
                          <div v-else>
                            <VPlaceholderPage title="Tidak Ada RIwayat Pasien Mutasi Hari Ini."
                              subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                              <template #image>
                                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png"
                                  alt="" />
                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                  alt="" />
                              </template>
                            </VPlaceholderPage>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </TabPanel>

            </TabView>

          </div>
        </div>
      </div>

      <div class="column is-4">
        <VCard>
          <VIconButton circle class="is-pulled-right" icon="feather:refresh-cw" raised bold @click="fetchDataChart()"
            :loading="isLoadingCall" />
          <div class="dashboard-card is-gauge">
            <div class="column border-custom mb-2">
              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Grafik Pelayanan Pasien
              </span>
            </div>
            <ApexChart id="apex-chart-22" :height="270" :type="'pie'" :series="chartStatus.series" :options="chartStatus">
            </ApexChart>
          </div>
        </VCard>
        <UIWidget class="search-widget" style="margin-top: 1.7rem;">
          <template #body>
            <div class="field">
              <div class="control">
                <input v-model="filters" class="input custom-text-filter" placeholder="Cari Dokter Praktek" />
                <button class="searcv-button" @click="fetchJadwalDokter()">
                  <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                </button>
              </div>
            </div>
          </template>
        </UIWidget>

        <div class="column border-custom mb-2 mt-5-min">
          <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Jadwal Dokter
          </span>
        </div>
        <div class="tile-grid tile-grid-v2">
          <VPlaceholderPage :class="[dataDokter.length !== 0 && 'is-hidden']" title="Tidak Ada Dokter Praktek Hari Ini."
            subtitle=" Silakan Pilih Ruangan untuk Melihat Jadwal Praktek Dokter" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>

          <!--Tile Grid v1-->

          <div name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
              <div v-for="item in dataDokter" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered />
                    <div class="meta">
                      <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{ item.namalengkap
                      }}</span>
                      <span>
                        <i aria-hidden="true" class="iconify" data-icon="feather:clock" style="padding-right: 3px;"></i>
                        {{ item.jammulai }} s.d {{ item.jamakhir }}</span>
                    </div>
                    <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> {{ item.hari }}
                    </VTag>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
        <div class="column border-custom mb-2" style="margin-top: 2rem;">
          <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Stok Produk
            <VIconButton v-tooltip.bottom.right="'Order Barang'" label="Bottom Right" color="primary" circle
              icon="feather:shopping-cart" style="margin-left: 13rem;" @click="orderBarang()" />
          </span>
        </div>
        <div class="tile-grid tile-grid-v2">

          <!--List Empty Search Placeholder -->
          <VPlaceholderPage :class="[dataStok.length !== 0 && 'is-hidden']" title="Tidak Ada Stok Produk."
            subtitle=" Silakan Pilih Ruangan untuk Stok Produk" larger>
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
            </template>
          </VPlaceholderPage>

          <!--Tile Grid v1-->

          <div name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div class="columns is-multiline p-2" style="max-height:300px;overflow: auto;">
              <div v-for="item in dataStok" :key="item.id" class="column is-6">
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner">
                    <VAvatar size="small" picture="/images/simrs/produk-ico.png" color="primary" bordered />
                    <div class="meta">
                      <span class="dark-inverted">{{ item.namaproduk }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <Dialog v-model:visible="modalChangeDokter" modal header="Form Ubah Dokter" :style="{ width: '25vw' }">
    <div class="column">
      <span style="font-weight: 500;">Dokter </span>
      <VField class="is-autocomplete-select pt-3">
        <VControl icon="feather:search">
          <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
            :field="'label'" placeholder="ketik Nama Dokter" />
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDokter = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDokter()" :loading="btnLoadSimpan"> Update
      </VButton>
    </template>
  </Dialog>

  <Dialog v-model:visible="modalPenanda" modal header="Form Penanda Pasien" :style="{ width: '50vw' }">
    <div class="column">
      <span style="font-weight: 500;">Penanda Pasien </span> <br>
      <div class="flex gap-4 my-2">
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenanda" inputId="bedah" name="bedah" value="bedah" />
                <label for="bedah" class="mx-2">Bedah</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenanda" inputId="non-bedah" name="non-bedah" value="non-bedah" />
                <label for="non-bedah" class="mx-2">Non Bedah</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenanda" inputId="kebidanan" name="kebidanan" value="kebidanan" />
                <label for="kebidanan" class="mx-2">Kebidanan</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenanda" inputId="psikiatri" name="psikiatri" value="psikiatri" />
                <label for="psikiatri" class="mx-2">Psikiatri</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenanda" inputId="geriatri" name="geriatri" value="geriatri" />
                <label for="geriatri" class="mx-2">Geriatri</label>
            </div>
        </div>
      <span style="font-weight: 500;">Kategori Usia </span> <br>
      <div class="flex gap-4 my-2">
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenandaUsia" inputId="bayi" name="bayi" value="bayi" />
                <label for="bayi" class="mx-2">Bayi</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenandaUsia" inputId="anak" name="anak" value="anak" />
                <label for="anak" class="mx-2">Anak</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenandaUsia" inputId="dewasa" name="dewasa" value="dewasa" />
                <label for="dewasa" class="mx-2">Dewasa</label>
            </div>
        </div>
      <span style="font-weight: 500;">Kategori Insiden </span> <br>
      <div class="flex gap-4 my-2">
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenandaInsiden" inputId="kecelakaan" name="kecelakaan" value="kecelakaan" />
                <label for="kecelakaan" class="mx-2">Kecelakaan</label>
            </div>
            <div class="flex align-items-center">
                <RadioButton v-model="item.modalPenandaInsiden" inputId="non-kecelakaan" name="non-kecelakaan" value="non-kecelakaan" />
                <label for="non-kecelakaan" class="mx-2">Non Kecelakaan</label>
            </div>
        </div>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalPenanda = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" class="ml-2" raised @click="savePenandaPasien()" :loading="btnLoadSimpan"> Update </VButton>
    </template>
  </Dialog>


  <Dialog v-model:visible="modalPesanRuangan" modal header="Form Pesan Ruangan" :style="{ width: '25vw' }">
    <div class="column">
      <span style="font-weight: 500;">Pesan Ruangan Ranap</span>
      <VField class="is-autocomplete-select pt-3">
        <VControl icon="fas fa-bed">
          <AutoComplete v-model="item.ruangPesanan" :suggestions="d_Ranap" @complete="fetchRanap($event)"
            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
            :field="'label'" placeholder="ketik Ruangan" />
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalPesanRuangan = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="savePesanRuangan()" :loading="btnLoadSimpan"> Pesan
      </VButton>
    </template>
  </Dialog>

  <OverlayPanel ref="op" appendTo="body" style="width:300px">
    <div class="columns is-multiline">
      <div class="column is-6 pt-1 pb-1">
        <VButton type="button" icon="fas fa-print" class="w-100" light circle outlined color="success" raised
          @click="cetakSEP()">
          Cetak SEP
        </VButton>
      </div>
      <div class="column is-6 pt-1 pb-1">
        <VButton type="button" icon="fas fa-pen" class="w-100" light circle outlined color="warning" raised
          @click="openModalDpjp()">
          Ubah DPJP
        </VButton>
      </div>
      <div class="column is-12 pt-1 pb-1">
        <VButton type="button" icon="lucide:tag" class="w-100" light circle outlined color="purple" raised
          @click="openModalPenanda()">
          Penanda Pasien
        </VButton>
      </div>
      <div class="column is-12 pt-1 pb-1">
        <VButton type="button" icon="fas fa-th" class="w-100" light circle outlined color="info" raised
          @click="detailRegistrasi()">
         Detail Registrasi
        </VButton>
      </div>
      <div class="column is-12 pt-1 pb-1">
        <VButton type="button" icon="fas fa-bed" class="w-100" light circle outlined color="info" raised
          @click="openModalPesanRuangan()">
         Pesan Ruangan
        </VButton>
      </div>
      <div class="column is-12 pt-1">
        <VButton type="button" icon="feather:log-in" class="w-100" circle outlined color="danger" raised
          v-if="selectedItem.tglpulang == null" @click="PulangPindah">
          Pulang atau Pindah
        </VButton>
        <VButton type="button" icon="feather:log-in" class="w-100" circle outlined color="danger" raised v-else
          @click="simpanBatalPulang">
          Batal Pulang
        </VButton>
      </div>
      <div class="column is-12 pt-1">
          <VButton type="button" icon="fas fa-user-file" class="w-100" circle outlined color="purple" raised
            @click="cetakSuratKeteranganDokter(selectedItem)">
            Cetak Surat Dokter
          </VButton>
        </div>
        <div class="column is-12 pt-1">
          <VButton type="button" icon="lnir lnir-whatsapp" class="w-100" circle outlined color="success" raised
            @click="kirimWASuratKeteranganDokter(selectedItem)">
            Kirim WA Surat Dokter
          </VButton>
        </div>
    </div>

  </OverlayPanel>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch, inject } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import moment, { isDate } from 'moment'
import ApexChart from 'vue3-apexcharts'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import MultiSelect from 'primevue/multiselect';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import RadioButton from 'primevue/radiobutton';
import OverlayPanel from 'primevue/overlaypanel';
import { state, socket } from "/@src/socket.js";
import * as qzService from '/@src/utils/qzTrayService'

useHead({
  title: 'Dashboard IGD - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const op = ref();
const activeTab = ref(0)
const date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const modalDetailKamar = ref(false)
const modalChangeDokter = ref(false)
const modalPenanda = ref(false)
const modalPesanRuangan = ref(false)
const modalInputPulang = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const isLoadingCall = ref(false)
const btnLoadSimpan = ref(false)
const showPindah = ref(false)
const showPulang = ref(false)
const showMeninggal = ref(false)
const showRujuk = ref(false)
const loadMutasi = ref(false)
const isMutasi = ref(false)
const isMutasiRegis = ref(false)
const isMutasiCm = ref(false)
const isbtnLoadPrint = ref(false)
const isbtnLoadBatalPulang = ref(false)
const route = useRoute()
const rowGroupMetadata = ref({})
const selectedItem: any = ref({})
const isLoadingPop: any = ref(false)

const d_SK: any = ref([])
const d_SP: any = ref([])
const d_KP: any = ref([])
const d_HK: any = ref([])
const d_PK: any = ref([])

const item: any = ref({
  aktif: true,
  fStatusAntrian: 'rawat',
  statuskeluar: 'Pulang',
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const chart: any = ref({
  aktif: true
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let ID_RUANGAN = useRoute().query.id as string
let dataSource: any = ref([])
let dataStok: any = ref([])
let dataObat: any = ref([])
let dataDokter: any = ref([])
let dataPasien: any = ref([])
let dataPasienMutasi: any = ref([])
let dataReservasi: any = ref([])
let d_Ruangan: any = ref([])
let d_Ranap: any = ref([])
let d_Dokter: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let chartStatus: any = ref({
  series: [],
})
let countRuangan: any = ref([])
const filters = ref('')

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/dropdown-igd/`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
}

const dropdownList = async () => {
  await useApi().get(`/rawatinap/combo-pindah`)
    .then((response: any) => {
      response.statuskeluar.forEach(element => {
        if (element.statuskeluar != 'Rujuk') {
          d_SK.value.push(element)
        }
      });
      d_KP.value = response.kondisipasien.map((e: any) => {
        return { label: e.kondisipasien, value: e.id, default: e }
      })
      d_SP.value = response.statuspulang.map((e: any) => {
        return { label: e.statuspulang, value: e.id, default: e }
      })
      d_HK.value = response.hubungankeluarga.map((e: any) => {
        return { label: e.hubungankeluarga, value: e.id, default: e }
      })
      d_PK.value = response.penyebabkematian.map((e: any) => {
        return { label: e.penyebabkematian, value: e.id, default: e }
      })
      d_Ruangan.value = response.namaruangan.map((e: any) => {
        return { label: e.namaruangan, value: e.id, default: e }
      })
    })
}

const fetchPasien = async () => {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  }
  let status = ''
  isMutasi.value = false

  let statuspanggil = ''
    , search = ''
  if (item.value.fStatusAntrian) statuspanggil = item.value.fStatusAntrian
  if (item.value.qsearch) search = item.value.qsearch
  isLoading.value = true
  dataPasien.value = []
  const response = await useApi().get(
    '/dashboard/igd-pasien?ruanganid=' + ruanganid
    + '&dari=' + dari
    + '&sampai=' + sampai
    + '&search=' + search
    + '&statuspanggil=' + statuspanggil
  )
  isLoading.value = false
  response.data.sort(compare);
  dataPasien.value = response.data

  updateRowGroupMetaData();

}

const filter = async () => {
  item.isDate = false;
  fetchPasien();
}


const pasienMutasi = async () => {
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  }
  loadMutasi.value = true
  isMutasi.value = true
  let searchmutasi = ''
  if (item.value.searchmutasi) searchmutasi = item.value.searchmutasi
  // let searchmutasi = item.value.searchmutasi ? `&searchmutasi=${item.value.searchmutasi}` : ''
  isLoading.value = true
  const response = await useApi().get(
    '/dashboard/get-riwayat-mutasi-igd?dari=' + dari
    + '&sampai=' + sampai
    + '&searchmutasi=' + searchmutasi
  )
  dataPasienMutasi.value = response
  isLoading.value = false
  loadMutasi.value = false
  // updateRowGroupMetaData();
}

const compare = (a: any, b: any) => {
  if (a.namaruangan > b.namaruangan) {
    return -1;
  }
  if (a.namaruangan < b.namaruangan) {
    return 1;
  }
  return 0;
}

const fetchJadwalDokter = async () => {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  let namaDokter = ''
  if (filters.value != undefined) {
    namaDokter = filters.value
  }
  dataDokter.value = []
  dataStok.value = []

  const response = await useApi().get(
    '/dashboard/igd-detail?ruanganid=' + ruanganid + '&namadokter=' + namaDokter + '&limit=10'
  )
  dataDokter.value = response.dokter
  dataStok.value = response.produk
}

const changeRuang = async (e: any) => {
  for (let x = 0; x < d_Ruangan.value.length; x++) {
    const element = d_Ruangan.value[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  fetchPasien()
  fetchJadwalDokter()
}
const reload = async () => {
  fetchPasien()
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
const fetchDataChart = async () => {
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  }
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  isLoadingCall.value = true
  await useApi()
    .get(`/dashboard/get-pelayanan-igd?ruanganid=${ruanganid}&dari=${dari}&sampai=${sampai}`)
    .then((response: any) => {
      isLoadingCall.value = false
      chart.value = response
      chartStatus.value = {
        series: response.chartStatus.series,
        chart: {
          height: 300,
          type: 'pie',
        },
        colors: [
          'rgb(250, 173, 66)',
          'rgb(3, 152, 226)',
          'rgb(230, 41, 100)',
          themeColors.purple,
          themeColors.orange,
        ],
        labels: response.chartStatus.labels,
        responsive: [
          {
            breakpoint: 270,
            options: {
              chart: {
                width: 300,
                toolbar: {
                  show: false,
                },
              },
              legend: {
                position: 'bottom',
              },
            },
          },
        ],
        legend: {
          position: 'bottom',
          horizontalAlign: 'center',
        },
      }
      countRuangan.value = response
      countRuangan.value.total = response.length
    })
}


const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchPasien()
  }
}
const orderBarang = () => {
  router.push({
    name: 'module-logistik-order-barang'
  })
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
const mutasiPasien = () => {
  router.push({
    name: 'module-registrasi-mutasi-pasien',
    query: {
      nocmfk: selectedItem.value.nocmfk,
      norec_pd: selectedItem.value.norec_pd,
    }
  })
}
const updateRowGroupMetaData = () => {
  rowGroupMetadata.value = {};

  if (dataPasien.value) {
    for (let i = 0; i < dataPasien.value.length; i++) {
      let rowData = dataPasien.value[i];
      let namaruangan = rowData.namaruangan;

      if (i == 0) {
        rowGroupMetadata.value[namaruangan] = { index: 0, size: 1 };
      }
      else {
        let previousRowData = dataPasien.value[i - 1];
        let previousRowGroup = previousRowData.namaruangan;
        if (namaruangan === previousRowGroup) {
          rowGroupMetadata.value[namaruangan].size++;
        }
        else {
          rowGroupMetadata.value[namaruangan] = { index: i, size: 1 };
        }
      }
    }
  }
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchRanap = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&settingdatafix=objectdepartemenfk,idDepRawatInap&limit=10`
  ).then((response) => {
    d_Ranap.value = response
  })
}

const toggleOP = (event: any, item: any) => {
  selectedItem.value = item
  op.value.toggle(event);
}

// const saveChangeDokter = async () => {
//   if (!item.value.dokterPemeriksa) {
//     H.alert('error', 'Dokter Wajib Dipilih')
//     return
//   }

//   btnLoadSimpan.value = true
//   await useApi().post('registrasi/change-dokter-dpjp', { 'norec_pd': selectedItem.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value }).then((response) => {
//     btnLoadSimpan.value = false
//     modalChangeDokter.value = false
//     fetchPasien()
//   }).catch((err) => {
//     console.log(err)
//   })
// }

const saveChangeDokter = async () => {
  if (!item.value.dokterPemeriksa) {
    H.alert('error', 'Dokter Wajib Dipilih')
    return
  }
  btnLoadSimpan.value = true
  await useApi().post('registrasi/change-dokter-dpjp', {'pasien':selectedItem.value, 'norec_pd': selectedItem.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value }).then((response) => {
    btnLoadSimpan.value = false
    modalChangeDokter.value = false
    fetchPasien()
  }).catch((err) => {
    btnLoadSimpan.value = false
    console.log(err)
  })
}

const savePenandaPasien = async () => {
  console.log(selectedItem.value)
  if (!item.value.modalPenanda) {
    H.alert('error', 'Pilih penanda terlebih dulu')
    return
  }

  const dataJson = {
    'pasien':selectedItem.value,
    'norec_pd': selectedItem.value.norec_pd,
    'penanda': item.value.modalPenanda,
    'kategoriUsia' : item.value.modalPenandaUsia,
    'kategoriInsiden' : item.value.modalPenandaInsiden,
  }
  btnLoadSimpan.value = true
  await useApi().post('registrasi/penanda-pasien', dataJson).then((response) => {
    btnLoadSimpan.value = false
    modalPenanda.value = false
    fetchPasien()
  }).catch((err) => {
    btnLoadSimpan.value = false
    console.log(err)
  })
}

const savePesanRuangan = async () => {
  if (!item.value.ruangPesanan) {
    H.alert('error', 'Ruangan Wajib Dipilih')
    return
  }
  btnLoadSimpan.value = true
  await useApi().post('registrasi/pesan-ruangan', {'pasien':selectedItem.value, 'norec_pd': selectedItem.value.norec_pd, 'ruangannextschedule': item.value.ruangPesanan.value }).then((response) => {
    btnLoadSimpan.value = false
    modalPesanRuangan.value = false
    fetchPasien()
  }).catch((err) => {
    btnLoadSimpan.value = false
    console.log(err)
  })
}

const openModalDpjp = (data: any) => {
  modalChangeDokter.value = true
  item.value.dokterPemeriksa = selectedItem.value.objectpegawaifk ? { value: selectedItem.value.objectpegawaifk, label: selectedItem.value.namalengkap } : ''
}

const openModalPenanda = (data: any) => {
  modalPenanda.value = true
  item.value.modalPenanda = selectedItem.value.penanda
  item.value.modalPenandaUsia = selectedItem.value.kategoriUsia
  item.value.modalPenandaInsiden = selectedItem.value.kategoriInsiden
}

const openModalPesanRuangan = (data: any) => {
  modalPesanRuangan.value = true
  item.value.ruangPesanan = selectedItem.value.ruangannextschedule ? { value: selectedItem.value.ruangannextschedule, label: selectedItem.value.namaruanganpesanan } : ''
}

const cetakSEP = (e: any) => {
  isbtnLoadPrint.value = true
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + selectedItem.value.noregistrasi + "&pdf=true", 'SEP', 1)
  isbtnLoadPrint.value = false
}


function simpanBatalPulang() {
  isbtnLoadBatalPulang.value = true
  useApi().post('/rawatinap/batal-pulang-pasien', { 'norec_pd': selectedItem.value.norec_pd }).then((response) => {
    reload()
  })
  isbtnLoadBatalPulang.value = false
}

const PulangPindah = async () => {
  router.push({
    name: 'module-rawat-inap-pindah-pulang',
    query: {
      nocmfk: selectedItem.value.nocmfk,
      norec_pd: selectedItem.value.norec_pd,
      departemenfk: selectedItem.value.objectdepartemenfk
    },
  })
}

const riwayatMutasi = async (e: any) => {
  router.push({
    name: 'module-rawat-inap-pindah-pulang',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      info: 'riwayat-mutasi'
    },
  })
}

const detailRegistrasi = () => {

  router.push({
    name: 'module-registrasi-detail-registrasi',
    query: {
      noregistrasi: selectedItem.value.noregistrasi,
      norec_pd: selectedItem.value.norec_pd,
      norec_apd: selectedItem.value.norec_apd,
    },
  })
}

const cetakSuratKeteranganDokter = async (e: any) => {
  let dokter = `&dokter=${e.namalengkap}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=9`
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

    const response = await useApi().post('/dashboard/send-WA-IGD', {
      dokter: e.namalengkap,
      kelompokpasien: e.kelompokpasien,
      objectdepartemenfk: '9',
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


dropdownList()


watch(
  () => item.value.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Pindah') {
      showPindah.value = true
    } else {
      showPindah.value = false
    }

  }
)
watch(
  () => item.value.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Meninggal') {
      showMeninggal.value = true
    } else {
      showMeninggal.value = false
    }
  }
)
watch(
  () => item.value.statuskeluar,
  (newValue, oldValue) => {
    if (newValue.statuskeluar == 'Pulang') {
      showPulang.value = true
    } else {
      showPulang.value = false
    }
  }
)

watch(
  () => [
    item.value.fStatusAntrian
  ], () => {
    if (item.value.fStatusAntrian == 'mutasi') {
      pasienMutasi();
    } else {
      fetchPasien();
    }
  }
);


fetchPasien()
fetchJadwalDokter()
fetchDataChart()
fetchdDropdown()

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/igd.scss';

.hide {
  display: hidden !important;
}
</style>
