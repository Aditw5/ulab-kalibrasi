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
      <table class="tbl-kmiccu">
        <thead>
          <tr>
            <th rowspan="3">No</th>
            <th rowspan="3">Parameter yang Dinilai</th>
            <th rowspan="3">Skala</th>
            <th colspan="2">Skor</th>
          </tr>
          <tr>
            <th>Masuk</th>
            <th>Keluar</th>
          </tr>
          <tr>
            <th>
              <VField>
            <VDatePicker v-model="input.tglMasuk" mode="dateTime" style="width: 100%" trim-weeks
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
            <VDatePicker v-model="input.tglKeluar" mode="dateTime" style="width: 100%" trim-weeks
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
                      v-model="input['penjelasan' + data.no]" />
                  </VControl>
                </VField>
              </td>
              <td>
                <VField label="Kanan :" v-if="data.kiriKanan" />
                <VField>
                  <VControl class="mt-2">
                    <VInput type="text" placeholder="Skor Masuk" class="is-rounded"
                      v-model="input['skorMasuk' + data.no + (data.kiriKanan ? 'Kanan' : '')]" />
                  </VControl>
                </VField>
                <VField label="Kiri :" v-if="data.kiriKanan" />
                <VField v-if="data.kiriKanan">
                  <VControl class="mt-2">
                    <VInput type="text" placeholder="Skor Masuk" class="is-rounded"
                      v-model="input['skorMasuk' + data.no + (data.kiriKanan ? 'Kiri' : '')]" />
                  </VControl>
                </VField>
              </td>
              <td>
                <VField label="Kanan :" v-if="data.kiriKanan" />
                <VField>
                  <VControl class="mt-2">
                    <VInput type="text" placeholder="Skor Keluar" class="is-rounded"
                      v-model="input['skorKeluar' + data.no + (data.kiriKanan ? 'Kanan' : '')]" />
                  </VControl>
                </VField>
                <VField label="Kiri :" v-if="data.kiriKanan" />
                <VField v-if="data.kiriKanan">
                  <VControl class="mt-2">
                    <VInput type="text" placeholder="Skor Keluar" class="is-rounded"
                      v-model="input['skorKeluar' + data.no + (data.kiriKanan ? 'Kiri' : '')]" />
                  </VControl>
                </VField>
              </td>
            </template>
          </tr>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3">Jumlah Skala</th>
            <th>{{ totalSkalaMasuk }}</th>
            <th>{{ totalSkalaKeluar }}</th>
          </tr>
        </tfoot>
      </table>

      <!-- <table class="tbl-kmiccu" style="margin-top: 30px;">
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
      </table> -->
      <VCard>
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
      </VCard>
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
import * as EMR from '../page-emr-plugins/the-national-institute-of-health-stroke-scale'

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
const input: any = ref({})
const totalSkalaMasuk: any = ref(0)
const totalSkalaKeluar: any = ref(0)

const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("TheNationalInstituteofHealthStrokeScale") //table mongodb
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

const changeJumlahSkala = () => {
  totalSkalaMasuk.value = 20
  totalSkalaKeluar.value = 20
}


watchEffect(() => {
  totalSkalaMasuk.value = 0;
  totalSkalaKeluar.value = 0;

  for (let i = 1; i <= 13; i++) {
    const skorMasuk = 'skorMasuk' + i;
    const skorKeluar = 'skorKeluar' + i;

    const skorMasuka = skorMasuk+'a'
    const skorMasukb = skorMasuk+'b'
    const skorMasukc = skorMasuk+'c'
    const skorKeluara = skorKeluar+'a'
    const skorKeluarb = skorKeluar+'b'
    const skorKeluarc = skorKeluar+'c'

    const skorMasukKanan = skorMasuk+'Kanan'
    const skorKeluarKanan = skorKeluar+'Kanan'
    const skorMasukKiri = skorMasuk+'Kiri'
    const skorKeluarKiri = skorKeluar+'Kiri'



    totalSkalaMasuk.value += input.value[skorMasuka] ? parseFloat(input.value[skorMasuka]) : 0;
    totalSkalaKeluar.value += input.value[skorKeluara] ? parseFloat(input.value[skorKeluara]) : 0;
    totalSkalaMasuk.value += input.value[skorMasukb] ? parseFloat(input.value[skorMasukb]) : 0;
    totalSkalaKeluar.value += input.value[skorKeluarb] ? parseFloat(input.value[skorKeluarb]) : 0;
    totalSkalaMasuk.value += input.value[skorMasukc] ? parseFloat(input.value[skorMasukc]) : 0;
    totalSkalaKeluar.value += input.value[skorKeluarc] ? parseFloat(input.value[skorKeluarc]) : 0;

    totalSkalaMasuk.value += input.value[skorMasuk] ? parseFloat(input.value[skorMasuk]) : 0;
    totalSkalaKeluar.value += input.value[skorKeluar] ? parseFloat(input.value[skorKeluar]) : 0;

    totalSkalaMasuk.value += input.value[skorMasukKanan] ? parseFloat(input.value[skorMasukKanan]) : 0;
    totalSkalaKeluar.value += input.value[skorKeluarKanan] ? parseFloat(input.value[skorKeluarKanan]) : 0;
    totalSkalaMasuk.value += input.value[skorMasukKiri] ? parseFloat(input.value[skorMasukKiri]) : 0;
    totalSkalaKeluar.value += input.value[skorKeluarKiri] ? parseFloat(input.value[skorKeluarKiri]) : 0;
  }
});



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
