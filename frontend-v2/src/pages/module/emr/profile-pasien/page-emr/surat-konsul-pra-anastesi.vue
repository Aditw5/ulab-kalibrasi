<template>
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

    <div class="column is-12">
        <VCard>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-6">
                    </div>
                    <div class="column is-6">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Kepada YTH :TS</h1>
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.namaAsisten" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari nama Pegawai" />
                            </VControl>
                        </VField>
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Di Tempat</h1>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: -5rem;"> Dengan Hormat </h1>
                    </div>
                    <div class="column is-12">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Mohon Konsul dan penatalaksanaan untukk
                            pasien dengan identitas seperti tertera pada label diatas : </h1>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Nama :</h1>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VControl>
                                <VInput type="text" class="input" placeholder="...." v-model="input.namaPasien" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Tanggal Lahir :</h1>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VDatePicker v-model="input.tglLahir" mode="date" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Diagnosa :</h1>
                    </div>
                    <div class="column is-6">
                        <VField>
                            <VControl>
                                <AutoComplete v-model="input.diagnosaPraBedah" :suggestions="d_Diagnosa"
                                    @complete="fetchDiagnosa($event)" :optionLabel="'label'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Diagnosa" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-12" style="overflow-y: auto;">
                    <table class="tg table-tg">
                        <tbody>
                            <tr>
                                <td style="width:55%">
                                    <table class="tg">
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">S
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.S" rows="5"
                                                            placeholder="Subjective...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">O
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.O" rows="5"
                                                            placeholder="Objective...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">A
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.A" rows="5" placeholder="Analysis...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="5%" style="width: 5%;font-weight: bold;vertical-align: middle;">P
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl>
                                                        <VTextarea v-model="input.P" rows="5" placeholder="Planning...">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Sifat Tindakan :</h1>
                    <div class="columns is-multiline">
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.sifatTindakanCyto" label="CYTO" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2 mb-3">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.sifatTindakanElektif" label="ELEKTIF" class="p-0"
                                        color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;">Waktu Operasi :</h1>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal </h1>
                            <VField>
                                <VDatePicker v-model="input.tglOperasi" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Jam</h1>
                            <VDatePicker v-model="input.jamOperasi" color="green" mode="time" is24hr>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:clock">
                                            <VInput :value="inputValue" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </div>
                    </div>
                </div>
                <div clas="mt-5">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                            <VField class="mt-3">
                                <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
                                    :max-date="new Date()">
                                    <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                                <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                    </template>
                                </VDatePicker>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                        </div>
                        <div class="column is-6">
                            <img v-if="input.dpjpPasien"
                                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpPasien.label ? input.dpjpPasien.label : input.dpjpPasien)">
                            <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                        </div>
                        <div class="column is-4">
                            <h1 class="p-0" style="font-weight: bold;">Dokter Penanggung Jawab Pasien</h1>
                            <VField>
                                <VControl class="prime-auto">
                                    <AutoComplete v-model="input.dpjpPasien" :suggestions="d_Dokter"
                                        @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                        :field="'label'" placeholder="DPJP IGG..." class="mt-2"
                                        @item-select="setTandaTangan($event, 'signature_1')" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
    </div>
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
// import Fieldset from 'primevue/fieldset';
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/laporan-operasi'

const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let laporan = ref(EMR.laporan())

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
const d_Pegawai: any = ref([])
const d_Diagnosa: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    jamOperasi: new Date(),
    tglOperasi: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const fetchDiagnosa = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/diagnosa_m?select=kddiagnosa,namadiagnosa&param_search=kddiagnosa&query=${filter.query}&limit=10`)
    d_Diagnosa.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_Ruangan.value = response
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
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
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
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
            NOREC_EMRPASIEN.value = response.norec_emr
            input.value.id = response.id
        }).catch((e: any) => {
            isLoading.value = false
        })
}

const kembaliKeun = () => {
    window.history.back()
}
const setAutoFill = async () => {
    input.value.tglPembuatan = new Date()
    input.value.dpjpPasien = props.registrasi.dokter
    input.value.namaPasien = props.pasien.namapasien
    input.value.tglLahir = props.pasien.tgllahir

    useApi().get(
        "emr/auto-fill-cppt?nocmfk=" + ID_PASIEN + "&norec_pd=" + props.registrasi.norec_pd +
        "&collection=CPPTDetail" + "&field=tgl,flag,S,P,O,uuid,nocmfk"
    ).then((response) => {
        if (response != null) {
            input.value.S = response.S
            input.value.P = response.P
            input.value.O = response.O
        }
    })

    useApi().get("diagnosa/riwayat-diagnosa-x-cppt?noregistrasi=" + props.registrasi.noregistrasi
    ).then((response) => {
        if (response != null) {
        let analysis = '';
        let count = 1; 
        response.data.forEach(element => {
            analysis += `${count}. ${element.kddiagnosa}, nama diagnosa = ${element.namadiagnosa}, jenis diagnosa = ${element.jenisdiagnosa}, keterangan = ${element.keterangan} \n`;
            count++;
        });
        analysis = analysis.slice(0, -2);
        input.value.A = ref(analysis);
      }
    })
}
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
.CPPT_HEIGHT {
    overflow: auto;
    height: 600px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    background-color: var(--white);
    border-color: var(--fade-grey-dark-2) !important;
}

.tg-card {
    background-color: #feffed;
}

.is-dark {
    .tg {
        background-color: var(--dark-sidebar-light-6)
    }

    .tg-card {
        background-color: var(--dark-sidebar-light-6)
    }
}

.tg td {
    border-color: var(--fade-grey-dark-2);
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg th {
    border-color: var(--fade-grey-dark-3) !important;
    border-style: solid;
    border-width: 1px;
    font-family: Arial, sans-serif;
    font-size: 14px;
    font-weight: normal;
    overflow: hidden;
    padding: 10px 5px;
    word-break: normal;
}

.tg .tg-0lax {
    text-align: left;
    vertical-align: top
}

.scroll-container-rev {
    height: 1000px;
    overflow: auto;
}

@media (max-width: 1144px) {

    .table-tg {
        width: 150%;
    }
}
</style>
