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
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: scroll; ">
                <table class="tg table-fkprj" v-for="(data, index) in input.pews" :key="index">
                  <tbody>
                    <tr class="tr-fkprj">
                      <td width="400px" class="td-fkprj" colspan="4">
                        Tanggal/Bulan/Waktu
                      </td>
                      <td width="500px" class="td-fkprj" v-for="(datatgl, index) in data.tglpemantauan">
                        <VField class="mt-2">
                          <VDatePicker v-model="datatgl.tgl" mode="dateTime" style="width: 100%" trim-weeks
                            :max-date="new Date()" is24hr>
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
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        Perilaku</td>
                      <td width="250px" class="td-fkprj" colspan="3">Skor</td>
                      <td width="250px" class="td-fkprj" v-for="(itemPerilaku1, index) in data.perilaku1">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemPerilaku1.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        Kardiovaskuler</td>
                      <td class="td-fkprj" colspan="3">Skor</td>
                      <td width="250px" class="td-fkprj" v-for="(itemKardiovaskuler1, index) in data.kardiovaskuler1">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemKardiovaskuler1.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" style="text-align: center; font-size: 12pt;">
                        Respirasi
                      </td>
                      <td class="td-fkprj" colspan="3">Skor</td>
                      <td width="250px" class="td-fkprj" v-for="(itemRespirasi1, index) in data.respirasi1">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemRespirasi1.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="4">Skor Total :</td>
                      <td width="250px" class="td-fkprj" v-for="(itemTotalSkor, index) in data.totalSkor" :class="{
        'green-background': itemTotalSkor.skor >= 0 && itemTotalSkor.skor <= 2,
        'yellow-background': itemTotalSkor.skor === 3,
        'orange-background': itemTotalSkor.skor === 4,
        'red-background': itemTotalSkor.skor >= 5
      }">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemTotalSkor.skor" class="input" :disabled="true"/>
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="4">Frekuensi Monitoring</td>
                      <td width="250px" class="td-fkprj" v-for="(itemFrekuensi, index) in data.frekuensi">
                        <VField>
                          <VControl>
                            <VInput style="text-align: center;" v-model="itemFrekuensi.skor" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="4">Rencana Eskalasi</td>
                      <td width="250px" class="td-fkprj" v-for="(itemEskalasi, index) in data.eskalasi">
                        <VField>
                          <VControl>
                            <VInput v-model="itemEskalasi.skor" style="text-align: center;" class="input" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr class="tr-fkprj">
                      <td class="td-fkprj" colspan="4">Paraf/Inisial PPA</td>
                      <td width="250px" class="td-fkprj" v-for="(itemInisial, index) in data.inisial">
                        <!-- <VField>
                          <VControl>
                            <VInput v-model="itemInisial.skor" style="text-align: center;" class="input" />
                          </VControl>
                        </VField> -->
                        <TandaTangan :elemenID="'signature_' + [index]" :width="'100'" :height="'100'" class="dek" />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="mt-5">
        <Fieldset :toggleable="true" legend="INSTRUMEN PEDIATRIC EARLY WARNING SCORE (PEWS)">
          <div class="columns is-multiline">
            <div class="column is-12">
              <table border="1" class="triase">
                <thead>
                  <tr>
                    <th>Komponen</th>
                    <th>Skor 0</th>
                    <th>Skor 1</th>
                    <th>Skor 2</th>
                    <th>Skor 3</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <span>Perilaku</span>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li> Sesuai</li>
                            <li>bermain</li>
                            <li>Alert</li>
                            <li>Aktif (padabayi)</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li> Kurang aktif</li>
                            <li>Mengantuk (sleepy)</li>
                            <li>Rewel namun masih bias dibujuk</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Iritabel/sensitif</li>
                            <li>Mudah marah dan tidak dapat dibujuk</li>
                            <li>Rewel & tidak dapat didiamkan (Bayi)</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Letangik</li>
                            <li>Bingung ATAU</li>
                            <li>Berkurangnya respon terhadap nyeri</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>KARDIOVASKULER</span>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Pink ATAU</li>
                            <li>Waktu pengisian kapiler 1-2</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Pucat ATAU</li>
                            <li>Waktu pengisian kapiler 3 detik</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Abu-abu/ sianosis ATAU</li>
                            <li>Waktu pengisian kapiler 4 detik ATAU</li>
                            <li>Takikasdia &ge; 20 laju normal atau disertai diaphoresis</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Abu-abu/mottled ATAU</li>
                            <li>Waktu pengisian kapiler 5 detik</li>
                            <li>Takikardia (&ge;30 normal) ATAU</li>
                            <li>Omset baru bradicardia</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>RESPIRASI</span>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Laju napas normal DAN</li>
                            <li>Tidak ada peningkatan usaha napas (tidak ada retraksi)</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>10 di atsa normal ATAU</li>
                            <li>Penggunaan otot bantu napas/retraksi ringan ATAU</li>
                            <li>FiO<sub>2</sub> 30% (L/mnt)</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>20 di atas normal ATAU</li>
                            <li>Retraksi sedang ATAU</li>
                            <li>Saturasi O<sub>2</sub> => 5 poin dibawah normal ATAU</li>
                            <li>fiO<sub>2</sub> 40% (6 L/mnt)</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-12">
                          <ul style="list-style-type: disc;" class="ml-4">
                            <li>Laju napas melambat &ge; 5 poin dibawah normal disertai atai grunting ATAU</li>
                            <li>Saturasi O2 > 5 poin dibawah normal disertai merintih & retraksi berat</li>
                            <li>FiO<sub>2</sub> 50% (8 L/mnt)</li>
                          </ul>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-6">
              <h1>Nilai Normal Respirasi & Denyut Nadi Sesuai Usia :</h1>
              <table border="1" class="triase">
                <thead>
                  <tr>
                    <th>Kelompok Usia</th>
                    <th>Nadi/HR</th>
                    <th>Respirasi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <span>Prematur</span>
                    </td>
                    <td>
                      <span>120-180</span>
                    </td>
                    <td>
                      <span>50 - 70</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>Neonatur (0-30 hari)</span>
                    </td>
                    <td>
                      <span>100-160</span>
                    </td>
                    <td>
                      <span>40 - 60</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>Infant (1-12 bulan)</span>
                    </td>
                    <td>
                      <span>100-140</span>
                    </td>
                    <td>
                      <span>35 - 40</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>Toddler (1-3 thn)</span>
                    </td>
                    <td>
                      <span>80-130</span>
                    </td>
                    <td>
                      <span>20 - 30</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>Prsekolah (4-6 thn)</span>
                    </td>
                    <td>
                      <span>80-110</span>
                    </td>
                    <td>
                      <span>20 - 30</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>Usia Sekolahh (7-12 thn)</span>
                    </td>
                    <td>
                      <span>70-100</span>
                    </td>
                    <td>
                      <span>18 - 24</span>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <span>Remaja (13-16 thn)</span>
                    </td>
                    <td>
                      <span>60-90</span>
                    </td>
                    <td>
                      <span>14 - 22</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="column is-3">
              <table border="1" class="triase">
                <thead>
                  <tr>
                    <th>InterpretasiSkor :</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <VTag :color="'green'" rounded style="width: 100px;height: 100px;" />
                        </div>
                        <div class="column is-6 mt-5">
                          <span style="font-weight: bold;">Skor (0-2)</span>
                        </div>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <VTag :color="'warning'" rounded style="width: 100px;height: 100px;" />
                        </div>
                        <div class="column is-6 mt-5">
                          <span style="font-weight: bold;">Skor 3</span>
                        </div>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <VTag :color="'orange'" rounded style="width: 100px;height: 100px;" />
                        </div>
                        <div class="column is-6 mt-5">
                          <span style="font-weight: bold;">Skor 4</span>
                        </div>
                      </div>
                      <div class="columns is-multiline">
                        <div class="column is-6">
                          <VTag :color="'danger'" rounded style="width: 100px;height: 100px;" />
                        </div>
                        <div class="column is-6 mt-5">
                          <span style="font-weight: bold;">Skor &ge; 5</span>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </Fieldset>
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
const COLLECTION: any = ref("PediatricEarlyWarningScoreChart") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const lengthTgl: any = ref(30)
const arrayTanggal = []
for (let i = 1; i <= lengthTgl.value; i++) {
  arrayTanggal.push({ no: i });
}

const input: any = ref({
  pews: [{
    no: 1,
    tglpemantauan: arrayTanggal,
    ptd: ptdEMR.value,
    perilaku1: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
    kardiovaskuler1: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
    respirasi1: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
    totalSkor: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
    frekuensi: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
    eskalasi: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
    inisial: Array.from({ length: 30 }, (_, j) => ({ no: j + 1 })),
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
        dataTTD.value = response[0].pews[0].inisial;

        dataTTD.value.forEach((element: any, index: any) => {
          H.tandaTangan().set("signature_" + [index], element.ttd, 100, 100);
        });
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
  json.data.pews[0].inisial.forEach((element: any, index: any) => {
    element.ttd = H.tandaTangan().get("signature_" + index);
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

const deepCopyArray = (arr) => {
  return arr.map(obj => ({ ...obj }));
}

const addNewPTV = () => {
  input.value.pews.push({
    no: input.value.pews[input.value.pews.length - 1].no + 1,
    tglpemantauan: deepCopyArray(arrayTanggal),
    ptd: deepCopyArray(ptdEMR.value),
    perilaku1: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    kardiovaskuler1: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    respirasi1: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    totalSkor: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    frekuensi: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    eskalasi: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
    inisial: Array.from({ length: 15 }, (_, j) => ({ no: j + 1 })),
  });
  setAutoFill()
}
const removePTV = (index: any) => {
  input.value.pews.splice(index, 1)
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

const calculateTotalScore = (index) => {
  let totalScore = 0;

  totalScore += input.value.pews[0].perilaku1[index].skor || 0;
  totalScore += input.value.pews[0].kardiovaskuler1[index].skor || 0;
  totalScore += input.value.pews[0].respirasi1[index].skor || 0;

  let sum = 0;
  while (totalScore) {
    sum += totalScore % 10;
    totalScore = Math.floor(totalScore / 10);
  }
  totalScore = sum;

  input.value.pews[0].totalSkor[index].skor = totalScore;
  console.log(totalScore);
};


watch(
  () => input.value.pews[0].perilaku1.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.pews[0].kardiovaskuler1.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);
watch(
  () => input.value.pews[0].respirasi1.map((item, index) => {
    watch(
      () => item,
      (newValue, oldValue) => {
        calculateTotalScore(index);
      },
      { deep: true }
    );
  }),
  () => {
  },
  { deep: true }
);

setView()
setAutoFill()
setRegisPasien()
loadRiwayat()
setAutoFillDiagnosa()
</script>


<style lang="scss">
.table-fkprj {
  width: 470% !important;
  border-collapse: collapse !important;
}

.table-responsive {
  overflow-x: scroll;
}

.table-fkprj,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 200%;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  // overflow: hidden;
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
  // overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: middle
}
</style>
<style lang="scss">
table.triase {
  border-collapse: collapse;
  width: 100%;
}

table.triase,
th,
.triase td {
  border: 1px solid black;
}

table.triase,
th {
  // text-align: center;

}

.bg-green {
  background-color: var(--primary);
}

.bg-warning {
  background-color: var(--warning);
}

.bg-danger {
  background-color: var(--danger);
}

.triase th,
td {
  padding: 8px;
  vertical-align: top !important;
}

.yellow-background {
  background-color: yellow;
}

.green-background {
  background-color: green;
}

.orange-background {
  background-color: orange;
}

.red-background {
  background-color: red;
}
</style>