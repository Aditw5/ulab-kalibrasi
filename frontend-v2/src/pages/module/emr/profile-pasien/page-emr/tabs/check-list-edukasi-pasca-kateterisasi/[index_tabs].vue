<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }} Halaman {{ route.params.index_tabs }}</h3>
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
            <h3> {{ props.FORM_NAME }} Halaman {{ route.params.index_tabs }}</h3>
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

  <div class="columns is-12">
    <VCard>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Nama :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Nama" v-model="input.nama" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Umur/Tanggal lahir :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="Umur/Tanggal Lahir" v-model="input.umurTanggalLahir" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> No. RM :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="No. RM" v-model="input.noRM" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Jenis Kelamin :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="Jenis Kelamin" v-model="input.jenisKelamin" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Ruang :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="Ruang" v-model="input.ruangan" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <table class="tbl-kmNICU">
        <thead>
          <tr>
            <th>No</th>
            <th>Edukasi</th>
            <th>Cek List</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="edukasi in d_Edukasi">
            <td width="2%">{{edukasi.no}}</td>
            <td v-html="edukasi.isi_edukasi"></td>
            <td width="6%">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[edukasi.model]" color="primary" />
                </VControl>
              </VField>
            </td>
          </tr>
        </tbody>
      </table>

      <table class="tbl-kmNICU" style="margin-top: 30px;">
        <tr>
          <td colspan="3" class="text-center">
            Cirebon, <VField>
              <VControl class="prime-auto">
                <Calendar v-model="input.tanggalVerif" selectionMode="single" :manualInput="true" class="w-100"
                  :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
              </VControl>
            </VField>
          </td>
        </tr>
        <tr>
          <td>
            <div class="column is-12 text-center">Penerima Informasi</div>
            <div class="column is-12">
              <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="Penerima Informasi" v-model="input.penerimaInformasi" />
              </VControl>
            </VField>
            </div>
          </td>
          <td>
            <div class="column is-12 text-center">PPA: </div>
            <div class="column is-12">
              <VField>
                    <VControl class="prime-auto">
                      <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                        :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                        :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP" class="mt-2"/>
                    </VControl>
                  </VField>
            </div>
          </td>
          <td>
            <div class="column is-12 text-center">Tanda Tangan </div>
            <div class="column is-12">
              <img v-if="input.dpjp"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjp.label ? input.dpjp.label : input.dpjp)">
            </div>
          </td>
        </tr>
      </table>
    </VCard>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import {
  h,
  reactive,
  ref,
  computed,
  defineComponent,
  watch,
  onMounted,
  onBeforeMount,
} from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave, onBeforeRouteUpdate } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../../../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Divider from 'primevue/divider'
import Calendar from 'primevue/calendar'
import * as EMR from '../../../page-emr-plugins/cek-list-edukasi-pasca-kateterisasi'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const route = useRoute()
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

const d_Edukasi = ref(EMR.cekListEdukasiPascaKateterisasi())
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const loadData: any = ref(true)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref("CheckListEdukasiPascaKateterisasi") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  tanggalMRS: new Date(),
  tanggalMNICU: new Date(),
  tanggalKNICU: new Date(),
  tanggalVerif: new Date(),
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const loadRiwayat = async () => {
  loadData.value = true
  await useApi()
    .get(
      `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}&index_tabs=${route.params.index_tabs}`
    )
    .then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
  loadData.value = false
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  input.value.tanggalMRS = H.formatDate(new Date(input.value.tanggalMRS), 'YYYY-MM-DD HH:mm')
  input.value.tanggalKNICU = H.formatDate(new Date(input.value.tanggalKNICU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalMNICU = H.formatDate(new Date(input.value.tanggalMNICU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalVerif = H.formatDate(new Date(input.value.tanggalVerif), 'YYYY-MM-DD HH:mm')
  if (route.params.index_tabs) {
  object.index_tabs = Array.isArray(route.params.index_tabs)
    ? parseInt(route.params.index_tabs[0])
    : parseInt(route.params.index_tabs);
}

  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
  }
  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.nama = props.pasien.namapasien
  input.value.umurTanggalLahir = `${props.pasien.umur} / ${props.pasien.tgllahir}`
  input.value.noRM = props.pasien.nocm
  input.value.jenisKelamin = props.pasien.jeniskelamin
  input.value.ruangan = props.registrasi.namaruangan
 }
onBeforeMount(async () => {
  try {
    await loadRiwayat()
    let rouutename = String(route.name) + '-' + route.params.index_tabs;
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) input.value = cache
  } catch (error) {
    console.error('Error mount cache TAB EMR:', error)
  }
})

onBeforeRouteLeave((to, from, next) => {
  try {
    let rouutename = String(from?.name) + '-' + (Array.isArray(route.params.index_tabs) ? route.params.index_tabs[0] : route.params.index_tabs);
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
  } catch (error) {
    console.error('Error leave cache TAB EMR:', error)
  }
  next()
})
watch(
  () => route.params.index_tabs,
  (newValue, oldValue) => {
    input.value = {}
      setAutoFill()
    loadRiwayat()
    let rouutename = String(route.name) + '-' + route.params.index_tabs;
    let cache = H.cacheEMR().get(`TAB~${props.registrasi.noregistrasi}~${rouutename}`)
    if (cache) {
      input.value = cache
    }
  }
)
watch(
  () => input.value,
  (newValue, oldValue) => {
    let rouutename = String(route.name) + '-' + route.params.index_tabs;
    H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, input.value)
    let timeout = null
    if (timeout) {
      clearTimeout(timeout)
    }
    timeout = setTimeout(() => {
      H.cacheEMR().set(`TAB~${props.registrasi.noregistrasi}~${rouutename}`, newValue)
    }, 500)
  },
  { deep: true }
)

setAutoFill()
setView()
loadRiwayat()
</script>
<style scoped lang="scss">
.tbl-kmNICU {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
}

.tbl-kmNICU tr,
.tbl-kmNICU th,
.tbl-kmNICU td {
  border: 1px solid black;
  padding: 5px;
}
</style>
