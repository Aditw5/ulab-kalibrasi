<template>
  <ConfirmDialog />
  <VCard radius="rounded">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Mitra </h3> <span> ( {{ totalData }}
        Results)</span>
    </div>

    <div class="columns  all-projects m-3 mt-0">
      <div class="columns is-multiline  projects-card-grid">
        <div class="column is-9">
          <a type="button" class="is-pulled-right mr-3" color="info" outlined raised>
            <RouterLink :to="{ name: 'module-registrasi-mitra-baru' }">
              <i class="fa fa-plus"></i> Mitra
              Baru
            </RouterLink>
          </a>
        </div>
        <div class="column is-9">
          <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
            <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
              <VFlexTableCell :column="{ grow: true, media: true }">
                <VPlaceloadAvatar size="medium" />
                <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
              </VFlexTableCell>
              <VFlexTableCell>
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
              </VFlexTableCell>
              <VFlexTableCell :column="{ align: 'end' }">
                <VPlaceload width="10%" class="mx-1" />
              </VFlexTableCell>
            </div>
          </div>
          <div class="flex-list-inner" v-else-if="ds_PASIEN.length === 0">
            <VPlaceholderSection title="Not found" subtitle="There is no data that match your query." class="my-6">
              <template #image>
                <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg" alt="" />
                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
              </template>
            </VPlaceholderSection>
          </div>
          <div v-else-if="ds_PASIEN.length > 0">


            <div class="grid-item mb-4" v-for="(items, i) in ds_PASIEN" :key="items.id">
              <div class="top-section">
                <div class="head">
                  <!-- <div class="title-wrap">
                    <div class="columns">
                      <div class="column is-3">
                        <VAvatar :picture="items.foto" size="small" v-if="items.isfoto" />
                        <VAvatar size="small" :color="listColor[i]" :initials="items.initials" v-if="!items.isfoto" />
                      </div>
                      <div class="column is-12 mr-3">
                        <h3>{{ items.namapasien }}</h3>
                        <p>{{ items.nocm + (items.jeniskelamin == 'Perempuan' ? ' (P)' : ' (L)') }}</p>
                      </div>
                    </div>
                  </div> -->
                  <div class="title-wrap">
                    <div class="columns">
                      <div class="column is-3">
                        <VAvatar :picture="items.foto" size="small" v-if="items.isfoto" />
                        <VAvatar size="small" :color="listColor[i]" :initials="items.initials" v-if="!items.isfoto" />
                      </div>
                      <div class="column is-12 mr-3">
                        <div class="is-flex is-align-items-center">
                          <h3>{{ items.namaperusahaan }}</h3>
                        </div>
                      </div>
                    </div>
                  </div>
                  <VDropdown icon="feather:more-vertical" spaced right v-tooltip.bubble="'AKSI'">
                    <template #content>

                      <!-- <a role="menuitem" href="#" class="dropdown-item is-media"> -->
                      <a role="menuitem" href="#" class="dropdown-item is-media" @click="editMitra(items)">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-pencil"></i>
                        </div>
                        <div class="meta">
                          <span>Edit</span>
                          <span>ubah data ini</span>
                        </div>
                      </a>

                      <!-- <a role="menuitem" href="#" class="dropdown-item is-media" @click="dialogConfirm(items)"> -->
                      <a role="menuitem" href="#" class="dropdown-item is-media">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-trash"></i>
                        </div>
                        <div class="meta">
                          <span>Delete</span>
                          <span>hapus data ini</span>
                        </div>
                      </a>

                      <!-- <a role="menuitem" href="#" class="dropdown-item is-media" @click="riwayatPasien(items)"> -->
                      <a role="menuitem" href="#" class="dropdown-item is-media">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-archive"></i>
                        </div>
                        <div class="meta">
                          <span>Riwayat</span>
                          <span>melihat riwayat registrasi</span>
                        </div>
                      </a>
                      <hr class="dropdown-divider" />
                    </template>
                  </VDropdown>

                </div>
                <div class="body">
                  <div class="columns is-multiline">
                    <div class="column">
                      <h4 class="heading">Alamat</h4>
                      <p class="fs-075">{{ items.alamatlengkap }}</p>
                      <p class="fs-075">{{ items.nohp }}</p>
                    </div>
                    <div class="column is-12">
                      <div class="progress-stats" style="margin-top: -10px;">
                        <span class="dark-inverted">Informasi kelengkapan pengisian data</span>
                        <span>{{ items.progress }}%</span>
                      </div>
                      <div class="progress-bar">
                        <VProgress :color="items.class_proggress" size="tiny" :value="items.progress" />
                      </div>
                    </div>

                  </div>
                </div>
              </div>

              <div class="bottom-section is-custom">
                <div class="foot-block" style="margin-top: 10px;">
                  <!-- <h4 class="heading">Action</h4> -->
                  <div class="developers">
                    <!-- <VButton type="button" icon="feather:eye" class="is-fullwidth mr-3" color="success" outlined raised
                      @click="detailPasien(items)">
                      Details </VButton> -->
                    <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3" color="info" outlined raised
                      @click="editMitra(items)">
                      Edit </VButton>
                    <VButton type="button" icon="feather:trash" class="is-fullwidth mr-3" color="danger" outlined raised
                      @click="dialogConfirm(items)">
                      Delete </VButton>
                    <VButton type="button" icon="fa fa-history" class="is-fullwidth mr-3" color="warning" outlined
                      raised @click="riwayatPasien(items)">
                      Riwayat </VButton>

                    <VButton type="button" icon="feather:arrow-right-circle" class="is-fullwidth mr-3" color="primary"
                      outlined raised @click="registrasiLab(items)" :disabled="items.status == 'Meninggal'"
                      v-if="items.tglmeninggal == null && (kelompokUser == 'laboratorium' || kelompokUser == 'radiologi')">
                      Registrasi </VButton>

                    <VButton type="button" icon="feather:arrow-right-circle" class="is-fullwidth mr-3" color="purple"
                      outlined raised @click="registrasi(items)" :loading="items.isLoading"
                      :disabled="items.status == 'Meninggal'"
                      v-else="items.tglmeninggal == null && (kelompokUser != 'laboratorium' || kelompokUser != 'radiologi')">
                      Registrasi </VButton>


                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="dataTable-bottom">
            <div class="dataTable-info">Menampilkan {{ currentPage.page }} ke {{ currentPage.limit }}
              dari
              {{ totalData }} entri data
            </div>
          </div>
          <div class="is-pulled-bottoms">
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
          <!-- <VFlexPagination v-model:current-page="currentPage.page" class="mt-6" :item-per-page="currentPage.limit"
            :total-items="currentPage.rows" :max-links-displayed="10" /> -->

        </div>
        <div class="column is-3">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField>
                <VControl icon="feather:search">
                  <input v-model="item.qnama" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                    placeholder="Filter Nama..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <h3 class="title is-5 mb-2 mr-1">Filters </h3>
            </div>
            <div class="column is-6">
              <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised> Clear
                All </a>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>No Serial Number</VLabel>
                <VControl icon="feather:user">
                  <VInput type="text" v-model="item.qnocm" v-on:keyup.enter="filter()" placeholder="No Serial Number" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VDatePicker v-model="item.tgldaftar" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VLabel>Tanggal Daftar</VLabel>
                    <VControl icon="feather:calendar">
                      <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                        class="is-rounded_Z" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>Rows</VLabel>
                <VControl icon="feather:book">
                  <VInput type="text" v-model="currentPage.limit" v-on:keyup.enter="filter()" placeholder="Rows" />
                </VControl>
              </VField>
            </div>
            <!-- <div class="column is-12">
              <VField>
                <VLabel>Alamat</VLabel>
                <VControl icon="feather:map">
                  <VInput type="text" v-model="item.qalamat" v-on:keyup.enter="filter()" placeholder="Alamat" />
                </VControl>
              </VField>
            </div> -->
            <div class="column is-12">
              <VButton @click="filter()" :loading="ds_PASIEN.loading" type="button" icon="feather:search"
                class="is-fullwidth mr-3" color="info" raised> Apply Filters
              </VButton>
            </div>
          </div>

        </div>
      </div>
    </div>

  </VCard>
  <VModal :open="modalRiw" title="Riwayat Registrasi" size="big" actions="right" @close="modalRiw = false"
    cancelLabel="Tutup">
    <template #content>
      <form class="modal-form">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12">
              <div class="block-header">
                <div class="left">
                  <div class="current-user">
                    <VAvatar size="medium" picture="/images/avatars/svg/vuero-1.svg" squared />
                    <h3>{{ dataSelect.namapasien }}</h3>
                    <!-- <p class="block-text">
                      {{ 'No RM : ' + dataSelect.nocm + (dataSelect.jeniskelamin ==
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
                      <p class="block-text"> {{ dataSelect.nocm }}</p>
                      <h4 class="block-heading">Tgl Lahir </h4>
                      <p class="block-text"> {{ dataSelect.tgllahir }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">NIK </h4>
                      <p class="block-text"> {{ dataSelect.noidentitas }}</p>
                      <h4 class="block-heading">Jenis Kelamin</h4>
                      <p class="block-text"> {{ dataSelect.jeniskelamin }}</p>
                    </div>
                  </div>
                </div>
                <div class="right">
                  <div class="columns">
                    <div class="column">
                      <h4 class="block-heading">No HP</h4>
                      <p class="block-text">{{ dataSelect.nohp }}</p>
                      <h4 class="block-heading">Nama Ibu</h4>
                      <p class="block-text">{{ dataSelect.namaibu ? dataSelect.namaibu : '-' }}</p>
                    </div>
                    <div class="column">
                      <h4 class="block-heading">Umur</h4>
                      <VTag color="orange" :label="dataSelect.umur" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="columns is-multiline">
          <div class="column is-12">
            <DataTable :value="dataSourceRiwayat" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
              class="p-datatable-customers" filterDisplay="menu"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
              :loading="dataSourceRiwayat.loading"
              :globalFilterFields="['namaruangan', 'namadokter', 'kelompokpasien']">
              <!-- <Column :exportable="false" header="#" style="width:8rem">
            <template #body="slotProps">
              <VIconButton type="button" icon="pi pi-arrow-right" class="mr-3" color="danger" circle outlined raised
                v-tooltip.top="'Input Resep'" @click="gotoResep(slotProps.data)">
              </VIconButton>
            </template>
  </Column> -->
              <template #header>
                <div class="flex justify-content-between  align-items-center">
                  <VField>
                    <VControl icon="feather:search">
                      <VInput type="text" v-model="filters['global'].value" placeholder="Filter" class="is-rounded" />
                    </VControl>
                  </VField>

                </div>
              </template>
              <template #empty>
                <span style="text-align:center"> No data found.</span>
              </template>
              <!-- <template #loading>
            Loading customers data. Please wait.
          </template> -->
              <Column field="no" header="No"></Column>
              <Column field="tglregistrasi" header="Tgl Registrasi" style="width:150px"></Column>
              <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="width:150px"></Column>
              <Column field="namaruangan" header="Ruang" :sortable="true" style="width:200px"></Column>
              <Column field="namadokter" header="Dokter" style="width:200px"></Column>
              <Column field="kelompokpasien" header="Tipe" style="width:150px"></Column>
              <Column field="tglpulang" header="Tgl Pulang" style="width:150px"></Column>
              <Column field="lamarawat" header="Lama Rawat" style="width:150px"></Column>
            </DataTable>
          </div>
        </div>
      </form>
    </template>
  </VModal>
</template>
<route lang="yaml">
meta:
  requiresAuth: true
</route>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { FilterMatchMode, FilterOperator } from "primevue/api";
import { useUserSession } from '/@src/stores/userSession'
useHead({
  title: 'Mitra - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle('Pasien Lama')
useViewWrapper().setFullWidth(false)
const confirm = useConfirm();
const total = ref(0)
const date = ref(new Date())
const item: any = reactive({})
const totalData: any = ref(0)
let listJK: any = ref([])
let listAgama: any = ref([])
let listGolonganDarah: any = ref([])
let listStatusPerkawinan: any = ref([])
let listPendidikan: any = ref([])
let listPekerjaan: any = ref([])
let listEtnis: any = ref([])
let listKebangsaan: any = ref([])
let listNegara: any = ref([])
let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
let dataSelect: any = ref({})
const modalRiw = ref(false)
const dataSourceRiwayat: any = ref([])
const route = useRoute()
const isbayi = ref(false)
const router = useRouter()
const { y } = useWindowScroll()
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser

const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});
const isStuck = computed(() => {
  return y.value > 30
})
const currentPage: any = ref({
  limit: 5,
  rows: 50
})
for (var i = listColor.value.length - 1; i >= 0; i--) {
  const element = listColor.value[i];
  if (element == 'primary') {
    listColor.value.splice(i, 1);
  }
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
      fetchMitra()
    }
  }
)
watch(
  () => currentPage.value.limit,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchMitra()
    }
  }
)


async function fetchMitra() {
  ds_PASIEN.value.loading = true

  let searchQuery = `&q=`
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (parseInt(offset) - 1) * limit
  // let offset=''
  let page: any = route.query.page ? route.query.page : 1
  let namaperusahaan = ''
  let tgldaftar = ''
  if (item.qnama) namaperusahaan = `&namaperusahaan=${item.qnama}`
  if (item.tgldaftar) tgldaftar = `&tgldaftar=${H.formatDate(item.tgldaftar, 'YYYY-MM-DD')}`
  totalData.value = 0
  const response = await useApi().get(`/registrasi/mitra-lama?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}${namaperusahaan}${tgldaftar}`)
  let pasien = response.data
  totalData.value = response.total
  for (let x = 0; x < pasien.length; x++) {
    const element = pasien[x];
    let ini = element.namaperusahaan.split(' ')
    let init = element.namaperusahaan.substr(0, 1)
    if (ini.length > 1) {
      init = init + ini[1].substr(0, 1)
    }
    element.initials = init
  }
  ds_PASIEN.value.loading = false
  ds_PASIEN.value = pasien
  // ds_PASIEN.value.total = pasien.length
}


// async function listDropdown() {
//   const response = await useApi().get(
//     `/registrasi/list-dropdown`)
//   listJK.value = []
//   for (let x = 0; x < response.jk.length; x++) {
//     const element = response.jk[x];
//     if (element.jeniskelamin != '-') {
//       listJK.value.push(element)
//     }
//   }
//   listAgama.value = response.agama.map((e: any) => { return { label: e.agama, value: e.id } })
//   listGolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id } })
//   listStatusPerkawinan.value = response.statusperkawinan.map((e: any) => { return { label: e.statusperkawinan, value: e.id } })
//   listPendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id } })
//   listPekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id } })
//   listEtnis.value = response.etnis.map((e: any) => { return { label: e.suku, value: e.id } })
//   listKebangsaan.value = response.kebangsaan.map((e: any) => { return { label: e.name, value: e.id } })
//   listNegara.value = response.negara.map((e: any) => { return { label: e.namanegara, value: e.id } })

// }
function savePasien() {
  if (!item.nik) {
    useToaster().error('NIK harus di isi')
    return
  }
  if (!item.nobpjs) {
    useToaster().error('No BPJS harus di isi')
    return
  }
  if (!item.namapasien) {
    useToaster().error('Nama harus di isi')
    return
  }
}
function resetForm() {

}
function editMitra(e: any) {
  router.push({
    name: 'module-registrasi-mitra-baru',
    query: {
      id: e.id,
    },
  })
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      hapusPasien(e)
    },
    reject: () => { },
  })
}

function hapusPasien(e: any) {
  useApi().post(
    `/registrasi/delete-pasien`, { 'id': e.id }).then((response: any) => {
      fetchMitra()
    }).catch((e: any) => {

    })
}
function riwayatPasien(e: any) {
  dataSelect.value = e
  router.push({
    name: 'module-registrasi-riwayat-registrasi',
    query: {
      nocmfk: e.id,
    },
  })
  // modalRiw.value = true
  // dataSourceRiwayat.value.loading = true
  // useApi().get(
  //   `/registrasi/riwayat-registrasi?id=${e.id}`).then((response: any) => {
  //     for (let x = 0; x < response.length; x++) {
  //       const element = response[x];
  //       element.no = x + 1
  //     }
  //     dataSourceRiwayat.value = response
  //     dataSourceRiwayat.value.loading = false
  //   }).catch((e: any) => {

  //   })
}
function detailPasien(e: any) {

}
const registrasi = async (e: any) => {
  router.push({
    name: 'module-registrasi-registrasi-mitra',
    query: {
      nomitrafk: e.id,
      statusmitra: "LAMA",
    },
  })
}
function registrasiLab(e: any) {
  router.push({
    name: 'module-registrasi-registrasi-ruangan-lab',
    query: {
      nocmfk: e.id,
    },
  })
}
function clearFilter() {
  delete item.qnama
  delete item.qnocm
  delete item.qnik
  delete item.qbpjs
  delete item.qalamat
  delete item.tglLahir
  fetchMitra()
}
function filter() {
  fetchMitra()
}

watch(
  () => item.isbayi,
  () => {
    fetchMitra()
  }
)

fetchMitra()
// listDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/listview';

@keyframes blink {
  0% {
    opacity: 1;
  }

  50% {
    opacity: 0;
  }

  100% {
    opacity: 1;
  }
}

.blink {
  animation: blink 2s infinite;
  /* Animasi berkedip setiap 1 detik */
}
</style>
