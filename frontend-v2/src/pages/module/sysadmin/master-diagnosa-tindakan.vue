<template>
    <VCard>
        <ConfirmDialog/>
        <!-- <div class="columns column c-title">
            <h3 class="title is-5 mb-2 ml-2" style="z-index:1">Kategori Diagnosa</h3>
        </div> -->

        <div class="columns is-multiline">
            <div class="column is-4">
                <img src="/images/avatars/label/diagnosa-tindakan.png" class="mix-tindakan">
                <VCard>
                    <div class="columns is-multiline">
                        <div class="column is-6">
                            <h3 v-if="item.id" class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                Edit Data
                            </h3>
                            <h3 v-else class="title is-6 mb-2 mr-1">
                                <i class="iconify" data-icon="feather:edit" aria-hidden="true"> </i>
                                Tambah Data
                            </h3>
                        </div>
                        <div class="column is-12">
                            <VField label="Diagnosa Tindakan">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.namadiagnosatindakan" placeholder="Diagnosa Tindakan"
                                        class="is-rounded" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12" v-if="item.id" style="display:flex;padding: 0px;">
                            <VField class="column is-8 is-rounded-select is-autocomplete-select">
                                <VLabel>Kategori Diagnosa</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkategorydiagnosafk"
                                        :options="d_kategoryDiagnosa" placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>

                            <VField class="column is-4">
                                <VLabel>Aktivasi</VLabel>
                                <VControl class="is-pulled-right">
                                    <VSwitchBlock style="padding:0px;padding-top: 4px;" v-model="item.statusenabled"
                                        label="Aktif" color="danger" />
                                </VControl>
                            </VField>

                        </div>
                        <div class="column is-12" v-else>
                            <VField class="is-rounded-select is-autocomplete-select">
                                <VLabel>Kategori Diagnosa</VLabel>
                                <VControl icon="feather:search">
                                    <Multiselect mode="single" v-model="item.objectkategorydiagnosafk"
                                        :options="d_kategoryDiagnosa" placeholder="Pilih data" :searchable="true" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField>
                                <VLabel>Kode</VLabel>
                                <VControl>
                                    <VTextarea v-model="item.kode" class="is-primary-focus" rows="2"
                                        placeholder="Kode..."></VTextarea>
                                </VControl>
                            </VField>
                        </div>



                        <div class="column is-12">
                            <div v-if="item.id">
                                <VButton @click="save(item)" :loading="isLoadingBtn" type="button" icon="feather:edit"
                                    class="is-fullwidth mr-3" color="info" raised>
                                    Update Data
                                </VButton>
                                <VButton @click="clear()" type="button" icon="feather:x-circle"
                                    class="is-fullwidth is-outlined is-warning mt-3" raised>
                                    Batal Edit
                                </VButton>
                            </div>
                            <div v-else>
                                <VButton @click="save(item)" :loading="isLoadingBtn" type="button" icon="feather:save"
                                    class="is-fullwidth mr-3" color="success" raised>
                                    Simpan Data
                                </VButton>
                            </div>


                        </div>
                    </div>
                </VCard>
            </div>
            <div class="column is-8" style="margin-top: 3.2rem;">
                <div class="user-grid-toolbar">
                    <VControl icon="feather:search">
                        <input v-model="filters" class="input custom-text-filter" placeholder="Search..." />
                    </VControl>
                    <div class="column is-6 switch-filter">
                        <VControl>
                            <InputSwitch v-model="item.aktif" @change="fetchData()" />
                        </VControl>
                        <span>Aktif</span>
                    </div>
                    <div class="buttons ml-4">
                        <VField v-slot="{ id }" class="is-icon-select">
                            <VControl class="mr-0">
                                <Multiselect v-model="selectView" :attrs="{ id }" placeholder="Select View" label="name"
                                    :options="d_View" :searchable="true" track-by="name" mode="single"
                                    @select="changeView(selectView)" autocomplete="off">
                                    <template #singlelabel="{ value }">
                                        <div class="multiselect-single-label">
                                            <div class="select-label-icon-wrap">
                                                <i :class="value.icon"></i>
                                            </div>
                                            <span class="select-label-text">
                                                {{ value.name }}
                                            </span>
                                        </div>
                                    </template>

                                    <template #option="{ option }">
                                        <div class="select-option-icon-wrap">
                                            <i :class="option.icon"></i>
                                        </div>
                                        <span class="select-option-text">
                                            {{ option.name }}
                                        </span>
                                    </template>
                                </Multiselect>
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="user-grid user-grid-v2" v-if="selectView == 'list'">
                    <DataTable :value="dataSourcefiltered" :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                        :loading="isLoading" class="p-datatable-sm"
                        paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                        responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                        currentPageReportTemplate="Showing {first} to {last} of {totalRecords}" showGridlines>

                        <Column field="no" header="#"></Column>
                        <Column field="nama" header="Diagnosa Tindakan" :sortable="true"></Column>
                        <Column field="kategorydiagnosa" header="Kategori Diagnosa" :sortable="true"></Column>
                        <Column field="status" header="Status" :sortable="true"></Column>
                        <Column field="kddiagnosatindakan" header="Kode" :sortable="true"></Column>
                        <Column :exportable="false" header="Aksi" style="text-align: center;">
                            <template #body="slotProps">
                                <VIconButton type="button" icon="pi pi-pencil" class="mr-3" color="info" circle outlined
                                    raised v-tooltip.top="'Edit'" @click="edit(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" icon="fas fa-trash" v-if="item.aktif == true" class="mr-3"
                                    color="danger" circle outlined raised v-tooltip.top="'Hapus'"
                                    @click="DialogConfirm(slotProps.data)">
                                </VIconButton>
                                <VIconButton type="button" icon="fas fa-trash" v-else disabled class="mr-3" color="danger"
                                    circle outlined raised v-tooltip.top="'Hapus'">
                                </VIconButton>
                            </template>
                        </Column>
                    </DataTable>
                </div>
                <div class="tile-grid tile-grid-v1" v-else-if="selectView == 'grid'">

                    <TransitionGroup name="list" tag="div" class="columns is-multiline">
                        <!--Grid item-->
                        <div v-for="(item, key) in dataSourcefiltered" :key="item.id" class="column is-6">
                            <div class="tile-grid-item">
                                <div class="tile-grid-item-inner">
                                    <VAvatar size="medium" picture="/images/avatars/icon/ic-diagnosatindakan.png"
                                        color="primary" squared bordered />

                                    <div class="meta" @click="edit(item)" style="cursor:pointer">
                                        <span class="dark-inverted mb-2">{{ item.nama }}</span>
                                        <span class="dark-inverted">{{ item.kategorydiagnosa }}</span>

                                    </div>
                                    <VTag :color="item.status_c" :label="item.status" style="margin-left:25px" />
                                    <VDropdown icon="feather:more-vertical" spaced right>
                                        <template #content>
                                            <a role="menuitem" class="dropdown-item is-media" @click="detail(item)">
                                                <div class="icon">
                                                    <i class="iconify" data-icon="feather:bookmark" aria-hidden="true"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Detail</span>
                                                    <span>Untuk melihat data </span>
                                                </div>
                                            </a>
                                            <a role="menuitem" class="dropdown-item is-media" @click="edit(item)">
                                                <div class="icon">
                                                    <i class="iconify" data-icon="feather:edit" aria-hidden="true"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Edit</span>
                                                    <span>Untuk merubah data </span>
                                                </div>
                                            </a>
                                            <a role="menuitem" class="dropdown-item is-media" v-if="item.aktif == true"
                                                @click="DialogConfirm(item)">
                                                <div class="icon">
                                                    <i aria-hidden="true" class="lnil lnil-trash-can-alt"></i>
                                                </div>
                                                <div class="meta">
                                                    <span>Remove</span>
                                                    <span>Hapus Data dari Daftar</span>
                                                </div>
                                            </a>
                                        </template>
                                    </VDropdown>
                                </div>
                            </div>
                        </div>
                    </TransitionGroup>
                </div>
            </div>

        </div>

        <VModal :open="modalDetail" title="Detail Diagnosa Tindakan" actions="right" @close="modalDetail = false, clear()">
            <template #content>
                <form class="modal-form">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <VField label="Status Keluar">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.diagnosatindakan" placeholder="Kelompok Produk"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Kategori Diagnosa">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kategorydiagnosa" placeholder="Kode External"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Kode">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.kedeexternal" placeholder="Norec" class="is-rounded"
                                        readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-6">
                            <VField label="Status Aktivasi">
                                <VControl icon="feather:bookmark">
                                    <VInput type="text" v-model="item.status" placeholder="Report Display"
                                        class="is-rounded" readonly style="cursor:pointer" />
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <VField label="Keterangan">
                                <VControl>
                                    <VTextarea v-model="item.keterangan" class="is-primary-focus" rows="2" readonly
                                        style="cursor:pointer"></VTextarea>
                                </VControl>
                            </VField>
                        </div>
                    </div>
                </form>
            </template>

        </VModal>
    </VCard>
</template>

<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, computed } from 'vue'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import ConfirmDialog from 'primevue/confirmdialog'
import InputSwitch from 'primevue/inputswitch';
import { useConfirm } from 'primevue/useconfirm'
import { useHead } from '@vueuse/head'
import { useToaster } from '/@src/composable/toaster'

useHead({
    title: 'Transmedic - Diagnosa Tindakan',
})
const confirm = useConfirm()
const item: any = ref({aktif: true})
const d_kategoryDiagnosa = ref([])
const modalInput = ref(false)
const modalDetail = ref(false)

let dataSource: any = ref([])
const d_View = [
    {
        name: 'Grid View',
        value: 'grid',
        icon: 'fas fa-id-card-alt',
    },
    {
        name: 'List View',
        value: 'list',
        icon: 'fas fa-list',
    },
]

const selectView: any = ref()
selectView.value = 'list'

let isLoadingBtn: any = ref(false)
let isLoading: any = ref(false)


const filters = ref('')
const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dataSource.value
    }

    return dataSource.value.filter((items: any) => {
        return (
            items.kddiagnosatindakan.match(new RegExp(filters.value, 'i'))
            // items.kddiagnosatindakan.match(new RegExp(filters.value, 'i'))
        )
    })
})

const fetchData = async () => {

    isLoading.value = true
    await useApi().get(`/sysadmin/master-diagnosa-tindakan?statusenabled=${item.value.aktif}`).then((response: any) => {
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            element.nama = (element.namadiagnosatindakan.length > 20) ? element.namadiagnosatindakan.substring(0, 20) + '...' : element.namadiagnosatindakan
        })
        dataSource.value = response.data
        isLoading.value = false
    })
}

const save = async (items:any) => {
    if (!items.namadiagnosatindakan) {
        useToaster().error('Nama Diagnosa Tindakan harus di isi')
        return
    }
    else if (!items.objectkategorydiagnosafk) {
        useToaster().error('Kategori Diagnosa harus di isi')
        return
    }
    else if (!items.kode) {
        useToaster().error('kode harus di isi')
        return
    }
    var objSave =
    {
        'diagnosatindakan': {
            'id': items.id ? items.id : '',
            'namadiagnosatindakan': items.namadiagnosatindakan,
            'objectkategorydiagnosafk': items.objectkategorydiagnosafk,
            'statusenabled': items.statusenabled,
            'norec': items.norec ? items.norec : '',
            'kodetindakan': items.kode,
        }
    }
    isLoadingBtn.value = true
    await useApi().post(`/sysadmin/master-diagnosa-tindakan/save`, objSave).then((response: any) => {
        isLoadingBtn.value = false
        clear()
        fetchData()
    }, (error) => {
        isLoadingBtn.value = false
        // console.log(error)
    })
}

const deleteItem = async (e: any) => {
    isLoading.value = true
    await useApi().post(`/sysadmin/master-diagnosa-tindakan/delete`, { 'id': e.id }).then((response: any) => {
        isLoading.value = false
        fetchData()
    }, (error) => {
    })
}

const DialogConfirm = (e: any) => {
    confirm.require({
        message: 'Apakah anda serius menghapus data ini ?',
        header: 'Konfirmasi Hapus Data',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        accept: () => {
            deleteItem(e)

        },
        reject: () => { },
    })
}

const loadDropdown = () => {
    d_kategoryDiagnosa.value = []
    useApi().get(`/sysadmin/master-diagnosa-tindakan/select-item`).then((response: any) => {
        d_kategoryDiagnosa.value = response.kategorydiagnosa.map((e: any) => { return { label: e.kategorydiagnosa, value: e.id } })
    })
}

const edit = (e: any) => {
    item.value.id = e.id
    item.value.namadiagnosatindakan = e.namadiagnosatindakan
    item.value.objectkategorydiagnosafk = e.objectkategorydiagnosafk
    item.value.keterangan = e.keterangan
    item.value.statusenabled = e.statusenabled
}
const detail = (e: any) => {
    item.value.id = e.id
    item.value.diagnosatindakan = e.diagnosatindakan
    item.value.kategorydiagnosa = e.kategorydiagnosa
    item.value.namaexternal = e.namaexternal
    item.value.keterangan = e.keterangan
    item.value.kedeexternal = e.kodeexternal
    item.value.status = e.status
    item.value.reportdisplay = e.reportdisplay
    modalDetail.value = true
}

const clear = () => {
    item.value.id = '',
        item.value.diagnosatindakan = '',
        item.value.keterangan = '',
        item.value.objectkategorydiagnosafk = '',
        modalInput.value = false
}
const changeView = (e: any) => {
    selectView.value = e
}

loadDropdown()
fetchData()

</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';

@import '/@src/scss/components/forms-outer';

.p-inputswitch.p-inputswitch-checked .p-inputswitch-slider {
  background: #e62964;
}
.switch-filter{
  justify-content: end;align-items: center;display: flex;padding: 0px;
}
.p-inputswitch-checked {
  .p-inputswitch-slider:hover {
    background: #e62964 !important;
  }
}

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

.mix-tindakan {
    margin-bottom: -1.3rem;
    max-width: 61%;
    margin-left: 5rem;
    opacity: .8;
    margin-top: -1.5rem;
}

.p-column-header-content {
    display: flex;
    justify-content: center;
}

.fs-075 {
    font-size: 0.9rem;
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

.is-navbar {
    .form-layout {
        margin-top: 30px;
    }
}

.form-layout {
    // max-width: 740px;
    margin: 0 auto;

    &.is-separate {
        // max-width: 1040px;

        .form-outer {
            background: none;
            border: none;

            .form-body {
                display: flex;

                .form-section {
                    flex-grow: 2;
                    padding: 10px;
                    width: 50%;

                    .form-section-inner {
                        @include vuero-s-card;

                        padding: 40px;

                        &.has-padding-bottom {
                            padding-bottom: 60px;
                            height: 100%;
                        }

                        >h3 {
                            font-family: var(--font-alt);
                            font-size: 1.2rem;
                            font-weight: 600;
                            color: var(--dark-text);
                            margin-bottom: 30px;
                        }

                        .columns {
                            .column {
                                padding-top: 0.25rem;
                                padding-bottom: 0.25rem;
                            }
                        }

                        .radio-boxes {
                            display: flex;
                            justify-content: space-between;
                            margin-left: -8px;
                            margin-right: -8px;

                            .radio-box {
                                position: relative;
                                width: calc(50% - 16px);
                                margin: 8px;

                                &:focus-within {
                                    border-radius: 3px;
                                    outline-offset: var(--accessibility-focus-outline-offset);
                                    outline-width: var(--accessibility-focus-outline-width);
                                    outline-style: var(--accessibility-focus-outline-style);
                                    outline-color: var(--primary);
                                }

                                input {
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    height: 100%;
                                    width: 100%;
                                    opacity: 0;
                                    cursor: pointer;

                                    &:checked {
                                        +.radio-box-inner {
                                            background: var(--primary);
                                            border-color: var(--primary);
                                            box-shadow: var(--primary-box-shadow);

                                            .fee,
                                            p {
                                                color: var(--smoke-white);
                                            }
                                        }
                                    }
                                }

                                .radio-box-inner {
                                    background: var(--white);
                                    border: 1px solid var(--fade-grey-dark-3);
                                    text-align: center;
                                    border-radius: var(--radius);
                                    font-family: var(--font);
                                    font-weight: 600;
                                    font-size: 0.9rem;
                                    transition: color 0.3s, background-color 0.3s, border-color 0.3s,
                                        height 0.3s, width 0.3s;
                                    padding: 30px 20px;

                                    .fee {
                                        font-family: var(--font);
                                        font-weight: 700;
                                        color: var(--dark-text);
                                        font-size: 2.4rem;
                                        line-height: 1;

                                        span {
                                            &::after {
                                                content: '$';
                                                position: relative;
                                                top: -10px;
                                                font-size: 1.5rem;
                                            }
                                        }
                                    }

                                    p {
                                        font-family: var(--font-alt);
                                    }
                                }
                            }
                        }

                        .control {
                            >p {
                                padding-top: 12px;

                                >span {
                                    display: block;
                                    font-size: 0.9rem;

                                    span {
                                        font-weight: 500;
                                        color: var(--dark-text);
                                    }
                                }
                            }
                        }
                    }

                    .form-section-outer {
                        .checkboxes {
                            padding: 16px 0;

                            .checkbox {
                                padding: 0;
                                font-size: 0.9rem;
                            }
                        }

                        .button-wrap {
                            .button {
                                min-height: 60px;
                                font-size: 1.05rem;
                                font-weight: 600;
                                font-family: var(--font-alt);
                            }
                        }
                    }
                }
            }
        }
    }
}

.is-dark {
    .form-layout {
        &.is-separate {
            .form-outer {
                background: none !important;

                .form-body {
                    .form-section {
                        .form-section-inner {
                            @include vuero-card--dark;

                            >h3 {
                                color: var(--dark-dark-text);
                            }

                            .radio-boxes {
                                .radio-box {
                                    input:checked+.radio-box-inner {
                                        background: var(--primary);
                                        border-color: var(--primary);
                                        box-shadow: var(--primary-box-shadow);

                                        .fee,
                                        p {
                                            color: var(--smoke-white);
                                        }
                                    }

                                    .radio-box-inner {
                                        background: var(--dark-sidebar-light-2);
                                        border-color: var(--dark-sidebar-light-12);

                                        .fee {
                                            color: var(--dark-dark-text);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (max-width: 767px) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;
                    flex-direction: column;

                    .form-section {
                        width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

@media only screen and (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
    .form-layout {
        &.is-separate {
            .form-outer {
                .form-body {
                    padding-left: 0;
                    padding-right: 0;

                    // flex-direction: column;

                    .form-section {
                        // width: 100%;

                        .form-section-inner {
                            padding: 30px;
                        }
                    }
                }
            }
        }
    }
}

.all-projects {
    .all-projects-header {
        display: flex;
        padding: 20px;
        background: var(--white);
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        margin-bottom: 1.5rem;

        .header-item {
            width: 25%;
            border-right: 1px solid var(--fade-grey-dark-3);

            &:last-child {
                border-right: none;
            }

            .item-inner {
                text-align: center;

                .lnil,
                .lnir {
                    font-size: 2.2rem;
                    margin-bottom: 6px;
                    color: var(--primary);
                }

                span {
                    display: block;
                    font-family: var(--font);
                    font-weight: 600;
                    font-size: 1.4rem;
                    color: var(--dark-text);
                }

                p {
                    font-family: var(--font-alt);
                }
            }
        }
    }

    .projects-card-grid {
        .grid-item {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            min-height: 220px;
            padding: 20px;
            background: var(--white);
            border: 1px solid var(--fade-grey-dark-3);
            border-radius: var(--radius-large);

            .top-section {
                .head {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 8px;

                    h3 {
                        font-size: 1rem;
                        font-family: var(--font-alt);
                        color: var(--dark-text);
                        font-weight: 600;
                    }
                }

                .body {
                    p {
                        font-family: var(--font);
                        color: var(--light-text);
                    }
                }
            }

            .bottom-section {
                display: flex;

                .foot-block {
                    margin-right: 30px;

                    .heading {
                        font-family: var(--font-alt);
                        font-size: 0.75rem;
                        color: var(--light-text-dark-22);
                    }

                    >p {
                        padding-top: 5px;
                    }

                    .developers {
                        display: flex;

                        .v-avatar {
                            margin-right: 6px;
                        }
                    }
                }
            }
        }
    }
}

.heading {
    font-family: var(--font-alt);
    font-size: 0.75rem;
    color: var(--light-text-dark-22);
}

.is-dark {
    .all-projects {
        .all-projects-header {
            background: var(--dark-sidebar-light-6);
            border-color: var(--dark-sidebar-light-12);

            .header-item {
                border-color: var(--dark-sidebar-light-18);

                span {
                    color: var(--dark-dark-text);
                }

                i {
                    color: var(--primary) !important;
                }
            }
        }

        .projects-card-grid {
            .grid-item {
                background: var(--dark-sidebar-light-6);
                border-color: var(--dark-sidebar-light-12);

                .top-section {
                    .head {
                        h3 {
                            color: var(--dark-dark-text);
                        }
                    }
                }

                .bottom-section {
                    .foot-block {
                        .heading {
                            color: var(--light-text-dark-12);
                        }
                    }
                }
            }
        }
    }
}
</style>
