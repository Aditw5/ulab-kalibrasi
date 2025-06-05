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

    <div class="column is-12">
        <VCard>
            <div class="column">
                <h1 style="font-weight:bold">Yang bertanda tangan dibawah ini</h1>
                <div class="column is-5 p-5">
                    <span class="label-ptd">Nama</span>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.namaPenangungJwb" />
                        </VControl>
                    </VField>
                </div>

                <div class="columns is-multiline pl-5 pr-5">
                    <div class="column is-4 pb-0">
                        <span class="label-ptd">Jenis Kelamin</span>
                        <div class="columns is-multiline p-5">
                            <div class="column p-0">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.jnsKlmPenangungJwb" class="p-0" true-value="Laki-laki"
                                            label="Laki-laki" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column p-0">
                                <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.jnsKlmPenangungJwb" class="p-0" true-value="Perempuan"
                                            label="Perempuan" color="primary" circle />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                    </div>

                    <div class="column is-3">
                        <span class="label-ptd">Tanggal Lahir</span>
                        <VDatePicker class="p-3 pb-0" v-model="input.tglLahirPenangungJwb" color="green" trim-weeks mode="date"
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                <VField>
                                    <VControl icon="feather:calendar">
                                        <VInput type="text" placeholder="Select a date" :value="inputValue"
                                            v-on="inputEvents" class="is-rounded_Z" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                </div>

                <div class="column is-7 pt-0 pl-5">
                    <span class="label-ptd">Alamat</span>
                    <VField class="pt-3">
                        <VControl>
                            <VTextarea v-model="input.alamat" rows="1" placeholder="Alamat ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>
            </div>

            <hr>
            <div class="column">
                <h1 style="font-weight:bold">Dalam Hal ini Bertindak Sebagai :</h1>
                <VField class="column is-4 p-0">
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.penanggungjawab" :suggestions="d_PenangungJwb"
                            @complete="fetchPenanggungJawab($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari..."
                            class="mt-2" />
                    </VControl>
                </VField>

                <div class="column is-5 pl-5">
                    <span class="label-ptd">Nama</span>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.namaPasien" />
                        </VControl>
                    </VField>
                </div>
                <div class="columns is-multiline pt-3 pl-5 pr-5 pb-0">
                    <div class="column is-3 pb-0">
                        <span class="label-ptd">Jenis Kelamin</span>
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.jnsKlmPasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pb-0">
                        <span class="label-ptd">No Rekam Medis</span>
                        <VField class="mt-3">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.norm" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3 pb-0">
                        <span class="label-ptd">Tanggal Lahir</span>
                        <VDatePicker class="p-3 pb-0" v-model="input.tglLhrPasien" color="green" trim-weeks mode="date"
                            :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }" class="pb-0">
                                <VField>
                                    <VControl icon="feather:calendar">
                                        <VInput type="text" placeholder="Select a date" :value="inputValue"
                                            v-on="inputEvents" class="is-rounded_Z" />
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                </div>

                <div class="column is-7 pl-5 pr-5 pt-0">
                    <span class="label-ptd">Alamat</span>
                    <VField class="pt-3">
                        <VControl>
                            <VTextarea v-model="input.alamatPasien" rows="1" placeholder="Alamat ...">
                            </VTextarea>
                        </VControl>
                    </VField>
                </div>

                <div class="column is-12 pl-5 pr-5 pb-0">
                    <p class="pb-3 p-justify">a. Dengan ini saya menyatakan bahwa saya membuat keputusan dan menyetujui
                        perintah "DO NOT RESCUITATE" (jangan di resusitasi)</p>
                    <p class="pb-3 p-justify">b. Saya menyatakan bahwa jika jantung saya berhenti berdetak atau jika saya
                        berhenti bernafas, tidak ada prosedur medis untuk
                        mengembalikan bernafas atau berfungsi kembali jantung akan dilakukan oleh staf rumah sakit, termasuk
                        namun tidak terbatas pada staf layanan medis darurat.</p>
                    <p class="pb-3 p-justify">c. Saya memahamki bahwa keputusan ini tidak akan mencegah saya menerima
                        pelayanan kesehatan lainnya seperti pemberian maneuver Heimlich atau pemberian oksigen dan
                        langkah-langkah perawatan untuk menignkatkan kenyamanan lainnya.</p>
                    <p class="pb-3 p-justify">d. Saya memberikan izin agar informasi ini diberikan kepada seluruh staf rumah
                        sakit dan saya memahami bahwa saya dapat mencabut pernyataan ini setiap saat.</p>
                </div>
            </div>
            <div class="column p-0">
                <p style="text-wrap: nowrap;">Demikianlah pernyataan ini saya buat dengan penuh kesadaran, dan bertanggung
                    jawab atas risiko yang mungkin terjadi sehubungan dengan pernyataan ini.</p>
            </div>

            <div class="column p-0 mt-5 is-3">
                <span class="label-ptd">Bandung</span>
                <VDatePicker class="p-3 pb-0" v-model="input.tglDibuat" color="green" trim-weeks mode="datetime"
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }" class="pb-0">
                        <VField>
                            <VControl icon="feather:calendar">
                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                    class="is-rounded_Z" />
                            </VControl>
                        </VField>
                    </template>
                </VDatePicker>
            </div>

            <div class="columns is-multiline pt-5" style="justify-content: space-around;">
                <div class="column is-3" style="text-align:center">
                    <TandaTangan :elemenID="'signatureSaksi_I'" :width="'180'" :height="'180'" class="dek" />
                    <div class="column p-0 mt-5" style="text-align: left;">
                        <span class="label-ptd">Saksi I</span>
                        <VField class="pt-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.saksi_I" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-3" style="text-align:center">
                    <TandaTangan :elemenID="'signatureSaksi_II'" :width="'180'" :height="'180'" class="dek" />
                    <div class="column p-0 mt-5" style="text-align: left;">
                        <span class="label-ptd">Saksi II</span>
                        <VField class="pt-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.saksi_II" />
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="column is-3" style="text-align:center">
                    <TandaTangan :elemenID="'signatureSaksi_III'" :width="'180'" :height="'180'" class="dek" />
                    <div class="column p-0 mt-5" style="text-align: left;">
                        <span class="label-ptd">Saksi III</span>
                        <VField class="pt-2">
                            <VControl>
                                <VInput type="text" class="input" v-model="input.saksi_III" />
                            </VControl>
                        </VField>
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
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
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
const d_PenangungJwb: any = ref([])
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
    tglDibuat: new Date()
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
                if (response[0].ttdSaksi_I) {
                    H.tandaTangan().set("signatureSaksi_I", response[0].ttdSaksi_I)
                }
                if (response[0].ttdSaksi_II) {
                    H.tandaTangan().set("signatureSaksi_II", response[0].ttdSaksi_II)
                }
                if (response[0].ttdSaksi_III) {
                    H.tandaTangan().set("signatureSaksi_III", response[0].ttdSaksi_III)
                }
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
    object.ttdSaksi_I = H.tandaTangan().get("signatureSaksi_I")
    object.ttdSaksi_II = H.tandaTangan().get("signatureSaksi_II")
    object.ttdSaksi_III = H.tandaTangan().get("signatureSaksi_III")
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
    input.value.jnsKlmPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.tglLhrPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
}

const fetchPenanggungJawab = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
    ).then((response) => {
        d_PenangungJwb.value = response
    })
}


setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-ptd {
    font-weight: 500;
}

.p-justify {
    text-align: justify;
}
</style>
