<template>
  <VCard radius="small">
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Suku</h3>
    </div>

    <div class="columns is-multiline">
      <div class="column is-8">
        <div class="user-grid-toolbar">
          <VControl icon="feather:search">
            <input v-model="filters" class="input custom-text-filter" placeholder="Cari Data..." />
          </VControl>
          <div class="buttons">
            <VField v-slot="{ id }" class="is-icon-select">
              <VControl>
                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                  :options="d_View" :searchable="true" track-by="name" mode="single" @select="changeView(selectView)"
                  autocomplete="off">
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
            <VControl class="is-pulled-right">
              <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger" @change="changeSwitch(item.aktif)" />
            </VControl>
          </div>
        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <Column field="id" header="Kode"></Column>
            <Column field="suku" header="Suku" :sortable="true"></Column>
            <Column field="reportdisplay" header="Report Display" :sortable="true"></Column>
            <Column :exportable="false" header="Action">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" class="mr-3" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="deleterow(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </div>
        <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">
          <TransitionGroup name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VIconBox size="medium" color="danger" squarre>
                    <VAvatar size="medium" picture="/images/avatars/svg/suku.svg" color="primary" squared bordered />
                  </VIconBox>

                  <div class="meta">
                    <span class="dark-inverted">{{ item.suku }}</span>
                  </div>
                  <VTag :color="item.status_c" :label="item.status" style="margin-left:90px" />
                  <VDropdown icon="feather:more-vertical" spaced right>
                    <template #content>
                      <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
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
                      <a role="menuitem" class="dropdown-item is-media" @click="deleterow(item)">
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
        </div>
      </div>
      <div class="column is-4">
        <img src="/images/avatars/svg/suku.png" alt="" srcset=""
          style="max-width:60%; margin-top: -5rem; margin-left: 1rem;">
        <VCard>
          <div class="columns is-multiline">
            <div class="column is-6">
              <h3 class="title is-6 mb-2 mr-1">
                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                Tambah Data
              </h3>
            </div>
            <div class="column is-12">
              <VField label="Suku">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.suku" placeholder="Suku" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-8">
              <VField label="Report Display">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Status</VLabel>
                <VControl>
                  <VSwitchBlock v-model="item.statusenabled" :options="d_status" label="Aktif" color="danger" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VButton @click="save()" :loading="isLoadingTT" type="button" icon="feather:save"
                class="is-fullwidth mr-3" color="success" raised>
                Simpan Data
              </VButton>
            </div>
          </div>
        </VCard>
      </div>
    </div>
    <VModal :open="modalDetail" title="Detail Suku" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Suku">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.suku" placeholder="Suku" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Nama Eksternal">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaexternal" placeholder="Nama Eksternal" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Report Display">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.reportdisplay" placeholder="Report Display" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField label="Kode Suku">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.id" placeholder="Kode Suku" class="is-rounded" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>

    </VModal>
  </VCard>

</template>
  
<script  setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import { useViewWrapper } from '/@src/stores/viewWrapper'
useHead({
  title: 'Suku - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const item: any = ref({
  aktif: true
})
const modalInput = ref(false)
const modalDetail = ref(false)

let dataSource: any = ref([])
let isRegistrasi = ref(false)
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
selectView.value = 'list'
const d_status = [
  { value: 't', label: 'True' },
  { value: 'f', label: 'False' },
]
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.suku.match(new RegExp(filters.value, 'i'))
    )
  })
})

const route = useRoute()
isLoading.value = false

async function fetchData() {
  isLoading = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  let Suku = ''
  let ReportDisplay = ''
  let kdSuku = ''
  let StatusEnabled = ''

  if (item.suku) Suku = '&suku' + item.suku
  if (item.reportdisplay) ReportDisplay = '&reportdisplay=' + item.reportdisplay
  if (item.value.aktif) StatusEnabled = '&statusenabled=' + item.value.aktif
  if (item.id) kdSuku = '&kdsuku=' + item.kdsuku
  item.value.aktif ? StatusEnabled = '&statusenabled=' + item.value.aktif : StatusEnabled = '&statusenabled=false'

  const response = await useApi().get(
    '/sysadmin/master-suku?offset=' + offset +
    '&limit=' + limit +
    '&rows=' + rows +
    kdSuku + Suku + ReportDisplay + StatusEnabled
  )
  isLoading = false
  for (let x = 0; x < response.data.length; x++) {
    const element = response.data[x];
    element.no = x + 1
  }

  dataSource.value = response.data
  //   dataSource.value.total = response.length
}

function loadData() {
  fetchData()
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.value.id = e.id
  item.value.suku = e.suku
  item.value.namaexternal = e.namaexternal
  item.value.statusenabled = e.statusenabled
  item.value.reportdisplay = e.reportdisplay
}
function detail(e: any) {
  item.value.id = e.id
  item.value.suku = e.suku
  item.value.namaexternal = e.namaexternal
  item.value.reportdisplay = e.reportdisplay
  item.value.statusenabled = e.statusenabled
  modalDetail.value = true
}

async function save() {
  if (!item.value.suku) {
    useToaster().error('Suku harus di isi')
    return
  }
  var objSave =
  {
    'suku': {
      'id': item.value.id ? item.value.id : '',
      'suku': item.value.suku,
      'namaexternal': item.value.namaexternal ? item.value.namaexternal : null,
      'reportdisplay': item.value.reportdisplay ? item.value.reportdisplay : null,
      'statusenabled': item.value.statusenabled,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-suku`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      loadData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-suku`, { 'id': e.id }).then((response: any) => {
      loadData()
    }, (error) => {
    })
}

function clear() {
  item.value = {}
  modalInput.value = false
}
function changeView(e: any) {
  selectView.value = e
}
function filter() {
  fetchData()
}
function clearFilter() {
  fetchData()
}
function changeSwitch(e: any) {
  loadData()
}
loadData()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

</style>