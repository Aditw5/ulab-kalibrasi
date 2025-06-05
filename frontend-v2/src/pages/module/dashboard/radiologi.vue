<template>
    <div>
        <div class="business-dashboard hr-dashboard">
            <div class="columns">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="illustration-header-2 large-screen">
                                <div class="header-image">
                                    <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                                        style="max-width:75%; margin-left: 2rem; margin-top: 0.5rem;" />
                                </div>

                                <div class="header-meta" style="margin-left : -2rem;">
                                    <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                                        Radiologi
                                    </h3>
                                    <p>
                                        Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                                    </p>
                                    <VTag @click="showModalFilter()" color="danger" rounded elevated
                                        style="position: relative; bottom: -1.5rem; cursor:pointer, height: 3em">{{
                                            H.formatDateToLocalString(item.periode.start) ==
                                                H.formatDateToLocalString(item.periode.end) ?
                                                H.formatDateToLocalString(item.periode.start) :
                                                H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                                                    H.formatDateToLocalString(item.periode.end) : '')
                                        }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
                        style="z-index: 6;top: 38px;position: relative; left: 20px" />
                    <div class="column is-12" style="margin-top: 2rem;">
                            <div class="list-view list-view-v3">

                                <div class="search-menu-rad mb-2">
                                    <div class="search-location-rad" style="width: 100%">
                                        <i class="iconify" data-icon="feather:search"></i>
                                        <input type="text"
                                            placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                            v-model="item.search" v-on:keyup.enter="fetchDataOrder(order)" />
                                    </div>
                                    <VButton raised class="search-button-rad" @click="fetchDataOrder(order)"
                                        :loading="isLoading"> Cari Data
                                    </VButton>
                                </div>

                                <VCard class="text-center pt-0 pb-0 mt-0">
                                    <VRadio v-model="order" value="0" label="Belum Kaji" name="outlined_radio"
                                        color="warning" />
                                    <VRadio v-model="order" value="1" label="Sudah KAji" name="outlined_radio"
                                        color="info" />
                                </VCard>

                                <VPlaceholderPage :title="H.assets().notFound"
                                    :subtitle="H.assets().notFoundSubtitle" class="my-6"
                                    :class="[dataOrder.length !== 0 && 'is-hidden']">
                                    <template #image>
                                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                            alt="" />
                                    </template>
                                </VPlaceholderPage>
                                <div class="list-view-inner"
                                    style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                                    <TransitionGroup name="list-complete" tag="div">
                                        <!--Item-->
                                        <div v-for="(items, m) in dataOrder" :key="m" class="list-view-item">
                                            <div class="list-view-item-inner">
                                                <VAvatar size="small" style="left: 8px;top: 4px;"
                                                    :color="listColor[i]" :initials="items.initials" />
                                                <div class="meta-left">
                                                    <h3>
                                                        {{ items.namaperusahaan }} <i
                                                            class="fas fa-venus"
                                                            aria-hidden="true"></i>
                                                    </h3>
                                                    <span style="color: black">
                                                        <i aria-hidden="true" class="iconify fas fa-stethoscope"
                                                            data-icon=""></i>
                                                        <span> {{ items.nopendaftaran }}</span>
                                                        <i aria-hidden="true"
                                                            class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:clock"></i>
                                                        <span>{{ items.tgldaftar }}</span>
                                                    </span>
                                                    <br>
                                                </div>
                                                <div class="meta-right">
                                                    <VIconButton v-tooltip.bottom.left="'Verifikasi'"
                                                        label="Bottom Left" color="primary" circle
                                                        icon="pi pi-check-circle" v-if="items.statusorder == 0"
                                                        @click="orderVerify(items)" style="margin-right: 15px;" />

                                                    <VIconButton color="primary" circle icon="fas fa-stethoscope"
                                                        outlined raised @click="emr(items)" v-tooltip.bottom="'EMR'"
                                                        v-if="items.statusorder == 0" style="margin-right: 15px;" />

                                                    <VIconButton v-tooltip.bottom.left="'Detail'"
                                                        label="Bottom Left" v-else color="primary" circle
                                                        icon="pi pi-book" @click="getDetailVerify(items)"
                                                        style="margin-right: 15px;" />
                                                    <VDropdown icon="feather:more-vertical" spaced right
                                                        v-tooltip.bubble="'CETAK'">
                                                        <template #content>
                                                            <a role="menuitem" class="dropdown-item is-media"
                                                                @click="cetakSEP(items)">
                                                                <div class="icon">
                                                                    <i class="iconify" data-icon="feather:printer"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="meta">
                                                                    <span>Cetak SEP</span>
                                                                    <span>Cetak Surat Elegibilitas</span>
                                                                </div>
                                                            </a>
                                                            <a role="menuitem" class="dropdown-item is-media"
                                                                @click="cetakOrder(items)">
                                                                <div class="icon">
                                                                    <i class="iconify" data-icon="feather:printer"
                                                                        aria-hidden="true"></i>
                                                                </div>
                                                                <div class="meta">
                                                                    <span>Cetak Order</span>
                                                                    <span>Cetak Order Lab</span>
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
                    </div>
                </div>
            </div>
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

        <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
            @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
            <template #content>
                <div class="business-dashboard hr-dashboard">
                    <div class="columns is-multiline">
                        <div class="column is-12 p-0">
                            <div class="block-header">
                                <div class="left column is-3 p-0">
                                    <div class="current-user">
                                        <VAvatar size="medium" :picture="item.jeniskelamin == 'PEREMPUAN'
                                            ? '/images/avatars/svg/vuero-4.svg'
                                            : '/images/avatars/svg/vuero-1.svg'
                                            " squared />
                                        <h3>{{ item.namapasien }}</h3>
                                        <p class="block-text">
                                            {{ item.noregistrasi + (item.jeniskelamin ==
                                                'PEREMPUAN' ? ' (P)'
                                                :
                                                ' (L)')
                                            }}</p>
                                    </div>
                                </div>
                                <div class="center">
                                    <div class="columns">
                                        <div class="column">
                                            <h4 class="block-heading">No. Order</h4>
                                            <p class="block-text">{{ item.noorder }}</p>
                                            <h4 class="block-heading">Tgl Registrasi</h4>
                                            <p class="block-text">{{ item.tglregistrasi }}</p>
                                        </div>
                                        <div class="column">
                                            <h4 class="block-heading">Diagnosa</h4>
                                            <p class="block-text">
                                                {{ item.namadiagnosa ? item.namadiagnosa : 'Belum Ada Diagnosa' }}</p>
                                            <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                                            <p>
                                                <VTag color="orange" :label="item.kelompokpasien" />
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="right">
                                    <div class="columns">
                                        <div class="column">
                                            <h4 class="block-heading">Ruangan Asal</h4>
                                            <p class="block-text">{{ item.ruanganasal }}</p>
                                            <h4 class="block-heading">Ruangan Tujuan</h4>
                                            <p class="block-text">{{ item.ruangantujuan }}</p>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="column is-12 p-4 mt-5">
                    <Fieldset legend="Tambah Tindakan" :toggleable="true">
                        <div class="columns pl-3">
                            <div class="column is-1 pr-0" style="padding-left: 0px;margin-right: -38px">
                                <VField label="No">
                                    <VAvatar initials="1" />
                                </VField>
                            </div>
                            <div class="column is-11 ml-5">
                                <div class="columns">
                                    <div class="column is-4">
                                        <VField label="Pelayanan" class="is-rounded-select  is-autocomplete-select"
                                            v-slot="{ id }">
                                            <VControl icon="feather:list" class="prime-auto-select" fullwidth>
                                                <Dropdown v-model="item.produk" :options="d_Produk"
                                                    :optionLabel="'label'" placeholder="Pilih data" style="width: 100%;"
                                                    class="is-rounded" showClear :filter="true"
                                                    @change="changeTindakan(item.produk)" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <VField label="Harga">
                                            <VLabel class="mt-4">{{
                                                H.formatRp((item.hargaLayanan ? item.hargaLayanan : 0),
                                                    'Rp.')
                                            }}</VLabel>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <VField label="Jumlah">
                                            <VControl icon="lnir lnir-repeat-one">
                                                <VInput type="text" v-model="item.jumlah" placeholder="Jumlah"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="columns mt-2" style="margin-left:40px">
                                        <VButtons>
                                            <VButton color="success" raised icon="feather:edit"
                                                v-if="item.no && d_Komponen.length" @click="update(item)"> Update
                                            </VButton>
                                            <VButton color="info" raised icon="fas fa-plus"
                                                v-else-if="!item.no && d_Komponen.length" @click="add(), clear()">
                                                Tambah
                                            </VButton>
                                            <VButton raised @click="clear()"> Batal </VButton>
                                        </VButtons>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Data Order Tindakan" :toggleable="true">
                        <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataOrder">
                            <div class="columns is-multiline">
                                <div class="column is-2" style="margin-top: 27px;">
                                    <VPlaceload class="mx-2" />
                                </div>
                                <div class="column">
                                    <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
                                </div>

                            </div>
                        </div>

                        <div class="timeline-wrapper" v-else>
                            <div class="timeline-wrapper-inner">
                                <div class="timeline-container">
                                    <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan"
                                        :key="items.norec">
                                        <div class="date">
                                            <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                        </div>
                                        <div :class="'dot is-' + listColor[index + 1]"></div>

                                        <div class="content-wrap is-grey">
                                            <div class="content-box">
                                                <div class="status"></div>
                                                <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                                    <i class="iconify" data-icon="feather:package"
                                                        aria-hidden="true"></i>
                                                </VIconBox>
                                                <div class="box-text" style="width:70%">
                                                    <div class="meta-text">
                                                        <p>
                                                            <span>{{ items.namaproduk }} {{ (items.posisi !== '' &&
                                                                items.posisi != null) ? `- ${items.posisi}`:'' }}</span>
                                                        </p>
                                                        <table class="tb-order">
                                                            <tr>
                                                                <td>Harga </td>
                                                                <td>:</td>
                                                                <td class="font-values">{{ H.formatRp(items.hargasatuan,
                                                                    'Rp. ')
                                                                    }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Jumlah</td>
                                                                <td>:</td>
                                                                <td>{{ items.qtyproduk }} </td>
                                                            </tr>
                                                            <!-- <tr>
                                                            <td>Cito</td>
                                                            <td>:</td>
                                                            <td>{{ items.nilaiStatusCito ?'Ya':'' }} </td>
                                                        </tr> -->

                                                        </table>

                                                    </div>
                                                </div>
                                                <div class="box-end" style="width: 30%">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-6" style="margin-top: 0.5rem;">
                                                            <VIconButton v-tooltip.bottom.left="'Edit'"
                                                                icon="feather:edit" @click="edit(items)" color="warning"
                                                                raised circle class="mr-2">
                                                            </VIconButton>
                                                            <VIconButton v-tooltip.bottom.right="'Hapus'"
                                                                icon="feather:trash" @click="hapusItems(items)"
                                                                color="danger" raised circle>
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </Fieldset>
                </div>

                <div class="column is-12" style="padding: 2rem 6rem;">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Jadwalkan Pemeriksaan">
                                <VDatePicker v-model="item.pemeriksaanterjadwal" mode="dateTime" style="width: 100%"
                                    trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Dokter Order">
                                <VControl class="mt-2">
                                    <VInput type="text" placeholder="Dokter Order" readonly class="is-rounded"
                                        v-model="item.dokterorder"
                                        style="cursor:pointer text-align: center;background: var(--fade-grey);" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField class="mt-2 ml-2" label="Pemeriksaan Tunda">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="item.status_tunda" class="p-0 ml-2" color="primary" square
                                        style="transform: scale(1.5);" />
                                </VControl>
                            </VField>
                        </div>
                        <div v-if="item.status_tunda === true" class="column is-6">
                            <VField label="Keterangan Tunda">
                                <VTextarea rows="2" placeholder="Keterangan Pemeriksaan Tunda......"
                                    v-model="item.kettunda">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <VField label="Waktu Pemeriksaan">
                                <VDatePicker v-model="item.waktuPemeriksaan" mode="dateTime" style="width: 100%"
                                    trim-weeks>
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Dokter Verifikator</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.dokterverify" :options="d_Dokter" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                        :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel class="required-field">Petugas Verifikator</VLabel>
                                <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                    <Dropdown v-model="item.petugasverify" :options="d_DokterVerif" optionLabel="label"
                                        class="is-rounded" placeholder="Pilih data" style="width: 100%;"
                                        :filter="true" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <VField label="Catatan">
                                <VControl class="mt-2">
                                    <VTextarea rows="4" placeholder="Tulis Catatan..." v-model="item.catatan">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Catatan Klinis">
                                <VControl class="mt-3">
                                    <VTextarea rows="4" placeholder="Tulis Catatan Klinis..."
                                        v-model="item.catatanklinis">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>

            </template>
            <template #action>
                <VButton v-if="isLoadDataSoNorec" icon="feather:printer" @click="cetakBuktiOrder(norec)" color="info"
                    :loading="isLoadingSave" raised>Cetak</VButton>
                <VButton v-if="isLoadDataSoNorec" icon="feather:save" @click="save()" color="primary"
                    :loading="isLoadingSave" raised disabled>Simpan</VButton>
                <VButton v-if="item.status_tunda === true" icon="feather:save" @click="saveTunda()" color="primary"
                    :loading="isLoadingSave" raised>
                    Simpan Tunda
                </VButton>
                <VButton v-if="!item.status_tunda && item.pemeriksaanterjadwal && !isLoadDataSoNorec"
                    icon="feather:save" @click="saveJadwal()" color="primary" :loading="isLoadingSave" raised>
                    Simpan Terjadwal
                </VButton>
                <VButton v-if="!isLoadDataSoNorec && !item.pemeriksaanterjadwal && !item.status_tunda"
                    icon="feather:save" @click="save()" color="primary" :loading="isLoadingSave" raised>Simpan
                </VButton>
            </template>
        </VModal>
        <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
            @close="modalFilter = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns">
                        <div class="column is-12" style="text-align: center">
                            <VField class="is-centered">
                                <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks />
                                <!-- :max-date="new Date()" /> -->
                            </VField>
                        </div>
                    </div>
                </form>
            </template>
            <template #action>
                <VButton icon="feather:search" @click="changePeriode()" :loading="isLoading" color="primary" raised>
                    Filter</VButton>
            </template>
        </VModal>
    </div>
</template>


<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset'
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import * as qzService from '/@src/utils/qzTrayService'

useHead({
    title: 'Dashboard Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const NOREC_PD = useRoute().query.nocm as string
const themeColors = useThemeColors()
const dataSource: any = ref([])
const filters = ref('')
const d_Komponen = ref([])
const d_Produk = ref([])
const d_Dokter = ref([])
const d_DokterVerif = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
const d_Ruangan = ref([])
const modalHistori = ref(false)

let chartOP: any = ref({
    series: [],
})
const rowGroupMetadata = ref({})
const currentPage: any = ref({
    limit: 5,
    rows: 50,
})

var date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });

let listColor: any = ref(Object.keys(useThemeColors()))
const modalDetail = ref(false)
const route = useRoute()
const userLogin = useUserSession().getUser()
let so_norec = ref('')
let statusOrder: any = ref([])
let result: any = ref([])
let dokterPraktek: any = ref([])
let dokterNonPraktek: any = ref([])
let isLoading: any = ref(false)
let modalDetailOrder: any = ref(false)
let modalFilter: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalGolonganDarah: any = ref(false)
let modalJenisKelamin: any = ref(false)
let modalTransaksi: any = ref(false)
let dataPasien: any = ref([])
let isLoadingSave: any = ref(false)
let isLoadDataOrder: any = ref(false)
let isLoadDataSoNorec: any = ref(false)
// let status_tunda: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataStokObat: any = ref([])
let detailOrderVerify: any = ref(0)
let detailOrderLayanan: any = ref(0)
let totalData: any = ref(0)
let dataPenunjang: any = ref([])
let isData: any = ref()
let sourceItemSelect: any = ref([])
let data2: any = ref([])
let chartLO: any = ref({
    series: [],
})
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
    waktuPemeriksaan: new Date()

})
const order: any = ref(0)
const dataOrder: any = ref(0)
const router = useRouter()

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dokterPraktek.value
    }
    return dokterPraktek.value.filter((item: any) => {
        return item.namalengkap.match(new RegExp(filters.value, 'i'))
    })
})

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
            fetchDataOrder(0)
            fetchPenunjang()
        }
    }
)
watch(
    () => currentPage.value.limit,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            fetchDataOrder(0)
            fetchPenunjang()
        }
    }
)

const fetchDataOrder = async (q: any) => {
    statusOrder.value = q
    isLoading.value = true
    let dari = 'dari=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD 00:00')
    let sampai = '&sampai=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD  23:59')
    let search = ''
    let qnocm = ''
    let qnoregistrasi = ''
    let StatusOrder = ''
    item.value.statusOrder = q
    if (order) StatusOrder = '&statusorder=' + q
    if (item.value.search) search = '&search=' + item.value.search
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    let page: any = route.query.page ? route.query.page : 1

    await useApi().get(`registrasi/list-mitra-grid?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + dari + sampai + StatusOrder + search).then((response) => {
        modalFilter.value = false
        dataOrder.value = response.data
        totalData.value = response.total
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
        });
        isData.value = response.total
        isLoading.value = false
    }).catch((err) => {
        modalFilter.value = false
    })
    isLoading.value = false
}

const fetchDetail = async () => {
    dokterPraktek.value = []
    dataStokObat.value = []
    const response = await useApi().get(
        '/dashboard/radiologi/get-detail-rad'
    )
    dokterPraktek.value = response.dokter
    dataStokObat.value = response.produk
}

const fetchPenunjang = async () => {
    let qnama = ''
    let qnocm = ''
    let qsearch = ''
    let qnoregistrasi = ''
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    let ruanganid = ''
    if (item.value.filterRuangan) {
        ruanganid = `&ruanganid=${item.value.filterRuangan}`
    }
    if (item.value.qnama) qnama = `&qnama=${item.value.qnama}`
    if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
    if (item.value.qnocm) qnocm = `&qnocm=${item.value.qnocm}`
    if (item.value.qnoregistrasi) qnoregistrasi = `&qnoregistrasi=${item.value.qnoregistrasi}`
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    let page: any = route.query.page ? route.query.page : 1

    dataPenunjang.value.loading = true
    dataPenunjang.value = []
    const response = await useApi().get(`/dashboard/radiologi/get-penunjang-rad?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + tglAwal + tglAkhir + qnama + qnocm + qnoregistrasi + ruanganid + qsearch)
    dataPenunjang.value.loading = false
    dataPenunjang.value = response.data
    if (page > 1 && response.data.length == 0) {
        router.push({
            name: 'module-dashboard-radiologi'
        })
    }
}


const getListPelayanan = async (data: any) => {
    const response = await useApi().get(`/dashboard/radiologi/get-pelayanan?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}`)
    d_Produk.value = response.map((e: any) => {
        return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
    })
}

const getListDokter = async () => {
    const response = await useApi().get(`/dashboard/radiologi/get-dokter`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
    d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
    d_Dokter.value = response.data.map((e: any) => {
        return { label: `${e.namalengkap}`, value: `${e.id}`, default: e }
    })
    d_DokterVerif.value = response.pegawaiRadiologi.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
    })

}

const orderVerify = async (e: any) => {
    detailOrderLayanan.value = []
    modalDetailOrder.value = true
    let data = {
        'idkelas': e.objectkelasfk,
        'idJenisPelayanan': e.jenispelayananfk,
        'idRekanan': e.objectrekananfk
    }
    d_DokterVerif.value.forEach((element: any) => {
        if (element.value == H.pegawaiLogin().id) {
            item.value.dokterverify = element
            return
        }
    });
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.ruanganasal = e.ruanganasal
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.pdNorec = e.pd_norec
    item.value.soNorec = e.norec
    item.value.noregistrasi = e.noregistrasi
    item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tglregistrasi = e.tglregistrasi
    item.value.kelas = e.objectkelasfk
    item.value.catatanklinis = e.catatanklinis
    item.value.catatan = e.keterangan
    getListPelayanan(data)
    isLoadDataOrder.value = true
    isLoadDataSoNorec.value = false
    const getHargaLayanan = await useApi().get(`/dashboard/radiologi/get-order-layanan-rad?strukorderfk=${e.norec}&objectkelasfk=${e.objectkelasfk}`)
    // const response = await useApi().get(`/dashboard/radiologi?statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    getHargaLayanan.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    isLoadDataOrder.value = false
    // detailDiagnosa.value = response[0].detailDiagnosa
    detailOrderLayanan.value = getHargaLayanan
}

const getDetailVerify = async (e: any) => {
    console.log(e)
    isLoadDataSoNorec = false

    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tglregistrasi = e.tglregistrasi

    modalDetailOrderVerify.value = true
    const response = await useApi().get(`/dashboard/radiologi/get-order-verify?norec_so=${e.norec}`)
    const diagnosa = await useApi().get(`/dashboard/radiologi?tglAwal=${e.tglregistrasi}&tglAkhir=${e.tglregistrasi}&statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    detailDiagnosa.value = diagnosa ? diagnosa[0].detailDiagnosa : ''
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailOrderVerify.value = response
    // console.log(detailOrderVerify)
}

const save = async () => {

    if (detailOrderLayanan.value.length < 1) {
        useToaster().error('Order Tidak Boleh kosong')
        return
    }
    if (!item.value.dokterverify) {
        useToaster().error('Dokter Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.petugasverify) {
        useToaster().error('Petugas Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.waktuPemeriksaan) {
        useToaster().error('Waktu Pemeriksaan Tidak Boleh Kosong')
        return
    }
    let datas: any = []
    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'catatan': item.value.catatan,
        // 'kettunda': item.value.kettunda,
        // 'status_tunda': item.value.status_tunda,
        'dokterverify': item.value.dokterverify.value,
        'pegawaiverifikatorfk': item.value.petugasverify.value,
        'catatanklinis': item.value.catatanklinis,
        'tglpelayanan': H.formatDate(item.value.waktuPemeriksaan, 'YYYY-MM-DD HH:mm:ss'),
    }
    isLoadingSave.value = true
    let objBridg: any = []
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        let response = detailOrderLayanan.value[i]
        let komponenHarga = detailOrderLayanan.value[i].komponenharga
        var objSave = {
            'idProduk': response.prid,
            'hargaLayanan': response.hargasatuan,
            'tglpelayanan': response.tglpelayanan,
            'jumlah': response.qtyproduk,
            'posisi': response.posisi,
            'komponenharga': komponenHarga
        }
        datas.push(objSave)
        objBridg.push({
            produkfk: response.prid,
            namaproduk: response.namaproduk,
            qtyproduk: response.qtyproduk,
            objectkelasfk: item.value.kelas,
        })

    }
    let itemsave = {
        "details": objBridg,
        "noorder": item.value.noorder,
        "objectkelasfk": item.value.kelas,
        "objectruangantujuanfk": item.value.idRuanganTujuan,
        "objectpegawaiorderfk": item.value.objectpegawaiorderfk,
        "iddokterverif": item.value.dokterverify.value,
        "namadokterverif": item.value.dokterverify.label,
        "catatan_klinis": item.value.catatanklinis ? item.value.catatanklinis : null,
    }
    await useApi().post('/bridging/penunjang/save-bridging-zeta', itemsave).then(async (e: any) => {
        await useApi().post('/dashboard/radiologi/save-order-pelayanan', { 'data': datas, 'parameter': parameter }).then((response: any) => {
            so_norec = response.pelPasien.strukorderfk
            if (item.value.noorder) {
                const jsonSpecimen = {
                    noorder: item.value.noorder
                }
                useApi().postNoMessage('/dashboard/satusehat/Specimen', jsonSpecimen).then((resp: any) => { })
            }
            isLoadingSave.value = false
            fetchDataOrder(0)
            reload()
        }).catch((e: any) => {
            useToaster().error(e)
        })

    }).catch((error) => {
        useToaster().error('Something Went Wrong')
    })

    // so_norec = "ada"
    isLoadDataSoNorec.value = true
    // console.log(so_norec);
}

const saveJadwal = async () => {

    if (detailOrderLayanan.value.length < 1) {
        useToaster().error('Order Tidak Boleh kosong')
        return
    }
    if (!item.value.dokterverify) {
        useToaster().error('Dokter Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.petugasverify) {
        useToaster().error('Petugas Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.waktuPemeriksaan) {
        useToaster().error('Waktu Pemeriksaan Tidak Boleh Kosong')
        return
    }
    let datas: any = []
    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'catatan': item.value.catatan,
        'pemeriksaanterjadwal': H.formatDate(item.value.pemeriksaanterjadwal, 'YYYY-MM-DD HH:mm:ss'),
        'dokterverify': item.value.dokterverify.value,
        'pegawaiverifikatorfk': item.value.petugasverify.value,
        'catatanklinis': item.value.catatanklinis,
        'tglpelayanan': H.formatDate(item.value.waktuPemeriksaan, 'YYYY-MM-DD HH:mm:ss'),
    }
    isLoadingSave.value = true
    let objBridg: any = []
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        let response = detailOrderLayanan.value[i]
        let komponenHarga = detailOrderLayanan.value[i].komponenharga
        var objSave = {
            'idProduk': response.prid,
            'hargaLayanan': response.hargasatuan,
            'tglpelayanan': response.tglpelayanan,
            'jumlah': response.qtyproduk,
            'posisi': response.posisi,
            'komponenharga': komponenHarga
        }
        datas.push(objSave)
        objBridg.push({
            produkfk: response.prid,
            namaproduk: response.namaproduk,
            qtyproduk: response.qtyproduk,
            objectkelasfk: item.value.kelas,
        })

    }
    let itemsave = {
        "details": objBridg,
        "noorder": item.value.noorder,
        "objectkelasfk": item.value.kelas,
        "objectruangantujuanfk": item.value.idRuanganTujuan,
        "objectpegawaiorderfk": item.value.objectpegawaiorderfk,
        "iddokterverif": item.value.dokterverify.value,
        "namadokterverif": item.value.dokterverify.label,
        "pemeriksaanterjadwal": H.formatDate(item.value.pemeriksaanterjadwal, 'YYYY-MM-DD HH:mm:ss'),
        "catatan_klinis": item.value.catatanklinis ? item.value.catatanklinis : null,
    }
    await useApi().post('/bridging/penunjang/save-bridging-zeta-terjadwal', itemsave).then(async (e: any) => {
        await useApi().post('/dashboard/radiologi/save-order-pelayanan-terjadwal', { 'data': datas, 'parameter': parameter }).then((response: any) => {
            so_norec = response.pelPasien.strukorderfk

            isLoadingSave.value = false
            fetchDataOrder(0)
            reload()
        }).catch((e: any) => {
            useToaster().error('Something Went Wrong')
        })

    }).catch((error) => {
        useToaster().error('Something Went Wrong')
    })

    // so_norec = "ada"
    isLoadDataSoNorec.value = true
    // console.log(so_norec);
}


const saveTunda = async () => {

    if (detailOrderLayanan.value.length < 1) {
        useToaster().error('Order Tidak Boleh kosong')
        return
    }
    if (!item.value.dokterverify) {
        useToaster().error('Dokter Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.petugasverify) {
        useToaster().error('Petugas Verify Tidak Boleh Kosong')
        return
    }
    if (!item.value.waktuPemeriksaan) {
        useToaster().error('Waktu Pemeriksaan Tidak Boleh Kosong')
        return
    }
    let datas: any = []
    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'catatan': item.value.catatan,
        'kettunda': item.value.kettunda,
        'status_tunda': item.value.status_tunda,
        'dokterverify': item.value.dokterverify.value,
        'pegawaiverifikatorfk': item.value.petugasverify.value,
        'catatanklinis': item.value.catatanklinis,
        'tglpelayanan': H.formatDate(item.value.waktuPemeriksaan, 'YYYY-MM-DD HH:mm:ss'),
    }
    isLoadingSave.value = true
    let objBridg: any = []
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        let response = detailOrderLayanan.value[i]
        let komponenHarga = detailOrderLayanan.value[i].komponenharga
        var objSave = {
            'idProduk': response.prid,
            'hargaLayanan': response.hargasatuan,
            'tglpelayanan': response.tglpelayanan,
            'jumlah': response.qtyproduk,
            'posisi': response.posisi,
            'komponenharga': komponenHarga
        }
        datas.push(objSave)
        objBridg.push({
            produkfk: response.prid,
            namaproduk: response.namaproduk,
            qtyproduk: response.qtyproduk,
            objectkelasfk: item.value.kelas,
        })

    }
    let itemsave = {
        "details": objBridg,
        "noorder": item.value.noorder,
        "objectkelasfk": item.value.kelas,
        "objectruangantujuanfk": item.value.idRuanganTujuan,
        "objectpegawaiorderfk": item.value.objectpegawaiorderfk,
        "iddokterverif": item.value.dokterverify.value,
        "namadokterverif": item.value.dokterverify.label,
        "catatan_klinis": item.value.catatanklinis ? item.value.catatanklinis : null,
    }
    await useApi().post('/dashboard/radiologi/save-order-pelayanan-tunda', { 'data': datas, 'parameter': parameter }).then((response: any) => {
        so_norec = response.pelPasien.strukorderfk
        isLoadingSave.value = false
        fetchDataOrder(0)
        reload()
    }).catch((e: any) => {
        useToaster().error('Something Went Wrong')
    })
}

const cetakBuktiOrder = () => {
    // console.log(so_norec)

    H.printBlade('radiologi/cetakan-hasil-radiologi?noregistrasi=' + item.value.noregistrasi
        + '&so_norec=' + so_norec);
}

const hapusItems = (e: any) => {
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        if (detailOrderLayanan.value[i].no == e.no) {
            detailOrderLayanan.value.splice(i, 1);
        }
    }
    dataSource.value = detailOrderLayanan.value
}


const changeSwitch = (e: any) => {
    fetchDataOrder(e)
}

const getHarga = async (idProduk: any) => {
    const response = await useApi().get(`/dashboard/radiologi/get-pelayanan?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idProduk=${idProduk}`)
    item.value.hargaLayanan = response[0].hargasatuan
    item.value.namaproduk = response[0].namaproduk
    item.value.jumlah = 1
}

const clear = () => {
    item.value.id = ''
    delete item.value.no
    delete item.value.produk
    item.value.hargaLayanan = ''
    item.value.qtyproduk = ''
    item.value.jumlah = ''
}

const add = async () => {

    let datas: any = []
    if (!item.value.produk) {
        useToaster().error('Layanan Tidak Boleh Kosong')
        return
    }
    if (!item.value.hargaLayanan) {
        useToaster().error('Harga Tidak Boleh Kosong')
        return
    }
    if (!item.value.jumlah) {
        useToaster().error('Jumlah Tidak Boleh Kosong')
        return
    }

    // let jumlah = e.jumlah
    // let harga = e.hargaLayanan
    // let ruangan = e.ruangantujuan
    // let namaproduk = e.namaproduk
    // let layanan = e.layanan
    let no

    (detailOrderLayanan.value.length == 0) ? no = 1 : no = detailOrderLayanan.value.length + 1

    let data = {
        no: no,
        prid: item.value.produk.id,
        tglpelayanan: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
        namaproduk: item.value.produk.namaproduk,
        komponenharga: d_Komponen.value,
        hargasatuan: item.value.hargaLayanan,
        qtyproduk: item.value.jumlah,
        ruangantujuan: item.value.ruangantujuan,
    }
    detailOrderLayanan.value.push(data)
}

const update = (e: any) => {
    // console.log(e)
    let data: any = {}
    for (let x = 0; x < detailOrderLayanan.value.length; x++) {
        const element = detailOrderLayanan.value[x];
        if (element.no == e.no) {
            data.no = element.no
            data.qtyproduk = e.jumlah
            data.hargasatuan = e.hargaLayanan
            data.komponenharga = d_Komponen.value
            data.prid = e.produk.id
            data.tglpelayanan = moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
            data.namaproduk = e.produk.namaproduk
            data.ruangantujuan = e.ruangantujuan
            detailOrderLayanan.value[x] = data
        }
    }
    clear()

}

const edit = (e: any) => {
    item.value.no = e.no
    d_Produk.value.forEach(element => {
        if (element.id == e.prid) {
            item.value.produk = element
            return
        }
    });
    d_Komponen.value = e.komponenharga
    item.value.hargaLayanan = e.hargasatuan
    item.value.jumlah = e.qtyproduk
}

const chartLayananByRuangan = async () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    await useApi().get(`/dashboard/radiologi/chart-layanan-ruangan?${tglAwal}${tglAkhir}`).then((res: any) => {
        item.value.chartLength = res.chartLO.count.length
        // item.value = res
        // console.log(res.chartLO.categories)
        chartLO.value = {
            series: [
                {
                    name: 'pasien',
                    data: res.chartLO.count
                }
            ],
            chart: {
                height: 220,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        // fontSize: '8px',
                        position: 'top', // top, center, bottom
                    },
                },
            },
            dataLabels: {
                enabled: true,
                // formatter: formatters.asPercent,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ['#304758'],
                },
            },
            xaxis: {
                categories: res.chartLO.categories,
                position: 'top',
                axisBorder: {
                    show: true,
                },
                axisTicks: {
                    show: false,
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        },
                    },
                },
                // tooltip: {
                //     enabled: true,
                // },
            },
            yaxis: {
                axisBorder: {
                    show: true,
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    // formatter: formatters.asPercent,
                },
            },
            colors: [themeColors.green, themeColors.secondary, themeColors.orange],
            title: {
                text: 'Pelayanan Berdasarkan Ruangan',
                align: 'left',
            },
        }
    })

}

const showModalFilter = () => {
    modalFilter.value = true
}

const changeRuang = (e: any) => {
    fetchDataOrder(0)
    fetchPenunjang()
    fetchPasien()
    chartLayananByRuangan()
}
const fetchPasien = async () => {
    let ruanganid = ''
    if (item.value.filterRuangan) {
        ruanganid = item.value.filterRuangan
    }
    let dari = ''
    if (item.value.periode.start) {
        dari = H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    }
    let sampai = ''
    if (item.value.periode.end) {
        sampai = H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    }
    let status = ''


    let namapasien = ''
        , nocm = ''
        , noreg = ''
        , statuspanggil = ''
        , rsearch = ''
    if (item.value.qnama) namapasien = item.value.qnama
    if (item.value.qnoreg) noreg = item.value.qnoreg
    if (item.value.qnocm) nocm = item.value.qnocm
    if (item.value.fStatusAntrian) statuspanggil = item.value.fStatusAntrian
    if (item.value.rsearch) rsearch = item.value.rsearch
    isLoading.value = true
    dataPasien.value = []
    const response = await useApi().get(
        '/radiologi/list-pasien-regis?ruanganid=' + ruanganid
        + '&dari=' + dari
        + '&sampai=' + sampai
        + '&namapasien=' + namapasien
        + '&nocm=' + nocm
        + '&noregistrasi=' + noreg
        + '&statuspanggil=' + statuspanggil
        + '&rsearch=' + rsearch
    )
    isLoading.value = false
    response.data.sort(compare);
    dataPasien.value = response.data

    updateRowGroupMetaData();
}
const compare = (a: any, b: any) => {
    if (a.namaruangan > b.namaruangan) {
        return -1;
    }
    if (a.namaruangan < b.namaruangan) {
        return 1;
    }
    return 0;
}
const updateRowGroupMetaData = () => {
    rowGroupMetadata.value = {};

    if (dataPasien.value) {
        for (let i = 0; i < dataPasien.value.length; i++) {
            let rowData = dataPasien.value[i];
            let namaruangan = rowData.namaruangan;

            if (i == 0) {
                rowGroupMetadata.value[namaruangan] = { index: 0, size: 1 };
            }
            else {
                let previousRowData = dataPasien.value[i - 1];
                let previousRowGroup = previousRowData.namaruangan;
                if (namaruangan === previousRowGroup)
                    rowGroupMetadata.value[namaruangan].size++;
                else
                    rowGroupMetadata.value[namaruangan] = { index: i, size: 1 };
            }
        }
    }
}

const daftar = (e: any) => {
    // console.log(e.objectruanganlastfk)
    item.norec_pd = e.norec_pd
    item.objectruanganlastfk = e.objectruanganlastfk
    item.nocmfk = e.nocm
    item.noregistrasi = e.norec_pd
    // item.objectruangantujuanfk = e.objectruangantujuanfk
    item.objectruanganfk = e.objectruanganfk
    item.norec_apd = e.norec_apd
    item.objectkelasfk = e.objectkelasfk
    item.tglregistrasi = e.tglregistrasi
    sourceItemSelect.value = e
    modalTransaksi.value = true
}

const saveTransaksi = async (e: any) => {

    let json = {
        pasiendaftar: {
            'norec_pd': item.norec_pd,
            'tglregistrasi': item.tglregistrasi,
            'objectkelasfk': item.objectkelasfk,
            'noregistrasifk': item.pd_norec,
            'objectruanganlastfk': item.objectruanganlastfk
        },
        antrianpasiendiperiksa: {
            'norec_apd': item.norec_apd,
            'objectruangantujuanfk': e,
        }
    }

    isLoading.value = true
    await useApi()
        .post(`/radiologi/save-transaksi-rad`, json)
        .then((response: any) => {
            isLoading.value = false
            clear()
            goTo(sourceItemSelect.value)
            // fetchPasien()
        })
        .catch((e: any) => {
            isLoading.value = false
        })
}

const goTo = (e: any) => {

    let bindData = sourceItemSelect.value
    router.push({
        name: 'module-radiologi-transaksi-radiologi',
        query: {
            nocmfk: bindData.nocmfk,
            norec_pasien_daftar: bindData.norec_pd,
            norec_apd: bindData.norec_apd
        },

    })
}

const emr = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd ? e.norec_pd : e.pd_norec,
            norec_apd: e.norec_apd,
        }
    })
}

const changePeriode = () => {
    fetchDataOrder(0)
    fetchPenunjang()
    chartLayananByRuangan()
}

const transaksiPelayanan = (e: any) => {
    console.log(e)
    router.push({
        name: 'module-radiologi-transaksi-radiologi',
        query: {
            nocmfk: e.nocmfk,
            norec_pasien_daftar: e.norec_pd,
            norec_apd: e.norec_apd,
            tglpelayanan: e.tglpelayanan
        },

    })
}

const UpdateJenisKelamin = (e: any) => {
    item.value.norm = e.nocm
    item.value.jeniskelamin = e.objectjeniskelaminfk
    modalJenisKelamin.value = true
}

const saveJenisKelamin = async (e: any) => {
    let json = {
        'nocm': item.value.norm,
        'objectjeniskelaminfk': item.value.jeniskelamin,
    }
    useApi().post(
        `/dashboard/save-jenkel`, json).then((response: any) => {
            modalJenisKelamin.value = false
            fetchPenunjang()
        })
}

const UpdateGolonganDarah = (e: any) => {
    item.value.nocm = e.nocm
    item.value.golongandarah = e.objectgolongandarahfk
    modalGolonganDarah.value = true
}

const saveGolonganDarah = async (e: any) => {
    let json = {
        'nocm': item.value.nocm,
        'objectgolongandarahfk': item.value.golongandarah,
    }
    useApi().post(
        `/dashboard/save-goldar`, json).then((response: any) => {
            modalGolonganDarah.value = false
            fetchPenunjang()
        })
}

const cetakOrder = async (e: any) => {
    let so_norec = e.norec;
    H.printBlade(`report/cetak-order?type=radiologi&noregistrasi=${so_norec}`)
}
const cetakSEP = (e: any) => {
    qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
        'SEP', 1)
}
const showPasienLama = async () => {
    router.push({
        name: 'module-registrasi-pasien-lama',
        query: {

        },

    })
}
const reload = async () => {
    fetchDataOrder(0)
    fetchPenunjang()
}

function changeTindakan(e: any) {
    // console.log(e)
    isLoading.value = true
    d_Komponen.value = []
    item.value.hargaLayanan = 0
    useApi().get(
        '/tindakan/list-tindakan-komponen?idRuangan=' + item.value.idRuanganTujuan
        + '&idKelas=' + item.value.kelas
        + '&idProduk=' + e.id
        + '&idJenisPelayanan=' + item.value.idJenisPelayanan
        // + '&idPenjamin=' + item.registrasi.objectrekananfk
    ).then((response: any) => {
        isLoading.value = false
        if (response.komponen.length == 0) {
            H.alert('warn', 'Komponen Tarif tidak ada')
            return
        }
        item.value.hargaLayanan = response.harga.hargasatuan
        item.hargasatuanDef = response.harga.hargasatuan
        item.value.jumlah = 1
        d_Komponen.value = response.komponen

    })
}

watch(
    () => [
        order.value
    ], () => {
        changeSwitch(order.value)
    }
)

fetchDataOrder(0)
getListDokter()
chartLayananByRuangan()
fetchPenunjang()
fetchDetail()
fetchPasien()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    position: relative;
    background: var(--fade-grey-light-2);
    border: 1px solid var(--fade-grey);
    max-width: 400px;
    height: 35px;
    border-bottom: none;

}

.tb-order .text-value {
    font-family: var(--font-alt);
    color: var(--dark-text);
    font-weight: 400;
    font-size: 12px;
}

.user-grid-v2 {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        @include vuero-s-card;

        text-align: center;

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.85rem;
        }

        .people {
            display: flex;
            justify-content: center;
            padding: 8px 0 30px;

            .v-avatar {
                margin: 0 4px;
            }
        }

        .buttons {
            display: flex;
            justify-content: space-between;

            .button {
                width: calc(50% - 4px);
                color: var(--light-text);

                &:hover,
                &:focus {
                    border-color: var(--fade-grey-dark-4);
                    color: var(--primary);
                    box-shadow: var(--light-box-shadow);
                }
            }
        }
    }

    .grid-item-wrap {
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        transition: all 0.3s; // transition-all test

        .grid-item-head {
            background: #fafafa;
            border-radius: var(--radius-large) 6px 0 0;
            padding: 20px;

            .flex-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;

                .meta {
                    span {
                        display: flex;

                        &:first-child {
                            font-family: var(--font-alt);
                            font-weight: 600;
                            font-size: 0.85rem;
                            color: white;
                        }

                        &:nth-child(2) {
                            font-size: 0.8rem;
                            color: white;
                        }
                    }
                }

                .status-icon {
                    height: 28px;
                    width: 28px;
                    min-width: 28px;
                    border-radius: var(--radius-rounded);
                    border: 1px solid var(--fade-grey-dark-3);
                    display: flex;
                    align-items: center;
                    justify-content: center;

                    &.is-success {
                        background: var(--success);
                        border-color: var(--success);
                        color: var(--white);
                    }

                    &.is-warning {
                        background: var(--orange);
                        border-color: var(--orange);
                        color: var(--white);
                    }

                    &.is-danger {
                        background: var(--danger);
                        border-color: var(--danger);
                        color: var(--white);
                    }

                    i {
                        font-size: 8px;
                    }
                }
            }

            .buttons {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0;

                .button,
                .v-button {
                    width: calc(50% - 4px);
                    color: var(--light-text);
                    margin-bottom: 0;

                    &:hover,
                    &:focus {
                        border-color: var(--fade-grey-dark-4);
                        color: var(--primary);
                        box-shadow: var(--light-box-shadow);
                    }
                }
            }
        }

        .grid-item {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border: none;
        }
    }
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }

    .user-grid-v2 {
        .grid-item-wrap {
            border-color: var(--dark-sidebar-light-12);

            .grid-item-head {
                background: var(--dark-sidebar-light-4);
            }
        }
    }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
    background: var(--success) !important
}

.user-grid-v2 .grid-item-wrap .grid-item-head {
    padding: 10px;
}

.search-menu-rad {
    height: 56px !important;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: white;
    border-radius: 8px;
    width: 100%;
    padding-left: 0.75rem;

    >div:not(:last-of-type) {
        border-right: 1px solid var(--search-border-color);
    }

    .search-bar {
        height: 55px;
        width: 100%;
        position: relative;
        display: flex;
        align-items: center;
        padding-right: 1.5rem;

        .field {
            width: 100%;
        }

        .multiselect-tags {
            padding-left: 2.5rem;
        }
    }

    .search-location-rad,
    .search-job,
    .search-salary {
        display: flex;
        align-items: center;
        width: 50%;
        font-size: 14px;
        font-weight: 500;
        padding: 0 25px;
        height: 100%;
        font-family: var(--font);

        input {
            width: 100%;
            height: 90%;
            display: block;
            font-family: var(--font);
            color: var(--input-color);
            background-color: transparent;
            border: none;
        }

        svg {
            margin-right: 0.5rem;
            width: 18px;
            color: var(--primary);
            flex-shrink: 0;
        }
    }

    .search-button-rad {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px !important;
        border: none;
        font-weight: 500;
        font-family: var(--font);
        padding: 0 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: white;
        cursor: pointer;
        margin-left: auto;
    }
}

.search-widget {
    flex: 1;
    display: inline-block;
    width: 100%;
    padding: 10px;
    background-color: var(--white);
    border-radius: 16px;
    border: 1px solid var(--fade-grey-dark-3);
    transition: all 0.3s;
}
</style>
