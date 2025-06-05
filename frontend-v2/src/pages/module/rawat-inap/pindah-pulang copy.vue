<template>
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>Pindah Atau Pulang</h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VButton
                icon="lnir lnir-arrow-left rem-100"
                light
                dark-outlined
                @click="back()"
              >
                Kembali
              </VButton>
              <!-- <VButton type="submit" color="primary" raised icon="feather:save" :loading="isLoading"
                                @click="simpan()"> Save
                            </VButton> -->
            </div>
          </div>
        </div>
      </div>

      <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12" v-if="!isLoadingPasien">
              <div class="block-header">
                <div class="left">
                  <div class="current-user">
                    <!-- <VAvatar size="medium" :color="'warning'" :initials="'ER'" /> -->
                    <VAvatar
                      size="medium"
                      :picture="
                        pasien.jeniskelamin == 'PEREMPUAN'
                          ? '/images/avatars/svg/vuero-4.svg'
                          : '/images/avatars/svg/vuero-1.svg'
                      "
                      squared
                    />
                    <h3>{{ pasien.namapasien }}</h3>
                    <!-- <p class="block-text">
                                    {{ 'No RM : ' + pasien.nocm + (pasien.jeniskelamin ==
                                            'PEREMPUAN' ? ' (P)'
                                            :
                                            ' (L)')
                                    }}</p> -->
                  </div>
                </div>
                <div class="center">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No RM</h4>
                      <p class="block-text">{{ pasien.nocm }}</p>
                      <h4 class="block-heading">Tgl Lahir</h4>
                      <p class="block-text">{{ pasien.tgllahir }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">NIK</h4>
                      <p class="block-text">{{ pasien.noidentitas }}</p>
                      <h4 class="block-heading">Kelamin</h4>
                      <p class="block-text">{{ pasien.jeniskelamin }}</p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No HP</h4>
                      <p class="block-text">{{ pasien.nohp }}</p>
                      <h4 class="block-heading">Alamat</h4>
                      <p class="block-text">{{ pasien.alamatlengkap }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Umur</h4>
                      <VTag color="orange" :label="pasien.umur" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-2" v-if="dataSourceRiwayat.loading">
              <div class="list-view list-view-v1">
                <div class="list-view-inner">
                  <div v-for="key in 10" :key="key" class="list-view-item mb-10">
                    <VAvatarStack class="is-pushed-mobile">
                      <VPlaceload class="mx-2 h-hidden-tablet-p mt-3" />
                      <VPlaceloadAvatar size="small" class="mx-1" />
                    </VAvatarStack>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-10" v-if="dataSourceRiwayat.loading">
              <div class="list-view list-view-v1">
                <div class="list-view-inner">
                  <!--Item-->
                  <div v-for="key in 10" :key="key" class="list-view-item">
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
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="form-body p-2">
    
    </div>

  <VCard style="margin-top: 1rem">
   
    <div class="timeline-wrapper" v-if="dataSourceRiwayat.length > 0">
      <div class="timeline-header"></div>
      <VButton
      type="button"
      color="info"
      raised
      rounded
      icon="feather:plus-circle"
      class="is-pulled-right mr-3 mt-0 mb-0"
      @click="showModal()"
    >
      Pulang atau Pindah
    </VButton>
    <br>
      <div class="timeline-wrapper-inner">
        <div class="timeline-container">
          <div
            class="timeline-item is-unread"
            v-for="(items, index) in dataSourceRiwayat"
            :key="items.norec"
          >
            <div class="date">
              <span>{{ H.formatDateIndo(items.tglregistrasi) }}</span>
            </div>
            <div :class="'dot is-' + listColor[index + 1]"></div>

            <div class="content-wrap is-grey">
              <div class="content-box">
                <div class="status"></div>
                <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                  <i class="lnir lnir-diagnosis" aria-hidden="true"></i>
                </VIconBox>
                <div class="box-text" style="width: 70%">
                  <div class="meta-text">
                    <p>
                      <span>{{ items.namaruangan + ' -- ' + items.noregistrasi }}</span>
                    </p>

                    <!-- <br /> -->
                    <table style="width: 100%; margin-top: 10px">
                      <tr v-if="items.namakamar != null">
                        <td class="font-labels" width="18%">Kamar</td>
                        <td class="font-labels">:</td>
                        <td class="font-values" width="80%">{{ items.namakamar }}</td>
                      </tr>
                      <tr v-if="items.namakamar != null">
                        <td class="font-labels">No. Bed</td>
                        <td class="font-labels">:</td>
                        <td class="font-values">{{ items.nobed }}</td>
                      </tr>
                      <tr>
                        <td class="font-labels" width="18%">Kelas</td>
                        <td class="font-labels">:</td>
                        <td class="font-values" width="80%">
                          {{ items.namakelas }}
                        </td>
                      </tr>
                      <tr>
                        <td class="font-labels" width="18%">Dokter</td>
                        <td class="font-labels">:</td>
                        <td class="font-values" width="80%">{{ items.namadokter }}</td>
                      </tr>
                      <tr>
                        <td class="font-labels" width="18%"> Pulang </td>
                        <td class="font-labels">:</td>
                        <td class="font-values" width="80%">{{ items.tglpulang }}</td>
                      </tr>
                    </table>
                  </div>
                </div>
                <div class="box-end" style="width: 30%">
                  <div class="columns is-multiline">
                    <div class="column is-12">
                      <b> {{ items.namadepartemen }}</b>
                    </div>

                    <div class="column is-6">
                      <VIconButton
                        icon="feather:edit"
                        @click="editItems(items)"
                        color="warning"
                        raised
                        circle
                        class="mr-2"
                      >
                      </VIconButton>
                      <VIconButton
                        icon="feather:trash"
                        @click="hapusItems(items)"
                        color="danger"
                        raised
                        circle
                      >
                      </VIconButton>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="load-more-wrap has-text-centered">
          <VButton dark-outlined>Load More</VButton>
        </div>
      </div>
    </div>
  </VCard>
  <VCard radius="rounded" class="mt-2" v-if="dataSourceRiwayat.length === 0">
    <VPlaceholderPage
      title="We couldn't find any matching results."
      subtitle="Too bad. Looks like we couldn't find any matching results for the
        search terms you've entered. Please try different search terms or
        criteria."
      larger
    >
      <template #image>
        <img
          class="light-image"
          src="/@src/assets/illustrations/placeholders/search-4.svg"
          alt=""
        />
        <img
          class="dark-image"
          src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
          alt=""
        />
      </template>
    </VPlaceholderPage>
  </VCard>

  <VModal
    :open="modalInput"
    title="Formulir Pulang atau Pindah"
    :noclose="false"
    size="big"
    actions="right"
    @close="modalInput = false"
  >
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12">
            <VCard>
              <div class="columns is-multiline p-1">
                <div class="column is-4">
                  <VField
                    label="Status Keluar "
                    class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }"
                  >
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect
                        mode="single"
                        v-model="item.statuskeluar"
                        :options="d_SK"
                        placeholder="Pilih data"
                        :searchable="true"
                        :attrs="{ id }"
                        autocomplete="off"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField
                    label="Kondisi Pasien "
                    class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }"
                  >
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect
                        mode="single"
                        v-model="item.kondisipasien"
                        :options="d_KP"
                        placeholder="Pilih data"
                        :searchable="true"
                        :attrs="{ id }"
                        autocomplete="off"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField
                    label="Status Pulang"
                    class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }"
                  >
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect
                        mode="single"
                        v-model="item.statuspulang"
                        :options="d_SP"
                        placeholder="Pilih data"
                        :searchable="true"
                        :attrs="{ id }"
                        autocomplete="off"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Tanggal Keluar">
                    <VDatePicker
                      v-model="item.tglpulang"
                      mode="dateTime"
                      style="width: 100%"
                    >
                      <template #default="{ inputValue, inputEvents }">
                        <VField>
                          <VControl icon="feather:calendar" fullwidth>
                            <VInput
                              :value="inputValue"
                              placeholder="Tanggal Keluar"
                              v-on="inputEvents"
                              class="is-rounded"
                            />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField label="Nama Pembawa Pulang">
                    <VControl icon="feather:user">
                      <VInput
                        type="text"
                        v-model="item.namalengkapambilpasien"
                        placeholder="Nama Pembawa Pulang"
                        class="is-rounded"
                      />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-4">
                  <VField
                    label="Hubungan Keluarga"
                    class="is-rounded-select is-autocomplete-select"
                    v-slot="{ id }"
                  >
                    <VControl icon="feather:list" fullwidth>
                      <Multiselect
                        mode="single"
                        v-model="item.hubungankeluarga"
                        :options="d_HK"
                        placeholder="Pilih data"
                        :searchable="true"
                        :attrs="{ id }"
                        autocomplete="off"
                      />
                    </VControl>
                  </VField>
                </div>

                <!-- <div class="column is-3">
                                        <VField>
                                            <VControl>
                                                <VRadio v-model="item.isRawatGabung" :value="'1'" label="is Rawat Gabungs"
                                                    name="isKasusBaru" color="primary" id="isKasusBaru" />
                                            </VControl>
                                        </VField>
                                    </div> -->
                <!-- <div class="column is-9">
                  <VField label="Keterangan">
                    <VControl>
                      <VTextarea
                        class="textarea is-rounded"
                        v-model="item.keterangan10"
                        rows="3"
                        placeholder="Keterangan diagnosis (jika belum tahu kodenya) ..."
                        autocomplete="off"
                        autocapitalize="off"
                        spellcheck="true"
                      />
                    </VControl>
                  </VField>
                </div> -->
              </div>
            </VCard>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton
        icon="feather:plus"
        @click="simpanStatus()"
        :loading="isLoading"
        color="primary"
        raised
        >Simpan
      </VButton>
      <!-- <VButton
        icon="feather:plus"
        @click="simpanPindah()"
        :loading="isLoading"
        color="primary"
        raised
        v-if="showPindah"
        >Simpan Pindah
      </VButton> -->
    </template>
  </VModal>
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
useHead({
  title: 'Pindah Pulang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let pasien: any = ref({})

const isLoadingPasien: any = ref(false)
const isLoading: any = ref(false)
const d_SK: any = ref([])
const d_SP: any = ref([])
const d_KP: any = ref([])
const d_HK: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
})
const dataSourceRiwayat: any = ref([])
const showPindah = ref(false)
const modalInput: any = ref(false)
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
let listColor: any = ref(Object.keys(useThemeColors()))

function headerPasien(id: any) {
  isLoadingPasien.value = true
  useApi()
    .get(`/diagnosa/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}`)
    .then((response: any) => {
      pasien.value = response.pasien
      item.NOREC_APD = response.last_registrasi.norec_apd
      item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
      item.registrasi = response.last_registrasi
      isLoadingPasien.value = false
      riwayatPasien()
    })
}

function dropdownList() {
  useApi()
    .get(`/rawatinap/combo-pindah`)
    .then((response: any) => {
      d_SK.value = response.statuskeluar.map((e: any) => {
        return { label: e.statuskeluar, value: e.id, default: e }
      })
      d_KP.value = response.kondisipasien.map((e: any) => {
        return { label: e.kondisipasien, value: e.id, default: e }
      })
      d_SP.value = response.statuspulang.map((e: any) => {
        return { label: e.statuspulang, value: e.id, default: e }
      })
      d_HK.value = response.hubungankeluarga.map((e: any) => {
        return { label: e.hubungankeluarga, value: e.id, default: e }
      })
    })
}

function riwayatPasien() {
  dataSourceRiwayat.value.loading = true
  useApi()
    .get(`/rawatinap/riwayat-apd?nocmfk=${ID_PASIEN}`)
    .then((response: any) => {
      for (let x = 0; x < response.length; x++) {
        const element = response[x]
        element.no = x + 1
      }
      dataSourceRiwayat.value = response
      dataSourceRiwayat.value.loading = false
    })
    .catch((e: any) => {})
}
function showModal() {
  clearInput()
  item.tglpulang = new Date()
  modalInput.value = true
}
async function simpanStatus() {
  debugger
    if (!item.tglpulang) {
        useToaster().error('Tanggal Pulang  harus di isi')
        return
    }

    let json = {
        'pasiendaftar': {
           'norec_pd': item.NOREC_PD,
            'objectstatuskeluarfk': item.statuskeluar,
            'objectstatuspulangfk': item.statuspulang,
            'objecthubungankeluargaambilpasienfk': item.hubungankeluarga,
            'objectkondisipasienfk': item.kondisipasien,
            'tglpulang': H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
            // 'tglmeninggal': H.formatDate(item.tglmeninggal ? item.tglmeninggal :null, 'YYYY-MM-DD HH:mm:ss'),
            'namalengkapambilpasien': item.namalengkapambilpasien ? item.namalengkapambilpasien : null,
            'objectpenyebabkematianfk': item.objectpenyebabkematianfk ? item.objectpenyebabkematianfk : null,
        },
        'antrianpasiendiperiksa': {
          'norec_apd': item.NOREC_APD,
          'tglkeluar': H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
        },
        'pasien': {
          'id' : item.ID_PASIEN,
          'tglmeninggal': item.tglmeninggal ? item.tglmeninggal : null,
        }

    }
    isLoading.value = true
    await useApi().post(
        `/rawatinap/save-pulang-pasien`, json).then((response: any) => {
            isLoading.value = false
            modalInput.value = false
            riwayatPasien()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

async function simpanPindah() {
  debugger
    if (!item.tglpulang) {
        useToaster().error('Tanggal Pulang  harus di isi')
        return
    }

    let json = {
        'pasiendaftar': {
           'norec_pd': item.NOREC_PD,
            'objectstatuskeluarfk': item.statuskeluar,
            'objectstatuspulangfk': item.statuspulang,
            'objecthubungankeluargaambilpasienfk': item.hubungankeluarga,
            'objectkondisipasienfk': item.kondisipasien,
            'tglpulang': H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
            // 'tglmeninggal': H.formatDate(item.tglmeninggal ? item.tglmeninggal :null, 'YYYY-MM-DD HH:mm:ss'),
            'namalengkapambilpasien': item.namalengkapambilpasien ? item.namalengkapambilpasien : null,
            'objectpenyebabkematianfk': item.objectpenyebabkematianfk ? item.objectpenyebabkematianfk : null,
        },
        'antrianpasiendiperiksa': {
          'norec_apd': item.NOREC_APD,
          'tglkeluar': H.formatDate(item.tglpulang, 'YYYY-MM-DD HH:mm:ss'),
        },
        'pasien': {
          'id' : item.ID_PASIEN,
          'tglmeninggal': item.tglmeninggal ? item.tglmeninggal : null,
        }

    }
    isLoading.value = true
    await useApi().post(
        `/rawatinap/save-pindah-pasien`, json).then((response: any) => {
            isLoading.value = false
            modalInput.value = false
            riwayatPasien()
        }).catch((e: any) => {
            isLoading.value = false
        })
}

function back() {
  window.history.back()
}

function clearInput() {
  delete item.statuskeluar
  delete item.statuspulang
  delete item.kondisipasien

  modalInput.value = false
}

watch(
    () => item.statuskeluar,
    (newValue, oldValue) => {
        if (newValue.statuskeluar == 'Pindah') {
            showPindah.value = true
        } else {
            showPindah.value = false
        }
    }
)

const data = [
  {
    type: 'messages',
    count: 5,
    status: 'new',
  },
  {
    type: 'tasks',
    count: 3,
    status: 'pending',
  },
]

const columns = {
  type: {
    label: 'Type',
    grow: 'lg',
    media: true,
  },
  count: {
    label: 'Count',
    align: 'center',
  },
  status: 'Status',
  actions: {
    label: 'Actions',
    align: 'end',
  },
} as const

headerPasien(ID_PASIEN)
dropdownList()
</script>

<style lang="scss" scoped>
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';

.list-view-v1 {
  .list-view-item {
    @include vuero-r-card;

    margin-bottom: 16px;
    padding: 16px;

    .list-view-item-inner {
      display: flex;
      align-items: center;

      .meta-left {
        margin-left: 16px;

        h3 {
          font-family: var(--font-alt);
          color: var(--dark-text);
          font-weight: 600;
          font-size: 1rem;
          line-height: 1;
        }

        > span:not(.tag) {
          font-size: 0.9rem;
          color: var(--light-text);

          svg {
            height: 12px;
            width: 12px;
          }
        }
      }

      .meta-right {
        margin-left: auto;
        display: flex;
        justify-content: flex-end;
        align-items: center;

        .tags {
          margin-right: 30px;
          margin-bottom: 0;

          .tag {
            margin-bottom: 0;
          }
        }

        .stats {
          display: flex;
          align-items: center;
          margin-right: 30px;

          .stat {
            display: flex;
            align-items: center;
            flex-direction: column;
            text-align: center;
            color: var(--light-text);

            > span {
              font-family: var(--font);

              &:first-child {
                font-size: 1.2rem;
                font-weight: 600;
                color: var(--dark-text);
                line-height: 1.4;
              }

              &:nth-child(2) {
                text-transform: uppercase;
                font-family: var(--font-alt);
                font-size: 0.75rem;
              }
            }

            svg {
              height: 16px;
              width: 16px;
            }

            i {
              font-size: 1.4rem;
            }
          }

          .separator {
            height: 25px;
            width: 2px;
            border-right: 1px solid var(--fade-grey-dark-3);
            margin: 0 16px;
          }
        }

        .network {
          display: flex;
          justify-content: flex-end;
          align-items: center;
          min-width: 145px;

          > span {
            font-family: var(--font);
            font-size: 0.9rem;
            color: var(--light-text);
            margin-left: 6px;
          }
        }

        .dropdown {
          margin-left: 30px;
        }
      }
    }
  }
}

.is-dark {
  .list-view-v1 {
    .list-view-item {
      @include vuero-card--dark;

      .list-view-item-inner {
        .meta-left {
          h3 {
            color: var(--dark-dark-text) !important;
          }
        }

        .meta-right {
          .stats {
            .stat {
              span {
                &:first-child {
                  color: var(--dark-dark-text);
                }
              }
            }

            .separator {
              border-color: var(--dark-sidebar-light-16) !important;
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .list-view-v1 {
    .list-view-item {
      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            > span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .list-view-v1 {
    display: flex;
    flex-wrap: wrap;

    .list-view-item {
      margin: 10px;
      width: calc(50% - 20px);

      .list-view-item-inner {
        position: relative;
        flex-direction: column;

        .v-avatar {
          margin-bottom: 10px;
        }

        .meta-left {
          margin-left: 0;
        }

        .meta-right {
          flex-direction: column;
          margin-left: 0;

          .tags {
            margin: 10px 0;
          }

          .stats {
            margin: 10px 0;
          }

          .network {
            margin: 10px 0 0;
            justify-content: center;

            > span {
              display: none;
            }
          }

          .dropdown {
            position: absolute;
            top: 0;
            right: 0;
            margin-left: 0;
          }
        }
      }
    }
  }
}
</style>
