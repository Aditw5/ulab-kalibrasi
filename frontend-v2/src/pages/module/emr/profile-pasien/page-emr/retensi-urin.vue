<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" 
                             @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>
            <div v-if="!isStuck" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" 
                             @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <h1 class="pt-5" style="font-weight: bold;">Tanggal</h1>
                                <div class="column is-6 pl-5">
                                    <VField>
                                        <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                                            :max-date="new Date()">
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
                            <div class="column is-8 p-0" style="margin-top: -2rem;">
                                    <VField>
                                        <VControl>
                                            <VRadio v-model="input.retensiUrin" value="Ya" label="Retensi Urin (0050)"
                                                name="retensiUrin" color="info" square style="font-size: 15px" />

                                        </VControl>
                                    </VField>
                                    
                                </div>
                                <div class="column is-8 p-0" style="margin-top: -1rem;">
                                    <VField>
                                        <VControl>
                                            <VRadio v-model="input.DiagnosaKeperawatan" value="Ya" label="Diagnosa Keperawatan"
                                                name="DiagnosaKeperawatan" color="info" square style="font-size: 15px" />

                                        </VControl>
                                    </VField>
                                </div>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Mayor">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Data Subjektif :</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in subjektifMayor">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                               
                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Minor">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Data Subjektif:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6">
                                                <VField>
                                                    <VControl>
                                                        <VInput type="text" class="input"
                                                            placeholder="Data Subjektif"
                                                            v-model="input.dataSubjektif" />
                                                    </VControl>

                                                </VField>
                                            </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Data Objektif:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in objektifMinor">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
            
                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Outcome">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Fliminasi Urin:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in fliminasiUrin">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Skala:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in skala">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                              
                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Intervensi">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Kateterisasi Urin :</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-12" v-for="(data, i) in kateterasasiUrin">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div> 
                            </Fieldset>
                        </div>

                    </div>
                </div>
            </VCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/retensi-urin'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let subjektifMayor = ref(EMR.subjektifMayor())
let objektifMinor = ref(EMR.objektifMinor())
let fliminasiUrin = ref(EMR.fliminasiUrin())
let skala = ref(EMR.skala())
let kateterasasiUrin = ref(EMR.kateterasasiUrin())


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
    tanggal : new Date()
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
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
setView()
loadRiwayat()
</script>