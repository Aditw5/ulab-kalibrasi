<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }} </h3>
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
            <h3> {{ props.FORM_NAME }} </h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="columns is-12 mt-2">
    <VCard>
      <div class="column is-12 p-0">
        <p>Yang bertanda tangan dibawah ini</p>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Nama :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Nama" v-model="input.namaMenyatkan" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Bertindak atas nama :</span>
          </div>
          <div class="column is-10">
            <div class="columns is-multiline">
            <VField v-for="hub in hubunganPasien" :key="hub.value" class="p-0 m-0 pt-4 column is-2">
                <VControl raw subcontrol>
                    <VCheckbox v-model="input.hubunganPasien" class="pt-1 pb-1 "
                        :true-value="hub.value" :label="hub.label" color="primary" square />
                </VControl>
            </VField>
          </div>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          dari pasien
        </div>
      </div>
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
            <span> Tanggal lahir :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="Tanggal Lahir" v-model="input.umurTanggalLahir" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Alamat :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="Alamat" v-model="input.alamat" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> No Rekam Medis :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <VInput type="text" class="input" placeholder="No Telpon" v-model="input.noRM" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          Dengan ini menyatakan mengakhiri pendampingan dari Manajer Pelayanan Pasien RSD Gunung Jati Kota Cirebon dikarenakan :
        </div>
      </div>

            <table>

              <tbody>
                <tr v-for="cekListPernyataanPengakhiranMPP in d_cekListPernyataanPengakhiranMPP">
                  <td width="6%">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input[cekListPernyataanPengakhiranMPP.model]" color="primary" />
                      </VControl>
                    </VField>
                  </td>
                  <td 
                    width="80%" 
                    style="vertical-align: middle; padding: 4px;" 
                    v-html="cekListPernyataanPengakhiranMPP.isi_cekListPernyataanPengakhiranMPP"> 
                  </td>
                </tr>
              </tbody>
            </table>

      <div class="column is-12 p-0">
        <div class="is-flex">
          Demikian pernyataan ini saya tanda tangani dengan penuh kesadaran.
        </div>
      </div>

      <table class="tbl-kmNICU" style="margin-top: 30px;">
        <tr>
          <td></td>
          <td class="text-center">
            Cirebon, <VField>
              <VControl class="prime-auto">
                <Calendar v-model="input.tanggalVerif" selectionMode="single" :manualInput="true" class="w-100"
                  :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
              </VControl>
            </VField>
          </td>
        </tr>
        <tr>
          <!-- <td>
            <div class="column is-12 text-center">Dokter RSD Gunung Jati</div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter" class="mt-2"/>
                </VControl>
              </VField>
            </div>
            <div class="column is-12 text-center">Tanda Tangan </div>
            <div class="column is-12">
              <img v-if="input.dpjp"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjp.label ? input.dpjp.label : input.dpjp)">
            </div>
          </td> -->
          <td>
            <div class="column is-12 text-center">Manajer Pelayanan Pasien:</div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VInput type="text" class="input" placeholder="Nama Jelas" v-model="input.userMPP" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <TandaTangan :elemenID="'signatureMPP'" :width="'180'" :height="'180'" class="dek" />
               <!-- <TandaTangan 
                  :elemenID="`signatureMPP_${indexData}`" 
                  :width="'180'" 
                  :height="'180'" 
                  class="dek" 
                  :value="H.tandaTangan().get(`signatureMPP_${indexData}`) || '' " 
                /> -->
            </div>
          </td>
          <td>
            <div class="column is-12 text-center">Yang Membuat Pernyataan:</div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <VInput type="text" class="input" placeholder="Nama Jelas" v-model="input.namaMenyatakan" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12">
              <TandaTangan :elemenID="'signatureYangMenyatakan'" :width="'180'" :height="'180'" class="dek" />
                <!-- <TandaTangan 
                  :elemenID="`signatureYangMenyatakan_${indexData}`" 
                  :width="'180'" 
                  :height="'180'" 
                  class="dek" 
                  :value="H.tandaTangan().get(`signatureYangMenyatakan_${indexData}`) || '' " 
                /> -->
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
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../../../page-emr-plugins/cek-list-pernyataan-pengakhiran-mpp'

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


const d_cekListPernyataanPengakhiranMPP = ref(EMR.cekListPernyataanPengakhiranMPP())
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
const hubunganPasien: any = ref([
    { label: 'Diri Sendiri', value: 'Diri Sendiri' },
    { label: 'Suami', value: 'Suami' },
    { label: 'Istri', value: 'Istri' },
    { label: 'Anak', value: 'Anak' },
    { label: 'Orang Tua', value: 'Orang Tua' },
    { label: 'Keluarga', value: 'Keluarga' },
])
// const ruanganKhusus: any = ref([
//     { label: 'Intensive Care Unit (ICU)', value: 'ICU' },
//     { label: 'Intensive Cardiac Care Unit (ICCU)', value: 'ICCU' },
//     { label: 'High Care Unit (ICCU)', value: 'HCU' },
//     { label: 'Neonatus Intensive Care Unit (NICU)', value: 'NICU' },
//     { label: 'Pediatric Intensive Care Unit (PICU)', value: 'PICU' },
//     { label: 'Isolasi LEVEL III Neonatus', value: 'Isolasi' },
// ])
// const ruanganUmum: any = ref([
//     { label: 'Intermediate Ward (IW)', value: 'IW' },
//     { label: 'Kelas 3', value: 'kelas3' },
//     { label: 'Kelas 2', value: 'kelas2' },
//     { label: 'Kelas 1', value: 'kelas1' },
//     { label: 'Kelas VIP', value: 'kelasVIP' },
// ])
// const bedahPrima: any = ref([
//     { label: 'Perawatan', value: 'Perawatan' },
//     { label: 'One Day Care (Non Perawatan)', value: 'nonPerawatan' },
// ])
const JenisKelamin: any = ref([
    { label: 'Laki - Laki', value: 'Laki - Laki' },
    { label: 'Perempuan', value: 'Perempuan' }
])
const COLLECTION: any = ref("suratPernyataanPengakhiranMPP") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  // tanggalMRS: new Date(),
  // tanggalMNICU: new Date(),
  // tanggalKNICU: new Date(),
  tanggalVerif: H.formatDate(new Date(), 'YYYY-MM-DD HH:mm'),
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
        if (response[0].ttdMPP) {
          H.tandaTangan().set("signatureMPP", response[0].ttdMPP)
        }
        if (response[0].ttdYangMenyatakan) {
          H.tandaTangan().set("signatureYangMenyatakan", response[0].ttdYangMenyatakan)
        }
      }
    })
  loadData.value = false
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  // input.value.tanggalMRS = H.formatDate(new Date(input.value.tanggalMRS), 'YYYY-MM-DD HH:mm')
  // input.value.tanggalKNICU = H.formatDate(new Date(input.value.tanggalKNICU), 'YYYY-MM-DD HH:mm')
  // input.value.tanggalMNICU = H.formatDate(new Date(input.value.tanggalMNICU), 'YYYY-MM-DD HH:mm')
  object.ttdMPP = H.tandaTangan().get("signatureMPP")
  object.ttdYangMenyatakan = H.tandaTangan().get("signatureYangMenyatakan")
  input.value.tanggalInput = H.formatDate(new Date(input.value.tanggalVerif), 'YYYY-MM-DD HH:mm')
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
  input.value.umurTanggalLahir = props.pasien.tgllahir
  input.value.noRM = props.pasien.nocm
  input.value.jenisKelamin = props.pasien.jeniskelamin
  input.value.ruangan = props.registrasi.namaruangan
  input.value.alamat = props.pasien.alamatlengkap
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
