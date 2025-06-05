<template>
  <ConfirmDialog />
  <div class="form-layout is-stacked">
    <div class="form-outer" style="margin-top: 15px">

      <div class="form-body p-2">
        <div class="business-dashboard hr-dashboard">
          <div class="column is-12">
            <div class="block-header">
              <div class="left">
                <div class="current-user" style="text-align: center;">
                  <VAvatar size="medium" :picture="pasien.jeniskelamin == 'PEREMPUAN'
                    ? '/images/avatars/svg/vuero-4.svg'
                    : '/images/avatars/svg/vuero-1.svg'
                    " squared />

                  <VPlaceload width="100%" v-if="isPlaceLoadHead" />
                  <h3 v-else>{{ pasien.namaPasien }}</h3>
                </div>
              </div>
              <div class="center">
                <div class="columns">
                  <div class="column">
                    <h4 class="block-heading">No RM</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <p class="block-text" v-else>{{ pasien.noCm }}</p>
                    <h4 class="block-heading">No Registrasi</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <p class="block-text" v-else>{{ pasien.noRegistrasi }}</p>
                  </div>
                  <div class="column">
                    <h4 class="block-heading">Ruangan</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <p class="block-text" v-else>{{ pasien.lastRuangan }}</p>
                    <h4 class="block-heading">Kelamin</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <p class="block-text" v-else>{{ pasien.jeniskelamin }}</p>
                  </div>
                </div>
              </div>
              <div class="right">
                <div class="columns">
                  <div class="column">
                    <h4 class="block-heading">Penjamin</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <p class="block-text" v-else>{{ pasien.namaPenjamin }}</p>
                    <h4 class="block-heading">Status</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <p class="block-text" v-else>{{ pasien.StatusPasien }}</p>
                  </div>
                  <div class="column">
                    <h4 class="block-heading">Kelas</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <VTag color="orange" v-else :label="pasien.kelasRawat" />

                    <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                    <VPlaceload width="50%" v-if="isPlaceLoadHead" />
                    <VTag color="orange" v-else :label="pasien.jenisPasien" />
                  </div>

                </div>
              </div>
            </div>
          </div>

          <div class="column is-12" v-if="isPlaceLoad">
            <VCard>
              <VPlaceloadWrap v-for="data in 25">
                <VPlaceload class="mx-2 mb-3" />
                <VPlaceload class="mx-2" />
              </VPlaceloadWrap>
            </VCard>
          </div>

          <div class="column is-12" v-else>
            <VCard>
              <VButton style="margin-right: auto;" raised color="purple" icon="fa fa-edit" @click="showModalChangeRekanan(pasien)">Ubah Rekanan
              </VButton>
              <div class="timeline-wrapper">
                <div class="timeline-wrapper-inner">
                  <div class="timeline-container">
                    <div class="timeline-item is-unread" v-for="(data) in dataSource" :key="data.norec">
                      <div class="date">
                        <span>{{ H.formatDateIndoSimple(data.tglmasuk) }}</span>
                      </div>
                      <div class="dot is-info"></div>
                      <div class="content-wrap">
                        <div class="content-box">
                          <div class="status"></div>
                          <VIconBox size="medium" color="primary" rounded>
                            <i class="lnir lnir-diagnosis" aria-hidden="true"></i>
                          </VIconBox>

                          <div class="column">
                            <span style="font-weight: 600; font-size: 17px;">{{ data.namaruangan }}</span>
                            <VTag class="ml-4" color="info" rounded>{{ data.namakelas }}</VTag>
                            <div class="columns is-multiline pt-3">
                              <div class="column  pt-0 pb-0 ">
                                <span>Dokter :</span> <span style="font-weight: 500;color: #646161;">{{ data.namadokter}}</span>
                              </div>

                            </div>
                            <div class="columns is-multiline pt-0">
                              <div class="column is-5 pt-0 pb-0 ">
                                <span>Kamar :</span> <span style="font-weight: 500;color: #646161;">{{ data.namakamar !=
                                  null ? data.namakamar : '-' }}</span>
                              </div>
                              <div class="column is-5 pt-0 pb-0">
                                <span>Bed : {{ data.nobed != null ? data.nobed : '-' }} </span> <span style="font-weight: 500;color: #646161;"></span>
                              </div>
                            </div>
                            <div class="columns is-multiline">
                              <div class="column is-5 pt-0 pb-0 ">
                                <span>Tanggal Masuk :</span> <span style="font-weight: 500;color: #646161;">{{
                                  H.formatDateIndoSimpleNoDay(data.tglmasuk) }}</span>
                              </div>
                              <div class="column is-5 pt-0 pb-0">
                                <span>Tanggal Keluar :</span> <span style="font-weight: 500;color: #646161;">{{
                                  data.tglkeluar ? H.formatDateIndoSimpleNoDay(data.tglkeluar) : '-' }}</span>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="column ml-5">
                          <VButtons>
                            <VButton raised color="info" icon="fa fa-edit" @click="changeDokter(data)">Ubah Dokter
                            </VButton>
                            <VButton raised color="success" icon="fa fa-edit" @click="showFormChangeDate(data)">Ubah
                              Tanggal
                            </VButton>
                            <VButton raised color="warning" icon="fas fa-file-medical-alt" @click="formKonsul(data)">
                              Konsul</VButton>
                            <VButton raised color="danger" icon="fas fa-trash-alt" :loading="btnLoadDelete"
                              @click="dialogConfirm(data)">Hapus Registrasi</VButton>
                          </VButtons>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </VCard>
          </div>
        </div>
      </div>
    </div>

  </div>

  <Dialog v-model:visible="modalKonsultasi" modal header="Form Konsultasi" :style="{ width: '25vw' }">
    <div class="column">
      <span style="font-weight: 500;">Dokter</span>
      <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
        <VControl icon="feather:search">
          <AutoComplete v-model="item.dokter" :suggestions="d_Dokter" @complete="fetchDokter($event)"
            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
            :field="'label'" placeholder="ketik Nama Dokter" />
        </VControl>
      </VField>
    </div>
    <div class="column">
      <div class="columns is-multiline">
        <div class="column is-12">
          <span style="font-weight: 500;">Ruangan</span>
          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="item.ruangan" :suggestions="d_Ruangan" @complete="fetchRuangan($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik Nama Ruangan" />
            </VControl>
          </VField>
        </div>
        <div class="column is-12">
          <span style="font-weight: 500;">Kelas</span>
          <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
            <VControl icon="feather:search">
              <AutoComplete v-model="item.kelas" :suggestions="d_Kelas" @complete="fetchKelas($event)"
                :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                :field="'label'" placeholder="ketik Nama Kelas" />
            </VControl>
          </VField>
        </div>
      </div>

    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalKonsultasi = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="saveKonsul(item)" :loading="isLoadingKonsul"> Simpan </VButton>
      <!-- <Button label="No" icon="pi pi-times" @click="modalChangeDokter.value = false" text />
      <Button label="Yes" icon="pi pi-check" @click=""/> -->
    </template>
  </Dialog>

  <Dialog v-model:visible="modalChangeDokter" modal header="Form Ubah Dokter" :style="{ width: '25vw' }">
    <div class="column">
      <span style="font-weight: 500;">Dokter</span>
      <VField class="is-autocomplete-select pt-3" v-slot="{ id }">
        <VControl icon="feather:search">
          <AutoComplete v-model="item.dokterPemeriksa" :suggestions="d_Dokter" @complete="fetchDokter($event)"
            :optionLabel="'label'" :dropdown="true" :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
            :field="'label'" placeholder="ketik Nama Dokter" />
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDokter = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDokter()" :loading="btnLoadSimpan"> Update
      </VButton>
      <!-- <Button label="No" icon="pi pi-times" @click="modalChangeDokter.value = false" text />
      <Button label="Yes" icon="pi pi-check" @click=""/> -->
    </template>
  </Dialog>

  <Dialog v-model:visible="modalChangeDate" modal header="Form Ubah Tanggal" :style="{ width: '25vw' }">
    <div class="column">
      <div class="columns is-multiline">
        <div class="column">
          <VDatePicker v-model="item.tanggalRegistrasi" mode="dateTime" style="width: 100%;" :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }">
              <VField label="Tanggal Registrasi">
                <VControl icon="feather:calendar" fullwidth>
                  <VInput :value="inputValue" v-on="inputEvents" placeholder="Select a date" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
        <div class="column">
          <VDatePicker v-model="item.tanggalMasuk" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
            <template #default="{ inputValue, inputEvents }" class="pb-0" disabled>
              <VField label="Tanggal Masuk">
                <VControl icon="feather:calendar">
                  <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                    class="is-rounded_Z" />
                </VControl>
              </VField>
            </template>
          </VDatePicker>
        </div>
      </div>
    </div>
    <div class="column pt-0">
      <VDatePicker v-model="item.tanggalKeluar" color="green" trim-weeks mode="dateTime" :max-date="new Date()">
        <template #default="{ inputValue, inputEvents }" class="pb-0" disabled>
          <VField label="Tanggal Keluar">
            <VControl icon="feather:calendar">
              <VInput type="text" placeholder="Select a date" :value="inputValue" v-on="inputEvents"
                class="is-rounded_Z" />
            </VControl>
          </VField>
        </template>
      </VDatePicker>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeDate = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="saveChangeDate()" :loading="btnLoadSimpan"> Update
      </VButton>
    </template>
  </Dialog>
  <Dialog v-model:visible="modalChangeRekanan" modal header="Form Ubah Rekanan Pasien" :style="{ width: '40vw'  }">
    <div class="column is-12 mb-5">
      <VField class="is-autocomplete-select">
        <VLabel>Pembiayaan</VLabel>
          <VControl icon="fas fa-calculator">
            <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien" placeholder="Pilih Ruangan" :searchable="true"  @select="changeKelompok(item.kelompokpasien)" />
          </VControl>
        </VField>
    </div>
    <div class="column is-12 mb-5">
      <VField v-if="item.kelompokpasien"  label="Penjamin" class="is-rounded-select_Z  is-autocomplete-select" v-slot="{ id }">
        <VControl icon="feather:command">
          <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan"
            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
            :loading="isLoadingTT" />
        </VControl>
      </VField>
    </div>
    <template #footer>
      <VButton color="danger" icon="pi pi-times" outlined raised @click="modalChangeRekanan = false"> Batal </VButton>
      <VButton color="primary" icon="pi pi-check" raised @click="saveUpdateRekanan()" :loading="btnLoadSimpan"> Update
      </VButton>
    </template>
  </Dialog>
</template>
<script setup lang="ts">
import { ref, reactive, computed, watch } from 'vue'
import { useWindowScroll } from '@vueuse/core'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useRoute, useRouter } from 'vue-router'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import AutoComplete from 'primevue/autocomplete';
import Dialog from 'primevue/dialog';
import { useConfirm } from "primevue/useconfirm"
import { useToaster } from '/@src/composable/toaster'
import ConfirmDialog from 'primevue/confirmdialog'

useHead({
  title: 'Detail Registrasi Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
let NOREGISTRASI = useRoute().query.noregistrasi as string
let NOREC_PD = useRoute().query.norec_pd as string
const pasien: any = ref({})
const dataSource: any = ref({})
const d_Ruangan: any = ref([])
const d_Dokter: any = ref([])
const d_KelompokPasien: any = ref([])
const d_Rekanan: any = ref([])
const d_Kelas: any = ref([])
const itemSource: any = ref([])
const modalKonsultasi: any = ref(false)
const modalChangeDokter: any = ref(false)
const modalChangeRekanan: any = ref(false)
const modalChangeDate: any = ref(false)
const btnLoadSimpan: any = ref(false)
const btnLoadDelete: any = ref(false)
const isPlaceLoad: any = ref(true)
const isLoadingTT: any = ref(false)
const isPlaceLoadHead: any = ref(true)
const isDisabled: any = ref(true)
const isLoadingKonsul: any = ref(false)
const item: any = reactive({
  idKelompokPasienBPJS: [],
})
const { y } = useWindowScroll()
const isStuck = computed(() => {
  return y.value > 30
})

const confirm = useConfirm();

const headerPasien = async () => {
  // isLoadingPasien.value = true
  await useApi()
    .get(`/registrasi/get-detail-registrasi?noregistrasi=${NOREGISTRASI}`)
    .then((response: any) => {
      pasien.value = response.data
    })
  detailRegistrasi()
}

const detailRegistrasi = async () => {
  isPlaceLoad.value = true
  await useApi().get(`/registrasi/get-detail-registrasi-pasien?noregistrasi=${NOREGISTRASI}`).then((response) => {
    dataSource.value = response
  })
  isPlaceLoadHead.value = false
  isPlaceLoad.value = false
}

const formKonsul = async (data: any) => {
  modalKonsultasi.value = true
  await fetchKelas({query:'NON KELAS'})
  if( d_Kelas.value.length){
    item.kelas = d_Kelas.value[0]
  }

  itemSource.value = data
}

const saveKonsul = async (e: any) => {

  let object = {
    "norec_pd": NOREC_PD,
    "asalRujukanfk": itemSource.value.objectasalrujukanfk,
    "noAntrian": dataSource.value.length + 1,
    "dokterfk": e.dokter.value,
    "objectruanganasalfk": itemSource.value.ruid_asal,
    "objectruangantujuan": e.ruangan.value,
    "kelasfk": e.kelas.value,
  }
  isLoadingKonsul.value = true
  await useApi().post('registrasi/simpan-pasien-konsul', object).then((response) => {
    isLoadingKonsul.value = false
    modalKonsultasi.value = false
    detailRegistrasi()
    delete item.dokter
    delete item.ruangan
    delete item.kelas
  }).catch((err) => {
    modalKonsultasi.value = false
    isLoadingKonsul.value = false
    delete item.dokter
    delete item.ruangan
    delete item.kelas
  })
}

const changeDokter = (data: any) => {
  modalChangeDokter.value = true
  data.pgid ? item.dokterPemeriksa = { value: data.pgid, label: data.namadokter } :{}
  itemSource.value = data
}

const saveChangeDokter = async () => {
  if (!item.dokterPemeriksa) {
    H.alert('error', 'Dokter Wajib Dipilih')
    return
  }
  btnLoadSimpan.value = true

  let objSave = {
    'norec_apd': itemSource.value.norec,
    'objectpegawaifk': item.dokterPemeriksa.value,
    'norec_pd': itemSource.value.norec_pd,
    'ruanganfk': itemSource.value.ruid_asal
  }
  await useApi().post('registrasi/change-dokter-apd', objSave).then((response) => {
    modalChangeDokter.value = false
    detailRegistrasi()
  }).catch((err) => {
    console.log(err)
  })
  btnLoadSimpan.value = false
}

const showFormChangeDate = (data: any) => {
  modalChangeDate.value = true
  item.tanggalRegistrasi = data.tglregistrasi
  item.tanggalKeluar = data.tglkeluar
  item.tanggalMasuk = data.tglmasuk
  itemSource.value = data
}

const saveChangeDate = async () => {
  let objSave = {
    "norec_apd": itemSource.value.norec,
    "norec_pd": itemSource.value.norec_pd,
    "noregistrasi": NOREGISTRASI,
    "ruanganasal": itemSource.value.ruid_asal,
    "tglkeluar": item.tanggalKeluar ? H.formatDate(item.tanggalKeluar, 'YYYY-MM-DD HH:mm:ss') : '',
    "tglmasuk": item.tanggalMasuk ? H.formatDate(item.tanggalMasuk, 'YYYY-MM-DD HH:mm:ss') : '',
    "tglregistrasi": item.tanggalRegistrasi ? H.formatDate(item.tanggalRegistrasi, 'YYYY-MM-DD HH:mm:ss') : '',
  }
  btnLoadSimpan.value = true
  await useApi().post('registrasi/ubah-tanggal', objSave).then((response) => {
    btnLoadSimpan.value = false
    modalChangeDate.value = false
    delete item.tanggalKeluar
    delete item.tanggalMasuk
    delete item.tanggalRegistrasi
    detailRegistrasi()
  }).catch((e) => {
    btnLoadSimpan.value = false
    modalChangeDate.value = false
    delete item.tanggalKeluar
    delete item.tanggalMasuk
    delete item.tanggalRegistrasi
  })
}

const dialogConfirm = (e: any) => {
  confirm.require({
    message: 'Apakah anda serius menghapus data ini ?',
    header: 'Konfirmasi Hapus Data',
    icon: 'pi pi-info-circle',
    acceptClass: 'p-button-danger',
    accept: () => {
      deleteItemAPD(e)

    },
    reject: () => { },
  })
}

const showModalChangeRekanan = (e: any) => {
  modalChangeRekanan.value = true;
  fetchKelompokPasien();
  itemSource.value = e
  item.nomerBpjs = e.nobpjs
  item.rekanan = e.objectrekananfk;
  item.kelompokpasien =e.objectkelompokpasienlastfk;
  fetchRekananPasien();
}

const saveUpdateRekanan = async () => {
  if (!item.kelompokpasien) {
    H.alert('error', 'Kelompok pasien Wajib Dipilih')
    return
  }
  btnLoadSimpan.value = true
  let objSave = {
    'norec_pd': itemSource.value.norec_pd,
    'objectkelompokpasienlastfk': item.kelompokpasien,
    'objectrekananfk': item.rekanan,
  }
  await useApi().post('registrasi/save-update-rekanan_pd', objSave).then((response) => {
    modalChangeRekanan.value = false
    detailRegistrasi()
    headerPasien()
  }).catch((err) => {
    console.log(err)
  })
  btnLoadSimpan.value = false
}
const changeKelompok = async (e: any) => {
      if (e == 2) {
        if (item.nomerBpjs == null || item.nomerBpjs == '') {
          H.alert('warning', 'NO BPJS anda masih kosong, harap lengkapi data')
        } else if (item.nomerBpjs.length != 13) {
          H.alert('warning', 'NO BPJS anda tidak sesuai, harap sesuaikan data ')
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
                    }
                }
            })
            .catch((error: any) => { isLoadingTT.value = false })
    }
}
const deleteItemAPD = async (e: any) => {
  btnLoadDelete.value = true
  await useApi().post('registrasi/hapus-apd', { 'norec_apd': e.norec, 'norec_pd': e.norec_pd }).then((response) => {
    btnLoadDelete.value = false
    detailRegistrasi()
  }).catch((err) => {
    btnLoadDelete.value = false
    useToaster().error(err.metaData.message)
  })
}

const fetchKelompokPasien =  () => {
  item.kelompokpasien = [];
  item.rekanan =null;
  useApi().get(
    `emr/dropdown/kelompokpasien_m?select=id,kelompokpasien`
  ).then((response) => {
    d_KelompokPasien.value = response
  })
}
const fetchRekananPasien = () => {
  useApi().get(
    `registrasi/penjamin-by-kelompokpasien?id=${item.kelompokpasien}`
  ).then((response) => {
    d_Rekanan.value = response.map((e: any) => { return { label: e.namarekanan, value: e.id } })
  })
}

const fetchKelas = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/kelas_m?select=id,namakelas&param_search=namakelas&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Kelas.value = response
  })
}

const fetchRuangan = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/ruangan_m?select=id,namaruangan&param_search=namaruangan&query=${filter.query}&limit=10`
  ).then((response) => {
    d_Ruangan.value = response
  })
}
const fetchDokter = async (filter: any) => {
  await useApi().get(
    `emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
  ).then((response) => {
    d_Dokter.value = response
  })
}


headerPasien()

</script>
<style lang="scss">
@import '/@src/scss/main';
@import '/@src/scss/custom/timeline-css';

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
  content: "";
  position: absolute;
  top: 106px !important;
  left: 111px;
  height: 100%;
  width: 2px;
  background: var(--placeholder);
  z-index: 0;
}


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
