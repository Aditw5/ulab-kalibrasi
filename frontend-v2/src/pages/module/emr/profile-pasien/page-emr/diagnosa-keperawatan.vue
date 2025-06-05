<template>
  <FloatingButtonSimpan @click="simpan" :loading="isLoading" />
  <FloatingButtonCetak @click="print" :loading="isLoading" />
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;"
        class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="column is-12 p-2">
          <div class="columns is-multiline">
            <div class="column" style="overflow: scroll;">
              <table class="table-ptv table-fkprj">
                <thead>
                  <tr class="tr-fkprj">
                    <th class="th-fkprj" width="2%" rowspan="2" style="font-weight: bold;vertical-align: inherit;">NO
                    </th>
                    <th class="th-fkprj" width="90%" style="font-weight: bold;text-align: center;">Diagnosa</th>
                    <th class="th-fkprj" style="font-weight: bold;vertical-align:inherit;text-align: center;"
                      width="2%">Prioritas
                    </th>
                    <th class="th-fkprj" style="vertical-align:inherit;text-align: center;" width="2%">#
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in input.diagnosisKerja" :key="item.no" class="tr-fkprj">
                    <td width="3%" class="td-fkprj">{{ item.no }}</td>
                    <td width="30%" class="td-fkprj">
                      <div class="drag-handle" draggable="true" @dragstart="dragStart(index)" @dragover.prevent
                        @drop="drop(index)" style="cursor: grab; padding: 5px; ">
                        <VField class="is-rounded-select is-autocomplete-select">
                          <VControl class="prime-auto">
                            <Dropdown v-model="item.diagnosakeperawatan" :options="d_DiagnosaKep"
                              :optionLabel="'diagnosakep'" placeholder="Pilih Diagnosa" style="width: 100%;"
                              :filter="true" appendTo="body" optionGroupLabel="detailjenisdiagnosakeperawatan"
                              optionGroupChildren="details" :showClear="true">
                              <template #optiongroup="{ option }">
                                <div class="flex align-items-center country-item">
                                  <i class="fas fa-layer-group mr-2 mt-1" aria-hidden="true"></i>
                                  <div>{{ option.detailjenisdiagnosakeperawatan }}</div>
                                </div>
                              </template>
                            </Dropdown>
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="7%" class="td-fkprj">
                      <div class="column p-1">
                        <VField>
                          <VControl class="prime-auto">
                            <VInput type="number" v-model="item.prioritas" style="font-weight: bold;" />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td width="7%" class="td-fkprj">
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
import Dropdown from 'primevue/dropdown';
import FloatingButtonSave from './float-button-save-pengkajian-keperawatan.vue'
import FloatingButtonSimpan from './float-button-save-pengkajian-rajal.vue'
import FloatingButtonCetak from './float-button-cetak-pengkajian-keperawatan.vue';

const dataTTD: any = ref([])
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
const d_DiagnosaKep: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const d_DiagnosaKeperawatan: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input = ref({
  diagnosisKerja: [{ no: 1, diagnosakeperawatan: "", prioritas: 1 }]
});
let draggedItemIndex: number | null = null;
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  await setAutoFill()
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

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?pdf=true&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const fetchDiagnosaKep = async () => {
  isLoading.value = true;
  await useApi().get(`/emr/list-diagnosa-keperawatan-edokep?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
    isLoading.value = false;
    d_DiagnosaKep.value = response.list_tindakan;
  })
    .catch((error) => {
      isLoading.value = false;
      console.error("Error fetching data:", error);
    });
};

const addNewItem = () => {
  input.value.diagnosisKerja.push({
    no: input.value.diagnosisKerja.length + 1,
    diagnosakeperawatan: "",
    prioritas: input.value.diagnosisKerja.length + 1
  });
};

const removeItem = (index: number) => {
  input.value.diagnosisKerja.splice(index, 1);
};

const dragStart = (index: number) => {
  draggedItemIndex = index;
};

const drop = (index: number) => {
  if (draggedItemIndex === null || draggedItemIndex === index) return;

  let items = JSON.parse(JSON.stringify(input.value.diagnosisKerja));
  const movedItem = { ...items[draggedItemIndex] };

  items.splice(draggedItemIndex, 1);
  items.splice(index, 0, movedItem);

  input.value.diagnosisKerja = items.map((item, i) => ({
    no: i + 1,
    diagnosakeperawatan: item.diagnosakeperawatan,
    prioritas: input.value.diagnosisKerja[i].prioritas
  }));

  draggedItemIndex = null;
};

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
  input.value.tanggalWaktuPindah = new Date()
  input.value.nama = dataPasien.namapasien
  input.value.poliklinik = dataRegis.namaruangan
  input.value.umur = dataPasien.umur
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=Triase" + "&field=Kesadaran,tandaVitalPernafasa,frekuensiNafas,frekuensiNadi,sikulasiSuhu,anakSuhu,anakSPO2,saturasiOksigen"
  ).then((response) => {
    if (response == null) return
    if (response.Kesadaran == 'GCS < 9') {
      input.value.kategoriPasien = ref('Emergent')
    } else {
      input.value.kategoriPasien = ref('Urgent')
    }
    input.value.diagnosisKerja.forEach((item, index) => {
      item.tglPembuatan = new Date()
      item.TD = response.tandaVitalPernafasa;
      item.Nadi = response.frekuensiNadi
      item.RR = response.frekuensiNafas
      item.GCS = response.Kesadaran
      item.Suhu = response.sikulasiSuhu ? response.sikulasiSuhu : response.anakSuhu;
      item.SPO2 = response.anakSPO2 ? response.anakSPO2 : response.saturasiOksigen
    });
  })

  input.value.tglPembuatan = new Date()
}
setView()
onMounted(async () => {
  // await setAutoFill()
  await loadRiwayat()
  await fetchDiagnosaKep()
})
</script>

<style lang="scss">
.drag-handle {
  cursor: grab;
}

.btn-cppt {
  -webkit-border-radius: 20px;
  -moz-border-radius: 20px;
  border-radius: 20px;
  cursor: pointer;
  font-size: 12px;
  font-weight: bolder;
  color: #cd9424;
  padding: 7px 20px;
  min-width: 90px;
  text-align: center;
  position: fixed;
  -webkit-transition-duration: 0.25s;
  -webkit-transition-duration: 0.25s;
  -moz-transition-duration: 0.25s;
  -o-transition-duration: 0.25s;
  transition-duration: 0.25s;
  -webkit-transition-property: background-color, -webkit-box-shadow;
  right: 0;
  -webkit-transition-property: background-color, box-shadow;
  -o-transition-property: background-color, box-shadow;
  transition-property: background-color, box-shadow;
  z-index: 2;
}

.btn-border-cppt {
  border: 2px solid #cd9424;
}

.btn-full-cppt {
  background-color: #a9ebbd;
}

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

.table-ptv {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.table-ptv td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  // overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.table-ptv th {
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

.table-ptv .table-ptv-0lax {
  text-align: left;
  vertical-align: middle
}

@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';
@import '/@src/scss/module/emr/order-laboratorium.scss';
</style>
