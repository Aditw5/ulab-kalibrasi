<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-12">
        <div class="columns is-multiline">
          <!--Header-->
          <div class="column is-12">
            <div class="illustration-header-2">
              <div class="header-image">
                <img src="/@src/assets/illustrations/dashboards/lifestyle/Picture6.png" alt=""
                  style="max-width:75%; margin-left: 2rem; margin-bottom: 1rem;" />
              </div>
              <div class="header-meta">
                <h3 style="color:white"><i class="fas fa-home"></i> Dashboard Pelaksana</h3>
                <p>
                  Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                </p>
              </div>
            </div>
          </div>
          <div class="column is-12">
            <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
              <TabPanel>
                <template #header>
                  <i class="fas fa-users mr-2" aria-hidden="true"></i>
                  <span>Daftar Alat</span>
                  <Badge :value="dataAlatKalibrasi.length" v-if="dataAlatKalibrasi.length > 0" severity="danger"
                    class="ml-2" />
                </template>

                <div v-if="activeTab == 0">
                  <div class="column is-6"
                    style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                      <template #default="{ inputValue, inputEvents }">
                        <VField addons>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                          </VControl>
                          <VControl>
                            <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                          </VControl>
                          <VControl icon="feather:calendar">
                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                          </VControl>
                        </VField>
                      </template>
                    </VDatePicker>

                  </div>

                  <div class="list-view list-view-v3">
                    <div class="search-menu-igd mb-2">
                      <div class="search-location-igd" style="width: 100%">
                        <i class="iconify" data-icon="feather:search"></i>
                        <input type="text" placeholder="Cari Nama Alat, No Oerder Alat, No Pendaftaran"
                          v-model="item.qsearch" v-on:keyup.enter="fetchAlatKalibrasi(order)" />
                      </div>
                      <VButton raised class="search-button-igd" @click="fetchAlatKalibrasi(order)" :loading="isLoading">
                        Cari
                        Data
                      </VButton>
                    </div>
                    <VCard class="text-center pt-0 pb-0 mt-0">
                      <VRadio v-model="order" value="0" label="Belum Verif" name="outlined_radio" color="success" />
                      <VRadio v-model="order" value="1" label="Sudah   Verif" name="outlined_radio" color="info" />
                    </VCard>
                    <VPlaceholderPage :class="[dataAlatKalibrasi.length !== 0 && 'is-hidden']"
                      title="Tidak Ada Alat Hari Ini." subtitle="Silakan Pilih Tanggal" larger>
                      <template #image>
                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.png" alt="" />
                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                          alt="" />
                      </template>
                    </VPlaceholderPage>
                    <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                      <div name="list-complete" tag="div">
                        <div v-for="(item, rowIndex) in dataAlatKalibrasi" :key="rowIndex">
                          <div v-if="rowGroupMetadata[item.lingkupkalibrasi].index === rowIndex">
                            <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                              item.lingkupkalibrasi }}</span>
                            <Badge :value="rowGroupMetadata[item.lingkupkalibrasi].size"
                              v-if="rowGroupMetadata[item.lingkupkalibrasi].size > 0" class="ml-2 mt-2-min" />
                          </div>
                          <div class="list-view-item ">
                            <div class="list-view-item-inner">
                              <VAvatar size="small" picture="/images/avatars/svg/propinsi.svg" color="primary"
                                bordered />
                              <div class="meta-left">
                                <h3>
                                  {{ item.namaproduk }}
                                </h3>
                                <span>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:calendar"></i>
                                  <span>{{ item.tglverifasman }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                  <span>{{ item.noorderalat }}</span>
                                  <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                  <i aria-hidden="true" class="iconify" data-icon="feather:check-circle"></i>
                                  <span>{{ item.nopendaftaran }}</span>

                                </span>
                                <div>
                                  <VTag v-if="item.statusPengerjaan == null && item.jenisorder == 'kalibrasi'"
                                    :label="'Durasi Kalibrasi : ' + item.durasikalbrasi" :color="'warning'"
                                    class="ml-2" />
                                  <VTag v-if="item.statusPengerjaan != null" :label="item.statusPengerjaan"
                                    :color="item.statusColor" class="ml-2" />
                                  <VTag
                                    v-if="item.pelaksanaisilembarkerjafk != null && (item.setujuilembarkerjapenyelia == null || item.setujuilembarkerjapenyelia == false)"
                                    :label="'Sudah Isi Lembar Kerja'" :color="'info'" class="ml-2" />
                                  <VTag v-if="item.pelaksanaisilaporanrepairfk != null"
                                    :label="'Sudah Isi Laporan Repair'" :color="'success'" class="ml-2" />
                                  <VTag
                                    v-if="item.setujuilembarkerjapenyelia != null && item.setujuilembarkerjapenyelia == true"
                                    :label="'Sertifikat Disetujui Penyelia'" :color="'primary'" class="ml-2" />
                                  <VTag
                                    v-if="item.setujuilembarkerjaasman != null && item.setujuilembarkerjaasman == true"
                                    :label="'Sertifikat Disetujui Asman'" :color="'primary'" class="ml-2" />
                                </div>
                                <div>
                                  <span style="font-weight: bold;">Penyelia Teknik :
                                    {{ item.penyeliateknik ?? '-' }}
                                  </span>
                                </div>
                                <div>
                                  <span style="font-weight: bold;">Pelaksana Teknik :
                                    {{ item.pelaksanateknik ?? '-' }}
                                  </span>
                                </div>
                              </div>
                              <div class="meta-right flex justify-center items-center">
                                <div class="buttons">
                                  <VIconButton v-tooltip.bottom.left="'SPK'" icon="feather:printer"
                                    @click="cetakSpk(item)" color="warning" raised circle class="mr-2">
                                  </VIconButton>
                                  <VIconButton
                                    v-if="item.setujuilembarkerjaasman != null && item.setujuilembarkerjaasman == true"
                                    v-tooltip.bottom.left="'Cetak Sertifikat'" icon="feather:printer"
                                    @click="cetakSertifikatLembarKerja(item)" color="info" raised circle class="mr-2">
                                  </VIconButton>
                                  <VIconButton
                                    v-if="item.statusorderpelaksana == 1 && (item.setujuilembarkerjaasman == null || item.setujuilembarkerjaasman == false) && item.jenisorder == 'kalibrasi'"
                                    color="info" circle icon="fas fa-pager" outlined raised @click="lembarKerja(item)"
                                    v-tooltip.bottom.left="'Lembar Kerja'" />
                                  <VIconButton v-if="item.statusorderpelaksana == 1 && item.jenisorder == 'repair' && item.pelaksanaisilaporanrepairfk != null"
                                    v-tooltip.bottom.left="'Cetak Laporan Repair'" icon="feather:printer"
                                    @click="cetakLaporanRepair(item)" color="success" raised circle class="mr-2">
                                  </VIconButton>
                                  <VIconButton v-if="item.statusorderpelaksana == 1 && item.jenisorder == 'repair'"
                                    color="info" circle icon="fas fa-tools" outlined raised @click="laporanRepair(item)"
                                    v-tooltip.bottom.left="'Laporan Repair'" />
                                  <VIconButton v-tooltip.bottom.left="'Verifikasi'" label="Bottom Left" color="primary"
                                    circle icon="pi pi-check-circle" v-if="item.statusorderpelaksana == 0"
                                    @click="orderVerify(item)" />
                                  <VIconButton v-tooltip.bottom.left="'Aktivitas'" icon="feather:activity"
                                    v-if="item.statusorderpelaksana == 1" @click="detailOrder(item)" color="info" raised
                                    circle class="mr-2">
                                  </VIconButton>
                                  <!-- <VIconButton color="primary" circle icon="pi pi-ellipsis-v" raised
                                    @click="toggleOP($event, item)" v-tooltip.bottom.left="'TINDAKAN'">
                                  </VIconButton> -->
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </TabPanel>

            </TabView>

          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- <OverlayPanel ref="op" appendTo="body" style="width:300px">
    <div class="columns is-multiline">
      
    </div>

  </OverlayPanel> -->
  <VModal :open="modalDetailOrder" title="Verifikasi" noclose size="big" actions="right"
    @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
    <template #content>
      <div class="column is-12">
        <Fieldset legend="Data Alat" :toggleable="true">
          <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataOrder">
            <div class="columns is-multiline">
              <div class="column is-2" style="margin-top: 27px;">
                <VPlaceload class="mx-2" />
              </div>
              <div class="column">
                <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
              </div>

            </div>
          </div>
          <div class="timeline-wrapper" v-else>
            <div class="timeline-wrapper-inner">
              <div class="timeline-container">
                <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan" :key="items.norec">
                  <div :class="'dot is-' + listColor[index + 1]"></div>

                  <div class="content-wrap is-grey">
                    <div class="content-box">
                      <div class="status"></div>
                      <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                        <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                      </VIconBox>
                      <div class="box-text" style="width:70%">
                        <div class="meta-text">
                          <p>
                            <span>{{ items.namaproduk }}</span>
                          </p>
                          <table class="tb-order">
                            <tr>
                              <td>Lingkup</td>
                              <td>:</td>
                              <td>{{ items.lingkupkalibrasi }} </td>
                            </tr>
                            <tr>
                              <td>Lokasi</td>
                              <td>:</td>
                              <td v-if="items.jenisorder == 'kalibrasi'">{{ items.lokasi }} </td>
                              <td v-if="items.jenisorder == 'repair'">{{ items.lokasirepair }} </td>
                            </tr>
                            <tr>
                              <td v-if="items.jenisorder == 'kalibrasi'">Penyelia Teknik </td>
                              <td v-if="items.jenisorder == 'repair'">Penyelia Teknik Repair </td>
                              <td>:</td>
                              <td class="font-values">{{ items.penyeliateknik }}</td>
                            </tr>
                            <tr>
                              <td v-if="items.jenisorder == 'kalibrasi'">Pelaksana Teknik</td>
                              <td v-if="items.jenisorder == 'repair'">Pelaksana Teknik Repair</td>
                              <td>:</td>
                              <td>{{ items.pelaksanateknik }} </td>
                            </tr>
                            <tr v-if="items.jenisorder == 'kalibrasi'">
                              <td>Durasi</td>
                              <td>:</td>
                              <td>
                                <VTag v-if="items.durasikalbrasi" color="warning" rounded> {{ items.durasikalbrasi }}
                                </VTag>
                              </td>
                            </tr>
                          </table>

                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="save(item)" color="info" :loading="isLoadingSave" raised>Simpan
        Verif
      </VButton>
    </template>
  </VModal>
  <VModal :open="modalRiwayat" noclose size="big" actions="right" @close="modalRiwayat = false, clear()"
    cancelLabel="Tutup">
    <template #content>
      <div class="column">
        <div class="business-dashboard hr-dashboard">
          <div class="columns is-multiline">
            <div class="column is-12 p-0">
              <div class="block-header">
                <div class="left column is-6 p-0">
                  <div class="current-user">
                    <h3>{{ item.namaproduk }}</h3>
                  </div>
                </div>
                <div class="left column is-6 p-0">
                  <div>
                    <div>
                      <h4 class="block-heading">Merk</h4>
                      <p class="block-hext">{{ item.namamerk }}</p>
                      <h4 class="block-heading">Tipe</h4>
                      <p class="block-hext">{{ item.namatipe }}</p>
                    </div>
                  </div>
                </div>
                <div class="center column is-6 p-0">
                  <div>
                    <div>
                      <h4 class="block-heading">S/N</h4>
                      <p class="block-hext">{{ item.namaserialnumber }}</p>
                      <h4 class="block-heading">Durasi</h4>
                      <p class="block-hext">{{ item.durasikalbrasi }}</p>
                    </div>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
      <div class="column is-12">
        <Fieldset legend="Data Alat" :toggleable="true">
          <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataDeatilOrder">
            <div class="columns is-multiline">
              <div class="column is-2" style="margin-top: 27px;">
                <VPlaceload class="mx-2" />
              </div>
              <div class="column">
                <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
              </div>

            </div>
          </div>
          <div class="timeline-wrapper" v-else>
            <div class="timeline-wrapper-inner">
              <div class="timeline-container">
                <div class="timeline-item is-unread" v-for="(item, index) in timelineItems" :key="index">
                  <div class="date">
                    <span>{{ H.formatDateIndo(item.date) }}</span>
                  </div>
                  <div :class="'dot is-' + listColor[index + 1]"></div>
                  <div class="content-wrap is-grey">
                    <div class="content-box">
                      <div class="status"></div>
                      <div class="box-text" style="width:70%">
                        <div class="meta-text">
                          <p>
                            <span>
                              {{ item.type }} : {{ item.nama }}
                            </span>
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>
    </template>
    <template #action>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, reactive, watch, inject } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { useHead } from '@vueuse/head'
import moment, { isDate } from 'moment'
import ApexChart from 'vue3-apexcharts'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import TabView from 'primevue/tabview';
import MultiSelect from 'primevue/multiselect';
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import TabPanel from 'primevue/tabpanel';
import Badge from 'primevue/badge';
import RadioButton from 'primevue/radiobutton';
import OverlayPanel from 'primevue/overlaypanel';
import { state, socket } from "/@src/socket.js";
import * as qzService from '/@src/utils/qzTrayService'

useHead({
  title: 'Dashboard Pelaksana - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const op = ref();
const activeTab = ref(0)
const date = new Date();
let listColor: any = ref(Object.keys(useThemeColors()))
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalDetailDokter = ref(false)
const modalDetailProduk = ref(false)
const modalDetailKamar = ref(false)
const modalChangeDokter = ref(false)
const modalPenanda = ref(false)
const modalPesanRuangan = ref(false)
const modalInputPulang = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const isLoadingCall = ref(false)
const btnLoadSimpan = ref(false)
const showPindah = ref(false)
const showPulang = ref(false)
const showMeninggal = ref(false)
const showRujuk = ref(false)
const isbtnLoadPrint = ref(false)
const isbtnLoadBatalPulang = ref(false)
const route = useRoute()
const rowGroupMetadata = ref({})
const selectedItem: any = ref({})
const isLoadingPop: any = ref(false)
let modalDetailOrder: any = ref(false)
let modalRiwayat: any = ref(false)
let isLoadDataOrder: any = ref(false)
let isLoadingSave: any = ref(false)
let detailOrderLayanan: any = ref(0)
let isLoadDataDeatilOrder: any = ref(false)
const timelineItems = ref([])
const order: any = ref(0)
const item: any = ref({
  aktif: true,
  fStatusVerif: 0,
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
})
const chart: any = ref({
  aktif: true
})

const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
let dataSource: any = ref([])
let dataAlatKalibrasi: any = ref([])
let isLoading: any = ref(false)
let isLoadingTT: any = ref(false)
const filters = ref('')

const fetchAlatKalibrasi = async (q: any) => {
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
  }
  let status = ''

  let statusorderpelaksana = ''
    , search = ''
  item.value.statusorderpelaksana = q
  if (order) statusorderpelaksana = '&statusorderpelaksana=' + q
  if (item.value.qsearch) search = item.value.qsearch
  isLoading.value = true
  dataAlatKalibrasi.value = []
  const response = await useApi().get(
    '/pelaksana/get-alat-pelaksana?dari=' + dari
    + '&sampai=' + sampai
    + '&search=' + search
    + statusorderpelaksana
  )
  isLoading.value = false
  response.data.sort(compare);
  dataAlatKalibrasi.value = response.data

  updateRowGroupMetaData();

}

const orderVerify = async (e: any) => {
  console.log(e)
  detailOrderLayanan.value = []
  modalDetailOrder.value = true
  item.value.norec = e.norec
  item.value.norec_detail = e.norec_detail
  // getListPelayanan(data)
  isLoadDataOrder.value = true
  const response = await useApi().get(`/pelaksana/layanan-verif-pelaksana?norec_pd=${e.norec_detail}`)
  response.detail.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  isLoadDataOrder.value = false
  detailOrderLayanan.value = response.detail
}


const detailOrder = async (e) => {
  modalRiwayat.value = true
  item.value.namaproduk = e.namaproduk
  item.value.namamerk = e.namamerk
  item.value.namatipe = e.namatipe
  item.value.namaserialnumber = e.namaserialnumber
  item.value.durasikalbrasi = e.durasikalbrasi
  isLoadDataDeatilOrder.value = true
  const response = await useApi().get(`/pelaksana/detail-produk?norec_pd=${e.norec_detail}`)
  timelineItems.value = response.timeline
  isLoadDataDeatilOrder.value = false
}


const filter = async () => {
  item.isDate = false;
  fetchAlatKalibrasi();
}

const save = async (e: any) => {
  console.log(e)
  let json = {
    'verif': {
      'norec': e.norec_detail ?? '',
    }
  }
  isLoadingSave.value = true
  await useApi().post('/pelaksana/save-verif', json).then((r) => {
    isLoadingSave.value = false
    modalDetailOrder.value = false
    fetchAlatKalibrasi(0)
  }).catch((error: any) => {
    isLoadingSave.value = false
    console.error('Error saat menyimpan berkas mitra:', error);

    if (error.response) {

      H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas mitra'}`);
    } else if (error.request) {

      H.alert('error', 'Tidak ada respons dari server. Silakan coba lagi.');
    } else {

      H.alert('error', `Terjadi kesalahan: ${error.message}`);
    }
  })
}

const cetakSpk = (e) => {
  // console.log(e)

  H.printBlade(`pelaksana/cetak-spk?pdf=true&norec=${e.norec}&pelaksanateknikfk=${e.pelaksanateknikfk}`);
}

const cetakSertifikatLembarKerja = (e) => {
  H.printBlade(`pelaksana/cetak-sertifikat-lembar-kerja?pdf=true&norec=${e.norec}&norec_detail=${e.norec_detail}`);
}


const cetakLaporanRepair = (e) => {
  H.printBlade(`penyelia/cetak-laporan-repair?pdf=true&norec=${e.norec}&norec_detail=${e.norec_detail}`);
}


const compare = (a: any, b: any) => {
  if (a.lingkupkalibrasi > b.lingkupkalibrasi) {
    return -1;
  }
  if (a.lingkupkalibrasi < b.lingkupkalibrasi) {
    return 1;
  }
  return 0;
}

const klikTab = (e: any) => {
  activeTab.value = e.index
  if (activeTab.value == 0) {
    fetchAlatKalibrasi(0)
  }
}

const lembarKerja = (e: any) => {
  router.push({
    name: 'module-pelaksana-lembar-kerja',
    query: {
      norec: e.norec,
      norec_detail: e.norec_detail,
    }
  })
}

const laporanRepair = (e: any) => {
  router.push({
    name: 'module-pelaksana-laporan-repair',
    query: {
      norec: e.norec,
      norec_detail: e.norec_detail,
    }
  })
}


const toggleOP = (event: any, item: any) => {
  selectedItem.value = item
  op.value.toggle(event);
}

const kirimWASuratKeteranganDokter = async (e: any) => {
  isLoadingPop.value = true;

  try {
    if (!e.namalengkap || !e.kelompokpasien || !e.tglregistrasi || !e.norec_pd) {
      throw new Error("Data tidak lengkap. Harap isi semua informasi yang diperlukan.");
    }

    const response = await useApi().post('/dashboard/send-WA-IGD', {
      dokter: e.namalengkap,
      kelompokpasien: e.kelompokpasien,
      objectdepartemenfk: '9',
      tglregistrasi: e.tglregistrasi,
      norec_pd: e.norec_pd,
    });

    if (response && response.data) {
      console.log("Response berhasil:", response.data);
    } else {
      console.warn("Response dari server tidak valid:", response);
    }
  } catch (error: any) {
    console.error("Gagal mengirim WA:", error.message || error);
  } finally {
    isLoadingPop.value = false;
  }
}

const updateRowGroupMetaData = () => {
  rowGroupMetadata.value = {};

  if (dataAlatKalibrasi.value) {
    for (let i = 0; i < dataAlatKalibrasi.value.length; i++) {
      let rowData = dataAlatKalibrasi.value[i];
      let lingkupkalibrasi = rowData.lingkupkalibrasi;

      if (i == 0) {
        rowGroupMetadata.value[lingkupkalibrasi] = { index: 0, size: 1 };
      }
      else {
        let previousRowData = dataAlatKalibrasi.value[i - 1];
        let previousRowGroup = previousRowData.lingkupkalibrasi;
        if (lingkupkalibrasi === previousRowGroup) {
          rowGroupMetadata.value[lingkupkalibrasi].size++;
        }
        else {
          rowGroupMetadata.value[lingkupkalibrasi] = { index: i, size: 1 };
        }
      }
    }
  }
}


const changeSwitch = (e: any) => {
  fetchAlatKalibrasi(e)
}


watch(
  () => [
    order.value
  ], () => {
    changeSwitch(order.value)
  }
)

fetchAlatKalibrasi(0)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/penyelia.scss';
@import '/@src/scss/module/dashboard/bedah.scss';

.hide {
  display: hidden !important;
}
</style>