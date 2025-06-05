<template>
    <div class="columns is-multiline">
        <div class="column is-12">
            <div class="py-2">
                <h1 style="font-weight:bold">LAPORAN ANTROL</h1>
            </div>
        </div>
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <VDatePicker v-model="item.filterDate" is-range color="pink" trim-weeks
                            :max-date="new Date()">
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
                    <div class="column is-3">
                        <!-- <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'"  placeholder="Pilih Ruangan" />
                            </VControl>
                        </VField> -->

                        <VField v-slot="{ id }" class="is-autocomplete-select">
                            <VControl icon="feather:search">
                            <Multiselect
                                v-model="item.ruangan"
                                :attrs="{ id }"
                                :options="d_Ruangan"
                                placeholder="Pilih Ruangan"
                                :searchable="true"
                            />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField v-slot="{ id }" class="is-autocomplete-select">
                            <VControl icon="feather:search">
                            <Multiselect
                                v-model="item.statusantrean"
                                :attrs="{ id }"
                                :options="optionsStatusAntrean"
                                placeholder="Pilih Status"
                                :searchable="true"
                            />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 mt-2">
                        <VIconButton circle icon="feather:search" raised bold @click="fecthData"
                            :loading="isLoading" v-tooltip.bubble="'Cari'" class="mt-2-min mr-2">
                        </VIconButton>
                    </div>
                </div>
            </VCard>
        </div>
        <!-- <div class="column is-12" v-if="isLoading">
            <VPlaceloadWrap>
                <VPlaceload height="500px" width="100%" class="mx-2" />
            </VPlaceloadWrap>
        </div> -->

        <div class="column is-12">
            <DataTable v-model:filters="filters" :value="dataSource" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50, 100]" dataKey="no"
                filterDisplay="row" :globalFilterFields="['peserta']['nama']"
                class="p-datatable-sm"
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">


                <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                        <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                        to
                        Excel </VButton>
                    </div>
                </template>

                <template #empty style="text-align: center;"> No data found. </template>
                <ColumnGroup type="header">
                    <Row>
                        <Column :rowspan="2" header="Tgl Registrasi"></Column>
                        <Column :rowspan="2" header="Kode Booking"></Column>
                        <Column :rowspan="2" header="No RM"></Column>
                        <Column :rowspan="2" header="Nama Pasien"></Column>
                        <Column :rowspan="2" header="Ruangan"></Column>
                        <!-- <Column :colspan="2" header="Waktu Tunggu Admisi"></Column>
                        <Column :colspan="2" header="Waktu Layanan Admisi"></Column> -->
                        <Column :colspan="2" header="Waktu Tunggu Poli"></Column>
                        <Column :colspan="2" header="Waktu Layan Poli"></Column>
                        <!-- <Column :colspan="2" header="Waktu Tunggu Farmasi/Selesai Layan Poli"></Column>
                            <Column :colspan="2" header="Waktu Layan Farmasi"></Column>
                            <Column :colspan="2" header="Waktu Obat Selesai"></Column> -->
                        <Column :rowspan="2" header="Total Waktu"></Column>
                    </Row>
                    <Row>
                        <!-- <Column field="taksid_1" header="Waktu"></Column>
                        <Column field="status_1" header="Status"></Column> -->
                        <!-- <Column field="taksid_2" header="Waktu"></Column>
                        <Column field="status_2" header="Status"></Column> -->
                        <Column field="taksid_3" header="Waktu"></Column>
                        <Column field="status_3" header="Status"></Column>
                        <Column field="taksid_4" header="Waktu"></Column>
                        <Column field="status_4" header="Status"></Column>
                        <!-- <Column field="taksid_5" header="Waktu"></Column>
                        <Column field="status_5" header="Status"></Column>
                        <Column field="taksid_6" header="Waktu"></Column>
                        <Column field="status_6" header="Status"></Column>
                        <Column field="taksid_7" header="Waktu"></Column>
                        <Column field="status_7" header="Status"></Column> -->
                    </Row>
                </ColumnGroup>
                <Column field="tglregistrasi" header="Tgl Registrasi" frozen :sortable="true" style="min-width: 150px"></Column>
                <Column field="noregistrasi" header="Kode Booking" frozen :sortable="true" style="min-width: 150px"></Column>
                <Column field="norm" header="No RM" frozen :sortable="true" style="min-width: 100px"></Column>
                <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 300px"></Column>
                <Column field="namaruangan" header="Ruangan" frozen :sortable="true" style="min-width: 300px"></Column>
                <!-- <Column field="taksid_1" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_1 }} </template>
                </Column> -->
                <!-- <Column field="status_1" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_1" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_2" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_2 }} </template>
                </Column>
                <Column field="status_2" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_2" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column> -->
                <Column field="taksid_3" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_3 }} </template>
                </Column>
                <Column field="status_3" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_3" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_4" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_4 }} </template>
                </Column>
                <Column field="status_4" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_4" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <!-- <Column field="taksid_5" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_5 }} </template>
                </Column>
                <Column field="status_5" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_5" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_6" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_6 }} </template>
                </Column>
                <Column field="status_6" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_6" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column>
                <Column field="taksid_7" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.taksid_7 }} </template>
                </Column>
                <Column field="status_7" header="Status" style="min-width: 100px">
                    <template #body="slotProps">
                        <VIconButton color="primary" light circle icon="feather:check-circle" class="mr-2" v-if="slotProps.data.status_7" />
                        <VIconButton color="danger" light circle icon="feather:x-circle" class="mr-2" v-else />
                    </template>
                </Column> -->
                <Column field="total" header="Waktu" style="min-width: 150px">
                    <template #body="slotProps"> {{ slotProps.data.total }} </template>
                </Column>
            </DataTable>
        </div>
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status danger">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Jumlah Antean Belum Lengkap</span>
                        </div>
                        <small class="text-bold-custom h-100" style="font-size:1.5rem;"> {{ item.jlmAntreanB }}</small>
                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status success">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Jumlah Antean Lengkap</span>
                        </div>
                        <small class="text-bold-custom h-100" style="font-size:1.5rem;"> {{ item.jlmAntreanL }}</small>

                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status info">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Total Antean</span>
                        </div>
                        <small class="text-bold-custom h-100" style="font-size:1.5rem;"> {{ item.totalAntrean }}</small>

                    </VCardCustom>
                </div>
                <div class="column is-3">
                    <VCardCustom :style="'padding:5px 25px'">
                        <div class="label-status" :class="{'success': item.qualityAntrean >= 80, 'danger': item.qualityAntrean < 80 }">
                            <i aria-hidden="true" class="fas fa-circle"></i>
                            <span class="ml-1">Quality Rate</span>
                        </div>
                        <small class="text-bold-custom h-100 label-status" :class="{'success': item.qualityAntrean >= 80, 'danger': item.qualityAntrean < 80 }" style="font-size:1.4rem;"> {{ item.qualityAntrean }} %</small>
                    </VCardCustom>
                </div>
            </div>
        </div>
    </div>

    <!-- start modal log antrian online  -->
    <VModal is="form"
    :open="modalLoging"
    title="Logging pengiriman Antrol"
    size="medium"
    actions="right"
    @close="modalLoging = false">
        <template #content>
            <div class="modal-form">
                <div class="field">
                    <div class="control">
                        <textarea class="textarea" rows="4" v-model="item.logging"></textarea>
                    </div>
                </div>
            </div>
        </template>
    </VModal>
    <!-- end modal  -->

    <!-- start modal validasi antrian online  -->
    <VModal is="form"
    :open="modalDataAntrean"
    title="Data Antrean"
    size="medium"
    actions="right"
    @close="modalDataAntrean = false">
        <template #content>
            <div class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <div class="field">
                            <label>kode booking </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="kodebooking" v-model="item.antr_kodebooking" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>jenis pasien </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="jenispasien" v-model="item.antr_jenispasien" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>nomor kartu </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nomorkartu" v-model="item.antr_nomorkartu" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>nik </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nik" v-model="item.antr_nik" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>nohp </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nohp" v-model="item.antr_nohp" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>Poli </label>
                            <div class="control">
                                <VField v-slot="{ id }" class="is-autocomplete-select">
                                    <VControl icon="feather:search">
                                    <Multiselect
                                        v-model="item.antr_poli"
                                        :attrs="{ id }"
                                        :options="d_Ruangan"
                                        placeholder="Pilih Ruangan"
                                        :searchable="true"
                                    />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="column is-6">
                        <div class="field">
                            <label>nama poli </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nama poli" v-model="item.antr_namapoli" />
                            </div>
                        </div>
                    </div> -->

                    <div class="column is-6">
                        <div class="field">
                            <label>pasien baru </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="Ya = 1, Tidak = 0" v-model="item.antr_pasienbaru" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>norm </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="norm" v-model="item.antr_norm" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>tanggal periksa </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="tanggal periksa" v-model="item.antr_tanggalperiksa" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>dokter </label>
                            <div class="control">
                                <VField v-slot="{ id }" class="is-autocomplete-select">
                                    <VControl icon="feather:search">
                                    <Multiselect
                                        v-model="item.antr_dokter"
                                        :attrs="{ id }"
                                        :options="d_Dokter"
                                        placeholder="Pilih Dokter"
                                        :searchable="true"
                                    />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <!-- <div class="column is-6">
                        <div class="field">
                            <label>nama dokter </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nama dokter" v-model="item.antr_namadokter" />
                            </div>
                        </div>
                    </div> -->

                    <div class="column is-6">
                        <div class="field">
                            <label>jam praktek </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="jam praktek" v-model="item.antr_jampraktek" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>jenis kunjungan </label>
                            <div class="control">
                                <!-- <input type="text" class="input" placeholder="jenis kunjungan" v-model="item.antr_jeniskunjungan" /> -->
                                <VField v-slot="{ id }" class="is-autocomplete-select">
                                    <VControl icon="feather:search">
                                    <Multiselect
                                        v-model="item.antr_jeniskunjungan"
                                        :attrs="{ id }"
                                        :options="optionsJenisKunjungan"
                                        placeholder="Pilih Kunjungan"
                                        :searchable="true"
                                    />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>nomor referensi </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nomor referensi" v-model="item.antr_nomorreferensi" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>nomor antrean </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="nomor antrean" v-model="item.antr_nomorantrean" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>angka antrean </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="angka antrean" v-model="item.antr_angkaantrean" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>estimasi dilayani </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="estimasi dilayani" v-model="item.antr_estimasidilayani" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>sisa kuota jkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="sisa kuota jkn" v-model="item.antr_sisakuotajkn" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>kuota jkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="kuota jkn" v-model="item.antr_kuotajkn" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>sisa kuota nonjkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="sisa kuota nonjkn" v-model="item.antr_sisakuotanonjkn" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-6">
                        <div class="field">
                            <label>kuota nonjkn </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="kuota nonjkn" v-model="item.antr_kuotanonjkn" />
                            </div>
                        </div>
                    </div>

                    <div class="column is-12">
                        <div class="field">
                            <label>keterangan </label>
                            <div class="control">
                                <input type="text" class="input" placeholder="keterangan" v-model="item.antr_keterangan" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton type="button" color="success" @click="saveDataAntrean" :loading="isLoading" raised>Save</VButton>
        </template>
    </VModal>
    <!-- end modal  -->

</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
import { useApi } from '/@src/composable/useApi'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';   // optional
import Row from 'primevue/row';
import InputText from 'primevue/inputtext';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import { FilterMatchMode } from 'primevue/api'
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan Pasien Antrol - ' + import.meta.env.VITE_PROJECT,
})
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
const modalLoging: boolean = ref(false);
const modalDataAntrean: boolean = ref(false);
const isLoading: any = ref(false)
const isLoadingValidasi: any = ref(false)
const d_Dokter: any = ref({})
const d_Ruangan: any = ref({})
const item: any = reactive({
    filterDate: {
        start: new Date(),
        end: new Date()
    },
    jlmAntreanB: 0,
    jlmAntreanL: 0,
    totalAntrean: 0,
    qualityAntrean: 0,
})
const optionsStatusAntrean: any = [
    { value: 1, label:'Antrean Lengkap' },
    { value: 2, label:'Antrean Belum Lengkap' },
]
const optionsJenisKunjungan: any = [
    { value: 1, label:'Rujukan FKTP' },
    { value: 2, label:'Rujukan Internal' },
    { value: 3, label:'Kontrol' },
    { value: 4, label:'Rujukan Antar RS' },
]
const dataSource = ref([])
const filters = ref({
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },

});

const loadCombo = async () => {
    await useApi().get('bridging/antrol/getComboMonitoring').then((response) => {
        let ruangan = []
        for (let i = 0; i < response.ruangan.length; i++) {
            const element = response.ruangan[i];
            ruangan.push({
                value: element.id,
                label: element.namaruangan,
                kdspesialisbpjs: element.kdspesialisbpjs,
                kdsubspesialisbpjs: element.kdsubspesialisbpjs,
            })

        }
        d_Ruangan.value = ruangan
        let dokter = []
        for (let i = 0; i < response.dokter.length; i++) {
            const element = response.dokter[i];
            dokter.push({
                value: element.kddokterbpjs,
                label: element.namalengkap,
                kddokterbpjs: element.kddokterbpjs,
            })

        }
        d_Dokter.value = dokter
    })
}

const fecthData = async () => {
    let dari = H.formatDate(item.filterDate.start, 'YYYY-MM-DD') + " 00:00"
    let sampai = H.formatDate(item.filterDate.end, 'YYYY-MM-DD') + " 23:59"
    let ruangan = ""
    if(item.ruangan) {
        ruangan = `&ruangId=${item.ruangan}`
    }

    item.jlmAntreanB = 0
    item.jlmAntreanL = 0
    item.totalAntrean = 0
    item.qualityAntrean = 0
    isLoading.value = true
    const data = await useApi().get(`/pelayanan/get-laporan-Antrol?tglAwal=${dari}&tglAkhir=${sampai}${ruangan}`)
    isLoading.value = false
    let result = []
    console.log(data)
    for (let i = 0; i < data.length; i++) {
        const element = data[i];
        if((element.status_1 == true
        && element.status_2 == true
        && element.status_3 == true
        && element.status_4 == true
        && element.status_5 == true)
        || (element.status_3 == true && element.status_4 == true && element.status_5 == true)
        ) {
            if(item.statusantrean && item.statusantrean == 1)
                result.push(element)

            item.jlmAntreanL = item.jlmAntreanL + 1
        } else {
            if(item.statusantrean && item.statusantrean == 2)
                result.push(element)

            item.jlmAntreanB = item.jlmAntreanB + 1
        }
        item.totalAntrean = item.totalAntrean + 1

        if(!item.statusantrean)
            result.push(element)
    }
    dataSource.value = result
    item.qualityAntrean = item.jlmAntreanL / item.totalAntrean * 100
    item.qualityAntrean = item.qualityAntrean.toFixed(2)
}

const remakeData: any = ref([])
const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      TanggalRegistrasi: e.tglregistrasi, KodeBooking: e.antr_kodebooking, NoRM: e.norm, NamaPasien: e.namapasien, Ruangan: e.namaruangan, WaktuTungguPoli: e.taksid_3, WaktuLayanPoli: e.taksid_4,
      TotalWaktu: e.total,
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus();
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

loadCombo()
</script>
