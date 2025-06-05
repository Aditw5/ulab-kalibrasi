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

  <VButton type="button" class="m-4" outlined icon="feather:plus"
    @click="tambahPersetujuan" color="success" v-tooltip-prime.top="'Tambah '">Tambah
  </VButton>

  <Fieldset v-for="(item, indexData) in input.details" :legend="`Peresetujuan/Penolakan ke ${item.no}`" :toggleable="true">
    <VButton type="button" class="m-4" outlined icon="feather:trash"
    @click="hapusPersetujuan(indexDetail)" color="danger" v-tooltip-prime.top="'Tambah '">Hapus Daftar
  </VButton>
    <VCard>
      <h3 class="title is-5 mb-2" style="text-align: center">DATA PERMINTAAN TRANSFUSI DARAH</h3>
      <p>
      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">INFORMED CONSENT</h1>
        </div>
        <div class="column is-6">
          <VField>
            <div style="display: flex; align-items: center;">
              <VControl raw subcontrol>
                <VCheckbox v-model="input.ada" label="Ada" class="p-0" color="primary" square />
              </VControl>
              <VControl raw subcontrol style="margin-left: 10px;"> <!-- Tambahkan margin kiri untuk jarak -->
                <VCheckbox v-model="input.tidak" label="Tidak" class="p-0" color="primary" square />
              </VControl>
            </div>
          </VField>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Tanggal Pemberian Darah</h1>
        </div>
        <div class="column is-6">
          <VDatePicker class="pt-3" v-model="item.tglDibuat" color="green" trim-weeks mode="date" :max-date="new Date()">
      <template #default="{ inputValue, inputEvents }" class="pb-0">
        <VField>
          <VControl icon="feather:calendar">
            <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents" class="is-rounded_Z" />
          </VControl>
        </VField>
      </template>
    </VDatePicker>
        </div>
      </div>
      <div class="columns is-multiline">
          <div class="column is-3">
            <h1 class="mt-3">Jam Penerimaan Darah</h1>
          </div>
          <div class="column is-6">
            <VField class="pt-3">
              <div style="display: flex; align-items: center;">
                <!-- Input waktu pertama -->
                <VControl>
                  <VInput type="text" v-model="item.jamPenerimaDarahMulai" placeholder="00:00" />
                </VControl>

                <!-- Teks "s/d" di tengah -->
                <span style="margin: 0 10px;">s/d</span>

                <!-- Input waktu kedua -->
                <VControl>
                  <VInput type="text" v-model="item.jamPenerimaDarahSelesai" placeholder="00:00" />
                </VControl>
              </div>
            </VField>
          </div>
        </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Golongan Darah Pasien</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.goldarah" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Jumlah Darah yang diberikan</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.jumlahDarah" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Nomor Kantong Darah</h1>
        </div>
        <div class="column is-6">
          <VField class="pt-3">
            <VControl>
              <VInput type="text" v-model="item.kantongDarah" />
            </VControl>
          </VField>
        </div>
      </div>

      <div class="columns is-multiline">
        <div class="column is-3">
          <h1 class="mt-3">Tanggal Kadaluwarsa</h1>
        </div>
        <div class="column is-6">
          <VDatePicker class="pt-3" v-model="item.tglDibuat" color="green" trim-weeks mode="datetime">
            <template #default="{ inputValue, inputEvents }" class="pb-0">
              <VField>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded_Z" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>
      </p>
    </VCard>

    <vCard>
      <h3 class="title is-5 mb-2" style="text-align: center">VERIFIKASI DATA INSTRUKSI DOKTER DENGAN FORMULIR DAN DARAH YANG ADA</h3>
      <p>
        <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
    <thead>
    <tr>
      <th rowspan="2" style="width: 5%;  background-color: #FFB6C1; text-align: center;">No</th>
      <th rowspan="2" style="width: 30%; background-color: #FFB6C1; text-align: center;">Yang dinilai</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Instruksi Dokter</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Form Permintaan</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Kantong Darah</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Label Darah</th>
    </tr>
    <tr>
      <th style="width: 7%;">Sesuai</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Sesuai</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Sesuai</th>
      <th style="width: 7%;">Tidak</th>
      <th style="width: 7%;">Sesuai</th>
      <th style="width: 7%;">Tidak</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>1</td>
    <td>Jenis Darah</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiSesuai1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiTidak1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanSesuai1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanTidak1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongSesuai1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongTidak1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelSesuai1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelTidak1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

  <tr>
    <td>2</td>
    <td>Golongan Darah</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiSesuai2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiTidak2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongSesuai2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongTidak2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelSesuai2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelTidak2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>


  <tr>
    <td>3</td>
    <td>Kondisi Kantong Darah</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiSesuai3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiTidak3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanSesuai3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanTidak3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongSesuai3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongTidak3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelSesuai3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelTidak3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

  <tr>
    <td>4</td>
    <td>Nomor Kantong Darah</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

  <tr>
    <td>5</td>
    <td>Tanggal Kadaluwarsa</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.instruksiTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.permintaanTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
        </tbody>
      </table>
      </p>
    </vCard>

    <vCard>
      <h3 class="title is-5 mb-2" style="text-align: center">VERIFIKASI KANTONG & LABEL DARAH DENGAN IDENTITAS PASIEN</h3>
      <p>
        <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%;">
  <thead>
    <tr>
      <th rowspan="2" style="width: 5%; background-color: #FFB6C1; text-align: center;">No</th>
      <th rowspan="2" style="width: 30%; background-color: #FFB6C1; text-align: center;">Yang dinilai</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Gelang Identitas</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Kantong Darah</th>
      <th colspan="2" style="background-color: #FFB6C1; text-align: center;">Label Darah</th>
    </tr>
    <tr>
      <th style="width: 8%;">Sesuai</th>
      <th style="width: 8%;">Tidak</th>
      <th style="width: 8%;">Sesuai</th>
      <th style="width: 8%;">Tidak</th>
      <th style="width: 8%;">Sesuai</th>
      <th style="width: 8%;">Tidak</th>
    </tr>
  </thead>
  <tbody>
  <tr>
    <td>1</td>
    <td>Nama Lengkap</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangSesuai1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangTidak1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongdar1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongtidakdar1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelbener1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelsalah1" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

  <tr>
    <td>2</td>
    <td>Tanggal Lahir</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangSesuai2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangTidak2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>

    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongdar2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongtidakdar2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelbener2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelsalah2" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

  <tr>
    <td>3</td>
    <td>Nomor Rekam Medis</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangSesuai3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangTidak3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongdar3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongtidakdar3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelbener3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelsalah3" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>

  <tr>
    <td>4</td>
    <td>Golongan Darah</td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangSesuai4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.gelangTidak4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongdar4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.kantongtidakdar4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelbener4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
    <td>
      <VControl raw subcontrol>
        <VInput v-model="item.labelsalah4" class="is-primary-focus" placeholder="" />
      </VControl>
    </td>
  </tr>
        </tbody>
      </table>
      <div style="border: 1px solid black; padding: 0px; margin-top: 10px;">
            <VControl raw subcontrol>
                <VTextarea
                    v-model="item.catatan"
                    class="is-primary-focus"
                    placeholder="Masukkan catatan di sini..."
                    rows="4"
                    style="width: 100%;"
                />
            </VControl>
        </div>
      </p>
    </vCard>

    <vCard>
      <h3 class="title is-5 mb-2" style="text-align: center">Yang Melakukan Verifikasi</h3>
      <p>
      <div class="columns is-multiline">
        <div class="column is-4">
          <h1 class="mt-3">Cirebon</h1>
        </div>
        <div class="column is-8">
          <VDatePicker class="pt-3" v-model="item.tglDibuat" color="green" trim-weeks mode="datetime"
            :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }" class="pb-0">
              <VField>
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded_Z" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>
      <div class="columns is-multiline">
        <div class="column is-4" style="text-align: center;">
          <p class="label-ppap">Yang menyatakan</p>
          <TandaTangan :elemenID="'signatureYangMenyatakan'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.pasienYangMenyatakan" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <p class="label-ppap">Saksi 1</p>
          <TandaTangan :elemenID="'signatureSaksi1'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.saksi1" />
              </VControl>
            </VField>
          </div>
        </div>
        <div class="column is-4" style="text-align: center;">
          <p class="label-ppap">Saksi 2</p>
          <TandaTangan :elemenID="'signatureSaksi2'" :width="'180'" :height="'180'" class="dek" />
          <div class="column pl-0 pr-0 pt-5">
            <VField class="pt-3">
              <VControl class="prime-auto">
                <VInput type="text" v-model="item.saksi2" />
              </VControl>
            </VField>
          </div>
        </div>


      </div>
      </p>
    </vCard>
  </Fieldset>

  <div class="column">
    <!-- form emr -->
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


let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string

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
const d_Dokter: any = ref([])
const d_produk: any = ref([])
const d_Tindakan: any = ref([])
const item: any = reactive({
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: '',
  registrasi: {},
  pegawaiOrder: useUserSession().getUser().id,
  selectedMenu: [false]
})
const COLLECTION: any = ref('daftarTransfusiDarah') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({
  details: [{ no: 1 }]
})
const setView = () => {
  useHead({
    title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
  })
  useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
  useViewWrapper().setFullWidth(true)
}

const tambahPersetujuan = () => {
  input.value.details.unshift({
      no : input.value.details.length + 1,
      tglPembuatanBawaPulang : new Date(),
    tglPembuatanObatIGD : new Date(),
    alamatlengkap : props.pasien.alamatlengkap,
    tglregis : props.registrasi.tglregistrasi,
    dokterDPJP : props.registrasi.dokter,
    namapasienKeluarga : props.pasien.namapasien,
    namaPasien : props.pasien.namapasien,
    pasienYangMenyatakan : props.pasien.namapasien,
    jenisKelamin : props.pasien.jeniskelamin,
    tglLahir : props.pasien.tgllahir,
    alamatPasien : props.pasien.alamatlengkap,
  })
}

const hapusPersetujuan = (indexDetail: any) => {
  input.value.details.splice(indexDetail, 1)
}

const loadRiwayat = () => {
  // if (NOREC_EMRPASIEN.value == '') return
  useApi().get(
    `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
      if (response.length) {
        input.value = response[0] //set ke inputan
        if (response[0].ttdsaksi1) {
          H.tandaTangan().set("signatureSaksi1", response[0].ttdsaksi1)
        }
        if (response[0].ttdsaksi2) {
          H.tandaTangan().set("signatureSaksi2", response[0].ttdsaksi2)
        }
        if (response[0].ttdyangMenyatakan) {
          H.tandaTangan().set("signatureYangMenyatakan", response[0].ttdyangMenyatakan)
        }
        if (response[0].ttdDokteryangMenyatakan) {
          H.tandaTangan().set("signatureDokterYangMenyatakan", response[0].ttdDokteryangMenyatakan)
        }
        if (response[0].ttdPasienyangMenyatakan) {
          H.tandaTangan().set("signaturePasienYangMenyatakan", response[0].ttdPasienyangMenyatakan)
        }
        if (response[0].tandaTanganPerawatObatRawatInap) {
          H.tandaTangan().set("signature_1", response[0].tandaTanganPerawatObatRawatInap)
        }
        if (response[0].tandaTanganApotekerObatRawatInap) {
          H.tandaTangan().set("signature_2", response[0].tandaTanganApotekerObatRawatInap)
        }
      }
    })
}

const simpan = () => {
  let ID = input.value.id ? input.value.id : ''

  let object: any = {}

  object = input.value
  object.ttdsaksi1 = H.tandaTangan().get("signatureSaksi1")
  object.ttdsaksi2 = H.tandaTangan().get("signatureSaksi2")
  object.tandaTanganPerawatObatRawatInap = H.tandaTangan().get("signature_1")
  object.tandaTanganApotekerObatRawatInap = H.tandaTangan().get("signature_2")
  object.ttdyangMenyatakan = H.tandaTangan().get("signatureYangMenyatakan")
  object.ttdDokteryangMenyatakan = H.tandaTangan().get("signatureDokterYangMenyatakan")
  object.ttdPasienyangMenyatakan = H.tandaTangan().get("signaturePasienYangMenyatakan")
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

const addNewItemObatRawatInap = () => {
  input.value.detailObatRawatInap.push({
    no: input.value.detailObatRawatInap[input.value.detailObatRawatInap.length - 1].no + 1,
  });
}
const removeItemObatRawatInap = (index: any) => {
  input.value.detailObatRawatInap.splice(index, 1)
}

const fetchPegawai = async (filter: any) => {

  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Pegawai.value = response
  })
}


const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}

const fetchTindakan = (filter: any) => {
  let nama = filter ? `?filterTindakan=${filter.query}` : ''
  useApi().get(`dashboard/data-tindakan${nama}`).then((response) => {
    d_Tindakan.value = response
  })
}

const fetchProduk = async (filter: any) => {
  const response = await useApi().get(
    `/farmasi/dropdown-obat?namaproduk=${filter.query}&limit=10`)
  d_produk.value = response

}

const kembaliKeun = () => {
  window.history.back()
}
const setAutoFill = async () => {
  input.value.details.forEach((element) => {
    element.tglPembuatanBawaPulang = new Date()
    element.tglPembuatanObatIGD = new Date()
    element.alamatlengkap = props.pasien.alamatlengkap
    element.tglregis = props.registrasi.tglregistrasi
    element.dokterDPJP = props.registrasi.dokter
    element.namapasienKeluarga = props.pasien.namapasien
    element.namaPasien = props.pasien.namapasien
    element.pasienYangMenyatakan = props.pasien.namapasien
    element.jenisKelamin = props.pasien.jeniskelamin
    element.tglLahir = props.pasien.tgllahir
    element.alamatPasien = props.pasien.alamatlengkap
  });
}
setView()
setAutoFill()
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
