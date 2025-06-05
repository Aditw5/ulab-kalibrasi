<template>
  <FloatingButtonSimpan @click="simpan" :loading="isLoading" />
  <FloatingButtonCetak @click="print" :loading="isLoading" />
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
        <div class="column" style="overflow: scroll;">
          <table class=" table-ptv-perencanaan">
            <thead>
              <tr class="tr-po">
                <th class="th-po" width="20%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Diagnosa</th>
                <th class="th-po" width="30%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Luaran</th>
                <th class="th-po" width="15%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Skor</th>
                <th class="th-po" width="50%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Intervensi</th>
              </tr>
            </thead>
            <tbody v-for="(items, index) in input.detailTindakan" :key="index">
              <tr class="tr-po">
                <td class="td-po">
                  <div class="column p-1">
                    <VField>
                      <VControl class="prime-auto">
                          <VInput type="text" v-model="items.detailjenisdiagnosakep" disabled style="font-weight: bold;"
                            placeholder="Sub Kategori Diagnosa" />
                          <VInput type="text" v-model="items.diagnosakep" disabled 
                            placeholder="Diagnosa Keperawatan" />
                      </VControl>
                    </VField>
                  </div>
                </td>
                <td class="td-po">
                  <table class="full-width borderless-table">
                    <tbody>
                      <tr v-if="items.selectedTujuan?.length > 0">
                        <td colspan="2"><strong>Luaran Utama :</strong></td>
                      </tr>
                      <tr v-for="(tujuan, idx) in items.selectedTujuan" :key="'utama-' + idx">
                        <td>
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="text" v-model="tujuan.tujuankep" disabled style="font-weight: bold;" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 50px; text-align: right;">
                          <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                            v-tooltip-prime.top="'Hapus'" @click="hapusLuaranUtama(items)">
                          </VIconButton>
                        </td>
                      </tr>

                      <tr v-if="items.selectedKriteria?.length > 0">
                        <td colspan="2"><strong>Luaran Tambahan :</strong></td>
                      </tr>
                      <tr v-for="(kriteria, idx) in items.selectedKriteria" :key="'tambahan-' + idx">
                        <td>
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="text" v-model="kriteria.kriteria" disabled />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 50px; text-align: right;">
                          <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                            v-tooltip-prime.top="'Hapus'" @click="hapusLuaranTambahan(items, idx)">
                          </VIconButton>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <VButton color="orange" class="w-100 btn-slim" light rounded outlined
                    v-tooltip-prime.right="'Tambah Luaran'" @click="openLuaran(items)">Tambah Luaran 
                  </VButton>
                </td>
                <td class="td-po">
                  <table class="full-width borderless-table">
                    <tbody>
                      <tr v-if="items.selectedTujuan?.length > 0">
                        <td colspan="2"><strong>Luaran Utama :</strong></td>
                      </tr>

                      <tr v-for="(tujuan, idx) in items.selectedTujuan" :key="'skor-utama-' + idx">
                        <td style="width: 30px; text-align: left;">
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="number" v-model="tujuan.skor" placeholder="Skor" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 70px;">
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="text" v-model="tujuan.keterangan" placeholder="Keterangan" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                      <tr v-if="items.selectedKriteria?.length > 0">
                        <td colspan="2"><strong>Luaran Tambahan :</strong></td>
                      </tr>
                      <tr v-for="(kriteria, idx) in items.selectedKriteria" :key="'skor-tambahan-' + idx">
                        <td>
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="number" v-model="kriteria.skor" placeholder="Skor" />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="text" v-model="kriteria.keterangan" placeholder="Keterangan" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </td>
                <td class="td-po">
                  <table class="full-width borderless-table">
                    <tbody>
                      <tr v-if="items.selectedIntervensi?.length > 0">
                        <td colspan="2"><strong>Intervensi Utama :</strong></td>
                      </tr>
                      <tr v-for="(intervensi, idx) in items.selectedIntervensi" :key="'utama-' + idx">
                        <td>
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="text" v-model="intervensi.intervensi" disabled style="font-weight: bold;" />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 50px; text-align: right;">
                          <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                            v-tooltip-prime.top="'Hapus'" @click="hapusIntervensiUtama(items)">
                          </VIconButton>
                        </td>
                      </tr>

                      <tr v-if="items.selectedAktifitas?.length > 0">
                        <td colspan="2"><strong>Intervensi Tambahan :</strong></td>
                      </tr>
                      <tr v-for="(aktifitas, idx) in items.selectedAktifitas" :key="'tambahan-' + idx">
                        <td>
                          <VField>
                            <VControl class="prime-auto">
                              <VInput type="text" v-model="aktifitas.aktifitas" disabled />
                            </VControl>
                          </VField>
                        </td>
                        <td style="width: 50px; text-align: right;">
                          <VIconButton color="danger" outlined circle icon="lnil lnil-trash-can-alt-1"
                            v-tooltip-prime.top="'Hapus'" @click="hapusIntervensiTambahan(items, idx)">
                          </VIconButton>
                        </td>
                      </tr>
                    </tbody>
                  </table>

                  <VButton color="orange" class="w-100 btn-slim" light rounded outlined
                    v-tooltip-prime.right="'Tambah Intervensi'" @click="openIntervensi(items)">Tambah Intervensi 
                  </VButton>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </VCard>
  </div>
  <Dialog v-model:visible="isLuaran" modal header="Tambah Luaran" :style="{ width: '80vw' }">
  <div class="columns is-multiline">
      <div class="column is-6">
        <span>Tujuan :</span>
        <div v-for="(item, index) in listTujuan" :key="index" class="column is-12">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="selectedTujuan" :value="item" :label="item.tujuankep" @change="handleSelectedTujuan(item)" class="p-0" color="primary" square />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-6">
        <span>Kriteria :</span>
        <div v-for="(item, index) in listKriteria" :key="index" class="column is-12">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="selectedKriteria" :value="item" :label="item.kriteria"
                class="p-0" color="primary" square />
            </VControl>
          </VField>
        </div>
      </div>
    </div>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="closeLuaran()">
        Simpan
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="isIntervensi" modal header="Tambah Intervensi" :style="{ width: '80vw' }">
  <div class="columns is-multiline">
      <div class="column is-6">
        <span>Intervensi Utama :</span>
        <div v-for="(item, index) in listIntervensi" :key="index" class="column is-12">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="selectedIntervensi" :value="item" :label="item.intervensi" @change="handleSelectedIntervensi(item)" class="p-0" color="primary" square />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-6">
        <span>Intervensi Tambahan :</span>
        <div v-for="(item, index) in listAktifitas" :key="index" class="column is-12">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="selectedAktifitas" :value="item" :label="item.aktifitas"
                class="p-0" color="primary" square />
            </VControl>
          </VField>
        </div>
      </div>
    </div>
    <template #footer>
      <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="closeIntervensi()">
        Simpan
      </VButton>
    </template>
  </Dialog>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import Dialog from 'primevue/dialog'
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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const listTujuan = ref([]); 
const listKriteria = ref([]); 
const listAktifitas = ref([]); 
const listIntervensi = ref([]); 
const selectedTujuan = ref(null);
const selectedIntervensi = ref(null);
const selectedKriteria = ref([]); 
const selectedAktifitas = ref([]); 
const activeItem = ref(null);
const activeItemIntervensi = ref(null);
const isLuaran: any = ref(false)
const isIntervensi: any = ref(false)
const d_DiagnosaKeperawatan: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) 
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  detailTindakan: [{
    no: 1
  }],
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = async () => {
  await riwayatDiagnosaKeperawatan()
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0]
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk

        }
      }
    })
  dataTTD.value.forEach((element: any, i: any) => {
    H.tandaTangan().set("signature_" + [i], element.ttd)
  })
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?pdf=true&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi()
    .get(`/emr/list-diagnosa-keperawatan?query=${filter.query}`)
    .then((response) => {
      d_DiagnosaKeperawatan.value = response.diagnosaKeperawatan.map((e: any) => {
        return { value: e.id, label: e.diagnosakep, default: e }
      })
    })
}

const openLuaran = async (items) => {
  isLuaran.value = true;
  activeItem.value = items;

  selectedTujuan.value = [...(items.selectedTujuan || [])];
  selectedKriteria.value = [...(items.selectedKriteria || [])];

  await fetchTujuanDiagnosaKeperawatan(items);

  if (selectedTujuan.value.length > 0) {
    await fetchKriteriaDiagnosaKeperawatan(selectedTujuan.value[0]);
  }
};

const openIntervensi = async (items) => {
  isIntervensi.value = true;
  activeItemIntervensi.value = items;

  selectedIntervensi.value = [...(items.selectedIntervensi || [])];
  selectedAktifitas.value = [...(items.selectedAktifitas || [])];

  await fetchIntervensiDiagnosaKeperawatan(items);

  if (selectedIntervensi.value.length > 0) {
    await fetchAktifitasDiagnosaKeperawatan(selectedIntervensi.value[0]);
  }
};


const handleSelectedTujuan = (item) => {
  selectedTujuan.value = [item]; 
  selectedKriteria.value = []; 
  listKriteria.value = [];
  fetchKriteriaDiagnosaKeperawatan(item);
};

const handleSelectedIntervensi = (item) => {
  selectedIntervensi.value = [item]; 
  selectedAktifitas.value = []; 
  listAktifitas.value = [];
  fetchAktifitasDiagnosaKeperawatan(item);
};


const fetchIntervensiDiagnosaKeperawatan = async (e) => {
  try {
    const response = await useApi().get(`/emr/list-intervensi-diagnosa-keperawatan-edokep?qdiagnosakep=${e.qdiagnosakep}`);
    listIntervensi.value = response.data || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

const fetchTujuanDiagnosaKeperawatan = async (e) => {
  try {
    const response = await useApi().get(`/emr/list-tujuan-diagnosa-keperawatan-edokep?qdiagnosakep=${e.qdiagnosakep}`);
    listTujuan.value = response.data || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

const fetchKriteriaDiagnosaKeperawatan = async (e) => {
  try {
    const response = await useApi().get(`/emr/list-kriteria-diagnosa-keperawatan-edokep?objecttujuanfk=${e.idtujuan}`);
    listKriteria.value = response.data || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

const fetchAktifitasDiagnosaKeperawatan = async (e) => {
  try {
    const response = await useApi().get(`/emr/list-aktifitas-diagnosa-keperawatan-edokep?kodeintervensi=${e.kodeintervensi}`);
    listAktifitas.value = response.data || [];
  } catch (error) {
    console.error("Error fetching data:", error);
  }
};

const hapusLuaranUtama = (items) => {
  items.selectedTujuan = [];
  items.selectedKriteria = [];
};

const hapusIntervensiUtama = (items) => {
  items.selectedIntervensi = [];
  items.selectedAktifitas = [];
};

const hapusLuaranTambahan = (items, idx) => {
  items.selectedKriteria.splice(idx, 1);
};

const hapusIntervensiTambahan = (items, idx) => {
  items.selectedAktifitas.splice(idx, 1);
};


const closeLuaran = () => {
  if (activeItem.value) {
    activeItem.value.selectedTujuan = [...selectedTujuan.value]; 
    activeItem.value.selectedKriteria = [...selectedKriteria.value]; 
  }
  isLuaran.value = false;
  listTujuan.value = [];
  listKriteria.value = [];
};

const closeIntervensi = () => {
  if (activeItemIntervensi.value) {
    activeItemIntervensi.value.selectedIntervensi = [...selectedIntervensi.value]; 
    activeItemIntervensi.value.selectedAktifitas = [...selectedAktifitas.value]; 
  }
  isIntervensi.value = false;
  listIntervensi.value = [];
  listAktifitas.value = [];
};


const simpan = async () =>{
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


const riwayatDiagnosaKeperawatan = async () => {
  await useApi().get(
    `/diagnosa/riwayat-diagnosa-keperawatan?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
      console.log(response)
      if (response.data.length <= 0) {
        input.value.detailTindakan = [{
          no: 1,
          diagnosakep: '',
        }]
      } else {
        input.value.detailTindakan = response.data.map((detail: any, i: any) => {
          return {
            no: i + 1,
            diagnosakep: detail.diagnosakep,
            detailjenisdiagnosakep: detail.detailjenisdiagnosakeperawatan,
            kodediagnosakep: detail.kodediagnosakep,
            id: detail.id,
            qdiagnosakep: detail.qdiagnosakep
          }
        })
      }
    }).catch((e: any) => {
    })
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

setView()
onMounted(async() => {
  await loadRiwayat()
})
</script>

<style lang="scss">

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

.borderless-table {
  width: 100%;
  border-collapse: collapse;
}

.borderless-table td, 
.borderless-table th, 
.borderless-table tr {
  border: none !important;
  padding: 8px 0;
}

.full-width {
  width: 100%;
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

.table-ptv-perencanaan {
  border-collapse: collapse;
  border-spacing: 0;
  width: 150%;
}

.table-ptv-perencanaan td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  // overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.table-ptv-perencanaan th {
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

.table-ptv-perencanaan .table-ptv-perencanaan-0lax {
  text-align: left;
  vertical-align: middle
}
</style>
