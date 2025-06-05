
<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Laporan Tracer</h3>
                            </div>
                            <div class="right">
                                <div class="buttons">
                                    <VButton icon="lnir lnir-arrow-left rem-100"
                                        :to="{ name: 'module-registrasi-daftar-pembatalan-pasien' }" light dark-outlined>
                                        Cancel
                                    </VButton>
                                    <VButton type="button" icon="feather:search" :loading="isLoading" color="success" raised
                                        @click="cariRiwayat()"> Cari
                                    </VButton>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-body">
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="fieldset-heading">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;">Filter Pencarian</h1>
                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-6">
                                            <label style="margin-bottom: 0.5rem;">Tanggal</label>
                                            <div class="columns is-multiline mb-3 mt-2">
                                                <div class="column">
                                                    <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks
                                                        class="pt-2">
                                                        <template #default="{ inputValue, inputEvents }">
                                                            <VField addons>
                                                                <VControl icon="feather:calendar">
                                                                    <VInput :value="inputValue.start" class="input-calendar"
                                                                        v-on="inputEvents.start" />
                                                                </VControl>
                                                                <VControl>
                                                                    <VButton static><i class="fas fa-arrow-right"
                                                                            aria-hidden="true"></i></VButton>
                                                                </VControl>
                                                                <VControl icon="feather:calendar">
                                                                    <VInput :value="inputValue.end" class="input-calendar"
                                                                        v-on="inputEvents.end" />
                                                                </VControl>
                                                            </VField>
                                                        </template>
                                                    </VDatePicker>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-6">
                                            <label style="">Unit Asal
                                            </label>
                                            <VField>
                                                <VControl class="prime-auto">
                                                    <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan"
                                                        :optionLabel="'label'" @complete="fetchRuangan($event)"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="Ruangan..." class="mt-5" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-6">
                                                    <label style="margin-bottom: 0.5rem;" class="mb-2">No Rekam
                                                        Medis</label>
                                                    <VField>
                                                        <VControl class="mt-5">
                                                            <VInput type="text" placeholder="Nomer Rekam Medis"
                                                                v-model="input.norekammedis"
                                                                style="cursor:pointer text-align: center;" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <label style="margin-bottom: 0.5rem;" class="mb-2">Nama Pasien</label>
                                                    <VField>
                                                        <VControl class="mt-5">
                                                            <VInput type="text" placeholder="Nama Pasien"
                                                                v-model="input.namapasien"
                                                                style="cursor:pointer text-align: center;" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12 mt-5">
                                            <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised
                                                @click="exportExcel()"> Export to Excel </VButton>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!--Fieldset-->
                        <div class="form-fieldset">
                            <div class="column" v-if="isLoading">
                                <VPlaceloadWrap v-for="data in 10">
                                    <VPlaceload class="mx-2 mb-3" />
                                </VPlaceloadWrap>
                            </div>
                            <div class="column" v-else>
                                <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
                                    subtitle="Silakan gunakan filter lain" larger>
                                    <template #image>
                                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg"
                                            alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderPage>
                                <DataTable v-else :value="dataSource" class="p-datatable-sm" :loading="isLoading"
                                    :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable
                                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>
                                    <Column field="no" header="No"></Column>
                                    <Column field="nocm" header="No RM"></Column>
                                    <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px">
                                        <template #body="slotProps">
                                            <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                                        </template>
                                    </Column>
                                    <Column field="unitasal" header="Unit Asal"></Column>
                                    <Column field="tglregistrasi" header="Tgl Registrasi"></Column>
                                    <Column field="tglkeluar" header="Tgl Keluar"></Column>
                                    <Column field="selisih" header="Selisih Waktu Kembali"></Column>
                                </DataTable>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as XLSX from "xlsx";
import moment from 'moment'
const input: any = ref({})
let isLoading = ref(false)
const dataSource: any = ref([])
const date = ref(new Date())
const d_Ruangan: any = ref([]);
const remakeData: any = ref([]);
let isPlaceLoad: any = ref(false)
const item: any = ref({
    qFilterTgl: {
        start: new Date(),
        end: new Date()
    },
})


useHead({
    title: 'Laporan Diagnosa Pasien - ' + import.meta.env.VITE_PROJECT,
})
async function cariRiwayat() {
    isPlaceLoad.value = true;
    let object: any = {};
    object = input.value;
    let startDate = moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
    let endDate   = moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
    let ruangan   = input.value.ruangan ? input.value.ruangan.value : "";
    let namapasien = input.value.namapasien ?? "";
    let unitAsal = input.value.ruangan ?? "";

    isLoading.value = true;
    useApi().get(
        `/resgistrasi/get-laporan-tracer?tglAwal=${startDate}&tglAkhir=${endDate}&ruangan=${ruangan}&namapasien=${namapasien}&unitAsal=${unitAsal}`).then((response: any) => {
            response.data.forEach((element: any, i: any) => {
                element.no = i + 1
            });
            dataSource.value = response.data
            isLoading.value = false
            console.log(JSON.stringify(response))
        }).catch((e: any) => {
            isLoading.value = false
        })
}
const setAutoFill = () => {
    input.value.tglAwal = new Date()
    input.value.tglAkhir = new Date()
}

const convertDate = (date) => {
    const dateObject = new Date(date);
    const day = dateObject.getDate().toString().padStart(2, '0');
    const month = (dateObject.getMonth() + 1).toString().padStart(2, '0');
    const year = dateObject.getFullYear();
    const formattedDate = `${year}-${month}-${day}`;
    return formattedDate;
}

const fetchRuangan = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}

const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            No: e.no, NoRm: e.nocm, NamaPasien: maskNamaPasien(e.namapasien),
            unitAsal: e.unitasal
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
}
const maskNamaPasien = (nama: string): string => {
  if (!nama) return '';
  
  const parts = nama.split(' ');
  const maskedParts = parts.map((part) => {
    if (part.length <= 2) {
      return part; 
    }
    const first = part[0]; 
    const last = part[part.length - 1]; 
    const mask = '*'.repeat(part.length - 2); 
    return `${first}${mask}${last}`;
  });

  return maskedParts.join(' ');
};
setAutoFill();
cariRiwayat();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 1200px;
    margin: 0 auto;
}

.form-fieldset {
    padding: 10px 0;
    max-width: 100%;
    margin: 0 auto;
}

.table-pi {
    width: 1400px;
    border: 1px solid #929090;
}

.table-scroll {
    overflow-x: scroll;
}

.date {
    background-color: #9b9b9b;
    color: #fff;
}
</style>
