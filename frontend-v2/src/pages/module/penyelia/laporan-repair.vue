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
                  <h4 class="block-heading">No Order</h4>
                  <p class="block-hext">{{ item.noorderalat }}</p>
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
          <i class="fas fa-tools mr-2" aria-hidden="true"></i>
          <span>Laporan Repair</span>
          <Badge :value="totalData" v-if="totalData > 0" severity="danger" class="ml-2" />
        </template>
        <VCard>
          <div class="column is-12">
            <div class="columns is-multiline" style="text-align:center">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Laporan Alat </h3>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-5">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.keterangan" rows="5" placeholder="keterangan" disabled>
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
              <div class="column is-5 mt-2">
                <div :style="'background-image:url(' + MARKINGSITE + ')'"
                  style="text-align: center; background-repeat: no-repeat; background-position: center; width: 600px; height: 400px;">
                </div>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <Fieldset legend="- STATUS ALAT" :toggleable="true">
                  <div style="overflow-y:auto;" class="mt-5 form-section-inner is-horizontal">
                    <table width="100%">
                      <thead>
                        <tr class="tr-po">
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Bagian Alat
                          </th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Dokumentasi
                          </th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Kondisi
                          </th>
                          <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody v-for="(items, index) in item.detailLaporanStatus" :key="index">
                        <tr class="tr-po">
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl>
                                  <VTextarea v-model="items.bagianalatstatus" rows="2" placeholder="Bagian Alat">
                                  </VTextarea>
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <FileUpload v-model="fileMitraStatus[index]" mode="advanced" name="demo"
                                accept="image/jpeg,image/jpg" :maxFileSize="10000000" @upload="onUpload" outlined
                                :invalidFileTypeMessage="'{0}: File yang diupload harus JPEG/JPG.'"
                                :invalidFileSizeMessage="'Ukuran maksimal berkas adalah {1}'"
                                style="background-color: transparent; color: var(--danger); border: 1px solid;"
                                :chooseLabel="fileMitraStatus[index] ? fileMitraStatus[index].name : 'Unggah'"
                                @select="onSelectStatus($event, index)" class="is-rounded w-100" /><br>
                              <button class="button is-primary" @click="openCameraModalStatus(index)">Buka
                                Kamera</button>
                              <button class="button is-link" v-if="fileMitraListStatus[index]"
                                @click="previewFileStatus(index)">Lihat
                                File</button>
                            </div>
                          </td>
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl>
                                  <VTextarea v-model="items.kondisistatus" rows="3" placeholder="Kondisi">
                                  </VTextarea>
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-po" style="vertical-align: inherit;">
                            <div class="column is-12 pl-0 pr-0">
                              <VButtons style="justify-content: space-around;">
                                <VIconButton type="button" raised circle icon="feather:plus"
                                  v-tooltip-prime.bottom="'Tambah'" @click="addNewLaporanStatus(items)" outlined
                                  color="info">
                                </VIconButton>
                                <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Hapus'" outlined
                                  icon="feather:trash" @click="removeLaporanStatus(items)" color="danger">
                                </VIconButton>
                              </VButtons>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </Fieldset>
                <br>
                <Fieldset legend="- TINDAKAN TEKNIS" :toggleable="true">
                  <div style="overflow-y:auto;" class="mt-5 form-section-inner is-horizontal">
                    <table width="100%">
                      <thead>
                        <tr class="tr-po">
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Bagian Alat
                          </th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Penanganan
                          </th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Status
                          </th>
                          <th class="th-po" width="20%" style="vertical-align:inherit;text-align: center;">
                            Sparepart & Material Comsumable
                          </th>
                          <th class="th-po" width="15%" style="vertical-align:inherit;text-align: center;">Aksi</th>
                        </tr>
                      </thead>
                      <tbody v-for="(items, index) in item.detailLaporanRepair" :key="index">
                        <tr class="tr-po">
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl icon="feather:map" fullwidth>
                                  <VInput v-model="items.bagianalatlaporan" />
                                </VControl>
                              </VField>
                              <FileUpload v-model="fileMitra[index]" mode="advanced" name="demo"
                                accept="image/jpeg,image/jpg" :maxFileSize="10000000" @upload="onUpload" outlined
                                :invalidFileTypeMessage="'{0}: File yang diupload harus JPEG/JPG.'"
                                :invalidFileSizeMessage="'Ukuran maksimal berkas adalah {1}'"
                                style="background-color: transparent; color: var(--danger); border: 1px solid;"
                                :chooseLabel="fileMitra[index] ? fileMitra[index].name : 'Unggah'"
                                @select="onSelect($event, index)" class="is-rounded w-100" /><br>
                              <button class="button is-primary" @click="openCameraModal(index)">Buka Kamera</button>
                              <button class="button is-link" v-if="fileMitraList[index]"
                                @click="previewFile(index)">Lihat
                                File</button>
                            </div>
                          </td>
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl>
                                  <VTextarea v-model="items.penanganan" rows="3" placeholder="Penanganan">
                                  </VTextarea>
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl>
                                  <VTextarea v-model="items.status" rows="3" placeholder="Status">
                                  </VTextarea>
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-po">
                            <div class="column pt-3 pb-0">
                              <VField>
                                <VControl>
                                  <VTextarea v-model="items.sparepart" rows="3" placeholder="Sparepart">
                                  </VTextarea>
                                </VControl>
                              </VField>
                            </div>
                          </td>
                          <td class="td-po" style="vertical-align: inherit;">
                            <div class="column is-12 pl-0 pr-0">
                              <VButtons style="justify-content: space-around;">
                                <VIconButton type="button" raised circle icon="feather:plus"
                                  v-tooltip-prime.bottom="'Tambah'" @click="addNewLaporanRepair(items)" outlined
                                  color="info">
                                </VIconButton>
                                <VIconButton type="button" raised circle v-tooltip-prime.bottom="'Hapus'" outlined
                                  icon="feather:trash" @click="removeLaporanRepair(items)" color="danger">
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
            </div>
            <div class="columns is-multiline">
              <div class="column is-12">
                <VField>
                  <VControl>
                    <VTextarea v-model="item.kesimpulan" rows="4" placeholder="Kesimpulan">
                    </VTextarea>
                  </VControl>
                </VField>
              </div>
            </div>
            <div class="columns is-multiline">
              <div class="column is-12 is-flex is-justify-content-flex-end mt-4">
                <VButton icon="feather:save" @click="Save()" :loading="isLoadingSave" color="info">Simpan</VButton>
              </div>
            </div>
          </div>
        </VCard>
        <VCard>
          <div class="column is-12">
            <div class="columns is-multiline" style="text-align:center">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Status Alat Repair </h3>
              </div>
            </div>
            <DataTable :value="dataSourceHasilLaporanRepairStatus" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
              <Column field="no" header="No" />
              <Column field="bagianalat" header="Penanganan" />
              <Column field="kondisi" header="Status" />
              <Column header="Dokumentasi" style="text-align:center; min-width: 150px" row-clickable>
                <template #body="slotProps">
                  <div
                    :style="'background-image:url(' + 'https://ulabumro.id:8000/berkas-laporan-repair/' + slotProps.data.fotoalatstatus + ')'"
                    style="text-align: center; background-position: center; width: 400px; height: 300px;">
                  </div>
                </template>
              </Column>
              <Column field="created_at" header="Tanggal Isi Status" />
              <Column field="petugasisistatus" header="Pengisi" />
              <Column header="Aksi" style="text-align:center; min-width: 150px" row-clickable>
                <template #body="slotProps">
                  <VIconButton color="danger" outlined circle icon="fas fa-trash" @click="hapusStatus(slotProps.data)"
                    :loading="isLoadingSave" />
                </template>
              </Column>
            </DataTable>
          </div>
        </VCard>
        <hr>
        <hr>
        <VCard>
          <div class="column is-12">
            <div class="columns is-multiline" style="text-align:center">
              <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1">Data Tindakan Teknis Repair</h3>
              </div>
            </div>
            <DataTable :value="dataSourceHasilLaporanRepair" :paginator="true" :rows="10"
              :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers p-datatable-sm" filterDisplay="menu"
              paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
              responsiveLayout="stack" breakpoint="960px" sortMode="multiple" showGridlines
              currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" :loading="isLoading">
              <template #header>
                <div class="columns is-multiline is-vcentered is-mobile pt-0 pb-0" style="gap: 0.25rem;">
                  <div class="column is-narrow" v-if="dataSourceHasilLaporanRepair.length > 0">
                    <VButtons style="justify-content: space-between;">
                      <VButton color="primary" @click="cetakLaporanRepair()" outlined icon="feather:printer">
                        Cetak Laporan
                      </VButton>
                    </VButtons>
                  </div>
                  <div class="column is-narrow" v-if="dataSourceHasilLaporanRepair.length > 0">
                    <VButton color="info" @click="setujuiLaporan()" outlined icon="feather:save"
                      :loading="isLoadingSave">
                      Setujui Laporan
                    </VButton>
                  </div>
                </div>
              </template>
              <Column field="no" header="No" />
              <Column field="bagianalatlaporan" header="Bagian Alat" />
              <Column field="penanganan" header="Penanganan" />
              <Column field="status" header="Status" />
              <Column field="sparepart" header="Sparepart" />
              <Column header="Foto Repair" style="text-align:center; min-width: 150px" row-clickable>
                <template #body="slotProps">
                  <div
                    :style="'background-image:url(' + 'https://ulabumro.id:8000/berkas-laporan-repair/' + slotProps.data.fotoalatrepair + ')'"
                    style="text-align: center; background-position: center; width: 400px; height: 300px;">
                  </div>
                </template>
              </Column>
              <Column field="created_at" header="Tanggal Isi" />
              <Column field="petugasrepair" header="Pengisi" />
              <Column header="Aksi" style="text-align:center; min-width: 150px" row-clickable>
                <template #body="slotProps">
                  <VIconButton color="danger" outlined circle icon="fas fa-trash" @click="hapusLaporan(slotProps.data)"
                    :loading="isLoadingSave" />
                </template>
              </Column>
            </DataTable>
          </div>
        </VCard>
      </TabPanel>
    </TabView>
  </div>
  <VModal :open="modalFotoRepair" title="Masukan Foto Alat" :noclose="false" size="big" actions="right"
    @close="closeCameraModal">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12 has-text-centered">
            <div class="is-flex is-flex-direction-column is-align-items-center">
              <video ref="video" width="500" height="300" autoplay style="display: none;"></video>
              <canvas ref="canvas" style="display: none;"></canvas>
              <div class="buttons mt-4">
                <button type="button" class="button is-success" v-if="isCameraActive" @click="takePhoto">Ambil
                  Foto</button>
                <button type="button" class="button is-light" @click="closeCameraModal">Tutup Kamera</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
    </template>
  </VModal>
  <VModal :open="modalFotoStatus" title="Masukan Foto Status Alat" :noclose="false" size="big" actions="right"
    @close="closeCameraModalStatus">
    <template #content>
      <form class="modal-form">
        <div class="columns is-multiline">
          <div class="column is-12 has-text-centered">
            <div class="is-flex is-flex-direction-column is-align-items-center">
              <video ref="video" width="500" height="300" autoplay style="display: none;"></video>
              <canvas ref="canvas" style="display: none;"></canvas>
              <div class="buttons mt-4">
                <button type="button" class="button is-success" v-if="isCameraActiveStatus"
                  @click="takePhotoStatus">Ambil
                  Foto</button>
                <button type="button" class="button is-light" @click="closeCameraModalStatus">Tutup Kamera</button>
              </div>
            </div>
          </div>
        </div>
      </form>
    </template>
    <template #action>
    </template>
  </VModal>
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
  title: 'Laporan Repair - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const MARKINGSITE: any = ref('')
let NOREC_DETAIL = useRoute().query.norec_detail as string
const isLoadingSave = ref(false)
let loadSearch: any = ref(false)
const dataSource: any = ref([])
const dataSourceHasilLaporanRepair: any = ref([])
const dataSourceHasilLaporanRepairStatus: any = ref([])
const item: any = ref({
  tglkalibrasi: new Date(),
  detailLaporanRepair: [{
    no: 1
  }],
  detailLaporanStatus: [{
    no: 1
  }]
})
const router = useRouter()
const fileMitra: any = ref([])
const video: any = ref(null);
const canvas: any = ref(null);
const isCameraActive = ref(false);
const modalFotoRepair = ref(false)
const fileMitraList = ref<File[]>([])
const currentRowIndex = ref<number | null>(null)

const fileMitraStatus: any = ref([])
const modalFotoStatus = ref(false)
const fileMitraListStatus = ref<File[]>([])
const isCameraActiveStatus = ref(false);
const currentRowIndexStatus = ref<number | null>(null)


const openCameraModal = (index: number) => {
  currentRowIndex.value = index;

  if (isMobileDevice()) {
    const videoInput = document.createElement('input');
    videoInput.type = 'file';
    videoInput.accept = 'image/*';
    videoInput.capture = 'environment';
    videoInput.onchange = async (event) => {
      const file = (event.target as HTMLInputElement).files?.[0];
      if (file) {
        const imageFileName = `repair_foto-row${index}_${Date.now()}_${NOREC_DETAIL}.jpeg`;
        const renamedFile = new File([file], imageFileName, { type: "image/jpeg" });

        fileMitra.value[index] = renamedFile;
        console.log("📱 Mobile: Gambar berhasil diambil dari kamera:", renamedFile.name);
      }
    };
    videoInput.click();
  } else {
    modalFotoRepair.value = true;
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

const openCameraModalStatus = (index: number) => {
  currentRowIndexStatus.value = index;

  if (isMobileDevice()) {
    const videoInput = document.createElement('input');
    videoInput.type = 'file';
    videoInput.accept = 'image/*';
    videoInput.capture = 'environment';
    videoInput.onchange = async (event) => {
      const file = (event.target as HTMLInputElement).files?.[0];
      if (file) {
        const imageFileName = `status_repair_foto-row${index}_${Date.now()}_${NOREC_DETAIL}.jpeg`;
        const renamedFile = new File([file], imageFileName, { type: "image/jpeg" });

        fileMitraStatus.value[index] = renamedFile;
        console.log("📱 Mobile: Gambar berhasil diambil dari kamera:", renamedFile.name);
      }
    };
    videoInput.click();
  } else {
    modalFotoStatus.value = true;
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({
        video: { facingMode: { ideal: "environment" } }
      })
        .then((stream) => {
          if (video.value) {
            video.value.srcObject = stream;
            video.value.style.display = 'block';
            isCameraActiveStatus.value = true;
          }
        })
        .catch((err) => {
          console.error("Gagal mengakses kamera:", err);
        });
    }
  }
};

const isMobileDevice = () => {
  return /Mobi|Android/i.test(navigator.userAgent);
};

const takePhotoStatus = () => {
  if (!video.value || currentRowIndexStatus.value === null) return;

  const canvasEl = document.createElement('canvas');
  canvasEl.width = video.value.videoWidth;
  canvasEl.height = video.value.videoHeight;
  const context = canvasEl.getContext('2d');
  if (context) {
    context.drawImage(video.value, 0, 0, canvasEl.width, canvasEl.height);
    canvasEl.toBlob((blob) => {
      if (blob) {
        const imageFileName = `status_repair_foto-${currentRowIndexStatus.value}_${Date.now()}_${NOREC_DETAIL}.jpeg`;

        const imageFileStatus = new File([blob], imageFileName, { type: "image/jpeg" });
        fileMitraListStatus.value[currentRowIndexStatus.value!] = imageFileStatus;
        fileMitraStatus.value[currentRowIndexStatus.value!] = imageFileStatus;
        console.log("Foto Status disimpan di index", currentRowIndexStatus.value);
      }
      closeCameraModalStatus();
    }, 'image/jpeg');
  }
};

const takePhoto = () => {
  if (!video.value || currentRowIndex.value === null) return;

  const canvasEl = document.createElement('canvas');
  canvasEl.width = video.value.videoWidth;
  canvasEl.height = video.value.videoHeight;
  const context = canvasEl.getContext('2d');
  if (context) {
    context.drawImage(video.value, 0, 0, canvasEl.width, canvasEl.height);
    canvasEl.toBlob((blob) => {
      if (blob) {
        const imageFileName = `repair_foto-${currentRowIndex.value}_${Date.now()}_${NOREC_DETAIL}.jpeg`;

        const imageFile = new File([blob], imageFileName, { type: "image/jpeg" });
        fileMitraList.value[currentRowIndex.value!] = imageFile;
        fileMitra.value[currentRowIndex.value!] = imageFile;
        console.log("Foto disimpan di index", currentRowIndex.value);
      }
      closeCameraModal();
    }, 'image/jpeg');
  }
};

const previewFile = (index: number) => {
  const file = fileMitra.value[index];
  if (file) {
    const url = URL.createObjectURL(file);
    window.open(url, '_blank');
  } else {
    alert('Tidak ada file yang bisa dilihat.');
  }
};

const previewFileStatus = (index: number) => {
  const fileStatus = fileMitraStatus.value[index];
  if (fileStatus) {
    const urlStatus = URL.createObjectURL(fileStatus);
    window.open(urlStatus, '_blank');
  } else {
    alert('Tidak ada file yang bisa dilihat.');
  }
};

const closeCameraModal = () => {
  if (video.value && video.value.srcObject) {
    const stream = video.value.srcObject as MediaStream;
    stream.getTracks().forEach((track) => track.stop());
    video.value.srcObject = null;
    video.value.style.display = 'none';
  }

  isCameraActive.value = false;
  modalFotoRepair.value = false;
  currentRowIndex.value = null;
};

const closeCameraModalStatus = () => {
  if (video.value && video.value.srcObject) {
    const stream = video.value.srcObject as MediaStream;
    stream.getTracks().forEach((track) => track.stop());
    video.value.srcObject = null;
    video.value.style.display = 'none';
  }

  isCameraActiveStatus.value = false;
  modalFotoStatus.value = false;
  currentRowIndexStatus.value = null;
};

const onSelect = async (event: any, index: number) => {
  const file = event.files[0];

  if (file.size > 10000000) {
    alert('Maksimal file size adalah 10 MB');
    return;
  }

  if (!file.type.startsWith("image/")) {
    alert('File harus berupa gambar (JPEG)');
    return;
  }

  fileMitra.value[index] = file;
};

const onSelectStatus = async (event: any, index: number) => {
  const fileStatus = event.files[0];

  if (fileStatus.size > 10000000) {
    alert('Maksimal file size adalah 10 MB');
    return;
  }

  if (!fileStatus.type.startsWith("image/")) {
    alert('File harus berupa gambar (JPEG)');
    return;
  }

  fileMitraStatus.value[index] = fileStatus;
};

const addNewLaporanStatus = () => {
  item.value.detailLaporanStatus.push({
    no: item.value.detailLaporanStatus[item.value.detailLaporanStatus.length - 1].no + 1
  });
}

const removeLaporanStatus = (index: any) => {
  item.value.detailLaporanStatus.splice(index, 1)
  if (item.value.detailLaporanStatus.length == 0) {
    item.value.detailLaporanStatus.push({
      no: 1
    });
  }
}

const addNewLaporanRepair = () => {
  item.value.detailLaporanRepair.push({
    no: item.value.detailLaporanRepair[item.value.detailLaporanRepair.length - 1].no + 1
  });
}

const removeLaporanRepair = (index: any) => {
  item.value.detailLaporanRepair.splice(index, 1)
  if (item.value.detailLaporanRepair.length == 0) {
    item.value.detailLaporanRepair.push({
      no: 1
    });
  }
}

const hapusStatus = async (e: any) => {
  let norec = e.norec;
  isLoadingSave.value = true;
  try {
    await useApi().post(`penyelia/hapus-status-repair`, { norec: norec });
    clear()
    fetchData()
    isLoadingSave.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    isLoadingSave.value = false;
  }
}

const hapusLaporan = async (e: any) => {
  let norec = e.norec;
  isLoadingSave.value = true;
  try {
    await useApi().post(`penyelia/hapus-laporan-repair`, { norec: norec });
    clear()
    fetchData()
    isLoadingSave.value = false;
  } catch (error) {
    console.error('Error fetching data:', error);
  } finally {
    isLoadingSave.value = false;
  }
}

const setujuiLaporan = async () => {
  let json = {
    'verif': {
      'norec': NOREC_DETAIL,
    }
  }
  isLoadingSave.value = true
  await useApi().post('/penyelia/save-setujui-laporan-repair', json).then((r) => {
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

const toDashboard = () => {
  router.push({
    name: 'module-dashboard-penyelia',
  })
}

const clear = () => {
  fileMitraStatus.value = []
  fileMitraListStatus.value = []
  fileMitra.value = []
  fileMitraList.value = []
  item.value.kesimpulan = ''
  item.value.detailLaporanRepair = [
    {
      bagianalatlaporan: '',
      penanganan: '',
      status: '',
      sparepart: ''
    }
  ],
    item.value.detailLaporanStatus = [
      {
        bagianalatstatus: '',
        kondisistatus: ''
      }
    ]
}

const fetchData = async () => {
  loadSearch.value = true
  await useApi().get(`penyelia/get-laporan-repair?norecdetail=${NOREC_DETAIL}`).then((response: any) => {
    response.data.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    response.datastatus.forEach((element: any, i: any) => {
      element.no = i + 1
    });
    dataSourceHasilLaporanRepair.value = response.data
    dataSourceHasilLaporanRepairStatus.value = response.datastatus
  }).catch((err) => {
    dataSourceHasilLaporanRepair.value = []
    dataSourceHasilLaporanRepairStatus.value = []
  })
  loadSearch.value = false
}

const detailOrder = async () => {
  const response = await useApi().get(`/penyelia/detail-alat-repair?norec_pd=${NOREC_DETAIL}`)
  const data = response.data

  item.value.namaproduk = data.namaproduk
  item.value.namamerk = data.namamerk
  item.value.namatipe = data.namatipe
  item.value.namaserialnumber = data.namaserialnumber
  item.value.noorderalat = data.noorderalat
  item.value.keterangan = data.keterangan
  item.value.namafile = data.namafile
  item.value.kesimpulan = data.kesimpulanrepair
  // const backendUrl = import.meta.env.VITE_API_URL 
  // MARKINGSITE.value = `${backendUrl}/berkas-mitra/${data.namafile}`
  MARKINGSITE.value = 'https://ulabumro.id:8000/berkas-mitra/' + data.namafile
}

const Save = async () => {
  if (!item.value.detailLaporanStatus || item.value.detailLaporanStatus.length === 0) { H.alert('warning', 'Status Alat harus diisi'); return; }
  if (!item.value.detailLaporanRepair || item.value.detailLaporanRepair.length === 0) { H.alert('warning', 'Laporan harus diisi'); return; }

  const formData = new FormData();
  formData.append('norec_detail', NOREC_DETAIL);
  formData.append('kesimpulan', item.value.kesimpulan || '');
  item.value.detailLaporanRepair.forEach((items: any, index: number) => {
    formData.append(`daftarlaporanrepair[${index}][bagianalatlaporan]`, items.bagianalatlaporan || '');
    formData.append(`daftarlaporanrepair[${index}][penanganan]`, items.penanganan || '');
    formData.append(`daftarlaporanrepair[${index}][status]`, items.status || '');
    formData.append(`daftarlaporanrepair[${index}][sparepart]`, items.sparepart || '');

    if (fileMitra.value[index]) {
      formData.append(`daftarlaporanrepair[${index}][fileMitra]`, fileMitra.value[index]);
    }
  });

  item.value.detailLaporanStatus.forEach((itemsStatus: any, index: number) => {
    formData.append(`daftarstatusrepair[${index}][bagianalatstatus]`, itemsStatus.bagianalatstatus || '');
    formData.append(`daftarstatusrepair[${index}][kondisistatus]`, itemsStatus.kondisistatus || '');

    if (fileMitraStatus.value[index]) {
      formData.append(`daftarstatusrepair[${index}][fileMitraStatus]`, fileMitraStatus.value[index]);
    }
  });

  isLoadingSave.value = true;
  try {
    await useApi().post('/penyelia/save-data-laporan-repair', formData);
    clear()
    isLoadingSave.value = false;
    fetchData();
  } catch (e: any) {
    isLoadingSave.value = false;
    console.error("Gagal menyimpan:", e);
  }
};


const kembali = () => {
  window.history.back()
}

const cetakLaporanRepair = () => {
  H.printBlade(`pelaksana/cetak-laporan-repair?pdf=true&norec=${dataSourceHasilLaporanRepair.value[0].norecregis}&norec_detail=${NOREC_DETAIL}`);
}

detailOrder()
fetchData()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/bedah.scss';

tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
  position: relative;
  background: var(--fade-grey-light-2);
  border: 1px solid var(--fade-grey);
  max-width: 400px;
  height: 35px;
  border-bottom: none;

}

.tb-order .text-value {
  font-family: var(--font-alt);
  color: var(--dark-text);
  font-weight: 400;
  font-size: 12px;
}

.user-grid-v2 {
  .columns {
    margin-left: -0.5rem !important;
    margin-right: -0.5rem !important;
    margin-top: -0.5rem !important;
  }

  .column {
    padding: 0.5rem !important;
  }

  .grid-item {
    @include vuero-s-card;

    text-align: center;

    >.v-avatar {
      display: block;
      margin: 0 auto 4px;
    }

    h3 {
      font-family: var(--font-alt);
      font-size: 0.95rem;
      font-weight: 600;
      color: var(--dark-text);
    }

    p {
      font-size: 0.85rem;
    }

    .people {
      display: flex;
      justify-content: center;
      padding: 8px 0 30px;

      .v-avatar {
        margin: 0 4px;
      }
    }

    .buttons {
      display: flex;
      justify-content: space-between;

      .button {
        width: calc(50% - 4px);
        color: var(--light-text);

        &:hover,
        &:focus {
          border-color: var(--fade-grey-dark-4);
          color: var(--primary);
          box-shadow: var(--light-box-shadow);
        }
      }
    }
  }

  .grid-item-wrap {
    border: 1px solid var(--fade-grey-dark-3);
    border-radius: var(--radius-large);
    transition: all 0.3s; // transition-all test

    .grid-item-head {
      background: #fafafa;
      border-radius: var(--radius-large) 6px 0 0;
      padding: 20px;

      .flex-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;

        .meta {
          span {
            display: flex;

            &:first-child {
              font-family: var(--font-alt);
              font-weight: 600;
              font-size: 0.85rem;
              color: white;
            }

            &:nth-child(2) {
              font-size: 0.8rem;
              color: white;
            }
          }
        }

        .status-icon {
          height: 28px;
          width: 28px;
          min-width: 28px;
          border-radius: var(--radius-rounded);
          border: 1px solid var(--fade-grey-dark-3);
          display: flex;
          align-items: center;
          justify-content: center;

          &.is-success {
            background: var(--success);
            border-color: var(--success);
            color: var(--white);
          }

          &.is-warning {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--white);
          }

          &.is-danger {
            background: var(--danger);
            border-color: var(--danger);
            color: var(--white);
          }

          i {
            font-size: 8px;
          }
        }
      }

      .buttons {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0;

        .button,
        .v-button {
          width: calc(50% - 4px);
          color: var(--light-text);
          margin-bottom: 0;

          &:hover,
          &:focus {
            border-color: var(--fade-grey-dark-4);
            color: var(--primary);
            box-shadow: var(--light-box-shadow);
          }
        }
      }
    }

    .grid-item {
      border-top-left-radius: 0;
      border-top-right-radius: 0;
      border: none;
    }
  }
}

.is-dark {
  .user-grid {
    .grid-item {
      @include vuero-card--dark;
    }
  }

  .user-grid-v2 {
    .grid-item-wrap {
      border-color: var(--dark-sidebar-light-12);

      .grid-item-head {
        background: var(--dark-sidebar-light-4);
      }
    }
  }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
  background: var(--success) !important
}

.user-grid-v2 .grid-item-wrap .grid-item-head {
  padding: 10px;
}

.search-menu-rad {
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

  .search-location-rad,
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

  .search-button-rad {
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

.search-widget {
  flex: 1;
  display: inline-block;
  width: 100%;
  padding: 10px;
  background-color: var(--white);
  border-radius: 16px;
  border: 1px solid var(--fade-grey-dark-3);
  transition: all 0.3s;
}
</style>
