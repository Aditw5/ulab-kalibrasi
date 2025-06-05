<template>
    <div>
        <div class="form-layout is-stacked-2">
            <div class="form-outer" style="margin-top:15px">
                <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Hasil Pemeriksaan MCU</h3>
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
                                <div class="column is-3">
                                    <h1 class="emr mb-3">Tanggal Pemeriksaan</h1>
                                    <VDatePicker v-model="input.tanggalPemeriksaan" mode="dateTime" style="width: 100%"
                                        trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar" fullwidth>
                                                    <VInput :value="inputValue" placeholder="Tanggal Pemeriksaan"
                                                        v-on="inputEvents" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Anamnesis" :toggleable="true">
                                        <div class="column is-8" v-for="(data) in listAnamnesis">
                                            <VField>
                                                <p class="mb-1">{{ data.title }}</p>
                                                <VControl>
                                                    <VTextarea v-model="input[data.model]" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Pemeriksaan Fisik" :toggleable="true">
                                        <div class="column is-12" v-for="(data) in pemeriksaanFisik">
                                            <h1 class="emr">{{ data.title }}</h1>
                                            <div class="columns is-multiline" v-if="data.type == 'textfield'">
                                                <div class="column is-one-quarter" v-for="(value) in data.detail">
                                                    <p class="mb-1">{{ value.subTitle }}</p>
                                                    <VField addons>
                                                        <VControl expanded>
                                                            <VInput type="text" class="input" :placeholder="value.subTitle"
                                                                v-model="input[value.model]" />
                                                        </VControl>
                                                        <VControl class="field-addon-body">
                                                            <VButton static>cc</VButton>
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-else-if="data.type == 'textbox'">
                                                <div class="column is-6" v-for="(value) in data.detail">
                                                    <VField>
                                                        <p class="pb-2">{{ value.subTitle }}</p>
                                                        <VControl>
                                                            <input v-model="input[value.model]" type="text" class="input" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column is-12 pb-0" v-for="(value) in data.detail" v-else>
                                                <h1 class="mb-2">{{ value.subTitle }}</h1>
                                                <VControl>
                                                    <input v-model="input[value.model]" type="text" class="input" />
                                                </VControl>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Pemeriksaan Penunjang" :toggleable="true">
                                        <div class="column is-8" v-for="(data) in pemeriksaanPenunjang ">
                                            <h1 class="emr pb-2">{{ data.title }}</h1>
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input[data.model]" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Kesimpulan" :toggleable="true">
                                        <div class="column is-5 pt-0">
                                            <h1 class="mb-3 emr">Hasil MCU</h1>
                                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.hasilMCU" :suggestions="d_hasilMCU"
                                                        @complete="fetchHasilMCU($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik Hasil MCU" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.kesimpulan" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset class="p-fieldsets" legend="Saran" :toggleable="true">
                                        <div class="column is-8">
                                            <VField>
                                                <VControl>
                                                    <VTextarea v-model="input.saran" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <div class="columns is-multiline">
                                        <div class="column is-4">
                                            <VCard class="boreder">
                                                <VField>
                                                    <h1 class="mb-3 emr">Bandung, Tanggal dan Jam</h1>
                                                    <VDatePicker v-model="input.waktuSimpan" mode="dateTime"
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
                                            </VCard>
                                        </div>

                                        <div class="column is-5" style="margin-left: auto;">
                                            <VCard>
                                                <h1 class="mb-3 emr" style="text-align: center;">Dokter Pemeriksa MCU</h1>
                                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                                <VControl icon="feather:search">
                                                    <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter"
                                                        @complete="fetchDokter($event)" :optionLabel="'label'"
                                                        :dropdown="true" :minLength="3" :appendTo="'body'"
                                                        :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                        placeholder="ketik Nama Dokter Pemeriksa" />
                                                </VControl>
                                            </VField>
                                            </VCard>
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
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/hasil-pemeriksaan-mcu'
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as H from '/@src/utils/appHelper'

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
const items = ref([]);
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

const COLLECTION: any = ref('HasilPemeriksaanMCU') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const value = ref("");
const d_Dokter: any = ref([])
const d_hasilMCU: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => {
    return y.value > 30
})
const isLoading = ref(false)


// ==================== Start List Data =================

const listAnamnesis = ref(EMR.anamnesis())

const pemeriksaanFisik = ref(EMR.pemeriksaanFisik())

const pemeriksaanPenunjang = ref(EMR.pemeriksaanPenunjang())


// ==================== End List Data ==================

const loadRiwayat = async () => {

    await useApi().get(
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
    useApi().post(
        `/emr/simpan-emr`, json).then((response: any) => {
            isLoading.value = false
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

const fetchHasilMCU = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/hasilmcu_m?select=id,hasilmcu&param_search=hasilmcu&query=${filter.query}&limit=10`
    ).then((response) => {
        d_hasilMCU.value = response
    })
}

const kembaliKeun = () => {
    window.history.back()
}
const fetchPasien = () => {
    pasien.value = props.pasien
    pasien.value.registrasi = props.registrasi
    NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const getDataExist = async () => {
    await useApi().get(`emr/get-data-exist?nocmfk=${ID_PASIEN}`).then((response) => {
        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.imt = response.IMT
        input.value.lingkarPerut = response.lingkarPerut
        input.value.frekuensiNadi = response.nadi
        input.value.suhu = response.suhu
        input.value.tekananDarah = response.tekananDarah
        input.value.frekuensiNafas = response.pernapasan

        console.log()
    })
}

const print = async () => {
    H.printBlade(`emr/cetak-hasil-pemeriksaan-mcu?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}



getDataExist()
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
