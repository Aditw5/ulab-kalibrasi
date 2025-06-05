<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <!-- <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div> -->
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <!-- <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div> -->
        </div>
      </div>

    </div>
  </div>

  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column" style="overflow: auto;">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th class="tg-0lax text-center" rowspan="2">TGL</th>
                        <th class="tg-0lax text-center" rowspan="2">Jenis Pemeriksaan</th>
                        <th class="tg-0lax text-center" colspan="2">Waktu</th>
                        <th class="tg-0lax text-center" rowspan="2">Nama & Paraf Penerima Hasil</th>
                        <th class="tg-0lax text-center" rowspan="2">Keterangan</th>
                      </tr>
                      <tr>
                        <th class="tg-0lax">Pengiriman/Pemeriksaan</th>
                        <th class="tg-0lax">Penerimaan Hasil</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-if="dataPemeriksaan.length == 0">
                        <td colspan="6">Belum ada data!</td>
                      </tr>
                      <tr v-for="(data, index) in dataPemeriksaan" :key="index" v-else>
                        <td width="2px" style="vertical-align: inherit;">
                          {{ data.tgl }}
                        </td>
                        <td width="2px">{{ data.namaproduk }} {{ data.keteranganlainnya ?? '' }}</td>
                        <td width="2px">
                          {{ data.tglorder }}
                        </td>
                        <td width="2px">
                          {{data.tgl_hasil ?? data.tanggalreport}}
                        </td>
                        <td width="2px">
                            {{data.petugas}}
                        </td>
                        <td width="2px">
                          {{data.keterangan}}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </Fieldset>
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
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-perdosi.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPasien: any = ref([])

const dataTTD: any = ref([])
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
const d_Registrasi: any = ref([])
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
const COLLECTION: any = ref("PemantauanHasilPemeriksaanPenunjang") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  detaiperdosi: [{ no: 1 }]
})
const dataPemeriksaan : any = ref([])
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  console.log(props)
  useApi().get(`/emr/pemantauan-penunjang?noregistrasi=${props.registrasi.noregistrasi}&norec_apd=${NOREC_APD}`).then(response => {
    console.log(response)
    dataPemeriksaan.value = response
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let tanggalRegistrasi = dataPasien.value.map((item: any) => item.tglregistrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object,
    'tanggal_registrasi': tanggalRegistrasi
  }
  console.log(object.detaiperdosi)
  if (!object.detaiperdosi[object.detaiperdosi.length - 1].namadokter2) {
    H.alert('warning', 'Dokter harus di isi')
    return;
  }
  // if (!object.detaiperdosi[object.detaiperdosi.length - 1].namaterapis2) {
  //   H.alert('warning', 'Terapis harus di isi')
  //   return;
  // }
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
    `emr/dropdown/pegawai_m?select=id,namalengkap,nosip&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
    console.log(d_Pegawai.value)
  })
}

const kembaliKeun = () => {
  window.history.back()
}

const setRegisPasien = async (e: any) => {
  dataPasien.value = []
  await useApi().get(`/emr/register-pasien-emr?nocmfk=${ID_PASIEN}`).then((response) => {
    dataPasien.value = response
    d_Registrasi.value = response
    // console.log(dataPasien.value)
  })
}

const setAutoFill = async () => {
  fetchJenisKelamin()
  input.value.tglPembuatan = new Date()
  input.value.namaPasien = props.pasien.namapasien
  input.value.detaiperdosi.forEach(data => {
    data.namapasien2 = props.pasien.namapasien
  })
  input.value.nocm = props.pasien.nocm
  input.value.tgllahir = props.pasien.tgllahir
  input.value.nohp = props.pasien.nohp
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.tglPembuatan = new Date()
  input.value.tanggalWaktuKejadian = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi
  input.value.dokter = props.registrasi.dokter
  input.value.umur = props.pasien.umur
  input.value.alamat = props.pasien.alamatlengkap
}
const setAutoFillDiagnosa = async () => {
  await useApi().get(
    `/emr/auto-fill-icd10/${props.registrasi.noregistrasi}`).then((response: any) => {
      let kdnamadiagnosa = []
      for (let i = 0; i < response.length; i++) {
        const element = response[i];
        kdnamadiagnosa.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa)
      }
      input.value.diagnosa = kdnamadiagnosa.join(',');
    })
}
// console.log(JSON.stringify(props.pasien));

setView()
// setAutoFill()
setRegisPasien()
loadRiwayat()
// setAutoFillDiagnosa()
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

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 120%;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
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
  vertical-align: middle
}
</style>

