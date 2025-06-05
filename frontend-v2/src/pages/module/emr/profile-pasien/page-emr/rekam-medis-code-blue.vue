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
    <div class="columns is-multiline p-5">
      <div class="column is-12">
        <VCard>
          <Fieldset :toggleable="true" legend="MULAI CODE BLUE">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Tanggal dan Jam: </span>
                </div>
                <div class="column is-6">
                  <VField class="mt-2">
                    <VDatePicker v-model="input.tglDanJam" mode="dateTime" style="width: 100%" trim-weeks
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
                  <span> Ditemukan oleh: </span>
                </div>
                <div class="column is-6">
                  <VControl>
                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                      <div class="column is-12" v-if="d_Ditemukan.length == 0">
                        <VPlaceloadText :lines="1" />
                      </div>
                      <div class="column is-4 mt-2 p-0" v-for="items in d_Ditemukan" :key="items.id">
                        <VRadio v-model="input.ditemukanOleh" :value="items.label" class="p-0 mb-3" :label="items.label"
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
                  <span> Ditemukan oleh: </span>
                </div>
                <div class="column is-10">
                <div class="columns is-multiline is-flex">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tidakSadar" class="p-0" true-value="Tidak Sadar"
                          label="Tidak Sadar" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.sadar" class="p-0" true-value="Sadar"
                          label="Sadar" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tidakAdaPulsasiKarotis" class="p-0" true-value="Tidak ada pulsasi karotis"
                          label="Tidak ada pulsasi karotis" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.terabaPulsasi" class="p-0" true-value="Teraba pulsasi"
                          label="Teraba pulsasi" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.tidakBernafas" class="p-0" true-value="Tidak Bernafas"
                          label="Tidak Bernafas" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.bernafas" class="p-0" true-value="Bernafas"
                          label="Bernafas" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.agonal" class="p-0" true-value="Agonal"
                          label="Agonal" color="primary" circle />
                      </VControl>
                    </VField>
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.gagReflex" class="p-0" true-value="Gag Reflex"
                          label="Gag Reflex" color="primary" circle />
                      </VControl>
                    </VField>
                  </div>
                </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="RESUSITASI">
            <div class="columns is-multiline is-flex">
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.tidakDilakukanResusitasi" class="p-0" true-value="Tidak dilakukan Resusitasi"
                      label="Tidak dilakukan Resusitasi" color="primary" circle />
                  </VControl>
                </VField>
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.pemasangaAksesinVena" class="p-0" true-value="Pemasangan aksesin vena"
                      label="Pemasangan aksesin vena" color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.intubasiDanBreathing" class="p-0" true-value="Intubasi dan breathing support"
                      label="Intubasi dan breathing support" color="primary" circle />
                  </VControl>
                </VField>
                <VField label="Waktu intubasi jam">
                    <VControl>
                        <VInput type="time" class="input form-timepicker mt-2" style="width:88px !important; " placeholder=""
                            v-model="input.jamIntubasi" />
                    </VControl>
                </VField>
              </div>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.resusitasiJantungParu" class="p-0" true-value="Resusitasi Jantung Paru"
                      label="Resusitasi Jantung Paru" color="primary" circle />
                  </VControl>
                </VField>
                <VField label="Waktu mulai resusitasi jam">
                    <VControl>
                        <VInput type="time" class="input form-timepicker mt-2" style="width:88px !important; " placeholder=""
                            v-model="input.jamResusitasi" />
                    </VControl>
                </VField>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="DEFIBRILATOR">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1">
                  <thead>
                    <tr>
                      <th>Mode</th>
                      <th>Energi</th>
                      <th>Waktu</th>
                      <th>Energi</th>
                      <th>Waktu</th>
                      <th>Energi</th>
                      <th>Waktu</th>
                      <th>Energi</th>
                      <th>Waktu</th>
                      <th>Energi</th>
                      <th>Waktu</th>
                      <th>Energi</th>
                      <th>Waktu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in  defibrilator">
                      <td width="18%" style="font-weight: 600;">{{ datas.label }}</td>
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                            <VControl v-if="checkbox.button" class="field-addon-body">
                                <!-- <VButton static>{{ checkbox.button }}</VButton> -->
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'waktu'">
                            <VControl>
                                <VInput type="time" class="input form-timepicker mt-2" style="width:60px !important; " placeholder=""
                                    v-model="input[checkbox.model]" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="OBAT">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1">
                  <thead>
                    <tr>
                      <th>Nama Obat</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in obat">
                      <td width="18%" style="font-weight: 600;">{{ datas.label }}</td>
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                            <VControl v-if="checkbox.button" class="field-addon-body">
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'waktu'">
                            <VControl>
                                <VInput type="time" class="input form-timepicker mt-2" style="width:60px !important; " placeholder=""
                                    v-model="input[checkbox.model]" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="CAIRAN INTRAVENA">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1">
                  <thead>
                    <tr>
                      <th>Jenis Cairan</th>
                      <th>Tetesan</th>
                      <th>Waktu</th>
                      <th>Tetesan</th>
                      <th>Waktu</th>
                      <th>Tetesan</th>
                      <th>Waktu</th>
                      <th>Tetesan</th>
                      <th>Waktu</th>
                      <th>Tetesan</th>
                      <th>Waktu</th>
                      <th>Tetesan</th>
                      <th>Waktu</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(datas, i) in cairanIntravena">
                      <td width="18%" style="font-weight: 600;">
                        <div class="column is-12">
                          <VField v-if="datas.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:100px !important; ">
                              <VInput type="text" v-model="input[datas.model]"></VInput>
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                            <VControl v-if="checkbox.button" class="field-addon-body">
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'waktu'">
                            <VControl>
                                <VInput type="time" class="input form-timepicker mt-2" style="width:60px !important; " placeholder=""
                                    v-model="input[checkbox.model]" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="HASIL INTERVENSI">
            <div class="columns is-multiline">
              <div class="column is-12">
                <table border="1">
                  <!-- <thead>
                    <tr>
                      <th>Nama Obat</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                      <th>Dosis</th>
                      <th>Waktu</th>
                    </tr>
                  </thead> -->
                  <tbody>
                    <tr v-for="(datas, i) in hasilIntervensi">
                      <td width="18%" style="font-weight: 600;">{{ datas.label }}</td>
                      <td v-for="data in datas.children">
                        <div class="column is-12" v-for="checkbox in data">
                          <VField v-if="checkbox.type == 'input'" addons>
                            <VControl subcontrol expanded style="width:60px !important; ">
                              <VInput type="text" v-model="input[checkbox.model]"></VInput>
                            </VControl>
                            <VControl v-if="checkbox.button" class="field-addon-body">
                            </VControl>
                          </VField>
                          <VField v-if="checkbox.type == 'waktu'">
                            <VControl>
                                <VInput type="time" class="input form-timepicker mt-2" style="width:60px !important; " placeholder=""
                                    v-model="input[checkbox.model]" />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </Fieldset>
        </VCard>
        <VCard>
          <Fieldset :toggleable="true" legend="HASIL AKHIR">
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Meninggal Jam: </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                        <VInput type="time" class="input form-timepicker mt-2" style="width:88px !important; " placeholder=""
                            v-model="input.meninggalJam" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> ROSC Jam: </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                        <VInput type="time" class="input form-timepicker mt-2" style="width:88px !important; " placeholder=""
                            v-model="input.roscJam" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-2" style="margin-top:0.5rem">
                  <span> Ditransfer ke : </span>
                </div>
                <div class="column is-10">
                  <VControl>
                    <div class="columns is-multiline pt-3 pb-2 pr-5 pl-3">
                      <div class="column is-12" v-if="d_Ditransfer.length == 0">
                        <VPlaceloadText :lines="1" />
                      </div>
                      <div class="column is-4 mt-2 p-0" v-for="items in d_Ditransfer" :key="items.id">
                        <VRadio v-model="input.ruanganTransfer" :value="items.label" class="p-0 mb-3" :label="items.label"
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
                  <span> Jam : </span>
                </div>
                <div class="column is-6">
                  <VField>
                    <VControl>
                        <VInput type="time" class="input form-timepicker mt-2" style="width:88px !important; " placeholder=""
                            v-model="input.jamPindahRuangan" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <h1 style="font-weight: bold;"> Anggota tim code blue yang bertugas </h1>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-6" style="margin-top:0.5rem">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="1... " v-model="input.anggotaCodeBlue1" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-6" style="margin-top:0.5rem">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="2... " v-model="input.anggotaCodeBlue2" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-6" style="margin-top:0.5rem">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="3... " v-model="input.anggotaCodeBlue3" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column is-12 p-0">
              <div class="is-flex">
                <div class="column is-6" style="margin-top:0.5rem">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="4... " v-model="input.anggotaCodeBlue4" />
                    </VControl>
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
                      <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
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
                      <!-- <h1 class="p-0" style="font-weight: bold;">Yang Membuat Pernyataan,</h1>
                      <VField>
                          <VControl>
                              <VInput type="text" class="input" placeholder="Pasien/Penanggung Jawab Pasien"
                                  v-model="input.pasienPenanggungjawab" />
                          </VControl>
                      </VField> -->
                  </div>
              </div>
          </div>
          </div>
          </Fieldset>
        </VCard>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/rekam-medis-code-blue'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let defibrilator = ref(EMR.defibrilator())
let obat = ref(EMR.obat())
let cairanIntravena = ref(EMR.cairanIntravena())
let hasilIntervensi = ref(EMR.hasilIntervensi())

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
const d_Obat: any = ref([])
const d_Petugas: any = ref([])
const d_Dokter: any = ref([])
const d_Ditemukan: any = ref([])
const d_Ditransfer: any = ref([])
const d_Pegawai: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('rekamMedisCodeBlue') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const getUsia = props.pasien.umur.split('thn')[0]
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaTanganDpjpIGD) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganDpjpIGD)
        }
        if (response[0].tandaTanganPPJPIGD) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPPJPIGD)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.tandaTanganDpjpIGD = H.tandaTangan().get("signature_1")
  object.tandaTanganPPJPIGD = H.tandaTangan().get("signature_2")
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': 'triase-igd',
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

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}
const setTandaTangan = async (e: any, i: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_" + i, element.ttd)
    } else {
      H.tandaTangan().set("signature_" + i, '')
    }
  })
}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
  ).then((response) => {
    if (response == null) return
    input.value.anakSuhu = response.suhu
    input.value.anakBeratBadan = response.beratBadan
    input.value.anakSPO2 = response.SPO2
    input.value.frekuensiNadi = response.nadi
    input.value.frekuensiNafas = response.pernapasan
  })
  input.value.dpjpIGD = props.registrasi.dokter
  input.value.ppjpIGD = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
}

const listSiapa = [
  {value: 1, label: "Orang awam"},
  {value: 2, label: "Pekarya"},
  {value: 3, label: "Medis/paramedis"},
  {value: 4, label: "Cleaning Service"},
  {value: 5, label: "Satpam"},
  {value: 6, label: "lainnya"},
]
d_Ditemukan.value = listSiapa

const listRuangTransfer = [
  {value: 1, label: "IGD"},
  {value: 2, label: "ICU"},
  {value: 3, label: "PICU"},
  {value: 4, label: "NICU"},
  {value: 5, label: "lainnya"},
]
d_Ditransfer.value = listRuangTransfer
setView()
setAutoFill()
loadRiwayat()
</script>

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
</style>
