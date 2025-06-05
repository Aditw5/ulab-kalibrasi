<template>
    <section>
        <div class="columns is-multiline">
            <div class="column is-12">
                <!-- <div class="search-widget"> -->
                    <div class="field">
                        <div class="columns is-multiline">
                            <!-- <div class="column is-2">
                                <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                                    :loading="isLoading" class="mr-2">Tambah </VButton>
                            </div> -->
                            <!-- <div class="column">
                                <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField addons>
                                            <VControl icon="feather:calendar">
                                                <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                            </VControl>
                                            <VControl>
                                                <VButton static icon="feather:arrow-right" />
                                            </VControl>
                                            <VControl subcontrol icon="feather:calendar">
                                                <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </div> -->
                            <!-- <div class="column is-6">
                                <div class="control">
                                    <input type="text" v-model="filter" class="input" placeholder="Cari..." />
                                    <button class="searcv-button" type="button" :loading="isLoading" @click="loadRiwayat">
                                        <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                    </button>
                                </div>
                            </div> -->
                        </div>
                    </div>
                <!-- </div> -->
            </div>
            <div class="column is-10">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <VDatePicker class="pt-0 pb-0 pl-0" v-model="input.tanggal" color="green" trim-weeks mode="dateTime">
                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                <VField>
                                    <VLabel class="required-field">Tanggal</VLabel>
                                    <VControl icon="feather:calendar">
                                        <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                            class="is-rounded" :disabled="disabledJawab" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                    <div class="column is-2">
                        <VField class="is-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                            <VLabel class="required-field">Ruang Asal</VLabel>
                            <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                                <Dropdown v-model="input.ruanganasal" :options="d_Ruangan" :optionLabel="'label'"
                                    class="is-rounded" placeholder="Ruang Asal" style="width: 100%;" :filter="true" showClear
                                    :disabled="disabledJawab" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField class="is-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                            <VLabel class="required-field">Ruangan Tujuan</VLabel>
                            <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                                <Dropdown v-model="input.ruangantujuan" :options="d_Ruangan" :optionLabel="'label'"
                                    class="is-rounded" placeholder="Ruang Tujuan" style="width: 100%;" :filter="true"
                                    :disabled="disabledJawab" showClear />
                            </VControl>
                        </VField>
                    </div>
                    <!-- <div class="column is-3">
                        <VField class="is-rounded-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                            <VLabel class="required-field">Kelas</VLabel>
                            <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                                <Dropdown v-model="input.kelas" :options="d_Kelas" :optionLabel="'label'"
                                    class="is-rounded" placeholder="Kelas" style="width: 100%;" :filter="true"
                                    :disabled="disabledJawab" showClear />
                            </VControl>
                        </VField>
                    </div> -->
                    <div class="column is-2">
                        <span style="font-weight: 500;">Kelas</span>
                        <VField class="is-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="input.kelas" :suggestions="d_Kelas" @complete="fetchKelas($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Kelas"
                                    class="is-rounded" />
                            </VControl>
                        </VField>
                    </div>

                    <div class="column is-2">
                        <VField class="is-select is-autocomplete-select
                                mt-0 pt-0" v-slot="{ id }">
                            <VLabel class="mb-0">Dokter/Pegawai Medis </VLabel>
                            <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                                <!-- <Dropdown v-model="input.dokter" :options="d_Dokter" :optionLabel="'label'"
                                    placeholder="Dokter" style="width: 100%;" :filter="true" showClear
                                    :disabled="disabledJawab" /> -->
                                <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchPegawai($event)"
                                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas ..."
                                    class="mt-2 is-rounded" :disabled="disabledJawab" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VLabel>Lain-lain</VLabel>
                            <VControl icon="feather:bookmark">
                                <input v-model="input.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain "
                                    :disabled="disabledJawab" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="input.rawatBersama" label="Rawat Bersama" color="danger"
                                    :disabled="disabledJawab" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField>
                            <VControl>
                                <VSwitchBlock v-model="input.konsultasi" label="Konsultasi" color="danger"
                                    :disabled="disabledJawab" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField>
                            <VLabel>Keterangan</VLabel>
                            <VControl>
                                <VTextarea v-model="input.keterangan" rows="10" placeholder="Keterangan"
                                    :disabled="disabledJawab">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12" v-if="disabledJawab">
                        <VField>
                            <VLabel>Jawaban</VLabel>
                            <VControl>
                                <VTextarea v-model="input.jawaban" rows="6" placeholder="Jawaban">
                                Yth. TS. <br><br><br><br> Salam Sejawat,
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading" @click="simpan()"
                            class="is-pulled-right"> Simpan
                        </VButton>
                    </div>
                </div>
            </div>
            <div class="column is-2">
                <!-- <UIWidget class="search-widget">
                    <template #body>
                    <div class="field">
                        <div class="control">
                        <input v-model="item.search" class="input custom-text-filter" placeholder="Cari Dokter Praktek" v-on:keyup.enter="fetchJadwalDokter"/>
                        <button class="searcv-button" @click="fetchJadwalDokter()">
                            <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                        </button>
                        </div>
                    </div>
                    </template>
                </UIWidget> -->
                <VCard  :loading="isLoading">
                    <div class="column border-custom mb-2 mt-5-min">
                        <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Jadwal Dokter
                        </span>
                    </div>
                    <div class="tile-grid-konsul tile-grid-konsul-v2" v-if="dataDokter.length">
                        <div name="list" tag="div" class="columns is-multiline">
                        <div class="columns is-multiline p-2" style="max-height:390px;overflow: auto;">
                            <div v-for="item in dataDokter" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                            <div class="tile-grid-konsul-item">
                                <div class="tile-grid-konsul-item-inner">
                                <!-- <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered /> -->
                                <div class="meta">
                                    <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{ item.namadokter
                                    }}</span>
                                    <span>
                                    <i aria-hidden="true" class="iconify" data-icon="feather:clock" style="padding-right: 3px;"></i>
                                    {{ item.jadwal }}</span>
                                </div>
                                <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> {{ item.namahari }}
                                </VTag>
                                </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <div class="tile-grid-konsul tile-grid-konsul-v2" v-if="dataDokter.length == 0">
                        <div class="flex-list-inner  text-center">
                            <VPlaceholderSection title="Harap Pilih Ruangan Tujuan Atau Jadwal Dokter Tidak Ada" class="my-6">
                            <template #image>
                                <img class="light-image" :src="'/@src/assets/illustrations/placeholders/search-4-dark.svg'" alt=""
                                style="width: 100px;" />
                                <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                                style="width: 100px;" />
                            </template>
                            </VPlaceholderSection>
                        </div>
                    </div>
                </VCard>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard>
                                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="10"
                                        :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers" filterDisplay="menu"
                                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                        :scrollable="true" :loading="isLoading" dataKey="norec">

                                        <Column :exportable="false" header="#" :style="{ width: '180px' }">
                                            <template #body="slotProps">
                                                <VIconButton type="button" icon="pi pi-pencil" class="mr-2" color="info"
                                                    circle outlined raised v-tooltip.top="'Edit '"
                                                    @click="edit(slotProps.data)">
                                                </VIconButton>
                                                <VIconButton type="button" icon="fas fa-trash" class="mr-2" color="danger"
                                                    circle outlined raised v-tooltip.top="'Hapus '"
                                                    @click="hapus(slotProps.data)" :loading="slotProps.data.loadingHapus">
                                                </VIconButton>
                                                <VIconButton type="button" icon="fas fa-arrow-right" class="mr-2"
                                                    color="success" circle outlined raised v-tooltip.top="'Jawab '"
                                                    @click="jawab(slotProps.data)">
                                                </VIconButton>
                                            </template>
                                        </Column>
                                        <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                                        <Column field="tglorder" header="Tanggal" style="width:150px" :sortable="true">
                                        </Column>
                                        <Column field="namapasien" header="Nama Pasien" style="width:150px" :sortable="true">
                                        </Column>
                                        <Column field="ruanganasal" header="Ruang Asal" style="width:150px"></Column>
                                        <Column field="ruangantujuan" header="Ruang Tujuan" :sortable="true"
                                            style="width:150px"></Column>
                                        <Column field="namalengkap" header="Dokter" style="width:200px"></Column>
                                        <Column field="keteranganorder" header="Keterangan" style="width:300px"></Column>
                                        <Column field="keteranganlainnya" header="Jawaban" style="width:300px"></Column>
                                        <!-- <Column field="statusorder" header="verifikasi" style="width:300px"></Column> -->
                                        <Column field="status_order" header="Status Verifikasi" style="width: 150px">
                                            <template #body="slotProps">
                                                <VTag class="ml-4" :color="slotProps.data.status_order === 'Terverifikasi' ? 'primary' : 'warning'" rounded>
                                                    {{ slotProps.data.status_order }}
                                                </VTag>
                                            </template>
                                        </Column>
                                    </DataTable>
                                </VCard>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!-- <Dialog v-model:visible="modalInput" modal header="Konsultasi" :style="{ width: '60vw' }">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VDatePicker class="pt-0 pb-0 pl-0" v-model="input.tanggal" color="green" trim-weeks mode="dateTime">
                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                            <VField>
                                <VLabel class="required-field">Tanggal</VLabel>
                                <VControl icon="feather:calendar">
                                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                        class="is-rounded" :disabled="disabledJawab" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </div>
                <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Ruang Asal</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.ruanganasal" :options="d_Ruangan" :optionLabel="'label'"
                                class="is-rounded" placeholder="Ruang Asal" style="width: 100%;" :filter="true" showClear
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Ruangan Tujuan</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.ruangantujuan" :options="d_Ruangan" :optionLabel="'label'"
                                class="is-rounded" placeholder="Ruang Tujuan" style="width: 100%;" :filter="true"
                                :disabled="disabledJawab" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">Kelas</VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.kelas" :options="d_Kelas" :optionLabel="'label'"
                                class="is-rounded" placeholder="Kelas" style="width: 100%;" :filter="true"
                                :disabled="disabledJawab" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <span style="font-weight: 500;">Kelas</span>
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VControl icon="feather:search">
                            <AutoComplete v-model="input.kelas" :suggestions="d_Kelas" @complete="fetchKelas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik Nama Kelas"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </div>

                <div class="column is-3">
                    <VField class="is-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="mb-0">Dokter/Pegawai Medis </VLabel>
                        <VControl icon="fas fa-bookmark" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.dokter" :options="d_Dokter" :optionLabel="'label'"
                                placeholder="Dokter" style="width: 100%;" :filter="true" showClear
                                :disabled="disabledJawab" />
                            <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchPegawai($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Petugas ..."
                                class="mt-2 is-rounded" :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top: 20px;">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="input.rawatBersama" label="Rawat Bersama" color="danger"
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-2" style="margin-top: 20px;">
                    <VField>
                        <VControl>
                            <VSwitchBlock v-model="input.konsultasi" label="Konsultasi" color="danger"
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4">
                    <VField>
                        <VLabel>Lain-lain</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="input.lainlain" type="text" class="input is-rounded" placeholder="Lain-lain "
                                :disabled="disabledJawab" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel>Keterangan</VLabel>
                        <VControl>
                            <VTextarea v-model="input.keterangan" rows="10" placeholder="Keterangan"
                                :disabled="disabledJawab">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12" v-if="disabledJawab">
                    <VField>
                        <VLabel>Jawaban</VLabel>
                        <VControl>
                            <VTextarea v-model="input.jawaban" rows="6" placeholder="Jawaban">
                              Yth. TS. <br><br><br><br> Salam Sejawat,
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>
            <template #footer>
                <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                    Batal
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="simpan()"> Simpan
                </VButton>
            </template>
        </Dialog> -->
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
// import { Notification } from '/@src/models/notification'
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
// let NOREC_PD = useRoute().query.norec_pasien_daftar as string

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const emit = defineEmits()
const COLLECTION: any = ref('CatatanPerkembanganPasienTerintegrasi') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
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
useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let dataDokter: any = ref([])
const filePasien: any = ref()
const riwayatCPPT : any = ref()
const selected = ref('all')
const d_JenisDiagnosa: any = ref([])
const d_Diagnosa: any = ref([])
const listDiagnosa: any = ref([])
const listDiagnosa9: any = ref([])
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const input: any = reactive({ tanggal: new Date() })
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const filter: any = ref('')
const dataSource: any = ref([])
const disabledJawab = ref(false)
const dataSourcefiltered = computed(() => {
    if (!filter.value) {
        return dataSource.value
    }
    return dataSource.value.filter((items: any) => {
        return (
            // items.ruanganasal.match(new RegExp(filter.value, 'i')),
            items.ruangantujuan.match(new RegExp(filter.value, 'i'))
            // items.namalengkap.match(new RegExp(filter.value, 'i'))
        )
    })
})

const loadDrop = async () => {
    d_Ruangan.value = await useApi().get(`emr/dropdown/ruangan_m?select=id,namaruangan,kdspesialisbpjs&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix,kdDepartemenRanapFix`)
    d_Kelas.value = await useApi().get(`emr/dropdown/kelas_m?select=id,namakelas`)
}

const fetchPegawai = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const fetchJadwalDokter = async () => {
  if (!input.ruangantujuan) {
    useToaster().error('Harap Pilih Ruangan Tujuan terlebih dahulu !')
    return
  }

  const tgl = H.formatDate(input.tanggal, 'YYYY-MM-DD')

  dataDokter.value = []
  isLoading.value = true

  var json = {
    url: `jadwaldokter/kodepoli/${input.ruangantujuan.kdspesialisbpjs}/tanggal/${tgl}`,
    method: 'GET',
    jenis: 'antrean',
    data: null,
  }
  await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
      if (response.metaData.code == 200 && response.response) {
        for (let x = 0; x < response.response.length; x++) {
          const element = response.response[x]
          element.no = x + 1
        }
        dataDokter.value = response.response
      }
      isLoading.value = false
    })
    .catch((err) => {
      console.log(err)
    })
}

// const fetchJadwalDokter = async () => {
//   let namaDokter = ''
//   let search = item.value.search ? `&search=${item.value.search}` : ''
//   let tanggal = `?tanggal=${H.formatDate(input.tanggal, 'Y-M-D')}`
//   dataDokter.value = []

//   const response = await useApi().get(
//     `/dashboard/jadwal-dokter${tanggal}${search}`
//   )
//   dataDokter.value = response.dokter
//   console.log(response)
// }

const loadRiwayat = () => {
    isLoading.value = true
    let dari = `&tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`
    let sampai = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`
    useApi().get(
        `/emr/get-order-konsul?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}${dari}${sampai}`).then((response: any) => {
            isLoading.value = false
            for (let x = 0; x < response.data.length; x++) {
                const element = response.data[x];
                element.no = x + 1
                element.loadingHapus = false
            }
            dataSource.value = response.data
        })
}

// const add = async () => {
//     disabledJawab.value = false
//     input.tanggal = new Date()
//     await loadDrop()
//     d_Ruangan.value.forEach((element) => {
//         if (element.value == props.registrasi.objectruanganlastfk) {
//             input.ruanganasal = element
//         }
//     });
//     await fetchKelas({ query: 'NON KELAS' })
//     if (d_Kelas.value.length) {
//         input.kelas = d_Kelas.value[0]
//     }

//     input.keterangan = 'Kepada Yth.\n\n\n\n\n\n\n\n\nSalam Sejawat, Terimakasih'


//     // modalInput.value = true
// }

const add = async () => {
    disabledJawab.value = false;
    input.tanggal = new Date();
    await loadDrop();

    d_Ruangan.value.forEach((element) => {
        if (element.value == props.registrasi.objectruanganlastfk) {
            input.ruanganasal = element;
        }
    });

    await fetchKelas({ query: 'NON KELAS' });
    if (d_Kelas.value.length) {
        input.kelas = d_Kelas.value[0];
    }

    let diagnosisICD10 = '';
    let diagnosisICD9 = '';

    // Menggunakan Set untuk menghindari duplikasi diagnosa
    const uniqueICD10 = new Set();
    const uniqueICD9 = new Set();

    // Check if input.value.details exists and contains data
    if (input.value && input.value.details) {
        let hasICD10 = false;
        let hasICD9 = false;

        input.value.details.forEach((element) => {
            // Handle multiple diagnosaDokter (ICD 10)
            if (element.diagnosaDokter && element.diagnosaDokter.length > 0) {
                if (!hasICD10) {
                    diagnosisICD10 = 'Diagnosis ICD 10\n';
                    hasICD10 = true;
                }
                element.diagnosaDokter.forEach((diagnosa) => {
                    const diagnosaText = `
                    Jenis Diagnosa: ${diagnosa.jenisDiagnosa?.label || 'Tidak ada data'}
                    Nama Diagnosa: ${diagnosa.keterangan || 'Tidak ada data'}
                    `;
                    uniqueICD10.add(diagnosaText); // Tambahkan diagnosa ke Set untuk menghindari duplikasi
                });
            }

            // Handle multiple diagnosaDokter9 (ICD 9)
            if (element.diagnosaDokter9 && element.diagnosaDokter9.length > 0) {
                if (!hasICD9) {
                    diagnosisICD9 = 'Diagnosis ICD 9\n';
                    hasICD9 = true;
                }
                element.diagnosaDokter9.forEach((diagnosa) => {
                    const diagnosaText = `
                    Jenis Diagnosa: ${diagnosa.jenisDiagnosa?.label || 'Tidak ada data'}
                    Nama Diagnosa: ${diagnosa.keterangan || 'Tidak ada data'}
                    `;
                    uniqueICD9.add(diagnosaText); // Tambahkan diagnosa ke Set untuk menghindari duplikasi
                });
            }
        });
    }

    // Gabungkan diagnosa unik dari Set ke dalam string
    diagnosisICD10 += Array.from(uniqueICD10).join('\n');
    diagnosisICD9 += Array.from(uniqueICD9).join('\n');

    // Update input.keterangan to include diagnosis information
    input.keterangan = `
    Kepada Yth.\n
    ${diagnosisICD10 || 'Tidak ada diagnosis ICD 10 yang tersedia'}\n
    ${diagnosisICD9 || 'Tidak ada diagnosis ICD 9 yang tersedia'}\n
    \nSalam Sejawat, Terimakasih
    `;
};

const fetchJenisDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/jenisdiagnosa_m?select=id,jenisdiagnosa&param_search=jenisdiagnosa&query=${filter.query ? filter.query : ''}&limit=10`)
  d_JenisDiagnosa.value = response

}

const fetchDiagnosaX = async () => {
  await useApi().get(
    "diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
  ).then((response) => {
    listDiagnosa.value = response.data
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      element.diagnosaDokter = []
      if (!response.data || response.data.length === 0 || response.data === null || response.data === '' || (Array.isArray(response.data) && response.data.length === 0)) {
        element.diagnosaDokter.push({
          no: 1,
          jenisDiagnosa: { label: d_JenisDiagnosa.value[0].label, value: d_JenisDiagnosa.value[0].value }
        });
      } else {
        for (let z = 0; z < response.data.length; z++) {
          const element2 = response.data[z];
          element.diagnosaDokter.push({
            no: z + 1,
            keterangan: element2.keterangan,
            norecDiagnosa: element2.norec,
            jenisDiagnosa: element2.jenisdiagnosa && element2.objectjenisdiagnosafk ? { label: element2.jenisdiagnosa, value: element2.objectjenisdiagnosafk } : z == 0 ? { label: d_JenisDiagnosa.value[0].label, value: d_JenisDiagnosa.value[0].value } : { label: d_JenisDiagnosa.value[1].label, value: d_JenisDiagnosa.value[1].value },
            diagnosaa: element2.namadiagnosa && element2.id ? { label: element2.kddiagnosa + "-" + element2.namadiagnosa, value: element2.id } : '',
            isLoadBtnDiagnosaDokter: false,
          });
        }
      }
    }
  })
}

const fetchDiagnosaIX = async () => {
  await useApi().get(
    "/diagnosa/riwayat-diagnosa-ix-cppt?noregistrasi=" + props.registrasi.noregistrasi
  ).then((response) => {
    listDiagnosa9.value = response.data
    for (let x = 0; x < input.value.details.length; x++) {
      const element = input.value.details[x];
      if (response.data.length > 0) {
        element.diagnosaDokter9 = []
        for (let z = 0; z < response.data.length; z++) {
          const element2 = response.data[z];
          element.diagnosaDokter9.push({
            no: z + 1,
            keterangan: element2.keterangantindakan,
            norecDiagnosa9: element2.norec,
            diagnosaa: element2.namadiagnosatindakan && element2.id ? { label: element2.kddiagnosatindakan + "-" + element2.namadiagnosatindakan, value: element2.id } : '',
          })
        }
      }
    }
  })
}

const loadRiwayatCPPT = async () => {
  useApi().get(
    `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length > 0) {
        let perawat = []
        let dokter = []
        response[0].details.forEach(element => {
          if (element.flag == 'perawat') {
            perawat.push(element)
            if (!element.tenagaMedis) {
              element.tenagaMedis = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
            }
          }
          if (element.flag == 'dokter') {
            if (element.diagnosaDokter.length === 0) {
              element.diagnosaDokter.push({
                no: 1,
              })
            }
            if (!element.tenagaMedis) {
              if (useUserSession().getUser().kelompokUser.kelompokUser == 'dokter') {
                element.tenagaMedis = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
              }
            }
            // element.tenagaMedis = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
            dokter.push(element)
          }
          element.dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
        });
        for (let x = 0; x < response[0].details.length; x++) {
          const element = response[0].details[x];
          element.tgl = H.formatDate(new Date(element.tgl), 'YYYY-MM-DD HH:mm')
          element.tglVerifikasi = H.formatDate(new Date(element.tglVerifikasi), 'YYYY-MM-DD HH:mm')

          if (element.flag == 'perawat') {
            dokter.forEach(item => {
              if (item.no == element.no) {
                if (!item.S) {
                  item.S = element.S
                }
                if (!item.O) {
                  item.O = element.O
                }
                if (!item.P) {
                  item.P = element.P
                }
              }
            });
            if (element.tujuanKep == undefined || element.tujuanKep.length == 0) {
              element.tujuanKep = [{
                no: 1
              }]
            }
            if (element.diagnosaKep == undefined || element.diagnosaKep.length == 0) {
              element.diagnosaKep = [{
                no: 1
              }]
            }
          }
        }
        input.value = response[0]
        input.value.dpjpUtama = props.registrasi.dokter
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
  fetchJenisDiagnosa("")
  fetchDiagnosaX()
  fetchDiagnosaIX()
}


const onSelect = async (filez: any) => {

    const file = filez
        .files[0];
    if (file.size > 1000000) {
        H.alert('error', 'Maksimal file size adalah 1 MB')
        return
    }

    if (file.type != "application/pdf" && (file.type.indexOf('image/') > -1) == false) {
        H.alert('error', 'File yang diizinkan dalam bentuk format PDF/Image')
        return;
    }
    filePasien.value = file


}
const onUpload = () => {

}
const kembaliKeun = () => {
    modalInput.value = false
    input = {
        tanggal: new Date()
    }
}
const simpan = async () => {
    if (!input.tanggal) {
        H.alert('error', 'Tanggal harus di isi')
        return
    }
    if (!input.ruanganasal) {
        H.alert('error', 'Ruang Asal harus di isi')
        return
    }
    if (!input.ruangantujuan) {
        H.alert('error', 'Ruang Tujuan harus di isi')
        return
    }
    // if (!input.dokter) {
    //     H.alert('error', 'Dokter harus di isi')
    //     return
    // }
    if (!input.kelas) {
        H.alert('error', 'Kelas Konsultasi Harus di isi')
        return
    }
    let formData = {
        'norec_so': input.norec != undefined ? input.norec : '',
        'norec_pd': props.registrasi.norec_pd,
        'nocmfk': props.pasien.nocmfk,
        'pegawaifk': input.dokter ? input.dokter.value : null,
        'objectruanganasalfk': input.ruanganasal.value,
        'objectruangantujuanfk': input.ruangantujuan.value,
        'keterangan': input.keterangan ? input.keterangan : '',
        'tanggalKonsul': H.formatDate(input.tanggal, 'YYYY-MM-DD HH:mm'),
        'rawatbersama': input.rawatBersama ? input.rawatBersama : null,
        'konsultasi': input.konsultasi ? input.konsultasi : null,
        'lainlain': input.lainlain ? input.lainlain : null,
        'ruangantujuan': input.ruangantujuan.label,
        'objectkelasfk': input.kelas.value,
        'noregistrasi': props.registrasi.noregistrasi,
        'nocm': props.pasien.nocm,
        'namapasien': props.pasien.namapasien,
    }
    isLoading.value = true
    if (disabledJawab.value == true) {
        formData = {
            'norec_so': input.norec,
            'jawaban': input.jawaban,
        }

        await useApi().post('/emr/jawab-order-konsul', formData).then((r) => {
            isLoading.value = false
            loadRiwayat()
            modalInput.value = false
            input.keterangan = 'Kepada Yth.\n\n\n\n\n\n\n\n\nSalam Sejawat, Terimakasih'
            input.konsultasi = false
            input.rawatBersama = false
            input.dokter = ""
            disabledJawab.value = false
            input.ruangantujuan = ""
        }).catch((e: any) => {
            isLoading.value = false
        })
        return
    }


    await useApi().post('/emr/simpan-order-konsul', formData).then((r) => {
        isLoading.value = false
        sendNotification(r)
        loadRiwayat()
        modalInput.value = false
        input.keterangan = 'Kepada Yth.\n\n\n\n\n\n\n\n\nSalam Sejawat, Terimakasih'
        input.konsultasi = false
        input.rawatBersama = false
        input.dokter = ""
        disabledJawab.value = false
        input.ruangantujuan = ""
    }).catch((e: any) => {
        isLoading.value = false
    })

}
const edit = async (e: any) => {
    disabledJawab.value = false
    input.norec = e.norec
    input.tanggal = new Date(e.tglorder)
    d_Ruangan.value.forEach((element: any) => {
        if (element.value == e.objectruanganfk) {
            input.ruanganasal = element
        }
        if (element.value == e.objectruangantujuanfk) {
            input.ruangantujuan = element
        }
    });

    d_Dokter.value.forEach((element: any) => {
        if (element.value == e.pegawaifk) {
            input.dokter = element
        }
    });
    input.konsultasi = e.konsultasi ? e.konsultasi : false
    input.lainlain = e.lainlain
    input.rawatBersama = e.rawatbersama ? e.rawatbersama : false
    input.keterangan = e.keteranganorder
    input.jawaban = e.keteranganlainnya

    // modalInput.value = true
}
const jawab = async (e: any) => {
    edit(e)
    input.jawaban = 'Yth.TS\n\n\n\n\nSalam Sejawat, Terimakasih'
    disabledJawab.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true

    await useApi().post(
        `/emr/hapus-order-konsul`, {
        'norec': e.norec,
        'noorder': e.noorder,
        'noregistrasi': props.registrasi.noregistrasi,
        'ruangantujuan': e.ruangantujuan,
        'nocm': props.pasien.nocm,
        'namapasien': props.pasien.namapasien,
    }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayat()
    })
}
const fetchKelas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Kelas.value = response
    })
}

const sendNotification = (e) => {
    let ruanganAsal = ''
    let ruanganTujuan = ''
    d_Ruangan.value.forEach((element: any) => {
        if (element.value == input.ruanganasal.value) {
            ruanganAsal = element.label
        }
        if (element.value == input.ruangantujuan.value) {
            ruanganTujuan = element.label
        }
    });

    let body = {
        norec: e.data.norec,
        judul: 'Order Konsul #' + props.pasien.namapasien,
        jenis: 'Konsultasi',
        pesanNotifikasi: `Konsultasi ke ${ruanganTujuan} dan Dokter ${input.dokter.label}`,
        idRuanganAsal: input.ruanganasal.value,
        idRuanganTujuan: input.ruangantujuan.value,
        ruanganAsal: ruanganAsal,
        ruanganTujuan: ruanganTujuan,
        kelompokUser: null,
        idKelompokUser: null,
        idPegawai: input.dokter.value,//H.pegawaiLogin().id,
        namapegawai: input.dokter.label,//H.pegawaiLogin().id,
        dataArray: [],
        urlForm: 'module-registrasi-daftar-konsultasi',
        params: null,
        group: 'pegawai',
        namaFungsiFrontEnd: null,
        tgl: e.data.tglorder,
        tgl_string: H.formatDateIndoSimple(e.data.tglorder),
    }
    H.sendSocket("sendNotification", body);
}


watch(
  () => input.tanggal,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
        fetchJadwalDokter()
    }
  }
)

watch(
  () => input.ruangantujuan,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
        fetchJadwalDokter()
    }
  }
)

watch(
  () => selected.value,
  (newValue, oldValue) => {

    if (newValue != oldValue) {
        loadRiwayatCPPT()
    }
  }
)

onMounted(() => {

});

add()
// fetchJadwalDokter()
loadRiwayat()
loadRiwayatCPPT()
// fetchDiagnosaX()
// fetchDiagnosaIX()
</script>
<style  lang="scss">
@import '/@src/scss/abstracts/all';

.tile-grid-konsul {
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
    .tile-grid-konsul {
        .tile-grid-konsul-item {
            @include vuero-card--dark;
        }
    }
}

.tile-grid-konsul-v1 {
    .tile-grid-konsul-item {
        @include vuero-s-card;

        border-radius: 14px;
        padding: 16px;

        .tile-grid-konsul-item-inner {
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
</style>
