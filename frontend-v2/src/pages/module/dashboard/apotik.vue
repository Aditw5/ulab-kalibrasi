
<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="personal-dashboard personal-dashboard-v3">
    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="columns is-multiline is-flex-tablet-p">
          <div class="column is-6">
            <div class="dashboard-card is-welcome" style="height: 22.9rem; padding-top: 17px">
              <div class="welcome-title">
                <h3 class="dark-inverted mb-2" style="font-size: 1.9rem;text-align: center;">Dashboard Apotik</h3>
                <p class="mb-2 mt-2" style="text-align: center;">Selamat Datang di {{ ROOMNOW == '' ? 'Dashboard Apotik' :
                  ROOMNOW }}</p>
              </div>
              <div class="welcome-progress">
                <div class="meta">
                  <img src="/images/avatars/label/dashboard/apotik.png" alt="label apotik"
                    style="max-width: 21rem; opacity: 0.9; margin-left: -1.6rem" />
                </div>
              </div>
            </div>
          </div>

          <div class="column is-6">
            <div class="stats-wrapper">
              <div class="dashboard-title pb-0" style="margin-bottom: 24px;margin-bottom: 2rem;">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <VField class=" is-autocomplete-select p-0 mt-3">
                      <VControl icon="feather:search">
                        <Multiselect mode="single" v-model="item.filterRuangan" placeholder="Filter Ruangan"
                          :searchable="true" :options="d_Ruangan" @select="changeRuang(item.filterRuangan)" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6 mt-4-min">
                    <VTag @click="showModalFilter()" color="danger" rounded elevated style=" cursor:pointer">{{
                      H.formatDateToLocalString(item.periode.start) == H.formatDateToLocalString(item.periode.end) ?
                      H.formatDateToLocalString(item.periode.start) :
                      H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                        H.formatDateToLocalString(item.periode.end) : '')
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                  </div>
                  <div class="column is-6" style="margin-top:-25px">
                      <VField>
                          <VControl>
                              <VSwitchBlock v-model="item.isIter" label="Resep Iter" color="danger" />
                          </VControl>
                      </VField>
                  </div>
                </div>
              </div>

              <div class="columns is-multiline is-flex-tablet-p" style="margin-top:-30px">
                <div class="column is-6">
                  <div class="dashboard-card" style="height: 7rem;padding-top: 28px;">
                    <VBlock title="" center>
                      <span style="font-weight: bold; color: black">{{
                        countOrder.pending
                      }}</span>
                      <span style="color: var(--light-text)">Menunggu</span>
                      <template #icon>
                        <VIconBox color="warning" rounded>
                          <img src="/images/avatars/icon/ic-antrian.png" style="max-width: 72%;" />
                        </VIconBox>
                      </template>
                    </VBlock>
                  </div>
                </div>
                <div class="column is-6">
                  <div class="dashboard-card" style="height: 7rem;padding-top: 28px;">
                    <VBlock title="" center>
                      <span style="font-weight: bold; color: black">{{
                        countOrder.produksi
                      }}</span>
                      <span style="color: var(--light-text)">Produksi</span>
                      <template #icon>
                        <VIconBox color="purple" rounded>
                          <img src="/images/avatars/icon/ic-proses.png" style="max-width: 72%;" />
                        </VIconBox>
                      </template>
                    </VBlock>
                  </div>
                </div>

                <div class="column is-6">
                  <div class="dashboard-card" style="height: 7rem;padding-top: 28px;">
                    <VBlock title="" center>
                      <span style="font-weight: bold; color: black">{{
                        countOrder.success
                      }}</span>
                      <span style="color: var(--light-text)">Selesai</span>
                      <template #icon>
                        <VIconBox color="primary" rounded>
                          <img src="/images/avatars/icon/ic-diterima.png" style="max-width: 72%;" />
                        </VIconBox>
                      </template>
                    </VBlock>
                  </div>
                </div>

                <div class="column is-6">
                  <div class="dashboard-card" style="height: 7rem;padding-top: 28px;">
                    <VBlock title="" center>
                      <span style="font-weight: bold; color: black">{{
                        countOrder.allOrderNow
                      }}</span>
                      <span style="color: var(--light-text)">Total Resep</span>
                      <template #icon>
                        <VIconBox style="background: paleturquoise;" rounded>
                          <img src="/images/avatars/icon/ic-total.png" style="max-width: 72%;" />
                        </VIconBox>
                      </template>
                    </VBlock>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-4">
        <UIWidget class="search-widget">
          <template #body>
            <div class="field">
              <div class="control">
                <input v-model="filtersObat" type="text" class="input" placeholder="Cari Persediaan Obat" />
              </div>
            </div>
          </template>
        </UIWidget>

        <div class="column is-multiline p-0" style="max-height:210px;overflow: auto;">

          <div class="column is-12 p-0 mb-2" v-if="loadFinishData" v-for="(length) in 4">
            <VCard>
              <div class="columns">
                <div class="column is-2 p-0">
                  <VPlaceloadAvatar />
                </div>
                <div class="column p-0">
                  <VPlaceload class="mt-2" />
                  <VPlaceload width="50%" class="mt-2" />
                </div>
              </div>
            </VCard>
          </div>

          <div class="column is-12 p-0 mb-2" v-else v-for="(items) in SourceObatfiltered" :key="items.id">
            <VCard>
              <div class="columns">
                <div class="column is-2 p-0">
                  <VAvatar size="medium" picture="/images/avatars/icon/ic_generic.png" squared bordered />
                </div>
                <div class="column p-0">
                  <h3 class="field" style="font-weight: 600; margin-bottom: 0px;">{{ items.namaproduk + " ("
                    + items.qtyproduk + ")" }} </h3>
                  <span class="field" style="font-weight: 300;color: var(--light-text);">{{ items.namaruangan }}</span>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>

    <div class="columns is-multiline pl-0">
      <div class="column is-8 pr-5">
        <UIWidget class="search-widget mb-2">
          <template #body>
            <div class="columns is-multiline">
              <div class="column is-4 ml-4" style="padding: 0px;align-self: center;">
                <span style="font-weight: 600;font-size: 15px;">Daftar eResep</span>
              </div>
              <div class="column" style="display: flex;justify-content: end;">
                <VButton color="primary" raised RouterLink :to="{ name: 'module-farmasi-daftar-pasien-apotik' }"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah Resep</VButton>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-6">
                <VField class="is-autocomplete-select p-0">
                  <VControl icon="feather:search">
                    <Multiselect mode="single" v-model="item.filterRuanganDatas" placeholder="Filter Ruangan Pengorder"
                      :searchable="true" :options="d_datasRuangan" @select="changeRuangDatas(item.filterRuanganDatas)" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6">
                <div class="columns is-multiline field p-3">
                  <input type="text" v-model="item.search" class="input column is-10" placeholder="Search..."
                    v-on:keyup.enter="fetchDataOrder()" />
                  <VIconButton type="button" color="success" class="searcv-button column is-2 p-0" raised
                    icon="fas fa-search" @click="fetchDataOrder()" :loading="isLoading">
                  </VIconButton>
                </div>
              </div>
              <!-- <div class="column is-6">
                <div class="columns is-multiline field p-3">
                  <input type="text" v-model="filters" class="input column is-10" placeholder="Search..." />
                  <VIconButton type="button" color="success" class="searcv-button column is-2 p-0" raised
                    icon="fas fa-search" @click="fetchDataOrder()" :loading="isLoading">
                  </VIconButton>
                </div>
              </div> -->
            </div>
            <div class="field" style="text-align: center;">
              <div class="control">
                <VRadio v-model="item.statusOrder" value="" label="Semua" name="outlined_radio" color="info" />
                <VRadio v-model="item.statusOrder" value="0" label="Menunggu" name="outlined_radio" color="primary" />
                <VRadio v-model="item.statusOrder" value="5" label="Verifikasi" name="outlined_radio" color="primary" />
                <VRadio v-model="item.statusOrder" value="1" label="Produksi" name="outlined_radio" color="info" />
                <VRadio v-model="item.statusOrder" value="2" label="Packaging" name="outlined_radio" color="danger" />
                <VRadio v-model="item.statusOrder" value="4" label="Diserahkan" name="outlined_radio" color="success" />
                <VRadio v-model="item.statusOrder" value="3" label="Selesai" name="outlined_radio" color="info" />
              </div>
            </div>
          </template>
        </UIWidget>

        <div style="overflow: scroll;height: 500px;">
          <div class="column is-12 p-0 m-0" v-if="isLoadFinish">
            <div class="timeline-wrapper" v-for="(length) in 5">
              <VCard class="mb-3">
                <div class="column-is-12">
                  <div class="columns">
                    <div class="column is-1">
                      <VPlaceloadAvatar />
                    </div>
                    <div class="column is-11">
                      <VPlaceload width="50%" class="mt-1 ml-1" />
                      <div class="columns mt-3 ml-1">
                        <VPlaceload width="30%" class="mt-2 mr-3" />
                        <VPlaceload width="40%" class="mt-2  mr-3" />
                        <VPlaceload width="30%" class="mt-2" />
                      </div>
                    </div>
                  </div>
                </div>
              </VCard>
            </div>
          </div>

          <div class="column is-12 p-0 m-0" v-else>
            <div class="timeline-wrapper" v-if="dataSourcefiltered.length > 0">
              <div class="timeline-header"></div>
              <div class="timeline-wrapper-inner pt-2">
                <div class="timeline-container">
                  <div class="timeline-item is-unread " v-for="(items, i)  in dataSourcefiltered" :key="items.norec">
                    <div class="content-wrap is-grey pb-2">
                      <div class="column is-12 p-0">
                        <div class="columns">
                          <div class="column is-1 pt-0" style="margin-right: -10px;">
                            <VAvatar size="small" :color="listColor[i]" :initials="items.inisial" class="mt-5" />
                          </div>
                          <div class="column is-11 p-0">
                            <div class="column is-12">
                              <div class="columns is-multiline">
                                <div class="column is-10">
                                  <p style="display: inline; font-weight: bold;font-size:1.1em;color:black">{{
                                    items.namapasien }}</p>
                                  <p style="display: inline; font-weight: bold"> - {{ items.noidentitas }} | {{ items.nocm
                                  }} | {{ items.jeniskelamin == 'Perempuan' ? '(P)' : '(L)' }} </p> <span v-if="items.isiter === true">|</span>
                                 <VTag class="ml-1" v-if="items.isiter === true" :color="'danger'" label="RESEP ITER" />
                                </div>
                                <div class="column is-2" style="display:flex;justify-content: space-between;"
                                  v-if="items.statusorder != 'Menunggu'">

                                  <VIconButton v-tooltip.bottom.left="'Panggil '" label="Bottom Left" color="primary"
                                    circle icon="feather:phone" @click="panggilPasien(items)"
                                    v-if="items.statusorder != 'Menunggu'" :loading="items.loading" />

                                  <VDropdown style="float:right" v-if="items.statusorder != 'Menunggu'"
                                    icon="feather:more-vertical" spaced right class=" ml-1">
                                    <template #content>
                                      <div style="height: 21rem;overflow: auto;">
                                        <a role="menuitem" @click="resepIter(items)"
                                          class="dropdown-item is-media" v-if="items.isiter">
                                          <div class="icon" style="color: none;">
                                            <i class="lnir lnir-file-name" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Resep Iter</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="getDetailOrder(items.noorder, items.norec_resep)"
                                          class="dropdown-item is-media">
                                          <div class="icon" style="color: none;">
                                            <i class="lnir lnir-file-name" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Detail</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="panggilPasien(items)" class="dropdown-item is-media">
                                          <div class="icon" style="color: none;">
                                            <i class="lnir lnir-phone-ring" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Panggil</span>
                                          </div>
                                        </a>
                                        <hr class="dropdown-divider" />
                                        <a role="menuitem" @click="changeProduksi(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-reload-alt" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Produksi</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="changePackaging(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-package" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Packaging</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="changeDone(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-checkmark" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Selesai</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="showModalPenyerah(items)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="lnil lnil-reload"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Penyerahan Obat</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakNama(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak Label Nama</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakResep(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak Resep</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakResep23(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak Resep Obat Kronis</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakResepPulang(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak Resep Obat Pulang</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakLabel(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak Label</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakLabelBiru(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Label Biru</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="cetakSEP(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak SEP</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="showModalDetailVerif(items.norec_resep)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Rekap Label</span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="showModalDetailOpsi(items.norec_resep)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Cetak Label dengan Opsi</span>
                                          </div>
                                        </a>

                                        <a role="menuitem" @click="cetakNomorAntrian(items)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Nomor Antrian </span>
                                          </div>
                                        </a>
                                        <a role="menuitem" @click="showModalPilihOrder(items)"
                                          class="dropdown-item is-media">
                                          <div class="icon">
                                            <i class="lnir lnir-printer" aria-hidden="true"></i>
                                          </div>
                                          <div class="meta">
                                            <span>Copy Resep Dokter </span>
                                          </div>
                                        </a>
                                        <hr class="dropdown-divider" />
                                        <a role="menuitem" style="background: #cd001a;margin-bottom: -6px;"
                                          @click="unverifResep(items)" class="dropdown-item is-media">
                                          <div class="icon">
                                            <i aria-hidden="true" class="fas fa-times-circle"
                                              style="color: aliceblue;"></i>
                                          </div>
                                          <div class="meta">
                                            <span style="color:white">Batal Verify</span>
                                          </div>
                                        </a>
                                      </div>
                                    </template>
                                  </VDropdown>
                                </div>
                                <div class="column is-2" style="text-align:right" v-else>
                                  <VIconButton type="button" icon="fas fa-ellipsis-v" class="mr-2" color="primary" circle
                                    outlined raised v-tooltip.top="'Aksi'" @click="toggle($event, items)">
                                  </VIconButton>
                                </div>
                              </div>
                              <div class="columns is-multiline mb-0" style="margin-top:-45px">
                                <div class="column is-2 pb-0">
                                  <h1>No Order</h1>
                                  <p class="td-label-xx">{{ items.noorder }}</p>
                                  <VTag :color="'info'" :label="items.noantri" v-if="items.noantri != '-'" />
                                </div>
                                <div class="column is-3 pb-0">
                                  <h1>Tgl Order</h1>
                                  <span class="td-label-xx">{{ H.formatDateIndoSimple(items.tglorder) }}</span>
                                  <VTag :label="'SEP : ' + item.nosep" v-if="item.nosep != null" :color="'success'" />
                                </div>
                                <div class="column is-3 pb-0">
                                  <h1>Ruangan</h1>
                                  <p class="td-label-xx">{{ items.namaruanganrawat }}</p>
                                  <p class="td-label-xx">{{ items.namaruangan }}</p>
                                </div>
                                <div class="column is-3 pb-0">
                                  <h1>Dokter</h1>
                                  <p class="td-label-xx">{{ items.dokterpengorder }}</p>
                                  <h1>Pegawai Order</h1>
                                  <p class="td-label-xx">{{ items.pegawaipengorder }}</p>
                                </div>
                                <div class="column pb-0">
                                  <h1>Status</h1>
                                  <VTag :color="items.statusorder == 'Menunggu' ? 'warning' : 'success'"
                                    :label="items.statusorder" />
                                  <div v-if="items.status_order">
                                    <h1>Terdapat Obat Kronis</h1>
                                    <VTag :color="'danger'" label="Obat kronis" />
                                  </div>
                                  <div v-if="items.isreseppulang">
                                    <!-- <h1>Terdapat Obat Pulang</h1> -->
                                    <VTag :color="'info'" label="Obat Pulang" />
                                  </div>
                                </div>
                              </div>

                              <div class="column p-0" v-if="items.diagnosa.length > 0">
                                <h1>Diagnosa</h1>
                                <p class="td-label-xx" style="font-weight:600" v-for="(diagnos) in items.diagnosa">{{
                                  diagnos.diagnosa }}</p>
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
            <div v-else>
              <div class="column p-0 m-0" style="display: flex;justify-content: center;">
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt=""
                  style="max-width: 38%;margin-top: 2rem;" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                  style="max-width: 38%;margin-top: 2rem;" />
              </div>
              <h3 style="text-align: center;font-weight: 600;color: var(--dark-text);font-family: var(--font-alt);">
                Pasien Order Farmasi Saat ini tidak tersedia</h3>
            </div>
            <OverlayPanel ref="overlaypanel">
              <VIconButton type="button" icon="feather:check-circle" color="success" outlined raised class="mr-2"
                v-tooltip.top="'Verifikasi'" @click="verifikasiResep(selected)">
              </VIconButton>
              <VIconButton type="button" icon="feather:printer" color="info" outlined raised class="mr-2"
                v-tooltip.top="'Cetak Resep'" @click="cetakResep(selected)">
              </VIconButton>
              <VIconButton type="button" icon="fas fa-copy" color="black" outlined raised v-tooltip.top="'Copy Resep'"
                @click="showModalPilihOrder(selected)">
              </VIconButton>
            </OverlayPanel>
          </div>
        </div>
      </div>

      <div class="column is-4 pl-0">
        <div class="dashboard-card is-gauge">
          <div class="column border-custom mb-3">
            <span style="font-weight: bold; font-size: 15px">Order berdasarkan Ruangan
            </span>
          </div>
          <ApexChart id="apex-chart-17" :height="270" :type="'pie'" :series="chartRNG.series" :options="chartRNG">
          </ApexChart>
        </div>
      </div>
    </div>
    <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit" :total-items="totalData"
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


  <VModal :open="modalObat" size="big" noclose title="Detail Obat" actions="right" @close="modalObat = false, clear()"
    cancelLabel="Tutup">
    <template #content>
      <div class="columns">
        <div class="column is-2">

        </div>
        <div class="column is-4">
          <div class="column is-12">
            <VField label="Tanggal Order" class="ml-3">
              <VControl class="mt-2">
                <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                  style="cursor:pointer; background: var(--fade-grey);" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Tanggal Order" class="ml-3">
              <VControl class="mt-2">
                <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                  style="cursor:pointer; background: var(--fade-grey);" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Tanggal Order" class="ml-3">
              <VControl class="mt-2">
                <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                  style="cursor:pointer; background: var(--fade-grey);" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column">
          <div class="columns">
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl class="mt-2">
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns">
            <div class="column is-12">
              <label for="">Komponen Harga</label>
              <div class="is-divider" style="border-top: 3px solid hsl(0deg, 0%, 86%);margin-top: 10px;"></div>
            </div>
          </div>
          <div class="columns" style="margin-top: -47px;">
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl>
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl>
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField label="Tanggal Order" class="ml-3">
                <VControl>
                  <VInput type="text" placeholder="Tanggal Order" readonly class="is-rounded"
                    style="cursor:pointer; background: var(--fade-grey);" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

    </template>
  </VModal>

  <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks />
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

  <VModal :open="modalSerahObat" title="Penyerahan Obat" :noclose="true" size="big" actions="right"
    @close="modalSerahObat = false, clearModalSerahObat()">
    <template #content>
      <div class="columns is-multiline">
        <div class="column is-12">
          <h1 style="font-weight: bold;">Rincian :</h1>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: bold;">1. Persyaratan Administrasi</h1>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>1.1. Apakah nama, umur, jenis kelamin, berat badan dan tinggi badan pasien sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanAdministrasi1" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>1.2. Apakah nama, nomor ijin, alamat dan paraf dokter sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanAdministrasi2" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>1.3. Apakah tanggal resep sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanAdministrasi3" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>1.4. Apakah ruangan/unit asal resep sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanAdministrasi4" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: bold;">2. Persyaratan Farmasetik</h1>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>2.1. Apakah nama obat, bentuk dan kekuatan sediaan sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanFarmasetik1" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>2.2. Apakah dosis dan jumlah obat sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanFarmasetik2" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>2.3. Apakah stabilitas obat sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanFarmasetik3" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>2.4. Apakah aturan dan cara penggunaan obat sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanFarmasetik4" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: bold;">3. Persyaratan Klinis</h1>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>3.1. Apakah ketepatan indikasi, dosis, dan waktu penggunaan obat sudah sesuai?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanKlinis1" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>3.2. Apakah terdapat duplikasi pengobatan?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanKlinis2" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>3.3. Apakah terdapat alergi dan reaksi obat yang tidak dikehendaki (ROTD)?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanKlinis3" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>3.4. Apakah terdapat kontraindikasi pengobatan?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanKlinis4" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-1"></div>
            <div class="column is-7">
              <h1>3.5. Apakah terdapat dampak interaksi obat?</h1>
            </div>
            <div class="column is-4">
              <div class="columns">
                <span class="column is-6" v-for="items in listSesuai">
                  <VRadio v-model="item.persyaratanKlinis5" :value="items.value" class="p-0 mb-3" :label="items.label" square color="primary" />
                </span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VDatePicker v-model="item.tglAmbil" color="green" trim-weeks>
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VLabel class="required-field">Tanggal Ambil</VLabel>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" disabled class="is-rounded" :value="inputValue"
                      v-on="inputEvents" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column is-6">
            <VField>
              <VLabel class="required-field">Nama Pengambil</VLabel>
              <VControl>
                <input v-model="item.namapengambil" type="text" class="input is-rounded" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-12 pt-0">
          <VField class=" is-autocomplete-select p-0 mt-3">
            <VLabel class="required-field">Hubungan Keluarga</VLabel>
            <VControl icon="feather:search">
              <Multiselect mode="single" v-model="item.hubungankeluarga" placeholder="Hubungan Keluarga"
                :searchable="true" :options="d_hubungan" />
            </VControl>
          </VField>
        </div>
      </form>
    </template>
    <template #action>
      <VButton @click="penyerahanObat(item)" :loading="isLoading" color="primary" raised>
        Simpan</VButton>
    </template>
  </VModal>

  <VModal :open="modalDetailOrder" size="big" noclose title="Detail Resep" actions="right"
    @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
    <template #content>
      <TabView v-model:activeIndex="activeIdx">
        <TabPanel header="Order">
          <div class="columns is-multiline">
            <div class="column">
              <DataTable :value="sourceDetailOrder" class="p-datatable-sm" :loading="sourceDetailOrder.loading"
                :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column field="no" header="#" frozen></Column>
                <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px"></Column>
                <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
                <Column field="jumlahobat" header="Qty" :sortable="true" style="min-width: 200px"></Column>
                <Column field="satuanstandar" header="Satuan" :sortable="true" style="min-width: 50px"></Column>
                <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
                <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
                <Column field="satuanresep" header="Satuan Resep" :sortable="true" style="min-width: 50px"></Column>
                <Column field="keterangan" header="Keterangan" :sortable="true" style="min-width: 50px"></Column>
              </DataTable>
            </div>
          </div>
        </TabPanel>
        <TabPanel header="Verifikasi">
          <div class="columns is-multiline">
            <div class="column">
              <DataTable :value="sourceDetailVerif" class="p-datatable-sm" :loading="sourceDetailVerif.loading"
                :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column field="no" header="#" frozen></Column>
                <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px"></Column>
                <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
                <Column field="jumlahobat" header="Qty" :sortable="true" style="min-width: 200px"></Column>
                <Column field="satuanstandar" header="Satuan Standar" :sortable="true" style="min-width: 50px"></Column>
                <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
                <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
                <Column field="satuanresep" header="Satuan Resep" :sortable="true" style="min-width: 50px"></Column>
                <Column field="keterangan" header="Keterangan" :sortable="true" style="min-width: 50px"></Column>
              </DataTable>
            </div>
          </div>
        </TabPanel>
      </TabView>
    </template>
  </VModal>

  <VModal :open="modalPilihOrder" size="big" noclose title="Detail Resep" actions="right"
    @close="modalPilihOrder = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <DataTable :value="sourceDetailOrder" dataKey="no" v-model:selection="selectedProduct" class="p-datatable-sm"
          :loading="sourceDetailOrder.loading" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px"></Column>
          <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
          <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column>
          <Column field="satuanstandar" header="Satuan" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jmldosis" header="Dosis" :sortable="true" style="min-width: 50px"></Column>
          <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
        </DataTable>
      </div>
    </template>
    <template #action>
      <!-- <VButton @click="cetakCopyResep(selectedProduct)" icon="feather:printer" :loading="isLoading" color="primary"raised>Cetak</VButton> -->
      <VButton @click="modalPilihApoteker = true" icon="feather:printer" :loading="isLoading" color="primary" raised>Cetak
      </VButton>
    </template>
  </VModal>

  <!-- <VModal :open="modalPilihApoteker" title="Pilih Apoteker" :noclose="true" size="small" actions="right"
    @close="modalPilihApoteker = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12">
          <VField class=" is-autocomplete-select p-0 mt-3" label="Apoteker">
            <VControl icon="feather:search">
              <Multiselect mode="single" v-model="item.apoteker" placeholder="Pilih Apoteker" :searchable="true"
                :options="d_Apoteker" />
            </VControl>
          </VField>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="cetakCopyResep(selectedProduct, item.apoteker)" color="primary" raised>print
      </VButton>
    </template>
  </VModal> -->

  <VModal :open="modalPilihApoteker" title="Pilih Apoteker" :noclose="true" size="small" actions="right"
    @close="modalPilihApoteker = false">
    <template #content>
      <form class="modal-form">
        <div class="column is-12">
          <VField class="is-autocomplete-select p-0 mt-3" label="Apoteker">
            <VControl icon="feather:search">
              <Multiselect mode="single" v-model="item.apoteker" placeholder="Pilih Apoteker" :searchable="true"
                :options="d_Apoteker" />
            </VControl>
          </VField>
        </div>
        <!-- Textarea for Keterangan -->
        <div class="column is-12 mt-3">
          <VField label="Keterangan">
            <VControl>
              <textarea v-model="item.keterangan" class="textarea" placeholder="Masukkan keterangan..."></textarea>
            </VControl>
          </VField>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="cetakCopyResep(selectedProduct, item.apoteker, item.keterangan)" color="primary" raised>
        Print
      </VButton>
    </template>
</VModal>


  <VModal :open="modalResepVerify" size="big" noclose title="Resep Verifikasi" actions="center"
    @close="modalResepVerify = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <DataTable :value="sourceDetailVerif" dataKey="no" v-model:selection="selectedResep" class="p-datatable-sm"
          :loading="sourceDetailVerif.loading" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px"></Column>
          <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
          <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column>
          <Column field="satuanstandar" header="Satuan" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jmldosis" header="Dosis" :sortable="true" style="min-width: 50px"></Column>
          <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
        </DataTable>
      </div>
    </template>
    <template #action>
      <VButton @click="cetakRekapLabel(selectedResep, true)" icon="feather:printer" :loading="isLoading" color="primary"
        raised>
        Label Injeksi</VButton>
      <VButton @click="cetakRekapLabel(selectedResep, false)" icon="feather:printer" :loading="isLoading" color="primary"
        raised>
        Rekap Label</VButton>
      <!-- <VButton @click="modalPilihApoteker = true" icon="feather:printer" :loading="isLoading" color="primary" raised>Cetak
      </VButton> -->
    </template>
  </VModal>
  <VModal :open="modalResepOpsi" size="big" noclose title="Resep Verifikasi" actions="center"
    @close="modalResepOpsi = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <DataTable :value="sourceDetailVerif" dataKey="no" v-model:selection="selectedResep" class="p-datatable-sm"
          :loading="sourceDetailVerif.loading" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

          <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
          <Column field="namaproduk" header="Nama Produk" frozen :sortable="true" style="min-width: 200px"></Column>
          <Column field="jmldosis" header="Jumlah Dosis" :sortable="true" style="min-width: 100px"></Column>
          <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column>
          <Column field="satuanstandar" header="Satuan" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jmldosis" header="Dosis" :sortable="true" style="min-width: 50px"></Column>
          <Column field="aturanpakai" header="Aturan pakai" :sortable="true" style="min-width: 50px"></Column>
          <Column field="jeniskemasan" header="Kemasan" :sortable="true" style="min-width: 50px"></Column>
        </DataTable>
      </div>
    </template>
    <template #action>
      <VButton @click="cetakOpsiLabel(selectedResep, true)" icon="feather:printer" :loading="isLoading" color="primary"
        raised>
        Label Biru</VButton>
      <VButton @click="cetakOpsiLabel(selectedResep, false)" icon="feather:printer" :loading="isLoading" color="primary"
        raised>
        Label Putih</VButton>
      <!-- <VButton @click="modalPilihApoteker = true" icon="feather:printer" :loading="isLoading" color="primary" raised>Cetak
      </VButton> -->
    </template>
  </VModal>
</template>

<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useToaster } from '/@src/composable/toaster'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
// import { useConfirm } from 'primevue/useconfirm'
import { useUserSession } from '/@src/stores/userSession'
import DataTable from 'primevue/datatable';
import { useToast } from 'primevue/usetoast';
import OverlayPanel from 'primevue/overlaypanel';
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import ConfirmDialog from 'primevue/confirmdialog';
import * as H from '/@src/utils/appHelper'
import { state, socket } from "/@src/socket.js";
import sleep from '/@src/utils/sleep'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import * as qzService from '/@src/utils/qzTrayService';
import { useConfirm } from "primevue/useconfirm"

useHead({
  title: 'Dashboard Farmasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const confirms = useConfirm();
const userSession = useUserSession()
const themeColors = useThemeColors()
const filters = ref('')
const ROOMNOW = ref('')
const metaKey = ref(true);
const selectedProduct = ref();
const selectedResep = ref();
const filtersObat = ref('')
const modalFilter: any = ref(false)
const date = new Date();
const overlaypanel: any = ref();
const selected: any = ref({})
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalDetail = ref(false)
const modalSerahObat = ref(false)
const modalResepVerify = ref(false)
const modalResepOpsi = ref(false)
const modalPilihApoteker = ref(false)
const loadFinishData = ref(false)
const route = useRoute()
const router = useRouter()
const sourceDetailOrder: any = ref([])
const sourceDetailVerif: any = ref([])
const chartRNG: any = ref({
  series: [],
})
let norec_RESEP: any = ref()
let listColor: any = ref(Object.keys(useThemeColors()))
let countRuangan: any = ref([])
let dataOrder: any = ref([])
let totalData: any = ref(0)
let isLoading: any = ref(false)
let isLoadFinish: any = ref(false)
let modalObat: any = ref(false)
let modalDetailOrder: any = ref(false)
let modalPilihOrder: any = ref(false)
let d_hubungan: any = ref([])
let d_Apoteker: any = ref([])
let stokObat: any = ref([])
let elementSource: any = ref([])
let d_Ruangan: any = ref()
let d_datasRuangan: any = ref()
let activeIdx: any = ref(0)
let listOP: any = reactive([])
let countOrder: any = ref([])
let item: any = ref({
  filterTgl: new Date(),
  isDate: true,
  tglAmbil: new Date(),
  statusOrder: '',
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
let listSesuai = ref([
  {
    label: "Sesuai",
    value: "Sesuai"
  },
  {
    label: "Belum Sesuai",
    value: "Belum Sesuai"
  }
])
const currentPage: any = ref({
  limit: 5,
  rows: 50
})

const dataSourcefiltered: any = computed(() => {
  if (!filters.value) {
    return dataOrder.value
  }

  let key = new RegExp(filters.value, 'i')
  return dataOrder.value.filter((item: any) => {
    return (
      item.namapasien.match(key) || item.nocm.match(key) || item.noidentitas.match(key)
    )
  })
})

const SourceObatfiltered: any = computed(() => {
  if (!filtersObat.value) {
    return stokObat.value
  }

  return stokObat.value.filter((item: any) => {
    return item.namaproduk.match(new RegExp(filtersObat.value, 'i'))
  })
})

const changeRuang = (e: any) => {
  d_Ruangan.value.forEach((element: any) => {
    if (element.value == item.value.filterRuangan) {
      ROOMNOW.value = element.label
    }
  });

  fetchObat()
  fetchDataOrder()
  // }
}

const changeRuangDatas = (e: any) => {
  userSession.setRuangan(item.value.filterRuanganDatas);
  localStorage.setItem('filterRuanganDatas', JSON.stringify(item.value.filterRuanganDatas));

  d_Ruangan.value.forEach((element: any) => {
    if (element.value == item.value.filterRuangan) {
      ROOMNOW.value = element.label;
    }
  });

  fetchObat();
  fetchDataOrder();
}


const fetchCountOrder = async () => {
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  await useApi().get(`/dashboard/apotik/count-by-date?${tglAwal}${tglAkhir}`).then((res: any) => { countOrder.value = res })
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
      fetchDataOrder()
    }
  }
)
watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchDataOrder()
    }
  }
)
const fetchDataOrder = async () => {
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
  let ruangan = item.value.filterRuangan ? '&ruanganfk=' + item.value.filterRuangan : ''
  let search = item.value.search ? '&search=' + item.value.search : ''
  let ruangan_datas = item.value.filterRuanganDatas ? '&objectruanganfk=' + item.value.filterRuanganDatas : ''
  let statusOrder = item.value.statusOrder != undefined ? '&statusorder=' + item.value.statusOrder : '&statusorder='
  let isIter = `&isiter=${item.value.isIter}`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  // let offset=''
  let page: any = route.query.page ? route.query.page : 1

  isLoadFinish.value = true
  await useApi().get(`/dashboard/apotik?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + tglAwal + tglAkhir + ruangan + ruangan_datas + statusOrder + search + isIter).then((response) => {
    isLoadFinish.value = false
    modalFilter.value = false

    response.data.forEach((element: any) => {
      element.inisial = H.INITIALS(element.namapasien)
    });
    dataOrder.value = response.data
    totalData.value = response.total
  })
  let c_set = {
    0: item.value.filterRuanganDatas,
    1: item.value.filterRuangan
  }
  H.cacheHelper().set('c_apotik', c_set);
}

let c = H.cacheHelper().get('c_apotik');
if (c != undefined) {
  item.value.filterRuanganDatas = c[0];
  item.value.filterRuangan = c[1];
}

const getDetailOrder = async (noorder: any, norec: any) => {
  item.value.norecResep = norec
  activeIdx.value = 0
  modalDetailOrder.value = true
  sourceDetailOrder.value.loading = true
  const response = await useApi().get(`/dashboard/apotik/detail-order?noorder=${noorder}`)
  sourceDetailOrder.value = response.orderpelayanan
  sourceDetailOrder.value.loading = false

}

const detailVerif = async () => {

  sourceDetailVerif.value.loading = true
  const response = await useApi().get(`/farmasi/input-resep-edit?norecResep=${item.value.norecResep}`)
  sourceDetailVerif.value.loading = false
  sourceDetailVerif.value = response.pelayananPasien
}

const showModalDetailVerif = async (e: any) => {

  modalResepVerify.value = true
  sourceDetailVerif.value.loading = true
  item.value.norec_resep = e
  const response = await useApi().get(`/farmasi/input-resep-edit?norecResep=${e}`)
  sourceDetailVerif.value.loading = false
  sourceDetailVerif.value = response.pelayananPasien
}
const showModalDetailOpsi = async (e: any) => {

  modalResepOpsi.value = true
  sourceDetailVerif.value.loading = true
  item.value.norec_resep = e
  const response = await useApi().get(`/farmasi/input-resep-edit?norecResep=${e}`)
  sourceDetailVerif.value.loading = false
  sourceDetailVerif.value = response.pelayananPasien
}

const fetchObat = async () => {
  loadFinishData.value = true
  let ruangan = item.value.filterRuangan ? `&ruanganfk=${item.value.filterRuangan}` : ''
  let ruangan_datas = item.value.filterRuanganDatas ? `&ruanganfk=${item.value.filterRuanganDatas}` : ''
  await useApi().get(`/dashboard/apotik/list-stok-obat?limit=10&${ruangan}${ruangan_datas}`).then((response) => {
    stokObat.value = response.data
    stokObat.value.total = response.data.length
    loadFinishData.value = false
  }).catch(() => {
    loadFinishData.value = false
  })
}

const fetchDataChart = async () => {
  let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')

  await useApi()
    .get(`/dashboard/apotik/count-by-room?${tglAwal}${tglAkhir}`)
    .then((response: any) => {

      chartRNG.value = {
        series: response.chartRNG.count,
        chart: {
          height: 350,
          type: 'pie',
        },
        colors: [
          themeColors.accent,
          themeColors.info,
          themeColors.green,
          themeColors.purple,
          themeColors.orange,
        ],
        labels: response.chartRNG.namaruangan,
        responsive: [
          {
            breakpoint: 480,
            options: {
              chart: {
                width: 200,
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

const dropdownRuangan = async () => {
  const response = await useApi().get('/dashboard/apotik/data-combo')
  d_Ruangan.value = response.ruangan.map((e: any) => {
    return { label: e.namaruangan, value: e.id, default: e }
  })
  d_datasRuangan.value = response.datasruangan.map((e: any) => {
    return { label: e.namaruangan, value: e.id, default: e }
  })
  d_Apoteker.value = response.apoteker.map((e: any) => {
    return { label: e.namalengkap, value: e.id }
  })

  d_hubungan.value = response.hubunganKeluarga.map((e: any) => {
    return { label: e.hubungankeluarga, value: e.id, default: e }
  })

  let pegawai = H.pegawaiLogin()
  item.value.apoteker = pegawai.id
}

const reload = () => {
  modalFilter.value = false
  fetchDataOrder()
  fetchCountOrder()
  fetchDataChart()
}

const showModalFilter = () => {
  modalFilter.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.namaPasien = ''
  item.value.nocm = ''
  item.value.kelompokpasien = ''
  item.value.statusorder = ''
  item.value.nopesanan = ''
  item.value.jeniskelamin = ''
  item.value.namakelas = ''
}

const modalDetailObat = () => {
  modalObat.value = true
}
const verifikasiResep = (e: any) => {
  router.push({
    name: 'module-farmasi-input-resep',
    query: {
      norec_pd: e.norec_pd,
      norec_order: e.norec_order,
      nocmfk: e.nocmfk,
      norec_apd: e.norec_apd,
    },

  })
}
const resepIter = (e: any) => {
  console.log(e)
  router.push({
    name: 'module-farmasi-input-resep',
    query: {
      norec_pd: e.norec_pd,
      norec_order: e.norec_order,
      isiter: e.isiter,
      nocmfk: e.nocmfk,
      norec_apd: e.norec_apd,
    },
  })
}

const unverifResep = (e: any) => {
  if (e.statuspembayaranresep == 1) {
    H.alert('error', H.alertKasir())
    return
  }
  confirms.require({
    group: 'positionDialog',
    message: H.alertBatal(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: async () => {
      await useApi()
        .post(`/dashboard/apotik/batal-verifikasi`,
          {
            'norec': e.norec_resep,
            'nocm': e.nocm
          })
        .then((res: any) => {
          reload()
        })
    },
    reject: () => {
    }
  });
}

const changeProduksi = async (e: any) => {

  let objsave = {
    tglambil: null,
    namapengambil: null,
    noorder: e.noorder,
    statusorder: 1,
    strukresep: e.noorder == e.noresep ? true : ''
  }

  await useApi().post('/dashboard/apotik/save-status-resepelektonik', objsave).then(() => {
    reload()
  }).catch((e) => {
    console.log(e)
  })

}

const changePackaging = async (e: any) => {

  let objsave = {
    tglambil: null,
    namapengambil: null,
    noorder: e.noorder,
    statusorder: 2,
    strukresep: e.noorder == e.noresep ? true : ''
  }

  await useApi().post('/dashboard/apotik/save-status-resepelektonik', objsave).then(() => {
    reload()
  }).catch((e) => {
    console.log(e)
  })

}

const changeDone = async (e: any) => {
  let objsave = {
    tglambil: null,
    namapengambil: null,
    noorder: e.noorder,
    statusorder: 3,
    strukresep: e.noorder == e.noresep ? true : ''
  }

  await useApi().post('/dashboard/apotik/save-status-resepelektonik', objsave).then(() => {
    reload()
  }).catch((e) => {
    console.log(e)
  })

}

const showModalPenyerah = (e: any) => {
  item.value.status = e.pengerjaanfk
  item.value.noregistrasifk = e.norec_pd
  elementSource.value = e
  modalSerahObat.value = true
  item.value.persyaratanAdministrasi1 = "Belum Sesuai"
  item.value.persyaratanAdministrasi2 = "Belum Sesuai"
  item.value.persyaratanAdministrasi3 = "Belum Sesuai"
  item.value.persyaratanAdministrasi4 = "Belum Sesuai"
  item.value.persyaratanFarmasetik1 = "Belum Sesuai"
  item.value.persyaratanFarmasetik2 = "Belum Sesuai"
  item.value.persyaratanFarmasetik3 = "Belum Sesuai"
  item.value.persyaratanFarmasetik4 = "Belum Sesuai"
  item.value.persyaratanKlinis1 = "Belum Sesuai"
  item.value.persyaratanKlinis2 = "Belum Sesuai"
  item.value.persyaratanKlinis3 = "Belum Sesuai"
  item.value.persyaratanKlinis4 = "Belum Sesuai"
  item.value.persyaratanKlinis5 = "Belum Sesuai"
}

const showModalPilihOrder = async (e: any) => {
  modalPilihOrder.value = true
  item.value.norec_order = e.norec_order
  item.value.norec_resep = e.norec_resep
  sourceDetailOrder.value.loading = true
  await useApi().get(`/dashboard/apotik/detail-order?noorder=${e.noorder}`).then((response) => {
    sourceDetailOrder.value = response.orderpelayanan
    sourceDetailOrder.value.loading = false
  })
}

const penyerahanObat = async (e: any) => {

  let element = elementSource.value
  let objsave = {
    tglambil: H.formatDate(item.value.tglAmbil, 'YYYY-MM-DD HH:mm'),
    namapengambil: item.value.namapengambil,
    hubunganKeluarga: item.value.hubungankeluarga,
    noorder: element.noorder,
    statusorder: 3,
    strukresep: element.noorder == element.noresep ? true : '',
    noregistrasifk: item.value.noregistrasifk,
    persyaratanAdministrasi1: item.value.persyaratanAdministrasi1,
    persyaratanAdministrasi2: item.value.persyaratanAdministrasi2,
    persyaratanAdministrasi3: item.value.persyaratanAdministrasi3,
    persyaratanAdministrasi4: item.value.persyaratanAdministrasi4,
    persyaratanFarmasetik1: item.value.persyaratanFarmasetik1,
    persyaratanFarmasetik2: item.value.persyaratanFarmasetik2,
    persyaratanFarmasetik3: item.value.persyaratanFarmasetik3,
    persyaratanFarmasetik4: item.value.persyaratanFarmasetik4,
    persyaratanKlinis1: item.value.persyaratanKlinis1,
    persyaratanKlinis2: item.value.persyaratanKlinis2,
    persyaratanKlinis3: item.value.persyaratanKlinis3,
    persyaratanKlinis4: item.value.persyaratanKlinis4,
    persyaratanKlinis5: item.value.persyaratanKlinis5
  }
  await useApi().post('/dashboard/apotik/save-status-resepelektonik', objsave).then(() => {
    reload()
    modalSerahObat.value = false
    if (item.value.noregistrasifk) {
      const jsonQuisioner = {
        noregistrasifk: item.value.noregistrasifk
      }
      useApi().postNoMessage('/bridging/satusehat/QuestionnaireResponse', jsonQuisioner).then((resp:any) => {})
    }
  }).catch((e) => {
    console.log(e)
  })
  clearModalSerahObat()
}

const cetakResep = async (e: any) => {
  qzService.printData(`report/farmasi/resep?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1)
  // H.printBlade(`report/farmasi/resep?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1)
}

// const cetakResep23 = (e: any) => {
//   // qzService.printData(`report/farmasi/resep-obat-23?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1)
//   H.printBlade(`report/farmasi/resep-obat-23?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1)
// }
const cetakResep23 = async (e: any) => {
  console.log(e)
  if (e.status_order === null || e.status_order === undefined) {
    useToaster().warn('Tidak ada data obat kronis')
  } else {
    qzService.printData(`report/farmasi/resep-obat-23?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1)
    // H.printBlade(`report/farmasi/resep-obat-23?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1);
  }
};

const cetakResepPulang = async (e: any) => {
  console.log(e)
  if (e.obat_pulang === null || e.obat_pulang === undefined) {
    useToaster().warn('Tidak ada data obat Pulang')
  } else {
    qzService.printData(`report/farmasi/resep-obat-pulang?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1)
    // H.printBlade(`report/farmasi/resep-obat-23?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'RESEP', 1);
  }
};

const cetakRekapLabel = async (e: any, isInjeksi: any) => {
  if (!e) {
    H.alert('error', 'Obat disalin tidak boleh kosong')
    return
  }
  let items = []
  e.forEach((element: any) => {
    items = [...new Set([...items, element.produkfk])]
  });

  let ketLabel = isInjeksi == true ? 'LABEL BIRU RESEP' : 'LABEL RESEP'
  qzService.printData(`report/farmasi/label-custom?pdf=true&norec_resep=${item.value.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`, ketLabel, 1)
  // H.printBlade(`report/farmasi/label-custom?pdf=true&norec_resep=${item.value.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`)
}
const cetakOpsiLabel = async (e: any, isBiru: any) => {
  if (!e) {
    H.alert('error', 'Obat disalin tidak boleh kosong')
    return
  }
  let items = []
  e.forEach((element: any) => {
    items = [...new Set([...items, element.produkfk])]
  });

  let ketLabel = isBiru == true ? 'LABEL BIRU RESEP' : 'LABEL RESEP'
  qzService.printData(`report/farmasi/label-custom?pdf=true&norec_resep=${item.value.norec_resep}&produkfk=${items}`, ketLabel, 1)
  // H.printBlade(`report/farmasi/label-custom?pdf=true&norec_resep=${item.value.norec_resep}&produkfk=${items}&injeksi=${isInjeksi}`)
}

const cetakLabelBiru = async (e: any) => {
  if (e.jenis == "Racikan") {
    H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=${e.norec_pd}`);
    // qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norecpd=${e.norec_pd}`, 'LABEL BIRU RESEP', 1);
  } else {
    // qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec_resep}`, 'LABEL BIRU RESEP', 1);
    H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true&norec=${e.norec_resep}`, 'LABEL BIRU RESEP', 1);
  }
}

const cetakLabel = async (e: any) => {

  let norec_resp = e.norec_resep ? `&norec=${e.norec_resep}` : ''
  let norec_pd = e.norec_pd ? `&norecpd=${e.norec_pd}` : ''

  // H.printBlade(`report/farmasi/cetak-apotik-label-kecil?pdf=true${norec_pd}${norec_resp}`);
  qzService.printData(`report/farmasi/cetak-apotik-label-kecil?pdf=true${norec_pd}${norec_resp}`, 'LABEL RESEP', 1);
}

const cetakNama = async (e: any) => {
  let norec_resp = e.norec_resep ? `&norec=${e.norec_resep}` : ''
  let norec_pd = e.norec_pd ? `&norecpd=${e.norec_pd}` : ''

  // H.printBlade(`report/farmasi/cetak-nama-pasien?pdf=true${norec_pd}${norec_resp}`);
  qzService.printData(`report/farmasi/cetak-nama-pasien?pdf=true${norec_pd}${norec_resp}`, 'LABEL NAMA', 1);
}

const cetakNomorAntrian = async (e: any) => {
  qzService.printData(`report/farmasi/cetak-nomor-antrian?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`, 'NOMOR ANTRIAN', 1)
  // H.printBlade(`report/farmasi/cetak-nomor-antrian?pdf=true&norec=${e.norec_resep}&norec_order=${e.norec_order}`)
}
const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true", 'SEP', 1)
}

const panggilPasien = async (e: any) => {
  e.loading = true
  sendAntrol(e.norec_pd)
  await socket.emit('call-antrian-farmasi', {
    namapasien: e.namapasien,
    namaruangan: e.namaruangan,
    noantri: e.noantri,
    nocm: e.nocm,
    noregistrasi: e.noregistrasi,
    jenis: e.jenis,
    status: 'panggil'
  });
  await sleep(1000)
  e.loading = false
}

// const cetakCopyResep = (e: any, apoteker: any) => {
//   if (!e) {
//     H.alert('error', 'Obat disalin tidak boleh kosong')
//     return
//   }
//   let items = []
//   e.forEach((element: any) => {
//     items = [...new Set([...items, element.norec_op])]
//   });
//   let apotek = apoteker ? `&apoteker=${apoteker}` : ''
//   let norec_resep = item.value.norec_resep ? `&norec_resep=${item.value.norec_resep}` : ''
//   qzService.printData(`report/farmasi/copy-resep?pdf=true&norec_order=${item.value.norec_order}${norec_resep}&norec_op=${items}${apotek}`, 'COPY RESEP', 1);
//   // H.printBlade(`report/farmasi/copy-resep?pdf=true&norec_order=${item.value.norec_order}${norec_resep}&norec_op=${items}${apotek}`)
//   modalPilihApoteker.value = false
// }

const cetakCopyResep = (e, apoteker, keterangan) => {
  if (!e) {
    H.alert('error', 'Obat disalin tidak boleh kosong');
    return;
  }
  let items = [];
  e.forEach((element) => {
    items = [...new Set([...items, element.norec_op])];
  });
  let apotek = apoteker ? `&apoteker=${apoteker}` : '';
  let norec_resep = item.value.norec_resep ? `&norec_resep=${item.value.norec_resep}` : '';
  let keteranganQuery = keterangan ? `&keterangan=${encodeURIComponent(keterangan)}` : '';
  qzService.printData(`report/farmasi/copy-resep?pdf=true&norec_order=${item.value.norec_order}${norec_resep}&norec_op=${items}${apotek}${keteranganQuery}`, 'COPY RESEP', 1);
  modalPilihApoteker.value = false;
}


const toggle = (event, e) => {
  overlaypanel.value.toggle(event);
  selected.value = e
}

const clearModalSerahObat = () => {
  delete item.value.tglambil
  delete item.value.hubungankeluarga
  delete item.value.noorder
  delete item.value.namapengambil
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Apoteker.value = response
  })
}

const sendAntrol = async (norec_pd) => {
  const jsont7 = {
    "noregistrasifk": norec_pd,
    "taskid": 7,
    "waktu": new Date().getTime(),
  }
  await useApi()
    .postNoMessage(`/bridging/antrol/sendTaskId`, jsont7)
    .then((response: any) => {
    })
}


watch(
  () => [
    item.value.statusOrder
  ], () => {
    fetchDataOrder()
  }
)
watch(
  () => activeIdx.value,
  (newValue, oldValue) => {
    if (newValue == 0) {

    }
    if (newValue == 1) {
      detailVerif()
    }

  }
)
qzService.connect()
fetchCountOrder()
fetchDataOrder()
dropdownRuangan()
fetchObat()
fetchDataChart()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/apotik.scss';

.dropdown-menu {
  top: 32px;
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
  display: none;
}

.td-label-x {
  font-size: 1.2rem;
  width: 100px;
  white-space: nowrap;
  overflow: hidden !important;
  text-overflow: ellipsis;
}

.v-modal.is-active {
  z-index: 999 !important;
}
</style>
