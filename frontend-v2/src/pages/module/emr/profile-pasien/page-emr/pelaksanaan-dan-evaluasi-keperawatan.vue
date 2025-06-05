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
        <div class="columns is-multiline">
          <div class="column" style="overflow: scroll;">
            <table class="table-ptv-pelaksanaan table-fkprj-pelaksanaan">
              <thead>
                <tr class="tr-fkprj">
                  <th class="th-fkprj"  width="20%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Diagnosa</th>
                  <th class="th-fkprj"  width="20%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Luaran</th>
                  <th class="th-fkprj"  width="10%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Skor</th>
                  <th class="th-fkprj"  width="20%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Implementasi</th>
                  <th class="th-fkprj"  width="10%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Waktu Pelaksaan</th>
                  <th class="th-fkprj"  width="20%" style="font-weight: bold;vertical-align:inherit;text-align: center;">Evaluasi</th>
                </tr>
              </thead>
              <tbody v-for="(items, index) in input.detailTindakan" :key="index">
                <tr class="tr-fkprj">
                  <td class="td-fkprj">
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
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td class="td-po">
                    <table class="full-width borderless-table">
                      <tbody>
                        <tr v-if="items.selectedTujuan?.length > 0">
                          <td colspan="2"><strong>Luaran Utama :</strong></td>
                        </tr>
  
                        <tr v-for="(tujuan, idx) in items.selectedTujuan" :key="'skor-utama-' + idx">
                          <td style="width: 30px;">
                            <VField>
                              <VControl class="prime-auto">
                                <VInput style=" margin-right:3px"  type="number" v-model="tujuan.skor" placeholder="Skor" disabled/>
                              </VControl>
                            </VField>
                          </td>
                          <td style="width: 70px;">
                            <VField>
                              <VControl class="prime-auto">
                                <VInput style=" margin-left:2px" type="text" v-model="tujuan.keterangan" placeholder="Keterangan" disabled/>
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
                                <VInput style=" margin-right:3px"  type="number" v-model="kriteria.skor" placeholder="Skor" disabled/>
                              </VControl>
                            </VField>
                          </td>
                          <td>
                            <VField>
                              <VControl class="prime-auto">
                                <VInput  style=" margin-left:2px"  type="text" v-model="kriteria.keterangan" placeholder="Keterangan" disabled/>
                              </VControl>
                            </VField>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td class="td-fkprj">
                    <table class="full-width borderless-table">
                      <tbody>
                        <tr v-if="items.selectedIntervensi?.length > 0">
                          <td colspan="2"><strong>Utama :</strong></td>
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
                          <td colspan="2"><strong>Pendukung :</strong></td>
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
                      v-tooltip-prime.right="'Tambah Intervensi'" @click="openIntervensi(items)">Tambah Implementasi 
                    </VButton>
                  </td>
                  <td class="td-fkprj" width="250px" style="text-align: center;">
                    <div class="columns is-multiline">
                      <div class="column is-12">
                            <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
                            <VField>
                                <VDatePicker v-model="items.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
                      <div class="column is-12 ">
                        <img v-if="items.namaParafPetugas"
                      :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (items.namaParafPetugas ? items.namaParafPetugas.label : '-')">
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VControl class="prime-auto">
                            <AutoComplete v-model="items.namaParafPetugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik nama petugas" class="mt-2"
                              @item-select="setTandaTangan($event, 'signature_1')" />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td class="td-fkprj">
                    <table class="full-width borderless-table" v-if="items.selectedTujuan?.length > 0">
                      <tbody>
                        <tr >
                          <td style="width: 10%;margin-top:0.9rem"><strong>S :</strong></td>
                          <td style="width: 90%;">
                            <VField>
                              <VTextarea rows="1" placeholder="S" v-model="items.S"></VTextarea>
                            </VField>
                          </td>
                        </tr>
                        <tr >
                          <td style="width: 10%;margin-top:0.9rem"><strong>O :</strong></td>
                          <td style="width: 90%;">
                            <VField>
                              <VTextarea rows="1" placeholder="O" v-model="items.O"></VTextarea>
                            </VField>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <Dialog v-model:visible="isIntervensi" modal header="Tambah Implementasi" :style="{ width: '80vw' }">
    <div class="columns is-multiline">
      <div class="column is-6">
        <span>Utama :</span>
        <div v-for="(item, index) in listIntervensi" :key="index" class="column is-12">
          <VField>
            <VControl raw subcontrol>
              <VCheckbox v-model="selectedIntervensi" :value="item" :label="item.intervensi" @change="handleSelectedIntervensi(item)" class="p-0" color="primary" square />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="column is-6">
        <span>Pendukung :</span>
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
import AutoComplete from 'primevue/autocomplete';
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
const selectedTujuan = ref(null);
const selectedKriteria = ref([]); 
const d_DiagnosaKeperawatan: any = ref([])
const COLLECTION: any = ref(props.COLLECTION) 
const NOREC_EMRPASIEN: any = ref('')
const selectedIntervensi = ref(null);
const listIntervensi = ref([]); 
const listAktifitas = ref([]); 
const selectedAktifitas = ref([]); 
const isIntervensi: any = ref(false)
const activeItemIntervensi = ref(null);
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
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?pdf=true&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

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

const handleSelectedIntervensi = (item) => {
  selectedIntervensi.value = [item]; 
  selectedAktifitas.value = []; 
  listAktifitas.value = [];
  fetchAktifitasDiagnosaKeperawatan(item);
};

const hapusIntervensiUtama = (items) => {
  items.selectedIntervensi = [];
  items.selectedAktifitas = [];
};

const hapusIntervensiTambahan = (items, idx) => {
  items.selectedAktifitas.splice(idx, 1);
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

const fetchIntervensiDiagnosaKeperawatan = async (e) => {
  try {
    const response = await useApi().get(`/emr/list-intervensi-diagnosa-keperawatan-edokep?qdiagnosakep=${e.qdiagnosakep}`);
    listIntervensi.value = response.data || [];
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
    `/diagnosa/riwayat-perencanaan-keperawatan?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`).then((response: any) => {
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
            detailjenisdiagnosakep: detail.detailjenisdiagnosakep,
            kodediagnosakep: detail.kodediagnosakep,
            id: detail.id,
            qdiagnosakep: detail.qdiagnosakep,
            selectedTujuan: detail.selectedTujuan,
            selectedKriteria: detail.selectedKriteria,
            tglPembuatan : new Date()
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
const setTandaTangan = async (e: any, i: any) => {
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

.table-fkprj-pelaksanaan {
  // width: 100% !important;
  border-collapse: collapse !important;
}

.table-responsive {
  overflow-x: scroll;
}

.table-fkprj-pelaksanaan,
.tr-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}

.table-ptv-pelaksanaan {
  border-collapse: collapse;
  border-spacing: 0;
  width: 180%;
}

.table-ptv-pelaksanaan td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  // overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.table-ptv-pelaksanaan th {
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

.table-ptv-pelaksanaan .table-ptv-pelaksanaan-0lax {
  text-align: left;
  vertical-align: middle
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


</style>
