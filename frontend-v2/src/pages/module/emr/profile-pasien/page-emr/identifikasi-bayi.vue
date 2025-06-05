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
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> DPJP yang Merujuk :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dpjpYangMerujuk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="DPJP" class="mt-2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-2" style="margin-top:0.5rem">
            <span> Dokter Paliatif :</span>
          </div>
          <div class="column is-10">
            <VField>
              <VControl class="prime-auto">
                <AutoComplete v-model="input.dokterPaliatif" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter" class="mt-2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <table class="tbl-kmICU">
        <!-- <thead>
          <tr>
            <th>NO</th>
            <th>KRITERIA YANG DI NILAI</th>
          </tr>
        </thead> -->
        <tbody>
          <tr>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Nama Ibu :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.namaIbu" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%"></td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Nama Ayah :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.namaAyah" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> No Pasien :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.noPasien1" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
          </tr>
          <tr>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Nama Bayi :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.namaBayi" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%"></td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Dokter / Bidan Penolong :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.namaDokterAtauBidanPenolong" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> No Pasien :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.noPasien2" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
          </tr>
          <tr>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> No Peneng :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.noPeneng" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Nama Pemberi Nomor :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.namaPemberiNomor" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Tanggal Lahir Bayi Jam :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl class="prime-auto">
                        <Calendar v-model="input.tanggalLahirBayi" selectionMode="single" :manualInput="true" class="w-100"
                          :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Jenis :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.jenis" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
          </tr>
          <tr>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Tanda Tangan :</span>
                  </div>
                  <div class="column is-10">
                    <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> warna Kulit :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.warnaKulit" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Berat Badan :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.beratbadan" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
            <td style="width:25%">
              <div class="column is-12 p-0">
                <!-- <div class="is-flex"> -->
                  <div class="column is-12" style="margin-top:0.5rem">
                    <span> Panjang :</span>
                  </div>
                  <div class="column is-10">
                    <VField>
                      <VControl>
                        <VInput type="text" class="input" placeholder="... " v-model="input.panjang" />
                      </VControl>
                    </VField>
                  </div>
                <!-- </div> -->
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2" style="height: 500px">
              <p>Cap telapak kaki kiri bayi</p>
            </td>
            <td colspan="2" style="height: 500px">
              <p>Cap telapak kaki kanan bayi</p>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="column is-12 p-0">
        <div class="is-flex">
          <div class="column is-1" style="margin-top:0.5rem">
            <span> Tanggal :</span>
          </div>
          <div class="column is-5">
            <VField>
              <VControl class="prime-auto">
                <Calendar v-model="input.tanggalPenilaian" selectionMode="single" :manualInput="true" class="w-100"
                  :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'" :disabled="item.tgl2" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>
      <div class="column is-12 p-0">
        <h1>SEWAKTU PULANG</h1>
        <p>Saya menyatakan bahwa pada saat saya pulang telah menerima bayi saya, memeriksanya dan meyakinkan bahwa saya tersebut adalah betul - betul anak saya, saya mengecek Nomor
          penengnya dan nomor pengenalnya adalah
        </p>
        <div class="column is-3">
          <VField>
            <VControl>
              <VInput type="text" class="input" placeholder="... " v-model="input.noPeneng2" />
            </VControl>
          </VField>
        </div>
        <p>Dan berisi keterangan pengenal yang sesuai</p>
      </div>
      <div class="column is-12">
        <div class="columns is-multiline">
            <div class="column is-6">
                <img v-if="input.petugasPerawat"
                :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.petugasPerawat ? input.petugasPerawat.label : '-')">
            </div>
            <div class="column is-6 ">
                <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan>
            </div>
            <div class="column is-4">
                <h1 class="p-0" style="font-weight: bold;">Pemeberi Informasi </h1>
                <VField>
                    <VControl class="prime-auto">
                        <AutoComplete v-model="input.petugasPerawat" :suggestions="d_Dokter"
                            @complete="fetchDokter($event)" :optionLabel="'label'" :dropdown="true"
                            :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                            :field="'label'" placeholder="Yang Menjelaskan..." class="mt-2"
                            @item-select="setTandaTangan($event)" />
                    </VControl>
                </VField>
            </div>
            <div class="column is-4 is-offset-2">
                <h1 class="p-0" style="font-weight: bold;">Tanda Tangan Ibu</h1>
                <VField>
                    <VControl>
                        <VInput type="text" class="input" placeholder="Nama Ibu"
                            v-model="input.tandaTanganIbu" />
                    </VControl>
                </VField>
            </div>
        </div>
    </div>
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
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'

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


const input: any = ref({})

const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref("identifikasiBayi") //table mongodb
const NOREC_EMRPASIEN: any = ref(norec_emr ? norec_emr : '')
const { y } = useWindowScroll()
const d_Dokter: any = ref([])
const d_Pegawai: any = ref([])
const isLoading: any = ref(false)

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
const isStuck = computed(() => { return y.value >= 20 })

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}
  input.value.tanggalMRS = H.formatDate(new Date(input.value.tanggalMRS), 'YYYY-MM-DD HH:mm')
  input.value.tanggalKICU = H.formatDate(new Date(input.value.tanggalKICU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalMICU = H.formatDate(new Date(input.value.tanggalMICU), 'YYYY-MM-DD HH:mm')
  input.value.tanggalVerif = H.formatDate(new Date(input.value.tanggalVerif), 'YYYY-MM-DD HH:mm')
  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  object.tandaTanganPerawat = H.tandaTangan().get("signature_1")
  object.tandaTanganPasien = H.tandaTangan().get("signature_2")
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
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganPasien)
        }
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
      }
    })
}

setAutoFill()
setView()
loadRiwayat()
</script>
<style scoped lang="scss">
.tbl-kmICU {
  width: 100%;
  border-collapse: collapse;
  font-size: 12pt;
}

.tbl-kmICU tr,
.tbl-kmICU th,
.tbl-kmICU td {
  border: 1px solid black;
}
</style>
