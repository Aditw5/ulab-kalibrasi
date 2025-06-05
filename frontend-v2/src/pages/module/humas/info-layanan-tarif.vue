<template>
    <ConfirmDialog />
    <div class="column is-12">
        <VCard style="padding-bottom: 0px">
            <div class="column c-title pt-2 mb-3">
                <label class="title-page">Pencarian</label>
            </div>
            <div class="column is-12">
                <div class="columns">
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Produk">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.produk" @select="fetchData()" :options="d_Produk"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Ruangan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.ruangan" @select="fetchData()" :options="d_ruangan"
                                    placeholder="Pilih Barang" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-2">
                        <VField class="is-autocomplete-select" label="Kelas">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.kelas" @select="fetchData()" :options="d_Kelas"
                                    placeholder="Pilih " :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField class="is-autocomplete-select" label="Jenis Layanan">
                            <VControl icon="feather:search">
                                <Multiselect mode="single" v-model="item.jenispelayanan" @select="fetchData()" :options="d_JenisPelayanan"
                                    placeholder="Pilih" :searchable="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column btn-search">
                        <VIconButton v-tooltip.bottom.left="'Cari Data'" label="Bottom Right" color="primary" circle
                            icon="pi pi-search" @click="fetchData()" :loading="item.loading" />
                        <!-- <VButton type="button" icon="feather:search" @click="fetchData()">
                            Cari Data
                        </VButton> -->
                    </div>

                </div>
            </div>
        </VCard>
    </div>

    <div class="column is-12">
        <VCard>
            <div class="column c-title pt-2 mb-0">
                <div class="columns p-2">
                    <div class="column is-10">
                        <label class="title-page">Info Layanan Tarif</label>
                    </div>
                </div>
            </div>

            <div class="column is-12 mt-5">
                <DataTable :value="dataSourcefiltered" :rows="5" :rowsPerPageOptions="[5, 10, 15]" :loading="isLoading"
                    class="p-datatable-sm" responsiveLayout="stack" breakpoint="960px" selectionMode="single"
                    sortMode="multiple" showGridlines
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}">

                    <Column field="no" header="No" style="text-align: center;" />
                    <Column field="namaproduk" header="Layanan" />
                    <Column field="namakelas" header="Kelas" />
                    <Column field="namaruangan" header="Ruangan" />
                    <Column field="jenispelayanan" header="Jenis Pelayanan" />
                    <Column field="hargalayanan" header="Harga Layanan">
                        <template #body="slotProps">
                            {{ H.formatRp(slotProps.data.hargalayanan, 'Rp. ') }}
                        </template>
                    </Column>
                    <Column field="namarekanan" header="Penjamin" />
                    <Column field="hargadijamin" header="Harga Dijamin" />


                </DataTable>
            </div>
        </VCard>
    </div>
</template>
    
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed, onMounted } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset';
import moment from 'moment'
import * as H from '/@src/utils/appHelper'
import ConfirmDialog from 'primevue/confirmdialog'
import Dialog from 'primevue/dialog';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown'
import Column from 'primevue/column'


import { useViewWrapper } from '/@src/stores/viewWrapper'
import { watch } from 'vue';
import { booleanTypeAnnotation } from '@babel/types';

useHead({
    title: 'Info Layanan Tarif- ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const item: any = ref({
    aktif: true,
    isKK: false,
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const currentPage: any = ref({
    limit: 10,
    rows: 50
})

const router = useRouter()
let isLoadingBtn: any = ref(false)
let idSatuanAsal: any
const confirm = useConfirm()
const d_ruangan = ref([])
const d_Kelas: any = ref([])
const dataSource: any = ref([])
const detailPenerimaan: any = ref([])
const modalDetail: any = ref(false)
const filters = ref('')
const route = useRoute()
let d_Produk: any = ref([])
let d_JenisPelayanan: any = ref([])
const expandedRows = ref();
let d_Rekanan: any = ref([])
let isLoading: any = ref(false)

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.namapasien.match(new RegExp(filters.value, 'i'))
        )
    })
})

const listDataCombo = async () => {
    await useApi().get('/humas/combo-cari').then((response) => {
        d_Produk.value = response.produk.map((e: any) => { return { label: e.namaproduk, value: e.id } })
        d_ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id } })
        d_Kelas.value = response.kelas.map((e: any) => { return { label: e.namakelas, value: e.id } })
        d_JenisPelayanan.value = response.jenispelayanan.map((e: any) => { return { label: e.jenispelayanan, value: e.id } })
    })
}

const fetchData = async () => {
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (offset * limit) - limit
    let idproduk = item.value.produk ? `&idproduk=${item.value.produk}` : ''
    let idruangan = item.value.ruangan ? `&idruangan=${item.value.ruangan}` : ''
    let idkelas = item.value.kelas ? `&idkelas=${item.value.kelas}` : ''
    let jenispelayananid = item.value.jenispelayanan ? `&jenispelayananid=${item.value.jenispelayanan}` : ''
    // let noRetur = item.value.noretur ? `&noretur=${item.value.noretur}` : ''
    isLoading.value = true
    await useApi().get('/humas/data-info-layanan?' + '&offset=' + offset
        + '&limit=' + limit
        + '&idproduk=' + idproduk
        + '&idruangan=' + idruangan
        + '&idkelas=' + idkelas
        + '&rows=' + currentPage.value.rows).then((response) => {
            response.data.forEach((element: any, i: any) => {
                element.no = i + 1
            });
            isLoading.value = false
            dataSource.value = response.data
        })

}

listDataCombo()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/sysadmin/master-data.scss';

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
    margin-top: 14px;
}
</style>
