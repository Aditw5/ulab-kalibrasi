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
            <Fieldset class="p-fieldsets" :legend="'Indikasi Terapi Trombolisis r-TPA Intravena (SEMUA HARUS YA)'" :toggleable="true">
                <div class="columns is-multiline">
                    <div class="column is-12" v-for="(data, index) in indikasiTrombolisis" :key="index">
                        <h1 class="emr bold">{{ data.title }}</h1>
                        <div class="column is-12" v-for="(category, subIndex) in data.detail" :key="subIndex">
                            <div class="columns is-multiline p-3">
                                <div class="column is-4">
                                    <h1 class="mb-2">{{ category.label }}</h1>
                                </div>
                                <div class="column is-2">
                                    <div class="columns is-multiline pt-3">
                                        <div v-for="(option, optionIndex) in category.options" :key="optionIndex">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[category.model]" :true-value="option.value" :label="option.label" class="p-0" color="primary" square
                                                    style="margin-right: 10px;"
                                                    />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6" v-if="data.keterangan = true">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input[category.model + 'Keterangan']" placeholder="keterangan . . . " />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'Kontraindikasi Terapi Trombolisis r-TPA Intravena (SEMUA HARUS TIDAK)'" :toggleable="true">
                <div class="columns is-multiline" v-for="(child, index) in kontradiksiTrombolisis" :key="index">
                    <div class="column is-12" v-for="(data, index) in child.children" :key="index">
                        <h1 class="emr bold">{{ data.title }}</h1>
                        <div class="column is-12" v-for="(category, subIndex) in data.detail" :key="subIndex">
                            <div class="columns is-multiline p-3">
                                <div class="column is-4">
                                    <h1 class="mb-2">{{ category.label }}</h1>
                                </div>
                                <div class="column is-2">
                                    <div class="columns is-multiline pt-3">
                                        <div v-for="(option, optionIndex) in category.options" :key="optionIndex">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[category.model]" :true-value="option.value" :label="option.label" class="p-0" color="primary" square
                                                    style="margin-right: 10px;"
                                                    />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6" v-if="data.keterangan = true">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input[category.model + 'Keterangan']" placeholder="keterangan . . . " />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'Penyulit Terapi Trombolisis r-TPA Intravena (KONSUL DIVISI NEUROVASKULAR BILA YA)'" :toggleable="true">
                <div class="columns is-multiline">
                    <div class="column is-12" v-for="(data, index) in penyulitTerapi" :key="index">
                        <h1 class="emr bold">{{ data.title }}</h1>
                        <div class="column is-12" v-for="(category, subIndex) in data.detail" :key="subIndex">
                            <div class="columns is-multiline p-3">
                                <div class="column is-4">
                                    <h1 class="mb-2">{{ category.label }}</h1>
                                </div>
                                <div class="column is-2">
                                    <div class="columns is-multiline pt-3">
                                        <div v-for="(option, optionIndex) in category.options" :key="optionIndex">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[category.model]" :true-value="option.value" :label="option.label" class="p-0" color="primary" square
                                                    style="margin-right: 10px;"
                                                    />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6" v-if="data.keterangan = true">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input[category.model + 'Keterangan']" placeholder="keterangan . . . " />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline" v-for="(child, index) in penyulitTerapiTrombolisis" :key="index">
                    <div class="column is-12" v-for="(data, index) in child.children" :key="index">
                        <h1 class="emr bold">{{ data.title }}</h1>
                        <div class="column is-12" v-for="(category, subIndex) in data.detail" :key="subIndex">
                            <div class="columns is-multiline p-3">
                                <div class="column is-4">
                                    <h1 class="mb-2">{{ category.label }}</h1>
                                </div>
                                <div class="column is-2">
                                    <div class="columns is-multiline pt-3">
                                        <div v-for="(option, optionIndex) in category.options" :key="optionIndex">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[category.model]" :true-value="option.value" :label="option.label" class="p-0" color="primary" square
                                                    style="margin-right: 10px;"
                                                    />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-6" v-if="data.keterangan = true">
                                    <VField>
                                        <VControl>
                                            <VInput type="text" v-model="input[category.model + 'Keterangan']" placeholder="keterangan . . . " />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'III. TENAGA MEDIS'" :toggleable="true">
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
            </Fieldset>
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
import * as EMR from '../page-emr-plugins/checklist-screening-terapi-r-tpa'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let indikasiTrombolisis = ref(EMR.indikasiTrombolisis())
let penyulitTerapi = ref(EMR.penyulitTerapi())
let penyulitTerapiTrombolisis = ref(EMR.penyulitTerapiTrombolisis())
let kontradiksiTrombolisis = ref(EMR.kontradiksiTrombolisis())

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
