<template>
  <div>
      <div class="form-layout is-stacked-2">
          <div class="form-outer" style="margin-top:15px">
              <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                  <div class="form-header-inner">
                      <div class="left">
                          <h3> Down Score / Evaluasi Gawat Nifas </h3>
                      </div>
                      <div class="right">
                          <div class="buttons">
                              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                  Kembali
                              </VButton>
                              <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                  :loading="isLoading || isLoadingPasien" @click="simpan()"> Simpan
                              </VButton>
                          </div>
                      </div>
                  </div>
              </div>
              <div class="form-body p-2">
                  <div class="business-dashboard hr-dashboard">
                      <div class="columns is-multiline">
                          <div class="column is-12" v-if="isLoadingPasien">
                              <PlaceloadHeader class="m-3" />
                          </div>
                          <div class="column is-12" v-if="!isLoadingPasien">
                              <VCard class="border-card pink">
                                  <div class="columns is-multiline p-3">
                                      <div class="column is-12">
                                        <Fieldset class="p-fieldsets" legend="Down Score / Evaluasi Gawat Nafas" :toggleable="true">
                                          <h1 class="emr"></h1>
                                          <div class="column is-12">
                                              <div class="columns is-multiline" style="border-bottom: 1px solid;">
                                                <div class="column is-2">
                                                      <h1 class="emr">Kriteria</h1>
                                                  </div>
                                                  <div class="column is-6">
                                                      <h1 class="emr"></h1>
                                                  </div>
                                                  <div class="column">
                                                      <h1 class="emr">Nilai</h1>
                                                  </div>
                                                  <!-- <div class="column is-2">Skor</div> -->
                                              </div>
                                              <div class="columns is-multiline"
                                                  v-for="(data) in resikoJatuh.pertama">
                                                  <div class="column is-2">
                                                      <h1 class="emr">{{ data.parameter }}</h1>
                                                  </div>
                                                  <div class="column is-6 pt-0">
                                                      <div class="column is-12 pb-0" v-for="(value) in data.kriteria">
                                                          <VField class="">
                                                              <VControl raw subcontrol>
                                                                  <VCheckbox v-model="input[value.model]" class="p-0"
                                                                      :true-value="value.value" :label="value.title"
                                                                      color="primary" circle />
                                                              </VControl>
                                                              <small>{{ value.keterangan }}</small>
                                                          </VField>
                                                      </div>
                                                  </div>
                                                  <div class="column pt-0">
                                                      <div class="column pb-0" v-for="(nilai) in data.kriteria">
                                                          <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin
                                                          }}
                                                          </h1>
                                                      </div>
                                                  </div>
                                              </div>
                                              <hr style="margin-top: 10px;">
                                              <div class="columns is-multiline"
                                                  v-for="(data) in resikoJatuh.kedua">
                                                  <div class="column is-2">
                                                      <h1 class="emr">{{ data.parameter }}</h1>
                                                  </div>
                                                  <div class="column is-6 pt-0">
                                                      <div class="column is-12 pb-0" v-for="(value) in data.kriteria">
                                                          <VField class="">
                                                              <VControl raw subcontrol>
                                                                  <VCheckbox v-model="input[value.model]" class="p-0"
                                                                      :true-value="value.value" :label="value.title"
                                                                      color="primary" circle />
                                                              </VControl>
                                                              <small>{{ value.keterangan }}</small>
                                                          </VField>
                                                      </div>
                                                  </div>
                                                  <div class="column pt-0">
                                                      <div class="column pb-0" v-for="(nilai) in data.kriteria">
                                                          <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin
                                                          }}
                                                          </h1>
                                                      </div>
                                                  </div>
                                              </div>
                                              <hr style="margin-top: 10px;">
                                              <div class="columns is-multiline"
                                                  v-for="(data) in resikoJatuh.ketiga">
                                                  <div class="column is-2">
                                                      <h1 class="emr">{{ data.parameter }}</h1>
                                                  </div>
                                                  <div class="column is-6 pt-0">
                                                      <div class="column is-12 pb-0" v-for="(value) in data.kriteria">
                                                          <VField class="">
                                                              <VControl raw subcontrol>
                                                                  <VCheckbox v-model="input[value.model]" class="p-0"
                                                                      :true-value="value.value" :label="value.title"
                                                                      color="primary" circle />
                                                              </VControl>
                                                              <small>{{ value.keterangan }}</small>
                                                          </VField>
                                                      </div>
                                                  </div>
                                                  <div class="column pt-0">
                                                      <div class="column pb-0" v-for="(nilai) in data.kriteria">
                                                          <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin
                                                          }}
                                                          </h1>
                                                      </div>
                                                  </div>
                                              </div>
                                              <hr style="margin-top: 10px;">
                                              <div class="columns is-multiline"
                                                  v-for="(data) in resikoJatuh.keempat">
                                                  <div class="column is-2">
                                                      <h1 class="emr">{{ data.parameter }}</h1>
                                                  </div>
                                                  <div class="column is-6 pt-0">
                                                      <div class="column is-12 pb-0" v-for="(value) in data.kriteria">
                                                          <VField class="">
                                                              <VControl raw subcontrol>
                                                                  <VCheckbox v-model="input[value.model]" class="p-0"
                                                                      :true-value="value.value" :label="value.title"
                                                                      color="primary" circle />
                                                              </VControl>
                                                              <small>{{ value.keterangan }}</small>
                                                          </VField>
                                                      </div>
                                                  </div>
                                                  <div class="column pt-0">
                                                      <div class="column pb-0" v-for="(nilai) in data.kriteria">
                                                          <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin
                                                          }}
                                                          </h1>
                                                      </div>
                                                  </div>
                                              </div>
                                              <hr style="margin-top: 10px;">
                                              <div class="columns is-multiline"
                                                  v-for="(data) in resikoJatuh.kelima">
                                                  <div class="column is-2">
                                                      <h1 class="emr">{{ data.parameter }}</h1>
                                                  </div>
                                                  <div class="column is-6 pt-0">
                                                      <div class="column is-12 pb-0" v-for="(value) in data.kriteria">
                                                          <VField class="">
                                                              <VControl raw subcontrol>
                                                                  <VCheckbox v-model="input[value.model]" class="p-0"
                                                                      :true-value="value.value" :label="value.title"
                                                                      color="primary" circle />
                                                              </VControl>
                                                              <small>{{ value.keterangan }}</small>
                                                          </VField>
                                                      </div>
                                                  </div>
                                                  <div class="column pt-0">
                                                      <div class="column pb-0" v-for="(nilai) in data.kriteria">
                                                          <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin
                                                          }}
                                                          </h1>
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="column is-3" style="margin-left: auto;">
                                              <VField label="Total">
                                                  <VControl raw subcontrol>
                                                      <input v-model="input.totalDownScore" class="input v-else"
                                                          disabled />
                                                  </VControl>
                                              </VField>
                                            </div>
                                          </div>

                                          <div class="column is-12">
                                            <vcard>
                                              <div style="display: flex; justify-content: space-between;">
                                                <div class="column is-8">
                                                  <h1 style="font-weight: bold; margin-bottom: 1rem;">Keterangan</h1>
                                                  <div class="columns is-multiline">
                                                      <VField>
                                                          <VControl>
                                                              <VCheckbox v-model="input.keteranganHasl"
                                                                  true-value="0-4 : Distres Nafas Ringan (memerlukan oksigen nasal atau headbox)"
                                                                  label="0-4 : Distres Nafas Ringan (memerlukan oksigen nasal atau headbox)" color="primary" square />
                                                          </VControl>
                                                      </VField>
                                                      <VField>
                                                          <VControl>
                                                              <VCheckbox v-model="input.keteranganHasl"
                                                                  true-value="4-7 : Distres Nafas Sedang (membutuhkan CPAP)"
                                                                  label="4-7 : Distres Nafas Sedang (membutuhkan CPAP)" color="primary" square />
                                                          </VControl>
                                                      </VField>
                                                      <VField>
                                                          <VControl>
                                                              <VCheckbox v-model="input.keteranganHasl"
                                                                  true-value="> 7 : Distres Nafas Berat, ancaman gagal nafas (memerlukan intubasi)"
                                                                  label="> 7 : Distres Nafas Berat, ancaman gagal nafas (memerlukan intubasi)" color="primary" square />
                                                          </VControl>
                                                      </VField>
                                                  </div>

                                              </div>
                                              </div>
                                            </vcard>
                                          </div>
                                        </Fieldset>
                                    </div>
                                  </div>

                                  <!-- <div class="column is-12 p-0">
                                    <div class="column is-12">
                                    <div class="columns is-multiline">

                                      <div class="column is-4">
                                        <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                                      </div>

                                      <div class="column is-4">
                                        <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
                                      </div>
                                    </div>

                                    <div class="columns is-multiline">

                                      <div class="column is-4">
                                        <h1 class="p-0" style="font-weight: bold;">Kepala Ruangan</h1>
                                        <VField>
                                          <VControl class="prime-auto">
                                            <AutoComplete v-model="input.DokterAnestesi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                                              @item-select="setTandaTangan($event, 'signature_1')" />
                                          </VControl>
                                        </VField>
                                      </div>


                                      <div class="column is-4">
                                        <h1 class="p-0" style="font-weight: bold;">Perawat / Bidan yang melakukan pengkajian </h1>
                                        <VField>
                                          <VControl class="prime-auto">
                                            <AutoComplete v-model="input.PenataAnestesi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                                              @item-select="setTandaTangan($event, 'signature_2')" />
                                          </VControl>
                                        </VField>
                                      </div>
                                      </div>
                                    </div>
                                  </div> -->
                              </VCard>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
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
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';
import AutoComplete from 'primevue/autocomplete';
import * as EMR from '../page-emr-plugins/evaluasi-gawat-nafas'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

useHead({
  title: 'Pengkajian Keperawatan Pasien Rawat Jalan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let DaftarInformasi = ref(EMR.DaftarInformasi())
let DaftarInformasi1 = ref(EMR.DaftarInformasi1())
let DaftarInformasi2 = ref(EMR.DaftarInformasi2())
let caraBayar = ref(EMR.caraBayar())
let tindakLanjut = ref(EMR.tindakLanjut())
let Pilihan = ref(EMR.Pilihan())
let pegangKursisaatDuduk = ref(EMR.pegangKursisaatDuduk())
let hasilResikoJatuh = ref(EMR.hasilResikoJatuh())
let resikoJatuh = ref(EMR.resikoJatuh())
let ListStatus = ref(EMR.ListStatus())
let ekpresiMuka = ref(EMR.ekpresiMuka())
let kemampuanBicara = ref(EMR.kemampuanBicara())
let koping = ref(EMR.koping())
let agama = ref(EMR.agama())
let polaIbadah = ref(EMR.polaIbadah())
let Respon = ref(EMR.Respon())
let support = ref(EMR.support())
let kebutuhanBicara = ref(EMR.kebutuhanBicara())
let penerjemah = ref(EMR.penerjemah())
let pendidikan = ref(EMR.pendidikan())
let kebutuhanEdukasi = ref(EMR.kebutuhanEdukasi())
let bahasaIsyarat = ref(EMR.bahasaIsyarat())
let planning = ref(EMR.planning())
let resikoNutrisional: any = ref(EMR.resikoNutrisional())
let fungsionalPertama: any = ref(EMR.fungsionalPertama())
let listRangeNilaiPoin: any = ref(EMR.nilaiPoin())
let listDESCNilai: any = ref(EMR.descNilai())
let listMasalah = ref(EMR.listMasalah())
let listMasalah2 = ref(EMR.listMasalah2())
let listMasalah3 = ref(EMR.listMasalah3())
let listMasalah4 = ref(EMR.listMasalah4())
let listMasalah5 = ref(EMR.listMasalah5())
let listMasalah6 = ref(EMR.listMasalah6())
let listMasalah7 = ref(EMR.listMasalah7())
let listMasalah8 = ref(EMR.listMasalah8())
let listImplementasi = ref(EMR.listImplementasi())
let listPemberian = ref(EMR.listPemberian())
let listImplementasi4 = ref(EMR.listImplementasi4())
let listImplementasi2 = ref(EMR.listImplementasi2())
let listImplementasi3 = ref(EMR.listImplementasi3())
let listImplementasi5 = ref(EMR.listImplementasi5())
let listImplementasi6 = ref(EMR.listImplementasi6())
let listImplementasi7 = ref(EMR.listImplementasi7())



const props = withDefaults(
  defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
  }>(),
  {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
  }
)
// console.log(props.registrasi)
const pasien: any = ref({})
const isLoadingPasien: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: props.registrasi.norec_apd,
  RUANGAN_LAST: props.registrasi.objectruanganlastfk,
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  date: {
      tanggal: new Date,
      jam: new Date
  },
  airway: [],
  disability: []

})

const COLLECTION: any = ref('EvaluasiGawatNafas') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
// waktuTataLaksana: new Date(),
waktuKontrol: new Date(),
waktuSimpan : new Date(),
jamPasienDatang : new Date(),
jamPengkajian : new Date(),
})
const d_pegawai: any = ref([])
const d_Dokter: any = ref([])
const isAktive = ref()
const router = useRouter()
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

let listImageNyeri = ref(
  {
      "nama": "Hurts", "detail": [
          {
              "nama": "No Hurt", "descNilai": 0,
              "img": "/images/skalanyeri/1.png"
          },
          {
              "nama": "Hurts Little Bit", "descNilai": 2,
              "img": "/images/skalanyeri/2.png",
          },
          {
              "nama": "Hurts Little More", "descNilai": 4,
              "img": "/images/skalanyeri/3.png",
          },
          {
              "nama": "Hurts Even More", "descNilai": 6,
              "img": "/images/skalanyeri/4.png",
          },
          {
              "nama": "Hurts Whole Lot", "descNilai": 8,
              "img": "/images/skalanyeri/5.png",
          },
          {
              "nama": "Hurts whorts", "descNilai": 10,
              "img": "/images/skalanyeri/6.png",
          }]
  }
)
let listSkoringNyeri: any = ref(
  {
      "nama": "Score ", "detail": [
          { "nama": "0 - 1 = Tidak Ada Nyeri", "descNilai": 0 },
          { "nama": "2 - 3 = Sedikit Nyeri", "descNilai": 2 },
          { "nama": "4 - 5 = Cukup Nyeri", "descNilai": 4 },
          { "nama": "6 - 7 = Lumayan Nyeri", "descNilai": 6 },
          { "nama": "8 - 9 = Sangat Nyeri", "descNilai": 8 },
          { "nama": "10 = Amat Sangat Nyeri", "descNilai": 10 },
      ]
  }
)
const isLoading = ref(false)
const listRiwayat = ref([])

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
                if (response[0].tandaTanganDokterRSD2) {
                  H.tandaTangan().set("signature_3", response[0].tandaTanganDokterRSD2)
                }
                if (response[0].tandaTanganPetugasTpp2) {
                  H.tandaTangan().set("signature_4", response[0].tandaTanganPetugasTpp2)
                }

                chartHigh(response[0])
                chartHigh2(response[0])
            }
          console.log(NOREC_EMRPASIEN.value)
        })
}

const simpan = () => {
  let object: any = {}
  let ID = input.value.id ? input.value.id : ''

  object = input.value //set inputan
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.tandaTanganDokterRSD = H.tandaTangan().get("signature_1")
  object.tandaTanganPetugasTpp = H.tandaTangan().get("signature_2")
  object.tandaTanganDokterRSD2 = H.tandaTangan().get("signature_3")
  object.tandaTanganPetugasTpp2 = H.tandaTangan().get("signature_4")
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

      }).catch((e: any) => {
          isLoading.value = false
      })
}

const kembaliKeun = () => {
  window.history.back()
}
const fetchPasien = () => {
  pasien.value = props.pasien
  pasien.value.registrasi = props.registrasi
  NOREC_EMRPASIEN.value = norec_emr ? norec_emr : ''
}

const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    }
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const test = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail
  let listImage = listImageNyeri.value.detail


  listSkor.forEach((element: any) => {
      if (element.descNilai == e.descNilai) {
          input.value.skor = e.descNilai
      }
  });
  isAktive.value = i

}

const setAutoFill = async () => {
input.value.tglPembuatan = new Date()
input.value.namaPasien = props.pasien.namapasien
input.value.norekammedis = props.pasien.nocm
input.value.jeniskelamin = props.pasien.jeniskelamin
input.value.tglPembuatan = new Date()
input.value.tanggalWaktuKejadian = new Date()
input.value.namaruangan = props.registrasi.namaruangan
input.value.tglDirawat = props.registrasi.tglregistrasi
input.value.dokterRSD = props.registrasi.dokter

}

const getDataExist = async () => {
  await useApi().get(
      `emr/get-data-exist?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
  ).then((response) => {

      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
      input.value.IMT = response.IMT
      input.value.lingkarPerut = response.lingkarPerut
      input.value.tekananDarah = response.tekananDarah
      input.value.pernapasan = response.pernapasan
      input.value.suhu = response.suhu
      input.value.nadi = response.nadi
  })
}

// const getPanggilSuster = async () => {
//     await useApi().get(
//       `emr/  ?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`
//     ).then((response) => {
//       input.value.tgldipanggilsuster = response.tgldipanggilsuster
//     })

// }

watch(() => [
  input.value.frekuensiNafas,
  input.value.retraksi,
  input.value.sianosis,
  input.value.airEntry,
  input.value.merintih,

], () => {

  let poin1 = input.value.frekuensiNafas ? parseInt(input.value.frekuensiNafas.poin) : 0
  let poin2 = input.value.retraksi ? parseInt(input.value.retraksi.poin) : 0
  let poin3 = input.value.sianosis ? parseInt(input.value.sianosis.poin) : 0
  let poin4 = input.value.airEntry ? parseInt(input.value.airEntry.poin) : 0
  let poin5 = input.value.merintih ? parseInt(input.value.merintih.poin) : 0


  const total = poin1 + poin2 + poin3 + poin4 + poin5
  input.value.totalDownScore = total

})
getDataExist()
// getPanggilSuster()
setAutoFill()
fetchPasien()
loadRiwayat()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

.p-fieldsets .p-fieldset-content {
  background: #ffffff;
}

.v-avatar.is-medium.active {
  padding: 3px;
  background: var(--success);
  display: inline-table !important;
}

table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
.triase th,
.triase td {
  border: 0.5px solid grey;
}


.triase th,
.triase td {
  padding: 8px;
  vertical-align: middle !important;
}
</style>
