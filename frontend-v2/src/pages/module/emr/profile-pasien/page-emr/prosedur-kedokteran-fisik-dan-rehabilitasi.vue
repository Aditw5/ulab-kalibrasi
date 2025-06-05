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
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama Pasien : </span>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama Pasien... " v-model="input.namaPasien" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2" style="margin-left:5rem; margin-top:0.5rem">
                <span> Jenis Kelamin : </span>
              </div>
              <div class="column is-4" style="margin-left:-5rem">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis Kelamin.. " v-model="input.jeniskelamin" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal Lahir: </span>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Tanggal Lahir... " v-model="input.tgllahir" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2" style="margin-left:5rem; margin-top:0.5rem">
                <span> Tanggal Pemeriksaan: </span>
              </div>
              <div class="column is-4" style="margin-left:-5rem">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglPemeriksaan" mode="dateTime" style="width: 100%" trim-weeks
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
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Usia : </span>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Usia... " v-model="input.umur" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Alamat : </span>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Alamat... " v-model="input.alamatlengkap" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2" style="margin-left:5rem; margin-top:0.5rem">
                <span> Diagnosa Fungsional : </span>
              </div>
              <div class="column is-4" style="margin-left:-5rem">
                <VField>
                  <VControl>
                    <!-- <VInput type="text" class="input" placeholder="Fungsional.. " v-model="input.fungsional" /> -->
                    <VTextarea v-model="input.fungsional" rows="3" placeholder="Fungsional.. "></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Telepon: </span>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Telp/HP... " v-model="input.nohp" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2" style="margin-left:5rem; margin-top:0.5rem">
                <span> Diagnosis Medis : </span>
              </div>
              <div class="column is-4" style="margin-left:-5rem">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.diagnosa" rows="3" placeholder="Diagnosa "></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-6">
                <span> Instrumen Uji Fungsi/Prosedur KFR </span>
                <VField class="mt-2">
                  <VTextarea v-model="input.instrumenUjiFungsiProsedurKFR" rows="3" placeholder="Instrumen Uji Fungsi/Prosedur KFR "></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <span> Hasil Yang Didapat </span>
                <VField class="mt-2">
                  <VTextarea v-model="input.hasilYangDidapat" rows="3" placeholder="Hasil Yang Didapat"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-6">
                <span>Kesimpulan </span>
                <VField class="mt-2">
                  <VTextarea v-model="input.kesimpulan" rows="3" placeholder="Kesimpulan"></VTextarea>
                </VField>
              </div>
              <div class="column is-6">
                <span> Rekomendasi </span>
                <VField class="mt-2">
                  <VTextarea v-model="input.rekomendasi" rows="3" placeholder="Rekomendasi"></VTextarea>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;">Tanda Tangan Dokter Pemeriksa</h1>
            <div>
              <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
              <img v-if="input.dokterPemeriksa"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterPemeriksa ? input.dokterPemeriksa.label : '-')">
            </div>
            <div class="mt-4">
              <VField>
                <VControl class="prime-auto">
                  <VControl class="prime-auto">
                      <AutoComplete v-model="input.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Data..."
                        class="mt-2" @item-select="setTandaTangan($event, 'signature_1')" />
                    </VControl>
                </VControl>
              </VField>
            </div>
          </VCard>
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
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-prosedur.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/surat-pengantar-rawat-inap'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let checked = ref(EMR.checked())

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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const d_JK: any = ref([])
const isLoadingTT = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("UjiFungsiProsedurKedokteranFisikdanRehabilitasi") //table mongodb
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
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
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
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
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

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}
const fetchJenisKelamin = async () => {
  const response = await useApi().get(
    `/emr/dropdown/jeniskelamin_m?select=id,jeniskelamin&param_search=&`)
  d_JK.value = response
}
const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    }
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  fetchJenisKelamin()
  input.value.tglPembuatan = new Date()
  input.value.tglPemeriksaan = new Date()
  input.value.namaPasien = props.pasien.namapasien
  input.value.nocm = props.pasien.nocm
  input.value.alamatlengkap = props.pasien.alamatlengkap
  input.value.tgllahir = props.pasien.tgllahir
  input.value.nohp = props.pasien.nohp
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.tanggalWaktuKejadian = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi
  input.value.dokter = props.registrasi.dokter
  input.value.umur = props.pasien.umur

  await useApi().get(
    `/emr/auto-fill-icd10/${props.registrasi.noregistrasi}`).then((response: any) => {
      let kdnamadiagnosa = []
      let kdnamadiagnosaUatama = []
      for (let i = 0; i < response.length; i++) {
        const element = response[i];
        // kdnamadiagnosa.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa)
        if (response[i].objectjenisdiagnosafk === 1) {
          kdnamadiagnosaUatama.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa);
        }
        if (response[i].objectjenisdiagnosafk === 2) {
          kdnamadiagnosa.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa);
        }
      }
      input.value.diagnosa = kdnamadiagnosa.join(',');
      input.value.fungsional = kdnamadiagnosaUatama.join(',');
    })

  await useApi().get(
    `/emr/auto-fill-icd9/${props.registrasi.noregistrasi}`).then((response: any) => {
      let kdnamadiagnosa9 = []
      // console.log(response)
      for (let i = 0; i < response.length; i++) {
        const element = response[i];
        kdnamadiagnosa9.push(response[i].kddiagnosatindakan + '~' + response[i].namadiagnosatindakan)
      }
      input.value.instrumenUjiFungsiProsedurKFR = kdnamadiagnosa9.join(',');
    })

    useApi().get(
    `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=CatatanPerkembanganPasienTerintegrasi&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length > 0) {
        const CPPT = response[0].details[0]
        input.value.hasilYangDidapat = CPPT.O
        input.value.rekomendasi = CPPT.P
      }
    })
    if(useUserSession().getUser().kelompokUser.kelompokUser == 'dokter'){
      input.value.dokterPemeriksa = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    }else{
      input.value.dokterPemeriksa = {label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk}
    }

}
// console.log(JSON.stringify(props.pasien));

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
</style>
