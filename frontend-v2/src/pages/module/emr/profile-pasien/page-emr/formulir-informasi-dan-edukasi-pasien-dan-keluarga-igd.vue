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

  <Fieldset v-for="(item, indexData) in input.details" :legend="`Formulir ${item.no}`" :toggleable="true">
    <VButton type="button" class="m-4" outlined icon="feather:trash"
    @click="hapusPersetujuan(indexDetail)" color="danger" v-tooltip-prime.top="'Tambah '">Hapus Daftar
  </VButton>
  <div class="columns is-multiline p-2">
    <div class="column is-12">
      <VCard>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="PERSIAPAN EDUKASI">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column is-12" v-for="(data, i) in informasi">
                  <div class="is-flex">
                    <div class="column is-2">
                      <span>{{ data.label }} :</span>
                    </div>
                    <div class="column is-10">
                      <div class="columns is-multiline">
                        <div class="column is-3" v-for="(detail, d) in data.children">
                          <VField style="padding:0px;" v-if="detail.type == 'checkbox'">
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input[detail.model]" class="pt-1 pb-1 " :true-value="detail.value"
                                :label="detail.label" color="primary" square />
                            </VControl>
                          </VField>
                          <VField style="padding:0px 10px;" v-if="detail.type == 'input'">
                            <VControl>
                              <VInput v-model="detail.tindakan" class="input" :placeholder="detail.placeholder">
                              </VInput>
                            </VControl>
                          </VField>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="column is-12">
                    <span>Pilih topik pembelajaran pada kotak yang tersedia :</span>
                  </div>
                  <div class="columns is-multiline">
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.pilihTopik" class="pt-1 pb-1 " true-value="Jenis Penyakit"
                            label="Jenis Penyakit" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-3">
                      <VField style="padding:0px;">
                        <VControl raw subcontrol>
                          <VCheckbox v-model="input.pilihTopik" class="pt-1 pb-1 " true-value="Rencana Tindakan/Terapi"
                            label="Rencana Tindakan/Terapi" color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                  </div>
                  <div class="column">
                    <span style="font-weight:bold">
                      Standar dan Prosedur yang diberikan atau diperlukan
                    </span>
                  </div>
                  <div class="column">
                    <span>
                      Jenis pelayanan termasuk terjadian yang diharapankan dan tidak diharapkan lagi
                    </span>
                  </div>
                  <div class="column is-6">
                    <VField>
                      <VControl>
                        <VInput v-model="input.tindakan" class="input" placeholder="diberikan standar dan prosedur..">
                        </VInput>
                      </VControl>
                    </VField>
                  </div>
                </div>
                <div class="column is-12">
                  <div class="column is-5">
                    <span>Pasien dan/atau keluarga untuk menerima informasi data edukasi:</span>
                  </div>
                  <div class="column is-12">
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.prosedurEdukasi" class="pt-1 pb-1 " true-value="Ya" label="Ya"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField style="padding:0px;">
                          <VControl raw subcontrol>
                            <VCheckbox v-model="input.prosedurEdukasi" class="pt-1 pb-1 " true-value="Tidak" label="Tidak"
                              color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns is-multiline">
                      <div class="column is-3">
                        <VField>
                          <VControl>
                            <VInput v-model="input.valueYaProsedurTindakan" class="input"
                              placeholder="Prosedur tindakan...">
                            </VInput>
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-3">
                        <VField>
                          <VControl>
                            <VInput v-model="input.valueTidakProsedurTindakan" class="input"
                              placeholder="Prosedur tindakan...">
                            </VInput>
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
        <div class="column is-12">
          <Fieldset :toggleable="true" legend="DETAIL EDUKASI">
            <div class="column is-12 p-2">
              <div class="columns is-multiline">
                <div class="column" style="overflow: auto;">
                  <table class="tg">
                    <thead>
                      <tr>
                        <th class="tg-0lax text-center" rowspan="2">TGL</th>
                        <th class="tg-0lax text-center" rowspan="2">POLITEKNIK</th>
                        <th class="tg-0lax text-center" rowspan="2">INFORMASI/EDUKASI TENTANG</th>
                        <th class="tg-0lax text-center" colspan="2">EDUKATOR</th>
                        <th class="tg-0lax text-center" colspan="2">SASARAN(PASIEN/KELUARGA/LAIN-LAIN)</th>
                        <th class="tg-0lax text-center" rowspan="2">EVALUASI</th>
                      </tr>
                      <tr>
                        <th class="tg-0lax">Nama/Profesi</th>
                        <th class="tg-0lax">TTD</th>
                        <th class="tg-0lax">Nama</th>
                        <th class="tg-0lax">TDD</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="(data, i) in detailEdukasi" :key="index">
                        <td width="200px">
                          <VField>
                            <VDatePicker v-model="input['tanggal_' + i]" mode="date" style="width: 100%" trim-weeks
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
                        </td>
                        <td width="250px">
                          <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:search">
                              <AutoComplete v-model="input['politeknik_' + i]" :suggestions="d_Ruangan"
                                @complete="fetchRuangan($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                placeholder="Cari poli..." />
                            </VControl>
                          </VField>
                        </td>
                        <td>
                          <span>{{ data.label }}</span>
                        </td>
                        <td width="230px">
                          <!-- <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input['namaEdukator_' + i]" class="input" :placeholder="i">
                              </VInput>
                            </VControl>
                          </VField> -->
                          <VField>
                            <VControl class="prime-auto">
                              <AutoComplete v-model="input['namaEdukator_' + i]" :suggestions="d_Petugas" @complete="fetchPetugas($event)"
                                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                                :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="PPJP IGD..." class="mt-2" />
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <!-- <TandaTangan :elemenID="'edukator_' + [i]" :width="'150'" :height="'150'" class="dek" /> -->
                          <img v-if="input['namaEdukator_' + i]"
                            :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input['namaEdukator_' + i] ? input['namaEdukator_' + i].label : '-')">
                        </td>
                        <td width="150px">
                          <VField style="padding:0px 10px;">
                            <VControl>
                              <VInput v-model="input['namaSasaran_' + i]" class="input" placeholder="Nama sasaran...">
                              </VInput>
                            </VControl>
                          </VField>
                        </td>
                        <td width="150px">
                          <TandaTangan :elemenID="'edukator_' + [i]" :width="'150'" :height="'150'" class="dek" />
                        </td>
                        <td width="200px">
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input['evaluasi_' + data.model]" class="pt-1 pb-1 "
                                  true-value="Sudah Mengerti" label="Sudah Mengerti" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input['evaluasi_' + data.model]" class="pt-1 pb-1 "
                                  true-value="Re-Edukasi" label="Re-Edukasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                          <div class="column is-12">
                            <VField style="padding:0px;">
                              <VControl raw subcontrol>
                                <VCheckbox v-model="input['evaluasi_' + data.model]" class="pt-1 pb-1 "
                                  true-value="Re-Demontrasi" label="Re-Demontrasi" color="primary" square />
                              </VControl>
                            </VField>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </Fieldset>
        </div>
      </VCard>
    </div>
  </div>
</Fieldset>
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
import * as EMR from '../page-emr-plugins/formulir-informasi-edukasi-pasien'



let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let informasi = ref(EMR.informasi())
let detailEdukasi = ref(EMR.detailEdukasi())

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
const d_Petugas: any = ref([])
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const dataTTD: any = ref([])
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
const loadRiwayat = async () => {
  // if (NOREC_EMRPASIEN.value == '') return
  await useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        if(NOREC_EMRPASIEN.value == ''){
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        input.value = response[0] //set ke inputan
        if (response[0].ttdedukator0) {
          H.tandaTangan().set("edukator_0", response[0].ttdedukator0)
        }
        if (response[0].ttdedukator1) {
          H.tandaTangan().set("edukator_1", response[0].ttdedukator1)
        }
        if (response[0].ttdedukator2) {
          H.tandaTangan().set("edukator_2", response[0].ttdedukator2)
        }
        if (response[0].ttdedukator3) {
          H.tandaTangan().set("edukator_3", response[0].ttdedukator3)
        }
        if (response[0].ttdedukator4) {
          H.tandaTangan().set("edukator_4", response[0].ttdedukator4)
        }
        if (response[0].ttdedukator5) {
          H.tandaTangan().set("edukator_5", response[0].ttdedukator5)
        }
        if (response[0].ttdedukator6) {
          H.tandaTangan().set("edukator_6", response[0].ttdedukator6)
        }
        if (response[0].ttdsasaran0) {
          H.tandaTangan().set("sasaran_0", response[0].ttdsasaran0)
        }
        if (response[0].ttdsasaran1) {
          H.tandaTangan().set("sasaran_1", response[0].ttdsasaran1)
        }
        if (response[0].ttdsasaran2) {
          H.tandaTangan().set("sasaran_2", response[0].ttdsasaran2)
        }
        if (response[0].ttdsasaran3) {
          H.tandaTangan().set("sasaran_3", response[0].ttdsasaran3)
        }
        if (response[0].ttdsasaran4) {
          H.tandaTangan().set("sasaran_4", response[0].ttdsasaran4)
        }
        if (response[0].ttdsasaran5) {
          H.tandaTangan().set("sasaran_5", response[0].ttdsasaran5)
        }
        if (response[0].ttdsasaran6) {
          H.tandaTangan().set("sasaran_6", response[0].ttdsasaran6)
        }
        if (response[0].ttdpasien0) {
          H.tandaTangan().set("pasien_0", response[0].ttdpasien0)
        }
        if (response[0].ttdpasien1) {
          H.tandaTangan().set("pasien_1", response[0].ttdpasien1)
        }
        if (response[0].ttdpasien2) {
          H.tandaTangan().set("pasien_2", response[0].ttdpasien2)
        }
        if (response[0].ttdpasien3) {
          H.tandaTangan().set("pasien_3", response[0].ttdpasien3)
        }
        if (response[0].ttdpasien4) {
          H.tandaTangan().set("pasien_4", response[0].ttdpasien4)
        }
        if (response[0].ttdpasien5) {
          H.tandaTangan().set("pasien_5", response[0].ttdpasien5)
        }
        if (response[0].ttdpasien6) {
          H.tandaTangan().set("pasien_6", response[0].ttdpasien6)
        }
        if (response[0].ttdpasien7) {
          H.tandaTangan().set("pasien_7", response[0].ttdpasien7)
        }
        if (response[0].ttdpasien8) {
          H.tandaTangan().set("pasien_8", response[0].ttdpasien8)
        }
        if (response[0].ttdpasien9) {
          H.tandaTangan().set("pasien_9", response[0].ttdpasien9)
        }
        if (response[0].ttdpasien10) {
          H.tandaTangan().set("pasien_10", response[0].ttdpasien10)
        }
        if (response[0].ttdpasien11) {
          H.tandaTangan().set("pasien_11", response[0].ttdpasien11)
        }
        if (response[0].ttdpasien12) {
          H.tandaTangan().set("pasien_12", response[0].ttdpasien12)
        }
        if (response[0].ttdpasien13) {
          H.tandaTangan().set("pasien_13", response[0].ttdpasien13)
        }
      }
    })
}


const fetchPetugas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Petugas.value = response
  })
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdedukator0 = H.tandaTangan().get("edukator_0")
  object.ttdedukator1 = H.tandaTangan().get("edukator_1")
  object.ttdedukator2 = H.tandaTangan().get("edukator_2")
  object.ttdedukator3 = H.tandaTangan().get("edukator_3")
  object.ttdedukator4 = H.tandaTangan().get("edukator_4")
  object.ttdedukator5 = H.tandaTangan().get("edukator_5")
  object.ttdedukator6 = H.tandaTangan().get("edukator_6")

  object.ttdsasaran0 = H.tandaTangan().get("sasaran_0")
  object.ttdsasaran1 = H.tandaTangan().get("sasaran_1")
  object.ttdsasaran2 = H.tandaTangan().get("sasaran_2")
  object.ttdsasaran3 = H.tandaTangan().get("sasaran_3")
  object.ttdsasaran4 = H.tandaTangan().get("sasaran_4")
  object.ttdsasaran5 = H.tandaTangan().get("sasaran_5")
  object.ttdsasaran6 = H.tandaTangan().get("sasaran_6")
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
  input.value.namaEdukator_0 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.namaEdukator_1 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.namaEdukator_2 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.namaEdukator_3 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.namaEdukator_4 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.namaEdukator_5 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }
  input.value.namaEdukator_6 = { label: useUserSession().getUser().pegawai.namaLengkap, value: useUserSession().getUser().pegawai.id }

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
  width: 150%;
}

.tg td {
  // border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  // border-color: var(--fade-grey-dark-3);
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
  vertical-align: middle
}
</style>
