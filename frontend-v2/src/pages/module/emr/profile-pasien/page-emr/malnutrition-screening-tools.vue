<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
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
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="Malnutrition Screening Tooll (MST)" :toggleable="true">
          <div class="column is-12">
            <div class="columns is-multiline px-5" style="border-bottom: 1px solid;">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-10">
                    <h1 class="emr">Pertanyaan</h1>
                  </div>
                  <div class="column is-2">
                    <h1 class="emr">Skor</h1>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline px-3">
              <div class="column is-12" v-for="(data, i) in skriningGizi">
                <span class="label-pengkajian"> {{ data.label }}</span>
                <div class="px-3">
                  <div class=" px-1" v-for="(dataDetail, i) in data.children">
                    <div class="columns is-multiline">
                      <div class="column is-8">
                        <span class="label-pengkajian"> {{ dataDetail.label }}</span>
                      </div>
                      <div class="column is-2">
                        <VField v-if="dataDetail.children == null">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input[dataDetail.model]" :true-value="dataDetail.value"
                              :label="dataDetail.text" class="p-0" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-2">
                        {{ dataDetail.value }}
                      </div>
                    </div>
                    <div class="px-1" v-if="dataDetail.children">
                      <div class="columns is-multiline px-1" v-for="(dataDetailChildren, i) in dataDetail.children">
                        <div class="column is-8">
                        </div>
                        <div class="column is-2">
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[dataDetailChildren.model]" :true-value="dataDetailChildren.value"
                                :label="dataDetailChildren.label" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                        <div class="column is-2">
                          {{ dataDetailChildren.value }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="column is-10">
                <div class="is-pulled-right">
                  <span style="font-weight: bold;font-size:15px;" class="label-pengkajian mt-3">Total
                    Skor</span>
                  <VField class="pt-3">
                    <VControl>
                      <VInput type="text" v-model="input.totalSkor" disabled />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
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
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/malnutrition-screening-tools'
import { async } from '@firebase/util'
import MultiSelect from 'primevue/multiselect';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let skriningGizi = ref(EMR.skriningGizi())
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
const isLoadingDropdown: any = ref(false)
const isLoadingVitalSign: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('MalnutritionScreeningTools') //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
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

watch(() => [
  input.value.turunBeratBadan,
  input.value.tidakAdaTurunBeratBadan,
  input.value.asupanMakan,
], () => {
  let poin1 = input.value.turunBeratBadan ? parseInt(input.value.turunBeratBadan) : 0
  let poin2 = input.value.tidakAdaTurunBeratBadan ? parseInt(input.value.tidakAdaTurunBeratBadan) : 0
  let poin3 = input.value.asupanMakan ? parseInt(input.value.asupanMakan) : 0

  const total = poin1 + poin2 + poin3
  input.value.totalSkor = total
})
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.umur = props.pasien.namapasien
  input.value.namaruangan = props.registrasi.namaruangan

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if(response != null){
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
    }
    isLoadingVitalSign.value =false;
  })
}

setView()
setAutoFill()
loadRiwayat()
</script>

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

.label-pengkajian {
  font-weight: 400;
}

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}
</style>

