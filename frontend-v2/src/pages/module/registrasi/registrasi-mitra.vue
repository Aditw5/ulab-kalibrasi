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
                <VAvatar size="medium" :picture="'/images/avatars/svg/vuero-1.svg'" squared />
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
                  <h4 class="block-heading">Tanggal Daftar</h4>
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
              <RouterLink :to="{ name: 'module-registrasi-mitra-lama', }" v-if="item.NOREC_PD">
                <VIconButton class="mr-5 is-pulled-right" type="button" color="info" rounded circle raised
                  icon="fas fa-users" v-tooltip.bubble="'Mitra Lama'">
                </VIconButton>
              </RouterLink>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                Batal
              </VButton>
              <VButton type="button" color="primary" rounded outlined raised icon="feather:save" :loading="isLoading"
                @click="saveRegistrasi()"> Simpan
              </VButton>
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
            <VField horizontal label="Nama Penanggung Jawab">
              <VControl fullwidth>
                <VInput v-model="item.namapenanggungjawab" placeholder="Nama Penanggung Jawab" />
              </VControl>
            </VField>
            <VField horizontal label="No.Hp Penanggung Jawab">
              <VControl fullwidth>
                <VInput type="number" v-model="item.nohppenanggungjawab" placeholder="No.HP Penanggung Jawab" />
              </VControl>
            </VField>
            <VField horizontal label="Jabatan Penanggung Jawab">
              <VControl fullwidth>
                <VInput v-model="item.jabatanpenanggungjawab" placeholder="Jabatan Penanggung Jawab" />
              </VControl>
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
                <AutoComplete v-model="item.lokasi" :suggestions="d_lokasikalibrasi"
                  @complete="fetchlokasiKalibrasi($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <span class="label-pengkajian"> Rentang Ukur</span>
            <div class="columns is-multiline p-3">
              <div class="column is-2">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.rentangUkur" true-value="standarLab" label="Standar Lab" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.rentangUkur" true-value="permintaanPelanggan" label="Permintaan Pelanggan"
                      class="p-0" color="primary" square />
                  </VControl>
                </VField>
              </div>
              <div class="column is-3">
                <VField>
                  <VControl raw subcontrol>
                    <VCheckbox v-model="item.rentangUkur" true-value="lainLain" label="Lain-Lain" class="p-0"
                      color="primary" square />
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline px-3">
              <div class="column is-12" v-if="item.rentangUkur == 'permintaanPelanggan'">
                <VField label="Keterangan Permintaan Pelanggan">
                  <VTextarea rows="2" placeholder="Permintaan Pelanggan......"
                    v-model="item.rentangUkurketPermintaanPelanggan">
                  </VTextarea>
                </VField>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <VField horizontal label="Paket Kalibrasi" class="is-rounded-select_Z  is-autocomplete-select">
              <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                <AutoComplete v-model="item.paketkalibrasi" :suggestions="d_paketkalibrasi"
                  @complete="fetchpaketKalibrasi($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <Fieldset legend="- Order Alat" :toggleable="true">
            <div class="mt-5 form-section-inner is-horizontal">
              <!-- wrapper untuk scroll horizontal di tablet/dekstop kecil -->
              <div class="table-responsive">
                <table class="table-po" width="100%">
                  <thead>
                    <tr class="tr-po">
                      <th class="th-po" width="25%" style="text-align:center;">Nama Alat</th>
                      <th class="th-po" width="15%" style="text-align:center;">Merk</th>
                      <th class="th-po" width="15%" style="text-align:center;">Tipe</th>
                      <th class="th-po" width="15%" style="text-align:center;">S/N</th>
                      <th class="th-po" width="15%" style="text-align:center;">Lingkup Kalibrasi</th>
                      <th class="th-po" width="8%" style="text-align:center;">Aksi</th>
                    </tr>
                  </thead>
                  <tbody v-for="(items, index) in input.detailOrderAlat" :key="index">
                    <tr class="tr-po">
                      <td class="td-po" data-label="Nama Alat">
                        <div class="column pt-3 pb-0">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="items.alat" :suggestions="d_produk" @complete="fetchProduk($event)"
                                optionLabel="label" :dropdown="true" :minLength="3" class="is-input" appendTo="body"
                                loadingIcon="pi pi-spinner" field="label" placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po" data-label="Merk">
                        <div class="column pt-3 pb-0">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="items.merkalat" :suggestions="d_merk" @complete="fetchmerk($event)"
                                optionLabel="label" :dropdown="true" :minLength="3" class="is-input" appendTo="body"
                                loadingIcon="pi pi-spinner" field="label" placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po" data-label="Tipe">
                        <div class="column pt-3 pb-0">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="items.tipealat" :suggestions="d_tipe" @complete="fetchtipe($event)"
                                optionLabel="label" :dropdown="true" :minLength="3" class="is-input" appendTo="body"
                                loadingIcon="pi pi-spinner" field="label" placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po" data-label="S/N">
                        <div class="column pt-3 pb-0">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="items.serialnumber" :suggestions="d_sn"
                                @complete="fetchserialnumber($event)" optionLabel="label" :dropdown="true"
                                :minLength="3" class="is-input" appendTo="body" loadingIcon="pi pi-spinner"
                                field="label" placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po" data-label="Lingkup Kalibrasi">
                        <div class="column pt-3 pb-0">
                          <VField>
                            <VControl>
                              <AutoComplete v-model="items.lingkupkalibrasi" :suggestions="d_lingkup"
                                @complete="fetchLingkup($event)" optionLabel="label" :dropdown="true" :minLength="3"
                                class="is-input" appendTo="body" loadingIcon="pi pi-spinner" field="label"
                                placeholder="ketik untuk mencari..." />
                            </VControl>
                          </VField>
                        </div>
                      </td>
                      <td class="td-po" data-label="Aksi" style="vertical-align:inherit;">
                        <div class="column is-12 pl-0 pr-0">
                          <VButtons style="justify-content: space-around;">
                            <VIconButton type="button" raised circle icon="feather:plus"
                              v-tooltip-prime.bottom="'Tambah'" @click="addNewAlat()" outlined color="info" />
                            <VIconButton type="button" raised circle icon="feather:trash"
                              v-tooltip-prime.bottom="'Hapus'" @click="removeAlat(items)" outlined color="danger" />
                          </VButtons>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
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
import * as qzService from '/@src/utils/qzTrayService'
import Fieldset from 'primevue/fieldset';

useHead({
  title: 'Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let ID_MITRA = useRoute().query.nomitrafk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
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
const d_paketkalibrasi = ref([])
const d_lingkup = ref([])
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
    `registrasi/produk-by-id?idmitra=${ID_MITRA}&param_search=namaproduk&query=${filter.query}`).then((response) => {
      d_produk.value = response.data.map((e: any) => {
        return { label: e.namaproduk, value: e.id }
      })
    })
}

// const fetchProduk = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/produk_m?select=id,namaproduk&param_search=namaproduk&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_produk.value = response
//   })
// }

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

const fetchpaketKalibrasi = async (filter: any) => {
  await useApi().get(
    `registrasi/dropdown-paket-kalibrasi?query=${filter.query}&limit=10`
  ).then((response) => {
    d_paketkalibrasi.value = response
    console.log(response)
  })
}

const fetchLingkup = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/lingkupkalibrasi_m?select=id,lingkupkalibrasi&param_search=lingkupkalibrasi&query=${filter.query}&limit=10`
  ).then((response) => {
    d_lingkup.value = response
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
  if (!item.namapenanggungjawab) { H.alert('warning', 'Nama Penanggung Jawab harus di isi'); return }
  if (!item.jabatanpenanggungjawab) { H.alert('warning', 'Jabatan Penanggung Jawab harus di isi'); return }
  if (!item.rentangUkur) { H.alert('warning', 'Rentang Ukur harus di isi'); return }

  const mappedOrderAlat = input.value.detailOrderAlat.map((alat: any) => ({
    namaalatfk: alat.alat?.value || null,
    namamerkfk: alat.merkalat?.value || null,
    namatipefk: alat.tipealat?.value || null,
    serialnumberfk: alat.serialnumber?.value || null,
    lingkupkalibrasifk: alat.lingkupkalibrasi?.value || null
  }));

  let json = {
    'mitraregistrasi': {
      'norec': item.NOREC_PD ? item.NOREC_PD : '',
      'nomitrafk': ID_MITRA,
      'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
      'catatan': item.catatan ? item.catatan : null,
      'statusmitra': STATUSMITRA ? STATUSMITRA : 'LAMA',
      'namaperusahaan': mitra.value.namaperusahaan,
      'lokasikalibrasi': item.lokasi?.value ?? null,
      'paketkalibrasi': item.paketkalibrasi?.value ?? null,
      'namapenanggungjawab': item.namapenanggungjawab,
      'nohppenanggungjawab': item.nohppenanggungjawab,
      'jabatanpenanggungjawab': item.jabatanpenanggungjawab,
      'rentangUkur': item.rentangUkur,
      'rentangUkurketPermintaanPelanggan': item.rentangUkurketPermintaanPelanggan ?? null,
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

const toDashboard = () => {
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

.th-po,
.td-po {
  padding: 8px !important;
}

.table-responsive {
  width: 100%;
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
}

.table-responsive .table-po {
  min-width: 900px;
  /* sesuaikan jika perlu */
}

/* Stacked table di layar ≤768px */
@media only screen and (max-width: 768px) {

  .table-po,
  .tr-po,
  .th-po {
    display: block;
  }

  .th-po {
    position: absolute;
    top: -9999px;
    left: -9999px;
  }

  .td-po {
    display: block;
    position: relative;
    padding-left: 50%;
    border: none;
    border-bottom: 1px solid #ddd;
    text-align: left;
  }

  .td-po:before {
    position: absolute;
    top: 0;
    left: 0;
    width: 45%;
    padding-right: 10px;
    white-space: nowrap;
    content: attr(data-label);
    font-weight: bold;
  }
}


// .dropdown.is-dots .is-trigger {
//     background: var(--red) !important;
// }
// .dropdown.is-dots .is-trigger svg {
//     color: white;
// }</style>
