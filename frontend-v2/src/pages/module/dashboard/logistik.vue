<template>
  <ConfirmDialog />
  <div class="business-dashboard hr-dashboard">
    <div class="columns is-multiline">
      <div class="column is-7 mt-3">
        <div class="columns is-multiline">
          <div class="block-header" style="background-color: #215E43;height: 22rem;">
            <div class="column is-6" style="padding-top: 5rem">
              <img src="/images/avatars/label/dashboard/logistik.png">
            </div>
            <div class="column is-6" style="padding-top: 6rem;">
              <span style="color:#F7F7F7"><i class="fas fa-dolly mr-3" aria-hidden="true" style="color:#F7F7F7"></i>
                Logistics</span>
              <h3 class="pt-3 pb-1 title-dash">
                Layanan Logistics</h3>
              <span style="color:#F7F7F7">Selamat Datang , {{ H.namaPegawai() }}</span>
              <VField class="column is-10  is-autocomplete-select p-0 mt-3">
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Pilih Ruangan" :searchable="true"
                    :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

      </div>

      <div class="column is-5">
        <div class="dashboard-card is-gauge" style="height: 22rem;">
          <div class="column border-custom">
            <span style="font-weight: bold; font-size: 15px">Medis Non Medis
            </span>
          </div>
          <ApexChart height="220" style="height: 220px;" type="bar" :series="chartMedisNonMedis.series"
            :options="chartMedisNonMedis">
          </ApexChart>
        </div>
      </div>
    </div>
  </div>


  <div class="columns" style="margin-top:3rem">
    <div class="column is-7 p-0">
      <VCard style="height: 58px;" />
      <VTabs class="slider-tri" slider selected="permintaan" :tabs="[
        { label: 'Permintaan', value: 'permintaan' },
        { label: 'Penerimaan', value: 'penerimaan' },
        { label: 'Distribusi', value: 'distribusi' },
      ]" style="margin-top: -57px;padding:5px">
        <template #tab="{ activeValue }">
          <p v-if="activeValue === 'permintaan'">
            <VCard
              style="border-top-right-radius: unset;padding-bottom: 0px; margin-top: -16px;border-top-left-radius: unset;border-top-style: none;margin-left: -5px;width: 47.5rem; padding-top: 0px; margin-bottom: 10px;">
              <VButton color="primary" RouterLink :to="{ name: 'module-logistik-order-barang' }" raised
                style="left: 32.6rem;top: -2.6rem;padding: 15px;font-size: 14px;"><i class="fas fa-plus mr-3 p-0"
                  aria-hidden="true"></i>Order Barang</VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VDatePicker v-model="item.qrangeDate" is-range color="pink" trim-weeks :max-date="new Date()">
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

                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="No Order" v-model="item.noorder" style="margin-right:20px" />
                </div>

                <VButton color="primary" raised class="search-button" @click="fetchDataOrder()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>

            </VCard>
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
            <!--  -->
            <div class="column is-12 p-0 pb-3" v-if="orderLength > 0" v-for="(item) in dataOrder" :key="item.id">
              <VCard>
                <div class="columns is-multiline">
                  <div class="column is-1" style="padding-top:37px">
                    <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
                  </div>
                  <div class="column is-11" style="padding-left: 23px;">
                    <div class="columns is-multiline">
                      <div class="column is-6 pb-0">
                        <label style="font-wight:400">Status</label>
                        <h3 class="field mt-1">{{ item.status }}</h3>
                      </div>
                      <div class="column is-6" style="text-align: end;">
                        <VDropdown icon="feather:more-vertical" spaced right>
                          <template #content>
                            <a role="menuitem" class="dropdown-item is-media" @click="detailPermintaan(item)">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Detail</span>
                                <span>Untuk melihat data </span>
                              </div>
                            </a>
                            <a role="menuitem" @click="kirimOrder(item)" class="dropdown-item is-media" v-if="item.status != 'Kirim Order Barang'">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Kirim</span>
                                <span>Untuk Mengirim Barang</span>
                              </div>
                            </a>
                            <a role="menuitem" class="dropdown-item is-media" @click="editOrder(item)">
                              <div class="icon">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                              </div>
                              <div class="meta">
                                <span>Edit</span>
                                <span>Untuk merubah data </span>
                              </div>
                            </a>
                            <a role="menuitem" class="dropdown-item is-media"  @click="DialogConfirmOrder(item)">
                              <div class="icon"> 
                                <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                              </div>
                              <div class="meta">
                                <span>Remove</span>
                                <span>Hapus Order</span>
                              </div>
                            </a>
                          </template>
                        </VDropdown>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <label style="font-wight:400">Unit Pengorder</label>
                        <h3 class="field mt-1">{{ item.ruanganAsal }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-wight:400">Tanggal order</label>
                        <h3 class="field mt-1">{{ item.tglOrder }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="text-align: center;">No Order</label>
                        <h3 class="field mt-1">{{ item.noorder }}</h3>
                      </div>
                    </div>
                  </div>
                  <!-- <div class="column is-11" style="padding-left: 23px;">
                    <label style="font-wight:400">Status</label>
                    <h3 class="field mt-1">{{ item.status }}</h3>
                    <div class="columns is-multiline">
                      <div class="column is-4">
                        <label style="font-wight:400">Unit Pengorder</label>
                        <h3 class="field mt-1">{{ item.ruanganTujuan }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-wight:400">Tanggal order</label>
                        <h3 class="field mt-1">{{ item.tglOrder }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="text-align: center;">No Order</label>
                        <h3 class="field mt-1">{{ item.noorder }}</h3>
                      </div>
                    </div>
                  </div> -->
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

          <p v-else-if="activeValue === 'penerimaan'">
            <VCard
              style="border-top-right-radius: unset;padding-bottom: 0px; margin-top: -16px;border-top-left-radius: unset;border-top-style: none;margin-left: -5px;width: 47.5rem; padding-top: 0px; margin-bottom: 10px;">
              <VButton color="primary" RouterLink :to="{ name: 'module-logistik-form-penerimaan-barang-suplier' }" raised
                style="left: 32.6rem;top: -2.6rem;padding: 15px;font-size: 14px;"><i class="fas fa-plus mr-3 p-0"
                  aria-hidden="true"></i>Tambah Penerimaan</VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VField class="mt-3">
                  <VDatePicker v-model="item.datePenerimaan" is-range color="pink" trim-weeks :max-date="new Date()">
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
                </VField>

                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="Struk Kirim" v-model="item.nostruk" style="margin-right:20px" />
                </div>

                <VButton color="primary" raised class="search-button" @click="fetchDataPenerimaanBarang()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>

            </VCard>
          <div v-if="dataPenerimaan.loading">
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
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>

          <div v-else class="column is-12 p-0" style="overflow: scroll;height: 44rem;">

            <div class="column is-12 p-0 pb-3" v-if="dataPenerimaan.lengthData > 0" v-for="(item) in dataPenerimaan"
              :key="item.id">
              <VCard>
                <div class="columns is-multiline">
                  <div class="column is-1" style="padding-top:37px;">
                    <VAvatar size="medium" picture="/images/avatars/svg/lab.svg" style="cursor: pointer;"
                      v-tooltip.bottom.center="'Detail'" squared bordered @click="detailPenerimaan(item)" />
                  </div>
                  <div class="column is-11" style="padding-left: 23px;">
                    <label style="font-wight:400">Rekanan</label>
                    <h3 class="field mt-1">{{ item.namarekanan }}</h3>
                    <div class="columns">
                      <div class="column is-3">
                        <label style="font-wight:400">Nomer Terima</label>
                        <h3 class="field mt-1">{{ item.nostruk }}</h3>
                      </div>
                      <div class="column is-1">
                        <label style="font-wight:400">Item</label>
                        <h3 class="field mt-1">{{ item.jmlitem }}</h3>
                      </div>
                      <div class="column is-4">
                        <label style="font-wight:400">Tanggal Diterima</label>
                        <h3 class="field mt-1">{{ item.tglDiterima }}</h3>
                      </div>
                      <div class="column">
                        <VIconButton v-tooltip.bottom.left="'Retur'" icon="fas fa-undo" color="info" raised circle
                          class="mr-2" @click="returPenerimaan(item)">
                        </VIconButton>
                        <VIconButton v-tooltip.bottom.center="'Edit Barang'" icon="feather:edit" color="warning" raised
                          circle class="mr-2" @click="editPenerimaan(item)">
                        </VIconButton>
                        <VIconButton v-tooltip.bottom.right="'Batal Kirim Barang'" icon="lnir lnir-cross-circle"
                          color="danger" raised circle class="mr-2" @click="dialogConfirmPenerimaan(item)">
                        </VIconButton>
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>

            <div v-else class="p-0">
              <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                  style="max-width: 38%;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="max-width: 38%;" />
              </div>
              <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">Data
                yang dicari tidak ada</h3>
            </div>

          </div>

          </p>

          <p v-else-if="activeValue === 'distribusi'">
            <VCard
              style="border-top-right-radius: unset;padding-bottom: 0px; margin-top: -16px;border-top-left-radius: unset;border-top-style: none;margin-left: -5px;width: 47.5rem; padding-top: 0px; margin-bottom: 10px;">
              <VButton color="primary" raised style="left: 32.6rem;top: -2.6rem;padding: 15px;font-size: 14px;" RouterLink
                :to="{ name: 'module-logistik-distribusi-barang' }"><i class="fas fa-plus mr-3 p-0"
                  aria-hidden="true"></i>Kirim Barang</VButton>
              <div class="search-menu" style="margin-bottom : 1rem;margin-top:-14px">
                <VField class="mt-3">
                  <VDatePicker v-model="item.dateDistribusi" is-range color="pink" trim-weeks :max-date="new Date()">
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
                </VField>

                <div class="search-location" style="padding-right: 0px; margin-right: -15px;">
                  <i class="iconify" data-icon="feather:code"></i>
                  <input type="text" placeholder="Struk Kirim" v-model="item.nokirim" style="margin-right:20px" />
                </div>

                <VButton color="primary" raised class="search-button" @click="fetchDataDistribusi()"
                  style="height:57px;color:whitesmoke;font-size: 14px;">
                  Cari Data </VButton>
              </div>

            </VCard>
          <div v-if="dataDistribusi.loading">
            <div v-for="key in 4" :key="key" class="column is-12">
              <VCard>
                <div class="tile-grid-item">
                  <div class="tile-grid-item-inner placeload-wrap is-flex">
                    <VPlaceloadAvatar rounded="sm" />
                    <VPlaceloadText width="70%" last-line-width="70%" class="mx-2 mt-2 ml-5" />
                    <VPlaceloadText width="70%" last-line-width="70%" class="mx-2 mt-2" />
                  </div>
                </div>
              </VCard>
            </div>
          </div>

          <div class="column is-12 p-0 m-0" v-else style="height: 46rem; overflow-y: scroll">
            <div class="column is-12 p-0 mb-4" v-for="items in dataDistribusi" :key="items.id">
              <VCard>
                <div class="column is-12 p-0">
                  <div class="columns">
                    <div class="column is-1" style="padding-top:37px">
                      <VAvatar size="medium" picture="/images/avatars/svg/lab.svg" squared bordered />
                    </div>
                    <div class="column is-10" style="cursor: pointer;" @click="detailDistribusi(items)">
                      <div class="columns">
                        <div class="column is-5">
                          <div class="column is-12 pb-0">
                            <label style="font-wight:400">Tanggal Kirim</label>
                            <h3 class="field mt-1">{{ items.tglkirim }}</h3>
                          </div>
                          <div class="column is-12">
                            <label style="font-wight:400">Ruangan Asal</label>
                            <h3 class="field mt-1">{{ items.namaruanganasal }}</h3>
                          </div>
                        </div>
                        <div class="column is-5 ml-3">
                          <div class="column is-12 pb-0">
                            <label style="font-wight:400">No Struk</label>
                            <h3 class="field mt-1">{{ items.nostruk }}</h3>
                          </div>
                          <div class="column is-12">
                            <label style="font-wight:400">Ruangan Tujuan</label>
                            <h3 class="field mt-1">{{ items.namaruangantujuan }}</h3>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="column pt-3 pl-0">
                      <VButtons>
                        <VIconButton color="warning" outlined circle icon="feather:edit"
                          v-tooltip.bottom.left="'Ubah Distribusi'" @click="editDis(items)" />
                        <VIconButton color="danger" outlined circle icon="fas fa-times"
                          v-tooltip.bottom.left="'Batal Kirim'" @click="batalKirim(items)" />
                        <VIconButton color="primary" circle outlined icon="fas fa-print"
                          v-tooltip.bottom.left="'Cetak Bukti'" />
                      </VButtons>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>


          <div v-if="dataDistribusi.lengthData == 0" class="p-0">
            <div class="column p-0 m-0" style="display: flex;justify-content: center;">
              <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                style="max-width: 38%;margin-top: 2rem;" />
              <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                style="max-width: 38%;margin-top: 2rem;" />
            </div>
            <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
              Daftar Permintaan Saat ini tidak tersedia</h3>
          </div>
          </p>

        </template>
      </VTabs>

    </div>

    <div class="column is-5 pt-0">
      <UIWidget class="search-widget">
        <template #body>
          <div class="field">
            <div class="control">
              <input v-model="item.namaproduk" class="input custom-text-filter" placeholder="Cari Persediaan..."
                v-on:keyup.enter="fetchStokProduk(item.namaproduk)" />
              <button class="searcv-button" @click="fetchStokProduk(item.namaproduk)">
                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
              </button>
            </div>
          </div>
        </template>
      </UIWidget>

      <div class="column is-multiline p-0" style="max-height:210px;overflow: auto;">
        <div v-if="dataStokProduk.loading">
          <div v-for="key in 4" :key="key" class="column is-12">
            <VCard>
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner placeload-wrap">
                  <div class="columns">
                    <div class="column is-1">
                      <VPlaceloadAvatar rounded="sm" />
                    </div>
                    <div class="column">
                      <div class="column pt-0">
                        <VPlaceload class="mx-2" width="70%" />
                      </div>
                      <div class="column pt-2 pb-0">
                        <VPlaceload class="mx-2" width="60%" />
                      </div>
                      <div class="column pb-0">
                        <VPlaceload class="mx-2" width="40%" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </div>
        <div class="column is-12 p-0 mb-2" v-for="products in dataSourcefiltered" :key="products.norec">
          <VCard>
            <div class="columns">
              <div class="column is-3 p-0 ml-3 mt-3">
                <VAvatar size="medium" picture="/images/avatars/icon/ic_generic.png" color="primary" squared bordered />
              </div>
              <div class="column p-0">
                <h3 class="field" style="font-weight: 600; margin-bottom: 0px;">{{ products.namaproduk }}</h3>
                <span class="field" style="font-weight: 300;color: var(--light-text);">Jenis : {{ products.jenis }} |
                  Stok
                  : {{ products.stok }} Pcs</span>
                <!-- <VButton color="primary" raised style="display:flex;margin-top: 8px;">Kartu Stock <i
                    class="fas fa-print ml-3" aria-hidden="true"></i></VButton> -->
              </div>
            </div>
          </VCard>
        </div>
      </div>


      <div class="dashboard-card is-gauge mt-3">
        <div class="columns is-multiline" style="margin:0px;padding:0px">
          <div class="column is-8" style="margin:0px;padding:0px">
            <h4 class="dark-inverted" style="font-weight: bold; font-size: 15px">Jumlah Permintaan tiap Ruangan dalam 1
              Bulan</h4>
          </div>
          <div class="column m-0 p-0" style="display: flex;justify-content: end;">
            <VTag color="warning" :label="dateNow" rounded elevated
              style="font-weight: 600; margin-top:10px margin-left:20px" />
          </div>
        </div>
        <ApexChart id="apex-chart-18" :height="265" :type="'donut'" :series="chartRNG.series" :options="chartRNG" />
      </div>
      <!-- <div class="dashboard-card is-gauge mt-3">
        <div class="column border-custom mb-3">
          <span style="font-weight: bold; font-size: 15px">Jumlah permintaan tiap ruangan dalam 1 Bulan
          </span>
        </div>
        <ApexChart id="apex-chart-17" :height="270" :type="'pie'" :series="chartRNG.series" :options="chartRNG">
        </ApexChart>
      </div> -->
    </div>
  </div>


  <VModal :open="modalDetailPenerimaan" title="Detail Penerimaan" size="big" actions="right"
    @close="modalDetailPenerimaan = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-2">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-3">
              <div class="mb-4">
                <label for="TGL Struk " class="labelin">No Faktur</label>
                <h3>{{ item.noFaktur }}</h3>
              </div>
            </div>

            <div class="column is-3">
              <div class="mb-4">
                <label for="No Struk" class="labelin">Supplier</label>
                <h3>{{ item.suplayer }}</h3>
              </div>
            </div>

            <div class="column is-3 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Nama Ruangan</label>
                <h3>{{ item.namaruangan }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>

      <form class="modal-form">
        <DataTable :value="sourceDetailPenerimaan" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="dataSourceDetailSuplier.loading">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" header="Nama Produk"></Column>
          <Column field="satuan" header="Satuan Standar" :sortable="true"></Column>
          <Column field="harga" header="Harga Satuan"></Column>
          <Column field="jumlah" header="Qty" :sortable="true"></Column>
          <Column field="hargadiscount" header="Diskon" :sortable="true"></Column>
          <Column field="hargappn" header="PPN"></Column>
          <Column field="total" header="Total"></Column>
          <Column field="nobatch" header="nobatch"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailPermintaan" title="Detail Permintaan" size="big" actions="right"
    @close="modalDetailPermintaan = false" cancelLabel="Tutup">
    <template #content>

      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-2">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-3">
              <div class="mb-4">
                <label class="labelin">{{ dataDetail.status == 'Terima Order Barang' ? 'Ruangan Pengirim' : 'Ruangan Pengorder'}}</label>
                <h3>{{ dataDetail.status == 'Terima Order Barang' ?  dataDetail.ruanganTujuan : dataDetail.ruanganAsal }}</h3>
              </div>
            </div>

            <div class="column is-3">
              <div class="mb-4">
                <label class="labelin">Ruangan Tujuan</label>
                <h3>{{ dataDetail.status == 'Terima Order Barang' ?  dataDetail.ruanganAsal : dataDetail.ruanganTujuan }} </h3>
              </div>
            </div>

            <div class="column is-2">
              <div class="mb-4">
                <label class="labelin">Tanggal Permintaan</label>
                <h3>{{ dataDetail.tglOrder }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-3">
              <div class="mb-4">
                <label class="labelin" style="display:block">Jenis Kirim</label>
                <h3>{{ dataDetail.jeniskirim }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>
      <form class="modal-form">
        <DataTable :value="DSPermintaan" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" :sortable="true" header="Nama Produk"></Column>
          <Column field="qtyproduk" header="Qty Produk"></Column>
          <Column field="satuanstandar" header="Satuan"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalDetailDistribusi" title="Detail Distribusi" size="big" actions="right"
    @close="modalDetailDistribusi = false" cancelLabel="Tutup">
    <template #content>

      <div class="column is-12 p-0">
        <VCard color="primary">
          <div class="columns ismultiline">
            <div class="column is-1">
              <VAvatar size="medium" picture="/images/avatars/label/dashboard/list-pending.png" squared bordered />
            </div>
            <div class="column is-1">
              <div class="mb-4">
                <label for="TGL Struk " class="labelin">TGL Struk</label>
                <h3>{{ item.tglstruk }}</h3>
              </div>
            </div>

            <div class="column is-2">
              <div class="mb-4">
                <label for="No Struk" class="labelin">No Struk</label>
                <h3>{{ item.nostruk }}</h3>
              </div>
            </div>

            <div class="column is-1" style="margin-left: -39px;">
              <div class="mb-4">
                <label for="Item" class="labelin">Item</label>
                <h3>{{ item.totalItem }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-0" style="margin-left: -32px;">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Ruang Asal</label>
                <h3>{{ item.ruanganasal }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Ruang Tujuan</label>
                <h3>{{ item.ruangantujuan }}</h3>
              </div>
            </div>

            <div class="column is-2 pl-0">
              <div class="mb-4">
                <label for="Ruang Asal" class="labelin">Petugas</label>
                <h3>{{ item.petugas }}</h3>
              </div>
            </div>

          </div>
        </VCard>
      </div>

      <form class="modal-form">
        <DataTable :value="dataSourceDetailDistribusi" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
          class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          :loading="dataSourceDetailDistribusi.loading">
          <Column field="no" header="No"></Column>
          <Column field="namaproduk" header="Nama Produk"></Column>
          <Column field="kdproduk" header="KD Produk"></Column>
          <Column field="satuanstandar" header="Satuan Standar"></Column>
          <Column field="qtyproduk" header="Qty" :sortable="true"></Column>
          <Column field="qtyprodukretur" header="Qty Retur" :sortable="true"></Column>
        </DataTable>
      </form>
    </template>
  </VModal>

  <VModal :open="modalBatalKirim" title="Batal Kirim" size="medium" actions="right" @close="modalBatalKirim = false"
    cancelLabel="Tutup">
    <template #content>

      <div class="columns is-multiline">
        <div class="column is-12">

          <div class="column is-5" style="margin-left:-1rem; margin-bottom: -2rem;">
            <img src="/images/avatars/label/dashboard/logistik.png" style="width: 80%;">
          </div>
          <div class="column is-8" style="margin-top: -9rem; margin-left: 12rem;">
            <span style="margin-bottom:1rem;font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Alasan
              Pembatalan
            </span>
            <br />

            <VField>
              <VControl>
                <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4"
                  placeholder="Alasan Pembatalan Pengiriman Barang" autocomplete="off" autocapitalize="off"
                  spellcheck="true" />
              </VControl>
            </VField>
          </div>

        </div>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:plus" color="primary" @click="saveBatal()" :loading="isLoading" raised>Simpan
      </VButton>
    </template>
  </VModal>
</template>


<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, watch } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import SpeedDial from 'primevue/speeddial';
import ConfirmDialog from 'primevue/confirmdialog'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import moment from 'moment'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'

useHead({
  title: 'Dashboard Logistik - Transmedic',
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const themeColors = useThemeColors()
const route = useRoute()
const router = useRouter()
const confirm = useConfirm()
const dataSourceDetailSuplier: any = ref([])
const DSPermintaan: any = ref([])
const dataSourceDetailDistribusi: any = ref([])
const dataDetail: any = ref([])
const modalDetailPenerimaan = ref(false)
const modalDetailPermintaan = ref(false)
const modalDetailDistribusi = ref(false)
const modalBatalKirim = ref(false)

const sourceDetailPenerimaan = ref([])

let chartRNG: any = ref({
  series: [],
})

const chartMedisNonMedis: any = ref({
  series: [],
})

const dateNow = H.formatDateToLocalString(new Date());
const userLogin = useUserSession().getUser()
let countRuangan: any = ref([])
let d_Ruangan: any = ref([])
let isData: any = ref(true)
let orderLength: any = ref()
let isOrder: any = ref(true)
let item: any = ref({
  qrangeDate: {
    start: new Date(),
    end: new Date()
  },
  datePenerimaan: {
    start: new Date(),
    end: new Date()
  },
  dateDistribusi: {
    start: new Date(),
    end: new Date()
  },
})
const order: any = ref(0)
const dataOrder: any = ref([])
const dataPenerimaan: any = ref([])
const dataDistribusi: any = ref([])
const dataStokProduk: any = ref([])
const isLoading: any = ref(false)

const filters = ref('')

const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataStokProduk.value
  }

  return dataStokProduk.value.filter((item: any) => {
    return (
      item.namaproduk.match(new RegExp(filters.value, 'i'))
    )
  })
})

const changeRuang = (e: any) => {
  fetchDataOrder()
  fetchDataPenerimaanBarang()
  fetchStokProduk()
  fetchDataDistribusi()
}

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/logistik/list-ruangan`)
  d_Ruangan.value = response.map((e: any) => {
    return { label: e.namaruangan, value: e.id, default: e }
  })
}

const fetchDataOrder = async () => {
  isOrder.value = true
  dataOrder.value.loading = true
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.qrangeDate.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.qrangeDate.end, 'YYYY-MM-DD')
  let ruanganid = item.value.filterRuangan ? `&ruangantujuanfk=${item.value.filterRuangan}` : ''
  let noorder = item.value.noorder ? `&noorder=${item.value.noorder}` : ''


  await useApi().get(`/dashboard/logistik/get-daftar-order?${tglAwal}${tglAkhir}${ruanganid}${noorder}`).then((response: any) => {
    response.daftar.forEach((element: any) => {
      let tglOrder = H.formatDateToLocalString(element.tglorder)
      let ruanganAsal = (element.namaruanganasal.length > 15) ? `${element.namaruanganasal.substr(0, 15)}...` : element.namaruanganasal
      let ruanganTujuan = (element.namaruangantujuan.length > 15) ? `${element.namaruangantujuan.substr(0, 15)}...` : element.namaruangantujuan
      let keterangan = (element.keterangan.length > 15) ? element.keterangan.substr(0, 15) + '...' : element.keterangan
      element.ruanganAsal = ruanganAsal
      element.ruanganTujuan = ruanganTujuan
      element.tglOrder = tglOrder
      element.keterangan = keterangan
    })
    orderLength.value = response.daftar.length
    dataOrder.value = response.daftar
    dataOrder.value.loading = false
  }).catch((e) => {
    dataOrder.value.loading = false
  })
}

const fetchDataPenerimaanBarang = async () => {
  dataPenerimaan.value.loading = true
  let ruanganid = item.value.filterRuangan ? `&ruangan=${item.value.filterRuangan}` : ''
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.datePenerimaan.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.datePenerimaan.end, 'YYYY-MM-DD')
  let noStruk = item.value.nostruk ? '&nostruk=' + item.value.nostruk : ''
  await useApi().get('/logistik/penerimaan-barang?' + tglAwal + tglAkhir + ruanganid + noStruk).then((response) => {
    dataPenerimaan.value.loading = false
    response.daftar.forEach((element: any) => {
      let tglDiterima = new Date(element.tglstruk).toLocaleDateString('id-ID', { year: "numeric", month: "long", day: "numeric" })
      let rekanan = (element.namarekanan.length > 22) ? `${element.namarekanan.substring(0, 23)}...` : element.namarekanan
      element.rekanan = rekanan
      element.tglDiterima = tglDiterima
    });
    dataPenerimaan.value = response.daftar
    dataPenerimaan.value.lengthData = response.daftar.length
    dataPenerimaan.value.loading = false
  })
}

const fetchDataDistribusi = async () => {
  let tglAwal = 'tglAwal=' + moment(item.value.dateDistribusi.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.dateDistribusi.end).format('YYYY-MM-DD')
  let noKirim = item.value.nokirim ? `&nokirim=${item.value.nokirim}` : '';
  let ruanganid = item.value.filterRuangan ? `&ruangantujuanfk=${item.value.filterRuangan}` : '';
  dataDistribusi.value.loading = true

  await useApi().get(`/dashboard/logistik/get-data-distribusi?${tglAwal}${tglAkhir}${noKirim}${ruanganid}`).then((response) => {
    response.daftar.forEach((element: any) => {
      element.tglkirim = H.formatDateToLocalString(element.tglstruk)
      element.totalItem = element.details.length
    });
    dataDistribusi.value = response.daftar
    dataDistribusi.value.loading = false
    dataDistribusi.value.lengthData = response.daftar.length
  })
}

const fetchStokProduk = async (e: any) => {
  let ruanganid = item.value.filterRuangan ? `ruangan=${item.value.filterRuangan}` : ''
  let produk = e ? `&namaproduk=${e}` : ''
  dataStokProduk.value.loading = true
  // if (item.value.filterRuangan) {
  //   ruanganid = '?ruangan=' + item.value.filterRuangan
  // }
  await useApi().get('/dashboard/logistik/get-stok-produk?' + ruanganid + produk).then(response => {
    dataStokProduk.value = response
    dataStokProduk.value.loading = false
  })
}

const fetchChartRequestByRuangan = async () => {
  await useApi().get('/dashboard/logistik/chart-request-ruangan').then((response) => {
    chartRNG.value = {
      series: response.chartRNG.count,
      chart: {
        height: 290,
        type: 'donut',
      },
      colors: [
        themeColors.accent,
        themeColors.info,
        themeColors.green,
        themeColors.purple,
        themeColors.orange,
      ],
      labels: response.chartRNG.ruangan,
      responsive: [
        {
          breakpoint: 480,
          options: {
            chart: {
              width: 280,
              toolbar: {
                show: false,
              },
            },
            legend: {
              position: 'top',
            },
          },
        },
      ],
      legend: {
        position: 'right',
        horizontalAlign: 'center',
      },
    }
  })
}

const fetchChartMedisNon = async () => {
  await useApi()
    .get(`/dashboard/logistik/chart-medis-non-medis`)
    .then((response: any) => {
      // item.value = response
      chartMedisNonMedis.value = {
        series: response.chartMedis.series,
        chart: {
          type: 'bar',
          height: 260,
          toolbar: {
            show: false,
          },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded',
          },
        },
        colors: [themeColors.accent, themeColors.info, themeColors.green, themeColors.purple],
        dataLabels: {
          enabled: false,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent'],
        },
        xaxis: {
          categories: response.chartMedis.categories,
        },
        yaxis: {
          title: {
            text: 'Jumlah',
          },
        },
        fill: {
          opacity: 1,
        },
        legend: {
          position: 'top',
          horizontalAlign: 'center',
        },
        title: {
          text: '',
          align: 'left',
        },
        tooltip: {
          // y: {
          //   formatter: formatters.asKDollar,
          // },
        },
      }
      countRuangan.value = response
      countRuangan.value.total = response.length
    })
}

const detailPermintaan = async (e: any) => {
  modalDetailPermintaan.value = true
  e.details.forEach((element: any, i: any) => {
    console.log(element)
    element.no = i + 1
  });
  DSPermintaan.value = e.details
  dataDetail.value.status = e.status
  dataDetail.value.keterangan = e.keterangan
  dataDetail.value.tglOrder = e.tglorder
  dataDetail.value.ruanganAsal = e.namaruanganasal
  dataDetail.value.ruanganTujuan = e.namaruangantujuan
  dataDetail.value.jeniskirim = e.jeniskirim
}

const detailPenerimaan = async (e: any) => {
  modalDetailPenerimaan.value = true
  e.details.forEach((element: any, i: any) => {
    element.no = i + 1
    element.harga = H.formatRp(element.hargasatuan, 'Rp.')
    element.total = H.formatRp(element.totalall, 'Rp.')
    element.hargadiscount = H.formatRp(element.hargadiskon, 'Rp.')
    element.hargappn = H.formatRp(element.nilaippn, 'Rp.')
  });
  item.value.noFaktur = e.nofaktur
  item.value.suplayer = e.namarekanan
  item.value.namaruangan = e.namaruangan
  sourceDetailPenerimaan.value = e.details
}

const detailDistribusi = (e: any) => {
  modalDetailDistribusi.value = true
  item.value.tglstruk = e.tglkirim
  item.value.totalItem = e.totalItem
  item.value.nostruk = e.nostruk
  item.value.qty = e.jmlitem
  item.value.ruanganasal = e.namaruanganasal
  item.value.ruangantujuan = e.namaruangantujuan
  item.value.petugas = e.petugas
  e.details.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  dataSourceDetailDistribusi.value = e.details
}

const clearInput = () => {
  delete item.value.keterangan
  modalBatalKirim.value = false

}

const clear = () => {
  sourceDetailPenerimaan.value = []
}

const batalKirim = (e: any) => {
  item.value.noorderfk = e.noorderfk
  item.value.norec = e.norec
  item.value.ruasid = e.ruasalid
  item.value.rutujuanid = e.rutujuanid
  item.value.jenispermintaanfk = e.jenispermintaanfk
  item.value.jmlitem = e.jmlitem
  item.value.nostruk = e.nostruk
  item.value.namaruangantujuan = e.namaruangantujuan
  item.value.namaruanganasal = e.namaruanganasal
  modalBatalKirim.value = true

}

const saveBatal = async () => {
  let param = {
    'strukkirim': {
      'noorderfk': item.value.noorderfk,
      'noreckirim': item.value.norec,
      'objectruanganasal': item.value.ruasid,
      'obejectruangantujuan': item.value.rutujuanid,
      'namaruanganasal': item.value.namaruanganasal,
      'namaruangantujuan': item.value.namaruangantujuan,
      'jenispermintaanfk': item.value.jenispermintaanfk,
      'nostruk': item.value.nostruk,
      'jmlitem': item.value.jmlitem,
      'keterangan': item.value.keterangan,
    }
  }
  isLoading.value = true
  await useApi().post('/dashboard/logistik/batal-kirim-barang', param).then((response: any) => {
    isLoading.value = false
    fetchDataDistribusi()
    clearInput()
  }, (error) => {
    isLoading.value = false
  })
}


const editDis = (e: any) => {
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      norec: e.norec,
    },
  })
}

const filter = () => {
  item.isDate = false
  fetchDataPenerimaanBarang()
}

const returPenerimaan = (e: any) => {
  router.push({
    name: 'module-logistik-retur-penerimaan-barang-suplier',
    query: {
      norec: e.norec,
    },
  })
}

const editPenerimaan = (e: any) => {
  router.push({
    name: 'module-logistik-form-penerimaan-barang-suplier',
    query: {
      norec: e.norec,
    },
  })
}

const dialogConfirmPenerimaan = (e: any) => {
  confirm.require({
    message: 'Apakah anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      batalTerima(e)
    },
    reject: () => { },
  })
}

const batalTerima = async (e: any) => {
  await useApi().post('/logistik/penerimaan-barang/delete-penerimaan-suplier', { 'nostruk': e.norec }).then((response) => {
    console.log(response)
    fetchDataPenerimaanBarang()
  }).catch((err: any) => {
    console.log(err)
  })
}

const kirimOrder = (e: any) => {
  if (e.statusorder == 'Sudah Kirim') {
    H.alert('error', 'Barang Sudah Dikirim')
    return
  }
  router.push({
    name: 'module-logistik-distribusi-barang',
    query: {
      norec_order: e.norec,
    },
  })
}

const editOrder = (e: any) => {
  router.push({
    name: 'module-logistik-order-barang',
    query: {
      norec: e.norec,
    },
  })
}

const DialogConfirmOrder = (e: any) => {
  confirm.require({
    message: 'Apakah Anda yakin menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusItems(e)

    },
    reject: () => { },
  })
}

const hapusItems = async (e: any) => {
  useApi().post(
    `/logistik/hapus-order-barang`, { norec: e.norec }).then((response: any) => {
      isLoading.value = false
      fetchDataOrder()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

fetchChartMedisNon()
fetchdDropdown()
fetchDataOrder()
fetchDataPenerimaanBarang()
fetchDataDistribusi()
fetchStokProduk();

</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/logistik.scss';

.slider-tri {
  .tabs-inner {
    margin-right: 26rem !important;
  }
}
</style>
