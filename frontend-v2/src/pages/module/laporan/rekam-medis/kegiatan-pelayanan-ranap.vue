<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.1</label>
                        <label for="">KEGIATAN PELAYANAN RAWAT INAP</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" groupRowsBy="group"
                    rowGroupMode="subheader">
                    <template #header>
                        <div class="columns is-multiline pb-3" style="justify-content: space-between;">
                            <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel"
                                class="mt-5 ml-3">
                                Export To Excel
                            </VButton>
                            <div class="column is-2">
                                <VField label="Periode">
                                    <Calendar v-model="item.year" view="year" dateFormat="yy" showIcon class="modif"
                                        @date-select="fetchData" />
                                </VField>
                            </div>
                        </div>
                    </template>
                    <ColumnGroup type="header">
                        <Row>
                            <Column header="No" style="min-width: 15px; text-align: center;" :rowspan="2" />
                            <Column header="JENIS PELAYANAN" style="min-width: 80px; text-align: center;" :rowspan="2" />
                            <Column header="PASIEN AWAL TAHUN" style="text-align: center;" :rowspan="2" />
                            <Column header="PASIEN MASUK" style="text-align: center" :rowspan="2" />
                            <Column header="PASIEN KELUAR HIDUP" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="PASIEN KELUAR MATI" style="text-align: center;"
                                :colspan="2" />
                            <Column header="JUMLAH LAMA DIRAWAT" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="PASIEN AKHIR TAHUN" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="JUMLAH HARI PERAWATAN" style="text-align: center;"
                                :rowspan="2" />
                            <Column header="RINCIAN HARI PERAWATAN PERKELAS" style="text-align: center;"
                                :colspan="6" />
                        </Row>
                        <Row>
                            <Column header="< 48 JAM" style="text-align: center;" />
                            <Column header="> 48 JAM" style="text-align: center;" />
                            <Column header="VIP B" style="text-align: center;" />
                            <Column header="VIP A" style="text-align: center;" />
                            <Column header="I" style="text-align: center;" />
                            <Column header="II" style="text-align: center;" />
                            <Column header="III" style="text-align: center;" />
                            <Column header="NON KELAS" style="text-align: center;" />
                        </Row>
                    </ColumnGroup>
                    <Column field="no" style="min-width: 10px; text-align:center" />
                    <Column field="jenis_spesialisasi" style="min-width: 80px;" />
                    <Column field="pasienawaltahun" style="min-width: 50px;" />
                    <Column field="pasienmasuk"/>
                    <Column field="pasienkeluarhidup"/>
                    <Column field="matikurang48jam"/>
                    <Column field="matilebih48jam"/>
                    <Column field="lamadirawat"/>
                    <Column field="pasienakhirtahun"/>
                    <Column field="jumlahharidirawat"/>
                    <Column field="rincianvipb"/>
                    <Column field="rincianvipa"/>
                    <Column field="rinciankel1"/>
                    <Column field="rinciankel2"/>
                    <Column field="rinciankel3"/>
                    <Column field="rinciannonkelas"/>
                </DataTable>
            </VCard>
        </div>
    </section>
</template>
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive } from 'vue'
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import ColumnGroup from 'primevue/columngroup';
import DataTable from 'primevue/datatable'
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import AutoComplete from 'primevue/autocomplete';
import Row from 'primevue/row';
import * as XLSX from "xlsx";
import * as XLSXStyle from 'xlsx-js-style';
import * as H from '/@src/utils/appHelper'
import Column from 'primevue/column'
import { useViewWrapper } from '/@src/stores/viewWrapper'

// app.directive('tooltip', Tooltip);

useHead({
    title: 'Laporan Indikator Pelayanan Rumah Sakit - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    year: new Date(),
})

const confirm = useConfirm()
const dataSource: any = ref([])
const modalDetail: any = ref(false)
const modalEdit: any = ref(false)
let d_KelompokPasien: any = ref([])
let loadSearch: any = ref(false)
let loadData: any = ref(false)
let loadSave: any = ref(false)
let isLoading: any = ref(false)

const fetchData = async () => {
    
    loadSearch.value = true
    await useApi().get(`laporan/indikator-pelayanan-ranap?tahun=${H.formatDate(item.value.year, 'YYYY')}`).then((response) => {
        response.data.forEach((element:any,i:any) => {
            element.no = i + 1
        });
        dataSource.value = response.data
    })
    loadData.value = false
    loadSearch.value = false
}


const exportExcel = () => {
    const workbook = XLSX.utils.book_new();
    const worksheet = XLSX.utils.aoa_to_sheet([
        ['Laporan Pelayanan Ranap'],
        [],
        ['NO', 'JENIS PELAYANAN', 'PASIEN AWAL TAHUN', 'TAHUN', 'BOR', 'LOS', 'BTO', 'TOI', 'NDR', 'GDR', 'RATA-RATA KUNJUNGAN / HARI'],
        ...dataSource.value.map((e: any) => [
            e.koders,
            e.kodeprov,
            e.kota,
            e.tahun,
            e.bor,
            e.alos,
            e.bto,
            e.toi,
            e.ndr,
            e.gdr,
            e.ratarataperhari,
        ]),
    ]);
    // Mendefinisikan style untuk header(centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            color: { rgb: 'FFFFFF' }
        },
        fill: { fgColor: { rgb: '807C7C' } }
    };

    // Mendefinisikan range header
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        worksheet[headerCell].s = headerStyle;
    }

    const columnWidths = [10, 10, 15, 10, 10, 10, 10, 10, 10, 10, 18];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center'
        },
        font: {
            bold: true,
            sz: 18
        }
    };

    // Menggabungkan dua baris pertama
    const mergeTitle = { s: { r: 0, c: 0 }, e: { r: 1, c: 10 } };
    worksheet['!merges'] = [mergeTitle];

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan Indikator', true);
    XLSXStyle.writeFile(workbook, 'RL 1.2 Indikator Pelayanan RS.xlsx');
}

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
    margin-top: 0px;
    font-weight: 600;
}

.btn-search {
    display: flex;
    align-items: center;
}</style>
