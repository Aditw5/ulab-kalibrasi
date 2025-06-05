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

  <div class="column is-12">
    <VCard>
      <div class="column">
        <h1 style="font-weight:bold">Yang bertanda tangan dibawah ini</h1>
        <div class="column is-6">
          <span class="label-pso">Nama</span>
          <VField class="mt-3">
            <VControl>
              <VInput type="text" class="input" v-model="input.namaPenangungJwb" />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline pt-3 pl-3 pr-3">
          <div class="column is-4 pb-0">
            <span class="label-pso">Jenis Kelamin</span>
            <div class="columns is-multiline pt-5 pl-5 pr-5">
              <div class="column p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKlmPenangungJwb" class="p-0" true-value="Laki-laki" label="Laki-laki"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
              <div class="column p-0">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.jnsKlmPenangungJwb" class="p-0" true-value="Perempuan" label="Perempuan"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-pso">Tanggal Lahir</span>
            <VDatePicker class="p-3 pb-0" v-model="input.tglLahirPenangungJwb" color="green" trim-weeks mode="date"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
        <div class="column is-7 pt-0">
          <span class="label-pso">Alamat</span>
          <VField class="pt-3">
            <VControl>
              <VTextarea v-model="input.alamat" rows="1" placeholder="Alamat ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column">
        <h1 style="font-weight:bold">Dalam hal ini bertindak sebagai : </h1>
        <VField class="column is-4">
          <VControl class="prime-auto">
            <AutoComplete v-model="item.wali" :suggestions="d_Wali" @complete="fetchWali($event)" :optionLabel="'label'"
              :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
              placeholder="Cari ..." class="mt-2" />
          </VControl>
        </VField>
        <div class="column is-6">
          <span class="label-pso">Nama</span>
          <VField class="mt-3">
            <VControl>
              <VInput type="text" class="input" v-model="input.namaPasien" />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline pt-3 pl-3">
          <div class="column is-3 pb-0">
            <span class="label-pso">Jenis Kelamin</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.jnsKlmPasien" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-pso">No Rekam Medis</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.norm" />
              </VControl>
            </VField>
          </div>
          <div class="column is-3 pb-0">
            <span class="label-pso">Tanggal Lahir</span>
            <VDatePicker class="p-3 pb-0" v-model="input.tglLahirPasien" color="green" trim-weeks mode="date"
              :max-date="new Date()">
              <template #default="{ inputValue, inputEvents }" class="pb-0">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
        </div>
      </div>

      <div class="column">
        <p>Dengan ini menyatakan dengan sadar dan sesungguhnya bahwa</p>
        <div class="column p-3">
          <p style="text-align:justify">1. Telah menerima dan memahami informasi mengenai kondisi terhadap diri
            saya / pasien dan tindakan penanganan awal yang telah dilakukan dari pihak Rumah Sakit</p>
          <p style="text-align:justify" class="pt-4">2. Meminta kepada pihak Rumah Sakit untuk diberikan
            kesempatan mencari second opinion terhadap alternatif diagnosa / pengobatan diri saya / pasien ke
            dokter</p>
          <div class="column is-5">
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.namaDokterTujuan" />
              </VControl>
            </VField>
          </div>
          <p style="text-align:justify" class="pt-4">di Rumah Sakit</p>
          <div class="column is-5">
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.namaRSTujuan" />
              </VControl>
            </VField>
          </div>
          <p style="text-align:justify" class="pt-4">3. Segala sarana, biaya maupun fasilitas untuk mencari second
            opinion adalah tanggung jawab diri saya / pasien / keluarga</p>
          <p style="text-align:justify" class="pt-4">4. Untuk keperluan tersebut di atas, meminjam hasil
            pemeriksaan penunjang kesehatan saya/pasien berupa</p>
        </div>
      </div>

      <div class="column is-3">
        <span class="label-pso">Bandung</span>
        <VDatePicker v-model="input.tglPermintaan" color="green" class="pt-3" trim-weeks mode="datetime"
          :max-date="new Date()">
          <template #default="{ inputValue, inputEvents }" class="pb-0">
            <VField>
              <VControl icon="feather:calendar">
                <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                  class="is-rounded_Z" />
              </VControl>
            </VField>
          </template>
        </VDatePicker>
      </div>

      <div class="columns is-multiline pt-5" style="justify-content: space-around;">
        <div class="column is-3" style="text-align:center">
          <TandaTangan :elemenID="'signaturePetugas'" :width="'180'" :height="'180'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <span class="label-pso">Petugas / Perawat</span>
            <VControl class="prime-auto">
              <AutoComplete v-model="input.petugas" :suggestions="d_Pegawai" @complete="fetchPegawai($event)"  @item-select="setTandaTanganPerawat($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="Cari Petugas / Perawat..." class="mt-2" />
            </VControl>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <TandaTangan :elemenID="'signatureSaksi'" :width="'180'" :height="'180'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <span class="label-pso">Saksi</span>
            <VField class="pt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.saksi" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <TandaTangan :elemenID="'signatureMenyatakan'" :width="'180'" :height="'180'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <span class="label-pso">Yang Menyatakan</span>
            <VField class="pt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.menyatakan" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-3" style="text-align:center">
          <TandaTangan :elemenID="'signaturePeminjam'" :width="'180'" :height="'180'" class="dek" />
          <div class="column p-0 mt-5" style="text-align: left;">
            <span class="label-pso">Peminjam</span>
            <VField class="pt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.peminjam" />
              </VControl>
            </VField>
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
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
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
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Wali: any = ref([])
const d_Pegawai: any = ref([])
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
  tglPermintaan: new Date()
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
        if (response[0].ttdPegawai) {
          H.tandaTangan().set("signaturePetugas", response[0].ttdPegawai)
        }
        if (response[0].ttdSaksi) {
          H.tandaTangan().set("signatureSaksi", response[0].ttdSaksi)
        }
        if (response[0].ttdMenyatakan) {
          H.tandaTangan().set("signatureMenyatakan", response[0].ttdMenyatakan)
        }
        if (response[0].ttdPeminjam) {
          H.tandaTangan().set("signaturePeminjam", response[0].ttdPeminjam)
        }
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
  object.ttdPetugas = H.tandaTangan().get("signaturePetugas")
  object.ttdSaksi = H.tandaTangan().get("signatureSaksi")
  object.ttdMenyatakan = H.tandaTangan().get("signatureMenyatakan")
  object.ttdPeminjam = H.tandaTangan().get("signaturePeminjam")
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

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namaPasien = props.pasien.namapasien
  input.value.jnsKlmPasien = props.pasien.jeniskelamin
  input.value.tglLahirPasien = props.pasien.tgllahir
  input.value.norm = props.pasien.nocm
}

const fetchWali = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/penanggungjawab_m?select=id,penanggungjawab&param_search=penanggungjawab&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Wali.value = response
  })
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}

const setTandaTanganPerawat = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signaturePetugas", element.ttd)
    } else {
      H.tandaTangan().set("signaturePetugas", '')
    }
  })
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.label-pso {
  font-weight: 500;
}
</style>
