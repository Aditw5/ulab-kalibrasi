<template>
  <div class="form-layout is-stacked-2">
      <div class="form-outer" style="margin-top:15px">
          <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
              <div class="form-header-inner">
                  <div class="left">
                      <h3> {{ props.FORM_NAME }}</h3>
                  </div>
                  <div class="right">
                      <div class="buttons">
                          <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                              Kembali
                          </VButton>
                          <VButton type="button" rounded outlined :disabled="!NOREC_EMRPASIEN ? true : false" color="warning" raised icon="lnir lnir-printer"
                              @click="print()"> Cetak
                          </VButton>
                          <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                              :loading="isLoading" @click="simpan()"> Simpan
                          </VButton>
                      </div>
                  </div>
              </div>
          </div>


      </div>
  </div>
  <div class="columns is-multiline p-2">
      <div class="column is-12">
          <VCard>
              <div class="columns is-multiline">
                  <div class="column is-12">
                      <div class="column is-12" style="overflow-x:auto;">
                          <table class="tc">
                              <thead>
                                  <tr>
                                      <td class="tg-0lax text-center" colspan="1">Tanggal/Jam</td>
                                      <td class="tg-0lax text-center" colspan="1">Catatan Partus</td>
                                      <td class="tg-0lax text-center" colspan="2">#</td>
                                  </tr>
                              </thead>
                              <tbody v-for="(item, index) in input.details" :key="index">
                                  <tr>
                                      <td>
                                        <VDatePicker class="pt-3" v-model="item.tglDibuat" color="green" mode="datetime">
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField>
                                                    <VControl>
                                                        <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                      </td>

                                      <td>
                                          <VField class="pt-4">
                                              <VControl>
                                                  <VInput type="text" v-model="item.kantong" placeholder="" />
                                              </VControl>
                                          </VField>
                                      </td>
                                      <td rowspan="2" style="width:7%;vertical-align: middle;">
                                          <div class="columns is-multiline">
                                              <div class="column is-6">
                                                  <VIconButton type="button" raised circle icon="feather:plus"
                                                      @click="addNewItem()" color="info" v-tooltip.bubble="'Tambah '">
                                                  </VIconButton>
                                              </div>
                                              <div class="column is-6 ml-3-min">
                                                  <VIconButton v-if="index > 0" type="button" raised circle
                                                      icon="feather:trash" @click="removeItem(index)" color="danger">
                                                  </VIconButton>
                                              </div>
                                          </div>
                                      </td>
                                  </tr>
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </VCard>
      </div>

      <div class="column is-12">
        <Fieldset :toggleable="true" legend="Pemantauan Kala IV">
          <div class="column is-4">
            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Pemantauan dilakukan pada jam </h1>
            <VField>
              <VDatePicker v-model="input.tglPemantauan" mode="dateTime" style="width: 100%" trim-weeks
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
          <div class="column is-12">
              <h1 style="font-weight: bold; margin-bottom: 1rem;">Keadaan Umum</h1>
              <VField>
                  <VControl>
                      <VTextarea v-model="input.keadaanUmum" rows="3">
                      </VTextarea>
                  </VControl>
              </VField>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tanda Vital</h1>
            <div class="columns is-multiline p-3">
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Tekanan Darah</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Tekanan Darah"
                                v-model="input.tekananDarah" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>mm/Hg</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Nadi</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Nadi"
                                v-model="input.frekuensiNadi" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>x/menit</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Respirasi</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Respirasi"
                                v-model="input.respirasi" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>x/menit</VButton>
                        </VControl>
                    </VField>
                </div>
                <div class="column is-3">
                    <h1 style="font-weight: bold; margin-bottom: 1rem;">Suhu</h1>
                    <VField addons>
                        <VControl expanded>
                            <VInput type="text" class="input" placeholder="Suhu"
                                v-model="input.suhu" />
                        </VControl>
                        <VControl class="field-addon-body">
                            <VButton static>C</VButton>
                        </VControl>
                    </VField>
                </div>
            </div>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Tinggi Fundus Uteri</h1>
            <VField>
                <VControl>
                    <VTextarea v-model="input.tinggiFundus" rows="2">
                    </VTextarea>
                </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Kontraksi Uterus</h1>
            <VField>
                <VControl>
                    <VTextarea v-model="input.kontraksiUterus" rows="2">
                    </VTextarea>
                </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Kandung Kemih</h1>
            <VField>
                <VControl>
                    <VTextarea v-model="input.kandungKemih" rows="2">
                    </VTextarea>
                </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <h1 style="font-weight: bold; margin-bottom: 1rem;">Pengeluaran Pervaginaan</h1>
            <VField>
                <VControl>
                    <VTextarea v-model="input.pengeluaranPervaginaan" rows="2">
                    </VTextarea>
                </VControl>
            </VField>
          </div>

          <div clas="mt-5">
        <div class="column is-4">
            <h1 style="font-weight: bold;"> Cirebon, Tanggal </h1>
            <VField class="mt-3">
                <VDatePicker v-model="input.tglPembuatan" mode="dateTime" style="width: 100%" trim-weeks
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
        <div class="column is-12">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <img v-if="input.dpjpPasien"
                        :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjpPasien.label ? input.dpjpPasien.label : input.dpjpPasien)">
                    <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
                </div>
                <div class="column is-6">

                </div>
                <div class="column is-4">
                    <h1 class="p-0" style="font-weight: bold;">Bidan</h1>
                    <VField>
                        <VControl class="prime-auto">
                            <AutoComplete v-model="input.dpjpPasien" :suggestions="d_Dokter"
                                @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                :field="'label'" placeholder="DPJP IGG..." class="mt-2"
                                @item-select="setTandaTangan($event, 'signature_1')" />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-4 is-offset-2">
                  <h1 class="p-0" style="font-weight: bold;">Keluarga</h1>
                    <VField>
                      <VControl expanded>
                            <VInput type="text" class="input" placeholder="Pasien/keluarga"
                                v-model="input.Keluarga" />
                        </VControl>
                    </VField>
                </div>
            </div>
        </div>
    </div>
        </Fieldset>
      </div>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed} from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import AutoComplete from 'primevue/autocomplete';
import Calendar from 'primevue/calendar';
import Fieldset from 'primevue/fieldset';

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
  defineProps<{
      pasien?: any
      registrasi?: any
      FORM_NAME?: string
      FORM_URL?: string
  }>(),
  {
      pasien: {},
      registrasi: {},
      FORM_NAME: '',
      FORM_URL: '',
  }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
const isLoading: any = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('LaporanPartus') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const input: any = ref({
  details: [{
      no: 1,
      tglDIbuat: new Date(),
  }]
})
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
const fetchDokter = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    ).then((response) => {
        d_Dokter.value = response
    })
}
const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}
const kembaliKeun = () => {
  window.history.back()
}
const addNewItem = () => {
  input.value.details.push({
      no: input.value.details.length > 0
          ? input.value.details[input.value.details.length - 1].no + 1 : 1,
      tglDibuat: new Date(),
  })
}

const removeItem = (index: any) => {
  input.value.details.splice(index, 1)
}

const print = async () => {
  H.printBlade(`emr/cetak/${COLLECTION.value}?emrpasienfk=${NOREC_EMRPASIEN.value}`)
}

const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  input.value.dpjpPasien = props.registrasi.dokter
}

setView()
loadRiwayat()
setAutoFill()
</script>
<style lang="scss">
.tc {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100% !important;
  overflow-x: scroll;
}

.tc td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  min-width: 200px;
}

.tc th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  min-width: 200px;
}

.tc .tg-0lax {
  text-align: left;
  vertical-align: top
}
</style>

