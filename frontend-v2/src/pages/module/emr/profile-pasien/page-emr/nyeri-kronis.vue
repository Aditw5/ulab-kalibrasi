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
                                <div class="column is-8" style="margin-top: -2rems;">
                                    <VField>
                                        <VControl>
                                            <VRadio v-model="input.NyeriKronis" value="Ya" label="Nyeri Kronis"
                                                name="NyeriKronis" color="info" square style="font-size: 15px" />

                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Diagnosa Keperawatan">
                                
                                <div class="column is-12">
                                    <h1> Definisi : pengalaman sensorik atau emosional yg berkaitan dengan kerusakan jaringan actual atau fungsional, dengan onset mendadak atau lambat dan berintensitas ringan hingga berat yang berlangsung lebih dari 3 bulan
                                    </h1>
                                </div>

                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Berhubungan dengan :</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in Hubungan">
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
                                    <h1 style="font-weight: bold;">Data Subjektif :</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-4" v-for="(data, i) in dataSubjektif">
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
                                    <h1 style="font-weight: bold;">Data Objektif:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-6" v-for="(data, i) in dataObjektif">
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
                            <Fieldset :toggleable="true" legend="Perencanaan Keperawatan">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Tujuan:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-4" v-for="(data, i) in Tujuan">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-6" style="margin-top:0.5rem">
                                            <h1> Setelah dilakukan tindakan keperawatan selama : </h1>
                                        </div>
                                        <div class="column is-6" style="margin-left: -5rem;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input"
                                                        placeholder="Lama Tindakan Keperawatan"
                                                        v-model="input.lamaTindakanKeperawatan" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                    <div class="column is-12 p-0">
                                    <div class="is-flex">
                                        <div class="column is-6" style="margin-top:0.5rem">
                                            <h1> diharapkan nyeri kronis teratasi ditandai dengan </h1>
                                        </div>
                                        <div class="column is-6" style="margin-left: -5rem;">
                                            <VField>
                                                <VControl>
                                                    <VInput type="text" class="input"
                                                        placeholder="Harapan"
                                                        v-model="input.harapan" />
                                                </VControl>

                                            </VField>
                                        </div>
                                    </div>
                                    </div>
                                   
                                    <div class="column is-12">
                                        <h1 style="font-weight: bold;">Kriteria Hasil</h1>
                                        <div class="columns is-multiline pt-3 pl-3">
                                            <div class="column is-6" v-for="(data, i) in KriteriaHasil">
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
                                        <h1 style="font-weight: bold;">Kontrol Nyeri</h1>
                                        <div class="columns is-multiline pt-3 pl-3">
                                            <div class="column is-6" v-for="(data, i) in KontrolNyeri">
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
                                        <h1 style="font-weight: bold;">Tingkat Nyeri</h1>
                                        <div class="columns is-multiline pt-3 pl-3">
                                            <div class="column is-6" v-for="(data, i) in TingkatNyeri">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                            :label="data.title" class="p-0" color="primary" square />
                                                    </VControl>
                                                </VField>
                                            </div>


                                        </div>
                                    </div>
                                
                                </div>

                            </Fieldset>
                        </div>
                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Rencana Tindakan">
                                <div class="column is-12">
                                    <h1 style="font-weight: bold;">Manajemen Nyeri:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-12" v-for="(data, i) in manajemenNyeri">
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
                                    <h1 style="font-weight: bold;">Peningkatan Koping:</h1>
                                    <div class="columns is-multiline pt-3 pl-3">
                                        <div class="column is-12" v-for="(data, i) in peningkatanKoping">
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
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/nyeri-kronis'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let Hubungan = ref(EMR.Hubungan())
let dataObjektif = ref(EMR.dataObjektif())
let dataSubjektif = ref(EMR.dataSubjektif())
let Tujuan = ref(EMR.Tujuan())
let KriteriaHasil = ref(EMR.KriteriaHasil())
let KontrolNyeri = ref(EMR.KontrolNyeri())
let TingkatNyeri = ref(EMR.TingkatNyeri())
let manajemenNyeri = ref(EMR.manajemenNyeri())
let peningkatanKoping = ref(EMR.peningkatanKoping())



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
const COLLECTION: any = ref('NyeriKronis') //table mongodb
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