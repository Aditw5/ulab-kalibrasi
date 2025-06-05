
<template>
  <div class="column">
    <VCard>
      <div class="column is-12">
        <div class="search-widget">
          <div class="field">
            <div class="columns is-multiline">
              <div class="column is-4 pt-0 pb-0">
                <span>Periode</span>
                <VDatePicker v-model="item.qFilterTgl" is-range color="pink" trim-weeks class="pt-2">
                  <template #default="{ inputValue, inputEvents }">
                    <VField addons>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.start" class="input-calendar" v-on="inputEvents.start" />
                      </VControl>
                      <VControl>
                        <VButton static><i class="fas fa-arrow-right" aria-hidden="true"></i></VButton>
                      </VControl>
                      <VControl icon="feather:calendar">
                        <VInput :value="inputValue.end" class="input-calendar" v-on="inputEvents.end" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </div>
              <div class="column pt-0 pb-0 is-4">
                <span>Dokter</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.dokterfk" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Nama Dokter..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column is-3 pt-0 pb-0">
                <span>Ruangan</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.ruanganfk" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Ruangan Rawat..."/>
                  </VControl>
                </VField>
              </div>
              <!-- <div class="column pt-0 pb-0 is-4">
                <span>Kelompok Pasien</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.kelompokpasien" :suggestions="d_KelompokPasien" @complete="fetchKelompokPasien($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Kelompok Pasien..."/>
                  </VControl>
                </VField>
              </div>
              <div class="column pt-0 pb-0 is-4">
                <span>Jenis Pasien</span>
                <VField class="is-autocomplete-select pt-2" v-slot="{ id }">
                  <VControl icon="feather:search">
                    <AutoComplete v-model="item.jenispasien" :suggestions="d_JenisPasien" @complete="fetchJenisPasien($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Jenis Pasien..."/>
                  </VControl>
                </VField>
              </div> -->
            </div>
          </div>
          <div class="field">
            <div class="control">
              <div class="columns is-multiline">
                <div class="column is-11 ">
                  <input type="text" v-model="item.search" v-on:keyup.enter="fetchData()" class="input"
                    placeholder="Search..." />
                </div>
                <div class="column" style="margin-left: auto:  !important;">
                  <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                    @click="fetchData()" :loading="isPlaceLoad">
                  </VIconButton>
                </div>
              </div>
            </div>
          </div>
          <div class="column is-3" style="margin-top:-30px">
                <VField>
                    <VControl>
                        <VSwitchBlock v-model="item.isTanggalPulang" label="Filter By Tanggal Pulang" color="danger" />
                    </VControl>
                </VField>
            </div>
        </div>
      </div>
    </VCard>
  </div>

  <div class="column">
    <VCard>
      <h3 class="title is-5 mb-2">Laporan KMKB</h3>
      <div class="column" v-if="isPlaceLoad">
        <VPlaceloadWrap v-for="data in 25">
          <VPlaceload class="mx-2 mb-3" />
          <VPlaceload class="mx-2" />
        </VPlaceloadWrap>
      </div>
      <div class="column" v-else>
        <VPlaceholderPage v-if="dataSource.length == 0" title="Data Tidak di Temukan."
          subtitle="Silakan filter pencarian di tanggal lain" larger>
          <template #image>
            <img class="light-image" src="/@src/assets/illustrations/placeholders/search-1.svg" alt="" />
            <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-1-dark.svg" alt="" />
          </template>
        </VPlaceholderPage>

        <div v-else>
          <DataTable :value="dataSource" class="p-datatable-sm" :loading="isLoading" :paginator="true" :rows="20"
            :rowsPerPageOptions="[20, 40, 60]" scrollable
            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

            <template #header>
              <div class="flex flex-wrap align-items-center justify-content-between gap-2">
                <VButton color="warning" class="mr-4 mb-3" icon="fas fa-file-excel" raised @click="exportExcel()"> Export
                  to
                  Excel </VButton>
              </div>
            </template>

            <Column field="no" header="#" frozen></Column>
            <Column field="namapasien" header="Nama Pasien" frozen :sortable="true" style="min-width: 200px"></Column>
            <Column field="nocm" header="No RM" :sortable="true" style="min-width: 100px"></Column>
            <Column field="noregistrasi" header="No Registrasi" :sortable="true" style="min-width: 100px"></Column>
            <Column field="kelompokpasien" header="Kelompok Pasien" :sortable="true" style="min-width: 200px"></Column>
            <Column field="realcost" header="Real Cost" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                {{H.formatRp(slotProps.data.realcost, 'Rp. ')}}
              </template>
            </Column>
            <Column field="inacbg_totalgrouper" header="Plafon" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                {{H.formatRp(slotProps.data.inacbg_totalgrouper, 'Rp. ')}}
              </template>
            </Column>
            <Column field="statuspasien" header="Status" :sortable="true">
              <template #body="slotProps">
                <VTag class="mx-4 my-2" :color="slotProps.data.colorStatus" rounded v-if="!slotProps.data.diagnosa || slotProps.data.inacbg_totalgrouper">{{ slotProps.data.statuspasien }}</VTag>
                <VButton type="button" color="info" class="mx-4 my-2" outlined rounded raised @click="hitungBiayaSementara(slotProps.data.norec_pd)" :loading="isFlafon" v-else> Cek Plafon </VButton>
                <!-- <span>{{ H.formatDateToLocalString(slotProps.data.tglregistrasi) }}</span> -->
              </template>
            </Column>
            <Column field="jeniskelamin" header="Jenis Kelamin" :sortable="true" style="min-width: 200px"></Column>
            <Column field="tgllahir" header="Tanggal Lahir" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tgllahir) }}</span>
              </template>
            </Column>
            <Column field="tglregistrasi" header="Tanggal Masuk" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ H.formatDateToLocalString(slotProps.data.tglmasuk) }}</span>
              </template>
            </Column>
            <Column field="tglregistrasi" header="Tanggal Pulang" :sortable="true" style="min-width: 200px">
              <template #body="slotProps">
                <span>{{ slotProps.data.tglpulang ? H.formatDateToLocalString(slotProps.data.tglpulang) : 'Belum Pulang' }}</span>
              </template>
            </Column>
            <Column field="dokter" header="Dokter" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namaruangan" header="Ruangan Rawat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakelas" header="Kelas Rawat" :sortable="true" style="min-width: 200px"></Column>
            <Column field="selisihwaktu" header="Lama Rawat" :sortable="true" style="min-width: 200px"></Column>
            <!-- <Column field="kodediagnosa" header="Kode Diagnosa" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namadiagnosa" header="Nama Diagnosa" :sortable="true" style="min-width: 200px"></Column>
            <Column field="ketdiagnosis" header="Diagnosa Dokter" :sortable="true" style="min-width: 200px"></Column> -->
            <Column field="namakotakabupaten" header="Asal Kota/Kabupaten" :sortable="true" style="min-width: 200px"></Column>
            <Column field="namakecamatan" header="Asal Kecamatan" :sortable="true" style="min-width: 200px"></Column>
          </DataTable>
        </div>
      </div>

    </VCard>
  </div>

</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { ref, computed, watch, reactive } from 'vue'
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { formatRp } from '/@src/utils/appHelper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import moment from 'moment'
import { useHead } from '@vueuse/head'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Calendar from 'primevue/calendar';
import * as XLSX from "xlsx";
useHead({
  title: 'Laporan KMKB - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const modalFilter: any = ref(false)
const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()
const total = ref(0)
const router = useRouter()
const modalInput = ref(false)
const isFlafon = ref(false)
const item: any = ref({
  qFilterTgl: {
    start: new Date(),
    end: new Date()
  },
})


const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const remakeData: any = ref([])
let dataSource: any = ref([])
let dataSourcePulang: any = ref([])
let dataHutang: any = ref([])
let d_Ruangan: any = ref([])
let d_KelompokPasien: any = ref([])
let d_JenisPasien: any = ref([])
let d_Dokter: any = ref([])
const dataSourceINACBG: any = ref([])
let isLoading: any = ref(false)
let isPlaceLoad: any = ref(false)
const filters = ref('')

const dataTagihanBelumLunas = computed(() => {
  if (!item.value.qFilter) {
    return dataHutang.value
  }
  return dataHutang.value.filter((items: any) => {
    return (
      items.namapasien.match(new RegExp(item.value.qFilter, 'i')) ||
      items.noRegistrasi.match(new RegExp(item.value.qFilter, 'i'))
    )
  })
})

const fetchKelompokPasien = async (filter: any) => {
    await useApi().get(`emr/dropdown/kelompokpasien_m?select=id,kelompokpasien&param_search=kelompokpasien&query=${filter.query}&limit=10`
    ).then((response) => {
        d_KelompokPasien.value = response
    })
}

const hitungBiayaSementara = async (norec_pd) => {
  isFlafon.value = true
  const e = await useApi().get('/bridging/inacbgs/get-for-plafon?norec_pd=' + norec_pd)
  if (e.set_claim_data == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Tidak ada')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {
    isFlafon.value = false
    H.alert('info', 'Data SEP Belum di isi')
    return
  }
  if (e.set_claim_data.data.diagnosa == null ||
    e.set_claim_data.data.diagnosa == '' ||
    e.set_claim_data.data.diagnosa == false) {
    isFlafon.value = false
    H.alert('info', 'Data Diagnosa Belum di isi')
    return
  }
  if (e.set_claim_data.metadata.nomor_sep == null) {

    let nosepTEMP = '9999R9999999V000999'
    e.new_claim.data.nomor_sep = nosepTEMP
    e.set_claim_data.data.nomor_sep = nosepTEMP
    e.set_claim_data.metadata.nomor_sep = nosepTEMP
    e.grouper.data.nomor_sep = nosepTEMP
    e.delete_claim.data.nomor_sep = nosepTEMP
  }
  if (e.set_claim_data.data.tgl_pulang == null) {

    e.set_claim_data.data.tgl_pulang = e.set_claim_data.data.tgl_masuk
  }
  dataSourceINACBG.value = e.data
  isFlafon.value = true
  let json = []
  json.push(e.new_claim)
  json.push(e.set_claim_data)

  await useApi().postBPJS('/bridging/inacbgs/save', { 'data': json }).then(async (r) => {

    for (let x = 0; x < r.response.dataresponse.length; x++) {
      const element = r.response.dataresponse[x];
      if (element.dataresponse.metadata.code == 200) {
        await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.grouper] }).then(async (rr) => {

          let arrGroup = []
          for (let x = 0; x < rr.response.dataresponse.length; x++) {
            const elementx = rr.response.dataresponse[x];
            if (elementx.dataresponse.metadata.code == 200) {
              arrGroup.push({
                'nomor_sep': elementx.datarequest.data.nomor_sep,
                'inacbg_status': elementx.dataresponse.metadata.method,
                'dataresponse': elementx.dataresponse
              })
              break
            } else {
              H.alert('error', elementx.dataresponse.response.cbg.description)
            }
          }

          await saveGrouping(arrGroup, true)
          await useApi().postBPJS('/bridging/inacbgs/save', { 'data': [e.delete_claim] }).then(async (xx) => {
            let arrStatus = []
            for (let x = 0; x < xx.response.dataresponse.length; x++) {
              const element = xx.response.dataresponse[x];
              if (element.dataresponse.metadata.code == 200) {
                arrStatus.push({
                  'nomor_sep': element.datarequest.data.nomor_sep,
                  'inacbg_status': null
                })
                break
                // H.alert('success', element.dataresponse.metadata.message)
              } else {
                H.alert('error', element.dataresponse.metadata.message)
              }
            }
            await saveStatus(arrStatus, true)
          })
        })
        break
      } else {
        if (element.dataresponse.metadata.message != 'Duplikasi nomor SEP') {
          H.alert('error', element.dataresponse.metadata.message)
        }
      }
    }

    isFlafon.value = false
  }, (error) => {
    isFlafon.value = false
  })
}
const saveStatus = async (e: any, load: boolean) => {
  if (!e.length) return
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
      }
    }
  }
  await useApi().postBPJS('/bridging/inacbgs/save-status', { 'data': e }).then(async (r) => {
    // if (load)
    // fetchData()
  })
}
const saveGrouping = async (e: any, load: boolean) => {
  if (!e.length) return
  for (let i = 0; i < dataSourceINACBG.value.length; i++) {
    const element = dataSourceINACBG.value[i];
    for (var ii = 0; ii < e.length; ii++) {
      const elem2 = e[ii]
      if (element.nomor_sep == elem2.nomor_sep) {
        elem2.norec = element.norec
        elem2.jenis_rawat = element.jenis_rawat
      }
    }
  }
  let tarifINACB = 0
  for (var ii = 0; ii < e.length; ii++) {
    const elem2 = e[ii]
    let totaldijamin = 0
    let biayanaikkelas = 0
    if (elem2.jenis_rawat == 1) {
      totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
    } else {
      let hakkelas = elem2.dataresponse.response.kelas
      if (hakkelas == "kelas_1") {
        totaldijamin = elem2.dataresponse.tarif_alt[0].tarif_inacbg
      } else if (hakkelas == "kelas_2") {
        totaldijamin = elem2.dataresponse.tarif_alt[1].tarif_inacbg
      } else if (hakkelas == "kelas_3") {
        totaldijamin = elem2.dataresponse.tarif_alt[2].tarif_inacbg
      }

      biayanaikkelas = elem2.dataresponse.response.add_payment_amt ? elem2.dataresponse.response.add_payment_amt : 0
      if (biayanaikkelas < 0) {
        biayanaikkelas = 0
      }
    }
    let json = {
      'norec': elem2.norec,
      'totaldijamin': totaldijamin,
      'biayanaikkelas': biayanaikkelas,
      'inacbg_grouper': elem2.dataresponse
    }
    tarifINACB = totaldijamin
    await useApi().postBPJS('/bridging/inacbgs/save-grouping', json)
    fetchData()
  }
}

const fetchJenisPasien = async (filter: any) => {
    d_JenisPasien.value = [{value: 'LAMA', label:'LAMA'},{value: 'BARU', label:'BARU'},]
}

const fetchData = async () => {
  isPlaceLoad.value = true
  let tglAwal = '&tglAwal=' + moment(item.value.qFilterTgl.start).format('YYYY-MM-DD')
  let tglAkhir = '&tglAkhir=' + moment(item.value.qFilterTgl.end).format('YYYY-MM-DD')
  let ruanganfk = item.value.ruanganfk ? `&ruanganfk=${item.value.ruanganfk.value}` : ``
  let dokter = item.value.dokterfk ? `&idpegawai=${item.value.dokterfk.value}` : ''
  let nama = item.value.namaPasien ? `&nama=${item.value.namaPasien}` : ''
  let isTanggalPulang = item.value.isTanggalPulang ? `&isTanggalPulang=${item.value.isTanggalPulang}` : ''
  let isStatusPulang = item.value.isStatusPulang ? `&isStatusPulang=${item.value.kelompokpasien.value}` : ''
  let statuspasien = item.value.jenispasien ? `&statuspasien=${item.value.jenispasien.value}` : ''
  let kmkb = 'kmkb=true'
  let search = item.value.search ? `&search=${item.value.search}` : ''

  await useApi().get(`/dashboard/kmkb?${kmkb}${ruanganfk}${tglAwal}${tglAkhir}${dokter}${nama}${isTanggalPulang}${statuspasien}${search}`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
      let persentase = (element.realcost / element.inacbg_totalgrouper) * 100
      if(element.kelompokpasien !== 'Umum'){
        if(parseFloat(element.realcost) > parseFloat(element.inacbg_totalgrouper ?? 0)){
          if(element.diagnosa || element.inacbg_totalgrouper > 0){
            element.statuspasien = 'Melebihi Plafon'
            element.colorStatus = 'danger'
          }else{
            element.statuspasien = 'Diagnosis belum diisi'
            element.colorStatus = 'info'
          }
        }else{
          if(persentase > 60){
            element.statuspasien = 'Hampir Melebihi Plafon'
            element.colorStatus = 'warning'
          }else{
            element.statuspasien = 'Belum Melebihi Plafon'
            element.colorStatus = 'success'
          }
        }
      }
    });
    dataSource.value = response
    isPlaceLoad.value = false
  }).catch(()=>{
    isPlaceLoad.value = false
  })

}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}

const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const exportExcel = () => {
  console.log(dataSource.value)
  remakeData.value = dataSource.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, NoRM: e.nocm, NoRegistrasi: e.noregistrasi, JenisKelamin: e.jeniskelamin, TanggalLahir: e.tgllahir, TanggalMasuk: e.tglmasuk, KelompokPasien: e.jenis_pasien,
      Tanggal: e.tglmasuk, StatusPulang: e.statuspulang, TanggalPulang: e.tglpulang, KondisiPasien: e.kondisipulang, Dokter: e.dokter, RuanganAsal: e.namaruanganasal, RuanganRawat: e.namaruanganrawat, Kelas: e.namakelas, LamaRawat: e.lamarawat,
      JenisDiagnosa: e.jenisdiagnosa, KodeDiagnosa: e.kodediagnosa, NamaDiagnosa: e.namadiagnosa, KotaKabupaten: e.namakotakabupaten, Kecamatan: e.namakecamatan, Desa: e.namadesakelurahan, Provinsi: e.namapropinsi,
      Status: e.statuspasien,
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  const _url = window.URL.createObjectURL(data)
  window.open(_url, EXCEL_EXTENSION).focus();
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

watch(
    () =>item.value.isTanggalPulang,
    (newValue, oldValue) => {
        if(newValue!=oldValue){
          fetchData()
        }
    }
)

fetchData()
</script>
