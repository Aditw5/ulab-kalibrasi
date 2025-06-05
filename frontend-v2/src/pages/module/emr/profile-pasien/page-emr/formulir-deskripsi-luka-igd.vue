<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
      <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>
      <div v-if="!isStuck" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3> {{ props.FORM_NAME }}</h3>
          </div>
          <div class="right">
            <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" 
:isDisableSimpanButton="props.pasien.isDisableSimpanButton"
:isLoading="isLoading" @simpan="simpan"
              @kembaliKeun="kembaliKeun"></ButtonEmr>
          </div>
        </div>
      </div>

    </div>
  </div>
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="BADAN TAMPAK DEPAN BELAKANG" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <table width="100%">
                    <tbody>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;">
                          <div>
                            <VIconButton type="button" circle outlined color="danger" raised icon="feather:trash"
                              @click="clearCanvas('markingsite_2')"> Clear
                            </VIconButton>
                            <!-- <VIconButton type="button" circle outlined color="info" raised icon="feather:zoom-in"
                            id="zoom_in_2"> Zoom In
                            </VIconButton>
                            <VIconButton type="button" circle outlined color="info" raised icon="feather:zoom-out"
                            id="zoom_out_2"> Zoom Out
                            </VIconButton> -->
                            <VIconButton type="button" circle outlined color="info" raised icon="fas fa-undo"
                            id="undo_btn_2"> Undo
                            </VIconButton>
                            <VIconButton type="button" circle outlined color="info" raised icon="fas fa-redo"
                            id="redo_btn_2"> Redo
                            </VIconButton>
                          </div>
                          <canvas id="markingsite_2" height="1000" width="800" :style="'background-image:url(' + MARKINGSITE_2 + ')'"
                          style="text-align: center; z-index: 0;background-repeat: no-repeat;background-position: center; background-size: cover;"></canvas>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

    </VCard>
  </div>
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="BADAN TAMPAK SAMPING" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <table width="100%">
                    <tbody>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;">
                          <div>
                            <VIconButton type="button" circle outlined color="danger" raised icon="feather:trash"
                              @click="clearCanvas('markingsite_3')"> Clear
                            </VIconButton>
                            <!-- <VIconButton type="button" circle outlined color="info" raised icon="feather:zoom-in"
                            id="zoom_in_3"> Zoom In
                            </VIconButton>
                            <VIconButton type="button" circle outlined color="info" raised icon="feather:zoom-out"
                            id="zoom_out_3"> Zoom Out
                            </VIconButton> -->
                            <VIconButton type="button" circle outlined color="info" raised icon="fas fa-undo"
                            id="undo_btn_3"> Undo
                            </VIconButton>
                            <VIconButton type="button" circle outlined color="info" raised icon="fas fa-redo"
                            id="redo_btn_3"> Redo
                            </VIconButton>
                          </div>
                          <canvas id="markingsite_3" height="1000" width="800" :style="'background-image:url(' + MARKINGSITE_3 + ')'"
                          style="text-align: center; z-index: 0;background-repeat: no-repeat;background-position: center; background-size: cover;"></canvas>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

    </VCard>
  </div>
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="KEPALA" :toggleable="true">
          <div class="columns is-multiline p-3">
            <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-12">
                  <table width="100%">
                    <tbody>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;">
                          <div>
                            <VIconButton type="button" circle outlined color="danger" raised icon="feather:trash"
                              @click="clearCanvas('markingsite_4')"> Clear
                            </VIconButton>
                            <!-- <VIconButton type="button" circle outlined color="info" raised icon="feather:zoom-in"
                            id="zoom_in_4"> Zoom In
                            </VIconButton>
                            <VIconButton type="button" circle outlined color="info" raised icon="feather:zoom-out"
                            id="zoom_out_4"> Zoom Out
                            </VIconButton> -->
                            <VIconButton type="button" circle outlined color="info" raised icon="fas fa-undo"
                            id="undo_btn_4"> Undo
                            </VIconButton>
                            <VIconButton type="button" circle outlined color="info" raised icon="fas fa-redo"
                            id="redo_btn_4"> Redo
                            </VIconButton>
                          </div>
                          <canvas id="markingsite_4" height="1000" width="800" :style="'background-image:url(' + MARKINGSITE_4 + ')'"
                          style="text-align: center; z-index: 0;background-repeat: no-repeat;background-position: center; background-size: cover;"></canvas>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VField>
                            <VControl>
                                <VTextarea v-model="input.contohDeskripsiLuka" rows="50" placeholder=".....">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </VCard>
        </div>
    </div>

      <!-- <ContohDeskripsi/> -->

    </VCard>
  </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { reactive, ref, computed, watch } from 'vue'
import { useRoute } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/pengkajian-awal-pasien-gawat-darurat'
import $ from "jquery";
import RadioButton from 'primevue/radiobutton';
import ContohDeskripsi from './formulir-contoh-deskripsi-luka.vue'
import sleep from '/@src/utils/sleep'




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let detailKeperawatan = ref(EMR.detailKeperawatan())
let listImageNyeri: any = ref(EMR.imgNyeri())
let listSkoringNyeri: any = ref(EMR.skoringNyeri())
let skoringJatuh: any = ref(EMR.skoringJatuh())
let resikoNutrisional: any = ref(EMR.listNutrisional())
let statusPasien: any = ref(EMR.statusPasien())
let pemeriksaanFisik: any = ref(EMR.pemeriksaanFisik())
let keperawatan: any = ref(EMR.keperawatan())

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
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Obat: any = ref([])
const d_Pegawai: any = ref([])
const d_Perawat: any = ref([])
const d_Dokter: any = ref([])
const d_DiagnosaKeperawatan: any = ref([])
const isAktive = ref()
const jenisKelamin: any = ref('')
const MARKINGSITE_2: any = ref('')
const MARKINGSITE_3: any = ref('')
const MARKINGSITE_4: any = ref('')
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  jenisKelamin.value = props.pasien.jeniskelamin.toUpperCase()
  MARKINGSITE_2.value = '/images/simrs/badan.png'
  MARKINGSITE_3.value = '/images/simrs/badan-samping.png'
  MARKINGSITE_4.value = '/images/simrs/kepala.png'
}
const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
        if (response[0].tandaBadanDepanBelakang) {
          H.tandaTangan().set("markingsite_2", response[0].tandaBadanDepanBelakang, 470, 600)
        }
        if (response[0].tandaBadanSamping) {
          H.tandaTangan().set("markingsite_3", response[0].tandaBadanSamping, 800, 900)
        }
        if (response[0].tandaKepala) {
          H.tandaTangan().set("markingsite_4", response[0].tandaKepala, 500, 600)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaBadanDepanBelakang = H.tandaTangan().get("markingsite_2")
  object.tandaBadanSamping = H.tandaTangan().get("markingsite_3")
  object.tandaKepala = H.tandaTangan().get("markingsite_4")
  object.pasien = H.setObjectPasien(props.pasien)
  object.registrasi = H.setObjectRegistrasi(props.registrasi)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = true
  useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

// const fetchPegawai = async (filter: any) => {

//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
//   ).then((response) => {
//     d_Pegawai.value = response
//   })
// }


// const fetchDokter = async (filter: any) => {
//   await useApi().get(
//     `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//   ).then((response) => {
//     d_Dokter.value = response
//   })
// }
const clearCanvas = (canvas: any) => {

  var sigCanvas: any = document.getElementById(canvas);
  var context = sigCanvas.getContext("2d");
  context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

}
const skor = (e: any, i: any) => {

  let listSkor = listSkoringNyeri.value.detail

  listSkor.forEach((element: any) => {
    if (element.descNilai == e.descNilai) {
      input.value.skoringNyeri = e.descNilai
    }
  });
  isAktive.value = i

}
const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.namapasien = props.pasien.namapasien
  input.value.nocm = props.pasien.nocm
  input.value.noregistrasi = props.registrasi.noregistrasi
  input.value.dokter = props.registrasi.dokter

  input.value.contohDeskripsiLuka = `
                                                                                                                                                 CONTOH DESKRPSI LUKA
1. KEPALA DAN LEHER :
a. Pada kepala bagian kanan/kiri/depan/belakang, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak kepala, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b. Pada dahi kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari alis, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
c. Pada hidung, ... centimeter dari pangkal hidung, ... centimeter dari garis tengah ke arah kanan/kiri, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
d. Pada pipi kanan/kiri, ... centimeter dari garis tengah, ... centimeter dari mata, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
e. Pada bibir atas/bawah, ... centimeter dari garis tengah ke arah kanan/kiri, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
f. Pada dagu, ... centimeter dari garis tengah ke arah kanan/kiri atau pada garis tengah, ... centimeter dari bibir, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
g. Pada leher, ... centimeter dari garis tengah depan/belakang ke arah kanan/kiri atau pada garis tengah depan/belakang, ... centimeter dari pangkal leher, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...


2. DADA, PUNGGUNG, DAN PERUT:
a.  Pada dada kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak bahu/pangkal leher, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b.  Pada perut kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak bahu/di atas pusar/di bawah pusar, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
c.  Pada punggung kanan/kiri, ... centimeter dari garis tengah atau pada garis tengah, ... centimeter dari puncak bahu/pangkal leher, terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...

3. LENGAN DAN TANGAN :
a.  Pada lengan atas kanan/kiri, ... centimeter dari puncak bahu/lipat siku/siku, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b.  Pada lengan bawah kanan/kiri, ... centimeter dari puncak bahu/lipat siku/siku, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
c.  Pada punggung/telapak tangan kanan/kiri, ... centimeter dari garis tengah ke arah luar/dalam atau pada garis tengah,  ... centimeter dari pergelangan tangan/ujung jari ..., terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...


4. PAHA, TUNGKAI BAWAH, DAN KAKI :
a.  Pada paha kanan/kiri, ... centimeter dari lipat paha/lutut/lipat lutut, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ... (untuk luka terbuka, ditambahkan deskripsi kedalaman ... centimeter, dasar ..., tepi rata/tidak rata, sudut tumpul/tajam, terdapat/tidak terdapat jembatan jaringan).
b.  Pada tungkai bawah kanan/kiri, ... centimeter dari lutut/lipat lutut/pergelangan kaki, ... centimeter dari garis tengah depan/belakang ke arah luar/dalam atau pada garis tengah depan/belakang,  terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...
c.  Pada punggung/telapak kaki kanan/kiri, ... centimeter dari garis tengah ke arah luar/dalam atau pada garis tengah,  ... centimeter dari pergelangan kaki/ujung jari ..., terdapat luka memar/lecet/terbuka, ukuran ... kali ... centimeter, warna ...


5. KETERANGAN TAMBAHAN:
a. Jika tampak bengkak, ditambahkan deskripsi: tampak bengkak, menimbul ... centimeter dari permukaan kulit.
b. Jika terdapat patah tulang tertutup, ditambahkan deskripsi: teraba derik tulang pada tulang...
c. Jika terdapat patah tulang terbuka, ditambahkan deskripsi: tampak tulang ... patah.

`;

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=tinggiBadan,IMT,lingkarPerut,tekananDarah,pernapasan,suhu,nadi,beratBadan,SPO2"
  ).then((response) => {
    if (response != null) {
      console.log(response)
      input.value.beratBadan = response.beratBadan
      input.value.tinggiBadan = response.tinggiBadan
    }
  })
  input.value.tglPembuatan = new Date()
}

const autoFillkeluhanUtama = () => {
  useApi().get("emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=pengkajianDokterIGD" + "&field=keluhanUtama"
  ).then((response) => {
    if (response != null) {
      input.value.keluhanUtama = response.keluhanUtama ? response.keluhanUtama : ''
    }
  })
}

const getPosition = (mouseEvent: any, sigCanvas: any) => {
  let rect = sigCanvas.getBoundingClientRect();
  return {
    X: mouseEvent.clientX - rect.left,
    Y: mouseEvent.clientY - rect.top
  };
}

const initializeMarkingSite = (canvasId: string, undoId: string, redoId: string, bgImage: string) => {
// const initializeMarkingSite = (canvasId: string, zoomInId: string, zoomOutId: string, undoId: string, redoId: string, bgImage: string) => {
    let sigCanvas: any = document.getElementById(canvasId);
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;

  let undoStack: string[] = [];
  let redoStack: string[] = [];
  let scale = 1;
  const ZOOM_FACTOR = 1.1;

  const saveState = () => {
    undoStack.push(sigCanvas.toDataURL());
    redoStack = [];
  };

  const undo = () => {
    if (undoStack.length > 0) {
      redoStack.push(sigCanvas.toDataURL());
      const prevState = undoStack.pop();
      loadState(prevState);
    }
  };

  const redo = () => {
    if (redoStack.length > 0) {
      undoStack.push(sigCanvas.toDataURL());
      const nextState = redoStack.pop();
      loadState(nextState);
    }
  };

  // let backgroundImage = new Image();
  // backgroundImage.src = bgImage; // Ganti dengan path gambar latar belakang

// const applyZoom = (direction: 'in' | 'out') => {
//     // Hitung skala baru
//     scale = direction === 'in' ? (scale / ZOOM_FACTOR) : (scale * ZOOM_FACTOR);

//     // // Reset canvas
//     context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);

//     // // Gambar ulang latar belakang dengan skala baru
//     context.drawImage(backgroundImage, 0, 0, sigCanvas.width / scale, sigCanvas.height / scale);

//     // Gambar ulang gambar yang ada berdasarkan skala baru
//     redraw();
// };

// const redraw = () => {
//     // Mengambil data URL dari canvas
//     let img = new Image();
//     img.src = sigCanvas.toDataURL();
// };

// Pastikan untuk menggambar gambar latar belakang saat memuat canvas awal
// backgroundImage.onload = () => {
//     context.drawImage(backgroundImage, 0, 0, sigCanvas.width, sigCanvas.height); // Gambar latar belakang
// };

// Gambar ulang pada saat state diubah
  const loadState = (dataUrl: string) => {
    let img = new Image();
    img.src = dataUrl;
    img.onload = () => {
      context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
      context.drawImage(img, 0, 0);
    };
  };




  // const loadState = (dataUrl: string) => {
  //   let img = new Image();
  //   img.src = dataUrl;
  //   img.onload = () => {
  //     context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
  //     context.drawImage(img, 0, 0);
  //   };
  // };

  // const applyZoom = (direction: 'in' | 'out') => {
  //   scale = direction === 'in' ? scale * ZOOM_FACTOR : scale / ZOOM_FACTOR;
  //   context.setTransform(scale, 0, 0, scale, 0, 0);
  //   redraw();
  // };

  // const redraw = () => {
  //   let img = new Image();
  //   img.src = sigCanvas.toDataURL();
  //   img.onload = () => {
  //     context.clearRect(0, 0, sigCanvas.width, sigCanvas.height);
  //     context.drawImage(img, 0, 0, sigCanvas.width, sigCanvas.height);
  //   };
  // };

  // Event listeners for zoom buttons
  // document.getElementById(zoomInId).addEventListener('click', () => applyZoom('in'));
  // document.getElementById(zoomOutId).addEventListener('click', () => applyZoom('out'));

  // Event listeners for undo/redo
  document.getElementById(undoId).addEventListener('click', undo);
  document.getElementById(redoId).addEventListener('click', redo);

  // Drawing functionality (can be reused for both mouse and touch events)
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {
    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
        saveState();
      },
      touchmove: function (coors: any) {
        if (this.isDrawing) {
          context.lineTo(coors.x, coors.y);
          context.stroke();
        }
      },
      touchend: function (coors: any) {
        if (this.isDrawing) {
          this.touchmove(coors);
          this.isDrawing = false;
        }
      }
    };

    const draw = (event: any) => {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;
      if (obj.offsetParent) {
        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        } while ((obj = obj.offsetParent) != null);
      }

      drawer[event.type](coors);
    };

    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);

    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();
    }, false);
  } else {
    // Mouse drawing functionality
    $(sigCanvas).mousedown(function (mouseEvent: any) {
      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      saveState();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });
  }

  function getPosition(mouseEvent: any, canvas: any) {
    let rect = canvas.getBoundingClientRect();
    return {
      X: mouseEvent.clientX - rect.left,
      Y: mouseEvent.clientY - rect.top
    };
  }

  function drawLine(event: any, canvas: any, ctx: any) {
    let position = getPosition(event, canvas);
    ctx.lineTo(position.X, position.Y);
    ctx.stroke();
  }

  function finishDrawing(event: any, canvas: any, ctx: any) {
    $(canvas).off('mousemove mouseup mouseout');
  }
};

// Initialize for all 4 marking sites
// initializeMarkingSite('markingsite_1', 'zoom_in_1', 'zoom_out_1', 'undo_btn_1', 'redo_btn_1');
// initializeMarkingSite('markingsite_2', 'zoom_in_2', 'zoom_out_2', 'undo_btn_2', 'redo_btn_2');
// initializeMarkingSite('markingsite_3', 'zoom_in_3', 'zoom_out_3', 'undo_btn_3', 'redo_btn_3');
// initializeMarkingSite('markingsite_4', 'zoom_in_4', 'zoom_out_4', 'undo_btn_4', 'redo_btn_4');


const drawLine = (mouseEvent: any, sigCanvas: any, context: any) => {

  let position = getPosition(mouseEvent, sigCanvas);

  context.lineTo(position.X, position.Y);
  context.stroke();
}
const finishDrawing = (mouseEvent: any, sigCanvas: any, context: any) => {
  drawLine(mouseEvent, sigCanvas, context);

  context.closePath();
  $(sigCanvas).unbind("mousemove")
    .unbind("mouseup")
    .unbind("mouseout");
}
const fetchPerawat = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Perawat.value = response
  })
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}

const setTandaTangan = async (e: any) => {
  await useApi().get(`emr/tanda-tangan/${e.value.value}`).then((element) => {
    if (element) {
      H.tandaTangan().set("signature_1", element.ttd)
    } else {
      H.tandaTangan().set("signature_1", '')
    }
  })
}


const fetchDiagnosaKeperawatan = async (filter: any) => {
  await useApi().get(`/emr/get-dropdown-diagnosa-keperawatan?search=${filter.query}`).then((response) => {
    d_DiagnosaKeperawatan.value = response
  })
}
const addNewDiagnosaKeper = () => {
  input.value.diagnosaKeper.push({
    no: input.value.diagnosaKeper[input.value.diagnosaKeper.length - 1].no + 1,
  });
}
const removeItemDiagnosaKeper = (index: any) => {
  input.value.diagnosaKeper.splice(index, 1)
}
watch(() => [
  input.value.penurunanBB,
  input.value.penurunanNafsuMakan,
  jenisKelamin.value,
], () => {

  let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
  let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

  const total = poin1 + poin2
  input.value.totalNilaiPemeriksaanNutrisi = total

})
async function performAction() {
  await sleep(1000);
initializeMarkingSite('markingsite_2','undo_btn_2', 'redo_btn_2', MARKINGSITE_2.value);
initializeMarkingSite('markingsite_3','undo_btn_3', 'redo_btn_3', MARKINGSITE_3.value);
initializeMarkingSite('markingsite_4','undo_btn_4', 'redo_btn_4', MARKINGSITE_4.value);
// initializeMarkingSite('markingsite_2', 'zoom_in_2', 'zoom_out_2', 'undo_btn_2', 'redo_btn_2', MARKINGSITE_2.value);
// initializeMarkingSite('markingsite_3', 'zoom_in_3', 'zoom_out_3', 'undo_btn_3', 'redo_btn_3', MARKINGSITE_3.value);
// initializeMarkingSite('markingsite_4', 'zoom_in_4', 'zoom_out_4', 'undo_btn_4', 'redo_btn_4', MARKINGSITE_4.value);
  // markignSite2();
  // markignSite3();
  // markignSite4();
}
performAction();
setView()
setAutoFill()
autoFillkeluhanUtama()
loadRiwayat()
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

.table-fkprj,
.th-fkprj,
.td-fkprj {
  border: 1.6px solid black !important;
  display: flex;
  justify-content: center !important;
}

.th-fkprj,
.td-fkprj {
  padding: 8px !important;
}

.body {
  display: flex;
  justify-content: center !important;
}
</style>
