

<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div>
    <div class="form-layout is-stacked">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> Verifikasi Tagihan </h3>
            </div>
            <div class="right">
              <div class="buttons">
                <VButton icon="lnir lnir-calculator rem-100" color="danger" outlined @click="billing()">
                  Billing
                </VButton>
                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                  Kembali
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                  @click="simpan()"> Simpan
                </VButton>
              </div>
            </div>
          </div>
        </div>
        <div class="form-body p-2">
          <div class=" hr2-dashboard business-dashboard hr-dashboard">
            <div class="columns is-multiline">
              <div class="column is-12" v-if="isLoadingPasien">
                <PlaceloadHeader class="m-3" />
              </div>
              <div class="column is-12" v-if="!isLoadingPasien">
                <HeadPasien :pasien="pasien" class="m-3" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <form class="form-layout is-separate">
      <div class="form-outer">
        <div class="form-body">
          <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
            <div class="columns is-multiline">
              <div class="column is-12  personal-dashboard personal-dashboard-v2">
                <div class="dashboard-card has-margin-bottom">
                  <div class="card-head">
                    <h3 class="dark-inverted">BILLING #{{ item.length }} - (Rp. {{ H.formatRp(item.BILLING, '') }}) </h3>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px;">
                      <VControl>
                        <VSwitchBlock v-model="item.isIUR" label="IUR Bayar" color="danger" @change="modalIur = true"
                          v-if="pasien.kelompokpasien != undefined && pasien.kelompokpasien.indexOf('BPJS') > -1" />
                      </VControl>
                      <VControl>
                        <VSwitchBlock v-model="item.isPenjamin" label="Multi Penjamin" color="danger"
                          @change="modalPenjamin = true" />
                      </VControl>
                    </div>
                  </div>
                  <div class="active-projects">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                        <div class="load-more-wrap has-text-centered p-1 mb-3">
                          <div class="columns is-multiline">
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status primary">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TAGIHAN</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.TOTAL, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status info">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">DEPOSIT</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.DEPOSIT, 'Rp.') }}</small>

                              </VCardCustom>
                            </div>
                            <div class="column is-3" v-if="item.total_multi == 0">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status warning">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TOTAL KLAIM</span>
                                </div>
                                <VField class=" h-100">
                                  <VControl icon="fas fa-calculator">
                                    <input v-model="item.DIKLAIM" type="text" class="input is-rounded"
                                      placeholder="TOTAL KLAIM " v-if="isHide" />
                                    <input v-model="item.DIKLAIM_TXT" type="text" class="input is-rounded"
                                      placeholder="TOTAL KLAIM " v-mask-currency />
                                  </VControl>
                                </VField>
                                <!-- <small class="text-bold-custom">{{ H.formatRp(item.SISA, 'Rp.')}}</small> -->
                              </VCardCustom>
                            </div>
                            <div class="column is-3" v-if="item.total_multi > 0">
                              <VCardCustom :style="'padding:5px 15px'">
                                <div class="label-status info">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TOTAL KLAIM MULTI</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.total_multi, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status purple">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">PIUTANG PASIEN</span>
                                </div>
                                <VField class=" h-100">
                                  <VControl icon="fas fa-calculator">
                                    <input v-model="item.DIKLAIMPASIEN" type="text" class="input is-rounded"
                                      placeholder="TOTAL KLAIM " v-if="isHide" />
                                    <input v-model="item.DIKLAIMPASIEN_TXT" type="text" class="input is-rounded"
                                      placeholder="TOTAL KLAIM " v-mask-currency />
                                  </VControl>
                                </VField>
                                <!-- <small class="text-bold-custom">{{ H.formatRp(item.SISA, 'Rp.')}}</small> -->
                              </VCardCustom>
                            </div>
                            <div class="column is-3 is-offset-1" v-if="item.IURBAYAR > 0">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status primary">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TOTAL DISKON</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.DISKON, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                            <div class="column is-3 is-offset-3" v-else>
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status primary">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">TOTAL DISKON</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.DISKON, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                            <div class="column is-3">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status danger">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">HARUS BAYAR</span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.DIBAYAR < 0 ? 0 :
                                  item.DIBAYAR, 'Rp.') }}</small>

                              </VCardCustom>
                            </div>
                            <div class="column is-3" v-if="item.PENGEMBALIAN > 0">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status info">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">PENGEMBALIAN </span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.PENGEMBALIAN, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                            <div class="column is-3" v-if="item.IURBAYAR > 0">
                              <VCardCustom :style="'padding:5px 25px'">
                                <div class="label-status danger">
                                  <i aria-hidden="true" class="fas fa-circle"></i>
                                  <span class="ml-1">IUR BAYAR </span>
                                </div>
                                <small class="text-bold-custom h-100">{{ H.formatRp(item.IURBAYAR, 'Rp.') }}</small>
                              </VCardCustom>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="column is-4">
                        <VField>
                          <VControl icon="feather:search">
                            <input v-model="filters" type="text" class="input is-rounded" placeholder="Filter " />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2 pt-1">
                        <VControl>
                          <VSwitchBlock v-model="isResep" :label="isResep ? 'Resep' : 'Layanan'" color="danger"
                            @change="chamgeResep(isResep)" />
                        </VControl>
                      </div>

                      <div class="column is-6">
                        <div class="content mt-0 mb-0 is-pulled-right">
                          <div class="is-divider mt-3 mb-2" data-content="Info"></div>
                          <VTag color="info" label="Layanan" />
                          <VTag color="info" :label="item.totalLayanan" outlined class="ml-1" />
                          <VTag color="danger" label="Resep" class="ml-3" />
                          <VTag color="danger" :label="item.totalResep" outlined class="ml-1" />
                        </div>
                      </div>
                      <div class="column is-12">
                        <div class="column is-12">
                          <table class="tb-custom mt-3">
                            <thead>
                              <tr>
                                <th class="text-center">
                                  <VControl raw subcontrol style="margin-top:-10px">
                                    <VCheckbox v-model="item.checkAll" label="Check All" color="info"
                                      @change="checkedAll(item.checkAll)" :value="item.checkAll" />
                                  </VControl>
                                </th>
                                <th width="30%">URAIAN</th>
                                <th>HARGA SATUAN</th>
                                <th>JUMLAH</th>
                                <th>SUBTOTAL</th>
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
                              <tbody v-if="!isLoadingBill" v-for="(items, index)  in dataSourcefiltered" :key="index">
                                <tr>
                                  <td colspan="5" class="koneng">
                                    {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                                  </td>
                                </tr>
                                <tr v-for="(itemsDet, index2)  in items.details" :key="index2">
                                  <td width="5%">
                                    <VControl raw subcontrol>
                                      <VCheckbox v-model="modelCheck[itemsDet.norec]" :value="itemsDet.norec" color="info"
                                        square :checked="modelCheck[itemsDet.norec]" @change="checkedItems()" />
                                    </VControl>
                                  </td>
                                  <td width="30%">
                                    <div class="columns is-multiline">
                                      <div class="column is-12" @click="checkedItemsLABEL(itemsDet.norec)"
                                        style="cursor:pointer">
                                        <div class="title-ruangan">{{ itemsDet.namaruangan }}</div>
                                        <div class="title-layan">{{ itemsDet.namaproduk }} - {{
                                          itemsDet.dokterpemeriksa ?
                                          itemsDet.dokterpemeriksa : 'Dokter belum di input'
                                        }}</div>
                                        <div class="title-layan">
                                          <VTag v-if="itemsDet.dokteroperasi" style="margin-left: auto;" class="mt-2"
                                            :style="{ 'background-color': '#bb2124', 'color': 'white', 'font-size': '12px' }"
                                            label="Tag Label" rounded elevated>
                                            {{ itemsDet.dokteroperasi }}
                                          </VTag>
                                        </div>
                                        <div class="title-layan">
                                          <VTag v-if="itemsDet.dokteranastesi" style="margin-left: auto;" class="mt-2"
                                            :style="{ 'background-color': '#5bc0de', 'color': 'white', 'font-size': '12px' }"
                                            label="Tag Label" rounded elevated>
                                            {{ itemsDet.dokteranastesi }}
                                          </VTag>
                                        </div>
                                        <div class="mt-1">
                                          <VTag :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                            :label="itemsDet.tglpelayanan" />
                                        </div>
                                        <div class="title-kelas">{{ itemsDet.namakelas }}</div>
                                      </div>
                                    </div>
                                  </td>
                                  <td class="center">
                                    <div class="columns is-multiline">
                                      <div class="column is-12">
                                        <div class="title-ruangan">Jasa : {{ H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                        <div class="title-layan">{{ H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}</div>
                                        <div class="title-kelas">Diskon : {{ H.formatRp(itemsDet.hargadiscount, 'Rp. ') }}
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                                  <td class="center">{{ itemsDet.jumlah }}</td>
                                  <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}</td>
                                </tr>
                              </tbody>
                              <div class="search-results-wrapper"
                                v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                                <div class="search-results-body ">
                                  <div class="page-placeholder">
                                    <div class="placeholder-content">
                                      <img class="light-image" style=" max-width: 340px;"
                                        :src="H.assets().iconNotFound_rev" alt="" />
                                      <img class="dark-image" style=" max-width: 340px;"
                                        :src="H.assets().iconNotFound_rev" alt="" />
                                      <h3>{{ H.assets().notFound }}</h3>
                                      <p class="is-larger">
                                        {{ H.assets().notFoundSubtitle }}
                                      </p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </table>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="content mt-0 mb-0">
                                            <div class="is-divider mt-3 mb-2" data-content="TOTAL"></div>
                                          </div> -->


                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>

  <Dialog v-model:visible="modalIur" modal header="IUR Bayar" :style="{ width: '40vw' }">
    <div class="columns is-multiline">
      <div class="column is-4">
        <VField label="Kelas Hak" class="is-rounded-select is-autocomplete-select  mt-0 pt-0 label-v3" v-slot="{ id }">
          <VControl icon="fas fa-arrows-alt-h" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.kelasHak" :options="d_KelasHak" :optionLabel="'nama'" class="is-rounded"
              placeholder="Kelas Hak" style="width: 100%;" :filter="true" showClear />
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Kelas Pelayanan" class="is-rounded-select is-autocomplete-select  mt-0 pt-0 label-v3"
          v-slot="{ id }">
          <VControl icon="fas fa-arrow-up" fullwidth class="prime-auto-select">
            <Dropdown v-model="item.kelasPelayanan" :options="d_KelasNaik" :optionLabel="'namakelas'" class="is-rounded"
              placeholder="Kelas Pelayanan" style="width: 100%;" :filter="true" showClear />
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField label="Jumlah Hari Naik Kelas" class="label-v3">
          <VControl icon="fas fa-bed">
            <input v-model="item.upgrade_class_los" type="number" class="input is-rounded"
              placeholder="Jumlah Hari Naik Kelas " />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField label="Tambahan Biaya Yang Dibayar Pasien " class="label-v3">
          <VControl icon="fas fa-calculator">
            <input v-model="item.iurBayar" type="text" class="input is-rounded"
              placeholder="Tambahan Biaya Yang Dibayar Pasien " />
          </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalIur = false">
        Tutup
      </VButton>
      <VButton rounded outlined :color="'warning'" class="mr-2" raised icon="fas fa-calculator" :loading="isTarif18"
        @click="detailTarif" bold>
        18 Variable Tarif
      </VButton>
      <VButtonImg rounded outlined :color="colorPLAFON" class="mr-2" icon="/images/icons/files/bpjs-no-bg.svg" raised
        :loading="isFlafon" @click="hitungBiayaIUR" bold
        v-tooltip-prime.top="'Hitung Tambahan Biaya Yang Dibayar Pasien'">
        Hitung IUR INACBG's
      </VButtonImg>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="saveIUR()"> Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalPenjamin" modal header="Multi Penjamin" :style="{ width: '60vw' }">
    <div class="columns is-multiline">
      <div class="column is-4">
        <VField class="is-rounded-select is-autocomplete-select mt-0 pt-0 label-v3">
          <label for="penjamin" class="required-label">Penjamin</label>
          <VControl>
            <Multiselect class="mt-2" id="penjamin" mode="single" v-model="item.rekanan" :options="d_Rekanan" placeholder="Pilih Rekanan / Penjamin"
              :searchable="true" />
          </VControl>
        </VField>
      </div>
      <div class="column is-4">
        <VField class="label-v3">
          <label for="penjamin" class="required-label">Jumlah Klaim</label>
          <VControl icon="fas fa-calculator">
            <input v-model="item.DIKLAIMMULTI" type="text" class="input is-rounded" placeholder="TOTAL KLAIM "
              v-if="isHide" />
            <input v-model="item.DIKLAIMMULTI_TXT" type="text" class="input is-rounded" placeholder="TOTAL KLAIM "
              v-mask-currency />
          </VControl>
        </VField>
      </div>
    </div>
    <VFlexTable :columns="columns" rounded>
      <template #body>
        <div name="list" tag="div" class="flex-list-inner">
          <div v-for="(items, index)  in dataSourceMultiBayar" class="flex-table-item">
            <VFlexTableCell>
              <span class="light-text"> {{ items.no }}</span>
            </VFlexTableCell>
            <VFlexTableCell>
              <span class="light-text"> {{ H.formatRp(items.klaim_multi, 'Rp.') }}</span>
            </VFlexTableCell>
            <VFlexTableCell>
              <span class="light-text"> {{ getRekananLabel(items.objectrekananfk) }}</span>
            </VFlexTableCell>
            <VFlexTableCell :column="{ align: 'end' }">
              <VIconButton circle icon="fas fa-times-circle" color="danger" raised bold @click="hapusItems(items)"
                v-tooltip.bubble="'Batal Klaim'">
              </VIconButton>
            </VFlexTableCell>
          </div>
        </div>
      </template>
    </VFlexTable>
    <template #footer>
      <div class="columns">
        <div class="column is-2">
          <VCardCustom :style="'padding:5px 15px'">
            <div class="label-status danger">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">HARUS BAYAR</span>
            </div>
            <small class="text-bold-custom h-100">{{ H.formatRp(item.DIBAYAR < 0 ? 0 : item.DIBAYAR, 'Rp.') }}</small>
          </VCardCustom>
        </div>
        <div class="column is-2">
          <VCardCustom :style="'padding:5px 15px'">
            <div class="label-status info">
              <i aria-hidden="true" class="fas fa-circle"></i>
              <span class="ml-1">TOTAL KLAIM MULTI</span>
            </div>
            <small class="text-bold-custom h-100">{{ H.formatRp(item.total_multi, 'Rp.') }}</small>
          </VCardCustom>
        </div>
      </div>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="modalPenjamin = false">
        Tutup
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
        @click="tambah()"> Tambah
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalDetail" modal header="Detail 18 Variable" :style="{ width: '70vw' }">
    <div class="columns is-multiline">
      <div class="column is-12">
        <div>

          <VCard class="mt-2">
            <div class="columns is-multiline">
              <div class="column is-4">
                <listWidgetCustom title="Prosedur Non Bedah"
                  :color="tarif18.prosedur_non_bedah > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.prosedur_non_bedah, 'Rp.')" />
                <listWidgetCustom title="Tenaga Ahli" :color="tarif18.tenaga_ahli > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.tenaga_ahli, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Radiologi" :color="tarif18.tenaga_ahli > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.radiologi, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Rehabilitasi" :color="tarif18.rehabilitasi > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.rehabilitasi, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Obat" :color="tarif18.obat > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.obat, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Alkes" :color="tarif18.alkes > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.alkes, 'Rp.')" class="mt-1" />
              </div>
              <div class="column is-4">
                <listWidgetCustom title="Prosedur Bedah" :color="tarif18.prosedur_non_bedah > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.prosedur_non_bedah, 'Rp.')" />
                <listWidgetCustom title="Keperawatan" :color="tarif18.keperawatan > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.keperawatan, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Laboratorium" :color="tarif18.laboratorium > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.laboratorium, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Kamar / Akomodasi" :color="tarif18.kamar > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.kamar, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Obat Kronis" :color="tarif18.obat_kronis > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.obat_kronis, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="BMHP" :color="tarif18.bmhp > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.bmhp, 'Rp.')" class="mt-1" />
              </div>
              <div class="column is-4">
                <listWidgetCustom title="Konsultasi" :color="tarif18.konsultasi > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.konsultasi, 'Rp.')" />
                <listWidgetCustom title="Penunjang" :color="tarif18.penunjang > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.penunjang, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Pelayanan Darah" :color="tarif18.pelayanan_darah > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.pelayanan_darah, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Rawat Intensif" :color="tarif18.rawat_intensif > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.rawat_intensif, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Obat Kemoterapi" :color="tarif18.obat_kemoterapi > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.obat_kemoterapi, 'Rp.')" class="mt-1" />
                <listWidgetCustom title="Sewa Alat" :color="tarif18.sewa_alat > 0 ? 'success' : 'warning'"
                  :subtitle="H.formatRp(tarif18.sewa_alat, 'Rp.')" class="mt-1" />
              </div>
            </div>
          </VCard>

          <Fieldset legend="Tarif Belum Dimapping" :toggleable="true"
            :collapsed="tarif18.belum_mapping.length > 0 ? false : true" class="mt-2">
            <div class="columns is-multiline">
              <div class="column is-3" v-for="item in tarif18.belum_mapping" :key="item.norec">
                <TStatusPojokKanan :title="item.namaproduk" :subtitle="H.formatRp(item.ttl, 'Rp.')"
                  class="inbox-widget-2" />
              </div>
            </div>

          </Fieldset>

          <VCard class="mt-2">
            <div class="columns is-multiline">
              <div class="column is-6">
                <b>Tarif Rumah Sakit INACBG's : {{ H.formatRp(tarif18.totalmappingtarif, 'Rp. ') }}</b>
              </div>
              <div class="column is-6">
                <b>Total Billing : {{ H.formatRp(tarif18.totalbilling, 'Rp. ') }}</b>
              </div>
            </div>
          </VCard>

        </div>
      </div>
    </div>
  </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import { useUserSession } from '/@src/stores/userSession'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { useConfirm } from "primevue/useconfirm";
import ConfirmDialog from 'primevue/confirmdialog';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
useHead({
  title: 'Verifikasi Tagihan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
let d_KelompokPasien: any = ref([])
// let d_Rekanan: any = ref([])
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
const isLoadingPasien: any = ref(false)
const columns = {
  no: 'NO',
  klaim_multi: 'JUMLAH KLAIM',
  objectrekananfk: 'NAMA PENJAMIN',
} as const

const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  total_multi: 0,
  total_multiTxt: 0,
  objectrekananfk: '',
  registrasi: {},
  tglorder: new Date(),
  produkCeklis: [],
  pegawaiOrder: useUserSession().getUser().id,
  totalResep: 0,
  totalLayanan: 0,
  DEPOSIT: 0,
  DIBAYAR: 0,
  DIKLAIM: 0,
  DIKLAIMMULTI: 0,
  DIKLAIMPASIEN: 0,
  TOTAL: 0,
  PENGEMBALIAN: 0
})
const d_Rekanan = [];
const label = getRekananLabel(item.objectrekananfk);
const isDetail: any = ref([true])
const data2: any = ref([])
const dataSourceMultiBayar: any = ref([])
const modalDetail: any = ref(false)
const isTarif18: any = ref(false)
const HASILGROUPING: any = ref(0)
const tarif18: any = ref({})
const isHide: any = ref(false)
const modelCheck: any = ref([])
const isLoadingBill: any = ref(false)
const listChecked: any = ref([])
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i];
  if (i <= 9 && element != 'primary')
    listColor.value.push(element)
}
const listColor2: any = ref(['primary', 'info', 'orange', 'yellow', 'success'])
const d_KelasHak: any = ref([
  { kode: 1, nama: 'Kelas 1' },
  { kode: 2, nama: 'Kelas 2' },
  { kode: 3, nama: 'Kelas 3' },
])
const d_KelasNaik: any = ref([])

const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const pasien: any = ref({})
const isLoading = ref(false)
const router = useRouter()
const filters = ref('')
const filtersHide = ref('')
const dataSource: any = ref([])
const isResep = ref(false)
const isFlafon: any = ref(false)
const dataSourceINACBG: any = ref([])
const colorPLAFON: any = ref('info')
const modalIur: any = ref(false)
const modalPenjamin: any = ref(false)
const dataSourcefiltered = computed(() => {
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
          || element2.namaruangan.match(new RegExp(filters.value, 'i'))
          || element2.dokterpemeriksa.match(new RegExp(filters.value, 'i'))

        ) {
          filteredD.push(element2);
          filtered.push({
            tglpelayanan_group: element.tglpelayanan_group,
            details: filteredD
          })
          break
        }
      } else if (filtersHide.value) {
        if (element2.jenis.match(new RegExp(filtersHide.value, 'i'))
        ) {
          for (let xxx = 0; xxx < filtered.length; xxx++) {
            const elementxxx = filtered[xxx];
            if (elementxxx.tglpelayanan_group == element.tglpelayanan_group) {
              filtered.splice(xxx, 1)
            }
          }
          filteredD.push(element2);
          filtered.push({
            tglpelayanan_group: element.tglpelayanan_group,
            details: filteredD
          })
          // break
        }
      }

    }
  }
  return filtered;
})
const confirm = useConfirm();

const confirmPembayaran = (response: any) => {
  confirm.require({
    group: 'positionDialog',
    message: 'Lanjut ke pembayaran?',
    header: 'Info ',
    icon: 'pi pi-info-circle',
    position: 'top',
    accept: () => {
      router.push({
        name: 'module-kasir-pembayaran-tagihan',
        query: {
          norec_sp: response.norec,
          norec_pd: NOREC_PD,
          pageFrom: 'tagihanPasien'
        },
      })
    },
    reject: () => {
      window.history.back()
    }
  });
};
const confirmPengembalian = () => {
  confirm.require({
    group: 'positionDialog',
    message: 'Lanjut ke pengembalian Deposit?',
    header: 'Info ',
    icon: 'pi pi-info-circle',
    position: 'top',
    accept: () => {
      router.push({
        name: 'module-kasir-pembayaran-tagihan',
        query: {
          norec_pd: NOREC_PD,
          totalBayar: parseFloat(item.DIBAYAR) * -1,
          pageFrom: 'pengembalianDeposit'
        },
      })
    },
    reject: () => {
    }
  });
}
const pasienByID = async (id: any) => {
  isLoadingPasien.value = true
  useApi().get(
    `/general/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then(async (response: any) => {
      pasien.value = response.pasien
      pasien.value.isClosing = response.registrasi[0].isclosing
      pasien.value.namaruangan = response.last_registrasi.namaruangan
      pasien.value.kelompokpasien = response.last_registrasi.kelompokpasien
      pasien.value.noregistrasi = response.last_registrasi.noregistrasi
      pasien.value.tglregistrasi = H.formatDateIndoSimple(response.last_registrasi.tglregistrasi)
      pasien.value.status = response.last_registrasi.statusbayar
      pasien.value.kelas = response.registrasi?.[0]?.namakelas || []
      pasien.value.rekanan = response.registrasi?.[0]?.namarekanan || []
      pasien.value.kelasditanggung = response.registrasi?.[0]?.kelasditanggung || []
      pasien.value.nmprovider = response.registrasi?.[0]?.nmprovider ?? ''
      pasien.value.klsrawathak_kode = response.registrasi?.[0]?.klsrawathak_kode ?? ''

      item.inacbg_totalgrouper = response.last_registrasi.inacbg_totalgrouper
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false

      // item.DIKLAIM_TXT = item.inacbg_totalgrouper != null ? H.formatRupiah(parseFloat(item.inacbg_totalgrouper), '') : 0
      // item.iurBayar = item.inacbg_totalgrouper != null ? H.formatRupiah(parseFloat(item.inacbg_totalgrouper), '') : 0
      // fetchTindakan(item.RUANGAN_LAST)
      if (pasien.value.kelompokpasien.indexOf('BPJS') > -1) {
        await fetchKelas()
        d_KelasHak.value.forEach(element => {
          if (element.kode == pasien.value.klsrawathak_kode) {
            item.kelasHak = element
          }
        });
        d_KelasNaik.value.forEach(element => {
          if (element.namakelas == pasien.value.kelas) {
            item.kelasPelayanan = element
          }
        });

        item.upgrade_class_los = H.daysDifference(response.last_registrasi.tglregistrasi, (response.last_registrasi.tglpulang != null ? response.last_registrasi.tglpulang : H.formatDate(new Date(), 'YYYY-MM-DD')))
        if (item.upgrade_class_los == 0) {
          item.upgrade_class_los = 1
        } else {
          item.upgrade_class_los = parseInt(item.upgrade_class_los)
        }

      }
    })
}

function tambah() {
  if (!item.rekanan) {
        useToaster().warn('Penjamin Harus Dipilih')
        return
    }
  // console.log(item)
  let nomor = 0
  if (data2.value.length == 0) {
    nomor = 1
  } else {
    nomor = data2.value.length + 1
  }
  var data: any = {};
  if (item.no != undefined) {
    for (let x = 0; x < data2.value.length; x++) {
      const element = data2.value[x];

      if (element.no == item.no) {
        data.no = item.no
        data.objectrekananfk = item.rekanan
        data.klaim_multi = item.DIKLAIMMULTI
        data.klaim_multiTxt = item.DIKLAIMMULTI_TXT
        data2.value[x] = data;
      }
    }
  } else {

    data = {
      'no': nomor,
      'objectrekananfk': item.rekanan,
      'klaim_multi': item.DIKLAIMMULTI,
      'klaim_multiTxt': item.DIKLAIMMULTI_TXT
    }
    data2.value.push(data)
  }
  isDetail.value[data2.value.length] = true
  dataSourceMultiBayar.value = data2.value
  hitungTotalKlaim()
  hitungTotalKlaimTxt()
  console.log(dataSourceMultiBayar.value)
}

function hapusItems(e: any) {
  for (var i = data2.value.length - 1; i >= 0; i--) {
    if (data2.value[i].no == e.no) {
      data2.value.splice(i, 1);
    }
  }
  dataSourceMultiBayar.value = data2.value
  hitungTotalKlaim()
  hitungTotalKlaimTxt()
}

function hitungTotalKlaim() {
  let total = 0
  for (let x = 0; x < data2.value.length; x++) {
    const element = data2.value[x];
    total = total + parseFloat(element.klaim_multi)
  }
  item.total_multi = total
}
function hitungTotalKlaimTxt() {
  let totalTxt = 0
  for (let x = 0; x < data2.value.length; x++) {
    const element = data2.value[x];
    totalTxt = totalTxt + parseFloat(element.klaim_multiTxt)
  }
  item.total_multiTxt = totalTxt
}

const getListCombo = async () => {
  await useApi().get('/kasir/list-item-pendukung').then((response) => {
    if (Array.isArray(response.rekanan)) {
      d_Rekanan.push(...response.rekanan.map((e) => {
        return { label: e.namarekanan, value: e.id, default: e };
      }));
    }
  });
};
function getRekananLabel(objectrekananfk) {
  const rekanan = d_Rekanan.find(rekanan => rekanan.value === objectrekananfk);
  return rekanan ? rekanan.label : objectrekananfk;
}
const isDecimal = (value) => {
  return Number.isFinite(value) && !Number.isInteger(value);
}
async function fetchBill() {
  isLoadingBill.value = true
  dataSource.value = []
  item.BILLING = 0
  item.DEPOSIT = 0
  item.PENGEMBALIAN = 0
  await useApi().get(
    `/kasir/verifikasi-tagihan?norec_pd=${NOREC_PD}&strukfk=null`).then(async (response: any) => {
      item.totalLayanan = 0
      item.totalResep = 0
      for (let x = 0; x < response.detail.length; x++) {
        const element = response.detail[x];
        for (let y = 0; y < element.details.length; y++) {
          const element2 = element.details[y];
          if (element2.strukresepfk == null) {
            item.totalLayanan = item.totalLayanan + 1
          } else {
            item.totalResep = item.totalResep + 1
          }
        }
      }
      dataSource.value = response.detail
      // item.DIBAYAR = response.dibayar
      // item.SISA = response.sisa
      // item.TOTAL = response.total
      item.BILLING = Math.round(response.total)
      item.DEPOSIT = response.deposit
      item.checkAll = true
      checkedAll(item.checkAll)
      item.DISKON = response.diskon
      item.length = response.length
      // LISTRUANGAN_APD.value = response.list_ruangan
      // LISTRUANGAN_APD_G.value = groupRuang(response.list_ruangan)
      isLoadingBill.value = false
    })
}

function chamgeResep(e: any) {
  if (e == true) {
    filtersHide.value = 'Resep'
  } else {
    filtersHide.value = ''
  }
}


function simpan() {

  // if(pasien.value.isClosing == null){
  //   H.alert('error','Pasien belum diclosing')
  //   return
  // }
  if (listChecked.value.length == 0) {
    H.alert('error', 'Ceklis data terlebih dahulu ruangan tujuan')
    return
  }
  var objSave = {
    norec_pd: NOREC_PD,
    noregistrasi: pasien.value.noregistrasi,
    nocm: pasien.value.nocm,
    namapasien: pasien.value.namapasien,
    total: item.TOTAL,
    deposit: item.DEPOSIT,
    klaim: item.DIKLAIM,
    klaim_multi: item.total_multi,
    savemulti: data2.value,
    klaim_pasien: item.DIKLAIMPASIEN,
    totalbayar: item.DIBAYAR < 0 ? 0 : item.DIBAYAR,
    totaliurbayar: item.IURBAYAR != undefined ? item.IURBAYAR : 0,
    details: listChecked.value,
  }
  isLoading.value = true
  useApi().post(
    `/kasir/verifikasi-tagihan/simpan`, objSave).then((response: any) => {
      isLoading.value = false
      if (item.DIBAYAR > 0 || item.IURBAYAR > 0) {
        confirmPembayaran(response.sp)
      } else if (item.DIBAYAR < 0) {
        confirmPengembalian()
      } else {
        window.history.back()
      }

      delete item.NOREC_SO
    }).catch((e: any) => {
      isLoading.value = false
    })


}
function checkedAll(e: any) {
  modelCheck.value = []
  listChecked.value = []
  if (e) {
    dataSource.value.forEach((e: any) => {
      e.details.forEach((f: any) => {
        listChecked.value.push(f)
        modelCheck.value[f.norec] = true
        // modelCheck.value.push({ [f.norec]: true })
      });
    });
  }
  setTotalChecked()
}
function setTotalChecked() {

  let jml723 = 0
  let tol = 0
  item.DIBAYAR = tol
  if (item.DIKLAIM == null || item.DIKLAIM == 0)
    item.DIKLAIM = tol;
  if (item.DIKLAIMMULTI == null || item.DIKLAIMMULTI == 0)
    item.DIKLAIMMULTI = tol;
  item.TOTAL = Math.round(tol)
  for (var i = 0; i < listChecked.value.length; i++) {
    const element = listChecked.value[i]
    if (element.iskronis == true) {
      jml723 = (parseFloat(element.total) / 30) * 7
    }
    tol = parseFloat(element.total) + tol
    item.DIBAYAR = Math.round(tol)
    item.TOTAL = Math.round(tol)
    if (pasien.value.kelompokpasien && pasien.value.kelompokpasien.indexOf('BPJS') > -1) {
      // debugger
      item.DIKLAIM_TXT = H.formatRupiah(parseFloat(tol - jml723), '')
      item.DIKLAIMMULTI_TXT = H.formatRupiah(parseFloat(tol - jml723), '')
      // item.DIKLAIM = tol - jml723;
    } else {
      // item.DIKLAIM = 0;
      item.DIKLAIMMULTI_TXT = 0;
    }
  }
  item.DIBAYAR = item.DIBAYAR - item.DEPOSIT - item.DIKLAIM - item.DIKLAIMMULTI
  item.PENGEMBALIAN = item.DEPOSIT > 0 ? (item.DIBAYAR < 0 ? (item.DIBAYAR * -1) : 0) : 0
}
function checkedItemsLABEL(e: any) {
  modelCheck.value[e] = !modelCheck.value[e]
  checkedItems()
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
  setTotalChecked()
}
function kembaliKeun() {
  window.history.back()
}

const hitungBiayaSementara = async () => {
  isFlafon.value = true
  const e = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd='
    + NOREC_PD)
  if (e.set_claim_data == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Tidak ada')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Belum di isi')
    return
  }
  if (e.set_claim_data.data.diagnosa == null ||
    e.set_claim_data.data.diagnosa == '' ||
    e.set_claim_data.data.diagnosa == false) {
    isFlafon.value = false
    H.alert('info', 'Data Diagnosa Belum di isi')
    return
  }
  dataSourceINACBG.value = e.data
  isFlafon.value = true
  let json = []
  json.push(e.new_claim)
  json.push(e.set_claim_data)

  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {

    for (let x = 0; x < r.response.dataresponse.length; x++) {
      const element = r.response.dataresponse[x];
      if (element.dataresponse.metadata.code == 200) {
        await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {

          let arrGroup = []
          for (let x = 0; x < rr.response.dataresponse.length; x++) {
            const elementx = rr.response.dataresponse[x];
            if (elementx.dataresponse.metadata.code == 200) {
              arrGroup.push({
                'nomor_sep': elementx.datarequest.data.nomor_sep,
                'inacbg_status': elementx.dataresponse.metadata.method,
                'dataresponse': elementx.dataresponse
              })
              break
            } else {
              H.alert('error', elementx.dataresponse.response.cbg.description)
            }
          }

          await saveGrouping(arrGroup, true)
          await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
            let arrStatus = []
            for (let x = 0; x < xx.response.dataresponse.length; x++) {
              const element = xx.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                  'nomor_sep': element.datarequest.data.nomor_sep,
                  'inacbg_status': null
                })
                break
                // H.alert('success', element.dataresponse.metadata.message)
              } else {
                H.alert('error', element.dataresponse.metadata.message)
              }
            }
            await saveStatus(arrStatus, true)
          })
        })
        break
      } else {
        if (element.dataresponse.metadata.message != 'Duplikasi nomor SEP') {
          H.alert('error', element.dataresponse.metadata.message)
        }
      }
    }

    isFlafon.value = false
  }, (error) => {
    isFlafon.value = false
  })
}
const saveGrouping = async (e: any, load: boolean) => {
  if (!e.length) return
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
        elem2.jenis_rawat = element.jenis_rawat
      }
    }
  }
  let tarifINACB = 0
  for (var ii = 0; ii < e.length; ii++) {
    const elem2 = e[ii]
    let totaldijamin = 0
    let biayanaikkelas = 0
    if (elem2.jenis_rawat == 1) {
      totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
    } else {
      let hakkelas = elem2.dataresponse.response.kelas
      if (hakkelas == "kelas_1") {
        totaldijamin = elem2.dataresponse.tarif_alt[0].tarif_inacbg
      } else if (hakkelas == "kelas_2") {
        totaldijamin = elem2.dataresponse.tarif_alt[1].tarif_inacbg
      } else if (hakkelas == "kelas_3") {
        totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
      }

      biayanaikkelas = elem2.dataresponse.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
      if (biayanaikkelas < 0) {
        biayanaikkelas = 0
      }
    }
    let json = {
      'norec': elem2.norec,
      'totaldijamin': totaldijamin,
      'biayanaikkelas': biayanaikkelas,
      'inacbg_grouper': elem2.dataresponse
    }
    tarifINACB = totaldijamin
    await useApi().postBPJS('/bridging/inacbgs/save-grouping', json)

  }
  // if (load)
  // fetchDataINACBG(tarifINACB)
}
const fetchDataINACBG = async (tarifINACB: any) => {
  if (tarifINACB != undefined) {
    H.alert('success', 'Sukses')
  }
  item.inacbg_totalgrouper = tarifINACB
  item.DIKLAIM_TXT = tarifINACB
  item.DIKLAIMMULTI_TXT = tarifINACB

  setColorFlafon({
    'inacbg_totalgrouper': tarifINACB,
    'billing': item.BILLING,
  })
}

const setColorFlafon = (element: any) => {
  let ditanggung = parseFloat(element.inacbg_totalgrouper)
  let totaltagihan = parseFloat(element.billing);
  let presn = ditanggung * 0.1
  let totalPersen = ditanggung - presn

  if (ditanggung != 0 && totaltagihan >= ditanggung) {
    colorPLAFON.value = 'danger'
  } else if (ditanggung != 0 && totaltagihan >= totalPersen) {
    colorPLAFON.value = 'warning'
  } else {
    colorPLAFON.value = 'info'
  }
}
const saveStatus = async (e: any, load: boolean) => {
  if (!e.length) return
  let jsons = []
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
        jsons.push(elem2)
      }
    }
  }
  if (jsons.length == 0) return

  await useApi().postBPJS('/bridging/inacbgs/save-status', { 'data': jsons }).then(async (r) => {
    // if (load)
    // fetchData()
    isFlafon.value = false
  }).catch((e: any) => {
    isFlafon.value = false
  })
}
const billing = () => {
  router.push({
    name: 'module-kasir-billing',
    query: {
      norec_pasien_daftar: NOREC_PD,
    },
  })
}
const fetchKelas = async () => {
  await useApi().get('/kasir/list-kelas').then(async (r) => {
    d_KelasNaik.value = r.kelas
  })
}
const hitungBiayaIUR = async () => {
  isFlafon.value = true
  const e = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd='
    + NOREC_PD)
  if (e.set_claim_data == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Tidak ada')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {
    // isFlafon.value = false
    let nosepTEMP = '9999R9999999V000999'
    e.new_claim.data.nomor_sep = nosepTEMP
    e.set_claim_data.data.nomor_sep = nosepTEMP
    e.set_claim_data.metadata.nomor_sep = nosepTEMP
    e.grouper.data.nomor_sep = nosepTEMP
    e.delete_claim.data.nomor_sep = nosepTEMP

    // H.alert('info', 'Data SEP Belum di isi')
    // return
  }

  if (e.set_claim_data.data.jenis_rawat != 1) {
    isFlafon.value = false
    H.alert('error', 'IUR Bayar INACBG hanya untuk Rawat Inap')
    return
  }
  if (e.set_claim_data.data.diagnosa == null ||
    e.set_claim_data.data.diagnosa == '' ||
    e.set_claim_data.data.diagnosa == false) {
    isFlafon.value = false
    H.alert('info', 'Data Diagnosa Belum di isi')
    return
  }

  if (!item.kelasHak) {
    H.alert('info', 'Kelas Hak Harus di isi')
    return
  }
  if (!item.kelasPelayanan) {
    H.alert('info', 'Kelas Pelayanan/Naik Harus di isi')
    return
  }
  if (!item.upgrade_class_los) {
    H.alert('info', 'Kelas Pelayanan/Naik Harus di isi')
    return
  }


  e.set_claim_data.data.kelas_rawat = item.kelasHak.kode
  e.set_claim_data.data.upgrade_class_ind = "1"
  e.set_claim_data.data.upgrade_class_class = item.kelasPelayanan.namabpjs
  e.set_claim_data.data.upgrade_class_los = item.upgrade_class_los
  e.set_claim_data.data.upgrade_class_payor = "peserta"
  e.set_claim_data.data.add_payment_pct = ''
  if (item.kelasPelayanan.namabpjs == 'vip' || item.kelasPelayanan.namabpjs == 'vvip') {

    // if (e.data[0].belum_mapping.length > 0) {
    //   H.alert('error', '18 Variable tarif masih ada yang belum di mapping')
    //   detailTarif()
    //   return
    // }
  }
  dataSourceINACBG.value = e.data
  isFlafon.value = true
  let json = []
  json.push(e.new_claim)
  json.push(e.set_claim_data)

  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {

    for (let x = 0; x < r.response.dataresponse.length; x++) {
      const element = r.response.dataresponse[x];
      if (element.dataresponse.metadata.code == 200
      ) {
        if (element.datarequest.metadata.method == 'set_claim_data') {
          await lanjutIUR(e)
          break
        }
      } else {
        if (element.dataresponse.metadata.message != 'Duplikasi nomor SEP') {
          H.alert('error', element.dataresponse.metadata.message)
        }
        // else{
        // lanjutIUR(e)
        // }
      }
    }

    isFlafon.value = false
  }, (error) => {
    isFlafon.value = false
  })
}
const lanjutIUR = async (e: any) => {
  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {

    let arrGroup = []
    for (let x = 0; x < rr.response.dataresponse.length; x++) {
      const elementx = rr.response.dataresponse[x];
      if (elementx.dataresponse.metadata.code == 200) {
        let tarifNAIKKELAS = 0;
        if (elementx.dataresponse.response.add_payment_amt != undefined) {
          // item.DIBAYAR = elementx.dataresponse.response.add_payment_amt
          item.iurBayar = elementx.dataresponse.response.add_payment_amt
          HASILGROUPING.value = elementx.dataresponse.response.cbg.tariff
          if ((item.kelasPelayanan.namabpjs == 'vip' || item.kelasPelayanan.namabpjs == 'vvip') && HASILGROUPING.value != 0) {
            let KOEFISIEN_NAIK_DIATAS_KELAS1 = (e.data[0].totalbilling - HASILGROUPING.value) / HASILGROUPING.value * 100
            if (KOEFISIEN_NAIK_DIATAS_KELAS1 > 75) {
              KOEFISIEN_NAIK_DIATAS_KELAS1 = 75 // peraturan kemkes max koefisien naik 75%
            }
            // untuk naik dari kelas 2 ke VIP
            if(item.kelasHak.nama == 'Kelas 2'){
              const tarifINACB1 = elementx.dataresponse.tarif_alt[0].tarif_inacbg
              const tarifINACB2 = elementx.dataresponse.tarif_alt[1].tarif_inacbg
              const selisihTarif = tarifINACB1 - tarifINACB2
              const persentase = 0.75*tarifINACB1
              const akumulasi = parseFloat(tarifINACB2) + parseFloat(selisihTarif) + parseFloat(persentase)
              const riilCost = e.data[0].totalbilling
              console.log(tarifINACB1, tarifINACB2, selisihTarif, persentase, akumulasi, riilCost)
              if(riilCost < tarifINACB2){
                item.iurBayar = 0
                break
              }else{
                if(akumulasi < riilCost){
                  item.iurBayar = selisihTarif + persentase
                  break
                }else{
                  item.iurBayar = riilCost - tarifINACB2
                  break
                }
              }
            }
            setIURBAYAR_HITUNG_KOEFISIEN(KOEFISIEN_NAIK_DIATAS_KELAS1.toFixed(5), HASILGROUPING.value)
            break;
          } else {
            H.alert('info', 'Tambahan Biaya Yang Dibayar Pasien Untuk Naik/Turun Kelas : ' + H.formatRp(elementx.dataresponse.response.add_payment_amt, 'Rp.'))
          }
        }
        // else{
        //   H.alert('info', elementx.dataresponse.response.cbg.description)
        // }
        arrGroup.push({
          'nomor_sep': elementx.datarequest.data.nomor_sep,
          'inacbg_status': elementx.dataresponse.metadata.method,
          'dataresponse': elementx.dataresponse
        })
        break
      } else {
        H.alert('error', elementx.dataresponse.metadata.message)
      }
    }

    // await saveGrouping(arrGroup, true)
    await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
      let arrStatus = []
      for (let x = 0; x < xx.response.dataresponse.length; x++) {
        const element = xx.response.dataresponse[x];
        if (element.dataresponse.metadata.code == 200) {
          arrStatus.push({
            'nomor_sep': element.datarequest.data.nomor_sep,
            'inacbg_status': null
          })
          break
          // H.alert('success', element.dataresponse.metadata.message)
        } else {
          H.alert('error', element.dataresponse.metadata.message)
        }
      }
      await saveStatus(arrStatus, true)
    })
  })
}
const setIURBAYAR_HITUNG_KOEFISIEN = (koefisien: any, klaim: any) => {
  let tambahanbiaya = klaim - klaim + (klaim * parseFloat(koefisien) / 100)
  tambahanbiaya = tambahanbiaya.toFixed(0)
  item.iurBayar = tambahanbiaya
  H.alert('info', 'Tambahan Biaya Yang Dibayar Pasien Untuk Naik/Turun Kelas : ' + H.formatRp(tambahanbiaya, 'Rp.'))
}
const saveIUR = () => {
  if (!item.iurBayar) {
    H.alert('error', 'Tambahan Biaya harus di isi')
    return
  }
  item.IURBAYAR = item.iurBayar
  modalIur.value = false
}
const detailTarif = async () => {
  isTarif18.value = true
  const x = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd=' + NOREC_PD)
  isTarif18.value = false
  let e = x.data[0]
  tarif18.value = e.tarif_rs
  tarif18.value.totalmappingtarif = e.totalmappingtarif
  tarif18.value.totalbilling = e.totalbilling
  tarif18.value.belum_mapping = e.belum_mapping
  modalDetail.value = true
}
watch(
  () => item.DIKLAIM,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue > item.TOTAL && item.inacbg_totalgrouper == null) {
        item.DIKLAIM = 0
      }
      item.DIBAYAR = item.TOTAL - parseFloat(newValue) - item.DEPOSIT - item.DIKLAIMPASIEN;
    }
  }
)
watch(
  () => item.total_multi,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (newValue > item.TOTAL && item.inacbg_totalgrouper == null) {
        item.total_multi = 0
      }
      item.DIBAYAR = item.TOTAL - parseFloat(newValue) - item.DEPOSIT - item.DIKLAIMPASIEN;
    }
  }
)
watch(
  () => item.DIKLAIMMULTI_TXT,
  (newValue, oldValue) => {
    item.DIKLAIMMULTI = H.unFormatRupiah(newValue)
  }
)
watch(
  () => item.DIKLAIMPASIEN,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      if (item.DIKLAIM > 0) {
        item.DIBAYAR = item.TOTAL - parseFloat(newValue) - item.DEPOSIT - item.DIKLAIM;
      } else {
        item.DIBAYAR = item.TOTAL - parseFloat(newValue) - item.DEPOSIT - item.DIKLAIMMULTI;
      }
    }
  }
);

watch(
  () => item.DIKLAIM_TXT,
  (newValue, oldValue) => {
    item.DIKLAIM = H.unFormatRupiah(newValue)
  }
)
watch(
  () => item.DIKLAIMPASIEN_TXT,
  (newValue, oldValue) => {
    item.DIKLAIMPASIEN = H.unFormatRupiah(newValue)
  }
)
watch(
  () => modalIur.value,
  (newValue, oldValue) => {
    if (newValue == false)
      item.isIUR = false
  }
)
watch(
  () => modalPenjamin.value,
  (newValue, oldValue) => {
    if (newValue === true) {
      item.DIKLAIM = '0';
      item.DIKLAIM_TXT = '0';
    }
  }
)
watch(
  () => modalPenjamin.value,
  (newValue, oldValue) => {
    if (newValue === false) {
      item.isPenjamin = false
    }
  }
)

getListCombo()
pasienByID(ID_PASIEN)
fetchBill()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/kasir/billing';
@import '/@src/scss/module/kasir/verifikasi-tagihan';

.form-layout.is-separate {
  width: 100%;
}

.form-layout .form-outer .form-body {
  padding: 0;
}

.field.label-v3>label {
  color: var(--light-text) !important;
}

.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
<style lang="scss">
.table-fro {
  width: 100%;
  border: 1px solid black;
}

.th-fro,
.td-fro {
  padding: 7px;
  border: 1px solid black;
  vertical-align: inherit;
}

.setFRO-center {
  text-align: center !important;
}

.p-fieldset-legend {
  margin-left: 15px;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  border-radius: 10px;
  overflow: hidden;
  width: 100%;
  margin: 0 auto;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 16px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 16px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
  font-weight: bold;
}

.tg th {
  background-color: lightblue !important;
}

.required-label::after {
  content: " *";
  color: red;
}
</style>

