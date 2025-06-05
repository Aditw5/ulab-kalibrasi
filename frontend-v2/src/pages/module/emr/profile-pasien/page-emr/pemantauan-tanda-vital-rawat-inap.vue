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
          <VSnack title="Pemantauan Tanda Vital" white color="warning" icon="fas fa-stethoscope"></VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg" v-for="(data, index) in input.pemantauantandavital" :key="index">
                  <tbody>
                    <tr>
                      <td width="3px" class="td-fkprj" style="vertical-align: inherit;" rowspan="43">
                        <div class="column">
                          <VButton color="primary" icon="feather:plus" elevated @click="addNewPTV()">
                            Tambah Baris
                          </VButton>
                          <VButton v-if="index > 0" color="danger" class="mt-2" icon="feather:trash" elevated
                            @click="removePTV(index)">
                            Hapus Baris
                          </VButton>
                        </div>
                      </td>
                      <td colspan="4">
                        Tanggal :
                      </td>
                      <td colspan="3" v-for="(datatgl, index) in data.tglpemantauan">
                        <VField class="mt-2">
                          <VDatePicker v-model="datatgl.tgl" mode="dateTime" style="width: 100%" trim-weeks
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
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">
                        Ruang Rawat:
                      </td>
                      <td colspan="15">
                        <VField>
                          <VControl>
                            <VInput v-model="input.ruangrawat" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Paraf Petugas: </td>

                      <template v-for="i in 5">
                        <td>P</td>
                        <td>S</td>
                        <td>M</td>
                      </template>
                    </tr>
                    <tr>
                      <td>FP</td>
                      <td>FN</td>
                      <td>S</td>
                      <td>TD</td>

                      <template v-for="i in 15">
                        <td></td>
                      </template>
                    </tr>
                    <tr v-for="(item, index) in data.ptd" :key="index">
                      <td>{{ item.FP }}</td>
                      <td>{{ item.FN }}</td>
                      <td>{{ item.S }}</td>
                      <td>{{ item.TD }}</td>
                      <td v-for="(itemPtv, index) in item.ptv" :key="index">
                        <VField>
                          <VControl>
                            <VInput v-model="itemPtv.ptv" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Skor Nyeri (VAS)</td>
                      <td v-for="(itemNyeri, index) in data.skorNyeri">
                        <VField>
                          <VControl>
                            <VInput v-model="itemNyeri.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Skor EWS</td>
                      <td v-for="(itemEws, index) in data.skorEws">
                        <VField>
                          <VControl>
                            <VInput v-model="itemEws.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Pupil</td>
                      <td v-for="(itemPupil, index) in data.pupil" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemPupil.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Refleks</td>
                      <td v-for="(itemRefleks, index) in data.refleks" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemRefleks.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">BB/TB/LK</td>
                      <td v-for="(itemBBTBLK, index) in data.bbtblk" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemBBTBLK.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Kesadaran/GCS</td>
                      <td v-for="(itemGCS, index) in data.gcs" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemGCS.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Skor Risiko Jatuh</td>
                      <td v-for="(itemRisikoJatuh, index) in data.risikoJatuh" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemRisikoJatuh.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td rowspan="4"
                        style="text-orientation: mixed; writing-mode: vertical-lr;transform: rotate(180deg); text-align: center; font-size: 12pt;">
                        Masuk</td>
                      <td colspan="3">Per Oral/ NGT</td>
                      <td v-for="(itemOral, index) in data.oral" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemOral.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Parental</td>
                      <td v-for="(itemParental, index) in data.parental" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemParental.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Transfusi</td>
                      <td v-for="(itemTransfusi, index) in data.transfusi" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemTransfusi.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Jumlah</td>
                      <td v-for="(itemJumlahMasuk, index) in data.jumlahMasuk" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemJumlahMasuk.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td rowspan="5"
                        style="text-orientation: mixed; writing-mode: vertical-lr;transform: rotate(180deg); text-align: center; font-size: 12pt;">
                        Keluar</td>
                      <td colspan="3">BAB</td>
                      <td v-for="(itemBAB, index) in data.bab" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemBAB.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Urine</td>
                      <td v-for="(itemUrine, index) in data.urine" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemUrine.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Muntah/NGT</td>
                      <td v-for="(itemMuntah, index) in data.muntah" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemMuntah.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">IWL</td>
                      <td v-for="(itemIWL, index) in data.iwl" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemIWL.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="3">Jumlah</td>
                      <td v-for="(itemJumlahKeluar, index) in data.jumlahKeluar" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemJumlahKeluar.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="4">Balans Cairan :</td>
                      <td v-for="(itemBalansCairan, index) in data.balansCairan" colspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="itemBalansCairan.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td colspan="19">
                        Kode gambar FP Frekuensi Pernafasan (+) FN: Frekuensi Nadi (): S: Suhu (X) Sistolik (v)
                        Diastolik: (^) BB: Berat
                        <br>Badan: TB Tinggi Badan, LK: Lingkar Kepala
                        <br>Kegunaan Tinta Wama: Pernapasan. Hijau, Nadi : Merah, Suhu Biru, Tekanan Darah Hitam, buat
                        garis lurus ke bawah
                      </td>
                    </tr>
                  </tbody>
                </table>
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
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-perdosi.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import * as EMR from '../page-emr-plugins/pemantauan-tanda-vital'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
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
let ptdEMR = ref(EMR.pemantauanTandaVital())
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
const COLLECTION: any = ref("PemantauanTandaVitalRawatInap") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const lengthTgl: any = ref(5)
const arrayTanggal = []
for (let i = 1; i <= lengthTgl.value; i++) {
  arrayTanggal.push({ no: i });
}

const input: any = ref({
  pemantauantandavital: [{
    no: 1,
    tglpemantauan: arrayTanggal,
    ptd: ptdEMR.value,
    skorNyeri: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    skorEws: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    pupil: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    refleks: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    bbtblk: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    gcs: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    risikoJatuh: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    oral: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    parental: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    transfusi: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    jumlahMasuk: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    bab: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    urine: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    muntah: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    iwl: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    jumlahKeluar: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    balansCairan: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
  }],
})
const d_Jenis = [
  { id: 1, label: 'DPJP Utama' },
  { id: 2, label: 'DPJP Tambahan' },
]
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
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

const deepCopyArray = (arr) => {
  return arr.map(obj => ({ ...obj }));
}

const addNewPTV = () => {
  input.value.pemantauantandavital.push({
    no: input.value.pemantauantandavital[input.value.pemantauantandavital.length - 1].no + 1,
    tglpemantauan: deepCopyArray(arrayTanggal),
    ptd: deepCopyArray(ptdEMR.value),
    skorNyeri: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    skorEws: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    pupil: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    refleks: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    bbtblk: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    gcs: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    risikoJatuh: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    oral: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    parental: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    transfusi: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    jumlahMasuk: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    bab: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    urine: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    muntah: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    iwl: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    jumlahKeluar: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
    balansCairan: Array.from({ length: 5 }, (_, j) => ({ no: j + 1 })),
  });
  setAutoFill()
}
const removePTV = (index: any) => {
  input.value.pemantauantandavital.splice(index, 1)
}

const addNewPerubahanDPJP = () => {
  input.value.perubahandpjp.push({
    no: input.value.perubahandpjp[input.value.perubahandpjp.length - 1].no + 1,
  });
  setAutoFill()
}
const removePerubahanDPJP = (index: any) => {
  input.value.perubahandpjp.splice(index, 1)
}
const addNewPerubahanDPJPTambahan = () => {
  input.value.perubahandpjptambahan.push({
    no: input.value.perubahandpjptambahan[input.value.perubahandpjptambahan.length - 1].no + 1,
  });
  setAutoFill()
}
const removePerubahanDPJPTambahan = (index: any) => {
  input.value.perubahandpjptambahan.splice(index, 1)
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap,nosip&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}
const fetchPegawai = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
    console.log(d_Pegawai.value)
  })
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

const setRegisPasien = async (e: any) => {
  dataPasien.value = []
  await useApi().get(`/emr/register-pasien-emr?nocmfk=${ID_PASIEN}`).then((response) => {
    dataPasien.value = response
    d_Registrasi.value = response
    // console.log(dataPasien.value)
  })
}

const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.ruangrawat = props.registrasi.namaruangan
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
setAutoFill()
setRegisPasien()
loadRiwayat()
setAutoFillDiagnosa()
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
  width: 100%;
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
