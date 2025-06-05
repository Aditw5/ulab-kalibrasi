
<template>
    <VCard>
        <div class="columns is-multiline">
            <div class="column is-12">
                <h3 class="title is-5 mb-2 mr-1"> Piutang Pasien </h3> <span> ( {{ ds_PASIEN.total }}
                    Totals)</span>
            </div>
            <div class="column is-12 all-projects m-3 mt-0">
                <div class="columns is-multiline  projects-card-grid">
                    <div class="column is-12" v-if="props.hideColumn">
                        <div class="columns is-multiline">
                            <div class="column is-6">
                                <VField>
                                    <VLabel>Periode</VLabel>
                                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                            <div class="column is-5">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Kelompok Pasien</VLabel>
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.qkelompokpasien"
                                            :options="d_KelompokPasien" placeholder="Pilih data" :searchable="true"
                                            :attrs="{ id }" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-10">
                                <VField>
                                    <p class="fs-075">Nama Pasien , No.RM, No.Registrasi</p>
                                    <VControl icon="feather:search">
                                        <input v-model="item.search" v-on:keyup.enter="fetchData" class="input"
                                            placeholder="Cari Nama Pasien, No.Registrasi, No.RM" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-2 mt-5">
                                <VIconButton type="button" color="success" class="searcv-button" raised icon="fas fa-search"
                                    @click="fetchData()" :loading="ds_PASIEN.loading">
                                </VIconButton>
                            </div>

                            <div class="column is-3 is-offset-7 switch-filter">
                                <VControl>
                                    <VSwitchBlock v-model="item.status" label="Verify" color="danger" class="p-0" />
                                </VControl>
                            </div>

                            <div class="column is-2">
                                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined
                                    raised>
                                    Clear
                                    All </a>
                            </div>

                            <!-- <div class="column is-12">
                                <VField>
                                    <VLabel>No RM</VLabel>
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.norm" v-on:keyup.enter="fetchData()"
                                            placeholder="No RM" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <VLabel>No Regisrasi</VLabel>
                                    <VControl icon="feather:book">
                                        <VInput type="text" v-model="item.noregis" v-on:keyup.enter="fetchData()"
                                            placeholder="No Regisrasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Kelompok Pasien</VLabel>
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien"
                                            placeholder="Pilih data" :searchable="true" @select="fetchData()"
                                            :attrs="{ id }" />
                                    </VControl>
                                </VField>
                            </div> -->

                        </div>
                    </div>
                    <div class="column " :class="props.hideColumn ? 'is-12' : 'is-8'">
                        <div class="flex-list-inner mb-4" v-if="ds_PASIEN.loading">
                            <div class="flex-table-item grid-item mb-4" v-for="key in 5" :key="key">
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
                        <div class="flex-list-inner" v-else-if="ds_PASIEN.total === 0">
                            <VCard>
                                <VPlaceholderSection title="Not found" subtitle="There is no data that match your query."
                                    class="my-6">
                                    <template #image>
                                        <img class="light-image" src="/@src/assets/illustrations/placeholders/search-4.svg"
                                            alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderSection>
                            </VCard>
                        </div>
                        <div v-else-if="ds_PASIEN.total > 0">
                            <div class="grid-item mb-4" v-for="(item, i) in dataSource" :key="item.id">
                                <div class="top-section">
                                    <div class="head">
                                        <div class="title-wrap">
                                            <div class="columns">
                                                <div class="column is-3">
                                                    <VAvatar size="small" :color="listColor[i]" :initials="item.initials" />
                                                </div>
                                                <div class="column is-12 mr-3">
                                                    <h3>{{ item.namaPasien + " - " + "(" + item.nocm + ")" }}
                                                        <VTag
                                                            :color="item.jenis_piutang === 'Piutang Pasien' ? 'danger' : (item.jenis_piutang === 'Piutang Penjamin' || item.jenis_piutang === 'Piutang Multi' || (item.jenis_piutang == null) ? 'info' : '')"
                                                            :label="item.jenis_piutang" rounded />
                                                    </h3>
                                                    <p>{{ item.noRegistrasi + (item.jeniskelamin == 'PEREMPUAN' ? ' (P)' : ' (L)')
                                                    }}</p>
                                                </div>
                                                <!-- <div class="column is-pulled-right" > -->

                                                <!-- @click="toggle($event, slotProps.data)"> -->
                                                <!-- <SpeedDial :model="listButton" :radius="80" type="semi-circle" style="right: 80px;"
                                                        direction="right" showIcon="pi pi-bars" buttonClass="p-button-outlined"
                                                        :transitionDelay="80" hideIcon="pi pi-times" className="speeddial-up"
                                                        @click="onSelect(item)" :tooltipOptions="listButton" /> -->
                                                <!-- </div> -->
                                            </div>
                                        </div>
                                        <!-- <ProjectCardDropdown /> -->
                                    </div>
                                    <div class="body">
                                        <div class="columns">
                                            <div class="column is-3">
                                                <h4 class="heading">Tanggal</h4>
                                                <p class="fs-075 font-bold">{{ item.tgltransaksi }}</p>
                                            </div>
                                            <!-- <div class="column is-3">
                                                <h4 class="heading">No Registrasi</h4>
                                                <p class="fs-075">{{ item.noRegistrasi }}</p>
                                            </div> -->
                                            <div class="column is-3">
                                                <h4 class="heading">Jenis Pasien</h4>
                                                <p class="fs-075 font-bold">{{ item.jenisPasisen }}</p>
                                            </div>
                                            <div v-if="item.jenis_piutang == 'Piutang Penjamin' || item.jenis_piutang == 'Piutang Multi' || (item.jenis_piutang == null)"
                                                class="column is-3">
                                                <h4 class="heading">Penjamin</h4>
                                                <p class="fs-075 font-bold">{{ item.rekanan }}</p>
                                            </div>
                                            <div v-if="item.jenis_piutang == 'Piutang Pasien'" class="column is-3">
                                                <h4 class="heading">Penjamin</h4>
                                                <p class="fs-075 font-bold">PASIEN</p>
                                            </div>
                                            <div class="column is-3 ">
                                                <h4 class="heading">Status Verifikasi</h4>
                                                <VTag :color=item.bgStatus :label=item.statusVerifikasi rounded />
                                            </div>
                                        </div>
                                        <div class="columns">
                                            <div class="column is-3">
                                                <h4 class="heading">Provinsi</h4>
                                                <p class="fs-075 font-bold">{{ item.namapropinsi }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">Kota/Kabupaten</h4>
                                                <p class="fs-075 font-bold">{{ item.namakotakabupaten }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">Kecamatan</h4>
                                                <p class="fs-075 font-bold">{{ item.namakecamatan }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">Kelurahan</h4>
                                                <p class="fs-075 font-bold">{{ item.namakelurahan }}</p>
                                            </div>
                                        </div>
                                        <div class="columns mt-5-min">
                                            <div class="column is-3">
                                                <h4 class="heading">Alamat</h4>
                                                <p class="fs-075 font-bold">{{ item.alamatlengkap }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">RT/RW</h4>
                                                <p class="fs-075 font-bold">{{ item.rtrw }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">No.HP</h4>
                                                <p class="fs-075 font-bold">{{ item.nohp }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">Bayar </h4>
                                                <p class="fs-075 font-bold">{{ H.formatRp(item.totalBayar, 'Rp. ') }}</p>
                                            </div>
                                        </div>
                                        <div class="columns mt-5-min">
                                            <div class="column is-3">
                                                <h4 class="heading">Billing </h4>
                                                <p class="fs-075 font-bold">{{ H.formatRp(item.totalBilling, 'Rp. ') }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading"
                                                    v-if="item.jenis_piutang === 'Piutang Penjamin' || item.jenis_piutang === 'Piutang Multi' || (item.jenis_piutang !== 'Piutang Pasien' && item.jenis_piutang == null)">
                                                    Klaim </h4>
                                                <h4 class="heading" v-if="item.jenis_piutang == 'Piutang Pasien'">Piutang
                                                    Pasien </h4>
                                                <p class="fs-075 font-bold">{{ H.formatRp(item.totalKlaim, 'Rp. ') }}</p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">Selisih Klaim </h4>
                                                <p class="fs-075 font-bold">{{ H.formatRp(item.tarifselisihklaim, 'Rp. ') }}
                                                </p>
                                            </div>
                                            <div class="column is-3">
                                                <h4 class="heading">Sisa </h4>
                                                <p class="fs-075 font-bold">{{ H.formatRp(item.sisabayar, 'Rp. ') }}</p>
                                            </div>
                                        </div>
                                        <div class="columns mt-5-min">
                                            <div class="column is-3">
                                                <h4 class="heading">STATUS </h4>
                                                <p class="fs-075 font-bold">{{ item.status }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bottom-section is-custom">
                                        <div class="column is-12">
                                            <div class="columns is-multiline mt-2">
                                                <div class="column is-2 p-0">
                                                    <VButton type="button" icon="feather:edit" class="is-fullwidth mr-3"
                                                        color="info" outlined raised @click="toggle($event, item)">
                                                        Aksi </VButton>
                                                </div>
                                                <div class="column is-2 p-0 ml-3"
                                                    v-if="item.statusVerifikasi == 'Verifikasi' && item.sisabayar != 0">
                                                    <VButton type="button" class="is-fullwidth mr-3" color="primary" raised
                                                        @click="bayarPiutang(item)">
                                                        Bayar Tagihan </VButton>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                            :total-items="ds_PASIEN.length > 5 ? currentPage.limit * 10 : 1" :max-links-displayed="5">
                            <template #before-pagination>
                            </template>
                            <template #before-navigation>
                                <VFlex class="mr-4 mt-1" column-gap="1rem">
                                    <VField>

                                    </VField>
                                    <VField>
                                        <VControl>
                                            <div class="select is-rounded">
                                                <select v-model="currentPage.limit">
                                                    <option :value="1">1 results per page</option>
                                                    <option :value="5">5 results per page</option>
                                                    <option :value="10">10 results per page</option>
                                                    <option :value="15">15 results per page</option>
                                                    <option :value="25">25 results per page</option>
                                                    <option :value="50">50 results per page</option>
                                                </select>
                                            </div>
                                        </VControl>
                                    </VField>
                                </VFlex>
                            </template>
                        </VFlexPagination>

                    </div>
                    <div class="column is-4" v-if="!props.hideColumn">
                        <div class="columns is-multiline">
                            <div class="column is-8">
                                <VField>
                                    <VControl icon="feather:search">
                                        <input v-model="item.qnama" type="text" class="input is-rounded"
                                            placeholder="Filter Nama..." />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-3 switch-filter">
                                <VControl>
                                    <VSwitchBlock v-model="item.status" label="Verify" color="danger" class="p-0" />
                                </VControl>
                            </div>
                            <div class="column is-6">
                                <h3 class="title is-5 mb-2 mr-1">Filters </h3>
                            </div>
                            <div class="column is-6">
                                <a @click="clearFilter()" type="button" class="is-pulled-right mr-3" color="info" outlined
                                    raised>
                                    Clear
                                    All </a>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <VLabel>Periode</VLabel>
                                    <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
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
                            <div class="column is-12">
                                <VField>
                                    <VLabel>No RM</VLabel>
                                    <VControl icon="feather:user">
                                        <VInput type="text" v-model="item.norm" v-on:keyup.enter="fetchData()"
                                            placeholder="No RM" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField>
                                    <VLabel>No Regisrasi</VLabel>
                                    <VControl icon="feather:book">
                                        <VInput type="text" v-model="item.noregis" v-on:keyup.enter="fetchData()"
                                            placeholder="No Regisrasi" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VField class="is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Kelompok Pasien</VLabel>
                                    <VControl icon="feather:search">
                                        <Multiselect mode="single" v-model="item.kelompokpasien" :options="d_KelompokPasien"
                                            placeholder="Pilih data" :searchable="true" @select="fetchData()"
                                            :attrs="{ id }" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-12">
                                <VButton @click="fetchData()" :loading="ds_PASIEN.loading" type="button"
                                    icon="feather:search" class="is-fullwidth mr-3" color="info" raised> Apply Filters
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </VCard>

    <VModal :open="modalUbahRekanan" title="Ubah Rekanan" size="small" actions="right" @close="modalUbahRekanan = false">
        <template #content>
            <form class="modal-form">
                <div class="field">
                    <VField class="is-autocomplete-select">
                        <VLabel>Kelompok Pasien</VLabel>
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.klmpasien" :options="d_KelompokPasien"
                                placeholder="Pilih Kelompok Pasien" :searchable="true" />
                        </VControl>
                    </VField>
                </div>
                <div class="field">
                    <VField class="is-autocomplete-select">
                        <VLabel class="required-field">Rekanan / Penjamin</VLabel>
                        <VControl icon="feather:search">
                            <Multiselect mode="single" v-model="item.rekanan" :options="d_Rekanan"
                                placeholder="Pilih Rekanan / Penjamin" :searchable="true" />
                        </VControl>
                    </VField>
                </div>
            </form>
        </template>
        <template #action>
            <VButton color="primary" :loading="isLoadUpdate" @click="updateRekanan(item)">Update</VButton>
        </template>
    </VModal>
    <VModal :open="modalUbahAlamat" title="Ubah Alamat Kontraktor" size="small" actions="right"
        @close="modalUbahAlamat = false">
        <template #content>
            <form class="modal-form">
                <div class="field">
                    <VField class="is-autocomplete-select">
                        <VLabel>Ubah Alamat kontraktor</VLabel>
                        <VControl>
                            <VTextarea class="textarea is-rounded" v-model="item.ubahalamat" rows="4"
                                placeholder="Ubah Alamat" autocomplete="off" autocapitalize="off" spellcheck="true" />
                        </VControl>
                    </VField>
                </div>
            </form>
        </template>
        <template #action>
            <VButton color="primary" :loading="isLoadUpdate" @click="cetakSuratKontraktor(item)">CETAK SURAT</VButton>
        </template>
    </VModal>
    <VModal :open="modalUbahAlamatSurat" title="Ubah Alamat Surat" size="large" actions="right"
        @close="modalUbahAlamatSurat = false">
        <template #content>
            <form class="modal-form">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline p-1">
                                <div class="column is-4">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Ubah Nama Tujuan</VLabel>
                                        <VControl fullwidth>
                                            <VInput type="text" v-model="item.namapasienbaru" class="is-rounded"/>
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Penanggung Jawab</VLabel>
                                        <VControl fullwidth>
                                            <VInput type="text" v-model="item.penanggungjawab" class="is-rounded" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Hubungan Keluarga</VLabel>
                                        <VControl fullwidth>
                                            <VInput type="text" v-model="item.hubungankeluarga" class="is-rounded" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline p-1">
                                <div class="column is-12">
                                    <VField class="is-rounded-select is-autocomplete-select">
                                        <VLabel>Ubah Alamat Surat</VLabel>
                                        <VControl fullwidth>
                                            <VTextarea class="textarea is-rounded" v-model="item.ubahAlamatSurat" rows="4"
                                                placeholder="Ubah Alamat" autocomplete="off" autocapitalize="off"
                                                spellcheck="true" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>
            </form>
        </template>
        <template #action>
            <VButton color="primary" :loading="isLoadUpdate" @click="cetakSurat(item)">CETAK SURAT</VButton>
        </template>
    </VModal>

    <OverlayPanel ref="op">
        <VButton type="button" icon="fas fa-times" class="mr-2" light circle outlined color="info" raised
            @click="Unverifikasi()" v-if="selectedItem.statusVerifikasi == 'Verifikasi'">
            Unverifikasi
        </VButton>
        <VButton type="button" icon="fas fa-clipboard-check" class="mr-2" light circle outlined color="info" raised
            @click="verif()" v-if="selectedItem.statusVerifikasi != 'Verifikasi'">
            Verifikasi
        </VButton>
        <VButton type="button" icon="fas fa-th-list" class="mr-2" light circle outlined color="info" raised
            @click="detalTag()">
            Detail Tagihan
        </VButton>
        <VButton type="button" icon="fas fa-edit" class="mr-2" color="warning" circle outlined raised @click="ubahRek()">
            Ubah Rekanan
        </VButton>

        <VButton type="button" icon="fas fa-file-pdf" class="mr-2" color="danger" circle outlined raised
            @click="ubahAlamatSurat()">
            Cetak Surat Tagihan Perorangan
        </VButton>
        <VButton type="button" icon="fas fa-file-pdf" class="mr-2" color="danger" circle outlined raised
            @click="ubahAlamat()">
            Cetak Surat Tagihan Kontraktor
        </VButton>
        <VButton type="button" icon="fas fa-file-pdf" class="mr-2" color="primary" circle outlined raised
            @click="cetakKwi()">
            Cetak Kwitansi
        </VButton>
    </OverlayPanel>
</template>

    
<script  setup lang="ts">
import { useApi } from '/@src/composable/useApi'
import { ref, reactive, computed } from 'vue'
import { useRouter, useRoute, RouterLink } from 'vue-router';
import { useHead } from '@vueuse/head'
import { useThemeColors } from '/@src/composable/useThemeColors'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import SpeedDial from 'primevue/speeddial'
import { watch } from 'vue';
import OverlayPanel from 'primevue/overlaypanel';
useHead({
    title: 'Daftar Piutang Pasien - ' + import.meta.env.VITE_PROJECT,
})

useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
const op = ref();
const route = useRoute()
const router = useRouter()
const item: any = ref({
    ubahalamat: reactive(''),
    ubahAlamatSurat: reactive(''),
    status: false,
    isKK: false,
    norec: '',
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),
})
const props = defineProps({
    hideColumn: {
        type: Boolean
    },
})
const currentPage: any = ref({
    limit: 5,
    rows: 50
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})

const dataSource: any = ref([])
let ds_PASIEN: any = ref([])
let d_KelompokPasien: any = ref([])
let d_Rekanan: any = ref([])
let modalUbahRekanan: any = ref(false)
let modalUbahAlamat: any = ref(false)
let modalUbahAlamatSurat: any = ref(false)
let isLoadUpdate: any = ref(false)
const selectedItem: any = ref({})
let listColor: any = ref(Object.keys(useThemeColors()))


const fetchData = async () => {
    ds_PASIEN.value.loading = true
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 0
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    let search = ''
    let qkelompokpasien = ''
    if (item.value.search) search = `&search=${item.value.search}`
    if (item.value.qkelompokpasien) qkelompokpasien = `&kelompokpasien=${item.value.qkelompokpasien}`

    let status = item.value.status == true ? '&status=1' : '&status=2'
    await useApi().get(`/kasir/daftar-piutang-pasien?${tglAwal}${tglAkhir}${qkelompokpasien}${search}${status}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}`)
        .then((response) => {
            response.data.forEach((element: any, i: any) => {
                element.no = i + 1
                let ini = element.namaPasien.split(' ')
                let init = element.namaPasien.substr(0, 1)
                element.initials = ini.length > 1 ? init + ini[1].substr(0, 1) : init
                if (ini.length > 1) {
                    init = init + ini[1].substr(0, 1)
                }
                element.initials = init
                element.bgStatus = element.statusVerifikasi == 'Verifikasi' ? 'success' : 'warning'
                element.tgltransaksi = H.formatDate(element.tglTransaksi, 'DD-MMM-YYYY')
            });
            ds_PASIEN.value.total = response.data.length
            dataSource.value = response.data
            ds_PASIEN.value.loading = false
            setAutoFill()
        })
}
const getListCombo = async () => {
    await useApi().get('/kasir/list-item-pendukung').then((response) => {
        d_KelompokPasien.value = response.kelompokpasien.map((e: any) => {
            return { label: e.kelompokpasien, value: e.id }
        })
        d_Rekanan.value = response.rekanan.map((e: any) => {
            return { label: e.namarekanan, value: e.id }
        })
    })
}

function clearFilter() {
    delete item.value.qnama
    delete item.value.nocm
    delete item.value.noregis
    delete item.value.kelompokpasien
    fetchData()
}

const listButton: any = ref([
    {
        label: 'Unverifikasi',
        icon: 'fas fa-times',
        command: async () => {
            await useApi().post(`/kasir/cancel-verif-piutang`, { 'norec': selectedItem.value.norec_spp }).then(() => {
                fetchData()
            })
        },
    },
    {
        label: 'Verifikasi',
        icon: 'fas fa-clipboard-check',
        command: async () => {
            console.log(selectedItem.value)
            if (selectedItem.value.isVerified == true) {
                H.alert('error', 'Data Sudah Terverifikasi')
                return
            }
            await useApi().post(`/kasir/verify-piutang-pasien`, { 'norec': selectedItem.value.norec_spp }).then(() => {
                fetchData()
            })
        },
    },
    {
        label: 'Detail Tagihan',
        icon: 'fas fa-th-list',
        command: () => {
            console.log(selectedItem.value.norec_pd)
            router.push({
                name: 'module-kasir-billing',
                query: {
                    noregistrasi: selectedItem.value.noRegistrasi,
                    norec_pasien_daftar: selectedItem.value.norec_pd,
                },
            })
            //   modalKirimLIS.value = true
        }

    },
    {
        label: 'Ubah Rekanan',
        icon: 'fas fa-edit',
        command: () => {
            modalUbahRekanan.value = true
            //   modalCatatan.value = true
        }
    },
    {
        label: 'Cetak Surat Tagihan',
        icon: 'fas fa-file-pdf',
        command: () => {
            H.printBlade('laboratorium/report/bukti-layanan-lab?noregistrasi=' + item.registrasi.noregistrasi + '&norec=' + item.total[0].norec_pp);
        }
    },
    {
        label: 'Cetak Kwitansi',
        icon: 'fas fa-file-pdf',
        command: (e: any) => {
            //   hasilLab (e)
        },
    },

])

const ubahRek = () => {
    modalUbahRekanan.value = true
}
const ubahAlamat = () => {
    modalUbahAlamat.value = true
    setAutoFill()
}
const ubahAlamatSurat = () => {
    modalUbahAlamatSurat.value = true
    setAutoFill()
}

function cetakKwi(e: any) {
  // qzService.printData(`kasir/daftar-penerimaan/report/kwitansi?pdf=true&norec=${e.norec}`,'KWITANSI',1)
  H.printBlade(`kasir/daftar-penerimaan/report/kwitansi-piutang?pdf=true&norec=${selectedItem.value.norec_spp}`);
}

const cetakSurat = (item) => {
    const alamat = item.ubahAlamatSurat ? item.ubahAlamatSurat : '';
    const namabaru = item.namapasienbaru ? item.namapasienbaru : '';
    H.printBlade(`kasir/cetak-surat-tagihan-perorangan?pdf=true&noregistrasi=${selectedItem.value.noRegistrasi}&norec=${selectedItem.value.norec_spp}&alamat=${alamat}&namabaru=${namabaru}`);
}
// const cetakSuratKontraktor = () => {
//   H.printBlade('kasir/cetak-surat-tagihan-kontraktor?pdf=true&noregistrasi=' + selectedItem.value.noRegistrasi + '&norec=' + selectedItem.value.norec_spp + '&alamat=' + );
// }

const cetakSuratKontraktor = (item) => {
    const alamat = item.ubahalamat ? item.ubahalamat : '';
    H.printBlade(`kasir/cetak-surat-tagihan-kontraktor?pdf=true&noregistrasi=${selectedItem.value.noRegistrasi}&norec=${selectedItem.value.norec_spp}&alamat=${alamat}`);
}



const detalTag = () => {
    router.push({
        name: 'module-kasir-billing',
        query: {
            noregistrasi: selectedItem.value.noRegistrasi,
            norec_pasien_daftar: selectedItem.value.norec_pd,
        },
    })
}

function bayarPiutang(e: any) {
    router.push({
        name: 'module-kasir-pembayaran-tagihan-piutang',
        query: {
            norec_spp: e.norec_spp,
            norec_pd: e.norec_pd
        },
    })
}

const Unverifikasi = async () => {
    await useApi().post(`/kasir/cancel-verif-piutang`, { 'norec': selectedItem.value.norec_spp }).then(() => {
        fetchData()
    })
}
const verif = async () => {
    if (selectedItem.value.isVerified == true) {
        H.alert('error', 'Data Sudah Terverifikasi')
        return
    }
    await useApi().post(`/kasir/verify-piutang-pasien`, { 'norec': selectedItem.value.norec_spp }).then(() => {
        fetchData()
    })
}
const updateRekanan = (e: any) => {
    isLoadUpdate.value = true
    let objUpdate = {
        'norec_pd': selectedItem.value.norec_pd,
        'objectrekananfk': e.rekanan,
        'objectkelompokpasienlastfk': e.klmpasien
    }
    if (!e.rekanan) {
        H.alert('error', 'Rekanan Tidak Boleh Kosong')
        return
    }
    useApi().post('/kasir/update-rekanan', objUpdate).then(() => {
        isLoadUpdate.value = false
        modalUbahRekanan.value = false
        fetchData()
    })
}
const onSelect = (e: any) => {
    selectedItem.value = e
}
const toggle = (event: any, e: any) => {
    selectedItem.value = e
    op.value.toggle(event);
}

watch(currentPage.value, () => {
    fetchData()
})

watch(
    () => item.value.status,
    () => {
        fetchData()
    }
)

const setAutoFill = async () => {
    try {
        const response = await useApi().get('kasir/autofill-pasien?norec_spp=' + selectedItem.value.norec_spp, {});
        let alamatlengkap = ''
        let alamatlengkap_pasien = ''
        let nama_pasien = ''
        let penanggung_jawab = ''
        let hubungan_keluarga = ''
        if (response.alamatrekanan) {
            alamatlengkap = response.alamatrekanan;
            item.value.ubahalamat = alamatlengkap || '';
        } else {
            item.ubahalamat = '';
        }
        if (response.alamatpasien) {
            alamatlengkap_pasien = response.alamatpasien;
            item.value.ubahAlamatSurat = alamatlengkap_pasien || '';
        } else {
            item.ubahAlamatSurat = '';
        }
        if (response.namaPasien) {
            nama_pasien = response.namaPasien;
            item.value.namapasienbaru = nama_pasien || '';
        } else {
            item.namapasienbaru = '';
        }
        if (response.penanggungJawab) {
            penanggung_jawab = response.penanggungJawab;
            item.value.penanggungjawab = penanggung_jawab || '';
        } else {
            item.penanggungjawab = '';
        }
        if (response.hubunganKeluarga) {
            hubungan_keluarga = response.hubunganKeluarga;
            item.value.hubungankeluarga = hubungan_keluarga || '';
        } else {
            item.hubungankeluarga = '';
        }
    } catch (error) {
        console.error('Error fetching data:', error);
        item.ubahalamat = '';
        item.ubahAlamatSurat = '';
        item.namapasienbaru = '';
        item.penanggungjawab = '';
        item.hubungankeluarga = '';
    }
};

getListCombo()
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
