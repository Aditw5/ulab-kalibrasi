<template>
  <div class="columns is-multiline">
    <div class="column is-12">
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="illustration-header-2 large-screen">
            <div class="header-image">
              <img src="/@src/assets/illustrations/dashboards/lifestyle/Picture1.png" alt=""
                style="max-width:84%; margin-left: 2rem; margin-bottom: 1rem;" />
            </div>
            <div class="header-meta">
              <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                Registrasi</h3>
              <p>
                Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div>
    <div class="business-dashboard hr-dashboard">
      <div class="columns">
        <div class="column is-12">
          <!-- <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
            style="z-index: 6;top: 38px;position: relative; left: 20px" /> -->
          <div class="column is-12" style="margin-top: 2rem;">
            <p>
            <div class="column is-12">
              <VCard radius="rounded">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField label="Periode">
                      <VControl class="prime-auto">
                        <Calendar inputId="range" v-model="item.qPeriode" selectionMode="range" :manualInput="false"
                          :disabled="!item.qAktif" class="w-100" :showIcon="true" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Cari Registrasi Mitra">
                      <VControl icon="fas fa-id-card" fullwidth>
                        <VInput type="text" placeholder="Nama Oerusahaan, No Pendaftaran" autocomplete="off"
                          v-model="item.search" class="is-rounded" v-on:keyup.enter="cari()" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2 mt-5">
                    <VIconButton type="button" color="success" class="is-rounded" rounded raised icon="fas fa-search"
                      @click="cari()" :loading="isLoading">
                    </VIconButton>
                  </div>
                  <div class="column is-3 mt-5">
                    <VField class="h-hidden-mobile">
                      <RouterLink :to="{ name: 'module-registrasi-mitra-lama', }">
                        <VIconButton class="ml-3 is-pulled-right" type="button" color="info" rounded circle raised
                          icon="fas fa-users" v-tooltip.bubble="'Mitra Lama'">
                        </VIconButton>
                      </RouterLink>
                      <VButton class="ml-3 is-pulled-right" type="button" color="info" rounded raised
                        icon="fas fa-long-arrow-alt-right" @click="mitraBaru()">
                        Mitra Baru
                      </VButton>
                    </VField>
                  </div>
                </div>
              </VCard>
            </div>
            <!-- <VCard class="text-center pt-0 pb-0 mt-0">
              <VRadio v-model="order" value="0" label="Belum Kaji" name="outlined_radio" color="warning" />
              <VRadio v-model="order" value="1" label="Sudah Kaji" name="outlined_radio" color="info" />
            </VCard> -->
            <VCard radius="rounded">
              <VCard>
                <div class="user-grid user-grid-v2">
                  <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6"
                    v-if="ds_MITRA.length === 0">
                    <template #image>
                      <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                      <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                    </template>
                  </VPlaceholderPage>
                  <TransitionGroup name="list" tag="div" class="columns is-multiline" v-else>
                    <div v-for="(item, key) in ds_MITRA" :key="key" class="column is-4">
                      <div class="grid-item-wrap is-clickable">
                        <!-- @click="clickCard(item)" -->
                        <div class="grid-item-head is-registrasi">
                          <div class="flex-head">

                            <div class="meta">
                              <span>
                                {{
                                  H.formatDateIndoSimple(item.tglregistrasi)
                                }}
                              </span>
                            </div>
                            <VTag v-if="!item.iskaji" color="warning" rounded>Belum Kaji</VTag>
                            <VTag v-if="item.iskaji" color="info" rounded>Sudah Kaji</VTag>
                          </div>
                        </div>
                        <div class="flex-head" style=" display: flex; justify-content: space-between;">
                          <VDropdown icon="feather:more-vertical" spaced left>
                            <template #content>
                              <a role="menuitem" @click="batalRegis(item)" class="dropdown-item is-media">
                                <div class="icon">
                                  <i aria-hidden="true" class="lnil lnil-trash"></i>
                                </div>
                                <div class="meta">
                                  <span>Batal Registrasi</span>
                                </div>
                              </a>
                              <a v-if="item.iskaji !== null" role="menuitem" @click="cetakTandaTerima(item)" class="dropdown-item is-media">
                                <div class="icon">
                                  <i aria-hidden="true" class="lnil lnil-printer"></i>
                                </div>
                                <div class="meta">
                                  <span>Cetak Tanda Terima </span>
                                </div>
                              </a>
                              <a v-if="item.iskaji !== null" role="menuitem" @click="cetakPermintaanKalibrasi(item)" class="dropdown-item is-media">
                                <div class="icon">
                                  <i aria-hidden="true" class="lnil lnil-printer"></i>
                                </div>
                                <div class="meta">
                                  <span>Cetak Permintaan Kalibrasi dan Kontrak </span>
                                </div>
                              </a>

                            </template>
                          </VDropdown>
                        </div>
                        <div class="grid-item">
                          <VAvatar :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')"
                            size="big" />
                          <h3 class="dark-inverted">{{ item.namaperusahaan }}</h3>
                          <h3 class="dark-inverted">{{ item.nopendaftaran }}</h3>
                          <!-- <p>{{ item.nocm }}</p> -->
                          <p>Email : {{ item.email }}</p>
                          <p>No HP : {{ item.nohp }}</p>
                          <div class="buttons mt-4" style="display: flex; justify-content: center;">
                            <VIconButton v-tooltip.bottom.left="'Kaji Ulang'" label="Bottom center" color="info"
                              outlined circle icon="pi pi-arrow-right" @click="kajiUlang(item)" />
                          </div>
                        </div>
                      </div>
                    </div>
                  </TransitionGroup>
                </div>
              </VCard>
            </VCard>
            </p>
          </div>
        </div>

      </div>
      <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
        :total-items="ds_MITRA.total" :max-links-displayed="5">
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
            <VLabel class="required-field">Nama Peerusahaan</VLabel>
            <VControl icon="feather:map-pin">
              <VInput type="text" v-model="item.perusahaan" placeholder="Tempat Lahir" class="is-rounded_Z" disabled />
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
      <VButton icon="feather:plus" color="primary" @click="saveBatalRegis" :loading="isLoading" raised>Simpan
      </VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import CardCountRev from '/@src/components/partials/widgets/stat/CardCountRev.vue'
import Calendar from 'primevue/calendar';
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import * as qzService from '/@src/utils/qzTrayService'
import CetakResep from '../farmasi/transaksi-pelayanan-farmasi.vue'

useHead({
  title: 'Dashboard Registrasi ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

let chartJK: any = ref({
  series: [],
})
let ID_RUANGAN = useRoute().query.id as string
let dataDokter: any = ref([])
let d_Instalasi: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Ruangan: any = ref([])
let d_Ranap: any = ref([])
let isLoading: any = ref(false)
let btnLoadSimpan: any = ref(false)
let modalGabungRM: any = ref(false)
let modalPesanRuangan: any = ref(false)
let ds_RESERVASI: any = ref([])
let ds_MITRA: any = ref([])
let ds_PASIEN_GABUNG: any = ref([])
isLoading.value = false

const isCetakResep: any = ref(false)
const route = useRoute()
const router = useRouter()
const filters = ref('')
const modalFilter: any = ref(false)
const modalBatalRegis: any = ref(false)
const modalRegisRanap: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const checkboxKel: any = ref([])
const checkboxInst: any = ref([])
const listChecked: any = ref([])
const listCheckedInst: any = ref([])
const HIDE_FILTER: any = ref(false)
const IS_REGISTRASI: any = ref(true)
const isBtnLoading: any = ref(false)
const modelCheck: any = ref([])
const dataSource: any = ref([])
const dataCount: any = ref([])
const dataDept: any = ref([])
const detailPasien: any = ref([])
const isReservasi: any = ref([])
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const selectView: any = ref()
selectView.value = 'grid'

const currentPage: any = ref({
  limit: 6,
  rows: 50,
})
const currentPageReservation: any = ref({
  limit: 6,
  rows: 50,
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
watch(currentPage.value, () => {
  fetchMitra()
})
currentPageReservation.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})

const order: any = ref(0)
const norecPd: string = ref('');
const apd: any = reactive({})
const item: any = reactive({
  qAktif: true,
  qkronis: false,
  qSelesai: false,
  tanggalpembatalan: new Date(),
  filterDate: new Date(),
  qPeriode: [
    new Date(),
    new Date()
  ],
  c_antrian: 0,
  c_dilayani: 0,
  c_registrasi: 0,
  c_reservasi: 0,
})
const chart: any = ref({
  aktif: true
})

const openModalCetakResep = (data) => {
  isCetakResep.value = true;
  norecPd.value = data.nopendaftaran;
  // console.log(norecPd.value)
}

const tutupCetakResep = async () => {
  isCetakResep.value = false;
}
const onPageChange = () => {
  console.log("successfully", currentPage.value);
  fetchMitra()
}


const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataDokter.value
  }
  return dataDokter.value.filter((item: any) => {
    return (
      item.namaruangan.match(new RegExp(filters.value, 'i'))
    )
  })
})


const fetchMitra = async () => {

  ds_MITRA.value = []
  ds_MITRA.value.loading = true

  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  let page: any = route.query.page ? route.query.page : 1
  let search = '', sampai = '', dari = ''
  isReservasi.value = false
  if (item.search) search = `&search=${item.search}`
  if (item.qPeriode) {
    if (item.qPeriode[0]) {
      dari = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD 00:00')
    }
    if (item.qPeriode[1]) {
      sampai = H.formatDate(item.qPeriode[1], 'YYYY-MM-DD 23:59')
    } else {
      sampai = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD 23:59')
    }
  }

  isLoading.value = true
  const response = await useApi().get(`/registrasi/list-mitra-grid?page=${page}&dari=${dari}&sampai=${sampai}&limit=${limit}&offset=${offset}${search}`)
  ds_MITRA.value.loading = false
  ds_MITRA.value = response.data
  ds_MITRA.value.total = response.total
  isLoading.value = false
  dataSource.value = response.data;

  // await countData()
}


const filter = () => {
  fetchMitra()
}

const cari = () => {
  if (IS_REGISTRASI.value) {
    fetchMitra()
  }
}

const mitraBaru = () => {
  router.push({
    name: 'module-registrasi-mitra-baru',
  })
}

const registrasi = (e: any) => {
  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.nocmfk,
    },
  })
}



const editRegistrasi = async (e: any) => {
  let apd = await getAPD(e);

  router.push({
    name: 'module-registrasi-registrasi-ruangan',
    query: {
      nocmfk: e.nocmfk,
      nopendaftaran: e.nopendaftaran,
      norec_apd: apd,
    },
  })
}


const cetakTandaTerima = (e) => {
  // console.log(e)
  H.printBlade(`registrasi/cetak-tanda-terima?pdf=true&norec=${e.iddetail}`);
}

const cetakPermintaanKalibrasi = (e) => {
  // console.log(e)
  H.printBlade(`registrasi/cetak-permintaan-kalibrasi?pdf=true&norec=${e.iddetail}`);
}

const cetakSEP = (e: any) => {
  H.printBlade('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=false")
  // qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true", 'SEP', 1)
}

const cetakLabel = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}
const cetakLabelODC = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-odc?pdf=true&noregistrasi=${e.noregistrasi}`, 'LABEL PASIEN', 1)
  // H.printBlade(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}

const cetakkartuPasien = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-kartu-pasien?noregistrasi=${e.noregistrasi}`,
    'KARTU PASIEN', 1)
}


const cetakSuratKeteranganKeluar = async (e: any) => {

  let dokter = `&dokter=${e.dokter}`
  let kelompokpasien = `&kelompokpasien=${e.kelompokpasien}`
  let objectdepartemenfk = `&objectdepartemenfk=${e.objectdepartemenfk}`
  let tglregistrasi = `&tglregistrasi=${e.tglregistrasi}`
  let nopendaftaran = `&nopendaftaran=${e.nopendaftaran}`
  H.printBlade(`dashboard/registrasi/cetak-surat-keterangan-keluar?noregistrasi=${e.noregistrasi}${dokter}${kelompokpasien}${objectdepartemenfk}${tglregistrasi}${nopendaftaran}`);
}


const detailRegistrasi = async (e: any) => {
  let apd = await getAPD(e);

  router.push({
    name: 'module-registrasi-detail-registrasi',
    query: {
      noregistrasi: e.noregistrasi,
      nopendaftaran: e.nopendaftaran,
      norec_apd: apd,
    },
  })
}


const cetakBuktiPendaftaran = (e: any) => {
  qzService.printData(`report/bukti-pendaftaran?pdf=true&noregistrasi=${e.noregistrasi}`,
    'ANTRIAN POLI', 1)
}

const batalRegis = async (e: any) => {
  item.perusahaan = e.namaperusahaan
  item.norecregis = e.iddetail

  modalBatalRegis.value = true
}

const saveBatalRegis = async () => {
  if (!item.alasanpembatalan) { H.alert('warning', 'Alasan Pembatalan harus di isi'); return }
  let json = {
    mitraregis: {
      'norecregis': item.norecregis,
      'tanggalpembatalan': item.tanggalpembatalan,
      'alasanpembatalan': item.alasanpembatalan,
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/registrasi/save-batal-regis-mitra`, json)
    .then((response: any) => {
      isLoading.value = false
      clear()
      fetchMitra()
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const detail = (e: any) => {
  // console.log(e)
  item.nocmfk_baru = e.id
  item.nocm_tujuan = e.nocm
  H.alert('success', 'Data telah terpilih')
  return
};

const clear = () => {
  item.alasanpembatalan = ''
  item.tanggalpembatalan = ''

  modalBatalRegis.value = false
}

const kajiUlang = (e: any) => {
  console.log(e)
  router.push({
    name: 'module-registrasi-kaji-ulang',
    query: {
      nocmfk: e.id,
      norec_mitra_daftar: e.iddetail,
      tglregistrasi: e.tglregistrasi
    },

  })
}


const listButton: any = ref([
  {
    label: 'Mitra Lama ',
    icon: 'fas fa-users',
    command: () => {
      router.push({ name: 'module-registrasi-mitra-lama' });
    }
  },
  {
    label: 'Mitra Baru',
    icon: 'fas fa-user-plus',
    command: () => {
      router.push({ name: 'module-registrasi-mitra-baru' });
    }
  }
])


const cetakKeluarMasuk = (e: any) => {
  // qzService.printData(`report/cetak-lembar-keluar-masuk?norec=${e.nopendaftaran}`, 'KELUAR MASUK', 1)
  H.printBlade(`report/cetak-lembar-keluar-masuk?norec=${e.nopendaftaran}`)
}

const cetakGelangPasien = (e: any) => {
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`, 'GELANG PASIEN', 1)
  // H.printBlade(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.noregistrasi}`)
}


const countData = async () => {
  let dari = ''
  let sampai = ''
  if (item.qPeriode) {
    if (item.qPeriode[0]) {
      dari = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD')
    }
    if (item.qPeriode[1]) {
      sampai = H.formatDate(item.qPeriode[1], 'YYYY-MM-DD')
    } else {
      sampai = H.formatDate(item.qPeriode[0], 'YYYY-MM-DD')
    }

    const response = await useApi().get(`/registrasi/count-daftar?dari=${dari}&sampai=${sampai}`).then((response: any) => {
      dataCount.value = response.kelompokpasien
      dataDept.value = response.departemen
    })
  }
}

qzService.connect()
// fetchdDropdown()
fetchMitra()
// countData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/registrasi.scss';
@import '/@src/scss/module/registrasi/list-pasien';

.c-title {
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
}

.block-heading {
  font-family: var(--font-alt);
  font-weight: 600;
  font-size: 1.1rem;
  color: var(--white);
  margin-bottom: 4px;
}
</style>
