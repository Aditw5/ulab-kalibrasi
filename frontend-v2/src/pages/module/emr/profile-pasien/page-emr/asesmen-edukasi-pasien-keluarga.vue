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
            <div class="column is-multiline">
                <h1 style="font-weight: bold;"> A. Kemampuan dan Kemauan Edukasi </h1>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Nama Panggilan : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nama Lengkap"
                                        v-model="input.namaPasien" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Agama : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nama Lengkap" v-model="input.agama" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Nilai-nilai yang dianut : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Nilai Yang dianut"
                                        v-model="input.nilaiYangdiAnut" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Pendidikan : </h1>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Pendidikan" v-model="input.pendidikan" />
                                </VControl>

                            </VField>
                        </div>

                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Kesulitan Komunikasi: </h1>
                        </div>
                        <div class="columns is-8" style=" margin-top: 1rem">
                            <VField v-for="items in kesulitanKomunikasi" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.kesulitanKomunikasi" class="pt-1 pb-1 "
                                        :true-value="items.label" :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKesulitanKomunikasi" />
                                </VControl>

                            </VField>
                        </div>

                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Bahasa Yang di pakai: </h1>
                        </div>
                        <div class="columns is-12" style=" margin-top: 1rem">
                            <VField v-for="items in bahasa" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.bahasaPasien" class="pt-1 pb-1 " :true-value="items.label"
                                        :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-2">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKesulitanKomunikasi" />
                                </VControl>

                            </VField>
                        </div>

                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-3" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Penterjemah: </h1>
                        </div>
                        <div class="columns is-8" style=" margin-top: 1rem">
                            <VField v-for="items in penterjemah" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.penterjemah" class="pt-1 pb-1 " :true-value="items.label"
                                        :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKesulitanKomunikasi" />
                                </VControl>

                            </VField>
                        </div>


                    </div>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;">Identifikasi dan berikan tanda (V) pada hambatan yang dapat mempengaruhi
                        proses dan hasil edukasi:</h1>
                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in prosesEdukasi">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKesulitanKomunikasi" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
                <div class="column is-12 p-0">
                    <div class="is-flex">
                        <div class="column is-6" style="margin-top:0.5rem">
                            <h1 style="font-weight: bold;"> Kesediaan pasien /keluarga* untuk menerima informasi yang
                                diberikan: </h1>
                        </div>
                        <div class="columns is-6" style=" margin-top: 1rem">
                            <VField v-for="items in kesediaanPasien" :key="items.value" style="padding:0px;">
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input.kesediaanPasien" class="pt-1 pb-1 " :true-value="items.label"
                                        :label="items.label" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-4">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Keterangan"
                                        v-model="input.KetKesulitanKomunikasi" />
                                </VControl>

                            </VField>
                        </div>


                    </div>
                </div>
                <h1 style="font-weight: bold;"> B. Kebutuhan Edukasi </h1>
                <div class="column is-12">
                    <h1 style="font-weight: bold;"> *Identifikasi dan berikan tanda (V) pada kebutuhan edukasi yang
                        dibutuhkan pasien dan keluarganya </h1>
                </div>
                <div class="column is-12">
                    <h1 style="font-weight: bold;"> *Pada Keadaan dimana pasien memerlukan edukasi suatu bidang yang khusus,
                        Dokter Penanggungjawab Pasien akan menentukan kebutuhan dan program edukasi sesuai</h1>
                </div>
                <div class="column is-12">

                    <div class="columns is-multiline pt-3 pl-3">
                        <div class="column is-6" v-for="(data, i) in BidangDisiplin">
                            <VField>
                                <VControl raw subcontrol>
                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.value" :label="data.title"
                                        class="p-0" color="primary" square />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField>
                                <VControl>
                                    <VInput type="text" class="input" placeholder="Edukasi Lainnya"
                                        v-model="input.ketEdukasiLainnya" />
                                </VControl>

                            </VField>
                        </div>
                    </div>
                </div>
            </div>
        </VCard>
    </div>
    <div class="column is-4 mt-3" style="margin-left: auto;">
        <VCard class="border-card pink">
            <div class="column is-9">
                <VField>
                    <h1 style="font-weight: bold;">Bandung , tanggal dan jam</h1>
                </VField>
                <VField>
                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
                        :max-date="new Date()">
                        <template #default="{ inputValue, inputEvents }">
                            <VField>
                                <VControl icon="feather:calendar" fullwidth>
                                    <VInput :value="inputValue" placeholder="Jam Keluar IGD" v-on="inputEvents" />
                                </VControl>
                            </VField>
                        </template>
                    </VDatePicker>
                </VField>
            </div>
            <div class="column pt-0">
                <VField>
                    <h1 style="font-weight: bold;">Petugas yang memberikan edukasi</h1>
                </VField>
                <VField class="is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:search">
                        <AutoComplete v-model="input.penangungJawab" :suggestions="d_Petugas"
                            @complete="fetchPetugas($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" />
                    </VControl>
                </VField>
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
// import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/asesmen-edukasi-pasien-keluarga'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let prosesEdukasi = ref(EMR.prosesEdukasi())
let BidangDisiplin = ref(EMR.BidangDisiplin())

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
const d_Petugas: any = ref([])
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
    tanggal: new Date(),
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

const kesulitanKomunikasi: any = ref([
    { label: 'Tidak Ada', value: 'Tidak Ada' },
    { label: 'Ada, Jelaskan', value: 'Ada' }
])
const bahasa: any = ref([
    { label: 'Indonesia', value: 'Indonesia' },
    { label: 'Inggris', value: 'Inggris' },
    { label: 'Mandarin', value: 'Mandarin' },
    { label: 'Bahasa Lainnya', value: 'Bahasa Lainnya' },
])
const penterjemah: any = ref([
    { label: 'Tidak Perlu', value: 'Tidak Perlu' },
    { label: 'Perlu,', value: 'Perlu' },
    { label: 'Lainnya, Jelaskan', value: 'Lainnya' }
])
const kesediaanPasien: any = ref([
    { label: 'Ya', value: 'Ya' },
    { label: 'Tidak,', value: 'Tidak' },
])
const fetchPetugas = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    ).then((response) => {
        d_Petugas.value = response
    })
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
    input.value.agama = props.pasien.agama
    input.value.pendidikan = props.pasien.pendidikan
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
</script>
