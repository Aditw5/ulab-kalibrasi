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
        <Fieldset :toggleable="true" legend="Monitoring & Evaluasi Terapi Gizi">
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center" colspan="2">Asupan Gizi</th>

                    </tr>
                    <tr>
                      <th class="tg-0lax">Target Kebutuan Energi dan Protein</th>
                      <th class="tg-0lax">Jenis dan Jalur Terapi Gizi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="170px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Target Kebutuan Energi dan Protein......"
                                v-model="input.targetKebutuhanEnergiProtein"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="columns is-multiline">
                          <div class="column is-6">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.jenisJalurTerapiGiziOral" class="pt-1 pb-1 "
                                  label="Oral (Padat/Lunak)" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.jenisJalurTerapiGiziEternalNutrition" class="pt-1 pb-1 "
                                  label="Eternal Nutrition" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.jenisJalurTerapiGiziOralPlusONS" class="pt-1 pb-1 "
                                  label="Oral (Padat/Lunak) + ONS*" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.jenisJalurTerapiGiziEternalNutritionPlusParentalNurition"
                                  class="pt-1 pb-1 " label="Eternal Nutrition + Parenteral Nutrition" color="primary"
                                  square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.jenisJalurTerapiGiziOralDietCair" class="pt-1 pb-1 "
                                  label="Oral (Diet Cair)" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-6">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input.jenisJalurTerapiGiziTotalParenteralNutrition" class="pt-1 pb-1 "
                                  label="Total Parenteral Nutrition" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax">#</th>
                      <th class="tg-0lax">Tanggal</th>
                      <th class="tg-0lax">Terapi Gizi</th>
                      <th class="tg-0lax">Monitoring</th>
                      <th class="tg-0lax">Evaluasi</th>
                      <th class="tg-0lax">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody v-for="(item, index) in input.diagnosisKerja" :key="index">
                    <tr>
                      <td width="50px" class="td-fkprj">{{ item.no }}
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                            v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeItem(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                      <td width="200px" class="td-fkprj">
                        <VField class="mt-2">
                          <VDatePicker v-model="item.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
                      <td class="td-fkprj">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Terapi Gizi......" v-model="item.terapiGizi"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Monitoring......" v-model="item.monitoring"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Evaluasi......" v-model="item.evaluasi"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-fkprj">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Keterangan......" v-model="item.keterangan"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center" colspan="4">Antropometri & Pemeriksaan Fisik</th>
                    </tr>
                    <tr>
                      <th class="tg-0lax">Kriteria</th>
                      <th class="tg-0lax">Monitoring</th>
                      <th class="tg-0lax">Evaluasi</th>
                      <th class="tg-0lax">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="170px">
                        Tinggi Badan
                        <div class="column is-12">
                          <VField></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Tinggi Badan" v-model="input.tinggiBadan"
                                :tabindex="1" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>Cm</VButton>
                            </VControl>
                          </VField>
                        </div>
                        Berat Badan
                        <div class="column is-12">
                          <VField></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="Berat Badan" v-model="input.beratBadan"
                                :tabindex="2" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static>Kg</VButton>
                            </VControl>
                          </VField>
                        </div>
                        Berat Badan / IMT
                        <div class="column is-12">
                          <VField></VField>
                          <VField addons>
                            <VControl expanded>
                              <VInput type="text" class="input" placeholder="IMT" v-model="input.IMT" />
                            </VControl>
                            <VControl class="field-addon-body">
                              <VButton static></VButton>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Monitoring......"
                                v-model="input.AntropometriPemeriksaanFisiBeratBadankMonitoring"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Evaluasi......"
                                v-model="input.AntropometriPemeriksaanFisikBeratBadanEvaluasi"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        Evaluasi Mingguan
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Evaluasi Mingguan......"
                                v-model="input.AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguan"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td width="170px">
                        Penurunan Kekuatan Tangan
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Penurunan Kekuatan Tangan......"
                                v-model="input.AntropometriPemeriksaanFisikPenurunanKekuatanTangan"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Monitoring......"
                                v-model="input.AntropometriPemeriksaanFisikPenurunanKekuatanTanganMonitoring"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Penurunan Kekuatan Tangan......"
                                v-model="input.AntropometriPemeriksaanFisikPenurunanKekuatanTanganEvaluasi"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        Evaluasi Mingguan / Bulanan
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Evaluasi Mingguan / Bulanan......"
                                v-model="input.AntropometriPemeriksaanFisikBeratBadanEvaluasiMingguanBulanan"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center" colspan="4">Pemeriksaan Laboratorium</th>
                    </tr>
                    <tr>
                      <th class="tg-0lax">Kriteria</th>
                      <th class="tg-0lax">Monitoring</th>
                      <th class="tg-0lax">Evaluasi</th>
                      <th class="tg-0lax">Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td width="170px">
                        Albumin
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Albumin......" v-model="input.pemeriksaanLabAlbumin">
                              </VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Monitoring......"
                                v-model="input.pemeriksaanLaboratoriumAlbuminkMonitoring"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Evaluasi......"
                                v-model="input.pemeriksaanLaboratoriumAlbuminEvaluasi"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td width="190px">
                        Data Dasar Lalu Evaluasi Mingguan
                        <div class="column is-12">
                          <VField style="padding:0px;">
                            <VControl raw subcontrol>
                              <VTextarea rows="2" placeholder="Data Dasar Lalu Evaluasi Mingguan......"
                                v-model="input.pemeriksaanLaboratoriumAlbuminEvaluasiMingguan"></VTextarea>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <VCard class="p-3">
              <div class="column is-12 px-2">
                <div class="columns is-multiline mb-5">
                  <div class="column is-12 P-0">
                    <div class="column is-12">
                      <span>*Evaluasi Mingguan/Bulanan dilakukan jika pasien dirawat lebih dari 1 minggu / bulan. (PNPK
                        MalnutrisiDewasa 2019. Hal. 71-77)</span>
                    </div>
                  </div>
                </div>
                <div class="columns is-multiline mb-5">
                  <div class="column is-12">
                    <span> Keterangan Tambahan :
                    </span>
                  </div>
                  <div class="column is-12">
                    <VField style="padding:0px;">
                      <VControl raw subcontrol>
                        <VTextarea rows="3" placeholder="Keterangan Tambahan..." v-model="input.keteranganTambahan">
                        </VTextarea>
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </Fieldset>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> Dokter Spesialis Gizi Klinis</h1>
            <div class="mt-6" style="text-align: center;" >
              <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
              <img v-if="input.dokterSpesialisGizi"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterSpesialisGizi ? input.dokterSpesialisGizi.label : '-')">
            </div>
            <div>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokterSpesialisGizi" :suggestions="d_Dokter"
                    @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Data..."
                    class="mt-2" @item-select="setTandaTangan($event)" />
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
import ButtonEmr from '../page-emr-plugins/button-emr-gizi-monitoring.vue'
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

const input: any = ref({
  diagnosisKerja: [{ no: 1 }]
})
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
const COLLECTION: any = ref("MonitoringEvaluasiTerapiGizi") //table mongodb
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
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDokter)
        }
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

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganDokter = H.tandaTangan().get("signature_1")
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
  json.data.diagnosisKerja.forEach((element: any, i: any) => {
  });
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

const addNewItem = () => {
  input.value.diagnosisKerja.push({
    no: input.value.diagnosisKerja[input.value.diagnosisKerja.length - 1].no + 1,
  });
}
const removeItem = (index: any) => {
  input.value.diagnosisKerja.splice(index, 1)
}

const kembaliKeun = () => {
  window.history.back()
}

watch(
    () => [
        input.value.beratBadan,
        input.value.tinggiBadan],
    (value) => {
        let txtFirstNumberValue: any = input.value.beratBadan;
        let txtSecondNumberValue: any = input.value.tinggiBadan;
        let result: any = parseFloat(txtFirstNumberValue) / (parseFloat(txtSecondNumberValue) / 100
            * parseFloat(txtSecondNumberValue) / 100);

        input.value.IMT = parseFloat(result).toFixed(2)
        if (isNaN(input.value.IMT)) {
            input.value.IMT = 0
        }
    }
)

const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi
  input.value.tanggalWaktuKunjuangan = new Date()
  input.value.tglPembuatan = new Date()
  input.value.umur = props.pasien.umur
  input.value.namapasien = props.pasien.namapasien
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.nocm = props.pasien.nocm
  input.value.namaruangan = props.registrasi.namaruangan
  input.value.namakelas = props.registrasi.namakelas
  input.value.dokter = props.registrasi.dokter
  input.value.dokterSpesialisGizi = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  // input.value.namadiagnosa = props.diagnosis.namadiagnosa

  isLoadingVitalSign.value = true;
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi"
  ).then((response) => {
    if (response != null) {
      // input.value.beratBadan = response.beratBadan
      // input.value.tinggiBadan = response.tinggiBadan
      // input.value.IMT = response.IMT
      // input.value.lingkarPerut = response.lingkarPerut
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
