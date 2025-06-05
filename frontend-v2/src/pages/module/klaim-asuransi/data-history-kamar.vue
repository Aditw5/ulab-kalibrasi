<template>
    <div class="columns">
        <div class="column">
            <VCard>
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-5 mb-2 mr-1">Daftar History Kamar</h3>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Tanggal">
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
                        </VField>
                    </div>
                    <!-- <div class="column is-4 mt-5">
                        <VField>
                            <VControl icon="feather:search">
                                <input v-model="item.search" v-on:keyup.enter="fetchData()" type="text" class="input"
                                placeholder="Cari No RM / No Registrasi / No BPJS / No SEP / Nama Pasien" />
                            </VControl>
                        </VField>
                    </div> -->
                    <div class="column is-4 flex mt-5">
                        <VControl raw subcontrol>
                            <VCheckbox v-model="item.ischecktglpulang" label="Filter By Tgl Pulang" color="success" />
                        </VControl>
                        <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                            :loading="isLoading" class=" is-pulled-">
                        </VIconButton>
                        <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-filter" raised bold @click="modalFilter = true"
                            v-tooltip.bubble="'Filter'">
                        </VIconButton>
                        <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-"
                            style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge>
                    </div>
                </div>

            </VCard>
        </div>
    </div>
    <div class="columns">
        <div class="column is-12">
            <VPlaceloadWrap v-if="isLoading">
                <VPlaceload height="500px" width="100%" />
            </VPlaceloadWrap>
            <VCard v-else>
                <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable :loading="isLoading"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                    class="p-datatable-sm">

                    <template #header>
                    <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                        <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                        to
                        Excel </VButton>
                    </div>
                    </template>

                    <template #empty style="text-align: center;"> No data found. </template>
                    <Column field="no" header="No" frozen></Column>
                    <Column field="nosep" header="No SEP" style="min-width: 100px"></Column>
                    <Column field="namaruangan" header="Poli / Unit" style="min-width: 200px"></Column>
                    <Column field="kamar" header="Kamar dan Bed" style="min-width: 200px"></Column>
                    <Column field="tglmasuk" header="Tanggal Masuk" style="min-width: 200px"></Column>
                    <Column field="tglkeluar" header="Tanggal Keluar" style="min-width: 200px"></Column>
                    <Column field="tglpulang" header="Tanggal Pulang" style="min-width: 200px"></Column>
                    <Column field="status" header="Status" style="min-width: 200px"></Column>
                </DataTable>
            </VCard>
        </div>
    </div>
    <Dialog v-model:visible="modalFilter" modal header="Filter" :style="{ width: '40vw' }">
        <div class="columns is-multiline">
            <!-- <div class="column is-6">
                <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                    <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.qInstalasi" :options="d_Departemen" :optionLabel="'namadepartemen'"
                            class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
                            @change="changeInst($event)" showClear />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                    <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.qRuangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                            class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                </VField>
            </div> -->
            <div class="column is-6">
                <VField label="Status Klaim" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                    <VControl icon="fas fa-credit-card" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.qStatus" :options="d_Status" :optionLabel="'status'" class="is-rounded"
                            placeholder="Status Klaim" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                </VField>
            </div>
        </div>
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="clearFilter()">
                Bersihkan
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:filter" :loading="isLoading"
                @click="terapkanFilter()"> Terapkan
            </VButton>
        </template>
    </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete'
import Dialog from 'primevue/dialog';
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Badge from 'primevue/badge';
import * as XLSX from "xlsx";
useHead({
    title: 'Laporan Monitoring Klaim - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let item: any = reactive({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const router = useRouter()
const dataSource: any = ref([])
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const d_Status: any = ref([])
const jmlFilter: any = ref(0)
const fetchDropdown = async () => {
    await useApi().get('/bridging/inacbgs/dropdown').then((r) => {
        d_Departemen.value = r.departemen
        d_Status.value = r.inacbg_status
        d_Status.value.push({inacbg_status: "belum_koding", status: 'Belum Koding'})
    })
    fetchData()
}
const fetchData = async () => {
    if (!item.periode) {
        H.alert("error", "Harap pilih tanggal terlebih dahulu !")
        return
    }
    const dari = H.formatDate(item.periode.start, 'YYYY-MM-DD')
    const sampai = H.formatDate(item.periode.end, 'YYYY-MM-DD')

    let search = ''
    ,ispulang = ''
    ,ruanganid = ''
    ,inacbg_status = ''
    ,depid = ''

    if(item.search)
        search = item.search

    if(item.ischecktglpulang)
        ispulang = item.ischecktglpulang

    jmlFilter.value = 0

    if(item.qInstalasi) {
        depid = item.qInstalasi.id
        jmlFilter.value += 1
    }

    if(item.qRuangan) {
        ruanganid = item.qRuangan.id
        jmlFilter.value += 1
    }

    if (item.qStatus) {
        inacbg_status = item.qStatus.inacbg_status
        jmlFilter.value += 1
    }

    isLoading.value = true
    dataSource.value = []
    const response = await useApi().get(
        '/bridging/klaim/data-history-kamar?tglawal='+ dari +'&tglakhir=' + sampai
        + '&search=' + search
        + '&ispulang=' + ispulang
        + '&ruanganid=' + ruanganid
        + '&depid=' + depid
        + '&inacbg_status=' + inacbg_status
    )
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });

    isLoading.value = false
    dataSource.value = response
}

const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any, index) => {
    return {
      No: e.no, NoSEP: e.nosep, Ruangan: e.namaruangan, kamarBed: e.kamar, TglMasuk: e.tglmasuk, TglKeluar: e.tglkeluar,
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

const remakeData: any = ref([])

const detailklaim = (data: any) => {
    router.push({
    name: 'module-klaim-asuransi-detail-klaim-bpjs',
        query: {
            nocmfk: data.nocmfk,
            norec_pd: data.norec_pd,
        }
    })
}
const changeInst = (e: any) => {
    d_Ruangan.value = e.value ? e.value.ruangan : []
}
const terapkanFilter = () => {
    fetchData()
    modalFilter.value = false
}
const clearFilter = () => {
    delete item.qRuangan
    delete item.qInstalasi
    fetchData()
    modalFilter.value = false
}

onMounted(() => {
    fetchDropdown()
})

</script>
<style lang="scss">
.text-center {
  justify-content: center;
  tect-align: center;
}
</style>
