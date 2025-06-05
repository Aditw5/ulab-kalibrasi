<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
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
                            <div class="columns is-multiline pt-3 pl-3">
                                <div class="column is-3" v-for="(data, i) in general">
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
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Physical Therapist</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input.PhysicalTerapist"
                                                placeholder="Physical Therapist" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Hospital/ Clinic</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input.hospital" value="RSJP Paramarta" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Date</h1>
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
                        </div>

                    </div>
                </div>
            </VCard>
        </div>
        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Anamneses">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Name</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.namaPasien" placeholder="Nama Lengkap" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Date of birth</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.tanggalLahirPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">No Medical Record</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.norm" />
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Address</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.alamatPasien" placeholder="Nama Lengkap" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Occupation</h1>
                            <VField>
                                <VControl>
                                    <VInput type="text" v-model="input.pekerjaanPasien" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Date</h1>
                            <VField>
                                <VDatePicker v-model="input.tanggalAsesmen" mode="dateTime" style="width: 100%" trim-weeks
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
                </div>
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Reason for Consultation</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.reason" rows="2" placeholder="Reason for Consultation...">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <h1 style="font-weight: bold; margin-bottom: 1rem;">Medical/ Therapy History</h1>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.terapiHistori" rows="2"
                                        placeholder="Medical/ Therapy History...">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <Vcard>
                        <div class="columns is-multiline pl-5">
                            <div class="column is-3">
                                <img src="/images/emr/asesmen_medis.png" style="width: 12rem !important;" />
                            </div>

                            <div class="column is-6">
                                <div class="column is-12">
                                    <div class="columns is-multiline pt-3 pl-3">

                                        <div class="column is-3" v-for="(data, i) in lokalis">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" square />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.statusLokalis" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </Vcard>

                </div>
            </Fieldset>
        </div>
        <div class="column is-12">
            <Fieldset :toggleable="true" legend="General Assesment">
                <div class="column is-12" v-for="(datas) in TandaVital">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">{{ datas.title }}</h1>
                    <div class="columns is-multiline">
                        <div class="column is-3" v-for="(data) in datas.value">
                            <h1 style="font-weight: bold;">{{ data.subTitle }}</h1>
                            <VField addons v-if="data.satuan">
                                <VControl expanded>
                                    <VInput type="text" class="input" placeholder="" v-model="input[data.model]" />
                                </VControl>
                                <VControl class="field-addon-body">
                                    <VButton static>{{ data.satuan }}</VButton>
                                </VControl>
                            </VField>
                            <VField v-else>
                                <VControl raw subcontrol>
                                    <input v-model="input[data.subTitle]" class="input" />
                                </VControl>
                            </VField>
                        </div>
                    </div>

                </div>
            </Fieldset>
        </div>
        <div class="column is-12">
            <Fieldset :toggleable="true" legend="Physical Therapy Intervention">
                <div class="column is-8" v-for="(datas) in fisik">
                    <h1 style="font-weight: bold;">{{ datas.title }}</h1>
                            <div class="column is-multiline">
                                <div class="column-12" v-for="(data) in datas.value">
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input[data.model]" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                    
                                </div>
                            </div>

                        </div>

            </Fieldset>
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
import * as EMR from '../page-emr-plugins/asesmen-fisioterapi'
import Fieldset from 'primevue/fieldset';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let general = ref(EMR.general())
let lokalis = ref(EMR.lokalis())
let TandaVital = ref(EMR.TandaVital())
let fisik = ref(EMR.fisik())

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
const COLLECTION: any = ref('TABLE_NAME') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date(),
    tanggalAsesmen: new Date()
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
const setAutoFill = async () => {
    input.value.namaPasien = props.pasien.namapasien
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.pekerjaanPasien = props.pasien.pekerjaan
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss"></style>
