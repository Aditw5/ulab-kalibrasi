<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading"
              @simpan="simpan" @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>


  <div class="column">
    <!-- daftar-pemberian-obat-infus-pasien -->
    <!-- form emr -->
    <div class="columns is-multiline">
      <div class="column is-12">
        <VCard class="p-3">
          <div class="column is-12 px-2">
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Status Pernikahan</b>
              </div>
              <div class="column is-4">
                <VField>
                  <VControl>
                    <VRadio v-model="input.statusPernikahan" value="Mekikah" label="Mekikah" color="danger" />
                    <VRadio v-model="input.statusPernikahan" value="Belum Menikah" label="Belum Menikah"
                      color="danger" />
                    <VRadio v-model="input.statusPernikahan" value="Janda / Duda" label="Janda / Duda" color="danger" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Orang Terdekat</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis" v-model="input.namaOrangTerdekat" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Lainya" v-model="input.namaOrangLainnya" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Tinggal" v-model="input.namaOrangTinggal" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Konflik Dengan</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Jenis" v-model="input.namaOrangKonflik" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Lainnya" v-model="input.namaLainnyaKonflik" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="Tinggal" v-model="input.namaTinggalKonflik" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <p><b>Stresor</b></p>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.Stresor" class="is-primary-focus" rows="3" placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <p><b>Autonamnesis</b></p>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.Autonamnesis" class="is-primary-focus" rows="3"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <p><b>Heteronamnesis</b></p>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.Heteronamnesis" class="is-primary-focus" rows="3"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <p><b>Sumber Informasi</b></p>
                <VField>
                  <VControl>
                    <VTextarea v-model="input.sumberInformasi" class="is-primary-focus" rows="3"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Tumbuh Kembang</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VRadio v-model="input.tumbuhKembang" value="Sesuai Usia" label="Sesuai Usia" color="danger" />
                    <VRadio v-model="input.tumbuhKembang" value="Ada Keterlambatan" label="Ada Keterlambatan"
                      color="danger" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="" v-model="input.tumbuhKembangKet" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>P'nyalahgunaan Obat</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VRadio v-model="input.penyalahGunaanObat" value="Ya" label="Ya" color="danger" />
                    <VRadio v-model="input.penyalahGunaanObat" value="Tidak" label="Tidak" color="danger" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="" v-model="input.penyalahGunaanKet" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Riwayat Penyakit Fisik</b>
              </div>
              <div class="column is-3">
                <p><b>Demam</b></p>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VRadio v-model="input.demamPilihan" value="Ya" label="Ya" color="danger" />
                      <VRadio v-model="input.demamPilihan" value="Tidak" label="Tidak" color="danger" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.demamKet" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-3">
                <p><b>Kejang</b></p>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VRadio v-model="input.kejangPilihan" value="Ya" label="Ya" color="danger" />
                      <VRadio v-model="input.kejangPilihan" value="Tidak" label="Tidak" color="danger" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.kejangKet" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-3">
                <p><b>Trauma Kepala</b></p>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VRadio v-model="input.traumaKPilihan" value="Ya" label="Ya" color="danger" />
                      <VRadio v-model="input.traumaKPilihan" value="Tidak" label="Tidak" color="danger" />
                    </VControl>
                  </VField>
                </div>
                <div class="column is-12">
                  <VField>
                    <VControl>
                      <VInput type="text" class="input" placeholder="" v-model="input.traumaKKet" />
                    </VControl>
                  </VField>
                </div>
              </div>
              <div class="column is-3">
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput type="text" class="input" placeholder="lainnya ..." v-model="input.riwayatLainnyaKet" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Efek Samping Obat</b>
              </div>
              <div class="column is-9">
                <VField>
                  <VControl>
                    <VTextarea v-model="input.efekSampingObat" class="is-primary-focus" rows="3"
                      placeholder="Keterangan..." />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Pemeriksaan Fisik</b>
              </div>
              <div class="column is-9">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>TD</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pfTD" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Respirasi</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pfRespirasi" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Nadi</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pfNadi" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Suhu</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pfSuhu" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Keadaan Umum</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.keadaanUmum" class="is-primary-focus" rows="1" placeholder="" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.keadaanUmumKet" class="is-primary-focus" rows="1" placeholder="keterangan" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Roman Muka</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.romanMuka" class="is-primary-focus" rows="1" placeholder="" />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.romanMukaKet" class="is-primary-focus" rows="1" placeholder="keterangan" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Kontak</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.kontak" class="is-primary-focus" rows="1" placeholder="" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Raport</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.kontak" class="is-primary-focus" rows="1" placeholder="" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Perhatian</b>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl>
                    <VInput v-model="input.perhatian" class="is-primary-focus" rows="1" placeholder="" />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Orientasi</b>
              </div>
              <div class="column is-9">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>Tempat</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.orientasiTempat" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Waktu</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.orientasiWaktu" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Orang</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.orientasiOrang" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Pikiran</b>
              </div>
              <div class="column is-9">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>Bentuk</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pikiranBentuk" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Arus Pikiran</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pikiranArus" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Preokupasi</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pikiranPreokupasi" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Waham</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.pikiranWaham" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Persepsi</b>
              </div>
              <div class="column is-9">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>Ilusi</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.persepsiIlusi" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Halusinasi</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.persepsiHalusinasi" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-3">
                <b>Emosi</b>
              </div>
              <div class="column is-9">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>Mood</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.emosiMood" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Aftek</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.emosiAftek" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>Bicara</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.bicara" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Tingkah Laku</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.tingkahLaku" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Dekorum</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.dekkorum" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Tilikan</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.tilikan" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <p>
                  <b>Diagnosis</b>
                </p>
                <div class="columns is-multiline">
                  <div class="column is-3">
                    <p><b>Aksis 1</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.aksis1" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Aksis 2</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.aksis2" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Aksis 3</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.aksis3" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-3">
                    <p><b>Aksis 4</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.aksis4" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <p><b>Aksis 5</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.aksis5" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                  <div class="column is-6">
                    <p><b>Penata Pelaksana</b></p>
                    <VField>
                      <VControl>
                        <VTextarea v-model="input.penataPelaksana" class="is-primary-focus" rows="1" placeholder="" />
                      </VControl>
                    </VField>
                  </div>
                </div>
              </div>
            </div>
          </div>
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
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'


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
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value > 30 })
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
const input: any = ref({})
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

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {

}
setView()
setAutoFill()
loadRiwayat()
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
</style>
