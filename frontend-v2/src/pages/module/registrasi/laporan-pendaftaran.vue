
<template>
    <div class="page-content-inner">
        <div class="is-navbar">
            <div class="form-layout">
                <div class="form-outer">
                    <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                        <div class="form-header-inner">
                            <div class="left">
                                <h3>Laporan Pasien Daftar</h3>
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
                                        <div class="column is-12">
                                            <div class="columns is-multiline mb-3 mt-2">
                                                <div class="column is-6">
                                                    <label style=" margin-bottom: 2rem;">Tanggal Awal
                                                    </label>
                                                    <VField class="mt-3">
                                                        <VDatePicker v-model="input.tglAwal" mode="date" style="width: 100%"
                                                            trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                                <div class="column is-6">
                                                    <label style=" margin-bottom: 2rem;">Tanggal Akhir
                                                    </label>
                                                    <VField class="mt-3">
                                                        <VDatePicker v-model="input.tglAkhir" mode="date"
                                                            style="width: 100%" trim-weeks :max-date="new Date()">
                                                            <template #default="{ inputValue, inputEvents }">
                                                                <VField>
                                                                    <VControl icon="feather:calendar" fullwidth>
                                                                        <VInput :value="inputValue" placeholder="Tanggal"
                                                                            v-on="inputEvents" />
                                                                    </VControl>
                                                                </VField>
                                                            </template>
                                                        </VDatePicker>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-4">
                                                    <label style=" margin-bottom: 0.5rem;">Departement
                                                    </label>
                                                    <VField>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="input.departement"
                                                                :suggestions="d_Departement" :optionLabel="'label'"
                                                                @complete="fetchDepartement($event)" :dropdown="true"
                                                                :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Departement..." class="mt-2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <label style=" margin-bottom: 0.5rem;">Ruangan
                                                    </label>
                                                    <VField>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="input.ruangan" :suggestions="d_Ruangan"
                                                                :optionLabel="'label'" @complete="fetchRuangan($event)"
                                                                :dropdown="true" :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Ruangan..." class="mt-2" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-4">
                                                    <label style=" margin-bottom: 0.5rem;">Kelompok Pasien
                                                    </label>
                                                    <VField>
                                                        <VControl class="prime-auto">
                                                            <AutoComplete v-model="input.kelompokpasien"
                                                                :suggestions="d_KelompokPasien" :optionLabel="'label'"
                                                                @complete="fetchKelompokPasien($event)" :dropdown="true"
                                                                :minLength="3" :appendTo="'body'"
                                                                :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                                placeholder="Kelompok Pasien..." class="mt-2" />
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
                                <VPlaceholderPage v-if="d_Pendaftaran.length == 0" title="Data Tidak di Temukan."
                                    subtitle="Silakan gunakan filter lain" larger>
                                    <template #image>
                                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg"
                                            alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderPage>
                                <div v-else>
                                    <div class="column mt-5">
                                        <div class="table-scroll">
                                            <DataTable :value="d_Pendaftaran" :paginator="true" :rows="10"
                                                :rowsPerPageOptions="[10, 20, 30, 40, 50]"
                                                class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
                                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
                                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                                :loading="isLoading">
                                                <Column field="no" header="No"  style="min-width: 50px; text-align:center;"/>
                                                <Column field="tglregistrasi" header="Tanggal Registrasi"  style="min-width: 100px"/>
                                                <Column field="jammasuk" header="Jam Masuk"  style="min-width: 100px ;text-align:center;"/>
                                                <Column field="nocm" header="NoCm" style="min-width: 100px;text-align:center;"/>
                                                <Column field="noregistrasi" header="No Registrasi" style="min-width: 120px;text-align:center;"/>
                                                <Column field="namapasien" header="Pasien" frozen :sortable="true" style="min-width: 300px">
                                                <template #body="slotProps">
                                                    <span>{{ maskNamaPasien(slotProps.data.namapasien) }}</span>
                                                </template>
                                                </Column>
                                                <Column field="ruangandaftar" header="Unit Layanan" style="min-width: 200px"/>
                                                <Column field="kelompokpasien" header="Tipe Pasien" style="min-width: 100px"/>
                                                <Column field="namarekanan" header="Penjamin" style="min-width: 150px"/>
                                                <Column field="tglLahir" header="Tgl Lahir" style="min-width: 150px"/>
                                                <Column field="umur" header="Umur" style="min-width: 50px;text-align:center;"/>
                                                <Column field="kabupaten" header="Kota/Kabupaten" style="min-width: 120px"></Column>
                                                <Column field="kecamatan" header="Kecamatan" style="min-width: 120px"></Column>
                                                <Column field="alamatlengkap" header="Alamat" style="min-width: 300px"/>
                                                <Column field="dokterpj" header="Dokter" style="min-width: 300px"/>
                                                <Column field="statuspasien" header="Status Pasien" style="min-width: 100px;text-align:center;"></Column>
                                                <Column field="tglPulang" header="TGL Pulang" style="min-width: 150px"/>
                                                <Column field="petugas" header="Petugas Registrasi" style="min-width: 300px"/>
                                                <!-- <Column field="tglmasuk" header="Tgl Masuk"></Column>
                                                <Column field="jammasuk" header="Jam Masuk"></Column>
                                                <Column field="noregistrasi" header="No Registrasi"></Column>
                                                <Column field="namapasien" header="Nama Pasien"></Column>
                                                <Column field="ruangandaftar" header="Unit Layanan"></Column>
                                                <Column field="kelompokpasien" header="Tipe Pasien"></Column>
                                                <Column field="penjamin" header="Penjamin"></Column>
                                                <Column field="jk" header="JK"></Column>
                                                <Column field="kecamata" header="Kecamatan"></Column>
                                                <Column field="kabupaten" header="Kota/Kabupaten"></Column>
                                                <Column field="alamatlengkap" header="Alamat"></Column>
                                                <Column field="tgllahir" header="Tgl Lahir"></Column>
                                                <Column field="umur" header="Umur"></Column>
                                                <Column field="namakelas" header="Kelas"></Column>
                                                <Column field="dokterpj" header="Dokter"></Column>
                                                <Column field="diagnosamasuk" header="Diagnosa Masuk"></Column>
                                                <Column field="tglpulang" header="Tgl Keluar"></Column>
                                                <Column field="jampulang" header="Jam Keluar"></Column> -->
                                            </DataTable>
                                        </div>
                                    </div>
                                </div>
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
import moment from 'moment'
const input: any = ref({})
const d_Departement: any = ref([]);
const d_Ruangan: any = ref([]);
const d_KelompokPasien: any = ref([]);
let isLoading = ref(false)
const d_Pendaftaran: any = ref([])
const dataSourceICD9 = ref([])
const remakeData: any = ref([])
import * as XLSX from "xlsx";


useHead({
    title: 'Laporan Pendaftaran - ' + import.meta.env.VITE_PROJECT,
})
async function cariRiwayat() {
    let object: any = {}

    object = input.value

    isLoading.value = true;
    let startDate = moment(object.tglAwal).format('YYYY-MM-DD');
    let endDate = moment(object.tglAkhir).format('YYYY-MM-DD');
    let kelompokpasien = input.value.kelompokpasien ? input.value.kelompokpasien.value : "";
    let ruangan = input.value.ruangan ? input.value.ruangan.value : "";
    let departement = input.value.departement ? input.value.departement.value : "";
    useApi().get(
        `/laporan/pendaftaran?tglAwal=${startDate}&tglAkhir=${endDate}&departement=${departement}&ruangan=${ruangan}&kelompokpasien=${kelompokpasien}`).then((response: any) => {
            response.forEach((element:any,i:any) => {
                element.no = i+1
                element.tglLahir = H.formatDateToLocalString(element.tgllahir)
                element.tglPulang =  element.tglpulang != null ? H.formatDateIndoSimple(element.tglpulang) : ''
            });
            d_Pendaftaran.value = response
            isLoading.value = false
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
const fetchDepartement = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/departemen_m?select=id,namadepartemen&param_search=namadepartemen&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Departement.value = response
    })
}
const fetchRuangan = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Ruangan.value = response
    })
}
const fetchKelompokPasien = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const exportExcel = () => {
    remakeData.value = d_Pendaftaran.value.map((e: any) => {
        return {
            TGLMasuk: e.tglmasuk, JamMasuk: e.jammasuk, NORegistrasi: e.noregistrasi,
            KelompokPasien: e.kelompokpasien, StatusPasien: e.statuspasien, Penjamin: e.penjamin, JenisKelamin: e.jk,
            Kecamatan: e.kecamatan, Kabupaten: e.kabupaten, Alamat: e.alamatlengkap, TGLLahir: e.tgllahir,
            Umur: e.umur, NamaKelas: e.namakelas, Alamat: e.alamatlengkap, Dokter: e.dokterpj,
            Diganosa: e.diagnosamasuk, TGLPulang: e.tglpulang, JamPulang: e.jampulang,Petugas: e.petugas
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
cariRiwayat();
setAutoFill();
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';

.form-layout {
    max-width: 1300px;
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
