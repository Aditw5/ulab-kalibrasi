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
          <VSnack title="Dirawat seorang DPJP" white color="success" icon="fas fa-stethoscope">
          </VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center">SMF</th>
                      <th class="tg-0lax text-center">Dokter</th>
                      <th class="tg-0lax text-center">Paraf</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.smf" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="input.dpjp1" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <img v-if="input.dpjp1"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjp1 ? input.dpjp1.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.parafdpjp1" class="input" />
                          </VControl>
                        </VField> -->
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

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VSnack title="Dirawat bersama ≥ DPJP" white color="warning" icon="fas fa-stethoscope"></VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th></th>
                      <th class="tg-0lax text-center" rowspan="2">Jenis DPJP</th>
                      <th class="tg-0lax text-center" rowspan="2">SMF</th>
                      <th class="tg-0lax text-center" rowspan="2">Nama Dokter</th>
                      <th class="tg-0lax text-center" rowspan="2">Paraf</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.dpjpbanyak" :key="index">
                      <td width="3px" class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewDPJP()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removeDPJP(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="data.jenisdpjpbanyak" :suggestions="d_Jenis"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="data.smfdpjpbanyak" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="data.dpjpbanyak" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td width="150px">
                        <img v-if="data.dpjpbanyak"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.dpjpbanyak ? data.dpjpbanyak.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VTextarea v-model="data.namaProgram2" rows="3" placeholder="Program"></VTextarea>
                          </VControl>
                        </VField> -->
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

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VSnack title="DPJP Ruang Intensif" white color="danger" icon="fas fa-stethoscope">
          </VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center">SMF</th>
                      <th class="tg-0lax text-center">Dokter</th>
                      <th class="tg-0lax text-center">Paraf</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.smfintensif" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="input.dpjpintensif" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <img v-if="input.dpjpintensif"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpintensif ? input.dpjpintensif.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.parafdpjp1" class="input" />
                          </VControl>
                        </VField> -->
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

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VSnack title="DPJP Kamar Operasi" white color="danger" icon="fas fa-stethoscope">
          </VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th class="tg-0lax text-center">SMF</th>
                      <th class="tg-0lax text-center">Dokter</th>
                      <th class="tg-0lax text-center">Paraf</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.smfoperator" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <span>Operator : </span>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="input.dpjpoperator" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <img v-if="input.dpjpoperator"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpoperator ? input.dpjpoperator.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.parafdpjp1" class="input" />
                          </VControl>
                        </VField> -->
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.smfanestesi" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <span>Anestesi : </span>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="input.dpjpanestesi" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <img v-if="input.dpjpanestesi"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpanestesi ? input.dpjpanestesi.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.parafdpjp1" class="input" />
                          </VControl>
                        </VField> -->
                      </td>
                    </tr>
                    <tr>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.smflain" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <span>DPJP Lain : </span>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="input.dpjplain" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <img v-if="input.dpjplain"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjplain ? input.dpjplain.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="input.parafdpjp1" class="input" />
                          </VControl>
                        </VField> -->
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

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VSnack title="Perubahan DPJP" white color="warning" icon="feather:alert-octagon"></VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th rowspan="2"></th>
                      <th class="tg-0lax text-center" rowspan="2">Tanggal</th>
                      <th class="tg-0lax text-center" rowspan="2">DPJP Semula</th>
                      <th class="tg-0lax text-center" colspan="2">DPJP Pengganti</th>
                      <th class="tg-0lax text-center" rowspan="2">Paraf</th>
                    </tr>
                    <tr>
                      <th>Nama Dokter</th>
                      <th>SMF</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.perubahandpjp" :key="index">
                      <td width="3px" class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewPerubahanDPJP()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removePerubahanDPJP(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                      <td>
                        <VField class="mt-2">
                          <VDatePicker v-model="data.tglperubahandpjp" mode="dateTime" style="width: 100%" trim-weeks
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
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="data.dpjpsemula" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="data.dpjppengganti" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="data.smfdpjppengganti" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td width="150px">
                        <img v-if="data.dpjppengganti"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.dpjppengganti ? data.dpjppengganti.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VTextarea v-model="data.namaProgram2" rows="3" placeholder="Program"></VTextarea>
                          </VControl>
                        </VField> -->
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

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <VSnack title="Perubahan DPJP Tambahan" white color="warning" icon="feather:alert-octagon"></VSnack>
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column" style="overflow: auto;">
                <table class="tg">
                  <thead>
                    <tr>
                      <th rowspan="2"></th>
                      <th class="tg-0lax text-center" rowspan="2">Tanggal</th>
                      <th class="tg-0lax text-center" rowspan="2">DPJP Semula</th>
                      <th class="tg-0lax text-center" colspan="2">DPJP Pengganti</th>
                      <th class="tg-0lax text-center" rowspan="2">Paraf</th>
                    </tr>
                    <tr>
                      <th>Nama Dokter</th>
                      <th>SMF</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(data, index) in input.perubahandpjptambahan" :key="index">
                      <td width="3px" class="td-fkprj" style="vertical-align: inherit;">
                        <div class="column">
                          <VIconButton type="button" raised circle icon="feather:plus" @click="addNewPerubahanDPJPTambahan()"
                            color="info" v-tooltip.bubble="'Tambah '">
                          </VIconButton>
                          <VIconButton class="mt-1" v-if="index > 0" type="button" raised circle icon="feather:trash"
                            @click="removePerubahanDPJPTambahan(index)" color="danger">
                          </VIconButton>
                        </div>
                      </td>
                      <td>
                        <VField class="mt-2">
                          <VDatePicker v-model="data.tglperubahandpjp" mode="dateTime" style="width: 100%" trim-weeks
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
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="data.dpjpsemula" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <AutoComplete v-model="data.dpjppengganti" :suggestions="d_Dokter"
                              @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                              :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                              placeholder="Cari Data..." />
                          </VControl>
                        </VField>
                      </td>
                      <td>
                        <VField style="padding:0px 10px;">
                          <VControl>
                            <VInput v-model="data.smfdpjppengganti" class="input" />
                          </VControl>
                        </VField>
                      </td>
                      <td width="150px">
                        <img v-if="data.dpjppengganti"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (data.dpjppengganti ? data.dpjppengganti.label : '-')">
                        <!-- <VField style="padding:0px 10px;">
                          <VControl>
                            <VTextarea v-model="data.namaProgram2" rows="3" placeholder="Program"></VTextarea>
                          </VControl>
                        </VField> -->
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
const COLLECTION: any = ref("FormulirDPJP") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  dpjpbanyak: [{ no: 1 }],
  perubahandpjp: [{ no: 1 }],
  perubahandpjptambahan: [{ no: 1 }],
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

const addNewDPJP = () => {
  input.value.dpjpbanyak.push({
    no: input.value.dpjpbanyak[input.value.dpjpbanyak.length - 1].no + 1,
  });
  setAutoFill()
}
const removeDPJP = (index: any) => {
  input.value.dpjpbanyak.splice(index, 1)
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
  fetchJenisKelamin()
  input.value.tglPembuatan = new Date()
  if (useUserSession().getUser().kelompokUser.kelompokUser == 'dokter') {
    input.dpjp1 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
    input.value.parafdpjp1 = useUserSession().getUser().pegawai.namaLengkap
  } else {
    input.value.dpjp1 = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
    input.value.parafdpjp1 = props.registrasi.dokter
  }

  if (input.value.dpjpbanyak) {
    input.value.dpjpbanyak.forEach((data, index) => {
      if (index == 0) {
        data.jenisdpjpbanyak = { id: 1, label: 'DPJP Utama' }
        data.dpjpbanyak = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
      } else {
        data.jenisdpjpbanyak = { id: 2, label: 'DPJP Tambahan' }
      }
    })
  }
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
