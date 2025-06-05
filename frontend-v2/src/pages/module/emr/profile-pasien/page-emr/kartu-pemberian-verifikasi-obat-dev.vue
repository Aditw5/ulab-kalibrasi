<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top: 15px">
      <div
        v-if="isStuck"
        :class="[isStuck && 'is-stuck']"
        style="margin-top: 50px; padding: 0px 0px !important"
        class="form-header stuck-header"
      >
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <!-- <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr> -->
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>{{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <!-- <ButtonEmr
              :NOREC_EMRPASIEN="NOREC_EMRPASIEN"
              :COLLECTION="COLLECTION"
              :isLoading="isLoading"
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
              @simpan="simpan"
              @kembaliKeun="kembaliKeun"
            ></ButtonEmr> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="column">
    <VCard>
      <div class="columns is-multiline">
        <div class="column is-12">
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column is-12" style="overflow: auto">
                <h1 class="mb-3 bold">Pemberian Obat</h1>
                <table class="table-kpo">
                  <thead>
                    <tr>
                      <th width="25%">Dokter</th>
                      <th width="20%">Nama Obat</th>
                      <th width="10%">Aksi</th>
                      <th width="5%">QTY</th>
                      <th width="5%">Aturan Pakai</th>
                      <th width="5%">Lama (Hari)</th>
                      <th width="5%">Tgl Mulai</th>
                      <th width="25%">Detail Pemberian</th>
                      <th width="5%">Validasi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="dataSource.length == 0">
                      <td colspan="9">
                        <div class="page-placeholder">
                          <div class="placeholder-content">
                            <img
                              class="light-image"
                              style="max-width: 340px"
                              src="/@src/assets/illustrations/placeholders/search-7.svg"
                              alt=""
                            />
                            <img
                              class="dark-image"
                              style="max-width: 340px"
                              src="/@src/assets/illustrations/placeholders/search-7-dark.svg"
                              alt=""
                            />
                            <h3>
                              {{ isLoadingDataKPO ? 'Loading.....' : 'Data belum ada.' }}
                            </h3>
                            <p class="is-larger">
                              {{
                                isLoadingDataKPO
                                  ? 'Sedang memuat data, tunggu sebentar'
                                  : 'Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu.'
                              }}
                            </p>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="data in dataSource">
                      <td>{{ data.namalengkap }}</td>
                      <td>
                        <div>
                          <VTag v-if="data.replace">
                            <span class="strikethrough-diagonal">{{
                              data.produk_order.namaproduk
                            }}</span>
                          </VTag>
                        </div>
                        <div>
                          <VTag
                            color="blue"
                            v-if="data.norec_order && data.norec_resep"
                            :label="data.namaproduk"
                          />
                          <VTag
                            color="warning"
                            v-if="!data.norec_order && data.norec_resep"
                            :label="data.namaproduk"
                          />
                          <VTag
                            v-if="data.norec_order && !data.norec_resep"
                            :label="data.namaproduk"
                          />
                        </div>
                        <!-- {{ data.namaproduk }} -->
                      </td>
                      <td align="center">
                        <div>
                          <VIconButton
                            type="button"
                            icon="feather:plus"
                            v-if="data.norec_resep && !data.iskpo"
                            @click="addKPO(data)"
                            outlined
                            light
                            color="primary"
                            :loading="loadingAddKPO"
                          >
                          </VIconButton>
                        </div>
                        <div>
                          <VButton
                            type="button"
                            v-if="data.iskpo"
                            class="w-full"
                            size="small"
                            :loading="isLoading"
                            @click="modalStopKPO(data)"
                            outlined
                            light
                            color="danger"
                          >
                            Stop
                          </VButton>
                        </div>
                        <div>
                          <VButton
                            type="button"
                            v-if="data.iskpo"
                            class="w-full mt-2"
                            size="small"
                            @click="modalGantiDosis(data)"
                            outlined
                            light
                            color="warning"
                          >
                            Ganti Dosis
                          </VButton>
                        </div>
                      </td>
                      <td>
                        <span v-if="data.iskpo">{{ data.jumlah }}</span>
                      </td>
                      <td>
                        <span v-if="data.iskpo">{{ data.aturanpakai }}</span>
                      </td>
                      <td>
                        <span v-if="data.iskpo">{{data.detailKPO.length}} dari {{ data.lamahari }}</span>
                      </td>
                      <td>
                        <span v-if="data.iskpo">{{ data.tglmulai }}</span>
                      </td>
                      <td>
                        <TabView :scrollable="true" style="width: 100%" v-if="data.iskpo">
                          <TabPanel
                            v-for="tab in data.detailKPO"
                            :key="tab.tglpemberian"
                            :header="tab.tglpemberian"
                            style="padding: 0;"
                          >
                            <div style="overflow-x: auto; width: 100%">
                              <table class="detail-kpo">
                                <tr>
                                  <td v-for="detail in tab.detail" :key="detail.id">
                                    <div>Pemberian</div>
                                    <div>{{ detail.jampemberian }}</div>
                                    <div class="text-center">
                                      <VIconButton
                                        type="button"
                                        icon="feather:check-circle"
                                        outlined
                                        circle
                                        color="success"
                                        @click="showQR(detail)"
                                      />
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </TabPanel>
                        </TabView>
                      </td>
                      <td>
                        <VIconButton
                          type="button"
                          icon="feather:plus"
                          @click="modalValidasiPemberian(data)"
                          v-if="data.iskpo"
                          outlined
                          light
                          color="primary"
                          :loading="loadingAddKPO"
                        >
                        </VIconButton>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="column is-12" style="overflow: auto">
                <h1 class="mb-3 bold">Penghentian Obat</h1>
                <table class="table-kpo">
                  <thead>
                    <tr>
                      <th width="20%">Dokter</th>
                      <th width="20%">Nama Obat</th>
                      <th width="5%">QTY</th>
                      <th width="5%">Aturan Pakai</th>
                      <th width="5%">Rencana Pemberian (Hari)</th>
                      <th width="5%">Jumlah Obat yang Telah Diberikan</th>
                      <th width="7%">Tgl Mulai</th>
                      <th width="7%">Tgl Berhenti</th>
                      <th width="30%">Detail Pemberian</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-if="dataSourceStop.length == 0">
                      <td colspan="9">
                        <div class="page-placeholder">
                          <div class="placeholder-content">
                            <img
                              class="light-image"
                              style="max-width: 340px"
                              src="/@src/assets/illustrations/placeholders/search-7.svg"
                              alt=""
                            />
                            <img
                              class="dark-image"
                              style="max-width: 340px"
                              src="/@src/assets/illustrations/placeholders/search-7-dark.svg"
                              alt=""
                            />
                            <h3>
                              {{
                                isLoadingDataBerhenti ? 'Loading.....' : 'Data belum ada.'
                              }}
                            </h3>
                            <p class="is-larger">
                              {{
                                isLoadingDataBerhenti
                                  ? 'Sedang memuat data, tunggu sebentar'
                                  : 'Sepertinya data ini belum di inputkan, silahkan melakukan penginputan terlebih dahulu.'
                              }}
                            </p>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="data in dataSourceStop">
                      <td>{{ data.namalengkap }}</td>
                      <td>
                        <div>
                          <VTag v-if="data.replace">
                            <span class="strikethrough-diagonal">{{
                              data.produk_order.namaproduk
                            }}</span>
                          </VTag>
                        </div>
                        <div>
                          <VTag
                            color="blue"
                            v-if="data.norec_order && data.norec_resep"
                            :label="data.namaproduk"
                          />
                          <VTag
                            color="warning"
                            v-if="!data.norec_order && data.norec_resep"
                            :label="data.namaproduk"
                          />
                          <VTag
                            v-if="data.norec_order && !data.norec_resep"
                            :label="data.namaproduk"
                          />
                        </div>
                        <!-- {{ data.namaproduk }} -->
                      </td>
                      <td>{{ data.jumlah }}</td>
                      <td>{{ data.aturanpakai }}</td>
                      <td>{{ data.lamahari }}</td>
                      <td>{{ data.jumlahpemberian }}</td>
                      <td>{{ H.formatDate(data.tglmulai, 'DD-MM-YYYY') }}</td>
                      <td>{{ H.formatDate(data.tglstop, 'DD-MM-YYYY') }}</td>
                      <td>
                        <TabView :scrollable="true" style="width: 100%">
                          <TabPanel
                            v-for="tab in data.detailKPO"
                            :key="tab.tglpemberian"
                            :header="tab.tglpemberian"
                          >
                            <div style="overflow-x: auto; width: 100%">
                              <table class="detail-kpo">
                                <tr>
                                  <td v-for="detail in tab.detail" :key="detail.id">
                                    <div>
                                      {{
                                        detail.jampemberian ? 'Pemberian' : 'Stop'
                                      }}
                                    </div>
                                    <div>
                                      {{
                                        detail.jampemberian
                                          ? detail.jampemberian
                                          : detail.jamstop
                                      }}
                                    </div>
                                    <div class="text-center">
                                      <!-- <img
                                        :src="`https://api.qrserver.com/v1/create-qr-code/?size=40x40&data=${detail.namalengkap}`"
                                        v-if="detail.jampemberian"
                                      /> -->
                                      <VIconButton
                                        type="button"
                                        icon="feather:check-circle"
                                        outlined
                                        circle
                                        color="success"
                                        @click="showQR(detail)"
                                        v-if="!detail.jamstop"
                                      />
                                      <VIconButton
                                        type="button"
                                        icon="feather:x-circle"
                                        outlined
                                        circle
                                        color="danger"
                                        v-if="detail.jamstop"
                                      >
                                      </VIconButton>
                                    </div>
                                    <div>
                                      {{ detail.jampemberian ? 'Jam Input' : 'Alasan' }}
                                    </div>
                                    <div>
                                      {{
                                        detail.jampemberian
                                          ? detail.jaminput
                                          : detail.alasan
                                      }}
                                    </div>
                                  </td>
                                </tr>
                              </table>
                            </div>
                          </TabPanel>
                        </TabView>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="column is-12">
                <h1 class="mb-3 bold">Resep Pulang</h1>
                <DataTable
                  :value="dataSourcePulang"
                  :rows="20"
                  v-model:expandedRows="expandedRows"
                  :rowsPerPageOptions="[5, 10, 15, 20, 50, 100]"
                  :loading="isLoadingBtn"
                  class="p-datatable-sm"
                  responsiveLayout="stack"
                  breakpoint="960px"
                  selectionMode="single"
                  sortMode="multiple"
                  dataKey="norec"
                  resizableColumns
                  paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                  paginator
                  currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                  v-model:filters="filters"
                  filterDisplay="row"
                  :globalFilterFields="['jenis_pelayanan']"
                  style="width: 100%"
                >
                  <template #empty>
                    <div class="page-placeholder">
                      <div class="placeholder-content">
                        <img
                          class="light-image"
                          style="max-width: 340px"
                          src="/@src/assets/illustrations/placeholders/search-7.svg"
                          alt=""
                        />
                        <img
                          class="dark-image"
                          style="max-width: 340px"
                          src="/@src/assets/illustrations/placeholders/search-7-dark.svg"
                          alt=""
                        />
                        <h3>Data belum ada.</h3>
                        <p class="is-larger">
                          Sepertinya data ini belum di inputkan, silahkan melakukan
                          penginputan terlebih dahulu.
                        </p>
                      </div>
                    </div>
                  </template>
                  <ColumnGroup type="header">
                    <Row>
                      <Column header="Dokter" headerStyle="min-width: 50%;" />
                      <Column header="Nama Obat" />
                      <Column header="Status" />
                      <Column header="Tanggal Order" />
                      <Column header="Aturan Pakai" style="min-width: 50%" />
                      <Column header="Depo" style="min-width: 50%" />
                      <Column header="Jumlah" />
                    </Row>
                  </ColumnGroup>
                  <Column field="dokter" />
                  <Column field="namaobat" />
                  <Column field="status" />
                  <Column field="tglorder" />
                  <Column field="aturanpakai" />
                  <Column field="depo" />
                  <Column field="jumlah" />
                </DataTable>
              </div>
            </div>
          </div>
        </div>
      </div>
    </VCard>
    <Dialog v-model:visible="isStop" modal header="Pemberhentian Obat">
      <div class="columns is-multiline">
        <div class="column is-12">
          Nama Dokter pemberi instruksi : {{ dataStop.namalengkap }}
        </div>
        <div class="column is-12">Nama Obat : {{ dataStop.namaproduk }}</div>
        <div class="column is-12">Aturan Pakai : {{ dataStop.aturanpakai }}</div>
        <div class="column is-12">
          Waktu Stop :
          <Calendar
            v-model="item.tglstop"
            selectionMode="single"
            :manualInput="true"
            class="w-100 mt-2"
            :showIcon="true"
            showTime
            hourFormat="24"
            :date-format="'yy-mm-dd'"
            placeholder="yy-mm-dd HH:mm"
            :locale="calendarLocale"
            :disabled="item.tglVerifikasi2"
          />
        </div>
        <div class="column is-12">
          Alasan :
          <VField>
            <VControl>
              <VTextarea
                v-model="item.alasan"
                rows="5"
                placeholder="Alasan...."
                :disabled="item.P2"
              >
              </VTextarea>
            </VControl>
          </VField>
        </div>
      </div>
      <template #footer>
        <VButton
          icon="lnir lnir-arrow-left rem-100"
          light
          dark-outlined
          @click="isStop = false"
        >
          Tutup
        </VButton>
        <VButton class="ml-2" color="danger" @click="stopKPO" :loading="isLoading">
          Stop Pemberian
        </VButton>
      </template>
    </Dialog>
    <Dialog v-model:visible="isGantiDosis" modal header="Ganti Dosis Obat">
      <div class="columns is-multiline">
        <div class="column is-12">
          Nama Dokter pemberi instruksi : {{ dataGanti.namalengkap }}
        </div>
        <div class="column is-12">Nama Obat : {{ dataGanti.namaproduk }}</div>
        <div class="column is-12">
          Aturan Pakai Sekarang : {{ dataGanti.aturanpakai }}
        </div>
        <div class="column is-12">
          Aturan Pakai Perubahan:
          <VField>
            <VControl>
              <VInput
                v-model="item.aturanpakaibaru"
                :true-value="dataStop.aturanpakai"
                rows="5"
                placeholder="Aturan Pakai Baru"
                :disabled="item.P2"
              />
            </VControl>
          </VField>
        </div>
      </div>
      <template #footer>
        <VButton
          icon="lnir lnir-arrow-left rem-100"
          light
          dark-outlined
          @click="isGantiDosis = false"
        >
          Tutup
        </VButton>
        <VButton class="ml-2" color="danger" @click="gantiDosis" :loading="isLoading">
          Ganti Dosis
        </VButton>
      </template>
    </Dialog>
    <Dialog v-model:visible="isQR" modal :header="dataQR.titleQR">
      <div class="columns is-multiline">
        <div class="column is-12">
          <img
            :src="`https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=${dataQR.isiQR}`"
          />
        </div>
      </div>
    </Dialog>

    <Dialog v-model:visible="isValidasi" modal header="Validasi Pemberian">
      <div class="columns is-multiline">
        <div class="column is-12">
          Nama Dokter pemberi instruksi : {{ dataValidasi.namalengkap }}
        </div>
        <div class="column is-12">Nama Obat : {{ dataValidasi.namaproduk }}</div>
        <div class="column is-12">Aturan Pakai : {{ dataValidasi.aturanpakai }}</div>
        <div class="column is-12">
          Jam Pemberian :
          <Calendar
            v-model="item.waktupemberian"
            selectionMode="single"
            :manualInput="true"
            class="w-100 mt-2"
            :showIcon="true"
            showTime
            hourFormat="24"
            :date-format="'yy-mm-dd'"
            placeholder="HH:mm"
            :locale="calendarLocale"
            :disabled="item.tglVerifikasi2"
          />
        </div>
        <div class="column is-12">
          Petugas Pemberi : {{ useUserSession().getUser().pegawai.namaLengkap }}
        </div>
      </div>
      <template #footer>
        <VButton
          icon="lnir lnir-arrow-left rem-100"
          light
          dark-outlined
          @click="isValidasi = false"
        >
          Tutup
        </VButton>
        <VButton
          class="ml-2"
          color="primary"
          @click="validasiPemberian"
          :loading="isLoading"
        >
          Validasi Pemberian
        </VButton>
      </template>
    </Dialog>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr-rehab-perdosi.vue'
import AutoComplete from 'primevue/autocomplete'
import Fieldset from 'primevue/fieldset'
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import DataTable from 'primevue/datatable'
import Dialog from 'primevue/dialog'
import Calendar from 'primevue/calendar'
import Column from 'primevue/column'
import ColumnGroup from 'primevue/columngroup' // optional
import Row from 'primevue/row'
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let dataPasien: any = ref([])

const dataTTD: any = ref([])
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
const isStuck = computed(() => {
  return y.value >= 20
})
const isLoading: any = ref(false)
const isLoadingDataKPO: any = ref(false)
const isLoadingDataBerhenti: any = ref(false)
const isStop: any = ref(false)
const isQR: any = ref(false)
const dataQR: any = ref({})
const isGantiDosis: any = ref(false)
const isValidasi: any = ref(false)
const loadingAddKPO: any = ref(false)
const dataStop: any = ref({})
const dataGanti: any = ref({})
const dataValidasi: any = ref({})
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const dataSource: any = ref([])
const dataSourceStop: any = ref([])
const dataSourcePulang: any = ref([])
const d_Registrasi: any = ref([])
const d_Dokter: any = ref([])
const d_Diagnosa: any = ref([])
const d_JK: any = ref([])
const isLoadingTT = ref(false)
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false],
})
const COLLECTION: any = ref('FormulirDPJP') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  dpjpbanyak: [{ no: 1 }],
  perubahandpjp: [{ no: 1 }],
  perubahandpjptambahan: [{ no: 1 }],
})
const d_Jenis = [
  { id: 1, label: 'DPJP Utama' },
  { id: 2, label: 'DPJP Tambahan' },
]
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}
const confirm = useConfirm()
const loadRiwayat = async () => {
  isLoading.value = true
  isLoadingDataKPO.value = true
  isLoadingDataBerhenti.value = true
  loadingAddKPO.value = true
  dataSource.value = []
  dataSourceStop.value = []
  await useApi()
    .get(`/farmasi/resep-kpo?norec_pd=${props.registrasi.norec_pd}`)
    .then((response: any) => {
      const newData = response.data.map((element) => {
        // Mengelompokkan data detailKPO berdasarkan tglpemberian
        const groupedData = element.detailKPO.reduce((result, current) => {
          const { tglpemberian } = current
          if (!result[tglpemberian]) {
            result[tglpemberian] = []
          }
          result[tglpemberian].push(current)
          return result
        }, {})

        // Transformasi data ke format yang diinginkan
        return {
          ...element,
          detailKPO: Object.keys(groupedData)
            .sort((a, b) => new Date(b).getTime() - new Date(a).getTime()) // Mengurutkan tglpemberian secara descending
            .map((date) => ({
              tglpemberian: date,
              detail: groupedData[date],
            })),
        }
      })
      // Menyimpan data yang telah diubah ke dataSource
      dataSource.value = newData
    })
    .catch((error) => {
      console.error('Failed to fetch and process data:', error)
    })
    .finally(() => {
      isLoading.value = false
      isLoadingDataKPO.value = false
      loadingAddKPO.value = false
    })

  await useApi()
    .get(`/farmasi/resep-kpo-stop?norec_pd=${props.registrasi.norec_pd}`)
    .then((response: any) => {
      const newData = response.data.map((element) => {
        // Mengelompokkan data detailKPO berdasarkan tglpemberian
        const groupedData = element.detailKPO.reduce((result, current) => {
          const tgl = current.tglpemberian || current.tglstop // Periksa tglpemberian terlebih dahulu, jika tidak ada gunakan tglstop
          if (!result[tgl]) {
            result[tgl] = []
          }
          result[tgl].push(current)
          return result
        }, {})

        // Transformasi data ke format yang diinginkan
        return {
          ...element,
          detailKPO: Object.keys(groupedData)
            .sort((a, b) => new Date(b).getTime() - new Date(a).getTime()) // Mengurutkan tglpemberian secara descending
            .map((date) => ({
              tglpemberian: date,
              detail: groupedData[date],
            })),
        }
      })

      dataSourceStop.value = newData
    })
    .finally(() => {
      isLoading.value = false
      isLoadingDataBerhenti.value = false
      loadingAddKPO.value = false
    })

  await useApi()
    .get(
      `/farmasi/riwayat-order-resep-pulang?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}`
    )
    .then((response: any) => {
      if (response.length <= 0) {
        input.value.detailObatResep = [
          {
            no: 1,
          },
        ]
      } else {
        dataSourcePulang.value = response.flatMap((e: any) =>
          e.details.map((detail: any) => {
            return {
              dokter: e.namalengkap,
              jenisObat: detail.jeniskemasan,
              namaobat: detail.namaproduk,
              tglorder: e.tglorder,
              status: detail.status,
              aturanpakai: detail.aturanpakai,
              jumlah: detail.jumlah,
              waktuPakai: detail.satuanresep,
              dosis: detail.dosis,
              depo: e.namaruangan,
            }
          })
        )
      }
    })
}

const simpanKPO = (data: any) => {
  isLoading.value = true
  loadingAddKPO.value = true
  useApi()
    .post(`/farmasi/simpan-kpo`, data)
    .then((response: any) => {
      loadingAddKPO.value = false
      isLoading.value = false
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
      loadingAddKPO.value = false
    })
}

const showQR = (data: any) => {
  const {tglpemberian, jampemberian, jaminput, namalengkap} = data
  isQR.value = true
  dataQR.value.titleQR = 'QR Validasi Pemberian'
  dataQR.value.isiQR = `user input: ${namalengkap}
  tanggal pemberian: ${tglpemberian} ${jampemberian}
  waktu input: ${jaminput}
  nama pasien: ${props.pasien.namapasien}`;
}

const modalStopKPO = (data: any) => {
  isStop.value = true
  item.tglstop = new Date()
  // data.tglstop = H.formatDate(new Date(), 'DD-MM-YYYY')
  dataStop.value = data
}

const modalGantiDosis = (data: any) => {
  isGantiDosis.value = true
  data.tglstop = H.formatDate(new Date(), 'DD-MM-YYYY')
  dataGanti.value = data
}

const modalValidasiPemberian = (data: any) => {
  isValidasi.value = true
  item.waktupemberian = new Date()
  dataValidasi.value = data
}

const stopKPO = (data: any) => {
  dataStop.value.tglstop = item.tglstop
  dataStop.value.alasan = item.alasan
  dataStop.value.petugaspemberifk = useUserSession().getUser().pegawai.id
  console.log(dataStop.value)

  confirm.require({
    message: `Apakah anda yakin akan menghentikan pemberian Obat ${dataStop.value.namaproduk} ?`,
    header: 'Konfirmasi Pemberhentian Obat',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      isLoading.value = true
      loadingAddKPO.value = true
      useApi()
        .post(`/farmasi/stop-kpo`, dataStop.value)
        .then((response: any) => {
          loadingAddKPO.value = false
          isLoading.value = false
          loadRiwayat()
        })
        .catch((e: any) => {
          isLoading.value = false
          loadingAddKPO.value = false
        })
        .finally(() => {
          isStop.value = false
        })
    },
    reject: () => {
      H.alert('info', 'Cancel Pemberhentian')
    },
    acceptLabel: 'Ya',
    rejectLabel: 'Tidak',
  })
}

const gantiDosis = (data: any) => {
  const splitObat = item.aturanpakaibaru.split(/x|X/)[0]
  const lamaHari = Math.ceil(parseInt(dataGanti.value.jumlah) / parseInt(splitObat))
  const tanggalMulai = H.formatDate(new Date(), 'YYYY-MM-DD HH:mm')
  dataGanti.value.lamahari = lamaHari
  dataGanti.value.tanggalmulai = tanggalMulai

  dataGanti.value.aturanpakaibaru = item.aturanpakaibaru
  dataGanti.value.petugaspemberifk = useUserSession().getUser().pegawai.id

  confirm.require({
    message: `Apakah anda yakin akan mengganti pemberian Obat ${dataGanti.value.namaproduk} ?`,
    header: 'Konfirmasi Ganti Dosis Obat',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      isLoading.value = true
      loadingAddKPO.value = true
      useApi()
        .post(`/farmasi/ganti-dosis-kpo`, dataGanti.value)
        .then((response: any) => {
          loadingAddKPO.value = false
          isLoading.value = false
          loadRiwayat()
        })
        .catch((e: any) => {
          isLoading.value = false
          loadingAddKPO.value = false
        })
        .finally(() => {
          isGantiDosis.value = false
        })
    },
    reject: () => {
      H.alert('info', 'Cancel Ganti Dosis')
    },
    acceptLabel: 'Ya',
    rejectLabel: 'Tidak',
  })
}

const validasiPemberian = (data: any) => {
  dataValidasi.value.waktupemberian = item.waktupemberian
  dataValidasi.value.petugaspemberifk = useUserSession().getUser().pegawai.id
  isLoading.value = true
  loadingAddKPO.value = true
  useApi()
    .post(`/farmasi/validasi-pemberian`, dataValidasi.value)
    .then((response: any) => {
      loadingAddKPO.value = false
      isLoading.value = false
      loadRiwayat()
    })
    .catch((e: any) => {
      isLoading.value = false
      loadingAddKPO.value = false
    })
    .finally(() => {
      isValidasi.value = false
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let tanggalRegistrasi = dataPasien.value.map((item: any) => item.tglregistrasi)
  let json = {
    id: ID,
    norec_emr: NOREC_EMRPASIEN.value,
    collection: COLLECTION.value,
    url_form: props.FORM_URL,
    name_form: props.FORM_NAME,
    jenis_emr: 'asesmen_medis',
    data: object,
    tanggal_registrasi: tanggalRegistrasi,
  }

  isLoading.value = true
  useApi()
    .post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    })
    .catch((e: any) => {
      isLoading.value = false
    })
}

const addNewDPJP = () => {
  input.value.dpjpbanyak.push({
    no: input.value.dpjpbanyak[input.value.dpjpbanyak.length - 1].no + 1,
  })
  setAutoFill()
}
const removeDPJP = (index: any) => {
  input.value.dpjpbanyak.splice(index, 1)
}

const addNewPerubahanDPJP = () => {
  input.value.perubahandpjp.push({
    no: input.value.perubahandpjp[input.value.perubahandpjp.length - 1].no + 1,
  })
  setAutoFill()
}
const removePerubahanDPJP = (index: any) => {
  input.value.perubahandpjp.splice(index, 1)
}
const addNewPerubahanDPJPTambahan = () => {
  input.value.perubahandpjptambahan.push({
    no:
      input.value.perubahandpjptambahan[input.value.perubahandpjptambahan.length - 1].no +
      1,
  })
  setAutoFill()
}

const addKPO = (data: any) => {
  const splitObat = data.aturanpakai.split(/x|X/)[0]
  const lamaHari = Math.ceil(parseInt(data.jumlah) / parseInt(splitObat))
  const tanggalMulai = H.formatDate(new Date(), 'YYYY-MM-DD HH:mm')
  data.lamahari = lamaHari
  data.tanggalmulai = tanggalMulai
  data.registrasi = props.registrasi
  console.log(data);
  simpanKPO(data)
}

const removePerubahanDPJPTambahan = (index: any) => {
  input.value.perubahandpjptambahan.splice(index, 1)
}

const fetchDokter = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap,nosip&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
    )
    .then((response) => {
      d_Dokter.value = response
    })
}
const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`
  )
  d_Diagnosa.value = response
}
const fetchPegawai = async (filter: any) => {
  await useApi()
    .get(
      `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
    )
    .then((response) => {
      d_Pegawai.value = response
      console.log(d_Pegawai.value)
    })
}
const fetchJenisKelamin = async () => {
  const response = await useApi().get(
    `/emr/dropdown/jeniskelamin_m?select=id,jeniskelamin&param_search=&`
  )
  d_JK.value = response
}
const setTandaTangan = async (e: any, i: any) => {
  await useApi()
    .get(`emr/tanda-tangan/${e.value.value}`)
    .then((element) => {
      if (element) {
        H.tandaTangan().set('signature_' + i, element.ttd)
      }
    })
}

const kembaliKeun = () => {
  window.history.back()
}

const setRegisPasien = async (e: any) => {
  dataPasien.value = []
  await useApi()
    .get(`/emr/register-pasien-emr?nocmfk=${ID_PASIEN}`)
    .then((response) => {
      dataPasien.value = response
      d_Registrasi.value = response
      // console.log(dataPasien.value)
    })
}

const setAutoFill = async () => {
  fetchJenisKelamin()
  input.value.tglPembuatan = new Date()
  if (useUserSession().getUser().kelompokUser.kelompokUser == 'dokter') {
    input.dpjp1 = {
      label: useUserSession().getUser().pegawai.namaLengkap,
      value: useUserSession().getUser().pegawai.id,
    }
    input.value.parafdpjp1 = useUserSession().getUser().pegawai.namaLengkap
  } else {
    input.value.dpjp1 = {
      label: props.registrasi.dokter,
      value: props.registrasi.objectpegawaifk,
    }
    input.value.parafdpjp1 = props.registrasi.dokter
  }

  if (input.value.dpjpbanyak) {
    input.value.dpjpbanyak.forEach((data, index) => {
      if (index == 0) {
        data.jenisdpjpbanyak = { id: 1, label: 'DPJP Utama' }
        data.dpjpbanyak = {
          label: props.registrasi.dokter,
          value: props.registrasi.objectpegawaifk,
        }
      } else {
        data.jenisdpjpbanyak = { id: 2, label: 'DPJP Tambahan' }
      }
    })
  }
}
const setAutoFillDiagnosa = async () => {
  await useApi()
    .get(`/emr/auto-fill-icd10/${props.registrasi.noregistrasi}`)
    .then((response: any) => {
      let kdnamadiagnosa = []
      for (let i = 0; i < response.length; i++) {
        const element = response[i]
        kdnamadiagnosa.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa)
      }
      input.value.diagnosa = kdnamadiagnosa.join(',')
    })
}
// console.log(JSON.stringify(props.pasien));

setView()
setAutoFill()
setRegisPasien()
loadRiwayat()
setAutoFillDiagnosa()
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
  vertical-align: middle;
}

.table-kpo {
  overflow-x: auto;
  table-layout: auto; /* Allow column width to adjust based on content */
  width: 100%; /* Ensure table spans full width */
  max-width: 100%; /* Prevent overflow outside the container */
}
// .table-kpo {
//   overflow-x: auto;
//   table-layout: fixed; /* Kolom memiliki lebar tetap */
//   width: 100%; /* Pastikan tabel memenuhi lebar kontainer */
// }

// .table-kpo th,
// .table-kpo td {
//   overflow: hidden; /* Hindari elemen meluap */
//   text-overflow: ellipsis; /* Tambahkan ellipsis jika teks terlalu panjang */
//   // white-space: nowrap; /* Hindari teks membungkus ke baris baru */
// }

.table-kpo th, .table-kpo td {
  white-space: nowrap; /* Prevent text from wrapping, ensuring no clipping */
  padding: 10px; /* Add some padding for better readability */
}

.table-kpo tr,
.table-kpo th,
.table-kpo td {
  border: 1px solid black;
  border-collapse: collapse;
  padding: 4px;
}

.detail-kpo{
  font-size: 10px;
}

.p-tabview-panels{
  padding: 5px;
}

.p-tabview-nav-link {
  padding: 5px;
}

.strikethrough-diagonal {
  position: relative;
  color: black;
  font-weight: bold;

  &:before {
    position: absolute;
    content: '';
    left: 0;
    color: red;
    top: 45%;
    right: 0;
    border-top: 1px solid;
    border-color: inherit;
    -webkit-transform: skewY(-10deg);
    -moz-transform: skewY(-10deg);
    transform: skewY(-10deg);
  }
}
</style>
