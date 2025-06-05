<template>
  <VCard>
  <div class="columns column">
    <h3 class="title is-5 mb-2 mr-1">Mapping Kelompok Staf Medis to Ruangan</h3>
  </div>
  <div class="columns is-multiline">
    <div class="column is-8">
      <div class="user-grid-toolbar">
        <VControl icon="feather:search">
          <input v-model="filters" class="input custom-text-filter" placeholder="Cari Data..." />
        </VControl>
        <div class="buttons">
          <VControl class="is-pulled-right">
            <VSwitchBlock v-model="item.aktif" label="Aktif" color="danger" @change="changeSwitch(item.aktif)" />
          </VControl>
        </div>
      </div>
      <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
        <DataTable :value="dataSourcefiltered" class="p-datatable-sm" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
          paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
          responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

          <Column field="no" header="No"></Column>
          <Column field="namaruangan" header="Nama Ruangan" :sortable="true"></Column>
          <Column field="kelompokstafmedis" header="Kelompok Staf Medis"></Column>
          <Column field="status" header="Status"></Column>
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
    </div>
    <div class="column is-4">
      <img src="/images/avatars/label/switches.svg" alt="" srcset=""
        style="max-width:60%; margin-top: -4rem; margin-left: 10rem;">
      <VCard>
        <div class="columns is-multiline">
          <div class="column is-6">
            <h3 class="title is-6 mb-2 mr-1">
              <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
              Tambah Data
            </h3>
          </div>
          <div class="column is-12">
            <VField label="Ruangan" class="is-rounded-select  is-autocomplete-select"
            v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                  <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-rounded-select is-autocomplete-select">
              <VLabel>Kelompok Staf Medis</VLabel>
              <VControl icon="feather:list" fullwidth :loading="isLoading" class="prime-auto-select">
                  <MultiSelect v-model="item.ksm" display="chip"
                      class="w-100 is-rounded" :options="d_Ksm"
                      optionLabel="label" optionValue="value" filter
                      :selectAll="false" :showToggleAll="false"
                      placeholder="Pilih Data" :maxSelectedLabels="3" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField label="Status" class="is-rounded-select  is-autocomplete-select"
            v-slot="{ id }">
              <VControl icon="feather:list" fullwidth>
                  <Multiselect mode="single" v-model="item.statusenabled" :options="d_Statusenabled" placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="save(item)" :loading="isLoading" type="button"
              :icon="item.id ? 'feather:edit' : 'feather:save'" class="is-fullwidth mr-3"
              :color="item.id ? 'info' : 'success'" raised>
              {{ item.id ? 'Update Data' : 'Simpan Data' }}
            </VButton>
            <VButton v-if="item.id" @click="clear()" type="button" icon="feather:x-circle"
              class="is-fullwidth is-outlined is-warning mt-3" raised>
              Batal Edit
            </VButton>
          </div>
        </div>
      </VCard>
    </div>
  </div>
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
import MultiSelect from 'primevue/multiselect';
import * as H from '/@src/utils/appHelper'

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
useHead({
  title: 'Kelompok Staf Medis - ' + import.meta.env.VITE_PROJECT,
})
const item: any = ref({
  aktif: true
})
const modalInput = ref(false)
const modalDetail = ref(false)
let dataSource: any = ref([])
let isRegistrasi = ref(false)
const d_Ruangan = ref([])
const d_Ksm = ref([])
const d_Statusenabled = ref([
  { label: 'Aktif', value: 1 },
  { label: 'Tidak Aktif', value: 2 }
])
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
      items.kelompokstafmedis.match(new RegExp(filters.value, 'i'))
    )
  })
})

const route = useRoute()
isLoading.value = false

async function loadCombo() {
  isLoading.value = true
  await useApi().get('sysadmin/get-combo-map-ksm').then((resp: any) => {
    isLoading.value = false
    d_Ruangan.value = resp.ruangan.map((item:any) => { return { label: item.namaruangan, value: item.id } })
    d_Ksm.value = resp.kelompokstafmedis.map((item:any) => { return { label: item.kelompokstafmedis, value: item.id } })
  }).catch((err: any) => {
    console.log(err)
  })
}

async function fetchData() {
  isLoading.value = true
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = offset * limit - limit
  let rows: any = currentPage.value.rows
  
  let response = await useApi().get('sysadmin/get-map-ksm-to-ruangan?status=' + item.value.aktif)
  isLoading.value = false
  if (response.data.length > 0) {
    let nomor = 1
    for (const item of response.data) {
      item.no = nomor++
    }
  }
  dataSource.value = response.data
}

function loadData() {
  fetchData ()
}

function openModalTambah() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
  item.value.id = e.id
  item.value.ruangan = e.objectruanganfk
  item.value.ksm = [e.objectkelompokstafmedisfk] 
  if (e.statusenabled) {
    item.value.statusenabled = 1
  } else {
    item.value.statusenabled = 2 
  }
}
function detail(e: any) {
  
}

async function save() {
  if (item.value.ruangan == undefined) {
    H.alert('warning', 'Ruangan belum dipilih')
    return
  }
  if (item.value.ksm == undefined || item.value.ksm.length == 0) {
    H.alert('warning', 'Kelompok staf medis belum dipilih')
    return
  }
  if (item.value.statusenabled == undefined) {
    H.alert('warning', 'Status belum diisi')
    return
  }

  let jsonSave = {
    id: item.value.id ? item.value.id : '',
    aktif: item.value.statusenabled,
    ruangan: item.value.ruangan,
    arrksm: item.value.ksm
  }

  isLoading.value = true
  useApi().post('sysadmin/save-map-ksm-to-ruangan', jsonSave)
  .then((resp:any) => {
      isLoading.value = false
      item.value.aktif = true
      clear()
      loadData()
    }).catch((err: any) => {
      isLoading.value = true
    })
}

async function deleterow(e: any) {
  let jsonDelete = {
    id: e.id
  }

  isLoading.value = true
  useApi().post('sysadmin/delete-map-ksm-to-ruangan', jsonDelete)
  .then((resp:any) => {
    isLoading.value = false
    loadData()
  }).catch((err:any) => {
    isLoading.value = false
  })
}

function clear() {
  item.value.id = undefined
  item.value.ruangan = undefined
  item.value.ksm = undefined
  item.value.statusenabled = undefined
}
function changeView(e: any) {
  selectView.value = e
}
function changeSwitch(e: any) {
  fetchData()
}
function filter() {
  fetchData()
}
function clearFilter() {
  fetchData()
}

loadData()
loadCombo()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.tile-grid {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }
}

.is-dark {
  .tile-grid {
    .tile-grid-item {
      @include vuero-card--dark;
    }
  }
}

.tile-grid-v1 {
  .tile-grid-item {
    @include vuero-s-card;

    border-radius: 14px;
    padding: 16px;

    .tile-grid-item-inner {
      display: flex;
      align-items: center;

      .meta {
        margin-left: 10px;
        line-height: 1.2;

        span {
          display: block;
          font-family: var(--font);

          &:first-child {
            color: var(--dark-text);
            font-family: var(--font-alt);
            font-weight: 600;
            font-size: 1rem;
          }

          &:nth-child(2) {
            color: var(--light-text);
            font-size: 0.9rem;
          }
        }
      }

      .dropdown {
        position: relative;
        margin-left: auto;
      }
    }
  }
}

.fs-075 {
  font-size: 0.9rem;
}

.is-navbar {
  .form-layout {
    margin-top: 30px;
  }
}

.form-layout {
  // max-width: 740px;
  margin: 0 auto;

  &.is-separate {
    // max-width: 1040px;

    .form-outer {
      background: none;
      border: none;

      .form-body {
        display: flex;

        .form-section {
          flex-grow: 2;
          padding: 10px;
          width: 50%;

          .form-section-inner {
            @include vuero-s-card;

            padding: 40px;

            &.has-padding-bottom {
              padding-bottom: 60px;
              height: 100%;
            }

            >h3 {
              font-family: var(--font-alt);
              font-size: 1.2rem;
              font-weight: 600;
              color: var(--dark-text);
              margin-bottom: 30px;
            }

            .columns {
              .column {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
              }
            }

            .radio-boxes {
              display: flex;
              justify-content: space-between;
              margin-left: -8px;
              margin-right: -8px;

              .radio-box {
                position: relative;
                width: calc(50% - 16px);
                margin: 8px;

                &:focus-within {
                  border-radius: 3px;
                  outline-offset: var(--accessibility-focus-outline-offset);
                  outline-width: var(--accessibility-focus-outline-width);
                  outline-style: var(--accessibility-focus-outline-style);
                  outline-color: var(--primary);
                }

                input {
                  position: absolute;
                  top: 0;
                  left: 0;
                  height: 100%;
                  width: 100%;
                  opacity: 0;
                  cursor: pointer;

                  &:checked {
                    +.radio-box-inner {
                      background: var(--primary);
                      border-color: var(--primary);
                      box-shadow: var(--primary-box-shadow);

                      .fee,
                      p {
                        color: var(--smoke-white);
                      }
                    }
                  }
                }

                .radio-box-inner {
                  background: var(--white);
                  border: 1px solid var(--fade-grey-dark-3);
                  text-align: center;
                  border-radius: var(--radius);
                  font-family: var(--font);
                  font-weight: 600;
                  font-size: 0.9rem;
                  transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                    height 0.3s, width 0.3s;
                  padding: 30px 20px;

                  .fee {
                    font-family: var(--font);
                    font-weight: 700;
                    color: var(--dark-text);
                    font-size: 2.4rem;
                    line-height: 1;

                    span {
                      &::after {
                        content: '$';
                        position: relative;
                        top: -10px;
                        font-size: 1.5rem;
                      }
                    }
                  }

                  p {
                    font-family: var(--font-alt);
                  }
                }
              }
            }

            .control {
              >p {
                padding-top: 12px;

                >span {
                  display: block;
                  font-size: 0.9rem;

                  span {
                    font-weight: 500;
                    color: var(--dark-text);
                  }
                }
              }
            }
          }

          .form-section-outer {
            .checkboxes {
              padding: 16px 0;

              .checkbox {
                padding: 0;
                font-size: 0.9rem;
              }
            }

            .button-wrap {
              .button {
                min-height: 60px;
                font-size: 1.05rem;
                font-weight: 600;
                font-family: var(--font-alt);
              }
            }
          }
        }
      }
    }
  }
}

.is-dark {
  .form-layout {
    &.is-separate {
      .form-outer {
        background: none !important;

        .form-body {
          .form-section {
            .form-section-inner {
              @include vuero-card--dark;

              >h3 {
                color: var(--dark-dark-text);
              }

              .radio-boxes {
                .radio-box {
                  input:checked+.radio-box-inner {
                    background: var(--primary);
                    border-color: var(--primary);
                    box-shadow: var(--primary-box-shadow);

                    .fee,
                    p {
                      color: var(--smoke-white);
                    }
                  }

                  .radio-box-inner {
                    background: var(--dark-sidebar-light-2);
                    border-color: var(--dark-sidebar-light-12);

                    .fee {
                      color: var(--dark-dark-text);
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
}

@media only screen and (max-width: 767px) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;
          flex-direction: column;

          .form-section {
            width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
  .form-layout {
    &.is-separate {
      .form-outer {
        .form-body {
          padding-left: 0;
          padding-right: 0;

          // flex-direction: column;

          .form-section {
            // width: 100%;

            .form-section-inner {
              padding: 30px;
            }
          }
        }
      }
    }
  }
}

.all-projects {
  .all-projects-header {
    display: flex;
    padding: 20px;
    background: var(--white);
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    margin-bottom: 1.5rem;

    .header-item {
      width: 25%;
      border-right: 1px solid var(--fade-grey-dark-3);

      &:last-child {
        border-right: none;
      }

      .item-inner {
        text-align: center;

        .lnil,
        .lnir {
          font-size: 2.2rem;
          margin-bottom: 6px;
          color: var(--primary);
        }

        span {
          display: block;
          font-family: var(--font);
          font-weight: 600;
          font-size: 1.4rem;
          color: var(--dark-text);
        }

        p {
          font-family: var(--font-alt);
        }
      }
    }
  }

  .projects-card-grid {
    .grid-item {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      min-height: 220px;
      padding: 20px;
      background: var(--white);
      border: 1px solid var(--fade-grey-dark-3);
      border-radius: var(--radius-large);

      .top-section {
        .head {
          display: flex;
          justify-content: space-between;
          align-items: center;
          margin-bottom: 8px;

          h3 {
            font-size: 1rem;
            font-family: var(--font-alt);
            color: var(--dark-text);
            font-weight: 600;
          }
        }

        .body {
          p {
            font-family: var(--font);
            color: var(--light-text);
          }
        }
      }

      .bottom-section {
        display: flex;

        .foot-block {
          margin-right: 30px;

          .heading {
            font-family: var(--font-alt);
            font-size: 0.75rem;
            color: var(--light-text-dark-22);
          }

          >p {
            padding-top: 5px;
          }

          .developers {
            display: flex;

            .v-avatar {
              margin-right: 6px;
            }
          }
        }
      }
    }
  }
}

.heading {
  font-family: var(--font-alt);
  font-size: 0.75rem;
  color: var(--light-text-dark-22);
}

.is-dark {
  .all-projects {
    .all-projects-header {
      background: var(--dark-sidebar-light-6);
      border-color: var(--dark-sidebar-light-12);

      .header-item {
        border-color: var(--dark-sidebar-light-18);

        span {
          color: var(--dark-dark-text);
        }

        i {
          color: var(--primary) !important;
        }
      }
    }

    .projects-card-grid {
      .grid-item {
        background: var(--dark-sidebar-light-6);
        border-color: var(--dark-sidebar-light-12);

        .top-section {
          .head {
            h3 {
              color: var(--dark-dark-text);
            }
          }
        }

        .bottom-section {
          .foot-block {
            .heading {
              color: var(--light-text-dark-12);
            }
          }
        }
      }
    }
  }
}
</style>
