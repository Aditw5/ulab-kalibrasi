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
        <div class="column is-12 p-0">
          <div class="is-flex">
            <div class="column is-2" style="margin-top:0.5rem">
              <span> Diagnosa :</span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl>
                  <VInput type="text" class="input" placeholder="Diagnosa" v-model="input.diagnosa" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 p-0">
          <div class="is-flex">
            <div class="column is-2" style="margin-top:0.5rem">
              <span> MRS Tanggal :</span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalMRS" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 p-0">
          <div class="is-flex">
            <div class="column is-2" style="margin-top:0.5rem">
              <span> Masuk NICU Tanggal :</span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalMNICU" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12 p-0">
          <div class="is-flex">
            <div class="column is-2" style="margin-top:0.5rem">
              <span> Keluar NICU Tanggal :</span>
            </div>
            <div class="column is-10">
              <VField>
                <VControl class="prime-auto">
                  <Calendar v-model="input.tanggalKNICU" selectionMode="single" :manualInput="true" class="w-100"
                    :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <table class="tbl-kmNICU">
          <thead>
            <tr>
              <th>KRITERIA MASUK NICU</th>
              <th>KRITERIA KELUAR NICU</th>
            </tr>
          </thead>
          <tbody>
            <td>
              <div class="column is-12">Batasan Usia: </div>
              <div class="column is-12" v-for="(data, index) in kriteriaMasuk">
                <VField :key="index">
                  <VControl>
                    <VCheckbox v-model="input[data.model + data.no]" :label="data.value" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </td>
            <td>
              <div class="column is-12" v-for="(data, index) in kriteriaKeluar">
                <VField :key="index">
                  <VControl>
                    <VCheckbox v-model="input[data.model + data.no]" :label="data.value" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
                <VField v-if="data.formPenjelasan">
                  <VControl class="mt-2">
                    <VInput type="text" placeholder="Penjelasan" class="is-rounded"
                      v-model="input['penjelasan' + data.no]" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-12">Kriteria Khusus: </div>
              <div class="column is-12" v-for="(data, index) in kriteriaKhusus">
                <VField :key="index">
                  <VControl>
                    <VCheckbox v-model="input[data.model + data.no]" :label="data.value" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </td>
          </tbody>
        </table>

        <table class="tbl-kmNICU" style="margin-top: 30px;">
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
              <div class="column is-12 text-center">Nama: </div>
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
                <img v-if="input.dpjp"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dpjp.label ? input.dpjp.label : input.dpjp)">
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
  import * as EMR from '../page-emr-plugins/checklist-kriteria-masuk-keluar-nicu'

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

  const kriteriaMasuk = ref(EMR.kriteriaMasuk())
  const kriteriaKeluar = ref(EMR.kriteriaKeluar())
  const kriteriaKhusus = ref(EMR.kriteriaKhusus())

  const input: any = ref({})

  const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
  })
  const COLLECTION: any = ref("CheckListKriteriaMasukdanKriteriaKeluarRuangNICU") //table mongodb
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
    input.value.tanggalKNICU = H.formatDate(new Date(input.value.tanggalKNICU), 'YYYY-MM-DD HH:mm')
    input.value.tanggalMNICU = H.formatDate(new Date(input.value.tanggalMNICU), 'YYYY-MM-DD HH:mm')
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

  const setAutoFill = () =>{
    input.value.dpjp = props.registrasi.dokter
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
  .tbl-kmNICU {
    width: 100%;
    border-collapse: collapse;
    font-size: 12pt;
  }

  .tbl-kmNICU tr,
  .tbl-kmNICU th,
  .tbl-kmNICU td {
    border: 1px solid black;
  }
  </style>
