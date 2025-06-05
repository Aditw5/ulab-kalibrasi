<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="column is-12">
        <VCard>
            <p class="p-2 paraf">Formulir ini adalah perintah dokter penanggung jawab pelayanan kepada seluruh staf klinis
                rumah sakit,
                agar tidak dilakukan resusitasi pada pasien ini bila terjadi henti jantung ( bila tidak ada denyut nadi )
                dan henti nafas (tak ada pernafasan spontan).</p>
            <p class="p-2 paraf">Formulir ini juga memberikan perintah kepada staf medis untuk tetap melakukan intervensi
                atau pengobatan
                atau tata laksana lainnya sebelum terjadinya henti jantung atau henti nafas.</p>

            <div class="column is-6">
                <span style="font-weight:500;">Nama Pasien</span>
                <VField class="mt-3">
                    <VControl>
                        <VInput type="text" class="input" v-model="input.namaPasien" />
                    </VControl>
                </VField>
            </div>
            <div class="columns is-multiline pl-3 pt-3">
                <div class="column is-3">
                    <span style="font-weight:500;">Jenis Kelamin</span>
                    <VField class="mt-3">
                        <VControl>
                            <VInput type="text" class="input" v-model="input.jenisKelamin" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <span style="font-weight:500;">Tanggal Lahir</span>
                    <VDatePicker class="pt-3" v-model="input.tglLahir" color="green" trim-weeks mode="date"
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
            </div>

            <p class="p-2 paraf">Perintah/pernyataan dokter penangung jawab pelayanan : </p>
            <p class="p-2 paraf" style="text-wrap: nowrap;">Saya dokter yang bertanda tangan dibawah ini menginstruksikan
                kepada seluruh staf medis dan staf klinis lainnya
                untuk melakukan hal-hal tertulis dibawah ini :</p>
            <div class="column is-12 ml-5 mr-5">
                <p class="pl-5 pr-5 paraf pb-3">a. Usaha komprehensif untuk mencegah henti jantung atau henti nafas tanpa
                    melakukan intubasi.</p>
                <span class="pl-5">"DO NOT RESUCITATE" (TIDAK DILAKUKAN RESUSITASI JANTUNG PARU/RJP).</span>
                <p class="pl-5 pr-5 pt-4 paraf">b. Usaha suportif sebelum terjadi henti nafas atau henti jantung yang
                    meliputi pembukaan jalan nafas non invansif, mengontrol pendarahan, memposisikan pasien dengan nyaman,
                    pemberian obat-obatan anti nyeri. TIDAK MELAKUKAN RJP (RESUSITASI JANTUNG PARU) bila henti nafas atau
                    henti jantung terjadi.</p>
            </div>
            <p class="paraf pl-2">Saya dokter bertanda tangan dibawha ini menyatakan bahwa keputusan DNR di atas diambil
                setelah pasien diberikan penjelasan dan informed consent diperoleh dari salah satu :</p>
            <div class="column is-12 ml-5 mr-5">
                <p class="pl-5 pr-5 paraf pb-3">a. Pasien</p>
                <p class="pl-5 pr-5 paraf pb-3">b. Tenaga Kesehatan yang ditunjuk pasien</p>
                <p class="pl-5 pr-5 paraf pb-3">c. Wali yang sah atas pasien (termasuk yang ditunjuk oleh pengadilan)</p>
                <p class="pl-5 pr-5 paraf pb-3">d. Anggota Keluarga Pasien</p>
            </div>
            <p class="paraf pl-2">Jika yang diatas tidak dimungkinkan maka dokter yang betanda tangan dibawah ini memberikan
                perintah DNR berdasarkan pada :</p>

            <div class="column is-12 ml-5 mr-5">
                <p class="pl-5 pr-5 paraf pb-3">a. Intruksi pasien sebelum atau</p>
                <p class="pl-5 pr-5 paraf pb-3">b. Keputusan dua orang dokter yang menyatakan bahwa Resusitasi Jantung Paru
                    (RJP) akan mendatangkan hasil yang tidak efektif</p>
            </div>

            <div class="columns is-multiline pt-5 pl-3 pr-3">
                <div class="column is-3">
                    <span style="font-weight:500">Bandung</span>
                    <VDatePicker class="pt-3" v-model="input.waktuDibuat" color="green" trim-weeks mode="datetime"
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
                <div class="column is-4" style="margin-left:auto">
                    <TandaTangan :elemenID="'signaturDokter'" :width="'180'" :height="'180'" class="dek" />
                    <div class="column pl-0 pr-0">
                        <span style="font-weight:500">Dokter</span>
                        <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.pegawaiDokter" :suggestions="d_Dokter"  @item-select="setTandaTanganDokter($event)"
                                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Dokter..." class="mt-2" />
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
        COLLECTION: ''
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('PermohonanJanganDilakukanTidakanResusitasi') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    waktuDibuat: new Date()
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
                if (response[0].ttdDokter) {
                    H.tandaTangan().set("signaturDokter", response[0].ttdDokter)
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
    object.ttdDokter = H.tandaTangan().get("signaturDokter")
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
    input.value.jenisKelamin = props.pasien.jeniskelamin
    input.value.tglLahir  = props.pasien.tgllahir
}

const setTandaTanganDokter = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signaturDokter", element.ttd)
      }else{
        H.tandaTangan().set("signaturDokter", '')
      }
    })
}

const fetchDokter = async (filter: any) => {

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.paraf {
    text-align: justify;
}
</style>
