

<template>
  <div class="is-100">
    <div class="business-dashboard hr-dashboard">
      <div class="columns is-multiline">
        <div class="column is-8" v-if="isLoadingPasien">
          <PlaceloadHeader />
        </div>
        <div class="column is-8" v-if="!isLoadingPasien">
          <HeadPasien :pasien="pasien" :isaktif="PASIEN_AKTIF" />
        </div>
        <div class="column is-4">
          <TDetailRegistrasi :selectedRegistrasi="selectedRegistrasi" :isaktif="PASIEN_AKTIF" />
        </div>
      </div>
    </div>
    <div class="page-content-inner">
      <div class="account-wrapper">
        <div class="columns">
          <div class="column " :class="hideRiwayat == true ? 'is-hidden' : 'is-3'">
            <div class="account-box is-navigation">
              <div class="account-menu">
                <VCardHead title="Riwayat Registrasi" class="no-border h-400 p-1">
                  <template #action>
                    <!-- <a class="is-expandeds" v-if="hideRiwayat == false" @click="hideRiwayat = true">
                      <i class="iconify" data-icon="feather:minimize-2" aria-hidden="true"></i>
                    </a> -->

                    <TActionLeftBar @reload="reloadListReg()" @back="backPage()" @filter="filter()"
                      :filterTgl="item.filterTgl" />

                  </template>
                  <div v-if="isLoadingPasien">
                    <VPlaceloadWrap v-for="key in 6" :key="key">
                      <VPlaceload width="100%" height="50px" class="mx-1 mt-2" />
                    </VPlaceloadWrap>
                  </div>
                  <div v-else-if="listRegistrasi.length == 0">
                    <VCard>
                      <span style="color: var(--light-text)">Data tidak ditemukan...</span>
                    </VCard>
                  </div>
                  <div v-else-if="listRegistrasi.length > 0">
                    <VCard v-for="(items, key) in listRegistrasi" :key="key" class="is-clickable mt-2 is-grey"
                      @click="selectNoreg(items)" :class="
                        selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''
                      ">
                      <i aria-hidden="true" class="lnir lnir-medicine mr-2"></i>
                      <span class="span-text-left-bar">{{
                        H.formatDateIndo(items.tglregistrasi)
                      }}</span>
                      <span v-if="selectedRegistrasi.norec == items.norec" class="is-pulled-right ml-2" :class="
                        selectedRegistrasi.norec == items.norec ? 'is-active-regis' : ''
                      ">
                        <i aria-hidden="true" class="fas fa-arrow-right"></i>
                      </span>
                    </VCard>
                  </div>
                  <div class="dataTable-bottom">
                    <div class="dataTable-info">Menampilkan {{ currentPage.page }} ke {{ currentPage.limit }} dari
                      {{ totalData }} entri data
                    </div>
                  </div>
                  <div class="is-pulled-bottom" style="position: absolute; bottom: 10%">
                    <VFlexPagination v-model:current-page="currentPage.page" class="mt-6"
                      :item-per-page="currentPage.limit" :total-items="listRegistrasi.length == 0 ? 1 : totalData"
                      :max-links-displayed="10" />

                  </div>
                </VCardHead>
              </div>
            </div>
          </div>
          <div class="column " :class="hideRiwayat == true ? 'is-12' : 'is-9'">
            <TEmrDetail :registrasi="selectedRegistrasi" :riwayat="riwayatPemeriksaan" :hideRiwayat="hideRiwayat"
              @showRiwayat="showRiwayat()" @reloadRiwayat="reloadRiwayat()" @billingPasien="billingPasien()"
              @hiddenRiwayat="hiddenRiwayat()" :isLoadingRiwayat="isLoadingRiwayat" :isPasienAktif="PASIEN_AKTIF">
            </TEmrDetail>
          </div>
        </div>
      </div>
    </div>
  </div>
  <VModal :open="modalFilter" title="Periode Registrasi" :noclose="true" size="small" actions="right"
    @close="modalFilter = false">
    <template #content>
      <form class="modal-form">
        <div class="columns">
          <div class="column is-12" style="text-align: center">
            <VField class="is-centered">
              <v-date-picker v-model="item.filterTgl" is-range class="is-centered" />
            </VField>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:search" @click="reloadListReg()" :loading="isLoadingFilter" color="primary" raised>
        Filter</VButton>
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
import { popovers } from '/@src/data/users/userPopovers'
import TActionLeftBar from './profile-pasien/t-action-left-bar.vue'
import TDetailRegistrasi from './profile-pasien/t-detail-registrasi.vue'
import TEmrDetail from './profile-pasien/t-emr-detail.vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
useHead({
  title: 'EMR - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let PASIEN_AKTIF = NOREC_PD == null ? false : true
let listWarna = ref([0, 1, 2])
const colors: any = ref(Object.keys(useThemeColors()))
const listColor: any = ref([])
const modalFilter: any = ref(false)
const isLoadingFilter: any = ref(false)
const currentPage: any = ref({
  limit: 5,
  rows: 25,
})
const hideRiwayat: any = ref(false)
const route = useRoute()
currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  reloadListReg()
})

for (let i = 0; i < colors.value.length; i++) {
  const element = colors.value[i]
  if (i <= 9 && element != 'primary') listColor.value.push(element)
}

const riwayatPemeriksaan: any = ref({
  LIST_TINDAKAN: [],
  LIST_RESEP: [],
  LIST_CPPT: [],
  LIST_VITAL: [],
  LIST_LAB: [],
  LIST_RAD: [],
  LIST_BEDAH: [],
  LIST_DIAGNOSIS: [],
  LIST_STATUSPASIEN: {},
  isEmpty: true,
  isLoading: false,
})
const item: any = reactive({
  tglregistrasi: new Date(),
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  filterTgl: reactive({
    start: new Date(new Date().setDate(new Date().getDate() - 30)),
    end: new Date(),
  }),
})
const totalData = ref(0)
const pasien: any = ref({})
const listRegistrasi: any = ref([])
const isLoadingPasien: any = ref(false)
const isLoadingRiwayat: any = ref(false)
const { y } = useWindowScroll()
const router = useRouter()

const $route = useRoute()
const isEmpty = ref(false)
const isStuck = computed(() => {
  return y.value > 30
})

const selectedRegistrasi: any = ref({})

function pasienByID(id: any) {
  isLoadingPasien.value = true
  isLoadingFilter.value = true
  let dari = H.formatDate(item.filterTgl.start, 'YYYY-MM-DD')
  let sampai = H.formatDate(item.filterTgl.end, 'YYYY-MM-DD')
  let limit: any = currentPage.value.limit

  let offset: any = route.query.page ? route.query.page : 1

  offset = (parseInt(offset) - 1) * limit
  totalData.value = 0
  useApi()
    .get(
      `/emr/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&dari=${dari}&sampai=${sampai}&limit=${limit}&offset=${offset}`
    )
    .then((response: any) => {
      pasien.value = response.pasien

      listRegistrasi.value = response.registrasi
      totalData.value = response.count_registrasi
      for (let x = 0; x < response.registrasi.length; x++) {
        const element = response.registrasi[x]
        if (item.NOREC_PD == element.norec) {
          selectNoreg(element)
          break
        }
      }
      if (item.NOREC_PD == '' && response.registrasi.length) {
        selectNoreg(response.registrasi[0])
      }

      isLoadingFilter.value = false
      isLoadingPasien.value = false
    })
}
function selectNoreg(items: any) {
  selectedRegistrasi.value.isloading = true
  selectedRegistrasi.value = items
  selectedRegistrasi.value.isloading = false
  detailPelayanan(items.norec)
}

function reloadListReg() {
  pasienByID(ID_PASIEN)
  modalFilter.value = false
}
function backPage() {
  window.history.back()
}
function detailPelayanan(NOREC_PD: any) {
  isLoadingRiwayat.value = true
  riwayatPemeriksaan.value = {
    LIST_TINDAKAN: [],
    LIST_RESEP: [],
    LIST_CPPT: [],
    LIST_VITAL: [],
    LIST_LAB: [],
    LIST_RAD: [],
    LIST_BEDAH: [],
    LIST_DIAGNOSIS: [],
    LIST_STATUSPASIEN: {},
    isEmpty: true,
    isLoading: false,
  }
  useApi()
    .get(`/emr/detail-pelayanan?norec_pd=${NOREC_PD}`)
    .then((response: any) => {
      isLoadingRiwayat.value = false
      let x = 0
      for (let i = 0; i < response.resep.length; i++) {
        const element = response.resep[i]
        element.no = x
        if (x > 3) {
          x = 0
        }
        x++
      }
      x = 0
      for (let i = 0; i < response.diagnosis.length; i++) {
        const element = response.diagnosis[i]
        element.color = listColor.value[x]
        element.icd = element.kddiagnosa + '-' + element.namadiagnosa
        if (x > 9) {
          x = 0
        }
        x++
      }
      x = 0
      for (let i = 0; i < response.laboratorium.length; i++) {
        const element = response.laboratorium[i]
        element.color = listColor.value[x]
        element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
        element.icon = 'fa fa-bong'
        if (x > 9) {
          x = 0
        }
        x++
      }
      x = 0
      for (let i = 0; i < response.radiologi.length; i++) {
        const element = response.radiologi[i]
        element.color = listColor.value[x]
        element.tglorder = H.formatDateIndoSimple(new Date(element.tglorder))
        element.icon = 'fa-inverse fa fa-radiation'
        if (x > 9) {
          x = 0
        }
        x++
      }
      response.statuspasien.alergi = response.statuspasien.alergi ? response.statuspasien.alergi : 'Tidak ada'
      response.statuspasien.lamarawat = response.statuspasien.lamarawat ? response.statuspasien.lamarawat : '-'
      response.statuspasien.tglpulang = response.statuspasien.tglpulang ? H.formatDateIndo(response.statuspasien.tglpulang) : '-'
      response.statuspasien.statuspulang = response.statuspasien.statuspulang ? response.statuspasien.statuspulang : '-'
      response.statuspasien.kondisipasien = response.statuspasien.kondisipasien ? response.statuspasien.kondisipasien : '-'

      riwayatPemeriksaan.value.LIST_TINDAKAN = response.tindakan
      riwayatPemeriksaan.value.LIST_RESEP = response.resep
      riwayatPemeriksaan.value.LIST_DIAGNOSIS = response.diagnosis
      riwayatPemeriksaan.value.LIST_RAD = response.radiologi
      riwayatPemeriksaan.value.LIST_LAB = response.laboratorium
      riwayatPemeriksaan.value.LIST_BEDAH = response.bedah
      riwayatPemeriksaan.value.LIST_STATUSPASIEN = response.statuspasien

      riwayatPemeriksaan.value.isLoading = false
      riwayatPemeriksaan.value.isEmpty = response.empty
    })
}
function filter() {
  modalFilter.value = true
}
function minimizeRiwayat(bool: any) {

}
function showRiwayat() {
  hideRiwayat.value = false
}
function hiddenRiwayat() {
  hideRiwayat.value = true
}

function reloadRiwayat() {
  detailPelayanan(selectedRegistrasi.value.norec)
}
function billingPasien() {
  let params = {}
  if (!PASIEN_AKTIF) {
    params = {
      norec_pasien_daftar: selectedRegistrasi.value.norec,
      noregistrasi: selectedRegistrasi.value.noregistrasi,
      isaktif: 'false'
    }
  } else {
    params = {
      norec_pasien_daftar: selectedRegistrasi.value.norec,
      noregistrasi: selectedRegistrasi.value.noregistrasi,
    }
  }
  router.push({
    name: 'module-kasir-billing',
    query: params
  })
}
pasienByID(ID_PASIEN)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/emr/profile-pasien';
</style>
