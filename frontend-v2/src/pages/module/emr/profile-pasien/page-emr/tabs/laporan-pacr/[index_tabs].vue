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

  <div class="columns is-12 mt-3">
    <VCard>
      <h3 class="title is-5 mb-2" style="text-align: center">Laporan PACF</h3>
      <p>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Nama Pasien:</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" placeholder="Nama Pasien" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Tgl. Lahir (Umur):</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" placeholder="Tanggal lahir pasien" v-model="input.tglLahir" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Janis Kelamin :</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" placeholder="Jenis Kelamin" v-model="input.jeniskelamin" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Nomor Rekam Medis :</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" placeholder="No Rekam Medis" v-model="input.noRM" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">No. Tindakan :</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" placeholder="No. Tindakan" v-model="input.noTindakan" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Tgl. Tindakan :</h1>
        </div>
        <div class="column is-10">
          <VDatePicker class="pt-3" v-model="input.tglTindakan" color="green" trim-weeks mode="date"
            :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }" class="pb-0">
              <VField>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded_Z" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Jaminan :</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" placeholder="Nao. Tindakan" v-model="input.jaminan" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-2">
          <h1 class="mt-3">Diagnosa/Ket. Klinik :</h1>
        </div>
        <div class="column is-10">
          <VField class="pt-3">
            <VControl>
              <VTextarea type="text" placeholder="Diagnosa/Ket. Klinik" v-model="input.diagnosaKlinik" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VField class="pt-3">
            <VControl>
              <VTextarea type="text" placeholder="Keterangan Laporan" v-model="input.keteranganLaporan" rows="15"/>
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <h1>Kesimpulan</h1>
        <div class="column is-12">
          <VField class="pt-3">
            <VControl>
              <VTextarea type="text" placeholder="Kesimpulan" v-model="input.kesimpulan" rows="5"/>
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <h1>Saran</h1>
        <div class="column is-12">
          <VField class="pt-3">
            <VControl>
              <VTextarea type="text" placeholder="Saran" v-model="input.saran" rows="5"/>
            </VControl>
          </VField>
        </div>
      </div>
      </p>
      <p>
      <div class="columns is-multiline">
        <div class="column is-1">
          <h1 class="mt-3">Cirebon</h1>
        </div>
        <div class="column is-8">
          <VDatePicker class="pt-3" v-model="input.tglDibuat" color="green" trim-weeks mode="datetime"
            :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }" class="pb-0">
              <VField>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded_Z" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <p class="label-ppap">Operator</p>
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="input.dokterDPJP" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                placeholder="Cari Dokter..." />
            </VControl>
          </VField>
          </div>
        </div>
      </div>
      </p>
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
import TandaTangan from '../../../page-emr-plugins/tanda-tangan.vue'
import Calendar from 'primevue/calendar'
import * as EMR from '../../../page-emr-plugins/persetujuan-pemasangan-restrain'
import VTextarea from '/@src/components/base/form/VTextarea.vue'

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

const d_Informasi : any = ref(EMR.PemberianInformasi())
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
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
const COLLECTION: any = ref("laporanPACR") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})

const hubunganPasien: any = ref([
    { label: 'Saya', value: 'Saya' },
    { label: 'Orangtua', value: 'Orangtua' },
    { label: 'Anak', value: 'Anak' },
    { label: 'Suami', value: 'Suami' },
    { label: 'Istri', value: 'Istri' },
    { label: 'Saudara', value: 'Saudara' },
    { label: 'Lain-lain', value: 'Lainlain' },
])

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
const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
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
  console.log(props.registrasi)
  input.value.namaPasien = props.pasien.namapasien
  input.value.tglLahir = `${H.formatDate(props.pasien.tgllahir, 'DD-MM-YYYY')} (${props.pasien.umur})`
  input.value.noRM = props.pasien.nocm
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.noTindakan = '- - - -PACR'
  input.value.tglTindakan = H.formatDate(new Date(), 'YYYY-MM-DD')
  input.value.keteranganLaporan = `
    1 Tindakan a/antiseptik di dareah pergelangan tangan kanan.
    2 Dilakukan anestesi sekitar arteri radialis kanan dengan Lidocain 2%
    3 Punksi arteri radialis kanan berjalan lancar [ISI INFORMASI] dimasukkan. Diberikan Heparin 5000 UI dan NTG mcg i.a.
    5 Dilakukan kanulasi LCA dan RCA dengan [ISI INFORMASI] angiografi menunjukan :
      LM    :   [ISI INFORMASI]
      LAD   :   [ISI INFORMASI]
      LCX   :   [ISI INFORMASI]
      RCA   :   [ISI INFORMASI]
    6 Tindakan selesai, hemodinamik pasien selama dan sesudah tindakan stabil.
    7 Kontras yang digunakan: [ISI INFORMASI] jumlah: [ISI INFORMASI] perdarahan [ISI INFORMASI]
  `
  // input.value.tanggalHandover = H.formatDateIndoNoTime(new Date())
  d_Informasi.value.forEach(info => {
      input.value[`jenis_informasi${info.model}`] = info.jenis_informasi;
      input.value[`isi${info.model}`] = info.isi_informasi;
  });
 }

 const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
  }
const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganDokter = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
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
