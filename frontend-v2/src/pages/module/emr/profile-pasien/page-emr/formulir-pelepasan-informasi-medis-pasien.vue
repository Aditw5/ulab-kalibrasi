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
          <h1 style="font-weight: bold;" class="text-center mb-5">FORMULIR PELEPASAN INFORMASI MEDIS PASIEN</h1>
        </div>
        <div class="column is-12">
          <h1 style="font-weight: 600;">Yang bertanda tangan di bawah ini :
          </h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nama... " v-model="input.namaPenanggung" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Tanggal Lahir : </span>
              </div>
              <div class="column is-6">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglLahirPenanggung" mode="dateTime" style="width: 100%" trim-weeks
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
                <span> Alamat : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VTextarea rows="2" placeholder="Alamat..." v-model="input.alamat"></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <h1 style="font-weight: 600;">Dengan ini menyatakan dengan sesungguhnya terhadap</h1>
          <div class="column is-12 p-0">
            <div class="">
              <div class="column is-6">
                <VControl>
                  <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                    <div class="column is-12" v-if="d_Siapa.length == 0">
                      <VPlaceloadText :lines="1" />
                    </div>
                    <div class="column is-4 mt-2 p-0" v-for="items in d_Siapa" :key="items.id">
                      <VRadio v-model="input.keluargaSehubungan" :value="items.label" class="p-0 mb-3" :label="items.label"
                        square color="primary" />
                        <VField v-if="items.label == 'lainnya'">
                          <VControl>
                            <VInput type="text" class="input" placeholder="lainnya... " v-model="input.keluargaLainnya" />
                          </VControl>
                        </VField>
                    </div>
                  </div>
                </VControl>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama: </span>
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
                <span> Tanggal Lahir : </span>
              </div>
              <div class="column is-6">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglLahir" mode="dateTime" style="width: 100%" trim-weeks
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
                <span> Alamat : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VTextarea rows="2" placeholder="Alamat..." v-model="input.alamatPasien"></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nomor Rekam Medik: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Nomor Rekam Medik... " v-model="input.norekammedis" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span>Ruang Perawatan : </span>
              </div>
              <div class="column is-4">
                <VField>
                  <AutoComplete v-model="input.namaruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VField>
              </div>
            </div>
          </div>
          <h1 style="font-weight: 600;">Mengijinkan kepada</h1>
          <div class="column is-12 p-0">
            <div class="">
              <div class="column is-6">
                <VControl>
                  <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                    <div class="column is-12" v-if="d_Keluarga.length == 0">
                      <VPlaceloadText :lines="1" />
                    </div>
                    <div class="column is-4 mt-2 p-0" v-for="items in d_Keluarga" :key="items.id">
                      <VRadio v-model="input.keluargaSehubungan" :value="items.label" class="p-0 mb-3" :label="items.label"
                        square color="primary" />
                        <VField v-if="items.label == 'lainnya'">
                          <VControl>
                            <VInput type="text" class="input" placeholder="lainnya... " v-model="input.keluargaLainnya" />
                          </VControl>
                        </VField>
                    </div>
                  </div>
                </VControl>
              </div>
            </div>
          </div>
          <h1 style="font-weight: 600;">di bawah ini untuk mendapatkan informasi dan data Kesehatan saya selama mendapatkan pelayanan di RSD Gunung Jati :</h1>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VTextarea rows="2" placeholder="pelayanan..." v-model="input.detailPelayanan"></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-1" style="margin-top:0.5rem">
                <span> Cirebon </span>
              </div>
              <div class="column is-4">
                <VField class="mt-2">
                  <VDatePicker v-model="input.tglLahirPenanggung" mode="dateTime" style="width: 100%" trim-weeks
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
            <div class="column is-12">
              <div class="columns is-multiline">
                  <div class="column is-6">
                      <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                      <!-- <h1 style="font-weight: bold;"> Cirebon </h1> -->
                      <img v-if="input.petugasPerawat"
                          :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat ? input.petugasPerawat.label : '-')">
                  </div>
                  <div class="column is-6 ">
                      <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                  <div class="column is-4">
                      <h1 class="p-0" style="font-weight: bold;">Petugas Kesehatan </h1>
                      <VField>
                          <VControl class="prime-auto">
                              <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Pegawai"
                                  @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                  :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                  :field="'label'" placeholder="Petugas Kesehatan..." class="mt-2"
                                  @item-select="setTandaTangan($event)" />
                          </VControl>
                      </VField>
                  </div>
                  <div class="column is-4 is-offset-2">
                      <h1 class="p-0" style="font-weight: bold;">Yang Membuat Pernyataan,</h1>
                      <VField>
                          <VControl>
                              <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                                  v-model="input.pasienPenanggungjawab" />
                          </VControl>
                      </VField>
                  </div>
              </div>
          </div>
          </div>
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
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_RuanganRI: any = ref([])
const d_Kamar: any = ref([])
const d_Siapa: any = ref([])
const d_Keluarga: any = ref([])
const d_Kelas: any = ref([])
const d_KamarDokter: any = ref([])
const d_KelasDokter: any = ref([])
const isLoadingTT = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
console.log('collection', COLLECTION);

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
        if (response[0].tandaTanganDokterRSD) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokterRSD)
        }
        if (response[0].tandaTanganPetugasTpp) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPetugasTpp)
        }
        // if (response[0].tandaTanganDokterRSD2) {
        //   H.tandaTangan().set("signature_3", response[0].tandaTanganDokterRSD2)
        // }
        // if (response[0].tandaTanganPetugasTpp2) {
        //   H.tandaTangan().set("signature_4", response[0].tandaTanganPetugasTpp2)
        // }
        if (response[0].ruangan) {
          changeRuang(response[0].ruangan)
          input.value.kelas = response[0].kelas
        }
        if (response[0].kelas) {
          changeKelas(response[0].kelas, response[0].ruangan)
          input.value.kamar = response[0].kamar
        }
        if (response[0].dokterRuangan) {
          changeRuangDokter(response[0].dokterRuangan)
          input.value.dokterKelas = response[0].dokterKelas
        }
        if (response[0].dokterKelas) {
          changeKelasDokter(response[0].dokterKelas, response[0].dokterRuangan)
          input.value.dokterKamar = response[0].dokterKamar
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
  // object.tandaTanganDokterRSD = H.tandaTangan().get("signature_1")
  object.tandaTanganPetugasTpp = H.tandaTangan().get("signature_2")
  // object.tandaTanganDokterRSD2 = H.tandaTangan().get("signature_3")
  // object.tandaTanganPetugasTpp2 = H.tandaTangan().get("signature_4")
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

// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10&settingdatafix=objectdepartemenfk,kdDepartemenRanapFix`)
  d_Ruangan.value = response
}
const fetchRuanganRI = async () => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&settingdatafix=objectdepartemenfk,kdDepartemenRanapFix`)
  d_RuanganRI.value = response
}

const listSiapa = [
  {value: 1, label: "diri sendiri"},
  {value: 2, label: "istri"},
  {value: 3, label: "suami"},
  {value: 4, label: "anak"},
  {value: 5, label: "ayah"},
  {value: 6, label: "ibu"},
  {value: 7, label: "saudara"},
  {value: 8, label: "saudari"},
  {value: 9, label: "lainnya"},
]
d_Siapa.value = listSiapa

const listKeluarga = [
  {value: 1, label: "keluarga"},
  {value: 2, label: "wali"},
  {value: 3, label: "atau yang tersebut Namanya"},
]
d_Keluarga.value = listKeluarga

const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    }
  })
}
const changeKelas = async (e: any, ruangan: any) => {
  d_Kamar.value = []
  input.value.kamar=null;
  isLoadingTT.value = true
  await useApi().get(
    `/registrasi/kamar-by-kelas?id=${e}&idRuangan=${ruangan}&isRG=false`)
    .then((response: any) => {
      isLoadingTT.value = false
      d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const changeKelasDokter = async (e: any, ruangan: any) => {
  d_KamarDokter.value = []
  input.value.dokterKamar =null;
  isLoadingTT.value = true
  await useApi().get(
    `/registrasi/kamar-by-kelas?id=${e}&idRuangan=${ruangan}&isRG=false`)
    .then((response: any) => {
      isLoadingTT.value = false
      d_KamarDokter.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const changeRuang = (e: any) => {
    input.value.kelas =null;
    input.value.kamar =null;
    setKelas(e)
}
const setKelas = (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/registrasi/kelas-by-ruangan?id=${e}`)
    .then((response: any) => {
      if (response) {
        d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
      }
      isLoadingTT.value = false
    })
    .catch((error: any) => { isLoadingTT.value = false })
}

const changeRuangDokter = (e: any) => {
  if (e) {
    setKelasDokter(e)
  }
}
const setKelasDokter = (e: any) => {
  isLoadingTT.value = true
  input.value.dokterKamar =null;
  input.value.dokterKelas =null;
  d_KelasDokter.value = []
  useApi().get(
    `/registrasi/kelas-by-ruangan?id=${e}`)
    .then((response: any) => {
      isLoadingTT.value = false
      d_KelasDokter.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  // fetchJenisKelamin()
  await fetchRuanganRI()
  input.value.tglPembuatan = new Date()
  input.value.namaPasien = props.pasien.namapasien
  input.value.norekammedis = props.pasien.nocm
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.tglPembuatan = new Date()
  input.value.tanggalWaktuKejadian = new Date()
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.tglDirawat = props.registrasi.tglregistrasi
  input.value.petugasRanap = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.petugasRanap2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }

}
console.log(JSON.stringify(props.pasien));

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
