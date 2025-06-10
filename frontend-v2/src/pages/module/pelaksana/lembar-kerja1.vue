<template>
  <div class="column">
    <div class="business-dashboard hr-dashboard">
      <div class="columns is-multiline">
        <div class="column is-12 p-0">
          <div class="block-header">
            <div class="left column is-6 p-0">
              <div class="current-user">
                <h3>{{ item.namaperusahaan }}</h3>
              </div>
            </div>
            <div class="left column is-6 p-0">
              <div>
                <div>
                  <h4 class="block-heading">No. Pendaftaran</h4>
                  <p class="block-hext">{{ item.nopendaftaran }}</p>
                  <h4 class="block-heading">Tgl Registrasi</h4>
                  <p class="block-hext">{{ item.tglregistrasi }}</p>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>

  <div class="column">
    <VCard>
      <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">

        <TabPanel>
          <template #header>
            <i class="fas fa-users mr-2" aria-hidden="true"></i>
            <span>Lembar Kerja</span>
            <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />


          </template>
          <div class="column is-12">
            <VCard>
              <div class="colum is-12">
                <div class="columns is-multiline">
                  <div class="column">
                    <div class="column is-12 mt-4-min">
                      <VButton rounded color="warning" class="" icon="feather:download" raised bold
                        @click="downloadTemplate()">
                        Download Template
                      </VButton>
                      <div class="dataTable-bottom mt-2">
                        <div class="dataTable-info" style="font-style:italic"> *Note : Template
                          digunakan untuk menyamakan data, agar saat di upload tidak terjadi
                          kesalahan memasukkan data.
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="column is-12">
                    <FileUpload name="demo[]" :multiple="false" @upload="onTemplatedUpload($event)" mode="advanced"
                      :showUploadButton="false" :showCancelButton="true" @select="onSelectedFiles" chooseLabel="Pilih"
                      cancelLabel="Batal" :maxFileSize="50000000">
                      <template #header="{ chooseCallback, uploadCallback, clearCallback, files }">
                        <div class="flex flex-wrap justify-content-between align-items-center flex-1 gap-2">
                          <div class="flex gap-2">
                            <Button @click="chooseCallback()" icon="pi pi-upload" rounded severity="info" class="mr-1"
                              outlined></Button>
                            <Button @click="clearCallback()" icon="pi pi-times" rounded outlined severity="danger"
                              :disabled="!files || files.length === 0"></Button>
                          </div>
                          <ProgressBar :value="totalSizePercent" :showValue="false"
                            :class="['md:w-20rem h-1rem w-full md:ml-auto', { 'exceeded-progress-bar': totalSizePercent > 100 }]">
                            <span class="white-space-nowrap">{{ totalSize
                              }}B / 50Mb</span>
                          </ProgressBar>
                        </div>
                      </template>
                      <template #content="{ files, uploadedFiles, removeUploadedFileCallback, removeFileCallback }">
                        <div v-if="files.length > 0">

                          <div class="flex flex-wrap p-0 sm:p-5 gap-5">
                            <div :key="files[0].name + files[0].type + files[0].size"
                              class="card m-0 px-6 flex flex-column border-1 surface-border align-items-center gap-3">
                              <div>
                                <i class="fas fa-file-excel shadow-2 mr-2" aria-hidden="true"></i>
                              </div>
                              <span class="font-semibold">{{ files[0].name
                                }}</span>
                              <div class="ml-2">{{
                                formatSize(files[0].size)
                                }}
                                <Badge :value="valueProgress >= 99 ? 'Uploaded' : 'Pending'"
                                  :severity="valueProgress >= 99 ? 'success' : 'warning'" class="ml-2 mr-2" />
                              </div>

                              <Button icon="pi pi-times"
                                @click="onRemoveTemplatingFile(files[0], removeFileCallback, 0)" outlined rounded
                                severity="danger" />
                            </div>
                          </div>
                        </div>
                      </template>
                      <template #empty>
                        <p>Drag atau drop files untuk mengupload.</p>
                      </template>
                    </FileUpload>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
          <div class="column is-12">
            <VCard>
              <div class="column" v-if="isLoading">
                <VPlaceloadWrap v-for="data in 10">
                  <VPlaceload class="mx-2 mb-3" />
                </VPlaceloadWrap>
              </div>
              <div v-else-if="dataSourcefilter.length == 0">
                <VPlaceholderSection :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" class="my-6">
                  <template #image>
                    <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderSection>
              </div>
              <div class="column is-12" v-else>
                <div class="columns is-multiline" style="align-items:right">
                  <div class="column is-3">
                    <h3 class="title is-5 mb-2 mr-1">Upload Klaim BPJS </h3>
                  </div>
                  <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status success">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TOTAL DATA</span>
                      </div>
                      <small class="text-bold-custom h-100">{{ dataSourcefilter.length }}</small>
                    </VCardCustom>
                  </div>
                  <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                      <div class="label-status warning">
                        <i aria-hidden="true" class="fas fa-circle"></i>
                        <span class="ml-1">TOTAL TARIF</span>
                      </div>
                      <small class="text-bold-custom h-100">{{ H.formatRp(item.totalTarif, 'RP')
                        }}</small>
                    </VCardCustom>
                  </div>
                  <div class="column is-3">
                    <VButton icon="feather:save" @click="Save()" :loading="isLoadingSave" color="info">Simpan</VButton>
                  </div>
                </div>
                <DataTable :value="dataSourcefilter" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                  class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
                  <Column field="ADMISSION_DATE" header="ADMISSION DATE" style="min-width: 130px" />
                  <Column field="DISCHARGE_DATE" header="DISCHARGE DATE" style="min-width: 130px" />
                  <Column field="MRN" header="MRN" style="min-width: 150px" />
                  <Column field="NAMA_PASIEN" header="NAMA PASIEN" style="min-width: 200px" />
                  <Column field="SEP" header="SEP" style="min-width: 200px" />
                  <Column field="INACBG" header="INACBG" style="min-width: 100px" />
                  <Column field="TOTAL_TARIF" header="TOTAL TARIF" style="min-width: 160px" />
                  <Column field="TARIF_RS" header="TARIF RS" style="min-width: 150px" />
                  <Column field="AKSI" header="AKSI" style="min-width: 150px" />
                </DataTable>
              </div>
            </VCard>
          </div>
          <div class="column is-12">
            <VCard>
              <div class="column is-12">
                <div class="columns is-multiline" style="text-align:center">
                  <div class="column is-12">
                    <h3 class="title is-5 mb-2 mr-1">Data Klaim BPJS </h3>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <VField label="Periode Simpan Klaim" style="margin-bottom: 6px;" />
                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>
                  </div>
                  <div class="column is-1 btn-search mt-5">
                    <VIconButton color="success" icon="fas fa-search" @click="fetchData" :loading="loadSearch" />
                  </div>
                </div>
                <DataTable :value="dataSourceDataKlaim" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                  class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="loadSearch">
                  <Column field="admission_date" header="ADMISSION DATE" style="min-width: 130px" />
                  <Column field="discharge_date" header="DISCHARGE DATE" style="min-width: 130px" />
                  <Column field="inacbg" header="INACBG" style="min-width: 100px" />
                  <Column field="total_tarif" header="TOTAL TARIF" sortable style="min-width: 160px" />
                  <Column field="tarif_rs" header="TARIF RS" sortable style="min-width: 150px" />
                  <Column field="nama_pasien" header="NAMA PASIEN" style="min-width: 200px" />
                  <Column field="mrn" header="MRN" style="min-width: 150px" />
                  <Column field="sep" header="SEP" style="min-width: 200px" />
                  <Column field="status_klaim" header="AKSI" style="min-width: 200px" />
                  <Column field="tglsimpan" header="TANGGAL SIMPAN KLAIM" sortable style="min-width: 150px" />
                </DataTable>
              </div>
            </VCard>
          </div>
        </TabPanel>
        <TabPanel>
          <template #header>
            <i class="fas fa-check-circle mr-2" aria-hidden="true"></i>
            <span>Konsultasi</span>
            <Badge :value="dataSource.length" v-if="dataSource.length > 0" severity="danger" class="ml-2" />
          </template>

          <div class="columns is-multiline">
            <div class="column">
              <div class="list-view list-view-v3" v-if="dataSource.length == 0">
                <VPlaceholderPage title="Tidak Ada Data."
                  subtitle="Silakan Pilih Tanggal dan Ruangan untuk melihat data" larger>
                  <template #image>
                    <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                    <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                  </template>
                </VPlaceholderPage>
              </div>

              <div class="columns is-multiline" v-else>
                <div class="column is-12">
                  <VCard>
                    <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]"
                      class="p-datatable-customers" filterDisplay="menu"
                      paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                      responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                      currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :scrollable="true"
                      dataKey="norec">


                      <Column :exportable="false" header="#" :style="{ width: '180px' }">
                        <template #body="slotProps">
                          <VIconButton type="button" icon="fas fa-stethoscope" class="mr-2" color="info" circle outlined
                            raised v-tooltip.top="'EMR '" @click="emr(slotProps.data)">
                          </VIconButton>
                          <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2" color="success" circle
                            outlined raised v-tooltip.top="'Jawab '" @click="jawab(slotProps.data)">
                          </VIconButton>
                        </template>
                      </Column>
                      <!-- <Column field="no" header="No" :style="{ width: '40px' }"> </Column> -->
                      <Column field="tglorder" header="Tanggal Order" style="width:150px" :sortable="true"> </Column>
                      <Column field="ruanganasal" header="Ruangan Asal" style="width:150px" :sortable="true"> </Column>
                      <Column field="namapasien" header="Nama Pasien" style="width:150px"></Column>
                      <Column field="nocm" header="No. RM" :sortable="true" style="width:150px"></Column>
                      <Column field="ruangantujuan" header="Ruangan Tujuan" style="width:200px"></Column>
                      <Column field="dokter" header="Dokter Konsul" style="width:200px"></Column>
                      <Column field="keteranganorder" header="Pertanyaan" style="width:300px"></Column>
                      <Column field="keteranganlainnya" header="Jawaban" style="width:300px"></Column>
                    </DataTable>
                  </VCard>
                </div>
              </div>
            </div>
          </div>
        </TabPanel>
      </TabView>
    </VCard>
  </div>
  <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }">
    <div class="columns is-multiline">
      <div class="column is-3">
        <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
          :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }" class="pb-0">
            <VField>
              <VLabel class="required-field">Tanggal</VLabel>
              <VControl icon="feather:calendar">
                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                  class="is-rounded" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>
      <div class="column is-3">
        <VField>
          <VLabel>Ruangan Asal</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VLabel>Ruangan Tujuan</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain "
              disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VLabel>Dokter</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.dokter" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div>


      <div class="column is-3">
        <VField>
          <VControl>
            <VSwitchBlock v-model="item.rawatBersama" label="Rawat Bersama" color="danger" disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-3">
        <VField>
          <VControl>
            <VSwitchBlock v-model="item.konsultasi" label="Konsultasi" color="danger" disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-6">
        <VField>
          <VLabel>Lain-lain</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Keterangan</VLabel>
          <VControl>
            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan" disabled>
            </VTextarea>
          </VControl>
        </VField>
      </div>
      <div class="column is-12">
        <VField>
          <VLabel>Jawaban</VLabel>
          <VControl>
            <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban">
            </VTextarea>
          </VControl>
        </VField>
      </div>
    </div>
    <template #footer>
      <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
        Batal
      </VButton>
      <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoadingBtn"
        @click="simpan()"> Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalUpdateDokter" modal header="Form Pilih Dokter" :style="{ width: '65vw' }">
    <div class="columns is-multiline">
      <!-- <div class="column is-3">
                    <VDatePicker class="pt-0 pb-0 pl-0" v-model="item.tanggal" color="green" trim-weeks mode="dateTime"
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VLabel class="required-field">Tanggal</VLabel>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                        class="is-rounded"  />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div> -->
      <!-- <div class="column is-3">
        <VField>
          <VLabel>Ruangan Asal</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruanganasal" type="text" class="input is-rounded d-hidden" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div> -->
      <div class="column is-6">
        <VField>
          <VLabel>Ruangan Asal</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.namaruanganasal" type="text" class="input is-rounded" placeholder="Lain-lain "
              disabled />
          </VControl>
        </VField>
      </div>
      <!-- <div class="column is-3">
        <VField>
          <VLabel>Ruangan Tujuan</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.ruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain " disabled />
          </VControl>
        </VField>
      </div> -->
      <div class="column is-6">
        <VField>
          <VLabel>Ruangan Tujuan</VLabel>
          <VControl icon="feather:bookmark">
            <input v-model="item.namaruangantujuan" type="text" class="input is-rounded" placeholder="Lain-lain "
              disabled />
          </VControl>
        </VField>
      </div>
      <!-- <div class="column is-3">
                    <VField>
                        <VLabel>Dokter</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.dokter" type="text" class="input is-rounded" placeholder="Lain-lain "
                            disabled/>
                        </VControl>
                    </VField>
                </div> -->


      <!-- <div class="column is-3">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="item.rawatBersama" label="Rawat Bersama" color="danger"
                            disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="item.konsultasi" label="Konsultasi" color="danger"
                            disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel>Lain-lain</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="item.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain "
                            disabled/>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="item.keterangan" rows="3" placeholder="Keterangan"
                            disabled>
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Jawaban</VLabel>
                        <VControl>
                            <VTextarea v-model="item.jawaban" rows="3" placeholder="Jawaban">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div> -->
    </div>
    <div class="column">
      <span style="font-weight: 500;">Dokter </span>
      <VField class="is-autocomplete-select pt-3">
        <VControl icon="feather:search">
          <AutoComplete v-model="item.dokter" :suggestions="d_DokterUpdate" @complete="fetchDokterUpdate($event)"
            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
            :field="'label'" placeholder="Dokter" />
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalUpdateDokter = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="simpanVerify()" :loading="btnLoadSimpan"> Update
      </VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import ConfirmDialog from 'primevue/confirmdialog'
import MultiSelect from 'primevue/multiselect';
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import moment from 'moment'
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
useHead({
  title: 'Daftar Konsultasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const item: any = ref({
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  isnotverif: true,
})
const confirm = useConfirm();

let listKelompokPasien: any = ref([])
let sourceRuangan: any = ref([])

let ds_PASIEN: any = ref([])
let listColor: any = ref(Object.keys(useThemeColors()))
const kelompokUser = useUserSession().getUser().kelompokUser.kelompokUser
// const currentPage: any = ref({
//   limit: 5,
//   rows: 50
// })

if (kelompokUser == 'dokter') {
  item.value.isdokter = true
}
if (kelompokUser == 'admin-rajal') {
  item.value.isAdmin = true
}
const activeTab = ref(0)
const userLogin = useUserSession().getUser()
const router = useRouter()
const route = useRoute()
const { y } = useWindowScroll()
const d_Ruangan = ref([])
const sourceOrder = ref([])
const dataSource = ref([])
const modalUpdateDokter = ref(false)
const d_Dokter = ref([])
const d_DokterUpdate = ref([])
const disabledJawab = ref(false)
const modalInput = ref(false)
const isLoadingBtn = ref(false)
const isLoading = ref(false)
const btnLoadSimpan = ref(false)
const modalChangeDate = ref(false)
const totalData: any = ref(0)
const isStuck = computed(() => {
  return y.value > 30
})
const currentPage: any = ref({
  limit: 5,
  rows: 50
})

currentPage.value.page = computed(() => {
  try {
    return Number.parseInt(route.query.page as string) || 1
  } catch { }
  return 1
})
const changeRuang = async (e: any) => {
  fetchOrder()
  fetchData()
}
const changeDokter = async (e: any) => {
  console.log(e.value);
  if (e.value) {
    fetchOrder()
    fetchData()
  }
}

const fetchData = async () => {
  let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
  let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let dokter = item.value.filterdokterfk ? `&dokterfk=${item.value.filterdokterfk.value}` : ''
  let idpegawai = item.value.isdokter == true ? `&idpegawai=${useUserSession().getUser().pegawai.id}` : ''

  // console.log(item.value.ruangan);

  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruangan = `&ruanganfk=${itemsRuang}`
  }
  await useApi().get(`registrasi/get-daftar-konsultasi${tglAwal}${tglAkhir}${ruangan}${dokter}${idpegawai}${search}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      let ini = element.namapasien.split(' ')
      let init = element.namapasien.substr(0, 1)
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1)
      }
      element.initials = init
    });
    dataSource.value = response
  })
}

const fetchOrder = async () => {
  ds_PASIEN.value.loading = true
  let tglAwal = `?tglAwal=${moment(item.value.periode.start).format('YYYY-MM-DD')}`
  let tglAkhir = `&tglAkhir=${moment(item.value.periode.end).format('YYYY-MM-DD')}`
  // let ruangan = item.value.ruangan ? `&ruanganfk=${item.value.ruangan}` : ''
  let ruangan
  let dokter = item.value.filterdokterfk ? `&dokterfk=${item.value.filterdokterfk.value}` : ''
  let search = item.value.search ? `&search=${item.value.search}` : ''
  let isnotverif = item.value.isnotverif ? `&isnotverif=${item.value.isnotverif}` : ''
  let idpegawai = item.value.isdokter == true ? `&idpegawai=${useUserSession().getUser().pegawai.id}` : ''
  let idadmin = item.value.isAdmin == true ? `&idadmin=${useUserSession().getUser().pegawai.id}` : ''
  let limit: any = currentPage.value.limit
  let offset: any = route.query.page ? route.query.page : 1
  offset = (offset * limit) - limit
  isLoading.value = true;
  if (sourceRuangan.value != undefined) {
    let itemsRuang = []
    sourceRuangan.value.forEach((element: any) => {
      // console.log(element);

      itemsRuang = [...new Set([...itemsRuang, element.value])]
    });
    ruangan = `&ruanganfk=${itemsRuang}`
  }
  await useApi().get(`registrasi/get-order-konsul${tglAwal}${tglAkhir}${ruangan}${dokter}${idpegawai}${isnotverif}${search}${idadmin}&limit=${limit}&offset=${offset}`).then((response) => {
    sourceOrder.value = response.data
    totalData.value = response.total
  })
  isLoading.value = false;
  // set page to 1
  route.query.page = 1
}


const edit = async (e: any) => {
  // console.log(e)

  // disabledJawab.value = false
  item.value.norec = e.norec
  // item.value.tanggal = new Date(e.tglorder)

  item.value.ruanganasal = e.ruanganasal
  item.value.ruangantujuan = e.ruangantujuan
  item.value.dokter = e.dokter


  item.value.konsultasi = e.konsultasi ? e.konsultasi : false
  item.value.lainlain = e.lainlain ? e.lainlain : ''
  item.value.rawatBersama = e.rawatbersama ? e.rawatbersama : false
  item.value.keterangan = e.keteranganorder ? e.keteranganorder : ''
  item.value.jawaban = e.keteranganlainnya ? e.keteranganlainnya : ''


  modalInput.value = true
}

const simpan = async () => {

  let formData = {
    'norec_so': item.value.norec,
    'jawaban': item.value.jawaban,
  }
  isLoadingBtn.value = true
  await useApi().post('/emr/jawab-order-konsul', formData).then((r) => {
    isLoadingBtn.value = false
    fetchData()
    modalInput.value = false
  }).catch((e: any) => {
    isLoadingBtn.value = false
  })
}
const jawab = async (e: any) => {
  edit(e)
}


const verifyOrder = async (e: any) => {
  // console.log(e);

  modalUpdateDokter.value = true
  item.value.norecpd = e.norec_pd
  item.value.ruanganasal = e.ruanganasal
  item.value.ruangantujuan = e.ruangantujuan
  item.value.objectruanganfk = e.objectruanganfk
  item.value.objectruangantujuanfk = e.objectruangantujuanfk
  item.value.kelasfk = e.kelasfk_pd
  item.value.norec_so = e.norec
  item.value.dokter = e.pegawaifk ? { label: e.namalengkap, value: e.pegawaifk } : ''
}

const simpanVerify = async () => {

  if (!item.value.dokter.value) {
    H.alert('error', 'Dokter Harus diisi')
    debugger
  }

  btnLoadSimpan.value = true
  let objSave = {
    "kelasfk": item.value.kelasfk,
    "norec_so": item.value.norec_so,
    "norec_pd": item.value.norecpd,
    "dokterfk": item.value.dokter.value,
    "objectruangantujuanfk": item.value.objectruangantujuanfk,
    "objectruanganasalfk": item.value.objectruanganfk,
  }

  await useApi().post('registrasi/get-save-konsul-order', objSave).then((response: any) => {
    fetchOrder()
    btnLoadSimpan.value = false
    modalUpdateDokter.value = false
  }).catch((e: any) => {
    modalUpdateDokter.value = false
    btnLoadSimpan.value = false
  })

}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deletePenunjung(e)

    },
    reject: () => { },
  })
}

const fetchRuangan = async (filter: any) => {
  // const response = await useApi().get(`/dashboard/registrasi/dropdown`)
  // d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  // d_Ruangan.value.forEach((element) => {
  //   item.value.ruangan.value.push(element)
  // })
  const response = await useApi().get(`/dashboard/dropdown-rawat-jalan`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Ruangan.value.forEach((element) => {
    sourceRuangan.value.push(element)
  })
}

const showModalDokter = (e: any) => {
  item.value.norec_apd = e.norec_apd
  modalUpdateDokter.value = true
}

const updateDokter = async (e: any) => {
  let objSave = {
    "norec_apd": item.value.norec_apd,
    "iddokter": e.value
  }
  await useApi().post('registrasi/save-pilih-dokter-konsul', objSave).then((response) => {
    isLoadingBtn.value = false
    modalUpdateDokter.value = false
    fetchData()
  }).catch((e) => {
    isLoadingBtn.value = false
    modalUpdateDokter.value = false
  })

}

const emr = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.norec_pd,
      norec_apd: e.norec_apd,
    },
  })
}
// kasir/billing?nocmfk=3&norec_pasien_daftar=21fbe1d4-7c0e-4714-a823-a4bffc598bf9&noregistrasi=2309000056
const rincianTagihan = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-kasir-billing',
    query: {
      nocmfk: e.nocmfk,
      norec_pasien_daftar: e.norec_pd,
      noregistrasi: e.noregistrasi
    },
  })
}

const fetchDokter = async (filter: any) => {
  let search = ''
  // console.log(filter.query);

  if (filter) {
    search = filter.query
  }
  useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=${search}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
  ).then((response) => {
    // d_Dokter.value = response
    d_Dokter.value = response.map((e: any) => { return { label: e.label, value: e.value, default: e } })
    d_Dokter.value.forEach(element => {
      if (userLogin.pegawai.id == element.value) {
        item.value.filterdokterfk = element
        console.log(element);

      }
    });
  })
}
const fetchDokterUpdate = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_DokterUpdate.value = response
  })
}
const kembaliKeun = () => {
  modalInput.value = false
  input = {
    tanggal: new Date()
  }
}

const klikTab = (e: any) => {
  // activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchOrder()
  }
  if (activeTab.value == 1) {
    fetchData()
  }
}
const cari = () => {
  if (activeTab.value == 0) {
    fetchOrder()
  }
  if (activeTab.value == 1) {
    fetchData()
  }
}
// fetchDokterUpdate()
fetchDokter()
fetchData()
fetchOrder()
fetchRuangan()


watch(currentPage.value, () => {
  fetchOrder()
})



</script>



<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/dashboard/bedah.scss';


.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
  content: "";
  position: absolute;
  top: 94px !important;
  left: 111px;
  height: 100%;
  width: 2px;
  background: var(--placeholder);
  z-index: 0;
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

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  max-width: 30% !important;
}
</style>
