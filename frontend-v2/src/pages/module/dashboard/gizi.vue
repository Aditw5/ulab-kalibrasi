
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
            <VTabs slider selected="Riwayat" :tabs="[
              { label: 'Daftar Order Gizi', value: 'Riwayat' },
              { label: 'Order Gizi', value: 'Pasien' },

            ]">

              <template #tab="{ activeValue }">
                <p v-if="activeValue === 'Pasien'">
                <div class="list-view list-view-v3">
                  <div class="search-menu-gizi" style="margin-bottom : 1rem;">
                    <div class="search-location-gizi" style="width: 100%">
                        <i class="iconify" data-icon="feather:search"></i>
                          <input type="text"
                            placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                            v-model="item.search" v-on:keyup.enter="fetchPasien(order)" />
                    </div>
                    <!-- <div class="search-location">
                      <i class="iconify" data-icon="feather:activity"></i>
                      <input type="text" placeholder="No Registrasi" v-model="item.qnoreg" />
                    </div>

                    <div class="search-salary">
                      <i class="iconify" data-icon="feather:clipboard"></i>
                      <input type="text" placeholder="No RM" v-model="item.qnocm" />
                    </div>
                    <div class="search-job">
                      <i class="iconify" data-icon="feather:user"></i>
                      <input type="text" placeholder="Nama Pasien" v-model="item.qnama" />
                    </div> -->
                    <VButton raised class="search-button-gizi" @click="fetchPasien()" :loading="isLoading"> Cari Data
                    </VButton>
                  </div>

                  <VCard>
                    <VPlaceholderPage :class="[dataSourcePasien.length !== 0 && 'is-hidden']"
                      title="Tidak Ada Pasien Hari Ini."
                      subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat Data Pasien" larger>
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                      </template>
                    </VPlaceholderPage>


                    <div class="list-view-inner" style="max-height:500px;overflow: auto;margin-top:2rem;">
                      <TransitionGroup name="list-complete" tag="div">
                        <Vcard>

                          <div v-for="item in dataSourcePasien" :key="item.id" class="list-view-item">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/pasien.svg" color="primary" bordered />
                              <div class="meta-left">
                                <h3>
                                  {{ item.namapasien }}

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

                                </span>
                                <br>

                              </div>
                              <div class="meta-right">
                                <div class="buttons">

                                  <RouterLink :to="{
                                    name: 'module-gizi-order-gizi',
                                    query: {
                                      nocmfk: item.nocmfk,
                                      norec_pd: item.norec_pd,
                                    }
                                  }">
                                    <VButton v-tooltip.bottom.left="'Order Gizi atau Kirim'" label="Bottom Left"
                                      color="primary" circle icon="fas fa-arrow-right"> Order Gizi </VButton>
                                  </RouterLink>

                                </div>

                              </div>
                            </div>
                          </div>
                        </Vcard>
                      </TransitionGroup>
                    </div>
                  </VCard>


                </div>

                </p>
                <p v-else-if="activeValue === 'Riwayat'">
                <!-- <div class="column is-5" style="margin-left: 30rem;margin-bottom: 20px;padding: 0px;margin-top: -4rem;">
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
                </div> -->
                <!-- <div class="search-menu-gizi" style="margin-bottom : 1rem;">
                  <div class="search-location-gizi" style="width: 100%">
                    <i class="iconify" data-icon="feather:search"></i>
                      <input type="text"
                        placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                        v-model="item.qsearch" v-on:keyup.enter="fetchRiwayat()" />
                  </div>
                  <div class="search-location">
                    <i class="iconify" data-icon="feather:activity"></i>
                    <input type="text" placeholder="No Registrasi" v-model="item.noreg" />
                  </div>

                  <div class="search-salary">
                    <i class="iconify" data-icon="feather:clipboard"></i>
                    <input type="text" placeholder="No RM" v-model="item.nocm" />
                  </div>
                  <div class="search-job">
                    <i class="iconify" data-icon="feather:user"></i>
                    <input type="text" placeholder="Nama Pasien" v-model="item.filterNama" />
                  </div>
                  <VButton raised class="search-button-gizi" @click="fetchRiwayat()" :loading="isLoading"> Cari Data
                  </VButton>
                </div> -->
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
                        color="info" />
                    <VRadio v-model="order" value="0" label="Belum Verifikasi" name="outlined_radio"
                        color="danger" />
                    <VRadio v-model="order" value="1" label="Terverifikasi" name="outlined_radio"
                        color="warning" />
                    <VRadio v-model="order" value="2" label="Diteruskan" name="outlined_radio"
                        color="success" />
                    <VRadio v-model="order" value="3" label="Diproses" name="outlined_radio"
                        color="primary" />
                    <VRadio v-model="order" value="4" label="Dikirim" name="outlined_radio"
                        color="link" />
                    <VRadio v-model="order" value="5" label="Selesai" name="outlined_radio"
                        color="info" />
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
                            <div class="column is-2 pb-0">
                              <VTag :color="item.statusgizi == 0 ? 'warning' : 'primary'" :label="item.statusgizi" rounded class="mr-3 mb-3" />
                              <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary" circle
                              outlined raised v-tooltip.bottom="'Aksi'" @click="toggle($event, item)">
                            </VIconButton>
                            </div>
                            
                           
                          </div>
                          <div class="columns is-multiline">
                            <div class="column is-4">
                              <label style="font-weight: 400;color: darkgray;font-size: 12px;">Kategori Diet - Jenis Waktu</label>
                              <h3 class="field mt-1" style="font-weight:500">{{ item.kategorydiet }} - {{ item.jeniswaktu }}</h3>
                            </div>
                            <div class="column is-4">
                              <label style="font-weight: 400;color: darkgray;font-size: 12px;">Jenis Diet</label>
                              <h3 class="field mt-1" style="font-weight:500">{{ item.arrjenisdiet ? item.arrjenisdiet : '-' }}</h3>
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

      <!-- <div class="column is-4">
        <VCard>
          <div class="dashboard-card is-gauge">
            <div class="column border-custom mb-2">
              <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Riwayat Kirim Menu
              </span>

              <VTag @click="showModalFilter()" color="danger" rounded elevated class="live-block is-clickable"
                style="margin-left: 1.2rem;">
                <span>{{ H.formatDateNoTime(item.filterDate) }}<i class="fas fa-filter ml-3"
                    aria-hidden="true"></i></span>
              </VTag>



            </div>
            <div class="tile-grid tile-grid-v2">

              <VPlaceholderPage :class="[dataKirim.length !== 0 && 'is-hidden']" title="Tidak Ada Menu yang Dikirim."
                subtitle=" Silakan Pilih Tanggal Kirim Menu" larger>
                <template #image>
                  <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                  <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                </template>
              </VPlaceholderPage>

              <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                  <div v-for="item in dataKirim" :key="item.id" class="column is-12">
                    <div class="tile-grid-item">

                      <div class="tile-grid-item-inner">

                        <VAvatar size="small" picture="/images/avatars/svg/man.png" color="primary" bordered />

                        <div class="meta">
                          <div v-for="nama in item.namaproduk">
                            <span>{{ nama.namaproduk }}</span>
                          </div>
                          <span class="dark-inverted">{{ item.namapasien }} -- {{ item.ruanganasal }}</span>
                          <span class="dark-inverted">{{ item.nokirim }}</span>
                          <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> Selesai
                          </VTag>
                        </div>

                      </div>

                    </div>
                  </div>
                </div>
              </TransitionGroup>

            </div>
          </div>
        </VCard>

        <UIWidget class="search-widget" style="margin-top: 1.7rem;">
          <template #body>
            <div class="field" style="padding: 2px">
              <div class="control">
                <input v-model="filters" class="input custom-text-filter" placeholder="Cari Stok Produk" />
                <button class="searcv-button">
                  <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                </button>
              </div>
            </div>
          </template>
        </UIWidget>
        <VCard>
          <div class="column border-custom mb-2">

            <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Stok Produk
              <VIconButton v-tooltip.bottom.right="'Request Produk'" label="Bottom Right" color="primary" circle
                icon="feather:external-link" style="margin-left: 10rem;" />
            </span>

          </div>
          <div class="tile-grid tile-grid-v2">
            <VPlaceholderPage :class="[dataStok.length !== 0 && 'is-hidden']" title="Tidak Ada Stok Produk."
              subtitle="Silakan Klik Tombol Permintaan Barang untuk Menambah Stok Produk" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <TransitionGroup name="list" tag="div" class="columns is-multiline">
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
            </TransitionGroup>

          </div>
        </VCard>
      </div> -->
    </div>
  </div>

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
    <template #action>
      <VButton icon="feather:check" @click="updateStatusTeruskan(dataDetailOrder)" :loading="isLoading" color="primary" raised>Teruskan
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalDetailRiwayatCppt" title="Riwayat CPPT Dokter Gizi Dan Nutrisionis" :noclose="true" size="big" actions="right"
  @close="modalDetailRiwayatCppt = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <TabView :scrollable="true" ref="tabView" v-if="riwayatCPPT && !isLoadCPPTRanap">
              <TabPanel v-for="tab in riwayatCPPT" :key="tab.title" :header="tab.title">
                <div class="columns is-multiline" v-for="riwayat in tab.content"
                :key="riwayat.uuid">
                  <div class="column is-12">
                    <Card
                      :style="{
                        backgroundColor:
                          riwayat.element.flag === 'perawat' || riwayat.element.flag === 'sbar' || riwayat.element.flag === 'sbarapt' ? 'var(--info--light-color)' :
                          riwayat.element.flag === 'dokter' || riwayat.element.flag === 'sbardokter' ? 'var(--danger--light-color)' : ''
                      }"
                      class="mt-2"
                    >
                      <template #title>
                          <div class="is-multiline columns">
                            <div class="is-2 column">
                                <span v-if="riwayat.element.flag !== 'sbar' && riwayat.element.flag !== 'sbarapt' && riwayat.element.flag !== 'sbardokter' && riwayat.element.flag !== 'gizi'">CPPT</span>
                                <span v-if="riwayat.element.flag === 'sbardokter'">SBAR DOKTER</span>
                                <span v-if="riwayat.element.flag === 'sbar'">SBAR PERAWAT</span>
                                <span v-if="riwayat.element.flag === 'sbarapt'">SBAR APOTEKER</span>
                                <span v-if="riwayat.element.flag === 'gizi'">ADIME</span>
                            </div>
                            <div class="is-7 column text-center text-bold">
                                <span>Tanggal: {{ H.formatDateIndo(riwayat.element.tgl) }}</span>
                            </div>
                          </div>
                      </template>
                      <template #subtitle>
                        <span style="font-weight: bolder;">{{ riwayat.element.tenagaMedis ? riwayat.element.tenagaMedis.label : '' }}</span>
                      </template>
                      <template #content v-if="riwayat.element.flag !== 'sbar' && riwayat.element.flag !== 'sbarapt' && riwayat.element.flag !== 'sbardokter' && riwayat.element.flag !== 'gizi'">
                        <table style="width: 100%">
                          <tr style="border-bottom: 1px solid #ddd">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyToClipboardS(riwayat.element.S)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">S</td>
                            <td style="width:1%">:</td>
                            <td>{{ riwayat.element.S }}</td>
                          </tr>
                          <tr style="border-bottom: 1px solid #ddd">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyToClipboardO(riwayat.element.O)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">O</td>
                            <td style="width:1%">:</td>
                            <td>{{ riwayat.element.O }}</td>
                          </tr>
                          <tr v-if="riwayat.element.flag == 'dokter'" style="border-bottom: 1px solid #ddd">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyDiagnosaAssesment(riwayat.element)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">A</td>
                            <td style="width:1%">:</td>
                            <td>
                              <table style="width: 100%">
                                <tr style="border-bottom: 1px solid #ddd">
                                  <td style="width:50%">Diagnosa Dokter</td>
                                  <td style="width:50%">Tindakan Dokter</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #ddd">
                                  <td style="width:50%">
                                    <div v-for="diagnosa in riwayat.element.diagnosaDokter">
                                      {{diagnosa.jenisDiagnosa ? `${diagnosa.jenisDiagnosa.label} : ` : ''}} {{ diagnosa.keterangan ? `${diagnosa.keterangan} | ` : '' }} <span style="font-weight: bold">{{ diagnosa.diagnosaa ? diagnosa.diagnosaa.label:'' }}</span>
                                    </div>
                                  </td>
                                  <td style="width:50%">
                                    <div v-for="diagnosa in riwayat.element.diagnosaDokter9">
                                      {{ diagnosa.keterangan ? `${diagnosa.keterangan} | ` : '' }} <span style="font-weight: bold">{{ diagnosa.diagnosaa ? diagnosa.diagnosaa.label:'' }}</span>
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr v-if="riwayat.element.flag == 'perawat'" style="border-bottom: 1px solid #ddd">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyDiagnosaAssesment(riwayat.element)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">A</td>
                            <td style="width:1%">:</td>
                            <td>
                              <div>Diagnosa Keperawatan</div>
                              <div v-for="diagnosa in riwayat.element.diagnosaKep">
                                {{ diagnosa.diagnosaKeperawatan ? diagnosa.diagnosaKeperawatan.label:'' }}
                              </div>
                              {{riwayat.element.AFreeText}}
                            </td>
                          </tr>
                          <tr v-if="riwayat.element.flag != 'perawat' && riwayat.element.flag != 'dokter'">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyDiagnosaAssesment(riwayat.element)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">A</td>
                            <td style="width:1%">:</td>
                            <td>
                              {{riwayat.element.A}}
                            </td>
                          </tr>
                          <tr v-if="riwayat.element.flag !== 'perawat'" style="border-bottom: 1px solid #ddd">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyToClipboardP(riwayat.element.P)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">P</td>
                            <td style="width:1%">:</td>
                            <td>
                              {{riwayat.element.P}}
                            </td>
                          </tr>
                          <tr v-if="riwayat.element.flag == 'perawat'" style="border-bottom: 1px solid #ddd">
                            <td style="width:3%">
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyToClipboardP(riwayat.element.P)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">P</td>
                            <td style="width:1%">:</td>
                            <td>
                              <table>
                                <tr>
                                  <td>
                                    {{riwayat.element.P}}
                                  </td>
                                </tr>
                              </table>
                              <table style="width: 100%">
                                <tr style="border-bottom: 1px solid #ddd">
                                  <td>Tujuan Keperawatan</td>
                                  <td>Intervensi Keperawatan</td>
                                </tr>
                                <tr style="border-bottom: 1px solid #ddd">
                                  <td>
                                    <div v-for="diagnosa in riwayat.element.tujuanKep">
                                      {{ diagnosa.tujuanKeperawatan ? diagnosa.tujuanKeperawatan.label ? diagnosa.tujuanKeperawatan.label : diagnosa.tujuanKeperawatan:'' }}
                                    </div>
                                    {{riwayat.element.AFreeText}}
                                  </td>
                                  <td>
                                    <div v-for="diagnosa in riwayat.element.tujuanKep">
                                      {{ diagnosa.intervensiKeperawatan ? diagnosa.intervensiKeperawatan.label ? diagnosa.intervensiKeperawatan.label : diagnosa.intervensiKeperawatan :'' }}
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </td>
                          </tr>
                          <tr style="border-bottom: 1px solid #ddd">
                            <td>
                              <button
                              style="cursor: pointer; border-radius: 50%; background-color: #10375C; border: none; padding: 3px 7px; color: white; font-weight: bolder; margin: 3px 0;"
                              v-if="riwayat.element.flag == 'dokter' && kelompokUser == 'dokter'" @click="copyToClipboardI(riwayat.element.intruksiPPA)"
                              >
                              C
                            </button>
                            </td>
                            <td style="width:1%; font-weight: bolder;">I</td>
                            <td>:</td>
                            <td>{{ riwayat.element.intruksiPPA }}</td>
                          </tr>
                        </table>
                        <div class="mt-4" v-if="riwayat.element.catatanDPJP">
                          <div style="font-weight: bolder;">Komentar Dokter : </div>
                          <div>
                            {{ riwayat.element.catatanDPJP }}
                          </div>
                        </div>
                      </template>
                      <template #content v-if="riwayat.element.flag === 'sbar'">
                        <div>
                          <span style="font-weight: bolder;">S : </span>
                          <span>
                            {{ riwayat.element.SSbar }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">B : </span>
                          <span>
                            {{ riwayat.element.BSbar }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">A : </span>
                          <span>
                            {{ riwayat.element.ASbar }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">R : </span>
                          <span>
                            {{ riwayat.element.RSbar }}
                          </span>
                        </div>
                        <div class="mt-4" v-if="riwayat.element.catatanDPJP">
                          <div style="font-weight: bolder;">Komentar Dokter : </div>
                          <div>
                            {{ riwayat.element.catatanDPJP }}
                          </div>
                        </div>
                      </template>
  
                      <template #content v-if="riwayat.element.flag === 'sbarapt'">
                        <div>
                          <span style="font-weight: bolder;">S : </span>
                          <span>
                            {{ riwayat.element.SSbarapt }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">B : </span>
                          <span>
                            {{ riwayat.element.BSbarapt }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">A : </span>
                          <span>
                            {{ riwayat.element.ASbarapt }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">R : </span>
                          <span>
                            {{ riwayat.element.RSbarapt }}
                          </span>
                        </div>
                        <div class="mt-4" v-if="riwayat.element.catatanDPJP">
                          <div style="font-weight: bolder;">Komentar Dokter : </div>
                          <div>
                            {{ riwayat.element.catatanDPJP }}
                          </div>
                        </div>
                      </template>
  
                      <template #content v-if="riwayat.element.flag === 'sbardokter'">
                        <div>
                          <span style="font-weight: bolder;">S : </span>
                          <span>
                            {{ riwayat.element.SSbardokter }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">B : </span>
                          <span>
                            {{ riwayat.element.BSbardokter }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">A : </span>
                          <span>
                            {{ riwayat.element.ASbardokter }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">R : </span>
                          <span>
                            {{ riwayat.element.RSbardokter }}
                          </span>
                        </div>
                        <div class="mt-4" v-if="riwayat.element.catatanDPJP">
                          <div style="font-weight: bolder;">Komentar Dokter : </div>
                          <div>
                            {{ riwayat.element.catatanDPJP }}
                          </div>
                        </div>
                      </template>
                      <template #content v-if="riwayat.element.flag === 'gizi'">
                        <div>
                          <span style="font-weight: bolder;">A : </span>
                          <span>
                            {{ riwayat.element.AGizi }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">D : </span>
                          <span>
                            {{ riwayat.element.DGizi }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">I : </span>
                          <span>
                            {{ riwayat.element.IGizi }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">M : </span>
                          <span>
                            {{ riwayat.element.MGizi }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bolder;">E : </span>
                          <span>
                            {{ riwayat.element.EGizi }}
                          </span>
                        </div>
                        <div class="mt-4" v-if="riwayat.element.catatanDPJP">
                          <div style="font-weight: bolder;">Komentar Dokter : </div>
                          <div>
                            {{ riwayat.element.catatanDPJP }}
                          </div>
                        </div>
                      </template>
                    </Card>
                  </div>
                </div>
              </TabPanel>
            </TabView>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailOrder" title="Detail Menu Gizi" :noclose="true" size="big" actions="right"
  @close="modalDetailOrder = false">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VField label="Tanggal" class="is-rounded-select">
              <VControl class="prime-auto ">
                <div class="is-rounded is-rounded-select">
                  <Calendar v-model="item.tglinputmenu" selectionMode="single" :manualInput="true" class="w-100 is-rounded" showTime :showIcon ="true" hourFormat="24" :date-format="'yy-mm-dd'" />
                </div>
              </VControl>
            </VField>
            <VField label="Jenis Makanan" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.kategoridietmenu" :options="d_KategoryDiet" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"  />
              </VControl>
            </VField>
            <VField label="Jenis Diet">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.arrjenisdietmenu" placeholder="Jenis Diet" />
              </VControl>
            </VField>
            <VField label="Keterangan">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.keteranganlainnyamenu" placeholder="Keterangan" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField label="Menu" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.menu" :options="d_MenuGizi" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeMenu(item.menu)" />
              </VControl>
            </VField>
            <VField label="Satuan" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                <Multiselect mode="single" v-model="item.satuangizimenu" :options="d_SatuanGizi" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"  />
              </VControl>
            </VField>
            <VField label="Waktu" class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:list" fullwidth :loading="isLoadChange" class="prime-auto-select">
                <MultiSelect v-model="item.waktujadwal" display="chip" class="w-100 is-rounded" :options="d_JenisWaktu" optionLabel="label" optionValue="value" filter placeholder="Pilih Data" :maxSelectedLabels="3" disabled />
              </VControl>
            </VField>
            <VField label="Keterangan Menu">
              <VControl icon="feather:bookmark">
                <VInput type="text" v-model="item.keteranganmenu" placeholder="Keterangan Menu" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <div class="mb-3">
              <VButton v-if="!item.mapjadwalfk" icon="feather:plus" @click="saveMenuJadwal()" :loading="isLoading" class="mr-2" color="primary" raised>
                Tambah
              </VButton>
              <VButton v-if="item.mapjadwalfk" icon="feather:edit" @click="saveMenuJadwal()" :loading="isLoading" color="warning" raised>
                Ubah
              </VButton>
            </div>
            <div class="user-grid user-grid-v2">
              <DataTable :value="dataSourceJadwalMenu" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                :loading="isLoading"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
  
                <Column field="no" header="No" style="text-align: center;"></Column>
                <Column field="menu" header="Menu" :sortable="true"></Column>
                <Column field="satuangizi" header="Satuan" :sortable="true"></Column>
                <Column field="jeniswaktu" header="Jenis Waktu" :sortable="true"></Column>
                <Column field="keteranganmenu" header="Ket Menu" :sortable="true"></Column>
                <Column :exportable="false" header="Aksi" style="text-align: center;">
                  <template #body="slotProps">
                    <VIconButton type="button" icon="fas fa-edit" class="mr-3" color="warning" circle outlined raised
                      v-tooltip.top="'Ubah'" @click="editMenu(slotProps.data)" :loading="isLoading">
                    </VIconButton>
                  </template>
                </Column>
              </DataTable>
            </div>
          </div>
        </div>
      </form>
    </template>
  </VModal>

  <OverlayPanel ref="overlaypanel">
    <VIconButton type="button" icon="feather:clipboard" color="link" outlined raised class="mr-2"
      v-tooltip.top="'Riwayat Cppt'" @click="showModalRiwayatCppt(selected)" :loading="isLoading"></VIconButton>
    <VIconButton type="button" icon="feather:info" color="info" outlined raised class="mr-2"
      v-tooltip.top="'Detail'" @click="showModalDetail(selected)" :loading="isLoading"></VIconButton>
    <VIconButton type="button" icon="feather:printer" color="warning" outlined raised class="mr-2"
      v-tooltip.top="'Cetak Label'" @click="cetakLabelGizi(selected)" :loading="isLoading"></VIconButton>
    <VIconButton v-if="selected.statusordergizi == 1" type="button" icon="feather:arrow-right" color="primary" outlined raised class="mr-2"
      v-tooltip.top="'Teruskan'" @click="showModalTeruskan(selected)" :loading="isLoading"></VIconButton>
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
import Calendar from 'primevue/calendar'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Card from 'primevue/card'

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
const modalDetailRiwayatCppt: any = ref(false)
const modalDetailOrder: any = ref(false)
const riwayatCPPT : any = ref()
const order: any = ref(99)
const item: any = ref({
  aktif: true,
  filterNama : '',  
  filterDate: new Date(),
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  selected: {}
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

const modalDetailProduk = ref(false)

let ID_RUANGAN = useRoute().query.id as string

let dataStok: any = ref([])
let dataKirim: any = ref([])
let dataRiwayat: any = ref([])
let dataPasien: any = ref([])
let dataDetailOrder: any = ref([])
let dataJadwalMenu: any = ref([])
let d_Ruangan: any = ref([])
let d_RuanganRanap: any = ref([])
let d_JenisDiet: any = ref([])
let d_JenisWaktu: any = ref([])
let d_MenuGizi: any = ref([])
let d_KategoryDiet: any = ref([])
let d_SatuanGizi: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))

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
const dataSourceJadwalMenu = computed(() => {
  if (!filters.value) {
    return dataJadwalMenu.value
  }

  return dataJadwalMenu.value.filter((items: any) => {
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
  d_JenisDiet.value = response.jenisdiet.map((e: any) => { return { label: e.jenisdiet, value: e.id, default: e } })
  d_JenisWaktu.value = response.jeniswaktu.map((e: any) => { return { label: e.jeniswaktu, value: e.id, default: e } })
  d_KategoryDiet.value = response.kategorydiet.map((e: any) => { return { label: e.kategorydiet, value: e.id, default: e } })
  d_SatuanGizi.value = response.satuangizi.map((e: any) => { return { label: e.satuangizi, value: e.id, default: e } })
  // d_MenuGizi.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e.id, default: e } })
}


async function fetchPasien() {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  let namapasien = ''
    , nocm = ''
    , noreg = ''
    , search = ''
  if (item.value.qnama) namapasien = `&namapasien=${item.value.qnama}`
  if (item.value.qnoreg) noreg = `&noregistrasi=${item.value.qnoreg}`
  if (item.value.qnocm) nocm = `&nocm=${item.value.qnocm}`
  if (item.value.search) search = `&search=${item.value.search}`

  isLoading.value = true
  dataStok.value = []
  dataPasien.value = []
  const response = await useApi().get(
    '/dashboard/pasien-order-gizi?ruanganid=' + ruanganid
    + '&namapasien=' + namapasien
    + '&nocm=' + nocm
    + '&noreg=' + noreg
    + '&search=' + search
  )
  isLoading.value = false
  dataPasien.value = response.data
  dataStok.value = response.produk
}

async function fetchRiwayat() {
  let ruanganid = ''
  if (item.value.filterRuangan) { ruanganid = item.value.filterRuangan }
  let namapasien = ''
  if (item.value.filterNama) { namapasien = item.value.filterNama }
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59')
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

const fetchKirim = async () => {
  let ruanganid = item.value.filterRuangan ? `ruanganid=${item.value.filterRuangan}` : ''
  let namapasien = item.value.namapasien ? `namapasien=${item.value.namapasien}` : ''
  let tgl = item.value.filterDate ? '&tgl=' + H.formatDate(item.value.filterDate, 'YYYY-MM-DD') : ''
  isLoading.value = true
  await useApi().get(`/dashboard/riwayat-kirim-gizi?${ruanganid}${tgl}`).then((response) => {
    response.data.forEach((element: any, i: any) => {
      element.namaproduk = element.details2
    });
    dataKirim.value = response.data
    isLoading.value = false
  })
  // isLoading.value = false
  // dataKirim.value = response.data
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
  fetchPasien()
  fetchRiwayat()
  fetchKirim()

  // fetchDetail()
}
function reload() {
  fetchPasien()
  fetchKirim()
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

function showModalTeruskan(e:any) {
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

function showModalRiwayatCppt(e:any) {
  const tipeSelected = 'gizi'
  isLoading.value = true
  useApi().get(`/emr/get-emr-cppt-ranap?nocmfk=${e.nocmfk}&norec_pd=${e.norec}&collection=CatatanPerkembanganPasienTerintegrasi&tipe=${tipeSelected}`)
    .then((response:any) => {
      isLoading.value = false
      if (response && response.details.length > 0) {
        riwayatCPPT.value = {}
        response[0].details.forEach(element => {
          const tanggalTemp = H.formatDate(new Date(element.tgl), 'DD-MM-YYYY')
          if(!riwayatCPPT.value[`${tanggalTemp}`]){
            riwayatCPPT.value[`${tanggalTemp}`] = {
              value: tanggalTemp,
              title: tanggalTemp,
              content: []
            }
          }

          riwayatCPPT.value[`${tanggalTemp}`].content.push({
            element
          })
        });
        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          element.tgl = H.formatDate(new Date(element.tgl), 'YYYY-MM-DD HH:mm')
          element.tglVerifikasi = H.formatDate(new Date(element.tglVerifikasi), 'YYYY-MM-DD HH:mm')
        }
        console.log(riwayatCPPT)
        
        modalDetailRiwayatCppt.value = true
      } else {
        H.alert('info', 'Data CPPT Dokter Gizi Dan Nutrisionis belum ada.')
      }
    })
    .catch((error:any) => {
      isLoading.value = false
      H.alert('info', 'Data CPPT Dokter Gizi Dan Nutrisionis belum ada.')
    })
}

async function fetchDataMenu() {
  try {
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    let page: any = route.query.page ? route.query.page : 1

    isLoading.value = true
    let response = await useApi().get(`/gizi/get-daftar-menu-gizi?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}`)
    isLoading.value = false
    d_MenuGizi.value = response.data.map((e: any) => { return { label: e.menu, value: e.id, default: e } })
  } catch (err) {
    console.error(err)
    isLoading.value = false
  } finally {
    isLoading.value = false
  }
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

function fetchJadwamMenu() {
  isLoading.value = true
  let tglorder = H.formatDate(item.value.selected.tglorder, 'YYYY-MM-DD')
  useApi().get('gizi/get-menu-gizi-berdasarkan-kelas?tglorder=' + tglorder + '&kelasid=' + item.value.selected.objectkelasfk + '&jeniswaktuid=' + item.value.selected.objectjeniswaktufk)
    .then((response:any) => {
      isLoading.value = false
      let nomor = 1
      for (const element of response.data) {
        element.no = nomor++
      }
      dataJadwalMenu.value = response.data
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

function showModalDetail(e:any) {
  item.value.selected.objectkelasfk = e.objectkelasfk
  item.value.selected.tglorder = e.tglorder
  item.value.selected.objectjeniswaktufk = e.objectjeniswaktufk

  // Set value
  item.value.tglinputmenu = new Date()
  item.value.waktujadwal = [e.objectjeniswaktufk]
  item.value.arrjenisdietmenu = e.arrjenisdiet != null && e.arrjenisdiet != '' ? e.arrjenisdiet : 'Tidak Diet'
  dataJadwalMenu.value = []
  fetchJadwamMenu()
  modalDetailOrder.value = true
}

function editMenu(e:any) {
  item.value.menu = e.menugizifk
  item.value.kategoridietmenu = e.kategoridietfk
  item.value.keteranganmenu = e.keteranganmenu
  item.value.satuangizimenu = e.satuangizifk
  item.value.keteranganlainnyamenu = e.keteranganlainnya
  item.value.mapjadwalfk = e.mapjadwalfk
}

function changeMenu(e:any) {
  for (const element of d_MenuGizi.value) {
    if (element.value == e) {
      item.value.kategoridietmenu = element.default.kategoridietfk
      item.value.satuangizimenu = element.default.satuangizifk
    }
  }
}

function saveMenuJadwal() {
  if (!item.value.tglinputmenu) {
    H.alert('warning', 'Tanggal menu belum diisi')
    return
  }

  if (!item.value.kategoridietmenu) {
    H.alert('warning', 'Kategori diet belum diisi')
    return
  }

  if (!item.value.menu) {
    H.alert('warning', 'Menu belum diisi')
    return
  }

  if (!item.value.satuangizimenu) {
    H.alert('warning', 'Satuan gizi belum diisi')
    return
  }

  if (!item.value.waktujadwal) {
    H.alert('warning', 'Waktu gizi belum diisi')
    return
  }

  let arrMenu = [{
    menugizifk: item.value.menu,
    keteranganmenu: item.value.keteranganmenu,
    keteranganlainnya: item.value.keteranganlainnyamenu,
  }]

  let listJadwal = []
  for (const element of item.value.waktujadwal) {
    listJadwal.push({
      id: item.value.mapjadwalfk ? item.value.mapjadwalfk : '',
      tgljadwal: H.formatDate(item.value.tglinputmenu, 'YYYY-MM-DD HH:mm'),
      jeniswaktufk: element,
      kategorydietfk: item.value.kategoridietmenu,
      listmenu: arrMenu
    })
  }

  let json = {
    dataMap: listJadwal
  }

  isLoading.value = true
  useApi().post('gizi/save-map-jadwal-menu-gizi', json)
  .then((response:any) => {
      isLoading.value = false
      
      fetchJadwamMenu()
      delete item.value.mapjadwalfk
      delete item.value.kategoridietmenu
      delete item.value.arrjenisdietmenu
      delete item.value.keteranganlainnyamenu
      delete item.value.menu
      delete item.value.satuangizimenu
      delete item.value.waktujadwal
      delete item.value.keteranganmenu
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

function updateStatusTeruskan(e:any) {
  isLoading.value = true
  let json = {
    norec_so: e[0].norec_so,
    statusorder: 2
  }
  useApi().post('/dashboard/save-status-order-gizi', json)
    .then((response:any) => {
      isLoading.value = false
      fetchRiwayat()
      modalDetailGizi.value = false
    })
    .catch((error:any) => {
      isLoading.value = false
    })
}

fetchPasien()
fetchRiwayat()
fetchKirim()
fetchDataMenu()
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

</style>
