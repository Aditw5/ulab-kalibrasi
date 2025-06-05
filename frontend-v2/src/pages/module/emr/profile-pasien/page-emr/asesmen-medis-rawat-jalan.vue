<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>{{ props.FORM_NAME }}</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                    @click="print()"> Cetak
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="A. Anamnesis (S)">

                                        <div class="column is-12 pl-0 pr-0">
                                            <div class="column is-12">
                                                <div class="columns">
                                                    <div class="column" v-for="(pilihan) in anamnesa">
                                                        <h1 class="mb-3 emr">{{ pilihan.title }}</h1>
                                                        <VField>
                                                            <VControl>
                                                                <VTextarea v-model="input[pilihan.model]" rows="3">
                                                                </VTextarea>
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-12 pl-0 pr-0">
                                            <h2 style="font-size: medium;font-weight: 600;">Faktor Risiko Penyakit Jantung
                                            </h2>
                                            <hr>
                                            <div class="column is-8" v-for="(datas) in listFaktorResJantung">
                                                <h1 class="emr">{{ datas.title }}</h1>
                                                <div class="columns is-multiline pt-3">
                                                    <div class="column is-one-quarter" v-for="(data) in datas.detail">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[data.model]"
                                                                    :true-value="data.label" :label="data.label"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="columns pl-3 mt-1">
                                                <div class="column is-3">
                                                    <h1 class="emr">Merokok</h1>
                                                    <div class="is-flex">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.merokok" true-value="Ya"
                                                                    label="Ya (Berapa pack/hari)" color="danger" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column is-5 pt-5 mt-4">
                                                    <VControl>
                                                        <input v-model="input.ketMerokok" type="text" class="input" />
                                                    </VControl>
                                                </div>
                                                <div class="column pt-5 mt-3">
                                                    <div class="is-flex">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input.merokok" true-value="Tidak"
                                                                    label="Tidak" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="column is-12">
                                                <h1 class="mb-3 emr">Riwayat Pengobatan</h1>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.riwayatPengobatan" rows="3">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>

                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="B. Pemeriksaan Fisik (O)">
                                        <div class="column is-10" v-for="(data) in keadaanUmum">
                                            <h1 class="mb-3 emr">{{ data.title }}</h1>
                                            <div class="columns">
                                                <div class="column is-3" v-for="(pilihan) in data.value">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]" :true-value="pilihan"
                                                                :label="pilihan" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-12" v-for="(data) in pemeriksaanFisik">
                                            <h1 class="mb-3 emr">{{ data.title }}</h1>
                                            <div class="columns p-3">
                                                <div class="column" v-for="(value) in data.value">
                                                    <VField>
                                                        <h1 style="font-weight: bold;" class="mb-2">{{ value.subTitle }}
                                                        </h1>
                                                        <VControl>
                                                            <input v-model="input[value.model]" class="input"
                                                                :placeholder="value.subTitle + '...'" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-12">
                                            <Fieldset :toggleable="true" legend="4. Kesadaran">
                                                <div class="columns p-3">
                                                    <div class="column is-6 pb-0">
                                                        <div class="columns">
                                                            <div class="column is-1">
                                                                <h1 class="mb-3 emr" style="text-align: center;">No</h1>
                                                            </div>
                                                            <div class="column is-one-quarter" style="text-align: center;">
                                                                <h1 class="mb-3 emr">Parameter</h1>
                                                            </div>
                                                            <div class="column is-5" style="text-align: center;">
                                                                <h1 class="mb-3 emr">Nilai</h1>
                                                            </div>
                                                        </div>
                                                        <div class="columns pb-4" v-for="(data) in kesadaran">
                                                            <div class="column is-1 pt-0 " style="text-align: center;">
                                                                <h1 class="mb-3 emr"> {{ data.no }}</h1>
                                                            </div>
                                                            <div class="column  is-one-quarter pt-0"
                                                                style="text-align: center;">
                                                                <h1 class="mb-3 emr">{{ data.parameter }}</h1>
                                                            </div>
                                                            <div class="column is-5" style="text-align: end;">
                                                                <div class="columns is-multiline pb-5">
                                                                    <div class="column is-4 pt-0"
                                                                        v-for="(pilihan) in data.nilai">
                                                                        <VField>
                                                                            <VControl raw subcontrol>
                                                                                <VCheckbox class="p-0"
                                                                                    :true-value="pilihan.poin"
                                                                                    v-model="input[pilihan.model]"
                                                                                    :label="pilihan.poin" color="primary" />
                                                                            </VControl>
                                                                        </VField>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-6">
                                                        <VCard>
                                                            <p>KETERANGAN</p>
                                                            <div class="column p-0" v-for="(data) in descRangeKesadaran">
                                                                <VField>
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox class="pb-0" :true-value="data.value"
                                                                            v-model="input[data.model]" :label="data.des"
                                                                            color="primary" disabled />
                                                                    </VControl>
                                                                </VField>
                                                            </div>
                                                        </VCard>
                                                    </div>
                                                </div>
                                                <div class="column is-4 pt-0" style="margin-left: auto;">
                                                    <VField>
                                                        <h1 style="font-weight: bold;" class="mb-2">JUMLAH TOTAL</h1>
                                                        <VControl>
                                                            <input v-model="input.totalKesadaran" class="input"
                                                                placeholder="Jumlah" disabled />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VControl>
                                                            <VTextarea v-model="input.keteranganKesadaran" rows="3">
                                                            </VTextarea>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </Fieldset>
                                        </div>

                                        <div class="column is-12" v-for="(datas) in keadaanUmum2">
                                            <h1 style="font-weight: bold;" class="">{{ datas.title }}</h1>
                                            <div class="column is-12" v-for="(data) in datas.value">
                                                <h1 style="font-weight: bold;" class="ml-3">{{ data.subTitle }}</h1>
                                                <div class="columns is-multiline" style="padding: 10px 20px; !important">
                                                    <div class="column"  v-for="(choice) in data.item" :class="choice.label == 'Ronki' || choice.label == 'Wheezing' ? 'is-2' : 'is-one-quarter'" >
                                                        <VField  v-if="choice.model != 'auskultasiKetRonki' && choice.model != 'auskultasiKetWheezing' ">
                                                            <VControl>
                                                                <VCheckbox v-model="input[choice.model]" 
                                                                    :true-value="choice.label" :label="choice.label"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                        <VField v-else>
                                                            <VControl>
                                                                <input v-model="input[choice.model]" class="input" />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column is-6 p-0 ml-5 mr-5" v-for="(optioanl) in data.optional">
                                                    <VField>
                                                        <VControl>
                                                            <input v-model="input[optioanl.model]" class="input" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="column is-12">
                                            <h1 class="mb-3 emr">15. Leher</h1>
                                            <div class="column is-8">
                                                <VField>
                                                    <VControl icon="feather:bookmark">
                                                        <VInput v-model.number="input.leher" placeholder="Tulis Angka " />
                                                    </VControl>
                                                </VField>
                                                <Slider v-model="input.leher" />
                                            </div>
                                        </div>

                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="C. Status Lokalis">
                                        <div class="columns is-multiline pl-5">
                                            <div class="column is-3">
                                                <img src="/images/emr/asesmen_medis.png" style="width: 12rem !important;" />
                                            </div>
                                            <div class="column is-6" style="margin-top: 4rem">
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.statusLokalis" rows="3">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>

                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="D. Pemeriksaan Penunjang">
                                        <div class="column" v-for="(data) in pemeriksaanPenunjang">
                                            <div class="columns is-multiline pl-5 pr-5">
                                                <div class="column is-one-quarter">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[data.model]" :true-value="data.value"
                                                                :label="data.title" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                                <div class="column">
                                                    <VControl>
                                                        <input v-model="input[data.desc]" class="input" />
                                                    </VControl>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column pl-5 pr-5 ml-2 mr-2">
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.pemeriksaanPenunjang" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="E. Diagnosa">
                                        <div class="column is-12">
                                            <VTabs selected="icd9" :tabs="[
                                                { label: 'Diagnosa ICD 9', value: 'icd9' },
                                                { label: 'Diagnosa ICD 10', value: 'icd10' },
                                            ]">
                                                <template #tab="{ activeValue }">
                                                    <p v-if="activeValue === 'icd9'">
                                                        <DataTable :value="dataSourceICD9" class="p-datatable-sm"
                                                            :loading="isLoading" :paginator="true" :rows="5"
                                                            :rowsPerPageOptions="[5, 10, 25]" selectionMode="single"
                                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                                            showGridlines>

                                                            <Column field="no" header="#"></Column>
                                                            <Column field="noregistrasi" header="No Registrasi"></Column>
                                                            <Column field="kddiagnosatindakan" header="Kode ICD 9"></Column>
                                                            <Column field="namadiagnosatindakan" header="Nama ICD 9">
                                                            </Column>
                                                            <Column field="namaruangan" header="Rungan"></Column>
                                                            <Column field="tglInput" header="Tgl Input"></Column>
                                                        </DataTable>
                                                    </p>
                                                    <p v-else-if="activeValue === 'icd10'">
                                                        <DataTable :value="dataSourceICD10" class="p-datatable-sm"
                                                            :loading="isLoading" :paginator="true" :rows="5"
                                                            :rowsPerPageOptions="[5, 10, 25]" selectionMode="single"
                                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                                            showGridlines>

                                                            <Column field="no" header="#"></Column>
                                                            <Column field="noregistrasi" header="No Registrasi" />
                                                            <Column field="jenisdiagnosa" header="Jenis Diagnosa" />
                                                            <Column field="kddiagnosa" header="Kode ICD 10" />
                                                            <Column field="namadiagnosa" header="Nama ICD 10" />
                                                            <Column field="namaruangan" header="Ruangan" />
                                                            <Column field="tglInput" header="TGL Input" />
                                                        </DataTable>
                                                    </p>
                                                </template>
                                            </VTabs>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" v-for="(datas) in dateAndDescrip" :legend="datas.name"
                                        class="mb-5">
                                        <div class="column is-12" v-for="(data) in datas.detail">
                                            <h1 style="font-weight: bold;" class="mb-2">{{ data.title }}</h1>
                                            <div class="column is-one-quarter p-0">
                                                <VField>
                                                    <VDatePicker v-model="input[data.model]" mode="dateTime"
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

                                            <div class="column is-8 p-0" v-for="(deskripsi) in data.descrip">
                                                <h1 style="font-weight: bold;" class="mb-2 mt-3">{{ deskripsi.subTitle }}
                                                </h1>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input[deskripsi.model]" rows="3">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>
                                
                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="H. Prognosis">
                                        <div class="columns is-multiline p-3">
                                            <div class="column is-one-quarter" v-for="(data) in prognosis">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input.prognosis" :true-value="data"
                                                                :label="data" color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-6" style="margin-left: auto;">
                                    <VCard class="border-card pink">
                                        <div class="column is-8 Sp-0">
                                            <VField>
                                                <h1 class="mb-3 emr">Bandung, Tanggal dan Jam</h1>
                                                <VDatePicker v-model="input.waktuSimpan" mode="dateTime" style="width: 100%"
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
                                        <div class="column pt-0">
                                            <h1 class="mb-3 emr">Penanggung Jawab Pasien</h1>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.penangungJawab" :suggestions="d_Dokter"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik nama Dokter" />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </VCard>
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
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import * as H from '/@src/utils/appHelper'
import * as EMR from '../page-emr-plugins/asesmen-medis-rj'
import AutoComplete from 'primevue/autocomplete';
import Slider from 'primevue/slider';
import Fieldset from 'primevue/fieldset';

useHead({
    title: 'Asesmen Awal - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)


const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: props.registrasi.norec_apd,
    RUANGAN_LAST: props.registrasi.objectruanganlastfk,
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    date: {
        tanggal: new Date,
        jam: new Date
    },
    airway: [],
    disability: []

})

const COLLECTION: any = ref('AsesmenMedisRawatJalan') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_ko: any = ref('')
const input: any = ref({
    waktuTataLaksana: new Date,
    waktuKontrol: new Date,
})
const d_Dokter: any = ref([])
const dataSourceICD9: any = ref([])
const dataSourceICD10: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)


// ==================== Start List Data =================
const anamnesa = ref(EMR.anamnesa())
const listFaktorResJantung = ref(EMR.faktorResJantung())
const keadaanUmum = ref(EMR.keadaanUmum_1())
const pemeriksaanFisik = ref(EMR.pemeriksaanFisik())
const kesadaran = ref(EMR.kesadaran())
const descRangeKesadaran = ref(EMR.dscRangeKesadaran())
const keadaanUmum2 = ref(EMR.keadaanUmum_2())
const pemeriksaanPenunjang = ref(EMR.penunjang())
const dateAndDescrip = ref(EMR.dateAndDescrip())
const prognosis = ref(EMR.prognosis())


// ==================== End List Data ==================

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd9?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD9.value = response
        // console.log(response)
    })
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
    })
}

const loadRiwayat = () => {

    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {

    let ID = input.id ? input.id : ''

    let object: any = {}

    object = input.value
    object.nocm = pasien.value.nocm

    object.pasien = H.setObjectPasien(pasien.value)
    object.registrasi = H.setObjectRegistrasi(pasien.value.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'url_form': props.FORM_URL,
        'name_form': props.FORM_NAME,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    // console.log(json)

    // // isLoading.value = true
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
            // NOREC_EMRPASIEN.value = response.norec_emr
        }).catch((e: any) => {
            isLoading.value = false
        })

    // console.log(resultValue)
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const countRangeNilai = (e: any) => {

    let cmc = {
        "keterangan": "CMC (14-15)",
        "poin": 15
    }
    let apatis = {
        "keterangan": "Apatis (12-13)",
        "poin": 13
    }
    let somnolen = {
        "keterangan": "Somnolen (10-11)",
        "poin": 11
    }
    let delirium = {
        "keterangan": "Delirium (7-9)",
        "poin": 9
    }
    let stupar = {
        "keterangan": "Stupar (4-6)",
        "poin": 6
    }
    let koma = {
        "keterangan": "Koma ( <= 3)",
        "poin": 3
    }

    descRangeKesadaran.value.forEach((elements: any) => {
        if (e <= 3 && e <= elements.value.poin) {
            input.value.rangeKesadaran = koma
        }
        else if (e <= 6 && e <= elements.value.poin) {
            input.value.rangeKesadaran = stupar
        }
        else if (e <= 9 && e <= elements.value.poin) {
            input.value.rangeKesadaran = delirium
        }
        else if (e <= 11 && e <= elements.value.poin) {
            input.value.rangeKesadaran = somnolen
        }
        else if (e <= 13 && e <= elements.value.poin) {
            input.value.rangeKesadaran = apatis
        }
        else if (e > 13 && e > elements.value.poin) {
            input.value.rangeKesadaran = cmc
        }
    })
}
const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.IMT = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.frekuensiNadi = response.nadi
        input.value.suhu = response.suhu
        input.value.tekananDarah = response.tekananDarah
        input.value.frekuensiNafas = response.pernapasan

        console.log()
    })
}

const print = async () => {
    H.printBlade(`emr/cetak-asesmen-medis-rj?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}



getDataExist()
diagnosa()
fetchPasien()
loadRiwayat()

watch(() => [
    input.value.kesadaranE,
    input.value.kesadaranM,
    input.value.kesadaranV,
    input.value.totalKesadaran,
    // input.value.point2
], () => {
    let poin1 = input.value.kesadaranE ? parseInt(input.value.kesadaranE) : 0
    let poin2 = input.value.kesadaranM ? parseInt(input.value.kesadaranM) : 0
    let poin3 = input.value.kesadaranV ? parseInt(input.value.kesadaranV) : 0
    const jumlahNilai = poin1 + poin2 + poin3
    countRangeNilai(jumlahNilai)
    input.value.totalKesadaran = jumlahNilai
})

</script>


<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/asesmen-awal.scss';

.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.p-fieldset-legend {
    margin-left: 14px;
}

.p-fieldset .p-fieldset-content {
    background: none;
}

.checkbox.is-outlined {
    padding: unset !important;
}

// .p-fieldset.p-component{
//     border-left: ;
// }

h1.emr {
    font-weight: bold;
}

table.assesment {
    border-collapse: collapse;
    width: 100%;
}


.assesment th {
    text-align: center !important;
    border-bottom: 1px solid black;
    // border: 1px solid black;
}


.assesment th,
td {
    padding: 8px;
    vertical-align: middle !important;
}

hr {
    background-color: hsl(0deg 6.81% 88.68%);
    border: none;
    display: block;
    height: 2px;
    margin: 1rem 0;
}
</style>
