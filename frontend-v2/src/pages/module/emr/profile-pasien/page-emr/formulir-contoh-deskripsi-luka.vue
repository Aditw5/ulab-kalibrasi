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
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.contohDeskripsiLuka" rows="50" placeholder=".....">
                                </VTextarea>
                            </VControl>
                        </VField>
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
import AutoComplete from 'primevue/autocomplete';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'


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
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const d_ruangan: any = ref([])
const d_agama: any = ref([])
const d_Dokter: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
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
    object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
    object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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

    input.value.pasienPenanggungjawab = props.pasien.namapasien
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.telepon = props.pasien.nohp
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.pihakPembayar = props.registrasi.kelompokpasien
    input.value.tglPembuatan = new Date()

    input.value.contohDeskripsiLuka = `
                                                                                                                                                 CONTOH DESKRPSI LUKA
1. KEPALA DAN LEHER :
a. Pada kepala bagian kanan/kiri/depan/belakang, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak kepala, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b. Pada dahi kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari alis, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... 
c. Pada hidung, ... centimeter dari pangkal hidung, ... centimeter dari garis tengah ke arah kanan/kiri, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
d. Pada pipi kanan/kiri, ... centimeter dari garis tengah, ... centimeter dari mata, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
e. Pada bibir atas/bawah, ... centimeter dari garis tengah ke arah kanan/kiri, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
f. Pada dagu, ... centimeter dari garis tengah ke arah kanan/kiri atau pada garis tengah, ... centimeter dari bibir, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
g. Pada leher, ... centimeter dari garis tengah depan/belakang ke arah kanan/kiri atau pada garis tengah depan/belakang, ... centimeter dari pangkal leher, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...


2. DADA, PUNGGUNG, DAN PERUT:
a.  Pada dada kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak bahu/pangkal leher, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b.  Pada perut kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak bahu/di atas pusar/di bawah pusar, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... 
c.  Pada punggung kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak bahu/pangkal leher, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... 

3. LENGAN DAN TANGAN :
a.  Pada lengan atas kanan/kiri, ... centimeter dari puncak bahu/lipat siku/siku, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b.  Pada lengan bawah kanan/kiri, ... centimeter dari puncak bahu/lipat siku/siku, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... 
c.  Pada punggung/telapak tangan kanan/kiri, ... centimeter dari garis tengah ke arah luar/dalam atau pada garis tengah,  ... centimeter dari pergelangan tangan/ujung jari ..., terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...


4. PAHA, TUNGKAI BAWAH, DAN KAKI :
a.  Pada paha kanan/kiri, ... centimeter dari lipat paha/lutut/lipat lutut, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b.  Pada tungkai bawah kanan/kiri, ... centimeter dari lutut/lipat lutut/pergelangan kaki, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... 
c.  Pada punggung/telapak kaki kanan/kiri, ... centimeter dari garis tengah ke arah luar/dalam atau pada garis tengah,  ... centimeter dari pergelangan kaki/ujung jari ..., terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...


5. KETERANGAN TAMBAHAN:
a. Jika tampak bengkak, ditambahkan deskripsi: tampak bengkak, menimbul ... centimeter dari permukaan kulit.
b. Jika terdapat patah tulang tertutup, ditambahkan deskripsi: teraba derik tulang pada tulang...
c. Jika terdapat patah tulang terbuka, ditambahkan deskripsi: tampak tulang ... patah.

`;

}
setView()
setAutoFill()
loadRiwayat()
</script>
<style>
#signature {
    border: double 3px transparent;
    border-radius: 5px;
    background-image: linear-gradient(white, white),
        radial-gradient(circle at top left, #4bc5e8, #9f6274);
    background-origin: border-box;
    background-clip: content-box, border-box;
}

.container {
    width: "100%";
    padding: 8px 16px;
}

.buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    margin-top: 8px;
}
</style>
