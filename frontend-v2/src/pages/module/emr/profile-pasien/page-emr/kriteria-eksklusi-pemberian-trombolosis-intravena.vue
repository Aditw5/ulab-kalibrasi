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

  <div class="columns is-12">
    <VCard>
      <div class="column is-12 p-0 fs-12">
        <Message severity="info" :closable="false">
          SEMUA poin checklist harus diisi 'TIDAK' pada pasien yang akan diberikan trombolisis Intravena. Jika salah
          satu poin diisi YA; pemberian trombolisis intravena dikontraindikasikan.
        </Message>
      </div>
      <div class="column is-12 p-0 fs-12 is-bold">
        <Message severity="secondary" :closable="false">
          Kontraindikasi pemberian trombolisis
        </Message>
      </div>
      <table class="tbl-kmiccu">
        <thead>
          <tr>
            <th>Trombolisis tida dapat diberikan pada pasien dengan risiko tinggi perdarahan seperti:</th>
            <th>Ya</th>
            <th>Tidak</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(data, index) in kontraindikasi">
            <td>{{ data.label }}</td>
            <td>
              <VField>
                <VControl>
                  <VRadio v-model="input[data.model + data.no]" value="ya" :name="data.model + data.no" color="primary"
                    square />
                </VControl>
              </VField>
            </td>
            <td>
              <VField>
                <VControl>
                  <VRadio v-model="input[data.model + data.no]" value="tidak" :name="data.model + data.no"
                    color="primary" square />
                </VControl>
              </VField>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="column is-12 p-0 fs-12 is-bold">
        <Message severity="secondary" :closable="false">
          Kontraindikasi tambahan
        </Message>
      </div>
      <table class="tbl-kmiccu">
        <thead>
          <tr>
            <th></th>
            <th>Ya</th>
            <th>Tidak</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(data, index) in kontraindikasiTambahan">
            <td>{{ data.label }}</td>
            <td>
              <VField>
                <VControl>
                  <VRadio v-model="input[data.model + data.no]" value="ya" :name="data.model + data.no" color="primary"
                    square />
                </VControl>
              </VField>
            </td>
            <td>
              <VField>
                <VControl>
                  <VRadio v-model="input[data.model + data.no]" value="tidak" :name="data.model + data.no"
                    color="primary" square />
                </VControl>
              </VField>
            </td>
          </tr>
        </tbody>
      </table>

      <table class="tbl-kmiccu" style="margin-top: 30px;">
        <tr>
          <td colspan="3" class="text-center">DOKTER PENANGGUNG JAWAB PASIEN</td>
        </tr>
        <tr>
          <td>
            <div class="column is-12 text-center">Tanggal & Jam: </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalVerif" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </div>
          </td>
          <td>
            <div class="column is-12 text-center">Dokter Jaga IGD: </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dokterJaga" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP" class="mt-2"
                    @item-select="setTandaTangan($event, 'signature_1')" />
                </VControl>
              </VField>
            </div>
            <div class="column is-12 text-center">Nama Dokter Neurologi (DPJP) yang dihubungi: </div>
            <div class="column is-12">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.dpjp" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                    :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP" class="mt-2"
                    @item-select="setTandaTangan($event, 'signature_1')" />
                </VControl>
              </VField>
            </div>
          </td>
          <td>
            <div class="column is-12 text-center">Tanda Tangan </div>
            <div class="column is-12">
              <img v-if="input.dokterJaga"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokterJaga.label ? input.dokterJaga.label : input.dokterJaga)">
            </div>
          </td>
        </tr>
      </table>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useHead } from '@vueuse/head'
import { useWindowScroll } from '@vueuse/core'
import { useUserSession } from '/@src/stores/userSession'
import { useApi } from '/@src/composable/useApi'
import Calendar from 'primevue/calendar'
import AutoComplete from 'primevue/autocomplete';
import Message from 'primevue/message';
import * as EMR from '../page-emr-plugins/kriteria-eksklusi-pemberian-trombolisis-intravena'

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

const kontraindikasi = ref(EMR.kontraindikasi())
const kontraindikasiTambahan = ref(EMR.kontraindikasiTambahan())
const input: any = ref({})

const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("KriteriaEksklusiPemberianTrombolisisIntravena") //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const { y } = useWindowScroll()
const d_Dokter: any = ref([])
const isLoading: any = ref(false)

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}
const isStuck = computed(() => { return y.value >= 20 })

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}
  input.value.tanggalMRS = H.formatDate(new Date(input.value.tanggalMRS), 'YYYY-MM-DD HH:mm')
  input.value.tanggalKICCU = H.formatDate(new Date(input.value.tanggalKICCU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalMICCU = H.formatDate(new Date(input.value.tanggalMICCU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalVerif = H.formatDate(new Date(input.value.tanggalVerif), 'YYYY-MM-DD HH:mm')
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

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const setAutoFill = () => {
  input.value.dokterJaga = props.registrasi.dokter
  input.value.tanggalVerif = H.formatDate(new Date(), 'YYYY-MM-DD HH:mm')
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

setAutoFill()
setView()
loadRiwayat()
</script>
<style scoped lang="scss">
.tbl-kmiccu {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
}

.tbl-kmiccu tr,
.tbl-kmiccu th,
.tbl-kmiccu td {
  border: 1px solid black;
  padding: 5px;
}
</style>
