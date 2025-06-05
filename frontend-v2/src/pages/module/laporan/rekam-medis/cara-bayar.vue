<template>
    <section>
        <div class="column is-12">
            <VCard>
                <div class="column c-title pt-2 mb-0">
                    <div class="column is-10 p-0">
                        <label class="title-page">Formulir RL 3.15</label>
                        <label for="">CARA BAYAR</label>
                    </div>
                </div>

                <VPlaceload height="20rem" width="100%" class="mx-2 mt-4" v-if="loadData" />
                <DataTable v-else :rows="5" :value="dataSource" :loading="loadSearch" :rowsPerPageOptions="[5, 10, 15]"
                    class="p-datatable-sm mt-4" breakpoint="960px" selectionMode="single" sortMode="multiple" showGridlines
                    paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                    paginator currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" groupRowsBy="group"
                    rowGroupMode="subheader">
                    <template #header>
                        <div class="columns is-multiline">
                            <div class="column is-2 pb-0 mb-0" style="padding-top: 2rem;">
                                <VButton color="primary" @click="exportExcel(activeTab)" outlined icon="fas fa-file-excel">
                                    Export To Excel
                                </VButton>
                            </div>

                            <div class="column is-10 pb-0 mb-3">
                                <div class="columns is-multiline" style="justify-content: right;">
                                    <div class="column is-3">
                                        <VField label="Periode" />
                                        <VDatePicker class="mt-2" v-model="item.filterDate" is-range color="pink"
                                            trim-weeks>
                                            <template #default="{ inputValue, inputEvents }">
                                                <VField addons>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.start" v-on="inputEvents.start" />
                                                    </VControl>
                                                    <VControl>
                                                        <VButton static><i class="fas fa-arrow-right"
                                                                aria-hidden="true"></i>
                                                        </VButton>
                                                    </VControl>
                                                    <VControl icon="feather:calendar">
                                                        <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                    </VControl>
                                                </VField>
                                            </template>
                                        </VDatePicker>
                                    </div>
                                    <div class="column is-1"  style="padding-top: 38px;text-align:center">
                                        <VIconButton color="success" icon="fas fa-search" @click="fetchData()" :loading="loadSearch" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <ColumnGroup type="header">
                        <Row>
                            <Column header="No" style="min-width: 15px; text-align: center;" :rowspan="2" />
                            <Column header="CARA PEMBAYARAN" style="min-width: 80px; text-align: center;" :rowspan="2" />
                            <Column header="PASIEN RAWAT INAP" style="text-align: center;" :colspan="2" />
                            <Column header="PASIEN RAWAT JALAN" style="text-align: center" :rowspan="2" />
                            <Column header="PASIEN RAWAT JALAN" style="text-align: center" :colspan="3" />
                        </Row>
                        <Row>
                            <Column header="JUMLAH PASIEN KELUAR" style="text-align: center;" />
                            <Column header="JUMLAH PASIEN DIRAWAT" style="text-align: center;" />
                            <Column header="LABORATORIUM" style="text-align: center;" />
                            <Column header="RADIOLOGI" style="text-align: center;" />
                            <Column header="LAIN-LAIN" style="text-align: center;" />
                        </Row>
                    </ColumnGroup>
                    <Column field="no" style="min-width: 10px; text-align:center" />
                    <Column field="golbayar" style="min-width: 80px;" />
                    <Column field="pasienKeluarRI" style="min-width: 50px;" />
                    <Column field="pasienDirawatRI" />
                    <Column field="pasienRawatJalan" />
                    <Column field="pasienLab" />
                    <Column field="pasienRad" />
                    <Column field="pasienLain" />
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
    title: 'Cara Bayar - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(true)

const item: any = ref({
    aktif: true,
    filterDate: reactive({
        start: new Date(),
        end: new Date(),
    }),
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

    let tglAwal = H.formatDate(item.value.filterDate.start, 'YYYY-MM-DD')
    let tglAkhir = H.formatDate(item.value.filterDate.end, 'YYYY-MM-DD')

    loadSearch.value = true
    await useApi().get(`/laporan/get-laporan-cara-bayar?tglAwal=${tglAwal}&tglAkhir=${tglAkhir}`).then((response) => {
        response.data.forEach((element: any, i: any) => {
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
        ['Laporan RL 3.15 - '],
        [],
        ['NO', 'CARA BAYAR', 'PASIEN RAWAT INAP', '', 'PASIEN RAWAT JALAN','PASIEN RAWAT JALAN'],
        ['', '', 'JUMLAH PASIEN KELUAR', 'JUMLAH PASIEN DIRAWAT', '', 'LABORATORIUM','RAG','LE'],
        ...dataSource.value.map((e, index) => [
            e.no,
            e.golbayar,
            e.pasienKeluarRI,
            e.pasienDirawatRI,
            e.pasienRawatJalan,
            e.pasienLab,
            e.pasienRad,
        ]),
    ]);

    // Defining style for the header (centered)
    const headerStyle = {
        alignment: {
            horizontal: 'center',
            vertical: 'center',
        },
        font: {
            color: { rgb: 'FFFFFF' },
            bold: true,
        },
        fill: { fgColor: { rgb: '807C7C' } },
        border: {
            top: { style: 'thin', color: { rgb: 'FFFFFF' } },
            bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
            left: { style: 'thin', color: { rgb: 'FFFFFF' } },
            right: { style: 'thin', color: { rgb: 'FFFFFF' } },
        },
    };

    // Applying header style
    const headerRange = XLSX.utils.decode_range(worksheet['!ref']);
    // console.log(headerRange)
    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        const headerCell = XLSX.utils.encode_cell({ r: 2, c: col });
        console.log(worksheet[headerCell])
        worksheet[headerCell].s = headerStyle;
    }

    // Setting column widths
    const columnWidths = [10, 40, 30, 30, 30, 30, 20, 30];

    for (let col = headerRange.s.c; col <= headerRange.e.c; col++) {
        worksheet['!cols'] = worksheet['!cols'] || [];
        worksheet['!cols'][col] = { wch: columnWidths[col] };
    }

    // Centering the text in cell A1
    const titleCell = XLSX.utils.encode_cell({ r: 0, c: 0 });
    worksheet[titleCell].s = {
        alignment: {
            horizontal: 'center',
            vertical: 'center',
        },
        font: {
            bold: true,
            sz: 18,
        },
    };

    const row2Style = {
        font: {
            color: { rgb: 'FFFFFF' },
            bold: true,
        },
        fill: { fgColor: { rgb: '807C7C' } },
        border: {
            top: { style: 'thin', color: { rgb: 'FFFFFF' } },
            bottom: { style: 'thin', color: { rgb: 'FFFFFF' } },
            left: { style: 'thin', color: { rgb: 'FFFFFF' } },
            right: { style: 'thin', color: { rgb: 'FFFFFF' } },
        },
    };

    const row2Range = XLSX.utils.decode_range(worksheet['!ref']);
    for (let col = row2Range.s.c; col <= row2Range.e.c; col++) {
        const cell = XLSX.utils.encode_cell({ r: 3, c: col });
        worksheet[cell].s = row2Style;
    }
    // Merging the first two rows
    const merges = [
        { s: { r: 0, c: 0 }, e: { r: 1, c: 8 } },
        { s: { r: 2, c: 0 }, e: { r: 3, c: 0 } },
        { s: { r: 2, c: 1 }, e: { r: 3, c: 1 } },
        { s: { r: 2, c: 4 }, e: { r: 3, c: 4 } },
        { s: { r: 2, c: 5 }, e: { r: 3, c: 5 } },

        { s: { r: 2, c: 2 }, e: { r: 2, c: 3 } },
        { s: { r: 2, c: 5 }, e: { r: 2, c: 7 } },
        // { s: { r: 2, c: 7 }, e: { r: 3, c: 7 } },
    ];
    worksheet['!merges'] = merges;

    XLSX.utils.book_append_sheet(workbook, worksheet, 'Laporan RL 5.4 - Jalan', true);
    XLSXStyle.writeFile(workbook, 'Laporan RL 5.4 - Penyakit Rawat Jalan.xlsx');
};

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
}
</style>
