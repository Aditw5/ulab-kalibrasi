<template>
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
    <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
      <TabPanel>
        <template #header>
          <i class="fas fa-users mr-2" aria-hidden="true"></i>
          <span>Lembar Kerja</span>
          <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
        </template>
        <VCard>
          <div class="colum is-12">
            <div class="columns is-multiline">
              <div class="column">
                <div class="column is-12 mt-4-min">
                  <VButton rounded color="warning" class="" icon="feather:download" raised bold
                    @click="downloadTemplate()">
                    Download Template
                  </VButton>
                  <div class="dataTable-bottom mt-2">
                    <div class="dataTable-info" style="font-style:italic"> *Note : Template
                      digunakan untuk menyamakan data, agar saat di upload tidak terjadi
                      kesalahan memasukkan data.
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </VCard>
        <hr>
        <VCard>
          <div class="column is-12">
            <div class="columns is-multiline" style="text-align:center">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Upload Lembar Kerja </h3>
              </div>
            </div>
            <DataTable rowGroupMode="rowspan" groupRowsBy="group" :value="dataSourceHasilLembarKerja" :paginator="true"
              :rows="10" :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm"
              filterDisplay="menu"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
              <template #header>
                <div class="columns is-multiline is-vcentered is-mobile pt-0 pb-0" style="gap: 0.25rem;">
                  <div class="column is-narrow" v-if="dataSourceHasilLembarKerja.length > 0">
                    <VButton color="primary" @click="cetakSertifikatLembarKerja()" outlined icon="feather:printer">
                      Cetak Sertifikat
                    </VButton>
                  </div>

                  <div class="column is-narrow" v-if="dataSourceHasilLembarKerja.length > 0">
                    <VButton color="info" @click="setujuiSertifikat()" outlined icon="feather:save"
                      :loading="isLoadingSave">
                      Setujui Sertifikat
                    </VButton>
                  </div>

                  <div class="column is-narrow" v-if="dataSourceHasilLembarKerja.length > 0">
                    <VButton color="danger" @click="cetakSertifikatLembarKerja()" outlined icon="feather:trash">
                      Tolak Sertifikat
                    </VButton>
                  </div>

                  <div class="column is-narrow" v-if="dataSourceHasilLembarKerja.length > 0">
                    <VButton rounded color="warning" icon="feather:download" raised bold
                      @click="downloadFileTerunggah()">
                      Download File
                    </VButton>
                  </div>

                  <div class="column is-narrow" v-if="dataSourceHasilLembarKerja.length > 0">
                    <div class="dataTable-info" style="font-style: italic; padding-left: 0.5rem;">
                      File Excel Terunggah {{ dataExcel }}
                    </div>
                  </div>
                </div>
              </template>
              <ColumnGroup type="header">
                <Row>
                  <Column header="No" />
                  <Column header="group" />
                  <Column header="Rentang" :colspan="2" />
                  <Column header="Penunjukan Standar" :colspan="4" />
                  <Column header="Pembacaan Alat" :colspan="2" />
                  <Column header="koreksi" :colspan="2" />
                  <Column header="Ketidakpastian" :colspan="2" />
                </Row>
              </ColumnGroup>
              <Column field="no" />
              <Column :rowspan="4" field="group" header="Rentang" />
              <Column field="rentang" header="Rentang" />
              <Column field="rentang_satuan" header="" />
              <Column field="penunjukan_standar" header="Penunjukan Standar" />
              <Column field="penunjukan_standar_satuan" header="" />
              <Column v-if="penunjukan_standar_2 !== ''" field="penunjukan_standar_2" header="" />
              <Column v-if="penunjukan_standar_satuan_2 !== ''" field="penunjukan_standar_satuan_2" header="" />
              <Column field="pembacaan_alat" header="Pembacaan Alat" />
              <Column field="pembacaan_alat_satuan" header="" />
              <Column field="koreksi" header="Koreksi" />
              <Column field="koreksi_satuan" header="" />
              <Column field="ketidakpastian" header="Ketidakpastian" />
              <Column field="ketidakpastian_standar" header="" />
            </DataTable>
          </div>
        </VCard>
      </TabPanel>
    </TabView>
  </div>
</template>
<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import { ref, computed, watch, reactive } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useViewWrapper } from '/@src/stores/viewWrapper';
import { useHead } from '@vueuse/head';
import FileUpload from 'primevue/fileupload';
import Button from 'primevue/button';
import * as H from '/@src/utils/appHelper';
import { useApi } from '/@src/composable/useApi';
import moment from 'moment';
import { useUserSession } from '/@src/stores/userSession'
import * as XLSX from "xlsx";
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';

useHead({
  title: 'Lembar Kerja - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

let NOREC_DETAIL = useRoute().query.norec_detail as string
let NOREC = useRoute().query.norec as string
const isLoading: Boolean = ref(false)
const isLoadingUpload: Boolean = ref(false)
const isLoadingSave: Boolean = ref(false)
const totalSizePercent: Number = ref(0)
const totalSize: Number = ref(0)
const valueProgress: Number = ref(0)
let loadSearch: any = ref(false)
const dataSource: any = ref([])
const dataSourceHasilLembarKerja: any = ref([])
const filterd = ref('')
const arr3 = ref([])
const dataExcel: any = ref()
const router = useRouter()
const item: any = ref({
  filterTgl: reactive({
    start: new Date(),
    end: new Date(),
  }),
  tglkalibrasi: new Date(),
  detailInstruksiKerja: [{
    no: 1
  }],
  detailPeralatanStandar: [{
    no: 1
  }]
})
const onUpload = () => {

}

const downloadTemplate = () => {
  window.open('https://ulabumro.id:8000/service/penyelia/download-template-lembar-kerja?token=' + useUserSession().token, '_blank');
}

const downloadFileTerunggah = () => {
  const norec = NOREC_DETAIL
  const token = useUserSession().token
  const url = `https://ulabumro.id:8000/service/manager/download-file-terunggah?norec=${norec}&token=${token}`
  window.open(url, '_blank')
}


const setujuiSertifikat = async () => {
  let json = {
    'verif': {
      'norec': dataSourceHasilLembarKerja.value[0].detailregistraifk ?? '',
    }
  }
  isLoadingSave.value = true
  await useApi().post('/manager/save-setujui-serti-manager', json).then((r) => {
    isLoadingSave.value = false
    toDashboard()
  }).catch((error: any) => {
    isLoadingSave.value = false
    console.error('Error saat menyimpan', error);

    if (error.response) {

      H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas mitra'}`);
    } else if (error.request) {

      H.alert('error', 'Tidak ada respons dari server. Silakan coba lagi.');
    } else {

      H.alert('error', `Terjadi kesalahan: ${error.message}`);
    }
  })
}

const toDashboard = (norec_pd: any) => {
  router.push({
    name: 'module-dashboard-manager',
  })
}


const fetchData = async () => {
  loadSearch.value = true
  await useApi().get(`manager/get-lembar-kerja-manager?norecdetail=${NOREC_DETAIL}`).then((response: any) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceHasilLembarKerja.value = response
  }).catch((err) => {
    dataSourceHasilLembarKerja.value = []
  })
  loadSearch.value = false
}

const onSelectedFiles = async (event) => {
  const files = event.files;

  if (files && files.length > 0) {
    const file = files[files.length - 1];
    if (file) {
      item.fileName = file.name;

      const reader = new FileReader();
      reader.onload = (e) => {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const firstSheetName = workbook.SheetNames[0];
        const worksheet = workbook.Sheets[firstSheetName];
        const jsonData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });

        let groups = [];
        let currentGroup = null;
        let nomor = 1;

        for (let i = 0; i < jsonData.length; i++) {
          const row = jsonData[i];

          // Jika hanya ada 1 kolom terisi → Judul group
          if (row.filter(x => !!x).length === 1 && typeof row[0] === "string" && row[0].length > 2) {
            // Mulai group baru
            currentGroup = {
              group: row[0],
              data: []
            };
            groups.push(currentGroup);
            nomor = 1; // Reset nomor untuk setiap group (atau hapus jika ingin global numbering)
            continue;
          }

          // Skip baris header label/kosong
          if (!currentGroup) continue; // belum ada group
          if (
            !row[0] ||
            (typeof row[0] === "string" && (
              row[0].toLowerCase().includes('rentang') ||
              row[0].toLowerCase().includes('range')
            ))
          ) continue;

          // Deteksi baris data valid (misal kolom 2 atau 3 terisi)
          if (row[0] && row[2]) {
            currentGroup.data.push({
              no: nomor++,
              rentang: row[0] ?? '',
              rentang_satuan: row[1] ?? '',
              penunjukan_standar: row[2] ?? '',
              penunjukan_standar_satuan: row[3] ?? '',
              penunjukan_standar_2: row[4] ?? '',
              penunjukan_standar_satuan_2: row[5] ?? '',
              pembacaan_alat: row[6] ?? '',
              pembacaan_alat_satuan: row[7] ?? '',
              koreksi: row[8] ?? '',
              koreksi_satuan: row[9] ?? '',
              ketidakpastian: row[10] ?? '',
              ketidakpastian_standar: row[11] ?? ''
            });
          }
        }

        // Simpan ke dataSource (bisa kamu pakai langsung di DataTable, atau flatten jika perlu)
        let flatData = [];
        for (const group of groups) {
          // tambahkan baris group header
          flatData.push({
            isGroupHeader: true,
            group: group.group
          });
          // lalu push semua data aslinya
          for (const row of group.data) {
            flatData.push({
              ...row,
              group: group.group,
              isGroupHeader: false
            });
          }
        }
        dataSource.value = flatData;
        // dataSource.value = groups;
        console.log(flatData)
      };
      reader.readAsArrayBuffer(file);
    }
  }
}


const onTemplatedUpload = (e: any) => {

}
const formatSize = (bytes: any) => {
  if (bytes === 0) return "0 B";
  const k = 1024;
  const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
};
const uploadEvent = (callback: any) => {
  totalSizePercent.value = totalSize.value / 10;
  isLoadingUpload.value = true
};
const onRemoveTemplatingFile = (file: any, removeFileCallback: any, index: any) => {
  removeFileCallback(index);
  totalSize.value = parseInt(formatSize(file.size));
  totalSizePercent.value = totalSize.value / 10;
  dataSource.value = []
  valueProgress.value = 0
};
const dataSourcefilter = computed(() => {
  let tarifTotal = 0;
  const filteredData = dataSource.value.filter((data) => {
    return (
      (!filterd.value ||
        (data.NAMA_PASIEN && data.NAMA_PASIEN.match(new RegExp(filterd.value, 'i'))) ||
        (data.NOKARTU && data.NOKARTU.match(new RegExp(filterd.value, 'i'))) ||
        (data.SEP && data.SEP.match(new RegExp(filterd.value, 'i'))) ||
        (data.DPJP && data.DPJP.match(new RegExp(filterd.value, 'i'))))
    );
  });

  tarifTotal = filteredData.reduce((total, data) => total + parseFloat(data.TOTAL_TARIF), 0);
  item.totalTarif = tarifTotal;
  console.log(item.totalTarif)
  return filteredData;
});
const Save = async () => {
  console.log(dataSourcefilter.value)
  if (!item.value.tglkalibrasi) { H.alert('warning', 'Tgl Kalibrasi harus di isi'); return }
  if (!item.value.tempatKalibrasi) { H.alert('warning', 'Tempat Kalibrasi harus di isi'); return }
  if (!item.value.kondisiRuangan) { H.alert('warning', 'Kondisi Ruangan Kalibrasi harus di isi'); return }
  if (!item.value.suhu) { H.alert('warning', 'Suhu harus di isi'); return }
  if (!item.value.kelembabanRelatif) { H.alert('warning', 'Kelembaban Relatif harus di isi'); return }
  const mappInstruksiKinerja = item.value.detailInstruksiKerja.map((items: any) => ({
    instruksikerja: items.daftarInstruksiKerja?.value || null
  }));
  const mappPeralatanStandar = item.value.detailPeralatanStandar.map((items: any) => ({
    peralatanstandar: items.daftaralatstandar?.value || null,
    merkalatstandar: items.merkalatstandar?.value || null,
    tipealatstandar: items.tipealatstandar?.value || null,
    serialalatstandar: items.serialalatstandar?.value || null
  }));
  let json = {
    'data': dataSourcefilter.value,
    'fileName': item.fileName,
    'norec_detail': NOREC_DETAIL,
    'tglkalibrasi': item.value.tglkalibrasi,
    'tempatKalibrasi': item.value.tempatKalibrasi,
    'kondisiRuangan': item.value.kondisiRuangan,
    'suhu': item.value.suhu,
    'kelembabanRelatif': item.value.kelembabanRelatif,
    'daftarinstruksikerja': mappInstruksiKinerja,
    'daftarperalatanstandar': mappPeralatanStandar,
  }
  isLoadingSave.value = true;
  await useApi().post(
    `/penyelia/save-data-upload-lembar-kerja`, json).then((response: any) => {
      isLoadingSave.value = false
      totalSizePercent.value = 0
      dataSource.value = []
      valueProgress.value = 0
      fetchData();
    }).catch((e: any) => {
      isLoadingSave.value = false
    })
}
const kembali = () => {
  window.history.back()
}
const collection = () => {
  if (!item.fileName) {
    H.alert("warning", "Data Kosong !");
    return;
  }
  H.cacheHelper().set('periodeTransaksiPencatatanPiutangDaftarLayanan', {
    key: 'bpjs_klaim_inacbgs',
    fileName: item.fileName
  })
  router.push({
    name: 'module-piutang-collection-piutang',
    query: {
      start: moment(new Date()).format('YYYY-MM-DD'),
      end: moment(new Date()).format('YYYY-MM-DD')
    },
  })
}
const detailOrder = async () => {
  const response = await useApi().get(`/penyelia/detail-produk-lembar-kerja?norec_pd=${NOREC_DETAIL}`)
  const data = response.data[0]

  item.value.namaproduk = data.namaproduk
  item.value.namamerk = data.namamerk
  item.value.namatipe = data.namatipe
  item.value.namaserialnumber = data.namaserialnumber
  item.value.durasikalbrasi = data.durasikalbrasi
  item.value.tglkalibrasi = data.tglkalibrasilembarkerja ?? new Date(),
    item.value.tempatKalibrasi = data.tempatKalibrasilembarkerja
  item.value.kondisiRuangan = data.kondisiRuanganlembarkerja
  item.value.suhu = data.suhulembarkerja
  item.value.kelembabanRelatif = data.kelembabanRelatiflembarkerja
  item.value.detailInstruksiKerja = (data.daftarinstruksikerja?.length > 0)
    ? data.daftarinstruksikerja.map(i => ({
      daftarInstruksiKerja: {
        value: i.value ?? '',
        label: i.label ?? ''
      }
    }))
    : [{ daftarInstruksiKerja: { value: '', label: '' } }]
  item.value.detailPeralatanStandar = (data.daftaralatstandar?.length > 0)
    ? data.daftaralatstandar.map(a => ({
      daftaralatstandar: {
        value: a.value ?? '',
        label: a.label ?? ''
      },
      merkalatstandar: {
        value: a.idmrek ?? '',
        label: a.namamerk ?? ''
      },
      tipealatstandar: {
        value: a.idtipe ?? '',
        label: a.namatipe ?? ''
      },
      serialalatstandar: {
        value: a.idsn ?? '',
        label: a.namaserialnumber ?? ''
      },
    }))
    : [{
      daftaralatstandar: { value: '', label: '' },
      merkalatstandar: { value: '', label: '' },
      tipealatstandar: { value: '', label: '' },
      serialalatstandar: { value: '', label: '' }
    }];

  const norec = NOREC_DETAIL
  const r = await useApi().get(`/manager/excel-length?norec=${norec}`)
  dataExcel.value = r.data.namafileexcel

}

const cetakSertifikatLembarKerja = () => {
  H.printBlade(`manager/cetak-sertifikat-lembar-kerja?pdf=true&norec=${dataSourceHasilLembarKerja.value[0].norecregis}&norec_detail=${dataSourceHasilLembarKerja.value[0].detailregistraifk}`);
}



detailOrder()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/module/dashboard/bedah.scss';
</style>
