<template>
  <div class="business-dashboard hr-dashboard">
    <div class="columns">
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-4" v-for="(item, index) in dataOrder" :key="index">
            <VCard radius="large" elevated>
              <div class="content">
                <!-- Judul Dinamis: Kamar 1, Kamar 2, dst -->
                <h1 class="title is-6">Kamar {{ index + 1 }}</h1>
                <p>
                  Nama Pasien: {{ item.namapasien }} <br>
                  Estimasi Waktu: {{ item.estimasiwaktu }} <br>
                  Nama Lengkap: {{ item.namalengkap }}
                </p>
              </div>
            </VCard>
          </div>
        </div>

      </div>
    </div>
  </div>

</template>

<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import Fieldset from 'primevue/fieldset'
import { useToaster } from '/@src/composable/toaster'
import * as H from '/@src/utils/appHelper'
import Dropdown from 'primevue/dropdown'
import DataTable from 'primevue/datatable'
import MultiSelect from 'primevue/multiselect';
import Column from 'primevue/column'
import * as XLSX from "xlsx";
import * as qzService from '/@src/utils/qzTrayService'
import AutoComplete from 'primevue/autocomplete';

useHead({
  title: 'Dashboard Bedah - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const themeColors = useThemeColors()
const userLogin = useUserSession().getUser()

const NOREC_PD = useRoute().query.nocm as string
const dataSource: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const remakeData: any = ref([])
const d_DokterOperasi = ref([])
const d_Petugas = ref([])
const d_JenisPelaksana = ref([])
const d_Komponen = ref([])
const d_Pegawai = ref([])
const d_Ruangan = ref([])
const d_Kamar = ref([])
const d_Produk = ref([])
const router = useRouter()
const modalFilter: any = ref(false)
const currentPage: any = ref({
  limit: 5,
  rows: 50,
})
const listItem: any = ref([
  {
    pegawai: [],
    d_Pegawai: [],
    jenisPelaksana: null,
  }
])
const date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
const modalDetail = ref(false)
const route = useRoute()
const item: any = ref({
  filterDate: new Date(),
  filterTgl: ref({
    start: new Date(),
    end: new Date(),
  }),
  tglMulaiOperasi: new Date(),
  tglSelesaiOperasi: new Date(),
})
const order: any = ref(0)
const dataOrder: any = ref(0)
const dataLaporan: any = ref(0)

let listColor: any = ref(Object.keys(useThemeColors()))
let statusOrder: any = ref([])
let dokterPraktek: any = ref([])
let isLoading: any = ref(false)
let modalDetailOrder: any = ref(false)
const isLoadChange: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalJadwalBedah: any = ref(false)
let modalFilterJadwal: any = ref(false)
let isLoadBtn: any = ref(false)
let detailDiagnosa: any = ref([])
let dataStokObat: any = ref([])
let dataOperasi: any = ref([])
let detailOrderVerify: any = ref(0)
let detailPetugas: any = ref(0)
let detailOrderLayanan: any = ref(0)
let isData: any = ref()

function getColorByType(objectjenispetugaspefk) {
  if (objectjenispetugaspefk === 6) {
    return '#bb2124';
  } else if (objectjenispetugaspefk === 17) {
    return '#5bc0de';
  } else {
    return 'gray';
  }
}

const fetchDataOrder = async (q: any) => {
  try {
    let ruanganid = item.value.filterRuangan ? `&ruanganid=${item.value.filterRuangan}` : '';
    let dari = item.value.filterTgl.start ? `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}` : '';
    let sampai = item.value.filterTgl.end ? `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}` : '';
    let qnamapasien = item.value.qnamapasien ? `&qnamapasien=${item.value.qnamapasien}` : '';
    let qnocm = item.value.qnocm ? `&qnocm=${item.value.qnocm}` : '';
    let qnoregistrasi = item.value.qnoregistrasi ? `&qnoregistrasi=${item.value.qnoregistrasi}` : '';
    let statusOrder = q ? `&statusorder=${q}` : '';
    let search = item.value.qsearch ? `&search=${item.value.qsearch}` : '';

    isLoading.value = true;

    const response = await useApi().get(`/dashboard/get-order-bedah?${ruanganid}${dari}${sampai}${qnamapasien}${statusOrder}${qnocm}${qnoregistrasi}${search}`);

    modalFilter.value = false;

    response.forEach((element: any, i: any) => {
      element.no = i;
      let ini = element.namapasien.split(' ');
      let init = element.namapasien.substr(0, 2);
      if (ini.length > 1) {
        init = init + ini[1].substr(0, 1);
      }
      element.initials = init;
      element.ruanganasal = element.asal_ruangan;
      element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD');
    });

    isData.value = response.length;
    dataOrder.value = response;
  } catch (error) {
    console.error('Error fetching order data:', error);
  } finally {
    isLoading.value = false;
  }
}

const MonitoringJadwalOperasi = async (q: any) => {
  try {
    isLoading.value = true;
    const response = await useApi().get(`/dashboard/get-monitoring-operasi`);
    modalFilter.value = false;
    dataOrder.value = response; // Pastikan `response.data` berisi array

    console.log(response.value)
    console.log(dataOrder.value)

  } catch (error) {
    console.error('Error fetching order data:', error);
  } finally {
    isLoading.value = false;
  }
};


function changeTindakan(e: any) {
  // console.log(e)
  isLoading.value = true
  d_Komponen.value = []
  item.value.hargaLayanan = 0
  useApi().get(
    '/tindakan/list-tindakan-komponen?idRuangan=' + item.value.idRuanganTujuan
    + '&idKelas=' + item.value.kelas
    + '&idProduk=' + e.id
    + '&idJenisPelayanan=' + item.value.idJenisPelayanan
    // + '&idPenjamin=' + item.registrasi.objectrekananfk
  ).then((response: any) => {
    isLoading.value = false
    if (response.komponen.length == 0) {
      H.alert('warning', 'Komponen Tarif tidak ada')
      return
    }
    item.value.hargaLayanan = response.harga.hargasatuan
    item.hargasatuanDef = response.harga.hargasatuan
    item.value.jumlah = 1
    d_Komponen.value = response.komponen

  })
}


// const fetchDataOrder = async (q: any) => {

//     let ruanganid = ''
//     if (item.value.filterRuangan) {
//         ruanganid = `&ruanganid=${item.value.filterRuangan}`
//     }
//     let dari = ''
//     if (item.value.filterTgl.start) {
//         dari = `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
//     }
//     let sampai = ''
//     if (item.value.filterTgl.end) {
//         sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
//     }
//     let qnamapasien = ''
//     if (item.value.qnamapasien) {
//         qnamapasien = `&qnamapasien=${item.value.qnamapasien}`
//     }
//     let qnocm = ''
//     let qnoregistrasi = ''
//     let StatusOrder = ''
//     let search = ''
//     item.value.statusOrder = q
//     if (order) StatusOrder = '&statusorder=' + q
//     if (item.value.qnocm) qnocm = '&qnocm=' + item.value.qnocm
//     if (item.value.search) search = '&search=' + item.value.search
//     if (item.value.qnoregistrasi) qnoregistrasi = '&qnoregistrasi=' + item.value.qnoregistrasi
//     await useApi().get('/dashboard/get-order-bedah?ruanganid=' + ruanganid + '&dari=' + dari + '&sampai=' + sampai + '&qnamapasien=' + qnamapasien + '&statusorder=' + StatusOrder + '&qnocm=' + qnocm + '&qnoregistrasi=' + qnoregistrasi + '&search=' + search).then((response) => {
//         modalFilter.value = false
//         response.forEach((element: any, i: any) => {
//             element.no = i
//             let ini = element.namapasien.split(' ')
//             let init = element.namapasien.substr(0, 2)
//             if (ini.length > 1) {
//                 init = init + ini[1].substr(0, 1)
//             }
//             element.initials = init
//             // element.ruanganasal = (element.asal_ruangan.length > 20) ? element.ruanganasal = element.asal_ruangan.substring(0, 20) + '...' : element.ruanganasal = element.asal_ruangan
//             element.ruanganasal = element.asal_ruangan
//             element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD')
//         });
//         isData.value = response.length
//         dataOrder.value = response
//         isLoading.value = false
//     }).catch((err) => {
//         modalFilter.value = false
//     })
// }

async function fetchLaporan() {
  dataSource.value.loading = true
  let dari = ''
  if (item.value.filterTgl.start) {
    dari = `&dari=${H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')}`
  }
  let sampai = ''
  if (item.value.filterTgl.end) {
    sampai = `&sampai=${H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')}`
  }
  let produk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : ''

  await useApi().get(`/dashboard/laporan-tindakan-operasi?${dari}${sampai}`).then((response) => {
    response.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataLaporan.value = response
    dataLaporan.value.loading = false
  }).catch((e) => {
    dataLaporan.value.loading = false
  })
}

const fetchJadwal = async () => {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  let tgl = H.formatDate(item.value.filterDate, 'YYYY-MM-DD')

  dataOperasi.value = []
  dataOperasi.value.loading = true
  const response = await useApi().get('/dashboard/jadwal-operasi?ruanganid=' + ruanganid + '&tgl=' + tgl
  )
  dataOperasi.value.loading = false
  modalFilterJadwal.value = false
  dataOperasi.value = response.dataOperasi
}

const filterJadwal = () => {
  modalFilterJadwal.value = true
}
const fetchDetail = async () => {
  let ruanganid = ''
  if (item.value.filterRuangan) {
    ruanganid = item.value.filterRuangan
  }
  dokterPraktek.value = []
  dataStokObat.value = []
  const response = await useApi().get(
    '/dashboard/bedah-detail?ruanganid=' + ruanganid
  )
  dokterPraktek.value = response.dokter
  dataStokObat.value = response.produk
}

const getListPelayanan = async (data: any) => {
  const response = await useApi().get(`/dashboard/get-pelayanan-bedah?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}`)
  d_Produk.value = response.map((e: any) => {
    return { label: `${e.namaproduk} | ${e.hargasatuan},`, namaproduk: `${e.namaproduk}`, id: e.objectprodukfk }
  })
}


const getKomponenHarga = async (param: any) => {
  let datas = []
  await useApi().get('/dashboard/get-komponen-bedah?idProduk=' + param.layanan + '&idKelas=' + param.kelas + '&idJenLayan=' + param.idJenisPelayanan)
    .then((response) => {
      response.forEach((items: any) => {
        datas.push(items)
      })
    })
}

const orderVerify = async (e: any) => {

  dropdownList()
  e.loading = true
  let data = {
    'idkelas': e.objectkelasfk,
    'idJenisPelayanan': e.jenispelayananfk,
    'idRekanan': e.objectrekananfk
  }
  item.value.jenisoperasi = e.jenisoperasi
  item.value.idJenisPelayanan = e.jenispelayananfk
  item.value.namapasien = e.namapasien
  item.value.inisial = e.initials
  item.value.ruangantujuan = e.ruangantujuan
  item.value.noorder = e.noorder
  item.value.no_rm = e.pas_nocm
  item.value.jeniskelamin = e.jeniskelamin
  item.value.kelompokpasien = e.kelompokpasien
  item.value.idRuanganTujuan = e.objectruangantujuanfk
  item.value.pdNorec = e.pd_norec
  item.value.noregistrasi = e.noregistrasi
  item.value.soNorec = e.so_norec
  item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
  item.value.dokterorder = e.nama_pegawai
  item.value.tgloperasi = e.tgloperasi
  item.value.tglregistrasi = e.tglregistrasi
  item.value.kelas = e.objectkelasfk
  item.value.noregistrasi = e.noregistrasi
  getListPelayanan(data)
  const getHargaLayanan = await useApi().get(`/dashboard/get-detail-order?strukorderfk=${e.so_norec}&objectkelasfk=${e.objectkelasfk}`)
  const diagnosa = await useApi().get(`diagnosa/riwayat-diagnosa-x?nocmfk=${e.nocmfk}&norec_pd=${e.pd_norec}`)
  getHargaLayanan.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  detailDiagnosa.value = diagnosa.data
  detailOrderLayanan.value = getHargaLayanan
  modalDetailOrder.value = true
  e.loading = false
}

const getDetailVerify = async (e: any) => {
  e.loading = true
  item.value.idJenisPelayanan = e.jenispelayananfk
  item.value.namapasien = e.namapasien
  item.value.inisial = e.initials
  item.value.ruangantujuan = e.ruangantujuan
  item.value.noorder = e.noorder
  item.value.no_rm = e.pas_nocm
  item.value.jeniskelamin = e.jeniskelamin
  item.value.kelompokpasien = e.kelompokpasien
  item.value.idRuanganTujuan = e.objectruangantujuanfk
  item.value.dokterorder = e.nama_pegawai
  item.value.tglregistrasi = e.tglregistrasi
  item.value.tgloperasi = e.tgloperasi

  const response = await useApi().get(`/dashboard/get-bedah-verify?norec_so=${e.so_norec}`)
  const petugas = await useApi().get(`/dashboard/get-petugas-verify?norec_so=${e.so_norec}`)
  const diagnosa = await useApi().get(`diagnosa/riwayat-diagnosa-x?nocmfk=${e.nocmfk}&norec_pd=${e.pd_norec}`)
  response.forEach((element: any, i: any) => {
    element.no = i + 1
  });
  detailDiagnosa.value = diagnosa.data
  detailOrderVerify.value = response
  detailPetugas.value = petugas
  e.loading = false
  modalDetailOrderVerify.value = true
}
const jadwalOperasi = async (e: any) => {
  e.loading = true

  e.loading = false
  modalJadwalBedah.value = true
}
const saveJadwalBedah = async (e: any) => {
  e.loading = true

  console.log('cek save bedah', e)

  e.loading = false
}

const save = async () => {
  if (detailOrderLayanan.value.length < 1) {
    useToaster().error('Order Tidak Boleh kosong')
    return
  }
  if (!item.value.estimasiwaktuoperasi) {
    useToaster().warn('estimasiwaktuoperasi Tidak Boleh kosong')
    return
  }
  if (listItem.value.length <= 0) {
    useToaster().error('Pilih Setidaknya 1 Petugas')
    return
  }
  let datas = []
  let datass = []
  let petugas = []
  for (let i = 0; i < listItem.value.length; i++) {
    const element = listItem.value[i];
    var jenispet = ''
    for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
      const element2: any = d_JenisPelaksana.value[z];
      if (element.jenisPelaksana == element2.value) {
        jenispet = element2.label
        break
      }
    }
    var listPegawai: any = []
    for (let k = 0; k < element.pegawai.length; k++) {
      const elementxx = element.pegawai[k];
      var namaPegawai = ''
      for (let zz = 0; zz < d_Pegawai.value.length; zz++) {
        const elementPeg: any = d_Pegawai.value[zz];
        if (elementxx == elementPeg.value) {
          namaPegawai = elementPeg.label
          break
        }
      }
      listPegawai.push({
        'id': elementxx,
        'namalengkap': namaPegawai
      })
    }
    petugas.push({
      "objectjenispetugaspefk": element.jenisPelaksana,
      "jenispetugaspe": jenispet,
      "listpegawai": listPegawai
    })
  }
  let parameter = {
    'idruangtujuan': item.value.idRuanganTujuan,
    'pd_norec': item.value.pdNorec,
    'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
    'tglregistrasi': item.value.tglregistrasi,
    'noregistrasi': item.value.noregistrasi,
    'so_norec': item.value.soNorec,
    'objectkelasfk': item.value.kelas,
    // 'catatan': item.value.catatan,
    // 'dokterverify': item.value.dokterverify.value,
    // 'dokteroperasi': item.value.dokteroperasi.value,
    // 'catatan_klinis': item.value.catatankliniks,
    'estimasiwaktuoperasi': item.value.estimasiwaktuoperasi,
    'ruanganoperasi': item.value.ruanganJadwalOperasi.value,
    'kamaroperasi': item.value.kamarJadwalOperasi.value,
    'tgloperasi': item.value.tgloperasi,
  }

  for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
    let response = detailOrderLayanan.value[i]
    let komponenHarga = detailOrderLayanan.value[i].komponenharga
    var objSave = {
      'idProduk': response.prid,
      'hargaLayanan': response.hargasatuan,
      'tglpelayanan': response.tglpelayanan,
      'jumlah': response.qtyproduk,
      'komponenharga': komponenHarga,
      'pelayananpetugas': petugas
    }
    datas.push(objSave)
  }
  isLoading.value = true;
  await useApi().post('/dashboard/save-order-pelayanan-bedah', { 'data': datas, 'parameter': parameter }).then((response: any) => {
    modalDetailOrder.value = false
    fetchDataOrder(0)
    isLoading.value = false;
  }).catch((error) => {
    isLoading.value = false;
    useToaster().error('Something Went Wrong')
  })
}

const hapusItems = (e: any) => {
  for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
    if (detailOrderLayanan.value[i].no == e.no) {
      detailOrderLayanan.value.splice(i, 1);
    }
  }
  dataSource.value = detailOrderLayanan.value
}


const changeSwitch = (e: any) => {
  fetchDataOrder(e)
}

const showModalFilter = () => {
  modalFilter.value = true
}

const clear = () => {
  item.value.id = ''
  item.value.no = ''
  item.value.layanan = ''
  item.value.hargaLayanan = ''
  item.value.qtyproduk = ''
  item.value.jumlah = ''
}

const reload = () => {
  fetchJadwal()
}
const orderBarang = () => {
  router.push({
    name: 'module-logistik-order-barang'
  })
}

const add = async () => {

  let datas: any = []
  if (!item.value.produk) {
    useToaster().error('Layanan Tidak Boleh Kosong')
    return
  }
  if (!item.value.hargaLayanan) {
    useToaster().error('Harga Tidak Boleh Kosong')
    return
  }
  if (!item.value.jumlah) {
    useToaster().error('Jumlah Tidak Boleh Kosong')
    return
  }

  // let jumlah = e.jumlah
  // let harga = e.hargaLayanan
  // let ruangan = e.ruangantujuan
  // let namaproduk = e.namaproduk
  // let layanan = e.layanan
  let no

  (detailOrderLayanan.value.length == 0) ? no = 1 : no = detailOrderLayanan.value.length + 1

  let data = {
    no: no,
    prid: item.value.produk.id,
    tglpelayanan: moment(new Date()).format('YYYY-MM-DD HH:mm:ss'),
    namaproduk: item.value.produk.namaproduk,
    komponenharga: d_Komponen.value,
    hargasatuan: item.value.hargaLayanan,
    qtyproduk: item.value.jumlah,
    ruangantujuan: item.value.ruangantujuan,
  }
  detailOrderLayanan.value.push(data)
}



const update = (e: any) => {
  // console.log(e)
  let data: any = {}
  for (let x = 0; x < detailOrderLayanan.value.length; x++) {
    const element = detailOrderLayanan.value[x];
    if (element.no == e.no) {
      data.no = element.no
      data.qtyproduk = e.jumlah
      data.hargasatuan = e.hargaLayanan
      data.komponenharga = d_Komponen.value
      data.prid = e.produk.id
      data.tglpelayanan = moment(new Date()).format('YYYY-MM-DD HH:mm:ss')
      data.namaproduk = e.produk.namaproduk
      data.ruangantujuan = e.ruangantujuan
      detailOrderLayanan.value[x] = data
    }
  }
  clear()

}
const edit = (e: any) => {
  item.value.no = e.no
  d_Produk.value.forEach(element => {
    if (element.id == e.prid) {
      item.value.produk = element
      return
    }
  });
  d_Komponen.value = e.komponenharga
  item.value.hargaLayanan = e.hargasatuan
  item.value.jumlah = e.qtyproduk
}

const fetchdDropdown = async () => {
  const response = await useApi().get(`/dashboard/list-bedah`)
  d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
  d_Kamar.value = response.kamar.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
  d_Dokter.value = response.namalengkap.map((e: any) => {
    return { label: e.namalengkap, value: e.id }
  })
}

const changePeriode = () => {
  fetchDataOrder(0)
  fetchLaporan()
}

const exportExcel = () => {
  remakeData.value = dataLaporan.value.map((e: any) => {
    return {
      NamaPasien: e.namapasien, AsalRuangan: e.asalruangan, Tindakan: e.namaproduk,
      tgloperasi: e.tgloperasi, dokterPemeriksa: e.dokterpemeriksa, Harga: e.hargasatuan, Total: e.total,
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'Laporan Tindakan');
}

const exportExcelOrder = () => {
  console.log(dataOrder.value)
  remakeData.value = dataOrder.value.map((e: any) => {
    return {
      Jam: null, NamaPasien: e.namapasien, NoCM: e.nocm, AsalRuangan: e.ruanganasal, RuangTindakan: null, RencanaTindakan: e.jenisoperasi, DokterOrder: e.nama_pegawai
    }
  })
  const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
  const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
  const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
  saveAsExcelFile(excelBuffer, 'Laporan Order');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
  let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
  let EXCEL_EXTENSION = '.xlsx';
  const data: Blob = new Blob([buffer], {
    type: EXCEL_TYPE
  });
  // const _url = window.URL.createObjectURL(data)
  // window.open(_url, EXCEL_EXTENSION).focus();
  const desiredFileName = fileName + EXCEL_EXTENSION;
  const link = document.createElement('a');
  link.href = window.URL.createObjectURL(data);
  link.download = desiredFileName;
  link.click();
  window.URL.revokeObjectURL(link.href);
  // window.open(_url,EXCEL_EXTENSION).focus()
  // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}
const cetakSEP = (e: any) => {
  qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
    'SEP', 1)
}

const changeRuang = (e: any) => {
  for (let x = 0; x < d_Ruangan.value.length; x++) {
    const element: any = d_Ruangan.value[x];
    if (e == element.value) {
      item.value.namaruangan = element.label
      break
    }
  }
  fetchDataOrder(0)
  fetchDetail()
  fetchJadwal()
}

const emr = (e: any) => {
  H.cacheHelper().set('xxx_cache_menu', undefined)
  router.push({
    name: 'module-emr-profile-pasien',
    query: {
      nocmfk: e.nocmfk,
      norec_pd: e.pd_norec,
      norec_apd: e.norec_apd
    }
  })
}
// const fetchPetugas = async () => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap`
//     ).then((response) => {
//         d_Petugas.value = response
//     })
// }

const changeJenis = async (e: any) => {

  // if (!e.jenisPelaksana) {
  //     H.alert('warning', 'Jenis pelaksana wajib dipilih')
  //     return
  // }
  isLoadChange.value = true
  await useApi().get('/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e.jenisPelaksana).then((response: any) => {
    if (response != null) {

      e.d_Pegawai = response.map((e: any) => {
        return {
          label: e.namalengkap, value: e.id
        }
      })
    } else {
      e.d_Pegawai = []
    }
  })
  isLoadChange.value = false
}

function dropdownList() {
  useApi().get(`tindakan/list-jenis-petugas`).then((response: any) => {
    d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
    item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1
    // console.log(response.jenispetugaspelaksana)
    for (let z = 0; z < listItem.value.length; z++) {
      const elementz = listItem.value[z];
      for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
        const element = response.jenispetugaspelaksana[x];
        if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
          elementz.jenisPelaksana = element.id
          changeJenis(elementz)
          break
        }
      }
    }
  })
}

const addNewItem = () => {
  listItem.value.push({
    jenisPelaksana: null,
    pegawai: [],
  });
}
const removeItem = (index: any) => {
  listItem.value.splice(index, 1)
}
watch(
  () => [
    order.value
  ], () => {
    changeSwitch(order.value)
  }
)
watch(
  () => item.value.filterTgl,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
      fetchDataOrder(0)
    }
  }
)


fetchDataOrder(0)
fetchLaporan()
fetchDetail()
fetchJadwal()
fetchdDropdown()
MonitoringJadwalOperasi(1)
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

.label-unset {
  text-overflow: unset !important;
}

.search-menu-bedah {
  height: 56px !important;
  white-space: nowrap;
  display: flex;
  flex-shrink: 0;
  align-items: center;
  background-color: white;
  border-radius: 8px;
  width: 100%;
  padding-left: 0.75rem;

  >div:not(:last-of-type) {
    border-right: 1px solid var(--search-border-color);
  }

  .search-bar {
    height: 55px;
    width: 100%;
    position: relative;
    display: flex;
    align-items: center;
    padding-right: 1.5rem;

    .field {
      width: 100%;
    }

    .multiselect-tags {
      padding-left: 2.5rem;
    }
  }

  .search-location-bedah,
  .search-job,
  .search-salary {
    display: flex;
    align-items: center;
    width: 50%;
    font-size: 14px;
    font-weight: 500;
    padding: 0 25px;
    height: 100%;
    font-family: var(--font);

    input {
      width: 100%;
      height: 90%;
      display: block;
      font-family: var(--font);
      color: var(--input-color);
      background-color: transparent;
      border: none;
    }

    svg {
      margin-right: 0.5rem;
      width: 18px;
      color: var(--primary);
      flex-shrink: 0;
    }
  }

  .search-button-bedah {
    background-color: var(--primary);
    min-width: 100px;
    height: 56px !important;
    border: none;
    font-weight: 500;
    font-family: var(--font);
    padding: 0 1rem;
    border-radius: 0 0.75rem 0.75rem 0;
    color: white;
    cursor: pointer;
    margin-left: auto;
  }
}
</style>
