<template>
    <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
        <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
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
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <span>Ruang Rawat / Unit Kerja :</span>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VTextarea rows="1" placeholder="....." v-model="input.ruangRawat">
                            </VTextarea>
                        </VField>
                    </div>
                    <div class="column is-1">
                        <span>BB :</span>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VTextarea rows="1" placeholder="....." v-model="input.Beratbadan">
                            </VTextarea>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column" style="overflow: auto;">
                <table class="tg">
                    <thead>
                            <tr>
                                <td class="tg-0lax text-center" colspan="2"></td>
                                <td class="tg-0lax text-center">Waktu</td>
                                <td class="tg-0lax text-center">TD (mmHg)</td>
                                <td class="tg-0lax text-center">GCS</td>
                                <td class="tg-0lax text-center">NIHSS</td>
                                <td class="tg-0lax text-center">Keterangan</td>
                                <td class="tg-0lax text-center">Nama Penilai</td>
                                <td class="tg-0lax text-center">ttd</td>
                            </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, index) in observasiPasca" :key="index">
                            <!-- Check for rowspan, apply dynamically -->
                            <td v-if="data.rowspan" :rowspan="data.jumlahrowspan" v-bind:colspan="data.colspan ? data.jumlahcolspan : null">
                                {{ data.td1 }}
                            </td>
                            <!-- Check for colspan, apply dynamically -->
                            <td v-if="data.colspan && !data.rowspan" :colspan="data.jumlahcolspan">
                                {{ data.td1 }}
                            </td>
                            <td v-else>{{ data.td2 }}</td>
                            <td>
                                <VField>
                                    <VDatePicker v-model="input[data.model + '_Waktu']" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }">
                                        <VField>
                                            <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                            </VControl>
                                        </VField>
                                        </template>
                                    </VDatePicker>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VControl class="mt-2">
                                        <VInput type="text" placeholder=". . ." class="is-rounded"
                                        v-model="input[data.model + '_TD']" />
                                    </VControl>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VControl class="mt-2">
                                        <VInput type="text" placeholder=". . ." class="is-rounded"
                                        v-model="input[data.model + '_GCS']" />
                                    </VControl>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VControl class="mt-2">
                                        <VInput type="text" placeholder=". . ." class="is-rounded"
                                        v-model="input[data.model + '_NIHSS']" />
                                    </VControl>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VControl class="mt-2">
                                        <VInput type="text" placeholder=". . ." class="is-rounded"
                                        v-model="input[data.model + '_Keterangan']" />
                                    </VControl>
                                </VField>
                            </td>
                            <td>
                                <VField>
                                    <VControl class="prime-auto">
                                        <AutoComplete v-model="input[data.model + '_Penilai']" :suggestions="d_Dokter" @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama..." class="mt-2"/>
                                    </VControl>
                                </VField>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-2">
                        <span>NIHSS Pulang :</span>
                    </div>
                    <div class="column is-4">
                        <VField>
                            <VTextarea rows="1" placeholder="....." v-model="input.nihssPulang">
                            </VTextarea>
                        </VField>
                    </div>
                    <div class="column is-1">
                        <span>mRS Pulamg :</span>
                    </div>
                    <div class="column is-5">
                        <VField>
                            <VTextarea rows="1" placeholder="....." v-model="input.mrsPulang">
                            </VTextarea>
                        </VField>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <h1 style="font-weight: bold">Dokter</h1>
                        <h1 style="font-weight: bold">Tanggal dan Jam</h1>
                        <VDatePicker v-model="input.tglPengkajianDokter" class="pt-3" mode="dateTime" style="width: 100%" trim-weeks :max-date="new Date()">
                            <template #default="{ inputValue, inputEvents }">
                                <VField>
                                    <VControl icon="feather:calendar" fullwidth>
                                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"/>
                                    </VControl>
                                </VField>
                            </template>
                        </VDatePicker>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <img v-if="input.dokterPengkajian"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterPengkajian.label ? input.dokterPengkajian.label : input.dokterPengkajian) + ', ' + (input.tglPengkajianDokter ? input.tglPengkajianDokter : input.tglPengkajianDokter)">
                        <!-- <TandaTangan
                        :elemenID="'signature_1'"
                        :width="'180'"
                        :height="'180'"
                        ></TandaTangan> -->
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div class="columns is-multiline">
                    <div class="column is-4">
                        <h1 class="p-0" style="font-weight: bold">Dokter</h1>
                        <VField>
                            <VControl class="prime-auto">
                                <AutoComplete v-model="input.dokterPengkajian" :suggestions="d_Dokter" @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter..." class="mt-2" @item-select="setTandaTangan($event)"/>
                            </VControl>
                        </VField>
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import Fieldset from 'primevue/fieldset';
import * as EMR from '../page-emr-plugins/observasi-pasca-r-tpa'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let observasiPasca = ref(EMR.observasiPasca())

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
const d_Hubungan: any = ref([])
const d_Dokter: any = ref([])
const d_KelompokPasien: any = ref([])
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
    useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
        if (response.length) {
            input.value = response[0] //set ke inputan
            if (NOREC_EMRPASIEN.value == '') {
                NOREC_EMRPASIEN.value = response[0].emrpasienfk
            }
        }
    })
}
const fetchHubungan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
    d_Hubungan.value = response
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response       
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
    useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
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
    input.value.tglPengkajianDokter = new Date()
    input.value.dokterPengkajian = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
}
const setTandaTangan = async (e: any) => {
    const response = await useApi().get(`/emr/tanda-tangan/${e.value.value}`)
    if (response != null) {
        H.tandaTangan().set("signature_1", response.ttd)
        input.value.tandaTanganPerawat = response.ttd
    } else {
        H.tandaTangan().set("signature_1", '')
    }
}

setView()
setAutoFill()
loadRiwayat()
</script>
<style  lang="scss">
#signature {
border: double 3px transparent;
border-radius: 5px;
background-image: linear-gradient(white, white), radial-gradient(circle at top left, #4bc5e8, #9f6274);
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
