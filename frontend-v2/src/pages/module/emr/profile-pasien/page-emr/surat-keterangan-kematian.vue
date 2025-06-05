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
              <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                @click="simpan()"> Simpan
              </VButton>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column is-12">
    <VCard>
      <div class="columns is-multiline p-3 pb-1">
        <div class="column is-3" v-for="(data) in dataKhusus">
          <span class="label-skk">{{ data.label }}</span>
          <VField class="mt-2">
            <VControl>
              <VInput type="text" class="input" v-model="input[data.model]" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="column pt-0">
        <h1 class="title-skk">I. Identitas Pasien</h1>
        <div class="column is-5">
          <span class="label-skk">Nama Lengkap</span>
          <VField class="mt-2">
            <VControl>
              <VInput type="text" class="input" v-model="input.namaLengkap" />
            </VControl>
          </VField>
        </div>
        <div class="columns is-multiline p-3">
          <div class="column is-4">
            <span class="label-skk">Nomer Induk Kependudukan</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.NIK" />
              </VControl>
            </VField>
          </div>
          <div class="column is-5">
            <span class="label-skk">Nomer Kartu Keluarga</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.NKK" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="columns is-multiline p-3">
          <div class="column is-3">
            <span class="label-skk">Jenis Kelamin</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.jenisKlm" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="label-skk">Tempat Lahir</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.tmptLhr" />
              </VControl>
            </VField>
          </div>
          <div class="column is-2">
            <span class="label-skk">Tanggal Lahir</span>
            <VDatePicker class="pt-2" v-model="input.tglLahir" color="green" trim-weeks :input="'YYYY-MM-DD'" mode="date">
              <template #default="{ inputValue, inputEvents }">
                <VField>
                  <VControl icon="feather:calendar">
                    <VInput type="text" disabled placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </template>
            </VDatePicker>
          </div>
          <div class="column">
            <span class="label-skk">Agama</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.agama" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-8">
          <span class="label-skk">Alamat Tempat Tinggal</span>
          <VField class="pt-2">
            <VControl>
              <VTextarea v-model="input.alamatTempTingl" rows="2" placeholder="Alamat tempat tinggal ...">
              </VTextarea>
            </VControl>
          </VField>
        </div>

        <div class="column is-6 pb-0">
          <span class="label-skk">Status Kependudukan</span>
          <div class="columns is-multiline">
            <div class="column is-4">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.statusKepend" true-value="Penduduk" label="Penduduk" color="primary" circle />
                </VControl>
              </VField>
            </div>
            <div class="column is-5">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.statusKepend" true-value="Bukan Penduduk" label="Bukan Penduduk"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
        <div class="column is-12">
          <span class="label-skk">Hubungan dengan Kepala Rumah Tangga</span>
          <div class="columns is-multiline p-3">
            <div class="column is-3 pt-3 pb-0 pl-3 pr-3" v-for="(data, i) in hubungan">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input[data.model]" :true-value="data.code" class="p-0" :label="data.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
          <div class="column is-6 pt-0">
            <span class="label-skk">Lainnya</span>
            <VField class="mt-2">
              <VControl>
                <VInput type="text" class="input" v-model="input.hubunganLainnya" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="columns is-multiline p-2">
          <div class="column is-3">
            <span class="label-skk">Waktu Meninggal</span>
            <VDatePicker class="pt-3" v-model="input.waktuMeninggal" color="green" trim-weeks mode="dateTime"
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

          <div class="column is-3">
            <span class="label-skk">Umur saat meninggal</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.umurMeninggal" />
              </VControl>
            </VField>
          </div>
        </div>

        <div class="column is-12">
          <span class="label-skk">Tempat Meninggal</span>
          <div class="columns is-multiline p-3">
            <div class="column pt-3 pb-0 pl-3 pr-3" v-for="(data, i) in tempatMeninggal"
              :class="data.code == 'LN' ? 'is-6' : 'is-3'">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.temptMeninggal" :true-value="data.code" class="p-0" :label="data.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>
        </div>
      </div>

      <div class="column">
        <h1 class="title-skk">II. Keterangan Khusus Kasus Kematian di Rumah atau Lainnya (termasuk Dead On Arrival)
        </h1>
        <div class="column is-12">
          <span class="label-skk">Status Jenazah</span>
          <div class="columns is-multiline p-3">
            <div class="column is-3 pt-3 pb-0 pl-3 pr-3" v-for="(data, i) in statusJenazah">
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="input.statusJenazah" :true-value="data.code" class="p-0" :label="data.label"
                    color="primary" circle />
                </VControl>
              </VField>
            </div>
          </div>

          <span class="label-skk">Tanggal</span>
          <div class="column is-3">
            <VDatePicker v-model="item.tglstatusJenazah" color="green" trim-weeks mode="date" :max-date="new Date()">
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

        <div class="columns is-multiline">
          <div class="column is-3">
            <span class="label-skk">Nama Pemeriksa Jenazah</span>
            <VField class="mt-3">
              <VControl>
                <VInput type="text" class="input" v-model="input.namaPemeriksa" />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <span class="label-skk">Kualifikasi Pemeriksa</span>
            <div class="columns is-multiline p-3">
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kualifikasiPem" true-value="Medis" class="p-0" label="Medis" color="primary"
                      circle />
                  </VControl>
                </VField>
              </div>
              <div class="column">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input.kualifikasiPem" true-value="Paramedis" class="p-0" label="Paramedis"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-3">
            <span class="label-skk">Waktu Pemeriksaan Jenzah</span>
            <VDatePicker v-model="item.waktuPemeJenzah" color="green" trim-weeks mode="date" :max-date="new Date()">
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
        <h1 class="title-skk">III. Penyebab Kematian</h1>
        <div class="column is-12" v-for="(datas) in penyebabKematian">
          <span class="label-skk">{{ datas.title }}</span>
          <div class="column is-12" v-for="(data) in datas.detail">
            <span v-if="data.subTitle != ''">{{ data.subTitle }}</span>
            <div class="columns is-multiline" :class="data.subTitle != '' ? 'p-3' : ''">
              <div class="column" v-for="(pilihan) in data.value"
                :class="pilihan.code == 'GTKL' || pilihan.code == 'CKL' ? 'is-4' : 'is-3'">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="input[data.model]" :true-value="pilihan.code" class="p-0" :label="pilihan.label"
                      color="primary" circle />
                  </VControl>
                </VField>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="column is-3">
        <h1 class="title-skk">Bandung</h1>
        <VDatePicker class="pt-3" v-model="input.waktuDibuat" color="green" trim-weeks mode="dateTime"
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

      <div class="column">
        <div class="columns is-multiline" style="justify-content: space-around;">
          <div class="column is-4">
            <div class="column" style="text-align: center;">
              <TandaTangan :elemenID="'signaturePenerima'" :width="'180'" :height="'180'" class="dek" />
            </div>
            <div class="column pl-0">
              <span class="label-skk">Pihak yang Menerima</span>
              <VField class="mt-2">
                <VControl>
                  <VInput type="text" class="input" v-model="input.pihakMenerima" />
                </VControl>
              </VField>
            </div>
          </div>

          <div class="column is-4">
            <div class="column" style="text-align: center;">
              <TandaTangan :elemenID="'signatureDokter'" :width="'180'" :height="'180'" class="dek" />
            </div>
            <div class="colupzmn pl-0 mt-3">
              <span class="label-skk">Dokter yang Menerangkan</span>
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter"
                    @item-select="setTandaTanganDokter($event)" @complete="fetchDokter($event)" :optionLabel="'label'"
                    :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                    placeholder="Cari Dokter Pemeriksa..." class="mt-2" />
                </VControl>
              </VField>
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
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/surat-keterangan-kematian'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataKhusus = ref(EMR.header())
let hubungan = ref(EMR.hubunganKepalaRumah())
let tempatMeninggal = ref(EMR.tempatMeninggal())
let statusJenazah = ref(EMR.statusJenazah())
let penyebabKematian = ref(EMR.penyebabKematian())
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
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Dokter: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('SuratKeteranganKematian') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  waktuMeninggal: new Date(),
  waktuDibuat: new Date()
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
        if (response[0].ttdPenerima) {
          H.tandaTangan().set("signaturePenerima", response[0].ttdPenerima)
        }
        if (response[0].ttdDokter) {
          H.tandaTangan().set("dokterPemeriksa", response[0].ttdDokter)
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
  object.ttdPenerima = H.tandaTangan().get("signaturePenerima")
  object.ttdDokter = H.tandaTangan().get("dokterPemeriksa")
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
const setAutoFill = () => {
  input.value.namaLengkap = props.pasien.namapasien
  input.value.norm = props.pasien.nocm
  input.value.jenisKlm = props.pasien.jeniskelamin
  input.value.tglLahir = props.pasien.tgllahir
  input.value.NIK = props.pasien.nik
  input.value.alamatTempTingl = props.pasien.alamatlengkap
}

const fetchDokter = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const setTandaTanganDokter = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signatureDokter", element.ttd)
    } else {
      H.tandaTangan().set("signatureDokter", '')
    }
  })
}

setView()
setAutoFill()
loadRiwayat()
</script>

<style lang="scss">
.title-skk {
  font-weight: bold;
}

.label-skk {
  font-weight: 500;
}
</style>
