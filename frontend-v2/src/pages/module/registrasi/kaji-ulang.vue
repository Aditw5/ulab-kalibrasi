<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="columns is-multiline " style="position: relative;width: 130%;left: -11rem;">
    <div class="column is-8">
      <div class="form-layout is-stacked p-0">
        <div v-if="isLoadHeader == true" style="background:rgb(65, 185, 131);">
          <PlaceloadHeader />
        </div>
        <div class="form-outer" v-else>
          <div class="form-body p-2">
            <div class="business-dashboard hr-dashboard">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0">
                  <div class="block-header">
                    <div class="left">
                      <div class="current-user">
                        <h3>{{ pasien.namaperusahaan }}</h3>
                      </div>
                    </div>
                    <div class="center">
                      <div class="columns">
                        <div class="column">
                          <h4 class="block-heading">No Pendaftaran</h4>
                          <p class="block-text">{{ pasien.nopendaftaran }}</p>
                          <h4 class="block-heading">Tgl Registrasi</h4>
                          <p class="block-text">{{ pasien.tgldaftar }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="right">
                      <div class="columns">
                        <div class="column">
                          <h4 class="block-heading">No HP</h4>
                          <p class="block-text">{{ pasien.nohp }}</p>
                          <h4 class="block-heading">Email</h4>
                          <p class="block-text">{{ pasien.email }}</p>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="personal-dashboard personal-dashboard-v2">
          <div class="columns is-multiline">
            <!-- <div class="column is-12" v-if="pasien.namapasien == undefined">
                <PlaceloadHeader />
            </div> -->
            <div class="column is-12" style="padding: 18px;">
              <div class="dashboard-card has-margin-bottom">
                <div class="card-head">
                  <h3 class="dark-inverted"> PENGKAJIAN ULANG </h3>
                </div>
                <div class="active-projects">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                      <VField>
                        <VControl icon="feather:search">
                          <input v-model="filters" type="text" class="input is-rounded" placeholder="Filter " />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-2">
                    </div>
                    <div class="column is-6">
                      <div class="columns is-multiline is-pulled-right">
                        <div class="column is-4">
                          <VButton icon="feather:printer" raised bold @click="cetakLabel(item)" :loading="isLoadingBill"
                            color="purple">
                            Cetak Label
                          </VButton>
                        </div>
                        <div class="column is-4 ">
                          <VButton icon="feather:plus-circle" raised bold class="w-100" @click="inputTindakan(item)"
                            :loading="isLoadingBill" color="info">
                            Input Tindakan
                          </VButton>
                        </div>
                        <!-- <div class="column pr-0 m-0">
                      <VButton class="ml-1 is-pulled-right" icon="pi pi-book" raised bold :loading="isLoadingBill" @click="lihat"
                        color="primary">
                        Hasil
                      </VButton>
                    </div> -->
                        <div class="column is-4 ">
                          <SplitButton label="Lainnya" icon="pi pi-info-circle" size="medium" :model="listButton"
                            style="height: 38px;" />
                          <!-- <SpeedDial :model="listButton" direction="right" :transitionDelay="80" showIcon="pi pi-ellipsis-v"
                        hideIcon="pi pi-times" buttonClassName="p-button-outlined" className="speeddial-right"
                        class="secondary" type="semi-circle" :tooltipOptions="listButton" /> -->
                        </div>
                      </div>
                    </div>
                    <div class="column is-12">
                      <div class="column is-12">
                        <table class="tb-custom mt-3">
                          <thead>
                            <tr>
                              <th width="10%" class="text-center">
                                <!-- <VControl raw subcontrol style="margin-top:-10px">
                                  <VCheckbox v-model="item.checkAll" label="#" color="info"
                                    @change="checkedAll(item.checkAll)" :value="item.checkAll" />
                                </VControl> -->
                              </th>
                              <th width="25%">LAYANAN</th>
                              <th width="20%">HARGA SATUAN</th>
                              <th>JUMLAH</th>
                              <th>SUBTOTAL</th>
                              <th>OPSI</th>
                            </tr>

                          </thead>
                          <tbody v-if="isLoadingBill">
                            <tr>
                              <td colspan="5">
                                <div class="list-view list-view-v1 is-fullwidth">
                                  <div class="list-view-inner">
                                    <div v-for="key in 6" :key="key" class="list-view-item mt-2">
                                      <VPlaceloadWrap>
                                        <VPlaceloadAvatar size="medium" />
                                        <VPlaceloadText last-line-width="60%" class="mx-2" />
                                        <VPlaceload class="mx-2" disabled />
                                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                        <VPlaceload class="mx-2" />
                                      </VPlaceloadWrap>
                                    </div>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                          <div style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                            <tbody>
                              <tr>
                                <td colspan="5" class="koneng">
                                  <!-- {{ H.formatDateOnlyLong(items.tglpelayanan_group) }} -->
                                </td>
                              </tr>
                              <tr  v-if="!isLoadingBill" v-for="(itemsDet, index)  in dataSourcefiltered" :key="index">
                                <td width="5%">
                                  <!-- <VControl raw subcontrol>
                                    <VCheckbox v-model="modelCheck[itemsDet.norec]" :value="itemsDet.itemsDet"
                                      color="info" square :class="modelCheck[items.norec] == true ? 'is-solid' : ''"
                                      @change="checkedItems()" />
                                  </VControl> -->
                                </td>
                                <td width="30%">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="title-ruangan">{{ itemsDet.nopendaftaran }}</div>
                                      <div class="title-layan">{{ itemsDet.namaproduk }} </div>
                                      <div>
                                        <!-- <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                          :label="itemsDet.tglpelayanan" />
                                        <VTag :color="'secondary'" class="ml-2" label="Kirim RIS"
                                          v-if="itemsDet.idbridging != null" />
                                        <VTag :color="itemsDet.norec_hr == null ? 'danger' : 'primary'"
                                          :class="itemsDet.norec_hr == null ? 'ml-2 fas fa-times-circle' : 'ml-2 fas fa-check-circle'" />
                                        <VTag :color="'success'" class="  ml-2" label="Sudah Ada Hasil"
                                          v-if="itemsDet.order_complete == 1" /> -->
                                      </div>
                                      <!-- <div class="title-kelas">{{ itemsDet.namakelas }} / {{ itemsDet.accession_num }}</div>
                                      <div class="title-kelas">
                                          <VTag :color="'light'" style="font-size:10pt"><b>Catatan Klinis : {{ itemsDet.catatanklinis }}</b></VTag>
                                      </div> -->
                                    </div>
                                  </div>
                                </td>
                                <td class="center">
                                  <!-- <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                      <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>

                                      <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}
                                      </div>
                                    </div>
                                  </div> -->
                                </td>
                                <td class="center" style="text-align:center">
                                <!-- {{ itemsDet.jumlah }} {{ itemsDet.isExpertise }} -->
                                </td>
                                <td class="center">
                                <!-- {{ H.formatRp(itemsDet.total, 'Rp. ') }} -->
                                </td>
                                <td class="center">
                                  <!-- <VIconButton color="primary" class="mr-2" light raised circle icon="feather:user"
                                    @click="detailPetugas(itemsDet)" v-tooltip.bubble="'Detail Petugas Pelayanan'" />
                                  <VIconButton color="info" light raised circle icon="feather:edit" class="mr-1"
                                    v-tooltip.bubble="'Hasil Expertise'" @click="HasilExpertise(itemsDet)" />
                                  <VIconButton color="danger" class="mr-2" light raised circle icon="feather:trash" v-if="itemsDet.norec_hr == null"
                                    @click="hapusItems(itemsDet)" v-tooltip.bubble="'Hapus Layanan'" />
                                  <VIconButton color="warning" class="mr-2" light raised circle icon="feather:book"
                                    @click="hasil(itemsDet)" v-tooltip.bubble="'Lihat Hasil'"
                                    :loading="itemsDet.isLoading" />
                                    <VIconButton color="danger" class="mr-2" light raised circle icon="feather:stop-circle"
                                    @click="hapusEx(itemsDet)" v-tooltip.bubble="'Hapus Expertise'" v-if="itemsDet.norec_hr != null"/> -->
                                </td>
                              </tr>
                            </tbody>
                            <!-- <div class="search-results-wrapper"
                              v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                              <div class="search-results-body ">
                                <div class="page-placeholder">
                                  <div class="placeholder-content">
                                    <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                      alt="" />
                                    <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                      alt="" />
                                    <h3>{{ H.assets().notFound }}</h3>
                                    <p class="is-larger">
                                      {{ H.assets().notFoundSubtitle }}
                                    </p>
                                  </div>
                                </div>
                              </div>
                            </div> -->
                          </div>
                        </table>
                      </div>

                    </div>
                  </div>
                  <div class="content mt-0 mb-0">
                    <div class="is-divider mt-3 mb-2" data-content="TOTAL"></div>
                  </div>
                  <div class="load-more-wrap has-text-centered p-1 mb-3">
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VCardCustom :style="'padding:5px 25px'">
                          <div class="label-status primary">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">TAGIHAN</span>
                          </div>
                          <small class="text-bold-custom">{{
                            H.formatRp(item.TOTAL,
                              'Rp.')
                          }}</small>

                        </VCardCustom>
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

    <div class="column is-4">
      <UIWidget class="search-widget">
        <template #body>
          <div class="field">
            <div class="control">
              <input v-model="filterd" class="input custom-text-filter" placeholder="Cari Pasien Penunjang ..." />
              <button class="searcv-button">
                <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
              </button>
            </div>
          </div>
          <div class="column">
            <VField class="h-hidden-mobile">
              <VControl>
                <VSwitchBlock v-model="item.isExpertise" label="Expertise" color="danger" @change="changeSwitch(item.isExpertise)" />
              </VControl>
            </VField>
          </div>
        </template>
      </UIWidget>

      <div class="recent-rookies">
        <div class="recent-rookies-header" style="justify-content: space-between;display: flex;">
          <h3 style="font-family: var(--font-alt);font-size: 1.1rem;font-weight: 600;color: var(--dark-text);">Pasien
            Penunjang</h3>
          <VTag @click="showModalFilter()" color="danger" rounded elevated style=" cursor:pointer">{{
            H.formatDateToLocalString(item.periode.start) == H.formatDateToLocalString(item.periode.end) ?
            H.formatDateToLocalString(item.periode.start) :
            H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
              H.formatDateToLocalString(item.periode.end) : '')
          }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
          <!-- <VTag color="danger" label="Tag Label" rounded elevated> {{ dataSource.total }} Tabel</VTag> -->
        </div>
      </div>
      <div class="tile-grid tile-grid-v2 mt-2" style="max-height:43rem;overflow: auto;">
        <VCard v-for="(data, i) in 10" :key="i" class="mb-3" v-if="loadPasienPenunjang">
          <div class="columns is-multiline" style="cursor:pointer;padding-bottom:6px;">
            <div class="column is-2 pb-0">
              <VPlaceloadAvatar size="medium" />
            </div>
            <div class="column is-10 pb-0 pt-0">
              <div class="column pt-3">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </div>
            </div>
          </div>
        </VCard>
        <VCard v-for="(data, a) in dataSourcefilter" :key="a" class="mb-3" v-else>
          <div class="columns is-multiline" style="cursor:pointer" @click="detail(data)">
            <div class="column is-2 pb-0">
              <VAvatar
                :picture="data.objectjeniskelaminfk == 2 ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'"
                :badge="(data.objectjeniskelaminfk == 1 ? '/images/other/male.png' : '/images/other/female.png')"
                size="medium" />
            </div>
            <div class="column is-10 pb-0 pt-0">
              <div class="column p-0">
                <p style="font-family: var(--font-alt);font-size: 1rem;font-weight: 600;color: var(--dark-text);">{{
                  data.namapasien }} {{ data.objectjeniskelaminfk == 1 ? '(L)' : '(P)' }}</p>
                <div class="columns is-multiline p-0 m-1">
                  <div class="column p-1">
                    <i aria-hidden="true" class="iconify" data-icon="feather:map-pin"></i><span class="ml-2">{{
                      data.ruanganasal }}</span>
                  </div>
                  <div class="column p-1">
                    <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i><span class="ml-2">{{
                      data.nocm }}</span>
                  </div>
                </div>
                <div class="columns is-multiline p-1">
                  <div class="column p-0" style="position:relative;left:17px">
                    <VTag color="primary" label="Terisi Expertise" v-if="data.isExpertise" />
                    <VTag color="warning" label="Belum Terisi Expertise" v-else />
                  </div>
                  <div class="column p-0 ml-2" style="padding:0px !important;">
                    <i class="fas fa-calendar-day" aria-hidden="true"></i><span class="ml-2">{{data.tglpelayanan ? H.formatDateIndoSimple(data.tglpelayanan) : ''}}</span>
                  </div>
                </div>
                <!-- <div class="column p-0">
                  <VTag color="primary" label="Terisi Expertise" v-if="data.isExpertise" />
                  <VTag color="warning" label="Belum Terisi Expertise" v-else />
                </div> -->
              </div>
            </div>
          </div>
        </VCard>
        <!--
        <VPlaceholderPage :class="[dataSourcefiltered.length !== 0 && 'is-hidden']"
          title="We couldn't find any matching results." subtitle="Too bad. Looks like we couldn't find any matching results for the
            search terms you've entered. Please try different search terms or
            criteria." larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
          </template>
        </VPlaceholderPage> -->


        <!-- <TransitionGroup name="list" tag="div" class="columns is-multiline">

          <div class="columns is-multiline p-2" style="max-height:550px;overflow: auto;">
            <div v-for="item in dataSourcefiltered" :key="item.id" class="column is-12">
              <div class="tile-grid-item">
                <RouterLink :to="{ name: item.link }">
                  <div class="tile-grid-item-inner">

                    <VAvatar size="medium" picture="/images/avatars/svg/datamaster.svg" color="primary" squared
                      bordered />

                    <div class="meta">
                      <span class="dark-inverted">Narendra Wicaksono</span>

                      <span></span>
                    </div>
                    <VTag style="margin-left:auto" color="primary" label="Tag Label" rounded elevated></VTag>

                  </div>
                </RouterLink>
              </div>
            </div>
          </div>
        </TransitionGroup> -->
      </div>

    </div>

  </div>

  <VModal :open="modalPetugas" title="Detail Petugas Tindakan" :noclose="false" size="medium" actions="right"
    @close="modalPetugas = false">
    <template #content>
      <form class="modal-form custom-mod ">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-6">
                  <p class="block-text">Tanggal Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>

                </div>
                <div class="column is-6">
                  <p class="block-text">Nama Pelayanan</p>
                  <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>

                </div>
                <div class="column is-12">
                  <VCard class="is-grey">
                    <div class="columns is-multiline p-1">
                      <div class="column is-12 mb--15 text-center" v-if="dataSourcePetugas.length == 0">
                        <img class="light-image" style=" max-width: 180px;" :src="H.assets().iconNotFoundCalendar"
                          alt="" />
                        <h3>{{ H.assets().notFound }}</h3>
                      </div>
                      <div class="column is-12 mb--15" v-else v-for="(item, index) in dataSourcePetugas" :key="index">
                        <div class="columns is-multiline p-0">
                          <div class="column is-9">
                            <div class="file-box-2">
                              <img :src="'/images/icons/files/dokter.svg'" alt="" />
                              <div class="meta">
                                <span>{{ item.namalengkap }} </span>
                                <span>
                                  <b>{{ item.jenispetugaspe }}</b>
                                </span>
                              </div>
                              <div class="is-right is-dots is-spaced dropdown end-action">
                                <i aria-hidden="true" class="fas fa-check-circle  is-pulled-right"
                                  style="color:var(--success)"></i>
                              </div>
                            </div>

                          </div>
                          <div class="column is-3 mt-5">
                            <VIconButton outlined type="button" class="mr-2" raised circle icon="feather:edit"
                              @click="editPetugas(item)" color="info">
                            </VIconButton>
                            <VIconButton outlined type="button" raised circle icon="feather:trash"
                              @click="hapusPetugas(item)" color="danger">
                            </VIconButton>
                          </div>

                        </div>
                      </div>

                    </div>
                  </VCard>
                </div>


              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:plus-circle" fullwidth>
                <Multiselect mode="single" v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                  @select="changeJenis(item.jenisPelaksana)" track-by="value" />

              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <VField class="is-rounded-select" v-slot="{ id }">
              <VLabel>Pegawai</VLabel>
              <VControl fullwidth>
                <Multiselect mode="single" v-model="item.pegawai" :options="d_Pegawai" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" track-by="value" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="savePetugas()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalCatatan" title="Catatan" :noclose="false" size="medium" actions="right"
    @close="modalCatatan = false">
    <template #content>
      <div class="column is-12">
        <VField>
          <VControl>
            <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveCatatan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalKirimRIS" title="Kirim Ke RIS" :noclose="false" size="medium" actions="right"
    @close="modalKirimRIS = false">
    <template #content>
      <div class="column is-12">
        <VField class="is-rounded-select is-autocomplete-select">
          <VLabel class="required-field">Dokter Pengirim</VLabel>
          <VControl icon="feather:search" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.objectpegawaifk" :options="d_Dokter" optionLabel="label" class="is-rounded"
              placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VControl>
            <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="4" placeholder="Catatan"
              autocomplete="off" autocapitalize="off" spellcheck="true" />
          </VControl>
        </VField>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="sendRISPACS()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>

  <VModal :open="modalExpertise" title="Masukan Ekspertise" :noclose="false" size="big" actions="right"
    @close="modalExpertise = false, clear()">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField>
              <VLabel>Nama Pelayanan</VLabel>
              <VControl icon="feather:box">
                <VInput type="text" v-model="item.namaproduk" placeholder="Nama Pelayanan" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Tanggal">
              <VDatePicker v-model="item.tanggal" mode="dateTime" style="width: 100%">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-4">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel class="required-field">Dokter</VLabel>
              <VControl icon="feather:search" fullwidth class="prime-auto-select">
                <Dropdown v-model="item.pegawaifk" :options="d_Dokter" optionLabel="label" class="is-rounded"
                :disabled="disabledExp"   placeholder="Pilih Dokter" style="width: 100%;" :filter="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VControl>
                <VTextarea class="textarea is-rounded" v-model="item.keterangan" rows="20" placeholder="Catatan Expertise"
                :disabled="disabledExp"  autocomplete="off" autocapitalize="off" spellcheck="true" />
              </VControl>
            </VField>
          </div>
        </div>
      </form>

    </template>
    <template #action>
      <VButton type="button" rounded outlined color="success" :loading="isLoadingPop" raised icon="feather:send" @click="kirimWA(item)">
        Kirim WA
      </VButton>
      <VButton type="button" rounded outlined color="info" :loading="isLoadingPop" raised icon="feather:printer" @click="Cetak(item)">
        Cetak
      </VButton>
      <VButton icon="feather:save" @click="saveExpertise(item)" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
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
              <!-- :max-date="new Date()" /> -->
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="fetchPenunjang()" :loading="isLoading" color="primary" raised>
        Filter</VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useWindowScroll } from '@vueuse/core'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { debounce } from 'lodash'
import SpeedDial from 'primevue/speeddial'
import Dropdown from 'primevue/dropdown'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import SplitButton from 'primevue/splitbutton';

useHead({
  title: 'Transaksi Pelayanan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
let NOREC_PD = useRoute().query.norec_mitra_daftar as string
let TGLREGISTRASI = useRoute().query.tglregistrasi as string
let ID_MITRA = useRoute().query.nocmfk as string
const PARAMETER: any = ref('')
const isLoadingPasien: any = ref(false)
const isLoadingBill: any = ref(false)
const modalFilter: any = ref(false)
const isLoadingPop: any = ref(false)
const pasien: any = ref({})
const dataSource: any = ref([])
const LISTRUANGAN_APD: any = ref([])
const dataSourcePetugas: any = ref([])
const dataPenunjang: any = ref([])
const router = useRouter()
const listChecked: any = ref([])
const modelCheck: any = ref([])
const filters = ref('')
const filterd = ref('')
const filtersHide = ref('')
const modalPetugas = ref(false)
const loadPasienPenunjang = ref(false)
const isLoadHeader = ref(true)
const modalExpertise: any = ref(false)
const modalKirimRIS: any = ref(false)
const modalCatatan: any = ref(false)
const dataSelect: any = ref({})
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const showJenis: any = ref(false)
const norecHasilRadiologi: any = ref('')
const disabledExp =ref(false)
const useNocmFilter = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  TGLREGISTRASI: TGLREGISTRASI != undefined ? TGLREGISTRASI : '',
  registrasi: {},
  tglorder: new Date(),
  tanggal: new Date(),
  produkCeklis: [],
  TOTAL: 0,
  DIBAYAR: 0,
  SISA: 0,
  DEPOSIT: 0,
  DISKON: 0,
  length: 0,
  pegawaiOrder: useUserSession().getUser().id,
  diskonKomponen: 0,
  namaKomponen: '',
  totalKomponen: 0,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  isExpertise: false,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

function changeSwitch(e: any) {
  useNocmFilter.value = item.isExpertise;
}

// const dataSourcefilter = computed(() => {
//   if (!filterd.value && !item.isExpertise) {
//     return dataPenunjang.value
//   }
//   return dataPenunjang.value.filter((dataItem: any) => {
//     return (
//       dataItem.namapasien.match(new RegExp(filterd.value, 'i')) ||
//       dataItem.nocm.match(new RegExp(filterd.value, 'i'))
//     );
//   });
// })

const dataSourcefilter = computed(() => {
  if (!filterd.value && !item.isExpertise) {
    return dataPenunjang.value;
  }

  const isExpertise = item.isExpertise;

  return dataPenunjang.value.filter((dataExpertise: any) => {
    return (
      // (!filterd.value ||
      //   (dataExpertise.namapasien && dataExpertise.namapasien.match(new RegExp(filterd.value, 'i'))) ||
      //   (dataExpertise.nocm && dataExpertise.nocm.match(new RegExp(filterd.value, 'i')))) &&
      (!isExpertise || dataExpertise.isExpertise === true)
    );
  });
});






const listButton: any = ref([
  {
    label: 'Catatan ',
    icon: 'pi pi-book',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data Untuk Menambahkan Catatan')
        return
      }
      modalCatatan.value = true
    }
  },
  {
    label: 'Kirim Ke RIS',
    icon: 'pi pi-send',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis Data yang akan Dikirim')
        return
      }

      for (let i = 0; i < listChecked.value.length; i++) {
        const element = listChecked.value[i];
        if (element.idbridging != null) {
          H.alert('error', `Produk ${element.namaproduk} dengan No. Rad ${element.noorder} sudah dikirim ke RIS !`)
          return
        }
      }
      // sendRISPACS()
      modalKirimRIS.value = true
    }
  },
  {
    label: 'Cetak Bukti Layanan ',
    icon: 'fas fa-print',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data yang mau dicetak')
        return
      }

      let norec = listChecked.value.map((a: any) => a.norec).join("|");
      H.printBlade('radiologi/cetakan-hasil-radiologi?noregistrasi=' + item.noregistrasi
        + '&norec=' + norec);

      console.log(listChecked.value)
    }
  },

])
const confirm = useConfirm();

const isDetail: any = ref(false)
const dataSourcefiltered: any = computed(() => {

  if (!filters.value && !filtersHide.value) {
    return dataSource.value
  }
  var filtered: any = [];
  for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    var filteredD = [];
    for (let z = 0; z < element.details.length; z++) {
      const element2 = element.details[z];
      if (filters.value) {
        if (element2.namaproduk.match(new RegExp(filters.value, 'i'))
          ) {
          filteredD.push(element2);
          filtered.push({
            details: filteredD
          })
          break
        }
      } else if (filtersHide.value) {
        if (element2.jenis.match(new RegExp(filtersHide.value, 'i'))
        ) {
          for (let xxx = 0; xxx < filtered.length; xxx++) {
            const elementxxx = filtered[xxx];
          }
          filteredD.push(element2);
          filtered.push({
            details: filteredD
          })
          // break
        }
      }

    }
  }
  return filtered;
})

const headerPasien = async (id: any, norec_pd: any, tglregistrasi: any) => {
  await useApi().get(`/registrasi/header-mitra?nocmfk=${id}&norec_pd=${norec_pd}&tglregistrasi=${tglregistrasi}`)
    .then((response: any) => {
      pasien.value = response.mitra
      fetchLayanan(norec_pd)
    })
  isLoadHeader.value = false
}

const fetchLayanan = async () => {
  isLoadingBill.value = true
  dataSource.value = []
  await useApi().get(
    `/registrasi/layana-mitra?norec_pd=${NOREC_PD}&tglregistrasi=${TGLREGISTRASI}`).then(async (response: any) => {
      dataSource.value = response.detail
      item.TOTAL = response.total
      item.length = response.length
      isLoadingBill.value = false

    })
  modalKirimRIS.value = false
}

const clearlah = () => {
  pasien.value = ''
}

const inputTindakan = (e: any) => {
  router.push({
    name: 'module-emr-tindakan',
    query: {
      norec_pasien_daftar: NOREC_PD,
      nocmfk: pasien.value.nocmfk,
      norec_apd: NOREC_APD
    },
  })
}

const hapusItems = async (e: any) => {
  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      var objSave = {
        'data':
          [{
            'noorder': e.noorder,
            'norec_pp': e.norec,
            'namaproduk': e.namaproduk,
            'namaruangan': e.namaruangan,
          }],
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'noregistrasi': pasien.value.noregistrasi,
      }
      nextHapus(objSave)
    },
    reject: () => {
    }
  });

}

const nextHapus = async (objSave: any) => {
  isLoadingBill.value = true
  useApi().post(
    `/radiologi/hapus-tindakan-rad`, objSave).then((response: any) => {
      isLoadingBill.value = false
      // fetchLayanan()
    }).catch((e: any) => {
      isLoadingBill.value = false
    })
}

const hapusEx = async (e: any) => {

  confirm.require({
    group: 'positionDialog',
    message: H.alertHapus(),
    header: 'Info ',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    position: 'top',
    accept: () => {
      var objSave = {
        'data':
          [{
            'norec_hr': e.norec_hr,
          }],
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'noregistrasi': pasien.value.noregistrasi,
        'noregistrasifk': e.norec_apd,
      }
      kuyHapus(objSave)
    },
    reject: () => {
    }
  });

}
const kuyHapus = async (objSave: any) => {
  isLoadingBill.value = true
  useApi().post(
    `/radiologi/hapus-expertise`, objSave).then((response: any) => {
      isLoadingBill.value = false
      headerPasien(ID_MITRA, NOREC_PD, NOREC_APD)
      // ID_MITRA, NOREC_PD, NOREC_APD
      fetchPenunjang()
    }).catch((e: any) => {
      // fetchLayanan()
      isLoadingBill.value = false
    })
}

const detailPetugas = async (e: any) => {
  clearInputPetugas()
  dataSelect.value = {}
  dataSelect.value = e
  await loadPetugas(e)
  await useApi().get(
    `tindakan/list-jenis-petugas`).then((response: any) => {
      d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
    })

  modalPetugas.value = true

}
const loadPetugas = async (e: any) => {
  isLoadingPop.value = true
  dataSourcePetugas.value = []
  await useApi().get(
    `/radiologi/petugas-radiologi?norec=${e.norec}`).then((response: any) => {
      isLoadingPop.value = false
      dataSourcePetugas.value = response
    }).catch((e: any) => {
      isLoadingPop.value = false
    })

}

const savePetugas = () => {
  let json =
  {
    'norec': item.norec_PPP ? item.norec_PPP : '',
    'objectjenispetugaspefk': item.jenisPelaksana,
    'objectpegawaifk': item.pegawai,
    'nomasukfk': dataSelect.value.norec_apd,
    'noregistrasi': pasien.value.noregistrasi,
    'pelayananpasien': dataSelect.value.norec,
    'namaproduk': dataSelect.value.namaproduk,
    'namaruangan': dataSelect.value.namaruangan,
  }
  useApi().post(
    `/radiologi/save-petugas-rad`, json).then((response: any) => {
      loadPetugas(dataSelect.value)
    })
}
const clearInputPetugas = () => {
  delete item.norec_PPP
  delete item.jenisPelaksana
  delete item.pegawai
}
const hapusPetugas = async (e: any) => {
  useApi().post(
    `/radiologi/hapus-petugas-rad`, {
    'norec': e.norec,
    'objectpegawaifk': e.objectpegawaifk,
    'namaproduk': dataSelect.value.namaproduk,
    'noregistrasi': pasien.value.noregistrasi,
  }).then((response: any) => {
    loadPetugas(dataSelect.value)
  })
}

const changeJenis = async (e: any) => {
  d_Pegawai.value = []
  await useApi().get(
    '/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e
  ).then(async (response: any) => {
    d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
  })
}

const editPetugas = async (e: any) => {
  item.norec_PPP = e.norec
  item.jenisPelaksana = e.objectjenispetugaspefk
  item.nomasukfk = e.nomasukfk
  item.pelayananpasien = e.pelayananpasien
  await changeJenis(e.objectjenispetugaspefk)
  item.pegawai = e.objectpegawaifk
}

const listPegawai = async () => {
  await useApi().get(
    `/dashboard/radiologi/get-dokter`).then((response: any) => {
      d_Dokter.value = response.data.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })
}

const HasilExpertise = async (e: any) => {

  item.norec_pp = e.norec
  item.norec_so = e.norec_so
  item.noregistrasifk = e.norec_apd
  item.dokterpemeriksa = e.dokterpemeriksa
  // item.pegawaifk = { label: e.dokterpemeriksa, value: e.pegawaifk }
  item.pegawaifk = { label:H.pegawaiLogin().namalengkap ,value: H.pegawaiLogin().id }
  item.namaproduk = e.namaproduk
  item.pelayananpasienfk = e.norec_pp
  item.noregistrasifk = e.norec_apd
  item.acessionNumber = e.accession_num
  disabledExp.value = false
  useApi().get(
    `/radiologi/get-expertise?norec=${item.norec_pp}`).then((response: any) => {
      norecHasilRadiologi.value ='';
      if (response != null) {
        norecHasilRadiologi.value = response.norec
        item.norec = response.norec
        item.tanggal = new Date(response.tanggalreport)
        item.dokterpemeriksa = response.namalengkap
        if( response.namalengkap !=H.pegawaiLogin().namalengkap ){
          disabledExp.value = true
        }
        item.pegawaifk = { label:response.namalengkap,value: response.pegawaifk }
        item.keterangan = response.keterangan.replace(/#&#/g, "\n")
      } else {
        if(e.description) {
          for (let i = 0; i < d_Dokter.value.length; i++) {
            const element = d_Dokter.value[i];
            if(element.value == e.charge_doc_id) {
              item.pegawaifk = element
              break
            }
          }
          item.tanggal = new Date(e.report_date)
          item.keterangan = e.description.replace(/#&#/g, "\n")
        }
      }
    })
  modalExpertise.value = true
}


const saveExpertise = async (e: any) => {
  if (!item.pegawaifk) {
    H.alert('warning', 'Dokter Harus Diisi !');
    return;
  }
  if (!item.keterangan) {
    H.alert('warning', 'Keterangan Harus Diisi !');
    return;
  }

  let objSave = {
    norec: e.norec,
    norec_so : e.norec_so,
    accession_num : e.acessionNumber,
    hasil: {
      keterangan: item.keterangan,
      pelayananpasienfk: item.norec_pp,
      noregistrasifk: item.noregistrasifk,
      pegawaifk: item.pegawaifk.value
    }

  }
  isLoadingPop.value = true
  await useApi().post(
    `/radiologi/save-expertise`, objSave).then((response: any) => {
      norecHasilRadiologi.value = response.data.norec
      isLoadingPop.value = false
      headerPasien(ID_MITRA, NOREC_PD, NOREC_APD)
      fetchPenunjang()
      modalExpertise.value = false
    })
    clear()
}

const kirimWA = () => {
  console.log(item.acessionNumber)
  let objSave = {
    accession_num : item.acessionNumber,
  }
  isLoadingPop.value = true
  useApi().post(
    `/radiologi/send-WA`, objSave).then((response: any) => {
      isLoadingPop.value = false
      modalExpertise.value = false
    }).finally(()=>{
      isLoadingPop.value = false
    })
}

const Cetak = async (e: any) => {

  if (!item.pegawaifk) {
    H.alert('warning', 'Dokter Harus Diisi !');
    return;
  }
  if (!item.keterangan) {
    H.alert('warning', 'Keterangan Harus Diisi !');
    return;
  }
  let objSave = {
    norec: e.norec,
    norec_so : e.norec_so,
    hasil: {
      keterangan: item.keterangan,
      pelayananpasienfk: item.norec_pp,
      noregistrasifk: item.noregistrasifk,
      pegawaifk: item.pegawaifk.value
    }

  }
  isLoadingPop.value = true
  await useApi().post(
    `/radiologi/save-expertise`, objSave).then((response: any) => {
      norecHasilRadiologi.value = response.data.norec
      isLoadingPop.value = false
      cetakExpertise()
      headerPasien(ID_MITRA, NOREC_PD, NOREC_APD)
      fetchPenunjang()
      modalExpertise.value = false
    })
    clear()
}

const cetakExpertise = () => {
  H.printBlade("radiologi/cetak-ekspertise?echo=true&norec=" + norecHasilRadiologi.value);
}

function checkedAll(e: any) {
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      e.details.forEach((f: any) => {
        listChecked.value.push(f)
        modelCheck.value[f.norec] = true
      });
    });
  }
}

function checkedItems() {
  let objectK = Object.keys(modelCheck.value)

  for (let x = 0; x < objectK.length; x++) {
    const element = objectK[x];
    if (modelCheck.value[element] == true) {
      for (var i = 0; i < dataSource.value.length; i++) {
        const element2 = dataSource.value[i];
        for (let xx = 0; xx < element2.details.length; xx++) {
          const element3 = element2.details[xx];
          if (element3.norec == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element4 = listChecked.value[z];
              if (element4.norec == element3.norec) {
                listChecked.value.splice(z, 1)
              }
            }
            listChecked.value.push(element3)
          }
        }

      }
    } else {
      for (var i = 0; i < dataSource.value.length; i++) {
        const element2 = dataSource.value[i];
        for (let xx = 0; xx < element2.details.length; xx++) {
          const element3 = element2.details[xx];
          if (element3.norec == element) {
            for (var z = 0; z < listChecked.value.length; z++) {
              const element4 = listChecked.value[z];
              if (element4.norec == element3.norec) {
                listChecked.value.splice(z, 1)
              }
            }
          }
        }
      }
    }
  }
}

const fetchPenunjang = async () => {
  loadPasienPenunjang.value = true;

  const tglAwal = H.formatDate(item.periode.start, 'YYYY-MM-DD');
  const tglAkhir = H.formatDate(item.periode.end, 'YYYY-MM-DD');
  const query = [`tglAwal=${tglAwal}`, `tglAkhir=${tglAkhir}`];

  if (filterd.value) {
    query.push(`qsearch=${filterd.value}`);
  }

  if (item.isExpertise) {
    query.push(`isExpertise=true`);
  }

  const response = await useApi().get(`/dashboard/radiologi/get-penunjang-rad?${query.join('&')}`);

  modalFilter.value = false;
  loadPasienPenunjang.value = false;
  dataPenunjang.value = response.data;
}

const showModalFilter = () => {
  modalFilter.value = true
}


const clear = () => {
  item.norec = ''
  item.nomorpa = ''
  item.pegawaifk = ''
  item.dokterpengirimfk = ''
  item.pelayananpasienfk = ''
  item.noregistrasifk = ''
  item.diagnosaklinik = ''
  item.keteranganklinik = ''
  item.diagnosapb = ''
  item.keteranganpb = ''
  item.topografi = ''
  item.morfologi = ''
  item.makroskopik = ''
  item.mikroskopik = ''
  item.kesimpulan = ''
  item.anjuran = ''
  item.jaringanasal = ''
  item.pegawaifk = ''
  item.keterangan = ''

  modalExpertise.value = false
  modalCatatan.value = false
}
const sendRISPACS = () => {

  let objSaveOrder: any = {}
  let objOrder: any = []
  let objBridg: any = []

  if (!item.objectpegawaifk) {
    H.alert('error', 'Pilih Dokter Pengirim terlebih dahulu !')
    return
  }

  for (let x = 0; x < listChecked.value.length; x++) {
    const element = listChecked.value[x];
    if (element.idbridging != null) {
      H.alert('warning', 'Data sudah pernah dikirim ke RIS')
      return
    }
  }
  for (let x = 0; x < listChecked.value.length; x++) {
    const element = listChecked.value[x];
    if (element.noorder == null) {
      objOrder.push({
        no: x + 1,
        produkfk: element.produkfk,
        qtyproduk: 1,
        objectkelasfk: item.KELAS_LAST,
        nourut: x + 1,
        norec_pp: element.norec,
      })
    } else {
      objBridg.push({
        produkfk: element.produkfk,
        namaproduk: element.namaproduk,
        qtyproduk: 1,
        objectkelasfk: item.KELAS_LAST,
      })
    }
    objSaveOrder = {
      status: "bridinglangsung",
      noregistrasi: item.noregistrasi,
      tanggal: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss'),
      tgloperasi: null,
      norec_so: '',
      norec_apd: element.norec_apd,
      norec_pd: item.registrasi.norec_pd,
      qtyproduk: objOrder.length,
      objectruanganfk: item.registrasi.objectruanganlastfk,
      pegawaiorderfk: element.pegawaifk,
      namalengkap: element.namalengkap,
      objectruangantujuanfk: item.registrasi.objectruanganfk,
      departemenfk: element.objectdepartemenfk,
      keterangan: null,
      noord: element.noorder,
      iscito: false,
      details: objOrder,
    }
  }
  if (objOrder.length) {
    isLoadingPop.value = true
    useApi().post(
      `/laboratorium/simpan-order`, objSaveOrder).then((response: any) => {

        let itemsave = {
          "details": objOrder,
          "noorder": response.strukorder.noorder,
          "objectkelasfk": item.KELAS_LAST,
          "objectruangantujuanfk": item.registrasi.objectruanganfk,
          "objectpegawaiorderfk": objSaveOrder.pegawaifk,
          "iddokterverif": item.objectpegawaifk.value,
          "namadokterverif": item.objectpegawaifk.label,
          "catatan_klinis": item.keterangan ? item.keterangan : null,
        }
        useApi().post('bridging/penunjang/save-bridging-zeta', itemsave).then((e: any) => {
          isLoadingPop.value = false
          fetchLayanan()
        }).catch((e: any) => {
          isLoadingPop.value = false
        })
      })
  }
  if (objBridg.length) {
    let itemsave = {
      "details": objBridg,
      "noorder": objSaveOrder.noord,
      "objectkelasfk": item.KELAS_LAST,
      "objectruangantujuanfk": item.registrasi.objectruanganfk,
      "objectpegawaiorderfk": objSaveOrder.pegawaifk,
      "iddokterverif": item.objectpegawaifk.value,
      "namadokterverif": item.objectpegawaifk.label,
      "catatan_klinis": item.keterangan ? item.keterangan : null,
    }
    isLoadingPop.value = true
    useApi().post('bridging/penunjang/save-bridging-zeta', itemsave).then((e: any) => {
      isLoadingPop.value = false
      fetchLayanan()
    }).catch((e: any) => {
      isLoadingPop.value = false
    })
  }

}

const detail = (e: any) => {
  // console.log(e);
  router.replace({
    name: 'module-radiologi-transaksi-radiologi',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      norec_apd: e.norec_apd,
      tglpelayanan: e.tglpelayanan
    },
  });
  if (ID_MITRA !== e.nocmfk || NOREC_PD !== e.norec_pd || NOREC_APD !== e.norec_apd || TGLREGISTRASI !== e.tglpelayanan) {
    ID_MITRA = e.nocmfk;
    NOREC_PD = e.norec_pd;
    NOREC_APD = e.norec_apd;
    TGLREGISTRASI = e.tglpelayanan;
    headerPasien(e.nocmfk, e.norec_pd, e.norec_apd, e.tglpelayanan);
  }
};


const hasil = (dataItem: any) => {

  if (dataItem.order_complete == 0) {
    H.alert('warning', 'Hasil belum ada')
  } else {
    router.push({
      name: 'module-radiologi-hasil-pacs',
      query: {
        url: dataItem.url_pacs_hasil,
      },
    })
  }
}



watch(
  () => item.persenDiskon,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue > 100) {
        item.persenDiskon = "";
      }
      item.diskonKomponen = ((parseFloat(item.hargasatuankom)) * newValue) / 100
    }
  }
)

watch(
  () => ID_MITRA,
  (newValue, oldValue) => {
    if (newValue != oldValue) {

    }
  }
)

watch(
  () => item.jenis,
  (newValue, oldValue) => {
    if (newValue == 'pa') {
      showJenis.value = true
    } else {
      showJenis.value = false
    }
  }
)

watch(filterd, debounce(fetchPenunjang, 500));

const cetakLabel = async (e: any) => {
  let so_norec = e.NOREC_PD;
  H.printBlade(`report/cetak-label-tindakan?norec=${so_norec}&type=radiologi`);
}



headerPasien(ID_MITRA, NOREC_PD, TGLREGISTRASI)
listPegawai()
fetchPenunjang()

</script>
<style lang="scss">
@import '/@src/scss/main';
@import '/@src/scss/module/kasir/billing';

.field>label {
  color: hsl(0deg, 0%, 4%) !important;
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 20px;
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
      padding-right: 10px;
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
</style>
