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

  <VButton type="button" class="m-4" outlined icon="feather:plus"
    @click="tambahPersetujuan" color="success" v-tooltip-prime.top="'Tambah '">Tambah
  </VButton>

  <Fieldset v-for="(item, indexData) in input.details" :legend="`Peresetujuan/Penolakan ke ${item.no}`" :toggleable="true">
    <VButton type="button" class="m-4" outlined icon="feather:trash"
    @click="hapusPersetujuan(indexDetail)" color="danger" v-tooltip-prime.top="'Tambah '">Hapus Persetujuan
  </VButton>
    <VCard>
      <h3 class="title is-5 mb-2" style="text-align: center">Pemberian Informasi</h3>
      <p>
      <!-- <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Jenis Tindakan</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="item.tindakan" :suggestions="d_Tindakan" @complete="fetchTindakan($event)"
                :optionLabel="'nama'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'nama'" placeholder="Cari Tindakan" />
            </VControl>
          </VField>
        </div>
      </div> -->
      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Dokter Pelaksana Tindakan</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="item.dokterTindakan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                placeholder="Cari Dokter..." />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Pemberi Informasi</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl class="prime-auto">
              <AutoComplete v-model="item.pegawaiPemberiInformasi" :suggestions="d_Pegawai"
                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Pemberi Informasi..." />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Penerima Informasi/Pemberi Persetujuan</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.penerimaInformasi" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Hubungan dengan Pasien</h1>
        </div>
        <div class="column is-9">
          <div class="columns is-multiline">
            <VField v-for="hub in hubunganPasien" :key="hub.value" class="p-0 m-0 pt-4 column is-2">
                <VControl raw subcontrol>
                    <VCheckbox v-model="item.hubunganPasien" class="pt-1 pb-1 "
                        :true-value="hub.value" :label="hub.label" color="primary" square />
                </VControl>
            </VField>
          </div>
          <VField class="pt-3" v-if="item.hubunganPasien == 'Lainlain'">
            <VControl>
              <VInput type="text" v-model="item.hubunganDenganPasien"/>
            </VControl>
          </VField>
        </div>
      </div>
      </p>
    </VCard>

    <vCard>
      <p>
      <table class="table is-hoverable is-fullwidth">
        <thead>
          <tr>
            <th scope="col"></th>
            <th scope="col" style="width:30%">Jenis Informasi</th>
            <th scope="col" style="width:60%">Isi Informasi</th>
            <th scope="col" class="is-end">Tanda <br>(&#10003;)</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="info in d_Informasi">
            <td>{{info.no}}</td>
            <td>
              <VField>
                <VControl>
                  <VTextarea
                    v-model="item[`jenis_informasi${info.model}`]"
                    class="is-primary-focus"
                    rows="2"
                  />
                </VControl>
              </VField>
            </td>
            <td>
              <VField>
                <VControl>
                  <VTextarea
                    v-model="item[`isi${info.model}`]"
                    class="is-primary-focus"
                    rows="2"
                  />
                </VControl>
              </VField>
            </td>
            <td>
              <VField>
                <VControl raw subcontrol>
                  <VCheckbox v-model="item[`option${info.model}`]" color="primary" />
                </VControl>
              </VField>
            </td>
          </tr>
        </tbody>
      </table>

      <div class="columns is-multiline">
        <div class="column is-9">
          <h1 class="mt-3">Dengan ini menyatakan bahwa saya Dokter telah menerangkan hal-hal di atas secara benar dan jelas
            dan
            memberikan
            kesempatan untuk bertanya dan/atau berdiskusi</h1>
        </div>
        <div class="column is-3" style="text-align: center;">
          <p class="label-ppap">Dokter</p>
          <!-- <TandaTangan :elemenID="'signatureDokterYangMenyatakan'" :width="'180'" :height="'180'" class="dek" /> -->
          <img v-if="item.dokterMenyatakan"
              :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (item.dokterMenyatakan ? item.dokterMenyatakan.label : '-')">
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-2">
              <VControl class="prime-auto">
                <AutoComplete v-model="item.dokterMenyatakan" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  @item-select="setTandaTanganDokter($event)" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="Cari Dokter..." />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-9">
          <h1 class="mt-3">Dengan ini menyatakan bahwa saya/keluarga pasien telah menerima informmasi sebagaimana di atas
            yang saya beri
            tanda/paraf di kolom kanannya serta telah diberi kesepakatan untuk bertanya/berdikusi dan telah memahaminya.
          </h1>
        </div>
        <div class="column is-3" style="text-align: center;">
          <p class="label-ppap">Pasien/keluarga Pasien</p>
          <TandaTangan :elemenID="'signaturePasienYangMenyatakan'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-2">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.namapasienKeluarga" />
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      </p>
    </vCard>

    <vCard>
      <h3 class="title is-5 mb-2" style="text-align: center">Persetujuan Tindakan</h3>
      <p>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Yang bertanda tangan dibawah ini, saya :
          </h1>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Nama :</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.namaPasien" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Tgl. Lahir :</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.tglLahir" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Jenis Kelamin :</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.jenisKelamin" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Alamat :</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.alamatPasien" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Dengan ini menyatakan <b>MENYETUJUI</b> dan <b>MEMUTUSKAN</b> untuk dilakukan tindakan terhadap saya /</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.pernyataanPTA" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">saya, Yang bernama</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.yangBernamaPTA" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Tanggal Lahir</h1>
        </div>
        <div class="column is-8">
          <VDatePicker class="pt-3" v-model="item.tglLahirPTA" color="green" trim-weeks mode="date" :max-date="new Date()">
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
        <div class="column is-4">
          <h1 class="mt-3">Jenis Kelamin</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.jeniskelamin2PTA" />
            </VControl>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Alamat</h1>
        </div>
        <div class="column is-8">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.alamat2PTA" />
            </VControl>
          </VField>
        </div>
      </div>
      Saya memahami perlunya dan manfaat tindakan tersebut sebagaimana telah dijelaskan seperti di atas kepada saya,termasuk
      resiko dan
      komplikasi yang mungkin timbul. <br>
      Saya juga menyadai bahwa dokter melakukan suatu upata dan oleh karena ilmu kedokteran bukanlah ilmu pasti, maka keberhasilan tindakan kedokteran bukanlah keniscayaan, melainkan sangat bergantung kepada izin Tuhan yang Maha Esa
      </p>
    </vCard>

    <vCard>
      <p>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Cirebon</h1>
        </div>
        <div class="column is-8">
          <VDatePicker class="pt-3" v-model="item.tglDibuat" color="green" trim-weeks mode="datetime"
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
      <div class="columns is-multiline">
        <div class="column is-4" style="text-align: center;">
          <p class="label-ppap">Yang menyatakan</p>
          <TandaTangan :elemenID="'signatureYangMenyatakan'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.pasienYangMenyatakan" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <p class="label-ppap">Saksi 1</p>
          <TandaTangan :elemenID="'signatureSaksi1'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.saksi1" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <p class="label-ppap">Saksi 2</p>
          <TandaTangan :elemenID="'signatureSaksi2'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.saksi2" />
              </VControl>
            </VField>
          </div>
        </div>


      </div>
      </p>
    </vCard>
  </Fieldset>

  <div class="column">
    <!-- form emr -->
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
import * as EMR from '../page-emr-plugins/persetujuan-tindakan-kedokteran'


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
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_produk: any = ref([])
const d_Tindakan: any = ref([])
const d_Informasi : any = ref(EMR.PemberianInformasi())
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('persetujuanTindakanKedokteranCathlab') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{ no: 1 }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const tambahPersetujuan = () => {
  input.value.details.unshift({
      no : input.value.details.length + 1,
      tglPembuatanBawaPulang : new Date(),
    tglPembuatanObatIGD : new Date(),
    alamatlengkap : props.pasien.alamatlengkap,
    tglregis : props.registrasi.tglregistrasi,
    dokterDPJP : props.registrasi.dokter,
    namapasienKeluarga : props.pasien.namapasien,
    namaPasien : props.pasien.namapasien,
    pasienYangMenyatakan : props.pasien.namapasien,
    jenisKelamin : props.pasien.jeniskelamin,
    tglLahir : props.pasien.tgllahir,
    alamatPasien : props.pasien.alamatlengkap,
  })
}

const hapusPersetujuan = (indexDetail: any) => {
  input.value.details.splice(indexDetail, 1)
}

const hubunganPasien: any = ref([
    { label: 'Orangtua', value: 'Orangtua' },
    { label: 'Anak', value: 'Anak' },
    { label: 'Suami', value: 'Suami' },
    { label: 'Istri', value: 'Istri' },
    { label: 'Saudara', value: 'Saudara' },
    { label: 'Lain-lain', value: 'Lainlain' },
])

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].ttdsaksi1) {
          H.tandaTangan().set("signatureSaksi1", response[0].ttdsaksi1)
        }
        if (response[0].ttdsaksi2) {
          H.tandaTangan().set("signatureSaksi2", response[0].ttdsaksi2)
        }
        if (response[0].ttdyangMenyatakan) {
          H.tandaTangan().set("signatureYangMenyatakan", response[0].ttdyangMenyatakan)
        }
        if (response[0].ttdDokteryangMenyatakan) {
          H.tandaTangan().set("signatureDokterYangMenyatakan", response[0].ttdDokteryangMenyatakan)
        }
        if (response[0].ttdPasienyangMenyatakan) {
          H.tandaTangan().set("signaturePasienYangMenyatakan", response[0].ttdPasienyangMenyatakan)
        }
        if (response[0].tandaTanganPerawatObatRawatInap) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawatObatRawatInap)
        }
        if (response[0].tandaTanganApotekerObatRawatInap) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganApotekerObatRawatInap)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdsaksi1 = H.tandaTangan().get("signatureSaksi1")
  object.ttdsaksi2 = H.tandaTangan().get("signatureSaksi2")
  object.tandaTanganPerawatObatRawatInap = H.tandaTangan().get("signature_1")
  object.tandaTanganApotekerObatRawatInap = H.tandaTangan().get("signature_2")
  object.ttdyangMenyatakan = H.tandaTangan().get("signatureYangMenyatakan")
  object.ttdDokteryangMenyatakan = H.tandaTangan().get("signatureDokterYangMenyatakan")
  object.ttdPasienyangMenyatakan = H.tandaTangan().get("signaturePasienYangMenyatakan")
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

const addNewItemObatRawatInap = () => {
  input.value.detailObatRawatInap.push({
    no: input.value.detailObatRawatInap[input.value.detailObatRawatInap.length - 1].no + 1,
  });
}
const removeItemObatRawatInap = (index: any) => {
  input.value.detailObatRawatInap.splice(index, 1)
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

const fetchTindakan = (filter: any) => {
  let nama = filter ? `?filterTindakan=${filter.query}` : ''
  useApi().get(`dashboard/data-tindakan${nama}`).then((response) => {
    d_Tindakan.value = response
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.details.forEach((element) => {
    element.tglPembuatanBawaPulang = new Date()
    element.tglPembuatanObatIGD = new Date()
    element.alamatlengkap = props.pasien.alamatlengkap
    element.tglregis = props.registrasi.tglregistrasi
    element.dokterDPJP = props.registrasi.dokter
    element.namapasienKeluarga = props.pasien.namapasien
    element.namaPasien = props.pasien.namapasien
    element.pasienYangMenyatakan = props.pasien.namapasien
    element.jenisKelamin = props.pasien.jeniskelamin
    element.tglLahir = props.pasien.tgllahir
    element.alamatPasien = props.pasien.alamatlengkap

    d_Informasi.value.forEach(info => {
        element[`jenis_informasi${info.model}`] = info.jenis_informasi;
        element[`isi${info.model}`] = info.isi_informasi;
    });
  });
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

.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 100%;
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle
}

.tg th {
  border-color: var(--fade-grey-dark-3);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: normal;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top;
  font-weight: bold;
}
</style>
