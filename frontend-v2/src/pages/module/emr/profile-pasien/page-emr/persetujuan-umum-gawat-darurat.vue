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
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    1. HAK DAN KEWAJIBAN SEBAGAI PASIEN.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Dengan menandatangani dokumen ini saya mengakui bahwa pada proses pendaftar untuk
                                    mendapatkan perawatan di RSD Gunung Jati Kota Cirebon telah mendapat informasi tentang
                                    hak-hak dan kewajiban saya sebagai pasien.
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    2. PERSETUJUAN PELAYANAN KESEHATAN.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya menyetujui dan memberikan persetujuan untuk
                                    mendapat pelayanan kesehatan di RSD Gunung Jati Kota Cirebon dan dengan ini saya meminta
                                    dan
                                    memberikan kuasa kepada RSD Gunung Jati Kota Cirebon, dokter dan perawat, dan tenaga
                                    kesehatan
                                    lainnya untuk memberikan asuhan perawatan, pemeriksaan fisik yang dilakukan oleh dokter
                                    dan perawat dan
                                    melakukan prosedur diagnostik, radiologi dan / atau terapi dan tatalaksana sesuai
                                    pertimbangan dokter yang
                                    diperlukan atau disarankan pada perawatan saya. Hal ini mencakup seluruh pemeriksaan dan
                                    prosedur
                                    diagnostik rutin, termasuk x-ray, pemberian dan/ atau tindakan medis serta penyuntikan
                                    (intramuskular,
                                    intravena dan prosedur invasif lainnya) produk farmasi dan obat-obatan, pemasangan alat
                                    kesehatan
                                    (kecuali yang membutuhkan persetujuan khusus/tertulis), dan pengambilan darah untuk
                                    pemeriksaan
                                    laboratorium atau pemeriksaan patologi yang dibutuhkan untuk pengobatan dan tindakan
                                    yang aman
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    3. PERSETUJUAN PELAYANAN OLEH PESERTA DIDIK.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Sebagai Rumah Sakit Pendidikan, proses
                                    pelayanan asuhan pasien melibatkan peserta didik, baik mahasiswa kedokteran, mahasiswa
                                    keperawatan,
                                    mahasiswa kebidanan dan tenaga kesehatan lainnya. Untuk itu saya menyetejui dan
                                    memberikan
                                    arta didik dalam proses asuhan pelayanan di Rumah Sakit.
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    4. AKSES INFORMASI KESEHATAN.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya memberi kuasa kepada setiap dan seluruh orang yang merawat
                                    saya untuk memeriksa dan atau memberitahukan informasi kesehatan saya kepada pemberi
                                    kesehatan lain.
                                    yang turut merawat saya selama di rumah sakit ini
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    5. RAHASIA MEDIS.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya setuju RSD Gunung Jati Kota Cirebon wajib menjamin kerahasiaan informasi medis
                                    saya baik untuk kepentingan perawatan dan pengobatan, pendidikan maupun penelitian
                                    kesuali saya
                                    mengungkapkan sendin atau orang yang lain yang saya beri kuasa untuk itu.
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    6. PEMBUKAAN INFORMASI MEDIS PASIEN.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya menyetujui bahwa penyakit adalah rahasia kedokteran
                                    yang hanya boleh diketahui oleh pasien, dokter, tenaga profesi yang diatur oleh
                                    undang-undang, oleh karena
                                    itu informasi medis ini boleh diinformasikan kepada yang saya tunjuk yaitu :
                                </h1>
                            </div>
                            <div class="is-flex" style="margin-left: 10px">
                                <table class="tg">
                                    <thead>
                                        <tr>
                                            <td class="tg-0lax text-center">No</td>
                                            <td class="tg-0lax text-center">Nama</td>
                                            <td class="tg-0lax text-center">Hubungan</td>
                                            <td class="tg-0lax text-center">No HP</td>
                                            <td class="tg-0lax text-center">Aksi</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(data, index) in input.detailKeluarga" :key="index">
                                            <td>{{ data.no }}</td>
                                            <td>
                                                <VField>
                                                    <VControl icon="feather:bookmark">
                                                        <VInput type="text" v-model="data.namakeluarga" placeholder="Nama.."
                                                            class="input" />
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl icon="feather:bookmark">
                                                        <VInput type="text" v-model="data.hubungan" placeholder="Hubungan.."
                                                            class="input" />
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td>
                                                <VField>
                                                    <VControl icon="feather:bookmark">
                                                        <VInput type="text" v-model="data.nohp" placeholder="No Hp..."
                                                            class="input" />
                                                    </VControl>
                                                </VField>
                                            </td>
                                            <td class="td-fkprj" style="vertical-align: inherit;">
                                                <div class="column">
                                                    <VIconButton type="button" raised circle icon="feather:plus"
                                                        @click="addNewItemKeluarga()" color="info"
                                                        v-tooltip.bubble="'Tambah '">
                                                    </VIconButton>
                                                    <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle
                                                        icon="feather:trash" @click="removeItemKeluarga(index)"
                                                        color="danger">
                                                    </VIconButton>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex" style="margin-left: 10px; margin-top: 9px">
                                <h1>
                                    Pembukaan Informasi medis Pasien Tersebut, boleh tanpa persetujuan untuk keperluan
                                    pembayaran
                                    asuransi, klaim BPJS dan untuk keperluan pengadilan.
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    7. PRIVASI.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya memberi kuasa kepada RSD Gunung Jati Kota Cirebon untuk menjaga privasi dan
                                    kerahasiaan penyakit saya selama dalam perawatan.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    a)
                                </h1>
                                <VField style="padding:0px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.bersedia" class="pt-1 pb-1 " label="Bersedia"
                                            color="primary" square />
                                    </VControl>
                                </VField>
                                <VField style="padding:0px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.tidakBersediaDijenguk" class="pt-1 pb-1 "
                                            label="Tidak Bersedia Dijenguk" color="primary" square />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    b)
                                </h1>
                                <VField style="padding:0px;">
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input.privasiLainnya" class="pt-1 pb-1 " label="Privasi Lainnya"
                                            color="primary" square />
                                    </VControl>
                                </VField>
                                <VField style="padding:0px;" v-if="input.privasiLainnya">
                                    <VControl raw subcontrol>
                                        <VInput type="text" v-model="input.privasiLainnyaKet" placeholder="...."
                                            class="input" style="width: 900px; font-size: 15px; padding: 8px;" />
                                    </VControl>
                                </VField>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    8. BARANG PRIBADI.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya tidak boleh membawa barang-barang berharga yang tidak diperlukan (seperti:
                                    perhiasan, elektronik dll) RSD Gunung Jati Kota Cirebon dan jika saya membawanya maka
                                    RSD Gunung Jati
                                    Kota Cirebon tidak bertanggung jawab terhadap kehilangan, kerusakan atau pencurian.
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    9. PENGAJUAN KELUHAN.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya menyatakan bahwa saya telah menerima informasi tentang adanya tatacara
                                    mengajukan dan mengatasi keluhan terkait pelayanan medik yang diberikan terhadap din
                                    saya. Saya setuju
                                    untuk mengikuti tatacara mengajukan keluhan sesuai prosedur yang ada.
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    10 KEWAJIBAN PEMBAYARAN.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Saya menyatakan setuju, baik sebagai wali atau sebagai pasien, bahwa
                                    sesuai perimbangan pelayanan yang diberikan kepada pasien, maka saya wajib untuk
                                    membayar total biaya
                                    pelayanan, Biaya pelayanan berdasarkan acuan biaya dan ketentuan RSD gunung Jati Kota
                                    Cirebon.
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    Apabila asuransi kesehatan swasta atau program pemerintah menanggung pembiayaan saya,
                                    saya memberi
                                    wewenang kepada rumah sakit untuk memberi tagihan dari semua pelayanan dan tindakan
                                    medis yang
                                    diberikan. Tanggungan Asuransi saya mungkin menyatakan bahwa sebagian pembayaran tetap
                                    menjadi
                                    tanggung jawab pribadi saya atau tidak ditanggung oleh Asuransi, maka rumah sakit
                                    berwenang memberi
                                    tagihan untuk biaya yang tidak ditanggung oleh asuransi dan saya bertanggung jawab untuk
                                    membayarnya.
                                    Saya juga menyadari dan memahami bahwa :
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 15px">
                                <h1>
                                    - Apabila saya tidak memberikan persetujuan, atau dikemudian hari mencabut persetujuan
                                    saya untuk
                                    melepaskan rahasia kedokteran saya kepada perusahaan asuransi yang saya tentukan,maka
                                    saya pribadi bertanggung jawab untuk membayar semua pelayanan dan tindakan medis
                                    dari RSD
                                    Gunung Jati
                                    Kota Cirebon.
                                    diri
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 15px">
                                <h1>
                                    - Apabila rumah sakit membutuhkan proses hukum untuk menagih biaya pelayanan rumah sakit
                                    dari saya,
                                    saya memahami bahwa saya bertanggung jawab untuk membayar semua biaya yang disebabkan
                                    dari
                                    proses hukum tersebut
                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <div class="flex">
                                <h1 style="font-weight: bold; font-size: 10pt">
                                    11. Melalui dokumen ini,
                                </h1>
                            </div>
                            <div class="flex" style="margin-left: 10px">
                                <h1>
                                    saya menegaskan kembali bahwa saya mempercayakan kepada semua tenaga
                                    kesehatan rumah sakit untuk memberikan perwatan, diagnostik dan terapi kepada saya
                                    sebagai pasien
                                    rawat inap atau rawat jalan atau Instalasi Gawat Darurat (TGD), termasuk semua
                                    pemeriksaan penunjang.
                                    yang dibutuhkan untuk pengobatan dan tindakan yang aman.

                                </h1>
                            </div>
                        </div>
                        <div class="column is-12">
                            <h1 style="font-weight: bold; display: inline-block;">
                                SAYA TELAH MEMBACA
                            </h1>
                            <h1 style="display: inline-block; margin-left:5px">
                                dan sepenuhnya setuju dengan setiap pernyataan yang terdapat pada formulir ini dan
                                menandatangani tanpa paksaan dan dengan kesadaran penuh.
                            </h1>
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
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-6">
                                    <img v-if="input.petugasPerawat"
                                    :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat ? input.petugasPerawat.label : '-')">
                                </div>
                                <div class="column is-6 ">
                                    <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                </div>
                                <div class="column is-4">
                                    <h1 class="p-0" style="font-weight: bold;">Pemeberi Informasi </h1>
                                    <VField>
                                        <VControl class="prime-auto">
                                            <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Dokter"
                                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                                                @item-select="setTandaTangan($event)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4 is-offset-2">
                                    <h1 class="p-0" style="font-weight: bold;">Pasien/Penanggung Jawab Pasien</h1>
                                    <VField>
                                        <VControl>
                                            <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                                                v-model="input.pasienPenanggungjawab" />
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
const COLLECTION: any = ref('persetujuanUmumGawatDarurat') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    detailKeluarga: [{ no: 1 }]
})
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}

// List Pilihan
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'Laki - Laki' },
    { label: 'Perempuan', value: 'Perempuan' }
])
const dokterJabatan: any = ref([
    { label: 'Umum', value: 'Umum' },
    { label: 'Specialis', value: 'Specialis' },
    { label: 'Subspecialis', value: 'Subspecialis' }
])

const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan
                if (response[0].tandaTanganPerawat) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
                }
                if (response[0].tandaTanganPasien) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
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
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}
const addNewItemKeluarga = () => {
    input.value.detailKeluarga.push({
        no: input.value.detailKeluarga[input.value.detailKeluarga.length - 1].no + 1,
    });
}
const removeItemKeluarga = (index: any) => {
    input.value.detailKeluarga.splice(index, 1)
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
    input.value.petugasPerawat = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
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
<style lang="scss">
.table-fro {
    width: 100%;
    border: 1px solid black;
}

.th-fro,
.td-fro {
    padding: 7px;
    border: 1px solid black;
    vertical-align: inherit;
}

.setFRO-center {
    text-align: center !important;
}

.p-fieldset-legend {
    margin-left: 15px;
}

.tg {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
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
    vertical-align: middle
}

.tg th {
    border-color: var(--fade-grey-dark-3);
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
    vertical-align: top;
    font-weight: bold;
}
</style>
