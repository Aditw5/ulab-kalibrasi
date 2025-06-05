<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
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
    <div class="column">
      <Fieldset :toggleable="true" legend="Status Anestesi / Sedasi">
        <div class="column">
          <div class="columns is-multiline">
            <div class="column is-4">
              <span class="label-apas">Tanggal Dan Jam</span>
              <VField class="column is-10 p-0 mt-3">
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
            <div class="column is-4">
              <span class="label-apas">Instrumen</span>
              <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.instrumen" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span class="label-apas">DPJP</span>
              <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" disabled
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4">
              <span class="label-apas">Dokter Anestesi</span>
              <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.dokterAnestesi" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span class="label-apas">Dokter Bedah Asisten</span>
              <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.dokterBedahAsisten" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
            <div class="column is-4">
              <span class="label-apas">Asisten</span>
              <VField class="is-rounded-select_Z  is-autocomplete-select pt-3" v-slot="{ id }">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="input.asisten" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="columns is-multiline">
            <div class="column is-4" v-for="(data) in statusAnestesi">
              <span class="label-apas">{{ data.label }}</span>
              <VField class="pt-3">
                <VControl>
                  <VTextarea v-model="input[data.model]" rows="2" :placeholder="data.label">
                  </VTextarea>
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column">
      <Fieldset :toggleable="true" legend="Assesment pra anestesi / sedasi">
        <div class="columns is-multiline p-3">
          <div class="column is-4" v-for="(datas) in asesmenSedasi">
            <div class="column p-0">
              <span class="label-apas">{{ datas.label_1 }}</span>
              <div class="columns is-multiline p-3">
                <div class="column mt-3" v-for="(data) in datas.value_1"
                  :class="data.type == 'textBox' ? 'is-12 pt-0' : 'pt-0 pb-0'">
                  <VField v-if="data.type == 'textarea'" class="pb-5">
                    <VControl>
                      <VTextarea v-model="input[data.model]" :placeholder="datas.label_1" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                  <VField v-else-if="data.type == 'checkBox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                        class="p-0" square />
                    </VControl>
                  </VField>
                  <VField v-else-if="data.type == 'textBox'">
                    <VControl>
                      <VInput type="text" v-model="input[datas.model]" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column p-0">
              <span class="label-apas">{{ datas.label_2 }}</span>
              <div class="columns is-multiline">
                <div class="column" v-for="(data) in datas.value_2">
                  <VField v-if="data.type == 'checkBox'">
                    <VControl raw subcontrol>
                      <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                        square />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
            <div class="column p-0">
              <span class="label-apas">{{ datas.label_3 }}</span>
              <div class="columns is-multiline">
                <div class="column" v-for="(data) in datas.value_3">
                  <VField v-if="data.type == 'textarea'" class="pt-3">
                    <VControl>
                      <VTextarea v-model="input[data.model]" :placeholder="datas.label_3" rows="2">
                      </VTextarea>
                    </VControl>
                  </VField>
                  <VField v-else>
                    <VControl>
                      <VInput type="text" v-model="input[data.model]" />
                    </VControl>
                  </VField>
                </div>
              </div>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="Pemeriksaan fisik dan penunjang">
        <div class="column">
          <span class="label-apas">KU dan Kesadaran</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.kuDanKesadaran" />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline p-3">
          <div class="column is-4" v-for="(data) in fisikDanPenunjang">
            <span class="label-apas">{{ data.label }}</span>
            <VField class="pt-3">
              <VControl>
                <VInput type="text" v-model="input[data.model]" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column mt-3">
          <span class="label-apas">Pemeriksaan Penunjang</span>
        </div>
        <div class="columns is-multiline pl-5 pr-5">
          <div class="column is-6" v-for="(data) in pemrksPenunjang">
            <span class="label-apas">{{ data.label }}</span>
            <VField class="pt-3">
              <VControl>
                <VTextarea v-model="input[data.model]" :placeholder="data.label" rows="2">
                </VTextarea>
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column">
          <span class="label-apas">Analisa Pemeriksaan Fisik</span>
          <div class="columns is-multiline">
            <div class="column" v-for="(data) in analisaFisik">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                    square />
                </VControl>
              </VField>
            </div>
          </div>
        </div>

        <div class="column">
          <span class="label-apas">Analisa Pemeriksaan Fisik</span>
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="input.ketAnalisaFisik" />
            </VControl>
          </VField>
        </div>

        <div class="column">
          <span class="label-apas">Informed Consent</span>
          <div class="columns is-multiline">
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.informedConsent" true-value="Ya" label="Ya" color="primary" square />
                </VControl>
              </VField>
            </div>
            <div class="column is-2">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.informedConsent" true-value="Tidak" label="Tidak" color="primary" square />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>
    <div class="column">
      <Fieldset :toggleable="true" legend="Rencana teknik anestesi">
        <div class="column" v-for="(data) in rencanaAnestesi">
          <span class="label-apas">{{ data.title }}</span>
          <div class="columns is-multiline p-3">
            <div class="column is-3" v-for="(pilihan) in data.detail">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[pilihan.model]" :true-value="pilihan.label" :label="pilihan.label"
                    color="primary" class="p-0" square />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <Fieldset :toggleable="true" legend="Teknik & Alat Khusus">
        <div class="columns is-multiline p-4">
          <div class="column is-3" v-for="(data) in alatKhusus">
            <VField>
              <VControl raw subcontrol>
                <VCheckbox v-model="input[data.model]" :true-value="data.label" :label="data.label" color="primary"
                  class="p-0" square />
              </VControl>
            </VField>
          </div>
        </div>
      </Fieldset>
    </div>

    <div class="column">
      <VCard>
        <div class="column is-12">
          <div class="columns is-multiline">
            <div class="column is-6">
              <div class="columns is-multiline pl-3 pr-3">
                <div class="column pb-0">
                  <span class="label-apas">Makan Terkhir,Jam</span>
                  <VDatePicker class="column is-7 p-0 mt-3" v-model="input.makanTerakhir" color="green" mode="time"
                    is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl>
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
                <div class="column">
                  <span class="label-apas">Minum Terkhir,Jam</span>
                  <VDatePicker class="column is-7 p-0 mt-3" v-model="input.minumTerakhir" color="green" mode="time"
                    is24hr>
                    <template #default="{ inputValue, inputEvents }">
                      <VField>
                        <VControl>
                          <VInput class="input form-timepicker" :value="inputValue" v-on="inputEvents" />
                        </VControl>
                      </VField>
                    </template>
                  </VDatePicker>
                </div>
              </div>
              <div class="column is-8 pt-0">
                <span class="label-apas">Kebutuhan Darah</span>
                <VField class="pt-3">
                  <VControl>
                    <VInput type="text" v-model="input.kebutuhanDarah" />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <span class="label-apas">Kebutuhan Ruang ICU</span>
                <div class="columns is-multiline p-3">
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kebutuhanICU" true-value="Ya" label="Ya" color="primary" class="p-0"
                          square />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column">
                    <VField>
                      <VControl raw subcontrol>
                        <VCheckbox v-model="input.kebutuhanICU" true-value="Tidak" label="Tidak" color="primary"
                          class="p-0" square />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="column is-6">
              <div class="column is-5">
                <span class="label-apas">Bandung</span>
                <VDatePicker v-model="input.tglDibuat" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField class="pt-3">
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>

              <div class="column is-7" style="text-align: center;">
                <TandaTangan :elemenID="'signatureDokter'" :width="'180'" :height="'180'" class="dek" />
              </div>
              <div class="column is-7">
                <span class="label-apas">Dokter</span>
                <VField class="pt-3">
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                      placeholder="Cari Dokter..." />
                  </VControl>
                </VField>
              </div>
            </div>

          </div>
        </div>
      </VCard>
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
import * as EMR from '../page-emr-plugins/assesment-pra-anestesi-sedasi'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

let statusAnestesi = ref(EMR.statusAnestesi())
let asesmenSedasi = ref(EMR.asesmenSedasi())
let fisikDanPenunjang = ref(EMR.fisikDanPenunjang())
let pemrksPenunjang = ref(EMR.pemrksPenunjang())
let analisaFisik = ref(EMR.analisaFisik())
let rencanaAnestesi = ref(EMR.rencanaAnestesi())
let alatKhusus = ref(EMR.alatKhusus())

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
const d_Dokter: any = ref([])
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
  tglDanJam : new Date(),
  makanTerakhir: new Date(),
  minumTerakhir: new Date(),
  tglDibuat: new Date()
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

const setTandaTanganDokter = async (e: any) => {
    await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element)=>{
      if(element){
        H.tandaTangan().set("signatureDokter", element.ttd)
      }else{
        H.tandaTangan().set("signatureDokter", '')
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


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.dpjp = props.registrasi.dokter
  await useApi().get(
        "emr/auto-fill?nocmfk=" + ID_PASIEN +
        "&collection=VitalSign" +
        "&field=beratBadan,tinggiBadan,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,SPO2"
    ).then((response) => {

        input.value.beratBadan = response.beratBadan
        input.value.tinggiBadan = response.tinggiBadan
        input.value.lingkarPerut = response.lingkarPerut
        input.value.tekananDarah = response.tekananDarah
        input.value.pernapasan = response.pernapasan
        input.value.suhu = response.suhu
        input.value.nadi = response.nadi
        input.value.spo2 = response.SPO2
    })
}
setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">

.label-apas{
  font-weight: 500;
}

.p-fieldset-legend {
  margin-left: 15px;
}
</style>
