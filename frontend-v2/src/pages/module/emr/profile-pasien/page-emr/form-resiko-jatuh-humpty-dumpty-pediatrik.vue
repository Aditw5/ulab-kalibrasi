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


  <div class="column">
    <VCard>
     
      <div class="column is-12" style="width:100%; overflow-x: scroll;">
        <Fieldset :toggleable="true" legend="Skala Resiko Jatuh Humpty Dumpty Pediatri">
          <div class="column is-12 p-2">
            <div class="columns is-multiline">
              <div class="column">
                <table class="tg" style="table-layout: fixed; width: 100%;">
                  <thead>
                    <tr>
                      <th>Parameter</th>
                      <th>Kriteria</th>
                      <th>Nilai</th>
                      <th>Skor</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td rowspan="4">Usia</td>
                      <td>&lt; 3 tahun</td>
                      <td>4</td>
                      <td rowspan="4">
                        <VField>
                          <VControl>
                            <VInput v-model="input.usia" type="number" placeholder="Skor Usia" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>3 - 7 tahun</td>
                      <td>3</td>
                    </tr>
                    <tr>
                      <td>7 - 13 tahun</td>
                      <td>2</td>
                    </tr>
                    <tr>
                      <td>&gt; 13 tahun</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td rowspan="2">Jenis Kelamin</td>
                      <td>Laki-laki</td>
                      <td>2</td>
                      <td rowspan="2">
                        <VField>
                          <VControl>
                            <VInput v-model="input.jeniskelamin" type="number" placeholder="Skor Jenis Kelamin" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>Perempuan</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td rowspan="4">Diagnosis</td>
                      <td>Diagnosa Neurologi</td>
                      <td>4</td>
                      <td rowspan="4">
                        <VField>
                          <VControl>
                            <VInput v-model="input.diagnosis" type="number" placeholder="Skor Diagnosis" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>Perubahan oksigenasi (diagnosis, respiratorik, dehidrasi, anemia, anoreksia, sinkop, pusing, dsb)</td>
                      <td>3</td>
                    </tr>
                    <tr>
                      <td>Gangguan perilaku/psikiatri</td>
                      <td>2</td>
                    </tr>
                    <tr>
                      <td>Diagnosis lainnya</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td rowspan="3">Gangguan Kognitif</td>
                      <td>Tidak menyadari keterbatasan dirinya</td>
                      <td>3</td>
                      <td rowspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="input.GangguanKognitif" type="number" placeholder="Skor Gangguan Kognitif" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>Lupa akan adanya keterbatasan</td>
                      <td>2</td>
                    </tr>
                    <tr>
                      <td>Orientasi baik terhadap diri sendiri</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td rowspan="5">Faktor Lingkungan</td>
                      <td>Riwayat jatuh/bayi diletakkan di tempat tidur dewasa</td>
                      <td>5</td>
                      <td rowspan="5">
                        <VField>
                          <VControl>
                            <VInput v-model="input.FaktorLingkungan" type="number" placeholder="Skor Faktor Lingkungan" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>Pasien menggunakan alat bantu</td>
                      <td>4</td>
                    </tr>
                    <tr>
                      <td>Pasien menggunakan alat bantu/bayi diletakkan dalam tempat tidur bayi/perabot rumah</td>
                      <td>3</td>
                    </tr>
                    <tr>
                      <td>Pasien diletakkan di tempat tidur</td>
                      <td>2</td>
                    </tr>
                    <tr>
                      <td>Area di luar rumah sakit</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td rowspan="3">Respon terhadap anestesi</td>
                      <td>Dalam 24 jam</td>
                      <td>4</td>
                      <td rowspan="3">
                        <VField>
                          <VControl>
                            <VInput v-model="input.ResponAnastesi" type="number" placeholder="Skor Respon Anastesi" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>Dalam 48 jam</td>
                      <td>3</td>
                    </tr>
                    <tr>
                      <td>&gt; 48 jam atau tidak menjalani</td>
                      <td>1</td>
                    </tr>
                    <tr>
                      <td rowspan="2">Penggunaan medikamentosa</td>
                      <td>Penggunaan salah satu obat di atas</td>
                      <td>2</td>
                      <td rowspan="2">
                        <VField>
                          <VControl>
                            <VInput v-model="input.Medikamentosa" type="number" placeholder="Skor Penggunaan Medikamentosa" />
                          </VControl>
                        </VField>
                      </td>
                    </tr>
                    <tr>
                      <td>Penggunaan medikasi lainnya/tidak ada medikasi</td>
                      <td>1</td>
                    </tr>
                  </tbody>
                      <tr>
                        <td colspan="2"></td>
                        <td>SKOR TOTAL </td>
                        <td>
                          <VField>
                          <VControl>
                            <VInput v-model="input.SkorTotal" type="number"  disabled />
                          </VControl>
                        </VField>
                        </td>
                      </tr>
                      <tr>
                        <td>Keterangan : Skor Minimum 7, Skor Maksimum 23</td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.ResikoRendah" label="Resiko Rendah (7-11) : RR" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td>
                          <VField>
                            <VControl raw subcontrol>
                              <VCheckbox v-model="input.ResikoTinggi" label="Resiko Tinggi (12-23) : RT" class="p-0" color="primary" square />
                            </VControl>
                          </VField>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                      </tr>
                </table>
              </div>
            </div>
          </div>
        </Fieldset>
      </div>

      <br>
      <div class="columns is-multiline">
        <div class="column is-4" style="margin-left: auto;">
          <VCard>
            <h1 style="font-weight: bold; text-align: center"> Perawat </h1>
            <div class="mt-6">
              <img v-if="input.Perawat"
                  :src="'https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=' + (input.Perawat.label ? input.Perawat.label : input.Perawat)">
            </div>
            <div class="mt-2">
              <VField>
                <VControl class="prime-auto">
                  <AutoComplete v-model="input.Perawat" :suggestions="d_Pegawai"
                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'"
                    :loadingIcon="'pi pi-spinner'" :field="'label'" placeholder="Cari Perawat..." />
                </VControl>
              </VField>
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
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import ButtonEmr from '../page-emr-plugins/button-emr.vue'
import AutoComplete from 'primevue/autocomplete';
import Fieldset from 'primevue/fieldset';
import TandaTangan from '../page-emr-plugins/tanda-tangan.vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useUserSession } from '/@src/stores/userSession'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column'
import * as EMR from '../page-emr-plugins/pengkajian-awal-gizi-rawat-inap'


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

const props = withDefaults(
  defineProps<{
    pasien?: any
    registrasi?: any
    diagnosis?: any
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
const isLoadingVitalSign: any = ref(false)
const d_Pegawai: any = ref([])
const d_Dokter: any = ref([])
const d_Ruangan: any = ref([])
const sourceDataLab = ref([])
const showData: any = ref(false)
const d_Kelas: any = ref([])
const d_Diagnosa: any = ref([])
const isLoadingTT: any = ref(false)
const item: any = reactive({

  NOREC_PD: (NOREC_PD !== undefined ? NOREC_PD : '') || '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})

const input = ref({
  usia: 0,
  jeniskelamin: 0,
  diagnosis: 0,
  GangguanKognitif: 0,
  FaktorLingkungan: 0,
  ResponAnastesi: 0,
  Medikamentosa: 0,
  SkorTotal: 0,
  ResikoRendah: false,
  ResikoTinggi: false,
});

// Computed untuk menghitung total skor
const SkorTotal = computed(() => {
  const total =
    Number(input.value.usia) +
    Number(input.value.jeniskelamin) +
    Number(input.value.diagnosis) +
    Number(input.value.GangguanKognitif) +
    Number(input.value.FaktorLingkungan) +
    Number(input.value.ResponAnastesi) +
    Number(input.value.Medikamentosa);

  // Update nilai `SkorTotal` di input
  input.value.SkorTotal = total;

  // Update kategori risiko
  if (total >= 7 && total <= 11) {
    input.value.ResikoRendah = true;
    input.value.ResikoTinggi = false;
  } else if (total >= 12 && total <= 23) {
    input.value.ResikoRendah = false;
    input.value.ResikoTinggi = true;
  } else {
    input.value.ResikoRendah = false;
    input.value.ResikoTinggi = false;
  }

  return total;
});

// Watch untuk debugging jika diperlukan
watch(SkorTotal, (newVal) => {
  console.log("SkorTotal updated:", newVal);
});

const COLLECTION: any = ref('lembarResikoJatuhHumptyDumptyPediatrik') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const isLocked = ref(false)
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const loadRiwayat = async () => {
  // Lakukan pengambilan data riwayat dari API
  await useApi().get(`/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    .then((response: any) => {
      if (response.length) {
        // Mengatur nilai input.value jika perlu
        input.value = response[0] // Sesuaikan sesuai struktur respons API Anda
        if (response[0].tandaTanganAhliGizi) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganAhliGizi)
        }
        if (NOREC_EMRPASIEN.value == '') {
          NOREC_EMRPASIEN.value = response[0].emrpasienfk
        }
      }
    })
}

const show = () => {
  showData.value = true
}

const SourceHasilLab = async () => {
  // Initialize sourceDataLab.value as empty only if necessary
  sourceDataLab.value = [];

  // Fetch first set of lab results (new data)
  await useApi()
    .get(`/bridging/penunjang/get-hasil-new-registrasi?norec_apd=${item.NOREC_APD}&nocmfk=${ID_PASIEN}&noregistrasi=${props.registrasi.noregistrasi}&nocm=${props.pasien.nocm}`)
    .then((response) => {
      if (response.length > 0) {
        const formattedResponse = response.flatMap((group) =>
          group
            .filter((item) => !item.IsHeader) // Only take non-header items
            .map((item) => ({
              nama_pemeriksaan: item.TestName,
              tgl_hasil: item.ValidateOn,
              normal: item.RefRange,
              no_order: item.OrderTestId,
              no_urut: parseInt(item.DispSequence, 10),
              hasil: item.ResultValue,
            }))
        );

        // Append formatted response to sourceDataLab.value
        sourceDataLab.value = sourceDataLab.value.concat(formattedResponse);
      }
    });

  // Fetch second set of lab results (existing data)
  await useApi()
    .get(`/laboratorium/source-hasil-lab?noregistrasi=${props.registrasi.noregistrasi}`)
    .then((response) => {
      const formattedExistingResponse = response.map((element) => ({
        ...element,
      }));

      // Append response to sourceDataLab.value without overwriting
      sourceDataLab.value = sourceDataLab.value.concat(formattedExistingResponse);
    });

  // Sort combined data by `tgl_hasil`
  sourceDataLab.value.sort((a, b) => new Date(a.tgl_hasil) - new Date(b.tgl_hasil));

  // Reassign sequential numbering after sorting
  sourceDataLab.value = sourceDataLab.value.map((item, index) => ({
    ...item,
    no: index + 1, // Assign new sequential number
  }));
};

const fetchPegawai = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
  d_Pegawai.value = response
}

const addRow = () => {
  input.value.detailSimplified.push({
    no: input.value.detailSimplified[input.value.detailSimplified.length - 1].no + 1,
  });
}
const removeRow = (index: any) => {
  input.value.detailSimplified.splice(index, 1)
}

const setTandaTangan = async (e: any) => {
  const response = await useApi().get(
    `/emr/tanda-tangan/${e.value.value}`)
  if (response != null) {
    H.tandaTangan().set("signature_1", response.ttd)
    input.value.tandaTanganAhliGizi = response.ttd
  } else {
    H.tandaTangan().set("signature_1", '')
  }
}

const fetchRuangan = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`)
  d_Ruangan.value = response
}

const fetchDiagnosa = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/diagnosa_m?select=id,namadiagnosa&param_search=namadiagnosa&query=${filter.query}&limit=10`)
  d_Diagnosa.value = response
}

const fetchKelas = async (filter: any) => {
  const response = await useApi().get(
    `/emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`)
  d_Kelas.value = response
}

const fetchDokter = async (filter: any) => {
  const response = await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`)
  d_Dokter.value = response
}


const simpan = () => {
  let ID = input.value.id ? input.value.id : ''
  let object: any = {}

  object = input.value
  object.tandaTanganAhliGizi = H.tandaTangan().get("signature_1")
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

  isLoading.value = true;
  useApi().post(`/emr/simpan-emr`, json)
    .then((response: any) => {
      isLoading.value = false;
      NOREC_EMRPASIEN.value = response.norec_emr;
      input.value.id = response.id;
      isLocked.value = true;
    })
    .catch((e: any) => {
      isLoading.value = false;
    })
}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.tanggalWaktuRegistrasi = props.registrasi.tglregistrasi;
  input.value.tanggalWaktuKunjuangan = new Date();
  input.value.tglPembuatan = new Date();
  input.value.umur = props.pasien.umur;
  input.value.namaruangan = props.registrasi.namaruangan;
  input.value.namakelas = props.registrasi.namakelas;
  input.value.dokter = props.registrasi.dokter;

  isLoadingVitalSign.value = true;

  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=VitalSign" + "&field=beratBadan,tinggiBadan"
  ).then((response) => {
    if (response != null) {
      input.value.bb = response.beratBadan;
      input.value.tb = response.tinggiBadan;
    }
    isLoadingVitalSign.value = false;
  }).catch((error) => {
    console.error("Error fetching vital signs:", error);
    isLoadingVitalSign.value = false;
  })
  await useApi().get(
    "emr/auto-fill?norec_pd=" + props.registrasi.norec_pd +
    "&collection=PengkajianAwalPasienGawatDarurat" + "&field=beratBadan,tinggiBadan"
  ).then((response) => {
    if (response != null) {
      input.value.bb = response.beratBadan;
      input.value.tb = response.tinggiBadan;
    }
    isLoadingPengkajianAwalPasienGawatDarurat.value = false;
  }).catch((error) => {
    console.error("Error fetching vital signs:", error);
    isLoadingPengkajianAwalPasienGawatDarurat.value = false;
  })
}
setView()
setAutoFill()
loadRiwayat()
await SourceHasilLab()
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
</style>
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
  border-color: var(--fade-grey-dark-2);
  border-style: solid;
  border-width: 1px;
  font-family: Arial, sans-serif;
  font-size: 14px;
  overflow: hidden;
  padding: 10px 5px;
  word-break: normal;
  vertical-align: middle
}

.tg th {
  border-color: var(--fade-grey-dark-3);
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
  vertical-align: top;
  font-weight: bold;
}
</style>