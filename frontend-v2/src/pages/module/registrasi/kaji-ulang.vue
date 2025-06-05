<template>
  <ConfirmDialog group="positionDialog"></ConfirmDialog>
  <div class="columns is-multiline " style="position: relative;width: 130%;left: -11rem;">
    <div class="column is-12">
      <div class="form-layout is-stacked p-0">
        <div v-if="isLoadHeader == true" style="background:rgb(65, 185, 131);">
          <PlaceloadHeader />
        </div>
        <div class="form-outer" v-else>
          <div class="form-body p-2">
            <div class="business-dashboard hr-dashboard">
              <div class="columns is-multiline">
                <div class="column is-12 pt-0">
                  <div class="block-header">
                    <div class="left">
                      <div class="current-user">
                        <h3>{{ mitra.namaperusahaan }}</h3>
                      </div>
                    </div>
                    <div class="center">
                      <div class="columns">
                        <div class="column">
                          <h4 class="block-heading">No Pendaftaran</h4>
                          <p class="block-text">{{ mitra.nopendaftaran }}</p>
                          <h4 class="block-heading">Tgl Registrasi</h4>
                          <p class="block-text">{{ mitra.tgldaftar }}</p>
                        </div>
                      </div>
                    </div>
                    <div class="right">
                      <div class="columns">
                        <div class="column">
                          <h4 class="block-heading">No HP</h4>
                          <p class="block-text">{{ mitra.nohp }}</p>
                          <h4 class="block-heading">Email</h4>
                          <p class="block-text">{{ mitra.email }}</p>
                        </div>

                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="personal-dashboard personal-dashboard-v2">
          <div class="columns is-multiline">
            <div class="column is-12" style="padding: 18px;">
              <div class="dashboard-card has-margin-bottom">
                <div class="card-head">
                  <h3 class="dark-inverted"> PENGKAJIAN ULANG </h3>
                </div>
                <div class="active-projects">
                  <div class="columns is-multiline">
                    <div class="column is-4">
                    </div>
                    <div class="column is-2">
                    </div>
                    <div class="column is-6">
                      <div class="columns is-multiline is-pulled-right">
                        <div class="column is-5">
                          <VButton icon="feather:printer" raised bold @click="cetakLabel(item)" :loading="isLoadingBill"
                            color="purple">
                            Cetak Tanda Terima
                          </VButton>
                        </div>
                        <div class="column is-3">
                          <VButton icon="feather:plus-circle" raised bold class="w-100" @click="inputTindakan(item)"
                            :loading="isLoadingBill" color="info">
                            Input Alat
                          </VButton>
                        </div>
                        <div class="column is-4 ">
                          <SplitButton label="Lainnya" icon="pi pi-info-circle" size="medium" style="height: 38px;" />
                        </div>
                      </div>
                    </div>
                    <div class="column is-12">
                      <div class="column is-12">
                        <table class="tb-custom mt-3">
                          <thead>
                            <tr>
                              <th width="10%" class="text-center">
                              </th>
                              <th width="25%">Alat</th>
                              <th width="20%">Merk/Tipe</th>
                              <th>S/N</th>
                              <th>Jumlah</th>
                              <th>OPSI</th>
                            </tr>
                          </thead>
                          <tbody v-if="isLoadingBill">
                            <tr>
                              <td colspan="5">
                                <div class="list-view list-view-v1 is-fullwidth">
                                  <div class="list-view-inner">
                                    <div v-for="key in 6" :key="key" class="list-view-item mt-2">
                                      <VPlaceloadWrap>
                                        <VPlaceloadAvatar size="medium" />
                                        <VPlaceloadText last-line-width="60%" class="mx-2" />
                                        <VPlaceload class="mx-2" disabled />
                                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                        <VPlaceload class="mx-2 h-hidden-tablet-p" />
                                        <VPlaceload class="mx-2" />
                                      </VPlaceloadWrap>
                                    </div>
                                  </div>
                                </div>
                              </td>
                            </tr>
                          </tbody>
                          <div style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                            <tbody>
                              <tr>
                                <td colspan="5" class="koneng">
                                </td>
                              </tr>
                              <tr v-if="!isLoadingBill" v-for="(itemsDet, index) in dataSourcefiltered" :key="index"
                                style="border-bottom: 1px solid #ccc;">
                                <td width="5%">
                                </td>
                                <td width="30%">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="title-ruangan">{{ itemsDet.nopendaftaran }}</div>
                                      <div class="title-layan">{{ itemsDet.namaproduk }} </div>
                                      <div>
                                        <VTag color="info" :label="itemsDet.namaperusahaan" class="mr-2" />
                                        <!-- <VTag :color="'primary'" class="ml-2" label="Sudah Kajian"
                                            v-if="itemsDet.iskaji == true" /> -->
                                        <VTag :color="itemsDet.iskaji == true ? 'primary' : 'danger'"
                                          :label="itemsDet.iskaji == true ? 'Sudah Kaji' : 'Belum Kaji'" />
                                      </div>
                                    </div>
                                  </div>
                                </td>
                                <td class="center">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="title-ruangan">{{ itemsDet.namamerk }} - {{ itemsDet.namatipe }}</div>

                                    </div>
                                  </div>
                                </td>
                                <td class="center" style="text-align:center">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="title-ruangan">{{ itemsDet.namaserialnumber }}</div>
                                    </div>
                                  </div>
                                </td>
                                <td class="center">
                                  <div class="columns is-multiline">
                                    <div class="column is-12">
                                      <div class="title-ruangan">
                                        <p>1</p>
                                      </div>
                                    </div>
                                  </div>

                                </td>
                                <td class="center">
                                  <VIconButton color="info" light raised circle icon="feather:edit" class="mr-1"
                                    v-tooltip.bubble="'Kaji ulang'" @click="KajiUlang(itemsDet)" />
                                </td>
                              </tr>
                            </tbody>
                            <div class="search-results-wrapper"
                              v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                              <div class="search-results-body ">
                                <div class="page-placeholder">
                                  <div class="placeholder-content">
                                    <img class="light-image" style=" max-width: 340px;"
                                      :src="H.assets().iconNotFound_rev" alt="" />
                                    <img class="dark-image" style=" max-width: 340px;"
                                      :src="H.assets().iconNotFound_rev" alt="" />
                                    <h3>{{ H.assets().notFound }}</h3>
                                    <p class="is-larger">
                                      {{ H.assets().notFoundSubtitle }}
                                    </p>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <VModal :open="modalKajiUlang" title="Masukan Pengkajian Ulang" :noclose="false" size="big" actions="right"
    @close="modalKajiUlang = false, clear()">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-4">
            <VField>
              <VLabel>Nama Alat</VLabel>
              <VControl icon="feather:box">
                <VInput type="text" v-model="input.namaproduk" placeholder="Nama Pelayanan" class="is-rounded"
                  disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField label="Tanggal Kaji Ulang">
              <VDatePicker v-model="input.tanggalKajian" mode="dateTime" style="width: 100%">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" class="is-rounded" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Lokasi Kalibrasi</VLabel>
              <VControl>
                <AutoComplete v-model="input.lokasikalibrasi" :suggestions="d_lokasikalibrasi"
                  @complete="fetchLokasi($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                  class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                  placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Lingkup Kalibrasi</VLabel>
              <VControl>
                <AutoComplete v-model="input.lingkupkalibrasi" :suggestions="d_lingkup" @complete="fetchLingkup($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Manager</VLabel>
              <VControl icon="feather:user">
                <VInput type="text" v-model="input.namamanager" placeholder="Nama Manager" class="is-rounded"
                  disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Asman</VLabel>
              <VControl icon="feather:user">
                <VInput type="text" v-model="input.namaasman" placeholder="Nama Asman" class="is-rounded" disabled />
              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Penyelia Teknik</VLabel>
              <VControl>
                <AutoComplete v-model="input.penyeliateknik" :suggestions="d_penyelia" @complete="fetchPenyelia($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />

              </VControl>
            </VField>
          </div>
          <div class="column is-4">
            <VField>
              <VLabel>Pelaksana Teknik</VLabel>
              <VControl>
                <AutoComplete v-model="input.pelaksana" :suggestions="d_pelaksana" @complete="fetchPelaksana($event)"
                  :optionLabel="'label'" :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="ketik untuk mencari..." />
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <VField>
              <VLabel>Keterangan</VLabel>
              <VControl>
                <VTextarea v-model="input.keterangan" rows="3" placeholder="Keterangan">
                </VTextarea>
              </VControl>
            </VField>
          </div>
          <div class="column is-12">
            <FileUpload v-model="fileMitra" mode="basic" name="demo" accept="application/pdf" :maxFileSize="100000000"
              @upload="onUpload" outlined :invalidFileTypeMessage="'{0}: File yang diupload harus pdf.'"
              :invalidFileSizeMessage="'Ukuran maksimal berkas adalah {1}'"
              style=" background-color: transparent; color: var(--danger); border: 1px solid;"
              :chooseLabel="fileMitra ? fileMitra.name : 'Unggah'" @select="onSelect($event)"
              class="is-rounded w-100" />
            <button type="button" @click="openCamera" class="button is-primary mt-2">Buka Kamera</button>
            <button type="button" v-if="fileMitra" @click="previewFile" class="button is-link mt-2">Lihat File</button>
            <video ref="video" width="500" height="300" autoplay style="display: none;"></video>
            <canvas ref="canvas" style="display: none;"></canvas>
            <button type="button" v-if="isCameraActive" @click="takePhoto" class="button is-success mt-2">Ambil
              Foto</button>
          </div>
        </div>
      </form>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="simpan(item)" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
    </template>
  </VModal>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useWindowScroll } from '@vueuse/core'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useUserSession } from '/@src/stores/userSession'
import { debounce } from 'lodash'
import SpeedDial from 'primevue/speeddial'
import Dropdown from 'primevue/dropdown'
import { useConfirm } from "primevue/useconfirm"
import ConfirmDialog from 'primevue/confirmdialog'
import FileUpload from 'primevue/fileupload';
import SplitButton from 'primevue/splitbutton';
import AutoComplete from 'primevue/autocomplete';
import jsPDF from 'jspdf';

useHead({
  title: 'Kajian Ulang - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
let NOREC_PD = useRoute().query.norec_mitra_daftar as string
let TGLREGISTRASI = useRoute().query.tglregistrasi as string
let ID_MITRA = useRoute().query.nocmfk as string
const PARAMETER: any = ref('')
const isLoadingPasien: any = ref(false)
const isLoadingBill: any = ref(false)
const modalFilter: any = ref(false)
const isLoadingPop: any = ref(false)
const mitra: any = ref({})
const dataSource: any = ref([])
const dataSourcePetugas: any = ref([])
const dataPenunjang: any = ref([])
const router = useRouter()
const listChecked: any = ref([])
const modelCheck: any = ref([])
const filters = ref('')
const filterd = ref('')
const filtersHide = ref('')
const modalPetugas = ref(false)
const loadPasienPenunjang = ref(false)
const isLoadHeader = ref(true)
const modalKajiUlang: any = ref(false)
const modalKirimRIS: any = ref(false)
const modalCatatan: any = ref(false)
const dataSelect: any = ref({})
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const showJenis: any = ref(false)
const norecHasilRadiologi: any = ref('')
const disabledExp = ref(false)
const useNocmFilter = ref(false);
const fileMitra: any = ref()
const video: any = ref(null);
const canvas: any = ref(null);
const isCameraActive = ref(false);
const kajiUlangData = ref<any>(null);
const lokasiKalibrasiData = ref<any>(null);
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  TGLREGISTRASI: TGLREGISTRASI != undefined ? TGLREGISTRASI : '',
  registrasi: {},
  tglorder: new Date(),
  tanggal: new Date(),
  produkCeklis: [],
  pegawaiOrder: useUserSession().getUser().id,
  periode: reactive({
    start: new Date(),
    end: new Date(),
  }),
  isExpertise: false,
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})
const input: any = ref({})
const d_lokasikalibrasi = ref([])
const d_lingkup = ref([])
const d_penyelia = ref([])
const d_pelaksana = ref([])

const openCamera = () => {
  if (isMobileDevice()) {
    const videoInput = document.createElement('input');
    videoInput.type = 'file';
    videoInput.accept = 'image/*';
    videoInput.capture = 'environment';

    videoInput.onchange = async (event) => {
      const file = (event.target as HTMLInputElement).files?.[0];
      if (file) {
        const norec_detail = kajiUlangData.value.norec_detail;
        const nopendaftaran = kajiUlangData.value.nopendaftaran;
        const imageFileName = `${norec_detail}_${nopendaftaran}.jpeg`;

        const renamedFile = new File([file], imageFileName, { type: "image/jpeg" });
        fileMitra.value = renamedFile;
        console.log("Gambar berhasil diambil dari kamera (mobile):", fileMitra.value);
      }
    };

    videoInput.click();
  } else {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({
        video: { facingMode: { ideal: "environment" } }
      })
        .then((stream) => {
          if (video.value) {
            video.value.srcObject = stream;
            video.value.style.display = 'block';
            isCameraActive.value = true;
          }
        })
        .catch((err) => {
          console.error("Gagal mengakses kamera:", err);
        });
    }
  }
};

const takePhoto = () => {
  if (video.value) {
    const canvas = document.createElement('canvas');
    canvas.width = video.value.videoWidth;
    canvas.height = video.value.videoHeight;
    const context = canvas.getContext('2d');
    if (context) {
      context.drawImage(video.value, 0, 0, canvas.width, canvas.height);
      canvas.toBlob((blob) => {
        if (blob) {
          const norec_detail = kajiUlangData.value.norec_detail;
          const nopendaftaran = kajiUlangData.value.nopendaftaran;
          const imageFileName = `${norec_detail}_${nopendaftaran}.jpeg`;

          const imageFile = new File([blob], imageFileName, { type: "image/jpeg" });
          fileMitra.value = imageFile;
          console.log("Foto tersimpan sebagai Gambar:", fileMitra.value);
        }
        stopCamera();
      }, "image/jpeg", 1.0);
    }
  }
};

const stopCamera = () => {
  if (video.value && video.value.srcObject) {
    const stream = video.value.srcObject;
    const tracks = stream.getTracks();
    tracks.forEach((track: MediaStreamTrack) => track.stop());
    video.value.srcObject = null;
    video.value.style.display = 'none';
    isCameraActive.value = false;
  }
};

const previewFile = () => {
  if (fileMitra.value) {
    const url = URL.createObjectURL(fileMitra.value);
    window.open(url, "_blank");
  } else {
    alert("Tidak ada file yang bisa dibuka.");
  }
};

const isMobileDevice = () => {
  return /Mobi|Android/i.test(navigator.userAgent);
};

const add = () => {
  fileMitra.value = null;
  input.value = {};
  modalInput.value = true;
};

const onSelect = async (filez: any) => {
  const file = filez.files[0];

  console.log(file.size);
  if (file.size > 10000000) {
    H.alert('error', 'Maksimal file size adalah 10 MB');
    return;
  }

  if (!file.type.startsWith("image/")) {
    H.alert('error', 'File yang diizinkan harus berupa gambar (JPEG, PNG, dsb)');
    return;
  }

  fileMitra.value = file;
};


const simpan = async () => {
  if (!input.value.tanggalKajian) {
    H.alert('error', 'Tanggal Kajian harus di isi')
    return
  }
  if (!input.value.lokasikalibrasi.value) {
    H.alert('error', 'Lokasi Kalibrasi harus di isi')
    return
  }
  if (!input.value.lingkupkalibrasi.value) {
    H.alert('error', 'Lingkup Kalibrasi harus di isi')
    return
  }
  if (!input.value.penyeliateknik.value) {
    H.alert('error', 'Penyelia Teknik harus di isi')
    return
  }
  if (!input.value.pelaksana.value) {
    H.alert('error', 'Pelaksana Teknik harus di isi')
    return
  }
  if (!fileMitra.value) {
    H.alert('error', 'File harus di unggah')
    return
  }
  const formData = new FormData()
  formData.append('fileMitra', fileMitra.value)
  formData.append('noregistrasifk', NOREC_PD)
  formData.append('norec', kajiUlangData.value.norec_detail)
  formData.append('keterangan', input.value.keterangan)
  formData.append('tanggalKajian', input.value.tanggalKajian)
  formData.append('lokasikalibrasi', input.value.lokasikalibrasi.value)
  formData.append('lingkupkalibrasi', input.value.lingkupkalibrasi.value)
  formData.append('penyeliateknik', input.value.penyeliateknik.value)
  formData.append('pelaksana', input.value.pelaksana.value)
  isLoadingPop.value = true
  await useApi().post('/registrasi/save-kajian-ulang', formData).then((r) => {
    isLoadingPop.value = false
    fetchLayanan()
    modalKajiUlang.value = false
    clear()
  }).catch((error: any) => {
    isLoadingPop.value = false
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

const edit = async (e: any) => {
  input.value.norec = e.norec
  input.value.keterangan = e.deskripsi
  input.value.nama = e.nama
  d_Berkas.value.forEach((element: any) => {
    if (e.objectberkaspasien == element.id) {
      input.value.namafile = element
    }
  });
  let path = 'berkaspasien/' + e.nocm + '/' + e.namafile

  let file = await H.getFileBE(path);

  fileMitra.value = file
  fileMitra.value.name = e.namafile
  modalInput.value = true
}

const listButton: any = ref([
  {
    label: 'Catatan ',
    icon: 'pi pi-book',
    command: () => {
      if (listChecked.value.length == 0) {
        H.alert('error', 'Ceklis data Untuk Menambahkan Catatan')
        return
      }
      modalCatatan.value = true
    }
  },

])
const confirm = useConfirm();

const isDetail: any = ref(false)
const dataSourcefiltered: any = computed(() => {

  if (!filters.value && !filtersHide.value) {
    return dataSource.value
  }
  var filtered: any = [];
  for (let x = 0; x < dataSource.value.length; x++) {
    const element = dataSource.value[x];
    var filteredD = [];
    for (let z = 0; z < element.details.length; z++) {
      const element2 = element.details[z];
      if (filters.value) {
        if (element2.namaproduk.match(new RegExp(filters.value, 'i'))
        ) {
          filteredD.push(element2);
          filtered.push({
            details: filteredD
          })
          break
        }
      } else if (filtersHide.value) {
        if (element2.jenis.match(new RegExp(filtersHide.value, 'i'))
        ) {
          for (let xxx = 0; xxx < filtered.length; xxx++) {
            const elementxxx = filtered[xxx];
          }
          filteredD.push(element2);
          filtered.push({
            details: filteredD
          })
          // break
        }
      }

    }
  }
  return filtered;
})

const headerPasien = async (id: any, norec_pd: any, tglregistrasi: any) => {
  await useApi().get(`/registrasi/header-mitra?nocmfk=${id}&norec_pd=${norec_pd}&tglregistrasi=${tglregistrasi}`)
    .then((response: any) => {
      mitra.value = response.mitra
      fetchLayanan(norec_pd)
    })
  isLoadHeader.value = false
}

const fetchLayanan = async () => {
  isLoadingBill.value = true
  dataSource.value = []
  await useApi().get(
    `/registrasi/layana-mitra?norec_pd=${NOREC_PD}&tglregistrasi=${TGLREGISTRASI}`).then(async (response: any) => {
      dataSource.value = response.detail
      item.TOTAL = response.total
      item.length = response.length
      isLoadingBill.value = false

    })
  modalKirimRIS.value = false
}

const inputTindakan = (e: any) => {
  router.push({
    name: 'module-registrasi-registrasi-mitra',
    query: {
      norec_pasien_daftar: NOREC_PD,
      nocmfk: mitra.value.nocmfk,
      norec_apd: NOREC_APD
    },
  })
}

const KajiUlang = async (e: any) => {
  input.value.tanggalKajian = new Date();

  await useApi().get(
    `/registrasi/layana-mitra?norec_pd=${NOREC_PD}&norecdetail=${e.norec_detail}`
  ).then(async (response: any) => {
    const detail = response.detail?.[0] ?? {};

    input.value.lokasikalibrasi = {
      value: detail.lokasikalibrasifk ?? '',
      label: detail.lokasi ?? ''
    };

    input.value.lingkupkalibrasi = {
      value: detail.lingkupfk ?? '',
      label: detail.lingkupkalibrasi ?? ''
    };

    input.value.penyeliateknik = {
      value: detail.penyeliateknikfk ?? '',
      label: detail.penyeliateknik ?? ''
    };

    input.value.pelaksana = {
      value: detail.pelaksanateknikfk ?? '',
      label: detail.pelaksanateknik ?? ''
    };

    fileMitra.value = detail.namafile ?? '';
    input.value.keterangan = detail.keterangan ?? '';
  });

  input.value.namaproduk = e.namaproduk ?? '';
  kajiUlangData.value = e;
  modalKajiUlang.value = true;
  setAutoFill();
};



const cetakExpertise = () => {
  H.printBlade("radiologi/cetak-ekspertise?echo=true&norec=" + norecHasilRadiologi.value);
}

const showModalFilter = () => {
  modalFilter.value = true
}

const fetchLokasi = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/lokasikalibrasi_m?select=id,lokasi&param_search=lokasi&query=${filter.query}&limit=10`
  ).then((response) => {
    d_lokasikalibrasi.value = response
  })
}

const fetchLingkup = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/lingkupkalibrasi_m?select=id,lingkupkalibrasi&param_search=lingkupkalibrasi&query=${filter.query}&limit=10`
  ).then((response) => {
    d_lingkup.value = response
  })
}

const fetchPenyelia = async (filter: any) => {
  let lokasi = input.value.lokasikalibrasi.value
  await useApi().get(
    `registrasi/pegawai-lokasi-kalibrasi?lokasi=${lokasi}&param_search=namalengkap&query=${filter.query}`).then((response) => {
      d_penyelia.value = response.data.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })
}

const fetchPelaksana = async (filter: any) => {
  let lokasi = input.value.lokasikalibrasi.value
  await useApi().get(
    `registrasi/pegawai-lokasi-kalibrasi?lokasi=${lokasi}&param_search=namalengkap&query=${filter.query}`).then((response) => {
      d_pelaksana.value = response.data.map((e: any) => {
        return { label: e.namalengkap, value: e.id }
      })
    })
}

const setAutoFill = async () => {
  input.value.tglPembuatan = new Date()
  let manager = 2;
  let asman = 3;
  await useApi().get(
    `registrasi/pegawai-kalibrasi?jabatan=${manager}`).then((response) => {
      input.value.namamanager = response.data.namalengkap
    })
  await useApi().get(
    `registrasi/pegawai-kalibrasi?jabatan=${asman}`).then((response) => {
      input.value.namaasman = response.data.namalengkap
    })
}


const clear = () => {
  item.norec = ''
  item.nomorpa = ''
  item.pegawaifk = ''
  item.dokterpengirimfk = ''
  item.pelayananpasienfk = ''
  item.noregistrasifk = ''
  item.diagnosaklinik = ''
  item.keteranganklinik = ''
  item.diagnosapb = ''
  item.keteranganpb = ''
  item.topografi = ''
  item.morfologi = ''
  item.makroskopik = ''
  item.mikroskopik = ''
  item.kesimpulan = ''
  item.anjuran = ''
  item.jaringanasal = ''
  item.pegawaifk = ''
  item.keterangan = ''
  input.value.keterangan = ''
  fileMitra.value = ''

  modalKajiUlang.value = false
  modalCatatan.value = false
}

watch(
  () => ID_MITRA,
  (newValue, oldValue) => {
    if (newValue != oldValue) {

    }
  }
)


const cetakLabel = async (e: any) => {
  let so_norec = e.NOREC_PD;
  H.printBlade(`report/cetak-label-tindakan?norec=${so_norec}&type=radiologi`);
}

headerPasien(ID_MITRA, NOREC_PD, TGLREGISTRASI)

</script>
<style lang="scss">
@import '/@src/scss/main';
@import '/@src/scss/module/kasir/billing';

.field>label {
  color: hsl(0deg, 0%, 4%) !important;
}

.hr-dashboard {
  .block-header {
    display: flex;
    border-radius: 16px;
    padding: 20px;
    background: var(--primary);
    font-family: var(--font);
    box-shadow: var(--primary-box-shadow);

    .left,
    .right {
      width: 30%;
    }

    .center {
      display: flex;
      flex-direction: column;
      width: 40%;
      padding-right: 10px;
      margin-right: 30px;
      border-right: 1px solid var(--primary-light-10);

      .block-text {
        margin-bottom: 16px;
      }

      .candidates {
        margin-top: auto;

        >.v-avatar {
          margin-right: 10px;
        }

        button {
          height: 40px;
          width: 40px;
          display: inline-flex;
          justify-content: center;
          align-items: center;
          border-radius: 10px;
          background: var(--white);
          color: var(--light-text);
          border: none;
          cursor: pointer;
          transition: all 0.3s; // transition-all test

          svg {
            height: 18px;
            width: 18px;
          }
        }
      }
    }

    .left {
      display: flex;
      justify-content: center;
      align-items: center;

      .current-user {
        .v-avatar {
          margin-bottom: 1rem;
        }

        h3 {
          font-family: var(--font-alt);
          font-weight: 700;
          font-size: 1.8rem;
          color: var(--white);
          line-height: 1.2;
        }
      }
    }

    .right {
      display: flex;
      flex-direction: column;

      .button {
        margin-top: auto;
      }
    }

    .block-heading {
      font-family: var(--font-alt);
      font-weight: 600;
      font-size: 1.1rem;
      color: var(--white);
      margin-bottom: 4px;
    }

    .block-text {
      font-family: var(--font);
      font-size: 0.9rem;
      color: var(--white);
      margin-bottom: 16px;
    }

    .header-meta {
      margin-left: 0;
      padding-right: 30px;

      h3 {
        color: var(--smoke-white);
        font-family: var(--font-alt);
        font-weight: 700;
        font-size: 1.3rem;
        max-width: 280px;
      }

      p {
        font-weight: 400;
        color: var(--smoke-white-dark-2);
        margin-bottom: 16px;
        max-width: 320px;
      }

      .action-link {
        span {
          font-size: 0.8rem;
          text-transform: uppercase;
          margin-right: 6px;
        }

        i {
          font-size: 12px;
        }
      }
    }
  }

  .feed-settings {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 20px 0;

    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    .button {
      font-size: 0.8rem;
      border-radius: 8px;
      margin-right: 4px;

      &.is-selected {
        background: var(--primary);
        color: var(--white);
        border-color: var(--primary);
        box-shadow: var(--primary-box-shadow);
      }
    }
  }

  .side-text {
    h3 {
      font-family: var(--font-alt);
      font-size: 1.1rem;
      font-weight: 600;
      color: var(--dark-text);
      margin-bottom: 8px;
    }

    p {
      font-size: 0.95rem;
      margin-bottom: 8px;
    }

    .action-link {
      font-size: 0.9rem;
    }
  }

  .recent-rookies {
    .recent-rookies-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 20px;

      h3 {
        font-family: var(--font-alt);
        font-size: 2rem;
        font-weight: 600;
        color: var(--dark-text);
      }
    }

    .user-grid {
      &.user-grid-v4 {
        .grid-item {
          @include vuero-l-card;
        }
      }
    }
  }
}
</style>
