<template>
  <div class="form-layout is-stacked-2">
    <div class="form-outer" style="margin-top:15px">
            <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
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
                        <ButtonEmr :NOREC_EMRPASIEN="NOREC_EMRPASIEN" :COLLECTION="COLLECTION" :isLoading="isLoading" @simpan="simpan"
                        @kembaliKeun="kembaliKeun"></ButtonEmr>
                    </div>
                </div>
            </div>

    </div>
  </div>
  <div class="column is-12">
    <VCard>
      <div class="column is-12">
        <div class="columns is-multiline">
          <div class="column is-6">
            <h1 style="font-weight: bold; margin-bottom: 0.5rem;"> Prosedur </h1>
            <VField>
              <VControl>
                <VInput type="text" class="input" placeholder="Prosedur" v-model="input.prosedur" />
              </VControl>
            </VField>
          </div>
          <div class="column is-6">
            <h1 style="font-weight: bold; margin-bottom: 0.5rem;">Tanggal Prosedur</h1>
            <VField>
              <VDatePicker v-model="input.tanggalProsedur" mode="dateTime" style="width: 100%" trim-weeks>
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>
          </div>
        </div>
      </div>
    </VCard>
  </div>
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PENANDAAN LOKASI OPERASI" :toggleable="true">
          <div class="columns is-multiline p-3">
            <!-- <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-4">
                  <VField label="Pilih Jenis Kelamin"></VField>
                  <div class="columns is-multiline">
                    <div class="column is-6">
                      <div class="form-tambahan-radio">
                        <RadioButton v-model="jenisKelamin" inputId="jk1" name="LAKI-LAKI" value="LAKI-LAKI" />
                        <label for="jk1" class="ml-2">LAKI-LAKI</label>
                      </div>
                    </div>
                    <div class="column is-6">
                      <div class="form-tambahan-radio">
                        <RadioButton v-model="jenisKelamin" inputId="jk2" name="PEREMPUAN" value="PEREMPUAN" />
                        <label for="jk2" class="ml-2">PEREMPUAN</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="column is-12">
              <div class="columns is-multiline p-3">
                <div class="column is-12 body">
                  <div :style="'background-image:url(' + MARKINGSITE + ')'"
                    style="text-align: center; z-index: 9;background-repeat: no-repeat;background-position: center;width: 600px;height: 900px;">
                    <canvas id="markingsite" height="900" width="600"></canvas>
                  </div><br>
                  <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                    @click="clearCanvas('markingsite')"> Clear
                  </VButton>
                </div>
                <div class="column is-12">
                  <table width="100%">
                    <tbody>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;text-align:center">
                          <div :style="'background-image:url(' + MARKINGSITE_2 + ')'"
                            style="text-align: center; z-index: 9;background-repeat: no-repeat;background-position: center;width: 900px;height: 400px;">
                            <canvas id="markingsite_2" height="400" width="900"></canvas>
                          </div>
                          <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                            @click="clearCanvas('markingsite_2')"> Clear
                          </VButton>
                        </td>
                      </tr>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;text-align:center">
                          <div :style="'background-image:url(' + MARKINGSITE_3 + ')'"
                            style="text-align: center; z-index: 9;background-repeat: no-repeat;background-position: center;width: 900px;height: 400px;">
                            <canvas id="markingsite_3" height="400" width="900"></canvas>
                          </div>
                          <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                            @click="clearCanvas('markingsite_3')"> Clear
                          </VButton>
                        </td>
                      </tr>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;text-align:center">
                          <div :style="'background-image:url(' + MARKINGSITE_4 + ')'"
                            style="text-align: center; z-index: 9;background-repeat: no-repeat;background-position: center;width: 900px;height: 400px;">
                            <canvas id="markingsite_4" height="400" width="900"></canvas>
                          </div>
                          <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                            @click="clearCanvas('markingsite_4')"> Clear
                          </VButton>
                        </td>
                      </tr>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;text-align:center">
                          <div :style="'background-image:url(' + MARKINGSITE_5 + ')'"
                            style="text-align: center; z-index: 9;background-repeat: no-repeat;background-position: center;width: 900px;height: 400px;">
                            <canvas id="markingsite_5" height="400" width="900"></canvas>
                          </div>
                          <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                            @click="clearCanvas('markingsite_5')"> Clear
                          </VButton>
                        </td>
                      </tr>
                      <tr class="tr-fkprj">
                        <td class="td-fkprj" style="vertical-align:inherit;text-align:center">
                          <div :style="'background-image:url(' + MARKINGSITE_6 + ')'"
                            style="text-align: center; z-index: 9;background-repeat: no-repeat;background-position: center;width: 900px;height: 480px;">
                            <canvas id="markingsite_6" height="480" width="900"></canvas>
                          </div>
                          <VButton type="button" rounded outlined color="danger" raised icon="feather:trash"
                            @click="clearCanvas('markingsite_6')"> Clear
                          </VButton>
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
      <div class="columns is-multiline">
        <div class="column is-12">
          <VCard>
            <div class="column is-12 p-2">
              <h1 style="font-weight: bold;" class="mb-2"> Saya menyatakan bahwa lokasi yang telah ditetapkan pada
                diagram
                adalah benar</h1>
            </div>
          </VCard>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-6">
          <VCard>
            <div class="column is-12 p-2">
              <h1 style="font-weight: bold;" class="mb-2"> Cirebon, Tanggal </h1>
              <VField>
                <VDatePicker v-model="input.tglPembuatanPasien" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
              <div class="column is-12">
                <img v-if="input.namapasien"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.namapasien ? input.namapasien : input.namapasien) + ', ' + (input.nocm ? input.nocm : input.nocm) + ', ' + (input.noregistrasi ? input.noregistrasi : input.noregistrasi) + ', ' + (input.tglPembuatanPasien ? input.tglPembuatanPasien : input.tglPembuatanPasien)">
                <!-- <TandaTangan :elemenID="'signature_1'" :width="'180'" :height="'180'"></TandaTangan> -->
              </div>
              <div class="column is-12">
                <h1 class="p-0" style="font-weight: bold;">Pasien </h1>
                <VField class="mt-2">
                  <VControl class="prime-auto">
                    <VInput type="text" class="input prime-auto" placeholder="Pasien" v-model="input.namapasien" />
                  </VControl>
                </VField>
              </div>
            </div>
          </VCard>
        </div>
        <div class="column is-6">
          <VCard>
            <div class="column is-12 p-2">
              <h1 style="font-weight: bold;" class="mb-2"> Cirebon, Tanggal </h1>
              <VField>
                <VDatePicker v-model="input.tglPembuatanDokter" mode="dateTime" style="width: 100%" trim-weeks
                  :max-date="new Date()">
                  <template #default="{ inputValue, inputEvents }">
                    <VField>
                      <VControl icon="feather:calendar" fullwidth>
                        <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                      </VControl>
                    </VField>
                  </template>
                </VDatePicker>
              </VField>
              <div class="column is-12">
                <img v-if="input.dokter"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.dokter.label ? input.dokter.label : input.dokter) + ', ' + (input.dokter.value ? input.dokter.value : input.dokter) + ', ' + (input.tglPembuatanDokter ? input.tglPembuatanDokter : input.tglPembuatanDokter)">
                <!-- <TandaTangan :elemenID="'signature_2'" :width="'180'" :height="'180'"></TandaTangan> -->
              </div>
              <div class="column is-12">
                <h1 class="p-0" style="font-weight: bold;">Dokter </h1>
                <VField>
                  <VControl class="prime-auto">
                    <AutoComplete v-model="input.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                      :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                      :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Dokter DPJP..." class="mt-2"
                      @item-select="setTandaTangan($event)" />
                  </VControl>
                </VField>
              </div>
            </div>
          </VCard>
        </div>
      </div>
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
const MARKINGSITE: any = ref('')
const MARKINGSITE_2: any = ref('')
const MARKINGSITE_3: any = ref('')
const MARKINGSITE_4: any = ref('')
const MARKINGSITE_5: any = ref('')
const MARKINGSITE_6: any = ref('')
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
  MARKINGSITE.value = '/images/simrs/' + (jenisKelamin.value == 'PEREMPUAN' ? 'body-women.svg' : 'body-man.svg')
  MARKINGSITE_2.value = '/images/simrs/kepala1.svg'
  MARKINGSITE_3.value = '/images/simrs/kepala2.svg'
  MARKINGSITE_4.value = '/images/simrs/tangan1.svg'
  MARKINGSITE_5.value = '/images/simrs/tangan2.svg'
  MARKINGSITE_6.value = '/images/simrs/kaki.svg'
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
        if (response[0].tandaTanganPasien) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPasien)
        }
        if (response[0].tandaTanganDokter) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganDokter)
        }
        if (response[0].tandaBodyDraw) {
          H.tandaTangan().set("markingsite", response[0].tandaBodyDraw, 600, 900)
        }
        if (response[0].tandaKepala1) {
          H.tandaTangan().set("markingsite_2", response[0].tandaKepala1, 900, 400)
        }
        if (response[0].tandaKepala2) {
          H.tandaTangan().set("markingsite_3", response[0].tandaKepala2, 900, 400)
        }
        if (response[0].tandaTangan1) {
          H.tandaTangan().set("markingsite_4", response[0].tandaTangan1, 900, 400)
        }
        if (response[0].tandaTangan2) {
          H.tandaTangan().set("markingsite_5", response[0].tandaTangan2, 900, 400)
        }
        if (response[0].tandaKaki) {
          H.tandaTangan().set("markingsite_6", response[0].tandaKaki, 900, 480)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.tandaTanganPasien = H.tandaTangan().get("signature_1")
  object.tandaTanganDokter = H.tandaTangan().get("signature_2")
  object.tandaBodyDraw = H.tandaTangan().get("markingsite")
  object.tandaKepala1 = H.tandaTangan().get("markingsite_2")
  object.tandaKepala2 = H.tandaTangan().get("markingsite_3")
  object.tandaTangan1 = H.tandaTangan().get("markingsite_4")
  object.tandaTangan2 = H.tandaTangan().get("markingsite_5")
  object.tandaKaki = H.tandaTangan().get("markingsite_6")
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
const markignSite = () => {
  let sigCanvas: any = document.getElementById("markingsite");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
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


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const markignSite2 = () => {
  let sigCanvas: any = document.getElementById("markingsite_2");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
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


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite_2").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const markignSite3 = () => {
  let sigCanvas: any = document.getElementById("markingsite_3");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
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


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite_3").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const markignSite4 = () => {
  let sigCanvas: any = document.getElementById("markingsite_4");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
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


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite_4").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const markignSite5 = () => {
  let sigCanvas: any = document.getElementById("markingsite_5");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
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


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite_5").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
const markignSite6 = () => {
  let sigCanvas: any = document.getElementById("markingsite_6");
  // sigCanvas.height = 500
  // sigCanvas.width = 500
  let context = sigCanvas.getContext("2d");
  context.strokeStyle = "red";
  context.lineJoin = "round";
  context.lineWidth = 2;
  let is_touch_device = 'ontouchstart' in document.documentElement;

  if (is_touch_device) {

    let drawer: any = {
      isDrawing: false,
      touchstart: function (coors: any) {
        context.beginPath();
        context.moveTo(coors.x, coors.y);
        this.isDrawing = true;
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


    function draw(event: any) {
      let coors = {
        x: event.targetTouches[0].pageX,
        y: event.targetTouches[0].pageY
      };

      let obj = sigCanvas;

      if (obj.offsetParent) {

        do {
          coors.x -= obj.offsetLeft;
          coors.y -= obj.offsetTop;
        }

        while ((obj = obj.offsetParent) != null);
      }


      drawer[event.type](coors);
    }


    sigCanvas.addEventListener('touchstart', draw, false);
    sigCanvas.addEventListener('touchmove', draw, false);
    sigCanvas.addEventListener('touchend', draw, false);


    sigCanvas.addEventListener('touchmove', function (event: any) {
      event.preventDefault();

    }, false);
  } else {

    $("#markingsite_6").mousedown(function (mouseEvent: any) {

      let position = getPosition(mouseEvent, sigCanvas);
      context.moveTo(position.X, position.Y);
      context.beginPath();
      $(this).mousemove(function (mouseEvent: any) {
        drawLine(mouseEvent, sigCanvas, context);
      }).mouseup(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      }).mouseout(function (mouseEvent: any) {
        finishDrawing(mouseEvent, sigCanvas, context);
      });
    });

  }
}
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
  markignSite();
  markignSite2();
  markignSite3();
  markignSite4();
  markignSite5();
  markignSite6();
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
