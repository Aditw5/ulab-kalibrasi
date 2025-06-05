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
                <h1 class="mt-3 mb-1" style="font-weight: bold;">I. Hak Pasien</h1>
                <h1 style="font-weight: bold; text-align: center;"> (sesuai pasal PMK No. 4 tahun 2018) </h1>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="column is-12">
                            <p>
                                1. Setiap pasien berhak memperoleh informasi mengenai tata tertib dan peraturan yang berlaku di Rumah Sakit.
                            </p>
                            <p>
                                2. Setiap pasien berhak memperoleh informasi tentang hak dan kewajiban pasien.
                            </p>
                            <p>
                                3. Setiap pasien berhak memperoleh layanan yang manusiawi, adil, jujur, dan tanpa diskriminasi.
                            </p>
                            <p>
                                4. Setiap pasien berhak memperoleh layanan kesehatan yang bermutu sesuai dengan standar profesi dan standar
                                prosedur operasional (SPO).
                            </p>
                            <p>
                                5. Setiap pasien berhak memperoleh layanan yang efektif dan efisien sehingga pasien terhindar dari kerugian
                                fisik dan materi.
                            </p>
                            <p>
                                6. Setiap pasien berhak mengajukan pengaduan atas kualitas pelayanan yang didapatkan.
                            </p>
                            <p>
                                7. Setiap pasien berhak memilih dokter dan kelas perawatan sesuai dengan keinginannya dan peraturan yang
                                berlaku di Rumah Sakit.
                            </p>
                            <p>
                                8. Setiap pasien berhak meminta konsultasi tentang penyakit yang dideritanya kepada dokter lain yang mempunyai Surat Izin Prakter (SIP) baik di dalam maupun di luar Rumah
                                Sakit.
                            </p>
                            <p>
                                9. Setiap pasien berhak mendapatkan privasi dan kerahasiaan penyakit yang diderita termasuk data-data
                                medisnya.
                            </p>
                            <p>
                                10. Setiap pasien berhak mendapat informasi yang meliputi diagnosis dan tata cara tindakan medis, tujuan tindakan medis, alternatif tindakan, risiko dan komplikasi yang mungkin terjadi, dan progronis terhadap tindakan yang dilakukan serta perkiraan biaya pengobatan.
                            </p>
                            <p>
                                11. Setiap pasien berhak memberikan persetujuan atau menolak atas tindakan yang akan dilakukan oleh tenaga kesehatan terhadap penyakit yang dideritanya.
                            </p>
                            <p>
                                12. Setiap pasien berhak didampingi keluarganya dalam keadaan kritis.
                            </p>
                            <p>
                                13. Setiap pasien berhak menjalankan ibadah sesuai agama atau kepercayaan yang dianutnya selama hal itu tidak
                                mengganggu pasien lainnya.
                            </p>
                            <p>
                                14. Setiap pasien berhak memperoleh keamanan dan keselamatan dirinya selama dalam perawatan Rumah Sakit.
                            </p>
                            <p>
                                15. Setiap pasien berhak mengajukan usul, saran, perbaikan atas perlakukan Rumah Sakit terhadap dirinya.
                            </p>
                            <p>
                                16. Setiap pasien berhak menolak layanan bimbingan rohani yang tidak sesuai dengan agama dan kepercayaan yang
                                dianutnya.
                            </p>
                            <p>
                                17. Setiap pasien berhak menggugat atau menuntut Rumah Sakit apabila Rumah Sakit diduga memberikan
                                pelayanan yang tidak sesuai dengan standar baik secara perdata maupun pidana, dan.
                            </p>
                            <p>
                                18. Setiap pasien berhak mengeluhkan pelayanan Rumah Sakit yang tidak sesuai dengan standar pelayanan melalui media cetak dan elektronik sesuai dengan ketentuan peraturan perundang-undangan.
                            </p>
                        </div>

                        <h1 class="mt-3 mb-1" style="font-weight: bold;">II. Hal-hal yang menjadi kewajiban pasien/keluarga adalah</h1>
                        <div class="column is-12">
                            <p>
                                1. Mematuhi peraturan yang berlaku di Rumah Sakit;
                            </p>
                            <p>
                                2. Menggunakan fasilitas Rumah Sakit secara bertanggung jawab;
                            </p>
                            <p>
                                3. Menghormati hak pasien lain, pengunjung dan hak kesehatan serta petugas lainnya yang bekerja di Rumah Sakit;
                            </p>
                            <p>
                                4. Memberikan informasi yang jujur, lengkap dan akurat sesuai dengan kemampuan dan pengetahuannya tentang masalah kesehatannya;
                            </p>
                            <p>
                                5. Memberikan informasi mengenai kemampuan finansial dan jaminan kesehatan yang dimilikinya;
                            </p>
                            <p>
                                6. Mematuhi rencana terapi yang direkomendasikan oleh tenaga kesehatan di rumah sakit dan disetujui oleh pasien yang bersangkutan setelah mendapatkan penjelasan sesuai dengan ketentuan peraturan perundang-undangan.
                            </p>
                            <p>
                                7. Menerima segala konsekuensi atas keputusan pribadinya untuk menolak rencana terapi yang direkomendasikan oleh tenaga kesehatan dan/atau mematuhi petunjuk yang diberikan oleh tenaga kesehatan untuk penyembuhan penyakit atau masalah kesehatannya; dan
                            </p>
                            <p>
                                8. Meberikan imbalan jasa atas pelayanan yang diterima.
                            </p>
                        </div>
                        <div class="columns is-multiline">
                            <div class="column is-6">
                            </div>
                            <div class="column is-4">
                                <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                                <VField>
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
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                </div>
                                <div class="column is-6 ">
                                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-6">
                                </div>
                                <div class="column is-4">
                                    <h1 class="p-0" style="font-weight: bold;">Pasien/Keluarga/Penannggung Jawab</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Pasien/Keluarga/Penannggung Jawab"
                                                v-model="input.pasienKeluargaPenannggungJawab" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
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
                input.value = response[0] //set ke inputan
                if (response[0].tandaTanganPasien) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPasien)
                }
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}
const fetchAgama = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`)
    d_agama.value = response
}
const fetchRuangan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
    d_ruangan.value = response
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_2", response.ttd)
        input.value.tandaTanganPetugas = response.ttd
    } else {
        H.tandaTangan().set("signature_2", '')
    }
}
const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.tandaTanganPasien = H.tandaTangan().get("signature_1")
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

    input.value.pasienKeluargaPenannggungJawab = props.pasien.namapasien
    input.value.jenisKelaminPasien = props.pasien.jeniskelamin
    input.value.norm = props.pasien.nocm
    input.value.telepon = props.pasien.nohp
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.pihakPembayar = props.registrasi.kelompokpasien
    input.value.tglPembuatan = new Date()
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
