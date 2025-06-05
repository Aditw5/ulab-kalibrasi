<template>
  <section>
    <div class="business-dashboard hr-dashboard">
      <div class="columns">
        <div class="column is-10 is-offset-1">
          <div class="columns is-multiline">
            <!--Header-->
            <div class="column is-12">
              <div class=" illustration-header-2 columns is-multiline">
                <div class="column is-4 p-0">
                  <div class="header-image">
                    <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                      style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
                  </div>
                </div>
                <div class="column is-4 p-0">
                  <div class="header-meta">
                    <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Rawat Jalan</h3>
                    <p>
                      Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                    </p>

                    <div class="column is-12 p-0">
                      <VControl>
                        <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                          filter placeholder="Pilih Ruangan" :maxSelectedLabels="8" style="display:flex"
                          @change="changeRuang(sourceRuangan)" />
                      </VControl>
                      <VControl>
                        <VSwitchBlock v-model="item.isPasien" label="Pasien Dokter" color="danger"
                          v-if="kelompokUser == 'dokter'" />
                      </VControl>
                    </div>
                  </div>
                </div>
                <div class="column is-2" v-if="kelompokUser == 'dokter'">
                  <VCard>
                    <VBlock title="Total Tindakan">
                      <template #icon>
                        <VIconBox color="success" rounded>
                          <i class="fas fa-procedures" aria-hidden="true"></i>
                        </VIconBox>
                      </template>
                      <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalTindakan
                        }}</span>
                    </VBlock>
                  </VCard>
                </div>
                <div class="column is-2" v-if="kelompokUser == 'dokter'">
                  <VCard>
                    <VBlock title="Total Tindakan">
                      <template #icon>
                        <VIconBox color="warning" rounded>
                          <i class="fas fa-coins" aria-hidden="true"></i>
                        </VIconBox>
                      </template>
                      <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{
                        H.formatRp(item.pendapatanJasa, 'Rp.') }}</span>
                    </VBlock>
                  </VCard>
                </div>

              </div>

              <!-- <div class="illustration-header-2">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                  style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
              </div> -->

              <!-- <div class="header-meta">
                <h3 style="color:white"><i class="fas fa-home"></i> Instalasi Rawat Jalan</h3>
                <p>
                  Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                </p>
                <div class="column is-2 p-0">
                  <VControl>
                    <MultiSelect v-model="sourceRuangan" display="chip" :options="d_Ruangan" optionLabel="label"
                      placeholder="Select Cities" :maxSelectedLabels="3"  />
                  </VControl>
                  <VControl>
                    <VSwitchBlock v-model="item.isPasien" label="Pasien Dokter" color="danger"
                      v-if="kelompokUser == 'dokter'" />
                  </VControl>
                </div>
              </div> -->

              <!-- </div> -->
            </div>

            <div class="column">
              <div class="columns is-multiline">
                <!-- <div class="column is-2">
                  <VCard color="warning"  @click="accKonsul">
                    <VBlock>
                      <span style="font-weight: bold;margin-top: 7px;font-size: 18px;">Verifikasi Konsultasi</span>
                      <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalPasien }}</span>
                    </VBlock>
                  </VCard>
                </div> -->
                <div class="column is-2" size="small">
                  <VCard
                    :style="item.pasienkonsul == true ? 'background-color: var(--warning)' : 'border: 1px solid var(--warning)'"
                    :value="''" @click="setValueKonsul(item)" class="px-0 py-2">
                    <div class="column">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <h3 class="title is-4 has-text-centered">
                            PASIEN KONSUL <br> {{ dataPasienTotalKonsul }}
                          </h3>
                        </div>
                        <!-- <div class="column is-2">
                          <VTag v-if="item.pasienkonsul == true" color="green" rounded/>
                          <VTag v-else color="white" rounded/>
                        </div> -->
                      </div>
                    </div>
                  </VCard>
                </div>
                <div class="column is-2">
                  <VCard
                    :style="item.fStatusAntrian == '' && item.pasienkonsul == false ? 'background-color: var(--success)' : 'border: 1px solid var(--success)'"
                    :value="''" @click="setValue(item)" class="px-0 py-2">
                    <div class="column">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <h3 class="title is-4 has-text-centered">
                            SEMUA PASIEN <br> {{ dataPasienTotalSemua }}
                            <!-- SEMUA PASIEN <br> {{ dataPasienTotalSemua }} -->
                          </h3>
                        </div>
                        <!-- <div class="column is-2">
                          <VTag v-if="item.fStatusAntrian == '' && item.pasienkonsul == false" color="green" rounded/>
                          <VTag v-else color="white" rounded/>
                        </div> -->
                      </div>
                    </div>
                  </VCard>
                </div>
                <div class="column is-2">
                  <VCard
                    :style="item.fStatusAntrian == 'Belum Dipanggil' ? 'background-color: var(--danger)' : 'border: 1px solid var(--danger)'"
                    @click="setValueBelum(item)" class="px-0 py-2">
                    <div class="column">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <h3 class="title is-4 has-text-centered">
                            BELUM DIPANGGIL <br> {{ dataPasienBelumDipanggil }}
                          </h3>
                        </div>
                        <!-- <div class="column is-2">
                          <VTag v-if="item.fStatusAntrian == 'Belum Dipanggil'" color="green" rounded/>
                          <VTag v-else color="white" rounded/>
                        </div> -->
                      </div>
                    </div>
                  </VCard>
                </div>
                <div class="column is-2">
                  <VCard
                    :style="item.fStatusAntrian == 'Sudah Dipanggil' ? 'background-color: var(--info)' : 'border: 1px solid var(--info)'"
                    @click="setValueSudah(item)" class="px-0 py-2">
                    <div class="column">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <h3 class="title is-4 has-text-centered">
                            SUDAH DIPANGGIL <br> {{ dataPasienTotalSudahPanggil }}
                          </h3>
                        </div>
                        <!-- <div class="column is-2">
                          <VTag v-if="item.fStatusAntrian == 'Sudah Dipanggil'" color="green" rounded/>
                          <VTag v-else color="white" rounded/>
                        </div> -->
                      </div>
                    </div>
                  </VCard>
                </div>
                <div class="column is-2">
                  <VCard
                    :style="item.fStatusAntrian == 'Belum Selesai' ? 'background-color: var(--primary)' : 'border: 1px solid var(--primary)'"
                    @click="setValueBelumSelesai(item)" class="px-0 py-2">
                    <div class="column">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <h3 class="title is-4 has-text-centered">
                            BELUM SELESAI <br> {{ dataPasienTotalBelumSelesai }}
                          </h3>
                        </div>
                        <!-- <div class="column is-2">
                          <VTag v-if="item.fStatusAntrian == 'Belum Selesai'" color="green" rounded/>
                          <VTag v-else color="white" rounded/>
                        </div> -->
                      </div>
                    </div>
                  </VCard>
                </div>
                <div class="column is-2">
                  <VCard
                    :style="item.fStatusAntrian == 'Selesai' ? 'background-color: var(--secondary)' : 'border: 1px solid var(--secondary)'"
                    @click="setValueSelesaiDiperiksa(item)" class="px-0 py-2">
                    <div class="column">
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <h3 class="title is-4 has-text-centered">
                            SELESAI DIPERIKSA <br> {{ dataPasienTotal }}
                          </h3>
                        </div>
                        <!-- <div class="column is-2">
                          <VTag v-if="item.fStatusAntrian == 'Selesai'" color="green" rounded/>
                          <VTag v-else color="white" rounded/>
                        </div> -->
                      </div>
                    </div>
                  </VCard>
                </div>
                <!-- <VRadio v-model="item.fStatusAntrian" value="" label="Semua" name="outlined_radio" color="success"
                          @click="setCache(item.fStatusAntrian)" /> -->
                <!-- <div class="column is-4">
                  <VCard>
                    <VBlock title="Total Pasien">
                      <template #icon>
                        <VIconBox color="danger" rounded>
                          <i class="fas fa-user-injured" aria-hidden="true"></i>
                        </VIconBox>
                      </template>
                      <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalPasien }}</span>
                    </VBlock>
                  </VCard>
                </div>
                <div class="column is-4">
                  <VCard>
                    <VBlock title="Total Tindakan">
                      <template #icon>
                        <VIconBox color="success" rounded>
                          <i class="fas fa-procedures" aria-hidden="true"></i>
                        </VIconBox>
                      </template>
                      <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{ item.totalTindakan
                      }}</span>
                    </VBlock>
                  </VCard>
                </div>
                <div class="column is-4">
                  <VCard>
                    <VBlock title="Total Tindakan">
                      <template #icon>
                        <VIconBox color="warning" rounded>
                          <i class="fas fa-coins" aria-hidden="true"></i>
                        </VIconBox>
                      </template>
                      <VPlaceload v-if="isLoadCount" class="mx-2 mt-3" />
                      <span v-else style="font-weight: 500;margin-top: 7px;font-size: 15px;">{{
                        H.formatRp(item.pendapatanJasa, 'Rp.') }}</span>
                    </VBlock>
                  </VCard>
                </div> -->
              </div>
            </div>

            <!--Incoming-->
            <div class="column is-12">
              <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                <TabPanel>
                  <template #header>
                    <i class="fas fa-users mr-2" aria-hidden="true"></i>
                    <span>Daftar Pasien</span>
                    <Badge :value="dataPasien.total" v-if="dataPasien.total > 0" severity="danger" class="ml-2" />
                  </template>

                  <div v-if="activeTab == 0">
                    <!-- <div class="column is-12"
                      style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;"> -->
                    <!-- <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                      </VDatePicker> -->
                    <!-- <VIconButton style="margin-left: 22rem;margin-top: -48px;height: 36px;" @click="reload()" :loading="isLoading" color="primary"
                    icon="feather:search" /> -->
                    <!-- </div> -->
                    <div class="search-menu-rajal mb-2"
                      :class="isStuck && 'is-stuck is-10 mt-2 py-2 pl-2 bussiness-dashboard'">
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
                      <div class="search-location-rajal" :class="isStuck && 'rounded has-background-white'"
                        style="width: 100%">
                        <i class="iconify" data-icon="feather:search"></i>
                        <input type="text"
                          placeholder="Cari Nama Pasien, No Reservasi, No Registrasi, No RM, BPJS, Atau NIK"
                          v-model="item.search" v-on:keyup.enter="searchData" />
                      </div>
                      <VButton raised class="search-button-rajal" @click="searchData()" :loading="isLoading"> Cari Data
                      </VButton>
                    </div>

                    <div class="list-view list-view-v3">
                      <div style="width: 100%" v-if="showButton()">
                        <VButton rounded color="info" class="mr-2 mt-2" icon="fas fa-users" raised bold
                          @click="accOrderSITB">
                          Verifikasi Order SITB
                          <Badge :value="totalOrderSITB" severity="danger" class="ml-2" v-if="totalOrderSITB > 0" />
                        </VButton>
                      </div>
                      <!-- <VCard class="text-center pt-0 pb-0 mt-0">
                        <VRadio v-model="item.fStatusAntrian" value="" label="Semua" name="outlined_radio" color="success"
                          @click="setCache(item.fStatusAntrian)" />
                        <VRadio v-model="item.fStatusAntrian" value="Belum Dipanggil" label="Belum Dipanggil"
                          name="outlined_radio" color="warning" @click="setCache(item.fStatusAntrian)" />
                        <VRadio v-model="item.fStatusAntrian" value="Sudah Dipanggil" label="Sudah Dipanggil"
                          name="outlined_radio" color="danger" @click="setCache(item.fStatusAntrian)" />
                        <VRadio v-model="item.fStatusAntrian" value="Belum Selesai" label="Belum Selesai"
                          name="outlined_radio" color="info" @click="setCache(item.fStatusAntrian)" />
                        <VRadio v-model="item.fStatusAntrian" value="Selesai" label="Selesai Diperiksa"
                          name="outlined_radio" color="info" @click="setCache(item.fStatusAntrian)" />
                        <VRadio value="Ranap" label="Rawat Inap" name="outlined_radio" color="info"
                          @click="rawatInap(item)" />
                      </VCard> -->
                      <VPlaceholderPage :class="[dataPasien.length !== 0 && 'is-hidden']"
                        title="Tidak Ada Pasien Hari Ini."
                        subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                        <template #image>
                          <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                          <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                            alt="" />
                        </template>
                      </VPlaceholderPage>
                      <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                        <div name="list-complete" tag="div">
                          <div v-for="(item, rowIndex) in dataPasien" :key="rowIndex">
                            <!-- <div v-if="rowGroupMetadata[item.namaruangan].index === rowIndex">
                              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                item.namaruangan }}</span>
                              <Badge :value="rowGroupMetadata[item.namaruangan].size"
                                v-if="rowGroupMetadata[item.namaruangan].size > 0" class="ml-2 mt-2-min" />
                            </div> -->
                            <div
                              v-if="rowGroupMetadata[item.namaruangan] && rowGroupMetadata[item.namaruangan].index === rowIndex">
                              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                item.namaruangan
                              }}</span>
                              <Badge :value="rowGroupMetadata[item.namaruangan].size"
                                v-if="rowGroupMetadata[item.namaruangan].size > 0" class="ml-2 mt-2-min" />
                            </div>
                            <div class="list-view-item ">
                              <div class="list-view-item-inner">
                                <VAvatar size="small" color="primary" squared bordered
                                  :picture="item.jeniskelamin === 'Laki-Laki' ? '/images/avatars/svg/pasien.svg' : '/images/avatars/svg/vuero-4.svg'" />
                                <!-- <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered /> -->
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
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                    <span>{{ item.tglregistrasi }}</span>

                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                    <span>{{ item.noregistrasi }}</span>
                                    <!-- <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                  <span>{{ item.namaruangan }}</span> -->
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
                                    <span v-if="item.sitb_id">
                                      | SITB :
                                      <template v-if="item.sitb_id === 'Menunggu Verifikasi'">
                                        <VTag :label="item.sitb_id" :color="'danger'" class="mr-2 mt-3-min" />
                                      </template>
                                      <template v-else>
                                        {{ item.sitb_id }}
                                      </template>
                                    </span>
                                  </span>
                                  <div>
                                    <i class="fas fa-sort-amount-down-alt mr-2 mt-1" aria-hidden="true"></i>
                                    <!-- <i class="fas fa-sort-numeric-up mr-2" aria-hidden="true"></i> -->
                                    <VTag :label="item.noantrian" :color="'danger'" class="mr-2 mt-3-min"
                                      v-tooltip.bubble="'NOMOR ANTRIAN'" />
                                    <VTag :label="item.status == null ? 'Belum Dipanggil' : item.status"
                                      :color="item.status == null || item.status == 'Belum Dipanggil' ? 'warning' : (item.status == 'Selesai' ? 'success' : 'info')"
                                      class="mr-2" />
                                    <VTag v-if="item.kelompokpasien == 'BPJS'"
                                      :label="item.skdp == null ? 'Belum dibuat SKDP' : 'Sudah dibuat SKDP'"
                                      :color="item.skdp !== null ? 'info' : 'danger'" class="mr-2" />
                                    <VTag :label="'SEP : ' + item.nosep" v-if="item.nosep != null" :color="'success'"
                                      class="mr-2" />
                                    <VTag :label="'Mobile JKN'" v-if="item.ismobilejkn == true" :color="'success'"
                                      class="mr-2" />
                                    <VTag :label="item.ischeckin == true ? 'Sudah Checkin' : 'Belum Checkin'"
                                      v-if="item.ismobilejkn == true"
                                      :color="item.ischeckin == true ? 'success' : 'danger'" class="mr-2" />
                                    <VTag :label="item.nosbmlastfk ? 'Sudah Confirm' : 'Belum Confirm'"
                                      v-if="item.ismobilejkn == false"
                                      :color="item.nosbmlastfk !== null ? 'purple' : 'danger'" class="mr-2" />
                                    <VTag :label="'TRACER : Dikirim'" v-if="item.isdikirim == true" :color="'info'"
                                      class="mr-2" />
                                    <VTag :label="'Pasien Kanker'" v-if="item.iskanker == true" :color="'danger'"
                                      class="mr-2" />

                                  </div>
                                  <div class="mt-1" style="justify-content: space-between; display: inline-flex;" v-if="item.laboratorium.length && item.laboratorium[0].keteranganorder == 'Order Laboratorium'
                                    || item.radiologi.length && item.radiologi[0].keteranganorder == 'Order Radiologi'
                                    || item.resep.length && item.resep[0].keteranganorder == 'Order Farmasi'
                                    || item.konsul.length && item.konsul[0].keteranganorder.includes('Kepada Yth')
                                    || item.perjanjian.length">
                                    <VIconButton icon="fas fa-bong" light raised bold color="danger"
                                      v-if="item.laboratorium.length && item.laboratorium[0].keteranganorder == 'Order Laboratorium'"
                                      v-tooltip.bubble="'LABORATORIUM'" style="margin-left: 10px" />
                                    <VIconButton icon="fas fa-radiation" light raised bold color="danger"
                                      v-if="item.radiologi.length && item.radiologi[0].keteranganorder == 'Order Radiologi'"
                                      v-tooltip.bubble="'RADIOLOGI'" style="margin-left: 10px" />
                                    <VIconButton icon="fas fa-pills" light raised bold color="danger"
                                      v-if="item.resep.length && item.resep[0].keteranganorder == 'Order Farmasi'"
                                      v-tooltip.bubble="'RESEP'" style="margin-left: 10px" />
                                    <VIconButton icon="fas fa-notes-medical" light raised bold color="danger"
                                      v-if="item.konsul.length && item.konsul[0].keteranganorder.includes('Kepada Yth')"
                                      v-tooltip.bubble="'KONSUL'" style="margin-left: 10px" />
                                    <VIconButton icon="fas fa-handshake" light raised bold color="danger"
                                      v-if="item.perjanjian.length" v-tooltip.bubble="'PERJANJIAN'"
                                      style="margin-left: 10px" />
                                  </div>
                                  <div>
                                    <span style="font-weight: bold;">DPJP :
                                      {{ item.namalengkap ? item.namalengkap : '-' }}
                                    </span>
                                    <div v-if="item.dokter_konsul_verif && item.konsultasi" style="font-weight: bold">
                                      Dokter Konsul
                                      : {{ item.dokter_konsul_verif }}</div>
                                    <div v-if="item.dokterTambahan.length > 0" style="font-weight: bold;">
                                      Dokter Pemeriksa : <span v-for="data in item.dokterTambahan">{{ data.namalengkap
                                        }},</span>
                                    </div>
                                  </div>
                                </div>

                                <div class="meta-right" v-if="item.objectstrukorderfk != null">
                                  <VIconButton v-tooltip.bottom.left="'Panggil Pasien'" label="Bottom Left"
                                    color="primary" circle icon="feather:phone" @click="panggil(item)"
                                    :loading="item.loading" />

                                  <VIconButton v-tooltip.bottom.left="'Jawab Konsul'" label="Bottom Left" color="danger"
                                    circle icon="fas fa-arrow-right" @click="jawab(item)" :loading="item.isLoadingTN"
                                    style="margin-right: 10px; margin-left: 10px" v-if="kelompokUser == 'dokter'" />

                                  <VIconButton v-tooltip.bottom.left="'Konsultasi Dokter'" label="Bottom Left"
                                    color="danger" circle icon="fas fa-book" @click="jawab(item)"
                                    :loading="item.isLoadingTN" style="margin-right: 10px; margin-left: 10px" v-else />

                                  <!-- <RouterLink :to="{
                                    // H.cacheHelper().set('xxx_cache_menu', undefined)
                                    name: 'module-emr-profile-pasien',
                                    query: {
                                      nocmfk: item.nocmfk,
                                      norec_pd: item.norec_pd,
                                      norec_apd: item.norec_apd,
                                    }
                                  }"> -->
                                  <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined raised
                                    @click="emr(item)" v-tooltip.bottom.left="'EMR'">
                                  </VIconButton>
                                  <!-- </RouterLink> -->
                                </div>
                                <div class="meta-right" v-else>
                                  <div class="buttons">
                                    <VIconButton v-tooltip.bottom.left="'Panggil Pasien'" label="Bottom Left"
                                      color="primary" circle icon="feather:phone" @click="panggil(item)"
                                      :loading="item.loading" />


                                    <VIconButton  v-if="item.skdp == null && item.kelompokpasien != 'Umum'" color="info"
                                      circle icon="fas fa-pager" outlined raised @click="openModalSKDP(item)"
                                      v-tooltip.bottom.left="'Buat SKDP'" />

                                    <VIconButton color="primary" class="mr-2" circle icon="fas fa-stethoscope" outlined
                                      raised @click="emr(item)" v-tooltip.bottom.left="'EMR'"
                                      v-if="(item.ischeckin == true && item.ismobilejkn == true) || item.ismobilejkn == null || item.nosbmlastfk">
                                    </VIconButton>

                                    <VIconButton color="primary" circle icon="pi pi-ellipsis-v" raised
                                      @click="toggleOP($event, item)" v-tooltip.bottom.left="'AKSI'">
                                    </VIconButton>
                                  </div>
                                </div>
                              </div>
                            </div>

                          </div>
                        </div>

                      </div>
                      <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                        :total-items="dataPasien.total" :max-links-displayed="5">
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
                                    <option :value="3">3 results per page</option>
                                    <option :value="5">5 results per page</option>
                                    <option :value="6">6 results per page</option>
                                    <option :value="10">10 results per page</option>
                                    <option :value="15">15 results per page</option>
                                    <option :value="25">25 results per page</option>
                                    <option :value="50">50 results per page</option>
                                    <option :value="100">100 results per page</option>
                                    <option :value="200">200 results per page</option>
                                    <option :value="500">500 results per page</option>
                                    <option :value="1000">1000 results per page</option>
                                    <option :value="10000">All</option>
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
                    <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
                    <span>Daftar Reservasi</span>
                    <Badge :value="dataReservasi.length" v-if="dataReservasi.length > 0" severity="danger"
                      class="ml-2" />
                  </template>
                  <div v-if="activeTab == 1">

                    <div class="column is-6"
                      style="margin-left: 25rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
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
                      <div class="search-menu-rajal" style="margin-bottom : 1rem;">
                        <div class="search-location-rajal" style="width: 100%">
                          <i class="iconify" data-icon="feather:search"></i>
                          <input type="text" placeholder="Cari Nama Pasien, No Reservasi, No RM, BPJS, Atau NIK"
                            v-model="item.qsearch" v-on:keyup.enter="fetchReservasi()" />
                        </div>
                        <!-- <div class="search-location">
                            <i class="iconify" data-icon="feather:activity"></i>
                            <input type="text" placeholder="No Reservasi" v-model="item.qnorev" />
                          </div>
                          <div class="search-job">
                            <i class="iconify" data-icon="feather:user"></i>
                            <input type="text" placeholder="Nama Pasien" v-model="item.qnamapasien" />
                          </div> -->
                        <VButton color="primary" raised class="search-button-rajal" @click="fetchReservasi()"
                          :loading="isLoadingTT"> Cari Data </VButton>
                      </div>
                      <div class="list-view-inner" style="max-height:300px;overflow: auto;">
                        <div name="list-complete" tag="div">
                          <!--Item-->
                          <div v-for="item in dataReservasi" :key="item.id" class="list-view-item">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                              <div class="meta-left">
                                <h3>
                                  {{ item.namapasien }}
                                </h3>
                                <span>
                                  <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                  <span>{{ item.namakotakabupaten }} / {{ item.namakecamatan }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
                                  <span>{{ item.namaruangan }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                  <span>{{ item.tanggalreservasi }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                  <span>{{ item.noreservasi }}</span>

                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:credit-card"></i>
                                  <span>{{ item.kelompokpasien }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                  <span>{{ item.nocm }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:clipboard"></i>
                                  <span>{{ item.nobpjs }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="teenyicons:id-outline"></i>
                                  <span>{{ item.noidentitas }}</span><br>
                                  <i aria-hidden="true" class="iconify" data-icon="ic:baseline-house"></i>
                                  <span>{{ item.namakotakabupaten }}</span>
                                </span>
                                <br>
                                <VTag :label="item.status" :color="item.status_c" v-if="!item.ismobilejkn"
                                  class="mr-1" />
                                <VTag label="Mobile JKN" :color="item.status_c" v-if="item.ismobilejkn" class="mr-1" />
                                <VTag :label="item.ischeckin == true ? 'Sudah Checkin' : 'Belum Checkin'"
                                  :color="item.status_c" v-if="item.ismobilejkn" />
                              </div>
                              <div class="meta-right">
                                <button class="button v-button is-dark-outlined" @click="confirmReservasi(item)"
                                  v-if="item.isconfirm == null && item.ismobilejkn != true">
                                  <span class="icon">
                                    <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                  </span>
                                  <span>Confirm</span>
                                </button>
                                <button class="button v-button is-dark-outlined" @click="checkinJKN(item)"
                                  :loading="isLoading" v-if="item.ischeckin != true && item.ismobilejkn == true">
                                  <span class="icon">
                                    <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                  </span>
                                  <span>Check In</span>
                                </button>
                                <button class="button v-button is-dark-outlined" @click="editAsuransi(item)"
                                  :loading="isLoading" v-if="item.ischeckin == true && item.ismobilejkn == true">
                                  <span class="icon">
                                    <i aria-hidden="true" class="iconify" data-icon="feather:arrow-right"></i>
                                  </span>
                                  <span>Edit Asuransi</span>
                                </button>
                              </div>
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

        <!-- <div class="column is-4">
          <VCard>
            <VIconButton circle class="is-pulled-right" icon="feather:refresh-cw" raised bold @click="fetchDataChart()"
              :loading="isLoadingCall" />
            <div class="dashboard-card is-gauge">
              <div class="column border-custom mb-2">
                <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Grafik Pelayanan Pasien
                </span>
              </div>
              <ApexChart id="apex-chart-22" :height="270" :type="'pie'" :series="chartStatus.series"
                :options="chartStatus">
              </ApexChart>
            </div>
          </VCard>

          <div class="column">
            <VButton rounded color="info" class="is-pulled-left mr-2 mt-2" icon="fas fa-users" raised bold
              @click="accKonsul" v-if="kelompokUser == 'dokter'">
              Verifikasi Konsultasi
              <Badge :value="totalKonsul" severity="danger" class="ml-2" v-if="totalKonsul > 0" />
            </VButton>
          </div>

          <div class="column border-custom mb-2" style="margin-top: 2rem;">
            <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Stok Produk
              <VIconButton v-tooltip.bottom.right="'Order Barang'" label="Bottom Right" color="primary" circle
                icon="feather:shopping-cart" style="margin-left: 13rem;" @click="orderBarang()" />
            </span>
          </div>
          <div class="tile-grid tile-grid-v2">

            List Empty Search Placeholder
            <VPlaceholderPage :class="[dataStok.length !== 0 && 'is-hidden']" title="Tidak Ada Stok Produk."
              subtitle=" Silakan Pilih Ruangan untuk Stok Produk" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>

            Tile Grid v1

            <div name="list" tag="div" class="columns is-multiline">
              Grid item
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
        </div> -->
      </div>
      <!-- modal uabh dokter dpjp-->
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
        <div class="column" v-for="data in listDokterTambahan">
          <span style="font-weight: 500;">Dokter Pemeriksa</span>
          <VField class="is-autocomplete-select">
            <VControl icon="feather:search">
              <AutoComplete v-model="data.dokterPemeriksaTambahan" :suggestions="d_Dokter"
                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Dokter" />
            </VControl>
          </VField>
        </div>
        <div class="column">
          <VButton color="info" :size="'small'" icon="pi pi-plus" outlined raised @click="tambahDokter()"> Tambah Dokter
          </VButton>
        </div>
        <template #footer>
          <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDokter = false"> Batal
          </VButton>
          <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDokter()" :loading="btnLoadSimpan">
            Update
          </VButton>
        </template>
      </Dialog>

      <Dialog v-model:visible="modalDelegasiDokter" modal header="Form Delegasi Dokter" :style="{ width: '25vw' }">
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
          <VButton color="danger" icon="pi pi-times" outlined raised @click="modalDelegasiDokter = false"> Batal
          </VButton>
          <VButton color="primary" icon="pi pi-check" raised @click="saveDelegasiDokter()" :loading="btnLoadSimpan">
            Delegasi
          </VButton>
        </template>
      </Dialog>

      <Dialog v-model:visible="modalSKDP" modal header="Form SKDP" :style="{ width: '90vw' }">
        <daftarSKDP :pasien="pasien" :registrasi="pasien" />
      </Dialog>
      <Dialog v-model:visible="modalPilihPerawat" modal header="Form Pilih Perawat" :style="{ width: '25vw' }">
        <div class="column">
          <span style="font-weight: 500;">Perawat</span>
          <VField class="is-autocomplete-select pt-3">
            <VControl icon="feather:search">
              <AutoComplete v-model="item.perawatfk" :suggestions="d_Perawat" @complete="fetchPerawat($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik Nama Perawat" />
            </VControl>
          </VField>
        </div>
        <template #footer>
          <VButton color="danger" icon="pi pi-times" outlined raised @click="modalPilihPerawat = false"> Batal
          </VButton>
          <VButton color="primary" icon="pi pi-check" raised @click="savePilihPerawat()" :loading="btnLoadSimpan">
            Update
          </VButton>
        </template>
      </Dialog>
      <!-- modal uabh dokter dpjp-->
    </div>

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
              <VLabel class="required-field">Ruangan</VLabel>
              <VControl icon="feather:map-pin">
                <VInput type="text" v-model="item.namaruangan" placeholder="Tempat Lahir" class="is-rounded_Z"
                  disabled />
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
        <VButton icon="feather:plus" color="primary" @click="saveBatalRegis()" :loading="isLoading" raised>Simpan
        </VButton>
      </template>
    </VModal>

    <VModal :open="modalInputSitb" title="Input No SITB" size="medium" actions="right" @close="modalInputSitb = false"
      cancelLabel="Tutup">
      <template #content>
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>NO SITB</VLabel>
              <VControl icon="feather:clipboard">
                <VInput type="text" v-model="item.nositb" placeholder="No SITB" class="is-rounded_Z" />
              </VControl>
            </VField>
          </div>
        </div>
      </template>
      <template #action>
        <VButton icon="feather:plus" color="primary" @click="saveInputSitb()" :loading="isLoading" raised>Simpan
        </VButton>
      </template>
    </VModal>

    <OverlayPanel ref="op" appendTo="body" style="width:300px">
      <div class="columns is-multiline">
        <div class="column is-6 pt-1 pb-1 ">
          <VButton type="button" icon="fas fa-print" class="w-100" circle outlined color="success" raised
            :loading="btnLoadCetak" @click="cetakSEP()">
            Cetak SEP
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1 pl-0">
          <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
            @click="openModalDpjp(selectedItem)">
            Ubah DPJP
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1 pl-0">
          <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
            @click="openDelegasiDpjp(selectedItem)">
            Delegasi DPJP
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1">
          <VButton type="button" icon="fas fa-user-nurse" class="w-100" circle outlined color="black" raised
            @click="openModaPilihPerawat()">
            Pilih Perawat
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1 pl-0">
          <VButton type="button" icon="fas fa-times-circle" class="w-100" circle outlined color="danger" raised
            @click="batalRegis(selectedItem)">
            Batal Registrasi
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1 pl-0" v-if="!selectedItem.sitb_id">
          <VButton type="button" icon="fas fa-user-file" class="w-100" circle outlined color="purple" raised
            @click="RequestSITB(selectedItem)">
            Request SITB
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1 pl-0">
          <VButton type="button" icon="fas fa-user-file" class="w-100" circle outlined color="purple" raised
            @click="cetakSuratKeteranganDokter(selectedItem)">
            Cetak Surat Dokter
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1 pl-0">
          <VButton type="button" icon="lnir lnir-whatsapp" class="w-100" circle outlined color="success" raised
            @click="kirimWASuratKeteranganDokter(selectedItem)">
            Kirim WA Surat Dokter
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1" v-if="selectedItem.sitb_id">
          <VButton type="button" icon="fas fa-user-file" class="w-100" circle outlined color="purple" raised>
            {{ selectedItem.sitb_id }}
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1"
          v-if="selectedItem.ischeckin == null && selectedItem.ismobilejkn == true && selectedItem.kelompokpasien !== 'Umum'">
          <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
            @click="checkinJKN(selectedItem)">
            Check-in MJKN
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1"
          v-if="selectedItem.ischeckin == true && selectedItem.ismobilejkn == true || selectedItem.kelompokpasien !== 'Umum'">
          <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
            @click="editAsuransi(selectedItem)">
            Edit Asuransi
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1"
          v-if="selectedItem.isconfirm == null && selectedItem.ismobilejkn != true && selectedItem.kelompokpasien == 'Umum'">
          <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
            @click="confirmReservasi(selectedItem)">
            Check-in Reservasi
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1"
          v-if="selectedItem.namaruangan == 'Poliklinik MDR-TB' || selectedItem.namaruangan == 'Poliklinik Paru'">
          <VButton type="button" icon="fas fa-file" class="w-100" circle outlined color="primary" raised
            @click="InputSitb(selectedItem)">
            Tambah No SITB
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1" v-if="selectedItem.iskanker == null || selectedItem.iskanker == false">
          <VButton type="button" icon="fas fa-user-edit" class="w-100" circle outlined color="warning" raised
            @click="openModalAccKanker(selectedItem)">
            Jadikan Pasien Kanker
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1">
          <VButton type="button" class="w-100" circle outlined color="success" raised @click="InputPpi(selectedItem)">
            Input PPI
          </VButton>
        </div>
        <div class="column is-6 pt-1 pb-1" v-if="selectedItem.iskanker == true">
          <VButton type="button" icon="fas fa-times-circle" class="w-100" circle outlined color="danger" raised
            @click="openModalAccKanker2(selectedItem)">
            Batalkan Pasien Kanker
          </VButton>
        </div>

      </div>

    </OverlayPanel>

    <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }">
      <div class="columns is-multiline">
        <div class="column is-3">
          <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
            :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }" class="pb-0">
              <VField>
                <VLabel class="required-field">Tanggal</VLabel>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column is-3">
          <VField>
            <VLabel>Ruangan Asal</VLabel>
            <VControl icon="feather:bookmark">
              <input v-model="item.ruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain "
                disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField>
            <VLabel>Ruangan Tujuan</VLabel>
            <VControl icon="feather:bookmark">
              <input v-model="item.ruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain "
                disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField>
            <VLabel>Dokter</VLabel>
            <VControl icon="feather:bookmark">
              <input v-model="item.dokter" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
            </VControl>
          </VField>
        </div>


        <div class="column is-3">
          <VField>
            <VControl>
              <VSwitchBlock v-model="item.rawatBersama" label="Rawat Bersama" color="danger" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-3">
          <VField>
            <VControl>
              <VSwitchBlock v-model="item.konsultasi" label="Konsultasi" color="danger" disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-6">
          <VField>
            <VLabel>Lain-lain</VLabel>
            <VControl icon="feather:bookmark">
              <input v-model="item.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <VField>
            <VLabel>Keterangan</VLabel>
            <VControl>
              <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan" disabled>
              </VTextarea>
            </VControl>
          </VField>
        </div>
        <div class="column is-12" v-if="kelompokUser == 'dokter'">
          <VField>
            <VLabel>Jawaban</VLabel>
            <VControl>
              <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban">
              </VTextarea>
            </VControl>
          </VField>
        </div>
        <div class="column is-12" v-else>
          <VField>
            <VLabel>Jawaban</VLabel>
            <VControl>
              <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban" disabled>
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
      <template #footer>
        <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
          Batal
        </VButton>
        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingTN"
          @click="simpan()" v-if="kelompokUser == 'dokter'"> Simpan
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="modalAcc" modal header="Konsultasi" :style="{ width: '60vw' }">
      <DaftarKonsul />
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalAcc = false">
          Tutup
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="modalAccOrder" modal header="List Request SITB" :style="{ width: '90vw' }">
      <DaftarOrderSITB />
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="closeAccOrder">
          Tutup
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="modalAccKanker" modal header="Konfirmasi" :style="{ width: '15vw' }">
      <p>Apakah anda yakin menjadikan pasien ini sebagai <b>Pasien Kanker</b>?</p>
      <template #footer>
        <VButton color="danger" icon="pi pi-times" outlined raised @click="modalAccKanker = false" class="mr-3"> Batal
        </VButton>
        <VButton color="primary" icon="pi pi-check" raised @click="jadikanPasienKanker()" :loading="btnLoadSimpan">
          Update
        </VButton>
      </template>
    </Dialog>

    <Dialog v-model:visible="modalAccKanker2" modal header="Konfirmasi" :style="{ width: '15vw' }">
      <p>Apakah anda yakin <b>Membatalkan</b> pasien ini sebagai <b>Pasien Kanker</b>?</p>
      <template #footer>
        <VButton color="danger" icon="pi pi-times" outlined raised @click="modalAccKanker2 = false" class="mr-3"> Batal
        </VButton>
        <VButton color="primary" icon="pi pi-check" raised @click="batalkanPasienKanker()" :loading="btnLoadSimpan">
          Update
        </VButton>
      </template>
    </Dialog>
    <VModal :open="modalInputPPI" title="Terdeteksi Infeksi" size="medium" actions="right"
      @close="modalInputPPI = false" cancelLabel="Tutup">
      <template #content>
        <div class="columns is-multiline">
          <div class="column is-12">
            <VField>
              <VLabel>Infeksi PPI</VLabel>
              <VControl class="prime-auto-cus ">
                <AutoComplete v-model="item.infeksippi" :suggestions="d_InfeksiPPI" :optionLabel="'label'"
                  @complete="fetchInfeksi($event)" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Infeksi..." class="mt-2  is-rounded" />
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
import { useWindowScroll } from '@vueuse/core'
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
import MultiSelect from 'primevue/multiselect';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import { state, socket } from "/@src/socket.js";
import sleep from '/@src/utils/sleep';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import OverlayPanel from 'primevue/overlaypanel';
import DaftarKonsul from '../registrasi/daftar-konsultasi.vue'
import DaftarOrderSITB from '../registrasi/daftar-order-sitb.vue'
import * as qzService from '/@src/utils/qzTrayService'
import daftarSKDP from '/@src/pages/module/emr/profile-pasien/page-emr/perjanjian-kontrol.vue'

useHead({
  title: 'Dashboard Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalInputPPI = ref(false)
const activeTab = ref(0);
const op = ref();
const date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalBatalRegis = ref(false)
const modalInputSitb = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const isLoadingCall = ref(false)
const isLoadingTN = ref(false)
const route = useRoute()
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const rowGroupMetadata: any = ref({})
const modalChangeDokter = ref(false)
const modalDelegasiDokter = ref(false)
const modalSKDP = ref(false)
const modalPilihPerawat = ref(false)
const modalAcc = ref(false)
const modalAccOrder = ref(false)
const itemSource: any = ref([])
const d_Dokter: any = ref([])
const d_Perawat: any = ref([])
const btnLoadSimpan: any = ref(false)
const btnLoadBtlRegis: any = ref(false)
const btnLoadCetak: any = ref(false)
const selectedItem: any = ref({})
const isReservasi: any = ref([])
const listDokterTambahan: any = ref([])
const noCmFkPasien: any = ref("")
const modalAccKanker = ref(false)
const modalAccKanker2 = ref(false)
const d_InfeksiPPI = ref([])
const isLoadingPop: any = ref(false)

const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const item = ref({
  isPasien: kelompokUser === 'dokter',
  ruangans: [],
  aktif: true,
  fStatusAntrian: route.query.statuspanggil ? route.query.statuspanggil : '',
  pasienkonsul: false,
  tanggalpembatalan: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const chart: any = ref({
  aktif: true
})

const currentPage: any = ref({
  limit: 50,
  rows: 50,
})

const pasien: any = ref({})

let ID_RUANGAN = useRoute().query.id as string
let sourceOrder: any = ref([])
let dataStok: any = ref([])
let dataKonsul: any = ref([])
let dataDokter: any = ref([])
let dataPasien: any = ref([])
let dataPasienTotalSemua: any = ref([])
let dataPasienTotalKonsul: any = ref([])
let dataPasienTotalOrderSITB: any = ref([])
let dataPasienTotal: any = ref([])
let dataPasienBelumDipanggil: any = ref([])
let dataPasienTotalSudahPanggil: any = ref([])
let dataPasienTotalBelumSelesai: any = ref([])
let totalKonsul: any = ref([])
let totalOrderSITB: any = ref([])
let dataReservasi: any = ref([])
let d_Ruangan: any = ref([])
let sourceRuangan: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let isLoadCount: any = ref(false)
let chartStatus: any = ref({
  series: [],
})
let countRuangan: any = ref([])
const filters = ref('')

const cities = ref([
  { name: 'New York', code: 'NY' },
  { name: 'Rome', code: 'RM' },
  { name: 'London', code: 'LDN' },
  { name: 'Istanbul', code: 'IST' },
  { name: 'Paris', code: 'PRS' }
]);

const tambahDokter = () => {
  listDokterTambahan.value.push({
    no: listDokterTambahan.value.length + 1,
  })
}

const InputPpi = (e: any) => {
  console.log(e)
  item.value.norec_pd = e.norec_pd
  item.value.norec = e.norec_ppi
  item.value.namapasien = e.namapasien
  item.value.nocm = e.nocm
  modalInputPPI.value = true
}

const saveInputPPI = async () => {
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
      fetchPasien()
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


const toggleOP = (event: any, item: any) => {
  selectedItem.value = item
  op.value.toggle(event);
}

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element) => {
    sourceRuangan.value.push(element)
  })
}

const showButton = () => {
  return d_Ruangan.value.some(room => room.label.includes('Poliklinik Paru') || room.label.includes('Poliklinik MDR-TB'));
}

const fetchPasien = async (e: any) => {

  if (H.cacheHelper().get('statusberobat')) {
    item.value.fStatusAntrian = H.cacheHelper().get('statusberobat')
  }

  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let limit: any = currentPage.value.limit
  let page: any = route.query.page ? route.query.page : 1
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let namapasien = item.value.qnama ? `&namapasien=${item.value.qnama}` : ''
  let nocm = item.value.qnocm ? `&nocm=${item.value.qnocm}` : ''
  let qnoreg = item.value.qnoreg ? `&noregistrasi=${item.value.qnoreg}` : ''
  let statuspanggil = item.value.fStatusAntrian ? `&statuspanggil=${item.value.fStatusAntrian}` : ''
  let konsul = item.value.pasienkonsul ? `&konsul=${item.value.pasienkonsul}` : ''
  isReservasi.value = false
  isLoading.value = true
  isLoadCount.value = true
  dataPasien.value = []
  await useApi().get(`/dashboard/rawat-jalan-pasien${dari}${sampai}${idPegawai}${ruanganid}${namapasien}${nocm}${qnoreg}${statuspanggil}${konsul}${search}&page=${page}&limit=${limit}`).then((response) => {
    if (page > response.last_page) {
      router.push({
        name: 'module-dashboard-rawat-jalan'
      })
    }
    isLoading.value = false
    response.data.sort(compare);
    response.data.forEach(pasien => {
      if (pasien.dokterTambahan.length > 0) {
        pasien.dokterTambahan.forEach((element, index) => {
          element.no = index + 1,
            element.dokterPemeriksaTambahan = element ? { value: element.objectpegawaifk, label: element.namalengkap } : ''
        });
      }
    })
    dataPasien.value = response.data
    dataPasien.value.total = response.total
  })
  if (kelompokUser == 'dokter') {
    countPasien(dari, sampai, idPegawai, e)
  }
  updateRowGroupMetaData()
}

const cetakSuratKeteranganDokter = async (e: any) => {
  let dokter = `&dokter=${e.namalengkap}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=18`
  let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  let norec_pd = `&norec_pd=${e.norec_pd}`
  H.printBlade(`dashboard/registrasi/cetak-surat-keterangan-dokter?noregistrasi=${e.noregistrasi}${dokter}${kelompokpasien}${objectdepartemenfk}${tglregistrasi}${norec_pd}`);
}

const kirimWASuratKeteranganDokter = async (e: any) => {
  // Pastikan loading state diaktifkan
  isLoadingPop.value = true;

  try {
    // Validasi input yang diperlukan sebelum mengirim
    if (!e.namalengkap || !e.kelompokpasien || !e.tglregistrasi || !e.norec_pd) {
      throw new Error("Data tidak lengkap. Harap isi semua informasi yang diperlukan.");
    }

    // Kirim data ke backend melalui API
    const response = await useApi().post('/dashboard/send-WA', {
      dokter: e.namalengkap,
      kelompokpasien: e.kelompokpasien,
      objectdepartemenfk: '18',
      tglregistrasi: e.tglregistrasi,
      norec_pd: e.norec_pd,
    });

    // Periksa status response
    if (response && response.data) {
      console.log("Response berhasil:", response.data);
    } else {
      console.warn("Response dari server tidak valid:", response);
    }
  } catch (error: any) {
    // Tangani error dengan baik dan tampilkan pesan ke pengguna
    console.error("Gagal mengirim WA:", error.message || error);
  } finally {
    // Pastikan loading state dihentikan
    isLoadingPop.value = false;
  }
}

const RequestSITB = async (e) => {
  await useApi()
    .post(`/dashboard/order-nositb-pasien`, e)
    .then((response: any) => {
      isLoading.value = false
      reload()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  // console.log(e)
  H.alert('success', 'Order Sudah terkirim, menunggu verifikasi');
}

const fetchPasienTotalKonsul = async (e: any) => {
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  dataPasienTotalKonsul.value = 0
  await useApi().get(`/dashboard/pasien-total-konsul${dari}${sampai}${idPegawai}${ruanganid}`).then((response) => {
    dataPasienTotalKonsul.value = response.length
  })
}

const fetchPasienTotalSelesai = async (e: any) => {
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let statuspanggil = `&statuspanggil=${"Selesai"}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  dataPasienTotal.value = 0
  await useApi().get(`/dashboard/pasien-total${dari}${sampai}${idPegawai}${ruanganid}${statuspanggil}`).then((response) => {
    dataPasienTotal.value = response.length
  })
}

const fetchPasienTotalBelumPanggil = async (e: any) => {
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let statuspanggil = `&statuspanggil=${"Belum Dipanggil"}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  dataPasienBelumDipanggil.value = 0
  await useApi().get(`/dashboard/pasien-total${dari}${sampai}${idPegawai}${ruanganid}${statuspanggil}`).then((response) => {
    dataPasienBelumDipanggil.value = response.length
  })
}

const fetchPasienTotalSudahPanggil = async (e: any) => {
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let statuspanggil = `&statuspanggil=${"Sudah Dipanggil"}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  dataPasienTotalSudahPanggil.value = 0
  await useApi().get(`/dashboard/pasien-total${dari}${sampai}${idPegawai}${ruanganid}${statuspanggil}`).then((response) => {
    dataPasienTotalSudahPanggil.value = response.length
  })
}

const fetchPasienTotalBelumSelesai = async (e: any) => {
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let statuspanggil = `&statuspanggil=${"Belum Selesai"}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  dataPasienTotalBelumSelesai.value = 0
  await useApi().get(`/dashboard/pasien-total${dari}${sampai}${idPegawai}${ruanganid}${statuspanggil}`).then((response) => {
    dataPasienTotalBelumSelesai.value = response.length
  })
}

const fetchPasienTotalSemua = async (e: any) => {
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let ruanganid = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruanganid = `&ruanganfk=${itemsRuang}`
  }
  dataPasienTotalSemua.value = 0
  await useApi().get(`/dashboard/pasien-total${dari}${sampai}${idPegawai}${ruanganid}`).then((response) => {
    dataPasienTotalSemua.value = response.length
  })
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
const fetchReservasi = async (e: any) => {

  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''
  let ruanganid = e != undefined ? `&ruanganid=${e}` : ''
  let namapasien = item.value.qnamapasien ? `&namapasien=${item.value.qnamapasien}` : ''
  let noreservasi = item.value.qnorev ? `&nocm=${item.value.qnorev}` : ''
  let search = item.value.qsearch ? `&search=${item.value.qsearch}` : ''
  isReservasi.value = true
  isLoading.value = true
  dataReservasi.value = []
  await useApi().get(`/dashboard/rawat-jalan-reservasi${dari}${sampai}${ruanganid}${idPegawai}${namapasien}${noreservasi}${search}`).then((response) => {
    isLoading.value = false;
    dataReservasi.value = response.reservasi
  })
}
const fetchSITB = async () => {
  let dari = `?dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  let sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  let idPegawai = `?idpegawai=${H.pegawaiLogin().id}`

  isLoading.value = true
  totalOrderSITB.value = []
  await useApi().get(`/dashboard/get-jumlah-request-sitb`).then((response) => {
    isLoading.value = false;
    totalOrderSITB.value = response
  })
}

const fetchJadwalDokter = async () => {
  let namaDokter = ''
  if (filters.value != undefined) {
    namaDokter = filters.value
  }
  let idPegawai = item.value.isPasien == true ? `&idpegawai=${H.pegawaiLogin().id}` : ''

  dataDokter.value = []
  dataStok.value = []

  const response = await useApi().get(
    '/dashboard/rawat-jalan-detail?namadokter=' + namaDokter + '&idPegawai' + idPegawai + '&limit=10'
  )
  dataDokter.value = response.dokter
  dataStok.value = response.produk
}

const fetchPerawat = async (filter: any) => {
  // let data = filter.query ? filter.query : filter
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}


const changeRuang = async (e: any) => {

  fetchPasien()
  fetchReservasi()
  fetchPasienTotalKonsul()
  fetchPasienTotalSemua()
  fetchPasienTotalBelumPanggil()
  fetchPasienTotalSudahPanggil()
  fetchPasienTotalBelumSelesai()
  fetchPasienTotalSelesai()
  // fetchJadwalDokter(items)
}
const reload = async () => {
  fetchPasien()
  fetchReservasi()
}

const searchData = async () => {
  fetchPasien()
  fetchReservasi()
  fetchSITB()
  fetchJadwalDokter()
  fetchPasienTotalKonsul()
  fetchPasienTotalSemua()
  fetchPasienTotalBelumPanggil()
  fetchPasienTotalSudahPanggil()
  fetchPasienTotalBelumSelesai()
  fetchPasienTotalSelesai()
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
    .get(`/dashboard/get-pelayanan-status?ruanganid=${ruanganid}&dari=${dari}&sampai=${sampai}`)
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
          'rgb(6, 214, 158)',
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
const filter = () => {
  fetchPasienTotal()
}
const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchPasien()
  }
  if (activeTab.value == 1) {
    fetchReservasi()
  }
}
const orderBarang = () => {
  router.push({
    name: 'module-logistik-order-barang'
  })
}
const panggil = async (e: any) => {
  e.loading = true

  await socket.emit('call-antrian-poli', {
    namapasien: e.namapasien,
    namaruangan: e.namaruangan,
    noantri: e.noantrian,
    nocm: e.nocm,
    norec: e.norec_apd,
  });

  sendAntrol(e.norec_pd)
  if (e.status == 'Belum Dipanggil') {
    useApi().post(
      `/dashboard/rawat-jalan/panggil`,
      {
        'norec_apd': e.norec_apd,
      }
    ).then((response: any) => {
      e.status = response.status
      e.loading = false
      fetchDataChart()
    }).catch((e: any) => {
      e.loading = false
    })
  }
  await sleep(1000)
  e.loading = false
}

const setCache = (e: any) => {
  H.cacheHelper().set('statusberobat', e)
}

const setValueKonsul = (item) => {
  item.pasienkonsul = true;
  item.fStatusAntrian = '';
  setCache(item.fStatusAntrian);
};

const setValue = (item) => {
  item.fStatusAntrian = '';
  item.pasienkonsul = false;
  setCache(item.fStatusAntrian);
}

const setValueBelum = (item) => {
  item.fStatusAntrian = 'Belum Dipanggil';
  item.pasienkonsul = false;
  setCache(item.fStatusAntrian);
}

const setValueSudah = (item) => {
  item.fStatusAntrian = 'Sudah Dipanggil';
  item.pasienkonsul = false;
  setCache(item.fStatusAntrian);
}

const setValueBelumSelesai = (item) => {
  item.fStatusAntrian = 'Belum Selesai';
  item.pasienkonsul = false;
  setCache(item.fStatusAntrian);
}

const setValueSelesaiDiperiksa = (item) => {
  item.fStatusAntrian = 'Selesai';
  item.pasienkonsul = false;
  setCache(item.fStatusAntrian);
}


const emr = (e: any) => {
  if (!e.nosep && (e.kelompokpasien == 'BPJS' || e.kelompokpasien == 'BPJS Non PBI ')) {
    console.log(e)
    H.alert('error', 'SEP belum dibuat / Silahkan Edit Asuransi')
    return
  }
  H.cacheHelper().set('xxx_cache_menu', undefined)
  sendAntrol(e.norec_pd)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    }
  })
}
// const mutasiPasien = (e: any) => {
//   router.push({
//     name: 'module-registrasi-mutasi-pasien',
//     query: {
//       nocmfk: e.nocmfk,
//       norec_pd: e.norec_pd,
//     }
//   })
// }
const rawatInap = (e: any) => {
  router.push({
    name: 'module-dashboard-rawat-inap',
    query: {
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
        if (namaruangan === previousRowGroup)
          rowGroupMetadata.value[namaruangan].size++;
        else
          rowGroupMetadata.value[namaruangan] = { index: i, size: 1 };
      }
    }
  }
}

const countPasien = (awal: any, akhir: any, pegawai: any, ruanganfk: any) => {

  // let ruangan = ruanganfk ? `&ruanganfk=${ruanganfk}` : ''
  let ruangan = ''
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruangan = `&ruanganfk=${itemsRuang}`
  }
  useApi().get(`dashboard/get-combo-jumlah${awal}${akhir}${pegawai}${ruangan}`).then((response) => {
    item.value.totalPasien = response.jumlahPasien
    item.value.totalTindakan = response.jumlahTindakan
    item.value.pendapatanJasa = response.pendapatanJasa.total
    isLoadCount.value = false
  })
}

const openModalDpjp = (data: any) => {
  modalChangeDokter.value = true
  item.value.dokterPemeriksa = selectedItem.value.pgid ? { value: selectedItem.value.pgid, label: selectedItem.value.namalengkap } : ''
  listDokterTambahan.value = data.dokterTambahan
}
const openDelegasiDpjp = (data: any) => {
  modalDelegasiDokter.value = true
  item.value.dokterPemeriksa = selectedItem.value.pgid ? { value: selectedItem.value.pgid, label: selectedItem.value.namalengkap } : ''
}

const openModalSKDP = (data: any) => {
  modalSKDP.value = true
  // console.log(data)
  pasien.value = {
    nocm: data.nocm,
    objectpegawaifk: data.objectpegawaifk,
    objectruanganlastfk: data.id_ruangan,
    noregistrasi: data.noregistrasi,
    nobpjs: data.nobpjs,
    noidentitas: data.noidentitas,
    nohp: data.nohp,
    nobpjs: data.nobpjs
  }
}

const openModaPilihPerawat = (data: any) => {
  modalPilihPerawat.value = true
  item.value.perawatfk = selectedItem.value.perawatfk ? { value: selectedItem.value.perawatfk, label: selectedItem.value.perawat } : ''
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
  btnLoadSimpan.value = true
  await useApi().post('registrasi/change-dokter-dpjp', { 'pasien': selectedItem.value, 'norec_pd': selectedItem.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value, 'dokterTambahan': listDokterTambahan.value }).then((response) => {
    btnLoadSimpan.value = false
    modalChangeDokter.value = false
    fetchPasien()
  }).catch((err) => {
    btnLoadSimpan.value = false
    console.log(err)
  })
}

const saveDelegasiDokter = async () => {
  if (!item.value.dokterPemeriksa) {
    H.alert('error', 'Dokter Wajib Dipilih')
    return
  }
  btnLoadSimpan.value = true
  await useApi().post('registrasi/change-dokter-dpjp', { 'pasien': selectedItem.value, 'norec_pd': selectedItem.value.norec_pd, 'objectpegawaifk': item.value.dokterPemeriksa.value }).then((response) => {
    btnLoadSimpan.value = false
    modalDelegasiDokter.value = false
    fetchPasien()
  }).catch((err) => {
    btnLoadSimpan.value = false
    console.log(err)
  })
}

const savePilihPerawat = async () => {

  if (!item.value.perawatfk) {
    H.alert('error', 'Perawat Wajib Dipilih')
    return
  }
  btnLoadSimpan.value = true
  await useApi().post('registrasi/tetapkan-perawat', { 'norec_pd': selectedItem.value.norec_pd, 'perawatfk': item.value.perawatfk.value }).then((response) => {
    btnLoadSimpan.value = false
    modalPilihPerawat.value = false
    delete item.value.perawatfk
    fetchPasien()
  }).catch((err) => {
    console.log(err)
  })
}

const cetakSEP = () => {
  btnLoadCetak.value = true
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + selectedItem.value.noregistrasi + "&pdf=true", 'SEP', 1)
  btnLoadCetak.value = false
}
const confirmReservasi = (e: any) => {
  console.log(e)
  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.nocmfk,
      noreservasi: e.noreservasi,
      norec_online: e.norec,
      tanggalreservasi: e.tanggalreservasi,
      ruangan: e.objectruanganfk,
      dokter: e.objectpegawaifk,
      dokter_name: e.dokter,
      kelompok: e.objectkelompokpasienfk,
    },
  })
}

const checkinJKN = async (e: any) => {
  isLoading.value = true
  let json = {
    'kodebooking': e.noreservasi
  }
  await useApi()
    .postNoMessage(`/dashboard/checkin-jkn`, json)
    .then((response: any) => {
      isLoading.value = false
      if (response.metaData.code == 200) {
        H.alert('success', response.metaData.message);
        router.push({
          name: 'module-registrasi-pemakaian-asuransi',
          query: {
            norec_pd: e.norec_pd,
            nocmfk: e.nocmfk,
            //   norec_apd: norec_apd
          }
        })
        // reload()
      } else {
        H.alert('error', response.metaData.message);
      }
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const editAsuransi = (e) => {
  router.push({
    name: 'module-registrasi-pemakaian-asuransi',
    query: {
      norec_pd: e.norec_pd,
      nocmfk: e.nocmfk,
      //   norec_apd: norec_apd
    }
  })
}

const saveBatalRegis = async () => {
  if (!item.value.alasanpembatalan) { H.alert('warning', 'Alasan Pembatalan harus di isi'); return }
  let json = {
    pasiendaftar: {
      'norec_pd': item.value.norec_pd,
      'tanggalpembatalan': H.formatDate(item.value.tanggalpembatalan, 'YYYY-MM-DD HH:mm:ss'),
      'alasanpembatalan': item.value.alasanpembatalan,
      'noregistrasi': item.value.noregistrasi,
      'namapasien': item.value.namapasien,
      'nocm': item.value.nocm,
      'ruangan': item.value.namaruangan,
    },
    antrianpasiendiperiksa: {
      'norec_apd': item.value.norec_apd,
    }

  }
  btnLoadBtlRegis.value = true
  await useApi()
    .post(`/dashboard/save-batal-registrasi`, json)
    .then((response: any) => {
      isLoading.value = false
      reload()
      clear()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  modalBatalRegis.value = false
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
  btnLoadBtlRegis.value = true
  await useApi()
    .post(`/dashboard/save-nositb-pasien`, json)
    .then((response: any) => {
      isLoading.value = false
      fetchPasien()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
  modalInputSitb.value = false
}

const clear = () => {

}
const batalRegis = (e: any) => {
  item.value.namaruangan = e.namaruangan
  item.value.norec_pd = e.norec_pd
  item.value.norec_apd = e.norec_apd
  item.value.noregistrasi = e.noregistrasi
  item.value.namapasien = e.namapasien
  item.value.nocm = e.nocm
  item.value.norec_apd = e.norec_apd
  modalBatalRegis.value = true
}

const InputSitb = (e: any) => {
  item.value.norec_pd = e.norec_pd
  item.value.nositb = e.sitb_id
  item.value.nocmfk = e.nocmfk
  modalInputSitb.value = true
}

const sendAntrol = async (norec_pd) => {
  const jsont4 = {
    "noregistrasifk": norec_pd,
    "taskid": 4,
    "waktu": new Date().getTime(),
  }
  await useApi()
    .postNoMessage(`/bridging/antrol/sendTaskId`, jsont4)
    .then(async (response: any) => {
      const jsont5 = {
        "noregistrasifk": norec_pd,
        "taskid": 5,
        "waktu": new Date().getTime(),
      }
      await useApi()
        .postNoMessage(`/bridging/antrol/sendTaskId`, jsont5)
        .then((response: any) => { })
    })
}
const konsul = (e: any) => {
  router.push({
    name: 'module-registrasi-daftar-konsultasi',
    query: {
    }
  })
}



const edit = async (e: any) => {
  // console.log(e)
  e.isLoadingTN = true
  // isLoadingTN.value = true
  await useApi().get(`dashboard/get-detail-konsul?norec_apd=${e.norec_apd}`).then((response) => {
    response.forEach((element: any, i: any) => {
      e.isLoadingTN = false
      item.value.norec = element.norec
      item.value.ruanganasal = element.ruanganasal

      item.value.tanggal = new Date(element.tglorder)

      item.value.ruanganasal = element.ruanganasal
      item.value.ruangantujuan = element.ruangantujuan
      item.value.dokter = element.dokter


      item.value.konsultasi = element.konsultasi ? element.konsultasi : false
      item.value.lainlain = element.lainlain ? element.lainlain : ''
      item.value.rawatBersama = element.rawatbersama ? element.rawatbersama : false
      item.value.keterangan = element.keteranganorder ? element.keteranganorder : ''
      item.value.jawaban = element.keteranganlainnya ? element.keteranganlainnya : 'Yth. TS. \n\n\n\n\n\n Salama sejawat, terimakasih'
      element.no = i + 1
    });
  })

  modalInput.value = true


}

const simpan = async () => {

  let formData = {
    'norec_so': item.value.norec,
    'jawaban': item.value.jawaban,
  }
  isLoadingTN.value = true
  await useApi().post('/emr/jawab-order-konsul', formData).then((r) => {
    isLoadingTN.value = false
    fetchPasien()
    modalInput.value = false
  }).catch((e: any) => {
    isLoadingTN.value = false
  })
}

const jawab = async (e: any) => {


  edit(e)
}

const accKonsul = () => {
  modalAcc.value = true
}
const accOrderSITB = () => {
  modalAccOrder.value = true
}

const closeAccOrder = () => {
  modalAccOrder.value = false
  fetchSITB()
}

const openModalAccKanker = (e: any) => {
  noCmFkPasien.value = e.nocmfk
  modalAccKanker.value = true
}

const openModalAccKanker2 = (e: any) => {
  noCmFkPasien.value = e.nocmfk
  modalAccKanker2.value = true
}

const kembaliKeun = () => {
  modalInput.value = false
}

const batalkanPasienKanker = async () => {
  if (noCmFkPasien.value == "") {
    H.alert('error', 'Pilih Pasin Terlebih Dahulu!')
    return
  }

  let formData = {
    'norec_pasien': noCmFkPasien.value,
  }
  isLoadingTN.value = true
  await useApi().post('/pasien/batal-status-kanker', formData).then((r) => {
    isLoadingTN.value = false
    reload()
    noCmFkPasien.value = ""
    modalAccKanker2.value = false
  }).catch((e: any) => {
    isLoadingTN.value = false
    modalAccKanker2.value = false
  })
}

const jadikanPasienKanker = async () => {
  if (noCmFkPasien.value == "") {
    H.alert('error', 'Pilih Pasin Terlebih Dahulu!')
    return
  }
  let formData = {
    'norec_pasien': noCmFkPasien.value,
  }
  isLoadingTN.value = true
  await useApi().post('/pasien/jadikan-status-kanker', formData).then((r) => {
    isLoadingTN.value = false
    noCmFkPasien.value = ""
    modalAccKanker.value = false
    reload()
  }).catch((e: any) => {
    isLoadingTN.value = false
    modalAccKanker.value = false
  })
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
      fetchPasien()
    }
  }
)
watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchPasien()
    }
  }
)
watch(
  () => [
    item.value.fStatusAntrian
  ], () => {
    H.cacheHelper().set('statusberobat', item.value.fStatusAntrian)
    fetchPasien()
  }
)
watch(
  () => [
    item.value.pasienkonsul
  ], () => {
    // H.cacheHelper().set('statusberobat', item.value.pasienkonsul)
    fetchPasien()
  }
)

watch(
  () => item.value.isPasien, () => {
    searchData()
  }
)

await fetchdDropdown()
await fetchPasien()
fetchSITB()
fetchPasienTotalKonsul()
fetchPasienTotalSemua()
fetchPasienTotalBelumPanggil()
fetchPasienTotalSudahPanggil()
fetchPasienTotalBelumSelesai()
fetchPasienTotalSelesai()
fetchJadwalDokter()
fetchDataChart()


</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/rawat-jalan.scss';

// .r-card {
//   flex: 1;
//   display: inline-block;
//   width: 100%;
//   padding: 15px;
//   background-color: var(--white);
//   border-radius: 10px;
//   border: 1px solid var(--fade-grey-dark-3);
//   transition: all 0.3s;
// }</style>
