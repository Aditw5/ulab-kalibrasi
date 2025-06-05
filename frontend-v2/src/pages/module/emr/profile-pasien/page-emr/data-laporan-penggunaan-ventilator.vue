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
                        <div class="columns is-multiline p-3">
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal dan Jam Pemasangan Ventilator</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalPemasanganVeltilator" mode="dateTime" style="width: 100%" trim-weeks
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
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal dan Jam Pelepasan Ventilator</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalPelepasanVentilator" mode="dateTime" style="width: 100%" trim-weeks
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
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal dan Jam Pemasangan Kembali Ventilator</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalPemasanganKembaliVeltilator" mode="dateTime" style="width: 100%" trim-weeks
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
                            <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Tanggal dan Jam Pelepasan Kembali Ventilator</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggalPelepasanKembaliVentilator" mode="dateTime" style="width: 100%" trim-weeks
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
                        <Fieldset class="p-fieldsets" legend="DATA PERUBAHAN VENTILATOR" :toggleable="true">
                            <div class="columns is-multiline">
                                <table align="center" class="triase" style="width: 90%; margin-top: 2rem;">
                                    <tr>
                                        <th colspan="9" style="text-align : center"> MODE / MODE PERUBAHAN VENTILATOR </th>
                                    </tr>
                                    <tr>
                                       <th colspan="2">Tanggal</th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal1" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal2" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal3" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal4" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal5" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal6" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                       <th class="bg-th">
                                        <VDatePicker v-model="input.tanggal7" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date"  :max-date="new Date()">
                                            <template #default="{ inputValue, inputEvents }">
                                            <VField>
                                                <VControl icon="feather:calendar">
                                                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                                                    class="is-rounded_Z input-30" style="width:150px" />
                                                </VControl>
                                            </VField>
                                            </template>
                                        </VDatePicker>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="text-align : center"> No </th>
                                        <th style="text-align : center"> Parameter Ventilator </th>
                                        <!-- <th style="text-align: center"> Isi Informasi </th> -->
                                        <!-- <th style="text-align: center"> Checklist </th> -->
                                    </tr>
                                    <tr v-for="(datas) in DaftarInformasi">
                                        <td>{{ datas.no }}</td>
                                        <td>{{ datas.Kriteria }}</td>
                                        <td v-for="(dot) in datas.keterangan">
                                            <div class="columns is 12">
                                                <div class="column">
                                                    <VField v-if="dot.type == 'checkBox'">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[dot.model]" :true-value="dot.title"
                                                                :label="dot.title" color="primary" square />
                                                        </VControl>
                                                    </VField>

                                                    <VField v-else="dot.type == 'textArea'">
                                                        <VControl>
                                                            <VTextarea rows="2" v-model="input[dot.model]"
                                                                placeholder="" />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>

                                        </td>
                                        <!-- <td>
                                            <div class="columns is-multiline">
                                                <div class="column is-12">
                                                    <VField v-for="(check) in datas.pilihan">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox class="p-0" v-model="input[check.model]"
                                                                :true-value="check.label" :label="check.label"
                                                                color="primary" square />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                        </td> -->

                                    </tr>
                                </table>

                            </div>

                        </Fieldset>
                    </div>
                </div>
                <div class="column is-12 pb-1">
                    <div>
                        <h3>Keterangan :</h3>
                        <p>Jenis Mode Ventilator :</p>
                        
                        <h4>A. Invasif Ventilator</h4>
                        <ol style="margin-left: 30px;">
                            <li>SIMV</li>
                            <li>CMV</li>
                            <li>PCV</li>
                            <li>ASV</li>
                            <li>PSIMV</li>
                            <li>DuoPAP</li>
                            <li>Spontan Ventilator</li>
                        </ol>
                        
                        <h4>B. Non Invasif Ventilator</h4>
                        <ol  style="margin-left: 30px;">
                            <li>CPAP</li>
                        </ol>
                   </div>

                 <div class="columns is-multiline" style="margin-top: 1rem;">
                     <div class="column is-12">
                        <div class="column is-3">
                        </div>
                        <div class="column is-3">
                        </div>
                       <div class="column is-3">
                                <h1 style="font-weight: bold; margin-bottom: 1rem;"> Cirebon, Tanggal dan Jam</h1>
                                <VField>
                                    <VDatePicker v-model="input.tanggal" mode="dateTime" style="width: 100%" trim-weeks
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
                 </div>

                <div class="column is-12 pb-1">

                    <div class="columns is-multiline" style="margin-top: 1rem;">
                        <div class="column is-12">
                            <div class="columns is-12">
                                <div class="column is-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
                                    <VField class="mt-3">
                                        <h1 style="font-weight: bold;">Petugas :</h1>
                                        <VControl>
                                            <VInput type="text" v-model="input.namaSaksi3" placeholder="Petugas" />
                                        </VControl>
                                    </VField>
                                </div>
                                <!-- <div class="column is-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature_4'" :width="'180'" :height="'180'"></TandaTangan>
                                    <VField class="mt-3">
                                        <h1 style="font-weight: bold;"> Saksi RS :</h1>
                                        <VControl>
                                            <VInput type="text" v-model="input.namaSaksi1" placeholder="Nama Lengkap" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature_5'" :width="'180'" :height="'180'"></TandaTangan>
                                    <VField class="mt-3">
                                        <h1 style="font-weight: bold;"> Saksi Pasien :</h1>
                                        <VControl>
                                            <VInput type="text" v-model="input.namaSaksi2" placeholder="Nama Lengkap" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3" style="text-align: center;">
                                    <TandaTangan :elemenID="'signature_6'" :width="'180'" :height="'180'"></TandaTangan>
                                    <VField class="mt-3">
                                        <h1 style="font-weight: bold;"> Yang Menyatakan :</h1>
                                        <VControl>
                                            <VInput type="text" v-model="input.namaYangMenyatakan"
                                                placeholder="Nama Lengkap" />
                                        </VControl>
                                    </VField>
                                </div> -->

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
import Fieldset from 'primevue/fieldset';
import { useUserSession } from '/@src/stores/userSession'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/data-laporan-penggunaan-ventilator'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let DaftarInformasi = ref(EMR.DaftarInformasi())

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
const isStuck = computed(() => { return y.value > 30 })
const d_Pegawai: any = ref([])
const d_Hubungan: any = ref([])
const isLoading: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('dataLaporanPenggunaanVentilator') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
    tanggal: new Date()
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
                if (response[0].tandaTanganWali2) {
                    H.tandaTangan().set("signature_3", response[0].tandaTanganWali2)
                }
                if (response[0].tandaTanganPegawai) {
                    H.tandaTangan().set("signature_1", response[0].tandaTanganPegawai)
                }
                if (response[0].tandaTanganWali1) {
                    H.tandaTangan().set("signature_2", response[0].tandaTanganWali1)
                }
                if (response[0].tandaTanganSaksi1) {
                    H.tandaTangan().set("signature_4", response[0].tandaTanganSaksi1)
                }
                if (response[0].tandaTanganSaksi2) {
                    H.tandaTangan().set("signature_5", response[0].tandaTanganSaksi2)
                }

                if (response[0].tandaTanganPernyataan) {
                    H.tandaTangan().set("signature_6", response[0].tandaTanganPernyataan)
                }

            }
        })
}
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'Laki - Laki' },
    { label: 'Perempuan', value: 'Perempuan' }
])
const HubunganDenganPasien: any = ref([
    { label: 'Diri Sendiri', value: 'Diri Sendiri' },
    { label: 'Suami', value: 'Suami' },
    { label: 'Istri', value: 'Istri' },
    { label: 'Anak', value: 'Anak' },
    { label: 'Orang Tua', value: 'Orang Tua' }
])
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const fetchHubungan = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
    d_Hubungan.value = response
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.tandaTanganPegawai = H.tandaTangan().get("signature_1")
    object.tandaTanganWali1 = H.tandaTangan().get("signature_2")
    object.tandaTanganWali2 = H.tandaTangan().get("signature_3")
    object.tandaTanganSaksi1 = H.tandaTangan().get("signature_4")
    object.tandaTanganSaksi2 = H.tandaTangan().get("signature_5")
    object.tandaTanganPernyataan = H.tandaTangan().get("signature_6")
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
    input.value.tanggalLahirPasien = props.pasien.tgllahir
    input.value.alamatPasien = props.pasien.alamatlengkap
    input.value.dokterRawat = props.registrasi.dokter
    input.value.tglPembuatan = new Date()
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(
        `/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPegawai = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}
const jumlahImdex = ref(7)
setView()
setAutoFill()
loadRiwayat()
</script>
<style lang="scss">
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

.p-fieldsets .p-fieldset-content {
    background: #ffffff;
}

table.triase {
    border-collapse: collapse;
    width: 100%;
}

table.triase,
.triase th,
.triase td {
    border: 0.5px solid grey;
}


.triase th,
.triase td {
    padding: 8px;
    vertical-align: middle !important;
}
</style>
