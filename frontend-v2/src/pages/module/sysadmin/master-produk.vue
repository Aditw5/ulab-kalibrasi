<template>
  <VCard>
    <TabView v-model:activeIndex="activeValue">
      <TabPanel header="Produk">
        <div class="columns is-multiline  projects-card-grid">
          <div class="column is-3">
            <VField v-slot="{ id }" class="is-icon-select">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name" :options="d_View"
                  :searchable="true" track-by="name" mode="single" @select="changeView(selectView)" autocomplete="off">
                  <template #singlelabel="{ value }">
                    <div class="multiselect-single-label">
                      <div class="select-label-icon-wrap">
                        <i :class="value.icon"></i>
                      </div>
                      <span class="select-label-text">
                        {{ value.name }}
                      </span>
                    </div>
                  </template>
                  <template #option="{ option }">
                    <div class="select-option-icon-wrap">
                      <i :class="option.icon"></i>
                    </div>
                    <span class="select-option-text">
                      {{ option.name }}
                    </span>
                  </template>
                </Multiselect>
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <a type="button" class="is-pulled-right" color="info" outlined raised>
              <VButton color="primary" RouterLink :to="{ name: 'module-sysadmin-produk-baru' }">
                <i class="fa fa-plus"></i> Produk Baru
              </VButton>
            </a>
          </div>
        </div>

        <div class="columns">
          <div class="column is-9" v-if="selectView == 'grid'">
            <div class="page-placeholder" v-if="dataSource.length == 0">
              <div class="placeholder-content">
                <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev" alt="" />
                <h3>{{ H.assets().notFound }}</h3>
                <p class="is-larger">
                  {{ H.assets().notFoundSubtitle }}
                </p>
              </div>
            </div>
            <div class="tile-grid tile-grid-v1">
              <TransitionGroup name="list" tag="div" class="columns is-multiline">
                <div v-for="(item, key) in dataSource" :key="key" class="column is-4">
                  <div class="tile-grid-item">
                    <div class="tile-grid-item-inner">
                      <VAvatar size="small" picture="/images/simrs/produk-ico.png" color="primary" squared bordered />
                      <div class="meta">
                        <span class="dark-inverted"> {{ item.namaproduk }}</span>
                      </div>
                      <VDropdown icon="feather:more-vertical" spaced right>
                        <template #content>
                          <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                            <div class="icon">
                              <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Detail</span>
                              <span>Untuk melihat data </span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                            <div class="icon">
                              <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                            </div>
                            <div class="meta">
                              <span>Edit</span>
                              <span>Untuk merubah data </span>
                            </div>
                          </a>
                          <a role="menuitem" class="dropdown-item is-media" @click="hapus(item)">
                            <div class="icon">
                              <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                            </div>
                            <div class="meta">
                              <span>Remove</span>
                              <span>Hapus Data dari Daftar</span>
                            </div>
                          </a>
                        </template>
                      </VDropdown>
                    </div>
                  </div>
                </div>
              </TransitionGroup>
              <div class="dataTable-bottom">
                <div class="dataTable-info">Menampilkan {{ dataSource.length }} ke {{ currentPage.limit }} dari
                  {{ currentPage.total }} entri data
                </div>
              </div>
            </div>
          </div>
          <div class="column is-9" v-else-if="selectView == 'list'">
            <DataTable :value="dataSource" class="p-datatable-sm" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">


              <Column field="no" header="#"></Column>
              <Column field="namaproduk" header="Produk" :sortable="true"></Column>
              <Column :exportable="false" header="Action" style="text-align: center;">
                <template #body="slotProps">
                  <VDropdown icon="feather:more-vertical" spaced right>
                    <template #content>
                      <a role="menuitem" class="dropdown-item is-media" @click="edit(slotProps.data)">
                        <div class="icon">
                          <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                        </div>
                        <div class="meta">
                          <span>Edit</span>
                          <span>Untuk merubah data </span>
                        </div>
                      </a>
                      <a role="menuitem" class="dropdown-item is-media" @click="hapus(slotProps.data)">
                        <div class="icon">
                          <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                        </div>
                        <div class="meta">
                          <span>Remove</span>
                          <span>Hapus Data dari Daftar</span>
                        </div>
                      </a>
                    </template>
                  </VDropdown>
                </template>
              </Column>
            </DataTable>
          </div>
          <div class="column is-3">
            <div class="columns is-multiline">
              <div class="column is-6">
                <h3 class="title is-5 mb-2 mr-1">Filter</h3>
              </div>
              <div class="column is-6">
                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
                  Clear
                </a>
              </div>
              <div class="column is-12">
                <VField label="Produk">
                  <VControl icon="feather:search">
                    <input v-model="item.namaproduk" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Nama Produk" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VField label="Rows">
                  <VControl icon="fas fa-list-ol">
                    <input v-model="currentPage.limit" v-on:keyup.enter="filter()" type="text" class="input is-rounded"
                      placeholder="Rows" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">
                <VButton @click="filter()" :loading="isLoading" type="button" icon="feather:search"
                  class="is-fullwidth mr-3" color="info" raised>
                  Pencarian
                </VButton>
              </div>
            </div>
          </div>
        </div>
      </TabPanel>
      <TabPanel header="Map Ruangan To Produk">
        <MasterKelompokProduk v-if="activeValue == 2"></MasterKelompokProduk>
      </TabPanel>
    </TabView>

  </VCard>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import MasterKelompokProduk from './master-kelompok-produk.vue'
import MasterJenisProduk from './master-jenis-produk.vue'
// import MasterBahanProduk from './master-bahan-produk.vue'
// import MasterBentukProduk from './master-bentuk-produk.vue'
// import MasterProdusenProduk from './master-produsen-produk.vue'
// import MasterDetailJenisProduk from './master-detail-jenis-produk.vue'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import * as H from '/@src/utils/appHelper'
import MappingRuanganToProduk from './mapping-ruangan-to-produk.vue'
import HargaNetto from './master-harga-netto-produk-by-kelas.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
useHead({
  title: 'Produk - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setFullWidth(false)
let dataSource: any = ref([])
let dataSourceTidakAktif: any = ref([])
let isLoading: any = ref(false)
let item: any = reactive({})
let listProduk: any = ref([])
let activeValue: any = ref(0)
let DetailProduk: any = ref([])
const router = useRouter()
const userLogin = useUserSession().getUser()
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
const currentPage: any = ref({
  limit: 10,
  rows: 50,
  total: 0,
})
const route = useRoute()
const d_View = [
  {
    name: 'Grid View',
    value: 'grid',
    icon: 'fas fa-id-card-alt',
  },
  {
    name: 'List View',
    value: 'list',
    icon: 'fas fa-list',
  },
]
const selectView: any = ref()
selectView.value = 'grid'
isLoading.value = false
listDropdown()

async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let nmProduk = ''
  let JenisProduk = ''
  let DetailJenisProduk = ''

  if (item.namaproduk) nmProduk = '&namaproduk=' + item.namaproduk
  if (item.jenisproduk) JenisProduk = '&objectjenisprodukfk=' + item.jenisproduk
  if (item.detailjenisproduk) DetailJenisProduk = '&objectdetailjenisprodukfk=' + item.detailjenisproduk

  const response = await useApi().get(
    '/sysadmin/master-produk?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    nmProduk + JenisProduk + DetailJenisProduk
  )
  isLoading.value = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
  currentPage.value.total = response.count
}

// function listDropdown() {
//   DetailProduk.value = []
//   useApi().get(`/sysadmin/master-produk-dropdown`).then((response: any) => {
//     DetailProduk.value = response.detailjenisproduk.map((e: any) => { return { label: e.detailjenisproduk, value: e.id } })
//   })
// }

function listDropdown() {
  DetailProduk.value = [];

  if (kelompokUser === 'logistik') {
    const allowedIds = [
      2219, 1469, 1470, 1471, 1472, 1473, 1474, 1475,
      1587, 1588, 1589, 1591, 1597, 474, 1346, 1463,
      1464, 1476, 1593, 1904, 1346, 1346, 1346, 1346,
      1346, 1346, 1346, 1346, 1346, 444, 899, 1318,
      763, 796, 797, 808, 1082, 1398, 1430, 1434,
      1493, 1657, 1910, 1956, 1958
    ];
    useApi().get(`/sysadmin/master-produk-dropdown`).then((response: any) => {
      DetailProduk.value = response.detailjenisproduk
        .filter((e: any) => allowedIds.includes(e.id))
        .map((filteredItem: any) => ({ label: filteredItem.detailjenisproduk, value: filteredItem.id }));
    });
  } else {
    useApi().get(`/sysadmin/master-produk-dropdown`).then((response: any) => {
      DetailProduk.value = response.detailjenisproduk.map((e: any) => ({ label: e.detailjenisproduk, value: e.id }));
    });
  }
}

function clearFilter() {
  delete item.namaproduk
  delete item.detailjenisproduk
  item.qAktif = false
  fetchData()
}

function filter() {
  fetchData()
}
function edit(e: any) {
  router.push({
    name: 'module-sysadmin-produk-baru',
    query: {
      id: e.id,
    },
  })
}
function editTidakAktif(e: any) {
  router.push({
    name: 'module-sysadmin-produk-baru-tidak-aktif',
    query: {
      id: e.id,
    },
  })
}
function detail(e: any) {
  router.push({
    name: 'module-sysadmin-produk-baru',
    query: {
      id: e.id,
    },
  })
}

function hapus(e: any) {
  useApi().post(
    `sysadmin/delete-master-produk`, { 'id': e.id }).then((response: any) => {
      fetchData()
    }).catch((e: any) => {

    })
}
function changeView(e: any) {
  selectView.value = e
}
fetchData()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/module/sysadmin/produk.scss';

.tabs-inner {
  margin-right: unset;
}
</style>
