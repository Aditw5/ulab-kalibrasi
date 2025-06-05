<template>
    <section>
        <div class="columns is-multiline">
            <div class="column is-12">
                <div class="search-widget">
                    <div class="field">
                        <div class="columns is-multiline">
                            <div class="column is-2">
                                <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                                    :loading="isLoading" class="mr-2">Tambah </VButton>
                            </div>
                            <div class="column is-10">
                                <div class="control">
                                    <input type="text" v-model="filter" class="input" placeholder="Cari..." />
                                    <button class="searcv-button" type="button" :loading="isLoading" @click="loadRiwayat">
                                        <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="column is-12">


                <div class="columns is-multiline mt-5" v-if="isLoading">
                    <VPlaceloadText :lines="1" class="p-2" />
                    <div class="column is-12" v-for="key in 2" :key="key">
                        <VPlaceloadWrap>
                            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                            <VPlaceload height="50px" width="25%" class="mx-2" rounded="sm" />
                        </VPlaceloadWrap>
                    </div>
                </div>
                <div class="columns is-multiline" v-else>
                    <div class="column is-12" v-if="dataSource.length">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                               <BerkasPasienView :data="dataSource" @edit="edit" @hapus="hapus" @lihat="lihat" :hide="false"></BerkasPasienView>
                            </div>
                        </div>
                    </div>
                    <div class="column is-12" v-else>
                        <div class="page-placeholder">
                            <div class="placeholder-content">
                                <img class="light-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                    alt="" />
                                <img class="dark-image" style=" max-width: 340px;" :src="H.assets().iconNotFound_rev"
                                    alt="" />
                                <h3>{{ H.assets().notFound }}</h3>
                                <p class="is-larger">
                                    {{ H.assets().notFoundSubtitle }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <Dialog v-model:visible="modalInput" modal header="Upload" :style="{ width: '30vw' }">
            <div class="columns is-multiline">

                <div class="column is-12">
                    <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                        <VLabel class="required-field">File</VLabel>
                        <VControl icon="fas fa-sticky-note" fullwidth class="prime-auto-select">
                            <Dropdown v-model="input.namafile" :options="d_Berkas" :optionLabel="'nama'" class="is-rounded"
                                placeholder="File" style="width: 100%;" :filter="true" showClear />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <VField>
                        <VLabel class="required-field">Nama</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="input.nama" type="text" class="input is-rounded" placeholder="Nama " />
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
                    <FileUpload v-model="filePasien" mode="basic" name="demo" accept="application/pdf" :maxFileSize="100000000"
                        @upload="onUpload" outlined :invalidFileTypeMessage="'{0}: File yang diupload harus pdf.'" :invalidFileSizeMessage="'Ukuran maksimal berkas adalah {1}'"
                        style=" background-color: transparent; color: var(--danger); border: 1px solid;"
                        :chooseLabel="filePasien ? filePasien.name : 'Unggah'" @select="onSelect($event)"
                        class="is-rounded w-100" />
                        <button @click="openCamera" class="button is-primary mt-2">Buka Kamera</button>
                        <button v-if="filePasien" @click="previewFile" class="button is-link mt-2">Lihat File</button>
    
                        <video ref="video" width="500" height="300" autoplay style="display: none;"></video>
    
                        <canvas ref="canvas" style="display: none;"></canvas>
                        <button v-if="isCameraActive" @click="takePhoto" class="button is-success mt-2">Ambil Foto</button>
                    <!-- <VTag rounded :label="filePasien ? 'Selected File: ' + filePasien.name : 'Selected File: None'"
                        outlined color="info" class="mt-2" /> -->
                </div>
            </div>
            <template #footer>
                <VButton icon="feather:refresh-cw rem-100" light dark-outlined @click="kembaliKeun()">
                    Batal
                </VButton>
                <VButton type="button" rounded outlined color="primary" raised icon="feather:save" :loading="isLoading"
                    @click="simpan()"> Simpan
                </VButton>
            </template>
        </Dialog>
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import jsPDF from 'jspdf';
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import BerkasPasienView from './berkas-pasien-preview.vue'
useHead({
    title: 'Berkas Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string

const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
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
const filePasien: any = ref()
const video: any = ref(null);
const canvas: any = ref(null);
const isCameraActive = ref(false);
const takePhotoButton: any = ref(null);
const modalInput: any = ref(false)
const isLoading: any = ref(false)

const input: any = ref({})
const d_Berkas: any = ref([])
const filter: any = ref('')
const dataSource: any = ref([])
const filteredList = computed(() => {
    if (!filter.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.nama.match(new RegExp(filter.value, 'i')) ||
            items.deskripsi?.match(new RegExp(filter.value, 'i'))
        )
    })
})
const norec = input.value.norec ? input.value.norec : "noregistrasi";
const noregistrasi = props.registrasi.noregistrasi ? props.registrasi.noregistrasi : "noregistrasi";

const getCurrentDateTime = () => {
    const now = new Date();
    const day = String(now.getDate()).padStart(2, '0');
    const month = String(now.getMonth() + 1).padStart(2, '0'); 
    const year = now.getFullYear();
    const hours = String(now.getHours()).padStart(2, '0');
    const minutes = String(now.getMinutes()).padStart(2, '0');
    return `${day}-${month}-${year}_${hours}.${minutes}`;
}

const currentDateTime = getCurrentDateTime();


// const openCamera = () => {
//       if (isMobileDevice()) {
//         // Open the device's camera app
//         const videoInput = document.createElement('input');
//         videoInput.type = 'file';
//         videoInput.accept = 'image/*';
//         videoInput.capture = 'environment'; // Use the rear camera
//         videoInput.onchange = (event) => {
//           const file = (event.target as HTMLInputElement).files?.[0];
//           if (file) {
//             const pdfFileName = `${norec}_${noregistrasi}_${currentDateTime}.pdf`;
//             const pdfFile = new File([file], pdfFileName, { type: "application/pdf" });
//             filePasien.value = pdfFile; 
//             console.log("Foto tersimpan sebagai PDF di filePasien:", filePasien.value);
//           }
//         };
//         videoInput.click(); // Open the camera app
//       } else {
//         // Use getUserMedia for desktop
//         if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
//           navigator.mediaDevices.getUserMedia({ video: true })
//             .then((stream) => {
//               if (video.value) {
//                 video.value.srcObject = stream;
//                 video.value.style.display = 'block'; 
//                 isCameraActive.value = true; 
//               }
//             })
//             .catch((err) => {
//               console.error("Error accessing the camera: ", err);
//             });
//         }
//       }
//     };


const openCamera = () => {
  if (isMobileDevice()) {
    const videoInput = document.createElement('input');
    videoInput.type = 'file';
    videoInput.accept = 'image/*';
    videoInput.capture = 'environment';

    videoInput.onchange = async (event) => {
      const file = (event.target as HTMLInputElement).files?.[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = () => {
          const img = new Image();
          img.onload = () => {
            const canvas = document.createElement('canvas');
            canvas.width = img.width;
            canvas.height = img.height;
            const ctx = canvas.getContext('2d');
            ctx?.drawImage(img, 0, 0);

            const pdf = new jsPDF({
              orientation: 'portrait',
              unit: 'px',
              format: [canvas.width, canvas.height]
            });
            pdf.addImage(canvas.toDataURL('image/jpeg'), 'JPEG', 0, 0, canvas.width, canvas.height);

            const pdfBlob = pdf.output('blob');
            const pdfFileName = `${norec}_${noregistrasi}_${currentDateTime}.pdf`;
            const pdfFile = new File([pdfBlob], pdfFileName, { type: "application/pdf" });

            filePasien.value = pdfFile;
            console.log("PDF berhasil dibuat dari foto:", filePasien.value);
          };
          img.src = reader.result as string;
        };
        reader.readAsDataURL(file);
      }
    };

    videoInput.click();
  } else {
    if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
      navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: { ideal: "environment" }
        }
      })
        .then((stream) => {
          if (video.value) {
            video.value.srcObject = stream;
            video.value.style.display = 'block';
            isCameraActive.value = true;
          }
        })
        .catch((err) => {
          console.error("Error accessing the camera: ", err);
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

        const imgData = canvas.toDataURL("image/jpeg", 1.0);
        const pdf = new jsPDF({
        orientation: 'portrait',
        unit: 'px',
        format: [canvas.width, canvas.height]
        });
        pdf.addImage(imgData, 'JPEG', 0, 0, canvas.width, canvas.height);

        const pdfBlob = pdf.output('blob'); 
        const pdfFileName = `${norec}_${noregistrasi}_${currentDateTime}.pdf`;
        const pdfFile = new File([pdfBlob], pdfFileName, { type: "application/pdf" });
        filePasien.value = pdfFile; 
        console.log("Foto tersimpan sebagai PDF di filePasien:", filePasien.value);

        stopCamera();
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
      if (filePasien.value) {
        const url = URL.createObjectURL(filePasien.value);
        window.open(url, "_blank");
      } else {
        alert("Tidak ada file yang bisa dibuka.");
      }
    };

const isMobileDevice = () => {
      return /Mobi|Android/i.test(navigator.userAgent);
    };

const loadDrop = () => {
    useApi().get(
        `/emr/combo-jenis-berkas`).then((response: any) => {
            d_Berkas.value = response
        })
}

const loadRiwayat = () => {
    isLoading.value = true
    useApi().get(
        `/emr/berkas-pasien?nocm=${props.pasien.nocm}&noregistrasi=${props.registrasi.noregistrasi}`).then((response: any) => {
            isLoading.value = false
            dataSource.value = response.data
        })
}

const add = () => {
    filePasien.value = null
    input.value = {}
    modalInput.value = true
}
const onSelect = async (filez: any) => {

    const file = filez
        .files[0];

    console.log(file.size)
    if (file.size > 10000000) {
        H.alert('error', 'Maksimal file size adalah 10 MB')
        return
    }

    if (file.type != "application/pdf") {
        H.alert('error', 'File yang diizinkan dalam bentuk format PDF')
        return;
    }
    filePasien.value = file


}
const onUpload = () => {

}
const kembaliKeun = () => {
    modalInput.value = false
    input.value = {
        tanggal: new Date()
    }
}
const simpan = async () => {
    if (!input.value.namafile) {
        H.alert('error', 'Jenis File harus di isi')
        return
    }
    if (!input.value.nama) {
        H.alert('error', 'Nama harus di isi')
        return
    }
    if (!filePasien.value) {
        H.alert('error', 'File harus di unggah')
        return
    }
    const formData = new FormData()
    formData.append('filePasien', filePasien.value)
    formData.append('norec', input.value.norec ? input.value.norec : '')
    formData.append('noregistrasi', props.registrasi.noregistrasi)
    formData.append('nocm', props.pasien.nocm)
    formData.append('norec_apd', props.registrasi.norec_apd)
    formData.append('namafile', input.value.namafile.nama)
    formData.append('keterangan', input.value.keterangan ? input.value.keterangan : null)
    formData.append('objectberkaspasien', input.value.namafile.id)
    formData.append('nama', input.value.nama)
    isLoading.value = true
    await useApi().post('/emr/simpan-berkas-pasien', formData).then((r) => {
        isLoading.value = false
        loadRiwayat()
        modalInput.value = false
    }).catch((error: any) => {
        isLoading.value = false
        console.error('Error saat menyimpan berkas pasien:', error);

        if (error.response) {

            H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas pasien'}`);
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

    filePasien.value = file
    filePasien.value.name = e.namafile
    modalInput.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true

    await useApi().post(
        `/emr/hapus-berkas-pasien`, {
        'norec': e.norec,
    }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayat()
    })
}

const lihat = async (e: any) => {
    H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}
onMounted(() => {

});

loadDrop()
loadRiwayat()
</script>
