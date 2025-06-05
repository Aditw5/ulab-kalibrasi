<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
              <div class="left">
                  <h3> {{ props.FORM_NAME }}</h3>
              </div>
              <div class="right">
                  <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
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
                <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                  @kembaliKeun="kembaliKeun"></ButtonEmr>
              </div>
            </div>
        </div>

    </div>
  </div>


  <div class="column">
    <VCard>
      <div class="columns is-multiline mb-5">
        <div class="column is-4">
          <VField label="Tanggal Pemeriksaan">
            <VDatePicker v-model="input.tanggalWaktuPindah" mode="dateTime" style="width: 100%" trim-weeks
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
        <div class="column is-2 mt-2">
          <span> Kategori Pasienn : </span>
        </div>
        <div class="column is-2">
          <VField>
            <VControl raw subcontrol>
              <VRadio v-model="input.kategoriPasien" label="Emergent" value="Emergent" class="p-0" color="primary"
                name="outlined_squared_radio" square />
            </VControl>
          </VField>
        </div>
        <div class="column is-2">
          <VField>
            <VControl raw subcontrol>
              <VRadio v-model="input.kategoriPasien" label="Urgent" value="Urgent" class="p-0" color="primary"
                name="outlined_squared_radio" square />
            </VControl>
          </VField>
        </div>
        <!-- <div class="column is-2">
          <VField>
            <VControl raw subcontrol>
            </VControl>
          </VField>
        </div> -->
      </div>
      <div class="columns is-multiline mb-5">
        <div class="column is-12 P-0">
          <div class="column is-12">
            <span> Dokter Jaga : </span>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.dokterJagaPagi" label="Pagi" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.dokterJagaSiang" label="Siang" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.dokterJagaMalam" label="Malam" class="p-0" color="primary" square />
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
            <span> Perawat : </span>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perawatJagaPagi" label="Pagi" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perawatJagaSiang" label="Siang" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.perawatJagaMalam" label="Malam" class="p-0" color="primary" square />
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
            <span> Konsulen : </span>
          </div>
          <div class="column is-12">
            <div class="columns is-multiline">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.konsulenJagaPagi" label="Pagi" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.konsulenJagaSiang" label="Siang" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.konsulenJagaMalam" label="Malam" class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <VField>
          <h1 style="font-weight: bold;">Diagnosis Kerja : (Assesmen) </h1>
          <VControl class="mt-2">
            <VTextarea rows="3" placeholder="Diagnosis Kerja..." v-model="input.diagnosisKerjaAssesmen"></VTextarea>
          </VControl>
        </VField>
      </div>

      <div class="column is-12">
        <div class="column is-12 p-2">
          <div class="columns is-multiline">
            <div class="column" style="overflow: scroll;">
              <table class="tg table-fkprj">
                <thead>
                  <tr class="tr-fkprj">
                    <th class="th-fkprj" width="2%" rowspan="2" style="vertical-align: inherit;">NO</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">Tanggal</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">TD (mmHg)</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">Suhu (<sup>o</sup>C)</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">Nadi (x/Mnt)</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">RR (x/Mnt)</th>
                    <th class="th-fkprj" colspan="2" style="text-align: center;">Reaksi</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">Extra Vasasi</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">Nyeri</th>
                    <th class="th-fkprj" rowspan="2" style="text-align: center;">Nama & Paraf</th>
                    <th class="th-fkprj" rowspan="2" style="vertical-align:inherit;text-align: center;" width="2%">Aksi
                    </th>
                  </tr>
                  <tr>
                    <th class="tg-0lax">Transfusi</th>
                    <th class="tg-0lax">Obat</th>
                  </tr>
                </thead>
                <tbody v-for="(item, index) in input.diagnosisKerja" :key="index">
                  <tr class="tr-fkprj">
                    <td class="td-fkprj">{{ item.no }}
                      <div class="column">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </div>
                    </td>
                    <td width="250px" class="td-fkprj">
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
                    <td width="250px" class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="TD" v-model="item.TD" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>mmHg</VButton>
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Suhu" v-model="item.Suhu" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static><sup>o</sup>C</VButton>
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Nadi" v-model="item.Nadi" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/Mnt</VButton>
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="RR" v-model="item.RR" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Transfusi" v-model="item.transfusi" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField>
                        <VControl>
                          <AutoComplete v-model="item.produk" :suggestions="d_produk" @complete="fetchProduk($event)"
                            :optionLabel="'namaproduk'" :dropdown="true" :minLength="3" class="is-input"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namaproduk'"
                            placeholder="ketik untuk mencari..." />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Extra Vasasi" v-model="item.extraVasasi" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj">
                      <VField addons>
                        <VControl>
                          <VInput type="text" class="input" placeholder="Nyeri" v-model="item.Nyeri" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj" width="250px">
                      <!-- <TandaTangan :elemenID="'signature_' + [index]" :width="'150'" :height="'150'" class="dek" /> -->
                      <img v-if="item.namaParafPetugas"
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (item.namaParafPetugas ? item.namaParafPetugas.label : '-')">
                      <VField class="is-autocomplete-select mt-2" v-slot="{ id }">
                        <VControl icon="feather:search">
                          <AutoComplete v-model="item.namaParafPetugas" :suggestions="d_Pegawai"
                            @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                            :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                            placeholder="ketik nama petugas" @item-select="setTandaTangan($event, 'signature_1')" />
                        </VControl>
                      </VField>
                    </td>
                    <td class="td-fkprj" style="vertical-align: inherit    ;">
                      <div class="column">
                        <VIconButton type="button" raised circle icon="feather:plus" @click="addNewItem()" color="info"
                          v-tooltip.bubble="'Tambah '">
                        </VIconButton>
                        <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                          @click="removeItem(index)" color="danger">
                        </VIconButton>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
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
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'

const dataTTD: any = ref([])
const d_produk: any = ref([])
const JenisKelamin: any = ref([
  { label: 'Laki - Laki', value: 'Laki-Laki' },
  { label: 'Perempuan', value: 'Perempuan' }
])


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosisKerja: [{ no: 1 }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk

        }
        dataTTD.value = response[0].diagnosisKerja
      }
    })
  dataTTD.value.forEach((element: any, i: any) => {
    H.tandaTangan().set("signature_" + [i], element.ttd)
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const setAutoFillJaga = async () => {
  let currentTime = new Date();
  let currentHour = currentTime.getHours();
  let timeZoneOffset = currentTime.getTimezoneOffset() / 60;
  let adjustedHour = currentHour + 7 + timeZoneOffset;

  if (adjustedHour >= 7 && adjustedHour < 14) {
    input.value.dokterJagaPagi = true;
    input.value.perawatJagaPagi = true;
    input.value.konsulenJagaPagi = true;
  } else if (adjustedHour >= 14 && adjustedHour < 21) {
    input.value.dokterJagaSiang = true;
    input.value.perawatJagaSiang = true;
    input.value.konsulenJagaSiang = true;
  } else {
    input.value.dokterJagaMalam = true;
    input.value.perawatJagaMalam = true;
    input.value.konsulenJagaMalam = true;
  }
}


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value

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
    element.ttd = H.tandaTangan().get("signature_" + [i])
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
  setAutoFill()
}
const removeItem = (index: any) => {
  input.value.diagnosisKerja.splice(index, 1)
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}
const setTandaTangan = async (e: any, i: any) => {
}


const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

  let dataPasien = H.setObjectPasien(props.pasien)
  let dataRegis = H.setObjectRegistrasi(props.registrasi)
  input.value.norm = dataPasien.nocm
  input.value.nama = dataPasien.namapasien
  input.value.poliklinik = dataRegis.namaruangan
  input.value.umur = dataPasien.umur
  interface JenisKelaminOption {
    value: string;
  }
  JenisKelamin.value.forEach((element: JenisKelaminOption) => {
    if (element.value == dataPasien.jeniskelamin) {
      input.value.jenisKelamin = element.value;
    }
  });
  input.value.diagnosisKerja.forEach(element => {
    element.namaParafPetugas = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  });

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=Triase" + "&field=Kesadaran,tandaVitalPernafasa,frekuensiNafas,frekuensiNadi,sikulasiSuhu,anakSuhu,anakSPO2"
  ).then((response) => {
    if (response == null) return
    if (response.Kesadaran == 'GCS < 9') {
      input.value.kategoriPasien = ref('Emergent')
    } else {
      input.value.kategoriPasien = ref('Urgent')
    }
    input.value.diagnosisKerja.forEach((item, index) => {
      item.TD = response.tandaVitalPernafasa;
      item.Nadi = response.frekuensiNadi
      item.Suhu = response.sikulasiSuhu ? response.sikulasiSuhu : response.anakSuhu;
      item.SPO2 = response.anakSPO2
    });
  })

  input.value.tglPembuatan = new Date()
}
setView()
setAutoFill()
loadRiwayat()
setAutoFillJaga()
</script>

<style lang="scss">
.table-fkprj {
  // width: 100% !important;
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
