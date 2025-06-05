<template>
  <ConfirmDialog />
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
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-10">
            <VField>
              <VLabel>Hasil Pemeriksaan Penunjang</VLabel>
              <VControl>
                <VTextarea v-model="input.pemeriksaPenunjang" rows="15" placeholder="Hasil Pemeriksaan Penunjang ...">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column" style="align-self: center;">
            <VField>
              <VLabel>Copy Hasil Laboratorium</VLabel>
              <VControl>
                <VIconButton style="margin-left: 30%" type="button" raised circle icon="fas fa-bong" v-tooltip-prime.bottom="'Terapkan Data'"
                  @click="show" color="warning">
                </VIconButton>
              </VControl>
            </VField>
            <VField>
              <VLabel>Copy Hasil Radiologi</VLabel>
              <VControl>
                <VIconButton style="margin-left: 30%" type="button" raised circle icon="fas fa-radiation" v-tooltip-prime.bottom="'Terapkan Data'"
                  @click="showRad" color="warning">
                </VIconButton>
              </VControl>
            </VField>
          </div>
        </div>
      </div>

      <Dialog v-model:visible="showData" maximizable modal header="Hasil Laboratorium" :style="{ width: '80rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VCard class="bt-color">
              <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.sourcePemeriksaanPenunjang" rows="20">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VCard class="pembungkus">
              <DataTable dataKey="no" :value="sourceDataLab" v-model:selection="selectedRad" class="p-datatable-sm"
                :paginator="true" :rows="10" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="no" header="No" :sortable="true" style="min-width: 50px"></Column>
                <Column field="nama_pemeriksaan" header="Layanan" frozen :sortable="true" style="min-width: 200px">
                </Column>
                <Column field="hasil" header="Hasil" :sortable="true" style="min-width: 100px"></Column>
                <Column field="normal" header="Nilai Normal" :sortable="true" style="min-width: 100px"></Column>
                <!-- <Column field="jumlahobat" header="Jumlah Obat" :sortable="true" style="min-width: 200px"></Column> -->
                <Column field="tgl_hasil" header="tanggal" :sortable="true" style="min-width: 200px"></Column>
              </DataTable>

              <div class="column is-12">
                <VButton color="primary" raised @click="tambahHasilLab(selectedRad)" outlined :loading="isLoadTmb"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
              </div>
            </VCard>
          </div>
        </div>
        <template #footer>
          <VButton color="primary" raised @click="simpanHasilPenunjang(item.sourcePemeriksaanPenunjang)"><i
              class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton>
        </template>
      </Dialog>
      <Dialog v-model:visible="showDataRad" maximizable modal header="Hasil Radiologi" :style="{ width: '80rem' }"
        :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">
        <div class="columns is-multiline">
          <div class="column is-6">
            <VCard class="bt-color">
              <h3 style="font-weight:800">Pemeriksaan Penunjang</h3>
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.sourcePemeriksaanPenunjangRad" rows="20">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </VCard>
          </div>
          <div class="column is-6">
            <VCard class="pembungkus">
              <DataTable dataKey="no" :value="sourceDataRad" v-model:selection="selectedResep" class="p-datatable-sm"
                :paginator="true" :rows="10" scrollable
                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="no" header="No" :sortable="true" style="min-width: 50px"></Column>
                <Column field="hasilexpertise" header="Hasil Expertise" frozen :sortable="true" style="min-width: 300px">
                </Column>
              </DataTable>

              <div class="column is-12">
                <VButton color="primary" raised @click="tambahHasilRad(selectedResep)" outlined :loading="isLoadTmb"><i
                    class="fas fa-plus mr-3 p-0" aria-hidden="true"></i>Tambah</VButton>
              </div>
            </VCard>
          </div>
        </div>
        <template #footer>
          <VButton color="primary" raised @click="simpanHasilPenunjangRad(item.sourcePemeriksaanPenunjangRad)"><i
              class="fas fa-save mr-3" aria-hidden="true"></i> Simpan</VButton>
        </template>
      </Dialog>
    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, watch, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import Dropdown from 'primevue/dropdown';
import * as H from '/@src/utils/appHelper'
import PanelMenu from 'primevue/panelmenu';
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import Fieldset from 'primevue/fieldset';


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
let ID_PASIEN = useRoute().query.nocmfk ? useRoute().query.nocmfk : props.registrasi.nocmfk as string

const { y } = useWindowScroll()
const selectedResep = ref();
const selectedRad = ref();
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const isSelesai: any = ref(false)
const isLoadTmb: any = ref(false)
const showData: any = ref(false)
const showDataRad: any = ref(false)
let hasilPenunjangs: any = ref([])
const sourceDataLab = ref([])
const sourceDataRad = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  tglpelayanan: new Date(),
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})

const COLLECTION: any = ref("HasilPemeriksaanPenunjang") //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})

const confirm = useConfirm();

const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {

  input.value.dokterDPJP = { label: props.registrasi.dokter, value: props.registrasi.objectpegawaifk }
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        response[0].statusSelesai ? isSelesai.value = response[0].statusSelesai : isSelesai.value = false
      }
    })
}


const SourceHasilLab = async () => {
  await useApi().get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    sourceDataLab.value = response
  })
}

const getHasilRad = async () => {
  await useApi().get(`/emr/get-hasil-radiologi-emr?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    sourceDataRad.value = response
  })
}

const simpan = async () => {
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
  useApi().post(`/emr/simpan-emr`, json).then((response: any) => {
    isLoading.value = false
    NOREC_EMRPASIEN.value = response.norec_emr
    input.value.id = response.id
  }).catch((e: any) => {
    isLoading.value = false
  })
}

const show = () => {
  showData.value = true
}

const showRad = () => {
  showDataRad.value = true
}


const kembaliKeun = () => {
  window.history.back()
}

const tambahHasilLab = async (e: any) => {
  let noorder = []
  let nourut = []
  let sementara = []
  item.sourcePemeriksaanPenunjang = ''
  isLoadTmb.value = true
  e.forEach((element: any) => {
    nourut = [...new Set([...nourut, element.no_urut])]
    noorder = [...new Set([...noorder, element.no_order])]
  });
  await useApi().get(`/laboratorium/hasil-lab?noregistrasi=${props.registrasi.noregistrasi}&nourut=${nourut}&noorder=${noorder}`).then((response) => {
    response.forEach(element => {
      sementara = [...new Set([...sementara, element.hasillab])]
    });
    sementara.forEach(element => {
      item.sourcePemeriksaanPenunjang += element
    });
    isLoadTmb.value = false
  })

  hasilPenunjangs.value.forEach(element => {
    item.sourcePemeriksaanPenunjang += element
  });
}

const tambahHasilRad = async (e: any) => {
  let tanggalreport = []
  let sementara = []
  item.sourcePemeriksaanPenunjangRad = ''
  isLoadTmb.value = true
  e.forEach((element: any) => {
    tanggalreport = [...new Set([...tanggalreport, element.tanggalreport])]
  });
  await useApi().get(`/emr/get-hasil-radiologi-emr?noregistrasi=${props.registrasi.noregistrasi}&norec_pd=${props.registrasi.norec_pd}&tanggalreport=${tanggalreport}`).then((response) => {
    response.forEach(element => {
      sementara = [...new Set([...sementara, element.hasilexpertise])]
    });
    sementara.forEach(element => {
      item.sourcePemeriksaanPenunjangRad += element
    });
    isLoadTmb.value = false
  })

  hasilPenunjangs.value.forEach(element => {
    item.sourcePemeriksaanPenunjangRad += element
  });
}

const simpanHasilPenunjang = (e: any) => {
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  if (!input.value.pemeriksaPenunjang) {
    input.value.pemeriksaPenunjang = ''
  }
  if (input.value.pemeriksaPenunjang !== '') {
    input.value.pemeriksaPenunjang += '\n'
  }

  input.value.pemeriksaPenunjang += e
  showData.value = false
}


const simpanHasilPenunjangRad = (e: any) => {
  if (!e) {
    H.alert('error', 'Hasil Penunjang Kosong')
    return
  }

  if (!input.value.pemeriksaPenunjang) {
    input.value.pemeriksaPenunjang = ''
  }
  if (input.value.pemeriksaPenunjang !== '') {
    input.value.pemeriksaPenunjang += '\n'
  }

  input.value.pemeriksaPenunjang += e
  showDataRad.value = false
}


setView()
onMounted(async () => {
  isLoading.value = true
  await SourceHasilLab()
  await getHasilRad()
  await loadRiwayat()
  isLoading.value = false
})

</script>

<style lang="scss">
.label-ass {
  font-weight: 500;
}

.title-ass {
  font-weight: bold;
}

.table-po {
  width: 100% !important;
  border-collapse: collapse !important;
}

.table-po,
.tr-po,
.th-po,
.td-po {
  border: 0.5px solid black !important;
}

.th-po,
.td-po {
  padding: 8px !important;
}

.bold {
  font-weight: bold;
}

.label-unset {
  text-overflow: unset !important;
}

.pembungkus {
  height: 100% !important;
  border-top: 2px solid var(--blue-500);
}

.bt-color {
  height: 100% !important;
  border-top: 2px solid var(--nonaktif);
}

.hidde-card {
  border: none;
  padding-top: 0px;
  padding-bottom: 0px;
}
</style>
