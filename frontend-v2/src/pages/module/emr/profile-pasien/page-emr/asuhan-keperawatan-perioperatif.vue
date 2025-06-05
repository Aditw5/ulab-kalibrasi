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
  <div class="columns is-12">
    <VCard>
      <div class="column is-12">
        <Fieldset class="p-fieldsets" legend="PRE OPERATIF" :toggleable="true" style="overflow: scroll">
          <div class="columns is-multiline p-3" style="width: 150% !important;">
            <table class="tg table-tg" style="width: 100% !important;">
              <thead>
                <tr>
                  <th align="center" rowspan="2">Pengkajian</th>
                  <th align="center" rowspan="2">Masalah</th>
                  <th align="center" rowspan="2" width="20%">Rencana Tindakan</th>
                  <th align="center" colspan="2">Tindakan Keperawatan</th>
                </tr>
                <tr>
                  <th align="center" width="25%">Implementasi</th>
                  <th align="center" width="15%">Evaluasi</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>
                    <div class="columns is-12">
                      <VField label="Jam :" class="column is-2"></VField>
                      <VField class="column is-10">
                        <VControl class="prime-auto">
                          <Calendar v-model="input.jamPreOperatif" selectionMode="single" :manualInput="true"
                            class="w-100" :showIcon="true" showTime hourFormat="24" :date-format="'yy-mm-dd'"
                            :disabled="item.tgl2" />
                        </VControl>
                      </VField>
                    </div>

                    <!-- Breathing -->
                    <div class="column is-12">
                      <VField label="1. Breathing" class="column is-2"></VField>
                      <VField>
                        <VControl>
                          <VRadio v-model="input.breathing" value="spontan" label="Spontan" name="breathingRadio"
                            color="primary" square />

                          <VRadio v-model="input.breathing" value="dibantu" label="Dibantu" name="breathingRadio"
                            color="primary" square />
                        </VControl>
                      </VField>
                    </div>
                    <div class="columns is-12">
                      <VField label="Resp rate" class="column is-2"></VField>
                      <VField addons class="column is-10">
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Respiration Rate" v-model="input.respRate"
                            :tabindex="1" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="columns is-12">
                      <VField label="Saturasi O2" class="column is-2"></VField>
                      <VField addons class="column is-10">
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Saturasi O2" v-model="input.saturasi"
                            :tabindex="1" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>%</VButton>
                        </VControl>
                      </VField>
                    </div>

                    <!-- Blood -->
                    <div class="column is-12">
                      <VField label="2. Blood" class="column is-2"></VField>
                    </div>
                    <div class="columns is-12">
                      <VField label="Tekanan Darah" class="column is-2"></VField>
                      <VField addons class="column is-10">
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Tekanan Darah" v-model="input.tekananDarah"
                            :tabindex="1" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>mmHg</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="columns is-12">
                      <VField label="Nadi" class="column is-2"></VField>
                      <VField addons class="column is-10">
                        <VControl expanded>
                          <VInput type="text" class="input" placeholder="Nadi" v-model="input.nadi" :tabindex="1" />
                        </VControl>
                        <VControl class="field-addon-body">
                          <VButton static>x/mnt</VButton>
                        </VControl>
                      </VField>
                    </div>
                    <div class="column is-12">
                      <VField label="Canula Intra Vena" class="column is-2"></VField>
                      <VField>
                        <VControl>
                          <VRadio v-model="input.canulaIntraVena" value="terpasang" label="Terpasang" name="canulaRadio"
                            color="primary" square />

                          <VRadio v-model="input.canulaIntraVena" value="tidak terpasang" label="Tidak"
                            name="canulaRadio" color="primary" square />
                        </VControl>
                      </VField>
                    </div>

                    <!-- Brain -->
                    <div class="columns is-multiline is-12">
                      <VField label="Kesadaran" class="column is-12 has-text-weight-bold"></VField>
                      <div class="column is-6" v-for="(data, index) in kesadaran">
                        <VField :key="index">
                          <VControl>
                            <VRadio v-model="input[data.model]" :value="data.value" :label="data.value"
                              name="'kesadaranRadio" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                    <div class="columns is-multiline is-12">
                      <VField label="Status Emosi" class="column is-12 has-text-weight-bold"></VField>
                      <div class="column is-6" v-for="(data, index) in statusEmosi">
                        <VField :key="index">
                          <VControl>
                            <VRadio v-model="input[data.model]" :value="data.value" :label="data.value"
                              name="statusEmosiRadio" color="primary" square />
                          </VControl>
                        </VField>
                      </div>
                    </div>

                    <!-- Penilaian Nyeri -->
                    <div class="column is-12">
                      <VField label="PENILAIAN NYERI" class="column is-2"></VField>
                    </div>
                    <div class="columns is-multiline is-12" v-for="(data, index) in penilaianNyeri" :key="index">
                      <template v-if="data.type === 'text'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <VField addons class="column is-10">
                          <VControl expanded>
                            <VInput type="text" class="input" :placeholder="data.value" v-model="input[data.value]"
                              :tabindex="1" />
                          </VControl>
                        </VField>
                      </template>
                      <template v-if="data.type === 'radio'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <div class="column is-2" v-for="(list, index) in data.listValue">
                          <VField :key="index">
                            <VControl>
                              <VRadio v-model="input[data.model]" :value="list.value" :label="list.value"
                                :name="data.model" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>

                    <div class="column is-12">
                      <VField label="4. BLADDER" class="column is-2"></VField>
                    </div>
                    <div class="columns is-multiline is-12" v-for="(data, index) in bladder" :key="index">
                      <template v-if="data.type === 'text'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <VField addons class="column is-10">
                          <VControl expanded>
                            <VInput type="text" class="input" :placeholder="data.value" v-model="input[data.value]"
                              :tabindex="1" />
                          </VControl>
                        </VField>
                      </template>
                      <template v-if="data.type === 'radio'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <div class="column is-3" v-for="(list, index) in data.listValue">
                          <VField :key="index">
                            <VControl>
                              <VRadio v-model="input[data.model]" :value="list.value" :label="list.label"
                                :name="data.model" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>

                    <div class="column is-12">
                      <VField label="5. BOWEL" class="column is-2"></VField>
                    </div>
                    <div class="columns is-multiline is-12" v-for="(data, index) in bowel" :key="index">
                      <template v-if="data.type === 'text'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <VField addons class="column is-10">
                          <VControl expanded>
                            <VInput type="text" class="input" :placeholder="data.value" v-model="input[data.value]"
                              :tabindex="1" />
                          </VControl>
                        </VField>
                      </template>
                      <template v-if="data.type === 'radio'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <div class="column is-2" v-for="(list, index) in data.listValue">
                          <VField :key="index">
                            <VControl>
                              <VRadio v-model="input[data.model]" :value="list.value" :label="list.label"
                                :name="data.model" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>

                    <div class="column is-12">
                      <VField label="6. BONE" class="column is-2"></VField>
                    </div>
                    <div class="columns is-multiline is-12" v-for="(data, index) in bone" :key="index">
                      <template v-if="data.type === 'text'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <VField addons class="column is-10">
                          <VControl expanded>
                            <VInput type="text" class="input" :placeholder="data.value" v-model="input[data.value]"
                              :tabindex="1" />
                          </VControl>
                        </VField>
                      </template>
                      <template v-if="data.type === 'radio'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <div class="column is-2" v-for="(list, index) in data.listValue">
                          <VField :key="index">
                            <VControl>
                              <VRadio v-model="input[data.model]" :value="list.value" :label="list.label"
                                :name="data.model" color="primary" square />
                            </VControl>
                          </VField>
                        </div>
                      </template>
                    </div>
                  </td>
                  <td>
                    <div class="columns is-multiline">
                      <div class="column is-12" v-for="(data, i) in masalahPreOperatif">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.value"
                              :label="data.label"
                              class="p-0"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VTextarea
                            rows="2"
                            placeholder="..............."
                            v-model="input.masalahPreOperatifLainnya"
                          ></VTextarea>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="columns is-multiline">
                      <div class="column is-12" v-for="(data, i) in rencanaTindakanPreOperatif">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.value"
                              :label="data.label"
                              class="p-0"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                      <div class="column is-12">
                        <VField>
                          <VTextarea
                            rows="2"
                            placeholder="..............."
                            v-model="input.rencanaTindakanPreOperatifLainnya"
                          ></VTextarea>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="columns is-multiline">
                      <div class="column is-12" v-for="(data, i) in implementasiPreOperatif">
                        <VField>
                          <VControl raw subcontrol>
                            <VCheckbox
                              v-model="input[data.model]"
                              :true-value="data.value"
                              :label="data.label"
                              class="p-0"
                              color="primary"
                              square
                            />
                          </VControl>
                        </VField>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="columns is-multiline is-12" v-for="(data, index) in evaluasiPreOperatif" :key="index">
                      <template v-if="data.type === 'radio'">
                        <div class="column is-6" v-for="(list, index) in data.listValue">
                          <VField :key="index">
                            <VControl>
                              <VRadio v-model="input[data.model]" :value="list.value" :label="list.label"
                                :name="data.model" color="primary" square class="p-0"/>
                            </VControl>
                          </VField>
                        </div>
                      </template>
                      <template v-if="data.type === 'text'">
                        <VField :label="data.value" class="column is-2"></VField>
                        <VField addons class="column is-10">
                          <VControl expanded>
                            <VInput type="text" class="input" :placeholder="data.value" v-model="input[data.value]"
                              :tabindex="1" />
                          </VControl>
                        </VField>
                      </template>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </Fieldset>
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
import * as EMR from '../page-emr-plugins/asuhan-keperawatan-perioperatif'
import $ from "jquery";
import RadioButton from 'primevue/radiobutton';
import sleep from '/@src/utils/sleep'
import Calendar from 'primevue/calendar';




let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
let kesadaran = ref(EMR.Kesadaran())
let statusEmosi = ref(EMR.statusEmosi())
let penilaianNyeri = ref(EMR.penilaianNyeri())
let bladder = ref(EMR.bladder())
let bowel = ref(EMR.bowel())
let bone = ref(EMR.bone())
let masalahPreOperatif = ref(EMR.masalahPreOperatif())
let rencanaTindakanPreOperatif = ref(EMR.rencanaTindakanPreOperatif())
let implementasiPreOperatif = ref(EMR.implementasiPreOperatif())
let evaluasiPreOperatif = ref(EMR.evaluasiPreOperatif())

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
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref(props.COLLECTION) //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  diagnosaKeper: [{ no: 1 }],
  jamPreOperatif: new Date()
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
  jenisKelamin.value = props.pasien.jeniskelamin.toUpperCase()
  MARKINGSITE.value = '/images/simrs/' + (jenisKelamin.value == 'PEREMPUAN' ? 'cewek.png' : 'cowok.png')
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
        if (response[0].tandaTanganPerawat) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawat)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  console.log(object)
  let json = {
    'id': ID,
    'norec_emr': NOREC_EMRPASIEN.value,
    'collection': COLLECTION.value,
    'url_form': props.FORM_URL,
    'name_form': props.FORM_NAME,
    'jenis_emr': 'asesmen_medis',
    'data': object
  }
  isLoading.value = false
  // useApi().post(
  //   `/emr/simpan-emr`, json).then((response: any) => {
  //     isLoading.value = false
  //     NOREC_EMRPASIEN.value = response.norec_emr
  //     input.value.id = response.id
  //   }).catch((e: any) => {
  //     isLoading.value = false
  //   })
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
// async function performAction() {
//   await sleep(1000);
//   markignSite();
// }
// performAction();
// setView()
// setAutoFill()
// autoFillkeluhanUtama()
// loadRiwayat()
</script>

<style lang="scss" scoped>
.tg {
  border-collapse: collapse;
  border-spacing: 0;
  width: 120%;
  background-color: var(--white);
  border-color: var(--fade-grey-dark-2) !important;
}

.table-tg {
    width: 150%;
    overflow: scroll;
  }

.tg-card {
  background-color: #feffed;
}

.is-dark {
  .tg {
    background-color: var(--dark-sidebar-light-6)
  }

  .tg-card {
    background-color: var(--dark-sidebar-light-6)
  }
}

.tg td {
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
}

.tg th {
  border-color: var(--fade-grey-dark-3) !important;
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  font-weight: bold;
  text-transform: uppercase;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle;
}

.tg .tg-0lax {
  text-align: left;
  vertical-align: top
}

.scroll-container-rev {
  height: 1000px;
  overflow: auto;
}

@media (max-width: 1144px) {

  .table-tg {
    width: 150%;
  }
}
</style>
