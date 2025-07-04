<template>
  <div class="food-delivery-dashboard">
    <!--Left-->
    <div class="left">
      <div class="left-header">
        <div class="header-image">
          <img src="/@src/assets/illustrations/dashboards/lifestyle/customer.png" alt=""
            style="max-width:75%; margin-left: 2rem; margin-top: 5rem;" />
        </div>
        <div class="header-meta">
          <h3>
            Dashboard Customer
            <span role="img" aria-label="Party Popper">🎉</span>
          </h3>
          <p>Selamat Datang , {{ userLogin.pegawai.namaLengkap }}</p>
        </div>
      </div>

      <div class="left-body" v-if="activeSection === 'cart' && 'is-active'">
        <div class="restaurants">
          <div class="restaurants-toolbar">
            <div class="left">
              <h3>Order Alat</h3>
            </div>
          </div>
          <div class="search-menu-rad mb-2">
            <div class="search-location-rad" style="width: 100%">
              <i class="iconify" data-icon="feather:search"></i>
              <input type="text" placeholder="Cari Nama Alat" v-model="item.searchDataAlat"
                v-on:keyup.enter="fetchDataAlat()" />
            </div>
            <VButton raised class="search-button-rad" @click="fetchDataAlat()" :loading="isLoading">
              Cari Data
            </VButton>
          </div>
          <div class="restaurants-list">
            <div class="columns is-multiline">
              <div v-for="(items, index) in dataOrder" :key="items.norec" class="column is-4">
                <div class="restaurants-list-item">
                  <img :src="'https://ulabumro.id:8000/produk/' + items.fotoproduk"
                    style="width: 300px; height: auto; max-height: 200px; object-fit: contain; border-radius: 8px; background: #f3f3f3;"
                    alt="Produk" @error.once="(event) => onceImageErrored(event, '300x200')" />
                  <div class="meta-container">
                    <div class="meta-content">
                      <h4>{{ items.namaproduk }}</h4>
                      <p>
                        <VButton type="button" icon="feather:arrow-right-circle" class="is-fullwidth mr-3" color="info"
                          @click="masukKeranjang(items)" outlined raised :loading="items.isLoading">
                          Masukkan Keranjang</VButton>
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
                        <option :value="5">6 results per page</option>
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

      <div class="left-body" v-if="activeSection === 'activity' && 'is-active'">
        <div class="restaurants">
          <div class="restaurants-toolbar">
            <div class="left">
              <h3>History Order</h3>
            </div>
          </div>
          <VCard class="text-center pt-0 pb-0 mb-3">
            <VRadio v-model="orderAlat" value='0' label="Order" name="outlined_radio" color="success" />
            <VRadio v-model="orderAlat" value='1' label="Detail Alat" name="outlined_radio" color="info" />
          </VCard>
          <div class="list-view list-view-v3" v-if="orderAlat == 0">
            <div class="search-menu-rad mb-2">
              <div class="search-location-rad" style="width: 100%">
                <i class="iconify" data-icon="feather:search"></i>
                <input type="text" placeholder="No Pendaftaran" v-model="item.searchHistoryKelompok"
                  v-on:keyup.enter="fetchHistoryOrderKelompok()" />
              </div>
              <VButton raised class="search-button-rad" @click="fetchHistoryOrderKelompok()" :loading="isLoading">
                Cari Data
              </VButton>
            </div>
            <VPlaceholderPage :class="[dataHistoryOrder.length !== 0 && 'is-hidden']" title="Tidak Ada Alat Hari Ini."
              subtitle="Silakan Pilih Tanggal" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
              <div name="list-complete" tag="div">
                <div v-for="(item, iddetail) in dataHistoryOrderKelompok" :key="iddetail">
                  <div class="list-view-item ">
                    <div class="list-view-item-inner">
                      <VAvatar size="small" picture="/images/avatars/svg/propinsi.svg" color="primary" bordered />
                      <div class="meta-left">
                        <h3>
                          {{ item.namaperusahaan }}
                        </h3>
                        <span>
                          <i aria-hidden="true" class="iconify" data-icon="feather:home"></i>
                          <span>{{ item.jenisorder }}</span>
                          <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                          <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                          <span>{{ item.tglregistrasi }}</span>
                          <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                          <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                          <span>{{ item.nopendaftaran }}</span>
                        </span>
                        <br>
                        <VTag v-if="item.jenisorder == 'repair'" color="warning" rounded>Repair</VTag>
                        <VTag v-if="item.jenisorder == 'kalibrasi'" color="info" rounded>Kalibrasi</VTag>
                        <div>
                          <VTag v-if="item.verifregiscustomer == null" label="Alat Menunggu Diverifikasi"
                            :color="'warning'" />
                          <VTag v-if="item.verifregiscustomer" label="Alat Sudah Diverifikasi" :color="'success'" />
                        </div>
                      </div>
                      <div class="meta-right flex justify-center items-center">
                        <div class="buttons">
                          <VIconButton v-tooltip.bottom.left="'Cetak AMS'" icon="feather:printer"
                            @click="cetakAmsKelompok(item)" color="warning" raised circle class="mr-2">
                          </VIconButton>
                          <VIconButton v-tooltip.bottom.left="'Download List Tools'" icon="feather:download"
                            @click="cetakAmsKelompok(item)" color="info" raised circle class="mr-2">
                          </VIconButton>
                          <VIconButton v-tooltip.bottom.left="'Detail'" label="Bottom Left" color="primary" circle
                            icon="pi pi-book" @click="getDetailVerify(item)" style="margin-right: 15px;" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <VFlexPagination v-model:current-page="currentPageHistoryKelompok.page"
              :item-per-page="currentPageHistoryKelompok.limit" :total-items="totalDataHistoryKelompok"
              :max-links-displayed="5">
              <template #before-pagination>
              </template>
              <template #before-navigation>
                <VFlex class="mr-4 mt-1" column-gap="1rem">
                  <VField>

                  </VField>
                  <VField>
                    <VControl>
                      <div class="select is-rounded">
                        <select v-model="currentPageHistoryKelompok.limit">
                          <option :value="1">1 results per page</option>
                          <option :value="5">6 results per page</option>
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

          <div class="list-view list-view-v3" v-if="orderAlat == 1">
            <div class="search-menu-rad mb-2">
              <div class="search-location-rad" style="width: 100%">
                <i class="iconify" data-icon="feather:search"></i>
                <input type="text" placeholder="Cari Nama Alat, Merk/Tipe, Serial Number, No order Alat, No Pendaftaran"
                  v-model="item.searchHistory" v-on:keyup.enter="fetchHistoryOrder()" />
              </div>
              <VButton raised class="search-button-rad" @click="fetchHistoryOrder()" :loading="isLoading">
                Cari Data
              </VButton>
            </div>
            <VPlaceholderPage :class="[dataHistoryOrder.length !== 0 && 'is-hidden']" title="Tidak Ada Alat Hari Ini."
              subtitle="Silakan Pilih Tanggal" larger>
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderPage>
            <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
              <div name="list-complete" tag="div">
                <div v-for="(item, rowIndex) in dataHistoryOrder" :key="rowIndex">
                  <div v-if="rowGroupMetadataHistory[item.jenisorder].index === rowIndex">
                    <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                      item.jenisorder }}</span>
                    <Badge :value="rowGroupMetadataHistory[item.jenisorder].size"
                      v-if="rowGroupMetadataHistory[item.jenisorder].size > 0" class="ml-2 mt-2-min" />
                  </div>
                  <div class="list-view-item ">
                    <div class="list-view-item-inner">
                      <VAvatar size="small" picture="/images/avatars/svg/propinsi.svg" color="primary" bordered />
                      <div class="meta-left">
                        <h3>
                          {{ item.namaproduk }}
                        </h3>
                        <span>
                          <i aria-hidden="true" class="iconify" data-icon="feather:home"></i>
                          <span>{{ item.namaperusahaan }}</span>
                          <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                          <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                          <span>{{ item.tglregistrasi }}</span>
                          <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                          <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                          <span>{{ item.nopendaftaran }}</span>
                          <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                          <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                          <span>{{ item.noorderalat }}</span>
                        </span>
                        <div>
                          <VTag v-if="item.verifregiscustomer == null" label="Alat Menunggu Diverifikasi"
                            :color="'warning'" class="ml-2" />
                          <VTag v-if="item.verifregiscustomer" label="Alat Sudah Diverifikasi" :color="'success'"
                            class="ml-2" />
                        </div>
                        <div>
                          <span style="font-weight: bold;">Merk/Tipe :
                            {{ item.namamerk ?? '-' }} - {{ item.namatipe }}
                          </span>
                        </div>
                        <div>
                          <span style="font-weight: bold;">SN :
                            {{ item.namaserialnumber ?? '-' }}
                          </span>
                        </div>
                      </div>
                      <div class="meta-right flex justify-center items-center">
                        <div class="buttons">
                          <VIconButton v-tooltip.bottom.left="'Cetak AMS'" icon="feather:printer"
                            @click="cetakAms(item)" color="warning" raised circle class="mr-2">
                          </VIconButton>
                          <VIconButton v-tooltip.bottom.left="'Download List Tools'" icon="feather:download"
                            @click="downloadTools(item)" color="info" raised circle class="mr-2">
                          </VIconButton>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <VFlexPagination v-model:current-page="currentPageHistory.page" :item-per-page="currentPageHistory.limit"
              :total-items="totalDataHistory" :max-links-displayed="5">
              <template #before-pagination>
              </template>
              <template #before-navigation>
                <VFlex class="mr-4 mt-1" column-gap="1rem">
                  <VField>

                  </VField>
                  <VField>
                    <VControl>
                      <div class="select is-rounded">
                        <select v-model="currentPageHistory.limit">
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
    </div>
    <div class="right fixed-parent">
      <div class="sticky-panel fixed-child">
        <div class="widget icon-toolbar-widget">
          <div class="icon-toolbar">
            <div class="toolbar-icon">
              <a style="width: 150px;" class="inner-icon" :class="[activeSection === 'cart' && 'is-active']"
                tabindex="0" @keydown.space.prevent="activeSection = 'cart'" @click="activeSection = 'cart'">
                <i aria-hidden="true" class="iconify" data-icon="feather:shopping-cart"></i>
                <span class="badge2 badge2-danger pulsate">{{ dataKeranjang.length }}</span>
              </a>
            </div>
            <div class="toolbar-icon">
              <a style="width: 150px;" class="inner-icon" :class="[activeSection === 'activity' && 'is-active']"
                tabindex="0" @keydown.space.prevent="activeSection = 'activity'" @click="activeSection = 'activity'">
                <i aria-hidden="true" class="iconify" data-icon="feather:activity"></i>
                <span class="badge2 badge2-danger pulsate">{{ totalDataOrder }}</span>
              </a>
            </div>
            <!-- <div class="toolbar-icon">
              <a class="inner-icon" :class="[activeSection === 'address' && 'is-active']" tabindex="0"
                @keydown.space.prevent="activeSection = 'address'" @click="activeSection = 'address'">
                <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i>
              </a>
            </div>
            <div class="toolbar-icon">
              <a class="inner-icon" :class="[activeSection === 'settings' && 'is-active']" tabindex="0"
                @keydown.space.prevent="activeSection = 'settings'" @click="activeSection = 'settings'">
                <i aria-hidden="true" class="iconify" data-icon="feather:settings"></i>
              </a>
            </div> -->
          </div>
        </div>

        <div class="widget cart-widget side-section" :class="[activeSection === 'cart' && 'is-active']">
          <div class="widget-toolbar">
            <div class="left">
              <h3 class="is-bigger">Keranjang Saya</h3>
            </div>
            <div class="right">
              <span class="tag is-curved">{{ dataKeranjang.length }}</span>
            </div>
          </div>
          <VPlaceholderSection v-if="isLoading" title="No Items"
            subtitle="Your cart is currently empty. Start adding products.">
            <template #image>
              <img class="light-image" src="/@src/assets/illustrations/dashboards/food/cart-placeholder.svg" alt="" />
              <img class="dark-image" src="/@src/assets/illustrations/dashboards/food/cart-placeholder.svg" alt="" />
            </template>
          </VPlaceholderSection>
          <div class="cart-items has-slimscroll" v-else>
            <div class="is-flex is-align-items-center mb-3" style="gap: 1rem; justify-content: space-between;">
              <VCheckbox v-model="selectAll" label="Pilih semua" color="info" class="m-0 p-0" />
              <VIconButton type="button" @click="hapusKeranjangSelected" raised circle v-tooltip-prime.bottom="'Hapus'"
                outlined icon="feather:trash" color="danger" />
            </div>
            <div v-for="(items, rowIndex) in dataKeranjang" :key="rowIndex">
              <template v-if="rowGroupMetadata[items.jenisorder]?.index === rowIndex">
                <div class="has-text-weight-semibold has-text-grey is-uppercase mt-3 mb-2" style="font-size: 13px;">
                  {{ items.jenisorder }}
                </div>
              </template>
              <div class="is-flex is-align-items-center mb-2 ml-2" style="gap: 0.75rem; padding-left: 0.5rem;">
                <VCheckbox v-model="items.checked" color="info" class="m-0 p-0" />
                <VAvatar picture="/demo/icon-registrasi.png" size="small" squared />
                <div class="meta" style="line-height: 1.3;">
                  <div class="has-text-weight-medium is-size-7">
                    {{ items.namaproduk }}
                  </div>
                  <div class="is-size-7 has-text-grey">x 1</div>
                </div>
              </div>
            </div>
          </div>
          <div class="cart-button">
            <div class="total">
              <span class="label">Total Dipilih</span>
              <span>{{ selectedItems.length }}</span>
            </div>
            <VButton color="primary" raised bold fullwidth @click="simpanCheckout(selectedItems)">
              Checkout
            </VButton>
          </div>
        </div>

        <div class="side-section" :class="[activeSection === 'activity' && 'is-active']">
          <UIWidget class="followers-widget">
            <template #header>
              <div class="mb-3">
                <span><b>Alat</b></span>
              </div>
            </template>
            <template #body>
              <div class="channels is-flex is-align-items-center" style="gap: 2.5rem;">
                <div class="channel">
                  <div class="channel-icon">
                    <VIconButton type="button" raised v-tooltip-prime.bottom="'Total Alat'" outlined icon="feather:tool"
                      color="info" />
                  </div>
                  <div class="channel-stats">
                    <span>{{ totalDataOrder }}</span>
                  </div>
                </div>
                <div class="channel">
                  <div class="channel-icon">
                    <VIconButton type="button" raised v-tooltip-prime.bottom="'Alat Diverifikasi'" icon="feather:check"
                      color="success" />
                  </div>
                  <div class="channel-stats">
                    <span>{{ countDiverifikasi }}</span>
                  </div>
                </div>
                <div class="channel">
                  <div class="channel-icon">
                    <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Alat Belum Diverifikasi'" outlined
                      icon="feather:clock" color="warning" />
                  </div>
                  <div class="channel-stats">
                    <span>{{ countBelumDiverifikasi }}</span>
                  </div>
                </div>
              </div>
            </template>
          </UIWidget>

          <div class="widget illustration-widget">
            <div class="column is-12">
              <div class="dashboard-card">
                <div class="card-head">
                  <h3 class="dark-inverted">Persentase Verifikasi</h3>
                </div>
                <div class="radial-wrap">
                  <ApexChart id="goal-gauge" :height="gaugeOptions.chart.height" :type="gaugeOptions.chart.type"
                    :series="gaugeOptions.series" :options="gaugeOptions">
                  </ApexChart>
                </div>
              </div>
            </div>
          </div>


        </div>

        <div class="side-section" :class="[activeSection === 'address' && 'is-active']">
          <UIWidget class="text-widget">
            <template #header>
              <UIWidgetToolbarIcon title="Deliver to" icon="feather:map-pin" />
            </template>
            <template #body>
              <div class="widget-content">
                <p>Erik Kovalsky</p>
                <p>38, Suite B2 Parkman Avenue</p>
                <p>Los Angeles, CA</p>
              </div>
            </template>
          </UIWidget>

          <ContactWidget picture="/images/avatars/svg/vuero-1.svg" username="Erik K." email="erikkovalsky@vuero.io"
            company="Vuero Ltd." position="Product Manager" location="Los Angeles, CA" phone="+1 444-5156" squared
            reversed />
        </div>

        <div class="side-section" :class="[activeSection === 'settings' && 'is-active']">
          <UIWidget class="icon-list-widget">
            <template #body>
              <UIWidgetIconList :list="iconList" />
            </template>
          </UIWidget>
        </div>
      </div>
    </div>
  </div>
  <VModal :open="modalKeranjang" title="Masukkan Keranjang" noclose size="medium" actions="right"
    @close="modalKeranjang = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="business-dashboard hr-dashboard">
        <div class="columns is-multiline">
          <div class="column is-12 p-0">
            <div class="block-header">
              <div class="left column is-12 p-0">
                <div class="current-user">
                  <h3>{{ item.namaproduk }}</h3>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <div class="column is-12 p-4 mt-5">
        <Fieldset legend="Edit Tindakan" :toggleable="true">
          <div class="columns pl-3">
            <div class="column is-12 ml-5">
              <div class="columns">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VLabel>Merk Alat</VLabel>
                      <VControl>
                        <AutoComplete v-model="item.merkalat" :suggestions="d_merk" @complete="fetchmerk($event)"
                          optionLabel="label" :dropdown="true" :minLength="3" class="is-input" appendTo="body"
                          loadingIcon="pi pi-spinner" field="label" placeholder="ketik untuk mencari..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VLabel>Tipe Alat</VLabel>
                      <VControl>
                        <AutoComplete v-model="item.tipealat" :suggestions="d_tipe" @complete="fetchtipe($event)"
                          optionLabel="label" :dropdown="true" :minLength="3" class="is-input" appendTo="body"
                          loadingIcon="pi pi-spinner" field="label" placeholder="ketik untuk mencari..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VLabel>Serial Number</VLabel>
                      <VControl>
                        <AutoComplete v-model="item.serialnumber" :suggestions="d_sn"
                          @complete="fetchserialnumber($event)" optionLabel="label" :dropdown="true" :minLength="3"
                          class="is-input" appendTo="body" loadingIcon="pi pi-spinner" field="label"
                          placeholder="ketik untuk mencari..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <span class="label-pengkajian">Jenis Order</span>
                    <div class="columns is-multiline p-3">
                      <div class="column is-6">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.jenisorderAlat" true-value="kalibrasi" label="Kalibrasi"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-6">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.jenisorderAlat" true-value="repair" label="Reapir" class="p-0"
                              color="primary" square />
                          </VControl>
                        </VField>
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
      <VButton icon="feather:save" @click="saveKeranjang(item)" color="info" :loading="isLoading" raised>
        Masukkan Keranjang
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalCheckout" title="Checkout" noclose size="medium" actions="right"
    @close="modalCheckout = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column is-12 p-4 mt-5">
        <Fieldset legend="Edit Tindakan" :toggleable="true">
          <div class="columns pl-3">
            <div class="column is-12">
              <div class="columns">
                <div class="columns is-multiline">
                  <div class="column is-6" v-if="!hanyaRepair">
                    <VField>
                      <VLabel class="required-field">Lokasi Kalibrasi/Repair</VLabel>
                      <VControl fullwidth class="prime-auto ">
                        <AutoComplete v-model="item.lokasi" :suggestions="d_lokasikalibrasi"
                          @complete="fetchlokasiKalibrasi($event)" :optionLabel="'label'" :dropdown="true"
                          :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                          :field="'label'" placeholder="ketik untuk mencari..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12" v-if="hanyaRepair">
                    <VField>
                      <VLabel class="required-field">Lokasi Repair</VLabel>
                      <VControl fullwidth class="prime-auto ">
                        <AutoComplete v-model="item.lokasi" :suggestions="d_lokasikalibrasi"
                          @complete="fetchlokasiKalibrasi($event)" :optionLabel="'label'" :dropdown="true"
                          :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                          :field="'label'" placeholder="ketik untuk mencari..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6" v-if="!hanyaRepair">
                    <VField>
                      <VLabel>Paket Kalibrasi</VLabel>
                      <VControl fullwidth class="prime-auto ">
                        <AutoComplete v-model="item.paketkalibrasi" :suggestions="d_paketkalibrasi"
                          @complete="fetchpaketKalibrasi($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                          class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                          placeholder="ketik untuk mencari..." />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12">
                    <VField>
                      <VLabel>Catatan</VLabel>
                      <VControl fullwidth>
                        <VTextarea class="textarea" v-model="item.catatan" rows="4"
                          placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                          spellcheck="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-12" v-if="!hanyaRepair">
                    <span class="label-pengkajian required-field"> Rentang Ukur</span>
                    <div class="columns is-multiline p-3">
                      <div class="column is-3">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.rentangUkur" true-value="standarLab" label="Standar Lab"
                              class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.rentangUkur" true-value="permintaanPelanggan"
                              label="Permintaan Pelanggan" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-4">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox v-model="item.rentangUkur" true-value="lainLain" label="Lain-Lain" class="p-0"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12" v-if="item.rentangUkur == 'permintaanPelanggan'">
                        <VField>
                          <VTextarea rows="2" placeholder="Permintaan Pelanggan......"
                            v-model="item.rentangUkurketPermintaanPelanggan">
                          </VTextarea>
                        </VField>
                      </div>
                    </div>
                  </div>
                  <span class="label-pengkajian required-field ml-4"> Upload AMS</span>
                  <div class="column is-12">
                    <FileUpload v-model="fileCustomer" mode="advanced" name="demo" accept="application/pdf"
                      :maxFileSize="10000000" outlined
                      :invalidFileTypeMessage="'{0}: File yang diupload harus JPEG/JPG.'"
                      :invalidFileSizeMessage="'Ukuran maksimal berkas adalah {1}'"
                      style="background-color: transparent; color: var(--danger); border: 1px solid;"
                      :chooseLabel="fileCustomer ? fileCustomer.name : 'Unggah'" @select="onSelect($event)"
                      class="is-rounded w-100" />
                  </div>
                  <span class="label-pengkajian ml-4"> Upload List Tools</span>
                  <div class="column is-12">
                    <FileUpload v-model="fileCustomerTools" mode="advanced" name="demo"
                      accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                      :maxFileSize="10000000" outlined
                      :invalidFileTypeMessage="'{0}: File yang diupload harus JPEG/JPG.'"
                      :invalidFileSizeMessage="'Ukuran maksimal berkas adalah {1}'"
                      style="background-color: transparent; color: var(--danger); border: 1px solid;"
                      :chooseLabel="fileCustomerTools ? fileCustomerTools.name : 'Unggah'"
                      @select="onSelectTools($event)" class="is-rounded w-100" />
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="checkoutSelected(item)" color="info" :loading="isLoading" raised>
        Simpan
      </VButton>
    </template>
  </VModal>
  <Dialog v-model:visible="statusCustomer" :modal="true" :closable="false" :draggable="false" :dismissableMask="false"
    :showHeader="false" :transitionOptions="null" :breakpoints="{}" :style="{ padding: '0', margin: '0' }"
    contentStyle="background: transparent; box-shadow: none; padding: 0;" class="loading-dialog load-search">
    <div class="loading-content"
      style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; width: 100vw;">
      <p style="font-size: 20pt; font-weight: bold; color: black;" class="mt-4">
        Lengkapi data Anda terlebih dahulu...
      </p><br>
      <div class="columns">
        <div class="columns is-multiline">
          <div class="column is-6">
            <p style="font-size: 9pt; font-weight: bold; color: black;" class="mt-4">
              Penanggung Jawab Unit
            </p>
            <VField>
              <VControl>
                <AutoComplete v-model="item.penanggungjawabunit" :suggestions="d_unit" @complete="fetchUnit($event)"
                  optionLabel="label" :dropdown="true" :minLength="3" class="is-input" appendTo="body"
                  loadingIcon="pi pi-spinner" field="label" placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <p style="font-size: 9pt; font-weight: bold; color: black;" class="mt-4">
              Jabatan
            </p>
            <VField>
              <VControl fullwidth>
                <VInput v-model="item.jabatanpenanggungjawab" placeholder="Jabatan Penanggung Jawab" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12" style="text-align: center;">
            <VButton icon="feather:save" @click="saveStatusCustomer(item)" color="success" :loading="isLoading" raised>
              Simpan
            </VButton>
          </div>
        </div>
      </div>
    </div>
  </Dialog>
</template>
<script setup lang="ts">
import type { TinySliderInstance } from 'tiny-slider/src/tiny-slider'
import { tns } from 'tiny-slider/src/tiny-slider'
import { ref, onMounted, onUnmounted, watch, reactive, computed } from 'vue'
import { useUserSession } from '/@src/stores/userSession'
import FoodWidget from '/@src/assets/illustrations/dashboards/food/widget.svg'
import { useRoute, useRouter } from 'vue-router'
import * as foodDelivery from '/@src/data/dashboards/food-delivery'
import { followersStats } from '/@src/data/widgets/ui/followers'
import { iconList } from '/@src/data/widgets/ui/menuList'
import { onceImageErrored } from '/@src/utils/via-placeholder'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import AutoComplete from 'primevue/autocomplete'
import * as H from '/@src/utils/appHelper'
import Dialog from 'primevue/dialog';
import FileUpload from 'primevue/fileupload';
import ApexChart from 'vue3-apexcharts'
import { useThemeColors } from '/@src/composable/useThemeColors'

useHead({
  title: 'Dashboard Customer - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
const activeSection = ref('cart')
const route = useRoute()
const router = useRouter()
const userLogin = useUserSession().getUser()
let slider: TinySliderInstance
const sliderElement = ref<HTMLElement>()
const nextButtonElement = ref<HTMLElement>()
const prevButtonElement = ref<HTMLElement>()
const item: any = ref({})
let isLoading: any = ref(false)
const currentPage = ref({
  page: 1,
  limit: 6,
  rows: 50,
})
const currentPageHistory = ref({
  page: 1,
  limit: 5,
  rows: 50,
})

const currentPageHistoryKelompok = ref({
  page: 1,
  limit: 5,
  rows: 50,
})
const themeColors = useThemeColors()
const statusCustomer: any = ref()
const dataCustomer: any = ref()
const dataOrder: any = ref(0)
const dataHistoryOrderKelompok: any = ref(0)
const dataHistoryOrder: any = ref(0)
const countDiverifikasi: any = ref(0)
const totalDataOrder: any = ref(0)
const countBelumDiverifikasi: any = ref(0)
const dataKeranjang = ref<any[]>([])
let totalData: any = ref(0)
let totalDataHistory: any = ref(0)
let totalDataHistoryKelompok: any = ref(0)
let modalKeranjang: any = ref(false)
let modalCheckout: any = ref(false)
let hanyaRepair: any = ref(false)
const d_paketkalibrasi = ref([])
const d_unit = ref([])
const d_merk = ref([])
const d_tipe = ref([])
const d_sn = ref([])
const d_lokasikalibrasi = ref([])
const selectedItems = computed(() =>
  dataKeranjang.value.filter((item: any) => item.checked)
)
const selectAll = ref(false)
const rowGroupMetadata = ref<Record<string, { index: number; size: number }>>({})
const rowGroupMetadataHistory = ref<Record<string, { index: number; size: number }>>({})
const fileCustomer: any = ref()
const fileCustomerTools: any = ref()
const orderAlat: any = ref(0)

const fetchStatusCustomer = async () => {
  await useApi().get(`customer/get-status-customer`).then((response) => {
    statusCustomer.value = response.data.isuserbaru
    dataCustomer.value = response.data
  }).catch((err) => {
  })
  isLoading.value = false
}

const percentVerif = computed(() => {
  if (totalDataOrder.value === 0) return 0
  return Math.round((countDiverifikasi.value / totalDataOrder.value) * 100)
})

const gaugeOptions = computed(() => ({
  series: [percentVerif.value],
  chart: {
    height: 270,
    type: 'radialBar',
    offsetY: -10,
  },
  colors: [themeColors.accent],
  plotOptions: {
    radialBar: {
      startAngle: -135,
      endAngle: 135,
      dataLabels: {
        name: {
          show: true,
          fontSize: '18px',
          fontWeight: 700,
          offsetY: -10,
          color: themeColors.accent,
          formatter: function () {
            return "Diverifikasi"
          }
        },
        value: {
          show: true,
          fontWeight: 600,
          color: themeColors.lightText,
          fontSize: '24px',
          offsetY: -5,
          formatter: function (val) {
            return val + "%";
          }
        }
      },
      hollow: {
        margin: 15,
        size: '75%',
      },
      track: {
        strokeWidth: '100%',
      },
    },
  },
  stroke: {
    lineCap: 'round',
  },
  labels: ['Diverifikasi'],
}))


const hapusKeranjangSelected = async () => {
  const itemsToDelete = selectedItems.value
  if (itemsToDelete.length === 0) {
    H.alert('warning', 'Pilih minimal satu item yang akan dihapus dari keranjang')
    return
  }

  isLoading.value = true
  try {
    const norecArray = itemsToDelete.map((item: any) => item.norec)
    await useApi().post(`/customer/hapus-keranjang-customer`, {
      norec: norecArray
    })
    fetchKeranjangCustomer()
  } catch (e: any) {
    console.clear()
    console.log(e)
  } finally {
    isLoading.value = false
  }
}

const saveStatusCustomer = async (e: any) => {
  if (!item.value.penanggungjawabunit) { H.alert('warning', 'Unit harus di isi'); return }
  if (!item.value.jabatanpenanggungjawab) { H.alert('warning', 'Jabatan Alat harus di isi'); return }

  let json = {
    'statusCustomer': {
      'mitrafk': item.value.penanggungjawabunit?.value ?? null,
      'jabatan': item.value.jabatanpenanggungjawab ?? null,
    }
  }

  isLoading.value = true
  await useApi().post(`/customer/save-status-customer`, json).then(async (response: any) => {
    isLoading.value = false
    fetchStatusCustomer()
  }).catch((e: any) => {
    isLoading.value = false
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

const onSelect = async (filez: any) => {
  const file = filez.files[0];
  if (!file) return;
  if (file.size > 10000000) {
    H.alert('error', 'Maksimal file size adalah 10 MB');
    return;
  }
  if (file.type !== "application/pdf") {
    H.alert('error', 'File yang diizinkan harus berupa PDF');
    return;
  }
  fileCustomer.value = file;
}

const onSelectTools = async (filez: any) => {
  const file = filez.files[0];
  if (!file) return;

  if (file.size > 10000000) {
    H.alert('error', 'Maksimal file size adalah 10 MB');
    return;
  }

  const allowedTypes = [
    "application/vnd.ms-excel",
    "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
  ];

  if (!allowedTypes.includes(file.type)) {
    H.alert('error', 'File harus berupa Excel (.xls atau .xlsx)');
    return;
  }

  fileCustomerTools.value = file;
};


const fetchDataAlat = async () => {
  isLoading.value = true
  let searchDataAlat = ''
  if (item.value.searchDataAlat) searchDataAlat = '&search=' + item.value.searchDataAlat
  let limit: any = currentPage.value.limit
  let page = currentPage.value.page
  let offset = (page - 1) * limit


  await useApi().get(`customer/get-alat-customer?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + searchDataAlat).then((response) => {
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
    isLoading.value = false
  }).catch((err) => {
  })
  isLoading.value = false
}

const fetchHistoryOrder = async () => {
  isLoading.value = true
  let searchHistory = ''
  if (item.value.searchHistory) searchHistory = '&search=' + item.value.searchHistory
  let limit: any = currentPageHistory.value.limit
  let page = currentPageHistory.value.page
  let offset = (page - 1) * limit


  await useApi().get(`customer/get-history-order-customer?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPageHistory.value.rows}&` + searchHistory).then((response) => {
    dataHistoryOrder.value = response.data.data
    totalDataOrder.value = response.totalData
    countDiverifikasi.value = response.countVerif
    countBelumDiverifikasi.value = response.countBelumVerif
    totalDataHistory.value = response.data.total
    response.data.data.forEach((element: any, i: any) => {
      element.no = i + 1
      let ini = element.namaproduk.split(' ')
      let init = element.namaproduk.substr(0, 2)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
    });
    isLoading.value = false
  }).catch((err) => {
  })
  isLoading.value = false
  updateRowGroupMetaDataHistory()
}

const fetchHistoryOrderKelompok = async () => {
  isLoading.value = true
  let searchHistoryKelompok = ''
  if (item.value.searchHistoryKelompok) searchHistoryKelompok = '&search=' + item.value.searchHistoryKelompok
  let limit: any = currentPageHistoryKelompok.value.limit
  let page = currentPageHistoryKelompok.value.page
  let offset = (page - 1) * limit
  const user = dataCustomer.value

  await useApi().get(`customer/get-history-order-customer-kelompok?page=${page}&offset=${offset}&limit=${limit}&mtrauser=${user.mitrafk}&rows=${currentPageHistoryKelompok.value.rows}&` + searchHistoryKelompok).then((response) => {
    dataHistoryOrderKelompok.value = response.data
    totalDataHistoryKelompok.value = response.total
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
      let ini = element.namaperusahaan.split(' ')
      let init = element.namaperusahaan.substr(0, 2)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
    });
    isLoading.value = false
  }).catch((err) => {
  })
  isLoading.value = false
}

const fetchKeranjangCustomer = async () => {
  isLoading.value = true
  let search = ''
  if (item.value.search) search = '&search=' + item.value.search

  await useApi().get(`customer/get-keranjang-customer?` + search).then((response) => {
    dataKeranjang.value = response.map((element: any, i: any) => {
      let ini = element.namaproduk.split(' ')
      let init = element.namaproduk.substr(0, 2)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }

      return {
        ...element,
        no: i + 1,
        initials: init,
        checked: false,
      }
    })
    isLoading.value = false
  })
  updateRowGroupMetaData();
}

const updateRowGroupMetaData = () => {
  rowGroupMetadata.value = {};
  if (dataKeranjang.value) {
    for (let i = 0; i < dataKeranjang.value.length; i++) {
      let rowData = dataKeranjang.value[i];
      let jenisorder = rowData.jenisorder;

      if (i == 0) {
        rowGroupMetadata.value[jenisorder] = { index: 0, size: 1 };
      }
      else {
        let previousRowData = dataKeranjang.value[i - 1];
        let previousRowGroup = previousRowData.jenisorder;
        if (jenisorder === previousRowGroup) {
          rowGroupMetadata.value[jenisorder].size++;
        }
        else {
          rowGroupMetadata.value[jenisorder] = { index: i, size: 1 };
        }
      }
    }
  }
}

const updateRowGroupMetaDataHistory = () => {
  rowGroupMetadataHistory.value = {};
  if (dataHistoryOrder.value) {
    for (let i = 0; i < dataHistoryOrder.value.length; i++) {
      let rowData = dataHistoryOrder.value[i];
      let jenisorder = rowData.jenisorder;

      if (i == 0) {
        rowGroupMetadataHistory.value[jenisorder] = { index: 0, size: 1 };
      }
      else {
        let previousRowData = dataHistoryOrder.value[i - 1];
        let previousRowGroup = previousRowData.jenisorder;
        if (jenisorder === previousRowGroup) {
          rowGroupMetadataHistory.value[jenisorder].size++;
        }
        else {
          rowGroupMetadataHistory.value[jenisorder] = { index: i, size: 1 };
        }
      }
    }
  }
}

const getDetailVerify = (e: any) => {
  router.push({
    name: 'module-customer-detail-registrasi',
    query: {
      norec_pd: e.iddetail,
    },
  })
}

watch(
  () => currentPage.value.page,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchDataAlat()
    }
  }
)
watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchDataAlat()
    }
  }
)

watch(
  () => currentPageHistory.value.page,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchHistoryOrder()
    }
  }
)
watch(
  () => currentPageHistory.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchHistoryOrder()
    }
  }
)

watch(
  () => currentPageHistoryKelompok.value.page,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchHistoryOrderKelompok()
    }
  }
)
watch(
  () => currentPageHistoryKelompok.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchHistoryOrderKelompok()
    }
  }
)

const masukKeranjang = async (e: any) => {
  console.log(e)
  modalKeranjang.value = true
  item.value.namaproduk = e.namaproduk
  item.value.idproduk = e.id
}

const saveKeranjang = async (e: any) => {
  if (!item.value.merkalat) { H.alert('warning', 'Merk Alat harus di isi'); return }
  if (!item.value.tipealat) { H.alert('warning', 'Tipe Alat harus di isi'); return }
  if (!item.value.serialnumber) { H.alert('warning', 'Serial Number harus di isi'); return }
  if (!item.value.jenisorderAlat) { H.alert('warning', 'Jenis Order harus di isi'); return }

  let json = {
    'keranjangcustomer': {
      'idalat': item.value.idproduk ?? null,
      'merkalat': item.value.merkalat?.value ?? null,
      'tipealat': item.value.tipealat?.value ?? null,
      'serialnumber': item.value.serialnumber?.value ?? null,
      'jenisorder': item.value.jenisorderAlat ?? null,
    }
  }

  isLoading.value = true
  await useApi().post(`/customer/save-keranjang-customer`, json).then(async (response: any) => {
    isLoading.value = false
    modalKeranjang.value = false
    fetchKeranjangCustomer()
  }).catch((e: any) => {
    isLoading.value = false
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

const fetchUnit = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/mitra_m?select=id,namaperusahaan&param_search=namaperusahaan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_unit.value = response
  })
}

const fetchpaketKalibrasi = async (filter: any) => {
  await useApi().get(
    `registrasi/dropdown-paket-kalibrasi?query=${filter.query}&limit=10`
  ).then((response) => {
    d_paketkalibrasi.value = response
    console.log(response)
  })
}

const fetchmerk = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/merkalat_m?select=id,namamerk&param_search=namamerk&query=${filter.query}&limit=10`
  ).then((response) => {
    d_merk.value = response
  })
}

const fetchtipe = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/tipealat_m?select=id,namatipe&param_search=namatipe&query=${filter.query}&limit=10`
  ).then((response) => {
    d_tipe.value = response
  })
}

const fetchserialnumber = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/serialnumber_m?select=id,namaserialnumber&param_search=namaserialnumber&query=${filter.query}&limit=10`
  ).then((response) => {
    d_sn.value = response
  })
}

const fetchlokasiKalibrasi = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/lokasikalibrasi_m?select=id,lokasi&param_search=lokasi&query=${filter.query}&limit=10`
  ).then((response) => {
    d_lokasikalibrasi.value = response
  })
}

const simpanCheckout = async (e: any[]) => {
  const itemsToCheckout = selectedItems.value
  if (itemsToCheckout.length === 0) {
    H.alert('warning', 'Pilih minimal satu item untuk checkout')
    return
  }
  hanyaRepair.value = e.every(item => item.jenisorder === 'repair')
  modalCheckout.value = true
}

const checkoutSelected = async (e: any) => {
  const user = dataCustomer.value
  const itemsToCheckout = selectedItems.value

  if (!e.lokasi) { H.alert('warning', 'Lokasi harus di isi'); return }
  if (!e.rentangUkur) { H.alert('warning', 'Rentang Ukur harus di isi'); return }

  const formData = new FormData()
  formData.append('fileCustomer', fileCustomer.value)
  formData.append('fileCustomerTools', fileCustomerTools.value)
  formData.append('namapenanggungjawab', user.name)
  formData.append('nomitrafk', user.mitrafk)
  formData.append('jabatanpenanggungjawab', user.jabatan)
  formData.append('catatan', e.catatan ?? null)
  formData.append('paketkalibrasi', e.paketkalibrasi?.value ?? null)
  formData.append('lokasi', e.lokasi?.value ?? null)
  formData.append('rentangUkur', e.rentangUkur)
  formData.append('rentangUkurketPermintaanPelanggan', e.rentangUkurketPermintaanPelanggan ?? null)
  formData.append('mitraregistrasidetail', JSON.stringify(itemsToCheckout))

  isLoading.value = true
  await useApi().post(`/customer/save-checkout`, formData).then(async (response: any) => {
    isLoading.value = false
    fetchKeranjangCustomer()
    fetchHistoryOrder()
    clear()
    modalCheckout.value = false
  }).catch((e: any) => {
    isLoading.value = false
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

watch(orderAlat, (newVal) => {
  if (newVal == '0') {
    fetchHistoryOrderKelompok()
  } else if (newVal == '1') {
    fetchHistoryOrder()
  }
})

watch(selectAll, (newVal) => {
  dataKeranjang.value.forEach((item) => {
    item.checked = newVal
  })
})

watch(dataKeranjang, (newItems) => {
  const allChecked = newItems.length > 0 && newItems.every((item) => item.checked)
  selectAll.value = allChecked
}, { deep: true })

const clear = () => {
  item.value.merkalat = ''
  item.value.tipealat = ''
  item.value.serialnumber = ''
  item.value.rentangUkurketPermintaanPelanggan = ''
  item.value.rentangUkur = ''
  item.value.paketkalibrasi = ''
  item.value.catatan = ''
  item.value.lokasi = ''
}

const onIndexChanged = (info: any) => {
  // direct access to info object
  const indexPrev = info.indexCached
  const indexCurrent = info.index

  // update style based on index
  info.slideItems[indexPrev].classList.remove('active')
  info.slideItems[indexCurrent].classList.add('active')
}
onMounted(() => {
  if (sliderElement.value && nextButtonElement.value && prevButtonElement.value) {
    slider = tns({
      container: sliderElement.value,
      controls: true,
      nav: false,
      mouseDrag: true,
      nextButton: nextButtonElement.value,
      prevButton: prevButtonElement.value,
      fixedWidth: 98,
      swipeAngle: false,
      items: 1,
      center: false,
      loop: true,
    })

    slider.events.on('indexChanged', onIndexChanged)
  }
})

const goTo = (index: number) => {
  if (slider) {
    slider.goTo(index)
  }
}

onUnmounted(() => {
  if (slider) {
    slider.events.off('indexChanged', onIndexChanged)
    slider.destroy()
  }
})

const cetakAmsKelompok = (e: any) => {
  if (!e.iddetail) {
    H.alert('warning', 'Data tidak valid');
    return;
  }

  H.printBlade(`registrasi/cetak-ams?norecregis=${e.iddetail}`);
};

const cetakAms = (e: any) => {
  if (!e.norec) {
    H.alert('warning', 'Data tidak valid');
    return;
  }

  H.printBlade(`registrasi/cetak-ams?norecregis=${e.norec}`);
};

const downloadTools = (item: any) => {
  const norec = item.norec;
  const token = useUserSession().token;
  // const url = `http://localhost:8000/service/registrasi/download-tools-customer?norecregis=${norec}&token=${token}`;
  const url = `https://ulabumro.id:8000/service/registrasi/download-tools-customer?norecregis=${norec}&token=${token}`;

  window.open(url, '_blank');
};

const initDashboard = async () => {
  await fetchStatusCustomer()
  await fetchKeranjangCustomer()
  await fetchDataAlat()
  await fetchHistoryOrder()
  await fetchHistoryOrderKelompok()
}

onMounted(() => {
  initDashboard()
})

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/dashboard/customer.scss';

.cart-item+.cart-item {
  border-top: 1px solid #f0f0f0;
  padding-top: 8px;
  margin-top: 8px;
}

.p-dialog.loading-dialog {
  border: none !important;
  border-radius: 0 !important;
  max-width: 100vw !important;
  max-height: 120vh !important;
}

.p-dialog.loading-dialog .p-dialog-content {
  background: transparent !important;
  box-shadow: none !important;
  padding: 0 !important;
}

.p-dialog.loading-dialog,
.p-dialog.loading-dialog.p-dialog-enter-active,
.p-dialog.loading-dialog.p-dialog-leave-active {
  animation: none !important;
  transition: none !important;
}

body>.p-dialog-mask:has(.p-dialog.loading-dialog) {
  background-color: rgba(255, 255, 255, 0.5) !important;
  backdrop-filter: blur(8px);
  -webkit-backdrop-filter: blur(8px);
  animation: none !important;
}

.search-menu-rad {
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

  .search-location-rad,
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

  .search-button-rad {
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

.food-delivery-dashboard {
  display: flex;

  &.is-navbar {
    margin-top: 30px;

    >.right {
      .sticky-panel {
        height: calc(100% - 120px) !important;

        &.is-stretched {
          height: calc(100% - 120px);
          top: 100px;
        }
      }
    }
  }

  .left {
    width: 70%;

    .left-header {
      display: flex;
      align-items: center;
      padding: 10px;
      border-radius: 16px;
      background: var(--primary-light-30);
      font-family: var(--font);

      .header-image {
        position: relative;
        height: 150px;
        width: 280px;

        img {
          position: absolute;
          top: -40px;
          left: -30px;
          display: block;
        }
      }

      .header-meta {
        margin-left: 0;
        margin-bottom: 20px;

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.6rem;
        }

        p {
          font-weight: 400;
          color: var(--primary-dark-14);
          margin-bottom: 8px;
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

    .left-body {
      .restaurants {
        .restaurants-toolbar {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin: 20px 0;
          font-family: var(--font);

          .left {
            h3 {
              font-family: var(--font-alt);
              color: var(--dark-text);
              font-size: 1.3rem;
              font-weight: 600;
            }
          }

          .right {
            display: flex;
            justify-content: flex-end;
          }
        }
      }

      .food-pills {
        position: relative;

        .food-pills-inner {
          .food-pill {
            text-align: center;
            width: 80px;
            max-width: 80px;
            height: 170px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: 500px;
            padding: 10px;
            margin: 0 10px;
            cursor: pointer;
            transition: all 0.3s; // transition-all test

            &.is-active {
              background: var(--primary);
              border-color: var(--primary);

              .food-pill-icon {
                border-color: var(--primary);
              }

              >span {
                color: var(--smoke-white);
              }
            }

            .food-pill-icon {
              display: flex;
              justify-content: center;
              align-items: center;
              width: 60px;
              height: 80px;
              background: var(--white);
              border: 1px solid var(--fade-grey-dark-3);
              border-radius: 500px;

              img {
                display: flex;
                height: 26px;
                width: 26px;
              }
            }

            span {
              font-family: var(--font);
              font-weight: 500;
              padding-top: 12px;
              display: block;
              transition: color 0.3s;
            }
          }
        }

        .tns-slider {
          .active {
            background: var(--primary);
            border-color: var(--primary);

            .food-pill-icon {
              border-color: var(--primary);
            }

            >span {
              color: var(--smoke-white);
            }
          }
        }

        .slick-prev::before,
        .slick-next::before {
          color: var(--muted-grey);
        }

        .slick-custom {
          position: absolute;
          top: -50px;
          display: flex;
          justify-content: center;
          align-items: center;
          border: 1px solid transparent;
          width: 30px;
          height: 30px;
          background: transparent;
          border-radius: 100px;
          cursor: pointer;
          color: var(--light-text);
          transition: all 0.3s; // transition-all test
          z-index: 25;

          &.is-prev {
            right: 30px;

            i {
              position: relative;
              left: -1px;
            }
          }

          &.is-next {
            right: 0;

            i {
              position: relative;
              right: -1px;
            }
          }

          &:hover {
            border-color: var(--fade-grey-dark-4);
            background: var(--white);
            box-shadow: var(--light-box-shadow);
          }

          svg {
            height: 16px;
            width: 16px;
            color: var(--primary);
            transition: all 0.3s; // transition-all test
          }
        }
      }

      .restaurants-list {
        padding: 30px 0;

        .restaurants-list-item {
          @include vuero-l-card;

          position: relative;
          padding: 0;
          border: none;
          background: none;

          .image-container {
            position: relative;

            >img {
              display: block;
              object-fit: cover;
              border-radius: 24px;
              min-height: 180px;
              max-height: 180px;
              width: 100%;
            }

            .timer {
              position: absolute;
              bottom: 10px;
              left: 10px;
              display: flex;
              justify-content: center;
              align-items: center;
              height: 50px;
              width: 50px;
              border-radius: 12px;
              background: var(--primary);
              border: 1px solid var(--primary);
              font-family: var(--font);
              text-align: center;

              span {
                display: block;

                &:first-child {
                  font-size: 1.3rem;
                  color: var(--smoke-white);
                  font-weight: 600;
                  line-height: 1;
                }

                &:nth-child(2) {
                  font-size: 0.7rem;
                  text-transform: uppercase;
                  color: var(--primary-light-40);
                }
              }
            }
          }

          .meta-container {
            display: flex;
            align-items: center;
            padding: 5px;

            .meta-icon {
              display: flex;
              justify-content: center;
              align-items: center;
              width: 46px;
              min-width: 46px;
              height: 46px;
              max-height: 46px;
              background: var(--white);
              border: 1px solid var(--fade-grey-dark-3);
              border-radius: 500px;

              img {
                display: flex;
                height: 22px;
                width: 22px;
              }
            }

            .meta-content {
              margin-left: 8px;
              font-family: var(--font);
              line-height: 1.3;

              h4 {
                font-family: var(--font-alt);
                font-weight: 600;
                font-size: 1rem;
                color: var(--dark-text);
              }

              p {
                display: flex;
                align-items: center;

                .fa-circle {
                  font-size: 5px;
                  margin: 0 10px;
                }

                .fa-star {
                  position: relative;
                  top: -1px;
                  font-size: 12px;
                  color: #fab82a;

                  +span {
                    color: var(--dark-text);
                  }
                }
              }
            }
          }
        }
      }
    }
  }

  >.right {
    width: 30%;
    padding: 0 0 0 20px;

    .sticky-panel {
      position: fixed;
      height: calc(100% - 100px);
      transition: all 0.3s; // transition-all test
      width: 336px;

      &.is-stretched {
        height: calc(100% - 30px);
        top: 10px;
      }

      .icon-toolbar-widget {
        width: 100%;
      }

      .side-section {
        display: none;
        animation: fadeInLeft 0.5s;

        &.is-active {
          display: block;
        }
      }

      .cart-widget {
        height: calc(100% - 90px);

        .section-placeholder {
          height: calc(100% - 160px);

          img {
            max-width: 90px;
            margin: 0 auto 10px;
          }
        }

        .cart-items {
          height: calc(100% - 160px);
          border-bottom: 1px solid var(--fade-grey-dark-3);
          padding-bottom: 40px;
          overflow-y: auto;

          .cart-item {
            align-items: center;

            .price {
              margin: 0;
              font-size: 0.9rem;
              font-weight: 500;
            }
          }
        }

        .cart-button {
          .total {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 5px;

            span {
              font-family: var(--font);
              margin: 0;

              &:first-child {
                font-size: 1rem;
                font-weight: 500;
                color: var(--light-text);
                text-transform: uppercase;
              }

              &:nth-child(2) {
                color: var(--dark-text);
                font-weight: 600;
                font-size: 1.4rem;
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .food-delivery-dashboard {
    .left {
      .left-header {
        background: var(--dark-sidebar-light-2) !important;

        .header-meta {
          h3 {
            color: var(--dark-dark-text);
          }

          p {
            color: var(--primary);
          }
        }
      }

      .left-body {
        .restaurants {
          .restaurants-toolbar {
            .left {
              h3 {
                color: var(--dark-dark-text);
              }
            }
          }

          .food-pills {
            .food-pills-inner {
              .food-pill {
                background: var(--dark-sidebar-light-2) !important;
                border-color: var(--dark-sidebar-light-12) !important;

                span {
                  color: var(--dark-dark-text);
                }

                &.active {
                  background: var(--primary) !important;
                  border-color: var(--primary) !important;

                  span {
                    color: var(--white);
                  }
                }

                .food-pill-icon {
                  background: var(--fade-grey-light-3);
                  border-color: var(--fade-grey-light-3);
                }
              }

              .slick-slide {
                &.slick-current {
                  background: var(--primary) !important;
                  border-color: var(--primary) !important;

                  .food-pill-icon {
                    border-color: var(--primary) !important;
                  }

                  span {
                    color: var(--smoke-white);
                  }
                }
              }

              .slick-custom {
                &:hover {
                  border-color: var(--dark-sidebar-light-2);
                  background: var(--dark-sidebar-light-2);
                  box-shadow: var(--light-box-shadow);
                }
              }
            }
          }

          .restaurants-list {
            .restaurants-list-item {
              @include vuero-card--dark;

              background: none;
              border: none;

              .image-container {
                .timer {
                  background: var(--primary);
                  border-color: var(--primary);

                  span {
                    &:nth-child(2) {
                      color: var(--primary-light-18);
                    }
                  }
                }
              }

              .meta-container {
                .meta-icon {
                  background: var(--fade-grey-light-3);
                  border-color: var(--fade-grey-light-3);
                }

                .meta-content {
                  h4 {
                    color: var(--dark-dark-text);
                  }

                  p {
                    .fa-star {
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

    .right {
      .cart-widget {
        .cart-items {
          border-color: var(--dark-sidebar-light-12) !important;
        }

        .cart-button {
          .total {
            span {
              &:first-child {
                color: var(--light-text);
              }

              &:nth-child(2) {
                color: var(--dark-dark-text);
              }
            }
          }
        }
      }
    }
  }
}

// Cart widget

.cart-widget {
  @include vuero-l-card;

  &.is-straight {
    @include vuero-s-card;
  }

  .cart-items {
    .cart-item {
      display: flex;
      margin: 8px 0;

      .meta {
        margin-left: 12px;
        display: flex;
        flex-direction: column;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            font-size: 0.9rem;
            color: var(--light-text);
          }

          &:nth-child(2) {
            color: var(--dark-text);
            margin-top: auto;
            font-weight: 600;
            font-size: 1.2rem;
          }
        }
      }
    }
  }

  .cart-button {
    padding-top: 16px;

    .button {
      min-height: 50px;
      border-radius: 10px;
    }
  }
}

.is-dark {
  .cart-widget {
    @include vuero-card--dark;

    .cart-items {
      .cart-item {
        .meta {
          span {
            &:nth-child(2) {
              color: var(--primary);
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .food-delivery-dashboard {
    flex-direction: column;

    .left,
    .right {
      width: 100%;
      padding: 0;
    }

    .left {
      .left-header {
        flex-direction: column;
        text-align: center;

        .header-image {
          img {
            left: 0;
          }
        }
      }

      .restaurants-list {
        .restaurants-list-item {
          .image-container {
            img {
              min-height: 220px !important;
              max-height: 220px !important;
            }
          }
        }
      }
    }

    .right {
      .sticky-panel {
        position: static;
        width: 100% !important;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .food-delivery-dashboard {
    flex-direction: column;

    .left,
    .right {
      width: 100%;
      padding: 0;
    }

    .left {
      .restaurants-list {
        .columns {
          display: flex;

          .column {
            min-width: 50%;
          }
        }

        .restaurants-list-item {
          .image-container {
            img {
              min-height: 220px !important;
              max-height: 220px !important;
            }
          }
        }
      }
    }

    .right {
      .sticky-panel {
        position: static;
        width: 100% !important;
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
  .food-delivery-dashboard {
    .left {
      .left-body {
        .restaurants-list {
          .restaurants-list-item {
            .image-container {
              >img {
                min-height: 140px !important;
                max-height: 140px !important;
              }
            }
          }
        }
      }
    }

    .right {
      .sticky-panel {
        max-width: 255px;
      }
    }
  }
}


.radial-wrap {
  display: flex;
  flex-direction: column;
  height: calc(100% - 44px);

  .radial-stats {
    margin-top: auto;
    display: flex;
    padding-top: 20px;
    border-top: 1px solid var(--fade-grey-dark-3);

    .radial-stat {
      width: 50%;
      text-align: center;

      &:first-child {
        border-right: 1px solid var(--fade-grey-dark-3);
      }

      span {
        display: block;

        &:first-child {
          color: var(--light-text);
          font-size: 0.9rem;
        }

        &:nth-child(2) {
          color: var(--dark-text);
          font-size: 1.3rem;
          font-weight: 600;
        }
      }
    }
  }
}
</style>
