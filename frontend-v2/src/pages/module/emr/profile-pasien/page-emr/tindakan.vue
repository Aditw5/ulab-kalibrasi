<template>
    <section>
        <ConfirmDialog group="positionDialog"></ConfirmDialog>
        <div class="form-layout is-stacked">

            <div class="form-outer" style="margin-top:15px">
                <div v-if="!props.pasien && isStuck" :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Tindakan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="props.pasien && isStuck" :class="[isStuck && 'is-stuck']" style="margin-top:50px; padding: 0px 0px !important;" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Tindakan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-if="!isStuck" class="form-header stuck-header">
                    <div class="form-header-inner">
                        <div class="left">
                            <h3>Tindakan</h3>
                        </div>
                        <div class="right">
                            <div class="buttons">
                                <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                    Kembali
                                </VButton>
                                <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                    :loading="isLoading" @click="simpan()"> Simpan
                                </VButton>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-body p-2">
                    <div class="business-dashboard hr-dashboard" v-if="!props.pasien">
                        <div class="columns is-multiline">
                            <div class="column is-12" v-if="isLoadingPasien">
                                <PlaceloadHeader class="m-3" />
                            </div>
                            <div class="column is-12" v-if="!isLoadingPasien">
                                <HeadPasien :pasien="pasien" class="m-3" />
                            </div>
                        </div>
                    </div>
                    <div class="columns is-multiline">
                        <div class="column is-12 ">
                            <div class="form-section pl-0 pl-3 pr-3 pb-0 mb-0">
                                <div class="tabs-wrapper" :class="['tab-naver']">
                                    <div class="tabs-inner">
                                        <div class="tabs is-boxed">
                                            <ul>
                                                <li v-for="(tab, key) in tabs" :key="key"
                                                    :class="[activeValue === tab.value && 'is-active']">
                                                    <slot name="tab-link" :active-value="activeValue" :tab="tab"
                                                        :index="key" :toggle="toggle">
                                                        <a tabindex="0" @keydown.space.prevent="toggle(tab.value)"
                                                            @click="toggle(tab.value)">
                                                            <VIcon v-if="tab.icon" :icon="tab.icon" />
                                                            <span>
                                                                <slot name="tab-link-label" :active-value="activeValue"
                                                                    :tab="tab" :index="key">
                                                                    {{ tab.label }}
                                                                </slot>
                                                            </span>
                                                        </a>
                                                    </slot>
                                                </li>
                                                <li v-if="sliderClass" class="tab-naver"></li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="tab-content is-active">
                                        <Transition :name="'fade-fast'" mode="out-in">
                                            <slot name="tab" :active-value="activeValue"></slot>
                                        </Transition>
                                    </div>
                                </div>
                                <div class="columns is-multiline">
                                    <div class="column is-12 ">
                                        <div class="columns is-multiline p-1" v-if="activeValue == 1">
                                            <div class="column is-3">
                                                <VButton type="button" color="info" raised rounded
                                                    icon="feather:plus-circle" class=" mr-3 mt-0 mb-0" @click="showModal()">
                                                    Tambah
                                                </VButton>
                                            </div>

                                            <div class="column is-9">
                                                <VControl class="is-pulled-right">
                                                    <VSwitchBlock v-model="isPaket" label="Paket" color="danger"
                                                        class="is-pulled-right" />
                                                </VControl>
                                            </div>
                                            <div class="column is-12">
                                                <div class="timeline-wrapper" v-if="dataSourceTindakan.length > 0">
                                                    <div class="timeline-header"></div>
                                                    <div class="timeline-wrapper-inner pt-0">
                                                        <div class="timeline-container">
                                                            <div class="timeline-item is-unread "
                                                                v-for="(items, index)  in dataSourceTindakan"
                                                                :key="items.norec">

                                                                <div class="date">
                                                                    <span>{{ H.formatDateIndo(items.tglpelayanan) }}</span>
                                                                </div>
                                                                <div :class="'dot is-' + listColor[index + 1]"></div>
                                                                <div class="collapse-icon is-clickable"
                                                                    @click="isDetail[index] = true" v-if="!isDetail[index]">
                                                                    <VIcon icon="feather:chevron-down" />
                                                                </div>
                                                                <div class="collapse-icon  is-clickable mr-1" open
                                                                    @click="isDetail[index] = false" v-else>
                                                                    <VIcon icon="feather:chevron-up" />
                                                                </div>
                                                                <div class="content-wrap is-grey">
                                                                    <div class="content-box is-fullwidth">
                                                                        <div class="status"></div>
                                                                        <VIconBox size="medium" :color="'kuning'" rounded>
                                                                            <i class="fas fa-file-medical-alt"
                                                                                aria-hidden="true"></i>
                                                                        </VIconBox>
                                                                        <div class="box-text" style="width:70%">
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
                                                                                        <td class="text-value">{{
                                                                                            items.jumlah
                                                                                        }} </td>
                                                                                    </tr>
                                                                                    <tr v-if="items.iscito">
                                                                                        <td>Cito</td>
                                                                                        <td>:</td>
                                                                                        <td class="text-value">{{
                                                                                            item.nilaiCito
                                                                                        }}%</td>
                                                                                    </tr>
                                                                                </table>
                                                                            </div>
                                                                        </div>
                                                                        <div class="box-end" style="width:40%">
                                                                            <div class="columns is-multiline">
                                                                                <div class="column is-12 mt-3">
                                                                                    <div
                                                                                        class="status is-pulled-right mt-2 ml-2">
                                                                                    </div>
                                                                                    <VTag v-if="items.iscito" label="Cito"
                                                                                        :color="'warning'" rounded
                                                                                        class="is-pulled-right" />
                                                                                    <VTag v-if="items.isparamedis"
                                                                                        label="Dilakukan paramedis"
                                                                                        :color="'warning'" rounded
                                                                                        class="is-pulled-right mr-1" />
                                                                                </div>
                                                                                <div class="column is-6 mt-3">
                                                                                    <b> {{ H.formatRp(items.subtotal, 'Rp.')
                                                                                    }}</b>
                                                                                </div>
                                                                                <div class="column is-6">
                                                                                    <VIconButton icon="feather:edit"
                                                                                        @click="editItems(items)"
                                                                                        color="warning" raised circle
                                                                                        class="mr-2">
                                                                                    </VIconButton>
                                                                                    <VIconButton icon="feather:trash"
                                                                                        @click="hapusItems(items)"
                                                                                        color="danger" raised circle>
                                                                                    </VIconButton>

                                                                                </div>
                                                                            </div>

                                                                        </div>

                                                                    </div>
                                                                    <VCard custom="card-grey" class="mt-1"
                                                                        v-if="isDetail[index]">
                                                                        <div class="columns is-multiline"
                                                                            v-for="(itemsDet, index2)  in items.pelayananpetugas"
                                                                            :key="index2">
                                                                            <div class="column is-3">
                                                                                <VField>
                                                                                    <VLabelText>Pelaksana
                                                                                    </VLabelText>
                                                                                    <VLabel class="value">{{
                                                                                        itemsDet.jenispetugaspe }}
                                                                                    </VLabel>
                                                                                </VField>
                                                                            </div>
                                                                            <div class="column is-9">
                                                                                <VField>
                                                                                    <VLabelText>Pegawai
                                                                                    </VLabelText>
                                                                                    <VLabel class="value"
                                                                                        style="width:500px !important">
                                                                                        {{
                                                                                            multiSelectArrayToString(itemsDet.listpegawai)
                                                                                        }}
                                                                                    </VLabel>
                                                                                </VField>
                                                                            </div>
                                                                        </div>
                                                                    </VCard>

                                                                </div>
                                                                <!-- <VCard custom="card-green" v-if="isDetail[index]">
                                            <div class="columns is-multiline"
                                                v-for="(itemsDet, index2)  in items.pelayananpetugas" :key="index2">
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabelText>Jenis Petugas</VLabelText>
                                                        <VLabel>{{ itemsDet.jenispetugaspe }} </VLabel>
                                                    </VField>
                                                </div>
                                                <div class="column is-12">
                                                    <VField>
                                                        <VLabelText>Pegawai</VLabelText>
                                                        <VLabel>{{ multiSelectArrayToString(itemsDet.listpegawai) }}
                                                        </VLabel>
                                                    </VField>
                                                </div>
                                            </div>
                                        </VCard> -->
                                                            </div>
                                                        </div>
                                                        <div class="load-more-wrap has-text-centered p-1 mb-3">
                                                            <div class="columns is-multiline">
                                                                <div class="column is-4 is-offset-8">
                                                                    <VCard>
                                                                        <div class="columns is-multiline">
                                                                            <div class="column is-3 mt-1">
                                                                                <VField>
                                                                                    <VLabel class="fs-total-label">TOTAL
                                                                                    </VLabel>
                                                                                </VField>
                                                                            </div>
                                                                            <div class="column is-9">
                                                                                <VField>
                                                                                    <VLabel class="fs-total">{{
                                                                                        H.formatRp(item.totalHarga,
                                                                                            'Rp.')
                                                                                    }} </VLabel>
                                                                                </VField>
                                                                            </div>
                                                                        </div>
                                                                    </VCard>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <VCard radius="rounded" class="mt-2" v-if="dataSourceTindakan.length === 0">
                                                    <VPlaceholderPage :title="H.assets().notFound"
                                                        :subtitle="H.assets().notFoundSubtitle" larger>
                                                        <template #image>
                                                            <img class="light-image" :src="H.assets().iconNotFound_rev"
                                                                alt="" />
                                                            <img class="dark-image"
                                                                src="/@src/assets/illustrations/placeholders/search-4-dark.svg"
                                                                alt="" />
                                                        </template>
                                                    </VPlaceholderPage>
                                                </VCard>


                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12 ">
                                        <div class="columns is-multiline p-1" v-if="activeValue == 2">
                                            <div class="column is-6">
                                                <VField>
                                                    <VControl icon="feather:search">
                                                        <input v-model="filters" type="text" class="input is-rounded"
                                                            placeholder="Filter " />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-6">
                                                <h3 class="title is-5 mb-2 mr-1 is-pulled-right">TOTAL #{{
                                                    dataSourcefiltered.length }} - (Rp. {{ H.formatRp(item.TOTAL_BILL, '')
    }}) </h3>

                                            </div>
                                            <Divider type="dashed" />
                                            <div class="column is-12">

                                                <table class="tb-custom mt-3">
                                                    <thead>
                                                        <tr>
                                                            <!-- <th width="10%" class="text-center">
                                                                <VControl raw subcontrol style="margin-top:-10px">
                                                                    <VCheckbox v-model="item.checkAll" label="#" color="info"
                                                                        @change="checkedAll(item.checkAll)"
                                                                        :value="item.checkAll" />
                                                                </VControl>
                                                            </th> -->
                                                            <th width="30%">URAIAN</th>
                                                            <th>HARGA SATUAN</th>
                                                            <th>JUMLAH</th>
                                                            <th>SUBTOTAL</th>
                                                            <th>OPSI</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody v-if="isLoadingBill">
                                                        <tr>
                                                            <td colspan="5">
                                                                <div class="list-view list-view-v1 is-fullwidth">
                                                                    <div class="list-view-inner">
                                                                        <div v-for="key in 6" :key="key"
                                                                            class="list-view-item mt-2">
                                                                            <VPlaceloadWrap>
                                                                                <VPlaceloadAvatar size="medium" />
                                                                                <VPlaceloadText last-line-width="60%"
                                                                                    class="mx-2" />
                                                                                <VPlaceload class="mx-2" disabled />
                                                                                <VPlaceload
                                                                                    class="mx-2 h-hidden-tablet-p" />
                                                                                <VPlaceload
                                                                                    class="mx-2 h-hidden-tablet-p" />
                                                                                <VPlaceload class="mx-2" />
                                                                            </VPlaceloadWrap>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                    <div
                                                        style="max-height:500px;min-height: 300px; overflow-y: scroll;display: block;">
                                                        <tbody v-if="!isLoadingBill"
                                                            v-for="(items, index)  in dataSourcefiltered" :key="index">
                                                            <tr>
                                                                <td colspan="5" class="koneng">
                                                                    {{ H.formatDateOnlyLong(items.tglpelayanan_group) }}
                                                                </td>
                                                            </tr>
                                                            <tr v-for="(itemsDet, index2)  in items.details" :key="index2">
                                                                <!-- <td width="5%">
                                                                    <VControl raw subcontrol>
                                                                        <VCheckbox v-model="modelCheck[itemsDet.norec]"
                                                                            :value="itemsDet.itemsDet" color="info" square
                                                                            :class="modelCheck[items.norec] == true ? 'is-solid' : ''"
                                                                            @change="checkedItems()" />
                                                                    </VControl>
                                                                </td> -->
                                                                <td width="30%">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">{{
                                                                                itemsDet.namaruangan
                                                                            }}</div>
                                                                            <div class="title-layan">{{ itemsDet.namaproduk
                                                                            }} - {{itemsDet.dokterpemeriksa ? itemsDet.dokterpemeriksa : 'Dokter belum di input'}}</div>
                                                                            <div>
                                                                                <VTag
                                                                                    :color="itemsDet.strukresepfk != null ? 'danger' : 'info'"
                                                                                    :label="itemsDet.tglpelayanan" />
                                                                            </div>
                                                                            <div class="title-kelas">{{ itemsDet.namakelas
                                                                            }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">
                                                                    <div class="columns is-multiline">
                                                                        <div class="column is-12">
                                                                            <div class="title-ruangan">Jasa : {{
                                                                                H.formatRp(itemsDet.jasa, 'Rp. ') }}</div>
                                                                            <div class="title-layan">{{
                                                                                H.formatRp(itemsDet.hargasatuan, 'Rp. ') }}
                                                                            </div>

                                                                            <div class="title-kelas">Diskon : {{
                                                                                H.formatRp(itemsDet.hargadiscount, 'Rp. ')
                                                                            }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td class="center">{{ itemsDet.jumlah }}</td>
                                                                <td class="center">{{ H.formatRp(itemsDet.total, 'Rp. ') }}
                                                                </td>
                                                                <td class="center">

                                                                    <VIconButton color="danger" class="mr-2" light raised
                                                                        circle icon="feather:trash"
                                                                        @click="hapusTindakan(itemsDet)" />
                                                                    <VDropdown spaced dots right>
                                                                        <template #button="{ open, toggle }">
                                                                            <VIconButton icon="feather:more-vertical"
                                                                                class="is-trigger" @mouseenter="open"
                                                                                @focusin="open" light raised circle
                                                                                @click="toggle" color="info"
                                                                                v-bind:disabled="PASIEN_AKTIF == false ? true : false">
                                                                                Aksi
                                                                            </VIconButton>
                                                                        </template>
                                                                        <template #content>
                                                                            <a role="menuitem"
                                                                                class="dropdown-item is-media"
                                                                                @click="detailPetugas(itemsDet)">
                                                                                <div class="icon">
                                                                                    <i class="iconify lnir lnir-users"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Detail Pelaksana</span>
                                                                                    <span>View petugas tindakan </span>
                                                                                </div>
                                                                            </a>

                                                                            <a role="menuitem"
                                                                                class="dropdown-item is-media"
                                                                                @click="detailKomponen(itemsDet)">
                                                                                <div class="icon">
                                                                                    <i class="iconify fas fa-money-check"
                                                                                        aria-hidden="true"></i>
                                                                                </div>
                                                                                <div class="meta">
                                                                                    <span>Komponen Harga</span>
                                                                                    <span>view detail harga tindakan </span>
                                                                                </div>
                                                                            </a>
                                                                        </template>
                                                                    </VDropdown>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                        <div class="search-results-wrapper"
                                                            v-if="dataSourcefiltered.length == 0 && isLoadingBill == false">
                                                            ti <div class="search-results-body ">
                                                                <div class="page-placeholder">
                                                                    <div class="placeholder-content">
                                                                        <img class="light-image" style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <img class="dark-image" style=" max-width: 340px;"
                                                                            :src="H.assets().iconNotFound_rev" alt="" />
                                                                        <h3>{{ H.assets().notFound }}</h3>
                                                                        <p class="is-larger">
                                                                            {{ H.assets().notFoundSubtitle }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
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
            <Dialog v-model:visible="modalInput" modal header="Tambah Tindakan" :style="{ width: '70vw' }">

                <div class="columns is-multiline">
                    <div class="column is-12">

                        <VButton type="button" rounded color="primary" raised icon="feather:save" class="is-pulled-right"
                            :loading="isLoading" @click="simpan()"> Simpan
                        </VButton>
                        <VTag :color="'warning'" :label="dataSourceTindakan.length + ' tindakan sudah ditambah'"
                            v-if="dataSourceTindakan.length" class="is-pulled-right mr-2 mt-2" />
                    </div>
                    <div class="column is-12">
                        <VCard>
                            <div class="columns is-multiline p-1">
                                <!-- <div class="column is-12 p-0">
                                        <VIconButton type="button" raised circle color="danger" icon="feather:trash"
                                            class="is-pulled-right ml-2" @click="removeItem(index)">
                                        </VIconButton>

                                    </div> -->
                                <div class="column is-2">
                                    <VField label="Tanggal" class="is-rounded-select">
                                      <VControl class="prime-auto ">
                                          <div class="is-rounded is-rounded-select">
                                              <Calendar v-model="item.tglpelayanan"
                                                  selectionMode="single" :manualInput="true"
                                                    class="w-100 is-rounded" showTime :showIcon ="true"
                                                    hourFormat="24" :date-format="'yy-mm-dd'" />
                                          </div>
                                      </VControl>
                                    </VField>
                                </div>
                                <div class="column is-4">
                                    <VField label="Pelayanan" class="is-rounded-select  is-autocomplete-select"
                                        v-slot="{ id }">
                                        <VControl icon="feather:list" fullwidth>
                                            <Multiselect mode="single" v-model="item.produk" :options="d_Produk"
                                                placeholder="Pilih data" :searchable="true" :attrs="{ id }"
                                                autocomplete="off" @select="changeTindakan(item.produk)" />
                                            <!-- <Multiselect mode="single" v-model="item.produk"
                                                                placeholder="Pilih data" :searchable="true"
                                                                :filter-results="false" :min-chars="0"
                                                                :resolve-on-load="false" :delay="0" :options="async function (query: any) {
                                                                    return await fetchTindakan(query)
                                                                }" autocomplete="off" /> -->

                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <VField label="Harga">
                                        <VLabel class="mt-4">{{
                                            H.formatRp(item.hargasatuan,
                                                'Rp.')
                                        }}</VLabel>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <VField label="Jumlah">
                                        <VControl icon="lnir lnir-repeat-one">
                                            <VInput type="text" v-model="item.jumlah" placeholder="Jumlah"
                                                class="is-rounded" />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-2">
                                    <VField class="mt-5">
                                        <VControl class="is-flex">
                                            <VLabel raw class="remember-toggle">
                                                <VInput raw type="checkbox" v-model="item.iscito"
                                                    :checked="item.iscito == 'true' || item.iscito == true ? true : false"
                                                    @change="handleChange($event.target.checked)" />

                                                <span class="toggler">
                                                    <span class="active">
                                                        <i aria-hidden="true" class="iconify" data-icon="feather:check"></i>
                                                    </span>
                                                    <span class="inactive">
                                                        <i aria-hidden="true" class="iconify"
                                                            data-icon="feather:circle"></i>
                                                    </span>
                                                </span>
                                            </VLabel>
                                            <VLabel raw class="remember-me">Cito</VLabel>

                                        </VControl>
                                    </VField>
                                    <!-- <VField class="mt-4">
                                            <VControl>
                                                <VSwitchBlock v-model="item.iscito" label="Cito" color="danger" />
                                            </VControl>
                                        </VField> -->
                                </div>
                                <div class="column is-12" v-for="(item, index) in listItem" :key="index">
                                    <VCard class="is-grey">
                                        <div class="columns is-multiline p-1">
                                            <div class="column is-2">
                                                <VField label="Jenis Pelaksana"
                                                    class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                                                    <VControl icon="feather:plus-circle" fullwidth>
                                                        <Dropdown v-model="item.jenisPelaksana" :options="d_JenisPelaksana"
                                                            :optionLabel="'label'" :optionValue="'value'" class="is-rounded"
                                                            placeholder="Pilih data" style="width: 100%;" showClear
                                                            :filter="true" @change="changeJenis(item)" />
                                                        <!-- <Multiselect mode="single" v-model="item.jenisPelaksana"
                                                            :options="d_JenisPelaksana" placeholder="Pilih data"
                                                            :searchable="true" :attrs="{ id }" autocomplete="off"
                                                            @select="changeJenis(item)" track-by="value" /> -->

                                                    </VControl>
                                                </VField>
                                            </div>

                                            <div class="column is-6">
                                                <VField class="is-rounded-select is-autocomplete-select" v-slot="{ id }">
                                                    <VLabel>Pegawai</VLabel>
                                                    <VControl icon="feather:user" fullwidth :loading="isLoadChange"
                                                        class="prime-auto-select">

                                                        <MultiSelect v-model="item.pegawai" display="chip"
                                                            class="w-100 is-rounded" :options="item.d_Pegawai"
                                                            optionLabel="label" optionValue="value" filter
                                                            :selectAll="false" :showToggleAll="false"
                                                            placeholder="Pilih Data" :maxSelectedLabels="3" />
                                                        <!-- <Multiselect mode="tags" :create-tag="true" placeholder="Pilih data"
                                                            v-model="item.pegawai" :options="item.d_Pegawai"
                                                            :searchable="true" :attrs="{ id }" autocomplete="off"
                                                            appendTo="body" /> -->
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-1">
                                                <VField class="mt-4">
                                                    <VControl>
                                                        <VSwitchBlock v-model="item.isparamedis"
                                                            @change="changeSwitch(item.isparamedis)" label="Paramedis"
                                                            color="danger" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-1 mt-3">
                                                <VIconButton v-if="index > 0" outlined type="button" raised circle
                                                    class="is-pulled-right" icon="feather:trash" @click="removeItem(index)"
                                                    color="danger">
                                                </VIconButton>
                                            </div>
                                            <div class="column is-1 is-flex mt-3">
                                                <!-- <VIconButton type="button" class="mr-1" raised circle
                                                        icon="feather:plus" @click="addNewItem()" color="warning">
                                                    </VIconButton> -->

                                                <VButton type="button" rounded outlined color="info" raised
                                                    icon="feather:plus" @click="addNewItem()"> Tambah Pelaksana
                                                </VButton>
                                            </div>
                                        </div>
                                    </VCard>
                                </div>
                            </div>
                        </VCard>
                    </div>
                </div>

                <template #footer>
                    <VButton icon="feather:plus" @click="tambah()" outlined :loading="isLoading" color="danger" raised>
                        Tambah
                    </VButton>
                </template>
            </Dialog>
            <VModal :open="modalPetugas" title="Detail Petugas Tindakan" :noclose="false" size="medium" actions="right"
                @close="modalPetugas = false">
                <template #content>
                    <form class="modal-form custom-mod ">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline p-1">
                                        <div class="column is-6">
                                            <p class="block-text">Tanggal Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>

                                        </div>
                                        <div class="column is-6">
                                            <p class="block-text">Nama Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>

                                        </div>
                                        <div class="column is-12">
                                            <VCard class="is-grey">
                                                <div class="columns is-multiline p-1">
                                                    <div class="column is-12 mb--15 text-center"
                                                        v-if="dataSourcePetugas.length == 0">
                                                        <img class="light-image" style=" max-width: 180px;"
                                                            :src="H.assets().iconNotFoundCalendar" alt="" />
                                                        <h3>{{ H.assets().notFound }}</h3>
                                                    </div>
                                                    <div class="column is-12 mb--15" v-else
                                                        v-for="(item, index) in dataSourcePetugas" :key="index">
                                                        <div class="columns is-multiline p-0">
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/icons/files/dokter.svg'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.namalengkap }} </span>
                                                                        <span>
                                                                            <b>{{ item.jenispetugaspe }}</b>
                                                                        </span>
                                                                    </div>
                                                                    <div
                                                                        class="is-right is-dots is-spaced dropdown end-action">
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-check-circle  is-pulled-right"
                                                                            style="color:var(--success)"></i>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>
                                    </div>
                                </VCard>
                            </div>

                        </div>
                    </form>
                </template>

            </VModal>
            <VModal :open="modalKomponen" title="Komponen Harga" :noclose="false" size="medium" actions="right"
                @close="modalKomponen = false">
                <template #content>
                    <form class="modal-form custom-mod ">
                        <div class="columns is-multiline">
                            <div class="column is-12">
                                <VCard>
                                    <div class="columns is-multiline p-1">
                                        <div class="column is-6">
                                            <p class="block-text">Tgl Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.tglpelayanan }}</h4>
                                        </div>
                                        <div class="column is-6">
                                            <p class="block-text">Nama Pelayanan</p>
                                            <h4 class="block-heading">{{ dataSelect.namaproduk }}</h4>
                                        </div>
                                        <div class="column is-12">
                                            <VCard class="is-grey">
                                                <div class="columns is-multiline p-1">
                                                    <div class="column is-12 mb--15 text-center"
                                                        v-if="dataSourceKom.length == 0">
                                                        <img class="light-image" style=" max-width: 180px;"
                                                            :src="H.assets().iconNotFoundList" alt="" />
                                                        <h3>{{ H.assets().notFound }}</h3>
                                                    </div>
                                                    <div class="column is-12 mb--15" v-else
                                                        v-for="(item, index) in dataSourceKom" :key="index">
                                                        <div class="columns is-multiline p-0">
                                                            <div class="column is-12">
                                                                <div class="file-box-2">
                                                                    <img :src="'/images/simrs/bill-list.png'" alt="" />
                                                                    <div class="meta">
                                                                        <span>{{ item.komponenharga }} </span>
                                                                        <table style="width: 100%;">
                                                                            <tr>
                                                                                <th class="tb-th">Harga</th>
                                                                                <th class="tb-th">Jumlah</th>
                                                                                <th class="tb-th">Diskon</th>
                                                                            </tr>
                                                                            <tr>
                                                                                <td class="tb-th text-center"> {{
                                                                                    H.formatRp(item.hargasatuan, '') }}</td>
                                                                                <td class="tb-th text-center"> {{
                                                                                    item.jumlah }}</td>
                                                                                <td class="tb-th text-center"> {{
                                                                                    H.formatRp(item.hargadiscount, '') }}
                                                                                </td>
                                                                            </tr>
                                                                        </table>


                                                                    </div>
                                                                    <div
                                                                        class="is-right is-dots is-spaced dropdown end-action">
                                                                        <i aria-hidden="true"
                                                                            class="fas fa-check-circle  is-pulled-right"
                                                                            style="color:var(--success)"></i>
                                                                    </div>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                    <div class="column is-12 " v-if="dataSourceKom.length != 0">
                                                        <VField class="is-pulled-right">
                                                            <VLabel class="fs-total">{{
                                                                H.formatRp(item.totalKomponen,
                                                                    'Rp.')
                                                            }} </VLabel>
                                                        </VField>
                                                    </div>
                                                </div>
                                            </VCard>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <h4 class="block-heading">Nama Komponen </h4>
                                            <p class="block-text"> {{ item.namaKomponen }}
                                            </p>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <h4 class="block-heading">Harga </h4>
                                            <p class="block-text">{{
                                                H.formatRp(item.hargasatuankom,
                                                    'Rp.')
                                            }}
                                            </p>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <VField>
                                                <VLabel style="color: black !important ;font-size: 100%;">Persen Diskon
                                                </VLabel>
                                                <VControl icon="fas fa-calculator">

                                                    <VInput type="number" v-model="item.persenDiskon" placeholder="Diskon"
                                                        class="is-rounded" />
                                                </VControl>
                                            </VField>
                                        </div>
                                        <div class="column is-6" v-if="item.norecPPD">
                                            <h4 class="block-heading">Total Diskon</h4>
                                            <p class="block-text">{{
                                                H.formatRp(item.diskonKomponen,
                                                    'Rp.')
                                            }}
                                            </p>
                                        </div>
                                    </div>
                                </VCard>
                            </div>

                        </div>
                    </form>
                </template>

            </VModal>
            <Dialog v-model:visible="modalPaket" modal header="Paket" :style="{ width: '60vw' }">
                <div class="columns is-multiline">

                    <div class="column is-3">
                        <VField label="Tanggal">
                            <VDatePicker v-model="item2.tglpelayanan" mode="dateTime" style="width: 100%;">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" class="is-rounded"
                                                v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>
                    <div class="column is-3">
                        <VField label="Jenis Pelaksana" class="is-rounded-select  is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:plus-circle" fullwidth>
                                <Multiselect mode="single" v-model="item2.jenisPelaksana" :options="d_JenisPelaksana"
                                    placeholder="Pilih data" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    @select="changeJenis2(item2)" track-by="value" />

                            </VControl>
                        </VField>
                    </div>
                    <div class="column is-6">
                        <VField class="is-rounded-select" v-slot="{ id }">
                            <VLabel>Pegawai</VLabel>
                            <VControl fullwidth :loading="isLoadChange">
                                <Multiselect mode="single" placeholder="Pilih data" v-model="item2.pegawai"
                                    :options="d_Pegawai2" :searchable="true" :attrs="{ id }" autocomplete="off"
                                    appendTo="body" track-by="value" />
                            </VControl>
                        </VField>
                    </div>
                    <!-- <div class="column is-6">
                        <VField>
                            <VControl icon="feather:search">
                                <input v-model="filterPaket.global.value" type="text" class="input is-rounded"
                                    placeholder="Filter " />
                            </VControl>
                        </VField>
                    </div> -->
                    <div class="column is-12">
                        <VCard>
                            <DataTable :value="dataSourcePaket" v-model:expandedRows="expandedRows" :paginator="true"
                                :rows="10" :rowsPerPageOptions="[5, 10, 25]" class="p-datatable-customers"
                                filterDisplay="menu" v-model:filters="filterPaket"
                                paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                :globalFilterFields="['namapaket']" :scrollable="true" :loading="dataSourcePaket.loading"
                                dataKey="id">
                                <template #header>
                                    <div class="flex justify-content-end">
                                        <span class="p-input-icon-left">
                                            <i class="pi pi-search" />
                                            <InputText v-model="filterPaket.global.value" placeholder="Keyword Search" />
                                        </span>
                                    </div>
                                </template>
                                <Column :expander="true" :style="{ width: '50px' }" />
                                <Column :exportable="false" header="#" :style="{ width: '50px' }">
                                    <template #body="slotProps">
                                        <VIconButton type="button" icon="pi pi-plus" class="mr-2" color="info" circle
                                            outlined raised v-tooltip.top="'Tambah'" @click="tambahPaket(slotProps.data)"
                                            :loading="slotProps.data.isLoading">
                                        </VIconButton>
                                    </template>
                                </Column>

                                <Column field="no" header="No" :style="{ width: '40px' }"> </Column>
                                <Column field="namapaket" header="Nama Paket" style="width:250px" :sortable="true"></Column>
                                <Column field="jml" header="Jumlah Pelayanan" style="width:100px"></Column>
                                <Column field="hargapaket" header="Harga Paket" style="width:100px">
                                    <template #body="slotProps">
                                        {{ H.formatRp(slotProps.data.hargapaket, '') }}
                                    </template>
                                </Column>
                                <template #expansion="slotProps">
                                    <div class="orders-subtable">
                                        <h5>Paket : {{ slotProps.data.namapaket }}</h5>
                                        <DataTable :value="slotProps.data.details" responsiveLayout="scroll">
                                            <Column field="namaproduk" header="Pelayanan" :sortable="true"> </Column>
                                        </DataTable>
                                    </div>
                                </template>

                            </DataTable>
                        </VCard>
                    </div>

                </div>

            </Dialog>
        </div>
    </section>
</template>
<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useThemeColors } from '/@src/composable/useThemeColors'
import { useToaster } from '/@src/composable/toaster'
import Dialog from 'primevue/dialog';
import Divider from 'primevue/divider';
import ConfirmDialog from 'primevue/confirmdialog';
import { useConfirm } from "primevue/useconfirm";
import DataTable from 'primevue/datatable';
import AutoComplete from 'primevue/autocomplete';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import MultiSelect from 'primevue/multiselect';
import Calendar from 'primevue/calendar';

import { FilterMatchMode } from 'primevue/api';
useHead({
    title: 'Tindakan - ' + import.meta.env.VITE_PROJECT,
})
useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pasien_daftar as string
let NOREC_APD = useRoute().query.norec_apd as string
const props = defineProps({
    registrasi: {
        type: Object as PropType<any>,
    },
    pasien: {
        type: Object as PropType<any>,
    },
    selected: undefined,
    type: undefined,
    align: undefined,
})
useViewWrapper().setFullWidth(props.pasien ? true : false)

// debugger
const isLoadingPasien: any = ref(false)
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: NOREC_APD != undefined ? NOREC_APD : '',
    hargasatuan: 0,
    tglpelayanan: new Date(),
    totalHarga: 0
})
const item2: any = reactive({
    tglpelayanan: new Date(),
})
const d_Produk: any = ref([])
const d_Komponen: any = ref([])
const d_JenisPelaksana: any = ref([])
const d_Pegawai: any = ref([])
const d_Pegawai2: any = ref([])
const pasien: any = ref({})
const { y } = useWindowScroll()
const dataSourceTindakan: any = ref([])
const filterPaket: any = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
})
const dataSource: any = ref([])
const selectedTabs: any = ref()
const listChecked: any = ref([])
const modelCheck: any = ref([])
const isLoadingBill: any = ref(false)
const isLoadingPop: any = ref(false)
const modalPetugas: any = ref(false)
const dataSourcePetugas: any = ref([])
const dataSourceKom: any = ref([])
const dataSourcePaket: any = ref([])
const expandedRows: any = ref([]);
const modalKomponen: any = ref(false)
const listColor: any = ref(Object.keys(useThemeColors()))
const confirm = useConfirm();
const dataSelect: any = ref({})
const isPaket: any = ref(false)
const modalPaket: any = ref(false)
const isStuck = computed(() => {
    return y.value >= 30
})

const tabs: any = ref([
    { label: 'Tindakan', value: 1, icon: 'lnir lnir-medicine-alt' },
    { label: 'Riwayat', value: 2, icon: 'fas fa-list' },
])
const emit = defineEmits<{
    (e: 'update:selected', value: string): void
}>()
const filters: any = ref('')
const activeValue: any = ref(1)
const sliderClass = computed(() => {
    if (!props.slider) {
        return ''
    }

    if (props.type === 'rounded') {
        if (props.tabs.length === 3) {
            return 'is-triple-slider'
        }
        if (props.tabs.length === 2) {
            return 'is-slider'
        }

        return ''
    }

    if (!props.type) {
        if (props.tabs.length === 3) {
            return 'is-squared is-triple-slider'
        }
        if (props.tabs.length === 2) {
            return 'is-squared is-slider'
        }
    }

    return ''
})
const toggle = (value: string) => {
    activeValue.value = value
}
const dataSourcefiltered = computed(() => {
  if (!filters.value ) {
    return dataSource.value
  }

  return dataSource.value.map((group: any) => {
    return {
      tglpelayanan_group: group.tglpelayanan_group,
      details: group.details.filter((detail: any) =>
        detail.namaproduk.match(new RegExp(filters.value, 'i'))
        || detail.namaruangan.match(new RegExp(filters.value, 'i'))
        || detail.dokterpemeriksa.match(new RegExp(filters.value, 'i'))
      )
    };
  }).filter((group: any) => group.details.length > 0);

});
const modalInput: any = ref(false)
const isLoading: any = ref(false)
const isLoadChange: any = ref(false)
const listItem: any = ref([
    {
        pegawai: [],
        jenisPelaksana: null,
        d_Pegawai: []
    }
])
const data2: any = ref([])
const isDetail: any = ref([true])
function pasienByID(id: any) {
    if (props.pasien != undefined) {
        pasien.value = props.pasien
        item.NOREC_APD = props.registrasi.norec_apd
        item.RUANGAN_LAST = props.registrasi.objectruanganlastfk
        item.registrasi = props.registrasi
        dropdownTindakan(item.RUANGAN_LAST, props.registrasi.objectkelasfk)
    } else {
        isLoadingPasien.value = true
        useApi().get(
            `/tindakan/header-pasien?nocmfk=${id}&norec_pd=${item.NOREC_PD}&norec_apd=${item.NOREC_APD}`).then((response: any) => {
                pasien.value = response.pasien
                item.NOREC_APD = response.last_registrasi.norec_apd
                item.RUANGAN_LAST = response.last_registrasi.objectruanganfk //response.last_registrasi.objectruanganlastfk
                item.registrasi = response.last_registrasi
                isLoadingPasien.value = false
                dropdownTindakan(item.RUANGAN_LAST, response.last_registrasi.objectkelasfk)
            })
    }
}
function dropdownTindakan(idruang: any, idkelas: any) {
    useApi().get(
        `/tindakan/list-tindakan?idruangan=${idruang}&idkelas=${idkelas}`).then((response: any) => {
            d_Produk.value = response.data.map((e: any) => { return { label: e.namaproduk, value: e } })

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
        for (let x = 0; x < response.jenispetugaspelaksana.length; x++) {
            const element = response.jenispetugaspelaksana[x];

            if (element.jenispetugaspe.toLowerCase().indexOf('pemeriksa') > -1) {
                item2.jenisPelaksana = element.id
                changeJenis2(item2)
                break
            }
        }

    })
}
function changeSwitch(e: any) {
    item.isparamedis = e
}
function addNewItem() {
    listItem.value.push({
        jenisPelaksana: null,
        pegawai: [],
        d_Pegawai: []
    });
}
function removeItem(index: any) {
    listItem.value.splice(index, 1)
}
function showModal() {
    item.hargasatuan = 0
    item.tglpelayanan = new Date()
    modalInput.value = true
}
function handleChange(e: any) {
    item.iscito = e
}
function changeTindakan(e: any) {
    isLoading.value = true
    d_Komponen.value = []
    item.hargasatuan = 0
    useApi().get(
        '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
        + '&idKelas=' + item.registrasi.objectkelasfk
        + '&idProduk=' + e.id
        + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
        + '&idPenjamin=' + item.registrasi.objectrekananfk
    ).then((response: any) => {
        isLoading.value = false
        item.hargasatuan = response.harga.hargasatuan
        item.hargasatuanDef = response.harga.hargasatuan
        item.jumlah = 1
        d_Komponen.value = response.komponen
    })
}

const changeJenis = async (e: any) => {

    if (!e.jenisPelaksana) {
        H.alert('warning', 'Jenis pelaksana wajib dipilih')
        return
    }
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
    //     // d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
    // })

    // console.log(d_Pegawai.value)
}
const changeJenis2 = async (e: any) => {
    isLoadChange.value = true
    await useApi().get('/tindakan/list-map-jenis-petugas?idJenisPetugas=' + e.jenisPelaksana).then((response: any) => {
        if (response != null) {
            d_Pegawai2.value = response.map((e: any) => {
                return {
                    label: e.namalengkap, value: e.id
                }
            })

        } else {
            d_Pegawai = []
        }
    })
    isLoadChange.value = false
    //     // d_Pegawai.value = response.map((e: any) => { return { label: e.namalengkap, value: e.id, default: e } })
    // })

    // console.log(d_Pegawai.value)
}

function tambah() {
    if (!item.produk) {
        useToaster().error('Pelayanan harus di isi')
        return
    }
    if (!item.jumlah) {
        useToaster().error('Jumlah harus di isi')
        return
    }
    if (item.hargasatuan == 0) {
        useToaster().error('Harga Satuan belum ada')
        return
    }


    let nomor = 0
    if (data2.value.length == 0) {
        nomor = 1
    } else {
        nomor = data2.value.length + 1
    }
    let petugas = []
    for (let i = 0; i < listItem.value.length; i++) {
        const element = listItem.value[i];
        var jenispet = ''
        for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
            const element2 = d_JenisPelaksana.value[z];
            if (element.jenisPelaksana == element2.value) {
                jenispet = element2.label
                break
            }
        }
        var listPegawai: any = []
        for (let k = 0; k < element.pegawai.length; k++) {
            const elementxx = element.pegawai[k];
            var namaPegawai = ''
            for (let zz = 0; zz < element.d_Pegawai.length; zz++) {
                const elementPeg = element.d_Pegawai[zz];
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
    var jasacito = 0
    if (item.iscito && item.iscito == true) {
        jasacito = parseFloat(item.hargasatuanDef) * item.nilaiCito;
    }

    var data: any = {};
    if (item.no != undefined) {
        for (let x = 0; x < data2.value.length; x++) {
            const element = data2.value[x];
            if (element.no == item.no) {
                data.no = item.no
                data.tglpelayanan = H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm')
                data.produkfk = item.produk.id
                data.namaproduk = item.produk.namaproduk
                data.hargasatuan = item.hargasatuanDef
                data.jumlah = item.jumlah
                data.pelayananpetugas = petugas
                data.komponenharga = d_Komponen.value
                data.iscito = item.iscito ? item.iscito : false
                data.jasacito = jasacito
                data.isparamedis = item.isparamedis ? item.isparamedis : false
                data.diskon = 0
                data.subtotal = parseFloat(item.hargasatuanDef) * parseFloat(item.jumlah) + jasacito
                data.icon = item.iscito == true ? "<i class='iconify' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify' data-icon='feather:x-circle' aria-hidden='true'></i>"

                data2.value[x] = data;
            }
        }
    } else {

        data = {
            'no': nomor,
            'tglpelayanan': H.formatDate(item.tglpelayanan, 'YYYY-MM-DD HH:mm'),
            'produkfk': item.produk.id,
            'namaproduk': item.produk.namaproduk,
            'hargasatuan': item.hargasatuanDef,
            'jumlah': item.jumlah,
            'pelayananpetugas': petugas,
            'komponenharga': d_Komponen.value,
            'iscito': item.iscito ? item.iscito : false,
            'icon': item.iscito == true ? "<i class='iconify is-success' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
            'jasacito': jasacito,
            'isparamedis': item.isparamedis ? item.isparamedis : false,
            'diskon': 0,
            'subtotal': parseFloat(item.hargasatuanDef) * parseFloat(item.jumlah) + jasacito
        }
        data2.value.push(data)
    }
    isDetail.value[data2.value.length] = true
    dataSourceTindakan.value = data2.value
    countTotal()
    clearInput()
}
function editItems(e: any) {
    item.no = e.no
    item.produk = { id: e.produkfk, namaproduk: e.namaproduk }
    item.tglpelayanan = new Date(e.tglpelayanan)
    item.hargasatuanDef = e.hargasatuan
    item.hargasatuan = e.hargasatuan
    item.jumlah = e.jumlah
    item.iscito = e.iscito
    changeSwitch((e.isparamedis ? true : false))
    d_Komponen.value = e.komponenharga
    listItem.value = []
    for (let x = 0; x < e.pelayananpetugas.length; x++) {
        const element = e.pelayananpetugas[x];
        var peg = []
        for (let z = 0; z < element.listpegawai.length; z++) {
            const elementz = element.listpegawai[z];
            peg.push(elementz.id)
        }
        listItem.value.push({
            'jenisPelaksana': element.objectjenispetugaspefk,
            'pegawai': peg,
        })
    }
    modalInput.value = true
}
function hapusItems(e: any) {
    for (var i = data2.value.length - 1; i >= 0; i--) {
        if (data2.value[i].no == e.no) {
            data2.value.splice(i, 1);
        }
    }
    dataSourceTindakan.value = data2.value
    countTotal()
    clearInput()
}
function multiSelectArrayToString(item: any) {
    return item.map(function (elem: any) {
        return elem.namalengkap
    }).join(", ");

}
function countTotal() {

    let total = 0
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        total = total + parseFloat(element.subtotal)
    }
    item.totalHarga = total
}
function clearInput() {
    if (item.produk) {
        H.alert('info', item.produk.namaproduk + ' berhasil ditambahkan')
    }
    delete item.produk
    delete item.jumlah
    delete item.no
    delete item.iscito
    delete item.isparamedis

    // for (let i = 0; i < listItem.value.length; i++) {
    //     const element = listItem.value[i];
    //     element.pegawai = []
    // }
    // modalInput.value = false
}
function kembaliKeun() {
    window.history.back()
}
function clearList() {
    data2.value = []
    dataSourceTindakan.value = data2.value
}
async function simpan() {
    if (data2.value.length == 0) { H.alert('warning', 'Tindakan belum di isi'); return }
    for (let x = 0; x < data2.value.length; x++) {
        const element = data2.value[x];
        element.kelasfk = item.registrasi.objectkelasfk
        element.norec_apd = item.NOREC_APD
        element.norec_pd = NOREC_PD
        element.tglregistrasi = item.registrasi.tglregistrasi
        if (element.komponenharga.length == 0) {
            H.alert('warning', 'Tindakan (' + element.namaproduk + ') ini belum ada komponen harganya, harap hubungi IT')
            return
        }
    }
    let json = {
        'pelayananpasien': data2.value,
        'noregistrasi': item.registrasi.noregistrasi,
        'nocm': pasien.value.nocm,
        'namapasien': pasien.value.namapasien,
        'namaruangan': item.registrasi.namaruangan
    }
    isLoading.value = true
    await useApi().post(
        `/tindakan/save-tindakan`, json).then((response: any) => {
            isLoading.value = false
            clearList()
        }).catch((e: any) => {
            isLoading.value = false
        })
}
async function fetchTindakan(filter: any) {
    let query = ''
    if (filter) {
        query = filter.toLowerCase()
    }
    const response = await useApi().get(
        `/tindakan/list-tindakan?name= ${query}&limit=10&idruangan=${item.RUANGAN_LAST}`)

    return response.data.map((item: any) => {
        return { value: item.id, label: item.namaproduk, default: item }
    })
}
const fetchBill = async () => {
    isLoadingBill.value = true
    dataSource.value = []
    await useApi().get(
        `/kasir/billing?norec_pd=${item.NOREC_PD}&istindakan=true`).then(async (response: any) => {
            dataSource.value = response.detail

            // item.DIBAYAR = response.dibayar
            // item.SISA = response.sisa
            item.TOTAL_BILL = response.total
            // item.DEPOSIT = response.deposit
            // item.DISKON = response.diskon
            // item.DIKLAIM = response.klaim
            // item.PENGEMBALIAN = response.pengembalian
            // item.IURBAYAR = response.iurbayar
            // item.length = response.length
            // item.tarif_inacbg = response.tarif_inacbg
            isLoadingBill.value = false
        })
}
const groupRuang = (result: any) => {
    let sama = false
    let arrGroup: any = [];
    for (let i = 0; i < result.length; i++) {
        sama = false
        for (let x = 0; x < arrGroup.length; x++) {
            if (arrGroup[x].namadepartemen == result[i].namadepartemen) {
                sama = true;
            }
        }
        if (sama == false) {
            let data = {
                'namadepartemen': result[i].namadepartemen,
                'details': [],
            }
            arrGroup.push(data)
        }
    }
    for (let x = 0; x < arrGroup.length; x++) {
        const element = arrGroup[x];
        for (let y = 0; y < result.length; y++) {
            const element2 = result[y];
            if (element.namadepartemen == element2.namadepartemen) {
                element.details.push(element2)
            }
        }

    }
    return arrGroup
}
const checkedAll = (e: any) => {
    modelCheck.value = []
    listChecked.value = []
    if (e) {
        dataSource.value.forEach((e: any) => {
            e.details.forEach((f: any) => {
                listChecked.value.push(f)
                modelCheck.value[f.norec] = true
            });
        });
    }
}
const checkedItems = () => {
    let objectK = Object.keys(modelCheck.value)
    for (let x = 0; x < objectK.length; x++) {
        const element = objectK[x];
        if (modelCheck.value[element] == true) {
            for (var i = 0; i < dataSource.value.length; i++) {
                const element2 = dataSource.value[i];
                for (let xx = 0; xx < element2.details.length; xx++) {
                    const element3 = element2.details[xx];
                    if (element3.norec == element) {
                        for (var z = 0; z < listChecked.value.length; z++) {
                            const element4 = listChecked.value[z];
                            if (element4.norec == element3.norec) {
                                listChecked.value.splice(z, 1)
                            }
                        }
                        listChecked.value.push(element3)
                    }
                }

            }
        } else {
            for (var i = 0; i < dataSource.value.length; i++) {
                const element2 = dataSource.value[i];
                for (let xx = 0; xx < element2.details.length; xx++) {
                    const element3 = element2.details[xx];
                    if (element3.norec == element) {
                        for (var z = 0; z < listChecked.value.length; z++) {
                            const element4 = listChecked.value[z];
                            if (element4.norec == element3.norec) {
                                listChecked.value.splice(z, 1)
                            }
                        }
                    }
                }
            }
        }
    }
}

const hapusTindakan = (e: any) => {
    confirm.require({
        group: 'positionDialog',
        message: H.alertHapus(),
        header: 'Info ',
        icon: 'pi pi-info-circle',
        acceptClass: 'p-button-danger',
        position: 'top',
        accept: () => {
            if (e.strukfk != null) {
                H.alert('error', H.alertKasir())
                return
            }
            if (e.strukresepfk != null) {
                H.alert('error', 'Resep hanya bisa dihapus di Farmasi')
                return
            }
            var objSave = {
                'data':
                    [{
                        'norec_pp': e.norec,
                        'namaproduk': e.namaproduk,
                        'namaruangan': e.namaruangan,
                    }],
                'nocm': pasien.value.nocm,
                'namapasien': pasien.value.namapasien,
                'noregistrasi': pasien.value.noregistrasi,
            }
            nextHapus(objSave)
        },
        reject: () => {
        }
    });

}

const nextHapus = (objSave: any) => {
    isLoadingBill.value = true
    useApi().post(
        `/kasir/billing/hapus-tindakan`, objSave).then((response: any) => {
            isLoadingBill.value = false
            fetchBill()
        }).catch((e: any) => {
            isLoadingBill.value = false
        })
}
const detailPetugas = async (e: any) => {
    dataSelect.value = e
    await loadPetugas(e)
    modalPetugas.value = true

}
const loadPetugas = async (e: any) => {
    isLoadingPop.value = true
    dataSourcePetugas.value = []
    await useApi().get(
        `/kasir/billing/petugas-tindakan?norec=${e.norec}`).then((response: any) => {
            isLoadingPop.value = false
            dataSourcePetugas.value = response
        }).catch((e: any) => {
            isLoadingPop.value = false
        })

}
const detailKomponen = (e: any) => {
    delete item.norecPPD
    dataSelect.value = {}
    dataSelect.value = e
    modalKomponen.value = true
    loadKomponen(e)
}
const loadKomponen = async (e: any) => {
    isLoadingPop.value = true
    dataSourceKom.value = []
    item.totalKomponen = 0
    await useApi().get(
        `/kasir/billing/detail-tindakan?norec=${e.norec}`).then((response: any) => {
            isLoadingPop.value = false
            item.totalKomponen = response.total
            dataSourceKom.value = response.data
        }).catch((e: any) => {
            isLoadingPop.value = false
        })
}
const fetchPaket = async () => {
    modalPaket.value = true
    await useApi().get(
        `/tindakan/list-paket`).then((response: any) => {
            dataSourcePaket.value = response
        }).catch((e: any) => {

        })
}
const tambahPaket = async (e: any) => {

    if (!item2.jenisPelaksana) {
        H.alert('error', 'Jenis Pelaksana harus di isi')
        return
    }
    if (!item2.pegawai) {
        H.alert('error', 'Pegawai harus di isi')
        return
    }
    e.isLoading = true
    let totalHargaDefault = 0
    for (var i = 0; i < e.details.length; i++) {
        const TINDAKAN = e.details[i];


        let listKomponen = []
        let response = await useApi().get(
            '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
            + '&idKelas=' + item.registrasi.objectkelasfk
            + '&idProduk=' + TINDAKAN.objectprodukfk
            + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
            + '&idPenjamin=' + item.registrasi.objectrekananfk
        )
        e.isLoading = false

        if (response.komponen.length == 0) {
            H.alert('error', 'Komponen tindakan ( ' + TINDAKAN.namaproduk + ') ini tidak ada .')
            break
        }
        totalHargaDefault = totalHargaDefault + response.harga.hargasatuan
    }
    e.isLoading = true
    for (var i = 0; i < e.details.length; i++) {
        const TINDAKAN = e.details[i];
        e.isLoading = false

        let listKomponen = []
        let response = await useApi().get(
            '/tindakan/list-tindakan-komponen?idRuangan=' + item.registrasi.objectruanganfk
            + '&idKelas=' + item.registrasi.objectkelasfk
            + '&idProduk=' + TINDAKAN.objectprodukfk
            + '&idJenisPelayanan=' + item.registrasi.jenispelayananfk
            + '&idPenjamin=' + item.registrasi.objectrekananfk
        )

        let hargasatuan = response.harga.hargasatuan


        if (TINDAKAN.hargapaket != 0 && TINDAKAN.hargapaket < totalHargaDefault) {
            hargasatuan = TINDAKAN.hargapaket / totalHargaDefault * hargasatuan
            //** Kompoonen */
            for (let j = 0; j < response.komponen.length; j++) {
                const elementXX = response.komponen[j];
                elementXX.hargasatuan = hargasatuan / parseFloat(hargasatuan) * parseFloat(elementXX.hargasatuan)

                elementXX.hargasatuan = parseFloat(elementXX.hargasatuan.toFixed(2))
            }
        }
        listKomponen = response.komponen

        let petugas = []
        let nomor = 0
        if (data2.value.length == 0) {
            nomor = 1
        } else {
            nomor = data2.value.length + 1
        }

        let jenispet = ''
        for (let z = 0; z < d_JenisPelaksana.value.length; z++) {
            const element2 = d_JenisPelaksana.value[z];
            if (item2.jenisPelaksana == element2.value) {
                jenispet = element2.label
                break
            }
        }
        let namaPegawai = null

        for (let zz = 0; zz < d_Pegawai2.value.length; zz++) {
            const elementPeg = d_Pegawai2.value[zz];
            if (item2.pegawai == elementPeg.value) {
                namaPegawai = elementPeg.label
                break
            }
        }
        petugas.push({
            "objectjenispetugaspefk": item2.jenisPelaksana,
            "jenispetugaspe": jenispet,
            "listpegawai": [
                {
                    'id': item2.pegawai,
                    'namalengkap': namaPegawai
                }
            ]
        })

        var jasacito = 0
        let data: any = {
            'no': nomor,
            'tglpelayanan': H.formatDate(item2.tglpelayanan, 'YYYY-MM-DD HH:mm'),
            'produkfk': TINDAKAN.objectprodukfk,
            'namaproduk': TINDAKAN.namaproduk,
            'hargasatuan': hargasatuan,
            'jumlah': 1,
            'pelayananpetugas': petugas,
            'komponenharga': listKomponen,
            'iscito': item.iscito ? item.iscito : false,
            'icon': item.iscito == true ? "<i class='iconify is-success' data-icon='feather:check-circle' aria-hidden='true'></i>" : "<i class='iconify is-danger' data-icon='feather:x-circle' aria-hidden='true'></i>",
            'jasacito': jasacito,
            'isparamedis': item.isparamedis ? item.isparamedis : false,
            'diskon': 0,
            'subtotal': parseFloat(hargasatuan) * parseFloat(1) + jasacito
        }
        data2.value.push(data)

        isDetail.value[data2.value.length] = true
        dataSourceTindakan.value = data2.value
        e.isLoading = false
        H.alert('info', TINDAKAN.namaproduk + '  berhasil ditambahkan .')

        countTotal()
        clearInput()
    }
    isPaket.value = false
    modalPaket.value = false
    isLoading.value = false
}
watch(
    () => selectedTabs,
    (value) => {
        activeValue.value = value
    }
)

watch(activeValue, (value: any) => {
    emit('update:selected', value)
})

watch(
    () => activeValue.value,
    (value) => {
        if (value == 2) {
            fetchBill()
        }
    }
)
watch(() => isPaket.value, (newValue, oldValue) => {
    if (newValue == true) {
        fetchPaket()
    }
})
pasienByID(ID_PASIEN)
dropdownList()
</script>
<style lang="scss">
@import '/@src/scss/abstracts/all';
@import '/@src/scss/components/forms-outer';
@import '/@src/scss/custom/config';
@import '/@src/scss/custom/timeline-css';

.form-layout {
    max-width: 1200px;
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item .dot {

    margin: 0 10px;
}

.timeline-wrapper .timeline-wrapper-inner .timeline-container .timeline-item::before {
    // content: "";
    content: none;
    position: absolute;
    top: 63px;
    left: 97px;
    height: 100%;
    width: 2px;
    background: var(--placeholder);
    z-index: 0;
}

.field>label {
    width: 250px;
}
.is-rounded-select{
  .p-calendar{
    border-radius : 20px !important;
     .p-inputtext {
      border-top-left-radius: 15px;
      border-bottom-left-radius: 15px;
     }
  }
  .p-calendar-w-btn .p-datepicker-trigger {
      border-top-left-radius: 0;
      border-bottom-left-radius: 0;
      border-top-right-radius: 15px;
      border-bottom-right-radius: 15px;
    }
}


 .field>label {
     color: var(--light-text) !important;
 }
</style>
