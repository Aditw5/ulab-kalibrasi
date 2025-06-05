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
        <Fieldset class="p-fieldsets" legend="SKRINING FAKTOR RESIKO PASIEN PULANG" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> RENCANA TANGGAL PULANG PASIEN : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>Apakah Pasien/Keluarga Tahu Rencana Pulangnya ?</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in pasienKeluargaTahuRencanaPulang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.pasienKeluargaTahuRencanaPulang" :true-value="data.value"
                          :label="data.label" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> FAKTOR RISIKO PASIEN PULANG : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>1. Apakah Pasien Tinggal Sendiri </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in apakahPasienTinggalSendiri">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.apakahPasienTinggalSendiri" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketApakahPasienTinggalSendiri">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>2. Apakah Mereka Kawatir Ketika Kembali Kerumah </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in kawatirKetikaKembaliKerumah">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kawatirKetikaKembaliKerumah" :true-value="data.value"
                          :label="data.label" class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketKawatirKetikaKembaliKerumah">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>3. Apakah Pasien Dirumah Ada Yang Merawat </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in apakhDirumahAdaYangMerawat">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.apakhDirumahAdaYangMerawat" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketApakhDirumahAdaYangMerawat">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>4. Bagaimana Jenis Tempat Tinggal Pasien </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in jenisTempatTinggalPasien">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.jenisTempatTinggalPasien" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline mt-6">
                  <div class="column is-4">
                    <span>5. Apakah Tempat Tinggal Ada Tangga </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in tempatTinggalAdaTangga">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tempatTinggalAdaTangga" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketTempatTinggalAdaTangga">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>6. Apakah Pasien Memiliki Tanggung Jawab Memelihara Anak/Keluarga Atau Peliharaan </span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in tanggungJawabMemelihara">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tanggungJawabMemelihara" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketTanggungJawabMemelihara">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>7. Apakah Ketika Pulang Masih Ada Perawatan Lanjutan Yang HArus Dilakukan Dirumah (Rawat Luka
                      Dll)</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in perawatanLajutanDiruamah">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.perawatanLajutanDiruamah" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketPerawatanLajutanDiruamah">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>8. Apakah Pasien Pulang Dengan Jumlah Obat Lebih dari 6 Jenis/Macam Obat</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in enamJenisObatSaatPulang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.enamJenisObatSaatPulang" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketEnamJenisObatSaatPulang">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>9. Apakah PAsien Mengajukan Permohonan Untuk Pendampingan Dari Rumah Sakit</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in permohonanPendampingan">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.permohonanPendampingan" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketPermohonanPendampingan">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
                <div class="columns is-multiline">
                  <div class="column is-4">
                    <span>10. Bagaimana Transportasi Pasien Saat Pulang</span>
                  </div>
                  <div class="column is-2" v-for="(data, i) in transportasiSaatPulang">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.transportasiSaatPulang" :true-value="data.value" :label="data.label"
                          class="p-0" color="primary" square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField>
                      <VTextarea rows="2" placeholder="Keterangan....." v-model="input.ketTransportasiSaatPulang">
                      </VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="RENCANA PULANG" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12" style="margin-top:0.5rem">
                <span class="bold-text"> RENCANA LAMA RAWAT : </span>
              </div>
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-2">
                    <span>Rencana Waktu Pulang :</span>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl>
                        <VInput type="" class="input" placeholder="....." v-model="input.rencanaWaktuPulangKet" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-2">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.rencanaWaktuPulangBelumDiketahui" label="Belum Bisa Ditetapkan"
                          class="p-0" color="primary" square />
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
        <Fieldset class="p-fieldsets" legend="TERAPI PULANG" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-12">
                    <VField>
                      <VTextarea rows="2" placeholder="Terapi Pulang....." v-model="input.terapiPulang"></VTextarea>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="EDUKASI" :toggleable="true">
          <div class="columns is-multiline">
            <div class="column is-12 P-0">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-6">
                    <VField>
                      <VTextarea rows="2" placeholder="Edukasi....." v-model="input.edukasi"></VTextarea>
                    </VField>
                  </div>
                  <div class="column is-4">
                    <VField label="Tanggal & Jam">
                      <VDatePicker v-model="input.tanggalJamEdukasi" mode="dateTime" style="width: 100%" trim-weeks
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
            </div>
          </div>
        </Fieldset>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> Yang Melakukan Pengkajian, DPJP</h1>
            <div class="column is-12 " style="text-align: center">
              <img v-if="input.dokter"
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokter ? input.dokter : '-')">
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama dokter" class="mt-2"/>
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> Pasien/ Keluarga Pasien </h1>
            <div class="mt-6" style="text-align: center">
              <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="mt-2" style="text-align: center"> 
              <VField>
                <VControl>
                  <VInput type="text" class="input prime-auto" placeholder="Pasien/Keluarga" v-model="input.namapasien"
                    :suggestions="d_Pasien" @complete="fetchPasien($event)" />
                </VControl>
              </VField>
            </div>
          </VCard>
        </div>
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> TTD Petugas </h1>
            <div class="column is-12 " style="text-align: center">
              <img v-if="input.petugasPerawatTerima"
            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawatTerima ? input.petugasPerawatTerima.label : '-')">
            </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.petugasPerawatTerima" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama petugas" class="mt-2"/>
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
import * as EMR from '../page-emr-plugins/pengkajian-awal-pi'
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
let pasienKeluargaTahuRencanaPulang = ref(EMR.pasienKeluargaTahuRencanaPulang())
let apakahPasienTinggalSendiri = ref(EMR.apakahPasienTinggalSendiri())
let kawatirKetikaKembaliKerumah = ref(EMR.kawatirKetikaKembaliKerumah())
let apakhDirumahAdaYangMerawat = ref(EMR.apakhDirumahAdaYangMerawat())
let jenisTempatTinggalPasien = ref(EMR.jenisTempatTinggalPasien())
let tempatTinggalAdaTangga = ref(EMR.tempatTinggalAdaTangga())
let tanggungJawabMemelihara = ref(EMR.tanggungJawabMemelihara())
let perawatanLajutanDiruamah = ref(EMR.perawatanLajutanDiruamah())
let enamJenisObatSaatPulang = ref(EMR.enamJenisObatSaatPulang())
let permohonanPendampingan = ref(EMR.permohonanPendampingan())
let transportasiSaatPulang = ref(EMR.transportasiSaatPulang())


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
const d_Pasien: any = ref([])
const d_Ruangan: any = ref([])
const d_Agama: any = ref([])
const d_Suku: any = ref([])
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
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
        if (response[0].tandaTanganPasienKeluarga) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPasienKeluarga)
        }
        if (response[0].tandaTanganPetugas) {
          H.tandaTangan().set("signature_3", response[0].tandaTanganPetugas)
        }
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
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
    input.value.tandaTanganPerawat = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const fetchPasien = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pasien_m?select=id,namapasien&param_search=namapasien&query=${filter.query}&limit=10`)
  d_Pasien.value = response
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchAgama = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/agama_m?select=id,agama&param_search=agama&query=${filter.query}&limit=10`)
  d_Agama.value = response
}

const fetchSuku = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/suku_m?select=id,suku&param_search=suku&query=${filter.query}&limit=10`)
  d_Suku.value = response
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
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
  object.tandaTanganPasienKeluarga = H.tandaTangan().get("signature_2")
  object.tandaTanganPetugas = H.tandaTangan().get("signature_3")
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

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
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.dokter = props.registrasi.dokter
  input.value.namapasien = props.pasien.namapasien
  input.value.petugasPerawatTerima = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }

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

.bold-text {
  font-weight: bold;
}
</style>
