<template>
 
  <div >
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>
        <div v-if="!isStuck" class="form-header stuck-header">
          <div class="form-header-inner">
            <div class="left">
              <h3> {{ props.FORM_NAME }}</h3>
            </div>
            <div class="right">
              <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                @kembaliKeun="kembaliKeun"></ButtonEmr>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="column">
      <div class="columns is-multiline">
        <div class="column is-2">
          <span class="label-icu">No RM</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.norm" />
            </VControl>
          </VField>
        </div>
        <div class="column is-4">
          <span class="label-icu">Nama Pasien</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <span class="label-icu">Tanggal</span>
          <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField class="pb-0 pt-3">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column">
          <span class="label-icu">Petugas</span>
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" />
            </VControl>
          </VField>
        </div>

      </div>
    </div>

    <div class="column">
      <VTabs slider selected="data" :tabs="[
        { label: 'Data', value: 'data' },
        { label: 'Chart', value: 'chart' },
      ]">
        <template #tab="{ activeValue }">

          <p v-if="activeValue === 'data'">
            <DataTable :value="description" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
              editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
              tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

              <ColumnGroup type="header">
                <Row>
                  <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="2" />
                  <Column header="Waktu" style="min-width: 300px;" class="font-bold" :colspan="waktu.length" />
                </Row>
                <Row>
                  <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                    :rowEditor="true">
                  </Column>
                </Row>
              </ColumnGroup>

              <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />
              <Column style="width: 25%" v-for="(data, i) in waktu" class="font-bold" :field="data">
                <template #body="slotProps">
                  {{ slotProps.data[data] }}
                </template>
                <template #editor="{ data, field }">
                  <InputText v-model="data[field]" autofocus />
                </template>
              </Column>
              <template #groupheader="slotProps">
                <div class="font-bold">
                  <span class="label-icu">{{ slotProps.data.group }}</span>
                </div>
              </template>
            </DataTable>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">III. Status Respirasi</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline p-3 mt-1">
              <div class="column is-3" v-for="(data) in statusRespirasi">
                <span class="label-icu">{{ data.label }}</span>
                <VField class="pt-3" v-if="data.type == 'textBox'">
                  <VControl>
                    <VInput type="text" class="input" v-model="input[data.model]" />
                  </VControl>
                </VField>
                <VField v-else>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input[data.model]" :suggestions="d_AlatBantu" @complete="fetchAlatBantu()"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">IV. Hemodinamik</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline p-3 mt-1">
              <div class="column is-3" v-for="(data) in hemodinamik">
                <span class="label-icu">{{ data.label }}</span>
                <VField class="pt-3" v-if="data.type == 'textBox'">
                  <VControl>
                    <VInput type="text" class="input" v-model="input[data.model]" />
                  </VControl>
                </VField>
                <VField v-else>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input[data.model]" :suggestions="d_CRT" @complete="fetchCRT()"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" class="mt-2" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">V. Medikasi</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="addNewItem"> Button </VButton>

            <div class="column pl-0 pr-0">
              <DataTable :value="medikasi" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                tableStyle="min-width: 50rem">

                <ColumnGroup type="header">
                  <Row>
                    <Column header="Nama Obat" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Dosis" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Action" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                      :colspan="listDescripWaktu.ketWaktu.length" />
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                      :colspan="2">
                    </Column>
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in listDescripWaktu.ketWaktu"
                      class="font-bold">
                    </Column>
                  </Row>
                </ColumnGroup>

                <Column field="namaObat" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                  <template #body="slotProps">
                    {{ slotProps.data.namaObat }}
                  </template>
                  <template #editor="{ data, field }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
                <Column field="dosis" class="font-bold" style="min-width: 150px; text-align: center;" frozen>
                  <template #body="slotProps">
                    {{ slotProps.data.dosis }}
                  </template>
                  <template #editor="{ data, field }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
                <Column field="action" style="min-width: 150px; text-align: center;" frozen>
                  <template #body="slotProps">
                    <VIconButton class="mt-1" v-if="slotProps.data" type="button" raised circle icon="feather:trash"
                      @click="removeItem(slotProps.index)" color="danger">
                    </VIconButton>
                  </template>
                </Column>
                <Column style="width: 25%" v-for="(data, i) in listDescripWaktu.ketWaktu" class="font-bold"
                  :field="data + '_' + i">
                  <template #body="slotProps">
                    {{ slotProps.data[data + '_' + i] }}
                  </template>
                  <template #editor="{ data, field, }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
              </DataTable>
            </div>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">VI. Balans Cairan</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="column pl-0 pr-0">
              <DataTable :value="tableBalansCairan" scrollable scrollHeight="500px" class="p-datatable-sm" showGridlines
                editMode="cell" @cell-edit-complete="onCellEditComplete" tableClass="editable-cells-table"
                tableStyle="min-width: 50rem" groupRowsBy="group" rowGroupMode="subheader">

                <ColumnGroup type="header">
                  <Row>
                    <Column header="Deskripsi" style="min-width: 150px; text-align: center;" frozen :rowspan="3" />
                    <Column header="Waktu" style="min-width: 300px;" class="font-bold"
                      :colspan="listDescripWaktu.descWaktu.length" />
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in waktu" class="font-bold"
                      :colspan="2">
                    </Column>
                  </Row>
                  <Row>
                    <Column :header="data" style="min-width: 100px;" v-for="(data) in listDescripWaktu.descWaktu"
                      class="font-bold" :rowEditor="true">
                    </Column>
                  </Row>
                </ColumnGroup>

                <Column field="deskripsi" style="min-width: 150px; text-align: center;" frozen />

                <Column style="width: 25%" v-for="(data, i) in listDescripWaktu.descWaktu" class="font-bold"
                  :field="data + '_' + i">
                  <template #body="slotProps">
                    {{ slotProps.data[data + '_' + i] }}
                  </template>
                  <template #editor="{ data, field, }">
                    <InputText v-model="data[field]" autofocus />
                  </template>
                </Column>
                <template #groupheader="slotProps">
                  <div class="font-bold">
                    <span class="label-icu">{{ slotProps.data.group }}</span>
                  </div>
                </template>
              </DataTable>
            </div>
          </div>

          <div class="column p-0 mb-5">
            <div class="columns is-multiline">
              <div class="column is-10">
                <span class="label-icu">VII. Data Laboratorium</span>
                <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
                <VField class="pt-3">
                  <VControl>
                    <VTextarea v-model="input.dataLaboratorium" rows="2">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
              <div class="column" style="align-self: center;">
                <VField>
                  <VLabel>Copy Hasil Laboratorium</VLabel>
                  <VControl>
                    <VIconButton style="margin-left: 30%" type="button" raised circle icon="fas fa-bong" v-tooltip-prime.bottom="'Terapkan Data'"
                      @click="show" color="warning">
                    </VIconButton>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column p-0 mb-5">
            <span class="label-icu">VIII. Catatan Penting</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input.catatanPenting" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <span class="label-icu">VI. Balans Cairan</span>
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <VButton color="primary" raised icon="fas fa-plus" class="mt-3" @click="showFormPemasanganAlat"> Button
            </VButton>
            <DataTable :value="dataSourceAlatInvasif" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
              :loading="isLoading" class="p-datatable-sm mt-4"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
              <Column field="no" header="No" class="font-bold" style="text-align: center"></Column>
              <Column field="alatInvasif" header="Pemasangan Alat Invasif" class="font-bold"></Column>
              <Column header="Pasang" :sortable="true">
                <template #body="slotProps">
                  <span class="label-icu">{{ H.formatDate(slotProps.data.tglPasang, 'DD-MM-YYYY') }}</span>
                </template>
              </Column>
              <Column header="Cabut" :sortable="true">
                <template #body="slotProps">
                  <span class="label-icu">{{ H.formatDate(slotProps.data.tglCabut, 'DD-MM-YYYY') }}</span>
                </template>
              </Column>
              <Column field="hari" header="Hari Ke" :sortable="true" style="text-align: center;" class="font-bold" />
              <Column :exportable="false" header="Action" style="text-align: center">
                <template #body="slotProps">
                  <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined raised
                    v-tooltip.top="'Edit'" @click="editItemAlat(slotProps.data)">
                  </VIconButton>
                  <VIconButton type="button" icon="fas fa-trash" color="danger" circle outlined raised
                    v-tooltip.top="'Hapus'" @click="deleteItemAlat(slotProps.index)">
                  </VIconButton>
                </template>
              </Column>
            </DataTable>
          </div>

          <div class="column pl-0 pr-0 mt-5">
            <hr class="mt-2 ml-0 mr-0 mb-0" style="border-top-style: dashed;border-color: var(--fade-grey-dark-2);">
            <div class="columns is-multiline pt-5">
              <div class="column is-4">
                <span class="label-icu">Paraf Petugas Pagi</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugasPagi" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <span class="label-icu">Paraf Petugas Siang</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugasSiang" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-4">
                <span class="label-icu">Paraf Petugas Malam</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.petugasMalam" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          </p>



          <p v-else-if="activeValue === 'chart'">


          </p>

        </template>
      </VTabs>
    </div>
  </div>

  <VModal is="form" :open="formTambahAlat" title="Form " size="small" actions="right" @close="formTambahAlat = false">
    <template #content>
      <div class="modal-form">
        <div class="field">
          <label>Nama Alat</label>
          <div class="control">
            <input type="text" class="input" placeholder="Nama Alat" v-model="item.namaAlat" />
          </div>
        </div>
        <div class="field">
          <label>Pasang</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.pasang" color="green" trim-weeks mode="date" :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Cabut</label>
          <div class="control">
            <VDatePicker class="pt-3" v-model="item.cabut" color="green" trim-weeks mode="date" :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="field">
          <label>Hari Ke</label>
          <div class="control">
            <input type="text" class="input" v-model="item.hariKe" />
          </div>
        </div>
      </div>
    </template>
    <template #action>
      <VButton type="submit" color="primary" raised @click="addtoSourceAlat(item)">Simpan</VButton>
    </template>
  </VModal>
  <Dialog v-model:visible="showData" maximizable modal header="Hasil Laboratorium" :style="{ width: '80rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VCard class="bt-color">
              <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.sourcePemeriksaanPenunjang" rows="20">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VCard class="pembungkus">
              <DataTable dataKey="no" :value="sourceDataLab" v-model:selection="selectedRad" class="p-datatable-sm"
                :paginator="true" :rows="10" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="no" header="No" :sortable="true" style="min-width: 50px"></Column>
                <Column field="nama_pemeriksaan" header="Layanan" frozen :sortable="true" style="min-width: 200px">
                </Column>
                <Column field="hasil" header="Hasil" :sortable="true" style="min-width: 100px"></Column>
                <Column field="normal" header="Nilai Normal" :sortable="true" style="min-width: 100px"></Column>
                <!-- <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column> -->
                <Column field="tgl_hasil" header="tanggal" :sortable="true" style="min-width: 200px"></Column>
              </DataTable>

              <div class="column is-12">
                <VButton color="primary" raised @click="tambahHasilLab(selectedRad)" outlined :loading="isLoadTmb"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
              </div>
            </VCard>
          </div>
        </div>
        <template #footer>
          <VButton color="primary" raised @click="simpanHasilPenunjang(item.sourcePemeriksaanPenunjang)"><i
              class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton>
        </template>
    </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import Fieldset from 'primevue/fieldset';
import * as ICU from '../page-emr-plugins/monitoring-icu'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import InputText from 'primevue/inputtext';
import { alatBantu } from '../page-emr-plugins/asesmen-awal-keperawatan-rawat-inap'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let waktu = ref(ICU.waktu())
let listDescripWaktu = ref(ICU.listDescripWaktu())
let description = ref(ICU.dataTableDescrip())
let statusRespirasi = ref(ICU.statusRespirasi())
let hemodinamik = ref(ICU.Hemodinamik())
let tableBalansCairan = ref(ICU.tableBalansCairan())
let titleMore = ref(ICU.titleMore())
let hasilPenunjangs: any = ref([])

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    FORM_NAME?: string
    FORM_URL?: string
    COLLECTION?: string
  }>(),
  {
    pasien: {},
    registrasi: {},
    FORM_NAME: '',
    FORM_URL: '',
    COLLECTION: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isLoadingHolder: any = ref(false)
const medikasi = ref([]);
const selectedRad = ref();
const showData: any = ref(false)
const sourceDataLab = ref([])
const isLoadTmb: any = ref(false)
const d_Obat: any = ref([])
const d_AlatBantu: any = ref([])
const d_CRT: any = ref([])
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const formTambahAlat: any = ref(false)
const showFormInput: any = ref(false)
const dataSourceAlatInvasif: any = ref([])
const item: any = reactive({})
const filter: any = ref('')
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggal: new Date(),
})

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const filteredList = computed(() => {
  if (!filter.value) {
    // return listVital.value
  }

  return listVital.value.filter((items: any) => {
    return (
      items.user_input.namalengkap.match(new RegExp(filter.value, 'i'))
    )
  })
})

const show = () => {
  showData.value = true
}

const SourceHasilLab = async () => {
  await useApi().get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    sourceDataLab.value = response
  })
}

const simpanHasilPenunjang = (e: any) => {
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  if (!input.value.dataLaboratorium) {
    input.value.dataLaboratorium = ''
  }
  if (input.value.dataLaboratorium !== '') {
    input.value.dataLaboratorium += '\n'
  }

  input.value.dataLaboratorium += e
  showData.value = false
}

const tambahHasilLab = async (e: any) => {
  let noorder = []
  let nourut = []
  let sementara = []
  item.sourcePemeriksaanPenunjang = ''
  isLoadTmb.value = true
  e.forEach((element: any) => {
    nourut = [...new Set([...nourut, element.no_urut])]
    noorder = [...new Set([...noorder, element.no_order])]
  });
  await useApi().get(`/laboratorium/hasil-lab?noregistrasi=${props.registrasi.noregistrasi}&nourut=${nourut}&noorder=${noorder}`).then((response) => {
    response.forEach(element => {
      sementara = [...new Set([...sementara, element.hasillab])]
    });
    sementara.forEach(element => {
      item.sourcePemeriksaanPenunjang += element
    });
    isLoadTmb.value = false
  })

  hasilPenunjangs.value.forEach(element => {
    item.sourcePemeriksaanPenunjang += element
  });
}

const add = () => {
  showFormInput.value = true
}

const loadRiwayat = () => {
  isLoadingHolder.value = true
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=MonitoringICU&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        description.value = response[0].monitoringICU
        medikasi.value = response[0].medikasi
        dataSourceAlatInvasif.value = response[0].pemasanganAlat
        tableBalansCairan.value = response[0].balansCairan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
  isLoadingHolder.value = false
}

const onCellEditComplete = (event: any) => {
  let { data, newValue, field, newData } = event;
  console.log(event)
  if (newValue != undefined) {
    data[field] = newValue
    // console.log(event)
  }

}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.monitoringICU = description.value
  object.medikasi = medikasi.value
  object.balansCairan = tableBalansCairan.value
  object.pemasanganAlat = dataSourceAlatInvasif.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': "MonitoringICU",
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const addNewItem = () => {
  medikasi.value.push({
    namaObat: "",
    dosis: "",
    no: medikasi.value.length + 1,
  });
}

const removeItem = (index: any) => {
  medikasi.value.splice(index, 1)
}

const addtoSourceAlat = (e: any) => {
  if (!e.namaAlat) {
    return H.alert('error', 'Nama Alat Harus diisi')
  }
  if (!e.pasang) {
    return H.alert('error', 'Tanggal Pasang Harus diisi')
  }
  if (!e.cabut) {
    return H.alert('error', 'Tanggal Cabut Harus diisi')
  }
  if (!e.hariKe) {
    return H.alert('error', 'Hari Ke Harus diisi')
  }
  if (e.no) {
    dataSourceAlatInvasif.value.forEach((element: any) => {
      if (e.no == element.no) {
        element.alatInvasif = e.namaAlat
        element.tglPasang = e.pasang
        element.tglCabut = e.cabut
        element.hari = e.hariKe
      }
    });
  } else {
    dataSourceAlatInvasif.value.push({
      no: dataSourceAlatInvasif.value.length + 1,
      alatInvasif: e.namaAlat,
      tglPasang: e.pasang,
      tglCabut: e.cabut,
      hari: e.hariKe
    })
  }
  formTambahAlat.value = false
  clear()
}

const editItemAlat = (e: any) => {
  showFormPemasanganAlat(e)
  console.log(e)
}

const deleteItemAlat = (i: any) => {
  dataSourceAlatInvasif.value.splice(i, 1)
}

const clear = () => {
  delete item.namaAlat
  delete item.tglPasang
  delete item.tglCabut
  delete item.hariKe
}

const fetchAlatBantu = () => {
  d_AlatBantu.value = [{ label: 'NC' }, { label: 'SM' }, { label: 'RM' }, { label: 'NRM' }, { label: 'HFNC' }, { label: 'Ventilator' }, { label: 'NGT' }]
}

const fetchCRT = () => {
  d_CRT.value = [{ label: "< 3" }, { label: "> 3" }]
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.norm = props.pasien.nocm
  input.value.namaPasien = props.pasien.namapasien
}

const showFormPemasanganAlat = (e: any) => {
  item.no = e.no ? e.no : ''
  item.namaAlat = e.alatInvasif ? e.alatInvasif : '',
    item.pasang = e.tglPasang ? e.tglPasang : '',
    item.cabut = e.tglCabut ? e.tglCabut : '',
    item.hariKe = e.hari ? e.hari : '',
    formTambahAlat.value = true
}

setView()
SourceHasilLab()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
// @import '/@src/scss/custom/timeline-css';
// @import '/@src/scss/custom/timeline-css';

// .p-column-header-content {
//   justify-content: space-evenly;
// }

// .p-fieldset-legend {
//   margin-left: 15px;
// }

// .p-rowgroup-header {
//   background: beige !important;
// }

.label-icu {
  font-weight: 500;
}

.list-widget {
    @include vuero-l-card;

    &.is-straight {
        @include vuero-s-card;
    }

    ul {
        li {
            &:not(:last-child) {
                margin-bottom: 12px;
            }

            a {
                font-family: var(--font);
                display: flex;
                justify-content: space-between;
                color: var(--light-text);

                &:hover,
                &:focus {
                    color: var(--primary);
                }
            }
        }
    }
}


.widget-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;

    .left {
        display: flex;
        align-items: center;
    }

    .center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .right {
        display: flex;
        align-items: center;
        justify-content: flex-end;

        .tag {
            font-family: var(--font);
        }

        .right-icon {
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 32px;
            width: 32px;
            min-width: 32px;
            border-radius: var(--radius-rounded);
            color: var(--light-text-light-12);
            transition: all 0.3s; // transition-all test

            &.has-indicator {
                &::after {
                    content: '';
                    position: absolute;
                    top: 3px;
                    right: 4px;
                    height: 10px;
                    width: 10px;
                    border-radius: var(--radius-rounded);
                    background: var(--secondary);
                    border: 1.8px solid var(--white);
                }
            }

            svg {
                height: 18px;
                width: 18px;
                transition: stroke 0.3s;
            }
        }
    }

    h3 {
        font-family: var(--font-alt);
        font-size: 0.9rem;
        color: var(--dark-text);
        font-weight: 600;

        &.is-bigger {
            font-size: 1rem;
        }
    }

    .action-icon {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 32px;
        width: 32px;
        min-width: 32px;
        border-radius: var(--radius-rounded);
        color: var(--light-text-light-12);
        transition: all 0.3s; // transition-all test

        svg {
            height: 18px;
            width: 18px;
            transition: stroke 0.3s;
        }
    }
}

.is-dark {
    .widget-toolbar {
        h3 {
            color: var(--dark-dark-text);
        }

        .right {
            .right-icon {
                &.has-indicator {
                    &::after {
                        border-color: var(--dark-sidebar-light-6);
                    }
                }
            }
        }
    }
}

small.is-tanggal {
    color: var(--light-text);
}

.is-dark-text {
    color: var(--light-text);
}
</style>
