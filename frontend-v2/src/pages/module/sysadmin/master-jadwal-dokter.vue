<template>
  <VCard>
    <div class="columns column">
      <h3 class="title is-5 mb-2 mr-1">Informasi Jadwal Dokter</h3>
    </div>

    <div class="columns is-multiline">
      <div class="column is-9">
        <div class="user-grid-toolbar">
          <VControl>
              <InputSwitch v-model="item.aktif" @change="fetchData()" />
          </VControl>
          <span>Aktif</span>
          <div class="buttons">
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
            <VButton color="primary" raised @click="add()">
              <span class="icon">
                <i aria-hidden="true" class="fas fa-plus"></i>
              </span>
              <span>Tambah Data</span>
            </VButton>
          </div>

        </div>

        <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
          <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

            <Column field="no" header="No"></Column>
            <Column field="namalengkap" header="Nama Dokter" :sortable="true" style="min-width:150px"></Column>
            <Column field="hari" header="Hari" style="min-width:150px"></Column>
            <Column field="namaruangan" header="Ruangan" :sortable="true" style="min-width:150px"></Column>
            <Column field="jammulai" header="Jam Mulai"></Column>
            <Column field="jamakhir" header="Jam Akhir"></Column>
            <Column :exportable="false" header="Action">
              <template #body="slotProps">
                <VIconButton type="button" icon="pi pi-pencil" class="mr-5" color="info" square outlined raised
                  v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                </VIconButton>
                <VIconButton type="button" icon="fas fa-trash" class="mr-5" color="danger" circle outlined raised
                  v-tooltip.top="'Hapus'" @click="deleterow(slotProps.data)">
                </VIconButton>
              </template>
            </Column>
          </DataTable>
        </div>
        <div class="user-grid tile-grid-v1" v-else-if="selectView == 'grid'">
          <TransitionGroup name="list" tag="div" class="columns is-multiline">
            <!--Grid item-->
            <div v-for="(item, key) in dataSourcefiltered" :key="key" class="column is-6">
              <div class="tile-grid-item">
                <div class="tile-grid-item-inner">
                  <VAvatar size="medium" picture="/images/avatars/svg/dokter.svg" color="primary" squared bordered />
                  <div class="meta">
                    <span class="dark-inverted">{{ item.namalengkap }}</span>
                    <span>{{ item.namaruangan }}</span>
                  </div>
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
                          <span>Hapus Data</span>
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
      <div class="column is-3">
        <div class="columns is-multiline">

          <div class="column is-6">
            <h3 class="title is-5 mb-2 mr-1">Filter</h3>
          </div>
          <div class="column is-6">
            <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined raised>
              Reset
            </a>
          </div>

          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Nama Dokter</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.namalengkap" :options="d_pegawai" placeholder="Pilih Dokter"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField class="is-autocomplete-select">
              <VLabel>Ruangan</VLabel>
              <VControl icon="feather:search">
                <Multiselect mode="single" v-model="item.namaruangan" :options="d_ruangan" placeholder="Pilih Ruangan"
                  :searchable="true" />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VButton @click="fetchData()" :loading="isLoading" type="button" icon="feather:search" class="is-fullwidth mr-3"
              color="info" raised>
              Cari Data
            </VButton>
          </div>
        </div>
      </div>
    </div>
    <template>
    </template>
    <VModal :open="modalInput" title="Tambah Jadwal Dokter" actions="right" @close="modalInput = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Ruangan</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectruanganfk" :options="d_ruangan" placeholder="Pilih data"
                    :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-rounded-select is-autocomplete-select">
                <VLabel>Nama Dokter</VLabel>
                <VControl icon="feather:search">
                  <Multiselect mode="single" v-model="item.objectpegawaifk" :options="d_pegawai" placeholder="Pilih data"
                    :searchable="true" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Hari">
                <VControl icon="feather:bookmark">
                  <MultiSelect v-model="item.hari" display="chip" :options="d_hari" optionLabel="label"
                      placeholder="Pilih Hari" class="is-rounded w-full" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VDatePicker v-model="input.jammulai" color="green" mode="time" is24hr>
                  <template #default="{ inputValue, inputEvents }">
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VLabel>Jam Mulai Praktek </VLabel>
                        <VControl icon="feather:clock">
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                  </template>
              </VDatePicker>
            </div>
            <div class="column is-6">
              <VDatePicker v-model="input.jamakhir" color="green" mode="time" is24hr style="border-radius : 40px">
                  <template #default="{ inputValue, inputEvents }">
                      <VField class="is-rounded-select is-autocomplete-select">
                        <VLabel>Jam Selesai Praktek</VLabel>
                        <VControl icon="feather:clock">
                          <VInput :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                  </template>
              </VDatePicker>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>Keterangan</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="textarea" v-model="item.keterangan" placeholder="Keterangan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
          </div>
        </form>
      </template>
      <template #action>
        <VButton icon="feather:plus" @click="save()" :loading="isLoadingTT" color="primary" raised>Simpan</VButton>
      </template>
    </VModal>
    <VModal :open="modalDetail" title="Detail Jadwal Dokter" actions="right" @close="modalDetail = false">
      <template #content>
        <form class="modal-form">
          <div class="columns is-multiline">
            <div class="column is-12">
              <VField label="Nama Ruangan">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namaruangan" placeholder="Nama Ruangan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField label="Nama Dokter">
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.namalengkap" placeholder="Nama Ruangan" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField class="is-autocomplete-select is-curved-select">
                <VLabel>Hari</VLabel>
                <VControl icon="feather:search">
                  <Multiselect v-model="item.hari" mode="tags" :searchable="true" :create-tag="true" :options="d_hari"
                    placeholder="Pilih Hari" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-autocomplete-select is-curved-select">
                <VLabel>Jam Mulai Praktek</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jammulai" placeholder="Jam Mulai" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-6">
              <VField class="is-autocomplete-select is-curved-select">
                <VLabel>Jam Akhir Praktek</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="text" v-model="item.jamakhir" placeholder="Jam Akhir" class="is-rounded" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <VField>
                <VLabel>Keterangan</VLabel>
                <VControl icon="feather:bookmark">
                  <VInput type="textarea" v-model="item.keterangan" placeholder="Keterangan" class="is-rounded" />
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
import InputSwitch from 'primevue/inputswitch'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import moment from 'moment'
import MultiSelect from 'primevue/multiselect';
import * as H from '/@src/utils/appHelper'
useHead({
  title: 'Jadwal Dokter - ' + import.meta.env.VITE_PROJECT,
})
// const item: any = ref({

// })
const item: any = ref({
  start: new Date(),
  aktif: true,
})
const input: any = ref({
  jammulai: "2023-10-25 00:00",
  jamakhir: "2023-10-25 00:00",
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
console.log(item.value);

const selectView: any = ref()
selectView.value = 'list'
const d_ruangan = ref([])
const d_pegawai = ref([])
const d_hari = [
  { value: 'Senin', label: 'Senin' },
  { value: 'Selasa', label: 'Selasa' },
  { value: 'Rabu', label: 'Rabu' },
  { value: 'Kamis', label: 'Kamis' },
  { value: 'Jumat', label: 'Jumat' },
  { value: 'Sabtu', label: 'Sabtu' },
  { value: 'Minggu', label: 'Minggu' },
]

let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
let ID_JadwalDokter = useRoute().query.id as string
let ID_JadwalDokter_SET = ref()

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const date = ref({
  start: new Date(),
  end: new Date(),
})
const filters = ref('')
const dataSourcefiltered = computed(() => {
  if (!filters.value) {
    return dataSource.value
  }

  return dataSource.value.filter((items: any) => {
    return (
      items.namaruangan.match(new RegExp(filters.value, 'i'))
      || items.namalengkap.match(new RegExp(filters.value, 'i'))
      || items.hari.match(new RegExp(filters.value, 'i'))

    )
  })
})

const route = useRoute()
isLoading.value = false

const fetchData = async ()=>{
isLoading.value = true

if(item.aktif == undefined){
    item.aktif = true
}
let namalengkap = item.value.namalengkap == undefined ? ""  :item.value.namalengkap ;
let namaruangan = item.value.namaruangan == undefined ? ""  : item.value.namaruangan;

await useApi().get('/sysadmin/master-jadwal-dokter?statusenabled=' + item.aktif +'&objectpegawaifk=' + namalengkap +'&objectruanganfk=' + namaruangan).then((response)=>{
    response.data.forEach((element:any,i:any) => {
        element.no = i+1
        element.namalengkap = (element.namalengkap.length > 40) ? element.namalengkap.substring(0, 40) + '...' : element.namalengkap
    });
    dataSource.value = response.data
    isLoading.value = false
})
}


function loadDropdown() {
  d_ruangan.value = []
  useApi().get(
    `/sysadmin/master-jadwal-dokter-dropdown`).then((response: any) => {
      d_ruangan.value = response.namaruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
      d_pegawai.value = response.namalengkap.map((e: any) => { return { label: e.namalengkap, value: e.id } })
    })
}

function add() {
  clear()
  modalInput.value = true
}

function edit(e: any) {
    let dataHari = []
    let dataHariSelect = e.hari.split(",")
    for (let i = 0; i < dataHariSelect.length; i++) {
        const element = dataHariSelect[i];
        for (let j = 0; j < d_hari.length; j++) {
            const element2 = d_hari[j];
            if(element.trim() == element2.value) {
                dataHari.push(element2)
            }
        }
    }

  item.value.id = e.id
  item.value.objectpegawaifk = e.objectpegawaifk
  item.value.objectruanganfk = e.objectruanganfk
  item.value.hari = dataHari
  input.value.jammulai =  H.formatDate(new Date(), 'YYYY-MM-DD') + " " + e.jammulai
  input.value.jamakhir =  H.formatDate(new Date(), 'YYYY-MM-DD') + " " + e.jamakhir
  item.value.keterangan = e.keterangan
  modalInput.value = true
}
function detail(e: any) {
  item.value.id = e.id
  item.value.namalengkap = e.namalengkap
  item.value.namaruangan = e.objectruanganfk
  item.value.hari = e.hari
  item.value.jammulai = e.jammulai
  item.value.jamakhir = e.jamakhir
  item.value.keterangan = e.keterangan
  modalDetail.value = true
}

async function save() {
  if (!item.value.objectruanganfk) {
    useToaster().error('Nama Ruangan harus di isi')
    return
  }
  if (!item.value.objectpegawaifk) {
    useToaster().error('Nama Dokter harus di isi')
    return
  }
  if (!input.value.jammulai) {
    useToaster().error('Jam Mulai Praktek harus di isi')
    return
  }
  let listHari = '';
    for (var i = 0; i < item.value.hari.length; i++) {
        if (i == item.value.hari.length - 1) {
            listHari += item.value.hari[i].value
        } else {
            listHari += item.value.hari[i].value + ", "
        }
  }
  var objSave =
  {
    'jadwaldokter': {
      'id': item.value.id ? item.value.id : '',
      'statusenabled': item.value.statusenabled,
      'objectruanganfk': item.value.objectruanganfk,
      'objectpegawaifk': item.value.objectpegawaifk,
      'hari': listHari != '' && listHari != undefined ? listHari : null,
      'jammulai': H.formatDate(input.value.jammulai, 'HH:mm'),
      'jamakhir':  H.formatDate(input.value.jamakhir, 'HH:mm'),
      'statusenabled' : true,
      'keterangan': item.value.keterangan ? item.value.keterangan : null,
    }
  }
  isLoadingTT.value = true
  await useApi().post(
    `/sysadmin/save-jadwal-dokter`, objSave).then((response: any) => {
      isLoadingTT.value = false
      clear()
      fetchData()
    }, (error) => {
      isLoadingTT.value = false
      // console.log(error)
    })
}

async function deleterow(e: any) {
  await useApi().post(
    `/sysadmin/delete-jadwal-dokter`, { 'id': e.id }).then((response: any) => {
      fetchData()
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

function clearFilter() {
  item.value.namaruangan = '',
  item.value.namalengkap = ''
  fetchData()
}

fetchData()
loadDropdown()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.p-inputswitch.p-inputswitch-checked .p-inputswitch-slider {
  background: #e62964 !important;
}
.p-inputswitch-checked {
  .p-inputswitch-slider:hover {
    background: #e62964 !important;
  }
}

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
