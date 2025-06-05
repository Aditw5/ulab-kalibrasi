

<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked">

    <div class="business-dashboard hr-dashboard">
      <div class="columns is-multiline">
        <div class="column is-12" v-if="isLoadingPasien">
          <div class="block-green">
            <div class="flex-list-inner mb-4">
              <div class="flex-table-item grid-item mb-4" v-for="key in 1" :key="key">
                <VFlexTableCell :column="{ grow: true, media: true }">
                  <VPlaceloadAvatar size="medium" />
                  <VPlaceloadText :lines="2" width="30%" last-line-width="20%" class="mx-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="100%" height="70px" class="mx-1 mt-2" />
                </VFlexTableCell>
                <VFlexTableCell>
                  <VPlaceload width="10%" height="20px" class="mx-1 mt-1" />
                </VFlexTableCell>
                <VFlexTableCell :column="{ align: 'end' }">
                  <VPlaceload width="10%" class="mx-1" />
                </VFlexTableCell>
              </div>
            </div>
          </div>
        </div>
        <div class="column is-12" v-if="!isLoadingPasien">
          <div class="block-header">
            <div class="left">
              <div class="current-user">
                <VAvatar size="medium"
                  :picture="pasien.jeniskelamin ==
                    'PEREMPUAN' ? '/images/avatars/svg/vuero-4.svg' : '/images/avatars/svg/vuero-1.svg'" squared />
                <h3>{{ pasien.namapasien }}</h3>
              </div>
            </div>
            <div class="center">
              <div class="columns">
                <div class="column">
                  <h4 class="block-heading">No RM</h4>
                  <p class="block-text"> {{ pasien.nocm }}</p>
                  <h4 class="block-heading">Tgl Lahir </h4>
                  <p class="block-text"> {{ pasien.tgllahir }}</p>
                </div>
                <div class="column">
                  <h4 class="block-heading">NIK </h4>
                  <p class="block-text"> {{ pasien.noidentitas }}</p>
                  <h4 class="block-heading">Kelamin</h4>
                  <p class="block-text"> {{ pasien.jeniskelamin }}</p>
                </div>
              </div>
            </div>
            <div class="right">
              <div class="columns">
                <div class="column">
                  <h4 class="block-heading">No HP</h4>
                  <p class="block-text">{{ pasien.nohp }}</p>
                  <h4 class="block-heading">Alamat</h4>
                  <p class="block-text">{{ pasien.alamatlengkap }}
                  </p>
                </div>
                <div class="column">
                  <h4 class="block-heading">Umur</h4>
                  <VTag color="purple" :label="pasien.umur" />
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="column is-12" >
                     <InfoPasien></InfoPasien>
                </div> -->
      </div>
    </div>

    <div class="form-outer" style="margin-top:15px">
      <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
        <div class="form-header-inner">
          <div class="left">
            <h3>
              {{ NOREC_PD != undefined ? 'Edit Registrasi' : 'Registrasi' }}
              <VTag
              v-if="pasien.isBelumSatuBulan"
              color="warning"
              label="Belum Ada Satu Bulan"
              size="medium"
              rounded
            />
          </h3>
          </div>
          <div class="right">
            <div class="buttons">
              <VDropdown icon="feather:more-vertical" spaced right v-if="item.NOREC_PD" class="mt-1-min mr-2"
                v-tooltip.bubble="'CETAK'">
                <template #content>

                  <!-- <hr class="dropdown-divider" /> -->
                  <a role="menuitem" @click="cetakSEP(item)" class="dropdown-item is-media">
                    <div class="icon">
                      <i aria-hidden="true" class="lnil lnil-printer"></i>
                    </div>
                    <div class="meta">
                      <span>Cetak SEP</span>
                      <span>Cetak Surat Elegibilitas </span>
                    </div>
                  </a>
                  <a @click="cetakBuktiPendaftaran(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Poli Antrian</span>
                      <span>Cetak Nomor</span>
                    </div>
                  </a>
                  <a @click="cetakLabel(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Label Pasien</span>
                      <span>Cetak Label</span>
                    </div>
                  </a>
                  <a @click="cetakkartuPasien(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Kartu Pasien</span>
                      <span>Cetak Kartu</span>
                    </div>
                  </a>
                  <a @click="cetakGelangPasien(item)" role="menuitem" class="dropdown-item is-media">
                    <div class="icon">
                      <i class="fas fa-print" aria-hidden="true"></i>
                    </div>
                    <div class="meta">
                      <span>Gelang Pasien</span>
                      <span>Cetak Gelang </span>
                    </div>
                  </a>
                </template>
              </VDropdown>
              <VButton v-if="item.NOREC_PD && item.kelompokpasien != item.idKelompokPasienUMUM"
                icon="lnir lnir-plus rem-100" light color="info" outlined @click="asuransi()">
                Asuransi
              </VButton>
              <RouterLink :to="{ name: 'module-registrasi-pasien-lama', }" v-if="item.NOREC_PD">
                <VIconButton class="mr-5 is-pulled-right" type="button" color="info" rounded circle raised
                  icon="fas fa-users" v-tooltip.bubble="'Pasien Lama'">
                </VIconButton>
              </RouterLink>
              <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                Batal
              </VButton>
              <VButton type="button" color="primary" rounded outlined raised icon="feather:save" :disabled="isDisabled" :loading="isLoading"
                @click="checkIsExsist()"> Simpan </VButton>

            </div>
          </div>
        </div>
      </div>

      <div class="form-body">

        <div class="form-section is-grey">
          <div class="form-section-header">
            <div class="left">
              <!-- <h3>Detail</h3> -->
            </div>
            <div class="right">

            </div>
          </div>

          <div class="form-section-inner is-horizontal">
            <VField horizontal label=" Tanggal">
              <VDatePicker v-model="item.tglregistrasi" mode="dateTime" style="width: 100%;">
                <template #default="{ inputValue, inputEvents }">
                  <VField>
                    <VControl icon="feather:calendar" fullwidth>
                      <VInput :value="inputValue" v-on="inputEvents" />
                    </VControl>
                  </VField>
                </template>
              </VDatePicker>
            </VField>

            <VField horizontal label="&nbsp;">
              <VControl>
                <VSwitchBlock v-model="item.isRawatInap" label="Rawat Inap" color="danger"
                  @change="changeSwitch(item.isRawatInap)" />

              </VControl>
              <VControl>
                <VRadio v-model="item.isRawatGabung" :value="'rawatgabung'" label="Rawat Gabung" name="isRawatGabung"
                  color="primary" id="isRawatGabung" style="margin-top: 5px; margin-left: 30px; font-size: 0.9rem;"
                  v-if="item.isRawatInap" />

              </VControl>
            </VField>
            <VField v-if="item.isRawatInap" horizontal label="&nbsp;">
              <VControl>
                <VCheckbox v-model="item.isRencanaBPJS" label="Rencana BPJS"
                  color="primary" style="font-size: 0.9rem;"
                />
              </VControl>
            </VField>
            <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:home" fullwidth>
                <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" @select="changeRuang(item.ruangan)" />
              </VControl>
            </VField>
            <!-- <VField horizontal label="NO SITB" v-if="item.ruangan == 155">
              <VControl icon="feather:bookmark" fullwidth>
                <VInput type="text" v-model="item.sitb" placeholder="No SITB"
                  class="is-rounded_Z" />
              </VControl>
            </VField> -->
            <VField v-if="item.isRawatInap" horizontal label="Kelas Rawat"
              class="is-rounded-select_Z  is-autocomplete-select">
              <VField v-slot="{ id }">
                <VControl icon="feather:layers" fullwidth>
                  <Multiselect mode="single" v-model="item.kelasRawat" :options="d_Kelas" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                    @select="changeKelas(item.kelasRawat)" />
                </VControl>
              </VField>
              <VField v-slot="{ id }" v-if="item.kelasRawat" horizontal label="Kelas Ditanggung">
                <VControl icon="feather:layers" fullwidth>
                  <Multiselect mode="single" v-model="item.kelas" :options="d_KelasDefault"
                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                    :loading="isLoadingTT" />
                </VControl>
              </VField>
            </VField>
            <VField v-if="item.isRawatInap && item.kelasRawat" horizontal label="Kamar"
              class="is-rounded-select_Z  is-autocomplete-select">
              <VField v-slot="{ id }">
                <VControl icon="fas fa-hospital-alt">
                  <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT"
                    @select="changeKamar(item.kamar)" />
                </VControl>
              </VField>
              <VField v-slot="{ id }" subcontrol v-if="item.kamar">
                <VControl icon="fas fa-bed">
                  <Multiselect mode="single" v-model="item.bed" :options="d_TempatTidur" placeholder="Pilih data"
                    :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
                </VControl>
              </VField>
            </VField>

            <div class="columns is-mulitline p-3">
              <div class="column is-2 pt-2 pb-0 pr-1">
                <div class="pt-1" style="display: flex;justify-content: end;">
                  <label
                    style="font-family: var(--font);font-size: 0.9rem;color: var(--light-text) !important;font-weight: 400;">Asal
                    Rujukan</label>
                </div>

              </div>
              <div class="column ml-2 pt-1 pb-0 pr-0"
                v-if="item.asalrujukan != 5 || item.asalrujukan != null ? 'is-10' : 'is-4 pr-2'">
                <VField horizontal class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" required-field>
                  <VControl icon="feather:git-merge" fullwidth>
                    <Multiselect mode="single" v-model="item.asalrujukan" :options="d_AsalRujukan"
                      placeholder="Pilih data" :searchable="true" autocomplete="off" :attrs="{ id }" track-by="value" />
                  </VControl>
                </VField>
              </div>

              <div class="column ml-2 p-0 mt-1" v-if="item.asalrujukan != 5">
                <VField>
                  <VControl icon="feather:bookmark">
                    <VInput type="text" v-model="item.keteranganasalrujukan" placeholder="Asal Rujukan lebih jelas"
                      class="is-rounded_Z" />
                  </VControl>
                </VField>
              </div>

              <!-- <div class="column p-0">
                                <VField horizontal label="Asal Rujukan" class="is-rounded-select_Z  is-autocomplete-select"
                                    v-slot="{ id }" required-field>
                                    <VControl icon="feather:git-merge" fullwidth>
                                        <Multiselect mode="single" v-model="item.asalrujukan" :options="d_AsalRujukan"
                                            placeholder="Pilih data" :searchable="true" autocomplete="off" :attrs="{ id }"
                                            track-by="value" />
                                    </VControl>
                                </VField>
                            </div> -->
            </div>

            <VField horizontal label="Pembiayaan" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="fas fa-calculator" fullwidth>
                <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien"
                  placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                  @select="changeKelompok(item.kelompokpasien)" />
              </VControl>
            </VField>
            <VField v-if="item.kelompokpasien" horizontal label="Penjamin"
              class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:command" fullwidth>
                <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan" placeholder="Pilih data"
                  :searchable="true" :attrs="{ id }" autocomplete="off" :loading="isLoadingTT" />
              </VControl>
            </VField>
            <VField v-if="item.kelompokpasien == 1" horizontal label="Nomor Antrian"
              class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="feather:command" fullwidth>
                <VInput type="text"  v-model="item.noantrian" placeholder="Angkanya saja"
                class="is-rounded_Z" />
              </VControl>
            </VField>
            <VField horizontal label="Tipe Layanan">
              <VControl>
                <VRadio v-model="item.jenispelayanan" v-for="items of d_JenisPelayanan" :key="items.id" :value="items.id"
                  :label="items.jenispelayanan" name="{{items.id}}" color="primary" />
                <!-- <VRadio v-model="item.jenispelayanan" value="Eksekutif" label="Eksekutif" color="primary" /> -->
              </VControl>

            </VField>

            <VField horizontal label="Dokter" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
              <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                <!-- <Multiselect mode="single" v-model="item.dokter" placeholder="Pilih data" :searchable="true"
                                    :filter-results="false" :min-chars="0" :resolve-on-load="false" :delay="0" :options="async function (query: any) {
                                                                                                    return await fetchDokter(query)
                                                                                                }" autocomplete="off" /> -->
                <AutoComplete v-model="item.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
                  :optionLabel="'namalengkap'" :dropdown="true" :minLength="3" :appendTo="'body'"
                  :loadingIcon="'pi pi-spinner'" :field="'namalengkap'" placeholder="ketik nama Dokter" />


              </VControl>
            </VField>
            <!-- <VField v-if="item.isRawatInap" horizontal label="Dokter Rawat Bersama"
                            class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="fa:user-md" fullwidth class="prime-auto ">
                                <AutoComplete v-model="item.dokterRawatBersama" :suggestions="d_Dokter"
                                    @complete="fetchDokter($event)" :optionLabel="'namalengkap'" :dropdown="true"
                                    :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'namalengkap'"
                                    placeholder="ketik nama Dokter" />


                            </VControl>
                        </VField> -->
            <VField horizontal label="Catatan">
              <VControl fullwidth>
                <VTextarea class="textarea" v-model="item.catatan" rows="4"
                  placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                  spellcheck="true" />

              </VControl>
            </VField>
          </div>
        </div>
        <div class="form-section is-grey">
          <div class="form-section-inner is-horizontal">


          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- <VModal :open="modalAsuransi" title="Surat Eligibilitas Peserta" :noclose="false" size="big" actions="right"
    @close="modalAsuransi = false">
    <template #content>
        <PemakaianAsuransi></PemakaianAsuransi>
    </template>
    <template #action>
      <VButton icon="feather:save" @click="saveTglPelayanan()" :loading="isLoadingPop" color="primary" raised>Simpan
      </VButton>
                                                                                        </template>
                                                                                      </VModal> -->
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useToaster } from '/@src/composable/toaster'
import { useHead } from '@vueuse/head'
import ConfirmDialog from 'primevue/confirmdialog'
import { useConfirm } from 'primevue/useconfirm'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import InfoPasien from '/@src/pages/include/info-pasien.vue';
import * as qzService from '/@src/utils/qzTrayService'

useHead({
  title: 'Registrasi - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string
let NOREC_RESERVASI = useRoute().query.norec_online as string
let STATUSPASIEN = useRoute().query.statuspasien as string
let NOREGISTRASI = "";
let RESERVASI = {
  'norec_online': useRoute().query.norec_online as string,
  'ruangan': useRoute().query.ruangan as string,
  'dokter': useRoute().query.dokter as string,
  'dokter_name': useRoute().query.dokter_name as string,
  'kelompok': useRoute().query.kelompok as string,
  'tanggalreservasi': useRoute().query.tanggalreservasi as string,
}
const item: any = reactive({
  tglregistrasi: new Date(),
  NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
  NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
  NOREGISTRASI: NOREGISTRASI != undefined ? NOREGISTRASI : '',
  idKelompokPasienBPJS: [],
  idKelompokPasienUMUM: null
})
const confirm = useConfirm();
const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_RuanganRI: any = ref([])
const d_RuanganRJ: any = ref([])
const d_AsalRujukan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_JenisPelayanan: any = ref([])
const d_Rekanan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_KelasDefault: any = ref([])
const d_Kamar: any = ref([])
const d_TempatTidur: any = ref([])
const isLoading: any = ref(false)
const isDisabled: any = ref(false)
const isLoadingTT: any = ref(false)
const isLoadingPasien: any = ref(false)
const modalAsuransi: any = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
  return y.value > 30
})

const pasienByID = (id: any) => {
  isLoadingPasien.value = true
  let paramsEdit = ''
  if (item.NOREC_PD != '' && item.NOREC_APD != '') {
    paramsEdit = `&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`
  }
  useApi().get(
    `/registrasi/pasien-registrasi?id=${id}${paramsEdit}`).then((response: any) => {
      pasien.value = response.pasien
      d_KelasDefault.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
      d_RuanganRJ.value = response.ruangan_RJ.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
      d_RuanganRI.value = response.ruangan_RI.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
      d_AsalRujukan.value = response.asalrujukan.map((e: any) => { return { label: e.asalrujukan, value: e.id, default: e } })
      d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
      // d_Dokter.value = response.dokter.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
      d_JenisPelayanan.value = response.jenispelayanan
      item.idKelompokPasienBPJS = response.idKelompokPasienBPJS
      item.idKelompokPasienUMUM = response.idKelompokPasienUMUM
      item.jenispelayanan = response.jenispelayanan[0].id
      for (let x = 0; x < response.asalrujukan.length; x++) {
        const element = response.asalrujukan[x];
        if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
          item.asalrujukan = element.id
          break
        }
      }
      if (response.registrasi == null) {
        d_Ruangan.value = d_RuanganRJ.value
        if (NOREC_RESERVASI != undefined) {
          setFromReservasi(RESERVASI)
        }
      } else {
        let regis = response.registrasi
        if (ID_PASIEN == regis.nocmfk) {
          setFromRegistrasi(regis)
        }
        item.NOREGISTRASI = response.registrasi.noregistrasi
      }
      isLoadingPasien.value = false
    })

}
const setFromReservasi = (e: any) => {
  item.tglregistrasi = new Date(e.tanggalreservasi)
  item.ruangan = e.ruangan
  item.kelompokpasien = e.kelompok
  item.noreservasi = e.noreservasi
  changeKelompok(item.kelompokpasien)
  item.dokter = { id: e.dokter, namalengkap: e.dokter_name }
}
const setFromRegistrasi = async (regis: any) => {
  d_Ruangan.value = d_RuanganRJ.value
  item.isRawatInap = regis.israwatinap
  if (regis.israwatinap == true) {
    d_Ruangan.value = d_RuanganRI.value
  }
  item.ruangan = regis.objectruanganlastfk
  await changeRuang(item.ruangan)
  item.tglregistrasi = new Date(regis.tglregistrasi)
  item.asalrujukan = regis.asalrujukanfk
  item.kelompokpasien = regis.objectkelompokpasienlastfk
  changeKelompok(item.kelompokpasien)
  item.rekanan = regis.objectrekananfk
  item.jenispelayanan = regis.jenispelayananfk
  if (regis.objectpegawaifk != null)
  item.dokter = { id: regis.objectpegawaifk, namalengkap: regis.dokter }
  item.kelasRawat = regis.objectkelasrawatfk
  item.kelas =regis.objectkelasfk
  await changeKelas(item.kelasRawat)
  item.catatan = regis.catatan
  item.statuspasien = regis.statuspasien
  item.kamar = regis.objectkamarfk
  await changeKamar(item.kamar)
  if (d_TempatTidur.value.length) {
    d_TempatTidur.value.push({
      "label": regis.tempattidur,
      "value": regis.nobed,
      "default": {
        "id": regis.nobed,
        "reportdisplay": regis.tempattidur,
        "nomorbed": regis.nomorbed,
        "objectkamarfk": item.kamar
      }
    })
  } else {
    d_TempatTidur.value = [{
      "label": regis.tempattidur,
      "value": regis.nobed,
      "default": {
        "id": regis.nobed,
        "reportdisplay": regis.tempattidur,
        "nomorbed": regis.nomorbed,
        "objectkamarfk": item.kamar
      }
    }]
  }
  item.bed = regis.nobed
}
const changeSwitch = (e: any) => {
  delete item.ruangan
  d_Ruangan.value = []
  if (e == true) { d_Ruangan.value = d_RuanganRI.value } else { d_Ruangan.value = d_RuanganRJ.value }
}
const changeRuang = (e: any) => {
  item.kelas = null
  item.kelasRawat = null
  if (e) {
    if (item.isRawatInap == true) {
      setKelas(e)
    }
  }
}
const setKelas = (e: any) => {
  isLoadingTT.value = true
  d_Kelas.value = []
  useApi().get(
    `/registrasi/kelas-by-ruangan?id=${e}`)
    .then((response: any) => {
      isLoadingTT.value = false
      if (response.length == 1) {
        item.kelas = response[0].id
      }
      d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
    })
    .catch((error: any) => { isLoadingTT.value = false })
}
const changeKelas = async (e: any) => {
  let isEdit = NOREC_PD ? true : false
  d_Kamar.value = []
  delete item.kamar
  if (e && item.ruangan) {
    isLoadingTT.value = true
    await useApi().get(
      `/registrasi/kamar-by-kelas?id=${e}&idRuangan=${item.ruangan}&isRG=false&isEdit=${isEdit}`)
      .then((response: any) => {
        isLoadingTT.value = false
        d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
      })
      .catch((error: any) => { isLoadingTT.value = false })
  }

}
const changeKamar = async (e: any) => {
  d_TempatTidur.value = []
  if (e) {
    for (let x = 0; x < d_Kamar.value.length; x++) {
      const element = d_Kamar.value[x];
      if (element.value == e) {
        d_TempatTidur.value = await element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
      }
    }
  }
}
const cancelRegistrasi = () => {
  window.history.back()
}

const changeKelompok = async (e: any) => {
  d_Rekanan.value = []
  for (let x = 0; x < item.idKelompokPasienBPJS.length; x++) {
    const element = item.idKelompokPasienBPJS[x];
    if (e == element) {
      if (pasien.value.nobpjs == null || pasien.value.nobpjs == '') {
        H.alert('warning', 'NO BPJS anda masih kosong, harap lengkapi data')
      } else if (pasien.value.nobpjs.length != 13) {
        H.alert('warning', 'NO BPJS anda tidak sesuai, harap sesuaikan data ')
      }
    }
  }

  delete item.rekanan
  if (e) {
    isLoadingTT.value = true
    await useApi().get(
      `/registrasi/penjamin-by-kelompokpasien?id=${e}`)
      .then((response: any) => {
        isLoadingTT.value = false
        if (response.length > 0) {
          d_Rekanan.value = response.map((e: any) => { return { label: e.namarekanan, value: e.id, default: e } })
          if (response.length == 1) {
            item.rekanan = response[0].id
          } else {
            if (item.idKelompokPasienBPJS == e) {
              for (let z = 0; z < d_Rekanan.value.length; z++) {
                const element = d_Rekanan.value[z];
                if (element.label.toLowerCase().indexOf('kesehatan') > -1) {
                  item.rekanan = element.value
                  break
                }
              }
            }
            if(e == 1){
              item.rekanan = response[0].id
            }
          }
        }
      })
      .catch((error: any) => { isLoadingTT.value = false })
  }

}

const checkSurkon = async (netxmont = 1) => {
  let bulan: any = new Date().getMonth() + netxmont
  let nokartu: any = pasien.value.nobpjs || "0000000000000"
  let result: any = {}

  bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan

  let json = {
    "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${new Date().getFullYear()}/Nokartu/${pasien.value.nobpjs}/filter/${2}`,
    "method": "GET",
    "data": null
  }
  try {
    const x = await useApi().postBPJS('/bridging/bpjs/tools', json);

    if (x.metaData.code === 200) {
      result = {
        "status": true,
        "data": x.response.list
      };
    } else {
      if (netxmont === 1) {
        // If not successful, recursively call and return its result
        return await checkSurkon(netxmont + 1);
      } else {
        result = {
          "status": false,
          "data": []
        };
      }
    }
  } catch (error) {
    console.error('Error fetching data:', error);
    result = {
      "status": false,
      "data": []
    };
  }

  // Return the final result
  return result;
}

const checkIsExsist = async () => {
  isLoading.value = true
  if (!item.NOREC_PD) {
    let respon = await useApi().get(`registrasi/pasien-hari-ini?nocmfk=${ID_PASIEN}`)
    isLoading.value = false
    if (respon != null) {
      confirm.require({
        message: `Pasien Sudah teregistrasi hari ini di ${respon.namaruangan}, Lanjutkan registrasi ?`,
        header: 'Konfirmasi Registrasi Pasien',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: async() => {
          await checkIsLessOneMonth()
        },
        reject: () => { },
      })
    } else {
      await checkIsLessOneMonth()
    }
  } else {
    isLoading.value = false
    await checkIsLessOneMonth()
  }
}
const checkIsLessOneMonth = async () => {
  if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
  isLoading.value = true
  let response = await useApi().get(`registrasi/periode-bulanan?nocmfk=${ID_PASIEN}&ruangan=${item.ruangan}`)
  if (response && response.isBelumSatuBulan) {
    isLoading.value = false
    confirm.require({
      message: `Pasien daftar belum lewat satu bulan, tetap daftarkan ?`,
      header: 'Konfirmasi Registrasi Pasien',
      icon: 'pi pi-info-circle',
      acceptClass: 'p-button-danger',
      accept: async() => {
        await saveRegistrasi()
      },
      reject: () => { },
    })
  } else {
    isLoading.value = false
    await saveRegistrasi()
  }
  isLoading.value = false
}

const saveRegistrasi = async () => {

  if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }
  if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
  if (!item.asalrujukan) { H.alert('warning', 'Asal Rujukan  harus di isi'); return }
  if (!item.kelompokpasien) { H.alert('warning', 'Pembiayaan harus di isi'); return }
  if (!item.jenispelayanan) { H.alert('warning', 'Tipe Layanan harus di isi'); return }
  if (item.isRawatInap == true) {
    if (!item.kelasRawat) { H.alert('warning', 'Kelas Dirawat harus di isi'); return }
    if (!item.kelas) { H.alert('warning', 'Kelas Ditanggung harus di isi'); return }
    if (!item.kamar) { H.alert('warning', 'Kamar harus di isi'); return }
    if (!item.bed) { H.alert('warning', 'Bed harus di isi'); return }
  }

  if (item.isRawatInap != true && (item.kelompokpasien == 2 || item.kelompokpasien == 18 || item.kelompokpasien == 4)) {
    let cekSurkon = await checkSurkon()
    if(cekSurkon.status) {
      let status = false
      let tglregis = H.formatDate(item.tglregistrasi, 'YYYY-MM-DD')
      let tglkontrol = ""
      for (let i = 0; i < cekSurkon.data.length; i++) {
        const element = cekSurkon.data[i];
        if(element.terbitSEP == "Belum" && element.tglRencanaKontrol > tglregis){
          status = true
          tglkontrol = element.tglRencanaKontrol
        }
      }

      if(status) {
        confirm.require({
          message: `Pasien dijadwalkan untuk kontrol di tanggal ${tglkontrol}, Apakah ingin melanjutkan registrasi ?`,
          header: 'Konfirmasi Registrasi Pasien',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          accept: async() => {
            lanjutsaveRegistrasi()
          },
          reject: () => { },
        })
      } else {
        lanjutsaveRegistrasi()
      }
    } else {
      lanjutsaveRegistrasi()
    }
  } else {
    lanjutsaveRegistrasi()
  }
}
const lanjutsaveRegistrasi = async () => {
  // if (item.ruangan == 155) {
  //   if (!item.sitb) { H.alert('warning', 'No SITB harus di isi'); return }
  // }
  let json = {
    'pasiendaftar': {
      'norec': item.NOREC_PD ? item.NOREC_PD : '',
      'nocmfk': ID_PASIEN,
      'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
      // 'sitb_id': item.sitb ? item.sitb : null,
      'objectruanganlastfk': item.ruangan,
      'asalrujukanfk': item.asalrujukan,
      'keteranganasalrujukan': item.keteranganasalrujukan ? item.keteranganasalrujukan : null,
      'objectkelompokpasienlastfk': item.kelompokpasien,
      'jenispelayananfk': item.jenispelayanan,
      'objectpegawaifk': item.dokter ? item.dokter.id : null,
      'objectpegawairawatbersamafk': item.dokterRawatBersama ? item.dokterRawatBersama.id : null,
      'objectkelasfk': item.kelas ? item.kelas : null,
      'objectkelasrawatfk': item.kelasRawat ? item.kelasRawat : null,
      'israwatinap': item.isRawatInap ? item.isRawatInap : false,
      'catatan': item.catatan ? item.catatan : null,
      'statuspasien': STATUSPASIEN ? STATUSPASIEN : 'LAMA',//item.statuspasien ? item.statuspasien : 'LAMA',
      'objectrekananfk': item.rekanan != undefined ? item.rekanan : null,
      'nocm': pasien.value.nocm,
      'namapasien': pasien.value.namapasien,
      'antrianpasienregistrasifk': NOREC_RESERVASI ? NOREC_RESERVASI : null,
      'isRencanaBPJS': item.isRencanaBPJS ? 'rencana-bpjs' : null,
    },
    'antrianpasiendiperiksa': {
      'norec': item.NOREC_APD ? item.NOREC_APD : '',
      'objectkamarfk': item.kamar ? item.kamar : null,
      'nobed': item.bed ? item.bed : null,
      'israwatgabung': item.isRawatGabung == 'rawatgabung' ? true : null,
      'noantrian' : item.noantrian ?? null,
    }
  }
  if(item.kelompokpasien == 2 || item.kelompokpasien == 18 || item.kelompokpasien == 4){
    const cekSEPTerbit = await useApi().get(`/registrasi/cek-sep?id=${ID_PASIEN}`)
    if(cekSEPTerbit != null){
      useToaster().error(`SEP Sudah terbit pada hari ini dengan nomor registrasi ${cekSEPTerbit.noregistrasi} dan no SEP ${cekSEPTerbit.nosep} di ${cekSEPTerbit.namaruangan}`)
      // return
    }
  }
  isLoading.value = true
  await useApi().post(`/registrasi/save-registrasi`, json).then((response: any) => {
    isLoading.value = false
    isDisabled.value = true
    if (!item.NOREC_PD) {
      saveAdminAuto(response.registrasi.pd.norec, response.registrasi.apd.norec)
    }
    // if( item.isRawatInap == true){
    //   let jsonKamar = {idruangan : item.ruangan, idkelas : item.kelas}
    //   useApi().postNoMessage(`/bridging/bpjs/update-kamar`, jsonKamar).then((response: any) => {})

    // }
    if (response.registrasi.pd.noregistrasi != undefined && response.registrasi.pd.noregistrasi != null) {
      const jsonEncounter = {
        noregistrasi: response.registrasi.pd.noregistrasi
      }

      useApi().postNoMessage('/bridging/satusehat/Encounter', jsonEncounter).then((resp:any) => {  })
    }
    item.NOREC_PD = response.registrasi.pd.norec
    item.NOREC_APD = response.registrasi.apd.norec
    item.NOREGISTRASI = response.registrasi.apd.noregistrasi
    for (let xx = 0; xx < item.idKelompokPasienBPJS.length; xx++) {
      const element = item.idKelompokPasienBPJS[xx];
      if (element == item.kelompokpasien) {
        tambahAsuransi(item.NOREC_PD)
      }
    }
    // cetakTracerMedik(response.registrasi.apd.noregistrasi,response.registrasi.nocm);
  }).catch((e: any) => {
    isLoading.value = false

    if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
        changeKelas(item.kelasRawat)
    }
    console.clear()
    console.log(e)
  })
  isLoading.value = false
}

// const checkAsalRujuk = (e:any)=>{
//     item.asalrujukan = e
//     console.log(item.asalrujukan)
// }

const fetchDokter = async (filter: any) => {
  if (!filter.query) {

  }

  const response = await useApi().get(
    `/registrasi/dokter-paging?name=${filter.query}&limit=10`)
  d_Dokter.value = response.dokter
  // return response.dokter.map((item: any) => {
  //     return { value: item.id, label: item.namalengkap, default: item }
  // })
}
const tambahAsuransi = (norec_pd: any) => {
  router.push({
    name: 'module-registrasi-pemakaian-asuransi',
    query: {
      norec_pd: norec_pd,
      nocmfk: pasien.value.nocmfk,
      //   norec_apd: norec_apd
    }
  })
  // modalAsuransi.value = true
}
const asuransi = () => {
  router.push({
    name: 'module-registrasi-pemakaian-asuransi',
    query: {
      norec_pd: item.NOREC_PD,
      nocmfk: pasien.value.nocmfk,
    }
  })
}
const cetakSEP = (e: any) => {
  H.printBlade('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true");
  // qzService.printData('registrasi/pemakaian-asuransi/sep?norec_pd=' + e.NOREC_PD + "&pdf=true", 'SEP', 1);
}

const cetakLabel = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-label-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}`, 'LABEL PASIEN', 1);
}

const cetakkartuPasien = async (e: any) => {
  qzService.printData(`dashboard/registrasi/cetak-kartu-pasien?pdf=true&norec_pd=${e.NOREC_PD}`, 'KARTU PASIEN', 1);
}

const cetakBuktiPendaftaran = (e: any) => {
  qzService.printData(`report/bukti-pendaftaran?pdf=true&noregistrasi=${e.NOREC_PD}&norec_pd=${e.NOREC_PD}`, 'BUKTI PENDAFATARN', 1);
}
const cetakGelangPasien = (e: any) => {
  qzService.printData(`report/cetak-gelang-pasien?pdf=true&noregistrasi=${e.NOREGISTRASI}`,
    'GELANG PASIEN', 1)
}
const cetakTracerMedik = (noreg: any, nocm: any) => {
  if (parseInt(nocm) % 2 === 0) {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GENAP', 1)
  } else {
    qzService.printData(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`, 'TRACER GANJIL', 1)
  }
  //  H.printBlade(`registrasi/cetak-tracer?pdf=true&noregistrasi=${noreg}`);
}
const saveAdminAuto = (norec_pd: any, norec_apd: any) => {
  let json = {
    norec: norec_pd,
    norec_apd: norec_apd
  }
  useApi().postNoMessage(
    `/registrasi/save-adminsitrasi`, json).then((response: any) => { })
}
const clearInput = () => {
  delete item.kelasRawat
}

const setAutoFill = async () => {
    try {
        const response = await useApi().get('kasir/autofill-pasien-sitb?id_pasien=' + ID_PASIEN);
        let sitb_id = ''
        if (response.sitb_pasien) {
            sitb_id = response.sitb_pasien;
            item.sitb = sitb_id || '';
        } else {
            item.sitb = '';
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        item.sitb = ''
    }
};


pasienByID(ID_PASIEN)
setAutoFill()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';

// .dropdown.is-dots .is-trigger {
//     background: var(--red) !important;
// }
// .dropdown.is-dots .is-trigger svg {
//     color: white;
// }
</style>
