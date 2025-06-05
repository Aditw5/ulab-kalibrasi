

<template>
    <div class="page-content-inner">
        <div class="jobs-dashboard">
            <!--Search toolbar -->
            <div class="search-menu">
                <div class="column is-5">
                    <VControl icon="feather:search" fullwidth class="prime-auto-select">
                        <Dropdown v-model="item.gudang" :options="d_Gudang" :disabled="isDisabled" optionLabel="label"
                             placeholder="Pilih Ruangan" style="width: 100%;" :filter="true" />
                    </VControl>
                </div>
                <div class="column is-5 ml-5">
                    <VField>
                        <VControl icon="feather:bookmark">
                            <VInput type="text" v-model="item.namaproduk" placeholder="Nama Produk"
                                class="is-rounded" />
                        </VControl>
                    </VField>
                </div>
                <VButton color="primary" v-model="item.save" raised class="search-button" @click="fetchData()"
                    :loading="dataSource.loading">
                    Search</VButton>
            </div>

            <div class="column is-12 p-0 pt-5">
                <VCard>
                    <div class="column c-title">
                        <div class="columns">
                            <div class="column is-10 pt-0">
                                <label class="title-page" style="color:var(--dark-text)">Stok Opname</label>
                                <!-- <label for="">Form Stok Opname</label> -->
                            </div>
                        </div>
                    </div>

                    <div class="column is-12 pt-0">
                      <VProgress size="tiny" :value="loadProgress" v-if="isLoadingProgess" />
                        <Toolbar>
                            <template #start>
                                <VButton color="primary" class="mr-4" icon="fas fa-file-excel" raised
                                    @click="exportExcel()"> Export
                                    Excel </VButton>
                                <FileUpload mode="basic" name="demo[]" url="./upload.php" color="info"
                                    accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"
                                    :maxFileSize="50000000" chooseLabel="Import File" @select="onSelectedFiles" />
                            </template>
                            <template #end>
                                <VButton icon="fas fa-save" :loading="btnLoad" color="info" outlined raised @click="saveData()"> Simpan
                                </VButton>
                                <!-- <VButton color="primary" icon="fas fa-file-excel" raised outlined >Import Excel</VButton> -->
                            </template>
                        </Toolbar>
                        <DataTable :value="dataSource" showGridlines editMode="cell" class="p-datatable-sm"
                            @cell-edit-complete="getSelisih" tableClass="editable-cells-table"
                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} " :paginator="true"
                            :rows="5" :rowsPerPageOptions="[5, 10, 25]">
                            <ColumnGroup type="header">
                                <Row>
                                    <Column header="No" :rowspan="4" />
                                </Row>
                                <Row>
                                    <Column header="Product" :rowspan="4" />
                                </Row>
                                <Row>
                                    <Column header="Jumlah" :colspan="3" />
                                    <Column header="Satuan" :rowspan="4" />
                                </Row>
                                <Row>
                                    <Column header="Sistem" sortable field="qtyProduk" />
                                    <Column header="Real" sortable field="qtyProduk" />
                                    <Column header="Selisih" sortable field="selisih" />
                                </Row>
                            </ColumnGroup>
                            <Column field="no" />
                            <Column field="namaProduk" />
                            <Column field="qtyProduk" style="text-align: end;"></Column>
                            <Column style="text-align: end;">
                                <template #body="slotProps">
                                    {{ slotProps.data.qtyReal }}
                                </template>
                                <template #editor="{ data, field }">
                                    <InputText v-model="data[field]" autofocus />
                                </template>
                            </Column>
                            <Column field="selisih" style="text-align: end;"></Column>
                            <Column field="satuanStandar"></Column>
                        </DataTable>
                    </div>
                </VCard>
            </div>
        </div>
    </div>
</template>
<script setup lang="ts">
import { useHead } from '@vueuse/head'
import { reactive, ref, computed, watch } from 'vue'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useApi } from '/@src/composable/useApi'
import { useRoute } from 'vue-router'
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown'
import FileUpload from 'primevue/fileupload';
import * as XLSX from "xlsx";
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import ColumnGroup from 'primevue/columngroup';
import Row from 'primevue/row';
import Toolbar from 'primevue/toolbar'
import * as H from '/@src/utils/appHelper'
import moment from 'moment'
export type Job = 'web-developer' | 'uiux-designer' | 'backend-developer'
useHead({
    title: 'Stok Opname - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
const route = useRoute()

const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    })
})
const dataSource: any = ref([])
const remakeData: any = ref([])
const currentPage: any = ref({
    limit: 10,
    rows: 50
})

const d_Gudang: any = ref([])
const d_satuan: any = ref([])
const btnLoad: any = ref(false)
const isDisabled : any = ref(false)
const isLoadingProgess : any = ref(false)
const fileSize: any = ref()

let c = H.cacheHelper().get('c_daftar-registrasi');
if (c != undefined) {
    item.value.periode.start = new Date(c[0]);
    item.value.periode.end = new Date(c[1]);
}

const loadProgress: any = ref(0);

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
watch(currentPage.value, () => {
    fetchData()
})

async function fetchData() {

    dataSource.value.loading = true
    let awal = `tglAwal=${H.formatDate(item.value.periode.start, 'YYYY-MM-DD')}`
    let akhir = `&tglAkhir=${H.formatDate(item.value.periode.end, 'YYYY-MM-DD')}`
    let ruangan = item.value.gudang ? `&ruanganfk=${item.value.gudang.value}` : ''
    let produk = item.value.namaproduk ? `&namaproduk=${item.value.namaproduk}` : ''

    await useApi().get(`/logistik/stok-opname?${awal}${akhir}${ruangan}${produk}`).then((respond) => {
        respond.data.forEach((element: any, i: any) => {
            element.no = i + 1
            element.qtyReal = element.qtyProduk
            element.selisih = element.selisih
        });
        dataSource.value = respond.data
        dataSource.value.loading = false
    }).catch((e) => {
        dataSource.value.loading = false
    })
}

const exportExcel = () => {
    remakeData.value = dataSource.value.map((e: any) => {
        return {
            namaProduk: e.namaProduk, kodeProduk: e.kodeProduk,
            qtyProduk: e.qtyProduk, qtyReal: e.qtyReal, satuanStandar: e.satuanStandar
        }
    })
    const worksheet = XLSX.utils.json_to_sheet(remakeData.value)
    const workbook = { Sheets: { data: worksheet }, SheetNames: ['data'] };
    const excelBuffer: any = XLSX.write(workbook, { bookType: 'xlsx', type: 'array' });
    saveAsExcelFile(excelBuffer, 'products');
}

const saveAsExcelFile = (buffer: any, fileName: string) => {
    let EXCEL_TYPE = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=UTF-8';
    let EXCEL_EXTENSION = '.xlsx';
    const data: Blob = new Blob([buffer], {
        type: EXCEL_TYPE
    });
    const _url = window.URL.createObjectURL(data)
    window.open(_url, EXCEL_EXTENSION).focus();
    // window.open(_url,EXCEL_EXTENSION).focus()
    // exportFilename.saveAs(data, fileName + '_export_' + new Date().getTime() + EXCEL_EXTENSION);
}

const getSelisih = (event: any) => {
    let { data, field, newValue } = event
    if (!item.value.gudang) {
        H.alert('error', 'Ruangan Mesti dipilih Dahulu')
        return
    }
    if (newValue) {
        data.qtyReal = newValue;
        data.selisih = data.qtyReal - data.qtyProduk
        isDisabled.value = true
    } else {
        data.qtyReal = data.qtyProduk
    }
    // console.log(event)
}

loadProgress.value = 90

const saveData = async () => {

    let dataResult: any = []
    btnLoad.value = true
    dataSource.value.forEach((element: any) => {
        if (element.selisih) {
            dataResult.push(element)
        }
    });

    if (dataResult.length <= 0) {
        H.alert('error', 'Tidak ada pembaruan data')
        btnLoad.value = false
        return
    }

    let dataLoadBuffer = 50
    let banyakPerulangan = Math.ceil(dataResult.length/dataLoadBuffer)
    let nilaiAwal = 0;
    let nilaiAKhir = 0;
    console.log(banyakPerulangan)
    for (let i = 0; i < banyakPerulangan; i++) {
      let arrayData = []
      nilaiAwal = i * dataLoadBuffer;
      nilaiAKhir = (i+1) * dataLoadBuffer
      if(nilaiAKhir > dataResult.length){
        nilaiAKhir = dataResult.length
      }
      for (let j = nilaiAwal; j < nilaiAKhir; j++) {
        arrayData.push(dataResult[j])
      }
      let objSave = {
          'ruanganId': item.value.gudang.value,
          'namaRuangan': item.value.gudang.label,
          'tglClosing': moment().format('YYYY-MM-DD HH:mm'),
          "stokProduk": arrayData
      }
      await useApi().postNoMessage('/logistik/save-stok-opname', objSave).then(() => {
        isLoadingProgess.value = true
        loadProgress.value = (nilaiAKhir/dataResult.length)*100
        if(nilaiAKhir == dataResult.length){
          isLoadingProgess.value = false
        }
      }).catch((e) => {
          console.log(e)
          btnLoad.value = false
      })
    }
    btnLoad.value = false
}

const listDataCombo = async () => {
    await useApi()
        .get('logistik/penerimaan-barang/get-data-combo')
        .then((response) => {
            d_Gudang.value = response.ruangan.map((e: any) => {
                return { label: e.namaruangan, value: e.id }
            })

        })
}

const onSelectedFiles = async (filez: any) => {
    const file = filez.files[0]

    if (file.type != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") {
        H.alert('error', 'File yang diizinkan dalam bentuk format Excel.')
        return;
    }
    if (file) {
        fileSize.value = parseInt(formatSize(file.size));
        let totalSizePercent = fileSize.value / 10;

        const reader = new FileReader();
        reader.onload = (e: any) => {
            const bstr = e.target.result;
            const wb = XLSX.read(bstr, { type: 'binary' });
            const wsname = wb.SheetNames[0];
            const ws = wb.Sheets[wsname];
            const data = XLSX.utils.sheet_to_json(ws, { header: 1 });
            setToGRID(data)
        }

        reader.readAsBinaryString(file);
    }
}

const formatSize = (bytes: any) => {
    if (bytes === 0) return "0 B";
    const k = 1024
    const sizes = ["B", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k))
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
}

const setToGRID = (e: any) => {
    dataSource.value = []
    e.forEach((element: any, i: any) => {
        if (i > 0) {
            dataSource.value.push({
                kodeProduk: element[1],
                namaProduk: element[0],
                qtyProduk: element[2],
                qtyReal: element[3],
                selisih: parseFloat(element[3]) - parseFloat(element[2]),
                satuanStandar: element[4],
            })
        }
    });

}

listDataCombo()
fetchData()


</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

::v-deep(.editable-cells-table td.p-cell-editing) {
    padding-top: 0;
    padding-bottom: 0;
}

.c-title {
  margin-left: -21px;
  padding-top: 21px;
  padding-top: 18px;
  margin-top: -21px;
  border-top-left-radius: 11px;
  border-left: solid hsl(19deg 100% 75% / 72%) 3px;
  padding-bottom: 0px;
  margin-bottom: 2rem;
}

.title-page {
    position: relative;
    font-size: 17px;
    display: block;
    margin-bottom: 3px;
    margin-top: 8px;
    font-weight: 600;
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }
}

.user-grid-v1 {

    .columns {
        margin-left: -0.5rem !important;
        margin-right: -0.5rem !important;
        margin-top: -0.5rem !important;
    }

    .column {
        padding: 0.5rem !important;
    }

    .grid-item {
        @include vuero-s-card;

        text-align: center;

        >.v-avatar {
            display: block;
            margin: 0 auto 4px;
        }

        h3 {
            font-family: var(--font-alt);
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--dark-text);
        }

        p {
            font-size: 0.85rem;
        }

        .people {
            display: flex;
            justify-content: center;
            padding: 8px 0 30px;

            .v-avatar {
                margin: 0 4px;
            }
        }

        .buttons {
            display: flex;
            justify-content: space-between;

            .button {
                width: calc(50% - 4px);
                color: var(--light-text);

                &:hover,
                &:focus {
                    border-color: var(--fade-grey-dark-4);
                    color: var(--primary);
                    box-shadow: var(--light-box-shadow);
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .user-grid-v1 {
        .columns {
            display: flex;

            .column {
                min-width: 50% !important;
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
    .user-grid-v1 {
        .columns {
            .column {
                min-width: 33.3% !important;
            }
        }
    }
}

:root {
    --header-bg-color: #fff;
    --search-border-color: #efefef;
    --subtitle-color: #83838e;
    --button-color: var(--white);
    --input-color: var(--subtitle-color);
}

.is-dark {
    --header-bg-color: var(--dark-sidebar-light-2);
    --search-border-color: var(--dark-sidebar-light-8);
    --input-color: var(--white);
}

.jobs-dashboard {
    display: flex;
    flex-direction: column;
    margin: 0 auto;
    overflow: hidden;

    .jobs-dashboard-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        scroll-behavior: smooth;
        overflow: auto;
    }

    .search-menu {
        height: 56px;
        white-space: nowrap;
        display: flex;
        flex-shrink: 0;
        align-items: center;
        background-color: var(--header-bg-color);
        border-radius: 8px;
        width: 100%;
        padding-left: 0.75rem;

        >div:not(:last-of-type) {
            border-right: 1px solid var(--search-border-color);
        }

        .search-bar {
            height: 55px;
            width: 100%;
            position: relative;
            display: flex;
            align-items: center;
            padding-right: 1.5rem;

            .field {
                width: 100%;
            }

            .multiselect-tags {
                padding-left: 2.5rem;
            }
        }

        .search-location,
        .search-job,
        .search-salary {
            display: flex;
            align-items: center;
            width: 50%;
            font-size: 14px;
            font-weight: 500;
            padding: 0 25px;
            height: 100%;
            font-family: var(--font);

            input {
                width: 100%;
                height: 100%;
                display: block;
                font-family: var(--font);
                color: var(--input-color);
                background-color: transparent;
                border: none;
            }

            svg {
                margin-right: 0.5rem;
                width: 18px;
                color: var(--primary);
                flex-shrink: 0;
            }
        }

        .search-button {
            background-color: var(--primary);
            min-width: 120px;
            height: 55px;
            border: none;
            font-weight: 500;
            font-family: var(--font);
            padding: 0 1rem;
            border-radius: 0 0.75rem 0.75rem 0;
            color: var(--button-color);
            cursor: pointer;
            margin-left: auto;
        }
    }

    .main-container {
        display: flex;
        flex-grow: 1;
        padding-top: 2rem;

        .search-type {
            width: 270px;
            display: flex;
            flex-direction: column;
            height: 100%;
            flex-shrink: 0;
        }

        .alert {
            background-color: var(--widget-grey);
            padding: 1.75rem;
            border-radius: 8px;

            .alert-title {
                font-size: 1rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
            }

            .alert-subtitle {
                font-size: 13px;
                font-family: var(--font);
                color: var(--subtitle-color);
                margin-bottom: 1.5rem;
            }

            input {
                border-radius: 6px;
            }
        }

        .job-time {
            padding-top: 1.75rem;

            .job-wrapper {
                padding-top: 1.75rem;
            }

            .job-time-title {
                font-size: 0.95rem;
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
            }

            .type-container {
                display: flex;
                align-items: center;
                color: var(--subtitle-color);
                font-size: 13px;

                label {
                    margin-left: 2px;
                    display: flex;
                    align-items: center;
                    cursor: pointer;
                }

                +.type-container {
                    margin-top: 10px;
                }

                .job-number {
                    margin-left: auto;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 25px;
                    min-width: 25px;
                    background-color: var(--white);
                    color: var(--subtitle-color);
                    font-size: 0.8rem;
                    font-family: var(--font);
                    font-weight: 500;
                    padding: 0 0.25rem;
                    border-radius: 50rem;
                }
            }
        }

        .searched-jobs {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding-left: 2.5rem;
        }

        .searched-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;

            .searched-count {
                font-family: var(--font-alt);
                font-size: 1rem;
                font-weight: 600;
                color: var(--dark-text);
            }
        }

        .job-cards {
            padding-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            grid-column-gap: 1.5rem;
            grid-row-gap: 1.5rem;

            @media screen and (max-width: 1212px) {
                grid-template-columns: repeat(2, 1fr);
            }

            @media screen and (max-width: 930px) {
                grid-template-columns: repeat(1, 1fr);
            }
        }

        .job-card {
            @include vuero-l-card;

            cursor: pointer;
            transition: 0.2s;

            &:hover,
            &:focus {
                transform: translateY(-5px);
            }

            .job-card-header {
                // display: flex;
                // align-items: flex-start;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-logo {
                width: 80px;
                height: 80px;
                margin-right: -40px;
            }

            .job-card-title {
                font-family: var(--font-alt);
                font-weight: 600;
                color: var(--dark-text);
                margin-bottom: 0.75rem;
                display: flex;
                justify-content: center;
                align-items: center;

                max-height: 42px;
                overflow: hidden;
                -webkit-box-orient: vertical;
                -webkit-line-clamp: 1;
                display: -webkit-box;
                text-align: center;
            }

            .job-card-subtitle {
                color: var(--subtitle-color);
                font-family: var(--font);
                font-size: 0.95rem;
                line-height: 1.6em;
                // margin-bottom: 1rem;
                margin-top: -5px;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .job-card-buttons {
                margin-top: 1rem;

                .buttons {
                    justify-content: space-between;

                    .v-button {
                        width: 48%;
                    }
                }
            }
        }
    }
}

.is-dark {
    .jobs-dashboard {
        .job-card {
            @include vuero-card--dark;
        }

        .main-container {
            .alert {
                @include vuero-card--dark;
            }

            .job-time {
                .job-number {
                    background: var(--dark-sidebar-light-2);
                }
            }
        }
    }
}

@media screen and (max-width: 620px) {
    .job-cards {
        grid-template-columns: repeat(1, 1fr);
    }
}

@media screen and (max-width: 730px) {
    .job-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media screen and (max-width: 767px) {
    .jobs-dashboard {
        .search-menu {
            flex-direction: column;
            height: auto;
            padding: 1rem;

            >div:not(:last-of-type) {
                border-right: none;
            }

            .search-bar {
                padding: 0;
            }

            .search-location,
            .search-job,
            .search-salary {
                width: 100%;
                height: 44px;
                padding: 0;
            }

            .search-button {
                width: 100%;
                border-radius: 0.75rem;
            }
        }

        .main-container {
            .search-type {
                display: none;
            }

            .searched-jobs {
                padding-left: 0;
            }
        }
    }
}
</style>
