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
  
    <div class="columns is-12">
      <VCard>
        <table class="tbl-kmiccu">
          <thead>
            <tr>
              <th rowspan="3">No</th>
              <th rowspan="3">Parameter yang Dinilai</th>
              <th rowspan="3">Skala</th>
              <th colspan="5">Skor</th>
            </tr>
            <tr>
              <th>Tanggal</th>
              <th>Tanggal</th>
              <th>Tanggal</th>
              <th>Tanggal</th>
              <th>Tanggal</th>
            </tr>
            <tr>
              <th>
                <VField>
                    <VDatePicker v-model="input.tglPemeriksaan1" mode="dateTime" style="width: 100%" trim-weeks
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
                </th>
              <th>
                <VField>
                    <VDatePicker v-model="input.tglPemeriksaan2" mode="dateTime" style="width: 100%" trim-weeks
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
              </th>
              <th>
                <VField>
                    <VDatePicker v-model="input.tglPemeriksaan3" mode="dateTime" style="width: 100%" trim-weeks
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
              </th>
              <th>
                <VField>
                    <VDatePicker v-model="input.tglPemeriksaan4" mode="dateTime" style="width: 100%" trim-weeks
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
              </th>
              <th>
                <VField>
                    <VDatePicker v-model="input.tglPemeriksaan5" mode="dateTime" style="width: 100%" trim-weeks
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
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(data, index) in parameter">
              <template v-if="data.special">
                <td colspan="5" align="center">
                  <span v-html="data.content"></span>
                </td>
              </template>
              <template v-if="!data.special">
                <td>{{ data.no }}</td>
                <td>{{ data.parameter }}</td>
                <td>
                  <span v-html="data.skala"></span>
                  <VField v-if="data.inputTambahan">
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Penjelasan" class="is-rounded"
                        v-model="input['penjelasan' + data.model]" />
                    </VControl>
                  </VField>
                </td>
                
                <td>
                  <VField label="Kanan :" v-if="data.kiriKanan" />
                  <VField>
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '1' + (data.kiriKanan ? 'Kanan' : '')]" />
                    </VControl>
                  </VField>
                  <VField label="Kiri :" v-if="data.kiriKanan" />
                  <VField v-if="data.kiriKanan">
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '1' + (data.kiriKanan ? 'Kiri' : '')]" />
                    </VControl>
                  </VField>
                </td>

                <td>
                  <VField label="Kanan :" v-if="data.kiriKanan" />
                  <VField>
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '2' + (data.kiriKanan ? 'Kanan' : '')]" />
                    </VControl>
                  </VField>
                  <VField label="Kiri :" v-if="data.kiriKanan" />
                  <VField v-if="data.kiriKanan">
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '2' + (data.kiriKanan ? 'Kiri' : '')]" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField label="Kanan :" v-if="data.kiriKanan" />
                  <VField>
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '3' + (data.kiriKanan ? 'Kanan' : '')]" />
                    </VControl>
                  </VField>
                  <VField label="Kiri :" v-if="data.kiriKanan" />
                  <VField v-if="data.kiriKanan">
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '3' + (data.kiriKanan ? 'Kiri' : '')]" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField label="Kanan :" v-if="data.kiriKanan" />
                  <VField>
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '4' + (data.kiriKanan ? 'Kanan' : '')]" />
                    </VControl>
                  </VField>
                  <VField label="Kiri :" v-if="data.kiriKanan" />
                  <VField v-if="data.kiriKanan">
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '4' + (data.kiriKanan ? 'Kiri' : '')]" />
                    </VControl>
                  </VField>
                </td>
                <td>
                  <VField label="Kanan :" v-if="data.kiriKanan" />
                  <VField>
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '5' + (data.kiriKanan ? 'Kanan' : '')]" />
                    </VControl>
                  </VField>
                  <VField label="Kiri :" v-if="data.kiriKanan" />
                  <VField v-if="data.kiriKanan">
                    <VControl class="mt-2">
                      <VInput type="text" placeholder="Skor. . ." class="is-rounded"
                        v-model="input[data.model + '5' + (data.kiriKanan ? 'Kiri' : '')]" />
                    </VControl>
                  </VField>
                </td>
              </template>
            </tr>
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3">Jumlah Skala</th>
              <th>{{ totalSkore1 }}</th>
              <th>{{ totalSkore2 }}</th>
              <th>{{ totalSkore3 }}</th>
              <th>{{ totalSkore4 }}</th>
              <th>{{ totalSkore5 }}</th>
            </tr>
          </tfoot>
        </table>
        <div class="column is-12 p-0 fs-12">
            <Message severity="contrast" :closable="false">
                <table>
                <tr>
                    <td colspan="3">Keterangan</td>
                </tr>
                <tr>
                    <td> &lt;5 </td>
                    <td> : </td>
                    <td> Defisit Neurologis ringan </td>
                </tr>
                <tr>
                    <td> 6-14 </td>
                    <td> : </td>
                    <td> Defisit Neurologis sedang </td>
                </tr>
                <tr>
                    <td> 15-24 </td>
                    <td> : </td>
                    <td> Defisit Neurologis </td>
                </tr>
                <tr>
                    <td> &gt;24 </td>
                    <td> : </td>
                    <td> Defisit Neurologis sangat berat</td>
                </tr>
                </table>
            </Message>
        </div>
        <table class="tbl-kmiccu">
            <thead>
                <tr>
                    <td colspan="5" align="center">
                        <span><img src="/images/simrs/nihss1.png"></span>
                    </td>
                    <td colspan="5" align="center">
                        <span><img src="/images/simrs/nihss2.png"></span>
                    </td>
                    <td colspan="5" align="center">
                        <span>
                            Anda tahu kenapa <br>Jatuh ke bumi<br>Saya pulang dari kerja <br>Dekat meja di ruang makan<br>Mereka mendengar dia siaran di radio tadi malam
                        </span>
                    </td>
                </tr>
            </thead>
        </table>
      </VCard>
    </div>
</template>
  
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { h, reactive, ref, computed, defineComponent, watch, onMounted, watchEffect } from 'vue'
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
import * as EMR from '../page-emr-plugins/the-national-institute-of-health-stroke-scale-2'

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

const parameter = ref(EMR.parameter())
const gambar = ref(EMR.gambar())
const input: any = ref({})

const item: any = reactive({
NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
NOREC_APD: '',
registrasi: {},
pegawaiOrder: useUserSession().getUser().id,
selectedMenu: [false]
})
const COLLECTION: any = ref("TheNationalInstituteofHealthStrokeScale2") //table mongodb
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
    if (response.length > 0) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
        NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
    }
    })
}

// Computed property to calculate the total score
const totalSkore1 = computed(() => {
    return (parseInt(input.value.nihssTingkatKesadaran1, 10) || 0) + 
            (parseInt(input.value.nihssMenjawabPertanyaan1, 10) || 0) + 
            (parseInt(input.value.nihssMengikutiPerintah1, 10) || 0) + 
            (parseInt(input.value.nihssGaze1, 10) || 0)  +
            (parseInt(input.value.nihssVisual1, 10) || 0)  +
            (parseInt(input.value.nihssParesisWajah1, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan1Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan1Kiri, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai1Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai1Kiri, 10) || 0)  +
            (parseInt(input.value.nihssAtaksiaAnggotaGerak1, 10) || 0)  +
            (parseInt(input.value.nihssSensorik1, 10) || 0)  +
            (parseInt(input.value.nihssBahasaTerbaik1, 10) || 0)  +
            (parseInt(input.value.nihssDisatria1, 10) || 0)  +
            (parseInt(input.value.nihssPengabaianInatensi1, 10) || 0)
})
const totalSkore2 = computed(() => {
    return (parseInt(input.value.nihssTingkatKesadaran2, 10) || 0) + 
            (parseInt(input.value.nihssMenjawabPertanyaan2, 10) || 0) + 
            (parseInt(input.value.nihssMengikutiPerintah2, 10) || 0) + 
            (parseInt(input.value.nihssGaze2, 10) || 0)  +
            (parseInt(input.value.nihssVisual2, 10) || 0)  +
            (parseInt(input.value.nihssParesisWajah2, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan2Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan2Kiri, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai2Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai2Kiri, 10) || 0)  +
            (parseInt(input.value.nihssAtaksiaAnggotaGerak2, 10) || 0)  +
            (parseInt(input.value.nihssSensorik2, 10) || 0)  +
            (parseInt(input.value.nihssBahasaTerbaik2, 10) || 0)  +
            (parseInt(input.value.nihssDisatria2, 10) || 0)  +
            (parseInt(input.value.nihssPengabaianInatensi2, 10) || 0)
})
const totalSkore3 = computed(() => {
    return (parseInt(input.value.nihssTingkatKesadaran3, 10) || 0) + 
            (parseInt(input.value.nihssMenjawabPertanyaan3, 10) || 0) + 
            (parseInt(input.value.nihssMengikutiPerintah3, 10) || 0) + 
            (parseInt(input.value.nihssGaze3, 10) || 0)  +
            (parseInt(input.value.nihssVisual3, 10) || 0)  +
            (parseInt(input.value.nihssParesisWajah3, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan3Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan3Kiri, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai3Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai3Kiri, 10) || 0)  +
            (parseInt(input.value.nihssAtaksiaAnggotaGerak3, 10) || 0)  +
            (parseInt(input.value.nihssSensorik3, 10) || 0)  +
            (parseInt(input.value.nihssBahasaTerbaik3, 10) || 0)  +
            (parseInt(input.value.nihssDisatria3, 10) || 0)  +
            (parseInt(input.value.nihssPengabaianInatensi3, 10) || 0)
})
const totalSkore4 = computed(() => {
    return (parseInt(input.value.nihssTingkatKesadaran4, 10) || 0) + 
            (parseInt(input.value.nihssMenjawabPertanyaan4, 10) || 0) + 
            (parseInt(input.value.nihssMengikutiPerintah4, 10) || 0) + 
            (parseInt(input.value.nihssGaze4, 10) || 0)  +
            (parseInt(input.value.nihssVisual4, 10) || 0)  +
            (parseInt(input.value.nihssParesisWajah4, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan4Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan4Kiri, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai4Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai4Kiri, 10) || 0)  +
            (parseInt(input.value.nihssAtaksiaAnggotaGerak4, 10) || 0)  +
            (parseInt(input.value.nihssSensorik4, 10) || 0)  +
            (parseInt(input.value.nihssBahasaTerbaik4, 10) || 0)  +
            (parseInt(input.value.nihssDisatria4, 10) || 0)  +
            (parseInt(input.value.nihssPengabaianInatensi4, 10) || 0)
})
const totalSkore5 = computed(() => {
    return (parseInt(input.value.nihssTingkatKesadaran5, 10) || 0) + 
            (parseInt(input.value.nihssMenjawabPertanyaan5, 10) || 0) + 
            (parseInt(input.value.nihssMengikutiPerintah5, 10) || 0) + 
            (parseInt(input.value.nihssGaze5, 10) || 0)  +
            (parseInt(input.value.nihssVisual5, 10) || 0)  +
            (parseInt(input.value.nihssParesisWajah5, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan5Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikLengan5Kiri, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai5Kanan, 10) || 0)  +
            (parseInt(input.value.nihssMotorikTungkai5Kiri, 10) || 0)  +
            (parseInt(input.value.nihssAtaksiaAnggotaGerak5, 10) || 0)  +
            (parseInt(input.value.nihssSensorik5, 10) || 0)  +
            (parseInt(input.value.nihssBahasaTerbaik5, 10) || 0)  +
            (parseInt(input.value.nihssDisatria5, 10) || 0)  +
            (parseInt(input.value.nihssPengabaianInatensi5, 10) || 0)
})
  
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
  
  .tbl-kmiccu th {
    text-align: center;
    vertical-align: middle;
  }
  
  .tbl-kmiccu tr,
  .tbl-kmiccu th,
  .tbl-kmiccu td {
    border: 1px solid black;
    padding: 5px;
  }
</style>
  