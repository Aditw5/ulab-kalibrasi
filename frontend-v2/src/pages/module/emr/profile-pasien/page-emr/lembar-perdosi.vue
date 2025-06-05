<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Lembar Perdosri (Lembar C)</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
            <div class="buttons">
              <!-- <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-printer"
                :disabled="NOREC_EMRPASIEN.length == 0" @click="ubahTangagalCetak()" v-if="!props.isHideCetak"> Cetak Sesuai Tanggal
              </VButton> -->
            </div>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> Lembar Perdosri (Lembar C)</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
            <div class="buttons">
              <!-- <VButton type="button" rounded outlined color="danger" raised icon="lnir lnir-printer"
                :disabled="NOREC_EMRPASIEN.length == 0" @click="ubahTangagalCetak()" v-if="!props.isHideCetak"> Cetak Sesuai Tanggal
              </VButton> -->
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> No.RM </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="NoCM... " v-model="input.nocm" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Nama Pasien: </span>
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
                <span> Diagnosa: </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Diagnosa... " v-model="input.diagnosa" />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12 p-0">
            <div class="is-flex">
              <div class="column is-2" style="margin-top:0.5rem">
                <span> Permintaan Terapi : </span>
              </div>
              <div class="column is-6">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.permintaanTerapi" rows="3" placeholder="Permintaan Terapi"></VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <!-- <div class="column is-12">
          <Fieldset :toggleable="true" legend="">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column" style="overflow: auto;">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th class="tg-0lax text-center" rowspan="2">PROGRAM</th>
                        <th class="tg-0lax text-center" rowspan="2">TANGGAL</th>
                        <th class="tg-0lax text-center" colspan="3">TTD</th>
                      </tr>
                      <tr>
                        <th class="tg-0lax">PASIEN</th>
                        <th class="tg-0lax">DOKTER</th>
                        <th class="tg-0lax">TERAPIS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(data, i) in dataPasien" :key="item.index">
                        <td width="100px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VTextarea v-model="input['program_' + i]" rows="3" placeholder="Program"></VTextarea>
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="data.tglregistrasi" class="input" disabled>
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="data.namapasien" class="input" disabled>
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <AutoComplete v-model="input['namaDokter_' + i]" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Data..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <AutoComplete v-model="input['namaTerapis_' + i]" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik nama petugas" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </Fieldset>
        </div> -->
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column" style="overflow: auto;">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th class="tg-0lax text-center" rowspan="2">#</th>
                        <th class="tg-0lax text-center" rowspan="2">NO</th>
                        <th class="tg-0lax text-center" rowspan="2">PROGRAM</th>
                        <th class="tg-0lax text-center" rowspan="2">REASSESSMENT</th>
                        <th class="tg-0lax text-center" rowspan="2">TANGGAL</th>
                        <th class="tg-0lax text-center" colspan="3">TTD</th>
                      </tr>
                      <tr>
                        <th class="tg-0lax">PASIEN</th>
                        <th class="tg-0lax">DOKTER</th>
                        <th class="tg-0lax">TERAPIS</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(data, index) in input.detaiperdosi" :key="index">
                        <td width="3px" class="td-fkprj" style="vertical-align: inherit;">
                          <div class="column">
                            <VIconButton type="button" raised circle icon="feather:plus"
                              @click="addNewItemObatRawatInap()" color="info" v-tooltip.bubble="'Tambah '">
                            </VIconButton>
                            <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                              @click="removeItemObatRawatInap(index)" color="danger">
                            </VIconButton>
                          </div>
                        </td>
                        <td width="2px">{{ data.no }}</td>
                          <td width="150px">
                            <div class="columns is-multiline">
                              <div class="column is-9">
                                <VField style="padding:0px 10px;">
                                  <VControl>
                                    <VTextarea v-model="data.namaProgram2" rows="3" placeholder="Program"></VTextarea>
                                  </VControl>
                                </VField>
                              </div>
                              <div class="column is-3 mt-4">
                                  <VButton color="danger" class="w-100 btn-slim" light rounded outlined
                                    v-tooltip-prime.right="'Tindakan'" @click="isLab = true"> Tindakan </VButton>
                              </div>
                            </div>
                          </td>
                        <!-- <td width="150px" height="150px">
                          <div class="column is-12">
                          <VField class="is-rounded-select  is-autocomplete-select" v-slot="{ id }" style="padding:0px 10px;">
                            <VControl icon="feather:list" fullwidth>
                                <Multiselect mode="single" v-model="data.produk" :options="d_Produk"
                                  placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                  autocomplete="off"/>
                            </VControl>
                          </VField>
                        </div>
                        </td> -->
                        <td width="40px" class="text-center">
                          <VField style="padding:0px 10px;">
                            <VControl raw subcontrol class="p-0">
                              <VCheckbox class="pt-0" v-model="data.reassessment"
                                color="primary" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px" text>
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="data.tglregistrasi2" class="input" disabled>
                              </VInput>
                              <VInput v-model="data.tglDirawat" class="input" disabled style="display:none">
                              </VInput>
                              <!-- <AutoComplete v-model="data.tglregistrasi2" :suggestions="d_Registrasi"
                                @complete="setRegisPasien($event)" :optionLabel="'tglregistrasi'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'tglregistrasi'"
                                placeholder="ketik nama petugas" /> -->
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="data.namapasien2" class="input">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <AutoComplete v-model="data.namadokter2" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari Data..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="40px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <AutoComplete v-model="data.namaterapis2" :suggestions="d_Pegawai"
                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="ketik nama petugas" />
                            </VControl>
                          </VField>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
      </VCard>
    </div>
  </div>
  <VModal :open="modalCetakTanggal" title="Pilih Tanggal cetak" size="medium" actions="right"
        @close="modalCetakTanggal = false">
        <template #content>
            <form class="modal-form">
                <div class="field">
                    <VField class="is-autocomplete-select">
                        <VLabel>Cetak Tanggal</VLabel>
                              <VControl>
                              <!-- <VInput v-model="data.tglregistrasi2" class="input" disabled>
                              </VInput> -->
                              <AutoComplete v-model="item.tglregistrasi2" :suggestions="d_Registrasi"
                                @complete="setRegisPasien($event)" :optionLabel="'tglregistrasi'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'tglregistrasi'"
                                placeholder="Pilih Tangal Cetak" />
                            </VControl>
                    </VField>
                </div>
            </form>
        </template>
        <template #action>
            <VButton color="primary" :loading="isLoadUpdate" @click="print(item)">CETAK LEMBAR</VButton>
        </template>
    </VModal>
    <Dialog v-model:visible="isLab" modal header="Order Laboratorim" :style="{ width: '80vw' }">
      <OrderTindakan :pasien="props.pasien" :registrasi="props.registrasi" />
      <template #footer>
        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="tutupLabSetAutofill()">
          Tutup
        </VButton>
      </template>
    </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-perdosi.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import MultiSelect from 'primevue/multiselect';
import Dialog from 'primevue/dialog';
import { onBeforeRouteLeave } from 'vue-router';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from 'primevue/useconfirm'
import OrderTindakan from './tindakan.vue'

const isLab: any = ref(false)
let modalCetakTanggal: any = ref(false)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPasien: any = ref([])


const tutupLabSetAutofill = async () => {
  isLab.value = false;
  // setAutoFill()
  setAutoFillTutup()
}

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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isSave: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Registrasi: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const d_JK: any = ref([])
const listPerdosi: any = ref([])
const isLoadingTT = ref(false);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  tglregistrasi2: reactive(''),
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const confirm = useConfirm()
const COLLECTION: any = ref("LembarPerdosi") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  detaiperdosi: [{ no: 1 }]
})
const ubahTangagalCetak = () => {
  modalCetakTanggal.value = true
}
const print = (item) => {
    const tanggal = item.tglregistrasi2 ? item.tglregistrasi2.tglregistrasi : '';
    H.printBlade(`emr/cetak-rehab-perdosi/${COLLECTION.value}?pdf=true&emrpasienfk=${NOREC_EMRPASIEN.value}&tanggal=${tanggal}`)
}
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
    `/emr/get-emr-perdosi?nocmfk=${ID_PASIEN}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      // console.log(response[0]);
      listPerdosi.value = JSON.parse(JSON.stringify(response[0].detaiperdosi));
      // console.log(listPerdosi.value)
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
  dataTTD.value.forEach((element: any, i: any) => {
    H.tandaTangan().set("signature_" + [i], element.ttd)
  })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {};
  const isHapus: any = ref(false);
  const isEditPerdosi: any = ref(false);
  let changedColumns: any = [];

  object = input.value;
  object.pasien = H.setObjectPasien(props.pasien);
  object.registrasi = H.setObjectRegistrasi(props.registrasi);
  let tanggalRegistrasi = dataPasien.value.map((item: any) => item.tglregistrasi);
  if (object.detaiperdosi.length < listPerdosi.value.length) {
    isHapus.value = true;
  }
  if (object.detaiperdosi.length) {
    for (let y = 0; y < object.detaiperdosi.length; y++) {
      const element2 = object.detaiperdosi[y];
      for (let z = 0; z < listPerdosi.value.length; z++) {
        const elementPerdosi = listPerdosi.value[z];
        const dokterValueMatch = elementPerdosi.namadokter2?.value !== element2.namadokter2?.value;
        const terapisValueMatch = elementPerdosi.namaterapis2?.value !== element2.namaterapis2?.value;
        const programMatch = elementPerdosi.namaProgram2 !== element2.namaProgram2;
        if (elementPerdosi.tglregistrasi2 === element2.tglregistrasi2 && (dokterValueMatch || programMatch || terapisValueMatch)) {
          isEditPerdosi.value = true;
          let changedField: { [key: string]: any } = {};
          if (dokterValueMatch) changedField['namadokter2'] = element2.namadokter2;
          if (terapisValueMatch) changedField['namaterapis2'] = element2.namaterapis2;
          if (programMatch) changedField['namaProgram2'] = element2.namaProgram2;

          changedColumns.push({
            tglregistrasi2: element2.tglregistrasi2,
            ...changedField
          });
        }
      }
    }
  }
// console.log(isEditPerdosi.value)
// console.log(changedColumns)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object,
    'tanggal_registrasi': tanggalRegistrasi,
    'isHapus': isHapus.value,
    'isEdit': isEditPerdosi.value,
    'changedColumns': changedColumns
  };

  if (!object.detaiperdosi[object.detaiperdosi.length - 1].namadokter2) {
    H.alert('warning', 'Dokter harus di isi')
    return;
  }

  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      isSave.value = true
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
      loadRiwayat()
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const addNewItemObatRawatInap = () => {
  input.value.detaiperdosi.push({
    no: input.value.detaiperdosi[input.value.detaiperdosi.length - 1].no + 1,
  });
  setAutoFillTambah()
}
const removeItemObatRawatInap = (index: any) => {
  input.value.detaiperdosi.splice(index, 1)
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
  fetchJenisKelamin()
  input.value.tglPembuatan = new Date()
  input.value.namaPasien = props.pasien.namapasien
  // input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglregistrasi2 = props.registrasi.tglregistrasi
  input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglregistrasi2 = H.formatDate(new Date, 'YYYY-MM-DD HH:mm:ss')
  input.value.detaiperdosi.forEach(data => {
    data.namapasien2 = props.pasien.namapasien
    if(useUserSession().getUser().kelompokUser.kelompokUser == 'dokter'){
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namadokter2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    }else{
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namadokter2 = {label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk}
    }
    input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namaterapis2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  })
  input.value.nocm = props.pasien.nocm
  input.value.tgllahir = props.pasien.tgllahir
  input.value.nohp = props.pasien.nohp
  input.value.jeniskelamin = props.pasien.jeniskelamin
  input.value.tglPembuatan = new Date()
  input.value.tanggalWaktuKejadian = new Date()
  // input.value.tglDirawat = props.registrasi.tglregistrasi
  input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglDirawat = props.registrasi.tglregistrasi
  input.value.dokter = props.registrasi.dokter
  input.value.umur = props.pasien.umur
  input.value.alamat = props.pasien.alamatlengkap
  let lab = ''
  // await useApi().get(
  //   /kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true).then((response: any) => {
  //     if (response != null) {
  //       lab = '';
  //       console.log(response)
  //       response.detail.forEach(element => {
  //         console.log(response)
  //         element.details.forEach((item, index) => {
  //           lab += item.namaproduk
  //           if (index != element.details.length - 1) {
  //             lab += ', '
  //           }
  //         })
  //       })
  //       input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namaProgram2 = ref(lab)
  //     }
  //   })
  await useApi().get(`/kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true`).then((response: any) => {
    if (response != null) {
      lab = '';
      response.detail.forEach(element => {
        element.details.forEach((item, index) => {
          // if (item.keteranganlain !== 'administrasi otomatis') {
            lab += item.namaproduk;
            if (index !== element.details.length - 1) {
              lab += ', ';
            }
          // }
        });
      });
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namaProgram2 = ref(lab);
    }
});

}

const setAutoFillTambah = async () => {
  fetchJenisKelamin();
  input.value.tglPembuatan = new Date();
  input.value.namaPasien = props.pasien.namapasien;
  input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglregistrasi2 = H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss');
  input.value.detaiperdosi.forEach((data) => {
    data.namapasien2 = props.pasien.namapasien;
    if (useUserSession().getUser().kelompokUser.kelompokUser === 'dokter') {
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namadokter2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id };
    } else {
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namadokter2 = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk };
    }
    input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namaterapis2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id };
  });

  input.value.nocm = props.pasien.nocm;
  input.value.tgllahir = props.pasien.tgllahir;
  input.value.nohp = props.pasien.nohp;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.tglPembuatan = new Date();
  input.value.tanggalWaktuKejadian = new Date();
  input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglDirawat = props.registrasi.tglregistrasi;
  input.value.dokter = props.registrasi.dokter;
  input.value.umur = props.pasien.umur;
  input.value.alamat = props.pasien.alamatlengkap;

  let lab = '';
};

const setAutoFillTutup = async () => {
  fetchJenisKelamin();
  input.value.tglPembuatan = new Date();
  input.value.namaPasien = props.pasien.namapasien;
  input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglregistrasi2 = H.formatDate(new Date(), 'YYYY-MM-DD HH:mm:ss');

  input.value.detaiperdosi.forEach((data) => {
    data.namapasien2 = props.pasien.namapasien;
    if (useUserSession().getUser().kelompokUser.kelompokUser === 'dokter') {
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namadokter2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id };
    } else {
      input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namadokter2 = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk };
    }
    input.value.detaiperdosi[input.value.detaiperdosi.length - 1].namaterapis2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id };
  });

  input.value.nocm = props.pasien.nocm;
  input.value.tgllahir = props.pasien.tgllahir;
  input.value.nohp = props.pasien.nohp;
  input.value.jeniskelamin = props.pasien.jeniskelamin;
  input.value.tglPembuatan = new Date();
  input.value.tanggalWaktuKejadian = new Date();
  input.value.detaiperdosi[input.value.detaiperdosi.length - 1].tglDirawat = props.registrasi.tglregistrasi;
  input.value.dokter = props.registrasi.dokter;
  input.value.umur = props.pasien.umur;
  input.value.alamat = props.pasien.alamatlengkap;

  let lab = '';

  // const hasSameRegistrasi = listPerdosi.value.some((elementPerdosi) => {
  //   return elementPerdosi.tglDirawat === props.registrasi.tglregistrasi;
  // });

  // if (!hasSameRegistrasi) {
    await useApi().get(`/kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true`).then((response: any) => {
      if (response != null) {
        let newLab = [];
        response.detail.forEach((element: any) => {
          element.details.forEach((item: any) => {
            newLab.push(item.namaproduk);
          });
        });
        const existingLab = listPerdosi.value
          .filter((perdosi) => perdosi.tglDirawat === props.registrasi.tglregistrasi)
          .flatMap((perdosi) =>
            perdosi.namaProgram2
              ? perdosi.namaProgram2.split(', ').map((item: string) => item.trim())
              : []
          );
        // console.log(existingLab)
        newLab = newLab.filter((produk) => !existingLab.includes(produk));
        // console.log(newLab)
        lab = newLab.join(', ');
        if (newLab.length > 0) {
          const lastIndex = input.value.detaiperdosi.length - 1;
          input.value.detaiperdosi[lastIndex].namaProgram2 = ref(lab);
        }
      }
    });
  // }
};

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

const setAutoFillCpptP = async () => {
  await useApi().get(
    `/emr/get-emr-cppt?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=CatatanPerkembanganPasienTerintegrasi&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length > 0) {
        const CPPT = response[0].details[0]
        input.value.permintaanTerapi = CPPT.P
      }
    })
}
// console.log(JSON.stringify(props.pasien));

onMounted(() => {
  window.addEventListener('beforeunload', (event) => {
    event.preventDefault();
    event.returnValue = '';
  });
});

onUnmounted(() => {
  window.removeEventListener('beforeunload', (event) => {
    event.preventDefault();
    event.returnValue = '';
  });
});

onBeforeRouteLeave((to, from, next) => {
  let object: any = {};
  let isEditPerdosi = false;
  object = input.value;
  console.log(isSave.value)

  if (object.detaiperdosi.length) {
    for (let y = 0; y < object.detaiperdosi.length; y++) {
      const element2 = object.detaiperdosi[y];
      for (let z = 0; z < listPerdosi.value.length; z++) {
        const elementPerdosi = listPerdosi.value[z];
        const dokterValueMatch = elementPerdosi.namadokter2?.value !== element2.namadokter2?.value;
        const terapisValueMatch = elementPerdosi.namaterapis2?.value !== element2.namaterapis2?.value;
        const programMatch = elementPerdosi.namaProgram2 !== element2.namaProgram2;
        if (elementPerdosi.tglregistrasi2 === element2.tglregistrasi2 && (dokterValueMatch || programMatch || terapisValueMatch)) {
          isEditPerdosi = true;
        }
      }
    }
  }

  if (isEditPerdosi || object.detaiperdosi.length !== listPerdosi.value.length) {
    confirm.require({
      group: 'positionDialog',
      message: 'Anda Memiliki Kolom Perdosi yang belum disimpan. Apakah Anda yakin ingin tetap meninggalkan halaman ini?',
      header: 'Info ',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      position: 'center',
      accept: () => {
        next();
      },
      reject: () => {
        next(false);
      }
    });
  } else if (!isSave.value && (!isEditPerdosi || object.detaiperdosi.length == listPerdosi.value.length)) {
    confirm.require({
      group: 'positionDialog',
      message: 'Anda Belum Melakukan Perubahan Pada Lembar Perdosi. Apakah Anda yakin ingin tetap meninggalkan halaman ini?',
      header: 'Info ',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      position: 'center',
      accept: () => {
        next();
      },
      reject: () => {
        next(false);
      }
    });
  } else {
    next();
  }
});

setView()
setAutoFill()
setRegisPasien()
loadRiwayat()
setAutoFillDiagnosa()
setAutoFillCpptP()
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
  width: 150%;
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

