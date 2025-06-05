

<template>
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
                                    <h4 class="block-heading" style="margin-top:12px;">Hak Kelas</h4>
                                    <VTag color="danger" :label="pasien.kelasrawat" />
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
                        <h3>{{ NOREC_PD != undefined ? 'Mutasi Pasien' : 'Mutasi Pasien' }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton v-if="item.NOREC_PD && item.kelompokpasien != item.idKelompokPasienUMUM"
                                icon="lnir lnir-plus rem-100" light dark-outlined @click="asuransi()">
                                Asuransi
                            </VButton>
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="cancelRegistrasi()">
                                Batal
                            </VButton>
                            <VButton type="button" color="primary" rounded outlined raised icon="feather:save"
                                :loading="isLoading" @click="saveMutasi()"> Simpan </VButton>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-body">

                <div class="form-section is-grey">
                    <div class="form-section-header">
                        <div class="left">
                          <VTag v-if="item.ruangannextschedule != null" class="my-3 mx-5" :label="item.ruangannextschedule+'(Pesanan)'"
                                color="green" rounded size="medium"/>
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
                                <VSwitchBlock v-model="item.israwatgabung" label="Rawat Gabung" color="danger"
                                    />
                            </VControl>
                        </VField>
                        <VField horizontal label="&nbsp;">
                            <VControl>
                                <VSwitchBlock v-model="item.isrencanaBPJS" label="Rencana BPJS" color="primary"
                                    />
                            </VControl>
                        </VField>
                        <VField horizontal label="Ruangan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="feather:home" fullwidth>
                                <Multiselect mode="single" v-model="item.ruangan" :options="d_Ruangan"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    @select="changeRuang(item.ruangan)" />
                            </VControl>
                        </VField>
                        <VField horizontal label="Kelas Rawat"
                            class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
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
                        <VField v-if="item.kelas" horizontal label="Kamar"
                            class="is-rounded-select_Z  is-autocomplete-select">
                            <VField v-slot="{ id }">
                                <VControl icon="fas fa-hospital-alt">
                                    <Multiselect mode="single" v-model="item.kamar" :options="d_Kamar"
                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                        :loading="isLoadingTT" @select="changeKamar(item.kamar)" />
                                </VControl>
                            </VField>
                            <VField v-slot="{ id }" subcontrol v-if="item.kamar">
                                <VControl icon="fas fa-bed">
                                    <Multiselect mode="single" v-model="item.bed" :options="d_TempatTidur"
                                        placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                        :loading="isLoadingTT" />
                                </VControl>
                            </VField>
                        </VField>

                        <VField horizontal label="Asal Rujukan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="feather:git-merge" fullwidth>
                                <Multiselect mode="single" v-model="item.asalrujukan" :options="d_AsalRujukan"
                                    placeholder="Pilih data" :searchable="true" autocomplete="off" :attrs="{ id }"
                                    track-by="value" />
                            </VControl>
                        </VField>
                        <VField horizontal label="Pembiayaan" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
                            <VControl icon="fas fa-calculator" fullwidth>
                                <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    @select="changeKelompok(item.kelompokpasien)" />
                            </VControl>
                        </VField>
                        <VField v-if="item.kelompokpasien" horizontal label="Penjamin"
                            class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:command" fullwidth>
                                <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    :loading="isLoadingTT" />
                            </VControl>
                        </VField>
                        <VField horizontal label="Tipe Layanan">
                            <VControl>
                                <VRadio v-model="item.jenispelayanan" v-for="items of d_JenisPelayanan" :key="items.id"
                                    :value="items.id" :label="items.jenispelayanan" name="{{items.id}}" color="primary" />
                                <!-- <VRadio v-model="item.jenispelayanan" value="Eksekutif" label="Eksekutif" color="primary" /> -->
                            </VControl>

                        </VField>

                        <VField horizontal label="Dokter" class="is-rounded-select_Z  is-autocomplete-select"
                            v-slot="{ id }">
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
                        <VField horizontal label="Catatan">
                            <VControl fullwidth>
                                <VTextarea class="textarea" v-model="item.catatan" rows="4"
                                    placeholder="catatan registrasi (optional) ..." autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />

                            </VControl>
                        </VField>
                        <VField horizontal label="&nbsp;">
                            <VControl>
                                <VSwitchBlock v-model="isInfoTambahan" label="Informasi Tambahan" color="info"
                                    />
                            </VControl>
                        </VField>
                        <div class="pl-5" v-if="isInfoTambahan">
                          <div class="fieldset-heading pl-5">
                              <h3>Informasi Tambahan</h3>
                          </div>

                          <div class="columns is-multiline">
                              <div class="column is-12">
                                  <VField horizontal label="Bahasa Sehari-hari">
                                      <VControl icon="feather:bookmark" fullwidth>
                                          <VInput type="text" v-model="item.bahasa" placeholder=""
                                              class="is-rounded_Z" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Perlu Penerjemah">
                                    <VControl>
                                        <VRadio v-model="item.bantuanpenerjemah" v-for="items of d_Opsi" :key="'penerjemah'+items.id"
                                            :value="items.id" :label="items.opsi" name="{{'penerjemah'+items.id}}" color="primary" />
                                    </VControl>
                                </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Perlu bimbingan rohani">
                                    <VControl>
                                        <VRadio v-model="item.bantuanpelayanan" v-for="items of d_Opsi" :key="'bimbingan'+items.id"
                                            :value="items.id" :label="items.opsi" name="{{'bimbingan'+items.id}}" color="primary" />
                                    </VControl>
                                </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Ada keinginan tidak dikunjungi orang tertentu">
                                    <VControl>
                                        <VRadio v-model="item.dikunjungi" v-for="items of d_Opsi" :key="'kunjungan'+items.id"
                                            :value="items.id" :label="items.opsi" name="{{'kunjungan'+items.id}}" color="primary" />
                                    </VControl>
                                </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Tinggal dengan siapa">
                                    <VControl>
                                        <VRadio v-model="item.tinggal" v-for="items of d_Tinggal" :key="items.id"
                                            :value="items.id" :label="items.label" name="{{items.id}}" color="primary" />
                                    </VControl>
                                    <VField v-if="item.tinggal == 4">
                                          <VControl icon="feather:bookmark" fullwidth>
                                              <VInput type="text" v-model="item.lainLainTinggal" placeholder="" class="is-rounded_Z" />
                                          </VControl>
                                      </VField>
                                </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Menggunakan alat bantu diri">
                                    <VControl>
                                        <VRadio v-model="item.alatbantu" v-for="items of d_AlatBantu" :key="items.id"
                                            :value="items.id" :label="items.label" name="{{'alatBantu'+items.id}}" color="primary" />
                                    </VControl>
                                </VField>
                              </div>
                              <div class="fieldset-heading pl-5">
                                  <h3>Informasi Penanggung Jawab</h3>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Nama Penanggung Jawab">
                                      <VControl icon="feather:bookmark" fullwidth>
                                          <VInput type="text" v-model="item.penanggungJawabP" placeholder=""
                                              class="is-rounded_Z" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                  <VField class="ccis-rounded-select_Z_Z  is-autocomplete-select" v-slot="{ id }" horizontal label="Hubungan dengan pasien">
                                      <!-- <VLabel>Hubungan Dengan Pasien</VLabel> -->
                                      <VControl icon="feather:search" fullwidth>
                                          <Multiselect mode="single" v-model="item.hubunganP" :options="d_HubunganPasien"  autocomplete="off"
                                              placeholder="Pilih data" :searchable="true" :attrs="{ id }"  track-by="value"/>
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="No Telepon">
                                      <VControl icon="feather:phone" fullwidth>
                                          <VInput type="text" v-model="item.telponP" placeholder=""
                                              class="is-rounded_Z" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Jenis Kelamin">
                                      <VControl fullwidth>
                                          <div class="columns is-multiline pt-3 pb-2 pr-5 pl-5">
                                              <div class="column is-12" v-if="d_JK.length == 0">
                                                  <VPlaceloadText :lines="1" />
                                              </div>
                                              <div class="column is-4 p-0" v-for="items in d_JK" :key="items.id">
                                                  <VRadio v-model="item.jenisKelP" :value="items.id" class="p-0 mb-3"
                                                      :label="items.jeniskelamin" name="{{items.id}}" square
                                                      color="primary" />
                                              </div>
                                          </div>
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                <VField horizontal label="Umur">
                                      <VControl icon="feather:bookmark" fullwidth>
                                          <VInput type="text" v-model="item.umurP" placeholder="" class="is-rounded_Z" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                  <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" horizontal label="Pekerjaan">
                                      <VControl icon="feather:search" fullwidth>
                                          <Multiselect mode="single" v-model="item.pekerjaanP" :options="d_Pekerjaan"
                                              placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                      </VControl>
                                  </VField>
                              </div>
                              <div class="column is-12">
                                    <VField class="cis-rounded-select_Z  is-autocomplete-select" v-slot="{ id }" horizontal label="Pendidikan">
                                        <VControl icon="feather:search" fullwidth>
                                            <Multiselect mode="single" v-model="item.pendidikanP" :options="d_Pendidikan"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                                        </VControl>
                                    </VField>
                                </div>
                              <div class="column is-12">
                                <VField horizontal label="Alamat Penanggung Jawab">
                                      <VControl fullwidth>
                                          <VTextarea v-model="item.alamatP" rows="4" placeholder="Alamat Lengkap">
                                          </VTextarea>
                                      </VControl>
                                  </VField>
                              </div>
                          </div>
                        </div>
                    </div>
                </div>


                <!-- <div class="form-section is-grey">
                    <div class="form-section-inner is-horizontal">


                    </div>
                </div> -->
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
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import AutoComplete from 'primevue/autocomplete';
import InfoPasien from '/@src/pages/include/info-pasien.vue'
useHead({
    title: 'Mutasi Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let NOREC_APD = useRoute().query.norec_apd as string

const item: any = reactive({
    tglregistrasi: new Date(),
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    idKelompokPasienBPJS: [],
    idKelompokPasienUMUM: null
})

const pasien: any = ref({})
const d_Ruangan: any = ref([])
const d_AsalRujukan: any = ref([])
const d_KelompokPasien: any = ref([])
const d_JenisPelayanan: any = ref([])
const d_Opsi: any = ref([
  {
      "id": 2,
      "opsi": "TIDAK"
  },
  {
      "id": 1,
      "opsi": "YA"
  },
])
const d_Tinggal: any = ref([
  {
      "id": 1,
      "label": "Orang Tua"
  },
  {
      "id": 2,
      "label": "Suami/Istri"
  },
  {
      "id": 3,
      "label": "Sendiri"
  },
  {
      "id": 4,
      "label": "Lain-lain"
  },
])
const d_AlatBantu: any = ref([
  {
      "id": 1,
      "label": "Tidak"
  },
  {
      "id": 2,
      "label": "Alat Bantu Dengar"
  },
  {
      "id": 3,
      "label": "Kacamata/Kontak Lensa"
  },
  {
      "id": 4,
      "label": "Kawat Gigi"
  },
  {
      "id": 5,
      "label": "Implant"
  },
])
const d_Rekanan: any = ref([])
const d_JK: any = ref([])
const d_HubunganPasien: any = ref([])
const d_Pendidikan: any = ref([])
const d_Pekerjaan: any = ref([])
const d_Dokter: any = ref([])
const d_Kelas: any = ref([])
const d_Kamar: any = ref([])
const d_KelasDefault: any = ref([])
const d_TempatTidur: any = ref([])
const isLoading: any = ref(false)
const isLoadingTT: any = ref(false)
const isInfoTambahan: any = ref(false)
const isLoadingPasien: any = ref(false)
const modalAsuransi: any = ref(false)
const { y } = useWindowScroll()
const router = useRouter()
const isStuck = computed(() => {
    return y.value > 30
})

const pasienByID = async (id: any) => {
    isLoadingPasien.value = true
    await useApi().get(
        `/registrasi/head-mutasi?nocmfk=${id}&norec_pd=${item.NOREC_PD}`).then((response: any) => {
            pasien.value = response.pasien
            item.NOREC_PD = response.last_registrasi.norec_pd
            item.NOREC_APD = response.last_registrasi.norec_apd
            item.RUANGAN_LAST = response.last_registrasi.objectruanganlastfk
            item.ruangannextschedule = response.registrasi[0].namaruanganpesanan ? response.registrasi[0].namaruanganpesanan : null
            d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })
            d_AsalRujukan.value = response.asalrujukan.map((e: any) => { return { label: e.asalrujukan, value: e.id, default: e } })
            d_KelompokPasien.value = response.kelompokpasien.map((e: any) => { return { label: e.kelompokpasien, value: e.id, default: e } })
            d_KelasDefault.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
            d_JenisPelayanan.value = response.jenispelayanan
            d_Pendidikan.value = response.pendidikan.map((e: any) => { return { label: e.pendidikan, value: e.id , default: e} })
            d_Pekerjaan.value = response.pekerjaan.map((e: any) => { return { label: e.pekerjaan, value: e.id , default: e} })
            item.idKelompokPasienBPJS = response.idKelompokPasienBPJS
            item.idKelompokPasienUMUM = response.idKelompokPasienUMUM
            // item.jenispelayanan = response.jenispelayanan[0].id
            for (let x = 0; x < response.asalrujukan.length; x++) {
                const element = response.asalrujukan[x];
                if (element.asalrujukan.toLowerCase() == 'datang sendiri') {
                    item.asalrujukan = element.id
                    break
                }
            }

            let dataRegistrasi = response.registrasi[0]
            item.jenispelayanan = dataRegistrasi.jenispelayananfk
            item.bantuanpenerjemah = 2
            item.bantuanpelayanan = 2
            item.dikunjungi = 2
            item.tinggal = 1
            item.alatbantu = 1
            item.kelompokpasien = dataRegistrasi.objectkelompokpasienlastfk
            changeKelompok(item.kelompokpasien)
            item.rekanan = dataRegistrasi.objectrekananfk
            // response.jenispelayanan.forEach(element => {
            //     if(element.id == dataRegistrasi.jenispelayananfk){
            //         item.jenispelayanan = element.id
            //     }else{
            //         console.log('ddd')
            //     }
            // });
            isLoadingPasien.value = false
        })

        const response = await useApi().get(
        `/registrasi/list-dropdown`)
        d_JK.value = []
        for (let x = 0; x < response.jk.length; x++) {
            const element = response.jk[x];
            if (element.jeniskelamin != '-') {
                d_JK.value.push(element)
            }
        }
        d_HubunganPasien.value = response.hubunganpasien.map((e: any) => { return { label: e.hubungankeluarga, value: e.id} })

}
const setFromReservasi = (e: any) => {
    item.tglregistrasi = new Date(e.tanggalreservasi)
    item.ruangan = e.ruangan
    item.kelompokpasien = e.kelompok
    changeKelompok(item.kelompokpasien)
    item.dokter = { id: e.dokter, namalengkap: e.dokter_name }
}
const setFromRegistrasi = (regis: any) => {
    item.tglregistrasi = new Date(regis.tglregistrasi)
    item.ruangan = regis.objectruanganlastfk
    item.asalrujukan = regis.asalrujukanfk
    item.kelompokpasien = regis.objectkelompokpasienlastfk
    changeKelompok(item.kelompokpasien)
    item.rekanan = regis.objectrekananfk
    item.jenispelayanan = regis.jenispelayananfk
    if (regis.objectpegawaifk != null)
        item.dokter = { id: regis.objectpegawaifk, namalengkap: regis.dokter }
    item.objectkelasfk = regis.kelas
    item.isRawatInap = regis.israwatinap
    item.catatan = regis.catatan
    item.statuspasien = regis.statuspasien
    item.kamar = regis.objectkamarfk
    item.bed = regis.nobed

}
// const changeSwitch = (e: any) => {
//     delete item.ruangan
//     d_Ruangan.value = []
//     if (e == true) { d_Ruangan.value = d_RuanganRI.value } else { d_Ruangan.value = d_RuanganRI.value }
// }
const changeRuang = (e: any) => {
    item.kelas = null
    item.kelasRawat = null
    item.kamar = null
    item.bed = null
    if (e) {
        if (item.ruangan != null) {
            setKelas(e)
        }
    }
}
const setKelas = (e: any) => {
    isLoadingTT.value = true
    d_Kelas.value = []
    useApi().get(
        `/registrasi/list-kelas-mutasi?id=${e}`)
        .then((response: any) => {
            isLoadingTT.value = false
            item.kelas = response[0].id
            d_Kelas.value = response.map((e: any) => { return { label: e.namakelas, value: e.id, default: e } })
        })
        .catch((error: any) => { isLoadingTT.value = false })
}
const changeKelas = (e: any) => {
    d_Kamar.value = []
    delete item.kamar
    if (e && item.ruangan) {
        isLoadingTT.value = true
        useApi().get(
            `/registrasi/list-kamar-mutasi?id=${e}&idRuangan=${item.ruangan}&isRG=false`)
            .then((response: any) => {
                isLoadingTT.value = false
                d_Kamar.value = response.map((e: any) => { return { label: e.namakamar, value: e.id, default: e } })
            })
            .catch((error: any) => { isLoadingTT.value = false })
    }

}
const changeKamar = (e: any) => {
    d_TempatTidur.value = []
    if (e) {
        for (let x = 0; x < d_Kamar.value.length; x++) {
            const element = d_Kamar.value[x];
            if (element.value == e) {
                d_TempatTidur.value = element.default.details.map((e: any) => { return { label: e.reportdisplay, value: e.id, default: e } })
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
            `/registrasi/penjamin-mutasi?id=${e}`)
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
                    }
                }
            })
            .catch((error: any) => { isLoadingTT.value = false })
    }

}


const saveMutasi = async () => {
    if (!item.tglregistrasi) { H.alert('warning', 'Tgl Registrasi harus di isi'); return }
    if (!item.ruangan) { H.alert('warning', 'Ruangan harus di isi'); return }
    if (!item.asalrujukan) { H.alert('warning', 'Asal Rujukan  harus di isi'); return }
    if (!item.kelompokpasien) { H.alert('warning', 'Pembiayaan harus di isi'); return }
    if (!item.jenispelayanan) { H.alert('warning', 'Tipe Layanan harus di isi'); return }
    if (!item.kelas) { H.alert('warning', 'Kelas harus di isi'); return }
    if (!item.kelasRawat) { H.alert('warning', 'Kelas rawat harus di isi'); return }
    if (!item.kamar) { H.alert('warning', 'Kamar harus di isi'); return }
    if (!item.bed) { H.alert('warning', 'Bed harus di isi'); return }

    let json = {
        'pasiendaftar': {
            'norec_pd': item.NOREC_PD,
            'nocmfk': ID_PASIEN,
            'objectruangantujuanfk': item.ruangan,
            'asalrujukanfk': item.asalrujukan,
            'jenispelayananfk': item.jenispelayanan,
            'objectkelompokpasienlastfk': item.kelompokpasien,
            'objectpegawaifk': item.dokter ? item.dokter.id : null,
            'objectkelasfk': item.kelas ? item.kelas : null,
            'objectkelasrawatfk': item.kelasRawat ? item.kelasRawat : null,
            'isrencanabpjs': item.isrencanaBPJS ? 'rencana-bpjs' : null,
            'bahasa': item.bahasa ? item.bahasa : '',
            'bantuanpenerjemah': item.bantuanpenerjemah ? item.bantuanpenerjemah: null,
            'bantuanpelayanan': item.bantuanpelayanan ? item.bantuanpelayanan : null,
            'dikunjungi': item.dikunjungi ? item.dikunjungi : null,
            'alatbantu': item.alatbantu ? item.alatbantu : null,
            'residencefk': item.tinggal != 4 ? item.tinggal: item.lainLainTinggal,
        },
        'pasien':{
          'penanggungjawab': item.penanggungJawabP ? item.penanggungJawabP : '',
          'hubungankeluargapj': item.hubunganP ? item.hubunganP : null,
          'telponpenanggungjawab': item.telponP ? item.telponP : null,
          'jeniskelaminpenanggungjawab': item.jenisKelP ? item.jenisKelP : null,
          'umurpenanggungjawab': item.umurP ? item.umurP : null,
          'pekerjaanpenanggungjawab': item.pekerjaanP ? item.pekerjaanP : null,
          'pendidikanpenanggungjawab': item.pendidikanP ? item.pendidikanP : null,
          'alamatrmh': item.alamatP ? item.alamatP : null,
        },
        'antrianpasiendiperiksa': {
            'norec_apd': item.NOREC_APD,
            'noregistrasifk' : item.NOREC_PD,
            'objectruanganasalfk': item.RUANGAN_LAST,
            'objectruangantujuanfk' : item.ruangan,
            'objectasalrujukanfk' : item.asalrujukan,
            'tglregistrasi': H.formatDate(item.tglregistrasi, 'YYYY-MM-DD HH:mm:ss'),
            'objectkelasfk': item.kelas,
            'objectkelasrawatfk': item.kelasRawat,
            'objectkamarfk': item.kamar,
            'objectbedfk' : item.bed,
            'objectpegawaifk' : item.dokter ? item.dokter.id : null,
            'israwatgabung': item.israwatgabung ? item.israwatgabung : null
        }
    }
    isLoading.value = true
    await useApi().post(
        `/registrasi/save-mutasi`, json).then((response: any) => {
            isLoading.value = false

            // item.NOREC_PD = response.last_registrasi.norec_pd
            // item.NOREC_APD = response.last_registrasi.norec_apd
            for (let xx = 0; xx < item.idKelompokPasienBPJS.length; xx++) {
                const element = item.idKelompokPasienBPJS[xx];
                if (element == item.kelompokpasien) {
                    tambahAsuransi(item.NOREC_PD)
                }
            }
        }).catch((e: any) => {
        if (e.message == 'Bed Sudah Terisi, Silakan Pilih Bed Lain') {
        changeKelas(item.kelasRawat)
    }


            isLoading.value = false
            console.clear()
            console.log(e)
        })
}
const fetchDokter = async (filter: any) => {
    if (!filter.query) {

    }

    const response = await useApi().get(
        `/registrasi/dokter-mutasi?name=${filter.query}&limit=10`)
    d_Dokter.value = response.dokter
    // return response.dokter.map((item: any) => {
    //     return { value: item.id, label: item.namalengkap, default: item }
    // })
}
const tambahAsuransi = (norec_pd: any) =>{
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
pasienByID(ID_PASIEN)
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
</style>
