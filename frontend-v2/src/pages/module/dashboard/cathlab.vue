<template>
    <div class="business-dashboard hr-dashboard">
        <div class="columns">
            <div class="column is-8">
                <div class="columns is-multiline">
                    <div class="column is-12">
                        <div class="illustration-header-2 large-screen">
                            <div class="header-image">
                                <img src="/@src/assets/illustrations/dashboards/lifestyle/da.png" alt=""
                                    style="max-width:75%; margin-left: 2rem; margin-top: 0.5rem;" />
                            </div>

                            <div class="header-meta" style="margin-left : -2rem;">
                                <h3 style="color:white"><i class="fas fa-id-card" aria-hidden="true"></i> Dashboard
                                    Cathlab
                                </h3>
                                <p>
                                    Selamat Datang , {{ userLogin.pegawai.namaLengkap }}
                                </p>
                                <VControl>
                                    <Multiselect mode="single" v-model="item.filterRuangan" :options="d_Ruangan"
                                        class="f-text" placeholder="Filter ruangan" :searchable="true" autocomplete="off"
                                        @select="changeRuang(item.filterRuangan)" />
                                </VControl>
                            </div>
                        </div>
                    </div>
                </div>
                <VTag @click="showModalFilter()" color="danger" rounded elevated
                    style="position: relative; bottom: -1.5rem; cursor:pointer" :style="styleFilter">{{
                        H.formatDateToLocalString(item.periode.start) == H.formatDateToLocalString(item.periode.end) ?
                        H.formatDateToLocalString(item.periode.start) :
                        H.formatDateToLocalString(item.periode.start) + ' - ' + (item.periode.end ?
                            H.formatDateToLocalString(item.periode.end) : '')
                    }} <i class="fas fa-filter ml-3" aria-hidden="true"></i></VTag>
                <div class="column is-12" style="margin-top: 1rem;">
                    <VTabs slider selected="Pasien" :tabs="[
                                { label: 'Daftar Order', value: 'Pasien' },
                                { label: 'Pasien Penunjang', value: 'Penunjang' },
                            ]" style="margin-top: -2rem;">
                        <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Pasien'">
                            <div class="list-view list-view-v3">
                                <Vcard>
                                    <div class="search-menu mb-2">
                                        <div class="search-location" style="width: 100%">
                                            <i class="iconify" data-icon="feather:search"></i>
                                            <input type="text"
                                                placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK"
                                                v-model="item.search" v-on:keyup.enter="fetchDataOrder(order)" />
                                        </div>
                                        <!-- <div class="search-location">
                                            <i class="iconify" data-icon="feather:activity"></i>
                                            <input type="text" placeholder="No Registrasi" v-model="item.qnoregistrasi" />
                                        </div>
                                        <div class="search-salary">
                                            <i class="iconify" data-icon="feather:clipboard"></i>
                                            <input type="text" placeholder="No RM" v-model="item.qnocm" />
                                        </div>
                                        <div class="search-job">
                                            <i class="iconify" data-icon="feather:user"></i>
                                            <input type="text" placeholder="Nama Pasien" v-model="item.qnamapasien" />
                                        </div> -->
                                        <VButton raised class="search-button" @click="fetchDataOrder(order)"
                                            :loading="isLoading"> Cari Data
                                        </VButton>
                                    </div>
                                </Vcard>
                                <VCard class="text-center pt-0 pb-0 mt-0">
                                    <VRadio v-model="order" value="0" label="Pending" name="outlined_radio"
                                        color="warning" />
                                    <VRadio v-model="order" value="1" label="Verifikasi" name="outlined_radio"
                                        color="info" />
                                    <VRadio v-model="order" value="2" label="Selesai Pelayanan" name="outlined_radio"
                                        color="primary" />

                                </VCard>

                                <!-- <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                    class="my-6" :class="[dataOrder.length !== 0 && 'is-hidden']">
                                    <template #image>
                                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderPage> -->
                                <div class="list-view-inner" style="max-height:500px;overflow: auto; margin-top: 1rem; ">
                                    <TransitionGroup name="list-complete" tag="div">
                                        <!--Item-->
                                        <div v-for="(items, i) in dataOrder" :key="items.id" class="list-view-item">
                                            <div class="list-view-item-inner">
                                                <VAvatar size="small" style="left: 8px;top: 4px;" :color="listColor[i]"
                                                    :initials="items.initials" />
                                                <div class="meta-left">
                                                    <h3>
                                                        {{ items.namapasien }} <i
                                                            :class="item.objectjeniskelaminfk == 1 ? 'fas fa-venus' : 'fas fa-mars'"
                                                            aria-hidden="true"
                                                            :style="'color:' + (items.objectjeniskelaminfk == 1 ? 'var(--light-blue)' : 'var(--pink)')"></i>
                                                        |
                                                        <VTag v-if="items.kelompokpasien != null" class="mt-3 ml-2"
                                                            :label="items.kelompokpasien"
                                                            :color="items.kelompokpasien == 'BPJS' ? 'green' : 'orange'"
                                                            rounded /> |
                                                        <i class="bulet fas fa-circle"></i>
                                                        {{ items.namakelas }}
                                                    </h3>
                                                    <span>
                                                        <!-- <i aria-hidden="true" class="iconify" data-icon="feather:user"></i>
                                                        <span>{{ items.jeniskelamin }}</span> -->
                                                        <!-- <i aria-hidden="true" class="fas fa-circle icon-separator"></i> -->
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:map-pin"></i>
                                                        <span>{{ items.asal_ruangan }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify" data-icon="feather:clock"></i>
                                                        <span>{{ items.tglregistrasi }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:check-circle"></i>
                                                        <span>{{ items.noorder }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:clipboard"></i>
                                                        <span>{{ items.nocm }}</span><br>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:check-circle"></i>
                                                        <span>{{ items.noregistrasi }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:calendar"></i>
                                                        <span>{{ items.umur }}</span>
                                                        <i aria-hidden="true" class="fas fa-circle icon-separator"></i>
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="teenyicons:id-outline"></i>
                                                        <span>{{ items.noidentitas }}</span>

                                                    </span>
                                                    <br>
                                                    <VTag color="warning" rounded v-if="items.statusorder == 0">Pending
                                                    </VTag>
                                                    <VTag color="info" rounded v-if="items.statusorder == 1">
                                                        Terverifikasi</VTag>
                                                    <VTag color="primary" rounded v-if="items.statusorder == 2">Selesai
                                                    </VTag>
                                                </div>
                                                <div class="meta-right">
                                                    <VIconButton v-if="items.statusorder == 0"
                                                        v-tooltip.bottom="'Verifikasi'" color="primary" circle
                                                        icon="pi pi-arrow-right" @click="orderVerify(items)"
                                                        :loading="items.loading" style="margin-right: 15px;" />
                                                    <VIconButton v-else v-tooltip.bottom="'Detail'" color="blue" circle
                                                        icon="fas fa-file-medical-alt" @click="getDetailVerify(items)"
                                                        :loading="items.loading" style="margin-right: 15px;" />
                                                    <VIconButton color="primary" circle icon="fas fa-stethoscope" outlined
                                                        raised @click="emr(items)" v-tooltip.bottom="'EMR'"
                                                        style="margin-right: 15px;">
                                                    </VIconButton>
                                                    <VIconButton v-tooltip.bottom.left="'Cetak SEP'" label="Bottom Left"
                                                        color="warning" circle icon="pi pi-print" @click="cetakSEP(items)"
                                                        :loading="item.loading">
                                                    </VIconButton>
                                                </div>
                                                <!-- <div class="meta-right">
                                                    <VButton color="primary" raised style="margin-top: 11px;"
                                                        v-if="items.statusorder == 0" @click="orderVerify(items)">
                                                        Verifikasi <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                                    </VButton>
                                                    <VButton color="primary" raised style="margin-top: 11px;" v-else
                                                        @click="getDetailVerify(items)">
                                                        Detail
                                                    </VButton>
                                                </div> -->
                                            </div>
                                        </div>
                                    </TransitionGroup>
                                </div>
                            </div>
                            </p>
                            <p v-else-if="activeValue === 'Penunjang'">
                            <div class="search-menu mb-2">
                                <div class="search-location" style="width: 100%">
                                    <i class="iconify" data-icon="feather:search"></i>
                                    <input type="text"
                                        placeholder="Cari Nama Pasien, No Registrasi, No RM, BPJS, Atau NIK Penunjang"
                                        v-model="item.qsearch" v-on:keyup.enter="fetchPenunjang()" />
                                </div>
                                <!-- <div class="search-location">
                                    <i class="iconify" data-icon="feather:activity"></i>
                                    <input type="text" placeholder="No Registrasi" v-model="item.qnoregistrasi" />
                                </div>
                                <div class="search-salary">
                                    <i class="iconify" data-icon="feather:clipboard"></i>
                                    <input type="text" placeholder="No RM" v-model="item.qnocm" />
                                </div>
                                <div class="search-job">
                                    <i class="iconify" data-icon="feather:user"></i>
                                    <input type="text" placeholder="Nama Pasien" v-model="item.qnama" />
                                </div> -->
                                <VButton raised class="search-button" @click="fetchPenunjang()" :loading="isLoading"> Cari
                                    Data
                                </VButton>
                            </div>
                            <VCard radius="rounded">

                                <VCard>
                                    <div class="user-grid user-grid-v2">
                                        <div class="columns is-multiline" v-if="dataPenunjang.loading">
                                            <!--Grid item-->
                                            <div v-for="key in 8" :key="key" class="column is-4">
                                                <div class="grid-item">
                                                    <VPlaceloadAvatar size="big" centered class="mb-2" />

                                                    <VPlaceloadText class="mb-4" width="80%" :lines="3"
                                                        last-line-width="60%" centered />

                                                    <div class="people">
                                                        <VPlaceloadAvatar size="small" class="mx-1" />
                                                        <VPlaceloadAvatar size="small" class="mx-1" />
                                                        <VPlaceloadAvatar size="small" class="mx-1" />
                                                        <VPlaceloadAvatar size="small" class="mx-1" />
                                                    </div>

                                                    <VButtons>
                                                        <VButton placeload="100%" dark-outlined>loading ...</VButton>
                                                        <VButton placeload="100%" dark-outlined>loading ...</VButton>
                                                    </VButtons>
                                                </div>
                                            </div>
                                        </div>

                                        <VPlaceholderPage v-else-if="dataPenunjang.length === 0"
                                            :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle" larger>
                                            <template #image>
                                                <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                                <img class="dark-image"
                                                    src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                    alt="" />
                                            </template>
                                        </VPlaceholderPage>

                                        <TransitionGroup name="list" tag="div" class="columns is-multiline"
                                            v-else-if="dataPenunjang.length > 0">

                                            <div v-for="item in dataPenunjang" :key="item.norec" class="column is-4">
                                                <div class="grid-item-wrap is-clickable">
                                                    <!-- @click="clickCard(item)" -->
                                                    <div :class="'grid-item-head ' + (
                                                        item.norec_apd != null ? 'is-registrasi' : ''
                                                    )">
                                                        <div class="flex-head">

                                                            <div class="meta">
                                                                <span v-if="item.norec_apd != null" class="dark-inverted">
                                                                    {{ item.ruanganasal }}
                                                                </span>

                                                                <span>
                                                                    {{
                                                                        H.formatDateIndoSimple(item.tglmasuk)
                                                                    }}
                                                                </span>
                                                            </div>
                                                            <div v-if="item.expertise != false" class="status-icon "
                                                                style="background-color: white; margin-left: -20px;">
                                                                <i aria-hidden="true" class="fas fa-check "
                                                                    style="color:var(--success)"></i>
                                                            </div>
                                                            <div v-if="item.expertise == false"
                                                                class="status-icon is-danger" style="margin-left: -20px;">
                                                                <i aria-hidden="true" class="fas fa-times"></i>
                                                            </div>
                                                        </div>

                                                    </div>
                                                    <div class="flex-head"
                                                        style=" display: flex; justify-content: space-between;">
                                                        <VTag v-if="item.kelompokpasien != null" class="mt-2 ml-2"
                                                            :label="item.kelompokpasien"
                                                            :color="item.kelompokpasien == 'BPJS' ? 'green' : 'orange'"
                                                            rounded />
                                                        <VDropdown icon="feather:more-vertical" spaced right
                                                            v-tooltip.bubble="'TINDAKAN'">
                                                            <template #content>
                                                                <a role="menuitem" @click="PengkajianMedis(item)"
                                                                    class="dropdown-item is-media">
                                                                    <div class="icon">
                                                                        <i aria-hidden="true"
                                                                            class="lnil lnil-medical-sign"></i>
                                                                    </div>
                                                                    <div class="meta">
                                                                        <span>Pengkajian Medis</span>
                                                                    </div>
                                                                </a>
                                                                <a role="menuitem" @click="UpdateJenisKelamin(item)"
                                                                    class="dropdown-item is-media">
                                                                    <div class="icon">
                                                                        <i aria-hidden="true"
                                                                            class="lnil lnil-user-alt"></i>
                                                                    </div>
                                                                    <div class="meta">
                                                                        <span>Ubah Jenis Kelamin</span>
                                                                    </div>
                                                                </a>
                                                                <a role="menuitem" @click="UpdateGolonganDarah(item)"
                                                                    class="dropdown-item is-media">
                                                                    <div class="icon">
                                                                        <i aria-hidden="true" class="lnil lnil-pencil"></i>
                                                                    </div>
                                                                    <div class="meta">
                                                                        <span>Ubah Golongan Darah</span>
                                                                    </div>
                                                                </a>

                                                            </template>
                                                        </VDropdown>
                                                    </div>
                                                    <div class="grid-item">
                                                        <VAvatar
                                                            :picture="(item.foto != null ? item.foto : '/images/other/no_image.jpg')"
                                                            :badge="(item.objectjeniskelaminfk == '1' ? '/images/other/male.png'
                                                                : '/images/other/female.png')" size="big" />
                                                        <h3 class="dark-inverted">{{ item.namapasien }}</h3>
                                                        <p>Gol Darah : {{ item.golongandarah }}</p>
                                                        <p>No RM : {{ item.nocm }}</p>
                                                        <p>No BPJS : {{ item.nobpjs }}</p>
                                                        <p>NIK : {{ item.noidentitas }}</p>
                                                        <p>Noregistrasi : {{ item.noregistrasi }}</p>

                                                        <!-- <div class="buttons">
                                                            <button class="button v-button is-primary"
                                                                @click="transaksiPelayanan(item)" style="width:100%">
                                                                <span style="color:white">Rincian</span>
                                                                <span class="icon" style="color:white">
                                                                    <i aria-hidden="true" class="iconify"
                                                                        data-icon="feather:arrow-right"></i>
                                                                </span>
                                                            </button>
                                                        </div> -->
                                                        <div class="buttons mt-4">
                                                            <VIconButton v-tooltip.bottom.left="'Rincian'"
                                                                label="Bottom Left" color="info" outlined circle
                                                                icon="pi pi-arrow-right" @click="transaksiPelayanan(item)"
                                                                :loading="item.loading" />
                                                            <VIconButton v-tooltip.bottom.left="'EMR'" color="success"
                                                                outlined circle icon="fas fa-stethoscope"
                                                                @click="emrPenunjang(item)" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </TransitionGroup>
                                    </div>
                                </VCard>
                            </VCard>
                            </p>
                        </template>
                    </VTabs>
                </div>
            </div>
            <div class="column is-4">
                <div class="column is-12  p-0">
                    <VCard>
                        <ApexChart height="220" type="bar" :series="chartLO.series" :options="chartLO">
                        </ApexChart>
                    </VCard>
                </div>
                <div class="column is-12" style="margin-top: 3rem;">

                    <VTabs slider selected="Dokter" :tabs="[
                        { label: 'Jadwal Dokter', value: 'Dokter', icon: 'fas fa-users' },
                        { label: 'Stok Barang', value: 'Stok', icon: 'feather:box' },
                    ]" style="margin-top: -2rem;">

                        <template #tab="{ activeValue }">
                            <p v-if="activeValue === 'Dokter'">
                                <UIWidget class="search-widget">
                                    <template #body>
                                        <div class="field" style="padding: 2px">
                                            <div class="control">
                                                <input v-model="filters" class="input custom-text-filter"
                                                    placeholder="Cari Dokter Praktek" />
                                                <button class="searcv-button">
                                                    <i aria-hidden="true" class="iconify" data-icon="feather:search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </UIWidget>
                            <div class="tile-grid tile-grid-v2">
                                <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                    class="my-6" :class="[dataSourcefiltered.length !== 0 && 'is-hidden']">
                                    <template #image>
                                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt="" />
                                    </template>
                                </VPlaceholderPage>

                                <!--Tile Grid v1-->
                                <Vcard>
                                    <TransitionGroup name="list" tag="div" class="columns is-multiline">
                                        <!--Grid item-->

                                        <div class="columns is-multiline p-2" style="max-height:500px;overflow: auto;">
                                            <div v-for="item in dataSourcefiltered" :key="item.id" class="column is-12">
                                                <div class="tile-grid-item">
                                                    <div class="tile-grid-item-inner">
                                                        <VAvatar size="small" picture="/images/avatars/svg/dokter.svg"
                                                            color="primary" bordered />
                                                        <div class="meta">
                                                            <span class="dark-inverted">{{ item.namalengkap }}</span>
                                                            <span> <i aria-hidden="true" class="iconify"
                                                                    data-icon="feather:clock"
                                                                    style="padding-right: 3px;"></i> {{ item.jammulai }} s.d
                                                                {{ item.jamakhir }}</span>
                                                        </div>
                                                        <VTag style="margin-left: auto;" color="info" label="Tag Label"
                                                            rounded elevated> {{ item.hari }}
                                                        </VTag>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </TransitionGroup>
                                </Vcard>

                            </div>

                            </p>
                            <p v-else-if="activeValue === 'Stok'">
                            <div class="tile-grid tile-grid-v2">

                                <!--List Empty Search Placeholder -->
                                <VPlaceholderPage :title="H.assets().notFound" :subtitle="H.assets().notFoundSubtitle"
                                    class="my-6" :class="[dataStokObat.length !== 0 && 'is-hidden']">
                                    <template #image>
                                        <img class="light-image" :src="H.assets().iconNotFound_rev" alt="" />
                                        <img class="dark-image"
                                            src="/@src/assets/illustrations/placeholders/search-4-dark.svg" alt=""
                                            style="width;70%" />
                                    </template>
                                </VPlaceholderPage>

                                <!--Tile Grid v1-->

                                <TransitionGroup name="list" tag="div" class="columns is-multiline">
                                    <!--Grid item-->
                                    <div class="columns is-multiline p-2" style="max-height:300px;overflow: auto;">
                                        <div v-for="item in dataStokObat" :key="item.id" class="column is-6">
                                            <div class="tile-grid-item">
                                                <div class="tile-grid-item-inner">
                                                    <VAvatar size="small" picture="/images/simrs/produk-ico.png"
                                                        color="primary" bordered />
                                                    <div class="meta">
                                                        <span class="dark-inverted">{{ item.namaproduk }}</span>
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

    <VModal :open="modalDetailOrder" title="Verifikasi Order" noclose size="big" actions="right"
        @close="modalDetailOrder = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="business-dashboard hr-dashboard">
                <div class="columns is-multiline">
                    <div class="column is-12 p-0">
                        <div class="block-header">
                            <div class="left">
                                <div class="current-user">
                                    <VAvatar size="medium" :picture="item.jeniskelamin == 'PEREMPUAN'
                                        ? '/images/avatars/svg/vuero-4.svg'
                                        : '/images/avatars/svg/vuero-1.svg'
                                        " squared />
                                    <h3>{{ item.namapasien }}</h3>
                                    <p class="block-text">
                                        {{ item.noregistrasi + (item.jeniskelamin ==
                                            'PEREMPUAN' ? ' (P)'
                                            :
                                            ' (L)')
                                        }}</p>
                                </div>
                            </div>
                            <div class="center">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">No. Order</h4>
                                        <p class="block-text">{{ item.noorder }}</p>
                                        <h4 class="block-heading">Tgl Registrasi</h4>
                                        <p class="block-text">{{ item.tglregistrasi }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="block-heading">Diagnosa</h4>
                                        <p class="block-text">{{ detailDiagnosa ? detailDiagnosa : 'Belum Ada Diagnosa' }}
                                        </p>
                                        <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                                        <p>
                                            <VTag color="orange" :label="item.kelompokpasien" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">Ruangan Asal</h4>
                                        <p class="block-text">{{ item.ruanganasal }}</p>
                                        <h4 class="block-heading">Ruangan Tujuan</h4>
                                        <p class="block-text">{{ item.ruangantujuan }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="column is-12 p-4 mt-5">
                <Fieldset legend="Tambah Tindakan" :toggleable="true">
                    <div class="columns">
                        <div class="column is-1 pr-0" style="padding-left: 0px;margin-right: -38px">
                            <VField label="No">
                                <VAvatar initials="1" />
                            </VField>
                        </div>
                        <div class="column is-11 ml-5">
                            <div class="columns">
                                <div class="column is-4">
                                    <VField class="is-autocomplete-select">
                                        <VLabel>Layanan</VLabel>
                                        <VControl icon="feather:search">
                                            <Multiselect mode="single" v-model="item.layanan" placeholder="Pilih Layanan"
                                                :searchable="true" :options="d_Produk" @select="getHarga(item.layanan)" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField label="Harga">
                                        <VControl icon="fas fa-money-bill-wave">
                                            <VInput type="text" placeholder="Harga" v-model="item.hargaLayanan" readonly
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <VField label="Jumlah">
                                        <VInput type="text" v-model="item.jumlah" placeholder="Jumlah" class="is-rounded" />
                                    </VField>
                                </div>

                                <div class="columns mt-2" style="margin-left:40px">
                                    <VButtons>
                                        <VButton color="success" raised icon="feather:edit" v-if="item.no"
                                            @click="update(item)"> Update
                                        </VButton>
                                        <VButton color="info" raised icon="fas fa-plus" v-else @click="add(item), clear()">
                                            Tambah
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
                <Fieldset legend="Data Order Tindakan" :toggleable="true">
                    <div class="timeline-wrapper" v-if="detailOrderLayanan.length > 0">
                        <div class="timeline-header">
                        </div>
                        <div class="timeline-wrapper-inner">
                            <div class="timeline-container">
                                <div class="timeline-item is-unread" v-for="(items, index) in detailOrderLayanan"
                                    :key="items.norec">
                                    <div class="date">
                                        <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                    </div>
                                    <div :class="'dot is-' + listColor[index + 1]"></div>

                                    <div class="content-wrap is-grey">
                                        <div class="content-box">
                                            <div class="status"></div>
                                            <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                                <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                                            </VIconBox>
                                            <div class="box-text" style="width:70%">
                                                <div class="meta-text">
                                                    <p>
                                                        <span>{{ items.namaproduk }}</span>
                                                    </p>
                                                    <table class="tb-order">
                                                        <tr>
                                                            <td>Harga </td>
                                                            <td>:</td>
                                                            <td class="font-values">{{ H.formatRp(items.hargasatuan, 'Rp. ')
                                                            }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Jumlah</td>
                                                            <td>:</td>
                                                            <td>{{ items.qtyproduk }} </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Cito</td>
                                                            <td>:</td>
                                                            <td>{{ items.nilaiStatusCito }} </td>
                                                        </tr>


                                                    </table>

                                                </div>
                                            </div>
                                            <div class="box-end" style="width: 30%">
                                                <div class="columns is-multiline">
                                                    <div class="column is-6" style="margin-top: 0.5rem;">
                                                        <VIconButton v-tooltip.bottom.left="'Edit'" icon="feather:edit"
                                                            @click="edit(items)" color="warning" raised circle class="mr-2">
                                                        </VIconButton>
                                                        <VIconButton v-tooltip.bottom.right="'Hapus'" icon="feather:trash"
                                                            @click="hapusItems(items)" color="danger" raised circle>
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

            <div class="columns is-multiline" style="padding: 2rem 6rem;">
                <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                    <VCard class="is-grey">
                        <div class="columns is-multiline p-1">
                            <div class="column is-3">
                                <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select"
                                    v-slot="{ id }">
                                    <VControl icon="feather:plus-circle" fullwidth>
                                        <Dropdown v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                                            :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                            placeholder="Pilih data" style="width: 100%;" showClear :filter="true"
                                            @change="changeJenis(item)" />
                                    </VControl>
                                </VField>
                            </div>
                            <div class="column is-6">
                                <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                    <VLabel>Pegawai</VLabel>
                                    <VControl icon="feather:user" fullwidth :loading="isLoadChange"
                                        class="prime-auto-select">
                                        <MultiSelect v-model="item.pegawai" display="chip" class="w-100 is-rounded"
                                            :options="item.d_Pegawai" optionLabel="label" optionValue="value" filter
                                            placeholder="Pilih Data" :maxSelectedLabels="3" />
                                    </VControl>
                                </VField>
                            </div>

                            <div class="column is-1 mt-3">
                                <VIconButton v-if="index > 0" outlined type="button" raised circle class="is-pulled-right"
                                    icon="feather:trash" @click="removeItem(index)" color="danger">
                                </VIconButton>
                            </div>
                            <div class="column is-1 is-flex mt-3">
                                <VButton type="button" rounded outlined color="info" raised icon="feather:plus"
                                    @click="addNewItem()"> Tambah Pelaksana
                                </VButton>
                            </div>
                        </div>
                    </VCard>
                </div>
                <div class="column is-6">
                    <div class="column is-12">
                        <VField label="Dokter Order">
                            <VControl class="mt-2">
                                <VInput type="text" placeholder="Dokter Order" readonly class="is-rounded"
                                    v-model="item.dokterorder"
                                    style="cursor:pointer text-align: center;background: var(--fade-grey);" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField label="Catatan">
                            <VControl class="mt-2">
                                <VTextarea rows="4" placeholder="Tulis Catatan..." v-model="item.catatan"></VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </div>

                <div class="column is-6">
                    <div class="column is-12">
                        <VField class="is-rounded-select is-autocomplete-select">
                            <VLabel class="required-field">Dokter Verifikator</VLabel>
                            <VControl icon="feather:search" fullwidth class="prime-auto-select">
                                <Dropdown v-model="item.dokterverify" :options="d_Dokter" optionLabel="label"
                                    class="is-rounded" placeholder="Pilih data" style="width: 100%;" :filter="true" />
                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-12">
                        <VField label="Catatan Klinis">
                            <VControl class="mt-3">
                                <VTextarea rows="4" placeholder="Tulis Catatan Klinis..." v-model="item.catatankliniks">
                                </VTextarea>
                            </VControl>
                        </VField>
                    </div>
                </div>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:save" @click="save()" color="primary" raised :loading="isLoadingSave">Simpan</VButton>
        </template>
    </VModal>

    <VModal :open="modalFilter" title="Filter Periode" :noclose="true" size="small" actions="right"
        @close="modalFilter = false">
        <template #content>
            <form class="modal-form">
                <div class="columns">
                    <div class="column is-12" style="text-align: center">
                        <VField class="is-centered">
                            <v-date-picker v-model="item.periode" class="is-centered" is-range trim-weeks
                                :max-date="new Date()" />
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

    <VModal :open="modalJenisKelamin" title="Jenis Kelamin" :noclose="true" size="medium" actions="right"
        @close="modalJenisKelamin = false">
        <template #content>

            <div class="column is-12">
                <VField label="Jenis Kelamin" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:plus-circle" fullwidth>
                        <Multiselect mode="single" v-model="item.jeniskelamin" :options="d_JenisKelamin"
                            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                            track-by="value" />
                    </VControl>
                </VField>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:save" @click="saveJenisKelamin(item)" :loading="isLoading" color="primary" raised>
                Simpan</VButton>
        </template>
    </VModal>

    <VModal :open="modalGolonganDarah" title="Golongan Darah" :noclose="true" size="medium" actions="right"
        @close="modalGolonganDarah = false">
        <template #content>
            <div class="column is-12">
                <VField label="Golongan Darah" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                    <VControl icon="feather:plus-circle" fullwidth>
                        <Multiselect mode="single" v-model="item.golongandarah" :options="d_GolonganDarah"
                            placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                            track-by="value" />
                    </VControl>
                </VField>
            </div>
        </template>
        <template #action>
            <VButton icon="feather:save" @click="saveGolonganDarah(item)" :loading="isLoading" color="primary" raised>
                Simpan</VButton>
        </template>
    </VModal>

    <VModal :open="modalDetailOrderVerify" title="Detail Order" noclose size="big" actions="right"
        @close="modalDetailOrderVerify = false, clear()" cancelLabel="Tutup">
        <template #content>
            <div class="business-dashboard hr-dashboard">
                <div class="columns is-multiline">
                    <div class="column is-12 p-0">
                        <div class="block-header">
                            <div class="left">
                                <div class="current-user">
                                    <VAvatar size="medium" :picture="item.jeniskelamin == 'PEREMPUAN'
                                        ? '/images/avatars/svg/vuero-4.svg'
                                        : '/images/avatars/svg/vuero-1.svg'
                                        " squared />
                                    <h3>{{ item.namapasien }}</h3>

                                </div>
                            </div>
                            <div class="center">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">No. Order</h4>
                                        <p class="block-text">{{ item.noorder }}</p>
                                        <h4 class="block-heading">Tgl Registrasi</h4>
                                        <p class="block-text">{{ H.formatDateIndo(item.tglregistrasi) }}</p>
                                    </div>
                                    <div class="column">
                                        <h4 class="block-heading">Ruangan</h4>
                                        <p class="block-text">{{ item.ruangantujuan }}</p>
                                        <h4 class="block-heading" style="margin-top: 1rem;">Jenis Pasien</h4>
                                        <p>
                                            <VTag color="orange" :label="item.kelompokpasien" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="right">
                                <div class="columns">
                                    <div class="column">
                                        <h4 class="block-heading">Diagnosa</h4>
                                        <p class="block-text">{{ detailDiagnosa ? detailDiagnosa : 'Belum Ada Diagnosa' }}
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="column is-11">
                <div class="timeline-wrapper" v-if="detailOrderVerify.length > 0">
                    <div class="timeline-header">
                    </div>
                    <div class="timeline-wrapper-inner">
                        <div class="timeline-container">
                            <div class="timeline-item is-unread" v-for="(items, index) in detailOrderVerify"
                                :key="items.norec">
                                <div class="date">
                                    <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                </div>
                                <div :class="'dot is-' + listColor[index + 1]"></div>

                                <div class="content-wrap is-grey">
                                    <div class="content-box">
                                        <div class="status"></div>
                                        <VIconBox size="medium" :color="listColor[index + 1]" rounded>
                                            <i class="iconify" data-icon="feather:package" aria-hidden="true"></i>
                                        </VIconBox>
                                        <div class="box-text" style="width: 100%">
                                            <div class="meta-text">
                                                <p>
                                                    <span>{{ items.namaproduk }}</span>
                                                </p>
                                                <table class="tb-order mt-1">
                                                    <tr>
                                                        <td>Harga</td>
                                                        <td>:</td>
                                                        <td class="text-value">{{
                                                            H.formatRp(items.hargasatuan,
                                                                'Rp. ')
                                                        }} </td>
                                                    </tr>
                                                    <tr>
                                                        <td>Jumlah</td>
                                                        <td>:</td>
                                                        <td class="text-value">{{ items.jumlah }} </td>
                                                    </tr>

                                                    <tr>
                                                        <td>Dokter Pemeriksa</td>
                                                        <td>:</td>
                                                        <td class="text-value">{{ items.namalengkap }}</td>
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
            </div>
        </template>
    </VModal>
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
import MultiSelect from 'primevue/multiselect';
import * as H from '/@src/utils/appHelper'
import * as qzService from '/@src/utils/qzTrayService'

useHead({
    title: 'Dashboard Cathlab - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
useViewWrapper().setFullWidth(false)

const NOREC_PD = useRoute().query.nocm as string
const themeColors = useThemeColors()
const dataSource: any = ref([])
const filters = ref('')
const d_Dokter = ref([])
const d_JenisKelamin = ref([])
const d_GolonganDarah = ref([])
const d_Ruangan = ref([])
const d_JenisPelaksana = ref([])
const d_Pegawai = ref([])
const d_Produk = ref([])
const modalHistori = ref(false)
let chartOP: any = ref({
    series: [],
})
const listItem: any = ref([
    {
        pegawai: [],
        d_Pegawai: [],
        jenisPelaksana: null,
    }
])

const emr = (e: any) => {
    console.log(e)
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.pd_norec,
        }
    })
}

const emrPenunjang = (e: any) => {
    console.log(e)
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd,
        }
    })
}
const cetakSEP = (e: any) => {
    H.printBlade('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=false")
    // qzService.printData('registrasi/pemakaian-asuransi/sep?noregistrasi=' + e.noregistrasi + "&pdf=true",'SEP', 1)
}
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
const isLoadChange: any = ref(false)
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
let isLoadingSave: any = ref(false)
let detailDiagnosa: any = ref(0)
let dataStokObat: any = ref([])
let detailOrderVerify: any = ref([])
let detailOrderLayanan: any = ref([])
let dataPenunjang: any = ref([])
let isData: any = ref()
let data2: any = ref([])
let styleFilter = ref('left:42rem')
let chartLO: any = ref({
    series: [],
})
let now = new Date()
const item: any = ref({
    periode: reactive({
        start: now,
        end: now,
    }),
})
const order: any = ref(0)
const dataOrder: any = ref([])
const router = useRouter()

const dataSourcefiltered = computed(() => {
    if (!filters.value) {
        return dokterPraktek.value
    }
    return dokterPraktek.value.filter((item: any) => {
        return item.namalengkap.match(new RegExp(filters.value, 'i'))
    })
})

const fetchDataOrder = async (q: any) => {
    statusOrder.value = q
    isLoading.value = true
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    let qnamapasien = ''
    let qnocm = ''
    let qnoregistrasi = ''
    let search = ''
    let StatusOrder = ''
    item.value.statusOrder = q
    if (order) StatusOrder = '&statusorder=' + q
    if (item.value.qnamapasien) qnamapasien = '&qnamapasien=' + item.value.qnamapasien
    if (item.value.qnocm) qnocm = '&qnocm=' + item.value.qnocm
    if (item.value.search) search = '&search=' + item.value.search
    if (item.value.qnoregistrasi) qnoregistrasi = '&qnoregistrasi=' + item.value.qnoregistrasi
    await useApi().get('/dashboard/cathlab?' + tglAwal + tglAkhir + StatusOrder + qnamapasien + qnocm + qnoregistrasi + search).then((response) => {
        modalFilter.value = false
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            let ini = element.namapasien.split(' ')
            let init = element.namapasien.substr(0, 2)
            if (ini.length > 1) {
                init = init + ini[1].substr(0, 1)
            }
            element.initials = init
            element.ruanganasal = (element.asal_ruangan.length > 14) ? element.ruanganasal = element.asal_ruangan.substring(0, 14) + '...' : element.ruanganasal = element.asal_ruangan
            element.tglRegistrasi = moment(element.tglregistrasi).format('YYYY-MM-DD')
        });
        isData.value = response.length
        dataOrder.value = response
        isLoading.value = false

        let chacePeriode = {
            0: tglAwal,
            1: tglAkhir,
        }

        H.cacheHelper().set('cathlab', chacePeriode);
    }).catch((err) => {
        modalFilter.value = false
    })
}

const fetchDetail = async () => {
    dokterPraktek.value = []
    dataStokObat.value = []
    const response = await useApi().get(
        '/dashboard/cathlab/get-detail-rad'
    )
    dokterPraktek.value = response.dokter
    dataStokObat.value = response.produk
}

const fetchPenunjang = async () => {
    // let ruanganid = ''
    // if (item.value.filterRuangan) {
    //     ruanganid = item.value.filterRuangan
    // }
    let qnama = ''
    let qnocm = ''
    let qsearch = ''
    let qnoregistrasi = ''
    let tglAwal = '&tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')

    if (item.value.qnama) qnama = `&qnama=${item.value.qnama}`
    if (item.value.qnocm) qnocm = `&qnocm=${item.value.qnocm}`
    if (item.value.qsearch) qsearch = `&qsearch=${item.value.qsearch}`
    if (item.value.qnoregistrasi) qnoregistrasi = `&qnoregistrasi=${item.value.qnoregistrasi}`

    isLoading.value = true
    dataPenunjang.value = []
    const response = await useApi().get('/dashboard/cathlab/get-penunjang-rad?' + tglAwal + tglAkhir + qnama + qnocm + qnoregistrasi + qsearch)

    isLoading.value = false
    dataPenunjang.value = response.data

}

const getListPelayanan = async (data: any) => {
    const response = await useApi().get(`/dashboard/cathlab/get-pelayanan?idkelas=${data.idkelas}&idjenispelayanan=${data.idJenisPelayanan}`)
    d_Produk.value = response.map((e: any) => {
        // return { label: `${e.namaproduk} | ${e.hargasatuan}`, value: `${e.id},${e.hargasatuan},${e.namaproduk}`, default: e }
        item.value.namaproduk = e.namaproduk
        return { label: `${e.namaproduk} | ${e.hargasatuan},`, value: `${e.objectprodukfk}`, default: e }
    })
}

const getListDokter = async () => {
    const response = await useApi().get(`/dashboard/cathlab/get-dokter`)
    d_Ruangan.value = response.ruangan.map((e: any) => { return { label: e.namaruangan, value: e.id, default: e } })

    d_JenisKelamin.value = response.jeniskelamin.map((e: any) => { return { label: e.jeniskelamin, value: e.id } })
    d_GolonganDarah.value = response.golongandarah.map((e: any) => { return { label: e.golongandarah, value: e.id, default: e.id } })
    d_Dokter.value = response.data.map((e: any) => {
        return { label: `${e.namalengkap}`, value: `${e.id}`, default: e }
    })
    item.value.filterRuangan = d_Ruangan.value[0].value
}

const orderVerify = async (e: any) => {
    dropdownList()
    modalDetailOrder.value = true
    let data = {
        'idkelas': e.objectkelasfk,
        'idJenisPelayanan': e.jenispelayananfk,
        'idRekanan': e.objectrekananfk
    }
    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.pdNorec = e.pd_norec
    item.value.soNorec = e.so_norec
    item.value.noregistrasi = e.noregistrasi
    item.value.objectpegawaiorderfk = e.objectpegawaiorderfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tglregistrasi = e.tglregistrasi
    item.value.kelas = e.objectkelasfk
    getListPelayanan(data)
    const getHargaLayanan = await useApi().get(`/dashboard/cathlab/get-order-layanan?strukorderfk=${e.so_norec}`)//&objectkelasfk=${e.objectkelasfk}
    // const response = await useApi().get(`/dashboard/cathlab?statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    getHargaLayanan.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailDiagnosa.value = e.detailDiagnosa.map((r: any) => r.kddiagnosa).join(', ')

    detailOrderLayanan.value = getHargaLayanan
}

const getDetailVerify = async (e: any) => {

    item.value.idJenisPelayanan = e.jenispelayananfk
    item.value.namapasien = e.namapasien
    item.value.inisial = e.initials
    item.value.ruangantujuan = e.ruangantujuan
    item.value.noorder = e.noorder
    item.value.no_rm = e.pas_nocm
    item.value.jeniskelamin = e.jeniskelamin
    item.value.kelompokpasien = e.kelompokpasien
    item.value.idRuanganTujuan = e.objectruangantujuanfk
    item.value.dokterorder = e.nama_pegawai
    item.value.tglregistrasi = e.tglregistrasi

    modalDetailOrderVerify.value = true
    const response = await useApi().get(`/dashboard/cathlab/get-order-verify?norec_so=${e.so_norec}`)
    const diagnosa = await useApi().get(`/dashboard/cathlab?tglAwal=${e.tglregistrasi}&tglAkhir=${e.tglregistrasi}&statusorder=${statusOrder.value}&noorder=${e.noorder}`)
    detailDiagnosa.value = e.detailDiagnosa.map((r: any) => r.kddiagnosa).join(', ')
    response.forEach((element: any, i: any) => {
        element.no = i + 1
    });
    detailOrderVerify.value = response
}

const save = async () => {

    if (detailOrderLayanan.value.length < 1) {
        useToaster().error('Order Tidak Boleh kosong')
        return
    }
    if (!item.value.dokterverify) {
        useToaster().error('Dokter Verify Tidak Boleh Kosong')
        return
    }
    if (listItem.value.length <= 0) {
        useToaster().error('Pilih Setidaknya 1 Petugas')
        return
    }
    let datas = []
    let datass = []
    let petugas = []
    for (let i = 0; i < listItem.value.length; i++) {
        const element = listItem.value[i];
        var jenispet = ''
        for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
            const element2: any = d_JenisPelaksana.value[z];
            if (element.jenisPelaksana == element2.value) {
                jenispet = element2.label
                break
            }
        }
        var listPegawai: any = []
        for (let k = 0; k < element.pegawai.length; k++) {
            const elementxx = element.pegawai[k];
            var namaPegawai = ''
            for (let zz = 0; zz < d_Pegawai.value.length; zz++) {
                const elementPeg: any = d_Pegawai.value[zz];
                if (elementxx == elementPeg.value) {
                    namaPegawai = elementPeg.label
                    break
                }
            }
            listPegawai.push({
                'id': elementxx,
                'namalengkap': namaPegawai
            })
        }
        petugas.push({
            "objectjenispetugaspefk": element.jenisPelaksana,
            "jenispetugaspe": jenispet,
            "listpegawai": listPegawai
        })
    }
    let parameter = {
        'idruangtujuan': item.value.idRuanganTujuan,
        'pd_norec': item.value.pdNorec,
        'objectpegawaiorderfk': item.value.objectpegawaiorderfk,
        'tglregistrasi': item.value.tglregistrasi,
        'noregistrasi': item.value.noregistrasi,
        'so_norec': item.value.soNorec,
        'catatan': item.value.catatan,
        'dokterverify': item.value.dokterverify.value,
        'catatan_klinis': item.value.catatankliniks,

    }
    isLoadingSave.value = true
    for (var i = detailOrderLayanan.value.length - 1; i >= 0; i--) {
        let response = detailOrderLayanan.value[i]
        let komponenHarga = detailOrderLayanan.value[i].komponenharga
        var objSave = {
            'idProduk': response.prid,
            'hargaLayanan': response.hargasatuan,
            'tglpelayanan': response.tglpelayanan,
            'jumlah': response.qtyproduk,
            'komponenharga': komponenHarga,
            'pelayananpetugas': petugas
        }
        datas.push(objSave)
    }
    await useApi().post('/dashboard/cathlab/save-order-pelayanan', { 'data': datas, 'parameter': parameter }).then((response: any) => {
        modalDetailOrder.value = false
        isLoadingSave.value = false
        fetchDataOrder(0)
    }).catch((error) => {
        useToaster().error('Something Went Wrong')
    })

}

function dropdownList() {
    useApi().get(`tindakan/list-jenis-petugas`).then((response: any) => {
        d_JenisPelaksana.value = response.jenispetugaspelaksana.map((e: any) => { return { label: e.jenispetugaspe, value: e.id, default: e } })
        item.nilaiCito = response.cito != null ? parseFloat(response.cito) : 1
        // console.log(response.jenispetugaspelaksana)
        for (let z = 0; z < listItem.value.length; z++) {
            const elementz = listItem.value[z];
            for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
                const element = response.jenispetugaspelaksana[x];
                if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
                    elementz.jenisPelaksana = element.id
                    changeJenis(elementz)
                    break
                }
            }
        }
    })
}

const addNewItem = () => {
    listItem.value.push({
        jenisPelaksana: null,
        pegawai: [],
    });
}
const removeItem = (index: any) => {
    listItem.value.splice(index, 1)
}

const changeJenis = async (e: any) => {

    // if (!e.jenisPelaksana) {
    //     H.alert('warning', 'Jenis pelaksana wajib dipilih')
    //     return
    // }
    isLoadChange.value = true
    await useApi().get('/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e.jenisPelaksana).then((response: any) => {
        if (response != null) {

            e.d_Pegawai = response.map((e: any) => {
                return {
                    label: e.namalengkap, value: e.id
                }
            })
        } else {
            e.d_Pegawai = []
        }
    })
    isLoadChange.value = false
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

const getHarga = async (idProduk: any) => {
    const response = await useApi().get(`/dashboard/cathlab/get-pelayanan?idkelas=${item.value.kelas}&idjenispelayanan=${item.value.idJenisPelayanan}&idProduk=${idProduk}`)
    item.value.hargaLayanan = response[0].hargasatuan
    item.value.namaproduk = response[0].namaproduk
}

const clear = () => {
    item.value.id = ''
    item.value.no = ''
    item.value.layanan = ''
    item.value.hargaLayanan = ''
    item.value.qtyproduk = ''
    item.value.jumlah = ''
}

const add = async (e: any) => {

    let datas: any = []
    if (!item.value.hargaLayanan) {
        useToaster().error('Harga Tidak Boleh Kosong')
        return
    }
    if (!item.value.jumlah) {
        useToaster().error('Jumlah Tidak Boleh Kosong')
        return
    }

    let jumlah = e.jumlah
    let harga = e.hargaLayanan
    let ruangan = e.ruangantujuan
    let namaproduk = e.namaproduk
    let layanan = e.layanan
    let no

    (detailOrderLayanan.value.length == 0) ? no = 1 : no = detailOrderLayanan.value.length + 1
    await useApi().get('/dashboard/cathlab/get-komponen-harga?idProduk=' + e.layanan + '&idKelas=' + e.kelas + '&idJenLayan=' + e.idJenisPelayanan)
        .then((response) => {
            response.forEach((items: any) => {
                datas.push(items)
            })
        })
    let data = {
        no: no,
        prid: layanan,
        tglpelayanan: moment(new Date()).format('YYYY-MM-DD HH:MM:SS'),
        namaproduk: namaproduk,
        komponenharga: datas,
        hargasatuan: harga,
        qtyproduk: jumlah,
        ruangantujuan: ruangan,
    }
    detailOrderLayanan.value.push(data)
}

const update = (e: any) => {
    // console.log(e)
    let data: any = {}
    detailOrderLayanan.value.forEach((items: any, i: any) => {
        if (e.no == items.no) {
            data.no = items.no
            data.qtyproduk = e.jumlah
            data.hargasatuan = e.hargaLayanan
            data.komponenharga = items.komponenharga
            data.prid = e.layanan
            data.tglpelayanan = moment(new Date()).format('YYYY-MM-DD hh:mm:ss')
            data.namaproduk = e.namaproduk
            data.ruangantujuan = e.ruangantujuan
            detailOrderLayanan.value[i] = data
        }
    })
    clear()

}

const edit = (e: any) => {
    item.value.no = e.no
    item.value.layanan = e.prid
    item.value.hargaLayanan = e.hargasatuan
    item.value.jumlah = e.qtyproduk
}

const chartLayananByRuangan = async () => {
    let tglAwal = 'tglAwal=' + H.formatDate(item.value.periode.start, 'YYYY-MM-DD')
    let tglAkhir = '&tglAkhir=' + H.formatDate(item.value.periode.end, 'YYYY-MM-DD')
    await useApi().get(`/dashboard/cathlab/chart-layanan-ruangan?${tglAwal}${tglAkhir}`).then((res: any) => {
        item.value.chartLength = res.chartLO.count.length
        // item.value = res
        // console.log(res.chartLO.categories)
        chartLO.value = {
            series: [
                {
                    name: 'pasien',
                    data: res.chartLO.count
                }
            ],
            chart: {
                height: 220,
                type: 'bar',
                toolbar: {
                    show: false,
                },
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        // fontSize: '8px',
                        position: 'top', // top, center, bottom
                    },
                },
            },
            dataLabels: {
                enabled: true,
                // formatter: formatters.asPercent,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ['#304758'],
                },
            },
            xaxis: {
                categories: res.chartLO.categories,
                position: 'top',
                axisBorder: {
                    show: true,
                },
                axisTicks: {
                    show: false,
                },
                crosshairs: {
                    fill: {
                        type: 'gradient',
                        gradient: {
                            colorFrom: '#D8E3F0',
                            colorTo: '#BED1E6',
                            stops: [0, 100],
                            opacityFrom: 0.4,
                            opacityTo: 0.5,
                        },
                    },
                },
                // tooltip: {
                //     enabled: true,
                // },
            },
            yaxis: {
                axisBorder: {
                    show: true,
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                    // formatter: formatters.asPercent,
                },
            },
            colors: [themeColors.green, themeColors.secondary, themeColors.orange],
            title: {
                text: 'Pelayanan Berdasarkan Ruangan',
                align: 'left',
            },
        }
    })

}

const showModalFilter = () => {
    modalFilter.value = true
}

const changePeriode = () => {
    if (item.value.periode.start != item.value.periode.end) {
        styleFilter.value = 'left:37rem;'
    }
    fetchDataOrder(0)
    fetchPenunjang()
    chartLayananByRuangan()
}

const PengkajianMedis = (e: any) => {
    H.cacheHelper().set('xxx_cache_menu', undefined)
    router.push({
        name: 'module-emr-profile-pasien',
        query: {
            nocmfk: e.nocmfk,
            norec_pd: e.norec_pd,
        }
    })
}

const transaksiPelayanan = (e: any) => {
    router.push({
        name: 'module-cathlab-transaksi-cathlab',
        query: {
            nocmfk: e.nocmfk,
            norec_pasien_daftar: e.norec_pd,
            norec_apd: e.norec_apd
        },

    })
}

const UpdateJenisKelamin = (e: any) => {
    item.value.norm = e.nocm
    item.value.jeniskelamin = e.objectjeniskelaminfk
    modalJenisKelamin.value = true
}

const saveJenisKelamin = async (e: any) => {
    let json = {
        'nocm': item.value.norm,
        'objectjeniskelaminfk': item.value.jeniskelamin,
    }
    useApi().post(
        `/dashboard/save-jenkel`, json).then((response: any) => {
            modalJenisKelamin.value = false
            fetchPenunjang()
        })
}

const UpdateGolonganDarah = (e: any) => {
    item.value.nocm = e.nocm
    item.value.golongandarah = e.objectgolongandarahfk
    modalGolonganDarah.value = true
}

const saveGolonganDarah = async (e: any) => {
    let json = {
        'nocm': item.value.nocm,
        'objectgolongandarahfk': item.value.golongandarah,
    }
    useApi().post(
        `/dashboard/save-goldar`, json).then((response: any) => {
            modalGolonganDarah.value = false
            fetchPenunjang()
        })
}

const initCache = () => {
    let chacePeriode = H.cacheHelper().get('cathlab');
    if (chacePeriode != undefined) {
        item.value.periode.start = new Date(chacePeriode[0]);
        item.value.periode.end = new Date(chacePeriode[1]);
    }
    if (item.value.periode.start != item.value.periode.end) {
        styleFilter.value = 'left:37rem;'
    }
}
watch(
    () => [
        order.value
    ], () => {
        changeSwitch(order.value)
    }
)
initCache()
fetchDataOrder(0)
getListDokter()
chartLayananByRuangan()
fetchPenunjang()
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

.search-menu {
    height: 56px;
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

    .search-button {
        background-color: var(--primary);
        min-width: 100px;
        height: 56px;
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

.hr-dashboard .block-header {
    background: #6c757d;
    box-shadow: var(--dark-box-shadow);
}

.hr-dashboard .block-header .center {
    border-right: 1px solid white;
}
</style>
