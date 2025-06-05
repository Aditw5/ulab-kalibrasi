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
          <h1 style="font-weight: 600;">I. Diisi Oleh Pasien/Peserta
          </h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama Pasien: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama Pasien... " v-model="input.namaPasien" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> No.RM/Reg: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="NoCM... " v-model="input.nocm" />
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
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Tanggal Lahir... " v-model="input.tgllahir" />
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
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Alamat... " v-model="input.alamat" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Telp/HP: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Telp/HP... " v-model="input.nohp" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Hubungan dengan tertanggung : </span>
              </div>
              <div class="column is-3 mt-2" style="margin-left:-0.8rem">
                <VField>
                  <VControl raw subcontrol class="p-0">
                    <VCheckbox class="pt-0" v-model="input.hubunganDenganTertanggungSuamiIstri" label="Suami/Istri"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2 mt-2" style="margin-left:-15rem">
                <VField>
                  <VControl raw subcontrol class="p-0">
                    <VCheckbox class="pt-0" v-model="input.hubunganDenganTertanggungAnak" label="Anak" color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: 600;">II. Diisi oleh Dokter SpKFR
          </h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal Pelayanan </span>
              </div>
              <div class="column is-6">
                <VField>
                      <VControl class="prime-auto">
                        <Calendar v-model="input.tglPelayanan" selectionMode="single" :manualInput="true" class="w-100"
                          :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                      </VControl>
                    </VField>
                <!-- <VField class="mt-2">
                  <VDatePicker v-model="input.tglPelayanan" mode="dateTime" style="width: 100%" trim-weeks
                    :max-date="new Date()">
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl icon="feather:calendar" fullwidth>
                          <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </VField> -->
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Anamnesa </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Anamnesa... " v-model="input.auamnesa" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Pemeriksaan Fisik dan Uji Fungsi </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pemeriksaan Fisik dan Uji Fungsi... "
                      v-model="input.pemeriksaanFisikUjiFungsi" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Diagnosa Medis (ICD-10): </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VTextarea v-model="input.diagnosa" rows="3" placeholder="Diagnosa "></VTextarea>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Diagnosa Fungsi (ICD-10): </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VTextarea v-model="input.diagnosaFungsi" rows="3" placeholder="Diagnosa "></VTextarea>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Pemeriksaan Penunjang </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Pemeriksaan penunjang.. "
                      v-model="input.pemeriksaanPenunjang" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Tata Laksana KFR (ICD 9CM) </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Tata Laksana  KFR.. " v-model="input.tataLaksanaKFR" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Anjuran </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Anjuran.. " v-model="input.anjuran" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Evaluasi </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Evaluasi.. " v-model="input.evaluasi" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> * Suspek Penyakit Akibat Kerja </span>
              </div>
              <div class="column is-3 mt-2" style="margin-left:-0.8rem">
                <VField>
                  <VControl raw subcontrol class="p-0">
                    <VCheckbox class="pt-0" v-model="input.suspekPenyakitAkibatKerjaYa" label="Ya" color="primary" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-6" v-if="input.suspekPenyakitAkibatKerjaYa">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Keterarangan.. "
                      v-model="input.suspekPenyakitAkibatKerjaYaKeterangan" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0" style="margin-left:4rem">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
              </div>
              <div class="column is-2 mt-2" style="margin-left:-0.8rem">
                <VField>
                  <VControl raw subcontrol class="p-0">
                    <VCheckbox class="pt-0" v-model="input.suspekPenyakitAkibatKerjaTidak" label="Tidak"
                      color="primary" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;"> Tanda Tangan Pasien</h1>
            <div class="mt-6">
              <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="mt-5">
              <VField>
                <VControl class="prime-auto">
                  <VInput type="text" class="input" placeholder="Nama Pasien... " v-model="input.namaPasien" />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold;">Tanda Tangan Dokter </h1>
            <VField>
              <VDatePicker v-model="input.tglTTDDOkter" mode="dateTime" style="width: 100%" trim-weeks
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
            <div>
              <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
              <img v-if="input.dokterttd"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterttd ? input.dokterttd.label : '-')">
            </div>
            <div class="mt-4">
              <VField>
                <VControl class="prime-auto">
                  <VControl class="prime-auto">
                      <AutoComplete v-model="input.dokterttd" :suggestions="d_Dokter" @complete="fetchDokter($event)"
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
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-layanan.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import Calendar from 'primevue/calendar';
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
const COLLECTION: any = ref("LayananKedokteranFisikdanRehabilitasi") //table mongodb
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
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
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
  object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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
  input.value.tglPelayanan = new Date()
  input.value.namaPasien = props.pasien.namapasien
  input.value.nocm = props.pasien.nocm
  input.value.tgllahir = props.pasien.tgllahir
  input.value.nohp = props.pasien.nohp
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.tglPembuatan = new Date()
  input.value.tanggalWaktuKejadian = new Date()
  input.value.tglTTDDOkter = new Date()
  input.value.tglDirawat = props.registrasi.tglregistrasi
  input.value.dokter = props.registrasi.dokter
  input.value.umur = props.pasien.umur
  input.value.alamat = props.pasien.alamatlengkap

  await useApi().get(
    `/emr/auto-fill-icd10/${props.registrasi.noregistrasi}`).then((response: any) => {
      let kdnamadiagnosa = []
      let kdnamadiagnosaUatama = []
      // console.log(response)
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
      input.value.diagnosaFungsi = kdnamadiagnosaUatama.join(',');

    })
    useApi().get(
    `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=CatatanPerkembanganPasienTerintegrasi&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length > 0) {
        const CPPT = response[0].details[0]
        input.value.pemeriksaanFisikUjiFungsi = CPPT.O
        input.value.tataLaksanaKFR = CPPT.P
        input.value.auamnesa = CPPT.S
      }
    })
    if(useUserSession().getUser().kelompokUser.kelompokUser == 'dokter'){
      input.value.dokterttd = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    }else{
      input.value.dokterttd = {label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk}
    }
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
