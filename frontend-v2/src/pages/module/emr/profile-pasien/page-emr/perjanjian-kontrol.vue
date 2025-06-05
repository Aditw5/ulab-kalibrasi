<template>
    <section>
      <ConfirmDialog/>
    <VCard>
        <div class="columns is-multiline" >
            <div class="column is-12">
                <div class="form-layout is-stacked-2">
                    <div class="form-outer" style="margin-top:15px">
                        <div v-if="isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                            <div class="form-header-inner">
                                <div class="left">
                                    <h3> {{ props.FORM_NAME }}</h3>
                                </div>
                                <div class="right">
                                    <div class="buttons">
                                        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined
                                            @click="kembaliKeun">
                                            Kembali
                                        </VButton>
                                        <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                            :loading="isLoading" @click="simpan"> Simpan
                                        </VButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div v-if="!isStuck" class="form-header stuck-header">
                            <div class="form-header-inner">
                                <div class="left">
                                    <h3> {{ props.FORM_NAME }}</h3>
                                </div>
                                <div class="right">
                                    <div class="buttons">
                                        <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined
                                            @click="kembaliKeun">
                                            Kembali
                                        </VButton>
                                        <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                            :loading="isLoading" @click="simpan"> Simpan
                                        </VButton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="columns is-multiline p-2" style="max-width: 1380px; margin: 0 auto;">
                    <div class="column is-10">
                            <div class="columns is-multiline">
                                <div class="column is-4">
                                    <VDatePicker v-model="input.tanggal" color="green" trim-weeks mode="dateTime"
                                        :min-date="new Date()">
                                        <template #default="{ inputValue, inputEvents }" class="pb-0">
                                            <VField>
                                                <VLabel class="required-field">Tanggal Perjanjian</VLabel>
                                                <VControl icon="feather:calendar">
                                                    <VInput type="text" placeholder="Select a date" :value="inputValue"
                                                        v-on="inputEvents" class="is-rounded_Z" />
                                                </VControl>
                                            </VField>
                                        </template>
                                    </VDatePicker>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel class="required-field">Poliklinik</VLabel>
                                        <VControl icon="feather:search" class="prime-auto-select">
                                            <!-- <AutoComplete v-model="input.poli" :suggestions="d_Poli"
                                                @complete="fetchPoli($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Poliklinik..." class="mt-2" /> -->
                                            <Dropdown v-model="input.poli" :options="d_Poli" :optionLabel="'label'"
                                                class="is-rounded" placeholder="Poliklinik" style="width: 100%;" showClear
                                                :filter="true" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField>
                                        <VLabel class="required-field">Dokter</VLabel>
                                        <VControl icon="feather:search" class="prime-auto-select">
                                            <Dropdown v-model="input.dokter" :options="d_Pegawai" :optionLabel="'label'"
                                                class="is-rounded" placeholder="Dokter" style="width: 100%;" showClear
                                                :filter="true" />
                                            <!-- <AutoComplete v-model="input.dokter" :suggestions="d_Pegawai"
                                                @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                                :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                :field="'label'" placeholder="Dokter..." class="mt-2" /> -->
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel class="required-field">Diagnosa </VLabel>
                                        <VControl>
                                            <VTextarea v-model="input.diagnosa" rows="3" placeholder="Diagnosa ">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Indikasi Kembali ke RS </VLabel>
                                        <VControl>
                                            <VTextarea v-model="input.indikasi" rows="3"
                                                placeholder="Indikasi Kembali ke RS ">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-12">
                                    <VField>
                                        <VLabel>Keterangan Lainnya</VLabel>
                                        <VControl>
                                            <VTextarea v-model="input.keteranganLainnya" rows="3"
                                                placeholder="Keterangan Lainnya ">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                    </div>
                    <div class="column is-2">
                        <VCard  :loading="isLoading">
                            <div class="column border-custom mb-2 mt-5-min">
                                <span style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">Jadwal Dokter
                                </span>
                            </div>
                            <div class="tile-grid-konsul tile-grid-konsul-v2" v-if="dataDokter.length">
                                <div name="list" tag="div" class="columns is-multiline">
                                <div class="columns is-multiline p-2" style="max-height:390px;overflow: auto;">
                                    <div v-for="item in dataDokter" :key="item.id" class="column is-12 p-0 pb-2 pl-2 pr-2 ">
                                    <div class="tile-grid-konsul-item">
                                        <div class="tile-grid-konsul-item-inner">
                                        <!-- <VAvatar size="small" picture="/images/avatars/svg/dokter.svg" color="primary" bordered /> -->
                                        <div class="meta">
                                            <span class="dark-inverted text-elipsis-wrap" style="width:200px !important">{{ item.namadokter
                                            }}</span>
                                            <span>
                                            <i aria-hidden="true" class="iconify" data-icon="feather:clock" style="padding-right: 3px;"></i>
                                            {{ item.jadwal }}</span>
                                        </div>
                                        <VTag style="margin-left: auto;" color="info" label="Tag Label" rounded elevated> {{ item.namahari }}
                                        </VTag>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div class="tile-grid-konsul tile-grid-konsul-v2" v-if="dataDokter.length == 0">
                                <div class="flex-list-inner  text-center">
                                    <VPlaceholderSection title="Harap Pilih Poliklinik Atau Jadwal Dokter Tidak Ada" class="my-6">
                                    <template #image>
                                        <img class="light-image" :src="'/@src/assets/illustrations/placeholders/search-4-dark.svg'" alt=""
                                        style="width: 100px;" />
                                        <img class="dark-image" src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                                        style="width: 100px;" />
                                    </template>
                                    </VPlaceholderSection>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </div>
        </div>
        <div class="columns is-multiline">
            <div class="column is-12">
                <div class="columns is-multiline">
                            <div class="column is-3">
                                <!-- <VButton rounded icon="feather:plus" raised bold @click="add()" color="success" outlined
                                    :loading="isLoading" class="mr-2">Tambah </VButton> -->
                                <VButton rounded icon="feather:printer" raised bold @click="cetakSKD()" color="info"
                                     :loading="isLoadingCetak" outlined class="mr-2">Cetak SKDP </VButton>
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
                        <DataTable :value="dataSource" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                            tableStyle="min-width: 50rem">
                            <Column header="Aksi" style="width: 100px">
                                <template #body="slotProps">
                                    <VIconButton icon="feather:printer" @click="cetak(slotProps.data)" color="info" raised
                                        circle class="mr-2">
                                    </VIconButton>
                                    <VIconButton icon="feather:edit" @click="edit(slotProps.data)" color="warning" raised
                                        circle class="mr-2">
                                    </VIconButton>
                                    <VIconButton icon="feather:trash" :loading="slotProps.data.loadingHapus"
                                        @click="hapus(slotProps.data)" color="danger" raised circle>
                                    </VIconButton>
                                </template>
                            </Column>
                            <Column field="tglperjanjian" header="Tgl Perjanjian" style="width: 100px"></Column>
                            <Column field="noantrian" header="No Antrian" style="width: 150px">
                              <template #body="slotProps">
                                <span v-if="slotProps.data.noantrian">
                                  {{slotProps.data.jenis ?? ''}}{{ slotProps.data.noantrian > 10 ? `-0${slotProps.data.noantrian}` : `-00${slotProps.data.noantrian}` }}
                                </span>
                              </template>
                            </Column>
                            <Column field="namaruangan" header="Poliklinik" style="width: 150px"></Column>
                            <Column field="namalengkap" header="Dokter" style="width: 150px"></Column>
                            <Column field="diagnosa" header="Diagnosa" style="width: 150px"></Column>
                            <Column field="indikasi" header="Indikasi Kembali ke RS" style="width:200px"></Column>
                            <Column field="keterangan" header="Keterangan" style="width: 200px"></Column>


                        </DataTable>
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
    </VCard>
        <Dialog v-model:visible="modalInput" modal header="Rencana Kontrol/SPRI" :style="{ width: '50vw' }">
            <div class="columns is-multiline">
                <div class="column is-6">
                    <VField>
                        <VLabel class="required-field">No Kartu</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="input.nobpjs" type="text" class="input is-rounded" placeholder="No Kartu " />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-6">
                    <VField>
                        <VLabel class="required-field">No SEP</VLabel>
                        <VControl icon="feather:bookmark">
                            <input v-model="input.nosep" type="text" class="input is-rounded" placeholder="No SEP " />
                        </VControl>
                    </VField>
                </div>
                <div class="column is-12">
                    <Panel header="Riwayat" toggleable>
                        <div class="columns is-multiline">
                            <div class="column is-3">
                                <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                                    <VLabel>Filter</VLabel>
                                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="input.filter" :options="d_Filter" :optionLabel="'nama'"
                                            class="is-rounded" placeholder="Filter" style="width: 100%;" :filter="true"
                                            showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                                    <VLabel>Tahun</VLabel>
                                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="input.tahun" :options="d_Tahun" :optionLabel="'nama'"
                                            class="is-rounded" placeholder="Tahun" style="width: 100%;" :filter="true"
                                            showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3">
                                <VField class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                                    <VLabel>Bulan</VLabel>
                                    <VControl icon="feather:bookmark" fullwidth class="prime-auto-select">
                                        <Dropdown v-model="input.bulan" :options="d_Bulan" :optionLabel="'nama'"
                                            class="is-rounded" placeholder="Bulan" style="width: 100%;" :filter="true"
                                            showClear />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2">
                                <VIconButton icon="feather:search" @click="cariSKD" color="success" raised
                                    :loading="isLoadingSKD" circle class="mt-5">
                                </VIconButton>
                            </div>
                            <div class="column is-12">
                                <DataTable :value="dataSourceSPRI" paginator :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                                    tableStyle="min-width: 50rem">
                                    <Column header="Aksi" style="width: 100px">
                                        <template #body="slotProps">
                                            <VIconButton icon="feather:printer" @click="cetakSPRI(slotProps.data)"
                                                color="info" raised circle class="mr-2">
                                            </VIconButton>

                                        </template>
                                    </Column>
                                    <Column field="noSuratKontrol" header="No Surat" style="width: 150px"></Column>
                                    <Column field="namaJnsKontrol" header="Jenis" style="width: 150px"></Column>
                                    <Column field="tglRencanaKontrol" header="Tgl Rencana Kontrol" style="width: 150px">
                                    </Column>
                                    <Column field="tglTerbitKontrol" header="Tgl Entri" style="width: 150px"></Column>
                                    <Column field="noSepAsalKontrol" header="No SEP Asal" style="width:150px"></Column>
                                    <Column field="namaPoliAsal" header="Poli Asal" style="width: 150px"></Column>
                                    <Column field="namaPoliTujuan" header="Poli Tujuan" style="width: 150px"></Column>
                                    <Column field="namaDokter" header="Dokter" style="width: 150px"></Column>
                                    <Column field="terbitSEP" header="Terbit SEP" style="width: 150px"></Column>

                                </DataTable>
                            </div>
                        </div>
                    </Panel>
                </div>
            </div>

        </Dialog>
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import Panel from 'primevue/panel';
import FileUpload from 'primevue/fileupload';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { useConfirm } from 'primevue/useconfirm'
import ConfirmDialog from 'primevue/confirmdialog'
useHead({
    title: 'Perjanjian Pasien - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let dataDokter: any = ref([])

const { y } = useWindowScroll()
const emit = defineEmits()
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
const d_Berkas: any = ref([])
const d_Poli: any = ref([])
const d_Pegawai: any = ref([])
const d_Filter: any = ref([{ kode: 2, nama: 'Tgl Rencana Kontrol' }, { kode: 1, nama: 'Tgl Entri' }])
const d_Bulan: any = ref(H.monthList())
const d_Tahun: any = ref(H.yearList())
const dataSourceSPRI: any = ref([])
const cariDokter: any = ref({})
const filePasien: any = ref()
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const isLoadingCetak: any = ref(false)
const isLoadingSKD: any = ref(false)
const showInput: any = ref(false)
const input: any = ref({})
const namappkRumahSakit = ref('')

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
for (let x = 0; x < d_Bulan.value.length; x++) {
    const element = d_Bulan.value[x];
    if (element.kode == new Date().getMonth()) {
        input.value.bulan = element
    }
}

const confirm = useConfirm();

const loadDrop = async () => {
    await useApi().get(
        `emr/dropdown/ruangan_m?select=id,namaruangan,kdsubspesialisbpjs,kdspesialisbpjs&param_search=namaruangan&query=&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix,kdDepartemenRawatJalanFix`
    ).then((response) => {
        d_Poli.value = response
    })

    await useApi().get(
        `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter`
    ).then((response) => {
        d_Pegawai.value = response
    })

    await useApi().get(
        `general/ppk-bpjs`
    ).then((response) => {
        namappkRumahSakit.value = response.BPJS_namaPPKRujukan
    })
}

const loadRiwayat = () => {
    isLoading.value = true
    // &noregistrasi=${props.registrasi.noregistrasi}
    useApi().get(
        `/emr/get-perjanjian?nocm=${props.pasien.nocm}`).then((response: any) => {
            isLoading.value = false
            dataSource.value = response.data
        })
}

const add = async () => {
    await loadDrop()
    filePasien.value = null
    input.value = {
        tanggal: new Date(),
        tahun: {
            kode: new Date().getFullYear(),
            nama: new Date().getFullYear(),
        }
    }
    d_Pegawai.value.forEach((element) => {
        if (element.value == props.registrasi.objectpegawaifk) {
            input.value.dokter = element
        }
    });
    // d_Ruangan.value.forEach((element) => {
    //     if (element.value == props.registrasi.objectruanganlastfk) {
    //         input.ruanganasal = element
    //     }
    // });
    d_Poli.value.forEach((element: any) => {
        if (props.registrasi.objectruanganlastfk == element.value) {
            input.value.poli = element
        }
    });
    // showInput.value = true
    isLoadingCetak.value = true
    await useApi().get(
        `/emr/auto-fill-icd10/${props.registrasi.noregistrasi}`).then((response: any) => {
            isLoadingCetak.value = false
            let kdnamadiagnosa = []
            for (let i = 0; i < response.length; i++) {
                const element = response[i];
                kdnamadiagnosa.push(response[i].kddiagnosa + '~' + response[i].namadiagnosa)
            }
            input.value.diagnosa = kdnamadiagnosa.join(',');
        })

}

const fetchJadwalDokter = async () => {
  if (!input.value.poli) {
    useToaster().error('Harap Pilih Ruangan Tujuan terlebih dahulu !')
    return
  }

  const tgl = H.formatDate(input.value.tanggal, 'YYYY-MM-DD')

  dataDokter.value = []
  isLoading.value = true

  var json = {
    url: `jadwaldokter/kodepoli/${input.value.poli.kdspesialisbpjs}/tanggal/${tgl}`,
    method: 'GET',
    jenis: 'antrean',
    data: null,
  }
  await useApi()
    .postNoMessage(`/bridging/bpjs/tools`, json)
    .then((response: any) => {
      if (response.metaData.code == 200 && response.response) {
        for (let x = 0; x < response.response.length; x++) {
          const element = response.response[x]
          element.no = x + 1
        }
        dataDokter.value = response.response.filter(e =>e.kodesubspesialis == input.value.poli.kdsubspesialisbpjs)
      }
      isLoading.value = false
    })
    .catch((err) => {
      console.log(err)
    })
}

const onSelect = async (filez: any) => {

    const file = filez
        .files[0];
    if (file.size > 1000000) {
        H.alert('error', 'Maksimal file size adalah 1 MB')
        return
    }

    if (file.type != "application/pdf" && (file.type.indexOf('image/') > -1) == false) {
        H.alert('error', 'File yang diizinkan dalam bentuk format PDF/Image')
        return;
    }
    filePasien.value = file


}
const onUpload = () => {

}
const kembaliKeun = () => {
    showInput.value = false
    input.value = {
        tanggal: new Date()
    }
}
const simpan = async () => {
    if(props.registrasi.kelompokpasien.toLowerCase().indexOf('bpjs') > - 1){

      cariDokter.value = dataDokter.value.find(e=> e.kodedokter==input.value.dokter.kddokterbpjs)

      if(cariDokter.value == undefined) {
        H.alert('error', 'Dokter tidak ada jadwal')
        return
      }

      confirm.require({
          message: 'Silakan pilih jenis SKDP yang ingin dibuat?',
          header: 'Konfirmasi SKDP',
          icon: 'pi pi-info-circle',
          acceptClass: 'p-button-danger',
          rejectClass: 'is-light mx-2',
          accept: () => {
              input.value.isAntrean = true
              insertRencanKontrolBPJS(
        props.registrasi.nosep,
        cariDokter.value.kodedokter,
        input.value.poli.kdsubspesialisbpjs,
        input.value.tanggal
      );
          },
          reject: () => {
            input.value.isAntrean = false
            insertRencanKontrolBPJS(
        props.registrasi.nosep,
        cariDokter.value.kodedokter,
        input.value.poli.kdsubspesialisbpjs,
        input.value.tanggal
      );
          },
          acceptLabel: 'Dengan Antrian Online (MJKN)',
          rejectLabel: 'Hanya SKDP tanpa ambil antrian',
      })
    }else{
      H.alert('info', 'Ini adalah pasien Non BPJS')
    }
}

const simpanPerjanjian = async () => {
  if (!input.value.poli) {
        H.alert('error', 'Poli harus di isi')
        return
    }
    if (!input.value.dokter) {
        H.alert('error', 'Dokter harus di isi')
        return
    }

    let jsonSave = {
        norec: input.value.norec !== undefined ? input.value.norec : '',
        nocm: props.pasien.nocm,
        norec_pd: props.registrasi.norec_pd,
        diagnosa: input.value.diagnosa !== undefined ? input.value.diagnosa : '',
        indikasi: input.value.indikasi !== undefined ? input.value.indikasi : '',
        objectdokterfk: input.value.dokter.value,
        tanggalperiksa: H.formatDate(input.value.tanggal, 'YYYY-MM-DD'),
        tglperjanjian: H.formatDate(input.value.tanggal, 'YYYY-MM-DD HH:mm'),
        kdsubspesialis: input.value.poli.kdsubspesialisbpjs,
        nomorkartu: props.pasien.nobpjs,
        nik: props.pasien.noidentitas,
        nohp: props.pasien.nohp,
        jumlahkujungan: null,
        keterangan: input.value.keteranganLainnya !== undefined ? input.value.keteranganLainnya : '-',
        objectruanganfk: input.value.poli.value,
        method: 'save'
    }

    await useApi().post('/emr/simpan-perjanjian', jsonSave).then((r) => {
        isLoading.value = false
        if (r.perjanjian != undefined && r.perjanjian != null) {
            const jsonCarePlan = {
                norec_pp: r.perjanjian.norec
            }
            useApi().postNoMessage('/bridging/satusehat/CarePlan', jsonCarePlan).then((resp:any) => {})
        }
        
        loadRiwayat()
        showInput.value = false
        emit('close-modal-skdp')
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const insertRencanKontrolBPJS = (noSep: any, kdDokter: any, kdPoli: any, Tglkontrol: any) => {
  const now = new Date();
      const inputDate = new Date(input.value.tanggal);
      const differenceInTime = inputDate.getTime() - now.getTime();
      const differenceInDays = differenceInTime / (1000 * 3600 * 24); // Convert milliseconds to days
  if (differenceInDays > 30 && input.value.isAntrean) {
        H.alert('error', 'Limit hari tanggal pelayanan adalah 30, jika ingin tetap ada skdp silakan pilih tanpa ambil antrean');
        return;
      }
    if(!noSep){
        H.alert('info', 'Rencana kontrol bpjs belum otomatis terbuat karna SEP pasien kosong')
        return
    }
    if(!kdDokter){
        H.alert('info', 'Rencana kontrol bpjs belum otomatis terbuat karna Dokter belum dimapping')
        return
    }
    if(!kdPoli){
        H.alert('info', 'Rencana kontrol bpjs belum otomatis terbuat karna Poli belum dimapping')
        return
    }
    let tglRencanaKontrol = H.formatDate(Tglkontrol, 'YYYY-MM-DD')
    let data = {
        "url": "RencanaKontrol/insert",
        "method": "POST",
        "data": {
            "request": {
                "noSEP": noSep,
                "kodeDokter": `${kdDokter}`,
                "poliKontrol": kdPoli,
                "tglRencanaKontrol": tglRencanaKontrol,
                "user": H.namaPegawai()
            }
        }
    }

    // brdiging untuk rencana kontrol
    useApi().postBPJS('/bridging/bpjs/tools', data).then(async function (x) {
      let status
        if(x.metaData.code == 200){
          let jsonAddAntrean = {
            nomorkartu: props.pasien.nobpjs,
            nik: props.pasien.noidentitas,
            nohp: props.pasien.nohp,
            kodepoli: input.value.poli.kdsubspesialisbpjs,
            norm: props.pasien.nocm,
            tanggalperiksa: H.formatDate(input.value.tanggal, 'YYYY-MM-DD'),
            kodedokter: input.value.dokter.kddokterbpjs,
            jeniskunjungan: 3,
            nomorreferensi: x.response.noSuratKontrol
          }

          if(input.value.isAntrean){
            useApi().postBPJS('/bridging/antrol/antrean', jsonAddAntrean)
            .then((antrol: any) => {
                try {
                    const jsonAntrol = {
                      "kodebooking": antrol.response.kodebooking,
                      "kodedokter": cariDokter.value.kodedokter,
                      "namadokter": cariDokter.value.namadokter,
                      "jampraktek": cariDokter.value.jadwal,
                      "jeniskunjungan": 3,
                      "nomorreferensi": x.response.noSuratKontrol,
                    }
                    useApi().postNoMessage(`/bridging/antrol/sendDataAntrean`, jsonAntrol).then(async (response: any) => {
                    }).catch((e: any) => {
                      status = false
                      console.log(e)
                    }) // Pastikan ini ada
                  } catch (error) {
                    console.error('Error di blok then:', error);
                  }
            })
            .catch((err) => {
              console.error('Error yang terjadi:', err);
              H.alert('info', 'Terjadi kesalahan, silakan coba lagi.');
            });
          }

          await simpanPerjanjian()

        }
        if (x.metaData.code == 201) {
          H.alert('error', x.metaData.message, '',10000)
            let jsonGet = {
                "url": `jadwaldokter/kodepoli/${kdPoli}/tanggal/${tglRencanaKontrol}`,
                "jenis": "antrean",
                "method": "GET",
                "data": null
            }
            useApi().postBPJS('/bridging/bpjs/tools', jsonGet).then(function (y) {
              console.log(y)
                if (y.metaData.code == 200) {
                    let listDPJP = y.metada.response;
                    let randDpjp = listDPJP[Math.floor(Math.random() * listDPJP.length)]
                    insertRencanKontrolBPJS(noSep, randDpjp.kodedokter, kdPoli, Tglkontrol)
                }
            })
        }

        if (x.metaData.code == 203) {
            let bulan = H.formatDate(Tglkontrol, 'MM')
            let tahun = H.formatDate(Tglkontrol, 'YYYY')
            let jsonGet = {
                "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${tahun}/Nokartu/${props.pasien.nobpjs}/filter/2`,
                "method": "GET",
                "data": null
            }
            H.alert('error', x.metaData.message, '',10000)
            useApi().postBPJS('/bridging/bpjs/tools', jsonGet).then((z) => {
                for (let i = 0; i < z.data.response.list.length; i++) {
                    const element = z.data.response.list[i];
                    if (element.noSepAsalKontrol == props.registrasi.nosep) {
                        let jsonDel = {
                            "url": "RencanaKontrol/Delete",
                            "method": "DELETE",
                            "data": {
                                "request": {
                                    "t_suratkontrol": {
                                        "noSuratKontrol": element.noSuratKontrol,
                                        "user": H.pegawaiLogin().namaLengkap
                                    }
                                }
                            }
                        }
                        useApi().postBPJS('/bridging/bpjs/tools', jsonDel).then((d) => {
                            if (d.data.metaData.code == 200) {
                                insertRencanKontrolBPJS(noSep, kdDokter, kdPoli, Tglkontrol)
                            }
                        });
                        break;
                    }
                }
            })
        }

    })
}

const deleteRencanKontrolBPJS = () => {
    let bulan = H.formatDate(input.value.tanggal, 'MM')
    let tahun = H.formatDate(input.value.tanggal, 'YYYY')
    let jsonGet = {
        "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${tahun}/Nokartu/${props.pasien.nobpjs}/filter/2`,
        "method": "GET",
        "data": null
    }
    useApi().postBPJS('/bridging/bpjs/tools', jsonGet).then((z) => {
        for (let i = 0; i < z.data.response.list.length; i++) {
            const element = z.data.response.list[i];
            if (element.noSepAsalKontrol == props.registrasi.nosep) {
                let jsonDel = {
                    "url": "RencanaKontrol/Delete",
                    "method": "DELETE",
                    "data": {
                        "request": {
                            "t_suratkontrol": {
                                "noSuratKontrol": element.noSuratKontrol,
                                "user": H.pegawaiLogin().namaLengkap
                            }
                        }
                    }
                }
                useApi().postBPJS('/bridging/bpjs/tools', jsonDel).then((d) => { });
                break;
            }
        }
    })
}
const edit = async (e: any) => {

    input.value.norec = e.norec
    input.value.diagnosa = e.diagnosa
    input.value.indikasi = e.indikasi
    input.value.keteranganLainnya = e.keterangan
    input.value.tanggal = new Date(e.tglperjanjian)

    d_Pegawai.value.forEach((element: any) => {
        if (e.objectdokterfk == element.value) {
            input.value.dokter = element
        }
    });
    d_Poli.value.forEach((element: any) => {
        if (e.objectruanganfk == element.value) {
            input.value.poli = element
        }
    });


    showInput.value = true
}
const hapus = async (e: any) => {
    e.loadingHapus = true
    await useApi().post(
        `/emr/hapus-perjanjian`, {
        'norec': e.norec,
    }).then((response: any) => {
        e.loadingHapus = false
        loadRiwayat()
    })
}
// const fetchPoli = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/ruangan_m?select=id,namaruangan,kdsubspesialisbpjs&param_search=namaruangan&query=${filter.query}&settingdatafix=objectdepartemenfk,kdDepartemenRawatJalanFix&limit=10`
//     ).then((response) => {
//         d_Poli.value = response
//     })
// }
// const fetchPegawai = async (filter: any) => {
//     await useApi().get(
//         `emr/dropdown/pegawai_m?select=id,namalengkap,kddokterbpjs&param_search=namalengkap&query=${filter.query}&settingdatafix=objectjenispegawaifk,idJenisPegawaiDokter&limit=10`
//     ).then((response) => {
//         d_Pegawai.value = response
//     })
// }
const lihat = async (e: any) => {
    H.openFile('berkaspasien/' + e.nocm + '/' + e.namafile);
}
const cetak = (e: any) => {
    H.printBlade('emr/cetak-surat-kontrol?norec=' + e.objectsuratfk + '&noregistrasi=' + props.registrasi.noregistrasi + '&pdf=true');

}
const cetakSKD = () => {
    input.value = {
        tanggal: new Date(),
        nobpjs : props.pasien.nobpjs,
        nosep : props.registrasi.nosep,
        tahun: {
            kode: new Date().getFullYear(),
            nama: new Date().getFullYear(),
        },
        filter: d_Filter.value[0]
        }
    modalInput.value = true
}
const cariSKD = () => {
    if(!input.value.nobpjs){
        H.alert('error', 'No Kartu Harus di isi')
        return
    }
    if(!input.value.bulan){
        H.alert('error', 'Bulan Harus di isi')
        return
    }
    let bulan = input.value.bulan.kode + 1

    bulan = bulan.toString().length == 1 ? '0' + bulan.toString() : bulan
    let json = {
        "url": `RencanaKontrol/ListRencanaKontrol/Bulan/${bulan}/Tahun/${input.value.tahun.kode}/Nokartu/${input.value.nobpjs}/filter/${input.value.filter.kode}`,
        "method": "GET",
        "data": null
    }
    isLoadingSKD.value = true
    useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
        isLoadingSKD.value = false
        if (x.metaData.code == 200) {
            dataSourceSPRI.value = x.response.list
        } else {
            H.alert('error', x.metaData.message)
        }
    })
}

const cetakSPRI = (e: any) => {
    let nosuratkontrol = e.noSuratKontrol
    let tglrencanakontrol = e.tglRencanaKontrol
    let txttglentrirencanakontrol = e.tglTerbitKontrol
    let noka = e.noKartu
    let nama = props.pasien.namapasien
    let tl = props.pasien.tgllahir
    let arr = tl.split("-");
    let tglL = arr[2] + '-' + arr[1] + '-' + arr[0]
    let tgllahir = H.formatDate(new Date(tglL), 'YYYY-MM-DD')
    let namaPoliTujuan = e.namaPoliTujuan
    let jeniskelamin = props.pasien.jeniskelamin
    let jnsKontrol = e.jnsKontrol
    let namaDokter = e.namaDokter
    let kddx = '-'
    let nmdpjpsepasal = props.registrasi.dokter ? props.registrasi.dokter : '-'
    let iddok = props.registrasi.objectpegawaifk ? props.registrasi.objectpegawaifk : 'null'
    let dxawal = '-'

    if (e.noSepAsalKontrol != null) {
        let json = {
            "url": "sep/" + e.noSepAsalKontrol,
            "method": "GET",
            "data": null
        }
        useApi().postBPJS('/bridging/bpjs/tools', json).then((x) => {
            if (x.metaData.code == 200) {
                dxawal = x.response.diagnosa

                cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
                    nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
                    jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);


            } else {
                H.alert('error', x.metaData.message);
            }
        })

    } else {
        dxawal = '-'
        cetakBladeSKDP(nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
            nama, tgllahir, namappkRumahSakit.value, namaPoliTujuan, jeniskelamin, dxawal,
            jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok);

    }
}
const cetakBladeSKDP = (nosuratkontrol, tglrencanakontrol, txttglentrirencanakontrol, noka,
    nama, tgllahir, namappkRumahSakit, namaPoliTujuan, jeniskelamin, dxawal, jnsKontrol, kddx, namaDokter, nmdpjpsepasal, iddok) => {


    H.printBlade('emr/cetak-spri?nosuratkontrol='
        + nosuratkontrol + '&tglrencanakontrol=' + tglrencanakontrol + '&txttglentrirencanakontrol=' + txttglentrirencanakontrol
        + '&noka=' + noka
        + '&tgllahir=' + tgllahir
        + '&namappkRumahSakit=' + namappkRumahSakit
        + '&namaPoliTujuan=' + namaPoliTujuan
        + '&jeniskelamin=' + jeniskelamin
        + '&dxawal=' + dxawal
        + '&jnsKontrol=' + jnsKontrol
        + '&kddx=' + kddx
        + '&namaDokter=' + namaDokter
        + '&nmdpjpsepasal=' + nmdpjpsepasal
        + '&iddok=' + iddok
        + '&nama=' + nama);
}

watch(
  () => input.value.tanggal,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
        fetchJadwalDokter()
    }
  }
)

watch(
  () => input.value.poli,
  (newValue, oldValue) => {
    if (newValue != oldValue) {
        fetchJadwalDokter()
    }
  }
)


onMounted(() => {

});

add()
loadDrop()
loadRiwayat()
</script>
<style  lang="scss">
@import '/@src/scss/abstracts/all';

.tile-grid {
    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }
}

.is-dark {
    .tile-grid {
        .tile-grid-item {
            @include vuero-card--dark;
        }
    }
}

.tile-grid-v1 {
    .tile-grid-item {
        @include vuero-s-card;

        border-radius: 14px;
        padding: 16px;

        .tile-grid-item-inner {
            display: flex;
            align-items: center;

            .meta {
                margin-left: 10px;
                line-height: 1.2;

                span {
                    display: block;
                    font-family: var(--font);

                    &:first-child {
                        color: var(--dark-text);
                        font-family: var(--font-alt);
                        font-weight: 600;
                        font-size: 1rem;
                    }

                    &:nth-child(2) {
                        color: var(--light-text);
                        font-size: 0.9rem;
                    }
                }
            }

            .dropdown {
                position: relative;
                margin-left: auto;
            }
        }
    }
}
</style>
