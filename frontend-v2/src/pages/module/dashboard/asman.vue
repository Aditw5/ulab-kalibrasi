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
                                    <VTag @click="showModalFilter()" color="danger" rounded elevated
                                        style="position: relative; bottom: -1.5rem; cursor:pointer, height: 3em">{{
                                            H.formatDateToLocalString(item.periode.start) ==
                                                H.formatDateToLocalString(item.periode.end) ?
                                                H.formatDateToLocalString(item.periode.start) :
                                                H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                                                    H.formatDateToLocalString(item.periode.end) : '')
                                        }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                                </div>
                            </div>
                        </div>
                    </div>
                    <Badge :value="dataOrder.length" v-if="dataOrder.length > 0" severity="danger"
                        style="z-index: 6;top: 38px;position: relative; left: 20px" />
                    <div class="columns is-multiline">
                        <div class="column is-8" style="margin-top: 2rem;">
                            <div class="list-view list-view-v3">
                                <div class="search-menu-rad mb-2">
                                    <div class="search-location-rad" style="width: 100%">
                                        <i class="iconify" data-icon="feather:search"></i>
                                        <input type="text" placeholder="Cari Nama Perusahaan, No Pendaftaran"
                                            v-model="item.search" v-on:keyup.enter="fetchDataOrder(order)" />
                                    </div>
                                    <VButton raised class="search-button-rad" @click="fetchDataOrder(order)"
                                        :loading="isLoading"> Cari Data
                                    </VButton>
                                </div>
                                <VCard class="text-center pt-0 pb-0 mt-0">
                                    <VRadio v-model="order" value="0" label="Belum Verif" name="outlined_radio"
                                        color="warning" />
                                    <VRadio v-model="order" value="1" label="Sudah Verif" name="outlined_radio"
                                        color="info" />
                                    <VRadio v-model="order" value="2" label="Ditolak" name="outlined_radio"
                                        color="info" />
                                </VCard>
                                <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                    class="my-6" :class="[dataOrder.length !== 0 && 'is-hidden']">
                                    <template #image>
                                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderPage>
                                <div class="list-view-inner mt-2" style="max-height:1000px;overflow: auto;">
                                    <div name="list-complete" tag="div">
                                        <div v-for="(items, rowIndex) in dataOrder" :key="rowIndex">
                                            <div v-if="rowGroupMetadata[items.lingkupkalibrasi].index === rowIndex">
                                                <span
                                                    style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                                        items.lingkupkalibrasi }}</span>
                                                <Badge :value="rowGroupMetadata[items.lingkupkalibrasi].size"
                                                    v-if="rowGroupMetadata[items.lingkupkalibrasi].size > 0"
                                                    class="ml-2 mt-2-min" />
                                            </div>
                                            <div class="list-view-item ">
                                                <div class="list-view-item-inner">
                                                    <VAvatar size="small" picture="/images/avatars/svg/propinsi.svg"
                                                        color="primary" bordered />
                                                    <div class="meta-left">
                                                        <div class="columns is-multiline">
                                                            <h3 style="font-weight: bold;">
                                                                {{ items.namaproduk }}
                                                            </h3>
                                                            <span class="ml-2">
                                                                ( {{ items.namaperusahaan ?? '-' }} )
                                                            </span>
                                                        </div>
                                                        <span>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:calendar"></i>
                                                            <span>{{ items.tglverifasman }}</span>
                                                            <i aria-hidden="true"
                                                                class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="feather:check-circle"></i>
                                                            <span>{{ items.nopendaftaran }}</span>
                                                            <i aria-hidden="true"
                                                                class="fas fa-circle icon-separator"></i>
                                                            <i aria-hidden="true" class="iconify"
                                                                data-icon="teenyicons:id-outline"></i>
                                                            <span>{{ items.noidentitas }}</span>

                                                        </span>
                                                        <div>
                                                            <VTag :label="'Durasi Kalibrasi : ' + items.durasikalbrasi"
                                                                :color="'warning'" class="ml-2" />
                                                            <VTag label="Ditolak" v-if="items.statusorderasman == 2"
                                                                :color="'danger'" class="ml-2" />
                                                            <VTag label="Sudah Verif" v-if="items.statusorderasman == 1"
                                                                :color="'info'" class="ml-2" />
                                                        </div>
                                                        <div>
                                                            <span style="font-weight: bold;">Penyelia Teknik :
                                                                {{ items.penyeliateknik ?? '-' }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <span style="font-weight: bold;">Pelaksana Teknik :
                                                                {{ items.pelaksanateknik ?? '-' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="meta-right flex justify-center items-center">
                                                        <div class="buttons">
                                                            <!-- <VIconButton v-if="item.statusorderpenyelia == 1" color="info"
                                                            circle icon="fas fa-pager" outlined raised
                                                            @click="lembarKerja(item)"
                                                            v-tooltip.bottom.left="'Lembar Kerja'" /> -->
                                                            <VIconButton v-tooltip.bottom.left="'Verifikasi'"
                                                                label="Bottom Left" color="primary" circle
                                                                icon="pi pi-check-circle"
                                                                v-if="items.statusorderasman == 0"
                                                                @click="orderVerify(items)"
                                                                style="margin-right: 15px;" />
                                                            <!-- <VIconButton v-tooltip.bottom.left="'Aktivitas'"
                                                            icon="feather:activity" v-if="item.statusorderpenyelia == 1"
                                                            @click="detailOrder(item)" color="info" raised circle
                                                            class="ml-2 mr-2">
                                                        </VIconButton> -->
                                                            <!-- <VIconButton color="primary" circle icon="pi pi-ellipsis-v"
                                                            raised @click="toggleOP($event, item)"
                                                            v-tooltip.bottom.left="'TINDAKAN'">
                                                        </VIconButton> -->
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="list-view-inner" style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                                <TransitionGroup name="list-complete" tag="div">
                                    <div v-for="(items, rowIndex) in dataOrder" :key="rowIndex" class="list-view-item">
                                        <div v-if="rowGroupMetadata[items.lingkupkalibrasi].index === rowIndex">
                                            <span
                                                style="font-weight: bold; font-size: 16px; font-family: var(--font-alt);">{{
                                                    items.lingkupkalibrasi }}</span>
                                            <Badge :value="rowGroupMetadata[items.lingkupkalibrasi].size"
                                                v-if="rowGroupMetadata[items.lingkupkalibrasi].size > 0"
                                                class="ml-2 mt-2-min" />
                                        </div>
                                        <div class="list-view-item-inner">
                                            <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                                                :initials="items.initials" />
                                            <div class="meta-left">
                                                <h3>
                                                    {{ items.namaproduk }} ({{ items.namaperusahaan }}) <i
                                                        aria-hidden="true"></i>
                                                </h3>
                                                <span style="color: black">
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="teenyicons:id-outline"></i>
                                                    <span> {{ items.nopendaftaran }}</span>
                                                    <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                    <i aria-hidden="true" class="iconify"
                                                        data-icon="feather:calendar"></i>
                                                    <span>{{ items.tgldaftar }}</span>
                                                </span>
                                                <br>
                                            </div>
                                            <div class="meta-right">
                                                <VIconButton v-tooltip.bottom.left="'Verifikasi'" label="Bottom Left"
                                                    color="primary" circle icon="pi pi-check-circle"
                                                    v-if="items.statusorder == 0" @click="orderVerify(items)"
                                                    style="margin-right: 15px;" />

                                                <VIconButton v-tooltip.bottom.left="'Detail'" label="Bottom Left" v-else
                                                    color="primary" circle icon="pi pi-book"
                                                    @click="getDetailVerify(items)" style="margin-right: 15px;" />
                                                <VDropdown icon="feather:more-vertical" spaced right
                                                    v-tooltip.bubble="'CETAK'">
                                                    <template #content>
                                                        <a role="menuitem" class="dropdown-item is-media"
                                                            @click="cetakSEP(items)">
                                                            <div class="icon">
                                                                <i class="iconify" data-icon="feather:printer"
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
                            </div> -->
                            </div>
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
                                                            <span>{{ items.namaproduk }}</span>
                                                        </p>
                                                        <table class="tb-order">
                                                            <tr>
                                                                <td>Merk</td>
                                                                <td>:</td>
                                                                <td>{{ items.namamerk }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Tipe</td>
                                                                <td>:</td>
                                                                <td>{{ items.namatipe }} </td>
                                                            </tr>
                                                            <tr>
                                                                <td>S/N</td>
                                                                <td>:</td>
                                                                <td>{{ items.namaserialnumber }} </td>
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
                <VButton icon="feather:trash" @click="tolak(item)" color="danger" :loading="isLoadingSave" raised>
                    Tolak
                </VButton>
                <VButton icon="feather:save" @click="save(item)" color="info" :loading="isLoadingSave" raised>Simpan
                    Verif
                </VButton>
            </template>
        </VModal>

        <!-- <VModal :open="modalDetailOrder" title="Verifikasi" noclose size="big" actions="right"
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
                                                <AutoComplete v-model="item.lokasikalibrasi"
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
                                                <AutoComplete v-model="item.lingkupkalibrasi" :suggestions="d_lingkup"
                                                    @complete="fetchLingkup($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="ketik untuk mencari..." />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="column is-4">
                                        <VField>
                                            <VLabel>Penyelia Teknik</VLabel>
                                            <VControl>
                                                <AutoComplete v-model="item.penyeliateknik" :suggestions="d_penyelia"
                                                    @complete="fetchPenyelia($event)" :optionLabel="'label'"
                                                    :dropdown="true" :minLength="3" class="is-input" :appendTo="'body'"
                                                    :loadingIcon="'pi pi-spinner'" :field="'label'"
                                                    placeholder="ketik untuk mencari..." />

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
                                                <AutoComplete v-model="item.pelaksana" :suggestions="d_pelaksana"
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
                                                <VInput type="number" v-model="item.durasikalbrasi" placeholder="Jumlah"
                                                    class="is-rounded" />
                                            </VControl>
                                        </VField>
                                    </div>
                                    <div class="columns mt-2" style="margin-left:40px">
                                        <VButtons>
                                            <VButton color="success" raised icon="feather:edit" v-if="item.pelaksana"
                                                @click="update(item)" :loading="isLoadingSave"> Update
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
                                                            <span>{{ items.namaproduk }}</span>
                                                        </p>
                                                        <table class="tb-order">
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
        </VModal> -->
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
        <VModal :open="modalTolak" title="Batal Registrasi" size="medium" actions="right"
            @close="modalTolak = false" cancelLabel="Tutup">
            <template #content>
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <VField>
                            <VLabel class="required-field">Tanggal Batal</VLabel>
                            <VDatePicker v-model="item.tanggalpembatalan" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents"
                                                disabled />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <span
                            style="margin-bottom:1rem;font-weight: bold; font-size: 12px; font-family: var(--font-alt);">Alasan
                            Penolakan
                        </span>

                        <VField>
                            <VControl>
                                <VTextarea class="textarea is-rounded" v-model="item.alasanpenolakanasman" rows="4"
                                    placeholder="Alasan Penolakan" autocomplete="off" autocapitalize="off"
                                    spellcheck="true" />
                            </VControl>
                        </VField>
                    </div>

                </div>
            </template>
            <template #action>
                <VButton icon="feather:plus" color="primary" @click="saveTolak(item)" :loading="isLoading" raised>Simpan
                </VButton>
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
import FloatingButton from "../emr/float-tambah.vue"
import Badge from 'primevue/badge';
import * as qzService from '/@src/utils/qzTrayService'
import AutoComplete from 'primevue/autocomplete';

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
const modalTolak: any = ref(false)
const d_lokasikalibrasi = ref([])
const d_lingkup = ref([])
const d_penyelia = ref([])
const d_pelaksana = ref([])
let chartLO: any = ref({
    series: [],
})
const item: any = ref({
    tanggalpembatalan: new Date(),
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

const updateRowGroupMetaData = () => {
    rowGroupMetadata.value = {};

    if (dataOrder.value) {
        for (let i = 0; i < dataOrder.value.length; i++) {
            let rowData = dataOrder.value[i];
            let lingkupkalibrasi = rowData.lingkupkalibrasi;

            if (i == 0) {
                rowGroupMetadata.value[lingkupkalibrasi] = { index: 0, size: 1 };
            }
            else {
                let previousRowData = dataOrder.value[i - 1];
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

const fetchDataOrder = async (q: any) => {
    statusOrder.value = q
    isLoading.value = true
    let dari = 'dari=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD 00:00')
    let sampai = '&sampai=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD  23:59')
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

    await useApi().get(`asman/list-mitra-regis?page=${page}&offset=${offset}&limit=${limit}&rows=${currentPage.value.rows}&` + dari + sampai + StatusOrder + search).then((response) => {
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
    updateRowGroupMetaData();
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
    item.value.norec = e.norec_detail
    item.value.namamerk = e.namamerk
    item.value.namatipe = e.namatipe
    item.value.namaserialnumber = e.namaserialnumber
    // getListPelayanan(data)
    isLoadDataOrder.value = true
    isLoadDataSoNorec.value = false
    const response = await useApi().get(`/asman/layanan-verif?norec_pd=${e.norec_detail}`)
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

// const getDetailVerify = async (e: any) => {
//     console.log(e)
//     isLoadDataSoNorec = false
//     item.value.inisial = e.initials
//     item.value.tglregistrasi = e.tglregistrasi

//     modalDetailOrderVerify.value = true
//     // const response = await useApi().get(`/dashboard/radiologi/get-order-verify?norec_so=${e.norec}`)
//     // const diagnosa = await useApi().get(`/dashboard/radiologi?tglAwal=${e.tglregistrasi}&tglAkhir=${e.tglregistrasi}&statusorder=${statusOrder.value}&noorder=${e.noorder}`)
//     // detailDiagnosa.value = diagnosa ? diagnosa[0].detailDiagnosa : ''
//     // response.forEach((element: any, i: any) => {
//     //     element.no = i + 1
//     // });
//     // detailOrderVerify.value = response
// }

// const edit = (e: any) => {
//     item.value.lokasikalibrasi = {
//         value: e.lokasikalibrasifk ?? '',
//         label: e.lokasi ?? ''
//     };
//     item.value.lingkupkalibrasi = {
//         value: e.lingkupfk ?? '',
//         label: e.lingkupkalibrasi ?? ''
//     };
//     item.value.penyeliateknik = {
//         value: e.penyeliateknikfk ?? '',
//         label: e.penyeliateknik ?? ''
//     };
//     item.value.pelaksana = {
//         value: e.pelaksanateknikfk ?? '',
//         label: e.pelaksanateknik ?? ''
//     };
//     item.value.norec_detail = e.norec_detail
//     item.value.norec = e.norec
//     item.value.durasikalbrasi = e.durasikalbrasi
// }

// const update = async (e: any) => {
//     console.log(e)
//     if (!e.durasikalbrasi) {
//         H.alert('error', 'Durasi harus di isi')
//         return
//     }
//     let json = {
//         'veriItem': {
//             'norec': e.norec_detail ? e.norec_detail : '',
//             'lokasikalibrasi': e.lokasikalibrasi.value,
//             'lingkupkalibrasi': e.lingkupkalibrasi.value,
//             'penyeliateknik': e.penyeliateknik.value,
//             'pelaksana': e.pelaksana.value,
//             'durasikalbrasi': e.durasikalbrasi,
//         }
//     }
//     isLoadingSave.value = true
//     await useApi().post('/asman/save-verif-item', json).then((r) => {
//         isLoadingSave.value = false
//         reloadItemVerify(e.norec)
//         clear()
//     }).catch((error: any) => {
//         isLoadingSave.value = false
//         console.error('Error saat menyimpan berkas mitra:', error);

//         if (error.response) {

//             H.alert('error', `Kesalahan: ${error.response.status} - ${error.response.data.message || 'Gagal menyimpan berkas mitra'}`);
//         } else if (error.request) {

//             H.alert('error', 'Tidak ada respons dari server. Silakan coba lagi.');
//         } else {

//             H.alert('error', `Terjadi kesalahan: ${error.message}`);
//         }
//     })
// }

const save = async (e: any) => {
    console.log(e)
    let json = {
        'verif': {
            'norec': e.norec ?? '',
        }
    }
    isLoadingSave.value = true
    await useApi().post('/asman/save-verif', json).then((r) => {
        isLoadingSave.value = false
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

const tolak = async (e: any) => {
    item.norec = e.norec
    modalTolak.value = true
}

const saveTolak = async (item : any) => {
    console.log(item)
  if (!item.alasanpenolakanasman) { H.alert('warning', 'Alasan Penolakan harus di isi'); return }
  let json = {
    mitraregis: {
      'norec': item.norec,
      'alasanpenolakanasman': item.alasanpenolakanasman,
    }
  }
  isLoading.value = true
  await useApi()
    .post(`/asman/save-penolakan`, json)
    .then((response: any) => {
      isLoading.value = false
      modalDetailOrder.value = false
      modalTolak.value = false
      clear()
      fetchDataOrder(0)
    })
    .catch((e: any) => {
      isLoading.value = false
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

const clear = () => {
    item.value.id = ''
    delete item.value.no
    item.value.pelaksana = ''
    item.value.lokasikalibrasi = ''
    item.value.lingkupkalibrasi = ''
    item.value.penyeliateknik = ''
    item.value.durasikalbrasi = ''
    item.value.alasanpenolakanasman = ''
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

fetchDataOrder(0)
fetchDetail()
</script>

<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/custom/config';
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
