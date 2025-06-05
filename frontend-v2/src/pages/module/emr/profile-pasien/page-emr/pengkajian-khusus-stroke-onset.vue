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
            <Fieldset class="p-fieldsets" :legend="'FAKTOR RESIKO'" :toggleable="true">
                <div class="columns is-multiline mb-5">
                    <div class="column is-12 P-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4" v-for="(data, i) in faktorResiko">
                                    <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0" color="primary" square/>
                                    </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'MANIFESTASI KLINIS STROKE'" :toggleable="true">
                <div class="columns is-multiline mb-5">
                    <div class="column is-12 P-0">
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-4" v-for="(data, i) in manifestasiKlinisStroke">
                                    <VField>
                                    <VControl raw subcontrol>
                                        <VCheckbox v-model="input[data.model]" :true-value="data.value" :label="data.label" class="p-0" color="primary" square/>
                                    </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'EVALUASI ABC'" :toggleable="true">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-1">
                            <span>Airway :</span>
                        </div>
                        <div class="column is-11">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.evaluasiABCAirway">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-1">
                            <span>Breathing :</span>
                        </div>
                        <div class="column is-11">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.evaluasiABCBreathing">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-1">
                            <span>Circulation :</span>
                        </div>
                        <div class="column is-11">
                            <VField>
                                <VTextarea rows="2" placeholder="....." v-model="input.evaluasiABCCirculation">
                                </VTextarea>
                            </VField>
                        </div>
                    </div>
                </div>
            </Fieldset>
            <Fieldset class="p-fieldsets" :legend="'TANDA VITAL'" :toggleable="true">
                <div class="columns is-multiline">
                    <div class="column is-1">
                        <span>Keadaan Umum :</span>
                    </div>
                    <div class="column is-11">
                        <VField>
                            <VTextarea rows="1" placeholder="....." v-model="input.tandaVitalKeadaanUmum">
                            </VTextarea>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Kesadaran"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="E___M___V___" v-model="input.kesadaran"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Pupil"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="___/___" v-model="input.pupil"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mm</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline pt-3 pr-3 pl-3">
                    <div class="column is-3">
                        <VField label="Tekanan Darah"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>mmHG</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Nadi"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/mnt</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Pernapasan"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Pernapasan" v-model="input.pernapasan"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>x/mnt</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Suhu"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Suhu" v-model="input.suhu"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>°C </VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="SPO2"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="SPO2" v-model="input.SPO2"  />
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>%</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Tinggi Badan"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Tinggi Badan"  v-model="input.tinggiBadan"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Cm</VButton>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Berat Badan"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratBadan"/>
                            </VControl>
                            <VControl class="field-addon-body">
                                <VButton static>Kg</VButton>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Skor mRS pre admisi"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="_________" v-model="input.skorMRSPreAdmisi"/>
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Skor NIHSS"></VField>
                        <VField addons>
                            <VControl expanded>
                                <VInput type="text" class="input" placeholder="_________" v-model="input.skorNIHSS"/>
                            </VControl>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-1">
                        <span>CT Skan Kepala :</span>
                    </div>
                    <div class="column is-11">
                        <VField>
                            <VTextarea rows="2" placeholder="....." v-model="input.ctSkanKepala">
                            </VTextarea>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-1">
                        <span>EKG :</span>
                    </div>
                    <div class="column is-11">
                        <VField>
                            <VTextarea rows="2" placeholder="....." v-model="input.ekg">
                            </VTextarea>
                        </VField>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-1">
                        <span>Rontgen Thorax :</span>
                    </div>
                    <div class="column is-11">
                        <VField>
                            <VTextarea rows="2" placeholder="....." v-model="input.rontgenThorax">
                            </VTextarea>
                        </VField>
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
import * as EMR from '../page-emr-plugins/pengkajian-khusus-stroke-onset'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let faktorResiko = ref(EMR.faktorResiko())
let manifestasiKlinisStroke = ref(EMR.manifestasiKlinisStroke())

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
const fetchHubungan = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=${filter.query}&limit=10`)
    d_Hubungan.value = response
}
const fetchDokter = async (filter: any) => {
    const response = await useApi().get(`/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Dokter.value = response       
}
const fetchDropdown = async () => {
    // const response = await useApi().get(
    //   `/emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=&limit=10`)
    // d_KelompokPasien.value = response
    const response = await useApi().get(`/emr/dropdown/hubungankeluarga_m?select=id,hubungankeluarga&param_search=hubungankeluarga&query=&limit=10`)
    d_Hubungan.value = response
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
    useApi().get('emr/auto-fill?norec_pd=' + props.registrasi.norec_pd + '&collection=VitalSign' + '&field=tinggiBadan,beratBadan,tekananDarah,suhu,nadi,pernapasan,SPO2').then((response) => {
      if (response != null) {
        input.value.tinggiBadan = response.tinggiBadan ? response.tinggiBadan : ''
        input.value.beratBadan = response.beratBadan ? response.beratBadan : ''
        input.value.tekananDarah = response.tekananDarah ? response.tekananDarah : ''
        input.value.nadi = response.nadi ? response.nadi : ''
        input.value.suhu = response.suhu ? response.suhu : ''
        input.value.pernapasan = response.pernapasan ? response.pernapasan : ''
        input.value.SPO2 = response.SPO2 ? response.SPO2 : ''
      }
    })
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
fetchDropdown()
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
