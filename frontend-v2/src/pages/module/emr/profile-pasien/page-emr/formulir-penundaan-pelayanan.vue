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
        <VCard class="p-3">
          <div class="column is-12 px-2">
            <div class="columns is-multiline">
              <div class="column is-4">
                <VField label="Ruang">
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
              <div class="column is-4">
                <VField label="DPJP">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
              <div class="column is-4">
                <VField label="Tanggal Tindakan">
                  <VDatePicker v-model="input.tanggalDilakukanTindakan" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" readonly />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField>
              </div>
            </div>
          </div>
        </VCard>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="A. JENIS PELAYANAN YANG DITUNDA" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> Pemeriksaan Penunjang : </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="...."
                          v-model="input.jenisDitundaPemeriksaanPenunjang" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> Tindakan Medis : </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="...." v-model="input.jenisDitundaTindakanMedis" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> Lain-Lain : </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="...." v-model="input.jenisDitundaLainLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="B. ALASAN PENUNDAAN PELAYANAN" :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> Peralatan : </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="...." v-model="input.alasanPenundaanPeralatan" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> SDM : </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="...." v-model="input.alasanPenundaanSDM" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span> Lain-Lain : </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="...." v-model="input.alasanPenundaanLainLain" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="C. ALTERNATIF PELAYANAN YANG DAPAT DIBERIKAN PADA PASIEN"
          :toggleable="true">
          <div class="columns is-multiline mb-5">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <VField>
                      <VTextarea rows="2" placeholder="........." v-model="input.alternatifPelayananPasien"></VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;"> Petugas Yang Menjelaskan</h1>
            <div>
              <img v-if="input.petugasPetugas"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPetugas ? input.petugasPetugas.label : '-')">
            </div>
            <div>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.petugasPetugas" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Pegawai..."
                    class="mt-2" @item-select="setTandaTangan($event)" />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;"> Pasien/Keluarga Pasien</h1>
            <div>
              <TandaTangan :elemenID="'signature_3'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div>
              <VField class="mt-2">
                <VControl class="prime-auto">
                  <VInput type="text" class="input prime-auto" placeholder="Pasien/Keluarga" v-model="input.namapasien"
                    :suggestions="d_Pasien" @complete="fetchPasien($event)" />
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pj'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let vitalSign = ref(EMR.vitalSign())

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    diagnosis?: any
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
const isLoadingVitalSign: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const d_Pasien: any = ref([])
const d_produk: any = ref([])
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const input: any = ref({
  detailObatDibawaPasien: [{ no: 1 }]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (response[0].tandaTanganPetugas) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPetugas)
        }
        if (response[0].tandaTanganPasienKeluarga) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPasienKeluarga)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const addNewItemObatDibawaPulang = () => {
  input.value.detailObatDibawaPasien.push({
    no: input.value.detailObatDibawaPasien[input.value.detailObatDibawaPasien.length - 1].no + 1,
  });
}
const removeItemObatDibawaPulang = (index: any) => {
  input.value.detailObatDibawaPasien.splice(index, 1)
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const fetchPasien = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pasien_m?select=id,namapasien&param_search=namapasien&query=${filter.query}&limit=10`)
  d_Pasien.value = response
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

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPetugas = H.tandaTangan().get("signature_2")
  object.tandaTanganPasienKeluarga = H.tandaTangan().get("signature_3")
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


let kesadaran = ref([
  {
    "no": 1,
    "parameter": "E",
    "nilai": [
      {
        "model": "kesadaranE",
        "poin": "1"
      },
      {
        "model": "kesadaranE",
        "poin": "2"
      },
      {
        "model": "kesadaranE",
        "poin": "3"
      },
      {
        "model": "kesadaranE",
        "poin": "4"
      },
    ]
  },
  {
    "no": 2,
    "parameter": "M",
    "nilai": [
      {
        "model": "kesadaranM",
        "poin": "1"
      },
      {
        "model": "kesadaranM",
        "poin": "2"
      },
      {
        "model": "kesadaranM",
        "poin": "3"
      },
      {
        "model": "kesadaranM",
        "poin": "4"
      },
      {
        "model": "kesadaranM",
        "poin": "5"
      },
      {
        "model": "kesadaranM",
        "poin": "6"
      },
    ]
  },
  {
    "no": 3,
    "parameter": "V",
    "nilai": [
      {
        "model": "kesadaranV",
        "poin": "5"
      },
      {
        "model": "kesadaranV",
        "poin": "4"
      },
      {
        "model": "kesadaranV",
        "poin": "3"
      },
      {
        "model": "kesadaranV",
        "poin": "2"
      },
      {
        "model": "kesadaranV",
        "poin": "1"
      },
    ]
  },
])

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.dokter = props.registrasi.dokter
  input.value.namapasien = props.pasien.namapasien
  input.value.petugasPetugas = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if (response != null) {
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
    }
    isLoadingVitalSign.value = false;
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
