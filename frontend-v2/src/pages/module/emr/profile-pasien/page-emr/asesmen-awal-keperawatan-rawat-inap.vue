<template>
    <div class="form-layout is-stacked-2">
        <div class="form-outer" style="margin-top:15px">
            <div :class="[isStuck && 'is-stuck']" class="form-header stuck-header">
                <div class="form-header-inner">
                    <div class="left">
                        <h3> {{ props.FORM_NAME }}</h3>
                    </div>
                    <div class="right">
                        <div class="buttons">
                            <VButton icon="lnir lnir-arrow-left rem-100" light dark-outlined @click="kembaliKeun()">
                                Kembali
                            </VButton>
                            <VButton type="button" rounded outlined color="warning" raised icon="lnir lnir-printer"
                                @click="print()"> Cetak
                            </VButton>
                            <VButton type="button" rounded outlined color="primary" raised icon="feather:save"
                                :loading="isLoading" @click="simpan()"> Simpan
                            </VButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="columns is-multiline p-2">
        <div class="column is-12">
            <VCard>
                <div class="column is-12">
                    <Fieldset :toggleable="true" legend="Diagnosa Masuk">
                        <div class="column is-12">
                            <VTabs selected="icd9" :tabs="[
                                { label: 'Diagnosa ICD 9', value: 'icd9' },
                                { label: 'Diagnosa ICD 10', value: 'icd10' },
                            ]">
                                <template #tab="{ activeValue }">
                                    <p v-if="activeValue === 'icd9'">
                                        <DataTable :value="dataSourceICD9" class="p-datatable-sm" :loading="isLoading"
                                            :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                                            selectionMode="single"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                            showGridlines>

                                            <Column field="no" header="#"></Column>
                                            <Column field="noregistrasi" header="No Registrasi"></Column>
                                            <Column field="kddiagnosatindakan" header="Kode ICD 9"></Column>
                                            <Column field="namadiagnosatindakan" header="Nama ICD 9"></Column>
                                            <Column field="namaruangan" header="Rungan"></Column>
                                            <Column field="tglInput" header="Tgl Input"></Column>
                                        </DataTable>
                                    </p>
                                    <p v-else-if="activeValue === 'icd10'">
                                        <DataTable :value="dataSourceICD10" class="p-datatable-sm" :loading="isLoading"
                                            :paginator="true" :rows="5" :rowsPerPageOptions="[5, 10, 25]"
                                            selectionMode="single"
                                            paginatorTemplate="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                                            responsiveLayout="stack" breakpoint="960px" sortMode="multiple"
                                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
                                            showGridlines>

                                            <Column field="no" header="#"></Column>
                                            <Column field="noregistrasi" header="No Registrasi" />
                                            <Column field="jenisdiagnosa" header="Jenis Diagnosa" />
                                            <Column field="kddiagnosa" header="Kode ICD 10" />
                                            <Column field="namadiagnosa" header="Nama ICD 10" />
                                            <Column field="namaruangan" header="Ruangan" />
                                            <Column field="tglInput" header="TGL Input" />
                                        </DataTable>
                                    </p>
                                </template>
                            </VTabs>
                        </div>
                    </Fieldset>
                </div>

                <div class="column is-12">
                    <VCard class="border-card pink">
                        <h1 class="emr">Data Umum</h1>
                        <h1 class="emr ml-3 mt-3">Kondisi saat pasien masuk</h1>
                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-one-quarter">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.konPasienMasuk" true-value="Mandiri" label="Mandiri"
                                                color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-one-quarter">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.konPasienMasuk" true-value="dipapah" label="dipapah"
                                                color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-one-quarter">
                                    <VField label="Lainnya">
                                        <VControl>
                                            <input v-model="input.kondisiLainnya" class="input" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <p class="mb-3">Keluhan Utama</p>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keluhanUtama" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12">
                            <p class="mb-3">Keluhan Saat Ini</p>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.keluhanSaatIni" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>
                        <div class="column is-12">
                            <p class="mb-3">Riwayat Penyakit Dahulu</p>
                            <VField>
                                <VControl>
                                    <VTextarea v-model="input.riwayatPenyakitDahulu" rows="3">
                                    </VTextarea>
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12">
                            <h1 class="emr">Asal Pasien</h1>
                            <div class="columns is-multiline">
                                <div class="column is-3">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.asalPasien" true-value="Poliklinik" label="Poliklinik"
                                                color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.asalPasien" true-value="IGD" label="IGD"
                                                color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField>
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input.asalPasien" true-value="Kamar Operasi"
                                                label="Kamar Operasi" color="primary" circle />
                                        </VControl>
                                    </VField>
                                </div>
                                <div class="column is-3">
                                    <VField label="Lainnya">
                                        <VControl raw subcontrol>
                                            <input v-model="input.asalLainnya" class="input" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-4">
                            <VField class="is-autocomplete-select" v-slot="{ id }">
                                <h1 class="mb-3 emr">Dpjp</h1>
                                <VControl icon="feather:search">
                                    <AutoComplete v-model="input.dokter" :suggestions="d_Pegawai"
                                        @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true"
                                        :minLength="3" :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                        placeholder="Cari nama Pegawai" />
                                </VControl>
                            </VField>
                        </div>

                        <div class="column is-12">
                            <h1 class="emr ">Alat bantu yang digunakan</h1>
                            <div class="columns is-multiline">
                                <div class="column" v-for="(data) in alatBantu"
                                    :class="data.nama === 'Alat Bantu Dengar' || data.type == 'textbox' ? 'is-3' : 'is-2'">
                                    <VField v-if="data.type == 'checkbox'">
                                        <VControl raw subcontrol>
                                            <VCheckbox v-model="input[data.model]" :true-value="data.nama"
                                                :label="data.nama" color="primary" circle />
                                        </VControl>
                                    </VField>

                                    <VField v-else :label="data.nama">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input pt-0" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <div class="columns is-multiline">
                                <div class="column is-one-quarter" v-for="(data) in ttv">
                                    <VField :label="data.title">
                                        <VControl raw subcontrol>
                                            <input v-model="input[data.model]" class="input v-else" />
                                        </VControl>
                                    </VField>
                                </div>
                            </div>
                        </div>

                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Glasgow Coma Scale Dewasa">
                                <div class="column is-12">
                                    <div class="columns is-multiline" style="text-align: center;">
                                        <div class="column p-0" v-for="(data) in glasgows">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input.glasgow" :true-value="data" :label="data"
                                                        color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <div class="column is-12" style="border-bottom: solid 1px;">
                                        <div class="columns is-multiline">
                                            <div class="column is-1">
                                                <h1 class="emr">No</h1>
                                            </div>
                                            <div class="column is-3">
                                                <h1 class="emr">Parameter</h1>
                                            </div>
                                            <div class="column is-6">
                                                <h1 class="emr">Pengkajian</h1>
                                            </div>
                                            <div class="column">
                                                <h1 class="emr">Nilai</h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-12">
                                        <div class="columns is-multiline" v-for="(data) in dataInTable.pertama">
                                            <div class="column is-1">
                                                <h1 class="emr">{{ data.no }}</h1>
                                            </div>
                                            <div class="column is-3">
                                                <h1 class="emr">{{ data.parameter }}</h1>
                                            </div>
                                            <div class="column is-6 pt-0">
                                                <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                    <VField class="">
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[value.model]" class="p-0"
                                                                :true-value="value.value" :label="value.title"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column pt-0">
                                                <div class="column pb-0" v-for="(poin) in data.nilai">
                                                    <h1 class="emr">{{ poin }}</h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="columns is-multiline" v-for="(data) in dataInTable.kedua">
                                            <div class="column is-1">
                                                <h1 class="emr">{{ data.no }}</h1>
                                            </div>
                                            <div class="column is-3">
                                                <h1 class="emr">{{ data.parameter }}</h1>
                                            </div>
                                            <div class="column is-6 pt-0">
                                                <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[value.model]"
                                                                :true-value="value.value" :label="value.title" class="p-0"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column pt-0">
                                                <div class="column pb-0" v-for="(poin) in data.nilai">
                                                    <h1 class="emr">{{ poin }}</h1>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="columns is-multiline" v-for="(data) in dataInTable.keTiga">
                                            <div class="column is-1">
                                                <h1 class="emr">{{ data.no }}</h1>
                                            </div>
                                            <div class="column is-3">
                                                <h1 class="emr">{{ data.parameter }}</h1>
                                            </div>
                                            <div class="column is-6 pt-0">
                                                <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                    <VField>
                                                        <VControl raw subcontrol>
                                                            <VCheckbox v-model="input[value.model]"
                                                                :true-value="value.value" :label="value.title" class="p-0"
                                                                color="primary" circle />
                                                        </VControl>
                                                    </VField>
                                                </div>
                                            </div>
                                            <div class="column pt-0">
                                                <div class="column pb-0" v-for="(poin) in data.nilai">
                                                    <h1 class="emr">{{ poin }}</h1>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="column is-3" style="margin-left: auto;">
                                        <VField label="Jumlah Total">
                                            <VControl raw subcontrol>
                                                <input v-model="input.jumlahNilai" class="input" disabled />
                                            </VControl>
                                        </VField>
                                    </div>

                                    <div class="column is-3">
                                        <VCard>
                                            <div class="column p-0 pb-2">
                                                <h1>KETERANGAN : </h1>
                                            </div>
                                            <div class="column p-0">
                                                <p>13-15 Ringan</p>
                                                <p>9-12 Sedang</p>
                                                <p>3-8 Berat</p>
                                            </div>

                                        </VCard>
                                    </div>

                                    <div class="column is-12">
                                        <h1 class="emr mb-3">Riwayat Pengobatan / Obat-obatan</h1>
                                        <VField>
                                            <VControl>
                                                <VTextarea v-model="input.riwayatPengobatan" rows="3">
                                                </VTextarea>
                                            </VControl>
                                        </VField>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="Dewasa dan Anak > 3 tahun (Wong Baker Faces)">
                                        <div class="column is-12">
                                            <div class="columns is-multiline">
                                                <div class="column is-1" v-for="(image, i) in listImageNyeri.detail">
                                                    <VAvatar size="medium" :picture="image.img"
                                                        style="margin-left: 10px; cursor:pointer !important"
                                                        :class="isAktive == i ? 'active' : ''"
                                                        @click="choiceScore(image, i)" />
                                                    <p style="text-align: center;">{{ image.descNilai }}</p>
                                                    <p style="text-align: center;">{{ image.nama }}</p>
                                                </div>
                                                <div class="column is-6">
                                                    <h1 style="font-weight: bold;">Score</h1>
                                                    <div class="columns is-multiline mt-2">
                                                        <div class="column is-6 pb-0"
                                                            v-for="(skor) in listSkoringNyeri.detail">
                                                            <VField>
                                                                <VControl raw subcontrol class="p-0">
                                                                    <VCheckbox class="p-0" v-model="input.skoringNyeri"
                                                                        :true-value="skor.descNilai" :label="skor.nama"
                                                                        color="primary" circle />
                                                                </VControl>
                                                            </VField>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <h1 class="emr">Riwayat Pasien</h1>
                                    <div class="columns is-multiline pt-4 pl-3">
                                        <div class="column" v-for="(data, i) in riwayatPasien"
                                            :class="data.title == 'Penyakit paru lainnya' ? 'is-3' : 'is-2'">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                                <div class="column is-4 pl-5">
                                    <VField label="Lainnya">
                                        <VControl raw subcontrol>
                                            <input v-model="input.riwayatPenyakitLainnya" class="input" />
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12 ml-3 mr-3">
                                    <p class="pb-3">Deskripsi penyakit dan operasi yang tidak tercantum diatas</p>
                                    <VField>
                                        <VControl>
                                            <VTextarea v-model="input.descPenyakitLain" rows="3">
                                            </VTextarea>
                                        </VControl>
                                    </VField>
                                </div>

                                <div class="column is-12 pb-0" v-for="(datas) in riwayatLainnya">
                                    <h1 class="emr">{{ datas.title }}</h1>
                                    <div class="columns is-multiline m-2">
                                        <div class="column pt-0" v-for="(data) in datas.value">
                                            <VField :label="data.subTitle" v-if="data.type == 'textbox'" class="p-0">
                                                <VControl raw subcontrol>
                                                    <input v-model="input.dscAlergi" class="input p-0" />
                                                </VControl>
                                            </VField>
                                            <VField v-else>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model]" :true-value="data.subTitle"
                                                        :label="data.subTitle" class="pt-5 mt-3" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12 pt-0">
                                    <h1 class="emr">Riwayat Keluarga</h1>
                                    <div class="columns is-multiline pt-4 pl-3">
                                        <div class="column is-3" v-for="(data, i) in riwayatKeluarga">
                                            <VField>
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[data.model + i]" :true-value="data.title"
                                                        :label="data.title" class="p-0" color="primary" circle />
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="RESIKO NUTRISIONAL">
                                        <h1 class="emr">Malnutrition Screening Tools (MST)</h1>
                                        <div class="column is-12">
                                            <div class="columns is-multiline" style="border-bottom: 1px solid;">
                                                <div class="column is-1">
                                                    <h1 class="emr">No</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">Parameter</h1>
                                                </div>
                                                <div class="column is-5"></div>
                                                <div class="column">
                                                    <h1 class="emr">Nilai</h1>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoNutrisional.pertama">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                            <small>{{ value.keterangan }}</small>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 :class="nilai.keterangan ? 'emr mb-5' : 'emr'">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoNutrisional.kedua">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]"
                                                                    :true-value="value.value" :label="value.title"
                                                                    class="p-0" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 :class="nilai.keterangan ? 'emr mb-3' : 'emr'">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-3" style="margin-left: auto;">
                                            <VField label="Jumlah Total">
                                                <VControl raw subcontrol>
                                                    <input v-model="input.totalNilaiMST" class="input v-else" disabled />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-12">
                                            <VCard>
                                                <h1 class="emr mb-2">Keterangan</h1>
                                                <div class="column" v-for="(desc) in keteranganJumlah">
                                                    <div class="columns is-multiline">
                                                        <div class="column is-3 p-0">
                                                            <p>{{ desc.rangeNilai }}</p>
                                                        </div>
                                                        <div class="column is-4 p-0">
                                                            <p>{{ desc.desc }}</p>
                                                        </div>
                                                    </div>
                                                </div>

                                            </VCard>
                                        </div>
                                    </Fieldset>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="PENILAIAN RESIKO JATUH MORSE">
                                        <div class="column is-12">
                                            <div class="columns is-multiline" style="border-bottom: 1px solid;">
                                                <div class="column is-1">
                                                    <h1 class="emr">No</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">Parameter</h1>
                                                </div>
                                                <div class="column is-5"></div>
                                                <div class="column">
                                                    <h1 class="emr">Nilai</h1>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.pertama">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.kedua">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]"
                                                                    :true-value="value.value" :label="value.title"
                                                                    class="p-0" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.ketiga">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.keempat">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]"
                                                                    :true-value="value.value" :label="value.title"
                                                                    class="p-0" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.lima">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoJatuhMorse.enam">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]"
                                                                    :true-value="value.value" :label="value.title"
                                                                    class="p-0" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="column is-3" style="margin-left: auto;">
                                            <VField label="Jumlah Total">
                                                <VControl raw subcontrol>
                                                    <input v-model="input.totalNilaiRJM" class="input v-else" disabled />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-12">
                                            <table class="assesment-awal">
                                                <thead>
                                                    <tr>
                                                        <th>Tingkatan Resiko</th>
                                                        <th>Nilai MFC</th>
                                                        <th>Tindakan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(datas) in ketJumlahPoin">
                                                        <td>
                                                            <VField>
                                                                <VControl raw subcontrol>
                                                                    <VCheckbox v-model="input[datas.model]"
                                                                        style="font-weight: bold !important;color:var(--dark-text);"
                                                                        :true-value="datas.value" :label="datas.title"
                                                                        class="p-0" color="primary" circle disabled />
                                                                </VControl>
                                                            </VField>
                                                        </td>
                                                        <td>
                                                            <h1 class="emr">{{ datas.nilai }}</h1>
                                                        </td>
                                                        <td>
                                                            <h1 class="emr">{{ datas.tindakan }}</h1>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </Fieldset>


                                </div>

                                <div class="column is-12">
                                    <h1 class="emr">8. Pemeriksaan Fisik Umum</h1>
                                    <div class="column is-12" v-for="(datas) in pemeriksaanFisikUmum">
                                        <h1 class="emr">{{ datas.title }}</h1>
                                        <div class="columns is-multiline pt-3" v-for="(data) in datas.detail">
                                            <div class="column is-3" v-if="data.type == 'checkBox'"
                                                v-for="(opsi, i) in data.value">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[data.model + i]" :true-value="opsi"
                                                            :label="opsi" color="primary" circle  class="p-0"/>
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-3" v-else-if="data.type == 'textBox'">
                                                <VField :label="data.subTitle">
                                                    <VControl raw subcontrol>
                                                        <input v-model="input[data.model]" class="input" />
                                                    </VControl>
                                                </VField>
                                            </div>
                                            <div class="column is-9 pt-0" v-else>
                                                <VField :label="data.subTitle">
                                                    <VControl>
                                                        <VTextarea v-model="input[data.model]" rows="3">
                                                        </VTextarea>
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="column is-12" v-for="(data) in pemeriksaanMuskuloskeletal ">
                                        <h1 class="emr">{{ data.title }}</h1>
                                        <div class="columns is-multiline pt-3">
                                            <div class="column p-0" v-for="(element) in data.detail"
                                                :class="data.detail.length > 5 ? 'is-3' : 'is-2'">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[element.model]"
                                                            :true-value="element.subTitle" :label="element.subTitle"
                                                            class="pb-0" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="column is-12">
                                    <Fieldset :toggleable="true" legend="Pemeriksaan Norton Scale (Resiko Kulit)">
                                        <div class="column is-12">
                                            <div class="columns is-multiline" style="border-bottom: 1px solid;">
                                                <div class="column is-1">
                                                    <h1 class="emr">No</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">Parameter</h1>
                                                </div>
                                                <div class="column is-5"></div>
                                                <div class="column">
                                                    <h1 class="emr">Nilai</h1>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoKulit.pertama">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoKulit.kedua">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]"
                                                                    :true-value="value.value" :label="value.title"
                                                                    class="p-0" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoKulit.ketiga">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoKulit.empat">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField>
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]"
                                                                    :true-value="value.value" :label="value.title"
                                                                    class="p-0" color="primary" circle />
                                                            </VControl>
                                                        </VField>
                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="columns is-multiline" v-for="(data) in resikoKulit.lima">
                                                <div class="column is-1">
                                                    <h1 class="emr">{{ data.no }}</h1>
                                                </div>
                                                <div class="column is-5">
                                                    <h1 class="emr">{{ data.parameter }}</h1>
                                                </div>
                                                <div class="column is-5 pt-0">
                                                    <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                        <VField class="">
                                                            <VControl raw subcontrol>
                                                                <VCheckbox v-model="input[value.model]" class="p-0"
                                                                    :true-value="value.value" :label="value.title"
                                                                    color="primary" circle />
                                                            </VControl>
                                                        </VField>

                                                    </div>
                                                </div>
                                                <div class="column pt-0">
                                                    <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                        <h1 class="emr">{{ nilai.poin }}
                                                        </h1>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="column is-2" style="margin-left: auto;">
                                            <VField label="Jumlah Total">
                                                <VControl raw subcontrol>
                                                    <input v-model="input.totalNilaiPmrksNortonScale" class="input v-else"
                                                        disabled />
                                                </VControl>
                                            </VField>
                                        </div>

                                        <div class="column is-12 pt-0">
                                            <VField label="Catatan">
                                                <VControl>
                                                    <VTextarea v-model="input.catatanResikoKulit" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </Fieldset>


                                </div>
                            </Fieldset>
                        </div>

                        <div class="column is-12">
                            <Fieldset :toggleable="true" legend="Pemeriksaan Norton Scale (Resiko Kulit)">
                                <div class="column is-12">
                                    <div class="columns is-multiline" style="border-bottom: 1px solid;">
                                        <div class="column is-1">
                                            <h1 class="emr">No</h1>
                                        </div>
                                        <div class="column is-5">
                                            <h1 class="emr">Parameter</h1>
                                        </div>
                                        <div class="column is-5"></div>
                                        <div class="column">
                                            <h1 class="emr">Nilai</h1>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline" v-for="(data) in pemeriksaanAHD.pertama">
                                        <div class="column is-1">
                                            <h1 class="emr">{{ data.no }}</h1>
                                        </div>
                                        <div class="column is-5">
                                            <h1 class="emr">{{ data.parameter }}</h1>
                                        </div>
                                        <div class="column is-5 pt-0">
                                            <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                <VField class="">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[value.model]" class="p-0"
                                                            :true-value="value.value" :label="value.title" color="primary"
                                                            circle />
                                                    </VControl>
                                                </VField>

                                            </div>
                                        </div>
                                        <div class="column pt-0">
                                            <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                <h1 class="emr">{{ nilai.poin }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline" v-for="(data) in pemeriksaanAHD.kedua">
                                        <div class="column is-1">
                                            <h1 class="emr">{{ data.no }}</h1>
                                        </div>
                                        <div class="column is-5">
                                            <h1 class="emr">{{ data.parameter }}</h1>
                                        </div>
                                        <div class="column is-5 pt-0">
                                            <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                <VField>
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[value.model]" :true-value="value.value"
                                                            :label="value.title" class="p-0" color="primary" circle />
                                                    </VControl>
                                                </VField>
                                            </div>
                                        </div>
                                        <div class="column pt-0">
                                            <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                <h1 class="emr">{{ nilai.poin }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="columns is-multiline" v-for="(data) in pemeriksaanAHD.ketiga">
                                        <div class="column is-1">
                                            <h1 class="emr">{{ data.no }}</h1>
                                        </div>
                                        <div class="column is-5">
                                            <h1 class="emr">{{ data.parameter }}</h1>
                                        </div>
                                        <div class="column is-5 pt-0">
                                            <div class="column is-12 pb-0" v-for="(value) in data.pengkajian">
                                                <VField class="">
                                                    <VControl raw subcontrol>
                                                        <VCheckbox v-model="input[value.model]" class="p-0"
                                                            :true-value="value.value" :label="value.title" color="primary"
                                                            circle />
                                                    </VControl>
                                                </VField>

                                            </div>
                                        </div>
                                        <div class="column pt-0">
                                            <div class="column pb-0" v-for="(nilai) in data.pengkajian">
                                                <h1 class="emr">{{ nilai.poin }}
                                                </h1>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="column is-2" style="margin-left: auto;">
                                    <VField label="Jumlah Total">
                                        <VControl raw subcontrol>
                                            <input v-model="input.totalNilaiPmrksADL" class="input" disabled />
                                        </VControl>
                                    </VField>
                                </div>
                            </Fieldset>
                        </div>

                        <div class="column is-12">
                            <h1 class="emr">Assesmen Keperawatan Khusus</h1>
                            <h1 class="emr">1. Asesmen Khusus Lansia (usia >65 Tahun)</h1>
                            <div class="column is-12" v-for="(datas) in AsesmentKepKhusus">
                                <h1 class="emr pl-0 mb-3">{{ datas.title }}</h1>
                                <div class="column is-12 pt-0 pb-0" v-for="(data) in datas.detail">
                                    <h1 class="emr mb-3">{{ data.subTitle }}</h1>
                                    <div class="columns is-multiline pl-5 pr-5">
                                        <div class="column" v-for="(value) in data.value">
                                            <VField v-if="value.type == 'checkBox'">
                                                <VControl raw subcontrol>
                                                    <VCheckbox v-model="input[value.model]" class="p-0 mb-3"
                                                        :true-value="value.name" :label="value.name" color="primary"
                                                        circle />
                                                </VControl>
                                            </VField>
                                            <VField :label="value.name" v-else-if="value.type == 'textBox'">
                                                <VControl raw subcontrol>
                                                    <input v-model="input[value.model]" class="input" />
                                                </VControl>
                                            </VField>
                                            <VField :label="value.name" v-else-if="value.type == 'textarea'"
                                                class="mt-3 mb-3">
                                                <VControl>
                                                    <VTextarea v-model="input[value.model]" rows="3">
                                                    </VTextarea>
                                                </VControl>
                                            </VField>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </VCard>
                </div>
            </VCard>
        </div>

        <div class="column is-6">
            <VCard class="border-card pink">
                <div class="columns is-multiline">
                    <div class="column is-6">
                        <h1 class="emr">Tanggal dan Jam</h1>
                        <VField>
                            <VDatePicker v-model="input.tgldanJam" mode="dateTime" style="width: 100%" trim-weeks
                                :max-date="new Date()">
                                <template #default="{ inputValue, inputEvents }">
                                    <VField>
                                        <VControl icon="feather:calendar" fullwidth>
                                            <VInput :value="inputValue" placeholder="Tanggal" v-on="inputEvents" />
                                        </VControl>
                                    </VField>
                                </template>
                            </VDatePicker>
                        </VField>
                    </div>

                    <div class="column is-6">
                        <h1 class="emr">Perawat</h1>
                        <VField class="is-autocomplete-select" v-slot="{ id }">
                            <VControl icon="feather:search">
                                <AutoComplete v-model="input.perawat" :suggestions="d_Pegawai"
                                    @complete="fetchPegawai($event)" :optionLabel="'label'" :dropdown="true" :minLength="3"
                                    :appendTo="'body'" :loadingIcon="'pi pi-spinner'" :field="'label'"
                                    placeholder="Cari Perawat" />
                            </VControl>
                        </VField>
                    </div>
                </div>
            </VCard>

        </div>

    </div>
</template>

<script setup lang="ts">
import { useWindowScroll } from '@vueuse/core'
import { useApi } from '/@src/composable/useApi'
import { h, reactive, ref, computed, defineComponent, watch, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useHead } from '@vueuse/head'
import * as H from '/@src/utils/appHelper'
import Fieldset from 'primevue/fieldset';
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import AutoComplete from 'primevue/autocomplete';
import { useViewWrapper } from '/@src/stores/viewWrapper'
import { useUserSession } from '/@src/stores/userSession'
import * as EMR from '../page-emr-plugins/asesmen-awal-keperawatan-rawat-inap'


// =======================

let alatBantu = ref(EMR.alatBantu())

let ttv = ref(EMR.ttv())

let glasgows = ref(EMR.glasgows())

let dataInTable = ref(EMR.dataInTable())

let listImageNyeri = ref(EMR.imgNyeri())

let listSkoringNyeri: any = ref(EMR.skoringNyeri())

let riwayatPasien = ref(EMR.riwayatPasien())

let riwayatKeluarga = ref(EMR.riwayatKeluarga())

let riwayatLainnya = ref(EMR.riwayatLainnya())

let resikoNutrisional = ref(EMR.listNutrisional())

let resikoJatuhMorse = ref(EMR.listResikoJatuh())

let keteranganJumlah = ref(EMR.descripJumlah())

let ketJumlahPoin = ref(EMR.descripJmlPoin())

let pemeriksaanFisikUmum = (EMR.listFisikUmum())

let pemeriksaanMuskuloskeletal = ref(EMR.listMuskuloketal())

let resikoKulit = ref(EMR.listResikoKulit())

let pemeriksaanAHD = ref(EMR.listAHD())

let AsesmentKepKhusus = ref(EMR.listKeperawatanKhusus())


// =========================================

let ID_PASIEN = useRoute().query.nocmfk as string
let NOREC_PD = useRoute().query.norec_pd as string
let norec_emr = useRoute().query.norec_emr as string
const props = withDefaults(
    defineProps<{
        pasien?: any
        registrasi?: any
        FORM_NAME?: string
        FORM_URL?: string
    }>(),
    {
        pasien: {},
        registrasi: {},
        FORM_NAME: '',
        FORM_URL: '',
    }
)
const { y } = useWindowScroll()
const isStuck = computed(() => { return y.value >= 20 })
const isLoading: any = ref(false)
const d_Pegawai: any = ref([])
const item: any = reactive({
    NOREC_PD: NOREC_PD != undefined ? NOREC_PD : '',
    NOREC_APD: '',
    registrasi: {},
    pegawaiOrder: useUserSession().getUser().id,
    selectedMenu: [false]
})
const COLLECTION: any = ref('AsesmenAwalKeperawatanRawatInap') //table mongodb
const NOREC_EMRPASIEN: any = ref('')
const input: any = ref({})
const dataSourceICD9 = ref([])
const dataSourceICD10 = ref([])
const isAktive = ref()
const setView = () => {
    useHead({
        title: props.FORM_NAME + ' - ' + import.meta.env.VITE_PROJECT,
    })
    useViewWrapper().setPageTitle(import.meta.env.VITE_PROJECT)
    useViewWrapper().setFullWidth(true)
}
const loadRiwayat = () => {
    // if (NOREC_EMRPASIEN.value == '') return
    useApi().get(
        `/emr/get-emr?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`).then((response: any) => {
            if (response.length) {
                input.value = response[0] //set ke inputan 
                if (NOREC_EMRPASIEN.value == '') {
                    NOREC_EMRPASIEN.value = response[0].emrpasienfk
                }
            }
        })
}

const simpan = () => {
    let ID = input.value.id ? input.value.id : ''

    let object: any = {}

    object = input.value
    object.pasien = H.setObjectPasien(props.pasien)
    object.registrasi = H.setObjectRegistrasi(props.registrasi)
    let json = {
        'id': ID,
        'norec_emr': NOREC_EMRPASIEN.value,
        'collection': COLLECTION.value,
        'name_form': props.FORM_NAME,
        'url_form': props.FORM_URL,
        'jenis_emr': 'asesmen_medis',
        'data': object
    }
    isLoading.value = true
    useApi().post(
    `/emr/simpan-emr`, json).then((response: any) => {
      isLoading.value = false
      NOREC_EMRPASIEN.value = response.norec_emr
      input.value.id = response.id
    }).catch((e: any) => {
      isLoading.value = false
    })
}

const diagnosa = async () => {
    await useApi().get(`emr/get-diagnosa-pasien-icd9?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD9.value = response
        // console.log(response)
    })
    await useApi().get(`emr/get-diagnosa-pasien-icd10?nocmfk=${ID_PASIEN}`).then((response) => {
        response.forEach((element: any, i: any) => {
            element.no = i + 1
            element.tglInput = H.formatDate(element.tglinputdiagnosa, 'DD-MM-YYYY')
        });
        dataSourceICD10.value = response
    })
}

const fetchPegawai = async (filter: any) => {
    const response = await useApi().get(
        `/emr/dropdown/pegawai_m?select=id,namalengkap&param_search=namalengkap&query=${filter.query}&limit=10`)
    d_Pegawai.value = response
}

const choiceScore = (e: any, i: any) => {
    let listSkor = listSkoringNyeri.value.detail

    listSkor.forEach((element: any) => {
        if (element.descNilai == e.descNilai) {
            input.value.skoringNyeri = e.descNilai
        }
    });
    isAktive.value = i
}

const indikatorRangeNilai = (e: any) => {
    let tidakBeresiko = {
        "keterangan": "Tidak Beresiko",
        "rangeNilai": 24
    }
    let resikoRendah = {
        "keterangan": "Resiko Rendah",
        "rangeNilai": 50,
    }
    let resikoTinggi = {
        "keterangan": "Resiko Tinggi",
        "rangeNilai": 51,
    }

    ketJumlahPoin.value.forEach((element: any) => {
        if (e <= 24 && e <= element.value.rangeNilai) {
            input.value.tingkatResiko = tidakBeresiko
        }
        else if (e <= 50 && e <= element.value.rangeNilai) {
            input.value.tingkatResiko = resikoRendah
        }
        else if (e > 50 && e > element.value.rangeNilai) {
            input.value.tingkatResiko = resikoTinggi
        }

    })
}
const kembaliKeun = () => {
    window.history.back()
}

const print = async () => {
    // await useApi().get(`emr/cetak?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
    H.printBlade(`emr/cetak-asesmen-awal-keper-ri?nocmfk=${ID_PASIEN}&norec_pd=${props.registrasi.norec_pd}&collection=${COLLECTION.value}&emrpasienfk=${NOREC_EMRPASIEN.value}`)
}


watch(() => [
    input.value.mata,
    input.value.verbal,
    input.value.pergerakan,
], () => {
    let poin1 = input.value.mata ? parseInt(input.value.mata.poin) : 0
    let poin2 = input.value.verbal ? parseInt(input.value.verbal.poin) : 0
    let poin3 = input.value.pergerakan ? parseInt(input.value.pergerakan.poin) : 0

    const total = poin1 + poin2 + poin3
    input.value.jumlahNilai = total

})

watch(() => [
    input.value.penurunanBB,
    input.value.penurunanNafsuMakan,
], () => {

    let poin1 = input.value.penurunanBB ? parseInt(input.value.penurunanBB.poin) : 0
    let poin2 = input.value.penurunanNafsuMakan ? parseInt(input.value.penurunanNafsuMakan.poin) : 0

    const total = poin1 + poin2
    input.value.totalNilaiMST = total

})

watch(() => [
    input.value.pasienPernahJatuh,
    input.value.berpenyakitBanyak,
    input.value.alatBantuJalan,
    input.value.terpasangInfus,
    input.value.gayaBerjalan,
    input.value.statusMental,
], () => {

    let poin1 = input.value.pasienPernahJatuh ? parseInt(input.value.pasienPernahJatuh.poin) : 0
    let poin2 = input.value.berpenyakitBanyak ? parseInt(input.value.berpenyakitBanyak.poin) : 0
    let poin3 = input.value.alatBantuJalan ? parseInt(input.value.alatBantuJalan.poin) : 0
    let poin4 = input.value.terpasangInfus ? parseInt(input.value.terpasangInfus.poin) : 0
    let poin5 = input.value.gayaBerjalan ? parseInt(input.value.gayaBerjalan.poin) : 0
    let poin6 = input.value.statusMental ? parseInt(input.value.statusMental.poin) : 0

    const total = poin1 + poin2 + poin3 + poin4 + poin5 + poin6
    input.value.totalNilaiRJM = total
    indikatorRangeNilai(total)
})

watch(() => [
    input.value.makanAtauPakaiBaju,
    input.value.berjalan,
    input.value.mandi,
], () => {

    let poin1 = input.value.makanAtauPakaiBaju ? parseInt(input.value.makanAtauPakaiBaju.poin) : 0
    let poin2 = input.value.berjalan ? parseInt(input.value.berjalan.poin) : 0
    let poin3 = input.value.mandi ? parseInt(input.value.mandi.poin) : 0

    const total = poin1 + poin2 + poin3
    input.value.totalNilaiPmrksADL = total
})
watch(() => [
    input.value.kondisiFisik,
    input.value.kondisiMental,
    input.value.aktifitas,
    input.value.mobilitas,
    input.value.inkkonteneasi,
], () => {

    let poin1 = input.value.kondisiFisik ? parseInt(input.value.kondisiFisik.poin) : 0
    let poin2 = input.value.kondisiMental ? parseInt(input.value.kondisiMental.poin) : 0
    let poin3 = input.value.aktifitas ? parseInt(input.value.aktifitas.poin) : 0
    let poin4 = input.value.mobilitas ? parseInt(input.value.mobilitas.poin) : 0
    let poin5 = input.value.inkkonteneasi ? parseInt(input.value.inkkonteneasi.poin) : 0

    const total = poin1 + poin2 + poin3 + poin4 + poin5
    input.value.totalNilaiPmrksNortonScale = total
})


setView()
diagnosa()
loadRiwayat()
</script>

<style lang="scss">
.v-avatar.is-medium.active {
    padding: 3px;
    background: var(--success);
    display: inline-table !important;
}

.centerCheck {
    margin-top: 22px !important;
}

h1.emr {
    font-weight: bold;
}

table.assesment-awal {
    width: 100%;
}

.assesment-awal tr,
th,
td {
    border: 1px solid;
}

.assesment-awal td {
    padding: 7px;
}
</style>