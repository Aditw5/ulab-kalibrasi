<template>
    <div class="columns">
        <div class="column">
            <VCard>
                <div class="columns">
                    <div class="column">
                        <h3 class="title is-5 mb-2 mr-1">Daftar Klaim Pasien</h3>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-3">
                        <VField label="Tanggal">
                            <VDatePicker v-model="item.periode" is-range color="pink" trim-weeks>
                                <template #default="{ inputValue, inputEvents }">
                                    <VField addons>
                                        <VControl icon="feather:calendar">
                                            <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                        </VControl>
                                        <VControl>
                                            <VButton static icon="feather:arrow-right" />
                                        </VControl>
                                        <VControl subcontrol icon="feather:calendar">
                                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-4 mt-5">
                        <VField>
                            <VControl icon="feather:search">
                                <input v-model="item.search" v-on:keyup.enter="fetchData()" type="text" class="input"
                                placeholder="Cari No RM / No Registrasi / No BPJS / No SEP / Nama Pasien" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2 flex mt-5">
                        <VControl raw subcontrol>
                            <VCheckbox v-model="item.ischecktglpulang" label="Filter By Tgl Pulang" color="success" />
                        </VControl>
                        <VIconButton type="button" color="success" circle raised icon="fas fa-search" @click="fetchData()"
                            :loading="isLoading" class=" is-pulled-">
                        </VIconButton>
                        <VIconButton circle class="ml-2  is-pulled-" icon="fas fa-filter" raised bold @click="modalFilter = true"
                            v-tooltip.bubble="'Filter'">
                        </VIconButton>
                        <Badge :value="jmlFilter" v-if="jmlFilter > 0" severity="info" class="is-pulled-"
                            style="margin-left:-10px ;z-index: 100;  position: relative; "></Badge>
                    </div>
                    <div class="column is-2 mt-5 flex">
                        <VButton color="primary" raised outlined rounded icon="fas fa-clipboard-list" :loading="isLoading"
                            class="w-100" @click="collectDaftar()">Collect All
                        </VButton>
                        <VButton color="primary" raised outlined rounded icon="fas fa-download" :loading="isLoading"
                            class="w-100 ml-2" @click="downloadAll()">Download All
                        </VButton>
                    </div>
                    <div class="column is-12 mt-0 pt-0" v-if="persenProgress">
                        <!-- <span>Pasien Tercollect {{ persenKlaim.toFixed(2) }} %</span>
                        <VProgress
                            :color="persenKlaim < 33 ? 'danger' : (persenKlaim >= 33 && persenKlaim < 70 ? 'warning' : 'success')"
                            size="medium" :value="persenKlaim" /> -->
                            <ProgressBar :value="persenKlaim.toFixed(2)" />
                    </div>
                </div>

            </VCard>
        </div>
    </div>
    <div class="columns">
        <div class="column is-12">
            <VPlaceloadWrap v-if="isLoadingTable">
                <VPlaceload height="500px" width="100%" />
            </VPlaceloadWrap>
            <VCard v-else>
                <DataTable :value="dataSource" :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 25]" scrollable :loading="isLoadingTable"
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                    currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                    class="p-datatable-sm">
                    <template #empty style="text-align: center;"> No data found. </template>
                    <Column header="#" frozen style="min-width: 50px">
                        <template #body="{ data }">
                            <VIconButton color="primary" circle icon="feather:list" class="mr-2" v-tooltip="`Detail`" :loading="isLoading" @click="detailklaim(data)" />
                            <VIconButton v-if="data.dokumen[1].doc" color="danger" circle icon="fas fa-file-pdf" class="mr-2" v-tooltip="'Lihat Dokumen'" :loading="isLoading" @click="bundleDok(data.noregistrasi)" />
                            <VIconButton v-else color="info" circle icon="fas fa-download" class="mr-2" v-tooltip="'Collect Dokumen'" :loading="isLoading" @click="collectAll(data)" />
                        </template>
                    </Column>
                    <Column field="tglregistrasi" header="Tgl Registrasi" style="min-width: 110px" frozen></Column>
                    <Column field="tglpulang" header="Tgl Pulang" style="min-width: 110px" frozen></Column>
                    <Column field="noregistrasi" header="No Registrasi" style="min-width: 110px" frozen></Column>
                    <Column field="nocm" header="No RM" style="min-width: 80px" frozen></Column>
                    <Column field="namapasien" header="Nama Pasien" style="min-width: 250px" frozen></Column>
                    <Column field="jeniskelamin" header="Jenis Kelamin" style="min-width: 100px"></Column>
                    <Column field="tgllahir" header="Tgl Lahir" style="min-width: 100px"></Column>
                    <Column field="nobpjs" header="No BPJS" style="min-width: 100px"></Column>
                    <Column field="nosep" header="No SEP" style="min-width: 100px"></Column>
                    <Column field="inacbg_totalgrouper" header="Total Grouping" style="min-width: 150px">
                        <template #body="slotProps">
                            <span>{{  H.formatRupiah(slotProps.data.inacbg_totalgrouper, "Rp. ")  }}</span>
                        </template>
                    </Column>
                    <Column field="namaruangan" header="Poli / Unit" style="min-width: 200px"></Column>
                    <Column field="tglmasuk" header="Tanggal Masuk" style="min-width: 200px"></Column>
                    <Column field="bed" header="Nama Kamar dan Bed" style="min-width: 200px"></Column>
                    <Column field="dokter" header="Dokter" style="min-width: 200px"></Column>
                    <Column field="status" header="Status" style="min-width: 200px">
                        <template #body="slotProps">
                            <VTag color="primary" :label="slotProps.data.status" />
                        </template>
                    </Column>
                    <Column field="ulasan" header="Ulasan" style="min-width: 150px"></Column>
                </DataTable>
            </VCard>
        </div>
    </div>
    <Dialog v-model:visible="modalFilter" modal header="Filter" :style="{ width: '40vw' }">
        <div class="columns is-multiline">
            <div class="column is-6">
                <VField label="Instalasi" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                    <VControl icon="fas fa-archway" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.qInstalasi" :options="d_Departemen" :optionLabel="'namadepartemen'"
                            class="is-rounded" placeholder="Instalasi" style="width: 100%;" :filter="true"
                            @change="changeInst($event)" showClear />
                    </VControl>
                </VField>
            </div>
            <div class="column is-6">
                <VField label="Ruangan" class="is-rounded-select is-autocomplete-select
                              mt-0 pt-0" v-slot="{ id }">
                    <VControl icon="fas fa-home" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.qRuangan" :options="d_Ruangan" :optionLabel="'namaruangan'"
                            class="is-rounded" placeholder="Ruangan" style="width: 100%;" :filter="true" showClear />
                    </VControl>
                </VField>
            </div>
        </div>
        <template #footer>
            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="clearFilter()">
                Bersihkan
            </VButton>
            <VButton type="button" rounded outlined color="primary" raised icon="feather:filter" :loading="isLoading"
                @click="terapkanFilter()"> Terapkan
            </VButton>
        </template>
    </Dialog>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Toolbar from 'primevue/toolbar'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import * as H from '/@src/utils/appHelper'
import AutoComplete from 'primevue/autocomplete'
import Dialog from 'primevue/dialog';
import { useCurrencyInput } from 'vue-currency-input'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import ProgressBar from 'primevue/progressbar';
import Badge from 'primevue/badge';
useHead({
    title: import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)
let item: any = reactive({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const router = useRouter()
const dataSource: any = ref([])
const modalFilter: any = ref(false)
const isLoading: any = ref(false)
const isLoadingTable: any = ref(false)
const persenProgress: any = ref(false)
const d_Departemen: any = ref([])
const d_Ruangan: any = ref([])
const jmlFilter: any = ref(0)
const persenKlaim: any = ref(0)
const fetchDropdown = async () => {
    await useApi().get('/bridging/inacbgs/dropdown').then((r) => {
        d_Departemen.value = r.departemen
    })
    fetchData()
}
const bundleDok = (e: any) => {
    H.open('bridging/inacbgs/bundle-dokumen?noregistrasi=' + e);
}

const downloadAll = async () => {
    if (!item.periode) {
        H.alert("error", "Harap pilih tanggal terlebih dahulu !");
        return;
    }

    isLoading.value = true;

    const dari = H.formatDate(item.periode.start, 'YYYY-MM-DD');
    const sampai = H.formatDate(item.periode.end, 'YYYY-MM-DD');

    const daftarRegistrasi = []
    dataSource.value.forEach(element => {
      daftarRegistrasi.push({registrasi : element.noregistrasi})
    });

    try {
        const response = await useApi().post('bridging/inacbgs/bundle-all-documents', {
            start_date: dari,
            end_date: sampai,
            isPulang : item.ischecktglpulang,
          data: daftarRegistrasi,
        }, { responseType: 'blob' });

        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'all_documents_' + dari + '_to_' + sampai + '.zip');
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    } catch (error) {
        H.alert("error", "Gagal mengunduh dokumen. Silakan coba lagi.");
    } finally {
        isLoading.value = false;
    }
};

const fetchData = async () => {
    if (!item.periode) {
        H.alert("error", "Harap pilih tanggal terlebih dahulu !")
        return
    }
    const dari = H.formatDate(item.periode.start, 'YYYY-MM-DD')
    const sampai = H.formatDate(item.periode.end, 'YYYY-MM-DD')

    let search = ''
    ,ispulang = ''
    ,ruanganid = ''
    ,depid = ''

    if(item.search)
        search = item.search

    if(item.ischecktglpulang)
        ispulang = item.ischecktglpulang

    jmlFilter.value = 0

    if(item.qInstalasi) {
        depid = item.qInstalasi.id
        jmlFilter.value += 1
    }

    if(item.qRuangan) {
        ruanganid = item.qRuangan.id
        jmlFilter.value += 1
    }

    isLoadingTable.value = true
    dataSource.value = []
    const response = await useApi().get(
        '/bridging/klaim/get-daftar-klaim?tglawal='+ dari +'&tglakhir=' + sampai
        + '&search=' + search
        + '&ispulang=' + ispulang
        + '&ruanganid=' + ruanganid
        + '&depid=' + depid
    )
    isLoadingTable.value = false
    dataSource.value = response
}
const detailklaim = (data: any) => {
    router.push({
    name: 'module-klaim-asuransi-detail-klaim-bpjs',
        query: {
            nocmfk: data.nocmfk,
            norec_pd: data.norec_pd,
        }
    })
}
const changeInst = (e: any) => {
    d_Ruangan.value = e.value ? e.value.ruangan : []
}
const terapkanFilter = () => {
    fetchData()
    modalFilter.value = false
}
const clearFilter = () => {
    delete item.qRuangan
    delete item.qInstalasi
    fetchData()
    modalFilter.value = false
}

let data = 0;
const collectDaftar = () => {
  persenProgress.value = true
  isLoading.value = true
  data = 1
  persenKlaim.value = 0
  dataSource.value.forEach( element => {
    collectAll(element)
  })
  // persenProgress.value = false
}

const collectAll = async (e: any) => {
  persenProgress.value = true
  await useApi().post('/bridging/inacbgs/collect-semua-dokumen', {
        'noregistrasi': e.noregistrasi,
        'dokumen': e.dokumen
    }).then((r) => {
      persenKlaim.value = (data/dataSource.value.length)*100
      if(data == dataSource.value.length){
        persenProgress.value = false
        isLoading.value = false
        fetchData()
      }
      data++;
    }).catch((er) => {
      data++;
    }).finally(()=>{
      isLoading.value = false
    })
}

onMounted(() => {
    fetchDropdown()
})

</script>
<style lang="scss">
.text-center {
  justify-content: center;
  tect-align: center;
}
</style>
