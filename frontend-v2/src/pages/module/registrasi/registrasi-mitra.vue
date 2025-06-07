<template>
  <div class="form-layout is-stacked">
    <div class="business-dashboard hr-dashboard">
      <div class="columns is-multiline">
        <div class="column is-12" v-if="isLoadingMitra">
          <div class="block-green">
            <div class="flex-list-inner mb-4">
              <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12" v-if="!isLoadingMitra">
          <div class="block-header">
            <div class="left">
              <div class="current-user">
                <!-- <VAvatar size="medium" :picture="mitra.jeniskelamin ==
                  'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'" squared /> -->
                <h3>{{ mitra.namaperusahaan }}</h3>
              </div>
            </div>
            <div class="center">
              <div class="columns">
                <div class="column">
                  <h4 class="block-heading">No HP</h4>
                  <p class="block-text">{{ mitra.nohp }}</p>
                  <h4 class="block-heading">Alamat</h4>
                  <p class="block-text">{{ mitra.alamatktr }}
                  </p>
                </div>
                <div class="column">
                  <h4 class="block-heading">Email </h4>
                  <p class="block-text"> {{ mitra.email }}</p>
                  <h4 class="block-heading">Tanggal</h4>
                  <p class="block-text"> {{ mitra.tgldaftar }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">

          </div>
          <div class="right">
            <div class="buttons">
              <VDropdown icon="feather:more-vertical" spaced right v-if="item.NOREC_PD" class="mt-1-min mr-2"
                v-tooltip.bubble="'CETAK'">
                <template #content>
                  <a @click="cetakBuktiPendaftaran(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Poli Antrian</span>
                      <span>Cetak Nomor</span>
                    </div>
                  </a>
                </template>
              </VDropdown>
              <RouterLink :to="{ name: 'module-registrasi-mitra-lama', }" v-if="item.NOREC_PD">
                <VIconButton class="mr-5 is-pulled-right" type="button" color="info" rounded circle raised
                  icon="fas fa-users" v-tooltip.bubble="'Mitra Lama'">
                </VIconButton>
              </RouterLink>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                Batal
              </VButton>
              <VButton type="button" color="primary" rounded outlined raised icon="feather:save"
                @click="saveRegistrasi()"> Simpan </VButton>

            </div>
          </div>
        </div>
      </div>
      <div class="form-body">
        <div class="form-section is-grey">
          <div class="form-section-header">
            <div class="left">
            </div>
            <div class="right">
            </div>
          </div>
          <div class="form-section-inner is-horizontal">
            <VField horizontal label=" Tanggal">
              <VDatePicker v-model="item.tglregistrasi" mode="dateTime" style="width: 100%;">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
            <VField horizontal label="Catatan">
              <VControl fullwidth>
                <VTextarea class="textarea" v-model="item.catatan" rows="4"
                  placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                  spellcheck="true" />
              </VControl>
            </VField>
              <VField horizontal label="Lokasi Kalibrasi" class="is-rounded-select_Z  is-autocomplete-select">
                <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                  <AutoComplete v-model="item.lokasi" :suggestions="d_lokasikalibrasi" @complete="fetchlokasiKalibrasi($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                </VControl>
              </VField>
          </div>
          <Fieldset legend="- Order Alat" :toggleable="true">
            <div style="overflow-y:auto;" class="mt-5 form-section-inner is-horizontal">
              <table width="100%">
                <thead>
                  <tr class="tr-po">
                    <th class="th-po" width="25%" style="vertical-align:inherit;text-align: center;">Nama Alat</th>
                    <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">Merk</th>
                    <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">Tipe</th>
                    <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">S/N</th>
                    <th class="th-po" width="8%" style="vertical-align:inherit;text-align: center;">Aksi</th>
                  </tr>
                </thead>
                <tbody v-for="(items, index) in input.detailOrderAlat" :key="index">
                  <tr class="tr-po">
                    <td class="td-po">
                      <div class="column pt-3 pb-0">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="items.alat" :suggestions="d_produk" @complete="fetchProduk($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po">
                      <div class="column pt-3 pb-0">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="items.merkalat" :suggestions="d_merk" @complete="fetchmerk($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po">
                      <div class="column pt-3 pb-0">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="items.tipealat" :suggestions="d_tipe" @complete="fetchtipe($event)"
                              :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                              :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po">
                      <div class="column pt-3 pb-0">
                        <VField>
                          <VControl>
                            <AutoComplete v-model="items.serialnumber" :suggestions="d_sn"
                              @complete="fetchserialnumber($event)" :optionLabel="'label'" :dropdown="true"
                              :minLength="3" class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                              :field="'label'" placeholder="ketik untuk mencari..." />
                          </VControl>
                        </VField>
                      </div>
                    </td>
                    <td class="td-po" style="vertical-align: inherit;">
                      <div class="column is-12 pl-0 pr-0">
                        <VButtons style="justify-content: space-around;">
                          <VIconButton type="button" raised circle icon="feather:plus" v-tooltip-prime.bottom="'Tambah'"
                            @click="addNewAlat(items)" outlined color="info">
                          </VIconButton>
                          <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Hapus'" outlined
                            icon="feather:trash" @click="removeAlat(items)" color="danger">
                          </VIconButton>
                        </VButtons>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </Fieldset>
        </div>
        <div class="form-section is-grey">
          <div class="form-section-inner is-horizontal">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToaster } from '/@src/composable/toaster'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import InfoPasien from '/@src/pages/include/info-mitra.vue';
import * as qzService from '/@src/utils/qzTrayService'
import Fieldset from 'primevue/fieldset';

useHead({
  title: 'Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

let ID_MITRA = useRoute().query.nomitrafk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_RESERVASI = useRoute().query.norec_online as string
let STATUSMITRA = useRoute().query.statusmitra as string
let NOREGISTRASI = "";

const item: any = reactive({
  tglregistrasi: new Date(),
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  NOREGISTRASI: NOREGISTRASI != undefined ? NOREGISTRASI : '',
  lokasi: null
})
const confirm = useConfirm();
const mitra: any = ref({})
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingTT: any = ref(false)
const isLoadingMitra: any = ref(false)
const d_produk = ref([])
const d_merk = ref([])
const d_tipe = ref([])
const d_sn = ref([])
const d_lokasikalibrasi = ref([])
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
  return y.value > 30
})

const input: any = ref({
  detailOrderAlat: [{
    no: 1
  }]
})


const addNewAlat = () => {
  input.value.detailOrderAlat.push({
    no: input.value.detailOrderAlat[input.value.detailOrderAlat.length - 1].no + 1
  });
}

const removeAlat = (index: any) => {
  input.value.detailOrderAlat.splice(index, 1)
  if (input.value.detailOrderAlat.length == 0) {
    input.value.detailOrderAlat.push({
      no: 1
    });
  }
}

const fetchProduk = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${filter.query}&limit=10`
  ).then((response) => {
    d_produk.value = response
  })
}

const fetchmerk = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/merkalat_m?select=id,namamerk&param_search=namamerk&query=${filter.query}&limit=10`
  ).then((response) => {
    d_merk.value = response
  })
}

const fetchtipe = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/tipealat_m?select=id,namatipe&param_search=namatipe&query=${filter.query}&limit=10`
  ).then((response) => {
    d_tipe.value = response
  })
}

const fetchserialnumber = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/serialnumber_m?select=id,namaserialnumber&param_search=namaserialnumber&query=${filter.query}&limit=10`
  ).then((response) => {
    d_sn.value = response
  })
}

const fetchlokasiKalibrasi = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/lokasikalibrasi_m?select=id,lokasi&param_search=lokasi&query=${filter.query}&limit=10`
  ).then((response) => {
    d_lokasikalibrasi.value = response
  })
}

const mitraByID = (id: any) => {
  isLoadingMitra.value = true
  let paramsEdit = ''
  if (item.NOREC_PD != '' && item.NOREC_APD != '') {
    paramsEdit = `&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`
  }
  useApi().get(
    `/registrasi/mitra-registrasi?id=${id}${paramsEdit}`).then((response: any) => {
      mitra.value = response.mitra
      isLoadingMitra.value = false
    })
}

const cancelRegistrasi = () => {
  window.history.back()
}

const saveRegistrasi = async () => {
  console.log(item)
  if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }

  const mappedOrderAlat = input.value.detailOrderAlat.map((alat: any) => ({
    namaalatfk: alat.alat?.value || null,    
    namamerkfk: alat.merkalat?.value || null,
    namatipefk: alat.tipealat?.value || null,
    serialnumberfk: alat.serialnumber?.value || null
  }));

  let json = {
    'mitraregistrasi': {
      'norec': item.NOREC_PD ? item.NOREC_PD : '',
      'nomitrafk': ID_MITRA,
      'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
      'catatan': item.catatan ? item.catatan : null,
      'statusmitra': STATUSMITRA ? STATUSMITRA : 'LAMA',
      'namaperusahaan': mitra.value.namaperusahaan,
      'lokasikalibrasi': item.lokasi.value,
    },
    'mitraregistrasidetail': mappedOrderAlat
  }

  isLoading.value = true
  await useApi().post(`/registrasi/save-registrasi-mitra`, json).then(async (response: any) => {
    isLoading.value = false
    isDisabled.value = true
    toDashboard()
  }).catch((e: any) => {
    isLoading.value = false
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

const toDashboard = (norec_pd: any) => {
  router.push({
    name: 'module-dashboard-registrasi',
  })
}

const cetakBuktiPendaftaran = (e: any) => {
  H.printBlade('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true");
  qzService.printData(`report/bukti-pendaftaran?pdf=true&noregistrasi=${e.NOREC_PD}&norec_pd=${e.NOREC_PD}`, 'BUKTI PENDAFATARN', 1);
}

const clearInput = () => {
  delete item.kelasRawat
}


mitraByID(ID_MITRA)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';


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

// .dropdown.is-dots .is-trigger {
//     background: var(--red) !important;
// }
// .dropdown.is-dots .is-trigger svg {
//     color: white;
// }</style>
