<template>
    <div>
        <div class="business-dashboard hr-dashboard">
            <div class="columns">
                <div class="column is-12">
                    <div class="columns is-multiline">
                        <div class="column is-12">
                            <div class="illustration-header-2 large-screen">
                                <div class="header-image">
                                    <img src="/@src/assets/illustrations/dashboards/lifestyle/Picture2.png" alt=""
                                        style="max-width:75%; margin-left: 2rem; margin-top: 0.5rem;" />
                                </div>
                                <div class="header-meta" style="margin-left : -2rem;">
                                    <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                                        Asman
                                    </h3>
                                    <p>
                                        Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-8" style="margin-top: 2rem;">
                            <TabView class="tabview-custom " :scrollable="true" @tab-click="klikTab($event)">
                                <TabPanel>
                                    <template #header>
                                        <i class="fas fa-users mr-2" aria-hidden="true"></i>
                                        <span>Daftar Mitra Registrasi</span>
                                        <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
                                            class="ml-2" />
                                    </template>

                                    <div v-if="activeTab == 0">
                                        <div class="column is-6"
                                            style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
                                            <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField addons>
                                                        <VControl icon="feather:calendar">
                                                            <VInput :value="inputValue.start"
                                                                v-on="inputEvents.start" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static><i class="fas fa-arrow-right"
                                                                    aria-hidden="true"></i></VButton>
                                                        </VControl>
                                                        <VControl icon="feather:calendar">
                                                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>

                                        </div>

                                        <div class="list-view list-view-v3">
                                            <div class="search-menu-rad mb-2">
                                                <div class="search-location-rad" style="width: 100%">
                                                    <i class="iconify" data-icon="feather:search"></i>
                                                    <input type="text"
                                                        placeholder="Cari Nama Perusahaan, No Pendaftaran"
                                                        v-model="item.search"
                                                        v-on:keyup.enter="fetchDataOrder(order)" />
                                                </div>
                                                <VButton raised class="search-button-rad" @click="fetchDataOrder(order)"
                                                    :loading="isLoading"> Cari Data
                                                </VButton>
                                            </div>
                                            <VCard class="text-center pt-0 pb-0 mt-0">
                                                <VRadio v-model="order" value="0" label="Belum Verif"
                                                    name="outlined_radio" color="warning" />
                                                <VRadio v-model="order" value="1" label="Sudah Verif"
                                                    name="outlined_radio" color="info" />
                                            </VCard>
                                            <VPlaceholderPage :title="H.assets().notFound"
                                                :subtitle="H.assets().notFoundSubtitle" class="my-6"
                                                :class="[dataOrder.length !== 0 && 'is-hidden']">
                                                <template #image>
                                                    <img class="light-image" :src="H.assets().iconNotFound_rev"
                                                        alt="" />
                                                    <img class="dark-image"
                                                        src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                        alt="" />
                                                </template>
                                            </VPlaceholderPage>
                                            <div class="list-view-inner"
                                                style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                                                <TransitionGroup name="list-complete" tag="div">
                                                    <div v-for="(items, m) in dataOrder" :key="m"
                                                        class="list-view-item">
                                                        <div class="list-view-item-inner">
                                                            <VAvatar size="small" style="left: 8px;top: 4px;"
                                                                :color="listColor[i]" :initials="items.initials" />
                                                            <div class="meta-left">
                                                                <h3>
                                                                    {{ items.namaperusahaan }} <i
                                                                        aria-hidden="true"></i>
                                                                </h3>
                                                                <span style="color: black">
                                                                    <i aria-hidden="true" class="iconify"
                                                                        data-icon="teenyicons:id-outline"></i>
                                                                    <span> {{ items.nopendaftaran }}</span>
                                                                    <i aria-hidden="true"
                                                                        class="fas fa-circle icon-separator"></i>
                                                                    <i aria-hidden="true" class="iconify"
                                                                        data-icon="feather:calendar"></i>
                                                                    <span>{{ items.tgldaftar }}</span>
                                                                </span>
                                                                <br>
                                                            </div>
                                                            <div class="meta-right">
                                                                <VIconButton v-tooltip.bottom.left="'Verifikasi'"
                                                                    label="Bottom Left" color="primary" circle
                                                                    icon="pi pi-check-circle"
                                                                    v-if="items.statusorder == 0"
                                                                    @click="orderVerify(items)"
                                                                    style="margin-right: 15px;" />

                                                                <VIconButton v-tooltip.bottom.left="'Detail'"
                                                                    label="Bottom Left" v-else color="primary" circle
                                                                    icon="pi pi-book" @click="getDetailVerify(items)"
                                                                    style="margin-right: 15px;" />
                                                                <VDropdown icon="feather:more-vertical" spaced right
                                                                    v-tooltip.bubble="'CETAK'">
                                                                    <template #content>
                                                                        <a role="menuitem"
                                                                            class="dropdown-item is-media"
                                                                            @click="cetakSEP(items)">
                                                                            <div class="icon">
                                                                                <i class="iconify"
                                                                                    data-icon="feather:printer"
                                                                                    aria-hidden="true"></i>
                                                                            </div>
                                                                            <div class="meta">
                                                                                <span>Cetak SEP</span>
                                                                                <span>Cetak Surat Elegibilitas</span>
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
                                </TabPanel>
                                <TabPanel>
                                    <template #header>
                                        <i class="fas fa-users mr-2" aria-hidden="true"></i>
                                        <span>Daftar Alat</span>
                                        <Badge :value="dataAlatKalibrasi.length" v-if="dataAlatKalibrasi.length > 0"
                                            severity="danger" class="ml-2" />
                                    </template>

                                    <div v-if="activeTab == 1">
                                        <div class="column is-6"
                                            style="margin-left: 23rem;margin-bottom: 20px;padding: 0px;margin-top: -4.25rem;">
                                            <VDatePicker v-model="item.filterTgl" is-range color="pink" trim-weeks>
                                                <template #default="{ inputValue, inputEvents }">
                                                    <VField addons>
                                                        <VControl icon="feather:calendar">
                                                            <VInput :value="inputValue.start"
                                                                v-on="inputEvents.start" />
                                                        </VControl>
                                                        <VControl>
                                                            <VButton static><i class="fas fa-arrow-right"
                                                                    aria-hidden="true"></i></VButton>
                                                        </VControl>
                                                        <VControl icon="feather:calendar">
                                                            <VInput :value="inputValue.end" v-on="inputEvents.end" />
                                                        </VControl>
                                                    </VField>
                                                </template>
                                            </VDatePicker>

                                        </div>

                                        <div class="list-view list-view-v3">
                                            <div class="search-menu-igd mb-2">
                                                <div class="search-location-igd" style="width: 100%">
                                                    <i class="iconify" data-icon="feather:search"></i>
                                                    <input type="text"
                                                        placeholder="Cari Nama Alat, Nama Perusahaan, No order Alat, No Pendaftaran"
                                                        v-model="item.qsearch"
                                                        v-on:keyup.enter="fetchAlatKalibrasi(orderAlat)" />
                                                </div>
                                                <VButton raised class="search-button-igd"
                                                    @click="fetchAlatKalibrasi(orderAlat)" :loading="isLoading">
                                                    Cari Data
                                                </VButton>
                                            </div>
                                            <VCard class="text-center pt-0 pb-0 mt-0">
                                                <VRadio v-model="orderAlat" value="0" label="Belum Setujui"
                                                    name="outlined_radio" color="success" />
                                                <VRadio v-model="orderAlat" value="2" label="Sudah Setujui"
                                                    name="outlined_radio" color="info" />
                                            </VCard>
                                            <VPlaceholderPage :class="[dataAlatKalibrasi.length !== 0 && 'is-hidden']"
                                                title="Tidak Ada Alat Hari Ini." subtitle="Silakan Pilih Tanggal"
                                                larger>
                                                <template #image>
                                                    <img class="light-image"
                                                        src="/@src/assets/illustrations/placeholders/search-4.png"
                                                        alt="" />
                                                    <img class="dark-image"
                                                        src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                        alt="" />
                                                </template>
                                            </VPlaceholderPage>
                                            <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                                                <div name="list-complete" tag="div">
                                                    <div v-for="(item, rowIndex) in dataAlatKalibrasi" :key="rowIndex">
                                                        <div
                                                            v-if="rowGroupMetadata[item.lingkupkalibrasi].index === rowIndex">
                                                            <span
                                                                style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                                                    item.lingkupkalibrasi }}</span>
                                                            <Badge :value="rowGroupMetadata[item.lingkupkalibrasi].size"
                                                                v-if="rowGroupMetadata[item.lingkupkalibrasi].size > 0"
                                                                class="ml-2 mt-2-min" />
                                                        </div>
                                                        <div class="list-view-item ">
                                                            <div class="list-view-item-inner">
                                                                <VAvatar size="small"
                                                                    picture="/images/avatars/svg/propinsi.svg"
                                                                    color="primary" bordered />
                                                                <div class="meta-left">
                                                                    <h3>
                                                                        {{ item.namaproduk }}
                                                                    </h3>
                                                                    <span>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:home"></i>
                                                                        <span>{{ item.namaperusahaan }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:calendar"></i>
                                                                        <span>{{ item.tglverifasman }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:check-circle"></i>
                                                                        <span>{{ item.nopendaftaran }}</span>
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-circle icon-separator"></i>
                                                                        <i aria-hidden="true" class="iconify"
                                                                            data-icon="feather:check-circle"></i>
                                                                        <span>{{ item.noorderalat }}</span>

                                                                    </span>
                                                                    <div>
                                                                        <VTag v-if="item.statusPengerjaan == null"
                                                                            :label="'Durasi Kalibrasi : ' + item.durasikalbrasi"
                                                                            :color="'warning'" class="ml-2" />
                                                                        <VTag v-if="item.statusPengerjaan != null"
                                                                            :label="item.statusPengerjaan"
                                                                            :color="item.statusColor" class="ml-2" />
                                                                        <VTag
                                                                            v-if="item.setujuilembarkerjapenyelia != null && item.setujuilembarkerjapenyelia == true"
                                                                            :label="'Sertifikat Disetujui Penyelia'"
                                                                            :color="'primary'" class="ml-2" />
                                                                        <VTag
                                                                            v-if="item.setujuilembarkerjaasman != null && item.setujuilembarkerjaasman == true"
                                                                            :label="'Sertifikat Disetujui Asman'"
                                                                            :color="'primary'" class="ml-2" />
                                                                    </div>
                                                                    <div>
                                                                        <span style="font-weight: bold;">Penyelia Teknik
                                                                            :
                                                                            {{ item.penyeliateknik ?? '-' }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <span style="font-weight: bold;">Pelaksana
                                                                            Teknik :
                                                                            {{ item.pelaksanateknik ?? '-' }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <div
                                                                    class="meta-right flex justify-center items-center">
                                                                    <div class="buttons">
                                                                        <VIconButton v-tooltip.bottom.left="'SPK'"
                                                                            icon="feather:printer"
                                                                            @click="cetakSpk(item)" color="warning"
                                                                            raised circle class="mr-2">
                                                                        </VIconButton>
                                                                        <VIconButton
                                                                            v-if="item.setujuilembarkerjaasman != null && item.setujuilembarkerjaasman == true"
                                                                            v-tooltip.bottom.left="'Cetak Sertifikat'"
                                                                            icon="feather:printer"
                                                                            @click="cetakSertifikatLembarKerja(item)"
                                                                            color="info" raised circle class="mr-2">
                                                                        </VIconButton>
                                                                        <VIconButton
                                                                            v-if="item.setujuilembarkerjapenyelia != null && item.setujuilembarkerjapenyelia == true && (item.setujuilembarkerjaasman == null || item.setujuilembarkerjaasman == false)"
                                                                            color="info" circle icon="fas fa-pager"
                                                                            outlined raised @click="lembarKerja(item)"
                                                                            v-tooltip.bottom.left="'Lembar Kerja'" />
                                                                        <VIconButton v-tooltip.bottom.left="'Aktivitas'"
                                                                            icon="feather:activity"
                                                                            @click="detailOrder(item)" color="info"
                                                                            raised circle class="mr-2">
                                                                        </VIconButton>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </TabPanel>

                            </TabView>
                        </div>
                        <div class="column is-4" style="margin-top: 4rem;">
                            <VTabs slider selected="Jakarta" :tabs="[
                                { label: 'Jakarta', value: 'Jakarta', icon: 'fas fa-users' },
                                { label: 'Gresik', value: 'Gresik', icon: 'fas fa-users' },
                            ]" style="margin-top: -2rem;">
                                <template #tab="{ activeValue }">
                                    <p v-if="activeValue === 'Jakarta'">
                                    <div class="tile-grid tile-grid-v2">
                                        <VPlaceholderPage :title="H.assets().notFound"
                                            :subtitle="H.assets().notFoundSubtitle" class="my-6"
                                            :class="[dataPegawaiJakarta.length !== 0 && 'is-hidden']">
                                            <template #image>
                                                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                                <img class="dark-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                    alt="" style="width:70%" />
                                            </template>
                                        </VPlaceholderPage>
                                        <TransitionGroup name="list" tag="div" class="columns is-multiline">
                                            <div class="columns is-multiline p-2"
                                                style="max-height:500px;overflow: auto;" key="1">
                                                <div v-for="(item, n) in dataPegawaiJakarta" :key="n"
                                                    class="column is-6">
                                                    <div class="tile-grid-item">
                                                        <div class="tile-grid-item-inner">
                                                            <VAvatar size="small" picture="/images/simrs/male.png"
                                                                color="primary" bordered />
                                                            <div class="meta">
                                                                <span class="dark-inverted">{{ item.namalengkap
                                                                    }}</span>
                                                                <span class="dark-inverted">{{ item.namajabatanulab
                                                                    }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </TransitionGroup>
                                    </div>
                                    </p>
                                    <p v-else-if="activeValue === 'Gresik'">
                                    <div class="tile-grid tile-grid-v2">
                                        <VPlaceholderPage :title="H.assets().notFound"
                                            :subtitle="H.assets().notFoundSubtitle" class="my-6"
                                            :class="[dataPegawaiGresik.length !== 0 && 'is-hidden']">
                                            <template #image>
                                                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                                <img class="dark-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                    alt="" style="width;70%" />
                                            </template>
                                        </VPlaceholderPage>
                                        <TransitionGroup name="list" tag="div" class="columns is-multiline">
                                            <!--Grid item-->
                                            <div class="columns is-multiline p-2"
                                                style="max-height:300px;overflow: auto;" key="1">
                                                <div v-for="(item, n) in dataPegawaiGresik" :key="n"
                                                    class="column is-6">
                                                    <div class="tile-grid-item">
                                                        <div class="tile-grid-item-inner">
                                                            <VAvatar size="small" picture="/images/simrs/male.png"
                                                                color="primary" bordered />
                                                            <div class="meta">
                                                                <span class="dark-inverted">{{ item.namalengkap
                                                                    }}</span>
                                                                <span class="dark-inverted">{{ item.namajabatanulab
                                                                    }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </TransitionGroup>
                                    </div>
                                    </p>
                                </template>
                            </VTabs>
                        </div>
                    </div>
                </div>
            </div>
            <VFlexPagination v-model:current-page="currentPage.page" :item-per-page="currentPage.limit"
                :total-items="totalData" :max-links-displayed="5">
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

        <VModal :open="modalDetailOrder" title="Verifikasi" noclose size="big" actions="right"
            @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
            <template #content>
                <div class="business-dashboard hr-dashboard">
                    <div class="columns is-multiline">
                        <div class="column is-12 p-0">
                            <div class="block-header">
                                <div class="left column is-6 p-0">
                                    <div class="current-user">
                                        <h3>{{ item.namaperusahaan }}</h3>
                                    </div>
                                </div>
                                <div class="left column is-6 p-0">
                                    <div>
                                        <div>
                                            <h4 class="block-heading">No. Pendaftaran</h4>
                                            <p class="block-hext">{{ item.nopendaftaran }}</p>
                                            <h4 class="block-heading">Tgl Registrasi</h4>
                                            <p class="block-hext">{{ item.tglregistrasi }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="column is-12 p-4 mt-5">
                    <Fieldset legend="Edit Tindakan" :toggleable="true">
                        <div class="columns pl-3">
                            <div class="column is-1 pr-0" style="padding-left: 0px;margin-right: -38px">
                                <VField label="No">
                                    <VAvatar initials="1" />
                                </VField>
                            </div>
                            <div class="column is-11 ml-5">
                                <div class="columns">
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Lokasi Kalibrasi</VLabel>
                                            <VControl>
                                                <AutoComplete v-model="item.lokasikalibrasiUpdate"
                                                    :suggestions="d_lokasikalibrasi" @complete="fetchLokasi($event)"
                                                    :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                    class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Lingkup Kalibrasi</VLabel>
                                            <VControl>
                                                <AutoComplete v-model="item.lingkupkalibrasiUpdate"
                                                    :suggestions="d_lingkup" @complete="fetchLingkup($event)"
                                                    :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                    class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Penyelia Teknik</VLabel>
                                            <VControl>
                                                <AutoComplete v-model="item.penyeliateknikUpdate"
                                                    :suggestions="d_penyelia" @complete="fetchPenyelia($event)"
                                                    :optionLabel="'label'" :dropdown="true" :minLength="3"
                                                    class="is-input" :appendTo="'body'" :loadingIcon="'pi pi-spinner'"
                                                    :field="'label'" placeholder="ketik untuk mencari..." />

                                            </VControl>
                                        </VField>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="columns pl-3">
                            <div class="column is-1 pr-0" style="padding-left: 0px;margin-right: -38px">
                            </div>
                            <div class="column is-11 ml-5">
                                <div class="columns">
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Pelaksana Teknik</VLabel>
                                            <VControl>
                                                <AutoComplete v-model="item.pelaksanaUpdate" :suggestions="d_pelaksana"
                                                    @complete="fetchPelaksana($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-2">
                                        <VField label="Durasi Hari">
                                            <VControl icon="lnir lnir-repeat-one">
                                                <VInput type="number" v-model="item.durasikalbrasiUpdate"
                                                    placeholder="Jumlah" class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="columns mt-2" style="margin-left:40px">
                                        <VButtons>
                                            <VButton color="success" raised icon="feather:edit"
                                                v-if="item.pelaksanaUpdate" @click="update(item)"
                                                :loading="isLoadingSave"> Update
                                            </VButton>
                                            <VButton raised @click="clear()"> Batal </VButton>
                                        </VButtons>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </Fieldset>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Data Alat" :toggleable="true">
                        <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataOrder">
                            <div class="columns is-multiline">
                                <div class="column is-2" style="margin-top: 27px;">
                                    <VPlaceload class="mx-2" />
                                </div>
                                <div class="column">
                                    <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
                                </div>

                            </div>
                        </div>
                        <div class="timeline-wrapper" v-else>
                            <div class="timeline-wrapper-inner">
                                <div class="timeline-container">
                                    <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan"
                                        :key="items.norec">
                                        <div :class="'dot is-' + listColor[index + 1]"></div>

                                        <div class="content-wrap is-grey">
                                            <div class="content-box">
                                                <div class="status"></div>
                                                <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                                    <i class="iconify" data-icon="feather:package"
                                                        aria-hidden="true"></i>
                                                </VIconBox>
                                                <div class="box-text" style="width:70%">
                                                    <div class="meta-text">
                                                        <p>
                                                            <span>{{ items.namaproduk }}
                                                                <VTag v-if="items.tanggalpenolakanregis != null" color="danger" label="Alat Ditolak"
                                                                    rounded>
                                                                </VTag>
                                                            </span>
                                                        </p>
                                                        <table class="tb-order">
                                                            <tr v-if="items.tanggalpenolakanregis != null">
                                                                <td>Alasan Penolakan</td>
                                                                <td>:</td>
                                                                <td>{{ items.alasanpenolakanregis ?? '' }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lingkup</td>
                                                                <td>:</td>
                                                                <td>{{ items.lingkupkalibrasi }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Lokasi</td>
                                                                <td>:</td>
                                                                <td>{{ items.lokasi }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Penyelias Teknik </td>
                                                                <td>:</td>
                                                                <td class="font-values">{{ items.penyeliateknik }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Pelaksana Teknik</td>
                                                                <td>:</td>
                                                                <td>{{ items.pelaksanateknik }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Durasi</td>
                                                                <td>:</td>
                                                                <td>
                                                                    <VTag v-if="items.durasikalbrasi" color="warning"
                                                                        rounded> {{ items.durasikalbrasi }}
                                                                    </VTag>
                                                                </td>
                                                            </tr>
                                                        </table>

                                                    </div>
                                                </div>
                                                <div class="box-end" style="width: 30%">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-6" style="margin-top: 0.5rem;">
                                                            <VIconButton v-tooltip.bottom.left="'Edit'"
                                                                icon="feather:edit" @click="edit(items)" color="warning"
                                                                raised circle class="mr-2">
                                                            </VIconButton>
                                                            <VIconButton v-tooltip.bottom.right="'Tolak'"
                                                                icon="feather:trash" @click="hapusItems(items)"
                                                                color="danger" raised circle>
                                                            </VIconButton>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                </div>
            </template>
            <template #action>
                <VButton v-if="isLoadDataSoNorec" icon="feather:printer" @click="cetakBuktiOrder(norec)" color="info"
                    :loading="isLoadingSave" raised>Cetak</VButton>
                <VButton icon="feather:save" @click="save(item)" color="info" :loading="isLoadingSave" raised>Simpan
                    Verif
                </VButton>
            </template>
        </VModal>
        <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
            @close="modalFilter = false">
            <template #content>
                <form class="modal-form">
                    <div class="columns">
                        <div class="column is-12" style="text-align: center">
                            <VField class="is-centered">
                                <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks />
                            </VField>
                        </div>
                    </div>
                </form>
            </template>
            <template #action>
                <VButton icon="feather:search" @click="changePeriode()" :loading="isLoading" color="primary" raised>
                    Filter</VButton>
            </template>
        </VModal>
        <VModal :open="modalRiwayat" noclose size="big" actions="right" @close="modalRiwayat = false"
            cancelLabel="Tutup">
            <template #content>
                <div class="column">
                    <div class="business-dashboard hr-dashboard">
                        <div class="columns is-multiline">
                            <div class="column is-12 p-0">
                                <div class="block-header">
                                    <div class="left column is-6 p-0">
                                        <div class="current-user">
                                            <h3>{{ item.namaproduk }}</h3>
                                        </div>
                                    </div>
                                    <div class="left column is-6 p-0">
                                        <div>
                                            <div>
                                                <h4 class="block-heading">Merk</h4>
                                                <p class="block-hext">{{ item.namamerk }}</p>
                                                <h4 class="block-heading">Tipe</h4>
                                                <p class="block-hext">{{ item.namatipe }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="center column is-6 p-0">
                                        <div>
                                            <div>
                                                <h4 class="block-heading">S/N</h4>
                                                <p class="block-hext">{{ item.namaserialnumber }}</p>
                                                <h4 class="block-heading">Durasi</h4>
                                                <p class="block-hext">{{ item.durasikalbrasi }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <Fieldset legend="Data Alat" :toggleable="true">
                        <div class="column" v-for="(data) in 3" style="text-align:center" v-if="isLoadDataDeatilOrder">
                            <div class="columns is-multiline">
                                <div class="column is-2" style="margin-top: 27px;">
                                    <VPlaceload class="mx-2" />
                                </div>
                                <div class="column">
                                    <VPlaceloadText :lines="4" width="75%" last-line-width="20%" />
                                </div>

                            </div>
                        </div>
                        <div class="timeline-wrapper" v-else>
                            <div class="timeline-wrapper-inner">
                                <div class="timeline-container">
                                    <div class="timeline-item is-unread" v-for="(item, index) in timelineItems"
                                        :key="index">
                                        <div class="date">
                                            <span>{{ H.formatDateIndo(item.date) }}</span>
                                        </div>
                                        <div :class="'dot is-' + listColor[index + 1]"></div>
                                        <div class="content-wrap is-grey">
                                            <div class="content-box">
                                                <div class="status"></div>
                                                <div class="box-text" style="width:70%">
                                                    <div class="meta-text">
                                                        <p>
                                                            <span>
                                                                {{ item.type }} : {{ item.nama }}
                                                            </span>
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Fieldset>
                </div>
            </template>
            <template #action>
            </template>
        </VModal>
    </div>
</template>
<script setup lang="ts">
import ApexChart from 'vue3-apexcharts'
import { useRoute, useRouter } from 'vue-router'
import { useApi } from '/@src/composable/useApi'
import { ref, computed, watch, reactive } from 'vue'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useHead } from '@vueuse/head'
import { useUserSession } from '/@src/stores/userSession'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import moment, { isDate } from 'moment'
import { useToaster } from '/@src/composable/toaster'
import Fieldset from 'primevue/fieldset'
import Dropdown from 'primevue/dropdown'
import * as H from '/@src/utils/appHelper'
import Badge from 'primevue/badge';
import * as qzService from '/@src/utils/qzTrayService'
import AutoComplete from 'primevue/autocomplete';
import TabView from 'primevue/tabview';
import TabPanel from 'primevue/tabpanel';

useHead({
    title: 'Dashboard Asman - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)
const NOREC_PD = useRoute().query.nocm as string
const themeColors = useThemeColors()
const dataSource: any = ref([])
const filters = ref('')
const d_Komponen = ref([])
const d_Produk = ref([])
const d_Dokter = ref([])
const d_DokterVerif = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
const d_Ruangan = ref([])
const modalHistori = ref(false)
const activeTab = ref(0)
let modalRiwayat: any = ref(false)
let isLoadDataDeatilOrder: any = ref(false)
const timelineItems = ref([])
let dataAlatKalibrasi: any = ref([])
let chartOP: any = ref({
    series: [],
})
const rowGroupMetadata = ref({})
const currentPage: any = ref({
    limit: 5,
    rows: 50,
})

var date = new Date();
const dateNow = date.toLocaleString('id-ID', { year: "numeric", month: "long", day: "numeric" });
let listColor: any = ref(Object.keys(useThemeColors()))
const modalDetail = ref(false)
const route = useRoute()
const userLogin = useUserSession().getUser()
let so_norec = ref('')
let statusOrder: any = ref([])
let result: any = ref([])
let dokterPraktek: any = ref([])
let dokterNonPraktek: any = ref([])
let isLoading: any = ref(false)
let modalDetailOrder: any = ref(false)
let modalFilter: any = ref(false)
let modalDetailOrderVerify: any = ref(false)
let modalGolonganDarah: any = ref(false)
let modalJenisKelamin: any = ref(false)
let modalTransaksi: any = ref(false)
let dataPasien: any = ref([])
let isLoadingSave: any = ref(false)
let isLoadDataOrder: any = ref(false)
let isLoadDataSoNorec: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataPegawaiJakarta: any = ref([])
let dataPegawaiGresik: any = ref([])
let detailOrderVerify: any = ref(0)
let detailOrderLayanan: any = ref(0)
let totalData: any = ref(0)
let isData: any = ref()
let sourceItemSelect: any = ref([])
let data2: any = ref([])
const d_lokasikalibrasi = ref([])
const d_lingkup = ref([])
const d_penyelia = ref([])
const d_pelaksana = ref([])
let chartLO: any = ref({
    series: [],
})
const item: any = ref({
    periode: reactive({
        start: new Date(),
        end: new Date(),
    }),
    filterTgl: reactive({
        start: new Date(),
        end: new Date(),
    }),

})
const order: any = ref(0)
const orderAlat: any = ref(0)
const dataOrder: any = ref(0)
const router = useRouter()

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dokterPraktek.value
    }
    return dokterPraktek.value.filter((item: any) => {
        return item.namalengkap.match(new RegExp(filters.value, 'i'))
    })
})

currentPage.value.page = computed(() => {
    try {
        return Number.parseInt(route.query.page as string) || 1
    } catch { }
    return 1
})
watch(
    () => currentPage.value.page,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            fetchDataOrder(0)
        }
    }
)
watch(
    () => currentPage.value.limit,
    (newValue, oldValue) => {
        if (newValue != oldValue) {
            fetchDataOrder(0)
        }
    }
)

const detailOrder = async (e) => {
    modalRiwayat.value = true
    item.value.namaproduk = e.namaproduk
    item.value.namamerk = e.namamerk
    item.value.namatipe = e.namatipe
    item.value.namaserialnumber = e.namaserialnumber
    item.value.durasikalbrasi = e.durasikalbrasi
    isLoadDataDeatilOrder.value = true
    const response = await useApi().get(`/asman/detail-produk?norec_pd=${e.norec_detail}`)
    timelineItems.value = response.timeline
    isLoadDataDeatilOrder.value = false
}


const fetchAlatKalibrasi = async (q: any) => {
    let dari = ''
    if (item.value.filterTgl.start) {
        dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD')
    }
    let sampai = ''
    if (item.value.filterTgl.end) {
        sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD')
    }
    let status = ''

    let statusorderasman = ''
        , search = ''
    item.value.statusorderasman = q
    if (orderAlat) statusorderasman = '&statusorderasman=' + q
    if (item.value.qsearch) search = item.value.qsearch
    isLoading.value = true
    dataAlatKalibrasi.value = []
    const response = await useApi().get(
        '/asman/get-alat-asman?dari=' + dari
        + '&sampai=' + sampai
        + '&search=' + search
        + statusorderasman
    )
    isLoading.value = false
    dataAlatKalibrasi.value = response.data

    updateRowGroupMetaData();

}

const updateRowGroupMetaData = () => {
    rowGroupMetadata.value = {};

    if (dataAlatKalibrasi.value) {
        for (let i = 0; i < dataAlatKalibrasi.value.length; i++) {
            let rowData = dataAlatKalibrasi.value[i];
            let lingkupkalibrasi = rowData.lingkupkalibrasi;

            if (i == 0) {
                rowGroupMetadata.value[lingkupkalibrasi] = { index: 0, size: 1 };
            }
            else {
                let previousRowData = dataAlatKalibrasi.value[i - 1];
                let previousRowGroup = previousRowData.lingkupkalibrasi;
                if (lingkupkalibrasi === previousRowGroup) {
                    rowGroupMetadata.value[lingkupkalibrasi].size++;
                }
                else {
                    rowGroupMetadata.value[lingkupkalibrasi] = { index: i, size: 1 };
                }
            }
        }
    }
}

const klikTab = (e: any) => {
    activeTab.value = e.index
    if (activeTab.value == 0) {
        fetchDataOrder(0)
    }
    if (activeTab.value == 1) {
        fetchAlatKalibrasi(0)
    }
}


const fetchDataOrder = async (q: any) => {
    statusOrder.value = q
    isLoading.value = true
    let dari = ''
    if (item.value.filterTgl.start) {
        dari = H.formatDate(item.value.filterTgl.start, 'YYYY-MM-DD 00:00')
    }
    let sampai = ''
    if (item.value.filterTgl.end) {
        sampai = H.formatDate(item.value.filterTgl.end, 'YYYY-MM-DD 23:59')
    }
    let search = ''
    let qnocm = ''
    let qnoregistrasi = ''
    let StatusOrder = ''
    item.value.statusOrder = q
    if (order) StatusOrder = '&statusorder=' + q
    if (item.value.search) search = '&search=' + item.value.search
    let limit: any = currentPage.value.limit
    let offset: any = route.query.page ? route.query.page : 1
    offset = (parseInt(offset) - 1) * limit
    let page: any = route.query.page ? route.query.page : 1

    await useApi().get(`asman/list-mitra-regis?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + '&dari=' + dari + '&sampai=' + sampai + StatusOrder + search).then((response) => {
        modalFilter.value = false
        dataOrder.value = response.data
        totalData.value = response.total
        response.data.forEach((element: any, i: any) => {
            element.no = i + 1
            let ini = element.namaperusahaan.split(' ')
            let init = element.namaperusahaan.substr(0, 2)
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1)
            }
            element.initials = init
        });
        isData.value = response.total
        isLoading.value = false
    }).catch((err) => {
        modalFilter.value = false
    })
    isLoading.value = false
}

const fetchDetail = async () => {
    dataPegawaiJakarta.value = []
    dataPegawaiGresik.value = []
    const response = await useApi().get(
        '/asman/get-detail-pegawai'
    )
    dataPegawaiJakarta.value = response.pegawaiJakarta
    dataPegawaiGresik.value = response.pegawaiGresik
}

const fetchLokasi = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/lokasikalibrasi_m?select=id,lokasi&param_search=lokasi&query=${filter.query}&limit=10`
    ).then((response) => {
        d_lokasikalibrasi.value = response
    })
}

const fetchLingkup = async (filter: any) => {
    await useApi().get(
        `emr/dropdown/lingkupkalibrasi_m?select=id,lingkupkalibrasi&param_search=lingkupkalibrasi&query=${filter.query}&limit=10`
    ).then((response) => {
        d_lingkup.value = response
    })
}

const fetchPenyelia = async (filter: any) => {
    let lokasi = item.value.lokasikalibrasi.value
    await useApi().get(
        `registrasi/pegawai-lokasi-kalibrasi?lokasi=${lokasi}&param_search=namalengkap&query=${filter.query}`).then((response) => {
            d_penyelia.value = response.data.map((e: any) => {
                return { label: e.namalengkap, value: e.id }
            })
        })
}

const fetchPelaksana = async (filter: any) => {
    let lokasi = item.value.lokasikalibrasi.value
    await useApi().get(
        `registrasi/pegawai-lokasi-kalibrasi?lokasi=${lokasi}&param_search=namalengkap&query=${filter.query}`).then((response) => {
            d_pelaksana.value = response.data.map((e: any) => {
                return { label: e.namalengkap, value: e.id }
            })
        })
}


const orderVerify = async (e: any) => {
    console.log(e)
    detailOrderLayanan.value = []
    modalDetailOrder.value = true
    item.value.namaperusahaan = e.namaperusahaan
    item.value.inisial = e.initials
    item.value.nopendaftaran = e.nopendaftaran
    item.value.catatan = e.keterangan
    item.value.norec = e.iddetail
    item.value.lokasikalibrasi = e.lokasikalibrasi
    item.value.lingkupkalibrasi = e.lingkupkalibrasi
    // getListPelayanan(data)
    isLoadDataOrder.value = true
    isLoadDataSoNorec.value = false
    const response = await useApi().get(`/asman/layanan-verif?norec_pd=${e.iddetail}`)
    response.detail.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    isLoadDataOrder.value = false
    detailOrderLayanan.value = response.detail
}


const getDetailVerify = (e: any) => {
    console.log(e)

    router.push({
        name: 'module-asman-detail-registrasi',
        query: {
            norec_pd: e.iddetail,
        },
    })
}

const cetakSertifikatLembarKerja = (e) => {
    console.log(e)
    H.printBlade(`asman/cetak-sertifikat-lembar-kerja?pdf=true&norec=${e.norec}&norec_detail=${e.norec_detail}`);
}



const edit = (e: any) => {
    item.value.lokasikalibrasiUpdate = {
        value: e.lokasikalibrasifk ?? '',
        label: e.lokasi ?? ''
    };
    item.value.lingkupkalibrasiUpdate = {
        value: e.lingkupfk ?? '',
        label: e.lingkupkalibrasi ?? ''
    };
    item.value.penyeliateknikUpdate = {
        value: e.penyeliateknikfk ?? '',
        label: e.penyeliateknik ?? ''
    };
    item.value.pelaksanaUpdate = {
        value: e.pelaksanateknikfk ?? '',
        label: e.pelaksanateknik ?? ''
    };
    item.value.norec_detail = e.norec_detail
    item.value.norec = e.norec
    item.value.durasikalbrasiUpdate = e.durasikalbrasi
}

const update = async (e: any) => {
    // console.log(e)
    if (!e.durasikalbrasiUpdate) {
        H.alert('error', 'Durasi harus di isi')
        return
    }
    let json = {
        'veriItem': {
            'norec': e.norec ? e.norec : '',
            'norec_detail': e.norec_detail ? e.norec_detail : '',
            'lokasikalibrasi': e.lokasikalibrasiUpdate.value,
            'lingkupkalibrasi': e.lingkupkalibrasiUpdate.value,
            'penyeliateknik': e.penyeliateknikUpdate.value,
            'pelaksana': e.pelaksanaUpdate.value,
            'durasikalbrasi': e.durasikalbrasiUpdate,
        }
    }
    isLoadingSave.value = true
    await useApi().post('/asman/save-verif-item', json).then((r) => {
        isLoadingSave.value = false
        reloadItemVerify(e.norec)
        clear()
    }).catch((error: any) => {
        isLoadingSave.value = false
        console.error('Error saat menyimpan berkas mitra:', error);

        if (error.response) {

            H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas mitra'}`);
        } else if (error.request) {

            H.alert('error', 'Tidak ada respons dari server. Silakan coba lagi.');
        } else {

            H.alert('error', `Terjadi kesalahan: ${error.message}`);
        }
    })
}

const save = async (e: any) => {
    // console.log(e)
    let json = {
        'verif': {
            'norec': e.norec ?? '',
            'lokasikalibrasi': e.lokasikalibrasi ?? '',
            'lingkupkalibrasi': e.lingkupkalibrasi ?? '',
        }
    }
    isLoadingSave.value = true
    await useApi().post('/asman/save-verif', json).then((r) => {
        isLoadingSave.value = false
        clear()
        modalDetailOrder.value = false
        fetchDataOrder(0)
    }).catch((error: any) => {
        isLoadingSave.value = false
        console.error('Error saat menyimpan berkas mitra:', error);

        if (error.response) {

            H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas mitra'}`);
        } else if (error.request) {

            H.alert('error', 'Tidak ada respons dari server. Silakan coba lagi.');
        } else {

            H.alert('error', `Terjadi kesalahan: ${error.message}`);
        }
    })
}

const cetakSpk = (e) => {
    console.log(e)

    H.printBlade(`asman/cetak-spk?pdf=true&norec=${e.norec}&penyeliateknikfk=${e.penyeliateknikfk}`);
}

const lembarKerja = (e: any) => {
    console.log(e)
    router.push({
        name: 'module-asman-lembar-kerja',
        query: {
            norec: e.norec,
            norec_detail: e.norec_detail,
        }
    })
}



const reloadItemVerify = async (e: any) => {
    const response = await useApi().get(`/asman/layanan-verif?norec_pd=${e}`)
    response.detail.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailOrderLayanan.value = response.detail
}


const hapusItems = (e: any) => {
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        if (detailOrderLayanan.value[i].no == e.no) {
            detailOrderLayanan.value.splice(i, 1);
        }
    }
    dataSource.value = detailOrderLayanan.value
}


const changeSwitch = (e: any) => {
    fetchDataOrder(e)
}

const changeSwitchAlat = (e: any) => {
    fetchAlatKalibrasi(e)
}

const clear = () => {
    item.value.id = ''
    delete item.value.no
    item.value.pelaksanaUpdate = ''
    item.value.lokasikalibrasiUpdate = ''
    item.value.lingkupkalibrasiUpdate = ''
    item.value.penyeliateknikUpdate = ''
    item.value.durasikalbrasiUpdate = ''
}

const showModalFilter = () => {
    modalFilter.value = true
}


const goTo = (e: any) => {

    let bindData = sourceItemSelect.value
    router.push({
        name: 'module-radiologi-transaksi-radiologi',
        query: {
            nocmfk: bindData.nocmfk,
            norec_pasien_daftar: bindData.norec_pd,
            norec_apd: bindData.norec_apd
        },

    })
}

const changePeriode = () => {
    fetchDataOrder(0)
}


const cetakOrder = async (e: any) => {
    let so_norec = e.norec;
    H.printBlade(`report/cetak-order?type=radiologi&noregistrasi=${so_norec}`)
}
const cetakSEP = (e: any) => {
    qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",
        'SEP', 1)
}

const reload = async () => {
    fetchDataOrder(0)
}

watch(
    () => [
        order.value
    ], () => {
        changeSwitch(order.value)
    }
)

watch(
    () => [
        orderAlat.value
    ], () => {
        changeSwitchAlat(orderAlat.value)
    }
)

fetchAlatKalibrasi(0)
fetchDataOrder(0)
fetchDetail()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
@import '/@src/scss/module/dashboard/penyelia.scss';
@import '/@src/scss/module/dashboard/bedah.scss';

.tabs-wrapper.is-slider .tabs,
.tabs-wrapper-alt.is-slider .tabs {
    position: relative;
    background: var(--fade-grey-light-2);
    border: 1px solid var(--fade-grey);
    max-width: 400px;
    height: 35px;
    border-bottom: none;

}

.tb-order .text-value {
    font-family: var(--font-alt);
    color: var(--dark-text);
    font-weight: 400;
    font-size: 12px;
}

.user-grid-v2 {
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
            font-size: 0.95rem;
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

    .grid-item-wrap {
        border: 1px solid var(--fade-grey-dark-3);
        border-radius: var(--radius-large);
        transition: all 0.3s; // transition-all test

        .grid-item-head {
            background: #fafafa;
            border-radius: var(--radius-large) 6px 0 0;
            padding: 20px;

            .flex-head {
                display: flex;
                align-items: center;
                justify-content: space-between;
                margin-bottom: 12px;

                .meta {
                    span {
                        display: flex;

                        &:first-child {
                            font-family: var(--font-alt);
                            font-weight: 600;
                            font-size: 0.85rem;
                            color: white;
                        }

                        &:nth-child(2) {
                            font-size: 0.8rem;
                            color: white;
                        }
                    }
                }

                .status-icon {
                    height: 28px;
                    width: 28px;
                    min-width: 28px;
                    border-radius: var(--radius-rounded);
                    border: 1px solid var(--fade-grey-dark-3);
                    display: flex;
                    align-items: center;
                    justify-content: center;

                    &.is-success {
                        background: var(--success);
                        border-color: var(--success);
                        color: var(--white);
                    }

                    &.is-warning {
                        background: var(--orange);
                        border-color: var(--orange);
                        color: var(--white);
                    }

                    &.is-danger {
                        background: var(--danger);
                        border-color: var(--danger);
                        color: var(--white);
                    }

                    i {
                        font-size: 8px;
                    }
                }
            }

            .buttons {
                display: flex;
                justify-content: space-between;
                margin-bottom: 0;

                .button,
                .v-button {
                    width: calc(50% - 4px);
                    color: var(--light-text);
                    margin-bottom: 0;

                    &:hover,
                    &:focus {
                        border-color: var(--fade-grey-dark-4);
                        color: var(--primary);
                        box-shadow: var(--light-box-shadow);
                    }
                }
            }
        }

        .grid-item {
            border-top-left-radius: 0;
            border-top-right-radius: 0;
            border: none;
        }
    }
}

.is-dark {
    .user-grid {
        .grid-item {
            @include vuero-card--dark;
        }
    }

    .user-grid-v2 {
        .grid-item-wrap {
            border-color: var(--dark-sidebar-light-12);

            .grid-item-head {
                background: var(--dark-sidebar-light-4);
            }
        }
    }
}

.user-grid-v2 .grid-item-wrap .grid-item-head.is-registrasi {
    background: var(--success) !important
}

.user-grid-v2 .grid-item-wrap .grid-item-head {
    padding: 10px;
}

.search-menu-rad {
    height: 56px !important;
    white-space: nowrap;
    display: flex;
    flex-shrink: 0;
    align-items: center;
    background-color: white;
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

    .search-location-rad,
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
            height: 90%;
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

    .search-button-rad {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px !important;
        border: none;
        font-weight: 500;
        font-family: var(--font);
        padding: 0 1rem;
        border-radius: 0 0.75rem 0.75rem 0;
        color: white;
        cursor: pointer;
        margin-left: auto;
    }
}

.search-widget {
    flex: 1;
    display: inline-block;
    width: 100%;
    padding: 10px;
    background-color: var(--white);
    border-radius: 16px;
    border: 1px solid var(--fade-grey-dark-3);
    transition: all 0.3s;
}
</style>
